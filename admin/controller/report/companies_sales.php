<?php

class ControllerReportCompaniesSales extends Controller {

    public function index() {
        $this->load->language('report/customer_boughtproducts');

        $this->document->setTitle('Sales from Companies');

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
        
        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = '';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['filter_date_start'])) {
            $url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
        }

        if (isset($this->request->get['filter_date_end'])) {
            $url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
        }
       

        $variations = array();
        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . $this->request->get['filter_name'];
            // $variations = $this->getProductVariantsInfo($this->request->get['filter_name']);
            //$log = new Log('error.log');
            //$log->write($variations);
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('report/companies_sales', 'token=' . $this->session->data['token'] . $url, 'SSL'),
        ];

        $this->load->model('report/sale');

        $data['products'] = [];

        $filter_data = [
            'filter_date_start' => $filter_date_start,
            'filter_date_end' => $filter_date_end,           
            'filter_name' => $filter_name,
            // 'filter_variations' => $variations
                // 'start' => ($page - 1) * $this->config->get('config_limit_admin'),
                // 'limit' => $this->config->get('config_limit_admin'),
        ];
            if (  '' != $filter_name) {

            $results = $this->model_report_sale->getSalesByCompanies($filter_data);
            $products_total = count($results);
        } else {
            $products_total = 0;
            $results = null;
        }
        $this->load->model('sale/order');
        if (is_array($results) && count($results) > 0) {
            $log = new Log('error.log');
            $log->write('Yes It Is Array');
            foreach ($results as $result) {
                $data['products'][] = [
                    // 'company' => $result['company'],
                    'order_date' => $result['date_added'],
                    'order_id' => $result['order_id'],
                    'customer' => $result['customer'],
                    'company' => $result['company'],
                    'customer_status' => $result['customer_status']==0?'Disabled':'Enabled',
                    // 'name' => $result['name'],
                    // 'unit' => $result['unit'],
                    // 'quantity' => $result['quantity'],
                    'status' => $result['status'],
                    'payment_terms' => $result['payment_terms'],
                ];
            }
        }
        //  echo "<pre>";print_r($data['customers']);die;
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_all_status'] = $this->language->get('text_all_status');

        $data['column_customer'] = $this->language->get('column_customer');
        $data['column_email'] = $this->language->get('column_email');
        $data['column_customer_group'] = $this->language->get('column_customer_group');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_orders'] = $this->language->get('column_orders');
        $data['column_products'] = $this->language->get('column_products');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_action'] = $this->language->get('column_action');

        $data['entry_date_start'] = $this->language->get('entry_date_start');
        $data['entry_date_end'] = $this->language->get('entry_date_end');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_customer'] = $this->language->get('entry_customer');

        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_show_filter'] = $this->language->get('button_show_filter');
        $data['button_hide_filter'] = $this->language->get('button_hide_filter');

        $data['token'] = $this->session->data['token'];

        $this->load->model('sale/customer');

        // $data['customer_names'] = $this->model_sale_customer->getCustomers(null);

        $url = '';

        if (isset($this->request->get['filter_date_start'])) {
            $url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
        }

        // if (isset($this->request->get['filter_date_end'])) {
        //     $url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
        // }


 
        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . $this->request->get['filter_name'];
        }

        $pagination = new Pagination();
        $pagination->total = $products_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('report/companies_sales', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($products_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($products_total - $this->config->get('config_limit_admin'))) ? $products_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $products_total, ceil($products_total / $this->config->get('config_limit_admin')));

        $data['filter_date_start'] = $filter_date_start;
        // $data['filter_date_end'] = $filter_date_end;
      
        $data['filter_name'] = $filter_name;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');


        //as the dynamic pagination will not work for this calculation , applied pagination on array
        $start = ($page - 1) * $this->config->get('config_limit_admin');
        $limit = $this->config->get('config_limit_admin');

        $data['products'] = array_slice($data['products'], $start, $limit);

        $this->response->setOutput($this->load->view('report/companies_sales.tpl', $data));
    }

    public function excel() {
        $this->load->language('report/customer_boughtproducts');

        $this->document->setTitle('Sales By Companies');

        if (isset($this->request->get['filter_date_start'])) {
            $filter_date_start = $this->request->get['filter_date_start'];
        } else {
            $filter_date_start = ""; //  '1990-01-01'default date removed
        }

        // if (isset($this->request->get['filter_date_end'])) {
        //     $filter_date_end = $this->request->get['filter_date_end'];
        // } else {
        //     $filter_date_end = date('Y-m-d');
        // }



        

        $variations = array();
        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
            // $variations = $this->getProductVariantsInfo($this->request->get['filter_name']);
            //$log = new Log('error.log');
            //$log->write($variations);
        } else {
            $filter_name = 0;
        }

        $filter_data = [
            'filter_date_start' => $filter_date_start,
            // 'filter_date_end' => $filter_date_end,
            'filter_name' => $filter_name,
            // 'filter_variations' => $variations
        ];

        $this->load->model('report/excel');  
        $this->model_report_excel->download_companies_sales_excel($filter_data);
    }

     

}
