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

        $data['entry_merchant_code'] = $this->language->get('entry_merchant_code');
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

        if (isset($this->error['merchant_code'])) {
            $data['error_merchant_code'] = $this->error['merchant_code'];
        } else {
            $data['error_merchant_code'] = '';
        }

        if (isset($this->error['pay_item_id'])) {
            $data['error_pay_item_id'] = $this->error['pay_item_id'];
        } else {
            $data['error_pay_item_id'] = '';
        }

        if (isset($this->error['data_ref'])) {
            $data['error_data_ref'] = $this->error['data_ref'];
        } else {
            $data['error_data_ref'] = '';
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

        if (isset($this->request->post['pezesha_merchant_code'])) {
            $data['pezesha_merchant_code'] = $this->request->post['pezesha_merchant_code'];
        } else {
            $data['pezesha_merchant_code'] = $this->config->get('pezesha_merchant_code');
        }

        if (isset($this->request->post['pezesha_pay_item_id'])) {
            $data['pezesha_pay_item_id'] = $this->request->post['pezesha_pay_item_id'];
        } else {
            $data['pezesha_pay_item_id'] = $this->config->get('pezesha_pay_item_id');
        }

        if (isset($this->request->post['pezesha_data_ref'])) {
            $data['pezesha_data_ref'] = $this->request->post['pezesha_data_ref'];
        } else {
            $data['pezesha_data_ref'] = $this->config->get('pezesha_data_ref');
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

        if (!$this->request->post['pezesha_merchant_code']) {
            $this->error['merchant_code'] = $this->language->get('error_merchant_code');
        }
        if (!$this->request->post['pezesha_pay_item_id']) {
            $this->error['pay_item_id'] = $this->language->get('error_pay_item_id');
        }
        if (!$this->request->post['pezesha_data_ref']) {
            $this->error['data_ref'] = $this->language->get('error_data_ref');
        }

        return !$this->error;
    }

}
