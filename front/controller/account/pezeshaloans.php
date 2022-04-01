<?php

class ControllerAccountPezeshaloans extends Controller {

    public function index() {
        $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_login.css');
        $this->document->addScript('https://www.js-tutorials.com/demos/jquery_bootstrap_pagination_example_demo/jquery.twbsPagination.min.js');

        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/pezeshaloans', '', 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $data['kondutoStatus'] = $this->config->get('config_konduto_status');

        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        $this->load->language('account/pezesha');

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
            'text' => $this->language->get('text_pezesha'),
            'href' => $this->url->link('account/pezeshaloans', '', 'SSL'),
        ];

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
        $data['column_amount'] = sprintf($this->language->get('column_amount'), $this->config->get('config_currency'));

        $data['text_total'] = $this->language->get('text_total');
        $data['text_empty'] = $this->language->get('text_empty');

        $data['button_continue'] = $this->language->get('button_continue');

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['base'] = $server;
        $data['total'] = $this->currency->format($this->customer->getBalance());
        $data['text_signout'] = $this->language->get('text_signout');
        $data['text_shopping'] = $this->language->get('text_shopping');

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }
        $data['home'] = $server;
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/information');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/pezeshaloans.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/pezeshaloans.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/account/pezeshaloans.tpl', $data));
        }
    }

    public function getPezeshaLoans() {
        $data['orders'] = NULL;
        $this->load->model('account/order');
        $pezesha_loans = $this->model_account_order->getPezeshaloans();
        foreach ($pezesha_loans as $pezesha_loan) {
            //$pezesha_loan['total'] = $this->currency->format($pezesha_loan['total']);
            $pezesha_loan['total_formatted'] = $this->currency->format($pezesha_loan['total']);
            //$pezesha_loan['amount'] = $this->currency->format($pezesha_loan['amount']);
            $pezesha_loan['amount_formatted'] = $this->currency->format($pezesha_loan['amount']);
            $data['orders'][] = $pezesha_loan;
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

}
