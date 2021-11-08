<?php

class ControllerPezeshaPezesha extends Controller {

    private $error = [];

    public function index() {
        
    }

    public function auth() {

        $log = new Log('error.log');
        $body = array('grant_type' => 'client_credentials', 'provider' => 'users', 'client_secret' => $this->config->get('pezesha_client_secret'), 'client_id' => $this->config->get('pezesha_client_id'), 'merchant _key' => $this->config->get('pezesha_merchant_key'));
        $curl = curl_init();
        if (ENV == 'production') {
            curl_setopt($curl, CURLOPT_URL, 'https://staging.api.pezesha.com/oauth/token');
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/x-www-form-urlencoded']);
        } else {
            curl_setopt($curl, CURLOPT_URL, 'https://staging.api.pezesha.com/oauth/token');
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/x-www-form-urlencoded']);
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
    }

    public function userregistration() {

        $log = new Log('error.log');
        $body = array('grant_type' => 'client_credentials', 'provider' => 'users', 'client_secret' => $this->config->get('pezesha_client_secret'), 'client_id' => $this->config->get('pezesha_client_id'), 'merchant _key' => $this->config->get('pezesha_merchant_key'));
        $curl = curl_init();
        if (ENV == 'production') {
            curl_setopt($curl, CURLOPT_URL, 'https://staging.api.pezesha.com/mfi/v1/borrowers');
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Authentication:Bearer ']);
        } else {
            curl_setopt($curl, CURLOPT_URL, 'https://staging.api.pezesha.com/mfi/v1/borrowers');
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Authentication:Bearer ']);
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
    }

}
