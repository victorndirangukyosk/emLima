<?php

require_once DIR_SYSTEM . '/vendor/konduto/vendor/autoload.php';

use Konduto\Core\Konduto;
use Konduto\Models;

require_once DIR_APPLICATION . '/controller/api/settings.php';

class ControllerCheckoutSuccess extends Controller {

    public function index() {
        //$this->load->language( 'account/register' );

        $this->load->model('account/customer');
        $parent_info = $this->model_account_customer->getCustomer($_SESSION['parent']);

        $is_he_parents = $this->model_account_customer->CheckHeIsParent();
        $parent_customer_info = $this->model_account_customer->getCustomer($is_he_parents);
        $sub_customer_order_approval_required = 1;
        if (isset($parent_customer_info) && $parent_customer_info != NULL && is_array($parent_customer_info)) {
            $sub_customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
            $sub_customer_order_approval_required = $sub_customer_info['sub_customer_order_approval'];
        }

        $this->load->language('checkout/success');
        /* $log = new Log('error.log');
          $log->write('parent_info');
          $log->write($parent_info);
          $log->write('parent_info'); */

        $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_checkout.css');

        if (!empty($_SESSION['parent']) && ($this->session->data['order_approval_access'] == 0 && $this->session->data['order_approval_access_role'] == NULL && $sub_customer_order_approval_required == 1)) {
            $this->document->setTitle($this->language->get('heading_title_sub_user'));
        }
        if (empty($_SESSION['parent']) || ($this->session->data['order_approval_access'] > 0 && $this->session->data['order_approval_access_role'] != NULL) || $sub_customer_order_approval_required == 0) {
            $this->document->setTitle($this->language->get('heading_title'));
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_basket'),
            'href' => $this->url->link('checkout/cart'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_checkout'),
            'href' => $this->url->link('checkout/checkout', '', 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_success'),
            'href' => $this->url->link('checkout/success'),
        ];

        $data['referral_description'] = $this->language->get('referral_description');
        if (!empty($_SESSION['parent']) && ($this->session->data['order_approval_access'] == 0 && $this->session->data['order_approval_access_role'] == NULL && $sub_customer_order_approval_required == 1)) {
            $data['heading_title'] = $this->language->get('heading_title_sub_user');
        }
        if (empty($_SESSION['parent']) || ($this->session->data['order_approval_access'] > 0 && $this->session->data['order_approval_access_role'] != NULL) || $sub_customer_order_approval_required == 0) {
            $data['heading_title'] = $this->language->get('heading_title');
        }

        $data['text_basket'] = $this->language->get('text_basket');
        if (empty($_SESSION['parent']) || ($this->session->data['order_approval_access'] > 0 && $this->session->data['order_approval_access_role'] != NULL) || $sub_customer_order_approval_required == 0) {
            $data['text_customer'] = $this->language->get('text_customer');
        }
        if (!empty($_SESSION['parent']) && ($this->session->data['order_approval_access'] == 0 && $this->session->data['order_approval_access_role'] == NULL && $sub_customer_order_approval_required == 1)) {
            $data['text_customer'] = $this->language->get('text_customer_sub_user_new');
        }
        $data['text_guest'] = $this->language->get('text_guest');
        $data['text_order_id'] = $this->language->get('text_order_id');

        // Get Order Status enter Message
        if ($this->customer->isLogged() && (empty($_SESSION['parent']) || ($this->session->data['order_approval_access'] > 0 && $this->session->data['order_approval_access_role'] != NULL) || $sub_customer_order_approval_required == 0)) {
            $data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/order', '', 'SSL'), $this->url->link('account/account', '', 'SSL'));
        } elseif ($this->customer->isLogged() && (!empty($_SESSION['parent']) && ($this->session->data['order_approval_access'] == 0 && $this->session->data['order_approval_access_role'] == NULL && $sub_customer_order_approval_required == 1))) {
            $data['text_message'] = sprintf($this->language->get('text_customer_sub_user_new'), $parent_info['firstname'], $parent_info['lastname'], $this->url->link('account/order', '', 'SSL'), $this->url->link('account/account', '', 'SSL'));
        } else {
            $data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
        }

        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        if (isset($this->session->data['order_id'])) {
            $this->load->model('checkout/success');
            $this->load->model('checkout/order');

            // $shipping_code = $this->model_checkout_success->getShippingCode($this->session->data['order_id']);
            // if(isset($shipping_code) && $shipping_code == 'shopper.shopper'){
            //     $this->model_checkout_success->notify_shoppers($this->session->data['order_id']);
            // }

            /* if (isset($this->session->data['reward'])) {
              $this->model_checkout_order->updateOrderCoupons($this->session->data['order_id'],$this->session->data['reward']);
              } */

            $this->cart->clear();
            // add wallet bal here

            $config_reward_switch_order_value = $this->config->get('config_reward_switch_order_value');
            $config_reward_on_order_total = $this->config->get('config_reward_on_order_total');
            $config_reward_enabled = $this->config->get('config_reward_enabled');

            $fixedDivide = count($this->session->data['order_id']);
            $this->load->model('account/customer');

            // Add to activity log
            foreach ($this->session->data['order_id'] as $order_id) {
                $this->load->model('account/activity');

                if ($this->customer->isLogged()) {
                    $activity_data = [
                        'customer_id' => $this->customer->getId(),
                        'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
                        'order_id' => $order_id,
                    ];

                    $this->model_account_activity->addActivity('order_account', $activity_data);
                } else {
                    $activity_data = [
                        'name' => $this->session->data['guest']['firstname'] . ' ' . $this->session->data['guest']['lastname'],
                        'order_id' => $order_id,
                    ];

                    $this->model_account_activity->addActivity('order_guest', $activity_data);
                }
                // Get Message order status message
                //$message = $this->model_checkout_success->getMessage($order_id);
                //$data['text_message'] = html_entity_decode(sprintf($message, $this->url->link('account/account', '', 'SSL'), $this->url->link('account/order', '', 'SSL'), $this->url->link('account/download', '', 'SSL'), $this->url->link('information/contact')));
                //$data['text_message'] = $message;

                $order_info = $this->model_checkout_order->getOrder($order_id);

                $recommedation = $this->getOrderRecommendation($order_id);

                $total = $order_info['total'];

                /*
                  refer reward on first order only
                 */

                $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

                /* if ($config_reward_enabled) {
                  // get order detail by order id
                  if ($config_reward_switch_order_value=='p') {
                  // its Percentage
                  $points = ($total * $config_reward_on_order_total)/100;

                  }else{
                  // its Fixed value
                  $points = $config_reward_on_order_total/$fixedDivide;

                  }


                  // insert query comes here
                  $this->model_checkout_order->setCustomerReward($order_info['customer_id'],$order_info['order_id'],$this->language->get('text_order_id'),$points);


                  } */
                if (!empty($_SESSION['parent']) && $sub_customer_order_approval_required == 1) {
                    $this->load->model('checkout/order');
                    $this->model_checkout_order->SendMailToParentUser($order_info['order_id']);
                }
                $this->session->data['completed_order_id'] = $this->session->data['order_id'];
                unset($this->session->data['shipping_method']);
                unset($this->session->data['shipping_methods']);
                unset($this->session->data['payment_method']);
                unset($this->session->data['payment_methods']);
                unset($this->session->data['guest']);
                unset($this->session->data['comment']);
                unset($this->session->data['order_id']);
                unset($this->session->data['coupon']);
                unset($this->session->data['reward']);
                unset($this->session->data['voucher']);
                unset($this->session->data['vouchers']);
                unset($this->session->data['totals']);
                unset($this->session->data['transaction_id']);
                unset($this->session->data['shipping_address_id']);
            }
        }

        $data['button_continue'] = $this->language->get('button_continue');

        $data['continue'] = $this->url->link('common/home');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/onlyHeader');

        /* ORDER SUMMARY */
        $this->load->language('account/order');
        $this->load->language('account/return');

        $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_login.css');

        $order_id = $this->session->data['completed_order_id'][75];
        $log = new Log('error.log');
        $log->write('completed_order_id');
        $log->write($order_id);
        $log->write('completed_order_id');

        $data['kondutoStatus'] = $this->config->get('config_konduto_status');

        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        //$this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/order');
        $order_info = $this->model_account_order->getOrder($order_id, true);
        $data['cashback_condition'] = $this->language->get('cashback_condition');

        if ($order_info) {
            $data['cashbackAmount'] = $this->currency->format(0);

            $coupon_history_data = $this->model_account_order->getCashbackAmount($order_id);

            if (count($coupon_history_data) > 0) {
                $data['cashbackAmount'] = $this->currency->format((-1 * $coupon_history_data['amount']));
            }

            //$this->document->setTitle($this->language->get('text_order'));

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
                'href' => $this->url->link('account/order/info', 'order_id=' . $order_id . $url, 'SSL'),
            ];

            $data['text_go_back'] = $this->language->get('text_go_back');
            $data['text_order_id_with_colon'] = $this->language->get('text_order_id_with_colon');
            $data['text_items'] = $this->language->get('text_items');
            $data['text_products'] = $this->language->get('text_products');

            $data['text_coupon_willbe_credited'] = $this->language->get('text_coupon_willbe_credited');
            $data['text_coupon_credited'] = $this->language->get('text_coupon_credited');

            //$data['heading_title'] = $this->language->get('heading_title');
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

                $end = $order_info['date_modified'];

                $timeFirst = strtotime($start);
                $timeSecond = strtotime($end);

                $differenceInSeconds = $timeFirst - $timeSecond;

                if ($differenceInSeconds <= $this->config->get('config_return_timeout')) {
                    $data['can_return'] = true;
                }
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
            $data['order_id'] = $order_id;
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

                    $resp = $this->load->controller('deliversystem/deliversystem/getDeliveryStatus', $data);
                    if (!$resp['status'] || isset($resp['error'])) {
                        $data['delivery_data'] = [];
                    } else {
                        $data['delivery_data'] = $resp['data'][0];
                    }

                    if (!$productStatus['status'] || !(count($productStatus['data']) > 0)) {
                        $data['products_status'] = [];
                    } else {
                        $data['products_status'] = $productStatus['data'];
                    }

                    $log->write('order log');
                    $log->write($data['products_status']);
                }
            }

            // Products
            $data['products'] = [];

            $products = $this->model_account_order->getOrderProducts($order_id);

            $returnProductCount = 0;
            foreach ($products as $product) {
                $option_data = [];

                $options = $this->model_account_order->getOrderOptions($order_id, $product['order_product_id']);

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

            $vouchers = $this->model_account_order->getOrderVouchers($order_id);

            foreach ($vouchers as $voucher) {
                $data['vouchers'][] = [
                    'description' => $voucher['description'],
                    'amount' => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
                ];
            }

            // Totals
            $data['totals'] = [];

            $totals = $this->model_account_order->getOrderTotals($order_id);

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

            $results = $this->model_account_order->getOrderHistories($order_id);

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
        }
        /* ORDER SUMMARY */

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/success.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/common/success.tpl', $data));
        }
    }

    public function orderfailed() {
        //$this->load->language( 'account/register' );

        $this->load->language('checkout/success');

        $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_checkout.css');

        $this->document->setTitle($this->language->get('heading_title_failed'));

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_basket'),
            'href' => $this->url->link('checkout/cart'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_checkout'),
            'href' => $this->url->link('checkout/checkout', '', 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_success'),
            'href' => $this->url->link('checkout/success'),
        ];

        $data['referral_description'] = $this->language->get('referral_description');
        $data['heading_title'] = $this->language->get('heading_title_failed');
        $data['text_basket'] = $this->language->get('text_basket');
        $data['text_customer'] = $this->language->get('text_customer_failed');
        $data['text_guest'] = $this->language->get('text_guest');
        $data['text_order_id'] = $this->language->get('text_order_id');

        // Get Order Status enter Message
        if ($this->customer->isLogged()) {
            $data['text_message'] = sprintf($this->language->get('text_customer_failed'), $this->url->link('account/order', '', 'SSL'), $this->url->link('account/account', '', 'SSL'));
        } else {
            $data['text_message'] = sprintf($this->language->get('text_customer_failed'), $this->url->link('information/contact'));
        }

        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        if (isset($this->session->data['order_id'])) {
            $this->load->model('checkout/success');
            $this->load->model('checkout/order');

            // $shipping_code = $this->model_checkout_success->getShippingCode($this->session->data['order_id']);
            // if(isset($shipping_code) && $shipping_code == 'shopper.shopper'){
            //     $this->model_checkout_success->notify_shoppers($this->session->data['order_id']);
            // }

            /* if (isset($this->session->data['reward'])) {
              $this->model_checkout_order->updateOrderCoupons($this->session->data['order_id'],$this->session->data['reward']);
              } */

            $this->cart->clear();
            // add wallet bal here

            $config_reward_switch_order_value = $this->config->get('config_reward_switch_order_value');
            $config_reward_on_order_total = $this->config->get('config_reward_on_order_total');
            $config_reward_enabled = $this->config->get('config_reward_enabled');

            $fixedDivide = count($this->session->data['order_id']);
            $this->load->model('account/customer');

            // Add to activity log
            foreach ($this->session->data['order_id'] as $order_id) {
                $this->load->model('account/activity');

                if ($this->customer->isLogged()) {
                    $activity_data = [
                        'customer_id' => $this->customer->getId(),
                        'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
                        'order_id' => $order_id,
                    ];

                    $this->model_account_activity->addActivity('order_account', $activity_data);
                } else {
                    $activity_data = [
                        'name' => $this->session->data['guest']['firstname'] . ' ' . $this->session->data['guest']['lastname'],
                        'order_id' => $order_id,
                    ];

                    $this->model_account_activity->addActivity('order_guest', $activity_data);
                }
                // Get Message order status message
                //$message = $this->model_checkout_success->getMessage($order_id);
                //$data['text_message'] = html_entity_decode(sprintf($message, $this->url->link('account/account', '', 'SSL'), $this->url->link('account/order', '', 'SSL'), $this->url->link('account/download', '', 'SSL'), $this->url->link('information/contact')));
                //$data['text_message'] = $message;

                $order_info = $this->model_checkout_order->getOrder($order_id);

                $recommedation = $this->getOrderRecommendation($order_id);

                $total = $order_info['total'];

                /*
                  refer reward on first order only
                 */

                $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

                /* if ($config_reward_enabled) {
                  // get order detail by order id
                  if ($config_reward_switch_order_value=='p') {
                  // its Percentage
                  $points = ($total * $config_reward_on_order_total)/100;

                  }else{
                  // its Fixed value
                  $points = $config_reward_on_order_total/$fixedDivide;

                  }


                  // insert query comes here
                  $this->model_checkout_order->setCustomerReward($order_info['customer_id'],$order_info['order_id'],$this->language->get('text_order_id'),$points);


                  } */

                unset($this->session->data['shipping_method']);
                unset($this->session->data['shipping_methods']);
                unset($this->session->data['payment_method']);
                unset($this->session->data['payment_methods']);
                unset($this->session->data['guest']);
                unset($this->session->data['comment']);
                unset($this->session->data['order_id']);
                unset($this->session->data['coupon']);
                unset($this->session->data['reward']);
                unset($this->session->data['voucher']);
                unset($this->session->data['vouchers']);
                unset($this->session->data['totals']);
                unset($this->session->data['transaction_id']);
                unset($this->session->data['shipping_address_id']);
            }
        }

        $data['button_continue'] = $this->language->get('button_continue');

        $data['continue'] = $this->url->link('common/home');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/onlyHeader');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/success.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/common/success.tpl', $data));
        }
    }

    public function pesapalsuccess() {
        //$this->load->language( 'account/register' );

        $this->load->language('checkout/success');

        $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_checkout.css');

        if (!empty($_SESSION['parent'])) {
            $this->document->setTitle($this->language->get('heading_title_sub_user'));
        }
        if (empty($_SESSION['parent'])) {
            $this->document->setTitle($this->language->get('heading_title'));
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_basket'),
            'href' => $this->url->link('checkout/cart'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_checkout'),
            'href' => $this->url->link('checkout/checkout', '', 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_success'),
            'href' => $this->url->link('checkout/success'),
        ];

        $data['referral_description'] = $this->language->get('referral_description');
        if (!empty($_SESSION['parent'])) {
            $data['heading_title'] = $this->language->get('heading_title_sub_user');
        }
        if (empty($_SESSION['parent'])) {
            $data['heading_title'] = $this->language->get('heading_title');
        }

        $data['text_basket'] = $this->language->get('text_basket');
        if (empty($_SESSION['parent'])) {
            $data['text_customer'] = $this->language->get('text_customer');
        }
        if (!empty($_SESSION['parent'])) {
            $data['text_customer'] = $this->language->get('text_customer_sub_user');
        }
        $data['text_guest'] = $this->language->get('text_guest');
        $data['text_order_id'] = $this->language->get('text_order_id');

        // Get Order Status enter Message
        if ($this->customer->isLogged() && empty($_SESSION['parent'])) {
            $data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/order', '', 'SSL'), $this->url->link('account/account', '', 'SSL'));
        } elseif ($this->customer->isLogged() && !empty($_SESSION['parent'])) {
            $data['text_message'] = sprintf($this->language->get('text_customer_sub_user'), $this->url->link('account/order', '', 'SSL'), $this->url->link('account/account', '', 'SSL'));
        } else {
            $data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
        }

        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        $data['button_continue'] = $this->language->get('button_continue');

        $data['continue'] = $this->url->link('common/home');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/onlyHeader');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/success.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/common/success.tpl', $data));
        }
    }

    public function pesapalfailed() {
        //$this->load->language( 'account/register' );

        $this->load->language('checkout/success');

        $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_checkout.css');

        $this->document->setTitle($this->language->get('heading_title_failed'));

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_basket'),
            'href' => $this->url->link('checkout/cart'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_checkout'),
            'href' => $this->url->link('checkout/checkout', '', 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_success'),
            'href' => $this->url->link('checkout/success'),
        ];

        $data['referral_description'] = $this->language->get('referral_description');
        $data['heading_title'] = $this->language->get('heading_title_failed');
        $data['text_basket'] = $this->language->get('text_basket');
        $data['text_customer'] = $this->language->get('text_customer_failed');
        $data['text_guest'] = $this->language->get('text_guest');
        $data['text_order_id'] = $this->language->get('text_order_id');

        // Get Order Status enter Message
        if ($this->customer->isLogged()) {
            $data['text_message'] = sprintf($this->language->get('text_customer_failed'), $this->url->link('account/order', '', 'SSL'), $this->url->link('account/account', '', 'SSL'));
        } else {
            $data['text_message'] = sprintf($this->language->get('text_customer_failed'), $this->url->link('information/contact'));
        }

        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        $data['button_continue'] = $this->language->get('button_continue');

        $data['continue'] = $this->url->link('common/home');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/onlyHeader');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/success.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/common/success.tpl', $data));
        }
    }

    public function sendOrderRating() {
        $data['email'] = $this->config->get('config_delivery_username');
        $data['password'] = $this->config->get('config_delivery_secret');

        $data['delivery_id'] = $this->request->post['delivery_id'];

        $data['rating'] = $this->request->post['rating'];
        $data['review'] = $this->request->post['review'];

        //$data['rating'] = 3;

        $response = $this->load->controller('deliversystem/deliversystem/getToken', $data);

        if ($response['status']) {
            $data['token'] = $response['token'];

            $respon = $this->load->controller('deliversystem/deliversystem/postRating', $data);

            //print_r($respon);
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput($respon);
        }
    }

    public function saveOrderRating() {
        $response['status'] = true;

        $order_id = $this->request->post['order_id'];

        $rating = $this->request->post['rating'];
        $this->load->model('account/order');

        $this->model_account_order->saveRatingOrder($rating, $order_id);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($response));
    }

    public function getOrderRecommendation($order_id) {
        $log = new Log('error.log');
        $log->write('getOrderRecommendation');

        $status = false;
        $this->load->model('checkout/order');
        $visitor_id = null;

        if (isset($this->session->data['visitor_id'])) {
            $visitor_id = $this->session->data['visitor_id'];
        }

        $order_info = $this->model_checkout_order->getOrder($order_id);

        $kondutoStatus = $this->config->get('config_konduto_status');

        $deliverSystemStatus = $this->config->get('config_deliver_system_status');

        $checkoutDeliverSystemStatus = $this->config->get('config_checkout_deliver_system_status');

        if ($deliverSystemStatus && $checkoutDeliverSystemStatus) {
            $allowedShippingMethods = $this->config->get('config_delivery_shipping_methods_status');
            //echo "<pre>";print_r($allowedShippingMethods);die;
            if (is_array($allowedShippingMethods) && count($allowedShippingMethods) > 0) {
                foreach ($allowedShippingMethods as $method) {
                    if ($order_info['shipping_code'] == $method . '.' . $method) {
                        $deliverSystemStatus = true;
                    }
                }
            }
        } else {
            $deliverSystemStatus = false;
        }

        $log->write($kondutoStatus);
        if (is_array($order_info) && $kondutoStatus) {
            $log->write('kondutoStatus if');
            $total = (int) $order_info['total'];
            $orderId = $order_info['order_id'];
            $customerId = $order_info['customer_id'];
            $currency_code = $order_info['currency_code'];
            $ip = $order_info['ip'];
            $date_modified = $order_info['date_modified'];
            $date_modified = date("Y-m-d\TH:i:s\Z", strtotime($date_modified));
            //echo "<pre>";print_r($order_info);
            if (isset($customerId)) {
                $customer_info = $this->model_checkout_order->getCustomer($order_info['customer_id']);

                //print_r($customer_info);die;
                $customerName = $customer_info['firstname'] . ' ' . $customer_info['lastname'];
                $customerEmail = $customer_info['email'];
                $customerAdded = $customer_info['date_added'];
                $dob = $customer_info['dob'];
                $telephone = $customer_info['telephone'];

                $log->write('dob');
                $log->write($dob);
                if (isset($dob)) {
                    $log->write('in');
                    $s = $dob;
                    $dt = new DateTime($s);

                    $dob = $dt->format('Y-m-d');
                }

                $log->write($dob);
                $s = $customerAdded;
                $dt = new DateTime($s);

                $customerAdded = $dt->format('Y-m-d');

                $present = $this->getOrderProductsForKonduto($orderId);

                $order = new Models\Order([
                    'id' => $orderId, //r
                    'visitor' => $visitor_id,
                    'total_amount' => $total, //r
                    //"shipping_amount" => 20.00,
                    //"tax_amount" => 3.45,
                    'currency' => $currency_code,
                    //"installments" => 1,
                    //"ip" => "170.149.100.10",
                    'ip' => $ip,
                    //"purchased_at" => "2015-04-25T22:29:14Z",
                    'purchased_at' => $date_modified,
                    'customer' => [
                        'id' => $customerId, //required)
                        'name' => $customerName, //(required)
                        //"tax_id" => "12345678909",
                        'dob' => $dob,
                        'phone1' => $telephone,
                        'email' => $customerEmail, //(required)
                        'created_at' => $customerAdded,],
                    'shipping' => [
                        'name' => $customerName, //(required)
                        'address1' => $order_info['shipping_address'],
                        'city' => $order_info['shipping_city'],
                        'zip' => $order_info['shipping_zipcode'],
                    ],
                    'shopping_cart' => $present, //(required) ,
                        //'payment' => array('type' => 'credit','status' => 'approved, declined or pending' )//(required) //(required)
//Payment type used by the customer. We support credit, boleto, debit, transfer and voucher.
                ]);

                $kondutoPrivateKey = $this->config->get('config_konduto_private_key');
                //Konduto::setApiKey("T32929BF22F782E09AE89");
                Konduto::setApiKey($kondutoPrivateKey);
                $comment = 'Approved';
                try {
                    $order = Konduto::analyze($order);

                    $recommendation = $order->getRecommendation();

                    //$recommendation = 'APPROVE';
                    if ('APPROVE' == $recommendation) {
                        $recommendation = 'approved';
                    }

                    if ('approved' == strtolower($recommendation)) {//approve
                        if ($deliverSystemStatus) {
                            $log->write('kondutoStatus else');
                            $this->createDeliveryRequest($order_id);
                        } else {
                            $log->write('deliverSystemStatus else');
                        }
                    } else {
                        //update order status in our system

                        $comment = 'POSSIBLE FRAUDSTER';

                        $this->load->model('localisation/order_status');

                        $order_status = $this->model_localisation_order_status->getOrderStatuses();

                        $log->write($order_status);

                        $order_status_id = 'no';

                        $order_status_id = $this->config->get('config_konduto_status_id');

                        /* foreach ($order_status as $order_state) {
                          # code...
                          if(strtolower($order_state['name']) == 'possible fraud' ) {
                          $order_status_id = $order_state['order_status_id'];
                          break;
                          }
                          } */

                        $log->write($order_status_id);
                        if ('no' != $order_status_id) {

                            $this->load->model('account/customer');
                            $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

                            $this->model_checkout_order->addOrderHistory($order_id, $order_status_id, $comment, true, $customer_info['customer_id'], 'customer');
                        } else {
                            $log->write('order_status_id no match');
                        }
                    }

                    $order = Konduto::updateOrderStatus($orderId, $recommendation, $comment);
                    $status = true;
                } catch (Exception $e) {
                    echo "\nException: {$e->getMessage()}\n";
                    //Exception: { "status": "error", "message": { "where": "\/", "why": { "expected": "new record", "found": "record already exists" } } }
                }
            }
        } else {
            if ($deliverSystemStatus) {
                $log->write('kondutoStatus else');
                $this->createDeliveryRequest($order_id);
            } else {
                $log->write('deliverSystemStatus else');
            }
        }
    }

    public function isOnlinePayment($payment_code) {
        $refundToCustomerWallet = false;

        $allowedPaymentMethods = $this->config->get('config_payment_methods_status');

        if (is_array($allowedPaymentMethods) && count($allowedPaymentMethods) > 0) {
            foreach ($allowedPaymentMethods as $method) {
                if ($payment_code == $method) {
                    $refundToCustomerWallet = true;
                }
            }
        }

        return $refundToCustomerWallet;
    }

    public function createDeliveryRequest($order_id, $order_status_id = 1) {
        $log = new Log('error.log');
        $order_info = $this->getOrder($order_id);

        if (1 == $order_status_id && $order_info) {
            $log->write('inside createDeliveryRequest');
            $this->load->model('account/order');

            $data['products']['products'] = [];

            $products = $this->model_account_order->getOrderProducts($order_id);

            //echo "<pre>";print_r($products);die;

            $weight = 0;

            $log = new Log('error.log');

            $log->write('tester log');
            $log->write($order_info);

            $this->load->language('checkout/success');

            foreach ($products as $product) {
                $weight += ($product['weight'] * $product['quantity']);
                $replacable = 'no';

                if ('replacable' == $product['product_type']) {
                    $replacable = 'yes';
                }

                $this->load->model('tool/image');

                if (file_exists(DIR_IMAGE . $product['image'])) {
                    $image = HTTP_IMAGE . $product['image'];
                } else {
                    $image = HTTP_IMAGE . 'placeholder.png';
                }

                $var = [
                    'product_name' => htmlspecialchars_decode($product['name']),
                    'product_unit' => $product['unit'],
                    'product_quantity' => $product['quantity'],
                    'product_weight' => ($product['weight'] * $product['quantity']),
                    'product_image' => $image, //"http:\/\/\/product-images\/camera.jpg",
                    'product_price' => $product['price'], //"1500.00",//product price unit price?? or total
                    'product_replaceable' => $replacable, //"no"
                ];

                array_push($data['products']['products'], $var);
            }

            $data['text_weight'] = sprintf($this->language->get('text_weight'), $weight);

            $log->write($data['products']['products']);

            $log->write($data['text_weight']);

            $store_details = $this->model_account_order->getStoreById($order_info['store_id']);

            $log->write($store_details);

            $delivery_priority = 'normal';

            $temp = explode('.', $order_info['shipping_code']);
            if (isset($temp[0])) {
                $delivery_priority = $temp[0];
            }

            $store_city_name = $this->model_account_order->getCityName($store_details['city_id']);

            $store_state_name = $this->model_account_order->getCityState($store_details['city_id']);

            //get state name from city id belongs to
            $timeSlotAverage = $this->getTimeslotAverage($order_info['delivery_timeslot']);

            $deliverAddress = $order_info['shipping_flat_number'] . ', ' . $order_info['shipping_building_name'] . ', ' . $order_info['shipping_landmark'];

            $this->load->model('sale/order');

            $new_total = 0;

            $totals = $this->model_sale_order->getOrderTotals($order_id);

            foreach ($totals as $total) {
                if ('total' == $total['code']) {
                    $new_total = $total['value'];
                    break;
                }
            }

            $pay_diff = ($new_total - $order_info['total']);

            $total_type = 'green';

            if (!$this->isOnlinePayment($order_info['payment_code']) || $pay_diff > 0) {
                $total_type = 'red';
            }

            if ($this->isOnlinePayment($order_info['payment_code'])) {
                if ($pay_diff < 0) {
                    $getPayment = 0;
                } else {
                    $getPayment = $pay_diff;
                }
            } else {
                $getPayment = $new_total;
            }

            $data['body'] = [
                'pickup_name' => $store_details['name'], //store name??
                'pickup_phone' => $store_details['telephone'],
                'pickup_address' => $store_details['address'],
                'pickup_city' => $store_city_name,
                //'pickup_state' => 'Brussels',
                'pickup_state' => $store_state_name,
                'from_lat' => $store_details['latitude'],
                'from_lng' => $store_details['longitude'],
                'pickup_zipcode' => $store_details['store_zipcode'], //''
                //'pickup_notes' => $data['text_weight'],
                'pickup_notes' => $store_details['pickup_notes'],
                'dropoff_name' => $order_info['shipping_name'],
                'dropoff_phone' => $order_info['telephone'],
                'dropoff_address' => $deliverAddress,
                'to_lat' => $order_info['latitude'],
                'to_lng' => $order_info['longitude'],
                'dropoff_city' => $order_info['shipping_city'], // from $order_info['city_id'],
                //'dropoff_state' => 'Brussels',
                'dropoff_state' => $order_info['shipping_state'],
                'dropoff_zipcode' => $order_info['shipping_zipcode'], // from $order_info['city_id'],
                'delivery_priority' => $delivery_priority, // normal/express all small
                'delivery_date' => $order_info['delivery_date'], //2017-04-13
                'delivery_slot' => $timeSlotAverage, //$order_info['delivery_timeslot'],//"10:30" //delivery slot is time so what will i enter here as i have data in format 06:26pm - 08:32pm
                'dropoff_notes' => $order_info['comment'],
                'type_of_delivery' => 'delivery', //delivery/return . Is it only one option for this index?
                'manifest_id' => $order_id, //order_id,
                'manifest_data' => json_encode($data['products']),
                'payment_method' => $order_info['payment_method'],
                'payment_code' => $order_info['payment_code'],
                'total_price' => (int) round($new_total),
                'get_amount' => (int) round($getPayment),
                'total_type' => $total_type,];

            $log->write($data['body']);

            $data['email'] = $this->config->get('config_delivery_username');
            $data['password'] = $this->config->get('config_delivery_secret');
            $response = $this->load->controller('deliversystem/deliversystem/getToken', $data);

            if ($response['status']) {
                $data['token'] = $response['token'];
                $res = $this->load->controller('deliversystem/deliversystem/createDelivery', $data);
                $log->write('reeponse');
                $log->write($res);

                if ($res['status']) {
                    $log->write('stsus');
                    if (isset($res['data']->delivery_id)) {
                        $delivery_id = $res['data']->delivery_id;
                        $log->write($delivery_id);

                        $this->model_account_order->updateOrderDeliveryId($delivery_id, $order_id);
                    }
                    //save in order table delivery id
                }
            }
        }
    }

    public function getOrder($order_id) {
        $this->load->model('localisation/language');
        $this->load->model('account/order');

        $order_query = $this->model_account_order->getFormatedOrder($order_id);

        /* $order_query = $this->db->query( "SELECT *, (SELECT os.name FROM `" . DB_PREFIX . "order_status` os WHERE os.order_status_id = o.order_status_id AND os.language_id = o.language_id) AS order_status FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int) $order_id . "'" ); */

        if ($order_query->num_rows) {
            $language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);

            $city_name = $this->model_account_order->getCityName($order_query->row['shipping_city_id']);

            $state_name = $this->model_account_order->getCityState($order_query->row['shipping_city_id']);

            if ($language_info) {
                $language_code = $language_info['code'];
                $language_directory = $language_info['directory'];
            } else {
                $language_code = '';
                $language_directory = '';
            }

            return [
                'order_id' => $order_query->row['order_id'],
                'invoice_no' => $order_query->row['invoice_no'],
                'invoice_prefix' => $order_query->row['invoice_prefix'],
                'store_id' => $order_query->row['store_id'],
                'store_name' => $order_query->row['store_name'],
                'store_url' => $order_query->row['store_url'],
                'customer_id' => $order_query->row['customer_id'],
                'firstname' => $order_query->row['firstname'],
                'lastname' => $order_query->row['lastname'],
                'email' => $order_query->row['email'],
                'telephone' => $order_query->row['telephone'],
                'fax' => $order_query->row['fax'],
                'custom_field' => unserialize($order_query->row['custom_field']),
                'shipping_name' => $order_query->row['shipping_name'],
                'shipping_address' => $order_query->row['shipping_address'],
                'shipping_city' => $city_name,
                'shipping_state' => $state_name,
                'shipping_contact_no' => $order_query->row['shipping_contact_no'],
                'shipping_method' => $order_query->row['shipping_method'],
                'shipping_zipcode' => $order_query->row['shipping_zipcode'],
                'shipping_code' => $order_query->row['shipping_code'],
                'shipping_flat_number' => $order_query->row['shipping_flat_number'],
                'shipping_building_name' => $order_query->row['shipping_building_name'],
                'shipping_landmark' => $order_query->row['shipping_landmark'],
                'latitude' => $order_query->row['latitude'],
                'longitude' => $order_query->row['longitude'],
                'payment_method' => $order_query->row['payment_method'],
                'payment_code' => $order_query->row['payment_code'],
                'comment' => $order_query->row['comment'],
                'total' => $order_query->row['total'],
                'order_status_id' => $order_query->row['order_status_id'],
                'order_status' => $order_query->row['order_status'],
                'affiliate_id' => $order_query->row['affiliate_id'],
                'commission' => $order_query->row['commission'],
                'language_id' => $order_query->row['language_id'],
                'language_code' => $language_code,
                'language_directory' => $language_directory,
                'currency_id' => $order_query->row['currency_id'],
                'currency_code' => $order_query->row['currency_code'],
                'currency_value' => $order_query->row['currency_value'],
                'order_pdf_link' => $order_query->row['order_pdf_link'],
                'ip' => $order_query->row['ip'],
                'forwarded_ip' => $order_query->row['forwarded_ip'],
                'user_agent' => $order_query->row['user_agent'],
                'accept_language' => $order_query->row['accept_language'],
                'date_modified' => $order_query->row['date_modified'],
                'date_added' => $order_query->row['date_added'],
                'delivery_date' => $order_query->row['delivery_date'],
                'delivery_timeslot' => $order_query->row['delivery_timeslot'],
                    /* 'date_modified' => $order_query->row['date_modified'],
                      'date_added' => $order_query->row['date_added'] */
            ];
        } else {
            return false;
        }
    }

    public function getTimeslotAverage($timeslot) {
        $str = $timeslot; //"06:26pm - 08:32pm";
        $arr = explode('-', $str);
        //print_r($arr);
        if (2 == count($arr)) {
            $one = date('H:i', strtotime($arr[0]));
            $two = date('H:i', strtotime($arr[1]));

            return $one;

            $time1 = explode(':', $one);
            $time2 = explode(':', $two);
            if (2 == count($time1) && 2 == count($time2)) {
                $mid1 = ($time1[0] + $time2[0]) / 2;
                $mid2 = ($time1[1] + $time2[1]) / 2;

                $mid1 = round($mid1);
                $mid2 = round($mid2);

                if ($mid2 <= 9) {
                    $mid2 = '0' . $mid2;
                }
                if ($mid1 <= 9) {
                    $mid1 = '0' . $mid1;
                }

                //if 19.5 is mid1 then i send 19 integer part cant send decimals

                return $mid1 . ':' . $mid2;
            }
        }

        return false;
    }

    public function getOrderProductsForKonduto($order_id) {
        $this->load->model('account/order');
        $this->load->model('assets/product');

        $data['products'] = [];

        //$order_id = 422;
        $products = $this->model_account_order->getOrderProducts($order_id);

        foreach ($products as $product) {
            $option_data = [];

            $options = $this->model_account_order->getOrderOptions($order_id, $product['order_product_id']);

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

            $data['products'][] = [
                'name' => $product['name'],
                'quantity' => (int) $product['quantity'],
                'unit_cost' => (int) $product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0),
            ];
        }

        // if(count($data['products']) > 0 )
        // 	return true;
        // return false;
        return $data['products'];
    }

}
