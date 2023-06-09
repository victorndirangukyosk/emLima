<?php

require_once(DIR_SYSTEM . 'vendor/firebase/php-jwt/vendor/autoload.php');
use Firebase\JWT\JWT;
class ControllerApiCustomerLogin extends Controller {


    public function index() {
        $this->load->language('api/login');



        //echo "<pre>";print_r($this->request->post);die;
        // Delete old login so not to cause any issues if there is an error
        unset($this->session->data['customer_id']);

        $keys = array(
            'username',
            'password'
        );

        foreach ($keys as $key) {
            if (!isset($this->request->post[$key])) {
                $this->request->post[$key] = '';
            }
        }

        $json = array();

        $this->load->model('account/api');

        if(is_numeric($this->request->post['username'])) {
            //login by phone number
            $api_info = $this->model_account_api->customerLoginByPhone($this->request->post['username'], $this->request->post['password']);
        } else {
            $api_info = $this->model_account_api->customer_login($this->request->post['username'], $this->request->post['password']);
        }
        

        //echo "<pre>";print_r($api_info);die;
        if ($api_info['status']) {
            
            /*if(!isset($this->session->data['customer_id'])) {
                $this->session->data['customer_id'] = $api_info['customer_id'];
            }*/
            $tokenId    = base64_encode(mcrypt_create_iv(32));
            $issuedAt   = time();
            $notBefore  = $issuedAt;  //Adding 10 seconds
            $expire     = $notBefore + 604800; // Adding 60 seconds
            $serverName = 'serverName'; /// set your domain name 

            
            /*
             * Create the token as an array
             */
            $data = [
                'iat'  => $issuedAt,         // Issued at: time when the token was generated
                'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
                'iss'  => $serverName,       // Issuer
                'nbf'  => $notBefore,        // Not before
                'exp'  => $expire,           // Expire
                'data' => [                  // Data related to the logged user you can set your required data
                            'id'   => $api_info['customer_id'], // id from the users table
                             'name' => $api_info['customer_email'], //  name
                          ]
            ];

            $secretKey = base64_decode(SECRET_KEY);
            /// Here we will transform this array into JWT:
            $jwt = JWT::encode(
                $data, //Data to be encoded in the JWT
                $secretKey, // The signing key
                 ALGORITHM 
            );

            $unencodedArray = ['jwt' => $jwt];

            
            $this->session->data['customer_id'] = $api_info['customer_id'];

            //echo  "{'status' : 'success','resp':".json_encode($unencodedArray)."}"
            $this->load->model('account/customer');

            $customer_info = $this->model_account_customer->getCustomer($api_info['customer_id']);
            
            
            if (!empty($customer_info['dob'])) {
                $customer_info['dob'] = date("d/m/Y", strtotime($customer_info['dob']));
            } else {
                $customer_info['dob'] = '01/01/1990';
            }
            $json['success'] = $this->language->get('text_success');
            $json['token'] = $jwt;//json_encode($unencodedArray);
            $json['status'] = true;
            
            $json['data'] = $customer_info;

            

        } else {

            if($api_info['not_verified']) {
                $json['error'] = $this->language->get('error_not_approved');
            } else {
                $json['error'] = $this->language->get('error_login');
            }
            
            $json['status'] = false;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addLogin() {


        //echo "<pre>";print_r( $this->request->post);die;
        $json = array();

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/login');

        // Delete old login so not to cause any issues if there is an error
        unset($this->session->data['customer_id']);

        $keys = array(
            'username',
            'password'
        );


        if(isset($this->request->post['username'] ) && isset($this->request->post['password'] )) {
            $this->load->model('account/api');

            $api_info = $this->model_account_api->customer_login($this->request->post['username'], $this->request->post['password']);

            if ($api_info['status']) {
                
                if(!isset($this->session->data['customer_id'])) {
                    $this->session->data['customer_id'] = $api_info['customer_id'];
                    //$json['cookie'] = $this->session->getId();
                }

                //$json['success'] = $this->language->get('text_success');
                $json['message'][] = ['type' =>  '' , 'body' =>  $this->language->get('text_success') ];

            } else {
                //$json['error'] = $this->language->get('error_login');
                $json['message'][] = ['type' =>  '' , 'body' =>  $this->language->get('error_login') ];
            }

        } else {

            $json['status'] = 10010;

            $json['message'][] = ['type' =>  '' , 'body' =>  $this->language->get('error_login') ];

            http_response_code(400);

        }

        

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addLoginByOtp() {


        //echo "<pre>";print_r( "addLoginByOtp");die;
        $json = array();

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/login');

        $this->load->language('api/general');

        if(isset($this->request->post['phone'] )) {
            $this->load->model('account/api');

            $api_info = $this->model_account_api->login_send_otp($this->request->post['phone']);

            //echo "<pre>";print_r($api_info);die;
            if ($api_info['status']) {
                
                $data['customer_id'] = $api_info['customer_id'];
                $json['data'] = $data;
                //$json['success'] = $this->language->get('text_success');
                $json['message'][] = ['type' =>  '' , 'body' =>  $api_info['success_message'] ];

            } else {
                
                $json['status'] = 10029;//user not found

                $json['message'][] = ['type' =>  '' , 'body' =>  $api_info['error_warning'] ];

                http_response_code(400);
            }

            

        } else {

            $json['status'] = 10010;

            $json['message'][] = ['type' =>  '' , 'body' =>  $this->language->get('error_login') ];

            http_response_code(400);

        }

        

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addLoginVerifyOtp() {

        //echo "<pre>";print_r( "addLoginVerifyOtp");die;

        $json = array();

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/login');

        $this->load->language('api/general');

        if(isset($this->request->post['otp']) && isset($this->request->post['customer_id']) ) {
            $this->load->model('account/api');

            $api_info = $this->model_account_api->login_verify_otp($this->request->post['otp'],$this->request->post['customer_id']);

            //echo "<pre>";print_r($api_info);die;
            if ($api_info['status']) {
                    
                $tokenId    = base64_encode(mcrypt_create_iv(32));
                $issuedAt   = time();
                $notBefore  = $issuedAt;  //Adding 10 seconds
                $expire     = $notBefore + 604800; // Adding 60 seconds
                //$expire     = $notBefore + 180; // Adding 60 seconds
                $serverName = 'serverName'; /// set your domain name 

                
                /*
                 * Create the token as an array
                 */
                $data = [
                    'iat'  => $issuedAt,         // Issued at: time when the token was generated
                    'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
                    'iss'  => $serverName,       // Issuer
                    'nbf'  => $notBefore,        // Not before
                    'exp'  => $expire,           // Expire
                    'data' => [                  // Data related to the logged user you can set your required data
                                'id'   => $this->request->post['customer_id'], // id from the users table
                                 'name' => $this->request->post['customer_id'], //  name
                              ]
                ];

                $secretKey = base64_decode(SECRET_KEY);
                /// Here we will transform this array into JWT:
                $jwt = JWT::encode(
                    $data, //Data to be encoded in the JWT
                    $secretKey, // The signing key
                     ALGORITHM 
                );

                $unencodedArray = ['jwt' => $jwt];

                
                $this->session->data['customer_id'] = $this->request->post['customer_id'];

                //echo  "{'status' : 'success','resp':".json_encode($unencodedArray)."}"
                $this->load->model('account/customer');

                $customer_info = $this->model_account_customer->getCustomer($this->request->post['customer_id']);
                
                
                if (!empty($customer_info['dob'])) {
                    $customer_info['dob'] = date("d/m/Y", strtotime($customer_info['dob']));
                } else {
                    $customer_info['dob'] = '01/01/1990';
                }

                $customer_info['user_rewards_available'] = (int)$this->customer->getRewardPoints();


                $referUnique  = 'refer='.strtolower(str_replace(' ', '', $this->customer->getFirstName())).strtolower(str_replace(' ', '', $this->customer->getLastName())).'!@'.strtolower($this->customer->getId());
            
                if ($this->request->server['HTTPS']) {
                    $refer_link  = $this->config->get('config_ssl');
                } else {
                    $refer_link  = $this->config->get('config_ssl');
                }

                $refer_link = rtrim($refer_link,"/");
                $refer_link .= '?'.$referUnique;

                $customer_info['refer_link'] = $referUnique;



                
                //$json['success'] = $this->language->get('text_valid_otp');
                $json['token'] = $jwt;//json_encode($unencodedArray);
                $json['status'] = true;
                
                $json['data'] = $customer_info;


                $json['message'][] = ['type' =>  '' , 'body' =>  $api_info['success_message'] ];

            } else {
                //$json['error'] = $this->language->get('error_login');
                $json['status'] = 10031;//user not found

                $json['message'][] = ['type' =>  '' , 'body' =>  $api_info['error_warning'] ];

                http_response_code(400);
            }

        } else {

            $json['status'] = 10010;

            $json['message'][] = ['type' =>  '' , 'body' =>  $this->language->get('error_login') ];

            http_response_code(400);

        }

        

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addNewAccessToken() {

        //echo "<pre>";print_r( "addNewAccessToken");die;
        //echo "<pre>";print_r($this->customer->getId());die;
        $this->request->post['customer_id'] = $this->customer->getId();
        $json = array();

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/login');

        $this->load->language('api/general');

        if (true) {
                
            $tokenId    = base64_encode(mcrypt_create_iv(32));
            $issuedAt   = time();
            $notBefore  = $issuedAt;  //Adding 10 seconds
            $expire     = $notBefore + 604800; // Adding 60 seconds
            //$expire     = $notBefore + 180; // Adding 60 seconds
            $serverName = 'serverName'; /// set your domain name 

            
            /*
             * Create the token as an array
             */
            $data = [
                'iat'  => $issuedAt,         // Issued at: time when the token was generated
                'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
                'iss'  => $serverName,       // Issuer
                'nbf'  => $notBefore,        // Not before
                'exp'  => $expire,           // Expire
                'data' => [                  // Data related to the logged user you can set your required data
                            'id'   => $this->request->post['customer_id'], // id from the users table
                             'name' => $this->request->post['customer_id'], //  name
                          ]
            ];

            $secretKey = base64_decode(SECRET_KEY);
            /// Here we will transform this array into JWT:
            $jwt = JWT::encode(
                $data, //Data to be encoded in the JWT
                $secretKey, // The signing key
                 ALGORITHM 
            );

            $unencodedArray = ['jwt' => $jwt];

            
            $this->session->data['customer_id'] = $this->request->post['customer_id'];

            //echo  "{'status' : 'success','resp':".json_encode($unencodedArray)."}"
            $this->load->model('account/customer');

            $customer_info = $this->model_account_customer->getCustomer($this->request->post['customer_id']);
            
            
            if (!empty($customer_info['dob'])) {
                $customer_info['dob'] = date("d/m/Y", strtotime($customer_info['dob']));
            } else {
                $customer_info['dob'] = '01/01/1990';
            }

            $customer_info['user_rewards_available'] = (int)$this->customer->getRewardPoints();


            $referUnique  = 'refer='.strtolower(str_replace(' ', '', $this->customer->getFirstName())).strtolower(str_replace(' ', '', $this->customer->getLastName())).'!@'.strtolower($this->customer->getId());
        
            if ($this->request->server['HTTPS']) {
                $refer_link  = $this->config->get('config_ssl');
            } else {
                $refer_link  = $this->config->get('config_ssl');
            }

            $refer_link = rtrim($refer_link,"/");
            $refer_link .= '?'.$referUnique;

            $customer_info['refer_link'] = $referUnique;



            
            //$json['success'] = $this->language->get('text_valid_otp');
            $json['token'] = $jwt;//json_encode($unencodedArray);
            $json['status'] = true;
            
            $json['data'] = $customer_info;


        } else {
            //$json['error'] = $this->language->get('error_login');
            $json['status'] = 10031;//user not found

            http_response_code(400);
        }

      
        

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }


}
