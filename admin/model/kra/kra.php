<?php

class ModelKraKra extends Model {

    public function addKraActivity($key, $data) {

        if (isset($data['service'])) {
            $service = $data['service'];
        } else {
            $service = 0;
        }
        if (isset($data['response'])) {
            $response = $data['response'];
        } else {
            $response = 0;
        }
        if (isset($data['order_id'])) {
            $order_id = $data['order_id'];
        } else {
            $order_id = 0;
        }

        $this->db->query('INSERT INTO `' . DB_PREFIX . "kra_orders` SET `order_id` = '" . (int) $order_id . "', `service` = '" . $service . "', `response` = '" . $response . "'");
        return $this->db->getLastId();
    }

}
