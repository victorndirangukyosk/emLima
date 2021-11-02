<?php

require_once DIR_SYSTEM.'/vendor/konduto/vendor/autoload.php';

require_once DIR_SYSTEM.'/vendor/fcp-php/autoload.php';

require DIR_SYSTEM.'vendor/Facebook/autoload.php';

require_once DIR_APPLICATION.'/controller/api/settings.php';

class ControllerApiCustomerAccount extends Controller
{
    private $error = [];

    public function getUserDetails()
    {
        $json = [];

        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');

        $this->load->language('account/edit');
        $this->load->language('account/account');
        $this->load->model('account/customer');
        $this->load->model('payment/stripe');

        //if( $this->customer->isLogged()) {
        if (true) {
            $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

            if (!empty($customer_info['dob'])) {
                $customer_info['dob'] = date('d/m/Y', strtotime($customer_info['dob']));
            } else {
                $customer_info['dob'] = '01/01/1990';
            }

            $customer_info['user_rewards_available'] = (int) $this->customer->getRewardPoints();

            $stripe_customer = $this->model_payment_stripe->getCustomer($this->customer->getId());

            $customer_info['stripe_details'] = $stripe_customer;

            $json['data'] = $customer_info;
        } else {
            $json['status'] = 10013;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_not_loggedin')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function editUserdetail($args = [])
    {
        $json = [];

        //echo "<pre>";print_r($this->customer->getId());die;
        //echo "<pre>";print_r($args);die;
        //echo "<pre>";print_r("getaddress");die;
        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');

        $this->load->language('account/edit');
        $this->load->language('account/account');
        $this->load->model('account/customer');

        if ($this->validate($args)) {
            //echo "<pre>";print_r($args);die;

            $date = $args['dob'];
            $newUI = $args['newUI'];

            $log = new Log('error.log');
            $log->write('account edit');

            if (isset($date)) {
                $date = DateTime::createFromFormat('d/m/Y', $date);

                $args['dob'] = $date->format('Y-m-d');
            } else {
                $args['dob'] = null;
            }
            if(isset($newUI))
            {
            $this->model_account_customer->editCustomerNew($args);
            }
            else
            {
            $this->model_account_customer->editCustomer($args);
            }

            /*if(isset($args['email']) && isset($args['password']) ) {
                $this->model_account_customer->editPassword($args['email'],$args['password']);
            }*/

            // Add to activity log
            $this->load->model('account/activity');

            $activity_data = [
                'customer_id' => $this->customer->getId(),
                'name' => $this->customer->getFirstName().' '.$this->customer->getLastName(),
            ];

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_success')];

            $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

            if (!empty($customer_info['dob'])) {
                $customer_info['dob'] = date('d/m/Y', strtotime($customer_info['dob']));
            } else {
                $customer_info['dob'] = '01/01/1990';
            }

            $customer_info['user_rewards_available'] = (int) $this->customer->getRewardPoints();

            $json['data'] = $customer_info;

            $this->model_account_activity->addActivity('edit', $activity_data);
        } else {
            $json['status'] = 10014;

            foreach ($this->error as $key => $value) {
                $json['message'][] = ['type' => $key, 'body' => $value];
            }

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validate($args)
    {
        $this->load->language('account/edit');

        $this->load->model('account/customer');

        if (!isset($args['firstname'])) {
            // $this->error['firstname'] = $this->language->get('error_firstname');//required field will be validated from ui
        } else if ((utf8_strlen(trim($args['firstname'])) < 1) || (utf8_strlen(trim($args['firstname'])) > 32)) {
            $this->error['firstname'] = $this->language->get('error_firstname');
        }

        if (!isset($args['email'])) {
            // $this->error['email'] = $this->language->get('error_email');//required field will be validated from ui
        } else {
            if ((utf8_strlen($args['email']) > 96) || !filter_var($args['email'], FILTER_VALIDATE_EMAIL)) {
                $this->error['email'] = $this->language->get('error_email');
            }
        }

        if (!isset($args['email'])) {
            // $this->error['email'] = $this->language->get('error_email');//required field will be validated from ui
        } else {
            if (($this->customer->getEmail() != $args['email']) && $this->model_account_customer->getTotalCustomersByEmail($args['email'])) {
                $this->error['warning'] = $this->language->get('error_exists');
            }
        }


        if (!isset($args['password'])) {
            // $this->error['password'] = $this->language->get('error_password');//required field will be validated from ui
        } else {



        if ((utf8_strlen($this->request->post['password']) >= 1) && (utf8_strlen($this->request->post['password']) < 6) || (utf8_strlen($this->request->post['password']) > 20)) {
            $this->error['password'] = $this->language->get('error_password');
        }

        if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{6,}$/', $this->request->post['password'])) {
            $this->error['password'] = 'Password must contain 6 characters 1 capital(A-Z) 1 numeric(0-9) 1 special(@$!%*#?&)';
        }
        
        }
        /*if ( ( utf8_strlen( $args['password'] ) >= 1 ) && ( utf8_strlen( $args['password'] ) < 4 ) || ( utf8_strlen( $args['password'] ) > 20 ) ) {
            $this->error['password'] = $this->language->get( 'error_password' );
        }

        if ( ( utf8_strlen( $args['confirmpassword'] ) >= 1 ) && ( utf8_strlen( $args['confirmpassword'] ) < 4 ) || ( utf8_strlen( $args['confirmpassword'] ) > 20 ) ) {
            $this->error['confirmpassword'] = $this->language->get( 'error_confirmpassword' );
        }

        if ( $args['confirmpassword'] != $args['password'] ) {
            $this->error['confirmpassword'] = $this->language->get('error_mismatch_password');
        }*/

        // if (!isset($args['dob'])) {
        //     $this->error['dob'] = $this->language->get('error_dob');
        // } else {
        //     if (false == DateTime::createFromFormat('d/m/Y', $args['dob'])) {
        //         $this->error['dob'] = $this->language->get('error_dob');
        //     }
        // }

        // if (empty($args['telephone'])) {
        //     $this->error['telephone'] = $this->language->get('error_telephone');
        // }

        // if (!isset($args['gender'])) {
        //     $this->error['gender'] = $this->language->get('error_gender');
        // }

        //echo "<pre>";print_r($this->error);die;

        return !$this->error;
    }

    public function getUserRewards()
    {
        $json = [];

        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');
        $this->load->model('account/customer');

        //if( $this->customer->isLogged()) {
        if (true) {
            $this->load->language('account/reward');

            $this->load->model('account/reward');

            if (isset($this->request->get['page'])) {
                $page = $this->request->get['page'];
            } else {
                $page = 1;
            }

            $data['rewards'] = [];

            $filter_data = [
                'sort' => 'date_added',
                'order' => 'DESC',
                'start' => ($page - 1) * 10,
                'limit' => 10,
            ];

            $reward_total = $this->model_account_reward->getTotalRewards();

            $results = $this->model_account_reward->getRewards($filter_data);

            //echo $this->customer->getId();
            //echo "<pre>";print_r($results);die;
            foreach ($results as $result) {
                $data['rewards'][] = [
                    'order_id' => $result['order_id'],
                    'plain_points' => $result['points'],
                    'points' => $result['points'],
                    'description' => $result['description'],
                    'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                    'href' => $this->url->link('account/order/info', 'order_id='.$result['order_id'], 'SSL'),
                ];
            }

            $data['results'] = sprintf($this->language->get('text_pagination'), ($reward_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($reward_total - 10)) ? $reward_total : ((($page - 1) * 10) + 10), $reward_total, ceil($reward_total / 10));

            $data['user_rewards_available'] = (int) $this->customer->getRewardPoints();

            $data['reward_total'] = $reward_total;

            $json['data'] = $data;
        } else {
            $json['status'] = 10013;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_not_loggedin')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getUserCash()
    {
        $json = [];

        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');

        $this->load->language('account/edit');
        $this->load->language('account/account');
        $this->load->model('account/customer');

        //if( $this->customer->isLogged()) {
        if (true) {
            $this->load->language('account/credit');

            $this->load->model('account/credit');

            if (isset($this->request->get['page'])) {
                $page = $this->request->get['page'];
            } else {
                $page = 1;
            }

            $data['credits'] = [];

            $filter_data = [
                'sort' => 'date_added',
                'order' => 'DESC',
                'start' => ($page - 1) * 10,
                'limit' => 10,
            ];

            $data['telephone'] = $this->customer->getTelephone();

            $data['user_rewards_available'] = (int) $this->customer->getRewardPoints();

            $credit_total = $this->model_account_credit->getTotalCredits();

            $results = $this->model_account_credit->getCredits($filter_data);

            foreach ($results as $result) {
                $data['credits'][] = [
                    'customer_credit_id' => $result['customer_credit_id'],
                    'amount' => $this->currency->format($result['amount'], $this->config->get('config_currency')),
                    'plain_amount' => $result['amount'],
                    'description' => $result['description'],
                    'date_added' => date($this->language->get('date_format_medium'), strtotime($result['date_added'])),
                ];
            }

            $data['results'] = sprintf($this->language->get('text_pagination'), ($credit_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($credit_total - 10)) ? $credit_total : ((($page - 1) * 10) + 10), $credit_total, ceil($credit_total / 10));

            $data['total'] = $this->currency->format($this->customer->getBalance());

            $data['plain_total'] = $this->customer->getBalance();

            $data['credit_total'] = $credit_total;

            $data['left_symbol_currency'] = $this->currency->getSymbolLeft();
            $data['right_symbol_currency'] = $this->currency->getSymbolRight();

            //echo "<pre>";print_r($data['credits']);die;

            $json['data'] = $data;
        } else {
            $json['status'] = 10013;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_not_loggedin')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addSendphonenumberotp()
    {
        //echo "<pre>";print_r( "addLoginByOtp");die;
        $json = [];

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/login');

        $this->load->language('api/general');

        if (isset($this->request->post['old_phone']) && isset($this->request->post['new_phone'])) {
            $this->load->model('account/api');

            $api_info = $this->model_account_api->phonenumber_send_otp();

            //echo "<pre>";print_r($api_info);die;
            if ($api_info['status']) {
                $data['customer_id'] = $api_info['customer_id'];
                $json['data'] = $data;
                //$json['success'] = $this->language->get('text_success');
                $json['message'][] = ['type' => $api_info['success_message'], 'body' => $api_info['success_message']];
            } else {
                $json['status'] = 10029; //user not found

                $json['message'][] = ['type' => '', 'body' => $api_info['error_warning']];

                http_response_code(400);
            }
        } else {
            $json['status'] = 10010;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('error_login')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addVerifyphonenumberotp()
    {
        //echo "<pre>";print_r( "addLoginVerifyOtp");die;

        $json = [];

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/login');

        $this->load->language('api/general');

        if (isset($this->request->post['otp']) && isset($this->request->post['new_phone']) && isset($this->request->post['customer_id'])) {
            $this->load->model('account/api');
            $this->load->model('account/customer');

            $api_info = $this->model_account_api->phonenumber_verify_otp($this->request->post['otp'], $this->request->post['customer_id']);

            //echo "<pre>";print_r($api_info);die;
            if ($api_info['status']) {
                // update user phonenumber
                $this->model_account_customer->editPhoneNumber($this->request->post['customer_id'], $this->request->post['new_phone']);

                $customer_info = $this->model_account_customer->getCustomer($this->request->post['customer_id']);

                if (!empty($customer_info['dob'])) {
                    $customer_info['dob'] = date('d/m/Y', strtotime($customer_info['dob']));
                } else {
                    $customer_info['dob'] = '01/01/1990';
                }
                //$json['success'] = $this->language->get('text_valid_otp');

                //$json['status'] = true;

                $json['data'] = $customer_info;

                $json['message'][] = ['type' => '', 'body' => $api_info['success_message']];
            } else {
                //$json['error'] = $this->language->get('error_login');
                $json['status'] = 10031; //user not found

                $json['message'][] = ['type' => '', 'body' => $api_info['error_warning']];

                http_response_code(400);
            }
        } else {
            $json['status'] = 10010;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('error_login')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addStripeUser()
    {
        $json = [];

        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        if ($this->customer->isLogged()) {
            $this->createCustomer($this->customer->getId());
        } else {
            $json['status'] = 10013;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_not_loggedin')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    private function initStripe()
    {
        $this->load->library('stripe');
        if ('live' == $this->config->get('stripe_environment')) {
            $stripe_secret_key = $this->config->get('stripe_live_secret_key');
        } else {
            $stripe_secret_key = $this->config->get('stripe_test_secret_key');
        }

        if ('' != $stripe_secret_key && null != $stripe_secret_key) {
            \Stripe\Stripe::setApiKey($stripe_secret_key);

            return true;
        }

        return false;
    }

    public function createCustomer($customerId)
    {
        $log = new Log('error.log');

        $log->write($customerId);
        $log->write('createCustomer');

        $this->load->library('stripe');
        $this->load->model('account/customer');
        $this->load->model('payment/stripe');

        $stripe_environment = $this->config->get('stripe_environment');

        if ($this->initStripe()) {
            $log->write('initStripe');

            $stripe_customer = $this->model_payment_stripe->getCustomer($customerId);

            // If customer is logged, but isn't registered as a customer in Stripe
            if (!$stripe_customer) {
                $customer_info = $this->model_account_customer->getCustomer($customerId);

                if (isset($customer_info['email']) && !empty($customer_info['email'])) {
                    $stripe_customer = \Stripe\Customer::create([
                        'email' => $customer_info['email'],
                        'metadata' => [
                            'customerId' => $customerId,
                        ],
                    ]);

                    $log->write($stripe_customer);
                    $log->write('stripe_customer');

                    $this->model_payment_stripe->addCustomer(
                        $stripe_customer,
                        $customerId,
                        $stripe_environment
                    );
                }
            }
        }

        return true;
    }

    public function addLogout($args)
    {
        $log = new Log('error.log');
        $log->write('account addLogout');
        $log->write($args);
        $json = [];

        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');
        $this->load->language('account/account');
        $this->load->model('account/customer');

        //if( $this->customer->isLogged()) {
        if (isset($args['device_id'])) {
            // $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
            $customer_info = $this->model_account_customer->getCustomerbyFirebaseDeviceID($args['device_id']);
            // echo $customer_info;exit;
            if ($customer_info) {
                $log->write('of');

                $this->load->model('setting/store');

                // Add to activity log
                $this->load->model('account/activity');

                $activity_data = [
                    'customer_id' =>  $customer_info['customer_id'],
                    'name' =>  $customer_info['firstname'] . ' ' . $customer_info['lastname'],
                ];

                $this->model_account_activity->addActivity('logout', $activity_data);
                
                

                $this->model_setting_store->removeDeviceIdAll($args);

                
                

                $this->customer->logout();
            }

            $json['data'] = $customer_info;
        } else {
            $json['status'] = 10013;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_not_loggedin')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }


    public function addSendNewDeviceotp()
    {
        //echo "<pre>";print_r( "addLoginByOtp");die;
        $json = [];

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/login');

        $this->load->language('api/general');

        if (isset($this->request->post['phone']) && isset($this->request->post['email'])) {
            $this->load->model('account/api');
            $api_info = $this->model_account_api->new_device_send_otp();

            //echo "<pre>";print_r($api_info);die;
            if ($api_info['status']) {
                $data['customer_id'] = $api_info['customer_id'];
                $json['data'] = $data;
                //$json['success'] = $this->language->get('text_success');
                $json['message'][] = ['type' => $api_info['success_message'], 'body' => $api_info['success_message']];
            } else {
                $json['status'] = 10029; //user not found

                $json['message'][] = ['type' => '', 'body' => $api_info['error_warning']];

                http_response_code(400);
            }
        } else {
            $json['status'] = 10010;

            $json['message'][] = ['type' => '', 'body' => "Params not passed properly"];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addVerifyNewDeviceotp()
    {
        //echo "<pre>";print_r( "addLoginVerifyOtp");die;

        $json = [];

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/login');

        $this->load->language('api/general');

        if (isset($this->request->post['otp'])  && isset($this->request->post['customer_id'])) {
            $this->load->model('account/api');
            $this->load->model('account/customer');

            $api_info = $this->model_account_api->new_device_verify_otp($this->request->post['otp'], $this->request->post['customer_id']);

            //echo "<pre>";print_r($api_info);die;
            if ($api_info['status']) {
                 
                $customer_info = $this->model_account_customer->getCustomer($this->request->post['customer_id']);

                if (!empty($customer_info['dob'])) {
                    $customer_info['dob'] = date('d/m/Y', strtotime($customer_info['dob']));
                } else {
                    $customer_info['dob'] = '01/01/1990';
                }
                //$json['success'] = $this->language->get('text_valid_otp');

                //$json['status'] = true;

                $json['data'] = $customer_info;

                $json['message'][] = ['type' => '', 'body' => $api_info['success_message']];
            } else {
                //$json['error'] = $this->language->get('error_login');
                $json['status'] = 10031; //user not found

                $json['message'][] = ['type' => '', 'body' => $api_info['error_warning']];

                http_response_code(400);
            }
        } else {
            $json['status'] = 10010;

            $json['message'][] = ['type' => '', 'body' => "Params not sent properly"];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }


    public function getWalletTotal() {
        $total = 0;
        // if (!$this->customer->isLogged()) {
        //     $this->session->data['redirect'] = $this->url->link('account/credit', '', 'SSL');

        //     $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        // } 
        $this->load->model('account/credit');         

        $result = $this->model_account_credit->getTotalAmount();       
        $total=$this->currency->format($result, $this->config->get('config_currency'));
       
        // echo "<pre>";print_r($total);die; 
        $json = [];

        $json['status'] = 200;
        $json['data'] =  $total;
        $json['message'] = 'success';
       
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }


    public function getWallet()
    {
         
        $this->load->language('account/credit');  
        $this->load->model('account/credit');

        
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['credits'] = [];

        $filter_data = [
            'sort' => 'date_added',
            'order' => 'DESC',
            'start' => ($page - 1) * 10,
            'limit' => 10,
        ];

        // $data['telephone'] = $this->customer->getTelephone();

        $credit_total = $this->model_account_credit->getTotalCredits();

        $results = $this->model_account_credit->getCredits($filter_data);

        foreach ($results as $result) {
            $transaction_ID="";
            if(isset($result['transaction_id']) && $result['transaction_id']!="" )
            {
                $transaction_ID='#Transaction ID '.$result['transaction_id'];
            }
            $data['credits'][] = [
                'amount' => $this->currency->format($result['amount'], $this->config->get('config_currency')),
                'plain_amount' => $result['amount'],
                'description' => $result['description'].' ' .$transaction_ID,
                'date_added' => date($this->language->get('date_format_medium'), strtotime($result['date_added'])),
            ];
        }

        

        
        $data['total'] = $this->currency->format($this->customer->getBalance());
       
        $data['records_count'] = $credit_total;
        $data['current_page'] = $page;
        $data['pagination_results'] = sprintf($this->language->get('text_pagination'), ($credit_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($credit_total - 10)) ? $credit_total : ((($page - 1) * 10) + 10), $credit_total, ceil($credit_total / 10));
        
 

        // echo "<pre>";print_r($data['credits']);die;
        $json = [];

        $json['status'] = 200;
        $json['data'] =  $data;
        $json['message'] = 'success';
       
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
        
    }

}
