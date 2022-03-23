<?php

class ControllerAccountApplypezesha extends Controller {

    public function index() {
        $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_login.css');

        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/pezesha', '', 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $data['kondutoStatus'] = $this->config->get('config_konduto_status');

        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        $this->load->language('account/pezesha');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/account', '', 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_credit'),
            'href' => $this->url->link('account/credit', '', 'SSL'),
        ];

        $this->load->model('account/credit');
        $this->load->model('account/customer');

        $data['label_address'] = $this->language->get('label_address');
        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_order'] = $this->language->get('text_order');
        $data['text_balance'] = $this->language->get('text_apply_pezesha');
        $data['text_activity'] = $this->language->get('text_activity');
        $data['text_report_issue'] = $this->language->get('text_report_issue');

        $data['text_load_more'] = $this->language->get('text_load_more');
        $data['text_no_more'] = $this->language->get('text_no_more');

        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_description'] = $this->language->get('column_description');
        $data['column_amount'] = sprintf($this->language->get('column_amount'), $this->config->get('config_currency'));

        $data['text_total'] = $this->language->get('text_total');
        $data['text_empty'] = $this->language->get('text_empty');

        $data['button_continue'] = $this->language->get('button_continue');

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['base'] = $server;
        $data['total'] = $this->currency->format($this->customer->getBalance());
        $data['text_signout'] = $this->language->get('text_signout');
        $data['text_shopping'] = $this->language->get('text_shopping');

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['continue'] = $this->url->link('account/account', '', 'SSL');
        $data['order'] = $this->url->link('account/order', '', 'SSL');
        $data['credit'] = $this->url->link('account/credit', '', 'SSL');
        $data['address'] = $this->url->link('account/address', '', 'SSL');
        $data['home'] = $server;

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/information');

        $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
        $data['national_id'] = $customer_info['national_id'];
        $data['kra'] = $customer_info['kra'];
        $data['dob'] = $customer_info['dob'] != NULL ? date('d/m/Y', strtotime($customer_info['dob'])) : NULL;
        $data['gender'] = $customer_info['gender'];

        // echo "<pre>";print_r($data['credits']);die;
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/applypezesha.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/applypezesha.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/account/applypezesha.tpl', $data));
        }
    }

    public function pezeshafiles() {
        $log = new Log('error.log');

        $file_name = $this->request->files['file']['name'];
        $temp_file_location = $this->request->files['file']['tmp_name'];

        $log->write($this->request->files);
        $mail = new Mail();
        $sthree_doc_url = $mail->UploadToSThree($file_name, $temp_file_location, 'copy_of_certificate_of_incorporation', $this->customer->getId());
        $log->write('sthree_doc_url');
        $log->write($sthree_doc_url);
        $log->write('sthree_doc_url');
        if ($sthree_doc_url != NULL) {
            $this->load->model('account/customer');
            $this->model_account_customer->SaveCustomerFiles($this->customer->getId(), $sthree_doc_url, 'PEZESHA', 'Copy Of Certificate Of Incorporation');
        }
    }

    public function pezeshafilestwo() {
        $log = new Log('error.log');

        $file_name = $this->request->files['file']['name'];
        $temp_file_location = $this->request->files['file']['tmp_name'];

        $log->write($this->request->files);

        $mail = new Mail();
        $sthree_doc_url = $mail->UploadToSThree($file_name, $temp_file_location, 'copy_of_bussiness_operating_permit', $this->customer->getId());
        $log->write('sthree_doc_url');
        $log->write($sthree_doc_url);
        $log->write('sthree_doc_url');

        if ($sthree_doc_url != NULL) {
            $this->load->model('account/customer');
            $this->model_account_customer->SaveCustomerFiles($this->customer->getId(), $sthree_doc_url, 'PEZESHA', 'Copy Of Bussiness Operating Permit');
        }
    }

    public function pezeshafilesthree() {
        $log = new Log('error.log');

        $file_name = $this->request->files['file']['name'];
        $temp_file_location = $this->request->files['file']['tmp_name'];

        $log->write($this->request->files);

        $mail = new Mail();
        $sthree_doc_url = $mail->UploadToSThree($file_name, $temp_file_location, 'copy_of_id_of_bussiness_owner_managing_director', $this->customer->getId());
        $log->write('sthree_doc_url');
        $log->write($sthree_doc_url);
        $log->write('sthree_doc_url');

        $this->load->model('account/customer');
        $this->model_account_customer->SaveCustomerFiles($this->customer->getId(), $sthree_doc_url, 'PEZESHA', 'Copy Of ID Of Bussiness Owner / Managing Director');
    }

    public function auth() {

        $log = new Log('error.log');

        $body = array('grant_type' => 'client_credentials', 'provider' => 'users', 'client_secret' => $this->config->get('pezesha_client_secret'), 'client_id' => $this->config->get('pezesha_client_id'), 'merchant _key' => $this->config->get('pezesha_merchant_key'));
        $body = http_build_query($body);
        $curl = curl_init();
        if ($this->config->get('pezesha_environment') == 'live') {
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

    public function accrptterms() {

        $log = new Log('error.log');
        $this->load->model('account/customer');
        $customer_device_info = $this->model_account_customer->getCustomer($this->customer->getId());
        $auth_response = $this->auth();
        $log->write('auth_response');
        $log->write($auth_response);
        $log->write($customer_device_info);
        $log->write('auth_response');
        $body = array('channel' => $this->config->get('pezesha_channel'), 'identifier' => $customer_device_info['national_id'], 'terms' => TRUE);
        $body = http_build_query($body);
        //$body = json_encode($body);
        $curl = curl_init();
        if ($this->config->get('pezesha_environment') == 'live') {
            curl_setopt($curl, CURLOPT_URL, 'https://api.pezesha.com/mfi/v1/borrowers/terms');
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
        //return $json;

        $json['status'] = true;
        $json['data'] = $result;

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function dataingestion() {

        $log = new Log('error.log');
        $this->load->model('account/customer');
        $this->load->model('sale/order');
        $customer_device_info = $this->model_account_customer->getCustomer($this->customer->getId());
        $customer_documents = $this->model_account_customer->getCustomerDocuments($this->customer->getId());

        $data['filter_customer_id'] = $this->customer->getId();
        $data['filter_paid'] = 'Y';

        $customer_order_info = $this->model_sale_order->getOrders($data);
        $transactions_details = array();

        foreach ($customer_order_info as $order_info) {
            $order_transaction_info = $this->model_sale_order->getOrderTransactionId($order_info['order_id']);
            $transactions['transaction_id'] = $order_transaction_info['transaction_id'];
            $transactions['merchant_id'] = $this->customer->getId();
            $transactions['face_amount'] = $order_info['total'];
            $transactions['transaction_time'] = $order_info['date_added'];
            $transactions['other_details'] = array('key' => 'Organization_id', 'value' => $customer_device_info['customer_id'], 'key' => 'payee_type', 'value' => $customer_device_info['firstname'] . ' ' . $customer_device_info['lastname'] . ' ' . $customer_device_info['company_name']);
            $transactions_details[] = $transactions;
        }
        $log->write($transactions_details);

        $auth_response = $this->auth();
        $log->write('auth_response');
        $log->write($auth_response);
        $log->write($customer_device_info);
        $log->write('auth_response');
        $body = array('channel' => $this->config->get('pezesha_channel'), 'transactions' => $transactions_details);
        //$body = http_build_query($body);
        $body = json_encode($body);
        $log->write($body);
        $curl = curl_init();
        if ($this->config->get('pezesha_environment') == 'live') {
            curl_setopt($curl, CURLOPT_URL, 'https://api.pezesha.com/mfi/v1.1/data');
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Authorization:Bearer ' . $auth_response]);
        } else {
            curl_setopt($curl, CURLOPT_URL, 'https://staging.api.pezesha.com/mfi/v1.1/data');
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
        //return $json;

        $json['status'] = true;
        $json['data'] = $result;
        //$this->model_account_customer->SendDocuments();
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function userregistration() {

        $this->load->model('account/customer');
        $customer_documents = $this->model_account_customer->getCustomerDocuments($this->customer->getId());
        if ($customer_documents != NULL && count($customer_documents) > 0) {
            $this->load->model('account/customer_group');
            $customer_device_info = $this->model_account_customer->getCustomer($this->customer->getId());
            $customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_device_info['customer_group_id']);
            $auth_response = $this->auth();
            $log = new Log('error.log');
            $log->write('auth_response');
            $log->write($auth_response);
            $log->write($customer_device_info);
            $log->write('auth_response');
            $meta_data = array('key' => 'customer_group', 'value' => $customer_group_info['description']);
            $body = array('channel' => $this->config->get('pezesha_channel'), 'terms' => TRUE, 'full_names' => $customer_device_info['firstname'] . ' ' . $customer_device_info['lastname'], 'phone' => '254' . '' . $customer_device_info['telephone'], 'other_phone_nos' => array('254' . '' . $customer_device_info['telephone'], '254' . '' . $customer_device_info['telephone']), 'national_id' => $customer_device_info['national_id'], 'dob' => date('Y-m-d', strtotime($customer_device_info['dob'])), 'email' => $customer_device_info['email'], 'merchant_id' => $customer_device_info['customer_id'], 'merchant_reg_date' => date('Y-m-d', strtotime($customer_device_info['date_added'])), 'location' => $customer_device_info['company_name'] . '' . $customer_device_info['company_address'], 'geo_location' => array('long' => $customer_device_info['longitude'], 'lat' => $customer_device_info['latitude']), 'meta_data' => $meta_data);
            //$body = http_build_query($body);
            $body = json_encode($body);
            $log->write($body);
            $curl = curl_init();
            if ($this->config->get('pezesha_environment') == 'live') {
                curl_setopt($curl, CURLOPT_URL, 'https://api.pezesha.com/mfi/v1/borrowers');
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
                $customer_device_info = $this->model_account_customer->addPezeshaCustomer($data);
            }
            $json = $result;
            //return $json;

            $json['status'] = true;
            $json['data'] = $result;
        } else {
            $json['status'] = false;
            $result['status'] = 422;
            $result['message'] = 'Validation Error';
            $result['errors']['documents'] = array('Documents Required!');
            $json['data'] = $result;
            $json['errors']['documents'] = array('Documents Required!');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function updatecustomerinfo() {
        $log = new Log('error.log');
        $log->write($this->request->post);
        $this->load->model('account/customer');
        $data['gender'] = $this->request->post['gender'];
        $data['kra'] = $this->request->post['kra'];
        $data['national_id'] = $this->request->post['national_id'];

        $date = $this->request->post['dob'];
        if (isset($date) && $date != NULL) {
            $date = DateTime::createFromFormat('d/m/Y', $date);
            $this->request->post['dob'] = $date->format('Y-m-d');
        } else {
            $this->request->post['dob'] = null;
        }
        $data['dob'] = $this->request->post['dob'];

        $this->model_account_customer->updatecustomerinfo($this->customer->getId(), $data);
    }

}
