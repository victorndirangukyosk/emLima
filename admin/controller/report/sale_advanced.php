<?php

class ControllerReportSaleAdvanced extends Controller
{
    public function excel()
    {
        //echo "<pre>";print_r($this->request->get);die;
        if (isset($this->request->get['filter_order_status_id'])) {
            $filter_order_status_id = $this->request->get['filter_order_status_id'];
        } else {
            $filter_order_status_id = 0;
        }

        if (isset($this->request->get['filter_store'])) {
            $filter_store = $this->request->get['filter_store'];
        } else {
            $filter_store = '';
        }

        if (isset($this->request->get['filter_store_id'])) {
            $filter_store_id = $this->request->get['filter_store_id'];
        } else {
            $filter_store_id = '';
        }

        if (isset($this->request->get['filter_date_start'])) {
            $filter_date_start = $this->request->get['filter_date_start'];
        } else {
            $filter_date_start = date('Y-m-d', strtotime(date('Y').'-'.date('m').'-01'));
        }

        if (isset($this->request->get['filter_date_end'])) {
            $filter_date_end = $this->request->get['filter_date_end'];
        } else {
            $filter_date_end = date('Y-m-d');
        }

        $data['filter_store'] = $filter_store_id;
        $data['filter_store_name'] = $filter_store;

        $data['filter_store_id'] = $filter_store_id;
        $data['filter_date_start'] = $filter_date_start;
        $data['filter_date_end'] = $filter_date_end;
        $data['filter_order_status_id'] = $filter_order_status_id;

        //echo "<pre>";print_r($data);die;

        $this->load->model('report/excel');
        $this->model_report_excel->download_saleorderadvanced($data);
    }

    public function index()
    {
        //echo "<pre>";print_r(date('d-M-y', strtotime('2018-12-26')));die;
        $this->load->language('report/sale_advanced');

        $this->document->setTitle($this->language->get('heading_title'));

        if (isset($this->request->get['filter_city'])) {
            $filter_city = $this->request->get['filter_city'];
        } else {
            $filter_city = '';
        }

        if (isset($this->request->get['filter_store'])) {
            $filter_store = $this->request->get['filter_store'];
        } else {
            $filter_store = '';
        }

        if (isset($this->request->get['filter_store_id'])) {
            $filter_store_id = $this->request->get['filter_store_id'];
        } else {
            $filter_store_id = '';
        }

        if (isset($this->request->get['filter_date_start'])) {
            $filter_date_start = $this->request->get['filter_date_start'];
        } else {
            $filter_date_start = date('Y-m-d', strtotime(date('Y').'-'.date('m').'-01'));
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
            $url .= '&filter_city='.$this->request->get['filter_city'];
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

        if (isset($this->request->get['filter_order_status_id'])) {
            $url .= '&filter_order_status_id='.$this->request->get['filter_order_status_id'];
        }

        if (isset($this->request->get['filter_store'])) {
            $url .= '&filter_store='.$this->request->get['filter_store'];
        }

        if (isset($this->request->get['filter_store_id'])) {
            $url .= '&filter_store_id='.$this->request->get['filter_store_id'];
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
            'href' => $this->url->link('report/sale_advanced', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        $this->load->model('report/sale');
        $this->load->model('sale/order');
        $this->load->model('account/order');

        $data['orders'] = [];

        $filter_data = [
            'filter_date_start' => $filter_date_start,
            'filter_date_end' => $filter_date_end,
            'filter_order_status_id' => $filter_order_status_id,
            'filter_store' => $filter_store_id,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        //echo "<pre>";print_r($filter_data);die;
        $order_total = $this->model_report_sale->getTotalAdvancedOrders($filter_data);

        $results = $this->model_report_sale->getAdvancedOrders($filter_data);

        //echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            $sub_total = 0;
            $total = 0;
            $delivery_charge = 0;
            $wallet_used = 0;
            $coupon_used = 0;
            $reward_points_used = 0;

            $totals = $this->model_sale_order->getOrderTotals($result['order_id']);

            $walletCredited = $this->model_sale_order->getTotalCreditsByOrderId($result['order_id']);

            //echo "<pre>";print_r($walletCredited);die;

            $wallet_used = 0;

            //echo "<pre>";print_r($totals);die;
            foreach ($totals as $tmptotal) {
                if ('sub_total' == $tmptotal['code']) {
                    $sub_total = $tmptotal['value'];
                }

                if ('total' == $tmptotal['code']) {
                    $total = $tmptotal['value'];
                }

                if ('shipping' == $tmptotal['code']) {
                    $delivery_charge = $tmptotal['value'];
                }

                if ('credit' == $tmptotal['code']) {
                    $wallet_used = $tmptotal['value'];
                }

                if ('coupon' == $tmptotal['code']) {
                    $coupon_used = $tmptotal['value'];
                }

                if ('reward' == $tmptotal['code']) {
                    $reward_points_used = $tmptotal['value'];
                }
            }

            $product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);

            $real_product_total = $this->model_account_order->getTotalRealOrderProductsByOrderId($result['order_id']);

            if ($real_product_total) {
                $product_total = $real_product_total;
            }

            $data['order_transaction_id'] = '';

            $order_transaction_data = $this->model_sale_order->getOrderTransactionId($result['order_id']);

            if (count($order_transaction_data) > 0) {
                $data['order_transaction_id'] = trim($order_transaction_data['transaction_id']);
            }

            $data['orders'][] = [
                'delivery_date' => date($this->language->get('date_format_short'), strtotime($result['delivery_date'])),
                'order_id' => $result['order_id'],

                'order_transaction_id' => $data['order_transaction_id'],
                'payment_method' => $result['payment_method'],
                'walletCredited' => $this->currency->format($walletCredited, $this->config->get('config_currency')),

                'no_of_items' => $product_total,
                'subtotal' => $this->currency->format($sub_total, $this->config->get('config_currency')),
                'wallet_used' => $this->currency->format($wallet_used, $this->config->get('config_currency')),
                'coupon_used' => $this->currency->format($coupon_used, $this->config->get('config_currency')),
                'reward_points_used' => $this->currency->format($reward_points_used, $this->config->get('config_currency')),
                'delivery_charge' => $this->currency->format($delivery_charge, $this->config->get('config_currency')),
                'total' => $this->currency->format($total, $this->config->get('config_currency')),
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

        $data['entry_store'] = $this->language->get('entry_store');
        $data['column_delivery_date'] = $this->language->get('column_delivery_date');
        $data['column_order_no'] = $this->language->get('column_order_no');
        $data['column_no_of_items'] = $this->language->get('column_no_of_items');
        $data['column_subtotal'] = $this->language->get('column_subtotal');
        $data['column_wallet_used'] = $this->language->get('column_wallet_used');
        $data['column_coupon'] = $this->language->get('column_coupon');

        $data['column_reward_points_claimed'] = $this->language->get('column_reward_points_claimed');
        $data['column_delivery_charges'] = $this->language->get('column_delivery_charges');

        $data['column_walletcredited'] = $this->language->get('column_walletcredited');
        $data['column_paymentmethod'] = $this->language->get('column_paymentmethod');
        $data['column_transaction_ID'] = $this->language->get('column_transaction_ID');

        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_show_filter'] = $this->language->get('button_show_filter');
        $data['button_hide_filter'] = $this->language->get('button_hide_filter');

        $data['token'] = $this->session->data['token'];

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

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

        if (isset($this->request->get['filter_store'])) {
            $url .= '&filter_store='.$this->request->get['filter_store'];
        }

        if (isset($this->request->get['filter_store_id'])) {
            $url .= '&filter_store_id='.$this->request->get['filter_store_id'];
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

        if (isset($this->request->get['filter_order_status_id'])) {
            $url .= '&filter_order_status_id='.$this->request->get['filter_order_status_id'];
        }

        $pagination = new Pagination();
        $pagination->total = $order_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('report/sale_advanced', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

        $data['filter_city'] = $filter_city;
        $data['filter_store'] = $filter_store;
        $data['filter_store_id'] = $filter_store_id;
        $data['filter_date_start'] = $filter_date_start;
        $data['filter_date_end'] = $filter_date_end;
        $data['filter_group'] = $filter_group;
        $data['filter_order_status_id'] = $filter_order_status_id;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('report/sale_advanced.tpl', $data));
    }

    public function city_autocomplete()
    {
        $this->load->model('sale/order');

        $json = $this->model_sale_advanced->getCitiesLike($this->request->get['filter_name']);

        header('Content-type: text/json');
        echo json_encode($json);
    }
}
