<?php

class ControllerCommonFooter extends Controller
{
    public function index()
    {
        $this->load->language('common/footer');

        $data['text_information'] = $this->language->get('text_information');
        $data['text_service'] = $this->language->get('text_service');
        $data['text_extra'] = $this->language->get('text_extra');
        $data['text_contact'] = $this->language->get('text_contact');
        $data['text_return'] = $this->language->get('text_return');
        $data['text_sitemap'] = $this->language->get('text_sitemap');
        $data['text_manufacturer'] = $this->language->get('text_manufacturer');
        $data['text_voucher'] = $this->language->get('text_voucher');
        $data['text_affiliate'] = $this->language->get('text_affiliate');
        $data['text_special'] = $this->language->get('text_special');
        $data['text_account'] = $this->language->get('text_account');
        $data['text_order'] = $this->language->get('text_order');
        $data['text_wishlist'] = $this->language->get('text_wishlist');
        $data['text_newsletter'] = $this->language->get('text_newsletter');
        $data['text_best_prices'] = $this->language->get('text_best_prices');
        $data['text_offers'] = $this->language->get('text_offers');
        $data['login_seller'] = $this->language->get('login_seller');
        $data['text_logo_title'] = $this->language->get('text_logo_title');
        $data['text_variety_title'] = $this->language->get('text_variety_title');
        $data['text_wide_assortment'] = $this->language->get('text_wide_assortment');
        $data['text_easy_returns'] = $this->language->get('text_easy_returns');
        $data['text_return_title'] = $this->language->get('text_return_title');
        $data['text_about_title'] = $this->language->get('text_about_title');
        $data['text_about_detail_part1'] = $this->language->get('text_about_detail_part1');
        $data['text_about_detail_part3'] = $this->language->get('text_about_detail_part3');
        $data['text_about_detail_part2'] = $this->language->get('text_about_detail_part2');
        $data['text_read_more'] = $this->language->get('text_read_more');
        $data['text_useful_links'] = $this->language->get('text_useful_links');
        $data['text_store_listed'] = $this->language->get('text_store_listed');
        $data['text_faq'] = $this->language->get('text_faq');
        $data['text_careers'] = $this->language->get('text_careers');
        $data['text_download_app'] = $this->language->get('text_download_app');
        //$data['text_trademark'] = $this->language->get('text_trademark');

        if ($this->config->get('config_name')) {
            $data['text_trademark'] = $this->config->get('config_name');
        } else {
            $data['text_trademark'] = '';
        }

        if ($this->config->get('config_footer_text')) {
            $data['footer_text'] = $this->config->get('config_footer_text');
        } else {
            $data['footer_text'] = 0;
        }

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['footer_video_link'] = $this->config->get('config_footer_video_link');

        if (is_file(DIR_IMAGE.$this->config->get('config_footer_thumb'))) {
            $data['footer_thumb'] = 'image/'.$this->config->get('config_footer_thumb');
        } else {
            $data['footer_thumb'] = '';
        }
        //echo "<pre>";print_r($this->config->get('config_footer_text'));die;

        if ($this->config->get('config_google_analytics_status')) {
            $data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');
        } else {
            $data['google_analytics'] = '';
        }

        $data['label_text'] = $this->language->get('label_text');
        $data['locations'] = $this->language->get('locations');
        $data['blog'] = $this->language->get('blog');
        $data['contactus'] = $this->language->get('contactus');
        $data['list_your_products'] = $this->language->get('list_your_products');
        $data['become_shopper'] = $this->language->get('become_shopper');
        $data['powered'] = $this->language->get('powered');

        $data['contactus_modal'] = $this->load->controller('information/contact');

        $this->load->model('assets/information');

        $data['informations'] = [];

        foreach ($this->model_assets_information->getInformations() as $result) {
            if ($result['bottom']) {
                $data['informations'][] = [
                    'title' => $result['title'],
                    'href' => $this->url->link('information/information', 'information_id='.$result['information_id']),
                ];
            }
        }

        $data['aboutus'] = $this->config->get('config_aboutus');
        $data['aboutus_link'] = $this->url->link('information/information', 'information_id='.$this->config->get('config_information_url'));

        $data['facebook'] = $this->config->get('facebook');
        $data['twitter'] = $this->config->get('twitter');
        $data['google'] = $this->config->get('google');
        $data['play_store'] = $this->config->get('config_android_app_link');
        $data['app_store'] = $this->config->get('config_apple_app_link');

        $data['youtube'] = $this->config->get('config_youtube');
        $data['instagram'] = $this->config->get('config_instagram');

        $data['playStorelogo'] = $this->model_tool_image->resize('play-store-logo.png', 200, 60);

        $data['appStorelogo'] = $this->model_tool_image->resize('app-store-logo.png', 200, 60);

        $this->load->model('setting/setting');
        $te = $this->model_setting_setting->getSetting('config');

        if ($te) {
            $data['facebook'] = $te['config_facebook'];
            $data['twitter'] = $te['config_twitter'];
            $data['google'] = $te['config_google'];
            $data['play_store'] = $te['config_android_app_link'];
            $data['app_store'] = $te['config_apple_app_link'];

            $data['youtube'] = $te['config_youtube'];
            $data['instagram'] = $te['config_instagram'];
        }
        $data['help'] = $this->url->link('information/help');
        $data['contact'] = $this->url->link('information/contact');
        $data['return'] = $this->url->link('account/return/add', '', 'SSL');
        $data['sitemap'] = $this->url->link('information/sitemap');
        $data['manufacturer'] = $this->url->link('product/manufacturer');
        $data['voucher'] = $this->url->link('account/voucher', '', 'SSL');
        $data['affiliate'] = $this->url->link('affiliate/account', '', 'SSL');
        $data['special'] = $this->url->link('product/special');
        $data['account'] = $this->url->link('account/account', '', 'SSL');
        $data['order'] = $this->url->link('account/order', '', 'SSL');
        $data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
        $data['newsletter'] = $this->url->link('account/newsletter', '', 'SSL');

        $data['powered'] = sprintf($this->language->get('text_powered'), $this->config->get('config_name'), date('Y', time()));

        // Whos Online
        if ($this->config->get('config_customer_online')) {
            $this->load->model('tool/online');

            if (isset($this->request->server['REMOTE_ADDR'])) {
                $ip = $this->request->server['REMOTE_ADDR'];
            } else {
                $ip = '';
            }

            if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {
                $url = 'http://'.$this->request->server['HTTP_HOST'].$this->request->server['REQUEST_URI'];
            } else {
                $url = '';
            }

            if (isset($this->request->server['HTTP_REFERER'])) {
                $referer = $this->request->server['HTTP_REFERER'];
            } else {
                $referer = '';
            }

            $this->model_tool_online->whosonline($ip, $this->customer->getId(), $url, $referer);
        }

        $data['language'] = $this->load->controller('common/language');

        if (is_file(DIR_IMAGE.$this->config->get('config_logo'))) {
            $data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'), 197, 34);
        //$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
        } else {
            $data['logo'] = '';
        }

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['base'] = $server;

        $data['change_store'] = $this->url->link('common/home/show_home', '', 'SSL');

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/common/footer.tpl')) {
            return $this->load->view($this->config->get('config_template').'/template/common/footer.tpl', $data);
        } else {
            return $this->load->view('default/template/common/footer.tpl', $data);
        }
    }
}
