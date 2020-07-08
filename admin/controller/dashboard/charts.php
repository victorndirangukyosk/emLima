<?php

class ControllerDashboardCharts extends Controller {

    public function index() {
        $this->load->language('dashboard/charts');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_welcome'] = sprintf($this->language->get('text_welcome'), $this->user->getUsername());
        $data['text_new_order'] = $this->language->get('text_new_order');
        $data['text_new_customer'] = $this->language->get('text_new_customer');
        $data['text_total_sale'] = $this->language->get('text_total_sale');
        $data['text_marketing'] = $this->language->get('text_marketing');
        $data['text_analytics'] = $this->language->get('text_analytics');
        $data['text_online'] = $this->language->get('text_online');
        $data['text_activity'] = $this->language->get('text_activity');
        $data['text_last_order'] = $this->language->get('text_last_order');
        $data['text_day'] = $this->language->get('text_day_home');
        $data['text_week'] = $this->language->get('text_week_home');
        $data['text_month'] = $this->language->get('text_month_home');
        $data['text_year'] = $this->language->get('text_year_home');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_event_summary'] = $this->language->get('text_event_summary');
        $data['text_sale'] = $this->language->get('text_sale');
        $data['text_order'] = $this->language->get('text_order');
        $data['text_customer'] = $this->language->get('text_customer');
        $data['text_affiliates'] = $this->language->get('text_affiliates');
        $data['text_reviews'] = $this->language->get('text_reviews');
        $data['text_rewards'] = $this->language->get('text_rewards');
        $data['text_shop_info'] = $this->language->get('text_shop_info');
        $data['text_products_and_sales'] = $this->language->get('text_products_and_sales');
        $data['text_best_sellers'] = $this->language->get('text_best_sellers');
        $data['text_less_sellers'] = $this->language->get('text_less_sellers');
        $data['text_most_viewed'] = $this->language->get('text_most_viewed');

        $data['text_total_sale'] = $this->language->get('text_total_sale');
        $data['text_total_sale_year'] = $this->language->get('text_total_sale_year');
        $data['text_total_order'] = $this->language->get('text_total_order');
        $data['text_total_customer'] = $this->language->get('text_total_customer');
        $data['text_total_customer_approval'] = $this->language->get('text_total_customer_approval');
        $data['text_total_review_approval'] = $this->language->get('text_total_review_approval');
        $data['text_total_affiliate'] = $this->language->get('text_total_affiliate');
        $data['text_total_affiliate_approval'] = $this->language->get('text_total_affiliate_approval');

        $data['column_order_id'] = $this->language->get('column_order_id');
        $data['column_customer'] = $this->language->get('column_customer');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_action'] = $this->language->get('column_action');
        $data['column_product_name'] = $this->language->get('column_product_name');
        $data['column_product_id'] = $this->language->get('column_product_id');

        $data['token'] = $this->session->data['token'];



        $this->load->model('localisation/currency');

        $currency = $this->model_localisation_currency->getCurrencyByCode($this->config->get('config_currency'));
        $data['symbol_left'] = $currency['symbol_left'];
        $data['symbol_right'] = $currency['symbol_right'];

        $data['todaysCreatedOrders'] = $this->getTodayOrderChartData('xyz','day' ,true);
        $data['todaysDeliveredOrders'] = $this->getTodayOrderChartData('complete','day', true);
        $data['todaysCancelledOrders'] = $this->getTodayOrderChartData('cancelled', 'day',true);

        if(count($data['todaysCreatedOrders']) <= 0) {
            $data['todaysCreatedOrders']['total'] = 0;
            $data['todaysCreatedOrders']['value'] = 0;
        }

        if(count($data['todaysDeliveredOrders']) <= 0) {
            $data['todaysDeliveredOrders']['total'] = 0;
            $data['todaysDeliveredOrders']['value'] = 0;
        }

        if(count($data['todaysCancelledOrders']) <= 0) {
            $data['todaysCancelledOrders']['total'] = 0;
            $data['todaysCancelledOrders']['value'] = 0;
        }

        $data['todaysCreatedOrders']['value'] = $this->currency->format($data['todaysCreatedOrders']['value']);
        $data['todaysDeliveredOrders']['value'] = $this->currency->format($data['todaysDeliveredOrders']['value']);
        $data['todaysCancelledOrders']['value'] = $this->currency->format($data['todaysCancelledOrders']['value']);

        $this->load->model('setting/store');

        $data['store_count'] = $this->model_setting_store->getTotalStores();


        //echo "<pre>";print_r($data['store_count']);die;
        //echo "<pre>";print_r($data['todaysCreatedOrders']);print_r($data['todaysDeliveredOrders']);print_r($data['todaysCancelledOrders']);die;

        # links
        $data['link_review_waiting'] = $this->url->link('catalog/review', 'token=' . $this->session->data['token'] . '&sort=r.status&order=ASC', 'SSL');
        $data['link_customer_waiting'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&filter_approved=0', 'SSL');
        $data['link_customers'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'], 'SSL');
        $data['link_sales'] = $this->url->link('report/sale_order', 'token=' . $this->session->data['token'], 'SSL');
        $data['link_orders'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL');
        $data['link_affiliates'] = $this->url->link('sale/affiliate', 'token=' . $this->session->data['token'], 'SSL');
        $data['link_affiliate_waiting'] = $this->url->link('sale/affiliate', 'token=' . $this->session->data['token'] . '&filter_approved=0', 'SSL');


        return $this->load->view('dashboard/charts.tpl', $data);
    }

    # Ajax Functions
    /*     * ******************************************************************************************************************* */
    
    public function vendorsales() {
        $this->load->language('dashboard/charts');
        $this->load->model('dashboard/charts');

        $json = $this->getChartData('getVendorSales', true);
        $json['order']['label'] = $this->language->get('text_sale');

        $this->response->setOutput(json_encode($json));
    }

    public function vendorbookedsales() {
        $this->load->language('dashboard/charts');
        $this->load->model('dashboard/charts');

        $json = $this->getChartData('getVendorBookedSales', true);
        $json['order']['label'] = $this->language->get('text_sale');

        $this->response->setOutput(json_encode($json));
    }
    
    public function VendorOrders(){
        $this->load->language('dashboard/charts');

        $json = $this->getChartData('getVendorOrders');
        $json['order']['label'] = $this->language->get('text_order');

        $json['created_orders'] = $this->getTodayOrderChartData('xyz','day' ,true);
        $json['delivered_orders'] = $this->getTodayOrderChartData('complete','day', true);
        $json['cancelled_orders'] = $this->getTodayOrderChartData('cancelled', 'day',true);

        if(count($json['created_orders']) <= 0) {
            $json['created_orders']['total'] = 0;
            $json['created_orders']['value'] = 0;
        }

        if(count($json['delivered_orders']) <= 0) {
            $json['delivered_orders']['total'] = 0;
            $json['delivered_orders']['value'] = 0;
        }

        if(count($json['cancelled_orders']) <= 0) {
            $json['cancelled_orders']['total'] = 0;
            $json['cancelled_orders']['value'] = 0;
        }

        $json['created_orders']['value'] = $this->currency->format($json['created_orders']['value']);
        $json['delivered_orders']['value'] = $this->currency->format($json['delivered_orders']['value']);
        $json['cancelled_orders']['value'] = $this->currency->format($json['cancelled_orders']['value']);

        
        $this->response->setOutput(json_encode($json));
    }


    public function VendorCreatedOrders(){
        $this->load->language('dashboard/charts');

        $json = $this->getChartData('getVendorCreatedOrders');
        $json['order']['label'] = $this->language->get('text_order');        
        $this->response->setOutput(json_encode($json));
    }


    public function VendorCancelledOrders(){
        $this->load->language('dashboard/charts');

        $json = $this->getChartData('getVendorCancelledOrders');
        $json['order']['label'] = $this->language->get('text_order');        
        $this->response->setOutput(json_encode($json));
    }
    
    public function orders() {
        $this->load->language('dashboard/charts');

        $json = $this->getChartData('getOrders');
        $json['order']['label'] = $this->language->get('text_order');

        $json['created_orders'] = $this->getTodayOrderChartData('xyz','day' ,true);
        $json['delivered_orders'] = $this->getTodayOrderChartData('complete','day', true);
        $json['cancelled_orders'] = $this->getTodayOrderChartData('cancelled', 'day',true);

        if(count($json['created_orders']) <= 0) {
            $json['created_orders']['total'] = 0;
            $json['created_orders']['value'] = 0;
        }

        if(count($json['delivered_orders']) <= 0) {
            $json['delivered_orders']['total'] = 0;
            $json['delivered_orders']['value'] = 0;
        }

        if(count($json['cancelled_orders']) <= 0) {
            $json['cancelled_orders']['total'] = 0;
            $json['cancelled_orders']['value'] = 0;
        }

        $json['created_orders']['value'] = $this->currency->format($json['created_orders']['value']);
        $json['delivered_orders']['value'] = $this->currency->format($json['delivered_orders']['value']);
        $json['cancelled_orders']['value'] = $this->currency->format($json['cancelled_orders']['value']);

        $this->response->setOutput(json_encode($json));
    }


    public function Createdorders() {
        $this->load->language('dashboard/charts');

        $json = $this->getChartData('getCreatedOrders');
        $json['order']['label'] = $this->language->get('text_order');     

       

        $this->response->setOutput(json_encode($json));
    }


    public function Cancelledorders() {
        $this->load->language('dashboard/charts');

        $json = $this->getChartData('getCancelledOrders');
        $json['order']['label'] = $this->language->get('text_order');  

       

        $this->response->setOutput(json_encode($json));
    }


    public function customers() {
        $this->load->language('dashboard/charts');
        $this->load->model('dashboard/charts');

        $json = $this->getChartData('getCustomers');
        $json['order']['label'] = $this->language->get('text_customer');

        $this->response->setOutput(json_encode($json));
    }

    public function sales() {
        $this->load->language('dashboard/charts');
        $this->load->model('dashboard/charts');

        $json = $this->getChartData('getSales', true);
        $json['order']['label'] = $this->language->get('text_sale');

        $this->response->setOutput(json_encode($json));
    }


    public function bookedsales() {
        $this->load->language('dashboard/charts');
        $this->load->model('dashboard/charts');

        $json = $this->getChartData('getBookedSales', true);
        $json['order']['label'] = $this->language->get('text_sale');

        $this->response->setOutput(json_encode($json));
    }

    public function affiliates() {
        $this->load->language('dashboard/charts');
        $this->load->model('dashboard/charts');

        $json = $this->getChartData('getAffiliates');
        $json['order']['label'] = $this->language->get('text_affiliates');

        $this->response->setOutput(json_encode($json));
    }

    public function reviews() {
        $this->load->language('dashboard/charts');
        $this->load->model('dashboard/charts');

        $json = $this->getChartData('getReviews');
        $json['order']['label'] = $this->language->get('text_reviews');

        $this->response->setOutput(json_encode($json));
    }

    public function rewards() {
        $this->load->language('dashboard/charts');
        $this->load->model('dashboard/charts');

        $json = $this->getChartData('getRewards');
        $json['order']['label'] = $this->language->get('text_rewards');

        $this->response->setOutput(json_encode($json));
    }

    public function getChartData($modelFunction, $currency_format = false) {
        $this->load->model('dashboard/charts');

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

        $date_start = date_create($start)->format('Y-m-d H:i:s');
        $date_end = date_create($end)->format('Y-m-d H:i:s');

        $diff_str = strtotime($end) - strtotime($start);
        $diff = floor($diff_str / 3600 / 24) + 1;

        $range = $this->getRange($diff);

        switch ($range) {
            case 'hour':
                $results = $this->model_dashboard_charts->{$modelFunction}($date_start, $date_end, 'HOUR');
                $order_data = array();

                for ($i = 0; $i < 24; $i++) {
                    $order_data[$i] = array(
                        'hour' => $i,
                        'total' => 0
                    );

                    $json['xaxis'][] = array($i, $i . ':00');
                }

                foreach ($results->rows as $result) {
                    $order_data[$result['hour']] = array(
                        'hour' => $result['hour'],
                        'total' => $result['total']
                    );
                }

                foreach ($order_data as $key => $value) {
                    $json['order']['data'][] = array($key, $value['total']);
                }

                break;
            default:
            case 'day':
                $results = $this->model_dashboard_charts->{$modelFunction}($date_start, $date_end, 'DAY');
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
                $results = $this->model_dashboard_charts->{$modelFunction}($date_start, $date_end, 'MONTH');
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
                $results = $this->model_dashboard_charts->{$modelFunction}($date_start, $date_end, 'YEAR');
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
        $result = $this->model_dashboard_charts->{$modelFunction}($date_start, $date_end);

        $total = $result['total'];
        if ($currency_format) {
            $total = $this->currency->format($result['total'], $this->config->get('config_currency'));
        }

        $json['order']['total'] = $total;

        return $json;
    }

    public function getTodayOrderChartData($type, $range,$currency_format = false) {

        $results = '';
        $modelFunction = 'getTodaysOrders';
        $this->load->model('dashboard/charts');

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

        $date_start = date_create($start)->format('Y-m-d');
        $date_end = date_create($end)->format('Y-m-d');

        $diff_str = strtotime($end) - strtotime($start);
        $diff = floor($diff_str / 3600 / 24) + 1;

        //$range = $this->getRange($diff);


        switch ($range) {
            case 'hour':
                $results = $this->model_dashboard_charts->{$modelFunction}($date_start, $date_end, 'HOUR');
                $order_data = array();

                for ($i = 0; $i < 24; $i++) {
                    $order_data[$i] = array(
                        'hour' => $i,
                        'total' => 0
                    );

                    $json['xaxis'][] = array($i, $i . ':00');
                }

                foreach ($results->rows as $result) {
                    $order_data[$result['hour']] = array(
                        'hour' => $result['hour'],
                        'total' => $result['total']
                    );
                }

                foreach ($order_data as $key => $value) {
                    $json['order']['data'][] = array($key, $value['total']);
                }

                break;
            default:
            case 'day':
                $results = $this->model_dashboard_charts->{$modelFunction}($date_start, $date_end, 'DAY',$type);
                /*$str_date = substr($date_start, 0, 10);
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
                }*/

                break;
            case 'month':
                $results = $this->model_dashboard_charts->{$modelFunction}($date_start, $date_end, 'MONTH');
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
                $results = $this->model_dashboard_charts->{$modelFunction}($date_start, $date_end, 'YEAR');
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

        /*$modelFunction = str_replace('get', 'getTotal', $modelFunction);
        $result = $this->model_dashboard_charts->{$modelFunction}($date_start, $date_end);

        $total = $result['total'];
        if ($currency_format) {
            $total = $this->currency->format($result['total'], $this->config->get('config_currency'));
        }

        $json['order']['total'] = $total;*/

        return $results;
    }


    # extra functions
    ######################################################################################################################################################

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

    public function getRange($diff) {
        if (isset($this->request->get['range']) and ! empty($this->request->get['range']) and $this->request->get['range'] != 'undefined') {
            $range = $this->request->get['range'];
        } else {
            $range = 'day';
        }

        if ($diff < 365 and $range == 'year') {
            $range = 'month';
        }

        if ($diff < 28) {
            $range = 'day';
        }

        if ($diff == 1) {
            $range = 'hour';
        }

        if ($diff >32 and $range == 'day') {
            $range = 'month';
        }
       
        return $range;
    }



    public function export_excel() {	 
		if ( isset( $this->request->get['start_date'] ) ) {
			$start_date = $this->request->get['start_date'];
		} else {
			$start_date = null;
		}

		if ( isset( $this->request->get['end_date'] ) ) {
			$end_date = $this->request->get['end_date'];
		} else {
			$end_date = null;
		}

		if ( isset( $this->request->get['ss'] ) ) {
			$ss = $this->request->get['ss'];
		} else {
			$ss = null;
		}

		if ( isset( $this->request->get['os'] ) ) {
			$os = $this->request->get['os'];
		} else {
			$os = null;
		}

		if ( isset( $this->request->get['cs'] ) ) {
			$cs = $this->request->get['cs'];
		} else {
			$cs = null;
		}

		if ( isset( $this->request->get['bs'] ) ) {
			$bs = $this->request->get['bs'];
		} else {
			$bs = null;
		}


		if ( isset( $this->request->get['cos'] ) ) {
			$cos = $this->request->get['cos'];
		} else {
			$cos = null;
		}

		if ( isset( $this->request->get['cns'] ) ) {
			$cns = $this->request->get['cns'];
		} else {
			$cns = null;
		} 

		$data = array(
			'start_date' => $start_date,
			'end_date' => $end_date,
			'ss' => $ss,
			'os' => $os,
			'cs' => $cs,
			'bs' => $bs,
			'cos' => $cos,
			'cns' => $cns 
		); 


  //echo "<pre>";print_r($data);die;


        
		$this->load->model('report/excel');	
		

		$this->model_report_excel->download_dashboard_excel($data);
		
    }


}
