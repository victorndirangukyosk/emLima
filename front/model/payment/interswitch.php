<?php

class ModelPaymentInterswitch extends Model {

    public function getMethod($total) {
        $this->load->language('payment/interswitch');

        if ($this->config->get('interswitch_total') > 0 && $this->config->get('interswitch_total') > $total) {
            $status = false;
        } else {
            $status = true;
        }

        $method_data = [];

        if ($status) {
            $method_data = [
                'code' => 'interswitch',
                'title' => $this->language->get('text_title'),
                'terms' => $this->language->get('text_terms'),
                'sort_order' => $this->config->get('interswitch_sort_order'),
            ];
        }

        return $method_data;
    }

    public function addOrder($order_info, $request_id, $checkout_request_id) {

        $this->db->query('INSERT INTO `' . DB_PREFIX . "interswitch_order` SET `order_id` = '" . (int) $order_info['order_id'] . "', `request_id` = '" . $request_id . "', `checkout_request_id` = '" . $checkout_request_id . "'");

        return $this->db->getLastId();
    }

    public function addOrderApi($interswitch_refrence_id, $request_id, $checkout_request_id, $order_id) {

        $this->db->query('INSERT INTO `' . DB_PREFIX . "interswitch_order` SET `interswitch_receipt_number` = '" . $interswitch_refrence_id . "', `order_id` = '" . $order_id . "', `request_id` = '" . $request_id . "', `checkout_request_id` = '" . $checkout_request_id . "'");

        return $this->db->getLastId();
    }

    public function insertOrderTransactionId($order_id, $transaction_id) {
        $sql = 'DELETE FROM ' . DB_PREFIX . "order_transaction_id WHERE order_id = '" . (int) $order_id . "'";

        $query = $this->db->query($sql);

        $sql = 'INSERT into ' . DB_PREFIX . "order_transaction_id SET order_id = '" . $order_id . "', transaction_id = '" . $transaction_id . "'";

        $query = $this->db->query($sql);
    }

    public function updateInterswitchOrder($order_id, $interswitch_receipt_number) {
        $this->db->query('UPDATE `' . DB_PREFIX . 'interswitch_order` SET `interswitch_receipt_number` = ' . $this->db->escape($interswitch_receipt_number) . ' where order_id=' . $order_id);
    }

    public function updateOrderIdInterswitchOrder($order_id, $interswitch_receipt_number) {
        $this->db->query('UPDATE `' . DB_PREFIX . 'interswitch_order` SET `order_id` = ' . $this->db->escape($order_id) . " where interswitch_receipt_number='" . $interswitch_receipt_number . "'");
    }

    public function insertOrderTransactionIdInterswitch($order_id, $transaction_tracking_id, $merchant_reference, $customer_id) {
        $this->db->query('INSERT INTO ' . DB_PREFIX . "interswitch_transactions SET order_id = '" . $order_id . "', interswitch_transaction_tracking_id = '" . $transaction_tracking_id . "', interswitch_merchant_reference = '" . $merchant_reference . "', customer_id = '" . $customer_id . "', created_at = NOW()");
    }

    public function insertOrderTransactionIdInterswitchOther($order_id, $transaction_tracking_id, $merchant_reference, $customer_id, $amount) {
        $this->db->query('INSERT INTO ' . DB_PREFIX . "interswitch_transactions SET order_id = NULL, interswitch_transaction_tracking_id = '" . $transaction_tracking_id . "', interswitch_merchant_reference = '" . $merchant_reference . "', customer_id = '" . $customer_id . "', amount = '" . $amount . "', created_at = NOW()");
    }

    public function addOrderHistory($order_id, $order_status_id, $added_by = '', $added_by_role = '') {
        $notify = 1;
        $comment = '';
        $this->db->query('UPDATE `' . DB_PREFIX . "order` SET order_status_id = '" . (int) $order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");
        $order_history = $this->db->query('SELECT * FROM `' . DB_PREFIX . "order_history` WHERE `order_id` = '" . $order_id . "' AND order_status_id='" . (int) $order_status_id . "'")->num_rows;
        $log = new Log('error.log');
        $log->write('INTERSWITCH ORDER HISTORY');
        $log->write($order_history);
        if ($order_history <= 0) {
            $log = new Log('error.log');
            $log->write('INTERSWITCH ORDER HISTORY');
            $log->write($order_history);
            $this->db->query('INSERT INTO ' . DB_PREFIX . "order_history SET order_id = '" . (int) $order_id . "', added_by = '" . (int) $added_by . "', role = '" . $added_by_role . "', order_status_id = '" . (int) $order_status_id . "', notify = '" . (int) $notify . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
        }
        if ($order_history > 0) {
            $log = new Log('error.log');
            $log->write('INTERSWITCH ORDER HISTORY');
            $log->write($order_history);
            $this->db->query('UPDATE `' . DB_PREFIX . "order_history` SET notify = '" . (int) $notify . "', added_by = '" . (int) $added_by . "', role = '" . $added_by_role . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
        }
        $this->insertOrderTransactionFee($order_id, $order_status_id);
    }

    public function insertOrderTransactionFee($order_id, $order_status_id) {
        $result = $this->db->query('SELECT * FROM `' . DB_PREFIX . "order_total` WHERE `order_id` = '" . $order_id . "' AND code='sub_total'")->row;
        $order_total = $this->db->query('SELECT * FROM `' . DB_PREFIX . "order_total` WHERE `order_id` = '" . $order_id . "' AND code='total'")->row;
        $log = new Log('error.log');
        $log->write('INTERSWITCH TRANSACTION AMOUNT');
        $log->write($result);
        $log->write('INTERSWITCH TRANSACTION AMOUNT');

        $log->write('INTERSWITCH TOTAL AMOUNT');
        $log->write($order_total);
        $log->write('INTERSWITCH TOTAL AMOUNT');

        $transaction_fee = 0;
        $percentage = 3.5;
        $transaction_fee = ($percentage / 100) * $result['value'];
        $total = str_replace(',', '', $result['value'] + $transaction_fee);
        $log->write('TRANSACTION FEE');
        $log->write($transaction_fee);
        $log->write($total);

        $this->db->query('UPDATE `' . DB_PREFIX . "order` SET order_status_id = '" . (int) $order_status_id . "', total = '" . $total . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");
        $fetch_order_details = $this->db->query('SELECT * FROM `' . DB_PREFIX . "order_total` WHERE `order_id` = '" . $order_id . "' AND code='sub_total'");
        if (count($fetch_order_details->rows) > 0) {
            $this->db->query('UPDATE `' . DB_PREFIX . "order_total` SET value = '" . $transaction_fee . "' WHERE order_id = '" . (int) $order_id . "' AND code='transaction_fee'");
            $this->db->query('UPDATE `' . DB_PREFIX . "order_total` SET value = '" . $total . "' WHERE order_id = '" . (int) $order_id . "' AND code='total'");
        } else {
            $log->write('TRANSACTION FEE INSERTION');
            $sql = 'INSERT INTO ' . DB_PREFIX . "order_total SET value = '" . $transaction_fee . "', order_id = '" . $order_id . "', title = 'Transaction-Fee', code = 'transaction_fee', sort_order = '" . $this->config->get('transaction_fee_sort_order') . "'";
            $this->db->query($sql);
            $this->db->query('UPDATE `' . DB_PREFIX . "order_total` SET value = '" . $total . "' WHERE order_id = '" . (int) $order_id . "' AND code='total'");
        }
    }

    public function OrderTransaction($order_id, $transaction_id) {
        $this->db->query('INSERT INTO ' . DB_PREFIX . "order_transaction_id SET order_id = '" . (int) $order_id . "', transaction_id = '" . $transaction_id . "'");
    }

    public function getInterswitchOtherAmount($customer_id) {
        $result = $this->db->query('SELECT * FROM `' . DB_PREFIX . "interswitch_transactions` WHERE `customer_id` = '" . $customer_id . "' AND order_id IS NULL")->rows;
        return $result;
    }

}
