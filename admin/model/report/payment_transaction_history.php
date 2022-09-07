<?php

class ModelReportPaymentTransactionHistory extends Model
{
      

    public function getPamentTransactionHistory($data = [])
    {
        $sql = "SELECT ph.id,ph.order_id,ph.transaction_id,ph.amount_received,ph.partial_amount,ph.patial_amount_applied,ph.date_added,ph.ip,ph.credit_id,ph.added_by, concat(u.firstname,' ',u.lastname) as  user FROM `".DB_PREFIX.'payment_history` ph join hf7_user u on ph.added_by=u.user_id';

           
        // echo "<pre>";print_r($sql);die;

        if (!empty($data['filter_user']) && !empty($data['filter_user_id'])) {
            $sql .= " AND ph.added_by = '".(int) $data['filter_user_id']."'";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND ph.order_id = '".(int) $data['filter_order_id']."'";
        }

        if (!empty($data['filter_transaction_id'])) {
            $sql .= " AND ph.transaction_id = '".$data['filter_transaction_id']."'";
        }

       
        
        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(ph.date_added) >= DATE('".$this->db->escape($data['filter_date_added'])."')";
        }

        
        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(ph.date_added) <= DATE('".$this->db->escape($data['filter_date_modified'])."')";
        }

        

        // $sort_data = [
        //     'ph.order_id',      
        // ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY '.$data['sort'];
        } else {
            $sql .= ' ORDER BY ph.date_added';
        }

        // if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        // } else {
            // $sql .= ' ASC';
        // }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT '.(int) $data['start'].','.(int) $data['limit'];
        }

        $query = $this->db->query($sql);
        //    echo "<pre>";print_r($sql);die;
        return $query->rows;
    }

          
    public function getOrderExactTotal($order_id)
    {
        $query = $this->db->query('SELECT value FROM '.DB_PREFIX."order_total WHERE order_id = '".(int) $order_id."' and code='total' ORDER BY sort_order");

        return $query->row['value'];
    }
    public function getTotalOrders($data = [])
    {
        $sql = 'SELECT COUNT(*) AS total FROM `'.DB_PREFIX.'payment_history` ph join hf7_user u on ph.added_by=u.user_id ';

        
        if (!empty($data['filter_user']) && !empty($data['filter_user_id'])) {
            $sql .= " AND ph.added_by = '".(int) $data['filter_user_id']."'";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND ph.order_id = '".(int) $data['filter_order_id']."'";
        }

        if (!empty($data['filter_transaction_id'])) {
            $sql .= " AND ph.transaction_id = '".$data['filter_transaction_id']."'";
        }

       
        
        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(ph.date_added) >= DATE('".$this->db->escape($data['filter_date_added'])."')";
        }

        
        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(ph.date_added) <= DATE('".$this->db->escape($data['filter_date_modified'])."')";
        }

        // echo "<pre>";print_r($sql);die;
        
        $query = $this->db->query($sql);

        return $query->row['total'];
    }
               
    public function getUserData($filter_name)
    {
        $sql = 'select user_id, CONCAT(firstname," ",lastname) as name from `'.DB_PREFIX.'user`';
        $sql .= ' WHERE CONCAT(firstname," ",lastname) LIKE "'.$filter_name.'%" LIMIT 5';

        return $this->db->query($sql)->rows;
    }
      
}
