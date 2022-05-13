<?php

class ModelPezeshaPezeshaloanreceivables extends Model {

    public function loanmpesadetails($data) {
        $pezesha = $this->db->query('SELECT * FROM ' . DB_PREFIX . "order WHERE order_id = '" . (int) $data['order'] . "'");
        $pezesha_loan_details = $pezesha->row;

        $sql = 'INSERT into ' . DB_PREFIX . "order_transaction_id SET order_id = '" . $data['order'] . "', transaction_id = '" . $this->db->escape($data['mpesa_reference']) . "', customer_id = '" . $pezesha_loan_details['customer_id'] . "', created_at = NOW()";
        $query = $this->db->query($sql);

        $this->db->query('UPDATE `' . DB_PREFIX . "order` SET payment_method = 'Pezesha', payment_code = 'pezesha', paid = 'Y', date_modified = NOW() WHERE order_id = '" . (int) $data['order'] . "'");

        $data['merchant_id'] = str_replace('KB', '', $data['merchant_id']);
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
        $pezesha_receivables = $this->db->query('SELECT plr.id, plr.order_id, plr.loan_type, plr.merchant_id, plr.pezesha_id, plr.loan_id, plr.amount, plr.account, plr.mpesa_reference, plr.transaction_date, plr.created_at, o.shipping_address FROM ' . DB_PREFIX . "pezesha_loan_recceivables plr INNER JOIN " . DB_PREFIX . "order o ON o.order_id = plr.order_id WHERE DATE(plr.created_at) = '" . $yesterday . "'");
        return $pezesha_receivables->rows;
    }

}
