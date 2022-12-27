<?php

class ModelSaleCustomer extends Model {

    public function addCustomer($data) {

        //  echo "<pre>";print_r($data); 
        //   echo "<pre>";print_r('INSERT INTO ' . DB_PREFIX . "customer SET customer_group_id = '" . (int) $data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', gender = '" . $this->db->escape($data['sex']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "',company_name = '" . $this->db->escape($data['company_name']) . "',company_address = '" . $this->db->escape($data['company_address']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "', newsletter = '" . (int) $data['newsletter'] . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', status = '" . (int) $data['status'] . "', approved = '" . (int) $data['approved'] . "', safe = '" . (int) $data['safe'] . "', customer_category = '" . $data['customer_category'] . "', SAP_customer_no = '" . $data['SAP_customer_no'] . "', date_added = NOW()");die;
         if ($data['SAP_customer_no'] && NULL != $data['SAP_customer_no']) {
            $this->db->query('INSERT INTO ' . DB_PREFIX . "customer SET customer_group_id = '" . (int) $data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', gender = '" . $this->db->escape($data['sex']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', dob = '" . $data['dob'] . "', national_id = '" . $data['national_id'] . "', email = '" . $this->db->escape($data['email']) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "',company_name = '" . $this->db->escape($data['company_name']) . "',company_address = '" . $this->db->escape($data['company_address']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "', newsletter = '" . (int) $data['newsletter'] . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', status = '" . (int) $data['status'] . "', approved = '" . (int) $data['approved'] . "', safe = '" . (int) $data['safe'] . "', account_manager_id = '" . $data['account_manager'] . "', customer_experience_id = '" . $data['customer_experience'] . "', customer_category = '" . $data['customer_category'] . "', SAP_customer_no = '" . $data['SAP_customer_no'] . "', source = '" . $data['source'] . "', payment_terms = '" . $data['payment_terms'] . "', statement_duration = '" . $data['statement_duration'] . "', date_added = NOW()");
        } else {
            $this->db->query('INSERT INTO ' . DB_PREFIX . "customer SET customer_group_id = '" . (int) $data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', gender = '" . $this->db->escape($data['sex']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', dob = '" . $data['dob'] . "', national_id = '" . $data['national_id'] . "', email = '" . $this->db->escape($data['email']) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "',company_name = '" . $this->db->escape($data['company_name']) . "',company_address = '" . $this->db->escape($data['company_address']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "', newsletter = '" . (int) $data['newsletter'] . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', status = '" . (int) $data['status'] . "', approved = '" . (int) $data['approved'] . "', safe = '" . (int) $data['safe'] . "', account_manager_id = '" . $data['account_manager'] . "', customer_experience_id = '" . $data['customer_experience'] . "', customer_category = '" . $data['customer_category'] . "', source = '" . $data['source'] . "', payment_terms = '" . $data['payment_terms'] . "', statement_duration = '" . $data['statement_duration'] . "', date_added = NOW()");
        }
        $customer_id = $this->db->getLastId();
        $this->savepassword($customer_id, $data['password']);

        if (isset($data['address'])) {
            foreach ($data['address'] as $address) {
                $this->db->query('INSERT INTO ' . DB_PREFIX . "address SET customer_id = '" . (int) $customer_id . "', name = '" . $this->db->escape($address['name']) . "', address = '" . $this->db->escape($address['address']) . "', city_id = '" . $this->db->escape($address['city_id']) . "', contact_no = '" . $this->db->escape($address['contct_no']) . "'");

                if (isset($address['default'])) {
                    $address_id = $this->db->getLastId();

                    $this->db->query('UPDATE ' . DB_PREFIX . "customer SET address_id = '" . (int) $address_id . "' WHERE customer_id = '" . (int) $customer_id . "'");
                }
            }
        }

        if ($customer_paybill_act == null || empty($customer_paybill_act) ) {
            // $log = new Log('error.log');
            // $log->write("customer_paybill_act");
            $customer_reg_date=$this->db->query('select date_added from  ' . DB_PREFIX . "customer WHERE customer_id = '" . (int) $customer_id . "'")->row;
            // $log->write($customer_reg_date);            
            $customer_reg_date_value=$customer_reg_date['date_added']??date("Y-m-d H:i:s");
            // $log->write($customer_reg_date_value);
            $customer_paybill_act = $customer_id.strtotime($customer_reg_date_value);
            // $log->write($customer_paybill_act); 
            // $log->write("customer_paybill_act");
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET paybill_act = '" . $customer_paybill_act . "' WHERE customer_id = '" . (int) $customer_id . "'");
            // echo "<pre>";print_r('UPDATE ' . DB_PREFIX . "customer SET paybill_act = '" . $customer_paybill_act . "' WHERE customer_id = '" . (int) $customer_id . "'");die;
        }
       //moved mail sending code to controller as , it is givin error from model

        return $customer_id;
    }

    public function editCustomer($customer_id, $data) {
        if (!isset($data['custom_field'])) {
            $data['custom_field'] = [];
        }
        //   echo "<pre>";print_r($data);die;

        $this->db->query('UPDATE ' . DB_PREFIX . "customer SET customer_group_id = '" . (int) $data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', dob = '" . $data['dob'] . "', national_id = '" . $data['national_id'] . "', gender = '" . $this->db->escape($data['sex']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "',company_name = '" . $this->db->escape($data['company_name']) . "',company_address = '" . $this->db->escape($data['company_address']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "', newsletter = '" . (int) $data['newsletter'] . "', status = '" . (int) $data['status'] . "', approved = '" . (int) $data['approved'] . "', safe = '" . (int) $data['safe'] . "', customer_category = '" . $data['customer_category'] . "', account_manager_id = '" . $data['account_manager'] . "', customer_experience_id = '" . $data['customer_experience'] . "',  modified_by = '" . $this->user->getId() . "', modifier_role = '" . $this->user->getGroupName() . "', payment_terms = '" . $data['payment_terms'] . "',statement_duration = '" . $data['statement_duration'] . "', date_modified = NOW() WHERE customer_id = '" . (int) $customer_id . "'");

        if ($data['password'] && 'default' != $data['password']) {
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', tempPassword = '" . (int) 1 . "' WHERE customer_id = '" . (int) $customer_id . "'");
        }


        if ($data['SAP_customer_no'] && NULL != $data['SAP_customer_no']) {
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET SAP_customer_no = '" . $data['SAP_customer_no'] . "' WHERE customer_id = '" . (int) $customer_id . "'");
        }
        $this->savepassword($customer_id, $data['password']);
        $this->deleteoldpassword($customer_id);

        $this->db->query('DELETE FROM ' . DB_PREFIX . "address WHERE customer_id = '" . (int) $customer_id . "'");

        if (isset($data['address'])) {
            foreach ($data['address'] as $address) {
                /* $this->db->query("INSERT INTO " . DB_PREFIX . "address SET address_id = '" . (int) $address['address_id'] . "', customer_id = '" . (int) $customer_id . "', name = '" . $this->db->escape($address['name']) . "', contact_no = '" . $this->db->escape($address['contact_no']) . "', address = '" . $this->db->escape($address['address']) . "', city_id = '" . $this->db->escape($address['city_id']) . "'"); */

                $address['address'] = $address['flat_number'] . ', ' . $address['building_name'] . ', ' . $address['landmark'];

                $this->db->query('INSERT INTO ' . DB_PREFIX . "address SET  address_id = '" . (int) $address['address_id'] . "', customer_id = '" . (int) $customer_id . "', name = '" . $this->db->escape($address['name']) . "', contact_no = '" . $this->db->escape($address['contact_no']) . "', city_id = '" . (int) $address['city_id'] . "', address_type = '" . $this->db->escape($address['address_type']) . "', longitude = '" . $this->db->escape($address['longitude']) . "', latitude = '" . $this->db->escape($address['latitude']) . "', flat_number = '" . $this->db->escape($address['flat_number']) . "', building_name = '" . $this->db->escape($address['building_name']) . "', landmark = '" . $this->db->escape($address['landmark']) . "', zipcode = '" . $this->db->escape($address['zipcode']) . "', address = '" . $this->db->escape($address['address']) . "'");

                if (isset($address['default'])) {
                    $address_id = $this->db->getLastId();

                    $this->db->query('UPDATE ' . DB_PREFIX . "customer SET address_id = '" . (int) $address_id . "' WHERE customer_id = '" . (int) $customer_id . "'");
                }
            }
        }
        
            $log = new Log('error.log');
            $log->write("customer_paybill_act");
        $customer_reg_date=$this->db->query('select date_added,paybill_act from  ' . DB_PREFIX . "customer WHERE customer_id = '" . (int) $customer_id . "'")->row;
        $customer_paybill_act=   $customer_reg_date['paybill_act']??"";
        if ($customer_paybill_act == null || empty($customer_paybill_act) )
         {

            $customer_reg_date_value=$customer_reg_date['date_added']??date("Y-m-d H:i:s");
            $log->write($customer_reg_date_value);
            $customer_paybill_act = $customer_id.strtotime($customer_reg_date_value);
            $log->write($customer_paybill_act); 
            $log->write("customer_paybill_act");
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET paybill_act = '" . $customer_paybill_act . "' WHERE customer_id = '" . (int) $customer_id . "'");
            // echo "<pre>";print_r('UPDATE ' . DB_PREFIX . "customer SET paybill_act = '" . $customer_paybill_act . "' WHERE customer_id = '" . (int) $customer_id . "'");die;
        }
    }

    public function editToken($customer_id, $token) {
        $this->db->query('UPDATE ' . DB_PREFIX . "customer SET token = '" . $this->db->escape($token) . "' WHERE customer_id = '" . (int) $customer_id . "'");
    }

    public function deleteCustomer($customer_id) {
        $this->db->query('DELETE FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . (int) $customer_id . "'");
        $this->db->query('DELETE FROM ' . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int) $customer_id . "'");
        $this->db->query('DELETE FROM ' . DB_PREFIX . "customer_credit WHERE customer_id = '" . (int) $customer_id . "'");
        $this->db->query('DELETE FROM ' . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int) $customer_id . "'");
        $this->db->query('DELETE FROM ' . DB_PREFIX . "address WHERE customer_id = '" . (int) $customer_id . "'");
    }

    public function getCustomer($customer_id) {
        $query = $this->db->query('SELECT DISTINCT * FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . (int) $customer_id . "'");

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

    public function getCustomerAccountManagerDetails($customer_id) {
        $customer_account_manager_details = NULL;
        $customer_details = $this->getCustomer($customer_id);
        if ($customer_details != NULL && $customer_details['account_manager_id'] > 0 && $customer_details['account_manager_id'] != NULL) {
            //$log = new Log('error.log');
            //$log->write($customer_details);
            $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "user WHERE user_id = '" . (int) $customer_details['account_manager_id'] . "'");
            return $query->row;
        } else {
            return $customer_account_manager_details;
        }
    }

    public function getCustomerExperinceDetails($customer_id) {
        $customer_experience_details = NULL;
        $customer_details = $this->getCustomer($customer_id);
        if ($customer_details != NULL && $customer_details['customer_experience_id'] > 0 && $customer_details['customer_experience_id'] != NULL) {
            //$log = new Log('error.log');
            //$log->write($customer_details);
            $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "user WHERE user_id = '" . (int) $customer_details['customer_experience_id'] . "'");
            return $query->row;
        } else {
            return $customer_experience_details;
        }
    }

    public function getCustomerByEmail($email) {
        $query = $this->db->query('SELECT DISTINCT * FROM ' . DB_PREFIX . "customer WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

        return $query->row;
    }

    public function getCustomers($data = []) {
        $sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, cgd.name AS customer_group FROM " . DB_PREFIX . 'customer c LEFT JOIN ' . DB_PREFIX . "customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

        $implode = [];

        if (!empty($data['filter_company'])) {
            $implode[] = "company_name LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        if (!empty($data['filter_name'])) {
            if ($this->user->isVendor()) {
                $implode[] = "c.firstname LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
            } else {
                $implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
            }
        }

        if (!empty($data['filter_parent_customer_id']) && !empty($data['filter_parent_customer'])) {
            $implode[] = "c.parent = '" . $this->db->escape($data['filter_parent_customer_id']) . "' or c.customer_id = '" . $this->db->escape($data['filter_parent_customer_id']) . "'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (!empty($data['filter_company'])) {
            if ('' != $data['filter_company']) {
                $implode[] = "c.company_name = '" . $this->db->escape($data['filter_company']) . "'";
            }
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



        if (isset($data['filter_pezesha']) && !is_null($data['filter_pezesha'])) {
            if ($data['filter_pezesha'] == '1') {
                $implode[] = "c.customer_id in  (select customer_id from hf7_pezesha_customers )";
            } else {
                $implode[] = "c.customer_id not in  (select customer_id from hf7_pezesha_customers )";
            }
        }

        if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
            $implode[] = "c.approved = '" . (int) $data['filter_approved'] . "'";
        }

        if ($this->user->isAccountManager()) {//in gettotalcustomers it is available
            $implode[] = "account_manager_id = '" . (int) $this->user->getId() . "'";
        }

        // if (!empty($data['filter_date_added'])) {
        //     $implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        // }


        if (!empty($data['filter_date_added']) && empty($data['filter_date_added_to'])) {
            $implode[] = " DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_to'])) {
            $implode[] = " DATE(c.date_added) BETWEEN DATE('" . $this->db->escape($data['filter_date_added']) . "') AND DATE('" . $this->db->escape($data['filter_date_added_to']) . "')";
        }



        if (isset($data['filter_parent']) && !is_null($data['filter_parent'])) {
            $implode[] = "c.parent = '" . (int) $data['filter_parent'] . "'";
        }

        if (isset($data['filter_account_manager_name']) && !is_null($data['filter_account_manager_id'])) {
            $implode[] = "c.account_manager_id = '" . (int) $data['filter_account_manager_id'] . "'";
        }

        if (isset($data['filter_payment_terms']) && !is_null($data['filter_payment_terms']) && $data['filter_payment_terms'] != NULL) {
            $implode[] = "c.payment_terms = '" . $data['filter_payment_terms'] . "'";
        }

        if (isset($data['filter_customer_price_category']) && !is_null($data['filter_customer_price_category']) && $data['filter_customer_price_category'] != NULL) {
            $implode[] = "c.customer_category = '" . $data['filter_customer_price_category'] . "'";
        }

        if (!empty($data['filter_customer_experience_id']) && !empty($data['filter_customer_experience_id'])) {
            $implode[] = "customer_experience_id = '" . $this->db->escape($data['filter_customer_experience_id']) . "'";
        }

        if (($data['filter_sub_customer_show'] == 0 || $data['filter_sub_customer_show'] == NULL || !array_key_exists('filter_sub_customer_show', $data)) && !isset($data['filter_parent_customer_id'])) {//!array_key_exists('filter_parent_customer_id', $data)
            $implode[] = "(parent = 0 OR parent IS NULL)";
        }

        if (!empty($data['filter_sub_customer_show']) && !empty($data['filter_sub_customer_show']) && $data['filter_sub_customer_show'] == 1) {
            //$implode[] = "parent > 0";
        }

        if (!empty($data['filter_monthyear_added'])) {
            $implode[] = "DATE_FORMAT(date_added, '%Y-%m') = '" . $this->db->escape($data['filter_monthyear_added']) . "'";
        }

        if ($implode) {
            $sql .= ' AND ' . implode(' AND ', $implode);
        }

        $sort_data = [
            'name',
            'c.email',
            'customer_group',
            'c.status',
            'c.approved',
            'c.ip',
            'c.date_added',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY name';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        $query = $this->db->query($sql);

        //echo "<pre>";print_r($sql);die;

        return $query->rows;
    }

    public function getCustomersNew($data = []) {
        $sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, cgd.name AS customer_group FROM " . DB_PREFIX . 'customer c LEFT JOIN ' . DB_PREFIX . "customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

        $implode = [];

        if (!empty($data['filter_company'])) {
            $implode[] = "company_name LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        if (!empty($data['filter_name'])) {
            if ($this->user->isVendor()) {
                $implode[] = "c.firstname LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
            } else {
                $implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
            }
        }

        if (!empty($data['filter_parent_customer_id']) && !empty($data['filter_parent_customer'])) {
            $implode[] = "c.parent = '" . $this->db->escape($data['filter_parent_customer_id']) . "' or c.customer_id = '" . $this->db->escape($data['filter_parent_customer_id']) . "'";
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

        // if (!empty($data['filter_date_added'])) {
        //     $implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        // }


        if (!empty($data['filter_date_added']) && empty($data['filter_date_added_to'])) {
            $implode[] = " DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_to'])) {
            $implode[] = " DATE(c.date_added) BETWEEN DATE('" . $this->db->escape($data['filter_date_added']) . "') AND DATE('" . $this->db->escape($data['filter_date_added_to']) . "')";
        }



        if (isset($data['filter_parent']) && !is_null($data['filter_parent'])) {
            $implode[] = "c.parent = '" . (int) $data['filter_parent'] . "'";
        }

        if (isset($data['filter_account_manager_name']) && !is_null($data['filter_account_manager_id'])) {
            $implode[] = "c.account_manager_id = '" . (int) $data['filter_account_manager_id'] . "'";
        }

        if (!empty($data['filter_sub_customer_show']) && !empty($data['filter_sub_customer_show']) && $data['filter_sub_customer_show'] == 1) {
            //$implode[] = "parent > 0";
        }

        if (!empty($data['filter_monthyear_added'])) {
            $implode[] = "DATE_FORMAT(date_added, '%Y-%m') = '" . $this->db->escape($data['filter_monthyear_added']) . "'";
        }

        if ($implode) {
            $sql .= ' AND ' . implode(' AND ', $implode);
        }

        $sort_data = [
            'name',
            'c.email',
            'customer_group',
            'c.status',
            'c.approved',
            'c.ip',
            'c.date_added',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY name';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        $query = $this->db->query($sql);

        //echo "<pre>";print_r($sql);die;

        return $query->rows;
    }

    public function getCustomerEmailById($data) {
        $sql = "SELECT c.customer_id, c.email, c.telephone, c.device_id, CONCAT(c.firstname, ' ', c.lastname) AS name, cgd.name AS customer_group FROM " . DB_PREFIX . 'customer c LEFT JOIN ' . DB_PREFIX . "customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

        $implode = [];

        if (!empty($data['filter_customer_id'])) {
            $implode[] = "c.customer_id IN (" . $data['filter_customer_id'] . ")";
        }

        if ($implode) {
            $sql .= ' AND ' . implode(' AND ', $implode);
        }

        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function getCustomersOTP($data = []) {
        $sql = "SELECT c.customer_id, c.company_name, c.email, c.source, c.telephone, o.id, o.customer_id as otp_customer_id, o.otp, o.type, o.expiry_time, o.created_at as otp_created_at, o.updated_at as otp_updated_at, CONCAT(c.firstname, ' ', c.lastname) AS name FROM " . DB_PREFIX . 'otp o LEFT JOIN ' . DB_PREFIX . "customer c ON (o.customer_id = c.customer_id)";

        $implode = [];

        if (!empty($data['filter_company'])) {
            $implode[] = "c.company_name LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        if (!empty($data['filter_name'])) {
            if ($this->user->isVendor()) {
                $implode[] = "c.firstname LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
            } else {
                $implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
            }
        }


        if (!empty($data['filter_email'])) {
            $implode[] = "c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (!empty($data['filter_company'])) {
            if ('' != $data['filter_company']) {
                $implode[] = "c.company_name = '" . $this->db->escape($data['filter_company']) . "'";
            }
        }

        if (!empty($data['filter_telephone'])) {
            $implode[] = "c.telephone LIKE '" . $this->db->escape($data['filter_telephone']) . "%'";
        }

        if (!empty($data['filter_ip'])) {
            $implode[] = "c.ip = '" . $this->db->escape($data['filter_ip']) . "'";
        }

        if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
            $implode[] = "c.approved = '" . (int) $data['filter_approved'] . "'";
        }

        if (!empty($data['filter_date_added'])) {
            //$implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
            $implode[] = "(DATE_FORMAT(o.created_at, '%Y-%m-%d') = '" . $this->db->escape($data['filter_date_added']) . "' OR DATE_FORMAT(o.updated_at, '%Y-%m-%d') = '" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (isset($data['filter_parent']) && !is_null($data['filter_parent'])) {
            $implode[] = "c.parent = '" . (int) $data['filter_parent'] . "'";
        }

        if (isset($data['filter_account_manager_name']) && !is_null($data['filter_account_manager_id'])) {
            $implode[] = "c.account_manager_id = '" . (int) $data['filter_account_manager_id'] . "'";
        }

        if (!empty($data['filter_monthyear_added'])) {
            $implode[] = "DATE_FORMAT(date_added, '%Y-%m') = '" . $this->db->escape($data['filter_monthyear_added']) . "'";
        }

        if (!isset($data['filter_date_added']) || empty($data['filter_date_added'])) {
            $implode[] = "(DATE_FORMAT(o.created_at, '%d-%m-%Y') = '" . date('d-m-Y') . "' OR DATE_FORMAT(o.updated_at, '%d-%m-%Y') = '" . date('d-m-Y') . "')";
        }
        /* if ($implode) {
          $sql .= ' AND ' . implode(' AND ', $implode);
          } */

        if ($implode) {
            $sql .= ' WHERE ' . implode(' AND ', $implode);
        }

        $sort_data = [
            'name',
            'c.email',
            'customer_group',
            'c.status',
            'c.approved',
            'c.ip',
            'c.date_added',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY name';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }
        /* $log = new Log('error.log');
          $log->write($sql); */
        $query = $this->db->query($sql);

        //echo "<pre>";print_r($sql);die;

        return $query->rows;
    }

    public function getCustomersOTPS($data = []) {
        $sql = "SELECT c.customer_id, c.company_name, c.email, c.source, c.telephone, o.id, o.customer_id as otp_customer_id, o.otp, o.type, o.expiry_time, o.created_at as otp_created_at, o.updated_at as otp_updated_at, CONCAT(c.firstname, ' ', c.lastname) AS name FROM " . DB_PREFIX . 'otp o LEFT JOIN ' . DB_PREFIX . "customer c ON (o.customer_id = c.telephone)";

        $implode = [];

        if (!empty($data['filter_company'])) {
            $implode[] = "c.company_name LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        if (!empty($data['filter_name'])) {
            if ($this->user->isVendor()) {
                $implode[] = "c.firstname LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
            } else {
                $implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
            }
        }


        if (!empty($data['filter_email'])) {
            $implode[] = "c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (!empty($data['filter_company'])) {
            if ('' != $data['filter_company']) {
                $implode[] = "c.company_name = '" . $this->db->escape($data['filter_company']) . "'";
            }
        }

        if (!empty($data['filter_telephone'])) {
            $implode[] = "c.telephone LIKE '" . $this->db->escape($data['filter_telephone']) . "%'";
        }

        if (!empty($data['filter_ip'])) {
            $implode[] = "c.ip = '" . $this->db->escape($data['filter_ip']) . "'";
        }

        if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
            $implode[] = "c.approved = '" . (int) $data['filter_approved'] . "'";
        }

        if (!empty($data['filter_date_added'])) {
            //$implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
            $implode[] = "(DATE_FORMAT(o.created_at, '%Y-%m-%d') = '" . $this->db->escape($data['filter_date_added']) . "' OR DATE_FORMAT(o.updated_at, '%Y-%m-%d') = '" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (isset($data['filter_parent']) && !is_null($data['filter_parent'])) {
            $implode[] = "c.parent = '" . (int) $data['filter_parent'] . "'";
        }

        if (isset($data['filter_account_manager_name']) && !is_null($data['filter_account_manager_id'])) {
            $implode[] = "c.account_manager_id = '" . (int) $data['filter_account_manager_id'] . "'";
        }

        if (!empty($data['filter_monthyear_added'])) {
            $implode[] = "DATE_FORMAT(date_added, '%Y-%m') = '" . $this->db->escape($data['filter_monthyear_added']) . "'";
        }

        if (!isset($data['filter_date_added']) || empty($data['filter_date_added'])) {
            $implode[] = "(DATE_FORMAT(o.created_at, '%d-%m-%Y') = '" . date('d-m-Y') . "' OR DATE_FORMAT(o.updated_at, '%d-%m-%Y') = '" . date('d-m-Y') . "')";
        }
        /* if ($implode) {
          $sql .= ' AND ' . implode(' AND ', $implode);
          } */

        if ($implode) {
            $sql .= ' WHERE ' . implode(' AND ', $implode);
        }

        $sort_data = [
            'name',
            'c.email',
            'customer_group',
            'c.status',
            'c.approved',
            'c.ip',
            'c.date_added',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY name';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }
        /* $log = new Log('error.log');
          $log->write($sql); */
        $query = $this->db->query($sql);

        //echo "<pre>";print_r($sql);die;

        return $query->rows;
    }

    public function getSubCustomers($data = []) {
        $sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, cgd.name AS customer_group FROM " . DB_PREFIX . 'customer c LEFT JOIN ' . DB_PREFIX . "customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

        $implode = [];

        if (!empty($data['filter_company'])) {
            $implode[] = "company_name LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        if (!empty($data['filter_name'])) {
            if ($this->user->isVendor()) {
                $implode[] = "c.firstname LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
            } else {
                $implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
            }
        }

        if (!empty($data['filter_parent_customer_id']) && !empty($data['filter_parent_customer'])) {
            $implode[] = "c.parent = '" . $this->db->escape($data['filter_parent_customer_id']) . "'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (!empty($data['filter_company'])) {
            if ('' != $data['filter_company']) {
                $implode[] = "c.company_name = '" . $this->db->escape($data['filter_company']) . "'";
            }
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


        if (isset($data['filter_parent']) && !is_null($data['filter_parent'])) {
            $implode[] = "c.parent = '" . (int) $data['filter_parent'] . "'";
        }

        if (isset($data['filter_account_manager_name']) && !is_null($data['filter_account_manager_id'])) {
            $implode[] = "c.account_manager_id = '" . (int) $data['filter_account_manager_id'] . "'";
        }

        if (!empty($data['filter_monthyear_added'])) {
            $implode[] = "DATE_FORMAT(date_added, '%Y-%m') = '" . $this->db->escape($data['filter_monthyear_added']) . "'";
        }

        if ($implode) {
            $sql .= ' AND ' . implode(' AND ', $implode);
        }

        $sort_data = [
            'name',
            'c.email',
            'customer_group',
            'c.status',
            'c.approved',
            'c.ip',
            'c.date_added',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY name';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        $query = $this->db->query($sql);

        //echo "<pre>";print_r($sql);die;

        return $query->rows;
    }

    public function getCompanies($data = []) {
        $sql = 'SELECT distinct company_name AS name FROM ' . DB_PREFIX . 'customer WHERE status = 1';

        $implode = [];
        if (!empty($data['filter_name'])) {
            $implode[] = " company_name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }
        if (!empty($data['filter_account_manager_id'])) {
            $implode[] = " account_manager_id = '" . $this->db->escape($data['filter_account_manager_id']) . "'";
        }
        if ($implode) {
            $sql .= ' AND ' . implode(' AND ', $implode);
        }
        $sql .= ' ORDER BY company_name';

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getCompanies_pezesha_all($data = []) {
        $sql = 'SELECT distinct company_name AS name FROM ' . DB_PREFIX . 'customer WHERE status = 1';

        $implode = [];
        if (!empty($data['filter_name'])) {
            $implode[] = " company_name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }
        $implode[] = "(parent in (select customer_id from hf7_pezesha_customers) || customer_id in (select customer_id from hf7_pezesha_customers))";

        if ($implode) {
            $sql .= ' AND ' . implode(' AND ', $implode);
        }
        $sql .= ' ORDER BY company_name';

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getCustomers_pezesha($data = []) {
        $sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, cgd.name AS customer_group FROM " . DB_PREFIX . 'customer c LEFT JOIN ' . DB_PREFIX . "customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

        $implode = [];

        if (!empty($data['filter_company'])) {
            $implode[] = "company_name LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }
        $implode[] = "(c.parent in (select customer_id from hf7_pezesha_customers) || c.customer_id in (select customer_id from hf7_pezesha_customers))";

        if (!empty($data['filter_name'])) {
            if ($this->user->isVendor()) {
                $implode[] = "c.firstname LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
            } else {
                $implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
            }
        }



        if (!empty($data['filter_company'])) {
            if ('' != $data['filter_company']) {
                $implode[] = "c.company_name = '" . $this->db->escape($data['filter_company']) . "'";
            }
        }




        if ($implode) {
            $sql .= ' AND ' . implode(' AND ', $implode);
        }

        $sort_data = [
            'name',
            'c.email',
            'customer_group',
            'c.status',
            'c.approved',
            'c.ip',
            'c.date_added',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY name';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        $query = $this->db->query($sql);

        //echo "<pre>";print_r($sql);die;

        return $query->rows;
    }

    public function getCompanies_pezesha($data = []) {
        $sql = 'SELECT distinct customer_id,company_name AS name FROM ' . DB_PREFIX . 'customer WHERE status = 1 and customer_id in (select customer_id from hf7_pezesha_customers)';

        $implode = [];
        if (!empty($data['filter_name'])) {
            $implode[] = " company_name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }
        if (!empty($data['filter_account_manager_id'])) {
            $implode[] = " account_manager_id = '" . $this->db->escape($data['filter_account_manager_id']) . "'";
        }
        if ($implode) {
            $sql .= ' AND ' . implode(' AND ', $implode);
        }
        $sql .= ' ORDER BY company_name';

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getPayments($data = []) {
        $sql = 'SELECT distinct code AS name FROM ' . DB_PREFIX . 'extension WHERE  type="payment"';

        $implode = [];
        if (!empty($data['filter_name'])) {
            $implode[] = " code LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if ($implode) {
            $sql .= ' AND ' . implode(' AND ', $implode);
        }
        $sql .= ' ORDER BY code';

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getParentCustomers($data = []) {
        $log = new Log('error.log');
        $parent_customer_ids = array();
        $parent_customer_query = $this->db->query('SELECT DISTINCT parent FROM ' . DB_PREFIX . "customer WHERE parent > 0");
        if ($parent_customer_query->num_rows > 0) {
            $parent_customer_ids = array_column($parent_customer_query->rows, 'parent');
            $parent_customer_ids = implode(', ', $parent_customer_ids);
        }
        $sql = 'SELECT *, CONCAT(firstname, " ", lastname) AS name FROM ' . DB_PREFIX . 'customer WHERE customer_id IN(' . $parent_customer_ids . ') AND  status = 1';

        $implode = [];
        if (!empty($data['filter_parent_customer'])) {
            $implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_parent_customer']) . "%'";
        }
        if ($implode) {
            $sql .= ' AND ' . implode(' AND ', $implode);
        }
        $sql .= ' ORDER BY name';

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getParentCompanies($data = []) {
        $log = new Log('error.log');
        $sql = 'SELECT customer_id, company_name, CONCAT(firstname, " ", lastname) AS name FROM ' . DB_PREFIX . 'customer WHERE status = 1 AND (parent = 0 OR parent IS NULL)';

        $implode = [];
        if (!empty($data['filter_parent_customer'])) {
            $implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_parent_customer']) . "%'";
        }
        if (!empty($data['filter_parent_company'])) {
            $implode[] = "company_name LIKE '%" . $this->db->escape($data['filter_parent_company']) . "%'";
        }
        if ($implode) {
            $sql .= ' AND ' . implode(' AND ', $implode);
        }
        $sql .= ' ORDER BY name';

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function approve($customer_id) {
        $customer_info = $this->getCustomer($customer_id);

        if ($customer_info) {
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET approved = '1' WHERE customer_id = '" . (int) $customer_id . "'");
            //AFTER CUSTOMER VERIFIED MAIL SENDING
            if (ENV != 'testing') {
                try {
                    $this->load->model('setting/store');

                    $store_info = $this->model_setting_store->getStore($customer_info['store_id']);

                    if ($store_info) {
                        $store_name = $store_info['name'];
                        //$store_url = $store_info['url'] . 'index.php?path=account/login';
                        $store_url = 'https://www.kwikbasket.com/index.php?path=account/login';
                    } else {
                        $store_name = $this->config->get('config_name');
                        //$store_url = HTTP_CATALOG . 'index.php?path=account/login';
                        $store_url = 'https://www.kwikbasket.com/index.php?path=account/login';
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
                    if ($this->emailtemplate->getSmsEnabled('Customer', 'customer_4')) {
                        $ret = $this->emailtemplate->sendmessage($customer_info['telephone'], $sms_message);
                    }
                } catch (Exception $e) {
                    $log = new Log('error.log');
                    $log->write($e);
                }
            }
        }
    }

    public function getAddress($address_id) {
        $address_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "address WHERE address_id = '" . (int) $address_id . "'");

        if ($address_query->num_rows) {
            return [
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
                'city_id' => $address_query->row['city_id'],
                'latitude' => $address_query->row['latitude'],
                'longitude' => $address_query->row['longitude'],
            ];
        }
    }

    public function getAddresses($customer_id) {
        $address_data = [];

        $query = $this->db->query('SELECT address_id FROM ' . DB_PREFIX . "address WHERE customer_id = '" . (int) $customer_id . "'");

        foreach ($query->rows as $result) {
            $address_info = $this->getAddress($result['address_id']);

            if ($address_info) {
                $address_data[$result['address_id']] = $address_info;
            }
        }

        return $address_data;
    }

    public function getTotalCustomers($data = []) {
        $sql = 'SELECT COUNT(*) AS total FROM ' . DB_PREFIX . 'customer';

        $implode = [];

        if (!empty($data['filter_company'])) {
            $implode[] = "company_name LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

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

        if (!empty($data['filter_parent_customer_id']) && !empty($data['filter_parent_customer'])) {
            $implode[] = "parent = '" . $this->db->escape($data['filter_parent_customer_id']) . "' or customer_id = '" . $this->db->escape($data['filter_parent_customer_id']) . "'";
        }

        if (!empty($data['filter_account_manager_id']) && !empty($data['filter_account_manager_name'])) {
            $implode[] = "account_manager_id = '" . $this->db->escape($data['filter_account_manager_id']) . "'";
        }

        if (!empty($data['filter_customer_experience_id']) && !empty($data['filter_customer_experience_id'])) {
            $implode[] = "customer_experience_id = '" . $this->db->escape($data['filter_customer_experience_id']) . "'";
        }

        if (!empty($data['filter_payment_terms']) && !empty($data['filter_payment_terms']) && $data['filter_payment_terms'] != NULL) {
            $implode[] = "payment_terms = '" . $this->db->escape($data['filter_payment_terms']) . "'";
        }

        if (!empty($data['filter_customer_price_category']) && !empty($data['filter_customer_price_category']) && $data['filter_customer_price_category'] != NULL) {
            $implode[] = "customer_category = '" . $this->db->escape($data['filter_customer_price_category']) . "'";
        }

        if (($data['filter_sub_customer_show'] == 0 || $data['filter_sub_customer_show'] == NULL || !array_key_exists('filter_sub_customer_show', $data)) && !isset($data['filter_parent_customer_id'])) {//!array_key_exists('filter_parent_customer_id', $data)
            $implode[] = "(parent = 0 OR parent IS NULL)";
        }

        if (!empty($data['filter_sub_customer_show']) && !empty($data['filter_sub_customer_show']) && $data['filter_sub_customer_show'] == 1) {
            //$implode[] = "parent > 0";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "status = '" . (int) $data['filter_status'] . "'";
        }


        if (isset($data['filter_pezesha']) && !is_null($data['filter_pezesha'])) {
            if ($data['filter_pezesha'] == '1') {
                $implode[] = "customer_id in  (select customer_id from hf7_pezesha_customers )";
            } else {
                $implode[] = " customer_id not in  (select customer_id from hf7_pezesha_customers )";
            }
        }

        if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
            $implode[] = "approved = '" . (int) $data['filter_approved'] . "'";
        }

        // if (!empty($data['filter_date_added'])) {
        //     $implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        // }

        if (!empty($data['filter_date_added']) && empty($data['filter_date_added_to'])) {
            $implode[] = " DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_to'])) {
            $implode[] = " DATE(date_added) BETWEEN DATE('" . $this->db->escape($data['filter_date_added']) . "') AND DATE('" . $this->db->escape($data['filter_date_added_to']) . "')";
        }



        if ($this->user->isAccountManager()) {
            $implode[] = "account_manager_id = '" . (int) $this->user->getId() . "'";
        }

        if (!empty($data['filter_monthyear_added'])) {
            $implode[] = "DATE_FORMAT(date_added, '%Y-%m') = '" . $this->db->escape($data['filter_monthyear_added']) . "'";
        }

        if ($implode) {
            $sql .= ' WHERE ' . implode(' AND ', $implode);
        }


        // echo "<pre>";print_r($sql);die;

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalOTPCustomers($data = []) {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . 'otp o LEFT JOIN ' . DB_PREFIX . "customer c ON (o.customer_id = c.customer_id)";
        //$sql = 'SELECT COUNT(*) AS total FROM ' . DB_PREFIX . 'otp';

        $implode = [];

        if (!empty($data['filter_company'])) {
            $implode[] = "company_name LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (!empty($data['filter_telephone'])) {
            $implode[] = "telephone LIKE '" . $this->db->escape($data['filter_telephone']) . "%'";
        }

        if (!empty($data['filter_ip'])) {
            $implode[] = "ip = '" . $this->db->escape($data['filter_ip']) . "'";
        }

        if (!empty($data['filter_date_added'])) {
            //$implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
            $implode[] = "(DATE_FORMAT(o.created_at, '%Y-%m-%d') = '" . $this->db->escape($data['filter_date_added']) . "' OR DATE_FORMAT(o.updated_at, '%Y-%m-%d') = '" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if ($this->user->isAccountManager()) {
            $implode[] = "account_manager_id = '" . (int) $this->user->getId() . "'";
        }

        if (!empty($data['filter_monthyear_added'])) {
            $implode[] = "DATE_FORMAT(date_added, '%Y-%m') = '" . $this->db->escape($data['filter_monthyear_added']) . "'";
        }

        if (!isset($data['filter_date_added']) || empty($data['filter_date_added'])) {
            $implode[] = "(DATE_FORMAT(o.created_at, '%d-%m-%Y') = '" . date('d-m-Y') . "' OR DATE_FORMAT(o.updated_at, '%d-%m-%Y') = '" . date('d-m-Y') . "')";
        }

        if ($implode) {
            $sql .= ' WHERE ' . implode(' AND ', $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalOTPCustomerss($data = []) {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . 'otp o LEFT JOIN ' . DB_PREFIX . "customer c ON (o.customer_id = c.telephone)";
        //$sql = 'SELECT COUNT(*) AS total FROM ' . DB_PREFIX . 'otp';

        $implode = [];

        if (!empty($data['filter_company'])) {
            $implode[] = "company_name LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (!empty($data['filter_telephone'])) {
            $implode[] = "telephone LIKE '" . $this->db->escape($data['filter_telephone']) . "%'";
        }

        if (!empty($data['filter_ip'])) {
            $implode[] = "ip = '" . $this->db->escape($data['filter_ip']) . "'";
        }

        if (!empty($data['filter_date_added'])) {
            //$implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
            $implode[] = "(DATE_FORMAT(o.created_at, '%Y-%m-%d') = '" . $this->db->escape($data['filter_date_added']) . "' OR DATE_FORMAT(o.updated_at, '%Y-%m-%d') = '" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if ($this->user->isAccountManager()) {
            $implode[] = "account_manager_id = '" . (int) $this->user->getId() . "'";
        }

        if (!empty($data['filter_monthyear_added'])) {
            $implode[] = "DATE_FORMAT(date_added, '%Y-%m') = '" . $this->db->escape($data['filter_monthyear_added']) . "'";
        }

        if (!isset($data['filter_date_added']) || empty($data['filter_date_added'])) {
            $implode[] = "(DATE_FORMAT(o.created_at, '%d-%m-%Y') = '" . date('d-m-Y') . "' OR DATE_FORMAT(o.updated_at, '%d-%m-%Y') = '" . date('d-m-Y') . "')";
        }

        if ($implode) {
            $sql .= ' WHERE ' . implode(' AND ', $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalCustomersForDashboard($data = []) {
        $sql = 'SELECT COUNT(*) AS total FROM ' . DB_PREFIX . 'customer';

        $implode = [];

        if (!empty($data['filter_company'])) {
            $implode[] = "company_name LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

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

        if (!empty($data['filter_parent_customer_id']) && !empty($data['filter_parent_customer'])) {
            $implode[] = "parent = '" . $this->db->escape($data['filter_parent_customer_id']) . "'";
        }

        if (!empty($data['filter_account_manager_id']) && !empty($data['filter_account_manager_name'])) {
            $implode[] = "account_manager_id = '" . $this->db->escape($data['filter_account_manager_id']) . "'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "status = '" . (int) $data['filter_status'] . "'";
        }

        if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
            $implode[] = "approved = '" . (int) $data['filter_approved'] . "'";
        }

        if (!empty($data['filter_date_added']) && empty($data['filter_date_added_end'])) {
            $implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if ($this->user->isAccountManager()) {
            $implode[] = "account_manager_id = '" . (int) $this->user->getId() . "'";
        }

        if (!empty($data['filter_monthyear_added'])) {
            $implode[] = "DATE_FORMAT(date_added, '%Y-%m') = '" . $this->db->escape($data['filter_monthyear_added']) . "'";
        }

        if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_end'])) {
            $implode[] = "DATE(date_added) BETWEEN DATE('" . $this->db->escape($data['filter_date_added']) . "') AND DATE('" . $this->db->escape($data['filter_date_added_end']) . "')";
        }

        //REMOVED FETCHING ONLY PARENT CUSTOMERS
        //$implode[] = "parent is null or parent = 0";

        if ($implode) {
            $sql .= ' WHERE ' . implode(' AND ', $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalCustomersOnBoarded($data = []) {
        $sql = 'SELECT COUNT(*) AS total FROM ' . DB_PREFIX . 'customer c where   c.customer_id  in (select o.customer_id from ' . DB_PREFIX . 'order o where o.order_status_id NOT IN (0,16,6,8,9,10))';

        // $implode = [];

        if (!empty($data['filter_company'])) {
            $sql .= "And c.company_name LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        if (!empty($data['filter_name'])) {
            $sql .= "And CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_email'])) {
            $sql .= "And c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (!empty($data['filter_telephone'])) {
            $sql .= "And c.telephone LIKE '" . $this->db->escape($data['filter_telephone']) . "%'";
        }

        if (isset($data['filter_newsletter']) && !is_null($data['filter_newsletter'])) {
            $sql .= "And c.newsletter = '" . (int) $data['filter_newsletter'] . "'";
        }

        if (!empty($data['filter_customer_group_id'])) {
            $sql .= "And c.customer_group_id = '" . (int) $data['filter_customer_group_id'] . "'";
        }

        if (!empty($data['filter_ip'])) {
            $sql .= "And c.ip = '" . $this->db->escape($data['filter_ip']) . "'";
        }

        if (!empty($data['filter_parent_customer_id']) && !empty($data['filter_parent_customer'])) {
            $sql .= "And c.parent = '" . $this->db->escape($data['filter_parent_customer_id']) . "'";
        }

        if (!empty($data['filter_account_manager_id']) && !empty($data['filter_account_manager_name'])) {
            $sql .= "And c.account_manager_id = '" . $this->db->escape($data['filter_account_manager_id']) . "'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= "And c.status = '" . (int) $data['filter_status'] . "'";
        }
        // $implode[] = "approved = 1";
        // if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
        //     $implode[] = "c.approved = '" . (int) $data['filter_approved'] . "'";
        // }

        if (!empty($data['filter_date_added']) && empty($data['filter_date_added_end'])) {
            $sql .= "And DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if ($this->user->isAccountManager()) {
            $sql .= "And  c.account_manager_id = '" . (int) $this->user->getId() . "'";
        }

        if (!empty($data['filter_monthyear_added'])) {
            $sql .= "And  DATE_FORMAT(c.date_added, '%Y-%m') = '" . $this->db->escape($data['filter_monthyear_added']) . "'";
        }


        if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_end'])) {
            $sql .= "And DATE(c.date_added) BETWEEN DATE('" . $this->db->escape($data['filter_date_added']) . "') AND DATE('" . $this->db->escape($data['filter_date_added_end']) . "')";
        }


        // $implode[] = " o.order_status_id NOT IN (0)";
        //$implode[] = "c.parent is null or c.parent = 0";
        // if ($implode) {
        //     $sql .= ' WHERE ' . implode(' AND ', $implode);
        // }
        // $sql .= ' GROUP BY c.customer_id';
        // echo "<pre>";print_r($sql);die;

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalCustomersAwaitingApproval() {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . "customer WHERE status = '0' OR approved = '0'");

        return $query->row['total'];
    }

    public function getTotalAddressesByCustomerId($customer_id) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . "address WHERE customer_id = '" . (int) $customer_id . "'");

        return $query->row['total'];
    }

    public function getTotalAddressesByCountryId($country_id) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . "address WHERE country_id = '" . (int) $country_id . "'");

        return $query->row['total'];
    }

    public function getTotalAddressesByZoneId($zone_id) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . "address WHERE zone_id = '" . (int) $zone_id . "'");

        return $query->row['total'];
    }

    public function getTotalCustomersByCustomerGroupId($customer_group_id) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . "customer WHERE customer_group_id = '" . (int) $customer_group_id . "'");

        return $query->row['total'];
    }

    public function addHistory($customer_id, $comment) {
        $this->db->query('INSERT INTO ' . DB_PREFIX . "customer_history SET customer_id = '" . (int) $customer_id . "', comment = '" . $this->db->escape(strip_tags($comment)) . "', date_added = NOW()");
    }

    public function getHistories($customer_id, $start = 0, $limit = 10) {
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 10;
        }

        $query = $this->db->query('SELECT comment, date_added FROM ' . DB_PREFIX . "customer_history WHERE customer_id = '" . (int) $customer_id . "' ORDER BY date_added DESC LIMIT " . (int) $start . ',' . (int) $limit);

        return $query->rows;
    }

    public function getTotalHistories($customer_id) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . "customer_history WHERE customer_id = '" . (int) $customer_id . "'");

        return $query->row['total'];
    }

    public function addCredit($customer_id, $description = '', $amount = '', $order_id = 0) {
        $customer_info = $this->getCustomer($customer_id);

        $log = new Log('error.log');

        if ($customer_info && 1 != 1) {//customer credit NA
            $this->db->query('INSERT INTO ' . DB_PREFIX . "customer_credit SET customer_id = '" . (int) $customer_id . "', order_id = '" . (int) $order_id . "', description = '" . $this->db->escape($description) . "', amount = '" . (float) $amount . "', date_added = NOW()");

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

    public function deleteCredit($order_id) {
        $this->db->query('DELETE FROM ' . DB_PREFIX . "customer_credit WHERE order_id = '" . (int) $order_id . "'");
    }

    public function getCredits($customer_id, $start = 0, $limit = 10) {
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 10;
        }

        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer_credit WHERE customer_id = '" . (int) $customer_id . "' ORDER BY date_added DESC LIMIT " . (int) $start . ',' . (int) $limit);

        return $query->rows;
    }

    public function getAllCredits($data = []) {
        $sql = "SELECT CONCAT(c.firstname, ' ', c.lastname) AS name,c.company_name, cgd.*,c.email  FROM " . DB_PREFIX . 'customer c  JOIN ' . DB_PREFIX . 'customer_credit cgd ON (c.customer_id = cgd.customer_id)';

        //echo "<pre>";print_r($sql);die;
        $implode = [];

        if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_company'])) {
            $implode[] = "company_name LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (!empty($data['filter_order_id'])) {
            $implode[] = "order_id = '" . $this->db->escape($data['filter_order_id']) . "'";
        }

        if (!empty($data['filter_type'])) {
            if ('credit' == strtolower($data['filter_type'])) {
                $implode[] = 'amount > 0';
            } else {
                $implode[] = 'amount <= 0';
            }
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(cgd.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if ($implode) {
            $sql .= ' AND ' . implode(' AND ', $implode);
        }

        $sort_data = [
            'name',
            'email',
            'date_added',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY name';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        //echo "<pre>";print_r($sql);die;
        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalWallet($data = []) {
        $sql = 'SELECT COUNT(*) AS total  FROM ' . DB_PREFIX . 'customer c  JOIN ' . DB_PREFIX . 'customer_credit cgd ON (c.customer_id = cgd.customer_id)';

        //$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_credit";

        $implode = [];

        if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_company'])) {
            $implode[] = "company_name LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (!empty($data['filter_order_id'])) {
            $implode[] = "order_id = '" . $this->db->escape($data['filter_order_id']) . "'";
        }

        if (!empty($data['filter_type'])) {
            if ('credit' == strtolower($data['filter_type'])) {
                $implode[] = 'amount > 0';
            } else {
                $implode[] = 'amount <= 0';
            }
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(cgd.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if ($implode) {
            $sql .= ' WHERE ' . implode(' AND ', $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getAllVendorCredits($data = []) {
        $sql = "SELECT CONCAT(c.firstname, ' ', c.lastname) AS name, cgd.*,c.email  FROM " . DB_PREFIX . 'user c  JOIN ' . DB_PREFIX . 'vendor_wallet cgd ON (c.user_id = cgd.vendor_id)';

        //echo "<pre>";print_r($sql);die;
        $implode = [];

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
            if ('credit' == strtolower($data['filter_type'])) {
                $implode[] = 'amount > 0';
            } else {
                $implode[] = 'amount <= 0';
            }
        }

        if ($implode) {
            $sql .= ' AND ' . implode(' AND ', $implode);
        }

        $sort_data = [
            'name',
            'email',
            'date_added',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY name';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        //echo "<pre>";print_r($sql);die;
        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalVendorWallet($data = []) {
        $sql = 'SELECT COUNT(*) AS total  FROM ' . DB_PREFIX . 'user c  JOIN ' . DB_PREFIX . 'vendor_wallet cgd ON (c.user_id = cgd.vendor_id)';

        //$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_credit";

        $implode = [];

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
            if ('credit' == strtolower($data['filter_type'])) {
                $implode[] = 'amount > 0';
            } else {
                $implode[] = 'amount <= 0';
            }
        }

        if ($implode) {
            $sql .= ' WHERE ' . implode(' AND ', $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getAllAdminCredits($data = []) {
        $sql = 'SELECT * FROM ' . DB_PREFIX . 'admin_wallet where id != 0 ';

        //echo "<pre>";print_r($sql);die;
        $implode = [];

        /* if (!empty($data['filter_name'])) {
          $implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
          }

          if (!empty($data['filter_email'])) {
          $implode[] = "c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
          } */

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_order_id'])) {
            $implode[] = "order_id = '" . $this->db->escape($data['filter_order_id']) . "'";
        }

        if (!empty($data['filter_type'])) {
            if ('credit' == strtolower($data['filter_type'])) {
                $implode[] = 'amount > 0';
            } else {
                $implode[] = 'amount <= 0';
            }
        }

        if ($implode) {
            $sql .= ' AND ' . implode(' AND ', $implode);
        }

        $sort_data = [
            'date_added',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY date_added';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        //echo "<pre>";print_r($sql);die;
        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalAdminWallet($data = []) {
        $sql = 'SELECT COUNT(*) AS total  FROM ' . DB_PREFIX . 'admin_wallet';

        //$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_credit";

        $implode = [];

        /* if (!empty($data['filter_name'])) {
          $implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
          }

          if (!empty($data['filter_email'])) {
          $implode[] = "email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
          } */

        if (!empty($data['filter_order_id'])) {
            $implode[] = "order_id = '" . $this->db->escape($data['filter_order_id']) . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_type'])) {
            if ('credit' == strtolower($data['filter_type'])) {
                $implode[] = 'amount > 0';
            } else {
                $implode[] = 'amount <= 0';
            }
        }

        if ($implode) {
            $sql .= ' WHERE ' . implode(' AND ', $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalCredits($customer_id) {
        $query = $this->db->query('SELECT COUNT(*) AS total  FROM ' . DB_PREFIX . "customer_credit WHERE customer_id = '" . (int) $customer_id . "'");

        return $query->row['total'];
    }

    public function getCreditTotal($customer_id) {
        $query = $this->db->query('SELECT SUM(amount) AS total FROM ' . DB_PREFIX . "customer_credit WHERE customer_id = '" . (int) $customer_id . "'");

        return $query->row['total'];
    }

    public function getTotalCreditsByOrderId($order_id) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . "customer_credit WHERE order_id = '" . (int) $order_id . "'");

        return $query->row['total'];
    }

    public function addReward($customer_id, $description = '', $points = '', $order_id = 0) {
        $customer_info = $this->getCustomer($customer_id);

        if ($customer_info) {
            $this->db->query('INSERT INTO ' . DB_PREFIX . "customer_reward SET customer_id = '" . (int) $customer_id . "', order_id = '" . (int) $order_id . "', points = '" . (int) $points . "', description = '" . $this->db->escape($description) . "', date_added = NOW()");

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
        $this->db->query('DELETE FROM ' . DB_PREFIX . "customer_reward WHERE order_id = '" . (int) $order_id . "' AND points > 0");
    }

    public function getRewards($customer_id, $start = 0, $limit = 10) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int) $customer_id . "' ORDER BY date_added DESC LIMIT " . (int) $start . ',' . (int) $limit);

        return $query->rows;
    }

    public function getTotalRewards($customer_id) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int) $customer_id . "'");

        return $query->row['total'];
    }

    public function getReferrals($customer_id, $start = 0, $limit = 10) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer WHERE refree_user_id = '" . (int) $customer_id . "' ORDER BY date_added DESC LIMIT " . (int) $start . ',' . (int) $limit);

        return $query->rows;
    }

    public function getTotalReferrals($customer_id) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . "customer WHERE refree_user_id = '" . (int) $customer_id . "'");

        return $query->row['total'];
    }

    public function getRewardTotal($customer_id) {
        $query = $this->db->query('SELECT SUM(points) AS total FROM ' . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int) $customer_id . "'");

        return $query->row['total'];
    }

    public function getTotalCustomerRewardsByOrderId($order_id) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . "customer_reward WHERE order_id = '" . (int) $order_id . "'");

        return $query->row['total'];
    }

    public function getIps($customer_id) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int) $customer_id . "'");

        return $query->rows;
    }

    public function getTotalIps($customer_id) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int) $customer_id . "'");

        return $query->row['total'];
    }

    public function getTotalCustomersByIp($ip) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . "customer_ip WHERE ip = '" . $this->db->escape($ip) . "'");

        return $query->row['total'];
    }

    public function addBanIp($ip) {
        $this->db->query('INSERT INTO `' . DB_PREFIX . "customer_ban_ip` SET `ip` = '" . $this->db->escape($ip) . "'");
    }

    public function removeBanIp($ip) {
        $this->db->query('DELETE FROM `' . DB_PREFIX . "customer_ban_ip` WHERE `ip` = '" . $this->db->escape($ip) . "'");
    }

    public function getTotalBanIpsByIp($ip) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `' . DB_PREFIX . "customer_ban_ip` WHERE `ip` = '" . $this->db->escape($ip) . "'");

        return $query->row['total'];
    }

    public function getTotalLoginAttempts($email) {
        $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . "customer_login` WHERE `email` = '" . $this->db->escape($email) . "'");

        return $query->row;
    }

    public function deleteLoginAttempts($email) {
        $this->db->query('DELETE FROM `' . DB_PREFIX . "customer_login` WHERE `email` = '" . $this->db->escape($email) . "'");
    }

    public function sendCustomerRegisterMail($data) {
        if (isset($data['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($data['customer_group_id'], $this->config->get('config_customer_group_display'))) {
            $customer_group_id = $data['customer_group_id'];
        } else {
            $customer_group_id = $this->config->get('config_customer_group_id');
        }

        $this->load->model('sale/customer_group');

        $customer_group_info = $this->model_sale_customer_group->getCustomerGroup($customer_group_id);

        //Get Email Template
        if (!$customer_group_info['approval']) {
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
        } else {
            //Customer Registration Approve
            // $subject = $this->emailtemplate->getSubject('Customer', 'customer_2', $data);
            // $message = $this->emailtemplate->getMessage('Customer', 'customer_2', $data);
            $sms_message = $this->emailtemplate->getSmsMessage('Customer', 'customer_2', $data);
        }

        // send message here

        if ($this->emailtemplate->getSmsEnabled('Customer', 'customer_1')) {
            // echo "string";
            // $sms = new sms();
            $ret = $this->emailtemplate->sendmessage($data['telephone'], $sms_message);
            //echo 'after';
            //print_r($ret);
        }

        // Send to main admin email if new account email is enabled
        if ($this->config->get('config_email') != null && $this->config->get('config_email') != "") {
            //echo "<pre>";print_r($this->config->get('config_email'));die;
            $mail = new Mail($data['email']);
            $mail->setTo($this->config->get('config_email'));
            $mail->send();

            $emails = explode(',', $this->config->get('config_alert_emails'));

            foreach ($emails as $email) {
                if (strlen($email) > 5 && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $mail->setTo($email);
                    $mail->send();
                }
            }
        }
    }

    public function getUsers($data = []) {
        $sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name FROM " . DB_PREFIX . 'user c WHERE c.user_id !=  0';

        $implode = [];

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
            $sql .= ' AND ' . implode(' AND ', $implode);
        }

        $sort_data = [
            'name',
            'c.email',
            'c.date_added',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY name';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
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

        $this->db->query("INSERT INTO lists SET app = 1, userID = 1, name = 'list" . rand() . '-' . date('H:i:s') . "'");

        $listId = $this->db->getLastId();

        foreach ($emails as $email) {
            $this->db->query("INSERT INTO subscribers SET email = '" . $email . "', userID = 1, list = " . $listId);
        }

        return $listId;
    }

    public function getCustomerContacts($customer_id, $start, $limit) {

        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 10;
        }



        $contacts = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer_contact c WHERE c.customer_id = '" . (int) $customer_id . "'ORDER BY contact_id DESC LIMIT " . (int) $start . ',' . (int) $limit);
        //  echo "<pre>";print_r($contacts); die;


        return $contacts->rows;
    }

    public function getTotalContacts($customer_id) {
        $contacts = $this->db->query('SELECT count(*) as total FROM ' . DB_PREFIX . "customer_contact c WHERE c.customer_id = '" . (int) $customer_id . "'");

        // echo "<pre>";print_r('SELECT count(*) as total FROM ' . DB_PREFIX . "customer_contact c WHERE c.customer_id = '" . (int) $customer_id . "'"); die;
        return $contacts->row['total'];
    }

    public function addEditContact($customer_id, $firstname, $lastname = '', $email, $phone, $customer_contact_send, $contact_id = 0) {
        $customer_info = $this->getCustomer($customer_id);

        $log = new Log('error.log');

        if ($customer_info) {

            $flag = 0;

            // echo "<pre>";print_r($contact_id); die;

            if ($customer_contact_send == "on") {
                $flag = 1;
            }
            if ($contact_id == 0) {
                $this->db->query('INSERT INTO ' . DB_PREFIX . "customer_contact SET  firstname = '" . $firstname . "', lastname = '" . $lastname . "', email = '" . $email . "', telephone = '" . $phone . "', send = '" . $flag . "', customer_id = '" . (int) $customer_id . "', created_date = NOW()");
                $contact_id = $this->db->getLastId();
            } else {
                $this->db->query('UPDATE ' . DB_PREFIX . "customer_contact SET   firstname = '" . $firstname . "', lastname = '" . $lastname . "',  email = '" . $email . "', telephone = '" . $phone . "', send = '" . $flag . "', customer_id = '" . $customer_id . "',  modified_date = NOW() WHERE contact_id = '" . (int) $contact_id . "'");
                // echo "<pre>";print_r('UPDATE ' . DB_PREFIX . "customer_contact SET   firstname = '" . $firstname . "', lastname = '" . $lastname . "',  email = '" . $email . "', telephone = '" . $phone . "', send = '" . $flag . "', customer_id = '" . $customer_id . "',  modified_date = NOW() WHERE contact_id = '" . (int) $contact_id . "'"); die;
            }

            return $contact_id;
        }
    }

    public function getCustomerContact($contact_id) {
        //    echo "<pre>";print_r('SELECT * FROM ' . DB_PREFIX . "customer_contact c WHERE c.contact_id = '" . (int) $contact_id . "'"'); die;


        $contact = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer_contact c WHERE c.contact_id = '" . (int) $contact_id . "'");

        return $contact->row;
    }

    public function deleteContact($contact_id) {


        $this->db->query('DELETE FROM `' . DB_PREFIX . "customer_contact` WHERE `contact_id` = '" . (int) $contact_id . "'");
    }

    public function editCustomerConfiguration($customer_id, $data) {

        if (isset($data['customer_category']) && $data['customer_category'] != NULL && $data['customer_category'] != 'undefined') {
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET customer_discount_category = NULL, customer_category = '" . $data['customer_category'] . "', modified_by = '" . $this->user->getId() . "', modifier_role = '" . $this->user->getGroupName() . "', date_modified = NOW() WHERE customer_id = '" . (int) $customer_id . "'");
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET customer_discount_category = NULL, customer_category = '" . $data['customer_category'] . "', modified_by = '" . $this->user->getId() . "', modifier_role = '" . $this->user->getGroupName() . "', date_modified = NOW() WHERE parent = '" . (int) $customer_id . "'");
        }
        if (isset($data['customer_discount_category']) && $data['customer_discount_category'] != NULL && $data['customer_discount_category'] != 'undefined') {
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET customer_category = NULL, customer_discount_category = '" . $data['customer_discount_category'] . "', modified_by = '" . $this->user->getId() . "', modifier_role = '" . $this->user->getGroupName() . "', date_modified = NOW() WHERE customer_id = '" . (int) $customer_id . "'");
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET customer_category = NULL, customer_discount_category = '" . $data['customer_discount_category'] . "', modified_by = '" . $this->user->getId() . "', modifier_role = '" . $this->user->getGroupName() . "', date_modified = NOW() WHERE parent = '" . (int) $customer_id . "'");
        }
        if (isset($data['account_manager']) && $data['account_manager'] != NULL && $data['customer_category'] != 'undefined') {
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET account_manager_id = '" . $data['account_manager'] . "', modified_by = '" . $this->user->getId() . "', modifier_role = '" . $this->user->getGroupName() . "', date_modified = NOW() WHERE customer_id = '" . (int) $customer_id . "'");
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET account_manager_id = '" . $data['account_manager'] . "', modified_by = '" . $this->user->getId() . "', modifier_role = '" . $this->user->getGroupName() . "', date_modified = NOW() WHERE parent = '" . (int) $customer_id . "'");
        }
        if (isset($data['customer_experience']) && $data['customer_experience'] != NULL && $data['customer_category'] != 'undefined') {
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET customer_experience_id = '" . $data['customer_experience'] . "',  modified_by = '" . $this->user->getId() . "', modifier_role = '" . $this->user->getGroupName() . "', date_modified = NOW() WHERE customer_id = '" . (int) $customer_id . "'");
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET customer_experience_id = '" . $data['customer_experience'] . "',  modified_by = '" . $this->user->getId() . "', modifier_role = '" . $this->user->getGroupName() . "', date_modified = NOW() WHERE parent = '" . (int) $customer_id . "'");
        }
        if (isset($data['payment_terms']) && $data['payment_terms'] != NULL && $data['customer_category'] != 'undefined') {
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET modified_by = '" . $this->user->getId() . "', modifier_role = '" . $this->user->getGroupName() . "', payment_terms = '" . $data['payment_terms'] . "', date_modified = NOW() WHERE customer_id = '" . (int) $customer_id . "'");
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET modified_by = '" . $this->user->getId() . "', modifier_role = '" . $this->user->getGroupName() . "', payment_terms = '" . $data['payment_terms'] . "', date_modified = NOW() WHERE parent = '" . (int) $customer_id . "'");
        }
        if (isset($data['statement_duration']) && $data['statement_duration'] != NULL && $data['customer_category'] != 'undefined') {
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET modified_by = '" . $this->user->getId() . "', modifier_role = '" . $this->user->getGroupName() . "', statement_duration = '" . $data['statement_duration'] . "', date_modified = NOW() WHERE customer_id = '" . (int) $customer_id . "'");
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET modified_by = '" . $this->user->getId() . "', modifier_role = '" . $this->user->getGroupName() . "', statement_duration = '" . $data['statement_duration'] . "', date_modified = NOW() WHERE parent = '" . (int) $customer_id . "'");
        }
    }

    public function editCustomerConfiguration_new($customer_id, $data) {
        $apply_discount_oncategoryprice = $this->config->get('config_apply_discount_on_categoryprice')??0;

        if($apply_discount_oncategoryprice==1)
        {
            if (isset($data['customer_category']) && $data['customer_category'] != NULL && $data['customer_category'] != 'undefined') {
                $this->db->query('UPDATE ' . DB_PREFIX . "customer SET customer_category = '" . $data['customer_category'] . "', modified_by = '" . $this->user->getId() . "', modifier_role = '" . $this->user->getGroupName() . "', date_modified = NOW() WHERE customer_id = '" . (int) $customer_id . "'");
                $this->db->query('UPDATE ' . DB_PREFIX . "customer SET customer_category = '" . $data['customer_category'] . "', modified_by = '" . $this->user->getId() . "', modifier_role = '" . $this->user->getGroupName() . "', date_modified = NOW() WHERE parent = '" . (int) $customer_id . "'");
            }
            if (isset($data['customer_discount_category']) && $data['customer_discount_category'] != NULL && $data['customer_discount_category'] != 'undefined') {
                $this->db->query('UPDATE ' . DB_PREFIX . "customer SET  customer_discount_category = '" . $data['customer_discount_category'] . "', modified_by = '" . $this->user->getId() . "', modifier_role = '" . $this->user->getGroupName() . "', date_modified = NOW() WHERE customer_id = '" . (int) $customer_id . "'");
                $this->db->query('UPDATE ' . DB_PREFIX . "customer SET  customer_discount_category = '" . $data['customer_discount_category'] . "', modified_by = '" . $this->user->getId() . "', modifier_role = '" . $this->user->getGroupName() . "', date_modified = NOW() WHERE parent = '" . (int) $customer_id . "'");
            }
        }

        else
        {

            if (isset($data['customer_category']) && $data['customer_category'] != NULL && $data['customer_category'] != 'undefined') {
                $this->db->query('UPDATE ' . DB_PREFIX . "customer SET customer_discount_category = NULL, customer_category = '" . $data['customer_category'] . "', modified_by = '" . $this->user->getId() . "', modifier_role = '" . $this->user->getGroupName() . "', date_modified = NOW() WHERE customer_id = '" . (int) $customer_id . "'");
                $this->db->query('UPDATE ' . DB_PREFIX . "customer SET customer_discount_category = NULL, customer_category = '" . $data['customer_category'] . "', modified_by = '" . $this->user->getId() . "', modifier_role = '" . $this->user->getGroupName() . "', date_modified = NOW() WHERE parent = '" . (int) $customer_id . "'");
            }
            if (isset($data['customer_discount_category']) && $data['customer_discount_category'] != NULL && $data['customer_discount_category'] != 'undefined') {
                $this->db->query('UPDATE ' . DB_PREFIX . "customer SET customer_category = NULL, customer_discount_category = '" . $data['customer_discount_category'] . "', modified_by = '" . $this->user->getId() . "', modifier_role = '" . $this->user->getGroupName() . "', date_modified = NOW() WHERE customer_id = '" . (int) $customer_id . "'");
                $this->db->query('UPDATE ' . DB_PREFIX . "customer SET customer_category = NULL, customer_discount_category = '" . $data['customer_discount_category'] . "', modified_by = '" . $this->user->getId() . "', modifier_role = '" . $this->user->getGroupName() . "', date_modified = NOW() WHERE parent = '" . (int) $customer_id . "'");
            }

        }
        if (isset($data['account_manager']) && $data['account_manager'] != NULL && $data['customer_category'] != 'undefined') {
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET account_manager_id = '" . $data['account_manager'] . "', modified_by = '" . $this->user->getId() . "', modifier_role = '" . $this->user->getGroupName() . "', date_modified = NOW() WHERE customer_id = '" . (int) $customer_id . "'");
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET account_manager_id = '" . $data['account_manager'] . "', modified_by = '" . $this->user->getId() . "', modifier_role = '" . $this->user->getGroupName() . "', date_modified = NOW() WHERE parent = '" . (int) $customer_id . "'");
        }
        if (isset($data['customer_experience']) && $data['customer_experience'] != NULL && $data['customer_category'] != 'undefined') {
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET customer_experience_id = '" . $data['customer_experience'] . "',  modified_by = '" . $this->user->getId() . "', modifier_role = '" . $this->user->getGroupName() . "', date_modified = NOW() WHERE customer_id = '" . (int) $customer_id . "'");
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET customer_experience_id = '" . $data['customer_experience'] . "',  modified_by = '" . $this->user->getId() . "', modifier_role = '" . $this->user->getGroupName() . "', date_modified = NOW() WHERE parent = '" . (int) $customer_id . "'");
        }
        if (isset($data['payment_terms']) && $data['payment_terms'] != NULL && $data['customer_category'] != 'undefined') {
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET modified_by = '" . $this->user->getId() . "', modifier_role = '" . $this->user->getGroupName() . "', payment_terms = '" . $data['payment_terms'] . "', date_modified = NOW() WHERE customer_id = '" . (int) $customer_id . "'");
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET modified_by = '" . $this->user->getId() . "', modifier_role = '" . $this->user->getGroupName() . "', payment_terms = '" . $data['payment_terms'] . "', date_modified = NOW() WHERE parent = '" . (int) $customer_id . "'");
        }
        if (isset($data['statement_duration']) && $data['statement_duration'] != NULL && $data['customer_category'] != 'undefined') {
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET modified_by = '" . $this->user->getId() . "', modifier_role = '" . $this->user->getGroupName() . "', statement_duration = '" . $data['statement_duration'] . "', date_modified = NOW() WHERE customer_id = '" . (int) $customer_id . "'");
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET modified_by = '" . $this->user->getId() . "', modifier_role = '" . $this->user->getGroupName() . "', statement_duration = '" . $data['statement_duration'] . "', date_modified = NOW() WHERE parent = '" . (int) $customer_id . "'");
        }
    }

    public function getCutomerFromOrder($order_id) {
        $query = $this->db->query('SELECT customer_id FROM ' . DB_PREFIX . "order WHERE order_id = '" . (int) $order_id . "'");
        // echo '<pre>';print_r($query->row['customer_id']);exit;
        return $query->row['customer_id'];
    }

    public function addOnlyCredit($customer_id, $description = '', $amount = '', $order_id = 0, $notify = 0) {
        $log = new Log('error.log');
        try {
            $customer_info = $this->getCustomer($customer_id);
            if ($customer_info) {
                $this->db->query('INSERT INTO ' . DB_PREFIX . "customer_credit SET customer_id = '" . (int) $customer_id . "', order_id = '" . (int) $order_id . "', description = '" . $this->db->escape($description) . "', amount = '" . (float) $amount . "', date_added = NOW()");
            }

            if ($notify == 1) {//send notification to customer
                $this->load->language('mail/customer');
                $store_name = $this->config->get('config_name');

                $data = $customer_info;
                $data['amount'] = $amount;

                $data['transfer_type'] = 'debited from';
                if ($amount >= 0) {
                    $data['transfer_type'] = 'credited in';
                }
                if ($amount < 0) {
                    $data['amount'] = -$amount;
                }

                $data['amount'] = $this->currency->format($data['amount']);
                if ($customer_info['email_notification'] == 1 && $this->emailtemplate->getEmailEnabled('Customer', 'customer_6')) {

                    $subject = $this->emailtemplate->getSubject('Customer', 'customer_6', $data);
                    $message = $this->emailtemplate->getMessage('Customer', 'customer_6', $data);
                    $mail = new Mail($this->config->get('config_mail'));
                    $mail->setTo($customer_info['email']);
                    $mail->setFrom($this->config->get('config_from_email'));
                    $mail->setSender($store_name);
                    $mail->setSubject($subject, ENT_QUOTES);
                    $mail->setHTML($message, ENT_QUOTES);
                    $mail->send();
                }

                if ($customer_info['sms_notification'] == 1 && $this->emailtemplate->getSmsEnabled('Customer', 'customer_6')) {
                    $ret = $this->emailtemplate->sendmessage($order_info['telephone'], $sms_message);
                }

                if ($customer_info['mobile_notification'] == 1 && $this->emailtemplate->getNotificationEnabled('Customer', 'customer_6')) {
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
            }//notify end
        } catch (exception $ex) {
            
        }
    }

    public function addOnlyCreditNew($customer_id, $description = '', $amount = '', $order_id = 0, $notify = 0) {
        $log = new Log('error.log');
        try {
            $customer_info = $this->getCustomer($customer_id);
            if ($customer_info) {
                $this->db->query('INSERT INTO ' . DB_PREFIX . "customer_credit SET customer_id = '" . (int) $customer_id . "', order_id = '" . (int) $order_id . "', description = '" . $this->db->escape($description) . "', amount = '" . (float) $amount . "', date_added = NOW()");
               $credit_id= $this->db->getLastId();
            }

            if ($notify == 1) {//send notification to customer
                $this->load->language('mail/customer');
                $store_name = $this->config->get('config_name');

                $data = $customer_info;
                $data['amount'] = $amount;

                $data['transfer_type'] = 'debited from';
                if ($amount >= 0) {
                    $data['transfer_type'] = 'credited in';
                }
                if ($amount < 0) {
                    $data['amount'] = -$amount;
                }

                $data['amount'] = $this->currency->format($data['amount']);
                if ($customer_info['email_notification'] == 1 && $this->emailtemplate->getEmailEnabled('Customer', 'customer_6')) {

                    $subject = $this->emailtemplate->getSubject('Customer', 'customer_6', $data);
                    $message = $this->emailtemplate->getMessage('Customer', 'customer_6', $data);
                    $mail = new Mail($this->config->get('config_mail'));
                    $mail->setTo($customer_info['email']);
                    $mail->setFrom($this->config->get('config_from_email'));
                    $mail->setSender($store_name);
                    $mail->setSubject($subject, ENT_QUOTES);
                    $mail->setHTML($message, ENT_QUOTES);
                    $mail->send();
                }

                if ($customer_info['sms_notification'] == 1 && $this->emailtemplate->getSmsEnabled('Customer', 'customer_6')) {
                    $ret = $this->emailtemplate->sendmessage($order_info['telephone'], $sms_message);
                }

                if ($customer_info['mobile_notification'] == 1 && $this->emailtemplate->getNotificationEnabled('Customer', 'customer_6')) {
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
            }//notify end
            return $credit_id;
        } catch (exception $ex) {
            
        }
    }
    public function check_customer_previous_password($customer_id, $password) {
        $user_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "password_resets WHERE customer_id = '" . $customer_id . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($password) . "'))))) OR password = '" . $this->db->escape(md5($password)) . "')");
        return $user_query->num_rows;
    }

    public function check_customer_current_password($customer_id, $password) {
        $user_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . $customer_id . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($password) . "'))))) OR password = '" . $this->db->escape(md5($password)) . "')");
        return $user_query->num_rows;
    }

    public function savepassword($customer_id, $password) {
        /* TIME ZONE ISSUE */
        $tz = (new DateTime('now', new DateTimeZone('Africa/Nairobi')))->format('P');
        $this->db->query("SET time_zone='$tz';");
        /* TIME ZONE ISSUE */
        $this->db->query('INSERT INTO ' . DB_PREFIX . "password_resets SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "', customer_id = '" . $customer_id . "', created_at = NOW()");
    }

    public function deleteoldpassword($customer_id) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "password_resets WHERE customer_id = '" . (int) $customer_id . "'");
        $old_passwords = $query->rows;
        $log = new Log('error.log');
        $log->write($old_passwords);
        if (count($old_passwords) > 3) {
            $remove_count = count($old_passwords) - 3;
            if ($remove_count > 0) {
                for ($x = 0; $x < $remove_count; $x++) {
                    $this->db->query('DELETE FROM ' . DB_PREFIX . "password_resets WHERE customer_id = '" . $customer_id . "' and id='" . $old_passwords[$x]['id'] . "'");
                }
            }
        }
    }

    public function passwordexpired($customer_id) {
        $diffInMonths = 0;
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "password_resets WHERE customer_id = '" . (int) $customer_id . "'");
        $old_passwords = $query->rows;
        if (count($old_passwords) > 0) {
            $record = end($old_passwords);
            $old_password_created_at = $record['created_at'];
            date_default_timezone_set('Africa/Nairobi');
            $current_password_created_at = date("Y-m-d H:i:s");

            $d1 = new DateTime($old_password_created_at);
            $d2 = new DateTime($current_password_created_at);
            $interval = $d1->diff($d2);
            $diffInMonths = $interval->m;
        }
        return $diffInMonths;
    }

    public function checknewpasswordsetted($customer_id) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "password_resets WHERE customer_id = '" . (int) $customer_id . "'");
        return $query->num_rows;
    }

    public function getParentCutomerFromOrder($order_id) {
        $query = $this->db->query('SELECT o.customer_id,c.parent,c.company_name  FROM ' . DB_PREFIX . 'order o join ' . DB_PREFIX . "customer c on o.customer_id =c.customer_id  WHERE order_id = '" . (int) $order_id . "'");
        // echo '<pre>';print_r('SELECT o.customer_id,c.parent,c.company_name  FROM ' . DB_PREFIX . 'order o join ' . DB_PREFIX . "customer c on o.customer_id =c.customer_id  WHERE order_id = '" . (int) $order_id . "'");exit;
        // echo '<pre>';print_r($query->row['parent']);die;

        if ($query->row['parent'] == null || $query->row['parent'] == 0) {
            $id = $query->row['customer_id'];
        } else {

            $parent = $query->row['parent'];
            $query1 = $this->db->query('SELECT customer_id,company_name FROM ' . DB_PREFIX . "customer WHERE parent = '" . (int) $parent . "'");
            if ($query1->row['company_name'] == $query->row['company_name']) {
                $id = $query1->row['customer_id'];
            } else {
                $id = $query->row['customer_id'];
            }
        }
        // echo '<pre>';print_r($id);die;
        return $id;
    }

    public function getCustomerActivities($customer_id, $start, $limit) {

        $sql = "SELECT ca.activity_id, ca.customer_id, ca.key, ca.data, ca.ip, ca.date_added ,ca.order_id FROM " . DB_PREFIX . 'customer_activity ca ';

        $implode = [];

        if (!empty($customer_id)) {
            $implode[] = "ca.customer_id =" . $customer_id;
        }

        if ($implode) {
            $sql .= ' WHERE ' . implode(' AND ', $implode);
        }

        $sql .= ' ORDER BY ca.date_added DESC';

        if (isset($start) || isset($limit)) {
            if ($start < 0) {
                $start = 0;
            }

            if ($limit < 1) {
                $limit = 20;
            }

            $sql .= ' LIMIT ' . (int) $start . ',' . (int) $limit;
        }
        // echo "<pre>";print_r($sql); 


        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalCustomerActivities($customer_id) {


        $query = $this->db->query('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . "customer_activity ca WHERE ca.customer_id = '" . (int) $customer_id . "'");

        return $query->row['total'];

        // echo "<pre>";print_r($sql); 
    }

    public function getUserActivitiesofCustomer($customer_id, $start, $limit) {

        $sql1 = ' SELECT ca.activity_id, ca.user_id,c.firstname,c.lastname, ca.key, ca.data, ca.ip, ca.date_added,ca.order_id FROM ' . DB_PREFIX . 'user_activity ca LEFT JOIN ' . DB_PREFIX . 'user c ON (ca.user_id = c.user_id)  LEFT OUTER JOIN ' . DB_PREFIX . 'customer cust ON (cust.customer_id = ca.customer_id) ';
        $sql2 = ' SELECT ca.activity_id, ca.user_id,c.firstname,c.lastname, ca.key, ca.data, ca.ip, ca.date_added,ca.order_id  FROM ' . DB_PREFIX . 'user_activity ca LEFT JOIN ' . DB_PREFIX . 'user c ON (ca.user_id = c.user_id)  LEFT OUTER JOIN ' . DB_PREFIX . 'order o ON (o.order_id = ca.order_id) ';

        $implode = [];

        if (!empty($customer_id)) {
            $implode[] = "ca.customer_id =" . $customer_id;
        }

        if ($implode) {
            $sql1 .= ' WHERE ' . implode(' AND ', $implode);
        }

        $implode = [];

        if (!empty($customer_id)) {
            $implode[] = "o.customer_id =" . $customer_id;
        }

        if ($implode) {
            $sql2 .= ' WHERE ' . implode(' AND ', $implode);
        }

        // $sql = 'select * from ( '.$sql1.' union '.$sql2.') as t ORDER BY date_added DESC';
        $sql = $sql1;
        if (isset($start) || isset($limit)) {
            if ($start < 0) {
                $start = 0;
            }

            if ($limit < 1) {
                $limit = 20;
            }

            $sql .= ' LIMIT ' . (int) $start . ',' . (int) $limit;
        }
        // echo "<pre>";print_r($sql); 


        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalUserActivitiesofCustomer($customer_id) {


        $query = $this->db->query('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . 'user_activity ca LEFT JOIN ' . DB_PREFIX . 'user c ON (ca.user_id = c.user_id)  LEFT OUTER JOIN ' . DB_PREFIX . 'customer cust ON (cust.customer_id = ca.customer_id)  WHERE ca.customer_id = ' . (int) $customer_id);

        // echo "<pre>";print_r('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . 'user_activity ca LEFT JOIN ' . DB_PREFIX . 'user c ON (ca.user_id = c.user_id)  LEFT OUTER JOIN ' . DB_PREFIX . 'customer cust ON (cust.customer_id = ca.customer_id)  WHERE ca.customer_id = ' . (int) $customer_id ); 

        return $query->row['total'];
    }

    public function editCustomerPassword($customer_id, $data) {

        //   echo "<pre>";print_r($data);die;


        if ($data['password'] && 'default' != $data['password']) {
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', tempPassword = '" . (int) 1 . "' WHERE customer_id = '" . (int) $customer_id . "'");
        }


        $this->savepassword($customer_id, $data['password']);
        $this->deleteoldpassword($customer_id);
    }

    public function getOrdersFilterNew($data = []) {
        $sql = "SELECT c.customer_id, o.order_id FROM " . DB_PREFIX . 'order o JOIN ' . DB_PREFIX . "customer c ON (c.customer_id = o.customer_id)";

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '" . (int) $data['filter_order_id'] . "'";
        }

        if (!empty($data['filter_delivery_date'])) {
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($data['filter_delivery_date']) . "')";
        }

        if (!empty($data['filter_delivery_time_slot'])) {
            $sql .= " AND o.delivery_timeslot = '" . $this->db->escape($data['filter_delivery_time_slot']) . "'";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }
        /* $log = new Log('error.log');
          $log->write($sql); */
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function addCustomerCategories($customer_id, $customer_categories) {
        $this->db->query('DELETE FROM ' . DB_PREFIX . "category_to_customer WHERE customer_id = '" . (int) $customer_id . "'");
        if ($customer_categories != NULL) {
            $customer_categories_array = explode(',', $customer_categories);
            foreach ($customer_categories_array as $customer_category) {
                $this->db->query('INSERT INTO ' . DB_PREFIX . "category_to_customer SET customer_id = '" . (int) $customer_id . "', category_id = '" . (int) $customer_category . "', max_discount = '" . (int) 0.00 . "'");
            }
        }
    }

    public function getCustomerCategories($customer_id) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "category_to_customer WHERE customer_id = '" . (int) $customer_id . "'");
        return $query->rows;
    }

    public function updateCustomerPriceCategory($price_category, $customer_id) {
        $query = 'UPDATE ' . DB_PREFIX . "customer SET customer_category = '" . $this->db->escape($price_category) . "' WHERE customer_id ='" . (int) $customer_id . "'";
        $log = new Log('error.log');
        $log->write($query);
        $res = $this->db->query($query);
        if ($res == 1) {
            $this->updateSubCustomerPriceCategory($price_category, $customer_id);
        }
        return $res;
    }

    public function updateCustomerPriceDiscount($price_category, $customer_id) {
        $query = 'UPDATE ' . DB_PREFIX . "customer SET customer_discount_category = '" . $this->db->escape($price_category) . "' WHERE customer_id ='" . (int) $customer_id . "'";
        $log = new Log('error.log');
        $log->write($query);
        $res = $this->db->query($query);

        $query2 = 'UPDATE ' . DB_PREFIX . "customer SET customer_category = NULL WHERE customer_id ='" . (int) $customer_id . "'";
        $log->write($query2);
        $res2 = $this->db->query($query2);

        if ($res == 1) {
            $this->updateSubCustomerDiscountCategory($price_category, $customer_id);
        }
        return $res;
    }

    public function updateSubCustomerPriceCategory($price_category, $customer_id) {
        $query = 'UPDATE ' . DB_PREFIX . "customer SET customer_category = '" . $this->db->escape($price_category) . "' WHERE parent ='" . (int) $customer_id . "'";
        $log = new Log('error.log');
        $log->write($query);
        $res = $this->db->query($query);
        return $res;
    }

    public function updateSubCustomerDiscountCategory($price_category, $customer_id) {
        $query = 'UPDATE ' . DB_PREFIX . "customer SET customer_discount_category = '" . $this->db->escape($price_category) . "' WHERE parent ='" . (int) $customer_id . "'";
        $log = new Log('error.log');
        $log->write($query);
        $res = $this->db->query($query);

        $query2 = 'UPDATE ' . DB_PREFIX . "customer SET customer_category = NULL WHERE parent ='" . (int) $customer_id . "'";
        $log->write($query2);
        $res2 = $this->db->query($query2);

        return $res;
    }

}
