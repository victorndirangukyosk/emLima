<?php

class ModelPaymentPerpetualPayments extends Model
{
    public function getMethod($total)
    {
        $this->load->language('payment/perpetual_payments');

        if ($this->config->get('perpetual_payments_total') > 0 && $this->config->get('perpetual_payments_total') > $total) {
            $status = false;
        } else {
            $status = true;
        }

        $method_data = [];

        if ($status) {
            $method_data = [
                'code' => 'perpetual_payments',
                'title' => $this->language->get('text_title'),
                'terms' => '',
                'sort_order' => $this->config->get('perpetual_payments_sort_order'),
            ];
        }

        return $method_data;
    }
}
