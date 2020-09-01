<?php

class ModelExtensionExtension extends Model
{
    public function getExtensions($type)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."extension WHERE `type` = '".$this->db->escape($type)."'");

        return $query->rows;
    }

    public function getStoreData($store_id)
    {
        return $this->db->query('select store_id,name,min_order_amount,city_id,commision from `'.DB_PREFIX.'store` where status=1 and store_id="'.$store_id.'"')->row;
    }

    public function getStoreAllData($store_id)
    {
        return $this->db->query('select * from `'.DB_PREFIX.'store` where status=1 and store_id="'.$store_id.'"')->row;
    }

    public function getVendorId($store_id)
    {
        return $this->db->query('select vendor_id from `'.DB_PREFIX.'store` WHERE store_id="'.$store_id.'"')->row['vendor_id'];
    }

    public function getCities()
    {
        return $this->db->query('select * from `'.DB_PREFIX.'city` WHERE status=1 order by sort_order')->rows;
    }

    public function getCityById($city_id)
    {
        return $this->db->query('select * from `'.DB_PREFIX.'city` WHERE city_id="'.$city_id.'"')->row;
    }

    public function getCityByIdQuery($city_id)
    {
        return $this->db->query('select * from `'.DB_PREFIX.'city` WHERE city_id="'.$city_id.'"');
    }
}
