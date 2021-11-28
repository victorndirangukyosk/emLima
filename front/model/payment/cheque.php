<?php

class ModelPaymentCheque extends Model
{
    public function getMethod($total)
    {
        $this->load->language('payment/cheque');

        if ($this->config->get('cheque_total') > 0 && $this->config->get('cheque_total') > $total) {
            $status = false;
        } else {
            $status = true;
        }

        $method_data = [];

        if ($status) {
            $method_data = [
                'code' => 'cheque',
                'title' => $this->language->get('text_title'),
                'terms' => '',
                'sort_order' => $this->config->get('cheque_sort_order'),
            ];
        }

        return $method_data;
    }
}
