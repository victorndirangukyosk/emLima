<?php

class ModelPaymentAuthorizeNetAim extends Model
{
    public function getMethod($total)
    {
        $this->load->language('payment/authorizenet_aim');

        if ($this->config->get('authorizenet_aim_total') > 0 && $this->config->get('authorizenet_aim_total') > $total) {
            $status = false;
        } else {
            $status = true;
        }

        $method_data = [];

        if ($status) {
            $method_data = [
                'code' => 'authorizenet_aim',
                'title' => $this->language->get('text_title'),
                'terms' => '',
                'sort_order' => $this->config->get('authorizenet_aim_sort_order'),
            ];
        }

        return $method_data;
    }
}
