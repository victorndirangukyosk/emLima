<?php

class ControllerApiCustomerTransactions extends Controller {

    private $error = [];

    public function getAllOrders() {
        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        if (!empty($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }
        $log = new Log('error.log');
        $log->write('getAllOrders');
        $this->load->model('account/order');
        $results_orders = $this->model_account_order->getOrders(($page - 1) * 10, 10, $NoLimit = false);
        $json['data'] = $results_orders;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
