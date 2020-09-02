<?php

class ControllerShopperRequest extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('shopper/request');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('shopper/request');

        $this->getList();
    }

    /*
     * Shopper accept order to deliver
     */
    public function accept()
    {
        $this->load->language('shopper/request');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('shopper/request');

        if (isset($this->request->get['request_id'])) {
            $this->model_shopper_request->accept($this->request->get['request_id']);
        }

        $this->session->data['success'] = $this->language->get('text_success');

        $url = '';

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id='.$this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_vendor_order_id'])) {
            $url .= '&filter_vendor_order_id='.$this->request->get['filter_vendor_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer='.urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_store_name'])) {
            $url .= '&filter_store_name='.urlencode(html_entity_decode($this->request->get['filter_store_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_delivery_date'])) {
            $url .= '&filter_delivery_date='.$this->request->get['filter_delivery_date'];
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status='.$this->request->get['filter_order_status'];
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total='.$this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added='.$this->request->get['filter_date_added'];
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

        $this->response->redirect($this->url->link('shopper/request', 'token='.$this->session->data['token'].$url, 'SSL'));
    }

    public function delete()
    {
        $this->load->language('shopper/request');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('shopper/request');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $order_id) {
                $this->model_shopper_request->deleteRequest($order_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort='.$this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order='.$this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page='.$this->request->get['page'];
            }

            $this->response->redirect($this->url->link('shopper/request', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getList();
    }

    protected function getList()
    {
        if (isset($this->request->get['filter_store_name'])) {
            $filter_store_name = $this->request->get['filter_store_name'];
        } else {
            $filter_store_name = null;
        }

        if (isset($this->request->get['filter_delivery_date'])) {
            $filter_delivery_date = $this->request->get['filter_delivery_date'];
        } else {
            $filter_delivery_date = null;
        }

        if (isset($this->request->get['filter_vendor_order_id'])) {
            $filter_vendor_order_id = $this->request->get['filter_vendor_order_id'];
        } else {
            $filter_vendor_order_id = null;
        }

        if (isset($this->request->get['filter_order_id'])) {
            $filter_order_id = $this->request->get['filter_order_id'];
        } else {
            $filter_order_id = null;
        }

        if (isset($this->request->get['filter_customer'])) {
            $filter_customer = $this->request->get['filter_customer'];
        } else {
            $filter_customer = null;
        }

        if (isset($this->request->get['filter_order_status'])) {
            $filter_order_status = $this->request->get['filter_order_status'];
        } else {
            $filter_order_status = null;
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

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'o.vendor_order_id';
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

        if (isset($this->request->get['filter_vendor_order_id'])) {
            $url .= '&filter_vendor_order_id='.$this->request->get['filter_vendor_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer='.urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_store_name'])) {
            $url .= '&filter_store_name='.urlencode(html_entity_decode($this->request->get['filter_store_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_delivery_date'])) {
            $url .= '&filter_delivery_date='.$this->request->get['filter_delivery_date'];
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status='.$this->request->get['filter_order_status'];
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total='.$this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added='.$this->request->get['filter_date_added'];
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
            'href' => $this->url->link('shopper/request', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        $data['invoice'] = $this->url->link('sale/order/invoice', 'token='.$this->session->data['token'], 'SSL');
        $data['shipping'] = $this->url->link('sale/order/shipping', 'token='.$this->session->data['token'], 'SSL');
        $data['add'] = $this->url->link('sale/order/add', 'token='.$this->session->data['token'], 'SSL');

        $data['orders'] = [];

        $filter_data = [
            'filter_order_id' => $filter_order_id,
            'filter_vendor_order_id' => $filter_vendor_order_id,
            'filter_customer' => $filter_customer,
            'filter_store_name' => $filter_store_name,
            'filter_delivery_date' => $filter_delivery_date,
            'filter_order_status' => $filter_order_status,
            'filter_total' => $filter_total,
            'filter_date_added' => $filter_date_added,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        $order_total = $this->model_shopper_request->getTotalOrders($filter_data);

        $results = $this->model_shopper_request->getOrders($filter_data);

        foreach ($results as $result) {
            $data['orders'][] = [
                'request_id' => $result['request_id'],
                'order_id' => $result['order_id'],
                'vendor_order_id' => $result['vendor_order_id'],
                'store_name' => $result['store_name'],
                'delivery_date' => $result['delivery_date'],
                'delivery_timeslot' => $result['delivery_timeslot'],
                'status' => $result['status'],
                'total' => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'accept' => $this->url->link('shopper/request/accept', 'request_id='.$result['request_id'].'&token='.$this->session->data['token'].$url, 'SSL'),
            ];
        }

        $data['invoice'] = $this->url->link('shopper/request/invoice', 'token='.$this->session->data['token'], 'SSL');
        $data['delete'] = $this->url->link('shopper/request/delete', 'token='.$this->session->data['token'].$url, 'SSL');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_missing'] = $this->language->get('text_missing');
        $data['text_heading'] = $this->language->get('text_heading');

        $data['column_vendor_order_id'] = $this->language->get('column_vendor_order_id');
        $data['column_store'] = $this->language->get('column_store');
        $data['column_delivery_date'] = $this->language->get('column_delivery_date');
        $data['column_delivery_timeslot'] = $this->language->get('column_delivery_timeslot');
        $data['column_order_id'] = $this->language->get('column_order_id');
        $data['column_customer'] = $this->language->get('column_customer');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_action'] = $this->language->get('column_action');

        $data['entry_return_id'] = $this->language->get('entry_return_id');
        $data['entry_order_id'] = $this->language->get('entry_order_id');
        $data['entry_customer'] = $this->language->get('entry_customer');
        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_total'] = $this->language->get('entry_total');
        $data['entry_date_added'] = $this->language->get('entry_date_added');

        $data['button_invoice_invoice'] = $this->language->get('button_invoice_invoice');
        $data['button_shipping_invoice'] = $this->language->get('button_shipping_invoice');
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

        if (isset($this->request->get['filter_store_name'])) {
            $url .= '&filter_store_name='.urlencode(html_entity_decode($this->request->get['filter_store_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_delivery_date'])) {
            $url .= '&filter_delivery_date='.$this->request->get['filter_delivery_date'];
        }

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id='.$this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer='.urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status='.$this->request->get['filter_order_status'];
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total='.$this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added='.$this->request->get['filter_date_added'];
        }

        if ('ASC' == $order) {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        $data['sort_order'] = $this->url->link('shopper/request', 'token='.$this->session->data['token'].'&sort=o.order_id'.$url, 'SSL');
        $data['sort_vendor_order_id'] = $this->url->link('shopper/request', 'token='.$this->session->data['token'].'&sort=o.vendor_order_id'.$url, 'SSL');
        $data['sort_store_name'] = $this->url->link('shopper/request', 'token='.$this->session->data['token'].'&sort=o.store_name'.$url, 'SSL');
        $data['sort_delivery_date'] = $this->url->link('shopper/request', 'token='.$this->session->data['token'].'&sort=o.delivery_date'.$url, 'SSL');
        $data['sort_customer'] = $this->url->link('shopper/request', 'token='.$this->session->data['token'].'&sort=customer'.$url, 'SSL');
        $data['sort_status'] = $this->url->link('shopper/request', 'token='.$this->session->data['token'].'&sort=status'.$url, 'SSL');
        $data['sort_total'] = $this->url->link('shopper/request', 'token='.$this->session->data['token'].'&sort=o.total'.$url, 'SSL');
        $data['sort_date_added'] = $this->url->link('shopper/request', 'token='.$this->session->data['token'].'&sort=o.date_added'.$url, 'SSL');

        $url = '';

        if (isset($this->request->get['filter_store_name'])) {
            $url .= '&filter_store_name='.urlencode(html_entity_decode($this->request->get['filter_store_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_delivery_date'])) {
            $url .= '&filter_delivery_date='.$this->request->get['filter_delivery_date'];
        }

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id='.$this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer='.urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status='.$this->request->get['filter_order_status'];
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total='.$this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added='.$this->request->get['filter_date_added'];
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
        $pagination->url = $this->url->link('shopper/request', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

        $data['filter_order_id'] = $filter_order_id;
        $data['filter_store_name'] = $filter_store_name;
        $data['filter_delivery_date'] = $filter_delivery_date;
        $data['filter_customer'] = $filter_customer;
        $data['filter_order_status'] = $filter_order_status;
        $data['filter_total'] = $filter_total;
        $data['filter_date_added'] = $filter_date_added;

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('shopper/common/header');
        $data['footer'] = $this->load->controller('shopper/common/footer');

        $this->response->setOutput($this->load->view('shopper/request_list.tpl', $data));
    }

    public function invoice()
    {
        $this->load->language('sale/order');
        $this->load->model('shopper/request');

        $data['title'] = $this->language->get('text_invoice');

        if ($this->request->server['HTTPS']) {
            $data['base'] = HTTPS_SERVER;
        } else {
            $data['base'] = HTTP_SERVER;
        }

        $data['direction'] = $this->language->get('direction');
        $data['lang'] = $this->language->get('code');

        $data['text_invoice'] = $this->language->get('text_invoice');
        $data['text_order_detail'] = $this->language->get('text_order_detail');
        $data['text_order_id'] = $this->language->get('text_order_id');
        $data['text_invoice_no'] = $this->language->get('text_invoice_no');
        $data['text_invoice_date'] = $this->language->get('text_invoice_date');
        $data['text_date_added'] = $this->language->get('text_date_added');
        $data['text_telephone'] = $this->language->get('text_telephone');
        $data['text_fax'] = $this->language->get('text_fax');
        $data['text_email'] = $this->language->get('text_email');
        $data['text_website'] = $this->language->get('text_website');
        $data['text_to'] = $this->language->get('text_to');
        $data['text_ship_to'] = $this->language->get('text_ship_to');
        $data['text_payment_method'] = $this->language->get('text_payment_method');
        $data['text_shipping_method'] = $this->language->get('text_shipping_method');

        $data['column_product'] = $this->language->get('column_product');
        $data['column_model'] = $this->language->get('column_model');
        $data['column_quantity'] = $this->language->get('column_quantity');
        $data['column_price'] = $this->language->get('column_price');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_comment'] = $this->language->get('column_comment');

        $this->load->model('sale/order');

        $this->load->model('setting/setting');

        $data['orders'] = [];

        $orders = $requests = [];

        if (isset($this->request->post['selected'])) {
            $requests = $this->request->post['selected'];
        } elseif (isset($this->request->get['requests_id'])) {
            $requests[] = $this->request->get['requests_id'];
        }

        foreach ($requests as $request_id) {
            $order_info = $this->model_shopper_request->getOrder($request_id);

            if ($order_info) {
                $store_id = $order_info['store_id'];

                $store_info = $this->model_shopper_request->getStoreDatas($order_info['store_id']);

                if ($store_info) {
                    $store_address = $store_info['address'];
                    $store_email = $store_info['email'];
                    $store_telephone = $store_info['telephone'];
                    $store_fax = $store_info['fax'];
                } else {
                    $store_address = '';
                    $store_email = '';
                    $store_telephone = '';
                    $store_fax = '';
                }

                $invoice_no = '';

                $this->load->model('tool/upload');

                $product_data = [];
                $products = $this->model_shopper_request->getOrderProducts($store_id, $order_info['order_id']);

                foreach ($products as $product) {
                    $product_data[] = [
                        'name' => $product['name'],
                        'model' => $product['model'],
                        'option' => [],
                        'quantity' => $product['quantity'],
                        'price' => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                        'total' => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
                    ];
                }

                $total_data = [];

                $totals = $this->model_shopper_request->getVendorOrderTotal($store_id, $order_info['order_id']);

                foreach ($totals as $total) {
                    $total_data[] = [
                        'title' => $total['title'],
                        'text' => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
                    ];
                }

                $data['orders'][] = [
                    'order_id' => $order_info['vendor_order_id'],
                    'invoice_no' => $invoice_no,
                    'date_added' => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
                    'store_name' => $order_info['store_name'],
                    'store_address' => nl2br($store_address),
                    'store_email' => $store_email,
                    'store_telephone' => $store_telephone,
                    'store_fax' => $store_fax,
                    'email' => $order_info['email'],
                    'telephone' => $order_info['telephone'],
                    'shipping_address' => $order_info['shipping_address'],
                    'shipping_city' => $order_info['shipping_city'],
                    'shipping_contact_no' => $order_info['shipping_contact_no'],
                    'shipping_name' => $order_info['shipping_name'],
                    'shipping_method' => $order_info['shipping_method'],
                    'payment_method' => $order_info['payment_method'],
                    'product' => $product_data,
                    'total' => $total_data,
                    'comment' => nl2br($order_info['comment']),
                ];
            }
        }

        $this->response->setOutput($this->load->view('shopper/order_invoice.tpl', $data));
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'shopper/request')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}
