<?php

class ControllerPaymentInterswitch extends Controller {

    public function index() {

        $this->load->language('payment/pesapal');
        $this->load->model('setting/setting');
        $this->load->model('payment/interswitch');
        $this->load->model('checkout/order');
        $this->load->model('account/customer');

        foreach ($this->session->data['order_id'] as $key => $value) {
            $order_id = $value;
        }

        $order_info = $this->model_checkout_order->getOrder($order_id);
        $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);

        if (count($order_info) > 0) {
            $amount = (int) ($order_info['total']);
        }

        $data['customer_number'] = $this->customer->getTelephone();

        $interswitch_creds = $this->model_setting_setting->getSetting('interswitch', 0);
        $data['interswitch_merchant_code'] = $interswitch_creds['interswitch_merchant_code'];
        $data['interswitch_pay_item_id'] = $interswitch_creds['interswitch_pay_item_id'];
        $data['interswitch_data_ref'] = base64_encode($order_info['customer_id'] . '_' . $order_id . '_' . $amount . '_' . date("Y-m-d h:i:s"));
        $data['interswitch_customer_id'] = $customer_info['customer_id'];
        $data['interswitch_customer_name'] = $customer_info['firstname'] . ' ' . $customer_info['lastname'];
        $data['interswitch_amount'] = $amount * 100;
        $log = new Log('error.log');
        $log->write($interswitch_creds['interswitch_merchant_code']);

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/interswitch.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/payment/interswitch.tpl', $data);
        } else {
            return $this->load->view('default/template/payment/interswitch.tpl', $data);
        }
    }

    public function InterswitchPaymentResponse() {
        $log = new Log('error.log');
        $log->write('interswitch payment response');
        $log->write($this->request->post['payment_response']);
        $log->write(base64_decode($this->request->post['payment_response']['txnref']));
        $txn_ref = base64_decode($this->request->post['payment_response']['txnref']);
        $txn_refl = explode('_', $txn_ref);
        $order_id = $txn_refl[1];
        $log->write($order_id);

        $this->load->language('payment/interswitch');
        $this->load->model('setting/setting');
        $this->load->model('payment/interswitch');
        $this->load->model('checkout/order');
        $this->load->model('account/customer');

        foreach ($this->session->data['order_id'] as $key => $value) {
            $order_id = $value;
        }
        $order_info = $this->model_checkout_order->getOrder($order_id);
        $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);

        if (00 == $this->request->post['payment_response']['resp']) {
            $this->response->redirect($this->url->link('checkout/success'));
        }

        if (00 != $this->request->post['payment_response']['resp']) {
            $this->response->redirect($this->url->link('checkout/success/orderfailed'));
        }

        $log->write('interswitch payment response');
    }

    public function status() {
        $this->response->redirect($this->url->link('checkout/success'));
    }

}
