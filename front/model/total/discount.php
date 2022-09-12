<?php

class ModelTotalDiscount extends Model {

    public function getTotal(&$total_data, &$total, &$taxes, $store_id = false, $proddiscounts) {

        $this->load->language('total/discount');

        $discount = 0;
        if (isset($proddiscounts['discount']) && count($proddiscounts['discount']) > 0) {
            foreach ($proddiscounts['discount'] as $proddiscount) {
                if (isset($proddiscount['amount']) && $proddiscount['amount'] > 0) {
                    $discount += $proddiscount['amount'];
                }
            }
        }

        if ($discount > 0) {
            $total_data[] = [
                'code' => 'discount',
                'title' => $this->language->get('text_discount'),
                'value' => -$discount,
                'sort_order' => 3,
            ];

            $total -= $discount;
        }
    }

    public function getApiTotal(&$total_data, &$total, &$taxes, $args) {
        $status = false;

        $log = new Log('error.log');
        $log->write('FRONT_MODEL_TOTAL_DISCOUNT');
        $log->write($total_data);
        $log->write($total);
        $log->write($taxes);
        $log->write($args);
        $log->write('FRONT_MODEL_TOTAL_DISCOUNT');
        if ($status) {
            $this->load->language('total/discount');

            $discount = ($this->cart->getSubTotal() / 100) * $settings['value'];

            if ($discount > 0) {
                $total_data[] = [
                    'code' => 'iugu_discount',
                    'title' => $this->language->get('text_discount'),
                    'value' => -$discount,
                    'sort_order' => 3,
                ];

                $total -= $discount;
            }
        }
    }

}
