<?php

require_once DIR_SYSTEM . '/vendor/mpesa-php-sdk-master/vendor/autoload.php';

class ControllerPaymentCod extends Controller {

    public function index() {
        $data['button_confirm'] = $this->language->get('button_confirm');

        $this->load->language('payment/cod');

        $data['text_loading'] = $this->language->get('text_loading');

        $data['continue'] = $this->url->link('checkout/success');
        $data['continue'] = $this->url->link('checkout/success');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/cod.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/payment/cod.tpl', $data);
        } else {
            return $this->load->view('default/template/payment/cod.tpl', $data);
        }
    }

    public function confirm() {
        $log = new Log('error.log');
        $log->write('cod confirm');
        $log->write($this->session->data['payment_method']['code']);
        if ('cod' == $this->session->data['payment_method']['code']) {
            $this->load->model('checkout/order');

            $log->write($this->session->data['order_id']);
            $log->write($this->config->get('cod_order_status_id'));

            $this->load->model('account/customer');
            $is_he_parents = $this->model_account_customer->CheckHeIsParent();
            $log->write('Order Confirm In COD');
            $log->write($is_he_parents);
            $log->write('Order Confirm In COD');
            $parent_approval = null == $is_he_parents ? 'Approved' : 'Pending';
            $order_status_id = null == $is_he_parents ? $this->config->get('cod_order_status_id') : 15;
            
            $order_id = NULL;
            foreach ($this->session->data['order_id'] as $order_id) {
                $log->write('cod loop' . $order_id);

                $ret = $this->model_checkout_order->addOrderHistory($order_id, $order_status_id);
            }
            if($order_id != NULL) {
            $this->model_checkout_order->UpdateParentApproval($order_id);
            }
            /*foreach ($this->session->data['order_id'] as $order_id) {
                $log->write('cod loop' . $order_id);

                $ret = $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('cod_order_status_id'));
            }*/
        }
    }

    public function apiConfirm($orders) {
        $log = new Log('error.log');
        $log->write('apiConfirm cod confirm');

        $this->load->model('checkout/order');

        $log->write($this->config->get('cod_order_status_id'));
        $is_he_parents = $this->model_account_customer->CheckHeIsParent();
        $parent_approval = null == $is_he_parents ? 'Approved' : 'Pending';
        $order_status_id = null == $is_he_parents ? $this->config->get('cod_order_status_id') : 15;
       
        foreach ($orders as $order_id) {
            $log->write('cod loop' . $order_id);

           // $ret = $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('cod_order_status_id'));
            $ret = $this->model_checkout_order->addOrderHistory($order_id, $order_status_id);
        }
    }

}
