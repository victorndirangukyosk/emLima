<?php

class ControllerReportUserActivity extends Controller
{
    public function index()
    {
        $this->load->language('report/user_activity');

        $this->document->setTitle($this->language->get('heading_title'));

        if (isset($this->request->get['filter_user'])) {
            $filter_user = $this->request->get['filter_user'];
        } else {
            $filter_user = null;
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

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['filter_user'])) {
            $url .= '&filter_user='.urlencode($this->request->get['filter_user']);
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

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
            'text' => $this->language->get('text_home'),
        ];

        $data['breadcrumbs'][] = [
            'href' => $this->url->link('report/user_activity', 'token='.$this->session->data['token'].$url, 'SSL'),
            'text' => $this->language->get('heading_title'),
        ];

        $this->load->model('report/user');

        $data['activities'] = [];

        $filter_data = [
            'filter_user' => $filter_user,
            'filter_ip' => $filter_ip,
            'filter_date_start' => $filter_date_start,
            'filter_date_end' => $filter_date_end,
            'start' => ($page - 1) * 20,
            'limit' => 20,
        ];

        $activity_total = $this->model_report_user->getTotalUserActivities($filter_data);

        $results = $this->model_report_user->getUserActivities($filter_data);

        foreach ($results as $result) {
            $comment = vsprintf($this->language->get('text_'.$result['key']), unserialize($result['data']));

            $find = [
                'user_id=',
                'order_id=',
                'account_manager_id=',
            ];

            $replace = [
                $this->url->link('user/user/edit', 'token='.$this->session->data['token'].'&user_id=', 'SSL'),
                $this->url->link('sale/order/info', 'token='.$this->session->data['token'].'&order_id=', 'SSL'),
                $this->url->link('sale/accountmanager/edit', 'token='.$this->session->data['token'].'&account_manager_id=', 'SSL'),
            ];

            $data['activities'][] = [
                'comment' => str_replace($find, $replace, $comment),
                'ip' => $result['ip'],
                'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added'])),
            ];
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_comment'] = $this->language->get('column_comment');
        $data['column_ip'] = $this->language->get('column_ip');
        $data['column_date_added'] = $this->language->get('column_date_added');

        $data['entry_user'] = $this->language->get('entry_user');
        $data['entry_ip'] = $this->language->get('entry_ip');
        $data['entry_date_start'] = $this->language->get('entry_date_start');
        $data['entry_date_end'] = $this->language->get('entry_date_end');

        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_show_filter'] = $this->language->get('button_show_filter');
        $data['button_hide_filter'] = $this->language->get('button_hide_filter');

        $data['token'] = $this->session->data['token'];

        $url = '';

        if (isset($this->request->get['filter_user'])) {
            $url .= '&filter_user='.urlencode($this->request->get['filter_user']);
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

        $pagination = new Pagination();
        $pagination->total = $activity_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('report/user_activity', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($activity_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($activity_total - $this->config->get('config_limit_admin'))) ? $activity_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $activity_total, ceil($activity_total / $this->config->get('config_limit_admin')));

        $data['filter_user'] = $filter_user;
        $data['filter_ip'] = $filter_ip;
        $data['filter_date_start'] = $filter_date_start;
        $data['filter_date_end'] = $filter_date_end;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('report/user_activity.tpl', $data));
    }
}
