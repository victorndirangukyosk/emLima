<?php

class ControllerApiShipping extends Controller
{
    public function address()
    {
        $this->load->language('api/shipping');

        // Delete old shipping address, shipping methods and method so not to cause any issues if there is an error
        unset($this->session->data['shipping_address']);
        unset($this->session->data['shipping_methods']);
        unset($this->session->data['shipping_method']);

        $json = [];

        if ($this->cart->hasShipping()) {
            if (!isset($this->session->data['api_id'])) {
                $json['error']['warning'] = $this->language->get('error_permission');
            } else {
                if (empty($this->request->post['shipping_city_id'])) {
                    $json['error']['shipping_city_id'] = $this->language->get('error_shipping_city');
                }

                if ((utf8_strlen(trim($this->request->post['shipping_name'])) < 1) || (utf8_strlen(trim($this->request->post['shipping_name'])) > 32)) {
                    $json['error']['shipping_name'] = $this->language->get('error_shipping_name');
                }

                if ((utf8_strlen(trim($this->request->post['shipping_contact_no'])) < 1) || (utf8_strlen(trim($this->request->post['shipping_contact_no'])) > 32)) {
                    $json['error']['shipping_contact_no'] = $this->language->get('error_shipping_contact_no');
                }

                if ((utf8_strlen(trim($this->request->post['shipping_address'])) < 3) || (utf8_strlen(trim($this->request->post['shipping_address'])) > 128)) {
                    $json['error']['shipping_address'] = $this->language->get('error_shipping_address');
                }

                if (!$json) {
                    $this->session->data['shipping_address'] = $this->request->post['shipping_address'];
                    $this->session->data['shipping_contact_no'] = $this->request->post['shipping_contact_no'];
                    $this->session->data['shipping_name'] = $this->request->post['shipping_name'];
                    $this->session->data['shipping_city_id'] = $this->request->post['shipping_city_id'];

                    $this->session->data['shipping_address'] = $this->request->post['shipping_flat_number'];
                    $this->session->data['shipping_contact_no'] = $this->request->post['shipping_building_name'];
                    $this->session->data['shipping_name'] = $this->request->post['shipping_landmark'];
                    $this->session->data['shipping_city_id'] = $this->request->post['shipping_zipcode'];

                    $json['success'] = $this->language->get('text_address');

                    unset($this->session->data['shipping_method']);
                    unset($this->session->data['shipping_methods']);
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function methods()
    {
        $this->load->language('api/shipping');

        // Delete past shipping methods and method just in case there is an error
        unset($this->session->data['shipping_methods']);
        unset($this->session->data['shipping_method']);
        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } elseif ($this->cart->hasShipping()) {
            if (!isset($this->session->data['shipping_address'])) {
                $json['error'] = $this->language->get('error_address');
            }

            if (!$json) {
                // Shipping Methods
                $json['shipping_methods'] = [];

                $this->load->model('extension/extension');

                $results = $this->model_extension_extension->getExtensions('shipping');

                $store_id = $this->request->post['store_id'];

                $store_info = $this->model_extension_extension->getStoreAllData($store_id);

                $delivery_by_owner = $store_info['delivery_by_owner'];

                $free_delivery_amount = $store_info['min_order_cod'];

                $store_total = $this->cart->getSubTotal($store_id);

                if ($store_total > $free_delivery_amount) {
                    $cost = 0;
                } else {
                    $cost = $store_info['cost_of_delivery'];
                }

                foreach ($results as $result) {
                    if ($this->config->get($result['code'].'_status')) {
                        if ('store_delivery' == $result['code']) {
                            if ($delivery_by_owner) {
                                $this->load->model('shipping/'.$result['code']);
                                $quote = $this->{'model_shipping_'.$result['code']}->getQuote($cost, $store_info['name']);
                                if ($quote) {
                                    $json['shipping_methods'][$result['code']] = [
                                        'title' => $quote['title'],
                                        'quote' => $quote['quote'],
                                        'sort_order' => $quote['sort_order'],
                                        'error' => $quote['error'],
                                    ];
                                }
                            }
                        } else {
                            $this->load->model('shipping/'.$result['code']);
                            $quote = $this->{'model_shipping_'.$result['code']}->getQuote($cost, $store_info['name']);

                            if ($quote) {
                                $json['shipping_methods'][$result['code']] = [
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

                foreach ($json['shipping_methods'] as $key => $value) {
                    $sort_order[$key] = $value['sort_order'];
                }

                array_multisort($sort_order, SORT_ASC, $json['shipping_methods']);

                if ($json['shipping_methods']) {
                    $this->session->data['shipping_methods'][$store_id] = $json['shipping_methods'];
                } else {
                    $json['error'] = $this->language->get('error_no_shipping');
                }
            }
        } else {
            $json['shipping_methods'] = [];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function method()
    {
        $this->load->language('api/shipping');

        // Delete old shipping method so not to cause any issues if there is an error
        unset($this->session->data['shipping_method']);

        $json = [];
        $store_id = $this->request->post['store_id'];

        //print_r($this->request->post);die;
        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            if ($this->cart->hasShipping()) {
                // Shipping Method
                if (empty($this->session->data['shipping_methods'])) {
                    $json['error'] = $this->language->get('error_no_shipping');
                } elseif (!$this->request->post['shipping_method']) {
                    $json['error'] = $this->language->get('error_method');
                } else {
                    $shipping = explode('.', $this->request->post['shipping_method']);
                    if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$store_id][$shipping[0]]['quote'][$shipping[1]])) {
                        $json['error']['warning'] = $this->language->get('error_shipping');
                    }
                }

                if (!$json) {
                    $this->session->data['shipping_method'][$store_id] = ['store_id' => $store_id, 'shipping_method' => $this->session->data['shipping_methods'][$store_id][$shipping[0]]['quote'][$shipping[1]]];
                    $json['success'] = $this->language->get('text_method');
                }
            } else {
                unset($this->session->data['shipping_address']);
                unset($this->session->data['shipping_method']);
                unset($this->session->data['shipping_methods']);
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
