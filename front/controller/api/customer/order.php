<?php

require_once DIR_SYSTEM . '/vendor/mpesa-php-sdk-master/vendor/autoload.php';

class ControllerApiCustomerOrder extends Controller {

    private $error = [];

    public function addRate($args = []) {
        //echo "<pre>";print_r("Ce");die;
        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $log = new Log('error.log');
        $log->write('addRate');
        $log->write($args);
        $this->load->language('api/general');

        $this->load->model('account/order');

        if (isset($args['order_id']) && isset($args['order_rating']) && isset($args['driver_rating'])) {
            //order rating

            $order_id = $args['order_id'];

            $order_info = $this->model_account_order->getAdminOrder($order_id);

            $rating = $args['order_rating'];

            $this->model_account_order->saveRatingOrder($rating, $order_id);

            // driver rating

            $data['email'] = $this->config->get('config_delivery_username');
            $data['password'] = $this->config->get('config_delivery_secret');

            $data['delivery_id'] = $order_info['delivery_id'];

            $data['rating'] = $args['driver_rating'];
            $data['review'] = isset($args['review']) ? $args['review'] : '';

            //$data['rating'] = 3;

            $response = $this->load->controller('deliversystem/deliversystem/getToken', $data);

            if ($response['status']) {
                $data['token'] = $response['token'];

                $log->write($data);
                $respon = $this->load->controller('deliversystem/deliversystem/postRating', $data);

                $log->write($respon);
            }

            $log->write($json);
        } else {
            $json['status'] = 10025;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_no_products')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getPaymentName($code) {
        $total = 9999999999999999999;
        $method_data = [];

        $this->load->model('extension/extension');

        $results = $this->model_extension_extension->getExtensions('payment');

        //echo "<pre>";print_r($results);die;
        $recurring = $this->cart->hasRecurringProducts();

        foreach ($results as $result) {
            if ($this->config->get($result['code'] . '_status')) {
                $this->load->model('payment/' . $result['code']);

                $method = $this->{'model_payment_' . $result['code']}->getMethod($total);

                if ($method) {
                    if ($recurring) {
                        if (method_exists($this->{'model_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_payment_' . $result['code']}->recurringPayments()) {
                            $method_data[$result['code']] = $method;
                        }
                    } else {
                        $method_data[$result['code']] = $method;
                    }
                }
            }
        }

        if (isset($method_data[$code])) {
            return $method_data[$code]['title'];
        } else {
            return '';
        }

        //echo "<pre>";print_r($method_data);die;
    }

    public function getShippingName($code, $store_id) {
        $mp = explode('.', $code);

        //echo "<pre>";print_r($mp);die;

        if (!isset($mp[0])) {
            return '';
        }

        $code = $mp[0];

        $method_data = [];

        $this->load->model('extension/extension');

        $results = $this->model_extension_extension->getExtensions('shipping');

        $this->load->model('tool/image');

        $store_info = $this->model_tool_image->getStore($store_id);

        $delivery_by_owner = $store_info['delivery_by_owner'];

        $pickup_delivery = $store_info['store_pickup_timeslots'];

        $free_delivery_amount = $store_info['min_order_cod'];

        $store_total = 99999999999999999;
        $subtotal = $store_total;
        if ($store_total > $free_delivery_amount) {
            $cost = 0;
        } else {
            $cost = $store_info['cost_of_delivery'];
        }

        //echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            if ($this->config->get($result['code'] . '_status')) {
                if ('normal' == $result['code']) {
                    $this->load->model('shipping/' . $result['code']);
                    $quote = $this->{'model_shipping_' . $result['code']}->getApiQuote($cost, $store_info['name'], $subtotal, $subtotal);
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
                    $this->load->model('shipping/' . $result['code']);
                    $quote = $this->{'model_shipping_' . $result['code']}->getApiQuote($cost, $store_info['name'], $subtotal, $subtotal);

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
                        $this->load->model('shipping/' . $result['code']);
                        $quote = $this->{'model_shipping_' . $result['code']}->getApiQuote($cost, $store_info['name'], $subtotal, $subtotal);
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
                        $this->load->model('shipping/' . $result['code']);
                        $quote = $this->{'model_shipping_' . $result['code']}->getApiQuote($cost, $store_info['name'], $subtotal, $subtotal);
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
                    $this->load->model('shipping/' . $result['code']);
                    $quote = $this->{'model_shipping_' . $result['code']}->getApiQuote($cost, $store_info['name'], $subtotal, $subtotal);
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

        if (isset($method_data[$code])) {
            return $method_data[$code]['title'];
        } else {
            return '';
        }
    }

    public function getOrders($args = []) {
        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');

        $this->load->language('account/order');

        $this->document->setTitle($this->language->get('heading_title'));

        $url = '';

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['orders'] = [];

        $this->load->model('account/order');
        $this->load->model('account/address');
        $this->load->model('account/customer');

        $order_total = $this->model_account_order->getTotalOrders();

        $results = $this->model_account_order->getOrders(($page - 1) * 10, 10);

        //echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            $city_name = $this->model_account_order->getCityName($result['shipping_city_id']);

            $product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);

            $real_product_total = $this->model_account_order->getTotalRealOrderProductsByOrderId($result['order_id']);

            $order_total_detail = $this->load->controller('checkout/totals/getTotal', $result['order_id']);

            //echo "<pre>";print_r($order_total_detail);die;
            $voucher_total = $this->model_account_order->getTotalOrderVouchersByOrderId($result['order_id']);

            $shipping_address = null;

            if (isset($result['shipping_address'])) {
                $shipping_address['address'] = $result['shipping_building_name'] . ', ' . $result['shipping_flat_number'];
                $shipping_address['city'] = $city_name;
                $shipping_address['zipcode'] = $result['shipping_zipcode'];
            }

            $shipped = false;
            foreach ($this->config->get('config_shipped_status') as $key => $value) {
                if ($value == $result['order_status_id']) {
                    $shipped = true;
                    break;
                }
            }
            if (!$shipped) {
                foreach ($this->config->get('config_complete_status') as $key => $value) {
                    if ($value == $result['order_status_id']) {
                        $shipped = true;
                        break;
                    }
                }
            }

            $realproducts = $this->model_account_order->hasRealOrderProducts($result['order_id']);

            // Totals
            $shipping_charge = '0';

            $totals = $this->model_account_order->getOrderTotals($result['order_id']);

            $latest_total = 0;
            //echo "<pre>";print_r($totals);die;
            foreach ($totals as $total) {
                if ('shipping' == $total['code']) {
                    if ($total['value'] > 0) {
                        $shipping_charge = $this->currency->format($total['value']);
                    }
                }

                if ('sub_total' == $total['code']) {
                    $sub_total = $this->currency->format($total['value']);
                }

                if ('total' == $total['code']) {
                    $latest_total = $total['value'];
                }
            }

            $all_app_order_status = $this->model_account_order->getAppOrderStatuses();

            $app_status_resp = $this->model_account_order->getAppOrderStatusMapping($result['order_status_id']);

            //echo "<pre>";print_r($app_status_resp);die;
            $app_status = '';
            $app_order_status_id = '';
            if ($app_status_resp['status']) {
                $app_status = $app_status_resp['data']['name'];
                $app_order_status_id = $app_status_resp['data']['code'];
            }

            if ($real_product_total) {
                $product_total = $real_product_total;
            }

            $this->load->model('sale/order');
            $approve_order_button = null;
            $order_appoval_access = false;
            if (empty($_SESSION['parent']) && $result['customer_id'] != $this->customer->getId()) {
                $approve_order_button = 'Need Approval';
            }
            if ($this->session->data['order_approval_access'] > 0 && $this->session->data['order_approval_access_role'] != NULL) {
                $order_appoval_access = true;
            }
            $this->load->model('account/customer');
            $customer_info = $this->model_account_customer->getCustomer($result['customer_id']);
            $is_he_parents = $this->model_account_customer->CheckHeIsParent();
            $customer_parent_info = $this->model_account_customer->getCustomerParentDetails($result['customer_id']);

            $sub_user_order = FALSE;
            $procurement_person = NULL;
            $head_chef = NULL;

            if (($customer_info['order_approval_access'] == NULL || $customer_info['order_approval_access'] == 0) && $customer_info['order_approval_access_role'] == NULL && $customer_parent_info != NULL) {
                $log = new Log('error.log');
                $order_approval_access = $this->db->query('SELECT c.customer_id, c.parent, c.order_approval_access_role, c.order_approval_access, c.email, c.firstname, c.lastname  FROM ' . DB_PREFIX . "customer c WHERE c.parent = '" . (int) $customer_parent_info['customer_id'] . "' AND c.order_approval_access = 1 AND (c.order_approval_access_role = 'head_chef' OR c.order_approval_access_role = 'procurement_person')");
                $order_approval_access_user = $order_approval_access->rows;

                foreach ($order_approval_access_user as $order_approval_access_use) {
                    if ($order_approval_access_use['order_approval_access_role'] == 'head_chef' && $order_approval_access_use['order_approval_access'] > 0) {
                        $head_chef = $order_approval_access_use['email'];
                        $log->write($order_approval_access_use['order_approval_access_role']);
                        $log->write($order_approval_access_use['order_approval_access']);
                        $log->write($order_approval_access_use['customer_id']);
                    }
                    if ($order_approval_access_use['order_approval_access_role'] == 'procurement_person' && $order_approval_access_use['order_approval_access'] > 0) {
                        $procurement_person = $order_approval_access_use['email'];
                        $log->write($order_approval_access_use['order_approval_access_role']);
                        $log->write($order_approval_access_use['order_approval_access']);
                        $log->write($order_approval_access_use['customer_id']);
                    }
                }
                $sub_user_order = TRUE;
            }


            $customer_parent_info = $this->model_account_customer->getCustomerParentEmail($result['customer_id']);

            $data['orders'][] = [
                'order_id' => $result['order_id'],
                'name' => $result['firstname'] . ' ' . $result['lastname'],
                'shipping_name' => $result['shipping_name'],
                'payment_method' => $result['payment_method'],
                'shipping_address' => $shipping_address,
                'order_total' => $order_total_detail,
                'store_name' => htmlspecialchars_decode($result['store_name']),
                'status' => $result['status'],
                'order_status_id' => $result['order_status_id'],
                'app_status' => $app_status,
                'app_order_status_id' => $app_order_status_id,
                'all_app_order_status' => $all_app_order_status,
                'shipped' => $shipped,
                'shipping_method' => $result['shipping_method'],
                'shipping_charge' => $shipping_charge,
                'realproducts' => $realproducts,
                'date_added' => date($this->language->get('date_format'), strtotime($result['date_added'])),
                'time_added' => date($this->language->get('time_format'), strtotime($result['date_added'])),
                'eta_date' => date($this->language->get('date_format'), strtotime($result['delivery_date'])),
                'delivered_date' => date($this->language->get('date_format'), strtotime($result['date_modified'])),
                'delivered_time' => date($this->language->get('time_format'), strtotime($result['date_modified'])),
                'eta_time' => $result['delivery_timeslot'],
                'products' => ($product_total + $voucher_total),
                'real_products' => ($real_product_total + $voucher_total),
                'left_symbol_currency' => $this->currency->getSymbolLeft(),
                'right_symbol_currency' => $this->currency->getSymbolRight(),
                'total' => $this->currency->format($latest_total, $result['currency_code'], $result['currency_value']),
                'sub_total' => $sub_total,
                'href' => $this->url->link('account/order/info', 'order_id=' . $result['order_id'], 'SSL'),
                'real_href' => $this->url->link('account/order/realinfo', 'order_id=' . $result['order_id'], 'SSL'),
                'customer_parent_info' => $customer_parent_info,
                'accept_reject_href' => $this->url->link('account/order/accept_reject', 'order_id=' . $result['order_id'], 'SSL'),
                'parent_approve_order' => $approve_order_button,
                'customer_id' => $result['customer_id'],
                'parent_approval' => $result['parent_approval'],
                'order_approval_access' => $order_appoval_access,
                'head_chef' => $result['head_chef'],
                'procurement' => $result['procurement'],
                'sub_user_order' => $sub_user_order,
                'procurement_person_email' => $procurement_person,
                'head_chef_email' => $head_chef,
                'order_approval_access_role' => $this->session->data['order_approval_access_role'],
                'parent_details' => $customer_parent_info != NULL && $customer_parent_info['email'] != NULL ? $customer_parent_info['email'] : NULL,
                'edit_order' => 15 == $result['order_status_id'] && (empty($_SESSION['parent']) || $order_appoval_access) && $result['paid'] == 'N' ? $this->url->link('account/order/edit_order', 'order_id=' . $result['order_id'], 'SSL') : '',
                'order_company' => isset($customer_info) && null != $customer_info['company_name'] ? $customer_info['company_name'] : null,
                'paid' => $result['paid'],
            ];
        }

        $data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($order_total - 10)) ? $order_total : ((($page - 1) * 10) + 10), $order_total, ceil($order_total / 10));

        $json['data'] = $data;

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getOrder() {
        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');

        $this->load->language('account/order');

        //echo "<pre>";print_r("cer");die;

        if (isset($this->request->get['order_id'])) {
            $order_id = $this->request->get['order_id'];

            $this->load->model('account/order');
            $this->load->model('account/customer');

            $order_info = $this->model_account_order->getOrder($order_id);

            $data['cashback_condition'] = $this->language->get('cashback_condition');

            $data['driver_rating'] = null;
            $data['driver_review'] = null;
            // echo "<pre>";print_r($order_info);die;
            if ($order_info) {
                $data['cashbackAmount'] = $this->currency->format(0);

                $coupon_history_data = $this->model_account_order->getCashbackAmount($order_id);

                if (count($coupon_history_data) > 0) {
                    $data['cashbackAmount'] = $this->currency->format((-1 * $coupon_history_data['amount']));
                }

                $url = '';

                if (isset($this->request->get['page'])) {
                    $url .= '&page=' . $this->request->get['page'];
                }

                $data['delivered'] = false;
                $data['coupon_cashback'] = false;

                if ($order_info['invoice_no']) {
                    $data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
                } else {
                    $data['invoice_no'] = '';
                }

                if ($order_info['settlement_amount']) {
                    $data['settlement_amount'] = $this->currency->format($order_info['settlement_amount']);
                } else {
                    $data['settlement_amount'] = null;
                }

                $data['order_id'] = $this->request->get['order_id'];

                //$data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_modified']));
                $data['date_added'] = date($this->language->get('date_format'), strtotime($order_info['order_date'])) . ' ' . date($this->language->get('time_format'), strtotime($order_info['order_date']));
                // 'date_added' => date($this->language->get('date_format'), strtotime($result['date_added'])),
                // 'time_added' => date($this->language->get('time_format'), strtotime($result['date_added'])),

                $data['payment_method'] = ucwords($order_info['payment_method']);
                $data['payment_code'] = $order_info['payment_code'];

                $data['order_rating'] = $order_info['rating'];
                $data['shipping_name'] = $order_info['shipping_name'];
                $data['shipping_contact_no'] = $order_info['shipping_contact_no'];

                $data['shipping_address'] = $order_info['shipping_flat_number'] . ', ' . $order_info['shipping_building_name'];

                $data['shipping_building_name'] = $order_info['shipping_building_name'];
                $data['shipping_flat_number'] = $order_info['shipping_flat_number'];

                $data['shipping_method'] = $order_info['shipping_method'];
                $data['shipping_city'] = $order_info['shipping_city'];

                $data['delivery_timeslot'] = $order_info['delivery_timeslot'];

                $data['order_status_id'] = $order_info['order_status_id'];

                $data['delivery_date'] = $order_info['delivery_date'];

                $data['original_total'] = $this->currency->format($order_info['total']);

                $data['delivery_date_formated'] = date($this->language->get('date_format'), strtotime($order_info['delivery_date']));

                $data['delivered_date'] = date($this->language->get('date_format'), strtotime($order_info['date_modified']));
                $data['delivered_time'] = date($this->language->get('time_format'), strtotime($order_info['date_modified']));

                $data['store_name'] = htmlspecialchars_decode($order_info['store_name']);
                $data['store_address'] = $order_info['store_address'];
                $data['status'] = $order_info['status'];

                $all_app_order_status = $this->model_account_order->getAppOrderStatuses();

                $data['all_app_order_status'] = $all_app_order_status;

                $app_status_resp = $this->model_account_order->getAppOrderStatusMapping($order_info['order_status_id']);

                //  echo "<pre>";print_r($order_info);die;
                $data['app_status'] = '';
                $data['app_order_status_id'] = '';
                if ($app_status_resp['status']) {
                    $data['app_status'] = $app_status_resp['data']['name'];
                    $data['app_order_status_id'] = $app_status_resp['data']['code'];
                }

                $data['can_return'] = false;

                if (isset($order_info['date_modified'])) {
                    $start = date('Y-m-d H:i:s');

                    //echo "<pre>";print_r($order_info['date_modified']);die;
                    //$end = date_create($order_info['date_modified']);
                    $end = $order_info['date_modified'];

                    $timeFirst = strtotime($start);
                    $timeSecond = strtotime($end);

                    //echo "<pre>";print_r($start."Cer");print_r($end);die;
                    $differenceInSeconds = $timeFirst - $timeSecond;

                    //echo "<pre>";print_r($this->config->get('config_return_timeout'));die;
                    if ($differenceInSeconds <= $this->config->get('config_return_timeout')) {
                        $data['can_return'] = true;
                    }
                    //echo "<pre>";print_r($differenceInSeconds);die;
                }

                $this->load->model('assets/product');
                $this->load->model('tool/upload');

                $data['email'] = $this->config->get('config_delivery_username');
                $data['password'] = $this->config->get('config_delivery_secret');

                $data['delivery_id'] = $order_info['delivery_id']; //"del_XPeEGFX3Hc4ZeWg5";//
                //$data['delivery_id'] =  26;
                $data['shopper_link'] = $this->config->get('config_shopper_link') . '/storage/';

                $data['products_status'] = [];
                $data['delivery_data'] = (object) [];

                $log = new Log('error.log');

                if (isset($data['delivery_id'])) {
                    $response = $this->load->controller('deliversystem/deliversystem/getToken', $data);

                    if ($response['status']) {
                        $data['token'] = $response['token'];
                        $productStatus = $this->load->controller('deliversystem/deliversystem/getProductStatus', $data);

                        //echo "<pre>";print_r($productStatus);die;
                        $resp = $this->load->controller('deliversystem/deliversystem/getDeliveryStatus', $data);
                        //echo "<pre>";print_r($resp);die;
                        $data['delivery_id'] = '';
                        if (!$resp['status'] || isset($resp['error'])) {
                            $data['delivery_data'] = (object) [];
                        } else {
                            $data['delivery_data'] = $resp['data'][0];

                            if (isset($data['delivery_data']->reviews) && isset($data['delivery_data']->reviews->ratings)) {
                                $data['driver_review'] = $data['delivery_data']->reviews;
                                $data['driver_rating'] = $data['delivery_data']->reviews->ratings;
                            }
                        }

                        if (!$productStatus['status'] || !(count($productStatus['data']) > 0)) {
                            $data['products_status'] = [];
                        } else {
                            $data['products_status'] = $productStatus['data'];
                        }

                        if (isset($data['delivery_data']->driver)) {
                            if (isset($data['delivery_data']->driver->driver_profile->drivers_photo)) {
                                $data['delivery_data']->driver->driver_profile->drivers_photo = $this->config->get('config_shopper_link') . '/storage/' . $data['delivery_data']->driver->driver_profile->drivers_photo;
                            }

                            if (isset($data['delivery_data']->driver->profile->drivers_photo)) {
                                $data['delivery_data']->driver->profile->drivers_photo = $this->config->get('config_shopper_link') . '/storage/' . $data['delivery_data']->driver->profile->drivers_photo;
                            }
                        }

                        $log->write('order log');
                        $log->write($data['products_status']);

                        //echo "<pre>";print_r($data['products_status']);die;
                    }
                }

                // Products
                $data['products'] = [];

                $realproducts = $this->model_account_order->hasRealOrderProducts($this->request->get['order_id']);

                $data['is_edited'] = $realproducts;

                if ($realproducts) {
                    $products = $this->model_account_order->getRealOrderProducts($this->request->get['order_id']);

                    $real_orders = $this->model_account_order->getOrderProducts($this->request->get['order_id']);
                    $data['edited_order'] = $products;
                } else {
                    $products = $this->model_account_order->getOrderProducts($this->request->get['order_id']);

                    $real_orders = $products;
                    $data['edited_order'] = $this->model_account_order->getRealOrderProducts($this->request->get['order_id']);
                }

                $log->write($products);

                $data['original_total_quantity'] = 0;

                foreach ($real_orders as $edit_product) {
                    $option_data = [];

                    $options = $this->model_account_order->getOrderOptions($this->request->get['order_id'], $edit_product['order_product_id']);

                    foreach ($options as $option) {
                        if ('file' != $option['type']) {
                            $value = $option['value'];
                        } else {
                            $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

                            if ($upload_info) {
                                $value = $upload_info['name'];
                            } else {
                                $value = '';
                            }
                        }

                        $option_data[] = [
                            'name' => $option['name'],
                            'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value),
                        ];
                    }

                    $product_info = $this->model_assets_product->getDetailproduct($edit_product['product_id']);

                    if ($product_info) {
                        $reorder = $this->url->link('account/order/reorder', 'order_id=' . $order_id . '&order_product_id=' . $edit_product['order_product_id'], 'SSL');
                    } else {
                        $reorder = '';
                    }

                    $this->load->model('tool/image');

                    if (file_exists(DIR_IMAGE . $edit_product['image'])) {
                        $image = $this->model_tool_image->resize($edit_product['image'], $this->config->get('config_app_image_product_width'), $this->config->get('config_app_image_product_height'));
                    } else {
                        $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_app_image_product_width'), $this->config->get('config_app_image_product_height'));
                    }

                    $return_status = '';

                    if (isset($edit_product['return_id']) && !is_null($edit_product['return_id'])) {
                        $this->load->model('account/return');

                        //$returnDetails = $this->model_account_return->getReturnHistories($edit_product['return_id']);
                        $returnDetails = $this->model_account_return->getReturn($edit_product['return_id']);

                        if (count($returnDetails) > 0) {
                            $return_status = $returnDetails['status'];
                        }
                    }

                    $data['real_order'][] = [
                        'store_id' => $edit_product['store_id'],
                        'vendor_id' => $edit_product['vendor_id'],
                        'name' => htmlspecialchars_decode($edit_product['name']),
                        'product_id' => $edit_product['product_id'],
                        'weight' => $edit_product['weight'],
                        'unit' => $edit_product['unit'],
                        'model' => $edit_product['model'],
                        'product_type' => trim($edit_product['product_type']),
                        'product_note' => trim($edit_product['product_note']),
                        'produce_type' => trim($edit_product['produce_type']),
                        'image' => $image,
                        'option' => $option_data,
                        'return_id' => $edit_product['return_id'],
                        'return_status' => $return_status,
                        'quantity' => $edit_product['quantity'],
                        'price' => $this->currency->format($edit_product['price'] + ($this->config->get('config_tax') ? $edit_product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                        'total' => $this->currency->format($edit_product['total'] + ($this->config->get('config_tax') ? ($edit_product['tax'] * $edit_product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
                        'reorder' => $reorder,
                        'return' => $this->url->link('account/return/add', 'order_id=' . $order_info['order_id'] . '&product_id=' . $edit_product['product_id'], 'SSL'),
                    ];

                    $data['original_total_quantity'] += $edit_product['quantity'];
                }

                //echo "<pre>";print_r($products);die;
                foreach ($products as $product) {
                    $option_data = [];

                    $options = $this->model_account_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);

                    foreach ($options as $option) {
                        if ('file' != $option['type']) {
                            $value = $option['value'];
                        } else {
                            $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

                            if ($upload_info) {
                                $value = $upload_info['name'];
                            } else {
                                $value = '';
                            }
                        }

                        $option_data[] = [
                            'name' => $option['name'],
                            'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value),
                        ];
                    }

                    $product_info = $this->model_assets_product->getDetailproduct($product['product_id']);

                    if ($product_info) {
                        $reorder = $this->url->link('account/order/reorder', 'order_id=' . $order_id . '&order_product_id=' . $product['order_product_id'], 'SSL');
                    } else {
                        $reorder = '';
                    }

                    $this->load->model('tool/image');

                    if (file_exists(DIR_IMAGE . $product['image'])) {
                        $image = $this->model_tool_image->resize($product['image'], $this->config->get('config_app_image_product_width'), $this->config->get('config_app_image_product_height'));
                    } else {
                        $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_app_image_product_width'), $this->config->get('config_app_image_product_height'));
                    }

                    $return_status = '';

                    if (isset($product['return_id']) && !is_null($product['return_id'])) {
                        $this->load->model('account/return');

                        //$returnDetails = $this->model_account_return->getReturnHistories($product['return_id']);
                        $returnDetails = $this->model_account_return->getReturn($product['return_id']);

                        if (count($returnDetails) > 0) {
                            $return_status = $returnDetails['status'];
                        }
                    }

                    $data['products'][] = [
                        'store_id' => $product['store_id'],
                        'vendor_id' => $product['vendor_id'],
                        'name' => htmlspecialchars_decode($product['name']),
                        'product_id' => $product['product_id'],
                        'weight' => $product['weight'],
                        'unit' => $product['unit'],
                        'model' => $product['model'],
                        'product_type' => trim($product['product_type']),
                        'product_note' => trim($product['product_note']),
                        'produce_type' => trim($product['produce_type']),
                        'image' => $image,
                        'option' => $option_data,
                        'return_id' => $product['return_id'],
                        'return_status' => $return_status,
                        'quantity' => $product['quantity'],
                        'price' => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                        'total' => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
                        'reorder' => $reorder,
                        'return' => $this->url->link('account/return/add', 'order_id=' . $order_info['order_id'] . '&product_id=' . $product['product_id'], 'SSL'),
                    ];
                }

                if ($data['is_edited']) {
                    $data['edited_order'] = $data['products'];
                }

                $log->write($data['products']);
                // Voucher
                $data['vouchers'] = [];

                $vouchers = $this->model_account_order->getOrderVouchers($this->request->get['order_id']);

                foreach ($vouchers as $voucher) {
                    $data['vouchers'][] = [
                        'description' => $voucher['description'],
                        'amount' => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
                    ];
                }

                // Totals
                $data['totals'] = [];

                $totals = $this->model_account_order->getOrderTotals($this->request->get['order_id']);

                $data['newTotal'] = $this->currency->format(0);

                $data['total'] = 0;

                //echo "<pre>";print_r($totals);die;
                foreach ($totals as $total) {
                    if ('sub_total' == $total['code']) {
                        $data['subtotal'] = $total['value'];
                    }

                    if ('total' == $total['code']) {
                        $temptotal = $total['value'];
                        $data['total'] = $total['value'];
                    }

                    $val = ['title' => $total['title'], 'text' => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value'])];

                    $data['totals'][] = [
                        $total['code'] => $val,];

                    $data['plain_settlement_amount'] = $order_info['settlement_amount'];
                    if (isset($data['settlement_amount']) && isset($data['subtotal']) && isset($temptotal)) {
                        $data['newTotal'] = $this->currency->format($temptotal - $data['subtotal'] + $order_info['settlement_amount']);
                    }
                }

                // History
                $data['histories'] = [];

                $results = $this->model_account_order->getOrderHistories($this->request->get['order_id']);

                foreach ($results as $result) {
                    $data['histories'][] = [
                        'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                        'status' => $result['status'],
                        'comment' => $result['notify'] ? nl2br($result['comment']) : '',
                    ];
                }

                $data['total'] = $this->currency->format($data['total']);

                $payment_text = '';

                foreach ($this->config->get('config_complete_status') as $key => $value) {
                    if ($value == $order_info['order_status_id']) {
                        $data['delivered'] = true;
                        $data['coupon_cashback'] = true;

                        $payment_text = sprintf($this->language->get('text_cod_delivered'), $data['total']);

                        break;
                    }
                }

                if (!$data['delivered']) {
                    $payment_text = sprintf($this->language->get('text_cod_not_delivered'), $data['total']);
                }

                if ('cod' != $order_info['payment_code'] && 'mod' != $order_info['payment_code']) {
                    $payment_text = sprintf($this->language->get('text_online_paid'), $data['total']);
                }

                $data['payment_text'] = $payment_text;
                $data['paid'] = $order_info['paid'];

                $data['comment'] = nl2br($order_info['comment']);

                $data['total_quantity'] = 0;
                $data['customer_parent_info'] = $this->model_account_customer->getCustomerParentEmail($order_info['customer_id']);
                $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);
                $hours = 0;
                $t1 = strtotime(date('Y-m-d H:i:s'));
                // $t2 = strtotime($order_info['date_added']);
                $t2 = strtotime($order_info['order_date']);
                $diff = $t1 - $t2;
                $hours = $diff / ( 60 * 60 );
                $log->write('hours');
                $log->write(date('Y-m-d H:i:s'));
                $log->write($order_info['date_added']);
                $log->write($order_info['payment_code']);
                $log->write(date_default_timezone_get());
                $log->write($hours);
                $log->write('hours');
                // $data['edit_order'] = ((15 == $order_info['order_status_id'] ) && (empty($_SESSION['parent']) || $order_appoval_access) )? true : false;
                $data['order_company'] = isset($customer_info) && null != $customer_info['company_name'] ? $customer_info['company_name'] : null;

                $data['edit_own_order'] = (($order_info['order_status_id'] == 15 || $order_info['order_status_id'] == 14) && $hours < 2 && $order_info['paid'] == 'N' && ($order_info['payment_code'] == 'cod' || $order_info['payment_code'] == 'mod')) ? true : false;

                $data['driver'] = $this->model_account_order->getDriverName($order_info['driver_id']);
                $data['executive'] = $this->model_account_order->getExecutiveName($order_info['delivery_executive_id']);

                $data['vehicle'] = $order_info['vehicle_number'];

                foreach ($data['products'] as $product) {
                    $data['total_quantity'] += $product['quantity'];
                }

                $json['data'] = $data;
            } else {
                //order info not found

                $json['status'] = 10023;

                $json['message'][] = ['type' => '', 'body' => $this->language->get('text_order_not_found')];
            }
        } else {
            $json['status'] = 10013;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_not_loggedin')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addOrderStatusByReferenceId() {
        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');

        $this->load->language('account/order');

        //echo "<pre>";print_r("cer");die;

        if (isset($this->request->post['order_reference_number'])) {
            $order_reference_number = $this->request->post['order_reference_number'];

            $this->load->model('account/order');

            $data['status'] = $this->model_account_order->getOrderByReferenceId($order_reference_number);

            $json['data'] = $data;
        } else {
            $json['status'] = 10013;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_not_loggedin')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addOrdercancel() {
        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');

        $this->load->language('account/order');

        //echo "<pre>";print_r("cer");die;

        if (isset($this->request->post['order_id']) && isset($this->request->post['comment'])) {
            $data['status'] = false;

            $log = new Log('error.log');

            $this->load->model('sale/order');
            $this->load->model('checkout/order');
            $this->load->model('api/checkout');

            $order_id = $this->request->post['order_id'];

            //update order status as cancelled
            $order_info = $this->model_api_checkout->getOrder($order_id);

            if ($order_info) {
                $log->write($order_id);

                $notify = true;
                //$comment = 'Order ID #'.$order_id.' Cancelled';
                $comment = $this->request->post['comment'];

                $this->load->model('localisation/order_status');

                $order_status = $this->model_localisation_order_status->getOrderStatuses();

                $order_status_id = false;
                foreach ($order_status as $order_state) {
                    if ('cancelled' == strtolower($order_state['name']) || 'cancelada' == strtolower($order_state['name'])) {
                        $order_status_id = $order_state['order_status_id'];
                        break;
                    }
                }

                $log->write($order_status_id);
                if ($order_info && $order_status_id) {
                    $log->write('if order his');
                    $this->model_checkout_order->addOrderHistory($order_id, $order_status_id, $comment, $notify);

                    $json['message'][] = ['type' => '', 'body' => $this->language->get('text_cancel_success')];
                } else {
                    $json['status'] = 10034;
                    $json['message'][] = ['type' => '', 'body' => $this->language->get('text_cancel_failed')];
                }
            } else {
                //order info not found

                $json['status'] = 10023;

                $json['message'][] = ['type' => '', 'body' => $this->language->get('text_order_not_found')];
            }
        } else {
            $json['status'] = 10013;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_not_loggedin')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getSettings($code, $store_id = 0) {
        $this->load->model('setting/setting');

        return $this->model_setting_setting->getSetting($code, $store_id);
    }

    public function addValidateOrderDetail($args = []) {
        //echo "<pre>";print_r("Ce");die;
        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];
        $json['valid'] = false;

        $log = new Log('error.log');
        $log->write('addValidateOrderDetails');
        $log->write($args);

        //echo "<pre>";print_r($args['products']);die;
        $this->load->language('api/general');

        $data['cart_products_with_stock_status'] = [];

        $valid = false;

        if (isset($args['stores'])) {
            $stores = array_keys($args['stores']);

            $count = count($stores);
            //print_r($stores);

            $log->write('count' . $count);
            $i = 1;
            foreach ($stores as $store_id) {
                if (isset($args['stores'][$store_id]['shipping_code'])) {
                    $args['stores'][$store_id]['shipping_method'] = $args['stores'][$store_id]['shipping_code'];
                }

                if ('express.express' == $args['stores'][$store_id]['shipping_method']) {
                    $this->load->model('tool/image');

                    $settings = $this->getSettings('express', 0);

                    if ($store_id) {
                        $timeDiff = $settings['express_how_much_time'];
                        //KWIKBASKET STORE TIME SLOTS USING
                        $store_open_hours = $this->model_tool_image->getStoreOpenHours(75, date('w'));

                        if ($store_open_hours && isset($store_open_hours['timeslot'])) {
                            $temp = explode('-', $store_open_hours['timeslot']);

                            $is_enabled = $this->timeIsBetween($temp[0], $temp[1], date('h:ia'), $timeDiff);

                            if ($is_enabled) {
                                $valid = true;
                            }
                        }
                    }
                } else {
                    if (isset($args['stores'][$store_id]['timeslot_selected']) && isset($args['stores'][$store_id]['shipping_method']) && isset($args['stores'][$store_id]['store_id']) && isset($args['stores'][$store_id]['delivery_date'])) {
                        //KWIKBASKET STORE TIME SLOTS USING
                        $kwikb = $args['stores'][$store_id];
                        $kwikb['store_id'] = 75;
                        $log->write($kwikb);
                        $response = $this->load->controller('api/customer/checkout/getApiDeliveryTimeslot', $kwikb);

                        $log->write('getApiDeliveryTimeslot');
                        //$log->write($response);

                        $d = (string) $args['stores'][$store_id]['delivery_date'];
                        $delivery_date = rtrim($d, ',');

                        $log->write($delivery_date);

                        if (isset($response['data']['timeslots']) && isset($response['data']['timeslots'][$delivery_date])) {
                            foreach ($response['data']['timeslots'][$delivery_date] as $key => $value) {
                                if ($args['stores'][$store_id]['timeslot_selected'] == $value['timeslot']) {
                                    $valid = true;
                                }
                            }
                        }
                    }
                }

                if ($valid && $count != $i) {
                    $valid = false;
                } else {
                    break;
                }

                ++$i;
            }

            $json['valid'] = $valid;
        } else {
            $json['status'] = 10025;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_no_products')];

            http_response_code(400);
        }

        $log->write($json);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    private function timeIsBetween($from, $to, $time, $time_diff = false) {
        //echo "time";print_r($from.$to.$time.$time_diff);die;
        $log = new Log('error.log');
        $log->write('time diff');
        $log->write($from . $to . $time . $time_diff);

        /* to calc */
        $to = trim($to);
        //calculate from_time in minuts
        $i = explode(':', $to);
        if (12 == $i[0]) {
            $to_min = substr($i[1], 0, 2);
        } else {
            $to_min = ($i[0] * 60) + substr($i[1], 0, 2);
        }
        //if pm add 12 hours
        $am_pm = substr($to, -2);
        if ('pm' == $am_pm) {
            $to_min += 12 * 60;
        }

        //calculate time in minuts
        $i = explode(':', $time);
        if (12 == $i[0]) {
            $min = substr($i[1], 0, 2);
        } else {
            $min = $i[0] * 60 + substr($i[1], 0, 2);
        }

        //if pm add 12 hours
        $am_pm = substr($time, -2);
        if ('pm' == $am_pm) {
            $min += 12 * 60;
        }

        //if time difference
        if ($time_diff) {
            $i = explode(':', $time_diff);
            $min = $min + $i[0] * 60 + $i[1]; //add difference minut to current time
        }

        /* from calc */

        $from = trim($from);
        //calculate from_time in minuts
        $i = explode(':', $from);
        if (12 == $i[0]) {
            $from_min = substr($i[1], 0, 2);
        } else {
            $from_min = ($i[0] * 60) + substr($i[1], 0, 2);
        }
        //if pm add 12 hours
        $am_pm = substr($from, -2);
        if ('pm' == $am_pm) {
            $from_min += 12 * 60;
        }

        /* from calc end */

        //echo "<pre>";print_r($min."cer".$to_min."from_min".$from_min);die;

        $log->write($min);
        $log->write($to_min);
        if ($from_min <= $min && $min <= $to_min) {
            return true;
        } else {
            return 0;
        }
    }

    protected function validate($args) {
        if (empty($args['payment_method'])) {
            $this->error['payment_method'] = $this->language->get('error_payment_method');
        }

        if (empty($args['payment_method_code'])) {
            $this->error['payment_method_code'] = $this->language->get('error_payment_method_code');
        }

        if (empty($args['shipping_address_id'])) {
            $this->error['shipping_address_id'] = $this->language->get('error_shipping_address_id');
        }

        // if (empty($args['shipping_city_id'])) {
        //     $this->error['error_shipping_city_id'] = $this->language->get('error_shipping_city_id');
        // }

        if (empty($args['stores'])) {
            $this->error['error_stores'] = $this->language->get('error_stores');
        }

        if (empty($args['products'])) {
            $this->error['error_products'] = $this->language->get('error_products');
        }

        /* $vendor_terms = json_decode($this->getCheckOtherVendorOrderExist(), true);
          if ($vendor_terms['modal_open'] == TRUE) {
          $this->error['vendor_terms'] = 'Please accept vendor terms!';
          } */

        if ((!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $this->response->redirect($this->url->link('checkout/cart'));
        }

        //echo "<pre>";print_r($this->error);die;
        return !$this->error;
    }

    public function addMaxOfProduct($args = []) {
        //echo "<pre>";print_r("Ce");die;
        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];
        $json['valid_cart'] = false;

        // $json['tax'] = 0;

        $log = new Log('error.log');
        $log->write($args['products']);
        //echo "<pre>";print_r($args['products']);die;
        $this->load->language('api/general');

        $data['cart_products_with_stock_status'] = [];

        $valid_cart = true;
        $store_id = false;

        if (isset($args['products']) && count($args['products']) > 0) {


            if ($this->request->get['parent'] != NULL && $this->request->get['parent'] > 0) {
                $customer_details = $this->db->query('SELECT customer_category FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . $this->request->get['parent'] . "' AND status = '1'");
            } else {
                $customer_details = $this->db->query('SELECT customer_category FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . $this->customer->getId() . "' AND status = '1'");
            }
            $customer_category = $this->session->data['customer_category'] = isset($customer_details->row['customer_category']) ? $customer_details->row['customer_category'] : null;
            // echo "<pre>";print_r($this->session->data['customer_category']);die;
            // echo "<pre>";print_r($customer_details->row['customer_category']);die;


            $this->load->model('assets/product');
            //the below data is directly from model, not cache data
            $cachePrice_data = $this->model_assets_product->getCategoryPriceStatusByCategoryNameNew($customer_category, 1, ACTIVE_STORE_ID);
            //for testing purpose
            $json['customer_id'] = $this->customer->getId();
            $json['parent_id'] = $this->request->get['parent'];
            $json['customer_category'] = $customer_category;
            //end
            //   echo "<pre>";print_r($cachePrice_data);die;
            foreach ($args['products'] as $product) {
                $store_id = $product['store_id'];
                // // // $order_products = $this->model_account_order->getOrderProducts($order_id);
                $stock = true;

                $this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
                $this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');
                $this->db->where('product.status', 1);
                $this->db->where('product_description.language_id', (int) $this->config->get('config_language_id'));
                $this->db->where('product_to_store.product_store_id', $product['product_store_id']);
                // echo $_SESSION['customer_category'] ;exit;
                $product_query = $this->db->get('product_to_store');

                //    echo "<pre>";print_r($product_query);die;

                $log->write($product_query->row);

                $quantity_available = 0;

                if ($product_query->num_rows) {
                    // new code

                    $s_price = 0;
                    $o_price = 0;
                    $product['tax_class_id'] = $product_query->row['tax_class_id'];
                    if (!$this->config->get('config_inclusiv_tax')) {

                        // echo 1212;exit;
                        //get price html
                        if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                            $product['price'] = $this->currency->format($this->tax->calculate($product_query->row['price'], $product_query->row['tax_class_id'], $this->config->get('config_tax')));

                            $o_price = $this->tax->calculate($product['price'], $product_query->row['tax_class_id'], $this->config->get('config_tax'));
                        } else {
                            $product['price'] = false;
                        }

                        if ((float) $product_query->row['special_price']) {
                            $product['special_price'] = $this->currency->format($this->tax->calculate($product_query->row['special_price'], $product_query->row['tax_class_id'], $this->config->get('config_tax')));

                            $s_price = $this->tax->calculate($product['special_price'], $product_query->row['tax_class_id'], $this->config->get('config_tax'));
                        } else {
                            $product['special_price'] = false;
                        }
                    } else {
                        $s_price = $product_query->row['special_price'];
                        $o_price = $product_query->row['price'];

                        if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                            //$product_query->row['price'] = $product_query->row['price'];formatWithoutCurrency
                            $product['price'] = $this->currency->format($product_query->row['price']);
                        } else {
                            $product['price'] = $product_query->row['price'];
                        }

                        if ((float) $product_query->row['special_price']) {
                            //$product_query->row['special_price'] = $product_query->row['special_price'];
                            $product['special_price'] = $this->currency->format($product_query->row['special_price']);
                        } else {
                            $product['special_price'] = $product_query->row['special_price'];
                        }
                    }
                    //for testing purpose, calling cache data,but sometimes, the cache is not loading for mobile users
                    $cachePrice_dataNew = $this->cache->get('category_price_data');

                    //log customer category
                    // echo $_SESSION['customer_category'] ;exit;
                    //   echo $cachePrice_data ;exit;
                    if (!isset($cachePrice_data))
                        $log->write("cachePrice_data not coming  in max Quantity API");


                    $log->write($_SESSION['customer_category']);
                    if (!isset($_SESSION['customer_category']))
                        $log->write('customer category not loaded in session in max quantity for user ');
                    $log->write($this->customer->getId());

                    $log->write($product['store_id']);
                    $log->write($cachePrice_data);
                    //Test Data
                    $product['testprice'] = $product['product_store_id'] . '_' . $customer_category . '_' . $product['store_id'];
                    $product['testprice2'] = $cachePrice_data[$product['product_store_id'] . '_' . $customer_category . '_' . $product['store_id']];
                    $product['testprice3'] = $cachePrice_dataNew[$product['product_store_id'] . '_' . $customer_category . '_' . $product['store_id']];
                    //end

                    if (CATEGORY_PRICE_ENABLED == true && isset($cachePrice_data) && isset($cachePrice_data[$product['product_store_id'] . '_' . $customer_category . '_' . $product['store_id']])) {
                        $json['CATEGORY_PRICE_ENABLED'] = true;
                        //  echo 'divya';exit;
                        $s_price = $cachePrice_data[$product['product_store_id'] . '_' . $customer_category . '_' . $product['store_id']];
                        $o_price = $cachePrice_data[$product['product_store_id'] . '_' . $customer_category . '_' . $product['store_id']];
                        $product['special_price'] = $this->currency->format($s_price);
                        $product['price'] = $this->currency->format($o_price);
                    } else {
                        $json['CATEGORY_PRICE_ENABLED'] = false;
                    }


                    $percent_off = null;
                    if (isset($s_price) && isset($o_price) && 0 != $o_price && 0 != $s_price) {
                        $percent_off = (($o_price - $s_price) / $o_price) * 100;
                    }

                    // if (is_null($product_query->row['special_price']) || !($product_query->row['special_price'] + 0)) {
                    //     $product['special_price'] = $product_query->row['price'];
                    // }

                    $product['percent_off'] = number_format($percent_off, 0);
                    $log->write('product info');
                    $log->write($product);
                    $log->write('product info');
                    $special_price = explode(' ', $product['special_price']);
                    $log->write($special_price);
                    $log->write("special_price");
                    // $special_price[1] = str_replace(',', '', $special_price[1]);
                    // $total_without_tax = $special_price[1] *  $product['quantity'];
                    $total_without_tax = $special_price * $product['quantity'];
                    //  echo $this->config->get('config_tax');
                    //  echo $special_price[1];
                    //  echo $this->config->get('config_tax');
                    //  echo $this->config->get('config_tax');
                    //  echo $this->config->get('config_tax');
                    //  echo $this->config->get('config_tax');exit; 
                    //   $total_with_tax = $this->config->get('config_tax') ? ($this->tax->calculate($special_price[1], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']) : 0;
                    $total_with_tax = $this->config->get('config_tax') ? ($this->tax->calculate($special_price, $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']) : 0;
                    $tax = 0;
                    $single_product_tax = 0;
                    if ($total_with_tax > 0 && $this->config->get('config_tax') == true) {
                        $tax = $total_with_tax - $total_without_tax;
                        $log->write('TAX');
                        $log->write($total_with_tax);
                        // $log->write('sri divya');
                        $log->write($total_without_tax);
                        $log->write($tax);
                        $log->write('TAX');
                        $single_product_tax = $tax / $product['quantity'];
                        $log->write('single_product_tax');
                        $log->write($single_product_tax);
                        $log->write('single_product_tax');
                    }

                    //  $total = $special_price[1] * $product['quantity'] + ($this->config->get('config_tax') ? ($order_products[$key]['tax'] * $product['quantity']) : 0);
                    $total = $special_price * $product['quantity'] + ($this->config->get('config_tax') ? ($order_products[$key]['tax'] * $product['quantity']) : 0);

                    $log->write('TOTAL');
                    $log->write($total);
                    $log->write('TOTAL');
                    $log->write($special_price[1]);
                    $log->write($this->tax->calculate($special_price[1], $product['tax_class_id'], $this->config->get('config_tax')));
                    // $log->write($product_id);
                    // $log->write($product_id);
                    $product['tax'] = $tax;
                    $product['total'] = $total;
                    $product['total_without_tax'] = $total_without_tax;
                    $product['total_with_tax'] = $total_with_tax;
                    $product['single_product_tax'] = $single_product_tax;

                    // new code end

                    $quantity_available = $product_query->row['quantity'];

                    $max_qty = $product_query->row['min_quantity'] > 0 ? $product_query->row['min_quantity'] : $product_query->row['quantity'];
                    // Stock
                    if ((int) $quantity_available < (int) $product['quantity']) {
                        $log->write('if');

                        $stock = false;
                        $valid_cart = false;
                    }
                } else {
                    $stock = false;
                    $valid_cart = false;
                }

                $product['has_stock'] = $stock;
                $product['max_qty'] = $max_qty;
                $product['number_of_stock_available'] = $quantity_available;

                $data['cart_products_with_stock_status'][] = $product;
            }
            $json['cachePricedata'] = $cachePrice_data;
            $json['valid_cart'] = $valid_cart;
            $json['data'] = $data['cart_products_with_stock_status'];

            $log->write($json);
        } else {
            $json['status'] = 10025;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_no_products')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addValidateOrder($args = []) {
        //echo "<pre>";print_r("Ce");die;
        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];
        $json['valid_cart'] = false;

        $json['error_message'] = 'Minimum order amount not reached';

        $json['tax'] = 0;

        $log = new Log('error.log');
        $log->write($args);
        //echo "<pre>";print_r($args['products']);die;
        $this->load->language('api/general');

        $data['cart_products_with_stock_status'] = [];

        $valid_cart = true;
        $store_id = false;

        if (isset($args['products']) && count($args['products']) > 0) {
            foreach ($args['products'] as $product) {
                $store_id = trim($product['store_id']);

                /* if(isset($args['stores'])) {


                  foreach ( $args['stores'] as $store ) {

                  $this->load->model( 'account/address' );

                  $log->write($store);

                  $store_info = $this->model_account_address->getStoreData($store['store_id']);

                  $log->write($store_info);

                  if ($store_info['min_order_amount'] > $store['store_total'] ) {

                  $store['min_order_amount'] = false;
                  $valid_cart = false;
                  }
                  }


                  $json['data']['stores'] = $args['stores'];
                  } */

                $product['valid_cart_min'] = true;
                $this->load->model('account/address');

                $store_info = $this->model_account_address->getStoreData($store_id);

                if ($this->config->get('config_active_store_minimum_order_amount') > $this->cart->getSubTotal()) {
                    $currentprice = $this->config->get('config_active_store_minimum_order_amount') - $this->cart->getSubTotal();
                    $json['error_message'] = $this->currency->format($currentprice) . ' away from minimum order value.';
                    $product['valid_cart_min'] = false;
                    $valid_cart = false;

                    $json['status'] = 10100;
                }
                /* PREVIOUS CODE FOR VALIDATE MINIMUM ORDER AMOUNT */
                /* if (isset($args['stores']) && isset($args['stores'][$store_id]) && false) {
                  $this->load->model('account/address');

                  $store_info = $this->model_account_address->getStoreData($store_id);

                  //echo "<pre>";print_r($store_info);die;
                  if ($store_info['min_order_amount'] > (int) $args['stores'][$store_id]['store_total']) {
                  $product['valid_cart_min'] = false;
                  $valid_cart = false;

                  $json['status'] = 10100;
                  }
                  } */

                $stock = true;

                $this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
                $this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');
                $this->db->where('product.status', 1);
                $this->db->where('product_description.language_id', (int) $this->config->get('config_language_id'));
                $this->db->where('product_to_store.product_store_id', $product['product_store_id']);

                $product_query = $this->db->get('product_to_store');

                //echo "<pre>";print_r($product_query);die;

                $log->write($product_query->row);

                $quantity_available = 0;

                if ($product_query->num_rows) {
                    $quantity_available = $product_query->row['quantity'];

                    $log->write('quantity_available' . $quantity_available . 'p_quantity_available' . $product['quantity']);
                    // Stock
                    // if ((int) $quantity_available < (int) $product['quantity']) {
                    //     $log->write('if');
                    //     $stock = false;
                    //     $valid_cart = false;
                    // }
                } else {
                    $stock = false;
                    $valid_cart = false;
                }

                $product['has_stock'] = $stock;
                $product['number_of_stock_available'] = $quantity_available;

                $data['cart_products_with_stock_status'][] = $product;
            }

            $json['valid_cart'] = $valid_cart;
            $json['data'] = $data['cart_products_with_stock_status'];

            /* calculate tax start */

            $taxes = $this->cart->getTaxesByApi($args);

            /* if($store_id) {
              $this->session->data['config_store_id'] = $store_id;
              } */

            $json['tax'] = $taxes;

            /* end */

            $log->write($json);
        } else {
            $json['status'] = 10025;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_no_products')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function createDeliveryRequest($order_id) {
        $log = new Log('error.log');
        $log->write('createDeliveryRequest');

        $status = false;
        $this->load->model('checkout/order');

        $visitor_id = null;

        $order_info = $this->model_checkout_order->getOrder($order_id);

        $deliverSystemStatus = $this->config->get('config_deliver_system_status');

        $checkoutDeliverSystemStatus = $this->config->get('config_checkout_deliver_system_status');

        $deliverSystemStatusForShipping = false;

        //$deliverSystemStatusForShipping = true;

        if ($deliverSystemStatus && $checkoutDeliverSystemStatus) {
            $log->write('createDeliveryRequest if');

            $allowedShippingMethods = $this->config->get('config_delivery_shipping_methods_status');

            $log->write($allowedShippingMethods);

            $log->write($order_info['shipping_code']);

            //echo "<pre>";print_r($allowedShippingMethods);die;
            if (is_array($allowedShippingMethods) && count($allowedShippingMethods) > 0) {
                foreach ($allowedShippingMethods as $method) {
                    $cmp = $method . '.' . $method;

                    $cmp = (string) $cmp;

                    $x = (string) $order_info['shipping_code'];

                    $p = explode('.', $order_info['shipping_code']);

                    //if($x == $cmp) {
                    if ($p[0] == $method) {
                        $log->write('allowed');

                        $deliverSystemStatus = true;
                        $deliverSystemStatusForShipping = true;
                    }
                }
            }
        } else {
            $deliverSystemStatus = false;
        }

        $log->write('deliverSystemStatus' . $deliverSystemStatus . 'deliverSystemStatusForShipping' . $deliverSystemStatusForShipping);

        if ($deliverSystemStatus && $deliverSystemStatusForShipping) {
            $log->write('deliverSystemStatus if');
            //$this->model_checkout_success->createDeliveryRequest($order_id);

            $this->load->controller('checkout/success' . '/createDeliveryRequest', $order_id);
        } else {
            $log->write('deliverSystemStatus else');
        }
    }

    public function refundCancelOrderByOrderId($order_id) {
        require_once DIR_SYSTEM . 'library/Iugu.php';

        $data['status'] = false;

        $log = new Log('error.log');

        if ($order_id) {
            $data['settlement_tab'] = false;

            $this->load->model('sale/order');
            $this->load->model('checkout/order');
            /* $iuguData =  $this->model_sale_order->getOrderIugu($order_id);

              $log->write('refundCancelOrder');
              $log->write($iuguData);
              if($iuguData) {

              $invoiceId = $iuguData['invoice_id'];

              Iugu::setApiKey($this->config->get('iugu_token'));


              $invoice = Iugu_Invoice::fetch($invoiceId);
              $resp = $invoice->refund();

              $log->write('refundAPI');
              $log->write($resp);

              if($resp) {

              } else {
              $data['status'] = false;
              }
              } */

            //update order status as cancelled
            $order_info = $this->model_checkout_order->getOrder($order_id);

            $log->write($order_id);

            $notify = true;
            $comment = 'Order ID #' . $order_id . ' Cancelled';

            $this->load->model('localisation/order_status');

            $order_status = $this->model_localisation_order_status->getOrderStatuses();

            $order_status_id = false;
            foreach ($order_status as $order_state) {
                if ('cancelled' == strtolower($order_state['name']) || 'cancelada' == strtolower($order_state['name'])) {
                    $order_status_id = $order_state['order_status_id'];
                    break;
                }
            }

            $log->write($order_status_id);
            if ($order_info && $order_status_id) {
                $log->write('if order his');

                $this->load->model('account/customer');
                $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

                $this->model_checkout_order->addOrderHistory($order_id, $order_status_id, $comment, $notify, $customer_info['customer_id'], 'customer');

                $data['status'] = true;
            } else {
                $data['status'] = false;
            }
        }

        /* $this->response->addHeader('Content-Type: application/json');
          $this->response->setOutput(json_encode($data)); */
    }

    //public function edit_full_order() //addMaxOfProduct
    public function addEditOrderWithNewitemAndQuantity($args = []) {
        $log = new Log('error.log');
        $json = [];
        $json['location'] = 'module';
        $json['data'] = [];
        $json['success'] = "";
        $json['message'] = [];
        if (!$this->customer->isLogged()) {
            $json['message'] = "User";
        }

        try {

            $order_id = $this->request->post['order_id'];
            // $log->write($order_id);in mobile app, multiple products will come in same APi
            // $product_id = $this->request->post['product_id'];
            // $quantity = $this->request->post['quantity'];
            // $unit = $this->request->post['unit'];
            $log = new Log('error.log');
            $log->write($order_id);
            $log->write('Order Edit new functionality');
            $this->load->model('account/order');
            $this->load->model('sale/orderlog');

            $order_info = $this->model_account_order->getOrder($order_id, true);

            //    echo "<pre>";print_r( $order_info);die;
            if (null != $order_info && (15 == $order_info['order_status_id'] || 14 == $order_info['order_status_id'])) {
                $order_products = $this->model_account_order->getOrderProducts($order_id);
                $log->write($order_products);
                foreach ($args['products'] as $product) {
                    // $product_id=$product['product_id'];
                    $product_id = $product['product_store_id'];
                    $quantity = $product['quantity'];
                    $unit = $product['unit'];
                    // $total = $product['total'];
                    $variation_id = $product['variation_id'];
                    $product_note = $product['product_note'];

                    $isExistingProduct = $product['isExistingProduct'];
                    $key = array_search($product_id, array_column($order_products, 'product_id'));

                    // $this->load->model('sale/orderlog');
                    if ($key !== false) {
                        $this->load->model('assets/product');
                        $ordered_product_info = $this->model_account_order->getOrderProductByOrderProductId($order_id, $order_products[$key]['product_id'], $order_products[$key]['order_product_id']);
                        $log->write('ordered_product_info');
                        $log->write($ordered_product_info);
                        $log->write('ordered_product_info');
                        $product_info = $this->model_assets_product->getProductForPopup($order_products[$key]['product_id'], false, $order_products[$key]['store_id']);
                        $s_price = 0;
                        $o_price = 0;

                        // if (!$this->config->get('config_inclusiv_tax')) {
                        //     //get price html
                        //     if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                        //         $product_info['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                        //         $o_price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                        //     } else {
                        //         $product_info['price'] = false;
                        //     }
                        //     if ((float) $product_info['special_price']) {
                        //         $product_info['special_price'] = $this->currency->format($this->tax->calculate($product_info['special_price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                        //         $s_price = $this->tax->calculate($product_info['special_price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                        //     } else {
                        //         $product_info['special_price'] = false;
                        //     }
                        // } else
                        {
                            $s_price = $product_info['special_price'];
                            $o_price = $product_info['price'];
                            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                                $product_info['price'] = $this->currency->format($product_info['price']);
                            } else {
                                $product_info['price'] = $product_info['price'];
                            }
                            if ((float) $product_info['special_price']) {
                                $product_info['special_price'] = $this->currency->format($product_info['special_price']);
                            } else {
                                $product_info['special_price'] = $product_info['special_price'];
                            }
                        }
                        $cachePrice_data = $this->cache->get('category_price_data');
                        if (CATEGORY_PRICE_ENABLED == true && isset($cachePrice_data) && isset($cachePrice_data[$product_info['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $order_products[$key]['store_id']])) {
                            $s_price = $cachePrice_data[$product_info['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $order_products[$key]['store_id']];
                            $o_price = $cachePrice_data[$product_info['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $order_products[$key]['store_id']];
                            $product_info['special_price'] = $this->currency->format($s_price);
                            $product_info['price'] = $this->currency->format($o_price);
                        }
                        $percent_off = null;
                        if (isset($s_price) && isset($o_price) && 0 != $o_price && 0 != $s_price) {
                            $percent_off = (($o_price - $s_price) / $o_price) * 100;
                        }

                        $log->write('product info');
                        $log->write($product_info);
                        $log->write('product info');
                        $special_price = explode(' ', $product_info['special_price']);
                        $log->write($special_price);
                        $special_price[1] = str_replace(',', '', $special_price[1]);

                        $total_without_tax = $special_price[1] * $quantity;

                        $total_with_tax = $this->config->get('config_tax') ? ($this->tax->calculate($special_price[1], $product_info['tax_class_id'], $this->config->get('config_tax')) * $quantity) : 0;
                        $tax = 0;
                        $single_product_tax = 0;

                        if ($total_with_tax > 0 && $this->config->get('config_tax') == true) {
                            $tax = $total_with_tax - $total_without_tax;
                            $log->write('TAX');
                            $log->write($total_with_tax);
                            $log->write($total_without_tax);
                            $log->write($tax);
                            $log->write('TAX');
                            $single_product_tax = $tax / $quantity;
                            $log->write('single_product_tax');
                            $log->write($single_product_tax);
                            $log->write('single_product_tax');
                        }
                        $total = $special_price[1] * $quantity + ($this->config->get('config_tax') ? ($order_products[$key]['tax'] * $quantity) : 0);
                        $log->write('TOTAL');
                        $log->write($total);
                        $log->write('TOTAL');
                        $log->write($special_price[1]);
                        $log->write($this->tax->calculate($special_price[1], $product_info['tax_class_id'], $this->config->get('config_tax')));
                        $log->write($product_id);
                        $log->write($product_id);

                        $data['order_id'] = $order_id;
                        $data['order_product_id'] = $ordered_product_info['order_product_id'];
                        $data['order_status_id'] = $order_info['order_status_id'];
                        $data['product_store_id'] = $ordered_product_info['product_id'];
                        $data['general_product_id'] = $ordered_product_info['general_product_id'];
                        $data['store_id'] = $ordered_product_info['store_id'];
                        $data['vendor_id'] = $ordered_product_info['vendor_id'];
                        $data['name'] = $ordered_product_info['name'];
                        $data['unit'] = $ordered_product_info['unit'];
                        $data['model'] = $ordered_product_info['model'];
                        $data['old_quantity'] = $ordered_product_info['quantity'];
                        $data['quantity'] = $quantity;

                        $log->write($isExistingProduct);
                        // if ($isExistingProduct == "true") { //existing product quantity is modified.
                        $log->write('Quantity change');

                        if (isset($this->request->post['product_note']) && $this->request->post['product_note'] != NULL) {
                            $this->db->query('UPDATE ' . DB_PREFIX . 'order_product SET product_note = "' . $product_note . '", quantity = ' . $quantity . ', tax = ' . $single_product_tax . ', total = ' . $total_without_tax . " WHERE order_product_id = '" . (int) $order_products[$key]['order_product_id'] . "' AND order_id  = '" . (int) $order_id . "' AND product_id = '" . (int) $product_id . "'");
                            $this->db->query('UPDATE ' . DB_PREFIX . 'real_order_product SET quantity = ' . $quantity . ', tax = ' . $single_product_tax . ', total = ' . $total_without_tax . " WHERE order_product_id = '" . (int) $order_products[$key]['order_product_id'] . "' AND order_id  = '" . (int) $order_id . "' AND product_id = '" . (int) $product_id . "'");
                            $this->model_sale_orderlog->addOrderLog($data);
                        } else {
                            $this->db->query('UPDATE ' . DB_PREFIX . 'order_product SET quantity = ' . $quantity . ', tax = ' . $single_product_tax . ', total = ' . $total_without_tax . " WHERE order_product_id = '" . (int) $order_products[$key]['order_product_id'] . "' AND order_id  = '" . (int) $order_id . "' AND product_id = '" . (int) $product_id . "'");
                            $this->db->query('UPDATE ' . DB_PREFIX . 'real_order_product SET quantity = ' . $quantity . ', tax = ' . $single_product_tax . ', total = ' . $total_without_tax . " WHERE order_product_id = '" . (int) $order_products[$key]['order_product_id'] . "' AND order_id  = '" . (int) $order_id . "' AND product_id = '" . (int) $product_id . "'");
                            $this->model_sale_orderlog->addOrderLog($data);
                        }

                        $order_totals = $this->db->query('SELECT SUM(total) AS total FROM ' . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");

                        $order_products_updated = $this->model_account_order->getOrderProducts($order_id);
                        $total_tax_updated = 0;
                        foreach ($order_products_updated as $order_products_update) {
                            $total_tax_updated += $order_products_update['quantity'] * $order_products_update['tax'];
                        }

                        $order_tax_totals = $this->db->query('SELECT SUM(tax) AS tax FROM ' . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");
                        $log->write($order_totals->row['total']);
                        $log->write($total_tax_updated);
                        $log->write($order_totals->row['total'] + $total_tax_updated);
                        $order_total = $order_totals->row['total'] + $total_tax_updated;

                        $order_product_details = $this->db->query('SELECT * FROM ' . DB_PREFIX . "order_product WHERE order_product_id = '" . (int) $order_products[$key]['order_product_id'] . "' AND order_id  = '" . (int) $order_id . "' AND product_id = '" . (int) $product_id . "'");

                        $this->db->query('UPDATE ' . DB_PREFIX . "order_total SET `value` = '" . $order_total . "' WHERE order_id = '" . $order_id . "' AND code='total'");
                        $this->db->query('UPDATE ' . DB_PREFIX . "order_total SET `value` = '" . $total_tax_updated . "' WHERE order_id = '" . $order_id . "' AND code='tax'");
                        $this->db->query('UPDATE ' . DB_PREFIX . "order_total SET `value` = '" . $order_totals->row['total'] . "' WHERE order_id = '" . $order_id . "' AND code='sub_total'");
                        $this->db->query('UPDATE ' . DB_PREFIX . "order SET `total` = '" . $order_total . "' WHERE order_id = '" . $order_id . "'");

                        if ($quantity <= 0) {
                            // $log = new Log('error.log');
                            // $log->write('DELETED');
                            // $log->write($quantity);
                            // $log->write('DELETED');
                            $this->db->query("DELETE FROM `" . DB_PREFIX . "order_product` WHERE order_product_id = '" . (int) $order_products[$key]['order_product_id'] . "' AND order_id  = '" . (int) $order_id . "' AND product_id = '" . (int) $product_id . "'");
                            $this->db->query("DELETE FROM `" . DB_PREFIX . "real_order_product` WHERE order_product_id = '" . (int) $order_products[$key]['order_product_id'] . "' AND order_id  = '" . (int) $order_id . "' AND product_id = '" . (int) $product_id . "'");
                        }
                        // $log->write($order_products);
                        // $log->write($key);
                        // $log->write($order_totals->row['total']);
                        // $log->write($order_product_details);
                        // $json['status'] = true;
                        // $json['status'] = 'Your Order Updated!';
                        // Add to activity log
                        $this->load->model('account/activity');

                        $activity_data = [
                            'customer_id' => $this->customer->getId(),
                            'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
                            'order_id' => $order_id,
                        ];
                        $log->write('account edit1');

                        $this->model_account_activity->addActivity('order_product_quaantity_changed', $activity_data);

                        $log->write('order_products COUNT 1');
                        $log->write(count($order_products));
                        $log->write('order_products COUNT 1');
                        if (count($order_products) <= 0 || $order_totals->row['total'] <= 0) {
                            $log = new Log('error.log');
                            $this->load->model('account/activity');
                            $activity_data = [
                                'customer_id' => $this->customer->getId(),
                                'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
                                'order_id' => $order_id,
                            ];
                            // $log->write('account cancelled by customer 1');
                            // $this->model_account_activity->addActivity('order_cancelled_by_customer', $activity_data);
                            // $log->write('EMPTY ORDER 1');
                            // $this->refundCancelOrderByOrderId($order_id);    
                            // $json['status'] = true;
                            // $json['redirect'] = $this->url->link('account/order', '', 'SSL');
                            // $json['status'] = 'Your Order Cancelled!';
                            // }
                        }
                    } else { //IF NEW PRODUCT IS ADDED  $log->write('edit_order_new_product_added');
                        # code...
                        $this->load->model('assets/product');
                        $new_product = $this->model_assets_product->getProductByProductStoreId($product_id);
                        $product_info = $this->model_assets_product->getProductForPopup($new_product['product_store_id'], false, $new_product['store_id']);
                        $s_price = 0;
                        $o_price = 0;

                        // if (!$this->config->get('config_inclusiv_tax')) {
                        //     //get price html
                        //     if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                        //         $product_info['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                        //         $o_price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                        //     } else {
                        //         $product_info['price'] = false;
                        //     }
                        //     if ((float) $product_info['special_price']) {
                        //         $product_info['special_price'] = $this->currency->format($this->tax->calculate($product_info['special_price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                        //         $s_price = $this->tax->calculate($product_info['special_price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                        //     } else {
                        //         $product_info['special_price'] = false;
                        //     }
                        // } else 
                        {
                            $s_price = $product_info['special_price'];
                            $o_price = $product_info['price'];

                            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                                $product_info['price'] = $this->currency->format($product_info['price']);
                            } else {
                                $product_info['price'] = $product_info['price'];
                            }

                            if ((float) $product_info['special_price']) {
                                $product_info['special_price'] = $this->currency->format($product_info['special_price']);
                            } else {
                                $product_info['special_price'] = $product_info['special_price'];
                            }
                        }

                        $cachePrice_data = $this->cache->get('category_price_data');
                        if (CATEGORY_PRICE_ENABLED == true && isset($cachePrice_data) && isset($cachePrice_data[$product_info['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $product_info['store_id']])) {
                            $s_price = $cachePrice_data[$product_info['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $product_info['store_id']];
                            $o_price = $cachePrice_data[$product_info['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $product_info['store_id']];
                            $product_info['special_price'] = $this->currency->format($s_price);
                            $product_info['price'] = $this->currency->format($o_price);
                        }
                        $percent_off = null;
                        if (isset($s_price) && isset($o_price) && 0 != $o_price && 0 != $s_price) {
                            $percent_off = (($o_price - $s_price) / $o_price) * 100;
                        }

                        $log->write('product info');
                        $log->write($product_info);
                        $log->write('product info');

                        $special_price = explode(' ', $product_info['special_price']);
                        $log->write($special_price);
                        $special_price[1] = str_replace(',', '', $special_price[1]);

                        $total_without_tax = $special_price[1] * $quantity;

                        $total_with_tax = $this->config->get('config_tax') ? ($this->tax->calculate($special_price[1], $product_info['tax_class_id'], $this->config->get('config_tax')) * $quantity) : 0;
                        $tax = 0;
                        $single_product_tax = 0;

                        if ($total_with_tax > 0 && $this->config->get('config_tax') == true) {
                            $tax = $total_with_tax - $total_without_tax;
                            $log->write('TAX');
                            $log->write($total_with_tax);
                            $log->write($total_without_tax);
                            $log->write($tax);
                            $log->write('TAX');
                            $single_product_tax = $tax / $quantity;
                            $log->write('single_product_tax');
                            $log->write($single_product_tax);
                            $log->write('single_product_tax');
                        }

                        $log->write('TOTAL');
                        $log->write($total);
                        $log->write('TOTAL');
                        $log->write($special_price[1]);
                        $log->write($this->tax->calculate($special_price[1], $product_info['tax_class_id'], $this->config->get('config_tax')));
                        $log->write($product_id);
                        $log->write($product_id);
                        $this->load->model('extension/extension');
                        $product_info['vendor_id'] = $this->model_extension_extension->getVendorId($product_info['store_id']);
                        $product_note = $this->request->post['product_note'];
                        $this->db->query('INSERT INTO ' . DB_PREFIX . "order_product SET product_note='" . $this->request->post['product_note'] . "', vendor_id='" . (int) $product_info['vendor_id'] . "', store_id='" . (int) $product_info['store_id'] . "', order_id = '" . (int) $this->request->post['order_id'] . "', variation_id = '" . (int) $this->request->post['variation_id'] . "', product_id = '" . (int) $product_info['product_store_id'] . "', general_product_id = '" . (int) $product_info['product_id'] . "',  name = '" . $this->db->escape($product_info['name']) . "', model = '" . $this->db->escape($product_info['model']) . "', quantity = '" . $quantity . "', price = '" . (float) $special_price[1] . "', total = '" . (float) $total_without_tax . "', tax = '" . (float) $single_product_tax . "', product_type = 'replacable', unit = '" . $this->db->escape($product_info['unit']) . "'");

                        $ordered_product_info = $this->model_account_order->getOrderProductByProductId($order_id, $product_info['product_store_id']);
                        $data['order_id'] = $order_id;
                        $data['order_product_id'] = $ordered_product_info['order_product_id'];
                        $data['order_status_id'] = $order_info['order_status_id'];
                        $data['product_store_id'] = $ordered_product_info['product_id'];
                        $data['general_product_id'] = $ordered_product_info['general_product_id'];
                        $data['store_id'] = $ordered_product_info['store_id'];
                        $data['vendor_id'] = $ordered_product_info['vendor_id'];
                        $data['name'] = $ordered_product_info['name'];
                        $data['unit'] = $ordered_product_info['unit'];
                        $data['model'] = $ordered_product_info['model'];
                        $data['old_quantity'] = 0;
                        $data['quantity'] = $quantity;
                        $this->model_sale_orderlog->addOrderLog($data);

                        $order_totals = $this->db->query('SELECT SUM(total) AS total FROM ' . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");

                        $order_products_updated = $this->model_account_order->getOrderProducts($order_id);
                        $total_tax_updated = 0;
                        foreach ($order_products_updated as $order_products_update) {
                            $total_tax_updated += $order_products_update['quantity'] * $order_products_update['tax'];
                        }

                        $order_tax_totals = $this->db->query('SELECT SUM(tax) AS tax FROM ' . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");
                        $log->write($order_totals->row['total']);
                        $log->write($total_tax_updated);
                        $log->write($order_totals->row['total'] + $total_tax_updated);
                        $order_total = $order_totals->row['total'] + $total_tax_updated;
                        $order_product_details = $this->db->query('SELECT * FROM ' . DB_PREFIX . "order_product WHERE order_product_id = '" . (int) $order_products[$key]['order_product_id'] . "' AND order_id  = '" . (int) $order_id . "' AND product_id = '" . (int) $product_id . "'");
                        $this->db->query('UPDATE ' . DB_PREFIX . "order_total SET `value` = '" . $order_total . "' WHERE order_id = '" . $order_id . "' AND code='total'");
                        $this->db->query('UPDATE ' . DB_PREFIX . "order_total SET `value` = '" . $total_tax_updated . "' WHERE order_id = '" . $order_id . "' AND code='tax'");
                        $this->db->query('UPDATE ' . DB_PREFIX . "order_total SET `value` = '" . $order_totals->row['total'] . "' WHERE order_id = '" . $order_id . "' AND code='sub_total'");
                        $total_products = $this->db->query('SELECT SUM(quantity) AS quantity FROM ' . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");
                        $this->db->query('UPDATE ' . DB_PREFIX . "order SET `total` = '" . $order_total . "' WHERE order_id = '" . $order_id . "'");

                        $json['count_products'] = $total_products->row['quantity'];
                        $json['sub_total_amount'] = $this->currency->format($order_totals->row['total']);
                        $json['total_amount'] = $this->currency->format($order_totals->row['total'] + $total_tax_updated);
                        $json['total_tax_amount'] = $this->currency->format($total_tax_updated);
                        $json['quantity'] = $total_products->row['quantity'];
                        //$json['product_total_price'] = $this->currency->format($order_product_details->row['total']);
                        $json['product_total_price'] = $this->currency->format($total_with_tax);

                        if ($quantity <= 0) {
                            $log = new Log('error.log');
                            $log->write('DELETED');
                            $log->write($quantity);
                            $log->write('DELETED');
                            $this->db->query("DELETE FROM `" . DB_PREFIX . "order_product` WHERE order_product_id = '" . (int) $order_products[$key]['order_product_id'] . "' AND order_id  = '" . (int) $order_id . "' AND product_id = '" . (int) $product_id . "'");
                            $this->db->query("DELETE FROM `" . DB_PREFIX . "real_order_product` WHERE order_product_id = '" . (int) $order_products[$key]['order_product_id'] . "' AND order_id  = '" . (int) $order_id . "' AND product_id = '" . (int) $product_id . "'");
                        }

                        $log->write($order_products);
                        $log->write($key);
                        $log->write($order_totals->row['total']);
                        $log->write($order_product_details);
                        $json['status'] = true;
                        $json['status'] = 'Your Order Updated!';

                        // Add to activity log
                        $this->load->model('account/activity');

                        $activity_data = [
                            'customer_id' => $this->customer->getId(),
                            'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
                            'order_id' => $order_id,
                        ];
                        $log->write('account edit1');

                        $this->model_account_activity->addActivity('order_new_product_added', $activity_data);

                        $log->write('order_products COUNT 2');
                        $log->write(count($order_products));
                        $log->write('order_products COUNT 2');
                        if (count($order_products) <= 0 || $order_totals->row['total'] <= 0) {

                            $log = new Log('error.log');
                            $this->load->model('account/activity');
                            $activity_data = [
                                'customer_id' => $this->customer->getId(),
                                'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
                                'order_id' => $order_id,
                            ];
                            $log->write('account cancelled by customer 2');
                            $this->model_account_activity->addActivity('order_cancelled_by_customer', $activity_data);

                            $log->write('EMPTY ORDER 2');
                            // $this->refundCancelOrderByOrderId($order_id);
                            // $json['status'] = true;
                            // $json['redirect'] = $this->url->link('account/order', '', 'SSL');
                            // $json['status'] = 'Your Order Cancelled!';
                        }
                    }
                    $total_products = $this->db->query('SELECT SUM(quantity) AS quantity FROM ' . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");

                    $json['count_products'] = $total_products->row['quantity'];
                    $json['sub_total_amount'] = $this->currency->format($order_totals->row['total']);
                    $json['total_amount'] = $this->currency->format($order_totals->row['total'] + $total_tax_updated);
                    $json['total_tax_amount'] = $this->currency->format($total_tax_updated);

                    $json['quantity'] = $total_products->row['quantity'];
                    // $json[$product_id]['product_total_price'] = $this->currency->format($order_product_details->row['total']);
                    $json['product_total_price'] = $this->currency->format($total_with_tax);

                    $json['status'] = 200;
                    $json['success'] = 'Your Order Updated!';
                }
            } else {
                $json['success'] = 'You Cant Update Order In This Status!';
            }
        } catch (Exception $ex) {
            $json['status'] = 500;
        } finally {

            // $log->write('edit_order_quantity');
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }
    }

    public function getPendingOrders($args = []) {
        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');

        $this->load->language('account/order');

        // $this->document->setTitle($this->language->get('heading_title'));

        $url = '';

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['orders'] = [];

        $this->load->model('account/order');
        $this->load->model('account/address');
        $this->load->model('account/customer');

        $order_total = $this->model_account_order->getTotalPendingOrders();

        $results = $this->model_account_order->getPendingOrders(($page - 1) * 10, 10);

        //echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            $city_name = $this->model_account_order->getCityName($result['shipping_city_id']);

            $product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);

            $real_product_total = $this->model_account_order->getTotalRealOrderProductsByOrderId($result['order_id']);

            $order_total_detail = $this->load->controller('checkout/totals/getTotalOnly', $result['order_id']);

            //echo "<pre>";print_r($order_total_detail);die;
            $voucher_total = $this->model_account_order->getTotalOrderVouchersByOrderId($result['order_id']);

            $shipping_address = null;

            if (isset($result['shipping_address'])) {
                $shipping_address['address'] = $result['shipping_building_name'] . ', ' . $result['shipping_flat_number'];
                $shipping_address['city'] = $city_name;
                $shipping_address['zipcode'] = $result['shipping_zipcode'];
            }

            $shipped = false;
            foreach ($this->config->get('config_shipped_status') as $key => $value) {
                if ($value == $result['order_status_id']) {
                    $shipped = true;
                    break;
                }
            }
            if (!$shipped) {
                foreach ($this->config->get('config_complete_status') as $key => $value) {
                    if ($value == $result['order_status_id']) {
                        $shipped = true;
                        break;
                    }
                }
            }

            $realproducts = $this->model_account_order->hasRealOrderProducts($result['order_id']);

            // Totals
            $shipping_charge = '0';

            $totals = $this->model_account_order->getOrderTotals($result['order_id']);

            $latest_total = 0;
            //echo "<pre>";print_r($totals);die;
            foreach ($totals as $total) {
                if ('shipping' == $total['code']) {
                    if ($total['value'] > 0) {
                        $shipping_charge = $this->currency->format($total['value']);
                    }
                }

                if ('sub_total' == $total['code']) {
                    $sub_total = $this->currency->format($total['value']);
                }

                if ('total' == $total['code']) {
                    $latest_total = $total['value'];
                }
            }

            $all_app_order_status = ""; // $this->model_account_order->getAppOrderStatuses();

            $app_status_resp = $this->model_account_order->getAppOrderStatusMapping($result['order_status_id']);

            //   echo "<pre>";print_r($_SESSION);die;
            $app_status = '';
            $app_order_status_id = '';
            if ($app_status_resp['status']) {
                $app_status = $app_status_resp['data']['name'];
                $app_order_status_id = $app_status_resp['data']['code'];
            }

            if ($real_product_total) {
                $product_total = $real_product_total;
            }

            $this->load->model('sale/order');
            $approve_order_button = null;
            $order_appoval_access = false;

            // #region parent,Approval access
            // if ($api_info['parent'] != NULL && $api_info['parent']>0) {
            //     $customer_details = $this->db->query('SELECT customer_category FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . $api_info['parent'] . "' AND status = '1'");
            // } else {
            //     $customer_details = $this->db->query('SELECT customer_category FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . $api_info['customer_id']. "' AND status = '1'");
            // }
            // $this->session->data['customer_category'] = isset($customer_details->row['customer_category']) ? $customer_details->row['customer_category'] : null;
            // // echo "<pre>";print_r($customer_details);die; 
            // $this->session->data['order_approval_access'] = $customer_info['order_approval_access'];
            // $this->session->data['order_approval_access_role'] = $customer_info['order_approval_access_role'];
            // #endregion
            // if (empty($_SESSION['parent']) && $result['customer_id'] != $this->customer->getId()) {
            //     $approve_order_button = 'Need Approval';
            // }
            // if ($this->session->data['order_approval_access'] > 0 && $this->session->data['order_approval_access_role'] != NULL) {
            //     $order_appoval_access = true;
            // }
            $this->load->model('account/customer');
            $customer_info = $this->model_account_customer->getCustomer($result['customer_id']);
            $is_he_parents = $this->model_account_customer->CheckHeIsParent();
            $customer_parent_info = $this->model_account_customer->getCustomerParentDetails($result['customer_id']);

            $logincustomer_approvalaccess = $this->model_account_customer->CheckApprover();
            //   echo "<pre>";print_r($logincustomer_approvalaccess);die;

            $parent = false;

            if (empty($is_he_parents) && $result['customer_id'] != $this->customer->getId()) {
                $approve_order_button = 'Need Approval';
            }

            if ($logincustomer_approvalaccess['order_approval_access'] > 0 && $logincustomer_approvalaccess['order_approval_access_role'] != NULL) {
                $order_appoval_access = true;
            }
            if ($is_he_parents == NULL) {
                $parent = true;
                $order_appoval_access = true;
            }

            $sub_user_order = FALSE;
            $procurement_person = NULL;
            $head_chef = NULL;

            if (($customer_info['order_approval_access'] == NULL || $customer_info['order_approval_access'] == 0) && $customer_info['order_approval_access_role'] == NULL && $customer_parent_info != NULL) {
                $log = new Log('error.log');
                $order_approval_access = $this->db->query('SELECT c.customer_id, c.parent, c.order_approval_access_role, c.order_approval_access, c.email, c.firstname, c.lastname  FROM ' . DB_PREFIX . "customer c WHERE c.parent = '" . (int) $customer_parent_info['customer_id'] . "' AND c.order_approval_access = 1 AND (c.order_approval_access_role = 'head_chef' OR c.order_approval_access_role = 'procurement_person')");
                $order_approval_access_user = $order_approval_access->rows;

                foreach ($order_approval_access_user as $order_approval_access_use) {
                    if ($order_approval_access_use['order_approval_access_role'] == 'head_chef' && $order_approval_access_use['order_approval_access'] > 0) {
                        $head_chef = $order_approval_access_use['email'];
                        $log->write($order_approval_access_use['order_approval_access_role']);
                        $log->write($order_approval_access_use['order_approval_access']);
                        $log->write($order_approval_access_use['customer_id']);
                    }
                    if ($order_approval_access_use['order_approval_access_role'] == 'procurement_person' && $order_approval_access_use['order_approval_access'] > 0) {
                        $procurement_person = $order_approval_access_use['email'];
                        $log->write($order_approval_access_use['order_approval_access_role']);
                        $log->write($order_approval_access_use['order_approval_access']);
                        $log->write($order_approval_access_use['customer_id']);
                    }
                }
                $sub_user_order = TRUE;
            }


            $customer_parent_info = $this->model_account_customer->getCustomerParentEmail($result['customer_id']);

            $data['orders'][] = [
                'order_id' => $result['order_id'],
                'name' => $result['firstname'] . ' ' . $result['lastname'],
                'shipping_name' => $result['shipping_name'],
                'payment_method' => $result['payment_method'],
                'shipping_address' => $shipping_address,
                'order_total' => $order_total_detail,
                'store_name' => htmlspecialchars_decode($result['store_name']),
                'status' => $result['status'],
                'order_status_id' => $result['order_status_id'],
                'app_status' => $app_status,
                'app_order_status_id' => $app_order_status_id,
                'all_app_order_status' => $all_app_order_status,
                'shipped' => $shipped,
                'shipping_method' => $result['shipping_method'],
                'shipping_charge' => $shipping_charge,
                'realproducts' => $realproducts,
                'date_added' => date($this->language->get('date_format'), strtotime($result['date_added'])),
                'time_added' => date($this->language->get('time_format'), strtotime($result['date_added'])),
                'eta_date' => date($this->language->get('date_format'), strtotime($result['delivery_date'])),
                'delivered_date' => date($this->language->get('date_format'), strtotime($result['date_modified'])),
                'delivered_time' => date($this->language->get('time_format'), strtotime($result['date_modified'])),
                'eta_time' => $result['delivery_timeslot'],
                'products' => ($product_total + $voucher_total),
                'real_products' => ($real_product_total + $voucher_total),
                'left_symbol_currency' => $this->currency->getSymbolLeft(),
                'right_symbol_currency' => $this->currency->getSymbolRight(),
                'total' => $this->currency->format($latest_total, $result['currency_code'], $result['currency_value']),
                'sub_total' => $sub_total,
                // 'href' => $this->url->link('account/order/info', 'order_id='.$result['order_id'], 'SSL'),
                // 'real_href' => $this->url->link('account/order/realinfo', 'order_id='.$result['order_id'], 'SSL'),
                'customer_parent_info' => $customer_parent_info,
                // 'accept_reject_href' => $this->url->link('account/order/accept_reject', 'order_id=' . $result['order_id'], 'SSL'),
                'parent_approve_order' => $approve_order_button,
                'customer_id' => $result['customer_id'],
                'parent_approval' => $result['parent_approval'],
                'order_approval_access' => $order_appoval_access,
                'head_chef' => $result['head_chef'],
                'procurement' => $result['procurement'],
                'sub_user_order' => $sub_user_order,
                'procurement_person_email' => $procurement_person,
                'head_chef_email' => $head_chef,
                // 'order_approval_access_role' => $this->session->data['order_approval_access_role'],
                'order_approval_access_role' => $customer_parent_info['order_approval_access_role'],
                'parent_details' => $customer_parent_info != NULL && $customer_parent_info['email'] != NULL ? $customer_parent_info['email'] : NULL,
                // 'edit_order' => 15 == $result['order_status_id'] && (empty($_SESSION['parent']) || $order_appoval_access) ? $this->url->link('account/order/edit_order', 'order_id=' . $result['order_id'], 'SSL') : '',
                'order_company' => isset($customer_info) && null != $customer_info['company_name'] ? $customer_info['company_name'] : null,
                'isparent' => $parent,
            ];
        }

        $data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($order_total - 10)) ? $order_total : ((($page - 1) * 10) + 10), $order_total, ceil($order_total / 10));

        $json['data'] = $data;

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validates($args) {

        if (empty($args['order_id'])) {
            $this->error['orer_id'] = 'Invalid Order ID!';
        }

        return !$this->error;
    }

    public function addReOrder($args = []) {

        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        // $data['kondutoStatus'] = $this->config->get('config_konduto_status');
        // $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        if (!$this->customer->isLogged()) {
            $json['status'] = 10014;

            $json['message'] = 'Unauthorized Session Expired!';

            http_response_code(401);
        }

        if ($this->validates($args) && $this->customer->isLogged()) {

            $this->load->model('account/order');
            $this->load->model('account/wishlist');

            $log = new Log('error.log');
            $order_id = $this->request->post['order_id'];
            $log->write($this->request->post['order_id']);
            $log->write('Order List Products');

            $order_details = $this->model_account_order->getOrderDetailsById($order_id);

            $Orderlist_products = $this->model_account_wishlist->getAvailableOrderedProducts($order_id);
            $log->write($Orderlist_products);
            $log->write('Order List Products obtained');

            if (is_array($Orderlist_products) && count($Orderlist_products) > 0) {
                $log->write('Order List Products inner');

                $all_products = array();
                foreach ($Orderlist_products as $Orderlist_product) {
                    $product_info = array();
                    $category_status_price_details = array();
                    $log->write('Order List Products 2');
                    $log->write($Orderlist_product);
                    $log->write($_SESSION['customer_category']);
                    $log->write('Order List Products 2');
                    $this->load->model('assets/product');
                    $store_data = $this->model_assets_product->getProductStoreId($Orderlist_product['product_id'], $Orderlist_product['store_id']);
                    $product_info = $this->model_assets_product->getDetailproduct($store_data['product_store_id']);

                    $percent_off = null;
                    if (isset($product_info['special_price']) && isset($product_info['price']) && 0 != $product_info['price'] && 0 != $product_info['special_price']) {
                        $percent_off = (($product_info['price'] - $product_info['special_price']) / $product_info['price']) * 100;
                    }

                    $this->load->model('tool/image');
                    if (file_exists(DIR_IMAGE . $product_info['image'])) {
                        $image = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
                    } else {
                        $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
                    }

                    $category_status_price_details = $this->model_assets_product->getCategoryPriceStatusByProductStoreId($store_data['product_store_id']);
                    $log = new Log('error.log');
                    $log->write($category_status_price_details);
                    $category_price_status = is_array($category_status_price_details) && array_key_exists('status', $category_status_price_details) && $category_status_price_details['status'] == 1 ? $category_status_price_details['status'] : 0;

                    $log->write('category_price_status');
                    $log->write($category_price_status);
                    $log->write('category_price_status');

                    if (isset($product_info) && count($product_info) > 0 && $category_price_status == 1) {
                        $product_info['category_price'] = $category_status_price_details['price'];
                        $product_info['ordered_quantity'] = $Orderlist_product['quantity'];
                        $product_info['product_note'] = $Orderlist_product['product_note'];
                        $product_info['percent_off'] = $percent_off;
                        $product_info['image'] = $image;
                        $log->write('store details');
                        $log->write($product_info);
                        $log->write($store_data);
                        $log->write('store details');
                    } else {
                        $product_info['category_price'] = 0;
                        $product_info['ordered_quantity'] = $Orderlist_product['quantity'];
                        $product_info['product_note'] = $Orderlist_product['product_note'];
                        $product_info['percent_off'] = $percent_off;
                        $product_info['image'] = $image;
                    }
                    $all_products[] = $product_info;
                    $json['data'] = $all_products;
                    $json['message'] = 'Order Products!';
                }
            }
        } else {
            $json['status'] = 10014;

            foreach ($this->error as $key => $value) {
                $json['message'][] = ['type' => $key, 'body' => $value];
            }

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getCheckMinimumOrderTotal() {
        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];
        $log = new Log('error.log');
        if (($this->config->get('shipping_status'))) {
            if (isset($this->session->data['delivery_charge_terms']) && $this->session->data['delivery_charge_terms'] == TRUE) {
                $json['min_order_total_reached'] = "TRUE";
            } else {

                $log = new Log('error.log');
                $json['min_order_total_reached'] = "FALSE";
                $log->write('min order value ');

                $sub_total = $this->cart->getSubTotal();
                $json['min_order_total_reached'] = $this->config->get('config_active_store_minimum_order_amount') <= $sub_total ? "TRUE" : "FALSE";
                $json['amount_required'] = "KES " . ($this->config->get('config_active_store_minimum_order_amount') - $sub_total);
                // echo "<pre>";print_r($json['amount_required']);die;
                $json['delivery_charge'] = "KES " . $this->config->get('config_active_store_delivery_charge');
                $log->write($json['min_order_total_reached']);
                $log->write($sub_total);
                $log->write('min order value ');
            }
        } else {
            $json['min_order_total_reached'] = "TRUE";
        }





        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addAcceptDeliveryCharge() {
        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];
        $log = new Log('error.log');
        $json['delivery_charge_terms'] = $this->request->post['accept_terms'];
        $this->session->data['delivery_charge_terms'] = $this->request->post['accept_terms'];
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addAcceptOtherVendorOrderTerm() {

        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];
        $log = new Log('error.log');
        $json['vendor_terms'] = $this->request->post['accept_terms'];
        $this->session->data['accept_vendor_terms'] = $this->request->post['accept_terms'];
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getCheckOtherVendorOrderExist() {

        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];
        $log = new Log('error.log');
        $json['modal_open'] = FALSE;
        if (isset($this->session->data['accept_vendor_terms']) && $this->session->data['accept_vendor_terms'] == TRUE) {
            $json['modal_open'] = FALSE;
        } else {
            foreach ($this->cart->getProducts() as $store_products) {
                /* FOR KWIKBASKET ORDERS */
                $log->write('CheckOtherVendorOrderExists');
                $log->write($store_products['store_id']);
                $log->write('CheckOtherVendorOrderExists');
                if ($store_products['store_id'] > 75 && $this->customer->getPaymentTerms() != 'Payment On Delivery') {
                    $json['modal_open'] = TRUE;
                }
            }
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getremoveothervendorproductsfromcart() {
        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];
        $json['products_removed'] = FALSE;
        $log = new Log('error.log');
        $log->write($this->cart->countProducts());
        $previous_count = $this->cart->countProducts();
        foreach ($this->cart->getProducts() as $store_products) {
            /* FOR KWIKBASKET ORDERS */
            if ($store_products['store_id'] > 75) {
                $log->write('CheckOtherVendorOrderExists');
                $log->write($store_products['key']);
                $this->cart->remove($store_products['key']);
                $log->write('CheckOtherVendorOrderExists');
            }
        }
        $log->write($this->cart->countProducts());
        $present_count = $this->cart->countProducts();
        if ($previous_count > $present_count) {
            $json['products_removed'] = TRUE;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addTotalByStore() {

        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->request->post['store_id'];
        $data = $this->cart->getTotalByStore($this->request->post['store_id']);
        $json['data'] = $data;
        $json['message'] = 'Store Total';
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addTotalKwikBasketStore() {

        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $data = $this->cart->getTotalForKwikBasket();
        $json['data'] = $data;
        $json['message'] = 'Store Total';
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validatenew($args) {
        if (empty($args['payment_method'])) {
            $this->error['payment_method'] = $this->language->get('error_payment_method');
        }

        if (empty($args['payment_method_code'])) {
            $this->error['payment_method_code'] = $this->language->get('error_payment_method_code');
        }

        if (((isset($args['payment_method_code']) && isset($args['payment_method']) && $args['payment_method_code'] == 'mpesa' && $args['payment_method'] == 'mPesa Online') && !isset($args['mpesa_mobile_number'])) || ((isset($args['payment_method_code']) && isset($args['payment_method']) && $args['payment_method_code'] == 'mpesa' && $args['payment_method'] == 'mPesa Online' && isset($args['mpesa_mobile_number'])) && $args['mpesa_mobile_number'] == NULL)) {
            $this->error['mpesa_mobile_number'] = 'Mpesa Mobile Number Required!';
        }

        if (array_key_exists('payment_method', $args) && $args['payment_method'] == 'mPesa Online' && !array_key_exists('mpesa_refrence_id', $args)) {
            $this->error['mpesa_refrence_id'] = 'Mpesa Reference ID Required!';
        }

        if (array_key_exists('payment_method_code', $args) && $args['payment_method_code'] == 'mpesa' && !array_key_exists('mpesa_refrence_id', $args)) {
            $this->error['mpesa_refrence_id'] = 'Mpesa Reference ID Required!';
        }

        if (empty($args['shipping_address_id'])) {
            $this->error['shipping_address_id'] = $this->language->get('error_shipping_address_id');
        }

        // if (empty($args['shipping_city_id'])) {
        //     $this->error['error_shipping_city_id'] = $this->language->get('error_shipping_city_id');
        // }

        if (empty($args['stores']) || !is_array($args['stores'])) {
            $this->error['error_stores'] = $this->language->get('error_stores');
        }

        if (empty($args['products']) || !is_array($args['products'])) {
            $this->error['error_products'] = $this->language->get('error_products');
        }

        if (!empty($args['products']) && is_array($args['products'])) {
            foreach ($args['products'] as $product) {
                if (!array_key_exists('product_store_id', $product)) {
                    $this->error['product_store_id'] = 'Product Store ID Required!';
                }

                if (!array_key_exists('store_id', $product)) {
                    $this->error['store_id'] = 'Store ID Required!';
                }

                if (!array_key_exists('store_product_variation_id', $product)) {
                    $this->error['store_product_variation_id'] = 'Product Store Variation ID Required!';
                }

                if (!array_key_exists('product_type', $product)) {
                    $this->error['product_type'] = 'Product Type Required!';
                }

                if (!array_key_exists('product_note', $product)) {
                    $this->error['product_note'] = 'Product Note Required!';
                }

                if (!array_key_exists('quantity', $product)) {
                    $this->error['quantity'] = 'Product Quantity Required!';
                }

                if (!array_key_exists('price', $product)) {
                    $this->error['price'] = 'Product Price Required!';
                }
            }
        }

        if (!empty($args['stores']) && is_array($args['stores'])) {
            foreach ($args['stores'] as $store) {
                if (!array_key_exists('store_id', $store)) {
                    $this->error['store_id'] = 'Store ID Required!';
                }

                if (!array_key_exists('timeslot', $store)) {
                    $this->error['timeslot'] = 'Store TimeSlot Required!';
                }

                if (!array_key_exists('timeslot_selected', $store)) {
                    $this->error['timeslot_selected'] = 'Store TimeSlot Selected Required!';
                }

                if (!array_key_exists('dates', $store)) {
                    $this->error['dates'] = 'Store TimeSlot Selected Required!';
                }

                if (!array_key_exists('delivery_date', $store)) {
                    $this->error['delivery_date'] = 'Store Delivery Date Required!';
                }

                if (!array_key_exists('comment', $store)) {
                    $this->error['comment'] = 'Store Comment Required!';
                }

                if (!array_key_exists('shipping_code', $store)) {
                    $this->error['shipping_code'] = 'Store Shipping Code Required!';
                }

                if (!array_key_exists('shipping_method', $store)) {
                    $this->error['shipping_method'] = 'Store Shipping Method Required!';
                }

                if (!array_key_exists('sub_total', $store)) {
                    $this->error['sub_total'] = 'Store Sub Total Required!';
                }

                if (!array_key_exists('total', $store)) {
                    $this->error['total'] = 'Store Total Required!';
                }

                if (!array_key_exists('weight', $store)) {
                    $this->error['weight'] = 'Store Weight Required!';
                }

                if (!array_key_exists('order_reference_number', $store)) {
                    $this->error['order_reference_number'] = 'Store Order Reference Number Required!';
                }
            }
        }

        $vendor_terms = json_decode($this->getCheckOtherVendorOrderExist(), true);
        if ($vendor_terms['modal_open'] == TRUE) {
            $this->error['vendor_terms'] = 'Please accept vendor terms!';
        }

        $pending_orders_count = $this->getunpaidorderscount();
        if ($pending_orders_count['unpaid_orders_count'] > 0) {
            $this->error['unpaid_orders'] = 'Your Order(s) Payment Is Pending!';
        }

        if ((!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $this->response->redirect($this->url->link('checkout/cart'));
        }

        if ($this->customer->getId() == NULL || $this->customer->getId() <= 0 || $this->customer->getId() == '') {
            $this->error['customer_id_invalid'] = 'Please Login Again!';
        }

        if ($this->cart->countProducts() <= 0) {
            $this->error['customer_cart_is_empty'] = 'Your Cart Is Empty Please Login Again!';
        }

        $log = new Log('error.log');
        $log->write($this->customer->getId() . 'ORDER_VALIDATION_FAILED');
        $log->write($this->error);
        $log->write($this->customer->getId() . 'ORDER_VALIDATION_FAILED');
        //echo "<pre>";print_r($this->error);die;
        return !$this->error;
    }

    protected function validatenewly($args) {
        if (empty($args['payment_method'])) {
            $this->error['payment_method'] = $this->language->get('error_payment_method');
        }

        if (empty($args['payment_method_code'])) {
            $this->error['payment_method_code'] = $this->language->get('error_payment_method_code');
        }

        if (((isset($args['payment_method_code']) && isset($args['payment_method']) && $args['payment_method_code'] == 'mpesa' && $args['payment_method'] == 'mPesa Online') && !isset($args['mpesa_mobile_number'])) || ((isset($args['payment_method_code']) && isset($args['payment_method']) && $args['payment_method_code'] == 'mpesa' && $args['payment_method'] == 'mPesa Online' && isset($args['mpesa_mobile_number'])) && $args['mpesa_mobile_number'] == NULL)) {
            $this->error['mpesa_mobile_number'] = 'Mpesa Mobile Number Required!';
        }

        /* if (array_key_exists('payment_method', $args) && $args['payment_method'] == 'mPesa Online' && !array_key_exists('mpesa_refrence_id', $args)) {
          $this->error['mpesa_refrence_id'] = 'Mpesa Reference ID Required!';
          } */

        /* if (array_key_exists('payment_method_code', $args) && $args['payment_method_code'] == 'mpesa' && !array_key_exists('mpesa_refrence_id', $args)) {
          $this->error['mpesa_refrence_id'] = 'Mpesa Reference ID Required!';
          } */

        if (empty($args['shipping_address_id'])) {
            $this->error['shipping_address_id'] = $this->language->get('error_shipping_address_id');
        }

        // if (empty($args['shipping_city_id'])) {
        //     $this->error['error_shipping_city_id'] = $this->language->get('error_shipping_city_id');
        // }

        if (empty($args['stores']) || !is_array($args['stores'])) {
            $this->error['error_stores'] = $this->language->get('error_stores');
        }

        if (empty($args['products']) || !is_array($args['products'])) {
            $this->error['error_products'] = $this->language->get('error_products');
        }

        if (!empty($args['products']) && is_array($args['products'])) {
            foreach ($args['products'] as $product) {
                if (!array_key_exists('product_store_id', $product)) {
                    $this->error['product_store_id'] = 'Product Store ID Required!';
                }

                if (!array_key_exists('store_id', $product)) {
                    $this->error['store_id'] = 'Store ID Required!';
                }

                if (!array_key_exists('store_product_variation_id', $product)) {
                    $this->error['store_product_variation_id'] = 'Product Store Variation ID Required!';
                }

                if (!array_key_exists('product_type', $product)) {
                    $this->error['product_type'] = 'Product Type Required!';
                }

                if (!array_key_exists('product_note', $product)) {
                    $this->error['product_note'] = 'Product Note Required!';
                }

                if (!array_key_exists('quantity', $product)) {
                    $this->error['quantity'] = 'Product Quantity Required!';
                }

                if (!array_key_exists('price', $product)) {
                    $this->error['price'] = 'Product Price Required!';
                }
            }
        }

        if (!empty($args['stores']) && is_array($args['stores'])) {
            foreach ($args['stores'] as $store) {
                if (!array_key_exists('store_id', $store)) {
                    $this->error['store_id'] = 'Store ID Required!';
                }

                if (!array_key_exists('timeslot', $store)) {
                    $this->error['timeslot'] = 'Store TimeSlot Required!';
                }

                if (!array_key_exists('timeslot_selected', $store)) {
                    $this->error['timeslot_selected'] = 'Store TimeSlot Selected Required!';
                }

                if (!array_key_exists('dates', $store)) {
                    $this->error['dates'] = 'Store TimeSlot Selected Required!';
                }

                if (!array_key_exists('delivery_date', $store)) {
                    $this->error['delivery_date'] = 'Store Delivery Date Required!';
                }

                if (!array_key_exists('comment', $store)) {
                    $this->error['comment'] = 'Store Comment Required!';
                }

                if (!array_key_exists('shipping_code', $store)) {
                    $this->error['shipping_code'] = 'Store Shipping Code Required!';
                }

                if (!array_key_exists('shipping_method', $store)) {
                    $this->error['shipping_method'] = 'Store Shipping Method Required!';
                }

                if (!array_key_exists('sub_total', $store)) {
                    $this->error['sub_total'] = 'Store Sub Total Required!';
                }

                if (!array_key_exists('total', $store)) {
                    $this->error['total'] = 'Store Total Required!';
                }

                if (!array_key_exists('weight', $store)) {
                    $this->error['weight'] = 'Store Weight Required!';
                }

                if (!array_key_exists('order_reference_number', $store)) {
                    $this->error['order_reference_number'] = 'Store Order Reference Number Required!';
                }
            }
        }

        $vendor_terms = json_decode($this->getCheckOtherVendorOrderExist(), true);
        if ($vendor_terms['modal_open'] == TRUE) {
            $this->error['vendor_terms'] = 'Please accept vendor terms!';
        }

        $pending_orders_count = $this->getunpaidorderscount();
        if ($pending_orders_count['unpaid_orders_count'] > 0) {
            $this->error['unpaid_orders'] = 'Your Order(s) Payment Is Pending!';
        }

        if ((!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $this->response->redirect($this->url->link('checkout/cart'));
        }

        $log = new Log('error.log');
        $log->write($this->customer->getId() . 'ORDER_VALIDATION_FAILED');
        $log->write($this->error);
        $log->write($this->customer->getId() . 'ORDER_VALIDATION_FAILED');
        //echo "<pre>";print_r($this->error);die;
        return !$this->error;
    }

    protected function validation($args) {

        if (empty($args['order_id'])) {
            $this->error['order_id'] = 'Order Id Is Required!';
        }

        if (!empty($args['order_id']) && ($args['order_id'] <= 0 || is_nan($args['order_id']))) {
            $this->error['order_id'] = 'Order Id Is Invalid!';
        }

        return !$this->error;
    }

    protected function validationmissedproducts($args) {

        if (empty($args['order_id'])) {
            $this->error['order_id'] = 'Order Id Is Required!';
        }

        if (!empty($args['order_id']) && ($args['order_id'] <= 0 || is_nan($args['order_id']))) {
            $this->error['order_id'] = 'Order Id Is Invalid!';
        }

        if (empty($args['products']) || !is_array($args['products']) || (is_array($args['products']) && count($args['products']) <= 0)) {
            $this->error['products'] = 'Missed Products Are Required!' . count($args['products']);
        }

        return !$this->error;
    }

    public function getunpaidorders() {
        $json = [];
        $log = new Log('error.log');
        $log->write($this->customer->getPaymentTerms());
        $log->write($this->customer->getId());

        $data['pending_order_id'] = NULL;

        if ($this->customer->getPaymentTerms() == 'Payment On Delivery') {
            $this->load->model('account/order');
            $this->load->model('sale/order');
            $page = 1;
            $results_orders = $this->model_account_order->getOrdersNew(($page - 1) * 10, 10, $NoLimit = true);
            $PaymentFilter = ['mPesa On Delivery', 'Cash On Delivery', 'mPesa Online', 'Corporate Account/ Cheque Payment', 'PesaPal', 'Interswitch', 'Pezesha'];
            if (count($results_orders) > 0) {
                foreach ($results_orders as $order) {
                    if (in_array($order['payment_method'], $PaymentFilter) && ($order['order_status_id'] == 4 || $order['order_status_id'] == 5)) {
                        $order['transcation_id'] = $this->model_sale_order->getOrderTransactionId($order['order_id']);
                        if (empty($order['transcation_id'])) {
                            $data['pending_order_id'][] = $order['order_id'];
                        }
                    }
                }
            }
        }

        $json['status'] = 200;
        $json['data'] = $data['pending_order_id'];
        $json['unpaid_orders'] = count($data['pending_order_id']);
        $json['success'] = 'Customer Unpaid Orders';
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
        //return $data;
    }

    public function getunpaidorderscount() {
        $json = [];
        $log = new Log('error.log');
        $log->write($this->customer->getPaymentTerms());
        $log->write($this->customer->getId());

        $data['pending_order_id'] = NULL;

        if ($this->customer->getPaymentTerms() == 'Payment On Delivery') {
            $this->load->model('account/order');
            $this->load->model('sale/order');
            $page = 1;
            $results_orders = $this->model_account_order->getOrdersNew(($page - 1) * 10, 10, $NoLimit = true);
            $PaymentFilter = ['mPesa On Delivery', 'Cash On Delivery', 'mPesa Online', 'Corporate Account/ Cheque Payment', 'PesaPal', 'Interswitch', 'Pezesha'];
            if (count($results_orders) > 0) {
                foreach ($results_orders as $order) {
                    if (in_array($order['payment_method'], $PaymentFilter) && ($order['order_status_id'] == 4 || $order['order_status_id'] == 5)) {
                        $order['transcation_id'] = $this->model_sale_order->getOrderTransactionId($order['order_id']);
                        if (empty($order['transcation_id']) || $order['paid'] == 'P') {
                            $data['pending_order_id'][] = $order['order_id'];
                        }
                    }
                }
            }
        }

        $data['unpaid_orders_count'] = count($data['pending_order_id']);
        $data['message'] = count($data['pending_order_id']) > 0 ? 'Your Order(s) Payment Is Pending!' : '';
        return $data;
    }

    public function getunpaidorderscounts() {
        $json = [];
        $log = new Log('error.log');
        $log->write($this->customer->getPaymentTerms());
        $log->write($this->customer->getId());

        $data['pending_order_id'] = NULL;

        if ($this->customer->getPaymentTerms() == 'Payment On Delivery') {
            $this->load->model('account/order');
            $this->load->model('sale/order');
            $page = 1;
            $results_orders = $this->model_account_order->getOrdersNew(($page - 1) * 10, 10, $NoLimit = true);
            $PaymentFilter = ['mPesa On Delivery', 'Cash On Delivery', 'mPesa Online', 'Corporate Account/ Cheque Payment', 'PesaPal', 'Interswitch', 'Pezesha'];
            if (count($results_orders) > 0) {
                foreach ($results_orders as $order) {
                    if (in_array($order['payment_method'], $PaymentFilter) && ($order['order_status_id'] == 4 || $order['order_status_id'] == 5)) {
                        $order['transcation_id'] = $this->model_sale_order->getOrderTransactionId($order['order_id']);
                        if (empty($order['transcation_id']) || $order['paid'] == 'P') {
                            $data['pending_order_id'][] = $order['order_id'];
                        }
                    }
                }
            }
        }

        $data['unpaid_orders_count'] = count($data['pending_order_id']);
        $data['message'] = count($data['pending_order_id']) > 0 ? 'Your Order(s) Payment Is Pending!' : '';
        //return $data;
        $json['status'] = 200;
        $json['data'] = $data;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addOrderedProduct($args = []) {
        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        if ($this->validation($args)) {
            $log = new Log('error.log');
            $this->load->model('account/order');
            $this->load->model('assets/product');
            $this->load->model('tool/upload');
            $this->load->model('tool/image');
            $order_info = $this->model_account_order->getOrderByCustomerId($args['order_id']);
            $products = $this->model_account_order->getRealOrderProducts($args['order_id']);
            if ($products == NULL || (is_array($products) && count($products) <= 0)) {
                $products = $this->model_account_order->getOrderProducts($args['order_id']);
            }
            $log->write($order_info);
            $log->write($products);
            if ($order_info == NULL || $products == NULL || (is_array($order_info) && count($order_info) <= 0) || (is_array($products) && count($products) <= 0)) {
                $json['status'] = 10014;

                $json['message'][] = 'Order Or Order Products Not Found!' . $this->customer->getId();
                http_response_code(404);
            } else {

                $data['products'] = [];

                $products = $this->model_account_order->getRealOrderProducts($args['order_id']);
                if ($products == NULL || (is_array($products) && count($products) <= 0)) {
                    $products = $this->model_account_order->getOrderProducts($args['order_id']);
                }
                foreach ($products as $product) {
                    $option_data = [];

                    $options = $this->model_account_order->getOrderOptions($args['order_id'], $product['order_product_id']);

                    foreach ($options as $option) {
                        if ('file' != $option['type']) {
                            $value = $option['value'];
                        } else {
                            $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

                            if ($upload_info) {
                                $value = $upload_info['name'];
                            } else {
                                $value = '';
                            }
                        }

                        $option_data[] = [
                            'name' => $option['name'],
                            'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value),
                        ];
                    }

                    if ($product['image'] != NULL && file_exists(DIR_IMAGE . $product['image'])) {
                        $image = $this->model_tool_image->resize($product['image'], 80, 100);
                    } else if ($product['image'] == NULL || !file_exists(DIR_IMAGE . $product['image'])) {
                        $image = $this->model_tool_image->resize('placeholder.png', 80, 100);
                    }

                    $data['products'][] = [
                        'product_id' => $product['product_id'],
                        'store_id' => $product['store_id'],
                        'vendor_id' => $product['vendor_id'],
                        'product_note' => $product['product_note'],
                        'name' => $product['name'],
                        'unit' => $product['unit'],
                        'model' => $product['model'],
                        'product_type' => $product['product_type'],
                        'image' => $image,
                        'option' => $option_data,
                        'quantity' => $product['quantity'],
                        'price' => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                        'total' => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
                    ];
                }
                $json['data'] = $data;
                $json['message'] = 'Ordered Products!';
            }
        } else {
            $json['status'] = 10014;

            foreach ($this->error as $key => $value) {
                $json['message'][] = ['type' => $key, 'body' => $value];
            }

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addMissingOrderProduct($args = []) {

        $this->load->model('account/order');
        $this->load->model('account/missedrejectedproducts');
        $log = new Log('error.log');
        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $order_info = $this->model_account_order->getOrderByCustomerId($args['order_id']);
        $products = $this->model_account_order->getRealOrderProducts($args['order_id']);
        if ($products == NULL || (is_array($products) && count($products) <= 0)) {
            $products = $this->model_account_order->getOrderProducts($args['order_id']);
        }

        if ($this->validationmissedproducts($args)) {
            foreach ($args['products'] as $product_id => $missed_product) {

                $products = $this->model_account_order->getRealOrderProducts($args['order_id']);
                if ($products == NULL || (is_array($products) && count($products) <= 0)) {
                    $products = $this->model_account_order->getOrderProducts($args['order_id']);
                }
                $find_product = NULL;
                foreach ($products as $product) {
                    if ($product['product_id'] == $product_id) {
                        $find_product['product_id'] = $product['product_id'];
                        $find_product['quantity'] = $product['quantity'];
                    }
                }

                if ((is_array($missed_product) && !array_key_exists('issue_type', $missed_product)) || (is_array($missed_product) && array_key_exists('issue_type', $missed_product) && $missed_product['issue_type'] != 'Rejected' && $missed_product['issue_type'] != 'Missed')) {
                    $json['status'] = 10014;

                    $json['message'] = 'Please Select Issue Type(Rejected Or Missed)!';
                    http_response_code(400);
                } elseif ($order_info == NULL || $products == NULL || (is_array($order_info) && count($order_info) <= 0) || (is_array($products) && count($products) <= 0)) {
                    $json['status'] = 10014;

                    $json['message'][] = 'Order Or Order Products Not Found!';
                    http_response_code(404);
                } elseif (is_array($missed_product) && array_key_exists('issue_type', $missed_product) && $missed_product['issue_type'] == 'Missed' && !array_key_exists('quantity', $missed_product)) {
                    $json['status'] = 10014;

                    $json['message'] = 'Missed Quantity Required!';
                    http_response_code(400);
                } elseif (is_array($missed_product) && array_key_exists('issue_type', $missed_product) && $missed_product['issue_type'] == 'Missed' && array_key_exists('quantity', $missed_product) && ($missed_product['quantity'] <= 0 || is_nan($missed_product['quantity']))) {
                    $json['status'] = 10014;

                    $json['message'][] = 'Missed Quantity Required!';
                    http_response_code(400);
                } elseif ($missed_product['issue_type'] == 'Missed' && $find_product['product_id'] == $product_id && $missed_product['quantity'] > $find_product['quantity']) {
                    $json['status'] = 10014;
                    $json['message'][] = ['type' => $product_id, 'body' => 'Missed Quantity Should Less Than Or Equal To Ordred Quantity!'];
                    http_response_code(400);
                } elseif ($find_product == NULL) {
                    $json['status'] = 10014;
                    $json['message'][] = ['type' => $product_id, 'body' => 'Invalid Missed Product!'];
                    http_response_code(400);
                } else {
                    $datainsert['order_id'] = $order_info['order_id'];
                    $datainsert['product_id'] = $product_id;
                    $datainsert['product_store_id'] = $product_id;
                    $datainsert['quantity'] = isset($missed_product['quantity']) && $missed_product['quantity'] != NULL && $missed_product['quantity'] > 0 ? $missed_product['quantity'] : $find_product['quantity'];
                    $datainsert['type'] = $missed_product['issue_type'];
                    $datainsert['notes'] = isset($missed_product['message']) ? $missed_product['message'] : NULL;
                    $this->model_account_missedrejectedproducts->addProducts($datainsert);
                    $json['message'][] = 'Products Saved Successfully!';
                }

                //$log->write($products);
            }
        } else {
            $json['status'] = 10014;

            foreach ($this->error as $key => $value) {
                $json['message'][] = ['type' => $key, 'body' => $value];
            }

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addOrderNew($args = []) {
        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $log = new Log('error.log');
        $log->write('Log 3.5');
        $log->write($args);
        $log->write('Log 3.5');
        //echo "<pre>";print_r($args);die;
        $data['kondutoStatus'] = $this->config->get('config_konduto_status');
        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        if ($this->validatenew($args)) {
            $log->write('addOrder');
            $log->write($args);
            $log->write('addOrder');
            $stores = array_keys($args['stores']);

            $active_Store_exsists = in_array("75", $stores);
            $shipping_added = 0;

            //print_r($stores);
            foreach ($stores as $store_id) {
                $order_data[$store_id] = [];
                $order_data[$store_id]['totals'] = [];

                $total = 0;
                $taxes = $this->cart->getTaxes();
                $taxes_by_store = $this->cart->getTaxesByStore($store_id);
                $log->write('taxes_by_store mobile');
                $log->write($store_id);
                $log->write($taxes_by_store);
                $log->write('taxes_by_store mobile');

                $this->load->model('extension/extension');

                $sort_order = [];

                $results = $this->model_extension_extension->getExtensions('total');

                foreach ($results as $key => $value) {
                    $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
                }
                array_multisort($sort_order, SORT_ASC, $results);

                foreach ($results as $result) {
                    if ($this->config->get($result['code'] . '_status')) {
                        $log->write($result['code']);
                        $this->load->model('total/' . $result['code']);

                        /* $log->write("in multiStoreIndex".$result['code']);
                          $log->write("in loop".$total); */

                        //$this->{'model_total_' . $result['code']}->getApiTotal( $order_data[$store_id]['totals'], $total, $taxes,$store_id ,$args['stores'][$store_id]);

                        if ($result['code'] == 'shipping') {
                            if ($active_Store_exsists == 1) {
                                $this->{'model_total_' . $result['code']}->getTotal($order_data[$store_id]['totals'], $total, $taxes_by_store, $store_id);
                            } else if ($result['code'] == 'shipping' && $active_Store_exsists == 0 && $shipping_added == 0) {
                                $this->{'model_total_' . $result['code']}->getTotal($order_data[$store_id]['totals'], $total, $taxes_by_store, -1);
                            }
                            $shipping_added = 1; //shipping charge added to one of the stores
                        } else {
                            $this->{'model_total_' . $result['code']}->getApiTotal($order_data[$store_id]['totals'], $total, $taxes_by_store, $store_id, $args);
                        }
                    }
                }

                $log->write('addOrder b total end');

                $sort_order = [];

                foreach ($order_data[$store_id]['totals'] as $key => $value) {
                    $sort_order[$key] = $value['sort_order'];
                }

                array_multisort($sort_order, SORT_ASC, $order_data[$store_id]['totals']);

                $this->load->model('sale/order');
                $store_info = $this->model_sale_order->getStoreInfo($store_id);

                $this->load->language('checkout/checkout');
                $order_data[$store_id]['invoice_prefix'] = $this->config->get('config_invoice_prefix');
                $order_data[$store_id]['store_id'] = $store_id;
                $order_data[$store_id]['store_name'] = $store_info['name'];

                $order_data[$store_id]['commission'] = ($store_info['commision'] > 0) ? $store_info['commision'] : $store_info['vendor_commision'];

                $order_data[$store_id]['fixed_commission'] = ($store_info['fixed_commision'] > 0) ? $store_info['fixed_commision'] : $store_info['vendor_fixed_commision'];

                if ($this->request->server['HTTPS']) {
                    $server = $this->config->get('config_ssl');
                } else {
                    $server = $this->config->get('config_url');
                }

                if ($order_data[$store_id]['store_id']) {
                    $order_data[$store_id]['store_url'] = $this->config->get('config_url');
                } else {
                    $order_data[$store_id]['store_url'] = $server;
                }

                if (!trim($order_data[$store_id]['store_url'])) {
                    $order_data[$store_id]['store_url'] = $server;
                }

                if ($this->customer->isLogged()) {
                    $this->load->model('account/customer');

                    $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

                    $order_data[$store_id]['customer_id'] = $this->customer->getId();
                    $order_data[$store_id]['customer_group_id'] = $customer_info['customer_group_id'];
                    $order_data[$store_id]['firstname'] = $customer_info['firstname'];
                    $order_data[$store_id]['lastname'] = $customer_info['lastname'];
                    $order_data[$store_id]['email'] = $customer_info['email'];
                    $order_data[$store_id]['telephone'] = $customer_info['telephone'];
                    $order_data[$store_id]['fax'] = $customer_info['fax'];
                    $order_data[$store_id]['custom_field'] = unserialize($customer_info['custom_field']);
                } elseif (isset($this->session->data['guest'])) {
                    $order_data[$store_id]['customer_id'] = 0;
                    $order_data[$store_id]['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
                    $order_data[$store_id]['firstname'] = $this->session->data['guest']['firstname'];
                    $order_data[$store_id]['lastname'] = $this->session->data['guest']['lastname'];
                    $order_data[$store_id]['email'] = $this->session->data['guest']['email'];
                    $order_data[$store_id]['telephone'] = $this->session->data['guest']['telephone'];
                    $order_data[$store_id]['fax'] = $this->session->data['guest']['fax'];
                    $order_data[$store_id]['custom_field'] = $this->session->data['guest']['custom_field'];
                }

                if (isset($args['payment_method'])) {
                    $order_data[$store_id]['payment_method'] = $args['payment_method'];
                } else {
                    $order_data[$store_id]['payment_method'] = '';
                }

                if (isset($args['mpesa_phonenumber']) && $args['mpesa_phonenumber'] != NULL) {
                    $order_data['mpesa_phonenumber'] = $args['mpesa_phonenumber'];
                } else {
                    $order_data['mpesa_phonenumber'] = $this->customer->getTelephone();
                }

                if (isset($args['payment_method_code'])) {
                    $order_data[$store_id]['payment_code'] = $args['payment_method_code'];

                    $c = $this->getPaymentName($args['payment_method_code']);
                    if (!empty($c)) {
                        $order_data[$store_id]['payment_method'] = $c;
                    }
                } else {
                    $order_data[$store_id]['payment_code'] = '';
                }

                if (isset($args['stores'][$store_id]['shipping_method']) && isset($args['stores'][$store_id]['shipping_code'])) {
                    if (isset($args['stores'][$store_id]['shipping_method'])) {
                        $order_data[$store_id]['shipping_method'] = $args['stores'][$store_id]['shipping_method'];
                    } else {
                        $order_data[$store_id]['shipping_method'] = '';
                    }

                    if (isset($args['stores'][$store_id]['shipping_code'])) {
                        $order_data[$store_id]['shipping_code'] = $args['stores'][$store_id]['shipping_code'];

                        $c = $this->getShippingName($args['stores'][$store_id]['shipping_code'], $store_id);
                        if (!empty($c)) {
                            $order_data[$store_id]['shipping_method'] = $c;
                        }
                    } else {
                        $order_data[$store_id]['shipping_code'] = '';
                    }
                } else {
                    $order_data[$store_id]['shipping_method'] = '';
                    $order_data[$store_id]['shipping_code'] = '';
                }

                if (isset($args['shipping_city_id'])) {
                    $shipping_city_id = $args['shipping_city_id'];
                    $order_data[$store_id]['shipping_city_id'] = $shipping_city_id;
                } else {
                    $order_data[$store_id]['shipping_city_id'] = '';
                }

                if (isset($args['shipping_address_id'])) {
                    $shipping_address_id = $args['shipping_address_id'];
                    $this->load->model('account/address');
                    $shipping_address_data = $this->model_account_address->getAddress($shipping_address_id);

                    $order_data[$store_id]['shipping_address'] = $shipping_address_data['address'];
                    $order_data[$store_id]['shipping_name'] = $shipping_address_data['name'];

                    $order_data[$store_id]['shipping_flat_number'] = $shipping_address_data['flat_number'];
                    $order_data[$store_id]['shipping_landmark'] = $shipping_address_data['landmark'];
                    $order_data[$store_id]['shipping_building_name'] = $shipping_address_data['building_name'];
                    $order_data[$store_id]['shipping_zipcode'] = $shipping_address_data['zipcode'];

                    $order_data[$store_id]['latitude'] = $shipping_address_data['latitude'];
                    $order_data[$store_id]['longitude'] = $shipping_address_data['longitude'];

                    if (isset($args['shipping_contact_no'])) {
                        $shipping_contact_no = $args['shipping_contact_no'];
                        $order_data[$store_id]['shipping_contact_no'] = $shipping_contact_no;
                    } elseif (isset($shipping_address_data['contact_no'])) {
                        $order_data[$store_id]['shipping_contact_no'] = $shipping_address_data['contact_no'];
                    } else {
                        $order_data[$store_id]['shipping_contact_no'] = '';
                    }
                } else {
                    $order_data[$store_id]['shipping_address'] = '';
                    $order_data[$store_id]['shipping_name'] = '';
                    $order_data[$store_id]['shipping_contact_no'] = '';
                    $order_data[$store_id]['shipping_zipcode'] = '';
                    $order_data[$store_id]['shipping_flat_number'] = '';
                    $order_data[$store_id]['shipping_landmark'] = '';
                    $order_data[$store_id]['shipping_building_name'] = '';
                }

                $order_data[$store_id]['products'] = [];

                $this->load->model('assets/product');

                foreach ($args['products'] as $product) {
                    $option_data = [];

                    $vendor_id = $this->model_extension_extension->getVendorId($product['store_id']);

                    $db_product_detail = $this->model_assets_product->getProductForPopupByApi($product['store_id'], $product['product_store_id']);

                    if ($store_id == $product['store_id']) {

                        if (is_null($db_product_detail['special_price']) || !($db_product_detail['special_price'] + 0)) {
                            $db_product_detail['special_price'] = $db_product_detail['price'];
                        }

                        $order_data[$store_id]['products'][] = [
                            'product_store_id' => $product['product_store_id'],
                            'product_id' => isset($db_product_detail['product_id']) ? $db_product_detail['product_id'] : '',
                            'store_product_variation_id' => $product['store_product_variation_id'],
                            'store_id' => $product['store_id'],
                            'vendor_id' => $vendor_id,
                            'name' => $db_product_detail['pd_name'],
                            'unit' => $db_product_detail['unit'],
                            'product_type' => trim($product['product_type']),
                            'product_note' => trim($product['product_note']),
                            'produce_type' => trim($product['produce_type']),
                            'model' => $db_product_detail['model'],
                            'option' => $option_data,
                            'download' => $product['download'],
                            'quantity' => $product['quantity'],
                            'subtract' => $db_product_detail['subtract_quantity'],
                            // 'price' => $db_product_detail['special_price'],
                            'price' => $product['price'], //check
                            // 'total' => ($product['quantity'] * $db_product_detail['special_price']),
                            'total' => ($product['price'] * $product['quantity']),
                            'tax' => $this->tax->getTax($product['price'], $db_product_detail['tax_class_id']),
                            // 'tax' => $this->tax->getTax($db_product_detail['special_price'], $db_product_detail['tax_class_id']),
                            'reward' => $product['reward'],
                        ];
                    }
                }
                $order_data[$store_id]['vouchers'] = [];

                /* if(isset($args['dropoff_notes']) && strlen($args['dropoff_notes']) > 0 ) {
                  $order_data[$store_id]['comment'] = $args['dropoff_notes'];
                  } else {
                  $order_data[$store_id]['comment'] = '';
                  } */

                if (isset($args['stores'][$store_id]['comment']) && strlen($args['stores'][$store_id]['comment']) > 0) {
                    $order_data[$store_id]['comment'] = $args['stores'][$store_id]['comment'];
                } else {
                    $order_data[$store_id]['comment'] = '';
                }

                $order_data[$store_id]['total'] = $total;

                $order_data[$store_id]['affiliate_id'] = 0;
                $order_data[$store_id]['marketing_id'] = 0;
                $order_data[$store_id]['tracking'] = '';
                $order_data[$store_id]['language_id'] = $this->config->get('config_language_id');
                $order_data[$store_id]['currency_id'] = $this->currency->getId();
                $order_data[$store_id]['currency_code'] = $this->currency->getCode();
                $order_data[$store_id]['currency_value'] = $this->currency->getValue($this->currency->getCode());
                $order_data[$store_id]['ip'] = $this->request->server['REMOTE_ADDR'];

                if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
                    $order_data[$store_id]['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
                } elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
                    $order_data[$store_id]['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
                } else {
                    $order_data[$store_id]['forwarded_ip'] = '';
                }

                /* if (isset($args['order_reference_number'])) {
                  $order_data[$store_id]['order_reference_number'] = $args['order_reference_number'];
                  } else {
                  $order_data[$store_id]['order_reference_number'] = '';
                  } */

                if (isset($args['stores'][$store_id]['order_reference_number']) && strlen($args['stores'][$store_id]['order_reference_number']) > 0) {
                    $order_data[$store_id]['order_reference_number'] = $args['stores'][$store_id]['order_reference_number'];
                } else {
                    $order_data[$store_id]['order_reference_number'] = '';
                }

                if (isset($this->request->server['HTTP_USER_AGENT'])) {
                    $order_data[$store_id]['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
                } else {
                    $order_data[$store_id]['user_agent'] = '';
                }

                if (isset($this->request->server['HTTP_USER_AGENT'])) {
                    $order_data[$store_id]['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
                } else {
                    $order_data[$store_id]['user_agent'] = '';
                }

                $order_data[$store_id]['accept_language'] = '';

                $this->load->model('api/checkout');
                if ($store_id == 75) {
                    if (isset($args['stores'][$store_id]['dates'])) {
                        $order_data[$store_id]['delivery_date'] = $args['stores'][$store_id]['dates'];
                    } else {
                        $order_data[$store_id]['delivery_date'] = date('d-m-Y');
                    }

                    //$log->write("shipin code".$order_data[$store_id]['shipping_code']);die;

                    if (isset($order_data[$store_id]['shipping_code']) && 'express.express' == trim($order_data[$store_id]['shipping_code'])) {
                        $order_data[$store_id]['delivery_date'] = date('d-m-Y');

                        $settings = $this->getSettings('express', 0);
                        $timeDiff = $settings['express_how_much_time'];

                        $min = 0;
                        if ($timeDiff) {
                            $i = explode(':', $timeDiff);
                            $min = $min + $i[0] * 60 + $i[1]; //add difference minut to current time
                        }
                        $to = date('h:ia', strtotime('+' . $min . ' minutes', strtotime(date('h:ia'))));

                        $delivery_timeslot = date('h:ia') . ' - ' . $to;

                        $order_data[$store_id]['delivery_timeslot'] = $delivery_timeslot;
                    } else {
                        if (isset($args['stores'][$store_id]['dates'])) {
                            $order_data[$store_id]['delivery_date'] = $args['stores'][$store_id]['dates'];
                        } else {
                            $order_data[$store_id]['delivery_date'] = date('d-m-Y');
                        }

                        if (isset($args['stores'][$store_id]['timeslot'])) {
                            $order_data[$store_id]['delivery_timeslot'] = $args['stores'][$store_id]['timeslot'];
                        } else {
                            $settings = $this->getSettings('express', 0);
                            $timeDiff = $settings['express_how_much_time'];

                            $min = 0;
                            if ($timeDiff) {
                                $i = explode(':', $timeDiff);
                                $min = $min + $i[0] * 60 + $i[1]; //add difference minut to current time
                            }
                            $to = date('h:ia', strtotime('+' . $min . ' minutes', strtotime(date('h:ia'))));

                            $delivery_timeslot = date('h:ia') . ' - ' . $to;

                            $order_data[$store_id]['delivery_timeslot'] = $delivery_timeslot;
                        }
                    }
                }
                if ($store_id != 75) {
                    $other_vendor_delivery_time = $this->load->controller('api/delivery_time/getothervendordeliverytime', $store_id);
                    $log = new Log('error.log');
                    $log->write('other_vendor_delivery_time');
                    $log->write($other_vendor_delivery_time);
                    $log->write('other_vendor_delivery_time');
                    //$other_vendor_delivery_time = $this->getothervendordeliverytime($store_id);

                    if ($other_vendor_delivery_time != null && $other_vendor_delivery_time['selected_time_slot_date'] != null && $other_vendor_delivery_time['selected_time_slot_date'] != '') {
                        $order_data[$store_id]['delivery_date'] = $other_vendor_delivery_time['selected_time_slot_date'];
                        $order_data[$store_id]['delivery_timeslot'] = $other_vendor_delivery_time['selected_time_slot_time'];
                    } else {
                        if (isset($args['stores'][$store_id]['dates'])) {
                            $order_data[$store_id]['delivery_date'] = $args['stores'][$store_id]['dates'];
                        } else {
                            $order_data[$store_id]['delivery_date'] = date('d-m-Y');
                        }

                        if (isset($args['stores'][$store_id]['timeslot'])) {
                            $order_data[$store_id]['delivery_timeslot'] = $args['stores'][$store_id]['timeslot'];
                        }
                    }
                }
            }
            $order_data[$store_id]['login_latitude'] = $args['login_latitude'];
            $order_data[$store_id]['login_longitude'] = $args['login_longitude'];
            $order_data[$store_id]['login_mode'] = $args['login_mode'];

            $log->write('addMultiOrder call');

            $order_ids = [];

            $order_ids = $this->model_api_checkout->addMultiOrder($order_data);

            $tot = 0;

            foreach ($stores as $store_id) {
                $data['totals'] = [];
                foreach ($order_data[$store_id]['totals'] as $total) {
                    $data['totals'][] = [
                        'title' => $total['title'],
                        'text' => $this->currency->format($total['value']),
                    ];
                    $tot += $total['value'];
                }
            }

            $transactionData = [
                'no_of_products' => count($args['products']),
                //'total' =>$tot,
                'total' => $args['total'],
            ];

            //$log->write($transactionData);

            $this->model_api_checkout->apiAddTransaction($transactionData, $order_ids);

            if (('mpesa' == $args['payment_method_code']) || ('mpesa' == $args['payment_method_code'] && 'wallet' == $args['payment_wallet_method_code'])) {
                //save for refrence id correct order id
                $order_ids['mpesa_phonenumber'] = $args['mpesa_phonenumber'];
                if (('mpesa' == $args['payment_method_code']) && (!isset($args['payment_wallet_method_code']))) {
                    $mpesa_result = $this->load->controller('api/customer/mpesa/mpesacheckout', $order_ids);
                }

                if (('mpesa' == $args['payment_method_code']) && isset($args['payment_wallet_method_code']) && 'wallet' == $args['payment_wallet_method_code']) {
                    $mpesa_result = $this->load->controller('api/customer/mpesa/mpesacheckout', $order_ids);
                }
                $log->write('mpesa_result');
                $log->write($mpesa_result);
                $log->write('mpesa_result');
                if (isset($mpesa_result)) {
                    $json['status'] = 200;
                    $json['msg'] = 'Payment Request Sent To Mpesa!';
                }
            } elseif (('pezesha' == $args['payment_method_code']) || ('pezesha' == $args['payment_method_code'] && 'wallet' == $args['payment_wallet_method_code'])) {
                if (('pezesha' == $args['payment_method_code']) && (!isset($args['payment_wallet_method_code']))) {
                    $pezesha_result = $this->load->controller('api/customer/pezesha/applyloanone', $order_ids);
                }

                if (('pezesha' == $args['payment_method_code']) && isset($args['payment_wallet_method_code']) && 'wallet' == $args['payment_wallet_method_code']) {
                    $pezesha_result = $this->load->controller('api/customer/pezesha/applyloanonehybrid', $order_ids);
                }
                $log->write('pezesha_result');
                $log->write($pezesha_result);
                $log->write('pezesha_result');
                if (isset($pezesha_result) && array_key_exists('data', $pezesha_result) && $pezesha_result['data']['status'] == 200 && $pezesha_result['data']['response_code'] == 0 && !$pezesha_result['data']['error']) {

                    unset($this->session->data['accept_vendor_terms']);
                    unset($this->session->data['delivery_charge_terms']);
                    $this->cart->clear();

                    $log->write('pezesha_result_2');
                    $log->write($pezesha_result);
                    $log->write('pezesha_result_2');

                    $json['status'] = 200;
                    $json['msg'] = 'Order placed Successfully';
                } else if (isset($pezesha_result) && array_key_exists('data', $pezesha_result) && $pezesha_result['data']['status'] != 200 && $pezesha_result['data']['response_code'] != 0 && $pezesha_result['data']['error']) {
                    $log->write('pezesha_result_2');
                    $log->write($pezesha_result);
                    $log->write('pezesha_result_2');

                    $json['status'] = $pezesha_result['data']['status'];
                    $json['msg'] = $pezesha_result['data']['message'] . ' ' . $pezesha_result['message'];
                } else {
                    $json['status'] = 500;
                    $json['msg'] = 'Something went wrong, Please try agian later!';
                }
            } elseif (('cod' == $args['payment_method_code']) || ('cod' == $args['payment_method_code'] && 'wallet' == $args['payment_wallet_method_code'])) {
                if (('cod' == $args['payment_method_code']) && (!isset($args['payment_wallet_method_code']))) {
                    $data['payment'] = $this->load->controller('api/customer/' . $args['payment_method_code'] . '/apiConfirm', $order_ids);
                }

                if (('cod' == $args['payment_method_code']) && isset($args['payment_wallet_method_code']) && 'wallet' == $args['payment_wallet_method_code']) {
                    $data['payment'] = $this->load->controller('api/customer/' . $args['payment_method_code'] . '/apiConfirmHybridPayments', $order_ids);
                }
                $json['status'] = 200;
                $json['msg'] = 'Order placed Successfully';
                unset($this->session->data['accept_vendor_terms']);
                unset($this->session->data['delivery_charge_terms']);

                $this->cart->clear();
            } elseif (('mod' == $args['payment_method_code']) || ('mod' == $args['payment_method_code'] && 'wallet' == $args['payment_wallet_method_code'])) {
                if (('mod' == $args['payment_method_code']) && (!isset($args['payment_wallet_method_code']))) {
                    $data['payment'] = $this->load->controller('api/customer/' . $args['payment_method_code'] . '/apiConfirm', $order_ids);
                }

                if (('mod' == $args['payment_method_code']) && isset($args['payment_wallet_method_code']) && 'wallet' == $args['payment_wallet_method_code']) {
                    $data['payment'] = $this->load->controller('api/customer/' . $args['payment_method_code'] . '/apiConfirmHybridPayments', $order_ids);
                }
                $json['status'] = 200;
                $json['msg'] = 'Order placed Successfully';
                unset($this->session->data['accept_vendor_terms']);
                unset($this->session->data['delivery_charge_terms']);

                $this->cart->clear();
            } elseif ('wallet' == $args['payment_method_code']) {
                if ('wallet' == $args['payment_method_code']) {
                    $data['payment'] = $this->load->controller('api/customer/' . $args['payment_method_code'] . '/apiConfirm', $order_ids);
                }
                $json['status'] = 200;
                $json['msg'] = 'Order placed Successfully';
                unset($this->session->data['accept_vendor_terms']);
                unset($this->session->data['delivery_charge_terms']);

                $this->cart->clear();
            } /* else {
              $data['payment'] = $this->load->controller('payment/' . $args['payment_method_code'] . '/apiConfirm', $order_ids);
              $json['status'] = 200;
              $json['msg'] = 'Order placed Successfully';
              unset($this->session->data['accept_vendor_terms']);
              unset($this->session->data['delivery_charge_terms']);

              $this->cart->clear();
              } */
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

        $log->write('ordernew json');
        $log->write($json);
        $log->write('ordernew json');
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addMpesaCheckOutNew($args = []) {

        $log = new Log('error.log');
        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = '';

        if ($this->validatenewly($args)) {
            $log->write('addMpesaCheckOutNew');
            $log->write($args);
            $log->write('addMpesaCheckOutNew');
            $stores = array_keys($args['stores']);

            $active_Store_exsists = in_array("75", $stores);
            $shipping_added = 0;

            //print_r($stores);
            foreach ($stores as $store_id) {
                $order_data[$store_id] = [];
                $order_data[$store_id]['totals'] = [];

                $total = 0;
                $taxes = $this->cart->getTaxes();
                $taxes_by_store = $this->cart->getTaxesByStore($store_id);
                $log->write('taxes_by_store mobile');
                $log->write($store_id);
                $log->write($taxes_by_store);
                $log->write('taxes_by_store mobile');

                $this->load->model('extension/extension');

                $sort_order = [];

                $results = $this->model_extension_extension->getExtensions('total');

                foreach ($results as $key => $value) {
                    $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
                }
                array_multisort($sort_order, SORT_ASC, $results);

                foreach ($results as $result) {
                    if ($this->config->get($result['code'] . '_status')) {
                        $log->write($result['code']);
                        $this->load->model('total/' . $result['code']);

                        /* $log->write("in multiStoreIndex".$result['code']);
                          $log->write("in loop".$total); */

                        //$this->{'model_total_' . $result['code']}->getApiTotal( $order_data[$store_id]['totals'], $total, $taxes,$store_id ,$args['stores'][$store_id]);

                        if ($result['code'] == 'shipping') {
                            if ($active_Store_exsists == 1) {
                                $this->{'model_total_' . $result['code']}->getTotal($order_data[$store_id]['totals'], $total, $taxes_by_store, $store_id);
                            } else if ($result['code'] == 'shipping' && $active_Store_exsists == 0 && $shipping_added == 0) {
                                $this->{'model_total_' . $result['code']}->getTotal($order_data[$store_id]['totals'], $total, $taxes_by_store, -1);
                            }
                            $shipping_added = 1; //shipping charge added to one of the stores
                        } else {
                            $this->{'model_total_' . $result['code']}->getApiTotal($order_data[$store_id]['totals'], $total, $taxes_by_store, $store_id, $args);
                        }
                    }
                }

                $log->write('addOrder b total end');

                $sort_order = [];

                foreach ($order_data[$store_id]['totals'] as $key => $value) {
                    $sort_order[$key] = $value['sort_order'];
                }

                array_multisort($sort_order, SORT_ASC, $order_data[$store_id]['totals']);

                $this->load->model('sale/order');
                $store_info = $this->model_sale_order->getStoreInfo($store_id);

                $this->load->language('checkout/checkout');
                $order_data[$store_id]['invoice_prefix'] = $this->config->get('config_invoice_prefix');
                $order_data[$store_id]['store_id'] = $store_id;
                $order_data[$store_id]['store_name'] = $store_info['name'];

                $order_data[$store_id]['commission'] = ($store_info['commision'] > 0) ? $store_info['commision'] : $store_info['vendor_commision'];

                $order_data[$store_id]['fixed_commission'] = ($store_info['fixed_commision'] > 0) ? $store_info['fixed_commision'] : $store_info['vendor_fixed_commision'];

                if ($this->request->server['HTTPS']) {
                    $server = $this->config->get('config_ssl');
                } else {
                    $server = $this->config->get('config_url');
                }

                if ($order_data[$store_id]['store_id']) {
                    $order_data[$store_id]['store_url'] = $this->config->get('config_url');
                } else {
                    $order_data[$store_id]['store_url'] = $server;
                }

                if (!trim($order_data[$store_id]['store_url'])) {
                    $order_data[$store_id]['store_url'] = $server;
                }

                if ($this->customer->isLogged()) {
                    $this->load->model('account/customer');

                    $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

                    $order_data[$store_id]['customer_id'] = $this->customer->getId();
                    $order_data[$store_id]['customer_group_id'] = $customer_info['customer_group_id'];
                    $order_data[$store_id]['firstname'] = $customer_info['firstname'];
                    $order_data[$store_id]['lastname'] = $customer_info['lastname'];
                    $order_data[$store_id]['email'] = $customer_info['email'];
                    $order_data[$store_id]['telephone'] = $customer_info['telephone'];
                    $order_data[$store_id]['fax'] = $customer_info['fax'];
                    $order_data[$store_id]['custom_field'] = unserialize($customer_info['custom_field']);
                } elseif (isset($this->session->data['guest'])) {
                    $order_data[$store_id]['customer_id'] = 0;
                    $order_data[$store_id]['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
                    $order_data[$store_id]['firstname'] = $this->session->data['guest']['firstname'];
                    $order_data[$store_id]['lastname'] = $this->session->data['guest']['lastname'];
                    $order_data[$store_id]['email'] = $this->session->data['guest']['email'];
                    $order_data[$store_id]['telephone'] = $this->session->data['guest']['telephone'];
                    $order_data[$store_id]['fax'] = $this->session->data['guest']['fax'];
                    $order_data[$store_id]['custom_field'] = $this->session->data['guest']['custom_field'];
                }

                if (isset($args['payment_method'])) {
                    $order_data[$store_id]['payment_method'] = $args['payment_method'];
                } else {
                    $order_data[$store_id]['payment_method'] = '';
                }

                if (isset($args['mpesa_phonenumber']) && $args['mpesa_phonenumber'] != NULL) {
                    $order_data['mpesa_phonenumber'] = $args['mpesa_phonenumber'];
                } else {
                    $order_data['mpesa_phonenumber'] = $this->customer->getTelephone();
                }

                if (isset($args['payment_method_code'])) {
                    $order_data[$store_id]['payment_code'] = $args['payment_method_code'];

                    $c = $this->getPaymentName($args['payment_method_code']);
                    if (!empty($c)) {
                        $order_data[$store_id]['payment_method'] = $c;
                    }
                } else {
                    $order_data[$store_id]['payment_code'] = '';
                }

                if (isset($args['stores'][$store_id]['shipping_method']) && isset($args['stores'][$store_id]['shipping_code'])) {
                    if (isset($args['stores'][$store_id]['shipping_method'])) {
                        $order_data[$store_id]['shipping_method'] = $args['stores'][$store_id]['shipping_method'];
                    } else {
                        $order_data[$store_id]['shipping_method'] = '';
                    }

                    if (isset($args['stores'][$store_id]['shipping_code'])) {
                        $order_data[$store_id]['shipping_code'] = $args['stores'][$store_id]['shipping_code'];

                        $c = $this->getShippingName($args['stores'][$store_id]['shipping_code'], $store_id);
                        if (!empty($c)) {
                            $order_data[$store_id]['shipping_method'] = $c;
                        }
                    } else {
                        $order_data[$store_id]['shipping_code'] = '';
                    }
                } else {
                    $order_data[$store_id]['shipping_method'] = '';
                    $order_data[$store_id]['shipping_code'] = '';
                }

                if (isset($args['shipping_city_id'])) {
                    $shipping_city_id = $args['shipping_city_id'];
                    $order_data[$store_id]['shipping_city_id'] = $shipping_city_id;
                } else {
                    $order_data[$store_id]['shipping_city_id'] = '';
                }

                if (isset($args['shipping_address_id'])) {
                    $shipping_address_id = $args['shipping_address_id'];
                    $this->load->model('account/address');
                    $shipping_address_data = $this->model_account_address->getAddress($shipping_address_id);

                    $order_data[$store_id]['shipping_address'] = $shipping_address_data['address'];
                    $order_data[$store_id]['shipping_name'] = $shipping_address_data['name'];

                    $order_data[$store_id]['shipping_flat_number'] = $shipping_address_data['flat_number'];
                    $order_data[$store_id]['shipping_landmark'] = $shipping_address_data['landmark'];
                    $order_data[$store_id]['shipping_building_name'] = $shipping_address_data['building_name'];
                    $order_data[$store_id]['shipping_zipcode'] = $shipping_address_data['zipcode'];

                    $order_data[$store_id]['latitude'] = $shipping_address_data['latitude'];
                    $order_data[$store_id]['longitude'] = $shipping_address_data['longitude'];

                    if (isset($args['shipping_contact_no'])) {
                        $shipping_contact_no = $args['shipping_contact_no'];
                        $order_data[$store_id]['shipping_contact_no'] = $shipping_contact_no;
                    } elseif (isset($shipping_address_data['contact_no'])) {
                        $order_data[$store_id]['shipping_contact_no'] = $shipping_address_data['contact_no'];
                    } else {
                        $order_data[$store_id]['shipping_contact_no'] = '';
                    }
                } else {
                    $order_data[$store_id]['shipping_address'] = '';
                    $order_data[$store_id]['shipping_name'] = '';
                    $order_data[$store_id]['shipping_contact_no'] = '';
                    $order_data[$store_id]['shipping_zipcode'] = '';
                    $order_data[$store_id]['shipping_flat_number'] = '';
                    $order_data[$store_id]['shipping_landmark'] = '';
                    $order_data[$store_id]['shipping_building_name'] = '';
                }

                $order_data[$store_id]['products'] = [];

                $this->load->model('assets/product');

                foreach ($args['products'] as $product) {
                    $option_data = [];

                    $vendor_id = $this->model_extension_extension->getVendorId($product['store_id']);

                    $db_product_detail = $this->model_assets_product->getProductForPopupByApi($product['store_id'], $product['product_store_id']);

                    if ($store_id == $product['store_id']) {

                        if (is_null($db_product_detail['special_price']) || !($db_product_detail['special_price'] + 0)) {
                            $db_product_detail['special_price'] = $db_product_detail['price'];
                        }

                        $order_data[$store_id]['products'][] = [
                            'product_store_id' => $product['product_store_id'],
                            'product_id' => isset($db_product_detail['product_id']) ? $db_product_detail['product_id'] : '',
                            'store_product_variation_id' => $product['store_product_variation_id'],
                            'store_id' => $product['store_id'],
                            'vendor_id' => $vendor_id,
                            'name' => $db_product_detail['pd_name'],
                            'unit' => $db_product_detail['unit'],
                            'product_type' => trim($product['product_type']),
                            'product_note' => trim($product['product_note']),
                            'produce_type' => trim($product['produce_type']),
                            'model' => $db_product_detail['model'],
                            'option' => $option_data,
                            'download' => $product['download'],
                            'quantity' => $product['quantity'],
                            'subtract' => $db_product_detail['subtract_quantity'],
                            // 'price' => $db_product_detail['special_price'],
                            'price' => $product['price'], //check
                            // 'total' => ($product['quantity'] * $db_product_detail['special_price']),
                            'total' => ($product['price'] * $product['quantity']),
                            'tax' => $this->tax->getTax($product['price'], $db_product_detail['tax_class_id']),
                            // 'tax' => $this->tax->getTax($db_product_detail['special_price'], $db_product_detail['tax_class_id']),
                            'reward' => $product['reward'],
                        ];
                    }
                }
                $order_data[$store_id]['vouchers'] = [];

                /* if(isset($args['dropoff_notes']) && strlen($args['dropoff_notes']) > 0 ) {
                  $order_data[$store_id]['comment'] = $args['dropoff_notes'];
                  } else {
                  $order_data[$store_id]['comment'] = '';
                  } */

                if (isset($args['stores'][$store_id]['comment']) && strlen($args['stores'][$store_id]['comment']) > 0) {
                    $order_data[$store_id]['comment'] = $args['stores'][$store_id]['comment'];
                } else {
                    $order_data[$store_id]['comment'] = '';
                }

                $order_data[$store_id]['total'] = $total;

                $order_data[$store_id]['affiliate_id'] = 0;
                $order_data[$store_id]['marketing_id'] = 0;
                $order_data[$store_id]['tracking'] = '';
                $order_data[$store_id]['language_id'] = $this->config->get('config_language_id');
                $order_data[$store_id]['currency_id'] = $this->currency->getId();
                $order_data[$store_id]['currency_code'] = $this->currency->getCode();
                $order_data[$store_id]['currency_value'] = $this->currency->getValue($this->currency->getCode());
                $order_data[$store_id]['ip'] = $this->request->server['REMOTE_ADDR'];

                if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
                    $order_data[$store_id]['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
                } elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
                    $order_data[$store_id]['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
                } else {
                    $order_data[$store_id]['forwarded_ip'] = '';
                }

                /* if (isset($args['order_reference_number'])) {
                  $order_data[$store_id]['order_reference_number'] = $args['order_reference_number'];
                  } else {
                  $order_data[$store_id]['order_reference_number'] = '';
                  } */

                if (isset($args['stores'][$store_id]['order_reference_number']) && strlen($args['stores'][$store_id]['order_reference_number']) > 0) {
                    $order_data[$store_id]['order_reference_number'] = $args['stores'][$store_id]['order_reference_number'];
                } else {
                    $order_data[$store_id]['order_reference_number'] = '';
                }

                if (isset($this->request->server['HTTP_USER_AGENT'])) {
                    $order_data[$store_id]['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
                } else {
                    $order_data[$store_id]['user_agent'] = '';
                }

                if (isset($this->request->server['HTTP_USER_AGENT'])) {
                    $order_data[$store_id]['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
                } else {
                    $order_data[$store_id]['user_agent'] = '';
                }

                $order_data[$store_id]['accept_language'] = '';

                $this->load->model('api/checkout');
                if ($store_id == 75) {
                    if (isset($args['stores'][$store_id]['dates'])) {
                        $order_data[$store_id]['delivery_date'] = $args['stores'][$store_id]['dates'];
                    } else {
                        $order_data[$store_id]['delivery_date'] = date('d-m-Y');
                    }

                    //$log->write("shipin code".$order_data[$store_id]['shipping_code']);die;

                    if (isset($order_data[$store_id]['shipping_code']) && 'express.express' == trim($order_data[$store_id]['shipping_code'])) {
                        $order_data[$store_id]['delivery_date'] = date('d-m-Y');

                        $settings = $this->getSettings('express', 0);
                        $timeDiff = $settings['express_how_much_time'];

                        $min = 0;
                        if ($timeDiff) {
                            $i = explode(':', $timeDiff);
                            $min = $min + $i[0] * 60 + $i[1]; //add difference minut to current time
                        }
                        $to = date('h:ia', strtotime('+' . $min . ' minutes', strtotime(date('h:ia'))));

                        $delivery_timeslot = date('h:ia') . ' - ' . $to;

                        $order_data[$store_id]['delivery_timeslot'] = $delivery_timeslot;
                    } else {
                        if (isset($args['stores'][$store_id]['dates'])) {
                            $order_data[$store_id]['delivery_date'] = $args['stores'][$store_id]['dates'];
                        } else {
                            $order_data[$store_id]['delivery_date'] = date('d-m-Y');
                        }

                        if (isset($args['stores'][$store_id]['timeslot'])) {
                            $order_data[$store_id]['delivery_timeslot'] = $args['stores'][$store_id]['timeslot'];
                        } else {
                            $settings = $this->getSettings('express', 0);
                            $timeDiff = $settings['express_how_much_time'];

                            $min = 0;
                            if ($timeDiff) {
                                $i = explode(':', $timeDiff);
                                $min = $min + $i[0] * 60 + $i[1]; //add difference minut to current time
                            }
                            $to = date('h:ia', strtotime('+' . $min . ' minutes', strtotime(date('h:ia'))));

                            $delivery_timeslot = date('h:ia') . ' - ' . $to;

                            $order_data[$store_id]['delivery_timeslot'] = $delivery_timeslot;
                        }
                    }
                }
                if ($store_id != 75) {
                    $other_vendor_delivery_time = $this->load->controller('api/delivery_time/getothervendordeliverytime', $store_id);
                    $log = new Log('error.log');
                    $log->write('other_vendor_delivery_time');
                    $log->write($other_vendor_delivery_time);
                    $log->write('other_vendor_delivery_time');
                    //$other_vendor_delivery_time = $this->getothervendordeliverytime($store_id);

                    if ($other_vendor_delivery_time != null && $other_vendor_delivery_time['selected_time_slot_date'] != null && $other_vendor_delivery_time['selected_time_slot_date'] != '') {
                        $order_data[$store_id]['delivery_date'] = $other_vendor_delivery_time['selected_time_slot_date'];
                        $order_data[$store_id]['delivery_timeslot'] = $other_vendor_delivery_time['selected_time_slot_time'];
                    } else {
                        if (isset($args['stores'][$store_id]['dates'])) {
                            $order_data[$store_id]['delivery_date'] = $args['stores'][$store_id]['dates'];
                        } else {
                            $order_data[$store_id]['delivery_date'] = date('d-m-Y');
                        }

                        if (isset($args['stores'][$store_id]['timeslot'])) {
                            $order_data[$store_id]['delivery_timeslot'] = $args['stores'][$store_id]['timeslot'];
                        }
                    }
                }
            }
            $order_data[$store_id]['login_latitude'] = $args['login_latitude'];
            $order_data[$store_id]['login_longitude'] = $args['login_longitude'];
            $order_data[$store_id]['login_mode'] = $args['login_mode'];

            $log->write('addMultiOrder call');

            /* $order_ids = [];

              $order_ids = $this->model_api_checkout->addMultiOrder($order_data); */

            $tot = 0;

            foreach ($stores as $store_id) {
                $data['totals'] = [];
                foreach ($order_data[$store_id]['totals'] as $total) {
                    $data['totals'][] = [
                        'title' => $total['title'],
                        'text' => $this->currency->format($total['value']),
                    ];
                    $tot += $total['value'];
                }
            }

            $transactionData = [
                'no_of_products' => count($args['products']),
                //'total' =>$tot,
                'total' => $args['total'],
            ];

            //$log->write($transactionData);
            //$this->model_api_checkout->apiAddTransaction($transactionData, $order_ids);

            if (('mpesa' == $args['payment_method_code']) || ('mpesa' == $args['payment_method_code'] && 'wallet' == $args['payment_wallet_method_code'])) {
                //save for refrence id correct order id
                $mpesa_result = NULL;
                if (('mpesa' == $args['payment_method_code']) && (!isset($args['payment_wallet_method_code']))) {
                    $mpesa_result = $this->SendPaymentRequestToMpesa($this->cart->getTotalWithShipping(), $args['mpesa_mobile_number'], base64_encode($this->customer->getId() . '_' . $this->cart->getTotalWithShipping() . '_' . date("Y-m-d h:i:s")));
                }

                if (('mpesa' == $args['payment_method_code']) && isset($args['payment_wallet_method_code']) && 'wallet' == $args['payment_wallet_method_code']) {
                    $mpesa_result = $this->SendPaymentRequestToMpesa($this->cart->getTotalWithShipping(), $args['mpesa_mobile_number'], base64_encode($this->customer->getId() . '_' . $this->cart->getTotalWithShipping() . '_' . date("Y-m-d h:i:s")));
                }
                $log->write('mpesa_result');
                $log->write($mpesa_result);
                $log->write('mpesa_result');
                if (isset($mpesa_result) && isset($mpesa_result['ResponseCode']) && $mpesa_result['ResponseCode'] == 0) {
                    $json['status'] = 200;
                    $json['message'] = $mpesa_result['ResponseDescription'];
                    $json['data']['merchant_request_id'] = $mpesa_result['MerchantRequestID'];
                    $json['data']['checkout_request_id'] = $mpesa_result['CheckoutRequestID'];
                } elseif (isset($mpesa_result) && isset($mpesa_result['errorCode']) && $mpesa_result['errorCode'] > 0) {
                    $json['status'] = 400;
                    $json['message'] = $mpesa_result['errorMessage'];
                }
            }
        } else {
            $json['status'] = 10014;

            foreach ($this->error as $key => $value) {
                $json['message'][] = ['type' => $key, 'body' => $value];
            }

            http_response_code(400);
        }

        $log->write('ordernew json');
        $log->write($json);
        $log->write('ordernew json');
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function SendPaymentRequestToMpesa($amount, $mpesa_mobile_number, $order_reference_number) {

        $log = new Log('error.log');

        $live = 'true';
        $mpesa = new \Safaricom\Mpesa\Mpesa($this->config->get('mpesa_customer_key'), $this->config->get('mpesa_customer_secret'), $this->config->get('mpesa_environment'), $live);
        $sta = false;

        $log->write('addMpesaCheckOuts');
        $log->write($sta);
        if (!$sta) {
            $PartyA = $this->config->get('config_telephone_code') . '' . $mpesa_mobile_number;

            $BusinessShortCode = $this->config->get('mpesa_business_short_code');
            $LipaNaMpesaPasskey = $this->config->get('mpesa_lipanampesapasskey');
            $TransactionType = 'CustomerPayBillOnline'; //'CustomerBuyGoodsOnline';    
            $CallBackURL = $this->url->link('deliversystem/deliversystem/mpesaMobileCallback', '', 'SSL');
            $Amount = $amount;
            $Amount = 1;
            $PartyB = $this->config->get('mpesa_business_short_code');
            $PhoneNumber = $this->config->get('config_telephone_code') . '' . $mpesa_mobile_number;
            $AccountReference = "#" . $order_reference_number; //$this->config->get('config_name');
            $TransactionDesc = "#" . $order_reference_number;
            $Remarks = 'PAYMENT';
            $log->write($BusinessShortCode . 'x' . $LipaNaMpesaPasskey . 'x' . $TransactionType . 'amount' . $Amount . 'x' . $PartyA . 'x' . $PartyB . 'x' . $PhoneNumber . 'x' . $CallBackURL . 'x' . $AccountReference . 'x' . $TransactionDesc . 'x' . $Remarks);
            $stkPushSimulation = $mpesa->STKPushSimulation($BusinessShortCode, $LipaNaMpesaPasskey, $TransactionType, $Amount, $PartyA, $PartyB, $PhoneNumber, $CallBackURL, $AccountReference, $TransactionDesc, $Remarks);
            $log->write('STKPushSimulation');
            $log->write($stkPushSimulation);
            $stkPushSimulation = json_decode($stkPushSimulation);
            $stkPushSimulation = json_decode(json_encode($stkPushSimulation), true);
            //$json['response'] = $stkPushSimulation;
            return $stkPushSimulation;
        }
    }

}
