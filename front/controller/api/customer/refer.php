<?php

require_once DIR_SYSTEM.'/vendor/konduto/vendor/autoload.php';

require_once DIR_SYSTEM.'/vendor/fcp-php/autoload.php';

require DIR_SYSTEM.'vendor/Facebook/autoload.php';

require_once DIR_APPLICATION.'/controller/api/settings.php';

class ControllerApiCustomerRefer extends Controller
{
    private $error = [];

    public function getUserRefers()
    {
        $json = [];

        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');

        $this->load->language('account/edit');
        $this->load->language('account/account');
        $this->load->model('account/customer');

        //if( $this->customer->isLogged()) {
        if (true) {
            $this->load->language('account/refer');

            $this->load->model('account/refer');

            $this->load->model('account/activity');

            $data['fb_app_id'] = !empty($this->config->get('config_fb_app_id')) ? $this->config->get('config_fb_app_id') : 'randomstringforappid';
            $data['fb_app_secret'] = !empty($this->config->get('config_fb_secret')) ? $this->config->get('config_fb_secret') : 'randomstringforappsecret';

            $data['refer_text'] = $this->language->get('refer_text');

            $data['total_signup_amount'] = 0;
            $data['total_referral_bonus_amount'] = $this->currency->format(0);

            $referUnique = 'refer='.strtolower(str_replace(' ', '', $this->customer->getFirstName())).strtolower(str_replace(' ', '', $this->customer->getLastName())).'!@'.strtolower($this->customer->getId());

            if ($this->request->server['HTTPS']) {
                $data['refer_link'] = $this->config->get('config_ssl');
            } else {
                $data['refer_link'] = $this->config->get('config_ssl');
            }

            $data['referral_description'] = 'Referral';

            $data['refer_link'] = rtrim($data['refer_link'], '/');
            $data['refer_link'] .= '?'.$referUnique;

            $data['total_signup_amount'] = $this->model_account_activity->getReferredSignup($this->customer->getId());

            $data['total_referral_bonus_amount'] = $this->model_account_activity->getReferredBonus($this->customer->getId(), $data['referral_description']);

            $json['data'] = $data;
        } else {
            $json['status'] = 10013;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_not_loggedin')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validate()
    {
        return !$this->error;
    }

    public function addUserRefer()
    {
        $json = [];

        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');

        $this->load->language('account/edit');
        $this->load->language('account/account');
        $this->load->model('account/customer');

        //if( $this->customer->isLogged()) {
        if (true) {
            $this->load->language('account/refer');

            $this->load->model('account/refer');

            $this->load->model('account/activity');

            $data['referral_description'] = 'Referral';

            $data['app_id'] = !empty($this->config->get('config_fb_app_id')) ? $this->config->get('config_fb_app_id') : 'randomstringforappid';
            $data['app_secret'] = !empty($this->config->get('config_fb_secret')) ? $this->config->get('config_fb_secret') : 'randomstringforappsecret';

            $data['refer_text'] = $this->language->get('refer_text');
            $data['refer_link'] = '#';

            $data['total_signup_amount'] = 0;
            $data['total_referral_bonus_amount'] = $this->currency->format(0);

            $referUnique = 'refer='.strtolower(str_replace(' ', '', $this->customer->getFirstName())).strtolower(str_replace(' ', '', $this->customer->getLastName())).'!@'.strtolower($this->customer->getId());

            if ($this->request->server['HTTPS']) {
                $data['refer_link'] = $this->config->get('config_ssl');
                $data['site_link'] = $this->config->get('config_ssl');
            } else {
                $data['site_link'] = $this->config->get('config_url');
                $data['refer_link'] = $this->config->get('config_ssl');
            }

            $data['refer_link'] = rtrim($data['refer_link'], '/');
            $data['refer_link'] .= '?'.$referUnique;

            $data['total_signup_amount'] = $this->model_account_activity->getReferredSignup($this->customer->getId());

            $data['total_referral_bonus_amount'] = $this->model_account_activity->getReferredBonus($this->customer->getId(), $data['referral_description']);

            if ($this->validate()) {
                $mailData['name'] = $this->customer->getFirstName();
                $mailData['site_name'] = $this->config->get('config_name');

                $mailData['refer_link'] = $data['refer_link'];

                $emailToSendTo = explode(',', $this->request->post['email']);

                $subject = $this->emailtemplate->getSubject('Referral', 'referral_1', $mailData);
                $message = $this->emailtemplate->getMessage('Referral', 'referral_1', $mailData);

                foreach ($emailToSendTo as $emailTo) {
                    $mail = new Mail($this->config->get('config_mail'));
                    $mail->setTo($emailTo);
                    $mail->setFrom($this->config->get('config_from_email'));
                    $mail->setSender($this->request->post['yourname']);
                    $mail->setSubject($subject);
                    $mail->setHtml($message);
                    $mail->send();
                }
            }

            if (isset($this->session->data['error'])) {
                $data['error_warning'] = $this->session->data['error'];

                unset($this->session->data['error']);
            } else {
                $data['error_warning'] = '';
            }

            if (isset($this->error['yourname'])) {
                $data['error_yourname'] = $this->error['yourname'];
            } else {
                $data['error_yourname'] = '';
            }

            if (isset($this->error['email'])) {
                $data['error_email'] = $this->error['email'];
            } else {
                $data['error_email'] = '';
            }

            if (isset($this->error['message'])) {
                $data['error_message'] = $this->error['message'];
            } else {
                $data['error_message'] = '';
            }

            $json['data'] = $data;
        } else {
            $json['status'] = 10013;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_not_loggedin')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
