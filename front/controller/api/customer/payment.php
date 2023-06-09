<?php

require_once DIR_SYSTEM.'/vendor/konduto/vendor/autoload.php';

use \Konduto\Core\Konduto;
use \Konduto\Models;
use paragraph1\phpFCM\Client;
use paragraph1\phpFCM\Message;
use paragraph1\phpFCM\Recipient\Device;
use paragraph1\phpFCM\Notification;

require_once DIR_SYSTEM.'/vendor/fcp-php/autoload.php';

require DIR_SYSTEM.'vendor/Facebook/autoload.php';

require_once DIR_APPLICATION.'/controller/api/settings.php';

class Controllerapicustomerpayment extends Controller {

    private $error = array();

    public function getStripeCustomerId() {
      
        $json = array();
        
        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');
        $this->load->model('payment/stripe');
        $res = $this->model_payment_stripe->getCustomer($this->customer->getId());

        if($res) {
            $json['data'] = $res;    
        } else {
            $json['status'] = 10033;
            $json['message'][] = ['type' =>  '' , 'body' =>  'no stripe customer id' ];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addStripeEphemeralKey() {
      
        $json = array();
        
        $this->load->library('stripe');

        $this->load->language('information/locations');

        //$json['status'] = 200;
        $json['data'] = [];
        //$json['message'] = [];

        $this->load->language('api/general');
        $this->load->model('payment/stripe');

        $res = $this->model_payment_stripe->getCustomer($this->customer->getId());

        //echo "<pre>";print_r($res);die;
        if(isset($this->request->post['api_version']) && $this->initStripe() && $res) {

            try {

                $key = \Stripe\EphemeralKey::create(
                  array("customer" => $res['stripe_customer_id']),
                  array("stripe_version" => $this->request->post['api_version'])
                );

                //echo "<pre>";print_r($key);die;
                $json['data'] = $key;

            } catch (Exception $e) {
                
                //echo "<pre>";print_r($e->getMessage());die;
                $json['status'] = 10034;
                $json['message'][] = ['type' =>  'Exception' , 'body' =>  $e->getMessage() ];

            }

        } else {
            $json['status'] = 10033;
            $json['message'][] = ['type' =>  '' , 'body' =>  'no api_version sent' ];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json['data']));
    }


    public function addStripePayment() {
      
        $json = array();
        
        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');

        
        //if( isset($this->request->post['token']) && isset($this->request->post['order_id']) ) {
        if( true ) {

            //$store_id = $this->request->get['store_id'];

            $this->send();

            $json['data'] = $data;

        }  else {

            $json['status'] = 10013;

            $json['message'][] = ['type' =>  '' , 'body' =>  $this->language->get('text_not_loggedin') ];

            http_response_code(400);
        }
        
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addMpesaConfirm() {
        
        $log = new Log('error.log');
        $json = array();
        
        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');

        $log->write($this->request->post);
        if( isset($this->request->post['mpesa_refrence_id']) && isset($this->request->post['mpesa_phonenumber']) && isset($this->request->post['amount'])  ) {

            //$store_id = $this->request->get['store_id'];
            $data = $this->request->post;

            $payResp = $this->load->controller( 'payment/mpesa/apiConfirm',$data);

            $log->write($payResp);

            if($payResp) {
                $json['data'] = $payResp;
            }

        }  else {

            $json['status'] = 10013;

            $json['message'][] = ['type' =>  '' , 'body' =>  'Form data missing' ];

            http_response_code(400);
        }
        
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addMpesaComplete() {
      
        $log = new Log('error.log');

        $log->write('addMpesaComplete');

        $json = array();
        
        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');

        $log->write($this->request->post);
        if( isset($this->request->post['mpesa_refrence_id']) ) {

            //$store_id = $this->request->get['store_id'];
            $data = $this->request->post;

            $payResp = $this->load->controller( 'payment/mpesa/apiComplete',$data);

            $log->write($payResp);

            if($payResp) {
                $json['data'] = $payResp;
            }

        }  else {

            $json['status'] = 10013;

            $json['message'][] = ['type' =>  '' , 'body' =>  'Form data missing' ];

            http_response_code(400);
        }
        
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }



    public function stripePayment($orders,$token,$card_token) {

        $json = array();

        //echo "<pre>";print_r("send");die;
        $this->load->library('stripe');
        $this->load->model('checkout/order');
        $this->load->model('account/customer');
        $this->load->model('payment/stripe');

        $stripe_environment = $this->config->get('stripe_environment');

        //echo "<pre>";print_r($order_ids);die;


        foreach ($order_ids as $key => $value) {
            $order_id = $value;
        }

        //$order_info = $this->model_checkout_order->getOrder($order_ids);
        $order_info = $this->model_checkout_order->getOrder($order_id);

        //echo "<pre>";print_r($order_info);die;
        if($this->initStripe()) {
            //$use_existing_card = json_decode($this->request->post['existingCard']);

            //$saveCreditCard = $this->request->post['saveCreditCard'];
            //$card_token = $this->request->post['card'];

            $saveCreditCard = false;

            $use_existing_card = false;

            $stripe_customer_id = '';
            $stripe_charge_parameters = array(
                'amount' => (int)($order_info['total'] * 100),
                'currency' => $this->config->get('stripe_currency'),
                'metadata' => array(
                    //'orderId' => $order_ids
                    'orderId' => $order_id
                    
                ),
                "transfer_group" => "{ORDERID#".$order_id."}",
            );

            /*// Create a Charge:
            $charge = \Stripe\Charge::create(array(
              "amount" => 10000,
              "currency" => "usd",
              "source" => "tok_visa",
              //"transfer_group" => "{ORDER10}",
              "transfer_group" => "{ORDERID#".$order_id."}",
            ));*/

            $stripe_customer = $this->model_payment_stripe->getCustomer($this->customer->getId());

            # If customer is logged, but isn't registered as a customer in Stripe
            if($this->customer->isLogged() && !$stripe_customer) {
                $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

                if(isset($customer_info['email']) && ! empty($customer_info['email'])) {
                    $stripe_customer = \Stripe\Customer::create(array(
                        'email' => $customer_info['email'],
                        'metadata' => array(
                            'customerId' => $this->customer->getId()
                        )
                    ));

                    $this->model_payment_stripe->addCustomer(
                        $stripe_customer,
                        $this->customer->getId(),
                        $stripe_environment
                    );
                }
            }

            # If customer exists we use it
            

            //echo "<pre>";print_r($stripe_charge_parameters);die;

            $log = new Log('error.log');
            $log->write('model_payment_stripe');
            $log->write($stripe_customer);
            $log->write($use_existing_card);


            # May be the customer want to save its credit card
            if($stripe_customer && ($use_existing_card == false)) {

                $log->write("if");
                $stripe_charge_parameters['customer'] = $stripe_customer['stripe_customer_id'];
                $customer = \Stripe\Customer::retrieve($stripe_customer['stripe_customer_id']);
                $stripe_card = $customer->sources->create(array("source" => $card_token));
                $stripe_charge_parameters['customer'] = $customer['id'];
                $stripe_charge_parameters['source'] = $stripe_card['id'];

                if(!!json_decode($saveCreditCard)) {
                    $log->write("saveCreditCard");
                    $this->model_payment_stripe->addCard(
                        $stripe_card,
                        $this->customer->getId(),
                        $stripe_environment
                    );
                }
            } else {

                $log->write("else");
                $stripe_charge_parameters['source'] = $card_token;
            }

            if($use_existing_card && $stripe_customer) {
                $stripe_charge_parameters['customer'] = $stripe_customer['stripe_customer_id'];
            }

            

            $charge = \Stripe\Charge::create($stripe_charge_parameters);

            

            if(!json_decode($saveCreditCard) && isset($customer) && isset($stripe_card)) {
                $customer->sources->retrieve($stripe_card['id'])->delete();
            }

            if(isset($charge['id'])) {
                $this->model_payment_stripe->addOrder($order_info, $charge['id'], $stripe_environment);
                $message = 'Charge ID: '.$charge['id'].' Status:'. $charge['status'];
                //$this->model_checkout_order->addOrderHistory($order_ids, $this->config->get('stripe_order_status_id'), $message, false);

                foreach ($order_ids as $key => $value) {

                    //value is order id 
                    $this->model_checkout_order->addOrderHistory($value, $this->config->get('stripe_order_status_id'), $message, false);
                }
                
                $json['processed'] = true;
            }

        } else {
            $json['error'] = 'Contact administrator';
        }
        
        return $json;
    }

    private function initStripe() {
        $this->load->library('stripe');
        if($this->config->get('stripe_environment') == 'live') {
            $stripe_secret_key = $this->config->get('stripe_live_secret_key');
        } else {
            $stripe_secret_key = $this->config->get('stripe_test_secret_key');
        }

        if($stripe_secret_key != '' && $stripe_secret_key != null) {
            \Stripe\Stripe::setApiKey($stripe_secret_key);
            //\Stripe\Stripe::setApiVersion("2018-01-23");
            return true;
        }

        return false;
    }

}
