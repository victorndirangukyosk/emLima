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
    }

}
