<?php

class ModelTotalSubTotal extends Model
{
    public function getTotal(&$total_data, &$total, &$taxes, $store_id = false)
    {
        $this->load->language('total/sub_total');

        $sub_total = $this->cart->getSubTotal($store_id);

        /*echo "sub";
        echo $sub_total;*/
        $log = new Log('error.log');
        $log->write('sub_total front_model_total_sub_total.php');
        $log->write($sub_total);
        $log->write('sub_total front_model_total_sub_total.php');
        if (isset($this->session->data['vouchers']) && $this->session->data['vouchers']) {
            foreach ($this->session->data['vouchers'] as $voucher) {
                $sub_total += $voucher['amount'];
            }
        }

        $total_data[] = [
            'code' => 'sub_total',
            'title' => $this->language->get('text_sub_total'),
            'value' => $sub_total,
            'sort_order' => $this->config->get('sub_total_sort_order'),
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
