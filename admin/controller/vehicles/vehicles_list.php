<?php

class ControllerVehiclesVehiclesList extends Controller {

    private $error = [];

    public function index() {
        $this->load->language('vehicles/vehicles');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('vehicles/vehicles');

        $this->getList();

        $data['kondutoStatus'] = $this->config->get('config_konduto_status');
        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');
    }

    public function add() {
        $this->load->language('vehicles/vehicles');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('vehicles/vehicles'); 

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $vehicle_id = $this->model_vehicles_vehicles->addVehicle($this->request->post);

            //$this->session->data['success'] = $this->language->get('text_success');
            $this->session->data['success'] = 'Success : Vehicle created successfully!';

            // Add to activity log
            $log = new Log('error.log');
            $this->load->model('user/user_activity');

            $activity_data = [
                'user_id' => $this->user->getId(),
                'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
                'user_group_id' => $this->user->getGroupId(),
                'vehicle_id' => $vehicle_id,
            ];
            $log->write('vehicle add');

            $this->model_user_user_activity->addActivity('vehicle_add', $activity_data);

            $log->write('vehicle add');

            $url = '';

            if (isset($this->request->get['filter_make'])) {
                $url .= '&filter_make=' . urlencode(html_entity_decode($this->request->get['filter_make'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_model'])) {
                $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_registration_number'])) {
                $url .= '&filter_registration_number=' . urlencode(html_entity_decode($this->request->get['filter_registration_number'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_registration_date'])) {
                $url .= '&filter_registration_date=' . urlencode(html_entity_decode($this->request->get['filter_registration_date'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_registration_validity_upto'])) {
                $url .= '&filter_registration_validity_upto=' . urlencode(html_entity_decode($this->request->get['filter_registration_validity_upto'], ENT_QUOTES, 'UTF-8'));
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
                $this->response->redirect($this->url->link('vehicles/vehicles_list/edit', 'vehicle_id=' . $vehicle_id . '&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('vehciles/vehicles_list/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            $this->response->redirect($this->url->link('vehicles/vehicles_list', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function addVehicle() {
        $this->load->language('vehicles/vehicles');
        $this->load->model('vehicles/vehicles'); 

        $json = [];
        $json['success']='';
        $json['error']='';

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $vehicle_id = $this->model_vehicles_vehicles->addVehicle($this->request->post);

            //$this->session->data['success'] = $this->language->get('text_success');
            $json['success'] = 'Success : Vehicle created successfully!';

            // Add to activity log
            $log = new Log('error.log');
            $this->load->model('user/user_activity');
            $json['token'] = $this->session->data['token'];
            $data['token'] = $this->session->data['token'];
            $activity_data = [
                'user_id' => $this->user->getId(),
                'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
                'user_group_id' => $this->user->getGroupId(),
                'vehicle_id' => $vehicle_id,
            ];
            $log->write('vehicle add');

            $this->model_user_user_activity->addActivity('vehicle_add', $activity_data);

            $log->write('vehicle add');

         }
         elseif(!$this->validateForm()){
            $json['error'] = 'Warning : Please fill all the data!';
         }
         else {
            $json['error'] = 'Please try again.';
         }

        // $this->getForm();

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function getList() {
        $this->load->language('vehicles/vehicles');

        if (isset($this->request->get['filter_make'])) {
            $filter_make = $this->request->get['filter_make'];
        } else {
            $filter_make = null;
        }

        if (isset($this->request->get['filter_model'])) {
            $filter_model = $this->request->get['filter_model'];
        } else {
            $filter_model = null;
        }

        if (isset($this->request->get['filter_registration_number'])) {
            $filter_registration_number = $this->request->get['filter_registration_number'];
        } else {
            $filter_registration_number = null;
        }

        if (isset($this->request->get['filter_registration_date'])) {
            $filter_registration_date = $this->request->get['filter_registration_date'];
        } else {
            $filter_registration_date = null;
        }

        if (isset($this->request->get['filter_registration_validity_upto'])) {
            $filter_registration_validity_upto = $this->request->get['filter_registration_validity_upto'];
        } else {
            $filter_registration_validity_upto = null;
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

        if (isset($this->request->get['filter_make'])) {
            $url .= '&filter_make=' . urlencode(html_entity_decode($this->request->get['filter_make'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_registration_number'])) {
            $url .= '&filter_registration_number=' . urlencode(html_entity_decode($this->request->get['filter_registration_number'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_registration_date'])) {
            $url .= '&filter_registration_date=' . urlencode(html_entity_decode($this->request->get['filter_registration_date'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_registration_validity_upto'])) {
            $url .= '&filter_registration_validity_upto=' . urlencode(html_entity_decode($this->request->get['filter_registration_validity_upto'], ENT_QUOTES, 'UTF-8'));
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
            'href' => $this->url->link('vehicles/vehicles_list', 'token=' . $this->session->data['token'] . $url, 'SSL'),
        ];

        $data['add'] = $this->url->link('vehicles/vehicles_list/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['delete'] = $this->url->link('vehicles/vehicles_list/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['customers'] = [];

        $filter_data = [
            'filter_make' => $filter_make,
            'filter_model' => $filter_model,
            'filter_registration_number' => $filter_registration_number,
            'filter_registration_date' => $filter_registration_date,
            'filter_registration_validity_upto' => $filter_registration_validity_upto,
            'filter_status' => $filter_status,
            'filter_date_added' => $filter_date_added,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        $vehicles_total = $this->model_vehicles_vehicles->getTotalVehicles($filter_data);

        $results = $this->model_vehicles_vehicles->getVehicles($filter_data);

        //echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            if (!$result['status']) {
                $status = $this->url->link('vehicles/vehicles_list/approve', 'token=' . $this->session->data['token'] . '&vehicle_id=' . $result['vehicle_id'] . $url, 'SSL');
            } else {
                $status = '';
            }


            $data['vehicles'][] = [
                'vehicle_id' => $result['vehicle_id'],
                'make' => $result['make'],
                'model' => $result['model'],
                'registration_number' => $result['registration_number'],
                'registration_date' => $result['registration_date'],
                'registration_validity_upto' => $result['registration_validity'],
                'status' => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'edit' => $this->url->link('vehicles/vehicles_list/edit', 'token=' . $this->session->data['token'] . '&vehicle_id=' . $result['vehicle_id'] . $url, 'SSL'),
                'vehicle_view' => $this->url->link('vehicles/vehicles_list/view_vehicle', 'token=' . $this->session->data['token'] . '&vehicle_id=' . $result['vehicle_id'] . $url, 'SSL'),
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

        $data['column_make'] = $this->language->get('column_make');
        $data['column_model'] = $this->language->get('column_model');
        $data['column_registration_number'] = $this->language->get('column_registration_number');
        $data['column_driving_licence'] = $this->language->get('column_driving_licence');
        $data['column_telephone'] = $this->language->get('column_telephone');
        $data['column_customer_group'] = $this->language->get('column_customer_group');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_action'] = $this->language->get('column_action');

        $data['entry_make'] = $this->language->get('entry_make');
        $data['entry_model'] = $this->language->get('entry_model');
        $data['entry_registration_number'] = $this->language->get('entry_registration_number');
        $data['entry_registration_date'] = $this->language->get('entry_registration_date');


        $data['entry_registration_validity_upto'] = $this->language->get('entry_registration_validity_upto');
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

        if (isset($this->request->get['filter_make'])) {
            $url .= '&filter_make=' . urlencode(html_entity_decode($this->request->get['filter_make'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_registration_date'])) {
            $url .= '&filter_registration_date=' . urlencode(html_entity_decode($this->request->get['filter_registration_date'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_registration_number'])) {
            $url .= '&filter_registration_number=' . urlencode(html_entity_decode($this->request->get['filter_registration_number'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_registration_validity_upto'])) {
            $url .= '&filter_registration_validity_upto=' . urlencode(html_entity_decode($this->request->get['filter_registration_validity_upto'], ENT_QUOTES, 'UTF-8'));
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

        $data['sort_make'] = $this->url->link('vehicles/vehicles_list', 'token=' . $this->session->data['token'] . '&sort=c.make' . $url, 'SSL');
        $data['sort_model'] = $this->url->link('vehicles/vehicles_list', 'token=' . $this->session->data['token'] . '&sort=c.model' . $url, 'SSL');
        $data['sort_status'] = $this->url->link('vehicles/vehicles_list', 'token=' . $this->session->data['token'] . '&sort=c.status' . $url, 'SSL');
        $data['sort_date_added'] = $this->url->link('vehicles/vehicles_list', 'token=' . $this->session->data['token'] . '&sort=c.date_added' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['filter_make'])) {
            $url .= '&filter_make=' . urlencode(html_entity_decode($this->request->get['filter_make'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_registration_number'])) {
            $url .= '&filter_registration_number=' . urlencode(html_entity_decode($this->request->get['filter_registration_number'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_registration_date'])) {
            $url .= '&filter_registration_date=' . urlencode(html_entity_decode($this->request->get['filter_registration_date'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_registration_validation_upto'])) {
            $url .= '&filter_registration_validation_upto=' . urlencode(html_entity_decode($this->request->get['filter_registration_validation_upto'], ENT_QUOTES, 'UTF-8'));
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
        $pagination->total = $vehicles_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('vehicles/vehicles_list', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($vehicles_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($vehicles_total - $this->config->get('config_limit_admin'))) ? $vehicles_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $vehicles_total, ceil($vehicles_total / $this->config->get('config_limit_admin')));

        $data['filter_make'] = $filter_make;
        $data['filter_model'] = $filter_model;
        $data['filter_registration_date'] = $filter_registration_date;
        $data['filter_registration_number'] = $filter_registration_number;
        $data['filter_registration_validity_upto'] = $filter_registration_validity_upto;
        $data['filter_status'] = $filter_status;
        $data['filter_date_added'] = $filter_date_added;
        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('vehicles/vehicles_list.tpl', $data));
    }

    protected function getForm() {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['entry_referred_by'] = $this->language->get('entry_referred_by');

        $data['text_form'] = !isset($this->request->get['vehicle_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['entry_model'] = $this->language->get('entry_model');
        $data['entry_registration_number'] = $this->language->get('entry_registration_number');
        $data['entry_registration_date'] = $this->language->get('entry_registration_date');
        ;

        $data['entry_registration_validity_upto'] = $this->language->get('entry_registration_validity_upto');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_make'] = $this->language->get('entry_make');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_savenew'] = $this->language->get('button_savenew');
        $data['button_saveclose'] = $this->language->get('button_saveclose');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['tab_general'] = $this->language->get('tab_general');

        $data['token'] = $this->session->data['token'];


        if (isset($this->request->get['vehicle_id'])) {
            $data['vehicle_id'] = $this->request->get['vehicle_id'];
        } else {
            $data['vehicle_id'] = 0;
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

        if (isset($this->error['make'])) {
            $data['error_make'] = $this->error['make'];
        } else {
            $data['error_make'] = '';
        }

        if (isset($this->error['model'])) {
            $data['error_model'] = $this->error['model'];
        } else {
            $data['error_model'] = '';
        }

        if (isset($this->error['registration_number'])) {
            $data['error_registration_number'] = $this->error['registration_number'];
        } else {
            $data['error_registration_number'] = '';
        }

        if (isset($this->error['registration_date'])) {
            $data['error_registration_date'] = $this->error['registration_date'];
        } else {
            $data['error_registration_date'] = '';
        }

        if (isset($this->error['registration_validity_upto'])) {
            $data['error_registration_validity_upto'] = $this->error['registration_validity_upto'];
        } else {
            $data['error_registration_validity_upto'] = '';
        }

        if (isset($this->error['confirm'])) {
            $data['error_confirm'] = $this->error['confirm'];
        } else {
            $data['error_confirm'] = '';
        }

        $url = '';

        if (isset($this->request->get['filter_make'])) {
            $url .= '&filter_make=' . urlencode(html_entity_decode($this->request->get['filter_make'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_registration_number'])) {
            $url .= '&filter_registration_number=' . urlencode(html_entity_decode($this->request->get['filter_registration_number'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_registration_date'])) {
            $url .= '&filter_registration_date=' . urlencode(html_entity_decode($this->request->get['filter_registration_date'], ENT_QUOTES, 'UTF-8'));
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
            'href' => $this->url->link('vehicles/vehicles_list', 'token=' . $this->session->data['token'] . $url, 'SSL'),
        ];

        if (!isset($this->request->get['vehicle_id'])) {
            $data['action'] = $this->url->link('vehicles/vehicles_list/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $data['action'] = $this->url->link('vehicles/vehicles_list/edit', 'token=' . $this->session->data['token'] . '&vehicle_id=' . $this->request->get['vehicle_id'] . $url, 'SSL');
        }

        $data['cancel'] = $this->url->link('vehicles/vehicles_list', 'token=' . $this->session->data['token'] . $url, 'SSL');
        if (isset($this->request->get['vehicle_id']) && ('POST' != $this->request->server['REQUEST_METHOD'])) {
            $vehicle_info = $this->model_vehicles_vehicles->getVehicle($this->request->get['vehicle_id']);
        }

        if (isset($this->request->post['make'])) {
            $data['make'] = $this->request->post['make'];
        } elseif (!empty($vehicle_info)) {
            $data['make'] = $vehicle_info['make'];
        } else {
            $data['make'] = '';
        }

        if (isset($this->request->post['model'])) {
            $data['model'] = $this->request->post['model'];
        } elseif (!empty($vehicle_info)) {
            $data['model'] = $vehicle_info['model'];
        } else {
            $data['model'] = '';
        }

        if (isset($this->request->post['registration_number'])) {
            $data['registration_number'] = $this->request->post['registration_number'];
        } elseif (!empty($vehicle_info)) {
            $data['registration_number'] = $vehicle_info['registration_number'];
        } else {
            $data['registration_number'] = '';
        }

        if (isset($this->request->post['registration_date'])) {
            $data['registration_date'] = $this->request->post['registration_date'];
        } elseif (!empty($vehicle_info)) {
            $data['registration_date'] = $vehicle_info['registration_date'];
        } else {
            $data['registration_date'] = '';
        }

        if (isset($this->request->post['registration_validity_upto'])) {
            $data['registration_validity_upto'] = $this->request->post['registration_validity_upto'];
        } elseif (!empty($vehicle_info)) {
            $data['registration_validity_upto'] = $vehicle_info['registration_validity'];
        } else {
            $data['registration_validity_upto'] = '';
        }

        //echo "<pre>";print_r($data);die;
        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($vehicle_info)) {
            $data['status'] = $vehicle_info['status'];
        } else {
            $data['status'] = true;
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['kondutoStatus'] = $this->config->get('config_konduto_status');
        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        $this->response->setOutput($this->load->view('vehicles/vehicle_form.tpl', $data));
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'vehicles/vehicles_list')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['make']) < 1) || (utf8_strlen(trim($this->request->post['make'])) > 32)) {
            $this->error['make'] = $this->language->get('error_make');
        }

        if ((utf8_strlen($this->request->post['model']) < 1) || (utf8_strlen(trim($this->request->post['model'])) > 32)) {
            $this->error['model'] = $this->language->get('error_model');
        }

        if ((utf8_strlen($this->request->post['registration_number']) < 1) || (utf8_strlen(trim($this->request->post['registration_number'])) > 32)) {
            $this->error['registration_number'] = $this->language->get('error_registration_number');
        }

        if ((utf8_strlen($this->request->post['registration_date']) < 1) || (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $this->request->post['registration_date'])) || $this->request->post['registration_date'] == '0000-00-00') {
            $this->error['registration_date'] = $this->language->get('error_registration_date');
        }

        if ((utf8_strlen($this->request->post['registration_validity_upto']) < 1) || (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $this->request->post['registration_validity_upto'])) || $this->request->post['registration_validity_upto'] == '0000-00-00') {
            $this->error['registration_validity_upto'] = $this->language->get('error_registration_validity_upto');
        }

        $vehicle_info = $this->model_vehicles_vehicles->getVehicleByNumber($this->request->post['registration_number']);

        /* if (!isset($this->request->get['vehicle_id'])) {
          if ($vehicle_info) {
          $this->error['warning'] = $this->language->get('error_exists');
          }
          } else {
          if ($vehicle_info && ($this->request->get['vehicle_id'] != $vehicle_info['vehicle_id'])) {
          $this->error['warning'] = $this->language->get('error_exists');
          }
          } */

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    public function edit() {
        $this->load->language('vehicles/vehicles');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('vehicles/vehicles');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $this->model_vehicles_vehicles->editVehicle($this->request->get['vehicle_id'], $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            // Add to activity log
            $log = new Log('error.log');
            $this->load->model('user/user_activity');

            $activity_data = [
                'user_id' => $this->user->getId(),
                'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
                'user_group_id' => $this->user->getGroupId(),
                'vehicle_id' => $this->request->get['vehicle_id'],
            ];
            $log->write('vehicle edit');

            $this->model_user_user_activity->addActivity('vehicle_edit', $activity_data);

            $log->write('vehicle edit');

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
                $this->response->redirect($this->url->link('vehicles/vehicles_list/edit', 'vehicle_id=' . $this->request->get['vehicle_id'] . '&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('vehicles/vehicles_list/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            $this->response->redirect($this->url->link('vehicles/vehicles_list', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function autocompletebyVehicleRegistrationNumber() {
        $log = new Log('error.log');
        $json = [];

        if (isset($this->request->get['filter_registration_number'])) {
            if (isset($this->request->get['filter_registration_number'])) {
                $filter_registration_number = $this->request->get['filter_registration_number'];
            } else {
                $filter_registration_number = '';
            }

            $this->load->model('vehicles/vehicles');

            $filter_data = [
                'filter_registration_number' => $filter_registration_number,
                'start' => 0,
                'limit' => 5,
            ];

            $results = $this->model_vehicles_vehicles->getVehicles($filter_data);
            //$log->write('results');
            //$log->write($results);
            //$log->write('results');

            foreach ($results as $result) {
                $json[] = [
                    'vehicle_id' => $result['vehicle_id'],
                    'make' => $result['make'],
                    'model' => $result['model'],
                    'registration_number' => $result['registration_number'],
                    'registration_validity' => $result['registration_validity'],
                    'registration_date' => $result['registration_date'],
                    'status' => $result['status'],
                    'date_added' => $result['date_added'],
                ];
            }
        }

        $sort_order = [];

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['registration_number'];
        }

        array_multisort($sort_order, SORT_ASC, $json);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    
    public function autocompletebyVehicleMake() {
        $log = new Log('error.log');
        $json = [];

        if (isset($this->request->get['filter_make'])) {
            if (isset($this->request->get['filter_make'])) {
                $filter_make = $this->request->get['filter_make'];
            } else {
                $filter_make = '';
            }

            $this->load->model('vehicles/vehicles');

            $filter_data = [
                'filter_make' => $filter_make,
                'start' => 0,
                'limit' => 5,
            ];

            $results = $this->model_vehicles_vehicles->getVehicles($filter_data);
            //$log->write('results');
            //$log->write($results);
            //$log->write('results');

            foreach ($results as $result) {
                $json[] = [
                    'vehicle_id' => $result['vehicle_id'],
                    'make' => $result['make'],
                    'model' => $result['model'],
                    'registration_number' => $result['registration_number'],
                    'registration_validity' => $result['registration_validity'],
                    'registration_date' => $result['registration_date'],
                    'status' => $result['status'],
                    'date_added' => $result['date_added'],
                ];
            }
        }

        $sort_order = [];

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['registration_number'];
        }

        array_multisort($sort_order, SORT_ASC, $json);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    
    public function autocompletebyVehicleModel() {
        $log = new Log('error.log');
        $json = [];

        if (isset($this->request->get['filter_model'])) {
            if (isset($this->request->get['filter_model'])) {
                $filter_model = $this->request->get['filter_model'];
            } else {
                $filter_model = '';
            }

            $this->load->model('vehicles/vehicles');

            $filter_data = [
                'filter_make' => $filter_model,
                'start' => 0,
                'limit' => 5,
            ];

            $results = $this->model_vehicles_vehicles->getVehicles($filter_data);
            //$log->write('results');
            //$log->write($results);
            //$log->write('results');

            foreach ($results as $result) {
                $json[] = [
                    'vehicle_id' => $result['vehicle_id'],
                    'make' => $result['make'],
                    'model' => $result['model'],
                    'registration_number' => $result['registration_number'],
                    'registration_validity' => $result['registration_validity'],
                    'registration_date' => $result['registration_date'],
                    'status' => $result['status'],
                    'date_added' => $result['date_added'],
                ];
            }
        }

        $sort_order = [];

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['registration_number'];
        }

        array_multisort($sort_order, SORT_ASC, $json);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function export_excel() {
        $data = [];
        $this->load->model('report/excel');
        $this->model_report_excel->download_vehicle_excel($data);
    }

    public function delete() {
        $this->load->language('vehicles/vehicles');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('vehicles/vehicles');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $vehicle_id) {
                $this->model_vehicles_vehicles->deleteVehicle($vehicle_id);

                // Add to activity log
                $log = new Log('error.log');
                $this->load->model('user/user_activity');

                $activity_data = [
                    'user_id' => $this->user->getId(),
                    'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
                    'user_group_id' => $this->user->getGroupId(),
                    'vehicle_id' => $vehicle_id,
                ];
                $log->write('vehicle delete');

                $this->model_user_user_activity->addActivity('vehicle_delete', $activity_data);

                $log->write('vehicle delete');
            }

            //$this->session->data['success'] = $this->language->get('text_success');
            $this->session->data['success'] = 'Success : Vehicle(s) deleted successfully!';

            $url = '';

            if (isset($this->request->get['filter_make'])) {
                $url .= '&filter_make=' . urlencode(html_entity_decode($this->request->get['filter_make'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_model'])) {
                $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
            }
            
            if (isset($this->request->get['filter_registration_number'])) {
                $url .= '&filter_registration_number=' . urlencode(html_entity_decode($this->request->get['filter_registration_number'], ENT_QUOTES, 'UTF-8'));
            }
            
            if (isset($this->request->get['filter_registration_validity_upto'])) {
                $url .= '&filter_registration_validity_upto=' . urlencode(html_entity_decode($this->request->get['filter_registration_validity_upto'], ENT_QUOTES, 'UTF-8'));
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

            $this->response->redirect($this->url->link('vehicles/vehicles_list', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'vehicles/vehicles_list')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function getAllVehicles() {
        $this->load->model('vehicles/vehicles');

        $results = $this->model_vehicles_vehicles->getVehicles();

        foreach ($results as $result) {
            if ($this->user->isVendor()) {
                $result['name'] = $result['firstname'];
            }

            $json[] = [
                'vehicle_id' => $result['vehicle_id'],
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
