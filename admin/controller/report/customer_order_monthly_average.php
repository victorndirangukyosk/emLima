<?php

class ControllerReportCustomerOrderMonthlyAverage extends Controller
{


    public function index()
    {
        $this->load->language('report/customer_order_monthly_average');
        $this->document->setTitle($this->language->get('heading_title'));

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
        if (isset($this->request->get['filter_payment_terms'])) {
            $filter_payment_terms = $this->request->get['filter_payment_terms'];
        } else {
            $filter_payment_terms = 'Payment On Delivery';
        }
        // if (isset($this->request->get['filter_customer'])) {
        //     $filter_customer = $this->request->get['filter_customer'];
        // } else {
        //     $filter_customer = '';
        // }
        if (isset($this->request->get['filter_company'])) {
            $filter_company = $this->request->get['filter_company'];
        } else {
            $filter_company = '';
        }//placing pagination effecting the calculation so, adding pagination to customer list
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }
        $url = '';
        if (isset($this->request->get['filter_date_start'])) {
            $url .= '&filter_date_start='.$this->request->get['filter_date_start'];
        }
        if (isset($this->request->get['filter_date_end'])) {
            $url .= '&filter_date_end='.$this->request->get['filter_date_end'];
        }
        if (isset($this->request->get['filter_payment_terms'])) {
            $url .= '&filter_payment_terms='.$this->request->get['filter_payment_terms'];
        }
        // if (isset($this->request->get['filter_customer'])) {
        //     $url .= '&filter_customer='.$this->request->get['filter_customer'];
        // }
        if (isset($this->request->get['filter_company'])) {
            $url .= '&filter_company='.$this->request->get['filter_company'];
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
            'href' => $this->url->link('report/customer_order_monthly_average', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        $this->load->model('report/customer');

        $data['customers'] = []; 
        $filter_data = [
            'filter_date_start' => $filter_date_start,
            'filter_date_end' => $filter_date_end,
            'filter_payment_terms' => $filter_payment_terms,
            //'filter_customer' => $filter_customer,
            'filter_company' => $filter_company,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];
        //    echo "<pre>";print_r($filter_data);die;


        if ('' != $filter_date_start && '' != $filter_date_end) {
            
        $company_total = $this->model_report_customer->getTotalMonthlyAverage($filter_data);

        $customerresults = $this->model_report_customer->getCustomerMonthlyAverage($filter_data);
        //    echo "<pre>";print_r($customerresults);die;
        } else {
            $company_total = 0;
            $customerresults = null;
        }

         
        $this->load->model('sale/order');
        $monthly_average_array = [];

        if (is_array($customerresults) && count($customerresults) > 0) {
            $log = new Log('error.log');
            $log->write('Yes It Is Array');
            
            foreach ($customerresults as $result) {               
                
                    $result['monthly_average']=($result['average']/$result['months']);
                // echo "<pre>";print_r($result);die;
                array_push($monthly_average_array, $result);
            }
        }
            //    echo "<pre>";print_r($data['customers']);die;
        $data['customers'] = $monthly_average_array;
            //    echo "<pre>";print_r($data['customers']);die;

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
        $data['entry_month_start'] = $this->language->get('entry_month_start');
        $data['entry_month_end'] = $this->language->get('entry_month_end');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_customer'] = $this->language->get('entry_customer');
        // $data['button_edit'] = $this->language->get('button_edit');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_show_filter'] = $this->language->get('button_show_filter');
        $data['button_hide_filter'] = $this->language->get('button_hide_filter');

        $data['token'] = $this->session->data['token'];
        // $this->load->model('localisation/order_status');
        // $data['order_statuses'] = $this->model_localisation_order_status->getValidOrderStatuses();

        $this->load->model('sale/customer');
        // $data['customer_names'] = $this->model_sale_customer->getCustomers(null);
        $url = '';
        if (isset($this->request->get['filter_date_start'])) {
            $url .= '&filter_date_start='.$this->request->get['filter_date_start'];
        }
        if (isset($this->request->get['filter_date_end'])) {
            $url .= '&filter_date_end='.$this->request->get['filter_date_end'];
        }
        if (isset($this->request->get['filter_payment_terms'])) {
            $url .= '&filter_payment_terms='.$this->request->get['filter_payment_terms'];
        }
        // if (isset($this->request->get['filter_customer'])) {
        //     $url .= '&filter_customer='.$this->request->get['filter_customer'];
        // }
        if (isset($this->request->get['filter_company'])) {
            $url .= '&filter_company='.$this->request->get['filter_company'];
        }
        $pagination = new Pagination();
        $pagination->total = $company_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('report/customer_order_monthly_average', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');
        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($company_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($company_total - $this->config->get('config_limit_admin'))) ? $company_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $company_total, ceil($company_total / $this->config->get('config_limit_admin')));

        $data['filter_date_start'] = $filter_date_start;
        $data['filter_date_end'] = $filter_date_end;
        $data['filter_payment_terms'] = $filter_payment_terms;
        // $data['filter_customer'] = $filter_customer;
        $data['filter_company'] = $filter_company;
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('report/customer_order_monthly_average.tpl', $data));
    }

    public function getmonthname($month){

        if($month==1)
        {
           $name="January"; 

        }
        else if($month==2)
        {
           $name="February"; 

        }
        else if($month==3)
        {
           $name="March"; 

        }
        else if($month==4)
        {
           $name="April"; 

        }
        else if($month==5)
        {
           $name="May"; 

        }
        else if($month==6)
        {
           $name="June"; 

        }
        else if($month==7)
        {
           $name="July"; 

        }
        else if($month==8)
        {
           $name="August"; 

        }
        else if($month==9)
        {
           $name="September"; 

        }
        else if($month==10)
        {
           $name="October"; 

        }
        else if($month==11)
        {
           $name="November"; 

        }
        else if($month==12)
        {
           $name="December"; 

        }
        return $name;

    }
    
    public function order_monthly_average_excel()
    {
        $this->load->language('report/customer_order_monthly_average');

        $this->document->setTitle($this->language->get('heading_title'));

        if (isset($this->request->get['filter_date_start'])) {
            $filter_date_start = $this->request->get['filter_date_start'];
        }  

        if (isset($this->request->get['filter_date_end'])) {
            $filter_date_end = $this->request->get['filter_date_end'];
        } else {
            $filter_date_end = date('Y-m-d');
        }

        if (isset($this->request->get['filter_payment_terms'])) {
            $filter_payment_terms = $this->request->get['filter_payment_terms'];
        } else {
            $filter_payment_terms = 'Payment On Delivery';
        }

        // if (isset($this->request->get['filter_customer'])) {
        //     $filter_customer = $this->request->get['filter_customer'];
        // } else {
        //     $filter_customer = 0;
        // }

        if (isset($this->request->get['filter_company'])) {
            $filter_company = $this->request->get['filter_company'];
        } else {
            $filter_company = 0;
        }
        $this->load->model('report/customer');

        $filter_data = [
            'filter_date_start' => $filter_date_start,
            'filter_date_end' => $filter_date_end,
            'filter_payment_terms' => $filter_payment_terms,
            'filter_customer' => $filter_customer,
            'filter_company' => $filter_company,
        ];

        if ('' != $filter_date_start && '' != $filter_date_end) {
            // $company_total = $this->model_report_customer->getTotalMonthlyAverage($filter_data);

            $customerresults = $this->model_report_customer->getCustomerMonthlyAverage($filter_data);
            //    echo "<pre>";print_r($customerresults);die;
           
     } else {
            // $company_total = 0;
            $customerresults = null;
        }

        $this->load->model('sale/order');
        $monthly_average_array=[];
        if (is_array($customerresults) && count($customerresults) > 0) {
            foreach ($customerresults as $result) {               
                
                $result['monthly_average']=($result['average']/$result['months']);
            // echo "<pre>";print_r($result);die;
            array_push($monthly_average_array, $result);
        }
        }
        $data['customers']=$monthly_average_array;
            //    echo "<pre>";print_r($data['customers']);die;
            // echo "<pre>";print_r($data['customers']);die;

        $this->load->model('report/excel');
        $this->model_report_excel->download_customer_order_monthly_average_excel($data['customers']);
    }

    
}
