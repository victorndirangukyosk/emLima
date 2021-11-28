<?php

class ControllerReportCustomerActivity extends Controller
{
    public function index()
    {
        $this->load->language('report/customer_activity');

        $this->document->setTitle($this->language->get('heading_title'));

        if (isset($this->request->get['filter_customer'])) {
            $filter_customer = $this->request->get['filter_customer'];
        } else {
            $filter_customer = null;
        }

        if (isset($this->request->get['filter_ip'])) {
            $filter_ip = $this->request->get['filter_ip'];
        } else {
            $filter_ip = null;
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

        if (isset($this->request->get['filter_company'])) {
            $filter_company = $this->request->get['filter_company'];
        } else {
            $filter_company = null;
        }

        if (isset($this->request->get['filter_key'])) {
            $filter_key = $this->request->get['filter_key'];
        } else {
            $filter_key = null;
        }
        if (isset($this->request->get['filter_order'])) {
            $filter_order = $this->request->get['filter_order'];
        } else {
            $filter_order = null;
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer='.urlencode($this->request->get['filter_customer']);
        }

        if (isset($this->request->get['filter_ip'])) {
            $url .= '&filter_ip='.$this->request->get['filter_ip'];
        }

        if (isset($this->request->get['filter_date_start'])) {
            $url .= '&filter_date_start='.$this->request->get['filter_date_start'];
        }

        if (isset($this->request->get['filter_date_end'])) {
            $url .= '&filter_date_end='.$this->request->get['filter_date_end'];
        }
        if (isset($this->request->get['filter_company'])) {
            $url .= '&filter_company='.urlencode($this->request->get['filter_company']);
        }
        if (isset($this->request->get['filter_key'])) {
            $url .= '&filter_key='.urlencode($this->request->get['filter_key']);
        }

        if (isset($this->request->get['filter_order'])) {
            $url .= '&filter_order='.urlencode($this->request->get['filter_order']);
        }


        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
            'text' => $this->language->get('text_home'),
        ];

        $data['breadcrumbs'][] = [
            'href' => $this->url->link('report/customer_activity', 'token='.$this->session->data['token'].$url, 'SSL'),
            'text' => $this->language->get('heading_title'),
        ];

        $this->load->model('report/customer');

        $data['activities'] = [];

        $filter_data = [
            'filter_customer' => $filter_customer,
            'filter_ip' => $filter_ip,
            'filter_date_start' => $filter_date_start,
            'filter_date_end' => $filter_date_end,
            'filter_company' => $filter_company,
            'filter_key' => $filter_key,
            'filter_order' => $filter_order,
            'start' => ($page - 1) * 20,
            'limit' => 20,
        ];

        $activity_total = $this->model_report_customer->getTotalCustomerActivities($filter_data);

        $results = $this->model_report_customer->getCustomerActivities($filter_data);

        foreach ($results as $result) {


            $comment = vsprintf($this->language->get('text_'.$result['key']), unserialize($result['data']));

            $find = [
                'farmer_id=',
                'customer_id=',
                'order_id=',
                'sub_customers_id='
            ];

            $replace = [
                $this->url->link('sale/farmer/edit', 'token='.$this->session->data['token'].'&farmer_id=', 'SSL'),
                $this->url->link('sale/customer/view_customer', 'token='.$this->session->data['token'].'&customer_id=', 'SSL'),
                $this->url->link('sale/order/info', 'token='.$this->session->data['token'].'&order_id=', 'SSL'),
                $this->url->link('sale/customer/view_customer', 'token='.$this->session->data['token'].'&sub_customers_id=', 'SSL'),
            ];

            $data['activities'][] = [
                'company_name' => $result['company_name'],
                'email' => $result['email'],
                'comment' => str_replace($find, $replace, $comment),
                'ip' => $result['ip'],
                'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added'])),
                'order_id' => ($result['order_id']==0?'NA':$result['order_id']),
            ];
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_comment'] = $this->language->get('column_comment');
        $data['column_ip'] = $this->language->get('column_ip');
        $data['column_date_added'] = $this->language->get('column_date_added');

        $data['entry_customer'] = $this->language->get('entry_customer');
        $data['entry_ip'] = $this->language->get('entry_ip');
        $data['entry_date_start'] = $this->language->get('entry_date_start');
        $data['entry_date_end'] = $this->language->get('entry_date_end');

        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_show_filter'] = $this->language->get('button_show_filter');
        $data['button_hide_filter'] = $this->language->get('button_hide_filter');

        $data['token'] = $this->session->data['token'];
        $data['activity_key'] = $this->model_report_customer->getActivityKeys();
        

        $url = '';

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer='.urlencode($this->request->get['filter_customer']);
        }

        if (isset($this->request->get['filter_ip'])) {
            $url .= '&filter_ip='.$this->request->get['filter_ip'];
        }

        if (isset($this->request->get['filter_date_start'])) {
            $url .= '&filter_date_start='.$this->request->get['filter_date_start'];
        }

        if (isset($this->request->get['filter_date_end'])) {
            $url .= '&filter_date_end='.$this->request->get['filter_date_end'];
        }
        if (isset($this->request->get['filter_company'])) {
            $url .= '&filter_company='.urlencode($this->request->get['filter_company']);
        }
        if (isset($this->request->get['filter_key'])) {
            $url .= '&filter_key='.urlencode($this->request->get['filter_key']);
        }
        if (isset($this->request->get['filter_order'])) {
            $url .= '&filter_order='.urlencode($this->request->get['filter_order']);
        }

        $pagination = new Pagination();
        $pagination->total = $activity_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('report/customer_activity', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($activity_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($activity_total - $this->config->get('config_limit_admin'))) ? $activity_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $activity_total, ceil($activity_total / $this->config->get('config_limit_admin')));

        $data['filter_customer'] = $filter_customer;
        $data['filter_ip'] = $filter_ip;
        $data['filter_date_start'] = $filter_date_start;
        $data['filter_date_end'] = $filter_date_end;
        $data['filter_company'] = $filter_company;
        $data['filter_key'] = $filter_key;
        $data['filter_order'] = $filter_order;


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('report/customer_activity.tpl', $data));
    }


    public function customeractivityexcel()
    {
        $this->load->language('report/customer_activity');

        $this->document->setTitle($this->language->get('heading_title'));

        if (isset($this->request->get['filter_customer'])) {
            $filter_customer = $this->request->get['filter_customer'];
        } else {
            $filter_customer = null;
        }

        if (isset($this->request->get['filter_ip'])) {
            $filter_ip = $this->request->get['filter_ip'];
        } else {
            $filter_ip = null;
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

        if (isset($this->request->get['filter_company'])) {
            $filter_company = $this->request->get['filter_company'];
        } else {
            $filter_company = null;
        }

        if (isset($this->request->get['filter_key'])) {
            $filter_key = $this->request->get['filter_key'];
        } else {
            $filter_key = null;
        }


        if (isset($this->request->get['filter_order'])) {
            $filter_order = $this->request->get['filter_order'];
        } else {
            $filter_order = null;
        }

        $filter_data = [
            'filter_customer' => $filter_customer,
            'filter_ip' => $filter_ip,
            'filter_date_start' => $filter_date_start,
            'filter_date_end' => $filter_date_end,
            'filter_company' => $filter_company,
            'filter_key' => $filter_key,
            'filter_order' => $filter_order,
        ];

        $this->load->model('report/excel');
        $this->model_report_excel->download_customer_activity_excel($filter_data);
    }

}
