<?php

require_once DIR_SYSTEM . '/vendor/aws/aws-autoloader.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\S3\Exception\S3Exception;

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

        // Create an SDK class used to share configuration across clients.
        $sdk = new Aws\Sdk([
            'region' => 'ap-south-1',
            'version' => 'latest',
            'credentials' => [
                'key' => 'AKIAUWRTJZVBHL7IDH6T',
                'secret' => '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'
            ]
        ]);

        // Use an Aws\Sdk class to create the S3Client object.
        $s3Client = $sdk->createS3();

        try {
            //$s3Client->createBucket(['Bucket' => 'kwikbasket-pezesha-files']);
            $bucket = 'kwikbasket-pezesha-files';
            $result = $s3Client->putObject([
                'Bucket' => $bucket,
                'Key' => $this->customer->getId() . '/copy_of_certificate_of_incorporation_' . $this->customer->getId() . '_' . strtotime("now"),
                'SourceFile' => $temp_file_location,
                'ACL' => 'private',
            ]);
            $this->load->model('account/customer');
            $this->model_account_customer->SaveCustomerFiles($this->customer->getId(), $result['ObjectURL'], 'PEZESHA', 'Copy Of Certificate Of Incorporation');
            $log->write($result['ObjectURL']);
        } catch (S3Exception $e) {
            $log = new Log('error.log');
            $log->write($e->getMessage());
            // Catch an S3 specific exception.
            echo $e->getMessage();
        } catch (AwsException $e) {
            $log = new Log('error.log');
            $log->write($e->getAwsRequestId());
            $log->write($e->getAwsErrorType());
            $log->write($e->getAwsErrorCode());
            // This catches the more generic AwsException. You can grab information
            // from the exception using methods of the exception object.
            echo $e->getAwsRequestId() . "\n";
            echo $e->getAwsErrorType() . "\n";
            echo $e->getAwsErrorCode() . "\n";

            // This dumps any modeled response data, if supported by the service
            // Specific members can be accessed directly (e.g. $e['MemberName'])
            var_dump($e->toArray());
        }
    }

    public function pezeshafilestwo() {
        $log = new Log('error.log');

        $file_name = $this->request->files['file']['name'];
        $temp_file_location = $this->request->files['file']['tmp_name'];

        $log->write($this->request->files);

        // Create an SDK class used to share configuration across clients.
        $sdk = new Aws\Sdk([
            'region' => 'ap-south-1',
            'version' => 'latest',
            'credentials' => [
                'key' => 'AKIAUWRTJZVBHL7IDH6T',
                'secret' => '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'
            ]
        ]);

        // Use an Aws\Sdk class to create the S3Client object.
        $s3Client = $sdk->createS3();

        try {
            //$s3Client->createBucket(['Bucket' => 'kwikbasket-pezesha-files']);
            $bucket = 'kwikbasket-pezesha-files';
            $result = $s3Client->putObject([
                'Bucket' => $bucket,
                'Key' => $this->customer->getId() . '/copy_of_certificate_of_incorporation_' . $this->customer->getId() . '_' . strtotime("now"),
                'SourceFile' => $temp_file_location,
                'ACL' => 'private',
            ]);
            $this->load->model('account/customer');
            $this->model_account_customer->SaveCustomerFiles($this->customer->getId(), $result['ObjectURL'], 'PEZESHA', 'Copy Of Bussiness Operating Permit');
            $log->write($result['ObjectURL']);
        } catch (S3Exception $e) {
            $log = new Log('error.log');
            $log->write($e->getMessage());
            // Catch an S3 specific exception.
            echo $e->getMessage();
        } catch (AwsException $e) {
            $log = new Log('error.log');
            $log->write($e->getAwsRequestId());
            $log->write($e->getAwsErrorType());
            $log->write($e->getAwsErrorCode());
            // This catches the more generic AwsException. You can grab information
            // from the exception using methods of the exception object.
            echo $e->getAwsRequestId() . "\n";
            echo $e->getAwsErrorType() . "\n";
            echo $e->getAwsErrorCode() . "\n";

            // This dumps any modeled response data, if supported by the service
            // Specific members can be accessed directly (e.g. $e['MemberName'])
            var_dump($e->toArray());
        }
    }

    public function pezeshafilesthree() {
        $log = new Log('error.log');

        $file_name = $this->request->files['file']['name'];
        $temp_file_location = $this->request->files['file']['tmp_name'];

        $log->write($this->request->files);

        // Create an SDK class used to share configuration across clients.
        $sdk = new Aws\Sdk([
            'region' => 'ap-south-1',
            'version' => 'latest',
            'credentials' => [
                'key' => 'AKIAUWRTJZVBHL7IDH6T',
                'secret' => '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'
            ]
        ]);

        // Use an Aws\Sdk class to create the S3Client object.
        $s3Client = $sdk->createS3();

        try {
            //$s3Client->createBucket(['Bucket' => 'kwikbasket-pezesha-files']);
            $bucket = 'kwikbasket-pezesha-files';
            $result = $s3Client->putObject([
                'Bucket' => $bucket,
                'Key' => $this->customer->getId() . '/copy_of_certificate_of_incorporation_' . $this->customer->getId() . '_' . strtotime("now"),
                'SourceFile' => $temp_file_location,
                'ACL' => 'private',
            ]);
            $this->load->model('account/customer');
            $this->model_account_customer->SaveCustomerFiles($this->customer->getId(), $result['ObjectURL'], 'PEZESHA', 'Copy Of ID Of Bussiness Owner / Managing Director');
            $log->write($result['ObjectURL']);
            $log->write($result);
            $log->write('result');
        } catch (S3Exception $e) {
            $log = new Log('error.log');
            $log->write($e->getMessage());
            // Catch an S3 specific exception.
            echo $e->getMessage();
        } catch (AwsException $e) {
            $log = new Log('error.log');
            $log->write($e->getAwsRequestId());
            $log->write($e->getAwsErrorType());
            $log->write($e->getAwsErrorCode());
            // This catches the more generic AwsException. You can grab information
            // from the exception using methods of the exception object.
            echo $e->getAwsRequestId() . "\n";
            echo $e->getAwsErrorType() . "\n";
            echo $e->getAwsErrorCode() . "\n";

            // This dumps any modeled response data, if supported by the service
            // Specific members can be accessed directly (e.g. $e['MemberName'])
            var_dump($e->toArray());
        }
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
        $this->SendDocuments($this->customer->getId());
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function SendDocuments($customer_id) {
        $this->load->model('account/customer');
        $documents = $this->model_account_customer->getCustomerDocuments($this->customer->getId());
        $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
        if ($documents != NULL && count($documents) > 0) {
            $log = new Log('error.log');
            try {
                $message = '';
                $subject = $customer_info['firstname'] . ' ' . $customer_info['lastname'] . ' Documents.';
                foreach ($documents as $document) {
                    $message .= $document['name'] . ':' . $document['path'];
                }
                $log->write('SEND_DOCUMENTS');
                $log->write($message);
                $log->write($subject);
                $log->write('SEND_DOCUMENTS');
                $mail = new mail($this->config->get('config_mail'));
                $mail->setTo('documents.kwikbasket@yopmail.com');
                $mail->setFrom($this->config->get('config_from_email'));
                $mail->setSubject($subject);
                $mail->setSender($this->config->get('config_name'));
                $mail->setHtml($message);
                $mail->send();
            } catch (Exception $e) {
                
            }
        }
    }

}
