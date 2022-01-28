<?php

class ModelPaymentPezesha extends Model {

    public function getMethod($total) {
        $this->load->language('payment/pezesha');
        $log = new Log(error . log);
        $log->write('getCustomerPezeshaId' . $this->customer->getCustomerPezeshaId());
        $log->write('getCustomerPezeshauuId' . $this->customer->getCustomerPezeshauuId());
        $log->write('pezesha_status' . $this->config->get('pezesha_status'));
        $log->write('pezesha_total' . $this->config->get('pezesha_total'));
        $log->write('total' . $total);

        if ($this->customer->getCustomerPezeshaId() > 0 && $this->customer->getCustomerPezeshauuId() != NULL && $this->config->get('pezesha_status') && $this->config->get('pezesha_total') > 0 && $this->config->get('pezesha_total') >= $total) {
            $status = true;
        } else {
            $status = false;
        }

        $log = new Log('error.log');
        $log->write('pyment method checking');
        $log->write($total);

        //in case customer experience team apply coupon , then cart total will be 0
        if ($_SESSION["ce_id"] > 0) {
            $status = true;
        }
        $log->write($status);
        $log->write('status');

        $method_data = [];

        if ($status) {
            $method_data = [
                'code' => 'pezesha',
                'title' => $this->language->get('text_title'),
                'terms' => $this->language->get('text_terms'),
                'terms1' => 'Available Pezesha Amount - ' . $this->session->data['pezesha_amount_limit'],
                'sort_order' => $this->config->get('pezesha_sort_order'),
            ];
        }
        $log->write('status');
        $log->write($method_data);
        return $method_data;
    }

    public function insertOrderTransactionId($order_id, $transaction_id, $customer_id) {
        $log = new Log('error.log');
        $log->write('order_id_transaction_id');
        $log->write($order_id . ' ' . $transaction_id);
        $log->write('order_id_transaction_id');
        $sql = 'DELETE FROM ' . DB_PREFIX . "order_transaction_id WHERE order_id = '" . (int) $order_id . "'";

        $query = $this->db->query($sql);

        $sql = 'INSERT into ' . DB_PREFIX . "order_transaction_id SET order_id = '" . $order_id . "', transaction_id = '" . $transaction_id . "', customer_id = '" . $customer_id . "', created_at = NOW()";

        $query = $this->db->query($sql);
    }

}
