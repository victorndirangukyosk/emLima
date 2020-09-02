<?php

class Controllerwalletsadminwallet extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('wallets/admin_wallet');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/customer');

        $this->getList();
    }

    protected function getList()
    {
        $this->load->language('wallets/admin_wallet');

        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }

        if (isset($this->request->get['filter_email'])) {
            $filter_email = $this->request->get['filter_email'];
        } else {
            $filter_email = null;
        }

        if (isset($this->request->get['filter_order_id'])) {
            $filter_order_id = $this->request->get['filter_order_id'];
        } else {
            $filter_order_id = null;
        }

        if (isset($this->request->get['filter_type'])) {
            $filter_type = $this->request->get['filter_type'];
        } else {
            $filter_type = null;
        }

        if (isset($this->request->get['filter_date_added'])) {
            $filter_date_added = $this->request->get['filter_date_added'];
        } else {
            $filter_date_added = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'date_added';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name='.urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email='.urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id='.urlencode(html_entity_decode($this->request->get['filter_order_id'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_type'])) {
            $url .= '&filter_type='.urlencode(html_entity_decode($this->request->get['filter_type'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added='.$this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order='.$this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('wallets/admin_wallet', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        $data['customers'] = [];

        $filter_data = [
            'filter_name' => $filter_name,
            'filter_email' => $filter_email,
            'filter_order_id' => $filter_order_id,
            'filter_type' => $filter_type,
            'filter_date_added' => $filter_date_added,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        //echo "<pre>";print_r($filter_data);die;
        $customer_total = $this->model_sale_customer->getTotalAdminWallet($filter_data);

        //$results = $this->model_sale_customer->getCustomers($filter_data);

        $results = $this->model_sale_customer->getAllAdminCredits($filter_data);

        //$customer_total = count($results);
        // echo "<pre>";print_r($results);die;
        //echo "<pre>";print_r($customer_total);die;
        foreach ($results as $result) {
            $data['customers'][] = [
                'amount' => $result['amount'],
                'description' => $result['description'],
                'order_id' => $result['order_id'],
                'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added'])),
            ];
        }
        $data['entry_transaction_type'] = $this->language->get('entry_transaction_type');
        $data['heading_title'] = $this->language->get('heading_title');
        $data['column_transaction_type'] = $this->language->get('column_transaction_type');
        $data['text_list'] = $this->language->get('text_list');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_default'] = $this->language->get('text_default');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_amount'] = $this->language->get('column_amount');
        $data['column_description'] = $this->language->get('column_description');

        $data['column_order_id'] = $this->language->get('column_order_id');
        $data['column_credit_id'] = $this->language->get('column_credit_id');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_email'] = $this->language->get('column_email');
        $data['column_customer_group'] = $this->language->get('column_customer_group');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_approved'] = $this->language->get('column_approved');
        $data['column_ip'] = $this->language->get('column_ip');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_action'] = $this->language->get('column_action');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_customer_group'] = $this->language->get('entry_customer_group');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_approved'] = $this->language->get('entry_approved');
        $data['entry_ip'] = $this->language->get('entry_ip');
        $data['entry_date_added'] = $this->language->get('entry_date_added');
        $data['filter_order'] = $this->language->get('filter_order');
        $data['button_approve'] = $this->language->get('button_approve');
        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_show_filter'] = $this->language->get('button_show_filter');
        $data['button_hide_filter'] = $this->language->get('button_hide_filter');
        $data['button_login'] = $this->language->get('button_login');
        $data['button_unlock'] = $this->language->get('button_unlock');

        $data['token'] = $this->session->data['token'];

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array) $this->request->post['selected'];
        } else {
            $data['selected'] = [];
        }

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name='.urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email='.urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id='.urlencode(html_entity_decode($this->request->get['filter_order_id'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_type'])) {
            $url .= '&filter_type='.urlencode(html_entity_decode($this->request->get['filter_type'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added='.$this->request->get['filter_date_added'];
        }

        if ('ASC' == $order) {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        $data['sort_name'] = $this->url->link('wallets/admin_wallet', 'token='.$this->session->data['token'].'&sort=name'.$url, 'SSL');
        $data['sort_email'] = $this->url->link('wallets/admin_wallet', 'token='.$this->session->data['token'].'&sort=c.email'.$url, 'SSL');
        $data['sort_date_added'] = $this->url->link('wallets/admin_wallet', 'token='.$this->session->data['token'].'&sort=date_added'.$url, 'SSL');

        $data['link_order_id'] = $this->url->link('sale/order/info', 'token='.$this->session->data['token'].'&sort=date_added'.$url, 'SSL');

        $data['link_admin'] = $this->url->link('vendor/vendor/edit', 'token='.$this->session->data['token'].'&sort=date_added'.$url, 'SSL');

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name='.urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email='.urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id='.urlencode(html_entity_decode($this->request->get['filter_order_id'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_type'])) {
            $url .= '&filter_type='.urlencode(html_entity_decode($this->request->get['filter_type'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added='.$this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order='.$this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $customer_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('wallets/admin_wallet', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($customer_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($customer_total - $this->config->get('config_limit_admin'))) ? $customer_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $customer_total, ceil($customer_total / $this->config->get('config_limit_admin')));

        $data['filter_name'] = $filter_name;
        $data['filter_email'] = $filter_email;
        $data['filter_order_id'] = $filter_order_id;
        $data['filter_type'] = $filter_type;
        $data['filter_date_added'] = $filter_date_added;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('wallets/admin_wallet.tpl', $data));
    }

    public function autocomplete()
    {
        $json = [];

        if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_email'])) {
            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            if (isset($this->request->get['filter_email'])) {
                $filter_email = $this->request->get['filter_email'];
            } else {
                $filter_email = '';
            }

            if (isset($this->request->get['filter_order_id'])) {
                $filter_order_id = $this->request->get['filter_order_id'];
            } else {
                $filter_order_id = '';
            }

            if (isset($this->request->get['filter_type'])) {
                $filter_type = $this->request->get['filter_type'];
            } else {
                $filter_type = '';
            }

            $this->load->model('sale/customer');

            $filter_data = [
                'filter_name' => $filter_name,
                'filter_email' => $filter_email,
                'filter_order_id' => $filter_order_id,
                'filter_type' => $filter_type,
                'start' => 0,
                'limit' => 5,
            ];

            //$results = $this->model_sale_customer->getCustomers($filter_data);
            $results = $this->model_sale_customer->getUsers($filter_data);

            foreach ($results as $result) {
                $json[] = [
                    'customer_id' => $result['user_id'],
                    //'customer_id' => $result['customer_id'],
                    'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                    'firstname' => $result['firstname'],
                    'lastname' => $result['lastname'],
                    'email' => $result['email'],
                ];
            }
        }

        $sort_order = [];

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['name'];
        }

        array_multisort($sort_order, SORT_ASC, $json);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
