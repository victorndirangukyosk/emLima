<?php

class ControllerPaymentPerpetualPayments extends Controller
{
    public function index()
    {
        $this->load->language('payment/perpetual_payments');

        $data['text_credit_card'] = $this->language->get('text_credit_card');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['entry_cc_number'] = $this->language->get('entry_cc_number');
        $data['entry_cc_start_date'] = $this->language->get('entry_cc_start_date');
        $data['entry_cc_expire_date'] = $this->language->get('entry_cc_expire_date');
        $data['entry_cc_cvv2'] = $this->language->get('entry_cc_cvv2');
        $data['entry_cc_issue'] = $this->language->get('entry_cc_issue');

        $data['help_start_date'] = $this->language->get('help_start_date');
        $data['help_issue'] = $this->language->get('help_issue');

        $data['button_confirm'] = $this->language->get('button_confirm');

        $data['months'] = [];

        for ($i = 1; $i <= 12; ++$i) {
            $data['months'][] = [
                'text' => strftime('%B', mktime(0, 0, 0, $i, 1, 2000)),
                'value' => sprintf('%02d', $i),
            ];
        }

        $today = getdate();

        $data['year_valid'] = [];

        for ($i = $today['year'] - 10; $i < $today['year'] + 1; ++$i) {
            $data['year_valid'][] = [
                'text' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
                'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
            ];
        }

        $data['year_expire'] = [];

        for ($i = $today['year']; $i < $today['year'] + 11; ++$i) {
            $data['year_expire'][] = [
                'text' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
                'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
            ];
        }

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/payment/perpetual_payments.tpl')) {
            return $this->load->view($this->config->get('config_template').'/template/payment/perpetual_payments.tpl', $data);
        } else {
            return $this->load->view('default/template/payment/perpetual_payments.tpl', $data);
        }
    }

    public function send()
    {
        $this->load->language('payment/perpetual_payments');

        $this->load->model('checkout/order');

        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

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

        $payment_data = [
            'auth_id' => $this->config->get('perpetual_payments_auth_id'),
            'auth_pass' => $this->config->get('perpetual_payments_auth_pass'),
            'card_num' => str_replace(' ', '', $this->request->post['cc_number']),
            'card_cvv' => $this->request->post['cc_cvv2'],
            'card_start' => $this->request->post['cc_start_date_month'].substr($this->request->post['cc_start_date_year'], 2),
            'card_expiry' => $this->request->post['cc_expire_date_month'].substr($this->request->post['cc_expire_date_year'], 2),
            'cust_name' => $order_info['firstname'].' '.$order_info['lastname'],
            'cust_address' => $address,
            'cust_country' => $this->config->get('config_country_code'),
            'cust_postcode' => '',
            'cust_tel' => $order_info['telephone'],
            'cust_ip' => $this->request->server['REMOTE_ADDR'],
            'cust_email' => $order_info['email'],
            'tran_ref' => $order_info['order_id'],
            'tran_amount' => $this->currency->format($order_info['total'], $order_info['currency_code'], 1.00000, false),
            'tran_currency' => $order_info['currency_code'],
            'tran_testmode' => $this->config->get('perpetual_payments_test'),
            'tran_type' => 'Sale',
            'tran_class' => 'MoTo',
        ];

        $curl = curl_init('https://secure.voice-pay.com/gateway/remote');

        curl_setopt($curl, CURLOPT_PORT, 443);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($payment_data));

        $response = curl_exec($curl);

        curl_close($curl);

        if ($response) {
            $data = explode('|', $response);

            if (isset($data[0]) && 'A' == $data[0]) {
                $message = '';

                if (isset($data[1])) {
                    $message .= $this->language->get('text_transaction').' '.$data[1]."\n";
                }

                if (isset($data[2])) {
                    if ('232' == $data[2]) {
                        $message .= $this->language->get('text_avs').' '.$this->language->get('text_avs_full_match')."\n";
                    } elseif ('400' == $data[2]) {
                        $message .= $this->language->get('text_avs').' '.$this->language->get('text_avs_not_match')."\n";
                    }
                }

                if (isset($data[3])) {
                    $message .= $this->language->get('text_authorisation').' '.$data[3]."\n";
                }

                $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('perpetual_payments_order_status_id'), $message, false);

                if ($this->request->server['HTTPS']) {
                    $server = $this->config->get('config_ssl');
                } else {
                    $server = $this->config->get('config_url');
                }

                $json['redirect'] = $server.'checkout-success';
            //$this->url->link('checkout/success');
            } else {
                $json['error'] = end($data);
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
