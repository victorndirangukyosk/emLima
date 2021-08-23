<?php

require_once DIR_SYSTEM.'vendor/firebase/php-jwt/vendor/autoload.php';
use Firebase\JWT\JWT;

class ControllerApiCustomerSignup extends Controller
{
    private $error = [];

    public function addSignup($args = [])
    {
        $json = [];

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');
        $this->load->model('api/approval');

        $log = new Log('error.log');

        $this->load->model('account/customer');
        //echo "<pre>";print_r($args);die;
        if (!$this->validate()) {
            $json['status'] = 10014;

            foreach ($this->error as $key => $value) {
                $json['message'][] = ['type' => '', 'body' => $value];
            }
            http_response_code(400);
        } else {
            $date = $this->request->post['dob'];

            if (isset($date)) {
                $date = DateTime::createFromFormat('d/m/Y', $date);

                $this->request->post['dob'] = $date->format('Y-m-d');
            } else {
                $this->request->post['dob'] = null;
            }
            $accountmanagerid = NULL;
            $accountmanagerid =$this->request->post['accountmanagerid'];
            $log->write('accountmanagerid from API');
            $log->write($accountmanagerid);
            $customer_id = $this->model_account_customer->addCustomer($this->request->post);

            //$this->createCustomer($customer_id);

            // Clear any previous login attempts for unregistered accounts.
            $this->model_account_customer->deleteLoginAttempts($this->request->post['email']);

            $logged_in = $this->customer->login($this->request->post['email'], $this->request->post['password']);

            unset($this->session->data['guest']);

            // Add to activity log
            $this->load->model('account/activity');

            $activity_data = [
                'customer_id' => $customer_id,
                'name' => $this->request->post['firstname'].' '.$this->request->post['lastname'],
            ];

            $this->model_account_activity->addActivity('register', $activity_data);

            /* If not able to login*/
            $data['status'] = true;

            if (!$logged_in) {
                $data['status'] = false;
            }
            $data['text_new_signup_reward'] = $this->language->get('text_new_signup_reward');
            $data['text_new_signup_credit'] = $this->language->get('text_new_signup_credit');

            //$data['message'] = $this->language->get( 'verify_mail_sent' );

            $json['message'][] = ['type' => $this->language->get('text_success_registered'), 'body' => $this->language->get('verify_mail_sent')];

            if (isset($referee_user_id)) {
                $config_reward_enabled = $this->config->get('config_reward_enabled');

                $config_credit_enabled = $this->config->get('config_credit_enabled');

                $config_refer_type = $this->config->get('config_refer_type');

                $config_refered_points = $this->config->get('config_refered_points');
                $config_referee_points = $this->config->get('config_referee_points');

                /*
                $log->write($customer_id);
                $log->write($config_refer_type);
                $log->write($referee_user_id. " referee_user_id");

                $log->write($config_referee_points);
                $log->write($config_refered_points);*/
                if ('reward' == $config_refer_type) {
                    $log->write($config_reward_enabled);

                    if ($config_reward_enabled && $config_refered_points && $config_referee_points) {
                        $log->write('if');

                        //referred points below
                        $this->model_account_activity->addCustomerReward($customer_id, $config_refered_points, $data['referral_description']);

                        //referee points below
                        $this->model_account_activity->addCustomerReward($referee_user_id, $config_referee_points, $data['referral_description']);
                    }
                } elseif ('credit' == $config_refer_type) {
                    $log->write('credit if');

                    if ($config_credit_enabled && $config_refered_points && $config_referee_points) {
                        //referred points below
                        $this->model_account_activity->addCredit($customer_id, $data['referral_description'], $config_refered_points);

                        //referee points below
                        $this->model_account_activity->addCredit($referee_user_id, $data['referral_description'], $config_referee_points);
                    }
                }
            } else {
                // add signup wallet for new registration. if is enabled

                //below was used for signup reward

                $config_reward_enabled = $this->config->get('config_reward_enabled');

                if ($config_reward_enabled) {
                    $log->write('if');

                    $points = $this->config->get('config_reward_onsignup');

                    if ($points) {
                        $this->model_account_activity->addCustomerReward($customer_id, $points, $data['text_new_signup_reward']);
                    }
                }

                //below was used for signup credit

                $config_credit_enabled = $this->config->get('config_credit_enabled');

                if ($config_credit_enabled) {
                    $log->write('credit enabled if');
                    $points = $this->config->get('config_credit_onsignup');

                    if ($points) {
                        $this->model_account_activity->addCredit($customer_id, $data['text_new_signup_credit'], $points);
                    }
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function validate()
    {
        $this->load->model('account/customer');

        $this->load->language('account/register');

        if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
            $this->error['firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
            $this->error['warning'] = $this->language->get('error_exists');
        }

        if ($this->model_account_customer->getTotalCustomersByPhone($this->request->post['telephone'])) {
            $this->error['telephone_exists'] = $this->language->get('error_telephone_exists');
        }

        if (empty($this->request->post['telephone'])) {
            $this->error['telephone'] = $this->language->get('error_telephone');
        }

        if (!isset($this->request->post['gender'])) {
            $this->error['gender'] = $this->language->get('error_gender');
        }

        if (!isset($this->request->post['dob']) || false == DateTime::createFromFormat('d/m/Y', $this->request->post['dob'])) {
            $this->error['dob'] = $this->language->get('error_dob');
        }

        if (!isset($this->request->post['password']) || (utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
            $this->error['password'] = $this->language->get('error_password');
        }

        return !$this->error;
    }

    public function addSignupByOtp()
    {
        //echo "<pre>";print_r( "addLoginByOtp");die;
        $json = [];

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/login');

        $this->load->language('api/general');

        $this->load->model('account/api');

        $api_info = $this->model_account_api->register_send_otp();

        //echo "<pre>";print_r($api_info);die;
        if ($api_info['status']) {
            $json['message'][] = ['type' => '', 'body' => $api_info['success_message']];
        } else {
            $json['status'] = 10032; //form invalid

            $json['message'] = $api_info['errors'];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }


    public function addSignupByOtpNew()
    {
        //echo "<pre>";print_r( "addLoginByOtp");die;
        $json = [];

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/login');

        $this->load->language('api/general');

        $this->load->model('account/api');

        // $api_info = $this->model_account_api->register_send_otp();
        $api_info = $this->model_account_api->register_user();

        //echo "<pre>";print_r($api_info);die;
        if ($api_info['status']) {
            $json['message'][] = ['type' => '', 'body' => $api_info['success_message']];
        } else {
            $json['status'] = 10032; //form invalid

            $json['message'] = $api_info['errors'];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addSignupVerifyOtp()
    {
        //echo "<pre>";print_r( "addLoginByOtp");die;
        $json = [];

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/login');

        $this->load->language('api/general');

        $this->load->model('account/api');

        $api_info = $this->model_account_api->register_verify_otp();

        //echo "<pre>";print_r($api_info);die;
        if ($api_info['status']) {
            $customer_id = $api_info['customer_id'];
            $tokenId = base64_encode(mcrypt_create_iv(32));
            $issuedAt = time();
            $notBefore = $issuedAt;  //Adding 10 seconds
            $expire = $notBefore + 604800; // Adding 60 seconds
            $serverName = 'serverName'; /// set your domain name

            /*
             * Create the token as an array
             */
            $data = [
                'iat' => $issuedAt,         // Issued at: time when the token was generated
                'jti' => $tokenId,          // Json Token Id: an unique identifier for the token
                'iss' => $serverName,       // Issuer
                'nbf' => $notBefore,        // Not before
                'exp' => $expire,           // Expire
                'data' => [                  // Data related to the logged user you can set your required data
                            'id' => $customer_id, // id from the users table
                             'name' => $customer_id, //  name
                          ],
            ];

            $secretKey = base64_decode(SECRET_KEY);
            /// Here we will transform this array into JWT:
            $jwt = JWT::encode(
                $data, //Data to be encoded in the JWT
                $secretKey, // The signing key
                 ALGORITHM
            );

            $unencodedArray = ['jwt' => $jwt];

            $this->session->data['customer_id'] = $customer_id;

            //echo  "{'status' : 'success','resp':".json_encode($unencodedArray)."}"
            $this->load->model('account/customer');

            $customer_info = $this->model_account_customer->getCustomer($customer_id);

            if (!empty($customer_info['dob'])) {
                $customer_info['dob'] = date('d/m/Y', strtotime($customer_info['dob']));
            } else {
                $customer_info['dob'] = '01/01/1990';
            }
            //$json['success'] = $this->language->get('text_valid_otp');
            $json['token'] = $jwt; //json_encode($unencodedArray);

            $json['data'] = $customer_info;

            $json['message'][] = ['type' => '', 'body' => $api_info['success_message']];
        } else {
            $json['status'] = 10032; //form invalid

            $json['message'] = $api_info['errors'];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }


    public function addSignupVerifyOtpNew()
    {
        //echo "<pre>";print_r( "addLoginByOtp");die;
        $json = [];

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/login');

        $this->load->language('api/general');

        $this->load->model('account/api');

        // $api_info = $this->model_account_api->register_verify_otp();
        $api_info = $this->model_account_api->register_verify_user_otp();

        // echo "<pre>";print_r($api_info);die;
        if ($api_info['status']) {
            $customer_id = $api_info['customer_id'];
            $tokenId = base64_encode(mcrypt_create_iv(32));
            $issuedAt = time();
            $notBefore = $issuedAt;  //Adding 10 seconds
            $expire = $notBefore + 604800; // Adding 60 seconds
            $serverName = 'serverName'; /// set your domain name

            /*
             * Create the token as an array
             */
            $data = [
                'iat' => $issuedAt,         // Issued at: time when the token was generated
                'jti' => $tokenId,          // Json Token Id: an unique identifier for the token
                'iss' => $serverName,       // Issuer
                'nbf' => $notBefore,        // Not before
                'exp' => $expire,           // Expire
                'data' => [                  // Data related to the logged user you can set your required data
                            'id' => $customer_id, // id from the users table
                             'name' => $customer_id, //  name
                          ],
            ];

            $secretKey = base64_decode(SECRET_KEY);
            /// Here we will transform this array into JWT:
            $jwt = JWT::encode(
                $data, //Data to be encoded in the JWT
                $secretKey, // The signing key
                 ALGORITHM
            );

            $unencodedArray = ['jwt' => $jwt];

            $this->session->data['customer_id'] = $customer_id;

            //echo  "{'status' : 'success','resp':".json_encode($unencodedArray)."}"
            $this->load->model('account/customer');

            $customer_info = $this->model_account_customer->getCustomer($customer_id);

            if (!empty($customer_info['dob'])) {
                $customer_info['dob'] = date('d/m/Y', strtotime($customer_info['dob']));
            } else {
                $customer_info['dob'] = '01/01/1990';
            }
            //$json['success'] = $this->language->get('text_valid_otp');
            $json['token'] = $jwt; //json_encode($unencodedArray);

            $json['data'] = $customer_info;

            $json['message'][] = ['type' => '', 'body' => $api_info['success_message']];
        } else {
            $json['status'] = 401; //form invalid

            $json['message'] = $api_info['errors'];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addResendSignupOtp()
    {
        //echo "<pre>";print_r( "addLoginByOtp");die;
        $json = [];

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];
        $json['error'] = "";

        $this->load->language('api/login');

        $this->load->language('api/general');

        $this->load->model('account/api');

        // $api_info = $this->model_account_api->register_send_otp();
        $api_info = $this->model_account_api->resend_register_otp();

        //   echo "<pre>";print_r($api_info);die;
        if ($api_info['status']) {
            $json['message'][] = ['type' => '', 'body' => $api_info['success_message']];
        } else {
            $json['status'] = 400; //form invalid

            $json['error'] =  $api_info['warning'];


            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }


    

    public function validateOtpSignup()
    {
        if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
            $this->error['firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
            $this->error['warning'] = $this->language->get('error_exists');
        }

        if (false !== strpos($this->request->post['telephone'], '#') || empty($this->request->post['telephone'])) {
            $this->error['telephone'] = $this->language->get('error_telephone');
        }

        $this->request->post['telephone'] = preg_replace('/[^0-9]/', '', $this->request->post['telephone']);

        //echo "<pre>";print_r($this->request->post);die;

        if ($this->model_account_customer->getTotalCustomersByPhone($this->request->post['telephone'])) {
            $this->error['telephone_exists'] = $this->language->get('error_telephone_exists');
        }

        //echo "<pre>";print_r($this->error);die;
        return !$this->error;
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
}
