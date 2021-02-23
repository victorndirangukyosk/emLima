<?php

class ControllerDashboardOrder extends Controller {

    public function index() {
        $this->load->language('dashboard/order');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/order');

        $today = $this->model_sale_order->getTotalOrders(['filter_date_added' => date('Y-m-d', strtotime('-1 day'))]);

        $yesterday = $this->model_sale_order->getTotalOrders(['filter_date_added' => date('Y-m-d', strtotime('-2 day'))]);

        $difference = $today - $yesterday;

        if ($difference && $today) {
            $data['percentage'] = round(($difference / $today) * 100);
        } else {
            $data['percentage'] = 0;
        }

        $order_total = $this->model_sale_order->getTotalOrders();
        $incomplete_order_total = $this->model_sale_order->getTotalIncompleteOrders();

        // if ($order_total > 1000000000000) {
        // 	$data['total'] = round($order_total / 1000000000000, 1) . 'T';
        // } elseif ($order_total > 1000000000) {
        // 	$data['total'] = round($order_total / 1000000000, 1) . 'B';
        // } elseif ($order_total > 1000000) {
        // 	$data['total'] = round($order_total / 1000000, 1) . 'M';
        // } elseif ($order_total > 1000) {
        // 	$data['total'] = round($order_total / 1000, 1) . 'K';
        // } else {
        // 	$data['total'] = $order_total;
        // }
        $data['total'] = $order_total;
        $data['incomplete_order_total'] = $incomplete_order_total;
        $data['order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL');

        return $this->load->view('dashboard/order.tpl', $data);
    }

    public function custom_index() {
        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/order');

        //$order_total = $this->model_sale_order->getTotalOrders();
        $data['filter_date_added'] = $this->request->get['start'];
        $data['filter_date_added_end'] = $this->request->get['end'];
        $order_total = $this->model_sale_order->getTotalOrdersCustom($data);
        $data['total'] = $order_total;
        $data['order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL');
        $json['data'] = $data;
        $this->response->setOutput(json_encode($json));
    }

    public function vendor() {
        $this->load->language('dashboard/order');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/order');

        $today = $this->model_sale_order->getTotalOrders(['filter_date_added' => date('Y-m-d', strtotime('-1 day'))]);

        $yesterday = $this->model_sale_order->getTotalOrders(['filter_date_added' => date('Y-m-d', strtotime('-2 day'))]);

        $difference = $today - $yesterday;

        if ($difference && $today) {
            $data['percentage'] = round(($difference / $today) * 100);
        } else {
            $data['percentage'] = 0;
        }

        $order_total = $this->model_sale_order->getTotalOrders();

        if ($order_total > 1000000000000) {
            $data['total'] = round($order_total / 1000000000000, 1) . 'T';
        } elseif ($order_total > 1000000000) {
            $data['total'] = round($order_total / 1000000000, 1) . 'B';
        } elseif ($order_total > 1000000) {
            $data['total'] = round($order_total / 1000000, 1) . 'M';
        } elseif ($order_total > 1000) {
            $data['total'] = round($order_total / 1000, 1) . 'K';
        } else {
            $data['total'] = $order_total;
        }

        $data['order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL');

        return $this->load->view('dashboard/order.tpl', $data);
    }

    public function accountmanager() {
        $this->load->language('dashboard/order');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/order');

        $today = $this->model_sale_order->getTotalOrders(['filter_date_added' => date('Y-m-d', strtotime('-1 day'))]);

        $yesterday = $this->model_sale_order->getTotalOrders(['filter_date_added' => date('Y-m-d', strtotime('-2 day'))]);

        $difference = $today - $yesterday;

        if ($difference && $today) {
            $data['percentage'] = round(($difference / $today) * 100);
        } else {
            $data['percentage'] = 0;
        }

        $order_total = $this->model_sale_order->getTotalOrders();

        if ($order_total > 1000000000000) {
            $data['total'] = round($order_total / 1000000000000, 1) . 'T';
        } elseif ($order_total > 1000000000) {
            $data['total'] = round($order_total / 1000000000, 1) . 'B';
        } elseif ($order_total > 1000000) {
            $data['total'] = round($order_total / 1000000, 1) . 'M';
        } elseif ($order_total > 1000) {
            $data['total'] = round($order_total / 1000, 1) . 'K';
        } else {
            $data['total'] = $order_total;
        }

        $data['order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL');

        return $this->load->view('dashboard/order.tpl', $data);
    }

    public function ReceivedOrders() {

        $this->load->language('dashboard/order');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/order');

        $order_total = $this->model_sale_order->getTotalOrders(['filter_order_status' => 14]);

        $data['total'] = $order_total;
        $data['order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL');

        return $this->load->view('dashboard/dashboard_order.tpl', $data);
    }

    public function ProcessedOrders() {

        $this->load->language('dashboard/order');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/order');

        $order_total = $this->model_sale_order->getTotalOrders(['filter_order_status' => 1]);

        $data['total'] = $order_total;
        $data['order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL');

        return $this->load->view('dashboard/dashboard_processing_order.tpl', $data);
    }

    public function CancelledOrders() {

        $this->load->language('dashboard/order');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/order');

        $order_total = $this->model_sale_order->getTotalOrders(['filter_order_status' => 6]);

        $data['total'] = $order_total;
        $data['order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL');

        return $this->load->view('dashboard/dashboard_cancelled_order.tpl', $data);
    }

    public function IncompleteOrders() {

        $this->load->language('dashboard/order');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/order');

        $order_total = $this->model_sale_order->getTotalIncompleteOrders();

        $data['total'] = $order_total;
        $data['order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL');

        return $this->load->view('dashboard/dashboard_incomplete_order.tpl', $data);
    }

    public function ApprovalPendingOrders() {

        $this->load->language('dashboard/order');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/order');

        $order_total = $this->model_sale_order->getTotalOrders(['filter_order_status' => 15]);

        $data['total'] = $order_total;
        $data['order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL');

        return $this->load->view('dashboard/dashboard_approval_pending_order.tpl', $data);
    }

    public function FastOrders() {

        $this->load->language('dashboard/order');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/order');

        $order_total_today = $this->model_sale_order->getTotalOrders(['filter_order_day' => 'today']);
        $log = new Log('error.log');
        /* 'filter_order_status' => 1, 14, */
        $order_total_tomorrow = $this->model_sale_order->getTotalOrders(['filter_order_day' => 'tomorrow']);
        $order_total = $order_total_today + $order_total_tomorrow;
        $data['total'] = $order_total;
        $data['order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL');

        return $this->load->view('dashboard/fast_order.tpl', $data);
    }

    public function TotalRevenueBookedDashBoard() {

        $this->load->language('dashboard/order');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/order');

        $order_grand_total = $this->model_sale_order->TotalRevenueBookedDashBoard();
        $data['total'] = $this->currency->format($order_grand_total);
        $log = new Log('error.log');
        /* $log->write('order_grand_total');
          $log->write($order_grand_total);
          $log->write('order_grand_total'); */
        return $this->load->view('dashboard/total_revenue_booked.tpl', $data);
    }

    public function TotalRevenueCollectedDashBoard() {
        
    }

    public function TotalRevenuePendingDashBoard() {
        
    }

}
