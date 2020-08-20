<?php

class ModelPaymentFlutterwavepaymentoptions extends Model {

    public function getpaymentoptions() {
        $result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "flutterwave_payment_options` WHERE `status` = 1");

        return $result->rows;
    }

}
