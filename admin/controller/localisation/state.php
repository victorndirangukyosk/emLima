<?php

class ControllerLocalisationState extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('localisation/state');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('localisation/state');

        $this->load->model('tool/export_import');

        $this->getList();
    }

    public function add()
    {
        $this->load->language('localisation/state');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('localisation/state');

        $this->load->model('tool/export_import');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $state_id = $this->model_localisation_state->addState($this->request->post);

            if ((isset($this->request->files['upload'])) && (is_uploaded_file($this->request->files['upload']['tmp_name']))) {
                $file = $this->request->files['upload']['tmp_name'];

                if (!$this->user->isVendor()) {
                    if ($this->model_tool_export_import->uploadGeneralstateZipcode($file, $state_id)) {
                        $this->session->data['success'] = $this->language->get('text_success');
                    /*$this->response->redirect($this->url->link('tool/export_import', 'token=' . $this->session->data['token'], 'SSL'));*/
                    } else {
                        $this->error['warning'] = $this->language->get('error_upload');
                        $this->error['warning'] .= "<br />\n".$this->language->get('text_log_details');
                    }
                }
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort='.$this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order='.$this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page='.$this->request->get['page'];
            }

            if (isset($this->request->post['button']) and 'save' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('localisation/state/edit', 'state_id='.$state_id.'&token='.$this->session->data['token'].$url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('localisation/state/add', 'token='.$this->session->data['token'].$url, 'SSL'));
            }

            $this->response->redirect($this->url->link('localisation/state', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getForm();
    }

    public function edit()
    {
        $this->load->language('localisation/state');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('localisation/state');

        $this->load->model('tool/export_import');

        //echo "<pre>";print_r($this->request->post);die;
        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $this->model_localisation_state->editstate($this->request->get['state_id'], $this->request->post);

            if ((isset($this->request->files['upload'])) && (is_uploaded_file($this->request->files['upload']['tmp_name']))) {
                $file = $this->request->files['upload']['tmp_name'];

                if (!$this->user->isVendor()) {
                    if ($this->model_tool_export_import->uploadGeneralstateZipcode($file, $this->request->get['state_id'])) {
                        $this->session->data['success'] = $this->language->get('text_success');
                    /*$this->response->redirect($this->url->link('tool/export_import', 'token=' . $this->session->data['token'], 'SSL'));*/
                    } else {
                        $this->error['warning'] = $this->language->get('error_upload');
                        $this->error['warning'] .= "<br />\n".$this->language->get('text_log_details');
                    }
                }
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort='.$this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order='.$this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page='.$this->request->get['page'];
            }

            if (isset($this->request->post['button']) and 'save' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('localisation/state/edit', 'state_id='.$this->request->get['state_id'].'&token='.$this->session->data['token'].$url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('localisation/state/add', 'token='.$this->session->data['token'].$url, 'SSL'));
            }

            $this->response->redirect($this->url->link('localisation/state', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->load->language('localisation/state');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('localisation/state');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $state_id) {
                $this->model_localisation_state->deletestate($state_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort='.$this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order='.$this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page='.$this->request->get['page'];
            }

            $this->response->redirect($this->url->link('localisation/state', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getList();
    }

    protected function getList()
    {
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
            'href' => $this->url->link('localisation/state', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        $data['add'] = $this->url->link('localisation/state/add', 'token='.$this->session->data['token'].$url, 'SSL');
        $data['delete'] = $this->url->link('localisation/state/delete', 'token='.$this->session->data['token'].$url, 'SSL');

        $data['cities'] = [];

        $filter_data = [
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        $state_total = $this->model_localisation_state->getTotalCities();

        $results = $this->model_localisation_state->getCities($filter_data);

        foreach ($results as $result) {
            $data['cities'][] = [
                'state_id' => $result['state_id'],
                'name' => $result['name'],
                'status' => $result['status'],
                'sort_order' => $result['sort_order'],
                'edit' => $this->url->link('localisation/state/edit', 'token='.$this->session->data['token'].'&state_id='.$result['state_id'].$url, 'SSL'),
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
            $url .= '&page='.$this->request->get['page'];
        }

        $data['sort_name'] = $this->url->link('localisation/state', 'token='.$this->session->data['token'].'&sort=name'.$url, 'SSL');
        $data['sort_status'] = $this->url->link('localisation/state', 'token='.$this->session->data['token'].'&sort=status'.$url, 'SSL');
        $data['sort_sort_order'] = $this->url->link('localisation/state', 'token='.$this->session->data['token'].'&sort=sort_order'.$url, 'SSL');

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order='.$this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $state_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('localisation/state', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($state_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($state_total - $this->config->get('config_limit_admin'))) ? $state_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $state_total, ceil($state_total / $this->config->get('config_limit_admin')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('localisation/state_list.tpl', $data));
    }

    protected function getForm()
    {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !isset($this->request->get['state_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_select'] = $this->language->get('text_select');
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

        $url = '';

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
            'href' => $this->url->link('localisation/state', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        if (!isset($this->request->get['state_id'])) {
            $data['action'] = $this->url->link('localisation/state/add', 'token='.$this->session->data['token'].$url, 'SSL');
        } else {
            $data['action'] = $this->url->link('localisation/state/edit', 'token='.$this->session->data['token'].'&state_id='.$this->request->get['state_id'].$url, 'SSL');
        }

        $data['cancel'] = $this->url->link('localisation/state', 'token='.$this->session->data['token'].$url, 'SSL');

        $data['state_zipcodes'] = [];

        if (isset($this->request->get['state_id']) && ('POST' != $this->request->server['REQUEST_METHOD'])) {
            $state_info = $this->model_localisation_state->getstate($this->request->get['state_id']);

            $this->load->model('localisation/state');

            //$stateZipcodes = $this->model_localisation_state->getAllZipcodeBystate($this->request->get['state_id']);

            // if($stateZipcodes) {
            //     $data['state_zipcodes'] = $stateZipcodes;
            // }
        }

        $data['token'] = $this->session->data['token'];

        $this->load->model('setting/store');

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($state_info)) {
            $data['name'] = $state_info['name'];
        } else {
            $data['name'] = '';
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($state_info)) {
            $data['status'] = $state_info['status'];
        } else {
            $data['status'] = '';
        }

        if (isset($this->request->post['sort_order'])) {
            $data['sort_order'] = $this->request->post['sort_order'];
        } elseif (!empty($state_info)) {
            $data['sort_order'] = $state_info['sort_order'];
        } else {
            $data['sort_order'] = '';
        }

        //echo "<pre>";print_r($stateZipcodes);die;
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('localisation/state_form.tpl', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'localisation/state')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        return !$this->error;
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'localisation/state')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $this->load->model('sale/order');

        foreach ($this->request->post['selected'] as $state_id) {
            //check order

            $order_count = $this->model_sale_order->getTotalFromOrder($state_id);

            if ($order_count > 0) {
                $this->error['warning'] = sprintf($this->language->get('error_order_exist'), $order_count);
            }

            //customer address

            $address_count = $this->model_sale_order->getTotalFromAddress($state_id);

            if ($address_count > 0) {
                $this->error['warning'] = sprintf($this->language->get('error_address_exist'), $address_count);
            }
        }

        return !$this->error;
    }

    public function export_state_zipcodes()
    {
        $data = [];
        if (isset($this->request->get['state_id'])) {
            $data['state_id'] = $this->request->get['state_id'];
        } else {
            $data['state_id'] = '';
        }
        $this->load->model('report/excel');

        $state = $this->model_report_excel->getstate($data['state_id']);

        $data['state_name'] = $city['name'];

        $this->model_report_excel->download_cityzipcode_excel($data);
    }
}
