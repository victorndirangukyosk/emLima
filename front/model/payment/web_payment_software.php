<?php

class ModelPaymentWebPaymentSoftware extends Model
{
    public function getMethod($total)
    {
        $this->load->language('payment/web_payment_software');

        if ($this->config->get('web_payment_software_total') > 0 && $this->config->get('web_payment_software_total') > $total) {
            $status = false;
        } else {
            $status = true;
        }

        $method_data = [];

        if ($status) {
            $method_data = [
                'code' => 'web_payment_software',
                'title' => $this->language->get('text_title'),
                'terms' => '',
                'sort_order' => $this->config->get('web_payment_software_sort_order'),
            ];
        }

        return $method_data;
    }
}
