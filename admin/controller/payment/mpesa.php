<?php

class ControllerPaymentMpesa extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('payment/mpesa');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validate()) {
            $this->model_setting_setting->editSetting('mpesa', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/payment', 'token='.$this->session->data['token'], 'SSL'));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_customer_key'] = $this->language->get('entry_customer_key');
        $data['entry_total'] = $this->language->get('entry_total');
        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $data['help_total'] = $this->language->get('help_total');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

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

        if (isset($this->error['customer_key'])) {
            $data['error_customer_key'] = $this->error['customer_key'];
        } else {
            $data['error_customer_key'] = '';
        }

        if (isset($this->error['customer_secret'])) {
            $data['error_customer_secret'] = $this->error['customer_secret'];
        } else {
            $data['error_customer_secret'] = '';
        }

        if (isset($this->error['business_short_code'])) {
            $data['error_business_short_code'] = $this->error['business_short_code'];
        } else {
            $data['error_business_short_code'] = '';
        }
        if (isset($this->error['lipanampesapasskey'])) {
            $data['error_lipanampesapasskey'] = $this->error['lipanampesapasskey'];
        } else {
            $data['error_lipanampesapasskey'] = '';
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_payment'),
            'href' => $this->url->link('extension/payment', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('payment/mpesa', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['action'] = $this->url->link('payment/mpesa', 'token='.$this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('extension/payment', 'token='.$this->session->data['token'], 'SSL');

        if (isset($this->request->post['mpesa_customer_key'])) {
            $data['mpesa_customer_key'] = $this->request->post['mpesa_customer_key'];
        } else {
            $data['mpesa_customer_key'] = $this->config->get('mpesa_customer_key');
        }

        if (isset($this->request->post['mpesa_customer_secret'])) {
            $data['mpesa_customer_secret'] = $this->request->post['mpesa_customer_secret'];
        } else {
            $data['mpesa_customer_secret'] = $this->config->get('mpesa_customer_secret');
        }

        if (isset($this->request->post['mpesa_environment'])) {
            $data['mpesa_environment'] = $this->request->post['mpesa_environment'];
        } else {
            $data['mpesa_environment'] = $this->config->get('mpesa_environment');
        }

        if (isset($this->request->post['mpesa_business_short_code'])) {
            $data['mpesa_business_short_code'] = $this->request->post['mpesa_business_short_code'];
        } else {
            $data['mpesa_business_short_code'] = $this->config->get('mpesa_business_short_code');
        }

        if (isset($this->request->post['mpesa_lipanampesapasskey'])) {
            $data['mpesa_lipanampesapasskey'] = $this->request->post['mpesa_lipanampesapasskey'];
        } else {
            $data['mpesa_lipanampesapasskey'] = $this->config->get('mpesa_lipanampesapasskey');
        }

        if (isset($this->request->post['mpesa_failed_order_status_id'])) {
            $data['mpesa_failed_order_status_id'] = $this->request->post['mpesa_failed_order_status_id'];
        } else {
            $data['mpesa_failed_order_status_id'] = $this->config->get('mpesa_failed_order_status_id');
        }

        if (isset($this->request->post['mpesa_total'])) {
            $data['mpesa_total'] = $this->request->post['mpesa_total'];
        } else {
            $data['mpesa_total'] = $this->config->get('mpesa_total');
        }

        if (isset($this->request->post['mpesa_order_status_id'])) {
            $data['mpesa_order_status_id'] = $this->request->post['mpesa_order_status_id'];
        } else {
            $data['mpesa_order_status_id'] = $this->config->get('mpesa_order_status_id');
        }

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        if (isset($this->request->post['mpesa_status'])) {
            $data['mpesa_status'] = $this->request->post['mpesa_status'];
        } else {
            $data['mpesa_status'] = $this->config->get('mpesa_status');
        }

        if (isset($this->request->post['mpesa_sort_order'])) {
            $data['mpesa_sort_order'] = $this->request->post['mpesa_sort_order'];
        } else {
            $data['mpesa_sort_order'] = $this->config->get('mpesa_sort_order');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('payment/mpesa.tpl', $data));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'payment/mpesa')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['mpesa_customer_key']) {
            $this->error['customer_key'] = $this->language->get('error_customer_key');
        }
        if (!$this->request->post['mpesa_customer_secret']) {
            $this->error['customer_secret'] = $this->language->get('error_customer_secret');
        }
        if (!$this->request->post['mpesa_business_short_code']) {
            $this->error['business_short_code'] = $this->language->get('error_business_short_code');
        }
        if (!$this->request->post['mpesa_lipanampesapasskey']) {
            $this->error['lipanampesapasskey'] = $this->language->get('error_lipanampesapasskey');
        }

        return !$this->error;
    }
}
