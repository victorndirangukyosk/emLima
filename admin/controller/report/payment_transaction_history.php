<?php

class ControllerReportPaymentTransactionHistory extends Controller
{
    private $error = [];

    public function excel()
    {
        $this->load->language('report/sale_transaction');

        $this->document->setTitle($this->language->get('heading_title'));

        if (isset($this->request->get['filter_order_id'])) {
            $filter_order_id = $this->request->get['filter_order_id'];
        } else {
            $filter_order_id = null;
        }

        if (isset($this->request->get['filter_transaction_id'])) {
            $filter_transaction_id = $this->request->get['filter_transaction_id'];
        } else {
            $filter_transaction_id = null;
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
  

        if (isset($this->request->get['filter_date_modified'])) {
            $filter_date_modified = $this->request->get['filter_date_modified'];
        } else {
            $filter_date_modified = null;
        }

        if (isset($this->request->get['filter_user'])) {
            $filter_user = $this->request->get['filter_user'];
        } else {
            $filter_user = null;
        }

        if (isset($this->request->get['filter_user_id'])) {
            $filter_user_id = $this->request->get['filter_user_id'];
        } else {
            $filter_user_id = null;
        }

        $filter_data = [
            'filter_order_id' => $filter_order_id,
            'filter_transaction_id' => $filter_transaction_id,
            'filter_customer' => $filter_customer,
            'filter_company' => $filter_company,
            'filter_user' => $filter_user,
            'filter_user_id' => $filter_user_id,
            'filter_date_added' => $filter_date_added,
            'filter_date_modified' => $filter_date_modified,
        ];

        $this->load->model('report/excel');
        $this->model_report_excel->download_payment_transaction_history_excel($filter_data);
    }

    public function index()
    {
        $this->load->language('report/payment_transaction_history');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('report/payment_transaction_history');

        $this->getList();
    }

    protected function getList()
    {
         

        if (isset($this->request->get['filter_order_id'])) {
            $filter_order_id = $this->request->get['filter_order_id'];
        } else {
            $filter_order_id = null;
        }

        if (isset($this->request->get['filter_transaction_id'])) {
            $filter_transaction_id = $this->request->get['filter_transaction_id'];
        } else {
            $filter_transaction_id = null;
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
  

        if (isset($this->request->get['filter_date_modified'])) {
            $filter_date_modified = $this->request->get['filter_date_modified'];
        } else {
            $filter_date_modified = null;
        }

        if (isset($this->request->get['filter_user'])) {
            $filter_user = $this->request->get['filter_user'];
        } else {
            $filter_user = null;
        }

        if (isset($this->request->get['filter_user_id'])) {
            $filter_user_id = $this->request->get['filter_user_id'];
        } else {
            $filter_user_id = null;
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

        if (isset($this->request->get['filter_transaction_id'])) {
            $url .= '&filter_transaction_id='.$this->request->get['filter_transaction_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer='.urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_company'])) {
            $url .= '&filter_company='.urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_user'])) {
            $url .= '&filter_user='.urlencode(html_entity_decode($this->request->get['filter_user'], ENT_QUOTES, 'UTF-8'));
        }  

        if (isset($this->request->get['filter_user_id'])) {
            $url .= '&filter_user_id='.urlencode(html_entity_decode($this->request->get['filter_user_id'], ENT_QUOTES, 'UTF-8'));
        }  
   
        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added='.$this->request->get['filter_date_added'];
        } 
        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified='.$this->request->get['filter_date_modified'];
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
            'href' => $this->url->link('sale/order', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

       
        $data['orders'] = [];

        $filter_data = [
            'filter_order_id' => $filter_order_id,
            'filter_transaction_id' => $filter_transaction_id,
            'filter_customer' => $filter_customer,
            'filter_company' => $filter_company,
            'filter_user' => $filter_user,
            'filter_user_id' => $filter_user_id,
            'filter_date_added' => $filter_date_added,
            'filter_date_modified' => $filter_date_modified,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        if ('' != $filter_order_id|| '' != $filter_user || '' != $filter_transaction_id || ('' != $filter_date_added || '' != $filter_date_modified) ) 
        {
         $order_total = $this->model_report_payment_transaction_history->getTotalOrders($filter_data);

        $results = $this->model_report_payment_transaction_history->getPamentTransactionHistory($filter_data); 
        }
        else {
            $order_total =0;
            $results =null;
        }

        //echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
           
            $latest_total = 0;
            $latest_total =  $this->model_report_payment_transaction_history->getOrderExactTotal($result['order_id']);
 
            $data['orders'][] = [
                'id' => $result['id'],
                'order_id' => $result['order_id'],
                'transaction_id' => $result['transaction_id'],
                'amount_received' => $result['amount_received'],
                'partial_amount' => $result['partial_amount'],
                'patial_amount_applied' => $result['patial_amount_applied'],
                'total' => $this->currency->format($latest_total),
                // 'date_added' => $result['date_added'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),

                'ip' => $result['ip'],
                'user' => $result['user'],
                'credit_id' => $result['credit_id'], 
                 ];
        }

        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results'); 
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

         

        $url = '';

        if (isset($this->request->get['filter_user'])) {
            $url .= '&filter_user='.$this->request->get['filter_user'];
        }

        if (isset($this->request->get['filter_user_id'])) {
            $url .= '&filter_user_id='.$this->request->get['filter_user_id'];
        }

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id='.$this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_transaction_id'])) {
            $url .= '&filter_transaction_id='.$this->request->get['filter_transaction_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer='.urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_company'])) {
            $url .= '&filter_company='.urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
        }

         
        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added='.$this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified='.$this->request->get['filter_date_modified'];
        }

        if ('ASC' == $order) {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        
        $url = '';

        if (isset($this->request->get['filter_user'])) {
            $url .= '&filter_user='.$this->request->get['filter_user'];
        }
        if (isset($this->request->get['filter_user_id'])) {
            $url .= '&filter_user_id='.$this->request->get['filter_user_id'];
        }

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id='.$this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_transaction_id'])) {
            $url .= '&filter_transaction_id='.$this->request->get['filter_transaction_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer='.urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['filter_company'])) {
            $url .= '&filter_company='.urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
        }
         

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added='.$this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified='.$this->request->get['filter_date_modified'];
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
        $pagination->url = $this->url->link('report/payment_transaction_history', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

        $data['filter_user'] = $filter_user;
        $data['filter_user_id'] = $filter_user_id;
        $data['filter_order_id'] = $filter_order_id;
        $data['filter_transaction_id'] = $filter_transaction_id;
        $data['filter_customer'] = $filter_customer;
        $data['filter_company'] = $filter_company;
        $data['filter_date_added'] = $filter_date_added;
        $data['filter_date_modified'] = $filter_date_modified;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('report/payment_transaction_history.tpl', $data));
    }

    public function getUserByName($name)
    {
        if ($name) {
            $query = $this->db->query('SELECT * FROM `'.DB_PREFIX."user` u WHERE CONCAT(u.firstname,' ',u.lastname) LIKE '".$this->db->escape($name)."%'");

            return $query->row['user_id'];
        }
    }

    public function getUser($id)
    {
        if ($id) {
            $query = $this->db->query('SELECT * FROM `'.DB_PREFIX."customer`  WHERE customer_id ='".$id."'");

            return $query->row['fax'];
        }
    }

    public function country()
    {
        $json = [];

        $this->load->model('localisation/country');

        $country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

        if ($country_info) {
            $this->load->model('localisation/zone');

            $json = [
                'country_id' => $country_info['country_id'],
                'name' => $country_info['name'],
                'iso_code_2' => $country_info['iso_code_2'],
                'iso_code_3' => $country_info['iso_code_3'],
                'address_format' => $country_info['address_format'],
                'postcode_required' => $country_info['postcode_required'],
                'zone' => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
                'status' => $country_info['status'],
            ];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function city_autocomplete()
    {
        $this->load->model('report/sale_transaction');

        $json = $this->model_report_sale_transaction->getCities();

        header('Content-type: text/json');
        echo json_encode($json);
    }

    public function user_autocomplete()
    {
        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = '';
        }

        $this->load->model('sale/order');

        $json = $this->model_sale_order->getUserData($filter_name);

        echo json_encode($json);
    }

}
