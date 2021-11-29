<?php

class ControllerSettingTestimonial extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('setting/testimonial');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/testimonial');

        $this->getList();
    }

    public function add()
    {
        $this->load->language('setting/testimonial');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/testimonial');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $testimonial_id = $this->model_setting_testimonial->addTestimonial($this->request->post);

            $this->load->model('setting/setting');

            // !empty($this->request->post['date_added']) ? : $this->request->post['date_added'] = $this->request->post['config_name'];

            //$this->model_setting_setting->editSetting('config', $this->request->post, $testimonial_id);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('setting/testimonial', 'token='.$this->session->data['token'], 'SSL'));
        }

        $this->getForm();
    }

    public function edit()
    {
        $this->load->language('setting/testimonial');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/testimonial');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $this->model_setting_testimonial->editTestimonial($this->request->get['testimonial_id'], $this->request->post);

            $this->load->model('setting/setting');

            !empty($this->request->post['date_added']) ?: $this->request->post['date_added'] = $this->request->post['config_name'];

            // $this->model_setting_setting->editSetting('config', $this->request->post, $this->request->get['testimonial_id']);

            $this->session->data['success'] = $this->language->get('text_success');

            if (isset($this->request->post['button']) and 'save' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('setting/testimonial/edit', 'testimonial_id='.$this->request->get['testimonial_id'].'&token='.$this->session->data['token'].$url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('setting/testimonial/add', 'token='.$this->session->data['token'].$url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'save' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('setting/testimonial/edit', 'testimonial_id='.$this->request->get['testimonial_id'].'&token='.$this->session->data['token'].$url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('setting/testimonial/add', 'token='.$this->session->data['token'].$url, 'SSL'));
            }

            $this->response->redirect($this->url->link('setting/testimonial', 'token='.$this->session->data['token'].'&testimonial_id='.$this->request->get['testimonial_id'], 'SSL'));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->load->language('setting/testimonial');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/testimonial');

        $this->load->model('setting/setting');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $testimonial_id) {
                $this->model_setting_testimonial->deleteTestimonial($testimonial_id);

                //  $this->model_setting_setting->deleteSetting('config', $testimonial_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('setting/testimonial', 'token='.$this->session->data['token'], 'SSL'));
        }

        $this->getList();
    }

    protected function getList()
    {
        $url = '';

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
            'href' => $this->url->link('setting/testimonial', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['add'] = $this->url->link('setting/testimonial/add', 'token='.$this->session->data['token'], 'SSL');
        $data['delete'] = $this->url->link('setting/testimonial/delete', 'token='.$this->session->data['token'], 'SSL');

        $data['testimonials'] = [];

        $testimonial_total = $this->model_setting_testimonial->getTotalTestimonials();

        $results = $this->model_setting_testimonial->getTestimonials();

        foreach ($results as $result) {
            $data['testimonials'][] = [
                'testimonial_id' => $result['testimonial_id'],
                'name' => $result['name'],
                'message' => $result['message'],
                'sort_order' => $result['sort_order'],
                'status' => $result['status'],
                'edit' => $this->url->link('setting/testimonial/edit', 'token='.$this->session->data['token'].'&testimonial_id='.$result['testimonial_id'], 'SSL'),
            ];
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_enable'] = $this->language->get('text_enable');
        $data['text_disable'] = $this->language->get('text_disable');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_url'] = $this->language->get('column_url');
        $data['column_action'] = $this->language->get('column_action');
        $data['column_message'] = $this->language->get('column_message');
        $data['column_sort_order'] = $this->language->get('column_sort_order');
        $data['column_status'] = $this->language->get('column_status');

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

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('setting/testimonial_list.tpl', $data));
    }

    public function getForm()
    {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_testimonials'] = $this->language->get('text_testimonials');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_message'] = $this->language->get('entry_message');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['entry_status'] = $this->language->get('entry_status');

        $data['text_enable'] = $this->language->get('text_enable');
        $data['text_disable'] = $this->language->get('text_disable');

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

        if (isset($this->error['message'])) {
            $data['error_message'] = $this->error['message'];
        } else {
            $data['error_message'] = '';
        }

        if (isset($this->error['image'])) {
            $data['error_image'] = $this->error['image'];
        } else {
            $data['error_image'] = '';
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('setting/testimonial', 'token='.$this->session->data['token'], 'SSL'),
        ];

        if (!isset($this->request->get['testimonial_id'])) {
            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_settings'),
                'href' => $this->url->link('setting/testimonial/add', 'token='.$this->session->data['token'], 'SSL'),
            ];
        } else {
            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_settings'),
                'href' => $this->url->link('setting/testimonial/edit', 'token='.$this->session->data['token'].'&testimonial_id='.$this->request->get['testimonial_id'], 'SSL'),
            ];
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (!isset($this->request->get['testimonial_id'])) {
            $data['action'] = $this->url->link('setting/testimonial/add', 'token='.$this->session->data['token'], 'SSL');
        } else {
            $data['action'] = $this->url->link('setting/testimonial/edit', 'token='.$this->session->data['token'].'&testimonial_id='.$this->request->get['testimonial_id'], 'SSL');
        }

        $data['cancel'] = $this->url->link('setting/testimonial', 'token='.$this->session->data['token'], 'SSL');

        if (isset($this->request->get['testimonial_id']) && ('POST' != $this->request->server['REQUEST_METHOD'])) {
            $this->load->model('setting/testimonial');

            $testimonial_info = $this->model_setting_testimonial->getTestimonial($this->request->get['testimonial_id']);
        }

        $data['token'] = $this->session->data['token'];

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (isset($testimonial_info['name'])) {
            $data['name'] = $testimonial_info['name'];
        } else {
            $data['name'] = '';
        }

        if (isset($this->request->post['message'])) {
            $data['message'] = $this->request->post['message'];
        } elseif (isset($testimonial_info['message'])) {
            $data['message'] = $testimonial_info['message'];
        } else {
            $data['message'] = '';
        }

        if (isset($this->request->post['image'])) {
            $data['image'] = $this->request->post['image'];
        } elseif (isset($testimonial_info)) {
            $data['image'] = $testimonial_info['image'];
        } else {
            $data['image'] = '';
        }

        if (isset($this->request->post['sort_order'])) {
            $data['sort_order'] = $this->request->post['sort_order'];
        } elseif (isset($testimonial_info['sort_order'])) {
            $data['sort_order'] = $testimonial_info['sort_order'];
        } else {
            $data['sort_order'] = '';
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (isset($testimonial_info['status'])) {
            $data['status'] = $testimonial_info['status'];
        } else {
            $data['status'] = '';
        }

        $this->load->model('tool/image');

        if (isset($this->request->post['image']) && is_file(DIR_IMAGE.$this->request->post['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
        } elseif (isset($testimonial_info['image']) && is_file(DIR_IMAGE.$testimonial_info['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($testimonial_info['image'], 100, 100);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('setting/testimonial_form.tpl', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'setting/testimonial')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['name']) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if ((utf8_strlen($this->request->post['message']) < 3) || (utf8_strlen($this->request->post['message']) > 256)) {
            $this->error['message'] = $this->language->get('error_message');
        }

        if (!$this->request->post['image']) {
            $this->error['image'] = $this->language->get('error_image');
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'setting/testimonial')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $this->load->model('sale/order');

        foreach ($this->request->post['selected'] as $testimonial_id) {
            if (!$testimonial_id) {
                $this->error['warning'] = $this->language->get('error_default');
            }

            $testimonial_total = $this->model_sale_order->getTotalOrdersByTestimonialId($testimonial_id);

            if ($testimonial_total) {
                $this->error['warning'] = sprintf($this->language->get('error_testimonial'), $testimonial_total);
            }
        }

        return !$this->error;
    }
}
