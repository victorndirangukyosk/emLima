<?php

class ControllerSaleAccountManagerUser extends Controller {

    private $error = [];

    public function index() {
        $log = new Log('error.log');
        $log->write($this->user->getId());
        $this->load->language('sale/accountmanageruser');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('user/accountmanager');

        $this->getList();

        $data['kondutoStatus'] = $this->config->get('config_konduto_status');
        $this->load->language('account/address');
        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        // $this->load->model('account/address');
    }

    public function export_excel() {
        $data = [];
        $this->load->model('report/excel');
        $this->model_report_excel->download_accountmanagercustomers_excel($data, $this->user->getId());
    }

    protected function getList() {
        $this->load->language('sale/accountmanageruser');


        if (isset($this->request->get['filter_company_name'])) {
            $filter_company_name = $this->request->get['filter_company_name'];
        } else {
            $filter_company_name = null;
        }

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

        if (isset($this->request->get['filter_telephone'])) {
            $filter_telephone = $this->request->get['filter_telephone'];
        } else {
            $filter_telephone = null;
        }

        if (isset($this->request->get['filter_customer_group_id'])) {
            $filter_customer_group_id = $this->request->get['filter_customer_group_id'];
        } else {
            $filter_customer_group_id = null;
        }

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = null;
        }

        if (isset($this->request->get['filter_approved'])) {
            $filter_approved = $this->request->get['filter_approved'];
        } else {
            $filter_approved = null;
        }

        if (isset($this->request->get['filter_ip'])) {
            $filter_ip = $this->request->get['filter_ip'];
        } else {
            $filter_ip = null;
        }

        if (isset($this->request->get['filter_date_added'])) {
            $filter_date_added = $this->request->get['filter_date_added'];
        } else {
            $filter_date_added = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'name';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['filter_company_name'])) {
            $url .= '&filter_company_name=' . urlencode(html_entity_decode($this->request->get['filter_company_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_telephone'])) {
            $url .= '&filter_telephone=' . urlencode(html_entity_decode($this->request->get['filter_telephone'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_customer_group_id'])) {
            $url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_approved'])) {
            $url .= '&filter_approved=' . $this->request->get['filter_approved'];
        }

        if (isset($this->request->get['filter_ip'])) {
            $url .= '&filter_ip=' . $this->request->get['filter_ip'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('sale/accountmanageruser', 'token=' . $this->session->data['token'] . $url, 'SSL'),
        ];

        $data['add'] = $this->url->link('sale/accountmanageruser/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['delete'] = $this->url->link('sale/accountmanageruser/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['customers'] = [];

        $filter_data = [
            'filter_company_name' => $filter_company_name,
            'filter_name' => $filter_name,
            'filter_email' => $filter_email,
            'filter_telephone' => $filter_telephone,
            'filter_status' => $filter_status,
            'filter_date_added' => $filter_date_added,
            'filter_ip' => $filter_ip,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        $customer_total = $this->model_user_accountmanager->getTotalAccountManagersCustomers($filter_data, $this->user->getId());

        $results = $this->model_user_accountmanager->getAccountManagersCustomers($filter_data, $this->user->getId());

        //echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            $country_code = '+' . $this->config->get('config_telephone_code');

            $data['customers'][] = [
                'user_id' => $result['customer_id'],
                'name' => $result['name'],
                'email' => $result['email'],
                'telephone' => $country_code . $result['telephone'],
                'status' => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'ip' => $result['ip'],
                'company_name' => $result['company_name'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'edit' => $this->url->link('sale/accountmanageruser/edit', 'token=' . $this->session->data['token'] . '&user_id=' . $result['customer_id'] . $url, 'SSL'),
                'login_customer' => $this->url->link('sale/accountmanageruser/login', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . '&store_id=0', 'SSL'),
            ];
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_default'] = $this->language->get('text_default');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_email'] = $this->language->get('column_email');
        $data['column_telephone'] = $this->language->get('column_telephone');
        $data['column_customer_group'] = $this->language->get('column_customer_group');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_approved'] = $this->language->get('column_approved');
        $data['column_ip'] = $this->language->get('column_ip');
        $data['column_company_name'] = $this->language->get('column_company_name');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_action'] = $this->language->get('column_action');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_company'] = $this->language->get('entry_company');
        $data['entry_email'] = $this->language->get('entry_email');

        $data['entry_company_address'] = $this->language->get('entry_company_address');

        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_customer_group'] = $this->language->get('entry_customer_group');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_approved'] = $this->language->get('entry_approved');
        $data['entry_ip'] = $this->language->get('entry_ip');
        $data['entry_date_added'] = $this->language->get('entry_date_added');

        $data['button_approve'] = $this->language->get('button_approve');
        $data['button_verify'] = $this->language->get('button_verify');

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



        if (isset($this->request->get['filter_company_name'])) {
            $url .= '&filter_company_name=' . urlencode(html_entity_decode($this->request->get['filter_company_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_telephone'])) {
            $url .= '&filter_telephone=' . urlencode(html_entity_decode($this->request->get['filter_telephone'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_customer_group_id'])) {
            $url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_approved'])) {
            $url .= '&filter_approved=' . $this->request->get['filter_approved'];
        }

        if (isset($this->request->get['filter_ip'])) {
            $url .= '&filter_ip=' . $this->request->get['filter_ip'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if ('ASC' == $order) {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_name'] = $this->url->link('sale/accountmanageruser', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
        $data['sort_email'] = $this->url->link('sale/accountmanageruser', 'token=' . $this->session->data['token'] . '&sort=c.email' . $url, 'SSL');
        $data['sort_status'] = $this->url->link('sale/accountmanageruser', 'token=' . $this->session->data['token'] . '&sort=c.status' . $url, 'SSL');
        $data['sort_ip'] = $this->url->link('sale/accountmanageruser', 'token=' . $this->session->data['token'] . '&sort=c.ip' . $url, 'SSL');
        $data['sort_company_name'] = $this->url->link('sale/accountmanageruser', 'token=' . $this->session->data['token'] . '&sort=c.company_name' . $url, 'SSL');
        $data['sort_date_added'] = $this->url->link('sale/accountmanageruser', 'token=' . $this->session->data['token'] . '&sort=c.date_added' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['filter_company_name'])) {
            $url .= '&filter_company_name=' . urlencode(html_entity_decode($this->request->get['filter_company_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_telephone'])) {
            $url .= '&filter_telephone=' . urlencode(html_entity_decode($this->request->get['filter_telephone'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_customer_group_id'])) {
            $url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_approved'])) {
            $url .= '&filter_approved=' . $this->request->get['filter_approved'];
        }

        if (isset($this->request->get['filter_ip'])) {
            $url .= '&filter_ip=' . $this->request->get['filter_ip'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $customer_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('sale/accountmanageruser', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($customer_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($customer_total - $this->config->get('config_limit_admin'))) ? $customer_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $customer_total, ceil($customer_total / $this->config->get('config_limit_admin')));

        $data['filter_company_name'] = $filter_company_name;
        $data['filter_name'] = $filter_name;
        $data['filter_email'] = $filter_email;
        $data['filter_telephone'] = $filter_telephone;
        $data['filter_customer_group_id'] = $filter_customer_group_id;
        $data['filter_status'] = $filter_status;
        $data['filter_approved'] = $filter_approved;
        $data['filter_ip'] = $filter_ip;
        $data['filter_date_added'] = $filter_date_added;

        $this->load->model('sale/customer_group');

        $data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

        $this->load->model('setting/store');

        $data['stores'] = $this->model_setting_store->getStores();

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('sale/accountmanageruser.tpl', $data));
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'sale/accountmanageruser')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['username']) < 3) || (utf8_strlen($this->request->post['username']) > 20)) {
            $this->error['username'] = $this->language->get('error_username');
        }

        $user_info = $this->model_user_accountmanager->getUserByUsername($this->request->post['username']);

        if (!isset($this->request->get['user_id'])) {
            if ($user_info) {
                $this->error['warning'] = $this->language->get('error_exists');
            }
        } else {
            if ($user_info && ($this->request->get['user_id'] != $user_info['user_id'])) {
                $this->error['warning'] = $this->language->get('error_exists');
            }
        }

        if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
            $this->error['firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
            $this->error['lastname'] = $this->language->get('error_lastname');
        }

        if ($this->request->post['password'] || (!isset($this->request->get['user_id']))) {
            if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
                $this->error['password'] = $this->language->get('error_password');
            }

            if ($this->request->post['password'] != $this->request->post['confirm']) {
                $this->error['confirm'] = $this->language->get('error_confirm');
            }
        }

        // if($this->error) {
        //     $this->error['warning'] = $this->language->get('error_warning');
        // }

        return !$this->error;
    }

    public function getUnassignedCustomers() {
        $name = $this->request->post['name'];
        $json = NULL;
        if ($name != NULL) {
            $this->load->model('user/accountmanager');
            $results = $this->model_user_accountmanager->getUnassignedCustomers($name);
            $json = $results;
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function assigncustomer() {
        $log = new Log('error.log');
        $log->write($this->request->post['assigncustomer']);
        $log->write($this->request->post['account_manager_id']);
        $this->load->model('user/accountmanager');
        if (is_array($this->request->post['assigncustomer']) && count($this->request->post['assigncustomer']) > 0) {
            foreach ($this->request->post['assigncustomer'] as $customer_id) {
                $results = $this->model_user_accountmanager->AssignCustomersToAccountManager($customer_id, $this->request->post['account_manager_id']);
            }
        }
        $json = true;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getAccountManagerCustomers($account_manager_id) {
        $this->load->model('user/accountmanager');
        $results = $this->model_user_accountmanager->getCustomerByAccountManagerId($account_manager_id);
    }

    public function login() {
        $json = [];

        if (isset($this->request->get['customer_id'])) {
            $customer_id = $this->request->get['customer_id'];
        } else {
            $customer_id = 0;
        }

        $this->load->model('sale/customer');

        $customer_info = $this->model_sale_customer->getCustomer($customer_id);

        if ($customer_info) {
            $token = md5(mt_rand());

            $this->model_sale_customer->editToken($customer_id, $token);

            if (isset($this->request->get['store_id'])) {
                $store_id = $this->request->get['store_id'];
            } else {
                $store_id = 0;
            }

            $this->load->model('setting/store');

            $store_info = $this->model_setting_store->getStore($store_id);

            if ($store_info) {
                $this->response->redirect($store_info['url'] . 'index.php?path=account/login/adminRedirectLogin&token=' . $token);
            } else {
                $this->response->redirect(HTTP_CATALOG . 'index.php?path=account/login/adminRedirectLogin&token=' . $token);
            }
        } else {
            $this->load->language('error/not_found');

            $this->document->setTitle($this->language->get('heading_title'));

            $data['heading_title'] = $this->language->get('heading_title');

            $data['text_not_found'] = $this->language->get('text_not_found');

            $data['breadcrumbs'] = [];

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
            ];

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('error/not_found', 'token=' . $this->session->data['token'], 'SSL'),
            ];

            $data['header'] = $this->load->controller('common/header');
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['footer'] = $this->load->controller('common/footer');

            $this->response->setOutput($this->load->view('error/not_found.tpl', $data));
        }
    }

}
