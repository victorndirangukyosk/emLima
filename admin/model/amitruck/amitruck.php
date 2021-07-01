<?php

class ModelAmitruckAmitruck extends Model {

    public function addDelivery($order_id, $data, $request_type) {
        $this->db->query('INSERT INTO `' . DB_PREFIX . "amitruck_delivery_response` SET `order_id` = '" . (int) $order_id . "', `response` = '" . $data . "', `request_type` = '" . $request_type . "', `created_at` = NOW()");
    }

    public function addDeliveryStatus($order_id, $data) {
        $log = new Log('error.log');
        $data = json_decode($data, true);
        $log->write($data);
        $this->db->query('INSERT INTO `' . DB_PREFIX . "order_delivery` SET `order_id` = '" . (int) $order_id . "', `order_reference_id` = '" . $data['delivery']['id'] . "', `delivery_status` = '" . $data['delivery']['status'] . "', `pickup_latitude` = '" . $data['delivery']['stops'][0]['latitude'] . "', `pickup_longitude` = '" . $data['delivery']['stops'][0]['longitude'] . "', `pickup_location` = '" . $data['delivery']['stops'][0]['name'] . "', `drop_latitude` = '" . $data['delivery']['stops'][1]['latitude'] . "', `drop_longitude` = '" . $data['delivery']['stops'][1]['longitude'] . "', `drop_location` = '" . $data['delivery']['stops'][1]['name'] . "', `drop_location` = '" . $data['delivery']['totalDistance'] . "', `created_at` = NOW()");
    }

    public function updateDeliveryStatus($order_id, $data) {
        $log = new Log('error.log');
        $data = json_decode($data, true);
        $log->write($data);
        $this->db->query('UPDATE `' . DB_PREFIX . "order_delivery` SET `delivery_status` = '" . $data['deliveries'][0]['status'] . "', `delivery_charges` = '" . $data['deliveries'][0]['cost'] . "', `updated_at` = NOW() WHERE order_id = '" . (int) $order_id . "' AND order_reference_id = '" . $data['deliveries'][0]['id'] . "'");
    }

    public function updateOrderDelivery($order_id, $data) {
        $log = new Log('error.log');
        $data = json_decode($data, true);
        $log->write($data);
        $sql = 'UPDATE ' . DB_PREFIX . "order SET delivery_id = '" . $data['delivery']['id'] . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'";
        $query = $this->db->query($sql);
    }

}
