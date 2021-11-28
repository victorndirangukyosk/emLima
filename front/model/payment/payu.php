<?php

class ModelPaymentPayu extends Model
{
    public function getMethod($total)
    {
        $this->load->language('payment/payu');

        if ($this->config->get('payu_total') > $total) {
            $status = false;
        } else {
            $status = true;
        }

        $method_data = [];

        if ($status) {
            $method_data = [
                'code' => 'payu',
                'title' => $this->language->get('text_title'),
                'sort_order' => $this->config->get('payu_sort_order'),
            ];
        }

        return $method_data;
    }
}
