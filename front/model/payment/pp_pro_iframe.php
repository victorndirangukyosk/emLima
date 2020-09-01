<?php

class ModelPaymentPPProIframe extends Model
{
    public function getMethod($total)
    {
        $this->load->language('payment/pp_pro_iframe');

        if ($this->config->get('pp_pro_iframe_total') > $total) {
            $status = false;
        } else {
            $status = true;
        }

        $method_data = [];

        if ($status) {
            $method_data = [
                'code' => 'pp_pro_iframe',
                'title' => $this->language->get('text_title'),
                'terms' => '',
                'sort_order' => $this->config->get('pp_pro_iframe_sort_order'),
            ];
        }

        return $method_data;
    }

    public function addOrder($order_data)
    {
        $this->db->query('INSERT INTO `'.DB_PREFIX."paypal_iframe_order` SET `order_id` = '".(int) $order_data['order_id']."', `date_added` = NOW(), `date_modified` = NOW(), `capture_status` = '".$this->db->escape($order_data['capture_status'])."', `currency_code` = '".$this->db->escape($order_data['currency_code'])."', `total` = '".(float) $order_data['total']."', `authorization_id` = '".$this->db->escape($order_data['authorization_id'])."'");

        return $this->db->getLastId();
    }

    public function addTransaction($transaction_data)
    {
        $this->db->query('INSERT INTO `'.DB_PREFIX."paypal_iframe_order_transaction` SET `paypal_iframe_order_id` = '".(int) $transaction_data['paypal_iframe_order_id']."', `transaction_id` = '".$this->db->escape($transaction_data['transaction_id'])."', `parent_transaction_id` = '".$this->db->escape($transaction_data['parent_transaction_id'])."', `date_added` = NOW(), `note` = '".$this->db->escape($transaction_data['note'])."', `msgsubid` = '".$this->db->escape($transaction_data['msgsubid'])."', `receipt_id` = '".$this->db->escape($transaction_data['receipt_id'])."', `payment_type` = '".$this->db->escape($transaction_data['payment_type'])."', `payment_status` = '".$this->db->escape($transaction_data['payment_status'])."', `pending_reason` = '".$this->db->escape($transaction_data['pending_reason'])."', `transaction_entity` = '".$this->db->escape($transaction_data['transaction_entity'])."', `amount` = '".(float) $transaction_data['amount']."', `debug_data` = '".$this->db->escape($transaction_data['debug_data'])."'");
    }

    public function log($message)
    {
        if ($this->config->get('pp_pro_iframe_debug')) {
            $log = new Log('pp_pro_iframe.log');
            $log->write($message);
        }
    }
}
