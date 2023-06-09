<?php

class ModelAccountCustomer extends Model {

    //overrided for direct login
    public function addCustomer($data, $override = false) {
        
        $log = new Log('error.log');
        if(!isset($data['dob'])) {
            $log->write('customer in');
            $data['dob'] = null;
        }


        $log->write('customer add');
        $this->trigger->fire('pre.customer.add', $data);

        if (isset($data['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($data['customer_group_id'], $this->config->get('config_customer_group_display'))) {
            $customer_group_id = $data['customer_group_id'];
        } else {
            $customer_group_id = $this->config->get('config_customer_group_id');
        }

        if (isset($data['company_name']) ) {
            $company_name = $data['company_name'];
        } else {
            $company_name = '';
        }


        if (isset($data['company_address']) ) {
            $company_address = $data['company_address'];
        } else {
            $company_address = '';
        }




        $log->write($customer_group_id);
        $this->load->model('account/customer_group');

        $customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

        $log->write($customer_group_info);

        if($override){
            $customer_group_info['approval'] = 0;
        }
        if(!isset($data['fax'])) {
            $data['fax'] = null;
        }

        if(!isset($data['gender'])) {
            $data['gender'] = null;
        }
        
        if(isset($data['telephone'])) {
            //(21) 42353-5255
            $data['telephone'] = preg_replace("/[^0-9]/", "", $data['telephone']);
        }

        if (!isset($data['dob'])) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int) $customer_group_id . "', store_id = '" . (int) $this->config->get('config_store_id') . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', company_name = '" . $this->db->escape($data['company_name']) . "', company_address = '" . $this->db->escape($data['company_address']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', gender = '" . $this->db->escape($data['gender']). "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['account']) ? serialize($data['custom_field']['account']) : '') . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', newsletter = '" . (isset($data['newsletter']) ? (int) $data['newsletter'] : 0) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', approved = '" . (int) !$customer_group_info['approval'] . "', date_added = NOW()"); 

        } else {
           $this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int) $customer_group_id . "', store_id = '" . (int) $this->config->get('config_store_id') . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', company_name = '" . $this->db->escape($data['company_name']) . "', company_address = '" . $this->db->escape($data['company_address']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', gender = '" . $this->db->escape($data['gender']). "', dob = '" . $data['dob']. "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['account']) ? serialize($data['custom_field']['account']) : '') . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', newsletter = '" . (isset($data['newsletter']) ? (int) $data['newsletter'] : 0) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', approved = '" . (int) !$customer_group_info['approval'] . "', date_added = NOW()"); 

        }
        

        $customer_id = $this->db->getLastId();

        if (!empty($data['country_id'])) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int) $customer_id . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']). "', dob = '" . $data['dob'] . "', company = '" . $this->db->escape($data['company']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int) $data['country_id'] . "', zone_id = '" . (int) $data['zone_id'] . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['address']) ? serialize($data['custom_field']['address']) : '') . "'");

            $address_id = $this->db->getLastId();

            $this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int) $address_id . "' WHERE customer_id = '" . (int) $customer_id . "'");
        }

        //update refree id start

        if (isset($data['referee_user_id'])) {

            $this->db->query("UPDATE " . DB_PREFIX . "customer SET refree_user_id = '" . (int) $data['referee_user_id'] . "' WHERE customer_id = '" . (int) $customer_id . "'");
        }

        //update refree id end

        #Get Email Template
        if (!$customer_group_info['approval']) {
            #Customer Registration Register

            $subject = $this->emailtemplate->getSubject('Customer', 'customer_1', $data);
            $message = $this->emailtemplate->getMessage('Customer', 'customer_1', $data);
            $sms_message = $this->emailtemplate->getSmsMessage('Customer', 'customer_1', $data);
        } else {
            #Customer Registration Approve

            $data['confirm_code']  = substr(sha1(uniqid(mt_rand(), true)), 0, 25);

            //set token and send mail
            $this->setCustomerToken($data['confirm_code'],$customer_id);

            //customer_7 is for resend verification mail template
            //customer_2 is for verify registration mail template
            //customer_4 is for after customer verified

            /*$subject = $this->emailtemplate->getSubject('Customer', 'customer_7', $data);
            $message = $this->emailtemplate->getMessage('Customer', 'customer_7', $data);*/

            //unset($data['confirm_code']);

            /* verification mail end*/

            $subject = $this->emailtemplate->getSubject('Customer', 'customer_2', $data);
            $message = $this->emailtemplate->getMessage('Customer', 'customer_2', $data);
            $sms_message = $this->emailtemplate->getSmsMessage('Customer', 'customer_2', $data);
        }

        $mail = new Mail($this->config->get('config_mail'));
        $mail->setTo($data['email']);
        $mail->setFrom($this->config->get('config_from_email'));
        $mail->setSender($this->config->get('config_name'));
        $mail->setSubject($subject);
        $mail->setHTML($message);
        $mail->send();

        // send message here
        if ( $this->emailtemplate->getSmsEnabled('Customer','customer_1')) {
         
            $ret =  $this->emailtemplate->sendmessage($data['telephone'],$sms_message);
            
        }
        
        
        // Send to main admin email if new account email is enabled
        if ($this->config->get('config_account_mail')) {
            $mail->setTo($this->config->get('config_email'));
            $mail->send();

            $emails = explode(',', $this->config->get('config_alert_emails'));

            foreach ($emails as $email) {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $mail->setTo($email);
                    $mail->send();
                }
            }
        }

        $this->trigger->fire('post.customer.add', $customer_id);

        return $customer_id;
    }

    public function editCustomer($data) {
              
        $this->trigger->fire('pre.customer.edit', $data);

        $customer_id = $this->customer->getId();

        if(isset($data['telephone'])) {
            //(21) 42353-5255
            $data['telephone'] = preg_replace("/[^0-9]/", "", $data['telephone']);
        }

        if(!isset($data['fax'])) {
            $data['fax'] = null;
        }

        if(!isset($data['gender'])) {
            $data['gender'] = null;
        }
        //if(isset($data['dob'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) .  "', dob = '" . $data['dob'] .  "', gender = '" . $this->db->escape($data['gender']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "' WHERE customer_id = '" . (int) $customer_id . "'");
        //}

        $this->trigger->fire('post.customer.edit', $customer_id);
    }

    public function editCustomerApi($data) {
              
        $this->trigger->fire('pre.customer.edit', $data);

        $customer_id = $this->customer->getId();

        if(!isset($data['fax'])) {
            $data['fax'] = null;
        }

        if(!isset($data['gender'])) {
            $data['gender'] = null;
        }
        if(isset($data['telephone'])) {

            $data['telephone'] = preg_replace("/[^0-9]/", "", $data['telephone']);

            $this->db->query("UPDATE " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) .  "', dob = '" . $data['dob'] .  "', gender = '" . $this->db->escape($data['gender']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "' WHERE customer_id = '" . (int) $customer_id . "'");
        } else {
            
            $this->db->query("UPDATE " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) .  "', dob = '" . $data['dob'] .  "', gender = '" . $this->db->escape($data['gender']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "' WHERE customer_id = '" . (int) $customer_id . "'");
        }

        $this->trigger->fire('post.customer.edit', $customer_id);
    }


    public function editPassword($email, $password) {
        $this->trigger->fire('pre.customer.edit.password');

        $this->db->query("UPDATE " . DB_PREFIX . "customer SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "' WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

        $this->trigger->fire('post.customer.edit.password');
    }

    public function editPhoneNumber($customer_id, $telephone) {
        

        $this->db->query("UPDATE " . DB_PREFIX . "customer SET telephone = '" . $this->db->escape($telephone) . "' WHERE customer_id = '" . (int) $customer_id . "'");


    }


    public function editNewsletter($newsletter) {
        $this->trigger->fire('pre.customer.edit.newsletter');

        $this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = '" . (int) $newsletter . "' WHERE customer_id = '" . (int) $this->customer->getId() . "'");

        $this->trigger->fire('post.customer.edit.newsletter');
    }

    public function getCustomer($customer_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int) $customer_id . "'");

        return $query->row;
    }

    public function getTotalOrders($customer_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` o WHERE customer_id = '" . (int) $customer_id . "' AND o.order_status_id > '0' ");

        //return $query;
        return $query->row['total'];
    }


    public function getCustomerByEmail($email) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

        return $query->row;
    }

    public function getCustomerByPhone($phone) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE telephone='" . $this->db->escape($phone) . "'");
        return $query->row;
    }

    public function getOTP($customer_id,$otp,$type) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "otp WHERE otp='" . $this->db->escape($otp) . "' AND customer_id = '".$customer_id."' and type='".$type."'" );
        return $query->row;
    }

    public function saveOTP($customer_id,$otp,$type) {

        $query = $this->db->query("DELETE FROM " . DB_PREFIX . "otp WHERE customer_id = '".$customer_id."' and type='".$type."'");

        $this->db->query("INSERT INTO " . DB_PREFIX . "otp SET customer_id = '" . $this->db->escape($customer_id) . "', otp = '" . $this->db->escape($otp) . "', type = '".$type."', created_at = '" . $this->db->escape(date('Y-m-d H:i:s')) . "', updated_at = '" . $this->db->escape(date('Y-m-d H:i:s')) . "'");
    }

    public function deleteOTP($customer_id,$otp,$type) {

        //echo "<pre>";print_r($customer_id.$otp.$type);die;
        $query = $this->db->query("DELETE FROM " . DB_PREFIX . "otp WHERE otp='" . $this->db->escape($otp) . "' AND customer_id = '".$customer_id."' and type='".$type."'");
        return true;
    }
    

    public function getCustomerByToken($token) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE token = '" . $this->db->escape($token) . "' AND token != ''");

        $this->db->query("UPDATE " . DB_PREFIX . "customer SET token = ''");

        return $query->row;
    }

    public function setCustomerToken($token,$customer_id) {
       
        $this->db->query("UPDATE " . DB_PREFIX . "customer SET token = '".$token."' where customer_id = ".$customer_id);

        return true;
    }


    public function getTotalCustomersByEmail($email) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

        return $query->row['total'];
    }

    public function getTotalCustomersByPhone($telephone) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE telephone = '" . $telephone . "'");

        return $query->row['total'];
    }

    public function getIps($customer_id) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_ip` WHERE customer_id = '" . (int) $customer_id . "'");

        return $query->rows;
    }

    public function isBanIp($ip) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_ban_ip` WHERE ip = '" . $this->db->escape($ip) . "'");

        return $query->num_rows;
    }

    public function addLoginAttempt($email) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_login WHERE email = '" . $this->db->escape(utf8_strtolower((string) $email)) . "' AND ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'");

        if (!$query->num_rows) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "customer_login SET email = '" . $this->db->escape(utf8_strtolower((string) $email)) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', total = 1, date_added = '" . $this->db->escape(date('Y-m-d H:i:s')) . "', date_modified = '" . $this->db->escape(date('Y-m-d H:i:s')) . "'");
        } else {
            $this->db->query("UPDATE " . DB_PREFIX . "customer_login SET total = (total + 1), date_modified = '" . $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE customer_login_id = '" . (int) $query->row['customer_login_id'] . "'");
        }
    }

    public function getLoginAttempts($email) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_login` WHERE email = '" . $this->db->escape(utf8_strtolower($email)) . "'");

        return $query->row;
    }

    public function deleteLoginAttempts($email) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "customer_login` WHERE email = '" . $this->db->escape(utf8_strtolower($email)) . "'");
    }

    public function resetPasswordMail($email, $password) {
        $customer = $this->getCustomerByEmail($email);

        $data = array(
            'firstname' => $customer['firstname'],
            'lastname' => $customer['lastname'],
            'email' => $customer['email'],
            'password' => $password
        );

        #Reset Password id = 3
        $subject = $this->emailtemplate->getSubject('Customer', 'customer_3', $data);
        $message = $this->emailtemplate->getMessage('Customer', 'customer_3', $data);

        $mail = new Mail($this->config->get('config_mail'));
        $mail->setTo($data['email']);
        $mail->setFrom($this->config->get('config_from_email'));
        $mail->setSender($this->config->get('config_name'));
        $mail->setSubject($subject);
        $mail->setHTML($message);
        $mail->send();
    }

    public function getIuguCustomerId($customer_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_to_customer_iugu WHERE customer_id = '" . (int) $customer_id . "'");

        return $query->row;
    }

    public function saveIuguCustomerId($data) {

         $this->db->query("INSERT INTO " . DB_PREFIX . "customer_to_customer_iugu SET customer_id = '" .  $data['customer_id'] . "', iugu_customer_id = '" . $data['iugu_customer_id'] ."', payment_method_id = '" . $data['id'] . "', brand = '" .$data['brand']. "', holder_name = '" . $data['holder_name'] . "', display_number = '" . $data['display_number'] . "', description = '" . $data['description'] . "'");
    }

    public function getIuguCustomerPaymentIds($customer_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_to_customer_iugu WHERE customer_id = '" . (int) $customer_id . "'");

        return $query->rows;
    }

    public function approve($customer_id) {

        $customer_info = $this->getCustomer($customer_id);

        if ($customer_info) {
            $this->db->query("UPDATE " . DB_PREFIX . "customer SET approved = '1' , token = '' WHERE customer_id = '" . (int) $customer_id . "'");



            $store_name = $this->config->get('config_name');
            $store_url = HTTP_CATALOG . 'index.php?path=account/login';
            

            $customer_info['store_name'] = $store_name;
            $customer_info['account_href'] = $store_url;

            $subject = $this->emailtemplate->getSubject('Customer', 'customer_4', $customer_info);
            $message = $this->emailtemplate->getMessage('Customer', 'customer_4', $customer_info);

            $mail = new Mail($this->config->get('config_mail'));
            $mail->setTo($customer_info['email']);
            $mail->setFrom($this->config->get('config_from_email'));
            $mail->setSender($this->config->get('config_name'));
            $mail->setSubject($subject);
            $mail->setHTML($message);
            $mail->send();

            $sms_message = $this->emailtemplate->getSmsMessage('Customer', 'customer_4', $customer_info);
            // send message here
            if ( $this->emailtemplate->getSmsEnabled('Customer','customer_4')) {
             
                $ret =  $this->emailtemplate->sendmessage($customer_info['telephone'],$sms_message);
                
            }

        }
    }

    public function resendVerificationEmail($data,$customer_id) {

        $data['confirm_code']  = substr(sha1(uniqid(mt_rand(), true)), 0, 25);

        //set token and send mail
        $this->setCustomerToken($data['confirm_code'],$customer_id);


        $verification_subject = $this->emailtemplate->getSubject('Customer', 'customer_7', $data);
        $verification_message = $this->emailtemplate->getMessage('Customer', 'customer_7', $data);

        unset($data['confirm_code']);

        $mail = new Mail($this->config->get('config_mail'));
        $mail->setTo($data['email']);
        $mail->setFrom($this->config->get('config_from_email'));
        $mail->setSender($this->config->get('config_name'));
        $mail->setSubject($verification_subject);
        $mail->setHTML($verification_message);
        $mail->send();

        return true;   
    }

    public function getAdminConfigSettings($store_id,$code) {

        $query = $this->db->query( "Select `key`,`value` from  `" . DB_PREFIX . "setting` WHERE store_id = '" . (int)$store_id . "' AND `code` = '" . $this->db->escape( $code ) . "'" );

        return $query->rows;
    }
}
