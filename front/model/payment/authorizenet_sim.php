<?php

class ModelPaymentAuthorizeNetSim extends Model
{
    public function getMethod($total)
    {
        $this->load->language('payment/authorizenet_sim');

        if ($this->config->get('authorizenet_sim_total') > 0 && $this->config->get('authorizenet_sim_total') > $total) {
            $status = false;
        } else {
            $status = true;
        }

        $method_data = [];

        if ($status) {
            $method_data = [
                'code' => 'authorizenet_sim',
                'title' => $this->language->get('text_title'),
                'terms' => '',
                'sort_order' => $this->config->get('authorizenet_sim_sort_order'),
            ];
        }

        return $method_data;
    }
}
