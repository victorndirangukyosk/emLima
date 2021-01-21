<?php

class ControllerDashboardManualOrders extends Controller
{
    public function index()
    {
        // $this->load->language('dashboard/online');

        $data['heading_title'] = "Manual Orders";//$this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];
        $enddate = date('Y-m-d', strtotime('1 days'));
        $data['manual_orders_url'] = $this->url->link('sale/order', 'token='.$this->session->data['token'].'&filter_order_type=0&filter_date_added=2021-01-01&filter_date_added_end='.$enddate, 'SSL');

        if (isset($this->request->get['filter_order_type'])) {
            $filter_order_type = $this->request->get['filter_order_type'];
        } else {
            $filter_order_type = 0;
        }
        if (isset($this->request->get['filter_date_added'])) {
            $filter_date_added = $this->request->get['filter_date_added'];
        } else {
            $filter_date_added = '2021-01-01';
        }
        if (isset($this->request->get['filter_date_added_end'])) {
            $filter_date_added_end = $this->request->get['filter_date_added_end'];
        } else {
            $filter_date_added_end = $enddate;
        }

        $filter_data = [ 
            'filter_order_type' => $filter_order_type,            
            'filter_date_added' => $filter_date_added,
            'filter_date_added_end' => $filter_date_added_end,
          
        ];

        // Total Orders
        $this->load->model('sale/order');

        //   Online orders from Jan
         
        $manual_total = $this->model_sale_order->getTotalOrders($filter_data);

        // if ($online_total > 1000000000000) {
        //     $data['total'] = round($online_total / 1000000000000, 1).'T';
        // } elseif ($online_total > 1000000000) {
        //     $data['total'] = round($online_total / 1000000000, 1).'B';
        // } elseif ($online_total > 1000000) {
        //     $data['total'] = round($online_total / 1000000, 1).'M';
        // } elseif ($online_total > 1000) {
        //     $data['total'] = round($online_total / 1000, 1).'K';
        // } else {
            $data['total'] = $manual_total;
        // }
 
        return $this->load->view('dashboard/manualorders.tpl', $data);
    }
}
