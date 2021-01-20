<?php

class ControllerAccountIncompleteOrders extends Controller
{
    private $error = [];

    public function index() {
        $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_login.css');

        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/order', '', 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');
        $data['kondutoStatus'] = $this->config->get('config_konduto_status');

        //setlocale (LC_ALL, "pt_BR");

        $this->load->language('account/order');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/account', '', 'SSL'),
        ];

        $url = '';

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('account/order', $url, 'SSL'),
        ];

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_empty'] = $this->language->get('text_empty');

        $data['column_order_id'] = $this->language->get('column_order_id');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_customer'] = $this->language->get('column_customer');
        $data['column_product'] = $this->language->get('column_product');
        $data['column_total'] = $this->language->get('column_total');
        $data['button_view'] = $this->language->get('button_view');
        $data['button_continue'] = $this->language->get('button_continue');
        $data['text_cancel'] = $this->language->get('text_cancel');
        $data['text_placed_on'] = $this->language->get('text_placed_on');
        $data['text_view'] = $this->language->get('text_view');
        $data['text_items_ordered'] = $this->language->get('text_items_ordered');
        $data['text_real_items_ordered'] = $this->language->get('text_real_items_ordered');
        $data['text_refund_text_part1'] = $this->language->get('text_refund_text_part1');
        $data['text_refund_text_part2'] = $this->language->get('text_refund_text_part2');
        $data['text_refund_text_part3'] = $this->language->get('text_refund_text_part3');
        $data['text_delivery_address'] = $this->language->get('text_delivery_address');
        $data['text_payment'] = $this->language->get('text_payment');
        $data['text_payment_options'] = $this->language->get('text_payment_options');
        $data['text_view_billing'] = $this->language->get('text_view_billing');
        $data['text_order_id'] = $this->language->get('text_order_id');
        $data['text_report_issue'] = $this->language->get('text_report_issue');
        $data['text_load_more'] = $this->language->get('text_load_more');
        $data['text_view_order'] = $this->language->get('text_view_order');

        $data['text_coupon_willbe_credited'] = $this->language->get('text_coupon_willbe_credited');
        $data['text_coupon_credited'] = $this->language->get('text_coupon_credited');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['orders'] = [];

        $this->load->model('account/order');
        $this->load->model('account/address');

        $order_total = $this->model_account_order->getTotalIncompleteOrders();

        $results = $this->model_account_order->getIncompleteOrders(($page - 1) * 10, 10);

        //	echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            $city_name = $this->model_account_order->getCityName($result['shipping_city_id']);

            $product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);

            $real_product_total = $this->model_account_order->getTotalRealOrderProductsByOrderId($result['order_id']);

            $order_total_detail = $this->load->controller('checkout/totals/getTotal', $result['order_id']);

            //echo "<pre>";print_r($product_total);die;
            $voucher_total = $this->model_account_order->getTotalOrderVouchersByOrderId($result['order_id']);

            $shipping_address = null;

            if (isset($result['shipping_address'])) {
                $shipping_address['address'] = $result['shipping_building_name'] . ', ' . $result['shipping_flat_number'];
                $shipping_address['city'] = $city_name;
                $shipping_address['zipcode'] = $result['shipping_zipcode'];
                //$order_info['shipping_flat_number'].", ".$order_info['shipping_building_name'].", ".$order_info['shipping_landmark'];
            }

            $shipped = false;
            foreach ($this->config->get('config_processing_status') as $key => $value) {
                if ($value == $result['order_status_id']) {
                    $shipped = true;
                    break;
                }
            }
            /* if(!$shipped) {

              foreach ($this->config->get('config_complete_status') as $key => $value) {
              if($value == $result['order_status_id']) {
              $shipped = true;
              break;
              }
              }
              } */

            $realproducts = $this->model_account_order->hasRealOrderProducts($result['order_id']);

            $total = $result['total'];

            $ordertotals = $this->model_account_order->getOrderTotals($result['order_id']);
            //  echo "<pre>";print_r($ordertotals);die;
            foreach ($ordertotals as $ordertotal) {
                if ('total' == $ordertotal['code']) {
                    $total = $ordertotal['value'];
                }
            }

            $this->load->model('sale/order');
            $approve_order_button = null;
            $order_appoval_access = false;
            if (empty($_SESSION['parent']) && $result['customer_id'] != $this->customer->getId()) {
                $approve_order_button = 'Need Approval';
            }
            if ($this->session->data['order_approval_access'] > 0 && $this->session->data['order_approval_access_role'] != NULL) {
                $order_appoval_access = true;
            }
            $this->load->model('account/customer');
            $customer_info = $this->model_account_customer->getCustomer($result['customer_id']);
            $is_he_parents = $this->model_account_customer->CheckHeIsParent();
            $customer_parent_info = $this->model_account_customer->getCustomerParentDetails($result['customer_id']);

            $sub_user_order = FALSE;
            $procurement_person = NULL;
            $head_chef = NULL;
            if (($customer_info['order_approval_access'] == NULL || $customer_info['order_approval_access'] == 0) && $customer_info['order_approval_access_role'] == NULL && $customer_parent_info != NULL) {
                $log = new Log('error.log');
                $order_approval_access = $this->db->query('SELECT c.customer_id, c.parent, c.order_approval_access_role, c.order_approval_access, c.email, c.firstname, c.lastname  FROM ' . DB_PREFIX . "customer c WHERE c.parent = '" . (int) $customer_parent_info['customer_id'] . "' AND c.order_approval_access = 1 AND (c.order_approval_access_role = 'head_chef' OR c.order_approval_access_role = 'procurement_person')");
                $order_approval_access_user = $order_approval_access->rows;

                foreach ($order_approval_access_user as $order_approval_access_use) {
                    if ($order_approval_access_use['order_approval_access_role'] == 'head_chef' && $order_approval_access_use['order_approval_access'] > 0) {
                        $head_chef = $order_approval_access_use['email'];
                        $log->write($order_approval_access_use['order_approval_access_role']);
                        $log->write($order_approval_access_use['order_approval_access']);
                        $log->write($order_approval_access_use['customer_id']);
                    }
                    if ($order_approval_access_use['order_approval_access_role'] == 'procurement_person' && $order_approval_access_use['order_approval_access'] > 0) {
                        $procurement_person = $order_approval_access_use['email'];
                        $log->write($order_approval_access_use['order_approval_access_role']);
                        $log->write($order_approval_access_use['order_approval_access']);
                        $log->write($order_approval_access_use['customer_id']);
                    }
                }
                $sub_user_order = TRUE;
            }

            $log = new Log('error.log');
            //$log->write('IS HE PARENT USER');
            //$log->write($is_he_parents);
            //$log->write($customer_parent_info);
            //$log->write('IS HE PARENT USER');
            $hours = 0;
            $t1 = strtotime(date('Y-m-d H:i:s'));
            $t2 = strtotime($result['date_added']);
            $diff = $t1 - $t2;
            $hours = $diff / ( 60 * 60 );
            $log->write('hours');
            $log->write(date('Y-m-d H:i:s'));
            $log->write($result['date_added']);
            $log->write($result['payment_code']);
            $log->write(date_default_timezone_get());
            $log->write($hours);
            $log->write('hours');
            
            $data['orders'][] = [
                'order_id' => $result['order_id'],
                'name' => $result['firstname'] . ' ' . $result['lastname'],
                'shipping_name' => $result['shipping_name'],
                'payment_method' => $result['payment_method'],
                'payment_transaction_id' => $this->model_sale_order->getOrderTransactionId($result['order_id']),
                'shipping_address' => $shipping_address,
                'order_total' => $order_total_detail,
                'store_name' => $result['store_name'],
                'status' => $result['status'],
                'order_status_color' => $result['order_status_color'],
                'shipped' => $shipped,
                'realproducts' => $realproducts,
                //'pt_date_added' => strftime( "%h %y",strtotime($result['date_added'])),
                'date_added' => date($this->language->get('date_format'), strtotime($result['date_added'])),
                'time_added' => date($this->language->get('time_format'), strtotime($result['date_added'])),
                'eta_date' => date($this->language->get('date_format'), strtotime($result['delivery_date'])),
                'eta_time' => $result['delivery_timeslot'],
                'products' => ($product_total + $voucher_total),
                'real_products' => ($real_product_total + $voucher_total),
                'total' => $this->currency->format($total, $result['currency_code'], $result['currency_value']),
                'href' => $this->url->link('account/order/info', 'order_id=' . $result['order_id'], 'SSL'),
                'real_href' => $this->url->link('account/order/realinfo', 'order_id=' . $result['order_id'], 'SSL'),
                'accept_reject_href' => $this->url->link('account/order/accept_reject', 'order_id=' . $result['order_id'], 'SSL'),
                'parent_approve_order' => $approve_order_button,
                'customer_id' => $result['customer_id'],
                'parent_approval' => $result['parent_approval'],
                'order_approval_access' => $order_appoval_access,
                'head_chef' => $result['head_chef'],
                'procurement' => $result['procurement'],
                'sub_user_order' => $sub_user_order,
                'procurement_person_email' => $procurement_person,
                'head_chef_email' => $head_chef,
                'order_approval_access_role' => $this->session->data['order_approval_access_role'],
                'parent_details' => $customer_parent_info != NULL && $customer_parent_info['email'] != NULL ? $customer_parent_info['email'] : NULL,
                'edit_order' => 15 == $result['order_status_id'] && (empty($_SESSION['parent']) || $order_appoval_access) ? $this->url->link('account/order/edit_order', 'order_id=' . $result['order_id'], 'SSL') : '',
                'order_company' => isset($customer_info) && null != $customer_info['company_name'] ? $customer_info['company_name'] : null,
                //'edit_own_order' => $this->url->link('checkout/edit_order/index_new', 'order_id=' . $result['order_id'], 'SSL'),
                'edit_own_order' => (($result['order_status_id'] == 15 || $result['order_status_id'] == 14) && $hours < 24 && $result['payment_code'] == 'cod') ? $this->url->link('account/order/edit_your_order', 'order_id=' . $result['order_id'], 'SSL') : NULL,
            ];
        }

        $pagination = new Pagination();
        $pagination->total = $order_total;
        $pagination->page = $page;
        $pagination->limit = 10;
        $pagination->url = $this->url->link('account/order', 'page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        //echo "<pre>";print_r($data['orders']);die;
        $data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($order_total - 10)) ? $order_total : ((($page - 1) * 10) + 10), $order_total, ceil($order_total / 10));

        $data['continue'] = $this->url->link('account/account', '', 'SSL');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/information');

        //echo "<pre>";print_r($this->config->get('config_shipped_status'));die;

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['base'] = $server;

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/order_list.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/order_list.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/account/order_list.tpl', $data));
        }
    }

    public function info() {
        $redirectNotLogin = true;
        $this->load->language('account/order');
        $this->load->language('account/return');

        $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_login.css');

        if (isset($this->request->get['order_id'])) {
            $order_id = $this->request->get['order_id'];
        } else {
            $order_id = 0;
        }
        if (false == is_numeric($order_id)) {
            $order_id = base64_decode(trim($order_id));
            $order_id = preg_replace('/[^A-Za-z0-9\-]/', '', $order_id);
            $this->request->get['order_id'] = $order_id;
            $redirectNotLogin = false;
            //$this->response->redirect($this->url->link('account/order/info', 'order_id=' . $order_id, 'SSL'));
        }

        $data['kondutoStatus'] = $this->config->get('config_konduto_status');

        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        $this->document->setTitle($this->language->get('heading_title'));

        if (!$this->customer->isLogged() && (true == $redirectNotLogin)) {
            $this->session->data['redirect'] = $this->url->link('account/order/info', 'order_id=' . $order_id, 'SSL');
            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $this->load->model('account/order');
        if (false == $redirectNotLogin) {
            $order_info = $this->model_account_order->getOrder($order_id, true);
        } else {
            $order_info = $this->model_account_order->getOrder($order_id);
        }
        //echo "<pre>";print_r($order_info);die;

        $data['cashback_condition'] = $this->language->get('cashback_condition');

        if ($order_info) {
            $data['cashbackAmount'] = $this->currency->format(0);

            $coupon_history_data = $this->model_account_order->getCashbackAmount($order_id);

            if (count($coupon_history_data) > 0) {
                $data['cashbackAmount'] = $this->currency->format((-1 * $coupon_history_data['amount']));
            }

            $this->document->setTitle($this->language->get('text_order'));

            $data['breadcrumbs'] = [];

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/home'),
            ];

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_account'),
                'href' => $this->url->link('account/account', '', 'SSL'),
            ];

            $url = '';

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('account/order', $url, 'SSL'),
            ];

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_order'),
                'href' => $this->url->link('account/order/info', 'order_id=' . $this->request->get['order_id'] . $url, 'SSL'),
            ];

            $data['text_go_back'] = $this->language->get('text_go_back');
            $data['text_order_id_with_colon'] = $this->language->get('text_order_id_with_colon');
            $data['text_items'] = $this->language->get('text_items');
            $data['text_products'] = $this->language->get('text_products');

            $data['text_coupon_willbe_credited'] = $this->language->get('text_coupon_willbe_credited');
            $data['text_coupon_credited'] = $this->language->get('text_coupon_credited');

            $data['heading_title'] = $this->language->get('heading_title');
            $data['text_order_detail'] = $this->language->get('text_order_detail');
            $data['text_invoice_no'] = $this->language->get('text_invoice_no');
            $data['text_order_id'] = $this->language->get('text_order_id');
            $data['text_date_added'] = $this->language->get('text_date_added');
            $data['text_shipping_method'] = $this->language->get('text_shipping_method');
            $data['text_shipping_address'] = $this->language->get('text_shipping_address');
            $data['text_payment_method'] = $this->language->get('text_payment_method');
            $data['text_payment_address'] = $this->language->get('text_payment_address');
            $data['text_history'] = $this->language->get('text_history');
            $data['text_comment'] = $this->language->get('text_comment');
            $data['text_processing'] = $this->language->get('text_processing');
            $data['text_shipped'] = $this->language->get('text_shipped');
            $data['text_delivered'] = $this->language->get('text_delivered');
            $data['text_name'] = $this->language->get('text_name');
            $data['text_contact_no'] = $this->language->get('text_contact_no');
            $data['text_estimated_datetime'] = $this->language->get('text_estimated_datetime');
            $data['text_cancel'] = $this->language->get('text_cancel');

            $data['column_name'] = $this->language->get('column_name');

            $data['column_image'] = $this->language->get('column_image');

            $data['column_unit'] = $this->language->get('column_unit');

            $data['column_model'] = $this->language->get('column_model');
            $data['column_quantity'] = $this->language->get('column_quantity');
            $data['column_price'] = $this->language->get('column_price');
            $data['column_total'] = $this->language->get('column_total');
            $data['column_action'] = $this->language->get('column_action');
            $data['column_date_added'] = $this->language->get('column_date_added');
            $data['column_status'] = $this->language->get('column_status');
            $data['column_comment'] = $this->language->get('column_comment');

            $data['button_reorder'] = $this->language->get('button_reorder');
            $data['button_return'] = $this->language->get('button_return');
            $data['button_continue'] = $this->language->get('button_continue');

            $data['delivered'] = false;
            $data['coupon_cashback'] = false;

            $data['can_return'] = false;

            if (isset($order_info['date_modified'])) {
                $start = date('Y-m-d H:i:s');

                //echo "<pre>";print_r($order_info['date_modified']);die;
                //$end = date_create($order_info['date_modified']);
                $end = $order_info['date_modified'];

                $timeFirst = strtotime($start);
                $timeSecond = strtotime($end);

                //echo "<pre>";print_r($start."Cer");print_r($end);die;
                $differenceInSeconds = $timeFirst - $timeSecond;

                //echo "<pre>";print_r($this->config->get('config_return_timeout'));die;
                if ($differenceInSeconds <= $this->config->get('config_return_timeout')) {
                    $data['can_return'] = true;
                }
                //echo "<pre>";print_r($differenceInSeconds);die;
            }

            foreach ($this->config->get('config_complete_status') as $key => $value) {
                if ($value == $order_info['order_status_id']) {
                    $data['delivered'] = true;
                    $data['coupon_cashback'] = true;
                    break;
                }
            }

            if (isset($this->session->data['error'])) {
                $data['error_warning'] = $this->session->data['error'];

                unset($this->session->data['error']);
            } else {
                $data['error_warning'] = '';
            }

            if (isset($this->session->data['success'])) {
                $data['success'] = $this->session->data['success'];

                unset($this->session->data['success']);
            } else {
                $data['success'] = '';
            }

            if ($order_info['invoice_no']) {
                $data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
            } else {
                $data['invoice_no'] = '';
            }

            if ($order_info['settlement_amount']) {
                $data['settlement_amount'] = $this->currency->format($order_info['settlement_amount']);
            } else {
                $data['settlement_amount'] = null;
            }

            $data['text_rating'] = $this->language->get('text_rating');
            $data['text_review'] = $this->language->get('text_review');
            $data['text_send'] = $this->language->get('text_send');

            $data['text_send_rating'] = $this->language->get('text_send_rating');
            $data['text_remaining'] = $this->language->get('text_remaining');
            $data['text_intransit'] = $this->language->get('text_intransit');
            $data['text_completed'] = $this->language->get('text_completed');
            $data['text_cancelled'] = $this->language->get('text_cancelled');

            $data['text_not_avialable'] = $this->language->get('text_not_avialable');
            $data['text_picked'] = $this->language->get('text_picked');
            $data['text_replaced'] = $this->language->get('text_replaced');
            $data['text_delivery_detail'] = $this->language->get('text_delivery_detail');
            $data['text_no_delivery_alloted'] = $this->language->get('text_no_delivery_alloted');
            $data['text_real_amount'] = $this->language->get('text_real_amount');

            $data['text_replacable_title'] = $this->language->get('text_replacable_title');
            $data['text_not_replacable_title'] = $this->language->get('text_not_replacable_title');
            $data['text_replacable'] = $this->language->get('text_replacable');
            $data['text_not_replacable'] = $this->language->get('text_not_replacable');
            $data['order_id'] = $this->request->get['order_id'];
            $data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_modified']));

            $data['payment_method'] = $order_info['payment_method'];

            $data['shipping_name'] = $order_info['shipping_name'];
            $data['shipping_contact_no'] = $order_info['shipping_contact_no'];

            $data['shipping_address'] = $order_info['shipping_flat_number'] . ', ' . $order_info['shipping_building_name'] . ', ' . $order_info['shipping_landmark'];

            $data['shipping_method'] = $order_info['shipping_method'];
            $data['shipping_city'] = $order_info['shipping_city'];

            $data['delivery_timeslot'] = $order_info['delivery_timeslot'];

            $data['order_status_id'] = $order_info['order_status_id'];

            $data['delivery_date'] = $order_info['delivery_date'];

            $data['store_name'] = $order_info['store_name'];
            $data['store_address'] = $order_info['store_address'];
            $data['status'] = $order_info['status'];

            $this->load->model('assets/product');
            $this->load->model('tool/upload');

            $data['email'] = $this->config->get('config_delivery_username');
            $data['password'] = $this->config->get('config_delivery_secret');

            $data['delivery_id'] = $order_info['delivery_id']; //"del_XPeEGFX3Hc4ZeWg5";//

            $data['rating'] = is_null($order_info['rating']) ? 0 : $order_info['rating']; //"del_XPeEGFX3Hc4ZeWg5";//
            //echo "<pre>";print_r($data['rating']);die;
            //$data['delivery_id'] =  26;
            $data['shopper_link'] = $this->config->get('config_shopper_link') . '/storage/';

            $data['products_status'] = [];
            $data['delivery_data'] = [];

            $log = new Log('error.log');

            if (isset($data['delivery_id'])) {
                $response = $this->load->controller('deliversystem/deliversystem/getToken', $data);

                if ($response['status']) {
                    $data['token'] = $response['token'];
                    $productStatus = $this->load->controller('deliversystem/deliversystem/getProductStatus', $data);

                    //echo "<pre>";print_r($productStatus);die;
                    $resp = $this->load->controller('deliversystem/deliversystem/getDeliveryStatus', $data);
                    //echo "<pre>";print_r($resp);die;
                    //$data['delivery_id'] = '';
                    if (!$resp['status'] || isset($resp['error'])) {
                        $data['delivery_data'] = [];
                    } else {
                        $data['delivery_data'] = $resp['data'][0];

                        //delivery_data->delivery_id
                    }

                    if (!$productStatus['status'] || !(count($productStatus['data']) > 0)) {
                        $data['products_status'] = [];
                    } else {
                        $data['products_status'] = $productStatus['data'];
                    }

                    $log->write('order log');
                    $log->write($data['products_status']);

                    //echo "<pre>";print_r($data['products_status']);die;
                }
            }

            // Products
            $data['products'] = [];

            $products = $this->model_account_order->getOrderProducts($this->request->get['order_id']);

            //echo "<pre>";print_r($products);die;
            $returnProductCount = 0;
            foreach ($products as $product) {
                $option_data = [];

                $options = $this->model_account_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);

                foreach ($options as $option) {
                    if ('file' != $option['type']) {
                        $value = $option['value'];
                    } else {
                        $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

                        if ($upload_info) {
                            $value = $upload_info['name'];
                        } else {
                            $value = '';
                        }
                    }

                    $option_data[] = [
                        'name' => $option['name'],
                        'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value),
                    ];
                }

                $product_info = $this->model_assets_product->getDetailproduct($product['product_id']);

                if ($product_info) {
                    $reorder = $this->url->link('account/order/reorder', 'order_id=' . $order_id . '&order_product_id=' . $product['order_product_id'], 'SSL');
                } else {
                    $reorder = '';
                }

                $this->load->model('tool/image');

                if ($product['image'] != NULL && file_exists(DIR_IMAGE . $product['image'])) {
                    $image = $this->model_tool_image->resize($product['image'], 80, 100);
                } else if ($product['image'] == NULL || !file_exists(DIR_IMAGE . $product['image'])) {
                    $image = $this->model_tool_image->resize('placeholder.png', 80, 100);
                }

                $return_status = '';

                if (isset($product['return_id']) && !is_null($product['return_id'])) {
                    $this->load->model('account/return');

                    //$returnDetails = $this->model_account_return->getReturnHistories($product['return_id']);
                    $returnDetails = $this->model_account_return->getReturn($product['return_id']);

                    if (count($returnDetails) > 0) {
                        $return_status = $returnDetails['status'];
                    }
                } else {
                    $returnProductCount = $returnProductCount + 1;
                }

                $data['products'][] = [
                    'product_id' => $product['product_id'],
                    'store_id' => $product['store_id'],
                    'vendor_id' => $product['vendor_id'],
                    'name' => $product['name'],
                    'unit' => $product['unit'],
                    'model' => $product['model'],
                    'product_type' => $product['product_type'],
                    'image' => $image,
                    'option' => $option_data,
                    'return_id' => $product['return_id'],
                    'return_status' => $return_status,
                    'quantity' => $product['quantity'],
                    'price' => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                    'total' => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
                    'reorder' => $reorder,
                    'return' => $this->url->link('account/return/add', 'order_id=' . $order_info['order_id'] . '&product_id=' . $product['product_id'], 'SSL'),
                ];
            }

            $log->write($data['products']);
            // Voucher
            $data['vouchers'] = [];

            $vouchers = $this->model_account_order->getOrderVouchers($this->request->get['order_id']);

            foreach ($vouchers as $voucher) {
                $data['vouchers'][] = [
                    'description' => $voucher['description'],
                    'amount' => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
                ];
            }

            // Totals
            $data['totals'] = [];

            $totals = $this->model_account_order->getOrderTotals($this->request->get['order_id']);

            $data['newTotal'] = $this->currency->format(0);

            //echo "<pre>";print_r($totals);die;
            foreach ($totals as $total) {
                $data['totals'][] = [
                    'title' => $total['title'],
                    'text' => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
                ];

                if ('sub_total' == $total['code']) {
                    $data['subtotal'] = $total['value'];
                }
                if ('total' == $total['code']) {
                    $temptotal = $total['value'];
                }

                $data['plain_settlement_amount'] = $order_info['settlement_amount'];
                if (isset($data['settlement_amount']) && isset($data['subtotal']) && isset($temptotal)) {
                    $data['newTotal'] = $this->currency->format($temptotal - $data['subtotal'] + $order_info['settlement_amount']);
                }
            }

            $data['comment'] = nl2br($order_info['comment']);

            // History
            $data['histories'] = [];

            $results = $this->model_account_order->getOrderHistories($this->request->get['order_id']);

            foreach ($results as $result) {
                $data['histories'][] = [
                    'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                    'status' => $result['status'],
                    'comment' => $result['notify'] ? nl2br($result['comment']) : '',
                ];
            }

            if ($this->request->server['HTTPS']) {
                $server = $this->config->get('config_ssl');
            } else {
                $server = $this->config->get('config_url');
            }

            $data['base'] = $server;

            $data['continue'] = $this->url->link('account/order', '', 'SSL');

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header/orderSummaryHeader');

            $data['total_products'] = count($data['products']);
            $data['total_quantity'] = 0;
            foreach ($data['products'] as $product) {
                $data['total_quantity'] += $product['quantity'];
            }

            $data['show_rating'] = false;
            $data['take_rating'] = false;

            if (in_array($data['order_status_id'], $this->config->get('config_complete_status'))) {
                $data['show_rating'] = false;

                if (is_null($data['rating']) || empty($data['rating'])) {
                    $data['take_rating'] = false;
                }
            }

            $this->load->model('localisation/return_reason');
            $data['entry_reason'] = $this->language->get('entry_reason');
            $data['entry_return_action'] = 'Desired Action';
            $data['entry_opened'] = $this->language->get('entry_opened');
            $data['entry_fault_detail'] = $this->language->get('entry_fault_detail');
            $data['text_yes'] = $this->language->get('text_yes');
            $data['text_no'] = $this->language->get('text_no');
            $data['return_reasons'] = $this->model_localisation_return_reason->getReturnReasons();
            $data['return_actions'] = $this->model_localisation_return_reason->getReturnActions();
            $data['button_submit'] = $this->language->get('button_submit');
            $data['button_back'] = $this->language->get('button_back');
            $data['action'] = $this->url->link('account/return/multipleproducts', '', 'SSL');
            $data['returnProductCount'] = $returnProductCount;
            if ($this->config->get('config_return_id')) {
                $this->load->model('assets/information');

                $information_info = $this->model_assets_information->getInformation($this->config->get('config_return_id'));

                if ($information_info) {
                    $data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_return_id'), 'SSL'), $information_info['title'], $information_info['title']);
                } else {
                    $data['text_agree'] = '';
                }
            } else {
                $data['text_agree'] = '';
            }
            //echo "<pre>";print_r($data);die;
            
            $this->load->model('drivers/drivers');
            $this->load->model('executives/executives');
            $order_driver_details = $this->model_drivers_drivers->getDriver($order_info['driver_id']);
            if(is_array($order_driver_details) && $order_driver_details != NULL) {
            $data['order_driver_details'] = $order_driver_details;
            } else {
            $data['order_driver_details'] = NULL;    
            }
            
            $order_delivery_executive_details = $this->model_executives_executives->getExecutive($order_info['delivery_executive_id']);
            if(is_array($order_delivery_executive_details) && $order_delivery_executive_details != NULL) {
            $data['order_delivery_executive_details'] = $order_delivery_executive_details;
            } else {
            $data['order_delivery_executive_details'] = NULL;    
            }
            
            $data['vehicle_number'] = $order_info['vehicle_number'];

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/order_info.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/order_info.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/account/order_info.tpl', $data));
            }
        } else {
            $this->document->setTitle($this->language->get('text_order'));

            $data['heading_title'] = $this->language->get('text_no_order');

            $data['text_error'] = $this->language->get('text_error');

            $data['button_continue'] = $this->language->get('button_continue');

            $data['breadcrumbs'] = [];

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/home'),
            ];

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_account'),
                'href' => $this->url->link('account/account', '', 'SSL'),
            ];

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('account/order', '', 'SSL'),
            ];

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_order'),
                'href' => $this->url->link('account/order/info', 'order_id=' . $order_id, 'SSL'),
            ];

            $data['continue'] = $this->url->link('account/order', '', 'SSL');

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header/orderSummaryHeader');

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
            }
        }
    }

    public function realinfo() {
        $this->load->language('account/order');

        $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_login.css');

        if (isset($this->request->get['order_id'])) {
            $order_id = $this->request->get['order_id'];
        } else {
            $order_id = 0;
        }
        $data['kondutoStatus'] = $this->config->get('config_konduto_status');

        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        $this->document->setTitle($this->language->get('heading_title'));

        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/order/info', 'order_id=' . $order_id, 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $this->load->model('account/order');

        $order_info = $this->model_account_order->getOrder($order_id);

        $data['cashback_condition'] = $this->language->get('cashback_condition');

        if ($order_info) {
            $data['cashbackAmount'] = $this->currency->format(0);

            $coupon_history_data = $this->model_account_order->getCashbackAmount($order_id);

            if (count($coupon_history_data) > 0) {
                $data['cashbackAmount'] = $this->currency->format((-1 * $coupon_history_data['amount']));
            }

            $this->document->setTitle($this->language->get('text_order'));

            $data['breadcrumbs'] = [];

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/home'),
            ];

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_account'),
                'href' => $this->url->link('account/account', '', 'SSL'),
            ];

            $url = '';

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('account/order', $url, 'SSL'),
            ];

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_order'),
                'href' => $this->url->link('account/order/info', 'order_id=' . $this->request->get['order_id'] . $url, 'SSL'),
            ];

            $data['text_go_back'] = $this->language->get('text_go_back');
            $data['text_order_id_with_colon'] = $this->language->get('text_order_id_with_colon');
            $data['text_items'] = $this->language->get('text_items');

            $data['text_coupon_willbe_credited'] = $this->language->get('text_coupon_willbe_credited');
            $data['text_coupon_credited'] = $this->language->get('text_coupon_credited');

            $data['heading_title'] = $this->language->get('heading_title');
            $data['text_order_detail'] = $this->language->get('text_order_detail');
            $data['text_invoice_no'] = $this->language->get('text_invoice_no');
            $data['text_order_id'] = $this->language->get('text_order_id');
            $data['text_date_added'] = $this->language->get('text_date_added');
            $data['text_shipping_method'] = $this->language->get('text_shipping_method');
            $data['text_shipping_address'] = $this->language->get('text_shipping_address');
            $data['text_payment_method'] = $this->language->get('text_payment_method');
            $data['text_payment_address'] = $this->language->get('text_payment_address');
            $data['text_history'] = $this->language->get('text_history');
            $data['text_comment'] = $this->language->get('text_comment');
            $data['text_processing'] = $this->language->get('text_processing');
            $data['text_shipped'] = $this->language->get('text_shipped');
            $data['text_delivered'] = $this->language->get('text_delivered');
            $data['text_name'] = $this->language->get('text_name');
            $data['text_contact_no'] = $this->language->get('text_contact_no');
            $data['text_estimated_datetime'] = $this->language->get('text_estimated_datetime');
            $data['text_cancel'] = $this->language->get('text_cancel');

            $data['column_name'] = $this->language->get('column_name');

            $data['column_image'] = $this->language->get('column_image');

            $data['column_unit'] = $this->language->get('column_unit');

            $data['column_model'] = $this->language->get('column_model');
            $data['column_quantity'] = $this->language->get('column_quantity');
            $data['column_price'] = $this->language->get('column_price');
            $data['column_total'] = $this->language->get('column_total');
            $data['column_action'] = $this->language->get('column_action');
            $data['column_date_added'] = $this->language->get('column_date_added');
            $data['column_status'] = $this->language->get('column_status');
            $data['column_comment'] = $this->language->get('column_comment');

            $data['button_reorder'] = $this->language->get('button_reorder');
            $data['button_return'] = $this->language->get('button_return');
            $data['button_continue'] = $this->language->get('button_continue');

            $data['delivered'] = false;
            $data['coupon_cashback'] = false;

            foreach ($this->config->get('config_complete_status') as $key => $value) {
                if ($value == $order_info['order_status_id']) {
                    $data['delivered'] = true;
                    $data['coupon_cashback'] = true;
                    break;
                }
            }

            if (isset($this->session->data['error'])) {
                $data['error_warning'] = $this->session->data['error'];

                unset($this->session->data['error']);
            } else {
                $data['error_warning'] = '';
            }

            if (isset($this->session->data['success'])) {
                $data['success'] = $this->session->data['success'];

                unset($this->session->data['success']);
            } else {
                $data['success'] = '';
            }

            if ($order_info['invoice_no']) {
                $data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
            } else {
                $data['invoice_no'] = '';
            }

            if ($order_info['settlement_amount']) {
                $data['settlement_amount'] = $this->currency->format($order_info['settlement_amount']);
            } else {
                $data['settlement_amount'] = null;
            }

            $data['text_rating'] = $this->language->get('text_rating');
            $data['text_review'] = $this->language->get('text_review');
            $data['text_send'] = $this->language->get('text_send');

            $data['text_send_rating'] = $this->language->get('text_send_rating');
            $data['text_remaining'] = $this->language->get('text_remaining');
            $data['text_intransit'] = $this->language->get('text_intransit');
            $data['text_completed'] = $this->language->get('text_completed');
            $data['text_cancelled'] = $this->language->get('text_cancelled');
            $data['text_not_avialable'] = $this->language->get('text_not_avialable');
            $data['text_picked'] = $this->language->get('text_picked');

            $data['text_replaced'] = $this->language->get('text_replaced');

            $data['text_delivery_detail'] = $this->language->get('text_delivery_detail');
            $data['text_no_delivery_alloted'] = $this->language->get('text_no_delivery_alloted');
            $data['text_real_amount'] = $this->language->get('text_real_amount');

            $data['text_replacable_title'] = $this->language->get('text_replacable_title');
            $data['text_not_replacable_title'] = $this->language->get('text_not_replacable_title');
            $data['text_replacable'] = $this->language->get('text_replacable');
            $data['text_not_replacable'] = $this->language->get('text_not_replacable');
            $data['order_id'] = $this->request->get['order_id'];
            $data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_modified']));

            $data['payment_method'] = $order_info['payment_method'];

            $data['shipping_name'] = $order_info['shipping_name'];
            $data['shipping_contact_no'] = $order_info['shipping_contact_no'];

            $data['shipping_address'] = $order_info['shipping_flat_number'] . ', ' . $order_info['shipping_building_name'] . ', ' . $order_info['shipping_landmark'];

            $data['shipping_method'] = $order_info['shipping_method'];
            $data['shipping_city'] = $order_info['shipping_city'];

            $data['delivery_timeslot'] = $order_info['delivery_timeslot'];

            $data['order_status_id'] = $order_info['order_status_id'];

            $data['delivery_date'] = $order_info['delivery_date'];

            $data['store_name'] = $order_info['store_name'];
            $data['store_address'] = $order_info['store_address'];
            $data['status'] = $order_info['status'];

            $this->load->model('assets/product');
            $this->load->model('tool/upload');

            $data['email'] = $this->config->get('config_delivery_username');
            $data['password'] = $this->config->get('config_delivery_secret');

            $data['delivery_id'] = $order_info['delivery_id']; //"del_XPeEGFX3Hc4ZeWg5";//
            //$data['delivery_id'] =  26;
            $data['shopper_link'] = $this->config->get('config_shopper_link') . '/storage/';

            $data['products_status'] = [];
            $data['delivery_data'] = [];

            $log = new Log('error.log');

            if (isset($data['delivery_id'])) {
                $response = $this->load->controller('deliversystem/deliversystem/getToken', $data);

                if ($response['status']) {
                    $data['token'] = $response['token'];
                    $productStatus = $this->load->controller('deliversystem/deliversystem/getProductStatus', $data);

                    //echo "<pre>";print_r($productStatus);die;
                    $resp = $this->load->controller('deliversystem/deliversystem/getDeliveryStatus', $data);
                    //echo "<pre>";print_r($resp);die;
                    $data['delivery_id'] = '';
                    if (!$resp['status'] || isset($resp['error'])) {
                        $data['delivery_data'] = [];
                    } else {
                        $data['delivery_data'] = $resp['data'][0];

                        //delivery_data->delivery_id
                    }

                    if (!$productStatus['status'] || !(count($productStatus['data']) > 0)) {
                        $data['products_status'] = [];
                    } else {
                        $data['products_status'] = $productStatus['data'];
                    }

                    $log->write('order log');
                    $log->write($data['products_status']);

                    //echo "<pre>";print_r($data['products_status']);die;
                }
            }

            // Products
            $data['products'] = [];

            $products = $this->model_account_order->getRealOrderProducts($this->request->get['order_id']);

            //			echo "<pre>";print_r($products);die;
            foreach ($products as $product) {
                $option_data = [];

                $options = $this->model_account_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);

                foreach ($options as $option) {
                    if ('file' != $option['type']) {
                        $value = $option['value'];
                    } else {
                        $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

                        if ($upload_info) {
                            $value = $upload_info['name'];
                        } else {
                            $value = '';
                        }
                    }

                    $option_data[] = [
                        'name' => $option['name'],
                        'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value),
                    ];
                }

                $product_info = $this->model_assets_product->getDetailproduct($product['product_id']);

                if ($product_info) {
                    $reorder = $this->url->link('account/order/reorder', 'order_id=' . $order_id . '&order_product_id=' . $product['order_product_id'], 'SSL');
                } else {
                    $reorder = '';
                }

                $this->load->model('tool/image');

                if (isset($product['image']) && file_exists(DIR_IMAGE . $product['image'])) {
                    $image = $this->model_tool_image->resize($product['image'], 80, 100);
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', 80, 100);
                }

                $data['products'][] = [
                    'store_id' => $product['store_id'],
                    'vendor_id' => $product['vendor_id'],
                    'name' => $product['name'],
                    'unit' => $product['unit'],
                    'model' => $product['model'],
                    'product_type' => $product['product_type'],
                    //'product_type'    => $product['product_type'],
                    'image' => $image,
                    'option' => $option_data,
                    'quantity' => $product['quantity'],
                    'price' => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                    'total' => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
                    'reorder' => $reorder,
                    'return' => $this->url->link('account/return/add', 'order_id=' . $order_info['order_id'] . '&product_id=' . $product['product_id'], 'SSL'),
                ];
            }

            $log->write($data['products']);
            // Voucher
            $data['vouchers'] = [];

            $vouchers = $this->model_account_order->getOrderVouchers($this->request->get['order_id']);

            foreach ($vouchers as $voucher) {
                $data['vouchers'][] = [
                    'description' => $voucher['description'],
                    'amount' => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
                ];
            }

            // Totals
            $data['totals'] = [];

            $totals = $this->model_account_order->getOrderTotals($this->request->get['order_id']);

            $data['newTotal'] = $this->currency->format(0);

            //echo "<pre>";print_r($totals);die;
            foreach ($totals as $total) {
                $data['totals'][] = [
                    'title' => $total['title'],
                    'text' => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
                ];

                if ('sub_total' == $total['code']) {
                    $data['subtotal'] = $total['value'];
                }
                if ('total' == $total['code']) {
                    $temptotal = $total['value'];
                }

                $data['plain_settlement_amount'] = $order_info['settlement_amount'];
                if (isset($data['settlement_amount']) && isset($data['subtotal']) && isset($temptotal)) {
                    $data['newTotal'] = $this->currency->format($temptotal - $data['subtotal'] + $order_info['settlement_amount']);
                }
            }

            $data['comment'] = nl2br($order_info['comment']);

            // History
            $data['histories'] = [];

            $results = $this->model_account_order->getOrderHistories($this->request->get['order_id']);

            foreach ($results as $result) {
                $data['histories'][] = [
                    'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                    'status' => $result['status'],
                    'comment' => $result['notify'] ? nl2br($result['comment']) : '',
                ];
            }

            if ($this->request->server['HTTPS']) {
                $server = $this->config->get('config_ssl');
            } else {
                $server = $this->config->get('config_url');
            }

            $data['base'] = $server;

            $data['continue'] = $this->url->link('account/order', '', 'SSL');

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header/onlyHeader');
            $data['text_products'] = $this->language->get('text_products');
            $data['total_products'] = count($data['products']);
            $data['total_quantity'] = 0;
            foreach ($data['products'] as $product) {
                $data['total_quantity'] += $product['quantity'];
            }
            
            $this->load->model('drivers/drivers');
            $this->load->model('executives/executives');
            $order_driver_details = $this->model_drivers_drivers->getDriver($order_info['driver_id']);
            if(is_array($order_driver_details) && $order_driver_details != NULL) {
            $data['order_driver_details'] = $order_driver_details;
            } else {
            $data['order_driver_details'] = NULL;    
            }
            
            $order_delivery_executive_details = $this->model_executives_executives->getExecutive($order_info['delivery_executive_id']);
            if(is_array($order_delivery_executive_details) && $order_delivery_executive_details != NULL) {
            $data['order_delivery_executive_details'] = $order_delivery_executive_details;
            } else {
            $data['order_delivery_executive_details'] = NULL;    
            }
            
            $data['vehicle_number'] = $order_info['vehicle_number'];

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/real_order_info.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/real_order_info.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/account/real_order_info.tpl', $data));
            }
        } else {
            $this->document->setTitle($this->language->get('text_order'));

            $data['heading_title'] = $this->language->get('text_no_order');

            $data['text_error'] = $this->language->get('text_error');

            $data['button_continue'] = $this->language->get('button_continue');

            $data['breadcrumbs'] = [];

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/home'),
            ];

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_account'),
                'href' => $this->url->link('account/account', '', 'SSL'),
            ];

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('account/order', '', 'SSL'),
            ];

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_order'),
                'href' => $this->url->link('account/order/info', 'order_id=' . $order_id, 'SSL'),
            ];

            $data['continue'] = $this->url->link('account/order', '', 'SSL');

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header/onlyHeader');

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
            }
        }
    }


    public function deleteWishlist()
    {
        $data['status'] = false;

        $log = new Log('error.log');

        $wishlist_id = isset($this->request->post['wishlist_id']) ? $this->request->post['wishlist_id'] : null;

        if ($wishlist_id) {
            $this->load->model('account/wishlist');

            $this->model_account_wishlist->deleteWishlists($wishlist_id);

            $data['status'] = true;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function deleteWishlistProduct()
    {
        $data['status'] = false;

        $log = new Log('error.log');

        $wishlist_id = isset($this->request->post['wishlist_id']) ? $this->request->post['wishlist_id'] : null;

        $product_id = isset($this->request->post['product_id']) ? $this->request->post['product_id'] : null;

        if ($wishlist_id && $product_id) {
            $this->load->model('account/wishlist');

            $this->model_account_wishlist->deleteWishlistProduct($wishlist_id, $product_id);

            $data['status'] = true;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function getProductWislists()
    {
        $log = new Log('error.log');
        $log = new Log('getProductWislists');
        $log = new Log('getProductWislists');

        $data['status'] = false;

        $this->load->model('account/wishlist');
        $this->load->model('assets/category');

        if ($this->customer->isLogged() && isset($this->request->post['product_id'])) {
            $lists = $this->model_assets_category->getUserLists();

            $log->write($this->request->post['product_id']);

            $p = '';

            foreach ($lists as $list) {
                $present = $this->model_account_wishlist->getProductOfWishlist($list['wishlist_id'], $this->request->post['product_id']);

                $log->write($present);
                if ($present) {
                    $inp = '<input type="checkbox" class="" name="add_to_list[]" value="'.$list['wishlist_id'].'" checked>';
                } else {
                    $inp = '<input type="checkbox" class="" name="add_to_list[]" value="'.$list['wishlist_id'].'">';
                }

                $p .= '<tr>
                    <td>'.$list['name'].'</td>
                    <td class="">'.$inp.' </td>
                </tr>';
            }

            $data['status'] = true;
            $data['html'] = $p;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function updateWishlistProduct()
    {
        $data['status'] = false;

        $log = new Log('error.log');

        $wishlist_id = isset($this->request->post['wishlist_id']) ? $this->request->post['wishlist_id'] : null;

        $product_id = isset($this->request->post['product_id']) ? $this->request->post['product_id'] : null;

        $quantity = isset($this->request->post['quantity']) ? $this->request->post['quantity'] : null;

        if ($wishlist_id && $product_id) {
            $this->load->model('account/wishlist');

            $this->model_account_wishlist->updateWishlistProduct($wishlist_id, $product_id, $quantity);
            if ($quantity <= 0) {
                $this->model_account_wishlist->deleteWishlistProduct($wishlist_id, $product_id);
            }
            //$log->write('total_quantity');
            $log->write($this->model_account_wishlist->getTotalWishlist());
            $data['total_quantity'] = $this->model_account_wishlist->getTotalWishlistQuantity();
            //$log->write('total_quantity');

            $data['status'] = true;
            if ($quantity <= 0) {
                $data['delete'] = true;
            } else {
                $data['delete'] = false;  
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function addWishlistProductToCart()
    {
        $this->load->language('account/wishlist');
        $this->load->model('account/wishlist');

        $data['text_cart_success'] = $this->language->get('text_cart_success');
        $log = new Log('error.log');
        $wishlist_id = $this->request->post['wishlist_id'];

        $wishlist_products = $this->model_account_wishlist->getWishlistProduct($wishlist_id);
        $log->write('Wish List Products');
        $log->write($wishlist_products);
        $log->write('Wish List Products');

        if (is_array($wishlist_products) && count($wishlist_products) > 0) {
            foreach ($wishlist_products as $wishlist_product) {
                $log->write('Wish List Products 2');
                $log->write($wishlist_product['product_id']);
                $log->write('Wish List Products 2');
                $this->load->model('assets/product');
                $store_data = $this->model_assets_product->getProductStoreId($wishlist_product['product_id'], 75);
                $product_info = $this->model_assets_product->getDetailproduct($store_data['product_store_id']);
                if(isset($product_info) && count($product_info) > 0) {
                $log->write('store details');
                $log->write($store_data);
                $log->write('store details');
                $this->cart->addCustom($store_data['product_store_id'], $wishlist_product['quantity'], $option = [], $recurring_id = 0, $store_data['store_id'], $store_product_variation_id = false, $product_type = 'replacable', $product_note = null, $produce_type = null);
                }
            }
        }
        // $this->model_account_wishlist->deleteWishlists($wishlist_id);
        //echo "reg";

        $this->session->data['success'] = $data['text_cart_success'];

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function addWishlistProductToCartByProduct()
    {
        $log = new Log('error.log');
        $log->write('Wish List Products 2');
        $log->write($this->request->post['wishlist_id']);
        $log->write($this->request->post['products']);
        $log->write('Wish List Products 2');
        $this->load->language('account/wishlist');
        $this->load->model('account/wishlist');
        $this->load->model('assets/product');

        $data['text_cart_success'] = $this->language->get('text_cart_success');
        $wishlist_id = $this->request->post['wishlist_id'];

        foreach ($this->request->post['products'] as $product_id) {
            $log->write($product_id);
            $wishlist_product = $this->model_account_wishlist->getProductOfWishlist($wishlist_id, $product_id);
            $log->write('Wish List Products');
            $log->write($wishlist_product);
            $log->write('Wish List Products');
            $store_data = $this->model_assets_product->getProductStoreId($product_id, 75);
            $log->write('Store Details123');
            $log->write($store_data);
            $log->write('Store Details123');
            $this->cart->addCustom($store_data['product_store_id'], $wishlist_product['quantity'], $option = [], $recurring_id = 0, $store_data['store_id'], $store_product_variation_id = false, $product_type = 'replacable', $product_note = null, $produce_type = null);
            //$this->model_account_wishlist->deleteWishlistProduct($wishlist_id, $product_id);
        //Items should remain in whishlist
        }

        $this->session->data['success'] = $data['text_cart_success'];
        $data['location'] = $this->url->link('checkout/checkoutitems', '', 'SSL');
        $data['status'] = 'success';

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function createWishlist()
    {
        $this->load->language('account/wishlist');

        $data['text_success_added_in_list'] = $this->language->get('text_success_added_in_list');
        $data['text_success_created_list'] = $this->language->get('text_success_created_list');

        $data['text_error_name_list'] = $this->language->get('text_error_name_list');
        $data['text_error_list'] = $this->language->get('text_error_list');

        $data['message'] = '';
        $data['status'] = false;

        $log = new Log('error.log');

        $this->load->model('account/wishlist');

        // $log->write($this->request->post['name']);
        // $log->write($this->request->post['listproductId']);
        $log->write($this->request->get['listproductId']);
        $log->write('createWishlist');
        if (isset($this->request->get['listproductId'])) {// isset($this->request->post['name']) &&
            //$count = $this->model_account_wishlist->getWishlistPresent($this->request->post['name']);
            $log->write($count);
            $count = $this->model_account_wishlist->getWishlistPresentForCustomer();

            if (!$count) {
                //not present
                $wishlist_id = $this->model_account_wishlist->createWishlist('wishlist'); //$this->request->post['name']

                $this->model_account_wishlist->addProductToWishlist($wishlist_id, $this->request->get['listproductId']);

                $data['status'] = true;

                $data['message'] = $data['text_success_created_list'];
            } else {
                //wishlist present
                //$data['message'] = $data['text_error_name_list'];
                $wishlist_id = $count;
                $this->model_account_wishlist->addProductToWishlist($wishlist_id, $this->request->get['listproductId']);
                $data['status'] = true;

                $data['message'] = $data['text_success_created_list'];
            }
        } else {
            $data['message'] = $data['text_error_list'];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function addProductToWishlist()
    {
        $this->load->language('account/wishlist');

        $data['text_success_added_in_list'] = $this->language->get('text_success_added_in_list');
        $data['text_success_created_list'] = $this->language->get('text_success_created_list');

        $data['text_error_name_list'] = $this->language->get('text_error_name_list');
        $data['text_error_list'] = $this->language->get('text_error_list');

        $data['message'] = $data['text_success_added_in_list'];
        $data['status'] = true;

        $log = new Log('error.log');

        $this->load->model('account/wishlist');
        $this->load->model('assets/category');
        $wishlist_ids = isset($this->request->post['add_to_list']) ? $this->request->post['add_to_list'] : null;

        // if ( $this->customer->isLogged()) {
        //     $lists = $this->model_assets_category->getUserLists();

        // foreach ($lists as $list) {
        //     $this->model_account_wishlist->deleteWishlistProduct($list['wishlist_id'],$this->request->post['listproductId']);
        // }
        // }

        $log->write($wishlist_ids);
        $log->write($this->request->post['listproductId']);
        $log->write('createWishlist');
        if (isset($this->request->post['listproductId']) && isset($wishlist_ids)) {
            foreach ($wishlist_ids as $wishlist_id) {
                $count = $this->model_account_wishlist->getWishlistPresentById($wishlist_id);

                $log->write($count);
                $log->write('count');
                if ($count) {
                    //present

                    $exists = $this->model_account_wishlist->getProductOfWishlist($wishlist_id, $this->request->post['listproductId']);

                    $log->write($exists);
                    $log->write('exists');

                    if (count($exists) > 0) {
                        $quantity = $exists['quantity'] + 1;

                        $this->model_account_wishlist->updateWishlistProduct($wishlist_id, $this->request->post['listproductId'], $quantity);
                    } else {
                        $this->model_account_wishlist->addProductToWishlist($wishlist_id, $this->request->post['listproductId']);
                    }

                    $data['status'] = true;

                    $data['message'] = $data['text_success_added_in_list'];
                } else {
                    //wishlist not present
                }
            }
        } else {
            //$data['message'] = $data['text_error_list'];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function addProdToWishlist($product_id)
    {
        $this->load->language('account/wishlist');

        $data['text_success_added_in_list'] = $this->language->get('text_success_added_in_list');
        $data['text_success_created_list'] = $this->language->get('text_success_created_list');

        $data['text_error_name_list'] = $this->language->get('text_error_name_list');
        $data['text_error_list'] = $this->language->get('text_error_list');

        $data['message'] = $data['text_success_added_in_list'];
        $data['status'] = true;

        $log = new Log('error.log');

        $this->load->model('account/wishlist');
        $this->load->model('assets/category');

        // if ( $this->customer->isLogged()) {
        //     $lists = $this->model_assets_category->getUserLists();

        //     foreach ($lists as $list) {
        //         $this->model_account_wishlist->deleteWishlistProduct($list['wishlist_id'],$this->request->post['listproductId']);
        //     }
        // }

        $log->write($wishlist_ids);
        $log->write($this->request->post['listproductId']);
        $log->write('createWishlist');
        if (isset($this->request->post['listproductId']) && isset($wishlist_ids)) {
            foreach ($wishlist_ids as $wishlist_id) {
                $count = $this->model_account_wishlist->getWishlistPresentById($wishlist_id);

                $log->write($count);
                $log->write('count');
                if ($count) {
                    //present

                    $exists = $this->model_account_wishlist->getProductOfWishlist($wishlist_id, $this->request->post['listproductId']);

                    $log->write($exists);
                    $log->write('exists');

                    if (count($exists) > 0) {
                        $quantity = $exists['quantity'] + 1;

                        $this->model_account_wishlist->updateWishlistProduct($wishlist_id, $this->request->post['listproductId'], $quantity);
                    } else {
                        $this->model_account_wishlist->addProductToWishlist($wishlist_id, $this->request->post['listproductId']);
                    }

                    $data['status'] = true;

                    $data['message'] = $data['text_success_added_in_list'];
                } else {
                    //wishlist not present
                }
            }
        } else {
            //$data['message'] = $data['text_error_list'];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function deleteWishlistProductByID()
    {
        $data['status'] = false;

        $log = new Log('error.log');

        // $wishlist_id = isset($this->request->post['wishlist_id'])?$this->request->post['wishlist_id']:null;

        // $product_id = isset($this->request->post['product_id'])?$this->request->post['product_id']:null;

        // if($wishlist_id && $product_id) {
        if ($this->request->get['listproductId']) {
            $this->load->model('account/wishlist');

            $this->model_account_wishlist->deleteWishlistProductByID($this->request->get['listproductId']);

            $data['status'] = true;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }
}
