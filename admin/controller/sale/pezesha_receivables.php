<?php

class ControllerSalePezeshaReceivables extends Controller {

    private $error = [];

    public function index() {
        $this->load->language('sale/order');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/order');
        $this->load->model('vendor/vendor');

        $this->getList();
    }

    protected function getList() {
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

        if (isset($this->request->get['filter_delivery_time_slot'])) {
            $url .= '&filter_delivery_time_slot=' . urlencode(html_entity_decode($this->request->get['filter_delivery_time_slot'], ENT_QUOTES, 'UTF-8'));
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
            'href' => $this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, 'SSL'),
        ];

        $data['invoice'] = $this->url->link('sale/order/invoice', 'token=' . $this->session->data['token'], 'SSL');
        $data['dispatchplanning'] = $this->url->link('vehicles/vehicles_list', 'token=' . $this->session->data['token'], 'SSL');
        $data['invoicepdf'] = $this->url->link('sale/order/invoicepdf', 'token=' . $this->session->data['token'], 'SSL');
        // $data['shipping'] = $this->url->link('sale/order/shipping', 'token=' . $this->session->data['token'], 'SSL');
        $data['shipping'] = $this->url->link('sale/order/shippingNote', 'token=' . $this->session->data['token'], 'SSL');
        $data['add'] = $this->url->link('sale/order/add', 'token=' . $this->session->data['token'], 'SSL');
        $data['delivery_sheet'] = $this->url->link('sale/order/consolidatedOrderSheet', 'token=' . $this->session->data['token'], 'SSL');
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
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        // echo "<pre>";print_r($filter_data);die; 

        $order_total = $this->model_sale_order->getTotalPezeshaOrders($filter_data);

        $results = $this->model_sale_order->getPezeshaOrders($filter_data);

        //        echo "<pre>";print_r($results);die;
        $disable = [2, 6, 7, 5, 8, 4, 15, 1, 14, 16, 13, 9, 10, 3, 11, 12];
        foreach ($results as $result) {
            $sub_total = 0;

            $totals = $this->model_sale_order->getOrderTotals($result['order_id']);
            $missing_products = $this->model_sale_order->getMissingProductsByOrderId($result['order_id']);
            $store_details = $this->model_vendor_vendor->getVendorByStoreId($result['store_id']);
            $vendor_details = $this->model_vendor_vendor->getVendorDetails($store_details['vendor_id']);

            //echo "<pre>";print_r($totals);die;
            foreach ($totals as $total) {
                if ('sub_total' == $total['code']) {
                    $sub_total = $total['value'];
                    break;
                }
            }

            if ($this->user->isVendor()) {
                $result['customer'] = strtok($result['firstname'], ' ');
            }

            if ($result['company_name']) {
                $result['company_name'] = ' (' . $result['company_name'] . ')';
            } else {
                // $result['company_name'] = "(NA)";
            }
            $vendor_total = $this->currency->format(($result['total'] - ($result['total'] * $result['commission']) / 100), $this->config->get('config_currency'));
            $this->load->model('localisation/order_status');
            $data['orders'][] = [
                'order_id' => $result['order_id'],
                'delivery_id' => $result['delivery_id'],
                'order_prefix' => $vendor_details['orderprefix'] != '' ? $vendor_details['orderprefix'] . '-' : '',
                'vendor_name' => $vendor_details['lastname'],
                'customer' => $result['customer'],
                'company_name' => $result['company_name'],
                'status' => $result['status'],
                'payment_method' => $result['payment_method'],
                'shipping_method' => $result['shipping_method'],
                'shipping_address' => $result['shipping_address'],
                'delivery_date' => date($this->language->get('date_format_short'), strtotime($result['delivery_date'])),
                'delivery_timeslot' => $result['delivery_timeslot'],
                'store' => $result['store_name'],
                'order_status_id' => $result['order_status_id'],
                'vendor_order_status_id' => $result['vendor_order_status_id'],
                'order_status_color' => $result['color'],
                'city' => $result['city'],
                'vendor_total' => $vendor_total,
                'total' => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
                'sub_total' => $this->currency->format($sub_total, $result['currency_code'], $result['currency_value']),
                'sub_total_custom' => $sub_total, $result['currency_code'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
                'shipping_code' => $result['shipping_code'],
                'view' => $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
                'invoice' => $this->url->link('sale/order/invoice', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
                'products_list' => $this->url->link('sale/order/productlist', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
                'order_spreadsheet' => $this->url->link('sale/order/orderCalculationSheet', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
                'shipping' => $this->url->link('sale/order/shipping', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
                'edit' => $this->url->link('sale/editinvoice/EditInvoice', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
                'delete' => $this->url->link('sale/order/delete', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
                'po_number' => $result['po_number'],
                'SAP_customer_no' => $result['SAP_customer_no'],
                'SAP_doc_no' => $result['SAP_doc_no'],
                'all_order_statuses' => $this->model_localisation_order_status->getOrderStatuses(),
                'paid' => $result['paid'],
                'amount_partialy_paid' => $result['amount_partialy_paid'],
                'delivery_charges' => $result['delivery_charges'],
                'missing_products_count' => count($missing_products),
                'mpesa_reference' => $result['mpesa_reference'],
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
        $data['column_delivery_time_slot'] = $this->language->get('column_delivery_time_slot');

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
        $data['button_status_update'] = 'Update Status To Order Processing.';
        $data['button_status_update_transit'] = 'Update Order Status To In Transit.';
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

        if (isset($this->request->get['filter_delivery_time_slot'])) {
            $url .= '&filter_delivery_time_slot=' . urlencode(html_entity_decode($this->request->get['filter_delivery_time_slot'], ENT_QUOTES, 'UTF-8'));
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

        if ('ASC' == $order) {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=o.order_id' . $url, 'SSL');
        $data['sort_city'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=c.name' . $url, 'SSL');
        $data['sort_customer'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, 'SSL');
        $data['sort_status'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
        $data['sort_total'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=o.total' . $url, 'SSL');
        $data['sort_date_added'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=o.date_added' . $url, 'SSL');
        $data['sort_date_modified'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=o.date_modified' . $url, 'SSL');

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

        if (isset($this->request->get['filter_delivery_time_slot'])) {
            $url .= '&filter_delivery_time_slot=' . urlencode(html_entity_decode($this->request->get['filter_delivery_time_slot'], ENT_QUOTES, 'UTF-8'));
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
        $pagination->url = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

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
        $data['filter_delivery_time_slot'] = $filter_delivery_time_slot;
        $data['filter_payment'] = $filter_payment;

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

        $this->load->model('vehicles/vehicles');
        $vehicles = $this->model_vehicles_vehicles->getAllVehicles();
        $data['vehicles'] = $vehicles;
        $this->load->model('setting/store');
        $deliveryTimeslots = $this->model_setting_store->getDeliveryTimeslots(75);
        $data['time_slots'] = $deliveryTimeslots;
        $this->load->language('vehicles/vehicles');

        $data['entry_make'] = $this->language->get('entry_make');
        $data['entry_model'] = $this->language->get('entry_model');
        $data['entry_registration_number'] = $this->language->get('entry_registration_number');
        $data['entry_registration_date'] = $this->language->get('entry_registration_date');

        $data['entry_registration_validity_upto'] = $this->language->get('entry_registration_validity_upto');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_date_added'] = $this->language->get('entry_date_added');
        $data['vendor_order_statuses'] = $this->model_localisation_order_status->getVendorOrderStatuses();
        $this->response->setOutput($this->load->view('sale/pezesha_order_list.tpl', $data));
    }

    public function getUserByName($name) {
        if ($name) {
            $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . "user` u WHERE CONCAT(u.firstname,' ',u.lastname) LIKE '" . $this->db->escape($name) . "%'");

            return $query->row['user_id'];
        }
    }

    public function getUser($id) {
        if ($id) {
            $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . "customer`  WHERE customer_id ='" . $id . "'");

            return $query->row['fax'];
        }
    }

}
