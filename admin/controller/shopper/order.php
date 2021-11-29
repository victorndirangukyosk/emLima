<?php

class ControllerShopperOrder extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('shopper/order');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('shopper/order');

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

        $order_total = $this->model_shopper_order->getTotalOrders($filter_data);

        $results = $this->model_shopper_order->getOrders($filter_data);

        foreach ($results as $result) {
            $data['orders'][] = [
                'order_id' => $result['order_id'],
                'order_status_id' => $result['order_status_id'],
                'vendor_order_id' => $result['vendor_order_id'],
                'store_name' => $result['store_name'],
                'delivery_date' => $result['delivery_date'],
                'delivery_timeslot' => $result['delivery_timeslot'],
                'status' => $result['status'],
                'shopper_commision' => $result['shopper_commision'],
                'track_info' => $this->url->link('shopper/order/track_info', 'vendor_order_id='.$result['vendor_order_id'].'&token='.$this->session->data['token'], 'SSL'),
                'track' => $this->url->link('shopper/order/track', 'vendor_order_id='.$result['vendor_order_id'].'&token='.$this->session->data['token'], 'SSL'),
                'total' => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
            ];
        }

        $data['invoice'] = $this->url->link('shopper/order/invoice', 'token='.$this->session->data['token'], 'SSL');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_missing'] = $this->language->get('text_missing');
        $data['text_heading'] = $this->language->get('text_heading');
        $data['text_details'] = $this->language->get('text_details');

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

        //track.tpl
        $data['text_total_distance'] = $this->language->get('text_total_distance');
        $data['text_total_commision'] = $this->language->get('text_total_commision');
        $data['button_order_shipped'] = $this->language->get('button_order_shipped');
        $data['text_order_info'] = $this->language->get('text_order_info');
        $data['column_vendor_order_id'] = $this->language->get('column_vendor_order_id');
        $data['column_delivery_date'] = $this->language->get('column_delivery_date');
        $data['column_payment_method'] = $this->language->get('column_payment_method');
        $data['column_total'] = $this->language->get('column_total');
        $data['text_customer_info'] = $this->language->get('text_customer_info');
        $data['column_contact_no'] = $this->language->get('column_contact_no');
        $data['column_name'] = $this->language->get('column_name');
        $data['column_address'] = $this->language->get('column_address');
        $data['column_delivery_date'] = $this->language->get('column_delivery_date');
        $data['text_store_info'] = $this->language->get('text_store_info');

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

        $data['sort_order'] = $this->url->link('shopper/order', 'token='.$this->session->data['token'].'&sort=o.order_id'.$url, 'SSL');
        $data['sort_vendor_order_id'] = $this->url->link('shopper/order', 'token='.$this->session->data['token'].'&sort=o.vendor_order_id'.$url, 'SSL');
        $data['sort_store_name'] = $this->url->link('shopper/order', 'token='.$this->session->data['token'].'&sort=o.store_name'.$url, 'SSL');
        $data['sort_delivery_date'] = $this->url->link('shopper/order', 'token='.$this->session->data['token'].'&sort=o.delivery_date'.$url, 'SSL');
        $data['sort_customer'] = $this->url->link('shopper/order', 'token='.$this->session->data['token'].'&sort=customer'.$url, 'SSL');
        $data['sort_status'] = $this->url->link('shopper/order', 'token='.$this->session->data['token'].'&sort=status'.$url, 'SSL');
        $data['sort_total'] = $this->url->link('shopper/order', 'token='.$this->session->data['token'].'&sort=o.total'.$url, 'SSL');
        $data['sort_date_added'] = $this->url->link('shopper/order', 'token='.$this->session->data['token'].'&sort=o.date_added'.$url, 'SSL');

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
        $pagination->url = $this->url->link('shopper/order', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

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

        $data['config_processing_status'] = $this->config->get('config_processing_status');

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('shopper/common/header');
        $data['footer'] = $this->load->controller('shopper/common/footer');

        $this->response->setOutput($this->load->view('shopper/order_list.tpl', $data));
    }

    public function invoice()
    {
        $this->load->language('sale/order');
        $this->load->model('shopper/order');
        $this->load->model('shopper/request');
        $data['title'] = $this->language->get('text_invoice');

        if ($this->request->server['HTTPS']) {
            $data['base'] = HTTPS_SERVER;
        } else {
            $data['base'] = HTTP_SERVER;
        }

        $data['direction'] = $this->language->get('direction');
        $data['lang'] = $this->language->get('code');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_contact_no'] = $this->language->get('entry_contact_no');

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

        $orders = [];

        if (isset($this->request->post['selected'])) {
            $orders = $this->request->post['selected'];
        } elseif (isset($this->request->get['request_id'])) {
            $orders[] = $this->request->get['request_id'];
        }

        foreach ($orders as $order_id) {
            $order_info = $this->model_shopper_order->getOrder($order_id);

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

                $products = $this->model_tool_upload->getOrderProducts($store_id, $order_info['order_id']);
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

                $totals = $this->model_tool_upload->getVendorOrderTotal($store_id, $order_info['order_id']);

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

    //save shopper position
    public function saveLatLong()
    {
        if (isset($this->request->post['latitude'])) {
            $latitude = $this->request->post['latitude'];
        } else {
            $latitude = 0;
        }

        if (isset($this->request->post['longitude'])) {
            $longitude = $this->request->post['longitude'];
        } else {
            $longitude = 0;
        }

        if ($latitude && $longitude) {
            $this->load->model('shopper/shopper');

            $this->model_shopper_shopper->updatePosition($latitude, $longitude, $this->user->getId());
        }
    }

    //save shopper position, order_id for order tracking
    //return total commision, total distance
    public function savePosition()
    {
        $this->load->model('shopper/shopper');

        $json = [];

        if (isset($this->request->post['order_id'])) {
            $order_id = $this->request->post['order_id'];
        } else {
            $order_id = 0;
        }

        if (isset($this->request->post['latitude'])) {
            $latitude = $this->request->post['latitude'];
        } else {
            $latitude = 0;
        }

        if (isset($this->request->post['longitude'])) {
            $longitude = $this->request->post['longitude'];
        } else {
            $longitude = 0;
        }

        //get last data
        $last_track_info = $this->model_shopper_shopper->lastPosition($order_id);

        if ($last_track_info) {
            $new_distance = $this->model_shopper_shopper->distance($last_track_info['latitude'], $last_track_info['longitude'], $latitude, $longitude);
            $total_distance = $new_distance + $last_track_info['distance'];
        } else {
            $total_distance = 0;
        }

        $this->model_shopper_shopper->savePosition($order_id, $latitude, $longitude, $total_distance);

        $json['total_distance'] = round($total_distance, 2);

        //total commision
        $json['total_commision'] = round($total_distance * $this->config->get('config_dri_charge'), 2);

        echo json_encode($json);
    }

    //start tracking latitude, longitude
    public function track()
    {
        $this->load->model('shopper/shopper');

        if (isset($this->request->get['vendor_order_id'])) {
            $data['vendor_order_id'] = $this->request->get['vendor_order_id'];
        } else {
            $data['vendor_order_id'] = 0;
        }

        $data = $this->model_shopper_shopper->getOrder($data['vendor_order_id']);

        $data['token'] = $this->session->data['token'];

        $data['header'] = $this->load->controller('shopper/common/header');
        $data['footer'] = $this->load->controller('shopper/common/footer');

        $this->response->setOutput($this->load->view('shopper/track.tpl', $data));
    }

    //display order track info by order_id
    public function track_info()
    {
        $this->load->model('shopper/shopper');

        if (isset($this->request->get['vendor_order_id'])) {
            $order_id = $this->request->get['vendor_order_id'];
        } else {
            $order_id = 0;
        }

        $data = $this->model_shopper_shopper->getTrackInfo($order_id);

        $data['total'] = $this->currency->format($data['total'], $data['currency_code'], $data['currency_value']);

        $data['header'] = $this->load->controller('shopper/common/header');
        $data['footer'] = $this->load->controller('shopper/common/footer');

        $this->response->setOutput($this->load->view('shopper/track_info.tpl', $data));
    }

    //after order shipped
    public function orderShipped()
    {
        $this->load->model('shopper/shopper');

        if (isset($this->request->post['order_id'])) {
            $order_id = $this->request->post['order_id'];
        } else {
            $order_id = 0;
        }

        $this->model_shopper_shopper->orderShipped($order_id);
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'shopper/request')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}
