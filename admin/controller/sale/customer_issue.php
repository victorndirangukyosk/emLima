<?php

class ControllerSaleCustomerIssue extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('sale/customer_issue');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/customer_issue');

        $this->getList();
    }

      

    protected function getList()
    {
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            //  $sort = 'rating';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order='.$this->request->get['order'];
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
            'href' => $this->url->link('sale/customer_issue', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];        
        $data['customer_issues'] = [];

        $filter_data = [
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        $customer_issue_total = $this->model_sale_customer_issue->getTotalCustomerIssues($filter_data);

        $results = $this->model_sale_customer_issue->getCustomerIssues($filter_data);

        foreach ($results as $result) {
            $data['customer_issues'][] = [
                'issue_id' => $result['issue_id'],
                'issue_type' => $result['issue_type'],
                'issue_details' => $result['issue_details'],
                'customer_name' => $result['name'],
                'company_name' => $result['company_name'],
                'order_id' => $result['order_id'],
                 
            ];
        }

        // echo print_r( $data['customer_issues']);die;
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results'); 

        $data['column_order_id'] = $this->language->get('column_order_id');
        $data['column_issue_type'] = $this->language->get('column_issue_type');
        $data['column_issue_details'] = $this->language->get('column_issue_details');
        $data['column_Customer'] = $this->language->get('column_Customer');
        $data['column_Company'] = $this->language->get('column_Company');

        // $data['button_edit'] = $this->language->get('button_edit');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        
        $url = '';

        if ('ASC' == $order) {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        $data['sort_rating'] = $this->url->link('sale/customer_issue', 'token='.$this->session->data['token'].'&sort=rating'.$url, 'SSL');

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order='.$this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $customer_issue_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('sale/customer_issue', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($customer_issue_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($customer_issue_total - $this->config->get('config_limit_admin'))) ? $customer_issue_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $customer_issue_total, ceil($customer_issue_total / $this->config->get('config_limit_admin')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('sale/customer_issue_list.tpl', $data));
    }

     
}
