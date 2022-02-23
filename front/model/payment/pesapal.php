<?php

class ModelPaymentPesapal extends Model {

    public function getMethod($total) {
        $this->load->language('payment/pesapal');

        if ($this->config->get('pesapal_total') > 0 && $this->config->get('pesapal_total') > $total) {
            $status = false;
        } else {
            $status = true;
        }

        $method_data = [];

        if ($status) {
            $method_data = [
                'code' => 'pesapal',
                'title' => $this->language->get('text_title'),
                'terms' => $this->language->get('text_terms'),
                'sort_order' => $this->config->get('pesapal_sort_order'),
            ];
        }

        return $method_data;
    }

    public function addOrder($order_info, $request_id, $checkout_request_id) {
        //$this->db->query("DELETE FROM " . DB_PREFIX . "pesapal_order WHERE order_id = " . (int) $order_info['order_id']);

        $this->db->query('INSERT INTO `' . DB_PREFIX . "pesapal_order` SET `order_id` = '" . (int) $order_info['order_id'] . "', `request_id` = '" . $request_id . "', `checkout_request_id` = '" . $checkout_request_id . "'");

        return $this->db->getLastId();
    }

    public function addOrderApi($pesapal_refrence_id, $request_id, $checkout_request_id, $order_id) {
        //$this->db->query("DELETE FROM " . DB_PREFIX . "pesapal_order WHERE pesapal_receipt_number = " . (int) $pesapal_refrence_id);

        $this->db->query('INSERT INTO `' . DB_PREFIX . "pesapal_order` SET `pesapal_receipt_number` = '" . $pesapal_refrence_id . "', `order_id` = '" . $order_id . "', `request_id` = '" . $request_id . "', `checkout_request_id` = '" . $checkout_request_id . "'");

        return $this->db->getLastId();
    }

    public function insertOrderTransactionId($order_id, $transaction_id) {
        $sql = 'DELETE FROM ' . DB_PREFIX . "order_transaction_id WHERE order_id = '" . (int) $order_id . "'";

        $query = $this->db->query($sql);

        $sql = 'INSERT into ' . DB_PREFIX . "order_transaction_id SET order_id = '" . $order_id . "', transaction_id = '" . $transaction_id . "'";

        $query = $this->db->query($sql);
    }

    public function updatePesapalOrder($order_id, $pesapal_receipt_number) {
        $this->db->query('UPDATE `' . DB_PREFIX . 'pesapal_order` SET `pesapal_receipt_number` = ' . $this->db->escape($pesapal_receipt_number) . ' where order_id=' . $order_id);
    }

    public function updateOrderIdPesapalOrder($order_id, $pesapal_receipt_number) {
        $this->db->query('UPDATE `' . DB_PREFIX . 'pesapal_order` SET `order_id` = ' . $this->db->escape($order_id) . " where pesapal_receipt_number='" . $pesapal_receipt_number . "'");
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

    public function insertOrderTransactionIdPesapal($order_id, $transaction_tracking_id, $merchant_reference, $customer_id) {
        $this->db->query('INSERT INTO ' . DB_PREFIX . "pesapal_transactions SET order_id = '" . $order_id . "', pesapal_transaction_tracking_id = '" . $transaction_tracking_id . "', pesapal_merchant_reference = '" . $merchant_reference . "', customer_id = '" . $customer_id . "', created_at = NOW()");
        //$sql = "INSERT into " . DB_PREFIX . "pesapal_transactions SET order_id = '" . $order_id . "', pesapal_transaction_tracking_id = '" . $transaction_tracking_id . "', pesapal_merchant_reference = '" . $merchant_reference . "'";
        //$query = $this->db->query($sql);
    }

    public function insertOrderTransactionIdPesapalOther($order_id, $transaction_tracking_id, $merchant_reference, $customer_id, $amount) {
        $this->db->query('INSERT INTO ' . DB_PREFIX . "pesapal_transactions SET order_id = NULL, pesapal_transaction_tracking_id = '" . $transaction_tracking_id . "', pesapal_merchant_reference = '" . $merchant_reference . "', customer_id = '" . $customer_id . "', amount = '" . $amount . "', created_at = NOW()");
        //$sql = "INSERT into " . DB_PREFIX . "pesapal_transactions SET order_id = '" . $order_id . "', pesapal_transaction_tracking_id = '" . $transaction_tracking_id . "', pesapal_merchant_reference = '" . $merchant_reference . "'";
        //$query = $this->db->query($sql);
    }

    public function updateorderstatusipn($order_id, $transaction_tracking_id, $merchant_reference, $customer_id, $status) {
        $this->db->query('UPDATE `' . DB_PREFIX . "pesapal_transactions` SET `status` = '" . $status . "',updated_at = NOW() where order_id='" . $order_id . "' AND pesapal_transaction_tracking_id ='" . $transaction_tracking_id . "'");
    }

    public function updateorderstatusipnOther($order_id, $transaction_tracking_id, $merchant_reference, $customer_id, $status) {
        $this->db->query('UPDATE `' . DB_PREFIX . "pesapal_transactions` SET `status` = '" . $status . "',updated_at = NOW() where pesapal_transaction_tracking_id ='" . $transaction_tracking_id . "'");
    }

    public function updateorderstatusipnBytrackingID($order_id, $transaction_tracking_id, $merchant_reference, $customer_id, $status) {
        $this->db->query('UPDATE `' . DB_PREFIX . "pesapal_transactions` SET `status` = '" . $status . "',updated_at = NOW() where order_id='" . $order_id . "' AND pesapal_transaction_tracking_id ='" . $transaction_tracking_id . "'");
    }

    public function addOrderHistory($order_id, $order_status_id, $added_by = '', $added_by_role = '', $paid = null) {
        $notify = 1;
        $comment = '';
        $this->db->query('UPDATE `' . DB_PREFIX . "order` SET order_status_id = '" . (int) $order_status_id . "', amount_partialy_paid = '0', paid = 'Y', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");
        $order_history = $this->db->query('SELECT * FROM `' . DB_PREFIX . "order_history` WHERE `order_id` = '" . $order_id . "' AND order_status_id='" . (int) $order_status_id . "'")->num_rows;
        $log = new Log('error.log');
        $log->write('PESAPAL ORDER HISTORY');
        $log->write($order_history);
        if ($order_history <= 0) {
            $log = new Log('error.log');
            $log->write('PESAPAL ORDER HISTORY');
            $log->write($order_history);
            $this->db->query('INSERT INTO ' . DB_PREFIX . "order_history SET order_id = '" . (int) $order_id . "', added_by = '" . (int) $added_by . "', role = '" . $added_by_role . "', order_status_id = '" . (int) $order_status_id . "', notify = '" . (int) $notify . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
        }
        if ($order_history > 0) {
            $log = new Log('error.log');
            $log->write('PESAPAL ORDER HISTORY');
            $log->write($order_history);
            $this->db->query('UPDATE `' . DB_PREFIX . "order_history` SET notify = '" . (int) $notify . "', added_by = '" . (int) $added_by . "', role = '" . $added_by_role . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
        }
        $this->insertOrderTransactionFee($order_id, $order_status_id);
        $this->updateDateAdded($order_id, $order_status_id);
    }

    public function addOrderHistoryFailed($order_id, $order_status_id, $added_by = '', $added_by_role = '', $paid) {
        $notify = 1;
        $comment = '';
        $log = new Log('error.log');
        $log->write('PESAPAL ORDER HISTORY FAILED');
        $log->write($paid);
        $log->write('PESAPAL ORDER HISTORY FAILED');
        if ($paid == 'Y') {
            $log = new Log('error.log');
            $log->write('PESAPAL ORDER HISTORY FAILED');
            $log->write($paid);
            $log->write('PESAPAL ORDER HISTORY FAILED');
            $this->db->query('UPDATE `' . DB_PREFIX . "order` SET paid = 'N', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");
        } else {
            $this->db->query('UPDATE `' . DB_PREFIX . "order` SET order_status_id = '" . (int) $order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");
        }
        $order_history = $this->db->query('SELECT * FROM `' . DB_PREFIX . "order_history` WHERE `order_id` = '" . $order_id . "' AND order_status_id='" . (int) $order_status_id . "'")->num_rows;
        $log = new Log('error.log');
        $log->write('PESAPAL ORDER HISTORY FAILED');
        $log->write($order_history);
        if ($order_history <= 0) {
            $log = new Log('error.log');
            $log->write('PESAPAL ORDER HISTORY FAILED');
            $log->write($order_history);
            $this->db->query('INSERT INTO ' . DB_PREFIX . "order_history SET order_id = '" . (int) $order_id . "', added_by = '" . (int) $added_by . "', role = '" . $added_by_role . "', order_status_id = '" . (int) $order_status_id . "', notify = '" . (int) $notify . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
        }
        if ($order_history > 0) {
            $log = new Log('error.log');
            $log->write('PESAPAL ORDER HISTORY FAILED');
            $log->write($order_history);
            $this->db->query('UPDATE `' . DB_PREFIX . "order_history` SET notify = '" . (int) $notify . "', added_by = '" . (int) $added_by . "', role = '" . $added_by_role . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
        }
        $this->updateDateAdded($order_id, $order_status_id);
        //$this->insertOrderTransactionFee($order_id, $order_status_id);
    }

    public function insertOrderTransactionFee($order_id, $order_status_id) {
        $result = $this->db->query('SELECT * FROM `' . DB_PREFIX . "order_total` WHERE `order_id` = '" . $order_id . "' AND code='sub_total'")->row;
        $order_total = $this->db->query('SELECT * FROM `' . DB_PREFIX . "order_total` WHERE `order_id` = '" . $order_id . "' AND code='total'")->row;
        $log = new Log('error.log');
        $log->write('PESAPAL TRANSACTION AMOUNT');
        $log->write($result);
        $log->write('PESAPAL TRANSACTION AMOUNT');

        $log->write('PESAPAL TOTAL AMOUNT');
        $log->write($order_total);
        $log->write('PESAPAL TOTAL AMOUNT');

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

    public function getPesapalOtherAmount($customer_id) {
        $result = $this->db->query('SELECT * FROM `' . DB_PREFIX . "pesapal_transactions` WHERE `customer_id` = '" . $customer_id . "' AND order_id IS NULL")->rows;
        return $result;
    }

    public function updateDateAdded($order_id, $order_status_id) {
        $order_history_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_history` WHERE order_id = '" . (int) $order_id . "'");
        $order_history = $order_history_query->num_rows;

        $order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int) $order_id . "'");
        $order = $order_query->row;
        $date_added = date('Y-m-d', strtotime($order['date_added']));
        $current_date = date('Y-m-d');
        $log = new Log('error.log');
        $log->write('UPDATE DATE ADDED');
        $log->write($order_id);
        $log->write($order_status_id);
        $log->write($date_added);
        $log->write($current_date);
        $log->write('UPDATE DATE ADDED');

        if ($order_history == 0 && ($order_status_id == 14 || $order_status_id == 1) && $date_added < $current_date) {
            $log->write('UPDATE DATE ADDED 2');
            $log->write($order_id);
            $log->write($order_status_id);
            $log->write($date_added);
            $log->write($current_date);
            $log->write('UPDATE DATE ADDED 2');
            $this->db->query('UPDATE `' . DB_PREFIX . 'order` SET date_added = NOW() WHERE order_id="' . $order_id . '"');
        }
    }

}
