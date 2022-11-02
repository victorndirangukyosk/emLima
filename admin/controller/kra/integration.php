<?php

class ControllerKraIntegration extends Controller {

    public function index() {
        $data = array();
        $data['fp'] = '';
        $data['DevIp'] = '197.254.20.107';
        $data['DevTcpPort'] = '8000';
        $data['DevTcpPassword'] = 'Password';

        $response = $this->load->controller('kra/kra/fpServerSetDeviceTcpSettings', $data);
        return $response;
    }

    public function settings() {

        $log = new Log('error.log');

        $com = $this->request->post['com'];
        $baud = $this->request->post['baud'];
        $tcp = $this->request->post['tcp'];
        $ip = $this->request->post['ip'];
        $port = $this->request->post['port'];
        $password = $this->request->post['password'];

        $settings = "(com=" . $com . ",baud=" . $baud . ",tcp=" . $tcp . ",ip=" . $ip . ",port=" . $port . ",password=" . $password . ")";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://localhost:4444/Settings' . $settings);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/x-www-form-urlencoded'));

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($curl);
        $xml_snippet = simplexml_load_string($result);
        $json_convert = json_encode($xml_snippet);

        $log->write($result);
        curl_close($curl);
        $final_result = json_decode($json_convert, true);
        $json['status'] = true;
        $json['data'] = $final_result;

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
