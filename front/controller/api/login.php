<?php

require_once DIR_SYSTEM.'vendor/firebase/php-jwt/vendor/autoload.php';
use Firebase\JWT\JWT;

class ControllerApiLogin extends Controller {

    public function index() {
        $this->load->language('api/login');

        // Delete old login so not to cause any issues if there is an error
        //echo $this->session->data['api_id'];
        // echo "session api id";

        unset($this->session->data['api_id']);

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
        $this->load->model('user/user');

        $api_info = $this->model_account_api->login($this->request->post['username'], $this->request->post['password']);
        if ($api_info['user_id']) {
            $user_info = $this->model_user_user->getUser($api_info['user_id']);
            $log = new Log('error.log');
            $log->write('user_info');
            $log->write($user_info);
            $log->write('user_info');
        }
        // echo $this->request->post['username'], $this->request->post['password'];

        if ($api_info['status']) {
            if (!isset($this->session->data['api_id'])) {
                $this->session->data['api_id'] = $api_info['user_id'];
            }
            //echo $this->request->post['groups'];exit;
            if (isset($this->request->post['groups']) && count($this->request->post['groups']) > 0) {
                if (in_array($user_info['user_group'], $this->request->post['groups'])) {
                    $this->session->data['api_id'] = $api_info['user_id'];
                    $json['success'] = $this->language->get('text_success');
                } else {
                    $this->response->addHeader('Content-Type: application/json');
                    $this->response->setOutput(json_encode(['error' => 'Not authorized to access this api']));
                    $this->response->output();
                    die();
                }
            } else {
                $json['success'] = $this->language->get('text_success');
            }
        } else {
            //print_r("else");
            $json['error'] = $this->language->get('error_login');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addsendpushnotification() {
        $ret = $this->emailtemplate->sendPushNotification($this->request->post['vendor_id'], $this->request->post['device_id'], $this->request->post['order_id'], $this->request->post['store_id'], $this->request->post['message'], $this->request->post['title']);
        $json['response'] = $ret;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }


    //Customer login by admin for new app//same method replicated in admin module regarding token session
    //this is temperory method, need to validate, category pricing, two level approval process,tax
    public function getloginbyadmin($args) {
        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];
        // $json['customer_id'] = $args['customer_id'];
        $json['url'] = [];

        // if (isset($args['customer_id'])) {
        //     $customer_id = $args['customer_id'];
        // } else {
        //     $customer_id = 0;
        // }

        //below token check added for security
        #region       


        // if ((isset($this->session->data['token']) && !isset($this->request->get['token'])) || ((isset($this->request->get['token']) && (isset($this->session->data['token']) && ($this->request->get['token'] != $this->session->data['token']))))) {
        //     $this->error['warning'] = $this->language->get('error_token');
        // }

            // echo "<pre>";print_r($this->session);die; 
            
        if(!isset($this->request->get['admintoken']))
        {
            $json['message'] = 'Please check the URL... ';                    
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
            return;
        }

        // if(!isset($this->session->data['admintoken']))
        // {
        //     $json['message'] = 'Please login again as Admin ';                    
        //     $this->response->addHeader('Content-Type: application/json');
        //     $this->response->setOutput(json_encode($json));
        //     return;
        // }
        // if ((isset($this->session->data['admintoken']) && !isset($this->request->get['token'])) || ((isset($this->request->get['token']) && (isset($this->session->data['admintoken']) && ($this->request->get['token'] != $this->session->data['admintoken']))))) {
        
        //     $json['message'] = 'Authentication Failed.Please login again as Admin';                    
        //     $this->response->addHeader('Content-Type: application/json');
        //     $this->response->setOutput(json_encode($json));
        //     return;
        // } session not coming from Admin folder ,so implementing base64

        $admintoken= base64_decode($this->request->get['admintoken']);
        $tokenvalue= (explode(":",$admintoken));
        
            if (isset($tokenvalue[1])) {
                $customervalue = $tokenvalue[1];
                $key = (int)(date("dmY")); 
                $customer_id= $customervalue-$key;
                $json['customer_id'] =$customer_id;
                
            }
           
            // echo "<pre>";print_r((int)($customer_id));die; 

        #endregion

        // $this->load->model('sale/customer');
        // $customer_info = $this->model_sale_customer->getCustomer($customer_id);

        $this->load->model('account/customer');
        $customer_info = $this->model_account_customer->getCustomer($customer_id);
       

        if (array_key_exists('customer_id', $customer_info)) {
            // echo "<pre>";print_r(($customer_info));die; 
            if($customer_info['email']!=$tokenvalue[0])
            {
                $json['message'] = 'User emaial does not match'; 
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($json));
                return;
            }
        $customer_info['devices'] = $this->model_account_customer->getCustomerDevices($api_info['customer_id']);


            $token = md5(mt_rand()); 

             $this->model_account_customer->editToken($customer_id, $token);
            if (isset($args['store_id'])) {
                $store_id = $args['store_id'];
            } else {
                $store_id = 0;
            }
             $this->load->model('setting/store');
             $store_info = $this->model_setting_store->getStore($store_id);
            
              // Add to activity log
              $this->load->model('account/activity');

            $activity_data = [
                'customer_id' => $customer_info['customer_id'],
                'name' => $customer_info['firstname'] . ' ' . $customer_info['lastname'],
            ];

            $this->model_account_activity->addActivity('login', $activity_data);

            //   if ($store_info) {
            //     //  $this->response->redirect($store_info['url'] . 'index.php?path=account/login/adminRedirectLogin&token=' . $token);
            //   } else {
            //     // $data=  $this->response->redirect(HTTP_CATALOG . 'index.php?path=account/login/adminRedirectLogin&token=' . $token);
               
            //     }

            $data=  $this->adminRedirectLogin($token) ;
            $json['message'] = 'User Logged';
            $json['customer_token'] = $token;
            $json['token'] = $data;
            $json['customer'] = $customer_info;

            
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));

        } else {
            $json['message'] = 'User not found'; 
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));

        }
    }

    public function adminRedirectLogin($token) {
        // $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_login.css');
            
        $this->load->model('account/customer');

        // Login override for admin users
        if (!empty($token)) {
            $this->trigger->fire('pre.customer.login');

            $this->customer->logout();
            $this->cart->clear();
            unset($this->session->data['customer_id']);
            unset($this->session->data['customer_category']);
            unset($this->session->data['wishlist']);
            unset($this->session->data['payment_address']);
            unset($this->session->data['payment_method']);
            unset($this->session->data['payment_methods']);
            unset($this->session->data['shipping_address']);
            unset($this->session->data['shipping_method']);
            unset($this->session->data['shipping_methods']);
            unset($this->session->data['comment']);
            unset($this->session->data['order_id']);
            unset($this->session->data['coupon']);
            unset($this->session->data['reward']);
            unset($this->session->data['voucher']);
            unset($this->session->data['vouchers']);
            unset($this->session->data['adminlogin']);

            $api_info= $customer_info = $this->model_account_customer->getCustomerByToken($token);

            // if ($customer_info && $this->customer->login($customer_info['email'], '', true)) {
            //     // Default Addresses
            //     $this->load->model('account/address');

            //     if ('shipping' == $this->config->get('config_tax_customer')) {
            //         $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
            //     }

            //     $this->model_account_customer->cacheProductPrices(75);
            //     $this->trigger->fire('post.customer.login');

            //     // maintain session to identify as admin login
            //     $this->session->data['adminlogin'] = 1;
            //     //$this->response->redirect($this->url->link('account/account', '', 'SSL'));
            //     //REDIRECTING TO HOME PAGE
            //     $this->response->redirect('/');
            // }


            if ($customer_info){
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

            // echo "<pre>";print_r($jwt);die; 
        }

         // echo "<pre>";print_r($api_info);die; 
            
         if ($api_info['parent'] != NULL && $api_info['parent']>0) {
            $customer_details = $this->db->query('SELECT customer_category FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . $api_info['parent'] . "' AND status = '1'");
        } else {
            $customer_details = $this->db->query('SELECT customer_category FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . $api_info['customer_id']. "' AND status = '1'");
        }
        $this->session->data['customer_category'] = isset($customer_details->row['customer_category']) ? $customer_details->row['customer_category'] : null;
        // echo "<pre>";print_r($customer_details);die; 
        $this->session->data['order_approval_access'] = $customer_info['order_approval_access'];
        $this->session->data['order_approval_access_role'] = $customer_info['order_approval_access_role'];

        //echo  "{'status' : 'success','resp':".json_encode($unencodedArray)."}"
        $this->load->model('account/customer');
        $this->model_account_customer->cacheProductPrices(75);


        }

        if ($this->customer->isLogged()) {
            $this->model_account_customer->cacheProductPrices(75);
            $this->response->redirect('/');
            //REDIRECTING TO HOME PAGE
            //$this->response->redirect($this->url->link('account/account', '', 'SSL'));
        }

        $this->load->language('account/login');

        // $this->document->setTitle($this->language->get('heading_title'));

        // if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validate()) {
        //     unset($this->session->data['guest']);

        //     // Default Shipping Address
        //     $this->load->model('account/address');

        //     if ('shipping' == $this->config->get('config_tax_customer')) {
        //         $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
        //     }

        //     // Add to activity log
        //     $this->load->model('account/activity');

        //     $activity_data = [
        //         'customer_id' => $this->customer->getId(),
        //         'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
        //     ];

        //     $this->model_account_activity->addActivity('login', $activity_data);

        //     $data['status'] = true;
        //     //$data['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');

        //     $this->session->data['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
        //     $this->response->addHeader('Content-Type: application/json');
        //     $this->response->setOutput(json_encode($data));
        //     /* if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
        //       $this->response->redirect(str_replace('&amp;', '&', $this->request->post['redirect']));
        //       } else {
        //       $this->response->redirect($this->url->link('account/account', '', 'SSL'));
        //       } */
        // }
   

        // $this->response->addHeader('Content-Type: application/json');
        // $this->response->setOutput(json_encode($data));
        return $jwt;
        

         
    }

    //below method used to check dmin login token/session token
    public function check() {
        $path = '';

        if (isset($this->request->get['path'])) {
            $part = explode('/', $this->request->get['path']);

            if (isset($part[0])) {
                $path .= $part[0];
            }

            if (isset($part[1])) {
                $path .= '/' . $part[1];
            }
        }

        $ignore = [
            'common/login',
            'common/forgotten',
            'common/reset',
            'common/scheduler',
            'amitruck/amitruck',
        ];

        if (!$this->user->isLogged() && !in_array($path, $ignore)) {
            return new Action('common/login');
        }

        if (isset($this->request->get['path'])) {
            $ignore = [
                'common/login',
                'common/logout',
                'common/forgotten',
                'common/reset',
                'error/not_found',
                'error/permission',
                'common/scheduler',
                'amitruck/amitruck',
            ];

            if (!in_array($path, $ignore) && (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token']))) {
                return new Action('common/login');
            }
        } else {
            if (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
                return new Action('common/login');
            }
        }
    }

}
