<?php

class ModelTotalTransactionFee extends Model
{
    public function getTotal(&$total_data, &$total, &$taxes, $store_id = false)
    {
        $this->load->language('total/transaction_fee');

        $sub_total = $this->cart->getSubTotal();
        
        $transaction_fee = 0;
        $percentage = 3.5;
        $transaction_fee = ($percentage / 100) * $sub_total;
        $log = new Log('error.log');
        $log->write('TRANSACTION FEE');
        $log->write($transaction_fee);
        $log->write('TRANSACTION FEE');

        $total_data[] = [
            'code' => 'transaction_fee',
            'title' => $this->language->get('text_transaction_fee'),
            'value' => $transaction_fee,
            'sort_order' => $this->config->get('transaction_fee_sort_order'),
        ];

        $total +=$transaction_fee;
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
