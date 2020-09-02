<?php

class ModelPaymentRealex extends Model
{
    public function getMethod($total)
    {
        $this->load->language('payment/realex');

        if ($this->config->get('realex_total') > 0 && $this->config->get('realex_total') > $total) {
            $status = false;
        } else {
            $status = true;
        }

        $method_data = [];

        if ($status) {
            $method_data = [
                'code' => 'realex',
                'title' => $this->language->get('text_title'),
                'terms' => '',
                'sort_order' => $this->config->get('realex_sort_order'),
            ];
        }

        return $method_data;
    }

    public function addOrder($order_info, $pas_ref, $auth_code, $account, $order_ref)
    {
        if (1 == $this->config->get('realex_auto_settle')) {
            $settle_status = 1;
        } else {
            $settle_status = 0;
        }

        $this->db->query('INSERT INTO `'.DB_PREFIX."realex_order` SET `order_id` = '".(int) $order_info['order_id']."', `settle_type` = '".(int) $this->config->get('realex_auto_settle')."', `order_ref` = '".$this->db->escape($order_ref)."', `order_ref_previous` = '".$this->db->escape($order_ref)."', `date_added` = now(), `date_modified` = now(), `capture_status` = '".(int) $settle_status."', `currency_code` = '".$this->db->escape($order_info['currency_code'])."', `pasref` = '".$this->db->escape($pas_ref)."', `pasref_previous` = '".$this->db->escape($pas_ref)."', `authcode` = '".$this->db->escape($auth_code)."', `account` = '".$this->db->escape($account)."', `total` = '".$this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false)."'");

        return $this->db->getLastId();
    }

    public function addTransaction($realex_order_id, $type, $order_info)
    {
        $this->db->query('INSERT INTO `'.DB_PREFIX."realex_order_transaction` SET `realex_order_id` = '".(int) $realex_order_id."', `date_added` = now(), `type` = '".$this->db->escape($type)."', `amount` = '".$this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false)."'");
    }

    public function addHistory($order_id, $order_status_id, $comment)
    {
        $this->db->query('INSERT INTO '.DB_PREFIX."order_history SET order_id = '".(int) $order_id."', order_status_id = '".(int) $order_status_id."', notify = '0', comment = '".$this->db->escape($comment)."', date_added = NOW()");
    }

    public function logger($message)
    {
        if (1 == $this->config->get('realex_debug')) {
            $log = new Log('realex.log');
            $log->write($message);
        }
    }
}
