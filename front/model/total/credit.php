<?php

class ModelTotalCredit extends Model
{
    public function getTotal(&$total_data, &$total, &$taxes, $store_id = '')
    {
        $log = new Log('error.log');

        $log->write('ModelTotalCredit');
        // echo "<pre>";print_r('$balance');die;


        if ($this->config->get('credit_status')) {
            $this->load->language('total/credit');
            $balance = $this->customer->getBalance();

            // echo "<pre>";print_r($balance);die;


            if ($store_id) {
                $log = new Log('error.log');
                $log->write('getTotal creditx');

                if ((float) $balance) {
                    if ($balance > $total) {
                        $credit = $total;
                    } else {
                        $credit = $balance;
                    }

                    if ($credit > 0) {
                        $main_total = $this->cart->getSubTotal();

                        $store_total = $this->cart->getSubTotal($store_id);

                        $weightage = ($store_total * 100) / $main_total;

                        $giveCredit = ($credit * $weightage) / 100;

                        /*$log->write($total);
                        $log->write($credit);$log->write($main_total);
                        $log->write($store_id);*/
                        $total_data[] = [
                            'code' => 'credit',
                            'title' => $this->language->get('text_credit'),
                            'value' => -$giveCredit,
                            'sort_order' => $this->config->get('credit_sort_order'),
                        ];

                        $total -= $giveCredit;
                    } elseif ($credit < 0) {
                        $main_total = $this->cart->getSubTotal();

                        $store_total = $this->cart->getSubTotal($store_id);

                        $weightage = ($store_total * 100) / $main_total;

                        $giveCredit = ($credit * $weightage) / 100;

                        if (-$giveCredit > 0.59) {
                            $total_data[] = [
                                'code' => 'credit',
                                'title' => $this->language->get('text_credit'),
                                'value' => -$giveCredit,
                                'sort_order' => $this->config->get('credit_sort_order'),
                            ];
                        }

                        $total -= $giveCredit;
                    }
                }
            } else {
                if ((float) $balance) {
                    if ($balance > $total) {
                        $credit = $total;
                    } else {
                        $credit = $balance;
                    }

                    if ($credit > 0) {
                        $total_data[] = [
                            'code' => 'credit',
                            'title' => $this->language->get('text_credit'),
                            'value' => -$credit,
                            'sort_order' => $this->config->get('credit_sort_order'),
                        ];

                        $total -= $credit;
                    } elseif ($credit < 0) {
                        $total_data[] = [
                            'code' => 'credit',
                            'title' => $this->language->get('text_credit'),
                            'value' => -$credit,
                            'sort_order' => $this->config->get('credit_sort_order'),
                        ];

                        $total -= $credit;
                    }
                }
            }
        }
    }

    public function getApiTotal(&$total_data, &$total, &$taxes, $store_id = '', $args)
    {
        $log = new Log('error.log');

        $log->write('ModelTotalCredit');

        if ($this->config->get('credit_status')) {
            $this->load->language('total/credit');
            $balance = $this->customer->getBalance();

            if ($store_id) {
                $log = new Log('error.log');
                $log->write('getTotal creditx');

                if ((float) $balance) {
                    if ($balance > $total) {
                        $credit = $total;
                    } else {
                        $credit = $balance;
                    }

                    if ($credit > 0) {
                        /*$main_total = $this->cart->getSubTotal();

                        $store_total = $this->cart->getSubTotal($store_id);*/

                        $main_total = $args['sub_total']; // final subtotal

                        $store_total = $args['stores'][$store_id]['total']; //store sub total

                        $weightage = ($store_total * 100) / $main_total;

                        $giveCredit = ($credit * $weightage) / 100;

                        /*$log->write($total);
                        $log->write($credit);$log->write($main_total);
                        $log->write($store_id);*/
                        $total_data[] = [
                            'code' => 'credit',
                            'title' => $this->language->get('text_credit'),
                            'value' => -$giveCredit,
                            'sort_order' => $this->config->get('credit_sort_order'),
                        ];

                        $total -= $giveCredit;
                    } elseif ($credit < 0) {
                        $main_total = $args['sub_total']; // final subtotal

                        $store_total = $args['stores'][$store_id]['total']; //store sub total

                        $weightage = ($store_total * 100) / $main_total;

                        $giveCredit = ($credit * $weightage) / 100;

                        /*$log->write($total);
                        $log->write($credit);$log->write($main_total);
                        $log->write($store_id);*/
                        $total_data[] = [
                            'code' => 'credit',
                            'title' => $this->language->get('text_credit'),
                            'value' => -$giveCredit,
                            'sort_order' => $this->config->get('credit_sort_order'),
                        ];

                        $total -= $giveCredit;
                    }
                }
            }
        }
    }

    public function confirm($order_info, $order_total)
    {
        $this->load->language('total/credit');

        if ($order_info['customer_id']) {
            $this->load->model('account/activity');

            $this->model_account_activity->addCredit($order_info['customer_id'], $this->db->escape(sprintf($this->language->get('text_order_id'), (int) $order_info['order_id'])), $order_total['value'], $order_info['order_id']);
            //$this->db->query("INSERT INTO " . DB_PREFIX . "customer_credit SET customer_id = '" . (int)$order_info['customer_id'] . "', order_id = '" . (int)$order_info['order_id'] . "', description = '" . $this->db->escape(sprintf($this->language->get('text_order_id'), (int)$order_info['order_id'])) . "', amount = '" . (float)$order_total['value'] . "', date_added = NOW()");
        }
    }

    public function unconfirm($order_id)
    {
        // /*return */$this->db->query('DELETE FROM '.DB_PREFIX."customer_credit WHERE order_id = '".(int) $order_id."'");

        //$this->model_account_activity->addCredit($order_info['customer_id'],$order_info['customer_id'],(float)$order_total['value'],$order_info['order_id']);
    }


    //new method to deduct wallet amount , after confirm order
    public function confirmWalletTransaction($order_info, $order_total)
    {
        $this->load->language('total/credit');

        if ($order_info['customer_id']) {
            $this->load->model('account/activity');

            $this->addCreditOnly($order_info['customer_id'], $this->db->escape(sprintf($this->language->get('text_order_id'), (int) $order_info['order_id'])), $order_total['value'], $order_info['order_id']);
        }
    }


    public function addOnlyCredit($customer_id, $description = '', $amount = '', $order_id = 0) {
        $customer_info = $this->getCustomer($customer_id);

        $log = new Log('error.log');

        if ($customer_info) {
            $this->db->query('INSERT INTO ' . DB_PREFIX . "customer_credit SET customer_id = '" . (int) $customer_id . "', order_id = '" . (int) $order_id . "', description = '" . $this->db->escape($description) . "', amount = '" . (float) $amount . "', date_added = NOW()");
             
        }
    }

    public function unconfirmWalletTransaction($order_id)
    {
         $this->db->query('DELETE FROM '.DB_PREFIX."customer_credit WHERE order_id = '".(int) $order_id."'");
    }
}
