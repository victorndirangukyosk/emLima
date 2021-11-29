<?php

class ControllerApiCustomerPezesha extends Controller {

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
            if (ENV == 'production') {
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
        if (ENV == 'production') {
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

}
