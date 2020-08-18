<?php

class ModelPaymentFlutterwavetransactions extends Model {

    public function addOrderTransaction($transaction_info, $order_id) {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "flutterwave_transactions` SET `order_id` = '" . (int) $order_id . "', `flutterwave_id` = '" . $transaction_info['id'] . "',`tx_ref` = '" . $transaction_info['tx_ref'] . "',`flw_ref` = '" . $transaction_info['flw_ref'] . "',`device_fingerprint` = '" . $transaction_info['device_fingerprint'] . "',`amount` = '" . $transaction_info['amount'] . "',`currency` = '" . $transaction_info['currency'] . "',`charged_amount` = '" . $transaction_info['charged_amount'] . "',`app_fee` = '" . $transaction_info['app_fee'] . "',`merchant_fee` = '" . $transaction_info['app_fee'] . "',`processor_response` = '" . $transaction_info['processor_response'] . "',`auth_model` = '" . $transaction_info['auth_model'] . "',`ip` = '" . $transaction_info['ip'] . "',`narration` = '" . $transaction_info['narration'] . "',`status` = '" . $transaction_info['status'] . "',`payment_type` = '" . $transaction_info['payment_type'] . "',`created_at` = '" . $transaction_info['created_at'] . "',`account_id` = '" . $transaction_info['account_id'] . "',`card_first_6digits` = '" . $transaction_info['card']['first_6digits'] . "',`card_last_4digits` = '" . $transaction_info['card']['last_4digits'] . "',`card_issuer` = '" . $transaction_info['card']['issuer'] . "',`card_country` = '" . $transaction_info['card']['country'] . "',`card_type` = '" . $transaction_info['card']['type'] . "',`card_token` = '" . $transaction_info['card']['token'] . "',`card_expiry` = '" . $transaction_info['card']['expiry'] . "',`amount_settled` = '" . $transaction_info['amount_settled'] . "',`customer_id` = '" . $transaction_info['customer']['id'] . "',`customer_name` = '" . $transaction_info['customer']['name'] . "',`customer_phone_number` = '" . $transaction_info['customer']['phone_number'] . "',`customer_email` = '" . $transaction_info['customer']['email'] . "',`customer_created_at` = '" . $transaction_info['customer']['created_at'] . "'");

        return $this->db->getLastId();
    }

}
