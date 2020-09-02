<?php

class Controllerreportcombinedreport extends Controller
{
    public function getStoreIdByName($name)
    {
        if ($name) {
            $query = $this->db->query('SELECT * FROM `'.DB_PREFIX."store` WHERE name LIKE '".$this->db->escape($name)."%'");

            return $query->row['store_id'];
        }
    }

    //download excel
    public function excel()
    {
        if (isset($this->request->get['filter_store_name'])) {
            $filter_store_name = $this->request->get['filter_store_name'];
        } else {
            $filter_store_name = '';
        }

        if (isset($this->request->get['filter_date_start'])) {
            $filter_date_start = $this->request->get['filter_date_start'];
        } else {
            $filter_date_start = date('Y-m-d', strtotime('-30 days'));
        }

        if (isset($this->request->get['filter_date_end'])) {
            $filter_date_end = $this->request->get['filter_date_end'];
        } else {
            $filter_date_end = date('Y-m-d');
        }

        if (isset($this->request->get['filter_date_end'])) {
            $filter_date_end = $this->request->get['filter_date_end'];
        } else {
            $filter_date_end = '';
        }

        if (isset($this->request->get['commission_per'])) {
            $commission_per = $this->request->get['commission_per'];
        } else {
            $commission_per = 10;
        }

        if (isset($this->request->get['vat_commission_per'])) {
            $vat_commission_per = $this->request->get['vat_commission_per'];
        } else {
            $vat_commission_per = 16;
        }

        $filter_data = [
            'filter_store_name' => $filter_store_name,
            'filter_store' => $this->getStoreIdByName($filter_store_name),
            'filter_date_start' => $filter_date_start,
            'filter_date_end' => $filter_date_end,
            'commission_per' => $commission_per,
            'vat_commission_per' => $vat_commission_per,
        ];

        //echo "<pre>";print_r($filter_data);die;
        $this->load->model('report/excel');
        $this->model_report_excel->download_report_combined_report_excel($filter_data);
    }

    public function index()
    {
        $this->load->language('report/combined_report');

        $this->document->setTitle($this->language->get('heading_title'));

        if (isset($this->request->get['filter_store_name'])) {
            $filter_store_name = $this->request->get['filter_store_name'];
        } else {
            $filter_store_name = '';
        }

        if (isset($this->request->get['filter_date_start'])) {
            $filter_date_start = $this->request->get['filter_date_start'];
        } else {
            $filter_date_start = date('Y-m-d', strtotime('-30 days'));
        }

        if (isset($this->request->get['filter_date_end'])) {
            $filter_date_end = $this->request->get['filter_date_end'];
        } else {
            $filter_date_end = date('Y-m-d');
        }

        if (isset($this->request->get['filter_return_status_id'])) {
            $filter_return_status_id = $this->request->get['filter_return_status_id'];
        } else {
            $filter_return_status_id = 0;
        }

        if (isset($this->request->get['commission_per'])) {
            $commission_per = $this->request->get['commission_per'];
        } else {
            $commission_per = 10;
        }

        if (isset($this->request->get['vat_commission_per'])) {
            $vat_commission_per = $this->request->get['vat_commission_per'];
        } else {
            $vat_commission_per = 16;
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['filter_city'])) {
            $url .= '&filter_city='.$this->request->get['filter_city'];
        }

        if (isset($this->request->get['filter_store_name'])) {
            $url .= '&filter_store_name='.$this->request->get['filter_store_name'];
        }

        if (isset($this->request->get['filter_date_start'])) {
            $url .= '&filter_date_start='.$this->request->get['filter_date_start'];
        }

        if (isset($this->request->get['filter_date_end'])) {
            $url .= '&filter_date_end='.$this->request->get['filter_date_end'];
        }

        if (isset($this->request->get['filter_group'])) {
            $url .= '&filter_group='.$this->request->get['filter_group'];
        }

        if (isset($this->request->get['filter_return_status_id'])) {
            $url .= '&filter_return_status_id='.$this->request->get['filter_return_status_id'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('report/combined_report', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        $this->load->model('report/return');
        $this->load->model('report/sale');
        $this->load->model('sale/order');

        $data['returns'] = [];

        $filter_data = [
            'filter_store_name' => $filter_store_name,
            'filter_store' => $this->getStoreIdByName($filter_store_name),
            'filter_date_start' => $filter_date_start,
            'filter_date_end' => $filter_date_end,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        $return_total = $this->model_report_return->getCombinedReportTotalReturns($filter_data);

        $return_results = $this->model_report_return->getCombinedReportReturns($filter_data);

        $data['returns'] = [];

        //echo "<pre>";print_r($return_results);die;
        foreach ($return_results as $result) {
            $data['returns'][] = [
                'return_date' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
                'order_id' => $result['order_id'],
                'return_id' => $result['return_id'],
                'return_amount' => $this->currency->format($result['price'] * $result['quantity']),
                'items' => $result['quantity'],
            ];
        }

        //echo "<pre>";print_r($data['returns']);die;

        $data['vendor_orders'] = [];

        //echo "<pre>";print_r($filter_data);die;
        $order_total = $this->model_report_sale->getTotalCombinedReportVendorOrders($filter_data);
        $order_results = $this->model_report_sale->getCombinedReportVendorOrders($filter_data);

        //echo "<pre>";print_r($order_results);die;
        foreach ($order_results as $result) {
            $products_qty = 0;

            if ($this->model_sale_order->hasRealOrderProducts($result['order_id'])) {
                $products_qty = $this->model_sale_order->getRealOrderProductsItems($result['order_id']);
            } else {
                $products_qty = $this->model_sale_order->getOrderProductsItems($result['order_id']);
            }

            $sub_total = 0;

            $totals = $this->model_sale_order->getOrderTotals($result['order_id']);

            //echo "<pre>";print_r($totals);die;
            foreach ($totals as $total) {
                if ('sub_total' == $total['code']) {
                    $sub_total = $total['value'];
                    break;
                }
            }

            $data['vendor_orders'][] = [
                'delivery_date' => date($this->language->get('date_format_short'), strtotime($result['delivery_date'])),
                'order_id' => $result['order_id'],
                'products' => $products_qty,
                'subtotal' => $this->currency->format($sub_total),
                'total' => $this->currency->format($result['total']),
            ];
        }

        //echo "<pre>";print_r($data['vendor_orders']);die;

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_all_status'] = $this->language->get('text_all_status');

        $data['column_date_start'] = $this->language->get('column_date_start');

        $data['column_return_date'] = $this->language->get('column_return_date');
        $data['column_order'] = $this->language->get('column_order');
        $data['column_return'] = $this->language->get('column_return');
        $data['entry_store_name'] = $this->language->get('entry_store_name');

        $data['column_return_amount'] = $this->language->get('column_return_amount');

        $data['column_date_end'] = $this->language->get('column_date_end');
        $data['column_returns'] = $this->language->get('column_returns');
        $data['column_total'] = $this->language->get('column_total');

        $data['entry_date_start'] = $this->language->get('entry_date_start');
        $data['entry_date_end'] = $this->language->get('entry_date_end');
        $data['entry_group'] = $this->language->get('entry_group');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_city'] = $this->language->get('entry_city');

        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_show_filter'] = $this->language->get('button_show_filter');
        $data['button_hide_filter'] = $this->language->get('button_hide_filter');

        $data['token'] = $this->session->data['token'];

        $this->load->model('localisation/return_status');

        $data['return_statuses'] = $this->model_localisation_return_status->getReturnStatuses();

        $data['groups'] = [];

        $data['groups'][] = [
            'text' => $this->language->get('text_year'),
            'value' => 'year',
        ];

        $data['groups'][] = [
            'text' => $this->language->get('text_month'),
            'value' => 'month',
        ];

        $data['groups'][] = [
            'text' => $this->language->get('text_week'),
            'value' => 'week',
        ];

        $data['groups'][] = [
            'text' => $this->language->get('text_day'),
            'value' => 'day',
        ];

        $url = '';

        if (isset($this->request->get['filter_city'])) {
            $url .= '&filter_city='.$this->request->get['filter_city'];
        }

        if (isset($this->request->get['filter_store_name'])) {
            $url .= '&filter_store_name='.$this->request->get['filter_store_name'];
        }

        if (isset($this->request->get['filter_date_start'])) {
            $url .= '&filter_date_start='.$this->request->get['filter_date_start'];
        }

        if (isset($this->request->get['filter_date_end'])) {
            $url .= '&filter_date_end='.$this->request->get['filter_date_end'];
        }

        if (isset($this->request->get['filter_group'])) {
            $url .= '&filter_group='.$this->request->get['filter_group'];
        }

        if (isset($this->request->get['filter_return_status_id'])) {
            $url .= '&filter_return_status_id='.$this->request->get['filter_return_status_id'];
        }

        if (isset($this->request->get['commission_per'])) {
            $url .= '&commission_per='.$this->request->get['commission_per'];
        }

        if (isset($this->request->get['vat_commission_per'])) {
            $url .= '&vat_commission_per='.$this->request->get['vat_commission_per'];
        }

        $pagination = new Pagination();
        $pagination->total = $return_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('report/combined_report', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($return_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($return_total - $this->config->get('config_limit_admin'))) ? $return_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $return_total, ceil($return_total / $this->config->get('config_limit_admin')));

        $data['filter_store_name'] = $filter_store_name;
        $data['filter_date_start'] = $filter_date_start;
        $data['filter_date_end'] = $filter_date_end;
        $data['commission_per'] = $commission_per;
        $data['vat_commission_per'] = $vat_commission_per;

        $data['filter_return_status_id'] = $filter_return_status_id;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('report/combined_report.tpl', $data));
    }

    public function city_autocomplete()
    {
        $this->load->model('report/return');

        $json = $this->model_report_return->getCities($this->request->get['filter_name']);

        header('Content-type: text/json');
        echo json_encode($json);
    }
}
