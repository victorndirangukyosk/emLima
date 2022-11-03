<?php

class ControllerKraIntegration extends Controller {

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

        $read_status = $this->readstatuss();

        $json['status'] = true;
        $json['data'] = $final_result;
        $json['device_status_code'] = $read_status['device_status_code'];
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

    public function readstatuss() {

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
        $device_status_code = json_decode((json_encode($xml_snippet->attributes()->Code)), true);
        $json_convert = json_encode($xml_snippet);

        $log->write($result);
        curl_close($curl);
        $final_result = json_decode($json_convert, true);
        $json['status'] = true;
        $json['data'] = $final_result;
        $json['device_status_code'] = $device_status_code[0];
        return $json;
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

        $order_id = isset($this->request->post['order_id']) && $this->request->post['order_id'] != NULL ? $this->request->post['order_id'] : NULL;

        $this->load->model('account/customer');
        $this->load->model('sale/order');

        $order_info = $this->model_sale_order->getOrder($order_id);
        $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);

        $CompanyName = $customer_info['company_name'];
        $ClientPINnum = NULL;
        $HeadQuarters = NULL;
        $Address = $customer_info['company_address'];
        $PostalCodeAndCity = NULL;
        $ExemptionNum = NULL;
        $TraderSystemInvNum = $order_info['order_id'];

        $invoice_data = "(CompanyName=" . $CompanyName . ",ClientPINnum=" . $ClientPINnum . ",HeadQuarters=" . $HeadQuarters . ",Address=" . $Address . ",PostalCodeAndCity=" . $PostalCodeAndCity . ",ExemptionNum=" . $ExemptionNum . ",TraderSystemInvNum=" . $TraderSystemInvNum . ")";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://localhost:4444/OpenInvoiceWithFreeCustomerData' . $invoice_data);
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

    public function sellplufromextdb() {
        $log = new Log('error.log');

        $NamePLU = isset($this->request->post['NamePLU']) && $this->request->post['NamePLU'] != NULL ? $this->request->post['NamePLU'] : NULL;
        $OptionVATClass = isset($this->request->post['ClientPINnum']) && $this->request->post['ClientPINnum'] != NULL ? $this->request->post['ClientPINnum'] : NULL;
        $Price = isset($this->request->post['HeadQuarters']) && $this->request->post['HeadQuarters'] != NULL ? $this->request->post['HeadQuarters'] : NULL;
        $MeasureUnit = isset($this->request->post['Address']) && $this->request->post['Address'] != NULL ? $this->request->post['Address'] : NULL;
        $HSCode = isset($this->request->post['PostalCodeAndCity']) && $this->request->post['PostalCodeAndCity'] != NULL ? $this->request->post['PostalCodeAndCity'] : NULL;
        $HSName = isset($this->request->post['ExemptionNum']) && $this->request->post['ExemptionNum'] != NULL ? $this->request->post['ExemptionNum'] : NULL;
        $VATGrRate = isset($this->request->post['TraderSystemInvNum']) && $this->request->post['TraderSystemInvNum'] != NULL ? $this->request->post['TraderSystemInvNum'] : NULL;
        $invoice_number = isset($this->request->post['invoice_number']) && $this->request->post['invoice_number'] != NULL ? $this->request->post['invoice_number'] : NULL;

        $this->load->model('sale/order');
        if ($this->model_sale_order->hasRealOrderProducts($invoice_number)) {
            $products = $this->model_sale_order->getRealOrderProducts($invoice_number);
        } else {
            $products = $this->model_sale_order->getOrderProducts($invoice_number);
        }

        $products_data = "(NamePLU=" . $NamePLU . ",OptionVATClass=" . $OptionVATClass . ",Price=" . $Price . ",MeasureUnit=" . $MeasureUnit . ",HSCode=" . $HSCode . ",HSName=" . $HSName . ",VATGrRate=" . $VATGrRate . ")";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://localhost:4444/SellPLUfromExtDB' . $products_data);
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

    public function readcurrentreceiptinfo() {

        $log = new Log('error.log');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://localhost:4444/ReadCurrentReceiptInfo()');
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

    public function closereceipt() {

        $log = new Log('error.log');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://localhost:4444/CloseReceipt()');
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

    public function readcunumbers() {

        $log = new Log('error.log');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://localhost:4444/ReadCUnumbers()');
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

    public function readdatetime() {

        $log = new Log('error.log');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://localhost:4444/ReadDateTime()');
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
