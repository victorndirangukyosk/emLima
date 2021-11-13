<?php

class ModelPezeshaPezesha extends Model {

    public function addCustomer($data) {
        $this->db->query('INSERT INTO ' . DB_PREFIX . "pezesha_customers SET customer_id = '" . (int) $data['customer_id'] . "', customer_uuid = '" . $this->db->escape($data['customer_uuid']) . "', pezesha_customer_id = '" . $this->db->escape($data['pezesha_customer_id']) . "', created_at = NOW()");
        $customer_id = $this->db->getLastId();
        return $customer_id;
    }

    public function getCustomer($customer_id) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "pezesha_customers WHERE customer_id = '" . (int) $customer_id . "'");
        return $query->row;
    }

}
