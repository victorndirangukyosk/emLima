<?php

class ModelApiCategories extends Model
{
    public function getCategory($data = [])
    {
        $sql = 'SELECT * FROM '.DB_PREFIX.'category c LEFT JOIN '.DB_PREFIX.'category_description cd ON (c.category_id = cd.category_id) LEFT JOIN '.DB_PREFIX.'category_to_store c2s ON (c.category_id = c2s.category_id)';

        $sql .= $this->getExtraConditions($data);

        $query = $this->db->query($sql);

        return $query->row;
    }

    public function getCategories($data = [])
    {
        $sql = 'SELECT * FROM '.DB_PREFIX.'category c LEFT JOIN '.DB_PREFIX.'category_description cd ON (c.category_id = cd.category_id) LEFT JOIN '.DB_PREFIX.'category_to_store c2s ON (c.category_id = c2s.category_id)';

        $sql .= $this->getExtraConditions($data);

        $sort_data = [
            'cd.name',
            'c.status',
            'c.sort_order',
            'c.date_added',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY '.$data['sort'];
        } else {
            $sql .= ' ORDER BY cd.name';
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

    public function getTotals($data = [])
    {
        $sql = 'SELECT COUNT(DISTINCT c.category_id) AS number FROM '.DB_PREFIX.'category c LEFT JOIN '.DB_PREFIX.'category_description cd ON (c.category_id = cd.category_id) LEFT JOIN '.DB_PREFIX.'category_to_store c2s ON (c.category_id = c2s.category_id)';

        $sql .= $this->getExtraConditions($data);

        $query = $this->db->query($sql);

        return $query->row;
    }

    private function getExtraConditions($data)
    {
        $sql = '';

        $implode = [];

        if (!empty($data['id'])) {
            $implode[] = "c.category_id = '".(int) $data['id']."'";
        }

        if (!empty($data['search'])) {
            $implode[] = "(cd.name LIKE '%".$this->db->escape($data['search'])."%' OR cd.description LIKE '%".$this->db->escape($data['search'])."%')";
        }

        if (!empty($data['status'])) {
            $implode[] = "c.status = '".(int) $data['status']."'";
        }

        if (!empty($data['parent_id'])) {
            $implode[] = "c.parent_id = '".(int) $data['parent_id']."'";
        }

        $implode[] = "cd.language_id = '".(int) $this->config->get('config_language_id')."'";

        $implode[] = "c2s.store_id = '".(int) $this->config->get('config_store_id')."'";

        if ($implode) {
            $sql .= ' WHERE '.implode(' AND ', $implode);
        }

        return $sql;
    }

    public function getCategoriesbyStore($data = [])
    {
        $sql = 'SELECT * FROM '.DB_PREFIX.'category_to_store';
        $query = $this->db->query($sql);
        $result = $query->rows;
        $filter = $data['store_id'];
        if (!empty($data['store_id'])) {
            $result = array_filter($result, function ($item) use ($filter) {
                if ($item['store_id'] == $filter) {
                    return true;
                }

                return false;
            });
        }

        $categoryids = array_column($result, 'category_id');
        // echo'<pre>';print_r($categoryids);exit;
        return $categoryids;
    }
}
