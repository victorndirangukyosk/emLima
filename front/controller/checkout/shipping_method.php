<?php

class ControllerCheckoutShippingMethod extends Controller
{
    public function index()
    {
        $this->load->language('checkout/checkout');

        // Shipping Methods
        $method_data = [];

        $this->load->model('extension/extension');

        $results = $this->model_extension_extension->getExtensions('shipping');

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

        if ($store_total >= $free_delivery_amount) {
            $cost = 0;
        } else {
            $cost = $store_info['cost_of_delivery'];
        }
        // code = pickup
        // code = store_delivery
        //echo "<pre>";print_r($results);

        foreach ($results as $result) {
            if ($this->config->get($result['code'].'_status')) {
                if ('normal' == $result['code']) {
                    //echo "<pre>";print_r('normal');die;
                    //if ($delivery_by_owner) {
                    $this->load->model('shipping/'.$result['code']);

                    $quote = $this->{'model_shipping_'.$result['code']}->getQuote($cost, $store_info['name'], $store_id);
                    //$quote = $this->{'model_shipping_' . $result['code']}->getQuote();
                    if ($quote) {
                        $method_data[$result['code']] = [
                                    'title' => $quote['title'],
                                    'quote' => $quote['quote'],
                                    'sort_order' => $quote['sort_order'],
                                    'error' => $quote['error'],
                                ];
                    }
                    //}
                } elseif ('express' == $result['code']) {
                    //echo "<pre>";print_r('express');die;
                    $this->load->model('shipping/'.$result['code']);
                    $quote = $this->{'model_shipping_'.$result['code']}->getQuote($cost, $store_info['name'], $store_id);
                    //$quote = $this->{'model_shipping_' . $result['code']}->getQuote();

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
                        $quote = $this->{'model_shipping_'.$result['code']}->getQuote($cost, $store_info['name'], $store_id);
                        if ($quote) {
                            $method_data[$result['code']] = [
                                'title' => $quote['title'],
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
                    $quote = $this->{'model_shipping_'.$result['code']}->getQuote($cost, $store_info['name'], $store_id);
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
        $this->session->data['shipping_methods'][$store_id] = $method_data;

        $data['text_shipping_method'] = $this->language->get('text_shipping_method');
        $data['text_comments'] = $this->language->get('text_comments');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['button_continue'] = $this->language->get('button_continue');

        if (empty($this->session->data['shipping_methods'][$store_id])) {
            $data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['shipping_methods'][$store_id])) {
            $data['shipping_methods'] = $this->session->data['shipping_methods'][$store_id];
        } else {
            $data['shipping_methods'] = [];
        }

        $data['code'] = '';

        foreach ($method_data as $key => $value) {
            //echo "<pre>";print_r($value['quote'][$key]['code']);die;
            if (isset($value['quote'][$key]['code'])) {
                $data['code'] = $value['quote'][$key]['code'];
            }
            break;
        }

        //echo "<pre>";print_r($data['code']);die;

        if (isset($this->session->data['shipping_method'][$store_id])) {
            $found = false;

            foreach ($this->session->data['shipping_method'] as $key => $value) {
                if ($store_id == $key) {
                    if (isset($value['shipping_method']['code'])) {
                        $data['code'] = $value['shipping_method']['code'];
                        $found = true;
                        break;
                    }
                }
            }
            if (false === $found) {
                $data['code'] = '';
            }
        }

        //echo "<pre>";print_r($method_data);die;
        //$data['code'] = 'express.express';

        if (isset($this->session->data['comment'])) {
            $data['comment'] = $this->session->data['comment'];
        } else {
            $data['comment'] = '';
        }
        $data['store_id'] = $store_id;

        //echo "<pre>";print_r($data);die;
        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/checkout/shipping_method.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/checkout/shipping_method.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/checkout/shipping_method.tpl', $data));
        }
    }

    public function getShippingMethods($data)
    {
        $this->load->language('checkout/checkout');

        // Shipping Methods
        $method_data = [];

        $this->load->model('extension/extension');

        $results = $this->model_extension_extension->getExtensions('shipping');

        $store_id = $data['store_id'];

        $this->load->model('tool/image');

        $store_info = $this->model_tool_image->getStore($store_id);

        $delivery_by_owner = $store_info['delivery_by_owner'];

        $pickup_delivery = $store_info['store_pickup_timeslots'];

        $free_delivery_amount = $store_info['min_order_cod'];

        $store_total = $this->cart->getSubTotal($store_id);

        if ($store_total >= $free_delivery_amount) {
            $cost = 0;
        } else {
            $cost = $store_info['cost_of_delivery'];
        }
        // code = pickup
        // code = store_delivery
        //echo "<pre>";print_r($results);

        foreach ($results as $result) {
            if ($this->config->get($result['code'].'_status')) {
                if ('normal' == $result['code']) {
                    //echo "<pre>";print_r('normal');die;
                    //if ($delivery_by_owner) {
                    $this->load->model('shipping/'.$result['code']);

                    $quote = $this->{'model_shipping_'.$result['code']}->getQuote($cost, $store_info['name'], $store_id);
                    //$quote = $this->{'model_shipping_' . $result['code']}->getQuote();
                    if ($quote) {
                        $method_data[$result['code']] = [
                                    'title' => $quote['title'],
                                    'quote' => $quote['quote'],
                                    'sort_order' => $quote['sort_order'],
                                    'error' => $quote['error'],
                                ];
                    }
                    //}
                } elseif ('express' == $result['code']) {
                    //echo "<pre>";print_r('express');die;
                    $this->load->model('shipping/'.$result['code']);
                    $quote = $this->{'model_shipping_'.$result['code']}->getQuote($cost, $store_info['name'], $store_id);
                    //$quote = $this->{'model_shipping_' . $result['code']}->getQuote();

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
                                'title' => $quote['title'],
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
        $this->session->data['shipping_methods'][$store_id] = $method_data;

        $data['text_shipping_method'] = $this->language->get('text_shipping_method');
        $data['text_comments'] = $this->language->get('text_comments');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['button_continue'] = $this->language->get('button_continue');

        if (empty($this->session->data['shipping_methods'][$store_id])) {
            $data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['shipping_methods'][$store_id])) {
            $data['shipping_methods'] = $this->session->data['shipping_methods'][$store_id];
        } else {
            $data['shipping_methods'] = [];
        }

        return count($data['shipping_methods']);
    }

    public function save()
    {
        $this->load->language('checkout/checkout');

        $json = [];

        // Validate if shipping is required. If not the customer should not have reached this page.
        if (!$this->cart->hasShipping()) {
            $json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
        }

        // Validate cart has products and has stock.
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $json['redirect'] = $this->url->link('checkout/cart');
        }

        // Validate minimum quantity requirements.
        $products = $this->cart->getProducts();
        $store_id = $this->request->post['store_id'];
        foreach ($products as $product) {
            $product_total = 0;

            foreach ($products as $product_2) {
                if ($product_2['product_store_id'] == $product['product_store_id']) {
                    $product_total += $product_2['quantity'];
                }
            }

            /*if ($product['minimum'] > $product_total) {
                $json['redirect'] = $this->url->link('checkout/cart');
                break;
            }*/
        }

        if (!isset($this->request->post['shipping_method'])) {
            $json['error']['warning'] = $this->language->get('error_shipping');
        } else {
            $shipping = explode('.', $this->request->post['shipping_method']);
            if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$store_id][$shipping[0]]['quote'][$shipping[1]])) {
                $json['error']['warning'] = $this->language->get('error_shipping');
            }
        }
        $found = false;
        if (!$json) {
            if (isset($this->session->data['shipping_method'][$store_id])) {
                $json['shipping_name'] = $this->session->data['shipping_methods'][$store_id][$shipping[0]]['quote'][$shipping[1]]['title'];

                //save timslot adn time for express if shipping method

                if (isset($shipping[0]) && 'express' == $shipping[0]) {
                    $delivery_date = date('d-m-Y');

                    $settings = $this->getSettings('express', 0);

                    $timeDiff = $settings['express_how_much_time'];

                    $min = 0;
                    if ($timeDiff) {
                        $i = explode(':', $timeDiff);
                        $min = $min + $i[0] * 60 + $i[1]; //add difference minut to current time
                    }
                    $to = date('h:ia', strtotime('+'.$min.' minutes', strtotime(date('h:ia'))));

                    $delivery_timeslot = date('h:ia').' - '.$to;

                    $log = new Log('error.log');
                    $log->write('checkout/shipping_method');
                    $log->write($delivery_date);
                    $log->write($delivery_timeslot);
                    $log->write($store_id.'store_id');

                    $this->session->data['dates'][$store_id] = $delivery_date;
                    $this->session->data['timeslot'][$store_id] = $delivery_timeslot;

                    $json['express_minutes'] = 'Within '.$min.' minutes';
                } else {
                    $json['express_minutes'] = '';
                }

                foreach ($this->session->data['shipping_method'] as $key => $value) {
                    //print_r($key);
                    if ($store_id == $value['store_id']) {
                        $this->session->data['shipping_method'][$store_id]['store_id'] = $store_id;
                        $this->session->data['shipping_method'][$store_id]['shipping_method'] = $this->session->data['shipping_methods'][$store_id][$shipping[0]]['quote'][$shipping[1]];
                        $found = true;
                        break;
                    }
                }
                if (false === $found) {
                    $this->session->data['shipping_method'][$store_id] = ['store_id' => $store_id, 'shipping_method' => $this->session->data['shipping_methods'][$store_id][$shipping[0]]['quote'][$shipping[1]]];

                    $json['shipping_name'] = $this->session->data['shipping_method'][$store_id]['shipping_method']['title'];
                }
            } else {
                $this->session->data['shipping_method'][$store_id] = ['store_id' => $store_id, 'shipping_method' => $this->session->data['shipping_methods'][$store_id][$shipping[0]]['quote'][$shipping[1]]];

                $json['shipping_name'] = $this->session->data['shipping_method'][$store_id]['shipping_method']['title'];
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getSettings($code, $store_id = 0)
    {
        $this->load->model('setting/setting');

        return $this->model_setting_setting->getSetting($code, $store_id);
    }
}
