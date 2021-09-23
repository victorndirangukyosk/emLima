<?php

class ModelPaymentMpesa extends Model {

    public function getMethod($total) {
        $this->load->language('payment/mpesa');

        if ($this->config->get('mpesa_total') > 0 && $this->config->get('mpesa_total') > $total) {
            $status = false;
        } else {
            $status = true;
        }

        $method_data = [];

        if ($status) {
            $method_data = [
                'code' => 'mpesa',
                'title' => $this->language->get('text_title'),
                'terms' => $this->language->get('text_terms'),
                'sort_order' => $this->config->get('mpesa_sort_order'),
            ];
        }

        return $method_data;
    }

    public function addOrder($order_info, $request_id, $checkout_request_id) {
        //$this->db->query("DELETE FROM " . DB_PREFIX . "mpesa_order WHERE order_id = " . (int) $order_info['order_id']);

        $this->db->query('INSERT INTO `' . DB_PREFIX . "mpesa_order` SET `order_id` = '" . (int) $order_info['order_id'] . "', `request_id` = '" . $request_id . "', `checkout_request_id` = '" . $checkout_request_id . "'");

        return $this->db->getLastId();
    }

    public function addOrderApi($mpesa_refrence_id, $request_id, $checkout_request_id, $order_id) {
        //$this->db->query("DELETE FROM " . DB_PREFIX . "mpesa_order WHERE mpesa_receipt_number = " . (int) $mpesa_refrence_id);

        $this->db->query('INSERT INTO `' . DB_PREFIX . "mpesa_order` SET `mpesa_receipt_number` = '" . $mpesa_refrence_id . "', `order_id` = '" . $order_id . "', `request_id` = '" . $request_id . "', `checkout_request_id` = '" . $checkout_request_id . "'");

        return $this->db->getLastId();
    }

    public function insertOrderTransactionId($order_id, $transaction_id) {
        $sql = 'DELETE FROM ' . DB_PREFIX . "order_transaction_id WHERE order_id = '" . (int) $order_id . "'";

        $query = $this->db->query($sql);

        $sql = 'INSERT into ' . DB_PREFIX . "order_transaction_id SET order_id = '" . $order_id . "', transaction_id = '" . $transaction_id . "'";

        $query = $this->db->query($sql);
    }

    public function updateMpesaOrder($order_id, $mpesa_receipt_number) {
        $this->db->query('UPDATE `' . DB_PREFIX . 'mpesa_order` SET `mpesa_receipt_number` = ' . $this->db->escape($mpesa_receipt_number) . ' where order_id=' . $order_id);
    }

    public function updateOrderIdMpesaOrder($order_id, $mpesa_receipt_number) {
        $this->db->query('UPDATE `' . DB_PREFIX . 'mpesa_order` SET `order_id` = ' . $this->db->escape($order_id) . " where mpesa_receipt_number='" . $mpesa_receipt_number . "'");
    }

    public function getMpesaOrder($request_id) {
        $result = $this->db->query('SELECT `order_id` FROM `' . DB_PREFIX . "mpesa_order` WHERE `request_id` = '" . $this->db->escape($request_id) . "'")->row;

        if ($result) {
            $order_id = $result['order_id'];
        } else {
            $order_id = false;
        }

        return $order_id;
    }

    public function getAllMpesaOrder($request_id) {
        $result = $this->db->query('SELECT `order_id` FROM `' . DB_PREFIX . "mpesa_order` WHERE `request_id` = '" . $this->db->escape($request_id) . "'")->rows;

        return $result;
    }

    public function getMpesaByOrderId($order_id) {
        $result = $this->db->query('SELECT * FROM `' . DB_PREFIX . "mpesa_order` WHERE `order_id` = '" . $this->db->escape($order_id) . "'");
        $log = new Log('error.log');
        $log->write('result');
        $log->write($result->rows);
        $log->write('result');
        if (count($result->rows) > 0) {
            $res = $result->rows[$result->num_rows - 1];
        }
        //echo '<pre>';print_r($res);exit;
        return $res;
    }

    public function getMpesaByOrderIdApi($order_id) {
        $result = $this->db->query('SELECT * FROM `' . DB_PREFIX . "mpesa_order` WHERE `mpesa_receipt_number` = '" . $this->db->escape($order_id) . "'")->rows;

        return $result;
    }

    public function addOrderHistoryTransaction($order_id, $order_status_id, $added_by = '', $added_by_role = '', $present_order_status_id, $payment_method, $payment_code) {
        $notify = 1;
        $comment = '';

        if (($present_order_status_id == 9 || $present_order_status_id == 14) && ($order_status_id == 1)) {
            $this->db->query('UPDATE `' . DB_PREFIX . "order` SET order_status_id = '" . (int) $order_status_id . "', payment_method = '" . $payment_method . "', payment_code = '" . $payment_code . "', paid = 'Y', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");
        }

        if ($present_order_status_id != 9 && $present_order_status_id != 14 && $order_status_id == 1) {
            $this->db->query('UPDATE `' . DB_PREFIX . "order` SET payment_method = '" . $payment_method . "', payment_code = '" . $payment_code . "', paid = 'Y', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");
        }

        $order_history = $this->db->query('SELECT * FROM `' . DB_PREFIX . "order_history` WHERE `order_id` = '" . $order_id . "' AND order_status_id='" . (int) $order_status_id . "'")->num_rows;
        $log = new Log('error.log');
        $log->write('MPESA ORDER HISTORY');
        $log->write($order_status_id);
        $log->write($order_history);

        /* if ($order_history <= 0) { */
        $log = new Log('error.log');
        $log->write('MPESA ORDER HISTORY');
        $log->write($order_history);
        $this->db->query('INSERT INTO ' . DB_PREFIX . "order_history SET order_id = '" . (int) $order_id . "', added_by = '" . (int) $added_by . "', role = '" . $added_by_role . "', order_status_id = '" . (int) $order_status_id . "', notify = '" . (int) $notify . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
        /* } */

        /* if ($order_history > 0) {
          $log = new Log('error.log');
          $log->write('INTERSWITCH ORDER HISTORY');
          $log->write($order_history);
          $this->db->query('UPDATE `' . DB_PREFIX . "order_history` SET notify = '" . (int) $notify . "', added_by = '" . (int) $added_by . "', role = '" . $added_by_role . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
          } */
        //$this->insertOrderTransactionFee($order_id, $order_status_id);
    }

}
