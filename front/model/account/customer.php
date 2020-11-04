<?php

class ModelAccountCustomer extends Model {

//overrided for direct login
    public function addCustomer($data, $override = false) {
//echo '<pre>';print_r($data);exit;
        $log = new Log('error.log');
        if (!isset($data['dob'])) {
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

        if (isset($data['company_name'])) {
            $company_name = $data['company_name'];
        } else {
            $company_name = '';
        }

        if (isset($data['company_address'])) {
            $company_address = $data['company_address'];
        } else {
            $company_address = '';
        }

        $log->write($customer_group_id);
        $this->load->model('account/customer_group');

        $customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

        $log->write($customer_group_info);

        if ($override) {
            $customer_group_info['approval'] = 0;
        }
        if (!isset($data['fax'])) {
            $data['fax'] = null;
        }

        if (!isset($data['gender'])) {
            $data['gender'] = null;
        }

        if (isset($data['telephone'])) {
//(21) 42353-5255
            $data['telephone'] = preg_replace('/[^0-9]/', '', $data['telephone']);
        }

        $customer_category = null;
        if (null != $data['parent']) {
            $parent_info = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . (int) $data['parent'] . "'");
            $customer_category = $parent_info->row['customer_category'];
        }
        if (!isset($data['dob'])) {
//$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int) $customer_group_id . "', store_id = '" . (int) $this->config->get('config_store_id') . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', company_name = '" . $this->db->escape($data['company_name']) . "', company_address = '" . $this->db->escape($data['company_address']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', gender = '" . $this->db->escape($data['gender']). "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['account']) ? serialize($data['custom_field']['account']) : '') . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', newsletter = '" . (isset($data['newsletter']) ? (int) $data['newsletter'] : 0) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', approved = '" . (int) !$customer_group_info['approval'] . "', date_added = NOW()");
            $this->db->query('INSERT INTO ' . DB_PREFIX . "customer SET customer_group_id = '" . (int) $customer_group_id . "', store_id = '" . (int) $this->config->get('config_store_id') . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', company_name = '" . $this->db->escape($data['company_name']) . "', company_address = '" . $this->db->escape($data['company_address']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', gender = '" . $this->db->escape($data['gender']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['account']) ? serialize($data['custom_field']['account']) : '') . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', newsletter = '" . (isset($data['newsletter']) ? (int) $data['newsletter'] : 0) . "',parent = '" . (isset($data['parent']) ? (int) $data['parent'] : null) . "',customer_category = '" . (null != $customer_category ? $customer_category : null) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', approved = '" . (int) $customer_group_info['approval'] . "', date_added = NOW()");
        } else {
//$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int) $customer_group_id . "', store_id = '" . (int) $this->config->get('config_store_id') . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', company_name = '" . $this->db->escape($data['company_name']) . "', company_address = '" . $this->db->escape($data['company_address']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', gender = '" . $this->db->escape($data['gender']). "', dob = '" . $data['dob']. "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['account']) ? serialize($data['custom_field']['account']) : '') . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', newsletter = '" . (isset($data['newsletter']) ? (int) $data['newsletter'] : 0) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', approved = '" . (int) !$customer_group_info['approval'] . "', date_added = NOW()");
            $this->db->query('INSERT INTO ' . DB_PREFIX . "customer SET customer_group_id = '" . (int) $customer_group_id . "', store_id = '" . (int) $this->config->get('config_store_id') . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', company_name = '" . $this->db->escape($data['company_name']) . "', company_address = '" . $this->db->escape($data['company_address']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', gender = '" . $this->db->escape($data['gender']) . "', dob = '" . $data['dob'] . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['account']) ? serialize($data['custom_field']['account']) : '') . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', newsletter = '" . (isset($data['newsletter']) ? (int) $data['newsletter'] : 0) . "',parent = '" . (isset($data['parent']) ? (int) $data['parent'] : null) . "',customer_category = '" . (null != $customer_category ? $customer_category : null) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', approved = '" . (int) $customer_group_info['approval'] . "', date_added = NOW()");
        }

        $customer_id = $this->db->getLastId();

        if (!empty($data['country_id'])) {
            $this->db->query('INSERT INTO ' . DB_PREFIX . "address SET customer_id = '" . (int) $customer_id . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', dob = '" . $data['dob'] . "', company = '" . $this->db->escape($data['company']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int) $data['country_id'] . "', zone_id = '" . (int) $data['zone_id'] . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['address']) ? serialize($data['custom_field']['address']) : '') . "'");

            $address_id = $this->db->getLastId();

            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET address_id = '" . (int) $address_id . "' WHERE customer_id = '" . (int) $customer_id . "'");
        }

        if (!empty($data['address'])) {
//$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int) $customer_id . "', name = '" . $this->db->escape($data['firstname']) . " " . $this->db->escape($data['lastname']). "',  address = '" . $this->db->escape($data['address']) . "', location = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int) $data['country_id'] . "', zone_id = '" . (int) $data['zone_id'] . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['address']) ? serialize($data['custom_field']['address']) : '') . "'");
            $this->db->query('INSERT INTO ' . DB_PREFIX . "address SET customer_id = '" . (int) $customer_id . "', name = '" . $this->db->escape($data['firstname']) . ' ' . $this->db->escape($data['lastname']) . "',  address = '" . $this->db->escape($data['address']) . ' ' . $this->db->escape($data['location']) . "', building_name = '" . $this->db->escape($data['house_building']) . "'");
            $address_id = $this->db->getLastId();
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET address_id = '" . (int) $address_id . "' WHERE customer_id = '" . (int) $customer_id . "'");
        }

        if (!empty($data['company_address'])) {
//$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int) $customer_id . "', name = '" . $this->db->escape($data['firstname']) . " " . $this->db->escape($data['lastname']). "',  address = '" . $this->db->escape($data['address']) . "', location = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int) $data['country_id'] . "', zone_id = '" . (int) $data['zone_id'] . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['address']) ? serialize($data['custom_field']['address']) : '') . "'");
            $this->db->query('INSERT INTO ' . DB_PREFIX . "address SET customer_id = '" . (int) $customer_id . "', name = '" . $this->db->escape($data['firstname']) . ' ' . $this->db->escape($data['lastname']) . "',  address = '" . $this->db->escape($data['company_address']) . ' ' . $this->db->escape($data['modal_address_locality']) . "', flat_number = '" . $this->db->escape($data['company_address']) . "', building_name = '" . $this->db->escape($data['modal_address_locality']) . "',landmark = '" . $this->db->escape($data['modal_address_locality']) . "', latitude = '" . $this->db->escape($data['latitude']) . "', longitude = '" . $this->db->escape($data['longitude']) . "', address_type = '" . 'office' . "'");
            $address_id = $this->db->getLastId();
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET address_id = '" . (int) $address_id . "' WHERE customer_id = '" . (int) $customer_id . "'");
        }

//update refree id start

        if (isset($data['referee_user_id'])) {
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET refree_user_id = '" . (int) $data['referee_user_id'] . "' WHERE customer_id = '" . (int) $customer_id . "'");
        }

//update refree id end
//Get Email Template
        if (!$customer_group_info['approval']) {
            try {
//Customer Registration Register

            $subject = $this->emailtemplate->getSubject('Customer', 'customer_1', $data);
            $message = $this->emailtemplate->getMessage('Customer', 'customer_1', $data);
            $sms_message = $this->emailtemplate->getSmsMessage('Customer', 'customer_1', $data);

            $mail = new Mail($this->config->get('config_mail'));
            $mail->setTo($data['email']);
            $mail->setFrom($this->config->get('config_from_email'));
            $mail->setSender($this->config->get('config_name'));
            $mail->setSubject($subject);
            $mail->setHTML($message);
            $mail->send();
            } catch (Exception $e) {
                
            }
        } else {
//Customer Registration Approve

            $data['confirm_code'] = substr(sha1(uniqid(mt_rand(), true)), 0, 25);

//set token and send mail
            $this->setCustomerToken($data['confirm_code'], $customer_id);

//customer_7 is for resend verification mail template
//customer_2 is for verify registration mail template
//customer_4 is for after customer verified

            /* $subject = $this->emailtemplate->getSubject('Customer', 'customer_7', $data);
              $message = $this->emailtemplate->getMessage('Customer', 'customer_7', $data); */

//unset($data['confirm_code']);

            /* verification mail end */

// $subject = $this->emailtemplate->getSubject('Customer', 'customer_2', $data);
// $message = $this->emailtemplate->getMessage('Customer', 'customer_2', $data);
            $sms_message = $this->emailtemplate->getSmsMessage('Customer', 'customer_2', $data);
        }

// send message here
        if ($this->emailtemplate->getSmsEnabled('Customer', 'customer_1')) {
            $ret = $this->emailtemplate->sendmessage($data['telephone'], $sms_message);
        }

// Send to main admin email if new account email is enabled
//commented as particular message is not required.
// if ($this->config->get('config_account_mail')) {
//     $mail = new Mail($this->config->get('config_mail'));
//     $mail->setTo($this->config->get('config_email'));
//     $mail->send();
//     $emails = explode(',', $this->config->get('config_alert_emails'));
//     foreach ($emails as $email) {
//         if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
//             $mail->setTo($email);
//             $mail->send();
//         }
//     }
// }

        $this->trigger->fire('post.customer.add', $customer_id);

        return $customer_id;
    }

    public function editCustomer($data) {
// echo "<pre>";print_r($data);die;
        $this->trigger->fire('pre.customer.edit', $data);

        $customer_id = $this->customer->getId();

        if (isset($data['telephone'])) {
//(21) 42353-5255
            $data['telephone'] = preg_replace('/[^0-9]/', '', $data['telephone']);
        }

        if (!isset($data['fax'])) {
            $data['fax'] = null;
        }

        if (!isset($data['gender'])) {
            $data['gender'] = null;
        }
//if(isset($data['dob'])) {
        $this->db->query('UPDATE ' . DB_PREFIX . "customer SET  customer_group_id = '" . (int) $data['customer_group_id'] . "' , firstname = '" . $this->db->escape($data['firstname']) . "', dob = '" . $data['dob'] . "', gender = '" . $this->db->escape($data['gender']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company_name = '" . $this->db->escape($data['companyname']) . "', company_address = '" . $this->db->escape($data['companyaddress']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "' WHERE customer_id = '" . (int) $customer_id . "'");
//}

        $this->trigger->fire('post.customer.edit', $customer_id);
    }

    public function editCustomerApi($data) {
        $this->trigger->fire('pre.customer.edit', $data);

        $customer_id = $this->customer->getId();

        if (!isset($data['fax'])) {
            $data['fax'] = null;
        }

        if (!isset($data['gender'])) {
            $data['gender'] = null;
        }
        if (isset($data['telephone'])) {
            $data['telephone'] = preg_replace('/[^0-9]/', '', $data['telephone']);

            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) . "', dob = '" . $data['dob'] . "', gender = '" . $this->db->escape($data['gender']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "' WHERE customer_id = '" . (int) $customer_id . "'");
        } else {
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) . "', dob = '" . $data['dob'] . "', gender = '" . $this->db->escape($data['gender']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "' WHERE customer_id = '" . (int) $customer_id . "'");
        }

        $this->trigger->fire('post.customer.edit', $customer_id);
    }

    public function resetPassword($email, $password) {
//echo "<pre>";print_r($password);die;, ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'

        if ($password && 'default' != $password) {
// echo "<pre>";print_r($this->db->escape($this->request->server['REMOTE_ADDR']));die;

            $this->trigger->fire('pre.customer.edit.password');

            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET  temppassword = '" . $this->db->escape(1) . "',   salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

            $this->trigger->fire('post.customer.edit.password');
        }
    }

    public function editPassword($email, $password) {
//  echo "<pre>";print_r($password);die;

        if ($password && 'default' != $password) {
// echo "<pre>";print_r($password);die;

            $this->trigger->fire('pre.customer.edit.password');

            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET   temppassword = '" . $this->db->escape(0) . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "' WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

            $this->trigger->fire('post.customer.edit.password');
        }
    }

    public function editPhoneNumber($customer_id, $telephone) {
        $this->db->query('UPDATE ' . DB_PREFIX . "customer SET telephone = '" . $this->db->escape($telephone) . "' WHERE customer_id = '" . (int) $customer_id . "'");
    }

    public function editNewsletter($newsletter) {
        $this->trigger->fire('pre.customer.edit.newsletter');

        $this->db->query('UPDATE ' . DB_PREFIX . "customer SET newsletter = '" . (int) $newsletter . "' WHERE customer_id = '" . (int) $this->customer->getId() . "'");

        $this->trigger->fire('post.customer.edit.newsletter');
    }

    public function getCustomer($customer_id) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . (int) $customer_id . "'");

        return $query->row;
    }

    public function getCustomerOtherInfo($customer_id) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer_other_info WHERE customer_id = '" . (int) $customer_id . "'");

        return $query->row;
    }

    public function getTotalOrders($customer_id) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `' . DB_PREFIX . "order` o WHERE customer_id = '" . (int) $customer_id . "' AND o.order_status_id > '0' ");

//return $query;
        return $query->row['total'];
    }

    public function getCustomerByEmail($email) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

        return $query->row;
    }

    public function getCustomerByPhone($phone) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer WHERE telephone='" . $this->db->escape($phone) . "'");

        return $query->row;
    }

    public function getOTP($customer_id, $otp, $type) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "otp WHERE otp='" . $this->db->escape($otp) . "' AND customer_id = '" . $customer_id . "' and type='" . $type . "'");

        return $query->row;
    }

    public function saveOTP($customer_id, $otp, $type) {
        $query = $this->db->query('DELETE FROM ' . DB_PREFIX . "otp WHERE customer_id = '" . $customer_id . "' and type='" . $type . "'");

        $this->db->query('INSERT INTO ' . DB_PREFIX . "otp SET customer_id = '" . $this->db->escape($customer_id) . "', otp = '" . $this->db->escape($otp) . "', type = '" . $type . "', created_at = '" . $this->db->escape(date('Y-m-d H:i:s')) . "', updated_at = '" . $this->db->escape(date('Y-m-d H:i:s')) . "'");
    }

    public function deleteOTP($customer_id, $otp, $type) {
//echo "<pre>";print_r($customer_id.$otp.$type);die;
        $query = $this->db->query('DELETE FROM ' . DB_PREFIX . "otp WHERE otp='" . $this->db->escape($otp) . "' AND customer_id = '" . $customer_id . "' and type='" . $type . "'");

        return true;
    }

    public function getCustomerByToken($token) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer WHERE token = '" . $this->db->escape($token) . "' AND token != ''");

        $this->db->query('UPDATE ' . DB_PREFIX . "customer SET token = ''");

        return $query->row;
    }

    public function setCustomerToken($token, $customer_id) {
        $this->db->query('UPDATE ' . DB_PREFIX . "customer SET token = '" . $token . "' where customer_id = " . $customer_id);

        return true;
    }

    public function getTotalCustomersByEmail($email) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

        return $query->row['total'];
    }

    public function getTotalCustomersByPhone($telephone) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . "customer WHERE telephone = '" . $telephone . "'");

        return $query->row['total'];
    }

    public function getIps($customer_id) {
        $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . "customer_ip` WHERE customer_id = '" . (int) $customer_id . "'");

        return $query->rows;
    }

    public function isBanIp($ip) {
        $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . "customer_ban_ip` WHERE ip = '" . $this->db->escape($ip) . "'");

        return $query->num_rows;
    }

    public function addLoginAttempt($email) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer_login WHERE email = '" . $this->db->escape(utf8_strtolower((string) $email)) . "' AND ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'");

        if (!$query->num_rows) {
            $this->db->query('INSERT INTO ' . DB_PREFIX . "customer_login SET email = '" . $this->db->escape(utf8_strtolower((string) $email)) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', total = 1, date_added = '" . $this->db->escape(date('Y-m-d H:i:s')) . "', date_modified = '" . $this->db->escape(date('Y-m-d H:i:s')) . "'");
        } else {
            $this->db->query('UPDATE ' . DB_PREFIX . "customer_login SET total = (total + 1), date_modified = '" . $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE customer_login_id = '" . (int) $query->row['customer_login_id'] . "'");
        }
    }

    public function getLoginAttempts($email) {
        $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . "customer_login` WHERE email = '" . $this->db->escape(utf8_strtolower($email)) . "'");

        return $query->row;
    }

    public function deleteLoginAttempts($email) {
        $this->db->query('DELETE FROM `' . DB_PREFIX . "customer_login` WHERE email = '" . $this->db->escape(utf8_strtolower($email)) . "'");
    }

    public function resetPasswordMail($email, $password) {
        $customer = $this->getCustomerByEmail($email);

        $data = [
            'firstname' => $customer['firstname'],
            'lastname' => $customer['lastname'],
            'email' => $customer['email'],
            'password' => $password,
            'ip_address' => $customer['ip'],
        ];

//Reset Password id = 3
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
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer_to_customer_iugu WHERE customer_id = '" . (int) $customer_id . "'");

        return $query->row;
    }

    public function saveIuguCustomerId($data) {
        $this->db->query('INSERT INTO ' . DB_PREFIX . "customer_to_customer_iugu SET customer_id = '" . $data['customer_id'] . "', iugu_customer_id = '" . $data['iugu_customer_id'] . "', payment_method_id = '" . $data['id'] . "', brand = '" . $data['brand'] . "', holder_name = '" . $data['holder_name'] . "', display_number = '" . $data['display_number'] . "', description = '" . $data['description'] . "'");
    }

    public function getIuguCustomerPaymentIds($customer_id) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer_to_customer_iugu WHERE customer_id = '" . (int) $customer_id . "'");

        return $query->rows;
    }

    public function approve($customer_id) {
        $customer_info = $this->getCustomer($customer_id);

        if ($customer_info) {
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET approved = '1' , token = '' WHERE customer_id = '" . (int) $customer_id . "'");

//AFTER CUSTOMER VERIFIED MAIL SENDING
//            $store_name = $this->config->get('config_name');
//            $store_url = HTTP_CATALOG . 'index.php?path=account/login';
//
//
//            $customer_info['store_name'] = $store_name;
//            $customer_info['account_href'] = $store_url;
//
//            $subject = $this->emailtemplate->getSubject('Customer', 'customer_4', $customer_info);
//            $message = $this->emailtemplate->getMessage('Customer', 'customer_4', $customer_info);
//
//            $mail = new Mail($this->config->get('config_mail'));
//            $mail->setTo($customer_info['email']);
//            $mail->setFrom($this->config->get('config_from_email'));
//            $mail->setSender($this->config->get('config_name'));
//            $mail->setSubject($subject);
//            $mail->setHTML($message);
//            $mail->send();
//
//            $sms_message = $this->emailtemplate->getSmsMessage('Customer', 'customer_4', $customer_info);
//            // send message here
//            if ($this->emailtemplate->getSmsEnabled('Customer', 'customer_4')) {
//
//                $ret = $this->emailtemplate->sendmessage($customer_info['telephone'], $sms_message);
//            }
        }
    }

    public function approvecustom($customer_id, $approve_id) {
        $customer_info = $this->getCustomer($customer_id);

        if ($customer_info) {
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET approved = '" . (int) $approve_id . "', token = '' WHERE customer_id = '" . (int) $customer_id . "'");
        }
    }

    public function deletecustom($customer_id) {
        $customer_info = $this->getCustomer($customer_id);

        if ($customer_info) {
            $this->db->query('DELETE FROM `' . DB_PREFIX . "customer` WHERE `customer_id` = '" . (int) $customer_id . "'");
        }
    }

    public function resendVerificationEmail($data, $customer_id) {
        $data['confirm_code'] = substr(sha1(uniqid(mt_rand(), true)), 0, 25);

//set token and send mail
        $this->setCustomerToken($data['confirm_code'], $customer_id);

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

    public function getAdminConfigSettings($store_id, $code) {
        $query = $this->db->query('Select `key`,`value` from  `' . DB_PREFIX . "setting` WHERE store_id = '" . (int) $store_id . "' AND `code` = '" . $this->db->escape($code) . "'");

        return $query->rows;
    }

    public function addEditCustomerInfo($customer_id, $data) {
        try {
            $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer_other_info WHERE customer_id = '" . (int) $customer_id . "'");
            if ($query->num_rows > 0) {
                $sql = 'UPDATE  ' . DB_PREFIX . "customer_other_info SET  location = '" . $data['location'] . "', requirement_per_week = '" . $this->db->escape($data['requirement']) . "', mandatory_veg_fruits = '" . $this->db->escape($data['mandatory_products']) . "' WHERE customer_id = '" . (int) $customer_id . "'";
            } else {
                $sql = 'INSERT INTO ' . DB_PREFIX . "customer_other_info SET customer_id = '" . (int) $customer_id . "', location = '" . $data['location'] . "', requirement_per_week = '" . $this->db->escape($data['requirement']) . "', mandatory_veg_fruits = '" . $this->db->escape($data['mandatory_products']) . "', date_added = NOW()";
            }
            $this->db->query($sql);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function addUpdateChannelMapping($customer_id, $data) {
        try {
            $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer_channel_mapping WHERE guid = '" . $data['guid'] . "'");
            if ($query->num_rows > 0) {
                $sql = 'UPDATE  ' . DB_PREFIX . "customer_channel_mapping SET  channel_id = '" . $data['channel_id'] . "', channel_token_id = '" . $this->db->escape($data['channel_token_id']) . "', conversation_id = '" . $this->db->escape($data['conversation_id']) . "' , customer_id = '" . (int) $customer_id . "' WHERE guid = '" . $data['guid'] . "'";
            } else {
                $sql = 'INSERT INTO ' . DB_PREFIX . "customer_channel_mapping SET channel_id = '" . $data['channel_id'] . "', channel_token_id = '" . $this->db->escape($data['channel_token_id']) . "', conversation_id = '" . $this->db->escape($data['conversation_id']) . "' , customer_id = '" . (int) $customer_id . "', guid ='" . $data['guid'] . "', date_added = NOW()";
            }
            $this->db->query($sql);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function cacheProductPrices($store_id) {
        $this->cache->delete('category_price_data');
        $cache_price_data = [];
        $sql = 'SELECT * FROM `' . DB_PREFIX . "product_category_prices` where `store_id` = $store_id";
//echo $sql;exit;
        $resultsdata = $this->db->query($sql);
//echo '<pre>'; print_r($resultsdata);exit;
        if (count($resultsdata->rows) > 0) {
            foreach ($resultsdata->rows as $result) {
                $cache_price_data[$result['product_store_id'] . '_' . $result['price_category'] . '_' . $store_id] = $result['price'];
            }
        }
        $this->cache->set('category_price_data', $cache_price_data);
    }

    public function CheckHeIsParent() {
        $is_he_parent = $this->db->query('SELECT c.parent FROM ' . DB_PREFIX . "customer c WHERE customer_id = '" . (int) $this->customer->getId() . "'");

        $parent = null;
        $log = new Log('error.log');
        if (is_array($is_he_parent->rows) && count($is_he_parent->rows) > 0) {
            foreach ($is_he_parent->rows as $is_he) {
                if (null != $is_he['parent'] && $is_he['parent'] > 0) {
                    $parent = $is_he['parent'];
                }
            }
        }

        return $parent;
    }


    public function CheckApprover() {
        
        $query = $this->db->query('SELECT c.order_approval_access,c.order_approval_access_role FROM ' . DB_PREFIX . "customer c WHERE customer_id = '" . (int) $this->customer->getId() . "'");
          
        
        // echo '<pre>';print_r($query->row);exit;
        return $query->row;
    }


    public function getCustomerParentDetails($customer_id) {
        $customer_parent_details = NULL;
        $customer_details = $this->getCustomer($customer_id);
        if ($customer_details != NULL && $customer_details['parent'] > 0 && $customer_details['parent'] != NULL) {
//$log = new Log('error.log');
//$log->write($customer_details);
            $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . (int) $customer_details['parent'] . "'");
            return $query->row;
        } else {
            return $customer_parent_details;
        }
    }

    public function getCustomerParentEmail($customer_id) {
        $customer_parent_details = NULL;
        $customer_details = $this->getCustomer($customer_id);
        if ($customer_details != NULL && $customer_details['parent'] > 0 && $customer_details['parent'] != NULL) {
//$log = new Log('error.log');
//$log->write($customer_details);
            $query = $this->db->query('SELECT  customer_id,email,firstname,lastname FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . (int) $customer_details['parent'] . "'");
            return $query->row;
        } else {
            return $customer_parent_details;
        }
    }

    public function getSubusersByParent($parent_user_id) {
        $sub_users = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer c WHERE c.parent = '" . (int) $parent_user_id . "'");
        return $sub_users->rows;
    }

    public function UpdateOrderApprovalAccess($parent_id, $customer_id, $status, $role) {
        $sql = 'UPDATE  ' . DB_PREFIX . "customer SET  order_approval_access = '0', order_approval_access_role = NULL WHERE parent = '" . (int) $parent_id . "' AND order_approval_access = 1 AND order_approval_access_role = '" . $role . "'";
        $this->db->query($sql);

        $sql2 = 'UPDATE  ' . DB_PREFIX . "customer SET  order_approval_access = '" . (int) $status . "', order_approval_access_role = '" . $role . "' WHERE parent = '" . (int) $parent_id . "' AND customer_id ='" . (int) $customer_id . "'";
        $this->db->query($sql2);
    }

    public function GetOrderApprovalAccessByParentId($parent_id, $customer_id) {
        $order_approval_access = $this->db->query('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . "customer c WHERE c.customer_id = '" . (int) $customer_id . "' AND c.parent = '" . (int) $parent_id . "' AND c.order_approval_access = 1 AND (c.order_approval_access_role = 'head_chef' OR c.order_approval_access_role = 'procurement_person')");
        return $order_approval_access->row['total'];
    }

}
