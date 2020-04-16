<?php

require_once DIR_SYSTEM.'/vendor/icanboogie/inflector/vendor/autoload.php';

require_once(DIR_SYSTEM . 'vendor/firebase/php-jwt/vendor/autoload.php'); 

use ICanBoogie\Inflector;
use Firebase\JWT\JWT;

define('SECRET_KEY','customer-app-apiss');
define('ALGORITHM','HS512');


class EventAppApi extends Event
{

    public function postAppEcommerce()
    {

        

        // api/categories or api/categories/1
        $path = $this->getPath();


        if (empty($path) || ($path[0] != 'api') || (count($path) < 2)) {
            return;
        }

        // Don't break old API calls @BC
        // if (Inflector::get()->singularize($path[1]) == $path[1]) {
        //     return;
        // }

        // Get request method
        $method = $this->getMethod();

        // Get arguments
        $args = $this->getArguments($path, $method);

        //echo "<pre>";print_r($args);die;
        /*echo "postAppEcommerce";
        print_r($args);*/
        // api/orders
        //$route = $this->getRoute($path, $method);

        

        if(!empty($this->request->server['HTTP_X_USER']) && $this->request->server['HTTP_X_USER'] == 'customer') {
            // customer api

            $route = $this->getCustomerRoute($path, $method);

            $log = new Log('error.log');
            $log->write('route  api');
            $log->write($route);

            // route  api
            // 2018-02-22 2:33:23 - api/customer/login/addNewAccessToken
            //echo "<pre>";print_r($route);die;
            //echo "<pre>";print_r($args);die;

            //unset($this->session->data['customer_id']);die;
            if($route == 'api/customer/address/getAlladdress' || $route == 'api/customer/address/deleteaddress' || $route == 'api/customer/address/deleteAddress' || $route == 'api/customer/address/addAddress' || $route == 'api/customer/address/getAddress' || $route == 'api/customer/address/editAddress' || $route == 'api/customer/account/getUserdetails' || $route == 'api/customer/account/editUserdetail' || $route == 'api/customer/account/editUserDetail' || $route == 'api/customer/order/addMissingOrder' || $route == 'api/customer/order/addOrder' || $route == 'api/customer/order/getOrders' || $route == 'api/customer/checkout/addApplycoupon' || $route == 'api/customer/checkout/addApplyreward' || $route == 'api/customer/address/addMakedefaultaddress' || $route == 'api/customer/order/getOrder' || $route == 'api/customer/account/getUserRewards' || $route == 'api/customer/return/getUserReturns' || $route == 'api/customer/wishlist/getUserList' || $route == 'api/customer/refer/getUserRefers' || $route == 'api/customer/account/getUserCash' || $route == 'api/customer/wishlist/addCreateWishlist' || $route == 'api/customer/wishlist/addProductToWishlist' || $route == 'api/customer/wishlist/editWishlistProduct' || $route == 'api/customer/wishlist/editDeleteWishlist' || $route == 'api/customer/wishlist/editDeleteWishlistProduct' || $route == 'api/customer/wishlist/addCreateWishlistWithProduct' || $route == 'api/customer/return/getReturnDetail' || $route == 'api/customer/return/addReturnProduct' || $route == 'api/customer/wishlist/getUserListProduct' || $route == 'api/customer/payment/getStripeCustomerId' || $route == 'api/customer/order/addOrdercancel' || $route == 'api/customer/payment/addStripeEphemeralKey' || $route == 'api/customer/account/addStripeUser' || $route == 'api/customer/settings/addDeviceIdToCustomer' || $route == 'api/customer/stores/getStoreShippingMethods' || $route == 'api/customer/stores/getStoreshippingmethods' || $route == 'api/customer/login/addNewAccessToken' || $route == 'api/customer/payment/addMpesaConfirm' || $route == 'api/customer/payment/addMpesaComplete') {

                //echo "<pre>";print_r("ER");die;
                // loogin required for above routes
                $resp = $this->customer_token_authenticate();

                //echo "<pre>";print_r($resp);die;
                if ($resp['status'] == 1) {
                    $this->load->controller($route, $args);
                } else {

                    $this->response->addHeader('Content-Type: application/json');
                    $this->response->setOutput(json_encode($resp));
                }

            } elseif($route == 'api/customer/login/addLogin') {
                // Authorize
                $this->load->controller('api/customer/login');

            } elseif($route == 'api/customer/login/addLoginByOtp') {

                // Authorize
                $this->load->controller('api/customer/login/addLoginByOtp');
            }
            elseif($route == 'api/customer/login/addLoginVerifyOtp') {

                // Authorize
                $this->load->controller('api/customer/login/addLoginVerifyOtp');
            }
            elseif($route == 'api/customer/login/addLoginVerifyOtp') {

                // Authorize
                $this->load->controller('api/customer/login/addLoginVerifyOtp');
            }
            else {

                //echo "<pre>";print_r($route);die;

                $this->load->controller($route, $args);
            }

            

            $this->response->output();

            die;

        } else {

            $route = $this->getRoute($path, $method);

            $log = new Log('error.log');
            $log->write('other api call other than customet api');
            $log->write($route);

            if($route == 'api/forgetpassword/addForgetpassword') {
                
            }else if( $route == 'api/orders/getOrdersForDelivery'){
                $groups = array('Delivery Team','Administrator','API User','vendor');
                if (!$this->authenticateByGroup($groups)) {
                    return;
                } 
            } else {
                $groups = array('Administrator','API User','vendor');
                if (!$this->authenticateByGroup($groups)) {
                    return;
                } 
            }

            
            // Action
            $this->load->controller($route, $args);

            // Echo
            $this->response->output();

        }


        /*if($route == 'api/forgetpassword/addForgetpassword') {
            
        } else {
            if (!$this->authenticate()) {
                return;
            } 
        }

        
        // Action
        $this->load->controller($route, $args);

        // Echo
        $this->response->output();*/
        
        

        die();
    }

    private function getPath()
    {
        
        $parts = array();

        $query_string = $this->uri->getQuery();

        $path = str_replace($this->url->getFullUrl(), '', rawurldecode($this->uri->toString()));
        $path = str_replace('?'.$query_string, '', $path);

        if (empty($path)) {
            return $parts;
        }

        // May not use htaccess
        $path = str_replace('index.php', '', $path);
        $path = ltrim($path, '/');

        $parts = explode('/', $path);

        return $parts;
    }

    private function authenticate()
    {
        $username = $password = '';

        if (!empty($this->request->server['PHP_AUTH_USER']) && !empty($this->request->server['PHP_AUTH_PW'])) {
            // mod_php servers
            $username = $this->request->server['PHP_AUTH_USER'];
            $password = $this->request->server['PHP_AUTH_PW'];
        } elseif (!empty($this->request->server['HTTP_AUTHORIZATION']) && (strpos(strtolower($this->request->server['HTTP_AUTHORIZATION']), 'basic') === 0)) {
            // most other servers
            list($username, $password) = explode(':', base64_decode(substr($this->request->server['HTTP_AUTHORIZATION'], 6)));
        }

        //echo "<pre>";print_r($password);die;
        if (empty($username) || empty($password)) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode(array('error' => 'Empty username or password')));
            $this->response->output();

            die();
        }

        // Set username/password
        $this->request->post['username'] = $username;
        $this->request->post['password'] = $password;

        // Authorize
        $this->load->controller('api/login');

        if (!isset($this->session->data['api_id'])) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode(array('error' => 'Login failed')));
            $this->response->output();

            die();
        }

        // Reset output
        $this->response->setOutput('');

        unset($this->request->post['username']);
        unset($this->request->post['password']);

        return true;
    }

    private function authenticateByGroup($groups=array())
    {
        $username = $password = '';

        if (!empty($this->request->server['PHP_AUTH_USER']) && !empty($this->request->server['PHP_AUTH_PW'])) {
            // mod_php servers
            $username = $this->request->server['PHP_AUTH_USER'];
            $password = $this->request->server['PHP_AUTH_PW'];
        } elseif (!empty($this->request->server['HTTP_AUTHORIZATION']) && (strpos(strtolower($this->request->server['HTTP_AUTHORIZATION']), 'basic') === 0)) {
            // most other servers
            list($username, $password) = explode(':', base64_decode(substr($this->request->server['HTTP_AUTHORIZATION'], 6)));
        }

        //echo "<pre>";print_r($password);die;
        if (empty($username) || empty($password)) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode(array('error' => 'Empty username or password')));
            $this->response->output();

            die();
        }

        // Set username/password
        $this->request->post['username'] = $username;
        $this->request->post['password'] = $password;
        $this->request->post['groups'] = $groups;


        // Authorize
        $this->load->controller('api/login');

        if (!isset($this->session->data['api_id'])) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode(array('error' => 'Login failed')));
            $this->response->output();

            die();
        }

        // Reset output
        $this->response->setOutput('');

        unset($this->request->post['username']);
        unset($this->request->post['password']);
        unset($this->request->post['groups']);


        return true;
    }

    private function customer_authenticate()
    {
        $username = $password = '';
        //unset($this->session->data['customer_id']);die;
        if( $this->customer->isLogged() ) {
            return true;
        }

        if (!empty($this->request->server['PHP_AUTH_USER']) && !empty($this->request->server['PHP_AUTH_PW'])) {
            // mod_php servers
            //echo "here php";

            /*echo "<pre>";print_r($this->request->server);die;
        echo "<pre>";print_r($password."hh".$username);die;*/

            $username = $this->request->server['PHP_AUTH_USER'];
            $password = $this->request->server['PHP_AUTH_PW'];
        } elseif (!empty($this->request->server['HTTP_AUTHORIZATION']) && (strpos(strtolower($this->request->server['HTTP_AUTHORIZATION']), 'basic') === 0)) {

            //echo "HTTP_AUTHORIZATION";die;
            // most other servers
            list($username, $password) = explode(':', base64_decode(substr($this->request->server['HTTP_AUTHORIZATION'], 6)));
        }

        if (empty($username) || empty($password)) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode(array('error' => 'Empty username or password')));
            $this->response->output();

            die();
        }

        // Set username/password
        $this->request->post['username'] = $username;
        $this->request->post['password'] = $password;

        // Authorize
        $this->load->controller('api/customer/login');

        if (!isset($this->session->data['customer_id'])) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode(array('error' => 'Login failed')));
            $this->response->output();

            die();
        }

        // Reset output
        $this->response->setOutput('');

        unset($this->request->post['username']);
        unset($this->request->post['password']);

        return true;
    }


    private function getMethod()
    {
        $method = isset($this->request->server['REQUEST_METHOD']) ? $this->request->server['REQUEST_METHOD'] : 'GET';

        return strtolower($method);
    }

    private function getArguments($path, $method)
    {
        $args = array();

        switch ($method) {
            case 'get':
                $args = $this->uri->getQuery(true);

                // Resource ID
                if (!empty($path[2]) && is_numeric($path[2])) {
                    $args['id'] = $path[2];
                }

                break;
            case 'post':
                $args = $this->request->post;

                // Resource ID
                if (!empty($path[2]) && is_numeric($path[2])) {
                    $args['id'] = $path[2];
                }
                break;
            case 'put':
                parse_str(file_get_contents('php://input'), $args);

                // Resource ID
                $args['id'] = $path[2];

                $args = $this->request->clean($args);

                break;
            case 'delete':
                // Resource ID
                $args['id'] = $path[2];

                break;
        }

        return $args;
    }

    private function getRoute($path, $method)
    {
        $folder = $path[0];
        $file = $path[1];
        $function = $this->getFunction($path, $method);

        $route = $folder . '/' . $file . '/' . $function;//exit;

        return $route;
    }

    private function getCustomerRoute($path, $method)
    {
        $folder = $path[0];
        $file = $path[1];
        $file1 = $path[2];
        $function = $this->getFunction($path, $method);

        $route = $folder . '/' . $file . '/' . $file1 . '/' . $function;

        return $route;
    }

    

    private function getFunction($path, $method)
    {
        $methods = array('get' => 'get', 'post' => 'add', 'put' => 'edit', 'delete' => 'delete');
        
        $log = new Log('error.log');
        $log->write('getFunction');
        $log->write($path);

        $all_singular = false;

        if (!empty($path[3])) {
            // link: api/orders/1/products
            // function: getProducts, addProduct
            $name = $path[3];
        } elseif (!empty($path[2])) {
            if (is_numeric($path[2])) {
                // link: api/orders/1
                // function: getOrder, editOrder, deleteOrder
                $name = $path[1];
                $all_singular = true;
            } else {
                // link: api/orders/totals
                // function: getTotals, addTotal
                $name = $path[2];
            }
        } else {
            // link: api/orders
            // function: getOrders, addOrder
            $name = $path[1];
        }

        $log->write($name);

        if (!$all_singular && ($method == 'get')) {
            $function = $methods[$method] . ucfirst($name);
        } else {
            $singular = Inflector::get()->singularize($name);

            $function = $methods[$method] . ucfirst($singular);
        }

        $log->write($function);

        return $function;
    }

    function getAuthorizationHeader(){
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }


    private function customer_token_authenticate()
    {
        $res['status'] = 10022;
        $res['message'] = "Unauthorized";
        

        $matches = [];
        $headers = $this->getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                /*echo "<pre>";print_r($matches);die;
                return $matches[1];*/
            }
        }

        $log = new Log('error.log');
        $log->write('customer_token_authenticate');
        $log->write($this->customer->isLogged()."cerf");
        $log->write($matches);
        //echo "<pre>";print_r($this->customer->isLogged());die;
        
        if(count($matches) > 1 && isset($matches[1])) {
            //echo "<pre>";print_r($headers);die;

            try {
                $secretKey = base64_decode(SECRET_KEY); 
                $DecodedDataArray = JWT::decode($matches[1], $secretKey, array(ALGORITHM));

                $log->write($DecodedDataArray);
                
                if(isset($DecodedDataArray) && isset($DecodedDataArray->data)) {
                    $this->session->data['customer_id'] = $DecodedDataArray->data->id;  
                    

                    $customer_query = $this->db->query( "SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$DecodedDataArray->data->id . "' AND status = '1'" );

                    //echo "<pre>";print_r($customer_query->row);die;
                    if ( $customer_query->num_rows ) {
                        $log->write("in customer st");
                        $this->customer->setVariables($customer_query->row);
                    } else {
                        return $res;
                    }


                    $log->write($this->customer->isLogged()."cerfxx");
                    $log->write($this->customer->getId()."cerfxx22");
                    

                } else {
                    return $res;
                }
                //echo "<pre>";print_r($DecodedDataArray->data->id);die;
                
                $res['status'] = 1;
                $res['data'] = json_encode($DecodedDataArray);

            } catch (Exception $e) {

                //echo "<pre>";print_r($e);die;
            }

        }

       //echo "<pre>";print_r($res);die;
       return $res;
    }
}

