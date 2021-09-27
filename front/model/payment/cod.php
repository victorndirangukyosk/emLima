<?php

class ModelPaymentCOD extends Model
{
    public function getMethod($total)
    {
        $this->load->language('payment/cod');

        if ($this->config->get('cod_total') > 0 && $this->config->get('cod_total') > $total) {
            $status = false;
        } else {
            $status = true;
        }

        $log = new Log('error.log');
        $log->write('pyment metho checking');
        $log->write($total);
        
        //in case customer experience team apply coupon , then cart total will be 0
        // if($_SESSION["ce_id"] > 0 || )
        {
            $status = true;
        }
        $log->write($status);
        $log->write('status');

        $method_data = [];

        if ($status) {
            $method_data = [
                'code' => 'cod',
                'title' => $this->language->get('text_title'),
                'terms' => $this->language->get('text_terms'),
                'sort_order' => $this->config->get('cod_sort_order'),
            ];
        }
        $log->write('status');
        $log->write($method_data);
        return $method_data;
    }
}
