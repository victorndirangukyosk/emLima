<?php

class ControllerDriversDriversList extends Controller {

    private $error = [];

    public function index() {
        $this->load->language('drivers/drivers');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('drivers/drivers');

        $this->getList();

        $data['kondutoStatus'] = $this->config->get('config_konduto_status');
        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');
    }

    public function add() {
        $this->load->language('drivers/drivers');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('drivers/drivers');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $driver_id = $this->model_drivers_drivers->addDriver($this->request->post);

            //$this->session->data['success'] = $this->language->get('text_success');
            $this->session->data['success'] = 'Success : Driver created successfully!';

            // Add to activity log
            $log = new Log('error.log');
            $this->load->model('user/user_activity');

            $activity_data = [
                'user_id' => $this->user->getId(),
                'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
                'user_group_id' => $this->user->getGroupId(),
                'driver_id' => $driver_id,
            ];
            $log->write('driver add');

            $this->model_user_user_activity->addActivity('driver_add', $activity_data);

            $log->write('driver add');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_email'])) {
                $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_driving_licence'])) {
                $url .= '&filter_driving_licence=' . urlencode(html_entity_decode($this->request->get['filter_driving_licence'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
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

            if (isset($this->request->post['button']) and 'save' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('drivers/drivers_list/edit', 'driver_id=' . $driver_id . '&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('drivers/drivers_list/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            $this->response->redirect($this->url->link('drivers/drivers_list', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    protected function getList() {
        $this->load->language('drivers/drivers');

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

        if (isset($this->request->get['filter_driving_licence'])) {
            $filter_driving_licence = $this->request->get['filter_driving_licence'];
        } else {
            $filter_driving_licence = null;
        }

        if (isset($this->request->get['filter_telephone'])) {
            $filter_telephone = $this->request->get['filter_telephone'];
        } else {
            $filter_telephone = null;
        }

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = null;
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

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_driving_licence'])) {
            $url .= '&filter_driving_licence=' . urlencode(html_entity_decode($this->request->get['filter_driving_licence'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_telephone'])) {
            $url .= '&filter_telephone=' . urlencode(html_entity_decode($this->request->get['filter_telephone'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
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
            'href' => $this->url->link('drivers/drivers_list', 'token=' . $this->session->data['token'] . $url, 'SSL'),
        ];

        $data['add'] = $this->url->link('drivers/drivers_list/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['delete'] = $this->url->link('drivers/drivers_list/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['customers'] = [];

        $filter_data = [
            'filter_name' => $filter_name,
            'filter_email' => $filter_email,
            'filter_driving_licence' => $filter_driving_licence,
            'filter_telephone' => $filter_telephone,
            'filter_status' => $filter_status,
            'filter_date_added' => $filter_date_added,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        $drivers_total = $this->model_drivers_drivers->getTotalDrivers($filter_data);

        $results = $this->model_drivers_drivers->getDrivers($filter_data);

        //echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            if (!$result['status']) {
                $status = $this->url->link('drivers/drivers_list/approve', 'token=' . $this->session->data['token'] . '&driver_id=' . $result['driver_id'] . $url, 'SSL');
            } else {
                $status = '';
            }

            $country_code = '+' . $this->config->get('config_telephone_code');

            $data['drivers'][] = [
                'driver_id' => $result['driver_id'],
                'name' => $result['name'],
                'email' => $result['email'],
                'driving_licence' => $result['driving_licence'],
                'driving_licence_expire_date' => $result['driving_licence_expire_date'],
                'telephone' => $country_code . $result['telephone'],
                'status' => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'edit' => $this->url->link('drivers/drivers_list/edit', 'token=' . $this->session->data['token'] . '&driver_id=' . $result['driver_id'] . $url, 'SSL'),
                'driver_view' => $this->url->link('drivers/drivers_list/view_driver', 'token=' . $this->session->data['token'] . '&driver_id=' . $result['driver_id'] . $url, 'SSL'),
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
        $data['column_driving_licence'] = $this->language->get('column_driving_licence');
        $data['column_telephone'] = $this->language->get('column_telephone');
        $data['column_customer_group'] = $this->language->get('column_customer_group');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_action'] = $this->language->get('column_action');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_driving_licence'] = $this->language->get('entry_driving_licence');


        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_date_added'] = $this->language->get('entry_date_added');

        $data['button_verify'] = $this->language->get('button_verify');

        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_show_filter'] = $this->language->get('button_show_filter');
        $data['button_hide_filter'] = $this->language->get('button_hide_filter');

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
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_driving_licence'])) {
            $url .= '&filter_driving_licence=' . urlencode(html_entity_decode($this->request->get['filter_driving_licence'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_telephone'])) {
            $url .= '&filter_telephone=' . urlencode(html_entity_decode($this->request->get['filter_telephone'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
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

        $data['sort_name'] = $this->url->link('drivers/drivers_list', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
        $data['sort_email'] = $this->url->link('drivers/drivers_list', 'token=' . $this->session->data['token'] . '&sort=c.email' . $url, 'SSL');
        $data['sort_status'] = $this->url->link('drivers/drivers_list', 'token=' . $this->session->data['token'] . '&sort=c.status' . $url, 'SSL');
        $data['sort_date_added'] = $this->url->link('drivers/drivers_list', 'token=' . $this->session->data['token'] . '&sort=c.date_added' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_driving_licence'])) {
            $url .= '&filter_driving_licence=' . urlencode(html_entity_decode($this->request->get['filter_driving_licence'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_telephone'])) {
            $url .= '&filter_telephone=' . urlencode(html_entity_decode($this->request->get['filter_telephone'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
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
        $pagination->total = $drivers_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('drivers/drivers_list', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($drivers_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($drivers_total - $this->config->get('config_limit_admin'))) ? $drivers_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $drivers_total, ceil($drivers_total / $this->config->get('config_limit_admin')));

        $data['filter_name'] = $filter_name;
        $data['filter_email'] = $filter_email;
        $data['filter_driving_licence'] = $filter_driving_licence;
        $data['filter_telephone'] = $filter_telephone;
        $data['filter_status'] = $filter_status;
        $data['filter_date_added'] = $filter_date_added;
        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('drivers/drivers_list.tpl', $data));
    }

    protected function getForm() {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['entry_referred_by'] = $this->language->get('entry_referred_by');

        $data['text_form'] = !isset($this->request->get['driver_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_driving_licence'] = $this->language->get('entry_driving_licence');
        $data['entry_driving_licence_expire_date'] = 'Driving Licence Expire Date';

        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_name'] = $this->language->get('entry_name');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_savenew'] = $this->language->get('button_savenew');
        $data['button_saveclose'] = $this->language->get('button_saveclose');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['tab_general'] = $this->language->get('tab_general');

        $data['token'] = $this->session->data['token'];


        if (isset($this->request->get['driver_id'])) {
            $data['driver_id'] = $this->request->get['driver_id'];
        } else {
            $data['driver_id'] = 0;
        }

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

        if (isset($this->error['firstname'])) {
            $data['error_firstname'] = $this->error['firstname'];
        } else {
            $data['error_firstname'] = '';
        }

        if (isset($this->error['lastname'])) {
            $data['error_lastname'] = $this->error['lastname'];
        } else {
            $data['error_lastname'] = '';
        }

        if (isset($this->error['email'])) {
            $data['error_email'] = $this->error['email'];
        } else {
            $data['error_email'] = '';
        }

        if (isset($this->error['driving_licence'])) {
            $data['error_driving_licence'] = $this->error['driving_licence'];
        } else {
            $data['error_driving_licence'] = '';
        }
        
        if (isset($this->error['driving_licence_expire_date'])) {
            $data['error_driving_licence_expire_date'] = $this->error['driving_licence_expire_date'];
        } else {
            $data['error_driving_licence_expire_date'] = '';
        }

        if (isset($this->error['telephone'])) {
            $data['error_telephone'] = $this->error['telephone'];
        } else {
            $data['error_telephone'] = '';
        }

        if (isset($this->error['confirm'])) {
            $data['error_confirm'] = $this->error['confirm'];
        } else {
            $data['error_confirm'] = '';
        }

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_driving_licence'])) {
            $url .= '&filter_driving_licence=' . urlencode(html_entity_decode($this->request->get['filter_driving_licence'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
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
            'href' => $this->url->link('drivers/drivers_list', 'token=' . $this->session->data['token'] . $url, 'SSL'),
        ];

        if (!isset($this->request->get['driver_id'])) {
            $data['action'] = $this->url->link('drivers/drivers_list/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $data['action'] = $this->url->link('drivers/drivers_list/edit', 'token=' . $this->session->data['token'] . '&driver_id=' . $this->request->get['driver_id'] . $url, 'SSL');
        }

        $data['cancel'] = $this->url->link('drivers/drivers_list', 'token=' . $this->session->data['token'] . $url, 'SSL');
        if (isset($this->request->get['driver_id']) && ('POST' != $this->request->server['REQUEST_METHOD'])) {
            $driver_info = $this->model_drivers_drivers->getDriver($this->request->get['driver_id']);
        }

        if (isset($this->request->post['firstname'])) {
            $data['firstname'] = $this->request->post['firstname'];
        } elseif (!empty($driver_info)) {
            $data['firstname'] = $driver_info['firstname'];
        } else {
            $data['firstname'] = '';
        }

        if (isset($this->request->post['lastname'])) {
            $data['lastname'] = $this->request->post['lastname'];
        } elseif (!empty($driver_info)) {
            $data['lastname'] = $driver_info['lastname'];
        } else {
            $data['lastname'] = '';
        }

        if (isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } elseif (!empty($driver_info)) {
            $data['email'] = $driver_info['email'];
        } else {
            $data['email'] = '';
        }

        if (isset($this->request->post['driving_licence'])) {
            $data['driving_licence'] = $this->request->post['driving_licence'];
        } elseif (!empty($driver_info)) {
            $data['driving_licence'] = $driver_info['driving_licence'];
        } else {
            $data['driving_licence'] = '';
        }
        
        if (isset($this->request->post['driving_licence_expire_date'])) {
            $data['driving_licence_expire_date'] = $this->request->post['driving_licence_expire_date'];
        } elseif (!empty($driver_info)) {
            $data['driving_licence_expire_date'] = $driver_info['driving_licence_expire_date'];
        } else {
            $data['driving_licence_expire_date'] = '';
        }

        if (isset($this->request->post['telephone'])) {
            $data['telephone'] = $this->request->post['telephone'];
        } elseif (!empty($driver_info)) {
            $data['telephone'] = $driver_info['telephone'];
        } else {
            $data['telephone'] = '';
        }

        //echo "<pre>";print_r($data);die;
        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($driver_info)) {
            $data['status'] = $driver_info['status'];
        } else {
            $data['status'] = true;
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['kondutoStatus'] = $this->config->get('config_konduto_status');
        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        $this->response->setOutput($this->load->view('drivers/driver_form.tpl', $data));
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'drivers/drivers_list')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
            $this->error['firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
            $this->error['lastname'] = $this->language->get('error_lastname');
        }

        /*if (isset($this->request->post['email']) && $this->request->post['email'] != NULL && ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL))) {
            $this->error['email'] = $this->language->get('error_email');
        }*/

        /*if ((utf8_strlen($this->request->post['driving_licence']) < 1) || (utf8_strlen(trim($this->request->post['driving_licence'])) > 32)) {
            $this->error['driving_licence'] = $this->language->get('error_driving_licence');
        }*/
        
        /*if ((utf8_strlen($this->request->post['driving_licence_expire_date']) < 1) || (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$this->request->post['driving_licence_expire_date'])) || $this->request->post['driving_licence_expire_date'] == '0000-00-00') {
            $this->error['driving_licence_expire_date'] = 'Driving Licence Expire Date filed required';
        }*/

        $driver_info = $this->model_drivers_drivers->getDriverByEmail($this->request->post['email']);

        /*if (!isset($this->request->get['driver_id'])) {
            if ($driver_info) {
                $this->error['warning'] = $this->language->get('error_exists');
            }
        } else {
            if ($driver_info && ($this->request->get['driver_id'] != $driver_info['driver_id'])) {
                $this->error['warning'] = $this->language->get('error_exists');
            }
        }*/

        if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
            $this->error['telephone'] = $this->language->get('error_telephone');
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    public function edit() {
        $this->load->language('drivers/drivers');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('drivers/drivers');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $this->model_drivers_drivers->editDriver($this->request->get['driver_id'], $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            // Add to activity log
            $log = new Log('error.log');
            $this->load->model('user/user_activity');

            $activity_data = [
                'user_id' => $this->user->getId(),
                'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
                'user_group_id' => $this->user->getGroupId(),
                'driver_id' => $this->request->get['driver_id'],
            ];
            $log->write('driver edit');

            $this->model_user_user_activity->addActivity('driver_edit', $activity_data);

            $log->write('driver edit');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_email'])) {
                $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_driving_licence'])) {
                $url .= '&filter_driving_licence=' . urlencode(html_entity_decode($this->request->get['filter_driving_licence'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
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

            if (isset($this->request->post['button']) and 'save' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('drivers/drivers_list/edit', 'driver_id=' . $this->request->get['driver_id'] . '&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('drivers/drivers_list/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            $this->response->redirect($this->url->link('drivers/drivers_list', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function autocompletebyDriverName() {
        $json = [];

        if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_email'])) {
            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            $this->load->model('drivers/drivers');

            $filter_data = [
                'filter_name' => $filter_name,
                'start' => 0,
                'limit' => 5,
            ];

            $results = $this->model_drivers_drivers->getDrivers($filter_data);

            foreach ($results as $result) {
                if ($this->user->isVendor()) {
                    $result['name'] = $result['firstname'];
                }

                $json[] = [
                    'driver_id' => $result['driver_id'],
                    'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                    'firstname' => $result['firstname'],
                    'lastname' => $result['lastname'],
                    'email' => $result['email'],
                    'driving_licence' => $result['driving_licence'],
                    'telephone' => $result['telephone'],
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

    public function export_excel() {
        $data = [];
        $this->load->model('report/excel');
        $this->model_report_excel->download_driver_excel($data);
    }

    public function delete() {
        $this->load->language('drivers/drivers');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('drivers/drivers');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $driver_id) {
                $this->model_drivers_drivers->deleteDriver($driver_id);

                // Add to activity log
                $log = new Log('error.log');
                $this->load->model('user/user_activity');

                $activity_data = [
                    'user_id' => $this->user->getId(),
                    'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
                    'user_group_id' => $this->user->getGroupId(),
                    'driver_id' => $driver_id,
                ];
                $log->write('driver delete');

                $this->model_user_user_activity->addActivity('driver_delete', $activity_data);

                $log->write('driver delete');
            }

            //$this->session->data['success'] = $this->language->get('text_success');
            $this->session->data['success'] = 'Success : Driver(s) deleted successfully!';

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

            $this->response->redirect($this->url->link('drivers/drivers_list', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'drivers/drivers_list')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function getAllDrivers() {
        $this->load->model('drivers/drivers');

        $results = $this->model_drivers_drivers->getDrivers();

        foreach ($results as $result) {
            if ($this->user->isVendor()) {
                $result['name'] = $result['firstname'];
            }

            $json[] = [
                'driver_id' => $result['driver_id'],
                'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                'firstname' => $result['firstname'],
                'lastname' => $result['lastname'],
                'email' => $result['email'],
                'driving_licence' => $result['driving_licence'],
                'telephone' => $result['telephone'],
            ];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
