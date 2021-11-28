<?php

class ControllerPaymentMod extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('payment/mod');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validate()) {
            $this->model_setting_setting->editSetting('mod', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            if (isset($this->request->post['button']) and 'save' == $this->request->post['button']) {
                $path = $this->request->get['path'];
                $module_id = '';
                if (isset($this->request->get['module_id'])) {
                    $module_id = '&module_id='.$this->request->get['module_id'];
                } elseif ($this->db->getLastId()) {
                    $module_id = '&module_id='.$this->db->getLastId();
                }
                $this->response->redirect($this->url->link($path, 'token='.$this->session->data['token'].$module_id, 'SSL'));
            }

            $this->response->redirect($this->url->link('extension/payment', 'token='.$this->session->data['token'], 'SSL'));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_total'] = $this->language->get('entry_total');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $data['help_total'] = $this->language->get('help_total');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_savenew'] = $this->language->get('button_savenew');
        $data['button_saveclose'] = $this->language->get('button_saveclose');
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
            'href' => $this->url->link('payment/mod', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['action'] = $this->url->link('payment/mod', 'token='.$this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('extension/payment', 'token='.$this->session->data['token'], 'SSL');

        if (isset($this->request->post['mod_total'])) {
            $data['mod_total'] = $this->request->post['mod_total'];
        } else {
            $data['mod_total'] = $this->config->get('mod_total');
        }

        if (isset($this->request->post['mod_order_status_id'])) {
            $data['mod_order_status_id'] = $this->request->post['mod_order_status_id'];
        } else {
            $data['mod_order_status_id'] = $this->config->get('mod_order_status_id');
        }

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        if (isset($this->request->post['mod_status'])) {
            $data['mod_status'] = $this->request->post['mod_status'];
        } else {
            $data['mod_status'] = $this->config->get('mod_status');
        }

        if (isset($this->request->post['mod_sort_order'])) {
            $data['mod_sort_order'] = $this->request->post['mod_sort_order'];
        } else {
            $data['mod_sort_order'] = $this->config->get('mod_sort_order');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('payment/mod.tpl', $data));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'payment/mod')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}
