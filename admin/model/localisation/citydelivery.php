<?php

class ModelLocalisationCitydelivery extends Model {

    public function addCitydelivery($data) {
        $this->db->query('INSERT INTO ' . DB_PREFIX . "city SET name = '" . $this->db->escape($data['name']) . "', status = '" . $this->db->escape($data['status']) . "', state_id = '" . $this->db->escape($data['state_id']) . "', sort_order = '" . $this->db->escape($data['sort_order']) . "'");
        $city_id = $this->db->getLastId();

        foreach ($data['city_zipcodes'] as $key => $value) {
            $this->db->query('INSERT INTO ' . DB_PREFIX . "city_zipcodes SET city_id = '" . (int) $city_id . "', zipcode = '" . $this->db->escape($value) . "'");
        }

        return $city_id;
    }

    public function editCitydelivery($city_id, $data) {
        $this->db->query('UPDATE ' . DB_PREFIX . "city SET name = '" . $this->db->escape($data['name']) . "', status = '" . $this->db->escape($data['status']) . "', state_id = '" . $this->db->escape($data['state_id']) . "', sort_order = '" . $this->db->escape($data['sort_order']) . "' WHERE city_id = '" . (int) $city_id . "'");

        $this->db->query('DELETE FROM ' . DB_PREFIX . 'city_zipcodes WHERE city_id = ' . (int) $city_id);

        foreach ($data['city_zipcodes'] as $key => $value) {
            $this->db->query('INSERT INTO ' . DB_PREFIX . "city_zipcodes SET city_id = '" . (int) $city_id . "', zipcode = '" . $this->db->escape($value) . "'");
        }
    }

    public function getCityDelivery($city_id) {
        $query = $this->db->query('SELECT DISTINCT * FROM ' . DB_PREFIX . "city_delivery WHERE city_id = '" . (int) $city_id . "'");

        return $query->row;
    }

}
