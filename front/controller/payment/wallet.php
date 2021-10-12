<?php

 

class ControllerPaymentWallet extends Controller {

    public function index() {
        $data['button_confirm'] = $this->language->get('button_confirm');

        $this->load->language('payment/wallet');

        $data['text_loading'] = $this->language->get('text_loading');

        $data['continue'] = $this->url->link('checkout/success');
        $data['continue'] = $this->url->link('checkout/success');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/wallet.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/payment/wallet.tpl', $data);
        } else {
            return $this->load->view('default/template/payment/wallet.tpl', $data);
        }
    }

    public function confirm() {
        $log = new Log('error.log');
        $log->write('wallet confirm');
        $log->write($this->session->data['payment_method']['code']);
        if ('wallet' == $this->session->data['payment_method']['code']) {
            $this->load->model('checkout/order');

            $log->write($this->session->data['order_id']);
            $log->write($this->config->get('wallet_order_status_id'));

            $this->load->model('account/customer');
            $is_he_parents = $this->model_account_customer->CheckHeIsParent();
            $log->write('Order Confirm In WALLET');
            $log->write($is_he_parents);
            $log->write('Order Confirm In WALLET');

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
            $order_status_id = $is_he_parents == NULL || $order_appoval_access == TRUE || $sub_customer_order_approval_required == 0 ? $this->config->get('wallet_order_status_id') : 15;

            $order_id = NULL;
            foreach ($this->session->data['order_id'] as $key => $value) {
                if ($key == 75) {
                    $order_id = $value;
                    $log->write('wallet loop:2' . $order_id);

                    $this->model_checkout_order->UpdateParentApproval($order_id);
                }
            }
            foreach ($this->session->data['order_id'] as $key => $value) {
                if ($key == 75) {
                    $order_id = $value;
                    $this->load->model('account/customer');
                    $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
                    $log->write('wallet loop' . $order_id);

                    $ret = $this->model_checkout_order->addOrderHistory($order_id, $order_status_id, '', true, $customer_info['customer_id'], 'customer');
                }
            }
            $this->load->controller('payment/wallet/confirmnonkb');
            /* if ($order_id != NULL) {
              $this->model_checkout_order->UpdateParentApproval($order_id);
              } */
            /* foreach ($this->session->data['order_id'] as $order_id) {
              $log->write('wallet loop' . $order_id);

              $ret = $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('wallet_order_status_id'));
              } */
        }
    }

    public function confirmnonkb() {
        $log = new Log('error.log');
        $log->write('wallet confirm non kb');
        $log->write($this->session->data['payment_method']['code']);
        //if ('wallet' == $this->session->data['payment_method']['code']) {
        $this->load->model('checkout/order');

        $log->write($this->session->data['order_id']);
        $log->write($this->config->get('wallet_order_status_id'));

        $this->load->model('account/customer');
        $is_he_parents = $this->model_account_customer->CheckHeIsParent();
        $log->write('Order Confirm In WALLET');
        $log->write($is_he_parents);
        $log->write('Order Confirm In WALLET');

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
        $order_status_id = $is_he_parents == NULL || $order_appoval_access == TRUE || $sub_customer_order_approval_required == 0 ? $this->config->get('wallet_order_status_id') : 15;

        $order_id = NULL;
        foreach ($this->session->data['order_id'] as $key => $value) {
            /* FOR NON KWIKBASKET ORDERS */
            if ($key != 75) {
                $order_id = $value;
                $log->write('wallet loop:2' . $order_id);

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
                $log->write('wallet loop' . $order_id);

                $ret = $this->model_checkout_order->addOrderHistory($order_id, $order_status_id, '', true, $customer_info['customer_id'], 'customer', $other_vendor_terms);
            }
        }
        /* if ($order_id != NULL) {
          $this->model_checkout_order->UpdateParentApproval($order_id);
          } */
        /* foreach ($this->session->data['order_id'] as $order_id) {
          $log->write('wallet loop' . $order_id);

          $ret = $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('wallet_order_status_id'));
          } */
        //}
    }

    public function apiConfirm($orders) {
        $log = new Log('error.log');
        $log->write('apiConfirm wallet confirm');
        $log->write($this->customer->getId());

        $this->load->model('checkout/order');

        $log->write($this->config->get('wallet_order_status_id'));
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
        $order_status_id = $is_he_parents == NULL || $order_appoval_access == TRUE || $sub_customer_order_approval_required == 0 ? $this->config->get('wallet_order_status_id') : 15;

        $order_id = NULL;
        foreach ($orders as $order_id) {
            $log->write('wallet loop:2' . $order_id);

            $this->model_checkout_order->UpdateParentApproval($order_id);
        }
        $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
        $comment = "";
        foreach ($orders as $order_id) {
            $log->write('wallet loop' . $order_id);

            // $ret = $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('wallet_order_status_id'));
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
