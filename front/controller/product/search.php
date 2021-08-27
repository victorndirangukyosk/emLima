<?php

class ControllerProductSearch extends Controller {

    public function index() {
        $this->load->language('product/category');

        $this->load->language('product/search');

        $this->load->model('assets/category');

        $this->load->model('assets/product');

        $this->load->model('tool/image');

        if ($this->customer->isLogged()) {
            $data['lists'] = $this->model_assets_category->getUserLists();
        }

        $data['text_add_to_list'] = $this->language->get('text_add_to_list');
        $data['text_list_name'] = $this->language->get('text_list_name');
        $data['text_add_to'] = $this->language->get('text_add_to');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_or'] = $this->language->get('text_or');
        $data['text_enter_list_name'] = $this->language->get('text_enter_list_name');
        $data['text_create_list'] = $this->language->get('text_create_list');

        if (isset($this->session->data['config_store_id'])) {
            $store_id = $this->session->data['config_store_id'];
        } else {
            $store_id = 0;
        }

        $store_info = $this->model_tool_image->getStore($store_id);

        if (!$store_info) {
            unset($this->session->data['config_store_id']);
            $this->response->redirect($this->url->link('common/home'));
        }

        if (isset($this->request->get['search'])) {
            $search = $this->request->get['search'];
        } else {
            $search = '';
        }

        if (isset($this->request->get['tag'])) {
            $tag = $this->request->get['tag'];
        } elseif (isset($this->request->get['search'])) {
            $tag = $this->request->get['search'];
        } else {
            $tag = '';
        }

        if (isset($this->request->get['description'])) {
            $description = $this->request->get['description'];
        } else {
            $description = '';
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
            $limit = $this->config->get('config_product_limit');
        }

        if (isset($this->request->get['search'])) {
            $this->document->setTitle($this->language->get('heading_title') . ' - ' . $this->request->get['search']);
        } elseif (isset($this->request->get['tag'])) {
            $this->document->setTitle($this->language->get('heading_title') . ' - ' . $this->language->get('heading_tag') . $this->request->get['tag']);
        } else {
            $this->document->setTitle($this->language->get('heading_title'));
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

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

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('product/search', $url),
        ];

        if (isset($this->request->get['search'])) {
            $data['heading_title'] = $this->language->get('heading_title') . ' - ' . $this->request->get['search'];
        } else {
            $data['heading_title'] = $this->language->get('heading_title');
        }

        $data['location_name'] = '';
        $data['zipcode'] = '';
        if (count($_COOKIE) > 0 && isset($_COOKIE['zipcode'])) {
            $data['zipcode'] = $_COOKIE['zipcode'];
        } elseif (count($_COOKIE) > 0 && isset($_COOKIE['location'])) {
            $data['location_name'] = $this->getHeaderPlace($_COOKIE['location']);
            $data['location_name_full'] = $data['location_name'];

            /* if(isset($_COOKIE['location_name'])) {
              $data['location_name'] = $_COOKIE['location_name'];
              $data['location_name_full'] = $_COOKIE['location_name'];
              } */
        }

        $data['text_change_locality_warning'] = $this->language->get('text_change_locality_warning');
        $data['text_change_location_name'] = $this->language->get('text_change_location_name');
        $data['text_only_on_change_locality_warning'] = $this->language->get('text_only_on_change_locality_warning');
        $data['button_change_locality'] = $this->language->get('button_change_locality');
        $data['button_change_store'] = $this->language->get('button_change_store');
        $data['text_change_locality'] = $this->language->get('text_change_locality');
        $data['text_empty'] = $this->language->get('text_empty');
        $data['text_search'] = 'Search results for <b>"' . $search . '"</b>';
        $data['text_keyword'] = $this->language->get('text_keyword');
        $data['text_category'] = $this->language->get('text_category');
        $data['text_sub_category'] = $this->language->get('text_sub_category');
        $data['text_quantity'] = $this->language->get('text_quantity');
        $data['text_manufacturer'] = $this->language->get('text_manufacturer');
        $data['text_model'] = $this->language->get('text_model');
        $data['text_price'] = $this->language->get('text_price');
        $data['text_tax'] = $this->language->get('text_tax');
        $data['text_points'] = $this->language->get('text_points');
        $data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
        $data['text_sort'] = $this->language->get('text_sort');
        $data['text_limit'] = $this->language->get('text_limit');
        $data['button_add'] = $this->language->get('button_add');
        $data['entry_search'] = $this->language->get('entry_search');
        $data['entry_description'] = $this->language->get('entry_description');

        $data['button_search'] = $this->language->get('button_search');
        $data['button_cart'] = $this->language->get('button_cart');
        $data['button_wishlist'] = $this->language->get('button_wishlist');
        $data['button_compare'] = $this->language->get('button_compare');
        $data['button_list'] = $this->language->get('button_list');
        $data['button_grid'] = $this->language->get('button_grid');

        $data['text_no_more_products'] = $this->language->get('text_no_more_products');

        $data['compare'] = $this->url->link('product/compare');

        $data['toHome'] = $this->url->link('common/home/toHome');
        $data['toStore'] = $this->url->link('common/home/toStore');

        $this->load->model('assets/category');

        // 3 Level Category Search
        $data['categories'] = [];

        $categories_1 = $this->model_assets_category->getCategories(0);

        foreach ($categories_1 as $category_1) {
            $level_2_data = [];

            $categories_2 = $this->model_assets_category->getCategories($category_1['category_id']);

            foreach ($categories_2 as $category_2) {
                $level_3_data = [];

                $categories_3 = $this->model_assets_category->getCategories($category_2['category_id']);

                foreach ($categories_3 as $category_3) {
                    $level_3_data[] = [
                        'category_id' => $category_3['category_id'],
                        'name' => $category_3['name'],
                    ];
                }

                $level_2_data[] = [
                    'category_id' => $category_2['category_id'],
                    'name' => $category_2['name'],
                    'children' => $level_3_data,
                ];
            }

            $data['categories'][] = [
                'category_id' => $category_1['category_id'],
                'name' => $category_1['name'],
                'children' => $level_2_data,
            ];
        }

        $data['products'] = [];

        if (isset($this->request->get['search']) || isset($this->request->get['tag'])) {
            $filter_data = [
                'filter_name' => $search,
                'filter_tag' => '',
                'filter_description' => $description,
                'filter_category_id' => $category_id,
                'filter_sub_category' => $sub_category,
                'sort' => $sort,
                'order' => $order,
                'start' => ($page - 1) * $limit,
                'limit' => $limit,
            ];

            $product_total = $this->model_assets_product->getTotalProducts($filter_data);

            $results = $this->model_assets_product->getProducts($filter_data);

            //echo "<pre>";print_r($results);die;
            foreach ($results as $result) {
                if (file_exists(DIR_IMAGE . $result['image'])) {
                    $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
                }

                $s_price = 0;
                $o_price = 0;

                if (!$this->config->get('config_inclusiv_tax')) {
                    //get price html
                    if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                        $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));

                        $o_price = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
                    } else {
                        $price = false;
                    }
                    if ((float) $result['special_price']) {
                        $special_price = $this->currency->format($this->tax->calculate($result['special_price'], $result['tax_class_id'], $this->config->get('config_tax')));

                        $s_price = $this->tax->calculate($result['special_price'], $result['tax_class_id'], $this->config->get('config_tax'));
                    } else {
                        $special_price = false;
                    }
                } else {
                    if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                        $price = $this->currency->format($result['price']);
                    } else {
                        $price = $result['price'];
                    }

                    if ((float) $result['special_price']) {
                        $special_price = $this->currency->format($result['special_price']);
                    } else {
                        $special_price = $result['special_price'];
                    }

                    $s_price = $result['special_price'];
                    $o_price = $result['price'];
                }

                //get qty in cart
                $key = base64_encode(serialize(['product_store_id' => (int) $result['product_store_id'], 'store_id' => $this->session->data['config_store_id']]));

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

                $data['products'][] = [
                    'key' => $key,
                    'qty_in_cart' => $qty_in_cart,
                    'variations' => $this->model_assets_product->getVariations($result['product_store_id']),
                    'store_product_variation_id' => 0,
                    'product_id' => $result['product_id'],
                    'product_store_id' => $result['product_store_id'],
                    'default_variation_name' => $result['default_variation_name'],
                    'thumb' => $image,
                    'name' => $name,
                    'unit' => $unit,
                    'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
                    'price' => $price,
                    'special' => $special_price,
                    'percent_off' => number_format($percent_off, 0),
                    'tax' => $result['tax_percentage'],
                    'minimum' => $result['min_quantity'] > 0 ? $result['min_quantity'] : $result['quantity'],
                    'rating' => 0,
                    'href' => $this->url->link('product/product', '&product_store_id=' . $result['product_store_id']),
                ];
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

            $data['sorts'] = [];

            $data['sorts'][] = [
                'text' => $this->language->get('text_default'),
                'value' => 'p.sort_order-ASC',
                'href' => $this->url->link('product/search', 'sort=p.sort_order&order=ASC' . $url),
            ];

            $data['sorts'][] = [
                'text' => $this->language->get('text_name_asc'),
                'value' => 'pd.name-ASC',
                'href' => $this->url->link('product/search', 'sort=pd.name&order=ASC' . $url),
            ];

            $data['sorts'][] = [
                'text' => $this->language->get('text_name_desc'),
                'value' => 'pd.name-DESC',
                'href' => $this->url->link('product/search', 'sort=pd.name&order=DESC' . $url),
            ];

            $data['sorts'][] = [
                'text' => $this->language->get('text_price_asc'),
                'value' => 'p.price-ASC',
                'href' => $this->url->link('product/search', 'sort=p.price&order=ASC' . $url),
            ];

            $data['sorts'][] = [
                'text' => $this->language->get('text_price_desc'),
                'value' => 'p.price-DESC',
                'href' => $this->url->link('product/search', 'sort=p.price&order=DESC' . $url),
            ];

            if ($this->config->get('config_review_status')) {
                $data['sorts'][] = [
                    'text' => $this->language->get('text_rating_desc'),
                    'value' => 'rating-DESC',
                    'href' => $this->url->link('product/search', 'sort=rating&order=DESC' . $url),
                ];

                $data['sorts'][] = [
                    'text' => $this->language->get('text_rating_asc'),
                    'value' => 'rating-ASC',
                    'href' => $this->url->link('product/search', 'sort=rating&order=ASC' . $url),
                ];
            }

            $data['sorts'][] = [
                'text' => $this->language->get('text_model_asc'),
                'value' => 'p.model-ASC',
                'href' => $this->url->link('product/search', 'sort=p.model&order=ASC' . $url),
            ];

            $data['sorts'][] = [
                'text' => $this->language->get('text_model_desc'),
                'value' => 'p.model-DESC',
                'href' => $this->url->link('product/search', 'sort=p.model&order=DESC' . $url),
            ];

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

            $data['limits'] = [];

            $limits = array_unique([$this->config->get('config_product_limit'), 25, 50, 75, 100]);

            sort($limits);

            foreach ($limits as $value) {
                $data['limits'][] = [
                    'text' => $value,
                    'value' => $value,
                    'href' => $this->url->link('product/search', $url . '&limit=' . $value),
                ];
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

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $pagination = new Pagination();
            $pagination->total = $product_total;
            $pagination->page = $page;
            $pagination->limit = $limit;
            $pagination->url = $this->url->link('product/search', $url . '&page={page}');

            $data['pagination'] = $pagination->render();

            $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));
        }

        $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_categories.css');

        $data['search'] = $search;
        $data['description'] = $description;
        $data['category_id'] = $category_id;
        $data['sub_category'] = $sub_category;

        $data['sort'] = $sort;
        $data['order'] = $order;
        $data['limit'] = $limit;

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $data['account_register'] = $this->load->controller('account/register');
        $data['login_modal'] = $this->load->controller('common/login_modal');
        $data['signup_modal'] = $this->load->controller('common/signup_modal');
        $data['forget_modal'] = $this->load->controller('common/forget_modal');

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['base'] = $server;

        $data['telephone_mask'] = $this->config->get('config_telephone_mask');

        if (isset($data['telephone_mask'])) {
            $data['telephone_mask_number'] = str_replace('#', '9', $this->config->get('config_telephone_mask'));
        }

        $data['taxnumber_mask'] = $this->config->get('config_taxnumber_mask');

        if (isset($data['taxnumber_mask'])) {
            $data['taxnumber_mask_number'] = str_replace('#', '*', $this->config->get('config_taxnumber_mask'));
        }

        //echo "<pre>";print_r($data);die;
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/search.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/search.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/product/search.tpl', $data));
        }
    }

    public function product_autocomplete() {
        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = '';
        }
        $this->load->model('assets/product');

        //$json =  $this->model_assets_product->getProductData($filter_name);
        $json = $this->model_assets_product->getProductDataByStore($filter_name);
        //echo "<pre>";print_r($json);die;

        $p['product_id'] = 'getall';
        $p['language_id'] = 'getall';
        $p['name'] = $this->language->get('text_show_all_results');
        $p['unit'] = '';

        array_push($json, $p);
        //echo "<pre>";print_r($json);die;

        echo json_encode($json);
    }

    public function product_search() {
        $cachePrice_data = $this->cache->get('category_price_data');

        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = '';
        }

        if (isset($this->request->get['filter_category'])) {
            $filter_category = $this->request->get['filter_category'];
        } else {
            $filter_category = '';
        }

        $this->load->model('assets/product');
        $this->load->model('assets/category');
        $this->load->model('tool/image');
        $categories = $this->model_assets_category->getCategoryByStoreId(ACTIVE_STORE_ID, 0);
        $categories = array_column($categories, 'category_id');

        $filter_data_product = [
            'start' => 0,
            'limit' => 5,
            'store_id' => ACTIVE_STORE_ID,
            'filter_name' => $filter_name,
            'filter_category_id' => $filter_category,
        ];

        //$json = $this->model_assets_product->getProducts($filter_data_product);
        $json = $this->model_assets_product->getProductsForHeaderSearch($filter_data_product);
        //echo "<pre>";print_r($json);die;
        //$json = array_unique($json);
        $products = [];
        foreach ($json as $key => $value) {
            if (in_array($value['category_id'], $categories)) {
                $link = $this->url->link('product/category', 'category=' . $value['category_id']);
                $link_array = explode('/', $link);
                $page_link = end($link_array);
                $value['href_cat'] = $this->url->link('product/store', 'store_id=' . $value['store_id']) . '?cat=' . $page_link . '&product=' . $value['pd_name'];
                if (file_exists(DIR_IMAGE . $value['image'])) {
                    $value['image'] = $this->model_tool_image->resize($value['image'], 100, 100);
                } else {
                    $value['image'] = $this->model_tool_image->resize('placeholder.png', 100, 100);
                }

                if (isset($this->session->data['config_store_id'])) {
                    $store_id1 = $this->session->data['config_store_id'];
                } else {
                    $store_id1 = $value['store_id'];
                }
                $key1 = base64_encode(serialize(['product_store_id' => (int) $value['product_store_id'], 'store_id' => $store_id1]));
                $value['key1'] = $key1;
                if (array_key_exists($key1, $this->session->data['cart']) && $this->session->data['cart'][$key1]['quantity']) {
                    $value['quantityadded'] = $this->session->data['cart'][$key1]['quantity'];
                } else {
                    $value['quantityadded'] = 0;
                }

                $s_price = 0;
                $o_price = 0;

                if (!$this->config->get('config_inclusiv_tax')) {
                    //FOR CATEGORY PRICING
                    $category_s_price = 0;
                    $category_o_price = 0;
                    if (CATEGORY_PRICE_ENABLED == true && isset($cachePrice_data) && isset($cachePrice_data[$value['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $value['store_id']])) {
                        $category_s_price = $cachePrice_data[$value['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $value['store_id']];
                        $category_o_price = $cachePrice_data[$value['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $value['store_id']];
                        if ($category_s_price != NULL && $category_s_price > 0) {
                            $value['price'] = $category_s_price;
                            $value['special_price'] = $category_s_price;
                        }
                    }
                    //FOR CATEGORY PRICING
                    //get price html
                    if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                        $price = $this->currency->format($this->tax->calculate($value['price'], $value['tax_class_id'], $this->config->get('config_tax')));

                        $o_price = $this->tax->calculate($value['price'], $value['tax_class_id'], $this->config->get('config_tax'));
                    } else {
                        $price = false;
                    }
                    if ((float) $value['special_price']) {
                        $special_price = $this->currency->format($this->tax->calculate($value['special_price'], $value['tax_class_id'], $this->config->get('config_tax')));

                        $s_price = $this->tax->calculate($value['special_price'], $value['tax_class_id'], $this->config->get('config_tax'));
                    } else {
                        $special_price = false;
                    }

                    $value['price'] = $o_price;
                    $value['special_price'] = $s_price;
                } else {
                    if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                        $price = $this->currency->format($value['price']);
                    } else {
                        $price = $value['price'];
                    }

                    if ((float) $value['special_price']) {
                        $special_price = $this->currency->format($value['special_price']);
                    } else {
                        $special_price = $value['special_price'];
                    }

                    $s_price = $value['special_price'];
                    $o_price = $value['price'];

                    // echo $s_price.'===>'.$o_price.'==>'.$special_price.'===>'.$price.'</br>';//exit;

                    if (CATEGORY_PRICE_ENABLED == true && isset($cachePrice_data) && isset($cachePrice_data[$value['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $value['store_id']])) {
                        $s_price = $cachePrice_data[$value['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $value['store_id']];
                        $o_price = $cachePrice_data[$value['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $value['store_id']];
                    }
                    $value['price'] = $o_price;
                    $value['special_price'] = $s_price;
                }

                /* if (CATEGORY_PRICE_ENABLED == true && isset($cachePrice_data) && isset($cachePrice_data[$value['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . ACTIVE_STORE_ID])) { */
                //echo $cachePrice_data[$product_info['product_store_id'].'_'.$_SESSION['customer_category'].'_'.$store_id];//exit;
                /* $s_price = $cachePrice_data[$value['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . ACTIVE_STORE_ID];
                  $o_price = $cachePrice_data[$value['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . ACTIVE_STORE_ID]; */
                /* $value['special_price'] = $this->currency->format($s_price);
                  $value['price'] = $this->currency->format($o_price); */
                /* $value['special_price'] = $s_price;
                  $value['price'] = $o_price;
                  } */
                $products[] = $value;
            }
        }

        //echo "<pre>";print_r($products);die;

        /* $p['product_id'] = 'getall';
          $p['language_id'] = 'getall';
          $p['name'] = $this->language->get( 'text_show_all_results' );
          $p['unit'] = ''; */

        //array_push($json, $p);
        //echo "<pre>";print_r($json);die;

        echo json_encode($products);
    }

    public function getHeaderPlace($location) {
        if (isset($_COOKIE['location_name']) && !empty($_COOKIE['location_name'])) {
            $p = $_COOKIE['location_name'];
        } else {
            $p = '';

            $userSearch = explode(',', $location);

            if (count($userSearch) >= 2) {
                $validateLat = is_numeric($userSearch[0]);
                $validateLat2 = is_numeric($userSearch[1]);

                $validateLat3 = strpos($userSearch[0], '.');
                $validateLat4 = strpos($userSearch[1], '.');

                if ($validateLat && $validateLat2 && $validateLat3 && $validateLat4) {
                    //echo "<pre>";print_r("er");die;
                    try {
                        $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . urlencode($location) . '&sensor=false&key=' . $this->config->get('config_google_server_api_key');

                        //echo "<pre>";print_r($url);
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        $headers = [
                            'Cache-Control: no-cache',
                        ];
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);

                        //curl_setopt($ch, CURLOPT_REFERER, '52.178.112.211');
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_WHATEVER);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                        $response = curl_exec($ch);
                        curl_close($ch);

                        $output = json_decode($response);

                        //echo "<pre>";print_r($output);die;
                        if (isset($output)) {
                            foreach ($output->results[0]->address_components as $addres) {
                                if (isset($addres->types)) {
                                    if (in_array('sublocality_level_1', $addres->types)) {
                                        //echo "<pre>";print_r($addres);die;
                                        $p = $addres->long_name;
                                        break;
                                    }
                                }
                            }
                            if (isset($output->results[0]->formatted_address)) {
                                $p = $output->results[0]->formatted_address;
                            }

                            $_COOKIE['location_name'] = $p;
                            setcookie('location_name', $p, time() + (86400 * 30 * 30 * 30 * 3), '/');
                        }
                    } catch (Exception $e) {
                        
                    }
                }
            }
        }

        return $p;
    }

}
