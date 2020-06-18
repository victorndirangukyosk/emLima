<?php

class ModelSaleCustomer extends Model {

    public function addCustomer($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int) $data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', gender = '" . $this->db->escape($data['sex']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "',company_name = '" . $this->db->escape($data['company_name']) . "',company_address = '" . $this->db->escape($data['company_address']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "', newsletter = '" . (int) $data['newsletter'] . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', status = '" . (int) $data['status'] . "', approved = '" . (int) $data['approved'] . "', safe = '" . (int) $data['safe'] . "', date_added = NOW()");

        $customer_id = $this->db->getLastId();

        if (isset($data['address'])) {
            foreach ($data['address'] as $address) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int) $customer_id . "', name = '" . $this->db->escape($address['name']) . "', address = '" . $this->db->escape($address['address']) . "', city_id = '" . $this->db->escape($address['city_id']) . "', contact_no = '" . $this->db->escape($address['contct_no']) . "'");

                if (isset($address['default'])) {
                    $address_id = $this->db->getLastId();

                    $this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int) $address_id . "' WHERE customer_id = '" . (int) $customer_id . "'");
                }
            }
        }

        if (!empty($data['send_email'])) {
            $this->sendCustomerRegisterMail($data);
        }

        return $customer_id;
    }

    public function editCustomer($customer_id, $data) {
        if (!isset($data['custom_field'])) {
            $data['custom_field'] = array();
        }

        $this->db->query("UPDATE " . DB_PREFIX . "customer SET customer_group_id = '" . (int) $data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', dob = '" . $data['dob'] . "', gender = '" . $this->db->escape($data['sex']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "',company_name = '" . $this->db->escape($data['company_name']) . "',company_address = '" . $this->db->escape($data['company_address']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "', newsletter = '" . (int) $data['newsletter'] . "', status = '" . (int) $data['status'] . "', approved = '" . (int) $data['approved'] . "', safe = '" . (int) $data['safe'] . "' WHERE customer_id = '" . (int) $customer_id . "'");

        if ($data['password'] && $data['password']<>'default') {
            $this->db->query("UPDATE " . DB_PREFIX . "customer SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' WHERE customer_id = '" . (int) $customer_id . "'");
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int) $customer_id . "'");

        if (isset($data['address'])) {
            foreach ($data['address'] as $address) {
                /*$this->db->query("INSERT INTO " . DB_PREFIX . "address SET address_id = '" . (int) $address['address_id'] . "', customer_id = '" . (int) $customer_id . "', name = '" . $this->db->escape($address['name']) . "', contact_no = '" . $this->db->escape($address['contact_no']) . "', address = '" . $this->db->escape($address['address']) . "', city_id = '" . $this->db->escape($address['city_id']) . "'");*/

                $address['address'] = $address['flat_number'].", ".$address['building_name'].", ".$address['landmark'];

                $this->db->query("INSERT INTO " . DB_PREFIX . "address SET  address_id = '" . (int) $address['address_id'] ."', customer_id = '" . (int) $customer_id . "', name = '" . $this->db->escape($address['name']) . "', contact_no = '" . $this->db->escape($address['contact_no']) . "', city_id = '" . $this->db->escape($address['city_id']) . "', address_type = '" . $this->db->escape($address['address_type']) ."', flat_number = '" . $this->db->escape($address['flat_number']) ."', building_name = '" . $this->db->escape($address['building_name']) ."', landmark = '" . $this->db->escape($address['landmark']) ."', zipcode = '" . $this->db->escape($address['zipcode']). "', address = '" . $this->db->escape($address['address']) . "'");

                if (isset($address['default'])) {
                    $address_id = $this->db->getLastId();

                    $this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int) $address_id . "' WHERE customer_id = '" . (int) $customer_id . "'");
                }
            }
        }
    }

    public function editToken($customer_id, $token) {
        $this->db->query("UPDATE " . DB_PREFIX . "customer SET token = '" . $this->db->escape($token) . "' WHERE customer_id = '" . (int) $customer_id . "'");
    }

    public function deleteCustomer($customer_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int) $customer_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int) $customer_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "customer_credit WHERE customer_id = '" . (int) $customer_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int) $customer_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int) $customer_id . "'");
    }

    public function getCustomer($customer_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int) $customer_id . "'");

        return $query->row;
    }

    public function getCustomerByEmail($email) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

        return $query->row;
    }

    public function getCustomers($data = array()) {
        $sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, cgd.name AS customer_group FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

        $implode = array();

        

        if (!empty($data['filter_name'])) {

            if($this->user->isVendor()) {

                $implode[] = "c.firstname LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
            } else {

                $implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
            }
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (!empty($data['filter_telephone'])) {
            $implode[] = "c.telephone LIKE '" . $this->db->escape($data['filter_telephone']) . "%'";
        }
        

        if (isset($data['filter_newsletter']) && !is_null($data['filter_newsletter'])) {
            $implode[] = "c.newsletter = '" . (int) $data['filter_newsletter'] . "'";
        }

        if (!empty($data['filter_customer_group_id'])) {
            $implode[] = "c.customer_group_id = '" . (int) $data['filter_customer_group_id'] . "'";
        }

        if (!empty($data['filter_ip'])) {
            $implode[] = "c.ip = '" . $this->db->escape($data['filter_ip']) . "'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "c.status = '" . (int) $data['filter_status'] . "'";
        }

        if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
            $implode[] = "c.approved = '" . (int) $data['filter_approved'] . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if ($implode) {
            $sql .= " AND " . implode(" AND ", $implode);
        }

        $sort_data = array(
            'name',
            'c.email',
            'customer_group',
            'c.status',
            'c.approved',
            'c.ip',
            'c.date_added'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY name";
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

    public function approve($customer_id) {
        $customer_info = $this->getCustomer($customer_id);

        if ($customer_info) {
            $this->db->query("UPDATE " . DB_PREFIX . "customer SET approved = '1' WHERE customer_id = '" . (int) $customer_id . "'");

            $this->load->model('setting/store');

            $store_info = $this->model_setting_store->getStore($customer_info['store_id']);

            if ($store_info) {
                $store_name = $store_info['name'];
                $store_url = $store_info['url'] . 'index.php?path=account/login';
            } else {
                $store_name = $this->config->get('config_name');
                $store_url = HTTP_CATALOG . 'index.php?path=account/login';
            }

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

    public function getAddress($address_id) {
        $address_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE address_id = '" . (int) $address_id . "'");

        if ($address_query->num_rows) {
            return array(
                'address_id' => $address_query->row['address_id'],
                'customer_id' => $address_query->row['customer_id'],
                'name' => $address_query->row['name'],
                'street_address' => $address_query->row['street_address'],
                'zipcode' => $address_query->row['zipcode'],
                'landmark' => $address_query->row['landmark'],
                'building_name' => $address_query->row['building_name'],
                'flat_number' => $address_query->row['flat_number'],
                'address_type' => $address_query->row['address_type'],
                'contact_no' => $address_query->row['contact_no'],
                'address' => $address_query->row['address'],
                'city_id' => $address_query->row['city_id']
            );
        }
    }

    public function getAddresses($customer_id) {
        $address_data = array();

        $query = $this->db->query("SELECT address_id FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int) $customer_id . "'");

        foreach ($query->rows as $result) {
            $address_info = $this->getAddress($result['address_id']);

            if ($address_info) {
                $address_data[$result['address_id']] = $address_info;
            }
        }

        return $address_data;
    }

    public function getTotalCustomers($data = array()) {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer";

        $implode = array();

        if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (!empty($data['filter_telephone'])) {
            $implode[] = "telephone LIKE '" . $this->db->escape($data['filter_telephone']) . "%'";
        }

        if (isset($data['filter_newsletter']) && !is_null($data['filter_newsletter'])) {
            $implode[] = "newsletter = '" . (int) $data['filter_newsletter'] . "'";
        }

        if (!empty($data['filter_customer_group_id'])) {
            $implode[] = "customer_group_id = '" . (int) $data['filter_customer_group_id'] . "'";
        }

        if (!empty($data['filter_ip'])) {
            $implode[] = "ip = '" . $this->db->escape($data['filter_ip']) . "'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "status = '" . (int) $data['filter_status'] . "'";
        }

        if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
            $implode[] = "approved = '" . (int) $data['filter_approved'] . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalCustomersAwaitingApproval() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE status = '0' OR approved = '0'");

        return $query->row['total'];
    }

    public function getTotalAddressesByCustomerId($customer_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int) $customer_id . "'");

        return $query->row['total'];
    }

    public function getTotalAddressesByCountryId($country_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE country_id = '" . (int) $country_id . "'");

        return $query->row['total'];
    }

    public function getTotalAddressesByZoneId($zone_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE zone_id = '" . (int) $zone_id . "'");

        return $query->row['total'];
    }

    public function getTotalCustomersByCustomerGroupId($customer_group_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE customer_group_id = '" . (int) $customer_group_id . "'");

        return $query->row['total'];
    }

    public function addHistory($customer_id, $comment) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "customer_history SET customer_id = '" . (int) $customer_id . "', comment = '" . $this->db->escape(strip_tags($comment)) . "', date_added = NOW()");
    }

    public function getHistories($customer_id, $start = 0, $limit = 10) {
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 10;
        }

        $query = $this->db->query("SELECT comment, date_added FROM " . DB_PREFIX . "customer_history WHERE customer_id = '" . (int) $customer_id . "' ORDER BY date_added DESC LIMIT " . (int) $start . "," . (int) $limit);

        return $query->rows;
    }

    public function getTotalHistories($customer_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_history WHERE customer_id = '" . (int) $customer_id . "'");

        return $query->row['total'];
    }

    public function addCredit($customer_id, $description = '', $amount = '', $order_id = 0) {
        $customer_info = $this->getCustomer($customer_id);

        $log = new Log('error.log');

        if ($customer_info) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "customer_credit SET customer_id = '" . (int) $customer_id . "', order_id = '" . (int) $order_id . "', description = '" . $this->db->escape($description) . "', amount = '" . (float) $amount . "', date_added = NOW()");

            $this->load->language('mail/customer');

            $this->load->model('setting/store');

            $store_info = $this->model_setting_store->getStore($customer_info['store_id']);

            //echo "<pre>";print_r($store_info);die;
            if ($store_info) {
                $store_name = $store_info['name'];
            } else {
                $store_name = $this->config->get('config_name');
            }

            $data = $customer_info;
            $data['amount'] = $amount;

            $data['transfer_type'] = "debited in";
            if($amount >= 0) 
                $data['transfer_type'] = "credited in";
            if($amount < 0)
                $data['amount'] = -$amount;

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

            if ( $this->emailtemplate->getNotificationEnabled('Customer','customer_6')) {
                
                $log->write('status enabled of mobi noti');
                $mobile_notification_template = $this->emailtemplate->getNotificationMessage( 'Customer', 'customer_6', $data );
                $mobile_notification_title = $this->emailtemplate->getNotificationTitle( 'Customer', 'customer_6', $data );


                if(isset($customer_info['device_id']) && strlen($customer_info['device_id']) > 0 ) {

                    $log->write('device id set');

                    //$notification_id = $this->saveVendorNotification($temporaryVendorInfo['vendor_id'],$customer_info['device_id'],$order_id,$mobile_notification_template,$mobile_notification_title);

                    $sen['wallet_id'] = '';
                    
                    //->setData(array('order_id' => $order_id,'store_id' => $store_id,'notification_id' => $args['notification_id']));
                    $ret =  $this->emailtemplate->sendDynamicPushNotification($customer_info['customer_id'],$customer_info['device_id'],$mobile_notification_template,$mobile_notification_title,$sen);

                    $log->write('device id set end');

                    
                } else {
                    $log->write('device id not set');
                }
            }
        }
    }

    public function deleteCredit($order_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "customer_credit WHERE order_id = '" . (int) $order_id . "'");
    }

    public function getCredits($customer_id, $start = 0, $limit = 10) {
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 10;
        }

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_credit WHERE customer_id = '" . (int) $customer_id . "' ORDER BY date_added DESC LIMIT " . (int) $start . "," . (int) $limit);

        return $query->rows;
    }

    public function getAllCredits($data = array()) {
        $sql = "SELECT CONCAT(c.firstname, ' ', c.lastname) AS name, cgd.*,c.email  FROM " . DB_PREFIX . "customer c  JOIN " . DB_PREFIX . "customer_credit cgd ON (c.customer_id = cgd.customer_id)";

        //echo "<pre>";print_r($sql);die;
        $implode = array();

        if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (!empty($data['filter_order_id'])) {
            $implode[] = "order_id = '" . $this->db->escape($data['filter_order_id']) . "'";
        }

        if (!empty($data['filter_type'])) {
            if(strtolower($data['filter_type']) == 'credit')
                $implode[] = "amount > 0";
            else
                $implode[] = "amount <= 0";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(cgd.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if ($implode) {
            $sql .= " AND " . implode(" AND ", $implode);
        }

        
        $sort_data = array(
            'name',
            'email',
            'date_added'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY name";
        }

        
        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        //echo "<pre>";print_r($sql);die;
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

    public function getTotalWallet($data = array()) {

        $sql = "SELECT COUNT(*) AS total  FROM " . DB_PREFIX . "customer c  JOIN " . DB_PREFIX . "customer_credit cgd ON (c.customer_id = cgd.customer_id)";

        //$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_credit";

        $implode = array();

        if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (!empty($data['filter_order_id'])) {
            $implode[] = "order_id = '" . $this->db->escape($data['filter_order_id']) . "'";
        }

        if (!empty($data['filter_type'])) {
            if(strtolower($data['filter_type']) == 'credit')
                $implode[] = "amount > 0";
            else
                $implode[] = "amount <= 0";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(cgd.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getAllVendorCredits($data = array()) {
        $sql = "SELECT CONCAT(c.firstname, ' ', c.lastname) AS name, cgd.*,c.email  FROM " . DB_PREFIX . "user c  JOIN " . DB_PREFIX . "vendor_wallet cgd ON (c.user_id = cgd.vendor_id)";

        //echo "<pre>";print_r($sql);die;
        $implode = array();

        if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (!empty($data['filter_order_id'])) {
            $implode[] = "order_id = '" . $this->db->escape($data['filter_order_id']) . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(cgd.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (isset($data['filter_date_start']) && !is_null($data['filter_date_start'])) {
            $implode[] = "DATE(cgd.date_added) >= DATE('" . $this->db->escape($data['filter_date_start']) . "')";
        } 

        if (isset($data['filter_date_end']) && !is_null($data['filter_date_end'])) {
            $implode[] = "DATE(cgd.date_added) <= DATE('" . $this->db->escape($data['filter_date_end']) . "')";
        }


        if (!empty($data['filter_type'])) {
            if(strtolower($data['filter_type']) == 'credit')
                $implode[] = "amount > 0";
            else
                $implode[] = "amount <= 0";
        }


        if ($implode) {
            $sql .= " AND " . implode(" AND ", $implode);
        }

        
        $sort_data = array(
            'name',
            'email',
            'date_added'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY name";
        }

        
        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        //echo "<pre>";print_r($sql);die;
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

    public function getTotalVendorWallet($data = array()) {

        $sql = "SELECT COUNT(*) AS total  FROM " . DB_PREFIX . "user c  JOIN " . DB_PREFIX . "vendor_wallet cgd ON (c.user_id = cgd.vendor_id)";

        //$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_credit";

        $implode = array();

        if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (!empty($data['filter_order_id'])) {
            $implode[] = "order_id = '" . $this->db->escape($data['filter_order_id']) . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(cgd.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (isset($data['filter_date_start']) && !is_null($data['filter_date_start'])) {
            $implode[] = "DATE(cgd.date_added) >= DATE('" . $this->db->escape($data['filter_date_start']) . "')";
        } 

        if (isset($data['filter_date_end']) && !is_null($data['filter_date_end'])) {
            $implode[] = "DATE(cgd.date_added) <= DATE('" . $this->db->escape($data['filter_date_end']) . "')";
        }
        

        if (!empty($data['filter_type'])) {
            if(strtolower($data['filter_type']) == 'credit')
                $implode[] = "amount > 0";
            else
                $implode[] = "amount <= 0";
        }


        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getAllAdminCredits($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "admin_wallet where id != 0 ";

        //echo "<pre>";print_r($sql);die;
        $implode = array();

        /*if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }*/

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_order_id'])) {
            $implode[] = "order_id = '" . $this->db->escape($data['filter_order_id']) . "'";
        }

        if (!empty($data['filter_type'])) {
            if(strtolower($data['filter_type']) == 'credit')
                $implode[] = "amount > 0";
            else
                $implode[] = "amount <= 0";
        }

        if ($implode) {
            $sql .= " AND " . implode(" AND ", $implode);
        }

        
        $sort_data = array(
            'date_added'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY date_added";
        }

        
        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        //echo "<pre>";print_r($sql);die;
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

    public function getTotalAdminWallet($data = array()) {

        $sql = "SELECT COUNT(*) AS total  FROM "  . DB_PREFIX . "admin_wallet";

        //$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_credit";

        $implode = array();

        /*if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }*/

        if (!empty($data['filter_order_id'])) {
            $implode[] = "order_id = '" . $this->db->escape($data['filter_order_id']) . "'";
        }
        
        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_type'])) {
            if(strtolower($data['filter_type']) == 'credit')
                $implode[] = "amount > 0";
            else
                $implode[] = "amount <= 0";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalCredits($customer_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total  FROM " . DB_PREFIX . "customer_credit WHERE customer_id = '" . (int) $customer_id . "'");

        return $query->row['total'];
    }

    public function getCreditTotal($customer_id) {
        $query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "customer_credit WHERE customer_id = '" . (int) $customer_id . "'");

        return $query->row['total'];
    }

    public function getTotalCreditsByOrderId($order_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_credit WHERE order_id = '" . (int) $order_id . "'");

        return $query->row['total'];
    }

    public function addReward($customer_id, $description = '', $points = '', $order_id = 0) {
        $customer_info = $this->getCustomer($customer_id);

        if ($customer_info) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "customer_reward SET customer_id = '" . (int) $customer_id . "', order_id = '" . (int) $order_id . "', points = '" . (int) $points . "', description = '" . $this->db->escape($description) . "', date_added = NOW()");

            $this->load->language('mail/customer');

            $this->load->model('setting/store');

            $store_info = $this->model_setting_store->getStore($customer_info['store_id']);

            if ($store_info) {
                $store_name = $store_info['name'];
            } else {
                $store_name = $this->config->get('config_name');
            }

            $message = sprintf($this->language->get('text_reward_received'), $points) . "\n\n";
            $message .= sprintf($this->language->get('text_reward_total'), $this->getRewardTotal($customer_id));

            $mail = new Mail($this->config->get('config_mail'));
            $mail->setTo($customer_info['email']);
            $mail->setFrom($this->config->get('config_from_email'));
            $mail->setSender($store_name);
            $mail->setSubject(sprintf($this->language->get('text_reward_subject'), $store_name));
            $mail->setHTML(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
            $mail->send();
        }
    }

    public function deleteReward($order_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "customer_reward WHERE order_id = '" . (int) $order_id . "' AND points > 0");
    }

    public function getRewards($customer_id, $start = 0, $limit = 10) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int) $customer_id . "' ORDER BY date_added DESC LIMIT " . (int) $start . "," . (int) $limit);

        return $query->rows;
    }

    public function getTotalRewards($customer_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int) $customer_id . "'");

        return $query->row['total'];
    }

    public function getReferrals($customer_id, $start = 0, $limit = 10) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE refree_user_id = '" . (int) $customer_id . "' ORDER BY date_added DESC LIMIT " . (int) $start . "," . (int) $limit);

        return $query->rows;
    }

    public function getTotalReferrals($customer_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE refree_user_id = '" . (int) $customer_id . "'");

        return $query->row['total'];
    }


    public function getRewardTotal($customer_id) {
        $query = $this->db->query("SELECT SUM(points) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int) $customer_id . "'");

        return $query->row['total'];
    }

    public function getTotalCustomerRewardsByOrderId($order_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_reward WHERE order_id = '" . (int) $order_id . "'");

        return $query->row['total'];
    }

    public function getIps($customer_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int) $customer_id . "'");

        return $query->rows;
    }

    public function getTotalIps($customer_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int) $customer_id . "'");

        return $query->row['total'];
    }

    public function getTotalCustomersByIp($ip) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_ip WHERE ip = '" . $this->db->escape($ip) . "'");

        return $query->row['total'];
    }

    public function addBanIp($ip) {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "customer_ban_ip` SET `ip` = '" . $this->db->escape($ip) . "'");
    }

    public function removeBanIp($ip) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "customer_ban_ip` WHERE `ip` = '" . $this->db->escape($ip) . "'");
    }

    public function getTotalBanIpsByIp($ip) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "customer_ban_ip` WHERE `ip` = '" . $this->db->escape($ip) . "'");

        return $query->row['total'];
    }

    public function getTotalLoginAttempts($email) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_login` WHERE `email` = '" . $this->db->escape($email) . "'");

        return $query->row;
    }

    public function deleteLoginAttempts($email) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "customer_login` WHERE `email` = '" . $this->db->escape($email) . "'");
    }

    public function sendCustomerRegisterMail($data) {
        if (isset($data['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($data['customer_group_id'], $this->config->get('config_customer_group_display'))) {
            $customer_group_id = $data['customer_group_id'];
        } else {
            $customer_group_id = $this->config->get('config_customer_group_id');
        }

        $this->load->model('sale/customer_group');

        $customer_group_info = $this->model_sale_customer_group->getCustomerGroup($customer_group_id);

        #Get Email Template
        if (!$customer_group_info['approval']) {
            #Customer Registration Register
            $subject = $this->emailtemplate->getSubject('Customer', 'customer_1', $data);
            $message = $this->emailtemplate->getMessage('Customer', 'customer_1', $data);
            $sms_message = $this->emailtemplate->getSmsMessage('Customer', 'customer_1', $data);
        } else {
            #Customer Registration Approve

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
           // echo "string";   
            // $sms = new sms(); 
            $ret =  $this->emailtemplate->sendmessage($data['telephone'],$sms_message);
            //echo 'after';
            //print_r($ret);
        }
        



        // Send to main admin email if new account email is enabled
        if ($this->config->get('config_account_mail')) {
            $mail->setTo($this->config->get('config_email'));
            $mail->send();

            $emails = explode(',', $this->config->get('config_alert_emails'));

            foreach ($emails as $email) {
                if (strlen($email) > 0 && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $mail->setTo($email);
                    $mail->send();
                }
            }
        }
    }

    public function getUsers($data = array()) {
        $sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name FROM " . DB_PREFIX . "user c WHERE c.user_id !=  0";

        $implode = array();

        if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if ($implode) {
            $sql .= " AND " . implode(" AND ", $implode);
        }

        $sort_data = array(
            'name',
            'c.email',
            'c.date_added'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY name";
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

        //echo $sql;
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function addInSendyListsAndSubscriber($emails) {

        $servername = $this->config->get('config_sendy_db_host');
        $username = $this->config->get('config_sendy_db_user');
        $password = $this->config->get('config_sendy_db_pass');
        $dbname = $this->config->get('config_sendy_db_name');
        $port = $this->config->get('config_sendy_db_port');

        $this->db = new DB('mysqli', $servername, $username, $password, $dbname);

        
        $this->db->query("INSERT INTO lists SET app = 1, userID = 1, name = 'list".rand()."-".date("H:i:s")."'");

        $listId = $this->db->getLastId();

        foreach ($emails as $email) {

            $this->db->query("INSERT INTO subscribers SET email = '".$email."', userID = 1, list = ".$listId);    

        }

        return $listId;
    }

}
