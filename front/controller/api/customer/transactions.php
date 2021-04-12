<?php

class ControllerApiCustomerTransactions extends Controller {

    private $error = [];

    public function getAllOrders() {
        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $page = 1;
        $log = new Log('error.log');
        $log->write('getAllOrders');
        $this->load->model('account/order');
        $results_orders = $this->model_account_order->getOrders(($page - 1) * 10, 10, $NoLimit = true);
        $json['data'] = $results_orders;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
