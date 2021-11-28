<?php

class ControllerPaymentPPPro extends Controller
{
    public function index()
    {
        $this->language->load('payment/pp_pro');

        $data['text_credit_card'] = $this->language->get('text_credit_card');
        $data['text_start_date'] = $this->language->get('text_start_date');
        $data['text_wait'] = $this->language->get('text_wait');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['entry_cc_type'] = $this->language->get('entry_cc_type');
        $data['entry_cc_number'] = $this->language->get('entry_cc_number');
        $data['entry_cc_start_date'] = $this->language->get('entry_cc_start_date');
        $data['entry_cc_expire_date'] = $this->language->get('entry_cc_expire_date');
        $data['entry_cc_cvv2'] = $this->language->get('entry_cc_cvv2');
        $data['entry_cc_issue'] = $this->language->get('entry_cc_issue');

        $data['help_start_date'] = $this->language->get('help_start_date');
        $data['help_issue'] = $this->language->get('help_issue');

        $data['button_confirm'] = $this->language->get('button_confirm');

        $data['cards'] = [];

        $data['cards'][] = [
            'text' => 'Visa',
            'value' => 'VISA',
        ];

        $data['cards'][] = [
            'text' => 'MasterCard',
            'value' => 'MASTERCARD',
        ];

        $data['cards'][] = [
            'text' => 'Discover Card',
            'value' => 'DISCOVER',
        ];

        $data['cards'][] = [
            'text' => 'American Express',
            'value' => 'AMEX',
        ];

        $data['cards'][] = [
            'text' => 'Maestro',
            'value' => 'SWITCH',
        ];

        $data['cards'][] = [
            'text' => 'Solo',
            'value' => 'SOLO',
        ];

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

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/payment/pp_pro.tpl')) {
            return $this->load->view($this->config->get('config_template').'/template/payment/pp_pro.tpl', $data);
        } else {
            return $this->load->view('default/template/payment/pp_pro.tpl', $data);
        }
    }

    public function send()
    {
        if (!$this->config->get('pp_pro_transaction')) {
            $payment_type = 'Authorization';
        } else {
            $payment_type = 'Sale';
        }

        $this->load->model('checkout/order');

        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        $request = 'METHOD=DoDirectPayment';
        $request .= '&VERSION=51.0';
        $request .= '&USER='.urlencode($this->config->get('pp_pro_username'));
        $request .= '&PWD='.urlencode($this->config->get('pp_pro_password'));
        $request .= '&SIGNATURE='.urlencode($this->config->get('pp_pro_signature'));
        $request .= '&CUSTREF='.(int) $order_info['order_id'];
        $request .= '&PAYMENTACTION='.$payment_type;
        $request .= '&AMT='.$this->currency->format($order_info['total'], $order_info['currency_code'], false, false);
        $request .= '&CREDITCARDTYPE='.$this->request->post['cc_type'];
        $request .= '&ACCT='.urlencode(str_replace(' ', '', $this->request->post['cc_number']));
        $request .= '&CARDSTART='.urlencode($this->request->post['cc_start_date_month'].$this->request->post['cc_start_date_year']);
        $request .= '&EXPDATE='.urlencode($this->request->post['cc_expire_date_month'].$this->request->post['cc_expire_date_year']);
        $request .= '&CVV2='.urlencode($this->request->post['cc_cvv2']);

        if ('SWITCH' == $this->request->post['cc_type'] || 'SOLO' == $this->request->post['cc_type']) {
            $request .= '&ISSUENUMBER='.urlencode($this->request->post['cc_issue']);
        }

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
            $data['address'] = $address_info['address'].', '.$address_info['city'];
        } else {
            $data['address'] = '';
        }

        $request .= '&FIRSTNAME='.urlencode($order_info['firstname']);
        $request .= '&LASTNAME='.urlencode($order_info['lastname']);
        $request .= '&EMAIL='.urlencode($order_info['email']);
        $request .= '&PHONENUM='.urlencode($order_info['telephone']);
        $request .= '&IPADDRESS='.urlencode($this->request->server['REMOTE_ADDR']);
        $request .= '&STREET='.urlencode($address);
        $request .= '&CITY='.urlencode($city);
        $request .= '&STATE='.urlencode($this->config->get('config_state_code'));
        $request .= '&ZIP=';
        $request .= '&COUNTRYCODE='.urlencode($this->config->get('config_country_code'));
        $request .= '&CURRENCYCODE='.urlencode($order_info['currency_code']);
        $request .= '&BUTTONSOURCE='.urlencode('2.0_WPP');

        if ($this->cart->hasShipping()) {
            $request .= '&SHIPTONAME='.urlencode($order_info['shipping_name']);
            $request .= '&SHIPTOSTREET='.urlencode($order_info['shipping_address']);
            $request .= '&SHIPTOCITY=';
        } else {
            $request .= '&SHIPTONAME='.urlencode($order_info['firstname'].' '.$order_info['lastname']);
            $request .= '&SHIPTOSTREET='.urlencode($order_info['shipping_address']);
            $request .= '&SHIPTOCITY=';
        }

        $request .= '&SHIPTOSTATE='.$this->config->get('config_state_code');
        $request .= '&SHIPTOCOUNTRYCODE='.$this->config->get('config_country_code');
        $request .= '&SHIPTOZIP=';

        if (!$this->config->get('pp_pro_test')) {
            $curl = curl_init('https://api-3t.paypal.com/nvp');
        } else {
            $curl = curl_init('https://api-3t.sandbox.paypal.com/nvp');
        }

        curl_setopt($curl, CURLOPT_PORT, 443);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);

        $response = curl_exec($curl);

        curl_close($curl);

        if (!$response) {
            $this->log->write('DoDirectPayment failed: '.curl_error($curl).'('.curl_errno($curl).')');
        }

        $response_info = [];

        parse_str($response, $response_info);

        $json = [];

        if (('Success' == $response_info['ACK']) || ('SuccessWithWarning' == $response_info['ACK'])) {
            $message = '';

            if (isset($response_info['AVSCODE'])) {
                $message .= 'AVSCODE: '.$response_info['AVSCODE']."\n";
            }

            if (isset($response_info['CVV2MATCH'])) {
                $message .= 'CVV2MATCH: '.$response_info['CVV2MATCH']."\n";
            }

            if (isset($response_info['TRANSACTIONID'])) {
                $message .= 'TRANSACTIONID: '.$response_info['TRANSACTIONID']."\n";
            }

            $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('pp_pro_order_status_id'), $message, false);
            if ($this->request->server['HTTPS']) {
                $server = $this->config->get('config_ssl');
            } else {
                $server = $this->config->get('config_url');
            }

            $json['success'] = $server.'checkout-success'; //$this->url->link('checkout/success');
        } else {
            $json['error'] = $response_info['L_LONGMESSAGE0'];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
