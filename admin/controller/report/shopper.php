<?php

class ControllerReportShopper extends Controller
{
    public function index()
    {
        $this->load->language('report/shopper');

        $this->document->setTitle($this->language->get('heading_title'));

        if (isset($this->request->get['filter_city'])) {
            $filter_city = $this->request->get['filter_city'];
        } else {
            $filter_city = '';
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

        if (isset($this->request->get['filter_group'])) {
            $filter_group = $this->request->get['filter_group'];
        } else {
            $filter_group = 'week';
        }

        if (isset($this->request->get['filter_vendor'])) {
            $filter_vendor = $this->request->get['filter_vendor'];
        } else {
            $filter_vendor = '';
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

        if (isset($this->request->get['filter_vendor'])) {
            $url .= '&filter_vendor='.$this->request->get['filter_vendor'];
        }

        if (isset($this->request->get['filter_group'])) {
            $url .= '&filter_group='.$this->request->get['filter_group'];
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
            'href' => $this->url->link('report/shopper', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        $this->load->model('report/shopper');

        $data['shoppers'] = [];

        $filter_data = [
            'filter_city' => $filter_city,
            'filter_date_start' => $filter_date_start,
            'filter_date_end' => $filter_date_end,
            'filter_vendor' => $filter_vendor,
            'filter_group' => $filter_group,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        $shopper_total = $this->model_report_shopper->getTotal($filter_data);

        $results = $this->model_report_shopper->getShoppers($filter_data);

        foreach ($results as $result) {
            $data['shoppers'][] = [
                'shopper' => $result['shopper'],
                'email' => $result['email'],
                'city' => $result['city'],
                'date_from' => $result['date_from'],
                'date_to' => $result['date_to'],
                'status' => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'orders' => $result['orders'],
                'commision' => $result['commision'],
                'edit' => $this->url->link('shopper/shopper/edit', 'token='.$this->session->data['token'].'&user_id='.$result['user_id'], 'SSL'),
            ];
        }

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

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_all_status'] = $this->language->get('text_all_status');

        $data['column_shopper'] = $this->language->get('column_shopper');
        $data['column_email'] = $this->language->get('column_email');
        $data['column_shopper_group'] = $this->language->get('column_shopper_group');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_orders'] = $this->language->get('column_orders');
        $data['column_products'] = $this->language->get('column_products');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_action'] = $this->language->get('column_action');
        $data['column_city'] = $this->language->get('column_city');
        $data['column_date_from'] = $this->language->get('column_date_from');
        $data['column_date_to'] = $this->language->get('column_date_to');
        $data['column_commision'] = $this->language->get('column_commision');

        $data['entry_date_start'] = $this->language->get('entry_date_start');
        $data['entry_date_end'] = $this->language->get('entry_date_end');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_group_by'] = $this->language->get('entry_group_by');
        $data['entry_shopper'] = $this->language->get('entry_shopper');
        $data['entry_city'] = $this->language->get('entry_city');

        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_show_filter'] = $this->language->get('button_show_filter');
        $data['button_hide_filter'] = $this->language->get('button_hide_filter');

        $data['token'] = $this->session->data['token'];

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

        if (isset($this->request->get['filter_vendor'])) {
            $url .= '&filter_vendor='.$this->request->get['filter_vendor'];
        }

        $pagination = new Pagination();
        $pagination->total = $shopper_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('report/shopper', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($shopper_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($shopper_total - $this->config->get('config_limit_admin'))) ? $shopper_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $shopper_total, ceil($shopper_total / $this->config->get('config_limit_admin')));

        $data['filter_date_start'] = $filter_date_start;
        $data['filter_date_end'] = $filter_date_end;
        $data['filter_vendor'] = $filter_vendor;
        $data['filter_city'] = $filter_city;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('report/shopper.tpl', $data));
    }

    public function name_autocomplete()
    {
        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = '';
        }

        $shopper_group_id = $this->config->get('config_shopper_group_ids');

        $this->load->model('sale/order');

        $json = $this->model_sale_order->getUserDetails($filter_name, $shopper_group_id);

        header('Content-type: text/json');
        echo json_encode($json);
    }

    public function city_autocomplete()
    {
        $this->load->model('sale/order');

        $json = $this->model_sale_order->getCitiesLikeWithLimit($this->request->get['filter_name'], 5);

        header('Content-type: text/json');
        echo json_encode($json);
    }
}
