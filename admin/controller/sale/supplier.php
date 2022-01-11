<?php

class ControllerSaleSupplier extends Controller {

    private $error = [];

    public function index() {
        $this->load->language('sale/supplier');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('user/supplier');

        $this->getList();

        $data['kondutoStatus'] = $this->config->get('config_konduto_status');
        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');
    }

    public function export_excel() {
        $data = [];
        $this->load->model('report/excel');
        $this->model_report_excel->download_supplier_excel($data);
    }

    public function add() {
        $this->load->language('sale/supplier');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('user/supplier');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $this->request->post['ip'] = $this->request->server['REMOTE_ADDR'];
            $this->request->post['latitude'] = 0;
            $this->request->post['longitude'] = 0;
            $supplier_id = $this->model_user_farmer->addFarmer($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            // Add to activity log
            $log = new Log('error.log');
            $this->load->model('user/user_activity');

            $activity_data = [
                'user_id' => $this->user->getId(),
                'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
                'user_group_id' => $this->user->getGroupId(),
                'supplier_id' => $supplier_id,
            ];
            $log->write('supplier add');

            $this->model_user_user_activity->addActivity('supplier_add', $activity_data);

            $log->write('supplier add');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            if (isset($this->request->post['button']) and 'save' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('sale/supplier/edit', 'supplier_id=' . $supplier_id . '&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('sale/supplier/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            $this->response->redirect($this->url->link('sale/supplier', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function edit() {
        $log = new Log('error.log');
        $log->write(HTTPS_SERVER . 'index.php?path=common/farmer');
        $this->load->language('sale/supplier');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('user/farmer');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $supplier_info = $this->model_user_farmer->getFarmer($this->request->get['supplier_id']);
            $send_message = FALSE;
            if (isset($supplier_info) && $supplier_info['username'] == NULL && $supplier_info['password'] == NULL) {
                $log->write('supplier_info');
                $log->write($supplier_info);
                $send_message = TRUE;
            }
            $this->model_user_farmer->editFarmer($this->request->get['supplier_id'], $this->request->post);
            $farmer_info2 = $this->model_user_farmer->getFarmer($this->request->get['supplier_id']);
            $send_message2 = FALSE;
            if (isset($farmer_info2) && $farmer_info2['username'] != NULL && $farmer_info2['password'] != NULL) {
                $log->write('farmer_info2');
                $log->write($farmer_info2);
                $send_message2 = TRUE;
            }

            if ($send_message == TRUE && $send_message2 == TRUE) {
                $farmer_info['firstname'] = $this->request->post['username'];
                $farmer_info['password'] = $this->request->post['password'];
                $farmer_info['store_name'] = 'KwikBasket';
                $farmer_info['order_link'] = HTTPS_SERVER . 'index.php?path=common/farmer';
                $farmer_info['system_name'] = 'KwikBasket';

                $log->write('SMS SENDING');
                $sms_message = $this->emailtemplate->getSmsMessage('Customer', 'customer_10', $farmer_info);
                $log->write($sms_message);
                // send message here
                if ($this->emailtemplate->getSmsEnabled('Customer', 'customer_10')) {
                    $log->write('FARMER SMS NOTIFICATION');
                    $ret = $this->emailtemplate->sendmessage($this->request->post['telephone'], $sms_message);
                }

                try {
                    if ($this->emailtemplate->getEmailEnabled('Customer', 'customer_10')) {
                        $subject = $this->emailtemplate->getSubject('Customer', 'customer_10', $farmer_info);
                        $message = $this->emailtemplate->getMessage('Customer', 'customer_10', $farmer_info);

                        $mail = new mail($this->config->get('config_mail'));
                        $mail->setTo($this->request->post['email']);
                        $mail->setFrom($this->config->get('config_from_email'));
                        $mail->setSubject($subject);
                        $mail->setSender($this->config->get('config_name'));
                        $mail->setHtml($message);
                        $mail->send();
                    }
                } catch (Exception $e) {
                    
                }
            }

            $this->session->data['success'] = $this->language->get('text_success');

            // Add to activity log
            $log = new Log('error.log');
            $this->load->model('user/user_activity');

            $activity_data = [
                'user_id' => $this->user->getId(),
                'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
                'user_group_id' => $this->user->getGroupId(),
                'supplier_id' => $this->request->get['supplier_id'],
            ];
            $log->write('farmer edit');

            $this->model_user_user_activity->addActivity('farmer_edit', $activity_data);

            $log->write('farmer edit');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            if (isset($this->request->post['button']) and 'save' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('sale/supplier/edit', 'supplier_id=' . $this->request->get['supplier_id'] . '&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('sale/supplier/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            $this->response->redirect($this->url->link('sale/supplier', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('sale/supplier');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('user/farmer');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $supplier_id) {
                $this->model_user_farmer->deleteUser($supplier_id);

                // Add to activity log
                $log = new Log('error.log');
                $this->load->model('user/user_activity');

                $activity_data = [
                    'user_id' => $this->user->getId(),
                    'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
                    'user_group_id' => $this->user->getGroupId(),
                    'supplier_id' => $supplier_id,
                ];
                $log->write('farmer delete');

                $this->model_user_user_activity->addActivity('farmer_delete', $activity_data);

                $log->write('farmer delete');
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('sale/supplier', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    public function approve() {
        $this->load->language('sale/customer');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/customer');

        $customers = [];

        if (isset($this->request->post['selected'])) {
            $customers = $this->request->post['selected'];
        } elseif (isset($this->request->get['customer_id'])) {
            $customers[] = $this->request->get['customer_id'];
        }

        if ($customers && $this->validateApprove()) {
            $this->model_sale_customer->approve($this->request->get['customer_id']);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_email'])) {
                $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
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

            $this->response->redirect($this->url->link('sale/supplier', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    protected function getList() {
        $this->load->language('sale/supplier');

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

        if (isset($this->request->get['filter_mobile'])) {
            $filter_mobile = $this->request->get['filter_mobile'];
        } else {
            $filter_mobile = null;
        }

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = null;
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
            $sort = 'c.farmer_id';
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
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_mobile'])) {
            $url .= '&filter_mobile=' . urlencode(html_entity_decode($this->request->get['filter_mobile'], ENT_QUOTES, 'UTF-8'));
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
            'href' => $this->url->link('sale/supplier', 'token=' . $this->session->data['token'] . $url, 'SSL'),
        ];

        $data['add'] = $this->url->link('sale/supplier/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['delete'] = $this->url->link('sale/supplier/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['customers'] = [];

        $filter_data = [
            'filter_name' => $filter_name,
            'filter_email' => $filter_email,
            'filter_mobile' => $filter_mobile,
            'filter_status' => $filter_status,
            'filter_date_added' => $filter_date_added,
            'filter_ip' => $filter_ip,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        $customer_total = $this->model_user_supplier->getTotalSuppliers($filter_data);

        $results = $this->model_user_supplier->getSuppliers($filter_data);

        //echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            $country_code = '+' . $this->config->get('config_telephone_code');

            $data['customers'][] = [
                'supplier_id' => $result['farmer_id'],
                'name' => $result['name'],
                'email' => $result['email'],
                'mobile' => $result['mobile'] != NULL && strlen($result['mobile']) > 0 && $result['mobile'] > 0 ? $country_code . $result['mobile'] : '',
                'organization' => $result['organization'],
                'location' => $result['location'],
                'description' => $result['description'],
                'farmer_type' => $result['farmer_type'],
                'farm_size' => $result['farm_size'],
                'status' => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'ip' => $result['ip'],
                'created_at' => date($this->language->get('date_format_short'), strtotime($result['created_at'])),
                'edit' => $this->url->link('sale/supplier/edit', 'token=' . $this->session->data['token'] . '&supplier_id=' . $result['farmer_id'] . $url, 'SSL'),
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



        if (isset($this->request->get['filter_company'])) {
            $url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_mobile'])) {
            $url .= '&filter_mobile=' . urlencode(html_entity_decode($this->request->get['filter_mobile'], ENT_QUOTES, 'UTF-8'));
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

        $data['sort_name'] = $this->url->link('sale/supplier', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
        $data['sort_email'] = $this->url->link('sale/supplier', 'token=' . $this->session->data['token'] . '&sort=c.email' . $url, 'SSL');
        $data['sort_status'] = $this->url->link('sale/supplier', 'token=' . $this->session->data['token'] . '&sort=c.status' . $url, 'SSL');
        $data['sort_ip'] = $this->url->link('sale/supplier', 'token=' . $this->session->data['token'] . '&sort=c.ip' . $url, 'SSL');
        $data['sort_date_added'] = $this->url->link('sale/supplier', 'token=' . $this->session->data['token'] . '&sort=c.created_at' . $url, 'SSL');

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

        if (isset($this->request->get['filter_mobile'])) {
            $url .= '&filter_mobile=' . urlencode(html_entity_decode($this->request->get['filter_mobile'], ENT_QUOTES, 'UTF-8'));
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
        $pagination->url = $this->url->link('sale/supplier', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($customer_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($customer_total - $this->config->get('config_limit_admin'))) ? $customer_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $customer_total, ceil($customer_total / $this->config->get('config_limit_admin')));

        $data['filter_name'] = $filter_name;
        $data['filter_email'] = $filter_email;
        $data['filter_mobile'] = $filter_mobile;
        $data['filter_status'] = $filter_status;
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

        $this->response->setOutput($this->load->view('sale/supplier.tpl', $data));
    }

    protected function getForm() {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !isset($this->request->get['supplier_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_username'] = $this->language->get('entry_username');
        $data['entry_user_group'] = $this->language->get('entry_user_group');
        $data['entry_password'] = $this->language->get('entry_password');
        $data['entry_confirm'] = $this->language->get('entry_confirm');
        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_status'] = $this->language->get('entry_status');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_savenew'] = $this->language->get('button_savenew');
        $data['button_saveclose'] = $this->language->get('button_saveclose');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['tab_general'] = $this->language->get('tab_general');

        $data['token'] = $this->session->data['token'];

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['username'])) {
            $data['error_username'] = $this->error['username'];
        } else {
            $data['error_username'] = '';
        }

        if (isset($this->error['password'])) {
            $data['error_password'] = $this->error['password'];
        } else {
            $data['error_password'] = '';
        }

        if (isset($this->error['confirm'])) {
            $data['error_confirm'] = $this->error['confirm'];
        } else {
            $data['error_confirm'] = '';
        }

        if (isset($this->error['first_name'])) {
            $data['error_first_name'] = $this->error['first_name'];
        } else {
            $data['error_first_name'] = '';
        }

        if (isset($this->error['last_name'])) {
            $data['error_last_name'] = $this->error['last_name'];
        } else {
            $data['error_last_name'] = '';
        }

        if (isset($this->error['email'])) {
            $data['error_email'] = $this->error['email'];
        } else {
            $data['error_email'] = '';
        }

        if (isset($this->error['mobile'])) {
            $data['error_mobile'] = $this->error['mobile'];
        } else {
            $data['error_mobile'] = '';
        }

        if (isset($this->error['farm_size'])) {
            $data['error_farm_size'] = $this->error['farm_size'];
        } else {
            $data['error_farm_size'] = '';
        }

        if (isset($this->error['location'])) {
            $data['error_location'] = $this->error['location'];
        } else {
            $data['error_location'] = '';
        }

        if (isset($this->error['description'])) {
            $data['error_description'] = $this->error['description'];
        } else {
            $data['error_description'] = '';
        }

        if (isset($this->error['organization'])) {
            $data['error_organization'] = $this->error['organization'];
        } else {
            $data['error_organization'] = '';
        }

        $url = '';

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
            'href' => $this->url->link('user/user', 'token=' . $this->session->data['token'] . $url, 'SSL'),
        ];

        if (!isset($this->request->get['supplier_id'])) {
            $data['action'] = $this->url->link('sale/supplier/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $data['action'] = $this->url->link('sale/supplier/edit', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'] . $url, 'SSL');
        }

        $data['cancel'] = $this->url->link('sale/supplier', 'token=' . $this->session->data['token'] . $url, 'SSL');

        if (isset($this->request->get['supplier_id'])) {
            $user_info = $this->model_user_farmer->getFarmer($this->request->get['supplier_id']);
            $data['supplier_id'] = $user_info['farmer_id'];
        }

        if (isset($this->request->post['username'])) {
            $data['username'] = $this->request->post['username'];
        } elseif (!empty($user_info)) {
            $data['username'] = $user_info['username'];
        } else {
            $data['username'] = '';
        }

        if (isset($this->request->post['user_group_id'])) {
            $data['user_group_id'] = $this->request->post['user_group_id'];
        } elseif (!empty($user_info)) {
            $data['user_group_id'] = $user_info['user_group_id'];
        } else {
            $data['user_group_id'] = '';
        }

        $this->load->model('user/user_group');

        $data['user_groups'] = $this->model_user_user_group->getUserGroups();

        if (isset($this->request->post['password'])) {
            $data['password'] = $this->request->post['password'];
        } else {
            $data['password'] = '';
        }

        if (isset($this->request->post['confirm'])) {
            $data['confirm'] = $this->request->post['confirm'];
        } else {
            $data['confirm'] = '';
        }

        if (isset($this->request->post['username'])) {
            $data['username'] = $this->request->post['username'];
        } elseif (!empty($user_info)) {
            $data['username'] = $user_info['username'];
        } else {
            $data['uername'] = '';
        }

        if (isset($this->request->post['first_name'])) {
            $data['first_name'] = $this->request->post['first_name'];
        } elseif (!empty($user_info)) {
            $data['first_name'] = $user_info['first_name'];
        } else {
            $data['first_name'] = '';
        }

        if (isset($this->request->post['last_name'])) {
            $data['last_name'] = $this->request->post['last_name'];
        } elseif (!empty($user_info)) {
            $data['last_name'] = $user_info['last_name'];
        } else {
            $data['last_name'] = '';
        }

        if (isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } elseif (!empty($user_info)) {
            $data['email'] = $user_info['email'];
        } else {
            $data['email'] = '';
        }

        if (isset($this->request->post['mobile'])) {
            $data['mobile'] = $this->request->post['mobile'];
        } elseif (!empty($user_info)) {
            $data['mobile'] = $user_info['mobile'];
        } else {
            $data['mobile'] = '';
        }

        if (isset($this->request->post['farmer_type'])) {
            $data['farmer_type'] = $this->request->post['farmer_type'];
        } elseif (!empty($user_info)) {
            $data['farmer_type'] = $user_info['farmer_type'];
        } else {
            $data['farmer_type'] = '';
        }

        if (isset($this->request->post['farm_size'])) {
            $data['farm_size'] = $this->request->post['farm_size'];
        } elseif (!empty($user_info)) {
            $data['farm_size'] = $user_info['farm_size'];
        } else {
            $data['farm_size'] = '';
        }

        if (isset($this->request->post['farm_size_type'])) {
            $data['farm_size_type'] = $this->request->post['farm_size_type'];
        } elseif (!empty($user_info)) {
            $data['farm_size_type'] = $user_info['farm_size_type'];
        } else {
            $data['farm_size_type'] = '';
        }

        if (isset($this->request->post['irrigation_type'])) {
            $data['irrigation_type'] = $this->request->post['irrigation_type'];
        } elseif (!empty($user_info)) {
            $data['irrigation_type'] = $user_info['irrigation_type'];
        } else {
            $data['irrigation_type'] = '';
        }

        if (isset($this->request->post['location'])) {
            $data['location'] = $this->request->post['location'];
        } elseif (!empty($user_info)) {
            $data['location'] = $user_info['location'];
        } else {
            $data['location'] = '';
        }

        if (isset($this->request->post['description'])) {
            $data['description'] = $this->request->post['description'];
        } elseif (!empty($user_info)) {
            $data['description'] = $user_info['description'];
        } else {
            $data['description'] = '';
        }

        if (isset($this->request->post['organization'])) {
            $data['organization'] = $this->request->post['organization'];
        } elseif (!empty($user_info)) {
            $data['organization'] = $user_info['organization'];
        } else {
            $data['organization'] = '';
        }

        if (isset($this->request->post['image'])) {
            $data['image'] = $this->request->post['image'];
        } elseif (!empty($user_info)) {
            $data['image'] = $user_info['image'];
        } else {
            $data['image'] = '';
        }

        $this->load->model('tool/image');

        if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
        } elseif (!empty($user_info) && $user_info['image'] && is_file(DIR_IMAGE . $user_info['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($user_info['image'], 100, 100);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($user_info)) {
            $data['status'] = $user_info['status'];
        } else {
            $data['status'] = 0;
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('sale/supplier_form.tpl', $data));
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'sale/supplier')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['email']) <= 0) || (utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        $this->load->model('user/farmer');
        $farmer_info = $this->model_user_farmer->getFarmerByEmail($this->request->post['email']);

        if (!isset($this->request->get['supplier_id'])) {
            if ($farmer_info) {
                $this->error['warning'] = $this->language->get('error_exists');
            }
        } else {
            if ($farmer_info && ($this->request->get['supplier_id'] != $farmer_info['supplier_id'])) {
                $this->error['warning'] = $this->language->get('error_exists');
            }
        }

        if ((utf8_strlen(trim($this->request->post['username'])) < 1) || (utf8_strlen(trim($this->request->post['username'])) > 32)) {
            $this->error['username'] = $this->language->get('error_username');
        }

        $supplier_username_info = $this->model_user_farmer->getFarmerByUsername($this->request->post['username']);

        if (!isset($this->request->get['supplier_id'])) {
            if ($supplier_username_info) {
                $this->error['warning'] = 'Warning: Username is already in use!';
            }
        } else {
            if ($supplier_username_info && ($this->request->get['supplier_id'] != $supplier_username_info['farmer_id'])) {
                $this->error['warning'] = 'Warning: Username is already in use!';
            }
        }

        if ((utf8_strlen(trim($this->request->post['first_name'])) < 1) || (utf8_strlen(trim($this->request->post['first_name'])) > 32)) {
            $this->error['first_name'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen(trim($this->request->post['last_name'])) < 1) || (utf8_strlen(trim($this->request->post['last_name'])) > 32)) {
            $this->error['last_name'] = $this->language->get('error_lastname');
        }

        if ((utf8_strlen($this->request->post['mobile']) < 3) || (utf8_strlen($this->request->post['mobile']) > 32)) {
            $this->error['mobile'] = $this->language->get('error_telephone');
        }

        $supplier_mobile_info = $this->model_user_farmer->getFarmerByPhone($this->request->post['mobile']);

        if (!isset($this->request->get['supplier_id'])) {
            if ($supplier_mobile_info) {
                $this->error['warning'] = 'Warning: Mobile is already in use!';
            }
        } else {
            if ($supplier_mobile_info && ($this->request->get['supplier_id'] != $supplier_mobile_info['farmer_id'])) {
                $this->error['warning'] = 'Warning: Mobile is already in use!';
            }
        }

        if ((strlen(utf8_decode($this->request->post['mobile'])) < 3) || (strlen(utf8_decode($this->request->post['mobile'])) > 32) || preg_match('/[^\d]/is', $this->request->post['mobile'])) {
            $this->error['mobile'] = $this->language->get('error_telephone');
        }

        if ($this->request->post['farm_size'] <= 0 || strlen($this->request->post['farm_size']) <= 0 || preg_match('/[^\d]/is', $this->request->post['farm_size'])) {
            $this->error['farm_size'] = 'Farm Size must be greater than zero!';
        }

        if ((utf8_strlen(trim($this->request->post['location'])) < 1) || (utf8_strlen(trim($this->request->post['location'])) > 32)) {
            $this->error['location'] = 'Farm Location Required!';
        }

        if ((utf8_strlen(trim($this->request->post['description'])) < 1) || (utf8_strlen(trim($this->request->post['description'])) > 32)) {
            $this->error['description'] = 'Farm Description Required!';
        }

        if ((utf8_strlen(trim($this->request->post['organization'])) < 1) || (utf8_strlen(trim($this->request->post['organization'])) > 32)) {
            $this->error['organization'] = 'Farmer Organization Required!';
        }

        if ($this->request->post['password'] || (!isset($this->request->get['supplier_id']))) {
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

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'sale/supplier')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateApprove() {
        if (!$this->user->hasPermission('modify', 'sale/customer')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateUnlock() {
        if (!$this->user->hasPermission('modify', 'sale/customer')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateHistory() {
        if (!$this->user->hasPermission('modify', 'sale/customer')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!isset($this->request->post['comment']) || utf8_strlen($this->request->post['comment']) < 1) {
            $this->error['warning'] = $this->language->get('error_comment');
        }

        return !$this->error;
    }

    public function history() {
        $this->load->language('sale/customer');

        $this->load->model('sale/customer');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateHistory()) {
            $this->model_sale_customer->addHistory($this->request->get['customer_id'], $this->request->post['comment']);

            $data['success'] = $this->language->get('text_success');
        } else {
            $data['success'] = '';
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['text_no_results'] = $this->language->get('text_no_results');

        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_comment'] = $this->language->get('column_comment');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['histories'] = [];

        $results = $this->model_sale_customer->getHistories($this->request->get['customer_id'], ($page - 1) * 10, 10);

        foreach ($results as $result) {
            $data['histories'][] = [
                'comment' => $result['comment'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
            ];
        }

        $history_total = $this->model_sale_customer->getTotalHistories($this->request->get['customer_id']);

        $pagination = new Pagination();
        $pagination->total = $history_total;
        $pagination->page = $page;
        $pagination->limit = 10;
        $pagination->url = $this->url->link('sale/supplier/history', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($history_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($history_total - 10)) ? $history_total : ((($page - 1) * 10) + 10), $history_total, ceil($history_total / 10));

        $this->response->setOutput($this->load->view('sale/customer_history.tpl', $data));
    }

    public function credit() {
        $this->load->language('sale/customer');

        $this->load->model('sale/customer');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->user->hasPermission('modify', 'sale/customer')) {
            $this->model_sale_customer->addCredit($this->request->get['customer_id'], $this->request->post['description'], $this->request->post['amount']);

            $data['success'] = $this->language->get('text_success');
        } else {
            $data['success'] = '';
        }

        if (('POST' == $this->request->server['REQUEST_METHOD']) && !$this->user->hasPermission('modify', 'sale/customer')) {
            $data['error_warning'] = $this->language->get('error_permission');
        } else {
            $data['error_warning'] = '';
        }

        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_balance'] = $this->language->get('text_balance');

        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_description'] = $this->language->get('column_description');
        $data['column_amount'] = $this->language->get('column_amount');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['credits'] = [];

        $results = $this->model_sale_customer->getCredits($this->request->get['customer_id'], ($page - 1) * 10, 10);

        foreach ($results as $result) {
            $data['credits'][] = [
                'amount' => $this->currency->format($result['amount'], $this->config->get('config_currency')),
                'description' => $result['description'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
            ];
        }

        $data['balance'] = $this->currency->format($this->model_sale_customer->getCreditTotal($this->request->get['customer_id']), $this->config->get('config_currency'));

        $credit_total = $this->model_sale_customer->getTotalCredits($this->request->get['customer_id']);

        $pagination = new Pagination();
        $pagination->total = $credit_total;
        $pagination->page = $page;
        $pagination->limit = 10;
        $pagination->url = $this->url->link('sale/supplier/credit', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($credit_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($credit_total - 10)) ? $credit_total : ((($page - 1) * 10) + 10), $credit_total, ceil($credit_total / 10));

        $this->response->setOutput($this->load->view('sale/customer_credit.tpl', $data));
    }

    public function reward() {
        $this->load->language('sale/customer');

        $this->load->model('sale/customer');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->user->hasPermission('modify', 'sale/customer')) {
            $this->model_sale_customer->addReward($this->request->get['customer_id'], $this->request->post['description'], $this->request->post['points']);

            $data['success'] = $this->language->get('text_success');
        } else {
            $data['success'] = '';
        }

        if (('POST' == $this->request->server['REQUEST_METHOD']) && !$this->user->hasPermission('modify', 'sale/customer')) {
            $data['error_warning'] = $this->language->get('error_permission');
        } else {
            $data['error_warning'] = '';
        }

        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_balance'] = $this->language->get('text_balance');

        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_description'] = $this->language->get('column_description');
        $data['column_points'] = $this->language->get('column_points');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['rewards'] = [];

        $results = $this->model_sale_customer->getRewards($this->request->get['customer_id'], ($page - 1) * 10, 10);

        foreach ($results as $result) {
            $data['rewards'][] = [
                'points' => $result['points'],
                'description' => $result['description'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
            ];
        }

        $data['balance'] = $this->model_sale_customer->getRewardTotal($this->request->get['customer_id']);

        $reward_total = $this->model_sale_customer->getTotalRewards($this->request->get['customer_id']);

        $pagination = new Pagination();
        $pagination->total = $reward_total;
        $pagination->page = $page;
        $pagination->limit = 10;
        $pagination->url = $this->url->link('sale/customer/reward', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($reward_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($reward_total - 10)) ? $reward_total : ((($page - 1) * 10) + 10), $reward_total, ceil($reward_total / 10));

        $this->response->setOutput($this->load->view('sale/customer_reward.tpl', $data));
    }

    public function ip() {
        $this->load->language('sale/customer');

        $this->load->model('sale/customer');

        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_add_ban_ip'] = $this->language->get('text_add_ban_ip');
        $data['text_remove_ban_ip'] = $this->language->get('text_remove_ban_ip');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['column_ip'] = $this->language->get('column_ip');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_action'] = $this->language->get('column_action');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['ips'] = [];

        $results = $this->model_sale_customer->getIps($this->request->get['customer_id'], ($page - 1) * 10, 10);

        foreach ($results as $result) {
            $ban_ip_total = $this->model_sale_customer->getTotalBanIpsByIp($result['ip']);

            $data['ips'][] = [
                'ip' => $result['ip'],
                'total' => $this->model_sale_customer->getTotalCustomersByIp($result['ip']),
                'date_added' => date('d/m/y', strtotime($result['date_added'])),
                'filter_ip' => $this->url->link('sale/supplier', 'token=' . $this->session->data['token'] . '&filter_ip=' . $result['ip'], 'SSL'),
                'ban_ip' => $ban_ip_total,
            ];
        }

        $ip_total = $this->model_sale_customer->getTotalIps($this->request->get['customer_id']);

        $pagination = new Pagination();
        $pagination->total = $ip_total;
        $pagination->page = $page;
        $pagination->limit = 10;
        $pagination->url = $this->url->link('sale/supplier/ip', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'] . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($ip_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($ip_total - 10)) ? $ip_total : ((($page - 1) * 10) + 10), $ip_total, ceil($ip_total / 10));

        $this->response->setOutput($this->load->view('sale/customer_ip.tpl', $data));
    }

    public function addBanIp() {
        $this->load->language('sale/customer');

        $json = [];

        if (isset($this->request->post['ip'])) {
            if (!$this->user->hasPermission('modify', 'sale/customer')) {
                $json['error'] = $this->language->get('error_permission');
            } else {
                $this->load->model('sale/customer');

                $this->model_sale_customer->addBanIp($this->request->post['ip']);

                $json['success'] = $this->language->get('text_success');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function removeBanIp() {
        $this->load->language('sale/customer');

        $json = [];

        if (isset($this->request->post['ip'])) {
            if (!$this->user->hasPermission('modify', 'sale/customer')) {
                $json['error'] = $this->language->get('error_permission');
            } else {
                $this->load->model('sale/customer');

                $this->model_sale_customer->removeBanIp($this->request->post['ip']);

                $json['success'] = $this->language->get('text_success');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function autocomplete() {
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

            $this->load->model('sale/supplier');

            $filter_data = [
                'filter_name' => $filter_name,
                'filter_email' => $filter_email,
                'start' => 0,
                'limit' => 5,
            ];

            $results = $this->model_sale_farmer->getCustomers($filter_data);

            foreach ($results as $result) {
                if ($this->user->isVendor()) {
                    $result['name'] = $result['firstname'];
                }

                $json[] = [
                    'supplier_id' => $result['farmer_id'],
                    'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                    'firstname' => $result['first_name'],
                    'lastname' => $result['last_name'],
                    'email' => $result['email'],
                    'mobile' => $result['mobile'],
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

    public function autocompletebyCompany() {
        $json = [];

        if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_email'])) {
            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            if (isset($this->request->get['filter_company'])) {
                $filter_company = $this->request->get['filter_company'];
            } else {
                $filter_company = '';
            }

            $this->load->model('sale/customer');

            $filter_data = [
                'filter_name' => $filter_name,
                'filter_company' => $filter_company,
                'start' => 0,
                'limit' => 5,
            ];

            $results = $this->model_sale_customer->getCustomers($filter_data);

            foreach ($results as $result) {
                if ($this->user->isVendor()) {
                    $result['name'] = $result['firstname'];
                }

                $json[] = [
                    'supplier_id' => $result['farmer_id'],
                    'customer_group_id' => $result['customer_group_id'],
                    'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                    'customer_group' => $result['customer_group'],
                    'firstname' => $result['first_name'],
                    'lastname' => $result['last_name'],
                    'email' => $result['email'],
                    'mobile' => $result['mobile'],
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

    public function customfield() {
        $json = [];

        $this->load->model('sale/custom_field');

        // Customer Group
        if (isset($this->request->get['customer_group_id'])) {
            $customer_group_id = $this->request->get['customer_group_id'];
        } else {
            $customer_group_id = $this->config->get('config_customer_group_id');
        }

        $custom_fields = $this->model_sale_custom_field->getCustomFields(['filter_customer_group_id' => $customer_group_id]);

        foreach ($custom_fields as $custom_field) {
            $json[] = [
                'custom_field_id' => $custom_field['custom_field_id'],
                'required' => empty($custom_field['required']) || 0 == $custom_field['required'] ? false : true,
            ];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function address() {
        $json = [];

        if (!empty($this->request->get['address_id'])) {
            $this->load->model('sale/customer');

            $json = $this->model_sale_customer->getAddress($this->request->get['address_id']);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function country() {
        $json = [];

        $this->load->model('localisation/country');

        $country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

        if ($country_info) {
            $this->load->model('localisation/zone');

            $json = [
                'country_id' => $country_info['country_id'],
                'name' => $country_info['name'],
                'iso_code_2' => $country_info['iso_code_2'],
                'iso_code_3' => $country_info['iso_code_3'],
                'address_format' => $country_info['address_format'],
                'postcode_required' => $country_info['postcode_required'],
                'zone' => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
                'status' => $country_info['status'],
            ];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function referral() {
        $this->load->language('sale/customer');

        $this->load->model('sale/customer');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->user->hasPermission('modify', 'sale/customer')) {
            $this->model_sale_customer->addReward($this->request->get['customer_id'], $this->request->post['description'], $this->request->post['points']);

            $data['success'] = $this->language->get('text_success');
        } else {
            $data['success'] = '';
        }

        if (('POST' == $this->request->server['REQUEST_METHOD']) && !$this->user->hasPermission('modify', 'sale/customer')) {
            $data['error_warning'] = $this->language->get('error_permission');
        } else {
            $data['error_warning'] = '';
        }

        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_balance'] = $this->language->get('text_balance');

        $data['column_user_added'] = $this->language->get('column_user_added');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_description'] = $this->language->get('column_description');
        $data['column_points'] = $this->language->get('column_points');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['referrals'] = [];

        $results = $this->model_sale_customer->getReferrals($this->request->get['customer_id'], ($page - 1) * 10, 10);

        foreach ($results as $result) {
            $data['referrals'][] = [
                'customer_id' => $result['customer_id'],
                'firstname' => $result['firstname'],
                'lastname' => $result['lastname'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
            ];
        }

        //echo "<pre>";print_r($data['referrals']);die;

        $reward_total = $this->model_sale_customer->getTotalReferrals($this->request->get['customer_id']);

        $pagination = new Pagination();
        $pagination->total = $reward_total;
        $pagination->page = $page;
        $pagination->limit = 10;
        $pagination->url = $this->url->link('sale/customer/referral', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($reward_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($reward_total - 10)) ? $reward_total : ((($page - 1) * 10) + 10), $reward_total, ceil($reward_total / 10));

        $this->response->setOutput($this->load->view('sale/customer_referrals.tpl', $data));
    }

    public function autocompleteorganization() {
        $json = [];

        if (isset($this->request->get['filter_name'])) {
            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            $this->load->model('user/farmer');

            $filter_data = [
                'filter_name' => $filter_name,
                'start' => 0,
                'limit' => 5,
            ];

            $results = $this->model_user_farmer->getFarmerOrganizations($filter_data);
            foreach ($results as $result) {
                $json[] = [
                    'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
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

    public function autocompletefarmer() {
        $json = [];

        if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_email']) || isset($this->request->get['filter_mobile'])) {
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

            if (isset($this->request->get['filter_mobile'])) {
                $filter_mobile = $this->request->get['filter_mobile'];
            } else {
                $filter_mobile = '';
            }

            $this->load->model('user/user');

            $filter_data = [
                'filter_name' => $filter_name,
                'filter_email' => $filter_email,
                'filter_mobile' => $filter_mobile,
                'start' => 0,
                'limit' => 5,
            ];

            $results = $this->model_user_user->getFarmerUsers($filter_data);

            foreach ($results as $result) {
                if ($this->user->isVendor()) {
                    $result['name'] = $result['first_name'];
                }

                $json[] = [
                    'supplier_id' => $result['farmer_id'],
                    'username' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                    'name' => $result['name'],
                    'firstname' => $result['first_name'],
                    'lastname' => $result['last_name'],
                    'email' => $result['email'],
                    'mobile' => $result['mobile']
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
