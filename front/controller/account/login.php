<?php
require_once(DIR_SYSTEM . 'vendor/firebase/php-jwt/vendor/autoload.php'); 
use Firebase\JWT\JWT;

define('SECRET_KEY','customer-app-apiss');
define('ALGORITHM','HS512');
class ControllerAccountLogin extends Controller {

    private $error = array();

    public function index() {

        if (!$this->request->isAjax()) {
            $this->response->redirect( $this->url->link( 'common/home/toHome' ) );
        }

        $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_login.css');

        $this->load->model('account/customer');

        $this->customer->logout();

        // Login override for admin users
        if (!empty($this->request->get['token'])) {
            $this->trigger->fire('pre.customer.login');

            $this->customer->logout();
            $this->cart->clear();

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

            $customer_info = $this->model_account_customer->getCustomerByToken($this->request->get['token']);

            if ($customer_info && $this->customer->login($customer_info['email'], '', true)) {
                // Default Addresses
                $this->load->model('account/address');

                if ($this->config->get('config_tax_customer') == 'shipping') {
                    $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
                }

                $this->trigger->fire('post.customer.login');

                $this->response->redirect($this->url->link('account/account', '', 'SSL'));
            }
        }

        if ($this->customer->isLogged()) {
            $this->response->redirect($this->url->link('account/account', '', 'SSL'));
        }

        $this->load->language('account/login');

        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            unset($this->session->data['guest']);

            // Default Shipping Address
            $this->load->model('account/address');

            if ($this->config->get('config_tax_customer') == 'shipping') {
                $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
            }

            // Add to activity log
            $this->load->model('account/activity');

            $activity_data = array(
                'customer_id' => $this->customer->getId(),
                'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
            );

            $this->model_account_activity->addActivity('login', $activity_data);

            $data['status'] = true;
            /*$data['redirect'] = true;
            $data['redirect_link'] = $this->url->link('account/account', '', 'SSL');*/
            //$data['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');

            $this->session->data['redirect'] =  $this->url->link('checkout/checkout', '', 'SSL');

            $this->session->data['just_loggedin'] =  true;

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($data));
            /*if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
                $this->response->redirect(str_replace('&amp;', '&', $this->request->post['redirect']));
            } else {
                $this->response->redirect($this->url->link('account/account', '', 'SSL'));
            }*/
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/account', '', 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_login'),
            'href' => $this->url->link('account/login', '', 'SSL')
        );

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_new_customer'] = $this->language->get('text_new_customer');
        $data['text_register'] = $this->language->get('text_register');
        $data['text_register_account'] = $this->language->get('text_register_account');
        $data['text_returning_customer'] = $this->language->get('text_returning_customer');
        $data['text_i_am_returning_customer'] = $this->language->get('text_i_am_returning_customer');
        $data['text_forgotten'] = $this->language->get('text_forgotten');

        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_password'] = $this->language->get('entry_password');

        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_login'] = $this->language->get('button_login');
        $data['button_facebook'] = $this->language->get('button_facebook');
        $data['button_google'] = $this->language->get('button_google');
        $data['button_signin'] = $this->language->get('button_signin');


        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        //get fb login url
        require DIR_SYSTEM.'vendor/Facebook/autoload.php';
        
        $fb = new Facebook\Facebook([
            'app_id' => !empty($this->config->get('config_fb_app_id')) ? $this->config->get('config_fb_app_id') : 'randomstringforappid',
            'app_secret' => !empty($this->config->get('config_fb_secret'))? $this->config->get('config_fb_secret') : 'randomstringforappsecret',
            'default_graph_version' => 'v2.5',
            //'default_access_token' => $this->request->get['code']//'5ce6c3df96acc19c6215f2ac62d3480e', // optional
        ]);
        $helper = $fb->getRedirectLoginHelper();
        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['facebook'] = $helper->getLoginUrl($server.'index.php?path=account/facebook', array('email'));
        
        if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
            $data['redirect'] = $this->request->post['redirect'];
        } elseif (isset($this->session->data['redirect'])) {
            $data['redirect'] = $this->session->data['redirect'];

            unset($this->session->data['redirect']);
        } else {
            $data['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = false;
        }

        if (isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } else {
            $data['email'] = '';
        }

        if (isset($this->request->post['password'])) {
            $data['password'] = $this->request->post['password'];
        } else {
            $data['password'] = '';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));

        // if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/login.tpl')) {
        //     $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/login.tpl', $data));
        // } else {
        //     $this->response->setOutput($this->load->view('default/template/account/login.tpl', $data));
        // }
    }

    protected function validate() {

        $this->trigger->fire('pre.customer.login');

        // Check how many login attempts have been made.
       /* $login_info = $this->model_account_customer->getLoginAttempts($this->request->post['email']);

        if ($login_info && ($login_info['total'] >= $this->config->get('config_login_attempts')) && strtotime('-10 minute') < strtotime($login_info['date_modified'])) {
            $this->error['warning'] = $this->language->get('error_attempts');
        }*/

        // Check if customer has been approved.

        $customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);

        if ($customer_info && !$customer_info['approved']) {
           
            $this->error['warning'] = $this->language->get('error_not_verified');
        }

        
        // below one do on otp verification

        if (!$this->error) {
            if (!$this->customer->login($this->request->post['email'], $this->request->post['password'])) {
                $this->error['warning'] = $this->language->get('error_login');

                $this->model_account_customer->addLoginAttempt($this->request->post['email']);
            } else {
                $this->model_account_customer->deleteLoginAttempts($this->request->post['email']);

                $this->trigger->fire('post.customer.login');
            }
        }

        //phone number login flow

        return !$this->error;
    }

    public function login_send_otp() {

        $data['status'] = true;

        
        $this->load->model('account/customer');

        if(strpos($this->request->post['phone'], '#') == false) {

            
            if(ctype_digit($this->request->post['phone'])) {
                //phone
                $this->request->post['phone'] = preg_replace("/[^0-9]/", "", $this->request->post['phone']);

                $customer_info = $this->model_account_customer->getCustomerByPhone($this->request->post['phone']);
            } else {
                $customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);
            }




            //echo "<pre>";print_r($customer_info);die;
            if (!$customer_info) {
               
                $data['status'] = false;

                if(ctype_digit($this->request->post['phone'])) {
                    $data['error_warning'] = $this->language->get('error_phone_login');    
                } else {
                    $data['error_warning'] = $this->language->get('error_email_login');
                }
                
                // user not found
            } else {

                $data['username'] = $customer_info['firstname'];
                if($this->request->post['phone'] == '111111111') {
                    $data['otp'] = '1234';
                } else {
                    $data['otp'] = mt_rand(1000,9999);    
                }

                $data['customer_id'] = $customer_info['customer_id'];


                $sms_message = $this->emailtemplate->getSmsMessage('LoginOTP', 'loginotp_2', $data);

                if ( $this->emailtemplate->getSmsEnabled('LoginOTP','loginotp_2')) {
                    

                    $ret =  $this->emailtemplate->sendmessage($this->request->post['phone'],$sms_message);
                }

                if ($this->emailtemplate->getEmailEnabled('LoginOTP','loginotp_2')) {
                    
                    $subject = $this->emailtemplate->getSubject( 'LoginOTP', 'loginotp_2', $data );
                    $message = $this->emailtemplate->getMessage( 'LoginOTP', 'loginotp_2', $data );


                    $mail = new mail( $this->config->get( 'config_mail' ) );
                    $mail->setTo( $customer_info['email'] );
                    $mail->setFrom( $this->config->get('config_from_email') );
                    $mail->setSubject( $subject );
                    $mail->setSender( $this->config->get( 'config_name' ));
                    $mail->setHtml( $message );
                    $mail->send();
                }

                $this->model_account_customer->saveOTP($customer_info['customer_id'],$data['otp'],'login');
                //$data['success_message'] = $this->language->get('text_otp_sent') .' '. $this->request->post['phone'];
                $data['success_message'] = $this->language->get('text_otp_sent_to');
            }
        } else {
            // enter valid number throw error
            $data['status'] = false;

            $data['error_warning'] = $this->language->get('error_telephone');
        }

        

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function login_verify_otp() {

        $data['status'] = true;

        
        $this->load->model('account/customer');

        if(isset($this->request->post['verify_otp']) && isset($this->request->post['customer_id'])) {

           
            $otp_data = $this->model_account_customer->getOTP($this->request->post['customer_id'],$this->request->post['verify_otp'],'login');



            //echo "<pre>";print_r($otp_data);die;
            if (!$otp_data) {
               
                $data['status'] = false;

                $data['error_warning'] = $this->language->get('error_invalid_otp');
                // user not found
            } else {

                
                // add activity and all

                if ($this->customer->loginByPhone($this->request->post['customer_id'])) {
                    
                    $this->model_account_customer->addLoginAttempt($this->customer->getEmail());
                


                    if ($this->config->get('config_tax_customer') == 'shipping') {
                        $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
                    }

                    // Add to activity log
                    $this->load->model('account/activity');

                    $activity_data = array(
                        'customer_id' => $this->customer->getId(),
                        'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
                    );

                    $this->model_account_activity->addActivity('login', $activity_data);

                    $data['status'] = true;

                    $this->session->data['redirect'] =  $this->url->link('checkout/checkout', '', 'SSL');

                    $this->session->data['just_loggedin'] =  true;

                    // end

                    // delete otp
                    $this->model_account_customer->deleteOTP($this->request->post['verify_otp'],$this->request->post['customer_id'],'login');

                    $data['success_message'] = $this->language->get('text_valid_otp');

                }
            }
        } else {
            // enter valid number throw error
            $data['status'] = false;

            $data['error_warning'] = $this->language->get('error_invalid_otp');
        }

        

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }



    public function adminRedirectLogin() {

        $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_login.css');

        $this->load->model('account/customer');

        // Login override for admin users
        if (!empty($this->request->get['token'])) {
            $this->trigger->fire('pre.customer.login');

            $this->customer->logout();
            $this->cart->clear();

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

            $customer_info = $this->model_account_customer->getCustomerByToken($this->request->get['token']);

            if ($customer_info && $this->customer->login($customer_info['email'], '', true)) {
                // Default Addresses
                $this->load->model('account/address');

                if ($this->config->get('config_tax_customer') == 'shipping') {
                    $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
                }

                $this->trigger->fire('post.customer.login');

                $this->response->redirect($this->url->link('account/account', '', 'SSL'));
            }
        }

        if ($this->customer->isLogged()) {
            $this->response->redirect($this->url->link('account/account', '', 'SSL'));
        }

        $this->load->language('account/login');

        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            unset($this->session->data['guest']);

            // Default Shipping Address
            $this->load->model('account/address');

            if ($this->config->get('config_tax_customer') == 'shipping') {
                $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
            }

            // Add to activity log
            $this->load->model('account/activity');

            $activity_data = array(
                'customer_id' => $this->customer->getId(),
                'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
            );

            $this->model_account_activity->addActivity('login', $activity_data);

            $data['status'] = true;
            //$data['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');

            $this->session->data['redirect'] =  $this->url->link('checkout/checkout', '', 'SSL');
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($data));
            /*if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
                $this->response->redirect(str_replace('&amp;', '&', $this->request->post['redirect']));
            } else {
                $this->response->redirect($this->url->link('account/account', '', 'SSL'));
            }*/
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/account', '', 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_login'),
            'href' => $this->url->link('account/login', '', 'SSL')
        );

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_new_customer'] = $this->language->get('text_new_customer');
        $data['text_register'] = $this->language->get('text_register');
        $data['text_register_account'] = $this->language->get('text_register_account');
        $data['text_returning_customer'] = $this->language->get('text_returning_customer');
        $data['text_i_am_returning_customer'] = $this->language->get('text_i_am_returning_customer');
        $data['text_forgotten'] = $this->language->get('text_forgotten');

        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_password'] = $this->language->get('entry_password');

        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_login'] = $this->language->get('button_login');
        $data['button_facebook'] = $this->language->get('button_facebook');
        $data['button_google'] = $this->language->get('button_google');
        $data['button_signin'] = $this->language->get('button_signin');


        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        //get fb login url
        require DIR_SYSTEM.'vendor/Facebook/autoload.php';
        
        $fb = new Facebook\Facebook([
            'app_id' => !empty($this->config->get('config_fb_app_id')) ? $this->config->get('config_fb_app_id') : 'randomstringforappid',
            'app_secret' => !empty($this->config->get('config_fb_secret'))? $this->config->get('config_fb_secret') : 'randomstringforappsecret',
            'default_graph_version' => 'v2.5',
            //'default_access_token' => $this->request->get['code']//'5ce6c3df96acc19c6215f2ac62d3480e', // optional
        ]);
        $helper = $fb->getRedirectLoginHelper();
        
        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['facebook'] = $helper->getLoginUrl($server.'index.php?path=account/facebook', array('email'));
        
        if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
            $data['redirect'] = $this->request->post['redirect'];
        } elseif (isset($this->session->data['redirect'])) {
            $data['redirect'] = $this->session->data['redirect'];

            unset($this->session->data['redirect']);
        } else {
            $data['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = false;
        }

        if (isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } else {
            $data['email'] = '';
        }

        if (isset($this->request->post['password'])) {
            $data['password'] = $this->request->post['password'];
        } else {
            $data['password'] = '';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));

        // if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/login.tpl')) {
        //     $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/login.tpl', $data));
        // } else {
        //     $this->response->setOutput($this->load->view('default/template/account/login.tpl', $data));
        // }
    }


    public function autologin(){
        $res['status'] = 10022;
        $res['message'] = "Unauthorized";
        $headers = $_REQUEST['token'];
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                /*echo "<pre>";print_r($matches);die;
                return $matches[1];*/
            }
        } 
        //echo "<pre>";print_r($matches);die;
        if(count($matches) > 1 && isset($matches[1])) {
        //echo "<pre>";print_r($headers);die;

         try {
            $secretKey = base64_decode(SECRET_KEY); 
            $DecodedDataArray = JWT::decode($matches[1], $secretKey, array(ALGORITHM));

            //$log->write($DecodedDataArray);
            
            if(isset($DecodedDataArray) && isset($DecodedDataArray->data)) {
                $this->session->data['customer_id'] = $DecodedDataArray->data->id;  
                

                $customer_query = $this->db->query( "SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$DecodedDataArray->data->id . "' AND status = '1'" );

                //echo "<pre>";print_r($customer_query->row);die;
                if ( $customer_query->num_rows ) {
                    //$log->write("in customer st");
                    $this->customer->setVariables($customer_query->row);
                    $this->session->data['customer_id'] = $this->customer->getId();
                    header("Location:".BASE_URL);
                } else {
                    return $res;
                }


                //$log->write($this->customer->isLogged()."cerfxx");
                //$log->write($this->customer->getId()."cerfxx22");
                

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
     
    }
   
}
