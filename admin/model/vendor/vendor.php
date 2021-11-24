<?php

class ModelVendorVendor extends Model {

    public function addUser($data) {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "user` SET "
            . "username = '" . $this->db->escape($data['username']) . "', "
            . "user_group_id = '" . (int)$data['user_group_id'] . "', "
            . "salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', "
            . "password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', "
            . "firstname = '" . $this->db->escape($data['firstname']) . "', "
            . "lastname = '" . $this->db->escape($data['lastname']) . "', "
            . "email = '" . $this->db->escape($data['email']) . "', "
            . "order_notification_emails = '" . $this->db->escape($data['order_notification_emails']) . "', "
            . "commision = '" . $this->db->escape($data['commision']) . "', " 
            . "fixed_commision = '" . $this->db->escape($data['fixed_commision']) . "', " 
            . "free_from = '" . $this->db->escape($data['free_from']) . "', " 
            . "free_to = '" . $this->db->escape($data['free_to']) . "', " 
            . "tin_no = '" . $this->db->escape($data['tin_no']) . "', " 
            . "mobile = '" . $this->db->escape($data['mobile']) . "', "
            . "telephone = '" . $this->db->escape($data['telephone']) . "', " 
            . "city_id = '" . $this->db->escape($data['city_id']) . "', " 
            . "address = '" . $this->db->escape($data['address']) . "', "                 
            . "image = '" . $this->db->escape($data['image']) . "', "
            . "orderprefix = '" . $this->db->escape($data['orderprefix']) . "', "
            . "display_name = '" . $this->db->escape($data['display_name']) . "', "
            . "delivery_time = '" . $this->db->escape($data['delivery_time']) . "', "
            . "status = '" . (int)$data['status'] . "', "
            . "date_added = NOW()");

        $vendor_id = $this->db->getLastId();

        $this->addVendorBank($data,$vendor_id);
        $this->add_excel_store_mapping($data,$user_id);
        return $vendor_id;
    }

    public function getStoreCategories($store_id) {
        $sql = 'select * from ' . DB_PREFIX . 'category_to_store cs';
        $sql .= ' left join ' . DB_PREFIX . 'category_description cd on cd.category_id = cs.category_id';
        $sql .= ' where store_id="' . $store_id . '" AND cd.language_id="' . $this->config->get('config_language_id') . '"';
        return $this->db->query($sql)->rows;
    }

    public function editUser($user_id, $data) {
       
        $sql = "update `" . DB_PREFIX . "user` SET "
                . "username = '" . $this->db->escape($data['username']) . "', "
                . "user_group_id = '" . (int)$data['user_group_id'] . "', "
                . "firstname = '" . $this->db->escape($data['firstname']) . "', "
                . "lastname = '" . $this->db->escape($data['lastname']) . "', "
                . "email = '" . $this->db->escape($data['email']) . "', "
                . "order_notification_emails = '" . $this->db->escape($data['order_notification_emails']) . "', "
                . "commision = '" . $this->db->escape($data['commision']) . "', " 
                . "fixed_commision = '" . $this->db->escape($data['fixed_commision']) . "', " 
                . "free_from = '" . $this->db->escape($data['free_from']) . "', " 
                . "free_to = '" . $this->db->escape($data['free_to']) . "', " 
                . "tin_no = '" . $this->db->escape($data['tin_no']) . "', " 
                . "mobile = '" . $this->db->escape($data['mobile']) . "', "
                . "telephone = '" . $this->db->escape($data['telephone']) . "', " 
                . "city_id = '" . $this->db->escape($data['city_id']) . "', " 
                . "address = '" . $this->db->escape($data['address']) . "', "   
                . "latitude = '" . $this->db->escape($data['latitude']) . "', "   
                . "longitude = '" . $this->db->escape($data['longitude']) . "', "                 
                . "image = '" . $this->db->escape($data['image']) . "', "
                . "orderprefix = '" . $this->db->escape($data['orderprefix']) . "', "
                . "display_name = '" . $this->db->escape($data['display_name']) . "', "
                . "delivery_time = '" . $this->db->escape($data['delivery_time']) . "', "
                . "status = '" . (int)$data['status'] . "' "
                . "WHERE user_id='" . $user_id . "'";

        $this->db->query($sql);

        if ($data['password'] && $data['password'] <>'default') {
            $this->db->query("UPDATE `" . DB_PREFIX . "user` SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' WHERE user_id = '" . (int) $user_id . "'");
        }

        $this->addVendorBank($data,$user_id);

        $this->add_excel_store_mapping($data,$user_id);

        


    }

    public function add_excel_store_mapping($data,$vendor_id) {

        if(isset($data['excel_store_mapping'])) {
            foreach ($data['excel_store_mapping'] as $key => $value) {

               $this->db->query("UPDATE " . DB_PREFIX . "excel_store_mapping SET text = '" . $this->db->escape($value['text']) . "', vendor_id = '" . $vendor_id . "', store_id = '" . $value['store_id']  . "' WHERE id = '" . (int)$key . "'");
            }
        }

        if(isset($data['excel_store'])) {
            foreach ($data['excel_store'] as $key => $value) {

               $this->db->query("INSERT INTO " . DB_PREFIX . "excel_store_mapping SET text = '" . $this->db->escape($value['text']) . "', vendor_id = '" . $vendor_id . "', store_id = '" . $value['store_id'] . "'");
            }
        }
    }

    


    public function editPassword($user_id, $password) {
        $this->db->query("UPDATE `" . DB_PREFIX . "user` SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "', code = '' WHERE user_id = '" . (int) $user_id . "'");
    }

    public function editCode($email, $code) {
        $this->db->query("UPDATE `" . DB_PREFIX . "user` SET code = '" . $this->db->escape($code) . "' WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
    }

    public function deleteUser($user_id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "user` WHERE user_id = '" . (int) $user_id . "'");
    }

    public function getUser($user_id) {
        $query = $this->db->query("SELECT *, (SELECT ug.name FROM `" . DB_PREFIX . "user_group` ug WHERE ug.user_group_id = u.user_group_id) AS user_group FROM `" . DB_PREFIX . "user` u WHERE u.user_id = '" . (int) $user_id . "'");

        return $query->row;
    }

    public function getSubAccountDetails($user_id) {
        $query = $this->db->query("SELECT * from `" . DB_PREFIX . "iugu_sub_account`  WHERE vendor_id =" . (int) $user_id);

        return $query->row;
    }
    
    public function getVendorBank($user_id) {
        $query = $this->db->query("SELECT * from `" . DB_PREFIX . "vendor_bank_account`  WHERE vendor_id =" . (int) $user_id);

        return $query->row;
    }
    
    public function getVendorByStoreId($store_id) {
        $query = $this->db->query("SELECT * from `" . DB_PREFIX . "store`  WHERE store_id =" . (int) $store_id);

        return $query->row;
    }

    public function addVendorBank($data,$vendor_id) {

        $this->db->query("DELETE FROM " . DB_PREFIX . "vendor_bank_account WHERE vendor_id = '" . (int) $vendor_id . "'");

        $this->db->query("INSERT INTO " . DB_PREFIX . "vendor_bank_account SET vendor_id = '" . (int) $vendor_id . "', bank_account_number = '" . $data['bank_account_number'] . "', bank_account_name = '" . $this->db->escape($data['bank_account_name']) . "', bank_name = '" . $this->db->escape($data['bank_name']) . "', bank_branch_name = '" . $this->db->escape($data['bank_branch_name']) . "', bank_account_type = '" . $this->db->escape($data['bank_account_type']) ."'");
    }


    public function getUserByUsername($username) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user` WHERE username = '" . $this->db->escape($username) . "'");

        return $query->row;
    }

    public function getUserByCode($code) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user` WHERE code = '" . $this->db->escape($code) . "' AND code != ''");

        return $query->row;
    }

    public function getUsers($data = array()) {
        $sql  = "SELECT u.*, c.name as city FROM `" . DB_PREFIX . "user` u ";
        $sql .= "LEFT JOIN `".DB_PREFIX."city` c on c.city_id = u.city_id ";
        
        //filter vendor groups 
        $sql .= ' WHERE u.user_group_id IN (' . $this->config->get('config_vendor_group_ids') . ')';

        if (isset($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $this->db->escape($data['filter_city']) . "%'";
        }

        if (isset($data['filter_user_name']) && !is_null($data['filter_user_name'])) {
            $sql .= " AND u.username LIKE '" . $this->db->escape($data['filter_user_name']) . "%'";
        }

        if (isset($data['filter_user_group']) && !is_null($data['filter_user_group'])) {
            $sql .= " AND u.user_group_id LIKE ( SELECT ug.user_group_id FROM `" . DB_PREFIX . "user_group` ug WHERE ug.name LIKE '" . $this->db->escape($data['filter_user_group']) . "%') ";
        }

        if (isset($data['filter_first_name']) && !is_null($data['filter_first_name'])) {
            $sql .= " AND u.firstname LIKE '" . $this->db->escape($data['filter_first_name']) . "%'";
        }

        if (isset($data['filter_last_name']) && !is_null($data['filter_last_name'])) {
            $sql .= " AND u.lastname LIKE '" . $this->db->escape($data['filter_last_name']) . "%'";
        }

        if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
            $sql .= " AND u.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND u.status LIKE '" . $this->db->escape($data['filter_status']) . "%'";
        }

        $sort_data = array(
            'u.username',
            'u.status',
            'u.date_added',
            'c.name'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY u.username";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalUsers() {

        $sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user`";

        //filter vendor groups 
        $sql .= ' WHERE user_group_id IN (' . $this->config->get('config_vendor_group_ids') . ')';

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalUsersFilter($data) {
        
        $sql  = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user` u ";
        $sql .= "LEFT JOIN `".DB_PREFIX."city` c on c.city_id = u.city_id ";
        
        //filter vendor groups 
        $sql .= ' WHERE u.user_group_id IN (' . $this->config->get('config_vendor_group_ids') . ')';

        if (isset($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $this->db->escape($data['filter_city']) . "%'";
        }
        
        if (isset($data['filter_user_name']) && !is_null($data['filter_user_name'])) {
            $sql .= " AND u.username LIKE '" . $this->db->escape($data['filter_user_name']) . "%'";
        }

        if (isset($data['filter_user_group']) && !is_null($data['filter_user_group'])) {
            $sql .= " AND u.user_group_id LIKE ( SELECT ug.user_group_id FROM `" . DB_PREFIX . "user_group` ug WHERE ug.name LIKE '" . $this->db->escape($data['filter_user_group']) . "%') ";
        }

        if (isset($data['filter_first_name']) && !is_null($data['filter_first_name'])) {
            $sql .= " AND u.firstname LIKE '" . $this->db->escape($data['filter_first_name']) . "%'";
        }

        if (isset($data['filter_last_name']) && !is_null($data['filter_last_name'])) {
            $sql .= " AND u.lastname LIKE '" . $this->db->escape($data['filter_last_name']) . "%'";
        }

        if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
            $sql .= " AND u.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND u.status LIKE '" . $this->db->escape($data['filter_status']) . "%'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalUsersByGroupId($user_group_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user` WHERE user_group_id = '" . (int) $user_group_id . "'");

        return $query->row['total'];
    }

    public function getTotalUsersByEmail($email) {

        $sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user` WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'";

        //filter vendor groups 
        $sql .= ' AND user_group_id IN (' . $this->config->get('config_vendor_group_ids') . ')';

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getVendorTotalOrders($data) {
        $sql = 'select count(*) as total from ' . DB_PREFIX . 'order  LEFT JOIN '.DB_PREFIX.'store  on '.DB_PREFIX.'store.store_id = '.DB_PREFIX.'order.store_id where vendor_id = "' . $data['vendor_id'] . '"  AND order_status_id != ""';
        //echo $sql;die;
        return $this->db->query($sql)->row['total'];
    }

    public function getVendorOrders($data = array()) {

        $sql = "SELECT vo.store_id, vo.commsion_received, vo.order_id,CONCAT(vo.firstname, ' ', vo.lastname) AS customer, vo.order_status_id, vo.total, vo.currency_code, vo.currency_value, vo.date_added, vo.commission, vo.commsion_received   FROM `" . DB_PREFIX . "order` vo LEFT JOIN ".DB_PREFIX."store st on st.store_id = vo.store_id";

        //echo $sql;die;
        if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
            $sql .= " WHERE vo.order_status_id = '" . $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " WHERE vo.order_status_id != ''";
        }

        $sql .= ' AND st.vendor_id="' . $data['vendor_id'] . '"';

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '" . (int) $data['filter_order_id'] . "'";
        }

        

        if (isset($data['filter_payment_status'])) {
            $sql .= " AND vo.commsion_received = '" . (int) $data['filter_payment_status'] . "'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(vo.firstname, ' ', vo.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(vo.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(vo.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if (!empty($data['filter_total'])) {
            $sql .= " AND vo.total = '" . (float) $data['filter_total'] . "'";
        }

        $sort_data = array(
            'vo.order_id',
            'customer',
            'status',
            'vo.date_added',
            'vo.date_modified',
            'vo.total'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY vo.order_id";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
        }

        $query = $this->db->query($sql);
        

        return $query->rows;
    }
    
    public function addCredit($vendor_id, $description = '', $amount = '', $order_id = 0,$iugu_transfer = 0,$data = '') {
        $user_info = $this->getUser($vendor_id);

        $iuguStatus = true;

        if($iugu_transfer == 'true' && $amount < 0) {
            $details = $this->getSubAccountDetails($vendor_id);

            if(count($details) > 0) {
                $data['amount_cents'] = $amount * -100;

                $data['receiver_id'] = $details['account_id'];
                $data['description'] = $description;

                if(!$this->transferToSubAccount($data)) {
                    $iuguStatus = false;
                }
            }
        }

        $invoice = 0;
        if(isset($data['has-invoice'])) {
            //$invoice = $this->url->link('sale/order/EditInvoice', 'token=' . $this->session->data['token'] . '&order_id=' . $order_id, 'SSL');
            $invoice = $data['has-invoice'];
        }

        if ($user_info && $iuguStatus) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "vendor_wallet SET vendor_id = '" . (int) $vendor_id . "', order_id = '" . (int) $order_id . "', invoice = '" . $invoice . "', description = '" . $this->db->escape($description) . "', amount = '" . (float) $amount . "', date_added = NOW()");

            $this->load->language('mail/vendor');

            $vendorData = $this->getVendorDetails($vendor_id);

            //echo "<pre>";print_r($vendorData);die;
          
            if(isset($vendorData['email'])) {
                // 6 merchant mail
                $vendorData['amount'] = abs($amount);

                $vendorData['transaction_type'] = 'credited';

                if($amount <= 0 ) {

                    
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
            if ( $this->emailtemplate->getNotificationEnabled('Contact','contact_6')) {
                    
                $vendorData['transaction_type'] = 'credited';

                $vendorData['amount'] = abs($amount);

                if($amount <= 0 ) {
                    $vendorData['transaction_type'] = 'debited';
                }

                $vendorData['amount'] = $this->currency->format($vendorData['amount']);
                
                $log->write('status enabled of wallet mobi noti');
                $mobile_notification_template = $this->emailtemplate->getNotificationMessage( 'Contact', 'contact_6', $vendorData );

                $log->write($mobile_notification_template);

                $mobile_notification_title = $this->emailtemplate->getNotificationTitle( 'Contact', 'contact_6', $vendorData );

                $log->write($mobile_notification_title);

                $log->write($vendorData);

                if(isset($vendorData['device_id']) && strlen($vendorData['device_id']) > 0 ) {

                    $log->write('VENDOR MOBILE PUSH NOTIFICATION device id set ADMIN.MODEL.VENDOR.VENDOR');

                    $this->load->model('sale/order');

                    

                    $notification_id = $this->model_sale_order->saveVendorNotification($vendor_id,$vendorData['device_id'],$order_id,$mobile_notification_template,$mobile_notification_title);

                    $sen['notification_id'] = $notification_id;

                    $ret =  $this->emailtemplate->sendVendorPushNotification($vendor_id,$vendorData['device_id'],$order_id,'1',$mobile_notification_template,$mobile_notification_title,$sen);

                } else {
                    $log->write('VENDOR MOBILE PUSH NOTIFICATION device id not set ADMIN.MODEL.VENDOR.VENDOR');
                }
            }
        }

        return true;
    }

    public function deleteCredit($order_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "vendor_wallet WHERE order_id = '" . (int) $order_id . "'");
    }

    public function getCredits($vendor_id, $start = 0, $limit = 10) {
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 10;
        }

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "vendor_wallet WHERE vendor_id = '" . (int) $vendor_id . "' ORDER BY date_added DESC LIMIT " . (int) $start . "," . (int) $limit);

        return $query->rows;
    }

    public function getTotalCredits($vendor_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total  FROM " . DB_PREFIX . "vendor_wallet WHERE vendor_id = '" . (int) $vendor_id . "'");

        return $query->row['total'];
    }

    public function getCreditTotal($vendor_id) {
        $query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "vendor_wallet WHERE vendor_id = '" . (int) $vendor_id . "'");

        return $query->row['total'];
    }

    public function getTotalCreditsByOrderId($order_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "vendor_wallet WHERE order_id = '" . (int) $order_id . "'");

        return $query->row['total'];
    }

    public function getVendorDetails($vendor_id) {

        return $this->db->query('select * from `'.DB_PREFIX.'user` WHERE user_id="'.$vendor_id.'"')->row; 
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

        curl_setopt($ch, CURLOPT_URL, "https://api.iugu.com/v1/transfers");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_USERPWD, 'ea9924eb230ea73962f5269367bdea1c');

        $headers = array();
        $headers[] = "Content-Type: application/x-www-form-urlencoded";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);

        //echo "<pre>";print_r($result);die;
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);
        //save data to table iugu_sub_account
        $result = json_decode($result,1);
        
        //echo "<pre>";print_r($result);die;
        if(isset($result['errors'])) {
            return false;
        }

        return true;
    }

}
