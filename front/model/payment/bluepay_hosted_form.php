<?php

class ModelPaymentBluePayHostedForm extends Model
{
    public function getMethod($total)
    {
        $this->load->language('payment/bluepay_hosted_form');

        if ($this->config->get('bluepay_hosted_form_total') > 0 && $this->config->get('bluepay_hosted_form_total') > $total) {
            $status = false;
        } else {
            $status = true;
        }

        $method_data = [];

        if ($status) {
            $method_data = [
                'code' => 'bluepay_hosted_form',
                'title' => $this->language->get('text_title'),
                'terms' => '',
                'sort_order' => $this->config->get('bluepay_hosted_form_sort_order'),
            ];
        }

        return $method_data;
    }

    public function addOrder($order_info, $response_data)
    {
        if ('SALE' == $this->config->get('bluepay_hosted_form_transaction')) {
            $release_status = 1;
        } else {
            $release_status = null;
        }

        $this->db->query('INSERT INTO `'.DB_PREFIX."bluepay_hosted_form_order` SET `order_id` = '".(int) $order_info['order_id']."', `transaction_id` = '".$this->db->escape($response_data['RRNO'])."', `date_added` = now(), `date_modified` = now(), `release_status` = '".(int) $release_status."', `currency_code` = '".$this->db->escape($order_info['currency_code'])."', `total` = '".$this->currency->format($order_info['total'], $order_info['currency_code'], false, false)."'");

        return $this->db->getLastId();
    }

    public function addTransaction($bluepay_hosted_form_order_id, $type, $order_info)
    {
        $this->db->query('INSERT INTO `'.DB_PREFIX."bluepay_hosted_form_order_transaction` SET `bluepay_hosted_form_order_id` = '".(int) $bluepay_hosted_form_order_id."', `date_added` = now(), `type` = '".$this->db->escape($type)."', `amount` = '".$this->currency->format($order_info['total'], $order_info['currency_code'], false, false)."'");
    }

    public function logger($message)
    {
        if (1 == $this->config->get('bluepay_hosted_form_debug')) {
            $log = new Log('bluepay_hosted_form.log');
            $log->write($message);
        }
    }
}
