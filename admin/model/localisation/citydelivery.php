<?php

class ModelLocalisationCitydelivery extends Model {

    public function editCitydelivery($city_id, $data) {
        $log = new Log('error.log');

        $monday = in_array("monday", $data['city_delivery']) ? 1 : 0;
        $tuesday = in_array("tuesday", $data['city_delivery']) ? 1 : 0;
        $wednesday = in_array("wednesday", $data['city_delivery']) ? 1 : 0;
        $thursday = in_array("thursday", $data['city_delivery']) ? 1 : 0;
        $friday = in_array("friday", $data['city_delivery']) ? 1 : 0;
        $saturday = in_array("saturday", $data['city_delivery']) ? 1 : 0;
        $sunday = in_array("sunday", $data['city_delivery']) ? 1 : 0;

        $query = $this->db->query('SELECT DISTINCT * FROM ' . DB_PREFIX . "city_delivery WHERE city_id = '" . (int) $city_id . "'");
        if ($query->num_rows > 0) {
            $this->db->query('UPDATE ' . DB_PREFIX . "city_delivery SET monday = '" . $monday . "', tuesday = '" . $tuesday . "', wednesday = '" . $wednesday . "', thursday = '" . $thursday . "', friday = '" . $friday . "', saturday = '" . $saturday . "', sunday = '" . $sunday . "', updated_at = NOW() WHERE city_id = '" . (int) $city_id . "'");
        } else {
            $this->db->query('INSERT INTO ' . DB_PREFIX . "city_delivery SET city_id = '" . $city_id . "', monday = '" . $monday . "', tuesday = '" . $tuesday . "', wednesday = '" . $wednesday . "', thursday = '" . $thursday . "', friday = '" . $friday . "', saturday = '" . $saturday . "', sunday = '" . $sunday . "', created_at = NOW()");
        }
    }

    public function getCityDelivery($city_id) {
        $query = $this->db->query('SELECT DISTINCT * FROM ' . DB_PREFIX . "city_delivery WHERE city_id = '" . (int) $city_id . "'");

        return $query->row;
    }

}
