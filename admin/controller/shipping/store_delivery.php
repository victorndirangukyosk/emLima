<?php

class ControllerShippingStoreDelivery extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('shipping/store_delivery');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validate()) {
            $this->model_setting_setting->editSetting('store_delivery', $this->request->post);

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

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_all_zones'] = $this->language->get('text_all_zones');
        $data['text_none'] = $this->language->get('text_none');

        $data['entry_cost'] = $this->language->get('entry_cost');
        $data['entry_tax_class'] = $this->language->get('entry_tax_class');
        $data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
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
            'href' => $this->url->link('shipping/store_delivery', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['action'] = $this->url->link('shipping/store_delivery', 'token='.$this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('extension/shipping', 'token='.$this->session->data['token'], 'SSL');

        if (isset($this->request->post['store_delivery_cost'])) {
            $data['store_delivery_cost'] = $this->request->post['store_delivery_cost'];
        } else {
            $data['store_delivery_cost'] = $this->config->get('store_delivery_cost');
        }

        if (isset($this->request->post['store_delivery_free_delivery_amount'])) {
            $data['store_delivery_free_delivery_amount'] = $this->request->post['store_delivery_free_delivery_amount'];
        } else {
            $data['store_delivery_free_delivery_amount'] = $this->config->get('store_delivery_free_delivery_amount');
        }

        if (isset($this->request->post['store_delivery_tax_class_id'])) {
            $data['store_delivery_tax_class_id'] = $this->request->post['store_delivery_tax_class_id'];
        } else {
            $data['store_delivery_tax_class_id'] = $this->config->get('store_delivery_tax_class_id');
        }

        $this->load->model('localisation/tax_class');

        $data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

        if (isset($this->request->post['store_delivery_geo_zone_id'])) {
            $data['store_delivery_geo_zone_id'] = $this->request->post['store_delivery_geo_zone_id'];
        } else {
            $data['store_delivery_geo_zone_id'] = $this->config->get('store_delivery_geo_zone_id');
        }

        $this->load->model('localisation/geo_zone');

        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        if (isset($this->request->post['store_delivery_status'])) {
            $data['store_delivery_status'] = $this->request->post['store_delivery_status'];
        } else {
            $data['store_delivery_status'] = $this->config->get('store_delivery_status');
        }

        if (isset($this->request->post['store_delivery_sort_order'])) {
            $data['store_delivery_sort_order'] = $this->request->post['store_delivery_sort_order'];
        } else {
            $data['store_delivery_sort_order'] = $this->config->get('store_delivery_sort_order');
        }

        if (isset($this->request->post['store_delivery_use_deliverysystem'])) {
            $data['store_delivery_use_deliverysystem'] = $this->request->post['store_delivery_use_deliverysystem'];
        } else {
            $data['store_delivery_use_deliverysystem'] = $this->config->get('store_delivery_use_deliverysystem');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('shipping/store_delivery.tpl', $data));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'shipping/store_delivery')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}
