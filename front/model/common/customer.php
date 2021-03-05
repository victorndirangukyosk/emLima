<?php

class ModelCommonCustomer extends Model {

    public function getValidCustomers($customer_id) {
        $sql = 'SELECT customer_id, company_name ,concat(firstname," ",lastname) as customer_name FROM ' . DB_PREFIX . "customer WHERE (customer_id = '" . (int) $customer_id . "' or parent='" . (int) $customer_id . "')";
          //echo "<pre>";print_r($sql);die;
          $query = $this->db->query($sql);
        return $query->rows;
    }
 

}
