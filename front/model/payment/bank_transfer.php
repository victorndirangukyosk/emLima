<?php

class ModelPaymentBankTransfer extends Model
{
    public function getMethod($total)
    {
        $this->load->language('payment/bank_transfer');

        if ($this->config->get('bank_transfer_total') > 0 && $this->config->get('bank_transfer_total') > $total) {
            $status = false;
        } else {
            $status = true;
        }

        $method_data = [];

        if ($status) {
            $method_data = [
                'code' => 'bank_transfer',
                'title' => $this->language->get('text_title'),
                'terms' => '',
                'sort_order' => $this->config->get('bank_transfer_sort_order'),
            ];
        }

        return $method_data;
    }
}
