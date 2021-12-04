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
        $log = new Log('error.log');
        $log->write('customer_id');
        $log->write($this->customer->getId());
        $log->write('customer_id');
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

    public function getAllTransactions() {

        $this->load->language('account/edit');
        $this->load->language('account/account');

        $this->load->model('account/customer');

        //$data['orders'] = [];

        $this->load->model('account/order');
        $order_total = $this->model_account_order->getTotalOrders();
        $data['total_orders'] = $order_total;
        if (!empty($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }
        $results_orders = $this->model_account_order->getOrders(($page - 1) * 10, 10, $NoLimit = true);
        $PaymentFilter = ['mPesa On Delivery', 'Cash On Delivery', 'mPesa Online', 'Corporate Account/ Cheque Payment', 'PesaPal', 'Pezesha'];
        $statusCancelledFilter = ['Cancelled'];
        $statusSucessFilter = ['Delivered', 'Partially Delivered'];
        $statusPendingFilter = ['Cancelled', 'Delivered', 'Refunded', 'Returned', 'Partially Delivered'];
        $data['pending_transactions'] = [];
        $data['success_transactions'] = [];
        $data['cancelled_transactions'] = [];
        $totalPendingAmount = 0;
        if (count($results_orders) > 0) {
            foreach ($results_orders as $order) {
                $this->load->model('sale/order');
                $order['transcation_id'] = $this->model_sale_order->getOrderTransactionId($order['order_id']);
                if (in_array($order['payment_method'], $PaymentFilter)) {
                    if (!empty($order['transcation_id'])) {
                        $data['success_transactions'][] = $order;
                    } elseif (in_array($order['status'], $statusCancelledFilter)) {
                        $data['cancelled_transactions'][] = $order;
                    } elseif (!in_array($order['status'], $statusCancelledFilter)) {
                        $totalPendingAmount = $totalPendingAmount + $order['total'];
                        $data['pending_order_id'][] = $order['order_id'];
                        $data['pending_transactions'][] = $order;
                    }
                }
            }
        }
        $data['total_pending_amount'] = $totalPendingAmount;
        $data['pending_order_id'] = implode('--', $data['pending_order_id']);
        $json['data'] = $data;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
