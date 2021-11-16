<?php

class ControllerEmailBulkEmail extends Controller {

    public function index() {
        $this->document->setTitle('Send Notification To Bulk Customers');
        // Text Editor
        $data['token'] = $this->session->data['token'];
        $data['text_editor'] = $this->config->get('config_text_editor');

        if (empty($data['text_editor'])) {
            $data['text_editor'] = 'tinymce';
        }
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('email/customer_bulk_email.tpl', $data));
    }

    public function bulkordernotification() {
        $this->document->setTitle('Send Notification To Bulk Customers');
        // Text Editor
        $data['token'] = $this->session->data['token'];
        $data['text_editor'] = $this->config->get('config_text_editor');

        if (empty($data['text_editor'])) {
            $data['text_editor'] = 'tinymce';
        }
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('email/order_bulk_email.tpl', $data));
    }

    public function sendbulknotification() {
        $subject = $this->request->post['subject'];
        $sms_description = $this->request->post['sms_description'];
        $mobile_notification_title = $this->request->post['mobile_notification_title'];
        $mobile_notification_message = $this->request->post['mobile_notification_message'];
        $selected = $this->request->post['selected'];
        $email_description = $this->request->post['email_description'];

        $log = new Log('error.log');
        $log->write($subject);
        $log->write($sms_description);
        $log->write($mobile_notification_title);
        $log->write($mobile_notification_message);
        $log->write($selected);
        $log->write($email_description);

        $this->load->model('sale/customer');
        $data['filter_customer_id'] = $selected;
        $results = $this->model_sale_customer->getCustomerEmailById($data);
        $customer_emails = array_column($results, 'email');
        $customer_emails = array_filter($customer_emails);
        $customer_mobiles = array_column($results, 'telephone');
        $customer_mobiles = array_filter($customer_mobiles);
        $customer_devices = array_column($results, 'device_id');
        $customer_devices = array_filter($customer_devices);

        $log->write($customer_emails);
        $log->write($customer_mobiles);
        $log->write($customer_devices);

        $coma_customer_emails = NULL;
        $coma_customer_mobiles = NULL;
        $coma_customer_devices = NULL;
        if (is_array($customer_emails) && count($customer_emails) > 0) {
            $coma_customer_emails = implode(',', $customer_emails);
        }

        if (is_array($customer_mobiles) && count($customer_mobiles) > 0) {
            $coma_customer_mobiles = implode(',', $customer_mobiles);
        }

        if (is_array($customer_devices) && count($customer_devices) > 0) {
            $coma_customer_devices = implode(',', $customer_devices);
        }

        $log->write($coma_customer_emails);
        $log->write($coma_customer_mobiles);
        $log->write($coma_customer_devices);

        $notification['bulk_notification_subject'] = $subject;
        $notification['bulk_notification_email_description'] = $email_description;
        $notification['bulk_notification_sms_description'] = $sms_description;
        $notification['bulk_notification_mobile_title'] = $mobile_notification_title;
        $notification['bulk_notification_mobile_message'] = $mobile_notification_message;

        $subject = $this->emailtemplate->getSubject('Customer', 'customer_94', $notification);
        $message = $this->emailtemplate->getMessage('Customer', 'customer_94', $notification);
        $sms_message = $this->emailtemplate->getSmsMessage('Customer', 'customer_94', $notification);
        $mobile_notification_template = $this->emailtemplate->getNotificationMessage('Customer', 'customer_94', $notification);
        $mobile_notification_title = $this->emailtemplate->getNotificationTitle('Customer', 'customer_94', $notification);

        $this->sendbulksms($coma_customer_mobiles, $sms_message);
        $this->sendbulkpushnotification($results, $mobile_notification_title, $mobile_notification_template);
        $mail = new Mail($this->config->get('config_mail'));
        $mail->setTo(BCC_MAILS);
        $mail->setCc($coma_customer_emails);
        $mail->setBcc($coma_customer_emails);
        $mail->setFrom($this->config->get('config_from_email'));
        $mail->setSender($this->config->get('config_name'));
        $mail->setSubject($subject);
        $mail->setHTML($message);
        $mail->send();
    }

    public function sendbulksms($coma_customer_mobiles, $sms_message) {
        $coma_customer_mobiles = explode(",", $coma_customer_mobiles);
        foreach ($coma_customer_mobiles as $coma_customer_mobile) {
            $log = new Log('error.log');
            $log->write($coma_customer_mobile);
            $ret = $this->emailtemplate->sendmessage($coma_customer_mobile, $sms_message);
        }
    }

    public function sendbulkpushnotification($results, $mobile_notification_title, $mobile_notification_template) {
        foreach ($results as $result) {
            if ($result['device_id'] != NULL) {
                $ret = $this->emailtemplate->sendDynamicPushNotification($result['customer_id'], $result['device_id'], $mobile_notification_title, $mobile_notification_template, 'com.instagolocal.showorder');
            }
        }
    }

}
