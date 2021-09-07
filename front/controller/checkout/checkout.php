<?php

class ControllerCheckoutCheckout extends Controller {

    public function index() {
        //echo "<pre>";print_r($this->session->data);die;
        $this->language->load('checkout/checkout');
        //check login

        $this->document->setTitle($this->language->get('heading_title'));

        if (!$this->customer->isLogged()) {
            $data['loginform'] = $this->load->controller('checkout/login/login');
            $data['loggedin'] = false;
        } else {
            $data['profile_complete'] = true;
            $user_telephone = $this->formatTelephone($this->customer->getTelephone());

            if (empty($user_telephone)) {
                $data['profile_complete'] = false;
            }
            $data['logout'] = $this->url->link('account/logout', '', 'SSL');

            $data['text_logged_in_as'] = $this->language->get('text_logged_in_as');
            //$data['loginform'] = $data['text_logged_in_as'].' ' . $this->customer->getFirstName(). ". <a id='". "checkoutLogout' style='cursor: pointer; cursor: hand;color: #f86e01;background-color: white;border-color: #f86e01;' type='button' class='btn btn-primary'> Logout </a>";
            $data['loginform'] = $data['text_logged_in_as'] . ' ' . $this->customer->getFirstName();
            $data['loggedin'] = true;
            $data['redirectopen2tab'] = $this->url->link('checkout/checkout#collapseTwo');
        }

        //echo "<pre>";print_r($this->language->get( 'heading_title' ));die;

        $this->load->model('account/address');

        $data['continue'] = $this->url->link('common/home');
        $data['button_continue'] = $this->language->get('button_continue');

        $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_checkout.css');

        // Validate cart has products and has stock.
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) /* || ( !$this->cart->hasStock() && !$this->config->get( 'config_stock_checkout' ) ) */) {
            $this->response->redirect($this->url->link('checkout/cart'));
        }

        $data['total_quantity'] = 0;
        $data['product_total_amount'] = 0;

        // Validate minimum quantity requirements.
        $products = $this->cart->getProducts();
        $product_total_count = 0;
        $product_total_amount = 0;

        $data['products_details'] = [];

        $this->load->model('tool/image');
        foreach ($products as $product) {
            $product_total = 0;

            foreach ($products as $product_2) {
                if ($product_2['product_store_id'] == $product['product_store_id']) {
                    $product_total += $product_2['quantity'];
                }
            }
            if (file_exists(DIR_IMAGE . $product['image'])) {
                $image = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
            }

            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
            } else {
                $price = false;
            }

            // Display prices
            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $total = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']);
            } else {
                $total = false;
            }
            $product_total_count += $product['quantity'];
            $product_total_amount += $product['total'];

            $data['products_details'][] = [
                'key' => $product['key'],
                'product_store_id' => $product['product_store_id'],
                'thumb' => $image,
                'name' => $product['name'],
                'product_type' => $product['product_type'],
                'produce_type' => $product['produce_type'],
                'unit' => $product['unit'],
                'model' => $product['model'],
                'quantity' => $product['quantity'],
                'stock' => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
                'reward' => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
                'price' => $price,
                'total' => $total,
                'store_id' => $product['store_id'],
                'href' => $this->url->link('product/product', 'product_store_id=' . $product['product_store_id']),
            ];

            /* if ( $product['minimum'] > $product_total ) {
              $this->response->redirect( $this->url->link( 'checkout/cart' ) );
              } */
        }
        //echo "<pre>";print_r($data['products_details']);die;

        $data['arrs'] = [];

        foreach ($data['products_details'] as $key => $item) {
            $data['arrs'][$item['store_id']][$key] = $item;
        }

        //echo "<pre>";print_r($data['arrs']);die;
        // echo "<pre>";print_r($data['products_details']);die;
        $data['total_quantity'] = $product_total_count;

        $data['product_total_amount'] = $this->currency->format($product_total_amount);

        $order_stores = $this->cart->getStores();

        $min_order_or_not = [];
        $store_data = [];

        foreach ($order_stores as $os) {
            $store_info = $this->model_account_address->getStoreData($os);
            $store_total = $this->cart->getSubTotal($os);
            $store_info['servicable_zipcodes'] = $this->model_account_address->getZipList($os);
            $store_data[] = $store_info;

            if ($this->cart->getTotalProductsByStore($os) && $store_info['min_order_amount'] > $store_total) {
                $this->response->redirect($this->url->link('checkout/cart'));
            }
        }

        //echo "<pre>";print_r($store_data);die;
        $data['kondutoStatus'] = $this->config->get('config_konduto_status');

        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        $data['telephone_mask'] = $this->config->get('config_telephone_mask');

        if (isset($data['telephone_mask'])) {
            $data['telephone_mask_number'] = str_replace('#', '9', $this->config->get('config_telephone_mask'));
        }

        $data['taxnumber_mask'] = $this->config->get('config_taxnumber_mask');

        if (isset($data['taxnumber_mask'])) {
            $data['taxnumber_mask_number'] = str_replace('#', '*', $this->config->get('config_taxnumber_mask'));
        }

        $data['latitude'] = null;
        $data['longitude'] = null;

        $data['detect_location'] = $this->language->get('detect_location');
        $data['text_locating'] = $this->language->get('text_locating');
        $data['locate_me'] = $this->language->get('locate_me');
        $data['text_ok'] = $this->language->get('text_ok');
        $data['text_your_location'] = $this->language->get('text_your_location');

        $data['check_address'] = false;

        if ($this->config->get('config_address_check')) {
            $data['check_address'] = true;
        }

        $data['checkout_question_enabled'] = false;

        if ($this->config->get('config_checkout_question_enabled')) {
            $data['checkout_question_enabled'] = true;
        }

        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_question'] = $this->language->get('text_question');

        $data['title'] = $this->language->get('heading_title');
        $data['text_or'] = $this->language->get('text_or');
        $data['text_promo_code'] = $this->language->get('text_promo_code');
        $data['text_dropoff_notes'] = $this->language->get('text_dropoff_notes');
        $data['heading_text'] = $this->language->get('heading_text');
        $data['text_deliver_here'] = $this->language->get('text_deliver_here');
        $data['text_not_deliver_here'] = $this->language->get('text_not_deliver_here');
        $data['text_new_delivery_adddress'] = $this->language->get('text_new_delivery_adddress');
        $data['text_delivery_option'] = $this->language->get('text_delivery_option');
        $data['text_next'] = $this->language->get('text_next');
        $data['text_delivery_timedate'] = $this->language->get('text_delivery_timedate');
        $data['text_deliver_charges'] = $this->language->get('text_deliver_charges');
        $data['text_store_name'] = $this->language->get('text_store_name');
        $data['text_payment'] = $this->language->get('text_payment');
        $data['text_item'] = $this->language->get('text_item');
        $data['text_other'] = $this->language->get('text_other');
        $data['text_free'] = $this->language->get('text_free');

        $data['text_replacable'] = $this->language->get('text_replacable');
        $data['text_not_replacable'] = $this->language->get('text_not_replacable');

        $data['text_replacable_title'] = $this->language->get('text_replacable_title');
        $data['text_not_replacable_title'] = $this->language->get('text_not_replacable_title');

        $data['text_name'] = $this->language->get('text_name');
        $data['text_view'] = $this->language->get('text_view');
        $data['text_office'] = $this->language->get('text_office');

        $data['text_flat_house_office'] = $this->language->get('text_flat_house_office');
        $data['text_stree_society_office'] = $this->language->get('text_stree_society_office');
        $data['text_locality'] = $this->language->get('text_locality');

        $data['text_home_address'] = $this->language->get('text_home_address');
        $data['text_other'] = $this->language->get('text_other');
        $data['text_office'] = $this->language->get('text_office');

        $data['text_cart'] = $this->language->get('text_cart');
        $data['text_signin'] = $this->language->get('text_signin');
        $data['text_place_order'] = $this->language->get('text_place_order');
        $data['text_select_address'] = $this->language->get('text_select_address');
        $data['text_delivery_details'] = $this->language->get('text_delivery_details');
        $data['text_delivery_options'] = $this->language->get('text_delivery_options');
        $data['text_delivery_time'] = $this->language->get('text_delivery_time');
        $data['text_payment_method'] = $this->language->get('text_payment_method');
        $data['text_apply_reward_points'] = $this->language->get('text_apply_reward_points');
        $data['title_availability'] = $this->language->get('title_availability');
        $data['title_price'] = $this->language->get('title_price');
        $data['title_text1'] = $this->language->get('title_text1');
        $data['title_text2'] = $this->language->get('title_text2');

        $data['text_office'] = $this->language->get('text_office');
        $data['text_home_address'] = $this->language->get('text_home_address');

        $data['entry_zipcode'] = $this->language->get('entry_zipcode');
        $data['entry_flat_number'] = $this->language->get('entry_flat_number');
        $data['entry_building_name'] = $this->language->get('entry_building_name');
        $data['entry_landmark'] = $this->language->get('entry_landmark');
        $data['entry_type'] = $this->language->get('entry_type');

        //echo "<pre>";print_r($data['entry_reward_points']);die;
        //echo "<pre>";print_r($this->customer->getRewardPoints());die;

        $data['label_name'] = $this->language->get('label_name');
        $data['label_zipcode'] = $this->language->get('label_zipcode');

        $data['label_phone'] = $this->language->get('label_phone');

        $data['label_city'] = $this->language->get('label_city');
        $data['label_address'] = $this->language->get('label_address');

        $data['button_add'] = $this->language->get('button_add');
        $data['button_confirm'] = $this->language->get('button_confirm');
        $data['save_address'] = $this->language->get('save_address');

        $data['error_name'] = $this->language->get('error_name');
        $data['error_phone'] = $this->language->get('error_phone');
        $data['error_address'] = $this->language->get('error_addresss');

        $this->document->addScript('front/ui/javascript/jquery/datetimepicker/moment.js');
        $this->document->addScript('front/ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
        $this->document->addStyle('front/ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

        // Required by klarna
        if ($this->config->get('klarna_account') || $this->config->get('klarna_invoice')) {
            $this->document->addScript('http://cdn.klarna.com/public/kitt/toc/v1.0/js/klarna.terms.min.js');
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_cart'),
            'href' => $this->url->link('checkout/cart'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('checkout/checkout', '', 'SSL'),
        ];

        $data = $this->language->all($data, ['error_agree']);

        $data['entry_reward_points'] = sprintf($this->language->get('entry_reward_points'), '' . $this->customer->getRewardPoints() . '');

        if ($this->config->get('config_checkout_id')) {
            $this->load->model('assets/information');

            $information_info = $this->model_assets_information->getInformation($this->config->get('config_checkout_id'));

            if ($information_info) {
                $data['error_agree'] = sprintf($this->language->get('error_agree'), $information_info['title']);
            }
        }

        if (isset($this->session->data['error'])) {
            $data['error_warning'] = $this->session->data['error'];
            unset($this->session->data['error']);
        } else {
            $data['error_warning'] = '';
        }

        $data['logged'] = $this->customer->isLogged();

        if (isset($this->session->data['account'])) {
            $data['account'] = $this->session->data['account'];
        } else {
            $data['account'] = '';
        }

        if (isset($this->request->post['address_type'])) {
            $data['address_type'] = $this->request->post['address_type'];
        } elseif (!empty($address_info)) {
            $data['address_type'] = $address_info['address_type'];
        } else {
            $data['address_type'] = false;
        }

        if (isset($this->error['error_flat_number'])) {
            $data['error_flat_number'] = $this->error['error_flat_number'];
        } else {
            $data['error_flat_number'] = '';
        }

        if (isset($this->error['error_building_name'])) {
            $data['error_building_name'] = $this->error['error_building_name'];
        } else {
            $data['error_building_name'] = '';
        }

        if (isset($this->error['error_contact_no'])) {
            $data['error_contact_no'] = $this->error['error_contact_no'];
        } else {
            $data['error_contact_no'] = '';
        }
        if (isset($this->error['error_landmark'])) {
            $data['error_landmark'] = $this->error['error_landmark'];
        } else {
            $data['error_landmark'] = '';
        }

        if (isset($this->request->post['flat_number'])) {
            $data['flat_number'] = $this->request->post['flat_number'];
        } elseif (!empty($address_info)) {
            $data['flat_number'] = $address_info['flat_number'];
        } else {
            $data['flat_number'] = '';
        }

        if (isset($this->request->post['building_name'])) {
            $data['building_name'] = $this->request->post['building_name'];
        } elseif (!empty($address_info)) {
            $data['building_name'] = $address_info['building_name'];
        } else {
            $data['building_name'] = '';
        }

        if (isset($this->request->post['landmark'])) {
            $data['landmark'] = $this->request->post['landmark'];
        } elseif (!empty($address_info)) {
            $data['landmark'] = $address_info['landmark'];
        } else {
            $data['landmark'] = '';
        }

        $data['coupon_apply'] = $this->url->link('checkout/coupon/coupon');
        $data['shipping_required'] = $this->cart->hasShipping();

        $data['account_register'] = $this->load->controller('account/register');
        $data['login_modal'] = $this->load->controller('common/login_modal');
        $data['signup_modal'] = $this->load->controller('common/signup_modal');
        $data['forget_modal'] = $this->load->controller('common/forget_modal');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/onlyHeader');

        $data['config'] = $this->config;

        $data['name'] = $this->customer->getFirstname() . ' ' . $this->customer->getLastname();
        $data['city_id'] = $store_info['city_id'];

        $data['store_name'] = $store_info['name'];

        $data['address_locality'] = '';

        $data['addresses'] = $this->model_account_address->getAddresses();

        $data['selected_address_id'] = NULL;
        $data['selected_address_data'] = NULL;
        if (isset($this->session->data['shipping_address_id']) && $this->session->data['shipping_address_id'] != NULL && $this->session->data['shipping_address_id'] > 0) {
            $selected_address_id = $this->session->data['shipping_address_id'];
            $data['selected_address_id'] = $selected_address_id;

            $data['selected_address_data'] = $this->model_account_address->getAddress($selected_address_id);
        }

        if ((!isset($this->session->data['shipping_address_id']) || $this->session->data['shipping_address_id'] == NULL || $this->session->data['shipping_address_id'] <= 0) && $this->customer->getAddressId() != NULL && $this->customer->getAddressId() > 0) {
            $selected_address_id = $this->customer->getAddressId();
            $data['selected_address_id'] = $selected_address_id;
            $data['selected_address_data'] = $this->model_account_address->getAddress($selected_address_id);
        }

        $data['selected_addresse'] = $this->model_account_address->getAddress($selected_address_id);

        $data['questions'] = $this->model_account_address->getCheckoutQuestion();

        /* $data['latitude'] = const_latitude;
          $data['longitude'] = const_longitude; */

        // echo "<pre>";print_r($data['addresses']);die;
        if (count($_COOKIE) > 0 && isset($_COOKIE['zipcode'])) {
            $data['zipcode'] = $_COOKIE['zipcode'];
        } elseif (count($_COOKIE) > 0 && isset($_COOKIE['location'])) {
            //echo "<pre>";print_r($_COOKIE['location']);die;

            $tmpD = explode(',', $_COOKIE['location']);

            if (count($tmpD) >= 2) {
                $data['latitude'] = $tmpD[0];
                $data['longitude'] = $tmpD[1];
            }

            //echo "<pre>";print_r($data);die;

            $addressTmp = $this->getZipcode($_COOKIE['location']);

            $addressLocality = $this->getPlace($_COOKIE['location']);

            /* if(isset($_COOKIE['location_name'])) {
              $addressLocality  = $_COOKIE['location_name'];
              } */

            $data['zipcode'] = $addressTmp ? $addressTmp : '';

            $data['address_locality'] = $addressLocality ? $addressLocality : '';

            /* echo "<pre>";print_r($store_info);
              echo "<pre>";print_r($data['addresses']);die; */

            $allAddresses = [];

            foreach ($data['addresses'] as $addre) {
                $addre['show_enabled'] = true;

                foreach ($order_stores as $os) {
                    $store_info = $this->model_account_address->getStoreData($os);

                    //echo "<pre>";print_r($store_info);die;
                    if (!empty($addre['latitude']) || !empty($addre['longitude']) || !empty($store_info['serviceable_radius'])) {
                        $res = $this->getDistance($addre['latitude'], $addre['longitude'], $data['latitude'], $data['longitude'], $store_info['serviceable_radius']);

                        if (!$res) {
                            $addre['show_enabled'] = false;
                            break;
                        }
                    }
                }

                $allAddresses[] = $addre;
            }

            $data['addresses'] = $allAddresses;

            //echo "<pre>";print_r($data);die;
        } else {
            $data['zipcode'] = '';
            /* New Code added */
            $data['latitude'] = $store_info['latitude'];
            $data['longitude'] = $store_info['longitude'];
            $location = $store_info['latitude'] . ',' . $store_info['longitude'];
            $addressTmp = $this->getZipcode($location);
            $addressLocality = $this->getPlace($location);
            $data['zipcode'] = $addressTmp ? $addressTmp : '';

            $data['address_locality'] = $addressLocality ? $addressLocality : '';

            //echo "<pre>";print_r($data);die;
            //echo "<pre>";print_r($data['addresses']);die;

            $allAddresses = [];

            foreach ($data['addresses'] as $addre) {
                $addre['show_enabled'] = true;

                foreach ($order_stores as $os) {
                    $store_info = $this->model_account_address->getStoreData($os);

                    //echo "<pre>";print_r($store_info);die;
                    if (!empty($addre['latitude']) || !empty($addre['longitude']) || !empty($store_info['serviceable_radius'])) {
                        $res = $this->getDistance($addre['latitude'], $addre['longitude'], $data['latitude'], $data['longitude'], $store_info['serviceable_radius']);

                        if (!$res) {
                            $addre['show_enabled'] = false;
                            break;
                        }
                    }
                }

                $allAddresses[] = $addre;
            }

            $data['addresses'] = $allAddresses;
            $data['address_locality'] = '';
        }

        //echo "<pre>";print_r($data['addresses']);die;
        //echo "<pre>";print_r($data['addresses']);die;

        $data['servicable_zipcodes'] = [];

        if (isset($store_info['zipcode'])) {
            $data['servicable_zipcodes'] = explode(',', $store_info['zipcode']);
        }

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['base'] = $server;

        $data['contact_no'] = $this->customer->getTelephone();
        $data['address'] = '';

        $data['address_id'] = $this->customer->getAddressId();

        //echo "<pre>";print_r($data['questions']);die;
        //get cities
        $data['cities'] = $this->model_account_address->getCities();

        $data['store_data'] = $store_data;

        $data['products'] = $products;

        if ($this->config->get('config_multi_store')) {
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/multi_store_checkout.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/checkout/multi_store_checkout.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/checkout/multi_store_checkout.tpl', $data));
            }
        } else {
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/checkout.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/checkout/checkout.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/checkout/checkout.tpl', $data));
            }
        }

        /*
          if ( file_exists( DIR_TEMPLATE . $this->config->get( 'config_template' ) . '/template/checkout/checkout.tpl' ) ) {
          $this->response->setOutput( $this->load->view( $this->config->get( 'config_template' ) . '/template/checkout/checkout.tpl', $data ) );
          } else {
          $this->response->setOutput( $this->load->view( 'default/template/checkout/checkout.tpl', $data ) );
          } */
    }

    public function country() {
        $json = [];

        $this->load->model('localisation/country');

        $country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

        if ($country_info) {
            $this->load->model('localisation/zone');

            $json = [
                'country_id' => $country_info['country_id'],
                'name' => $country_info['name'],
                'iso_code_2' => $country_info['iso_code_2'],
                'iso_code_3' => $country_info['iso_code_3'],
                'address_format' => $country_info['address_format'],
                'postcode_required' => $country_info['postcode_required'],
                'zone' => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
                'status' => $country_info['status'],
            ];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function customfield() {
        $json = [];

        $this->load->model('account/custom_field');

        // Customer Group
        if (isset($this->request->get['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->get['customer_group_id'], $this->config->get('config_customer_group_display'))) {
            $customer_group_id = $this->request->get['customer_group_id'];
        } else {
            $customer_group_id = $this->config->get('config_customer_group_id');
        }

        $custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

        foreach ($custom_fields as $custom_field) {
            $json[] = [
                'custom_field_id' => $custom_field['custom_field_id'],
                'required' => $custom_field['required'],
            ];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getAddressFromPost($args) {
        $type = $args[0];
        $is_guest = $args[1];

        $this->load->model('localisation/country');
        $this->load->model('localisation/zone');

        $address = [];

        $address['country_id'] = !empty($this->request->post['address_country_id']) ? $this->request->post['address_country_id'] : 0;
        $address['zone_id'] = !empty($this->request->post['address_zone_id']) ? $this->request->post['address_zone_id'] : 0;
        $address['city'] = !empty($this->request->post['address_city']) ? $this->request->post['address_city'] : '';
        $address['postcode'] = !empty($this->request->post['address_postcode']) ? $this->request->post['address_postcode'] : '';

        if (!empty($address['country_id'])) {
            $country = $this->model_localisation_country->getCountry($address['country_id']);

            $address['country'] = $country['name'];
            $address['iso_code_2'] = $country['iso_code_2'];
            $address['iso_code_3'] = $country['iso_code_3'];
            $address['address_format'] = $country['address_format'];
        } else {
            $address['country'] = '';
            $address['iso_code_2'] = '';
            $address['iso_code_3'] = '';
            $address['address_format'] = '';
        }

        if (!empty($address['zone_id'])) {
            $zone = $this->model_localisation_zone->getZone($address['zone_id']);

            $address['zone'] = $zone['name'];
            $address['zone_code'] = $zone['code'];
        } else {
            $address['zone'] = '';
            $address['zone_code'] = '';
        }

        $this->session->data[$type . '_address']['country_id'] = $address['country_id'];
        $this->session->data[$type . '_address']['zone_id'] = $address['zone_id'];
        $this->session->data[$type . '_address']['city'] = $address['city'];
        $this->session->data[$type . '_address']['postcode'] = $address['postcode'];

        if (true == $is_guest) {
            $this->session->data[$type . '_address']['country'] = $address['country'];
            $this->session->data[$type . '_address']['iso_code_2'] = $address['iso_code_2'];
            $this->session->data[$type . '_address']['iso_code_3'] = $address['iso_code_3'];
            $this->session->data[$type . '_address']['address_format'] = $address['address_format'];
            $this->session->data[$type . '_address']['zone'] = $address['zone'];
            $this->session->data[$type . '_address']['zone_code'] = $address['zone_code'];
        }

        return $address;
    }

    public function checksession() {
        echo '<pre>';
        //unset($this->session->data['shipping_method']);die;
        print_r($this->session->data);
        exit;
    }

    public function formatTelephone($telephone) {
        /* if(strlen($telephone) == 11 ) {
          //(21) 42353-5255

          $str1 = '(';
          $str3 = ')';
          $str4 = ' ';
          $str6 = '-';

          $str  = $telephone;
          $str2 = substr($str,0,2);
          $str5 = substr($str,2,5);
          $str7 = substr($str,7,4);


          return  $str1.$str2.$str3.$str4.$str5.$str6.$str7;
          } else {
          return $telephone;
          } */
        return $telephone;
    }

    public function saveQuestionResponse() {
        $this->load->language('checkout/checkout');

        //echo "saveQuestionResponse";
        $data = $this->request->post['data'];

        //echo "<pre>";print_r($data);

        $json['status'] = false;

        $this->load->model('assets/information');

        $res = $this->model_assets_information->saveQuestionResponse($data);

        if ($res) {
            $json['status'] = true;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getZipcode($address) {
        if (!empty($address)) {
            //Formatted address
            $formattedAddr = str_replace(' ', '+', $address);
            //Send request and receive json data by address

            $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $formattedAddr . '&sensor=false&key=' . $this->config->get('config_google_server_api_key');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);

            $headers = [
                'Cache-Control: no-cache',
            ];
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //curl_setopt($ch, CURLOPT_REFERER, '52.178.112.211');

            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_WHATEVER);

            $response = curl_exec($ch);
            curl_close($ch);
            $output1 = json_decode($response);

            //Get latitude and longitute from json data
            $latitude = $output1->results[0]->geometry->location->lat;
            $longitude = $output1->results[0]->geometry->location->lng;

            $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $latitude . ',' . $longitude . '&sensor=false&key=' . $this->config->get('config_google_server_api_key');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);

            $headers = [
                'Cache-Control: no-cache',
            ];
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //curl_setopt($ch, CURLOPT_REFERER, '52.178.112.211');

            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_WHATEVER);

            $response = curl_exec($ch);
            curl_close($ch);
            $output2 = json_decode($response);

            if (!empty($output2)) {
                $addressComponents = $output2->results[0]->address_components;
                foreach ($addressComponents as $addrComp) {
                    if ('postal_code' == $addrComp->types[0]) {
                        //Return the zipcode
                        return $addrComp->long_name;
                    }
                }

                return false;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getPlace($location) {
        $p = '';

        $userSearch = explode(',', $location);

        if (count($userSearch) >= 2) {
            $validateLat = is_numeric($userSearch[0]);
            $validateLat2 = is_numeric($userSearch[1]);

            $validateLat3 = strpos($userSearch[0], '.');
            $validateLat4 = strpos($userSearch[1], '.');

            if ($validateLat && $validateLat2 && $validateLat3 && $validateLat4) {
                $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $location . '&sensor=false&key=' . $this->config->get('config_google_server_api_key');

                //echo "<pre>";print_r($url);die;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                $headers = [
                    'Cache-Control: no-cache',
                ];
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);

                //curl_setopt($ch, CURLOPT_REFERER, '52.178.112.211');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_WHATEVER);

                $response = curl_exec($ch);

                //echo "<pre>";print_r($response);die;

                curl_close($ch);
                $output = json_decode($response);

                //print_r($output);die;

                if (isset($output)) {
                    $p = $output->results[0]->formatted_address;
                }
            }
        }

        return $p;
    }

    public function getDistance($latitude1, $longitude1, $latitude2, $longitude2, $storeRadius) {
        //$storeRadius = 2;
        $earth_radius = 6371;

        $dLat = deg2rad($latitude2 - $latitude1);
        $dLon = deg2rad($longitude2 - $longitude1);

        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * asin(sqrt($a));
        $d = $earth_radius * $c;

        //echo "<pre>";print_r($d);die;
        if ($d < $storeRadius) {
            //echo "Within 100 kilometer radius";
            return true;
        } else {
            //echo "Outside 100 kilometer radius";
            return false;
        }
    }

}
