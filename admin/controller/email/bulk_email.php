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

}
