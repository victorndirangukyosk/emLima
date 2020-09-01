<?php

class ModelPaymentSagePayUS extends Model
{
    public function getMethod($total)
    {
        $this->load->language('payment/sagepay_us');

        if ($this->config->get('sagepay_us_total') > 0 && $this->config->get('sagepay_us_total') > $total) {
            $status = false;
        } else {
            $status = true;
        }

        $method_data = [];

        if ($status) {
            $method_data = [
                'code' => 'sagepay_us',
                'title' => $this->language->get('text_title'),
                'terms' => '',
                'sort_order' => $this->config->get('sagepay_us_sort_order'),
            ];
        }

        return $method_data;
    }
}
