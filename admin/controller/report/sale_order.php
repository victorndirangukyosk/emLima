<?php

class ControllerReportSaleOrder extends Controller {

    public function index() {
        $this->load->language('report/sale_order');

        $this->document->setTitle($this->language->get('heading_title'));

        if (isset($this->request->get['filter_city'])) {
            $filter_city = $this->request->get['filter_city'];
        } else {
            $filter_city = '';
        }

        if (isset($this->request->get['filter_customer'])) {
            $filter_customer = $this->request->get['filter_customer'];
        } else {
            $filter_customer = '';
        }

        if (isset($this->request->get['filter_date_start'])) {
            $filter_date_start = $this->request->get['filter_date_start'];
        } else {
            $filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
        }

        if (isset($this->request->get['filter_date_end'])) {
            $filter_date_end = $this->request->get['filter_date_end'];
        } else {
            $filter_date_end = date('Y-m-d');
        }

        if (isset($this->request->get['filter_group'])) {
            $filter_group = $this->request->get['filter_group'];
        } else {
            $filter_group = 'week';
        }

        if (isset($this->request->get['filter_order_status_id'])) {
            $filter_order_status_id = $this->request->get['filter_order_status_id'];
        } else {
            $filter_order_status_id = 0;
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['filter_city'])) {
            $url .= '&filter_city=' . $this->request->get['filter_city'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . $this->request->get['filter_customer'];
        }

        if (isset($this->request->get['filter_date_start'])) {
            $url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
        }

        if (isset($this->request->get['filter_date_end'])) {
            $url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
        }

        if (isset($this->request->get['filter_group'])) {
            $url .= '&filter_group=' . $this->request->get['filter_group'];
        }

        if (isset($this->request->get['filter_order_status_id'])) {
            $url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('report/sale_order', 'token=' . $this->session->data['token'] . $url, 'SSL'),
        ];

        $this->load->model('report/sale');

        $data['orders'] = [];

        $filter_data = [
            'filter_city' => $filter_city,
            'filter_customer' => $filter_customer,
            'filter_date_start' => $filter_date_start,
            'filter_date_end' => $filter_date_end,
            'filter_group' => $filter_group,
            'filter_order_status_id' => $filter_order_status_id,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        $order_total = $this->model_report_sale->getTotalOrdersbyDeliveryDate($filter_data);

        $results = $this->model_report_sale->getOrdersbyDeliveryDate($filter_data);
        // echo "<pre>" . print_r($results) . "</pre>";
        //die;

        foreach ($results as $result) {
            $final_tax=($result['tax']??0)+($result['vat_shipping']??0);
            $data['orders'][] = [
                'date_start' => date($this->language->get('date_format_short'), strtotime($result['date_start'])),
                'date_end' => date($this->language->get('date_format_short'), strtotime($result['date_end'])),
                'date_starto' => $result['date_start'],
                'date_endo' => $result['date_end'],
                'orders' => $result['orders'],
                'products' => $result['realproducts'] != NULL && $result['realproducts'] > 0 ? $result['realproducts'] : $result['products'],
                //'tax' => $this->currency->format($result['tax'], $this->config->get('config_currency')),
                //'total' => $this->currency->format($result['total'], $this->config->get('config_currency')),
                // 'tax' => $this->currency->format($result['tax'], $this->config->get('config_currency')),
                'tax' => $this->currency->format($final_tax, $this->config->get('config_currency')),
                'total' => $this->currency->format($result['totals'], $this->config->get('config_currency')),
            ];
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_all_status'] = $this->language->get('text_all_status');

        $data['column_date_start'] = $this->language->get('column_date_start');
        $data['column_date_end'] = $this->language->get('column_date_end');
        $data['column_orders'] = $this->language->get('column_orders');
        $data['column_products'] = $this->language->get('column_products');
        $data['column_tax'] = $this->language->get('column_tax');
        $data['column_total'] = $this->language->get('column_total');

        $data['entry_date_start'] = $this->language->get('entry_date_start');
        $data['entry_date_end'] = $this->language->get('entry_date_end');
        $data['entry_group'] = $this->language->get('entry_group');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_city'] = $this->language->get('entry_city');
        $data['entry_customer'] = $this->language->get('entry_customer');

        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_show_filter'] = $this->language->get('button_show_filter');
        $data['button_hide_filter'] = $this->language->get('button_hide_filter');

        $data['token'] = $this->session->data['token'];

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $data['groups'] = [];

        $data['groups'][] = [
            'text' => $this->language->get('text_year'),
            'value' => 'year',];

        $data['groups'][] = [
            'text' => $this->language->get('text_month'),
            'value' => 'month',];

        $data['groups'][] = [
            'text' => $this->language->get('text_week'),
            'value' => 'week',];

        $data['groups'][] = [
            'text' => $this->language->get('text_day'),
            'value' => 'day',];

        $url = '';

        if (isset($this->request->get['filter_city'])) {
            $url .= '&filter_city=' . $this->request->get['filter_city'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . $this->request->get['filter_customer'];
        }

        if (isset($this->request->get['filter_date_start'])) {
            $url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
        }

        if (isset($this->request->get['filter_date_end'])) {
            $url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
        }

        if (isset($this->request->get['filter_group'])) {
            $url .= '&filter_group=' . $this->request->get['filter_group'];
        }

        if (isset($this->request->get['filter_order_status_id'])) {
            $url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
        }

        $pagination = new Pagination();
        $pagination->total = $order_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('report/sale_order', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

        $data['filter_city'] = $filter_city;
        $data['filter_customer'] = $filter_customer;
        $data['filter_date_start'] = $filter_date_start;
        $data['filter_date_end'] = $filter_date_end;
        $data['filter_group'] = $filter_group;
        $data['filter_order_status_id'] = $filter_order_status_id;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('report/sale_order.tpl', $data));
    }

    public function city_autocomplete() {
        $this->load->model('sale/order');

        $json = $this->model_sale_order->getCitiesLike($this->request->get['filter_name']);

        header('Content-type: text/json');
        echo json_encode($json);
    }

    public function saleorderexcel() {
        $this->load->language('report/sale_order');

        $this->document->setTitle($this->language->get('heading_title'));

        if (isset($this->request->get['filter_city'])) {
            $filter_city = $this->request->get['filter_city'];
        } else {
            $filter_city = '';
        }

        if (isset($this->request->get['filter_customer'])) {
            $filter_customer = $this->request->get['filter_customer'];
        } else {
            $filter_customer = '';
        }

        if (isset($this->request->get['filter_date_start'])) {
            $filter_date_start = $this->request->get['filter_date_start'];
        } else {
            $filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
        }

        if (isset($this->request->get['filter_date_end'])) {
            $filter_date_end = $this->request->get['filter_date_end'];
        } else {
            $filter_date_end = date('Y-m-d');
        }

        if (isset($this->request->get['filter_group'])) {
            $filter_group = $this->request->get['filter_group'];
        } else {
            $filter_group = 'week';
        }

        if (isset($this->request->get['filter_order_status_id'])) {
            $filter_order_status_id = $this->request->get['filter_order_status_id'];
        } else {
            $filter_order_status_id = 0;
        }

        $this->load->model('report/sale');

        $data['orders'] = [];

        $filter_data = [
            'filter_city' => $filter_city,
            'filter_customer' => $filter_customer,
            'filter_date_start' => $filter_date_start,
            'filter_date_end' => $filter_date_end,
            'filter_group' => $filter_group,
            'filter_order_status_id' => $filter_order_status_id,
                // 'start' => ($page - 1) * $this->config->get('config_limit_admin'),
                // 'limit' => $this->config->get('config_limit_admin'),
        ];

        // $order_total = $this->model_report_sale->getTotalOrders($filter_data);

        $results = $this->model_report_sale->getOrdersbyDeliveryDate($filter_data);

       

        foreach ($results as $result) {

            $final_tax=($result['tax']??0)+($result['vat_shipping']??0);

            $data['orders'][] = [
                'date_start' => date($this->language->get('date_format_short'), strtotime($result['date_start'])),
                'date_end' => date($this->language->get('date_format_short'), strtotime($result['date_end'])),
                'date_starto' => $result['date_start'],
                'date_endo' => $result['date_end'],
                'orders' => $result['orders'],
                //'products' => $result['products'],
                'products' => $result['realproducts'] != NULL && $result['realproducts'] > 0 ? $result['realproducts'] : $result['products'],
                'tax' => $this->currency->format($final_tax, $this->config->get('config_currency')),
                'tax_value' => $final_tax??0,
                //'total' => $this->currency->format($result['total'], $this->config->get('config_currency')),
                'total' => $this->currency->format($result['totals'], $this->config->get('config_currency')),
                'totalvalue' => $result['totals'],
                //'totalvalue' => $result['total'],
            ];
        }

        // echo "<pre>" . print_r($data) . "</pre>";
        // die;

        $data['filter_date_start'] = $filter_date_start;
        $data['filter_date_end'] = $filter_date_end;
        $this->load->model('report/excel');
        $this->model_report_excel->download_sale_order_excel($data);
    }

}
