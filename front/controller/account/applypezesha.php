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
            $bucket = 'kwikbasket-pezesha-files';
            $result = $s3Client->putObject([
                'Bucket' => $bucket,
                'Key' => $this->customer->getId() . '/copy_of_certificate_of_incorporation_' . $this->customer->getId() . '_' . strtotime("now"),
                'SourceFile' => $temp_file_location,
                'ACL' => 'private',
            ]);
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

}
