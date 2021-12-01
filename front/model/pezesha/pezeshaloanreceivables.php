<?php

class ModelPezeshaPezeshaloanreceivables extends Model {

    public function loanmpesadetails($data) {
        $this->db->query('INSERT INTO ' . DB_PREFIX . "pezesha_loan_recceivables SET order_id = '" . (int) $data['order'] . "', loan_type = '" . $this->db->escape($data['type']) . "', merchant_id = '" . $data['merchant_id'] . "', pezesha_id = '" . $data['pezesha_id'] . "', loan_id = '" . $data['loan_id'] . "', amount = '" . $data['amount'] . "', account = '" . $data['account'] . "', mpesa_reference = '" . $this->db->escape($data['mpesa_reference']) . "', transaction_date = '" . $this->db->escape($data['transaction_date']) . "'");
        return $this->db->getLastId();
    }

}
