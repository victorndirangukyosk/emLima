<?php

class ModelPaymentPPPayflow extends Model
{
    public function getMethod($total)
    {
        $this->load->language('payment/pp_payflow');

        if ($this->config->get('pp_payflow_total') > $total) {
            $status = false;
        } else {
            $status = true;
        }

        $method_data = [];

        if ($status) {
            $method_data = [
                'code' => 'pp_payflow',
                'title' => $this->language->get('text_title'),
                'terms' => '',
                'sort_order' => $this->config->get('pp_payflow_sort_order'),
            ];
        }

        return $method_data;
    }
}
