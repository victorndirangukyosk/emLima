<?php

class ControllerReportShopperOrder extends Controller
{
    public function index()
    {
        $this->load->language('report/shopper_order');

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('report/shopper_order', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title'] = $this->language->get('heading_title');

        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_show_filter'] = $this->language->get('button_show_filter');
        $data['button_hide_filter'] = $this->language->get('button_hide_filter');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_all_status'] = $this->language->get('text_all_status');
        $data['text_select_shopper'] = $this->language->get('text_select_shopper');

        $data['entry_date_start'] = $this->language->get('entry_date_start');
        $data['entry_date_end'] = $this->language->get('entry_date_end');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_shopper'] = $this->language->get('entry_shopper');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $data['token'] = $this->session->data['token'];

        $this->response->setOutput($this->load->view('report/shopper_order.tpl', $data));
    }

    public function getChartData($currency_format = false)
    {
        /*
         * date_start
         * date_end
         * shopper_id
         * order_assigned
         * order_rejected
         * order_fullfilled
         */

        $this->load->model('report/shopper');

        $json = [];

        if (isset($this->request->get['filter_date_start'])) {
            $start = $this->request->get['filter_date_start'];
        } else {
            $start = '';
        }

        if (!empty($this->request->get['filter_date_end'])) {
            $end = $this->request->get['filter_date_end'];
        } else {
            $end = '';
        }

        if (!empty($this->request->get['filter_shopper_id'])) {
            $shopper_id = $this->request->get['filter_shopper_id'];
        } else {
            $shopper_id = '';
        }

        $date_start = date_create($start)->format('Y-m-d H:i:s');
        $date_end = date_create($end)->format('Y-m-d H:i:s');

        $diff_str = strtotime($end) - strtotime($start);
        $diff = floor($diff_str / 3600 / 24) + 1;

        $range = $this->getRange($diff);

        switch ($range) {
            case 'hour':

                $order_assigned = [];
                $order_rejected = [];
                $order_fullfilled = [];

                for ($i = 0; $i < 24; ++$i) {
                    $order_assigned[$i] = [
                        'hour' => $i,
                        'total' => 0,
                    ];

                    $order_rejected[$i] = [
                        'hour' => $i,
                        'total' => 0,
                    ];

                    $order_fullfilled[$i] = [
                        'hour' => $i,
                        'total' => 0,
                    ];

                    $json['xaxis'][] = [$i, $i.':00'];
                }

                $assigned = $this->model_report_shopper->getAssigned($shopper_id, $date_start, $date_end, 'HOUR');
                $rejected = $this->model_report_shopper->getRejected($shopper_id, $date_start, $date_end, 'HOUR');
                $fullfilled = $this->model_report_shopper->getFullfilled($shopper_id, $date_start, $date_end, 'HOUR');

                foreach ($assigned as $result) {
                    $order_assigned[$result['hour']] = [
                        'hour' => $result['hour'],
                        'total' => $result['total'],
                    ];
                }

                foreach ($rejected as $result) {
                    $order_rejected[$result['hour']] = [
                        'hour' => $result['hour'],
                        'total' => $result['total'],
                    ];
                }
                foreach ($fullfilled as $result) {
                    $order_fullfilled[$result['hour']] = [
                        'hour' => $result['hour'],
                        'total' => $result['total'],
                    ];
                }

                foreach ($order_assigned as $key => $value) {
                    $json['order']['assigned'][] = [$key, $value['total']];
                }

                foreach ($order_rejected as $key => $value) {
                    $json['order']['rejected'][] = [$key, $value['total']];
                }

                foreach ($order_fullfilled as $key => $value) {
                    $json['order']['fullfiled'][] = [$key, $value['total']];
                }

                break;
            default:
            case 'day':

                $order_assigned = [];
                $order_rejected = [];
                $order_fullfilled = [];

                $str_date = substr($date_start, 0, 10);

                for ($i = 0; $i < $diff; ++$i) {
                    $date = date_create($str_date)->modify('+'.$i.' day')->format('Y-m-d');

                    //setting default values
                    $order_assigned[$date] = [
                        'day' => $date,
                        'total' => 0,
                    ];

                    $order_rejected[$date] = [
                        'day' => $date,
                        'total' => 0,
                    ];

                    $order_fullfilled[$date] = [
                        'day' => $date,
                        'total' => 0,
                    ];

                    $json['xaxis'][] = [$i, $date];
                }

                $assigned = $this->model_report_shopper->getAssigned($shopper_id, $date_start, $date_end, 'DAY');
                $rejected = $this->model_report_shopper->getRejected($shopper_id, $date_start, $date_end, 'DAY');
                $fullfilled = $this->model_report_shopper->getFullfilled($shopper_id, $date_start, $date_end, 'DAY');

                foreach ($assigned as $result) {
                    $total = $result['total'];

                    if ($currency_format) {
                        $total = $this->currency->format($result['total'], $this->config->get('config_currency'), '', false);
                    }

                    $order_assigned[$result['date']] = [
                        'day' => $result['date'],
                        'total' => $total,
                    ];
                }

                foreach ($rejected as $result) {
                    $total = $result['total'];

                    if ($currency_format) {
                        $total = $this->currency->format($result['total'], $this->config->get('config_currency'), '', false);
                    }

                    $order_rejected[$result['date']] = [
                        'day' => $result['date'],
                        'total' => $total,
                    ];
                }

                foreach ($fullfilled as $result) {
                    $total = $result['total'];

                    if ($currency_format) {
                        $total = $this->currency->format($result['total'], $this->config->get('config_currency'), '', false);
                    }

                    $order_fullfilled[$result['date']] = [
                        'day' => $result['date'],
                        'total' => $total,
                    ];
                }

                $i = 0;
                foreach ($order_assigned as $key => $value) {
                    $json['order']['assigned'][] = [$i++, $value['total']];
                }

                $i = 0;
                foreach ($order_rejected as $key => $value) {
                    $json['order']['rejected'][] = [$i++, $value['total']];
                }

                $i = 0;
                foreach ($order_fullfilled as $key => $value) {
                    $json['order']['fullfilled'][] = [$i++, $value['total']];
                }

                break;
            case 'month':

                $months = $this->getMonths($date_start, $date_end);

                $order_assigned = [];
                $order_rejected = [];
                $order_fullfilled = [];

                for ($i = 0; $i < count($months); ++$i) {
                    $order_assigned[$months[$i]] = [
                        'month' => $months[$i],
                        'total' => 0,
                    ];

                    $order_rejected[$months[$i]] = [
                        'month' => $months[$i],
                        'total' => 0,
                    ];

                    $order_fullfilled[$months[$i]] = [
                        'month' => $months[$i],
                        'total' => 0,
                    ];

                    $json['xaxis'][] = [$i, $months[$i]];
                }

                $assigned = $this->model_report_shopper->getAssigned($shopper_id, $date_start, $date_end, 'MONTH');
                $rejected = $this->model_report_shopper->getRejected($shopper_id, $date_start, $date_end, 'MONTH');
                $fullfilled = $this->model_report_shopper->getFullfilled($shopper_id, $date_start, $date_end, 'MONTH');

                foreach ($assigned as $result) {
                    $order_assigned[$result['month']] = [
                        'month' => $result['month'],
                        'total' => $result['total'],
                    ];
                }

                foreach ($rejected as $result) {
                    $order_rejected[$result['month']] = [
                        'month' => $result['month'],
                        'total' => $result['total'],
                    ];
                }

                foreach ($fullfilled as $result) {
                    $order_fullfilled[$result['month']] = [
                        'month' => $result['month'],
                        'total' => $result['total'],
                    ];
                }

                $i = 0;
                foreach ($order_assigned as $key => $value) {
                    $json['order']['assigned'][] = [$i++, $value['total']];
                }

                $i = 0;
                foreach ($order_rejected as $key => $value) {
                    $json['order']['rejected'][] = [$i++, $value['total']];
                }

                $i = 0;
                foreach ($order_fullfilled as $key => $value) {
                    $json['order']['fullfilled'][] = [$i++, $value['total']];
                }

                break;
            case 'year':

                $str_date = substr($date_start, 0, 10);
                $diff = floor($diff / 365) + 1;

                $order_assigned = [];
                $order_rejected = [];
                $order_fullfilled = [];

                for ($i = 0; $i < $diff; ++$i) {
                    $date = date_create($str_date)->modify('+'.$i.' year')->format('Y');

                    $order_assigned[$date] = [
                        'year' => $date,
                        'total' => 0,
                    ];

                    $order_rejected[$date] = [
                        'year' => $date,
                        'total' => 0,
                    ];

                    $order_fullfilled[$date] = [
                        'year' => $date,
                        'total' => 0,
                    ];

                    $json['xaxis'][] = [$i, $date];
                }

                $assigned = $this->model_report_shopper->getAssigned($shopper_id, $date_start, $date_end, 'YEAR');
                $rejected = $this->model_report_shopper->getRejected($shopper_id, $date_start, $date_end, 'YEAR');
                $fullfilled = $this->model_report_shopper->getFullfilled($shopper_id, $date_start, $date_end, 'YEAR');

                foreach ($assigned as $result) {
                    $order_assigned[$result['year']] = [
                        'year' => $result['year'],
                        'total' => $result['total'],
                    ];
                }

                foreach ($rejected as $result) {
                    $order_rejected[$result['year']] = [
                        'year' => $result['year'],
                        'total' => $result['total'],
                    ];
                }

                foreach ($fullfilled as $result) {
                    $order_fullfilled[$result['year']] = [
                        'year' => $result['year'],
                        'total' => $result['total'],
                    ];
                }

                $i = 0;
                foreach ($order_assigned as $key => $value) {
                    $json['order']['assigned'][] = [$i++, $value['total']];
                }

                $i = 0;
                foreach ($order_rejected as $key => $value) {
                    $json['order']['rejected'][] = [$i++, $value['total']];
                }

                $i = 0;
                foreach ($order_fullfilled as $key => $value) {
                    $json['order']['fullfilled'][] = [$i++, $value['total']];
                }

                break;
        }

        header('Content-type: text/json');
        echo json_encode($json);
    }

    public function getMonths($date1, $date2)
    {
        $time1 = strtotime($date1);
        $time2 = strtotime($date2);
        $my = date('n-Y', $time2);
        $mesi = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        //$mesi = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec');

        $months = [];
        $f = '';

        while ($time1 < $time2) {
            if (date('n-Y', $time1) != $f) {
                $f = date('n-Y', $time1);
                if (date('n-Y', $time1) != $my && ($time1 < $time2)) {
                    $str_mese = $mesi[(date('n', $time1) - 1)];
                    $months[] = $str_mese.' '.date('Y', $time1);
                }
            }
            $time1 = strtotime((date('Y-n-d', $time1).' +15days'));
        }

        $str_mese = $mesi[(date('n', $time2) - 1)];
        $months[] = $str_mese.' '.date('Y', $time2);

        return $months;
    }

    public function getRange($diff)
    {
        if (isset($this->request->get['range']) and !empty($this->request->get['range']) and 'undefined' != $this->request->get['range']) {
            $range = $this->request->get['range'];
        } else {
            $range = 'day';
        }

        if ($diff < 365 and 'year' == $range) {
            $range = 'month';
        }

        if ($diff < 28) {
            $range = 'day';
        }

        if (1 == $diff) {
            $range = 'hour';
        }

        return $range;
    }
}
