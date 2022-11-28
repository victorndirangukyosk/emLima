<?php

class ControllerKraIntegration extends Controller {

    public function settings() {

        $log = new Log('error.log');

        $com = isset($this->request->post['com']) && $this->request->post['com'] != NULL ? $this->request->post['com'] : NULL;
        $baud = isset($this->request->post['baud']) && $this->request->post['baud'] != NULL ? $this->request->post['baud'] : NULL;
        $tcp = isset($this->request->post['tcp']) && $this->request->post['tcp'] != NULL ? $this->request->post['tcp'] : $this->config->get('config_kra_tcp');
        $ip = isset($this->request->post['ip']) && $this->request->post['ip'] != NULL ? $this->request->post['ip'] : $this->config->get('config_kra_ip');
        $port = isset($this->request->post['port']) && $this->request->post['port'] != NULL ? $this->request->post['port'] : $this->config->get('config_kra_port');
        $password = isset($this->request->post['password']) && $this->request->post['password'] != NULL ? $this->request->post['password'] : $this->config->get('config_kra_password');

        $settings = "(com=" . $com . ",baud=" . $baud . ",tcp=" . $tcp . ",ip=" . $ip . ",port=" . $port . ",password=" . $password . ")";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->config->get('config_kra_url') . 'Settings' . $settings);
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

        // Add to activity log
        $this->load->model('user/user_activity');

        $activity_data = [
            'user_id' => $this->user->getId(),
            'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
            'user_group_id' => $this->user->getGroupId(),
            'order_id' => $this->request->post['order_id'],
            'service' => 'CONNECT_TO_KRA_DEVICE',
        ];
        $log->write('connect to kra device');

        $this->model_user_user_activity->addActivity('connect_to_kra_device', $activity_data);

        $log->write('connect to kra device');

        $json['status'] = true;
        $json['data'] = $final_result;
        $json['device_status_code'] = $read_status['device_status_code'];
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function readstatus() {

        $log = new Log('error.log');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->config->get('config_kra_url') . 'ReadStatus()');
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
        curl_setopt($curl, CURLOPT_URL, $this->config->get('config_kra_url') . 'ReadStatus()');
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
        curl_setopt($curl, CURLOPT_URL, $this->config->get('config_kra_url') . 'CancelReceipt()');
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

        // Add to activity log
        $this->load->model('user/user_activity');

        $activity_data = [
            'user_id' => $this->user->getId(),
            'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
            'user_group_id' => $this->user->getGroupId(),
            'order_id' => $this->request->post['order_id'],
            'service' => 'CANCEL_RECEIPT_ON_KRA_DEVICE',
        ];
        $log->write('cancel receipt on kra device');

        $this->model_user_user_activity->addActivity('cancel_receipt_on_kra_device', $activity_data);

        $log->write('cancel receipt on kra device');

        return $json;
        /* $this->response->addHeader('Content-Type: application/json');
          $this->response->setOutput(json_encode($json)); */
    }

    public function openinvoicewithfreecustomerdata() {

        $log = new Log('error.log');

        $order_id = isset($this->request->post['order_id']) && $this->request->post['order_id'] != NULL ? $this->request->post['order_id'] : NULL;

        $this->load->model('account/customer');
        $this->load->model('sale/order');

        $order_info = $this->model_sale_order->getOrder($order_id);
        $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);

        $CompanyName = substr(preg_replace('/[0-9\,\-\@\.\;\" "]+/', '', $customer_info['company_name']), 0, 29);
        $ClientPINnum = NULL;
        $HeadQuarters = NULL;
        $Address = substr(preg_replace('/[0-9\,\-\@\.\;\" "]+/', '', $customer_info['company_address']), 0, 29);
        $PostalCodeAndCity = NULL;
        $ExemptionNum = NULL;
        $TraderSystemInvNum = $order_info['order_id'];

        $invoice_data = "(CompanyName=" . $CompanyName . ",ClientPINnum=" . $ClientPINnum . ",HeadQuarters=" . $HeadQuarters . ",Address=" . $Address . ",PostalCodeAndCity=" . $PostalCodeAndCity . ",ExemptionNum=" . $ExemptionNum . ",TraderSystemInvNum=" . $TraderSystemInvNum . ")";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->config->get('config_kra_url') . 'OpenInvoiceWithFreeCustomerData' . $invoice_data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/x-www-form-urlencoded'));

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($curl);
        $log->write($result);
        $log->write($invoice_data);
        $info = curl_getinfo($curl);
        $log->write($info);

        $xml_snippet = simplexml_load_string($result);
        $kra_json = json_encode($xml_snippet);
        $device_status_code = json_decode((json_encode($xml_snippet->attributes()->Code)), true);
        $json_convert = json_encode($xml_snippet);

        $log->write($result);
        curl_close($curl);
        $final_result = json_decode($json_convert, true);
        $json['status'] = true;
        $json['data'] = $final_result;
        $json['device_status_code'] = $device_status_code;

        // Add to activity log
        $this->load->model('user/user_activity');
        $this->load->model('kra/kra');

        $kra_activity_data = [
            'order_id' => $this->request->post['order_id'],
            'service' => 'OPEN_INVOICE_ON_KRA_DEVICE',
            'response' => $kra_json
        ];
        $this->model_kra_kra_activity->addKraActivity('open_invoice_on_kra_device', $kra_activity_data);

        $activity_data = [
            'user_id' => $this->user->getId(),
            'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
            'user_group_id' => $this->user->getGroupId(),
            'order_id' => $this->request->post['order_id'],
            'service' => 'OPEN_INVOICE_ON_KRA_DEVICE',
        ];
        $log->write('open invoice on kra device');

        $this->model_user_user_activity->addActivity('open_invoice_on_kra_device', $activity_data);

        $log->write('open invoice on kra device');

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function sellplufromextdb() {
        $log = new Log('error.log');

        $invoice_number = isset($this->request->post['order_id']) && $this->request->post['order_id'] != NULL ? $this->request->post['order_id'] : NULL;

        $this->load->model('sale/order');
        if ($this->model_sale_order->hasRealOrderProducts($invoice_number)) {
            $products = $this->model_sale_order->getRealOrderProducts($invoice_number);
        } else {
            $products = $this->model_sale_order->getOrderProducts($invoice_number);
        }

        $json['status'] = true;

        $new_product_array = NULL;
        foreach ($products as $product) {
            $new_product_array['NamePLU'] = preg_replace('/[0-9\,\(\)\-\@\.\;\" "]+/', '', $product['name']);
            $new_product_array['OptionVATClass'] = $product['tax'] > 0 ? 'A' : 'C';
            $new_product_array['Price'] = number_format((float) $product['price'], 2, '.', '');
            $new_product_array['MeasureUnit'] = $product['unit'];
            $new_product_array['HSCode'] = NULL;
            $new_product_array['HSName'] = NULL;
            $new_product_array['VATGrRate'] = $product['tax'] > 0 ? 16 : 0;
            $new_product_array['Quantity'] = $product['quantity'];
            $new_product_array['DiscAddP'] = 0;

            $hs_code = '0024.11.00';
            //$hs_code = NULL;
            $hs_name = NULL;
            $products_data = "(NamePLU=" . $new_product_array['NamePLU'] . ",OptionVATClass=" . $new_product_array['OptionVATClass'] . ",Price=" . $new_product_array['Price'] . ",MeasureUnit=" . $new_product_array['MeasureUnit'] . ",HSCode=" . $hs_code . ",HSName=" . $hs_name . ",VATGrRate=" . $new_product_array['VATGrRate'] . ",Quantity=" . $new_product_array['Quantity'] . ",DiscAddP=" . $new_product_array['DiscAddP'] . ")";

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $this->config->get('config_kra_url') . 'SellPLUfromExtDB' . $products_data);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/x-www-form-urlencoded'));

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $result = curl_exec($curl);
            $info = curl_getinfo($curl);
            $log->write($result);
            $log->write($info);
            $xml_snippet = simplexml_load_string($result);
            $kra_json = json_encode($xml_snippet);
            $device_status_code = json_decode((json_encode($xml_snippet->attributes()->Code)), true);
            $json_convert = json_encode($xml_snippet);

            $log->write($result);
            curl_close($curl);
            $final_result = json_decode($json_convert, true);
            $json['data'] = $final_result;
            $json['device_status_code'] = $device_status_code;
        }

        // Add to activity log
        $this->load->model('user/user_activity');
        $this->load->model('kra/kra');

        $kra_activity_data = [
            'order_id' => $this->request->post['order_id'],
            'service' => 'PUSH_INVOICE_PRODUCTS_TO_KRA_DEVICE',
            'response' => $kra_json
        ];
        $this->model_kra_kra_activity->addKraActivity('push_invoice_products_to_kra_device', $kra_activity_data);

        $activity_data = [
            'user_id' => $this->user->getId(),
            'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
            'user_group_id' => $this->user->getGroupId(),
            'order_id' => $this->request->post['order_id'],
            'service' => 'PUSH_INVOICE_PRODUCTS_TO_KRA_DEVICE',
        ];
        $log->write('push invoice products to kra device');

        $this->model_user_user_activity->addActivity('push_invoice_products_to_kra_device', $activity_data);

        $log->write('push invoice products to kra device');

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function readcurrentreceiptinfo() {

        $log = new Log('error.log');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->config->get('config_kra_url') . 'ReadCurrentReceiptInfo()');
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

        // Add to activity log
        $this->load->model('user/user_activity');
        $this->load->model('kra/kra');

        $kra_json = json_encode($xml_snippet);
        $kra_activity_data = [
            'order_id' => $this->request->post['order_id'],
            'service' => 'READ_RECEIPT_ON_KRA_DEVICE',
            'response' => $kra_json
        ];
        $this->model_kra_kra_activity->addKraActivity('read_receipt_on_kra_device', $kra_activity_data);

        $activity_data = [
            'user_id' => $this->user->getId(),
            'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
            'user_group_id' => $this->user->getGroupId(),
            'order_id' => $this->request->post['order_id'],
            'service' => 'READ_RECEIPT_ON_KRA_DEVICE',
        ];
        $log->write('read receipt on kra device');

        $this->model_user_user_activity->addActivity('read_receipt_on_kra_device', $activity_data);

        $log->write('read receipt on kra device');

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function closereceipt() {

        $log = new Log('error.log');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->config->get('config_kra_url') . 'CloseReceipt()');
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

        // Add to activity log
        $this->load->model('user/user_activity');
        $this->load->model('kra/kra');

        $kra_json = json_encode($xml_snippet);
        $kra_activity_data = [
            'order_id' => $this->request->post['order_id'],
            'service' => 'CLOSE_RECEIPT_ON_KRA_DEVICE',
            'response' => $kra_json
        ];
        $this->model_kra_kra_activity->addKraActivity('close_receipt_on_kra_device', $kra_activity_data);

        $activity_data = [
            'user_id' => $this->user->getId(),
            'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
            'user_group_id' => $this->user->getGroupId(),
            'order_id' => $this->request->post['order_id'],
            'service' => 'CLOSE_RECEIPT_ON_KRA_DEVICE',
        ];
        $log->write('close receipt on kra device');

        $this->model_user_user_activity->addActivity('close_receipt_on_kra_device', $activity_data);

        $log->write('close receipt on kra device');

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function readcunumbers() {

        $log = new Log('error.log');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->config->get('config_kra_url') . 'ReadCUnumbers()');
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

        // Add to activity log
        $this->load->model('user/user_activity');
        $this->load->model('kra/kra');

        $kra_json = json_encode($xml_snippet);
        $kra_activity_data = [
            'order_id' => $this->request->post['order_id'],
            'service' => 'GET_KRA_DETAILS',
            'response' => $kra_json
        ];
        $this->model_kra_kra_activity->addKraActivity('get_kra_details', $kra_activity_data);

        $activity_data = [
            'user_id' => $this->user->getId(),
            'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
            'user_group_id' => $this->user->getGroupId(),
            'order_id' => $this->request->post['order_id'],
            'service' => 'GET_KRA_DETAILS',
        ];
        $log->write('get kra details');

        $this->model_user_user_activity->addActivity('get_kra_details', $activity_data);

        $log->write('get kra details');

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function readdatetime() {

        $log = new Log('error.log');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->config->get('config_kra_url') . 'ReadDateTime()');
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
