<?php

class ControllerCommonHeader extends Controller {

    //header for help pages
    public function help() {
        $this->load->model('tool/image');
        $data['title'] = $this->document->getTitle();

        $data['kondutoStatus'] = $this->config->get('config_konduto_status');
        $data['text_help_center'] = $this->language->get('text_help_center');

        if ($this->config->get('config_meta_generator')) {
            $this->document->addMeta('generator', $this->config->get('config_meta_generator'));
        }

        if ($this->config->get('config_meta_googlekey')) {
            $this->document->addMeta('google-site-verification', $this->config->get('config_meta_googlekey'));
        }

        if ($this->config->get('config_meta_alexakey')) {
            $this->document->addMeta('alexaVerifyID', $this->config->get('config_meta_alexakey'));
        }

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
            $data['icon'] = $server . 'image/' . $this->config->get('config_icon');
        } else {
            $data['icon'] = '';
        }

        if (isset($this->session->data['language'])) {
            $data['config_language'] = $this->session->data['language'];
        } else {
            $data['config_language'] = 'pt-BR';
        }

        /* if(!$server){
          $server = HTTP_SERVER;
          } */

        $this->document->setDescription($this->config->get('config_meta_description'));
        $this->document->setKeywords($this->config->get('config_meta_keyword'));

        $data['base'] = $server;
        $data['description'] = $this->document->getDescription();
        $data['keywords'] = $this->document->getKeywords();
        $data['metas'] = $this->document->getMetas();
        $data['links'] = $this->document->getLinks();
        $data['styles'] = $this->document->getStyles();
        $data['scripts'] = $this->document->getScripts();
        $data['lang'] = $this->language->get('code');
        $data['direction'] = $this->language->get('direction');

        //$data['store'] = $this->url->link('product/store', '', 'SSL');
        //$data['store'] = $this->url->link('product/store','store_id='.$this->session->data['config_store_id'].'');

        if (isset($this->session->data['config_store_id'])) {
            $data['store'] = $this->url->link('product/store', 'store_id=' . $this->session->data['config_store_id'] . '');
        } else {
            $data['store'] = $this->url->link('product/store');
        }

        if ($this->config->get('config_google_analytics_status')) {
            $data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');
        } else {
            $data['google_analytics'] = '';
        }

        $data['name'] = $this->config->get('config_name');

        if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
            //$data['icon'] = $server . 'image/' . $this->config->get('config_icon');
            $data['icon'] = $this->model_tool_image->resize($this->config->get('config_icon'), 30, 30);
        } else {
            $data['icon'] = '';
        }

        if (is_file(DIR_IMAGE . $this->config->get('config_small_icon'))) {
            //$data['small_icon'] = $server . 'image/' . $this->config->get('config_small_icon');
            $data['small_icon'] = $this->model_tool_image->resize($this->config->get('config_small_icon'), 30, 30);
        } else {
            $data['small_icon'] = '';
        }

        if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
            $data['logo'] = $server . 'image/' . $this->config->get('config_logo');
            //$data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'),197,34);
        } else {
            $data['logo'] = '';
        }

        $this->load->language('common/header');

        $data['text_how_can_help'] = $this->language->get('text_how_can_help');
        $data['text_genuine_product'] = $this->language->get('text_genuine_product');
        $data['text_secure_payments'] = $this->language->get('text_secure_payments');
        $data['text_replacement_guarantee'] = $this->language->get('text_replacement_guarantee');

        $data['home'] = $this->url->link('common/home');

        $status = true;

        if (isset($this->request->server['HTTP_USER_AGENT'])) {
            $robots = explode("\n", str_replace(["\r\n", "\r"], "\n", trim($this->config->get('config_robots'))));

            foreach ($robots as $robot) {
                if ($robot && false !== strpos($this->request->server['HTTP_USER_AGENT'], trim($robot))) {
                    $status = false;

                    break;
                }
            }
        }

        $data['is_login'] = $this->customer->isLogged();
        $data['name'] = $this->customer->getFirstName();

        $data['f_name'] = $this->customer->getFirstName();
        $data['l_name'] = $this->customer->getLastName();
        $data['full_name'] = $data['f_name']; //d.' '.$data['l_name'];
        $this->load->language('common/header');

        $data['text_home'] = $this->language->get('text_home');
        $data['text_wishlist'] = $this->language->get('text_wishlist');
        $data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
        $data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', 'SSL'), $this->customer->getFirstName(), $this->url->link('account/logout', '', 'SSL'));
        $data['text_my_cash'] = $this->language->get('text_my_cash');
        $data['text_my_wishlist'] = $this->language->get('text_my_wishlist');
        $data['heading_title'] = $this->language->get('heading_title');
        $data['contactus'] = $this->language->get('contactus');
        $data['text_all_categories'] = $this->language->get('text_all_categories');
        $data['list_products'] = $this->language->get('list_products');
        $data['button_clear_cart'] = $this->language->get('button_clear_cart');
        $data['text_search_product'] = $this->language->get('text_search_product');
        $data['text_verify_number'] = $this->language->get('text_verify_number');
        $data['text_proceed_to_checkout'] = $this->language->get('text_proceed_to_checkout');

        $data['text_shopping_from'] = $this->language->get('text_shopping_from');
        $data['support'] = $this->language->get('support');
        $data['faq'] = $this->language->get('faq');
        $data['call'] = $this->language->get('call');
        $data['text'] = $this->language->get('text');
        $data['text_offers'] = $this->language->get('text_offers');
        $data['text_menu'] = $this->language->get('text_menu');
        $data['text_my_profile'] = $this->language->get('text_my_profile');
        $data['text_rewards'] = $this->language->get('text_rewards');
        $data['text_orders'] = $this->language->get('text_orders');
        $data['text_refer'] = $this->language->get('text_refer');
        $data['text_sign_out'] = $this->language->get('text_sign_out');
        $data['text_sign_in'] = $this->language->get('text_sign_in');
        $data['button_recipes'] = $this->language->get('button_recipes');
        $data['label_my_address'] = $this->language->get('label_my_address');
        $data['text_account'] = $this->language->get('text_account');
        $data['text_register'] = $this->language->get('text_register');
        $data['text_login'] = $this->language->get('text_login');
        $data['text_order'] = $this->language->get('text_order');
        $data['text_credit'] = $this->language->get('text_credit');
        $data['text_user_product_notes'] = $this->language->get('text_user_product_notes');
        $data['text_user_notification_settings'] = $this->language->get('text_user_notification_settings');
        $data['text_download'] = $this->language->get('text_download');
        $data['text_logout'] = $this->language->get('text_logout');
        $data['text_checkout'] = $this->language->get('text_checkout');
        $data['text_category'] = $this->language->get('text_category');
        $data['text_all'] = $this->language->get('text_all');

        $data['home'] = $this->url->link('common/home');
        $data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
        $data['logged'] = $this->customer->isLogged();
        $data['account'] = $this->url->link('account/account', '', 'SSL');
        $data['dashboard'] = $this->url->link('account/dashboard', '', 'SSL');
        $data['register'] = $this->url->link('account/register', '', 'SSL');
        $data['login'] = $this->url->link('account/login', '', 'SSL');
        $data['order'] = $this->url->link('account/order', '', 'SSL');
        $data['credit'] = $this->url->link('account/credit', '', 'SSL');
        $data['user_product_notes'] = $this->url->link('account/user_product_notes', '', 'SSL');
        $data['user_notification_settings'] = $this->url->link('account/user_notification_settings', '', 'SSL');
        $data['download'] = $this->url->link('account/download', '', 'SSL');
        $data['logout'] = $this->url->link('account/logout', '', 'SSL');
        $data['shopping_cart'] = $this->url->link('checkout/cart');
        $data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
        $data['contact'] = $this->url->link('information/contact');
        $data['telephone'] = $this->config->get('config_telephone');
        $data['refer'] = $this->url->link('account/refer', '', 'SSL');
        $data['help'] = $this->url->link('information/help');

        /*         * *Sigup / register /contact modals * */
        $data['contactus_modal'] = $this->load->controller('information/contact');
        $data['login_modal'] = $this->load->controller('common/login_modal');
        $data['signup_modal'] = $this->load->controller('common/signup_modal');
        $data['forget_modal'] = $this->load->controller('common/forget_modal');

        $data['wallet_url'] = $this->url->link('account/credit', '', 'SSL');
        $data['wallet_amount'] = $this->load->controller('account/credit/getWalletTotal');

        $data['language'] = $this->load->controller('common/language/dropdown');
        if (isset($this->session->data['config_store_id'])) {
            $data['go_to_store'] = $this->url->link('product/store', 'store_id=' . $this->session->data['config_store_id'] . '');
        } else {
            $data['go_to_store'] = $this->url->link('product/store');
        }
        $data['checkout_summary'] = $this->url->link('checkout/checkoutitems', '', 'SSL');
        //echo "<pre>";print_r($data);die;
        $data['refer'] = $this->url->link('account/refer', '', 'SSL');
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header_help.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/common/header_help.tpl', $data);
        } else {
            return $this->load->view('default/template/common/header_help.tpl', $data);
        }
    }

    //header for information pages
    public function information() {
        $data['title'] = $this->document->getTitle();
        if (!empty($this->request->get['path']) and ('common/home' != $this->request->get['path'])) {
            if ('pre' == $this->config->get('config_meta_title_add', 0)) {
                $data['title'] = $this->config->get('config_meta_title', '') . ' - ' . $data['title'];
            } elseif ('post' == $this->config->get('config_meta_title_add', 0)) {
                $data['title'] = $data['title'] . ' - ' . $this->config->get('config_meta_title', '');
            }
        }

        if (isset($this->session->data['language'])) {
            $data['config_language'] = $this->session->data['language'];
        } else {
            $data['config_language'] = 'pt-BR';
        }

        $data['kondutoStatus'] = $this->config->get('config_konduto_status');

        //echo "fewf";print_r($data['title']);die;
        if ($this->config->get('config_meta_generator')) {
            $this->document->addMeta('generator', $this->config->get('config_meta_generator'));
        }

        if ($this->config->get('config_meta_googlekey')) {
            $this->document->addMeta('google-site-verification', $this->config->get('config_meta_googlekey'));
        }

        if ($this->config->get('config_meta_alexakey')) {
            $this->document->addMeta('alexaVerifyID', $this->config->get('config_meta_alexakey'));
        }

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        if (isset($this->session->data['error'])) {
            $data['error_warning'] = $this->session->data['error'];

            unset($this->session->data['error']);
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        //echo "<pre>";print_r($server);die;
        if (is_file(DIR_APPLICATION . 'view/theme/' . $this->config->get('config_template') . '/stylesheet/customizer.css')) {
            $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/customizer.css');
            $this->checkFont();
            $this->checkCustom();
        }

        $this->document->setDescription($this->config->get('config_meta_description'));
        $this->document->setKeywords($this->config->get('config_meta_keyword'));

        $data['base'] = $server;
        $data['description'] = $this->document->getDescription();
        $data['keywords'] = $this->document->getKeywords();
        $data['metas'] = $this->document->getMetas();
        $data['links'] = $this->document->getLinks();
        $data['styles'] = $this->document->getStyles();
        $data['scripts'] = $this->document->getScripts();
        $data['lang'] = $this->language->get('code');
        $data['direction'] = $this->language->get('direction');

        $this->load->model('assets/category');

        if (isset($this->session->data['config_store_id'])) {
            $data['store'] = $this->model_assets_category->getStoreData($this->session->data['config_store_id']);
        } else {
            $data['store'] = [];
        }
        if ($this->config->get('config_google_analytics_status')) {
            $data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');
        } else {
            $data['google_analytics'] = '';
        }

        $data['name'] = $this->config->get('config_name');

        if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
            //$data['icon'] = $server . 'image/' . $this->config->get('config_icon');
            $data['icon'] = $this->model_tool_image->resize($this->config->get('config_icon'), 30, 30);
        } else {
            $data['icon'] = '';
        }

        if (is_file(DIR_IMAGE . $this->config->get('config_small_icon'))) {
            //$data['small_icon'] = $server . 'image/' . $this->config->get('config_small_icon');
            $data['small_icon'] = $this->model_tool_image->resize($this->config->get('config_small_icon'), 30, 30);
        } else {
            $data['small_icon'] = '';
        }

        if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
            $data['logo'] = $server . 'image/' . $this->config->get('config_logo');
            // $data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'),197,34);
        } else {
            $data['logo'] = '';
        }

        $data['is_login'] = $this->customer->isLogged();
        $data['name'] = $this->customer->getFirstName();

        $data['f_name'] = $this->customer->getFirstName();
        $data['l_name'] = $this->customer->getLastName();
        $data['full_name'] = $data['f_name']; //.' '.$data['l_name'];
        /* Added new params */
        $data['text_my_cash'] = 'My Wallet';
        $data['text_my_wishlist'] = 'My List';
        $data['text_my_incompleteorders'] = 'Incomplete Orders';
        $data['label_my_address'] = 'My Addresses';
        $data['contactus'] = $this->language->get('contactus');
        $data['text_cash'] = $this->language->get('text_cash');
        $this->load->language('common/header');

        $data['user_telephone'] = $this->formatTelephone($this->customer->getTelephone());

        $data['text_shopping_cart'] = $this->language->get('text_shopping_cart');

        $data['text_home'] = $this->language->get('text_home');
        $data['text_wishlist'] = $this->language->get('text_wishlist');
        $data['text_incompleteorders'] = $this->language->get('text_incompleteorders');
        $data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', 'SSL'), $this->customer->getFirstName(), $this->url->link('account/logout', '', 'SSL'));

        $data['label_address'] = $this->language->get('label_address');
        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_replacement_guarantee'] = $this->language->get('text_replacement_guarantee');

        $data['text_genuine_product'] = $this->language->get('text_genuine_product');
        $data['text_secure_payments'] = $this->language->get('text_secure_payments');
        $data['text_replacement_guarantee'] = $this->language->get('text_replacement_guarantee');

        $data['text_cash'] = $this->language->get('text_cash');
        $data['text_signout'] = $this->language->get('text_signout');
        $data['text_all_categories'] = $this->language->get('text_all_categories');
        $data['list_products'] = $this->language->get('list_products');
        $data['text_shopping_from'] = $this->language->get('text_shopping_from');
        $data['text_menu'] = $this->language->get('text_menu');
        $data['support'] = $this->language->get('support');
        $data['faq'] = $this->language->get('faq');
        $data['call'] = $this->language->get('call');
        $data['text'] = $this->language->get('text');
        $data['text_account'] = $this->language->get('text_account');
        $data['text_rewards'] = $this->language->get('text_rewards');
        $data['text_orders'] = $this->language->get('text_orders');
        $data['text_refer'] = $this->language->get('text_refer');
        $data['text_sign_out'] = $this->language->get('text_sign_out');
        $data['text_sign_in'] = $this->language->get('text_sign_in');
        $data['button_recipes'] = $this->language->get('button_recipes');

        $data['text_account'] = $this->language->get('text_account');
        $data['text_register'] = $this->language->get('text_register');
        $data['text_login'] = $this->language->get('text_login');
        $data['text_order'] = $this->language->get('text_order');
        $data['text_return'] = $this->language->get('text_return');
        $data['text_profile'] = $this->language->get('text_profile');
        $data['text_profile_info'] = 'Other Information';
        $data['text_transactions'] = 'My Transactions';
        $data['text_sub_customer'] = 'Sub Users';
        $data['text_customer_contacts'] = 'My Contacts';
        $data['text_credit'] = $this->language->get('text_credit');
        $data['text_user_product_notes'] = $this->language->get('text_user_product_notes');
        $data['text_user_notification_settings'] = $this->language->get('text_user_notification_settings');
        $data['text_download'] = $this->language->get('text_download');
        $data['text_logout'] = $this->language->get('text_logout');
        $data['text_checkout'] = $this->language->get('text_checkout');
        $data['text_category'] = $this->language->get('text_category');
        $data['text_all'] = $this->language->get('text_all');

        $data['text_hello'] = $this->language->get('text_hello');

        $data['address'] = $this->url->link('account/address', '', 'SSL');
        $data['home'] = $this->url->link('common/home/toHome', '', 'SSL');
        $data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
        $data['incompleteorders'] = $this->url->link('account/incompleteorders', '', 'SSL');
        $data['logged'] = $this->customer->isLogged();
        $data['account'] = $this->url->link('account/account', '', 'SSL');
        $data['dashboard'] = $this->url->link('account/dashboard', '', 'SSL');

        $data['profile_info'] = $this->url->link('account/profileinfo', '', 'SSL');
        $data['account_transactions'] = $this->url->link('account/transactions', '', 'SSL');
        $data['sub_users'] = $this->url->link('account/sub_users', '', 'SSL');
        $data['customer_contacts'] = $this->url->link('account/customer_contacts', '', 'SSL');
        $data['account_edit'] = $this->url->link('account/edit', '', 'SSL');
        $data['register'] = $this->url->link('account/register', '', 'SSL');
        $data['login'] = $this->url->link('account/login', '', 'SSL');
        $data['order'] = $this->url->link('account/order', '', 'SSL');
        $data['return'] = $this->url->link('account/return', '', 'SSL');
        $data['credit'] = $this->url->link('account/credit', '', 'SSL');
        $data['user_product_notes'] = $this->url->link('account/user_product_notes', '', 'SSL');
        $data['user_notification_settings'] = $this->url->link('account/user_notification_settings', '', 'SSL');
        $data['download'] = $this->url->link('account/download', '', 'SSL');
        $data['logout'] = $this->url->link('account/logout', '', 'SSL');
        $data['shopping_cart'] = $this->url->link('checkout/cart');
        $data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
        $data['contact'] = $this->url->link('information/contact');
        $data['telephone'] = $this->config->get('config_telephone');
        $data['help'] = $this->url->link('information/help');
        $data['refer'] = $this->url->link('account/refer', '', 'SSL');
        $data['reward'] = $this->url->link('account/reward', '', 'SSL');

        //$data['store'] = $this->url->link('product/store', '', 'SSL');

        if (isset($this->session->data['config_store_id'])) {
            $data['store'] = $this->url->link('product/store', 'store_id=' . $this->session->data['config_store_id'] . '');
        } else {
            $data['store'] = $this->url->link('product/store');
        }

        $status = true;

        if (isset($this->request->server['HTTP_USER_AGENT'])) {
            $robots = explode("\n", str_replace(["\r\n", "\r"], "\n", trim($this->config->get('config_robots'))));

            foreach ($robots as $robot) {
                if ($robot && false !== strpos($this->request->server['HTTP_USER_AGENT'], trim($robot))) {
                    $status = false;

                    break;
                }
            }
        }

        $data['cart'] = $this->load->controller('common/cart');

        // For page specific css
        if (isset($this->request->get['path'])) {
            if (isset($this->request->get['product_id'])) {
                $class = '-' . $this->request->get['product_id'];
            } elseif (isset($this->request->get['category'])) {
                $class = '-' . $this->request->get['category'];
            } elseif (isset($this->request->get['manufacturer_id'])) {
                $class = '-' . $this->request->get['manufacturer_id'];
            } else {
                $class = '';
            }

            $data['class'] = str_replace('/', '-', $this->request->get['path']) . $class;
        } else {
            $data['class'] = 'common-home';
        }
        $data['checkout_summary'] = $this->url->link('checkout/checkoutitems', '', 'SSL');
        // echo '<pre>';print_r($data);exit;
        $data['language'] = $this->load->controller('common/language/dropdown');
        $data['contactus_modal'] = $this->load->controller('information/contact');
        $data['reportissue_modal'] = $this->load->controller('information/reportissue');
        $data['wallet_url'] = $this->url->link('account/credit', '', 'SSL');
        $data['wallet_amount'] = $this->load->controller('account/credit/getWalletTotal');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header_information.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/common/header_information.tpl', $data);
        } else {
            return $this->load->view('default/template/common/header_information.tpl', $data);
        }
    }

    public function index() {
        $data['title'] = $this->document->getTitle();

        $data['breadcrumbs_title'] = $data['title'];

        $data['kondutoStatus'] = $this->config->get('config_konduto_status');

        if (!empty($this->request->get['path']) and ('common/home' != $this->request->get['path'])) {
            if ('pre' == $this->config->get('config_meta_title_add', 0)) {
                $data['title'] = $this->config->get('config_meta_title', '') . ' - ' . $data['title'];
            } elseif ('post' == $this->config->get('config_meta_title_add', 0)) {
                $data['title'] = $data['title'] . ' - ' . $this->config->get('config_meta_title', '');
            }
        }

        $this->load->model('tool/image');
        $this->load->model('assets/category');

        if (isset($this->session->data['language'])) {
            $data['config_language'] = $this->session->data['language'];
        } else {
            $data['config_language'] = 'pt-BR';
        }

        if (isset($this->session->data['config_store_id'])) {
            $data['store'] = $this->model_assets_category->getStoreData($this->session->data['config_store_id']);
        }

        if (isset($data['store']) && is_file(DIR_IMAGE . $data['store']['big_logo'])) {
            $data['store_big_logo'] = $this->model_tool_image->resize($data['store']['big_logo'], 320, 103);
        } else {
            $data['store_big_logo'] = $this->model_tool_image->resize('placeholder.png', 320, 103);
        }

        if ($this->config->get('config_meta_generator')) {
            $this->document->addMeta('generator', $this->config->get('config_meta_generator'));
        }

        if ($this->config->get('config_meta_googlekey')) {
            $this->document->addMeta('google-site-verification', $this->config->get('config_meta_googlekey'));
        }

        if ($this->config->get('config_meta_alexakey')) {
            $this->document->addMeta('alexaVerifyID', $this->config->get('config_meta_alexakey'));
        }

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        /* if(!$server){
          $server = HTTP_SERVER;
          } */

        if (is_file(DIR_APPLICATION . 'view/theme/' . $this->config->get('config_template') . '/stylesheet/customizer.css')) {
            $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/customizer.css');
            $this->checkFont();
            $this->checkCustom();
        }

        $this->document->setDescription($this->config->get('config_meta_description'));
        $this->document->setKeywords($this->config->get('config_meta_keyword'));

        $data['base'] = $server;
        $data['description'] = $this->document->getDescription();
        $data['keywords'] = $this->document->getKeywords();
        $data['metas'] = $this->document->getMetas();
        $data['links'] = $this->document->getLinks();
        $data['styles'] = $this->document->getStyles();
        $data['scripts'] = $this->document->getScripts();
        $data['lang'] = $this->language->get('code');
        $data['direction'] = $this->language->get('direction');

        if ($this->config->get('config_google_analytics_status')) {
            $data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');
        } else {
            $data['google_analytics'] = '';
        }

        $data['name'] = $this->config->get('config_name');

        if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
            //$data['icon'] = $server . 'image/' . $this->config->get('config_icon');
            //24x30
            $data['icon'] = $this->model_tool_image->resize($this->config->get('config_icon'), 30, 30);
        } else {
            $data['icon'] = '';
        }

        if (is_file(DIR_IMAGE . $this->config->get('config_small_icon'))) {
            //$data['small_icon'] = $server . 'image/' . $this->config->get('config_small_icon');
            $data['small_icon'] = $this->model_tool_image->resize($this->config->get('config_small_icon'), 30, 30);
        } else {
            $data['small_icon'] = '';
        }

        if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
            $data['logo'] = $server . 'image/' . $this->config->get('config_logo');
            //$data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'),197,34);
        } else {
            $data['logo'] = '';
        }

        $data['is_login'] = $this->customer->isLogged();
        $data['name'] = $this->customer->getFirstName();

        $data['f_name'] = $this->customer->getFirstName();
        $data['l_name'] = $this->customer->getLastName();
        $data['full_name'] = $data['f_name']; //d.' '.$data['l_name'];
        $this->load->language('common/header');

        $data['text_home'] = $this->language->get('text_home');
        $data['text_wishlist'] = $this->language->get('text_wishlist');
        $data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
        $data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', 'SSL'), $this->customer->getFirstName(), $this->url->link('account/logout', '', 'SSL'));
        $data['text_my_cash'] = $this->language->get('text_my_cash');
        $data['text_my_wishlist'] = $this->language->get('text_my_wishlist');
        $data['heading_title'] = $this->language->get('heading_title');
        $data['contactus'] = $this->language->get('contactus');
        $data['text_all_categories'] = $this->language->get('text_all_categories');
        $data['list_products'] = $this->language->get('list_products');
        $data['button_clear_cart'] = $this->language->get('button_clear_cart');
        $data['text_search_product'] = $this->language->get('text_search_product');
        $data['text_verify_number'] = $this->language->get('text_verify_number');
        $data['text_proceed_to_checkout'] = $this->language->get('text_proceed_to_checkout');

        $data['text_shopping_from'] = $this->language->get('text_shopping_from');
        $data['support'] = $this->language->get('support');
        $data['faq'] = $this->language->get('faq');
        $data['call'] = $this->language->get('call');
        $data['text'] = $this->language->get('text');
        $data['text_offers'] = $this->language->get('text_offers');
        $data['text_menu'] = $this->language->get('text_menu');
        $data['text_my_profile'] = $this->language->get('text_my_profile');
        $data['text_rewards'] = $this->language->get('text_rewards');
        $data['text_orders'] = $this->language->get('text_orders');
        $data['text_refer'] = $this->language->get('text_refer');
        $data['text_sign_out'] = $this->language->get('text_sign_out');
        $data['text_sign_in'] = $this->language->get('text_sign_in');
        $data['button_recipes'] = $this->language->get('button_recipes');
        $data['label_my_address'] = $this->language->get('label_my_address');
        $data['text_account'] = $this->language->get('text_account');
        $data['text_register'] = $this->language->get('text_register');
        $data['text_login'] = $this->language->get('text_login');
        $data['text_order'] = $this->language->get('text_order');
        $data['text_credit'] = $this->language->get('text_credit');
        $data['text_user_product_notes'] = $this->language->get('text_user_product_notes');
        $data['text_user_notification_settings'] = $this->language->get('text_user_notification_settings');
        $data['text_download'] = $this->language->get('text_download');
        $data['text_logout'] = $this->language->get('text_logout');
        $data['text_checkout'] = $this->language->get('text_checkout');
        $data['text_category'] = $this->language->get('text_category');
        $data['text_all'] = $this->language->get('text_all');

        $data['home'] = $this->url->link('common/home');
        $data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
        $data['logged'] = $this->customer->isLogged();
        $data['account'] = $this->url->link('account/account', '', 'SSL');
        $data['dashboard'] = $this->url->link('account/dashboard', '', 'SSL');

        $data['register'] = $this->url->link('account/register', '', 'SSL');
        $data['login'] = $this->url->link('account/login', '', 'SSL');
        $data['order'] = $this->url->link('account/order', '', 'SSL');
        $data['credit'] = $this->url->link('account/credit', '', 'SSL');
        $data['download'] = $this->url->link('account/download', '', 'SSL');
        $data['logout'] = $this->url->link('account/logout', '', 'SSL');
        $data['shopping_cart'] = $this->url->link('checkout/cart');
        $data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
        $data['contact'] = $this->url->link('information/contact');
        $data['telephone'] = $this->config->get('config_telephone');
        $data['refer'] = $this->url->link('account/refer', '', 'SSL');
        $data['help'] = $this->url->link('information/help');
        //$data['go_to_store'] = $this->url->link('product/store', '', 'SSL');

        /* $data['go_to_store'] = $this->url->link('product/store','store_id='.$this->session->data['config_store_id'].''); */

        if (isset($this->session->data['config_store_id'])) {
            $data['go_to_store'] = $this->url->link('product/store', 'store_id=' . $this->session->data['config_store_id'] . '');
        } else {
            $data['go_to_store'] = $this->url->link('product/store');
        }

        $data['address'] = $this->url->link('account/address', '', 'SSL');
        $data['credit'] = $this->url->link('account/credit', '', 'SSL');

        //echo "<pre>";print_r($data['checkout']);die;
        $status = true;

        if (isset($this->request->server['HTTP_USER_AGENT'])) {
            $robots = explode("\n", str_replace(["\r\n", "\r"], "\n", trim($this->config->get('config_robots'))));

            foreach ($robots as $robot) {
                if ($robot && false !== strpos($this->request->server['HTTP_USER_AGENT'], trim($robot))) {
                    $status = false;

                    break;
                }
            }
        }

        // Menu
        $this->load->model('assets/category');
        $this->load->model('assets/product');

        $data['breadcrumbs'] = [];

        // <a href="#" class="user-address" type="button" data-toggle="modal" data-target="#useraddress-popup">

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home/toHome'),
        ];

        if (isset($data['store'])) {
            $data['breadcrumbs'][] = [
                'text' => $data['store']['name'],
                'href' => $data['go_to_store'],
            ];
        }

        $data['category_id'] = '';
        $data['sub_category_id'] = '';

        $data['offers'] = $this->url->link('product/offers');

        if (false !== strpos($data['offers'], $_SERVER['REQUEST_URI'])) {
            $data['breadcrumbs'][] = [
                'text' => $data['text_offers'],
                'href' => $this->url->link('product/offers'),
            ];
        }

        if (isset($this->request->get['category'])) {
            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $path = '';

            $parts = explode('_', (string) $this->request->get['category']);
            $tempPart = $parts;
            $category_id = (int) array_pop($tempPart);

            if (count($parts) >= 2) {
                $data['category_id'] = $parts[0];
                $data['sub_category_id'] = $parts[1];
            } elseif (1 == count($parts)) {
                $data['category_id'] = $parts[0];
            }

            foreach ($parts as $path_id) {
                if (!$path) {
                    $path = (int) $path_id;
                } else {
                    $path .= '_' . (int) $path_id;
                }

                $category_info = $this->model_assets_category->getCategory($path_id);
                if ($category_info) {
                    $data['breadcrumbs'][] = [
                        'text' => $category_info['name'],
                        'href' => $this->url->link('product/category', 'category=' . $path . $url),
                    ];
                }
            }
        } else {
            $category_id = 0;
        }

        /* search breadcrumb start */

        if (isset($this->request->get['search'])) {
            $data['breadcrumbs'][] = [
                'text' => 'searched: ' . $this->request->get['search'],
                'href' => $this->url->link('product/search', 'search=' . $this->request->get['search']),
            ];
        }

        /* search breadcrumb end */

        $data['categories'] = [];

        //$categories = $this->model_assets_category->getCategoryByStore(0);
        $categories = $this->model_assets_category->getCategoryByStoreId(ACTIVE_STORE_ID, 0);
        //echo "<pre>";print_r($categories);die;
        foreach ($categories as $category) {
            // Level 2
            $children_data = [];

            $children = $this->model_assets_category->getCategories($category['category_id']);

            //echo "<pre>";print_r($children);die;
            foreach ($children as $child) {
                $children_data[] = [
                    'name' => $child['name'],
                    'id' => $child['category_id'],
                    'href' => $this->url->link('product/category', 'category=' . $category['category_id'] . '_' . $child['category_id']),
                ];
            }

            // Level 1
            $data['categories'][] = [
                'name' => $category['name'],
                'id' => $category['category_id'],
                'thumb' => $this->model_tool_image->resize($category['image'], 300, 300),
                'children' => $children_data,
                'column' => $category['column'] ? $category['column'] : 1,
                'href' => $this->url->link('product/category', 'category=' . $category['category_id']),
            ];
        }

        $data['language'] = $this->load->controller('common/language');
        $data['language'] = $this->load->controller('common/language/dropdown');
        $data['currency'] = $this->load->controller('common/currency');
        $data['search'] = $this->load->controller('common/search');
        $data['cart'] = $this->load->controller('common/cart');

        $data['reward'] = $this->url->link('account/reward', '', 'SSL');

        // For page specific css
        if (isset($this->request->get['path'])) {
            if (isset($this->request->get['product_id'])) {
                $class = '-' . $this->request->get['product_id'];
            } elseif (isset($this->request->get['category'])) {
                $class = '-' . $this->request->get['category'];
            } elseif (isset($this->request->get['manufacturer_id'])) {
                $class = '-' . $this->request->get['manufacturer_id'];
            } else {
                $class = '';
            }

            $data['class'] = str_replace('/', '-', $this->request->get['path']) . $class;
        } else {
            $data['class'] = 'common-home';
        }

        //get notice
        $data['notices'] = [];

        if (count($_COOKIE) > 0 && isset($_COOKIE['zipcode'])) {
            $this->load->model('assets/category');

            $data['zipcode'] = $_COOKIE['zipcode'];

            $link = $this->url->link('information/locations/stores');

            if (false !== strpos($link, '?')) {
                $data['link'] = $link . '&zipcode=' . $data['zipcode'];
            } else {
                $data['link'] = $link . '?zipcode=' . $data['zipcode'];
            }

            $rows = $this->model_assets_category->getNoticeData($data['zipcode']);
            foreach ($rows as $row) {
                $data['notices'][] = $row['notice'];
            }
        } elseif (count($_COOKIE) > 0 && isset($_COOKIE['location'])) {
            $data['zipcode'] = $this->getHeaderPlace($_COOKIE['location']);

            /* if(isset($_COOKIE['location_name'])) {
              $data['zipcode'] = $_COOKIE['location_name'];
              } */

            $data['zipcode_full'] = $data['zipcode'];
            $data['zipcode'] = strlen($data['zipcode']) > 20 ? substr($data['zipcode'], 0, 20) . '...' : $data['zipcode'];

            /* $addressTmp = $this->getZipcode($_COOKIE['location']);


              $data['zipcode'] = $addressTmp?$addressTmp:''; */

            $link = $this->url->link('information/locations/stores');

            if (false !== strpos($link, '?')) {
                $data['link'] = $link . '&zipcode=' . $data['zipcode'];
            } else {
                $data['link'] = $link . '?zipcode=' . $data['zipcode'];
            }

            $rows = $this->model_assets_category->getFullNoticeData($_COOKIE['location']);

            //echo "<pre>";print_r($rows);die;
            foreach ($rows as $row) {
                $data['notices'][] = $row['notice'];
            }
        } else {
            $data['zipcode'] = '';
        }
        $data['checkout_summary'] = $this->url->link('checkout/checkoutitems', '', 'SSL');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/common/header.tpl', $data);
        } else {
            return $this->load->view('default/template/common/header.tpl', $data);
        }
    }

    public function getMenuLink($parent, $child = null) {
        $item = empty($child) ? $parent : $child;

        switch ($item['menu_type']) {
            case 'category':
                $path = 'product/category';

                if (!empty($child)) {
                    $args = 'category=' . $parent['link'] . '_' . $item['link'];
                } else {
                    $args = 'category=' . $item['link'];
                }
                break;
            case 'product':
                $path = 'product/product';
                $args = 'product_id=' . $item['link'];
                break;
            case 'manufacturer':
                $path = 'product/manufacturer/info';
                $args = 'manufacturer_id=' . $item['link'];
                break;
            case 'information':
                $path = 'information/information';
                $args = 'information_id=' . $item['link'];
                break;
            default:
                $tmp = explode('&', str_replace('index.php?path=', '', $item['link']));

                if (!empty($tmp)) {
                    $path = $tmp[0];
                    unset($tmp[0]);
                    $args = (!empty($tmp)) ? implode('&', $tmp) : '';
                } else {
                    $path = $item['link'];
                    $args = '';
                }

                break;
        }

        $check = strpos($item['link'], 'http');
        if (false !== $check) {
            $link = $item['link'];
        } else {
            $link = $this->url->link($path, $args);
        }

        return $link;
    }

    public function checkFont() {
        $this->load->model('appearance/customizer');

        $data = $this->model_appearance_customizer->getDefaultData('customizer');

        if (!empty($data['font']) && 'inherit' != $data['font'] && 'Georgia, serif' != $data['font'] && 'Helvetica, sans-serif' != $data['font']) {
            $this->document->addStyle('//fonts.googleapis.com/css?family=' . str_replace(' ', '+', $data['font']), 'stylesheet', '');
        }
    }

    public function checkCustom() {
        if (is_file(DIR_APPLICATION . 'view/theme/' . $this->config->get('config_template') . '/stylesheet/custom.css')) {
            $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/custom.css');
        }

        if (is_file(DIR_APPLICATION . 'view/theme/' . $this->config->get('config_template') . '/javascript/custom.js')) {
            $this->document->addScript('front/ui/theme/' . $this->config->get('config_template') . '/javascript/custom.js');
        }
    }

    public function onlyHeader($param = null) {
        $this->load->language('common/header');
        $this->load->model('tool/image');
        $data['kondutoStatus'] = $this->config->get('config_konduto_status');

        $data['title'] = $this->document->getTitle();

        //echo "<pre>";print_r($data);die;
        if (isset($this->session->data['language'])) {
            $data['config_language'] = $this->session->data['language'];
        } else {
            $data['config_language'] = 'pt-BR';
        }

        if (!empty($this->request->get['path']) and ('common/home' != $this->request->get['path'])) {
            if ('pre' == $this->config->get('config_meta_title_add', 0)) {
                $data['title'] = $this->config->get('config_meta_title', '') . ' - ' . $data['title'];
            } elseif ('post' == $this->config->get('config_meta_title_add', 0)) {
                $data['title'] = $data['title'] . ' - ' . $this->config->get('config_meta_title', '');
            }
        }

        if ($this->config->get('config_meta_generator')) {
            $this->document->addMeta('generator', $this->config->get('config_meta_generator'));
        }

        if ($this->config->get('config_meta_googlekey')) {
            $this->document->addMeta('google-site-verification', $this->config->get('config_meta_googlekey'));
        }

        if ($this->config->get('config_meta_alexakey')) {
            $this->document->addMeta('alexaVerifyID', $this->config->get('config_meta_alexakey'));
        }

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        if (isset($this->session->data['error'])) {
            $data['error_warning'] = $this->session->data['error'];

            unset($this->session->data['error']);
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        //echo "<pre>";print_r($data['success']);die;
        /* if(!$server){
          $server = HTTP_SERVER;
          } */

        if (is_file(DIR_APPLICATION . 'view/theme/' . $this->config->get('config_template') . '/stylesheet/customizer.css')) {
            $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/customizer.css');
            $this->checkFont();
            $this->checkCustom();
        }
        //editk

        $user_telephone = $this->formatTelephone($this->customer->getTelephone());

        $is_login = $this->customer->isLogged();

        $data['notices'] = [];

        if (empty($user_telephone) && $is_login) {
            $account_link = $this->url->link('account/account', '&redirect=checkout');

            $data['text_profile_incomplete'] = $this->language->get('text_profile_incomplete');
            $data['text_profile_incomplete_msg1'] = $this->language->get('text_profile_incomplete_msg1');
            $data['text_profile_incomplete_msg2'] = $this->language->get('text_profile_incomplete_msg2');

            $data['notices'][] = $data['text_profile_incomplete'] . ' <a href=' . $account_link . ' style="color: #fff;text-decoration: underline;"> ' . $data['text_profile_incomplete_msg1'] . ' </a>' . $data['text_profile_incomplete_msg2'];
        }

        $this->document->setDescription($this->config->get('config_meta_description'));
        $this->document->setKeywords($this->config->get('config_meta_keyword'));

        $data['base'] = $server;
        $data['description'] = $this->document->getDescription();
        $data['keywords'] = $this->document->getKeywords();
        $data['metas'] = $this->document->getMetas();
        $data['links'] = $this->document->getLinks();
        $data['styles'] = $this->document->getStyles();
        $data['scripts'] = $this->document->getScripts();
        $data['lang'] = $this->language->get('code');
        $data['direction'] = $this->language->get('direction');

        $this->load->model('assets/category');

        if (isset($this->session->data['config_store_id'])) {
            $data['store'] = $this->model_assets_category->getStoreData($this->session->data['config_store_id']);
        } else {
            $data['store'] = [];
        }

        if ($this->config->get('config_google_analytics_status')) {
            $data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');
        } else {
            $data['google_analytics'] = '';
        }

        $data['name'] = $this->config->get('config_name');

        if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
            //$data['icon'] = $server . 'image/' . $this->config->get('config_icon');
            //24x30
            $data['icon'] = $this->model_tool_image->resize($this->config->get('config_icon'), 30, 30);
        } else {
            $data['icon'] = '';
        }

        if (is_file(DIR_IMAGE . $this->config->get('config_small_icon'))) {
            //$data['small_icon'] = $server . 'image/' . $this->config->get('config_small_icon');
            $data['small_icon'] = $this->model_tool_image->resize($this->config->get('config_small_icon'), 30, 30);
        } else {
            $data['small_icon'] = '';
        }

        if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
            $data['logo'] = $server . 'image/' . $this->config->get('config_logo');
            //$data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'),197,34);
        } else {
            $data['logo'] = '';
        }

        $data['is_login'] = $this->customer->isLogged();
        $data['name'] = $this->customer->getFirstName();

        $data['user_telephone'] = $this->formatTelephone($this->customer->getTelephone());

        $data['text_genuine_product'] = $this->language->get('text_genuine_product');
        $data['text_secure_payments'] = $this->language->get('text_secure_payments');
        $data['text_replacement_guarantee'] = $this->language->get('text_replacement_guarantee');

        $data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_home'] = $this->language->get('text_home');
        $data['text_wishlist'] = $this->language->get('text_wishlist');
        $data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', 'SSL'), $this->customer->getFirstName(), $this->url->link('account/logout', '', 'SSL'));

        $data['label_address'] = $this->language->get('label_address');

        $data['text_cash'] = $this->language->get('text_cash');
        $data['text_signout'] = $this->language->get('text_signout');
        $data['text_all_categories'] = $this->language->get('text_all_categories');
        $data['list_products'] = $this->language->get('list_products');
        $data['text_shopping_from'] = $this->language->get('text_shopping_from');
        $data['text_menu'] = $this->language->get('text_menu');
        $data['support'] = $this->language->get('support');
        $data['faq'] = $this->language->get('faq');
        $data['call'] = $this->language->get('call');
        $data['text'] = $this->language->get('text');
        $data['text_account'] = $this->language->get('text_account');
        $data['text_rewards'] = $this->language->get('text_rewards');
        $data['text_orders'] = $this->language->get('text_orders');
        $data['text_refer'] = $this->language->get('text_refer');
        $data['text_sign_out'] = $this->language->get('text_sign_out');
        $data['text_sign_in'] = $this->language->get('text_sign_in');
        $data['button_recipes'] = $this->language->get('button_recipes');

        $data['text_account'] = $this->language->get('text_account');
        $data['text_register'] = $this->language->get('text_register');
        $data['text_login'] = $this->language->get('text_login');
        $data['text_order'] = $this->language->get('text_order');
        $data['text_credit'] = $this->language->get('text_credit');
        $data['text_download'] = $this->language->get('text_download');
        $data['text_logout'] = $this->language->get('text_logout');
        $data['text_checkout'] = $this->language->get('text_checkout');
        $data['text_category'] = $this->language->get('text_category');
        $data['text_all'] = $this->language->get('text_all');

        $data['address'] = $this->url->link('account/address', '', 'SSL');
        $data['home'] = $this->url->link('common/home/toStore', '', 'SSL');
        $data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
        $data['logged'] = $this->customer->isLogged();
        $data['account'] = $this->url->link('account/account', '', 'SSL');
        $data['dashboard'] = $this->url->link('account/dashboard', '', 'SSL');
        $data['register'] = $this->url->link('account/register', '', 'SSL');
        $data['login'] = $this->url->link('account/login', '', 'SSL');
        $data['order'] = $this->url->link('account/order', '', 'SSL');
        $data['credit'] = $this->url->link('account/credit', '', 'SSL');
        $data['download'] = $this->url->link('account/download', '', 'SSL');
        $data['logout'] = $this->url->link('account/logout', '', 'SSL');
        $data['shopping_cart'] = $this->url->link('checkout/cart');
        $data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
        $data['contact'] = $this->url->link('information/contact');
        $data['telephone'] = $this->config->get('config_telephone');
        $data['help'] = $this->url->link('information/help');
        $data['refer'] = $this->url->link('account/refer', '', 'SSL');
        $data['reward'] = $this->url->link('account/reward', '', 'SSL');
        /* Added new params */
        $data['is_login'] = $this->customer->isLogged();
        $data['full_name'] = $this->customer->getFirstName();
        $data['text_my_cash'] = $this->language->get('text_my_cash');
        $data['text_my_wishlist'] = $this->language->get('text_my_wishlist');
        $data['label_my_address'] = $this->language->get('label_my_address');
        $data['contactus'] = $this->language->get('contactus');
        $data['text_cash'] = $this->language->get('text_cash');
        /*         * *Sigup / register /contact modals * */
        $data['contactus_modal'] = $this->load->controller('information/contact');
        $data['login_modal'] = $this->load->controller('common/login_modal');
        $data['signup_modal'] = $this->load->controller('common/signup_modal');
        $data['forget_modal'] = $this->load->controller('common/forget_modal');
        //$data['store'] = $this->url->link('product/store', '', 'SSL');

        /* $data['store'] = $this->url->link('product/store','store_id='.$this->session->data['config_store_id'].''); */

        if (isset($this->session->data['config_store_id'])) {
            $data['store'] = $this->url->link('product/store', 'store_id=' . $this->session->data['config_store_id'] . '');
        } else {
            $data['store'] = $this->url->link('product/store');
        }
        $status = true;

        if (isset($this->request->server['HTTP_USER_AGENT'])) {
            $robots = explode("\n", str_replace(["\r\n", "\r"], "\n", trim($this->config->get('config_robots'))));

            foreach ($robots as $robot) {
                if ($robot && false !== strpos($this->request->server['HTTP_USER_AGENT'], trim($robot))) {
                    $status = false;

                    break;
                }
            }
        }

        $data['cart'] = $this->load->controller('common/cart');

        // For page specific css
        if (isset($this->request->get['path'])) {
            if (isset($this->request->get['product_id'])) {
                $class = '-' . $this->request->get['product_id'];
            } elseif (isset($this->request->get['category'])) {
                $class = '-' . $this->request->get['category'];
            } elseif (isset($this->request->get['manufacturer_id'])) {
                $class = '-' . $this->request->get['manufacturer_id'];
            } else {
                $class = '';
            }

            $data['class'] = str_replace('/', '-', $this->request->get['path']) . $class;
        } else {
            $data['class'] = 'common-home';
        }

        $data['language'] = $this->load->controller('common/language/dropdown');
        if (isset($this->session->data['config_store_id'])) {
            $data['go_to_store'] = $this->url->link('product/store', 'store_id=' . $this->session->data['config_store_id'] . '');
        } else {
            $data['go_to_store'] = $this->url->link('product/store');
        }
        $data['checkout_summary'] = $this->url->link('checkout/checkoutitems', '', 'SSL');
        $data['multi_store_checkoutitems_css'] = $param;

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/only_header_information.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/common/only_header_information.tpl', $data);
        } else {
            return $this->load->view('default/template/common/only_header_information.tpl', $data);
        }
    }

    public function orderSummaryHeader() {
        $this->load->language('common/header');
        $this->load->model('tool/image');
        $data['kondutoStatus'] = $this->config->get('config_konduto_status');

        $data['title'] = $this->document->getTitle();

        //echo "<pre>";print_r($data);die;
        if (isset($this->session->data['language'])) {
            $data['config_language'] = $this->session->data['language'];
        } else {
            $data['config_language'] = 'pt-BR';
        }

        if (!empty($this->request->get['path']) and ('common/home' != $this->request->get['path'])) {
            if ('pre' == $this->config->get('config_meta_title_add', 0)) {
                $data['title'] = $this->config->get('config_meta_title', '') . ' - ' . $data['title'];
            } elseif ('post' == $this->config->get('config_meta_title_add', 0)) {
                $data['title'] = $data['title'] . ' - ' . $this->config->get('config_meta_title', '');
            }
        }

        if ($this->config->get('config_meta_generator')) {
            $this->document->addMeta('generator', $this->config->get('config_meta_generator'));
        }

        if ($this->config->get('config_meta_googlekey')) {
            $this->document->addMeta('google-site-verification', $this->config->get('config_meta_googlekey'));
        }

        if ($this->config->get('config_meta_alexakey')) {
            $this->document->addMeta('alexaVerifyID', $this->config->get('config_meta_alexakey'));
        }

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        if (isset($this->session->data['error'])) {
            $data['error_warning'] = $this->session->data['error'];

            unset($this->session->data['error']);
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        //echo "<pre>";print_r($data['success']);die;
        /* if(!$server){
          $server = HTTP_SERVER;
          } */

        if (is_file(DIR_APPLICATION . 'view/theme/' . $this->config->get('config_template') . '/stylesheet/customizer.css')) {
            $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/customizer.css');
            $this->checkFont();
            $this->checkCustom();
        }
        //editk

        $user_telephone = $this->formatTelephone($this->customer->getTelephone());

        $is_login = $this->customer->isLogged();

        $data['notices'] = [];

        if (empty($user_telephone) && $is_login) {
            $account_link = $this->url->link('account/account', '&redirect=checkout');

            $data['text_profile_incomplete'] = $this->language->get('text_profile_incomplete');
            $data['text_profile_incomplete_msg1'] = $this->language->get('text_profile_incomplete_msg1');
            $data['text_profile_incomplete_msg2'] = $this->language->get('text_profile_incomplete_msg2');

            $data['notices'][] = $data['text_profile_incomplete'] . ' <a href=' . $account_link . ' style="color: #fff;text-decoration: underline;"> ' . $data['text_profile_incomplete_msg1'] . ' </a>' . $data['text_profile_incomplete_msg2'];
        }

        $this->document->setDescription($this->config->get('config_meta_description'));
        $this->document->setKeywords($this->config->get('config_meta_keyword'));

        $data['base'] = $server;
        $data['description'] = $this->document->getDescription();
        $data['keywords'] = $this->document->getKeywords();
        $data['metas'] = $this->document->getMetas();
        $data['links'] = $this->document->getLinks();
        $data['styles'] = $this->document->getStyles();
        $data['scripts'] = $this->document->getScripts();
        $data['lang'] = $this->language->get('code');
        $data['direction'] = $this->language->get('direction');

        $this->load->model('assets/category');

        if (isset($this->session->data['config_store_id'])) {
            $data['store'] = $this->model_assets_category->getStoreData($this->session->data['config_store_id']);
        } else {
            $data['store'] = [];
        }

        if ($this->config->get('config_google_analytics_status')) {
            $data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');
        } else {
            $data['google_analytics'] = '';
        }

        $data['name'] = $this->config->get('config_name');

        if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
            //$data['icon'] = $server . 'image/' . $this->config->get('config_icon');
            //24x30
            $data['icon'] = $this->model_tool_image->resize($this->config->get('config_icon'), 30, 30);
        } else {
            $data['icon'] = '';
        }

        if (is_file(DIR_IMAGE . $this->config->get('config_small_icon'))) {
            //$data['small_icon'] = $server . 'image/' . $this->config->get('config_small_icon');
            $data['small_icon'] = $this->model_tool_image->resize($this->config->get('config_small_icon'), 30, 30);
        } else {
            $data['small_icon'] = '';
        }

        if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
            $data['logo'] = $server . 'image/' . $this->config->get('config_logo');
            //$data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'),197,34);
        } else {
            $data['logo'] = '';
        }

        $data['is_login'] = $this->customer->isLogged();
        $data['name'] = $this->customer->getFirstName();

        $data['user_telephone'] = $this->formatTelephone($this->customer->getTelephone());

        $data['text_genuine_product'] = $this->language->get('text_genuine_product');
        $data['text_secure_payments'] = $this->language->get('text_secure_payments');
        $data['text_replacement_guarantee'] = $this->language->get('text_replacement_guarantee');

        $data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_home'] = $this->language->get('text_home');
        $data['text_wishlist'] = $this->language->get('text_wishlist');
        $data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', 'SSL'), $this->customer->getFirstName(), $this->url->link('account/logout', '', 'SSL'));

        $data['label_address'] = $this->language->get('label_address');

        $data['text_cash'] = $this->language->get('text_cash');
        $data['text_signout'] = $this->language->get('text_signout');
        $data['text_all_categories'] = $this->language->get('text_all_categories');
        $data['list_products'] = $this->language->get('list_products');
        $data['text_shopping_from'] = $this->language->get('text_shopping_from');
        $data['text_menu'] = $this->language->get('text_menu');
        $data['support'] = $this->language->get('support');
        $data['faq'] = $this->language->get('faq');
        $data['call'] = $this->language->get('call');
        $data['text'] = $this->language->get('text');
        $data['text_account'] = $this->language->get('text_account');
        $data['text_rewards'] = $this->language->get('text_rewards');
        $data['text_orders'] = $this->language->get('text_orders');
        $data['text_refer'] = $this->language->get('text_refer');
        $data['text_sign_out'] = $this->language->get('text_sign_out');
        $data['text_sign_in'] = $this->language->get('text_sign_in');
        $data['button_recipes'] = $this->language->get('button_recipes');

        $data['text_account'] = $this->language->get('text_account');
        $data['text_register'] = $this->language->get('text_register');
        $data['text_login'] = $this->language->get('text_login');
        $data['text_order'] = $this->language->get('text_order');
        $data['text_credit'] = $this->language->get('text_credit');
        $data['text_download'] = $this->language->get('text_download');
        $data['text_logout'] = $this->language->get('text_logout');
        $data['text_checkout'] = $this->language->get('text_checkout');
        $data['text_category'] = $this->language->get('text_category');
        $data['text_all'] = $this->language->get('text_all');

        $data['address'] = $this->url->link('account/address', '', 'SSL');
        $data['home'] = $this->url->link('common/home/toStore', '', 'SSL');
        $data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
        $data['logged'] = $this->customer->isLogged();
        $data['account'] = $this->url->link('account/account', '', 'SSL');
        $data['dashboard'] = $this->url->link('account/dashboard', '', 'SSL');

        $data['register'] = $this->url->link('account/register', '', 'SSL');
        $data['login'] = $this->url->link('account/login', '', 'SSL');
        $data['order'] = $this->url->link('account/order', '', 'SSL');
        $data['credit'] = $this->url->link('account/credit', '', 'SSL');
        $data['download'] = $this->url->link('account/download', '', 'SSL');
        $data['logout'] = $this->url->link('account/logout', '', 'SSL');
        $data['shopping_cart'] = $this->url->link('checkout/cart');
        $data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
        $data['contact'] = $this->url->link('information/contact');
        $data['telephone'] = $this->config->get('config_telephone');
        $data['help'] = $this->url->link('information/help');
        $data['refer'] = $this->url->link('account/refer', '', 'SSL');
        $data['reward'] = $this->url->link('account/reward', '', 'SSL');
        /* Added new params */
        $data['is_login'] = $this->customer->isLogged();
        $data['full_name'] = $this->customer->getFirstName();
        $data['text_my_cash'] = $this->language->get('text_my_cash');
        $data['text_my_wishlist'] = $this->language->get('text_my_wishlist');
        $data['label_my_address'] = $this->language->get('label_my_address');
        $data['contactus'] = $this->language->get('contactus');
        $data['text_cash'] = $this->language->get('text_cash');
        /*         * *Sigup / register /contact modals * */
        $data['contactus_modal'] = $this->load->controller('information/contact');
        $data['login_modal'] = $this->load->controller('common/login_modal');
        $data['signup_modal'] = $this->load->controller('common/signup_modal');
        $data['forget_modal'] = $this->load->controller('common/forget_modal');
        //$data['store'] = $this->url->link('product/store', '', 'SSL');

        /* $data['store'] = $this->url->link('product/store','store_id='.$this->session->data['config_store_id'].''); */

        if (isset($this->session->data['config_store_id'])) {
            $data['store'] = $this->url->link('product/store', 'store_id=' . $this->session->data['config_store_id'] . '');
        } else {
            $data['store'] = $this->url->link('product/store');
        }
        $status = true;

        if (isset($this->request->server['HTTP_USER_AGENT'])) {
            $robots = explode("\n", str_replace(["\r\n", "\r"], "\n", trim($this->config->get('config_robots'))));

            foreach ($robots as $robot) {
                if ($robot && false !== strpos($this->request->server['HTTP_USER_AGENT'], trim($robot))) {
                    $status = false;

                    break;
                }
            }
        }

        $data['cart'] = $this->load->controller('common/cart');

        // For page specific css
        if (isset($this->request->get['path'])) {
            if (isset($this->request->get['product_id'])) {
                $class = '-' . $this->request->get['product_id'];
            } elseif (isset($this->request->get['category'])) {
                $class = '-' . $this->request->get['category'];
            } elseif (isset($this->request->get['manufacturer_id'])) {
                $class = '-' . $this->request->get['manufacturer_id'];
            } else {
                $class = '';
            }

            $data['class'] = str_replace('/', '-', $this->request->get['path']) . $class;
        } else {
            $data['class'] = 'common-home';
        }

        $data['language'] = $this->load->controller('common/language/dropdown');
        if (isset($this->session->data['config_store_id'])) {
            $data['go_to_store'] = $this->url->link('product/store', 'store_id=' . $this->session->data['config_store_id'] . '');
        } else {
            $data['go_to_store'] = $this->url->link('product/store');
        }
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/order_summary_header.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/common/order_summary_header.tpl', $data);
        } else {
            return $this->load->view('default/template/common/only_header_information.tpl', $data);
        }
    }

    public function orderSummaryHeaders() {
        $this->load->language('common/header');
        $this->load->model('tool/image');
        $data['kondutoStatus'] = $this->config->get('config_konduto_status');

        $data['title'] = $this->document->getTitle();

        //echo "<pre>";print_r($data);die;
        if (isset($this->session->data['language'])) {
            $data['config_language'] = $this->session->data['language'];
        } else {
            $data['config_language'] = 'pt-BR';
        }

        if (!empty($this->request->get['path']) and ('common/home' != $this->request->get['path'])) {
            if ('pre' == $this->config->get('config_meta_title_add', 0)) {
                $data['title'] = $this->config->get('config_meta_title', '') . ' - ' . $data['title'];
            } elseif ('post' == $this->config->get('config_meta_title_add', 0)) {
                $data['title'] = $data['title'] . ' - ' . $this->config->get('config_meta_title', '');
            }
        }

        if ($this->config->get('config_meta_generator')) {
            $this->document->addMeta('generator', $this->config->get('config_meta_generator'));
        }

        if ($this->config->get('config_meta_googlekey')) {
            $this->document->addMeta('google-site-verification', $this->config->get('config_meta_googlekey'));
        }

        if ($this->config->get('config_meta_alexakey')) {
            $this->document->addMeta('alexaVerifyID', $this->config->get('config_meta_alexakey'));
        }

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        if (isset($this->session->data['error'])) {
            $data['error_warning'] = $this->session->data['error'];

            unset($this->session->data['error']);
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        //echo "<pre>";print_r($data['success']);die;
        /* if(!$server){
          $server = HTTP_SERVER;
          } */

        if (is_file(DIR_APPLICATION . 'view/theme/' . $this->config->get('config_template') . '/stylesheet/customizer.css')) {
            $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/customizer.css');
            $this->checkFont();
            $this->checkCustom();
        }
        //editk

        $user_telephone = $this->formatTelephone($this->customer->getTelephone());

        $is_login = $this->customer->isLogged();

        $data['notices'] = [];

        if (empty($user_telephone) && $is_login) {
            $account_link = $this->url->link('account/account', '&redirect=checkout');

            $data['text_profile_incomplete'] = $this->language->get('text_profile_incomplete');
            $data['text_profile_incomplete_msg1'] = $this->language->get('text_profile_incomplete_msg1');
            $data['text_profile_incomplete_msg2'] = $this->language->get('text_profile_incomplete_msg2');

            $data['notices'][] = $data['text_profile_incomplete'] . ' <a href=' . $account_link . ' style="color: #fff;text-decoration: underline;"> ' . $data['text_profile_incomplete_msg1'] . ' </a>' . $data['text_profile_incomplete_msg2'];
        }

        $this->document->setDescription($this->config->get('config_meta_description'));
        $this->document->setKeywords($this->config->get('config_meta_keyword'));

        $data['base'] = $server;
        $data['description'] = $this->document->getDescription();
        $data['keywords'] = $this->document->getKeywords();
        $data['metas'] = $this->document->getMetas();
        $data['links'] = $this->document->getLinks();
        $data['styles'] = $this->document->getStyles();
        $data['scripts'] = $this->document->getScripts();
        $data['lang'] = $this->language->get('code');
        $data['direction'] = $this->language->get('direction');

        $this->load->model('assets/category');

        if (isset($this->session->data['config_store_id'])) {
            $data['store'] = $this->model_assets_category->getStoreData($this->session->data['config_store_id']);
        } else {
            $data['store'] = [];
        }

        if ($this->config->get('config_google_analytics_status')) {
            $data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');
        } else {
            $data['google_analytics'] = '';
        }

        $data['name'] = $this->config->get('config_name');

        if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
            //$data['icon'] = $server . 'image/' . $this->config->get('config_icon');
            //24x30
            $data['icon'] = $this->model_tool_image->resize($this->config->get('config_icon'), 30, 30);
        } else {
            $data['icon'] = '';
        }

        if (is_file(DIR_IMAGE . $this->config->get('config_small_icon'))) {
            //$data['small_icon'] = $server . 'image/' . $this->config->get('config_small_icon');
            $data['small_icon'] = $this->model_tool_image->resize($this->config->get('config_small_icon'), 30, 30);
        } else {
            $data['small_icon'] = '';
        }

        if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
            $data['logo'] = $server . 'image/' . $this->config->get('config_logo');
            //$data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'),197,34);
        } else {
            $data['logo'] = '';
        }

        $data['is_login'] = $this->customer->isLogged();
        $data['name'] = $this->customer->getFirstName();

        $data['user_telephone'] = $this->formatTelephone($this->customer->getTelephone());

        $data['text_genuine_product'] = $this->language->get('text_genuine_product');
        $data['text_secure_payments'] = $this->language->get('text_secure_payments');
        $data['text_replacement_guarantee'] = $this->language->get('text_replacement_guarantee');

        $data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_home'] = $this->language->get('text_home');
        $data['text_wishlist'] = $this->language->get('text_wishlist');
        $data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', 'SSL'), $this->customer->getFirstName(), $this->url->link('account/logout', '', 'SSL'));

        $data['label_address'] = $this->language->get('label_address');

        $data['text_cash'] = $this->language->get('text_cash');
        $data['text_signout'] = $this->language->get('text_signout');
        $data['text_all_categories'] = $this->language->get('text_all_categories');
        $data['list_products'] = $this->language->get('list_products');
        $data['text_shopping_from'] = $this->language->get('text_shopping_from');
        $data['text_menu'] = $this->language->get('text_menu');
        $data['support'] = $this->language->get('support');
        $data['faq'] = $this->language->get('faq');
        $data['call'] = $this->language->get('call');
        $data['text'] = $this->language->get('text');
        $data['text_account'] = $this->language->get('text_account');
        $data['text_rewards'] = $this->language->get('text_rewards');
        $data['text_orders'] = $this->language->get('text_orders');
        $data['text_refer'] = $this->language->get('text_refer');
        $data['text_sign_out'] = $this->language->get('text_sign_out');
        $data['text_sign_in'] = $this->language->get('text_sign_in');
        $data['button_recipes'] = $this->language->get('button_recipes');

        $data['text_account'] = $this->language->get('text_account');
        $data['text_register'] = $this->language->get('text_register');
        $data['text_login'] = $this->language->get('text_login');
        $data['text_order'] = $this->language->get('text_order');
        $data['text_credit'] = $this->language->get('text_credit');
        $data['text_download'] = $this->language->get('text_download');
        $data['text_logout'] = $this->language->get('text_logout');
        $data['text_checkout'] = $this->language->get('text_checkout');
        $data['text_category'] = $this->language->get('text_category');
        $data['text_all'] = $this->language->get('text_all');

        $data['address'] = $this->url->link('account/address', '', 'SSL');
        $data['home'] = $this->url->link('common/home/toStore', '', 'SSL');
        $data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
        $data['logged'] = $this->customer->isLogged();
        $data['account'] = $this->url->link('account/account', '', 'SSL');
        $data['dashboard'] = $this->url->link('account/dashboard', '', 'SSL');

        $data['register'] = $this->url->link('account/register', '', 'SSL');
        $data['login'] = $this->url->link('account/login', '', 'SSL');
        $data['order'] = $this->url->link('account/order', '', 'SSL');
        $data['credit'] = $this->url->link('account/credit', '', 'SSL');
        $data['download'] = $this->url->link('account/download', '', 'SSL');
        $data['logout'] = $this->url->link('account/logout', '', 'SSL');
        $data['shopping_cart'] = $this->url->link('checkout/cart');
        $data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
        $data['contact'] = $this->url->link('information/contact');
        $data['telephone'] = $this->config->get('config_telephone');
        $data['help'] = $this->url->link('information/help');
        $data['refer'] = $this->url->link('account/refer', '', 'SSL');
        $data['reward'] = $this->url->link('account/reward', '', 'SSL');
        /* Added new params */
        $data['is_login'] = $this->customer->isLogged();
        $data['full_name'] = $this->customer->getFirstName();
        $data['text_my_cash'] = $this->language->get('text_my_cash');
        $data['text_my_wishlist'] = $this->language->get('text_my_wishlist');
        $data['label_my_address'] = $this->language->get('label_my_address');
        $data['contactus'] = $this->language->get('contactus');
        $data['text_cash'] = $this->language->get('text_cash');
        /*         * *Sigup / register /contact modals * */
        $data['contactus_modal'] = $this->load->controller('information/contact');
        $data['login_modal'] = $this->load->controller('common/login_modal');
        $data['signup_modal'] = $this->load->controller('common/signup_modal');
        $data['forget_modal'] = $this->load->controller('common/forget_modal');
        //$data['store'] = $this->url->link('product/store', '', 'SSL');

        /* $data['store'] = $this->url->link('product/store','store_id='.$this->session->data['config_store_id'].''); */

        if (isset($this->session->data['config_store_id'])) {
            $data['store'] = $this->url->link('product/store', 'store_id=' . $this->session->data['config_store_id'] . '');
        } else {
            $data['store'] = $this->url->link('product/store');
        }
        $status = true;

        if (isset($this->request->server['HTTP_USER_AGENT'])) {
            $robots = explode("\n", str_replace(["\r\n", "\r"], "\n", trim($this->config->get('config_robots'))));

            foreach ($robots as $robot) {
                if ($robot && false !== strpos($this->request->server['HTTP_USER_AGENT'], trim($robot))) {
                    $status = false;

                    break;
                }
            }
        }

        $data['cart'] = $this->load->controller('common/cart');

        // For page specific css
        if (isset($this->request->get['path'])) {
            if (isset($this->request->get['product_id'])) {
                $class = '-' . $this->request->get['product_id'];
            } elseif (isset($this->request->get['category'])) {
                $class = '-' . $this->request->get['category'];
            } elseif (isset($this->request->get['manufacturer_id'])) {
                $class = '-' . $this->request->get['manufacturer_id'];
            } else {
                $class = '';
            }

            $data['class'] = str_replace('/', '-', $this->request->get['path']) . $class;
        } else {
            $data['class'] = 'common-home';
        }

        $this->load->model('assets/category');
        $categories = $this->model_assets_category->getCategoryByStoreId(ACTIVE_STORE_ID, 0);
        $data['categories'] = $categories;

        $data['language'] = $this->load->controller('common/language/dropdown');
        if (isset($this->session->data['config_store_id'])) {
            $data['go_to_store'] = $this->url->link('product/store', 'store_id=' . $this->session->data['config_store_id'] . '');
        } else {
            $data['go_to_store'] = $this->url->link('product/store');
        }
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/order_summary_headers.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/common/order_summary_headers.tpl', $data);
        } else {
            return $this->load->view('default/template/common/only_header_information.tpl', $data);
        }
    }

    public function storeHeader() {
        if (isset($this->session->data['language'])) {
            $data['config_language'] = $this->session->data['language'];
        } else {
            $data['config_language'] = 'pt-BR';
        }

        if ($this->config->get('config_meta_generator')) {
            $this->document->addMeta('generator', $this->config->get('config_meta_generator'));
        }

        if ($this->config->get('config_meta_googlekey')) {
            $this->document->addMeta('google-site-verification', $this->config->get('config_meta_googlekey'));
        }

        if ($this->config->get('config_meta_alexakey')) {
            $this->document->addMeta('alexaVerifyID', $this->config->get('config_meta_alexakey'));
        }

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['base'] = $server;

        //echo "<pre>";print_r($server);die;
        /* if(!$server){
          $server = HTTP_SERVER;
          } */

        if (is_file(DIR_APPLICATION . 'view/theme/' . $this->config->get('config_template') . '/stylesheet/customizer.css')) {
            $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/customizer.css');
            $this->checkFont();
            $this->checkCustom();
        }

        $this->document->setDescription($this->config->get('config_meta_description'));
        $this->document->setKeywords($this->config->get('config_meta_keyword'));

        $data['description'] = $this->document->getDescription();
        $data['keywords'] = $this->document->getKeywords();
        $data['metas'] = $this->document->getMetas();
        $data['links'] = $this->document->getLinks();
        $data['styles'] = $this->document->getStyles();
        $data['scripts'] = $this->document->getScripts();
        $data['lang'] = $this->language->get('code');
        $data['direction'] = $this->language->get('direction');

        $data['action'] = $this->url->link('common/home/toHome');

        if (isset($this->request->get['filter'])) {
            $filter = urldecode($this->request->get['filter']);
        } else {
            $filter = '';
        }

        $data['filter'] = $filter;

        $this->load->model('assets/category');
        $this->load->model('assets/product');

        $data['language'] = $this->load->controller('common/language/dropdown');

        if (isset($this->session->data['config_store_id'])) {
            $data['store'] = $this->model_assets_category->getStoreData($this->session->data['config_store_id']);
        } else {
            $data['store'] = [];
        }

        if ($this->config->get('config_google_analytics_status')) {
            $data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');
        } else {
            $data['google_analytics'] = '';
        }

        $data['name'] = $this->config->get('config_name');

        if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
            //$data['icon'] = $server . 'image/' . $this->config->get('config_icon');
            //24x30
            $data['icon'] = $this->model_tool_image->resize($this->config->get('config_icon'), 30, 30);
        } else {
            $data['icon'] = '';
        }

        if (is_file(DIR_IMAGE . $this->config->get('config_small_icon'))) {
            //$data['small_icon'] = $server . 'image/' . $this->config->get('config_small_icon');
            $data['small_icon'] = $this->model_tool_image->resize($this->config->get('config_small_icon'), 30, 30);
        } else {
            $data['small_icon'] = '';
        }

        if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
            $data['logo'] = $server . 'image/' . $this->config->get('config_logo');
            //$data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'),198,34);
        } else {
            $data['logo'] = '';
        }

        $data['is_login'] = $this->customer->isLogged();
        $data['name'] = $this->customer->getFirstName();
        $data['f_name'] = $this->customer->getFirstName();
        $data['l_name'] = $this->customer->getLastName();
        $data['full_name'] = $data['f_name']; //.' '.$data['l_name'];
        $this->load->language('common/header');

        $data['user_telephone'] = $this->formatTelephone($this->customer->getTelephone());
        $data['text_my_cash'] = $this->language->get('text_my_cash');
        $data['text_my_wishlist'] = $this->language->get('text_my_wishlist');
        $data['label_my_address'] = $this->language->get('label_my_address');
        $data['text_my_profile'] = $this->language->get('text_my_profile');
        $data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
        $data['contactus'] = $this->language->get('contactus');
        $data['text_logout'] = $this->language->get('text_logout');
        $data['button_clear_cart'] = $this->language->get('button_clear_cart');
        $data['text_cash'] = $this->language->get('text_cash');

        $data['text_home'] = $this->language->get('text_home');
        $data['text_wishlist'] = $this->language->get('text_wishlist');
        $data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', 'SSL'), $this->customer->getFirstName(), $this->url->link('account/logout', '', 'SSL'));

        $data['label_address'] = $this->language->get('label_address');

        $data['text_signout'] = $this->language->get('text_signout');
        $data['text_all_categories'] = $this->language->get('text_all_categories');
        $data['list_products'] = $this->language->get('list_products');
        $data['text_shopping_from'] = $this->language->get('text_shopping_from');
        $data['text_menu'] = $this->language->get('text_menu');
        $data['support'] = $this->language->get('support');
        $data['faq'] = $this->language->get('faq');
        $data['call'] = $this->language->get('call');
        $data['text'] = $this->language->get('text');
        $data['text_account'] = $this->language->get('text_account');
        $data['text_rewards'] = $this->language->get('text_rewards');
        $data['text_orders'] = $this->language->get('text_orders');
        $data['text_refer'] = $this->language->get('text_refer');
        $data['text_sign_out'] = $this->language->get('text_sign_out');
        $data['text_sign_in'] = $this->language->get('text_sign_in');
        $data['button_recipes'] = $this->language->get('button_recipes');
        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_search_store'] = $this->language->get('text_search_store');

        $data['text_account'] = $this->language->get('text_account');
        $data['text_register'] = $this->language->get('text_register');
        $data['text_login'] = $this->language->get('text_login');
        $data['text_order'] = $this->language->get('text_order');
        $data['text_credit'] = $this->language->get('text_credit');
        $data['text_download'] = $this->language->get('text_download');

        $data['text_checkout'] = $this->language->get('text_checkout');
        $data['text_category'] = $this->language->get('text_category');
        $data['text_all'] = $this->language->get('text_all');

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['address'] = $this->url->link('account/address', '', 'SSL');
        $data['home'] = $server;
        $data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
        $data['logged'] = $this->customer->isLogged();
        $data['account'] = $this->url->link('account/account', '', 'SSL');
        $data['dashboard'] = $this->url->link('account/dashboard', '', 'SSL');

        $data['register'] = $this->url->link('account/register', '', 'SSL');
        $data['login'] = $this->url->link('account/login', '', 'SSL');
        $data['order'] = $this->url->link('account/order', '', 'SSL');
        $data['credit'] = $this->url->link('account/credit', '', 'SSL');
        $data['download'] = $this->url->link('account/download', '', 'SSL');
        $data['logout'] = $this->url->link('account/logout', '', 'SSL');
        $data['shopping_cart'] = $this->url->link('checkout/cart');
        $data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
        $data['contact'] = $this->url->link('information/contact');
        $data['telephone'] = $this->config->get('config_telephone');
        $data['help'] = $this->url->link('information/help');
        $data['refer'] = $this->url->link('account/refer', '', 'SSL');
        $data['reward'] = $this->url->link('account/reward', '', 'SSL');

        $status = true;

        $data['notices'] = [];

        //echo "<pre>";print_r($_COOKIE);die;

        if (count($_COOKIE) > 0 && isset($_COOKIE['zipcode'])) {
            $this->load->model('assets/category');

            $data['zipcode'] = $_COOKIE['zipcode'];

            $link = $this->url->link('information/locations/stores');

            if (false !== strpos($link, '?')) {
                $data['link'] = $link . '&zipcode=' . $data['zipcode'];
            } else {
                $data['link'] = $link . '?zipcode=' . $data['zipcode'];
            }

            $rows = $this->model_assets_category->getNoticeData($data['zipcode']);
            foreach ($rows as $row) {
                $data['notices'][] = $row['notice'];
            }
        } elseif (count($_COOKIE) > 0 && isset($_COOKIE['location'])) {
            $data['zipcode'] = $this->getHeaderPlace($_COOKIE['location']);

            /* if(isset($_COOKIE['location_name'])) {
              $data['zipcode'] = $_COOKIE['location_name'];
              } */

            $data['zipcode_full'] = $data['zipcode'];
            $data['zipcode'] = strlen($data['zipcode']) > 20 ? substr($data['zipcode'], 0, 20) . '...' : $data['zipcode'];
            /* $addressTmp = $this->getZipcode($_COOKIE['location']);

              $data['zipcode'] = $addressTmp?$addressTmp:''; */

            $link = $this->url->link('information/locations/stores');

            if (false !== strpos($link, '?')) {
                $data['link'] = $link . '&location=' . $_COOKIE['location'];
            } else {
                $data['link'] = $link . '?location=' . $_COOKIE['location'];
            }

            $rows = $this->model_assets_category->getFullNoticeData($_COOKIE['location']);
            foreach ($rows as $row) {
                $data['notices'][] = $row['notice'];
            }
        } else {
            $data['zipcode'] = '';
        }

        $data['title'] = 'Stores in ' . $data['zipcode_full'] . ' '; //$this->document->getTitle();
        //$data['title'] = '';

        $this->load->model('tool/image');
        $data['kondutoStatus'] = $this->config->get('config_konduto_status');
        if (!empty($this->request->get['path']) and ('common/home' != $this->request->get['path'])) {
            if ('pre' == $this->config->get('config_meta_title_add', 0)) {
                $data['title'] = $this->config->get('config_meta_title', '') . ' - ' . $data['title'];
            } elseif ('post' == $this->config->get('config_meta_title_add', 0)) {
                $data['title'] = $data['title'] . ' - ' . $this->config->get('config_meta_title', '');
            }
        }

        //echo "<pre>";print_r($data['title']);die;

        if (isset($this->request->server['HTTP_USER_AGENT'])) {
            $robots = explode("\n", str_replace(["\r\n", "\r"], "\n", trim($this->config->get('config_robots'))));

            foreach ($robots as $robot) {
                if ($robot && false !== strpos($this->request->server['HTTP_USER_AGENT'], trim($robot))) {
                    $status = false;

                    break;
                }
            }
        }

        $data['cart'] = $this->load->controller('common/cart');

        // For page specific css
        if (isset($this->request->get['path'])) {
            if (isset($this->request->get['product_id'])) {
                $class = '-' . $this->request->get['product_id'];
            } elseif (isset($this->request->get['category'])) {
                $class = '-' . $this->request->get['category'];
            } elseif (isset($this->request->get['manufacturer_id'])) {
                $class = '-' . $this->request->get['manufacturer_id'];
            } else {
                $class = '';
            }

            $data['class'] = str_replace('/', '-', $this->request->get['path']) . $class;
        } else {
            $data['class'] = 'common-home';
        }

        //$data['go_to_store'] = $this->url->link('product/store', '', 'SSL');
        //$data['go_to_store'] = $this->url->link('product/store','store_id='.$this->session->data['config_store_id'].'');

        if (isset($this->session->data['config_store_id'])) {
            //$data['go_to_store'] = $this->url->link('product/store','store_id='.$this->session->data['config_store_id'].'');
            $data['go_to_store'] = $this->url->link('product/store', 'store_id=' . ACTIVE_STORE_ID . '');
        } else {
            $data['go_to_store'] = $this->url->link('product/store');
        }

        $data['categories'] = [];

        $categories = $this->model_assets_category->getCategoriesByStoreId(ACTIVE_STORE_ID);

        //echo "<pre>";print_r($categories);die;
        foreach ($categories as $category) {
            // Level 2
            $children_data = [];

            $children = $this->model_assets_category->getCategories($category['category_id']);

            //echo "<pre>";print_r($children);die;
            foreach ($children as $child) {
                $children_data[] = [
                    'name' => $child['name'],
                    'id' => $child['category_id'],
                    'href' => $this->url->link('product/category', 'category=' . $category['category_id'] . '_' . $child['category_id']),
                ];
            }

            // Level 1
            $data['categories'][] = [
                'name' => $category['name'],
                'id' => $category['category_id'],
                'thumb' => $this->model_tool_image->resize($category['image'], 300, 300),
                'children' => $children_data,
                'column' => $category['column'] ? $category['column'] : 1,
                'href' => $this->url->link('product/category', 'category=' . $category['category_id']),
            ];
        }

        $products = $this->model_assets_product->getProductDataByStoreId(2);
        $data['latest_products'] = $products;

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/store_header.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/common/store_header.tpl', $data);
        } else {
            return $this->load->view('default/template/common/store_header.tpl', $data);
        }
    }

    public function formatTelephone($telephone) {
        /* if(strlen($telephone) == 11 ) {
          //(21) 42353-5255

          $str1 = '(';
          $str3 = ')';
          $str4 = ' ';
          $str6 = '-';

          $str  = $telephone;
          $str2 = substr($str,0,2);
          $str5 = substr($str,2,5);
          $str7 = substr($str,7,4);


          return  $str1.$str2.$str3.$str4.$str5.$str6.$str7;
          } else {
          return $telephone;
          } */
        return $telephone;
    }

    public function getZipcode($address) {
        if (!empty($address)) {
            //Formatted address
            $formattedAddr = str_replace(' ', '+', $address);
            //Send request and receive json data by address

            $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $formattedAddr . '&sensor=false&key=' . $this->config->get('config_google_server_api_key');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);

            $headers = [
                'Cache-Control: no-cache',
            ];
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //curl_setopt($ch, CURLOPT_REFERER, '52.178.112.211');

            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_WHATEVER);

            $response = curl_exec($ch);
            curl_close($ch);
            $output1 = json_decode($response);

            //Get latitude and longitute from json data
            $latitude = $output1->results[0]->geometry->location->lat;
            $longitude = $output1->results[0]->geometry->location->lng;

            $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $latitude . ',' . $longitude . '&sensor=false&key=' . $this->config->get('config_google_server_api_key');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);

            $headers = [
                'Cache-Control: no-cache',
            ];
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //curl_setopt($ch, CURLOPT_REFERER, '52.178.112.211');

            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_WHATEVER);

            $response = curl_exec($ch);
            curl_close($ch);
            $output2 = json_decode($response);

            if (!empty($output2)) {
                $addressComponents = $output2->results[0]->address_components;
                foreach ($addressComponents as $addrComp) {
                    if ('postal_code' == $addrComp->types[0]) {
                        //Return the zipcode
                        return $addrComp->long_name;
                    }
                }

                return false;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getPlace($location) {
        $p = '';

        $userSearch = explode(',', $location);

        if (count($userSearch) >= 2) {
            $validateLat = is_numeric($userSearch[0]);
            $validateLat2 = is_numeric($userSearch[1]);

            $validateLat3 = strpos($userSearch[0], '.');
            $validateLat4 = strpos($userSearch[1], '.');

            if ($validateLat && $validateLat2 && $validateLat3 && $validateLat4) {
                $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $location . '&sensor=false&key=' . $this->config->get('config_google_server_api_key');

                //echo "<pre>";print_r($url);die;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                $headers = [
                    'Cache-Control: no-cache',
                ];
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                //curl_setopt($ch, CURLOPT_REFERER, '52.178.112.211');

                curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_WHATEVER);

                $response = curl_exec($ch);

                //echo "<pre>";print_r($response);die;

                curl_close($ch);
                $output = json_decode($response);

                //print_r($output);die;

                if (isset($output)) {
                    $p = $output->results[0]->formatted_address;
                }
            }
        }

        return $p;
    }

    public function getHeaderPlace($location) {
        if (isset($_COOKIE['location_name']) && !empty($_COOKIE['location_name'])) {
            $p = $_COOKIE['location_name'];
        } else {
            $p = '';

            $userSearch = explode(',', $location);

            if (count($userSearch) >= 2) {
                $validateLat = is_numeric($userSearch[0]);
                $validateLat2 = is_numeric($userSearch[1]);

                $validateLat3 = strpos($userSearch[0], '.');
                $validateLat4 = strpos($userSearch[1], '.');

                if ($validateLat && $validateLat2 && $validateLat3 && $validateLat4) {
                    //echo "<pre>";print_r("er");die;
                    try {
                        $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . urlencode($location) . '&sensor=false&key=' . $this->config->get('config_google_server_api_key');

                        //echo "<pre>";print_r($url);
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        $headers = [
                            'Cache-Control: no-cache',
                        ];
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);

                        //curl_setopt($ch, CURLOPT_REFERER, '52.178.112.211');
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_WHATEVER);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                        $response = curl_exec($ch);
                        curl_close($ch);

                        $output = json_decode($response);

                        //echo "<pre>";print_r($output);die;
                        if (isset($output)) {
                            foreach ($output->results[0]->address_components as $addres) {
                                if (isset($addres->types)) {
                                    if (in_array('sublocality_level_1', $addres->types)) {
                                        //echo "<pre>";print_r($addres);die;
                                        $p = $addres->long_name;
                                        break;
                                    }
                                }
                            }
                            if (isset($output->results[0]->formatted_address)) {
                                $p = $output->results[0]->formatted_address;
                            }

                            $_COOKIE['location_name'] = $p;
                            setcookie('location_name', $p, time() + (86400 * 30 * 30 * 30 * 3), '/');
                        }
                    } catch (Exception $e) {
                        
                    }
                }
            }
        }

        return $p;
    }

    public function multi_store_checkoutitems_css() {
        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }
        $data['base'] = $server;
        return $this->load->view($this->config->get('config_template') . '/template/common/multi_store_checkoutitems_css.tpl', $data);
    }

}
