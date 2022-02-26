<?php

require_once DIR_SYSTEM . 'vendor/firebase/php-jwt/vendor/autoload.php';

use Firebase\JWT\JWT;

class ControllerApiCustomerLogin extends Controller {

    public function index() {
        $this->load->language('api/login');

        //echo "<pre>";print_r($this->request->post);die;
        // Delete old login so not to cause any issues if there is an error
        unset($this->session->data['customer_id']);
        unset($this->session->data['customer_category']);

        $keys = [
            'username',
            'password',
        ];

        foreach ($keys as $key) {
            if (!isset($this->request->post[$key])) {
                $this->request->post[$key] = '';
            }
        }

        $json = [];

        $this->load->model('account/api');

        if (is_numeric($this->request->post['username'])) {
            //login by phone number
            $api_info = $this->model_account_api->customerLoginByPhone($this->request->post['username'], $this->request->post['password']);
        } else {
            $api_info = $this->model_account_api->customer_login($this->request->post['username'], $this->request->post['password']);
        }

        //echo "<pre>";print_r($api_info);die;
        if ($api_info['status']) {
            /* if(!isset($this->session->data['customer_id'])) {
              $this->session->data['customer_id'] = $api_info['customer_id'];
              } */
            $tokenId = base64_encode(mcrypt_create_iv(32));
            $issuedAt = time();
            $notBefore = $issuedAt;  //Adding 10 seconds
            $expire = $notBefore + 15780000; // Adding 60 seconds
            $serverName = 'serverName'; /// set your domain name

            /*
             * Create the token as an array
             */
            $data = [
                'iat' => $issuedAt, // Issued at: time when the token was generated
                'jti' => $tokenId, // Json Token Id: an unique identifier for the token
                'iss' => $serverName, // Issuer
                'nbf' => $notBefore, // Not before
                'exp' => $expire, // Expire
                'data' => [// Data related to the logged user you can set your required data
                    'id' => $api_info['customer_id'], // id from the users table
                    'name' => $api_info['customer_email'], //  name
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

            $this->session->data['customer_id'] = $api_info['customer_id'];
            // echo "<pre>";print_r($api_info);die; 

            if ($api_info['parent'] != NULL && $api_info['parent'] > 0) {
                $customer_details = $this->db->query('SELECT customer_category FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . $api_info['parent'] . "' AND status = '1'");
            } else {
                $customer_details = $this->db->query('SELECT customer_category FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . $api_info['customer_id'] . "' AND status = '1'");
            }
            $this->session->data['customer_category'] = isset($customer_details->row['customer_category']) ? $customer_details->row['customer_category'] : null;
            // echo "<pre>";print_r($customer_details);die; 
            $this->session->data['order_approval_access'] = $api_info['order_approval_access'];
            $this->session->data['order_approval_access_role'] = $api_info['order_approval_access_role'];
            $this->session->data['parent'] = $api_info['parent'];

            //echo  "{'status' : 'success','resp':".json_encode($unencodedArray)."}"
            $this->load->model('account/customer');
            $this->model_account_customer->cacheProductPrices(75);
            $customer_info = $this->model_account_customer->getCustomer($api_info['customer_id']);
            $customer_info['pezesha_customer_id'] = $this->customer->getCustomerPezeshaId();
            $customer_info['pezesha_customer_uuid'] = $this->customer->getCustomerPezeshauuId();
            $customer_info['pezesha_identifier'] = $this->customer->getCustomerPezeshaIdentifier();
            $customer_info['devices'] = $this->model_account_customer->getCustomerDevices($api_info['customer_id']);

            // Add to activity log
            $this->load->model('account/activity');

            $activity_data = [
                'customer_id' => $customer_info['customer_id'],
                'name' => $customer_info['firstname'] . ' ' . $customer_info['lastname'],
            ];

            $this->model_account_activity->addActivity('login', $activity_data);

            #region login history
            $logindata['customer_id'] = $api_info['customer_id'];
            if (isset($this->request->post['login_latitude'])) {
                $logindata['login_latitude'] = $this->request->post['login_latitude'];
            } else {
                $logindata['login_latitude'] = 0;
            }

            if (isset($this->request->post['login_longitude'])) {
                $logindata['login_longitude'] = $this->request->post['login_longitude'];
            } else {
                $logindata['login_longitude'] = 0;
            }

            if (isset($this->request->post['login_mode'])) {
                $logindata['login_mode'] = $this->request->post['login_mode'];
            } else {
                $logindata['login_mode'] = '';
            }
            $this->model_account_customer->addLoginHistory($logindata);
            #endregion login history
            if (!empty($customer_info['dob'])) {
                $customer_info['dob'] = date('d/m/Y', strtotime($customer_info['dob']));
            } else {
                $customer_info['dob'] = '01/01/1990';
            }
            $json['success'] = $this->language->get('text_success');
            $json['token'] = $jwt; //json_encode($unencodedArray);
            $json['cookie'] = $this->session->getId();
            $log = new Log('error.log');
            $log->write('CUSTOMER_CATEGORY');
            $log->write($this->customer->getCustomerCategory());
            $log->write('CUSTOMER_CATEGORY');
            $json['customer_category'] = $this->customer->getCustomerCategory();
            $json['status'] = true;

            $json['data'] = $customer_info;
        } else {
            if ($api_info['not_verified']) {
                // $json['error'] = $this->language->get('error_not_approved');
                $json['error'] = $this->language->get('error_approved');
            } else {
                $json['error'] = $this->language->get('error_login');
            }

            $json['status'] = false;
        }
        $this->load->model('account/customer');
        $this->model_account_customer->getDBCart();
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addLogin() {
        //echo "<pre>";print_r( $this->request->post);die;
        $json = [];

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/login');

        // Delete old login so not to cause any issues if there is an error
        unset($this->session->data['customer_id']);
        unset($this->session->data['customer_category']);

        $keys = [
            'username',
            'password',
        ];

        if (isset($this->request->post['username']) && isset($this->request->post['password'])) {
            $this->load->model('account/api');

            $api_info = $this->model_account_api->customer_login($this->request->post['username'], $this->request->post['password']);

            if ($api_info['status']) {
                if (!isset($this->session->data['customer_id'])) {
                    $this->session->data['customer_id'] = $api_info['customer_id'];
                    $this->session->data['order_approval_access'] = $api_info['order_approval_access'];
                    $this->session->data['order_approval_access_role'] = $api_info['order_approval_access_role'];

                    //$json['cookie'] = $this->session->getId();
                }

                //$json['success'] = $this->language->get('text_success');
                $json['message'][] = ['type' => '', 'body' => $this->language->get('text_success')];
            } else {
                //$json['error'] = $this->language->get('error_login');
                $json['message'][] = ['type' => '', 'body' => $this->language->get('error_login')];
            }
        } else {
            $json['status'] = 10010;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('error_login')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addLoginByOtp() {
        //echo "<pre>";print_r( "addLoginByOtp");die;
        $json = [];

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/login');

        $this->load->language('api/general');

        if (isset($this->request->post['phone'])) {
            $this->load->model('account/api');

            $api_info = $this->model_account_api->login_send_otp($this->request->post['phone']);

            //echo "<pre>";print_r($api_info);die;
            if ($api_info['status']) {
                $data['customer_id'] = $api_info['customer_id'];
                $json['data'] = $data;
                //$json['success'] = $this->language->get('text_success');
                $json['message'][] = ['type' => '', 'body' => $api_info['success_message']];
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

    public function addLoginVerifyOtp() {
        //echo "<pre>";print_r( "addLoginVerifyOtp");die;

        $json = [];

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/login');

        $this->load->language('api/general');

        if (isset($this->request->post['otp']) && isset($this->request->post['customer_id'])) {
            $this->load->model('account/api');

            $api_info = $this->model_account_api->login_verify_otp($this->request->post['otp'], $this->request->post['customer_id']);

            //echo "<pre>";print_r($api_info);die;
            if ($api_info['status']) {
                $tokenId = base64_encode(mcrypt_create_iv(32));
                $issuedAt = time();
                $notBefore = $issuedAt;  //Adding 10 seconds
                $expire = $notBefore + 604800; // Adding 60 seconds
                //$expire     = $notBefore + 180; // Adding 60 seconds
                $serverName = 'serverName'; /// set your domain name

                /*
                 * Create the token as an array
                 */
                $data = [
                    'iat' => $issuedAt, // Issued at: time when the token was generated
                    'jti' => $tokenId, // Json Token Id: an unique identifier for the token
                    'iss' => $serverName, // Issuer
                    'nbf' => $notBefore, // Not before
                    'exp' => $expire, // Expire
                    'data' => [// Data related to the logged user you can set your required data
                        'id' => $this->request->post['customer_id'], // id from the users table
                        'name' => $this->request->post['customer_id'], //  name
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

                $this->session->data['customer_id'] = $this->request->post['customer_id'];

                //echo  "{'status' : 'success','resp':".json_encode($unencodedArray)."}"
                $this->load->model('account/customer');

                $customer_info = $this->model_account_customer->getCustomer($this->request->post['customer_id']);

                if (!empty($customer_info['dob'])) {
                    $customer_info['dob'] = date('d/m/Y', strtotime($customer_info['dob']));
                } else {
                    $customer_info['dob'] = '01/01/1990';
                }

                $customer_info['user_rewards_available'] = (int) $this->customer->getRewardPoints();

                $referUnique = 'refer=' . strtolower(str_replace(' ', '', $this->customer->getFirstName())) . strtolower(str_replace(' ', '', $this->customer->getLastName())) . '!@' . strtolower($this->customer->getId());

                if ($this->request->server['HTTPS']) {
                    $refer_link = $this->config->get('config_ssl');
                } else {
                    $refer_link = $this->config->get('config_ssl');
                }

                $refer_link = rtrim($refer_link, '/');
                $refer_link .= '?' . $referUnique;

                $customer_info['refer_link'] = $referUnique;

                //$json['success'] = $this->language->get('text_valid_otp');
                $json['token'] = $jwt; //json_encode($unencodedArray);
                $json['status'] = true;

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

    public function addNewAccessToken() {
        //echo "<pre>";print_r( "addNewAccessToken");die;
        //echo "<pre>";print_r($this->customer->getId());die;
        $this->request->post['customer_id'] = $this->customer->getId();
        $json = [];

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/login');

        $this->load->language('api/general');

        if (true) {
            $tokenId = base64_encode(mcrypt_create_iv(32));
            $issuedAt = time();
            $notBefore = $issuedAt;  //Adding 10 seconds
            $expire = $notBefore + 15780000; // Adding 60 seconds
            //$expire     = $notBefore + 180; // Adding 60 seconds
            $serverName = 'serverName'; /// set your domain name

            /*
             * Create the token as an array
             */
            $data = [
                'iat' => $issuedAt, // Issued at: time when the token was generated
                'jti' => $tokenId, // Json Token Id: an unique identifier for the token
                'iss' => $serverName, // Issuer
                'nbf' => $notBefore, // Not before
                'exp' => $expire, // Expire
                'data' => [// Data related to the logged user you can set your required data
                    'id' => $this->request->post['customer_id'], // id from the users table
                    'name' => $this->request->post['customer_id'], //  name
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

            $this->session->data['customer_id'] = $this->request->post['customer_id'];

            //echo  "{'status' : 'success','resp':".json_encode($unencodedArray)."}"
            $this->load->model('account/customer');

            $customer_info = $this->model_account_customer->getCustomer($this->request->post['customer_id']);
            $this->model_account_customer->cacheProductPrices(75);
            if (!empty($customer_info['dob'])) {
                $customer_info['dob'] = date('d/m/Y', strtotime($customer_info['dob']));
            } else {
                $customer_info['dob'] = '01/01/1990';
            }

            $customer_info['user_rewards_available'] = (int) $this->customer->getRewardPoints();

            $referUnique = 'refer=' . strtolower(str_replace(' ', '', $this->customer->getFirstName())) . strtolower(str_replace(' ', '', $this->customer->getLastName())) . '!@' . strtolower($this->customer->getId());

            if ($this->request->server['HTTPS']) {
                $refer_link = $this->config->get('config_ssl');
            } else {
                $refer_link = $this->config->get('config_ssl');
            }

            $refer_link = rtrim($refer_link, '/');
            $refer_link .= '?' . $referUnique;

            $customer_info['refer_link'] = $referUnique;

            //$json['success'] = $this->language->get('text_valid_otp');
            $json['token'] = $jwt; //json_encode($unencodedArray);
            $json['status'] = true;

            $json['data'] = $customer_info;
        } else {
            //$json['error'] = $this->language->get('error_login');
            $json['status'] = 10031; //user not found

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addloginHistory() {
        $json = [];

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        #region login history
        try {
            // $logindata['customer_id'] = $customer_info['customer_id'];
            $logindata['customer_id'] = $this->request->post['customer_id'];

            if (isset($this->request->post['login_latitude'])) {
                $logindata['login_latitude'] = $this->request->post['login_latitude'];
            } else {
                $logindata['login_latitude'] = 0;
            }

            if (isset($this->request->post['login_longitude'])) {
                $logindata['login_longitude'] = $this->request->post['login_longitude'];
            } else {
                $logindata['login_longitude'] = 0;
            }

            if (isset($this->request->post['login_mode'])) {
                $logindata['login_mode'] = $this->request->post['login_mode'];
            } else {
                $logindata['login_mode'] = '';
            }
            $this->load->model('account/customer');
            $this->model_account_customer->addLoginHistory($logindata);
            $json['message'][] = ['type' => '', 'body' => 'success'];
            $json['success'] = 'success';
        } catch (exception $ex) {
            $log = new Log('error.log');
            $log->write('Login History not saved');
            $json['message'][] = ['type' => '', 'body' => 'failed'];
            $json['success'] = 'failed';
        } finally {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }
        #endregion login history
    }

    public function addNewCustomerDevice() {
        //echo "<pre>";print_r( $this->request->post);die;
        try {
            $json = [];
            $json['status'] = 200;
            // $json['data'] = [];
            // $json['message'] = []; 
            if (isset($this->request->post['customer_id']) && isset($this->request->post['device_id'])) {
                $this->load->model('account/api');
                $this->model_account_api->addCustomerDevice($this->request->post['customer_id'], $this->request->post['device_id']);
                $json['message'] = "Success";
            } else {
                $json['status'] = 10010;
                $json['message'] = "Error";
                http_response_code(400);
            }
        } catch (exception $ex) {
            $json['status'] = 500;
            $json['message'] = "Device Mapping Failed";
            http_response_code(400);
        } finally {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }
    }

    public function addSendpushnotificationtoallcustomer() {

        $log = new Log('error.log');
        $log->write('addsendpushnotificationtoallcustomers');
        $this->load->model('account/customer');
        $customers = $this->model_account_customer->getAllCustomers();
        //$customers = $this->model_account_customer->getCustomerById($this->request->post['customer_id']);
        foreach ($customers as $customer) {
            $sen['customer_id'] = '';
            $log->write($customer['customer_id'] . ' ' . $customer['device_id']);
            $ret = $this->emailtemplate->sendDynamicPushNotification($customer['customer_id'], $customer['device_id'], $this->request->post['message'], $this->request->post['title'], $sen);
        }
        $json['response'] = $ret;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function refreshtoken() {
        //echo "<pre>";print_r( "addNewAccessToken");die;
        //echo "<pre>";print_r($this->customer->getId());die;
        $log = new Log('error.log');
        $log->write('TOKEN_REFRESH');
        $this->request->post['customer_id'] = $this->customer->getId();
        $json = [];

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/login');

        $this->load->language('api/general');

        if ($this->customer->getId()) {
            $tokenId = base64_encode(mcrypt_create_iv(32));
            $issuedAt = time();
            $notBefore = $issuedAt;  //Adding 10 seconds
            $expire = $notBefore + 15780000; // Adding 60 seconds
            //$expire     = $notBefore + 180; // Adding 60 seconds
            $serverName = 'serverName'; /// set your domain name

            /*
             * Create the token as an array
             */
            $data = [
                'iat' => $issuedAt, // Issued at: time when the token was generated
                'jti' => $tokenId, // Json Token Id: an unique identifier for the token
                'iss' => $serverName, // Issuer
                'nbf' => $notBefore, // Not before
                'exp' => $expire, // Expire
                'data' => [// Data related to the logged user you can set your required data
                    'id' => $this->request->post['customer_id'], // id from the users table
                    'name' => $this->request->post['customer_id'], //  name
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

            $this->session->data['customer_id'] = $this->request->post['customer_id'];

            $this->load->model('account/customer');

            $customer_info = $this->model_account_customer->getCustomer($this->request->post['customer_id']);
            $customer_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . (int) $this->customer->getId() . "' AND status = '1'");

            if ($customer_query->num_rows) {
                $log->write($customer_query->row['customer_category']);

                /* SET CUSTOMER CATEGORY */
                $data['customer_category'] = NULL;
                if ($customer_query->row['customer_id'] > 0 && $customer_query->row['parent'] > 0) {
                    $parent_customer_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . $this->db->escape($customer_query->row['parent']) . "' AND status = '1' AND approved='1'");
                    if ($customer_query->num_rows > 0 && $parent_customer_query->row['customer_id'] > 0) {
                        $data['customer_category'] = $parent_customer_query->row['customer_category'];
                    } else {
                        $data['customer_category'] = NULL;
                    }
                }

                if ($customer_query->row['customer_id'] > 0 && ($customer_query->row['parent'] == NULL || $customer_query->row['parent'] == 0)) {
                    $data['customer_category'] = $customer_query->row['customer_category'];
                }

                $customer_query->row['customer_category'] = $data['customer_category'];
                /* SET CUSTOMER CATEGORY */

                /* SET CUSTOMER PEZESHA */
                if ($customer_query->row['customer_id'] > 0 && ($customer_query->row['parent'] == NULL || $customer_query->row['parent'] == 0)) {
                    $pezesha_customer_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "pezesha_customers WHERE customer_id = '" . (int) $customer_query->row['customer_id'] . "'");
                }
                if ($customer_query->row['customer_id'] > 0 && $customer_query->row['parent'] > 0) {
                    $pezesha_customer_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "pezesha_customers WHERE customer_id = '" . (int) $customer_query->row['parent'] . "'");
                }
                if ($customer_query->num_rows > 0 && $pezesha_customer_query->num_rows > 0 && $pezesha_customer_query->row['customer_id'] > 0) {
                    $customer_query->row['pezesha_customer_id'] = $pezesha_customer_query->row['pezesha_customer_id'];
                    $customer_query->row['pezesha_customer_uuid'] = $pezesha_customer_query->row['customer_uuid'];
                    $customer_query->row['pezesha_identifier'] = $pezesha_customer_query->row['customer_id'];
                } else {
                    $customer_query->row['pezesha_customer_id'] = NULL;
                    $customer_query->row['pezesha_customer_uuid'] = NULL;
                    $customer_query->row['pezesha_identifier'] = NULL;
                }
                /* SET CUSTOMER PEZESHA */

                $this->customer->setVariables($customer_query->row);
            }

            $json['token'] = $jwt; //json_encode($unencodedArray);
            $json['status'] = true;

            $json['data'] = $customer_info;
        } else {
            $json['status'] = 1; //user not found
        }
        $log = new Log('error.log');
        $log->write('TOKEN_REFRESH');
        return $json;
    }

}
