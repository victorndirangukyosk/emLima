<?php

class ControllerSaleCustomerPezesha extends Controller {

    private $error = [];

    public function index() {

        $customer_id = $this->request->get['customer_id'];
        $this->load->model('sale/customer');
        $log = new Log('error.log');
        $log->write($customer_id);
        exit;
        $customer_device_info = $this->model_sale_customer->getCustomer($customer_id);
        $response = NULL;
        if (is_array($customer_device_info) && count($customer_device_info) > 0) {
            $response = $this->load->controller('pezesha/pezesha/userregistration', $customer_device_info['customer_id']);
        }
        return $response;
    }

}
