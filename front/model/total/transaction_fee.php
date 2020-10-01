<?php

class ModelTotalTransactionFee extends Model
{
    public function getTotal(&$total_data, &$total, &$taxes, $store_id = false)
    {
        $this->load->language('total/transaction_fee');

        $sub_total = $this->cart->getSubTotal();

        $total_data[] = [
            'code' => 'transaction_fee',
            'title' => $this->language->get('text_transaction_fee'),
            'value' => $sub_total,
            'sort_order' => $this->config->get('transaction_fee_sort_order'),
        ];

        $total += $sub_total;
    }

    public function getApiTotal(&$total_data, &$total, &$taxes, $store_id = false, $args)
    {
        $this->load->language('total/sub_total');

        if (isset($store_id) && isset($args['stores'][$store_id])) {
            $total_data[] = [
                'code' => 'sub_total',
                'title' => $this->language->get('text_sub_total'),
                'value' => $args['stores'][$store_id]['total'],
                'sort_order' => $this->config->get('sub_total_sort_order'),
            ];

            $total += $args['stores'][$store_id]['total'];
        }

        /*if (isset($this->session->data['vouchers']) && $this->session->data['vouchers']) {
            foreach ($this->session->data['vouchers'] as $voucher) {
                $sub_total += $voucher['amount'];
            }
        }*/
    }
}
