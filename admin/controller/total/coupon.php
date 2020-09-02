<?php

class ControllerTotalCoupon extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('total/coupon');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validate()) {
            $this->model_setting_setting->editSetting('coupon', $this->request->post);

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

            $this->response->redirect($this->url->link('extension/total', 'token='.$this->session->data['token'], 'SSL'));
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_savenew'] = $this->language->get('button_savenew');
        $data['button_saveclose'] = $this->language->get('button_saveclose');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_total'),
            'href' => $this->url->link('extension/total', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('total/coupon', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['action'] = $this->url->link('total/coupon', 'token='.$this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('extension/total', 'token='.$this->session->data['token'], 'SSL');

        if (isset($this->request->post['coupon_status'])) {
            $data['coupon_status'] = $this->request->post['coupon_status'];
        } else {
            $data['coupon_status'] = $this->config->get('coupon_status');
        }

        if (isset($this->request->post['coupon_sort_order'])) {
            $data['coupon_sort_order'] = $this->request->post['coupon_sort_order'];
        } else {
            $data['coupon_sort_order'] = $this->config->get('coupon_sort_order');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('total/coupon.tpl', $data));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'total/coupon')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}
