<?php

class ControllerReportProductReceivedSold extends Controller {

    private $error = [];

    public function index() {
        $this->load->language('inventory/wastage');


        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('inventory/product_received_sold');

        $this->getList();
    }



    protected function getList() {

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
            $sort = 'pd.name';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
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

        // echo '<pre>';print_r($this->request->get['filter_group_by_date']);die;
      

        // if (isset($this->request->get['filter_group_by_date'])) {
        //     $url .= '&filter_group_by_date=' . urlencode(html_entity_decode($this->request->get['filter_group_by_date'], ENT_QUOTES, 'UTF-8'));
        // }
 
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

        //echo $prices;exit;
        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title') ,
            'href' => $this->url->link('report/product_received_sold', 'token=' . $this->session->data['token'] . $url, 'SSL'),
        ];


        $data['products'] = [];
        $this->load->model('inventory/product_received_sold');


        $filter_data = [
            'filter_name' => $filter_name,           
           
            'filter_date_added' => $filter_date_added,
            'filter_date_added_to' => $filter_date_added_to,
            'sort' => $sort,
            'order' => $order,
            // 'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            // 'limit' => $this->config->get('config_limit_admin'),
        ];

        $this->load->model('tool/image');

 
        $results = $this->model_inventory_product_received_sold->getProductsReceivedSold($filter_data);
        // echo '<pre>';print_r($results);die;

        
        foreach ($results as $result) {

 

                $data['products'][] = [

                    'product_store_id' => $result['product_id'],
                    // 'product_id' => $result['product_id'],
                    'name' => $result['name'],//product_name
                    'unit' => $result['unit'],
                    'procured_qty' => $result['procured_qty'],
                    'rejected_qty' => $result['rejected_qty'],
                    'sold_qty' => $result['quantity'],
                    // 'date_added' => $result['date_added'],
                    // 'added_by_user' => $result['added_by_user'],
                    // 'cumulative_wastage' => $result['cumulative_wastage'],
                    // 'date_added' => $result['date_added'],

                ];
            }

            $product_total =count($results);

        $data['heading_title'] =  $this->language->get('heading_title') ;

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');


        $data['column_unit'] = $this->language->get('column_unit');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_product_id'] = $this->language->get('column_product_id');
        $data['column_vproduct_id'] = $this->language->get('column_vproduct_id');
        $data['column_quantity'] = $this->language->get('column_quantity');

        $data['entry_name'] = $this->language->get('entry_name');


        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_show_filter'] = $this->language->get('button_show_filter');
        $data['button_hide_filter'] = $this->language->get('button_hide_filter');

        $data['token'] = $this->session->data['token'];

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['warning'])) {
            $data['error_warning'] = $this->session->data['warning'];
            unset($this->session->data['warning']);
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
 


        // if (isset($this->request->get['filter_group_by_date'])) {
        //     $url .= '&filter_group_by_date=' . urlencode(html_entity_decode($this->request->get['filter_group_by_date'], ENT_QUOTES, 'UTF-8'));
        // }

        if ('ASC' == $order) {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }


            $data['sort_name'] = $this->url->link('report/product_received_sold', 'token=' . $this->session->data['token'] . '&sort=t.name' . $url, 'SSL');
            $data['sort_product_id'] = $this->url->link('report/product_received_sold', 'token=' . $this->session->data['token'] . '&sort=t.product_id' . $url, 'SSL');


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

        // if (isset($this->request->get['filter_group_by_date'])) {
        //     $url .= '&filter_group_by_date=' . urlencode(html_entity_decode($this->request->get['filter_group_by_date'], ENT_QUOTES, 'UTF-8'));
        // }


        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $product_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');


            $pagination->url = $this->url->link('report/product_received_sold', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');


        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

        $data['filter_name'] = $filter_name;        
        $data['filter_date_added'] = $filter_date_added;
        $data['filter_date_added_to'] = $filter_date_added_to;
        // $data['filter_group_by_date'] = $filter_group_by_date;


        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');

        //echo "<pre>";print_r($data['heading_title'] );die;

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        
        /* PREVIOUS CODE */

        //echo '<pre>';print_r($cachePrice_data);exit;
            $this->response->setOutput($this->load->view('report/product_received_sold_lists.tpl', $data));

    }


  


    public function getUserByName($name) {
        if ($name) {
            $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . "user` u WHERE CONCAT(u.firstname,' ',u.lastname) LIKE '" . $this->db->escape($name) . "%'");

            return $query->row['user_id'];
        }
    }
 

    public function excel()
    {
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

        

        $filter_data = [
            'filter_name' => $filter_name,           
            'filter_date_added' => $filter_date_added,
            'filter_date_added_to' => $filter_date_added_to,
            'sort' => $sort,
            'order' => $order,
            // 'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            // 'limit' => $this->config->get('config_limit_admin'),
        ];


        $this->load->model('report/excel');
        // $this->model_report_excel->download_product_wastage_excel($filter_data);
        $this->model_report_excel->download_product_received_sold_excel($filter_data);
    }
}
