<?php

class ControllerPaymentFreeCheckout extends Controller
{
    public function index()
    {
        $data['button_confirm'] = $this->language->get('button_confirm');

        $data['continue'] = $this->url->link('checkout/success');

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/payment/free_checkout.tpl')) {
            return $this->load->view($this->config->get('config_template').'/template/payment/free_checkout.tpl', $data);
        } else {
            return $this->load->view('default/template/payment/free_checkout.tpl', $data);
        }
    }

    public function confirm()
    {
        if ('free_checkout' == $this->session->data['payment_method']['code']) {
            $this->load->model('checkout/order');

            //$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('free_checkout_order_status_id'));
            foreach ($this->session->data['order_id'] as $order_id) {
                $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('free_checkout_order_status_id'));
            }
        }
    }

    public function apiConfirm($orders)
    {
        $log = new Log('error.log');
        $log->write('apiConfirm free_checkout confirm');

        $this->load->model('checkout/order');

        foreach ($orders as $order_id) {
            $ret = $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('free_checkout_order_status_id'));
        }
    }
}
