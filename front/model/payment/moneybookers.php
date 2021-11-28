<?php

class ModelPaymentMoneybookers extends Model
{
    public function getMethod($total)
    {
        $this->load->language('payment/moneybookers');

        if ($this->config->get('moneybookers_total') > 0 && $this->config->get('moneybookers_total') > $total) {
            $status = false;
        } else {
            $status = true;
        }

        $method_data = [];

        if ($status) {
            $method_data = [
                'code' => 'moneybookers',
                'title' => $this->language->get('text_title'),
                'terms' => '',
                'sort_order' => $this->config->get('moneybookers_sort_order'),
            ];
        }

        return $method_data;
    }
}
