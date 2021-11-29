<?php

class ControllerPaymentPPPayflowIframe extends Controller
{
    public function index()
    {
        $this->load->model('checkout/order');
        $this->load->model('payment/pp_payflow_iframe');
        $this->load->model('localisation/country');
        $this->load->model('localisation/zone');

        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        if ($this->config->get('pp_payflow_iframe_test')) {
            $mode = 'TEST';
        } else {
            $mode = 'LIVE';
        }

        $payflow_url = 'https://payflowlink.paypal.com';

        if ('sale' == $this->config->get('pp_payflow_iframe_transaction_method')) {
            $transaction_type = 'S';
        } else {
            $transaction_type = 'A';
        }

        $secure_token_id = md5($this->session->data['order_id'].mt_rand().microtime());

        $this->model_payment_pp_payflow_iframe->addOrder($order_info['order_id'], $secure_token_id);

        //get address
        $this->load->model('account/address');
        $this->load->model('account/customer');

        $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);

        if ($customer_info) {
            $address_id = $customer_info['address_id'];
        } else {
            $address_id = 0;
        }

        $address_info = $this->model_account_address->getAddress($address_id);

        if ($address_info) {
            $city = $address_info['city'];
            $address = $address_info['address'].', '.$address_info['city'];
        } else {
            $city = '';
            $address = '';
        }

        $url_params = [
            'TENDER' => 'C',
            'TRXTYPE' => $transaction_type,
            'AMT' => $this->currency->format($order_info['total'], $order_info['currency_code'], false, false),
            'CURRENCY' => $order_info['currency_code'],
            'CREATESECURETOKEN' => 'Y',
            'SECURETOKENID' => $secure_token_id,
            'BILLTOFIRSTNAME' => $order_info['firstname'],
            'BILLTOLASTNAME' => $order_info['lastname'],
            'BILLTOSTREET' => trim($address),
            'BILLTOCITY' => $city,
            'BILLTOSTATE' => $this->config->get('config_state_code'),
            'BILLTOZIP' => '',
            'BILLTOCOUNTRY' => $this->config->get('config_country_code'),
        ];

        $url_params['SHIPTOFIRSTNAME'] = $order_info['shipping_name'];
        $url_params['SHIPTOLASTNAME'] = '';
        $url_params['SHIPTOSTREET'] = trim($order_info['shipping_address']);
        $url_params['SHIPTOCITY'] = '';
        $url_params['SHIPTOSTATE'] = $this->config->get('config_state_code');
        $url_params['SHIPTOZIP'] = '';
        $url_params['SHIPTOCOUNTRY'] = $this->config->get('config_country_code');

        $response_params = $this->model_payment_pp_payflow_iframe->call($url_params);

        if (isset($response_params['SECURETOKEN'])) {
            $secure_token = $response_params['SECURETOKEN'];
        } else {
            $secure_token = '';
        }

        $iframe_params = [
            'MODE' => $mode,
            'SECURETOKENID' => $secure_token_id,
            'SECURETOKEN' => $secure_token,
        ];

        $data['iframe_url'] = $payflow_url.'?'.http_build_query($iframe_params, '', '&');
        $data['checkout_method'] = $this->config->get('pp_payflow_iframe_checkout_method');
        $data['button_confirm'] = $this->language->get('button_confirm');

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/payment/pp_payflow_iframe.tpl')) {
            return $this->load->view($this->config->get('config_template').'/template/payment/pp_payflow_iframe.tpl', $data);
        } else {
            return $this->load->view('default/template/payment/pp_payflow_iframe.tpl', $data);
        }
    }

    public function paymentReturn()
    {
        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['url'] = $server.'checkout-success';
        //$this->url->link('checkout/success');

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/payment/pp_payflow_iframe_return.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/payment/pp_payflow_iframe_return.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/payment/pp_payflow_iframe_return.tpl', $data));
        }
    }

    public function paymentCancel()
    {
        $data['url'] = $this->url->link('checkout/checkout');

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/payment/pp_payflow_iframe_return.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/payment/pp_payflow_iframe_return.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/payment/pp_payflow_iframe_return.tpl', $data));
        }
    }

    public function paymentError()
    {
        $data['url'] = $this->url->link('checkout/checkout');

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/payment/pp_payflow_iframe_return.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/payment/pp_payflow_iframe_return.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/payment/pp_payflow_iframe_return.tpl', $data));
        }
    }

    public function paymentIpn()
    {
        $this->load->model('payment/pp_payflow_iframe');
        $this->load->model('checkout/order');

        $this->model_payment_pp_payflow_iframe->log('POST: '.print_r($this->request->post, 1));

        $order_id = $this->model_payment_pp_payflow_iframe->getOrderId($this->request->post['SECURETOKENID']);

        if ($order_id) {
            $order_info = $this->model_checkout_order->getOrder($order_id);

            $url_params = [
                'TENDER' => 'C',
                'TRXTYPE' => 'I',
                'ORIGID' => $this->request->post['PNREF'],
            ];

            $response_params = $this->model_payment_pp_payflow_iframe->call($url_params);

            if (0 == $order_info['order_status_id'] && '0' == $response_params['RESULT'] && 0 == $this->request->post['RESULT']) {
                $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('pp_payflow_iframe_order_status_id'));

                if ('S' == $this->request->post['TYPE']) {
                    $complete = 1;
                } else {
                    $complete = 0;
                }

                $data = [
                    'secure_token_id' => $this->request->post['SECURETOKENID'],
                    'transaction_reference' => $this->request->post['PNREF'],
                    'transaction_type' => $this->request->post['TYPE'],
                    'complete' => $complete,
                ];

                $this->model_payment_pp_payflow_iframe->updateOrder($data);

                $data = [
                    'order_id' => $order_id,
                    'type' => $this->request->post['TYPE'],
                    'transaction_reference' => $this->request->post['PNREF'],
                    'amount' => $this->request->post['AMT'],
                ];

                $this->model_payment_pp_payflow_iframe->addTransaction($data);
            }
        }

        $this->response->setOutput('Ok');
    }
}
