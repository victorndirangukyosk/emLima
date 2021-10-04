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


    public function ReceivedOrdersAll() {

        $this->load->language('dashboard/order');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/order');
        //  echo "<pre>";print_r($this->request->get['filter_monthyear_added']);die;
        $order_total = $this->model_sale_order->getTotalOrders(['filter_order_status' => 14,  NULL]);

        $data['url'] = htmlspecialchars_decode($this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_order_status=14', 'SSL'));
        $data['total'] = $order_total;
        $data['order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_order_status=14', 'SSL');
        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($data));
        } else {
            return $this->load->view('dashboard/dashboard_order.tpl', $data);
        }
    }


    public function ReceivedOrders() {

        $this->load->language('dashboard/order');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/order');
        $this->request->get['filter_monthyear_added'] = isset($this->request->get['filter_monthyear_added']) ? $this->request->get['filter_monthyear_added'] : date('Y-m');
        $order_total = $this->model_sale_order->getTotalOrders(['filter_order_status' => 14, 'filter_monthyear_added' => $this->request->get['filter_monthyear_added']]);

        $data['url'] = htmlspecialchars_decode($this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_order_status=14&filter_monthyear_added=' . $this->request->get['filter_monthyear_added'], 'SSL'));
        $data['total'] = $order_total;
        $data['order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_order_status=14&filter_monthyear_added=' . $this->request->get['filter_monthyear_added'], 'SSL');
        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($data));
        } else {
            return $this->load->view('dashboard/dashboard_order.tpl', $data);
        }
    }

    public function ProcessedOrders() {

        $this->load->language('dashboard/order');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/order');

        $order_total = $this->model_sale_order->getTotalOrders(['filter_order_status' => 1, 'filter_monthyear_added' => $this->request->get['filter_monthyear_added']]);

        $data['url'] = htmlspecialchars_decode($this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_order_status=1&filter_monthyear_added=' . $this->request->get['filter_monthyear_added'], 'SSL'));
        $data['total'] = $order_total;
        $data['order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_order_status=1&filter_monthyear_added=' . $this->request->get['filter_monthyear_added'], 'SSL');
        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($data));
        } else {
            return $this->load->view('dashboard/dashboard_processing_order.tpl', $data);
        }
    }

    public function ProcessedOrdersAll() {

        $this->load->language('dashboard/order');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/order');

        $order_total = $this->model_sale_order->getTotalOrders(['filter_order_status' => 1,  NULL]);

        $data['url'] = htmlspecialchars_decode($this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_order_status=1', 'SSL'));
        $data['total'] = $order_total;
        $data['order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_order_status=1', 'SSL');
        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($data));
        } else {
            return $this->load->view('dashboard/dashboard_processing_order.tpl', $data);
        }
    }

    public function CancelledOrders() {

        $this->load->language('dashboard/order');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/order');

        $order_total = $this->model_sale_order->getTotalOrders(['filter_order_status' => 6, 'filter_monthyear_added' => $this->request->get['filter_monthyear_added']]);

        $data['url'] = htmlspecialchars_decode($this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_order_status=6&filter_monthyear_added=' . $this->request->get['filter_monthyear_added'], 'SSL'));
        $data['total'] = $order_total;
        $data['order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_order_status=6&filter_monthyear_added=' . $this->request->get['filter_monthyear_added'], 'SSL');
        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($data));
        } else {
            return $this->load->view('dashboard/dashboard_cancelled_order.tpl', $data);
        }
    }

    public function CancelledOrdersAll() {

        $this->load->language('dashboard/order');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/order');

        $order_total = $this->model_sale_order->getTotalOrders(['filter_order_status' => 6, NULL]);

        $data['url'] = htmlspecialchars_decode($this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_order_status=6', 'SSL'));
        $data['total'] = $order_total;
        $data['order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL');
        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($data));
        } else {
            return $this->load->view('dashboard/dashboard_cancelled_order.tpl', $data);
        }
    }

    public function IncompleteOrders() {

        $this->load->language('dashboard/order');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/order');

        $order_total = $this->model_sale_order->getTotalIncompleteOrders(['filter_order_status' => 0, 'filter_monthyear_added' => $this->request->get['filter_monthyear_added']]);

        $data['total'] = $order_total;
        $data['order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_order_status=6&filter_monthyear_added=' . $this->request->get['filter_monthyear_added'], 'SSL');
        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($data));
        } else {
            return $this->load->view('dashboard/dashboard_incomplete_order.tpl', $data);
        }
    }


    public function IncompleteOrdersAll() {

        $this->load->language('dashboard/order');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/order');

        $order_total = $this->model_sale_order->getTotalIncompleteOrders(['filter_order_status' => 0, NULL]);

        $data['total'] = $order_total;
        $data['order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_order_status=6', 'SSL');
        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($data));
        } else {
            return $this->load->view('dashboard/dashboard_incomplete_order.tpl', $data);
        }
    }

    public function ApprovalPendingOrders() {

        $this->load->language('dashboard/order');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/order');

        $order_total = $this->model_sale_order->getTotalOrders(['filter_order_status' => 15, 'filter_monthyear_added' => $this->request->get['filter_monthyear_added']]);

        $data['url'] = htmlspecialchars_decode($this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_order_status=15&filter_monthyear_added=' . $this->request->get['filter_monthyear_added'], 'SSL'));
        $data['total'] = $order_total;
        $data['order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_order_status=15&filter_monthyear_added=' . $this->request->get['filter_monthyear_added'], 'SSL');
        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($data));
        } else {
            return $this->load->view('dashboard/dashboard_approval_pending_order.tpl', $data);
        }
    }


    public function ApprovalPendingOrdersAll() {

        $this->load->language('dashboard/order');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/order');

        $order_total = $this->model_sale_order->getTotalOrders(['filter_order_status' => 15, NULL]);

        $data['url'] = htmlspecialchars_decode($this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_order_status=15', 'SSL'));
        $data['total'] = $order_total;
        $data['order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_order_status=15', 'SSL');
        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($data));
        } else {
            return $this->load->view('dashboard/dashboard_approval_pending_order.tpl', $data);
        }
    }

    public function FastOrders() {

        $this->load->language('dashboard/order');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/order');

        $order_total_today = $this->model_sale_order->getTotalOrders(['filter_order_day' => 'today', 'filter_monthyear_added' => $this->request->get['filter_monthyear_added']]);
        $log = new Log('error.log');
        /* 'filter_order_status' => 1, 14, */
        $order_total_tomorrow = $this->model_sale_order->getTotalOrders(['filter_order_day' => 'tomorrow', 'filter_monthyear_added' => $this->request->get['filter_monthyear_added']]);
        $order_total = $order_total_today + $order_total_tomorrow;

        $data['url'] = htmlspecialchars_decode($this->url->link('sale/fast_order', 'token=' . $this->session->data['token'] . '&filter_order_status=14,1&filter_order_day=today&filter_monthyear_added=' . $this->request->get['filter_monthyear_added'], 'SSL'));
        $data['total'] = $order_total;
        $data['order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_order_status=14,1&filter_order_day=today&filter_monthyear_added=' . $this->request->get['filter_monthyear_added'], 'SSL');
        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($data));
        } else {
            return $this->load->view('dashboard/fast_order.tpl', $data);
        }
    }


    public function FastOrdersAll() {

        $this->load->language('dashboard/order');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/order');

        $order_total_today = $this->model_sale_order->getTotalOrders(['filter_order_day' => 'today',NULL]);
        $log = new Log('error.log');
        /* 'filter_order_status' => 1, 14, */
        $order_total_tomorrow = $this->model_sale_order->getTotalOrders(['filter_order_day' => 'tomorrow', NULL]);
        $order_total = $order_total_today + $order_total_tomorrow;

        $data['url'] = htmlspecialchars_decode($this->url->link('sale/fast_order', 'token=' . $this->session->data['token'] . '&filter_order_status=14,1&filter_order_day=today', 'SSL'));
        $data['total'] = $order_total;
        $data['order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_order_status=14,1&filter_order_day=today', 'SSL');
        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($data));
        } else {
            return $this->load->view('dashboard/fast_order.tpl', $data);
        }
    }

    public function TotalRevenueBookedDashBoard() {

        $this->load->language('dashboard/order');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/order');

        $order_grand_total = $this->model_sale_order->TotalRevenueBookedDashBoard(['filter_order_status_id_not_in' => '0, 6, 8', 'filter_monthyear_added' => $this->request->get['filter_monthyear_added']]);
        $data['total'] = $this->currency->format($order_grand_total);
        $log = new Log('error.log');
        /* $log->write('order_grand_total');
          $log->write($order_grand_total);
          $log->write('order_grand_total'); */
        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($data));
        } else {
            return $this->load->view('dashboard/total_revenue_booked.tpl', $data);
        }
    }


    public function TotalRevenueBookedDashBoardAll() {

        $this->load->language('dashboard/order');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/order');

        // $order_grand_total = $this->model_sale_order->TotalRevenueBookedDashBoard(['filter_order_status_id_not_in' => '0, 6, 8']);
        $order_grand_total = $this->model_sale_order->getOrdersDashboard(['filter_order_status_id_not_in' => '0, 6, 8']);
        $data['total'] = $this->currency->format($order_grand_total);
        $log = new Log('error.log');
        /* $log->write('order_grand_total');
          $log->write($order_grand_total);
          $log->write('order_grand_total'); */
        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($data));
        } else {
            return $this->load->view('dashboard/total_revenue_booked.tpl', $data);
        }
    }

    public function TotalRevenueCollectedDashBoard() {

        $this->load->language('dashboard/order');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/order');

        $order_grand_total = $this->model_sale_order->TotalRevenueBookedDashBoard(['filter_order_status' => 5, 'filter_monthyear_added' => $this->request->get['filter_monthyear_added']]);
        $data['total'] = $this->currency->format($order_grand_total);
        $log = new Log('error.log');
        /* $log->write('order_grand_total');
          $log->write($order_grand_total);
          $log->write('order_grand_total'); */
        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($data));
        } else {
            return $this->load->view('dashboard/total_revenue_collected.tpl', $data);
        }
    }



    public function TotalRevenueCollectedDashBoardAll() {

        $this->load->language('dashboard/order');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/order');

        // $order_grand_total = $this->model_sale_order->TotalRevenueBookedDashBoard(['filter_order_status' => 5]);
        $order_grand_total = $this->model_sale_order->getOrdersDashboard(['filter_order_status' => '5']);
       
        $data['total'] = $this->currency->format($order_grand_total);
        $log = new Log('error.log');
        /* $log->write('order_grand_total');
          $log->write($order_grand_total);
          $log->write('order_grand_total'); */
        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($data));
        } else {
            return $this->load->view('dashboard/total_revenue_collected.tpl', $data);
        }
    }

    public function TotalRevenuePendingDashBoard() {

        $this->load->language('dashboard/order');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/order');

        $order_grand_total = $this->model_sale_order->TotalRevenueBookedDashBoard(['filter_order_status_id_not_in' => '0, 5, 6, 8', 'filter_monthyear_added' => $this->request->get['filter_monthyear_added']]);
        $data['total'] = $this->currency->format($order_grand_total);
        $log = new Log('error.log');
        /* $log->write('order_grand_total');
          $log->write($order_grand_total);
          $log->write('order_grand_total'); */
        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($data));
        } else {
            return $this->load->view('dashboard/total_revenue_pending.tpl', $data);
        }
    }


    public function TotalRevenuePendingDashBoardAll() {

        $this->load->language('dashboard/order');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/order');

        // $order_grand_total = $this->model_sale_order->TotalRevenueBookedDashBoard(['filter_order_status_id_not_in' => '0, 5, 6, 8', 'filter_monthyear_added' => $this->request->get['filter_monthyear_added']]);
        
        $order_grand_total = $this->model_sale_order->getOrdersDashboard(['filter_order_status_id_not_in' =>  '0, 5, 6, 8']);
        
        $data['total'] = $this->currency->format($order_grand_total);
        $log = new Log('error.log');
        /* $log->write('order_grand_total');
          $log->write($order_grand_total);
          $log->write('order_grand_total'); */
        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($data));
        } else {
            return $this->load->view('dashboard/total_revenue_pending.tpl', $data);
        }
    }

    public function DashboardYesterday() {
        return $this->load->view('dashboard/dashboard_yesterday.tpl', null);
    }

    public function DashboardToday() {
        return $this->load->view('dashboard/dashboard_today.tpl', null);
    }

    public function DashboardTomorrow() {
        return $this->load->view('dashboard/dashboard_tomorrow.tpl', null);
    }

    public function DeliveredOrdersByYstDate($date) {

        $this->load->language('dashboard/order');
        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_view'] = $this->language->get('text_view');
        $data['token'] = $this->session->data['token'];
        // Total Orders filter_delivery_date
        $this->load->model('sale/order');
        if ($date == null || $date == "") {
            $date = date('Y-m-d');
        }
        $yesterdayDeliveryDate = date('Y-m-d', strtotime('-1 day', strtotime($date)));
        $filter_data = [
            'filter_order_status' => '14,1,2,5,7,4,13,3',
            'filter_delivery_date' => $yesterdayDeliveryDate,
        ];

        $order_total = $this->model_sale_order->getTotalOrders($filter_data);

        // echo "<pre>";print_r($order_total);die;

        $data['url'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_order_status=14,1,2,5,7,4,13,3&filter_delivery_date=' . $yesterdayDeliveryDate, 'SSL');
        $data['total'] = $order_total;
        $data['order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_order_status=14,1,2,5,7,4,13,3&filter_delivery_date=' . $yesterdayDeliveryDate, 'SSL');

        return $this->load->view('dashboard/dashboard_order_ystdate.tpl', $data);
    }

    public function DeliveredOrdersByTodayDate($date) {

        $this->load->language('dashboard/order');
        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_view'] = $this->language->get('text_view');
        $data['token'] = $this->session->data['token'];
        // Total Orders filter_delivery_date
        $this->load->model('sale/order');
        if ($date == null || $date == "") {
            $date = date('Y-m-d');
        }
        $filter_data = [
            'filter_order_status' => '14,1,2,5,7,4,13,3',
            'filter_delivery_date' => $date,
        ];

        $order_total = $this->model_sale_order->getTotalOrders($filter_data);

        // echo "<pre>";print_r($order_total);die;

        $data['url'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_order_status=14,1,2,5,7,4,13,3&filter_delivery_date=' . $date, 'SSL');
        $data['total'] = $order_total;
        $data['order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_order_status=14,1,2,5,7,4,13,3&filter_delivery_date=' . $date, 'SSL');

        return $this->load->view('dashboard/dashboard_order_todaydate.tpl', $data);
    }

    public function DeliveredOrdersByTmrwDate($date) {

        $this->load->language('dashboard/order');
        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_view'] = $this->language->get('text_view');
        $data['token'] = $this->session->data['token'];
        // Total Orders filter_delivery_date
        $this->load->model('sale/order');
        if ($date == null || $date == "") {
            $date = date('Y-m-d');
        }
        $tmrwDeliveryDate = date('Y-m-d', strtotime('1 day', strtotime($date)));
        $filter_data = [
            'filter_order_status' => '14,1,2,5,7,4,13,3',
            'filter_delivery_date' => $tmrwDeliveryDate,
        ];

        $order_total = $this->model_sale_order->getTotalOrders($filter_data);

        // echo "<pre>";print_r($order_total);die;

        $data['url'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_order_status=14,1,2,5,7,4,13,3&filter_delivery_date=' . $tmrwDeliveryDate, 'SSL');
        $data['total'] = $order_total;
        $data['order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_order_status=14,1,2,5,7,4,13,3&filter_delivery_date=' . $tmrwDeliveryDate, 'SSL');

        return $this->load->view('dashboard/dashboard_order_tmrwdate.tpl', $data);
    }

    public function TotalRevenueBookedDashBoardByYstDate($date) {

        $this->load->language('dashboard/order');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/order');
        if ($date == null || $date == "") {
            $date = date('Y-m-d');
        }
        $yesterdayDeliveryDate = date('Y-m-d', strtotime('-1 day', strtotime($date)));
        $filter_data = [
            'filter_order_status_id_not_in' => '0, 6, 8',
            'filter_delivery_date' => $yesterdayDeliveryDate,
        ];

        $order_grand_total = $this->model_sale_order->TotalRevenueBookedDashBoard($filter_data);
        $data['total'] = $this->currency->format($order_grand_total);
        $log = new Log('error.log');
        /* $log->write('order_grand_total');
          $log->write($order_grand_total);
          $log->write('order_grand_total'); */
        return $this->load->view('dashboard/total_revenue_booked_ystdate.tpl', $data);
    }

    public function TotalRevenueBookedDashBoardByTodayDate($date) {

        $this->load->language('dashboard/order');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/order');
        if ($date == null || $date == "") {
            $date = date('Y-m-d');
        }

        $filter_data = [
            'filter_order_status_id_not_in' => '0, 6, 8',
            'filter_delivery_date' => $date,
        ];

        $order_grand_total = $this->model_sale_order->TotalRevenueBookedDashBoard($filter_data);
        $data['total'] = $this->currency->format($order_grand_total);
        $log = new Log('error.log');
        /* $log->write('order_grand_total');
          $log->write($order_grand_total);
          $log->write('order_grand_total'); */
        return $this->load->view('dashboard/total_revenue_booked_todaydate.tpl', $data);
    }

    public function TotalRevenueBookedDashBoardBytmrwDate($date) {

        $this->load->language('dashboard/order');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/order');
        if ($date == null || $date == "") {
            $date = date('Y-m-d');
        }
        $tmrwDeliveryDate = date('Y-m-d', strtotime('1 day', strtotime($date)));
        $filter_data = [
            'filter_order_status_id_not_in' => '0, 6, 8',
            'filter_delivery_date' => $tmrwDeliveryDate,
        ];

        $order_grand_total = $this->model_sale_order->TotalRevenueBookedDashBoard($filter_data);
        $data['total'] = $this->currency->format($order_grand_total);
        $log = new Log('error.log');
        /* $log->write('order_grand_total');
          $log->write($order_grand_total);
          $log->write('order_grand_total'); */
        return $this->load->view('dashboard/total_revenue_booked_tmrwdate.tpl', $data);
    }

    public function DashboardOrderDataByDate($date) {

        //   echo '<pre>';print_r($date);exit;
        // Total Orders
        $this->load->model('sale/order');
        $date = $this->request->get['date'];
        if ($date == null || $date == "") {
            $date = date('Y-m-d');
        }
        $yesterdayDeliveryDate = date('Y-m-d', strtotime('-1 day', strtotime($date)));
        $tmrwDeliveryDate = date('Y-m-d', strtotime('1 day', strtotime($date)));
        $filter_data_yst = [
            'filter_order_status_id_not_in' => '0, 6, 8',
            'filter_delivery_date' => $yesterdayDeliveryDate,
        ];
        $filter_data_today = [
            'filter_order_status_id_not_in' => '0, 6, 8',
            'filter_delivery_date' => $date,
        ];
        $filter_data_tmrw = [
            'filter_order_status_id_not_in' => '0, 6, 8',
            'filter_delivery_date' => $tmrwDeliveryDate,
        ];

        $order_grand_total_yst = $this->model_sale_order->TotalRevenueBookedDashBoard($filter_data_yst);
        $order_grand_total_today = $this->model_sale_order->TotalRevenueBookedDashBoard($filter_data_today);
        $order_grand_total_tmrw = $this->model_sale_order->TotalRevenueBookedDashBoard($filter_data_tmrw);

        $json['TotalRevenueBookedYst'] = $this->currency->format($order_grand_total_yst);
        $json['TotalRevenueBookedToday'] = $this->currency->format($order_grand_total_today);
        $json['TotalRevenueBookedTmrw'] = $this->currency->format($order_grand_total_tmrw);

        $filter_data_yst = [
            'filter_order_status' => '14,1,2,5,7,4,13,3',
            'filter_delivery_date' => $yesterdayDeliveryDate,
        ];
        $filter_data_today = [
            'filter_order_status' => '14,1,2,5,7,4,13,3',
            'filter_delivery_date' => $date,
        ];

        $filter_data_tmrw = [
            'filter_order_status' => '14,1,2,5,7,4,13,3',
            'filter_delivery_date' => $tmrwDeliveryDate,
        ];

        $order_total_yst = $this->model_sale_order->getTotalOrders($filter_data_yst);
        $order_total_today = $this->model_sale_order->getTotalOrders($filter_data_today);
        $order_total_tmrw = $this->model_sale_order->getTotalOrders($filter_data_tmrw);


        $json['DelveredOrdersYst'] = $order_total_yst;
        $json['DelveredOrdersToday'] = $order_total_today;
        $json['DelveredOrdersTmrw'] = $order_total_tmrw;
        // echo "<pre>";print_r($order_total);die;

        $json['DelveredOrdersYst_url'] = htmlspecialchars_decode($this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_order_status=14,1,2,5,7,4,13,3&filter_delivery_date=' . $yesterdayDeliveryDate, 'SSL'));
        $json['DelveredOrdersToday_url'] = htmlspecialchars_decode($this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_order_status=14,1,2,5,7,4,13,3&filter_delivery_date=' . $date, 'SSL'));
        $json['DelveredOrdersTmrw_url'] = htmlspecialchars_decode($this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_order_status=14,1,2,5,7,4,13,3&filter_delivery_date=' . $tmrwDeliveryDate, 'SSL'));


        $json['Yst'] = $yesterdayDeliveryDate;
        $json['Today'] = $date;
        $json['Tmrw'] = $tmrwDeliveryDate;

        $this->response->setOutput(json_encode($json));
    }

}
