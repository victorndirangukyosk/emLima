<?php

class ModelVehiclesVehicles extends Model {

    public function addVehicle($data) {
        // echo "<pre>";print_r('INSERT INTO ' . DB_PREFIX . "vehicles SET make = '" . $this->db->escape($data['make']) . "', model = '" . $this->db->escape($data['model']) . "', registration_number = '" . $this->db->escape($data['registration_number']) . "', registration_validity = '" . $this->db->escape($data['registration_validity_upto']) . "', registration_date = '" . $this->db->escape($data['registration_date']) . "', status = '" . (int) $data['status'] . "', date_added = NOW()");die;
        
        $this->db->query('INSERT INTO ' . DB_PREFIX . "vehicles SET make = '" . $this->db->escape($data['make']) . "', model = '" . $this->db->escape($data['model']) . "', registration_number = '" . $this->db->escape($data['registration_number']) . "', registration_validity = '" . $this->db->escape($data['registration_validity_upto']) . "', registration_date = '" . $this->db->escape($data['registration_date']) . "', status = '" . (int) $data['status'] . "', date_added = NOW()");
        $vehicle_id = $this->db->getLastId();
        return $vehicle_id;
    }

    public function editVehicle($vehicle_id, $data) {
        $this->db->query('UPDATE ' . DB_PREFIX . "vehicles SET make = '" . $this->db->escape($data['make']) . "', model = '" . $this->db->escape($data['model']) . "', registration_number = '" . $this->db->escape($data['registration_number']) . "', registration_validity = '" . $this->db->escape($data['registration_validity_upto']) . "', status = '" . (int) $data['status'] . "', registration_date = '" . $data['registration_date'] . "' WHERE vehicle_id = '" . (int) $vehicle_id . "'");
    }

    public function deleteVehicle($vehicle_id) {
        $this->db->query('DELETE FROM ' . DB_PREFIX . "vehicles WHERE vehicle_id = '" . (int) $vehicle_id . "'");
    }

    public function getVehicle($vehicle_id) {
        $query = $this->db->query('SELECT DISTINCT * FROM ' . DB_PREFIX . "vehicles WHERE vehicle_id = '" . (int) $vehicle_id . "'");

        return $query->row;
    }

    public function getVehicleByNumber($registration_number) {
        $query = $this->db->query('SELECT DISTINCT * FROM ' . DB_PREFIX . "vehicles WHERE registration_number = '" . $this->db->escape($registration_number) . "'");

        return $query->row;
    }

    public function getVehicles($data = []) {
        $sql = "SELECT * FROM " . DB_PREFIX . 'vehicles c';

        $implode = [];

        if (!empty($data['filter_make'])) {
            $implode[] = "c.make = '" . $this->db->escape($data['filter_make']) . "'";
        }

        if (!empty($data['filter_model'])) {
            $implode[] = "c.model = '" . $this->db->escape($data['filter_model']) . "'";
        }

        if (!empty($data['filter_registration_number'])) {
            $implode[] = "c.registration_number = '" . $this->db->escape($data['filter_registration_number']) . "'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "c.status = '" . (int) $data['filter_status'] . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if ($implode) {
            $sql .= ' WHERE ' . implode(' AND ', $implode);
        }

        $sort_data = [
            'c.make',
            'c.model',
            'c.registration_number',
            'c.registration_validity',
            'c.registration_date',
            'c.status',
            'c.date_added',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY date_added';
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

        //echo "<pre>";print_r($sql);die;

        return $query->rows;
    }

    public function getTotalVehicles($data = []) {
        $sql = 'SELECT COUNT(*) AS total FROM ' . DB_PREFIX . 'vehicles c';

        $implode = [];

        if (!empty($data['filter_make'])) {
            $implode[] = "c.make = '" . $this->db->escape($data['filter_make']) . "'";
        }

        if (!empty($data['filter_model'])) {
            $implode[] = "c.model = '" . $this->db->escape($data['filter_model']) . "'";
        }

        if (!empty($data['filter_registration_number'])) {
            $implode[] = "c.registration_number = '" . $this->db->escape($data['filter_registration_number']) . "'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "c.status = '" . (int) $data['filter_status'] . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if ($implode) {
            $sql .= ' WHERE ' . implode(' AND ', $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }


    public function getAllVehicles($data = []) {
        $sql = "SELECT vehicle_id, registration_number as name FROM " . DB_PREFIX . 'vehicles c';

        $implode = [];

        if (!empty($data['filter_make'])) {
            $implode[] = "c.make = '" . $this->db->escape($data['filter_make']) . "'";
        }

        if (!empty($data['filter_model'])) {
            $implode[] = "c.model = '" . $this->db->escape($data['filter_model']) . "'";
        }

        if (!empty($data['filter_registration_number'])) {
            $implode[] = "c.registration_number = '" . $this->db->escape($data['filter_registration_number']) . "'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "c.status = '" . (int) $data['filter_status'] . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if ($implode) {
            $sql .= ' WHERE ' . implode(' AND ', $implode);
        }

        $sort_data = [
            'c.make',
            'c.model',
            'c.registration_number',
            'c.registration_validity',
            'c.registration_date',
            'c.status',
            'c.date_added',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY date_added';
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

        // echo "<pre>";print_r($sql);die;

        return $query->rows;
    }

}
