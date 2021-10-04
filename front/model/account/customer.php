<?php

class ModelAccountCustomer extends Model {

//overrided for direct login
    public function addCustomer($data, $override = false, $onlycustomer = false) {
        //echo '<pre>';print_r($data);exit;
        $log = new Log('error.log');
        $log->write($data['login_latitude']);
        $log->write($data['login_longitude']);
        if (isset($data['cityid']) && $data['cityid'] != NULL && $data['cityid'] > 0) {
            $data['cityid'] = $data['cityid'];
        } else {
            $data['cityid'] = 32;
        }

        $customer_accountmanager_id = NULL;
        if (isset($data['accountmanagerid'])) {
            $accountmanagerid = $data['accountmanagerid'];
            $log->write('accountmanagerid');
            $log->write($accountmanagerid);
            $log->write('accountmanagerid');
            $account_manager_details = $this->getAccountManagerId($accountmanagerid);
            if (isset($account_manager_details) && is_array($account_manager_details) && array_key_exists('user_id', $account_manager_details) && array_key_exists('user_group_id', $account_manager_details) && $account_manager_details['user_group_id'] == $this->config->get('config_account_manager_group_id') && $account_manager_details['user_id'] > 0) {
                $customer_accountmanager_id = $account_manager_details['user_id'];
            } else {
                $random_accountmanager = $this->getRandomAccountManagerId();
                $customer_accountmanager_id = $random_accountmanager['user_id'];
            }
        } else {
            $random_accountmanager = $this->getRandomAccountManagerId();
            $log->write('random_accountmanager');
            $log->write($random_accountmanager);
            $log->write('random_accountmanager');
            $customer_accountmanager_id = $random_accountmanager['user_id'];
        }

        $login_latitude = NULL;
        if (isset($data['login_latitude'])) {
            $login_latitude = $data['login_latitude'];
        }

        $sub_customer_order_approval = 1;
        if (isset($data['sub_customer_order_approval'])) {
            $sub_customer_order_approval = $data['sub_customer_order_approval'];
        }

        $login_longitude = NULL;
        if (isset($data['login_longitude'])) {
            $login_longitude = $data['login_longitude'];
        }

        if (!isset($data['dob'])) {
            $log->write('customer in');
            $data['dob'] = null;
        }

        $log->write('customer add');
        $this->trigger->fire('pre.customer.add', $data);
        //below line commented,as the settings are not checking through out the application.
        if (isset($data['customer_group_id'])) {// && is_array($this->config->get('config_customer_group_display')) && in_array($data['customer_group_id'], $this->config->get('config_customer_group_display')))
            $customer_group_id = $data['customer_group_id'];
        } elseif ($this->config->get('config_customer_group_id') > 0) {
            $customer_group_id = $this->config->get('config_customer_group_id');
        } else {
            $customer_group_id = 9;
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

        // echo "<pre>";print_r($customer_group_info);die;
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

        $source = '';
        if (isset($data['source'])) {
            $source = $data['source'];
        }

        $status = 1;
        if (isset($data['status'])) {
            $status = $data['status'];
        }

        $customer_category = null;
        if (null != $data['parent']) {
            $parent_info = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . (int) $data['parent'] . "'");
            $customer_category = $parent_info->row['customer_category'];
        }
        if (!isset($data['dob'])) {
            //$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int) $customer_group_id . "', store_id = '" . (int) $this->config->get('config_store_id') . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', company_name = '" . $this->db->escape($data['company_name']) . "', company_address = '" . $this->db->escape($data['company_address']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', gender = '" . $this->db->escape($data['gender']). "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['account']) ? serialize($data['custom_field']['account']) : '') . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', newsletter = '" . (isset($data['newsletter']) ? (int) $data['newsletter'] : 0) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', approved = '" . (int) !$customer_group_info['approval'] . "', date_added = NOW()");
            $this->db->query('INSERT INTO ' . DB_PREFIX . "customer SET customer_group_id = '" . (int) $customer_group_id . "', store_id = '" . (int) $this->config->get('config_store_id') . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', company_name = '" . $this->db->escape($data['company_name']) . "', company_address = '" . $this->db->escape($data['company_address']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', gender = '" . $this->db->escape($data['gender']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['account']) ? serialize($data['custom_field']['account']) : '') . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', newsletter = '" . (isset($data['newsletter']) ? (int) $data['newsletter'] : 0) . "',parent = '" . (isset($data['parent']) ? (int) $data['parent'] : null) . "',customer_category = '" . (null != $customer_category ? $customer_category : null) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '" . (int) $status . "', approved = '" . (int) $customer_group_info['approval'] . "', source = '" . $source . "', latitude = '" . $login_latitude . "', longitude = '" . $login_longitude . "', sub_customer_order_approval = '" . $sub_customer_order_approval . "', account_manager_id = '" . $customer_accountmanager_id . "', date_added = NOW()");
        } else {
            //$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int) $customer_group_id . "', store_id = '" . (int) $this->config->get('config_store_id') . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', company_name = '" . $this->db->escape($data['company_name']) . "', company_address = '" . $this->db->escape($data['company_address']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', gender = '" . $this->db->escape($data['gender']). "', dob = '" . $data['dob']. "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['account']) ? serialize($data['custom_field']['account']) : '') . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', newsletter = '" . (isset($data['newsletter']) ? (int) $data['newsletter'] : 0) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', approved = '" . (int) !$customer_group_info['approval'] . "', date_added = NOW()");
            $this->db->query('INSERT INTO ' . DB_PREFIX . "customer SET customer_group_id = '" . (int) $customer_group_id . "', store_id = '" . (int) $this->config->get('config_store_id') . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', company_name = '" . $this->db->escape($data['company_name']) . "', company_address = '" . $this->db->escape($data['company_address']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', gender = '" . $this->db->escape($data['gender']) . "', dob = '" . $data['dob'] . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['account']) ? serialize($data['custom_field']['account']) : '') . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', newsletter = '" . (isset($data['newsletter']) ? (int) $data['newsletter'] : 0) . "',parent = '" . (isset($data['parent']) ? (int) $data['parent'] : null) . "',customer_category = '" . (null != $customer_category ? $customer_category : null) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '" . (int) $status . "', approved = '" . (int) $customer_group_info['approval'] . "', source = '" . $source . "', latitude = '" . $login_latitude . "', longitude = '" . $login_longitude . "', sub_customer_order_approval = '" . $sub_customer_order_approval . "', account_manager_id = '" . $customer_accountmanager_id . "', date_added = NOW()");
        }

        $customer_id = $this->db->getLastId();
        $this->savepassword($customer_id, $data['password']);

        if (!empty($data['country_id'])) {
            $this->db->query('INSERT INTO ' . DB_PREFIX . "address SET customer_id = '" . (int) $customer_id . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', dob = '" . $data['dob'] . "', company = '" . $this->db->escape($data['company']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city_id = '" . $this->db->escape($data['cityid']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int) $data['country_id'] . "', zone_id = '" . (int) $data['zone_id'] . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['address']) ? serialize($data['custom_field']['address']) : '') . "'");

            $address_id = $this->db->getLastId();

            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET address_id = '" . (int) $address_id . "' WHERE customer_id = '" . (int) $customer_id . "'");
        }

        if (!empty($data['address'])) {
            //$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int) $customer_id . "', name = '" . $this->db->escape($data['firstname']) . " " . $this->db->escape($data['lastname']). "',  address = '" . $this->db->escape($data['address']) . "', location = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int) $data['country_id'] . "', zone_id = '" . (int) $data['zone_id'] . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['address']) ? serialize($data['custom_field']['address']) : '') . "'");
            $this->db->query('INSERT INTO ' . DB_PREFIX . "address SET customer_id = '" . (int) $customer_id . "', name = '" . $this->db->escape($data['firstname']) . ' ' . $this->db->escape($data['lastname']) . "',  address = '" . $this->db->escape($data['address']) . ' ' . $this->db->escape($data['location']) . "', city_id = '" . $this->db->escape($data['cityid']) . "', building_name = '" . $this->db->escape($data['house_building']) . "'");
            $address_id = $this->db->getLastId();
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET address_id = '" . (int) $address_id . "' WHERE customer_id = '" . (int) $customer_id . "'");
            if (!empty($data['address_lat']) && !empty($data['address_lng'])) {
                $this->db->query('UPDATE ' . DB_PREFIX . "address SET latitude = '" . $data['address_lat'] . "', longitude = '" . $data['address_lng'] . "', city_id = '" . $data['cityid'] . "', WHERE customer_id = '" . (int) $customer_id . "' AND address_id = '" . $address_id . "'");
            }
        }

        if (!empty($data['company_address'])) {
            //$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int) $customer_id . "', name = '" . $this->db->escape($data['firstname']) . " " . $this->db->escape($data['lastname']). "',  address = '" . $this->db->escape($data['address']) . "', location = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int) $data['country_id'] . "', zone_id = '" . (int) $data['zone_id'] . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['address']) ? serialize($data['custom_field']['address']) : '') . "'");
            $this->db->query('INSERT INTO ' . DB_PREFIX . "address SET customer_id = '" . (int) $customer_id . "', name = '" . $this->db->escape($data['firstname']) . ' ' . $this->db->escape($data['lastname']) . "', city_id = '" . $this->db->escape($data['cityid']) . "',  address = '" . $this->db->escape($data['company_address']) . ' ' . $this->db->escape($data['modal_address_locality']) . "', flat_number = '" . $this->db->escape($data['company_address']) . "', building_name = '" . $this->db->escape($data['modal_address_locality']) . "',landmark = '" . $this->db->escape($data['modal_address_locality']) . "', latitude = '" . $this->db->escape($data['latitude']) . "', longitude = '" . $this->db->escape($data['longitude']) . "', address_type = '" . 'office' . "'");
            $address_id = $this->db->getLastId();
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET address_id = '" . (int) $address_id . "' WHERE customer_id = '" . (int) $customer_id . "'");
        }

        //update refree id start

        if (isset($data['referee_user_id'])) {
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET refree_user_id = '" . (int) $data['referee_user_id'] . "' WHERE customer_id = '" . (int) $customer_id . "'");
        }

        //update refree id end
        //Get Email Template

        if ($onlycustomer == false) {//the same method used for mobile registration, 
            //in mobile , customer is saving, before OTP check ,so  $onlycustomer is added
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
                // send message here
            }
            if ($this->emailtemplate->getSmsEnabled('Customer', 'customer_1')) {
                $ret = $this->emailtemplate->sendmessage($data['telephone'], $sms_message);
            }
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
        //$this->db->query('UPDATE ' . DB_PREFIX . "customer SET  customer_group_id = '" . (int) $data['customer_group_id'] . "' , firstname = '" . $this->db->escape($data['firstname']) . "', dob = '" . $data['dob'] . "', gender = '" . $this->db->escape($data['gender']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company_name = '" . $this->db->escape($data['companyname']) . "', company_address = '" . $this->db->escape($data['companyaddress']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "', modified_by = '" . $this->customer->getId() . "', modifier_role = 'customer', date_modified = NOW() WHERE customer_id = '" . (int) $customer_id . "'");
//}
        $this->db->query('UPDATE ' . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) . "', dob = '" . $data['dob'] . "', gender = '" . $this->db->escape($data['gender']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company_name = '" . $this->db->escape($data['companyname']) . "', company_address = '" . $this->db->escape($data['companyaddress']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "', modified_by = '" . $this->customer->getId() . "', modifier_role = 'customer', date_modified = NOW() WHERE customer_id = '" . (int) $customer_id . "'");

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

            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET temporary_password = '" . $password . "',  temppassword = '" . $this->db->escape(1) . "',   salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

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

    public function getCustomerDeliverychargeFlag($customer_id) {
        $query = $this->db->query('SELECT delivery_charge FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . (int) $customer_id . "'");

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
        $log = new Log('error.log');
        $log->write($email);
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

        return $query->row;
    }

    public function getCustomerByPhone($phone) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer WHERE telephone='" . $this->db->escape($phone) . "'");

        return $query->row;
    }

    public function getOTP($customer_id, $otp, $type) {
        if ($customer_id == 540 || $customer_id == "540") {//hardcoded for testing.
            return "1234";
        } else {
            $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "otp WHERE otp='" . $this->db->escape($otp) . "' AND customer_id = '" . $customer_id . "' and type='" . $type . "'");

            return $query->row;
        }
    }

    public function getRegisterOTP($customer_id, $type) {

        $query = $this->db->query('SELECT otp FROM ' . DB_PREFIX . "otp WHERE customer_id = '" . $customer_id . "' and type='" . $type . "'");
        //    echo "<pre>";print_r('SELECT otp FROM ' . DB_PREFIX . "otp WHERE customer_id = '" . $customer_id . "' and type='" . $type . "'");die;
        // echo "<pre>";print_r($query->row);die;

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

    public function getTotalAccountManagersByNameAndId($accountmanagername, $account_manager_id) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . "user WHERE user_id = '" . (int) $account_manager_id . "' AND firstname = '" . $accountmanagername . "' AND user_group_id = '" . (int) $this->config->get('config_account_manager_group_id') . "'");

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

    public function addLoginHistory($data) {
        $this->db->query('INSERT INTO ' . DB_PREFIX . "login_history SET customer_id = '" . $this->db->escape($data['customer_id']) . "', login_latitude = '" . $this->db->escape($data['login_latitude']) . "', login_longitude = '" . $this->db->escape($data['login_longitude']) . "', login_mode = '" . $this->db->escape($data['login_mode']) . "', login_date = '" . $this->db->escape(date('Y-m-d H:i:s')) . "', login_ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'");
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
            'order_link' => $this->url->link('account/login/customer'),
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

    public function customernotifications($user_id, $active_status, $notification_id) {
        $customer_info = $this->getCustomer($user_id);

        if ($customer_info && $notification_id == 'sms') {
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET sms_notification = '" . (int) $active_status . "' WHERE customer_id = '" . (int) $user_id . "'");
        }

        if ($customer_info && $notification_id == 'mobile') {
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET mobile_notification = '" . (int) $active_status . "' WHERE customer_id = '" . (int) $user_id . "'");
        }

        if ($customer_info && $notification_id == 'email') {
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET email_notification = '" . (int) $active_status . "' WHERE customer_id = '" . (int) $user_id . "'");
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
        //$sql = 'SELECT * FROM `' . DB_PREFIX . "product_category_prices` where `store_id` = $store_id";
        $sql = 'SELECT * FROM `' . DB_PREFIX . "product_category_prices` where `store_id` > 0";
//echo $sql;exit;
        $resultsdata = $this->db->query($sql);
//echo '<pre>'; print_r($resultsdata);exit;
        if (count($resultsdata->rows) > 0) {
            foreach ($resultsdata->rows as $result) {
                $cache_price_data[$result['product_store_id'] . '_' . $result['price_category'] . '_' . $result['store_id']] = $result['price'];
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

        if ($customer_id != NULL) {
            $sql2 = 'UPDATE  ' . DB_PREFIX . "customer SET  order_approval_access = '" . (int) $status . "', order_approval_access_role = '" . $role . "' WHERE parent = '" . (int) $parent_id . "' AND customer_id ='" . (int) $customer_id . "'";
            $this->db->query($sql2);
        }
    }

    public function UpdateCustomerOrderApproval($customer_id, $status) {
        $sql = 'UPDATE  ' . DB_PREFIX . "customer SET sub_customer_order_approval = '" . (int) $status . "' WHERE parent = '" . (int) $customer_id . "'";
        $this->db->query($sql);
    }

    public function UpdateCustomerOrderApprovalBySubCustomerid($customer_id, $sub_customer_id, $status) {
        $sql = 'UPDATE  ' . DB_PREFIX . "customer SET sub_customer_order_approval = '" . (int) $status . "' WHERE parent = '" . (int) $customer_id . "' AND customer_id ='" . (int) $sub_customer_id . "'";
        $this->db->query($sql);
    }

    public function GetOrderApprovalAccessByParentId($parent_id, $customer_id) {
        $order_approval_access = $this->db->query('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . "customer c WHERE c.customer_id = '" . (int) $customer_id . "' AND c.parent = '" . (int) $parent_id . "' AND c.order_approval_access = 1 AND (c.order_approval_access_role = 'head_chef' OR c.order_approval_access_role = 'procurement_person')");
        return $order_approval_access->row['total'];
    }

    public function getCustomerDevices($customer_id) {
        $query = $this->db->query('SELECT device_id,date_added FROM ' . DB_PREFIX . "customer_devices WHERE customer_id = '" . (int) $customer_id . "' order by date_added desc");

        return $query->rows;
    }

    public function new_ip_send_otp() {
        $data['status'] = true;
        // $this->load->model('account/customer');
        $this->load->language('api/general');

        if (!empty($this->request->post['email'])) {

            $customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);
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
                $this->model_account_customer->saveOTP($customer_info['customer_id'], $data['otp'], 'newiplogin');
                try {

                    //the Same login verify OTP mail is being used.
                    $log = new Log('error.log');

                    if ($this->emailtemplate->getEmailEnabled('NewDeviceLogin', 'NewDeviceLogin_1')) {

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

                        if ($this->emailtemplate->getSmsEnabled('NewDeviceLogin', 'NewDeviceLogin_1')) {
                            $ret = $this->emailtemplate->sendmessage($this->request->post['phone'], $sms_message);
                        }
                    }
                } catch (Exception $ex) {
                    $log = new Log('error.log');
                    $log->write("new device OTP SMS/Mail Failed");
                } finally {
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

    public function new_ip_verify_otp($verify_otp, $email) {
        $data['status'] = true;
        $this->load->model('account/customer');
        $this->load->language('api/general');

        if (isset($verify_otp) && isset($email)) {
            $customer_info = $this->model_account_customer->getCustomerByEmail($email);
            $log = new Log('error.log');
            $log->write($customer_info);
            $otp_data = $this->model_account_customer->getOTP($customer_info['customer_id'], $verify_otp, 'newiplogin');

            //echo "<pre>";print_r($otp_data);die;
            if (!$otp_data) {
                $data['status'] = false;
                $data['error_warning'] = $this->language->get('error_invalid_otp');
                // user not found
            } else {
                $data['status'] = true;

                $data['error_warning'] = '';
            }
        } else {
            // enter valid number throw error
            $data['status'] = false;

            $data['error_warning'] = $this->language->get('error_invalid_otp');
        }

        return $data;
    }

    public function getCustomerIpAddresses($customer_id, $ip = NULL) {
        if ($ip == NULL) {
            $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int) $customer_id . "'");
        } else {
            $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int) $customer_id . "' AND ip = '" . $ip . "'");
        }

        // echo '<pre>';print_r('SELECT * FROM ' . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int) $customer_id . "' AND ip = '" . $ip . "'");exit;
        return $query->rows;
    }

    public function addregisterIP($customer_id) {

        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int) $customer_id . "' AND ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'");

        if (!$query->num_rows) {
            $this->db->query('INSERT INTO ' . DB_PREFIX . "customer_ip SET customer_id = '" . (int) $customer_id . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', date_added = NOW()");
        }
    }

    public function getAllCustomers() {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer");

        return $query->rows;
    }

    public function getCustomerById($customer_id) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . (int) $customer_id . "'");

        return $query->rows;
    }

    public function getCustomerbyFirebaseDeviceID($device_id) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer WHERE device_id = '" . $device_id . "'");
        //    echo 'SELECT * FROM ' . DB_PREFIX . "customer WHERE device_id = '" .   $device_id . "'" ;exit;
        return $query->row;
    }

    public function getRandomAccountManagerId() {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "user WHERE user_group_id = '" . (int) $this->config->get('config_account_manager_group_id') . "' ORDER BY RAND() LIMIT 1");
        return $query->row;
    }

    public function getAccountManagerId($account_manager_id) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "user WHERE user_group_id = '" . (int) $this->config->get('config_account_manager_group_id') . "' AND user_id = '" . (int) $account_manager_id . "'");
        return $query->row;
    }

    public function editToken($customer_id, $token) {
        $this->db->query('UPDATE ' . DB_PREFIX . "customer SET token = '" . $this->db->escape($token) . "' WHERE customer_id = '" . (int) $customer_id . "'");
    }

    public function editCustomerNew($data) {
        // echo "<pre>";print_r($data);die;
        $this->trigger->fire('pre.customer.edit', $data);

        $customer_id = $this->customer->getId();

        $sql = 'UPDATE ' . DB_PREFIX . "customer SET ";
        if (isset($data['customer_group_id'])) {
            $sql .= ' customer_group_id = ' . (int) $data['customer_group_id'] . ' , ';
        }

        if (isset($data['lastname'])) {
            $sql .= ' lastname = "' . $this->db->escape($data['lastname']) . '" , ';
        }

        if (isset($data['firstname'])) {
            $sql .= ' firstname = "' . $this->db->escape($data['firstname']) . '" , ';
        }

        if (isset($data['telephone'])) {
            //(21) 42353-5255
            $data['telephone'] = preg_replace('/[^0-9]/', '', $data['telephone']);
            $sql .= ' telephone = ' . $data['telephone'] . ' ,';
        }

        if (isset($data['fax'])) {
            $sql .= ' fax = "' . $this->db->escape($data['fax']) . '" , ';
        }

        if (isset($data['gender'])) {
            $sql .= ' gender = "' . $this->db->escape($data['gender']) . '" , ';
        }
        if (isset($data['companyname'])) {
            $sql .= ' company_name = "' . $this->db->escape($data['companyname']) . '" , ';
        }
        if (isset($data['companyaddress'])) {
            $sql .= ' company_address = "' . $this->db->escape($data['companyaddress']) . '" , ';
        }

        if (isset($data['email'])) {
            $sql .= ' email = "' . $this->db->escape($data['email']) . '" , ';
        }
        // if (isset($data['custom_field'])) {
        //     $sql .= ' custom_field = "'. $this->db->escape($data['custom_field'])  ? serialize($data['custom_field']) : '' .' , ';
        // }

        if (isset($data['dob'])) {
            $sql .= ' dob = ' . $data['dob'] . ' , ';
        }
        $customer = 'customer';
        $sql .= ' modified_by = ' . $this->customer->getId() . ' , date_modified = NOW() , modifier_role = "' . $this->db->escape($customer) . '" WHERE customer_id =' . (int) $customer_id;

        //     ';
        //    echo "<pre>";print_r($sql);die;
        $query = $this->db->query($sql);

        $this->trigger->fire('post.customer.edit', $customer_id);
    }

    public function getCustomerContact($contact_id) {
        $contact = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer_contact c WHERE c.contact_id = '" . (int) $contact_id . "'");
        //    echo "<pre>";print_r('SELECT * FROM ' . DB_PREFIX . "customer_contact c WHERE c.contact_id = '" . (int) $contact_id . "'");die;

        return $contact->row;
    }

    public function getCustomerContacts($customer_id) {
        $contacts = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer_contact c WHERE c.customer_id = '" . (int) $customer_id . "'");
        return $contacts->rows;
    }

    public function getTotalContactsByEmail($email) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . "customer_contact WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

        return $query->row['total'];
    }

    public function deletecontact($contact_id) {

        $this->db->query('DELETE FROM `' . DB_PREFIX . "customer_contact` WHERE `contact_id` = '" . (int) $contact_id . "'");
    }

    public function SendInvoiceFlagUpdate($contact_id, $send_invoice) {


        $this->db->query('UPDATE ' . DB_PREFIX . "customer_contact SET send = '" . (int) $send_invoice . "'  WHERE contact_id = '" . (int) $contact_id . "'");
    }

    public function addCustomerContact($data) {
        //    echo '<pre>';print_r($data);exit;
        $flag = 0;
        if ($data['customer_contact_send'] == "on") {
            $flag = 1;
        }
        $data['email'] = $data['input-emailnew'];
        $log = new
                Log('error.log');
        $log->write('contact add');
        $customer_id = $this->customer->getId();
        if (isset($data['telephone'])) {
            $data['telephone'] = preg_replace('/[^0-9]/', '', $data['telephone']);
        }
        $this->db->query('INSERT INTO ' . DB_PREFIX . "customer_contact SET  firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', send = '" . $flag . "', customer_id = '" . (int) $customer_id . "', created_date = NOW(), modified_date = NOW()");

        $contact_id = $this->db->getLastId();

        return $contact_id;
    }

    public function editCustomerContact($data) {
        $data['email'] = $data['input-emailnew'];
        $flag = 0;
        if ($data['customer_contact_send'] == "on") {
            $flag = 1;
        }
        $customer_id = $this->customer->getId();

        if (isset($data['telephone'])) {
            $data['telephone'] = preg_replace('/[^0-9]/', '', $data['telephone']);
        }


        $this->db->query('UPDATE ' . DB_PREFIX . "customer_contact SET   firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "',  email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', send = '" . $flag . "', customer_id = '" . $this->customer->getId() . "',  modified_date = NOW() WHERE contact_id = '" . (int) $data['contactid'] . "'");

        // echo '<pre>';print_r($data);exit;
        // $contact_id = $this->db->getLastId();   return $contact_id;
    }

    public function addCustomerIssue($customer_id, $data) {
        try {
            if (!$data['selectedorderid'])
                $data['selectedorderid'] = 0;
            $sql = 'INSERT INTO ' . DB_PREFIX . "issue SET customer_id = '" . (int) $customer_id . "', order_id = '" . $data['selectedorderid'] . "', issue_details = '" . $this->db->escape($data['issuesummary']) . "', issue_type = '" . $this->db->escape($data['selectissuetype']) . "', created_date = NOW()";

            $this->db->query($sql);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getCustomerLastFeedback($customer_id) {

        $selectquery = $this->db->query('SELECT created_date  FROM ' . DB_PREFIX . "feedback WHERE customer_id = '" . (int) $customer_id . "' Order by feedback_id desc Limit 0, 1");
        $feedback_date = $selectquery->row['created_date'];
        if ($feedback_date) {
            $query = $this->db->query('SELECT count(order_id) as Count FROM ' . DB_PREFIX . "order WHERE customer_id = '" . (int) $customer_id . "' and date(date_added) >date('" . $feedback_date . "')");
            //   echo '<pre>';print_r($query->row['Count']);exit;
            return $query->row['Count'];
        } else {
            return 0;
        }
    }

    //Customer Feedbacks and issues are clubed to single table
    public function addCustomerFeedback($customer_id, $data) {


        try {
            if (!isset($data['selectedorderid']))
                $data['selectedorderid'] = 0;
            if (!isset($data['feedback_type']))
                $data['feedback_type'] = 'I';

            if (isset($data['comments']))
                $data['issuesummary'] = $data['comments'];

            $data['customer_name'] = $this->customer->getFirstName() . ' ' . $this->customer->getLastName();
            $data['email'] = $this->customer->getEmail();
            $data['mobile'] = $this->customer->getTelephone();
            $data['feedback_type'] = ($data['feedback_type'] == "S" ? "Suggestions" : ($data['feedback_type'] == "I" ? "Issue" . " - " . $data['selectissuetype'] : "Happy"));
            $data['description'] = $data['issuesummary'];
            $status = 'Open';

            if (!isset($data['issue_status']))
                $data['issue_status'] = Null;

            if (!isset($data['rating_id']))
                $data['rating_id'] = 1;
            if ($data['rating_id'] > 3) {
                $status = 'Closed';
            }
            // $sql = 'INSERT INTO ' . DB_PREFIX . "feedback SET customer_id = '" . (int) $customer_id . "', order_id = '" . $data['selectedorderid'] . "', issue_details = '" . $this->db->escape($data['issuesummary']) . "', issue_type = '" . $this->db->escape($data['selectissuetype']) . "', created_date = NOW()";
            $sql = 'INSERT INTO ' . DB_PREFIX . "feedback SET customer_id = '" . (int) $customer_id . "',order_id = '" . $data['selectedorderid'] . "', comments = '" . $this->db->escape($data['issuesummary']) . "', rating = '" . $this->db->escape($data['rating_id']) . "', feedback_type ='" . $this->db->escape($data['feedback_type']) . "',issue_type= '" . $this->db->escape($data['selectissuetype']) . "', created_date = '" . $this->db->escape(date('Y-m-d H:i:s')) . "',Status='" . $status . "'";
            //   echo '<pre>';($sql);exit;          

            $this->db->query($sql);

            #region send mail to customer experience
            try {
                $customerexperienceEmails = null;
                if ($data['rating_id'] <= 3) {
                    //get customer experience emails.
                    $customerexperienceEmails = $this->getCustomerExperienceEmails();
                }

                $subject = $this->emailtemplate->getSubject('Feedback', 'feedback_1', $data);
                $message = $this->emailtemplate->getMessage('Feedback', 'feedback_1', $data);

                if ($subject != null && $message != null && $this->emailtemplate->getEmailEnabled('Feedback', 'feedback_1')) {
                    $Senderemails = "";
                    foreach ($customerexperienceEmails as $emailvalue) {
                        if ($Senderemails == "")
                            $Senderemails = $emailvalue['email'];
                        else
                            $Senderemails = $Senderemails . ';' . $emailvalue['email'];
                    }
                    if ($Senderemails != "") {
                        $mail = new Mail($this->config->get('config_mail'));
                        // $mail->setTo($this->config->get('config_email'));
                        $mail->setTo($Senderemails);
                        $mail->setFrom($this->config->get('config_from_email'));
                        // $mail->setSender($this->request->post['name']);
                        $mail->setSender($this->config->get('config_name'));

                        $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
                        $mail->setHtml(html_entity_decode(strip_tags($message), ENT_QUOTES, 'UTF-8'));
                        $mail->send();
                    }
                }
            } catch (exception $ex) {
                //write to log

                $log = new Log('error.log');
                $log->write('error in issue sending mail to customer experience' . $ex);
            }

            #endregion

            return true;
        } catch (Exception $e) {


            return false;
        }
    }

    public function getCustomerExperienceEmails() {
        $customerexperienceEmails = $this->db->query('SELECT email FROM ' . DB_PREFIX . "user u join " . DB_PREFIX . "user_group ug on u.user_group_id =ug.user_group_id WHERE ug.name = 'Customer Experience' and u.status=1");
        return $customerexperienceEmails->rows;
    }

    public function updateCustomerStatus($email_id) {
        $this->db->query('UPDATE ' . DB_PREFIX . "customer SET status = '1' WHERE email = '" . $email_id . "'");
        $query = $this->db->query('SELECT customer_id FROM ' . DB_PREFIX . "customer WHERE email = '" . $email_id . "'");

        return $query->row['customer_id'];
    }

    public function getAllCities() {
        return $this->db->query('select * from `' . DB_PREFIX . 'city` WHERE status = 1 order by sort_order')->rows;
    }

    public function addCustomerFromERP($data, $override = false) {
        //echo '<pre>';print_r($data);exit;
        $log = new Log('error.log');
        // $log->write($data['login_latitude']);
        // $log->write($data['login_longitude']);
        if (isset($data['cityid']) && $data['cityid'] != NULL && $data['cityid'] > 0) {
            $data['cityid'] = $data['cityid'];
        } else {
            $data['cityid'] = 32;
        }

        $customer_accountmanager_id = NULL;
        if (isset($data['accountmanagerid'])) {
            $accountmanagerid = $data['accountmanagerid'];
            $log->write('accountmanagerid');
            $log->write($accountmanagerid);
            $log->write('accountmanagerid');
            $account_manager_details = $this->getAccountManagerId($accountmanagerid);
            if (isset($account_manager_details) && is_array($account_manager_details) && array_key_exists('user_id', $account_manager_details) && array_key_exists('user_group_id', $account_manager_details) && $account_manager_details['user_group_id'] == $this->config->get('config_account_manager_group_id') && $account_manager_details['user_id'] > 0) {
                $customer_accountmanager_id = $account_manager_details['user_id'];
            } else {
                $random_accountmanager = $this->getRandomAccountManagerId();
                $customer_accountmanager_id = $random_accountmanager['user_id'];
            }
        } else {
            $random_accountmanager = $this->getRandomAccountManagerId();
            $log->write('random_accountmanager');
            $log->write($random_accountmanager);
            $log->write('random_accountmanager');
            $customer_accountmanager_id = $random_accountmanager['user_id'];
        }

        $login_latitude = NULL;
        if (isset($data['login_latitude'])) {
            $login_latitude = $data['login_latitude'];
        }

        $sub_customer_order_approval = 1;
        if (isset($data['sub_customer_order_approval'])) {
            $sub_customer_order_approval = $data['sub_customer_order_approval'];
        }

        $login_longitude = NULL;
        if (isset($data['login_longitude'])) {
            $login_longitude = $data['login_longitude'];
        }

        if (!isset($data['dob'])) {
            $log->write('customer in');
            $data['dob'] = null;
        }

        $log->write('customer add');
        $this->trigger->fire('pre.customer.add', $data);
        //below line commented,as the settings are not checking through out the application.
        if (isset($data['customer_group_id'])) {// && is_array($this->config->get('config_customer_group_display')) && in_array($data['customer_group_id'], $this->config->get('config_customer_group_display')))
            $customer_group_id = $data['customer_group_id'];
        } elseif ($this->config->get('config_customer_group_id') > 0) {
            $customer_group_id = $this->config->get('config_customer_group_id');
        } else {
            $customer_group_id = 9;
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

        // echo "<pre>";print_r($customer_group_info);die;
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

        $source = '';
        if (isset($data['source'])) {
            $source = $data['source'];
        }

        $status = 1;
        if (isset($data['status'])) {
            $status = $data['status'];
        }

        $customer_category = null;
        if (null != $data['parent']) {
            $parent_info = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . (int) $data['parent'] . "'");
            $customer_category = $parent_info->row['customer_category'];
        }
        if (!isset($data['dob'])) {
            //$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int) $customer_group_id . "', store_id = '" . (int) $this->config->get('config_store_id') . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', company_name = '" . $this->db->escape($data['company_name']) . "', company_address = '" . $this->db->escape($data['company_address']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', gender = '" . $this->db->escape($data['gender']). "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['account']) ? serialize($data['custom_field']['account']) : '') . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', newsletter = '" . (isset($data['newsletter']) ? (int) $data['newsletter'] : 0) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', approved = '" . (int) !$customer_group_info['approval'] . "', date_added = NOW()");
            $this->db->query('INSERT INTO ' . DB_PREFIX . "customer SET customer_group_id = '" . (int) $customer_group_id . "', store_id = '" . (int) $this->config->get('config_store_id') . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', company_name = '" . $this->db->escape($data['company_name']) . "', company_address = '" . $this->db->escape($data['company_address']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', gender = '" . $this->db->escape($data['gender']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['account']) ? serialize($data['custom_field']['account']) : '') . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', newsletter = '" . (isset($data['newsletter']) ? (int) $data['newsletter'] : 0) . "',parent = '" . (isset($data['parent']) ? (int) $data['parent'] : null) . "',customer_category = '" . (null != $customer_category ? $customer_category : null) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '" . (int) $status . "', approved = '" . (int) $customer_group_info['approval'] . "', source = '" . $source . "', latitude = '" . $login_latitude . "', longitude = '" . $login_longitude . "', sub_customer_order_approval = '" . $sub_customer_order_approval . "', account_manager_id = '" . $customer_accountmanager_id . "',lead_reference_id = '" . $this->db->escape($data['lead_reference_id']) . "', tempPassword = 1, date_added = NOW()");
        } else {
            //$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int) $customer_group_id . "', store_id = '" . (int) $this->config->get('config_store_id') . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', company_name = '" . $this->db->escape($data['company_name']) . "', company_address = '" . $this->db->escape($data['company_address']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', gender = '" . $this->db->escape($data['gender']). "', dob = '" . $data['dob']. "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['account']) ? serialize($data['custom_field']['account']) : '') . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', newsletter = '" . (isset($data['newsletter']) ? (int) $data['newsletter'] : 0) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', approved = '" . (int) !$customer_group_info['approval'] . "', date_added = NOW()");
            $this->db->query('INSERT INTO ' . DB_PREFIX . "customer SET customer_group_id = '" . (int) $customer_group_id . "', store_id = '" . (int) $this->config->get('config_store_id') . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', company_name = '" . $this->db->escape($data['company_name']) . "', company_address = '" . $this->db->escape($data['company_address']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', gender = '" . $this->db->escape($data['gender']) . "', dob = '" . $data['dob'] . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['account']) ? serialize($data['custom_field']['account']) : '') . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', newsletter = '" . (isset($data['newsletter']) ? (int) $data['newsletter'] : 0) . "',parent = '" . (isset($data['parent']) ? (int) $data['parent'] : null) . "',customer_category = '" . (null != $customer_category ? $customer_category : null) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '" . (int) $status . "', approved = '" . (int) $customer_group_info['approval'] . "', source = '" . $source . "', latitude = '" . $login_latitude . "', longitude = '" . $login_longitude . "', sub_customer_order_approval = '" . $sub_customer_order_approval . "', account_manager_id = '" . $customer_accountmanager_id . "',lead_reference_id = '" . $this->db->escape($data['lead_reference_id']) . "', tempPassword = 1, date_added = NOW()");
        }

        $customer_id = $this->db->getLastId();

        if (!empty($data['country_id'])) {
            $this->db->query('INSERT INTO ' . DB_PREFIX . "address SET customer_id = '" . (int) $customer_id . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', dob = '" . $data['dob'] . "', company = '" . $this->db->escape($data['company']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city_id = '" . $this->db->escape($data['cityid']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int) $data['country_id'] . "', zone_id = '" . (int) $data['zone_id'] . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['address']) ? serialize($data['custom_field']['address']) : '') . "'");

            $address_id = $this->db->getLastId();

            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET address_id = '" . (int) $address_id . "' WHERE customer_id = '" . (int) $customer_id . "'");
        }

        if (!empty($data['address'])) {
            //$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int) $customer_id . "', name = '" . $this->db->escape($data['firstname']) . " " . $this->db->escape($data['lastname']). "',  address = '" . $this->db->escape($data['address']) . "', location = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int) $data['country_id'] . "', zone_id = '" . (int) $data['zone_id'] . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['address']) ? serialize($data['custom_field']['address']) : '') . "'");
            $this->db->query('INSERT INTO ' . DB_PREFIX . "address SET customer_id = '" . (int) $customer_id . "', name = '" . $this->db->escape($data['firstname']) . ' ' . $this->db->escape($data['lastname']) . "',  address = '" . $this->db->escape($data['address']) . ' ' . $this->db->escape($data['location']) . "', city_id = '" . $this->db->escape($data['cityid']) . "', building_name = '" . $this->db->escape($data['house_building']) . "'");
            $address_id = $this->db->getLastId();
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET address_id = '" . (int) $address_id . "' WHERE customer_id = '" . (int) $customer_id . "'");
            if (!empty($data['address_lat']) && !empty($data['address_lng'])) {
                $this->db->query('UPDATE ' . DB_PREFIX . "address SET latitude = '" . $data['address_lat'] . "', longitude = '" . $data['address_lng'] . "', city_id = '" . $data['cityid'] . "', WHERE customer_id = '" . (int) $customer_id . "' AND address_id = '" . $address_id . "'");
            }
        }

        if (!empty($data['company_address'])) {
            //$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int) $customer_id . "', name = '" . $this->db->escape($data['firstname']) . " " . $this->db->escape($data['lastname']). "',  address = '" . $this->db->escape($data['address']) . "', location = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int) $data['country_id'] . "', zone_id = '" . (int) $data['zone_id'] . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['address']) ? serialize($data['custom_field']['address']) : '') . "'");
            $this->db->query('INSERT INTO ' . DB_PREFIX . "address SET customer_id = '" . (int) $customer_id . "', name = '" . $this->db->escape($data['firstname']) . ' ' . $this->db->escape($data['lastname']) . "', city_id = '" . $this->db->escape($data['cityid']) . "',  address = '" . $this->db->escape($data['company_address']) . ' ' . $this->db->escape($data['modal_address_locality']) . "', flat_number = '" . $this->db->escape($data['company_address1']) . "', building_name = '" . $this->db->escape($data['modal_address_locality']) . "',landmark = '" . $this->db->escape($data['modal_address_locality']) . "', latitude = '" . $this->db->escape($data['latitude']) . "', longitude = '" . $this->db->escape($data['longitude']) . "', address_type = '" . 'office' . "'");
            $address_id = $this->db->getLastId();
            // $this->db->query('UPDATE ' . DB_PREFIX . "customer SET address_id = '" . (int) $address_id . "' WHERE customer_id = '" . (int) $customer_id . "'");
        }


        $this->trigger->fire('post.customer.add', $customer_id);

        return $customer_id;
    }

    public function getCartProduct($product_store_id) {
        $product_info = $this->db->query('SELECT * FROM ' . DB_PREFIX . "cart WHERE product_store_id = '" . (int) $product_store_id . "'");
        return $product_info->row;
    }

    public function AddToCart($product_store_id, $qty, $option = [], $recurring_id = 0, $store_id = false, $store_product_variation_id = false, $product_type = 'replacable', $product_note = null, $produce_type = null, $product_id) {
        $options = NULL;
        if (isset($option) && is_array($option) && count($option) > 0) {
            $options = implode("-", $option);
        }
        $product_info = $this->getCartProduct($product_store_id);
        if (isset($product_info) && is_array($product_info) && count($product_info) > 0 && $product_info['product_store_id'] == $product_store_id) {
            $this->db->query('UPDATE ' . DB_PREFIX . "cart SET customer_id = '" . (int) $this->customer->getId() . "', product_id = '" . (int) $product_id . "', product_store_id = '" . (int) $product_store_id . "', quantity = '" . $qty . "', options = '" . $options . "', recurring_id = '" . $recurring_id . "', store_id = '" . $store_id . "', store_product_variation_id = '" . $store_product_variation_id . "', product_type = '" . $product_type . "', product_note = '" . $product_note . "', produce_type = '" . $produce_type . "', updated_at = NOW() WHERE product_store_id = '" . $product_store_id . "' AND customer_id ='" . $this->customer->getId() . "'");
        } else {
            $this->db->query('INSERT INTO ' . DB_PREFIX . "cart SET customer_id = '" . (int) $this->customer->getId() . "', product_id = '" . (int) $product_id . "', product_store_id = '" . (int) $product_store_id . "', quantity = '" . $qty . "', options = '" . $options . "', recurring_id = '" . $recurring_id . "', store_id = '" . $store_id . "', store_product_variation_id = '" . $store_product_variation_id . "', product_type = '" . $product_type . "', product_note = '" . $product_note . "', produce_type = '" . $produce_type . "', created_at = NOW()");
            return $this->db->getLastId();
        }
    }

    public function ClearCart() {
        $query = $this->db->query('DELETE FROM ' . DB_PREFIX . "cart WHERE customer_id='" . $this->customer->getId() . "'");
    }

    public function getDBCart() {
        $customer_cart_info = $this->db->query('SELECT * FROM ' . DB_PREFIX . "cart WHERE customer_id = '" . (int) $this->customer->getId() . "'");
        $cart_db_data = $customer_cart_info->rows;
        if ($customer_cart_info->num_rows > 0 && is_array($cart_db_data) && count($cart_db_data) > 0) {
            foreach ($cart_db_data as $cart_db_dat) {
                $this->cart->add($cart_db_dat['product_store_id'], $cart_db_dat['quantity'], [], $cart_db_dat['recurring_id'], $cart_db_dat['store_id'], $cart_db_dat['store_product_variation_id'], $cart_db_dat['product_type'], $cart_db_dat['product_note'], $cart_db_dat['produce_type']);
            }
        }
    }

    public function checkWalletRunningLow($customer_id) {
        //check the customer wallet and send mail, if wallet is low
        $query = $this->db->query('SELECT SUM(amount) AS total FROM `' . DB_PREFIX . "customer_credit` WHERE customer_id = '" . (int) $customer_id . "' GROUP BY customer_id");
        $customer_wallet_amount = 0;
        $customer_order_average = 0;
        if ($query->num_rows) {
            $customer_wallet_amount = $query->row['total'];
        }
        //get average order value of customer
        //SELECT AVG(total) AS total FROM (select total,order_id from `hf7_order` WHERE customer_id = '273'   ORDER BY order_id DESC   LIMIT 0, 3) as t
        $query1 = $this->db->query('SELECT AVG(total) AS total FROM (select total,order_id FROM `' . DB_PREFIX . "order` WHERE total>0 and customer_id = '" . (int) $customer_id . "' ORDER BY order_id DESC LIMIT 0, 5) as t");
        // echo '<pre>';print_r('SELECT AVG(total) AS total FROM (select total,order_id FROM `'.DB_PREFIX."order` WHERE customer_id = '".(int) $customer_id."' ORDER BY order_id DESC LIMIT 0, 3) as t");exit;
        if ($query1->num_rows) {
            $customer_order_average = $query1->row['total'];
        }
        // echo '<pre>';print_r( $customer_order_average);die;
        $log = new Log('error.log');
        $log->write($customer_wallet_amount);
        $log->write('Above wallet, below average order');
        $log->write($customer_order_average);
        if ($customer_wallet_amount > 0 && $customer_wallet_amount <= $customer_order_average) {
            //then send mail to customer
            $data = $this->model_account_customer->getCustomerById($customer_id);
            $data = $data[0];
            //    echo '<pre>'; print_r($data);die;
            $log->write($data['email']);

            try {
                if ($data['email_notification'] == 1 && $this->emailtemplate->getEmailEnabled('Customer', 'customer_19')) {
                    $log->write('low wallet mail sending');
                    $subject = $this->emailtemplate->getSubject('Customer', 'customer_19', $data);
                    $message = $this->emailtemplate->getMessage('Customer', 'customer_19', $data);
                    $sms_message = $this->emailtemplate->getSmsMessage('Customer', 'customer_19', $data);
                    // echo '<pre>'; print_r($subject);die;

                    $mail = new Mail($this->config->get('config_mail'));
                    $mail->setTo($data['email']);
                    $mail->setFrom($this->config->get('config_from_email'));
                    $mail->setSender($this->config->get('config_name'));
                    $mail->setSubject($subject);
                    $mail->setHTML($message);
                    $mail->send();
                }



                if ($data['sms_notification'] == 1 && $this->emailtemplate->getSmsEnabled('Customer', 'customer_19')) {

                    $ret = $this->emailtemplate->sendmessage($data['telephone'], $sms_message);
                }

                // if ($this->emailtemplate->getNotificationEnabled('Customer', 'customer_19')) {
                //     $mobile_notification_template = $this->emailtemplate->getNotificationMessage('Customer', 'customer_19', $data);
                //     //$log->write($mobile_notification_template);
                //     $mobile_notification_title = $this->emailtemplate->getNotificationTitle('Customer', 'customer_19' , $data);
                //     //$log->write($mobile_notification_title);
                //     if (isset($data) && isset($data['device_id']) && $data['mobile_notification'] == 1 && strlen($data['device_id']) > 0) {
                //         $log->write('customer device id set FRONT.MODEL.CHECKOUT.ORDER');
                //         $ret = $this->emailtemplate->sendPushNotification($data['customer_id'], $data['device_id'], '', '', $mobile_notification_title, $mobile_notification_template, 'com.instagolocal.showorder');
                //     } else {
                //         $log->write('customer device id not set FRONT.MODEL.CHECKOUT.ORDER');
                //     }
                // }
            } catch (Exception $e) {
                //   echo '<pre>';print_r( $data);die;
            }
        }
    }

    public function savepassword($customer_id, $password) {
        /* TIME ZONE ISSUE */
        $tz = (new DateTime('now', new DateTimeZone('Africa/Nairobi')))->format('P');
        $this->db->query("SET time_zone='$tz';");
        /* TIME ZONE ISSUE */
        $this->db->query('INSERT INTO ' . DB_PREFIX . "password_resets SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "', customer_id = '" . $customer_id . "', created_at = NOW()");
    }

}
