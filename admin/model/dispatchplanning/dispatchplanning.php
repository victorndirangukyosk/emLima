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

    public function getUnAssignedVehicles($delivery_date, $delivery_timeslot) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "dispatch_assignment WHERE delivery_date = '" . $delivery_date . "' AND delivery_time_slot = '" . $delivery_timeslot . "'");
        $selected_vehicles = $query->rows;
        if (count($selected_vehicles) > 0) {
            $vehicles = array_column($selected_vehicles, 'vehicle_id');
            $log = new Log('error.log');
            $log->write($vehicles);
            $log->write($selected_vehicles);
            $vehicles = implode(',', $vehicles);
            $query_vehicles = $this->db->query('SELECT * FROM ' . DB_PREFIX . "vehicles WHERE vehicle_id NOT IN (" . $vehicles . ")");
            $un_assigned_vehicles = $query_vehicles->rows;
            $log->write($vehicles);
            $log->write($un_assigned_vehicles);
            $log->write('SELECT * FROM ' . DB_PREFIX . "vehicles WHERE vehicle_id NOT IN (" . $vehicles . ")");
        } else {
            $query_vehicles = $this->db->query('SELECT * FROM ' . DB_PREFIX . "vehicles");
            $un_assigned_vehicles = $query_vehicles->rows;
        }
        return $un_assigned_vehicles;
    }

    public function getUnAssignedDeliveryExecutives($delivery_date, $delivery_timeslot) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "dispatch_assignment WHERE delivery_date = '" . $delivery_date . "' AND delivery_time_slot = '" . $delivery_timeslot . "'");
        $selected_executives = $query->rows;
        if (count($selected_executives) > 0) {
            $executives = array_column($selected_executives, 'delivery_executive_id');
            $log = new Log('error.log');
            $log->write($executives);
            $log->write($executives);
            $executives = implode(',', $executives);
            $query_executives = $this->db->query('SELECT * FROM ' . DB_PREFIX . "delivery_executives WHERE delivery_executive_id NOT IN (" . $executives . ")");
            $un_assigned_executives = $query_executives->rows;
        } else {
            $query_executives = $this->db->query('SELECT * FROM ' . DB_PREFIX . "delivery_executives");
            $un_assigned_executives = $query_executives->rows;
        }
        return $un_assigned_executives;
    }

    public function getUnAssignedDriver($delivery_date, $delivery_timeslot) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "dispatch_assignment WHERE delivery_date = '" . $delivery_date . "' AND delivery_time_slot = '" . $delivery_timeslot . "'");
        $selected_drivers = $query->rows;
        if (count($selected_drivers) > 0) {
            $drivers = array_column($selected_drivers, 'driver_id');
            $log = new Log('error.log');
            $log->write($drivers);
            $log->write($drivers);
            $drivers = implode(',', $drivers);
            $query_drivers = $this->db->query('SELECT * FROM ' . DB_PREFIX . "drivers WHERE driver_id NOT IN (" . $drivers . ")");
            $un_assigned_drivers = $query_drivers->rows;
        } else {
            $query_drivers = $this->db->query('SELECT * FROM ' . DB_PREFIX . "drivers");
            $un_assigned_drivers = $query_drivers->rows;
        }
        return $un_assigned_drivers;
    }

    public function getAssignedVehicles($delivery_date, $delivery_timeslot) {
        $un_assigned_vehicles = array();
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "dispatch_assignment WHERE delivery_date = '" . $delivery_date . "' AND delivery_time_slot = '" . $delivery_timeslot . "'");
        $selected_vehicles = $query->rows;

        if (count($selected_vehicles) > 0) {
            $vehicles = array_column($selected_vehicles, 'vehicle_id');
            $log = new Log('error.log');
            $log->write($vehicles);
            $log->write($selected_vehicles);
            $vehicles = implode(',', $vehicles);
            $query_vehicles = $this->db->query('SELECT * FROM ' . DB_PREFIX . "vehicles WHERE vehicle_id IN (" . $vehicles . ")");
            $un_assigned_vehicles = $query_vehicles->rows;
            $log->write($vehicles);
            $log->write($un_assigned_vehicles);
        }

        return $un_assigned_vehicles;
    }

    public function getAssignedVehiclesByVehicle($delivery_date, $delivery_timeslot, $vehicle) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "dispatch_assignment WHERE delivery_date = '" . $delivery_date . "' AND delivery_time_slot = '" . $delivery_timeslot . "' AND vehicle_id = '" . $vehicle . "'");
        $selected_vehicles = $query->row;
        return $selected_vehicles;
    }

}
