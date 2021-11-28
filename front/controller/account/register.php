<?php

class ControllerAccountRegister extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('account/register');

        //$this->document->setTitle( $this->language->get( 'heading_title' ) );

        $data['referral_description'] = 'Referral'; //$this->language->get( 'referral_description' );

        $this->document->addScript('front/ui/javascript/jquery/datetimepicker/moment.js');
        $this->document->addScript('front/ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
        $this->document->addStyle('front/ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');
        $this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_login.css');

        $this->load->model('account/customer');

        $log = new Log('error.log');
        //$log->write("outside form");

        $data['telephone_mask'] = $this->config->get('config_telephone_mask');

        $data['taxnumber_mask'] = $this->config->get('config_taxnumber_mask');

        //echo "<pre>";print_r($this->request->post);die;
        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validate()) {
            $date = $this->request->post['dob'];

            $log = new Log('error.log');
            $log->write('register');

            $referee_user_id = null;

            if (count($_COOKIE) > 0 && isset($_COOKIE['referral']) && ('expired' != $_COOKIE['referral'])) {
                //echo "Cookies are enabled.";
                $this->request->post['referee_user_id'] = $_COOKIE['referral'];
                $referee_user_id = $_COOKIE['referral'];

                setcookie('referral', null, time() - 3600, '/');
                //unset($_COOKIE['referral']);
            }
            //refer=abhishekchaurasia!@1
            //hishekchaurasia%21%401
            //echo "<pre>";print_r($referee_user_id);die;

            if (isset($date)) {
                $date = DateTime::createFromFormat('d/m/Y', $date);

                $this->request->post['dob'] = $date->format('Y-m-d');
            } else {
                $this->request->post['dob'] = null;
            }

            $log->write('in post signup 0.1');

            $customer_id = $this->model_account_customer->addCustomer($this->request->post);

            $log->write('in post signup 0.2');
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

            $log->write('in post signup 1');
            $this->model_account_activity->addActivity('register', $activity_data);

            /* If not able to login*/
            $data['status'] = true;

            if (!$logged_in) {
                $data['status'] = false;
            }
            $data['text_new_signup_reward'] = $this->language->get('text_new_signup_reward');
            $data['text_new_signup_credit'] = $this->language->get('text_new_signup_credit');

            $data['message'] = $this->language->get('verify_mail_sent');

            if (isset($referee_user_id)) {
                $config_reward_enabled = $this->config->get('config_reward_enabled');

                $config_credit_enabled = $this->config->get('config_credit_enabled');

                $config_refer_type = $this->config->get('config_refer_type');

                $config_refered_points = $this->config->get('config_refered_points');
                $config_referee_points = $this->config->get('config_referee_points');

                $log->write($customer_id);
                $log->write($config_refer_type);
                $log->write($referee_user_id.' referee_user_id');

                $log->write($config_referee_points);
                $log->write($config_refered_points);

                if ('reward' == $config_refer_type) {
                    $log->write($config_reward_enabled);

                    if ($config_reward_enabled && $config_refered_points && $config_referee_points) {
                        $log->write('if');

                        //referred points below
                        $this->model_account_activity->addCustomerReward($customer_id, $config_refered_points, $data['referral_description']);

                        //referee points below
            //$this->model_account_activity->addCustomerReward( $referee_user_id,$config_referee_points,$data['referral_description'] );
                    }
                } elseif ('credit' == $config_refer_type) {
                    $log->write('credit if');

                    if ($config_credit_enabled && $config_refered_points && $config_referee_points) {
                        //referred points below
                        $this->model_account_activity->addCredit($customer_id, $data['referral_description'], $config_refered_points);

                        //referee points below
            //$this->model_account_activity->addCredit($referee_user_id, $data['referral_description'] , $config_referee_points);
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

            // otp send

            // otp send end

            $log->write('in post signup');

            $data['redirect'] = $this->url->link('account/account', '', 'SSL');

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($data));
        } else {
            $log->write('outside form 3nr dime');

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
        'text' => $this->language->get('text_register'),
        'href' => $this->url->link('account/register', '', 'SSL'),
      ];

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

            if (isset($this->request->post['gender'])) {
                $data['gender'] = $this->request->post['gender'];
            } elseif (!empty($customer_info)) {
                $data['gender'] = $customer_info['gender'];
            } else {
                $data['gender'] = '';
            }

            if (isset($this->request->post['dob'])) {
                $data['dob'] = date('d/m/Y', strtotime($this->request->post['dob']));
            } elseif (!empty($customer_info['dob'])) {
                $data['dob'] = date('d/m/Y', strtotime($customer_info['dob']));
            } else {
                $data['dob'] = '';
            }

            if (isset($this->error['warning'])) {
                $data['error_warning'] = $this->error['warning'];
            } else {
                $data['error_warning'] = false;
            }

            if (isset($this->error['firstname'])) {
                $data['error_firstname'] = $this->error['firstname'];
            } else {
                $data['error_firstname'] = false;
            }

            if (isset($this->error['lastname'])) {
                $data['error_lastname'] = $this->error['lastname'];
            } else {
                $data['error_lastname'] = false;
            }

            if (isset($this->error['email'])) {
                $data['error_email'] = $this->error['email'];
            } else {
                $data['error_email'] = false;
            }

            if (isset($this->error['company_name_address'])) {
                $data['error_company_name_address'] = $this->error['company_name_address'];
            } else {
                $data['error_company_name_address'] = false;
            }

            /*if ( isset( $this->error['company_name'] ) ) {
              $data['error_company_name'] = $this->error['company_name'];
            } else {
              $data['error_company_name'] = false;
            }

            if ( isset( $this->error['company_address'] ) ) {
              $data['error_company_address'] = $this->error['company_address'];
            } else {
              $data['error_company_address'] = false;
            }*/

            if (isset($this->error['telephone'])) {
                $data['error_telephone'] = $this->error['telephone'];
            } else {
                $data['error_telephone'] = false;
            }

            if (isset($this->error['telephone_exists'])) {
                $data['error_telephone_exists'] = $this->error['telephone_exists'];
            } else {
                $data['error_telephone_exists'] = false;
            }

            if (isset($this->error['error_tax'])) {
                $data['error_tax'] = $this->error['error_tax'];
            } else {
                $data['error_tax'] = false;
            }

            if (isset($this->error['gender'])) {
                $data['error_gender'] = $this->error['gender'];
            } else {
                $data['error_gender'] = false;
            }

            if (isset($this->error['dob'])) {
                $data['error_dob'] = $this->error['dob'];
            } else {
                $data['error_dob'] = false;
            }

            if (isset($this->error['address_1'])) {
                $data['error_address_1'] = $this->error['address_1'];
            } else {
                $data['error_address_1'] = false;
            }

            if (isset($this->error['city'])) {
                $data['error_city'] = $this->error['city'];
            } else {
                $data['error_city'] = false;
            }

            if (isset($this->error['postcode'])) {
                $data['error_postcode'] = $this->error['postcode'];
            } else {
                $data['error_postcode'] = false;
            }

            if (isset($this->error['country'])) {
                $data['error_country'] = $this->error['country'];
            } else {
                $data['error_country'] = false;
            }

            if (isset($this->error['zone'])) {
                $data['error_zone'] = $this->error['zone'];
            } else {
                $data['error_zone'] = false;
            }

            if (isset($this->error['custom_field'])) {
                $data['error_custom_field'] = $this->error['custom_field'];
            } else {
                $data['error_custom_field'] = [];
            }

            if (isset($this->error['password'])) {
                $data['error_password'] = $this->error['password'];
            } else {
                $data['error_password'] = false;
            }

            if (isset($this->error['confirm'])) {
                $data['error_confirm'] = $this->error['confirm'];
            } else {
                $data['error_confirm'] = false;
            }

            $data['action'] = $this->url->link('account/register', '', 'SSL');

            if (isset($this->request->post['firstname'])) {
                $data['firstname'] = $this->request->post['firstname'];
            } else {
                $data['firstname'] = '';
            }

            if (isset($this->request->post['lastname'])) {
                $data['lastname'] = $this->request->post['lastname'];
            } else {
                $data['lastname'] = '';
            }

            if (isset($this->request->post['email'])) {
                $data['email'] = $this->request->post['email'];
            } else {
                $data['email'] = '';
            }

            if (isset($this->request->post['telephone'])) {
                $data['telephone'] = $this->request->post['telephone'];
            } else {
                $data['telephone'] = '';
            }

            if (isset($this->request->post['fax'])) {
                $data['fax'] = $this->request->post['fax'];
            } else {
                $data['fax'] = '';
            }

            if (isset($this->request->post['company'])) {
                $data['company'] = $this->request->post['company'];
            } else {
                $data['company'] = '';
            }

            if (isset($this->request->post['password'])) {
                $data['password'] = $this->request->post['password'];
            } else {
                $data['password'] = '';
            }

            if (isset($this->request->post['confirm'])) {
                $data['confirm'] = $this->request->post['confirm'];
            } else {
                $data['confirm'] = '';
            }

            if (isset($this->request->post['newsletter'])) {
                $data['newsletter'] = $this->request->post['newsletter'];
            } else {
                $data['newsletter'] = '';
            }

            if ($this->config->get('config_account_id')) {
                $this->load->model('assets/information');

                $information_info = $this->model_assets_information->getInformation($this->config->get('config_account_id'));

                if ($information_info) {
                    $data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id='.$this->config->get('config_account_id'), 'SSL'), $information_info['title'], $information_info['title']);
                } else {
                    $data['text_agree'] = '';
                }
            } else {
                $data['text_agree'] = '';
            }

            if (isset($this->request->post['agree'])) {
                $data['agree'] = $this->request->post['agree'];
            } else {
                $data['agree'] = false;
            }

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');

            if (('POST' == $this->request->server['REQUEST_METHOD'])) {
                if (!$this->validate()) {
                    $data['status'] = false;

                    $this->response->addHeader('Content-Type: application/json');
                    $this->response->setOutput(json_encode($data));
                } else {
                    $data['status'] = true;
                    $data['redirect'] = $this->url->link('account/account', '', 'SSL');

                    $this->response->addHeader('Content-Type: application/json');
                    $this->response->setOutput(json_encode($data));
                }
            }

            $data['customer_groups'] = $this->model_assets_information->getCustomerGroups();
            //echo '<pre>';print_r($data['customer_groups']);exit;
            
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

            $log->write('outside form 3253nr dime');

            if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/account/register.tpl')) {
                $log->write('return view form');

                return $this->load->view($this->config->get('config_template').'/template/account/register.tpl', $data);
            } else {
                $log->write('return view form default');
                $this->response->setOutput($this->load->view('default/template/account/register.tpl', $data));
                //return $this->load->view( 'default/template/account/register.tpl', $data);
            }
        }
    }

    public function validate()
    {
        if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
            $this->error['firstname'] = $this->language->get('error_firstname');
        }

        /*if ( ( utf8_strlen( trim( $this->request->post['lastname'] ) ) < 1 ) || ( utf8_strlen( trim( $this->request->post['lastname'] ) ) > 32 ) ) {
          $this->error['lastname'] = $this->language->get( 'error_lastname' );
        }*/

        if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if ((utf8_strlen(trim($this->request->post['password'])) < 1) || (utf8_strlen(trim($this->request->post['password'])) > 32)) {
            $this->error['password'] = $this->language->get('error_password');
        }

        if ((utf8_strlen(trim($this->request->post['confirm'])) < 1) || (utf8_strlen(trim($this->request->post['confirm'])) > 32)) {
            $this->error['confirm'] = $this->language->get('error_confirm');
        }

        if ((trim($this->request->post['password'])) != (trim($this->request->post['confirm']))) {
            $this->error['match'] = $this->language->get('error_match_password');
        }

        if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
            //$this->error['warning'] = $this->language->get( 'error_exists' );

            $numb = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);

            if (isset($numb['telephone'])) {
                $this->error['warning'] = sprintf($this->language->get('error_exists_email'), $numb['telephone']);
            } else {
                $this->error['warning'] = $this->language->get('error_exists');
            }
        }

        if (false !== strpos($this->request->post['telephone'], '#') || empty($this->request->post['telephone'])) {
            $this->error['telephone'] = $this->language->get('error_telephone');
        }

        $this->request->post['telephone'] = preg_replace('/[^0-9]/', '', $this->request->post['telephone']);

        //echo "<pre>";print_r($this->request->post);die;

        if ($this->model_account_customer->getTotalCustomersByPhone($this->request->post['telephone'])) {
            $this->error['telephone_exists'] = $this->language->get('error_telephone_exists');
        }

        /*if (  $this->request->post['customer_group_id']  > 1 && ( ( utf8_strlen( trim( $this->request->post['company_name'] ) ) < 1 ) || ( utf8_strlen( trim( $this->request->post['company_address'] ) ) < 1 ) ) ) {
          $this->error['company_name_address'] = $this->language->get( 'error_company_name_address' );
        }*/

        if ((utf8_strlen(trim($this->request->post['company_name'])) < 1)) {
            $this->error['company_name'] = $this->language->get('error_company_name');
        }

        if ((utf8_strlen(trim($this->request->post['company_address'])) < 1)) {
            $this->error['company_address'] = $this->language->get('error_company_address');
        }

        /*if (( utf8_strlen( trim( $this->request->post['address'] ) ) < 1 )) {
          $this->error['address'] = $this->language->get( 'error_address' );
        }*/

        if ((utf8_strlen(trim($this->request->post['house_building'])) < 1)) {
            $this->error['house_building'] = $this->language->get('error_house_building');
        }

        if ((utf8_strlen(trim($this->request->post['location'])) < 1)) {
            $this->error['location'] = $this->language->get('error_location');
        }
        
        $log = new Log('error.log');
        /*if ($this->request->post['accountmanagername'] != NULL && $this->request->post['accountmanagerid'] != NULL && $this->request->post['accountmanagerid'] > 0 && $this->model_account_customer->getTotalAccountManagersByNameAndId($this->request->post['accountmanagername'], $this->request->post['accountmanagerid']) == 0) {
            $log->write('account_manager_2');
            $this->error['account_manager'] = $this->language->get('error_account_manager');
        }*/

        //echo "<pre>";print_r($this->error);die;
        return !$this->error;
    }

    public function register_send_otp()
    {
        $data['status'] = false;

        $this->load->language('account/login');

        $this->load->language('account/register');

        //$this->document->setTitle( $this->language->get( 'heading_title' ) );

        $data['referral_description'] = 'Referral'; //$this->language->get( 'referral_description' );

        $this->document->addScript('front/ui/javascript/jquery/datetimepicker/moment.js');
        $this->document->addScript('front/ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
        $this->document->addStyle('front/ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');
        $this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_login.css');

        $this->load->model('account/customer');

        $log = new Log('error.log');
        $log->write($this->request->post['login_latitude']);
        $log->write($this->request->post['login_longitude']);
        //$log->write("outside form");

        $data['telephone_mask'] = $this->config->get('config_telephone_mask');

        $data['taxnumber_mask'] = $this->config->get('config_taxnumber_mask');

        //		echo "<pre>";print_r($this->request->post);die;
        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validate()) {
            $this->load->model('account/customer');

            $this->request->post['phone'] = $this->request->post['telephone'];

            if (false == strpos($this->request->post['phone'], '#') || !empty($this->request->post['phone'])) {
                $this->request->post['phone'] = preg_replace('/[^0-9]/', '', $this->request->post['phone']);

                $data['username'] = $this->request->post['firstname'];
                $data['otp'] = mt_rand(1000, 9999);

                $sms_message = $this->emailtemplate->getSmsMessage('registerOTP', 'registerotp_2', $data);

                //                echo "<pre>";print_r($sms_message);die;
                if ($this->emailtemplate->getSmsEnabled('registerOTP', 'registerotp_2')) {
                    $ret = $this->emailtemplate->sendmessage($this->request->post['phone'], $sms_message);

                    //save in otp table
                    $data['status'] = true;

                    $this->model_account_customer->saveOTP($this->request->post['phone'], $data['otp'], 'register');
                    $data['text_verify_otp'] = $this->language->get('text_verify_otp');

                    // $data['success_message'] = $this->language->get('text_otp_sent_email').' '.$this->request->post['email'];
                    $data['success_message'] = $this->language->get('text_otp_sent_email_mobile');
                }
                
                try{
                if ($this->emailtemplate->getEmailEnabled('registerOTP', 'registerotp_2')) {
                    $subject = $this->emailtemplate->getSubject('registerOTP', 'registerotp_2', $data);
                    $message = $this->emailtemplate->getMessage('registerOTP', 'registerotp_2', $data);

                    $mail = new mail($this->config->get('config_mail'));
                    $mail->setTo($this->request->post['email']);
                    $mail->setFrom($this->config->get('config_from_email'));
                    $mail->setSubject($subject);
                    $mail->setSender($this->config->get('config_name'));
                    $mail->setHtml($message);
                    $mail->send();
                }
                } catch(Exception $e) {
                    
                }
            } else {
                // enter valid number throw error
                $data['status'] = false;

                $data['warning'] = $this->language->get('error_telephone');
            }
        } else {
            $log->write('outside form 3nr dime');

            $data['entry_submit'] = $this->language->get('entry_submit');
            $data['entry_email_address'] = $this->language->get('entry_email_address');
            $data['entry_signup_otp'] = $this->language->get('entry_signup_otp');
            $data['entry_phone'] = $this->language->get('entry_phone');

            //$data['heading_title'] = $this->language->get( 'heading_title' );
            $data['heading_text'] = $this->language->get('heading_text');
            $data['text_account_already'] = sprintf($this->language->get('text_account_already'), $this->url->link('account/login', '', 'SSL'));

            if (isset($this->error['warning'])) {
                $data['error_warning'] = $this->error['warning'];
                $data['error_message'] = $this->error['warning'];
            } else {
                $data['error_warning'] = false;
            }

            if (isset($this->error['firstname'])) {
                $data['error_firstname'] = $this->error['firstname'];
                $data['error_message'] = $this->error['firstname'];
            } else {
                $data['error_firstname'] = false;
            }

            if (isset($this->error['lastname'])) {
                $data['error_lastname'] = $this->error['lastname'];
                $data['error_message'] = $this->error['lastname'];
            } else {
                $data['error_lastname'] = false;
            }

            if (isset($this->error['email'])) {
                $data['error_email'] = $this->error['email'];
                $data['error_message'] = $this->error['email'];
            } else {
                $data['error_email'] = false;
            }

            if (isset($this->error['company_name_address'])) {
                $data['error_company_name_address'] = $this->error['company_name_address'];
                $data['error_message'] = $this->error['company_name_address'];
            } else {
                $data['error_company_name_address'] = false;
            }

            if (isset($this->error['company_name'])) {
                $data['error_company_name'] = $this->error['company_name'];
                $data['error_message'] = $this->error['company_name'];
            } else {
                $data['error_company_name'] = false;
            }

            if (isset($this->error['company_address'])) {
                $data['error_company_address'] = $this->error['company_address'];
                $data['error_message'] = $this->error['company_address'];
            } else {
                $data['error_company_address'] = false;
            }

            if (isset($this->error['company_address'])) {
                $data['error_address'] = $this->error['address'];
                $data['error_message'] = $this->error['address'];
            } else {
                $data['error_address'] = false;
            }

            if (isset($this->error['house_building'])) {
                $data['error_house_building'] = $this->error['house_building'];
                $data['error_message'] = $this->error['house_building'];
            } else {
                $data['error_house_building'] = false;
            }

            if (isset($this->error['location'])) {
                $data['error_location'] = $this->error['location'];
                $data['error_message'] = $this->error['location'];
            } else {
                $data['error_location'] = false;
            }

            if (isset($this->error['telephone'])) {
                $data['error_telephone'] = $this->error['telephone'];
                $data['error_message'] = $this->error['telephone'];
            } else {
                $data['error_telephone'] = false;
            }

            if (isset($this->error['telephone_exists'])) {
                $data['error_telephone_exists'] = $this->error['telephone_exists'];
                $data['error_message'] = $this->error['telephone_exists'];
            } else {
                $data['error_telephone_exists'] = false;
            }

            if (isset($this->error['error_tax'])) {
                $data['error_tax'] = $this->error['error_tax'];
                $data['error_message'] = $this->error['error_tax'];
            } else {
                $data['error_tax'] = false;
            }

            if (isset($this->error['gender'])) {
                $data['error_gender'] = $this->error['gender'];
                $data['error_message'] = $this->error['gender'];
            } else {
                $data['error_gender'] = false;
            }

            if (isset($this->error['dob'])) {
                $data['error_dob'] = $this->error['dob'];
                $data['error_message'] = $this->error['dob'];
            } else {
                $data['error_dob'] = false;
            }

            if (isset($this->error['password'])) {
                $data['error_password'] = $this->error['password'];
                $data['error_message'] = $this->error['password'];
            } else {
                $data['error_password'] = false;
            }

            if (isset($this->error['confirm'])) {
                $data['error_confirm'] = $this->error['confirm'];
                $data['error_message'] = $this->error['confirm'];
            } else {
                $data['error_confirm'] = false;
            }

            if (isset($this->error['match'])) {
                $data['error_match_password'] = $this->error['match'];
                $data['error_message'] = $this->error['match'];
            } else {
                $data['error_match_password'] = false;
            }
            
            if (isset($this->error['account_manager'])) {
                $data['error_account_manager'] = $this->error['account_manager'];
                $data['error_message'] = $this->error['account_manager'];
            } else {
                $data['error_account_manager'] = false;
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function register_resend_otp()
    {
        $data['status'] = false;

        $this->load->language('account/login');
        $this->load->language('account/register');
 
        $this->load->model('account/customer');

        $log = new Log('error.log');       

        $data['telephone_mask'] = $this->config->get('config_telephone_mask');
        $data['taxnumber_mask'] = $this->config->get('config_taxnumber_mask');

        //		echo "<pre>";print_r($this->request->post);die;
        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validate()) {
            $this->load->model('account/customer');

            $this->request->post['phone'] = $this->request->post['telephone'];

            if (false == strpos($this->request->post['phone'], '#') || !empty($this->request->post['phone'])) {
                $this->request->post['phone'] = preg_replace('/[^0-9]/', '', $this->request->post['phone']);

                $data['username'] = $this->request->post['firstname'];
                // $data['otp'] = mt_rand(1000, 9999);
                $data['otp'] = $this->model_account_customer->getRegisterOTP($this->request->post['phone'], 'register');


                $sms_message = $this->emailtemplate->getSmsMessage('registerOTP', 'registerotp_2', $data);

                //                echo "<pre>";print_r($sms_message);die;
                if ($this->emailtemplate->getSmsEnabled('registerOTP', 'registerotp_2')) {
                    $ret = $this->emailtemplate->sendmessage($this->request->post['phone'], $sms_message);

                    //save in otp table
                    $data['status'] = true;

                    // $this->model_account_customer->saveOTP($this->request->post['phone'], $data['otp'], 'register');
                    $data['text_verify_otp'] = $this->language->get('text_verify_otp');

                    // $data['success_message'] = $this->language->get('text_otp_sent_email').' '.$this->request->post['email'];
                    $data['success_message'] = $this->language->get('text_otp_sent_email_mobile');
                }
                
                try{
                if ($this->emailtemplate->getEmailEnabled('registerOTP', 'registerotp_2')) {
                    $subject = $this->emailtemplate->getSubject('registerOTP', 'registerotp_2', $data);
                    $message = $this->emailtemplate->getMessage('registerOTP', 'registerotp_2', $data);

                    $mail = new mail($this->config->get('config_mail'));
                    $mail->setTo($this->request->post['email']);
                    $mail->setFrom($this->config->get('config_from_email'));
                    $mail->setSubject($subject);
                    $mail->setSender($this->config->get('config_name'));
                    $mail->setHtml($message);
                    $mail->send();
                }
                } catch(Exception $e) {
                    
                }
            } else {
                // enter valid number throw error
                $data['status'] = false;

                $data['warning'] = $this->language->get('error_telephone');
            }
        } else {
            $data['status'] = false;

            $data['warning'] = "Validation failed.";
  
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function register_verify_otp()
    {
        $data['status'] = false;

        $this->load->language('account/login');

        $this->load->language('account/register');

        //$this->document->setTitle( $this->language->get( 'heading_title' ) );

        $data['referral_description'] = 'Referral'; //$this->language->get( 'referral_description' );

        $this->document->addScript('front/ui/javascript/jquery/datetimepicker/moment.js');
        $this->document->addScript('front/ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
        $this->document->addStyle('front/ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');
        $this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_login.css');

        $this->load->model('account/customer');

        $log = new Log('error.log');

        $this->request->post['telephone'] = preg_replace('/[^0-9]/', '', $this->request->post['telephone']);

        $this->request->post['phone'] = $this->request->post['telephone'];
        //echo "<pre>";print_r($this->request->post);die;
        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validate()) {
            $this->load->model('account/customer');

            if (isset($this->request->post['signup_otp']) && isset($this->request->post['phone'])) {
                //echo "<pre>";print_r("er");die;
                $otp_data = $this->model_account_customer->getOTP($this->request->post['phone'], $this->request->post['signup_otp'], 'register');

                //echo "<pre>";print_r($otp_data);die;
                if (!$otp_data) {
                    $data['status'] = false;

                    $data['error_warning'] = $this->language->get('error_invalid_otp');
                // user not found
                } else {
                    // add activity and all

                    $log = new Log('error.log');
                    $log->write('register');

                    $referee_user_id = null;

                    if (count($_COOKIE) > 0 && isset($_COOKIE['referral']) && ('expired' != $_COOKIE['referral'])) {
                        //echo "Cookies are enabled.";
                        $this->request->post['referee_user_id'] = $_COOKIE['referral'];
                        $referee_user_id = $_COOKIE['referral'];

                        setcookie('referral', null, time() - 3600, '/');
                        //unset($_COOKIE['referral']);
                    }

                    if (isset($date)) {
                        $date = DateTime::createFromFormat('d/m/Y', $date);

                        $this->request->post['dob'] = $date->format('Y-m-d');
                    } else {
                        $this->request->post['dob'] = null;
                    }
                    $this->request->post['source'] = 'WEB';
                    $log->write($this->request->post['login_latitude']);
                    $log->write($this->request->post['login_longitude']);
                    //$this->request->post['password'] = mt_rand(1000,9999);

                    $customer_id = $this->model_account_customer->addCustomer($this->request->post, true);

                    // Clear any previous login attempts for unregistered accounts.
                    $this->model_account_customer->deleteLoginAttempts($this->request->post['email']);
                    /* Commented Login for change flow */
                    // $logged_in = $this->customer->loginByPhone( $customer_id );
                    //Add ip address from where user registered.
                    //this will prevent , the new IP OTP 
                    $this->model_account_customer->addregisterIP($customer_id);

                    unset($this->session->data['guest']);

                    // Add to activity log
                    $this->load->model('account/activity');

                    $activity_data = [
            'customer_id' => $customer_id,
            'name' => $this->request->post['firstname'].' '.$this->request->post['lastname'],
          ];

                    $log->write('in post signup 1');
                    $this->model_account_activity->addActivity('register', $activity_data);

                    /* If not able to login*/
                    $data['status'] = true;

                    /*if(!$logged_in) {
                      $data['status'] = false;
                    }*/

                    $data['text_new_signup_reward'] = $this->language->get('text_new_signup_reward');
                    $data['text_new_signup_credit'] = $this->language->get('text_new_signup_credit');

                    $data['message'] = $this->language->get('verify_mail_sent');

                    if (isset($referee_user_id)) {
                        $config_reward_enabled = $this->config->get('config_reward_enabled');

                        $config_credit_enabled = $this->config->get('config_credit_enabled');

                        $config_refer_type = $this->config->get('config_refer_type');

                        $config_refered_points = $this->config->get('config_refered_points');
                        $config_referee_points = $this->config->get('config_referee_points');

                        $log->write($customer_id);
                        $log->write($config_refer_type);
                        $log->write($referee_user_id.' referee_user_id');

                        $log->write($config_referee_points);
                        $log->write($config_refered_points);

                        if ('reward' == $config_refer_type) {
                            $log->write($config_reward_enabled);

                            if ($config_reward_enabled && $config_refered_points && $config_referee_points) {
                                $log->write('if');

                                //referred points below
                                $this->model_account_activity->addCustomerReward($customer_id, $config_refered_points, $data['referral_description']);

                                //referee points below
                //$this->model_account_activity->addCustomerReward( $referee_user_id,$config_referee_points,$data['referral_description'] );
                            }
                        } elseif ('credit' == $config_refer_type) {
                            $log->write('credit if');

                            if ($config_credit_enabled && $config_refered_points && $config_referee_points) {
                                //referred points below
                                $this->model_account_activity->addCredit($customer_id, $data['referral_description'], $config_refered_points);

                                //referee points below
                //$this->model_account_activity->addCredit($referee_user_id, $data['referral_description'] , $config_referee_points);
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

                    // delete otp
                    $this->model_account_customer->deleteOTP($this->request->post['phone'], $this->request->post['signup_otp'], 'register');

                    //$data['success_message'] = $this->language->get('text_valid_otp');
                    $data['success_message'] = $this->language->get('register_disabled_msg');
                }
            } else {
                // enter valid number throw error
                $data['status'] = false;

                $data['error_warning'] = $this->language->get('error_invalid_otp');
            }
        } else {
            $log->write('outside form 3nr dime');

            $data['entry_submit'] = $this->language->get('entry_submit');
            $data['entry_email_address'] = $this->language->get('entry_email_address');
            $data['entry_signup_otp'] = $this->language->get('entry_signup_otp');
            $data['entry_phone'] = $this->language->get('entry_phone');

            //$data['heading_title'] = $this->language->get( 'heading_title' );
            $data['heading_text'] = $this->language->get('heading_text');
            $data['text_account_already'] = sprintf($this->language->get('text_account_already'), $this->url->link('account/login', '', 'SSL'));

            if (isset($this->error['warning'])) {
                $data['error_warning'] = $this->error['warning'];
            } else {
                $data['error_warning'] = false;
            }

            if (isset($this->error['firstname'])) {
                $data['error_firstname'] = $this->error['firstname'];
            } else {
                $data['error_firstname'] = false;
            }

            if (isset($this->error['lastname'])) {
                $data['error_lastname'] = $this->error['lastname'];
            } else {
                $data['error_lastname'] = false;
            }

            if (isset($this->error['email'])) {
                $data['error_email'] = $this->error['email'];
            } else {
                $data['error_email'] = false;
            }

            if (isset($this->error['company_name_address'])) {
                $data['error_company_name_address'] = $this->error['company_name_address'];
            } else {
                $data['error_company_name_address'] = false;
            }

            if (isset($this->error['telephone'])) {
                $data['error_telephone'] = $this->error['telephone'];
            } else {
                $data['error_telephone'] = false;
            }

            if (isset($this->error['telephone_exists'])) {
                $data['error_telephone_exists'] = $this->error['telephone_exists'];
            } else {
                $data['error_telephone_exists'] = false;
            }

            if (isset($this->error['error_tax'])) {
                $data['error_tax'] = $this->error['error_tax'];
            } else {
                $data['error_tax'] = false;
            }

            if (isset($this->error['gender'])) {
                $data['error_gender'] = $this->error['gender'];
            } else {
                $data['error_gender'] = false;
            }

            if (isset($this->error['dob'])) {
                $data['error_dob'] = $this->error['dob'];
            } else {
                $data['error_dob'] = false;
            }

            if (isset($this->error['password'])) {
                $data['error_password'] = $this->error['password'];
            } else {
                $data['error_password'] = false;
            }

            if (isset($this->error['confirm'])) {
                $data['error_confirm'] = $this->error['confirm'];
            } else {
                $data['error_confirm'] = '';
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function register()
    {
        $data['status'] = false;

        $this->load->language('account/login');

        $this->load->language('account/register');

        //$this->document->setTitle( $this->language->get( 'heading_title' ) );

        $data['referral_description'] = 'Referral'; //$this->language->get( 'referral_description' );

        $this->document->addScript('front/ui/javascript/jquery/datetimepicker/moment.js');
        $this->document->addScript('front/ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
        $this->document->addStyle('front/ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');
        $this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_login.css');

        $this->load->model('account/customer');

        $log = new Log('error.log');

        $this->request->post['telephone'] = preg_replace('/[^0-9]/', '', $this->request->post['telephone']);

        $this->request->post['phone'] = $this->request->post['telephone'];
        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validate()) {
            $this->load->model('account/customer');

            // add activity and all
            $log = new Log('error.log');
            $log->write('register');

            $referee_user_id = null;

            if (count($_COOKIE) > 0 && isset($_COOKIE['referral']) && ('expired' != $_COOKIE['referral'])) {
                //echo "Cookies are enabled.";
                $this->request->post['referee_user_id'] = $_COOKIE['referral'];
                $referee_user_id = $_COOKIE['referral'];

                setcookie('referral', null, time() - 3600, '/');
                //unset($_COOKIE['referral']);
            }

            if (isset($date)) {
                $date = DateTime::createFromFormat('d/m/Y', $date);

                $this->request->post['dob'] = $date->format('Y-m-d');
            } else {
                $this->request->post['dob'] = null;
            }

            //$this->request->post['password'] = mt_rand(1000,9999);

            $customer_id = $this->model_account_customer->addCustomer($this->request->post);

            // Clear any previous login attempts for unregistered accounts.
            $this->model_account_customer->deleteLoginAttempts($this->request->post['email']);

            $logged_in = $this->customer->loginByPhone($customer_id);

            unset($this->session->data['guest']);

            // Add to activity log
            $this->load->model('account/activity');

            $activity_data = [
        'customer_id' => $customer_id,
        'name' => $this->request->post['firstname'].' '.$this->request->post['lastname'],
      ];

            $log->write('in post signup 1');
            $this->model_account_activity->addActivity('register', $activity_data);

            /* If not able to login*/
            $data['status'] = true;

            if (!$logged_in) {
                $data['status'] = false;
            }

            $data['text_new_signup_reward'] = $this->language->get('text_new_signup_reward');
            $data['text_new_signup_credit'] = $this->language->get('text_new_signup_credit');

            //$data['message'] = $this->language->get('verify_mail_sent');
            $data['message'] = $this->language->get('register_mail_sent');

            if (isset($referee_user_id)) {
                $config_reward_enabled = $this->config->get('config_reward_enabled');

                $config_credit_enabled = $this->config->get('config_credit_enabled');

                $config_refer_type = $this->config->get('config_refer_type');

                $config_refered_points = $this->config->get('config_refered_points');
                $config_referee_points = $this->config->get('config_referee_points');

                $log->write($customer_id);
                $log->write($config_refer_type);
                $log->write($referee_user_id.' referee_user_id');

                $log->write($config_referee_points);
                $log->write($config_refered_points);

                if ('reward' == $config_refer_type) {
                    $log->write($config_reward_enabled);

                    if ($config_reward_enabled && $config_refered_points && $config_referee_points) {
                        $log->write('if');

                        //referred points below
                        $this->model_account_activity->addCustomerReward($customer_id, $config_refered_points, $data['referral_description']);

                        //referee points below
            //$this->model_account_activity->addCustomerReward( $referee_user_id,$config_referee_points,$data['referral_description'] );
                    }
                } elseif ('credit' == $config_refer_type) {
                    $log->write('credit if');

                    if ($config_credit_enabled && $config_refered_points && $config_referee_points) {
                        //referred points below
                        $this->model_account_activity->addCredit($customer_id, $data['referral_description'], $config_refered_points);

                        //referee points below
            //$this->model_account_activity->addCredit($referee_user_id, $data['referral_description'] , $config_referee_points);
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

            // delete otp
            // $this->model_account_customer->deleteOTP($this->request->post['phone'],$this->request->post['signup_otp'],'register');

            $data['success_message'] = $this->language->get('text_valid_otp');
        } else {
            $log->write('outside form 3nr dime');
            $data['entry_submit'] = $this->language->get('entry_submit');
            $data['entry_email_address'] = $this->language->get('entry_email_address');
            $data['entry_signup_otp'] = $this->language->get('entry_signup_otp');
            $data['entry_phone'] = $this->language->get('entry_phone');

            //$data['heading_title'] = $this->language->get( 'heading_title' );
            $data['heading_text'] = $this->language->get('heading_text');
            $data['text_account_already'] = sprintf($this->language->get('text_account_already'), $this->url->link('account/login', '', 'SSL'));

            if (isset($this->error['warning'])) {
                $data['error_warning'] = $this->error['warning'];
            } else {
                $data['error_warning'] = '';
            }

            if (isset($this->error['firstname'])) {
                $data['error_firstname'] = $this->error['firstname'];
            } else {
                $data['error_firstname'] = false;
            }

            if (isset($this->error['lastname'])) {
                $data['error_lastname'] = $this->error['lastname'];
            } else {
                $data['error_lastname'] = false;
            }

            if (isset($this->error['email'])) {
                $data['error_email'] = $this->error['email'];
            } else {
                $data['error_email'] = false;
            }

            if (isset($this->error['company_name_address'])) {
                $data['error_company_name_address'] = $this->error['company_name_address'];
            } else {
                $data['error_company_name_address'] = false;
            }

            if (isset($this->error['telephone'])) {
                $data['error_telephone'] = $this->error['telephone'];
            } else {
                $data['error_telephone'] = false;
            }

            if (isset($this->error['telephone_exists'])) {
                $data['error_telephone_exists'] = $this->error['telephone_exists'];
            } else {
                $data['error_telephone_exists'] = false;
            }

            if (isset($this->error['error_tax'])) {
                $data['error_tax'] = $this->error['error_tax'];
            } else {
                $data['error_tax'] = false;
            }

            if (isset($this->error['gender'])) {
                $data['error_gender'] = $this->error['gender'];
            } else {
                $data['error_gender'] = false;
            }

            if (isset($this->error['dob'])) {
                $data['error_dob'] = $this->error['dob'];
            } else {
                $data['error_dob'] = false;
            }

            if (isset($this->error['password'])) {
                $data['error_password'] = $this->error['password'];
            } else {
                $data['error_password'] = false;
            }

            if (isset($this->error['confirm'])) {
                $data['error_confirm'] = $this->error['confirm'];
            } else {
                $data['error_confirm'] = '';
            }

            if (isset($this->error['match'])) {
                $data['error_match_password'] = $this->error['match'];
            } else {
                $data['error_match_password'] = '';
            }
            
            if (isset($this->error['account_manager'])) {
                $data['error_account_manager'] = $this->error['account_manager'];
            } else {
                $data['error_account_manager'] = false;
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function customfield()
    {
        $json = [];

        $this->load->model('account/custom_field');

        // Customer Group
        if (isset($this->request->get['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->get['customer_group_id'], $this->config->get('config_customer_group_display'))) {
            $customer_group_id = $this->request->get['customer_group_id'];
        } else {
            $customer_group_id = $this->config->get('config_customer_group_id');
        }

        $custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

        foreach ($custom_fields as $custom_field) {
            $json[] = [
        'custom_field_id' => $custom_field['custom_field_id'],
        'required' => $custom_field['required'],
      ];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
