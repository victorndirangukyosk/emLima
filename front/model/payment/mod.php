<?php

class ModelPaymentMOD extends Model
{
    public function getMethod($total)
    {
        $this->load->language('payment/mod');

        if ($this->config->get('mod_total') > 0 && $this->config->get('mod_total') > $total) {
            $status = false;
        } else {
            $status = true;
        }

        $method_data = [];

        if ($status) {
            $method_data = [
                'code' => 'mod',
                'title' => $this->language->get('text_title'),
                'terms' => $this->language->get('text_terms'),
                'sort_order' => $this->config->get('mod_sort_order'),
            ];
        }

        return $method_data;
    }
}
