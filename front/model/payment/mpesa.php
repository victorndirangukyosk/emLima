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

        $this->db->query('INSERT INTO `' . DB_PREFIX . "mpesa_order` SET `order_id` = '" . (int) $order_info['order_id'] . "', `request_id` = '" . $request_id . "', `checkout_request_id` = '" . $checkout_request_id . "', `customer_id` = '" . $customer_id . "', `amount` = '" . $topup_amount . "', date_added = NOW()");

        return $this->db->getLastId();
    }

    public function deleteOrder($order_info) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "mpesa_order WHERE order_id = " . (int) $order_info);
    }

    // public function addOrderMpesa($order_info, $request_id, $checkout_request_id, $customer_id = 0, $topup_amount = 0) {
    //     //$this->db->query("DELETE FROM " . DB_PREFIX . "mpesa_order WHERE order_id = " . (int) $order_info['order_id']);
    //     $this->db->query('INSERT INTO `' . DB_PREFIX . "mpesa_order` SET `order_id` = '" . (int) $order_info . "', `request_id` = '" . $request_id . "', `checkout_request_id` = '" . $checkout_request_id . "', `customer_id` = '" . $customer_id . "', `amount` = '" . $topup_amount . "'");
    //     return $this->db->getLastId();
    // }



    public function addOrderMobile($order_info, $request_id, $checkout_request_id, $customer_id = 0, $topup_amount = 0, $order_reference_number = null) {
        //$this->db->query("DELETE FROM " . DB_PREFIX . "mpesa_order WHERE order_id = " . (int) $order_info['order_id']);

        $this->db->query('INSERT INTO `' . DB_PREFIX . "mpesa_order` SET `order_id` = '" . (int) $order_info['order_id'] . "', `request_id` = '" . $request_id . "', `checkout_request_id` = '" . $checkout_request_id . "', `order_reference_number` = '" . $order_reference_number . "', `customer_id` = '" . $customer_id . "', `amount` = '" . $topup_amount . "', date_added = NOW()");

        return $this->db->getLastId();
    }

    public function addOrderApi($mpesa_refrence_id, $request_id, $checkout_request_id, $order_id) {
        //$this->db->query("DELETE FROM " . DB_PREFIX . "mpesa_order WHERE mpesa_receipt_number = " . (int) $mpesa_refrence_id);

        $this->db->query('INSERT INTO `' . DB_PREFIX . "mpesa_order` SET `mpesa_receipt_number` = '" . $mpesa_refrence_id . "', `order_id` = '" . $order_id . "', `request_id` = '" . $request_id . "', `checkout_request_id` = '" . $checkout_request_id . "'");

        return $this->db->getLastId();
    }

    public function insertOrderTransactionId($order_id, $transaction_id, $customer_id = '', $amount = '') {
        $log = new Log('error.log');

        $result = $this->db->query('SELECT * FROM `' . DB_PREFIX . "order_transaction_id` WHERE `order_id` = '" . $this->db->escape($order_id) . "' AND `transaction_id` = '" . $this->db->escape($transaction_id) . "'");
        $total_records = $result->num_rows;

        $log->write('order_id_transaction_id');
        $log->write($total_records);
        $log->write($order_id . ' ' . $transaction_id);
        $log->write('order_id_transaction_id');
        /* $sql = 'DELETE FROM ' . DB_PREFIX . "order_transaction_id WHERE order_id = '" . (int) $order_id . "'";

          $query = $this->db->query($sql); */
        if ($total_records == 0) {
            $sql = 'INSERT into ' . DB_PREFIX . "order_transaction_id SET order_id = '" . $order_id . "', customer_id = '" . $customer_id . "', amount = '" . $amount . "', transaction_id = '" . $transaction_id . "', created_at = NOW()";

            $query = $this->db->query($sql);
        }
    }

    public function insertOrderTransactionIdHybrid($order_id, $transaction_id, $customer_id = '', $amount = '') {
        $log = new Log('error.log');

        $result = $this->db->query('SELECT * FROM `' . DB_PREFIX . "order_transaction_id` WHERE `order_id` = '" . $this->db->escape($order_id) . "' AND `transaction_id` = '" . $this->db->escape($transaction_id) . "'");
        $total_records = $result->num_rows;

        $log->write('order_id_transaction_id');
        $log->write($total_records);
        $log->write($order_id . ' ' . $transaction_id);
        $log->write('order_id_transaction_id');
        if ($total_records == 0) {
            $sql = 'INSERT into ' . DB_PREFIX . "order_transaction_id SET order_id = '" . $order_id . "', customer_id = '" . $customer_id . "', amount = '" . $amount . "', transaction_id = '" . $transaction_id . "', created_at = NOW()";
            $query = $this->db->query($sql);
        }
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

    public function getMpesaTopup($request_id) {
        $result = $this->db->query('SELECT * FROM `' . DB_PREFIX . "order_transaction_id` WHERE `merchant_request_id` = '" . $this->db->escape($request_id) . "'")->rows;
        return $result;
    }

    public function getMpesaTopupNew($request_id) {
        $result = $this->db->query('SELECT * FROM `' . DB_PREFIX . "order_transaction_id` WHERE `transaction_id` = '" . $this->db->escape($request_id) . "'")->rows;
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
        $result = $this->db->query('SELECT * FROM `' . DB_PREFIX . "mpesa_order` WHERE `order_id` = 0 and customer_id='" . $this->db->escape($customer_id) . "' order by date_added asc");
        $log = new Log('error.log');
        // echo '<pre>';print_r('SELECT * FROM `' . DB_PREFIX . "mpesa_order` WHERE `order_id` = 0 and customer_id='" . $this->db->escape($customer_id) . "' order by date_added desc");exit;


        $log->write('result');
        $log->write($result->rows);
        $log->write('result');
        if (count($result->rows) > 0) {
            $res = $result->rows[$result->num_rows - 1];
        }
        //  echo '<pre>';print_r($res);exit;
        return $res;
    }

    public function getMpesaWalletByCustomerId($customer_id, $mpesa_checkout_request_id) {
        $result = $this->db->query('SELECT * FROM `' . DB_PREFIX . "order_transaction_id` WHERE `order_id` = 0 and customer_id='" . $this->db->escape($customer_id) . "' and checkout_request_id='" . $mpesa_checkout_request_id . "' order by created_at asc");
        $log = new Log('error.log');

        $log->write($result);
        return $result;
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

    public function insertCustomerTransactionId($customer_id, $checkout_request_id, $merchant_requestid = 0, $amount = '') {
        /* $sql = 'DELETE FROM ' . DB_PREFIX . "order_transaction_id WHERE order_id = 0 and customer_id='" . (int) $customer_id . "'and merchant_request_id ='" . $merchant_requestid . "'";

          $query = $this->db->query($sql); */
        // $this->deleteCustomerTransactionId($customer_id, $transaction_id);

        $sql = 'INSERT into ' . DB_PREFIX . "order_transaction_id SET order_id = 0 ,customer_id='" . $customer_id . "', amount = '" . $amount . "', transaction_id = '" . $checkout_request_id . "', checkout_request_id = '" . $checkout_request_id . "', merchant_request_id = '" . $merchant_requestid . "', created_at = NOW()";

        $query = $this->db->query($sql);
    }

    public function addupdateOrderTransactionId($customer_id, $mpesa_receipt_number, $merchant_request_id, $checkout_request_id, $order_id, $amount_topup) {
        $sql1 = 'SELECT * FROM ' . DB_PREFIX . "order_transaction_id WHERE order_id = 0 and customer_id= '" . (int) $customer_id . "'and merchant_request_id = '" . $merchant_request_id . "'";
        $result = $this->db->query($sql1);

        if ($result) {
            $this->db->query('UPDATE `' . DB_PREFIX . 'order_transaction_id` SET `transaction_id` = "' . $this->db->escape($mpesa_receipt_number) . '" where merchant_request_id="' . $result['merchant_request_id'] . '" AND checkout_request_id="' . $result['checkout_request_id'] . '" AND order_id =' . (int) $order_id);
        } else {
            $sql = 'INSERT into ' . DB_PREFIX . "order_transaction_id SET order_id = 0 ,customer_id='" . $customer_id . "', amount = '" . $amount_topup . "', transaction_id = '" . $mpesa_receipt_number . "', checkout_request_id = '" . $checkout_request_id . "', merchant_request_id = '" . $checkout_request_id . "', created_at = NOW()";
            $query = $this->db->query($sql);
        }
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

    public function findCustomerCredit($customer_id, $merchant_request_id) {
        $sql1 = 'SELECT * FROM ' . DB_PREFIX . "customer_credit WHERE order_id = 0 and customer_id= '" . (int) $customer_id . "'and transaction_id = '" . $merchant_request_id . "'";
        $query = $this->db->query($sql1);
        return $query;
    }

    public function findCustomerCreditTransaction($customer_id, $merchant_request_id) {
        $sql1 = 'SELECT * FROM ' . DB_PREFIX . "order_transaction_id WHERE order_id = 0 and customer_id= '" . (int) $customer_id . "'and transaction_id = '" . $merchant_request_id . "'";
        $query = $this->db->query($sql1);
        return $query;
    }

    public function getMpesaCustomer($request_id) {
        $result = $this->db->query('SELECT `customer_id` FROM `' . DB_PREFIX . "mpesa_order` WHERE `request_id` = '" . $this->db->escape($request_id) . "'")->row;

        if ($result) {
            $customer_id = $result['customer_id'];
        } else {
            $customer_id = 0;
        }

        return $customer_id;
    }

    public function getMpesaCustomers($request_id) {
        $result = $this->db->query('SELECT * FROM `' . DB_PREFIX . "mpesa_order` WHERE `request_id` = '" . $this->db->escape($request_id) . "'")->row;
        return $result;
    }

    public function deleteCustomerTransactionId($customer_id, $transaction_id) {
        /* $sql = 'DELETE FROM ' . DB_PREFIX . "order_transaction_id WHERE order_id = 0 and customer_id= '" . (int) $customer_id . "'and transaction_id = '" . $transaction_id . "'";

          $query = $this->db->query($sql); */

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

    public function insertMpesaCustomerTransaction($order_id, $customer_id, $order_reference_number, $receipt_number, $merchant_requestid) {
        $sql = 'INSERT into ' . DB_PREFIX . "order_transaction_id SET order_id = '" . $order_id . "',customer_id = '" . $customer_id . "', order_reference_number = '" . $order_reference_number . "', transaction_id = '" . $receipt_number . "', merchant_request_id = '" . $merchant_requestid . "'";
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

    public function getCustomerTransactionDetailsByMerchantRequestId($merchant_request_id) {
        $result = $this->db->query('SELECT * FROM `' . DB_PREFIX . "order_transaction_id` WHERE `merchant_request_id` = '" . $this->db->escape($merchant_request_id) . "'")->row;
        return $result;
    }

    public function updateMpesaOrderTransactionWithOrderId($order_id, $order_reference_number) {
        $this->db->query('UPDATE `' . DB_PREFIX . 'order_transaction_id` SET `order_id` = "' . $this->db->escape($order_id) . '" where order_reference_number="' . $order_reference_number . '"');
    }

    public function insertMobileMpesaRequest($customer_id, $merchant_request_id, $checkout_request_id, $amount) {
        $this->db->query('INSERT INTO `' . DB_PREFIX . "mobile_mpesa_requests` SET `customer_id` = '" . $customer_id . "', `merchant_request_id` = '" . $merchant_request_id . "', `checkout_request_id` = '" . $checkout_request_id . "', `amount` = '" . $amount . "'");
        return $this->db->getLastId();
    }

    public function getLatestMpesaRequest($customer_id) {
        $result = $this->db->query('SELECT * FROM `' . DB_PREFIX . "mobile_mpesa_requests` WHERE `customer_id` = '" . (int) $customer_id . "' order by id desc")->row;
        return $result;
    }

    public function insertMpesapaymentsconfirmation($data) {
        $log = new Log('error.log');
        $log->write('insertMpesapaymentsconfirmation');
        $log->write($data);
        $this->db->query('INSERT INTO `' . DB_PREFIX . "mpesa_track_payments_confirmation` SET `transaction_type` = '" . $data->TransactionType . "', `transaction_id` = '" . $data->TransID . "', `transaction_time` = '" . $data->TransTime . "', `transaction_amount` = '" . $data->TransAmount . "', `business_short_code` = '" . $data->BusinessShortCode . "', `bill_reference_number` = '" . $data->BillRefNumber . "', `invoice_number` = '" . $data->InvoiceNumber . "', `org_account_balance` = '" . $data->OrgAccountBalance . "', `third_party_trans_id` = '" . $data->ThirdPartyTransID . "', `msisdn` = '" . $data->MSISDN . "', `firstname` = '" . $data->FirstName . "'");
        return $this->db->getLastId();
    }

    public function UpdateDeliveredOrders($data) {
        $log = new Log('error.log');
        $log->write('UpdateDeliveredOrders_MODEL');
        $log->write($data->BillRefNumber);
        $log->write('UpdateDeliveredOrders_MODEL');

        // Validate if a string is a valid number
        $number_validation_regex = "/^\\d+$/";
        if (preg_match($number_validation_regex, $data->BillRefNumber)) {

            $result = $this->db->query('SELECT * FROM `' . DB_PREFIX . "order` WHERE `order_id` = '" . (int) $data->BillRefNumber . "' AND `payment_method` != 'Pezesha'")->row;
            $log->write('RESULT_UpdateDeliveredOrders');
            $log->write($result);
            $log->write('RESULT_UpdateDeliveredOrders');

            if (isset($result) && $result != NULL && $result['total'] == $data->TransAmount && $result['paid'] == 'N') {
                $log->write('TOTAL MATCHED FOR PAID STATUS N' . $result['order_id']);

                $sql = 'INSERT into ' . DB_PREFIX . "order_transaction_id SET order_id = '" . (int) $result['order_id'] . "', transaction_id = '" . $this->db->escape($data->TransID) . "', amount = '" . (int) $data->TransAmount . "', customer_id = '" . $result['customer_id'] . "', created_at = NOW()";
                $query = $this->db->query($sql);

                $this->db->query('UPDATE `' . DB_PREFIX . "order` SET payment_method = 'mPesa On Delivery', payment_code = 'mod', paid = 'Y', date_modified = NOW() WHERE order_id = '" . (int) $result['order_id'] . "'");

                // Add to activity log
                $this->load->model('account/activity');

                $activity_data = [
                    'customer_id' => $result['customer_id'],
                    'name' => $result['firstname'] . ' ' . $result['lastname'],
                    'order_id' => $result['order_id']
                ];
                $log->write('PAYBILL');

                $this->addActivity('PAYBILL', $activity_data);

                $log->write('PAYBILL');
            }

            if (isset($result) && $result != NULL && $result['paid'] == 'P') {
                $log->write('TOTAL MATCHED FOR PAID STATUS P' . $result['order_id']);

                $pending_amount = $result['total'] - $result['amount_partialy_paid'];

                if ($pending_amount == $data->TransAmount) {
                    $sql = 'INSERT into ' . DB_PREFIX . "order_transaction_id SET order_id = '" . (int) $result['order_id'] . "', transaction_id = '" . $this->db->escape($data->TransID) . "', amount = '" . (int) $data->TransAmount . "', customer_id = '" . $result['customer_id'] . "', created_at = NOW()";
                    $query = $this->db->query($sql);

                    $this->db->query('UPDATE `' . DB_PREFIX . "order` SET payment_method = 'mPesa On Delivery', payment_code = 'mod', paid = 'Y', amount_partialy_paid = 0, date_modified = NOW() WHERE order_id = '" . (int) $result['order_id'] . "'");
                }

                if ($pending_amount > $data->TransAmount) {
                    $pending_amount = ((int) $result['amount_partialy_paid']) - ((int) $data->TransAmount);

                    $sql = 'INSERT into ' . DB_PREFIX . "order_transaction_id SET order_id = '" . (int) $result['order_id'] . "', transaction_id = '" . $this->db->escape($data->TransID) . "', pending_amount = '" . (int) $pending_amount . "', amount = '" . (int) $data->TransAmount . "', customer_id = '" . $result['customer_id'] . "', created_at = NOW()";
                    $query = $this->db->query($sql);

                    $amount_partialy_paid = ((int) $result['amount_partialy_paid']) + ((int) $data->TransAmount);

                    //$this->db->query('UPDATE `' . DB_PREFIX . "order` SET payment_method = 'mPesa On Delivery', payment_code = 'mod', paid = 'P', amount_partialy_paid = '" . (int) ((int) $result['amount_partialy_paid']) + ((int) $data->TransAmount) . "', date_modified = NOW() WHERE order_id = '" . (int) $result['order_id'] . "'");
                    $this->db->query('UPDATE ' . DB_PREFIX . 'order SET payment_method="mPesa On Delivery",payment_code="mod",paid="P",amount_partialy_paid ="' . (int) $amount_partialy_paid . '",date_modified=NOW() where  order_id = "' . (int) $result['order_id'] . '"');
                }

                if ($pending_amount < $data->TransAmount) {
                    $sql = 'INSERT into ' . DB_PREFIX . "order_transaction_id SET order_id = '" . (int) $result['order_id'] . "', transaction_id = '" . $this->db->escape($data->TransID) . "', amount = '" . (int) $data->TransAmount . "', customer_id = '" . $result['customer_id'] . "', created_at = NOW()";
                    $query = $this->db->query($sql);

                    $this->db->query('UPDATE `' . DB_PREFIX . "order` SET payment_method = 'mPesa On Delivery', payment_code = 'mod', paid = 'Y', amount_partialy_paid = 0, date_modified = NOW() WHERE order_id = '" . (int) $result['order_id'] . "'");
                }

                // Add to activity log
                $this->load->model('account/activity');

                $activity_data = [
                    'customer_id' => $result['customer_id'],
                    'name' => $result['firstname'] . ' ' . $result['lastname'],
                    'order_id' => $result['order_id']
                ];
                $log->write('PAYBILL');

                $this->addActivity('PAYBILL', $activity_data);

                $log->write('PAYBILL');
            }

            if (isset($result) && $result != NULL && $data->TransAmount < $result['total'] && $result['paid'] == 'N') {
                $log->write('TOTAL IS GREATER THAN TRANSACTION AMOUNT' . $result['order_id']);

                $sql = 'INSERT into ' . DB_PREFIX . "order_transaction_id SET order_id = '" . (int) $result['order_id'] . "', transaction_id = '" . $this->db->escape($data->TransID) . "', pending_amount = '" . (int) (($result['total']) - ($data->TransAmount)) . "', amount = '" . (int) $data->TransAmount . "', customer_id = '" . $result['customer_id'] . "', created_at = NOW()";
                $query = $this->db->query($sql);

                $amount_partialy_paid = ((int) $result['amount_partialy_paid']) + ((int) $data->TransAmount);

                //$this->db->query('UPDATE `' . DB_PREFIX . "order` SET payment_method = 'mPesa On Delivery', payment_code = 'mod', paid = 'P', amount_partialy_paid = '" . (int) ((int) $result['amount_partialy_paid']) + ((int) $data->TransAmount) . "', date_modified = NOW() WHERE order_id = '" . (int) $result['order_id'] . "'");
                $this->db->query('UPDATE ' . DB_PREFIX . 'order SET payment_method="mPesa On Delivery",payment_code="mod",paid="P",amount_partialy_paid ="' . (int) $amount_partialy_paid . '",date_modified=NOW() where  order_id = "' . (int) $result['order_id'] . '"');

                // Add to activity log
                $this->load->model('account/activity');

                $activity_data = [
                    'customer_id' => $result['customer_id'],
                    'name' => $result['firstname'] . ' ' . $result['lastname'],
                    'order_id' => $result['order_id']
                ];
                $log->write('PAYBILL');

                $this->addActivity('PAYBILL', $activity_data);

                $log->write('PAYBILL');
            }

            if (isset($result) && $result != NULL && $data->TransAmount > $result['total'] && $result['paid'] == 'N') {
                $log->write('TOTAL IS LESS THAN TRANSACTION AMOUNT' . $result['order_id']);

                $sql = 'INSERT into ' . DB_PREFIX . "order_transaction_id SET order_id = '" . $result['order_id'] . "', transaction_id = '" . $this->db->escape($data->TransID) . "', amount = '" . (int) $data->TransAmount . "', customer_id = '" . $result['customer_id'] . "', created_at = NOW()";
                $query = $this->db->query($sql);

                $this->db->query('UPDATE `' . DB_PREFIX . "order` SET payment_method = 'mPesa On Delivery', payment_code = 'mod', paid = 'Y', amount_partialy_paid = 0, date_modified = NOW() WHERE order_id = '" . (int) $result['order_id'] . "'");

                // Add to activity log
                $this->load->model('account/activity');

                $activity_data = [
                    'customer_id' => $result['customer_id'],
                    'name' => $result['firstname'] . ' ' . $result['lastname'],
                    'order_id' => $result['order_id']
                ];
                $log->write('PAYBILL');

                $this->addActivity('PAYBILL', $activity_data);

                $log->write('PAYBILL');
            }
        }
    }

    public function addActivity($key, $data) {
        if (isset($data['customer_id'])) {
            $customer_id = $data['customer_id'];
        } else {
            $customer_id = 0;
        }

        if (isset($data['order_id'])) {
            $order_id = $data['order_id'];
        } else {
            $order_id = 0;
        }

        $this->db->query('INSERT INTO `' . DB_PREFIX . "customer_activity` SET `customer_id` = '" . (int) $customer_id . "', `key` = '" . $this->db->escape($key) . "', `data` = '" . $this->db->escape(serialize($data)) . "', `ip` = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', `date_added` = NOW(), `order_id` = '" . (int) $order_id . "'");
    }

}
