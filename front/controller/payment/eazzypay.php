<?php

class ControllerPaymentEazzypay extends Controller
{
    public function index()
    {
        $log = new Log('error.log');

        $data['button_confirm'] = $this->language->get('button_confirm');

        $this->load->language('payment/cod');

        $data['text_loading'] = $this->language->get('text_loading');

        /*start */

        $order_id = 0;
        foreach ($this->session->data['order_id'] as $key => $value) {
            $order_id = $value;
        }

        $data['amount'] = 0;
        $data['amount_in_decimal'] = 0;
        if (isset($order_id)) {
            $order_info = $this->model_checkout_order->getOrder($order_id);
            if (count($order_info) > 0) {
                $data['amount'] = (int) ($order_info['total'] * 100);
                // /$data['amount_in_decimal'] = round($order_info['total'],2);
            }
        }

        $data['token'] = false;

        $merchantCode = '4103509955';
        $merchantCode = $this->config->get('eazzypay_merchant_code');

        $data['merchantCode'] = $merchantCode;
        $data['merchant'] = $this->config->get('config_name');
        $data['order_id'] = $order_id;
        $data['currency'] = 'KSH';
        //$data['amount'] = '100';

        //$password = 'CEyAUD07ADKmtAziLDgjhxDli1pTWuMWc';
        $password = $this->config->get('eazzypay_password');

        $url = 'https://api-test.equitybankgroup.com/identity-uat/v1/token';

        /*$consumer_key = 'cyzrHQxXAcb4Avlk8Yj3oTV0UtjacNss';
        $secret = 'VJA82mfRWXlg0f7R';*/

        $consumer_key = $this->config->get('eazzypay_customer_key');
        $secret = $this->config->get('eazzypay_customer_secret');

        $enc = base64_encode("$consumer_key:$secret");

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-type: application/x-www-form-urlencoded', 'AUTHORIZATION: Basic '.$enc]);
        curl_setopt($curl, CURLOPT_POSTFIELDS,
            'username='.$merchantCode.'&password='.$password.'&merchant=merchant&grant_type=password');

        $result = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($result);

        $log->write('ControllerPaymentEazzypay');
        $log->write($result);

        //echo "<pre>";print_r($result);die;
        if (isset($result->access_token)) {
            $data['status'] = true;
            $data['token'] = $result->access_token;
            //$data['expiry'] = date("Y-m-dTh:i:s", time() + $result->expires_in);

            $p = date('Y-m-d h:i:s', strtotime('+1 days'));
            $data['expiry'] = $p;
            $data['expiry'] = str_replace(' ', 'T', $data['expiry']);
        } else {
            $data['status'] = false;

            $p = date('Y-m-d h:i:s', strtotime('+1 days'));
            $data['expiry'] = $p;
            $data['expiry'] = str_replace(' ', 'T', $data['expiry']);
        }

        /*end*/

        //echo "<pre>";print_r($data);die;
        $data['continue'] = $this->url->link('payment/eazzypay/confirm');
        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/payment/cod.tpl')) {
            return $this->load->view($this->config->get('config_template').'/template/payment/cod.tpl', $data);
        } else {
            return $this->load->view('default/template/payment/eazzypay.tpl', $data);
        }
    }

    public function confirm()
    {
        $log = new Log('error.log');
        $log->write('Eazzypay confirm');
        $log->write($this->request->get);
        /*2018-03-12 15:37:02 - Array
(
    [path] => payment/eazzypay/confirm
    [status] => paid
    [orderRef] => 282
    [amount] => 700.74
    [transactionId] => 807123454345
    [date] =>
    [description] => cards
    [hash] =>
)*/

        $log->write($this->session->data['payment_method']['code']);
        if ('eazzypay' == $this->session->data['payment_method']['code']) {
            $this->load->model('checkout/order');

            $log->write($this->session->data['order_id']);
            $log->write($this->config->get('eazzypay_order_status_id'));

            foreach ($this->session->data['order_id'] as $order_id) {
                $log->write('cod loop'.$order_id);

                $comment = isset($this->request->get['transactionId']) ? 'Trasnsaction id : #'.$this->request->get['transactionId'] : '';

                $ret = $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('eazzypay_order_status_id'), $comment);
            }
        }

        $this->response->redirect($this->url->link('checkout/success', '', 'SSL'));
    }

    public function apiConfirm($orders)
    {
        $log = new Log('error.log');
        $log->write('apiConfirm cod confirm');

        $this->load->model('checkout/order');

        $log->write($this->config->get('cod_order_status_id'));

        foreach ($orders as $order_id) {
            $log->write('cod loop'.$order_id);

            $ret = $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('cod_order_status_id'));
        }
    }
}
