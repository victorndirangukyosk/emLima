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

    public function SaveCustomerLoans($customer_id, $order_id, $loan_id, $loan_type) {
        $log = new Log('error.log');
        $log->write('loan_type');
        $log->write($loan_type);
        $log->write('loan_type');
        $this->db->query('INSERT INTO ' . DB_PREFIX . "customer_pezesha_loans SET customer_id = '" . (int) $customer_id . "', loan_id = '" . (int) $loan_id . "', order_id = '" . (int) $order_id . "', loan_type = '" . $loan_type . "', created_at = NOW()");
    }

    public function UpdateCustomerLoans($customer_id, $order_id, $loan_id, $loan_type, $amount) {
        $log = new Log('error.log');
        $log->write('loan_type');
        $log->write($loan_type);
        $log->write('loan_type');
        $this->db->query('UPDATE ' . DB_PREFIX . "customer_pezesha_loans SET loan_id = '" . (int) $loan_id . "', amount = '" . $amount . "', loan_type = '" . $loan_type . "', updated_at = NOW() WHERE customer_id = '" . (int) $customer_id . "' AND order_id = '" . (int) $order_id . "'");
    }

    public function getCustomerPezeshaLoan($order_id, $customer_id) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer_pezesha_loans WHERE customer_id = '" . (int) $customer_id . "' AND order_id = '" . (int) $order_id . "'");
        return $query->row;
    }

}
