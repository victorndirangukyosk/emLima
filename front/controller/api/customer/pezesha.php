<?php

class ControllerApiCustomerPezesha extends Controller {

    private $error = [];

    public function getPezeshaLoans() {
        if ($this->customer->getCustomerPezeshaId() != NULL && $this->customer->getCustomerPezeshauuId() != NULL && $this->config->get('pezesha_status')) {
            $data['orders'] = NULL;
            $this->load->model('account/order');
            $pezesha_loans = $this->model_account_order->getPezeshaloans();
            $data['message'] = count($pezesha_loans) > 0 ? 'Pezesha Loans Fetched Successfully!' : 'Pezesha Loans Not Found!';
            foreach ($pezesha_loans as $pezesha_loan) {
                $pezesha_loan['total'] = $this->currency->format($pezesha_loan['total']);
                $data['orders'][] = $pezesha_loan;
            }
        } else {
            $data['message'] = 'Please Check Your Pezesha Details!';
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function getPezeshaLoanOffers() {

        $log = new Log('error.log');
        if ($this->customer->getCustomerPezeshaId() != NULL && $this->customer->getCustomerPezeshauuId() != NULL && $this->config->get('pezesha_status')) {

            $this->load->model('account/customer');

            $customer_id = $this->customer->getId();

            $customer_device_info = $this->model_account_customer->getCustomer($customer_id);
            $customer_pezesha_info = $this->model_account_customer->getPezeshaCustomer($customer_id);

            $auth_response = $this->auth();
            $log->write('auth_response');
            $log->write($auth_response);
            $log->write($customer_device_info);
            $log->write('auth_response');
            $body = array('identifier' => $customer_pezesha_info['prefix'] . '' . $customer_pezesha_info['customer_id'], 'channel' => $this->config->get('pezesha_channel'));
            //$body = http_build_query($body);
            $body = json_encode($body);
            $log->write($body);
            $curl = curl_init();
            if ($this->config->get('pezesha_environment') == 'live') {
                curl_setopt($curl, CURLOPT_URL, 'https://api.pezesha.com/mfi/v1/borrowers/options');
                curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Authorization:Bearer ' . $auth_response]);
            } else {
                curl_setopt($curl, CURLOPT_URL, 'https://staging.api.pezesha.com/mfi/v1/borrowers/options');
                curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Authorization:Bearer ' . $auth_response]);
            }
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body); //Setting post data as xml
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $result = curl_exec($curl);

            $log->write($result);
            curl_close($curl);
            $result = json_decode($result, true);
            $log->write($result);
            $json = $result;
            if ($result['status'] == 200 && $result['response_code'] == 0 && $result['error'] == false) {
                $this->session->data['pezesha_amount_limit'] = $this->currency->format($result['data']['amount'], $this->config->get('config_currency'));
                $this->session->data['pezesha_customer_amount_limit'] = $result['data']['amount'];
                $log->write('pezesha_amount_limit');
                $log->write($result['data']['amount']);
                $log->write('pezesha_amount_limit');
            }
        } else {
            $result['message'] = 'Please Check Your Pezesha Details!';
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($result));
    }

    public function getPezeshaCreditLimit() {

        $log = new Log('error.log');
        if ($this->customer->getCustomerPezeshaId() != NULL && $this->customer->getCustomerPezeshauuId() != NULL && $this->config->get('pezesha_status')) {

            $this->load->model('account/customer');

            $customer_id = $this->customer->getId();

            $customer_device_info = $this->model_account_customer->getCustomer($customer_id);
            $customer_pezesha_info = $this->model_account_customer->getPezeshaCustomer($customer_id);

            $auth_response = $this->auth();
            $log->write('auth_response');
            $log->write($auth_response);
            $log->write($customer_device_info);
            $log->write('auth_response');
            $body = array('identifier' => $customer_pezesha_info['prefix'] . '' . $customer_pezesha_info['customer_id'], 'channel' => $this->config->get('pezesha_channel'));
            //$body = http_build_query($body);
            $body = json_encode($body);
            $log->write($body);
            $curl = curl_init();
            if ($this->config->get('pezesha_environment') == 'live') {
                curl_setopt($curl, CURLOPT_URL, 'https://api.pezesha.com/mfi/v1/borrowers/options');
                curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Authorization:Bearer ' . $auth_response]);
            } else {
                curl_setopt($curl, CURLOPT_URL, 'https://staging.api.pezesha.com/mfi/v1/borrowers/options');
                curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Authorization:Bearer ' . $auth_response]);
            }
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body); //Setting post data as xml
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $result = curl_exec($curl);

            $log->write($result);
            curl_close($curl);
            $result = json_decode($result, true);
            $log->write($result);
            if ($result['status'] == 200 && $result['response_code'] == 0 && $result['error'] == false) {
                $log->write('pezesha_amount_limit');
                $log->write($result['data']['amount']);
                $log->write('pezesha_amount_limit');

                return $result['data']['amount'];
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function auth() {

        $log = new Log('error.log');

        $body = array('grant_type' => 'client_credentials', 'provider' => 'users', 'client_secret' => $this->config->get('pezesha_client_secret'), 'client_id' => $this->config->get('pezesha_client_id'), 'merchant _key' => $this->config->get('pezesha_merchant_key'));
        $body = http_build_query($body);
        $curl = curl_init();
        if ($this->config->get('pezesha_environment') == 'live') {
            curl_setopt($curl, CURLOPT_URL, 'https://api.pezesha.com/oauth/token');
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/x-www-form-urlencoded'));
        } else {
            curl_setopt($curl, CURLOPT_URL, 'https://staging.api.pezesha.com/oauth/token');
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/x-www-form-urlencoded'));
        }

        //$log->write($body);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body); //Setting post data as xml
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($curl);

        //$log->write($result);
        curl_close($curl);
        $result = json_decode($result, true);
        return $result['access_token'];
        /* $json['status'] = true;
          $json['data'] = $result;

          $this->response->addHeader('Content-Type: application/json');
          $this->response->setOutput(json_encode($json)); */
    }

    public function applyloan($args = []) {
        $json['status'] = false;
        if ($this->validatenew($args)) {
            $this->loanoffers();
            if ('pezesha' == $args['payment_method_code'] && $this->cart->getTotal() > $this->getPezeshaCreditLimit()) {
                $json['status'] = false;
                $json['message'] = 'Plese Check Your Pezesha Amount Limit!(' . $this->getPezeshaCreditLimit() . ')';
            }
            if ('pezesha' == $args['payment_method_code'] && $this->cart->getTotal() <= $this->getPezeshaCreditLimit()) {
                $log = new Log('error.log');
                $this->load->model('account/customer');
                $this->load->model('checkout/order');
                $this->load->model('payment/pezesha');

                $customer_id = $this->customer->getId();
                $amount = $this->cart->getTotal();
                $order_id = '#' . implode("#", $this->session->data['order_id']);

                $customer_device_info = $this->model_account_customer->getCustomer($customer_id);
                $customer_pezesha_info = $this->model_account_customer->getPezeshaCustomer($customer_id);

                $auth_response = $this->auth();
                $log->write('auth_response');
                $log->write($auth_response);
                $log->write($customer_device_info);
                $log->write('auth_response');
                //$payment_details = array('type' => 'BUY_GOODS/PAYBILL', 'number' => $order_id, 'callback_url' => $this->url->link('deliversystem/deliversystem/pezeshacallback', '', 'SSL'));
                $payment_details = NULL;
                $body = array('pezesha_id' => /* $customer_pezesha_info['pezesha_customer_id'] */$this->customer->getCustomerPezeshaId(), 'amount' => $amount, 'duration' => $this->config->get('pezesha_loan_duration'), 'interest' => ($this->config->get('pezesha_interest') / 100 * $amount), 'rate' => $this->config->get('pezesha_interest'), 'fee' => $this->config->get('pezesha_processing_fee'), 'channel' => $this->config->get('pezesha_channel'), 'payment_details' => $payment_details);
                //$body = http_build_query($body);
                $body = json_encode($body);
                $log->write($body);
                $curl = curl_init();
                if ($this->config->get('pezesha_environment') == 'live') {
                    curl_setopt($curl, CURLOPT_URL, 'https://api.pezesha.com/mfi/v1/borrowers/loans');
                    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Authorization:Bearer ' . $auth_response]);
                } else {
                    curl_setopt($curl, CURLOPT_URL, 'https://staging.api.pezesha.com/mfi/v1/borrowers/loans');
                    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Authorization:Bearer ' . $auth_response]);
                }
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $body); //Setting post data as xml
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                $result = curl_exec($curl);

                $log->write($result);
                curl_close($curl);
                $result = json_decode($result, true);
                $log->write($result);
                $json = $result;
                $loan_type = strtoupper(str_replace(' ', '_', $result['message']));
                $log->write('loan_type');
                $log->write($loan_type);
                $log->write('loan_type');
                //return $json;
                if ($result['status'] == 200 && $result['response_code'] == 0 && !$result['error']) {
                    foreach ($this->session->data['order_id'] as $key => $value) {
                        $order_id = $value;
                        $this->model_account_customer->SaveCustomerLoans($this->customer->getId(), $order_id, $result['data']['loan_id'], $loan_type);
                        $this->model_payment_pezesha->insertOrderTransactionId($order_id, 'PEZESHA_' . $result['data']['loan_id'], $this->customer->getId());
                        $ret = $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('pezesha_order_status_id'), 'Paid With Pezesha', true, $this->customer->getId(), 'customer', '', 'Y');
                    }
                    $json['status'] = true;
                    $json['message'] = 'Pezesha Loan Applied Successfully!';
                    $json['data'] = $result;
                } else {
                    $json['status'] = false;
                    $json['message'] = 'Please Select Other Payment Option!';
                    $json['data'] = $result;
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function applyloanone($orders) {
        $json['status'] = false;
        if ($this->cart->getTotal() > $this->getPezeshaCreditLimit()) {
            $json['status'] = false;
            $json['message'] = 'Plese Check Your Pezesha Amount Limit!(' . $this->getPezeshaCreditLimit() . ')';
            $json['data']['status'] = 400;
            $json['data']['response_code'] = 1;
            $json['data']['error'] = TRUE;
            $json['data']['message'] = '';
        }
        if ($this->cart->getTotal() <= $this->getPezeshaCreditLimit()) {
            $log = new Log('error.log');
            $this->load->model('account/customer');
            $this->load->model('checkout/order');
            $this->load->model('payment/pezesha');

            $customer_id = $this->customer->getId();
            $amount = $this->cart->getTotal();
            $order_id = '#' . implode("#", $orders);

            $customer_device_info = $this->model_account_customer->getCustomer($customer_id);
            $customer_pezesha_info = $this->model_account_customer->getPezeshaCustomer($customer_id);

            $auth_response = $this->auth();
            $log->write('auth_response');
            $log->write($auth_response);
            $log->write($customer_device_info);
            $log->write('auth_response');
            //$payment_details = array('type' => 'BUY_GOODS/PAYBILL', 'number' => $order_id, 'callback_url' => $this->url->link('deliversystem/deliversystem/pezeshacallback', '', 'SSL'));
            $payment_details = NULL;
            $order_ids = array_values($orders);
            $body = array('order_id' => $order_ids, 'pezesha_id' => /* $customer_pezesha_info['pezesha_customer_id'] */$this->customer->getCustomerPezeshaId(), 'amount' => $amount, 'duration' => $this->config->get('pezesha_loan_duration'), 'interest' => ($this->config->get('pezesha_interest') / 100 * $amount), 'rate' => $this->config->get('pezesha_interest'), 'fee' => $this->config->get('pezesha_processing_fee'), 'channel' => $this->config->get('pezesha_channel'), 'payment_details' => $payment_details);
            //$body = http_build_query($body);
            $body = json_encode($body);
            $log->write($body);
            $curl = curl_init();
            if ($this->config->get('pezesha_environment') == 'live') {
                curl_setopt($curl, CURLOPT_URL, 'https://api.pezesha.com/mfi/v1/borrowers/orders');
                curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Authorization:Bearer ' . $auth_response]);
            } else {
                curl_setopt($curl, CURLOPT_URL, 'https://staging.api.pezesha.com/mfi/v1/borrowers/orders');
                curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Authorization:Bearer ' . $auth_response]);
            }
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body); //Setting post data as xml
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $result = curl_exec($curl);

            $log->write($result);
            curl_close($curl);
            $result = json_decode($result, true);
            $log->write($result);
            $json = $result;
            $loan_type = strtoupper(str_replace(' ', '_', $result['message']));
            $log->write('loan_type');
            $log->write($loan_type);
            $log->write('loan_type');
            //return $json;
            if ($result['status'] == 200 && $result['response_code'] == 0 && !$result['error']) {
                foreach ($orders as $order_id) {
                    $this->model_account_customer->SaveCustomerLoans($this->customer->getId(), $order_id, $result['data']['loan_id'], $loan_type);
                    //$this->model_payment_pezesha->insertOrderTransactionId($order_id, 'PEZESHA_' . $result['data']['loan_id'], $this->customer->getId());
                    // $ret = $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('pezesha_order_status_id'), 'Paid With Pezesha', true, $this->customer->getId(), 'customer', '', 'Y');
                    $ret = $this->model_checkout_order->addOrderHistory($order_id, 14/* $this->config->get('pezesha_order_status_id') */, 'Applied For Pezesha Loan', true, $this->customer->getId(), 'customer', '', 'N');
                }
                $json['status'] = true;
                $json['message'] = 'Pezesha Loan Applied Successfully!';
                $json['data'] = $result;
            } else {
                $json['status'] = false;
                $json['message'] = 'Please Select Other Payment Option!';
                $json['data'] = $result;
            }
        }
        return $json;
        /* $this->response->addHeader('Content-Type: application/json');
          $this->response->setOutput(json_encode($json)); */
    }

    public function applyloanonehybrid($orders) {
        $json['status'] = false;

        $this->load->model('account/credit');
        $customer_wallet_total = $this->model_account_credit->getTotalAmount();

        if ($this->getPezeshaCreditLimit() <= 0) {
            $json['status'] = false;
            $json['message'] = 'Plese Check Your Pezesha Amount Limit!(' . $this->getPezeshaCreditLimit() . ')';
            $json['data']['status'] = 400;
            $json['data']['response_code'] = 1;
            $json['data']['error'] = TRUE;
            $json['data']['message'] = '';
        } elseif ($customer_wallet_total <= 0) {
            $json['status'] = false;
            $json['message'] = 'Plese Check Your Wallet Amount!(' . $customer_wallet_total . 'KES)';
            $json['data']['status'] = 400;
            $json['data']['response_code'] = 1;
            $json['data']['error'] = TRUE;
            $json['data']['message'] = '';
        }
        if ($this->getPezeshaCreditLimit() > 0 && $customer_wallet_total > 0) {
            $log = new Log('error.log');
            $this->load->model('account/customer');
            $this->load->model('checkout/order');
            $this->load->model('payment/pezesha');
            $this->load->model('account/credit');
            $this->load->model('payment/wallet');
            $this->load->model('sale/order');

            $amount = $this->cart->getTotal();
            $customer_wallet_total = $this->model_account_credit->getTotalAmount();
            if ($customer_wallet_total > 0) {
                $amount = $amount - $customer_wallet_total;
                $amount = abs($amount);
            }

            $customer_id = $this->customer->getId();
            $order_id = '#' . implode("#", $orders);

            $customer_device_info = $this->model_account_customer->getCustomer($customer_id);
            $customer_pezesha_info = $this->model_account_customer->getPezeshaCustomer($customer_id);

            $auth_response = $this->auth();
            $log->write('auth_response');
            $log->write($auth_response);
            $log->write($customer_device_info);
            $log->write('auth_response');
            //$payment_details = array('type' => 'BUY_GOODS/PAYBILL', 'number' => $order_id, 'callback_url' => $this->url->link('deliversystem/deliversystem/pezeshacallback', '', 'SSL'));
            $payment_details = NULL;
            $order_ids = array_values($orders);
            $body = array('order_id' => $order_ids, 'pezesha_id' => /* $customer_pezesha_info['pezesha_customer_id'] */$this->customer->getCustomerPezeshaId(), 'amount' => $amount, 'duration' => $this->config->get('pezesha_loan_duration'), 'interest' => ($this->config->get('pezesha_interest') / 100 * $amount), 'rate' => $this->config->get('pezesha_interest'), 'fee' => $this->config->get('pezesha_processing_fee'), 'channel' => $this->config->get('pezesha_channel'), 'payment_details' => $payment_details);
            //$body = http_build_query($body);
            $body = json_encode($body);
            $log->write($body);
            $curl = curl_init();
            if ($this->config->get('pezesha_environment') == 'live') {
                curl_setopt($curl, CURLOPT_URL, 'https://api.pezesha.com/mfi/v1/borrowers/orders');
                curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Authorization:Bearer ' . $auth_response]);
            } else {
                curl_setopt($curl, CURLOPT_URL, 'https://staging.api.pezesha.com/mfi/v1/borrowers/orders');
                curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Authorization:Bearer ' . $auth_response]);
            }
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body); //Setting post data as xml
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $result = curl_exec($curl);

            $log->write($result);
            curl_close($curl);
            $result = json_decode($result, true);
            $log->write($result);
            $json = $result;
            $loan_type = strtoupper(str_replace(' ', '_', $result['message']));
            $log->write('loan_type');
            $log->write($loan_type);
            $log->write('loan_type');
            //return $json;
            if ($result['status'] == 200 && $result['response_code'] == 0 && !$result['error']) {
                foreach ($orders as $order_id) {
                    $customer_wallet_total = $this->model_account_credit->getTotalAmount();

                    if ($customer_wallet_total > 0) {
                        $totals = $this->model_sale_order->getOrderTotals($order_id);
                        $log->write($totals);
                        $total = 0;
                        foreach ($totals as $total) {
                            if ('total' == $total['code']) {
                                $total = $total['value'];
                                break;
                            }
                        }
                        if ($customer_wallet_total > 0 && $totals != NULL && $total > 0 && $total <= $customer_wallet_total) {
                            $this->model_payment_wallet->addTransactionCreditForHybridPayment($this->customer->getId(), "Wallet amount deducted #" . $order_id, $total, $order_id, 'Y', 0);
                            $this->model_sale_order->UpdatePaymentMethod($order_id, 'Wallet Payment', 'wallet');
                            $ret = $this->model_checkout_order->addOrderHistory($order_id, 1, 'Paid Through Wallet By Customer', FALSE, $this->customer->getId(), 'customer');
                        } elseif ($customer_wallet_total > 0 && $totals != NULL && $total > 0 && $total > $customer_wallet_total) {
                            $this->model_payment_wallet->addTransactionCreditForHybridPayment($this->customer->getId(), "Wallet amount deducted #" . $order_id, $customer_wallet_total, $order_id, 'P', $customer_wallet_total);
                            $this->model_sale_order->UpdatePaymentMethod($order_id, 'Wallet Payment', 'wallet');
                            $ret = $this->model_checkout_order->addOrderHistory($order_id, 14, 'Paid Partially Through Wallet By Customer', FALSE, $this->customer->getId(), 'customer');

                            $order_info = $this->model_checkout_order->getOrder($order_id);

                            $log->write('order_info');
                            $log->write($order_info);
                            $log->write('order_info');
                            if ($order_info['paid'] == 'P') {
                                $this->model_account_customer->SaveCustomerLoans($this->customer->getId(), $order_id, 0/* $result['data']['loan_id'] */, $loan_type);
                                //$this->model_payment_pezesha->insertOrderTransactionId($order_id, 'PEZESHA_' . $result['data']['loan_id'], $this->customer->getId());
                                $ret = $this->model_checkout_order->addOrderHistory($order_id, 14/* $this->config->get('pezesha_order_status_id') */, 'Applied For Pezesha Loan', true, $this->customer->getId(), 'customer', '', 'P');
                            }
                        } elseif ($customer_wallet_total == 0 && $totals != NULL && $total > 0 && $total > $customer_wallet_total) {
                            $this->model_account_customer->SaveCustomerLoans($this->customer->getId(), $order_id, 0/* $result['data']['loan_id'] */, $loan_type);
                            $ret = $this->model_checkout_order->addOrderHistory($order_id, 14/* $this->config->get('pezesha_order_status_id') */, 'Applied For Pezesha Loan', true, $this->customer->getId(), 'customer', '', 'N');
                        }
                        /* WALLET */
                    }
                }
                $json['status'] = true;
                $json['message'] = 'Pezesha Loan Applied Successfully!';
                $json['data'] = $result;
            } else {
                $json['status'] = false;
                $json['message'] = 'Please Select Other Payment Option!';
                $json['data'] = $result;
            }
        }
        return $json;
        /* $this->response->addHeader('Content-Type: application/json');
          $this->response->setOutput(json_encode($json)); */
    }

    public function loanoffers() {

        $log = new Log('error.log');
        $this->load->model('account/customer');

        $customer_id = $this->customer->getId();

        $customer_device_info = $this->model_account_customer->getCustomer($customer_id);
        $customer_pezesha_info = $this->model_account_customer->getPezeshaCustomer($customer_id);

        $auth_response = $this->auth();
        $log->write('auth_response');
        $log->write($auth_response);
        $log->write($customer_device_info);
        $log->write('auth_response');
        $body = array('identifier' => /* $customer_pezesha_info['customer_id'] */$this->customer->getCustomerPezeshaIdentifier(), 'channel' => $this->config->get('pezesha_channel'));
        //$body = http_build_query($body);
        $body = json_encode($body);
        $log->write($body);
        $curl = curl_init();
        if ($this->config->get('pezesha_environment') == 'live') {
            curl_setopt($curl, CURLOPT_URL, 'https://api.pezesha.com/mfi/v1/borrowers/options');
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Authorization:Bearer ' . $auth_response]);
        } else {
            curl_setopt($curl, CURLOPT_URL, 'https://staging.api.pezesha.com/mfi/v1/borrowers/options');
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Authorization:Bearer ' . $auth_response]);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body); //Setting post data as xml
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($curl);

        $log->write($result);
        curl_close($curl);
        $result = json_decode($result, true);
        $log->write($result);
        $json = $result;
        if ($result['status'] == 200 && $result['response_code'] == 0 && $result['error'] == false) {
            $this->session->data['pezesha_amount_limit'] = $this->currency->format($result['data']['amount'], $this->config->get('config_currency'));
            $this->session->data['pezesha_customer_amount_limit'] = $result['data']['amount'];
            $log->write('pezesha_amount_limit');
            $log->write($result['data']['amount']);
            $log->write('pezesha_amount_limit');
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/loan_offers.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/loan_offers.tpl', $result));
        } else {
            $this->response->setOutput($this->load->view('default/template/account/loan_offers.tpl', $result));
        }
    }

    protected function validatenew($args) {
        if (empty($args['payment_method'])) {
            $this->error['payment_method'] = $this->language->get('error_payment_method');
        }

        if (empty($args['payment_method_code'])) {
            $this->error['payment_method_code'] = $this->language->get('error_payment_method_code');
        }

        if (empty($args['shipping_address_id'])) {
            $this->error['shipping_address_id'] = $this->language->get('error_shipping_address_id');
        }

        if (empty($args['stores'])) {
            $this->error['error_stores'] = $this->language->get('error_stores');
        }

        if (empty($args['products'])) {
            $this->error['error_products'] = $this->language->get('error_products');
        }

        $vendor_terms = json_decode($this->getCheckOtherVendorOrderExist(), true);
        if ($vendor_terms['modal_open'] == TRUE) {
            $this->error['vendor_terms'] = 'Please accept vendor terms!';
        }

        $pending_orders_count = $this->getunpaidorderscount();
        if ($pending_orders_count['unpaid_orders_count'] > 0) {
            $this->error['unpaid_orders'] = 'Your Order(s) Payment Is Pending!';
        }

        return !$this->error;
    }

    public function getunpaidorderscount() {
        $json = [];
        $log = new Log('error.log');
        $log->write($this->customer->getPaymentTerms());
        $log->write($this->customer->getId());

        $data['pending_order_id'] = NULL;

        if ($this->customer->getPaymentTerms() == 'Payment On Delivery') {
            $this->load->model('account/order');
            $this->load->model('sale/order');
            $page = 1;
            $results_orders = $this->model_account_order->getOrdersNew(($page - 1) * 10, 10, $NoLimit = true);
            $PaymentFilter = ['mPesa On Delivery', 'Cash On Delivery', 'mPesa Online', 'Corporate Account/ Cheque Payment', 'PesaPal', 'Interswitch', 'Pezesha'];
            if (count($results_orders) > 0) {
                foreach ($results_orders as $order) {
                    if (in_array($order['payment_method'], $PaymentFilter) && ($order['order_status_id'] == 4 || $order['order_status_id'] == 5)) {
                        $order['transcation_id'] = $this->model_sale_order->getOrderTransactionId($order['order_id']);
                        if (empty($order['transcation_id']) || $order['paid'] == 'P') {
                            $data['pending_order_id'][] = $order['order_id'];
                        }
                    }
                }
            }
        }

        $data['unpaid_orders_count'] = count($data['pending_order_id']);
        $data['message'] = count($data['pending_order_id']) > 0 ? 'Your Order(s) Payment Is Pending!' : '';
        return $data;
    }

    public function getCheckOtherVendorOrderExist() {

        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];
        $log = new Log('error.log');
        $json['modal_open'] = FALSE;
        if (isset($this->session->data['accept_vendor_terms']) && $this->session->data['accept_vendor_terms'] == TRUE) {
            $json['modal_open'] = FALSE;
        } else {
            foreach ($this->cart->getProducts() as $store_products) {
                /* FOR KWIKBASKET ORDERS */
                $log->write('CheckOtherVendorOrderExists');
                $log->write($store_products['store_id']);
                $log->write('CheckOtherVendorOrderExists');
                if ($store_products['store_id'] > 75 && $this->customer->getPaymentTerms() != 'Payment On Delivery') {
                    $json['modal_open'] = TRUE;
                }
            }
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addloanStatus() {

        $log = new Log('error.log');
        $this->load->model('account/customer');

        $customer_id = $this->customer->getId();

        $customer_device_info = $this->model_account_customer->getCustomer($customer_id);
        $customer_pezesha_info = $this->model_account_customer->getPezeshaCustomer($customer_id);

        $auth_response = $this->auth();
        $log->write('auth_response');
        $log->write($auth_response);
        $log->write($customer_device_info);
        $log->write('auth_response');
        //$this->request->post['order_id'] = array("55789E36D67", "5545E36D90");
        $body = array('identifier' => $this->request->post['order_id'], 'channel' => $this->config->get('pezesha_channel'));
        //$body = http_build_query($body);
        $body = json_encode($body);
        $log->write($body);
        $curl = curl_init();
        if ($this->config->get('pezesha_environment') == 'live') {
            curl_setopt($curl, CURLOPT_URL, 'https://api.pezesha.com/mfi/v1/borrowers/latest');
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Authorization:Bearer ' . $auth_response]);
        } else {
            curl_setopt($curl, CURLOPT_URL, 'https://staging.api.pezesha.com/mfi/v1/borrowers/latest');
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Authorization:Bearer ' . $auth_response]);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body); //Setting post data as xml
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($curl);

        $log->write($result);
        curl_close($curl);
        $result = json_decode($result, true);
        $log->write($result);
        $json = $result;

        // if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/pezesha_loan_info.tpl')) {
        //     $html = $this->load->view($this->config->get('config_template') . '/template/account/pezesha_loan_info.tpl', $json['data']);
        // }
        // echo json_encode(['html' => $html]);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($result));
    }

}
