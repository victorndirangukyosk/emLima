<?php

class ControllerSaleCustomerOTP extends Controller {

    private $error = [];

  

    public function index() {//customer_otp
        $this->load->language('sale/customer');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/customer');

        $this->getotpList();

        $data['kondutoStatus'] = $this->config->get('config_konduto_status');
        $this->load->language('account/address');
        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        // $this->load->model('account/address');
    }

    public function export_excel() {
        $data = [];
        $this->load->model('report/excel');


        if (isset($this->request->get['filter_company'])) {
            $filter_company = $this->request->get['filter_company'];
        } else {
            $filter_company = null;
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

        if (isset($this->request->get['filter_parent_customer'])) {
            $filter_parent_customer = $this->request->get['filter_parent_customer'];
        } else {
            $filter_parent_customer = null;
        }

        if (isset($this->request->get['filter_parent_customer_id'])) {
            $filter_parent_customer_id = $this->request->get['filter_parent_customer_id'];
        } else {
            $filter_parent_customer_id = null;
        }

        if (isset($this->request->get['filter_account_manager_name'])) {
            $filter_account_manager_name = $this->request->get['filter_account_manager_name'];
        } else {
            $filter_account_manager_name = null;
        }

        if (isset($this->request->get['filter_account_manager_id'])) {
            $filter_account_manager_id = $this->request->get['filter_account_manager_id'];
        } else {
            $filter_account_manager_id = null;
        }

        if (isset($this->request->get['filter_sub_customer_show'])) {
            $filter_sub_customer_show = $this->request->get['filter_sub_customer_show'];
        } else {
            $filter_sub_customer_show = null;
        }

        if (isset($this->request->get['filter_sub_customer_show'])) {
            $filter_sub_customer_show = $this->request->get['filter_sub_customer_show'];
        } else {
            $filter_sub_customer_show = null;
        }

        if (isset($this->request->get['filter_date_added'])) {
            $filter_date_added = $this->request->get['filter_date_added'];
        } else {
            $filter_date_added = null;
        }


        if (isset($this->request->get['filter_date_added_to'])) {
            $filter_date_added_to = $this->request->get['filter_date_added_to'];
        } else {
            $filter_date_added_to = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'c.date_added';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }



        $filter_data = [
            'filter_company' => $filter_company,
            'filter_name' => $filter_name,
            'filter_email' => $filter_email,
            'filter_telephone' => $filter_telephone,
            'filter_customer_group_id' => $filter_customer_group_id,
            'filter_status' => $filter_status,
            'filter_approved' => $filter_approved,
            'filter_date_added' => $filter_date_added,
            'filter_date_added_to' => $filter_date_added_to,
            'filter_ip' => $filter_ip,
            'filter_parent_customer' => $filter_parent_customer,
            'filter_parent_customer_id' => $filter_parent_customer_id,
            'filter_account_manager_name' => $filter_account_manager_name,
            'filter_account_manager_id' => $filter_account_manager_id,
            'filter_sub_customer_show' => $filter_sub_customer_show,
            'filter_monthyear_added' => $this->request->get['filter_monthyear_added'],
            'sort' => $sort,
            'order' => $order,
            
        ];

        // $filter_data = [
        //     'sort' => $sort,
        //     'order' => $order,
        // ];

        $this->model_report_excel->download_customer_excel($filter_data);
    }

    
  
   
    protected function getotpList() {
        $this->load->language('sale/customerotp');

        if (isset($this->request->get['filter_company'])) {
            $filter_company = $this->request->get['filter_company'];
        } else {
            $filter_company = null;
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

        if (isset($this->request->get['filter_parent_customer'])) {
            $filter_parent_customer = $this->request->get['filter_parent_customer'];
        } else {
            $filter_parent_customer = null;
        }

        if (isset($this->request->get['filter_parent_customer_id'])) {
            $filter_parent_customer_id = $this->request->get['filter_parent_customer_id'];
        } else {
            $filter_parent_customer_id = null;
        }

        if (isset($this->request->get['filter_account_manager_name'])) {
            $filter_account_manager_name = $this->request->get['filter_account_manager_name'];
        } else {
            $filter_account_manager_name = null;
        }

        if (isset($this->request->get['filter_account_manager_id'])) {
            $filter_account_manager_id = $this->request->get['filter_account_manager_id'];
        } else {
            $filter_account_manager_id = null;
        }

        if (isset($this->request->get['filter_sub_customer_show'])) {
            $filter_sub_customer_show = $this->request->get['filter_sub_customer_show'];
        } else {
            $filter_sub_customer_show = null;
        }

        if (isset($this->request->get['filter_sub_customer_show'])) {
            $filter_sub_customer_show = $this->request->get['filter_sub_customer_show'];
        } else {
            $filter_sub_customer_show = null;
        }

        if (isset($this->request->get['filter_date_added'])) {
            $filter_date_added = $this->request->get['filter_date_added'];
        } else {
            $filter_date_added = null;
        }

        if (isset($this->request->get['filter_date_added_to'])) {
            $filter_date_added_to = $this->request->get['filter_date_added_to'];
        } else {
            $filter_date_added_to = null;
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

        if (isset($this->request->get['filter_company'])) {
            $url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
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

        if (isset($this->request->get['filter_parent_customer'])) {
            $url .= '&filter_parent_customer=' . $this->request->get['filter_parent_customer'];
        }

        if (isset($this->request->get['filter_parent_customer_id'])) {
            $url .= '&filter_parent_customer_id=' . $this->request->get['filter_parent_customer_id'];
        }

        if (isset($this->request->get['filter_account_manager_name'])) {
            $url .= '&filter_account_manager_name=' . $this->request->get['filter_account_manager_name'];
        }

        if (isset($this->request->get['filter_account_manager_id'])) {
            $url .= '&filter_account_manager_id=' . $this->request->get['filter_account_manager_id'];
        }

        if (isset($this->request->get['filter_sub_customer_show'])) {
            $url .= '&filter_sub_customer_show=' . $this->request->get['filter_sub_customer_show'];
        }

        if (isset($this->request->get['filter_sub_customer_show'])) {
            $url .= '&filter_sub_customer_show=' . $this->request->get['filter_sub_customer_show'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }
        if (isset($this->request->get['filter_date_added_to'])) {
            $url .= '&filter_date_added_to=' . $this->request->get['filter_date_added_to'];
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
            'href' => $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'),
        ];

        $data['customers'] = [];

        $filter_data = [
            'filter_company' => $filter_company,
            'filter_name' => $filter_name,
            'filter_email' => $filter_email,
            'filter_telephone' => $filter_telephone,
            'filter_customer_group_id' => $filter_customer_group_id,
            'filter_status' => $filter_status,
            'filter_approved' => $filter_approved,
            'filter_date_added' => $filter_date_added,
            'filter_date_added_to' => $filter_date_added_to,
            'filter_ip' => $filter_ip,
            'filter_parent_customer' => $filter_parent_customer,
            'filter_parent_customer_id' => $filter_parent_customer_id,
            'filter_account_manager_name' => $filter_account_manager_name,
            'filter_account_manager_id' => $filter_account_manager_id,
            'filter_sub_customer_show' => $filter_sub_customer_show,
            'filter_monthyear_added' => $this->request->get['filter_monthyear_added'],
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        $customer_total = $this->model_sale_customer->getTotalOTPCustomers($filter_data);
        $customer_totals = $this->model_sale_customer->getTotalOTPCustomerss($filter_data);
        $customer_total = $customer_total + $customer_totals;

        $results = $this->model_sale_customer->getCustomersOTP($filter_data);
        $resultss = $this->model_sale_customer->getCustomersOTPS($filter_data);
        $results = array_merge($results, $resultss);

        //echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            $country_code = '+' . $this->config->get('config_telephone_code');
            if ($result['company_name']) {
                $result['company_name'] = ' (' . $result['company_name'] . ')';
            }
            if (isset($result['telephone']) && $result['telephone'] != NULL) {
                $customer_phone = $country_code . $result['telephone'];
            } else {
                $customer_phone = NULL;
            }

            $data['customers'][] = [
                'customer_id' => $result['customer_id'],
                'name' => $result['name'],
                'company_name' => $result['company_name'],
                'email' => $result['email'],
                'telephone' => $customer_phone,
                'source' => $result['source'],
                'otp' => $result['otp'],
                'type' => $result['type'],
                'otp_phone' => $result['otp_customer_id'],
                'created_at' => $result['otp_created_at'],
                'updated_at' => $result['otp_updated_at'],
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
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_action'] = $this->language->get('column_action');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_email'] = $this->language->get('entry_email');

        $data['entry_company_name'] = $this->language->get('entry_company_name');
        $data['entry_company_address'] = $this->language->get('entry_company_address');

        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_customer_group'] = $this->language->get('entry_customer_group');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_approved'] = $this->language->get('entry_approved');
        $data['entry_ip'] = $this->language->get('entry_ip');
        $data['entry_parent_customer'] = 'Parent Customer Name';
        $data['entry_account_manager_name'] = 'Account Manager Name';
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

        if (isset($this->request->get['filter_company'])) {
            $url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
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

        if (isset($this->request->get['filter_parent_customer'])) {
            $url .= '&filter_parent_customer=' . $this->request->get['filter_parent_customer'];
        }

        if (isset($this->request->get['filter_parent_customer_id'])) {
            $url .= '&filter_parent_customer_id=' . $this->request->get['filter_parent_customer_id'];
        }

        if (isset($this->request->get['filter_account_manager_name'])) {
            $url .= '&filter_account_manager_name=' . $this->request->get['filter_account_manager_name'];
        }

        if (isset($this->request->get['filter_account_manager_id'])) {
            $url .= '&filter_account_manager_id=' . $this->request->get['filter_account_manager_id'];
        }

        if (isset($this->request->get['filter_sub_customer_show'])) {
            $url .= '&filter_sub_customer_show=' . $this->request->get['filter_sub_customer_show'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_added_to'])) {
            $url .= '&filter_date_added_to=' . $this->request->get['filter_date_added_to'];
        }

        if ('ASC' == $order) {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_name'] = $this->url->link('sale/customer/customer_otp', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
        $data['sort_email'] = $this->url->link('sale/customer/customer_otp', 'token=' . $this->session->data['token'] . '&sort=c.email' . $url, 'SSL');
        $data['sort_customer_group'] = $this->url->link('sale/customer/customer_otp', 'token=' . $this->session->data['token'] . '&sort=customer_group' . $url, 'SSL');
        $data['sort_status'] = $this->url->link('sale/customer/customer_otp', 'token=' . $this->session->data['token'] . '&sort=c.status' . $url, 'SSL');
        $data['sort_ip'] = $this->url->link('sale/customer/customer_otp', 'token=' . $this->session->data['token'] . '&sort=c.ip' . $url, 'SSL');
        $data['sort_date_added'] = $this->url->link('sale/customer/customer_otp', 'token=' . $this->session->data['token'] . '&sort=c.date_added' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['filter_company'])) {
            $url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
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

        if (isset($this->request->get['filter_parent_customer'])) {
            $url .= '&filter_parent_customer=' . $this->request->get['filter_parent_customer'];
        }

        if (isset($this->request->get['filter_parent_customer_id'])) {
            $url .= '&filter_parent_customer_id=' . $this->request->get['filter_parent_customer_id'];
        }

        if (isset($this->request->get['filter_account_manager_name'])) {
            $url .= '&filter_account_manager_name=' . $this->request->get['filter_account_manager_name'];
        }

        if (isset($this->request->get['filter_account_manager_id'])) {
            $url .= '&filter_account_manager_id=' . $this->request->get['filter_account_manager_id'];
        }

        if (isset($this->request->get['filter_sub_customer_show'])) {
            $url .= '&filter_sub_customer_show=' . $this->request->get['filter_sub_customer_show'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_added_to'])) {
            $url .= '&filter_date_added_to=' . $this->request->get['filter_date_added_to'];
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
        $pagination->url = $this->url->link('sale/customer/customer_otp', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($customer_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($customer_total - $this->config->get('config_limit_admin'))) ? $customer_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $customer_total, ceil($customer_total / $this->config->get('config_limit_admin')));

        $data['filter_company'] = $filter_company;
        $data['filter_name'] = $filter_name;
        $data['filter_email'] = $filter_email;
        $data['filter_telephone'] = $filter_telephone;
        $data['filter_customer_group_id'] = $filter_customer_group_id;
        $data['filter_status'] = $filter_status;
        $data['filter_approved'] = $filter_approved;
        $data['filter_ip'] = $filter_ip;
        $data['filter_parent_customer'] = $filter_parent_customer;
        $data['filter_parent_customer_id'] = $filter_parent_customer_id;
        $data['filter_account_manager_name'] = $filter_account_manager_name;
        $data['filter_account_manager_id'] = $filter_account_manager_id;
        $data['filter_sub_customer_show'] = $filter_sub_customer_show;
        $data['filter_date_added'] = $filter_date_added;
        $data['filter_date_added_to'] = $filter_date_added_to;

        $this->load->model('setting/store');

        $data['stores'] = $this->model_setting_store->getStores();

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $this->load->model('sale/customer_group');
        $data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

        $this->response->setOutput($this->load->view('sale/customer_otp_list.tpl', $data));
    }

     
      
 

}
