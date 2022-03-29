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

    public function applyloan() {

        $customer_id = $this->request->post['customer_id'];
        $amount = isset($this->request->post['amount']) && $this->request->post['amount'] > 0 ? $this->request->post['amount'] : 241.00;
        $order_id = isset($this->request->post['order_id']) && $this->request->post['order_id'] > 0 ? $this->request->post['order_id'] : 4207;

        $customer_info['customer_id'] = $customer_id;
        $customer_info['amount'] = $amount;
        $customer_info['order_id'] = $order_id;

        $this->load->model('sale/customer');
        $log = new Log('error.log');
        $log->write($customer_id);
        $customer_device_info = $this->model_sale_customer->getCustomer($customer_id);
        $response = NULL;
        if (is_array($customer_device_info) && count($customer_device_info) > 0) {
            $response = $this->load->controller('pezesha/pezesha/applyloan', $customer_info);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($response));
    }
    
    public function applyloanfordeliveredorder() {

        $order_id = isset($this->request->post['order_id']) && $this->request->post['order_id'] > 0 ? $this->request->post['order_id'] : 0;

        if ($order_id != NULL && $order_id > 0) {
            $this->load->model('sale/customer');
            $this->load->model('sale/order');

            $order_info = $this->model_sale_order->getOrder($order_id);

            if ($order_info != NULL) {

                $customer_info['customer_id'] = $order_info['customer_id'];
                $customer_info['amount'] = $order_info['total'];
                $customer_info['order_id'] = $order_info['order_id'];

                $log = new Log('error.log');
                $log->write($order_info['customer_id']);
                $customer_device_info = $this->model_sale_customer->getCustomer($order_info['customer_id']);
                $response = NULL;
                if (is_array($customer_device_info) && count($customer_device_info) > 0) {
                    $response = $this->load->controller('pezesha/pezesha/applyloanfordeliveredorder', $customer_info);
                }

                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($response));
            }
        }
    }

}
