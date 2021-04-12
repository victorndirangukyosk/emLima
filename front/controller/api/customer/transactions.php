<?php

class ControllerApiCustomerTransactions extends Controller {

    private $error = [];

    public function getAllOrders() {
        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $filters = array();
        $filters = [
            'filter_order_status_id' => '14, 1, 2, 3, 4',
        ];

        if (!empty($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }
        $log = new Log('error.log');
        $log->write('getAllOrders');
        $this->load->model('account/order');
        $results_orders = $this->model_account_order->getOrdersForTransactions(($page - 1) * 10, 10, $NoLimit = false, $filters);
        $json['data'] = $results_orders;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
