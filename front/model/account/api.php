<?php

class ModelAccountApi extends Model
{
    private $error = [];

    public function login($username, $password)
    {
        /*$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "api` WHERE username = '" . $this->db->escape($username) . "' AND password = '" . $this->db->escape($password) . "' AND status = '1'");

        return $query->row;*/

        $data['status'] = true;
        $user_query = $this->db->query('SELECT * FROM '.DB_PREFIX."user WHERE username = '".$this->db->escape($username)."' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('".$this->db->escape($password)."'))))) OR password = '".$this->db->escape(md5($password))."') AND status = '1'");

        //print_r($user_query);
        if ($user_query->num_rows) {
            $data['user_id'] = $user_query->row['user_id'];

            $data['username'] = $user_query->row['username'];
            $data['user_group_id'] = $user_query->row['user_group_id'];
        } else {
            $data['status'] = false;
        }

        return $data;
    }

    public function customer_login($username, $password)
    {
        /*$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "api` WHERE username = '" . $this->db->escape($username) . "' AND password = '" . $this->db->escape($password) . "' AND status = '1'");

        return $query->row;*/

        $data['status'] = true;
        $data['not_verified'] = false;
        $user_query = $this->db->query('SELECT * FROM '.DB_PREFIX."customer WHERE email = '".$this->db->escape($username)."' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('".$this->db->escape($password)."'))))) OR password = '".$this->db->escape(md5($password))."')");

        //print_r($user_query);
        if ($user_query->num_rows) {
            //if($user_query->row['token'] == "") {
            if ($user_query->row['approved']) {
                $data['customer_id'] = $user_query->row['customer_id'];
                $data['order_approval_access'] = $user_query->row['order_approval_access'];
                $data['order_approval_access_role'] = $user_query->row['order_approval_access_role'];

                $data['customer_email'] = $user_query->row['email'];
                $data['parent'] = $user_query->row['parent'];
            } else {
                $data['status'] = false;
                $data['not_verified'] = true;
            }
        } else {
            $data['status'] = false;
        }

        return $data;
    }

    public function customerLoginByPhone($username, $password)
    {
        $data['status'] = true;
        $data['not_verified'] = false;

        $user_query = $this->db->query('SELECT * FROM '.DB_PREFIX."customer WHERE telephone = '".$this->db->escape($username)."' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('".$this->db->escape($password)."'))))) OR password = '".$this->db->escape(md5($password))."') AND status = '1'");

        //print_r($user_query);
        if ($user_query->num_rows) {
            if ($user_query->row['approved']) {
                $data['customer_id'] = $user_query->row['customer_id'];

                $data['customer_phone'] = $user_query->row['telephone'];
                $data['customer_email'] = $user_query->row['email'];
                $data['parent'] = $user_query->row['parent'];
            } else {
                $data['status'] = false;
                $data['not_verified'] = true;
            }
        } else {
            $data['status'] = false;
        }

        return $data;
    }

    public function login_send_otp()
    {
        $data['status'] = true;

        $this->load->model('account/customer');

        $this->load->language('api/general');

        if (false == strpos($this->request->post['phone'], '#') && !empty($this->request->post['phone'])) {
            if (ctype_digit($this->request->post['phone'])) {
                //phone
                $this->request->post['phone'] = preg_replace('/[^0-9]/', '', $this->request->post['phone']);

                $customer_info = $this->model_account_customer->getCustomerByPhone($this->request->post['phone']);
            } else {
                $customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['phone']);
            }

            //echo "<pre>";print_r($customer_info);die;
            if (!$customer_info) {
                $data['status'] = false;

                if (ctype_digit($this->request->post['phone'])) {
                    $data['error_warning'] = $this->language->get('error_phone_login');
                } else {
                    $data['error_warning'] = $this->language->get('error_email_login');
                }

                //$data['error_warning'] = $this->language->get('error_phone_login');
            } else {
                $data['username'] = $customer_info['firstname'];
                if ('111111111' == $this->request->post['phone']) {
                    $data['otp'] = '1234';
                } else {
                    $data['otp'] = mt_rand(1000, 9999);
                }

                $data['customer_id'] = $customer_info['customer_id'];

                if ('111111111' != $this->request->post['phone']) {
                    $sms_message = $this->emailtemplate->getSmsMessage('LoginOTP', 'loginotp_2', $data);

                    if ($this->emailtemplate->getSmsEnabled('LoginOTP', 'loginotp_2')) {
                        $ret = $this->emailtemplate->sendmessage($this->request->post['phone'], $sms_message);
                    }
                }
                
                if ($this->emailtemplate->getEmailEnabled('LoginOTP', 'loginotp_2')) {
                //if ($customer_info['email_notification'] == 1 && $this->emailtemplate->getEmailEnabled('LoginOTP', 'loginotp_2')) {
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

                //save in otp table

                $this->model_account_customer->saveOTP($customer_info['customer_id'], $data['otp'], 'login');

                $data['success_message'] = $this->language->get('text_otp_sent_to');
            }
        } else {
            // enter valid number throw error
            $data['status'] = false;

            $data['error_warning'] = $this->language->get('error_telephone');
        }

        return $data;
    }

    public function phonenumber_send_otp()
    {
        $data['status'] = true;

        $this->load->model('account/customer');

        $this->load->language('api/general');

        if (false == strpos($this->request->post['new_phone'], '#') && !empty($this->request->post['new_phone'])) {
            $this->request->post['new_phone'] = preg_replace('/[^0-9]/', '', $this->request->post['new_phone']);

            $new_customer_info = $this->model_account_customer->getCustomerByPhone($this->request->post['new_phone']);

            $customer_info = $this->model_account_customer->getCustomerByPhone($this->request->post['old_phone']);

            //echo "<pre>";print_r($customer_info);die;
            if ($new_customer_info) {
                $data['status'] = false;

                $data['error_warning'] = $this->language->get('error_phone_already_used');
            // user not found
            } else {
                $data['username'] = $customer_info['firstname'];
                $data['otp'] = mt_rand(1000, 9999);

                $data['customer_id'] = $customer_info['customer_id'];

                $sms_message = $this->emailtemplate->getSmsMessage('LoginOTP', 'loginotp_2', $data);

                if ($this->emailtemplate->getSmsEnabled('LoginOTP', 'loginotp_2')) {
                    $ret = $this->emailtemplate->sendmessage($this->request->post['new_phone'], $sms_message);

                    //save in otp table

                    $this->model_account_customer->saveOTP($customer_info['customer_id'], $data['otp'], 'phone_change');

                    $data['success_message'] = $this->language->get('text_otp_sent').' '.$this->request->post['new_phone'];
                }
            }
        } else {
            // enter valid number throw error
            $data['status'] = false;

            $data['error_warning'] = $this->language->get('error_telephone');
        }

        return $data;
    }

    public function phonenumber_verify_otp($verify_otp, $customer_id)
    {
        $data['status'] = true;

        $this->load->model('account/customer');

        $this->load->language('api/general');

        if (isset($verify_otp) && isset($customer_id)) {
            $otp_data = $this->model_account_customer->getOTP($customer_id, $verify_otp, 'phone_change');

            //echo "<pre>";print_r($otp_data);die;
            if (!$otp_data) {
                $data['status'] = false;

                $data['error_warning'] = $this->language->get('error_invalid_otp');
            // user not found
            } else {
                $data['status'] = true;

                // end

                // delete otp

                $this->model_account_customer->deleteOTP($customer_id, $verify_otp, 'phone_change');

                $data['success_message'] = $this->language->get('text_valid_otp');
            }
        } else {
            // enter valid number throw error
            $data['status'] = false;

            $data['error_warning'] = $this->language->get('error_invalid_otp');
        }

        return $data;
    }

    public function login_verify_otp($verify_otp, $customer_id)
    {
        $data['status'] = true;

        $this->load->model('account/customer');

        $this->load->language('api/general');

        if (isset($verify_otp) && isset($customer_id)) {
            $otp_data = $this->model_account_customer->getOTP($customer_id, $verify_otp, 'login');

            //echo "<pre>";print_r($otp_data);die;
            if (!$otp_data) {
                $data['status'] = false;

                $data['error_warning'] = $this->language->get('error_invalid_otp');
            // user not found
            } else {
                // add activity and all

                if ($this->customer->loginByPhone($customer_id)) {
                    $this->model_account_customer->addLoginAttempt($this->customer->getEmail());

                    if ('shipping' == $this->config->get('config_tax_customer')) {
                        $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
                    }

                    // Add to activity log
                    $this->load->model('account/activity');

                    $activity_data = [
                        'customer_id' => $this->customer->getId(),
                        'name' => $this->customer->getFirstName().' '.$this->customer->getLastName(),
                    ];

                    $this->model_account_activity->addActivity('login', $activity_data);

                    $data['status'] = true;

                    // end

                    // delete otp
                    $this->model_account_customer->deleteOTP($verify_otp, $customer_id, 'login');

                    $data['success_message'] = $this->language->get('text_valid_otp');
                }
            }
        } else {
            // enter valid number throw error
            $data['status'] = false;

            $data['error_warning'] = $this->language->get('error_invalid_otp');
        }

        return $data;
    }

    public function signup_validate()
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

 

        if ((utf8_strlen($this->request->post['password']) >= 1) && (utf8_strlen($this->request->post['password']) < 6) || (utf8_strlen($this->request->post['password']) > 20)) {
            $this->error['password'] = $this->language->get('error_password');
        }

        if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{6,}$/', $this->request->post['password'])) {
            $this->error['password'] = 'Password must contain 6 characters 1 capital(A-Z) 1 numeric(0-9) 1 special(@$!%*#?&)';
        }


        //echo "<pre>";print_r($this->error);die;
        return !$this->error;
    }

    public function register_send_otp()
    {
        $data['status'] = false;

        $this->load->language('account/login');

        $this->load->language('account/register');

        $this->load->language('api/general');

        $this->load->model('account/customer');

        $log = new Log('error.log');

        //echo "<pre>";print_r($this->request->post);die;
        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->signup_validate()) {
            $this->load->model('account/customer');

            $this->request->post['phone'] = $this->request->post['telephone'];

            if (false == strpos($this->request->post['phone'], '#') || !empty($this->request->post['phone'])) {
                $this->request->post['phone'] = preg_replace('/[^0-9]/', '', $this->request->post['phone']);

                $data['username'] = $this->request->post['firstname'];
                $data['otp'] = mt_rand(1000, 9999);

                $sms_message = $this->emailtemplate->getSmsMessage('registerOTP', 'registerotp_2', $data);

                //echo "<pre>";print_r($sms_message);die;
                if ($this->emailtemplate->getSmsEnabled('registerOTP', 'registerotp_2')) {
                    $ret = $this->emailtemplate->sendmessage($this->request->post['phone'], $sms_message);

                    //save in otp table
                    $data['status'] = true;

                    $this->model_account_customer->saveOTP($this->request->post['phone'], $data['otp'], 'register');
                    $data['text_verify_otp'] = $this->language->get('text_verify_otp');

                    $data['success_message'] = $this->language->get('text_otp_sent').' '.$this->request->post['phone'];
                }

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
            } else {
                // enter valid number throw error
                $data['status'] = false;

                $data['warning'] = $this->language->get('error_telephone');
            }
        } else {
            foreach ($this->error as $key => $value) {
                $data['errors'][] = ['type' => $key, 'body' => $value];
            }
        }

        return $data;
    }

    public function register_verify_otp()
    {
        $data['status'] = false;

        $this->load->language('account/login');

        $this->load->language('account/register');

        $this->load->language('api/general');

        $this->load->model('account/customer');

        $log = new Log('error.log');

        $this->request->post['telephone'] = preg_replace('/[^0-9]/', '', $this->request->post['telephone']);

        $this->request->post['phone'] = $this->request->post['telephone'];
        //echo "<pre>";print_r($this->request->post);die;
        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->signup_validate()) {
            $this->load->model('account/customer');

            if (isset($this->request->post['signup_otp']) && isset($this->request->post['phone'])) {
                //echo "<pre>";print_r("er");die;
                $otp_data = $this->model_account_customer->getOTP($this->request->post['phone'], $this->request->post['signup_otp'], 'register');

                //echo "<pre>";print_r($otp_data);die;
                if (!$otp_data) {
                    $data['status'] = false;
                    $this->error['warning'] = $this->language->get('error_invalid_otp');
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
                    $this->request->post['source'] = 'MOBILE';
                    //$this->request->post['password'] = mt_rand(1000,9999);
                    // echo "<pre>";print_r($this->request->post);die;
                    // $accountmanagerid = NULL;
                    // $accountmanagerid =$this->request->post['accountmanagerid'];
                    // $log->write('accountmanagerid from API');
                    // $log->write($accountmanagerid);
                    $customer_id = $this->model_account_customer->addCustomer($this->request->post,true);

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

                    $data['success_message'] = $this->language->get('text_valid_otp');
                    $data['customer_id'] = $customer_id;
                }
            } else {
                // enter valid number throw error
                $data['status'] = false;
                //$data['error_warning'] = $this->language->get('error_invalid_otp');
                $this->error['warning'] = $this->language->get('error_invalid_otp');
            }
        }

        foreach ($this->error as $key => $value) {
            $data['errors'][] = ['type' => $key, 'body' => $value];
        }

        return $data;
    }

    public function new_device_send_otp()
    {
        $data['status'] = true;
        $this->load->model('account/customer');
        $this->load->language('api/general');

        if (false == strpos($this->request->post['phone'], '#') && !empty($this->request->post['phone'])) {
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
            } else {
                $data['username'] = $customer_info['firstname'];
                if ('111111111' == $this->request->post['phone']) {
                    $data['otp'] = '1234';
                } else {
                    $data['otp'] = mt_rand(1000, 9999);
                }

                $data['customer_id'] = $customer_info['customer_id'];
                //save in otp table
                $this->model_account_customer->saveOTP($customer_info['customer_id'], $data['otp'], 'newdevicelogin');
                try{      
                   
                        //the Same login verify OTP mail is being used.
                        $log = new Log('error.log');
                    if ($this->emailtemplate->getEmailEnabled('NewDeviceLogin', 'NewDeviceLogin_1')) {
                    //if ($customer_info['email_notification'] == 1 && $this->emailtemplate->getEmailEnabled('NewDeviceLogin', 'NewDeviceLogin_1')) {
                        
                        $subject = $this->emailtemplate->getSubject('NewDeviceLogin', 'NewDeviceLogin_1', $data);
                        
                        $message = $this->emailtemplate->getMessage('NewDeviceLogin', 'NewDeviceLogin_1', $data);
                        
                        $mail = new mail($this->config->get('config_mail'));
                        $mail->setTo($customer_info['email']);
                        $mail->setFrom($this->config->get('config_from_email'));
                        $mail->setSubject($subject);
                        $mail->setSender($this->config->get('config_name'));
                        $mail->setHtml($message);
                        $mail->send();
                    }

                    if ('111111111' != $this->request->post['phone']) {
                        $sms_message = $this->emailtemplate->getSmsMessage('NewDeviceLogin', 'NewDeviceLogin_1', $data);

                        if ($customer_info['sms_notification'] == 1 && $this->emailtemplate->getSmsEnabled('NewDeviceLogin', 'NewDeviceLogin_1')) {
                            $ret = $this->emailtemplate->sendmessage($this->request->post['phone'], $sms_message);
                        }
                    }
                }
                catch(Exception $ex)
                {
                    $log = new Log('error.log');
                    $log->write("new device OTP SMS/Mail Failed");
                }
                finally{
                 $data['success_message'] = $this->language->get('text_otp_sent_to');
                 return $data;
                 }
            }
        } else {
            // enter valid number throw error
            $data['status'] = false;
            $data['error_warning'] = $this->language->get('error_phone');
        }

        return $data;
    }

    public function new_device_verify_otp($verify_otp, $customer_id)
    {
        $data['status'] = true;
        $this->load->model('account/customer');
        $this->load->language('api/general');

        if (isset($verify_otp) && isset($customer_id)) {
            $otp_data = $this->model_account_customer->getOTP($customer_id, $verify_otp, 'newdevicelogin');

            //echo "<pre>";print_r($otp_data);die;
            if (!$otp_data) {
                $data['status'] = false;
                $data['error_warning'] = $this->language->get('error_invalid_otp');
            // user not found
            } else {
                // add activity and all
                if ($this->customer->loginByPhone($customer_id)) {
                    $this->model_account_customer->addLoginAttempt($this->customer->getEmail());
                    if ('shipping' == $this->config->get('config_tax_customer')) {
                        $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
                    }

                    //Add activity log--> will be added from login method
                    //Add login history--> will be added from login method
                    $data['status'] = true;
                    // end
                    // delete otp
                    $this->model_account_customer->deleteOTP($verify_otp, $customer_id, 'newdevicelogin');

                    $data['success_message'] = $this->language->get('text_valid_otp');
                }
            }
        } else {
            // enter valid number throw error
            $data['status'] = false;

            $data['error_warning'] = $this->language->get('error_invalid_otp');
        }

        return $data;
    }
    public function addCustomerDevice($customer_id, $device_id)
    {        
        $this->db->query('INSERT INTO '.DB_PREFIX."customer_devices SET customer_id = '".(int) $customer_id."', device_id = '".$this->db->escape($device_id)."', date_added = NOW()");
    }

   
    public function register_user()
    {
        $data['status'] = false;
        $this->load->language('account/login');
        $this->load->language('account/register');
        $this->load->language('api/general');
        $this->load->model('account/customer');
        $log = new Log('error.log');
        $this->request->post['telephone'] = preg_replace('/[^0-9]/', '', $this->request->post['telephone']);

        $this->request->post['phone'] = $this->request->post['telephone'];
        //echo "<pre>";print_r($this->request->post);die;
        $log->write('New User registration with phone number '.$this->request->post['phone']);
        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->signup_validate()) {
            $this->load->model('account/customer');

            if (false == strpos($this->request->post['phone'], '#') || isset($this->request->post['phone'])) {                  
                  {
                    if (isset($date)) {
                        $date = DateTime::createFromFormat('d/m/Y', $date);
                        $this->request->post['dob'] = $date->format('Y-m-d');
                    } else {
                        $this->request->post['dob'] = null;
                    }
                    $this->request->post['source'] = 'MOBILE';
                    $this->request->post['status'] = 0;
                    //$this->request->post['password'] = mt_rand(1000,9999);
                    // echo "<pre>";print_r($this->request->post);die;
                    // $accountmanagerid = NULL;
                    // $accountmanagerid =$this->request->post['accountmanagerid'];
                    // $log->write('accountmanagerid from API');
                    // $log->write($accountmanagerid);
                    $customer_id = $this->model_account_customer->addCustomer($this->request->post,true,true);

                    // Clear any previous login attempts for unregistered accounts.
                    $this->model_account_customer->deleteLoginAttempts($this->request->post['email']);

                        //Mail & SMS sending region
                      #region SMS and mail sending
                        
                        $data['username'] = $this->request->post['firstname'];
                        $data['otp'] = mt_rand(1000, 9999);
        
        
                        //echo "<pre>";print_r($sms_message);die;
                        // if ($this->emailtemplate->getSmsEnabled('registerOTP', 'registerotp_2')) {
                           try{
                        $sms_message = $this->emailtemplate->getSmsMessage('registerOTP', 'registerotp_2', $data);

                            $ret = $this->emailtemplate->sendmessage($this->request->post['phone'], $sms_message);
                            $log->write('OTP send to phone number '.$this->request->post['phone']);
                            $log->write('OTP send to phone number '.$sms_message);
                            $log->write('OTP send to phone number '.$ret);
                           }
                           catch(exception $ex)
                           {
                            $log->write('error sending OTP to phone number'.$ex); 
                           }
                           
                           
                        // }

                        $data['status'] = true;
                        //save in otp table
                        $this->model_account_customer->saveOTP($this->request->post['phone'], $data['otp'], 'register');
                        $data['text_verify_otp'] = $this->language->get('text_verify_otp');
    
                        // $data['success_message'] = $this->language->get('text_otp_sent').' '.$this->request->post['phone'];
                        $data['success_message'] = $this->language->get('text_otp_sent');
        
                         if ($this->emailtemplate->getEmailEnabled('registerOTP', 'registerotp_2')) {
                           
                           try{ 
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
                           catch(exception $ex)
                           {
                            $log->write('OTP send to Email erroe'.$ex); 

                           }
                         }
                     #endregion  
                    // $logged_in = $this->customer->loginByPhone($customer_id);
                    // unset($this->session->data['guest']);
                    // Add to activity log
                    $this->load->model('account/activity');
                    $activity_data = [
                        'customer_id' => $customer_id,
                        'name' => $this->request->post['firstname'].' '.$this->request->post['lastname'],
                    ];

                    // $log->write('Registered');
                    $this->model_account_activity->addActivity('register', $activity_data);

                    /* If not able to login*/
                    $data['status'] = true;

                    // if (!$logged_in) {
                    //     $data['status'] = false;
                    // }

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

                    // $data['success_message'] = $this->language->get('text_valid_otp');
                    // $data['customer_id'] = $customer_id;
                }
            } else {
                // enter valid number throw error
                $data['status'] = false;
                //$data['error_warning'] = $this->language->get('error_invalid_otp');
                $data['warning'] = $this->language->get('error_telephone');
            }
        }

        foreach ($this->error as $key => $value) {
            $data['errors'][] = ['type' => $key, 'body' => $value];
        }

        return $data;
    }


      public function resend_register_otp()
    {
        $data['status'] = false;
        $this->load->language('account/login');
        $this->load->language('account/register');
        $this->load->language('api/general');
        $this->load->model('account/customer');
        $log = new Log('error.log');
        $this->request->get['telephone'] = preg_replace('/[^0-9]/', '', $this->request->post['telephone']);
 
        //echo "<pre>";print_r($this->request->get);die;
        if (('GET' == $this->request->server['REQUEST_METHOD']) ) {
            $this->load->model('account/customer');

            if (false == strpos($this->request->get['telephone'], '#') || isset($this->request->get['telephone'])) {                  
                  {
                       
                        //Mail & SMS sending region
                      #region SMS and mail sending
                        
                        $data['username'] = $this->request->get['firstname'];
                        // $data['otp'] = mt_rand(1000, 9999);
                        $data['otp'] = $this->model_account_customer->getRegisterOTP($this->request->get['telephone'], 'register');
        
                        if(isset($data['otp']))
                        {

                      
                        //echo "<pre>";print_r($sms_message);die;
                        // if ($this->emailtemplate->getSmsEnabled('registerOTP', 'registerotp_2')) {
                           try{
                        $sms_message = $this->emailtemplate->getSmsMessage('registerOTP', 'registerotp_2', $data);

                            $ret = $this->emailtemplate->sendmessage($this->request->post['phone'], $sms_message);
                            $log->write('OTP send to phone number '.$this->request->post['phone']);
                            $log->write('OTP send to phone number '.$sms_message);
                            $log->write('OTP send to phone number '.$ret);
                           }
                           catch(exception $ex)
                           {
                            $log->write('error sending OTP to phone number'.$ex); 
                           }
                           
                           
                        // }

                        $data['status'] = true;
                          $data['text_verify_otp'] = $this->language->get('text_verify_otp');
    
                        // $data['success_message'] = $this->language->get('text_otp_sent').' '.$this->request->post['phone'];
                        $data['success_message'] = $this->language->get('text_otp_sent');
        
                         if ($this->emailtemplate->getEmailEnabled('registerOTP', 'registerotp_2')) {
                           
                           try{ 
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
                           catch(exception $ex)
                           {
                            $log->write('OTP send to Email erroe'.$ex); 

                           }
                         }
                     #endregion  
                     $data['status'] = true;
                        }
                        else{
                            $data['status'] = false;
                            $data['warning'] = "Please register again";
                        }
                     
                   
 
                }
            } else {
                // enter valid number throw error
                $data['status'] = false;
                //$data['error_warning'] = $this->language->get('error_invalid_otp');
                $data['warning'] = $this->language->get('error_telephone');
            }
        }

        foreach ($this->error as $key => $value) {
            $data['errors'][] = ['type' => $key, 'body' => $value];
        }

        return $data;
    }

    public function register_verify_user_otp()
    {
        $data['status'] = false;
        $this->load->language('account/login');
        $this->load->language('account/register');
        $this->load->language('api/general');
        $this->load->model('account/customer');
        $log = new Log('error.log');
        $this->request->post['telephone'] = preg_replace('/[^0-9]/', '', $this->request->post['telephone']);

        $this->request->post['phone'] = $this->request->post['telephone'];
        //echo "<pre>";print_r($this->request->post);die;
        if (('POST' == $this->request->server['REQUEST_METHOD']) ) {//&& $this->signup_validate()
            $this->load->model('account/customer');

            if (isset($this->request->post['signup_otp']) && isset($this->request->post['phone'])) {
                //echo "<pre>";print_r("er");die;
                $otp_data = $this->model_account_customer->getOTP($this->request->post['phone'], $this->request->post['signup_otp'], 'register');

                //echo "<pre>";print_r($otp_data);die;
                if (!$otp_data) {
                    $data['status'] = false;
                    $this->error['warning'] = $this->language->get('error_invalid_otp');
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
                    //$this->request->post['password'] = mt_rand(1000,9999);
                    // echo "<pre>";print_r($this->request->post);die;
                    // $accountmanagerid = NULL;
                    // $accountmanagerid =$this->request->post['accountmanagerid'];
                    // $log->write('accountmanagerid from API');
                    // $log->write($accountmanagerid);
                    // $customer_id = $this->model_account_customer->addCustomer($this->request->post,true);
                    
                    //update status and get customerid
                    $customer_id = $this->model_account_customer->updateCustomerStatus($this->request->post['email']);

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

                    // if (!$logged_in) {
                    //     $data['status'] = false;
                    // }

                    $data['text_new_signup_reward'] = $this->language->get('text_new_signup_reward');
                    $data['text_new_signup_credit'] = $this->language->get('text_new_signup_credit');

                    $data['message'] = $this->language->get('verify_mail_sent');                   

                    // delete otp
                    $this->model_account_customer->deleteOTP($this->request->post['phone'], $this->request->post['signup_otp'], 'register');

                    $data['success_message'] = $this->language->get('text_valid_otp');
                    $data['customer_id'] = $customer_id;
                }
            } else {
                // enter valid number throw error
                $data['status'] = false;
                //$data['error_warning'] = $this->language->get('error_invalid_otp');
                $this->error['warning'] = $this->language->get('error_invalid_otp');
            }
        }

        foreach ($this->error as $key => $value) {
            $data['errors'][] = ['type' => $key, 'body' => $value];
        }

        return $data;
    }


}
