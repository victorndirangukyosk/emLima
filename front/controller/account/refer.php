<?php

class ControllerAccountRefer extends Controller
{
    private $error = [];

    public function index()
    {
        if (!($this->customer->isLogged())) {
            $this->response->redirect($this->url->link('common/home'));
        }

        $this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_login.css');

        $this->load->language('account/refer');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/refer');

        $this->load->model('account/activity');

        $data['referral_description'] = 'Referral';

        $data['config_google_client_id'] = $this->config->get('config_google_client_id');

        $data['heading_title'] = $this->language->get('heading_title');
        $data['heading_text'] = $this->language->get('heading_text');
        $data['total_signup'] = $this->language->get('total_signup');
        $data['total_referral_bonus'] = $this->language->get('total_referral_bonus');

        $data['app_id'] = !empty($this->config->get('config_fb_app_id')) ? $this->config->get('config_fb_app_id') : 'randomstringforappid';
        $data['app_secret'] = !empty($this->config->get('config_fb_secret')) ? $this->config->get('config_fb_secret') : 'randomstringforappsecret';

        $data['refer_text'] = $this->language->get('refer_text');
        $data['refer_link'] = '#';

        $data['redirect_url'] = urlencode($this->url->link('account/refer/success'));
        $data['entry_refral'] = $this->language->get('entry_refral');
        $data['entry_refral_link'] = $this->language->get('entry_refral_link');
        $data['entry_refered'] = $this->language->get('entry_refered');
        $data['entry_message'] = $this->language->get('entry_message');

        $data['text_share'] = $this->language->get('text_share');
        $data['entry_send_mail'] = $this->language->get('entry_send_mail');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/information');

        // $data['error_warning'] = $this->language->get('error_warning_message');

        $data['total_signup_amount'] = 0;
        $data['total_referral_bonus_amount'] = $this->currency->format(0);
        $data['referral_data']['referrer'] = false;
        $data['referral_data']['referred'] = false;

        if (!$this->customer->isLogged()) {
            $this->response->redirect($this->url->link('common/home', '', 'SSL'));
        } else {
            $referUnique = strtolower(str_replace(' ', '', $this->customer->getFirstName())).strtolower(str_replace(' ', '', $this->customer->getLastName())).'!@'.strtolower($this->customer->getId());

            $data['refer_link'] = $this->url->link('account/invite', 'refer='.$referUnique, 'SSL');

            $data['total_signup_amount'] = $this->model_account_activity->getReferredSignup($this->customer->getId());

            $data['total_referral_bonus_amount'] = $this->model_account_activity->getReferredBonus($this->customer->getId(), $data['referral_description']);

            $referral_data = $this->model_account_activity->getReferralAmount();

            if ($referral_data['referrer']) {
                $data['referral_data']['referrer'] = sprintf($this->language->get('referrer_reward_text'), $referral_data['referrer']);
            }

            if ($referral_data['referred']) {
                $data['referral_data']['referred'] = sprintf($this->language->get('referred_reward_text'), $referral_data['referred']);
            }

            //echo "<pre>";print_r( $data['referral_data']);die;
        }

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validate()) {
            $mailData['name'] = $this->customer->getFirstName();
            $mailData['site_name'] = $this->config->get('config_name');

            $mailData['refer_link'] = $data['refer_link'];

            $mailData['reward_text'] = '';
            if ($referral_data['referred']) {
                $mailData['reward_text'] = sprintf($this->language->get('mail_referred_reward_text'), $referral_data['referred']);
            }

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

            $this->response->redirect($this->url->link('account/refer/success'));
        }
        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
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

        if (isset($this->request->post['yourname'])) {
            $data['yourname'] = $this->request->post['yourname'];
        } else {
            $data['yourname'] = $this->customer->getFirstName().' '.$this->customer->getLastName();
        }

        if (isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } else {
            $data['email'] = '';
        }

        if (isset($this->request->post['message'])) {
            $data['message'] = $this->request->post['message'];
        } else {
            $data['message'] = '';
        }

        $data['refer'] = $this->url->link('account/refer');

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/account/refer.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/account/refer.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/account/refer.tpl', $data));
        }
    }

    public function success()
    {
        $this->load->language('account/refer');

        $this->document->setTitle($this->language->get('heading_title'));
        $this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_checkout.css');

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('information/contact'),
        ];

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_message'] = $this->language->get('text_success');

        $data['button_continue'] = $this->language->get('button_continue');

        $data['continue'] = $this->url->link('common/home');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/onlyHeader');

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/common/success.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/common/success.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/common/success.tpl', $data));
        }
    }

    protected function validate()
    {
        /*
                if (!filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
                    $this->error['email'] = $this->language->get('error_email');
                }*/

        return !$this->error;
    }

    public function getReferralAmount()
    {
        $response['referrer'] = false;
        $response['referred'] = false;

        $config_reward_enabled = $this->config->get('config_reward_enabled');

        $config_credit_enabled = $this->config->get('config_credit_enabled');

        $config_refer_type = $this->config->get('config_refer_type');

        $config_refered_points = $this->config->get('config_refered_points');
        $config_referee_points = $this->config->get('config_referee_points');

        if ('reward' == $config_refer_type) {
            if ($config_reward_enabled && $config_refered_points) {
                $response['referrer'] = $config_referee_points.'Reward points';
                $response['referred'] = $config_refered_points.'Reward points';
            }
        } elseif ('credit' == $config_refer_type) {
            $log->write('credit if');

            if ($config_credit_enabled && $config_refered_points) {
                $response['referrer'] = $this->currency->format($config_referee_points);
                $response['referred'] = $this->currency->format($config_refered_points);
            }
        }

        return $response;
    }
}
