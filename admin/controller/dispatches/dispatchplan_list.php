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

        if (isset($this->request->get['filter_registration_number'])) {
            $filter_registration_number = $this->request->get['filter_registration_number'];
        } else {
            $filter_registration_number = null;
        }

        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }

        if (isset($this->request->get['filter_delivery_executive_name'])) {
            $filter_delivery_executive_name = $this->request->get['filter_delivery_executive_name'];
        } else {
            $filter_delivery_executive_name = null;
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

        if (isset($this->request->get['filter_registration_number'])) {
            $url .= '&filter_registration_number=' . urlencode(html_entity_decode($this->request->get['filter_registration_number'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_delivery_executive_name'])) {
            $url .= '&filter_delivery_executive_name=' . urlencode(html_entity_decode($this->request->get['filter_delivery_executive_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . urlencode(html_entity_decode($this->request->get['filter_date_added'], ENT_QUOTES, 'UTF-8'));
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
            'filter_registration_number' => $filter_registration_number,
            'filter_name' => $filter_name,
            'filter_delivery_executive_name' => $filter_delivery_executive_name,
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
                'dispatche_id' => $result['id'],
                'vehicle_id' => $result['vehicle_id'],
                'registration_number' => $vehicle_details['registration_number'],
                'driver_id' => $result['driver_id'],
                'driver_name' => $driver_details['firstname'] . ' ' . $driver_details['lastname'],
                'delivery_executive_id' => $result['delivery_executive_id'],
                'delivery_executive_name' => $delivery_executives['firstname'] . ' ' . $delivery_executives['lastname'],
                'delivery_date' => $result['delivery_date'],
                'delivery_time_slot' => $result['delivery_time_slot'],
                'created_at' => $result['created_at'],
                'updated_at' => $result['updated_at'],
            ];
        }

        $data['delete'] = $this->url->link('dispatches/dispatchplan_list/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

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

        if (isset($this->request->get['filter_registration_number'])) {
            $url .= '&filter_registration_number=' . urlencode(html_entity_decode($this->request->get['filter_registration_number'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_delivery_executive_name'])) {
            $url .= '&filter_delivery_executive_name=' . urlencode(html_entity_decode($this->request->get['filter_delivery_executive_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . urlencode(html_entity_decode($this->request->get['filter_date_added'], ENT_QUOTES, 'UTF-8'));
        }

        if ('ASC' == $order) {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_date_added'] = $this->url->link('dispatches/dispatchplan_list', 'token=' . $this->session->data['token'] . '&sort=d.date_added' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['filter_registration_number'])) {
            $url .= '&filter_registration_number=' . urlencode(html_entity_decode($this->request->get['filter_registration_number'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_delivery_executive_name'])) {
            $url .= '&filter_delivery_executive_name=' . urlencode(html_entity_decode($this->request->get['filter_delivery_executive_name'], ENT_QUOTES, 'UTF-8'));
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

        $data['filter_registration_number'] = $filter_registration_number;
        $data['filter_name'] = $filter_name;
        $data['filter_delivery_executive_name'] = $filter_delivery_executive_name;
        $data['filter_date_added'] = $filter_date_added;
        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('dispatches/dispatches_list.tpl', $data));
    }

    public function delete() {
        $this->load->language('dispatches/dispatchplanning');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('dispatchplanning/dispatchplanning');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $dispatche_id) {
                $this->model_dispatchplanning_dispatchplanning->deleteDispatche($dispatche_id);

                // Add to activity log
                $log = new Log('error.log');
                $this->load->model('user/user_activity');

                $activity_data = [
                    'user_id' => $this->user->getId(),
                    'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
                    'user_group_id' => $this->user->getGroupId(),
                    'customer_id' => $dispatche_id,
                ];
                $log->write('dispatch delete');

                $this->model_user_user_activity->addActivity('dispatch_delete', $activity_data);

                $log->write('dispatch delete');
            }

            $this->session->data['success'] = 'Success : Dispatch Plan(s) deleted successfully!';

            $url = '';

            if (isset($this->request->get['filter_registration_number'])) {
                $url .= '&filter_registration_number=' . urlencode(html_entity_decode($this->request->get['filter_registration_number'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_delivery_executive_name'])) {
                $url .= '&filter_delivery_executive_name=' . urlencode(html_entity_decode($this->request->get['filter_delivery_executive_name'], ENT_QUOTES, 'UTF-8'));
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

            $this->response->redirect($this->url->link('dispatches/dispatchplan_list', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'dispatches/dispatchplan_list')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

}
