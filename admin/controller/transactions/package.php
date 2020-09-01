<?php

class ControllerTransactionsPackage extends Controller
{
    private $error = [];

    public function index()
    {
        $this->language->load('transactions/package');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('transactions/package');

        $this->getList();
    }

    public function getList()
    {
        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
                    'text' => $this->language->get('text_home'),
                    'href' => $this->url->link('common/home', 'token='.$this->session->data['token'], 'SSL'),
                    'separator' => false,
            ];

        $data['breadcrumbs'][] = [
                    'text' => $this->language->get('heading_title'),
                    'href' => $this->url->link('transactions/package', 'token='.$this->session->data['token'], 'SSL'),
                    'separator' => ' :: ',
            ];

        $url = '';

        if (isset($this->request->get['filter_package'])) {
            $filter_package = $this->request->get['filter_package'];
            $url .= 'filter_package='.$filter_package;
        } else {
            $filter_package = '';
        }

        if (isset($this->request->get['filter_vendor'])) {
            $filter_vendor = $this->request->get['filter_vendor'];
            $url .= 'filter_vendor='.$filter_vendor;
        } else {
            $filter_vendor = '';
        }

        if (isset($this->request->get['filter_transaction_no'])) {
            $filter_transaction_no = $this->request->get['filter_transaction_no'];
            $url .= 'filter_transaction_no='.$filter_transaction_no;
        } else {
            $filter_transaction_no = '';
        }

        if (isset($this->request->get['filter_amount'])) {
            $filter_amount = $this->request->get['filter_amount'];
            $url .= 'filter_amount='.$filter_amount;
        } else {
            $filter_amount = '';
        }

        if (isset($this->request->get['filter_date_added'])) {
            $filter_date_added = $this->request->get['filter_date_added'];
            $url .= 'filter_added='.$filter_date_added;
        } else {
            $filter_date_added = '';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $filter_data = [
                    'filter_package' => $filter_package,
                    'filter_vendor' => $filter_vendor,
                    'filter_transaction_no' => $filter_transaction_no,
                    'filter_amount' => $filter_amount,
                    'filter_date_added' => $filter_date_added,
                    'start' => ($page - 1) * $this->config->get('config_admin_limit'),
                    'limit' => $this->config->get('config_admin_limit'),
            ];

        $total = $this->model_transactions_package->getTotal($filter_data);

        $data['rows'] = $this->model_transactions_package->getList($filter_data);

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['button_hide_filter'] = $this->language->get('button_hide_filter');
        $data['button_show_filter'] = $this->language->get('button_show_filter');
        $data['button_filter'] = $this->language->get('button_filter');

        $data['heading_title'] = $this->language->get('heading_title');
        $data['token'] = $this->session->data['token'];

        $data['entry_package'] = $this->language->get('entry_package');
        $data['entry_vendor'] = $this->language->get('entry_vendor');
        $data['entry_transaction_no'] = $this->language->get('entry_transaction_no');
        $data['entry_amount'] = $this->language->get('entry_amount');

        $data['column_package'] = $this->language->get('column_package');
        $data['column_vendor'] = $this->language->get('column_vendor');
        $data['column_transaction_no'] = $this->language->get('column_transaction_no');
        $data['column_amount'] = $this->language->get('column_amount');
        $data['column_date_added'] = $this->language->get('column_date_added');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['error'])) {
            $data['error_warning'] = $this->session->data['error'];
            unset($this->session->data['error']);
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $pagination = new Pagination();
        $pagination->total = $total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_admin_limit');
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('transactions/package', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total - $this->config->get('config_limit_admin'))) ? $total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total, ceil($total / $this->config->get('config_limit_admin')));

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('transactions/package.tpl', $data));
    }
}
