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
                $added_by = $this->request->get['added_by'];
                $added_by_role = $this->request->get['added_by_role'];

            } else {
                $order_id = 0;
            }

            $order_info = $this->model_checkout_order->getOrder($order_id);

            $log->write($order_id);

            if ($order_info) {
                $this->model_checkout_order->addOrderHistory($order_id, $this->request->post['order_status_id'], $this->request->post['comment'], $this->request->post['notify'], $added_by, $added_by_role);

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
                'paid',
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
                $this->model_checkout_order->addOrderHistory($order_id, $this->request->get['order_status_id'], $this->request->get['comment'], $this->request->get['notify'], $this->request->get['paid']);

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

        //if (!isset($this->session->data['api_id'])) {
          //  $json['error'] = $this->language->get('error_permission');
       // } else
        //{
            $this->load->model('account/order');
            // if (isset($this->request->get['order_id'])) {
            //     $order_id = $this->request->get['order_id'];
            // } else {
            //     $order_id = 0;
            // }
            $order_id = $this->request->post['order_id'];
            // $customer_id = $this->request->post['customer_id'];
            if (isset($this->request->get['customer_id'])) {
                $customer_id = $this->request->get['customer_id'];
            } else {
                $customer_id = $this->model_account_order->getCustomerParentByOrderId($order_id);
                
            }
            $order_status = $this->request->post['order_status'];
            $log = new Log('error.log');
            $log->write($order_id);
            // $log->write($customer_id);
            $log->write($order_status);

            $sub_users_order_details = $this->model_account_order->getSubUserOrderDetailsapi($order_id );
            $log->write($sub_users_order_details);

            if (is_array($sub_users_order_details) && count($sub_users_order_details) > 0) {
                $order_update = $this->model_account_order->ApproveOrRejectSubUserOrderApi($order_id, $order_status);
                //$json['success'] = 'Order '.$order_status.'!';
                $sub_users_order_details = $this->model_account_order->getSubUserOrderDetailsapi($order_id);
                
                // echo "<pre>";print_r( $sub_users_order_details);die;
                
                if (($sub_users_order_details['parent_approval'] == 'Approved') || ($sub_users_order_details['head_chef'] == 'Approved' && $sub_users_order_details['procurement'] == 'Approved')) {
                    $comment = 'Order Approved By Parent User';
                    // $this->model_account_order->UpdateOrderStatus($order_id, 14, $comment);
                    $this->model_account_order->UpdateOrderStatus($order_id, 14, $comment,$customer_id, 'customer');
                    
                    $sub_users_order_details = $this->model_account_order->getSubUserOrderDetailsapi($order_id);
                   
                    //echo "<pre>";print_r( $sub_users_order_details);die;
                    if ($sub_users_order_details['order_status_id'] == 14) {
                        $json['success'] = 'Order Recieved';
                    }
    
                    if ($sub_users_order_details['order_status_id'] == 15) {
                        $json['success'] = 'Order Approval Pending';
                    }
    
                    if ($sub_users_order_details['order_status_id'] == 16) {
                        $json['success'] = 'Order Rejected';
                    }

                    if (($sub_users_order_details['parent_approval'] == 'Approved') || ($sub_users_order_details['head_chef'] == 'Approved' && $sub_users_order_details['procurement'] == 'Approved')) {
                        $this->model_account_order->SubUserOrderApproved($order_id, 14);
                    }
                }
    
                if ($sub_users_order_details['parent_approval'] == 'Rejected' || $sub_users_order_details['head_chef'] == 'Rejected') {
                    $comment = 'Order Rejected By Parent User';
                    //  $this->model_account_order->UpdateOrderStatus($order_id, 16,$comment);
                $this->model_account_order->UpdateOrderStatus($order_id, 16, $comment, $customer_id, 'customer');
                $this->model_account_order->SubUserOrderReject($order_id, 16);
                    $sub_users_order_details = $this->model_account_order->getSubUserOrderDetailsapi($order_id);
                    if ($sub_users_order_details['order_status_id'] == 14) {
                        $json['success'] = 'Order Recieved';
                    }
    
                    if ($sub_users_order_details['order_status_id'] == 15) {
                        $json['success'] = 'Order Approval Pending';
                    }
    
                    if ($sub_users_order_details['order_status_id'] == 16) {
                        $json['success'] = 'Order Rejected';
                    }
                }
    
                if ($sub_users_order_details['head_chef'] == 'Pending' || $sub_users_order_details['procurement'] == 'Pending') {
                    $sub_users_order_details = $this->model_account_order->getSubUserOrderDetailsapi($order_id);
                    if ($sub_users_order_details['order_status_id'] == 14) {
                        $json['success'] = 'Order Recieved';
                    }
    
                    if ($sub_users_order_details['order_status_id'] == 15) {
                        $json['success'] = 'Order Approval Pending';
                    }
    
                    if ($sub_users_order_details['order_status_id'] == 16) {
                        $json['success'] = 'Order Rejected';
                    }
                }
    
                if (($sub_users_order_details['head_chef'] == 'Rejected' || $sub_users_order_details['head_chef'] == 'Approved') && $sub_users_order_details['procurement'] == 'Rejected') {
                    $comment = 'Order Rejected By Parent User';
                    // $this->model_account_order->UpdateOrderStatus($order_id, 16,$comment);
                    $this->model_account_order->UpdateOrderStatus($order_id, 16, $comment,$customer_id, 'customer');
                    
                    $sub_users_order_details = $this->model_account_order->getSubUserOrderDetailsapi($order_id);
                    if ($sub_users_order_details['order_status_id'] == 14) {
                        $json['success'] = 'Order Recieved';
                    }
    
                    if ($sub_users_order_details['order_status_id'] == 15) {
                        $json['success'] = 'Order Approval Pending';
                    }
    
                    if ($sub_users_order_details['order_status_id'] == 16) {
                        $json['success'] = 'Order Rejected';
                    }
                }
            }

             
        //}

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function ApproveOrRejectSubUserOrderByChefProcurement() {
        $json['success'] = 'Something went wrong!';
        $order_id = $this->request->post['order_id'];
        $customer_id = $this->request->post['customer_id'];
        $order_status = $this->request->post['order_status'];
        $role = $this->request->post['role'];

        $log = new Log('error.log');
        $log->write($order_id);
        $log->write($customer_id);
        $log->write($order_status);

        $this->load->model('account/order');
        $sub_users_order_details = $this->model_account_order->getSubUserOrderDetailsapi($order_id, $customer_id);

        if (is_array($sub_users_order_details) && count($sub_users_order_details) > 0) {
            $order_update = $this->model_account_order->ApproveOrRejectSubUserOrderByChefProcurement($order_id, $customer_id, $order_status, $role);
            $sub_users_order_details = $this->model_account_order->getSubUserOrderDetails($order_id, $customer_id);
            $log->write($sub_users_order_details);
            if ($role != NULL) {
                $user_role = str_replace('_', ' ', $role);
            } else {
                $user_role = 'Parent';
            }
            if (($sub_users_order_details['parent_approval'] == 'Approved') || ($sub_users_order_details['head_chef'] == 'Approved' && $sub_users_order_details['procurement'] == 'Approved')) {
                $comment = 'Order Approved By ' . $user_role . ' User';
                // $this->model_account_order->UpdateOrderStatus($order_id, 14, $comment);
                $this->model_account_order->UpdateOrderStatus($order_id, 14, $comment, $customer_id, 'customer');
                
                $sub_users_order_details = $this->model_account_order->getSubUserOrderDetails($order_id, $customer_id);
                if ($sub_users_order_details['order_status_id'] == 14) {
                    $json['success'] = 'Order Recieved';
                }

                if ($sub_users_order_details['order_status_id'] == 15) {
                    $json['success'] = 'Order Approval Pending';
                }

                if ($sub_users_order_details['order_status_id'] == 16) {
                    $json['success'] = 'Order Rejected';
                }

                if($sub_users_order_details['head_chef'] == 'Approved' && $sub_users_order_details['procurement'] == 'Approved') {
                    $this->model_account_order->SubUserOrderApproved($order_id, 14); 
                    }

            }

            if ($sub_users_order_details['parent_approval'] == 'Rejected' || $sub_users_order_details['head_chef'] == 'Rejected') {
               
                $comment = 'Order Rejected By ' .$user_role.' User';
                // $this->model_account_order->UpdateOrderStatus($order_id, 16, $comment);
                $this->model_account_order->UpdateOrderStatus($order_id, 16, $comment, $customer_id, 'customer');
                
                $sub_users_order_details = $this->model_account_order->getSubUserOrderDetails($order_id, $customer_id);
                if ($sub_users_order_details['order_status_id'] == 14) {
                    $json['success'] = 'Order Recieved';
                }

                if ($sub_users_order_details['order_status_id'] == 15) {
                    $json['success'] = 'Order Approval Pending';
                }

                if ($sub_users_order_details['order_status_id'] == 16) {
                    $json['success'] = 'Order Rejected';
                }

                $this->model_account_order->SubUserOrderReject($order_id, 16);
            }

            if ($sub_users_order_details['head_chef'] == 'Pending' || $sub_users_order_details['procurement'] == 'Pending') {
                
                $sub_users_order_details = $this->model_account_order->getSubUserOrderDetails($order_id, $customer_id);
                if ($sub_users_order_details['order_status_id'] == 14) {
                    $json['success'] = 'Order Recieved';
                }

                if ($sub_users_order_details['order_status_id'] == 15) {
                    $json['success'] = 'Order Approval Pending';
                }

                if ($sub_users_order_details['order_status_id'] == 16) {
                    $json['success'] = 'Order Rejected';
                }
            }

            if (($sub_users_order_details['head_chef'] == 'Rejected' || $sub_users_order_details['head_chef'] == 'Approved') && $sub_users_order_details['procurement'] == 'Rejected') {
                $comment = 'Order Rejected By ' .$user_role.' User';
                // $this->model_account_order->UpdateOrderStatus($order_id, 16, $comment);
                $this->model_account_order->UpdateOrderStatus($order_id, 16, $comment, $customer_id, 'customer');
                
                $sub_users_order_details = $this->model_account_order->getSubUserOrderDetails($order_id, $customer_id);
                if ($sub_users_order_details['order_status_id'] == 14) {
                    $json['success'] = 'Order Recieved';
                }

                if ($sub_users_order_details['order_status_id'] == 15) {
                    $json['success'] = 'Order Approval Pending';
                }

                if ($sub_users_order_details['order_status_id'] == 16) {
                    $json['success'] = 'Order Rejected';
                }

                if ($sub_users_order_details['head_chef'] == 'Rejected' && $sub_users_order_details['procurement'] == 'Rejected') {
                    $this->model_account_order->SubUserOrderReject($order_id, 16);
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    
    public function editorderquantity()
    {    
        
        $json = [];
        $json['success'] = 'Something went wrong!';

        //if (!isset($this->session->data['api_id'])) {
            //$json['error'] = $this->language->get('error_permission');
       // } else 
       {

        $json['location'] = 'module';
        
        $json['data'] = [];
        $json['message'] = [];

        $order_id = $this->request->post['order_id'];
        // $log->write($order_id);
        $product_id = $this->request->post['product_id'];
        $quantity = $this->request->post['quantity'];
        $unit = $this->request->post['unit'];
        $log = new Log('error.log');
            $log->write($order_id);
            $log->write($product_id);
            $log->write($quantity);
        $this->load->model('account/order');
        $order_info = $this->model_account_order->getOrder($order_id, true);
        if (null != $order_info && 15 == $order_info['order_status_id']) {
            $order_products = $this->model_account_order->getOrderProducts($order_id);
            // $log->write($order_products);

            $key = array_search($product_id, array_column($order_products, 'product_id'));

            $this->load->model('assets/product');
            $product_info = $this->model_assets_product->getProductForPopup($order_products[$key]['product_id'], false, $order_products[$key]['store_id']);
            $s_price = 0;
            $o_price = 0;

            if (!$this->config->get('config_inclusiv_tax')) {
                //get price html
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $product_info['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));

                    $o_price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                } else {
                    $product_info['price'] = false;
                }
                if ((float) $product_info['special_price']) {
                    $product_info['special_price'] = $this->currency->format($this->tax->calculate($product_info['special_price'], $product_info['tax_class_id'], $this->config->get('config_tax')));

                    $s_price = $this->tax->calculate($product_info['special_price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                } else {
                    $product_info['special_price'] = false;
                }
            } else {
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
            if (CATEGORY_PRICE_ENABLED == true && isset($cachePrice_data) && isset($cachePrice_data[$product_info['product_store_id'].'_'.$_SESSION['customer_category'].'_'.$order_products[$key]['store_id']])) {
                $s_price = $cachePrice_data[$product_info['product_store_id'].'_'.$_SESSION['customer_category'].'_'.$order_products[$key]['store_id']];
                $o_price = $cachePrice_data[$product_info['product_store_id'].'_'.$_SESSION['customer_category'].'_'.$order_products[$key]['store_id']];
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
              $special_price[1] = str_replace( ',', '', $special_price[1]);
            $total = $special_price[1] * $quantity + ($this->config->get('config_tax') ? ($order_products[$key]['tax'] * $quantity) : 0);
            // $log->write($total);
            $log->write($product_id);
            $log->write($product_id);

            $this->db->query('UPDATE '.DB_PREFIX.'order_product SET quantity = '.$quantity.', total = '.$total." WHERE order_product_id = '".(int) $order_products[$key]['order_product_id']."' AND order_id  = '".(int) $order_id."' AND product_id = '".(int) $product_id."'");
            $this->db->query('UPDATE '.DB_PREFIX.'real_order_product SET quantity = '.$quantity.', total = '.$total." WHERE order_product_id = '".(int) $order_products[$key]['order_product_id']."' AND order_id  = '".(int) $order_id."' AND product_id = '".(int) $product_id."'");
            $order_totals = $this->db->query('SELECT SUM(total) AS total FROM '.DB_PREFIX."order_product WHERE order_id = '".(int) $order_id."'");
            $order_product_details = $this->db->query('SELECT * FROM '.DB_PREFIX."order_product WHERE order_product_id = '".(int) $order_products[$key]['order_product_id']."' AND order_id  = '".(int) $order_id."' AND product_id = '".(int) $product_id."'");
            $this->db->query('UPDATE '.DB_PREFIX."order_total SET `value` = '".$order_totals->row['total']."' WHERE order_id = '".$order_id."' AND code='total'");
            $this->db->query('UPDATE '.DB_PREFIX."order_total SET `value` = '".$order_totals->row['total']."' WHERE order_id = '".$order_id."' AND code='sub_total'");
            $total_products = $this->db->query('SELECT SUM(quantity) AS quantity FROM '.DB_PREFIX."order_product WHERE order_id = '".(int) $order_id."'");

            $json['count_products'] = $total_products->row['quantity'];
            $json['total_amount'] = $this->currency->format($order_totals->row['total']);
            $json['quantity'] = $total_products->row['quantity'];
            $json['product_total_price'] = $this->currency->format($order_product_details->row['total']);

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
            $json['status'] = 200;
            $json['success'] =   'Your Order Updated!';
        } else {
            $json['success'] =   'You Cant Update Order In This Status!';
        }
       }
        // $log->write('edit_order_quantity');
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }


 
}
