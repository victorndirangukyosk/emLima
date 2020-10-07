<?php

class ControllerApiCustomerOrder extends Controller
{
    private $error = [];

    public function addRate($args = [])
    {
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

    public function addOrder($args = [])
    {
        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $log = new Log('error.log');
        //$log->write($args);die;
        //$log->write('Log 3.5');
        //echo "<pre>";print_r($args);die;
        $data['kondutoStatus'] = $this->config->get('config_konduto_status');
        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        if ($this->validate($args)) {
            $log->write('addOrder');
            $stores = array_keys($args['stores']);

            //print_r($stores);
            foreach ($stores as $store_id) {
                $order_data[$store_id] = [];
                $order_data[$store_id]['totals'] = [];

                $total = 0;
                $taxes = $this->cart->getTaxes();

                $this->load->model('extension/extension');

                $sort_order = [];

                $results = $this->model_extension_extension->getExtensions('total');

                foreach ($results as $key => $value) {
                    $sort_order[$key] = $this->config->get($value['code'].'_sort_order');
                }
                array_multisort($sort_order, SORT_ASC, $results);

                foreach ($results as $result) {
                    if ($this->config->get($result['code'].'_status')) {
                        $log->write($result['code']);
                        $this->load->model('total/'.$result['code']);

                        /*$log->write("in multiStoreIndex".$result['code']);
                        $log->write("in loop".$total);*/

                        //$this->{'model_total_' . $result['code']}->getApiTotal( $order_data[$store_id]['totals'], $total, $taxes,$store_id ,$args['stores'][$store_id]);
                        $this->{'model_total_'.$result['code']}->getApiTotal($order_data[$store_id]['totals'], $total, $taxes, $store_id, $args);
                    }
                }

                $log->write('addOrder b total end');

                $sort_order = [];

                foreach ($order_data[$store_id]['totals'] as $key => $value) {
                    $sort_order[$key] = $value['sort_order'];
                }

                array_multisort($sort_order, SORT_ASC, $order_data[$store_id]['totals']);

                //$log->write($order_data[$store_id]['totals']);die;
                //echo "<pre>";print_r($order_data[$store_id]['totals']);die;

                $this->db->select('store.store_id,store.name,store.min_order_amount,store.city_id,store.commision,store.fixed_commision,user.commision as vendor_commision ,user.fixed_commision as vendor_fixed_commision', false);
                $this->db->join('user', 'user.user_id = store.vendor_id', 'left');
                $this->db->where('store.store_id', $store_id);
                $this->db->where('store.status', 1);
                $store_info = $this->db->get('store')->row;

                $this->load->language('checkout/checkout');
                $order_data[$store_id]['invoice_prefix'] = $this->config->get('config_invoice_prefix');
                $order_data[$store_id]['store_id'] = $store_id;
                $order_data[$store_id]['store_name'] = $store_info['name'];

                $order_data[$store_id]['commission'] = ($store_info['commision'] > 0) ? $store_info['commision'] : $store_info['vendor_commision'];

                $order_data[$store_id]['fixed_commission'] = ($store_info['fixed_commision'] > 0) ? $store_info['fixed_commision'] : $store_info['vendor_fixed_commision'];

                //echo $store_info['vendor_commision'];die;
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

                //for future user

                if (isset($args['payment_method'])) {
                    $order_data[$store_id]['payment_method'] = $args['payment_method'];
                } else {
                    $order_data[$store_id]['payment_method'] = '';
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
                //$log->write('Log 3.4');

                $this->load->model('assets/product');

                foreach ($args['products'] as $product) {
                    $option_data = [];

                    $vendor_id = $this->model_extension_extension->getVendorId($product['store_id']);

                    $db_product_detail = $this->model_assets_product->getProductForPopupByApi($product['store_id'], $product['product_store_id']);

                    //$log->write($db_product_detail);

                    if ($store_id == $product['store_id']) {
                        /*$b = str_replace( ',', '', $product['price'] );

                        if( is_numeric( $b ) ) {
                            $product['price'] = $b;
                        }*/

                        if (is_null($db_product_detail['special_price']) || !($db_product_detail['special_price'] + 0)) {
                            //$db_product_detail['special_price'] = 0;
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
                            'price' => $db_product_detail['special_price'],
                            //'price' => $product['price'],
                            'total' => ($product['quantity'] * $db_product_detail['special_price']),
                            //'total' => ($product['price'] * $product['quantity']),
                            //'tax' => $this->tax->getTax( $product['price'], $db_product_detail['tax_class_id'] ),
                            'tax' => $this->tax->getTax($db_product_detail['special_price'], $db_product_detail['tax_class_id']),
                               'reward' => $product['reward'],
                        ];
                    }
                }
                $order_data[$store_id]['vouchers'] = [];

                /*if(isset($args['dropoff_notes']) && strlen($args['dropoff_notes']) > 0 ) {
                    $order_data[$store_id]['comment'] = $args['dropoff_notes'];
                } else {
                    $order_data[$store_id]['comment'] = '';
                }*/

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

                if (isset($args['order_reference_number'])) {
                    $order_data[$store_id]['order_reference_number'] = $args['order_reference_number'];
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
                    $to = date('h:ia', strtotime('+'.$min.' minutes', strtotime(date('h:ia'))));

                    $delivery_timeslot = date('h:ia').' - '.$to;

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
                        $to = date('h:ia', strtotime('+'.$min.' minutes', strtotime(date('h:ia'))));

                        $delivery_timeslot = date('h:ia').' - '.$to;

                        $order_data[$store_id]['delivery_timeslot'] = $delivery_timeslot;
                    }
                }
            }

            $log->write('addMultiOrder call');
            //echo "<pre>";print_r($order_data);die;
            //$log->write($order_data);

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

            if (isset($args['stripe_source']) && 'stripe' == $args['payment_method_code']) {
                $this->load->model('api/payment');

                $payment_response = $this->model_api_payment->stripePayment($order_ids, $args['stripe_source']);

                if (!$payment_response['processed']) {
                    $json['status'] = 10040;

                    $json['message'][] = ['type' => '', 'body' => 'Payment failed'];
                }
            } elseif ('mpesa' == $args['payment_method_code']) {
                //save for refrence id correct order id

                if (isset($args['mpesa_refrence_id'])) {
                    $this->load->model('payment/mpesa');
                    $this->load->model('checkout/order');

                    foreach ($order_ids as $order_id) {
                        $this->model_payment_mpesa->updateOrderIdMpesaOrder($order_id, $args['mpesa_refrence_id']);

                        $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('mpesa_order_status_id'));
                    }
                }
            } else {
                $data['payment'] = $this->load->controller('payment/'.$args['payment_method_code'].'/apiConfirm', $order_ids);
                $json['status'] = 200;
                $json['msg'] = 'Order placed Successfully';
            }

            foreach ($order_ids as $key => $value) {
                $this->createDeliveryRequest($value);
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

    public function getPaymentName($code)
    {
        $total = 9999999999999999999;
        $method_data = [];

        $this->load->model('extension/extension');

        $results = $this->model_extension_extension->getExtensions('payment');

        //echo "<pre>";print_r($results);die;
        $recurring = $this->cart->hasRecurringProducts();

        foreach ($results as $result) {
            if ($this->config->get($result['code'].'_status')) {
                $this->load->model('payment/'.$result['code']);

                $method = $this->{'model_payment_'.$result['code']}->getMethod($total);

                if ($method) {
                    if ($recurring) {
                        if (method_exists($this->{'model_payment_'.$result['code']}, 'recurringPayments') && $this->{'model_payment_'.$result['code']}->recurringPayments()) {
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

    public function getShippingName($code, $store_id)
    {
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
            if ($this->config->get($result['code'].'_status')) {
                if ('normal' == $result['code']) {
                    $this->load->model('shipping/'.$result['code']);
                    $quote = $this->{'model_shipping_'.$result['code']}->getApiQuote($cost, $store_info['name'], $subtotal, $subtotal);
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
                    $quote = $this->{'model_shipping_'.$result['code']}->getApiQuote($cost, $store_info['name'], $subtotal, $subtotal);

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
                        $quote = $this->{'model_shipping_'.$result['code']}->getApiQuote($cost, $store_info['name'], $subtotal, $subtotal);
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
                        $quote = $this->{'model_shipping_'.$result['code']}->getApiQuote($cost, $store_info['name'], $subtotal, $subtotal);
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
                    $quote = $this->{'model_shipping_'.$result['code']}->getApiQuote($cost, $store_info['name'], $subtotal, $subtotal);
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

    public function addMissingOrder($args = [])
    {
        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $log = new Log('error.log');
        $log->write($args);
        //echo "<pre>";print_r($args);die;
        $data['kondutoStatus'] = $this->config->get('config_konduto_status');
        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        if ($this->validate($args)) {
            $log->write('Log 3');
            $stores = array_keys($args['stores']);

            //print_r($stores);
            foreach ($stores as $store_id) {
                $order_data[$store_id] = [];
                $order_data[$store_id]['totals'] = [];

                $total = 0;
                $taxes = $this->cart->getTaxes();

                $this->load->model('extension/extension');

                $sort_order = [];

                $results = $this->model_extension_extension->getExtensions('total');

                foreach ($results as $key => $value) {
                    $sort_order[$key] = $this->config->get($value['code'].'_sort_order');
                }
                array_multisort($sort_order, SORT_ASC, $results);

                foreach ($results as $result) {
                    if ($this->config->get($result['code'].'_status')) {
                        $log->write($result['code']);
                        $this->load->model('total/'.$result['code']);

                        /*$log->write("in multiStoreIndex".$result['code']);
                        $log->write("in loop".$total);*/

                        //$this->{'model_total_' . $result['code']}->getApiTotal( $order_data[$store_id]['totals'], $total, $taxes,$store_id ,$args['stores'][$store_id]);
                        $this->{'model_total_'.$result['code']}->getApiTotal($order_data[$store_id]['totals'], $total, $taxes, $store_id, $args);
                    }
                }

                $log->write('addOrder b total end');

                $sort_order = [];

                foreach ($order_data[$store_id]['totals'] as $key => $value) {
                    $sort_order[$key] = $value['sort_order'];
                }

                array_multisort($sort_order, SORT_ASC, $order_data[$store_id]['totals']);

                //$log->write($order_data[$store_id]['totals']);die;
                //echo "<pre>";print_r($order_data[$store_id]['totals']);die;

                $this->db->select('store.store_id,store.name,store.min_order_amount,store.city_id,store.commision,store.fixed_commision,user.commision as vendor_commision ,user.fixed_commision as vendor_fixed_commision', false);
                $this->db->join('user', 'user.user_id = store.vendor_id', 'left');
                $this->db->where('store.store_id', $store_id);
                $this->db->where('store.status', 1);
                $store_info = $this->db->get('store')->row;

                $this->load->language('checkout/checkout');
                $order_data[$store_id]['invoice_prefix'] = $this->config->get('config_invoice_prefix');
                $order_data[$store_id]['store_id'] = $store_id;
                $order_data[$store_id]['store_name'] = $store_info['name'];

                $order_data[$store_id]['commission'] = ($store_info['commision'] > 0) ? $store_info['commision'] : $store_info['vendor_commision'];

                $order_data[$store_id]['fixed_commission'] = ($store_info['fixed_commision'] > 0) ? $store_info['fixed_commision'] : $store_info['vendor_fixed_commision'];

                //echo $store_info['vendor_commision'];die;
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

                //for future user

                if (isset($args['payment_method'])) {
                    $order_data[$store_id]['payment_method'] = $args['payment_method'];
                } else {
                    $order_data[$store_id]['payment_method'] = '';
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
                //$log->write('Log 3.4');

                $this->load->model('assets/product');

                foreach ($args['products'] as $product) {
                    $option_data = [];

                    $vendor_id = $this->model_extension_extension->getVendorId($product['store_id']);

                    $db_product_detail = $this->model_assets_product->getProductForPopupByApi($product['store_id'], $product['product_store_id']);

                    //$log->write($db_product_detail);

                    if ($store_id == $product['store_id']) {
                        /*$b = str_replace( ',', '', $product['price'] );

                        if( is_numeric( $b ) ) {
                            $product['price'] = $b;
                        }*/

                        if (is_null($db_product_detail['special_price']) || !($db_product_detail['special_price'] + 0)) {
                            //$db_product_detail['special_price'] = 0;
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
                            'price' => $db_product_detail['special_price'],
                            //'price' => $product['price'],
                            'total' => ($product['quantity'] * $db_product_detail['special_price']),
                            //'total' => ($product['price'] * $product['quantity']),
                            //'tax' => $this->tax->getTax( $product['price'], $db_product_detail['tax_class_id'] ),
                            'tax' => $this->tax->getTax($db_product_detail['special_price'], $db_product_detail['tax_class_id']),
                               'reward' => $product['reward'],
                        ];
                    }
                }
                $order_data[$store_id]['vouchers'] = [];

                /*if(isset($args['dropoff_notes']) && strlen($args['dropoff_notes']) > 0 ) {
                    $order_data[$store_id]['comment'] = $args['dropoff_notes'];
                } else {
                    $order_data[$store_id]['comment'] = '';
                }*/

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

                if (isset($args['order_reference_number'])) {
                    $order_data[$store_id]['order_reference_number'] = $args['order_reference_number'];
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
                    $to = date('h:ia', strtotime('+'.$min.' minutes', strtotime(date('h:ia'))));

                    $delivery_timeslot = date('h:ia').' - '.$to;

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
                        $to = date('h:ia', strtotime('+'.$min.' minutes', strtotime(date('h:ia'))));

                        $delivery_timeslot = date('h:ia').' - '.$to;

                        $order_data[$store_id]['delivery_timeslot'] = $delivery_timeslot;
                    }
                }
            }

            $log->write('addMultiOrder call');
            //echo "<pre>";print_r($order_data);die;
            //$log->write($order_data);

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
            $json['data']['reference'] ='';
            $json['data']['Order_Ids'] = $order_ids;
            if($order_ids &&  $order_data[$store_id]['payment_code']=='pesapal')
            {
            $reference = $order_ids[0] . '-' . time() . '-' . $customer_info['customer_id']; //unique order id of the transaction, generated by merchant
            $json['data']['reference'] = $reference;
            }


        /*if(isset($args['stripe_source']) && $args['payment_method_code'] == 'stripe') {
            $this->load->model('api/payment');

            $payment_response = $this->model_api_payment->stripePayment($order_ids,$args['stripe_source']);//

            if(!$payment_response['processed']) {
                $json['status'] = 10040;

                $json['message'][] = ['type' =>  'Payment failed' , 'body' =>  'Payment failed' ];
            }

        } elseif ($args['payment_method_code'] == 'mpesa') {

            //save for refrence id correct order id


            if(isset($args['mpesa_refrence_id'])) {

                $this->load->model('payment/mpesa');
                $this->load->model('checkout/order');

                foreach ($order_ids as $order_id) {
                    $this->model_payment_mpesa->updateOrderIdMpesaOrder($order_id,$args['mpesa_refrence_id']);

                    $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('mpesa_order_status_id'));
                }
            }

        } else {

            $data['payment'] = $this->load->controller( 'payment/' . $args['payment_method_code']."/apiConfirm",$order_ids );
        }*/

            /*foreach ($order_ids as $key => $value) {
                $this->createDeliveryRequest($value);
            }*/
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

    public function getOrders($args = [])
    {
        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');

        $this->load->language('account/order');

        $this->document->setTitle($this->language->get('heading_title'));

        $url = '';

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
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
                $shipping_address['address'] = $result['shipping_building_name'].', '.$result['shipping_flat_number'];
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
                'name' => $result['firstname'].' '.$result['lastname'],
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
                'href' => $this->url->link('account/order/info', 'order_id='.$result['order_id'], 'SSL'),
                'real_href' => $this->url->link('account/order/realinfo', 'order_id='.$result['order_id'], 'SSL'),
                'customer_parent_info'=> $customer_parent_info,
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
                'edit_order' => 15 == $result['order_status_id'] && (empty($_SESSION['parent']) || $order_appoval_access) ? $this->url->link('account/order/edit_order', 'order_id=' . $result['order_id'], 'SSL') : '',
                'order_company' => isset($customer_info) && null != $customer_info['company_name'] ? $customer_info['company_name'] : null,
            ];
        }

        $data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($order_total - 10)) ? $order_total : ((($page - 1) * 10) + 10), $order_total, ceil($order_total / 10));

        $json['data'] = $data;

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getOrder()
    {
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
            //echo "<pre>";print_r($order_info);die;
            if ($order_info) {
                $data['cashbackAmount'] = $this->currency->format(0);

                $coupon_history_data = $this->model_account_order->getCashbackAmount($order_id);

                if (count($coupon_history_data) > 0) {
                    $data['cashbackAmount'] = $this->currency->format((-1 * $coupon_history_data['amount']));
                }

                $url = '';

                if (isset($this->request->get['page'])) {
                    $url .= '&page='.$this->request->get['page'];
                }

                $data['delivered'] = false;
                $data['coupon_cashback'] = false;

                if ($order_info['invoice_no']) {
                    $data['invoice_no'] = $order_info['invoice_prefix'].$order_info['invoice_no'];
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
                $data['date_added'] = date($this->language->get('date_format'), strtotime($order_info['order_date'])).' '.date($this->language->get('time_format'), strtotime($order_info['order_date']));
                // 'date_added' => date($this->language->get('date_format'), strtotime($result['date_added'])),
                // 'time_added' => date($this->language->get('time_format'), strtotime($result['date_added'])),

                $data['payment_method'] = ucwords($order_info['payment_method']);
                $data['payment_code'] = $order_info['payment_code'];

                $data['order_rating'] = $order_info['rating'];
                $data['shipping_name'] = $order_info['shipping_name'];
                $data['shipping_contact_no'] = $order_info['shipping_contact_no'];

                $data['shipping_address'] = $order_info['shipping_flat_number'].', '.$order_info['shipping_building_name'];

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

                //echo "<pre>";print_r($app_status_resp);die;
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
                $data['shopper_link'] = $this->config->get('config_shopper_link').'/storage/';

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
                                $data['delivery_data']->driver->driver_profile->drivers_photo = $this->config->get('config_shopper_link').'/storage/'.$data['delivery_data']->driver->driver_profile->drivers_photo;
                            }

                            if (isset($data['delivery_data']->driver->profile->drivers_photo)) {
                                $data['delivery_data']->driver->profile->drivers_photo = $this->config->get('config_shopper_link').'/storage/'.$data['delivery_data']->driver->profile->drivers_photo;
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
                            'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20).'..' : $value),
                        ];
                    }

                    $product_info = $this->model_assets_product->getDetailproduct($edit_product['product_id']);

                    if ($product_info) {
                        $reorder = $this->url->link('account/order/reorder', 'order_id='.$order_id.'&order_product_id='.$edit_product['order_product_id'], 'SSL');
                    } else {
                        $reorder = '';
                    }

                    $this->load->model('tool/image');

                    if (file_exists(DIR_IMAGE.$edit_product['image'])) {
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
                        'return' => $this->url->link('account/return/add', 'order_id='.$order_info['order_id'].'&product_id='.$edit_product['product_id'], 'SSL'),
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
                            'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20).'..' : $value),
                        ];
                    }

                    $product_info = $this->model_assets_product->getDetailproduct($product['product_id']);

                    if ($product_info) {
                        $reorder = $this->url->link('account/order/reorder', 'order_id='.$order_id.'&order_product_id='.$product['order_product_id'], 'SSL');
                    } else {
                        $reorder = '';
                    }

                    $this->load->model('tool/image');

                    if (file_exists(DIR_IMAGE.$product['image'])) {
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
                        'return' => $this->url->link('account/return/add', 'order_id='.$order_info['order_id'].'&product_id='.$product['product_id'], 'SSL'),
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
                            $total['code'] => $val, ];

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

                if ('cod' != $order_info['payment_code']) {
                    $payment_text = sprintf($this->language->get('text_online_paid'), $data['total']);
                }

                $data['payment_text'] = $payment_text;

                $data['comment'] = nl2br($order_info['comment']);

                $data['total_quantity'] = 0;
                $data['customer_parent_info'] = $this->model_account_customer->getCustomerParentEmail($order_info['customer_id']);


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

    public function addOrderStatusByReferenceId()
    {
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

    public function addOrdercancel()
    {
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

    public function getSettings($code, $store_id = 0)
    {
        $this->load->model('setting/setting');

        return $this->model_setting_setting->getSetting($code, $store_id);
    }

    public function addValidateOrderDetail($args = [])
    {
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

            $log->write('count'.$count);
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

                        $store_open_hours = $this->model_tool_image->getStoreOpenHours($store_id, date('w'));

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
                        $response = $this->load->controller('api/customer/checkout/getApiDeliveryTimeslot', $args['stores'][$store_id]);

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

    private function timeIsBetween($from, $to, $time, $time_diff = false)
    {
        //echo "time";print_r($from.$to.$time.$time_diff);die;
        $log = new Log('error.log');
        $log->write('time diff');
        $log->write($from.$to.$time.$time_diff);

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

        /* from calc*/

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

        /*from calc end*/

        //echo "<pre>";print_r($min."cer".$to_min."from_min".$from_min);die;

        $log->write($min);
        $log->write($to_min);
        if ($from_min <= $min && $min <= $to_min) {
            return true;
        } else {
            return 0;
        }
    }

    protected function validate($args)
    {
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

        if ((!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $this->response->redirect($this->url->link('checkout/cart'));
        }

        //echo "<pre>";print_r($this->error);die;
        return !$this->error;
    }

    public function addMaxOfProduct($args = [])
    {
        //echo "<pre>";print_r("Ce");die;
        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];
        $json['valid_cart'] = false;

        $json['tax'] = 0;

        $log = new Log('error.log');
        $log->write($args['products']);
        //echo "<pre>";print_r($args['products']);die;
        $this->load->language('api/general');

        $data['cart_products_with_stock_status'] = [];

        $valid_cart = true;
        $store_id = false;

        if (isset($args['products']) && count($args['products']) > 0) {
            foreach ($args['products'] as $product) {
                $store_id = $product['store_id'];

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
                    // new code

                    $s_price = 0;
                    $o_price = 0;

                    if (!$this->config->get('config_inclusiv_tax')) {
                        //get price html
                        if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                            $product['price'] = $this->tax->calculate($product_query->row['price'], $product_query->row['tax_class_id'], $this->config->get('config_tax'));

                            $o_price = $this->tax->calculate($product['price'], $product_query->row['tax_class_id'], $this->config->get('config_tax'));
                        } else {
                            $product['price'] = false;
                        }

                        if ((float) $product_query->row['special_price']) {
                            $product['special_price'] = $this->tax->calculate($product_query->row['special_price'], $product_query->row['tax_class_id'], $this->config->get('config_tax'));

                            $s_price = $this->tax->calculate($product['special_price'], $product_query->row['tax_class_id'], $this->config->get('config_tax'));
                        } else {
                            $product['special_price'] = false;
                        }
                    } else {
                        $s_price = $product_query->row['special_price'];
                        $o_price = $product_query->row['price'];

                        if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                            //$product_query->row['price'] = $product_query->row['price'];
                            $product['price'] = $this->currency->formatWithoutCurrency($product_query->row['price']);
                        } else {
                            $product['price'] = $product_query->row['price'];
                        }

                        if ((float) $product_query->row['special_price']) {
                            //$product_query->row['special_price'] = $product_query->row['special_price'];
                            $product['special_price'] = $this->currency->formatWithoutCurrency($product_query->row['special_price']);
                        } else {
                            $product['special_price'] = $product_query->row['special_price'];
                        }
                    }

                    $percent_off = null;
                    if (isset($s_price) && isset($o_price) && 0 != $o_price && 0 != $s_price) {
                        $percent_off = (($o_price - $s_price) / $o_price) * 100;
                    }

                    if (is_null($product_query->row['special_price']) || !($product_query->row['special_price'] + 0)) {
                        $product['special_price'] = $product_query->row['price'];
                    }

                    $product['percent_off'] = number_format($percent_off, 0);

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

    public function addValidateOrder($args = [])
    {
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

                /*if(isset($args['stores'])) {


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
                }*/

                $product['valid_cart_min'] = true;
                if (isset($args['stores']) && isset($args['stores'][$store_id]) && false) {
                    $this->load->model('account/address');

                    $store_info = $this->model_account_address->getStoreData($store_id);

                    //echo "<pre>";print_r($store_info);die;
                    if ($store_info['min_order_amount'] > (int) $args['stores'][$store_id]['store_total']) {
                        $product['valid_cart_min'] = false;
                        $valid_cart = false;

                        $json['status'] = 10100;
                    }
                }

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

                    $log->write('quantity_available'.$quantity_available.'p_quantity_available'.$product['quantity']);
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
                $product['number_of_stock_available'] = $quantity_available;

                $data['cart_products_with_stock_status'][] = $product;
            }

            $json['valid_cart'] = $valid_cart;
            $json['data'] = $data['cart_products_with_stock_status'];

            /* calculate tax start */

            $taxes = $this->cart->getTaxesByApi($args);

            /*if($store_id) {
                $this->session->data['config_store_id'] = $store_id;
            }*/

            $json['tax'] = $taxes;

            /*end*/

            $log->write($json);
        } else {
            $json['status'] = 10025;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_no_products')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function createDeliveryRequest($order_id)
    {
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
                    $cmp = $method.'.'.$method;

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

        $log->write('deliverSystemStatus'.$deliverSystemStatus.'deliverSystemStatusForShipping'.$deliverSystemStatusForShipping);

        if ($deliverSystemStatus && $deliverSystemStatusForShipping) {
            $log->write('deliverSystemStatus if');
            //$this->model_checkout_success->createDeliveryRequest($order_id);

            $this->load->controller('checkout/success'.'/createDeliveryRequest', $order_id);
        } else {
            $log->write('deliverSystemStatus else');
        }
    }
}
