<?php

class ControllerAccountOrder extends Controller {

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

        $order_total = $this->model_account_order->getTotalOrders();

        $results = $this->model_account_order->getOrders(($page - 1) * 10, 10);

        // echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            $city_name = $this->model_account_order->getCityName($result['shipping_city_id']);

            $product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);
            $products_total = $this->model_account_order->getTotalOrderedProductsByOrderId($result['order_id']);

            $real_product_total = $this->model_account_order->getTotalRealOrderProductsByOrderId($result['order_id']);
            $real_products_total = $this->model_account_order->getTotalRealOrderedProductsByOrderId($result['order_id']);

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
                'store_id' => $result['store_id'],
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
                'productss' => ($products_total + $voucher_total),
                'realproductss' => ($real_products_total + $voucher_total),
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
                'edit_own_order' => (($result['order_status_id'] == 15 || $result['order_status_id'] == 14) && $hours <= 2 && $result['paid'] == 'N' && $result['payment_code'] == 'cod') ? $this->url->link('account/order/edit_your_order', 'order_id=' . $result['order_id'], 'SSL') : NULL,
                'paid' => $result['paid'],
                'products_missed' => $result['delivery_date'] == date('Y-m-d') && ($result['order_status_id'] == 4 || $result['order_status_id'] == 5) ? 1 : 0,
                'products_rejected' => $result['delivery_date'] == date('Y-m-d') && ($result['order_status_id'] == 4 || $result['order_status_id'] == 5) ? 1 : 0
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
        //   echo "<pre>"; print_r($data);die;

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
            if (is_array($order_driver_details) && $order_driver_details != NULL) {
                $data['order_driver_details'] = $order_driver_details;
            } else {
                $data['order_driver_details'] = NULL;
            }

            $order_delivery_executive_details = $this->model_executives_executives->getExecutive($order_info['delivery_executive_id']);
            if (is_array($order_delivery_executive_details) && $order_delivery_executive_details != NULL) {
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

    public function infoPopup() {
        $redirectNotLogin = true;
        $this->load->language('account/order');
        $this->load->language('account/return');

        // $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_login.css');

        if (isset($this->request->get['order_id'])) {
            $order_id = $this->request->get['order_id'];
        } else {
            $order_id = 0;
        }
        // if (false == is_numeric($order_id)) {
        //     $order_id = base64_decode(trim($order_id));
        //     $order_id = preg_replace('/[^A-Za-z0-9\-]/', '', $order_id);
        //     $this->request->get['order_id'] = $order_id;
        //     $redirectNotLogin = false;
        //     //$this->response->redirect($this->url->link('account/order/info', 'order_id=' . $order_id, 'SSL'));
        // }

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
        //  echo "<pre>";print_r($order_info);die;

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
                    'product_note' => $product['product_note'],
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
            if (is_array($order_driver_details) && $order_driver_details != NULL) {
                $data['order_driver_details'] = $order_driver_details;
            } else {
                $data['order_driver_details'] = NULL;
            }

            $order_delivery_executive_details = $this->model_executives_executives->getExecutive($order_info['delivery_executive_id']);
            if (is_array($order_delivery_executive_details) && $order_delivery_executive_details != NULL) {
                $data['order_delivery_executive_details'] = $order_delivery_executive_details;
            } else {
                $data['order_delivery_executive_details'] = NULL;
            }

            $data['vehicle_number'] = $order_info['vehicle_number'];

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/order_info.tpl')) {
                $html = $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/order_infopopup.tpl', $data));
            } else {
                $html = $this->response->setOutput($this->load->view('default/template/account/order_info.tpl', $data));
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
                $html = $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
            } else {
                $html = $this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
            }

            echo json_encode(['html' => $html]);
        }
    }

    public function missingproducts() {
        $redirectNotLogin = true;
        $this->load->language('account/order');
        $this->load->language('account/return');

        if (isset($this->request->get['order_id'])) {
            $order_id = $this->request->get['order_id'];
        } else {
            $order_id = 0;
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
        //  echo "<pre>";print_r($order_info);die;

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

            $products = $this->model_account_order->getRealOrderProducts($this->request->get['order_id']);
            if ($products == NULL || (is_array($products) && count($products) <= 0)) {
                $products = $this->model_account_order->getOrderProducts($this->request->get['order_id']);
            }

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
                    'product_note' => $product['product_note'],
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
            if (is_array($order_driver_details) && $order_driver_details != NULL) {
                $data['order_driver_details'] = $order_driver_details;
            } else {
                $data['order_driver_details'] = NULL;
            }

            $order_delivery_executive_details = $this->model_executives_executives->getExecutive($order_info['delivery_executive_id']);
            if (is_array($order_delivery_executive_details) && $order_delivery_executive_details != NULL) {
                $data['order_delivery_executive_details'] = $order_delivery_executive_details;
            } else {
                $data['order_delivery_executive_details'] = NULL;
            }

            $data['vehicle_number'] = $order_info['vehicle_number'];

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/missed_products.tpl')) {
                $html = $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/missed_products.tpl', $data));
            } else {
                $html = $this->response->setOutput($this->load->view('default/template/account/missed_products.tpl', $data));
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
                $html = $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
            } else {
                $html = $this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
            }

            echo json_encode(['html' => $html]);
        }
    }

    public function rejectedproducts() {
        $redirectNotLogin = true;
        $this->load->language('account/order');
        $this->load->language('account/return');

        // $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_login.css');

        if (isset($this->request->get['order_id'])) {
            $order_id = $this->request->get['order_id'];
        } else {
            $order_id = 0;
        }
        // if (false == is_numeric($order_id)) {
        //     $order_id = base64_decode(trim($order_id));
        //     $order_id = preg_replace('/[^A-Za-z0-9\-]/', '', $order_id);
        //     $this->request->get['order_id'] = $order_id;
        //     $redirectNotLogin = false;
        //     //$this->response->redirect($this->url->link('account/order/info', 'order_id=' . $order_id, 'SSL'));
        // }

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
        //  echo "<pre>";print_r($order_info);die;

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

            $products = $this->model_account_order->getRealOrderProducts($this->request->get['order_id']);
            if ($products == NULL || (is_array($products) && count($products) <= 0)) {
                $products = $this->model_account_order->getOrderProducts($this->request->get['order_id']);
            }
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
                    'product_note' => $product['product_note'],
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
            if (is_array($order_driver_details) && $order_driver_details != NULL) {
                $data['order_driver_details'] = $order_driver_details;
            } else {
                $data['order_driver_details'] = NULL;
            }

            $order_delivery_executive_details = $this->model_executives_executives->getExecutive($order_info['delivery_executive_id']);
            if (is_array($order_delivery_executive_details) && $order_delivery_executive_details != NULL) {
                $data['order_delivery_executive_details'] = $order_delivery_executive_details;
            } else {
                $data['order_delivery_executive_details'] = NULL;
            }

            $data['vehicle_number'] = $order_info['vehicle_number'];

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/rejected_products.tpl')) {
                $html = $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/rejected_products.tpl', $data));
            } else {
                $html = $this->response->setOutput($this->load->view('default/template/account/rejected_products.tpl', $data));
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
                $html = $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
            } else {
                $html = $this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
            }

            echo json_encode(['html' => $html]);
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
                    'product_note' => $product['product_note'],
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
            if (is_array($order_driver_details) && $order_driver_details != NULL) {
                $data['order_driver_details'] = $order_driver_details;
            } else {
                $data['order_driver_details'] = NULL;
            }

            $order_delivery_executive_details = $this->model_executives_executives->getExecutive($order_info['delivery_executive_id']);
            if (is_array($order_delivery_executive_details) && $order_delivery_executive_details != NULL) {
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

    public function reorder() {
        $this->load->language('account/order');

        if (isset($this->request->get['order_id'])) {
            $order_id = $this->request->get['order_id'];
        } else {
            $order_id = 0;
        }

        $this->load->model('account/order');

        $order_info = $this->model_account_order->getOrder($order_id);

        if ($order_info) {
            if (isset($this->request->get['order_product_id'])) {
                $order_product_id = $this->request->get['order_product_id'];
            } else {
                $order_product_id = 0;
            }

            $order_product_info = $this->model_account_order->getOrderProduct($order_id, $order_product_id);

            if ($order_product_info) {
                $this->load->model('assets/product');

                $product_info = $this->model_assets_product->getProduct($order_product_info['product_id']);

                if ($product_info) {
                    $option_data = [];

                    $order_options = $this->model_account_order->getOrderOptions($order_product_info['order_id'], $order_product_id);

                    foreach ($order_options as $order_option) {
                        if ('select' == $order_option['type'] || 'radio' == $order_option['type'] || 'image' == $order_option['type']) {
                            $option_data[$order_option['product_option_id']] = $order_option['product_option_value_id'];
                        } elseif ('checkbox' == $order_option['type']) {
                            $option_data[$order_option['product_option_id']][] = $order_option['product_option_value_id'];
                        } elseif ('text' == $order_option['type'] || 'textarea' == $order_option['type'] || 'date' == $order_option['type'] || 'datetime' == $order_option['type'] || 'time' == $order_option['type']) {
                            $option_data[$order_option['product_option_id']] = $order_option['value'];
                        } elseif ('file' == $order_option['type']) {
                            $option_data[$order_option['product_option_id']] = $this->encryption->encrypt($order_option['value']);
                        }
                    }

                    $this->cart->add($order_product_info['product_id'], $order_product_info['quantity'], $option_data, false, $order_product_info['store_id']);

                    $this->session->data['success'] = sprintf($this->language->get('text_success'), $product_info['name'], $this->url->link('checkout/cart'));
                    //$this->url->link('product/product', 'product_id=' . $product_info['product_id']),

                    unset($this->session->data['shipping_method']);
                    unset($this->session->data['shipping_methods']);
                    unset($this->session->data['payment_method']);
                    unset($this->session->data['payment_methods']);
                } else {
                    $this->session->data['error'] = sprintf($this->language->get('error_reorder'), $order_product_info['name']);
                }
            }
        }

        $this->response->redirect($this->url->link('account/order/info', 'order_id=' . $order_id));
    }

    public function refundCancelOrder() {
        require_once DIR_SYSTEM . 'library/Iugu.php';

        $data['status'] = false;

        $log = new Log('error.log');

        $order_id = isset($this->request->post['order_id']) ? $this->request->post['order_id'] : null;

        if ($order_id) {
            $data['settlement_tab'] = false;

            $this->load->model('sale/order');
            $this->load->model('checkout/order');
            /* $iuguData =  $this->model_sale_order->getOrderIugu($order_id);

              $log->write('refundCancelOrder');
              $log->write($iuguData);
              if($iuguData) {

              $invoiceId = $iuguData['invoice_id'];

              Iugu::setApiKey($this->config->get('iugu_token'));


              $invoice = Iugu_Invoice::fetch($invoiceId);
              $resp = $invoice->refund();

              $log->write('refundAPI');
              $log->write($resp);

              if($resp) {

              } else {
              $data['status'] = false;
              }
              } */

            //update order status as cancelled
            $order_info = $this->model_checkout_order->getOrder($order_id);

            $log->write($order_id);

            $notify = true;
            $comment = 'Order ID #' . $order_id . ' Cancelled';

            $this->load->model('localisation/order_status');

            $order_status = $this->model_localisation_order_status->getOrderStatuses();

            $order_status_id = false;
            foreach ($order_status as $order_state) {
                if ('cancelled' == strtolower($order_state['name']) || 'cancelada' == strtolower($order_state['name'])) {
                    $order_status_id = $order_state['order_status_id'];
                    break;
                }
            }

            $log->write($order_status_id);
            if ($order_info && $order_status_id) {
                $log->write('if order his');

                $this->load->model('account/customer');
                $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

                $this->model_checkout_order->addOrderHistory($order_id, $order_status_id, $comment, $notify, $customer_info['customer_id'], 'customer');

                $data['status'] = true;
            } else {
                $data['status'] = false;
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function refundCancelOrderByOrderId($order_id) {
        require_once DIR_SYSTEM . 'library/Iugu.php';

        $data['status'] = false;

        $log = new Log('error.log');

        if ($order_id) {
            $data['settlement_tab'] = false;

            $this->load->model('sale/order');
            $this->load->model('checkout/order');
            /* $iuguData =  $this->model_sale_order->getOrderIugu($order_id);

              $log->write('refundCancelOrder');
              $log->write($iuguData);
              if($iuguData) {

              $invoiceId = $iuguData['invoice_id'];

              Iugu::setApiKey($this->config->get('iugu_token'));


              $invoice = Iugu_Invoice::fetch($invoiceId);
              $resp = $invoice->refund();

              $log->write('refundAPI');
              $log->write($resp);

              if($resp) {

              } else {
              $data['status'] = false;
              }
              } */

            //update order status as cancelled
            $order_info = $this->model_checkout_order->getOrder($order_id);

            $log->write($order_id);

            $notify = true;
            $comment = 'Order ID #' . $order_id . ' Cancelled';

            $this->load->model('localisation/order_status');

            $order_status = $this->model_localisation_order_status->getOrderStatuses();

            $order_status_id = false;
            foreach ($order_status as $order_state) {
                if ('cancelled' == strtolower($order_state['name']) || 'cancelada' == strtolower($order_state['name'])) {
                    $order_status_id = $order_state['order_status_id'];
                    break;
                }
            }

            $log->write($order_status_id);
            if ($order_info && $order_status_id) {
                $log->write('if order his');

                $this->load->model('account/customer');
                $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

                $this->model_checkout_order->addOrderHistory($order_id, $order_status_id, $comment, $notify, $customer_info['customer_id'], 'customer');

                $data['status'] = true;
            } else {
                $data['status'] = false;
            }
        }

        /* $this->response->addHeader('Content-Type: application/json');
          $this->response->setOutput(json_encode($data)); */
    }

    public function can_return() {
        $this->load->language('account/order');

        $resp['can_return'] = false;

        $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_login.css');

        if (isset($this->request->post['order_id'])) {
            $order_id = $this->request->post['order_id'];
        } else {
            $order_id = 0;
        }

        $this->load->model('account/order');

        $order_info = $this->model_account_order->getOrder($order_id);

        if ($order_info) {
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
                    $resp['can_return'] = true;
                }
                //echo "<pre>";print_r($differenceInSeconds);die;
            }
        }
        //$resp['can_return'] = false;

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($resp));
    }

    public function accept_delivery_submit() {
        try {
            $this->load->language('account/order');

            $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_login.css');

            $order_id = $this->request->post['order_id'];
            $products = $this->request->post['products'];
            $return_replace_count = $this->request->post['return_replace'];
            foreach ($products as $product) {
                $product_id = $product[0];
                $action = $product[2];
                $action_note = $product[3];
                //echo "UPDATE `" . DB_PREFIX . "order_product` SET 	on_delivery_action = '" . $action . "', delivery_action_note = '" . $action_note . "' WHERE order_id = '" . (int) $order_id . "' AND product_id = '" . (int) $product_id . "'";
                //echo '<br>';
                $this->db->query('UPDATE `' . DB_PREFIX . "order_product` SET 	on_delivery_action = '" . $action . "', delivery_action_note = '" . $action_note . "' WHERE order_id = '" . (int) $order_id . "' AND product_id = '" . (int) $product_id . "'");
            }

            if ($return_replace_count > 0) {
                $orderStatus = 'Partially Delivered';
            } else {
                $orderStatus = 'Delivered';
            }

            $sql = 'SELECT order_status_id FROM ' . DB_PREFIX . "order_status WHERE language_id = '" . (int) $this->config->get('config_language_id') . "' AND name='" . $orderStatus . "'";
            $query = $this->db->query($sql);
            $order_status_id = $query->row['order_status_id'];
            //echo "Order_status_id "+$order_status_id;
            $comment = 'Automatic status change on Accept Delivery';

            //echo "UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int) $order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'";
            //echo "INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int) $order_id . "', order_status_id = '" . (int) $order_status_id . "', comment = '" . $this->db->escape( $comment ) . "', date_added = NOW()";
            //exit;
            $this->db->query('UPDATE `' . DB_PREFIX . "order` SET order_status_id = '" . (int) $order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");
            $this->db->query('INSERT INTO `' . DB_PREFIX . "order_history` SET order_id = '" . (int) $order_id . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
            $resp['status'] = true;
        } catch (Exception $e) {
            $resp['status'] = false;
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($resp));
    }

    public function accept_reject() {
        //echo '<pre>';print_r($_REQUEST);exit;
        $this->load->language('account/order');
        $this->load->language('account/return');

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

                if (file_exists(DIR_IMAGE . $product['image'])) {
                    $image = $this->model_tool_image->resize($product['image'], 80, 100);
                } else {
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
            $data['header'] = $this->load->controller('common/header/onlyHeader');

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
            // Payment Methods
            $mpesaOnline = false;
            $method_data = [];

            $this->load->model('extension/extension');

            $results = $this->model_extension_extension->getExtensions('payment');

            //echo "<pre>";print_r($results);die;
            $recurring = $this->cart->hasRecurringProducts();

            foreach ($results as $result) {
                if ($this->config->get($result['code'] . '_status')) {
                    $this->load->model('payment/' . $result['code']);

                    $method = $this->{'model_payment_' . $result['code']}->getMethod($total);

                    if ($method) {
                        if ($recurring) {
                            if (method_exists($this->{'model_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_payment_' . $result['code']}->recurringPayments()) {
                                $method_data[$result['code']] = $method;
                            }
                        } else {
                            $method_data[$result['code']] = $method;
                        }
                    }
                }
            }
            $sort_order = [];

            //echo "<pre>";print_r($method_data);die;

            foreach ($method_data as $key => $value) {
                if ('mpesa' == $key) {
                    $mpesaOnline = true;
                }
                $sort_order[$key] = $value['sort_order'];
            }

            //echo "<pre>===";print_r($mpesaOnline);die;
            array_multisort($sort_order, SORT_ASC, $method_data);
            $this->load->model('sale/order');
            $transcation_id = $this->model_sale_order->getOrderTransactionId($order_id);
            if (!empty($transcation_id)) {
                $mpesaOnline = false;
            }
            $data['mpesaOnline'] = $mpesaOnline;
            $data['account'] = $this->url->link('account/order');
            $data['continue'] = $this->url->link('checkout/success');

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/order_accept_delivery.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/order_accept_delivery.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/account/order_accept_delivery	.tpl', $data));
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

    public function invoice() {
        $this->load->language('account/invoice');

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

        if (isset($this->request->post['selected'])) {
            $orders = $this->request->post['selected'];
        } elseif (isset($this->request->get['order_id'])) {
            $orders[] = $this->request->get['order_id'];
        }

        if (isset($this->request->get['store_id'])) {
            $store_id = $this->request->get['store_id'];
        } else {
            $store_id = 0;
        }

        foreach ($orders as $order_id) {
            $order_info = $this->model_sale_order->getOrder($order_id);
            // //check vendor order
            // if ($this->user->isVendor()) {
            //     if (!$this->isVendorOrder($order_id)) {
            //         $this->response->redirect($this->url->link('error/not_found'));
            //     }
            // }

            if ($order_info) {
                $store_info = $this->model_setting_setting->getSetting('config', $order_info['store_id']);

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

                if ($this->model_sale_order->hasRealOrderProducts($order_id)) {
                    $products = $this->model_sale_order->getRealOrderProducts($order_id);
                } else {
                    $products = $this->model_sale_order->getOrderProducts($order_id);
                }

                foreach ($products as $product) {
                    if ($store_id && $product['store_id'] != $store_id) {
                        continue;
                    }
                    $option_data = [];

                    $options = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);

                    // foreach ($options as $option) {
                    //     if ($option['type'] != 'file') {
                    //         $value = $option['value'];
                    //     } else {
                    //         $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);
                    //         if ($upload_info) {
                    //             $value = $upload_info['name'];
                    //         } else {
                    //             $value = '';
                    //         }
                    //     }
                    //     $option_data[] = array(
                    //         'name' => $option['name'],
                    //         'value' => $value
                    //     );
                    // }

                    $product_data[] = [
                        'product_id' => $product['product_id'],
                        'name' => $product['name'],
                        'model' => $product['model'],
                        'unit' => $product['unit'],
                        'option' => null, // $option_data,
                        'quantity' => $product['quantity'],
                        'price' => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                        'total' => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
                    ];
                }

                $total_data = [];

                if ($store_id) {
                    $totals = $this->model_sale_order->getVendorOrderTotals($order_id, $store_id);
                } else {
                    $totals = $this->model_sale_order->getOrderTotals($order_id);
                }

                foreach ($totals as $total) {
                    $total_data[] = [
                        'title' => $total['title'],
                        'text' => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
                        'amount_in_words' => ucwords($this->translateAmountToWords(floor(($total['value'] * 100) / 100))) . ' Kenyan Shillings',
                    ];
                }

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
                    'shipping_name' => ($order_info['shipping_name']) ? $order_info['shipping_name'] : $order_info['firstname'] . ' ' . $order_info['lastname'],
                    'customer_company_name' => $order_info['customer_company_name'],
                    'shipping_method' => $order_info['shipping_method'],
                    'po_number' => $order_info['po_number'],
                    'payment_method' => $order_info['payment_method'],
                    'products' => $product_data,
                    'totals' => $total_data,
                    'comment' => nl2br($order_info['comment']),
                ];
            }
        }
        //    echo "<pre>";print_r($data['orders'][0]);die;
        $this->response->setOutput($this->load->view('metaorganic/template/account/order_invoice.tpl', $data['orders'][0]));
    }

    public function getUser($id) {
        if ($id) {
            $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . "customer`  WHERE customer_id ='" . $id . "'");

            return $query->row['fax'];
        }
    }

    public function ApproveOrRejectSubUserOrder() {
        $json['success'] = 'Something went wrong!';
        $order_id = $this->request->post['order_id'];
        $customer_id = $this->request->post['customer_id'];
        $order_status = $this->request->post['order_status'];

        $log = new Log('error.log');
        $log->write($order_id);
        $log->write($customer_id);
        $log->write($order_status);

        $this->load->model('account/order');
        $sub_users_order_details = $this->model_account_order->getSubUserOrderDetails($order_id, $customer_id);
        $log->write($sub_users_order_details);

        if (is_array($sub_users_order_details) && count($sub_users_order_details) > 0) {
            $order_update = $this->model_account_order->ApproveOrRejectSubUserOrder($order_id, $customer_id, $order_status);
            $sub_users_order_details = $this->model_account_order->getSubUserOrderDetails($order_id, $customer_id);

            if (($sub_users_order_details['parent_approval'] == 'Approved') || ($sub_users_order_details['head_chef'] == 'Approved' && $sub_users_order_details['procurement'] == 'Approved')) {
                $comment = 'Order Approved By Parent User';
                $this->model_account_order->UpdateOrderStatus($order_id, 14, $comment, $this->customer->getId(), 'customer');

                $sub_users_order_details = $this->model_account_order->getSubUserOrderDetails($order_id, $customer_id);
                if ($sub_users_order_details['order_status_id'] == 14) {
                    $json['success'] = 'Order Recieved';
                }

                if ($sub_users_order_details['order_status_id'] == 15) {
                    $json['success'] = 'Order Approval Pending';
                }

                if ($sub_users_order_details['order_status_id'] == 16) {
                    $json['success'] = 'Order Rejected';
                }

                if (($sub_users_order_details['parent_approval'] == 'Approved') || ($sub_users_order_details['head_chef'] == 'Approved' && $sub_users_order_details['procurement'] == 'Approved')) {
                    $this->model_account_order->SubUserOrderApproved($order_id, 14);
                }
            }

            if ($sub_users_order_details['parent_approval'] == 'Rejected' || $sub_users_order_details['head_chef'] == 'Rejected') {
                $comment = 'Order Rejected By Parent User';
                $this->model_account_order->UpdateOrderStatus($order_id, 16, $comment, $this->customer->getId(), 'customer');
                $this->model_account_order->SubUserOrderReject($order_id, 16);

                $sub_users_order_details = $this->model_account_order->getSubUserOrderDetails($order_id, $customer_id);
                if ($sub_users_order_details['order_status_id'] == 14) {
                    $json['success'] = 'Order Recieved';
                }

                if ($sub_users_order_details['order_status_id'] == 15) {
                    $json['success'] = 'Order Approval Pending';
                }

                if ($sub_users_order_details['order_status_id'] == 16) {
                    $json['success'] = 'Order Rejected';
                }
            }

            if ($sub_users_order_details['head_chef'] == 'Pending' || $sub_users_order_details['procurement'] == 'Pending') {
                $sub_users_order_details = $this->model_account_order->getSubUserOrderDetails($order_id, $customer_id);
                if ($sub_users_order_details['order_status_id'] == 14) {
                    $json['success'] = 'Order Recieved';
                }

                if ($sub_users_order_details['order_status_id'] == 15) {
                    $json['success'] = 'Order Approval Pending';
                }

                if ($sub_users_order_details['order_status_id'] == 16) {
                    $json['success'] = 'Order Rejected';
                }
            }

            if (($sub_users_order_details['head_chef'] == 'Rejected' || $sub_users_order_details['head_chef'] == 'Approved') && $sub_users_order_details['procurement'] == 'Rejected') {
                $comment = 'Order Rejected By Parent User';
                $this->model_account_order->UpdateOrderStatus($order_id, 16, $comment, $this->customer->getId(), 'customer');

                $sub_users_order_details = $this->model_account_order->getSubUserOrderDetails($order_id, $customer_id);
                if ($sub_users_order_details['order_status_id'] == 14) {
                    $json['success'] = 'Order Recieved';
                }

                if ($sub_users_order_details['order_status_id'] == 15) {
                    $json['success'] = 'Order Approval Pending';
                }

                if ($sub_users_order_details['order_status_id'] == 16) {
                    $json['success'] = 'Order Rejected';
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function ApproveOrRejectSubUserOrderByChefProcurement() {
        $json['success'] = 'Something went wrong!';
        $order_id = $this->request->post['order_id'];
        $customer_id = $this->request->post['customer_id'];
        $order_status = $this->request->post['order_status'];
        $role = $this->request->post['role'];

        $log = new Log('error.log');
        $log->write($order_id);
        $log->write($customer_id);
        $log->write($order_status);

        $this->load->model('account/order');
        $sub_users_order_details = $this->model_account_order->getSubUserOrderDetails($order_id, $customer_id);

        if (is_array($sub_users_order_details) && count($sub_users_order_details) > 0) {
            $order_update = $this->model_account_order->ApproveOrRejectSubUserOrderByChefProcurement($order_id, $customer_id, $order_status, $role);
            $sub_users_order_details = $this->model_account_order->getSubUserOrderDetails($order_id, $customer_id);
            $log->write($sub_users_order_details);
            if ($role != NULL) {
                $user_role = str_replace('_', ' ', $role);
            } else {
                $user_role = 'Parent';
            }

            if (($sub_users_order_details['parent_approval'] == 'Approved') || ($sub_users_order_details['head_chef'] == 'Approved' && $sub_users_order_details['procurement'] == 'Approved')) {
                $comment = 'Order Approved By ' . $user_role . ' User';
                $this->model_account_order->UpdateOrderStatus($order_id, 14, $comment, $this->customer->getId(), 'customer');

                $sub_users_order_details = $this->model_account_order->getSubUserOrderDetails($order_id, $customer_id);
                if ($sub_users_order_details['order_status_id'] == 14) {
                    $json['success'] = 'Order Recieved';
                }

                if ($sub_users_order_details['order_status_id'] == 15) {
                    $json['success'] = 'Order Approval Pending';
                }

                if ($sub_users_order_details['order_status_id'] == 16) {
                    $json['success'] = 'Order Rejected';
                }

                if ($sub_users_order_details['head_chef'] == 'Approved' && $sub_users_order_details['procurement'] == 'Approved') {
                    $this->model_account_order->SubUserOrderApproved($order_id, 14);
                }
            }

            if ($sub_users_order_details['parent_approval'] == 'Rejected' || $sub_users_order_details['head_chef'] == 'Rejected') {
                $comment = 'Order Rejected By ' . $user_role . ' User';
                $this->model_account_order->UpdateOrderStatus($order_id, 16, $comment, $this->customer->getId(), 'customer');

                $sub_users_order_details = $this->model_account_order->getSubUserOrderDetails($order_id, $customer_id);
                if ($sub_users_order_details['order_status_id'] == 14) {
                    $json['success'] = 'Order Recieved';
                }

                if ($sub_users_order_details['order_status_id'] == 15) {
                    $json['success'] = 'Order Approval Pending';
                }

                if ($sub_users_order_details['order_status_id'] == 16) {
                    $json['success'] = 'Order Rejected';
                }
                $this->model_account_order->SubUserOrderReject($order_id, 16);
            }

            if ($sub_users_order_details['head_chef'] == 'Pending' || $sub_users_order_details['procurement'] == 'Pending') {

                $sub_users_order_details = $this->model_account_order->getSubUserOrderDetails($order_id, $customer_id);
                if ($sub_users_order_details['order_status_id'] == 14) {
                    $json['success'] = 'Order Recieved';
                }

                if ($sub_users_order_details['order_status_id'] == 15) {
                    $json['success'] = 'Order Approval Pending';
                }

                if ($sub_users_order_details['order_status_id'] == 16) {
                    $json['success'] = 'Order Rejected';
                }
            }

            if (($sub_users_order_details['head_chef'] == 'Rejected' || $sub_users_order_details['head_chef'] == 'Approved') && $sub_users_order_details['procurement'] == 'Rejected') {
                $comment = 'Order Rejected By ' . $user_role . ' User';
                $this->model_account_order->UpdateOrderStatus($order_id, 16, $comment, $this->customer->getId(), 'customer');

                $sub_users_order_details = $this->model_account_order->getSubUserOrderDetails($order_id, $customer_id);
                if ($sub_users_order_details['order_status_id'] == 14) {
                    $json['success'] = 'Order Recieved';
                }

                if ($sub_users_order_details['order_status_id'] == 15) {
                    $json['success'] = 'Order Approval Pending';
                }

                if ($sub_users_order_details['order_status_id'] == 16) {
                    $json['success'] = 'Order Rejected';
                }

                if ($sub_users_order_details['head_chef'] == 'Rejected' && $sub_users_order_details['procurement'] == 'Rejected') {
                    $this->model_account_order->SubUserOrderReject($order_id, 16);
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function edit_order() {
        $this->load->model('account/customer');
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

                if (file_exists(DIR_IMAGE . $product['image'])) {
                    $image = $this->model_tool_image->resize($product['image'], 80, 100);
                } else {
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
            $data['order_customer_id'] = $order_info['customer_id'];
            $data['loogged_customer_id'] = $this->customer->getId();
            $data['order_status_id'] = $order_info['order_status_id'];
            $data['order_status_name'] = $order_info['status'];

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

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/order_edit.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/order_edit.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/account/order_edit.tpl', $data));
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

    public function edit_order_quantity() {
        $log = new Log('error.log');
        $json = [];
        $json['location'] = 'module';

        $order_id = $this->request->post['order_id'];
        $log->write($order_id);
        $product_id = $this->request->post['product_id'];
        $quantity = $this->request->post['quantity'];
        $unit = $this->request->post['unit'];

        $this->load->model('account/order');
        $order_info = $this->model_account_order->getOrder($order_id, true);
        if (null != $order_info && 15 == $order_info['order_status_id']) {
            $order_products = $this->model_account_order->getOrderProducts($order_id);
            $log->write($order_products);

            $key = array_search($product_id, array_column($order_products, 'product_id'));

            $this->load->model('assets/product');
            $product_info = $this->model_assets_product->getProductForPopup($order_products[$key]['product_id'], false, $order_products[$key]['store_id']);
            $s_price = 0;
            $o_price = 0;

            if (!$this->config->get('config_inclusiv_tax')) {
                //get price html
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $product_info['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));

                    $o_price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                } else {
                    $product_info['price'] = false;
                }
                if ((float) $product_info['special_price']) {
                    $product_info['special_price'] = $this->currency->format($this->tax->calculate($product_info['special_price'], $product_info['tax_class_id'], $this->config->get('config_tax')));

                    $s_price = $this->tax->calculate($product_info['special_price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                } else {
                    $product_info['special_price'] = false;
                }
            } else {
                $s_price = $product_info['special_price'];
                $o_price = $product_info['price'];

                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $product_info['price'] = $this->currency->format($product_info['price']);
                } else {
                    $product_info['price'] = $product_info['price'];
                }

                if ((float) $product_info['special_price']) {
                    $product_info['special_price'] = $this->currency->format($product_info['special_price']);
                } else {
                    $product_info['special_price'] = $product_info['special_price'];
                }
            }

            $cachePrice_data = $this->cache->get('category_price_data');
            if (CATEGORY_PRICE_ENABLED == true && isset($cachePrice_data) && isset($cachePrice_data[$product_info['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $order_products[$key]['store_id']])) {
                $s_price = $cachePrice_data[$product_info['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $order_products[$key]['store_id']];
                $o_price = $cachePrice_data[$product_info['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $order_products[$key]['store_id']];
                $product_info['special_price'] = $this->currency->format($s_price);
                $product_info['price'] = $this->currency->format($o_price);
            }

            $percent_off = null;
            if (isset($s_price) && isset($o_price) && 0 != $o_price && 0 != $s_price) {
                $percent_off = (($o_price - $s_price) / $o_price) * 100;
            }
            $log->write('product info');
            $log->write($product_info);
            $log->write('product info');
            $special_price = explode(' ', $product_info['special_price']);
            $log->write($special_price);
            $special_price[1] = str_replace(',', '', $special_price[1]);
            $total = $special_price[1] * $quantity + ($this->config->get('config_tax') ? ($order_products[$key]['tax'] * $quantity) : 0);
            $log->write('TOTAL');
            $log->write($total);
            $log->write('TOTAL');
            $log->write($product_id);
            $log->write($product_id);

            $this->db->query('UPDATE ' . DB_PREFIX . 'order_product SET quantity = ' . $quantity . ', total = ' . $total . " WHERE order_product_id = '" . (int) $order_products[$key]['order_product_id'] . "' AND order_id  = '" . (int) $order_id . "' AND product_id = '" . (int) $product_id . "'");
            $this->db->query('UPDATE ' . DB_PREFIX . 'real_order_product SET quantity = ' . $quantity . ', total = ' . $total . " WHERE order_product_id = '" . (int) $order_products[$key]['order_product_id'] . "' AND order_id  = '" . (int) $order_id . "' AND product_id = '" . (int) $product_id . "'");
            $order_totals = $this->db->query('SELECT SUM(total) AS total FROM ' . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");
            $order_product_details = $this->db->query('SELECT * FROM ' . DB_PREFIX . "order_product WHERE order_product_id = '" . (int) $order_products[$key]['order_product_id'] . "' AND order_id  = '" . (int) $order_id . "' AND product_id = '" . (int) $product_id . "'");
            $this->db->query('UPDATE ' . DB_PREFIX . "order_total SET `value` = '" . $order_totals->row['total'] . "' WHERE order_id = '" . $order_id . "' AND code='total'");
            $this->db->query('UPDATE ' . DB_PREFIX . "order_total SET `value` = '" . $order_totals->row['total'] . "' WHERE order_id = '" . $order_id . "' AND code='sub_total'");
            $total_products = $this->db->query('SELECT SUM(quantity) AS quantity FROM ' . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");

            $json['count_products'] = $total_products->row['quantity'];
            $json['total_amount'] = $this->currency->format($order_totals->row['total']);
            $json['quantity'] = $total_products->row['quantity'];
            $json['product_total_price'] = $this->currency->format($order_product_details->row['total']);

            if ($quantity <= 0) {
                $log = new Log('error.log');
                $log->write('DELETED');
                $log->write($quantity);
                $log->write('DELETED');
                $this->db->query("DELETE FROM `" . DB_PREFIX . "order_product` WHERE order_product_id = '" . (int) $order_products[$key]['order_product_id'] . "' AND order_id  = '" . (int) $order_id . "' AND product_id = '" . (int) $product_id . "'");
                $this->db->query("DELETE FROM `" . DB_PREFIX . "real_order_product` WHERE order_product_id = '" . (int) $order_products[$key]['order_product_id'] . "' AND order_id  = '" . (int) $order_id . "' AND product_id = '" . (int) $product_id . "'");
            }

            $log->write($order_products);
            $log->write($key);
            $log->write($order_totals->row['total']);
            $log->write($order_product_details);
            $json['status'] = true;
            $json['status'] = 'Your Order Updated!';
        } else {
            $json['status'] = 'You Cant Update Order In This Status!';
        }
        $log->write('edit_order_quantity');
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    //if any code changed in this method ,need to implement same in mobile API
    //controller/api/customer/order.php-addEditOrderWithNewitemAndQuantity()
    public function edit_full_order() {
        $log = new Log('error.log');
        $json = [];
        $json['location'] = 'module';

        $order_id = $this->request->post['order_id'];
        $log->write($order_id);
        $product_id = $this->request->post['product_id'];
        $quantity = $this->request->post['quantity'];
        $unit = $this->request->post['unit'];
        $variation_id = $this->request->post['variation_id'];
        $product_note = $this->request->post['product_note'];

        $this->load->model('account/order');
        $this->load->model('sale/orderlog');
        $order_info = $this->model_account_order->getOrder($order_id, true);
        //$log->write($order_info);
        if (null != $order_info && ($order_info['order_status_id'] == 15 || $order_info['order_status_id'] == 14) && $order_info['customer_id'] == $this->customer->getId()) {
            $order_products = $this->model_account_order->getOrderProducts($order_id);
            //$log->write($order_products);

            $key = array_search($product_id, array_column($order_products, 'product_id'));
            if ($key !== false) {
                $log->write('edit_order_quantity');
                $this->load->model('assets/product');
                $ordered_product_info = $this->model_account_order->getOrderProductByOrderProductId($order_id, $order_products[$key]['product_id'], $order_products[$key]['order_product_id']);
                $log->write('ordered_product_info');
                $log->write($ordered_product_info);
                $log->write('ordered_product_info');
                $product_info = $this->model_assets_product->getProductForPopup($order_products[$key]['product_id'], false, $order_products[$key]['store_id']);
                $s_price = 0;
                $o_price = 0;

                // if (!$this->config->get('config_inclusiv_tax')) {
                //     //get price html
                //     if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                //         $product_info['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                //         $o_price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                //     } else {
                //         $product_info['price'] = false;
                //     }
                //     if ((float) $product_info['special_price']) {
                //         $product_info['special_price'] = $this->currency->format($this->tax->calculate($product_info['special_price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                //         $s_price = $this->tax->calculate($product_info['special_price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                //     } else {
                //         $product_info['special_price'] = false;
                //     }
                // } else
                {
                    $s_price = $product_info['special_price'];
                    $o_price = $product_info['price'];

                    if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                        $product_info['price'] = $this->currency->format($product_info['price']);
                    } else {
                        $product_info['price'] = $product_info['price'];
                    }

                    if ((float) $product_info['special_price']) {
                        $product_info['special_price'] = $this->currency->format($product_info['special_price']);
                    } else {
                        $product_info['special_price'] = $product_info['special_price'];
                    }
                }

                $cachePrice_data = $this->cache->get('category_price_data');
                if (CATEGORY_PRICE_ENABLED == true && isset($cachePrice_data) && isset($cachePrice_data[$product_info['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $order_products[$key]['store_id']])) {
                    $s_price = $cachePrice_data[$product_info['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $order_products[$key]['store_id']];
                    $o_price = $cachePrice_data[$product_info['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $order_products[$key]['store_id']];
                    $product_info['special_price'] = $this->currency->format($s_price);
                    $product_info['price'] = $this->currency->format($o_price);
                }

                $percent_off = null;
                if (isset($s_price) && isset($o_price) && 0 != $o_price && 0 != $s_price) {
                    $percent_off = (($o_price - $s_price) / $o_price) * 100;
                }
                $log->write('product info');
                $log->write($product_info);
                $log->write('product info');
                $special_price = explode(' ', $product_info['special_price']);
                $log->write($special_price);
                $special_price[1] = str_replace(',', '', $special_price[1]);

                $total_without_tax = $special_price[1] * $quantity;

                $total_with_tax = $this->config->get('config_tax') ? ($this->tax->calculate($special_price[1], $product_info['tax_class_id'], $this->config->get('config_tax')) * $quantity) : 0;
                $tax = 0;
                $single_product_tax = 0;
                if ($total_with_tax > 0 && $this->config->get('config_tax') == true) {
                    $tax = $total_with_tax - $total_without_tax;
                    $log->write('TAX');
                    $log->write($total_with_tax);
                    $log->write($total_without_tax);
                    $log->write($tax);
                    $log->write('TAX');
                    $single_product_tax = $tax / $quantity;
                    $log->write('single_product_tax');
                    $log->write($single_product_tax);
                    $log->write('single_product_tax');
                }

                $total = $special_price[1] * $quantity + ($this->config->get('config_tax') ? ($order_products[$key]['tax'] * $quantity) : 0);
                $log->write('TOTAL');
                $log->write($total);
                $log->write('TOTAL');
                $log->write($special_price[1]);
                $log->write($this->tax->calculate($special_price[1], $product_info['tax_class_id'], $this->config->get('config_tax')));
                $log->write($product_id);
                $log->write($product_id);

                $data['order_id'] = $order_id;
                $data['order_product_id'] = $ordered_product_info['order_product_id'];
                $data['order_status_id'] = $order_info['order_status_id'];
                $data['product_store_id'] = $ordered_product_info['product_id'];
                $data['general_product_id'] = $ordered_product_info['general_product_id'];
                $data['store_id'] = $ordered_product_info['store_id'];
                $data['vendor_id'] = $ordered_product_info['vendor_id'];
                $data['name'] = $ordered_product_info['name'];
                $data['unit'] = $ordered_product_info['unit'];
                $data['model'] = $ordered_product_info['model'];
                $data['old_quantity'] = $ordered_product_info['quantity'];
                $data['quantity'] = $quantity;

                if (isset($this->request->post['product_note']) && $this->request->post['product_note'] != NULL) {
                    $this->db->query('UPDATE ' . DB_PREFIX . 'order_product SET product_note = "' . $product_note . '", quantity = ' . $quantity . ', tax = ' . $single_product_tax . ', total = ' . $total_without_tax . " WHERE order_product_id = '" . (int) $order_products[$key]['order_product_id'] . "' AND order_id  = '" . (int) $order_id . "' AND product_id = '" . (int) $product_id . "'");
                    $this->db->query('UPDATE ' . DB_PREFIX . 'real_order_product SET quantity = ' . $quantity . ', tax = ' . $single_product_tax . ', total = ' . $total_without_tax . " WHERE order_product_id = '" . (int) $order_products[$key]['order_product_id'] . "' AND order_id  = '" . (int) $order_id . "' AND product_id = '" . (int) $product_id . "'");
                    $this->model_sale_orderlog->addOrderLog($data);
                } else {
                    $this->db->query('UPDATE ' . DB_PREFIX . 'order_product SET quantity = ' . $quantity . ', tax = ' . $single_product_tax . ', total = ' . $total_without_tax . " WHERE order_product_id = '" . (int) $order_products[$key]['order_product_id'] . "' AND order_id  = '" . (int) $order_id . "' AND product_id = '" . (int) $product_id . "'");
                    $this->db->query('UPDATE ' . DB_PREFIX . 'real_order_product SET quantity = ' . $quantity . ', tax = ' . $single_product_tax . ', total = ' . $total_without_tax . " WHERE order_product_id = '" . (int) $order_products[$key]['order_product_id'] . "' AND order_id  = '" . (int) $order_id . "' AND product_id = '" . (int) $product_id . "'");
                    $this->model_sale_orderlog->addOrderLog($data);
                }
                $order_totals = $this->db->query('SELECT SUM(total) AS total FROM ' . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");

                $order_products_updated = $this->model_account_order->getOrderProducts($order_id);
                $total_tax_updated = 0;
                foreach ($order_products_updated as $order_products_update) {
                    $total_tax_updated += $order_products_update['quantity'] * $order_products_update['tax'];
                }

                $order_tax_totals = $this->db->query('SELECT SUM(tax) AS tax FROM ' . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");
                $log->write($order_totals->row['total']);
                $log->write($total_tax_updated);
                $log->write($order_totals->row['total'] + $total_tax_updated);
                $order_total = $order_totals->row['total'] + $total_tax_updated;
                $order_product_details = $this->db->query('SELECT * FROM ' . DB_PREFIX . "order_product WHERE order_product_id = '" . (int) $order_products[$key]['order_product_id'] . "' AND order_id  = '" . (int) $order_id . "' AND product_id = '" . (int) $product_id . "'");
                $this->db->query('UPDATE ' . DB_PREFIX . "order_total SET `value` = '" . $order_total . "' WHERE order_id = '" . $order_id . "' AND code='total'");
                $this->db->query('UPDATE ' . DB_PREFIX . "order_total SET `value` = '" . $total_tax_updated . "' WHERE order_id = '" . $order_id . "' AND code='tax'");
                $this->db->query('UPDATE ' . DB_PREFIX . "order_total SET `value` = '" . $order_totals->row['total'] . "' WHERE order_id = '" . $order_id . "' AND code='sub_total'");
                $total_products = $this->db->query('SELECT SUM(quantity) AS quantity FROM ' . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");
                $this->db->query('UPDATE ' . DB_PREFIX . "order SET `total` = '" . $order_total . "' WHERE order_id = '" . $order_id . "'");

                $json['count_products'] = $total_products->row['quantity'];
                $json['sub_total_amount'] = $this->currency->format($order_totals->row['total']);
                $json['total_amount'] = $this->currency->format($order_totals->row['total'] + $total_tax_updated);
                $json['total_tax_amount'] = $this->currency->format($total_tax_updated);
                $json['quantity'] = $total_products->row['quantity'];
                //$json['product_total_price'] = $this->currency->format($order_product_details->row['total']);
                $json['product_total_price'] = $this->currency->format($total_with_tax);

                if ($quantity <= 0) {
                    $log = new Log('error.log');
                    $log->write('DELETED');
                    $log->write($quantity);
                    $log->write('DELETED');
                    $this->db->query("DELETE FROM `" . DB_PREFIX . "order_product` WHERE order_product_id = '" . (int) $order_products[$key]['order_product_id'] . "' AND order_id  = '" . (int) $order_id . "' AND product_id = '" . (int) $product_id . "'");
                    $this->db->query("DELETE FROM `" . DB_PREFIX . "real_order_product` WHERE order_product_id = '" . (int) $order_products[$key]['order_product_id'] . "' AND order_id  = '" . (int) $order_id . "' AND product_id = '" . (int) $product_id . "'");
                }

                $log->write($order_products);
                $log->write($key);
                $log->write($order_totals->row['total']);
                $log->write($order_product_details);
                $json['status'] = true;
                $json['status'] = 'Your Order Updated!';

                // Add to activity log
                $this->load->model('account/activity');

                $activity_data = [
                    'customer_id' => $this->customer->getId(),
                    'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
                    'order_id' => $order_id,
                ];
                $log->write('account edit1');

                $this->model_account_activity->addActivity('order_product_quaantity_changed', $activity_data);

                $log->write('order_products COUNT 1');
                $log->write(count($order_products));
                $log->write('order_products COUNT 1');
                if (count($order_products) <= 0 || $order_totals->row['total'] <= 0) {

                    $log = new Log('error.log');
                    $this->load->model('account/activity');
                    $activity_data = [
                        'customer_id' => $this->customer->getId(),
                        'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
                        'order_id' => $order_id,
                    ];
                    $log->write('account cancelled by customer 1');
                    $this->model_account_activity->addActivity('order_cancelled_by_customer', $activity_data);

                    $log->write('EMPTY ORDER 1');
                    $this->refundCancelOrderByOrderId($order_id);
                    $json['status'] = true;
                    $json['redirect'] = $this->url->link('account/order', '', 'SSL');
                    $json['status'] = 'Your Order Cancelled!';
                }
            } else {
                $log->write('edit_order_new_product_added');
                $this->load->model('assets/product');
                $new_product = $this->model_assets_product->getProductByProductStoreId($this->request->post['product_id']);
                $product_info = $this->model_assets_product->getProductForPopup($new_product['product_store_id'], false, $new_product['store_id']);
                $s_price = 0;
                $o_price = 0;

                // if (!$this->config->get('config_inclusiv_tax')) {
                //     //get price html
                //     if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                //         $product_info['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                //         $o_price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                //     } else {
                //         $product_info['price'] = false;
                //     }
                //     if ((float) $product_info['special_price']) {
                //         $product_info['special_price'] = $this->currency->format($this->tax->calculate($product_info['special_price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                //         $s_price = $this->tax->calculate($product_info['special_price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                //     } else {
                //         $product_info['special_price'] = false;
                //     }
                // } else
                {
                    $s_price = $product_info['special_price'];
                    $o_price = $product_info['price'];

                    if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                        $product_info['price'] = $this->currency->format($product_info['price']);
                    } else {
                        $product_info['price'] = $product_info['price'];
                    }

                    if ((float) $product_info['special_price']) {
                        $product_info['special_price'] = $this->currency->format($product_info['special_price']);
                    } else {
                        $product_info['special_price'] = $product_info['special_price'];
                    }
                }

                $cachePrice_data = $this->cache->get('category_price_data');
                if (CATEGORY_PRICE_ENABLED == true && isset($cachePrice_data) && isset($cachePrice_data[$product_info['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $product_info['store_id']])) {
                    $s_price = $cachePrice_data[$product_info['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $product_info['store_id']];
                    $o_price = $cachePrice_data[$product_info['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $product_info['store_id']];
                    $product_info['special_price'] = $this->currency->format($s_price);
                    $product_info['price'] = $this->currency->format($o_price);
                }

                $percent_off = null;
                if (isset($s_price) && isset($o_price) && 0 != $o_price && 0 != $s_price) {
                    $percent_off = (($o_price - $s_price) / $o_price) * 100;
                }
                $log->write('product info');
                $log->write($product_info);
                $log->write('product info');
                $special_price = explode(' ', $product_info['special_price']);
                $log->write($special_price);
                $special_price[1] = str_replace(',', '', $special_price[1]);

                $total_without_tax = $special_price[1] * $quantity;

                $total_with_tax = $this->config->get('config_tax') ? ($this->tax->calculate($special_price[1], $product_info['tax_class_id'], $this->config->get('config_tax')) * $quantity) : 0;
                $tax = 0;
                $single_product_tax = 0;
                if ($total_with_tax > 0 && $this->config->get('config_tax') == true) {
                    $tax = $total_with_tax - $total_without_tax;
                    $log->write('TAX');
                    $log->write($total_with_tax);
                    $log->write($total_without_tax);
                    $log->write($tax);
                    $log->write('TAX');
                    $single_product_tax = $tax / $quantity;
                    $log->write('single_product_tax');
                    $log->write($single_product_tax);
                    $log->write('single_product_tax');
                }

                $total = $special_price[1] * $quantity + ($this->config->get('config_tax') ? ($order_products[$key]['tax'] * $quantity) : 0);
                $log->write('TOTAL');
                $log->write($total);
                $log->write('TOTAL');
                $log->write($special_price[1]);
                $log->write($this->tax->calculate($special_price[1], $product_info['tax_class_id'], $this->config->get('config_tax')));
                $log->write($product_id);
                $log->write($product_id);
                $this->load->model('extension/extension');
                $product_info['vendor_id'] = $this->model_extension_extension->getVendorId($product_info['store_id']);
                $product_note = $this->request->post['product_note'];
                $this->db->query('INSERT INTO ' . DB_PREFIX . "order_product SET product_note='" . $this->request->post['product_note'] . "', vendor_id='" . (int) $product_info['vendor_id'] . "', store_id='" . (int) $product_info['store_id'] . "', order_id = '" . (int) $this->request->post['order_id'] . "', variation_id = '" . (int) $this->request->post['variation_id'] . "', product_id = '" . (int) $product_info['product_store_id'] . "', general_product_id = '" . (int) $product_info['product_id'] . "',  name = '" . $this->db->escape($product_info['name']) . "', model = '" . $this->db->escape($product_info['model']) . "', quantity = '" . $quantity . "', price = '" . (float) $special_price[1] . "', total = '" . (float) $total_without_tax . "', tax = '" . (float) $single_product_tax . "', product_type = 'replacable', unit = '" . $this->db->escape($product_info['unit']) . "'");

                $ordered_product_info = $this->model_account_order->getOrderProductByProductId($order_id, $product_info['product_store_id']);
                $data['order_id'] = $order_id;
                $data['order_product_id'] = $ordered_product_info['order_product_id'];
                $data['order_status_id'] = $order_info['order_status_id'];
                $data['product_store_id'] = $ordered_product_info['product_id'];
                $data['general_product_id'] = $ordered_product_info['general_product_id'];
                $data['store_id'] = $ordered_product_info['store_id'];
                $data['vendor_id'] = $ordered_product_info['vendor_id'];
                $data['name'] = $ordered_product_info['name'];
                $data['unit'] = $ordered_product_info['unit'];
                $data['model'] = $ordered_product_info['model'];
                $data['old_quantity'] = 0;
                $data['quantity'] = $quantity;
                $this->model_sale_orderlog->addOrderLog($data);

                $order_totals = $this->db->query('SELECT SUM(total) AS total FROM ' . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");

                $order_products_updated = $this->model_account_order->getOrderProducts($order_id);
                $total_tax_updated = 0;
                foreach ($order_products_updated as $order_products_update) {
                    $total_tax_updated += $order_products_update['quantity'] * $order_products_update['tax'];
                }

                $order_tax_totals = $this->db->query('SELECT SUM(tax) AS tax FROM ' . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");
                $log->write($order_totals->row['total']);
                $log->write($total_tax_updated);
                $log->write($order_totals->row['total'] + $total_tax_updated);
                $order_total = $order_totals->row['total'] + $total_tax_updated;
                $order_product_details = $this->db->query('SELECT * FROM ' . DB_PREFIX . "order_product WHERE order_product_id = '" . (int) $order_products[$key]['order_product_id'] . "' AND order_id  = '" . (int) $order_id . "' AND product_id = '" . (int) $product_id . "'");
                $this->db->query('UPDATE ' . DB_PREFIX . "order_total SET `value` = '" . $order_total . "' WHERE order_id = '" . $order_id . "' AND code='total'");
                $this->db->query('UPDATE ' . DB_PREFIX . "order_total SET `value` = '" . $total_tax_updated . "' WHERE order_id = '" . $order_id . "' AND code='tax'");
                $this->db->query('UPDATE ' . DB_PREFIX . "order_total SET `value` = '" . $order_totals->row['total'] . "' WHERE order_id = '" . $order_id . "' AND code='sub_total'");
                $total_products = $this->db->query('SELECT SUM(quantity) AS quantity FROM ' . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");
                $this->db->query('UPDATE ' . DB_PREFIX . "order SET `total` = '" . $order_total . "' WHERE order_id = '" . $order_id . "'");

                $json['count_products'] = $total_products->row['quantity'];
                $json['sub_total_amount'] = $this->currency->format($order_totals->row['total']);
                $json['total_amount'] = $this->currency->format($order_totals->row['total'] + $total_tax_updated);
                $json['total_tax_amount'] = $this->currency->format($total_tax_updated);
                $json['quantity'] = $total_products->row['quantity'];
                //$json['product_total_price'] = $this->currency->format($order_product_details->row['total']);
                $json['product_total_price'] = $this->currency->format($total_with_tax);

                if ($quantity <= 0) {
                    $log = new Log('error.log');
                    $log->write('DELETED');
                    $log->write($quantity);
                    $log->write('DELETED');
                    $this->db->query("DELETE FROM `" . DB_PREFIX . "order_product` WHERE order_product_id = '" . (int) $order_products[$key]['order_product_id'] . "' AND order_id  = '" . (int) $order_id . "' AND product_id = '" . (int) $product_id . "'");
                    $this->db->query("DELETE FROM `" . DB_PREFIX . "real_order_product` WHERE order_product_id = '" . (int) $order_products[$key]['order_product_id'] . "' AND order_id  = '" . (int) $order_id . "' AND product_id = '" . (int) $product_id . "'");
                }

                $log->write($order_products);
                $log->write($key);
                $log->write($order_totals->row['total']);
                $log->write($order_product_details);
                $json['status'] = true;
                $json['status'] = 'Your Order Updated!';

                // Add to activity log
                $this->load->model('account/activity');

                $activity_data = [
                    'customer_id' => $this->customer->getId(),
                    'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
                    'order_id' => $order_id,
                ];
                $log->write('account edit1');

                $this->model_account_activity->addActivity('order_new_product_added', $activity_data);

                $log->write('order_products COUNT 2');
                $log->write(count($order_products));
                $log->write('order_products COUNT 2');
                if (count($order_products) <= 0 || $order_totals->row['total'] <= 0) {

                    $log = new Log('error.log');
                    $this->load->model('account/activity');
                    $activity_data = [
                        'customer_id' => $this->customer->getId(),
                        'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
                        'order_id' => $order_id,
                    ];
                    $log->write('account cancelled by customer 2');
                    $this->model_account_activity->addActivity('order_cancelled_by_customer', $activity_data);

                    $log->write('EMPTY ORDER 2');
                    $this->refundCancelOrderByOrderId($order_id);
                    $json['status'] = true;
                    $json['redirect'] = $this->url->link('account/order', '', 'SSL');
                    $json['status'] = 'Your Order Cancelled!';
                }
            }
        } else {
            $json['status'] = 'You Cant Update Order In This Status!';
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    //if below method modified , please check in API-->Orders.php-getOrderProductsWithVariancesNew
    public function getOrderProductsWithVariancesNew($order_id) {

        $this->load->model('account/order');
        $orderProducts = [];

        if ($this->model_account_order->hasRealOrderProduct($order_id)) {
            // Order products with weight change
            $originalProducts = $products = $this->model_account_order->getOnlyRealOrderProducts($order_id);
        } else {

            // Products as the user ordered them on the platform
            $originalProducts = $products = $this->model_account_order->getOnlyOrderProducts($order_id);
        }

        //echo "<pre>";print_r($originalProducts);die;

        foreach ($originalProducts as $originalProduct) {
            // $totalUpdated = $originalProduct['price'] * $originalProduct['quantity']
            //     + ($this->config->get('config_tax') ? $originalProduct['tax'] : 0);
            //in admin orders screen, directly showing total
            $totalUpdated = $originalProduct['total'];

            $uomOrderedWithoutApproximations = trim(explode('(', $originalProduct['unit'])[0]);

            $orderProducts[] = [
                'order_product_id' => $originalProduct['order_product_id'],
                'product_id' => $originalProduct['product_id'],
                'vendor_id' => $originalProduct['vendor_id'],
                'store_id' => $originalProduct['store_id'],
                'name' => $originalProduct['name'],
                'unit' => $uomOrderedWithoutApproximations,
                'product_type' => $originalProduct['product_type'],
                'model' => $originalProduct['model'],
                'quantity' => $originalProduct['quantity'],
                'quantity_updated' => $originalProduct['quantity'],
                'unit_updated' => $uomOrderedWithoutApproximations,
                'price' => $this->currency->format($originalProduct['price'] + ($this->config->get('config_tax') ? $originalProduct['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                'total' => $this->currency->format($originalProduct['total'] + ($this->config->get('config_tax') ? ($originalProduct['tax'] * $originalProduct['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
                'total_updated' => $this->currency->format($totalUpdated, $order_info['currency_code'], $order_info['currency_value']),
                'total_updated_currency' => trim(explode(' ', $this->currency->format($totalUpdated, $order_info['currency_code'], $order_info['currency_value']))[0]),
                'total_updated_value' => trim(explode(' ', $this->currency->format($totalUpdated, $order_info['currency_code'], $order_info['currency_value']))[1]),
                'total_updatedvalue' => $totalUpdated,
            ];
        }

        return $orderProducts;
    }

    public function export_products_excel($order_id) {
        $data = [];

        $orderid = $this->request->get['order_id'];

        $this->load->model('account/order');
        $order_info = $this->model_account_order->getOrder($orderid);
        //  echo "<pre>";print_r($order_info);die;


        $customer = $order_info['firstname'] . ' ' . $order_info['lastname'];
        $company = $this->request->get['company'];
        $date = $order_info['order_date'];
        $deliverydate = $order_info['delivery_date'];
        $shippingaddress = $order_info['shipping_address'] . ' . ' . $order_info['shipping_city'] . ' ' . $order_info['zipcode'];
        $paymentmethod = $order_info['payment_method'];

        $data['consolidation'][] = [
            'orderid' => $orderid,
            'customer' => $customer,
            'company' => $company,
            'date' => $date,
            'deliverydate' => $deliverydate,
            'shippingaddress' => $shippingaddress,
            'paymentmethod' => $paymentmethod,
        ];

        $orderProducts = $this->getOrderProductsWithVariancesNew($orderid);
        $data['products'] = $orderProducts;

        // echo "<pre>";print_r($orderProducts);die;
        // $sum = 0;
        // foreach ($orderProducts as $item) {
        //     $sum += $item['total_updatedvalue'];
        // } 


        $this->load->model('account/order');
        $this->model_account_order->download_products_excel($data);
    }

    public function edit_your_order() {
        $this->load->model('account/customer');
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

        $log = new Log('error.log');
        $hours = 0;
        $t1 = strtotime(date('Y-m-d H:i:s'));
        $t2 = strtotime($order_info['order_date']);
        $diff = $t1 - $t2;
        $hours = $diff / ( 60 * 60 );
        $log->write('hours');
        $log->write(date('Y-m-d H:i:s'));
        $log->write($order_info['order_date']);
        $log->write($hours);
        $log->write('hours');

        if ($order_info && $order_info['customer_id'] == $this->customer->getId() && ($order_info['order_status_id'] == 15 || $order_info['order_status_id'] == 14) && $hours <= 5 && $order_info['payment_code'] == 'cod') {
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

                if (file_exists(DIR_IMAGE . $product['image'])) {
                    $image = $this->model_tool_image->resize($product['image'], 80, 100);
                } else {
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
            $this->load->model('account/address');
            $store_info = $this->model_account_address->getStoreData($order_info['store_id']);
            $log->write($store_info);
            $data['store_warning'] = '';
            if ($this->config->get('config_active_store_minimum_order_amount') > $this->cart->getSubTotal()) {
                $currentprice = $this->config->get('config_active_store_minimum_order_amount') - $this->cart->getSubTotal();
                $data['store_warning'] = "<center style='background-color:#ee4054;color:#fff'>" . $this->currency->format($currentprice) . ' away from minimum order value </center>';
            }

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
            $data['order_customer_id'] = $order_info['customer_id'];
            $data['loogged_customer_id'] = $this->customer->getId();
            $data['order_status_id'] = $order_info['order_status_id'];
            $data['order_status_name'] = $order_info['status'];

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header/orderSummaryHeaders');

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

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/edit_your_order.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/edit_your_order.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/account/edit_your_order.tpl', $data));
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
            $data['header'] = $this->load->controller('common/header/orderSummaryHeaders');

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
            }
        }
    }

    public function export_incomplete0rder_products_excel($order_id) {
        $data = [];

        $orderid = $this->request->get['order_id'];

        $this->load->model('account/order');
        $order_info = $this->model_account_order->getIncompleteOrder($orderid);
        //    echo "<pre>";print_r($order_info);die;


        $customer = $order_info['firstname'] . ' ' . $order_info['lastname'];
        $company = $this->request->get['company'];
        $date = $order_info['date_added'];
        $deliverydate = $order_info['delivery_date'];
        $shippingaddress = $order_info['shipping_address'] . ' . ' . $order_info['shipping_city'] . ' ' . $order_info['zipcode'];
        $paymentmethod = $order_info['payment_method'];

        $data['consolidation'][] = [
            'orderid' => $orderid,
            'customer' => $customer,
            'company' => $company,
            'date' => $date,
            'deliverydate' => $deliverydate,
            'shippingaddress' => $shippingaddress,
            'paymentmethod' => $paymentmethod,
        ];

        $orderProducts = $this->getOrderProductsWithVariancesNew($orderid);
        $data['products'] = $orderProducts;

        // echo "<pre>";print_r($orderProducts);die;
        // $sum = 0;
        // foreach ($orderProducts as $item) {
        //     $sum += $item['total_updatedvalue'];
        // } 


        $this->load->model('account/order');
        $this->model_account_order->download_products_excel($data);
    }

    public function addMissedRejectedProducts() {
        $log = new Log('error.log');
        $this->load->model('account/order');
        $this->load->model('account/missedrejectedproducts');
        //$log->write($this->request->post);
        $data = $this->request->post;
        $products = $this->model_account_order->getRealOrderProducts($data['order_id']);
        if ($products == NULL || (is_array($products) && count($products) <= 0)) {
            $products = $this->model_account_order->getOrderProducts($data['order_id']);
        }

        $report = NULL;
        foreach ($products as $product) {
            $report['order_id'] = $data['order_id'];
            $report['product_id'] = $product['product_id'];
            $report['product_store_id'] = $product['product_id'];
            $report['type'] = $data['issue_type'][$product['product_id']];
            $report['quantity'] = $data['qty'][$product['product_id']];
            $report['notes'] = $data['product_notes'][$product['product_id']];
            $this->model_account_missedrejectedproducts->addProducts($report);
            $log->write($report);
        }
    }

}
