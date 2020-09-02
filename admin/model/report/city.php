<?php

class ModelReportCity extends Model
{
    public function getTotalOrder($city_id)
    {
        return $this->db->query('select count(*) as total from `'.DB_PREFIX.'order` WHERE shipping_city_id="'.$city_id.'"')->row['total'];
    }

    public function getTotalShopper($city_id)
    {
        $ids = $this->config->get('config_shopper_group_ids');

        return $this->db->query('select count(*) as total from `'.DB_PREFIX.'user` WHERE user_group_id IN ('.$ids.') AND city_id="'.$city_id.'"')->row['total'];
    }

    public function getTotalStore($city_id)
    {
        return $this->db->query('select count(*) as total from `'.DB_PREFIX.'store` WHERE city_id="'.$city_id.'"')->row['total'];
    }

    public function getTotalVendor($city_id)
    {
        $ids = $this->config->get('config_vendor_group_ids');

        return $this->db->query('select count(*) as total from `'.DB_PREFIX.'user` WHERE user_group_id IN ('.$ids.') AND city_id="'.$city_id.'"')->row['total'];
    }

    public function getCities()
    {
        return $this->db->query('select name, city_id from `'.DB_PREFIX.'city` WHERE status=1 order by sort_order')->rows;
    }
}
