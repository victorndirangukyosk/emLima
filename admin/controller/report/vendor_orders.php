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

    public function index() {
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
            foreach ($totals as $total) {
                if ('sub_total' == $total['code']) {
                    $sub_total = $total['value'];
                    //break;
                }
                if ('total' == $total['code']) {
                    $total = $total['value'];
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

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('report/vendor_orders.tpl', $data));
    }

    public function consolidatedOrderSheet() { 


        if (isset($this->request->get['filter_delivery_date'])) {
            $deliveryDate = $this->request->get['filter_delivery_date'];


            $filter_data = [
                'filter_delivery_date' => $deliveryDate,
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

            $filter_data = [
                'filter_order_day' => $filter_order_day,
                'filter_order_status' => $filter_order_status,
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
            $deliveryDate = date("Y-m-d");
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
            'filter_order_id' => $order_id
        ];

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

}
