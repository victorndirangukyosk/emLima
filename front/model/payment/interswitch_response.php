<?php

class ModelPaymentInterswitchResponse extends Model {

    public function Saveresponse($customer_id, $order_id, $response) {
        $this->db->query('INSERT INTO `' . DB_PREFIX . "interswitch_response` SET `order_id` = '" . (int) $order_id . "', `customer_id` = '" . $customer_id . "', `response` = '" . $response . "', created_at = NOW()");
        return $this->db->getLastId();
    }

}
