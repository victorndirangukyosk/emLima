<?php

class ControllerReportOrderAndUpdatedProduct extends Controller {

    public function index() {
        $this->load->language('report/order_and_updated_product');

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


        if (isset($this->request->get['filter_order_id'])) {
            $filter_order_id = $this->request->get['filter_order_id'];
        } else {
            $filter_order_id = '';
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


        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $this->load->model('report/order_product');

        $data['customers'] = [];

        $filter_data = [
            'filter_date_start' => $filter_date_start,
            'filter_date_end' => $filter_date_end,
            'filter_order_id' => $filter_order_id,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        // $customer_total = $this->model_report_order_product->getTotalOrders($filter_data);

        // $results = $this->model_report_order_product->getOrders($filter_data);

        // $new_array = NULL;
        // foreach ($results as $result) {
        //     $ordered_products = $this->model_report_order_product->getOrderedProducts($result['order_id']);
        //     $order_updated_products = $this->model_report_order_product->getOrderUpdatedProducts($result['order_id']);
        //     $all_products = $this->custom_array_merge($ordered_products, $order_updated_products);
        //     $new_array[] = $all_products;
        // }

        // $data['order_and_updated_products'] = $new_array;
        if(($filter_order_id == ''||$filter_order_id == undefined) &&( $filter_date_start =='' || $filter_date_end==''))

        {
            $results = null;
            $customer_total =0;
     
        }
        else{
        
        $results = $this->model_report_order_product->getOrderedAndUpdatedProducts($filter_data);
        $customer_total =count($results);
        }

            //    echo  print_R($results);die; 

        $data['order_and_updated_products'] = $results;

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

        $data['column_vendor_product_id'] = $this->language->get('column_vendor_product_id');
        $data['column_product_name'] = $this->language->get('column_product_name');
        $data['column_uom'] = $this->language->get('column_uom');
        $data['column_customer_ordred_quantity'] = $this->language->get('column_customer_ordred_quantity');
        $data['column_updated_quantity'] = $this->language->get('column_updated_quantity');

        $data['column_order_id'] = $this->language->get('column_order_id');
        $data['column_products'] = $this->language->get('column_products');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_action'] = $this->language->get('column_action');

        $data['entry_date_start'] = $this->language->get('entry_date_start');
        $data['entry_date_end'] = $this->language->get('entry_date_end');
        $data['entry_status'] = $this->language->get('entry_status');

        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_show_filter'] = $this->language->get('button_show_filter');
        $data['button_hide_filter'] = $this->language->get('button_hide_filter');

        $data['token'] = $this->session->data['token'];

        $url = '';

        if (isset($this->request->get['filter_date_start'])) {
            $url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
        }

        if (isset($this->request->get['filter_date_end'])) {
            $url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
        }

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        $pagination = new Pagination();
        $pagination->total = $customer_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('report/order_and_updated_product', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($customer_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($customer_total - $this->config->get('config_limit_admin'))) ? $customer_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $customer_total, ceil($customer_total / $this->config->get('config_limit_admin')));

        $data['filter_date_start'] = $filter_date_start;
        $data['filter_date_end'] = $filter_date_end;
        $data['filter_order_id'] = $filter_order_id;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('report/order_and_updated_product.tpl', $data));
    }

     

    public function excel() {
        $this->load->language('report/customer_order');

        $this->document->setTitle($this->language->get('heading_title'));

        if (isset($this->request->get['filter_date_start'])) {
            $filter_date_start = $this->request->get['filter_date_start'];
        } else {
            $filter_date_start = '1990-01-01';
        }

        if (isset($this->request->get['filter_date_end'])) {
            $filter_date_end = $this->request->get['filter_date_end'];
        } else {
            $filter_date_end = date('Y-m-d');
        }

        if (isset($this->request->get['filter_order_status_id'])) {
            $filter_order_status_id = $this->request->get['filter_order_status_id'];
        } else {
            $filter_order_status_id = 0;
        }



        $filter_data = [
            'filter_date_start' => $filter_date_start,
            'filter_date_end' => $filter_date_end,
            'filter_order_status_id' => $filter_order_status_id,
        ];

        $this->load->model('report/excel');
        $this->model_report_excel->download_customer_order_excel($filter_data);
    }

    public function custom_array_merge(&$array1, &$array2) {
        $result = array();
        foreach ($array1 as $key_1 => &$value_1) {
            // if($value['name'])
            foreach ($array2 as $key_1 => $value_2) {
                if ($value_1['order_id'] == $value_2['order_id'] && $value_1['product_id'] == $value_2['product_id']) {
                    $result[] = array_merge($value_1, $value_2);
                } else {
                    
                }
            }
        }
        return $result;
    }

}
