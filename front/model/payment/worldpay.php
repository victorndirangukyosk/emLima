<?php

class ModelPaymentWorldPay extends Model
{
    public function getMethod($total)
    {
        $this->load->language('payment/worldpay');

        if ($this->config->get('worldpay_total') > 0 && $this->config->get('worldpay_total') > $total) {
            $status = false;
        } else {
            $status = true;
        }

        $method_data = [];

        if ($status) {
            $method_data = [
                'code' => 'worldpay',
                'title' => $this->language->get('text_title'),
                'terms' => '',
                'sort_order' => $this->config->get('worldpay_sort_order'),
            ];
        }

        return $method_data;
    }
}
