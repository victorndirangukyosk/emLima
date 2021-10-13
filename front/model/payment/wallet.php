<?php

class ModelPaymentWallet extends Model
{
    public function getMethod($total)
    {
        $this->load->language('payment/wallet');

        if ($this->config->get('wallet_total') > 0 && $this->config->get('wallet_total') > $total) {
            $status = false;
        } else {
            $status = true;
        }
        //check customer wallet available balance with order total
        $customer_wallet_total=0;
        if($status == true)
        {
            $this->load->model('account/credit');
            $customer_wallet_total=$this->model_account_credit->getTotalAmount();
            if($customer_wallet_total>=$total)
            {
                $status = true;
            }
            else {
                $status = false;
            }
        }

        $log = new Log('error.log');
        $log->write('pyment metho checking');
        $log->write($total);
        
         
        $log->write($status);
        $log->write('status');

        $method_data = [];
        $customer_wallet_amount=$this->currency->format($customer_wallet_total, $this->config->get('config_currency'));
        if ($status) {
            $method_data = [
                'code' => 'wallet',
                'title' => $this->language->get('text_title') ,
                'terms' => $this->language->get('text_terms'),
                'terms1' => 'Available Wallet Amount - '.$customer_wallet_amount,
                'sort_order' => $this->config->get('wallet_sort_order'),
            ];
        }
        $log->write('status');
        $log->write($method_data);
        return $method_data;
    }
}
