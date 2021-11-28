<?php

class ModelSettingStoreGroup extends Model
{
    public function addStoreGroup($data)
    {
        $query = 'INSERT INTO '.DB_PREFIX."store_groups SET name = '".$this->db->escape($data['name'])."', stores='".implode(',', $data['stores'])."', logo='".$data['logo']."',status='".$data['status']."'";

        $this->db->query($query);

        $id = $this->db->getLastId();

        $alias = empty($data['seo_url']) ? $data['name'] : $data['seo_url'];

        $alias = $this->model_catalog_url_alias->generateAlias($alias);

        if ($alias) {
            $this->db->query('INSERT INTO '.DB_PREFIX."url_alias SET query = 'store_group_id=".(int) $id."', keyword = '".$this->db->escape($alias)."' ,language_id = '1' ");
        }

        return $id;
    }

    public function editStoreGroup($id, $data)
    {
        $query = 'UPDATE '.DB_PREFIX."store_groups SET name = '".$this->db->escape($data['name'])."', stores='".implode(',', $data['stores'])."', logo='".$data['logo']."',status='".$data['status']."' where id=".$id;

        $this->db->query($query);

        $this->db->query('DELETE FROM '.DB_PREFIX."url_alias WHERE query = 'store_group_id=".(int) $id."'");

        $alias = empty($data['seo_url']) ? $data['name'] : $data['seo_url'];

        $alias = $this->model_catalog_url_alias->generateAlias($alias);

        if ($alias) {
            $this->db->query('INSERT INTO '.DB_PREFIX."url_alias SET query = 'store_group_id=".(int) $id."', keyword = '".$this->db->escape($alias)."' ,language_id = '1' ");
        }

        return $id;
    }

    public function deleteStoreGroup($id)
    {
        $this->db->query('DELETE FROM '.DB_PREFIX."store_groups WHERE id = '".(int) $id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."url_alias WHERE query = 'store_group_id=".(int) $id."'");
    }

    public function getStoreGroupData($id)
    {
        $query = $this->db->query('SELECT DISTINCT * FROM '.DB_PREFIX."store_groups WHERE id = '".(int) $id."'");

        $store = $query->row;

        //echo "<pre>";print_r("SELECT DISTINCT * FROM " . DB_PREFIX . "store WHERE store_id = '" . (int) $store_id . "'");die;

        if (count($store) > 0) {
            $store['seo_url'] = '';

            $rows = $this->db->query('SELECT keyword, language_id FROM '.DB_PREFIX."url_alias WHERE query = 'store_group_id=".(int) $id."'")->row;

            if ($rows) {
                $store['seo_url'] = $rows['keyword'];
            }
        }

        return $store;
    }

    public function getStoreGroup($data = [])
    {
        $sql = 'SELECT *  FROM '.DB_PREFIX.'store_groups sg';

        $implode = [];

        if (!empty($data['filter_name'])) {
            $implode[] = "sg.name LIKE '".$this->db->escape($data['filter_name'])."%'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "sg.status = '".$this->db->escape($data['filter_status'])."'";
        }

        if ($implode) {
            $sql .= ' WHERE '.implode(' AND ', $implode);
        }

        $sort_data = [
            'sg.name',
            'sg.status',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY '.$this->db->escape($data['sort']);
        } else {
            $sql .= ' ORDER BY sg.name';
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

        return $this->db->query($sql)->rows;
    }

    public function getTotalStoreGroup($data = [])
    {
        $sql = 'SELECT COUNT(*) as total  FROM '.DB_PREFIX.'store_groups sg';

        $implode = [];

        if (!empty($data['filter_name'])) {
            $implode[] = "sg.name LIKE '".$this->db->escape($data['filter_name'])."%'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "sg.status = '".$this->db->escape($data['filter_status'])."'";
        }

        if ($implode) {
            $sql .= ' WHERE '.implode(' AND ', $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }
}
