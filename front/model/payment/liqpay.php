<?php

class ModelPaymentLiqPay extends Model
{
    public function getMethod($total)
    {
        $this->load->language('payment/liqpay');

        if ($this->config->get('liqpay_total') > 0 && $this->config->get('liqpay_total') > $total) {
            $status = false;
        } else {
            $status = true;
        }

        $method_data = [];

        if ($status) {
            $method_data = [
                'code' => 'liqpay',
                'title' => $this->language->get('text_title'),
                'terms' => '',
                'sort_order' => $this->config->get('liqpay_sort_order'),
            ];
        }

        return $method_data;
    }
}
