<?php

class ControllerCheckoutPaymentMethod extends Controller {

    public function index() {
        $this->load->language('checkout/checkout');

        // Totals
        $total_data = [];
        $total = 0;
        $taxes = $this->cart->getTaxes();

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

                $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
            }
        }

        // Payment Methods
        $method_data = [];

        $this->load->model('extension/extension');

        $results = $this->model_extension_extension->getExtensions('payment');

        //echo "<pre>";print_r($results);die;
        $recurring = $this->cart->hasRecurringProducts();

        foreach ($results as $result) {
            $log = new Log('error.log');
            $log->write('code');
            $log->write($result['code']);
            $log->write('code');
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
        $sort_order = [];

        foreach ($method_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $method_data);
        //    echo "<pre>";print_r($method_data);die;


        $this->session->data['payment_methods'] = $method_data;

        $data['text_payment_method'] = $this->language->get('text_payment_method');
        $data['text_comments'] = $this->language->get('text_comments');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['button_continue'] = $this->language->get('button_continue');

        if (empty($this->session->data['payment_methods'])) {
            $data['error_warning'] = sprintf($this->language->get('error_no_payment'), $this->url->link('information/contact'));
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['payment_methods'])) {
            $data['payment_methods'] = $this->session->data['payment_methods'];
        } else {
            $data['payment_methods'] = [];
        }
        /* if (isset($this->session->data['payment_method']['code'])) {
          $data['code'] = $this->session->data['payment_method']['code'];
          } else {
          $data['code'] = 'free_checkout';
          } */

        /* if(!array_key_exists($data['code'], $this->session->data['payment_methods'])) {
          $data['code'] = 'cod';
          } */

        if (isset($this->session->data['comment'])) {
            $data['comment'] = $this->session->data['comment'];
        } else {
            $data['comment'] = '';
        }

        $data['scripts'] = $this->document->getScripts();

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

        if (isset($this->session->data['agree'])) {
            $data['agree'] = $this->session->data['agree'];
        } else {
            $data['agree'] = '';
        }

        //echo "<pre>";print_r($data);die;
        $log = new Log('error.log');

        /* if (!empty($_SESSION['parent'])) {
          $log->write('FOR SUB USERS REMOVED OTHER PAYMENT METHODS');
          foreach ($data['payment_methods'] as $payment_method) {
          if ($payment_method['code'] != 'cod' && $payment_method['code'] != 'pezesha') {
          unset($data['payment_methods'][$payment_method['code']]);
          }
          }
          $log->write('FOR SUB USERS REMOVED OTHER PAYMENT METHODS');
          } else { */

        $log->write('getPaymentTerms');
        $log->write($this->customer->getPaymentTerms());
        if ($this->customer->getPaymentTerms() == 'Payment On Delivery' && $this->customer->getCustomerPezeshaId() == NULL && $this->customer->getCustomerPezeshauuId() == NULL) {
            foreach ($data['payment_methods'] as $payment_method) {
                if ($payment_method['code'] == 'wallet') {
                    $data['payment_wallet_methods'] = $payment_method;
                }
                if (/* $payment_method['code'] != 'wallet' && */ $payment_method['code'] != 'mod' && $payment_method['code'] != 'pesapal' && $payment_method['code'] != 'interswitch' && $payment_method['code'] != 'mpesa') {
                    unset($data['payment_methods'][$payment_method['code']]);
                }
            }
        } if ($this->customer->getPaymentTerms() == '7 Days Credit' || $this->customer->getPaymentTerms() == '15 Days Credit' || $this->customer->getPaymentTerms() == '30 Days Credit' && ($this->customer->getCustomerPezeshaId() == NULL && $this->customer->getCustomerPezeshauuId() == NULL)) {
            foreach ($data['payment_methods'] as $payment_method) {
                if ($payment_method['code'] == 'wallet') {
                    $data['payment_wallet_methods'] = $payment_method;
                }
                if ($payment_method['code'] != 'cod') {
                    unset($data['payment_methods'][$payment_method['code']]);
                }
            }
        } if ($this->customer->getCustomerPezeshaId() != NULL && $this->customer->getCustomerPezeshauuId() != NULL && $this->config->get('pezesha_status')) {
            foreach ($data['payment_methods'] as $payment_method) {
                if ($payment_method['code'] != 'pezesha') {
                    unset($data['payment_methods'][$payment_method['code']]);
                }
            }
        }
        $log->write('getPaymentTerms');
        //}

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/payment_method.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/checkout/payment_method.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/checkout/payment_method.tpl', $data));
        }
    }

    public function save() {
        $this->load->language('checkout/checkout');

        $json = [];

        // Validate cart has products and has stock.
        /* if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
          $json['redirect'] = $this->url->link('checkout/cart');
          } */

        // Validate minimum quantity requirements.
        $products = $this->cart->getProducts();

        foreach ($products as $product) {
            $product_total = 0;

            foreach ($products as $product_2) {
                if ($product_2['product_store_id'] == $product['product_store_id']) {
                    $product_total += $product_2['quantity'];
                }
            }

            /* if ($product['minimum'] > $product_total) {
              $json['redirect'] = $this->url->link('checkout/cart');

              break;
              } */
        }

        if (!isset($this->request->post['payment_method'])) {
            $json['error']['warning'] = $this->language->get('error_payment');
        } elseif (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
            $json['error']['warning'] = $this->language->get('error_payment');
        }

        if ($this->config->get('config_checkout_id')) {
            $this->load->model('assets/information');

            $information_info = $this->model_assets_information->getInformation($this->config->get('config_checkout_id'));

            if ($information_info && !isset($this->request->post['agree'])) {
                //$json['error']['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
            }
        }

        if (!$json) {
            $this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];
            $this->session->data['payment_wallet_method'] = $this->session->data['payment_methods'][$this->request->post['payment_wallet_method']];
            //$this->session->data['comment'] = strip_tags($this->request->post['comment']);
        }

        $this->load->model('account/credit');
        $customer_wallet_total = $this->model_account_credit->getTotalAmount();
        if ($this->request->post['payment_method'] == 'wallet' && $this->request->post['payment_wallet_method'] == 'wallet' && $this->cart->getTotal() > $customer_wallet_total) {
            $log = new Log('error.log');
            $log->write('payment_method');
            $log->write($this->request->post['payment_method']);
            $log->write($this->request->post['payment_wallet_method']);
            $log->write('payment_method');

            $this->session->data['wallet_balance_sufficient'] = FALSE;
            $json['error']['notice'] = 'Your Wallet Don\'t Have Sufficient Balance To Complete This Transaction, Please Select One More Payment Method!';
        }

        if ($this->request->post['payment_method'] != 'wallet' || $this->cart->getTotal() <= $customer_wallet_total) {
            $this->session->data['wallet_balance_sufficient'] = TRUE;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function clearpaymentmethod() {

        $this->load->model('account/credit');
        $customer_wallet_total = $this->model_account_credit->getTotalAmount();
        if ($customer_wallet_total > 0 && $this->cart->getTotal() <= $customer_wallet_total) {
            $this->session->data['payment_method'] = $this->session->data['payment_methods']['wallet'];
        }

        if ($customer_wallet_total > 0 && $this->cart->getTotal() > $customer_wallet_total) {
            $this->session->data['payment_wallet_method'] = $this->session->data['payment_methods']['wallet'];
            unset($this->session->data['payment_method']);
        }

        $log = new Log('error.log');
        $log->write('clearpaymentmethod');
        $log->write($this->session->data['payment_method']);
        $log->write($this->session->data['payment_wallet_method']);
        $log->write('clearpaymentmethod');
        unset($this->session->data['payment_method']);
        unset($this->session->data['payment_wallet_method']);
        unset($this->session->data['wallet_balance_sufficient']);
    }

    public function checkwalletbalancesufficient() {
        $json = [];
        if (isset($this->session->data['wallet_balance_sufficient']) && $this->session->data['wallet_balance_sufficient'] == TRUE) {
            $json['wallet_balance_sufficient'] = TRUE;
        }

        if (isset($this->session->data['wallet_balance_sufficient']) && $this->session->data['wallet_balance_sufficient'] == FALSE) {
            $json['wallet_balance_sufficient'] = FALSE;
        }

        if (!isset($this->session->data['wallet_balance_sufficient'])) {
            $json['wallet_balance_sufficient'] = TRUE;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
