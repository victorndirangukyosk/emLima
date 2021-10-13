<?php

class ControllerReportcustomerwallet extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('report/customer_wallet');
        $this->document->setTitle($this->language->get('heading_title'));


        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }

        if (isset($this->request->get['filter_company'])) {
            $filter_company = $this->request->get['filter_company'];
        } else {
            $filter_company = null;
        }

        if (isset($this->request->get['filter_email'])) {
            $filter_email = $this->request->get['filter_email'];
        } else {
            $filter_email = null;
        }

        if (isset($this->request->get['filter_type'])) {
            $filter_type = $this->request->get['filter_type'];
        } else {
            $filter_type = null;
        }

        if (isset($this->request->get['filter_order_id'])) {
            $filter_order_id = $this->request->get['filter_order_id'];
        } else {
            $filter_order_id = null;
        }

        if (isset($this->request->get['filter_date_added'])) {
            $filter_date_added = $this->request->get['filter_date_added'];
        } else {
            $filter_date_added = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'date_added';
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

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name='.urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_company'])) {
            $url .= '&filter_company='.urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email='.urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_type'])) {
            $url .= '&filter_type='.urlencode(html_entity_decode($this->request->get['filter_type'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id='.urlencode(html_entity_decode($this->request->get['filter_order_id'], ENT_QUOTES, 'UTF-8'));
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
            'href' => $this->url->link('report/customer_wallet', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        $this->load->model('sale/customer');

        $data['customers'] = [];

        $filter_data = [
            'filter_name' => $filter_name,
            'filter_company' => $filter_company,
            'filter_email' => $filter_email,
            'filter_type' => $filter_type,
            'filter_order_id' => $filter_order_id,
            'filter_date_added' => $filter_date_added,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        //echo "<pre>";print_r($filter_data);die;
        $customer_total = $this->model_sale_customer->getTotalWallet($filter_data);

        //$results = $this->model_sale_customer->getCustomers($filter_data);

        $results = $this->model_sale_customer->getAllCredits($filter_data);

        //$customer_total = count($results);
        //  echo "<pre>";print_r($results);die;
        //echo "<pre>";print_r($customer_total);die;
        foreach ($results as $result) {
            $transaction_ID="";
            if(isset($result['transaction_id']) && $result['transaction_id']!="" )
            {
                $transaction_ID='#Transaction ID '.$result['transaction_id'];
            }
            if ($result['company_name']) {
                $result['company_name'] = ' (' . $result['company_name'] . ')';
            }
            $data['customers'][] = [
                // 'customer_id' => $result['customer_id'],
                'name' => $result['name'],
                'company' => $result['company_name'],
                // 'email' => $result['email'],
                'amount' => $result['amount'],
                'description' => $result['description'].' ' .$transaction_ID,
                // 'transaction_id' => $result['transaction_id'],
                'order_id' => $result['order_id'],
                'customer_credit_id' => $result['customer_credit_id'],
                'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added'])),
            ];
        }

        $data['filter_order'] = $this->language->get('filter_order');

        $data['heading_title'] = $this->language->get('heading_title');
        $data['column_transaction_type'] = $this->language->get('column_transaction_type');
       
        $data['column_amount'] = $this->language->get('column_amount');
        $data['column_description'] = $this->language->get('column_description');

        $data['column_order_id'] = $this->language->get('column_order_id');
        $data['column_credit_id'] = $this->language->get('column_credit_id');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_email'] = $this->language->get('column_email');
        $data['column_customer_group'] = $this->language->get('column_customer_group');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_approved'] = $this->language->get('column_approved');
        $data['column_ip'] = $this->language->get('column_ip');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_action'] = $this->language->get('column_action');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_transaction_type'] = $this->language->get('entry_transaction_type');
        $data['entry_customer_group'] = $this->language->get('entry_customer_group');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_approved'] = $this->language->get('entry_approved');
        $data['entry_ip'] = $this->language->get('entry_ip');
        $data['entry_date_added'] = $this->language->get('entry_date_added');

        

        $data['token'] = $this->session->data['token'];

        

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name='.urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_company'])) {
            $url .= '&filter_company='.urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email='.urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id='.urlencode(html_entity_decode($this->request->get['filter_order_id'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_type'])) {
            $url .= '&filter_type='.urlencode(html_entity_decode($this->request->get['filter_type'], ENT_QUOTES, 'UTF-8'));
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

         $data['sort_name'] = $this->url->link('report/customer_wallet', 'token='.$this->session->data['token'].'&sort=name'.$url, 'SSL');
        // $data['sort_email'] = $this->url->link('wallets/customer_wallet', 'token='.$this->session->data['token'].'&sort=c.email'.$url, 'SSL');
          $data['sort_date_added'] = $this->url->link('report/customer_wallet', 'token='.$this->session->data['token'].'&sort=date_added'.$url, 'SSL');

        // $data['link_order_id'] = $this->url->link('sale/order/info', 'token='.$this->session->data['token'].'&sort=date_added'.$url, 'SSL');

        // $data['link_customer'] = $this->url->link('sale/customer/edit', 'token='.$this->session->data['token'].'&sort=date_added'.$url, 'SSL');

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name='.urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_company'])) {
            $url .= '&filter_company='.urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email='.urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id='.urlencode(html_entity_decode($this->request->get['filter_order_id'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_type'])) {
            $url .= '&filter_type='.urlencode(html_entity_decode($this->request->get['filter_type'], ENT_QUOTES, 'UTF-8'));
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
        $pagination->total = $customer_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('report/customer_wallet', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($customer_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($customer_total - $this->config->get('config_limit_admin'))) ? $customer_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $customer_total, ceil($customer_total / $this->config->get('config_limit_admin')));

        $data['filter_name'] = $filter_name;
        $data['filter_company'] = $filter_company;
        $data['filter_email'] = $filter_email;
        $data['filter_order_id'] = $filter_order_id;
        $data['filter_type'] = $filter_type;
        $data['filter_date_added'] = $filter_date_added;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('report/customer_wallet.tpl', $data));

       
    }

    

    public function customerwalletexcel()
    {
        $this->load->language('report/customer_wallet');

        $this->document->setTitle($this->language->get('heading_title'));

        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }

        if (isset($this->request->get['filter_company'])) {
            $filter_company = $this->request->get['filter_company'];
        } else {
            $filter_company = null;
        }

        if (isset($this->request->get['filter_email'])) {
            $filter_email = $this->request->get['filter_email'];
        } else {
            $filter_email = null;
        }

        if (isset($this->request->get['filter_type'])) {
            $filter_type = $this->request->get['filter_type'];
        } else {
            $filter_type = null;
        }

        if (isset($this->request->get['filter_order_id'])) {
            $filter_order_id = $this->request->get['filter_order_id'];
        } else {
            $filter_order_id = null;
        }

        if (isset($this->request->get['filter_date_added'])) {
            $filter_date_added = $this->request->get['filter_date_added'];
        } else {
            $filter_date_added = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'date_added';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }


        
        // $this->load->model('sale/customer');

        // $data['customers'] = [];

        $filter_data = [
            'filter_name' => $filter_name,
            'filter_company' => $filter_company,
            'filter_email' => $filter_email,
            'filter_type' => $filter_type,
            'filter_order_id' => $filter_order_id,
            'filter_date_added' => $filter_date_added,
            'sort' => $sort,
            'order' => $order 
        ];

        $this->load->model('report/excel');
        $this->model_report_excel->download_customer_wallet_excel($filter_data);
    }
}
