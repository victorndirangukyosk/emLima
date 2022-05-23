<?php

class ControllerReportMissingProductsRevenue extends Controller {

    private $error = [];

    public function index() {

        $this->load->language('sale/order_product_missing');
         
        $this->load->model('sale/order');
        $this->getMissingProductsList();
    }

    protected function getMissingProductsList() {

          

        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }

   
             

        if (isset($this->request->get['filter_date_added'])) {
            $filter_date_added = $this->request->get['filter_date_added'];
        } else {
            $filter_date_added = null;
        }

        if (isset($this->request->get['filter_date_added_to'])) {
            $filter_date_added_to = $this->request->get['filter_date_added_to'];
        } else {
            $filter_date_added_to = null;
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

          

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        } 
 

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_added_to'])) {
            $url .= '&filter_date_added_to=' . $this->request->get['filter_date_added_to'];
        }

        
        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
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
            'href' => $this->url->link('report/missing_products_revenue', 'token=' . $this->session->data['token'] . $url, 'SSL'),
        ];

        $data['orders'] = [];

        $filter_data = [
          
            'filter_name' => $filter_name,        
            'filter_date_added' => $filter_date_added,
            'filter_date_added_to' => $filter_date_added_to,
            'sort' => $sort,
            'order' => $order, // all orders are fecting by group, so dont send limit
                // 'start' => ($page - 1) * $this->config->get('config_limit_admin'),
                // 'limit' => $this->config->get('config_limit_admin'),
        ];

        // echo "<pre>";print_r($filter_data);die;        
        
        #region

     

        $results = $this->model_sale_order->getMissingProductsSummary($filter_data);
        // echo "<pre>";print_r($results);die;
        
        if (!empty($results)) { 

            
            foreach ($results as $result) {
            $data['orders'] []= [
                
                'product_store_id' => $result['product_store_id'],
                'name' => $result['name'],
                'unit' => $result['unit'],
                // 'quantity' => $result['quantity'],
                'quantity_required' => $result['quanity'],
                'total' => $result['total'],
                // 'price' => $result['price'],
                // 'tax' => $result['tax'],
               
            ];
        }
        $results_count=count($results);
        } else {
            $results_count = 0;
            $results = [];
        }


     
        #end region
        // echo "<pre>";print_r($data['orders']);die;

        $data['all_orders'] = $data['orders'];
        // echo "<pre>";print_r($data['all_orders']);die;
 

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

          

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }
         
        
  
        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_added_to'])) {
            $url .= '&filter_date_added_to=' . $this->request->get['filter_date_added_to'];
        }

        

        if ('ASC' == $order) {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_product_store_id'] = $this->url->link('report/missing_products_revenue', 'token=' . $this->session->data['token'] . '&sort=mp.product_store_id' . $url, 'SSL');
        $data['sort_name'] = $this->url->link('report/missing_products_revenue', 'token=' . $this->session->data['token'] . '&sort=mp.name' . $url, 'SSL');
       
        $url = '';

         
 
        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }
 
          

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_added_to'])) {
            $url .= '&filter_date_added_to=' . $this->request->get['filter_date_added_to'];
        }

         

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $results_count;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('report/missing_products_revenue', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($results_count) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($results_count - $this->config->get('config_limit_admin'))) ? $results_count : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $results_count, ceil($results_count / $this->config->get('config_limit_admin')));

        
        $data['filter_name'] = $filter_name;       
        $data['filter_date_added'] = $filter_date_added;
        $data['filter_date_added_to'] = $filter_date_added_to;

       $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');         
        
 //as the dynamic pagination will not work for this calculation , applied pagination on array
 $start = ($page - 1) * $this->config->get('config_limit_admin');
 $limit = $this->config->get('config_limit_admin');

 $data['all_orders'] = array_slice($data['all_orders'], $start, $limit);
        $this->response->setOutput($this->load->view('report/missing_products_revenue.tpl', $data));
    }

    public function getUserByName($name) {
        if ($name) {
            $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . "user` u WHERE CONCAT(u.firstname,' ',u.lastname) LIKE '" . $this->db->escape($name) . "%'");

            return $query->row['user_id'];
        }
    }

    public function downloadmissingproducts() {

       
 

        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }

          
 

        if (isset($this->request->get['filter_date_added'])) {
            $filter_date_added = $this->request->get['filter_date_added'];
        } else {
            $filter_date_added = null;
        }

        if (isset($this->request->get['filter_date_added_to'])) {
            $filter_date_added_to = $this->request->get['filter_date_added_to'];
        } else {
            $filter_date_added_to = null;
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


        


        $filter_data = [
           
            'filter_name' => $filter_name,
            
            'filter_date_added' => $filter_date_added,
            'filter_date_added_to' => $filter_date_added_to,
            'sort' => $sort,
            'order' => $order,
        ];

       

        $this->load->model('sale/order');
        $results = $this->model_sale_order->getMissingProductsSummary($filter_data);
        // echo "<pre>";print_r($results);die;
        
        if (!empty($results)) { 

            
            foreach ($results as $result) {
            $data['orders'] []= [
                
                'product_store_id' => $result['product_store_id'],
                'name' => $result['name'],
                'unit' => $result['unit'],
                // 'quantity' => $result['quantity'],
                'quantity_required' => $result['quanity'],
                'total' => $result['total'],
                // 'price' => $result['price'],
                // 'tax' => $result['tax'],
               
            ];
        }
        // $results_count=count($results);
        } else {
            // $results_count = 0;
            $results = [];
        }
        
        $this->load->model('report/excel');
        
        $this->model_report_excel->download_missing_products_summary_excel_report($data['orders']);
    }

}
