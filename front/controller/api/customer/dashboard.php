<?php

require_once DIR_SYSTEM.'/vendor/konduto/vendor/autoload.php';

//require_once DIR_SYSTEM.'/vendor/mpesa-php-sdk-master/vendor/autoload.php';

require_once DIR_SYSTEM.'/vendor/fcp-php/autoload.php';

require DIR_SYSTEM.'vendor/Facebook/autoload.php';

require_once DIR_APPLICATION.'/controller/api/settings.php';

class ControllerApiCustomerDashboard extends Controller
{
      private $error = [];

    public function getDashboardDetails()//index
    {

        $json = [];

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];
        // if (isset($_GET['redirect'])) {
        //     $this->session->data['checkout_redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
        //     $data['redirect_coming'] = true;
        // }
        // if (!$this->customer->isLogged()) {
        //     $this->session->data['redirect'] = $this->url->link('account/dashboard', '', 'SSL');

        //     $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        // }

        $this->load->language('account/dashboard');
        $this->load->model('account/dashboard');

        // $data['heading_title'] = 'Recent Orders';
        // $data['text_no_results'] = 'No results!';

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
            // $data['token'] = $this->session->data['token'];
            $data['customer_id'] = $this->customer->getId();
            // $total_orders = $this->model_account_dashboard->getTotalOrders($this->customer->getId(),$start, $end);
             $orders =null;// $this->model_account_dashboard->getOrders($this->customer->getId(),$start, $end);
            $most_purchased =null;// $this->model_account_dashboard->getMostPurchased($this->customer->getId());

            $this->load->model('sale/order');
            $total_spent = 0;
            $products_qty = 0;
            $todaydate = $todaydate = date('Y-m-d');
            if (!empty($orders)) {
                $first_order_Date = $orders[0]['date_added'];
            }
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
                    if ('sub_total' == $total['code']) {
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

            while (($date1 = strtotime('+1 MONTH', $date1)) <= $date2) {
                ++$months;
            }
            if (0 == $months) {
                $frequency = ($total_orders);
            } else {
                $frequency = ($total_orders / $months);
            }

            $customer_name = $customer_info['firstname'].' '.$customer_info['lastname'];
            $this->load->model('user/user');
            $account_manager_details = $this->model_user_user->getUser($customer_info['account_manager_id']);
            $data['DashboardData'] = [
                'customer_name' => $customer_name,
                'email' => $customer_info['email'],
                'telephone' => $customer_info['telephone'],
                'account_manager_details' => $account_manager_details,
                // 'total_orders' => $total_orders,
                // 'total_spent' => $this->currency->format($total_spent, $this->config->get('config_currency')),
                // 'avg_value' => $this->currency->format($avg_value, $this->config->get('config_currency')),
                // 'First_order_date' => $first_order_Date,
                // 'frequency' => $frequency,
                 'most_purhased' => $most_purchased,
            ];

            $customer_SubUser_info = $this->model_account_dashboard->getCustomerSubUsers($this->customer->getId());
            if (count($customer_SubUser_info) > 1) {
                $newdata = [
    [
    'company_name' => 'All Branches',
    'customer_id' => '-1',
    ],
  ];
                // $customer_SubUser_info[-1]['company_name']='All Branches';
                // $customer_SubUser_info[-1]['customer_id']='0';

                // $customer_SubUser_info=ksort($customer_SubUser_info,1);
                $customer_SubUser_info = array_merge($newdata, $customer_SubUser_info);
            }

            // echo "<pre>";print_r($customer_SubUser_info);die;

            $data['DashboardData']['companyname'] = $customer_SubUser_info;

            // $recent_orders = $this->model_account_dashboard->getRecentOrders($this->customer->getId());
            // foreach ($recent_orders as $ro) {
            //     $user_recent_orders[] = ['order_id' => $ro['order_id'],
            //         'name' => $ro['name'],
            //         'date_added' => $ro['date_added'],
            //         'delivery_date' => $ro['delivery_date'],
            //         'href' => $this->url->link('account/order/info', 'order_id='.$ro['order_id'], 'SSL'),
            //         'real_href' => $this->url->link('account/order/realinfo', 'order_id='.$ro['order_id'], 'SSL'), ];
            // }
            $data['DashboardData']['recent_orders'] =null;// $user_recent_orders;
            $user_recent_activity = $this->model_account_dashboard->getRecentActivity($this->customer->getId());
            //   $user_recent_activity = $this->model_account_dashboard->getCustomerActivities($this->customer->getId());

            foreach ($user_recent_activity as $ra) {
                if (15 == $ra['order_status_id']) {
                    $comment1 = 'Placed Order';
                    $comment2 = ' and Approval is Required';
                } elseif (14 == $ra['order_status_id']) {
                    $comment1 = 'Placed Order';
                    $comment2 = ' ';
                } else {
                    $comment1 = 'Placed Order';
                    $comment2 = ' and the order is  '.$ra['name'];
                }

                $recent_activity[] = ['store_name' => $ra['store_name'],
                    'firstname' => $ra['firstname'],
                    'lastname' => $ra['lastname'],
                    'order_id' => $ra['order_id'],
                    'comment1' => $comment1,
                    'comment2' => $comment2,
                    'href' => $this->url->link('account/order/info', 'order_id='.$ra['order_id'], 'SSL'),
                    'total' => $this->currency->format($ra['total'], $this->config->get('config_currency')),
                    'date_added' => $ra['date_added'],
                    'total_value' => Math.round($ra['total'],2)
                    // 'total_value' => $ra['total'],
                 ];
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

        // echo "<pre>";print_r($data);die;
        $json['data'] =$data;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));

    }

    public function getvalueofbasket()
    {
        $json = $this->getChartsData('getValueOfBasket', true);

        $json['order']['label'] = 'Basket Value Per Day';

        $this->response->setOutput(json_encode($json));
    }

    public function getRange($diff)
    {
        // if (isset($this->request->get['range']) and ! empty($this->request->get['range']) and $this->request->get['range'] != 'undefined') {
        //     $range = $this->request->get['range'];
        // } else {
        //     $range = 'day';
        // }

        $range = 'day';

        if ($diff < 365 and 'year' == $range) {
            $range = 'month';
        }

        // if ($diff < 31) {
        //     $range = 'day';
        // }

        // if ($diff == 1) {
        //     $range = 'hour';
        // }

        if ($diff > 31 and 'day' == $range) {
            $range = 'month';
        }

        return $range;
    }

    public function getChartsData($modelFunction, $currency_format = false)
    {
        $this->load->model('account/dashboard');

        $json = [];

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

        $customer_id = $this->customer->getId();

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
                $results = $this->model_account_dashboard->{$modelFunction}($selectedcustomer_id, $date_start, $date_end, 'DAY', $this->customer->getId());
                $str_date = substr($date_start, 0, 10);
                $order_data = [];

                for ($i = 0; $i < $diff; ++$i) {
                    $date = date_create($str_date)->modify('+'.$i.' day')->format('Y-m-d');

                    //setting default values
                    $order_data[$date] = [
                        'day' => $date,
                        'total' => 0,
                    ];

                    $json['xaxis'][] = [$i, $date];
                }

                foreach ($results->rows as $result) {
                    $total = $result['total'];

                    if ($currency_format) {
                        $total = $this->currency->format($result['total'], $this->config->get('config_currency'), '', false);
                    }

                    $order_data[$result['date']] = [
                        'day' => $result['date'],
                        'total' => $total,
                    ];
                }

                $i = 0;
                foreach ($order_data as $key => $value) {
                    $json['order']['data'][] = [$i++, $value['total']];
                }

                break;
            case 'month':
                $results = $this->model_account_dashboard->{$modelFunction}($selectedcustomer_id, $date_start, $date_end, 'MONTH', $this->customer->getId());
                $months = $this->getMonths($date_start, $date_end);
                $order_data = [];

                for ($i = 0; $i < count($months); ++$i) {
                    $order_data[$months[$i]] = [
                        'month' => $months[$i],
                        'total' => 0,
                    ];

                    $json['xaxis'][] = [$i, $months[$i]];
                }

                foreach ($results->rows as $result) {
                    $order_data[$result['month']] = [
                        'month' => $result['month'],
                        'total' => $result['total'],
                    ];
                }

                $i = 0;
                foreach ($order_data as $key => $value) {
                    $json['order']['data'][] = [$i++, $value['total']];
                }
                break;
            case 'year':
                $results = $this->model_account_dashboard->{$modelFunction}($selectedcustomer_id, $date_start, $date_end, 'YEAR', $this->customer->getId());
                $str_date = substr($date_start, 0, 10);
                $order_data = [];
                $diff = floor($diff / 365) + 1;

                for ($i = 0; $i < $diff; ++$i) {
                    $date = date_create($str_date)->modify('+'.$i.' year')->format('Y');

                    $order_data[$date] = [
                        'year' => $date,
                        'total' => 0,
                    ];

                    $json['xaxis'][] = [$i, $date];
                }

                foreach ($results->rows as $result) {
                    $order_data[$result['year']] = [
                        'year' => $result['year'],
                        'total' => $result['total'],
                    ];
                }

                $i = 0;
                foreach ($order_data as $key => $value) {
                    $json['order']['data'][] = [$i++, $value['total']];
                }
                break;
        }

        $modelFunction = str_replace('get', 'getTotal', $modelFunction);
        $result = $this->model_account_dashboard->{$modelFunction}($selectedcustomer_id, $date_start, $date_end, $customer_id);

        // echo "<pre>";print_r($result);die;

        $total = $result['total'];
        if ($currency_format) {
            $total = $this->currency->format($result['total'], $this->config->get('config_currency'));
        }

        $json['order']['total'] = $total;

        return $json;
    }

    public function getDashboardData()
    {
        $this->load->model('account/dashboard');

        $json = [];

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
        $customer_id = $this->customer->getId();

        $total_orders = $this->model_account_dashboard->getTotalOrders($customer_id, $selectedCustomer_id, $date_start, $date_end);
        $orders = $this->model_account_dashboard->getOrders($customer_id, $selectedCustomer_id, $date_start, $date_end);
        // $most_purchased = $this->model_account_dashboard->getMostPurchased($customer_id,$selectedCustomer_id,$date_start,$date_end);

        $this->load->model('sale/order');
        $total_spent = 0;
        $products_qty = 0;
        $todaydate = $todaydate = date('Y-m-d');
        if (!empty($orders)) {
            $first_order_Date = $orders[0]['date_added'];
        }
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
                if ('sub_total' == $total['code']) {
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

        $json['total_orders'] = $total_orders;
        $json['total_spent'] = $this->currency->format($total_spent, $this->config->get('config_currency'));
        $json['avg_value'] = $this->currency->format($avg_value, $this->config->get('config_currency'));
        //'frequency' => $frequency
        // );

        // }

        // $json['order']['total'] = $total;
        // return $json;
        $this->response->setOutput(json_encode($json));
    }

    public function recentbuyingpattern()
    {
        $this->load->model('account/dashboard');

        $json = $this->getChartData('getSales', true);
        $json['order']['label'] = 'Order Values/ Day';

        $this->response->setOutput(json_encode($json));
    }

    public function getChartData($modelFunction, $currency_format = false)
    {
        $this->load->model('account/dashboard');
        $json = [];

        $json = [];

        $results = $this->model_account_dashboard->getBuyingPattern($this->customer->getId());
        //echo  count($results->rows);die;
        $count = count($results->rows);
        $order_data = [];

        for ($i = 0; $i < $count; ++$i) {
            $date = $results->rows[$i]['date'];

            //setting default values
            $order_data[$date] = [
                        'day' => $date,
                        'total' => 0,
                    ];

            $json['xaxis'][] = [$i, $date];
        }

        foreach ($results->rows as $result) {
            $total = $result['total'];

            if ($currency_format) {
                $total = $this->currency->format($result['total'], $this->config->get('config_currency'), '', false);
            }

            $order_data[$result['date']] = [
                        'day' => $result['date'],
                        'total' => $total,
                    ];
        }

        $i = 0;
        foreach ($order_data as $key => $value) {
            $json['order']['data'][] = [$i++, $value['total']];
        }

        return $json;
    }

    public function getOrderProducts()
    {
        $order_id = $this->request->post['order_id'];
        $order_query = $this->db->query('SELECT product_id,general_product_id,quantity,unit,name,order_id  FROM '.DB_PREFIX."order_product o WHERE o.order_id = '".(int) $order_id."'");

        if ($order_query->num_rows) {
            foreach ($order_query->rows   as $ra) {
                $data[] = [
                'order_id' => $ra['order_id'],
                'product_id' => $ra['product_id'],
                'general_product_id' => $ra['general_product_id'],
                'quantity' => $ra['quantity'],
                'unit' => $ra['unit'],
                'name' => $ra['name'],
                        ];
            }

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($data));
        } else {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode(null));
        }
    }
    public function getAvailableOrderProducts()
    {
        $order_id = $this->request->post['order_id'];
        $order_query = $this->db->query('SELECT product_id,general_product_id,quantity,unit,name,order_id  FROM '.DB_PREFIX."order_product o WHERE o.order_id = '".(int) $order_id."'");

        if ($order_query->num_rows) {
            // $this->load->model('assets/product');
            foreach ($order_query->rows   as $ra) {

                // $fromStore = false;
                // $product_store_id = 0;

                // // product_store_id 11
                // if ($data['store_id']) {
                //     $productStoreData = $this->model_assets_product->getProductStoreId($product['product_id'], $data['store_id']);

                //     //echo "<pre>";print_r($productStoreData);die;

                //     if (count($productStoreData) > 0) {
                //         $product_store_id = $productStoreData['product_store_id'];
                //         $fromStore = true;
                //     }
                // }

                //echo "<pre>";print_r($product_store_id);die;
                // $special_price = 0;
                // $price = 0;

                // if (count($product_info) > 0)
                {

                    // if ((float) $product_info['special_price']) {
                    //     $special_price = $this->currency->format($product_info['special_price']);
                    // } else {
                    //     $special_price = $product_info['special_price'];
                    // }

                    // if ((float) $product_info['price']) {
                    //     $price = $this->currency->format($product_info['price']);
                    // } else {
                    //     $price = $product_info['price'];
                    // }

                $data[] = [
                'order_id' => $ra['order_id'],
                'product_id' => $ra['product_id'],
                'general_product_id' => $ra['general_product_id'],
                'quantity' => $ra['quantity'],
                'unit' => $ra['unit'],
                'name' => $ra['name'],
                        ];
                    }
            }

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($data));
        } else {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode(null));
        }
    }


    public function getAndAddOrderProducts()
    {
        $order_id = $this->request->post['order_id'];
        $order_query = $this->db->query('SELECT product_id,general_product_id,quantity,unit,name,order_id  FROM '.DB_PREFIX."order_product o WHERE o.order_id = '".(int) $order_id."'");

        if ($order_query->num_rows) {
            foreach ($order_query->rows   as $ra) {
                $data[] = [
                'order_id' => $ra['order_id'],
                'product_id' => $ra['product_id'],
                'general_product_id' => $ra['general_product_id'],
                'quantity' => $ra['quantity'],
                'unit' => $ra['unit'],
                'name' => $ra['name'],
                        ];
            }

            $log = new Log('error.log');
            $log->write('Ordered Products');
            $log->write($data);
            $log->write('Ordered Products');
            // echo "<pre>";print_r($data);die;
            if (is_array($data) && count($data) > 0) {
                foreach ($data as $order_product) {
                    $log->write('Order Products 2');
                    $log->write($order_product['product_id']);
                    $log->write('Order Products 2');
                    if ($order_product['quantity'] > 0)
                    $this->cart->add($order_product['product_id'], $order_product['quantity'], [], $recurring_id = 0, $store_id = false, $store_product_variation_id = false, $product_type = 'replacable', $product_note = null, $produce_type = null);
                }
            }

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($data));
        } else {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode(null));
        }
    }
    //below method used in dashboard and reorder of order list
    public function addOrderProductToCart()
    {
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
                $this->cart->add($wishlist_product['product_id'], $wishlist_product['quantity'], [], $recurring_id = 0, $store_id = false, $store_product_variation_id = false, $product_type = 'replacable', $product_note = null, $produce_type = null);
            }
        }
        $this->model_account_wishlist->deleteWishlists($wishlist_id);
        //echo "reg";

        $this->session->data['success'] = $data['text_cart_success'];

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function getRecentOrderProductsList()
    {
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
            $url .= '&filter_product_name='.urlencode(html_entity_decode($this->request->get['filter_product_name'], ENT_QUOTES, 'UTF-8'));
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

        // $data['breadcrumbs'] = array();

        // $data['breadcrumbs'][] = array(
        //     'text' => $this->language->get('text_home'),
        //     'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        // );

        // $data['breadcrumbs'][] = array(
        //     'text' => $this->language->get('heading_title'),
        //     'href' => $this->url->link('account/dashboard', 'token=' . $this->session->data['token'] . $url, 'SSL')
        // );

        $data['recentorderproducts'] = [];

        $filter_data = [
            'filter_product_name' => $filter_product_name,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
            'customer_id' => $this->customer->getId(),
        ];

        $this->load->model('account/dashboard');

        $recentorderproducts_total_results = $this->model_account_dashboard->getTotalrecentorderproducts($filter_data);

        $recentorderproducts_total = count($recentorderproducts_total_results);
        $results = $this->model_account_dashboard->getrecentorderproducts($filter_data);

        //echo "<pre>";print_r($recentorderproducts_total);die;
        foreach ($results as $result) {
            $data['recentorderproducts'][] = [
                'name' => $result['name'],
                'unit' => $result['unit'],
                'total' => $result['total'],
                 ];
        }

        $data['heading_title'] = 'Most bought Products (Last 30 days)';

        // $data['token'] = $this->session->data['token'];
        $data['customer_id'] = $this->customer->getId();

        $url = '';

        if (isset($this->request->get['filter_product_name'])) {
            $url .= '&filter_product_name='.urlencode(html_entity_decode($this->request->get['filter_product_name'], ENT_QUOTES, 'UTF-8'));
        }

        if ('ASC' == $order) {
            $url .= '&order=ASC';
        } else {
            $url .= '&order=DESC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        $data['sort_name'] = $this->url->link('account/dashboard/getRecentOrderProductsList', '&sort=pd.name'.$url, 'SSL');
        $data['sort_total'] = $this->url->link('account/dashboard/getRecentOrderProductsList', '&sort=total'.$url, 'SSL');
        $data['sort_unit'] = $this->url->link('account/dashboard/getRecentOrderProductsList', '&sort=op.unit'.$url, 'SSL');

        $url = '';

        if (isset($this->request->get['filter_product_name'])) {
            $url .= '&filter_product_name='.urlencode(html_entity_decode($this->request->get['filter_product_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order='.$this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $recentorderproducts_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('account/dashboard/getRecentOrderProductsList', $url.'&page={page}', 'SSL');

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

    public function getRecentOrdersProductsList() {// getRecentOrderProductsList_new

                
        $json = [];

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];
        try{
        if (isset($this->request->get['filter_product_name'])) {
            $filter_product_name = $this->request->get['filter_product_name'];
        } else {
            $filter_product_name = null;
        }

        if (isset($this->request->get['customer_id'])) {
            $filter_customer_id = $this->request->get['customer_id'];
        } else {
            $filter_customer_id = null;
        }
        
        if (isset($this->request->get['start'])) {
            $filter_start_date = $this->request->get['start'];
        } else {
            $filter_start_date = null;
        }
        
        if (isset($this->request->get['end'])) {
            $filter_end_date = $this->request->get['end'];
        } else {
            $filter_end_date = null;
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

        $data['recentorderproducts'] = [];

        $filter_data = [
            'filter_product_name' => $filter_product_name,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
            // 'customer_id' => $this->customer->getId(),
            'customer_id' => $filter_customer_id, 
            'start_date' => $filter_start_date,
            'end_date' => $filter_end_date
        ];

        $this->load->model('account/dashboard');

        $recentorderproducts_total_results = $this->model_account_dashboard->getTotalrecentorderproducts_new($filter_data);
        $recentorderproducts_total = $recentorderproducts_total_results['count'];
        $results = $this->model_account_dashboard->getrecentorderproducts_new($filter_data);

        //echo "<pre>";print_r($recentorderproducts_total);die;
        foreach ($results as $result) {
            $data['recentorderproducts'][] = [
                'name' => $result['name'],
                'unit' => $result['unit'],
                'total' => $result['total'],
            ];
        }

        $data['heading_title'] = 'Most bought Products (Last 30 days)';

        $data['token'] = $this->session->data['token'];
        $data['customer_id'] = $this->customer->getId();

        $url = '';

        if (isset($this->request->get['filter_product_name'])) {
            $url .= '&filter_product_name=' . urlencode(html_entity_decode($this->request->get['filter_product_name'], ENT_QUOTES, 'UTF-8'));
        }

        if ('ASC' == $order) {
            $url .= '&order=ASC';
        } else {
            $url .= '&order=DESC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        // $data['sort_name'] = $this->url->link('account/dashboard/getRecentOrderProductsList_new', '&sort=pd.name' . $url, 'SSL');
        // $data['sort_total'] = $this->url->link('account/dashboard/getRecentOrderProductsList_new', '&sort=total' . $url, 'SSL');
        // $data['sort_unit'] = $this->url->link('account/dashboard/getRecentOrderProductsList_new', '&sort=op.unit' . $url, 'SSL');

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
        // $pagination->url = $this->url->link('account/dashboard/getRecentOrderProductsList_new', $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($recentorderproducts_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($recentorderproducts_total - $this->config->get('config_limit_admin'))) ? $recentorderproducts_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $recentorderproducts_total, ceil($recentorderproducts_total / $this->config->get('config_limit_admin')));

        $data['filter_product_name'] = $filter_product_name;
        // $this->document->setTitle($data['heading_title']);
        // $data['footer'] = $this->load->controller('common/footer');
        // $data['header'] = $this->load->controller('common/header/onlyHeader');
        $this->load->model('account/dashboard');
        $data['sort'] = $sort;
        $data['order'] = $order;

        // echo "<pre>";print_r($data);die;

        // $this->response->setOutput($this->load->view('metaorganic/template/account/recentorderproducts_list.tpl', $data));
        // echo "<pre>";print_r($data);die;
        $json['data'] =$data;
        $json['message'] ="Success";
            }catch(exception $ex)
            {
                $json['message'] ="Something went wrong";
            }finally{
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($json));
            }

    }

    public function getRecentOrdersList()
    {
                

        $json = [];

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];
        try{
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


        if (isset($this->request->get['customer_id'])) {
            $filter_customer_id = $this->request->get['customer_id'];
        } else {
            $filter_customer_id = null;
        }
        
        if (isset($this->request->get['start'])) {
            $filter_start_date = $this->request->get['start'];
        } else {
            $filter_start_date = null;
        }
        
        if (isset($this->request->get['end'])) {
            $filter_end_date = $this->request->get['end'];
        } else {
            $filter_end_date = null;
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
            $url .= '&filter_order_id='.$this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_delivery_method'])) {
            $url .= '&filter_delivery_method='.urlencode(html_entity_decode($this->request->get['filter_delivery_method'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_payment'])) {
            $url .= '&filter_payment='.urlencode(html_entity_decode($this->request->get['filter_payment'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status='.$this->request->get['filter_order_status'];
        }

        // if (isset($this->request->get['filter_total'])) {
        //     $url .= '&filter_total=' . $this->request->get['filter_total'];
        // }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added='.$this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified='.$this->request->get['filter_date_modified'];
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

        // $data['breadcrumbs'] = array();

        // $data['breadcrumbs'][] = array(
        //     'text' => $this->language->get('text_home'),
        //     'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        // );

        // $data['breadcrumbs'][] = array(
        //     'text' => $this->language->get('heading_title'),
        //     'href' => $this->url->link('account/dashboard', 'token=' . $this->session->data['token'] . $url, 'SSL')
        // );

        $data['recentorders'] = [];

        $filter_data = [
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
            // 'customer_id' => $this->customer->getId(),
            'customer_id' => $filter_customer_id,
            'filter_start_date' => $filter_start_date,
            'filter_end_date' => $filter_end_date,
        ];

        $this->load->model('account/dashboard');

        $recentorders_total = $this->model_account_dashboard->getTotalrecentorders($filter_data);
        $results = $this->model_account_dashboard->getrecentordersofcustomer($filter_data);

        //  echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            $data['recentorders'][] = [
                'order_id' => $result['order_id'],
                'status' => $result['status'],
                'date_added' => $result['date_added'],
                'name' => $result['name'],
                'delivery_date' => $result['delivery_date'],
                 ];
        }

        $data['heading_title'] = 'Recent Orders';

        $data['token'] = $this->session->data['token'];
        $data['customer_id'] = $this->customer->getId();

        $url = '';

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id='.$this->request->get['filter_order_id'];
        }
        if (isset($this->request->get['filter_delivery_method'])) {
            $url .= '&filter_delivery_method='.urlencode(html_entity_decode($this->request->get['filter_delivery_method'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_payment'])) {
            $url .= '&filter_payment='.urlencode(html_entity_decode($this->request->get['filter_payment'], ENT_QUOTES, 'UTF-8'));
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

        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified='.$this->request->get['filter_date_modified'];
        }

        if ('ASC' == $order) {
            $url .= '&order=ASC';
        } else {
            $url .= '&order=DESC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        // $data['sort_order'] = $this->url->link('api/customer/dashboard/RecentOrdersList', '&sort=o.order_id'.$url, 'SSL');
        // $data['sort_status'] = $this->url->link('api/customer/dashboard/RecentOrdersList', '&sort=name'.$url, 'SSL');
        // $data['sort_date_added'] = $this->url->link('api/customer/dashboard/RecentOrdersList', '&sort=o.date_added'.$url, 'SSL');
        // $data['sort_date_modified'] = $this->url->link('api/customer/dashboard/RecentOrdersList', '&sort=o.date_modified'.$url, 'SSL');

        $url = '';

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id='.$this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_delivery_method'])) {
            $url .= '&filter_delivery_method='.urlencode(html_entity_decode($this->request->get['filter_delivery_method'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_payment'])) {
            $url .= '&filter_payment='.urlencode(html_entity_decode($this->request->get['filter_payment'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status='.$this->request->get['filter_order_status'];
        }

        // if (isset($this->request->get['filter_total'])) {
        //     $url .= '&filter_total=' . $this->request->get['filter_total'];
        // }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added='.$this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified='.$this->request->get['filter_date_modified'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order='.$this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $recentorders_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        // $pagination->url = $this->url->link('api/customer/dashboard/RecentOrdersList', $url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($recentorders_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($recentorders_total - $this->config->get('config_limit_admin'))) ? $recentorders_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $recentorders_total, ceil($recentorders_total / $this->config->get('config_limit_admin')));

        $data['filter_product_name'] = $filter_product_name;

        // $data['footer'] = $this->load->controller('common/footer');
        // $data['header'] = $this->load->controller('common/header/onlyHeader');
         $this->load->model('account/dashboard');
        $data['sort'] = $sort;
        $data['order'] = $order;

        //echo "<pre>";print_r($data['recentorders']);die;

        // $this->response->setOutput($this->load->view('metaorganic/template/account/recentorders_list.tpl', $data));

        // echo "<pre>";print_r($data);die;
        $json['data'] =$data;  $json['message'] ="Success";
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
                }
                catch(exception $ex)
                { $json['message'] ="Something went wrong!";
                    $this->response->addHeader('Content-Type: application/json');
                    $this->response->setOutput(json_encode($json));
                }
    }

    public function export_mostpurchased_products_excel($customer_id)
    {
        $data = [];

        if (isset($this->request->get['customer_id'])) {
            $data['customer_id'] = $this->request->get['customer_id'];
        }

        $this->load->model('account/dashboard');
        $this->model_account_dashboard->download_mostpurchased_products_excel($data);
    }


    public function getMostPurchasedProductsExcel() {//export_mostpurchased_products_excel_new
        $data = array();
        $data = [
            /*'filter_customer' => $this->request->get['customer_id'] > 0 ? $this->request->get['customer_id'] : $this->customer->getId(),*/
            'filter_customer' => $this->request->get['customer_id'],
            'filter_date_start' => $this->request->get['start'],
            'filter_date_end' => $this->request->get['end']
        ];

        $this->load->model('account/dashboard');
        $this->model_account_dashboard->download_mostpurchased_products_excel_new($data);
    }

    public function getPurchaseHistory()
    {
        $this->load->model('account/dashboard');
        //echo 'date.timezone ' ;;
        $data = $this->request->post;

        /// echo '<pre>';print_r($this->request->post);exit;

        if ('POST' == $this->request->server['REQUEST_METHOD']) {
            $data = $this->model_account_dashboard->getPurchaseHistory($this->request->post['product_id'], $this->customer->getId());

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



    public function getPurchaseHistoryByProductID() {//getPurchaseHistoryNew
       
       
       
        $json = [];
            $json['status'] = 200;
            $json['data'] = [];
            $json['message'] = [];
            try{
                 $this->load->model('account/dashboard');
        //echo 'date.timezone ' ;;
        $data = array();
        $data = [
            'filter_customer' => $this->request->get['customer_id'] > 0 ? $this->request->get['customer_id'] : $this->customer->getId(),
            'filter_product_id' => $this->request->get['product_id'],
            'filter_date_start' => $this->request->get['start'],
            'filter_date_end' => $this->request->get['end']
        ];

        //  echo '<pre>';print_r($this->request->get);exit;
        $result = $this->model_account_dashboard->getPurchaseHistoryNew($data);
        $log = new Log('error.log');
        $log->write($result);


        $result['status'] = true;

        $result['totalvalue'] = $this->currency->format($result['totalvalue'], $this->config->get('config_currency'));

        // if ($this->request->isAjax()) {
        //     $this->response->addHeader('Content-Type: application/json');
        //     $this->response->setOutput(json_encode($result));
        // }
        $json['data'] =$result;
        $json['message'] ="Success";
        //  echo '<pre>';print_r($result);exit;

        // return true;
            }

            catch(exception $ex)
            {
                $json['message'] ="Some thing went wrong";

            }
            finally{
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($json));
            }
    }

    public function addPurchaseHistory()
    {
        $this->load->model('account/dashboard');
        //echo 'date.timezone ' ;;
        $data = $this->request->post;

        /// echo '<pre>';print_r($this->request->post);exit;

        if ('POST' == $this->request->server['REQUEST_METHOD']) {
            $data = $this->model_account_dashboard->getPurchaseHistorybyDate($this->request->post['product_id'], $this->customer->getId(),$this->request->post['start_date'],$this->request->post['end_date'],$this->request->post['subuser_id']);

            if($data==null)
            {
                $data['message'] = "No data available";
            }
            else{

            $data['totalvalue'] = $this->currency->format($data['totalvalue'], $this->config->get('config_currency'));
            }
            $data['status'] = true;

            // if ($this->request->isAjax()) 
            // {
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($data));
            // }
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

    public function getMonths($date1, $date2)
    {
        $time1 = strtotime($date1);
        $time2 = strtotime($date2);
        $my = date('n-Y', $time2);
        $mesi = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        //$mesi = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec');

        $months = [];
        $f = '';

        while ($time1 < $time2) {
            if (date('n-Y', $time1) != $f) {
                $f = date('n-Y', $time1);
                if (date('n-Y', $time1) != $my && ($time1 < $time2)) {
                    $str_mese = $mesi[(date('n', $time1) - 1)];
                    $months[] = $str_mese.' '.date('Y', $time1);
                }
            }
            $time1 = strtotime((date('Y-n-d', $time1).' +15days'));
        }

        $str_mese = $mesi[(date('n', $time2) - 1)];
        $months[] = $str_mese.' '.date('Y', $time2);

        return $months;
    }



    //similar method from Admin/reports/customer_order/statement
    public function addcustomerstatement()
    { 

        $json = [];

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];
        // echo "<pre>";print_r($filter_data);die;

        try{
        if (isset($this->request->post['filter_date_start'])) {
            $filter_date_start = $this->request->post['filter_date_start'];
        } else {
            $filter_date_start = '';
        }

        if (isset($this->request->post['filter_date_end'])) {
            $filter_date_end = $this->request->post['filter_date_end'];
        } else {
            $filter_date_end = '';
        }

        if (isset($this->request->post['filter_order_status_id'])) {
            $filter_order_status_id = $this->request->post['filter_order_status_id'];
        } else {
            $filter_order_status_id = 0;
        }

        if (isset($this->request->post['filter_customer'])) {
            $filter_customer =  ($this->request->post['filter_customer']);
        } else {
            $filter_customer = '';
        }

        if (isset($this->request->post['filter_company'])) {
            $filter_company = $this->request->post['filter_company'];
        } else {
            $filter_company = '';
        }

        if (isset($this->request->post['page'])) {
            $page = $this->request->post['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->post['filter_date_start'])) {
            $url .= '&filter_date_start='.$this->request->post['filter_date_start'];
        }

        if (isset($this->request->post['filter_date_end'])) {
            $url .= '&filter_date_end='.$this->request->post['filter_date_end'];
        }

        if (isset($this->request->post['filter_order_status_id'])) {
            $url .= '&filter_order_status_id='.$this->request->post['filter_order_status_id'];
        }

        if (isset($this->request->post['filter_customer'])) {
            $url .= '&filter_customer='.$this->request->post['filter_customer'];
        }

        if (isset($this->request->post['filter_company'])) {
            $url .= '&filter_company='.$this->request->post['filter_company'];
        }

        if (isset($this->request->post['page'])) {
            $url .= '&page='.$this->request->post['page'];
        }       

        $this->load->model('report/customer');
        $data['customers'] = [];

        $filter_data = [
            'filter_date_start' => $filter_date_start,
            'filter_date_end' => $filter_date_end,
            'filter_order_status_id' => $filter_order_status_id,
            'filter_customer' => $filter_customer,
            'filter_company' => $filter_company,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];
        // echo "<pre>";print_r($filter_data);die;
        if ('' != $filter_customer || '' != $filter_company) {
 
            $data['check'] =  $this->load->controller('common/check/checkValidCustomer',array(null,$filter_customer,$filter_company));;
           
           
            // echo "<pre>";print_r($data['check'] );die;
            if($data['check']=="true")
            {
             $customer_total = $this->model_report_customer->getTotalValidCustomerOrders($filter_data);

            $results = $this->model_report_customer->getValidCustomerOrders($filter_data);
            }
            else
            {
                $json['status'] = 500;
                $json['data'] = [];
                $json['message'] = "Unauthorized to access the requested data";
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($json));
                return;
            }
        } else {
            $customer_total = 0;
            $results = null;
        }
        $this->load->model('sale/order');
        if (is_array($results) && count($results) > 0) {
            $log = new Log('error.log');
            $log->write('Yes It Is Array');
            foreach ($results as $result) {
                $products_qty = 0;
                if ($this->model_sale_order->hasRealOrderProducts($result['order_id'])) {
                    $products_qty = $this->model_sale_order->getRealOrderProductsItems($result['order_id']);
                } else {
                    $products_qty = $this->model_sale_order->getOrderProductsItems($result['order_id']);
                }
                $sub_total = 0;
                $totals = $this->model_sale_order->getOrderTotals($result['order_id']);
                //echo "<pre>";print_r($results);die;
                foreach ($totals as $total) {
                    if ('sub_total' == $total['code']) {
                        $sub_total = $total['value'];
                        break;
                    }
                }
                $data['customers'][] = [
                'company' => $result['company'],
                'customer' => $result['customer'],
                'email' => $result['email'],
                'customer_group' => $result['customer_group'],
                'status' => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'order_id' => $result['order_id'],
                'products' => $result['products'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'delivery_date' => date($this->language->get('date_format_short'), strtotime($result['delivery_date'])),
                'editedproducts' => $products_qty,
                'po_number' => $result['po_number'],
                // 'total' => $this->currency->format($result['total'], $this->config->get('config_currency')).replace("KES",""),
                'total' => $this->currency->format($result['total'], $this->config->get('config_currency')),
                'subtotal' => str_replace('KES', ' ', $this->currency->format($sub_total)),
            ];
            }
        }
        //  echo "<pre>";print_r($data['customers']);die;
         
        // $data['token'] = $this->session->data['token'];

        // $this->load->model('localisation/order_status');

        // $data['order_statuses'] = $this->model_localisation_order_status->getValidOrderStatuses();

        // $this->load->model('sale/customer');

        // $data['customer_names'] = $this->model_sale_customer->getCustomers(null);

        $url = '';

        if (isset($this->request->get['filter_date_start'])) {
            $url .= '&filter_date_start='.$this->request->get['filter_date_start'];
        }

        if (isset($this->request->get['filter_date_end'])) {
            $url .= '&filter_date_end='.$this->request->get['filter_date_end'];
        }

        if (isset($this->request->get['filter_order_status_id'])) {
            $url .= '&filter_order_status_id='.$this->request->get['filter_order_status_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer='.$this->request->get['filter_customer'];
        }

        if (isset($this->request->get['filter_company'])) {
            $url .= '&filter_company='.$this->request->get['filter_company'];
        }

        $pagination = new Pagination();
        $pagination->total = $customer_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        // $pagination->url = $this->url->link('report/customer_order/statement', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($customer_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($customer_total - $this->config->get('config_limit_admin'))) ? $customer_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $customer_total, ceil($customer_total / $this->config->get('config_limit_admin')));

        $data['filter_date_start'] = $filter_date_start;
        $data['filter_date_end'] = $filter_date_end;
        $data['filter_order_status_id'] = $filter_order_status_id;
        $data['filter_customer'] = $filter_customer;
        $data['filter_company'] = $filter_company;

         // echo "<pre>";print_r($data);die;
         $json['data'] =$data;
         $json['message'] = "Success";

         $this->response->addHeader('Content-Type: application/json');
         $this->response->setOutput(json_encode($json));
        }
        catch(exception $ex)
        {
            $json['status'] = 400;
            $json['data'] = [];
            $json['message'] = "Error in fetching data.";
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));

        }
    }

    public function addStatementexcel()
    {
        // $this->load->language('report/customer_statement');

        // $this->document->setTitle($this->language->get('heading_title'));

        if (isset($this->request->post['filter_date_start'])) {
            $filter_date_start = $this->request->post['filter_date_start'];
        } else {
            $filter_date_start ="";//  '1990-01-01'default date removed
        }

        if (isset($this->request->post['filter_date_end'])) {
            $filter_date_end = $this->request->post['filter_date_end'];
        } else {
            $filter_date_end = date('Y-m-d');
        }

        if (isset($this->request->post['filter_order_status_id'])) {
            $filter_order_status_id = $this->request->post['filter_order_status_id'];
        } else {
            $filter_order_status_id = 0;
        }

        if (isset($this->request->post['filter_customer'])) {
            $filter_customer = $this->request->post['filter_customer'];
        } else {
            $filter_customer = 0;
        }

        if (isset($this->request->post['filter_company'])) {
            $filter_company = $this->request->post['filter_company'];
        } else {
            $filter_company = 0;
        }


        

        $filter_data = [
            'filter_date_start' => $filter_date_start,
            'filter_date_end' => $filter_date_end,
            'filter_order_status_id' => $filter_order_status_id,
            'filter_customer' => $filter_customer,
            'filter_company' => $filter_company,
        ];



        if ('' != $filter_customer || '' != $filter_company) {
 
            $data['check'] =  $this->load->controller('common/check/checkValidCustomer',array(null,$filter_customer,$filter_company));;
           
           
            // echo "<pre>";print_r($data['check'] );die;
            if($data['check']=="true")
            {
                $this->load->model('report/excel');
                $this->model_report_excel->download_customer_statement_excel($filter_data);
            }
            else
            {
                $json['status'] = 500;
                $json['data'] = [];
                $json['message'] = "Unauthorized to access the requested data";
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($json));
                return;
            }
        } else {
            $json['status'] = 200;
            $json['message'] = "No Data Available";
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
            return;
        }
        $json['status'] = 200;
        $json['message'] = "Downloaded Successfully";
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
      
    }


    public function addConsolidatedOrderProduct() {
        $orderid = $this->request->post['order_id'];
        $customer = $this->request->post['customer'];
        $company = $this->request->post['company'];
        $date = $this->request->post['date'];

        $data = [];
        $data['consolidation'][] = [
            'orderid' => $orderid,
            'customer' => $customer,
            'company' => $company,
            'date' => $date,
        ];



        if ('' != $company || '' != $customer) {
 
            $data['check'] =  $this->load->controller('common/check/checkValidCustomer',array(null,$customer,$company));;
           
           
            // echo "<pre>";print_r($data['check'] );die;
            if($data['check']=="true")
            {
                $orderProducts = $this->getOrderProductsWithVariancesNew($orderid);

            }
            else
            {
                $json['status'] = 500;
                $json['data'] = [];
                $json['message'] = "Unauthorized to access the requested data";
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($json));
                return;
            }
        } else {
            $json['status'] = 200;
            $json['message'] = "No Data Available";
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
            return;
        }


        $data['products'] = $orderProducts;
        $sum = 0;
        foreach ($orderProducts as $item) {
            $sum += $item['total_updatedvalue'];
        }
        // $data['consolidation'][$index]['amount'] = $sum;
        //   $totalOrdersAmount += $sum;
        // $data['consolidation']['total'] = $totalOrdersAmount;

        $this->load->model('report/excel');
        $this->model_report_excel->download_order_products_excel($data);
    }

    public function getOrderProductsWithVariancesNew($order_id) {
        $this->load->model('sale/order');

        $orderProducts = [];
        $order_info = $this->model_sale_order->getOrder($order_id);
        if ($this->model_sale_order->hasRealOrderProducts($order_id)) {
            // Order products with weight change
            $originalProducts = $products = $this->model_sale_order->getRealOrderProducts($order_id);
        } else {
            // Products as the user ordered them on the platform
            $originalProducts = $products = $this->model_sale_order->getOrderProducts($order_id);
        }

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


    // public function orderexcel()
    // {
    //     $this->load->language('report/customer_order');

    //     $this->document->setTitle($this->language->get('heading_title'));

    //     if (isset($this->request->get['filter_date_start'])) {
    //         $filter_date_start = $this->request->get['filter_date_start'];
    //     } else {
    //         $filter_date_start = '1990-01-01';
    //     }

    //     if (isset($this->request->get['filter_date_end'])) {
    //         $filter_date_end = $this->request->get['filter_date_end'];
    //     } else {
    //         $filter_date_end = date('Y-m-d');
    //     }

    //     if (isset($this->request->get['filter_order_status_id'])) {
    //         $filter_order_status_id = $this->request->get['filter_order_status_id'];
    //     } else {
    //         $filter_order_status_id = 0;
    //     }

        

    //     $filter_data = [
    //         'filter_date_start' => $filter_date_start,
    //         'filter_date_end' => $filter_date_end,
    //         'filter_order_status_id' => $filter_order_status_id,
             
    //     ];

    //     $this->load->model('report/excel');
    //     $this->model_report_excel->download_customer_order_excel($filter_data);
    // }




    public function getCustomerMostBoughtProducts() {        
            $json = [];
            $json['status'] = 200;
            $json['data'] = [];
            $json['message'] = [];
            try{
                $this->load->model('account/dashboard');
                $data = array();
                $data = [
                    /*'filter_customer' => $this->request->get['customer_id'] > 0 ? $this->request->get['customer_id'] : $this->customer->getId(),*/
                    'filter_customer' => $this->request->get['customer_id'],
                    'filter_date_start' => $this->request->get['start'],
                    'filter_date_end' => $this->request->get['end']
                ];

                $most_purchased = $this->model_account_dashboard->getboughtproductswithRealOrders($data);
                $data['most_purchased'] = $most_purchased;
                //$log = new Log('error.log');
                //$log->write('most_purchased');
                //$log->write($most_purchased);
                //$log->write('most_purchased');
                // $this->response->setOutput($this->load->view('metaorganic/template/account/most_bought_products.tpl', $data));
                
                // echo "<pre>";print_r($data);die;
                $json['data'] =$data;$json['message'] = "Success";
            }
            catch(exception $ex)
            {
                //$log = new Log('error.log');
                //$log->write('most_purchased'); 
             $json['status'] = 400;
            $json['message'] = "Something went wrong!";
            }
            finally{
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($json));
            }
                  
  }

    public function getRecentActivities() {

        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];
        try{
        $this->load->model('account/dashboard');
        $data = array();
        $data = [
            /*'filter_customer' => $this->request->get['customer_id'] > 0 ? $this->request->get['customer_id'] : $this->customer->getId(),*/
            'filter_customer' => $this->request->get['customer_id'],
            'filter_date_start' => $this->request->get['start'],
            'filter_date_end' => $this->request->get['end']
        ];

        $user_recent_activity = $this->model_account_dashboard->getRecentActivity_new($data);

        foreach ($user_recent_activity as $ra) {
            if (15 == $ra['order_status_id']) {
                $comment1 = 'Placed Order';
                $comment2 = ' and Approval is Required';
            } elseif (14 == $ra['order_status_id']) {
                $comment1 = 'Placed Order';
                $comment2 = ' ';
            } else {
                $comment1 = 'Placed Order';
                $comment2 = ' and the order is  ' . $ra['name'];
            }

            $recent_activity[] = ['store_name' => $ra['store_name'],
                'firstname' => $ra['firstname'],
                'lastname' => $ra['lastname'],
                'order_id' => $ra['order_id'],
                'comment1' => $comment1,
                'comment2' => $comment2,
                'href' => $this->url->link('account/order/info', 'order_id=' . $ra['order_id'], 'SSL'),
                'total' => $this->currency->format($ra['total'], $this->config->get('config_currency')),
                'date_added' => $ra['date_added'],];
        }

        $data['recent_activity'] = $recent_activity;
        $json['message'] = "Success";
        $json['data'] =$data;
            }
            catch(exception $ex)
            {
                //$log = new Log('error.log');
                //$log->write('most_purchased');
            $json['status'] = 400;
            $json['message'] = "Something went wrong!";
            }
            finally{
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($json));
            
            }

        // $this->response->setOutput($this->load->view('metaorganic/template/account/recent_activity.tpl', $data));
    }

    public function getRecentOrders() {
        $json = [];
       
               $json['status'] = 200;
               $json['data'] = [];
               $json['message'] = [];

               try{
        $this->load->model('account/dashboard');
        $data = array();
        $data = [
            /*'filter_customer' => $this->request->get['customer_id'] > 0 ? $this->request->get['customer_id'] : $this->customer->getId(),*/
            'filter_customer' => $this->request->get['customer_id'],
            'filter_date_start' => $this->request->get['start'],
            'filter_date_end' => $this->request->get['end']
        ];
        $recent_orders = $this->model_account_dashboard->getRecentOrders_new($data);
        foreach ($recent_orders as $ro) {
            $user_recent_orders[] = ['order_id' => $ro['order_id'],
                'name' => $ro['name'],
                'date_added' => $ro['date_added'],
                'delivery_date' => $ro['delivery_date'],
                'href' => $this->url->link('account/order/info', 'order_id=' . $ro['order_id'], 'SSL'),
                'real_href' => $this->url->link('account/order/realinfo', 'order_id=' . $ro['order_id'], 'SSL'),];
        }
        $data['recent_orders'] = $user_recent_orders;
        $json['data'] =$data; $json['message'] = "Success";
        // $this->response->setOutput($this->load->view('metaorganic/template/account/recentorders.tpl', $data)); $json['message'] = "Success";
        }
        catch(exception $ex)
        {
            //$log = new Log('error.log');
            //$log->write('most_purchased'); 
        $json['status'] = 400;
        $json['message'] = "Something went wrong!";
        }
        finally{
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        
        }
    }


    public function getProductPurchaseHistory()
    {
        $json = $this->getPurchaseHistoryChartsData('getProductPurchaseHistory', false);

        $json['order']['label'] = 'Product purchase history per day';

        $this->response->setOutput(json_encode($json));
    }


    public function getPurchaseHistoryChartsData($modelFunction, $currency_format = false)
    {
        $this->load->model('account/dashboard');

        $json = [];
 
        //  echo '<pre>';print_r($this->request->get);exit;
        

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


        if (!empty($this->request->get['product_id'])) {
            $product_id = $this->request->get['product_id'];
        } else {
            $product_id = '';
        }


        //    echo '<pre>';print_r($this->request->get);exit;


        if($product_id == ''|| $selectedcustomer_id == ''|| $end == ''|| $start == '')
        {
            //filters are not correct
            return $json;
        }
        // echo "<pre>";print_r($selectedcustomer_id);die;



        $date_start = date_create($start)->format('Y-m-d H:i:s');
        $date_end = date_create($end)->format('Y-m-d H:i:s');

        $diff_str = strtotime($end) - strtotime($start);
        $diff = floor($diff_str / 3600 / 24) + 1;

        $range = $this->getRange($diff);

        $customer_id = $this->customer->getId();

        //   echo "<pre>";print_r($json);die;
        // echo "<pre>";print_r($range);die;


        switch ($range) {
            
            
            case 'day':
        // echo "<pre>";print_r($selectedcustomer_id);die;

                $results = $this->model_account_dashboard->{$modelFunction}($selectedcustomer_id, $date_start, $date_end, 'DAY', $this->customer->getId(),$product_id);
                $str_date = substr($date_start, 0, 10);
                $order_data = [];

        // echo "<pre>";print_r($results);die;

                for ($i = 0; $i < $diff; ++$i) {
                    $date = date_create($str_date)->modify('+'.$i.' day')->format('Y-m-d');

                    //setting default values
                    $order_data[$date] = [
                        'day' => $date,
                        'total' => 0,
                    ];

                    $json['xaxis'][] = [$i, $date];
                }

                foreach ($results->rows as $result) {
                    $total = $result['total'];

                    if ($currency_format) {
                        $total = $this->currency->format($result['total'], $this->config->get('config_currency'), '', false);
                    }

                    $order_data[$result['date']] = [
                        'day' => $result['date'],
                        'total' => $total,
                    ];
                }

                $i = 0;
                foreach ($order_data as $key => $value) {
                    $json['order']['data'][] = [$i++, $value['total']];
                }

                break;
            case 'month':
                $results = $this->model_account_dashboard->{$modelFunction}($selectedcustomer_id, $date_start, $date_end, 'MONTH', $this->customer->getId(),$product_id);
                $months = $this->getMonths($date_start, $date_end);
                $order_data = [];

                for ($i = 0; $i < count($months); ++$i) {
                    $order_data[$months[$i]] = [
                        'month' => $months[$i],
                        'total' => 0,
                    ];

                    $json['xaxis'][] = [$i, $months[$i]];
                }

                foreach ($results->rows as $result) {
                    $order_data[$result['month']] = [
                        'month' => $result['month'],
                        'total' => $result['total'],
                    ];
                }

                $i = 0;
                foreach ($order_data as $key => $value) {
                    $json['order']['data'][] = [$i++, $value['total']];
                }
                break;
            case 'year':
                $results = $this->model_account_dashboard->{$modelFunction}($selectedcustomer_id, $date_start, $date_end, 'YEAR', $this->customer->getId(),$product_id);
                $str_date = substr($date_start, 0, 10);
                $order_data = [];
                $diff = floor($diff / 365) + 1;

                for ($i = 0; $i < $diff; ++$i) {
                    $date = date_create($str_date)->modify('+'.$i.' year')->format('Y');

                    $order_data[$date] = [
                        'year' => $date,
                        'total' => 0,
                    ];

                    $json['xaxis'][] = [$i, $date];
                }

                foreach ($results->rows as $result) {
                    $order_data[$result['year']] = [
                        'year' => $result['year'],
                        'total' => $result['total'],
                    ];
                }

                $i = 0;
                foreach ($order_data as $key => $value) {
                    $json['order']['data'][] = [$i++, $value['total']];
                }
                break;
        }

        $modelFunction = str_replace('get', 'getTotal', $modelFunction);
        $result = $this->model_account_dashboard->{$modelFunction}($selectedcustomer_id, $date_start, $date_end, $customer_id,$product_id);

        // echo "<pre>";print_r($currency_format);die;

        $total = $result['total'];
        if ($currency_format) {
            $total = $this->currency->format($result['total'], $this->config->get('config_currency'));
        }

        $json['order']['total'] = $total;

        return $json;
    }

   
}
