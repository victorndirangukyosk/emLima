<?php

class ControllerApiPayment extends Controller
{
    public function address()
    {
        $this->load->language('api/payment');

        // Delete old payment address, payment methods and method so not to cause any issues if there is an error
        unset($this->session->data['payment_address']);
        unset($this->session->data['payment_methods']);
        unset($this->session->data['payment_method']);

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error']['warning'] = $this->language->get('error_permission');
        } else {
            if (!$json) {
                $json['success'] = $this->language->get('text_address');

                unset($this->session->data['payment_method']);
                unset($this->session->data['payment_methods']);
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function methods()
    {
        $this->load->language('api/payment');

        // Delete past shipping methods and method just in case there is an error
        unset($this->session->data['payment_methods']);
        unset($this->session->data['payment_method']);

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            if (!$json) {
                // Totals
                $total_data = [];
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
                        $this->load->model('total/'.$result['code']);

                        $this->{'model_total_'.$result['code']}->getTotal($total_data, $total, $taxes);
                    }
                }

                // Payment Methods
                $json['payment_methods'] = [];

                $this->load->model('extension/extension');

                $results = $this->model_extension_extension->getExtensions('payment');

                $recurring = $this->cart->hasRecurringProducts();

                foreach ($results as $result) {
                    if ($this->config->get($result['code'].'_status')) {
                        $this->load->model('payment/'.$result['code']);

                        $method = $this->{'model_payment_'.$result['code']}->getMethod($total);

                        if ($method) {
                            if ($recurring) {
                                if (method_exists($this->{'model_payment_'.$result['code']}, 'recurringPayments') && $this->{'model_payment_'.$result['code']}->recurringPayments()) {
                                    $json['payment_methods'][$result['code']] = $method;
                                }
                            } else {
                                $json['payment_methods'][$result['code']] = $method;
                            }
                        }
                    }
                }

                $sort_order = [];

                foreach ($json['payment_methods'] as $key => $value) {
                    $sort_order[$key] = $value['sort_order'];
                }

                array_multisort($sort_order, SORT_ASC, $json['payment_methods']);

                if ($json['payment_methods']) {
                    $this->session->data['payment_methods'] = $json['payment_methods'];
                } else {
                    $json['error'] = $this->language->get('error_no_payment');
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function method()
    {
        $this->load->language('api/payment');

        // Delete old payment method so not to cause any issues if there is an error
        unset($this->session->data['payment_method']);

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            // Payment Method
            if (empty($this->session->data['payment_methods'])) {
                $json['error'] = $this->language->get('error_no_payment');
            } elseif (!isset($this->request->post['payment_method'])) {
                $json['error'] = $this->language->get('error_method');
            } elseif (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
                $json['error'] = $this->language->get('error_method');
            }

            if (!$json) {
                $this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];

                $json['success'] = $this->language->get('text_method');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
