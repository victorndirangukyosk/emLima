<?php

class ModelPaymentPayMate extends Model
{
    public function getMethod($total)
    {
        $this->load->language('payment/paymate');

        if ($this->config->get('paymate_total') > 0 && $this->config->get('paymate_total') > $total) {
            $status = false;
        } else {
            $status = true;
        }

        $currencies = [
            'AUD',
            'NZD',
            'USD',
            'EUR',
            'GBP',
        ];

        if (!in_array(strtoupper($this->currency->getCode()), $currencies)) {
            $status = false;
        }

        $method_data = [];

        if ($status) {
            $method_data = [
                'code' => 'paymate',
                'title' => $this->language->get('text_title'),
                'terms' => '',
                'sort_order' => $this->config->get('paymate_sort_order'),
            ];
        }

        return $method_data;
    }
}
