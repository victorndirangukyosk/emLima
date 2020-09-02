<?php

class Iugu_APIRequest
{
    public function __construct()
    {
    }

    private function _defaultHeaders($headers = [])
    {
        $headers[] = 'Authorization: Basic '.base64_encode(Iugu::getApiKey().':');
        $headers[] = 'Accept: application/json';
        $headers[] = 'Accept-Charset: utf-8';
        $headers[] = 'User-Agent: Iugu PHPLibrary';
        $headers[] = 'Accept-Language: pt-br;q=0.9,pt-BR';

        return $headers;
    }

    public function request($method, $url, $data = [])
    {
        global $iugu_last_api_response_code;

        if (null == Iugu::getApiKey()) {
            Iugu_Utilities::authFromEnv();
        }

        if (null == Iugu::getApiKey()) {
            throw new IuguAuthenticationException('Chave de API não configurada. Utilize Iugu::setApiKey(...) para configurar.');
        }
        $headers = $this->_defaultHeaders();

        list($response_body, $response_code) = $this->requestWithCURL($method, $url, $headers, $data);

        $response = json_decode($response_body);

        if (JSON_ERROR_NONE != json_last_error()) {
            throw new IuguObjectNotFound($response_body);
        }
        if (404 == $response_code) {
            throw new IuguObjectNotFound($response_body);
        }
        if (isset($response->errors)) {
            if (('string' != gettype($response->errors)) && 0 == count(get_object_vars($response->errors))) {
                unset($response->errors);
            } elseif (('string' != gettype($response->errors)) && count(get_object_vars($response->errors)) > 0) {
                $response->errors = (array) $response->errors;
            }

            if (isset($response->errors) && ('string' == gettype($response->errors))) {
                $response->errors = $response->errors;
            }
        }

        $iugu_last_api_response_code = $response_code;

        return $response;
    }

    private function encodeParameters($method, $url, $data = [])
    {
        $method = strtolower($method);

        switch ($method) {
    case 'get':
    case 'delete':
      $paramsInURL = Iugu_Utilities::arrayToParams($data);
      $data = null;
      $url = (strpos($url, '?')) ? $url.'&'.$paramsInURL : $url.'?'.$paramsInURL;
      break;
    case 'post':
    case 'put':
      $data = Iugu_Utilities::arrayToParams($data);
      break;
    }

        return [$url, $data];
    }

    private function requestWithCURL($method, $url, $headers, $data = [])
    {
        $curl = curl_init();

        $opts = [];

        list($url, $data) = $this->encodeParameters($method, $url, $data);

        if ('post' == strtolower($method)) {
            $opts[CURLOPT_POST] = 1;
            $opts[CURLOPT_POSTFIELDS] = $data;
        }
        if ('delete' == strtolower($method)) {
            $opts[CURLOPT_CUSTOMREQUEST] = 'DELETE';
        }

        if ('put' == strtolower($method)) {
            $opts[CURLOPT_CUSTOMREQUEST] = 'PUT';
            $opts[CURLOPT_POSTFIELDS] = $data;
        }

        $opts[CURLOPT_URL] = $url;
        $opts[CURLOPT_RETURNTRANSFER] = true;
        $opts[CURLOPT_CONNECTTIMEOUT] = 30;
        $opts[CURLOPT_TIMEOUT] = 80;
        $opts[CURLOPT_RETURNTRANSFER] = true;
        $opts[CURLOPT_HTTPHEADER] = $headers;

        $opts[CURLOPT_SSL_VERIFYHOST] = 2;
        $opts[CURLOPT_SSL_VERIFYPEER] = true;
        $opts[CURLOPT_CAINFO] = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'data').DIRECTORY_SEPARATOR.'ca-bundle.crt';

        curl_setopt_array($curl, $opts);

        $response_body = curl_exec($curl);
        $response_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        return [$response_body, $response_code];
    }
}
