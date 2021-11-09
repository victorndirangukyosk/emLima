<?php

class ModelPaymentPayzesha extends Model
{
    public function getMethod($total)
    {
        $this->load->language('payment/pezesha');

        if ($this->config->get('pezesha_total') > 0 && $this->config->get('pezesha_total') > $total) {
            $status = false;
        } else {
            $status = true;
        }

        $log = new Log('error.log');
        $log->write('pyment method checking');
        $log->write($total);
        
        //in case customer experience team apply coupon , then cart total will be 0
         if($_SESSION["ce_id"] > 0  )
        {
            $status = true;
        }
        $log->write($status);
        $log->write('status');

        $method_data = [];

        if ($status) {
            $method_data = [
                'code' => 'payzesha',
                'title' => $this->language->get('text_title'),
                'terms' => $this->language->get('text_terms'),
                'sort_order' => $this->config->get('payzesha_sort_order'),
            ];
        }
        $log->write('status');
        $log->write($method_data);
        return $method_data;
    }
}
