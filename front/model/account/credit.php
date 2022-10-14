<?php

class ModelAccountCredit extends Model {

    public function getCredits($data = []) {
        $sql = 'SELECT * FROM `' . DB_PREFIX . "customer_credit` WHERE customer_id = '" . (int) $this->customer->getId() . "'";

        $sort_data = [
            'amount',
            'description',
            'date_added',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY date_added';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalCredits() {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `' . DB_PREFIX . "customer_credit` WHERE customer_id = '" . (int) $this->customer->getId() . "'");

        return $query->row['total'];
    }

    public function getTotalAmount() {


        $query = $this->db->query('SELECT SUM(amount) AS total FROM `' . DB_PREFIX . "customer_credit` WHERE customer_id = '" . (int) $this->customer->getId() . "' GROUP BY customer_id");
        // echo "<pre>";print_r('SELECT SUM(amount) AS total FROM `'.DB_PREFIX."customer_credit` WHERE customer_id = '".(int) $this->customer->getId()."' GROUP BY customer_id");die;

        if ($query->num_rows) {
            //   echo "<pre>";print_r($query->row['total']);die;

            return $query->row['total'];
        } else {
            return 0;
        }
    }

    public function getTotalAmountOfParent($parent_id) {
        $query = $this->db->query('SELECT SUM(amount) AS total FROM `' . DB_PREFIX . "customer_credit` WHERE  customer_id = '" . (int) $parent_id . "'  GROUP BY customer_id"); //customer_id = '".(int) $this->customer->getId()."' ||
        // echo "<pre>";print_r('SELECT SUM(amount) AS total FROM `'.DB_PREFIX."customer_credit` WHERE customer_id = '".(int) $parent_id."' GROUP BY customer_id");die;

        if ($query->num_rows) {
            //   echo "<pre>";print_r($query->row['total']);die;

            return $query->row['total'];
        } else {
            return 0;
        }
    }

    public function addCustomerCredit($customer_id, $description, $amount, $transaction_id, $pesapal_merchant_reference, $order_id = 0) {
        $this->db->query('INSERT INTO ' . DB_PREFIX . "customer_credit SET customer_id = '" . (int) $customer_id . "', order_id = '" . (int) $order_id . "', description = '" . $this->db->escape($description) . "', amount = '" . (float) $amount . "', transaction_id = '" . $transaction_id . "', date_added = NOW()");
    }

    public function addCustomerCredits($customer_id, $description, $amount, $transaction_id, $pesapal_merchant_reference, $order_id = 0) {
        $this->db->query('INSERT INTO ' . DB_PREFIX . "order_transaction_id SET customer_id = '" . (int) $customer_id . "', amount = '" . (float) $amount . "', order_id = '" . (int) $order_id . "', transaction_id = '" . $transaction_id . "', merchant_request_id = '" . $pesapal_merchant_reference . "'");
    }

}
