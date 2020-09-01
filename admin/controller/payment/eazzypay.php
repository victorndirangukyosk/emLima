<?php

class ControllerPaymentEazzypay extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('payment/eazzypay');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validate()) {
            $this->model_setting_setting->editSetting('eazzypay', $this->request->post);

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

        if (isset($this->error['customer_secret'])) {
            $data['error_merchant_code'] = $this->error['customer_secret'];
        } else {
            $data['error_merchant_code'] = '';
        }

        if (isset($this->error['customer_secret'])) {
            $data['error_password'] = $this->error['customer_secret'];
        } else {
            $data['error_password'] = '';
        }

        if (isset($this->error['business_short_code'])) {
            $data['error_business_short_code'] = $this->error['business_short_code'];
        } else {
            $data['error_business_short_code'] = '';
        }
        if (isset($this->error['lipanaeazzypaypasskey'])) {
            $data['error_lipanaeazzypaypasskey'] = $this->error['lipanaeazzypaypasskey'];
        } else {
            $data['error_lipanaeazzypaypasskey'] = '';
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
            'href' => $this->url->link('payment/eazzypay', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['action'] = $this->url->link('payment/eazzypay', 'token='.$this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('extension/payment', 'token='.$this->session->data['token'], 'SSL');

        if (isset($this->request->post['eazzypay_customer_key'])) {
            $data['eazzypay_customer_key'] = $this->request->post['eazzypay_customer_key'];
        } else {
            $data['eazzypay_customer_key'] = $this->config->get('eazzypay_customer_key');
        }

        if (isset($this->request->post['eazzypay_customer_secret'])) {
            $data['eazzypay_customer_secret'] = $this->request->post['eazzypay_customer_secret'];
        } else {
            $data['eazzypay_customer_secret'] = $this->config->get('eazzypay_customer_secret');
        }

        if (isset($this->request->post['eazzypay_merchant_code'])) {
            $data['eazzypay_merchant_code'] = $this->request->post['eazzypay_merchant_code'];
        } else {
            $data['eazzypay_merchant_code'] = $this->config->get('eazzypay_merchant_code');
        }

        if (isset($this->request->post['eazzypay_password'])) {
            $data['eazzypay_password'] = $this->request->post['eazzypay_password'];
        } else {
            $data['eazzypay_password'] = $this->config->get('eazzypay_password');
        }

        if (isset($this->request->post['eazzypay_failed_order_status_id'])) {
            $data['eazzypay_failed_order_status_id'] = $this->request->post['eazzypay_failed_order_status_id'];
        } else {
            $data['eazzypay_failed_order_status_id'] = $this->config->get('eazzypay_failed_order_status_id');
        }

        if (isset($this->request->post['eazzypay_total'])) {
            $data['eazzypay_total'] = $this->request->post['eazzypay_total'];
        } else {
            $data['eazzypay_total'] = $this->config->get('eazzypay_total');
        }

        if (isset($this->request->post['eazzypay_order_status_id'])) {
            $data['eazzypay_order_status_id'] = $this->request->post['eazzypay_order_status_id'];
        } else {
            $data['eazzypay_order_status_id'] = $this->config->get('eazzypay_order_status_id');
        }

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        if (isset($this->request->post['eazzypay_status'])) {
            $data['eazzypay_status'] = $this->request->post['eazzypay_status'];
        } else {
            $data['eazzypay_status'] = $this->config->get('eazzypay_status');
        }

        if (isset($this->request->post['eazzypay_sort_order'])) {
            $data['eazzypay_sort_order'] = $this->request->post['eazzypay_sort_order'];
        } else {
            $data['eazzypay_sort_order'] = $this->config->get('eazzypay_sort_order');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('payment/eazzypay.tpl', $data));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'payment/eazzypay')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['eazzypay_customer_key']) {
            $this->error['customer_key'] = $this->language->get('error_customer_key');
        }
        if (!$this->request->post['eazzypay_customer_secret']) {
            $this->error['customer_secret'] = $this->language->get('error_customer_secret');
        }

        return !$this->error;
    }
}
