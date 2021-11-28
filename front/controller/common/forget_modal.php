<?php

class ControllerCommonForgetModal extends Controller
{
    public function index()
    {
        $this->load->language('common/forget_modal');

        $data['text_find_account'] = $this->language->get('text_find_account');
        $data['text_forget'] = $this->language->get('text_forget');
        $data['text_enter_email_address'] = $this->language->get('text_enter_email_address');
        $data['text_enter_password'] = $this->language->get('text_enter_password');
        $data['text_move_next'] = $this->language->get('text_move_next');
        $data['text_success_verification'] = $this->language->get('text_success_verification');
        $data['text_enter_you_agree'] = $this->language->get('text_enter_you_agree');
        $data['text_terms_of_service'] = $this->language->get('text_terms_of_service');
        $data['text_privacy_policy'] = $this->language->get('text_privacy_policy');
        $data['text_welcome_message'] = $this->language->get('text_welcome_message');
        $data['text_have_account'] = $this->language->get('text_have_account');
        $data['text_forget_password'] = $this->language->get('text_forget_password');

        $data['forget_link'] = $this->url->link('account/forgotten');

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/common/forget_modal.tpl')) {
            return $this->load->view($this->config->get('config_template').'/template/common/forget_modal.tpl', $data);
        } else {
            return $this->load->view('default/template/common/forget_modal.tpl', $data);
        }
    }
}
