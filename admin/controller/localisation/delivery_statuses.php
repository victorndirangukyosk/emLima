<?php

class ControllerLocalisationDeliveryStatuses extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('localisation/delivery_statuses');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('localisation/delivery_statuses');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $this->model_localisation_tax_class->editTaxClass($this->request->get['tax_class_id'], $this->request->post);

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
                $this->response->redirect($this->url->link('localisation/tax_class/edit', 'tax_class_id='.$this->request->get['tax_class_id'].'&token='.$this->session->data['token'].$url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('localisation/tax_class/add', 'token='.$this->session->data['token'].$url, 'SSL'));
            }

            $this->response->redirect($this->url->link('localisation/tax_class', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getForm();
    }

    public function add()
    {
        $this->load->language('localisation/delivery_statuses');

        $this->document->setTitle($this->language->get('heading_title'));

        //$this->load->model('localisation/delivery_statuses');
        //echo "<pre>";print_r($this->request->post);die;
        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $this->load->model('localisation/order_status');

            $saveDeliveryStatuses = $this->model_localisation_order_status->saveDeliveryStatuses($this->request->post['delivery_status']);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            $this->response->redirect($this->url->link('localisation/delivery_statuses', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getForm();
    }

    protected function getForm()
    {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !isset($this->request->get['tax_class_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_shipping'] = $this->language->get('text_shipping');
        $data['text_payment'] = $this->language->get('text_payment');
        $data['text_store'] = $this->language->get('text_store');

        $data['entry_title'] = $this->language->get('entry_title');
        $data['entry_description'] = $this->language->get('entry_description');
        $data['entry_code'] = $this->language->get('entry_code');
        $data['entry_based'] = $this->language->get('entry_based');
        $data['entry_trigger'] = $this->language->get('entry_trigger');

        $data['entry_order_statuses'] = $this->language->get('entry_order_statuses');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_savenew'] = $this->language->get('button_savenew');
        $data['button_saveclose'] = $this->language->get('button_saveclose');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_rule_add'] = $this->language->get('button_rule_add');
        $data['button_remove'] = $this->language->get('button_remove');

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

        if (isset($this->error['title'])) {
            $data['error_title'] = $this->error['title'];
        } else {
            $data['error_title'] = '';
        }

        if (isset($this->error['description'])) {
            $data['error_description'] = $this->error['description'];
        } else {
            $data['error_description'] = '';
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
            'href' => $this->url->link('localisation/tax_class', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        if (!isset($this->request->get['tax_class_id'])) {
            $data['action'] = $this->url->link('localisation/delivery_statuses/add', 'token='.$this->session->data['token'].$url, 'SSL');
        } else {
            $data['action'] = $this->url->link('localisation/delivery_statuses/edit', 'token='.$this->session->data['token'].'&tax_class_id='.$this->request->get['tax_class_id'].$url, 'SSL');
        }

        $data['cancel'] = $this->url->link('localisation/delivery_statuses', 'token='.$this->session->data['token'].$url, 'SSL');

        if (isset($this->request->get['tax_class_id']) && ('POST' != $this->request->server['REQUEST_METHOD'])) {
            $tax_class_info = $this->model_localisation_tax_class->getTaxClass($this->request->get['tax_class_id']);
        }

        if (isset($this->request->post['title'])) {
            $data['title'] = $this->request->post['title'];
        } elseif (!empty($tax_class_info)) {
            $data['title'] = $tax_class_info['title'];
        } else {
            $data['title'] = '';
        }

        if (isset($this->request->post['description'])) {
            $data['description'] = $this->request->post['description'];
        } elseif (!empty($tax_class_info)) {
            $data['description'] = $tax_class_info['description'];
        } else {
            $data['description'] = '';
        }

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $db_delviery_statuses = $this->model_localisation_order_status->getDeliveryStatuses();

        //echo "<pre>";print_r($db_delviery_statuses);die;
        //
        $data['email'] = $this->config->get('config_delivery_username');
        $data['password'] = $this->config->get('config_delivery_secret');
        $response = $this->load->controller('deliversystem/deliversystem/getToken', $data);

        $apiDeliveryStatuses = [];

        if ($response['status']) {
            $data['token'] = $response['token'];
            $res = $this->load->controller('deliversystem/deliversystem/getAllDeliveryStatus', $data);

            $apiDeliveryStatuses = json_decode($res['data'], true);
        }

        //echo "<pre>";print_r($apiDeliveryStatuses);die;
        $data['delivery_statuses'] = [];

        if (isset($this->request->post['delivery_statuses'])) {
            $data['delivery_statuses'] = $this->request->post['delivery_statuses'];
        } else {
            // $apiDeliveryStatuses = [ 0 => ['code' => '00','status' => 'pending'],1 => ['code' => '01','status' => 'processing']];

            foreach ($apiDeliveryStatuses as $apiStatus) {
                $orderStatusId = false;
                foreach ($db_delviery_statuses as $dbDeliveryStatuses) {
                    if ($apiStatus['code'] == $dbDeliveryStatuses['code']) {
                        $orderStatusId = $dbDeliveryStatuses['order_status_id'];
                        break;
                    }
                }

                $var['code'] = $apiStatus['code'];
                $var['status'] = $apiStatus['trigger'];
                $var['order_status_id'] = $orderStatusId;

                array_push($data['delivery_statuses'], $var);
            }
        }

        //echo "<pre>";print_r($data['delivery_statuses']);die;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('localisation/delivery_statuses_form.tpl', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'localisation/delivery_statuses')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        // if ((utf8_strlen($this->request->post['title']) < 3) || (utf8_strlen($this->request->post['title']) > 32)) {
        //     $this->error['title'] = $this->language->get('error_title');
        // }

        // if ((utf8_strlen($this->request->post['description']) < 3) || (utf8_strlen($this->request->post['description']) > 255)) {
        //     $this->error['description'] = $this->language->get('error_description');
        // }

        return !$this->error;
    }
}
