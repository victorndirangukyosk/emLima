<?php

class ControllerCheckoutEditOrder extends Controller {

    public function formatTelephone($telephone) {
        return $telephone;
    }

    public function payment_method_button() {
        $log = new Log('error.log');
        $log->write('Log 1.cs');

        $log->write($this->request->post);

        //echo "<pre>";print_r($this->request->post);die;
        $redirect = '';
        //$this->session->data['payment_method']['code']
        //if(isset($this->request->post['payment_method'])) {
        if (isset($this->session->data['payment_method']['code'])) {
            $payment_method = $this->session->data['payment_method']['code'];
            $data['payment'] = $this->load->controller('payment/' . $payment_method . '/edit_order_index');

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/confirm.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/checkout/confirm.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/checkout/confirm.tpl', $data));
            }
        }
    }

    public function index() {
        $this->load->model('sale/order');

        if (isset($this->session->data['edit_order']) && $this->config->get('config_edit_order')) {
            //unset($this->session->data['edit_order']);
        } else {
            $this->response->redirect($this->url->link('account/account'));
        }

        $this->language->load('checkout/checkout');

        $data['update_order'] = $this->url->link('checkout/edit_order/updateOrder');
        $data['require_payment'] = false;

        $order_id = $this->session->data['edit_order'];

        if (!isset($this->session->data['order_id'])) {
            $this->session->data['order_id'][] = $order_id;
        }

        //echo "<pre>";print_r($order_id);die;
        $order_info = $this->model_sale_order->getOrder($this->session->data['edit_order']);

        //echo "<pre>";print_r($order_info);die;
        $store_id = 0;
        $shipping_code = 'normal.normal';
        $stripe_total = 0;
        if (!empty($order_info)) {
            $store_id = $order_info['store_id'];
            $shipping_code = $order_info['shipping_code'];

            if ('stripe' == $order_info['payment_code']) {
                $stripe_total = $order_info['stripe_total'];
            } else {
                $stripe_total = $order_info['total'];
            }
        }

        $data['store_id'] = $store_id;
        $data['shipping_code'] = $shipping_code;

        $other_charges = 0;

        $totals = $this->model_sale_order->getOrderTotals($order_id);

        if (isset($this->session->data['shipping_method'])) {
            foreach ($this->session->data['shipping_method'] as $key => $value) {
                $other_charges += $value['shipping_method']['cost'];
            }
        }

        /* foreach ($totals as $total) {

          if($total['code'] != 'total' && $total['code'] != 'sub_total') {
          $other_charges += $total['value'];
          }

          } */

        $final_amount = $this->cart->getSubTotal() + $other_charges;

        //echo "<pre>";print_r($stripe_total);print_r($final_amount);die;

        if ($final_amount > $stripe_total) {
            $data['require_payment'] = true;
        }

        //check login

        $this->document->setTitle($this->language->get('heading_title'));

        //check login
        if (!$this->customer->isLogged()) {
            $this->response->redirect($this->url->link('common/home'));
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

        //echo "<pre>";print_r($products);die;
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
            if (!empty($product['image']) && file_exists(DIR_IMAGE . $product['image'])) {
                $image = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
            } else {
                $image = $this->model_tool_image->resize('no_image.png', $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
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

            if ($this->cart->getTotalProductsByStore($os) && $this->config->get('config_active_store_minimum_order_amount') > $this->cart->getSubTotal()) {
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

        $data['entry_reward_points'] = $this->language->get('entry_reward_points');

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

        $data['questions'] = $this->model_account_address->getCheckoutQuestion();

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

            $data['zipcode'] = $addressTmp ? $addressTmp : '';

            $data['address_locality'] = $addressLocality ? $addressLocality : '';

            /* echo "<pre>";print_r($store_info);
              echo "<pre>";print_r($data['addresses']);die; */

            $allAddresses = [];

            foreach ($data['addresses'] as $addre) {
                $addre['show_enabled'] = false;

                if (!empty($addre['latitude']) || !empty($addre['longitude']) || !empty($store_info['serviceable_radius'])) {
                    $res = $this->getDistance($addre['latitude'], $addre['longitude'], $data['latitude'], $data['longitude'], $store_info['serviceable_radius']);

                    if ($res) {
                        $addre['show_enabled'] = true;
                    }
                }

                $allAddresses[] = $addre;
            }

            $data['addresses'] = $allAddresses;

            //echo "<pre>";print_r($data['addresses']);die;
            //echo "<pre>";print_r($data);die;
        } else {
            $data['zipcode'] = '';
        }

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

        //echo "<pre>";print_r($data['arrs']);die;
        if ($this->config->get('config_multi_store')) {
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/edit_order.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/checkout/edit_order.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/checkout/edit_order.tpl', $data));
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

    public function updateOrder() {
        $json = [];

        //echo "<pre>";print_r($this->session->data);die;
        $this->load->language('sale/order');
        $log = new Log('error.log');
        $log->write('api/updateInvoice');

        $this->load->model('sale/order');
        $this->load->model('tool/image');

        $order_id = $this->session->data['edit_order'];

        if (isset($this->session->data['edit_order']) && $this->config->get('config_edit_order')) {
            
        } else {
            $this->response->redirect($this->url->link('account/account'));
        }

        $order_info = $this->model_sale_order->getOrder($order_id);

        $order_number = 0;

        if (!empty($order_info)) {
            $order_number = $order_info['order_number'];
        }

        $totals = $this->model_sale_order->getOrderTotals($order_id);

        $shipping_total = [];

        if (isset($this->session->data['shipping_method'])) {
            foreach ($this->session->data['shipping_method'] as $key => $value) {
                $tmpData['code'] = $value['shipping_method']['code'];
                $tmpData['title'] = $value['shipping_method']['title_with_store'];
                $tmpData['value'] = $value['shipping_method']['cost'];
                $tmpData['actual_value'] = $value['shipping_method']['actual_cost'];
                $shipping_total = $tmpData;
            }
        }

        /* foreach ($totals as $total) {


          if($total['code'] == 'shipping') {
          $shipping_total = $total;
          break;
          }

          } */

        $sendData['codes'] = 'shipping';
        $sendData['totals']['shipping'] = $shipping_total;

        foreach ($this->cart->getProducts() as $key => $value) {
            $rn = $this->getRandomString();
            $sendData['products'][$rn] = $value;
            $sendData['products'][$rn]['product_id'] = $value['product_store_id'];
        }

        $url_data = [];
        $log->write('if');

        $curl = curl_init();
        $url = HTTPS_ADMIN . 'index.php?path=sale/order/EditOrder&order_id=' . $order_id;

        $log->write($url);

        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $url);

        if ($sendData) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($sendData));
        }

        $json = curl_exec($curl);

        $json = json_decode($json);

        $res['redirect'] = $order_number;
        $res['status'] = false;
        $res['message'] = '';

        if (isset($json->status) && $json->status) {
            $res['status'] = $json->status;

            $this->session->data['success'] = 'Updated Order Successfully';
            if (isset($this->session->data['edit_order'])) {
                unset($this->session->data['edit_order']);
                unset($this->session->data['order_id']);
            }
        } else {
            $res['message'] = $json->message;

            $this->session->data['error'] = 'Editing Order Failed';
        }

        /* $res['redirect'] = $this->url->link( 'account/order/realinfo','order_id='.$order_number );
          $res['status'] = false; */

        $res = json_encode($res);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput($res);
    }

    public function updateOrderAfterPayment() {
        $json = [];

        $this->load->language('sale/order');
        $log = new Log('error.log');
        $log->write('api/updateOrderAfterPayment');

        $this->load->model('sale/order');
        $this->load->model('tool/image');

        $order_id = $this->session->data['edit_order'];

        if (isset($this->session->data['edit_order']) && $this->config->get('config_edit_order')) {
            
        } else {
            $this->response->redirect($this->url->link('account/account'));
        }

        $order_info = $this->model_sale_order->getOrder($order_id);

        $order_number = 0;

        if (!empty($order_info)) {
            $order_number = $order_info['order_number'];
        }

        $totals = $this->model_sale_order->getOrderTotals($order_id);

        $shipping_total = [];

        if (isset($this->session->data['shipping_method'])) {
            foreach ($this->session->data['shipping_method'] as $key => $value) {
                $tmpData['code'] = $value['shipping_method']['code'];
                $tmpData['title'] = $value['shipping_method']['title_with_store'];
                $tmpData['value'] = $value['shipping_method']['cost'];
                $tmpData['actual_value'] = $value['shipping_method']['actual_cost'];
                $shipping_total = $tmpData;
            }
        }

        /* foreach ($totals as $total) {


          if($total['code'] == 'shipping') {
          $shipping_total = $total;
          break;
          }

          } */

        $sendData['codes'] = 'shipping';
        $sendData['totals']['shipping'] = $shipping_total;

        foreach ($this->cart->getProducts() as $key => $value) {
            $rn = $this->getRandomString();
            $sendData['products'][$rn] = $value;
            $sendData['products'][$rn]['product_id'] = $value['product_store_id'];
        }

        $url_data = [];
        $log->write('if');

        $curl = curl_init();
        $url = HTTPS_ADMIN . 'index.php?path=sale/order/EditOrderNoPayment&order_id=' . $order_id;

        $log->write($url);

        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $url);

        if ($sendData) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($sendData));
        }

        $json = curl_exec($curl);

        $json = json_decode($json);

        $res['redirect'] = $this->url->link('account/order/realinfo', 'order_id=' . $order_number);
        $res['status'] = false;

        if (isset($json->status) && $json->status) {
            $res['status'] = $json->status;

            $this->session->data['success'] = 'Updated Order Successfully';
            if (isset($this->session->data['edit_order'])) {
                unset($this->session->data['edit_order']);
                unset($this->session->data['order_id']);
            }
        } else {
            $this->session->data['error'] = 'Editing Order Failed';
        }

        $log->write('response EditOrderNoPayment');
        $log->write($res);

        return $res;
    }

    public function updateOrderOld() {
        $json = [];

        $this->load->language('sale/order');

        $json['status'] = true;
        $log = new Log('error.log');
        $log->write('api/updateInvoice');

        $this->load->model('sale/order');

        $log->write('api/updateInvoice 1');
        $this->load->model('tool/image');

        $datas = $this->request->post;

        $uniqueIds = array_keys($datas['products']);
        $log->write($datas);

        $order_id = $this->request->get['order_id'];

        $order_info = $this->model_sale_order->getOrder($order_id);

        $store_id = 0;

        if (!empty($order_info)) {
            $store_id = $order_info['store_id'];
        }

        $shipping_city_id = 0;

        if (!empty($order_info)) {
            $shipping_city_id = $order_info['shipping_city_id'];
        }

        $stripe_total = 0;

        //echo "<pre>";print_r($datas);die;
        if (true) {
            // Store

            if (isset($order_info['total'])) {
                $old_total = $order_info['total'];
            }

            if (isset($order_info['stripe_total'])) {
                $stripe_total = $order_info['stripe_total'];
            }

            $old_sub_total = 0;

            $old_sub_total = $this->model_sale_order->getOrderProductsSum($order_id, $store_id);

            $totals = $this->model_sale_order->getOrderTotals($order_id);
            foreach ($totals as $total) {
                /* if($total['code'] == 'sub_total') {
                  $old_sub_total = $total['value'];
                  } */

                if ('total' == $total['code']) {
                    $old_total = $total['value'];
                }
            }

            $allProductIds = $this->model_sale_order->getOrderProductsIds($order_id);
            foreach ($allProductIds as $deletePro) {
                if (!isset($datas['products'][$deletePro['product_id']])) {
                    $products = $this->model_sale_order->deleteOrderProduct($order_id, $deletePro['product_id']);
                } else {
                    //$log->write("set");
                }
            }

            $sumTotal = 0;

            $tempProds['products'] = [];

            $log->write($datas['products']);

            $vendor_id = $this->model_sale_order->getVendorId($store_id);

            //echo "<pre>";print_r($datas['products']);die;
            foreach ($datas['products'] as $p_id_key => $updateProduct) {
                $updateProduct['store_id'] = $store_id;
                $updateProduct['vendor_id'] = $vendor_id;

                if (is_numeric($p_id_key)) {
                    //echo "<pre>";print_r($datas['products']);die;
                    $products = $this->model_sale_order->updateOrderProduct($order_id, $p_id_key, $updateProduct);
                } else {
                    //echo "<pre>";print_r($updateProduct);die;
                    // new product added

                    /* $new_product_id = $this->addNewProduct($updateProduct);

                      if($new_product_id) {
                      $updateProduct['product_id'] = $new_product_id;
                      }


                      $products = $this->model_sale_order->updateOrderNewProduct($order_id,$updateProduct['product_id'],$updateProduct); */
                }

                $sumTotal += ($updateProduct['price'] * $updateProduct['quantity']);

                array_push($tempProds['products'], $updateProduct);
            }

            $subTotal = $sumTotal;

            //$log->write("tax_total start ");
            //$tax_total= $this->model_tool_image->getTaxTotal($tempProds,$store_id);
            $tax_total = $this->model_sale_order->getTaxTotal($tempProds, $store_id);

            //echo "<pre>";print_r($tax_total);die;

            if (count($tax_total) > 0) {
                foreach ($tax_total as $x => $tmpV) {
                    array_push($datas['totals'], $tmpV);
                }
            }

            //unset totals coming from web
            if (isset($datas['totals']['tax'])) {
                unset($datas['totals']['tax']);
            }

            //saving order total below

            $this->model_sale_order->deleteOrderTotal($order_id);

            foreach ($datas['totals'] as $p_id_code => $tot) {
                $sumTotal += $tot['value'];
            }

            $orderTotal = $sumTotal;

            //get shipping method and get price
            //echo "<pre>";print_r($order_info);die;
            $tmp = explode('.', $order_info['shipping_code']);

            $shipping_price = [];

            if ('express' == $tmp[0] || 'normal' == $tmp[0]) {
                $p = $tmp[0] . '_free_delivery_amount';
                $free_delivery_amount = $this->config->get($p);

                if (isset($store_id) && $store_id) {
                    $store_info = $this->model_sale_order->getStore($store_id);

                    if ($store_info) {
                        $free_delivery_amount = $store_info['min_order_cod'];
                    }
                }

                if ($old_sub_total < $free_delivery_amount) {
                    $this->load->model('shipping/' . $tmp[0]);
                    $shipping_price = $this->{'model_shipping_' . $tmp[0]}->getPrice($store_id, $subTotal, $orderTotal, $order_info['latitude'], $order_info['longitude'], $shipping_city_id);

                    $log->write($shipping_price);
                }
            }

            $p = 2;

            //$log->write("datas_totals");

            foreach ($datas['totals'] as $p_id_code => $tot) {
                /* $log->write("updatetotals");
                  $log->write($tot); */
                $tot['sort'] = $p;
                $this->model_sale_order->insertOrderTotal($order_id, $tot, $shipping_price);

                if ('shipping' == $tot['code'] && count($shipping_price) > 0 && isset($shipping_price['cost']) && isset($shipping_price['actual_cost'])) {
                    $orderTotal -= $tot['value'];
                    $orderTotal += $shipping_price['cost'];
                }

                ++$p;
            }

            $orderTotal = round($orderTotal, 2);
            $subTotal = round($subTotal, 2);

            $this->model_sale_order->insertOrderSubTotalAndTotal($order_id, $subTotal, $orderTotal, $p);

            //echo "<pre>";print_r($shipping_price);die;
            // editDeliveryRequest

            if (true) {
                //settle and  update
                $log->write('if settle');
                $customer_id = $this->customer->getId();
                $final_amount = $orderTotal;

                $log->write($final_amount);
                $log->write($old_total);

                if ($final_amount != $old_total) {
                    if ('stripe' == $order_info['payment_code']) {
                        $iuguData = true;

                        //if($final_amount > $old_total + $this->config->get('stripe_order_buffer_amount')) {//25 is buffer amount charged
                        if ($final_amount > $stripe_total) {//25 is buffer amount charged
                            $iuguData = false;
                            $iuguData = $this->refundAndChargeNewTotalStripe($order_id, $customer_id, $final_amount);
                        }

                        if (!$iuguData) {
                            $json['status'] = false;

                            $data['order_id'] = $order_id;
                            $this->cancelOrder($data);
                            //mark order failed and cancel DS request
                        } else {
                            $this->editDeliveryRequest($order_id);
                        }
                    } else {
                        // for payment type other than stripe
                        $json['status'] = true;
                        $this->editDeliveryRequest($order_id);
                    }
                } else {
                    $log->write('same amount settle');
                }
            } else {
                
            }

            //$this->sendNewInvoice($order_id);
        } else {
            $json['status'] = false;
        }

        $json = json_encode($json);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput($json);
    }

    public function getRandomString($length = 5) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = '';

        for ($i = 0; $i < $length; ++$i) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        return $string;
    }

    public function updateNewShippingAddressFromAdmin() {
        $log = new Log('error.log');
        $log->write('updateNewShippingAddressFromAdmin');
        $log->write($this->request->post);
        $json['message'] = "<center style='color:green'>" . $this->language->get('text_edited_success') . '</center>';

        $this->load->model('account/order');

        $this->load->model('api/checkout');

        $order_id = $this->request->post['order_id'];
        $order_info = $this->model_api_checkout->getOrder($order_id);

        if ($order_info) {
            $edited = $this->model_account_order->updateNewShippingAddress($order_id, $this->request->post);

            $log->write($edited);

            $order_info = $this->model_api_checkout->getOrder($order_id);

            if ($edited && $order_info) {
                //echo "<pre>";print_r($order_info);die;
                $deliveryAlreadyCreated = $this->model_account_order->getOrderDSDeliveryId($order_id);

                //$deliveryAlreadyCreated = true;

                $log->write($deliveryAlreadyCreated);

                if ($deliveryAlreadyCreated) {
                    $deliverAddress = $order_info['shipping_flat_number'] . ', ' . $order_info['shipping_building_name'] . ', ' . $order_info['shipping_landmark'];

                    $data['body'] = [
                        'manifest_id' => $deliveryAlreadyCreated, //order_id,
                        'dropoff_address' => $deliverAddress,
                        'to_lat' => $order_info['latitude'],
                        'to_lng' => $order_info['longitude'],
                        'dropoff_zipcode' => $order_info['shipping_zipcode'], // from $order_info['city_id'],
                    ];

                    $log->write($data['body']);

                    $data['email'] = $this->config->get('config_delivery_username');
                    $data['password'] = $this->config->get('config_delivery_secret');
                    $response = $this->load->controller('deliversystem/deliversystem/getToken', $data);

                    $log->write('token');
                    $log->write($response);
                    if ($response['status']) {
                        $data['tokens'] = $response['token'];
                        $res = $this->load->controller('deliversystem/deliversystem/updateDeliveryAddress', $data);
                        $log->write('reeponse');
                        $log->write($res);
                    }
                }
            }
        }

        if ($this->request->post['user_id'] != NULL && $this->request->post['user_id'] > 0) {
            $user_id = $this->request->post['user_id'];
            $this->load->model('user/user');
            $user_info = $this->model_user_user->getUser($user_id);
            if ($user_info != NULL) {
                // Add to activity log
                $log = new Log('error.log');
                $this->load->model('user/user_activity');

                $activity_data = [
                    'user_id' => $user_info['user_id'],
                    'name' => $user_info['firstname'] . ' ' . $user_info['lastname'],
                    'user_group_id' => $user_info['user_group_id'],
                    'order_id' => $order_id,
                ];
                $log->write('update new shipping address from admin');

                $this->model_user_user_activity->addActivity('order_shipping_address_changed', $activity_data);

                $log->write('update new shipping address from admin');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function updateOnlyFlatNumberShippingAddressFromAdmin() {
        $log = new Log('error.log');
        $log->write('updateOnlyFlatNumberShippingAddressFromAdmin');
        $log->write($this->request->post);
        $json['message'] = "<center style='color:green'>" . $this->language->get('text_edited_success') . '</center>';

        $this->load->model('account/order');

        $this->load->model('api/checkout');

        $order_id = $this->request->post['order_id'];
        $order_info = $this->model_api_checkout->getOrder($order_id);

        if ($order_info) {
            $order_info_old = $this->model_api_checkout->getOrder($order_id);

            $edited = $this->model_account_order->updateOnlyFlatNumber($order_id, $this->request->post, $order_info_old);

            $log->write($edited);

            $order_info = $this->model_api_checkout->getOrder($order_id);

            if ($edited && $order_info) {
                //echo "<pre>";print_r($order_info);die;
                $deliveryAlreadyCreated = $this->model_account_order->getOrderDSDeliveryId($order_id);

                //$deliveryAlreadyCreated = true;

                $log->write($deliveryAlreadyCreated);

                if ($deliveryAlreadyCreated) {
                    $deliverAddress = $order_info['shipping_flat_number'] . ', ' . $order_info['shipping_building_name'] . ', ' . $order_info['shipping_landmark'];

                    $data['body'] = [
                        'manifest_id' => $deliveryAlreadyCreated, //order_id,
                        'dropoff_address' => $deliverAddress,
                        'to_lat' => $order_info['latitude'],
                        'to_lng' => $order_info['longitude'],
                        'dropoff_zipcode' => $order_info['shipping_zipcode'], // from $order_info['city_id'],
                    ];

                    $log->write($data['body']);

                    $data['email'] = $this->config->get('config_delivery_username');
                    $data['password'] = $this->config->get('config_delivery_secret');
                    $response = $this->load->controller('deliversystem/deliversystem/getToken', $data);

                    $log->write('token');
                    $log->write($response);
                    if ($response['status']) {
                        $data['tokens'] = $response['token'];
                        $res = $this->load->controller('deliversystem/deliversystem/updateDeliveryAddress', $data);
                        $log->write('reeponse');
                        $log->write($res);
                    }
                }
            }
        }

        if ($this->request->post['user_id'] != NULL && $this->request->post['user_id'] > 0) {
            $user_id = $this->request->post['user_id'];
            $this->load->model('user/user');
            $user_info = $this->model_user_user->getUser($user_id);
            if ($user_info != NULL) {
                // Add to activity log
                $log = new Log('error.log');
                $this->load->model('user/user_activity');

                $activity_data = [
                    'user_id' => $user_info['user_id'],
                    'name' => $user_info['firstname'] . ' ' . $user_info['lastname'],
                    'user_group_id' => $user_info['user_group_id'],
                    'order_id' => $order_id,
                ];
                $log->write('update only flat number shipping address from admin');

                $this->model_user_user_activity->addActivity('order_flat_number_changed', $activity_data);

                $log->write('update only flat number shipping address from admin');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    
    public function index_new() {

        if ($this->customer->isLogged()) {
            $order_id = $this->request->get['order_id'];
            $this->load->model('sale/order');
            $this->load->model('account/order');
            $this->load->model('account/order');
            $order_info = $this->model_sale_order->getOrder($order_id);
            if (is_array($order_info) && count($order_info) > 0 && $order_info['customer_id'] === $this->customer->getId()) {
                $order_transaction_details_id = $this->model_sale_order->getOrderTransactionDetailsId($order_id);
                $order_products = $this->model_account_order->getOrderProducts($order_id);
                if (is_array($order_products) && count($order_products) > 0 && $order_info['customer_id'] === $this->customer->getId()) {
                    foreach ($order_products as $order_product) {
                        $this->cart->addCustom($order_product['product_id'], $order_product['quantity'], $option = [], $recurring_id = 0, $order_product['store_id'], $store_product_variation_id = false, $product_type = 'replacable', $product_note = null, $produce_type = null);
                    }
                }
                $this->session->data['order_id'] = array($order_info['store_id'] => $order_info['order_id']);
                $this->session->data['transaction_id'] = $order_transaction_details_id['transaction_details_id'];
                $this->response->redirect($this->url->link('common/home'));
            }
        } else {
            $this->response->redirect($this->url->link('error/not_found'));
        }
    }

}
