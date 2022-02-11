<?php

class ControllerApiCustomerProducts extends Controller {

    private $error = [];

    public function getProducts() {
        $json = [];

        if ($this->request->get['parent'] != NULL && $this->request->get['parent'] > 0) {
            $customer_details = $this->db->query('SELECT customer_category FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . $this->request->get['parent'] . "' AND status = '1'");
        } else {
            $customer_details = $this->db->query('SELECT customer_category FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . $this->request->get['customer_id'] . "' AND status = '1'");
        }
        $this->session->data['customer_category'] = isset($customer_details->row['customer_category']) ? $customer_details->row['customer_category'] : null;
        $log = new Log('error.log');
        $log->write('Session category check');
        $log->write($this->session->data['customer_category']);
        $log->write('Session category check');

        //  echo "<pre>";print_r($_SESSION['customer_category']);die;

        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/products');

        $this->load->model('assets/category');

        $this->load->model('assets/product');

        $this->load->model('tool/image');

        if (/* isset($this->request->get['page']) && */isset($this->request->get['store_id']) && isset($this->request->get['category'])) {
            if (isset($this->request->get['filter'])) {
                $filter = $this->request->get['filter'];
            } else {
                $filter = '';
            }

            if (isset($this->request->get['sort'])) {
                $sort = $this->request->get['sort'];
            } else {
                $sort = 'p.sort_order';
            }

            if (isset($this->request->get['order'])) {
                $order = $this->request->get['order'];
            } else {
                $order = 'ASC';
            }

            if (isset($this->request->get['page'])) {
                $page = $this->request->get['page'];
            } else {
                $page = 1;
            }

            if (isset($this->request->get['limit'])) {
                $limit = $this->request->get['limit'];
            } else {
                $limit = $this->config->get('config_app_product_limit');
            }

            if (isset($this->request->get['store_id'])) {
                $store_id = $this->request->get['store_id'];
            }

            $store_info = $this->model_tool_image->getStore($store_id);

            if (!$store_info) {
                // store not found
                //echo "r";
                $json['status'] = 10005;

                $json['message'][] = ['type' => '', 'body' => $this->language->get('store_not_found')];
            } else {
                if (isset($this->request->get['category'])) {
                    $url = '';

                    if (isset($this->request->get['sort'])) {
                        $url .= '&sort=' . $this->request->get['sort'];
                    }

                    if (isset($this->request->get['order'])) {
                        $url .= '&order=' . $this->request->get['order'];
                    }

                    if (isset($this->request->get['limit'])) {
                        $url .= '&limit=' . $this->request->get['limit'];
                    }

                    $path = '';

                    $parts = explode('_', (string) $this->request->get['category']);

                    $category_id = (int) array_pop($parts);
                    foreach ($parts as $path_id) {
                        if (!$path) {
                            $path = (int) $path_id;
                        } else {
                            $path .= '_' . (int) $path_id;
                        }

                        $category_info = $this->model_assets_category->getCategory($path_id);
                    }
                } else {
                    $category_id = 0;
                }

                $category_info = $this->model_assets_category->getCategory($category_id);

                //$this->config->set('config_language_id',2);
                //echo "<pre>";print_r($category_info);die;
                if ($category_info) {
                    $data['title'] = empty($category_info['meta_title']) ? $category_info['name'] : $category_info['meta_title'];

                    if ($category_info['image']) {
                        $data['thumb'] = $this->model_tool_image->resize($category_info['image'], $this->config->get('config_app_image_category_width'), $this->config->get('config_app_image_category_height'));
                    } else {
                        $data['thumb'] = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_app_image_category_width'), $this->config->get('config_app_image_category_height'));
                    }

                    $data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');

                    $url = '';

                    if (isset($this->request->get['filter'])) {
                        $url .= '&filter=' . $this->request->get['filter'];
                    }

                    if (isset($this->request->get['sort'])) {
                        $url .= '&sort=' . $this->request->get['sort'];
                    }

                    if (isset($this->request->get['order'])) {
                        $url .= '&order=' . $this->request->get['order'];
                    }

                    if (isset($this->request->get['limit'])) {
                        $url .= '&limit=' . $this->request->get['limit'];
                    }

                    $data['categories'] = [];

                    $results = $this->model_assets_category->getCategories($category_id);

                    $product_total = 0;
                    //echo "<pre>";print_r($results);die;
                    //top category
                    if ($results) {
                        foreach ($results as $result) {
                            $filter_data = [
                                'filter_category_id' => $result['category_id'],
                                'filter_sub_category' => true,
                                'start' => 0,
                                'limit' => $limit,
                            ];

                            if (!empty($result['image'])) {
                                $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_app_image_category_width'), $this->config->get('config_app_image_category_height'));
                            } else {
                                $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_app_image_category_width'), $this->config->get('config_app_image_category_height'));
                            }

                            $data['categories'][] = [
                                'name' => htmlspecialchars_decode($result['name']),
                                'products' => $this->getProductsFnNew($filter_data, $store_id),
                                'href' => $this->url->link('product/category', 'category=' . $this->request->get['category'] . '_' . $result['category_id'] . $url),
                                'next_category_call_id' => $this->request->get['category'] . '_' . $result['category_id'],
                                'thumb' => $image,
                            ];
                        }
                    } else {
                        $data['products'] = [];

                        $filter_data = [
                            'filter_category_id' => $category_id,
                            'filter_filter' => $filter,
                            'sort' => $sort,
                            'order' => $order,
                            'start' => ($page - 1) * $limit,
                            'limit' => $limit,
                            'group_by' => true,
                        ];

                        $filter_data['store_id'] = $store_id;

                        $product_total = $this->model_assets_product->getTotalProductsByApiNew($filter_data);

                        $data['products'] = $this->getProductsFnNew($filter_data, $store_id, $groupByName = true);

                        $url = '';

                        if (isset($this->request->get['filter'])) {
                            $url .= '&filter=' . $this->request->get['filter'];
                        }

                        if (isset($this->request->get['sort'])) {
                            $url .= '&sort=' . $this->request->get['sort'];
                        }

                        if (isset($this->request->get['order'])) {
                            $url .= '&order=' . $this->request->get['order'];
                        }

                        if (isset($this->request->get['limit'])) {
                            $url .= '&limit=' . $this->request->get['limit'];
                        }

                        $pagination = new Pagination();
                        $pagination->total = $product_total;
                        $pagination->page = $page;
                        $pagination->limit = $limit;
                        $pagination->url = $this->url->link('product/category', 'category=' . $this->request->get['category'] . $url . '&page={page}');

                        //$data['pagination'] = $pagination->render();

                        $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));
                    }

                    $url = '';

                    if (isset($this->request->get['filter'])) {
                        $url .= '&filter=' . $this->request->get['filter'];
                    }

                    if (isset($this->request->get['limit'])) {
                        $url .= '&limit=' . $this->request->get['limit'];
                    }

                    /* $data['sorts'] = array();

                      $data['sorts'][] = array(
                      'text' => $this->language->get('text_default'),
                      'value' => 'p.sort_order-ASC',
                      'href' => $this->url->link('product/category', 'category=' . $this->request->get['category'] . '&sort=p.sort_order&order=ASC' . $url)
                      );

                      $data['sorts'][] = array(
                      'text' => $this->language->get('text_name_asc'),
                      'value' => 'pd.name-ASC',
                      'href' => $this->url->link('product/category', 'category=' . $this->request->get['category'] . '&sort=pd.name&order=ASC' . $url)
                      );

                      $data['sorts'][] = array(
                      'text' => $this->language->get('text_name_desc'),
                      'value' => 'pd.name-DESC',
                      'href' => $this->url->link('product/category', 'category=' . $this->request->get['category'] . '&sort=pd.name&order=DESC' . $url)
                      );

                      $data['sorts'][] = array(
                      'text' => $this->language->get('text_price_asc'),
                      'value' => 'p.price-ASC',
                      'href' => $this->url->link('product/category', 'category=' . $this->request->get['category'] . '&sort=p.price&order=ASC' . $url)
                      );

                      $data['sorts'][] = array(
                      'text' => $this->language->get('text_price_desc'),
                      'value' => 'p.price-DESC',
                      'href' => $this->url->link('product/category', 'category=' . $this->request->get['category'] . '&sort=p.price&order=DESC' . $url)
                      );

                      if ($this->config->get('config_review_status')) {
                      $data['sorts'][] = array(
                      'text' => $this->language->get('text_rating_desc'),
                      'value' => 'rating-DESC',
                      'href' => $this->url->link('product/category', 'category=' . $this->request->get['category'] . '&sort=rating&order=DESC' . $url)
                      );

                      $data['sorts'][] = array(
                      'text' => $this->language->get('text_rating_asc'),
                      'value' => 'rating-ASC',
                      'href' => $this->url->link('product/category', 'category=' . $this->request->get['category'] . '&sort=rating&order=ASC' . $url)
                      );
                      }

                      $data['sorts'][] = array(
                      'text' => $this->language->get('text_model_asc'),
                      'value' => 'p.model-ASC',
                      'href' => $this->url->link('product/category', 'category=' . $this->request->get['category'] . '&sort=p.model&order=ASC' . $url)
                      );

                      $data['sorts'][] = array(
                      'text' => $this->language->get('text_model_desc'),
                      'value' => 'p.model-DESC',
                      'href' => $this->url->link('product/category', 'category=' . $this->request->get['category'] . '&sort=p.model&order=DESC' . $url)
                      ); */

                    $url = '';

                    if (isset($this->request->get['filter'])) {
                        $url .= '&filter=' . $this->request->get['filter'];
                    }

                    if (isset($this->request->get['sort'])) {
                        $url .= '&sort=' . $this->request->get['sort'];
                    }

                    if (isset($this->request->get['order'])) {
                        $url .= '&order=' . $this->request->get['order'];
                    }

                    /* $data['limits'] = array();

                      $limits = array_unique(array($this->config->get('config_app_product_limit'), 25, 50, 75, 100));

                      sort($limits);

                      foreach ($limits as $value) {
                      $data['limits'][] = array(
                      'text' => $value,
                      'value' => $value,
                      'href' => $this->url->link('product/category', 'category=' . $this->request->get['category'] . $url . '&limit=' . $value),
                      );
                      } */

                    $data['sort'] = $sort;
                    $data['order'] = $order;
                    $data['limit'] = $limit;
                    $data['total_product'] = $product_total;

                    if (isset($this->session->data['warning'])) {
                        $data['warning'] = $this->session->data['warning'];
                        unset($this->session->data['warning']);
                    } else {
                        $data['warning'] = '';
                    }

                    if ($this->request->server['HTTPS']) {
                        $server = $this->config->get('config_ssl');
                    } else {
                        $server = $this->config->get('config_url');
                    }

                    $data['base'] = $server;

                    $json['data'] = $data;
                } else {
                    //category not found
                    $json['status'] = 10008;

                    $json['message'][] = ['type' => '', 'body' => $this->language->get('text_category_not_found')];
                }
            }
        } else {
            $store_id = $this->request->get['store_id'];
            $filter_data['store_id'] = $this->request->get['store_id'];
            $filter_data['limit'] = $this->request->get['limit'];
            $data['products'] = $this->getProductsFnNew($filter_data, $store_id);
            $json['status'] = 200;
            $json['data'] = $data;

            /* $json['status'] = 10010;

              $json['message'][] = ['type' =>  $this->language->get('text_data_missing') , 'body' =>  $this->language->get('text_data_missing_detail') ];

              http_response_code(400);
             */
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getProductCollectionProducts() {
        $json = [];

        $this->load->language('api/products');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        if (isset($this->request->get['store_id']) && isset($this->request->get['product_collection_id'])) {
            $this->load->model('assets/category');

            $this->load->model('assets/product');

            $this->load->model('tool/image');

            if (isset($this->request->get['filter'])) {
                $filter = $this->request->get['filter'];
            } else {
                $filter = '';
            }

            if (isset($this->request->get['sort'])) {
                $sort = $this->request->get['sort'];
            } else {
                $sort = 'p.sort_order';
            }

            if (isset($this->request->get['order'])) {
                $order = $this->request->get['order'];
            } else {
                $order = 'ASC';
            }

            if (isset($this->request->get['page'])) {
                $page = $this->request->get['page'];
            } else {
                $page = 1;
            }

            if (isset($this->request->get['limit'])) {
                $limit = $this->request->get['limit'];
            } else {
                $limit = $this->config->get('config_product_limit');
            }

            $store_id = $this->request->get['store_id'];

            $store_info = $this->model_tool_image->getStore($store_id);

            $product_collection_id = $this->request->get['product_collection_id'];

            $this->load->model('assets/product');

            //echo "<pre>";print_r($product_collection_id);die;

            $product_collection_info = $this->model_assets_product->getProductCollectionDescriptions($product_collection_id);

            //echo "<pre>";print_r($product_collection_info);die;
            if ($product_collection_info) {
                $title = empty($product_collection_info['meta_title']) ? $product_collection_info['name'] : $product_collection_info['meta_title'];

                $data['name'] = htmlspecialchars_decode(!empty($product_collection_info['name']) ? $product_collection_info['name'] : '');

                $url = '';

                if (isset($this->request->get['filter'])) {
                    $url .= '&filter=' . $this->request->get['filter'];
                }

                if (isset($this->request->get['sort'])) {
                    $url .= '&sort=' . $this->request->get['sort'];
                }

                if (isset($this->request->get['order'])) {
                    $url .= '&order=' . $this->request->get['order'];
                }

                if (isset($this->request->get['limit'])) {
                    $url .= '&limit=' . $this->request->get['limit'];
                }

                $data['products'] = [];

                $filter_data = [
                    'filter_product_collection_id' => $product_collection_id,
                    'filter_filter' => $filter,
                    'sort' => $sort,
                    'order' => $order,
                    'start' => ($page - 1) * $limit,
                    'limit' => $limit,
                    'store_id' => $store_id,
                ];

                $product_total = $this->model_assets_product->getTotalCollectionProductsApi($filter_data);

                //echo "<pre>";print_r($product_total);die;
                $data['products'] = $this->getCollectionProducts($filter_data);

                //echo "<pre>";print_r($data['products']);die;

                $pagination = new Pagination();
                $pagination->total = $product_total;
                $pagination->page = $page;
                $pagination->limit = $limit;
                $pagination->url = $this->url->link('product/collection', 'product_collection_id=' . $this->request->get['product_collection_id'] . $url . '&page={page}');

                $data['pagination'] = $pagination->render();

                /* print_r($product_total."ss");
                  print_r($page."ss");
                  print_r($limit."ss");
                  echo "<pre>";print_r($data['pagination']);die; */
                $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

                $data['total_product'] = $product_total;

                $json['data'] = $data;
                //return $data;
            } else {
                // not found product_collection details

                $json['status'] = 10012;

                $json['message'][] = ['type' => $this->language->get('text_not_found'), 'body' => $this->language->get('text_product_collection_not_found')];
            }
        } else {
            $json['status'] = 10010;

            $json['message'][] = ['type' => $this->language->get('text_data_missing'), 'body' => $this->language->get('text_data_missing_detail')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getCollectionProducts($filter_data) {
        $this->load->model('assets/product');
        $this->load->model('tool/image');

        $store_id = $filter_data['store_id'];

        $results = $this->model_assets_product->getCollectionProductsApi($filter_data);

        $data['products'] = [];

        foreach ($results as $result) {
            // if qty less then 1 dont show product
            if ($result['quantity'] <= 0) {
                continue;
            }

            if (file_exists(DIR_IMAGE . $result['image'])) {
                $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
            }

            $s_price = 0;
            $o_price = 0;

            //$result['special_price'] = 10;
            if (!$this->config->get('config_inclusiv_tax')) {
                //get price html
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    //$price = $this->currency->format( $this->tax->calculate( $result['price'], $result['tax_class_id'], $this->config->get( 'config_tax' ) ) );
                    $price = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));

                    $o_price = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
                } else {
                    $price = false;
                }
                if ((float) $result['special_price']) {
                    //$special_price = $this->currency->format( $this->tax->calculate( $result['special_price'], $result['tax_class_id'], $this->config->get( 'config_tax' ) ) );
                    $special_price = $this->tax->calculate($result['special_price'], $result['tax_class_id'], $this->config->get('config_tax'));

                    $s_price = $this->tax->calculate($result['special_price'], $result['tax_class_id'], $this->config->get('config_tax'));
                } else {
                    $special_price = false;
                }
            } else {
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    //$price = $result['price'];
                    $price = $this->currency->formatWithoutCurrency($result['price']);
                } else {
                    $price = $result['price'];
                }

                if ((float) $result['special_price']) {
                    //$special_price =  $result['special_price'];
                    $special_price = $this->currency->formatWithoutCurrency($result['special_price']);
                } else {
                    $special_price = $result['special_price'];
                }

                $s_price = $result['special_price'];
                $o_price = $result['price'];
            }

            //$result['name'] = strlen($result['name']) > 27 ? substr($result['name'],0,27)."..." : $result['name'];
            $name = htmlspecialchars_decode($result['name']);
            //$name .= str_repeat('&nbsp;',30 - strlen($result['name']));

            $percent_off = null;
            if (isset($s_price) && isset($o_price) && 0 != $o_price && 0 != $s_price) {
                $percent_off = (($o_price - $s_price) / $o_price) * 100;
            }

            if (is_null($special_price) || !($special_price + 0)) {
                //$special_price = 0;
                $special_price = $price;
            }

            $data['products'][] = [
                /* 'key' => $key,
                  'qty_in_cart' => $qty_in_cart, */
                'variations' => $this->model_assets_product->getApiVariations($result['product_store_id']),
                'store_product_variation_id' => 0,
                'product_id' => $result['product_id'],
                'product_store_id' => $result['product_store_id'],
                'default_variation_name' => $result['default_variation_name'],
                'thumb' => $image,
                'name' => $name,
                'unit' => $result['unit'],
                'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
                'price' => $price,
                'special' => $special_price,
                'percent_off' => number_format($percent_off, 0),
                'left_symbol_currency' => $this->currency->getSymbolLeft(),
                'right_symbol_currency' => $this->currency->getSymbolRight(),
                'tax' => $result['tax_percentage'],
                //'minimum' => $result['min_quantity'] > 0 ? $result['min_quantity'] : 1,
                'max_qty' => $result['min_quantity'] > 0 ? $result['min_quantity'] : $result['quantity'],
                'rating' => 0,
                'href' => $this->url->link('product/product', '&product_store_id=' . $result['product_store_id']),
                'produce_type' => $result['produce_type'],
            ];
        }

        return $data['products'];
    }

    public function addProductSearch() {
        $json = [];

        $log = new Log('error.log');
        $log->write('api/getProductSearch');

        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/products');

        $this->load->model('assets/category');

        $this->load->model('assets/product');

        $this->load->model('tool/image');

        if (isset($this->request->post['store_id'])) {
            if (isset($this->request->post['store_id'])) {
                $store_id = $this->request->post['store_id'];
            }

            $store_info = $this->model_tool_image->getStore($store_id);

            if (!$store_info) {
                // store not found
                //echo "r";
                $json['status'] = 10005;

                $json['message'][] = ['type' => '', 'body' => $this->language->get('store_not_found')];
            } else {
                if (isset($this->request->post['search'])) {
                    $search = $this->request->post['search'];
                } else {
                    $search = '';
                }

                if (isset($this->request->post['category_id'])) {
                    $category_id = $this->request->post['category_id'];
                } else {
                    $category_id = 0;
                }

                if (isset($this->request->post['sub_category'])) {
                    $sub_category = $this->request->post['sub_category'];
                } else {
                    $sub_category = '';
                }

                if (isset($this->request->post['sort'])) {
                    $sort = $this->request->post['sort'];
                } else {
                    $sort = 'p.sort_order';
                }

                if (isset($this->request->post['order'])) {
                    $order = $this->request->post['order'];
                } else {
                    $order = 'ASC';
                }

                if (isset($this->request->post['page'])) {
                    $page = $this->request->post['page'];
                } else {
                    $page = 1;
                }

                if (isset($this->request->post['limit'])) {
                    $limit = $this->request->post['limit'];
                } else {
                    $limit = $this->config->get('config_app_product_limit');
                }

                $url = '';

                if (isset($this->request->post['search'])) {
                    $url .= '&search=' . urlencode(html_entity_decode($this->request->post['search'], ENT_QUOTES, 'UTF-8'));
                }

                if (isset($this->request->post['tag'])) {
                    $url .= '&tag=' . urlencode(html_entity_decode($this->request->post['tag'], ENT_QUOTES, 'UTF-8'));
                }

                if (isset($this->request->post['description'])) {
                    $url .= '&description=' . $this->request->post['description'];
                }

                if (isset($this->request->post['category_id'])) {
                    $url .= '&category_id=' . $this->request->post['category_id'];
                }

                if (isset($this->request->post['sub_category'])) {
                    $url .= '&sub_category=' . $this->request->post['sub_category'];
                }

                if (isset($this->request->post['sort'])) {
                    $url .= '&sort=' . $this->request->post['sort'];
                }

                if (isset($this->request->post['order'])) {
                    $url .= '&order=' . $this->request->post['order'];
                }

                if (isset($this->request->post['page'])) {
                    $url .= '&page=' . $this->request->post['page'];
                }

                if (isset($this->request->post['limit'])) {
                    $url .= '&limit=' . $this->request->post['limit'];
                }

                $log->write($url);

                $this->load->model('assets/category');

                //$this->config->set('config_language_id',2);
                // 3 Level Category Search
                /* $data['categories'] = array();

                  $categories_1 = $this->model_assets_category->getCategories(0);

                  foreach ($categories_1 as $category_1) {
                  $level_2_data = array();

                  $categories_2 = $this->model_assets_category->getCategories($category_1['category_id']);

                  foreach ($categories_2 as $category_2) {
                  $level_3_data = array();

                  $categories_3 = $this->model_assets_category->getCategories($category_2['category_id']);

                  foreach ($categories_3 as $category_3) {
                  $level_3_data[] = array(
                  'category_id' => $category_3['category_id'],
                  'name' => $category_3['name'],
                  );
                  }

                  $level_2_data[] = array(
                  'category_id' => $category_2['category_id'],
                  'name' => $category_2['name'],
                  'children' => $level_3_data
                  );
                  }

                  $data['categories'][] = array(
                  'category_id' => $category_1['category_id'],
                  'name' => $category_1['name'],
                  'children' => $level_2_data
                  );
                  } */

                $data['products'] = [];

                if (isset($this->request->post['search']) || isset($this->request->post['tag'])) {
                    $filter_data = [
                        'filter_name' => urldecode($search),
                        'filter_name_test' => urldecode($search),
                        'filter_tag' => '',
                        'filter_category_id' => $category_id,
                        'filter_sub_category' => $sub_category,
                        'sort' => $sort,
                        'order' => $order,
                        'start' => ($page - 1) * $limit,
                        'limit' => $limit,
                    ];

                    $filter_data['store_id'] = $store_id;

                    $product_total = $this->model_assets_product->getTotalProductsByApi($filter_data);

                    $log = new Log('error.log');
                    $log->write('api/search');
                    $log->write($filter_data);

                    $results = $this->model_assets_product->getProductsByApi($filter_data);

                    foreach ($results as $result) {
                        if (file_exists(DIR_IMAGE . $result['image'])) {
                            $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
                        } else {
                            $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
                        }

                        //$result['special_price'] = 10;

                        $s_price = 0;
                        $o_price = 0;

                        if (!$this->config->get('config_inclusiv_tax')) {
                            //get price html
                            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                                //$price = $this->currency->format( $this->tax->calculate( $result['price'], $result['tax_class_id'], $this->config->get( 'config_tax' ) ) );
                                $price = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));

                                $o_price = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
                            } else {
                                $price = false;
                            }
                            if ((float) $result['special_price']) {
                                //$special_price = $this->currency->format( $this->tax->calculate( $result['special_price'], $result['tax_class_id'], $this->config->get( 'config_tax' ) ) );
                                $special_price = $this->tax->calculate($result['special_price'], $result['tax_class_id'], $this->config->get('config_tax'));

                                $s_price = $this->tax->calculate($result['special_price'], $result['tax_class_id'], $this->config->get('config_tax'));
                            } else {
                                $special_price = false;
                            }
                        } else {
                            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                                //$price = $result['price'];
                                $price = $this->currency->formatWithoutCurrency($result['price']);
                            } else {
                                $price = $result['price'];
                            }

                            if ((float) $result['special_price']) {
                                //$special_price = $result['special_price'];
                                $special_price = $this->currency->formatWithoutCurrency($result['special_price']);
                            } else {
                                $special_price = $result['special_price'];
                            }

                            $s_price = $result['special_price'];
                            $o_price = $result['price'];
                        }

                        //get qty in cart
                        $key = base64_encode(serialize(['product_store_id' => (int) $result['product_store_id'], 'store_id' => $store_id]));

                        if (isset($this->session->data['cart'][$key])) {
                            $qty_in_cart = $this->session->data['cart'][$key]['quantity'];
                        } else {
                            $qty_in_cart = 0;
                        }

                        //$result['name'] = strlen($result['name']) > 27 ? substr($result['name'],0,27)."..." : $result['name'];
                        $name = $result['name'];
                        //$name .= str_repeat('&nbsp;',30 - strlen($result['name']));

                        $unit = $result['unit'] ? $result['unit'] : false;

                        $percent_off = null;
                        if (isset($s_price) && isset($o_price) && 0 != $o_price && 0 != $s_price) {
                            $percent_off = (($o_price - $s_price) / $o_price) * 100;
                        }

                        if (is_null($special_price) || !($special_price + 0)) {
                            //$special_price = 0;
                            $special_price = $price;
                        }

                        /* $temp['name'] = htmlspecialchars_decode($tempProduct['name'])." - ".$tempProduct['unit'];
                          //$temp['name'] = $tempProduct['name']." - ".$tempProduct['unit'];
                          $temp['unit'] = $tempProduct['unit'];
                          $temp['only_name'] = htmlspecialchars_decode($tempProduct['name']);
                          $temp['product_id'] = $tempProduct['product_id'];
                          $temp['product_store_id'] = $tempProduct['product_store_id']; */
                        $price = strval($price);
                        $special_price = strval($special_price);
                        $data['products'][] = [
                            'key' => $key,
                            'qty_in_cart' => $qty_in_cart,
                            'variations' => $this->model_assets_product->getApiVariations($result['product_store_id']),
                            'store_product_variation_id' => 0,
                            'product_id' => $result['product_id'],
                            'product_store_id' => $result['product_store_id'],
                            'default_variation_name' => $result['default_variation_name'],
                            'thumb' => $image,
                            'name' => htmlspecialchars_decode($name),
                            'unit' => $unit,
                            'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
                            'price' => $price,
                            'special' => $special_price,
                            'percent_off' => number_format($percent_off, 0),
                            'left_symbol_currency' => $this->currency->getSymbolLeft(),
                            'right_symbol_currency' => $this->currency->getSymbolRight(),
                            'tax' => $result['tax_percentage'],
                            //'minimum' => $result['min_quantity'] > 0 ? $result['min_quantity'] : 1,
                            'max_qty' => $result['min_quantity'] > 0 ? $result['min_quantity'] : $result['quantity'],
                            'rating' => 0,
                            'href' => $this->url->link('product/product', '&product_store_id=' . $result['product_store_id']),
                            'produce_type' => $result['produce_type'],
                        ];
                    }

                    $url = '';

                    if (isset($this->request->post['search'])) {
                        $url .= '&search=' . urlencode(html_entity_decode($this->request->post['search'], ENT_QUOTES, 'UTF-8'));
                    }

                    if (isset($this->request->post['tag'])) {
                        $url .= '&tag=' . urlencode(html_entity_decode($this->request->post['tag'], ENT_QUOTES, 'UTF-8'));
                    }

                    if (isset($this->request->post['description'])) {
                        $url .= '&description=' . $this->request->post['description'];
                    }

                    if (isset($this->request->post['category_id'])) {
                        $url .= '&category_id=' . $this->request->post['category_id'];
                    }

                    if (isset($this->request->post['sub_category'])) {
                        $url .= '&sub_category=' . $this->request->post['sub_category'];
                    }

                    if (isset($this->request->post['limit'])) {
                        $url .= '&limit=' . $this->request->post['limit'];
                    }

                    $url = '';

                    if (isset($this->request->post['search'])) {
                        $url .= '&search=' . urlencode(html_entity_decode($this->request->post['search'], ENT_QUOTES, 'UTF-8'));
                    }

                    if (isset($this->request->post['tag'])) {
                        $url .= '&tag=' . urlencode(html_entity_decode($this->request->post['tag'], ENT_QUOTES, 'UTF-8'));
                    }

                    if (isset($this->request->post['description'])) {
                        $url .= '&description=' . $this->request->post['description'];
                    }

                    if (isset($this->request->post['category_id'])) {
                        $url .= '&category_id=' . $this->request->post['category_id'];
                    }

                    if (isset($this->request->post['sub_category'])) {
                        $url .= '&sub_category=' . $this->request->post['sub_category'];
                    }

                    if (isset($this->request->post['sort'])) {
                        $url .= '&sort=' . $this->request->post['sort'];
                    }

                    if (isset($this->request->post['order'])) {
                        $url .= '&order=' . $this->request->post['order'];
                    }

                    /* $data['limits'] = array();

                      $limits = array_unique(array($this->config->get('config_app_product_limit'), 25, 50, 75, 100));

                      sort($limits);

                      foreach ($limits as $value) {
                      $data['limits'][] = array(
                      'text' => $value,
                      'value' => $value,
                      'href' => $this->url->link('product/search', $url . '&limit=' . $value)
                      );
                      } */

                    $url = '';

                    if (isset($this->request->post['search'])) {
                        $url .= '&search=' . urlencode(html_entity_decode($this->request->post['search'], ENT_QUOTES, 'UTF-8'));
                    }

                    if (isset($this->request->post['tag'])) {
                        $url .= '&tag=' . urlencode(html_entity_decode($this->request->post['tag'], ENT_QUOTES, 'UTF-8'));
                    }

                    if (isset($this->request->post['description'])) {
                        $url .= '&description=' . $this->request->post['description'];
                    }

                    if (isset($this->request->post['category_id'])) {
                        $url .= '&category_id=' . $this->request->post['category_id'];
                    }

                    if (isset($this->request->post['sub_category'])) {
                        $url .= '&sub_category=' . $this->request->post['sub_category'];
                    }

                    if (isset($this->request->post['sort'])) {
                        $url .= '&sort=' . $this->request->post['sort'];
                    }

                    if (isset($this->request->post['order'])) {
                        $url .= '&order=' . $this->request->post['order'];
                    }

                    if (isset($this->request->post['limit'])) {
                        $url .= '&limit=' . $this->request->post['limit'];
                    }

                    $pagination = new Pagination();
                    $pagination->total = $product_total;
                    $pagination->page = $page;
                    $pagination->limit = $limit;
                    $pagination->url = $this->url->link('product/search', $url . '&page={page}');

                    //$data['pagination'] = $pagination->render();

                    $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));
                }

                $data['search'] = $search;
                // $data['category_id'] = $category_id;
                // $data['sub_category'] = $sub_category;

                $data['sort'] = $sort;
                $data['total_product'] = $product_total;

                $data['order'] = $order;
                $data['limit'] = $limit;

                if ($this->request->server['HTTPS']) {
                    $server = $this->config->get('config_ssl');
                } else {
                    $server = $this->config->get('config_url');
                }

                $data['base'] = $server;

                $json['data'] = $data;
            }
        } else {
            $json['status'] = 10010;

            $json['message'][] = ['type' => $this->language->get('text_data_missing'), 'body' => $this->language->get('text_data_missing_detail')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getProductSearch() {
        $cachePrice_data = $this->cache->get('category_price_data');
        $log = new Log('error.log');
        $log->write('data');
        $log->write($this->request->get);
        $log->write($this->customer->getId());
        $log->write($this->customer->getCustomerCategory());
        $log->write($this->customer->getPaymentTerms());
        $log->write('data');
        $json = [];

        if ($this->request->get['parent'] != NULL && $this->request->get['parent'] > 0) {
            $customer_details = $this->db->query('SELECT customer_category FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . $this->request->get['parent'] . "' AND status = '1'");
        } else {
            $customer_details = $this->db->query('SELECT customer_category FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . $this->request->get['customer_id'] . "' AND status = '1'");
        }
        $this->session->data['customer_category'] = isset($customer_details->row['customer_category']) ? $customer_details->row['customer_category'] : null;

        $this->session->data['customer_category'] = !isset($this->session->data['customer_category']) || $this->session->data['customer_category'] == NULL ? $this->customer->getCustomerCategory() : $customer_details->row['customer_category'];
        $log->write($this->session->data['customer_category']);
        $log = new Log('error.log');
        $log->write('api/getProductSearch');

        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/products');

        $this->load->model('assets/category');

        $this->load->model('assets/product');

        $this->load->model('tool/image');

        if (isset($this->request->get['store_id'])) {
            if (isset($this->request->get['store_id'])) {
                $store_id = $this->request->get['store_id'];
            }

            $store_info = $this->model_tool_image->getStore($store_id);

            if (!$store_info) {
                // store not found
                //echo "r";
                $json['status'] = 10005;

                $json['message'][] = ['type' => '', 'body' => $this->language->get('store_not_found')];
            } else {
                if (isset($this->request->get['search'])) {
                    $search = $this->request->get['search'];
                } else {
                    $search = '';
                }

                if (isset($this->request->get['category_id'])) {
                    $category_id = $this->request->get['category_id'];
                } else {
                    $category_id = 0;
                }

                if (isset($this->request->get['sub_category'])) {
                    $sub_category = $this->request->get['sub_category'];
                } else {
                    $sub_category = '';
                }

                if (isset($this->request->get['sort'])) {
                    $sort = $this->request->get['sort'];
                } else {
                    $sort = 'p.sort_order';
                }

                if (isset($this->request->get['order'])) {
                    $order = $this->request->get['order'];
                } else {
                    $order = 'ASC';
                }

                if (isset($this->request->get['page'])) {
                    $page = $this->request->get['page'];
                } else {
                    $page = 1;
                }

                if (isset($this->request->get['limit'])) {
                    $limit = $this->request->get['limit'];
                } else {
                    $limit = $this->config->get('config_app_product_limit');
                }

                $url = '';

                if (isset($this->request->get['search'])) {
                    $url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
                }

                if (isset($this->request->get['tag'])) {
                    $url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
                }

                if (isset($this->request->get['description'])) {
                    $url .= '&description=' . $this->request->get['description'];
                }

                if (isset($this->request->get['category_id'])) {
                    $url .= '&category_id=' . $this->request->get['category_id'];
                }

                if (isset($this->request->get['sub_category'])) {
                    $url .= '&sub_category=' . $this->request->get['sub_category'];
                }

                if (isset($this->request->get['sort'])) {
                    $url .= '&sort=' . $this->request->get['sort'];
                }

                if (isset($this->request->get['order'])) {
                    $url .= '&order=' . $this->request->get['order'];
                }

                if (isset($this->request->get['page'])) {
                    $url .= '&page=' . $this->request->get['page'];
                }

                if (isset($this->request->get['limit'])) {
                    $url .= '&limit=' . $this->request->get['limit'];
                }

                $log->write($url);

                $this->load->model('assets/category');

                //$this->config->set('config_language_id',2);
                // 3 Level Category Search
                /* $data['categories'] = array();

                  $categories_1 = $this->model_assets_category->getCategories(0);

                  foreach ($categories_1 as $category_1) {
                  $level_2_data = array();

                  $categories_2 = $this->model_assets_category->getCategories($category_1['category_id']);

                  foreach ($categories_2 as $category_2) {
                  $level_3_data = array();

                  $categories_3 = $this->model_assets_category->getCategories($category_2['category_id']);

                  foreach ($categories_3 as $category_3) {
                  $level_3_data[] = array(
                  'category_id' => $category_3['category_id'],
                  'name' => $category_3['name'],
                  );
                  }

                  $level_2_data[] = array(
                  'category_id' => $category_2['category_id'],
                  'name' => $category_2['name'],
                  'children' => $level_3_data
                  );
                  }

                  $data['categories'][] = array(
                  'category_id' => $category_1['category_id'],
                  'name' => $category_1['name'],
                  'children' => $level_2_data
                  );
                  } */

                $data['products'] = [];

                if (isset($this->request->get['search']) || isset($this->request->get['tag'])) {
                    $filter_data = [
                        'filter_name' => urldecode($search),
                        'filter_name_test' => urldecode($search),
                        'filter_tag' => '',
                        'filter_category_id' => $category_id,
                        'filter_sub_category' => $sub_category,
                        'sort' => $sort,
                        'order' => $order,
                        'start' => ($page - 1) * $limit,
                        'limit' => $limit,
                    ];

                    $filter_data['store_id'] = $store_id;

                    $product_total = $this->model_assets_product->getTotalProductsByApiNew($filter_data);

                    $log = new Log('error.log');
                    $log->write('api/search');
                    $log->write($filter_data);

                    $results = $this->model_assets_product->getProductsByApiNew($filter_data);
                    //    echo "<pre>";print_r($product_total);die;
                    foreach ($results as $result) {
                        if (file_exists(DIR_IMAGE . $result['image'])) {
                            $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
                        } else {
                            $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
                        }

                        //$result['special_price'] = 10;

                        $s_price = 0;
                        $o_price = 0;

                        if (!$this->config->get('config_inclusiv_tax')) {
                            //FOR CATEGORY PRICING
                            $category_s_price = 0;
                            $category_o_price = 0;
                            if (CATEGORY_PRICE_ENABLED == true && isset($cachePrice_data) && isset($cachePrice_data[$result['product_store_id'] . '_' . $this->customer->getCustomerCategory() . '_' . $result['store_id']])) {
                                $category_s_price = $cachePrice_data[$result['product_store_id'] . '_' . $this->customer->getCustomerCategory() . '_' . $result['store_id']];
                                $category_o_price = $cachePrice_data[$result['product_store_id'] . '_' . $this->customer->getCustomerCategory() . '_' . $result['store_id']];
                                if ($category_s_price != NULL && $category_s_price > 0) {
                                    $result['price'] = $category_s_price;
                                    $result['special_price'] = $category_s_price;
                                }
                            }
                            //FOR CATEGORY PRICING
                            //get price html
                            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                                //$price = $this->currency->format( $this->tax->calculate( $result['price'], $result['tax_class_id'], $this->config->get( 'config_tax' ) ) );
                                $price = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));

                                $o_price = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
                            } else {
                                $price = false;
                            }
                            if ((float) $result['special_price']) {
                                //$special_price = $this->currency->format( $this->tax->calculate( $result['special_price'], $result['tax_class_id'], $this->config->get( 'config_tax' ) ) );
                                $special_price = $this->tax->calculate($result['special_price'], $result['tax_class_id'], $this->config->get('config_tax'));

                                $s_price = $this->tax->calculate($result['special_price'], $result['tax_class_id'], $this->config->get('config_tax'));
                            } else {
                                $special_price = false;
                            }
                        } else {
                            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                                //$price = $result['price'];
                                $price = $this->currency->formatWithoutCurrency($result['price']);
                            } else {
                                $price = $result['price'];
                            }

                            if ((float) $result['special_price']) {
                                //$special_price = $result['special_price'];
                                $special_price = $this->currency->formatWithoutCurrency($result['special_price']);
                            } else {
                                $special_price = $result['special_price'];
                            }

                            $s_price = $result['special_price'];
                            $o_price = $result['price'];

                            if (CATEGORY_PRICE_ENABLED == true && isset($cachePrice_data) && isset($cachePrice_data[$result['product_store_id'] . '_' . $this->customer->getCustomerCategory() . '_' . $result['store_id']])) {
                                $category_s_price = $cachePrice_data[$result['product_store_id'] . '_' . $this->customer->getCustomerCategory() . '_' . $result['store_id']];
                                $category_o_price = $cachePrice_data[$result['product_store_id'] . '_' . $this->customer->getCustomerCategory() . '_' . $result['store_id']];
                                if ($category_s_price != NULL && $category_s_price > 0) {
                                    $result['price'] = $category_s_price;
                                    $result['special_price'] = $category_s_price;
                                    $special_price = $category_s_price;
                                    $price = $category_s_price;
                                }
                            }
                        }

                        $percent_off = null;
                        if (isset($s_price) && isset($o_price) && 0 != $o_price && 0 != $s_price) {
                            $percent_off = (($o_price - $s_price) / $o_price) * 100;
                        }

                        if (is_null($special_price) || !($special_price + 0)) {
                            //$special_price = 0;
                            $special_price = $price;
                        }


                        /* $cachePrice_data = $this->cache->get('category_price_data'); */
                        // echo "<pre>";print_r($_SESSION['customer_category']);die;
                        /* if (CATEGORY_PRICE_ENABLED == true && isset($cachePrice_data) && isset($cachePrice_data[$result['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $result['store_id']])) {
                          //echo $cachePrice_data[$product_info['product_store_id'].'_'.$_SESSION['customer_category'].'_'.$store_id];//exit;
                          $s_price = $cachePrice_data[$result['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $result['store_id']];
                          $o_price = $cachePrice_data[$result['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $result['store_id']];
                          $special_price = $s_price;
                          $price = $o_price;
                          // echo "<pre>";print_r($special_price);die;
                          } */

                        //get qty in cart
                        $key = base64_encode(serialize(['product_store_id' => (int) $result['product_store_id'], 'store_id' => $store_id]));

                        if (isset($this->session->data['cart'][$key])) {
                            $qty_in_cart = $this->session->data['cart'][$key]['quantity'];
                        } else {
                            $qty_in_cart = 0;
                        }

                        //$result['name'] = strlen($result['name']) > 27 ? substr($result['name'],0,27)."..." : $result['name'];
                        $name = $result['name'];
                        //$name .= str_repeat('&nbsp;',30 - strlen($result['name']));

                        $unit = $result['unit'] ? $result['unit'] : false;

                        $productNames = array_column($data['products'], 'name');
                        $price = strval($price);
                        $special_price = strval($special_price);
                        $product_category_data = $this->model_assets_category->getCategory($result['category_id']);
                        $product_category_name = isset($product_category_data) && is_array($product_category_data) && count($product_category_data) > 0 ? $product_category_data['name'] : NULL;
                        if (false !== array_search($result['name'], $productNames)) {
                            // Add variation to existing product
                            $productIndex = array_search($result['name'], $productNames);
                            // TODO: Check for product variation duplicates
                            $data['products'][$productIndex]['variations'][] = [
                                'variation_id' => $result['product_store_id'],
                                'unit' => $result['unit'],
                                'weight' => floatval($result['weight']),
                                'price' => $price,
                                'special' => $special_price,
                                'percent_off' => number_format($percent_off, 0),
                                'max_qty' => $result['min_quantity'] > 0 ? $result['min_quantity'] : $result['quantity'],
                            ];
                        } else {
                            $data['products'][] = [
                                'key' => $key,
                                'qty_in_cart' => $qty_in_cart,
                                'variations' => $this->model_assets_product->getApiVariationsNew($result['product_store_id']),
                                'store_product_variation_id' => 0,
                                'store_id' => $result['store_id'],
                                'product_id' => $result['product_id'],
                                'product_store_id' => $result['product_store_id'],
                                'default_variation_name' => $result['default_variation_name'],
                                'thumb' => $image,
                                'name' => htmlspecialchars_decode($name),
                                'unit' => $unit,
                                'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
                                'price' => $price,
                                'special' => $special_price,
                                'percent_off' => number_format($percent_off, 0),
                                'left_symbol_currency' => $this->currency->getSymbolLeft(),
                                'right_symbol_currency' => $this->currency->getSymbolRight(),
                                'variations' => [
                                    [
                                        'variation_id' => $result['product_store_id'],
                                        'unit' => $result['unit'],
                                        'weight' => floatval($result['weight']),
                                        'price' => $price,
                                        'special' => $special_price,
                                        'percent_off' => number_format($percent_off, 0),
                                        'max_qty' => $result['min_quantity'] > 0 ? $result['min_quantity'] : $result['quantity'],
                                    ],
                                ],
                                'tax' => $result['tax_percentage'],
                                //'minimum' => $result['min_quantity'] > 0 ? $result['min_quantity'] : 1,
                                'max_qty' => $result['min_quantity'] > 0 ? $result['min_quantity'] : $result['quantity'],
                                'rating' => 0,
                                'href' => $this->url->link('product/product', '&product_store_id=' . $result['product_store_id']),
                                'produce_type' => $result['produce_type'],
                                'category_id' => $result['category_id'],
                                'category_name' => $product_category_name
                            ];
                        }
                    }

                    $url = '';

                    if (isset($this->request->get['search'])) {
                        $url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
                    }

                    if (isset($this->request->get['tag'])) {
                        $url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
                    }

                    if (isset($this->request->get['description'])) {
                        $url .= '&description=' . $this->request->get['description'];
                    }

                    if (isset($this->request->get['category_id'])) {
                        $url .= '&category_id=' . $this->request->get['category_id'];
                    }

                    if (isset($this->request->get['sub_category'])) {
                        $url .= '&sub_category=' . $this->request->get['sub_category'];
                    }

                    if (isset($this->request->get['limit'])) {
                        $url .= '&limit=' . $this->request->get['limit'];
                    }

                    $url = '';

                    if (isset($this->request->get['search'])) {
                        $url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
                    }

                    if (isset($this->request->get['tag'])) {
                        $url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
                    }

                    if (isset($this->request->get['description'])) {
                        $url .= '&description=' . $this->request->get['description'];
                    }

                    if (isset($this->request->get['category_id'])) {
                        $url .= '&category_id=' . $this->request->get['category_id'];
                    }

                    if (isset($this->request->get['sub_category'])) {
                        $url .= '&sub_category=' . $this->request->get['sub_category'];
                    }

                    if (isset($this->request->get['sort'])) {
                        $url .= '&sort=' . $this->request->get['sort'];
                    }

                    if (isset($this->request->get['order'])) {
                        $url .= '&order=' . $this->request->get['order'];
                    }

                    /* $data['limits'] = array();

                      $limits = array_unique(array($this->config->get('config_app_product_limit'), 25, 50, 75, 100));

                      sort($limits);

                      foreach ($limits as $value) {
                      $data['limits'][] = array(
                      'text' => $value,
                      'value' => $value,
                      'href' => $this->url->link('product/search', $url . '&limit=' . $value)
                      );
                      } */

                    $url = '';

                    if (isset($this->request->get['search'])) {
                        $url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
                    }

                    if (isset($this->request->get['tag'])) {
                        $url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
                    }

                    if (isset($this->request->get['description'])) {
                        $url .= '&description=' . $this->request->get['description'];
                    }

                    if (isset($this->request->get['category_id'])) {
                        $url .= '&category_id=' . $this->request->get['category_id'];
                    }

                    if (isset($this->request->get['sub_category'])) {
                        $url .= '&sub_category=' . $this->request->get['sub_category'];
                    }

                    if (isset($this->request->get['sort'])) {
                        $url .= '&sort=' . $this->request->get['sort'];
                    }

                    if (isset($this->request->get['order'])) {
                        $url .= '&order=' . $this->request->get['order'];
                    }

                    if (isset($this->request->get['limit'])) {
                        $url .= '&limit=' . $this->request->get['limit'];
                    }

                    $pagination = new Pagination();
                    $pagination->total = $product_total;
                    $pagination->page = $page;
                    $pagination->limit = $limit;
                    $pagination->url = $this->url->link('product/search', $url . '&page={page}');

                    //$data['pagination'] = $pagination->render();

                    $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));
                }

                $data['search'] = $search;
                // $data['category_id'] = $category_id;
                // $data['sub_category'] = $sub_category;

                $data['sort'] = $sort;
                $data['total_product'] = $product_total;

                $data['order'] = $order;
                $data['limit'] = $limit;

                if ($this->request->server['HTTPS']) {
                    $server = $this->config->get('config_ssl');
                } else {
                    $server = $this->config->get('config_url');
                }

                $data['base'] = $server;

                $json['data'] = $data;
            }
        } else {
            $json['status'] = 10010;

            $json['message'][] = ['type' => $this->language->get('text_data_missing'), 'body' => $this->language->get('text_data_missing_detail')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getProductsFn($filter_data, $store_id) {
        $this->load->model('assets/product');
        $this->load->model('tool/image');

        $filter_data['store_id'] = $store_id;
        if (isset($filter_data['group_by']) && ('name' == $filter_data['group_by'])) {
            $filter_data['group_by'] = 'name';
        }

        // $results = $this->model_assets_product->getProductsByApi($filter_data);
        if (isset($filter_data['filter_category_id'])) {
            $results = $this->model_assets_product->getProductsByApi($filter_data);
        } else {
            $limit = 10;
            if (isset($filter_data['limit'])) {
                $limit = $filter_data['limit'];
            }
            $results = $this->model_assets_product->getLatestProductsByStoreId($filter_data['store_id'], $limit);
        }

        //echo "<pre>";print_r($results);die;
        $data['products'] = [];

        foreach ($results as $result) {
            // if qty less then 1 dont show product
            //REMOVED QUANTITY CHECK CONDITION
            // if ($result['quantity'] <= 0) {
            //     continue;
            // }

            if (file_exists(DIR_IMAGE . $result['image'])) {
                $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_app_image_product_width'), $this->config->get('config_app_image_product_height'));
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_app_image_product_width'), $this->config->get('config_app_image_product_height'));
            }

            //$result['special_price'] = 10;

            $s_price = 0;
            $o_price = 0;

            if (!$this->config->get('config_inclusiv_tax')) {
                //get price html
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));

                    $o_price = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
                } else {
                    $price = false;
                }
                if ((float) $result['special_price']) {
                    $special_price = $this->tax->calculate($result['special_price'], $result['tax_class_id'], $this->config->get('config_tax'));

                    $s_price = $this->tax->calculate($result['special_price'], $result['tax_class_id'], $this->config->get('config_tax'));
                } else {
                    $special_price = false;
                }
            } else {
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    //$price = $result['price'];
                    $price = $this->currency->formatWithoutCurrency($result['price']);
                } else {
                    $price = $result['price'];
                }

                if ((float) $result['special_price']) {
                    //$special_price = $result['special_price'];
                    $special_price = $this->currency->formatWithoutCurrency($result['special_price']);
                } else {
                    $special_price = $result['special_price'];
                }

                $s_price = $result['special_price'];
                $o_price = $result['price'];
            }

            //get qty in cart
            $key = base64_encode(serialize(['product_store_id' => (int) $result['product_store_id'], 'store_id' => $store_id]));

            if (isset($this->session->data['cart'][$key])) {
                $qty_in_cart = $this->session->data['cart'][$key]['quantity'];
            } else {
                $qty_in_cart = 0;
            }

            //$result['name'] = strlen($result['name']) > 27 ? substr($result['name'],0,27)."..." : $result['name'];
            $name = $result['name'];
            //$name .= str_repeat('&nbsp;',30 - strlen($result['name']));

            $percent_off = null;
            if (isset($s_price) && isset($o_price) && 0 != $o_price && 0 != $s_price) {
                $percent_off = (($o_price - $s_price) / $o_price) * 100;
            }

            if (is_null($special_price) || !($special_price + 0)) {
                //$special_price = 0;
                $special_price = $price;
            }

            /* $productNames = array_column($data['products'], 'name');
              if (array_search($result['name'], $productNames) !== false) {
              // Add variation to existing product
              $productIndex = array_search($result['name'], $productNames);
              // TODO: Check for product variation duplicates
              $data['products'][$productIndex]['variations'][] = array(
              'variation_id' => $result['product_store_id'],
              'unit' => $result['unit'],
              'weight' => floatval($result['weight']),
              'price' => $price,
              'special' => $special_price,
              'percent_off' => number_format($percent_off,0),
              'max_qty' => $result['min_quantity'] > 0 ? $result['min_quantity'] : $result['quantity']
              );
              } else { */
            $formatted = false;
            if (isset($filter_data['group_by']) && ('name' == $filter_data['group_by'])) {
                $formatted = true;
            }
            $data['products'][] = [
                'key' => $key,
                'qty_in_cart' => $qty_in_cart,
                'variations' => $this->model_assets_product->getApiVariations($result['product_store_id']),
                'store_product_variation_id' => 0,
                'product_id' => $result['product_id'],
                'model' => $result['model'],
                'product_store_id' => $result['product_store_id'],
                'default_variation_name' => $result['default_variation_name'],
                'thumb' => $image,
                'name' => htmlspecialchars_decode($name),
                'unit' => $result['unit'],
                'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
                'price' => $price,
                'special' => $special_price,
                'percent_off' => number_format($percent_off, 0),
                'left_symbol_currency' => $this->currency->getSymbolLeft(),
                'right_symbol_currency' => $this->currency->getSymbolRight(),
                'tax' => $result['tax_percentage'],
                //'minimum' => $result['min_quantity'] > 0 ? $result['min_quantity'] : 1,
                'max_qty' => $result['min_quantity'] > 0 ? $result['min_quantity'] : $result['quantity'],
                'rating' => 0,
                'href' => $this->url->link('product/product', '&product_store_id=' . $result['product_store_id']),
                'variations' => $this->model_assets_product->getProductVariations($name, $formatted),
                'produce_type' => $result['produce_type'],
                    /* 'variations' => array(
                      array(
                      'variation_id' => $result['product_store_id'],
                      'unit' => $result['unit'],
                      'weight' => floatval($result['weight']),
                      'price' => $price,
                      'special' => $special_price,
                      'percent_off' => number_format($percent_off,0),
                      'max_qty' => $result['min_quantity'] > 0 ? $result['min_quantity'] : $result['quantity']
                      )
                      ) */
            ];
            /* } */
        }

        return $data['products'];
    }

    public function getProductsFnNew($filter_data, $store_id) {
        $cachePrice_data = $this->cache->get('category_price_data');
        $this->load->model('assets/product');
        $this->load->model('tool/image');

        $filter_data['store_id'] = $store_id;
        if (isset($filter_data['group_by']) && ('name' == $filter_data['group_by'])) {
            $filter_data['group_by'] = 'name';
        }

        // $results = $this->model_assets_product->getProductsByApi($filter_data);
        if (isset($filter_data['filter_category_id'])) {
            $results = $this->model_assets_product->getProductsByApiNew($filter_data);
        } else {
            $limit = 10;
            if (isset($filter_data['limit'])) {
                $limit = $filter_data['limit'];
            }
            $results = $this->model_assets_product->getLatestProductsByStoreId($filter_data['store_id'], $limit);
        }

        //echo "<pre>";print_r($results);die;
        $data['products'] = [];

        foreach ($results as $result) {
            // if qty less then 1 dont show product
            //REMOVED QUANTITY CHECK CONDITION
            // if ($result['quantity'] <= 0) {
            //     continue;
            // }

            if (file_exists(DIR_IMAGE . $result['image'])) {
                $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_app_image_product_width'), $this->config->get('config_app_image_product_height'));
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_app_image_product_width'), $this->config->get('config_app_image_product_height'));
            }

            //$result['special_price'] = 10;

            $s_price = 0;
            $o_price = 0;

            if (!$this->config->get('config_inclusiv_tax')) {

                $category_s_price = 0;
                $category_o_price = 0;
                if (CATEGORY_PRICE_ENABLED == true && isset($cachePrice_data) && isset($cachePrice_data[$result['product_store_id'] . '_' . $this->customer->getCustomerCategory() . '_' . $result['store_id']])) {
                    $category_s_price = $cachePrice_data[$result['product_store_id'] . '_' . $this->customer->getCustomerCategory() . '_' . $result['store_id']];
                    $category_o_price = $cachePrice_data[$result['product_store_id'] . '_' . $this->customer->getCustomerCategory() . '_' . $result['store_id']];
                    if ($category_s_price != NULL && $category_s_price > 0) {
                        $result['price'] = $category_s_price;
                        $result['special_price'] = $category_s_price;
                    }
                }
                //get price html
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));

                    $o_price = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
                } else {
                    $price = false;
                }
                if ((float) $result['special_price']) {
                    $special_price = $this->tax->calculate($result['special_price'], $result['tax_class_id'], $this->config->get('config_tax'));

                    $s_price = $this->tax->calculate($result['special_price'], $result['tax_class_id'], $this->config->get('config_tax'));
                } else {
                    $special_price = false;
                }
            } else {
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    //$price = $result['price'];
                    $price = $this->currency->formatWithoutCurrency($result['price']);
                } else {
                    $price = $result['price'];
                }

                if ((float) $result['special_price']) {
                    //$special_price = $result['special_price'];
                    $special_price = $this->currency->formatWithoutCurrency($result['special_price']);
                } else {
                    $special_price = $result['special_price'];
                }

                $s_price = $result['special_price'];
                $o_price = $result['price'];

                if (CATEGORY_PRICE_ENABLED == true && isset($cachePrice_data) && isset($cachePrice_data[$result['product_store_id'] . '_' . $this->customer->getCustomerCategory() . '_' . $result['store_id']])) {
                    $s_price = $cachePrice_data[$result['product_store_id'] . '_' . $this->customer->getCustomerCategory() . '_' . $result['store_id']];
                    $o_price = $cachePrice_data[$result['product_store_id'] . '_' . $this->customer->getCustomerCategory() . '_' . $result['store_id']];
                    $special_price = $this->currency->format($s_price);
                    $price = $this->currency->format($o_price);
                }
            }

            //get qty in cart
            $key = base64_encode(serialize(['product_store_id' => (int) $result['product_store_id'], 'store_id' => $store_id]));

            if (isset($this->session->data['cart'][$key])) {
                $qty_in_cart = $this->session->data['cart'][$key]['quantity'];
            } else {
                $qty_in_cart = 0;
            }

            //$result['name'] = strlen($result['name']) > 27 ? substr($result['name'],0,27)."..." : $result['name'];
            $name = $result['name'];
            //$name .= str_repeat('&nbsp;',30 - strlen($result['name']));

            $percent_off = null;
            if (isset($s_price) && isset($o_price) && 0 != $o_price && 0 != $s_price) {
                $percent_off = (($o_price - $s_price) / $o_price) * 100;
            }

            if (is_null($special_price) || !($special_price + 0)) {
                //$special_price = 0;
                $special_price = $price;
            }

            /* $productNames = array_column($data['products'], 'name');
              if (array_search($result['name'], $productNames) !== false) {
              // Add variation to existing product
              $productIndex = array_search($result['name'], $productNames);
              // TODO: Check for product variation duplicates
              $data['products'][$productIndex]['variations'][] = array(
              'variation_id' => $result['product_store_id'],
              'unit' => $result['unit'],
              'weight' => floatval($result['weight']),
              'price' => $price,
              'special' => $special_price,
              'percent_off' => number_format($percent_off,0),
              'max_qty' => $result['min_quantity'] > 0 ? $result['min_quantity'] : $result['quantity']
              );
              } else { */
            $formatted = false;
            if (isset($filter_data['group_by']) && ('name' == $filter_data['group_by'])) {
                $formatted = true;
            }

            $tax_amount = 0;
            $tax_name = NULL;
            $tax_percentage = 0;
            if ($result['tax_class_id'] > 0) {
                $c_price = $special_price > 0 ? $special_price : $price;
                $tax_details = $this->tax->getRates($c_price, $result['tax_class_id']);
                $log = new Log('error.log');
                //$log->write('tax_details');
                //$log->write($c_price);
                //$log->write($result['tax_class_id'][0]);
                //$log->write($tax_details);
                //$log->write('tax_details');

                foreach ($tax_details as $tax_detail) {
                    $tax_amount = $tax_detail['amount'];
                    $tax_name = $tax_detail['name'];
                    $tax_percentage = $tax_detail['rate'];
                    $log->write($tax_detail['amount']);
                    $log->write($tax_detail['name']);
                    $log->write($tax_detail['rate']);
                }
            }

            $price = strval($price);
            $special_price = strval($special_price);
            $data['products'][] = [
                'key' => $key,
                'qty_in_cart' => $qty_in_cart,
                'variations' => $this->model_assets_product->getApiVariationsNew($result['product_store_id']),
                'store_product_variation_id' => 0,
                'product_id' => $result['product_id'],
                'model' => $result['model'],
                'product_store_id' => $result['product_store_id'],
                'store_id' => $result['store_id'],
                'default_variation_name' => $result['default_variation_name'],
                'thumb' => $image,
                'name' => htmlspecialchars_decode($name),
                'unit' => $result['unit'],
                'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
                'price' => $price,
                'special' => $special_price,
                'percent_off' => number_format($percent_off, 0),
                'left_symbol_currency' => $this->currency->getSymbolLeft(),
                'right_symbol_currency' => $this->currency->getSymbolRight(),
                'tax' => $result['tax_percentage'],
                'tax_class_id' => $result['tax_class_id'],
                'tax_percentage' => $tax_percentage,
                'tax_amount' => $tax_amount,
                'tax_name' => $tax_name,
                //'minimum' => $result['min_quantity'] > 0 ? $result['min_quantity'] : 1,
                'max_qty' => $result['min_quantity'] > 0 ? $result['min_quantity'] : $result['quantity'],
                'rating' => 0,
                'href' => $this->url->link('product/product', '&product_store_id=' . $result['product_store_id']),
                'variations' => $this->model_assets_product->getProductVariationsAPI($name, $formatted),
                'produce_type' => $result['produce_type'],
                    /* 'variations' => array(
                      array(
                      'variation_id' => $result['product_store_id'],
                      'unit' => $result['unit'],
                      'weight' => floatval($result['weight']),
                      'price' => $price,
                      'special' => $special_price,
                      'percent_off' => number_format($percent_off,0),
                      'max_qty' => $result['min_quantity'] > 0 ? $result['min_quantity'] : $result['quantity']
                      )
                      ) */
            ];
            /* } */
        }

        return $data['products'];
    }

    public function getProductDetails($args = []) {
        //above should be set once user enters a store

        $json = [];
        //$this->config->set('config_language_id',2);
        $this->load->language('product/product');
        $this->load->language('api/products');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->model('tool/image');

        if (isset($this->request->get['product_store_id']) && isset($this->request->get['store_id'])) {
            $product_store_id = $this->request->get['product_store_id'];
            $store_id = $this->request->get['store_id'];
            $log = new Log('error.log');
            $log->write('store_id');
            $log->write($store_id);
            $log->write('store_id');

            $this->load->model('assets/product');

            $product_info = $this->model_assets_product->getProductForPopupByApi($store_id, $product_store_id);

            //echo "<pre>";print_r($product_info);die;
            if ($product_info) {
                if (file_exists(DIR_IMAGE . $product_info['image'])) {
                    $thumb = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_app_image_thumb_width'), $this->config->get('config_app_image_thumb_height'));
                } else {
                    $thumb = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_app_image_thumb_width'), $this->config->get('config_app_image_thumb_height'));
                }

                if (file_exists(DIR_IMAGE . $product_info['image'])) {
                    $product_info['image'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_app_image_thumb_width'), $this->config->get('config_app_image_thumb_height'));
                } else {
                    $product_info['image'] = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_app_image_thumb_width'), $this->config->get('config_app_image_thumb_height'));
                }

                //$product_info['special_price'] = 10;

                /* $data['images'] = array();

                  $results = $this->model_assets_product->getProductImages( $product_info['product_id'] );

                  foreach ( $results as $result ) {

                  if ( file_exists( DIR_IMAGE .$result['image'] ) ) {
                  $popup = $this->model_tool_image->resize($result['image'], 500, 500 );
                  $thumb = $this->model_tool_image->resize($result['image'], 500, 500 );
                  } else {
                  $popup = $this->model_tool_image->resize('placeholder.png', 500, 500 );
                  $thumb = $this->model_tool_image->resize('placeholder.png', 500, 500 );
                  }

                  $data['images'][] = array(
                  'popup' => $popup,
                  'thumb' => $thumb
                  );
                  } */
                //get qty in cart
                $key = base64_encode(serialize(['product_store_id' => (int) $product_info['product_store_id'], 'store_id' => $store_id]));

                $s_price = 0;
                $o_price = 0;

                if (!$this->config->get('config_inclusiv_tax')) {
                    //get price html
                    if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                        $product_info['price'] = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));

                        $o_price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                    } else {
                        $product_info['price'] = false;
                    }
                    if ((float) $product_info['special_price']) {
                        $product_info['special_price'] = $this->tax->calculate($product_info['special_price'], $product_info['tax_class_id'], $this->config->get('config_tax'));

                        $s_price = $this->tax->calculate($product_info['special_price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                    } else {
                        $product_info['special_price'] = false;
                    }
                } else {
                    $s_price = $product_info['special_price'];
                    $o_price = $product_info['price'];

                    if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                        //$product_info['price'] = $product_info['price'];
                        $product_info['price'] = $this->currency->formatWithoutCurrency($product_info['price']);
                    } else {
                        $product_info['price'] = $product_info['price'];
                    }

                    if ((float) $product_info['special_price']) {
                        //$product_info['special_price'] = $product_info['special_price'];
                        $product_info['special_price'] = $this->currency->formatWithoutCurrency($product_info['special_price']);
                    } else {
                        $product_info['special_price'] = $product_info['special_price'];
                    }
                }

                //echo "<pre>";print_r($product_info);die;
                if (isset($product_info['pd_name']) && !empty($product_info['pd_name'])) {
                    $product_info['name'] = htmlspecialchars_decode($product_info['pd_name']);
                } else {
                    $product_info['name'] = htmlspecialchars_decode($product_info['name']);
                }

                $percent_off = null;
                if (isset($s_price) && isset($o_price) && 0 != $o_price && 0 != $s_price) {
                    $percent_off = (($o_price - $s_price) / $o_price) * 100;
                }

                /* if(is_null($product_info['special_price'])) {
                  $product_info['special_price'] = 0;
                  } */
                if (is_null($product_info['special_price']) || !($product_info['special_price'] + 0)) {
                    $product_info['special_price'] = $product_info['price'];
                }

                $product_info['max_qty'] = $product_info['min_quantity'] > 0 ? $product_info['min_quantity'] : $product_info['quantity'];

                $product_info['percent_off'] = number_format($percent_off, 0);

                $data['product'] = [
                    'thumb' => $thumb,
                    'key' => $key,
                    'product_store_id' => $product_info['product_store_id'],
                    'store_product_variation_id' => 0,
                    //'images' => $data['images'],
                    'product_info' => $product_info,
                    'left_symbol_currency' => $this->currency->getSymbolLeft(),
                    'right_symbol_currency' => $this->currency->getSymbolRight(),
                    'percent_off' => number_format($percent_off, 0),
                    'default_variation_name' => $product_info['default_variation_name'],
                    'variations' => $this->model_assets_product->getApiVariations($product_info['product_store_id']),
                    'produce_type' => $product_info['produce_type'],
                ];

                if (is_null($data['product']['variations'])) {
                    $data['product']['variations'] = [];
                }

                //for ($i=0; $i < 3; $i++) {

                $data['extra_details'][] = [
                    'title' => 'Description',
                    'description' => strip_tags(htmlspecialchars_decode($product_info['description'])),
                ];
                //}

                $json['data'] = $data;
            } else {
                //product detail not found
                $json['status'] = 10011;

                $json['message'][] = ['type' => $this->language->get('text_not_found'), 'body' => $this->language->get('text_product_detail_not_found')];
            }
        } else {
            $json['status'] = 10010;

            $json['message'][] = ['type' => $this->language->get('text_data_missing'), 'body' => $this->language->get('text_data_missing_detail')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getProductImages($args = []) {
        //above should be set once user enters a store

        $json = [];

        $data['images'] = [];
        //$this->config->set('config_language_id',2);
        $this->load->language('product/product');
        $this->load->language('api/products');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->model('tool/image');

        if (isset($this->request->get['product_store_id']) && isset($this->request->get['store_id'])) {
            $product_store_id = $this->request->get['product_store_id'];
            $store_id = $this->request->get['store_id'];

            $this->load->model('assets/product');

            $product_info = $this->model_assets_product->getProductForPopupByApi($store_id, $product_store_id);

            if ($product_info) {
                if (file_exists(DIR_IMAGE . $product_info['image'])) {
                    $thumb = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_app_image_thumb_width'), $this->config->get('config_app_image_thumb_height'));
                    $popup = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_app_image_thumb_width'), $this->config->get('config_app_image_thumb_height'));
                } else {
                    $thumb = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_app_image_thumb_width'), $this->config->get('config_app_image_thumb_height'));
                    $popup = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_app_image_thumb_width'), $this->config->get('config_app_image_thumb_height'));
                }

                $data['images'][] = [
                    'popup' => $popup,
                    'thumb' => $thumb,
                ];

                $results = $this->model_assets_product->getProductImages($product_info['product_id']);

                foreach ($results as $result) {
                    if (file_exists(DIR_IMAGE . $result['image'])) {
                        $popup = $this->model_tool_image->resize($result['image'], $this->config->get('config_app_image_thumb_width'), $this->config->get('config_app_image_thumb_height'));
                        $thumb = $this->model_tool_image->resize($result['image'], $this->config->get('config_app_image_thumb_width'), $this->config->get('config_app_image_thumb_height'));
                    } else {
                        $popup = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_app_image_thumb_width'), $this->config->get('config_app_image_thumb_height'));
                        $thumb = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_app_image_thumb_width'), $this->config->get('config_app_image_thumb_height'));
                    }

                    $data['images'][] = [
                        'popup' => $popup,
                        'thumb' => $thumb,
                    ];
                }

                $json['data'] = $data;
            } else {
                //product detail not found
                $json['status'] = 10011;

                $json['message'][] = ['type' => $this->language->get('text_not_found'), 'body' => $this->language->get('text_product_detail_not_found')];
            }
        } else {
            $json['status'] = 10010;

            $json['message'][] = ['type' => $this->language->get('text_data_missing'), 'body' => $this->language->get('text_data_missing_detail')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getSearchProductAutocomplete($args = []) {
        $json = [];

        $this->load->language('api/errors');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $args['start'] = 0;

        if (isset($args['store_id'])) {
            //echo "<pre>";print_r($args);die;
            $this->load->model('setting/store');
            $this->load->model('assets/product');

            $autocompleteData = $this->model_assets_product->getProductsByApiNew($args);

            //echo "<pre>";print_r($autocompleteData);die;
            if ($autocompleteData) {
                $finalAutoCompleteData = [];

                foreach ($autocompleteData as $tempProduct) {
                    $temp['name'] = htmlspecialchars_decode($tempProduct['name']) . ' - ' . $tempProduct['unit'];
                    //$temp['name'] = $tempProduct['name']." - ".$tempProduct['unit'];
                    $temp['unit'] = $tempProduct['unit'];
                    $temp['only_name'] = htmlspecialchars_decode($tempProduct['name']);
                    $temp['product_id'] = $tempProduct['product_id'];
                    $temp['product_store_id'] = $tempProduct['product_store_id'];

                    array_push($finalAutoCompleteData, $temp);
                }

                $json['data'] = $finalAutoCompleteData;
            } else {
                $json['status'] = 10007;

                $json['message'][] = ['type' => '', 'body' => $this->language->get('no_product_match')];
            }
        } else {
            $json['status'] = 10005;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('store_not_found')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addSearchProductAutocomplete() {
        $json = [];

        $this->load->language('api/errors');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $args = $this->request->post;

        $args['start'] = 0;

        if (isset($args['store_id'])) {
            //echo "<pre>";print_r($args);die;
            $this->load->model('setting/store');
            $this->load->model('assets/product');

            $autocompleteData = $this->model_assets_product->getProductsByApi($args);

            //echo "<pre>";print_r($autocompleteData);die;
            if ($autocompleteData) {
                $finalAutoCompleteData = [];

                foreach ($autocompleteData as $tempProduct) {
                    $temp['name'] = htmlspecialchars_decode($tempProduct['name']) . ' - ' . $tempProduct['unit'];
                    //$temp['name'] = $tempProduct['name']." - ".$tempProduct['unit'];
                    $temp['unit'] = $tempProduct['unit'];
                    $temp['only_name'] = htmlspecialchars_decode($tempProduct['name']);
                    $temp['product_id'] = $tempProduct['product_id'];
                    $temp['product_store_id'] = $tempProduct['product_store_id'];

                    array_push($finalAutoCompleteData, $temp);
                }

                $json['data'] = $finalAutoCompleteData;
            } else {
                $json['status'] = 10007;

                $json['message'][] = ['type' => '', 'body' => $this->language->get('no_product_match')];
            }
        } else {
            $json['status'] = 10005;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('store_not_found')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getProductAutocomplete($data = []) {
        $conn = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

        if ($this->request->get['parent'] != NULL && $this->request->get['parent'] > 0) {
            $customer_details = $this->db->query('SELECT customer_category FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . $this->request->get['parent'] . "' AND status = '1'");
        } else {
            $customer_details = $this->db->query('SELECT customer_category FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . $this->request->get['customer_id'] . "' AND status = '1'");
        }
        $customercategory_new = $this->session->data['customer_category'] = isset($customer_details->row['customer_category']) ? $customer_details->row['customer_category'] : null;

        $sql = 'SELECT p.*,pd.*,p2c.product_id product_id2 FROM ' . DB_PREFIX . 'product p LEFT JOIN ' . DB_PREFIX . 'product_description pd ON (p.product_id = pd.product_id) LEFT JOIN ' . DB_PREFIX . 'product_to_category p2c ON (p.product_id = p2c.product_id)';

        if (!empty($data['filter_store'])) {
            $sql .= ' LEFT JOIN `' . DB_PREFIX . 'product_to_store` ps on ps.product_id = p.product_id';
        }

        $sql .= " WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

        /* if (!empty($data['filter_store'])) {
          $sql .= ' AND ps.store_id="' . $data['filter_store'] . '"';
          } */

        /* if ($this->user->isVendor()) {
          // $sql .= ' AND p.vendor_id="'.$this->user->getId().'"';
          }else{
          // $sql .= ' AND p.vendor_id!="0"';
          }
         */

        if (!empty($data['filter_name'])) {
            $sql .= " AND pd.name LIKE '%" . $data['filter_name'] . "%'";
            //$this->db->like('product_description.name', $this->db->escape( $filter_name ) , 'both');
        }

        if (!empty($data['filter_model'])) {
            $sql .= " AND p.model LIKE '" . $data['filter_model'] . "%'";
        }

        if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
            $sql .= " AND p2c.category_id = '" . $data['filter_category'] . "'";
        }

        if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
            $lGroup = false;
            $sql .= " AND p2c.category_id = '" . $data['filter_category'] . "'";
        } else {
            $lGroup = true;
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND p.status = '" . (int) $data['filter_status'] . "'";
            $sql .= " AND ps.status = '" . (int) $data['filter_status'] . "'";
        }
        //$sql .= " GROUP BY p.product_id";
        //$sql .= " LIMIT 10";
        //$sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
        // echo $sql;exit;
        $sort_data = [
            'pd.name',
            'p.model',
            'p.price',
            'p2c.category_id',
            'p.quantity',
            'p.status',
            'p.sort_order',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY pd.name';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        } else {
            $sql .= ' LIMIT 10';
        }

        $results = $query = $conn->query($sql);

        $disabled_products_string = NULL;
        // if(isset($_SESSION['customer_category']) && $_SESSION['customer_category'] != NULL) 
        if (isset($customercategory_new) && $customercategory_new != NULL) {
            $this->load->model('assets/product');
            $category_pricing_disabled_products = $this->model_assets_product->getCategoryPriceStatusByCategoryName($customercategory_new, 0);
            //$log = new Log('error.log');
            //$log->write('category_pricing_disabled_products');
            $disabled_products = array_column($category_pricing_disabled_products, 'product_id');
            $disabled_products_string = implode(',', $disabled_products);
            //$log->write($disabled_products_string);
            //$log->write('category_pricing_disabled_products');
        }


        foreach ($results as $result) {
            $avaialble = 0;
            if ($disabled_products_string != NULL && isset($_SESSION['customer_category']) && $_SESSION['customer_category'] != NULL) {
                // if (in_array($r['product_store_id'], $disabled_products_string)) {
                //      continue;
                // } 

                foreach ($disabled_products as $key => $value) {
                    if ($value == $result['product_id']) {
                        $avaialble = 1;
                    }
                }
            }
            if ($avaialble == 0) {
                // echo "<pre>";print_r($disabled_products_string);die;
                $result['index'] = $result['name'];
                if (strpos($result['name'], '&nbsp;&nbsp;&gt;&nbsp;&nbsp;')) {
                    $result['name'] = explode('&nbsp;&nbsp;&gt;&nbsp;&nbsp;', $result['name']);
                    $result['name'] = end($result['name']);
                }

                $json[] = [
                    'product_id' => $result['product_id'],
                    'index' => $result['index'],
                    'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')) . ' - ' . $result['unit'],
                    'unit' => $result['unit'],
                ];
            }
        }
        $sort_order = [];

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['name'];
        }
        array_multisort($sort_order, SORT_ASC, $json);
        $resjson['status'] = 200;
        $resjson['data'] = $json;
        $resjson['msg'] = 'Product list fetched succesfully';
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($resjson));
        //echo '<pre>';print_r($json);exit;
        //echo $sql;ext;
        // $query = $this->db->query($sql);
    }

    public function getProductID($data = []) {//this is temp for demo, need to modify according getProductAutocomplete
        try {
            $conn = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

            // if ($this->request->get['parent'] != NULL && $this->request->get['parent'] > 0) {
            //     $customer_details = $this->db->query('SELECT customer_category FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . $this->request->get['parent'] . "' AND status = '1'");
            // } else {
            //     $customer_details = $this->db->query('SELECT customer_category FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . $this->request->get['customer_id'] . "' AND status = '1'");
            // }
            // $customercategory_new = $this->session->data['customer_category'] = isset($customer_details->row['customer_category']) ? $customer_details->row['customer_category'] : null;
            // $sql = 'SELECT p.*,pd.*,p2c.product_id product_id2 FROM ' . DB_PREFIX . 'product p LEFT JOIN ' . DB_PREFIX . 'product_description pd ON (p.product_id = pd.product_id) LEFT JOIN ' . DB_PREFIX . 'product_to_category p2c ON (p.product_id = p2c.product_id)';
            $sql = 'SELECT p.*,pd.*,p2c.product_id product_id2,ps.product_store_id FROM ' . DB_PREFIX . 'product p LEFT JOIN ' . DB_PREFIX . 'product_description pd ON (p.product_id = pd.product_id) LEFT JOIN ' . DB_PREFIX . 'product_to_category p2c ON (p.product_id = p2c.product_id)';

            // if (!empty($data['filter_store'])) 
            {
                $sql .= ' LEFT JOIN `' . DB_PREFIX . 'product_to_store` ps on ps.product_id = p.product_id';
            }

            $sql .= " WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

            if (!empty($data['filter_store'])) {
                $sql .= ' AND ps.store_id="' . $data['filter_store'] . '"';
            }

            // /*if ($this->user->isVendor()) {
            //     // $sql .= ' AND p.vendor_id="'.$this->user->getId().'"';
            // }else{
            //     // $sql .= ' AND p.vendor_id!="0"';
            // }
            // */

            if (!empty($data['filter_name'])) {
                $sql .= " AND pd.name LIKE '%" . $data['filter_name'] . "%'";
            }
            if (!empty($data['filter_varient'])) {
                $sql .= " AND p.unit LIKE '%" . $data['filter_varient'] . "%'";
            }

            // if (!empty($data['filter_model'])) {
            //     $sql .= " AND p.model LIKE '" . $data['filter_model'] . "%'";
            // }
            // if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
            //     $sql .= " AND p2c.category_id = '" . $data['filter_category'] . "'";
            // }
            // if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
            //     $lGroup = false;
            //     $sql .= " AND p2c.category_id = '" . $data['filter_category'] . "'";
            // } else {
            //     $lGroup = true;
            // }
            // if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            //     $sql .= " AND p.status = '" . (int) $data['filter_status'] . "'";
            // }
            // //$sql .= " GROUP BY p.product_id";
            $sql .= " LIMIT 1";
            // //$sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
            // echo $sql;exit;
            // $sort_data = [
            //     'pd.name',
            //     'p.model',
            //     'p.price',
            //     'p2c.category_id',
            //     'p.quantity',
            //     'p.status',
            //     'p.sort_order',
            // ];
            // if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            //     $sql .= ' ORDER BY ' . $data['sort'];
            // } else {
            //     $sql .= ' ORDER BY pd.name';
            // }
            // if (isset($data['order']) && ('DESC' == $data['order'])) {
            //     $sql .= ' DESC';
            // } else {
            //     $sql .= ' ASC';
            // }



            $results = $query = $conn->query($sql);

            $disabled_products_string = NULL;
            // if(isset($_SESSION['customer_category']) && $_SESSION['customer_category'] != NULL) 
            if (isset($customercategory_new) && $customercategory_new != NULL) {
                $this->load->model('assets/product');
                $category_pricing_disabled_products = $this->model_assets_product->getCategoryPriceStatusByCategoryName($customercategory_new, 0);
                //$log = new Log('error.log');
                //$log->write('category_pricing_disabled_products');
                $disabled_products = array_column($category_pricing_disabled_products, 'product_id');
                $disabled_products_string = implode(',', $disabled_products);
                //$log->write($disabled_products_string);
                //$log->write('category_pricing_disabled_products');
            }


            foreach ($results as $result) {
                $avaialble = 0;
                if ($disabled_products_string != NULL && isset($_SESSION['customer_category']) && $_SESSION['customer_category'] != NULL) {
                    // if (in_array($r['product_store_id'], $disabled_products_string)) {
                    //      continue;
                    // } 

                    foreach ($disabled_products as $key => $value) {
                        if ($value == $result['product_id']) {
                            $avaialble = 1;
                        }
                    }
                }
                if ($avaialble == 0) {
                    // echo "<pre>";print_r($disabled_products_string);die;
                    $result['index'] = $result['name'];
                    if (strpos($result['name'], '&nbsp;&nbsp;&gt;&nbsp;&nbsp;')) {
                        $result['name'] = explode('&nbsp;&nbsp;&gt;&nbsp;&nbsp;', $result['name']);
                        $result['name'] = end($result['name']);
                    }

                    $json[] = [
                        'product_store_id' => $result['product_store_id'],
                        'index' => $result['index'],
                        'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')) . ' - ' . $result['unit'],
                        'unit' => $result['unit'],
                        'product_id' => $result['product_id'],
                    ];
                }
            }
            $sort_order = [];

            foreach ($json as $key => $value) {
                $sort_order[$key] = $value['name'];
            }
            array_multisort($sort_order, SORT_ASC, $json);
            $resjson['status'] = 200;
            $resjson['data'] = $json;
            $resjson['msg'] = 'Product list fetched succesfully';
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($resjson));
            //echo '<pre>';print_r($json);exit;
            //echo $sql;ext;
            // $query = $this->db->query($sql);
        } catch (exception $ex) {
            $json['status'] = 400;
            $json['data'] = '';
            $json['msg'] = 'Product data fetching failed';
        }
    }

    public function addProductID($data = []) {//this is temp for demo, need to modify according getProductAutocomplete
        try {
            $conn = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

            // if ($this->request->get['parent'] != NULL && $this->request->get['parent'] > 0) {
            //     $customer_details = $this->db->query('SELECT customer_category FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . $this->request->get['parent'] . "' AND status = '1'");
            // } else {
            //     $customer_details = $this->db->query('SELECT customer_category FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . $this->request->get['customer_id'] . "' AND status = '1'");
            // }
            // $customercategory_new = $this->session->data['customer_category'] = isset($customer_details->row['customer_category']) ? $customer_details->row['customer_category'] : null;
            // $sql = 'SELECT p.*,pd.*,p2c.product_id product_id2 FROM ' . DB_PREFIX . 'product p LEFT JOIN ' . DB_PREFIX . 'product_description pd ON (p.product_id = pd.product_id) LEFT JOIN ' . DB_PREFIX . 'product_to_category p2c ON (p.product_id = p2c.product_id)';
            $sql = 'SELECT p.*,pd.*,p2c.product_id product_id2,ps.product_store_id FROM ' . DB_PREFIX . 'product p LEFT JOIN ' . DB_PREFIX . 'product_description pd ON (p.product_id = pd.product_id) LEFT JOIN ' . DB_PREFIX . 'product_to_category p2c ON (p.product_id = p2c.product_id)';

            // if (!empty($data['filter_store'])) 
            {
                $sql .= ' LEFT JOIN `' . DB_PREFIX . 'product_to_store` ps on ps.product_id = p.product_id';
            }

            $sql .= " WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

            if (!empty($data['filter_store'])) {
                $sql .= ' AND ps.store_id="' . $data['filter_store'] . '"';
            }

            // /*if ($this->user->isVendor()) {
            //     // $sql .= ' AND p.vendor_id="'.$this->user->getId().'"';
            // }else{
            //     // $sql .= ' AND p.vendor_id!="0"';
            // }
            // */

            if (!empty($data['filter_name'])) {
                $sql .= " AND pd.name LIKE '%" . $data['filter_name'] . "%'";
            }
            if (!empty($data['filter_varient'])) {
                $sql .= " AND p.unit LIKE '%" . $data['filter_varient'] . "%'";
            }

            // if (!empty($data['filter_model'])) {
            //     $sql .= " AND p.model LIKE '" . $data['filter_model'] . "%'";
            // }
            // if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
            //     $sql .= " AND p2c.category_id = '" . $data['filter_category'] . "'";
            // }
            // if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
            //     $lGroup = false;
            //     $sql .= " AND p2c.category_id = '" . $data['filter_category'] . "'";
            // } else {
            //     $lGroup = true;
            // }
            // if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            //     $sql .= " AND p.status = '" . (int) $data['filter_status'] . "'";
            // }
            // //$sql .= " GROUP BY p.product_id";
            $sql .= " LIMIT 1";
            // //$sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
            // echo $sql;exit;
            // $sort_data = [
            //     'pd.name',
            //     'p.model',
            //     'p.price',
            //     'p2c.category_id',
            //     'p.quantity',
            //     'p.status',
            //     'p.sort_order',
            // ];
            // if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            //     $sql .= ' ORDER BY ' . $data['sort'];
            // } else {
            //     $sql .= ' ORDER BY pd.name';
            // }
            // if (isset($data['order']) && ('DESC' == $data['order'])) {
            //     $sql .= ' DESC';
            // } else {
            //     $sql .= ' ASC';
            // }



            $results = $query = $conn->query($sql);

            $disabled_products_string = NULL;
            // if(isset($_SESSION['customer_category']) && $_SESSION['customer_category'] != NULL) 
            if (isset($customercategory_new) && $customercategory_new != NULL) {
                $this->load->model('assets/product');
                $category_pricing_disabled_products = $this->model_assets_product->getCategoryPriceStatusByCategoryName($customercategory_new, 0);
                //$log = new Log('error.log');
                //$log->write('category_pricing_disabled_products');
                $disabled_products = array_column($category_pricing_disabled_products, 'product_id');
                $disabled_products_string = implode(',', $disabled_products);
                //$log->write($disabled_products_string);
                //$log->write('category_pricing_disabled_products');
            }


            foreach ($results as $result) {
                $avaialble = 0;
                if ($disabled_products_string != NULL && isset($_SESSION['customer_category']) && $_SESSION['customer_category'] != NULL) {
                    // if (in_array($r['product_store_id'], $disabled_products_string)) {
                    //      continue;
                    // } 

                    foreach ($disabled_products as $key => $value) {
                        if ($value == $result['product_id']) {
                            $avaialble = 1;
                        }
                    }
                }
                if ($avaialble == 0) {
                    // echo "<pre>";print_r($disabled_products_string);die;
                    $result['index'] = $result['name'];
                    if (strpos($result['name'], '&nbsp;&nbsp;&gt;&nbsp;&nbsp;')) {
                        $result['name'] = explode('&nbsp;&nbsp;&gt;&nbsp;&nbsp;', $result['name']);
                        $result['name'] = end($result['name']);
                    }

                    $json[] = [
                        'product_store_id' => $result['product_store_id'],
                        'index' => $result['index'],
                        'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')) . ' - ' . $result['unit'],
                        'unit' => $result['unit'],
                        'product_id' => $result['product_id'],
                    ];
                }
            }
            $sort_order = [];

            foreach ($json as $key => $value) {
                $sort_order[$key] = $value['name'];
            }
            array_multisort($sort_order, SORT_ASC, $json);
            $resjson['status'] = 200;
            $resjson['data'] = $json;
            $resjson['msg'] = 'Product list fetched succesfully';
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($resjson));
            //echo '<pre>';print_r($json);exit;
            //echo $sql;ext;
            // $query = $this->db->query($sql);
        } catch (exception $ex) {
            $json['status'] = 400;
            $json['data'] = '';
            $json['msg'] = 'Product data fetching failed';
        }
    }

    public function getAllProducts() {

        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = '';
        }

        $this->load->model('sale/order');
        $products = $this->model_sale_order->getProductsForInventory($filter_name);
        foreach ($products as $j) {
            if (isset($j['special_price']) && !is_null($j['special_price']) && $j['special_price'] && (float) $j['special_price']) {
                $j['price'] = $j['special_price'];
            }

            $j['name'] = htmlspecialchars_decode($j['name']);

            $send[] = $j;
        }

        $json['status'] = 200;
        $json['data'] = $send;
        $json['msg'] = 'Product list fetched succesfully';
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getProductUOM() {
        $log = new Log('error.log');
        $log->write($this->request->get['product_store_id']);

        $this->load->model('sale/order');
        $this->load->model('assets/product');

        $product_details = $this->model_sale_order->getProduct($this->request->get['product_store_id']);
        $product_info = $this->model_assets_product->getProductForPopup($this->request->get['product_store_id'], false, $product_details['store_id']);
        $variations = $this->model_sale_order->getVendorProductVariations($product_info['name'], $product_details['store_id']);

        $json['status'] = 200;
        $json['data'] = $variations;
        $json['msg'] = 'Product list fetched succesfully';
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getSupplierFarmer() {
        $data = [];

        if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_email']) || isset($this->request->get['filter_mobile'])) {
            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            if (isset($this->request->get['filter_email'])) {
                $filter_email = $this->request->get['filter_email'];
            } else {
                $filter_email = '';
            }

            if (isset($this->request->get['filter_mobile'])) {
                $filter_mobile = $this->request->get['filter_mobile'];
            } else {
                $filter_mobile = '';
            }

            $this->load->model('sale/order');

            $filter_data = [
                'filter_name' => $filter_name,
                'filter_email' => $filter_email,
                'filter_mobile' => $filter_mobile,
                'start' => 0,
                'limit' => 5,
            ];

            $results = $this->model_sale_order->getFarmerSupplierUsers($filter_data);

            foreach ($results as $result) {
                $data[] = [
                    'supplier_id' => $result['farmer_id'],
                    'username' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                    'name' => $result['name'],
                    'firstname' => $result['first_name'],
                    'lastname' => $result['last_name'],
                    'email' => $result['email'],
                    'mobile' => $result['mobile']
                ];
            }
        }

        $sort_order = [];

        foreach ($data as $key => $value) {
            $sort_order[$key] = $value['name'];
        }

        array_multisort($sort_order, SORT_ASC, $data);

        $json['status'] = 200;
        $json['data'] = $data;
        $json['msg'] = 'Supplier/Farmers list fetched succesfully';
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getupdateInventorysingle() {

        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];
        $supplier_details = NULL;

        $log = new Log('error.log');
        $log->write($this->request->post);
        $log->write($this->request->get);

        if ($this->request->get['vendor_product_id'] != NULL && $this->request->get['vendor_product_uom'] != NULL && $this->request->get['buying_price'] != NULL && $this->request->get['procured_quantity'] != NULL && $this->request->get['rejected_quantity'] != NULL) {
            $this->load->model('sale/order');
            $this->load->model('user/farmer');
            $this->load->model('user/supplier');

            $supplier_details = $this->model_user_supplier->getSupplier($this->request->get['buying_source_id']);
            if ($supplier_details == NULL) {
                $supplier_details = $this->model_user_farmer->getFarmer($this->request->get['buying_source_id']);
            }

            $log->write('supplier_details');
            $log->write($supplier_details);
            $log->write('supplier_details');

            $product_details = $this->model_sale_order->getProduct($this->request->get['vendor_product_id']);
            $log->write($product_details);
            $vendor_product_uom = $this->request->get['vendor_product_uom'];
            $buying_price = $this->request->get['buying_price'];
            $buying_source = $this->request->get['buying_source'];
            $buying_source_id = $this->request->get['buying_source_id'];
            $procured_quantity = $this->request->get['procured_quantity'];
            $rejected_quantity = $this->request->get['rejected_quantity'];
            $vendor_product_id = $this->request->get['vendor_product_id'];

            $product['rejected_qty'] = $rejected_quantity;
            $product['procured_qty'] = $procured_quantity;
            $product['current_buying_price'] = $buying_price;
            $product['source'] = $buying_source;
            //$product['current_qty'] = $procured_quantity - $rejected_quantity;
            $product['current_qty'] = $product_details['quantity'];
            $product['product_name'] = $product_details['name'];
            $product['product_id'] = $product_details['product_id'];

            $result = $this->model_sale_order->updateProductInventory($vendor_product_id, $product);
            //$ret = $this->emailtemplate->sendmessage($get_farmer_phone['mobile'], $sms_message);
            $log->write('RESULT');
            $log->write($result);
            $log->write('RESULT');
            $json['data'] = '';
            $json['status'] = '200';
            $json['message'] = 'Products stocks modified successfully!';
            $this->session->data['success'] = 'Products stocks modified successfully!';
        } else {
            $json['status'] = '400';
            $json['message'] = 'All fields are mandatory!';
            $this->session->data['warning'] = 'All fields are mandatory!';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addupdateInventory($args = []) {
        $log = new Log('error.log');
        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        if ($this->validatenew($args)) {
            foreach ($args['products'] as $product) {
                $this->load->model('sale/order');
                $this->load->model('user/farmer');
                $this->load->model('user/supplier');
                $this->load->model('user/user');

                $user = $this->model_user_user->getUserByEmail($args['email']);
                $product['user_id'] = $user['user_id'];
                $product['user_role'] = $user['user_group'];
                $product['user_name'] = $user['firstname'] . ' ' . $user['lastname'];

                $supplier_details = $this->model_user_supplier->getSupplier($product['buying_source_id']);
                if ($supplier_details == NULL) {
                    $supplier_details = $this->model_user_farmer->getFarmer($product['buying_source_id']);
                }

                $log->write('supplier_details');
                $log->write($supplier_details);
                $log->write('supplier_details');

                $product_details = $this->model_sale_order->getProduct($product['vendor_product_id']);
                $log->write($product_details);
                $buying_price = $product['buying_price'];
                $buying_source = $product['buying_source'];
                $procured_quantity = $product['procured_quantity'];
                $rejected_quantity = $product['rejected_quantity'];
                $vendor_product_id = $product['vendor_product_id'];

                $product['rejected_qty'] = $rejected_quantity;
                $product['procured_qty'] = $procured_quantity;
                $product['current_buying_price'] = $buying_price;
                $product['source'] = $buying_source;
                //$product['current_qty'] = $procured_quantity - $rejected_quantity;
                $product['current_qty'] = $product_details['quantity'];
                $product['product_name'] = $product_details['name'];
                $product['product_id'] = $product_details['product_id'];

                $result = $this->model_sale_order->updateProductInventory($vendor_product_id, $product);
                //$ret = $this->emailtemplate->sendmessage($get_farmer_phone['mobile'], $sms_message);
                $log->write('RESULT');
                $log->write($result);
                $log->write('RESULT');
                $json['data'] = '';
                $json['status'] = '200';
                $json['message'] = 'Products stocks modified successfully!';
                $this->session->data['success'] = 'Products stocks modified successfully!';
            }
        } else {
            $json['status'] = 10014;

            foreach ($this->error as $key => $value) {
                $json['message'][] = ['type' => $key, 'body' => $value];
            }

            http_response_code(400);
        }

        if (200 == $json['status']) {
            $json['data']['status'] = true;
        } else {
            $json['data']['status'] = false;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validatenew($args) {
        if (empty($args['email'])) {
            $this->error['email'] = 'Email Required!';
        }

        if (!empty($args['email'])) {
            $this->load->model('user/user');
            $user = $this->model_user_user->getUserByEmail($args['email']);
            if ($user == NULL) {
                $this->error['user'] = 'User Invalid!';
            }
        }

        if (empty($args['firstname'])) {
            $this->error['firstname'] = 'FirstName Required!';
        }

        if (empty($args['lastname'])) {
            $this->error['lastname'] = 'LastName Required!';
        }

        if (empty($args['user_id'])) {
            $this->error['user_id'] = 'User Id Required!';
        }

        if (empty($args['products']) || !is_array($args['products'])) {
            $this->error['products'] = 'Products Required!';
        }

        if (!empty($args['products']) && is_array($args['products'])) {
            $log = new Log('error.log');
            foreach ($args['products'] as $product) {
                if (!array_key_exists('procured_quantity', $product)) {
                    $this->error['procured_quantity'] = 'Procured Quantity Required!';
                }

                if (array_key_exists('procured_quantity', $product) && ($product['procured_quantity'] <= 0 || $product['procured_quantity'] == NULL)) {
                    $this->error['procured_quantity'] = 'Procured Quantity Required!';
                }

                if (!array_key_exists('rejected_quantity', $product)) {
                    $this->error['procured_quantity'] = 'Procured Quantity Required!';
                }

                if (array_key_exists('rejected_quantity', $product) && $product['rejected_quantity'] == NULL) {
                    $this->error['rejected_quantity'] = 'Rejected Quantity Required!';
                }

                if (!array_key_exists('vendor_product_id', $product)) {
                    $this->error['vendor_product_id'] = 'Vendor Product Required!';
                }

                if (array_key_exists('vendor_product_id', $product) && ($product['vendor_product_id'] == NULL || $product['procured_quantity'] <= 0)) {
                    $this->error['vendor_product_id'] = 'Vendor Product Required!';
                }

                if (!array_key_exists('buying_price', $product)) {
                    $this->error['buying_price'] = 'Buying Price Required!';
                }

                if (array_key_exists('buying_price', $product) && ($product['buying_price'] == NULL || $product['buying_price'] <= 0)) {
                    $this->error['buying_price'] = 'Buying Price Required!';
                }

                if (array_key_exists('buying_source', $product) && ($product['buying_source'] == NULL || $product['buying_source'] <= 0)) {
                    $this->error['buying_source'] = 'Buying Source Required!';
                }

                if (array_key_exists('buying_source_id', $product) && ($product['buying_source_id'] == NULL || $product['buying_source_id'] <= 0)) {
                    $this->error['buying_source_id'] = 'Buying Source ID Required!';
                }
            }
        }

        return !$this->error;
    }

    public function getProductsInventory() {
        $json = [];

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->model('catalog/vendor_product');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['limit'])) {
            $limit = $this->request->get['limit'];
        } else {
            $limit = $this->config->get('config_limit_admin');
        }

        $filter_data = [
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $limit,
            'start' => ($page - 1) * $limit,
        ];

        $product_total = $this->model_catalog_vendor_product->getTotalProducts($filter_data);
        $results = $this->model_catalog_vendor_product->getProducts($filter_data);

        $pagination = new Pagination();
        $pagination->total = $product_total;
        $pagination->page = $page;
        $pagination->limit = $limit;

        $data['products'] = $results;
        $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));
        $data['total_product'] = $product_total;
        $data['limit'] = $limit;
        $data['page'] = $page;

        $json['data'] = $data;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getProductsInventoryHistory() {
        $json = [];

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->model('catalog/vendor_product');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['limit'])) {
            $limit = $this->request->get['limit'];
        } else {
            $limit = $this->config->get('config_limit_admin');
        }

        $filter_data = [
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $limit,
            'start' => ($page - 1) * $limit,
        ];

        $product_total = $this->model_catalog_vendor_product->getTotalProductInventoryHistory($filter_data);
        $results = $this->model_catalog_vendor_product->getProductInventoryHistory($filter_data);

        $pagination = new Pagination();
        $pagination->total = $product_total;
        $pagination->page = $page;
        $pagination->limit = $limit;

        $data['products'] = $results;
        $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));
        $data['total_product'] = $product_total;
        $data['limit'] = $limit;
        $data['page'] = $page;

        $json['data'] = $data;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
