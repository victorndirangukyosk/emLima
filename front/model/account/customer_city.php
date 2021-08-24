<?php

class ModelAccountCustomerCity extends Model
{
    public function getCity($customer_city_id)
    {
        $query = $this->db->query('SELECT DISTINCT * FROM '.DB_PREFIX."city c WHERE cg.city_id = '".(int) $customer_city_id."'");

        return $query->row;
    }

    public function getCities()
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."city c  ORDER BY c.city_id ASC");

        // echo "<pre>";print_r('SELECT * FROM '.DB_PREFIX."city c  ORDER BY c.city_id ASC"); die;

        // echo
        return $query->rows;
    }
}
