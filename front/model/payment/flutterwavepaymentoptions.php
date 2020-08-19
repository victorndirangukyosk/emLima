<?php

class ModelPaymentFlutterwavepaymentoptions extends Model {

    public function getpaymentoptions() {
        $result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "flutterwave_payment_options`");

        return $result->rows;
    }

}
