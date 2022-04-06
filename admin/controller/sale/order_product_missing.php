<?php

class ControllerSaleOrderProductMissing extends Controller {

    private $error = [];

    public function index() {

        $this->load->language('sale/ordered_product');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('sale/order');
        $this->getMissingProductsList();
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'sale/order_product_missing')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        return !$this->error;
    }

    protected function getMissingProductsList() {
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
        if (isset($this->request->get['filter_order_day'])) {
            $url .= '&filter_order_day=' . $this->request->get['filter_order_day'];
        }
        if (isset($this->request->get['filter_delivery_date'])) {
            $filter_delivery_date = $this->request->get['filter_delivery_date'];
        } else {
            $filter_delivery_date = null;
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


        if (isset($this->request->get['filter_order_day'])) {
            $filter_order_day = $this->request->get['filter_order_day'];
        } else {
            $filter_order_day = 'today';
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

        if (isset($this->request->get['filter_city'])) {
            $url .= '&filter_city=' . $this->request->get['filter_city'];
        }

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_order_from_id'])) {
            $url .= '&filter_order_from_id=' . $this->request->get['filter_order_from_id'];
        }


        if (isset($this->request->get['filter_order_to_id'])) {
            $url .= '&filter_order_to_id=' . $this->request->get['filter_order_to_id'];
        }


        if (isset($this->request->get['filter_company'])) {
            $url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_vendor'])) {
            $url .= '&filter_vendor=' . urlencode(html_entity_decode($this->request->get['filter_vendor'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_store_name'])) {
            $url .= '&filter_store_name=' . urlencode(html_entity_decode($this->request->get['filter_store_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_delivery_method'])) {
            $url .= '&filter_delivery_method=' . urlencode(html_entity_decode($this->request->get['filter_delivery_method'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_delivery_date'])) {
            $url .= '&filter_delivery_date=' . urlencode(html_entity_decode($this->request->get['filter_delivery_date'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_payment'])) {
            $url .= '&filter_payment=' . urlencode(html_entity_decode($this->request->get['filter_payment'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
        }


        if (isset($this->request->get['filter_order_day'])) {
            $url .= '&filter_order_day=' . $this->request->get['filter_order_day'];
        }

        if (isset($this->request->get['filter_order_type'])) {
            $url .= '&filter_order_type=' . $this->request->get['filter_order_type'];
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total=' . $this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_added_end'])) {
            $url .= '&filter_date_added_end=' . $this->request->get['filter_date_added_end'];
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
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
            'href' => $this->url->link('sale/order_product_missing', 'token=' . $this->session->data['token'] . $url, 'SSL'),
        ];

// $data['invoice'] = $this->url->link('sale/order/invoice', 'token=' . $this->session->data['token'], 'SSL');
// $data['invoicepdf'] = $this->url->link('sale/order/invoicepdf', 'token=' . $this->session->data['token'], 'SSL');
// // $data['shipping'] = $this->url->link('sale/order/shipping', 'token=' . $this->session->data['token'], 'SSL');
// $data['shipping'] = $this->url->link('sale/order/shippingNote', 'token=' . $this->session->data['token'], 'SSL');
// $data['add'] = $this->url->link('sale/order/add', 'token=' . $this->session->data['token'], 'SSL');
// $data['delivery_sheet'] = $this->url->link('sale/order/consolidatedOrderSheet', 'token=' . $this->session->data['token'], 'SSL');
        $data['orders'] = [];

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
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
            'filter_order_day' => $filter_order_day,
        ];

// echo "<pre>";print_r($filter_data);die; 

        $order_total = $this->model_sale_order->getTotalOrderedProducts($filter_data);

        $results = $this->model_sale_order->getOrderedProducts($filter_data);

//        echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            $sub_total = 0;

// $totals = $this->model_sale_order->getOrderTotals($result['order_id']);
//echo "<pre>";print_r($totals);die;
// foreach ($totals as $total) {
//     if ('sub_total' == $total['code']) {
//         $sub_total = $total['value'];
//         break;
//     }
// }

            if ($this->user->isVendor()) {
                $result['customer'] = strtok($result['firstname'], ' ');
            }

            if ($result['company_name']) {
                $result['company_name'] = ' (' . $result['company_name'] . ')';
            } else {
// $result['company_name'] = "(NA)";
            }

            $this->load->model('localisation/order_status');
            $data['orders'][] = [
                'order_id' => $result['order_id'],
                'customer' => $result['customer'],
                'company_name' => $result['company_name'],
                'status' => $result['status'],
                'product_id' => $result['product_id'],
                'general_product_id' => $result['general_product_id'],
                'name' => $result['name'],
                'unit' => $result['unit'],
                'quantity' => $result['quantity'],
                'total' => $result['total'],
                'price' => $result['price'],
                'tax' => $result['tax'],
                'addmissingproduct' => $this->url->link('sale/order_product_missing/addtomissingproduct', 'token=' . $this->session->data['token'] . $url, 'SSL'),
                'order_product_id' => $result['order_product_id'],
            ];
        }

        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_vendor'] = $this->language->get('text_vendor');
        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_missing'] = $this->language->get('text_missing');

        $data['column_order_id'] = $this->language->get('column_order_id');
        $data['column_customer'] = $this->language->get('column_customer');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_city'] = $this->language->get('column_city');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_date_modified'] = $this->language->get('column_date_modified');
        $data['column_action'] = $this->language->get('column_action');

        $data['column_payment'] = $this->language->get('column_payment');
        $data['column_delivery_method'] = $this->language->get('column_delivery_method');
        $data['column_delivery_date'] = $this->language->get('column_delivery_date');

        $data['column_sub_total'] = $this->language->get('column_sub_total');
        $data['entry_return_id'] = $this->language->get('entry_return_id');
        $data['entry_order_id'] = $this->language->get('entry_order_id');
        $data['entry_customer'] = $this->language->get('entry_customer');
        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_total'] = $this->language->get('entry_total');
        $data['entry_city'] = $this->language->get('entry_city');
        $data['entry_date_added'] = $this->language->get('entry_date_added');
        $data['entry_date_added_end'] = $this->language->get('entry_date_added_end');
        $data['entry_date_modified'] = $this->language->get('entry_date_modified');
        $data['entry_store_name'] = $this->language->get('entry_store_name');
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

        if (isset($this->request->get['filter_city'])) {
            $url .= '&filter_city=' . $this->request->get['filter_city'];
        }

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_order_from_id'])) {
            $url .= '&filter_order_from_id=' . $this->request->get['filter_order_from_id'];
        }

        if (isset($this->request->get['filter_order_to_id'])) {
            $url .= '&filter_order_to_id=' . $this->request->get['filter_order_to_id'];
        }
        if (isset($this->request->get['filter_company'])) {
            $url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_vendor'])) {
            $url .= '&filter_vendor=' . urlencode(html_entity_decode($this->request->get['filter_vendor'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_store_name'])) {
            $url .= '&filter_store_name=' . urlencode(html_entity_decode($this->request->get['filter_store_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_delivery_method'])) {
            $url .= '&filter_delivery_method=' . urlencode(html_entity_decode($this->request->get['filter_delivery_method'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_delivery_date'])) {
            $url .= '&filter_delivery_date=' . urlencode(html_entity_decode($this->request->get['filter_delivery_date'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_payment'])) {
            $url .= '&filter_payment=' . urlencode(html_entity_decode($this->request->get['filter_payment'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
        }


        if (isset($this->request->get['filter_order_day'])) {
            $url .= '&filter_order_day=' . $this->request->get['filter_order_day'];
        }

        if (isset($this->request->get['filter_order_type'])) {
            $url .= '&filter_order_type=' . $this->request->get['filter_order_type'];
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total=' . $this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_added_end'])) {
            $url .= '&filter_date_added_end=' . $this->request->get['filter_date_added_end'];
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
        }

        if ('ASC' == $order) {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_order'] = $this->url->link('sale/order_product_missing', 'token=' . $this->session->data['token'] . '&sort=o.order_id' . $url, 'SSL');
        $data['sort_city'] = $this->url->link('sale/order_product_missing', 'token=' . $this->session->data['token'] . '&sort=c.name' . $url, 'SSL');
        $data['sort_customer'] = $this->url->link('sale/order_product_missing', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, 'SSL');
        $data['sort_status'] = $this->url->link('sale/order_product_missing', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
        $data['sort_total'] = $this->url->link('sale/order_product_missing', 'token=' . $this->session->data['token'] . '&sort=o.total' . $url, 'SSL');
        $data['sort_date_added'] = $this->url->link('sale/order_product_missing', 'token=' . $this->session->data['token'] . '&sort=o.date_added' . $url, 'SSL');
        $data['sort_date_modified'] = $this->url->link('sale/order_product_missing', 'token=' . $this->session->data['token'] . '&sort=o.date_modified' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['filter_city'])) {
            $url .= '&filter_city=' . $this->request->get['filter_city'];
        }

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_order_from_id'])) {
            $url .= '&filter_order_from_id=' . $this->request->get['filter_order_from_id'];
        }

        if (isset($this->request->get['filter_order_to_id'])) {
            $url .= '&filter_order_to_id=' . $this->request->get['filter_order_to_id'];
        }


        if (isset($this->request->get['filter_company'])) {
            $url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_vendor'])) {
            $url .= '&filter_vendor=' . urlencode(html_entity_decode($this->request->get['filter_vendor'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_store_name'])) {
            $url .= '&filter_store_name=' . urlencode(html_entity_decode($this->request->get['filter_store_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_delivery_method'])) {
            $url .= '&filter_delivery_method=' . urlencode(html_entity_decode($this->request->get['filter_delivery_method'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_delivery_date'])) {
            $url .= '&filter_delivery_date=' . urlencode(html_entity_decode($this->request->get['filter_delivery_date'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_payment'])) {
            $url .= '&filter_payment=' . urlencode(html_entity_decode($this->request->get['filter_payment'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
        }

        if (isset($this->request->get['filter_order_type'])) {
            $url .= '&filter_order_type=' . $this->request->get['filter_order_type'];
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total=' . $this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_added_end'])) {
            $url .= '&filter_date_added_end=' . $this->request->get['filter_date_added_end'];
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $order_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('sale/order_product_missing', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

        $data['filter_city'] = $filter_city;
        $data['filter_order_id'] = $filter_order_id;
        $data['filter_order_from_id'] = $filter_order_from_id;
        $data['filter_order_to_id'] = $filter_order_to_id;
        $data['filter_company'] = $filter_company;
        $data['filter_customer'] = $filter_customer;
        $data['filter_vendor'] = $filter_vendor;
        $data['filter_store_name'] = $filter_store_name;
        $data['filter_delivery_method'] = $filter_delivery_method;
        $data['filter_delivery_date'] = $filter_delivery_date;
        $data['filter_payment'] = $filter_payment;
        $data['filter_order_day'] = $filter_order_day;
        $data['filter_order_status'] = $filter_order_status;
        $data['filter_order_type'] = $filter_order_type;
        $data['filter_total'] = $filter_total;
        $data['filter_date_added'] = $filter_date_added;
        $data['filter_date_added_end'] = $filter_date_added_end;
        $data['filter_date_modified'] = $filter_date_modified;

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->load->model('executives/executives');
        $delivery_executives = $this->model_executives_executives->getExecutives();
        $data['delivery_executives'] = $delivery_executives;

        $this->load->model('drivers/drivers');
        $drivers = $this->model_drivers_drivers->getDrivers();
        $data['drivers'] = $drivers;

        $this->load->model('orderprocessinggroup/orderprocessinggroup');
        $order_processing_groups = $this->model_orderprocessinggroup_orderprocessinggroup->getOrderProcessingGroups();
        $data['order_processing_groups'] = $order_processing_groups;

        $this->response->setOutput($this->load->view('sale/order_product_missing_list.tpl', $data));
    }

    public function getUserByName($name) {
        if ($name) {
            $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . "user` u WHERE CONCAT(u.firstname,' ',u.lastname) LIKE '" . $this->db->escape($name) . "%'");

            return $query->row['user_id'];
        }
    }

    public function addtomissingproduct() {
        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];
        $ordered_products = NULL;
        $error = NULL;
        $log = new Log('error.log');
        try {
            $this->load->language('sale/order');
            $this->load->model('account/customer');
            $this->load->model('sale/order');
            $this->load->model('catalog/vendor_product');
            $this->load->model('tool/image');

            $data['orders'] = [];

            if (isset($this->request->post['selected'])) {
                $orders = explode(",", $this->request->post['selected']);
            }

            if (isset($this->request->post['quantityrequired'])) {
                $ordersquantityrequired = explode(",", $this->request->post['quantityrequired']);
            }

            $j = 0;
            foreach ($orders as $order_product_id) {
                $ordered_products = $this->model_sale_order->getRealOrderProductById($this->request->post['order_id'], $order_product_id);
                if ($ordered_products == NULL) {
                    $ordered_products = $products = $this->model_sale_order->getOrderProductById($this->request->post['order_id'], $order_product_id);
                }
                if ($ordersquantityrequired[$j] > $ordered_products['quantity']) {
                    $error = $ordered_products['name'] . ' Should Not Be Greater Than Ordered Quantity!';
                }
                $j++;
                $json['status'] = 400;
                $json['message'] = $error;
            }

            if ($error == NULL) {
                $i = 0;
                foreach ($orders as $order_product_id) {

                    $ordered_products = $this->model_sale_order->getRealOrderProductById($this->request->post['order_id'], $order_product_id);
                    if ($ordered_products == NULL) {
                        $ordered_products = $products = $this->model_sale_order->getOrderProductById($this->request->post['order_id'], $order_product_id);
                    }

                    $order_product_info = $this->model_sale_order->addOrderProductToMissingProduct($order_product_id, $ordersquantityrequired[$i], $ordered_products['name'], $ordered_products['unit'], $ordered_products['product_note'], $ordered_products['model'], $this->request->post['order_id'], 0);

                    // echo "<pre>";print_r($ordered_products);die; 
                    
                    try{

                        $this->load->model('user/user_activity');
                        $activity_data = [
                            'user_id' => $this->user->getId(),
                            'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
                            'order_id' => $this->request->post['order_id'],
                            'product_id' => $ordered_products['product_id'],
                            'missing_quantity' => $ordersquantityrequired[$i],
                            'user_group_id' => $this->user->getGroupId(),

                        ];
                            $this->model_user_user_activity->addActivity('missing_product_insert', $activity_data);
            
                    }
                    catch(exception $ex)
                    {
                        $log = new Log('error.log');
                        $log->write('Missing Product Insterted ('.$this->request->post['order_id'].'-'.$order_product_id.') .By - '.$this->user->getId());
                    }

                    $i++;

                }
//$this->editinvocebymissingproducts($this->request->post);
                $json['status'] = 200;
                $json['message'] = 'Missed Products Saved Successfully!';
                $json['data'] = $order_product_info;
            }
        } catch (exception $ex) {
            $json = 'failed';
        } finally {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }
    }

    public function editinvocebymissingproducts($data) {

        $log = new Log('error.log');
        $ordered_products = NULL;
        $new_ordered_products = NULL;
        $real_order_products_update = NULL;
        $order_products_update = NULL;

        if (isset($this->request->post['selected'])) {
            $orders = explode(",", $this->request->post['selected']);
        }

        if (isset($this->request->post['quantityrequired'])) {
            $ordersquantityrequired = explode(",", $this->request->post['quantityrequired']);
        }

        $j = 0;
        foreach ($orders as $order_product_id) {
            $real_order_products_update = 'YES';
            $ordered_products = $this->model_sale_order->getRealOrderProductById($this->request->post['order_id'], $order_product_id);
            if ($ordered_products == NULL) {
                $real_order_products_update = NULL;
                $order_products_update = 'YES';
                $ordered_products = $products = $this->model_sale_order->getOrderProductById($this->request->post['order_id'], $order_product_id);
            }

            $product_details = $this->model_catalog_vendor_product->getProduct($ordered_products['product_id']);
            $product_details['product_id'] = $product_details['product_store_id'];

            if ($ordered_products['product_id'] == $product_details['product_store_id'] && $ordersquantityrequired[$j] == $ordered_products['quantity']) {
                $log->write('DELETE PRODUCT');
                $log->write($ordered_products);
                $log->write('DELETE PRODUCT');

                if ($real_order_products_update == 'YES') {
                    $products = $this->model_sale_order->deleteOrderProduct($this->request->post['order_id'], $product_details['product_store_id']);
                }

                if ($order_products_update == 'YES') {
                    $products = $this->model_sale_order->deleteCustomerOrderProduct($this->request->post['order_id'], $product_details['product_store_id']);
                }
            }

            if ($ordered_products['product_id'] == $product_details['product_store_id'] && $ordersquantityrequired[$j] < $ordered_products['quantity']) {
                $log->write('EDIT PRODUCT');
                $updateProduct = $ordered_products;
                $updateProduct['quantity'] = $ordered_products['quantity'] - $ordersquantityrequired[$j];
                $custom_price = $ordered_products['price'];
                $log->write($updateProduct);
                $updateProduct_tax_total = NULL;
                $updateProduct_tax_total = $this->model_tool_image->getTaxTotalCustom($product_details, $product_details['store_id'], NULL, $custom_price);
                $log->write($updateProduct_tax_total);
                $log->write('EDIT PRODUCT');

                if ($real_order_products_update == 'YES') {
                    $products = $this->model_sale_order->updateOrderProduct($this->request->post['order_id'], $product_details['product_store_id'], $updateProduct, $updateProduct_tax_total);
                }

                if ($order_products_update == 'YES') {
                    $products = $this->model_sale_order->updateOrderProductNew($this->request->post['order_id'], $product_details['product_store_id'], $updateProduct, $updateProduct_tax_total);
                }
            }

            $j++;
        }

        $new_ordered_products = $this->model_sale_order->getRealOrderProducts($this->request->post['order_id']);
        if ($new_ordered_products == NULL) {
            $new_ordered_products = $products = $this->model_sale_order->getOrderProducts($this->request->post['order_id']);
        }

        $sumTotal = 0;
        $sumTotalTax = 0;

        foreach ($new_ordered_products as $new_ordered_product) {
            $sumTotal += ($new_ordered_product['price'] * $new_ordered_product['quantity']);
            $sumTotalTax += ($new_ordered_product['tax'] * $new_ordered_product['quantity']);
        }

        $totals = $this->model_sale_order->getOrderTotals($this->request->post['order_id']);
        $this->model_sale_order->deleteOrderTotal($this->request->post['order_id']);
        $dbsubtotal = NULL;
        $dbtotal = NULL;
        $dbtax = NULL;
        $dbothertotal = 0;
        $dbothertotals = NULL;
        $grand_total = 0;

        foreach ($totals as $total) {
            if ($total['code'] != 'sub_total' && $total['code'] != 'total' && $total['code'] != 'tax') {
                $dbothertotal += $total['value'];

                $dbothertotals['order_id'] = $this->request->post['order_id'];
                $dbothertotals['code'] = $total['code'];
                $dbothertotals['title'] = $total['title'];
                $dbothertotals['sort'] = $total['sort_order'];
                $dbothertotals['value'] = $total['value'];
                $dbothertotals['actual_value'] = $total['actual_value'];
                $log->write($total);
                if ($dbothertotals != NULL) {
                    $this->model_sale_order->insertOrderTotal($this->request->post['order_id'], $dbothertotals, NULL);
                }
            }
            $dbothertotals = NULL;
        }

        foreach ($totals as $total) {

            if ($total['code'] == 'sub_total') {
                $dbsubtotal['order_id'] = $this->request->post['order_id'];
                $dbsubtotal['code'] = $total['code'];
                $dbsubtotal['title'] = $total['title'];
                $dbsubtotal['sort'] = $total['sort_order'];
                $dbsubtotal['value'] = $sumTotal;
                $dbsubtotal['actual_value'] = $total['actual_value'];
                $log->write($total);
                $this->model_sale_order->insertOrderTotal($this->request->post['order_id'], $dbsubtotal, NULL);
            }

            if ($total['code'] == 'total') {
                $dbtotal['order_id'] = $this->request->post['order_id'];
                $dbtotal['code'] = $total['code'];
                $dbtotal['title'] = $total['title'];
                $dbtotal['sort'] = $total['sort_order'];
                $dbtotal['value'] = $sumTotal + $sumTotalTax + $dbothertotal;
                $dbtotal['actual_value'] = $total['actual_value'];
                $log->write($total);
                $this->model_sale_order->insertOrderTotal($this->request->post['order_id'], $dbtotal, NULL);
            }

            if ($total['code'] == 'tax') {
                $dbtax['order_id'] = $this->request->post['order_id'];
                $dbtax['code'] = $total['code'];
                $dbtax['title'] = $total['title'];
                $dbtax['sort'] = $total['sort_order'];
                $dbtax['value'] = $sumTotalTax;
                $dbtotal['actual_value'] = $total['actual_value'];
                $log->write($total);
                $this->model_sale_order->insertOrderTotal($this->request->post['order_id'], $dbtax, NULL);
            }
        }

        $grand_total = $sumTotal + $sumTotalTax + $dbothertotal;

        $this->model_sale_order->updateordertotal($this->request->post['order_id'], $grand_total);
        $this->sendmailwithmissingproducts($this->request->post['order_id']);
        $data['order_id'] = $this->request->post['order_id'];
        $this->getOrderProductListTemplate($this->request->post['order_id']);
//$this->missing_products_order_invoice_download($data);
        $log->write('TOTALS');
        $log->write($sumTotal);
        $log->write($sumTotalTax);
        $log->write($dbothertotal);
        $log->write('TOTALS');
    }

    public function missing_products_order_invoice_download($custom_order_id) {
        $this->load->language('sale/order');

        $data['title'] = $this->language->get('text_invoice');

        if ($this->request->server['HTTPS']) {
            $data['base'] = HTTPS_SERVER;
        } else {
            $data['base'] = HTTP_SERVER;
        }

        $data['direction'] = $this->language->get('direction');
        $data['lang'] = $this->language->get('code');

        $data['text_date_delivered'] = $this->language->get('text_date_delivered');
        $data['text_invoice'] = $this->language->get('text_invoice');
        $data['text_order_detail'] = $this->language->get('text_order_detail');
        $data['text_order_id'] = $this->language->get('text_order_id');
        $data['text_invoice_no'] = $this->language->get('text_invoice_no');
        $data['text_invoice_date'] = $this->language->get('text_invoice_date');
        $data['text_date_added'] = $this->language->get('text_date_added');
        $data['text_telephone'] = $this->language->get('text_telephone');
        $data['text_fax'] = $this->language->get('text_fax');
        $data['text_name'] = $this->language->get('text_name');
        $data['text_contact_no'] = $this->language->get('text_contact_no');
        $data['text_email'] = $this->language->get('text_email');
        $data['text_website'] = $this->language->get('text_website');
        $data['text_to'] = $this->language->get('text_to');
        $data['text_po_no'] = $this->language->get('text_po_no');
        $data['text_ship_to'] = $this->language->get('text_ship_to');
        $data['text_payment_method'] = $this->language->get('text_payment_method');
        $data['text_shipping_method'] = $this->language->get('text_shipping_method');

        $data['column_product'] = $this->language->get('column_product');
        $data['column_produce_type'] = $this->language->get('column_produce_type');

        $data['column_model'] = $this->language->get('column_model');
        $data['column_unit'] = $this->language->get('column_unit') . ' Ordered';
        $data['column_quantity'] = $this->language->get('column_quantity') . ' Ordered';
        $data['column_unit_change'] = $this->language->get('column_unit') . ' Change';
        $data['column_quantity_change'] = $this->language->get('column_quantity') . ' Change';
        $data['column_price'] = $this->language->get('column_price');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_comment'] = $this->language->get('column_comment');

        $data['text_tax'] = $this->language->get('text_tax');
        $data['text_cpf_number'] = $this->language->get('text_cpf_number');

        $this->load->model('sale/order');
        $this->load->model('tool/image');

        $this->load->model('setting/setting');

        $data['orders'] = [];

        $orders = [];

        $orders[] = $custom_order_id['order_id'];

        if (isset($this->request->get['store_id'])) {
            $store_id = $this->request->get['store_id'];
        } else {
            $store_id = 0;
        }

        foreach ($orders as $order_id) {
            $order_info = $this->model_sale_order->getOrder($order_id);
//check vendor order

            if ($order_info['order_status_id'] != 1 && $order_info['order_status_id'] != 4 && $order_info['order_status_id'] != 5) {
                $this->response->redirect($this->url->link('error/not_found'));
            }
            if ($this->user->isVendor()) {
                if (!$this->isVendorOrder($order_id)) {
                    $this->response->redirect($this->url->link('error/not_found'));
                }
            }

            if ($order_info) {
                $this->load->model('drivers/drivers');
                $driver_info = $this->model_drivers_drivers->getDriver($order_info['driver_id']);
                $driver_name = NULL;
                $driver_phone = NULL;
                if ($driver_info) {
                    $driver_name = $driver_info['firstname'] . ' ' . $driver_info['lastname'];
                    $driver_phone = $driver_info['telephone'];
                }
                $data['driver_name'] = $driver_name;
                $data['driver_phone'] = $driver_phone;

                $this->load->model('executives/executives');
                $executive_info = $this->model_executives_executives->getExecutive($order_info['delivery_executive_id']);
                $executive_name = NULL;
                $executive_phone = NULL;
                if ($executive_info) {
                    $executive_name = $executive_info['firstname'] . ' ' . $executive_info['lastname'];
                    $executive_phone = $executive_info['telephone'];
                }
                $data['delivery_executive_name'] = $executive_name;
                $data['delivery_executive_phone'] = $executive_phone;
                $store_info = $this->model_setting_setting->getSetting('config', $order_info['store_id']);
// if ($store_info) {
//     $store_address = $store_info['config_address'];
//     $store_email = $store_info['config_email'];
//     $store_telephone = $store_info['config_telephone'];
//     $store_fax = $store_info['config_fax'];
// } else {
//     $store_address = $this->config->get('config_address');
//     $store_email = $this->config->get('config_email');
//     $store_telephone = $this->config->get('config_telephone');
//     $store_fax = $this->config->get('config_fax');
// }

                $store_data = $this->model_sale_order->getStoreData($order_info['store_id']);
                if ($store_data) {
                    $store_address = $store_data['address'];
                    $store_email = $store_data['email'];
                    $store_telephone = $store_data['telephone'];
                    $store_fax = $store_data['fax'];
                    $store_tax = $store_data['tax'];
                } else {
                    $store_address = $this->config->get('config_address');
                    $store_email = $this->config->get('config_email');
                    $store_telephone = $this->config->get('config_telephone');
                    $store_fax = $this->config->get('config_fax');
                    $store_tax = '';
                }

                $data['store_logo'] = $this->model_tool_image->resize($store_data['logo'], 300, 300);
                $data['store_name'] = $store_data['name'];

                if ($order_info['invoice_no']) {
                    $invoice_no = $order_info['invoice_prefix'] . $order_info['invoice_no'] . $order_info['invoice_sufix'];
                } else {
                    $invoice_no = '';
                }

                $this->load->model('tool/upload');

                $product_data = [];

                $filter['filter_order_id'] = $order_id;

                $products = $this->model_sale_order->getOrderedMissingProducts($filter);
                $sub_total = 0;
                $tax = 0;
                foreach ($products as $product) {
                    if ($store_id && $product['store_id'] != $store_id) {
                        continue;
                    }
                    $option_data = [];

                    $product_data[] = [
                        'product_id' => $product['product_id'],
                        'name' => $product['name'],
                        'product_note' => $product['product_note'],
                        'model' => $product['model'],
                        'unit' => $product['unit'],
                        'option' => $option_data,
                        'quantity' => $product['quantity_required'],
                        'price' => $this->currency->format($product['mp_price'] + ($this->config->get('config_tax') ? $product['mp_tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                        'total' => $this->currency->format($product['mp_total'] + ($this->config->get('config_tax') ? ($product['mp_tax'] * $product['quantity_required']) : 0), $order_info['currency_code'], $order_info['currency_value']),
                    ];
                    $sub_total += $product['mp_price'] * $product['quantity_required'];
                    $tax += $product['mp_tax'] * $product['quantity_required'];
                }
                $new_total = $sub_total + $tax;
                $total_data = [];

                if ($store_id) {
                    $totals = $this->model_sale_order->getVendorOrderTotals($order_id, $store_id);
                } else {
                    $totals = $this->model_sale_order->getOrderTotals($order_id);
                }
                $credit_amount = 0;
                foreach ($totals as $total) {
                    if ($total['value'] > 0 || $total['code'] == 'credit')
                        if ($total['code'] == 'credit') {
                            $credit_amount = $total['value'];
                        }
                    if ($total['code'] == 'total') {
                        $total['value'] += $credit_amount;
                        $total_data[] = [
                            'title' => $total['title'],
                            'text' => $this->currency->format($new_total, $order_info['currency_code'], $order_info['currency_value']),
                            'amount_in_words' => ucwords($this->translateAmountToWords(floor(($new_total * 100) / 100))) . ' Kenyan Shillings',
                        ];
                    } if ($total['code'] == 'sub_total') {
                        $total_data[] = [
                            'title' => $total['title'],
                            'text' => $this->currency->format($sub_total, $order_info['currency_code'], $order_info['currency_value']),
                            'amount_in_words' => ucwords($this->translateAmountToWords(floor(($sub_total * 100) / 100))) . ' Kenyan Shillings',
                        ];
                    } if ($total['code'] == 'tax') {
                        $total_data[] = [
                            'title' => $total['title'],
                            'text' => $this->currency->format($tax, $order_info['currency_code'], $order_info['currency_value']),
                            'amount_in_words' => ucwords($this->translateAmountToWords(floor(($tax * 100) / 100))) . ' Kenyan Shillings',
                        ];
                    } if ($total['code'] == 'transaction_fee') {
                        $total_data[] = [
                            'title' => $total['title'],
                            'text' => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
                            'amount_in_words' => ucwords($this->translateAmountToWords(floor(($total['value'] * 100) / 100))) . ' Kenyan Shillings',
                        ];
                    }
                }

                $this->load->model('sale/customer');
                $this->load->model('user/accountmanager');
                $this->load->model('user/customerexperience');
                $order_customer_detials = $this->model_sale_customer->getCustomer($order_info['customer_id']);
                $order_customer_first_last_name = NULL;
                $company_name = NULL;
                $customer_account_manager_first_last_name = NULL;
                $customer_account_manager_phone = NULL;
                $customer_experience_first_last_name = NULL;
                $customer_experince_phone = NULL;
                if ($order_customer_detials != NULL && is_array($order_customer_detials)) {
                    $order_customer_first_last_name = $order_customer_detials['firstname'] . ' ' . $order_customer_detials['lastname'];
                    $company_name = $order_customer_detials['company_name'];
                    $customer_account_manager_detials = $this->model_user_accountmanager->getUser($order_customer_detials['account_manager_id']);
                    if ($order_customer_detials['account_manager_id'] > 0 && $order_customer_detials['account_manager_id'] != NULL && $customer_account_manager_detials != NULL) {
                        $customer_account_manager_first_last_name = $customer_account_manager_detials['firstname'] . ' ' . $customer_account_manager_detials['lastname'];
                        $customer_account_manager_phone = $customer_account_manager_detials['mobile'] == NULL ? '+254 ' . $customer_account_manager_detials['telephone'] : '+254 ' . $customer_account_manager_detials['mobile'];
                    }
                    $customer_customer_experience_detials = $this->model_user_customerexperience->getUser($order_customer_detials['customer_experience_id']);
                    if ($order_customer_detials['customer_experience_id'] > 0 && $order_customer_detials['customer_experience_id'] != NULL && $customer_customer_experience_detials != NULL) {
                        $customer_experience_first_last_name = $customer_customer_experience_detials['firstname'] . ' ' . $customer_customer_experience_detials['lastname'];
                        $customer_experince_phone = $customer_customer_experience_detials['mobile'] == NULL ? '+254 ' . $customer_customer_experience_detials['telephone'] : '+254 ' . $customer_customer_experience_detials['mobile'];
                    }
                }
                $data['delivery_charge'] = $order_info['delivery_charge'];

                $this->load->model('drivers/drivers');
                $driver_info = $this->model_drivers_drivers->getDriver($order_info['driver_id']);
                $driver_name = NULL;
                $driver_phone = NULL;
                if ($driver_info) {
                    $driver_name = $driver_info['firstname'] . ' ' . $driver_info['lastname'];
                    $driver_phone = $driver_info['telephone'];
                }
                $data['driver_name'] = $driver_name;
                $data['driver_phone'] = $driver_phone;

                $delivery_executive_info = $this->model_executives_executives->getExecutive($order_info['delivery_executive_id']);
                $delivery_executive_name = NULL;
                $delivery_executive_phone = NULL;
                if ($delivery_executive_info) {
                    $delivery_executive_name = $delivery_executive_info['firstname'] . ' ' . $delivery_executive_info['lastname'];
                    $delivery_executive_phone = $delivery_executive_info['telephone'];
                }
                $data['delivery_executive_name'] = $delivery_executive_name;
                $data['delivery_executive_phone'] = $delivery_executive_phone;

                $data['orders'][] = [
                    'order_id' => $order_id,
                    'invoice_no' => $invoice_no,
                    'date_added' => date($this->language->get('datetime_format'), strtotime($order_info['date_added'])),
                    'delivery_date' => date($this->language->get('date_format_short'), strtotime($order_info['delivery_date'])),
                    'delivery_timeslot' => $order_info['delivery_timeslot'],
                    'store_name' => $order_info['store_name'],
                    'store_url' => rtrim($order_info['store_url'], '/'),
                    'store_address' => nl2br($store_address),
                    'store_email' => $store_email,
                    'store_tax' => $store_tax,
                    'store_telephone' => $store_telephone,
                    'store_fax' => $store_fax,
                    'email' => $order_info['email'],
                    'cpf_number' => $this->getUser($order_info['customer_id']),
                    'telephone' => $order_info['telephone'],
                    'shipping_address' => $order_info['shipping_address'],
                    'shipping_city' => $order_info['shipping_city'],
                    'shipping_flat_number' => $order_info['shipping_flat_number'],
                    'shipping_contact_no' => ($order_info['shipping_contact_no']) ? $order_info['shipping_contact_no'] : $order_info['telephone'],
                    /* 'shipping_name' => ($order_info['shipping_name']) ? $order_info['shipping_name'] : $order_info['firstname'] . ' ' . $order_info['lastname'], */
                    'shipping_name' => $order_customer_first_last_name == NULL ? $order_info['firstname'] . ' ' . $order_info['lastname'] : $order_customer_first_last_name,
                    'customer_company_name' => $company_name == NULL ? $order_info['customer_company_name'] : $company_name,
                    'shipping_method' => $order_info['shipping_method'],
                    'po_number' => $order_info['po_number'],
                    'payment_method' => $order_info['payment_method'],
                    'products' => $product_data,
                    'totals' => $total_data,
                    'comment' => nl2br($order_info['comment']),
                    'shipping_name_original' => $order_info['shipping_name'],
                    'driver_name' => $driver_name,
                    'driver_phone' => '+' . $this->config->get('config_telephone_code') . ' ' . $driver_phone,
                    'delivery_executive_name' => $delivery_executive_name,
                    'delivery_executive_phone' => '+' . $this->config->get('config_telephone_code') . ' ' . $delivery_executive_phone,
                    'delivery_charge' => $order_info['delivery_charge'],
                    'vendor_terms_cod' => $order_info['vendor_terms_cod'],
                    'payment_terms' => $order_customer_detials['payment_terms'],
                    'account_manager_name' => $customer_account_manager_first_last_name,
                    'account_manager_phone' => $customer_account_manager_phone,
                    'customer_experience_first_last_name' => $customer_experience_first_last_name,
                    'customer_experince_phone' => $customer_experince_phone
                ];
            }
        }

        if ($custom_order_id != NULL && $custom_order_id['order_id'] > 0) {
            try {
                require_once DIR_ROOT . '/vendor/autoload.php';
                $pdf = new \mikehaertl\wkhtmlto\Pdf;
                $template = $this->load->view('sale/order_invoice_pdf.tpl', $data['orders'][0]);
                $pdf->addPage($template);
                $filename = "KWIKBASKET_MISSED_PRODUCTS_ORDER_" . $order_id . ".pdf";
                if (!$pdf->saveAs(DIR_ROOT . 'scheduler_downloads' . '/' . $filename)) {
                    $error = $pdf->getError();
                    $log = new Log('error.log');
                    $log->write('pdf_error');
                    $log->write($error);
                    $log->write('pdf_error');
                }
            } catch (Exception $e) {
                $log = new Log('error.log');
                $log->write('pdf_error');
                $log->write($e->getMessage());
                $log->write('pdf_error');
            }
        }
    }

    public function translateAmountToWords(int $number) {
        /*
         * A recursive function to turn digits into words
         * Numbers must be integers from -999,999,999,999 to 999,999,999,999 inclusive.
         * Zero is a special case, it cause problems even with typecasting if we don't deal with it here
         */
        $max_size = pow(10, 18);
        if (!$number) {
            return 'zero';
        }
        if ($number < abs($max_size)) {
            switch ($number) {
// set up some rules for converting digits to words
                case $number < 0:
                    $prefix = 'negative';
                    $suffix = $this->translateAmountToWords(-1 * $number);
                    $string = $prefix . ' ' . $suffix;
                    break;
                case 1:
                    $string = 'one';
                    break;
                case 2:
                    $string = 'two';
                    break;
                case 3:
                    $string = 'three';
                    break;
                case 4:
                    $string = 'four';
                    break;
                case 5:
                    $string = 'five';
                    break;
                case 6:
                    $string = 'six';
                    break;
                case 7:
                    $string = 'seven';
                    break;
                case 8:
                    $string = 'eight';
                    break;
                case 9:
                    $string = 'nine';
                    break;
                case 10:
                    $string = 'ten';
                    break;
                case 11:
                    $string = 'eleven';
                    break;
                case 12:
                    $string = 'twelve';
                    break;
                case 13:
                    $string = 'thirteen';
                    break;
// fourteen handled later
                case 15:
                    $string = 'fifteen';
                    break;
                case $number < 20:
                    $string = $this->translateAmountToWords($number % 10);
// eighteen only has one "t"
                    if (18 == $number) {
                        $suffix = 'een';
                    } else {
                        $suffix = 'teen';
                    }
                    $string .= $suffix;
                    break;
                case 20:
                    $string = 'twenty';
                    break;
                case 30:
                    $string = 'thirty';
                    break;
                case 40:
                    $string = 'forty';
                    break;
                case 50:
                    $string = 'fifty';
                    break;
                case 60:
                    $string = 'sixty';
                    break;
                case 70:
                    $string = 'seventy';
                    break;
                case 80:
                    $string = 'eighty';
                    break;
                case 90:
                    $string = 'ninety';
                    break;
                case $number < 100:
                    $prefix = $this->translateAmountToWords($number - $number % 10);
                    $suffix = $this->translateAmountToWords($number % 10);
                    $string = $prefix . ' ' . $suffix;
                    break;
// handles all number 100 to 999
                case $number < pow(10, 3):
// floor return a float not an integer
                    $prefix = $this->translateAmountToWords(intval(floor($number / pow(10, 2)))) . ' hundred';
                    if ($number % pow(10, 2)) {
                        $suffix = ' and ' . $this->translateAmountToWords($number % pow(10, 2));
                    }
                    $string = $prefix . $suffix;
                    break;
                case $number < pow(10, 6):
// floor return a float not an integer
                    $prefix = $this->translateAmountToWords(intval(floor($number / pow(10, 3)))) . ' thousand';
                    if ($number % pow(10, 3)) {
                        $suffix = $this->translateAmountToWords($number % pow(10, 3));
                    }
                    $string = $prefix . ' ' . $suffix;
                    break;
                case $number < pow(10, 9):
// floor return a float not an integer
                    $prefix = $this->translateAmountToWords(intval(floor($number / pow(10, 6)))) . ' million';
                    if ($number % pow(10, 6)) {
                        $suffix = $this->translateAmountToWords($number % pow(10, 6));
                    }
                    $string = $prefix . ' ' . $suffix;
                    break;
                case $number < pow(10, 12):
// floor return a float not an integer
                    $prefix = $this->translateAmountToWords(intval(floor($number / pow(10, 9)))) . ' billion';
                    if ($number % pow(10, 9)) {
                        $suffix = $this->translateAmountToWords($number % pow(10, 9));
                    }
                    $string = $prefix . ' ' . $suffix;
                    break;
                case $number < pow(10, 15):
// floor return a float not an integer
                    $prefix = $this->translateAmountToWords(intval(floor($number / pow(10, 12)))) . ' trillion';
                    if ($number % pow(10, 12)) {
                        $suffix = $this->translateAmountToWords($number % pow(10, 12));
                    }
                    $string = $prefix . ' ' . $suffix;
                    break;
// Be careful not to pass default formatted numbers in the quadrillions+ into this function
// Default formatting is float and causes errors
                case $number < pow(10, 18):
// floor return a float not an integer
                    $prefix = $this->translateAmountToWords(intval(floor($number / pow(10, 15)))) . ' quadrillion';
                    if ($number % pow(10, 15)) {
                        $suffix = $this->translateAmountToWords($number % pow(10, 15));
                    }
                    $string = $prefix . ' ' . $suffix;
                    break;
            }
        } else {
            echo "ERROR with - $number<br/> Number must be an integer between -" . number_format($max_size, 0, '.', ',') . ' and ' . number_format($max_size, 0, '.', ',') . ' exclusive.';
        }

        return $string;
    }

    public function getUser($id) {
        if ($id) {
            $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . "customer`  WHERE customer_id ='" . $id . "'");

            return $query->row['fax'];
        }
    }

    public function getOrderProductListTemplate($order_id) {
        $log = new Log('error.log');
        $data['products'] = [];
        $filter['filter_order_id'] = $order_id;

        $sub_total = 0;
        $tax = 0;
        $order_info = $this->model_sale_order->getOrder($order_id);
        $products = $this->model_sale_order->getOrderedMissingProducts($filter);
        $totals = $this->model_sale_order->getOrderTotals($order_id);

        foreach ($products as $product) {
            $data['products'][] = [
                'product_id' => $product['product_id'],
                'product_note' => $product['product_note'],
                'name' => $product['name'],
                'unit' => $product['unit'],
                'model' => $product['model'],
                'quantity' => $product['quantity_required'],
                'price' => $this->currency->format($product['mp_price'] + ($this->config->get('config_tax') ? $product['mp_tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                'total' => $this->currency->format($product['mp_total'] + ($this->config->get('config_tax') ? ($product['mp_tax'] * $product['quantity_required']) : 0), $order_info['currency_code'], $order_info['currency_value']),
            ];
            $sub_total += $product['mp_price'] * $product['quantity_required'];
            $tax += $product['mp_tax'] * $product['quantity_required'];
        }

        $new_total = $sub_total + $tax;
        $total_data = [];

        foreach ($totals as $total) {
            if ($total['code'] == 'sub_total') {
                $total_data[] = [
                    'title' => $total['title'],
                    'text' => $this->currency->format($sub_total, $order_info['currency_code'], $order_info['currency_value']),
                    'amount_in_words' => ucwords($this->translateAmountToWords(floor(($sub_total * 100) / 100))) . ' Kenyan Shillings',
                    'value' => $sub_total,
                    'sort_order' => $total['sort_order'],
                ];
            } elseif ($total['code'] == 'tax') {
                $total_data[] = [
                    'title' => $total['title'],
                    'text' => $this->currency->format($tax, $order_info['currency_code'], $order_info['currency_value']),
                    'amount_in_words' => ucwords($this->translateAmountToWords(floor(($tax * 100) / 100))) . ' Kenyan Shillings',
                    'value' => $tax,
                    'sort_order' => $total['sort_order'],
                ];
            } elseif ($total['code'] == 'transaction_fee') {
                $total_data[] = [
                    'title' => $total['title'],
                    'text' => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
                    'amount_in_words' => ucwords($this->translateAmountToWords(floor(($total['value'] * 100) / 100))) . ' Kenyan Shillings',
                    'value' => $total['value'],
                    'sort_order' => $total['sort_order'],
                ];
            } elseif ($total['code'] == 'total') {
                $total_data[] = [
                    'title' => $total['title'],
                    'text' => $this->currency->format($new_total, $order_info['currency_code'], $order_info['currency_value']),
                    'amount_in_words' => ucwords($this->translateAmountToWords(floor(($new_total * 100) / 100))) . ' Kenyan Shillings',
                    'value' => $new_total,
                    'sort_order' => $total['sort_order'],
                ];
            } else {
                $total_data[] = [
                    'title' => $total['title'],
                    'text' => $this->currency->format($new_total, $order_info['currency_code'], $order_info['currency_value']),
                    'amount_in_words' => ucwords($this->translateAmountToWords(floor(($new_total * 100) / 100))) . ' Kenyan Shillings',
                    'value' => $total['value'],
                    'sort_order' => $total['sort_order'],
                ];
            }
        }

        usort($total_data, function ($a, $b) {
            return $a['sort_order'] <=> $b['sort_order'];
        });

        $log->write('total_data');
        $log->write($total_data);
        $log->write('total_data');

        $html = '';
        $html .= '<table class="table table-bordered" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;border-collapse: collapse!important;border-spacing: 0;background-color: transparent;width: 100%;max-width: 100%;margin-bottom: 20px;border: 1px solid #ddd;">';
        $html .= '<thead class="thead-bg" style="background: #EC7122;color: #fff;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;display: table-header-group;">'
                . '<tr style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;page-break-inside: avoid;">'
                . '<th scope="col" style="background-color: #EC7122 !important;color: #000;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding: 8px;text-align: left;line-height: 1.42857143;vertical-align: bottom;border-top: 1px solid #ddd;border-bottom: 2px solid #ddd;border: 1px solid #ddd!important;border-bottom-width: 2px;">S.NO</th>'
                . '<th scope="col" style="background-color: #EC7122 !important;color: #000;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding: 8px;text-align: left;line-height: 1.42857143;vertical-align: bottom;border-top: 1px solid #ddd;border-bottom: 2px solid #ddd;border: 1px solid #ddd!important;border-bottom-width: 2px;">PRODUCT NAME</th>'
                . '<th scope="col" style="background-color: #EC7122 !important;color: #000;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding: 8px;text-align: left;line-height: 1.42857143;vertical-align: bottom;border-top: 1px solid #ddd;border-bottom: 2px solid #ddd;border: 1px solid #ddd!important;border-bottom-width: 2px;">UNIT PRICE</th>'
                . '<th scope="col" style="background-color: #EC7122 !important;color: #000;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding: 8px;text-align: left;line-height: 1.42857143;vertical-align: bottom;border-top: 1px solid #ddd;border-bottom: 2px solid #ddd;border: 1px solid #ddd!important;border-bottom-width: 2px;">UNIT</th>'
                . '<th scope="col" style="background-color: #EC7122 !important;color: #000;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding: 8px;text-align: left;line-height: 1.42857143;vertical-align: bottom;border-top: 1px solid #ddd;border-bottom: 2px solid #ddd;border: 1px solid #ddd!important;border-bottom-width: 2px;">QUANTITY</th>'
                . '<th scope="col" style="background-color: #EC7122 !important;color: #000;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding: 8px;text-align: left;line-height: 1.42857143;vertical-align: bottom;border-top: 1px solid #ddd;border-bottom: 2px solid #ddd;border: 1px solid #ddd!important;border-bottom-width: 2px;">TOTAL</th>'
                . '</tr>'
                . '</thead>';
        $html .= '<tbody style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;">';
        $count = 1;
        foreach ($data['products'] as $product) {
            $html .= '<tr style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;page-break-inside: avoid;">
            <th scope="row" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding: 8px;text-align: left;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;border: 1px solid #ddd!important;background-color: #fff!important;">' . $count . '</th>
            <td style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;border: 1px solid #ddd!important;background-color: #fff!important;">' . $product['name'] . '</td>
            <td style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;border: 1px solid #ddd!important;background-color: #fff!important;">' . $product['price'] . '</td>
            <td style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;border: 1px solid #ddd!important;background-color: #fff!important;">' . $product['unit'] . '</td>
            <td style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;border: 1px solid #ddd!important;background-color: #fff!important;">' . $product['quantity'] . '</td>
            <td style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;border: 1px solid #ddd!important;background-color: #fff!important;">' . $product['total'] . '</td>
        </tr>';
            $count++;
        }
        foreach ($total_data as $total) {
            $html .= '<tr style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;page-break-inside: avoid;">'
                    . '<th colspan="4" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding: 8px;text-align: left;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;border: 1px solid #ddd!important;background-color: #fff!important;"></th>'
                    . '<td style="text-align: right;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;border: 1px solid #ddd!important;background-color: #fff!important;"><strong style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;font-weight: 700;">' . $total['title'] . '</strong></td>'
                    . '<td style="text-align: right;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;border: 1px solid #ddd!important;background-color: #fff!important;"><strong style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;font-weight: 700;">' . $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']) . '</strong></td>'
                    . '</tr>';
        }
        $html .= '</tbody></table>';
        return $html;
    }

    public function sendmailwithmissingproducts($order_id) {
        $log = new Log('error.log');
        $this->load->model('sale/order');
        $this->load->model('account/customer');
        $order_info = $this->model_sale_order->getOrder($order_id);

        $address = '';
        $payment_address = '';
        $special = NULL;
        if ($order_info['customer_id']) {
            $order_href = $order_info['store_url'] . 'index.php?path=account/order/info&order_id=' . $order_info['order_id'];
        }
        $order_pdf_href = NULL;
        $order_status = NULL;
        $totals = NULL;
        $tax_amount = NULL;
        $invoice_no = NULL;

        $data = array(
            'template_id' => 'order_21',
            'order_info' => $order_info,
            'address' => $address,
            'payment_address' => $payment_address,
            'special' => $special,
            'order_href' => $order_href,
            'order_pdf_href' => $order_pdf_href,
            'order_status' => $order_status,
            'totals' => $totals,
            'tax_amount' => $tax_amount,
            'order_id' => $order_id,
            'invoice_no' => !empty($invoice_no) ? $invoice_no : '',
            'order_products_list' => $this->getOrderProductListTemplate($order_id),
            'missed_products_order_link' => $order_info['store_url'] . 'index.php?path=deliversystem/deliversystem/createorderwithmissingproducts&order_id=' . base64_encode($order_info['order_id']),
        );
        $this->model_sale_order->SaveMissingOrderProductLink($order_id, $data['missed_products_order_link']);

        try {
            $log = new Log('error.log');
            $log->write('MISSING_ORDER_PRODUCT_MAIL_SENDING');
            $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);
            $subject = $this->emailtemplate->getSubject('OrderAll', 'order_21', $data);
            $message = $this->emailtemplate->getMessage('OrderAll', 'order_21', $data);
            $sms_message = $this->emailtemplate->getSmsMessage('OrderAll', 'order_21', $data);

            $mail = new Mail($this->config->get('config_mail'));
            $mail->setTo($customer_info['email']);
            $mail->setFrom($this->config->get('config_from_email'));
            $mail->setSender($this->config->get('config_name'));
            $mail->setSubject($subject);
            $mail->setHTML($message);
            $mail->send();

            
            // $log->write('SMS SENDING');
            // send message here
            // if ($customer_info['sms_notification'] == 1 && $this->emailtemplate->getSmsEnabled('Customer', 'customer_8')) {
                $ret = $this->emailtemplate->sendmessage($customer_info['telephone'], $sms_message);
            // }

           

            $log = new Log('error.log');
            $log->write('subject');
            $log->write($subject);
            $log->write('subject');
        } catch (exception $ex) {
            $log = new Log('error.log');
            $log->write('ORDER PRODUCT MISSING EXCEPTION');
            $log->write($ex);
            $log->write('ORDER PRODUCT MISSING EXCEPTION');
        }
    }

}
