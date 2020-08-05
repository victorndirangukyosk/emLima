<?php

require_once DIR_SYSTEM . '/vendor/konduto/vendor/autoload.php';

//require_once DIR_SYSTEM.'/vendor/mpesa-php-sdk-master/vendor/autoload.php';

use \Konduto\Core\Konduto;
use \Konduto\Models;
use paragraph1\phpFCM\Client;
use paragraph1\phpFCM\Client as FCMClient;
use paragraph1\phpFCM\Message;
use paragraph1\phpFCM\Recipient\Device;
use paragraph1\phpFCM\Notification;

require_once DIR_SYSTEM . '/vendor/fcp-php/autoload.php';

require DIR_SYSTEM . 'vendor/Facebook/autoload.php';

require_once DIR_APPLICATION . '/controller/api/settings.php';

class ControllerAccountDashboard extends Controller {

    private $error = array();

    public function index() {

        $data['kondutoStatus'] = $this->config->get('config_konduto_status');

        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        $data['redirect_coming'] = false;

        if (isset($_GET['redirect'])) {
            $this->session->data['checkout_redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
            $data['redirect_coming'] = true;
        }

        $this->document->addStyle('/front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_login.css');

        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/dashboard', '', 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }


        $this->load->language('account/dashboard');


        $this->document->setTitle($this->language->get('heading_title'));
        //echo "<pre>";print_r($this->language->get('heading_title'));die;
        $this->load->model('account/dashboard');
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/dashboard', '', 'SSL')
        );

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_no_results'] = $this->language->get('text_no_results');

        $customer_info = $this->model_account_dashboard->getCustomerDashboardData($this->customer->getId());

        $total_orders = $orders = 0;
        if (!empty($customer_info)) {

            $total_orders = $this->model_account_dashboard->getTotalOrders($this->customer->getId());
            $orders = $this->model_account_dashboard->getOrders($this->customer->getId());
            $most_purchased = $this->model_account_dashboard->getMostPurchased($this->customer->getId());

            $this->load->model('sale/order');
            $total_spent = 0;
            $products_qty = 0;
            $todaydate = $todaydate = date('Y-m-d');
            if (!empty($orders))
                $first_order_Date = $orders[0]['date_added'];
            foreach ($orders as $result) {

                if ($this->model_sale_order->hasRealOrderProducts($result['order_id'])) {
                    $products_qty = $products_qty + $this->model_sale_order->getRealOrderProductsItems($result['order_id']);
                } else {
                    $products_qty = $products_qty + $this->model_sale_order->getOrderProductsItems($result['order_id']);
                }
                $sub_total = 0;
                $totals = $this->model_sale_order->getOrderTotals($result['order_id']);
                // echo "<pre>";print_r($orders);die;
                foreach ($totals as $total) {
                    if ($total['code'] == 'sub_total') {
                        $sub_total = $total['value'];
                        $total_spent = $total_spent + $sub_total;
                        break;
                    }
                }

                //     'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])) ,
                // 'total' => $this->currency->format($result['total'], $this->config->get('config_currency')),
                //     'subtotal'     => $this->currency->format($sub_total)
            }
            if ($total_orders) {
                $avg_value = ($total_spent / $total_orders);
            } else {
                $avg_value = 0;
            }


            $months = 0;

            while (($date1 = strtotime('+1 MONTH', $date1)) <= $date2)
                $months++;
            if ($months == 0)
                $frequency = ($total_orders);
            else
                $frequency = ($total_orders / $months);

            $customer_name = $customer_info['firstname'] . ' ' . $customer_info['lastname'];
            $data['DashboardData'] = array(
                'customer_name' => $customer_name,
                'email' => $customer_info['email'],
                'telephone' => $customer_info['telephone'],
                'total_orders' => $total_orders,
                'total_spent' => $this->currency->format($total_spent, $this->config->get('config_currency')),
                'avg_value' => $this->currency->format($avg_value, $this->config->get('config_currency')),
                'First_order_date' => $first_order_Date,
                'frequency' => $frequency,
                'most_purhased' => $most_purchased
            );

            $recent_orders = $this->model_account_dashboard->getRecentOrders($this->customer->getId());
            foreach ($recent_orders as $ro) {
                $user_recent_orders[] = array('order_id' => $ro['order_id'],
                    'name' => $ro['name'],
                    'date_added' => $ro['date_added'],
                    'delivery_date' => $ro['delivery_date'],
                    'href' => $this->url->link('account/order/info', 'order_id=' . $ro['order_id'], 'SSL'),
                    'real_href' => $this->url->link('account/order/realinfo', 'order_id=' . $ro['order_id'], 'SSL'),);
            }
            $data['DashboardData']['recent_orders'] = $user_recent_orders;

            $user_recent_activity = $this->model_account_dashboard->getRecentActivity($this->customer->getId());


            foreach ($user_recent_activity as $ra) {
                $recent_activity[] = array('store_name' => $ra['store_name'],
                    'firstname' => $ra['firstname'],
                    'lastname' => $ra['lastname'],
                    'order_id' => $ra['order_id'],
                    'href' => $this->url->link('account/order/info', 'order_id=' . $ra['order_id'], 'SSL'),
                    'total' => $this->currency->format($ra['total'], $this->config->get('config_currency')),
                    'date_added' => $ra['date_added']);
            }

            $data['DashboardData']['recent_activity'] = $recent_activity;
            // $recent_buying_pattern = $this->model_account_dashboard->getBuyingPattern($this->customer->getId());

            // foreach ($recent_buying_pattern as $rbp) {
            //     $user_recent_buying_pattern[] = array('date_added' => $rbp['date_added'],
            //         'total' => $this->currency->format($rbp['total'], $this->config->get('config_currency')));
            // }

            // $data['DashboardData']['recent_buying_pattern'] = $user_recent_buying_pattern;
        }


        //echo "<pre>";print_r($data['DashboardData']);die;
        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }


        $data['base'] = $server;

        $data['action'] = $this->url->link('account/account', '', 'SSL');

        // $data['column_left'] = $this->load->controller('common/column_left');
        // $data['column_right'] = $this->load->controller('common/column_right');
        // $data['content_top'] = $this->load->controller('common/content_top');
        // $data['content_bottom'] = $this->load->controller('common/content_bottom');

        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/onlyheader');

        $data['home'] = $this->url->link('common/home/toHome');

        // echo "<pre>";print_r($data);die;
        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/dashboard.tpl', $data));
    }


    public function recentbuyingpattern() {


        $this->load->model('account/dashboard');

        $json = $this->getChartData('getSales', true);
        $json['order']['label'] = 'Order Values/ Day';

        $this->response->setOutput(json_encode($json));
    }


    public function getChartData($modelFunction, $currency_format = false) {
        $this->load->model('account/dashboard');
        $json = array();

           $results = $this->model_account_dashboard->getBuyingPattern($this->customer->getId());
                //echo  count($results->rows);die;
           $count=count($results->rows);
                $order_data = array();

                for ($i = 0; $i < $count; $i++) {
                    $date = $results->rows[$i]['date'] ;

                    //setting default values
                    $order_data[$date] = array(
                        'day' => $date,
                        'total' => 0
                    );

                    $json['xaxis'][] = array($i, $date);
                }

                foreach ($results->rows as $result) {

                    $total = $result['total'];

                    if ($currency_format) {
                        $total = $this->currency->format($result['total'], $this->config->get('config_currency'), '', false);
                    }

                    $order_data[$result['date']] = array(
                        'day' => $result['date'],
                        'total' => $total
                    );
                }

                $i = 0;
                foreach ($order_data as $key => $value) {
                    $json['order']['data'][] = array($i++, $value['total']);
                }




  return $json;
    }

    public function getOrderProducts() {

        $order_id = $this->request->post['order_id'];
        $order_query = $this->db->query( "SELECT product_id,general_product_id,quantity,unit,name,order_id  FROM `" . DB_PREFIX . "order_product` o WHERE o.order_id = '" . (int) $order_id . "'" );

        if ( $order_query->num_rows ) {
            foreach ($order_query->rows   as $ra) {
            $data[]=   array(
                'order_id' => $ra['order_id'],
                'product_id' => $ra['product_id'],
                'general_product_id' => $ra['general_product_id'],
                'quantity' => $ra['quantity'],
                'unit' => $ra['unit'],
                'name' => $ra['name']
                        );
                    }

                        $this->response->addHeader('Content-Type: application/json');
                        $this->response->setOutput(json_encode($data));
        } else {
            $this->response->addHeader('Content-Type: application/json');
                        $this->response->setOutput(json_encode(null));
        }
    }

    public function addOrderProductToCart() {
        $this->load->model('account/wishlist');

        $data['text_cart_success'] = $this->language->get('text_cart_success');
        $log = new Log('error.log');
        $wishlist_id = $this->request->post['wishlist_id'];

        $wishlist_products =  $this->model_account_wishlist->getWishlistProduct($wishlist_id);
        $log->write('Wish List Products');
        $log->write($wishlist_products);
        $log->write('Wish List Products');

        if(is_array($wishlist_products) && count($wishlist_products) > 0) {
            foreach ($wishlist_products as $wishlist_product) {
            $log->write('Wish List Products 2');
            $log->write($wishlist_product['product_id']);
            $log->write('Wish List Products 2');
            $this->cart->add($wishlist_product['product_id'], $wishlist_product['quantity'], array(), $recurring_id = 0, $store_id= false, $store_product_variation_id= false,$product_type = 'replacable',$product_note=null,$produce_type=null);
            }
        }
        $this->model_account_wishlist->deleteWishlists($wishlist_id);
        //echo "reg";

        $this->session->data['success'] = $data['text_cart_success'];

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

}
