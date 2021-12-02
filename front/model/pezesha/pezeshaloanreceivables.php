<?php

class ModelPezeshaPezeshaloanreceivables extends Model {

    public function loanmpesadetails($data) {
        $this->db->query('INSERT INTO ' . DB_PREFIX . "pezesha_loan_recceivables SET order_id = '" . (int) $data['order'] . "', loan_type = '" . $this->db->escape($data['type']) . "', merchant_id = '" . $data['merchant_id'] . "', pezesha_id = '" . $data['pezesha_id'] . "', loan_id = '" . $data['loan_id'] . "', amount = '" . $data['amount'] . "', account = '" . $data['account'] . "', mpesa_reference = '" . $this->db->escape($data['mpesa_reference']) . "', transaction_date = '" . $this->db->escape($data['transaction_date']) . "'");
        return $this->db->getLastId();
    }

    public function findPezeshaLoanById($order_id) {
        $pezesha = $this->db->query('SELECT * FROM ' . DB_PREFIX . "order WHERE order_id = '" . (int) $order_id . "'");
        return $pezesha->row;
    }

    public function getPezeshaReceivables() {
        $yesterday = date('Y-m-d', strtotime("-1 days"));
        $yesterday = date('Y-m-d');
        $pezesha_receivables = $this->db->query('SELECT plr.id, plr.order_id, plr.loan_type, plr.merchant_id, plr.pezesha_id, plr.loan_id, plr.amount, plr.account, plr.mpesa_reference, plr.transaction_date, plr.created_at FROM ' . DB_PREFIX . "pezesha_loan_recceivables plr INNER JOIN " . DB_PREFIX . "order o ON o.order_id = plr.order_id WHERE DATE(plr.created_at) = '" . $yesterday . "'");
        return $pezesha_receivables->rows;
    }

}
