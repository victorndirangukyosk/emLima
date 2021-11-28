<?php

class ControllerShopperCommonHeader extends Controller
{
    public function index()
    {
        $this->load->language('common/Header');

        $data['text_home'] = $this->language->get('text_home');
        $data['text_my_order'] = $this->language->get('text_my_order');
        $data['text_my_account'] = $this->language->get('text_my_account');
        $data['text_wallet'] = $this->language->get('text_wallet');
        $data['text_setting'] = $this->language->get('text_setting');
        $data['text_password'] = $this->language->get('text_password');
        $data['text_logout'] = $this->language->get('text_logout');

        $data['config_name'] = $this->config->get('config_name');

        $data['wallet'] = $this->url->link('shopper/wallet', 'token='.$this->session->data['token'], 'SSL');
        $data['setting'] = $this->url->link('shopper/setting', 'token='.$this->session->data['token'], 'SSL');
        $data['password'] = $this->url->link('shopper/setting/password', 'token='.$this->session->data['token'], 'SSL');
        $data['request'] = $this->url->link('shopper/request', 'token='.$this->session->data['token'], 'SSL');
        $data['order'] = $this->url->link('shopper/order', 'token='.$this->session->data['token'], 'SSL');
        $data['logout'] = $this->url->link('common/logout', 'token='.$this->session->data['token'], 'SSL');

        return $this->load->view('shopper/common/header.tpl', $data);
    }
}
