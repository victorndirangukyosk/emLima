<?php

class ControllerApiCustomerMod extends Controller {

    public function apiConfirm($orders) {
        $log = new Log('error.log');
        $log->write('apiConfirm mod confirm');

        $this->load->model('checkout/order');

        $log->write($this->config->get('mod_order_status_id'));

        $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
        $comment = "";
        foreach ($orders as $order_id) {
            $log->write('mod loop' . $order_id);
            $log->write('mod loop' . $order_id . 'front\controller\api\customer\mod.php');
            $log->write('mod loop' . $order_id . ' ' . $this->cart->getSubTotal() . ' ' . $this->cart->getTotal());

            if ($customer_info != null) {
                $ret = $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('mod_order_status_id'), $comment, true, $customer_info['customer_id'], 'customer');
            } else {
                $ret = $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('mod_order_status_id'), $comment, true, 0, 'customer');
            }
            $this->load->model('account/activity');
            $activity_data = [
                'customer_id' => $this->customer->getId(),
                'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
                'order_id' => $order_id,
            ];

            $this->model_account_activity->addActivity('order_account', $activity_data);
        }
    }

    public function apiConfirmHybridPayments($orders) {
        $log = new Log('error.log');
        $log->write('apiConfirm mod confirm');

        $this->load->model('checkout/order');

        $log->write($this->config->get('mod_order_status_id'));

        $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
        $comment = "";
        foreach ($orders as $order_id) {
            $log->write('mod loop' . $order_id);
            $log->write('mod loop' . $order_id . 'front\controller\api\customer\mod.php');
            $log->write('mod loop' . $order_id . ' ' . $this->cart->getSubTotal() . ' ' . $this->cart->getTotal());

            $this->load->model('account/credit');
            $customer_wallet_total = $this->model_account_credit->getTotalAmount();
            $order_info = $this->model_checkout_order->getOrder($order_id);

            if ($customer_wallet_total > 0 && $order_info['paid'] == 'N') {

                $this->load->model('sale/order');
                $totals = $this->model_sale_order->getOrderTotals($order_id);
                $log->write($totals);
                $total = 0;
                foreach ($totals as $total) {
                    if ('total' == $total['code']) {
                        $total = $total['value'];
                        break;
                    }
                }

                if ($customer_info != null) {
                    $ret = $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('mod_order_status_id'), $comment, true, $customer_info['customer_id'], 'customer');
                } else {
                    $ret = $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('mod_order_status_id'), $comment, true, 0, 'customer');
                }
                $this->load->model('account/activity');
                $activity_data = [
                    'customer_id' => $this->customer->getId(),
                    'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
                    'order_id' => $order_id,
                ];

                $this->model_account_activity->addActivity('order_account', $activity_data);
            }
        }
    }

}
