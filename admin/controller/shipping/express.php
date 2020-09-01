<?php

class ControllerShippingExpress extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('shipping/express');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validate()) {
            //echo "<pre>";print_r($this->request->post);die;
            $this->model_setting_setting->editSetting('express', $this->request->post);

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

            $this->response->redirect($this->url->link('extension/shipping', 'token='.$this->session->data['token'], 'SSL'));
        }

        if (isset($this->request->post['delivery_timeslots'])) {
            $data['delivery_timeslots'] = [];

            //get delivery_timeslots
            if (!empty($this->request->post['delivery_timeslots'][0])) {
                foreach ($this->request->post['delivery_timeslots'][0] as $timeslot => $temp) {
                    $data['delivery_timeslots'][] = [
                        'timeslot' => $timeslot,
                        0 => $this->request->post['delivery_timeslots'][0][$timeslot],
                        1 => $this->request->post['delivery_timeslots'][1][$timeslot],
                        2 => $this->request->post['delivery_timeslots'][2][$timeslot],
                        3 => $this->request->post['delivery_timeslots'][3][$timeslot],
                        4 => $this->request->post['delivery_timeslots'][4][$timeslot],
                        5 => $this->request->post['delivery_timeslots'][5][$timeslot],
                        6 => $this->request->post['delivery_timeslots'][6][$timeslot],
                    ];
                }
            }
        } else {
            $code = 'express';
            $delivery_timeslots = $this->model_setting_setting->getDeliveryTimeslots($code);
            $data['delivery_timeslots'] = [];

            foreach ($delivery_timeslots as $timeslot) {
                $data['delivery_timeslots'][] = [
                    'timeslot' => $timeslot['timeslot'],
                    0 => $this->model_setting_setting->getDeliveryStatus($timeslot['timeslot'], 0, $code),
                    1 => $this->model_setting_setting->getDeliveryStatus($timeslot['timeslot'], 1, $code),
                    2 => $this->model_setting_setting->getDeliveryStatus($timeslot['timeslot'], 2, $code),
                    3 => $this->model_setting_setting->getDeliveryStatus($timeslot['timeslot'], 3, $code),
                    4 => $this->model_setting_setting->getDeliveryStatus($timeslot['timeslot'], 4, $code),
                    5 => $this->model_setting_setting->getDeliveryStatus($timeslot['timeslot'], 5, $code),
                    6 => $this->model_setting_setting->getDeliveryStatus($timeslot['timeslot'], 6, $code),
                ];
            }
        }

        $data['entry_add_timeslot'] = $this->language->get('entry_add_timeslot');
        $data['button_add_timeslot'] = $this->language->get('button_add_timeslot');

        $data['column_timeslot'] = $this->language->get('column_timeslot');
        $data['column_sunday'] = $this->language->get('column_sunday');
        $data['column_monday'] = $this->language->get('column_monday');
        $data['column_tuesday'] = $this->language->get('column_tuesday');
        $data['column_wesnesday'] = $this->language->get('column_wesnesday');
        $data['column_thursday'] = $this->language->get('column_thursday');
        $data['column_friday'] = $this->language->get('column_friday');
        $data['column_saturday'] = $this->language->get('column_saturday');

        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_all_zones'] = $this->language->get('text_all_zones');
        $data['text_none'] = $this->language->get('text_none');

        $data['entry_total'] = $this->language->get('entry_total');
        $data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['entry_cost'] = $this->language->get('entry_cost');
        $data['entry_free_delivery_amount'] = $this->language->get('entry_free_delivery_amount');

        $data['help_total'] = $this->language->get('help_total');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_savenew'] = $this->language->get('button_savenew');
        $data['button_saveclose'] = $this->language->get('button_saveclose');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['entry_home_delivery_time_difference'] = $this->language->get('entry_home_delivery_time_difference');

        $data['entry_how_much_time'] = $this->language->get('entry_how_much_time');

        if (isset($this->error['express_how_much_time'])) {
            $data['error_how_much_time'] = $this->error['express_how_much_time'];
        } else {
            $data['error_how_much_time'] = '';
        }

        if (isset($this->request->post['express_how_much_time'])) {
            $data['express_how_much_time'] = $this->request->post['express_how_much_time'];
        } elseif ($this->config->has('express_how_much_time')) {
            $data['express_how_much_time'] = $this->config->get('express_how_much_time');
        } else {
            $data['express_how_much_time'] = '00:00';
        }

        if (isset($this->error['express_delivery_time_diff'])) {
            $data['error_delivery_time_diff'] = $this->error['express_delivery_time_diff'];
        } else {
            $data['error_delivery_time_diff'] = '';
        }

        if (isset($this->request->post['express_delivery_time_diff'])) {
            $data['express_delivery_time_diff'] = $this->request->post['express_delivery_time_diff'];
        } elseif ($this->config->has('express_delivery_time_diff')) {
            $data['express_delivery_time_diff'] = $this->config->get('express_delivery_time_diff');
        } else {
            $data['express_delivery_time_diff'] = '00:00';
        }

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
            'text' => $this->language->get('text_shipping'),
            'href' => $this->url->link('extension/shipping', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('shipping/express', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['action'] = $this->url->link('shipping/express', 'token='.$this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('extension/shipping', 'token='.$this->session->data['token'], 'SSL');

        if (isset($this->request->post['express_total'])) {
            $data['express_total'] = $this->request->post['express_total'];
        } else {
            $data['express_total'] = $this->config->get('express_total');
        }

        if (isset($this->request->post['express_free_delivery_amount'])) {
            $data['express_free_delivery_amount'] = $this->request->post['express_free_delivery_amount'];
        } else {
            $data['express_free_delivery_amount'] = $this->config->get('express_free_delivery_amount');
        }

        if (isset($this->request->post['express_use_deliverysystem'])) {
            $data['express_use_deliverysystem'] = $this->request->post['express_use_deliverysystem'];
        } else {
            $data['express_use_deliverysystem'] = $this->config->get('express_use_deliverysystem');
        }

        if (isset($this->request->post['express_cost'])) {
            $data['express_cost'] = $this->request->post['express_cost'];
        } else {
            $data['express_cost'] = $this->config->get('express_cost');
        }

        if (isset($this->request->post['express_status'])) {
            $data['express_status'] = $this->request->post['express_status'];
        } else {
            $data['express_status'] = $this->config->get('express_status');
        }

        if (isset($this->request->post['express_sort_order'])) {
            $data['express_sort_order'] = $this->request->post['express_sort_order'];
        } else {
            $data['express_sort_order'] = $this->config->get('express_sort_order');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('shipping/express.tpl', $data));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'shipping/express')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}
