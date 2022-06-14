<?php

class ControllerApiCustomerWallet extends Controller {

    public function getWallet($args = []) {
        $this->load->language('api/wallet');
        //  echo "<pre>";print_r($args);die;
        // echo "<pre>";print_r($this->session->data['customer_id']);die;
        $json = [];

        if (!isset($this->session->data['customer_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('tool/image');
            $this->load->model('api/wallet');

            if (isset($args['filter_name'])) {
                $filter_name = $args['filter_name'];
            } else {
                $filter_name = null;
            }

            if (isset($args['filter_email'])) {
                $filter_email = $args['filter_email'];
            } else {
                $filter_email = null;
            }

            if (isset($args['filter_order_id'])) {
                $filter_order_id = $args['filter_order_id'];
            } else {
                $filter_order_id = null;
            }

            if (isset($args['filter_type'])) {
                $filter_type = $args['filter_type'];
            } else {
                $filter_type = null;
            }

            if (isset($args['filter_date_added'])) {
                $filter_date_added = $args['filter_date_added'];
            } else {
                $filter_date_added = null;
            }

            if (isset($args['date_from'])) {
                $date_from = $args['date_from'];
            } else {
                $date_from = null;
            }

            if (isset($args['date_to'])) {
                $date_to = $args['date_to'];
            } else {
                $date_to = null;
            }

            if (isset($args['limit'])) {
                $limit = $args['limit'];
            } else {
                $limit = 10;
            }

            if (isset($args['start'])) {
                $start = $args['start'];
            } else {
                $start = 0;
            }

            if (isset($args['sort'])) {
                $sort = $args['sort'];
            } else {
                $sort = 'date_added';
            }

            if (isset($args['order'])) {
                $order = $args['order'];
            } else {
                $order = 'DESC';
            }

            /* if (isset($args['page'])) {
              $page = $args['page'];
              } else {
              $page = 1;
              } */

            $filter_data = [
                'filter_name' => $filter_name,
                'filter_email' => $filter_email,
                'filter_order_id' => $filter_order_id,
                'filter_type' => $filter_type,
                'filter_date_added' => $filter_date_added,
                'date_from' => $date_from,
                'date_to' => $date_to,
                'sort' => $sort,
                'order' => $order,
                'start' => $start,
                'limit' => $limit,
            ];

            //$wallet_total = $this->model_api_wallet->getTotalVendorWallet($filter_data);
            $data['filterd_wallet_total'] = 0;

            $results = $this->model_api_wallet->getAllVendorCredits($filter_data);

            $resultsAmount = $this->model_api_wallet->getAllVendorCreditsTotal($filter_data);

            foreach ($resultsAmount as $resultsAmt) {
                $data['filterd_wallet_total'] += $resultsAmt['amount'];
            }

            $data['wallet_total'] = $this->currency->format($this->model_api_wallet->getCreditTotal($this->session->data['api_id']), $this->config->get('config_currency'));

            $data['wallets'] = [];

            //echo "<pre>";print_r($results);die;
            foreach ($results as $result) {
                $data['wallets'][] = [
                    // 'vendor_id' => $result['vendor_id'],
                    // 'name' => $result['name'],
                    //'email' => $result['email'],
                    //'amount' => $result['amount'],
                    'amount' => $this->currency->format($result['amount'], $this->config->get('config_currency')),
                    'description' => $result['description'],
                    'order_id' => $result['order_id'],
                    'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added'])),
                ];
            }
            //echo "<pre>";print_r($data['wallets']);die;
            //$json = $data['wallets'];

            $data['filterd_wallet_total'] = $this->currency->format($data['filterd_wallet_total']);

            $json = $data;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getAdminWallet($args = []) {
        $this->load->language('api/wallet');

        //echo "api/wallet";

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('tool/image');
            $this->load->model('api/wallet');

            if (isset($args['filter_name'])) {
                $filter_name = $args['filter_name'];
            } else {
                $filter_name = null;
            }

            if (isset($args['filter_email'])) {
                $filter_email = $args['filter_email'];
            } else {
                $filter_email = null;
            }

            if (isset($args['filter_order_id'])) {
                $filter_order_id = $args['filter_order_id'];
            } else {
                $filter_order_id = null;
            }

            if (isset($args['filter_type'])) {
                $filter_type = $args['filter_type'];
            } else {
                $filter_type = null;
            }

            if (isset($args['filter_date_added'])) {
                $filter_date_added = $args['filter_date_added'];
            } else {
                $filter_date_added = null;
            }

            if (isset($args['limit'])) {
                $limit = $args['limit'];
            } else {
                $limit = 10;
            }

            if (isset($args['start'])) {
                $start = $args['start'];
            } else {
                $start = 0;
            }

            if (isset($args['sort'])) {
                $sort = $args['sort'];
            } else {
                $sort = 'date_added';
            }

            if (isset($args['order'])) {
                $order = $args['order'];
            } else {
                $order = 'DESC';
            }

            $filter_data = [
                'filter_name' => $filter_name,
                'filter_email' => $filter_email,
                'filter_order_id' => $filter_order_id,
                'filter_type' => $filter_type,
                'filter_date_added' => $filter_date_added,
                'sort' => $sort,
                'order' => $order,
                'start' => $start,
                'limit' => $limit,
            ];

            $data['wallet_count'] = $this->model_api_wallet->getTotalAdminWallet($filter_data);
            //$results = $this->model_sale_customer->getCustomers($filter_data);

            $data['wallet_total'] = $this->currency->format($this->model_api_wallet->getAdminCreditTotal(), $this->config->get('config_currency'));

            $results = $this->model_api_wallet->getAllAdminCredits($filter_data);

            foreach ($results as $result) {
                $data['wallet'][] = [
                    'amount' => $result['amount'],
                    'description' => $result['description'],
                    'order_id' => $result['order_id'],
                    'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added'])),
                ];
            }

            $json = $data;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getCustomerWallet($args = []) {
        $this->load->language('api/wallet');

        //echo "api/wallet";

        $json = [];

        // if (!isset($this->session->data['api_id'])) {
        //     $json['error'] = $this->language->get('error_permission');
        // } else 
        {
            $this->load->model('tool/image');
            $this->load->model('api/wallet');

            if (isset($args['filter_name'])) {
                $filter_name = $args['filter_name'];
            } else {
                $filter_name = null;
            }

            if (isset($args['filter_email'])) {
                $filter_email = $args['filter_email'];
            } else {
                $filter_email = null;
            }

            if (isset($args['filter_order_id'])) {
                $filter_order_id = $args['filter_order_id'];
            } else {
                $filter_order_id = null;
            }

            if (isset($args['filter_type'])) {
                $filter_type = $args['filter_type'];
            } else {
                $filter_type = null;
            }

            if (isset($args['filter_date_added'])) {
                $filter_date_added = $args['filter_date_added'];
            } else {
                $filter_date_added = null;
            }

            if (isset($args['limit'])) {
                $limit = $args['limit'];
            } else {
                $limit = 10;
            }

            if (isset($args['start'])) {
                $start = $args['start'];
            } else {
                $start = 0;
            }

            if (isset($args['sort'])) {
                $sort = $args['sort'];
            } else {
                $sort = 'date_added';
            }

            if (isset($args['order'])) {
                $order = $args['order'];
            } else {
                $order = 'DESC';
            }

            $filter_data = [
                'filter_name' => $filter_name,
                'filter_email' => $filter_email,
                'filter_order_id' => $filter_order_id,
                'filter_type' => $filter_type,
                'filter_date_added' => $filter_date_added,
                'sort' => $sort,
                'order' => $order,
                'start' => $start,
                'limit' => $limit,
            ];

            $data['wallet_count'] = $this->model_api_wallet->getTotalCustomerWallet($filter_data);
            //$results = $this->model_sale_customer->getCustomers($filter_data);

            $data['wallet_total'] = $this->currency->format($this->model_api_wallet->getCustomerCreditTotal(), $this->config->get('config_currency'));

            $results = $this->model_api_wallet->getAllCustomerCredits($filter_data);

            foreach ($results as $result) {
                $data['wallet'][] = [
                    'amount' => $result['amount'],
                    'description' => $result['description'],
                    'order_id' => $result['order_id'],
                    'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added'])),
                ];
            }

            $json = $data;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getVendorWallet($args = []) {
        $this->load->language('api/wallet');

        //echo "api/wallet";

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('tool/image');
            $this->load->model('api/wallet');

            if (isset($args['filter_name'])) {
                $filter_name = $args['filter_name'];
            } else {
                $filter_name = null;
            }

            if (isset($args['filter_email'])) {
                $filter_email = $args['filter_email'];
            } else {
                $filter_email = null;
            }

            if (isset($args['filter_order_id'])) {
                $filter_order_id = $args['filter_order_id'];
            } else {
                $filter_order_id = null;
            }

            if (isset($args['filter_type'])) {
                $filter_type = $args['filter_type'];
            } else {
                $filter_type = null;
            }

            if (isset($args['filter_date_added'])) {
                $filter_date_added = $args['filter_date_added'];
            } else {
                $filter_date_added = null;
            }

            if (isset($args['limit'])) {
                $limit = $args['limit'];
            } else {
                $limit = 10;
            }

            if (isset($args['start'])) {
                $start = $args['start'];
            } else {
                $start = 0;
            }

            if (isset($args['sort'])) {
                $sort = $args['sort'];
            } else {
                $sort = 'date_added';
            }

            if (isset($args['order'])) {
                $order = $args['order'];
            } else {
                $order = 'DESC';
            }

            /* if (isset($args['page'])) {
              $page = $args['page'];
              } else {
              $page = 1;
              } */

            $filter_data = [
                'filter_name' => $filter_name,
                'filter_email' => $filter_email,
                'filter_order_id' => $filter_order_id,
                'filter_type' => $filter_type,
                'filter_date_added' => $filter_date_added,
                'sort' => $sort,
                'order' => $order,
                'start' => $start,
                'limit' => $limit,
            ];

            //$wallet_total = $this->model_api_wallet->getTotalVendorWallet($filter_data);

            $results = $this->model_api_wallet->getAllVendorCreditsByAdmin($filter_data);

            $data['wallet_count'] = $this->model_api_wallet->getTotalVendorWalletByAdmin($filter_data);

            $data['wallet_total'] = $this->currency->format($this->model_api_wallet->getVendorCreditTotal(), $this->config->get('config_currency'));

            $data['wallet'] = [];

            //echo "<pre>";print_r($results);die;
            foreach ($results as $result) {
                $data['wallet'][] = [
                    // 'vendor_id' => $result['vendor_id'],
                    // 'name' => $result['name'],
                    //'email' => $result['email'],
                    //'amount' => $result['amount'],
                    'amount' => $this->currency->format($result['amount'], $this->config->get('config_currency')),
                    'description' => $result['description'],
                    'order_id' => $result['order_id'],
                    'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added'])),
                ];
            }
            //echo "<pre>";print_r($data['wallets']);die;
            //$json = $data['wallets'];
            $json = $data;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
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
