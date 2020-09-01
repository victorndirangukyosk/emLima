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

        $data['heading_title'] = "Recent Orders";

        $data['text_no_results'] = "No results!";

        $customer_info = $this->model_account_dashboard->getCustomerDashboardData($this->customer->getId());

        $total_orders = $orders = 0;
        if (!empty($customer_info)) {

            
        if (isset($this->request->get['start'])) {
            $start = $this->request->get['start'];
        } else {
            $start = '';
        }

        if (!empty($this->request->get['end'])) {
            $end = $this->request->get['end'];
        } else {
            $end = '';
        }


        if (!empty($this->request->get['customer_id'])) {
            $customer_id = $this->request->get['customer_id'];
        } else {
            $customer_id = '';
        }
        
        $date_start = date_create($start)->format('Y-m-d H:i:s');
        $date_end = date_create($end)->format('Y-m-d H:i:s'); 

            $data['token'] = $this->session->data['token'];
            $data['customer_id'] = $this->customer->getId();
           // $total_orders = $this->model_account_dashboard->getTotalOrders($this->customer->getId(),$start, $end);
            //$orders = $this->model_account_dashboard->getOrders($this->customer->getId(),$start, $end);
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
                // 'total_orders' => $total_orders,
                // 'total_spent' => $this->currency->format($total_spent, $this->config->get('config_currency')),
                // 'avg_value' => $this->currency->format($avg_value, $this->config->get('config_currency')),
                // 'First_order_date' => $first_order_Date,
                // 'frequency' => $frequency,
                 'most_purhased' => $most_purchased
            );

        $customer_SubUser_info = $this->model_account_dashboard->getCustomerSubUsers($this->customer->getId());
if(count($customer_SubUser_info)>1)
{ 
$newdata =  array (
    array(
    'company_name' => 'All Branches',
    'customer_id' => '-1' 
    )
  );
// $customer_SubUser_info[-1]['company_name']='All Branches';
// $customer_SubUser_info[-1]['customer_id']='0';

// $customer_SubUser_info=ksort($customer_SubUser_info,1);
$customer_SubUser_info=array_merge($newdata,$customer_SubUser_info);

}

    // echo "<pre>";print_r($customer_SubUser_info);die;

        $data['DashboardData']['companyname'] = $customer_SubUser_info;

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
            //   $user_recent_activity = $this->model_account_dashboard->getCustomerActivities($this->customer->getId());
 
          
         foreach ($user_recent_activity as $ra) {

            if($ra['order_status_id']==15)
            {
                $comment1="Placed Order";
                $comment2=" and Approval is Required";
            }
            else if($ra['order_status_id']==14)
            {
                $comment1="Placed Order";
                $comment2=" ";
            }

            else  
            {
                $comment1="Placed Order";
                $comment2=" and the order is  ".$ra['name'];
            }

                $recent_activity[] = array('store_name' => $ra['store_name'],
                    'firstname' => $ra['firstname'],
                    'lastname' => $ra['lastname'],
                    'order_id' => $ra['order_id'],
                    'comment1' => $comment1,
                    'comment2' => $comment2,
                    'href' => $this->url->link('account/order/info', 'order_id=' . $ra['order_id'], 'SSL'),
                    'total' => $this->currency->format($ra['total'], $this->config->get('config_currency')),
                    'date_added' => $ra['date_added']);
            }


		// foreach ($user_recent_activity as $result) {
		// 	$comment =  vsprintf($this->language->get('text_' . $result['key']), unserialize($result['data']));

		// 	$find = array(
		// 		'customer_id=',
		// 		'order_id=',
		// 		//'affiliate_id=',
		// 		'return_id='
		// 	);

		// 	$replace = array(
		// 		$this->url->link('sale/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=', 'SSL'),
		// 		$this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=', 'SSL'),
		// 		//$this->url->link('marketing/affiliate/edit', 'token=' . $this->session->data['token'] . '&affiliate_id=', 'SSL'),
		// 		$this->url->link('sale/return/edit', 'token=' . $this->session->data['token'] . '&return_id=', 'SSL')
		// 	);

		// 	$recent_activity[] = array(
		// 		'comment'    =>  str_replace($find, $replace, $comment) ,
		// 		'ip'         => $result['ip'],
		// 		'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added']))
		// 	);
        // }
        


           

            $data['DashboardData']['recent_activity'] = $recent_activity;
            // $recent_buying_pattern = $this->model_account_dashboard->getBuyingPattern($this->customer->getId());

            // foreach ($recent_buying_pattern as $rbp) {
            //     $user_recent_buying_pattern[] = array('date_added' => $rbp['date_added'],
            //         'total' => $this->currency->format($rbp['total'], $this->config->get('config_currency')));
            // }

            // $data['DashboardData']['recent_buying_pattern'] = $user_recent_buying_pattern;
        }


        //  echo "<pre>";print_r($data['DashboardData']);die;
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

    public function valueofbasket() {

       
        $json = $this->getChartsData('getValueOfBasket', true);

         

        $json['order']['label'] = "Basket Value Per Day";
  
        $this->response->setOutput(json_encode($json));
    }


    public function getRange($diff) {
        // if (isset($this->request->get['range']) and ! empty($this->request->get['range']) and $this->request->get['range'] != 'undefined') {
        //     $range = $this->request->get['range'];
        // } else {
        //     $range = 'day';
        // }

        $range = 'day';

        if ($diff < 365 and $range == 'year') {
            $range = 'month';
        }

        if ($diff < 28) {
            $range = 'day';
        }

        // if ($diff == 1) {
        //     $range = 'hour';
        // }

        if ($diff >31 and $range == 'day') {
            $range = 'month';
        }
       
        return $range;
    }


    public function getChartsData($modelFunction, $currency_format = false) {

        
        $this->load->model('account/dashboard');

        $json = array();

        if (isset($this->request->get['start'])) {
            $start = $this->request->get['start'];
        } else {
            $start = '';
        }

        if (!empty($this->request->get['end'])) {
            $end = $this->request->get['end'];
        } else {
            $end = '';
        }

        if (!empty($this->request->get['selectedcustomer_id'])) {
            $selectedcustomer_id = $this->request->get['selectedcustomer_id'];
        } else {
            $selectedcustomer_id = '';
        }


        $date_start = date_create($start)->format('Y-m-d H:i:s');
        $date_end = date_create($end)->format('Y-m-d H:i:s');

        $diff_str = strtotime($end) - strtotime($start);
        $diff = floor($diff_str / 3600 / 24) + 1;

        $range = $this->getRange($diff);

         
        //   echo "<pre>";print_r($json);die;

        switch ($range) {
            // case 'hour':
            //     $results = $this->model_dashboard_charts->{$modelFunction}($date_start, $date_end, 'HOUR');
            //     $order_data = array();

            //     for ($i = 0; $i < 24; $i++) {
            //         $order_data[$i] = array(
            //             'hour' => $i,
            //             'total' => 0
            //         );

            //         $json['xaxis'][] = array($i, $i . ':00');
            //     }

            //     foreach ($results->rows as $result) {
            //         $order_data[$result['hour']] = array(
            //             'hour' => $result['hour'],
            //             'total' => $result['total']
            //         );
            //     }

            //     foreach ($order_data as $key => $value) {
            //         $json['order']['data'][] = array($key, $value['total']);
            //     }

            //     break;
            // default:
            case 'day':
                $results = $this->model_account_dashboard->{$modelFunction}( $selectedcustomer_id,$date_start, $date_end, 'DAY',$this->customer->getId());
                $str_date = substr($date_start, 0, 10);
                $order_data = array();

                for ($i = 0; $i < $diff; $i++) {
                    $date = date_create($str_date)->modify('+' . $i . ' day')->format('Y-m-d');

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

                break;
            case 'month':
                $results = $this->model_account_dashboard->{$modelFunction}( $selectedcustomer_id,$date_start, $date_end, 'MONTH',$this->customer->getId());
                $months = $this->getMonths($date_start, $date_end);
                $order_data = array();

                for ($i = 0; $i < count($months); $i++) {
                    $order_data[$months[$i]] = array(
                        'month' => $months[$i],
                        'total' => 0
                    );

                    $json['xaxis'][] = array($i, $months[$i]);
                }

                foreach ($results->rows as $result) {
                    $order_data[$result['month']] = array(
                        'month' => $result['month'],
                        'total' => $result['total']
                    );
                }

                $i = 0;
                foreach ($order_data as $key => $value) {
                    $json['order']['data'][] = array($i++, $value['total']);
                }
                break;
            case 'year':
                $results = $this->model_account_dashboard->{$modelFunction}( $selectedcustomer_id,$date_start, $date_end, 'YEAR',$this->customer->getId());
                $str_date = substr($date_start, 0, 10);
                $order_data = array();
                $diff = floor($diff / 365) + 1;

                for ($i = 0; $i < $diff; $i++) {
                    $date = date_create($str_date)->modify('+' . $i . ' year')->format('Y');

                    $order_data[$date] = array(
                        'year' => $date,
                        'total' => 0
                    );

                    $json['xaxis'][] = array($i, $date);
                }

                foreach ($results->rows as $result) {
                    $order_data[$result['year']] = array(
                        'year' => $result['year'],
                        'total' => $result['total']
                    );
                }

                $i = 0;
                foreach ($order_data as $key => $value) {
                    $json['order']['data'][] = array($i++, $value['total']);
                }
                break;
        }

        $modelFunction = str_replace('get', 'getTotal', $modelFunction);
        $result = $this->model_account_dashboard->{$modelFunction}($selectedcustomer_id, $customer_id,$date_start, $date_end);

        // echo "<pre>";print_r($result);die;

        $total = $result['total'];
        if ($currency_format) {
            $total = $this->currency->format($result['total'], $this->config->get('config_currency'));
        }

        $json['order']['total'] = $total;

        return $json;
    }
     

    public function getDashboardData() {
        $this->load->model('account/dashboard');

        $json = array();

        if (isset($this->request->get['start'])) {
            $start = $this->request->get['start'];
        } else {
            $start = '';
        }

        if (!empty($this->request->get['end'])) {
            $end = $this->request->get['end'];
        } else {
            $end = '';
        }

        if (!empty($this->request->get['customer_id'])) {
            $selectedCustomer_id = $this->request->get['customer_id'];
        } else {
            $selectedCustomer_id = '';
        }

        $date_start = date_create($start)->format('Y-m-d H:i:s');
        $date_end = date_create($end)->format('Y-m-d H:i:s'); 
        //$customer_info = $this->model_account_dashboard->getCustomerDashboardData($this->customer->getId(),$date_start, $date_end);

        
        $total_orders = $orders = 0;
       // if (!empty($customer_info)) {
            // $data['token'] = $this->session->data['token'];
            $customer_id  = $this->customer->getId();
             
            $total_orders = $this->model_account_dashboard->getTotalOrders($customer_id,$selectedCustomer_id,$date_start,$date_end);
            $orders = $this->model_account_dashboard->getOrders($customer_id,$selectedCustomer_id,$date_start,$date_end);
          // $most_purchased = $this->model_account_dashboard->getMostPurchased($customer_id,$selectedCustomer_id,$date_start,$date_end);

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
 
            }
            if ($total_orders) {
                $avg_value = ($total_spent / $total_orders);
            } else {
                $avg_value = 0;
            }


            // $months = 0;

            // while (($date1 = strtotime('+1 MONTH', $date1)) <= $date2)
            //     $months++;
            // if ($months == 0)
            //     $frequency = ($total_orders);
            // else
            //     $frequency = ($total_orders / $months);

            
           // $data['DashboardNewData'] = array(
                
                $json['total_orders'] =  $total_orders;
                $json['total_spent'] = $this->currency->format($total_spent, $this->config->get('config_currency'));
                $json['avg_value'] = $this->currency->format($avg_value, $this->config->get('config_currency'));                
                $json['most_purchased'] =  $most_purchased;
                //'frequency' => $frequency 
           // );
 
             
        // }

        // $json['order']['total'] = $total;
       // return $json;
       $this->response->setOutput(json_encode($json));
    }

    function getMonths($date1, $date2) {
        $time1 = strtotime($date1);
        $time2 = strtotime($date2);
        $my = date('n-Y', $time2);
        $mesi = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        //$mesi = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec');

        $months = array();
        $f = '';

        while ($time1 < $time2) {
            if (date('n-Y', $time1) != $f) {
                $f = date('n-Y', $time1);
                if (date('n-Y', $time1) != $my && ($time1 < $time2)) {
                    $str_mese = $mesi[(date('n', $time1) - 1)];
                    $months[] = $str_mese . " " . date('Y', $time1);
                }
            }
            $time1 = strtotime((date('Y-n-d', $time1) . ' +15days'));
        }

        $str_mese = $mesi[(date('n', $time2) - 1)];
        $months[] = $str_mese . " " . date('Y', $time2);
        return $months;
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



    public function getRecentOrderProductsList() {

       

        if (isset($this->request->get['filter_product_name'])) {
            $filter_product_name = $this->request->get['filter_product_name'];
        } else {
            $filter_product_name = null;
        }
 
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'name';
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

        if (isset($this->request->get['filter_product_name'])) {
            $url .= '&filter_product_name=' . urlencode(html_entity_decode($this->request->get['filter_product_name'], ENT_QUOTES, 'UTF-8'));
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

        // $data['breadcrumbs'] = array();

        // $data['breadcrumbs'][] = array(
        //     'text' => $this->language->get('text_home'),
        //     'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        // );

        // $data['breadcrumbs'][] = array(
        //     'text' => $this->language->get('heading_title'),
        //     'href' => $this->url->link('account/dashboard', 'token=' . $this->session->data['token'] . $url, 'SSL')
        // );

        
        $data['recentorderproducts'] = array();

        $filter_data = array(
            'filter_product_name' => $filter_product_name,            
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
            'customer_id'=>$this->customer->getId()
        );

        $this->load->model('account/dashboard');

        $recentorderproducts_total_results= $this->model_account_dashboard->getTotalrecentorderproducts($filter_data);

         $recentorderproducts_total=count($recentorderproducts_total_results);
        $results = $this->model_account_dashboard->getrecentorderproducts($filter_data);

        //echo "<pre>";print_r($recentorderproducts_total);die;
        foreach ($results as $result) {
             

            $data['recentorderproducts'][] = array(
                'name' => $result['name'],
                'unit' => $result['unit'],
                'total' =>  $result['total']  ,
                 );
        }

        $data['heading_title'] = "Most bought Products (Last 30 days)";

        

        $data['token'] = $this->session->data['token'];
        $data['customer_id'] = $this->customer->getId();

        

        $url = '';

        if (isset($this->request->get['filter_product_name'])) {
            $url .= '&filter_product_name=' . urlencode(html_entity_decode($this->request->get['filter_product_name'], ENT_QUOTES, 'UTF-8'));
        }

         

        if ($order == 'ASC') {
            $url .= '&order=ASC';
        } else {
            $url .= '&order=DESC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_name'] = $this->url->link('account/dashboard/getRecentOrderProductsList',  '&sort=pd.name' . $url, 'SSL');
        $data['sort_total'] = $this->url->link('account/dashboard/getRecentOrderProductsList','&sort=total' . $url, 'SSL');
        $data['sort_unit'] = $this->url->link('account/dashboard/getRecentOrderProductsList',  '&sort=op.unit' . $url, 'SSL');
        
        $url = '';

        if (isset($this->request->get['filter_product_name'])) {
            $url .= '&filter_product_name=' . urlencode(html_entity_decode($this->request->get['filter_product_name'], ENT_QUOTES, 'UTF-8'));
        }

         

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $recentorderproducts_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('account/dashboard/getRecentOrderProductsList',  $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($recentorderproducts_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($recentorderproducts_total - $this->config->get('config_limit_admin'))) ? $recentorderproducts_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $recentorderproducts_total, ceil($recentorderproducts_total / $this->config->get('config_limit_admin')));

        $data['filter_product_name'] = $filter_product_name;
        
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/onlyHeader');
        $this->load->model('account/dashboard'); 
        $data['sort'] = $sort;
        $data['order'] = $order;
 
        // echo "<pre>";print_r($data);die;

        $this->response->setOutput($this->load->view('metaorganic/template/account/recentorderproducts_list.tpl', $data));
    }

    
    public function getRecentOrdersList() {
 
        if (isset($this->request->get['filter_order_id'])) {
            $filter_order_id = $this->request->get['filter_order_id'];
        } else {
            $filter_order_id = null;
        }
 

        if (isset($this->request->get['filter_delivery_method'])) {
            $filter_delivery_method = $this->request->get['filter_delivery_method'];
        } else {
            $filter_delivery_method = null;
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

        // if (isset($this->request->get['filter_total'])) {
        //     $filter_total = $this->request->get['filter_total'];
        // } else {
        //     $filter_total = null;
        // }

        if (isset($this->request->get['filter_date_added'])) {
            $filter_date_added = $this->request->get['filter_date_added'];
        } else {
            $filter_date_added = null;
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

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        

        if (isset($this->request->get['filter_delivery_method'])) {
            $url .= '&filter_delivery_method=' . urlencode(html_entity_decode($this->request->get['filter_delivery_method'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_payment'])) {
            $url .= '&filter_payment=' . urlencode(html_entity_decode($this->request->get['filter_payment'], ENT_QUOTES, 'UTF-8'));
        }


        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
        }

        // if (isset($this->request->get['filter_total'])) {
        //     $url .= '&filter_total=' . $this->request->get['filter_total'];
        // }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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

        // $data['breadcrumbs'] = array();

        // $data['breadcrumbs'][] = array(
        //     'text' => $this->language->get('text_home'),
        //     'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        // );

        // $data['breadcrumbs'][] = array(
        //     'text' => $this->language->get('heading_title'),
        //     'href' => $this->url->link('account/dashboard', 'token=' . $this->session->data['token'] . $url, 'SSL')
        // );

        
        $data['recentorders'] = array();

        $filter_data = array(
            'filter_order_id' => $filter_order_id,
           
            'filter_delivery_method' => $filter_delivery_method,
            'filter_payment' => $filter_payment,
            'filter_order_status' => $filter_order_status,
            //'filter_total' => $filter_total,
            'filter_date_added' => $filter_date_added,
            'filter_date_modified' => $filter_date_modified,            
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
            'customer_id'=>$this->customer->getId()
        );

        $this->load->model('account/dashboard');

        $recentorders_total= $this->model_account_dashboard->getTotalrecentorders($filter_data); 
        $results = $this->model_account_dashboard->getrecentordersofcustomer($filter_data);

        //  echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
             

            $data['recentorders'][] = array(
                'order_id' => $result['order_id'],
                'status' => $result['status'],
                'date_added' =>  $result['date_added']  ,
                'name' =>  $result['name']  ,
                'delivery_date'=>  $result['delivery_date']
                 );
        }

        $data['heading_title'] = "Recent Orders";

        

        $data['token'] = $this->session->data['token'];
        $data['customer_id'] = $this->customer->getId();

        

        $url = '';

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }
        if (isset($this->request->get['filter_delivery_method'])) {
            $url .= '&filter_delivery_method=' . urlencode(html_entity_decode($this->request->get['filter_delivery_method'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_payment'])) {
            $url .= '&filter_payment=' . urlencode(html_entity_decode($this->request->get['filter_payment'], ENT_QUOTES, 'UTF-8'));
        }


        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total=' . $this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
        }


         

        if ($order == 'ASC') {
            $url .= '&order=ASC';
        } else {
            $url .= '&order=DESC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_order'] = $this->url->link('account/dashboard/getRecentOrdersList',  '&sort=o.order_id' . $url, 'SSL');
        $data['sort_status'] = $this->url->link('account/dashboard/getRecentOrdersList','&sort=name' . $url, 'SSL');
        $data['sort_date_added'] = $this->url->link('account/dashboard/getRecentOrdersList', '&sort=o.date_added' . $url, 'SSL');
        $data['sort_date_modified'] = $this->url->link('account/dashboard/getRecentOrdersList',  '&sort=o.date_modified' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }
 

        if (isset($this->request->get['filter_delivery_method'])) {
            $url .= '&filter_delivery_method=' . urlencode(html_entity_decode($this->request->get['filter_delivery_method'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_payment'])) {
            $url .= '&filter_payment=' . urlencode(html_entity_decode($this->request->get['filter_payment'], ENT_QUOTES, 'UTF-8'));
        }


        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
        }

        // if (isset($this->request->get['filter_total'])) {
        //     $url .= '&filter_total=' . $this->request->get['filter_total'];
        // }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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
        $pagination->total = $recentorders_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('account/dashboard/getRecentOrdersList',  $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($recentorders_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($recentorders_total - $this->config->get('config_limit_admin'))) ? $recentorders_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $recentorders_total, ceil($recentorders_total / $this->config->get('config_limit_admin')));

        $data['filter_product_name'] = $filter_product_name;
        
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/onlyHeader');
        $this->load->model('account/dashboard'); 
        $data['sort'] = $sort;
        $data['order'] = $order;
 
         //echo "<pre>";print_r($data['recentorders']);die;

        $this->response->setOutput($this->load->view('metaorganic/template/account/recentorders_list.tpl', $data));
    }
     
    

    public function export_mostpurchased_products_excel($customer_id) {
        $data = array();

        if (isset($this->request->get['customer_id'])) {
            $data['customer_id'] = $this->request->get['customer_id'];
        } 

        
        $this->load->model('account/dashboard');
        $this->model_account_dashboard->download_mostpurchased_products_excel($data);
    }


    public function getPurchaseHistory()
    {

        $this->load->model('account/dashboard');
        //echo 'date.timezone ' ;;
        $data = $this->request->post;


       /// echo '<pre>';print_r($this->request->post);exit; 

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {

            $data= $this->model_account_dashboard->getPurchaseHistory($this->request->post['product_id'],$this->customer->getId());

            $data['status'] = true;

            $data['totalvalue'] = $this->currency->format($data['totalvalue'], $this->config->get('config_currency'));

            if ($this->request->isAjax()) {
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($data));
            }
        } else {

            $data['status'] = false;

            if ($this->request->isAjax()) {
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($data));
            }
        }
        //  echo '<pre>';print_r($data);exit;

        return true;
    }

}
