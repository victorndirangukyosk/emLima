<?php

class ControllerAccountInvite extends Controller
{
    public function index()
    {
        $this->load->language('account/invite');

        /*$referUnique = 'ferf';
        echo "<pre>";print_r($this->url->link('account/account', 'refer='.$referUnique, 'SSL'));die;*/
        $log = new Log('error.log');

        if ($this->customer->isLogged()) {
            $this->response->redirect($this->url->link('common/home'));
        }

        if (isset($this->session->data['language'])) {
            $data['config_language'] = $this->session->data['language'];
        } else {
            $data['config_language'] = 'pt-BR';
        }

        $p = false;
        if (isset($this->request->get['refer'])) {
            $x = strpos($this->request->get['refer'], '%21%40');
            $p = substr($this->request->get['refer'], $x + 6);

            /*$x = strpos($this->request->get['refer'],"!@");
            $p = substr($this->request->get['refer'], $x+2);*/
            //echo "<pre>";print_r($x);die;

            //echo "<pre>";print_r($p);die;

            $cookie_name = 'referral';
            $cookie_value = $p;

            //echo "<pre>";print_r($p);die;
            setcookie($cookie_name, $cookie_value, time() + (86400 * 30), '/'); // 1 day expiry
        }

        $this->load->model('account/activity');

        $data['referral_data']['referrer'] = false;
        $data['referral_data']['referred'] = false;

        //echo "<pre>";print_r($this->request->get['refer']);die;
        //echo "<pre>";print_r($p);die;

        $this->load->model('tool/image');
        $this->load->model('account/customer');

        //echo "<pre>";print_r($data['blocks']);die;
        $this->document->setTitle($this->config->get('config_meta_title'));
        $this->document->setDescription($this->config->get('config_meta_description'));
        $this->document->setKeywords($this->config->get('config_meta_keyword'));

        $data['description'] = $this->document->getDescription();
        $data['keywords'] = $this->document->getKeywords();
        $data['metas'] = $this->document->getMetas();

        //echo "<pre>";print_r($data);die;

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        if ($this->config->get('config_google_analytics_status')) {
            $data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');
        } else {
            $data['google_analytics'] = '';
        }

        $data['base'] = $server;
        $data['heading_title'] = $this->language->get('heading_title');

        $data['system_name'] = $this->config->get('config_name');

        $data['invitation_text'] = sprintf($this->language->get('user_not_found_text'), $data['system_name']);

        if ($p) {
            $customer_info = $this->model_account_customer->getCustomer($p);

            if ($customer_info) {
                $data['invitation_text'] = sprintf($this->language->get('invitation_text'), $customer_info['firstname'], $data['system_name']);

                $referral_data = $this->model_account_activity->getReferralAmount();

                if ($referral_data['referrer']) {
                    $data['referral_data']['referrer'] = sprintf($this->language->get('referrer_reward_text'), $referral_data['referrer']);
                }

                if ($referral_data['referred']) {
                    $data['referral_data']['referred'] = sprintf($this->language->get('referred_reward_text'), $referral_data['referred']);
                }
            }
        }

        $data['text_register'] = $this->language->get('text_register');

        if (is_file(DIR_IMAGE.$this->config->get('config_icon'))) {
            //$data['icon'] = $server . 'image/' . $this->config->get('config_icon');
            $data['icon'] = $this->model_tool_image->resize($this->config->get('config_icon'), 30, 30);
        } else {
            $data['icon'] = '';
        }

        if (is_file(DIR_IMAGE.$this->config->get('config_fav_icon'))) {
            $data['fav_icon'] = $server.'image/'.$this->config->get('config_fav_icon');
        } else {
            $data['fav_icon'] = '';
        }

        if (is_file(DIR_IMAGE.$this->config->get('config_logo'))) {
            $data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'), 240, 41);
        //$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
        } else {
            $data['logo'] = '';
        }

        $data['playStorelogo'] = $this->model_tool_image->resize('play-store-logo.png', 100, 30);

        $data['appStorelogo'] = $this->model_tool_image->resize('app-store-logo.png', 100, 30);

        if (isset($this->session->data['warning'])) {
            $data['warning'] = $this->session->data['warning'];
            unset($this->session->data['warning']);
        } else {
            $data['warning'] = '';
        }

        $data['play_store'] = $this->config->get('config_android_app_link');
        $data['app_store'] = $this->config->get('config_apple_app_link');

        $this->load->model('setting/setting');
        $te = $this->model_setting_setting->getSetting('config');

        if ($te) {
            $data['play_store'] = $te['config_android_app_link'];
            $data['app_store'] = $te['config_apple_app_link'];
        }

        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/onlyheader');

        $data['login_modal'] = $this->load->controller('common/login_modal');

        $data['signup_modal'] = $this->load->controller('common/signup_modal');

        $data['forget_modal'] = $this->load->controller('common/forget_modal');

        $data['heading_title'] = $this->config->get('config_meta_title', '');

        $data['login'] = $this->url->link('account/login', '', 'SSL');
        $data['register'] = $this->url->link('account/register', '', 'SSL');
        $data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/account/invite.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/account/invite.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/account/invite.tpl', $data));
        }
    }
}
