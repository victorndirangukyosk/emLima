<?php

class ModelLocalisationRegion extends Model {

    public function addRegion($data) {

        $monday = in_array("monday", $data['city_delivery']) ? 1 : 0;
        $tuesday = in_array("tuesday", $data['city_delivery']) ? 1 : 0;
        $wednesday = in_array("wednesday", $data['city_delivery']) ? 1 : 0;
        $thursday = in_array("thursday", $data['city_delivery']) ? 1 : 0;
        $friday = in_array("friday", $data['city_delivery']) ? 1 : 0;
        $saturday = in_array("saturday", $data['city_delivery']) ? 1 : 0;
        $sunday = in_array("sunday", $data['city_delivery']) ? 1 : 0;

        $this->db->query('INSERT INTO ' . DB_PREFIX . "regions SET region_name = '" . $this->db->escape($data['name']) . "', status = '" . $this->db->escape($data['status']) . "', sort_order = '" . $this->db->escape($data['sort_order']) . "', monday = '" . $monday . "', tuesday = '" . $tuesday . "', wednesday = '" . $wednesday . "', thursday = '" . $thursday . "', friday = '" . $friday . "', saturday = '" . $saturday . "', sunday = '" . $sunday . "', created_at = NOW()");
        $state_id = $this->db->getLastId();
        return $state_id;
    }

    public function editRegion($region_id, $data) {

        $monday = in_array("monday", $data['city_delivery']) ? 1 : 0;
        $tuesday = in_array("tuesday", $data['city_delivery']) ? 1 : 0;
        $wednesday = in_array("wednesday", $data['city_delivery']) ? 1 : 0;
        $thursday = in_array("thursday", $data['city_delivery']) ? 1 : 0;
        $friday = in_array("friday", $data['city_delivery']) ? 1 : 0;
        $saturday = in_array("saturday", $data['city_delivery']) ? 1 : 0;
        $sunday = in_array("sunday", $data['city_delivery']) ? 1 : 0;
        
        $this->db->query('UPDATE ' . DB_PREFIX . "regions SET region_name = '" . $this->db->escape($data['name']) . "', status = '" . $this->db->escape($data['status']) . "', sort_order = '" . $this->db->escape($data['sort_order']) . "', monday = '" . $monday . "', tuesday = '" . $tuesday . "', wednesday = '" . $wednesday . "', thursday = '" . $thursday . "', friday = '" . $friday . "', saturday = '" . $saturday . "', sunday = '" . $sunday . "', updated_at = NOW() WHERE region_id = '" . (int) $region_id . "'");
    }

    public function deleteRegion($region_id) {
        $this->db->query('DELETE FROM ' . DB_PREFIX . 'regions WHERE state_id = ' . (int) $region_id);
    }

    public function getRegion($region_id) {
        $query = $this->db->query('SELECT DISTINCT * FROM ' . DB_PREFIX . "regions WHERE region_id = '" . (int) $region_id . "'");
        return $query->row;
    }

    public function getRegions($data = []) {
        $sql = 'SELECT * FROM ' . DB_PREFIX . 'regions';

        $sort_data = [
            'region_name',
            'sort_order',
            'status',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY region_name';
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

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalRegions() {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . 'regions');

        return $query->row['total'];
    }

    public function getAllRegions() {
        return $this->db->query('select * from `' . DB_PREFIX . 'regions` order by sort_order')->rows;
    }

}
