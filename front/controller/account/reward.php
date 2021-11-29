<?php

class ControllerAccountReward extends Controller
{
    public function index()
    {
        $this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_login.css');

        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/reward', '', 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $this->load->language('account/reward');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/account', '', 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_reward'),
            'href' => $this->url->link('account/reward', '', 'SSL'),
        ];

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['base'] = $server;

        $data['continue'] = $this->url->link('account/account', '', 'SSL');

        $data['home'] = $server;

        $this->load->model('account/reward');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['label_address'] = $this->language->get('label_address');
        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_order'] = $this->language->get('text_order');
        $data['text_balance'] = $this->language->get('text_balance');
        $data['text_activity'] = $this->language->get('text_activity');
        $data['text_report_issue'] = $this->language->get('text_report_issue');

        $data['text_load_more'] = $this->language->get('text_load_more');
        $data['text_no_more'] = $this->language->get('text_no_more');

        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_description'] = $this->language->get('column_description');
        $data['column_points'] = $this->language->get('column_points');
        $data['text_balance'] = $this->language->get('text_balance');
        $data['text_shopping'] = $this->language->get('text_shopping');
        $data['text_total'] = $this->language->get('text_total');
        $data['text_empty'] = $this->language->get('text_empty');

        $data['button_continue'] = $this->language->get('button_continue');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['rewards'] = [];

        $filter_data = [
            'sort' => 'date_added',
            'order' => 'DESC',
            'start' => ($page - 1) * 10,
            'limit' => 10,
        ];

        $reward_total = $this->model_account_reward->getTotalRewards();

        $results = $this->model_account_reward->getRewards($filter_data);

        foreach ($results as $result) {
            $data['rewards'][] = [
                'order_id' => $result['order_id'],
                'plain_points' => $result['points'],
                'points' => $result['points'],
                'description' => $result['description'],
                'date_added' => date($this->language->get('date_format_medium'), strtotime($result['date_added'])),
                'href' => $this->url->link('account/order/info', 'order_id='.$result['order_id'], 'SSL'),
            ];
        }

        $pagination = new Pagination();
        $pagination->total = $reward_total;
        $pagination->page = $page;
        $pagination->limit = 10;
        $pagination->url = $this->url->link('account/reward', 'page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($reward_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($reward_total - 10)) ? $reward_total : ((($page - 1) * 10) + 10), $reward_total, ceil($reward_total / 10));

        $data['total'] = (int) $this->customer->getRewardPoints();

        $data['continue'] = $this->url->link('account/account', '', 'SSL');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/information');

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/account/reward.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/account/reward.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/account/reward.tpl', $data));
        }
    }
}
