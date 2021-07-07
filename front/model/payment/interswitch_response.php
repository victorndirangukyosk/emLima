<?php

class ModelPaymentInterswitchResponse extends Model {

    public function Saveresponse($customer_id, $order_id, $response) {
        $this->db->query('INSERT INTO `' . DB_PREFIX . "interswitch_response` SET `order_id` = '" . (int) $order_id . "', `customer_id` = '" . $customer_id . "', `response` = '" . $response . "', created_at = NOW()");
        return $this->db->getLastId();
    }

    public function SaveResponseIndv($customer_id, $order_id, $payment_gateway_description, $payment_reference_number, $banking_reference_number, $transaction_reference_number, $approved_amount, $payment_gateway_amount, $card_number, $mac, $response_code, $status) {
        $this->db->query('INSERT INTO `' . DB_PREFIX . "interswitch_transaction` SET `order_id` = '" . (int) $order_id . "', `customer_id` = '" . $customer_id . "', `transaction_reference` = '" . $transaction_reference_number . "', `payment_reference` = '" . $payment_reference_number . "', `banking_reference` = '" . $banking_reference_number . "', `approved_amount` = '" . $approved_amount . "', `amount` = '" . $payment_gateway_amount . "', `card_number` = '" . $card_number . "', `mac` = '" . $mac . "', `response_code` = '" . $response_code . "', `description` = '" . $payment_gateway_description . "', `status` = '" . $status . "', created_at = NOW()");
        return $this->db->getLastId();
    }

}
