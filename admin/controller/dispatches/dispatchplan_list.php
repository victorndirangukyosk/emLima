<?php

class ControllerDispatchesDispatchplanlist extends Controller {

    private $error = [];

    public function index() {
        $this->load->language('dispatches/dispatchplanning');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('dispatchplanning/dispatchplanning');
        $this->load->model('vehicles/vehicles');
        $this->load->model('drivers/drivers');
        $this->load->model('executives/executives');
        $this->getList();

        $data['kondutoStatus'] = $this->config->get('config_konduto_status');
        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');
    }

    protected function getList() {
        $this->load->language('dispatches/dispatchplanning');

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

        $data['dispatch'] = [];

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

        $dispatches_total = $this->model_dispatchplanning_dispatchplanning->getTotalDispatches($filter_data);

        $results = $this->model_dispatchplanning_dispatchplanning->getAllDispatches($filter_data);

        //echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            $delivery_executives = $this->model_executives_executives->getExecutive($result['delivery_executive_id']);
            $driver_details = $this->model_drivers_drivers->getDriver($result['driver_id']);
            $vehicle_details = $this->model_vehicles_vehicles->getVehicle($result['vehicle_id']);

            $data['dispatches'][] = [
                'vehicle_id' => $result['vehicle_id'],
                'registration_number' => $vehicle_details['registration_number'],
                'driver_id' => $result['driver_id'],
                'driver_name' => $driver_details['firstname'].' '.$driver_details['lastname'],
                'delivery_executive_id' => $result['delivery_executive_id'],
                'delivery_executive_name' => $delivery_executives['firstname'].''.$delivery_executives['lastname'],
                'delivery_date' => $result['delivery_date'],
                'delivery_time_slot' => $result['delivery_time_slot'],
                'created_at' => $result['created_at'],
                'updated_at' => $result['updated_at'],
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
        $pagination->total = $dispatches_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('dispatches/dispatchplan_list', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($dispatches_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($dispatches_total - $this->config->get('config_limit_admin'))) ? $dispatches_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $dispatches_total, ceil($dispatches_total / $this->config->get('config_limit_admin')));

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

        $this->response->setOutput($this->load->view('dispatches/dispatches_list.tpl', $data));
    }

}
