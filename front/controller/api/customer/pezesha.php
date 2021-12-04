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
            $body = array('identifier' => $customer_pezesha_info['customer_id'], 'channel' => $this->config->get('pezesha_channel'));
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
            if ('pezesha' == $args['payment_method_code'] && $this->cart->getTotal() > $this->session->data['pezesha_customer_amount_limit']) {
                $json['status'] = false;
                $json['message'] = 'Plese Check Your Pezesha Amount Limit!(' . $this->session->data['pezesha_amount_limit'] . ')';
            }
            if ('pezesha' == $args['payment_method_code'] && $this->cart->getTotal() <= $this->session->data['pezesha_customer_amount_limit']) {
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
                $body = array('pezesha_id' => /*$customer_pezesha_info['pezesha_customer_id']*/$this->customer->getCustomerPezeshaId(), 'amount' => $amount, 'duration' => $this->config->get('pezesha_loan_duration'), 'interest' => ($this->config->get('pezesha_interest') / 100 * $amount), 'rate' => $this->config->get('pezesha_interest'), 'fee' => $this->config->get('pezesha_processing_fee'), 'channel' => $this->config->get('pezesha_channel'), 'payment_details' => $payment_details);
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
        $body = array('identifier' => /*$customer_pezesha_info['customer_id']*/$this->customer->getCustomerPezeshaIdentifier(), 'channel' => $this->config->get('pezesha_channel'));
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

}
