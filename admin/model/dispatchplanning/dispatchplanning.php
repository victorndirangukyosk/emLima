<?php

class ModelDispatchplanningDispatchplanning extends Model {

    public function addVehicleToDispatchPlanning($data) {
        $this->db->query('INSERT INTO ' . DB_PREFIX . "dispatch_assignment SET driver_id = '" . $this->db->escape($data['driver']) . "', vehicle_id = '" . $this->db->escape($data['vehicle_id']) . "', delivery_executive_id = '" . $this->db->escape($data['delivery_executive']) . "', delivery_date = '" . $this->db->escape($data['delivery_date']) . "', delivery_time_slot = '" . $this->db->escape($data['delivery_timeslot']) . "'");
        $dispatch_id = $this->db->getLastId();
        return $dispatch_id;
    }

    public function CheckVehicleAssigned($vehicle_id, $delivery_date, $delivery_timeslot) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "dispatch_assignment WHERE vehicle_id = '" . (int) $vehicle_id . "' AND delivery_date = '" . $delivery_date . "' AND delivery_time_slot = '" . $delivery_timeslot . "'");
        return $query->row;
    }

    public function CheckDriverAssigned($driver_id, $delivery_date, $delivery_timeslot) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "dispatch_assignment WHERE driver_id = '" . (int) $driver_id . "' AND delivery_date = '" . $delivery_date . "' AND delivery_time_slot = '" . $delivery_timeslot . "'");
        return $query->row;
    }

    public function CheckDeliveryExecutiveAssigned($delivery_executive, $delivery_date, $delivery_timeslot) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "dispatch_assignment WHERE delivery_executive_id = '" . (int) $delivery_executive . "' AND delivery_date = '" . $delivery_date . "' AND delivery_time_slot = '" . $delivery_timeslot . "'");
        return $query->row;
    }

}
