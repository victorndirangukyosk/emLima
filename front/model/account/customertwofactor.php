<?php

class ModelAccountCustomerTwoFactor extends Model {

    public function addupdateCustomerTwoFactor($secret_code, $onetime_password, $customer_id) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer_two_factor WHERE customer_id = '" . $customer_id . "'");
        if ($query->num_rows > 0) {
            $login_attempts_count = $query->row['login_attempts_count'] + 1;
            $sql = 'UPDATE  ' . DB_PREFIX . "customer_two_factor SET secret_code = '" . $secret_code . "', onetime_password = '" . $onetime_password . "' , login_attempts_count = '" . $login_attempts_count . "', created_at = NOW() WHERE customer_id = '" . $customer_id . "'";
        } else {
            $sql = 'INSERT INTO ' . DB_PREFIX . "customer_two_factor SET customer_id = '" . $customer_id . "', secret_code = '" . $secret_code . "', onetime_password = '" . $onetime_password . "', login_attempts_count = '1' , created_at = NOW()";
        }
        $this->db->query($sql);
    }

    public function getCustomerTwoFactor($secret_code, $onetime_password, $customer_id) {
        $customer_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer_two_factor WHERE customer_id = '" . $this->db->escape($customer_id) . "' AND secret_code = '" . $secret_code . "' AND onetime_password = '" . $onetime_password . "'");
        return $customer_query->num_rows;
    }

}
