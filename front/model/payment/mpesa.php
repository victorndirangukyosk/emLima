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

    public function addOrder($order_info, $request_id, $checkout_request_id, $customer_id = 0, $topup_amount = 0) {
        //$this->db->query("DELETE FROM " . DB_PREFIX . "mpesa_order WHERE order_id = " . (int) $order_info['order_id']);

        $this->db->query('INSERT INTO `' . DB_PREFIX . "mpesa_order` SET `order_id` = '" . (int) $order_info['order_id'] . "', `request_id` = '" . $request_id . "', `checkout_request_id` = '" . $checkout_request_id . "', `customer_id` = '" . $customer_id . "', `amount` = '" . $topup_amount . "'");

        return $this->db->getLastId();
    }

    public function addOrderMobile($order_info, $request_id, $checkout_request_id, $customer_id = 0, $topup_amount = 0, $order_reference_number = null) {
        //$this->db->query("DELETE FROM " . DB_PREFIX . "mpesa_order WHERE order_id = " . (int) $order_info['order_id']);

        $this->db->query('INSERT INTO `' . DB_PREFIX . "mpesa_order` SET `order_id` = '" . (int) $order_info['order_id'] . "', `request_id` = '" . $request_id . "', `checkout_request_id` = '" . $checkout_request_id . "', `order_reference_number` = '" . $order_reference_number . "', `customer_id` = '" . $customer_id . "', `amount` = '" . $topup_amount . "'");

        return $this->db->getLastId();
    }

    public function addOrderApi($mpesa_refrence_id, $request_id, $checkout_request_id, $order_id) {
        //$this->db->query("DELETE FROM " . DB_PREFIX . "mpesa_order WHERE mpesa_receipt_number = " . (int) $mpesa_refrence_id);

        $this->db->query('INSERT INTO `' . DB_PREFIX . "mpesa_order` SET `mpesa_receipt_number` = '" . $mpesa_refrence_id . "', `order_id` = '" . $order_id . "', `request_id` = '" . $request_id . "', `checkout_request_id` = '" . $checkout_request_id . "'");

        return $this->db->getLastId();
    }

    public function insertOrderTransactionId($order_id, $transaction_id) {
        $log = new Log('error.log');
        $log->write('order_id_transaction_id');
        $log->write($order_id . ' ' . $transaction_id);
        $log->write('order_id_transaction_id');
        $sql = 'DELETE FROM ' . DB_PREFIX . "order_transaction_id WHERE order_id = '" . (int) $order_id . "'";

        $query = $this->db->query($sql);

        $sql = 'INSERT into ' . DB_PREFIX . "order_transaction_id SET order_id = '" . $order_id . "', transaction_id = '" . $transaction_id . "'";

        $query = $this->db->query($sql);
    }

    public function insertMobileCheckoutOrderTransactionId($order_reference_number, $mpesa_receipt_number) {
        $this->db->query('UPDATE `' . DB_PREFIX . 'mpesa_order` SET `mpesa_receipt_number` = "' . $this->db->escape($mpesa_receipt_number) . '" where order_reference_number="' . $order_reference_number . '"');
    }

    public function updateMpesaOrder($order_id, $mpesa_receipt_number) {
        $this->db->query('UPDATE `' . DB_PREFIX . 'mpesa_order` SET `mpesa_receipt_number` = "' . $this->db->escape($mpesa_receipt_number) . '" where order_id=' . $order_id);
    }

    public function updateMpesaOrderByMerchant($order_id, $mpesa_receipt_number, $checkout_request_id) {
        $this->db->query('UPDATE `' . DB_PREFIX . 'mpesa_order` SET `mpesa_receipt_number` = "' . $this->db->escape($mpesa_receipt_number) . '" WHERE checkout_request_id = "' . $checkout_request_id . '" AND order_id=' . $order_id);
    }

    public function updateMpesaCustomerByMerchant($order_id, $customer_id, $mpesa_receipt_number, $checkout_request_id) {
        $this->db->query('UPDATE `' . DB_PREFIX . 'mpesa_order` SET `mpesa_receipt_number` = "' . $this->db->escape($mpesa_receipt_number) . '" WHERE checkout_request_id = "' . $checkout_request_id . '" AND customer_id=' . $customer_id);
    }

    public function updateOrderIdMpesaOrder($order_id, $mpesa_receipt_number) {
        $this->db->query('UPDATE `' . DB_PREFIX . 'mpesa_order` SET `order_id` = ' . $this->db->escape($order_id) . " where order_reference_number='" . $mpesa_receipt_number . "'");
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

    public function getMpesaOrders($request_id) {
        $result = $this->db->query('SELECT * FROM `' . DB_PREFIX . "mpesa_order` WHERE `request_id` = '" . $this->db->escape($request_id) . "'")->rows;
        return $result;
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

    public function getMpesaByOrderReferenceNumber($order_reference_number) {
        $result = $this->db->query('SELECT * FROM `' . DB_PREFIX . "mpesa_order` WHERE `order_reference_number` = '" . $this->db->escape($order_reference_number) . "'");
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

    public function getMpesaByCustomerId($customer_id) {
        $result = $this->db->query('SELECT * FROM `' . DB_PREFIX . "mpesa_order` WHERE `order_id` = 0 and customer_id='" . $this->db->escape($customer_id) . "'");
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
        $log = new Log('error.log');
        $notify = 1;
        $comment = 'mPesa Transaction Completed Successfully!';

        $this->db->query('UPDATE `' . DB_PREFIX . "order` SET payment_method = '" . $payment_method . "', payment_code = '" . $payment_code . "', amount_partialy_paid = '0',  paid = 'Y', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");

        if (($present_order_status_id == 9 || $present_order_status_id == 14) && ($order_status_id == 1)) {
            $log->write('UPDATING ORDER AS Y');
            $this->db->query('UPDATE `' . DB_PREFIX . "order` SET order_status_id = '" . (int) $order_status_id . "', payment_method = '" . $payment_method . "', payment_code = '" . $payment_code . "', amount_partialy_paid = '0',  paid = 'Y', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");
        }

        if ($present_order_status_id != 9 && $present_order_status_id != 14 && $order_status_id == 1) {
            $log->write('UPDATING ORDER AS Y 2');
            $this->db->query('UPDATE `' . DB_PREFIX . "order` SET payment_method = '" . $payment_method . "', payment_code = '" . $payment_code . "', amount_partialy_paid = '0', paid = 'Y', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");
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

    public function addOrderHistoryTransactionFailed($order_id, $order_status_id, $added_by = '', $added_by_role = '', $present_order_status_id, $payment_method, $payment_code, $paid) {
        $log = new Log('error.log');
        $notify = 1;
        $comment = 'mPesa Transaction Failed!';

        if ($paid == 'Y') {
            $log->write('UPDATING ORDER AS N');
            $this->db->query('UPDATE `' . DB_PREFIX . "order` SET payment_method = '" . $payment_method . "', payment_code = '" . $payment_code . "', paid = 'N', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");
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

    public function insertCustomerTransactionId($customer_id, $transaction_id) {
        /* $sql = 'DELETE FROM ' . DB_PREFIX . "order_transaction_id WHERE order_id = 0 and customer_id='" . (int) $order_id . "'";

          $query = $this->db->query($sql); */
        // $this->deleteCustomerTransactionId($customer_id, $transaction_id);

        $sql = 'INSERT into ' . DB_PREFIX . "order_transaction_id SET order_id = 0 ,customer_id='" . $customer_id . "', transaction_id = '" . $transaction_id . "'";

        $query = $this->db->query($sql);
    }

    public function addCustomerHistoryTransaction($customer_id, $order_status_id, $amount_topup, $payment_method, $payment_code, $merchant_request_id, $added_by = '', $added_by_role = '') {
        $notify = 1;
        $comment = 'mPesa Transaction Completed Successfully!';
        $sql1 = 'DELETE FROM ' . DB_PREFIX . "customer_credit WHERE order_id = 0 and customer_id= '" . (int) $customer_id . "'and transaction_id = '" . $merchant_request_id . "'";

        $query = $this->db->query($sql1);

        // if ($order_status_id == 1) {
        $this->db->query('INSERT INTO ' . DB_PREFIX . "customer_credit SET customer_id = '" . (int) $customer_id . "', order_id = 0, description = 'Topup from mpesa', amount = '" . (float) $amount_topup . "', date_added = NOW(),transaction_id='" . $merchant_request_id . "'");
        // } 
    }

    public function getMpesaCustomer($request_id) {
        $result = $this->db->query('SELECT `customer_id` FROM `' . DB_PREFIX . "mpesa_order` WHERE `request_id` = '" . $this->db->escape($request_id) . "'")->row;

        if ($result) {
            $customer_id = $result['customer_id'];
        } else {
            $customer_id = false;
        }

        return $customer_id;
    }

    public function getMpesaCustomers($request_id) {
        $result = $this->db->query('SELECT * FROM `' . DB_PREFIX . "mpesa_order` WHERE `request_id` = '" . $this->db->escape($request_id) . "'")->row;
        return $result;
    }

    public function deleteCustomerTransactionId($customer_id, $transaction_id) {
        $sql = 'DELETE FROM ' . DB_PREFIX . "order_transaction_id WHERE order_id = 0 and customer_id= '" . (int) $customer_id . "'and transaction_id = '" . $transaction_id . "'";

        $query = $this->db->query($sql);

        //after deleteing Failed Transaction, check wallet and delete record if exists
        $sql1 = 'DELETE FROM ' . DB_PREFIX . "customer_credit WHERE order_id = 0 and customer_id= '" . (int) $customer_id . "'and transaction_id = '" . $transaction_id . "'";

        $query = $this->db->query($sql1);
    }

    public function getTopupAmount($customer_id, $request_id) {
        $result = $this->db->query('SELECT `amount` FROM `' . DB_PREFIX . "mpesa_order` WHERE `request_id` = '" . $this->db->escape($request_id) . "'")->row;

        if ($result) {
            $amount = $result['amount'];
        } else {
            $amount = 0;
        }

        return $amount;
    }

    public function insertMpesaOrderTransaction($order_id, $order_reference_number, $receipt_number) {
        $sql = 'INSERT into ' . DB_PREFIX . "order_transaction_id SET order_id = '" . $order_id . "', order_reference_number = '" . $order_reference_number . "', transaction_id = '" . $receipt_number . "'";
        $query = $this->db->query($sql);
    }

    public function insertMpesaCustomerTransaction($order_id, $customer_id, $order_reference_number, $receipt_number) {
        $sql = 'INSERT into ' . DB_PREFIX . "order_transaction_id SET order_id = '" . $order_id . "',customer_id = '" . $customer_id . "', order_reference_number = '" . $order_reference_number . "', transaction_id = '" . $receipt_number . "'";
        // $sql = 'INSERT into ' . DB_PREFIX . "order_transaction_id SET order_id = 0 ,customer_id='" . $customer_id . "', transaction_id = '" . $transaction_id . "'";

        $query = $this->db->query($sql);
    }

    public function updateMpesaOrderTransaction($order_id, $order_reference_number, $receipt_number) {
        $this->db->query('UPDATE `' . DB_PREFIX . 'order_transaction_id` SET `transaction_id` = "' . $this->db->escape($receipt_number) . '" where order_reference_number="' . $order_reference_number . '" AND order_id =' . (int) $order_id);
    }

    public function updateMpesaCustomerTransaction($order_id, $customer_id, $order_reference_number, $receipt_number) {
        $this->db->query('UPDATE `' . DB_PREFIX . 'order_transaction_id` SET `transaction_id` = "' . $this->db->escape($receipt_number) . '" where order_reference_number="' . $order_reference_number . '" AND order_id =' . (int) $order_id);
    }

    public function getOrderTransactionDetails($order_reference_number) {
        $result = $this->db->query('SELECT * FROM `' . DB_PREFIX . "order_transaction_id` WHERE `order_reference_number` = '" . $this->db->escape($order_reference_number) . "'")->rows;
        return $result;
    }

    public function getOrderTransactionDetailsByOrderId($order_id) {
        $result = $this->db->query('SELECT * FROM `' . DB_PREFIX . "order_transaction_id` WHERE `order_id` = '" . $this->db->escape($order_id) . "'")->row;
        return $result;
    }

    public function updateMpesaOrderTransactionWithOrderId($order_id, $order_reference_number) {
        $this->db->query('UPDATE `' . DB_PREFIX . 'order_transaction_id` SET `order_id` = "' . $this->db->escape($order_id) . '" where order_reference_number="' . $order_reference_number . '"');
    }


    

}
