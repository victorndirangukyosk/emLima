<?php

class ControllerApiCustomerPezesha extends Controller {

    public function getPezeshaLoans() {
        $data['orders'] = NULL;
        $this->load->model('account/order');
        $pezesha_loans = $this->model_account_order->getPezeshaloans();
        foreach ($pezesha_loans as $pezesha_loan) {
            $pezesha_loan['total'] = $this->currency->format($pezesha_loan['total']);
            $data['orders'][] = $pezesha_loan;
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

}
