<?php

class ModelPaymentPayPoint extends Model
{
    public function getMethod($total)
    {
        $this->load->language('payment/paypoint');

        if ($this->config->get('paypoint_total') > 0 && $this->config->get('paypoint_total') > $total) {
            $status = false;
        } else {
            $status = true;
        }

        $method_data = [];

        if ($status) {
            $method_data = [
                'code' => 'paypoint',
                'title' => $this->language->get('text_title'),
                'terms' => '',
                'sort_order' => $this->config->get('paypoint_sort_order'),
            ];
        }

        return $method_data;
    }
}
