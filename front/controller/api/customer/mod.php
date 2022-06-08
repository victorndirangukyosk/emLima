<?php

class ControllerApiCustomerMod extends Controller {

    public function apiConfirm($orders) {
        $log = new Log('error.log');
        $log->write('apiConfirm mod confirm');

        $this->load->model('checkout/order');

        $log->write($this->config->get('mod_order_status_id'));

        $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
        $comment = "";

        /* DECIDING ORDER STATUS IF CUSTOMER SUB CUSTOMER */
        $this->load->model('account/customer');
        $is_he_parents = $this->model_account_customer->CheckHeIsParent();
        $log->write('Order Confirm In Mobile MOD');
        $log->write($is_he_parents);
        $log->write($this->customer->getOrderApprovalAccess());
        $log->write($this->customer->getOrderApprovalAccessRole());
        $log->write('Order Confirm In Mobile MOD');

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
        if ($this->customer->getOrderApprovalAccess() > 0 && $this->customer->getOrderApprovalAccessRole() != NULL) {
            $order_appoval_access = TRUE;
        }

        $parent_approval = $is_he_parents == NULL || $order_appoval_access == TRUE || $sub_customer_order_approval_required == 0 ? 'Approved' : 'Pending';
        $order_status_id = $is_he_parents == NULL || $order_appoval_access == TRUE || $sub_customer_order_approval_required == 0 ? $this->config->get('mod_order_status_id') : 15;
        $order_status_id = $order_status_id > 0 ? $order_status_id : $this->config->get('mod_order_status_id');
        /* DECIDING ORDER STATUS IF CUSTOMER SUB CUSTOMER */

        foreach ($orders as $order_id) {
            $log->write('mod loop' . $order_id);
            $log->write('mod loop' . $order_id . 'front\controller\api\customer\mod.php');
            $log->write('mod loop' . $order_id . ' ' . $this->cart->getSubTotal() . ' ' . $this->cart->getTotal());
            $log->write('mod loop order status id' . $order_status_id);

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

        /* DECIDING ORDER STATUS IF CUSTOMER SUB CUSTOMER */
        $this->load->model('account/customer');
        $is_he_parents = $this->model_account_customer->CheckHeIsParent();
        $log->write('Order Confirm In Mobile MOD');
        $log->write($is_he_parents);
        $log->write($this->customer->getOrderApprovalAccess());
        $log->write($this->customer->getOrderApprovalAccessRole());
        $log->write('Order Confirm In Mobile MOD');

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
        if ($this->customer->getOrderApprovalAccess() > 0 && $this->customer->getOrderApprovalAccessRole() != NULL) {
            $order_appoval_access = TRUE;
        }

        $parent_approval = $is_he_parents == NULL || $order_appoval_access == TRUE || $sub_customer_order_approval_required == 0 ? 'Approved' : 'Pending';
        $order_status_id = $is_he_parents == NULL || $order_appoval_access == TRUE || $sub_customer_order_approval_required == 0 ? $this->config->get('mod_order_status_id') : 15;
        $order_status_id = $order_status_id > 0 ? $order_status_id : $this->config->get('mod_order_status_id');
        /* DECIDING ORDER STATUS IF CUSTOMER SUB CUSTOMER */

        foreach ($orders as $order_id) {
            $log->write('mod loop' . $order_id);
            $log->write('mod loop' . $order_id . 'front\controller\api\customer\mod.php');
            $log->write('mod loop' . $order_id . ' ' . $this->cart->getSubTotal() . ' ' . $this->cart->getTotal());
            $log->write('mod loop order status id' . $order_status_id);

            $this->load->model('account/credit');
            $customer_wallet_total = $this->model_account_credit->getTotalAmount();
            $order_info = $this->model_checkout_order->getOrder($order_id);

            if ($customer_wallet_total > 0 && $order_info['paid'] == 'N') {

                $this->load->model('sale/order');
                $this->load->model('payment/wallet');

                $totals = $this->model_sale_order->getOrderTotals($order_id);
                $log->write($totals);
                $total = 0;
                foreach ($totals as $total) {
                    if ('total' == $total['code']) {
                        $total = $total['value'];
                        break;
                    }
                }

                if ($customer_info != NULL && $customer_wallet_total > 0 && $totals != NULL && $total > 0 && $total <= $customer_wallet_total) {
                    $this->model_payment_wallet->addTransactionCreditForHybridPayment($this->customer->getId(), "Wallet amount deducted #" . $order_id, $total, $order_id, 'Y', 0);
                    $this->model_sale_order->UpdatePaymentMethod($order_id, 'Wallet Payment', 'wallet');
                    $ret = $this->model_checkout_order->addOrderHistory($order_id, 1, 'Paid Through Wallet By Customer', FALSE, $this->customer->getId(), 'customer');
                } elseif ($customer_info != NULL && $customer_wallet_total > 0 && $totals != NULL && $total > 0 && $total > $customer_wallet_total) {
                    $this->model_payment_wallet->addTransactionCreditForHybridPayment($this->customer->getId(), "Wallet amount deducted #" . $value, $customer_wallet_total, $value, 'P', $customer_wallet_total);
                    $this->model_sale_order->UpdatePaymentMethod($order_id, 'Wallet Payment', 'wallet');
                    $ret = $this->model_checkout_order->addOrderHistory($order_id, 1, 'Paid Partially Through Wallet By Customer', FALSE, $this->customer->getId(), 'customer');
                }

                /* if ($customer_info != null) {
                  $ret = $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('mod_order_status_id'), $comment, true, $customer_info['customer_id'], 'customer');
                  } else {
                  $ret = $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('mod_order_status_id'), $comment, true, 0, 'customer');
                  } */
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
