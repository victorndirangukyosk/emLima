<?php

class ControllerPaymentPezesha extends Controller {

    private $error = [];

    public function index() {
        $this->load->language('payment/pezesha');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validate()) {
            $this->model_setting_setting->editSetting('pezesha', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_merchant_key'] = $this->language->get('entry_merchant_key');
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

        if (isset($this->error['merchant_key'])) {
            $data['error_merchant_key'] = $this->error['merchant_key'];
        } else {
            $data['error_merchant_key'] = '';
        }

        if (isset($this->error['client_secret'])) {
            $data['error_client_secret'] = $this->error['client_secret'];
        } else {
            $data['error_client_secret'] = '';
        }

        if (isset($this->error['client_id'])) {
            $data['error_client_id'] = $this->error['client_id'];
        } else {
            $data['error_client_id'] = '';
        }
        
        if (isset($this->error['channel'])) {
            $data['error_channel'] = $this->error['channel'];
        } else {
            $data['error_channel'] = '';
        }
        
        if (isset($this->error['interest'])) {
            $data['error_interest'] = $this->error['interest'];
        } else {
            $data['error_interest'] = '';
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_payment'),
            'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('payment/pezesha', 'token=' . $this->session->data['token'], 'SSL'),
        ];

        $data['action'] = $this->url->link('payment/pezesha', 'token=' . $this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->post['pezesha_merchant_key'])) {
            $data['pezesha_merchant_key'] = $this->request->post['pezesha_merchant_key'];
        } else {
            $data['pezesha_merchant_key'] = $this->config->get('pezesha_merchant_key');
        }

        if (isset($this->request->post['pezesha_client_id'])) {
            $data['pezesha_client_id'] = $this->request->post['pezesha_client_id'];
        } else {
            $data['pezesha_client_id'] = $this->config->get('pezesha_client_id');
        }

        if (isset($this->request->post['pezesha_client_secret'])) {
            $data['pezesha_client_secret'] = $this->request->post['pezesha_client_secret'];
        } else {
            $data['pezesha_client_secret'] = $this->config->get('pezesha_client_secret');
        }
        
        if (isset($this->request->post['pezesha_channel'])) {
            $data['pezesha_channel'] = $this->request->post['pezesha_channel'];
        } else {
            $data['pezesha_channel'] = $this->config->get('pezesha_channel');
        }
        
        if (isset($this->request->post['pezesha_interest'])) {
            $data['pezesha_interest'] = $this->request->post['pezesha_interest'];
        } else {
            $data['pezesha_interest'] = $this->config->get('pezesha_interest');
        }

        if (isset($this->request->post['pezesha_environment'])) {
            $data['pezesha_environment'] = $this->request->post['pezesha_environment'];
        } else {
            $data['pezesha_environment'] = $this->config->get('pezesha_environment');
        }

        if (isset($this->request->post['pezesha_failed_order_status_id'])) {
            $data['pezesha_failed_order_status_id'] = $this->request->post['pezesha_failed_order_status_id'];
        } else {
            $data['pezesha_failed_order_status_id'] = $this->config->get('pezesha_failed_order_status_id');
        }

        if (isset($this->request->post['pezesha_pending_order_status_id'])) {
            $data['pezesha_pending_order_status_id'] = $this->request->post['pezesha_pending_order_status_id'];
        } else {
            $data['pezesha_pending_order_status_id'] = $this->config->get('pezesha_pending_order_status_id');
        }

        if (isset($this->request->post['pezesha_total'])) {
            $data['pezesha_total'] = $this->request->post['pezesha_total'];
        } else {
            $data['pezesha_total'] = $this->config->get('pezesha_total');
        }

        if (isset($this->request->post['pezesha_order_status_id'])) {
            $data['pezesha_order_status_id'] = $this->request->post['pezesha_order_status_id'];
        } else {
            $data['pezesha_order_status_id'] = $this->config->get('pezesha_order_status_id');
        }

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        if (isset($this->request->post['pezesha_status'])) {
            $data['pezesha_status'] = $this->request->post['pezesha_status'];
        } else {
            $data['pezesha_status'] = $this->config->get('pezesha_status');
        }

        if (isset($this->request->post['pezesha_sort_order'])) {
            $data['pezesha_sort_order'] = $this->request->post['pezesha_sort_order'];
        } else {
            $data['pezesha_sort_order'] = $this->config->get('pezesha_sort_order');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('payment/pezesha.tpl', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'payment/pezesha')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['pezesha_merchant_key']) {
            $this->error['merchant_key'] = $this->language->get('error_merchant_key');
        }
        if (!$this->request->post['pezesha_client_secret']) {
            $this->error['client_secret'] = $this->language->get('error_client_secret');
        }
        if (!$this->request->post['pezesha_client_id']) {
            $this->error['client_id'] = $this->language->get('error_client_id');
        }
        if (!$this->request->post['pezesha_channel']) {
            $this->error['channel'] = $this->language->get('error_channel');
        }
        if (!$this->request->post['pezesha_interest']) {
            $this->error['interest'] = $this->language->get('error_interest');
        }

        return !$this->error;
    }

}
