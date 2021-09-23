<?php

class ModelAccountActivity extends Model {

    public function addActivity($key, $data) {
        if (isset($data['customer_id'])) {
            $customer_id = $data['customer_id'];
        } else {
            $customer_id = 0;
        }

        $this->db->query('INSERT INTO `' . DB_PREFIX . "customer_activity` SET `customer_id` = '" . (int) $customer_id . "', `key` = '" . $this->db->escape($key) . "', `data` = '" . $this->db->escape(serialize($data)) . "', `ip` = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', `date_added` = NOW()");
    }

    public function addCustomerReward($customer_id, $points, $description) {
        $this->db->query('INSERT INTO ' . DB_PREFIX . "customer_reward SET customer_id = '" . (int) $customer_id . "', order_id = '0', description = '" . $description . "', points = '" . (float) $points . "', date_added = NOW()");
    }

    public function getStoreData($store_id) {
        return $this->db->query('select name,store_id,delivery_time_diff from `' . DB_PREFIX . 'store` WHERE store_id = "' . $store_id . '"')->row;
    }

    public function getReferredSignup($customer_id) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . "customer WHERE refree_user_id = '" . (int) $customer_id . "'");

        return $query->row['total'];
    }

    public function getReferralAmount() {
        $response['referrer'] = false;
        $response['referred'] = false;

        $config_reward_enabled = $this->config->get('config_reward_enabled');

        $config_credit_enabled = $this->config->get('config_credit_enabled');

        $config_refer_type = $this->config->get('config_refer_type');

        $config_refered_points = $this->config->get('config_refered_points');
        $config_referee_points = $this->config->get('config_referee_points');

        if ('reward' == $config_refer_type) {
            if ($config_reward_enabled && $config_refered_points) {
                $response['referrer'] = $config_referee_points . ' Reward points';
                $response['referred'] = $config_refered_points . ' Reward points';
            }
        } elseif ('credit' == $config_refer_type) {
            if ($config_credit_enabled && $config_refered_points) {
                $response['referrer'] = $this->currency->format($config_referee_points);
                $response['referred'] = $this->currency->format($config_refered_points);
            }
        }

        return $response;
    }

    public function getReferredBonus($customer_id, $description) {
        $query = $this->db->query('SELECT SUM(amount) AS total FROM ' . DB_PREFIX . "customer_credit WHERE customer_id = '" . (int) $customer_id . "' and description = '" . $description . "'");

        $creditsTotal = $query->row['total'];

        $query = $this->db->query('SELECT SUM(points) AS total FROM ' . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int) $customer_id . "' and description = '" . $description . "'");

        $rewardTotal = $this->config->get('config_reward_value') * $query->row['total'];

        return $creditsTotal + $rewardTotal;
    }

    public function getUser($user_id) {
        $query = $this->db->query('SELECT *, (SELECT ug.name FROM `' . DB_PREFIX . 'user_group` ug WHERE ug.user_group_id = u.user_group_id) AS user_group FROM `' . DB_PREFIX . "user` u WHERE u.user_id = '" . (int) $user_id . "'");

        return $query->row;
    }

    public function getSubAccountDetails($user_id) {
        $query = $this->db->query('SELECT * from `' . DB_PREFIX . 'iugu_sub_account`  WHERE vendor_id =' . (int) $user_id);

        return $query->row;
    }

    public function transferToSubAccount($data) {
        //live key 67ff0666d626234797f4a6f65095df8c
        // market place live key ea9924eb230ea73962f5269367bdea1c

        $data['amount_cents'] = $data['amount_cents'];
        $data['receiver_id'] = $data['receiver_id'];
        $data['custom_variables'] = [];

        $datas['name'] = $data['description'];
        $datas['value'] = $data['amount_cents'];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.iugu.com/v1/transfers');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_USERPWD, 'ea9924eb230ea73962f5269367bdea1c');

        $headers = [];
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);

        //echo "<pre>";print_r($result);die;
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        //save data to table iugu_sub_account
        $result = json_decode($result, 1);

        //echo "<pre>";print_r($result);die;
        if (isset($result['errors'])) {
            return false;
        }

        return true;
    }

    public function getVendorDetails($vendor_id) {
        return $this->db->query('select * from `' . DB_PREFIX . 'user` WHERE user_id="' . $vendor_id . '"')->row;
    }

    public function addCreditVendorCopy($vendor_id, $description = '', $amount = '', $order_id = 0, $iugu_transfer = 0, $data = '') {
        $user_info = $this->getUser($vendor_id);

        $iuguStatus = true;

        if ('true' == $iugu_transfer && $amount < 0) {
            $details = $this->getSubAccountDetails($vendor_id);

            if (count($details) > 0) {
                $data['amount_cents'] = $amount * -100;

                $data['receiver_id'] = $details['account_id'];
                $data['description'] = $description;

                if (!$this->transferToSubAccount($data)) {
                    $iuguStatus = false;
                }
            }
        }

        $invoice = 0;
        if (isset($data['has-invoice'])) {
            //$invoice = $this->url->link('sale/order/EditInvoice', 'token=' . $this->session->data['token'] . '&order_id=' . $order_id, 'SSL');
            $invoice = $data['has-invoice'];
        }

        if ($user_info && $iuguStatus) {
            $this->db->query('INSERT INTO ' . DB_PREFIX . "vendor_wallet SET vendor_id = '" . (int) $vendor_id . "', order_id = '" . (int) $order_id . "', invoice = '" . $invoice . "', description = '" . $this->db->escape($description) . "', amount = '" . (float) $amount . "', date_added = NOW()");

            $this->load->language('mail/vendor');

            $vendorData = $this->getVendorDetails($vendor_id);

            //echo "<pre>";print_r($vendorData);die;

            if (isset($vendorData['email'])) {
                // 6 merchant mail
                $vendorData['amount'] = $amount;

                $vendorData['transaction_type'] = 'credited';

                if ($vendorData['amount'] <= 0) {
                    $vendorData['transaction_type'] = 'debited';
                }

                $vendorData['amount'] = $this->currency->format($vendorData['amount']);

                $subject = $this->emailtemplate->getSubject('Contact', 'contact_6', $vendorData);
                $message = $this->emailtemplate->getMessage('Contact', 'contact_6', $vendorData);
                //mishramanjari15@gmail.com
                $mail = new mail($this->config->get('config_mail'));
                $mail->setTo($vendorData['email']);
                $mail->setFrom($this->config->get('config_from_email'));
                //$mail->setReplyTo($vendorData['email']);
                $mail->setSender($this->config->get('config_name'));
                $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
                $mail->setHtml(html_entity_decode(strip_tags($message), ENT_QUOTES, 'UTF-8'));
                $mail->send();
            }

            $log = new Log('error.log');
            if ($this->emailtemplate->getNotificationEnabled('Contact', 'contact_6')) {
                $vendorData['transaction_type'] = 'credited';

                if ($vendorData['amount'] <= 0) {
                    $vendorData['transaction_type'] = 'debited';
                }

                $vendorData['amount'] = $this->currency->format($vendorData['amount']);

                $log->write('status enabled of wallet mobi noti');
                $mobile_notification_template = $this->emailtemplate->getNotificationMessage('Contact', 'contact_6', $vendorData);

                $log->write($mobile_notification_template);

                $mobile_notification_title = $this->emailtemplate->getNotificationTitle('Contact', 'contact_6', $vendorData);

                $log->write($mobile_notification_title);

                $log->write($vendorData);

                if (isset($vendorData['device_id']) && strlen($vendorData['device_id']) > 0) {
                    $log->write('VENDOR MOBILE PUSH NOTIFICATION device id set FRONT.MODEL.ACCOUNT.ACTIVITY');

                    $this->load->model('account/order');

                    $notification_id = $this->model_account_order->saveVendorNotification($vendor_id, $vendorData['device_id'], $order_id, $mobile_notification_template, $mobile_notification_title);

                    $sen['notification_id'] = $notification_id;

                    $ret = $this->emailtemplate->sendVendorPushNotification($vendor_id, $vendorData['device_id'], $order_id, '1', $mobile_notification_template, $mobile_notification_title, $sen);
                } else {
                    $log->write('VENDOR MOBILE PUSH NOTIFICATION device id not set FRONT.MODEL.ACCOUNT.ACTIVITY');
                }
            }
        }

        return true;
    }

    public function addCreditAdminCopy($customer_id, $description = '', $amount = '', $order_id = 0) {
        $customer_info = $this->getCustomer($customer_id);

        $log = new Log('error.log');

        if ($customer_info && 1!=1) {//customer credit NA 
        
            $this->db->query('INSERT INTO ' . DB_PREFIX . "customer_credit SET customer_id = '" . (int) $customer_id . "', order_id = '" . (int) $order_id . "', description = '" . $this->db->escape($description) . "', amount = '" . (float) $amount . "', date_added = NOW()");

            $this->load->language('mail/customer');

            $this->load->model('tool/image');

            $store_info = $this->model_tool_image->getStore($customer_info['store_id']);

            //echo "<pre>";print_r($store_info);die;
            if ($store_info) {
                $store_name = $store_info['name'];
            } else {
                $store_name = $this->config->get('config_name');
            }

            $data = $customer_info;
            $data['amount'] = $amount;

            $data['transfer_type'] = 'debited in';
            if ($amount >= 0) {
                $data['transfer_type'] = 'credited in';
            }
            if ($amount < 0) {
                $data['amount'] = -$amount;
            }

            $data['amount'] = $this->currency->format($data['amount']);

            $subject = $this->emailtemplate->getSubject('Customer', 'customer_6', $data);
            $message = $this->emailtemplate->getMessage('Customer', 'customer_6', $data);

            $mail = new Mail($this->config->get('config_mail'));
            $mail->setTo($customer_info['email']);
            $mail->setFrom($this->config->get('config_from_email'));
            $mail->setSender($store_name);
            $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
            $mail->setHTML(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
            $mail->send();

            if ($this->emailtemplate->getNotificationEnabled('Customer', 'customer_6')) {
                $log->write('status enabled of mobi noti');
                $mobile_notification_template = $this->emailtemplate->getNotificationMessage('Customer', 'customer_6', $data);
                $mobile_notification_title = $this->emailtemplate->getNotificationTitle('Customer', 'customer_6', $data);

                if (isset($customer_info['device_id']) && strlen($customer_info['device_id']) > 0) {
                    $log->write('device id set');

                    //$notification_id = $this->saveVendorNotification($temporaryVendorInfo['vendor_id'],$customer_info['device_id'],$order_id,$mobile_notification_template,$mobile_notification_title);

                    $sen['wallet_id'] = '';

                    //->setData(array('order_id' => $order_id,'store_id' => $store_id,'notification_id' => $args['notification_id']));
                    $ret = $this->emailtemplate->sendDynamicPushNotification($customer_info['customer_id'], $customer_info['device_id'], $mobile_notification_template, $mobile_notification_title, $sen);

                    $log->write('device id set end');
                } else {
                    $log->write('device id not set');
                }
            }
        }
    }

    public function addCredit($customer_id, $description = '', $amount = '', $order_id = 0) {
        $customer_info = $this->getCustomer($customer_id);

        $this->load->language('mail/customer');

        if ($customer_info && 1!=1) {//customer credit NA
            $this->db->query('INSERT INTO ' . DB_PREFIX . "customer_credit SET customer_id = '" . (int) $customer_id . "', order_id = '" . (int) $order_id . "', description = '" . $this->db->escape($description) . "', amount = '" . (float) $amount . "', date_added = NOW()");

            $this->load->language('mail/customer');

            $this->load->model('setting/store');

            $store_name = $this->config->get('config_name');

            $data = $customer_info;
            $data['amount'] = $amount;

            $data['transfer_type'] = 'debited in';
            if ($amount >= 0) {
                $data['transfer_type'] = 'credited in';
            }
            if ($amount < 0) {
                $data['amount'] = -$amount;
            }

            $data['amount'] = $this->currency->format($data['amount']);

            $subject = $this->emailtemplate->getSubject('Customer', 'customer_6', $data);
            $message = $this->emailtemplate->getMessage('Customer', 'customer_6', $data);

            $log = new Log('error.log');

            $mail = new Mail($this->config->get('config_mail'));
            $mail->setTo($customer_info['email']);
            $mail->setFrom($this->config->get('config_from_email'));
            $mail->setSender($store_name);
            $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
            $mail->setHTML(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
            $mail->send();

            if ($this->emailtemplate->getNotificationEnabled('Customer', 'customer_6')) {
                $log->write('status enabled of mobi noti');
                $mobile_notification_template = $this->emailtemplate->getNotificationMessage('Customer', 'customer_6', $data);
                $mobile_notification_title = $this->emailtemplate->getNotificationTitle('Customer', 'customer_6', $data);

                if (isset($customer_info['device_id']) && strlen($customer_info['device_id']) > 0) {
                    $log->write('device id set');

                    //$notification_id = $this->saveVendorNotification($temporaryVendorInfo['vendor_id'],$customer_info['device_id'],$order_id,$mobile_notification_template,$mobile_notification_title);

                    $sen['wallet_id'] = '';

                    //->setData(array('order_id' => $order_id,'store_id' => $store_id,'notification_id' => $args['notification_id']));
                    $ret = $this->emailtemplate->sendDynamicPushNotification($customer_info['customer_id'], $customer_info['device_id'], $mobile_notification_template, $mobile_notification_title, $sen);

                    $log->write('device id set end');
                } else {
                    $log->write('device id not set');
                }
            }
        }
    }

    public function getCustomer($customer_id) {
        $query = $this->db->query('SELECT DISTINCT * FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . (int) $customer_id . "'");

        return $query->row;
    }

    public function getCreditTotal($customer_id) {
        $query = $this->db->query('SELECT SUM(amount) AS total FROM ' . DB_PREFIX . "customer_credit WHERE customer_id = '" . (int) $customer_id . "'");

        return $query->row['total'];
    }

    public function addFarmerActivity($key, $data) {
        $dat = $data;
        if (isset($data['farmer_id'])) {
            $farmer_id = $data['farmer_id'];
        } else {
            $farmer_id = 0;
        }
        unset($dat['user_group_id']);
        $this->db->query('INSERT INTO `' . DB_PREFIX . "farmer_activity` SET `farmer_id` = '" . (int) $farmer_id . "', `user_group_id` = '" . (int) $data['user_group_id'] . "', `key` = '" . $this->db->escape($key) . "', `data` = '" . $this->db->escape(serialize($dat)) . "', `ip` = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', `date_added` = NOW()");
    }

}
