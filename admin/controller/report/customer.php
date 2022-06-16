<?php

class ControllerReportCustomer extends Controller
{
    public function index()
    {
        $this->load->language('report/customer');

        $this->document->setTitle($this->language->get('heading_title'));

        if (isset($this->request->get['filter_customer'])) {
            $filter_customer = $this->request->get['filter_customer'];
        } else {
            $filter_customer = null;
        }

         

        if (isset($this->request->get['filter_company'])) {
            $filter_company = $this->request->get['filter_company'];
        } else {
            $filter_company = null;
        }

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = null;
        }
       

        // echo  print_r($this->request->get['filter_status']);die;

        if (isset($this->request->get['filter_payment_terms'])) {
            $filter_payment_terms = $this->request->get['filter_payment_terms'];
        } else {
            $filter_payment_terms = null;
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

        
        if (isset($this->request->get['filter_company'])) {
            $url .= '&filter_company='.urlencode($this->request->get['filter_company']);
        }
        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status='.urlencode($this->request->get['filter_status']);
        }

        if (isset($this->request->get['filter_payment_terms'])) {
            $url .= '&filter_payment_terms='.urlencode($this->request->get['filter_payment_terms']);
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
            'href' => $this->url->link('report/customer', 'token='.$this->session->data['token'].$url, 'SSL'),
            'text' => $this->language->get('heading_title'),
        ];

        $this->load->model('report/customer');

        $data['customers'] = [];

        $filter_data = [
            'filter_customer' => $filter_customer,
            'filter_company' => $filter_company,
            'filter_status' => $filter_status,
            'filter_payment_terms' => $filter_payment_terms,
            'start' => ($page - 1) * 20,
            'limit' => 20,
        ];

        // echo  print_r($filter_data['filter_status']);die;


        $customer_total = $this->model_report_customer->getTotalCustomers($filter_data);

        $results = $this->model_report_customer->getCustomers($filter_data);

        foreach ($results as $result) {         

            $data['customers'][] = [
                'name' => $result['name'],
                'status' => ($result['status']==0 ?'Disabled':'Enabled'),
                'payment_terms' => $result['payment_terms'],
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
        

        $url = '';

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer='.urlencode($this->request->get['filter_customer']);
        }

        
        if (isset($this->request->get['filter_company'])) {
            $url .= '&filter_company='.urlencode($this->request->get['filter_company']);
        }
        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status='.urlencode($this->request->get['filter_status']);
        }
        if (isset($this->request->get['filter_payment_terms'])) {
            $url .= '&filter_payment_terms='.urlencode($this->request->get['filter_payment_terms']);
        }

        $pagination = new Pagination();
        $pagination->total = $customer_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('report/customer', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($customer_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($customer_total - $this->config->get('config_limit_admin'))) ? $customer_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $customer_total, ceil($customer_total / $this->config->get('config_limit_admin')));

        $data['filter_customer'] = $filter_customer;      
        $data['filter_company'] = $filter_company;
        $data['filter_status'] = $filter_status;
        $data['filter_payment_terms'] = $filter_payment_terms;


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('report/customer.tpl', $data));
    }


    public function customerexcel()
    {
        $this->load->language('report/customer');

        $this->document->setTitle($this->language->get('heading_title'));

        if (isset($this->request->get['filter_customer'])) {
            $filter_customer = $this->request->get['filter_customer'];
        } else {
            $filter_customer = null;
        }

         

        if (isset($this->request->get['filter_company'])) {
            $filter_company = $this->request->get['filter_company'];
        } else {
            $filter_company = null;
        }

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = null;
        }
       

        // echo  print_r($this->request->get['filter_status']);die;

        if (isset($this->request->get['filter_payment_terms'])) {
            $filter_payment_terms = $this->request->get['filter_payment_terms'];
        } else {
            $filter_payment_terms = null;
        }

        $filter_data = [
            'filter_customer' => $filter_customer,
            'filter_company' => $filter_company,
            'filter_status' => $filter_status,
            'filter_payment_terms' => $filter_payment_terms,
            
        ];

        $this->load->model('report/excel');
        $this->model_report_excel->download_customer_master_excel($filter_data);
    }


   

}
