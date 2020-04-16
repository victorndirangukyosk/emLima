<?php

class ControllerApiForgetPassword extends Controller {

    private $error = array();

    public function addForgetPassword() {

        //echo "forget";

        $this->load->model('user/user');

        $this->load->language('mail/appforgotten');

        $json['status'] = 501;

        if ($this->validate()) {

            $code = sha1(uniqid(mt_rand(), true));

            if ($this->request->server['HTTPS']) {
                $server = $this->config->get('config_ssl');
            } else {
                $server = $this->config->get('config_url');
            }


            $this->model_user_user->editCode($this->request->post['email'], $code);

            $subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));

            $message  = sprintf($this->language->get('text_greeting'), $this->config->get('config_name')) . "\n\n";
            $message .= $this->language->get('text_change') . "\n\n";
            //$message .= $this->url->link('common/reset', 'code=' . $code, 'SSL') . "\n\n";
            $message .= $server.'admin/index.php?path=common/reset&code='.$code . "\n\n";
            
            $message .= sprintf($this->language->get('text_ip'), $this->request->server['REMOTE_ADDR']) . "\n\n";

            $mail = new mail($this->config->get('config_mail'));
            $mail->setTo($this->request->post['email']);
            $mail->setFrom($this->config->get('config_from_email'));
            $mail->setSender($this->config->get('config_name'));
            $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
            $mail->setHtml(html_entity_decode(strip_tags($message), ENT_QUOTES, 'UTF-8'));
            $mail->send();

                
            unset($this->session->data['api_id']);
            $json['success'] = $this->language->get('text_success');
            $json['status'] = 200;

        } else {
            $json['message'] = $this->language->get('error_email');
        }
        

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validate() {
        if (!isset($this->request->post['email'])) {
            $this->error['warning'] = $this->language->get('error_email');
        } elseif (!$this->model_user_user->getTotalUsersByEmail($this->request->post['email'])) {
            $this->error['warning'] = $this->language->get('error_email');
        }

        return !$this->error;
    }

}
