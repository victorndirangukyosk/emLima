<?php

class ModelInformationCareers extends Model {

    public function createCareers($first_name, $last_name, $role, $yourself,$email,$phone,$job_id,$cover,$cv_path,$jobposition) {

        $this->db->query('INSERT INTO `' . DB_PREFIX . "careers` SET `first_name` = '" . $first_name . "', `last_name` = '" . $last_name . "', `role` = '" . $role . "', `your_self` = '" . $yourself . "', `email` = '" . $email . "', `phone` = '" . $phone . "', `job_id` = '" . $job_id . "', `cover_letter` = '" . $cover . "', `cv_path` = '" . $cv_path . "', created_at = NOW()");
        
        $id=$this->db->getLastId();
       

        return $id;
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

    public function addOrderHistory($order_id, $order_status_id) {
        $notify = 1;
        $comment = '';
        $this->db->query('UPDATE `' . DB_PREFIX . "order` SET order_status_id = '" . (int) $order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");
        $this->db->query('INSERT INTO ' . DB_PREFIX . "order_history SET order_id = '" . (int) $order_id . "', order_status_id = '" . (int) $order_status_id . "', notify = '" . (int) $notify . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
    }

    public function OrderTransaction($order_id, $transaction_id) {
        $this->db->query('INSERT INTO ' . DB_PREFIX . "order_transaction_id SET order_id = '" . (int) $order_id . "', transaction_id = '" . $transaction_id . "'");
    }

    public function getPesapalOtherAmount($customer_id) {
        $result = $this->db->query('SELECT * FROM `' . DB_PREFIX . "pesapal_transactions` WHERE `customer_id` = '" . $customer_id . "' AND order_id IS NULL")->rows;
        return $result;
    }


    public function getJobPositions($data)
    {
        
        $sql = 'SELECT distinct *  FROM '.DB_PREFIX.'jobposition where status=1';
        $implode = [];

        if (!empty($data['filter_category'])) {
            $implode[] = " job_category LIKE '%".$this->db->escape($data['filter_category'])."%'";
        }
        if (!empty($data['filter_type'])) {
            $implode[] = " job_type LIKE '%".$this->db->escape($data['filter_type'])."%'";
        }
        if (!empty($data['filter_location'])) {
            $implode[] = " job_location LIKE '%".$this->db->escape($data['filter_location'])."%'";
        }
        if (!empty($data['id'])) {
            $implode[] = " job_id = ' ".$this->db->escape($data['id'])."'";
        }
        
        if ($implode) {
            $sql .= ' AND '.implode(' AND ', $implode);
        }


        $sql .= ' ORDER BY date_added DESC';
        // $sql .= ' LIMIT 0,9';
        $query = $this->db->query($sql);

        //   echo  $sql;die;
        return $query->rows;
    }

    public function getJobCategories()
    {
        
        $sql = 'SELECT distinct job_category  FROM '.DB_PREFIX.'jobposition where status=1';
        //$sql .= ' LIMIT 0,9';
        $query = $this->db->query($sql);
        // echo  $sql;die;
        return $query->rows;
    }
    public function getJobLocations()
    {
        
        $sql = 'SELECT distinct job_location  FROM '.DB_PREFIX.'jobposition where status=1';
        //$sql .= ' LIMIT 0,9';
        $query = $this->db->query($sql);
        // echo  $sql;die;
        return $query->rows;
    }

    public function getJobTypes()
    {
        
        $sql = 'SELECT distinct job_type  FROM '.DB_PREFIX.'jobposition where status=1';
        //$sql .= ' LIMIT 0,9';
        $query = $this->db->query($sql);
        // echo  $sql;die;
        return $query->rows;
    }



    public function saveCareers($first_name,$email,$phone,$job_id,$cover) {

        $this->db->query('INSERT INTO `' . DB_PREFIX . "careers` SET `first_name` = '" . $first_name . "', `last_name` = '', `role` = '', `your_self` = '', `email` = '" . $email . "', `phone` = '" . $phone . "', `job_id` = '" . $job_id . "', `cover_letter` = '" . $cover . "', `cv_path` = '', created_at = NOW()");
        
        $id=$this->db->getLastId();
       

        return $id;
    }



}
