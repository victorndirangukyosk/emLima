<?php

class ModelPaymentPPStandard extends Model
{
    public function getMethod($total)
    {
        $this->load->language('payment/pp_standard');

        if ($this->config->get('pp_standard_total') > $total) {
            $status = false;
        } else {
            $status = true;
        }

        $currencies = [
            'AUD',
            'CAD',
            'EUR',
            'GBP',
            'JPY',
            'USD',
            'NZD',
            'CHF',
            'HKD',
            'SGD',
            'SEK',
            'DKK',
            'PLN',
            'NOK',
            'HUF',
            'CZK',
            'ILS',
            'MXN',
            'MYR',
            'BRL',
            'PHP',
            'TWD',
            'THB',
            'TRY',
        ];

        if (!in_array(strtoupper($this->currency->getCode()), $currencies)) {
            $status = false;
        }

        $method_data = [];

        if ($status) {
            $method_data = [
                'code' => 'pp_standard',
                'title' => $this->language->get('text_title'),
                'terms' => '',
                'sort_order' => $this->config->get('pp_standard_sort_order'),
            ];
        }

        return $method_data;
    }
}
