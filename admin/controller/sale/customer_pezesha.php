<?php

class ControllerSaleCustomerPezesha extends Controller {

    private $error = [];

    public function index() {

        $customer_id = $this->request->post['customer_id'];
        $this->load->model('sale/customer');
        $log = new Log('error.log');
        $log->write($customer_id);
        $customer_device_info = $this->model_sale_customer->getCustomer($customer_id);
        $response = NULL;
        if (is_array($customer_device_info) && count($customer_device_info) > 0) {
            $response = $this->load->controller('pezesha/pezesha/userregistration', $customer_device_info['customer_id']);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($response));
    }

    public function accrptterms() {

        $customer_id = $this->request->post['customer_id'];
        $this->load->model('sale/customer');
        $log = new Log('error.log');
        $log->write($customer_id);
        $customer_device_info = $this->model_sale_customer->getCustomer($customer_id);
        $response = NULL;
        if (is_array($customer_device_info) && count($customer_device_info) > 0) {
            $response = $this->load->controller('pezesha/pezesha/acceptterms', $customer_device_info['customer_id']);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($response));
    }

    public function optout() {

        $customer_id = $this->request->post['customer_id'];
        $this->load->model('sale/customer');
        $log = new Log('error.log');
        $log->write($customer_id);
        $customer_device_info = $this->model_sale_customer->getCustomer($customer_id);
        $response = NULL;
        if (is_array($customer_device_info) && count($customer_device_info) > 0) {
            $response = $this->load->controller('pezesha/pezesha/optout', $customer_device_info['customer_id']);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($response));
    }

    public function dataingestion() {

        $customer_id = $this->request->post['customer_id'];
        $this->load->model('sale/customer');
        $log = new Log('error.log');
        $log->write($customer_id);
        $customer_device_info = $this->model_sale_customer->getCustomer($customer_id);
        $response = NULL;
        if (is_array($customer_device_info) && count($customer_device_info) > 0) {
            $response = $this->load->controller('pezesha/pezesha/dataingestion', $customer_device_info['customer_id']);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($response));
    }

}
