<?php

class ControllerLocalisationCity extends Controller {

    private $error = [];

    public function index() {
        $this->load->language('localisation/city');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('localisation/city');

        $this->load->model('tool/export_import');

        $this->getList();
    }

    public function add() {
        $this->load->language('localisation/city');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('localisation/city');

        $this->load->model('tool/export_import');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $city_id = $this->model_localisation_city->addCity($this->request->post);

            if ((isset($this->request->files['upload'])) && (is_uploaded_file($this->request->files['upload']['tmp_name']))) {
                $file = $this->request->files['upload']['tmp_name'];

                if (!$this->user->isVendor()) {
                    if ($this->model_tool_export_import->uploadGeneralCityZipcode($file, $city_id)) {
                        $this->session->data['success'] = $this->language->get('text_success');
                        /* $this->response->redirect($this->url->link('tool/export_import', 'token=' . $this->session->data['token'], 'SSL')); */
                    } else {
                        $this->error['warning'] = $this->language->get('error_upload');
                        $this->error['warning'] .= "<br />\n" . $this->language->get('text_log_details');
                    }
                }
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

            if (isset($this->request->post['button']) and 'save' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('localisation/city/edit', 'city_id=' . $city_id . '&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('localisation/city/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            $this->response->redirect($this->url->link('localisation/city', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function edit() {
        $this->load->language('localisation/city');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('localisation/city');
        $this->load->model('localisation/citydelivery');

        $this->load->model('tool/export_import');

        //echo "<pre>";print_r($this->request->post);die;
        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $this->model_localisation_city->editCity($this->request->get['city_id'], $this->request->post);

            if ((isset($this->request->files['upload'])) && (is_uploaded_file($this->request->files['upload']['tmp_name']))) {
                $file = $this->request->files['upload']['tmp_name'];

                if (!$this->user->isVendor()) {
                    if ($this->model_tool_export_import->uploadGeneralCityZipcode($file, $this->request->get['city_id'])) {
                        $this->session->data['success'] = $this->language->get('text_success');
                        /* $this->response->redirect($this->url->link('tool/export_import', 'token=' . $this->session->data['token'], 'SSL')); */
                    } else {
                        $this->error['warning'] = $this->language->get('error_upload');
                        $this->error['warning'] .= "<br />\n" . $this->language->get('text_log_details');
                    }
                }
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

            if (isset($this->request->post['button']) and 'save' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('localisation/city/edit', 'city_id=' . $this->request->get['city_id'] . '&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('localisation/city/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            $this->response->redirect($this->url->link('localisation/city', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function editcitydelivery() {
        $this->load->language('localisation/city');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('localisation/city');
        $this->load->model('localisation/citydelivery');

        if (('POST' == $this->request->server['REQUEST_METHOD'])) {
            $this->model_localisation_citydelivery->editCitydelivery($this->request->get['city_id'], $this->request->post);

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

            if (isset($this->request->post['button']) and 'save' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('localisation/city/editcitydelivery', 'city_id=' . $this->request->get['city_id'] . '&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('localisation/city/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            $this->response->redirect($this->url->link('localisation/city', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getDeliveryForm();
    }

    public function delete() {
        $this->load->language('localisation/city');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('localisation/city');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $city_id) {
                $this->model_localisation_city->deleteCity($city_id);
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

            $this->response->redirect($this->url->link('localisation/city', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    protected function getList() {
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
            'href' => $this->url->link('localisation/city', 'token=' . $this->session->data['token'] . $url, 'SSL'),
        ];

        $data['add'] = $this->url->link('localisation/city/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['delete'] = $this->url->link('localisation/city/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['cities'] = [];

        $filter_data = [
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        $city_total = $this->model_localisation_city->getTotalCities();

        $results = $this->model_localisation_city->getCities($filter_data);

        foreach ($results as $result) {
            $data['cities'][] = [
                'city_id' => $result['city_id'],
                'name' => $result['name'],
                'status' => $result['status'],
                'sort_order' => $result['sort_order'],
                'editcitydelivery' => $this->url->link('localisation/city/editcitydelivery', 'token=' . $this->session->data['token'] . '&city_id=' . $result['city_id'] . $url, 'SSL'),
                'edit' => $this->url->link('localisation/city/edit', 'token=' . $this->session->data['token'] . '&city_id=' . $result['city_id'] . $url, 'SSL'),
            ];
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_address'] = $this->language->get('column_address');
        $data['column_action'] = $this->language->get('column_action');
        $data['column_sort_order'] = $this->language->get('column_sort_order');

        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');

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

        if ('ASC' == $order) {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_name'] = $this->url->link('localisation/city', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
        $data['sort_status'] = $this->url->link('localisation/city', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
        $data['sort_sort_order'] = $this->url->link('localisation/city', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $city_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('localisation/city', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($city_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($city_total - $this->config->get('config_limit_admin'))) ? $city_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $city_total, ceil($city_total / $this->config->get('config_limit_admin')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('localisation/city_list.tpl', $data));
    }

    protected function getForm() {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !isset($this->request->get['city_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_select'] = $this->language->get('text_select');
        $data['entry_state'] = $this->language->get('entry_state');

        $data['text_none'] = $this->language->get('text_none');
        $data['text_default'] = $this->language->get('text_default');
        $data['text_geocode'] = $this->language->get('text_geocode');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_address'] = $this->language->get('entry_address');
        $data['entry_zipcode'] = $this->language->get('entry_zipcode');

        $data['entry_geocode'] = $this->language->get('entry_geocode');
        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_fax'] = $this->language->get('entry_fax');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_open'] = $this->language->get('entry_open');
        $data['entry_comment'] = $this->language->get('entry_comment');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $data['help_geocode'] = $this->language->get('help_geocode');
        $data['help_open'] = $this->language->get('help_open');
        $data['help_comment'] = $this->language->get('help_comment');

        $data['text_import_zipcode'] = $this->language->get('text_import_zipcode');
        $data['text_export_zipcode'] = $this->language->get('text_export_zipcode');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_savenew'] = $this->language->get('button_savenew');
        $data['button_saveclose'] = $this->language->get('button_saveclose');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
        } else {
            $data['success'] = '';
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        if (isset($this->error['state'])) {
            $data['error_state'] = $this->error['state'];
        } else {
            $data['error_state'] = '';
        }
        
        if (isset($this->error['region'])) {
            $data['error_region'] = $this->error['region'];
        } else {
            $data['error_region'] = '';
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
            'href' => $this->url->link('localisation/city', 'token=' . $this->session->data['token'] . $url, 'SSL'),
        ];

        if (!isset($this->request->get['city_id'])) {
            $data['action'] = $this->url->link('localisation/city/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $data['action'] = $this->url->link('localisation/city/edit', 'token=' . $this->session->data['token'] . '&city_id=' . $this->request->get['city_id'] . $url, 'SSL');
        }

        $data['cancel'] = $this->url->link('localisation/city', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['city_zipcodes'] = [];
        $data['city_delivery_info'] = NULL;
        if (isset($this->request->get['city_id']) && ('POST' != $this->request->server['REQUEST_METHOD'])) {
            $city_info = $this->model_localisation_city->getCity($this->request->get['city_id']);
            $city_delivery_info = $this->model_localisation_citydelivery->getCityDelivery($this->request->get['city_id']);

            $this->load->model('localisation/city');

            $cityZipcodes = $this->model_localisation_city->getAllZipcodeByCity($this->request->get['city_id']);

            if ($cityZipcodes) {
                $data['city_zipcodes'] = $cityZipcodes;
            }
            $data['city_delivery_info'] = $city_delivery_info;
        }

        $data['states'] = $this->model_localisation_city->getStates();

        $data['token'] = $this->session->data['token'];

        $this->load->model('setting/store');
        $this->load->model('localisation/region');
        $data['regions'] = NULL;
        $regions = $this->model_localisation_region->getRegions($filter[] = NULL);
        $data['regions'] = $regions;

        if (isset($this->request->post['state_id'])) {
            $data['city_state_id'] = $this->request->post['state_id'];
        } elseif (!empty($city_info)) {
            $data['city_state_id'] = $city_info['state_id'];
        } else {
            $data['city_state_id'] = '';
        }
        
        if (isset($this->request->post['region_id'])) {
            $data['region_id'] = $this->request->post['region_id'];
        } elseif (!empty($city_info)) {
            $data['region_id'] = $city_info['region_id'];
        } else {
            $data['region_id'] = '';
        }

        //echo "<pre>";print_r($data);die;
        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($city_info)) {
            $data['name'] = $city_info['name'];
        } else {
            $data['name'] = '';
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($city_info)) {
            $data['status'] = $city_info['status'];
        } else {
            $data['status'] = '';
        }

        if (isset($this->request->post['sort_order'])) {
            $data['sort_order'] = $this->request->post['sort_order'];
        } elseif (!empty($city_info)) {
            $data['sort_order'] = $city_info['sort_order'];
        } else {
            $data['sort_order'] = '';
        }

        //echo "<pre>";print_r($cityZipcodes);die;
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('localisation/city_form.tpl', $data));
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'localisation/city')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        //echo "<pre>";print_r($this->request->post);die;
        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if ('' == $this->request->post['state_id']) {
            $this->error['state'] = $this->language->get('error_state');
        }
        
        if ('' == $this->request->post['region_id']) {
            $this->error['region'] = $this->language->get('error_region');
        }

        return !$this->error;
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'localisation/city')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $this->load->model('sale/order');

        foreach ($this->request->post['selected'] as $city_id) {
            //check order

            $order_count = $this->model_sale_order->getTotalFromOrder($city_id);

            if ($order_count > 0) {
                $this->error['warning'] = sprintf($this->language->get('error_order_exist'), $order_count);
            }

            //customer address

            $address_count = $this->model_sale_order->getTotalFromAddress($city_id);

            if ($address_count > 0) {
                $this->error['warning'] = sprintf($this->language->get('error_address_exist'), $address_count);
            }
        }

        return !$this->error;
    }

    public function export_city_zipcodes() {
        $data = [];
        if (isset($this->request->get['city_id'])) {
            $data['city_id'] = $this->request->get['city_id'];
        } else {
            $data['city_id'] = '';
        }
        $this->load->model('report/excel');

        $city = $this->model_report_excel->getCity($data['city_id']);

        $data['city_name'] = $city['name'];

        $this->model_report_excel->download_cityzipcode_excel($data);
    }

    protected function getDeliveryForm() {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !isset($this->request->get['city_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_select'] = $this->language->get('text_select');
        $data['entry_state'] = $this->language->get('entry_state');

        $data['text_none'] = $this->language->get('text_none');
        $data['text_default'] = $this->language->get('text_default');
        $data['text_geocode'] = $this->language->get('text_geocode');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_address'] = $this->language->get('entry_address');
        $data['entry_zipcode'] = $this->language->get('entry_zipcode');

        $data['entry_geocode'] = $this->language->get('entry_geocode');
        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_fax'] = $this->language->get('entry_fax');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_open'] = $this->language->get('entry_open');
        $data['entry_comment'] = $this->language->get('entry_comment');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $data['help_geocode'] = $this->language->get('help_geocode');
        $data['help_open'] = $this->language->get('help_open');
        $data['help_comment'] = $this->language->get('help_comment');

        $data['text_import_zipcode'] = $this->language->get('text_import_zipcode');
        $data['text_export_zipcode'] = $this->language->get('text_export_zipcode');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_savenew'] = $this->language->get('button_savenew');
        $data['button_saveclose'] = $this->language->get('button_saveclose');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
        } else {
            $data['success'] = '';
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        if (isset($this->error['state'])) {
            $data['error_state'] = $this->error['state'];
        } else {
            $data['error_state'] = '';
        }
        
        if (isset($this->error['region'])) {
            $data['error_region'] = $this->error['region'];
        } else {
            $data['error_region'] = '';
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
            'href' => $this->url->link('localisation/city', 'token=' . $this->session->data['token'] . $url, 'SSL'),
        ];

        if (!isset($this->request->get['city_id'])) {
            $data['action'] = $this->url->link('localisation/city/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $data['action'] = $this->url->link('localisation/city/editcitydelivery', 'token=' . $this->session->data['token'] . '&city_id=' . $this->request->get['city_id'] . $url, 'SSL');
        }

        $data['cancel'] = $this->url->link('localisation/city', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['city_zipcodes'] = [];
        $data['city_delivery_info'] = NULL;

        if (isset($this->request->get['city_id']) && ('POST' != $this->request->server['REQUEST_METHOD'])) {
            $city_info = $this->model_localisation_city->getCity($this->request->get['city_id']);

            $this->load->model('localisation/city');

            $cityZipcodes = $this->model_localisation_city->getAllZipcodeByCity($this->request->get['city_id']);
            $this->load->model('localisation/citydelivery');
            $city_delivery_info = $this->model_localisation_citydelivery->getCityDelivery($this->request->get['city_id']);

            if ($cityZipcodes) {
                $data['city_zipcodes'] = $cityZipcodes;
            }
            $data['city_delivery_info'] = $city_delivery_info;
        }

        $data['states'] = $this->model_localisation_city->getStates();

        $data['token'] = $this->session->data['token'];

        $this->load->model('setting/store');

        if (isset($this->request->post['state_id'])) {
            $data['city_state_id'] = $this->request->post['state_id'];
        } elseif (!empty($city_info)) {
            $data['city_state_id'] = $city_info['state_id'];
        } else {
            $data['city_state_id'] = '';
        }

        //echo "<pre>";print_r($data);die;
        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($city_info)) {
            $data['name'] = $city_info['name'];
        } else {
            $data['name'] = '';
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($city_info)) {
            $data['status'] = $city_info['status'];
        } else {
            $data['status'] = '';
        }

        if (isset($this->request->post['sort_order'])) {
            $data['sort_order'] = $this->request->post['sort_order'];
        } elseif (!empty($city_info)) {
            $data['sort_order'] = $city_info['sort_order'];
        } else {
            $data['sort_order'] = '';
        }

        //echo "<pre>";print_r($cityZipcodes);die;
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('localisation/citydelivery.tpl', $data));
    }

}
