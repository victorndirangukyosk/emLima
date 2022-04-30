<?php

class ControllerPaymentMod extends Controller {

    public function index() {
        $data['button_confirm'] = $this->language->get('button_confirm');

        $this->load->language('payment/mod');

        $data['text_loading'] = $this->language->get('text_loading');

        $data['continue'] = $this->url->link('checkout/success');
        $data['continue'] = $this->url->link('checkout/success');

        $this->load->model('checkout/order');
        $order_ids = array();
        foreach ($this->session->data['order_id'] as $key => $value) {
            /* FOR KWIKBASKET ORDERS */
            //if ($key == 75) {
            $order_ids[] = $value;
            $order_id = $value;
            if ($order_id != NULL) {
                $this->model_checkout_order->UpdateParentApproval($order_id);
            }
            //}
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/mod.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/payment/mod.tpl', $data);
        } else {
            return $this->load->view('default/template/payment/mod.tpl', $data);
        }
    }

    public function confirm() {
        $log = new Log('error.log');
        $log->write('mod confirm');
        $log->write($this->session->data['payment_method']['code']);
        if ('mod' == $this->session->data['payment_method']['code']) {
            $this->load->model('checkout/order');
            $this->load->model('account/customer');
            $this->load->model('payment/wallet');
            $this->load->model('account/credit');

            $log->write($this->session->data['order_id']);
            $log->write($this->config->get('mod_order_status_id'));
            
            /*DECIDING ORDER STATUS IF CUSTOMER SUB CUSTOMER*/
            $this->load->model('account/customer');
            $is_he_parents = $this->model_account_customer->CheckHeIsParent();
            $log->write('Order Confirm In MOD');
            $log->write($is_he_parents);
            $log->write('Order Confirm In MOD');

            $parent_customer_info = NULL;
            if ($is_he_parents != NULL && $is_he_parents > 0) {
                $parent_customer_info = $this->model_account_customer->getCustomer($is_he_parents);
            }

            $sub_customer_order_approval_required = 1;
            if (isset($parent_customer_info) && $parent_customer_info != NULL && is_array($parent_customer_info)) {
                $sub_customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
                $sub_customer_order_approval_required = $sub_customer_info['sub_customer_order_approval'];
            }

            $order_appoval_access = FALSE;
            if ($this->session->data['order_approval_access'] > 0 && $this->session->data['order_approval_access_role'] != NULL) {
                $order_appoval_access = TRUE;
            }

            $parent_approval = $is_he_parents == NULL || $order_appoval_access == TRUE || $sub_customer_order_approval_required == 0 ? 'Approved' : 'Pending';
            $order_status_id = $is_he_parents == NULL || $order_appoval_access == TRUE || $sub_customer_order_approval_required == 0 ? $this->config->get('cod_order_status_id') : 15;
            $order_status_id = $order_status_id > 0 ? $order_status_id : $this->config->get('mod_order_status_id');
            /*DECIDING ORDER STATUS IF CUSTOMER SUB CUSTOMER*/
            
            foreach ($this->session->data['order_id'] as $key => $value) {

                $customer_wallet_total = $this->model_account_credit->getTotalAmount();
                $order_info = $this->model_checkout_order->getOrder($value);

                if ($this->session->data['payment_wallet_method']['code'] == 'wallet' && $customer_wallet_total > 0 && $order_info['paid'] == 'N') {
                    $log->write($this->session->data['payment_wallet_method']);
                    $this->load->model('sale/order');

                    $totals = $this->model_sale_order->getOrderTotals($value);
                    $log->write($totals);
                    $total = 0;
                    foreach ($totals as $total) {
                        if ('total' == $total['code']) {
                            $total = $total['value'];
                            break;
                        }
                    }
                    if ($customer_wallet_total > 0 && $totals != NULL && $total > 0 && $total <= $customer_wallet_total) {
                        $this->model_payment_wallet->addTransactionCreditForHybridPayment($this->customer->getId(), "Wallet amount deducted #" . $value, $total, $value, 'Y', 0);
                        $this->model_sale_order->UpdatePaymentMethod($value, $this->session->data['payment_wallet_method']['title'], $this->session->data['payment_wallet_method']['code']);
                        $ret = $this->model_checkout_order->addOrderHistory($value, 1, 'Paid Through Wallet By Customer', FALSE, $this->customer->getId(), 'customer');
                    } elseif ($customer_wallet_total > 0 && $totals != NULL && $total > 0 && $total > $customer_wallet_total) {
                        $this->model_payment_wallet->addTransactionCreditForHybridPayment($this->customer->getId(), "Wallet amount deducted #" . $value, $customer_wallet_total, $value, 'P', $customer_wallet_total);
                        $this->model_sale_order->UpdatePaymentMethod($value, $this->session->data['payment_wallet_method']['title'], $this->session->data['payment_wallet_method']['code']);
                        $ret = $this->model_checkout_order->addOrderHistory($value, $order_status_id, 'Paid Partially Through Wallet By Customer', FALSE, $this->customer->getId(), 'customer');
                    }
                } elseif ((!isset($this->session->data['payment_wallet_method']['code']) || $this->session->data['payment_wallet_method']['code'] == 0 || ($customer_wallet_total <= 0 && $this->session->data['payment_wallet_method']['code'] == 'wallet')) && $order_info['paid'] == 'N') {
                    /* FOR KWIKBASKET ORDERS */
                    //if ($key == 75) {
                    $order_id = $value;
                    $order_info = $this->model_checkout_order->getOrder($order_id);
                    $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);

                    $ret = $this->model_checkout_order->addOrderHistory($order_id, $order_status_id, 'mPesa On Delivery By Customer', TRUE, $customer_info['customer_id'], 'customer');
                    //$this->load->controller('payment/cod/confirmnonkb');
                    //}
                }
            }
        }
    }

    public function apiConfirm($orders) {
        $log = new Log('error.log');
        $log->write('apiConfirm mod confirm');

        $this->load->model('checkout/order');

        $log->write($this->config->get('mod_order_status_id'));

        $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
        $comment = "";
        foreach ($orders as $order_id) {
            $log->write('mod loop' . $order_id);

            if ($customer_info != null)
                $ret = $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('mod_order_status_id'), $comment, true, $customer_info['customer_id'], 'customer');
            else
                $ret = $this->model_checkout_order->addOrderHistory($order_id, $order_status_id, $comment, true, 0, 'customer');

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
