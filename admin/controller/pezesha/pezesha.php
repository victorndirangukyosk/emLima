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

    public function userregistration($customer_id) {

        $this->load->model('sale/customer');
        $this->load->model('pezesha/pezesha');
        $customer_device_info = $this->model_sale_customer->getCustomer($customer_id);
        $auth_response = $this->auth();
        $log = new Log('error.log');
        $log->write('auth_response');
        $log->write($auth_response);
        $log->write($customer_device_info);
        $log->write('auth_response');
        $body = array('terms' => TRUE, 'full_names' => $customer_device_info['firstname'] . ' ' . $customer_device_info['lastname'], 'phone' => '254' . '' . $customer_device_info['telephone'], 'other_phone_nos' => array('254' . '' . $customer_device_info['telephone'], '254' . '' . $customer_device_info['telephone']), 'national_id' => $customer_device_info['national_id'], 'dob' => date('Y-m-d', strtotime($customer_device_info['dob'])), 'email' => $customer_device_info['email'], 'merchant_id' => $customer_device_info['customer_id'], 'merchant_reg_date' => date('Y-m-d', strtotime($customer_device_info['date_added'])), 'location' => $customer_device_info['company_name'] . '' . $customer_device_info['company_address'], 'geo_location' => array('long' => $customer_device_info['longitude'], 'lat' => $customer_device_info['latitude']));
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
        $log->write($result);
        if (is_array($result) && array_key_exists('status', $result) && array_key_exists('response_code', $result) && array_key_exists('data', $result) && $result['response_code'] == 0 && $result['status'] == 200) {
            $data['customer_id'] = $result['data']['merchant_id'];
            $data['pezesha_customer_id'] = $result['data']['customer_id'];
            $data['customer_uuid'] = $result['data']['customer_uuid'];
            $customer_device_info = $this->model_pezesha_pezesha->addCustomer($data);
        }
        $json = $result;
        return $json;

        /* $json['status'] = true;
          $json['data'] = $result;

          $this->response->addHeader('Content-Type: application/json');
          $this->response->setOutput(json_encode($json)); */
    }

    public function acceptterms($customer_id) {

        $this->load->model('sale/customer');
        $this->load->model('pezesha/pezesha');
        $customer_device_info = $this->model_sale_customer->getCustomer($customer_id);
        $customer_pezesha_info = $this->model_pezesha_pezesha->getCustomer($customer_id);
        $auth_response = $this->auth();
        $log = new Log('error.log');
        $log->write('auth_response');
        $log->write($auth_response);
        $log->write($customer_device_info);
        $log->write('auth_response');
        $body = array('channel' => $this->config->get('pezesha_channel'), 'identifier' => $customer_device_info['national_id'], 'terms' => TRUE);
        $body = http_build_query($body);
        //$body = json_encode($body);
        $curl = curl_init();
        if (ENV == 'production') {
            curl_setopt($curl, CURLOPT_URL, 'https://staging.api.pezesha.com/mfi/v1/borrowers/terms');
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/x-www-form-urlencoded', 'Authentication:Bearer ' . $auth_response]);
        } else {
            curl_setopt($curl, CURLOPT_URL, 'https://staging.api.pezesha.com/mfi/v1/borrowers/terms');
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/x-www-form-urlencoded', 'Authentication:Bearer ' . $auth_response]);
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
        return $json;

        /* $json['status'] = true;
          $json['data'] = $result;

          $this->response->addHeader('Content-Type: application/json');
          $this->response->setOutput(json_encode($json)); */
    }

}
