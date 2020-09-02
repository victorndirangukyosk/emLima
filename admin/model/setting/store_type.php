<?php

class ModelSettingStoreType extends Model
{
    public function addStoreType($data)
    {
        /*$sql = "INSERT INTO " . DB_PREFIX . "help SET name='".$this->db->escape($data['name'])."',answer='".$this->db->escape($data['answer'])."', category_id='".(int)$this->db->escape($data['category_id'])."',sort_order = '" . (int) $data['sort_order'] . "'";
        $this->db->query($sql);
        return $this->db->getLastId(); */

        //echo "<pre>";print_r($data['help']);die;
        foreach ($data['help'] as $language_id => $value) {
            if (isset($store_type_id)) {
                $sql = 'INSERT INTO '.DB_PREFIX."store_type SET name='".$this->db->escape($value['name'])."',store_type_id = '".(int) $store_type_id."',language_id = '".(int) $language_id."',sort_order = '".(int) $value['sort_order']."'";

                $this->db->query($sql);
            } else {
                $sql = 'INSERT INTO '.DB_PREFIX."store_type SET name='".$this->db->escape($value['name'])."',language_id = '".(int) $language_id."',sort_order = '".(int) $value['sort_order']."'";

                $this->db->query($sql);

                $store_type_id = $this->db->getLastId();
            }
        }

        $this->cache->delete('store_type');

        return $store_type_id;
    }

    public function editStoreType($store_type_id, $data)
    {
        /* $this->db->query("UPDATE " . DB_PREFIX . "help SET name='".$this->db->escape($data['name'])."',answer='".$this->db->escape($data['answer'])."', category_id='".(int)$this->db->escape($data['category_id'])."',sort_order = '" . (int) $data['sort_order'] . "' WHERE store_type_id = '" . (int) $store_type_id . "'");*/

        $this->db->query('DELETE FROM '.DB_PREFIX."store_type WHERE store_type_id = '".(int) $store_type_id."'");

        foreach ($data['help'] as $language_id => $value) {
            //print_r($value);

            $sql = 'INSERT INTO '.DB_PREFIX."store_type SET name='".$this->db->escape($value['name'])."',store_type_id = '".(int) $store_type_id."',language_id = '".(int) $language_id."',sort_order = '".(int) $value['sort_order']."'";

            $this->db->query($sql);
        }

        $this->cache->delete('store_type');
    }

    public function deleteStoreType($store_type_id)
    {
        $this->db->query('DELETE FROM '.DB_PREFIX."store_type WHERE store_type_id = '".(int) $store_type_id."'");
    }

    public function getStoreType($store_type_id)
    {
        return $this->db->query('SELECT * FROM '.DB_PREFIX."store_type WHERE store_type_id = '".(int) $store_type_id."'")->row;
    }

    public function getStoreTypeDetails($store_type_id)
    {
        $category_data = [];

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."store_type WHERE store_type_id = '".(int) $store_type_id."'");

        foreach ($query->rows as $result) {
            $category_data[$result['language_id']] = [
                    'name' => $result['name'],
                    'sort_order' => $result['sort_order'],
                    'store_type_id' => $result['store_type_id'],
                ];
        }

        return $category_data;
    }

    public function getStoreTypes($data = [])
    {
        $sql = 'SELECT * from `'.DB_PREFIX.'store_type`';

        $where = 0;
        if (!empty($data['filter_name'])) {
            $where = 1;
            $sql .= " WHERE name LIKE '".$this->db->escape($data['filter_name'])."%'";
        }

        if ($where) {
            $sql .= " And language_id = '".(int) $this->config->get('config_language_id')."'";
        } else {
            $sql .= " WHERE language_id = '".(int) $this->config->get('config_language_id')."'";
        }

        $sort_data = [
            'name',
            'sort_order',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY '.$data['sort'];
        } else {
            $sql .= ' ORDER BY sort_order';
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

            $sql .= ' LIMIT '.(int) $data['start'].','.(int) $data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalStoreType()
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX."store_type where language_id = '".(int) $this->config->get('config_language_id')."'");

        return $query->row['total'];
    }

    public function getTotalStoreTypeFilter($data)
    {
        $sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX."store_type where language_id = '".(int) $this->config->get('config_language_id')."'";

        if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
            $sql .= "and    name LIKE '".$this->db->escape($data['filter_name'])."%'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }
}
