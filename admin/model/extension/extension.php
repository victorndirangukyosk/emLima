<?php

class ModelExtensionExtension extends Model
{
    public function getExtensions($type)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."extension WHERE `type` = '".$this->db->escape($type)."'");

        return $query->rows;
    }

    public function getInstalled($type)
    {
        $extension_data = [];

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."extension WHERE `type` = '".$this->db->escape($type)."' ORDER BY code");

        foreach ($query->rows as $result) {
            $extension_data[] = $result['code'];
        }

        return $extension_data;
    }

    public function install($type, $code)
    {
        $this->db->query('INSERT INTO '.DB_PREFIX."extension SET `type` = '".$this->db->escape($type)."', `code` = '".$this->db->escape($code)."'");
    }

    public function uninstall($type, $code)
    {
        $this->db->query('DELETE FROM '.DB_PREFIX."extension WHERE `type` = '".$this->db->escape($type)."' AND `code` = '".$this->db->escape($code)."'");
    }

    public function getStoreData($store_id)
    {
        return $this->db->query('select store_id,name,min_order_amount,city_id,commision from `'.DB_PREFIX.'store` where status=1 and store_id="'.$store_id.'"')->row;
    }

    public function getStoreAllData($store_id)
    {
        return $this->db->query('select * from `'.DB_PREFIX.'store` where status=1 and store_id="'.$store_id.'"')->row;
    }
}
