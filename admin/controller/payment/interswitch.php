<?php

class ControllerPaymentInterswitch extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('payment/interswitch');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validate()) {
            $this->model_setting_setting->editSetting('interswitch', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/payment', 'token='.$this->session->data['token'], 'SSL'));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_consumer_key'] = $this->language->get('entry_consumer_key');
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

        if (isset($this->error['consumer_key'])) {
            $data['error_consumer_key'] = $this->error['consumer_key'];
        } else {
            $data['error_consumer_key'] = '';
        }

        if (isset($this->error['consumer_secret'])) {
            $data['error_consumer_secret'] = $this->error['consumer_secret'];
        } else {
            $data['error_consumer_secret'] = '';
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
            'href' => $this->url->link('payment/interswitch', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['action'] = $this->url->link('payment/interswitch', 'token='.$this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('extension/payment', 'token='.$this->session->data['token'], 'SSL');

        if (isset($this->request->post['interswitch_consumer_key'])) {
            $data['interswitch_consumer_key'] = $this->request->post['interswitch_consumer_key'];
        } else {
            $data['interswitch_consumer_key'] = $this->config->get('interswitch_consumer_key');
        }

        if (isset($this->request->post['interswitch_consumer_secret'])) {
            $data['interswitch_consumer_secret'] = $this->request->post['interswitch_consumer_secret'];
        } else {
            $data['interswitch_consumer_secret'] = $this->config->get('interswitch_consumer_secret');
        }

        if (isset($this->request->post['interswitch_environment'])) {
            $data['interswitch_environment'] = $this->request->post['interswitch_environment'];
        } else {
            $data['interswitch_environment'] = $this->config->get('interswitch_environment');
        }

        if (isset($this->request->post['interswitch_failed_order_status_id'])) {
            $data['interswitch_failed_order_status_id'] = $this->request->post['interswitch_failed_order_status_id'];
        } else {
            $data['interswitch_failed_order_status_id'] = $this->config->get('interswitch_failed_order_status_id');
        }
        
        if (isset($this->request->post['interswitch_pending_order_status_id'])) {
            $data['interswitch_pending_order_status_id'] = $this->request->post['interswitch_pending_order_status_id'];
        } else {
            $data['interswitch_pending_order_status_id'] = $this->config->get('interswitch_pending_order_status_id');
        }

        if (isset($this->request->post['interswitch_total'])) {
            $data['interswitch_total'] = $this->request->post['interswitch_total'];
        } else {
            $data['interswitch_total'] = $this->config->get('interswitch_total');
        }

        if (isset($this->request->post['interswitch_order_status_id'])) {
            $data['interswitch_order_status_id'] = $this->request->post['interswitch_order_status_id'];
        } else {
            $data['interswitch_order_status_id'] = $this->config->get('interswitch_order_status_id');
        }

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        if (isset($this->request->post['interswitch_status'])) {
            $data['interswitch_status'] = $this->request->post['interswitch_status'];
        } else {
            $data['interswitch_status'] = $this->config->get('interswitch_status');
        }

        if (isset($this->request->post['interswitch_sort_order'])) {
            $data['interswitch_sort_order'] = $this->request->post['interswitch_sort_order'];
        } else {
            $data['interswitch_sort_order'] = $this->config->get('interswitch_sort_order');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('payment/interswitch.tpl', $data));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'payment/interswitch')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['interswitch_consumer_key']) {
            $this->error['consumer_key'] = $this->language->get('error_consumer_key');
        }
        if (!$this->request->post['interswitch_consumer_secret']) {
            $this->error['consumer_secret'] = $this->language->get('error_consumer_secret');
        }

        return !$this->error;
    }
}
