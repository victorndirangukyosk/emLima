<?php

class ControllerPezeshaPezesha extends Controller {

    private $error = [];

    public function index() {
        
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

    public function userregistration() {

        $this->load->model('sale/customer');
        $customer_device_info = $this->model_sale_customer->getCustomer(702);
        $auth_response = $this->auth();
        $log = new Log('error.log');
        $log->write('auth_response');
        $log->write($auth_response);
        $log->write($customer_device_info);
        $log->write('auth_response');
        $body = array('full_names' => $customer_device_info['firstname'] . ' ' . $customer_device_info['lastname'], 'phone' => '254' . '' . $customer_device_info['telephone'], 'other_phone_nos' => $this->config->get('pezesha_client_secret'), 'national_id' => $this->config->get('pezesha_client_id'), 'dob' => $this->config->get('pezesha_merchant_key'), 'email' => '', 'merchant_id' => '', 'merchant_reg_date' => '', 'location' => '', 'geo_location' => '');
        //$body = http_build_query($body);
        $body = json_encode($body);
        $curl = curl_init();
        if (ENV == 'production') {
            curl_setopt($curl, CURLOPT_URL, 'https://staging.api.pezesha.com/mfi/v1/borrowers');
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Authentication:Bearer ' . $auth_response]);
        } else {
            curl_setopt($curl, CURLOPT_URL, 'https://staging.api.pezesha.com/mfi/v1/borrowers');
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Authentication:Bearer ' . $auth_response]);
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
        $json = $result;

        $json['status'] = true;
        $json['data'] = $result;

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
