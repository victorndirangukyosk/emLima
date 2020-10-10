<?php

class ModelTotalTransactionFee extends Model {

    public function getTotal(&$total_data, &$total, &$taxes, $store_id = false) {
        /* FOR PESAPAL TRANSACTION */
        $this->load->language('total/transaction_fee');

        $sub_total = $this->cart->getSubTotal();

        $transaction_fee = 0;
        $percentage = 3.5;
        /* $transaction_fee = ($percentage / 100) * $sub_total; */
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

    public function getApiTotal(&$total_data, &$total, &$taxes, $store_id = false, $args) {

        /* FOR PESAPAL TRANSACTION */
        $this->load->language('total/transaction_fee');

        $sub_total = $this->cart->getSubTotal();

        $transaction_fee = 0;
        $percentage = 3.5;
        /* $transaction_fee = ($percentage / 100) * $sub_total; */
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

        /* if (isset($this->session->data['vouchers']) && $this->session->data['vouchers']) {
          foreach ($this->session->data['vouchers'] as $voucher) {
          $sub_total += $voucher['amount'];
          }
          } */
    }

}
