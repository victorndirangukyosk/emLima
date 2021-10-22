<?php

class ControllerCheckoutConfirm extends Controller {

    public function index() {
        $log = new Log('error.log');
        $log->write('Log 1.cs');

        $redirect = '';

        if (isset($this->request->post['store_id'])) {
            $store_id = $this->request->post['store_id'];
        } else {
            $store_id = $this->session->data['config_store_id'];
        }

        //05:53pm - 07:57pm09-03-2017
        //echo "<pre>";print_r($this->request->post);die;
        if (isset($this->request->post['dates_selected']) && strlen($this->request->post['dates_selected']) > 0) {
            $delivery_date = $this->request->post['dates_selected'];
            $this->session->data['dates'][$store_id] = $delivery_date;
        } elseif (isset($this->request->post['shipping_method']) && 'express.express' == $this->request->post['shipping_method']) {
            $delivery_date = date('d-m-Y');
            $this->session->data['dates'][$store_id] = $delivery_date;
        } else {
            $delivery_date = '';
        }

        if (isset($this->request->post['shipping_time_selected']) && strlen($this->request->post['shipping_time_selected']) > 0) {
            $delivery_timeslot = $this->request->post['shipping_time_selected'];
            $this->session->data['timeslot'][$store_id] = $delivery_timeslot;
        } elseif (isset($this->request->post['shipping_method']) && 'express.express' == $this->request->post['shipping_method']) {
            //date_default_timezone_set('Asia/Kolkata');
            $settings = $this->getSettings('express', 0);
            $timeDiff = $settings['express_how_much_time'];

            $min = 0;
            if ($timeDiff) {
                $i = explode(':', $timeDiff);
                $min = $min + $i[0] * 60 + $i[1]; //add difference minut to current time
            }
            $to = date('h:ia', strtotime('+' . $min . ' minutes', strtotime(date('h:ia'))));

            $delivery_timeslot = date('h:ia') . ' - ' . $to;

            $this->session->data['timeslot'][$store_id] = $delivery_timeslot;
            //print_r(date('h:ia'));print_r($delivery_timeslot);
        } else {
            $delivery_timeslot = '';
        }

        //echo "<pre>";print_r($delivery_timeslot);print_r($delivery_date);die;
        if (isset($this->request->post['payment_method'])) {
            $payment_method = $this->request->post['payment_method'];
        } else {
            $payment_method = 'free_checkout';
        }

        if (isset($this->session->data['payment_methods'])) {
            /* if(!array_key_exists($payment_method, $this->session->data['payment_methods'])) {
              $payment_method = 'cod';
              } */

            //$this->session->data['payment_method'] = $this->session->data['payment_methods'][$payment_method];
        }

        if ($this->cart->hasShipping()) {
            // Validate if shipping method has been set.
            if (!isset($this->session->data['shipping_method'])) {
                $redirect = $this->url->link('checkout/checkout', '', 'SSL');
            }
        } else {
            unset($this->session->data['shipping_method']);
            unset($this->session->data['shipping_methods']);
        }

        // Validate if payment method has been set.
        if (!isset($this->session->data['payment_method'])) {
            $redirect = $this->url->link('checkout/checkout', '', 'SSL');
        }

        $data['kondutoStatus'] = $this->config->get('config_konduto_status');

        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        $products = $this->cart->getProducts();

        $log->write('Log 2');
        foreach ($products as $product) {
            $product_total = 0;

            foreach ($products as $product_2) {
                if ($product_2['product_store_id'] == $product['product_store_id']) {
                    $product_total += $product_2['quantity'];
                }
            }

            if ($product['minimum'] > $product_total) {
                $redirect = $this->url->link('checkout/cart');

                break;
            }
        }

        if (!$redirect) {
            $log->write('Log 3');
            $stores = $this->cart->getStores();

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
                    $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
                }

                array_multisort($sort_order, SORT_ASC, $results);
                $log->write($results);

                foreach ($results as $result) {
                    //print_r("zz".$result['code']."yy".$this->config->get( $result['code']. '_status')."xx");

                    if ($this->config->get($result['code'] . '_status')) {
                        $this->load->model('total/' . $result['code']);
                        $log->write('in loop' . $result['code']);
                        $log->write('in loop' . $total);

                        $this->{'model_total_' . $result['code']}->getTotal($order_data[$store_id]['totals'], $total, $taxes, $store_id);
                    }
                }

                //print_r($total);die;
                $log->write('Log 3.1');
                $sort_order = [];

                foreach ($order_data[$store_id]['totals'] as $key => $value) {
                    $sort_order[$key] = $value['sort_order'];
                }
                array_multisort($sort_order, SORT_ASC, $order_data[$store_id]['totals']);
                $log->write('Log 3.2');
                $log->write($order_data[$store_id]['totals']);
                //echo "<pre>";print_r($order_data[$store_id]['totals']);die;
                //$this->db->select('store.store_id,store.name,store.min_order_amount,store.city_id,store.commision,user.commision as vendor_commision', FALSE);
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
                $log->write('Log 3.3');
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
                $log->write('Log 3.4');
                //for future user

                if (isset($this->request->post['dropoff_notes']) && strlen($this->request->post['dropoff_notes']) > 0) {
                    $order_data[$store_id]['comment'] = $this->request->post['dropoff_notes'];
                } else {
                    $order_data[$store_id]['comment'] = '';
                }

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
                        $order_data[$store_id]['shipping_method'] = $this->session->data['shipping_method'][75]['shipping_method']['title'];
                    } else {
                        $order_data[$store_id]['shipping_method'] = $this->session->data['shipping_method'][75]['shipping_method']['title'];
                    }

                    if (isset($this->session->data['shipping_method'][$store_id]['shipping_method']['code'])) {
                        $order_data[$store_id]['shipping_code'] = $this->session->data['shipping_method'][75]['shipping_method']['code'];
                    } else {
                        $order_data[$store_id]['shipping_code'] = $this->session->data['shipping_method'][75]['shipping_method']['code'];
                    }
                } else {
                    $order_data[$store_id]['shipping_method'] = '';
                    $order_data[$store_id]['shipping_code'] = '';
                }

                //print_r($order_data);die;
                if (isset($this->request->post['shipping_city_id'])) {
                    $shipping_city_id = $this->request->post['shipping_city_id'];
                    $order_data[$store_id]['shipping_city_id'] = $shipping_city_id;
                } elseif (isset($shipping_address_data['contact_no'])) {
                    $order_data[$store_id]['shipping_city_id'] = '';
                }

                if (isset($this->request->post['shipping_address_id'])) {
                    $shipping_address_id = $this->request->post['shipping_address_id'];
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

                    if (isset($this->request->post['shipping_contact_no'])) {
                        $shipping_contact_no = $this->request->post['shipping_contact_no'];
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
                $log->write('Log 3.4');
                foreach ($this->cart->getProducts() as $product) {
                    $option_data = [];

                    $vendor_id = $this->model_extension_extension->getVendorId($product['store_id']);

                    if ($store_id == $product['store_id']) {
                        $order_data[$store_id]['products'][] = [
                            'product_store_id' => $product['product_store_id'],
                            'store_product_variation_id' => $product['store_product_variation_id'],
                            'store_id' => $product['store_id'],
                            'vendor_id' => $vendor_id,
                            'name' => $product['name'],
                            'unit' => $product['unit'],
                            'product_type' => $product['product_type'],
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
                $log->write('Log 3.5');
                $order_data[$store_id]['vouchers'] = [];

                if (isset($this->request->post['dropoff_notes']) && strlen($this->request->post['dropoff_notes']) > 0) {
                    $order_data[$store_id]['comment'] = $this->request->post['dropoff_notes'];
                } else {
                    $order_data[$store_id]['comment'] = '';
                }

                $order_data[$store_id]['total'] = $total;

                $order_data[$store_id]['affiliate_id'] = 0;
                //$order_data[$store_id]['commission'] = 0;
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
                $log->write('Log 3.6');
                //$order_data[$store_id]['order_id'] = '';
                $this->load->model('checkout/order');

                if (isset($this->session->data['dates'][$store_id])) {
                    $order_data[$store_id]['delivery_date'] = $this->session->data['dates'][$store_id];
                } else {
                    //$order_data[$store_id]['delivery_date'] = '';
                    $order_data[$store_id]['delivery_date'] = date('d-m-Y');
                }

                if (isset($this->session->data['timeslot'][$store_id])) {
                    $order_data[$store_id]['delivery_timeslot'] = $this->session->data['timeslot'][$store_id];
                } else {
                    //$order_data[$store_id]['delivery_timeslot'] = '';
                    $settings = $this->getSettings('express', 0);
                    $timeDiff = $settings['express_how_much_time'];

                    $min = 0;
                    if ($timeDiff) {
                        $i = explode(':', $timeDiff);
                        $min = $min + $i[0] * 60 + $i[1]; //add difference minut to current time
                    }
                    $to = date('h:ia', strtotime('+' . $min . ' minutes', strtotime(date('h:ia'))));

                    $delivery_timeslot = date('h:ia') . ' - ' . $to;

                    $order_data[$store_id]['delivery_timeslot'] = $this->session->data['timeslot'][$store_id] = $delivery_timeslot;
                }
            }

            $this->model_checkout_order->addOrder($order_data);

            $log->write('Log 3.7');
            $data['text_recurring_item'] = $this->language->get('text_recurring_item');
            $data['text_payment_recurring'] = $this->language->get('text_payment_recurring');

            $data['column_name'] = $this->language->get('column_name');
            $data['column_model'] = $this->language->get('column_model');
            $data['column_quantity'] = $this->language->get('column_quantity');
            $data['column_price'] = $this->language->get('column_price');
            $data['column_total'] = $this->language->get('column_total');
            $tot = 0;
            foreach ($stores as $store_id) {
                $data['totals'] = [];
                foreach ($order_data[$store_id]['totals'] as $total) {
                    if ($total['code'] != 'total') {
                        $data['totals'][] = [
                            'title' => $total['title'],
                            'text' => $this->currency->format($total['value']),
                        ];
                        $tot += $total['value'];
                    }
                }
            }
            $transactionData = [
                'no_of_products' => $this->cart->countProducts(),
                'total' => $tot,
            ];
            $this->model_checkout_order->addTransaction($transactionData);
            $data['payment'] = $this->load->controller('payment/' . $this->session->data['payment_method']['code']);
        } else {
            $log->write('Log 4');
            $data['redirect'] = $redirect;
        }
        $log->write('Log 5');
        if ($this->config->get('config_checkout_id')) {
            $this->load->model('assets/information');

            $information_info = $this->model_assets_information->getInformation($this->config->get('config_checkout_id'));

            if ($information_info) {
                $data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_checkout_id'), 'SSL'), $information_info['title'], $information_info['title']);
            } else {
                $data['text_agree'] = '';
            }
        } else {
            $data['text_agree'] = '';
        }
        $log->write('Log 6');
        if (isset($this->session->data['agree'])) {
            $data['agree'] = $this->session->data['agree'];
        } else {
            $data['agree'] = '';
        }

        //echo json_encode(array('status'=>1,'redirect' => $this->url->link('checkout/success')));
        //return $this->url->link('checkout/success');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/confirm.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/checkout/confirm.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/checkout/confirm.tpl', $data));
        }
    }

    public function validateTimeslotEditOrder($argsData = []) {
        //echo "<pre>";print_r($argsData);die;
        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];
        $json['valid'] = false;

        $args = [];

        if (!isset($argsData['shipping_code']) || !isset($argsData['store_id']) || !isset($argsData['delivery_date']) || !isset($argsData['delivery_timeslot'])) {
            return $json;
        }

        $tmp['shipping_method'] = $argsData['shipping_code'];
        $tmp['timeslot_selected'] = $argsData['delivery_timeslot'];
        $tmp['store_id'] = $argsData['store_id'];
        $tmp['delivery_date'] = $argsData['delivery_date'];

        $args['stores'][$argsData['store_id']] = $tmp;

        $log = new Log('error.log');
        $log->write('validateTimeslot');
        $log->write($args);

        //echo "<pre>";print_r($args);die;
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
                        $log->write($response);

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

            $json['message'][] = ['type' => $this->language->get('text_no_products'), 'body' => $this->language->get('text_no_products')];

            http_response_code(400);
        }

        $log->write($json);

        return $json;

        /* $this->response->addHeader('Content-Type: application/json');
          $this->response->setOutput(json_encode($json)); */
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

    public function getSettings($code, $store_id = 0) {
        $this->load->model('setting/setting');

        return $this->model_setting_setting->getSetting($code, $store_id);
    }

    public function confirmPayment() {
        $log = new Log('error.log');
        $log->write('Log 1.x');
        $redirect = '';

        $log->write($this->request->post);

        if (isset($this->request->post['payment_method'])) {
            $payment_method = $this->request->post['payment_method'];
        } else {
            $payment_method = 'cod';
        }

        //$log->write($this->session->data['payment_methods']);
        //$log->write($payment_method);
        //$log->write($this->request->post);

        $this->session->data['payment_method'] = $this->session->data['payment_methods'][$payment_method];
        //$this->session->data['payment_method'] = 'cod';

        if ($this->cart->hasShipping()) {
            // Validate if shipping method has been set.
            if (!isset($this->session->data['shipping_method'])) {
                $redirect = $this->url->link('checkout/checkout', '', 'SSL');
            }
        } else {
            unset($this->session->data['shipping_method']);
            unset($this->session->data['shipping_methods']);
        }

        // Validate if payment method has been set.
        if (!isset($this->session->data['payment_method'])) {
            $redirect = $this->url->link('checkout/checkout', '', 'SSL');
        }

        // Validate cart has products and has stock.
        /* if ( ( !$this->cart->hasProducts() && empty( $this->session->data['vouchers'] ) ) || ( !$this->cart->hasStock() && !$this->config->get( 'config_stock_checkout' ) ) ) {
          $redirect = $this->url->link( 'checkout/cart' );
          } */

        $products = $this->cart->getProducts();

        $log->write('Log 2');
        foreach ($products as $product) {
            $product_total = 0;

            foreach ($products as $product_2) {
                if ($product_2['product_store_id'] == $product['product_store_id']) {
                    $product_total += $product_2['quantity'];
                }
            }

            if ($product['minimum'] > $product_total) {
                $redirect = $this->url->link('checkout/cart');

                break;
            }
        }

        if (!$redirect) {
            $log->write('Log 3');

            //$log->write($this->session->data['payment_method']);
            if (isset($this->session->data['payment_method']['code'])) {
                //$log->write($this->session->data['payment_method']['code']);
                $data['payment'] = $this->load->controller('payment/' . $this->session->data['payment_method']['code']);
            } else {
                $data['payment'] = '';
            }
        } else {
            $log->write('Log 4');
            $data['redirect'] = $redirect;
        }
        $log->write('Log 5');
        if ($this->config->get('config_checkout_id')) {
            $this->load->model('assets/information');

            $information_info = $this->model_assets_information->getInformation($this->config->get('config_checkout_id'));

            if ($information_info) {
                $data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_checkout_id'), 'SSL'), $information_info['title'], $information_info['title']);
            } else {
                $data['text_agree'] = '';
            }
        } else {
            $data['text_agree'] = '';
        }
        $log->write('Log 6');
        if (isset($this->session->data['agree'])) {
            $data['agree'] = $this->session->data['agree'];
        } else {
            $data['agree'] = '';
        }

        //echo json_encode(array('status'=>1,'redirect' => $this->url->link('checkout/success')));
        //return $this->url->link('checkout/success');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/confirm.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/checkout/confirm.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/checkout/confirm.tpl', $data));
        }
    }

    public function multiStoreIndex() {
        $log = new Log('error.log');
        $log->write('Log 1.cs');

        $redirect = '';

        if (isset($this->request->post['store_id'])) {
            $store_id = $this->request->post['store_id'];
        } else {
            $store_id = $this->session->data['config_store_id'];
        }

        if (isset($this->request->post['payment_method'])) {
            $payment_method = $this->request->post['payment_method'];
        } else {
            $payment_method = 'free_checkout';
        }

        if (isset($this->session->data['payment_methods'])) {
            /* if(!array_key_exists($payment_method, $this->session->data['payment_methods'])) {
              $payment_method = 'cod';
              } */

            //$this->session->data['payment_method'] = $this->session->data['payment_methods'][$payment_method];
        }

        if ($this->cart->hasShipping()) {
            // Validate if shipping method has been set.
            if (!isset($this->session->data['shipping_method'])) {
                $redirect = $this->url->link('checkout/checkout', '', 'SSL');
            }
        } else {
            unset($this->session->data['shipping_method']);
            unset($this->session->data['shipping_methods']);
        }

        // Validate if payment method has been set.
        if (!isset($this->session->data['payment_method'])) {
            $redirect = $this->url->link('checkout/checkout', '', 'SSL');
        }

        $data['kondutoStatus'] = $this->config->get('config_konduto_status');

        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        // Validate cart has products and has stock.
        /* if ( ( !$this->cart->hasProducts() && empty( $this->session->data['vouchers'] ) ) || ( !$this->cart->hasStock() && !$this->config->get( 'config_stock_checkout' ) ) ) {
          $redirect = $this->url->link( 'checkout/cart' );
          } */

        $products = $this->cart->getProducts();

        $log->write('Log 2');
        foreach ($products as $product) {
            $product_total = 0;

            foreach ($products as $product_2) {
                if ($product_2['product_store_id'] == $product['product_store_id']) {
                    $product_total += $product_2['quantity'];
                }
            }

            /* if ( $product['minimum'] > $product_total ) {
              $redirect = $this->url->link( 'checkout/cart' );

              break;
              } */
        }

        if (!$redirect) {
            $log->write('Log 3');
            $stores = $this->cart->getStores();

            //print_r($stores);
            foreach ($stores as $store_id) {
                $order_data[$store_id] = [];
                $order_data[$store_id]['totals'] = [];

                $total = 0;
                $taxes = $this->cart->getTaxes();
                $taxes_by_store = $this->cart->getTaxesByStore($store_id);
                $log->write('taxes_by_store');
                $log->write($taxes_by_store);
                $log->write('taxes_by_store');

                $this->load->model('extension/extension');

                $sort_order = [];

                $results = $this->model_extension_extension->getExtensions('total');

                foreach ($results as $key => $value) {
                    $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
                }
                array_multisort($sort_order, SORT_ASC, $results);

                foreach ($results as $result) {
                    if ($this->config->get($result['code'] . '_status')) {
                        $this->load->model('total/' . $result['code']);

                        $log->write('in multiStoreIndex' . $result['code']);
                        $log->write('in loop' . $total);

                        $this->{'model_total_' . $result['code']}->getTotal($order_data[$store_id]['totals'], $total, $taxes_by_store, $store_id);
                    }
                }

                //print_r($total);die;
                $log->write('Log 3.1');
                $sort_order = [];

                foreach ($order_data[$store_id]['totals'] as $key => $value) {
                    $sort_order[$key] = $value['sort_order'];
                }
                array_multisort($sort_order, SORT_ASC, $order_data[$store_id]['totals']);
                $log->write('Log 3.2');
                $log->write($order_data[$store_id]['totals']);
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
                $log->write('Log 3.3');
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
                $log->write('Log 3.4');
                //for future user
                // echo "<pre>";print_r($this->request->post);die;

                if (isset($this->request->post['dropoff_notes']) && count($this->request->post['dropoff_notes']) > 0 && isset($this->request->post['dropoff_notes'][$store_id])) {
                    $order_data[$store_id]['comment'] = $this->request->post['dropoff_notes'][$store_id];
                } else {
                    $order_data[$store_id]['comment'] = '';
                }

                if (isset($this->request->post['login_latitude'])) {
                    $order_data[$store_id]['login_latitude'] = $this->request->post['login_latitude'];
                } else {
                    $order_data[$store_id]['login_latitude'] = 0;
                }

                if (isset($this->request->post['login_longitude'])) {
                    $order_data[$store_id]['login_longitude'] = $this->request->post['login_longitude'];
                } else {
                    $order_data[$store_id]['login_longitude'] = 0;
                }


                if (isset($this->request->post['login_mode'])) {
                    $order_data[$store_id]['login_mode'] = $this->request->post['login_mode'];
                } else {
                    $order_data[$store_id]['login_mode'] = '';
                }

                if (isset($this->session->data['payment_method']['title']) && $store_id == 75) {
                    $order_data[$store_id]['payment_method'] = $this->session->data['payment_method']['title'];
                } elseif (isset($this->session->data['payment_method']['title']) && $store_id != 75) {
                    if ($this->session->data['payment_method']['code'] == 'wallet' || $this->session->data['payment_method']['code'] == 'mod') {
                        $order_data[$store_id]['payment_method'] = $this->session->data['payment_method']['title'];
                    } else {
                        $order_data[$store_id]['payment_method'] = 'Corporate Account/ Cheque Payment';
                    }
                } else {
                    $order_data[$store_id]['payment_method'] = '';
                }

                if (isset($this->session->data['payment_method']['code']) && $store_id == 75) {
                    $order_data[$store_id]['payment_code'] = $this->session->data['payment_method']['code'];
                } elseif (isset($this->session->data['payment_method']['code']) && $store_id != 75) {
                    if ($this->session->data['payment_method']['code'] == 'wallet' || $this->session->data['payment_method']['code'] == 'mod') {
                        $order_data[$store_id]['payment_code'] = $this->session->data['payment_method']['code'];
                    } else {
                        $order_data[$store_id]['payment_code'] = 'cod';
                    }
                } else {
                    $order_data[$store_id]['payment_code'] = '';
                }

                if ($this->cart->hasShipping()) {
                    if (isset($this->session->data['shipping_method'][$store_id]['shipping_method']['title'])) {
                        $order_data[$store_id]['shipping_method'] = $this->session->data['shipping_method'][75]['shipping_method']['title'];
                    } else {
                        $order_data[$store_id]['shipping_method'] = $this->session->data['shipping_method'][75]['shipping_method']['title'];
                    }

                    if (isset($this->session->data['shipping_method'][$store_id]['shipping_method']['code'])) {
                        $order_data[$store_id]['shipping_code'] = $this->session->data['shipping_method'][75]['shipping_method']['code'];
                    } else {
                        $order_data[$store_id]['shipping_code'] = $this->session->data['shipping_method'][75]['shipping_method']['code'];
                    }
                } else {
                    $order_data[$store_id]['shipping_method'] = '';
                    $order_data[$store_id]['shipping_code'] = '';
                }

                if (isset($this->request->post['shipping_city_id'])) {
                    $shipping_city_id = $this->request->post['shipping_city_id'];
                    $order_data[$store_id]['shipping_city_id'] = $shipping_city_id;
                } elseif (isset($shipping_address_data['contact_no'])) {
                    $order_data[$store_id]['shipping_city_id'] = '';
                }

                //echo "<pre>";print_r(	$this->session->data['shipping_address_id']);die;
                //if(isset($this->request->post['shipping_address_id'])){
                if (isset($this->session->data['shipping_address_id'])) {
                    // print_r($order_data);die;
                    // $shipping_address_id = $this->request->post['shipping_address_id'];
                    $shipping_address_id = $this->session->data['shipping_address_id'];
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

                    if (isset($this->request->post['shipping_contact_no'])) {
                        $shipping_contact_no = $this->request->post['shipping_contact_no'];
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
                //echo '<pre>';print_r($this->cart->getProducts());exit;
                $log->write('Log 3.4');
                foreach ($this->cart->getProducts() as $product) {
                    $option_data = [];

                    $vendor_id = $this->model_extension_extension->getVendorId($product['store_id']);

                    if ($store_id == $product['store_id']) {
                        $order_data[$store_id]['products'][] = [
                            'product_store_id' => $product['product_store_id'],
                            'product_id' => $product['product_id'],
                            'store_product_variation_id' => $product['store_product_variation_id'],
                            'store_id' => $product['store_id'],
                            'vendor_id' => $vendor_id,
                            'name' => $product['name'],
                            'unit' => $product['unit'],
                            'product_type' => $product['product_type'],
                            'produce_type' => $product['produce_type'],
                            'product_note' => $product['product_note'],
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
                $log->write('Log 3.5');
                $order_data[$store_id]['vouchers'] = [];

                if (isset($this->request->post['dropoff_notes']) && count($this->request->post['dropoff_notes']) > 0 && isset($this->request->post['dropoff_notes'][$store_id])) {
                    $order_data[$store_id]['comment'] = $this->request->post['dropoff_notes'][$store_id];
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
                $log->write('Log 3.6');
                //$order_data[$store_id]['order_id'] = '';
                $this->load->model('checkout/order');

                /* if ( isset( $this->session->data['dates'][$store_id] ) ) {

                  $order_data[$store_id]['delivery_date'] = $this->session->data['dates'][$store_id];
                  } else {
                  $order_data[$store_id]['delivery_date'] = '';
                  }

                  if ( isset( $this->session->data['timeslot'][$store_id] ) ) {
                  $order_data[$store_id]['delivery_timeslot'] = $this->session->data['timeslot'][$store_id];
                  } else {
                  $order_data[$store_id]['delivery_timeslot'] = '';
                  } */

                if (isset($this->session->data['dates'][$store_id])) {
                    $order_data[$store_id]['delivery_date'] = $this->session->data['dates'][$store_id];
                } else {
                    //$order_data[$store_id]['delivery_date'] = '';
                    $order_data[$store_id]['delivery_date'] = date('d-m-Y');
                }

                if (isset($this->session->data['timeslot'][$store_id])) {
                    $order_data[$store_id]['delivery_timeslot'] = $this->session->data['timeslot'][$store_id];
                } else {
                    //$order_data[$store_id]['delivery_timeslot'] = '';
                    $settings = $this->getSettings('express', 0);
                    $timeDiff = $settings['express_how_much_time'];

                    $min = 0;
                    if ($timeDiff) {
                        $i = explode(':', $timeDiff);
                        $min = $min + $i[0] * 60 + $i[1]; //add difference minut to current time
                    }
                    $to = date('h:ia', strtotime('+' . $min . ' minutes', strtotime(date('h:ia'))));

                    $delivery_timeslot = date('h:ia') . ' - ' . $to;

                    $order_data[$store_id]['delivery_timeslot'] = $this->session->data['timeslot'][$store_id] = $delivery_timeslot;
                }
            }
            // echo "<pre>";print_r($order_data);die;

            $log->write('addMultiOrder call');
            //$log->write($order_data);

            $this->model_checkout_order->addMultiOrder($order_data);

            //  echo "<pre>";print_r($order_data);die;

            $data['text_recurring_item'] = $this->language->get('text_recurring_item');
            $data['text_payment_recurring'] = $this->language->get('text_payment_recurring');

            $data['column_name'] = $this->language->get('column_name');
            $data['column_model'] = $this->language->get('column_model');
            $data['column_quantity'] = $this->language->get('column_quantity');
            $data['column_price'] = $this->language->get('column_price');
            $data['column_total'] = $this->language->get('column_total');

            $tot = 0;

            foreach ($stores as $store_id) {
                $data['totals'] = [];
                foreach ($order_data[$store_id]['totals'] as $total) {
                    if ($total['code'] != 'total') {
                        $data['totals'][] = [
                            'title' => $total['title'],
                            'text' => $this->currency->format($total['value']),
                        ];
                        $tot += $total['value'];
                    }
                }
            }

            $transactionData = [
                'no_of_products' => $this->cart->countProducts(),
                'total' => $tot,
            ];

            $log->write('addTransaction');
            $log->write($this->session->data['order_id']);

            $this->model_checkout_order->addTransaction($transactionData);

            $data['payment'] = $this->load->controller('payment/' . $this->session->data['payment_method']['code']);
            $data['payment_interswitch'] = $this->load->controller('payment/interswitch');
            $log->write('payment');
            $log->write($data['payment']);
            $log->write('payment');
        } else {
            $log->write('Log 4');
            $data['redirect'] = $redirect;
        }
        $log->write('Log 5');
        if ($this->config->get('config_checkout_id')) {
            $this->load->model('assets/information');

            $information_info = $this->model_assets_information->getInformation($this->config->get('config_checkout_id'));

            if ($information_info) {
                $data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_checkout_id'), 'SSL'), $information_info['title'], $information_info['title']);
            } else {
                $data['text_agree'] = '';
            }
        } else {
            $data['text_agree'] = '';
        }
        $log->write('Log 6');
        if (isset($this->session->data['agree'])) {
            $data['agree'] = $this->session->data['agree'];
        } else {
            $data['agree'] = '';
        }

        //echo json_encode(array('status'=>1,'redirect' => $this->url->link('checkout/success')));
        //return $this->url->link('checkout/success');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/confirm.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/checkout/confirm.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/checkout/confirm.tpl', $data));
        }
    }

    public function setAddressIdSession() {
        //echo "<pre>";print_r($this->request->post['shipping_address_id']);die;
        $this->session->data['shipping_address_id'] = $this->request->post['shipping_address_id'];

        $this->load->model('account/address');
        $shipping_address_data = $this->model_account_address->getAddress($this->request->post['shipping_address_id']);

        $data['address'] = strlen($shipping_address_data['address']) > 27 ? substr($shipping_address_data['address'], 0, 27) . '...' : $shipping_address_data['address'];
        //$this->session->data['shipping_address'] = strlen($shipping_address_data['address']) > 100 ? substr($shipping_address_data['address'],0,100)."..." : $shipping_address_data['address'];;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function CheckOtherVendorOrderExists() {

        $log = new Log('error.log');
        $json['modal_open'] = FALSE;
        if (isset($this->session->data['accept_vendor_terms']) && $this->session->data['accept_vendor_terms'] == TRUE) {
            $json['modal_open'] = FALSE;
        } else {
            $json['product_list'] = null;
            foreach ($this->cart->getProducts() as $store_products) {
                /* FOR KWIKBASKET ORDERS */
                $log->write('CheckOtherVendorOrderExists');
                $log->write($store_products['store_id']);
                $log->write('CheckOtherVendorOrderExists');
                if ($store_products['store_id'] > 75 && $this->customer->getPaymentTerms() != 'Payment On Delivery') {
                    $json['modal_open'] = TRUE;
                    if ($json['product_list'] == null) {
                        $json['product_list'] = $store_products['name'];
                    } else {
                        $json['product_list'] = $json['product_list'] . ' ,' . $store_products['name'];
                    }
                }
            }
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function AcceptOtherVendorOrderTerms() {

        $log = new Log('error.log');
        $json['vendor_terms'] = $this->request->post['accept_terms'];
        $this->session->data['accept_vendor_terms'] = $this->request->post['accept_terms'];
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function GetProductDeliveryDays() {

        $log = new Log('error.log');
        $this->load->model('assets/product');
        $this->load->model('user/user');
        $results = NULL;
        foreach ($this->cart->getProducts() as $product) {

            $product_store_id = $product['product_store_id'];
            $store_id = $product['store_id'];
            $product_id = $product['product_id'];

            $product_delivery_days = $this->model_assets_product->GetProductByProductDeliveryDays($product_id, $product_store_id, $store_id);
            if (is_array($product_delivery_days) && count($product_delivery_days) > 0 && ($product_delivery_days['monday'] == 0 || $product_delivery_days['tuesday'] == 0 || $product_delivery_days['wednesday'] == 0 || $product_delivery_days['thursday'] == 0 || $product_delivery_days['friday'] == 0 || $product_delivery_days['saturday'] == 0 || $product_delivery_days['sunday'] == 0)) {
                $vendor_details = $this->model_user_user->getUser($product_delivery_days['merchant_id']);

                $delivery_time = $vendor_details['delivery_time'] != NULL && $vendor_details['delivery_time'] > 0 ? $vendor_details['delivery_time'] : 0;

                $new_delivery_time = date("Y-m-d H:i:s", strtotime('+' . $delivery_time . ' hours'));
                $new_delivery_times = new DateTime($new_delivery_time);

                if ($product_delivery_days['monday'] == 1) {
                    $new_delivery_times->modify('next monday');
                    $delivarbale_week_monday = $new_delivery_times->format('Y-m-d');
                    $product_delivery_days['monday_date'] = $delivarbale_week_monday;
                }

                if ($product_delivery_days['tuesday'] == 1) {
                    $new_delivery_times->modify('next tuesday');
                    $delivarbale_week_tuesday = $new_delivery_times->format('Y-m-d');
                    $product_delivery_days['tuesday_date'] = $delivarbale_week_tuesday;
                }

                if ($product_delivery_days['wednesday'] == 1) {
                    $new_delivery_times->modify('next wednesday');
                    $delivarbale_week_wednesday = $new_delivery_times->format('Y-m-d');
                    $product_delivery_days['wednesday_date'] = $delivarbale_week_wednesday;
                }

                if ($product_delivery_days['thursday'] == 1) {
                    $new_delivery_times->modify('next thursday');
                    $delivarbale_week_thursday = $new_delivery_times->format('Y-m-d');
                    $product_delivery_days['thursday_date'] = $delivarbale_week_thursday;
                }

                if ($product_delivery_days['friday'] == 1) {
                    $new_delivery_times->modify('next friday');
                    $delivarbale_week_friday = $new_delivery_times->format('Y-m-d');
                    $product_delivery_days['friday_date'] = $delivarbale_week_friday;
                }

                if ($product_delivery_days['saturday'] == 1) {
                    $new_delivery_times->modify('next saturday');
                    $delivarbale_week_saturday = $new_delivery_times->format('Y-m-d');
                    $product_delivery_days['saturday_date'] = $delivarbale_week_saturday;
                }

                if ($product_delivery_days['sunday'] == 1) {
                    $new_delivery_times->modify('next sunday');
                    $delivarbale_week_sunday = $new_delivery_times->format('Y-m-d');
                    $product_delivery_days['sunday_date'] = $delivarbale_week_sunday;
                }

                $log->write('vendor_details');
                $log->write($new_delivery_time);
                $log->write($vendor_details);
                $log->write('vendor_details');

                $results[] = $product_delivery_days;
            }
            $log->write('results');
            $log->write($results);
            $log->write('results');
        }

        $json['data'] = $results;
        $json['count'] = count($results);
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
