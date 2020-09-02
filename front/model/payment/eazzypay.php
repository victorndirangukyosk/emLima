<?php

class ModelPaymentEazzypay extends Model
{
    public function getMethod($total)
    {
        $this->load->language('payment/eazzypay');

        if ($this->config->get('eazzypay_total') > 0 && $this->config->get('eazzypay_total') > $total) {
            $status = false;
        } else {
            $status = true;
        }

        $method_data = [];

        if ($status) {
            $method_data = [
                'code' => 'eazzypay',
                'title' => $this->language->get('text_title'),
                'terms' => '',
                'sort_order' => $this->config->get('eazzypay_sort_order'),
            ];
        }

        return $method_data;
    }

    public function addOrder($order_info, $request_id, $checkout_request_id)
    {
        $this->db->query('DELETE FROM '.DB_PREFIX.'eazzypay_order WHERE order_id = '.(int) $order_info['order_id']);

        $this->db->query('INSERT INTO `'.DB_PREFIX."eazzypay_order` SET `order_id` = '".(int) $order_info['order_id']."', `request_id` = '".$request_id."', `checkout_request_id` = '".$checkout_request_id."'");

        return $this->db->getLastId();
    }

    public function updateEazzypayOrder($order_id, $eazzypay_receipt_number)
    {
        $this->db->query('UPDATE `'.DB_PREFIX.'eazzypay_order` SET `eazzypay_receipt_number` = '.$this->db->escape($eazzypay_receipt_number).' where order_id='.$order_id);
    }

    public function getEazzypayOrder($request_id)
    {
        $result = $this->db->query('SELECT `order_id` FROM `'.DB_PREFIX."eazzypay_order` WHERE `request_id` = '".$this->db->escape($request_id)."'")->row;

        if ($result) {
            $order_id = $result['order_id'];
        } else {
            $order_id = false;
        }

        return $order_id;
    }

    public function getEazzypayByOrderId($order_id)
    {
        $result = $this->db->query('SELECT * FROM `'.DB_PREFIX."eazzypay_order` WHERE `order_id` = '".$this->db->escape($order_id)."'")->row;

        return $result;
    }
}
