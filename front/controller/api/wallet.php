<?php

class ControllerApiWallet extends Controller
{
    public function getWallet($args = [])
    {
        $this->load->language('api/wallet');
        //echo "<pre>";print_r($args);die;
        //echo "<pre>";print_r($this->session->data['api_id']);die;
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
             }*/

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

    public function getAdminWallet($args = [])
    {
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

    public function getCustomerWallet($args = [])
    {
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

    public function getVendorWallet($args = [])
    {
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
             }*/

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
}
