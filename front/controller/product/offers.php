<?php

class ControllerProductOffers extends Controller
{
    public function index()
    {
        $this->load->language('product/category');

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

        $data['text_no_more_products'] = $this->language->get('text_no_more_products');
        $data['account_register'] = $this->load->controller('account/register');
        $data['login_modal'] = $this->load->controller('common/login_modal');
        $data['signup_modal'] = $this->load->controller('common/signup_modal');
        $data['forget_modal'] = $this->load->controller('common/forget_modal');
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

        $data['telephone_mask'] = $this->config->get('config_telephone_mask');

        if (isset($data['telephone_mask'])) {
            $data['telephone_mask_number'] = str_replace('#', '9', $this->config->get('config_telephone_mask'));
        }

        $data['taxnumber_mask'] = $this->config->get('config_taxnumber_mask');

        if (isset($data['taxnumber_mask'])) {
            $data['taxnumber_mask_number'] = str_replace('#', '*', $this->config->get('config_taxnumber_mask'));
        }

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

        $data['location_name'] = '';
        $data['zipcode'] = '';
        if (count($_COOKIE) > 0 && isset($_COOKIE['zipcode'])) {
            $data['zipcode'] = $_COOKIE['zipcode'];
        } elseif (count($_COOKIE) > 0 && isset($_COOKIE['location'])) {
            $data['location_name'] = $this->getHeaderPlace($_COOKIE['location']);

            /*if(isset($_COOKIE['location_name'])) {
                $data['location_name'] = $_COOKIE['location_name'];
            }*/
        }

        //$category_info = $this->model_assets_category->getCategory($category_id);

        if (true) {
            /*$title = empty($category_info['meta_title']) ? $category_info['name'] : $category_info['meta_title'];

            $this->document->setTitle($title);
            $this->document->setDescription($category_info['meta_description']);
            $this->document->setKeywords($category_info['meta_keyword']);*/
            /*if (!$this->config->get('config_seo_url')) {
                $this->document->addLink($this->url->link('product/offers'));
            }*/

            $this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_categories.css');
            $data['text_change_locality'] = $this->language->get('text_change_locality');
            $data['text_change_location_name'] = $this->language->get('text_change_location_name');
            $data['heading_title'] = $this->language->get('text_offer_title');
            $data['text_change_locality_warning'] = $this->language->get('text_change_locality_warning');
            $data['text_only_on_change_locality_warning'] = $this->language->get('text_only_on_change_locality_warning');
            $data['button_change_locality'] = $this->language->get('button_change_locality');
            $data['button_change_store'] = $this->language->get('button_change_store');
            $data['text_refine'] = $this->language->get('text_refine');
            $data['text_empty'] = $this->language->get('text_empty');
            $data['text_quantity'] = $this->language->get('text_quantity');
            $data['text_manufacturer'] = $this->language->get('text_manufacturer');
            $data['text_model'] = $this->language->get('text_model');
            $data['text_price'] = $this->language->get('text_price');
            $data['text_tax'] = $this->language->get('text_tax');
            $data['text_points'] = $this->language->get('text_points');
            $data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
            $data['text_sort'] = $this->language->get('text_sort');
            $data['text_limit'] = $this->language->get('text_limit');
            $data['text_incart'] = $this->language->get('text_incart');
            $data['text_view'] = $this->language->get('text_view');

            $data['error_no_delivery'] = $this->language->get('error_no_delivery');

            $data['button_cart'] = $this->language->get('button_cart');
            $data['button_wishlist'] = $this->language->get('button_wishlist');
            $data['button_compare'] = $this->language->get('button_compare');
            $data['button_continue'] = $this->language->get('button_continue');
            $data['button_list'] = $this->language->get('button_list');
            $data['button_grid'] = $this->language->get('button_grid');
            $data['button_add'] = $this->language->get('button_add');
            $data['button_clear_cart'] = $this->language->get('button_clear_cart');
            $data['button_checkout'] = $this->language->get('button_checkout');
            // Set the last category breadcrumb

            $data['compare'] = $this->url->link('product/compare');

            $url = '';

            if (isset($this->request->get['filter'])) {
                $url .= '&filter='.$this->request->get['filter'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort='.$this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order='.$this->request->get['order'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit='.$this->request->get['limit'];
            }

            $data['categories'] = [];

            $data['products'] = [];

            $filter_data = [
                'filter_filter' => $filter,
                'sort' => $sort,
                'order' => $order,
                'start' => ($page - 1) * $limit,
                'limit' => $limit,
            ];

            $product_total = $this->model_assets_product->getTotalOfferProductsBySpecialPrice($filter_data);

            $data['products'] = $this->getOfferProductsBySpecialPrice($filter_data);

            $template = 'offers.tpl';

            $url = '';

            if (isset($this->request->get['filter'])) {
                $url .= '&filter='.$this->request->get['filter'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort='.$this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order='.$this->request->get['order'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit='.$this->request->get['limit'];
            }

            $pagination = new Pagination();
            $pagination->total = $product_total;
            $pagination->page = $page;
            $pagination->limit = $limit;
            $pagination->url = $this->url->link('product/offers', $url.'&page={page}');

            $data['pagination'] = $pagination->render();

            $data['toHome'] = $this->url->link('common/home/toHome');
            $data['toStore'] = $this->url->link('common/home/toStore');

            $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

            $url = '';

            if (isset($this->request->get['filter'])) {
                $url .= '&filter='.$this->request->get['filter'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit='.$this->request->get['limit'];
            }

            $data['sorts'] = [];

            /*$data['sorts'][] = array(
                'text' => $this->language->get('text_default'),
                'value' => 'p.sort_order-ASC',
                'href' => $this->url->link('product/category', 'category=' . $this->request->get['category'] . '&sort=p.sort_order&order=ASC' . $url)
            );*/

            $url = '';

            if (isset($this->request->get['filter'])) {
                $url .= '&filter='.$this->request->get['filter'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort='.$this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order='.$this->request->get['order'];
            }

            $data['limits'] = [];

            $limits = array_unique([$this->config->get('config_product_limit'), 25, 50, 75, 100]);

            sort($limits);

            foreach ($limits as $value) {
                $data['limits'][] = [
                    'text' => $value,
                    'value' => $value,
                    'href' => $this->url->link('product/offers', $url.'&limit='.$value),
                ];
            }

            $data['sort'] = $sort;
            $data['order'] = $order;
            $data['limit'] = $limit;

            $data['continue'] = $this->url->link('common/home');

            $data['column_left'] = $this->load->controller('common/column_left');

            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            if (isset($this->session->data['warning'])) {
                $data['warning'] = $this->session->data['warning'];
                unset($this->session->data['warning']);
            } else {
                $data['warning'] = '';
            }

            $not_delivery = 0;
            /*if($this->cart->hasProducts()){
                $zipcode = $_COOKIE['zipcode'];

                foreach ($this->cart->getStores() as $s ) {
                    $checkStore = $this->model_assets_product->getStoreByZip($zipcode,$s);
                    if (!$checkStore) {
                        $not_delivery = 1;
                        break;
                    }
                }
            }*/
            $data['not_delivery'] = $not_delivery;
            $data['home'] = $this->url->link('common/home');

            if ($this->request->server['HTTPS']) {
                $server = $this->config->get('config_ssl');
            } else {
                $server = $this->config->get('config_url');
            }

            $data['base'] = $server;

            //echo "<pre>";print_r($data);die;
            if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/product/'.$template)) {
                //echo "<pre>";print_r($this->config->get('config_template') . '/template/product/'.$template);die;
                //echo "<pre>";print_r($data);die;
                $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/product/'.$template, $data));
            } else {
                //echo "<pre>s";print_r($this->config->get('config_template') . '/template/product/'.$template);die;
                $this->response->setOutput($this->load->view('default/template/product/'.$template, $data));
            }
        }
    }

    public function getOfferProductsBySpecialPrice($filter_data)
    {
        $this->load->model('assets/product');
        $this->load->model('tool/image');

        $results = $this->model_assets_product->getOfferProductsBySpecialPrice($filter_data);

        //echo "<pre>";print_r($results);die;
        $data['products'] = [];

        foreach ($results as $result) {
            if ($result['quantity'] <= 0) {
                continue;
            }

            if (file_exists(DIR_IMAGE.$result['image'])) {
                $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
            }

            //if category discount define override special price
            $discount = '';

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

            if (isset($result['pd_name'])) {
                $name = $result['pd_name'];
            }

            //$name .= str_repeat('&nbsp;',30 - strlen($result['name']));

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
                'unit' => $result['unit'],
                'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')).'..',
                'price' => $price,
                'special' => $special_price,
                'percent_off' => number_format($percent_off, 0),
                'tax' => $result['tax_percentage'],
                'minimum' => $result['min_quantity'] > 0 ? $result['min_quantity'] : $result['quantity'],
                'rating' => 0,
                'href' => $this->url->link('product/product', '&product_store_id='.$result['product_store_id']),
            ];
        }

        return $data['products'];
    }

    public function getOfferProducts($filter_data)
    {
        $this->load->model('assets/product');
        $this->load->model('tool/image');

        $results = $this->model_assets_product->getOfferProducts($filter_data);

        //echo "<pre>";print_r($results);die;
        $data['products'] = [];

        foreach ($results as $result) {
            if (file_exists(DIR_IMAGE.$result['image'])) {
                $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
            }

            //if category discount define override special price
            $discount = '';

            if (!$this->config->get('config_inclusiv_tax')) {
                //get price html
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $price = false;
                }
                if ((float) $result['special_price']) {
                    $special_price = $this->currency->format($this->tax->calculate($result['special_price'], $result['tax_class_id'], $this->config->get('config_tax')));
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
                'unit' => $result['unit'],
                'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')).'..',
                'price' => $price,
                'special' => $special_price,
                'tax' => $result['tax_percentage'],
                'minimum' => $result['min_quantity'] > 0 ? $result['min_quantity'] : $result['quantity'],
                'rating' => 0,
                'href' => $this->url->link('product/product', '&product_store_id='.$result['product_store_id']),
            ];
        }

        return $data['products'];
    }

    public function getHeaderPlace($location)
    {
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
                        $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.urlencode($location).'&sensor=false&key='.$this->config->get('config_google_server_api_key');

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
