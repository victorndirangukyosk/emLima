<?php

class ControllerCommonStoreInfo extends Controller
{
    public function index()
    {
        $this->load->language('product/store');
        $this->load->model('tool/image');

        $data['button_change_store'] = $this->language->get('button_change_store');
        $data['text_deliver'] = $this->language->get('text_deliver');

        $data['store'] = $this->model_tool_image->getStore($this->session->data['config_store_id']);

        $data['thumb'] = 'image/'.$data['store']['logo'];

        $data['zipcodes'] = ''; //52153435643654';

        $zipcodes = $this->model_tool_image->getStoreZipCodes($this->session->data['config_store_id']);

        foreach ($zipcodes as $zipcode) {
            $data['zipcodes'] .= $zipcode['zipcode'].', ';
        }

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/common/store_info.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/common/store_info.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/common/store_info.tpl', $data));
        }
    }
}
