<?php

class ModelLocalisationCity extends Model {

    public function addCity($data) {
        $this->db->query('INSERT INTO ' . DB_PREFIX . "city SET name = '" . $this->db->escape($data['name']) . "', status = '" . $this->db->escape($data['status']) . "', state_id = '" . $this->db->escape($data['state_id']) . "', region_id = '" . $this->db->escape($data['region_id']) . "', sort_order = '" . $this->db->escape($data['sort_order']) . "'");
        $city_id = $this->db->getLastId();

        $this->db->query('INSERT INTO ' . DB_PREFIX . "city_delivery SET city_id = '" . $city_id . "', monday = 1, tuesday = 1, wednesday = 1, thursday = 1, friday = 1, saturday = 1, sunday = 1, created_at = NOW()");

        foreach ($data['city_zipcodes'] as $key => $value) {
            $this->db->query('INSERT INTO ' . DB_PREFIX . "city_zipcodes SET city_id = '" . (int) $city_id . "', zipcode = '" . $this->db->escape($value) . "'");
        }

        return $city_id;
    }

    public function editCity($city_id, $data) {
        $this->db->query('UPDATE ' . DB_PREFIX . "city SET name = '" . $this->db->escape($data['name']) . "', status = '" . $this->db->escape($data['status']) . "', state_id = '" . $this->db->escape($data['state_id']) . "', region_id = '" . $this->db->escape($data['region_id']) . "', sort_order = '" . $this->db->escape($data['sort_order']) . "' WHERE city_id = '" . (int) $city_id . "'");

        $this->db->query('DELETE FROM ' . DB_PREFIX . 'city_zipcodes WHERE city_id = ' . (int) $city_id);

        foreach ($data['city_zipcodes'] as $key => $value) {
            $this->db->query('INSERT INTO ' . DB_PREFIX . "city_zipcodes SET city_id = '" . (int) $city_id . "', zipcode = '" . $this->db->escape($value) . "'");
        }
    }

    public function deleteCity($city_id) {
        $this->db->query('DELETE FROM ' . DB_PREFIX . 'city WHERE city_id = ' . (int) $city_id);
    }

    public function getCity($city_id) {
        $query = $this->db->query('SELECT DISTINCT * FROM ' . DB_PREFIX . "city WHERE city_id = '" . (int) $city_id . "'");

        return $query->row;
    }

    public function getStates() {
        $query = $this->db->query('SELECT DISTINCT * FROM ' . DB_PREFIX . 'state');

        return $query->rows;
    }

    public function getCities($data = []) {
        $sql = 'SELECT * FROM ' . DB_PREFIX . 'city';

        $sort_data = [
            'name',
            'sort_order',
            'status',];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY name';
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

    public function getTotalCities() {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . 'city');

        return $query->row['total'];
    }

    public function getAllCities() {
        return $this->db->query('select * from `' . DB_PREFIX . 'city` order by sort_order')->rows;
    }

    public function getAllZipcodeByCity($city_id) {
        return $this->db->query('select * from `' . DB_PREFIX . 'city_zipcodes` where city_id="' . $city_id . '"')->rows;
    }

    public function getZipcodeFilteredByCity($like, $city_id) {
        return $this->db->query('select * from `' . DB_PREFIX . 'city_zipcodes` where city_id="' . $city_id . '" and zipcode LIKE "' . $like . '%"')->rows;
    }

}
