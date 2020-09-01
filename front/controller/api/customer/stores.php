<?php

class ControllerApiCustomerStores extends Controller
{
    public function getFindStoresbyCategoryId($args = [])
    {
        $this->load->model('setting/store');
        $this->load->language('information/locations');
        $this->load->model('assets/category');
        $this->load->model('tool/image');
        $stores = $this->model_setting_store->getStoresAll($args);
        if ($args['location']) {
            $userSearch = explode(',', $args['location']);
        }
        //$data =array();
        foreach ($stores as $store) {
            $tempStore = $store;
            $tempStore['href'] = $this->model_setting_store->getSeoUrl('store_id='.$store['store_id']);
            $tempStore['thumb'] = $this->model_tool_image->resize($store['logo'], 300, 300);
            $tempStore['categorycount'] = $this->model_setting_store->getStoreCategoriesbyStoreId($store['store_id'], $args['category_id']);

            if ($args['category_id'] && $args['location']) {
                $res = $this->model_setting_store->getDistance($userSearch[0], $userSearch[1], $store['latitude'], $store['longitude'], $store['serviceable_radius']);
                if ($res && ($tempStore['categorycount'] > 0)) {
                    $tempstoreCategories = $this->model_setting_store->getStoreCategories($store['store_id']);
                    if (count($tempstoreCategories) > 0) {
                        $temp_cat_stories = [];
                        $tempArray = [];
                        foreach ($tempstoreCategories as $temp) {
                            array_push($tempArray, $temp['name']);
                            $temp['thumb'] = $this->model_tool_image->resize($temp['image'], 300, 300);
                            //echo "<pre>";print_r($temp);die;
                            if (0 == $temp['parent_id']) {
                                array_push($temp_cat_stories, $temp);
                            }
                        }
                        $tmp = implode(', ', $tempArray);
                        //$storeCategories1 = strlen($tmp) > 40 ? substr($tmp,0,40)."" : $tmp;
                        //$storeCategories = trim(strlen($tmp) > 80 ? substr($tmp,40,40)."..." : $tmp);
                        $storeCategories1 = $tmp;
                        $storeCategories = $tmp;
                        $storeCategoriesFull = $tmp;

                        //$tempStore['categories'] = $temp_cat_stories;
                    }
                    $tempStore['categories'] = $temp_cat_stories;
                    $tempStore['store_open_hours'] = $this->model_tool_image->getStoreOpenHours($store['store_id'], date('w'));
                    $tempStore['store_detail_one'] = htmlspecialchars_decode($storeCategories1);
                    $tempStore['store_detail_two'] = htmlspecialchars_decode($storeCategories);
                    $tempStore['store_detail_full'] = htmlspecialchars_decode($storeCategoriesFull);
                    $data['stores'][] = $tempStore;
                }
            } elseif ($args['category_id']) {
                if ($tempStore['categorycount'] > 0) {
                    $tempstoreCategories = $this->model_setting_store->getStoreCategories($store['store_id']);
                    if (count($tempstoreCategories) > 0) {
                        $temp_cat_stories = [];
                        $tempArray = [];
                        foreach ($tempstoreCategories as $temp) {
                            array_push($tempArray, $temp['name']);
                            $temp['thumb'] = $this->model_tool_image->resize($temp['image'], 300, 300);
                            //echo "<pre>";print_r($temp);die;
                            if (0 == $temp['parent_id']) {
                                array_push($temp_cat_stories, $temp);
                            }
                        }
                        $tmp = implode(', ', $tempArray);
                        //$storeCategories1 = strlen($tmp) > 40 ? substr($tmp,0,40)."" : $tmp;
                        //$storeCategories = trim(strlen($tmp) > 80 ? substr($tmp,40,40)."..." : $tmp);
                        $storeCategories1 = $tmp;
                        $storeCategories = $tmp;
                        $storeCategoriesFull = $tmp;

                        //$tempStore['categories'] = $temp_cat_stories;
                    }
                    $tempStore['categories'] = $temp_cat_stories;
                    $tempStore['store_open_hours'] = $this->model_tool_image->getStoreOpenHours($store['store_id'], date('w'));
                    $tempStore['store_detail_one'] = htmlspecialchars_decode($storeCategories1);
                    $tempStore['store_detail_two'] = htmlspecialchars_decode($storeCategories);
                    $tempStore['store_detail_full'] = htmlspecialchars_decode($storeCategoriesFull);
                    $data['stores'][] = $tempStore;
                }
            } elseif ($args['location']) {
                $res = $this->model_setting_store->getDistance($userSearch[0], $userSearch[1], $store['latitude'], $store['longitude'], $store['serviceable_radius']);
                if ($res) {
                    $tempstoreCategories = $this->model_setting_store->getStoreCategories($store['store_id']);
                    if (count($tempstoreCategories) > 0) {
                        $temp_cat_stories = [];
                        $tempArray = [];
                        foreach ($tempstoreCategories as $temp) {
                            array_push($tempArray, $temp['name']);
                            $temp['thumb'] = $this->model_tool_image->resize($temp['image'], 300, 300);
                            //echo "<pre>";print_r($temp);die;
                            if (0 == $temp['parent_id']) {
                                array_push($temp_cat_stories, $temp);
                            }
                        }
                        $tmp = implode(', ', $tempArray);
                        //$storeCategories1 = strlen($tmp) > 40 ? substr($tmp,0,40)."" : $tmp;
                        //$storeCategories = trim(strlen($tmp) > 80 ? substr($tmp,40,40)."..." : $tmp);
                        $storeCategories1 = $tmp;
                        $storeCategories = $tmp;
                        $storeCategoriesFull = $tmp;

                        //$tempStore['categories'] = $temp_cat_stories;
                    }
                    $tempStore['categories'] = $temp_cat_stories;
                    $tempStore['store_open_hours'] = $this->model_tool_image->getStoreOpenHours($store['store_id'], date('w'));
                    $tempStore['store_detail_one'] = htmlspecialchars_decode($storeCategories1);
                    $tempStore['store_detail_two'] = htmlspecialchars_decode($storeCategories);
                    $tempStore['store_detail_full'] = htmlspecialchars_decode($storeCategoriesFull);
                    $data['stores'][] = $tempStore;
                }
            }
        }
        $data['status'] = 200;
        $data['message'] = 'Store List fetched successfully';
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function getStoreType()
    {
        $this->load->model('tool/image');
        $this->load->model('assets/category');
        $store_types = $this->model_assets_category->getStoreTypes();
        $json = [];

        $json['status'] = 200;
        $json['data'] = $store_types;
        $json['message'] = 'Store type list fetched succesfully';

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getStore($args = [])
    {
        $this->load->language('api/products');

        $json = [];

        if (!isset($this->session->data['api_id']) || !isset($args['id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('tool/image');
            $this->load->model('api/stores');

            $json = $this->model_tool_image->getStore($args['id']);
        }
        //echo "<pre>";print_r($json);die;
        //http_response_code(200);
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getStoreShippingMethods()
    {
        $log = new Log('error.log');

        $log->write('getStoreShippingMethods');
        $log->write($this->request->get);
        //echo "<pre>";print_r('getStoreShippingMethods');die;
        $json = [];

        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');

        $this->load->model('assets/category');

        $this->load->model('assets/product');

        $this->load->model('tool/image');

        if (isset($this->request->get['store_id']) && isset($this->request->get['total']) && isset($this->request->get['subtotal']) && isset($this->request->get['shipping_address_id'])) {
            $log->write('if getStoreShippingMethods  ');
            $this->load->language('checkout/checkout');

            // Shipping Methods
            $method_data = [];

            $this->load->model('extension/extension');

            $results = $this->model_extension_extension->getExtensions('shipping');

            //echo "<pre>";print_r($results);die;
            if (isset($this->request->post['store_id'])) {
                $store_id = $this->request->post['store_id'];
            } else {
                $store_id = $this->request->get['store_id'];
            }

            $this->load->model('tool/image');

            $store_info = $this->model_tool_image->getStore($store_id);

            $delivery_by_owner = $store_info['delivery_by_owner'];

            $pickup_delivery = $store_info['store_pickup_timeslots'];

            $free_delivery_amount = $store_info['min_order_cod'];

            $store_total = $this->request->get['subtotal'];

            if ($store_total > $free_delivery_amount) {
                $cost = 0;
            } else {
                $cost = $store_info['cost_of_delivery'];
            }

            //echo "<pre>";print_r($results);die;
            foreach ($results as $result) {
                if ($this->config->get($result['code'].'_status')) {
                    if ('normal' == $result['code']) {
                        $this->load->model('shipping/'.$result['code']);
                        $quote = $this->{'model_shipping_'.$result['code']}->getShippingCharegApiQuote($cost, $store_info['name'], $this->request->get['subtotal'], $this->request->get['total'], $this->request->get['shipping_address_id'], $store_id);
                        if ($quote) {
                            $method_data[$result['code']] = [
                                'title' => $quote['title'],
                                'quote' => $quote['quote'],
                                'sort_order' => $quote['sort_order'],
                                'error' => $quote['error'],
                            ];
                        }
                    } elseif ('express' == $result['code']) {
                        //echo "<pre>";print_r('express');die;
                        $this->load->model('shipping/'.$result['code']);
                        $quote = $this->{'model_shipping_'.$result['code']}->getShippingCharegApiQuote($cost, $store_info['name'], $this->request->get['subtotal'], $this->request->get['total'], $this->request->get['shipping_address_id'], $store_id, $store_id);

                        if ($quote) {
                            $method_data[$result['code']] = [
                                'title' => $quote['title'],
                                'quote' => $quote['quote'],
                                'sort_order' => $quote['sort_order'],
                                'error' => $quote['error'],
                            ];
                        }
                    } elseif ('store_delivery' == $result['code']) {
                        if ($delivery_by_owner) {
                            $this->load->model('shipping/'.$result['code']);
                            $quote = $this->{'model_shipping_'.$result['code']}->getShippingCharegApiQuote($cost, $store_info['name'], $this->request->get['subtotal'], $this->request->get['total'], $this->request->get['shipping_address_id'], $store_id);
                            if ($quote) {
                                $method_data[$result['code']] = [
                                    'title' => 'Standard Delivery', //$quote['title'],
                                    'quote' => $quote['quote'],
                                    'sort_order' => $quote['sort_order'],
                                    'error' => $quote['error'],
                                ];
                            }
                        }
                    } elseif ('pickup' == $result['code']) {
                        if ($pickup_delivery) {
                            $this->load->model('shipping/'.$result['code']);
                            $quote = $this->{'model_shipping_'.$result['code']}->getShippingCharegApiQuote($cost, $store_info['name'], $this->request->get['subtotal'], $this->request->get['total'], $this->request->get['shipping_address_id'], $store_id);
                            if ($quote) {
                                $method_data[$result['code']] = [
                                    'title' => $quote['title'],
                                    'quote' => $quote['quote'],
                                    'sort_order' => $quote['sort_order'],
                                    'error' => $quote['error'],
                                ];
                            }
                        }
                    } else {
                        //echo "<pre>";print_r('express');die;
                        $this->load->model('shipping/'.$result['code']);
                        $quote = $this->{'model_shipping_'.$result['code']}->getShippingCharegApiQuote($cost, $store_info['name'], $this->request->get['subtotal'], $this->request->get['total'], $this->request->get['shipping_address_id'], $store_id);
                        if ($quote) {
                            $method_data[$result['code']] = [
                                'title' => $quote['title'],
                                'quote' => $quote['quote'],
                                'sort_order' => $quote['sort_order'],
                                'error' => $quote['error'],
                            ];
                        }
                    }
                }
            }

            $sort_order = [];

            foreach ($method_data as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }
            array_multisort($sort_order, SORT_ASC, $method_data);

            //echo "<pre>";print_r($method_data);die;

            $data = [];

            foreach ($method_data as $key => $value) {
                if (isset($value['quote'][$key])) {
                    array_push($data, $value['quote'][$key]);
                }
            }

            $json['data'] = $data;
        } elseif (isset($this->request->get['store_id'])) {
            $this->load->language('checkout/checkout');

            // Shipping Methods
            $method_data = [];

            $this->load->model('extension/extension');

            $results = $this->model_extension_extension->getExtensions('shipping');

            //echo "<pre>";print_r($results);die;
            if (isset($this->request->post['store_id'])) {
                $store_id = $this->request->post['store_id'];
            } else {
                $store_id = $this->request->get['store_id'];
            }

            $this->load->model('tool/image');

            $store_info = $this->model_tool_image->getStore($store_id);

            $delivery_by_owner = $store_info['delivery_by_owner'];

            $pickup_delivery = $store_info['store_pickup_timeslots'];

            $free_delivery_amount = $store_info['min_order_cod'];

            $store_total = $this->cart->getSubTotal($store_id);

            if ($store_total > $free_delivery_amount) {
                $cost = 0;
            } else {
                $cost = $store_info['cost_of_delivery'];
            }

            //echo "<pre>";print_r($results);die;
            foreach ($results as $result) {
                if ($this->config->get($result['code'].'_status')) {
                    if ('normal' == $result['code']) {
                        $this->load->model('shipping/'.$result['code']);
                        $quote = $this->{'model_shipping_'.$result['code']}->getQuote($cost, $store_info['name']);
                        if ($quote) {
                            $method_data[$result['code']] = [
                                'title' => $quote['title'],
                                'quote' => $quote['quote'],
                                'sort_order' => $quote['sort_order'],
                                'error' => $quote['error'],
                            ];
                        }
                    } elseif ('express' == $result['code']) {
                        //echo "<pre>";print_r('express');die;
                        $this->load->model('shipping/'.$result['code']);
                        $quote = $this->{'model_shipping_'.$result['code']}->getQuote($cost, $store_info['name'], $store_id);

                        if ($quote) {
                            $method_data[$result['code']] = [
                                'title' => $quote['title'],
                                'quote' => $quote['quote'],
                                'sort_order' => $quote['sort_order'],
                                'error' => $quote['error'],
                            ];
                        }
                    } elseif ('store_delivery' == $result['code']) {
                        if ($delivery_by_owner) {
                            $this->load->model('shipping/'.$result['code']);
                            $quote = $this->{'model_shipping_'.$result['code']}->getQuote($cost, $store_info['name']);
                            if ($quote) {
                                $method_data[$result['code']] = [
                                    'title' => 'Standard Delivery', //$quote['title'],
                                    'quote' => $quote['quote'],
                                    'sort_order' => $quote['sort_order'],
                                    'error' => $quote['error'],
                                ];
                            }
                        }
                    } elseif ('pickup' == $result['code']) {
                        if ($pickup_delivery) {
                            $this->load->model('shipping/'.$result['code']);
                            $quote = $this->{'model_shipping_'.$result['code']}->getQuote($cost, $store_info['name']);
                            if ($quote) {
                                $method_data[$result['code']] = [
                                    'title' => $quote['title'],
                                    'quote' => $quote['quote'],
                                    'sort_order' => $quote['sort_order'],
                                    'error' => $quote['error'],
                                ];
                            }
                        }
                    } else {
                        //echo "<pre>";print_r('express');die;
                        $this->load->model('shipping/'.$result['code']);
                        $quote = $this->{'model_shipping_'.$result['code']}->getQuote($cost, $store_info['name']);
                        if ($quote) {
                            $method_data[$result['code']] = [
                                'title' => $quote['title'],
                                'quote' => $quote['quote'],
                                'sort_order' => $quote['sort_order'],
                                'error' => $quote['error'],
                            ];
                        }
                    }
                }
            }

            $sort_order = [];

            foreach ($method_data as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }
            array_multisort($sort_order, SORT_ASC, $method_data);

            //echo "<pre>";print_r($method_data);die;

            $data = [];

            foreach ($method_data as $key => $value) {
                if (isset($value['quote'][$key])) {
                    array_push($data, $value['quote'][$key]);
                }
            }

            $json['data'] = $data;
        } else {
            $json['status'] = 10013;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_not_loggedin')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getFindStores($args = [])
    {
        $json = [];

        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        if (isset($this->request->get['filter_name'])) {
            //$filter_name = $this->request->get['filter_name'];
            $filter_name = urldecode($this->request->get['filter_name']);
        } else {
            $filter_name = '';
        }

        $log = new Log('error.log');
        $log->write('getFindStores');
        $log->write($this->request->get);

        if (isset($args['zipcode'])) {
            $this->load->model('setting/store');
            $stores = $this->model_setting_store->getStoreByZip($args['zipcode'], $filter_name);

            if ($stores) {
                $json['data'] = $this->stores($args);
            } else {
                $json['status'] = 10003;

                $json['message'][] = ['type' => '', 'body' => $this->language->get('text_error')];
            }
        } elseif (isset($args['location'])) {
            $this->load->model('setting/store');
            $stores = $this->model_setting_store->getStoreByLatLang($args['location'], $filter_name);

            if ($stores) {
                $json['data'] = $this->stores($args);
            } else {
                $json['status'] = 10003;

                $json['message'][] = ['type' => $this->language->get('text_not_found'), 'body' => $this->language->get('text_error')];
            }
        } else {
            $json['status'] = 10003;

            $json['message'][] = ['type' => $this->language->get('text_not_found'), 'body' => $this->language->get('text_error')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getFilterStores($args = [])
    {
        $json = [];

        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = '';
        }

        if (isset($args['zipcode'])) {
            $this->load->model('setting/store');
            $stores = $this->model_setting_store->getStoreByZip($args['zipcode'], $filter_name);

            if ($stores) {
                $json['data'] = $this->stores($args);
            } else {
                $json['status'] = 10004;

                $json['message'][] = ['type' => $this->language->get('text_not_found'), 'body' => sprintf($this->language->get('text_error_not_found'), $filter_name)];
            }
        } else {
            $json['status'] = 10004;

            $json['message'][] = ['type' => $this->language->get('text_not_found'), 'body' => sprintf($this->language->get('text_error_not_found'), $filter_name)];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getStoreHome($args = [])
    {
        $json = [];

        //echo "<pre>";print_r("re");die;
        $this->load->language('api/errors');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = '';
        }

        if (isset($args['store_id'])) {
            //echo "<pre>";print_r($args);die;
            $this->load->model('setting/store');
            $storeHomeData = $this->getStoreHomeData($args['store_id']);

            //echo "<pre>";print_r($storeHomeData);die;
            if ($storeHomeData['status']) {
                $json['data'] = $storeHomeData;
            } else {
                $json['status'] = 10006;

                $json['message'][] = ['type' => $this->language->get('store_data_retrival_failed'), 'body' => $this->language->get('store_data_empty')];
            }
        } else {
            $json['status'] = 10005;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('store_not_found')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getAllOfferProducts($args = [])
    {
        $json = [];

        //echo "<pre>";print_r("re");die;
        $this->load->language('api/errors');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = '';
        }

        if (isset($args['store_id'])) {
            //echo "<pre>";print_r($args);die;
            $this->load->model('setting/store');
            $offer_products = $this->allOfferProducts($args['store_id']);

            //echo "<pre>";print_r($offer_products);die;
            if ($offer_products['status']) {
                $json['data'] = $offer_products;
            } else {
                $json['status'] = 10006;

                $json['message'][] = ['type' => $this->language->get('store_data_retrival_failed'), 'body' => $this->language->get('store_data_empty')];
            }
        } else {
            $json['status'] = 10005;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('store_not_found')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function stores($args = [])
    {
        $this->load->language('information/locations');
        $this->load->model('setting/store');
        $this->load->model('assets/category');

        $this->load->model('tool/image');

        $location = null;

        if (isset($args['zipcode'])) {
            $zipcode = $args['zipcode'];
        } elseif (isset($args['location'])) {
            $location = $args['location'];
        } else {
            $zipcode = '';
            $location = '';
        }

        if (isset($args['filter_name'])) {
            $filter = $args['filter_name'];
        } else {
            $filter = '';
        }

        $data = [];

        $data['filter'] = $filter;

        $data['notices'] = [];

        $log = new Log('error.log');

        $log->write('store lisy api');

        if ($location) {
            $tempStores = $this->model_setting_store->getStoreByLatLang($location, $filter);

            $rows = $this->model_assets_category->getFullNoticeData($location);

            $log->write($rows);

            foreach ($rows as $row) {
                if (!empty($row) && is_file(DIR_IMAGE.$row['image'])) {
                    $row['image'] = $this->model_tool_image->resize($row['image'], $this->config->get('config_app_notice_image_location_height'), $this->config->get('config_app_notice_image_location_width'));
                }

                $data['notices'] = $row;
            }
        } else {
            $tempStores = $this->model_setting_store->getStoreByZip($zipcode, $filter);
        }

        $this->load->model('tool/image');

        $this->load->model('assets/category');
        //$data['categories'] = $this->model_assets_category->getCategories(0);
        $store_types = $this->model_assets_category->getStoreTypes();

        $storeTypes = [];

        foreach ($store_types as $store_type) {
            $temp_store_type['name'] = $store_type['name'];
            $temp_store_type['stores'] = [];

            foreach ($tempStores as $store) {
                if (in_array($store_type['store_type_id'], explode(',', $store['store_type_ids']))) {
                    $temp_store = $store;

                    if ($store['logo']) {
                        $image = $this->model_tool_image->resize($store['logo'], $this->config->get('config_app_image_location_width'), $this->config->get('config_app_image_location_height'));
                    } else {
                        $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_app_image_location_width'), $this->config->get('config_app_image_location_height'));
                    }

                    $temp_store['image'] = $image;

                    $temp_store['name'] = htmlspecialchars_decode($temp_store['name']);

                    //echo "<pre>";print_r($temp_store);die;
                    /*$temp_store['rating'] = 4.5;
                    $temp_store['review_count'] = 102;*/

                    $ratingDetails = $this->model_setting_store->getStoreRatingReviewCount($temp_store['store_id']);

                    //echo "<pre>";print_r($ratingDetails);die;
                    $temp_store['rating'] = $ratingDetails['rating'];
                    $temp_store['review_count'] = $ratingDetails['review_count'];

                    $store_open_hours = $this->model_tool_image->getStoreOpenHours($temp_store['store_id'], date('w'));
                    //echo '<pre>';print_r($store_open_hours);exit;
                    $temp_store['store_note'] = 'Same Prices as in Store';

                    /*if($store_open_hours && isset($store_open_hours['timeslot'])) {
                        $temp_store['store_note'] = $store_open_hours['timeslot'];
                    }*/
                    /*if(isset($temp_store['min_order_cod'])) {
                        $temp_store['store_note'] = 'Free Delivery above '.$this->currency->format($temp_store['min_order_cod']);
                    }*/

                    $deliveryTypesResponse = $this->getDeliveryMethods($temp_store['store_id']);

                    $deliveryTypes = ' -- ';
                    $nextTimeslot = ' -- ';
                    $distanceFromZipcode = ' -- ';
                    $storeCategories = ' -- ';
                    $storeCategories1 = ' -- ';
                    $storeCategoriesFull = ' -- ';

                    $deliveryTypeMethod = null;

                    if (count($deliveryTypesResponse) > 1) {
                        $deliveryTypes = $deliveryTypesResponse[0]['title'].'...';
                        $deliveryTypeMethod = $deliveryTypesResponse[0]['code'];
                    } elseif (1 == count($deliveryTypesResponse)) {
                        $deliveryTypes = $deliveryTypesResponse[0]['title'];
                        $deliveryTypeMethod = $deliveryTypesResponse[0]['code'];
                    }

                    //echo "<pre>";print_r($deliveryTypeMethod);die;

                    if (isset($deliveryTypeMethod)) {
                        $nextTimeslot = $this->getNextTimeslotForStore($temp_store['store_id'], $deliveryTypeMethod);
                    }

                    //echo "<pre>";print_r($nextTimeslot);die;

                    if (isset($temp_store['latitude']) && isset($temp_store['longitude'])) {
                        $distanceFromZipcode = 988;
                        //$distanceFromZipcode = $this->getDistanceFromZipcode($temp_store['latitude'],$temp_store['longitude'],$zipcode);
                    }

                    //echo "<pre>";print_r($distanceFromZipcode);die;

                    $tempstoreCategories = $this->model_setting_store->getStoreCategories($temp_store['store_id']);

                    //echo "<pre>";print_r($tempstoreCategories);die;

                    if (count($tempstoreCategories) > 0) {
                        $temp_cat_stories = [];
                        $tempArray = [];
                        foreach ($tempstoreCategories as $temp) {
                            array_push($tempArray, $temp['name']);
                            $temp['thumb'] = $this->model_tool_image->resize($temp['image'], 300, 300);
                            //echo "<pre>";print_r($temp);die;
                            if (0 == $temp['parent_id']) {
                                array_push($temp_cat_stories, $temp);
                            }
                        }

                        $tmp = implode(', ', $tempArray);
                        //$storeCategories1 = strlen($tmp) > 40 ? substr($tmp,0,40)."" : $tmp;
                        //$storeCategories = trim(strlen($tmp) > 80 ? substr($tmp,40,40)."..." : $tmp);
                        $storeCategories1 = $tmp;
                        $storeCategories = $tmp;
                        $storeCategoriesFull = $tmp;
                    }

                    $temp_store['store_open_hours'] = $store_open_hours;
                    $temp_store['categories'] = $temp_cat_stories;
                    //$temp_store['store_detail_one'] = $deliveryTypes .' | '. $nextTimeslot.' | '.$distanceFromZipcode;
                    $temp_store['store_detail_one'] = htmlspecialchars_decode($storeCategories1);
                    $temp_store['store_detail_two'] = htmlspecialchars_decode($storeCategories);
                    $temp_store['store_detail_full'] = htmlspecialchars_decode($storeCategoriesFull);

                    array_push($temp_store_type['stores'], $temp_store);
                }
            }

            if (count($temp_store_type['stores']) > 0) {
                array_push($storeTypes, $temp_store_type);
            }
        }

        $data['store_lists'] = $storeTypes;

        //echo "<pre>";print_r($data);die;

        return $data;
    }

    public function getDeliveryMethods($store_id)
    {
        $this->load->language('checkout/checkout');

        // Shipping Methods
        $method_data = [];

        $this->load->model('extension/extension');

        $results = $this->model_extension_extension->getExtensions('shipping');

        $this->load->model('tool/image');

        $store_info = $this->model_tool_image->getStore($store_id);

        $delivery_by_owner = $store_info['delivery_by_owner'];

        $pickup_delivery = $store_info['store_pickup_timeslots'];

        $free_delivery_amount = $store_info['min_order_cod'];

        $cost = 0;
        foreach ($results as $result) {
            if ($this->config->get($result['code'].'_status')) {
                $this->load->model('shipping/'.$result['code']);
                $quote = $this->{'model_shipping_'.$result['code']}->getQuote($cost, $store_info['name']);
                if ($quote) {
                    $method_data[] = [
                        'title' => $quote['title'],
                        'sort_order' => $quote['sort_order'],
                        'code' => $result['code'],
                    ];
                }
            }
        }

        $sort_order = [];

        foreach ($method_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }
        array_multisort($sort_order, SORT_ASC, $method_data);

        return $method_data;
    }

    public function getNextTimeslotForStore($store_id, $deliveryTypeMethod)
    {
        //$data['shipping_method'] = 'express';//$deliveryTypeMethod;
        $data['shipping_method'] = $deliveryTypeMethod;
        $data['store_id'] = $store_id;

        $timeInMin = $this->load->Controller('checkout/delivery_time/getApiNextTimeSlot', $data);

        $time = '--';
        $text = ' min';

        if (is_numeric($timeInMin)) {
            if ($timeInMin > 120) {
                $time = ceil($timeInMin / 60);
                $text = ' hr';
            } else {
                $time = $timeInMin;
            }

            return $time.$text;
        } else {
            $time = $timeInMin;

            return $time;
        }
    }

    public function getDistanceFromZipcode($pickup_lat, $pickup_lng, $dropoff_zipcode)
    {
        $url = 'http://maps.googleapis.com/maps/api/geocode/json?address='.$dropoff_zipcode.'&sensor=false';
        $details = file_get_contents($url);
        $result = json_decode($details, true);

        if (count($result['results']) > 0) {
            $dropoff_lat = $result['results'][0]['geometry']['location']['lat'];

            $dropoff_lng = $result['results'][0]['geometry']['location']['lng'];

            $distance = $this->distance($pickup_lat, $pickup_lng, $dropoff_lat, $dropoff_lng, 'K');

            //return $distance;
            return round($distance, 2).' Km';
        } else {
            return '--';
        }

        return $distance;
    }

    public function distance($lat1, $lon1, $lat2, $lon2, $unit)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ('K' == $unit) {
            return $miles * 1.609344;
        } elseif ('N' == $unit) {
            return $miles * 0.8684;
        } else {
            return $miles;
        }
    }

    public function getStoreHomeData($store_id)
    {
        //echo "<pre>";print_r("Cer");die;
        $data['status'] = false;

        if (isset($store_id)) {
            $this->session->data['config_store_id'] = $store_id;
        } else {
            return $data;
        }

        $this->load->language('product/store');
        $this->load->language('api/errors');

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

        if (isset($this->session->data['config_store_id'])) {
            $store_id = $this->session->data['config_store_id'];
        } else {
            $store_id = 0;
        }

        $store_info = $this->model_tool_image->getStore($store_id);

        if (isset($store_info['latitude']) && isset($store_info['longitude'])) {
            $storeLocation = $store_info['latitude'].','.$store_info['longitude'];
        } else {
            $storeLocation = 0;
        }

        $rows = $this->model_assets_category->getFullNoticeData($storeLocation);

        foreach ($rows as $row) {
            if (!empty($row) && is_file(DIR_IMAGE.$row['image'])) {
                $row['image'] = $this->model_tool_image->resize($row['image'], $this->config->get('config_app_notice_image_location_height'), $this->config->get('config_app_notice_image_location_width'));
            }

            $data['notices'] = $row;
        }

        if (!$store_info) {
            unset($this->session->data['config_store_id']);

            return $data;
        }

        $data['lists'] = [];

        if (false) {
        } else {
            //get listes
            //$data['lists'] = $this->model_assets_category->getUserLists();
        }

        //echo "<pre>";print_r($data['lists']);die;
        //if ( $store_info['logo'] ) {
        if (file_exists(DIR_IMAGE.$store_info['logo'])) {
            $data['thumb'] = $this->model_tool_image->resize($store_info['logo'], $this->config->get('config_app_image_category_width'), $this->config->get('config_app_image_category_height'));
        } else {
            $data['thumb'] = '';
        }

        /*if ( $store_info['banner_logo'] ) {
            $data['banner_logo'] = $this->model_tool_image->resize( $store_info['banner_logo'],800,450);
        } else {
            $data['banner_logo'] = $this->model_tool_image->resize( 'placeholder.png',800,450);
        }*/
        $tmp_slid = [];

        $slider_image = $this->model_assets_category->getStoreSlider($store_id);

        foreach ($slider_image as $slider) {
            $product_collection = $this->model_assets_product->getProductCollectionApi($slider['link']);

            if ($product_collection) {
                $slider['image'] = $this->model_tool_image->resize($slider['image'], 800, 450);

                $product_collection_info = $this->model_assets_product->getProductCollectionDescriptions($slider['link']);

                $name = '';
                if ($product_collection_info) {
                    $name = !empty($product_collection_info['name']) ? $product_collection_info['name'] : '';
                }

                $slider['name'] = htmlspecialchars_decode($name);

                array_push($tmp_slid, $slider);
            }
        }

        $data['banner_logos'] = $tmp_slid;

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

        $results = $this->model_assets_category->getCategoryByStore(0);

        //echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            $filter_data = [
                'filter_category_id' => $result['category_id'],
                'filter_sub_category' => true,
                'start' => 0,
                'limit' => 6,
            ];

            // Level 2
            $children_data = [];

            $children = $this->model_assets_category->getCategoriesByStoreId($result['category_id'], $store_id);

            $csvSubCategories = [];

            //echo "<pre>";print_r($children);die;
            foreach ($children as $child) {
                //if(!empty($child['image'])) {
                if (file_exists(DIR_IMAGE.$child['image'])) {
                    $image = $this->model_tool_image->resize($child['image'], $this->config->get('config_app_image_category_width'), $this->config->get('config_app_image_category_height'));
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_app_image_category_width'), $this->config->get('config_app_image_category_height'));
                }

                $children_data[] = [
                            'name' => htmlspecialchars_decode($child['name']),
                            'id' => $child['category_id'],
                            'href' => $this->url->link('product/category', 'category='.$result['category_id'].'_'.$child['category_id']),
                            'next_category_call_id' => $result['category_id'].'_'.$child['category_id'],
                            'thumb' => $image,
                            //'note' => $this->language->get( 'sub_category_note' ),
                            'note' => $child['max_discount'] >= 5 ? sprintf($this->language->get('sub_category_note'), (int) $child['max_discount'].'%') : '',
                    ];

                array_push($csvSubCategories, $child['name']);
            }

            $csvSubCategoriesData = implode(', ', $csvSubCategories);

            if (!empty($result['image'])) {
                $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_app_image_category_width'), $this->config->get('config_app_image_category_height'));
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_app_image_category_width'), $this->config->get('config_app_image_category_height'));
            }

            $data['categories'][] = [
                'name' => htmlspecialchars_decode($result['name']),
                'id' => $result['category_id'],
                //'products' => $this->getProducts( $filter_data ),
                'href' => $this->url->link('product/category', 'category='.$result['category_id'].$url),
                'thumb' => $image,
                'sub_category' => $children_data,
                //'csv_sub_category' => htmlspecialchars_decode(strlen($csvSubCategoriesData) > 50 ? substr($csvSubCategoriesData,0,50)."..." : $csvSubCategoriesData),
                'csv_sub_category' => htmlspecialchars_decode($csvSubCategoriesData),
                //'note' => $this->language->get( 'category_note' ),
                'note' => $result['max_discount'] >= 5 ? sprintf($this->language->get('category_note'), (int) $result['max_discount'].'%') : '',
            ];
        }

        $url = '';

        if (isset($this->request->get['filter'])) {
            $url .= '&filter='.$this->request->get['filter'];
        }

        if (isset($this->request->get['limit'])) {
            $url .= '&limit='.$this->request->get['limit'];
        }

        $data['offer_show'] = $this->config->get('config_offer_status');

        $data['offer_products'] = [];

        $filter_data = [
            'filter_store_id' => $store_id,
            'start' => 0,
            'limit' => 6,
        ];

        if ($data['offer_show']) {
            $data['offer_products'] = [
                'products' => $this->getOfferProductsBySpecialPrice($filter_data),
            ];
        }

        $data['status'] = true;

        return $data;
    }

    public function allOfferProducts($store_id)
    {
        $data['status'] = false;

        if (isset($store_id)) {
            $this->session->data['config_store_id'] = $store_id;
        } else {
            return $data;
        }

        $this->load->language('product/store');
        $this->load->language('api/errors');

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

        $store_info = $this->model_tool_image->getStore($store_id);

        if (!$store_info) {
            unset($this->session->data['config_store_id']);

            return $data;
        }

        $url = '';

        if (isset($this->request->get['filter'])) {
            $url .= '&filter='.$this->request->get['filter'];
        }

        if (isset($this->request->get['limit'])) {
            $url .= '&limit='.$this->request->get['limit'];
        }

        $data['offer_show'] = $this->config->get('config_offer_status');

        $filter_data = [
            'filter_store_id' => $store_id,
            'start' => ($page - 1) * $limit,
            'limit' => $limit,
        ];

        if ($data['offer_show']) {
            $data['offer_products'] = $this->getOfferProductsBySpecialPrice($filter_data);
        } else {
            $data['offer_products'] = [];
        }

        $data['total_product'] = $this->model_assets_product->getTotalOfferProductsBySpecialPrice();

        $data['status'] = true;

        return $data;
    }

    public function getOfferProductsBySpecialPrice($filter_data)
    {
        $this->load->model('assets/product');
        $this->load->model('tool/image');

        $results = $this->model_assets_product->getOfferProductsBySpecialPrice($filter_data);

        //echo "<pre>";print_r($results);die;
        $data['products'] = [];

        foreach ($results as $result) {
            // if qty less then 1 dont show product
            if ($result['quantity'] <= 0) {
                continue;
            }

            if (file_exists(DIR_IMAGE.$result['image'])) {
                $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_app_image_product_width'), $this->config->get('config_app_image_product_height'));
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_app_image_product_width'), $this->config->get('config_app_image_product_height'));
            }

            //if category discount define override special price
            $discount = '';

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
            $key = base64_encode(serialize(['product_store_id' => (int) $result['product_store_id'], 'store_id' => $this->session->data['config_store_id']]));

            if (isset($this->session->data['cart'][$key])) {
                $qty_in_cart = $this->session->data['cart'][$key]['quantity'];
            } else {
                $qty_in_cart = 0;
            }

            //$result['name'] = strlen($result['name']) > 27 ? substr($result['name'],0,27)."..." : $result['name'];
            $name = $result['name'];

            if (isset($result['pd_name'])) {
                //$result['name'] = $result['pd_name'];
                //$name = strlen($result['pd_name']) > 27 ? substr($result['pd_name'],0,27)."..." : $result['pd_name'];
                $name = $result['pd_name'];
            }

            //$name .= str_repeat('&nbsp;',30 - strlen($result['name']));

            $percent_off = null;
            if (isset($s_price) && isset($o_price) && 0 != $o_price && 0 != $s_price) {
                $percent_off = (($o_price - $s_price) / $o_price) * 100;
            }

            if (is_null($special_price) || !($special_price + 0)) {
                //$special_price = 0;
                $special_price = $price;
            }

            $productNames = array_column($data['products'], 'name');
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
                /*'qty_in_cart' => $qty_in_cart,
                'variations' => $this->model_assets_product->getVariations( $result['product_store_id'] ),
                'store_product_variation_id' => 0,
                */'product_id' => $result['product_id'],
                'product_store_id' => $result['product_store_id'],
                'variations' => $this->model_assets_product->getVariations($result['product_store_id']),
                //'default_variation_name' => $result['default_variation_name'],
                'thumb' => $image,
                'name' => html_entity_decode($name),
                'unit' => $result['unit'],
                'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')).'..',
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
                'href' => $this->url->link('product/product', '&product_store_id='.$result['product_store_id']),
            ];
            }
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
            // if qty less then 1 dont show product
            if ($result['quantity'] <= 0) {
                continue;
            }

            if (file_exists(DIR_IMAGE.$result['image'])) {
                $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_app_image_product_width'), $this->config->get('config_app_image_product_height'));
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_app_image_product_width'), $this->config->get('config_app_image_product_height'));
            }

            //if category discount define override special price
            $discount = '';

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
            $key = base64_encode(serialize(['product_store_id' => (int) $result['product_store_id'], 'store_id' => $this->session->data['config_store_id']]));

            if (isset($this->session->data['cart'][$key])) {
                $qty_in_cart = $this->session->data['cart'][$key]['quantity'];
            } else {
                $qty_in_cart = 0;
            }

            //$result['name'] = strlen($result['name']) > 27 ? substr($result['name'],0,27)."..." : $result['name'];
            $name = $result['name'];

            if (isset($result['pd_name'])) {
                //$result['name'] = $result['pd_name'];
                //$name = strlen($result['pd_name']) > 27 ? substr($result['pd_name'],0,27)."..." : $result['pd_name'];
                $name = $result['pd_name'];
            }

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
                'key' => $key,
                /*'qty_in_cart' => $qty_in_cart,
                'variations' => $this->model_assets_product->getVariations( $result['product_store_id'] ),
                'store_product_variation_id' => 0,
                */'product_id' => $result['product_id'],
                'product_store_id' => $result['product_store_id'],
                //'default_variation_name' => $result['default_variation_name'],
                'thumb' => $image,
                'name' => html_entity_decode($name),
                'unit' => $result['unit'],
                'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')).'..',
                'price' => $price,
                'special' => $special_price,
                'percent_off' => number_format($percent_off, 0),
                'left_symbol_currency' => $this->currency->getSymbolLeft(),
                'right_symbol_currency' => $this->currency->getSymbolRight(),

                'tax' => $result['tax_percentage'],
                //'minimum' => $result['min_quantity'] > 0 ? $result['min_quantity'] : 1,
                'max_qty' => $result['min_quantity'] > 0 ? $result['min_quantity'] : $result['quantity'],
                'rating' => 0,
                'href' => $this->url->link('product/product', '&product_store_id='.$result['product_store_id']),
            ];
        }

        return $data['products'];
    }
}
