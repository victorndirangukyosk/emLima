<?php

class ControllerSaleOrderReceivables extends Controller
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

        if (isset($this->request->get['filter_total'])) {
            $filter_total = $this->request->get['filter_total'];
        } else {
            $filter_total = null;
        }

        if (isset($this->request->get['filter_date_added'])) {
            $filter_date_added = $this->request->get['filter_date_added'];
        } else {
            $filter_date_added = null;
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

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total='.$this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added='.$this->request->get['filter_date_added'];
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

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('sale/order_receivables', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        $data['orders'] = [];

        $filter_data = [
            'filter_order_id' => $filter_order_id,
            'filter_customer' => $filter_customer,
            'filter_company' => $filter_company,
            'filter_total' => $filter_total,
            'filter_date_added' => $filter_date_added,

            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];


        if ('' != $filter_customer || '' != $filter_company || '' != $filter_order_id) {
           // $order_total = $this->model_sale_transactions->getTotaltransactions($filter_data);
        $order_total_grandTotal = $this->model_sale_order_receivables->getTotalOrderReceivablesAndGrandTotal($filter_data);
        
        //    echo'<pre>';print_r($order_total_grandTotal['total']);exit;
        
        $order_total =$order_total_grandTotal['total'];
        $amount =$order_total_grandTotal['GrandTotal'];
        $results = $this->model_sale_order_receivables->getOrderReceivables($filter_data);
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
                    'text' => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
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
        // echo'<pre>';print_r($data['orders']);exit;
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

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total='.$this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added='.$this->request->get['filter_date_added'];
        }

        if ('ASC' == $order) {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        $data['sort_order'] = $this->url->link('sale/order_receivables', 'token='.$this->session->data['token'].'&sort=o.order_id'.$url, 'SSL');
        // $data['sort_city'] = $this->url->link('sale/order_receivables', 'token='.$this->session->data['token'].'&sort=c.name'.$url, 'SSL');
        $data['sort_customer'] = $this->url->link('sale/order_receivables', 'token='.$this->session->data['token'].'&sort=customer'.$url, 'SSL');
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

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total='.$this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added='.$this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order='.$this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $order_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('sale/order_receivables', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

        $data['filter_customer'] = $filter_customer;
        $data['filter_company'] = $filter_company;

        $data['filter_total'] = $filter_total;
        $data['filter_date_added'] = $filter_date_added;
        $data['filter_order_id'] = $filter_order_id;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('sale/order_receivables_list.tpl', $data));
    }

    public function orderreceivablesexcel()
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

        // if (isset($this->request->get['filter_total'])) {
        //     $filter_total = $this->request->get['filter_total'];
        // } else {
        //     $filter_total = null;
        // }

        // if (isset($this->request->get['filter_date_added'])) {
        //     $filter_date_added = $this->request->get['filter_date_added'];
        // } else {
        //     $filter_date_added = null;
        // }

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
            // 'filter_total' => $filter_total,
            // 'filter_date_added' => $filter_date_added,
            // 'sort' => $sort,
              'order' => $order,
             
        ];
        
        $this->load->model('report/excel');
        // $this->model_report_excel->download_sale_ordertransaction_excel($filter_data);
        $this->model_report_excel->download_sale_order_receivables_excel($filter_data);
    }




    public function confirmPaymentReceived() {
        try{
        $this->load->model('sale/order_receivables'); 
         // echo '<pre>';print_r($this->request->post);exit;
        if (!$this->user->hasPermission('modify', 'sale/order_receivables')) {
            $data['error'] = $this->language->get('error_permission');
            $data['status']=false;
        } else {            

             $this->model_sale_order_receivables->confirmPaymentReceived($this->request->post['paid_order_id'], $this->request->post['transaction_id']);
            
            $data['success'] = 'Updated Successfully';
            // Add to activity log
            $log = new Log('error.log');
            $this->load->model('user/user_activity');
            $activity_data = [
                'user_id' => $this->user->getId(),
                'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
                'user_group_id' => $this->user->getGroupId(),
                'order_id' => $this->request->post['paid_order_id'],
            ];
            $log->write('order transaction id added');
            $this->model_user_user_activity->addActivity('order_transaction_id_added', $activity_data);
            $log->write('order transaction id added');
        $data['status']=true;

        }
    }
    catch(exception $ex)
    {
        $data['error']="Please try again";
        $data['status']=false;
    }
    finally{

        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($data));
        }
    }
 
    }


    public function confirmBulkPaymentReceived() {

    if (isset($this->request->post['selected'])) {
        $orders =explode(",",$this->request->post['selected']);
        sort($orders);
    }  
 
    if (isset($this->request->post['amount_received'])) {
        $amount_received = $this->request->post['amount_received'];
    }  
    if (isset($this->request->post['grand_total'])) {
        $grand_total = $this->request->post['grand_total'];
    }  
    if (isset($this->request->post['transaction_id'])) {
        $transaction_id = $this->request->post['transaction_id'];
    }  

    // echo'<pre>';print_r($orders);exit;

    try{
        $this->load->model('sale/order_receivables'); 
         // echo '<pre>';print_r($this->request->post);exit;
        if (!$this->user->hasPermission('modify', 'sale/order_receivables')) {
            $data['error'] = $this->language->get('error_permission');
            $data['status']=false;
        } else {           
            
            if($amount_received ==$grand_total)
            {

                foreach($orders as $order)
                {

                $this->model_sale_order_receivables->confirmPaymentReceived($order, $transaction_id);
                // Add to activity log
                $log = new Log('error.log');
                $this->load->model('user/user_activity');
                $activity_data = [
                    'user_id' => $this->user->getId(),
                    'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
                    'user_group_id' => $this->user->getGroupId(),
                    'order_id' => $this->request->post['order'],
                ];
                $log->write('order transaction id added');
                $this->model_user_user_activity->addActivity('order_transaction_id_added', $activity_data);
                $log->write('order transaction id added');
                
                }
            }

            else
            {
                $log = new Log('error.log');
                $log->write('check for payment receivables');

                    $grand_amount_availble=$amount_received;
                $log->write($grand_amount_availble);

                foreach($orders as $order)
                {
                    $amount_partialy_paid=0;$ordertotal_remaining=0;
                    $ordertotal_array=$this->model_sale_order_receivables->getOrderTotal($order);
                    $ordertotal= $ordertotal_array[order_total];
                    $amount_partialy_paid=$ordertotal_array[amount_partialy_paid];
                
                    $log->write($ordertotal);
                    $log->write("ordertotal");
                    
                    $ordertotal_needtopay=$ordertotal-$amount_partialy_paid;
                    $order_amount_sufficient=$grand_amount_availble-$ordertotal_needtopay;
                    $log->write("order_amount_sufficient");
                    $log->write($order_amount_sufficient);
                    //    exit;
                    if($order_amount_sufficient>=0){
                $this->model_sale_order_receivables->confirmPaymentReceived($order, $transaction_id);
                    }
                    else
                {

                    $amount_partialy_paid=$amount_partialy_paid+$grand_amount_availble;
                $this->model_sale_order_receivables->confirmPartialPaymentReceived($order, $transaction_id,'',$amount_partialy_paid);
                }
                $grand_amount_availble=$grand_amount_availble-$ordertotal_needtopay;

                // Add to activity log
               
                $this->load->model('user/user_activity');
                $activity_data = [
                    'user_id' => $this->user->getId(),
                    'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
                    'user_group_id' => $this->user->getGroupId(),
                    'order_id' => $this->request->post['order'],
                ];
                $log->write('order transaction id added');
                $this->model_user_user_activity->addActivity('order_transaction_id_added', $activity_data);
                $log->write('order transaction id added');
                

                if($grand_amount_availble<=0)
                {
                    break;//break the loop if avaialble grand total received becomes 0
                }
                }
            }
            $data['success'] = 'Updated Successfully';
           
        $data['status']=true;

        }
    }
    catch(exception $ex)
    {
        $data['error']="Please try again";
        $data['status']=false;
    }
    finally{

        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($data));
        }
    }
 

}
}
