<?php

class ControllerReportPaymentReceivables extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('sale/order_receivables');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/order_receivables');

        $this->getList();
    }

    protected function getList()
    {
        if (isset($this->request->get['filter_city'])) {
            $filter_city = $this->request->get['filter_city'];
        } else {
            $filter_city = null;
        }

        if (isset($this->request->get['filter_order_id'])) {
            $filter_order_id = $this->request->get['filter_order_id'];
        } else {
            $filter_order_id = null;
        }

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


        if (isset($this->request->get['filter_payment_terms'])) {
            $filter_payment_terms = $this->request->get['filter_payment_terms'];
        } else {
            $filter_payment_terms = null;
        }

      

        if (isset($this->request->get['filter_date_added'])) {
            $filter_date_added = $this->request->get['filter_date_added'];
        } else {
            $filter_date_added = null;
        }

        if (isset($this->request->get['filter_date_added_end'])) {
            $filter_date_added_end = $this->request->get['filter_date_added_end'];
        } else {
            $filter_date_added_end = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'o.order_id';
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
        


        if (isset($this->request->get['page_success'])) {
            $page_success = $this->request->get['page_success'];
        } else {
            $page_success = 1;
        }
        $url = '';

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id='.$this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer='.urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_company'])) {
            $url .= '&filter_company='.urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status='.$this->request->get['filter_order_status'];
        }

        

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added='.$this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_added_end'])) {
            $url .= '&filter_date_added_end=' . $this->request->get['filter_date_added_end'];
        }


        if (isset($this->request->get['filter_payment_terms'])) {
            $url .= '&filter_payment_terms=' . $this->request->get['filter_payment_terms'];
        }


        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order='.$this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }
        if (isset($this->request->get['page_success'])) {
            $url .= '&page_success='.$this->request->get['page_success'];
        }
        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('report/payment_receivables', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        $data['orders'] = [];

        $filter_data = [
            'filter_order_id' => $filter_order_id,
            'filter_customer' => $filter_customer,
            'filter_company' => $filter_company,            
            'filter_date_added' => $filter_date_added,
            'filter_date_added_end' => $filter_date_added_end,
            'filter_payment_terms' => $filter_payment_terms,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];


         
        //filter commented, becoz, if multiple customers, then unable to add wallet to  particular customer
        //|| '' != $filter_company|| '' != $filter_date_added || '' != $filter_date_added_end
        if ('' != $filter_date_added  || '' != $filter_date_added_end ) {
           // $order_total = $this->model_sale_transactions->getTotaltransactions($filter_data);
           $order_total_grandTotal = $this->model_sale_order_receivables->getTotalOrderReceivablesAndGrandTotal($filter_data);
        
        //    echo'<pre>';print_r($order_total_grandTotal['total']);exit;
        
        $order_total =$order_total_grandTotal['total'];
        $amount =$order_total_grandTotal['GrandTotal'];
        $results = $this->model_sale_order_receivables->getOrderReceivables($filter_data);

            // echo "<pre>";print_r($results_success);die; 

        } else {
            $order_total_grandTotal = null;
            $order_total=0;
            $amount=0;
            $results = null;
        }


        // $amount=0;
        $totalPages = ceil($order_total / $this->config->get('config_limit_admin'));
        
        $this->load->model('sale/order');
        foreach ($results as $result) {
            // $amount=$amount+$result['total'];
            $totals = $this->model_sale_order->getOrderTotals($result['order_id']);

            // echo "<pre>";print_r($totals);die; 
            foreach ($totals as $total) {
                $data['totals'][] = [
                    'title' => $total['title'],
                    'code' => $total['code'],
                    'text' => $this->currency->format($total['value']),//, $order_info['currency_code'], $order_info['currency_value']
                ];

                if ('total' == $total['code']) {
                    $result['total'] = $total['value'];
                }
            }
            if ($result['company']) {
                $result['company'] = ' (' . $result['company'] . ')';
            } else {
                // $result['company_name'] = "(NA)";
            }
            $data['orders'][] = [
                'order_id' => $result['order_id'],
                'customer_id' => $result['customer_id'],
                // 'no_of_products' => $result['no_of_products'],
                'customer' => $result['firstname'].' '.$result['lastname'],
                'company' => $result['company'],
                'total' => $this->currency->format($result['total']),
                'total_value' =>($result['total']),
                // 'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'grand_total' => $this->currency->format($amount),
                'total_pages' => $totalPages,
                // o.paid,o.amount_partialy_paid
                'paid' => $result['paid'],

                'amount_partialy_paid_value' => $result['amount_partialy_paid'],
                'amount_partialy_paid' => $result['amount_partialy_paid']?$this->currency->format($result['amount_partialy_paid']):'',
                'pending_amount' => $this->currency->format ($result['total']-$result['amount_partialy_paid']),



            ];
        }


        // echo "<pre>";print_r($results_success);die; 
 
    //    echo'<pre>';print_r($data['orders_success']);exit;
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_missing'] = $this->language->get('text_missing');

        $data['column_order_id'] = $this->language->get('column_order_id');
        $data['column_customer'] = $this->language->get('column_customer');
        $data['column_company'] = $this->language->get('column_company');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_date_modified'] = $this->language->get('column_date_modified');
        $data['column_action'] = $this->language->get('column_action');

        $data['entry_return_id'] = $this->language->get('entry_return_id');
        $data['entry_order_id'] = $this->language->get('entry_order_id');
        $data['entry_customer'] = $this->language->get('entry_customer');
        $data['entry_company'] = $this->language->get('entry_company');
        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_total'] = $this->language->get('entry_total');
        $data['entry_date_added'] = $this->language->get('entry_date_added');
        $data['entry_date_modified'] = $this->language->get('entry_date_modified');

        $data['button_invoice_print'] = $this->language->get('button_invoice_print');
        $data['button_shipping_print'] = $this->language->get('button_shipping_print');
        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_show_filter'] = $this->language->get('button_show_filter');
        $data['button_hide_filter'] = $this->language->get('button_hide_filter');
        $data['button_view'] = $this->language->get('button_view');

        $data['token'] = $this->session->data['token'];

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

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array) $this->request->post['selected'];
        } else {
            $data['selected'] = [];
        }

        $url = '';

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id='.$this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status='.$this->request->get['filter_order_status'];
        }

        
        if (isset($this->request->get['filter_payment_terms'])) {
            $url .= '&filter_payment_terms='.$this->request->get['filter_payment_terms'];
        }
        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added='.$this->request->get['filter_date_added'];
        }
        if (isset($this->request->get['filter_date_added_end'])) {
            $url .= '&filter_date_added_end=' . $this->request->get['filter_date_added_end'];
        }

        if ('ASC' == $order) {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        if (isset($this->request->get['page_success'])) {
            $url .= '&page_success='.$this->request->get['page_success'];
        }

        $data['sort_order'] = $this->url->link('report/payment_receivables', 'token='.$this->session->data['token'].'&sort=o.order_id'.$url, 'SSL');
        // $data['sort_city'] = $this->url->link('sale/order_receivables', 'token='.$this->session->data['token'].'&sort=c.name'.$url, 'SSL');
        $data['sort_customer'] = $this->url->link('report/payment_receivables', 'token='.$this->session->data['token'].'&sort=customer'.$url, 'SSL');
        // $data['sort_status'] = $this->url->link('sale/order_receivables', 'token='.$this->session->data['token'].'&sort=status'.$url, 'SSL');
        // $data['sort_total'] = $this->url->link('sale/order_receivables', 'token='.$this->session->data['token'].'&sort=o.total'.$url, 'SSL');
        // $data['sort_date_added'] = $this->url->link('sale/order_receivables', 'token='.$this->session->data['token'].'&sort=o.date_added'.$url, 'SSL');
        // $data['sort_date_modified'] = $this->url->link('sale/order_receivables', 'token='.$this->session->data['token'].'&sort=o.date_modified'.$url, 'SSL');

        $url = '';

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer='.urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_company'])) {
            $url .= '&filter_company='.urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
        }

       

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added='.$this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_added_end'])) {
            $url .= '&filter_date_added_end=' . $this->request->get['filter_date_added_end'];
        }
        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order='.$this->request->get['order'];
        }

        if (isset($this->request->get['filter_payment_terms'])) {
            $url .= '&order='.$this->request->get['filter_payment_terms'];
        }

        $pagination = new Pagination();
        $pagination->total = $order_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('report/payment_receivables', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));



       
        
        $data['filter_customer'] = $filter_customer;
        $data['filter_company'] = $filter_company;
        $data['filter_payment_terms'] = $filter_payment_terms;
         
        $data['filter_date_added'] = $filter_date_added;
        $data['filter_order_id'] = $filter_order_id;
        $data['filter_date_added_end'] = $filter_date_added_end;


        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('report/payment_receivables_list.tpl', $data));
    }

    public function paymentreceivablesexcel()
    {
        $this->load->language('sale/order_receivables');

        $this->document->setTitle($this->language->get('heading_title'));

        if (isset($this->request->get['filter_order_id'])) {
            $filter_order_id = $this->request->get['filter_order_id'];
        } else {
            $filter_order_id = null;
        }

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

        if (isset($this->request->get['filter_date_added'])) {
            $filter_date_added = $this->request->get['filter_date_added'];
        } else {
            $filter_date_added = null;
        }

        if (isset($this->request->get['filter_date_added_end'])) {
            $filter_date_added_end = $this->request->get['filter_date_added_end'];
        } else {
            $filter_date_added_end = null;
        }

        if (isset($this->request->get['filter_payment_terms'])) {
            $filter_payment_terms = $this->request->get['filter_payment_terms'];
        } else {
            $filter_payment_terms = null;
        }
        if (isset($this->request->get['sort'])) {
            $sort  = $this->request->get['sort'];
        }
        else{
            $sort='o.order_id';
        }

        if (isset($this->request->get['order'])) {
            $order  = $this->request->get['order'];
        }
        else{
            $order='DESC';
        }

        $filter_data = [
            'filter_order_id' => $filter_order_id,
            'filter_customer' => $filter_customer,
            'filter_company' => $filter_company,
            'filter_payment_terms' => $filter_payment_terms,
            // 'filter_total' => $filter_total,
            'filter_date_added' => $filter_date_added,
            'filter_date_added_end' => $filter_date_added_end,
            'sort' => $sort,
            'order' => $order,
             
        ];
        
        $this->load->model('report/excel');
        $this->model_report_excel->download_report_payment_receivables_excel($filter_data);
    }
 

}
