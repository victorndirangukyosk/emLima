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

        $com = isset($this->request->post['com']) && $this->request->post['com'] != NULL ? $this->request->post['com'] : NULL;
        $baud = isset($this->request->post['baud']) && $this->request->post['baud'] != NULL ? $this->request->post['baud'] : NULL;
        $tcp = isset($this->request->post['tcp']) && $this->request->post['tcp'] != NULL ? $this->request->post['tcp'] : 1;
        $ip = isset($this->request->post['ip']) && $this->request->post['ip'] != NULL ? $this->request->post['ip'] : '197.254.20.107';
        $port = isset($this->request->post['port']) && $this->request->post['port'] != NULL ? $this->request->post['port'] : '8000';
        $password = isset($this->request->post['password']) && $this->request->post['password'] != NULL ? $this->request->post['password'] : 'Password';

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

    public function readstatus() {

        $log = new Log('error.log');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://localhost:4444/ReadStatus()');
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

    public function cancelreceipt() {

        $log = new Log('error.log');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://localhost:4444/CancelReceipt()');
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

    public function openinvoicewithfreecustomerdata() {

        $log = new Log('error.log');

        $CompanyName = isset($this->request->post['CompanyName']) && $this->request->post['CompanyName'] != NULL ? $this->request->post['CompanyName'] : NULL;
        $ClientPINnum = isset($this->request->post['ClientPINnum']) && $this->request->post['ClientPINnum'] != NULL ? $this->request->post['ClientPINnum'] : NULL;
        $HeadQuarters = isset($this->request->post['HeadQuarters']) && $this->request->post['HeadQuarters'] != NULL ? $this->request->post['HeadQuarters'] : NULL;
        $Address = isset($this->request->post['Address']) && $this->request->post['Address'] != NULL ? $this->request->post['Address'] : NULL;
        $PostalCodeAndCity = isset($this->request->post['PostalCodeAndCity']) && $this->request->post['PostalCodeAndCity'] != NULL ? $this->request->post['PostalCodeAndCity'] : NULL;
        $ExemptionNum = isset($this->request->post['ExemptionNum']) && $this->request->post['ExemptionNum'] != NULL ? $this->request->post['ExemptionNum'] : NULL;
        $TraderSystemInvNum = isset($this->request->post['TraderSystemInvNum']) && $this->request->post['TraderSystemInvNum'] != NULL ? $this->request->post['TraderSystemInvNum'] : NULL;

        $invoice_data = "(CompanyName=" . $CompanyName . ",ClientPINnum=" . $ClientPINnum . ",HeadQuarters=" . $HeadQuarters . ",Address=" . $Address . ",PostalCodeAndCity=" . $PostalCodeAndCity . ",ExemptionNum=" . $ExemptionNum . ",TraderSystemInvNum=" . $TraderSystemInvNum . ")";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://localhost:4444/Settings' . $invoice_data);
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
