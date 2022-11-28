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

    public function addKraDetails($order_id, $invoice_number = NULL, $qr_code = NULL, $serial_number = NULL, $pin_number = NULL) {
        $query = $this->db->query('SELECT order_id FROM ' . DB_PREFIX . "order_kra_details WHERE order_id = '" . (int) $order_id . "'");
        $count = $query->num_rows;

        if ($count > 0 && $invoice_number != NULL && $qr_code != NULL) {
            $this->db->query('UPDATE ' . DB_PREFIX . "order_kra_details SET invoice_number = '" . $invoice_number . "', qr_code = '" . $qr_code . "' WHERE order_id = '" . (int) $order_id . "'");
        }

        if ($count <= 0 && $invoice_number != NULL && $qr_code != NULL) {
            $this->db->query('INSERT INTO `' . DB_PREFIX . "order_kra_details` SET `order_id` = '" . (int) $order_id . "', `invoice_number` = '" . $invoice_number . "', `qr_code` = '" . $qr_code . "'");
        }

        if ($count <= 0 && $serial_number != NULL && $pin_number != NULL) {
            $this->db->query('INSERT INTO `' . DB_PREFIX . "order_kra_details` SET `order_id` = '" . (int) $order_id . "', `serial_number` = '" . $serial_number . "', `pin_number` = '" . $pin_number . "'");
        }

        if ($count > 0 && $serial_number != NULL && $pin_number != NULL) {
            $this->db->query('UPDATE ' . DB_PREFIX . "order_kra_details SET serial_number = '" . $serial_number . "', pin_number = '" . $pin_number . "' WHERE order_id = '" . (int) $order_id . "'");
        }
    }

    public function getKraDetails($order_id) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "order_kra_details WHERE order_id = '" . (int) $order_id . "'");
        return $query->row;
    }

}
