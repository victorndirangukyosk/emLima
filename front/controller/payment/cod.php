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

            $order_id = NULL;
            foreach ($this->session->data['order_id'] as $key => $value) {
                if ($key == 75) {
                    $order_id = $value;
                    $log->write('cod loop:2' . $order_id);

                    $this->model_checkout_order->UpdateParentApproval($order_id);
                }
            }
            foreach ($this->session->data['order_id'] as $key => $value) {
                //if ($key == 75) {

                /* WALLET */
                $this->load->model('sale/order');
                $this->load->model('account/credit');
                $this->load->model('payment/wallet');

                $customer_wallet_total = $this->model_account_credit->getTotalAmount();
                if ($this->session->data['payment_wallet_method']['code'] == 'wallet' && $customer_wallet_total > 0) {
                    $log->write($this->session->data['payment_wallet_method']);
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
                        $ret = $this->model_checkout_order->addOrderHistory($value, 1, 'Paid Through Wallet By Customer', FALSE, $this->customer->getId(), 'customer');
                    }
                    if ($customer_wallet_total > 0 && $totals != NULL && $total > 0 && $total > $customer_wallet_total) {
                        $this->model_payment_wallet->addTransactionCreditForHybridPayment($this->customer->getId(), "Wallet amount deducted #" . $value, $customer_wallet_total, $value, 'P', $customer_wallet_total);
                        $ret = $this->model_checkout_order->addOrderHistory($value, $this->config->get('mod_order_status_id'), 'Paid Partially Through Wallet By Customer', FALSE, $this->customer->getId(), 'customer');
                    }
                }
                /* WALLET */

                $order_id = $value;
                $this->load->model('account/customer');
                $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
                $log->write('cod loop' . $order_id);

                $ret = $this->model_checkout_order->addOrderHistory($order_id, $order_status_id, '', true, $customer_info['customer_id'], 'customer');
                //}
            }
            //$this->load->controller('payment/cod/confirmnonkb');
            /* if ($order_id != NULL) {
              $this->model_checkout_order->UpdateParentApproval($order_id);
              } */
            /* foreach ($this->session->data['order_id'] as $order_id) {
              $log->write('cod loop' . $order_id);

              $ret = $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('cod_order_status_id'));
              } */
        }
    }

    public function confirmnonkb() {
        $log = new Log('error.log');
        $log->write('cod confirm non kb');
        $log->write($this->session->data['payment_method']['code']);
        //if ('cod' == $this->session->data['payment_method']['code']) {
        $this->load->model('checkout/order');

        $log->write($this->session->data['order_id']);
        $log->write($this->config->get('cod_order_status_id'));

        $this->load->model('account/customer');
        $is_he_parents = $this->model_account_customer->CheckHeIsParent();
        $log->write('Order Confirm In COD');
        $log->write($is_he_parents);
        $log->write('Order Confirm In COD');

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

        $order_id = NULL;
        foreach ($this->session->data['order_id'] as $key => $value) {
            /* FOR NON KWIKBASKET ORDERS */
            if ($key != 75) {
                $order_id = $value;
                $log->write('cod loop:2' . $order_id);

                $this->model_checkout_order->UpdateParentApproval($order_id);
            }
        }
        foreach ($this->session->data['order_id'] as $key => $value) {
            /* FOR NON KWIKBASKET ORDERS */
            $other_vendor_terms = FALSE;
            if (isset($this->session->data['accept_vendor_terms']) && $this->session->data['accept_vendor_terms'] == TRUE) {
                $other_vendor_terms = TRUE;
            }
            if ($key != 75) {
                $order_id = $value;
                $this->load->model('account/customer');
                $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
                $log->write('cod loop' . $order_id);

                $ret = $this->model_checkout_order->addOrderHistory($order_id, $order_status_id, '', true, $customer_info['customer_id'], 'customer', $other_vendor_terms);
            }
        }
        /* if ($order_id != NULL) {
          $this->model_checkout_order->UpdateParentApproval($order_id);
          } */
        /* foreach ($this->session->data['order_id'] as $order_id) {
          $log->write('cod loop' . $order_id);

          $ret = $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('cod_order_status_id'));
          } */
        //}
    }

    public function apiConfirm($orders) {
        $log = new Log('error.log');
        $log->write('apiConfirm cod confirm');
        $log->write($this->customer->getId());

        $this->load->model('checkout/order');

        $log->write($this->config->get('cod_order_status_id'));
        $is_he_parents = $this->model_account_customer->CheckHeIsParent();

        $parent_customer_info = NULL;
        if ($is_he_parents != NULL && $is_he_parents > 0) {
            $parent_customer_info = $this->model_account_customer->getCustomer($is_he_parents);
        }

        $sub_customer_order_approval_required = 1;
        if (isset($parent_customer_info) && $parent_customer_info != NULL && is_array($parent_customer_info)) {
            // $sub_customer_order_approval_required = $parent_customer_info['sub_customer_order_approval'];
            $sub_customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
            $sub_customer_order_approval_required = $sub_customer_info['sub_customer_order_approval'];
        }

        $order_appoval_access = FALSE;
        //session values are not getting
        // if ($this->session->data['order_approval_access'] > 0 && $this->session->data['order_approval_access_role'] != NULL) {
        //     $order_appoval_access = TRUE;
        // }

        $Approver_assigned = $this->model_account_customer->CheckApprover();
        $log->write($Approver_assigned['order_approval_access']);
        $log->write($Approver_assigned['order_approval_access_role']);

        if ($Approver_assigned['order_approval_access'] > 0 && $Approver_assigned['order_approval_access_role'] != NULL) {
            $order_appoval_access = TRUE;
        }



        $parent_approval = $is_he_parents == NULL || $order_appoval_access == TRUE || $sub_customer_order_approval_required == 0 ? 'Approved' : 'Pending';
        $order_status_id = $is_he_parents == NULL || $order_appoval_access == TRUE || $sub_customer_order_approval_required == 0 ? $this->config->get('cod_order_status_id') : 15;

        $order_id = NULL;
        foreach ($orders as $order_id) {
            $log->write('cod loop:2' . $order_id);

            $this->model_checkout_order->UpdateParentApproval($order_id);
        }
        $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
        $comment = "";
        foreach ($orders as $order_id) {
            $log->write('cod loop' . $order_id);

            // $ret = $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('cod_order_status_id'));
            // $ret = $this->model_checkout_order->addOrderHistory($order_id, $order_status_id);
            if ($customer_info != null)
                $ret = $this->model_checkout_order->addOrderHistory($order_id, $order_status_id, $comment, true, $customer_info['customer_id'], 'customer');
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

        /* if ($order_id != NULL) {
          $this->model_checkout_order->UpdateParentApprovalAPI($order_id, $Approver_assigned['order_approval_access'], $Approver_assigned['order_approval_access_role']);
          } */

        if ($parent_approval == "Pending") {//&& $sub_customer_order_approval_required == 1
            $this->load->model('checkout/order');
            $this->model_checkout_order->SendMailToParentUser($order_id);
        }
    }

}
