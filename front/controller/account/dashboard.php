<?php

require_once DIR_SYSTEM.'/vendor/konduto/vendor/autoload.php';

//require_once DIR_SYSTEM.'/vendor/mpesa-php-sdk-master/vendor/autoload.php';

use \Konduto\Core\Konduto;
use \Konduto\Models;
use paragraph1\phpFCM\Client;
use paragraph1\phpFCM\Client as FCMClient;
use paragraph1\phpFCM\Message;
use paragraph1\phpFCM\Recipient\Device;
use paragraph1\phpFCM\Notification;
require_once DIR_SYSTEM.'/vendor/fcp-php/autoload.php';

require DIR_SYSTEM.'vendor/Facebook/autoload.php';

require_once DIR_APPLICATION.'/controller/api/settings.php';

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

        $this->document->addStyle('/front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_login.css');
        
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
              
            $total_orders=$orders=0;
       if(!empty($customer_info))
       {

        $total_orders= $this->model_account_dashboard->getTotalOrders($this->customer->getId());
        $orders= $this->model_account_dashboard->getOrders($this->customer->getId());
        $most_purchased= $this->model_account_dashboard->getMostPurchased($this->customer->getId());
         
        $this->load->model('sale/order');
        $total_spent=0;$products_qty = 0;
        $todaydate =$todaydate =   date('Y-m-d');
        if(!empty($orders))
        $first_order_Date=$orders[0]['date_added'];
        foreach ($orders as $result) {
           
            if($this->model_sale_order->hasRealOrderProducts($result['order_id'])) {
                $products_qty = $products_qty + $this->model_sale_order->getRealOrderProductsItems($result['order_id']);
            } else {
                $products_qty   =$products_qty+ $this->model_sale_order->getOrderProductsItems($result['order_id']);
            }
            $sub_total = 0;
           $totals = $this->model_sale_order->getOrderTotals($result['order_id']);
             // echo "<pre>";print_r($orders);die;
            foreach ($totals as $total) {
                if($total['code'] == 'sub_total') {
                    $sub_total = $total['value'];
                    $total_spent   = $total_spent+$sub_total;
                    break;
                }
            }
            
            //     'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])) ,
                // 'total' => $this->currency->format($result['total'], $this->config->get('config_currency')),
            //     'subtotal'     => $this->currency->format($sub_total)
             
        }
       if($total_orders)
       {
        $avg_value=($total_spent/$total_orders);
       }
       else{
        $avg_value=0;
       }
           

            $months = 0;

            while (($date1 = strtotime('+1 MONTH', $date1)) <= $date2)
                $months++;
                if($months==0)
            $frequency=($total_orders);                    
                else
            $frequency=($total_orders/$months);   

            $customer_name=$customer_info['firstname'].' '.$customer_info['lastname'];
            $data['DashboardData']= array(

                'customer_name' => $customer_name,
                'email' => $customer_info['email'],
                'telephone' => $customer_info['telephone'],
                'total_orders' => $total_orders,
                'total_spent' =>$this->currency->format($total_spent, $this->config->get('config_currency')), 
                'avg_value' => $this->currency->format($avg_value,$this->config->get('config_currency')), 
                'First_order_date' => $first_order_Date,
                'frequency' => $frequency,
                'most_purhased'=>$most_purchased
            );
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
        
}
