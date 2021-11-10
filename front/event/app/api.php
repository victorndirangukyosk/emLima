<?php

require_once DIR_SYSTEM . '/vendor/icanboogie/inflector/vendor/autoload.php';

require_once DIR_SYSTEM . 'vendor/firebase/php-jwt/vendor/autoload.php';

use Firebase\JWT\JWT;
use ICanBoogie\Inflector;

define('SECRET_KEY', 'customer-app-apiss');
define('ALGORITHM', 'HS512');

class EventAppApi extends Event {

    public function postAppEcommerce() {
        // api/categories or api/categories/1
        $path = $this->getPath();
        // echo "<pre>";print_r($path);die;
        if (empty($path) || ('api' != $path[0]) || (count($path) < 2)) {
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
        /* echo "postAppEcommerce";
          print_r($args); */
        // api/orders
        //$route = $this->getRoute($path, $method);

        if (!empty($this->request->server['HTTP_X_USER']) && 'customer' == $this->request->server['HTTP_X_USER']) {
            // customer api

            $route = $this->getCustomerRoute($path, $method);

            $log = new Log('error.log');
            $log->write('route  api');
            $log->write($this->session->getId());
            $log->write($route);

            // route  api
            // 2018-02-22 2:33:23 - api/customer/login/addNewAccessToken
            //  echo "<pre>";print_r($route);die;
            //echo "<pre>";print_r($args);die;
            //unset($this->session->data['customer_id']);die;
            //$this->response->addHeader('Cookie:' . 'PHPSESSID=' . $this->session->getId() . ';currency=KES; language=en');
            if ('api/customer/address/getAlladdress' == $route || 'api/customer/address/deleteaddress' == $route || 'api/customer/address/deleteAddress' == $route || 'api/customer/address/addAddress' == $route || 'api/customer/address/getAddress' == $route || 'api/customer/address/editAddress' == $route || 'api/customer/account/getUserdetails' == $route || 'api/customer/account/editUserdetail' == $route || 'api/customer/account/editUserDetail' == $route || 'api/customer/order/addMissingOrder' == $route || 'api/customer/order/addOrder' == $route || 'api/customer/order/getOrders' == $route || 'api/customer/checkout/addApplycoupon' == $route || 'api/customer/checkout/addApplyreward' == $route || 'api/customer/address/addMakedefaultaddress' == $route || 'api/customer/order/getOrder' == $route || 'api/customer/account/getUserRewards' == $route || 'api/customer/return/getUserReturns' == $route || 'api/customer/wishlist/getUserList' == $route || 'api/customer/refer/getUserRefers' == $route || 'api/customer/account/getUserCash' == $route || 'api/customer/wishlist/addCreateWishlist' == $route || 'api/customer/wishlist/addProductToWishlist' == $route || 'api/customer/wishlist/editWishlistProduct' == $route || 'api/customer/wishlist/editDeleteWishlist' == $route || 'api/customer/wishlist/editDeleteWishlistProduct' == $route || 'api/customer/wishlist/addCreateWishlistWithProduct' == $route || 'api/customer/return/getReturnDetail' == $route || 'api/customer/return/addReturnProduct' == $route || 'api/customer/wishlist/getUserListProduct' == $route || 'api/customer/payment/getStripeCustomerId' == $route || 'api/customer/order/addOrdercancel' == $route || 'api/customer/payment/addStripeEphemeralKey' == $route || 'api/customer/account/addStripeUser' == $route || 'api/customer/settings/addDeviceIdToCustomer' == $route || 'api/customer/stores/getStoreShippingMethods' == $route || 'api/customer/stores/getStoreshippingmethods' == $route || 'api/customer/login/addNewAccessToken' == $route || 'api/customer/payment/addMpesaConfirm' == $route || 'api/customer/payment/addMpesaComplete' == $route || 'api/customer/products/getProducts' == $route || 'api/customer/products/getProductSearch' == $route || 'api/customer/account/addSendNewDeviceotp' == $route || 'api/customer/account/addVerifyNewDeviceotp' == $route || 'api/customer/products/getProductAutocomplete' == $route || 'api/customer/user_notification_settings/addCustomerNotificationSetting' == $route | 'api/customer/user_notification_settings/getCustomerNotificationSettings' == $route || 'api/customer/order/addEditOrderWithNewitemAndQuantity' == $route || 'api/customer/order/addMaxOfProduct' == $route || 'api/customer/wishlist/getAvailableOrderProducts' == $route || 'api/customer/dashboard/getDashboardDetails' == $route || 'api/customer/dashboard/getDashboardData' == $route || 'api/customer/dashboard/getValueofbasket' == $route || 'api/customer/dashboard/addCustomerstatement' == $route || 'api/customer/dashboard/addPurchaseHistory' == $route || 'api/customer/dashboard/addStatementexcel' == $route || 'api/customer/dashboard/addConsolidatedOrderProduct' == $route || 'api/customer/dashboard/getCustomerMostBoughtProducts' == $route || 'api/customer/dashboard/getRecentActivities' == $route || 'api/customer/dashboard/getRecentOrders' == $route || 'api/customer/dashboard/getRecentOrdersList' == $route || 'api/customer/dashboard/getRecentOrdersProductsList' == $route || 'api/customer/dashboard/getMostPurchasedProductsExcel' == $route || 'api/customer/dashboard/getPurchaseHistoryByProductID' == $route || 'api/customer/dashboard/getProductPurchaseHistory' == $route || 'api/customer/subusers/getSubUsers' == $route || 'api/customer/subusers/addNewSubUser' == $route || 'api/customer/order/getPendingorders' == $route || 'api/customer/Feedback/addFeedback' == $route || 'api/customer/Feedback/getFeedback' == $route || 'api/orders/export_products_excel/getExport_products_excel' == $route || 'api/customer/order/addReorder' == $route || 'api/customer/transactions/getAllTransactions' == $route || 'api/customer/account/getWalletTotal' == $route || 'api/customer/account/getWallet' == $route) {
                //  echo "<pre>";print_r("ER");die;
                // loogin required for above routes
                $resp = $this->customer_token_authenticate();

                //echo "<pre>";print_r($resp);die;
                if (1 == $resp['status']) {

                    // check customer ID
                    $cust_id = $this->customer->getId();
                    if (isset($cust_id) && $cust_id > 0) {
                        $this->load->controller($route, $args);
                    } else {
                        $resp['message'] = "Please Login again.";
                        $resp['data'] = "";
                        $resp['status'] = "10022";
                        $this->response->addHeader('Content-Type: application/json');
                        $this->response->setOutput(json_encode($resp));
                    }
                } else {
                    $this->response->addHeader('Content-Type: application/json');
                    $this->response->setOutput(json_encode($resp));
                }
            } elseif ('api/customer/login/addLogin' == $route) {
                // Authorize
                $this->load->controller('api/customer/login');
            } elseif ('api/customer/login/addLoginByOtp' == $route) {
                // Authorize
                $this->load->controller('api/customer/login/addLoginByOtp');
            } elseif ('api/customer/login/addLoginVerifyOtp' == $route) {
                // Authorize
                $this->load->controller('api/customer/login/addLoginVerifyOtp');
            } elseif ('api/customer/login/addLoginVerifyOtp' == $route) {
                // Authorize
                $this->load->controller('api/customer/login/addLoginVerifyOtp');
            } else {
                //echo "<pre>";print_r($route);die;

                $this->load->controller($route, $args);
            }

            $this->response->output();

            die;
        }
        //added else if condition to skip authentication for landing pages
        else if ('landingpage' == $path[1]) {
            $route = $this->getCustomerRoute($path, $method);

            $log = new Log('error.log');
            $log->write('route  api landing');
            $log->write($route);

            $this->load->controller($route, $args);
            $this->response->output();
            die;
        } else {
            $route = $this->getRoute($path, $method);
            // echo "<pre>";print_r($route);die;   

            $log = new Log('error.log');
            $log->write('other api call other than customet api');
            $log->write($route);
            // $log->write($args);

            if ('api/forgetpassword/addForgetpassword' == $route) {
                
            } elseif ('api/orders/getOrdersForDelivery' == $route) {
                $groups = ['Delivery Team', 'Administrator', 'API User', 'vendor'];
                if (!$this->authenticateByGroup($groups)) {
                    return;
                }
            } elseif ('api/customer/login/getloginbyadmin' == $route) {
                // Authorize
                $this->load->controller('api/customer/login/getloginbyadmin', $args);
            } elseif ('api/categories/getCategories' == $route) {
                // Authorize
                $this->load->controller('api/categories/getCategories', $args);
            } elseif ('api/customers/getCustomergroups' == $route) {
                // Authorize
                $this->load->controller('api/customers/getcustomergroups', $args);
                // echo "<pre>";print_r($route);die;   
            } elseif ('api/customers/getCustomercities' == $route) {
                // echo "<pre>";print_r($route);die;   
                // Authorize
                $this->load->controller('api/customers/getCustomercities', $args);
            } elseif ('api/customers/getDeliverytimeslots' == $route) {
                // echo "<pre>";print_r($route);die;   
                // Authorize
                $this->load->controller('api/customers/getDeliverytimeslots', $args);
            } elseif ('api/customers/getProducts' == $route) {
                // echo "<pre>";print_r($route);die;   
                // Authorize
                $this->load->controller('api/customers/getProducts', $args);
            } elseif ('api/customers/getCustomerregions' == $route) {
                // echo "<pre>";print_r($route);die;   
                // Authorize
                $this->load->controller('api/customers/getCustomerregions', $args);
            } else {
                $groups = ['Administrator', 'API User', 'vendor'];
                if (!$this->authenticateByGroup($groups)) {
                    return;
                }
            }

            // Action
            $this->load->controller($route, $args);

            // Echo
            $this->response->output();
        }

        /* if($route == 'api/forgetpassword/addForgetpassword') {

          } else {
          if (!$this->authenticate()) {
          return;
          }
          }


          // Action
          $this->load->controller($route, $args);

          // Echo
          $this->response->output(); */

        die();
    }

    private function getPath() {
        $parts = [];

        $query_string = $this->uri->getQuery();

        $path = str_replace($this->url->getFullUrl(), '', rawurldecode($this->uri->toString()));
        $path = str_replace('?' . $query_string, '', $path);

        if (empty($path)) {
            return $parts;
        }

        // May not use htaccess
        $path = str_replace('index.php', '', $path);
        $path = ltrim($path, '/');

        $parts = explode('/', $path);

        return $parts;
    }

    private function authenticate() {
        $username = $password = '';

        if (!empty($this->request->server['PHP_AUTH_USER']) && !empty($this->request->server['PHP_AUTH_PW'])) {
            // mod_php servers
            $username = $this->request->server['PHP_AUTH_USER'];
            $password = $this->request->server['PHP_AUTH_PW'];
        } elseif (!empty($this->request->server['HTTP_AUTHORIZATION']) && (0 === strpos(strtolower($this->request->server['HTTP_AUTHORIZATION']), 'basic'))) {
            // most other servers
            list($username, $password) = explode(':', base64_decode(substr($this->request->server['HTTP_AUTHORIZATION'], 6)));
        }

        //echo "<pre>";print_r($password);die;
        if (empty($username) || empty($password)) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode(['error' => 'Empty username or password']));
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
            $this->response->setOutput(json_encode(['error' => 'Login failed']));
            $this->response->output();

            die();
        }

        // Reset output
        $this->response->setOutput('');

        unset($this->request->post['username']);
        unset($this->request->post['password']);

        return true;
    }

    private function authenticateByGroup($groups = []) {
        $username = $password = '';

        if (!empty($this->request->server['PHP_AUTH_USER']) && !empty($this->request->server['PHP_AUTH_PW'])) {
            // mod_php servers
            $username = $this->request->server['PHP_AUTH_USER'];
            $password = $this->request->server['PHP_AUTH_PW'];
        } elseif (!empty($this->request->server['HTTP_AUTHORIZATION']) && (0 === strpos(strtolower($this->request->server['HTTP_AUTHORIZATION']), 'basic'))) {
            // most other servers
            list($username, $password) = explode(':', base64_decode(substr($this->request->server['HTTP_AUTHORIZATION'], 6)));
        }

        //echo "<pre>";print_r($password);die;
        if (empty($username) || empty($password)) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode(['error' => 'Empty username or password']));
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
            $this->response->setOutput(json_encode(['error' => 'Login failed']));
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

    private function customer_authenticate() {
        $username = $password = '';
        //unset($this->session->data['customer_id']);die;
        if ($this->customer->isLogged()) {
            return true;
        }

        if (!empty($this->request->server['PHP_AUTH_USER']) && !empty($this->request->server['PHP_AUTH_PW'])) {
            // mod_php servers
            //echo "here php";

            /* echo "<pre>";print_r($this->request->server);die;
              echo "<pre>";print_r($password."hh".$username);die; */

            $username = $this->request->server['PHP_AUTH_USER'];
            $password = $this->request->server['PHP_AUTH_PW'];
        } elseif (!empty($this->request->server['HTTP_AUTHORIZATION']) && (0 === strpos(strtolower($this->request->server['HTTP_AUTHORIZATION']), 'basic'))) {
            //echo "HTTP_AUTHORIZATION";die;
            // most other servers
            list($username, $password) = explode(':', base64_decode(substr($this->request->server['HTTP_AUTHORIZATION'], 6)));
        }

        if (empty($username) || empty($password)) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode(['error' => 'Empty username or password']));
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
            $this->response->setOutput(json_encode(['error' => 'Login failed']));
            $this->response->output();

            die();
        }

        // Reset output
        $this->response->setOutput('');

        unset($this->request->post['username']);
        unset($this->request->post['password']);

        return true;
    }

    private function getMethod() {
        $method = isset($this->request->server['REQUEST_METHOD']) ? $this->request->server['REQUEST_METHOD'] : 'GET';

        return strtolower($method);
    }

    private function getArguments($path, $method) {
        $args = [];

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

    private function getRoute($path, $method) {
        $folder = $path[0];
        $file = $path[1];
        $function = $this->getFunction($path, $method);

        $route = $folder . '/' . $file . '/' . $function; //exit;

        return $route;
    }

    private function getCustomerRoute($path, $method) {
        $folder = $path[0];
        $file = $path[1];
        $file1 = $path[2];
        $function = $this->getFunction($path, $method);

        $route = $folder . '/' . $file . '/' . $file1 . '/' . $function;

        return $route;
    }

    private function getFunction($path, $method) {
        $methods = ['get' => 'get', 'post' => 'add', 'put' => 'edit', 'delete' => 'delete'];

        $log = new Log('error.log');
        $log->write('getFunction');
        $log->write($method);
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

        if (!$all_singular && ('get' == $method)) {
            $function = $methods[$method] . ucfirst($name);
        } else {
            $singular = Inflector::get()->singularize($name);

            $function = $methods[$method] . ucfirst($singular);
        }

        $log->write($function);

        return $function;
    }

    public function getAuthorizationHeader() {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER['Authorization']);
        } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER['HTTP_AUTHORIZATION']);
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

    private function customer_token_authenticate() {
        $res['status'] = 10022;
        $res['message'] = 'Unauthorized';

        $matches = [];
        $headers = $this->getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                /* echo "<pre>";print_r($matches);die;
                  return $matches[1]; */
            }
        }

        $log = new Log('error.log');
        $log->write('customer_token_authenticate');
        $log->write($this->customer->isLogged() . 'cerf');
        $log->write($matches);
        //echo "<pre>";print_r($this->customer->isLogged());die;

        if (count($matches) > 1 && isset($matches[1])) {
            //echo "<pre>";print_r($headers);die;

            try {
                $secretKey = base64_decode(SECRET_KEY);
                $DecodedDataArray = JWT::decode($matches[1], $secretKey, [ALGORITHM]);

                $log->write($DecodedDataArray);

                if (isset($DecodedDataArray) && isset($DecodedDataArray->data)) {
                    $this->session->data['customer_id'] = $DecodedDataArray->data->id;

                    $customer_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . (int) $DecodedDataArray->data->id . "' AND status = '1'");

                    //echo "<pre>";print_r($customer_query->row);die;
                    if ($customer_query->num_rows) {
                        $log->write('in customer st');
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
                        //$this->customer->setVariables($data['customer_category']);
                        /* SET CUSTOMER CATEGORY */

                        /* SET CUSTOMER PEZESHA */
                        $pezesha_customer_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "pezesha_customers WHERE customer_id = '" . (int) $customer_query->row['customer_id'] . "'");
                        if ($customer_query->num_rows > 0 && $pezesha_customer_query->num_rows > 0 && $pezesha_customer_query->row['customer_id'] > 0) {
                            $customer_query->row['pezesha_customer_id'] = $pezesha_customer_query->row['pezesha_customer_id'];
                            $customer_query->row['pezesha_customer_uuid'] = $pezesha_customer_query->row['customer_uuid'];
                        } else {
                            $customer_query->row['pezesha_customer_id'] = NULL;
                            $customer_query->row['pezesha_customer_uuid'] = NULL;
                        }
                        /* SET CUSTOMER PEZESHA */

                        $this->customer->setVariables($customer_query->row);
                    } else {
                        return $res;
                    }
                    $log->write($this->customer->getCustomerCategory() . 'customer_category');
                    $log->write($this->customer->isLogged() . 'cerfxx');
                    $log->write($this->customer->getId() . 'cerfxx22');
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
