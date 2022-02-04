<?php

class ModelPaymentWallet extends Model {

    public function getMethod($total) {
        $this->load->language('payment/wallet');

        if ($this->config->get('wallet_total') > 0 && $this->config->get('wallet_total') > $total) {
            $status = false;
        } else {
            $status = true;
        }
        //check customer wallet available balance with order total
        $customer_wallet_total = 0;
        if ($status == true) {
            $this->load->model('account/credit');
            $customer_wallet_total = $this->model_account_credit->getTotalAmount();
            if ($customer_wallet_total > 0) {
                $status = true;
            } else {
                $status = false;
            }
            /* if($customer_wallet_total>=$total)
              {
              $status = true;
              }
              else {
              $status = false;
              } */
        }

        $log = new Log('error.log');
        $log->write('pyment metho checking');
        $log->write($total);

        $log->write($status);
        $log->write('status');

        $method_data = [];
        $customer_wallet_amount = $this->currency->format($customer_wallet_total, $this->config->get('config_currency'));
        if ($status) {
            $method_data = [
                'code' => 'wallet',
                'title' => $this->language->get('text_title'),
                'terms' => $this->language->get('text_terms'),
                'terms1' => 'Available Wallet Amount - ' . $customer_wallet_amount,
                'sort_order' => $this->config->get('wallet_sort_order'),
            ];
        }
        $log->write('status');
        $log->write($method_data);
        return $method_data;
    }

    public function addTransactionCredit($customer_id, $description = '', $amount = '', $order_id = 0) {


        // $query = $this->db->query('SELECT total AS total FROM ' . DB_PREFIX . "order WHERE customer_id = '" . (int) $this->customer->getId() . "' AND order_id = '" . (int) $order_id . "'");
        // $total = $query->row['total'];
        // $description = 'Wallet amount deducted#' . $order_id;
        // $this->db->query('DELETE FROM ' . DB_PREFIX . "customer_credit WHERE customer_id = '" . (int) $customer_id . "' and  order_id = '" . (int) $order_id . "'");
        $this->db->query('INSERT INTO ' . DB_PREFIX . "customer_credit SET customer_id = '" . (int) $customer_id . "', order_id = '" . (int) $order_id . "', description = '" . $description . "', amount = '" . (float) ($amount * -1) . "', date_added = NOW()");
        $this->db->query('INSERT INTO ' . DB_PREFIX . "order_transaction_id SET customer_id = '" . (int) $customer_id . "', order_id = '" . (int) $order_id . "', transaction_id = 'Paid from wallet amount (T)'");

        $this->db->query('UPDATE ' . DB_PREFIX . "order SET paid='Y', amount_partialy_paid = 0   WHERE order_id='" . (int) $order_id . "'"); //,total='" . (float) $total . "'
    }

    public function addTransactionCreditForHybridPayment($customer_id, $description = '', $amount = '', $order_id = 0) {


        // $query = $this->db->query('SELECT total AS total FROM ' . DB_PREFIX . "order WHERE customer_id = '" . (int) $this->customer->getId() . "' AND order_id = '" . (int) $order_id . "'");
        // $total = $query->row['total'];
        // $description = 'Wallet amount deducted#' . $order_id;
        // $this->db->query('DELETE FROM ' . DB_PREFIX . "customer_credit WHERE customer_id = '" . (int) $customer_id . "' and  order_id = '" . (int) $order_id . "'");
        $this->db->query('INSERT INTO ' . DB_PREFIX . "customer_credit SET customer_id = '" . (int) $customer_id . "', order_id = '" . (int) $order_id . "', description = '" . $description . "', amount = '" . (float) ($amount * -1) . "', date_added = NOW()");
        $this->db->query('INSERT INTO ' . DB_PREFIX . "order_transaction_id SET customer_id = '" . (int) $customer_id . "', order_id = '" . (int) $order_id . "', transaction_id = 'Paid from wallet amount (T)'");

        $this->db->query('UPDATE ' . DB_PREFIX . "order SET paid='P', amount_partialy_paid = '" . $amount . "'   WHERE order_id='" . (int) $order_id . "'"); //,total='" . (float) $total . "'
    }

}
