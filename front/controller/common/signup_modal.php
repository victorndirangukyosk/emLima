<?php

class ControllerCommonSignupModal extends Controller
{
    public function index()
    {
        $this->load->language('common/signup_modal');

        $data['text_number_verification'] = $this->language->get('text_number_verification');
        $data['text_enter_number_to_login'] = $this->language->get('text_enter_number_to_login');
        $data['text_enter_email_address'] = $this->language->get('text_enter_email_address');
        $data['text_enter_password'] = $this->language->get('text_enter_password');
        $data['text_move_next'] = $this->language->get('text_move_next');
        $data['text_login'] = $this->language->get('text_login');
        $data['text_or'] = $this->language->get('text_or');
        $data['text_continue_with_facebook'] = $this->language->get('text_continue_with_facebook');
        $data['text_continue_with_twitter'] = $this->language->get('text_continue_with_twitter');
        $data['text_continue_with_google'] = $this->language->get('text_continue_with_google');
        $data['text_back'] = $this->language->get('text_back');
        $data['text_code_verification'] = $this->language->get('text_code_verification');
        $data['text_enter_code_in_area'] = $this->language->get('text_enter_code_in_area');
        $data['text_enter_phone'] = $this->language->get('text_enter_phone');
        $data['text_move_Next'] = $this->language->get('text_move_Next');
        $data['text_success_verification'] = $this->language->get('text_success_verification');
        $data['text_enter_you_agree'] = $this->language->get('text_enter_you_agree');
        $data['text_terms_of_service'] = $this->language->get('text_terms_of_service');
        $data['text_privacy_policy'] = $this->language->get('text_privacy_policy');
        $data['text_welcome_message'] = $this->language->get('text_welcome_message').' '.$this->config->get('config_name');
        $data['error_agree_terms'] = $this->language->get('error_agree_terms');

        $data['text_verify'] = $this->language->get('text_verify');

        $data['text_have_account'] = $this->language->get('text_have_account');
        $data['text_log_in'] = $this->language->get('text_log_in');

        $data['account_register'] = $this->load->controller('account/register');

        $data['privacy_link'] = $this->url->link('information/information', 'information_id='.$this->config->get('config_privacy_policy_id'), 'SSL');

        $data['account_terms_link'] = $this->url->link('information/information', 'information_id='.$this->config->get('config_account_id'), 'SSL');

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/common/signup_modal.tpl')) {
            return $this->load->view($this->config->get('config_template').'/template/common/signup_modal.tpl', $data);
        } else {
            return $this->load->view('default/template/common/signup_modal.tpl', $data);
        }
    }
}
