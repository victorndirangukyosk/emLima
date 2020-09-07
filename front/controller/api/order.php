<?php

class ControllerApiOrder extends Controller
{
    public function add()
    {
        $this->load->language('api/order');

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            // Payment Method
            if (empty($this->session->data['payment_methods'])) {
                $json['error'] = 'Payment method required!';
            } elseif (!isset($this->request->post['payment_method'])) {
                $json['error'] = 'Payment method required!';
            } elseif (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
                $json['error'] = 'Payment method required!';
            }

            if (!$json) {
                $this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];
            }

            // Shipping Method
            if (empty($this->session->data['shipping_methods'])) {
                $json['error'] = 'Shipping method required!';
            }

            // Customer
            if (!isset($this->session->data['customer'])) {
                $json['error'] = $this->language->get('error_customer');
            }

            // Payment Method
            if (!isset($this->session->data['payment_method'])) {
                $json['error'] = $this->language->get('error_payment_method');
            }

            // Shipping
            if ($this->cart->hasShipping()) {
                // Shipping Address
                if (!isset($this->session->data['shipping_address'])) {
                    $json['error'] = $this->language->get('error_shipping_address');
                }

                // Shipping Method
                if (!isset($this->request->post['shipping_method'])) {
                    $json['error'] = $this->language->get('error_shipping_method');
                }
            } else {
                unset($this->session->data['shipping_address']);
                unset($this->session->data['shipping_method']);
                unset($this->session->data['shipping_methods']);
            }

            // Cart
            if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
                // $json['error'] = $this->language->get('error_stock');
            }

            // Validate minimum quantity requirements.
            $products = $this->cart->getProducts();

            foreach ($products as $product) {
                $product_total = 0;

                foreach ($products as $product_2) {
                    if ($product_2['product_store_id'] == $product['product_store_id']) {
                        $product_total += $product_2['quantity'];
                    }
                }

                /*if ($product['minimum'] > $product_total) {
                    $json['error'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);

                    break;
                }*/
            }

            if (!$json) {
                $stores = $this->cart->getStores();

                $order_data = [];

                foreach ($stores as $store_id) {
                    $order_data[$store_id] = [];
                    $order_data[$store_id]['totals'] = [];

                    $total = 0;
                    //$taxes = $this->cart->getTaxes();

                    $this->load->model('extension/extension');

                    $sort_order = [];

                    $results = $this->model_extension_extension->getExtensions('total');

                    foreach ($results as $key => $value) {
                        $sort_order[$key] = $this->config->get($value['code'].'_sort_order');
                    }
                    array_multisort($sort_order, SORT_ASC, $results);

                    foreach ($results as $result) {
                        if ($this->config->get($result['code'].'_status')) {
                            $this->load->model('total/'.$result['code']);
                            $this->{'model_total_'.$result['code']}->getTotal($order_data[$store_id]['totals'], $total, $taxes, $store_id);
                        }
                    }
                    //echo "<pre>";
                    //print_r($order_data[$store_id]['totals']);

                    $sort_order = [];

                    foreach ($order_data[$store_id]['totals'] as $key => $value) {
                        $sort_order[$key] = $value['sort_order'];
                    }

                    array_multisort($sort_order, SORT_ASC, $order_data[$store_id]['totals']);

                    $store_info = $this->model_extension_extension->getStoreData($store_id);

                    $this->load->language('checkout/checkout');
                    $order_data[$store_id]['invoice_prefix'] = $this->config->get('config_invoice_prefix');
                    $order_data[$store_id]['store_id'] = $store_id;
                    $order_data[$store_id]['store_name'] = $store_info['name'];
                    $order_data[$store_id]['commission'] = $store_info['commision'];

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
                    $order_data[$store_id]['comment'] = '';

                    if (isset($this->session->data['payment_method']['title'])) {
                        $order_data[$store_id]['payment_method'] = $this->session->data['payment_method']['title'];
                    } else {
                        $order_data[$store_id]['payment_method'] = '';
                    }

                    if (isset($this->session->data['payment_method']['code'])) {
                        $order_data[$store_id]['payment_code'] = $this->session->data['payment_method']['code'];
                    } else {
                        $order_data[$store_id]['payment_code'] = '';
                    }

                    if ($this->cart->hasShipping()) {
                        if (isset($this->session->data['shipping_method'][$store_id]['shipping_method']['title'])) {
                            $order_data[$store_id]['shipping_method'] = $this->session->data['shipping_method'][$store_id]['shipping_method']['title'];
                        } else {
                            $order_data[$store_id]['shipping_method'] = '';
                        }

                        if (isset($this->session->data['shipping_method'][$store_id]['shipping_method']['code'])) {
                            $order_data[$store_id]['shipping_code'] = $this->session->data['shipping_method'][$store_id]['shipping_method']['code'];
                        } else {
                            $order_data[$store_id]['shipping_code'] = '';
                        }
                    } else {
                        $order_data[$store_id]['shipping_method'] = '';
                        $order_data[$store_id]['shipping_code'] = '';
                    }

                    //print_r($order_data);die;

                    $order_data[$store_id]['shipping_address'] = $this->session->data['shipping_address'];
                    $order_data[$store_id]['shipping_contact_no'] = $this->session->data['shipping_contact_no'];
                    $order_data[$store_id]['shipping_name'] = $this->session->data['shipping_name'];
                    $order_data[$store_id]['shipping_city_id'] = $this->session->data['shipping_city_id'];

                    $order_data[$store_id]['products'] = [];

                    foreach ($this->cart->getProducts() as $product) {
                        $option_data = [];

                        $this->load->model('account/address');
                        $vendor_id = $this->model_account_address->getVendorId($product['store_id']);

                        if ($store_id == $product['store_id']) {
                            $order_data[$store_id]['products'][] = [
                                    'product_store_id' => $product['product_store_id'],
                                    'store_product_variation_id' => $product['store_product_variation_id'],
                                    'store_id' => $product['store_id'],
                                    'vendor_id' => $vendor_id,
                                    'name' => $product['name'],
                                    'model' => $product['model'],
                                    'option' => $option_data,
                                    'download' => $product['download'],
                                    'quantity' => $product['quantity'],
                                    'subtract' => $product['subtract'],
                                    'price' => $product['price'],
                                    'total' => $product['total'],
                                    'tax' => $this->tax->getTax($product['price'], $product['tax_class_id']),
                                    'reward' => $product['reward'],
                                ];
                        }
                    }

                    $order_data[$store_id]['vouchers'] = [];

                    $order_data[$store_id]['comment'] = '';
                    $order_data[$store_id]['total'] = $total;

                    $order_data[$store_id]['affiliate_id'] = 0;
                    // $order_data[$store_id]['commission'] = 0;
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

                    if (isset($this->request->server[''])) {
                        $order_data[$store_id]['accept_language'] = $this->request->server[''];
                    } else {
                        $order_data[$store_id]['accept_language'] = '';
                    }
                    $order_data[$store_id]['order_id'] = '';
                    $this->load->model('checkout/order');

                    if (isset($this->session->data['dates'][$store_id])) {
                        $order_data[$store_id]['delivery_date'] = $this->session->data['dates'][$store_id];
                    } else {
                        $order_data[$store_id]['delivery_date'] = '';
                    }

                    if (isset($this->session->data['timeslot'][$store_id])) {
                        $order_data[$store_id]['delivery_timeslot'] = $this->session->data['timeslot'][$store_id];
                    } else {
                        $order_data[$store_id]['delivery_timeslot'] = '';
                    }
                }
                //print_r($order_data);die;
                $this->load->model('checkout/order');

                $json['order_id'] = $this->model_checkout_order->addOrder($order_data);

                // Set the order history
                if (isset($this->request->post['order_status_id'])) {
                    $order_status_id = $this->request->post['order_status_id'];
                } else {
                    $order_status_id = $this->config->get('config_order_status_id');
                }

                foreach ($this->session->data['order_id'] as $order_id) {
                    $this->model_checkout_order->addOrderHistory($order_id, $order_status_id);
                }

                unset($this->session->data['order_id']);
                $json['success'] = $this->language->get('text_success');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function edit()
    {
        $this->load->language('api/order');

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('checkout/order');

            if (isset($this->request->get['order_id'])) {
                $order_id = $this->request->get['order_id'];
            } else {
                $order_id = 0;
            }
            $store_id = $this->request->post['store_id'];

            $order_info = $this->model_checkout_order->getOrder($order_id);

            if ($order_info) {
                // Customer
                if (!isset($this->session->data['customer'])) {
                    $json['error'] = $this->language->get('error_customer');
                }

                // Payment Method
                if (empty($this->session->data['payment_methods'])) {
                    $json['error'] = 'Payment method required!';
                } elseif (!isset($this->request->post['payment_method'])) {
                    $json['error'] = 'Payment method required!';
                } elseif (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
                    $json['error'] = 'Payment method required!';
                }

                if (!$json) {
                    $this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];
                }

                // Shipping Method
                if (empty($this->session->data['shipping_methods'])) {
                    $json['error'] = 'Shipping method required!';
                } elseif (!isset($this->request->post['shipping_method'])) {
                    $json['error'] = 'Shipping method required!';
                } else {
                    $shipping = explode('.', $this->request->post['shipping_method']);
                    //print_r($this->session->data['shipping_methods']);
                    if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$store_id][$shipping[0]]['quote'][$shipping[1]])) {
                        $json['error'] = 'Shipping method required!';
                    }
                }

                if (!$json) {
                    $this->session->data['shipping_method'][$store_id] = ['shipping_method' => $this->session->data['shipping_methods'][$store_id][$shipping[0]]['quote'][$shipping[1]]];
                }

                if (!isset($this->session->data['payment_method'])) {
                    $json['error'] = $this->language->get('error_payment_method');
                }

                // Shipping
                if ($this->cart->hasShipping()) {
                    // Shipping Address
                    if (!isset($this->session->data['shipping_address'])) {
                        $json['error'] = '1';
                    }

                    // Shipping Method
                    if (!isset($this->request->post['shipping_method'])) {
                        $json['error'] = '2';
                    }
                } else {
                    unset($this->session->data['shipping_address']);
                    unset($this->session->data['shipping_method']);
                    unset($this->session->data['shipping_methods']);
                }

                // Cart
                if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
                    $json['error'] = $this->language->get('error_stock');
                }

                // Validate minimum quantity requirements.
                $products = $this->cart->getProducts();

                foreach ($products as $product) {
                    $product_total = 0;

                    foreach ($products as $product_2) {
                        if ($product_2['product_store_id'] == $product['product_store_id']) {
                            $product_total += $product_2['quantity'];
                        }
                    }

                    if ($product['minimum'] > $product_total) {
                        $json['error'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);

                        break;
                    }
                }

                if (!$json) {
                    $order_data = [];

                    $order_data['currency_value'] = $this->currency->getValue();
                    $order_data['currency_code'] = $this->currency->getCode();

                    // Store Details
                    $order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
                    $order_data['store_id'] = $store_id;

                    $this->load->model('account/address');
                    $store_info = $this->model_account_address->getAllStoreData($store_id);

                    $order_data['store_name'] = $store_info['name'];
                    $order_data['store_url'] = $this->config->get('config_url');

                    $order_data['commission'] = $store_info['commision'];

                    // Customer Details
                    $order_data['customer_id'] = $this->session->data['customer']['customer_id'];
                    $order_data['customer_group_id'] = $this->session->data['customer']['customer_group_id'];
                    $order_data['firstname'] = $this->session->data['customer']['firstname'];
                    $order_data['lastname'] = $this->session->data['customer']['lastname'];
                    $order_data['email'] = $this->session->data['customer']['email'];
                    $order_data['telephone'] = $this->session->data['customer']['telephone'];
                    $order_data['fax'] = $this->session->data['customer']['fax'];
                    $order_data['custom_field'] = $this->session->data['customer']['custom_field'];

                    if (isset($this->session->data['payment_method']['title'])) {
                        $order_data['payment_method'] = $this->session->data['payment_method']['title'];
                    } else {
                        $order_data['payment_method'] = '';
                    }

                    if (isset($this->session->data['payment_method']['code'])) {
                        $order_data['payment_code'] = $this->session->data['payment_method']['code'];
                    } else {
                        $order_data['payment_code'] = '';
                    }

                    $order_data['payment_custom_field'] = [];

                    // Shipping Details
                    if ($this->cart->hasShipping()) {
                        $order_data['shipping_address'] = $this->session->data['shipping_address'];
                        $order_data['shipping_contact_no'] = $this->session->data['shipping_contact_no'];
                        $order_data['shipping_name'] = $this->session->data['shipping_name'];
                        $order_data['shipping_city_id'] = $this->session->data['shipping_city_id'];
                        $order_data['shipping_custom_field'] = [];

                        if (isset($this->session->data['shipping_method'][$store_id]['shipping_method']['title'])) {
                            $order_data['shipping_method'] = $this->session->data['shipping_method'][$store_id]['shipping_method']['title'];
                        } else {
                            $order_data['shipping_method'] = '';
                        }

                        if (isset($this->session->data['shipping_method'][$store_id]['shipping_method']['code'])) {
                            $order_data['shipping_code'] = $this->session->data['shipping_method'][$store_id]['shipping_method']['code'];
                        } else {
                            $order_data['shipping_code'] = '';
                        }
                    } else {
                        $order_data['shipping_address'] = '';
                        $order_data['shipping_contact_no'] = '';
                        $order_data['shipping_name'] = [];
                        $order_data['shipping_method'] = '';
                        $order_data['shipping_code'] = '';
                    }

                    // Products
                    $order_data['products'] = [];

                    $this->load->model('account/address');

                    foreach ($this->cart->getProducts() as $product) {
                        $option_data = [];

                        $temp = $this->model_account_address->getVendorData($product['store_id']);

                        if ($temp) {
                            $vendor_id = $temp['vendor_id'];
                        } else {
                            $vendor_id = '';
                        }

                        $order_data['products'][] = [
                            'product_store_id' => $product['product_store_id'],
                           'store_product_variation_id' => $product['store_product_variation_id'],
                            'vendor_id' => $vendor_id,
                            'store_id' => $product['store_id'],
                            'name' => $product['name'],
                            'model' => $product['model'],
                            'option' => $option_data,
                            'download' => $product['download'],
                            'quantity' => $product['quantity'],
                            'subtract' => $product['subtract'],
                            'price' => $product['price'],
                            'total' => $product['total'],
                            'tax' => $this->tax->getTax($product['price'], $product['tax_class_id']),
                            'reward' => $product['reward'],
                        ];
                    }

                    if (isset($this->session->data['dates'][$store_id])) {
                        $order_data['delivery_date'] = $this->session->data['dates'][$store_id];
                    } else {
                        $order_data['delivery_date'] = '';
                    }

                    if (isset($this->session->data['timeslot'][$store_id])) {
                        $order_data['delivery_timeslot'] = $this->session->data['timeslot'][$store_id];
                    } else {
                        $order_data['delivery_timeslot'] = '';
                    }

                    // Order Totals
                    $this->load->model('extension/extension');

                    $order_data['totals'] = [];
                    $total = 0;
                    $taxes = $this->cart->getTaxes();

                    $sort_order = [];

                    $results = $this->model_extension_extension->getExtensions('total');

                    foreach ($results as $key => $value) {
                        $sort_order[$key] = $this->config->get($value['code'].'_sort_order');
                    }

                    array_multisort($sort_order, SORT_ASC, $results);

                    foreach ($results as $result) {
                        if ($this->config->get($result['code'].'_status')) {
                            $this->load->model('total/'.$result['code']);

                            $this->{'model_total_'.$result['code']}->getTotal($order_data['totals'], $total, $taxes);
                        }
                    }

                    $sort_order = [];

                    foreach ($order_data['totals'] as $key => $value) {
                        $sort_order[$key] = $value['sort_order'];
                    }

                    array_multisort($sort_order, SORT_ASC, $order_data['totals']);

                    if (isset($this->request->post['comment'])) {
                        $order_data['comment'] = $this->request->post['comment'];
                    } else {
                        $order_data['comment'] = '';
                    }

                    $order_data['total'] = $total;

                    //print_r($order_data);

                    $this->model_checkout_order->editOrder($order_id, $order_data);

                    // Set the order history
                    if (isset($this->request->post['order_status_id'])) {
                        $order_status_id = $this->request->post['order_status_id'];
                    } else {
                        $order_status_id = $this->config->get('config_order_status_id');
                    }

                    $this->model_checkout_order->addOrderHistory($order_id, $order_status_id);

                    $json['success'] = $this->language->get('text_success');
                }
            } else {
                $json['error'] = $this->language->get('error_not_found');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function delete()
    {
        $this->load->language('api/order');

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('checkout/order');

            if (isset($this->request->get['order_id'])) {
                $order_id = $this->request->get['order_id'];
            } else {
                $order_id = 0;
            }

            $order_info = $this->model_checkout_order->getOrder($order_id);

            if ($order_info) {
                $this->model_checkout_order->deleteOrder($order_id);

                $json['success'] = $this->language->get('text_success');
            } else {
                $json['error'] = $this->language->get('error_not_found');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function history()
    {
        $this->load->language('api/order');

        $json = [];

        $log = new Log('error.log');
        $log->write('api/order/history');

        if (isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            // Add keys for missing post vars
            $keys = [
                'order_status_id',
                'notify',
                'append',
                'comment',
            ];

            $log->write('1');

            foreach ($keys as $key) {
                if (!isset($this->request->post[$key])) {
                    $this->request->post[$key] = '';
                }
            }

            $this->load->model('checkout/order');

            if (isset($this->request->get['order_id'])) {
                $order_id = $this->request->get['order_id'];
            } else {
                $order_id = 0;
            }

            $order_info = $this->model_checkout_order->getOrder($order_id);

            $log->write($order_id);

            if ($order_info) {
                $this->model_checkout_order->addOrderHistory($order_id, $this->request->post['order_status_id'], $this->request->post['comment'], $this->request->post['notify']);

                //$this->createDeliveryRequest($order_id);

                $json['success'] = $this->language->get('text_success');
            } else {
                $json['error'] = $this->language->get('error_not_found');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function hasProduct()
    {
        $json = [];

        if ($this->cart->countProducts() > 0) {
            $json['error']['warning'] = '';
        } else {
            $json['error']['warning'] = 'No product Founds';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addHistory()
    {
        $this->load->language('api/order');

        $json = [];

        $log = new Log('error.log');
        $log->write('api/order/history');

        if (isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            // Add keys for missing post vars
            $keys = [
                'order_status_id',
                'notify',
                'append',
                'comment',
            ];

            $log->write('1');

            foreach ($keys as $key) {
                if (!isset($this->request->post[$key])) {
                    $this->request->post[$key] = '';
                }
            }

            $this->load->model('checkout/order');

            if (isset($this->request->get['order_id'])) {
                $order_id = $this->request->get['order_id'];
            } else {
                $order_id = 0;
            }

            $order_info = $this->model_checkout_order->getOrder($order_id);

            $log->write($order_id);

            if ($order_info) {
                $this->model_checkout_order->addOrderHistory($order_id, $this->request->get['order_status_id'], $this->request->get['comment'], $this->request->get['notify']);

                $json['success'] = $this->language->get('text_success');
            } else {
                $json['error'] = $this->language->get('error_not_found');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }


    public function ApproveOrRejectSubUserOrder()
    {       

        // $this->load->language('api/order');
        $json = [];
        $json['success'] = 'Something went wrong!';

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('account/order');
            // if (isset($this->request->get['order_id'])) {
            //     $order_id = $this->request->get['order_id'];
            // } else {
            //     $order_id = 0;
            // }
            $order_id = $this->request->post['order_id'];
            $customer_id = $this->request->post['customer_id'];
            $order_status = $this->request->post['order_status'];
            $log = new Log('error.log');
            $log->write($order_id);
            $log->write($customer_id);
            $log->write($order_status);

            $sub_users_order_details = $this->model_account_order->getSubUserOrderDetails($order_id, $customer_id);
            $log->write($sub_users_order_details);

            if (is_array($sub_users_order_details) && count($sub_users_order_details) > 0) {
                $order_update = $this->model_account_order->ApproveOrRejectSubUserOrder($order_id, $customer_id, $order_status);
                $json['success'] = 'Order '.$order_status.'!';
            }

             
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
