<?php

class ControllerSaleCustomerFeedback extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('sale/customer_feedback');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/customer_feedback');

        $this->getList();
    }

      

    protected function getList()
    {

        if (isset($this->request->get['feedback_id'])) {
            $filter_feedback_id = $this->request->get['feedback_id'];
        }  

        if (isset($this->request->get['filter_company'])) {
            $filter_company = $this->request->get['filter_company'];
        } else {
            $filter_company = null;
        }

        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }


        if (isset($this->request->get['filter_customer_rating_id'])) {
            $filter_customer_rating_id = $this->request->get['filter_customer_rating_id'];
        } else {
            $filter_customer_rating_id = null;
        }

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = null;
        }

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

 

        if (isset($this->request->get['filter_company'])) {
            $url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_customer_rating_id'])) {
            $url .= '&filter_customer_rating_id=' . $this->request->get['filter_customer_rating_id'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
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
            'href' => $this->url->link('sale/customer_feedback', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];        
        $data['customer_feedbacks'] = [];
        $isaccountmanager=$this->user->isAccountManager();

 
        $filter_data = [
            
            'filter_feedback_id' => $filter_feedback_id,
            'filter_company' => $filter_company,
            'filter_name' => $filter_name,
            'filter_customer_rating_id' => $filter_customer_rating_id,
            'filter_status' => $filter_status,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
            'isaccountmanager' => $isaccountmanager,

        ];
        $customer_feedback_total = $this->model_sale_customer_feedback->getTotalCustomerFeedbacks($filter_data);

        $results = $this->model_sale_customer_feedback->getCustomerFeedbacks($filter_data);

        foreach ($results as $result) {

            if ($result['company_name']) {
                $result['company_name'] = ' (' . $result['company_name'] . ')';
            } else {
                // $result['company_name'] = "(NA)";
            }
            $data['customer_feedbacks'][] = [
                'feedback_id' => $result['feedback_id'],
                'rating' => $result['rating'],
                'comments' => $result['comments'],
                'customer_name' => $result['name'],
                'company_name' => $result['company_name'],
                'feedback_type' =>  ($result['feedback_type'] =="S"? "Suggestions" : ($result['feedback_type'] =="I"? "Issue"." - ".$result['issue_type'] :"Happy")),
                'order_id' => ($result['order_id']==0?"NA":$result['order_id']),
                'status' => $result['status'],
                'created_date' => $result['created_date'],
                'closed_date' => $result['closed_date'],
                'closed_comments' => $result['closed_comments'],
                'accepted_user' => $result['accepted_user'],
                 
            ];
        }

        // echo print_r( $data['customer_feedbacks']);die;
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results'); 

        $data['column_rating'] = $this->language->get('column_rating');
        $data['column_feedback_type'] = $this->language->get('column_feedback_type');
        $data['column_comments'] = $this->language->get('column_comments');
        $data['column_Customer'] = $this->language->get('column_Customer');
        $data['column_Company'] = $this->language->get('column_Company');

        $data['button_edit'] = $this->language->get('button_edit');

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
        
        if (isset($this->request->get['filter_company'])) {
            $url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['filter_customer_rating_id'])) {
            $url .= '&filter_customer_rating_id=' . $this->request->get['filter_customer_rating_id'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if ('ASC' == $order) {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        $data['sort_rating'] = $this->url->link('sale/customer_feedback', 'token='.$this->session->data['token'].'&sort=rating'.$url, 'SSL');

        $url = '';


        if (isset($this->request->get['filter_company'])) {
            $url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_customer_rating_id'])) {
            $url .= '&filter_customer_rating_id=' . $this->request->get['filter_customer_rating_id'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }
        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order='.$this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $customer_feedback_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('sale/customer_feedback', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($customer_feedback_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($customer_feedback_total - $this->config->get('config_limit_admin'))) ? $customer_feedback_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $customer_feedback_total, ceil($customer_feedback_total / $this->config->get('config_limit_admin')));


        $data['filter_company'] = $filter_company;
        $data['filter_name'] = $filter_name;
        
        $data['filter_customer_rating_id'] = $filter_customer_rating_id;
        $data['filter_status'] = $filter_status;

        $data['sort'] = $sort;
        $data['order'] = $order;
        // $data['customer_ratings'][] = [
        //     'customer_rating_id' => 0,             
        // ];
          $data['customer_ratings'][] = [
            'customer_rating_id' => 1,             
        ];
        $data['customer_ratings'][] = [
            'customer_rating_id' => 2,             
        ];
        $data['customer_ratings'][] = [
            'customer_rating_id' => 3,             
        ];
        $data['customer_ratings'][] = [
            'customer_rating_id' => 4,             
        ]; $data['customer_ratings'][] = [
            'customer_rating_id' => 5,             
        ];

        $data['issue_statuses'][] = [
            'name' => 'Open',             
        ];
        $data['issue_statuses'][] = [
            'name' => 'Closed',             
        ];
        $data['issue_statuses'][] = [
            'name' => 'Attending',             
        ];
 

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['token'] = $this->session->data['token'];

        $this->response->setOutput($this->load->view('sale/customer_feedback_list.tpl', $data));
    }

     

     

    public function acceptIssue() {
        $this->load->model('sale/customer_feedback');
        //echo 'date.timezone ' ;;
        $data = $this->request->post;

        //   echo '<pre>';print_r($this->request->post);exit;

        if ('POST' == $this->request->server['REQUEST_METHOD']) {
            $data = $this->model_sale_customer_feedback->acceptIssue($this->request->post['feedback_id'],$this->user->getId());

            $data['status'] = true;

            if ($this->request->isAjax()) {
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($data));
            }
        } else {
            $data['status'] = false;

            if ($this->request->isAjax()) {
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($data));
            }
        }
        //  echo '<pre>';print_r($data);exit;

        return true;
    }


    public function closeIssue() {
        $this->load->model('sale/customer_feedback');
        //echo 'date.timezone ' ;;
        $data = $this->request->post;

        //    echo '<pre>';print_r($this->request->post);exit;

        if ('POST' == $this->request->server['REQUEST_METHOD']) {
            $data = $this->model_sale_customer_feedback->closeIssue($this->request->post['feedback_id'],$this->request->post['closing_comments'],$this->user->getId());

            $data['status'] = true;

            if ($this->request->isAjax()) {
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($data));
            }
        } else {
            $data['status'] = false;

            if ($this->request->isAjax()) {
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($data));
            }
        }
        //  echo '<pre>';print_r($data);exit;

        return true;
    }



    public function export_excel() {
        
        $this->load->model('report/excel');

        if (isset($this->request->get['feedback_id'])) {
            $filter_feedback_id = $this->request->get['feedback_id'];
        }  

        if (isset($this->request->get['filter_company'])) {
            $filter_company = $this->request->get['filter_company'];
        } else {
            $filter_company = null;
        }

        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }


        if (isset($this->request->get['filter_customer_rating_id'])) {
            $filter_customer_rating_id = $this->request->get['filter_customer_rating_id'];
        } else {
            $filter_customer_rating_id = null;
        }

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = null;
        }
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

        $filter_data = [
            
            'filter_feedback_id' => $filter_feedback_id,
            'filter_company' => $filter_company,
            'filter_name' => $filter_name,
            'filter_customer_rating_id' => $filter_customer_rating_id,
            'filter_status' => $filter_status,
            'sort' => $sort,
            'order' => $order,
            
        ];      
        $this->load->model('sale/customer_feedback');

        $results = $this->model_sale_customer_feedback->getCustomerFeedbacks($filter_data);
        
        //  echo print_r( $results);die;
        
        
        foreach ($results as $result) {

            if ($result['company_name']) {
                $result['company_name'] = ' (' . $result['company_name'] . ')';
            } else {
                // $result['company_name'] = "(NA)";
            }
            $data['customer_feedbacks'][] = [
                'feedback_id' => $result['feedback_id'],
                'rating' => $result['rating'],
                'comments' => $result['comments'],
                'customer_name' => $result['name'],
                'company_name' => $result['company_name'],
                'feedback_type' =>  ($result['feedback_type'] =="S"? "Suggestions" : ($result['feedback_type'] =="I"? "Issue"." - ".$result['issue_type'] :"Happy")),
                'order_id' => ($result['order_id']==0?"NA":$result['order_id']),
                'status' => $result['status'],
                'created_date' => $result['created_date'],
                'closed_date' => $result['closed_date'],
                'closed_comments' => $result['closed_comments'],
                'accepted_user' => $result['accepted_user'],
                 
            ];
        }

        //  echo print_r( $data);die;

        $this->model_report_excel->download_feedback_excel($data);
    }

}
