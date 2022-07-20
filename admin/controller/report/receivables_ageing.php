<?php

class ControllerReportReceivablesAgeing extends Controller
{
   
    public function index()
    {
        $this->load->language('report/customer_financial_statement');

        $this->document->setTitle('Receivables Ageing');

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

        

        if (isset($this->request->get['filter_customer'])) {
            $filter_customer = $this->request->get['filter_customer'];
        } else {
            $filter_customer = '';
        }

        if (isset($this->request->get['filter_company'])) {
            $filter_company = $this->request->get['filter_company'];
        } else {
            $filter_company = '';
        }

        if (isset($this->request->get['filter_payment'])) {
            $filter_payment = $this->request->get['filter_payment'];
        } else {
            $filter_payment = null;
        }


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

        

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer='.$this->request->get['filter_customer'];
        }

        if (isset($this->request->get['filter_company'])) {
            $url .= '&filter_company='.$this->request->get['filter_company'];
        }

        if (isset($this->request->get['filter_payment'])) {
            $url .= '&filter_payment=' . urlencode(html_entity_decode($this->request->get['filter_payment'], ENT_QUOTES, 'UTF-8'));
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
            'href' => $this->url->link('report/receivables_ageing', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        $this->load->model('sale/order');

        $data['customers'] = [];

        $filter_data = [
            'filter_date_start' => $filter_date_start,
            'filter_date_end' => $filter_date_end,
            'filter_customer' => $filter_customer,
            'filter_company' => $filter_company,
            'filter_payment' => $filter_payment,

            // 'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            // 'limit' => $this->config->get('config_limit_admin'),
        ];
         
            $results = $this->model_sale_order->getReceivablesAgeing($filter_data);
            $results_customers = $this->model_sale_order->getReceivablesAgeing_customers($filter_data);
            // $customer_total=$this->model_sale_order->getTotalReceivablesSummary($filter_data);;
       
        $this->load->model('sale/order');
        $customer_total=count($results_customers);

        if (is_array($results) && $customer_total > 0) {
            $log = new Log('error.log');
            $log->write('Yes It Is Array');

            foreach ($results_customers as $res_cust) {     
                
               
                $payment_term_diff=0;
                $total=0;
                $not_due=0;
                $sum_30=0;
                $sum_60=0;
                $sum_90=0;
                $sum_180=0;
                $sum_360=0;
                $sum_360_greater=0;


                if($res_cust['payment_terms']=='Payment On Delivery')
                {
                    $payment_term_diff=0;
                }
                else if($res_cust['payment_terms']=='7 Days Credit')
                {
                    $payment_term_diff=7;

                }
                else if($res_cust['payment_terms']=='15 Days Credit')
                {
                    $payment_term_diff=15;

                }
                else if($res_cust['payment_terms']=='30 Days Credit')
                {
                    $payment_term_diff=30;

                }

            foreach ($results as $result) {              
                if($res_cust['customer']==$result['customer'])
                {
                    $total= $total+($result['order_total']-$result['partialy_paid']);
                    if($result['datediff']<=$payment_term_diff && $payment_term_diff!=0)
                    {
                        $not_due=$not_due+($result['order_total']-$result['partialy_paid']);
                    }
                    else if($result['datediff']>=0 && $result['datediff']<=30)
                    {
                        $sum_30=$sum_30+($result['order_total']-$result['partialy_paid']);
                    }
                    else if($result['datediff']>=31 && $result['datediff']<=60)
                    {
                        $sum_60=$sum_60+($result['order_total']-$result['partialy_paid']);
                    }
                    else if($result['datediff']>=61 && $result['datediff']<=90)
                    {
                        $sum_90=$sum_90+($result['order_total']-$result['partialy_paid']);
                    }
                    else if($result['datediff']>=91 && $result['datediff']<=180)
                    {
                        $sum_180=$sum_180+($result['order_total']-$result['partialy_paid']);
                    }
                    else if($result['datediff']>=181 && $result['datediff']<=360)
                    {
                        $sum_360=$sum_360+($result['order_total']-$result['partialy_paid']);
                    }
                    else if($result['datediff']>=361 )
                    {
                        $sum_360_greater=$sum_360_greater+($result['order_total']-$result['partialy_paid']);
                    }

                }
              
            }

            $data['orders'][] = [
                'company' => $res_cust['company_name'],               
                'customer' => $res_cust['customer'],
                'payment_terms' => $res_cust['payment_terms'],
                'total' => $total,
                // 'order_total' => round(($result['order_total']-$result['partialy_paid']),2),
                'not_due' => $not_due,
                'sum_30' => $sum_30,
                'sum_60' => $sum_60,
                'sum_90' => $sum_90,
                'sum_180' =>$sum_180,
                'sum_360' => $sum_360,
                'sum_360_greater' => $sum_360_greater,
               
            ];
        }
        }
        //   echo "<pre>";print_r($data['orders']);die;
        $data['heading_title'] = 'Receivables Ageing';

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

        $this->load->model('localisation/order_status');

        // $data['order_statuses'] = $this->model_localisation_order_status->getValidOrderStatuses();

        $this->load->model('sale/customer');

        $data['customer_names'] = $this->model_sale_customer->getCustomers(null);

        $url = '';

        if (isset($this->request->get['filter_date_start'])) {
            $url .= '&filter_date_start='.$this->request->get['filter_date_start'];
        }

        if (isset($this->request->get['filter_date_end'])) {
            $url .= '&filter_date_end='.$this->request->get['filter_date_end'];
        }

        

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer='.$this->request->get['filter_customer'];
        }

        if (isset($this->request->get['filter_company'])) {
            $url .= '&filter_company='.$this->request->get['filter_company'];
        }

        if (isset($this->request->get['filter_payment'])) {
            $url .= '&filter_payment=' . urlencode(html_entity_decode($this->request->get['filter_payment'], ENT_QUOTES, 'UTF-8'));
        }


        $pagination = new Pagination();
        $pagination->total = $customer_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('report/receivables_ageing', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($customer_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($customer_total - $this->config->get('config_limit_admin'))) ? $customer_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $customer_total, ceil($customer_total / $this->config->get('config_limit_admin')));

        $data['filter_date_start'] = $filter_date_start;
        $data['filter_date_end'] = $filter_date_end;
        $data['filter_customer'] = $filter_customer;
        $data['filter_company'] = $filter_company;
        $data['filter_payment'] = $filter_payment;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('report/receivables_ageing.tpl', $data));
    }

    public function receivablesageingexcel()
    {
        $this->load->language('report/customer_statement');

        $this->document->setTitle('Receivables Ageing');

        if (isset($this->request->get['filter_date_start'])) {
            $filter_date_start = $this->request->get['filter_date_start'];
        } else {
            $filter_date_start ="";//  '1990-01-01'default date removed
        }

        if (isset($this->request->get['filter_date_end'])) {
            $filter_date_end = $this->request->get['filter_date_end'];
        } else {
            $filter_date_end = date('Y-m-d');
        }

       

        if (isset($this->request->get['filter_customer'])) {
            $filter_customer = $this->request->get['filter_customer'];
        } else {
            $filter_customer = 0;
        }

        if (isset($this->request->get['filter_company'])) {
            $filter_company = $this->request->get['filter_company'];
        } else {
            $filter_company = 0;
        }

        if (isset($this->request->get['filter_payment'])) {
            $filter_payment = $this->request->get['filter_payment'];
        } else {
            $filter_payment = null;
        }

        $filter_data = [
            'filter_date_start' => $filter_date_start,
            'filter_date_end' => $filter_date_end,
            'filter_customer' => $filter_customer,
            'filter_company' => $filter_company,
            'filter_payment' => $filter_payment,

        ];

        $this->load->model('report/excel');
        // $this->model_report_excel->download_receivables_summary_excel($filter_data);
        $this->model_report_excel->download_receivables_ageing_excel($filter_data);
    }


   
}
