<?php

class ControllerReportVendorReturns extends Controller
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
            $filter_date_start = '';
        }

        if (isset($this->request->get['filter_date_end'])) {
            $filter_date_end = $this->request->get['filter_date_end'];
        } else {
            $filter_date_end = '';
        }

        if (isset($this->request->get['filter_return_status_id'])) {
            $filter_return_status_id = $this->request->get['filter_return_status_id'];
        } else {
            $filter_return_status_id = 0;
        }

        $filter_data = [
            'filter_store_name' => $filter_store_name,
            'filter_store' => $this->getStoreIdByName($filter_store_name),
            'filter_date_start' => $filter_date_start,
            'filter_date_end' => $filter_date_end,
            'filter_return_status_id' => $filter_return_status_id,
        ];

        $this->load->model('report/excel');
        $this->model_report_excel->download_report_vendor_returns_excel($filter_data);
    }

    public function index()
    {
        $this->load->language('report/vendor_returns');

        $this->document->setTitle($this->language->get('heading_title'));

        if (isset($this->request->get['filter_store_name'])) {
            $filter_store_name = $this->request->get['filter_store_name'];
        } else {
            $filter_store_name = '';
        }

        if (isset($this->request->get['filter_date_start'])) {
            $filter_date_start = $this->request->get['filter_date_start'];
        } else {
            $filter_date_start = '';
        }

        if (isset($this->request->get['filter_date_end'])) {
            $filter_date_end = $this->request->get['filter_date_end'];
        } else {
            $filter_date_end = '';
        }

        if (isset($this->request->get['filter_return_status_id'])) {
            $filter_return_status_id = $this->request->get['filter_return_status_id'];
        } else {
            $filter_return_status_id = 0;
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
            'href' => $this->url->link('report/vendor_returns', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        $this->load->model('report/return');

        $data['returns'] = [];

        $filter_data = [
            'filter_store_name' => $filter_store_name,
            'filter_store' => $this->getStoreIdByName($filter_store_name),
            'filter_date_start' => $filter_date_start,
            'filter_date_end' => $filter_date_end,
            'filter_return_status_id' => $filter_return_status_id,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        $return_total = $this->model_report_return->getReportTotalReturns($filter_data);

        $results = $this->model_report_return->getReportReturns($filter_data);

        //echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            $data['returns'][] = [
                'return_date' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
                'order_id' => $result['order_id'],
                'return_id' => $result['return_id'],
                'return_amount' => $this->currency->format($result['price'] * $result['quantity']),
            ];
        }

        //echo "<pre>";print_r($data['returns']);die;

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

        $pagination = new Pagination();
        $pagination->total = $return_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('report/vendor_returns', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($return_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($return_total - $this->config->get('config_limit_admin'))) ? $return_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $return_total, ceil($return_total / $this->config->get('config_limit_admin')));

        $data['filter_store_name'] = $filter_store_name;
        $data['filter_date_start'] = $filter_date_start;
        $data['filter_date_end'] = $filter_date_end;
        $data['filter_return_status_id'] = $filter_return_status_id;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('report/vendor_returns.tpl', $data));
    }

    public function city_autocomplete()
    {
        $this->load->model('report/return');

        $json = $this->model_report_return->getCities($this->request->get['filter_name']);

        header('Content-type: text/json');
        echo json_encode($json);
    }
}
