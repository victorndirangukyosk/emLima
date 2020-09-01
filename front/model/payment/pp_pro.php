<?php

class ModelPaymentPPPro extends Model
{
    public function getMethod($total)
    {
        $this->language->load('payment/pp_pro');

        if ($this->config->get('pp_pro_total') > 0 && $this->config->get('pp_pro_total') > $total) {
            $status = false;
        } else {
            $status = true;
        }

        $method_data = [];

        if ($status) {
            $method_data = [
                'code' => 'pp_pro',
                'title' => $this->language->get('text_title'),
                'terms' => '',
                'sort_order' => $this->config->get('pp_pro_sort_order'),
            ];
        }

        return $method_data;
    }
}
