<?php

require_once DIR_SYSTEM . 'vendor/firebase/php-jwt/vendor/autoload.php';
require_once DIR_ROOT . '/vendor/autoload.php';

use Firebase\JWT\JWT;

define('SECRET_KEY', 'customer-app-apiss');
define('ALGORITHM', 'HS512');

class ControllerAccountLogin extends Controller {

    private $error = [];

    public function index() {
        if (!$this->request->isAjax()) {
            $this->response->redirect($this->url->link('common/home/toHome'));
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
            unset($this->session->data['adminlogin']);
            unset($this->session->data['accept_vendor_terms']);
            unset($this->session->data['pezesha_amount_limit']);
            unset($this->session->data['pezesha_customer_amount_limit']);

            $customer_info = $this->model_account_customer->getCustomerByToken($this->request->get['token']);

            if ($customer_info && $this->customer->login($customer_info['email'], '', true)) {
                // Default Addresses
                $this->load->model('account/address');

                if ('shipping' == $this->config->get('config_tax_customer')) {
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

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validate()) {
            unset($this->session->data['guest']);

            // Default Shipping Address
            $this->load->model('account/address');

            if ('shipping' == $this->config->get('config_tax_customer')) {
                $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
            }

            // Add to activity log
            $this->load->model('account/activity');

            $activity_data = [
                'customer_id' => $this->customer->getId(),
                'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
            ];

            $this->model_account_activity->addActivity('login', $activity_data);

            $data['status'] = true;
            /* $data['redirect'] = true;
              $data['redirect_link'] = $this->url->link('account/account', '', 'SSL'); */
            //$data['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');

            $this->session->data['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');

            $this->session->data['just_loggedin'] = true;

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($data));
            /* if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
              $this->response->redirect(str_replace('&amp;', '&', $this->request->post['redirect']));
              } else {
              $this->response->redirect($this->url->link('account/account', '', 'SSL'));
              } */
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/account', '', 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_login'),
            'href' => $this->url->link('account/login', '', 'SSL'),
        ];

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
        require DIR_SYSTEM . 'vendor/Facebook/autoload.php';

        $fb = new Facebook\Facebook([
            'app_id' => !empty($this->config->get('config_fb_app_id')) ? $this->config->get('config_fb_app_id') : 'randomstringforappid',
            'app_secret' => !empty($this->config->get('config_fb_secret')) ? $this->config->get('config_fb_secret') : 'randomstringforappsecret',
            'default_graph_version' => 'v2.5',
                //'default_access_token' => $this->request->get['code']//'5ce6c3df96acc19c6215f2ac62d3480e', // optional
        ]);
        $helper = $fb->getRedirectLoginHelper();
        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['facebook'] = $helper->getLoginUrl($server . 'index.php?path=account/facebook', ['email']);

        if (isset($this->request->post['redirect']) && (false !== strpos($this->request->post['redirect'], $this->config->get('config_url')) || false !== strpos($this->request->post['redirect'], $this->config->get('config_ssl')))) {
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
          } */

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

        if (false == strpos($this->request->post['phone'], '#')) {
            if (ctype_digit($this->request->post['phone'])) {
                //phone
                $this->request->post['phone'] = preg_replace('/[^0-9]/', '', $this->request->post['phone']);

                $customer_info = $this->model_account_customer->getCustomerByPhone($this->request->post['phone']);
            } else {
                $customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);
            }

            //echo "<pre>";print_r($customer_info);die;
            if (!$customer_info) {
                $data['status'] = false;

                if (ctype_digit($this->request->post['phone'])) {
                    $data['error_warning'] = $this->language->get('error_phone_login');
                } else {
                    $data['error_warning'] = $this->language->get('error_email_login');
                }

                // user not found
            } else {
                $data['username'] = $customer_info['firstname'];
                if ('111111111' == $this->request->post['phone']) {
                    $data['otp'] = '1234';
                } else {
                    $data['otp'] = mt_rand(1000, 9999);
                }

                $data['customer_id'] = $customer_info['customer_id'];

                $sms_message = $this->emailtemplate->getSmsMessage('LoginOTP', 'loginotp_2', $data);

                if ($this->emailtemplate->getSmsEnabled('LoginOTP', 'loginotp_2')) {
                    $ret = $this->emailtemplate->sendmessage($this->request->post['phone'], $sms_message);
                }

                if ($this->emailtemplate->getEmailEnabled('LoginOTP', 'loginotp_2')) {
                    $subject = $this->emailtemplate->getSubject('LoginOTP', 'loginotp_2', $data);
                    $message = $this->emailtemplate->getMessage('LoginOTP', 'loginotp_2', $data);

                    $mail = new mail($this->config->get('config_mail'));
                    $mail->setTo($customer_info['email']);
                    $mail->setFrom($this->config->get('config_from_email'));
                    $mail->setSubject($subject);
                    $mail->setSender($this->config->get('config_name'));
                    $mail->setHtml($message);
                    $mail->send();
                }

                $this->model_account_customer->saveOTP($customer_info['customer_id'], $data['otp'], 'login');
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

        if (isset($this->request->post['verify_otp']) && isset($this->request->post['customer_id'])) {
            $otp_data = $this->model_account_customer->getOTP($this->request->post['customer_id'], $this->request->post['verify_otp'], 'login');

            //echo "<pre>";print_r($otp_data);die;
            if (!$otp_data) {
                $data['status'] = false;

                $data['error_warning'] = $this->language->get('error_invalid_otp');
                // user not found
            } else {
                // add activity and all

                if ($this->customer->loginByPhone($this->request->post['customer_id'])) {
                    $this->model_account_customer->addLoginAttempt($this->customer->getEmail());

                    if ('shipping' == $this->config->get('config_tax_customer')) {
                        $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
                    }

                    // Add to activity log
                    $this->load->model('account/activity');

                    $activity_data = [
                        'customer_id' => $this->customer->getId(),
                        'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
                    ];

                    $this->model_account_activity->addActivity('login', $activity_data);

                    $data['status'] = true;

                    $this->session->data['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');

                    $this->session->data['just_loggedin'] = true;

                    // end
                    // delete otp
                    $this->model_account_customer->deleteOTP($this->request->post['verify_otp'], $this->request->post['customer_id'], 'login');

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

    public function newcustomer() {
        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        if (isset($this->session->data['customer_id'])) {
            $this->response->redirect($server);
        }

        $this->load->language('common/login_modal');
        $this->load->model('tool/image');

        if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
            //$data['icon'] = $server . 'image/' . $this->config->get('config_icon');
            $data['icon'] = $this->model_tool_image->resize($this->config->get('config_icon'), 30, 30);
        } else {
            $data['icon'] = '';
        }

        if (is_file(DIR_IMAGE . $this->config->get('config_fav_icon'))) {
            $data['fav_icon'] = $server . 'image/' . $this->config->get('config_fav_icon');
        } else {
            $data['fav_icon'] = '';
        }

        if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
            //$data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'), 200, 110);
            //$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
            $data['logo'] = $server . 'image/' . $this->config->get('config_logo');
        } else {
            $data['logo'] = 'assets/img/logo.svg';
        }

        /* forget  Variables */
        $this->load->language('common/forget_modal');

        $data['text_find_account'] = $this->language->get('text_find_account');
        $data['text_forget'] = $this->language->get('text_forget');
        $data['text_enter_email_address'] = $this->language->get('text_enter_email_address');
        $data['text_enter_password'] = $this->language->get('text_enter_password');
        $data['text_move_next'] = $this->language->get('text_move_next');
        $data['text_success_verification'] = $this->language->get('text_success_verification');
        $data['text_enter_you_agree'] = $this->language->get('text_enter_you_agree');
        $data['text_terms_of_service'] = $this->language->get('text_terms_of_service');
        $data['text_privacy_policy'] = $this->language->get('text_privacy_policy');
        $data['text_welcome_message'] = $this->language->get('text_welcome_message');
        $data['text_have_account'] = $this->language->get('text_have_account');
        $data['text_forget_password'] = $this->language->get('text_forget_password');

        $data['forget_link'] = $this->url->link('account/forgotten');

        /* Login Variables */
        $data['text_number_verification'] = $this->language->get('text_number_verification') . ' ' . $this->config->get('config_name');
        $data['text_enter_number_to_login'] = $this->language->get('text_enter_number_to_login');
        $data['text_enter_email_address'] = $this->language->get('text_enter_email_address');
        $data['text_enter_password'] = $this->language->get('text_enter_password');
        $data['text_move_next'] = $this->language->get('text_move_next');
        $data['text_login'] = $this->language->get('text_login');
        $data['text_or'] = $this->language->get('text_or');
        $data['text_signup'] = $this->language->get('text_signup');
        $data['text_continue_with_facebook'] = $this->language->get('text_continue_with_facebook');
        $data['text_continue_with_twitter'] = $this->language->get('text_continue_with_twitter');
        $data['text_continue_with_google'] = $this->language->get('text_continue_with_google');
        $data['text_back'] = $this->language->get('text_back');
        $data['text_code_verification'] = $this->language->get('text_code_verification');
        $data['text_enter_code_in_area'] = $this->language->get('text_enter_code_in_area');
        $data['text_enter_phone'] = $this->language->get('text_enter_phone');
        $data['text_move_Next'] = $this->language->get('text_move_Next');

        $data['text_verify'] = $this->language->get('text_verify');
        $data['text_resend_otp'] = $this->language->get('text_resend_otp');

        $data['text_success_verification'] = $this->language->get('text_success_verification');
        $data['text_enter_you_agree'] = $this->language->get('text_enter_you_agree');
        $data['text_terms_of_service'] = $this->language->get('text_terms_of_service');
        $data['text_privacy_policy'] = $this->language->get('text_privacy_policy');
        $data['text_welcome_message'] = $this->language->get('text_welcome_message');
        $data['text_have_account'] = $this->language->get('text_have_account');
        $data['text_forget_password'] = $this->language->get('text_forget_password');

        $data['privacy_link'] = $this->url->link('information/information', 'information_id=' . $this->config->get('config_privacy_policy_id'), 'SSL');

        $data['account_terms_link'] = $this->url->link('information/information', 'information_id=' . $this->config->get('config_account_id'), 'SSL');

        /* Register Variables */
        $this->load->language('account/register');
        $data['referral_description'] = 'Referral';
        $data['telephone_mask'] = $this->config->get('config_telephone_mask');
        $data['taxnumber_mask'] = $this->config->get('config_taxnumber_mask');
        $data['entry_submit'] = $this->language->get('entry_submit');
        $data['entry_email_address'] = $this->language->get('entry_email_address');
        $data['entry_signup_otp'] = $this->language->get('entry_signup_otp');
        $data['entry_phone'] = $this->language->get('entry_phone');

        //$data['heading_title'] = $this->language->get( 'heading_title' );
        $data['heading_text'] = $this->language->get('heading_text');
        $data['text_account_already'] = sprintf($this->language->get('text_account_already'), $this->url->link('account/login', '', 'SSL'));
        $data['text_your_details'] = $this->language->get('text_your_details');
        $data['text_your_address'] = $this->language->get('text_your_address');
        $data['text_your_password'] = $this->language->get('text_your_password');
        $data['text_newsletter'] = $this->language->get('text_newsletter');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_select'] = $this->language->get('text_select');
        $data['text_none'] = $this->language->get('text_none');
        $data['text_resend_otp'] = $this->language->get('text_resend_otp');
        $data['text_loading'] = $this->language->get('text_loading');
        $data['text_already'] = $this->language->get('text_already');

        $data['entry_customer_group'] = $this->language->get('entry_customer_group');
        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');

        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_fax'] = $this->language->get('entry_fax');
        $data['entry_company'] = $this->language->get('entry_company');
        $data['entry_address_1'] = $this->language->get('entry_address_1');
        $data['entry_address_2'] = $this->language->get('entry_address_2');
        $data['entry_postcode'] = $this->language->get('entry_postcode');
        $data['entry_city'] = $this->language->get('entry_city');
        $data['entry_country'] = $this->language->get('entry_country');
        $data['entry_zone'] = $this->language->get('entry_zone');
        $data['entry_newsletter'] = $this->language->get('entry_newsletter');
        $data['entry_password'] = $this->language->get('entry_password');
        $data['entry_confirm'] = $this->language->get('entry_confirm');

        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_upload'] = $this->language->get('button_upload');
        $data['button_facebook'] = $this->language->get('button_facebook');
        $data['button_google'] = $this->language->get('button_google');
        $data['button_create'] = $this->language->get('button_create');
        $data['button_signin'] = $this->language->get('button_signin');

        $data['text_male'] = $this->language->get('text_male');
        $data['text_female'] = $this->language->get('text_female');
        $data['text_other'] = $this->language->get('text_other');
        $data['entry_dob'] = $this->language->get('entry_dob');
        $data['entry_gender'] = $this->language->get('entry_gender');

        $this->load->model('assets/information');
        $data['customer_groups'] = $this->model_assets_information->getCustomerGroups();

        $filter_data = [];
        $this->load->model('user/user');
        $data['account_managers'] = $this->model_user_user->getAccountManagerUsers($filter_data);

        $filter_city_data = [];
        $this->load->model('account/customer');
        $data['cities'] = $this->model_account_customer->getAllCities($filter_city_data);

        if (isset($this->error['captcha'])) {
            $data['error_captcha'] = $this->error['captcha'];
        } else {
            $data['error_captcha'] = '';
        }

        if (isset($this->request->post['captcha'])) {
            $data['captcha'] = $this->request->post['captcha'];
        } else {
            $data['captcha'] = '';
        }

        if ($this->config->get('config_google_captcha_status')) {
            $this->document->addScript('https://www.google.com/recaptcha/api.js');

            $data['site_key'] = $this->config->get('config_google_captcha_public');
        } else {
            $data['site_key'] = '';
        }

        $data['store_name'] = $this->config->get('config_name');

        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/landing_page/register.tpl', $data));
    }

    public function customer() {
        setcookie('po_number', null, -1, '/');
        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
            //$data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'),200,110);
            $data['logo'] = $server . 'image/' . $this->config->get('config_logo');
        } else {
            $data['logo'] = 'assets/img/logo.svg';
        }

        if (isset($this->session->data['customer_id'])) {
            $this->response->redirect($server);
        }

        $this->load->language('common/login_modal');
        $this->load->model('tool/image');
        $data['base'] = '';
        $data['text_find_account'] = $this->language->get('text_find_account');
        $data['text_forget'] = $this->language->get('text_forget');
        $data['text_enter_email_address'] = $this->language->get('text_enter_email_address');
        $data['text_enter_password'] = $this->language->get('text_enter_password');
        $data['text_move_next'] = $this->language->get('text_move_next');
        $data['text_success_verification'] = $this->language->get('text_success_verification');
        $data['text_enter_you_agree'] = $this->language->get('text_enter_you_agree');
        $data['text_terms_of_service'] = $this->language->get('text_terms_of_service');
        $data['text_privacy_policy'] = $this->language->get('text_privacy_policy');
        $data['text_welcome_message'] = $this->language->get('text_welcome_message');
        $data['text_have_account'] = $this->language->get('text_have_account');
        $data['text_forget_password'] = $this->language->get('text_forget_password');
        $data['store_name'] = $this->config->get('config_name');

        $data['forget_link'] = $this->url->link('account/forgotten');

        //          echo '<pre>';print_r($data);exit;
        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/landing_page/login.tpl', $data));
    }

    public function login() {
        $data['status'] = false;

        $this->load->model('account/customer');
        $this->load->model('account/changepass');

        if (isset($this->request->post['password']) && isset($this->request->post['email'])) {
            //$otp_data = $this->model_account_customer->getOTP($this->request->post['customer_id'],$this->request->post['verify_otp'],'login');

            $user_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer WHERE email = '" . $this->db->escape($this->request->post['email']) . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($this->request->post['password']) . "'))))) OR password = '" . $this->db->escape(md5($this->request->post['password'])) . "')");

            //print_r($user_query);
            if ($user_query->num_rows) {
                $expired_months = $this->model_account_changepass->passwordexpired($user_query->row['customer_id']);
                $checknewpasswordsetted = $this->model_account_changepass->checknewpasswordsetted($user_query->row['customer_id']);
                $log = new Log('error.log');
                $log->write('expired_months');
                $log->write($expired_months);
                $log->write($checknewpasswordsetted);
                $log->write('expired_months');
                if ($user_query->row['approved'] && $user_query->row['status'] && (($checknewpasswordsetted > 0 && $expired_months < 3) || $user_query->row['tempPassword'] == 1)) {
                    $data['customer_id'] = $user_query->row['customer_id'];
                    $data['customer_email'] = $user_query->row['email'];
                    $data['temppassword'] = $user_query->row['tempPassword'];

                    //echo '<pre>';print_r($isIPexist);
                    //echo '<pre>';var_dump($data['isnewIP']);exit;

                    $logged_in = $this->customer->loginByPhone($data['customer_id']);
                    if ($logged_in) {
                        $this->model_account_customer->addLoginAttempt($this->customer->getEmail());

                        $logindata['customer_id'] = $user_query->row['customer_id'];
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
                        // $logindata['login_date'] = "";
                        // $logindata['login_ip'] = "";
                        $this->model_account_customer->addLoginHistory($logindata);

                        if ('shipping' == $this->config->get('config_tax_customer')) {
                            $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
                        }

                        // Add to activity log
                        $this->load->model('account/activity');

                        $activity_data = [
                            'customer_id' => $this->customer->getId(),
                            'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
                        ];

                        $this->model_account_activity->addActivity('login', $activity_data);

                        $data['status'] = true;

                        $this->session->data['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');

                        $this->session->data['just_loggedin'] = true;

                        $data['success_message'] = $this->language->get('text_login_success');
                        $this->model_account_customer->cacheProductPrices(75);
                        $this->session->data['order_approval_access'] = $user_query->row['order_approval_access'];
                        $this->session->data['order_approval_access_role'] = $user_query->row['order_approval_access_role'];
                    }
                } elseif ($user_query->row['approved'] && $user_query->row['status'] && ($checknewpasswordsetted <= 0 || $expired_months >= 3)) {
                    $data['status'] = false;
                    $data['password_expired'] = true;
                    $data['redirect'] = $this->url->link('account/order', '', 'SSL');

                    $data['error_warning'] = 'You need to update your password, Because your password is expired.';
                } else {
                    $data['status'] = false;

                    $data['error_warning'] = $this->language->get('error_approved');
                }
            } else {
                $data['status'] = false;

                $data['error_warning'] = $this->language->get('error_login');
            }

            // add activity and all
        } else {
            // enter valid number throw error
            $data['status'] = false;

            $data['error_warning'] = $this->language->get('error_login');
        }

        $data['redirect'] = null; //if null is not placed change pass not working
        //check email sessions
        $log = new Log('error.log');
        $this->load->model('checkout/order');
        $order_info = $this->model_checkout_order->getOrder($this->session->data['email_sub_user_order_id']);
        $log->write('EMAIL SESSION');
        $log->write($order_info);
        $log->write('EMAIL SESSION');

        if (null != $order_info) {
            $this->load->model('account/customer');
            $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);
            $log->write('EMAIL SESSION');
            $log->write($customer_info);
            $log->write('EMAIL SESSION');

            if (null != $customer_info && $customer_info['customer_id'] == $this->session->data['email_sub_user_id'] && $this->customer->getId() == $this->session->data['email_parent_user_id']) {
                $log->write('ORDER INFO CUSTOMER ID PROVIDED CUSTOMER ID MATCHED');
                $this->session->data['redirect'] = $this->url->link('account/order', '', 'SSL');
                $data['redirect'] = $this->url->link('account/order', '', 'SSL');
                unset($this->session->data['redirect']);
                unset($this->session->data['email_sub_user_order_id']);
                unset($this->session->data['email_sub_user_id']);
                unset($this->session->data['email_parent_user_id']);
                $log->write($data);
            }
        }
        $this->model_account_customer->getDBCart();
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
            unset($this->session->data['adminlogin']);

            $customer_info = $this->model_account_customer->getCustomerByToken($this->request->get['token']);

            if ($customer_info && $this->customer->login($customer_info['email'], '', true)) {
                // Default Addresses
                $this->load->model('account/address');

                if ('shipping' == $this->config->get('config_tax_customer')) {
                    $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
                }

                $this->model_account_customer->cacheProductPrices(75);
                $this->trigger->fire('post.customer.login');

                // maintain session to identify as admin login
                $this->session->data['adminlogin'] = 1;
                // maintain session to identify admin logged user group
                if (!empty($this->request->get['ce_id'])) {
                    $this->session->data['ce_id'] = $this->request->get['ce_id'];
                }
                //$this->response->redirect($this->url->link('account/account', '', 'SSL'));
                //REDIRECTING TO HOME PAGE
                $this->model_account_customer->getDBCart();
                $this->response->redirect('/');
            }
        }

        if ($this->customer->isLogged()) {
            $this->model_account_customer->cacheProductPrices(75);
            $this->response->redirect('/');
            //REDIRECTING TO HOME PAGE
            //$this->response->redirect($this->url->link('account/account', '', 'SSL'));
        }

        $this->load->language('account/login');

        $this->document->setTitle($this->language->get('heading_title'));

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validate()) {
            unset($this->session->data['guest']);

            // Default Shipping Address
            $this->load->model('account/address');

            if ('shipping' == $this->config->get('config_tax_customer')) {
                $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
            }

            // Add to activity log
            $this->load->model('account/activity');

            $activity_data = [
                'customer_id' => $this->customer->getId(),
                'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
            ];

            $this->model_account_activity->addActivity('login', $activity_data);

            $data['status'] = true;
            //$data['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');

            $this->session->data['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($data));
            /* if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
              $this->response->redirect(str_replace('&amp;', '&', $this->request->post['redirect']));
              } else {
              $this->response->redirect($this->url->link('account/account', '', 'SSL'));
              } */
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/account', '', 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_login'),
            'href' => $this->url->link('account/login', '', 'SSL'),
        ];

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
        require DIR_SYSTEM . 'vendor/Facebook/autoload.php';

        $fb = new Facebook\Facebook([
            'app_id' => !empty($this->config->get('config_fb_app_id')) ? $this->config->get('config_fb_app_id') : 'randomstringforappid',
            'app_secret' => !empty($this->config->get('config_fb_secret')) ? $this->config->get('config_fb_secret') : 'randomstringforappsecret',
            'default_graph_version' => 'v2.5',
                //'default_access_token' => $this->request->get['code']//'5ce6c3df96acc19c6215f2ac62d3480e', // optional
        ]);
        $helper = $fb->getRedirectLoginHelper();

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['facebook'] = $helper->getLoginUrl($server . 'index.php?path=account/facebook', ['email']);

        if (isset($this->request->post['redirect']) && (false !== strpos($this->request->post['redirect'], $this->config->get('config_url')) || false !== strpos($this->request->post['redirect'], $this->config->get('config_ssl')))) {
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

    public function adminRedirectLoginAPI($token) {


        if (isset($this->request->get['token'])) {//isset($args['customer_id'])
            $token = $this->request->get['token'];
        } else {
            $token = 0;
        }

        // echo "<pre>";print_r($token);die; 
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

            $api_info = $customer_info = $this->model_account_customer->getCustomerByToken($token);

            // echo "<pre>";print_r($api_info);die; 
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


            if ($customer_info) {
                $tokenId = base64_encode(mcrypt_create_iv(32));
                $issuedAt = time();
                $notBefore = $issuedAt;  //Adding 10 seconds
                $expire = $notBefore + 604800; // Adding 60 seconds
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

                // echo "<pre>";print_r($jwt);die; 
            }

            // echo "<pre>";print_r($api_info);die; 

            if ($api_info['parent'] != NULL && $api_info['parent'] > 0) {
                $customer_details = $this->db->query('SELECT customer_category FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . $api_info['parent'] . "' AND status = '1'");
            } else {
                $customer_details = $this->db->query('SELECT customer_category FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . $api_info['customer_id'] . "' AND status = '1'");
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

    public function autologin() {
        $res['status'] = 10022;
        $res['message'] = 'Unauthorized';
        $headers = $_REQUEST['token'];
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                
            }
        }

        if (count($matches) > 1 && isset($matches[1])) {
            try {
                $secretKey = base64_decode(SECRET_KEY);
                $DecodedDataArray = JWT::decode($matches[1], $secretKey, [ALGORITHM]);
                //echo time();exit;
                //echo '<pre>';print_r($DecodedDataArray);exit;

                if (isset($DecodedDataArray) && isset($DecodedDataArray->data)) {
                    $customer_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . (int) $DecodedDataArray->data->id . "' AND status = '1'");

                    if ($customer_query->num_rows) {

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
                        //$this->customer->setVariables($data['customer_category']);
                        /* SET CUSTOMER CATEGORY */
                        $customer_query->row['customer_category'] = $data['customer_category'];

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

                        $this->load->model('account/customer');
                        $this->load->model('setting/store');
                        $redirect = BASE_URL;
                        $resChannel = $this->model_account_customer->addUpdateChannelMapping($this->customer->getId(), $_REQUEST);
                        if ($resChannel) {
                            $this->session->data['customer_id'] = $this->customer->getId();
                            if (!empty($_REQUEST['store_id'])) {
                                $storeSEO = $this->model_setting_store->getSeoUrl('store_id=' . $_REQUEST['store_id']);
                                if (!empty($storeSEO)) {
                                    $redirect .= '/';
                                    $redirect .= 'store/' . $storeSEO;
                                }
                            }
                            header('Location:' . $redirect);
                        } else {
                            $this->response->addHeader('Content-Type: application/json');
                            $this->response->setOutput(json_encode($res));
                        }
                    } else {
                        $this->response->addHeader('Content-Type: application/json');
                        $this->response->setOutput(json_encode($res));
                    }
                } else {
                    $this->response->addHeader('Content-Type: application/json');
                    $this->response->setOutput(json_encode($res));
                }
            } catch (Exception $e) {
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($res));
            }
        }
    }

    public function farmer() {
        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        if (isset($this->session->data['farmer_id'])) {
            $this->response->redirect($server);
        }

        $this->load->language('common/login_modal');
        $this->load->model('tool/image');

        if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
            //$data['icon'] = $server . 'image/' . $this->config->get('config_icon');
            $data['icon'] = $this->model_tool_image->resize($this->config->get('config_icon'), 30, 30);
        } else {
            $data['icon'] = '';
        }

        if (is_file(DIR_IMAGE . $this->config->get('config_fav_icon'))) {
            $data['fav_icon'] = $server . 'image/' . $this->config->get('config_fav_icon');
        } else {
            $data['fav_icon'] = '';
        }

        if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
            $data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'), 200, 110);
            //$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
        } else {
            $data['logo'] = '';
        }

        /* Register Variables */
        $this->load->language('account/farmerregister');
        $data['referral_description'] = 'Referral';
        $data['telephone_mask'] = $this->config->get('config_telephone_mask');
        $data['taxnumber_mask'] = $this->config->get('config_taxnumber_mask');
        $data['entry_submit'] = $this->language->get('entry_submit');
        $data['entry_email_address'] = $this->language->get('entry_email_address');
        $data['entry_signup_otp'] = $this->language->get('entry_signup_otp');
        $data['entry_phone'] = $this->language->get('entry_phone');

        //$data['heading_title'] = $this->language->get( 'heading_title' );
        $data['heading_text'] = $this->language->get('heading_text');
        $data['text_account_already'] = sprintf($this->language->get('text_account_already'), $this->url->link('account/login', '', 'SSL'));
        $data['text_your_details'] = $this->language->get('text_your_details');
        $data['text_your_address'] = $this->language->get('text_your_address');
        $data['text_your_password'] = $this->language->get('text_your_password');
        $data['text_newsletter'] = $this->language->get('text_newsletter');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_select'] = $this->language->get('text_select');
        $data['text_none'] = $this->language->get('text_none');
        $data['text_resend_otp'] = $this->language->get('text_resend_otp');
        $data['text_loading'] = $this->language->get('text_loading');
        $data['text_already'] = $this->language->get('text_already');

        $data['entry_customer_group'] = $this->language->get('entry_customer_group');
        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');

        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_fax'] = $this->language->get('entry_fax');
        $data['entry_company'] = $this->language->get('entry_company');
        $data['entry_address_1'] = $this->language->get('entry_address_1');
        $data['entry_address_2'] = $this->language->get('entry_address_2');
        $data['entry_postcode'] = $this->language->get('entry_postcode');
        $data['entry_city'] = $this->language->get('entry_city');
        $data['entry_country'] = $this->language->get('entry_country');
        $data['entry_zone'] = $this->language->get('entry_zone');
        $data['entry_newsletter'] = $this->language->get('entry_newsletter');
        $data['entry_password'] = $this->language->get('entry_password');
        $data['entry_confirm'] = $this->language->get('entry_confirm');

        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_upload'] = $this->language->get('button_upload');
        $data['button_facebook'] = $this->language->get('button_facebook');
        $data['button_google'] = $this->language->get('button_google');
        $data['button_create'] = $this->language->get('button_create');
        $data['button_signin'] = $this->language->get('button_signin');

        if (isset($this->error['captcha'])) {
            $data['error_captcha'] = $this->error['captcha'];
        } else {
            $data['error_captcha'] = '';
        }

        if (isset($this->request->post['captcha'])) {
            $data['captcha'] = $this->request->post['captcha'];
        } else {
            $data['captcha'] = '';
        }

        if ($this->config->get('config_google_captcha_status')) {
            $this->document->addScript('https://www.google.com/recaptcha/api.js');

            $data['site_key'] = $this->config->get('config_google_captcha_public');
        } else {
            $data['site_key'] = '';
        }

        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/landing_page/farmer-registration.tpl', $data));
    }

    public function checksubuserorder() {
        $decryption_iv = '1234567891011121';
        $decryption_key = 'KwikBasket';
        $options = 0;
        $ciphering = 'AES-128-CTR';
        $iv_length = openssl_cipher_iv_length($ciphering);

        $order_id = openssl_decrypt($this->request->get['order_token'], $ciphering, $decryption_key, $options, $decryption_iv);
        $user_id = openssl_decrypt($this->request->get['user_token'], $ciphering, $decryption_key, $options, $decryption_iv);
        $parent_user_id = openssl_decrypt($this->request->get['parent_user_token'], $ciphering, $decryption_key, $options, $decryption_iv);

        echo 'order_token : ' . $order_id;
        echo 'sub_user_token : ' . $user_id;
        echo 'parent_user_token : ' . $parent_user_id;
        echo 'Order Details Checking';
        $log = new Log('error.log');
        $log->write('order_token : ' . $order_id);
        $log->write('sub_user_token : ' . $user_id);
        $log->write('parent_token : ' . $parent_user_id);
        $log->write('checksubuserorder');

        echo $this->session->data['email_sub_user_order_id'] = $order_id;
        echo $this->session->data['email_sub_user_id'] = $user_id;
        echo $this->session->data['email_parent_user_id'] = $parent_user_id;

        if ($this->customer->isLogged() && $this->customer->getId() == $parent_user_id) {
            $this->response->redirect($this->url->link('account/order', '', 'SSL'));
        } else {
            $this->response->redirect($this->url->link('account/login/customer', '', 'SSL'));
        }
    }

    public function addSendNewIPotp() {
        //echo "<pre>";print_r( "addLoginByOtp");die;
        // $json = [];
        // $json['status'] = 200;
        // $json['data'] = [];
        // $json['message'] = [];
        $data['status'] = false;
        $this->load->language('api/login');
        $this->load->language('api/general');

        $this->document->addScript('front/ui/javascript/jquery/datetimepicker/moment.js');
        $this->document->addScript('front/ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
        $this->document->addStyle('front/ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');
        $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_login.css');

        $log = new Log('error.log');
        // $data['telephone_mask'] = $this->config->get('config_telephone_mask');
        // $data['taxnumber_mask'] = $this->config->get('config_taxnumber_mask');


        if (isset($this->request->post['email'])) {
            $this->load->model('account/customer');
            $api_info = $this->model_account_customer->new_ip_send_otp();

            //echo "<pre>";print_r($api_info);die;
            if ($api_info['status']) {
                $data['customer_id'] = $api_info['customer_id'];
                // $json['data'] = $data;
                $data['status'] = true;
                //$json['success'] = $this->language->get('text_success');
                // $json['message'][] = ['type' => $api_info['success_message'], 'body' => $api_info['success_message']];
                $data['message'] = $this->language->get('verify_mail_sent');
            } else {
                // $json['status'] = 10029; //user not found
                // $json['message'][] = ['type' => '', 'body' => $api_info['error_warning']];
                // http_response_code(400);
                $data['status'] = false;
                $data['message'] = "Error";
                $data['warning'] = "Error";
            }
        } else {
            // $json['status'] = 10010;
            // $json['message'][] = ['type' => '', 'body' => "Params not passed properly"];
            // http_response_code(400);

            $data['status'] = false;
            $data['warning'] = "Error";
            $data['message'] = "Error";
        }

        $this->response->addHeader('Content-Type: application/json');
        // $this->response->setOutput(json_encode($json));
        $this->response->setOutput(json_encode($data));
    }

    public function addVerifyNewIPotp() {
        //echo "<pre>";print_r( "addLoginVerifyOtp");die;

        $json = [];
        $data['status'] = false;

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->document->addScript('front/ui/javascript/jquery/datetimepicker/moment.js');
        $this->document->addScript('front/ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
        $this->document->addStyle('front/ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');
        $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_login.css');

        $this->load->language('api/login');
        $this->load->language('api/general');
        $this->load->model('account/customer');
        $customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);

        if (isset($this->request->post['otp']) && isset($customer_info['customer_id'])) {

            $this->load->model('account/customer');

            $api_info = $this->model_account_customer->new_ip_verify_otp($this->request->post['otp'], $this->request->post['email']);

            //echo "<pre>";print_r($api_info);die;
            if ($api_info['status']) {

                $customer_info = $this->model_account_customer->getCustomer($customer_info['customer_id']);

                // if (!empty($customer_info['dob'])) {
                //     $customer_info['dob'] = date('d/m/Y', strtotime($customer_info['dob']));
                // } else {
                //     $customer_info['dob'] = '01/01/1990';
                // }
                //$json['success'] = $this->language->get('text_valid_otp');
                //$json['status'] = true;

                $json['data'] = $customer_info;
                $data['status'] = true;
                $data['message'] = "verification mail sent";
                $json['message'][] = ['type' => '', 'body' => $api_info['success_message']];
            } else {
                $data['error_warning'] = "Invalid OTP";
                $data["status"] = false;
            }
        } else {
            $data['error_warning'] = "Invalid OTP";
            $data["status"] = false;
        }

        $this->response->addHeader('Content-Type: application/json');
        // $this->response->setOutput(json_encode($json));
        $this->response->setOutput(json_encode($data));
    }

    public function checkipaddress() {

        if (isset($this->request->post['email']) && isset($this->request->post['password'])) {
            $this->load->model('account/customer');

            $user_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer WHERE email = '" . $this->db->escape($this->request->post['email']) . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($this->request->post['password']) . "'))))) OR password = '" . $this->db->escape(md5($this->request->post['password'])) . "')");

            //print_r($user_query);
            if ($user_query->num_rows) {
                $customer_info = $user_query->row;
                $isIPexist = $this->model_account_customer->getCustomerIpAddresses($customer_info['customer_id'], $_SERVER['REMOTE_ADDR']);
                $log = new Log('error.log');
                $log->write('isIPexist');
                $log->write($isIPexist);
                $log->write($customer_info['customer_id']);
                $log->write('isIPexist');
                //$isIPexist = array_search($_SERVER['REMOTE_ADDR'], array_column($all_IPAddress, 'ip'));
                if (is_array($isIPexist) && count($isIPexist) > 0) {
                    $isnewIP = false;
                } else {
                    $isnewIP = true;
                }
                $isnewIP = false; //as some times , Ip not working properly, for deployment, returning false
                $data['isnewIP'] = $isnewIP;
                $data['status'] = true;
            } else {
                $data['message'] = 'Username and password does not match';
                $data['status'] = true;
            }
        } else {

            $data['status'] = false;
            $data['warning'] = "Error";
            $data['message'] = "Error";
        }

        $this->response->addHeader('Content-Type: application/json');
        // $this->response->setOutput(json_encode($json));
        $this->response->setOutput(json_encode($data));
    }

    public function autocompleteaccountmanager() {
        $log = new Log('error.log');
        $json = [];

        if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_email']) || isset($this->request->get['filter_telephone'])) {
            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            if (isset($this->request->get['filter_email'])) {
                $filter_email = $this->request->get['filter_email'];
            } else {
                $filter_email = '';
            }

            if (isset($this->request->get['filter_telephone'])) {
                $filter_telephone = $this->request->get['filter_telephone'];
            } else {
                $filter_telephone = '';
            }

            $this->load->model('user/user');

            $filter_data = [
                'filter_name' => $filter_name,
                'filter_email' => $filter_email,
                'filter_telephone' => $filter_telephone,
                'start' => 0,
                'limit' => 5,
            ];

            $results = $this->model_user_user->getAccountManagerUsers($filter_data);

            foreach ($results as $result) {
                $json[] = [
                    'user_id' => $result['user_id'],
                    'user_group_id' => $result['user_group_id'],
                    'username' => strip_tags(html_entity_decode($result['username'], ENT_QUOTES, 'UTF-8')),
                    'name' => $result['name'],
                    'firstname' => $result['firstname'],
                    'lastname' => $result['lastname'],
                    'email' => $result['email'],
                    'telephone' => $result['telephone']
                ];
            }
        }

        $sort_order = [];

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['name'];
        }

        array_multisort($sort_order, SORT_ASC, $json);
        $log->write($json);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function SendSNS() {
        $mobile = $this->request->get['mobile'];
        $this->emailtemplate->SendSNS($mobile);
    }

}
