<?php

class ControllerPaymentPezesha extends Controller {

    public function index() {
        $data['button_confirm'] = $this->language->get('button_confirm');

        $this->load->language('payment/pezesha');

        $data['text_loading'] = $this->language->get('text_loading');

        $data['continue'] = $this->url->link('checkout/success');
        $data['continue'] = $this->url->link('checkout/success');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/pezesha.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/payment/pezesha.tpl', $data);
        } else {
            return $this->load->view('default/template/payment/pezesha.tpl', $data);
        }
    }

    public function auth() {

        $log = new Log('error.log');

        $body = array('grant_type' => 'client_credentials', 'provider' => 'users', 'client_secret' => $this->config->get('pezesha_client_secret'), 'client_id' => $this->config->get('pezesha_client_id'), 'merchant _key' => $this->config->get('pezesha_merchant_key'));
        $body = http_build_query($body);
        $curl = curl_init();
        if ($this->config->get('pezesha_environment') == 'live') {
            $log->write('PEZESHA_PRODUCTION');
            $log->write($this->config->get('pezesha_environment'));
            curl_setopt($curl, CURLOPT_URL, 'https://api.pezesha.com/oauth/token');
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/x-www-form-urlencoded'));
        } else {
            $log->write('PEZESHA_SANDBOX');
            $log->write($this->config->get('pezesha_environment'));
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
        if ($result['status'] == 200 && $result['response_code'] == 0 && $result['error'] == true) {
            $this->session->data['pezesha_amount_limit'] = $this->currency->format(0, $this->config->get('config_currency'));
            $this->session->data['pezesha_customer_amount_limit'] = 0;
            $log->write('pezesha_amount_limit');
            $log->write(0);
            $log->write('pezesha_amount_limit');
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/loan_offers.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/loan_offers.tpl', $result));
        } else {
            $this->response->setOutput($this->load->view('default/template/account/loan_offers.tpl', $result));
        }
    }

    public function loanstatus() {

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

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/pezesha_loan_info.tpl')) {
            $html = $this->load->view($this->config->get('config_template') . '/template/account/pezesha_loan_info.tpl', $json['data']);
        }

        echo json_encode(['html' => $html]);
    }

    public function applyloan() {
        $json['status'] = false;
        $this->loanoffers();

        $amount = $this->cart->getTotal();
        $this->load->model('account/credit');
        $this->load->model('sale/order');
        $customer_wallet_total = $this->model_account_credit->getTotalAmount();
        if ($this->session->data['payment_wallet_method']['code'] == 'wallet' && $customer_wallet_total > 0) {
            $amount = $amount - $customer_wallet_total;
            $amount = abs($amount);
        }

        if ('pezesha' == $this->session->data['payment_method']['code'] && $amount > $this->session->data['pezesha_customer_amount_limit']) {
            $json['status'] = false;
            $json['message'] = 'Plese Check Your Pezesha Amount Limit!(' . $this->session->data['pezesha_amount_limit'] . ')';
        }
        if ('pezesha' == $this->session->data['payment_method']['code'] && $amount <= $this->session->data['pezesha_customer_amount_limit']) {
            $log = new Log('error.log');
            $this->load->model('account/customer');
            $this->load->model('checkout/order');
            $this->load->model('payment/pezesha');

            $customer_id = $this->customer->getId();
            //$amount = $this->cart->getTotal();
            $amount = ceil($amount);
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
            $order_ids = array_values($this->session->data['order_id']);
            $body = array('order_id' => $order_ids, 'pezesha_id' => /* $customer_pezesha_info['pezesha_customer_id'] */$this->customer->getCustomerPezeshaId(), 'amount' => $amount, 'duration' => $this->config->get('pezesha_loan_duration'), 'interest' => ceil(($this->config->get('pezesha_interest') / 100 * $amount)), 'rate' => $this->config->get('pezesha_interest'), 'fee' => $this->config->get('pezesha_processing_fee'), 'channel' => $this->config->get('pezesha_channel'), 'payment_details' => $payment_details);
            //$body = http_build_query($body);
            $body = json_encode($body);
            $log->write('APPLY_LOAN_BODY');
            $log->write($body);
            $log->write('APPLY_LOAN_BODY');
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

                    $customer_wallet_total = $this->model_account_credit->getTotalAmount();
                    if ($this->session->data['payment_wallet_method']['code'] == 'wallet' && $customer_wallet_total > 0) {
                        $this->load->model('payment/wallet');
                        $log->write($this->session->data['payment_wallet_method']);
                        $totals = $this->model_sale_order->getOrderTotals($value);
                        $log->write($totals);
                        $total = 0;
                        foreach ($totals as $total) {
                            if ('total' == $total['code']) {
                                $total = $total['value'];
                                break;
                            }
                        }
                        if ($customer_wallet_total > 0 && $totals != NULL && $total > 0 && $total <= $customer_wallet_total) {
                            $this->model_payment_wallet->addTransactionCreditForHybridPayment($this->customer->getId(), "Wallet amount deducted #" . $value, $total, $value, 'Y', 0);
                            $this->model_sale_order->UpdatePaymentMethod($value, $this->session->data['payment_wallet_method']['title'], $this->session->data['payment_wallet_method']['code']);
                            $ret = $this->model_checkout_order->addOrderHistory($value, 1, 'Paid Through Wallet By Customer', FALSE, $this->customer->getId(), 'customer');
                        } elseif ($customer_wallet_total > 0 && $totals != NULL && $total > 0 && $total > $customer_wallet_total) {
                            $this->model_payment_wallet->addTransactionCreditForHybridPayment($this->customer->getId(), "Wallet amount deducted #" . $value, $customer_wallet_total, $value, 'P', $customer_wallet_total);
                            $this->model_sale_order->UpdatePaymentMethod($value, $this->session->data['payment_wallet_method']['title'], $this->session->data['payment_wallet_method']['code']);
                            $ret = $this->model_checkout_order->addOrderHistory($value, 14, 'Paid Partially Through Wallet By Customer', FALSE, $this->customer->getId(), 'customer');

                            $order_info = $this->model_checkout_order->getOrder($value);

                            $log->write('order_info');
                            $log->write($order_info);
                            $log->write('order_info');
                            if ($order_info['paid'] == 'P') {
                                $this->model_account_customer->SaveCustomerLoans($this->customer->getId(), $order_id, $result['data']['loan_id'], $loan_type);
                                $this->model_payment_pezesha->insertOrderTransactionId($order_id, 'PEZESHA_' . $result['data']['loan_id'], $this->customer->getId());
                                $ret = $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('pezesha_order_status_id'), 'Paid With Pezesha', true, $this->customer->getId(), 'customer', '', 'Y');
                            }
                        }
                        /* WALLET */
                    } elseif (!isset($this->session->data['payment_wallet_method']['code']) || $this->session->data['payment_wallet_method']['code'] == 0 || $this->session->data['payment_wallet_method']['code'] != 'wallet' || $customer_wallet_total <= 0) {
                        $this->model_account_customer->SaveCustomerLoans($this->customer->getId(), $order_id, $result['data']['loan_id'], $loan_type);
                        $this->model_payment_pezesha->insertOrderTransactionId($order_id, 'PEZESHA_' . $result['data']['loan_id'], $this->customer->getId());
                        $ret = $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('pezesha_order_status_id'), 'Paid With Pezesha', true, $this->customer->getId(), 'customer', '', 'Y');
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

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    
    public function applyloanone() {
        $json['status'] = false;
        $this->loanoffers();

        $amount = $this->cart->getTotal();
        $this->load->model('account/credit');
        $this->load->model('sale/order');
        $customer_wallet_total = $this->model_account_credit->getTotalAmount();
        if ($this->session->data['payment_wallet_method']['code'] == 'wallet' && $customer_wallet_total > 0) {
            $amount = $amount - $customer_wallet_total;
            $amount = abs($amount);
        }

        if ('pezesha' == $this->session->data['payment_method']['code'] && $amount > $this->session->data['pezesha_customer_amount_limit']) {
            $json['status'] = false;
            $json['message'] = 'Plese Check Your Pezesha Amount Limit!(' . $this->session->data['pezesha_amount_limit'] . ')';
        }
        if ('pezesha' == $this->session->data['payment_method']['code'] && $amount <= $this->session->data['pezesha_customer_amount_limit']) {
            $log = new Log('error.log');
            $this->load->model('account/customer');
            $this->load->model('checkout/order');
            $this->load->model('payment/pezesha');

            $customer_id = $this->customer->getId();
            //$amount = $this->cart->getTotal();
            $amount = ceil($amount);
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
            $order_ids = array_values($this->session->data['order_id']);
            $body = array('order_id' => $order_ids, 'pezesha_id' => /* $customer_pezesha_info['pezesha_customer_id'] */$this->customer->getCustomerPezeshaId(), 'amount' => $amount, 'duration' => $this->config->get('pezesha_loan_duration'), 'interest' => ceil(($this->config->get('pezesha_interest') / 100 * $amount)), 'rate' => $this->config->get('pezesha_interest'), 'fee' => $this->config->get('pezesha_processing_fee'), 'channel' => $this->config->get('pezesha_channel'), 'payment_details' => $payment_details);
            //$body = http_build_query($body);
            $body = json_encode($body);
            $log->write('APPLY_LOAN_BODY');
            $log->write($body);
            $log->write('APPLY_LOAN_BODY');
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
                foreach ($this->session->data['order_id'] as $key => $value) {
                    $order_id = $value;

                    $customer_wallet_total = $this->model_account_credit->getTotalAmount();
                    if ($this->session->data['payment_wallet_method']['code'] == 'wallet' && $customer_wallet_total > 0) {
                        $this->load->model('payment/wallet');
                        $log->write($this->session->data['payment_wallet_method']);
                        $totals = $this->model_sale_order->getOrderTotals($value);
                        $log->write($totals);
                        $total = 0;
                        foreach ($totals as $total) {
                            if ('total' == $total['code']) {
                                $total = $total['value'];
                                break;
                            }
                        }
                        if ($customer_wallet_total > 0 && $totals != NULL && $total > 0 && $total <= $customer_wallet_total) {
                            $this->model_payment_wallet->addTransactionCreditForHybridPayment($this->customer->getId(), "Wallet amount deducted #" . $value, $total, $value, 'Y', 0);
                            $this->model_sale_order->UpdatePaymentMethod($value, $this->session->data['payment_wallet_method']['title'], $this->session->data['payment_wallet_method']['code']);
                            $ret = $this->model_checkout_order->addOrderHistory($value, 1, 'Paid Through Wallet By Customer', FALSE, $this->customer->getId(), 'customer');
                        } elseif ($customer_wallet_total > 0 && $totals != NULL && $total > 0 && $total > $customer_wallet_total) {
                            $this->model_payment_wallet->addTransactionCreditForHybridPayment($this->customer->getId(), "Wallet amount deducted #" . $value, $customer_wallet_total, $value, 'P', $customer_wallet_total);
                            $this->model_sale_order->UpdatePaymentMethod($value, $this->session->data['payment_wallet_method']['title'], $this->session->data['payment_wallet_method']['code']);
                            $ret = $this->model_checkout_order->addOrderHistory($value, 14, 'Paid Partially Through Wallet By Customer', FALSE, $this->customer->getId(), 'customer');

                            $order_info = $this->model_checkout_order->getOrder($value);

                            $log->write('order_info');
                            $log->write($order_info);
                            $log->write('order_info');
                            if ($order_info['paid'] == 'P') {
                                $this->model_account_customer->SaveCustomerLoans($this->customer->getId(), $order_id, $result['data']['loan_id'], $loan_type);
                                $this->model_payment_pezesha->insertOrderTransactionId($order_id, 'PEZESHA_' . $result['data']['loan_id'], $this->customer->getId());
                                $ret = $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('pezesha_order_status_id'), 'Paid With Pezesha', true, $this->customer->getId(), 'customer', '', 'Y');
                            }
                        }
                        /* WALLET */
                    } elseif (!isset($this->session->data['payment_wallet_method']['code']) || $this->session->data['payment_wallet_method']['code'] == 0 || $this->session->data['payment_wallet_method']['code'] != 'wallet' || $customer_wallet_total <= 0) {
                        $this->model_account_customer->SaveCustomerLoans($this->customer->getId(), $order_id, $result['data']['loan_id'], $loan_type);
                        $this->model_payment_pezesha->insertOrderTransactionId($order_id, 'PEZESHA_' . $result['data']['loan_id'], $this->customer->getId());
                        $ret = $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('pezesha_order_status_id'), 'Paid With Pezesha', true, $this->customer->getId(), 'customer', '', 'Y');
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

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
