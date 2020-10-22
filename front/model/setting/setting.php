<?php

class ModelSettingSetting extends Model
{
    public function getSetting($code, $store_id = 0)
    {
        $data = [];

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."setting WHERE store_id = '".(int) $store_id."' AND `code` = '".$this->db->escape($code)."'");

        foreach ($query->rows as $result) {
            if (!$result['serialized']) {
                $data[$result['key']] = $result['value'];
            } else {
                $data[$result['key']] = unserialize($result['value']);
            }
        }

        return $data;
    }

    public function getUrlAlias($part)
    {
        return $this->db->query('SELECT * FROM '.DB_PREFIX."url_alias WHERE keyword = '".$this->db->escape($part)."'");
    }

    public function getUrlAliasKeyValue($key, $value)
    {
        return $this->db->query('SELECT * FROM '.DB_PREFIX."url_alias WHERE `query` = '".$this->db->escape($key.'='.(int) $value)."'");
    }

    public function getUrlAliasByCatogoryId($category)
    {
        return $this->db->query('SELECT * FROM '.DB_PREFIX."url_alias WHERE `query` = 'category_id=".(int) $category."'");
    }

    public function getUrlAliasByPathAndId($path, $id)
    {
        return $this->db->query('SELECT * FROM '.DB_PREFIX."url_alias WHERE `query` = '".$this->db->escape($path.'='.$id)."'");
    }

    public function getUser($user_id)
    {
        return $this->db->query('select * from '.DB_PREFIX.'user where user_id="'.$user_id.'"')->row;
    }

    public function getUserStores($user_id, $data = [])
    {
        $sql = 'SELECT s.*, c.name as city FROM '.DB_PREFIX.'store s ';
        $sql .= 'LEFT JOIN `'.DB_PREFIX.'city` c on c.city_id = s.city_id ';

        if (!empty($data['filter_vendor'])) {
            $sql .= ' inner join `'.DB_PREFIX.'user` u on u.user_id = s.vendor_id';
        }

        $implode = [];

        //if ($this->user->isVendor()) {
        if (true) {
            $implode[] = "s.vendor_id = '".$user_id."'";
        }

        if (!empty($data['filter_vendor'])) {
            $implode[] = "CONCAT(u.firstname,' ',u.lastname) LIKE '".$this->db->escape($data['filter_vendor'])."%'";
        }

        if (!empty($data['filter_city'])) {
            $implode[] = "c.name LIKE '".$this->db->escape($data['filter_city'])."%'";
        }
        if (!empty($data['filter_name'])) {
            $implode[] = "s.name LIKE '".$this->db->escape($data['filter_name'])."%'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "s.date_added = '".$this->db->escape($data['filter_date_added'])."'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "s.status = '".$this->db->escape($data['filter_status'])."'";
        }

        if ($implode) {
            $sql .= ' WHERE '.implode(' AND ', $implode);
        }

        $sort_data = [
            's.name',
            's.status',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY '.$this->db->escape($data['sort']);
        } else {
            $sql .= ' ORDER BY s.name';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        /*if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
        }*/

        return $this->db->query($sql)->rows;
    }


    public function getEmailSetting($code)
    {
        
        $query = $this->db->query('SELECT value FROM '.DB_PREFIX."setting_email WHERE  `code` =  '".$code."'   ");
        //   echo "<pre>";print_r($query->row[value]);die;
        return $query->row[value];
    }
}
