<?php

class ControllerReportVendorOrders extends Controller {

    public function getUserByName($name) {
        if ($name) {
            $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . "user` u WHERE CONCAT(u.firstname,' ',u.lastname) LIKE '" . $this->db->escape($name) . "%'");

            return $query->row['user_id'];
        }
    }

    public function getStoreIdByName($name) {
        if ($name) {
            $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . "store` WHERE name LIKE '" . $this->db->escape($name) . "%'");

            return $query->row['store_id'];
        }
    }

    public function excel() {
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

        if (isset($this->request->get['filter_order_status_id'])) {
            $filter_order_status_id = $this->request->get['filter_order_status_id'];
        } else {
            $filter_order_status_id = 0;
        }

        if (isset($this->request->get['filter_city'])) {
            $filter_city = $this->request->get['filter_city'];
        } else {
            $filter_city = '';
        }

        if (isset($this->request->get['filter_vendor'])) {
            $filter_vendor = $this->request->get['filter_vendor'];
        } else {
            $filter_vendor = '';
        }

        if (isset($this->request->get['filter_store'])) {
            $filter_store = $this->request->get['filter_store'];
        } else {
            $filter_store = '';
        }

        $data = [
            'filter_city' => $filter_city,
            'filter_vendor' => $this->getUserByName($filter_vendor),
            'filter_date_start' => $filter_date_start,
            'filter_date_end' => $filter_date_end,
            'filter_order_status_id' => $filter_order_status_id,
            'filter_store' => $this->getStoreIdByName($filter_store),
            'filter_store_name' => $filter_store,
        ];

        $this->load->model('report/excel');
        $this->model_report_excel->download_report_vendor_orders_excel($data);
    }

    public function downloadorders() {
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

        if (isset($this->request->get['filter_order_from_id'])) {
            $filter_order_from_id = $this->request->get['filter_order_from_id'];
        } else {
            $filter_order_from_id = null;
        }

        if (isset($this->request->get['filter_order_to_id'])) {
            $filter_order_to_id = $this->request->get['filter_order_to_id'];
        } else {
            $filter_order_to_id = null;
        }


        if (isset($this->request->get['filter_company'])) {
            $filter_company = $this->request->get['filter_company'];
        } else {
            $filter_company = null;
        }

        if (isset($this->request->get['filter_customer'])) {
            $filter_customer = $this->request->get['filter_customer'];
        } else {
            $filter_customer = null;
        }

        if (isset($this->request->get['filter_vendor'])) {
            $filter_vendor = $this->request->get['filter_vendor'];
        } else {
            $filter_vendor = null;
        }

        if (isset($this->request->get['filter_store_name'])) {
            $filter_store_name = $this->request->get['filter_store_name'];
        } else {
            $filter_store_name = null;
        }

        if (isset($this->request->get['filter_delivery_method'])) {
            $filter_delivery_method = $this->request->get['filter_delivery_method'];
        } else {
            $filter_delivery_method = null;
        }

        if (isset($this->request->get['filter_delivery_date'])) {
            $filter_delivery_date = $this->request->get['filter_delivery_date'];
        } else {
            $filter_delivery_date = null;
        }

        if (isset($this->request->get['filter_delivery_time_slot'])) {
            $filter_delivery_time_slot = $this->request->get['filter_delivery_time_slot'];
        } else {
            $filter_delivery_time_slot = null;
        }

        if (isset($this->request->get['filter_payment'])) {
            $filter_payment = $this->request->get['filter_payment'];
        } else {
            $filter_payment = null;
        }
        
        if (isset($this->request->get['filter_paid'])) {
            $filter_paid = $this->request->get['filter_paid'];
        } else {
            $filter_paid = null;
        }

        if (isset($this->request->get['filter_order_status'])) {
            $filter_order_status = $this->request->get['filter_order_status'];
        } else {
            $filter_order_status = null;
        }

        if (isset($this->request->get['filter_order_type'])) {
            $filter_order_type = $this->request->get['filter_order_type'];
        } else {
            $filter_order_type = null;
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

        if (isset($this->request->get['filter_date_added_end'])) {
            $filter_date_added_end = $this->request->get['filter_date_added_end'];
        } else {
            $filter_date_added_end = null;
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $filter_date_modified = $this->request->get['filter_date_modified'];
        } else {
            $filter_date_modified = null;
        }

        if (isset($this->request->get['selected_order_id'])) {
            $orders = $this->request->get['selected_order_id'];
        } else {
            $orders = null;
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

        $filter_data = [
            'filter_city' => $filter_city,
            'filter_order_id' => $filter_order_id,
            'filter_order_from_id' => $filter_order_from_id,
            'filter_order_to_id' => $filter_order_to_id,
            'filter_customer' => $filter_customer,
            'filter_company' => $filter_company,
            'filter_vendor' => $this->getUserByName($filter_vendor),
            'filter_store_name' => $filter_store_name,
            'filter_delivery_method' => $filter_delivery_method,
            'filter_delivery_date' => $filter_delivery_date,
            'filter_delivery_time_slot' => $filter_delivery_time_slot,
            'filter_payment' => $filter_payment,
            'filter_paid' => $filter_paid,
            'filter_order_status' => $filter_order_status,
            'filter_order_type' => $filter_order_type,
            'filter_total' => $filter_total,
            'filter_date_added' => $filter_date_added,
            'filter_date_added_end' => $filter_date_added_end,
            'filter_date_modified' => $filter_date_modified,
            'filter_monthyear_added' => $this->request->get['filter_monthyear_added'],
            'sort' => $sort,
            'order' => $order,
            'filter_orders' => $orders,
                /* 'start' => ($page - 1) * $this->config->get('config_limit_admin'),
                  'limit' => $this->config->get('config_limit_admin'), */
        ];

        $this->load->model('report/excel');
        $this->model_report_excel->download_orders_excel($filter_data);
    }
    
    public function downloadpezeshaordersreceivables() {
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

        if (isset($this->request->get['filter_order_from_id'])) {
            $filter_order_from_id = $this->request->get['filter_order_from_id'];
        } else {
            $filter_order_from_id = null;
        }

        if (isset($this->request->get['filter_order_to_id'])) {
            $filter_order_to_id = $this->request->get['filter_order_to_id'];
        } else {
            $filter_order_to_id = null;
        }


        if (isset($this->request->get['filter_company'])) {
            $filter_company = $this->request->get['filter_company'];
        } else {
            $filter_company = null;
        }

        if (isset($this->request->get['filter_customer'])) {
            $filter_customer = $this->request->get['filter_customer'];
        } else {
            $filter_customer = null;
        }

        if (isset($this->request->get['filter_company_parent'])) {
            $filter_company_parent = $this->request->get['filter_company_parent'];
        } else {
            $filter_company_parent = null;
        }


        if (isset($this->request->get['filter_company_parent_id'])) {
            $filter_company_parent_id = $this->request->get['filter_company_parent_id'];
        } else {
            $filter_company_parent_id = null;
        }

        if (isset($this->request->get['filter_vendor'])) {
            $filter_vendor = $this->request->get['filter_vendor'];
        } else {
            $filter_vendor = null;
        }

        if (isset($this->request->get['filter_store_name'])) {
            $filter_store_name = $this->request->get['filter_store_name'];
        } else {
            $filter_store_name = null;
        }

        if (isset($this->request->get['filter_delivery_method'])) {
            $filter_delivery_method = $this->request->get['filter_delivery_method'];
        } else {
            $filter_delivery_method = null;
        }

        if (isset($this->request->get['filter_delivery_date'])) {
            $filter_delivery_date = $this->request->get['filter_delivery_date'];
        } else {
            $filter_delivery_date = null;
        }

        if (isset($this->request->get['filter_delivery_time_slot'])) {
            $filter_delivery_time_slot = $this->request->get['filter_delivery_time_slot'];
        } else {
            $filter_delivery_time_slot = null;
        }

        if (isset($this->request->get['filter_payment'])) {
            $filter_payment = $this->request->get['filter_payment'];
        } else {
            $filter_payment = null;
        }

        if (isset($this->request->get['filter_order_status'])) {
            $filter_order_status = $this->request->get['filter_order_status'];
        } else {
            $filter_order_status = null;
        }

        if (isset($this->request->get['filter_order_type'])) {
            $filter_order_type = $this->request->get['filter_order_type'];
        } else {
            $filter_order_type = null;
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

        if (isset($this->request->get['filter_date_added_end'])) {
            $filter_date_added_end = $this->request->get['filter_date_added_end'];
        } else {
            $filter_date_added_end = null;
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $filter_date_modified = $this->request->get['filter_date_modified'];
        } else {
            $filter_date_modified = null;
        }

        if (isset($this->request->get['selected_order_id'])) {
            $orders = $this->request->get['selected_order_id'];
        } else {
            $orders = null;
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

        $filter_data = [
            'filter_city' => $filter_city,
            'filter_order_id' => $filter_order_id,
            'filter_order_from_id' => $filter_order_from_id,
            'filter_order_to_id' => $filter_order_to_id,
            'filter_customer' => $filter_customer,
            'filter_company' => $filter_company,
            'filter_company_parent' => $filter_company_parent,
            'filter_company_parent_id' => $filter_company_parent_id,
            'filter_vendor' => $this->getUserByName($filter_vendor),
            'filter_store_name' => $filter_store_name,
            'filter_delivery_method' => $filter_delivery_method,
            'filter_delivery_date' => $filter_delivery_date,
            'filter_delivery_time_slot' => $filter_delivery_time_slot,
            'filter_payment' => $filter_payment,
            'filter_order_status' => $filter_order_status,
            'filter_order_type' => $filter_order_type,
            'filter_total' => $filter_total,
            'filter_date_added' => $filter_date_added,
            'filter_date_added_end' => $filter_date_added_end,
            'filter_date_modified' => $filter_date_modified,
            'filter_monthyear_added' => $this->request->get['filter_monthyear_added'],
            'sort' => $sort,
            'order' => $order,
            'filter_orders' => $orders,
                /* 'start' => ($page - 1) * $this->config->get('config_limit_admin'),
                  'limit' => $this->config->get('config_limit_admin'), */
        ];

        $this->load->model('report/excel');
        $this->model_report_excel->download_pezesha_orders_receivables_excel($filter_data);
    }

    public function downloadordersstickers() {
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

        if (isset($this->request->get['filter_order_from_id'])) {
            $filter_order_from_id = $this->request->get['filter_order_from_id'];
        } else {
            $filter_order_from_id = null;
        }

        if (isset($this->request->get['filter_order_to_id'])) {
            $filter_order_to_id = $this->request->get['filter_order_to_id'];
        } else {
            $filter_order_to_id = null;
        }


        if (isset($this->request->get['filter_company'])) {
            $filter_company = $this->request->get['filter_company'];
        } else {
            $filter_company = null;
        }

        if (isset($this->request->get['filter_customer'])) {
            $filter_customer = $this->request->get['filter_customer'];
        } else {
            $filter_customer = null;
        }

        if (isset($this->request->get['filter_vendor'])) {
            $filter_vendor = $this->request->get['filter_vendor'];
        } else {
            $filter_vendor = null;
        }

        if (isset($this->request->get['filter_store_name'])) {
            $filter_store_name = $this->request->get['filter_store_name'];
        } else {
            $filter_store_name = null;
        }

        if (isset($this->request->get['filter_delivery_method'])) {
            $filter_delivery_method = $this->request->get['filter_delivery_method'];
        } else {
            $filter_delivery_method = null;
        }

        if (isset($this->request->get['filter_delivery_date'])) {
            $filter_delivery_date = $this->request->get['filter_delivery_date'];
        } else {
            $filter_delivery_date = null;
        }

        if (isset($this->request->get['filter_delivery_time_slot'])) {
            $filter_delivery_time_slot = $this->request->get['filter_delivery_time_slot'];
        } else {
            $filter_delivery_time_slot = null;
        }

        if (isset($this->request->get['filter_payment'])) {
            $filter_payment = $this->request->get['filter_payment'];
        } else {
            $filter_payment = null;
        }
        
        if (isset($this->request->get['filter_paid'])) {
            $filter_paid = $this->request->get['filter_paid'];
        } else {
            $filter_paid = null;
        }

        if (isset($this->request->get['filter_order_status'])) {
            $filter_order_status = $this->request->get['filter_order_status'];
        } else {
            $filter_order_status = null;
        }

        if (isset($this->request->get['filter_order_type'])) {
            $filter_order_type = $this->request->get['filter_order_type'];
        } else {
            $filter_order_type = null;
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

        if (isset($this->request->get['filter_date_added_end'])) {
            $filter_date_added_end = $this->request->get['filter_date_added_end'];
        } else {
            $filter_date_added_end = null;
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $filter_date_modified = $this->request->get['filter_date_modified'];
        } else {
            $filter_date_modified = null;
        }

        if (isset($this->request->get['selected_order_id'])) {
            $orders = $this->request->get['selected_order_id'];
        } else {
            $orders = null;
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

        $filter_data = [
            'filter_city' => $filter_city,
            'filter_order_id' => $filter_order_id,
            'filter_order_from_id' => $filter_order_from_id,
            'filter_order_to_id' => $filter_order_to_id,
            'filter_customer' => $filter_customer,
            'filter_company' => $filter_company,
            'filter_vendor' => $this->getUserByName($filter_vendor),
            'filter_store_name' => $filter_store_name,
            'filter_delivery_method' => $filter_delivery_method,
            'filter_delivery_date' => $filter_delivery_date,
            'filter_delivery_time_slot' => $filter_delivery_time_slot,
            'filter_payment' => $filter_payment,
            'filter_paid' => $filter_paid,
            'filter_order_status' => $filter_order_status,
            'filter_order_type' => $filter_order_type,
            'filter_total' => $filter_total,
            'filter_date_added' => $filter_date_added,
            'filter_date_added_end' => $filter_date_added_end,
            'filter_date_modified' => $filter_date_modified,
            'filter_monthyear_added' => $this->request->get['filter_monthyear_added'],
            'sort' => $sort,
            'order' => $order,
            'filter_orders' => $orders,
        ];

        $this->load->model('sale/order');
        $rows['orders'] = $this->model_sale_order->getOrders($filter_data);
        try {
            require_once DIR_ROOT . '/vendor/autoload.php';
            $pdf = new \mikehaertl\wkhtmlto\Pdf;
            $template = $this->load->view('sale/order_sticker_pdf.tpl', $rows);
            $pdf->addPage($template);
            if (!$pdf->send("KwikBasket Orders.pdf")) {
                $error = $pdf->getError();
                echo $error;
                die;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function index() {
        ini_set('display_errors', "on");
        ini_set('error_reporting', E_ALL);
        $this->language->load('report/vendor_orders');

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

        if (isset($this->request->get['filter_order_status_id'])) {
            $filter_order_status_id = $this->request->get['filter_order_status_id'];
        } else {
            $filter_order_status_id = 0;
        }

        if (isset($this->request->get['filter_city'])) {
            $filter_city = $this->request->get['filter_city'];
        } else {
            $filter_city = '';
        }

        if (isset($this->request->get['filter_vendor'])) {
            $filter_vendor = $this->request->get['filter_vendor'];
        } else {
            $filter_vendor = '';
        }

        if (isset($this->request->get['filter_vendor_id'])) {
            $filter_vendor_id = $this->request->get['filter_vendor_id'];
        } else {
            $filter_vendor_id = '';
        }

        if (isset($this->request->get['filter_store'])) {
            $filter_store = $this->request->get['filter_store'];
        } else {
            $filter_store = '';
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = '';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = '';
        }

        if (isset($this->request->get['filter_group'])) {
            $filter_group = $this->request->get['filter_group'];
        } else {
            $filter_group = 'week';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['filter_city'])) {
            $url .= '&filter_city=' . $this->request->get['filter_city'];
        }

        if (isset($this->request->get['filter_vendor'])) {
            $url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
        }

        if (isset($this->request->get['filter_vendor_id'])) {
            $url .= '&filter_vendor_id=' . $this->request->get['filter_vendor_id'];
        }

        if (isset($this->request->get['filter_store'])) {
            $url .= '&filter_store=' . $this->request->get['filter_store'];
        }

        if (isset($this->request->get['filter_date_start'])) {
            $url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
        }

        if (isset($this->request->get['filter_date_end'])) {
            $url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
        }

        if (isset($this->request->get['filter_group'])) {
            $url .= '&filter_group=' . $this->request->get['filter_group'];
        }

        if (isset($this->request->get['filter_order_status_id'])) {
            $url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false,
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('report/vendor_orders', 'token=' . $this->session->data['token'] . $url, 'SSL'),
            'separator' => ' :: ',
        ];

        $this->load->model('report/sale');
        $this->load->model('sale/order');

        $data['vendor_orders'] = [];

        $filter_data = [
            'filter_city' => $filter_city,
            'filter_vendor' => $this->getUserByName($filter_vendor),
            'filter_date_start' => $filter_date_start,
            'filter_date_end' => $filter_date_end,
            'filter_order_status_id' => $filter_order_status_id,
            'filter_store' => $this->getStoreIdByName($filter_store),
            'filter_group' => $filter_group,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        //echo "<pre>";print_r($filter_data);die;

        $order_total = $this->model_report_sale->getTotalReportVendorOrders($filter_data);
        $results = $this->model_report_sale->getReportVendorOrders($filter_data);
        //$order_total = count($results);
        //echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            $products_qty = 0;

            if ($this->model_sale_order->hasRealOrderProducts($result['order_id'])) {
                $products_qty = $this->model_sale_order->getRealOrderProductsItems($result['order_id']);
            } else {
                $products_qty = $this->model_sale_order->getOrderProductsItems($result['order_id']);
            }

            $sub_total = 0;
            $total = 0;

            $totals = $this->model_sale_order->getOrderTotals($result['order_id']);

            //echo "<pre>";print_r($totals);die;
            foreach ($totals as $tot) {
                if ('sub_total' == $tot['code']) {
                    $sub_total = $tot['value'];
                    //break;
                }
                if ('total' == $tot['code']) {
                    $total = $tot['value'];
                    break;
                }
            }

            //echo "<pre>";print_r($products);die;

            $data['vendor_orders'][] = [
                'delivery_date' => date($this->language->get('date_format_short'), strtotime($result['delivery_date'])),
                'order_id' => $result['order_id'],
                'products' => $products_qty,
                'subtotal' => $this->currency->format($sub_total),
                'total' => $this->currency->format($total),
                    //'total' => $this->currency->format($result['total']),
            ];
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_all_status'] = $this->language->get('text_all_status');

        $data['text_list'] = $this->language->get('text_list');
        $data['button_show_filter'] = $this->language->get('button_show_filter');
        $data['button_hide_filter'] = $this->language->get('button_hide_filter');

        $data['column_date_start'] = $this->language->get('column_date_start');
        $data['column_date_end'] = $this->language->get('column_date_end');

        $data['column_delivery_date'] = $this->language->get('column_delivery_date');

        $data['column_orders'] = $this->language->get('column_orders');
        $data['column_products'] = $this->language->get('column_products');
        $data['column_tax'] = $this->language->get('column_tax');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_subtotal'] = $this->language->get('column_subtotal');
        $data['column_vendor'] = $this->language->get('column_vendor');

        $data['entry_date_start'] = $this->language->get('entry_date_start');
        $data['entry_date_end'] = $this->language->get('entry_date_end');
        $data['entry_group'] = $this->language->get('entry_group');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_city'] = $this->language->get('entry_city');
        $data['entry_vendor'] = $this->language->get('entry_vendor');
        $data['entry_store_name'] = $this->language->get('entry_store_name');

        $data['button_filter'] = $this->language->get('button_filter');

        $data['token'] = $this->session->data['token'];

        $this->load->model('localisation/order_status');

        $data['groups'] = [];

        $data['groups'][] = [
            'text' => $this->language->get('text_year'),
            'value' => 'year',
        ];

        $data['groups'][] = [
            'text' => $this->language->get('text_month'),
            'value' => 'month',
        ];

        $data['groups'][] = [
            'text' => $this->language->get('text_week'),
            'value' => 'week',
        ];

        $data['groups'][] = [
            'text' => $this->language->get('text_day'),
            'value' => 'day',
        ];

        $url = '';

        if (isset($this->request->get['filter_city'])) {
            $url .= '&filter_city=' . $this->request->get['filter_city'];
        }

        if (isset($this->request->get['filter_vendor'])) {
            $url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
        }

        if (isset($this->request->get['filter_vendor_id'])) {
            $url .= '&filter_vendor_id=' . $this->request->get['filter_vendor_id'];
        }

        if (isset($this->request->get['filter_store'])) {
            $url .= '&filter_store=' . $this->request->get['filter_store'];
        }

        if (isset($this->request->get['filter_date_start'])) {
            $url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
        }

        if (isset($this->request->get['filter_order_status_id'])) {
            $url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
        }

        if (isset($this->request->get['filter_date_end'])) {
            $url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
        }

        if (isset($this->request->get['filter_group'])) {
            $url .= '&filter_group=' . $this->request->get['filter_group'];
        }

        if ('ASC' == $order) {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['sort'])) {
            $data['sort'] = $this->request->get['sort'];
        } else {
            $data['sort'] = 'total';
        }

        if (isset($this->request->get['order'])) {
            $data['order'] = $this->request->get['order'];
        } else {
            $data['order'] = 'DESC';
        }

        $data['sort_orders'] = $this->url->link('report/vendor_orders', 'token=' . $this->session->data['token'] . '&sort=orders' . $url, 'SSL');
        $data['sort_products'] = $this->url->link('report/vendor_orders', 'token=' . $this->session->data['token'] . '&sort=products' . $url, 'SSL');
        $data['sort_total'] = $this->url->link('report/vendor_orders', 'token=' . $this->session->data['token'] . '&sort=total' . $url, 'SSL');
        $data['sort_subtotal'] = $this->url->link('report/vendor_orders', 'token=' . $this->session->data['token'] . '&sort=subtotal' . $url, 'SSL');

        $pagination = new Pagination();
        $pagination->total = $order_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('report/vendor_orders', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

        $data['filter_city'] = $filter_city;
        $data['filter_vendor'] = $filter_vendor;
        $data['filter_store'] = $filter_store;
        $data['filter_date_start'] = $filter_date_start;
        $data['filter_date_end'] = $filter_date_end;
        $data['filter_order_status_id'] = $filter_order_status_id;
        $data['filter_group'] = $filter_group;

        $this->load->model('localisation/order_status');
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
        $this->load->model('setting/store');
        $deliveryTimeslots = $this->model_setting_store->getDeliveryTimeslots(75);
        $data['time_slots'] = $deliveryTimeslots;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('report/vendor_orders.tpl', $data));
    }

    public function consolidatedOrderSheet() {


        if (isset($this->request->get['filter_delivery_date'])) {
            $deliveryDate = $this->request->get['filter_delivery_date'];
            $deliveryTime = isset($this->request->get['filter_delivery_time_slot']) && $this->request->get['filter_delivery_time_slot'] != NULL ? $this->request->get['filter_delivery_time_slot'] : '';

            $filter_data = [
                'filter_delivery_date' => $deliveryDate,
                'filter_delivery_time' => $deliveryTime,
            ];
            $this->load->model('sale/order');
            // $results = $this->model_sale_order->getOrders($filter_data);
            // $results = $this->model_sale_order->getNonCancelledOrderswithPending($filter_data);
            $results = $this->model_sale_order->getOrderswithProcessing($filter_data);
        } else {
            $deliveryDate = null;
        }
        //below if ondition for fast orders, not required in sheduler
        if (isset($this->request->get['filter_order_day'])) {
            $filter_order_day = $this->request->get['filter_order_day'];
            if (isset($this->request->get['filter_order_status'])) {
                $filter_order_status = $this->request->get['filter_order_status'];
            } else {
                $filter_order_status = null;
            }


            if (isset($this->request->get['selected_order_id'])) {
                $orders = $this->request->get['selected_order_id'];
            } else {
                $orders = null;
            }

            $filter_data = [
                'filter_order_day' => $filter_order_day,
                'filter_order_status' => $filter_order_status,
                'filter_orders' => $orders
            ];
            $this->load->model('sale/order');

            $results = $this->model_sale_order->getFastOrders($filter_data);
        } else {
            $filter_order_day = null;
        }//end of if 


        $data = [];
        $unconsolidatedProducts = [];

        foreach ($results as $index => $order) {
            $data['orders'][$index] = $order;
            $orderProducts = $this->model_sale_order->getOrderAndRealOrderProducts($data['orders'][$index]['order_id']);
            $data['orders'][$index]['products'] = $orderProducts;

            foreach ($orderProducts as $product) {
                $unconsolidatedProducts[] = [
                    'name' => $product['name'],
                    'unit' => $product['unit'],
                    'quantity' => $product['quantity'],
                    'note' => $product['product_note'],
                    'produce_type' => $product['produce_type'],
                ];
            }
        }

        $consolidatedProducts = [];

        foreach ($unconsolidatedProducts as $product) {
            $productName = $product['name'];
            $productUnit = $product['unit'];
            $productQuantity = $product['quantity'];
            $productNote = isset($product['product_note']) ? $product['product_note'] : '';
            $produceType = $product['produce_type'];

            $consolidatedProductNames = array_column($consolidatedProducts, 'name');
            if (false !== array_search($productName, $consolidatedProductNames)) {
                $indexes = array_keys($consolidatedProductNames, $productName);

                $foundExistingProductWithSimilarUnit = false;
                foreach ($indexes as $index) {
                    if ($productUnit == $consolidatedProducts[$index]['unit']) {
                        if ($consolidatedProducts[$index]['produce_type']) {
                            $produceType = $consolidatedProducts[$index]['produce_type'] . ' / ' . $produceType . ' ';
                        }

                        $consolidatedProducts[$index]['quantity'] += $productQuantity;
                        $consolidatedProducts[$index]['produce_type'] = $produceType;
                        $foundExistingProductWithSimilarUnit = true;
                        break;
                    }
                }

                if (!$foundExistingProductWithSimilarUnit) {
                    $consolidatedProducts[] = [
                        'name' => $productName,
                        'unit' => $productUnit,
                        'quantity' => $productQuantity,
                        'note' => $productNote,
                        'produce_type' => $produceType,
                    ];
                }
            } else {
                $consolidatedProducts[] = [
                    'name' => $productName,
                    'unit' => $productUnit,
                    'quantity' => $productQuantity,
                    'note' => $productNote,
                    'produce_type' => $produceType,
                ];
            }
        }
        //echo "<pre>";print_r($consolidatedProducts);die;

        $data['products'] = $consolidatedProducts;
        // echo "<pre>";print_r($data);die;

        $this->load->model('report/excel');
        $this->model_report_excel->download_consolidated_order_sheet_excel($data);
    }

    public function consolidatedOrderSheetForOrders() {


        if (isset($this->request->get['filter_delivery_date'])) {
            $deliveryDate = $this->request->get['filter_delivery_date'];
        } else {//consolidated orders data should not be more , so get delivery date
            // $deliveryDate = date("Y-m-d");
        }


        if (isset($this->request->get['filter_order_status'])) {
            $order_status = $this->request->get['filter_order_status'];
        } else {
            $order_status = null;
        }

        if (isset($this->request->get['filter_company'])) {
            $company = $this->request->get['filter_company'];
        } else {
            $company = null;
        }

        if (isset($this->request->get['filter_customer'])) {
            $customer = $this->request->get['filter_customer'];
        } else {
            $customer = null;
        }

        if (isset($this->request->get['filter_total'])) {
            $total = $this->request->get['filter_total'];
        } else {
            $total = null;
        }

        if (isset($this->request->get['filter_delivery_method'])) {
            $delivery_method = $this->request->get['filter_delivery_method'];
        } else {
            $delivery_method = null;
        }

        if (isset($this->request->get['filter_payment'])) {
            $payment = $this->request->get['filter_payment'];
        } else {
            $payment = null;
        }

        if (isset($this->request->get['filter_order_type'])) {
            $order_type = $this->request->get['filter_order_type'];
        } else {
            $order_type = null;
        }

        if (isset($this->request->get['filter_order_from_id'])) {
            $order_from_id = $this->request->get['filter_order_from_id'];
        } else {
            $order_from_id = null;
        }

        if (isset($this->request->get['filter_order_to_id'])) {
            $order_to_id = $this->request->get['filter_order_to_id'];
        } else {
            $order_to_id = null;
        }

        if (isset($this->request->get['filter_date_added'])) {
            $date_added = $this->request->get['filter_date_added'];
        } else {
            $date_added = null;
        }

        if (isset($this->request->get['filter_date_added_end'])) {
            $date_added_end = $this->request->get['filter_date_added_end'];
        } else {
            $date_added_end = null;
        }

        if (isset($this->request->get['filter_order_id'])) {
            $order_id = $this->request->get['filter_order_id'];
        } else {
            $order_id = null;
        }

        if (isset($this->request->get['filter_delivery_time_slot'])) {
            $delivery_time_slot = $this->request->get['filter_delivery_time_slot'];
        } else {
            $delivery_time_slot = null;
        }

        if (isset($this->request->get['selected_order_id'])) {
            $orders = $this->request->get['selected_order_id'];
        } else {
            $orders = null;
        }
        $filter_data = [
            'filter_delivery_date' => $deliveryDate,
            'filter_order_status' => $order_status,
            'filter_company' => $company,
            'filter_customer' => $customer,
            'filter_total' => $total,
            'filter_delivery_method' => $delivery_method,
            'filter_payment' => $payment,
            'filter_order_type' => $order_type,
            'filter_order_type' => $order_type,
            'filter_order_from_id' => $order_from_id,
            'filter_order_to_id' => $order_to_id,
            'filter_date_added' => $date_added,
            'filter_date_added_end' => $date_added_end,
            'filter_order_id' => $order_id,
            'filter_orders' => $orders,
            'filter_delivery_time' => $delivery_time_slot,
        ];

        // echo "<pre>";print_r($filter_data);die;

        $this->load->model('sale/order');
        // $results = $this->model_sale_order->getOrders($filter_data);
        // $results = $this->model_sale_order->getNonCancelledOrderswithPending($filter_data);
        $results = $this->model_sale_order->getOrderswithProcessing($filter_data);

        // echo "<pre>";print_r($results);die;

        $data = [];
        $unconsolidatedProducts = [];

        foreach ($results as $index => $order) {
            $data['orders'][$index] = $order;
            $orderProducts = $this->model_sale_order->getOrderAndRealOrderProducts($data['orders'][$index]['order_id']);
            $data['orders'][$index]['products'] = $orderProducts;

            foreach ($orderProducts as $product) {
                $unconsolidatedProducts[] = [
                    'name' => $product['name'],
                    'unit' => $product['unit'],
                    'quantity' => $product['quantity'],
                    'note' => $product['product_note'],
                    'produce_type' => $product['produce_type'],
                ];
            }
        }

        $consolidatedProducts = [];

        foreach ($unconsolidatedProducts as $product) {
            $productName = $product['name'];
            $productUnit = $product['unit'];
            $productQuantity = $product['quantity'];
            $productNote = array_key_exists('product_note', $product) && isset($product['product_note']) && $product['product_note'] != NULL ? $product['product_note'] : '';
            $produceType = $product['produce_type'];

            $consolidatedProductNames = array_column($consolidatedProducts, 'name');
            if (false !== array_search($productName, $consolidatedProductNames)) {
                $indexes = array_keys($consolidatedProductNames, $productName);

                $foundExistingProductWithSimilarUnit = false;
                foreach ($indexes as $index) {
                    if ($productUnit == $consolidatedProducts[$index]['unit']) {
                        if ($consolidatedProducts[$index]['produce_type']) {
                            $produceType = $consolidatedProducts[$index]['produce_type'] . ' / ' . $produceType . ' ';
                        }

                        $consolidatedProducts[$index]['quantity'] += $productQuantity;
                        $consolidatedProducts[$index]['produce_type'] = $produceType;
                        $foundExistingProductWithSimilarUnit = true;
                        break;
                    }
                }

                if (!$foundExistingProductWithSimilarUnit) {
                    $consolidatedProducts[] = [
                        'name' => $productName,
                        'unit' => $productUnit,
                        'quantity' => $productQuantity,
                        'note' => $productNote,
                        'produce_type' => $produceType,
                    ];
                }
            } else {
                $consolidatedProducts[] = [
                    'name' => $productName,
                    'unit' => $productUnit,
                    'quantity' => $productQuantity,
                    'note' => $productNote,
                    'produce_type' => $produceType,
                ];
            }
        }
        //echo "<pre>";print_r($consolidatedProducts);die;

        $data['products'] = $consolidatedProducts;
        // echo "<pre>";print_r($data);die;

        $this->load->model('report/excel');
        $this->model_report_excel->download_consolidated_order_sheet_excel($data);
    }

    public function consolidatedOrderSheetGroupByCategory() {


        if (isset($this->request->get['filter_delivery_date'])) {
            $deliveryDate = $this->request->get['filter_delivery_date'];
            $deliveryTime = isset($this->request->get['filter_delivery_time_slot']) && $this->request->get['filter_delivery_time_slot'] != NULL ? $this->request->get['filter_delivery_time_slot'] : '';

            $filter_data = [
                'filter_delivery_date' => $deliveryDate,
                'filter_delivery_time' => $deliveryTime,
            ];
            $this->load->model('sale/order');
            // $results = $this->model_sale_order->getOrders($filter_data);
            // $results = $this->model_sale_order->getNonCancelledOrderswithPending($filter_data);
            $results = $this->model_sale_order->getOrderswithProcessing($filter_data);
        } else {
            $deliveryDate = null;
        }
        //below if ondition for fast orders, not required in sheduler
        if (isset($this->request->get['filter_order_day'])) {
            $filter_order_day = $this->request->get['filter_order_day'];
            if (isset($this->request->get['filter_order_status'])) {
                $filter_order_status = $this->request->get['filter_order_status'];
            } else {
                $filter_order_status = null;
            }


            if (isset($this->request->get['selected_order_id'])) {
                $orders = $this->request->get['selected_order_id'];
            } else {
                $orders = null;
            }

            $filter_data = [
                'filter_order_day' => $filter_order_day,
                'filter_order_status' => $filter_order_status,
                'filter_orders' => $orders
            ];
            $this->load->model('sale/order');

            $results = $this->model_sale_order->getFastOrders($filter_data);
        } else {
            $filter_order_day = null;
        }//end of if 


        $data = [];
        $unconsolidatedProducts = [];

        foreach ($results as $index => $order) {
            $data['orders'][$index] = $order;
            $orderProducts = $this->model_sale_order->getOrderAndRealOrderProducts($data['orders'][$index]['order_id']);
            // $data['orders'][$index]['products'] = $orderProducts;
            $orderProductsnew=[];
            $category_ids_order=[];
            foreach ($orderProducts as $product) 
            {
                
                    if($product['general_product_id']==null || $product['general_product_id']==0 || $product['general_product_id']=='' )
                    {
                    $product_category=$this->model_sale_order->getProductCategoryByProductID($product['product_id']);
                    }
                    else{
                        $product_category=$this->model_sale_order->getProductCategoryByGeneralProductID($product['general_product_id']);

                    }

                    if($product_category!=null)
                    {
                        $product['category']=$product_category['category']??'sadasd';
                        $product['category_id']=$product_category['category_id']??0;
                        // echo "<pre>";print_r($product);die;                    
                    }

                    $category_ids_order[] = ['category_id'=>$product['category_id'],'category_name'=>$product['category']];

                    
                    $unconsolidatedProducts[] = [
                        'name' => $product['name'],
                        'unit' => $product['unit'],
                        'quantity' => $product['quantity'],
                        'note' => $product['product_note'],
                        'produce_type' => $product['produce_type'],
                        'product_category' => $product['category'],
                        'product_category_id' => $product['category_id'],
                    ];
                    array_push($orderProductsnew,$product);
                
            }   
            
                // echo "<pre>";print_r($orderProductsnew);die;
            $uniquecategory_ids_order= array_unique($category_ids_order,SORT_REGULAR);

                $data['orders'][$index]['categories'] = $uniquecategory_ids_order;
            
                $data['orders'][$index]['products'] = $orderProductsnew;
            }

        $consolidatedProducts = [];

       
        foreach ($unconsolidatedProducts as $product) {
            $category_ids[] = ['category_id'=>$product['product_category_id'],'category_name'=>$product['product_category']];
            $productName = $product['name'];
            $productUnit = $product['unit'];
            $productQuantity = $product['quantity'];
            $productNote = isset($product['product_note']) ? $product['product_note'] : '';
            $produceType = $product['produce_type'];
            $product_category = $product['product_category'];
            $product_category_id = $product['product_category_id'];

            $consolidatedProductNames = array_column($consolidatedProducts, 'name');
            if (false !== array_search($productName, $consolidatedProductNames)) {
                $indexes = array_keys($consolidatedProductNames, $productName);

                $foundExistingProductWithSimilarUnit = false;
                foreach ($indexes as $index) {
                    if ($productUnit == $consolidatedProducts[$index]['unit']) {
                        if ($consolidatedProducts[$index]['produce_type']) {
                            $produceType = $consolidatedProducts[$index]['produce_type'] . ' / ' . $produceType . ' ';
                        }

                        $consolidatedProducts[$index]['quantity'] += $productQuantity;
                        $consolidatedProducts[$index]['produce_type'] = $produceType;
                        $foundExistingProductWithSimilarUnit = true;
                        break;
                    }
                }

                if (!$foundExistingProductWithSimilarUnit) {
                    $consolidatedProducts[] = [
                        'name' => $productName,
                        'unit' => $productUnit,
                        'quantity' => $productQuantity,
                        'note' => $productNote,
                        'produce_type' => $produceType,
                        'product_category' => $product_category,
                        'product_category_id' => $product_category_id,

                    ];
                }
            } else {
                $consolidatedProducts[] = [
                    'name' => $productName,
                    'unit' => $productUnit,
                    'quantity' => $productQuantity,
                    'note' => $productNote,
                    'produce_type' => $produceType,
                    'product_category' => $product_category,
                    'product_category_id' => $product_category_id,


                ];
            }
        }
        $productCat = array_column($consolidatedProducts, 'product_category_id');
        array_multisort(  $productCat,SORT_ASC,$consolidatedProducts);

        // array_unique(array_column($category_ids, 'category_id'));
        $uniquecategory_ids= array_unique($category_ids,SORT_REGULAR);

        // array_multisort(  $uniquecategory_ids,SORT_ASC);
        // echo "<pre>";print_r($category_ids);
        // echo "<pre>";print_r($uniquecategory_ids);die;



        // echo "<pre>";print_r($data);die;

        $data['uniquecategory_ids'] = $uniquecategory_ids;
        $data['products'] = $consolidatedProducts;
        // echo "<pre>";print_r($data);die;

        $this->load->model('report/excel');
        $this->model_report_excel->download_consolidated_order_sheet_excel_category($data);
    }
}
