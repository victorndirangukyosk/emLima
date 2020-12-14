<?php

class ControllerAccountOcr extends Controller
{
    public function index() {
        $this->document->setTitle('Purchase Order Document');

        $this->load->model('account/address');


        $data['header'] = $this->load->controller('common/header/onlyheader');
        $data['customer_id'] = $_SESSION['customer_id'];
        $data['customer_category'] = $_SESSION['customer_category'];
        $data['addresses'] = array_column($this->model_account_address->getAddresses(),'name','address_id');
        // echo "<pre>";print_r($data);die;

        $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/account/ocr.tpl', $data));
    }
}