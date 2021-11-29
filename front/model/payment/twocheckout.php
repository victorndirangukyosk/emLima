<?php

class ModelPaymentTwoCheckout extends Model
{
    public function getMethod($total)
    {
        $this->load->language('payment/twocheckout');

        if ($this->config->get('twocheckout_total') > 0 && $this->config->get('twocheckout_total') > $total) {
            $status = false;
        } else {
            $status = true;
        }

        $method_data = [];

        if ($status) {
            $method_data = [
                'code' => 'twocheckout',
                'title' => $this->language->get('text_title'),
                'terms' => '',
                'sort_order' => $this->config->get('twocheckout_sort_order'),
            ];
        }

        return $method_data;
    }
}
