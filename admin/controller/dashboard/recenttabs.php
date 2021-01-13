<?php

class ControllerDashboardRecenttabs extends Controller {

    public function index() {
        $this->load->language('dashboard/recenttabs');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_no_results'] = $this->language->get('text_no_results');

        $data['text_products_and_sales'] = $this->language->get('text_products_and_sales');
        $data['text_best_sellers'] = $this->language->get('text_best_sellers');
        $data['text_less_sellers'] = $this->language->get('text_less_sellers');
        $data['text_most_viewed'] = $this->language->get('text_most_viewed');
        $data['text_last_order'] = $this->language->get('text_last_order');

        $data['column_order_id'] = $this->language->get('column_order_id');
        $data['column_customer'] = $this->language->get('column_customer');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_action'] = $this->language->get('column_action');
        $data['column_product_name'] = $this->language->get('column_product_name');
        $data['column_product_id'] = $this->language->get('column_product_id');

        $data['button_view'] = $this->language->get('button_view');
        $data['button_edit'] = $this->language->get('button_edit');

        $data['button_view'] = $this->language->get('button_view');

        $data['token'] = $this->session->data['token'];

        $this->load->model('dashboard/recenttabs');

        // 5 best seller product
        if ($this->user->isVendor()) {
            $results = $this->model_dashboard_recenttabs->getVendorBestSellers();
        } else {
            $results = $this->model_dashboard_recenttabs->getBestSellers();
        }

        //echo "<pre>";print_r($results);die;
        $bestseller = [];
        foreach ($results as $product) {
            if ($this->user->isVendor()) {
                /* $p_store_id = $this->getProductStoreId($product['product_id'],$product['store_id']);

                  if(count($p_store_id) > 0) {
                  $editLink = $this->url->link('catalog/general/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $p_store_id['product_store_id'], 'SSL');
                  } else {

                  $editLink = $this->url->link('catalog/general/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], 'SSL');
                  } */

                $p_id = $product['product_id'];

                $editLink = $this->url->link('catalog/vendor_product/edit', 'token=' . $this->session->data['token'] . '&store_product_id=' . $product['product_id'], 'SSL');
            } else {
                $p_id = $product['general_product_id'];

                $editLink = $this->url->link('catalog/general/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $product['general_product_id'], 'SSL');
            }

            $bestseller[] = [
                'product_id' => $p_id,
                'name' => $product['name'],
                'total' => $product['total'],
                'edit' => $editLink,
            ];
        }

        $data['bestseller'] = $bestseller;

        // // 5 less seller product
        if ($this->user->isVendor()) {
            $results = $this->model_dashboard_recenttabs->getVendorLessSellers();
        } else {
            $results = $this->model_dashboard_recenttabs->getLessSellers();
        }

        $lessseller = [];
        foreach ($results as $product) {
            if ($this->user->isVendor()) {
                /* $p_store_id = $this->getProductStoreId($product['product_id'],$product['store_id']);

                  if(count($p_store_id) > 0) {
                  $editLink = $this->url->link('catalog/general/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $p_store_id['product_store_id'], 'SSL');
                  } else {

                  $editLink = $this->url->link('catalog/general/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], 'SSL');
                  } */

                $p_id = $product['product_id'];

                $editLink = $this->url->link('catalog/vendor_product/edit', 'token=' . $this->session->data['token'] . '&store_product_id=' . $product['product_id'], 'SSL');
            } else {
                $p_id = $product['general_product_id'];

                $editLink = $this->url->link('catalog/general/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $product['general_product_id'], 'SSL');
            }

            $lessseller[] = [
                'product_id' => $p_id,
                'name' => $product['name'],
                'total' => $product['total'],
                'edit' => $editLink,
            ];
        }

        $data['lessseller'] = $lessseller;

        // 5 most viewed product
        if ($this->user->isVendor()) {
            $results = $this->model_dashboard_recenttabs->getVendorMostViewed();
        } else {
            $results = $this->model_dashboard_recenttabs->getMostViewed();
        }

        $viewed = [];
        foreach ($results as $product) {
            $editLink = $this->url->link('catalog/general/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], 'SSL');

            $viewed[] = [
                'product_id' => $product['product_id'],
                'name' => $product['name'],
                'total' => $product['viewed'],
                'edit' => $editLink,
            ];
        }

        $data['viewed'] = $viewed;

        // Last 5 Orders
        $filter_data = [
            'sort' => 'o.date_added',
            'order' => 'DESC',
            'start' => 0,
            'limit' => 10,
        ];

        $this->load->model('sale/order');
        if ($this->user->isAccountManager()) {
        $results = $this->model_sale_order->getOrdersByAccountManager($filter_data);
        } else {
        $results = $this->model_sale_order->getOrders($filter_data);    
        }
        $data['orders'] = [];
        if ($this->user->isAccountManager()) {
            $view = 'sale/accountmanageruserorders/info';
        } else {
            $view = 'sale/order/info';
        }
        foreach ($results as $result) {
            $data['orders'][] = [
                'order_id' => $result['order_id'],
                'customer' => $result['customer'],
                'status' => $result['status'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'total' => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
                'view' => $this->url->link($view, 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'], 'SSL'),
            ];
        }

        return $this->load->view('dashboard/recenttabs.tpl', $data);
    }
    
    public function custom_index() {
        $this->load->language('dashboard/recenttabs');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_no_results'] = $this->language->get('text_no_results');

        $data['text_products_and_sales'] = $this->language->get('text_products_and_sales');
        $data['text_best_sellers'] = $this->language->get('text_best_sellers');
        $data['text_less_sellers'] = $this->language->get('text_less_sellers');
        $data['text_most_viewed'] = $this->language->get('text_most_viewed');
        $data['text_last_order'] = $this->language->get('text_last_order');

        $data['column_order_id'] = $this->language->get('column_order_id');
        $data['column_customer'] = $this->language->get('column_customer');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_action'] = $this->language->get('column_action');
        $data['column_product_name'] = $this->language->get('column_product_name');
        $data['column_product_id'] = $this->language->get('column_product_id');

        $data['button_view'] = $this->language->get('button_view');
        $data['button_edit'] = $this->language->get('button_edit');

        $data['button_view'] = $this->language->get('button_view');

        $data['token'] = $this->session->data['token'];

        $this->load->model('dashboard/recenttabs');

        // 5 best seller product
        if ($this->user->isVendor()) {
            $results = $this->model_dashboard_recenttabs->getVendorBestSellers();
        } else {
            $results = $this->model_dashboard_recenttabs->getBestSellers();
        }

        //echo "<pre>";print_r($results);die;
        $bestseller = [];
        foreach ($results as $product) {
            if ($this->user->isVendor()) {
                /* $p_store_id = $this->getProductStoreId($product['product_id'],$product['store_id']);

                  if(count($p_store_id) > 0) {
                  $editLink = $this->url->link('catalog/general/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $p_store_id['product_store_id'], 'SSL');
                  } else {

                  $editLink = $this->url->link('catalog/general/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], 'SSL');
                  } */

                $p_id = $product['product_id'];

                $editLink = $this->url->link('catalog/vendor_product/edit', 'token=' . $this->session->data['token'] . '&store_product_id=' . $product['product_id'], 'SSL');
            } else {
                $p_id = $product['general_product_id'];

                $editLink = $this->url->link('catalog/general/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $product['general_product_id'], 'SSL');
            }

            $bestseller[] = [
                'product_id' => $p_id,
                'name' => $product['name'],
                'total' => $product['total'],
                'edit' => $editLink,
            ];
        }

        $data['bestseller'] = $bestseller;

        // // 5 less seller product
        if ($this->user->isVendor()) {
            $results = $this->model_dashboard_recenttabs->getVendorLessSellers();
        } else {
            $results = $this->model_dashboard_recenttabs->getLessSellers();
        }

        $lessseller = [];
        foreach ($results as $product) {
            if ($this->user->isVendor()) {
                /* $p_store_id = $this->getProductStoreId($product['product_id'],$product['store_id']);

                  if(count($p_store_id) > 0) {
                  $editLink = $this->url->link('catalog/general/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $p_store_id['product_store_id'], 'SSL');
                  } else {

                  $editLink = $this->url->link('catalog/general/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], 'SSL');
                  } */

                $p_id = $product['product_id'];

                $editLink = $this->url->link('catalog/vendor_product/edit', 'token=' . $this->session->data['token'] . '&store_product_id=' . $product['product_id'], 'SSL');
            } else {
                $p_id = $product['general_product_id'];

                $editLink = $this->url->link('catalog/general/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $product['general_product_id'], 'SSL');
            }

            $lessseller[] = [
                'product_id' => $p_id,
                'name' => $product['name'],
                'total' => $product['total'],
                'edit' => $editLink,
            ];
        }

        $data['lessseller'] = $lessseller;

        // 5 most viewed product
        if ($this->user->isVendor()) {
            $results = $this->model_dashboard_recenttabs->getVendorMostViewed();
        } else {
            $results = $this->model_dashboard_recenttabs->getMostViewed();
        }

        $viewed = [];
        foreach ($results as $product) {
            $editLink = $this->url->link('catalog/general/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], 'SSL');

            $viewed[] = [
                'product_id' => $product['product_id'],
                'name' => $product['name'],
                'total' => $product['viewed'],
                'edit' => $editLink,
            ];
        }

        $data['viewed'] = $viewed;

        // Last 5 Orders
        $filter_data = [
            'sort' => 'o.date_added',
            'order' => 'DESC',
            'start' => 0,
            'limit' => 10,
        ];

        $this->load->model('sale/order');
        if ($this->user->isAccountManager()) {
        $results = $this->model_sale_order->getOrdersByAccountManager($filter_data);
        } else {
        $results = $this->model_sale_order->getOrders($filter_data);    
        }
        $data['orders'] = [];
        if ($this->user->isAccountManager()) {
            $view = 'sale/accountmanageruserorders/info';
        } else {
            $view = 'sale/order/info';
        }
        foreach ($results as $result) {
            $data['orders'][] = [
                'order_id' => $result['order_id'],
                'customer' => $result['customer'],
                'status' => $result['status'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'total' => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
                'view' => $this->url->link($view, 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'], 'SSL'),
            ];
        }

        return $this->load->view('dashboard/custom_recenttabs.tpl', $data);
    }

    public function getProductStoreId($product_id, $store_id) {
        $query = $this->db->query('SELECT * from  ' . DB_PREFIX . 'product_to_store where store_id = ' . (int) $store_id . ' and product_id = ' . $product_id);

        return $query->row;
    }
    
    public function getRecentOrders() {
        // Last 5 Orders
        $filter_data = [
            'sort' => 'o.date_added',
            'order' => 'DESC',
            'start' => 0,
            'limit' => 10,
            'filter_date_added' => isset($this->request->get['start']) ? $this->request->get['start'] : '',
            'filter_date_added_end' => isset($this->request->get['end']) ? $this->request->get['end'] : '',
            'account_manager' => isset($this->request->get['account_manager']) && $this->request->get['account_manager'] > 0 ? $this->request->get['account_manager'] : ''
        ];

        $this->load->model('sale/order');
        
        if ($this->user->isAccountManager() || (isset($this->request->get['account_manager']) && $this->request->get['account_manager'] > 0)) {
            //$results = $this->model_sale_order->getOrdersByAccountManager($filter_data);
            $results = $this->model_sale_order->getOrdersByAccountManagerCustom($filter_data);
        } else {
            $results = $this->model_sale_order->getOrders($filter_data);
        }
        $data['orders'] = [];
        if ($this->user->isAccountManager()) {
            $view = 'sale/accountmanageruserorders/info';
        } else {
            $view = 'sale/order/info';
        }
        foreach ($results as $result) {
            $data['orders'][] = [
                'order_id' => $result['order_id'],
                'customer' => $result['customer'],
                'status' => $result['status'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'total' => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
                'view' => $this->url->link($view, 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'], 'SSL'),
            ];
        }
        
        $json['data'] = $data;
        $this->response->setOutput(json_encode($json));
    }

}
