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
        if (ENV == 'production') {
            curl_setopt($curl, CURLOPT_URL, 'https://staging.api.pezesha.com/oauth/token');
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
        $body = array('identifier' => $customer_pezesha_info['customer_id'], 'channel' => $this->config->get('pezesha_channel'));
        //$body = http_build_query($body);
        $body = json_encode($body);
        $log->write($body);
        $curl = curl_init();
        if (ENV == 'production') {
            curl_setopt($curl, CURLOPT_URL, 'https://staging.api.pezesha.com/mfi/v1/borrowers/options');
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

    public function applyloan() {
        $json['status'] = false;
        $this->loanoffers();
        if ('pezesha' == $this->session->data['payment_method']['code'] && $this->cart->getTotal() > $this->session->data['pezesha_customer_amount_limit']) {
            $json['status'] = false;
            $json['message'] = 'Plese Check Your Pezesha Amount Limit!(' . $this->session->data['pezesha_amount_limit'] . ',' . $this->cart->getTotal() . ')';
        }
        if ('pezesha' == $this->session->data['payment_method']['code'] && $this->cart->getTotal() <= $this->session->data['pezesha_customer_amount_limit']) {
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
            $payment_details = array('type' => 'BUY_GOODS/PAYBILL', 'number' => $order_id, 'callback_url' => $this->url->link('deliversystem/deliversystem/pezeshacallback', '', 'SSL'));
            $body = array('pezesha_id' => $customer_pezesha_info['pezesha_customer_id'], 'amount' => $amount, 'duration' => 30, 'interest' => ($this->config->get('pezesha_interest') / 100 * $amount), 'rate' => $this->config->get('pezesha_interest'), 'fee' => 0, 'channel' => $this->config->get('pezesha_channel'), 'payment_details' => $payment_details);
            //$body = http_build_query($body);
            $body = json_encode($body);
            $log->write($body);
            $curl = curl_init();
            if (ENV == 'production') {
                curl_setopt($curl, CURLOPT_URL, 'https://staging.api.pezesha.com/mfi/v1/borrowers/loans');
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
                    $this->model_account_customer->SaveCustomerLoans($this->customer->getId(), $order_id, $result['data']['loan_id'], $order_id, $loan_type);
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

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
