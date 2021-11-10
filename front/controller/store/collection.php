<?php

class ControllerStoreCollection extends Controller {

    public function start() {
        $this->session->data['config_store_id'] = $this->request->post['store_id'];

        $json['status'] = 1;
        //$json['location'] =  $this->url->link('product/store');

        $json['location'] = $this->url->link('product/store', 'store_id=' . $this->session->data['config_store_id'] . '');

        echo json_encode($json);
    }

    public function getFacebookRedirectUrl() {
        $url = null;
        if (isset($this->request->get['category'])) {
            $url = $this->request->get['category'];
        }

        if (isset($this->request->get['redirect_url'])) {
            if (false !== ($pos = strpos($this->request->get['redirect_url'], 'path='))) {
                $redirectPath = substr($this->request->get['redirect_url'], $pos + 5);
                if ($url) {
                    $redirectPath .= '&category=' . $url;
                }
                $this->session->data['redirect'] = $this->url->link($redirectPath);
            } else {
                $this->session->data['redirect'] = $this->request->get['redirect_url'];
            }
        }

        require DIR_SYSTEM . 'vendor/Facebook/autoload.php';

        $fb = new Facebook\Facebook([
            'app_id' => !empty($this->config->get('config_fb_app_id')) ? $this->config->get('config_fb_app_id') : 'randomstringforappid',
            'app_secret' => !empty($this->config->get('config_fb_secret')) ? $this->config->get('config_fb_secret') : 'randomstringforappsecret',
            'default_graph_version' => 'v2.5',
                //'default_access_token' => $this->request->get['code']//'5ce6c3df96acc19c6215f2ac62d3480e', // optional
        ]);

        $helper = $fb->getRedirectLoginHelper();

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $json['facebook'] = $helper->getLoginUrl($server . 'index.php?path=account/facebook', ['email']);

        echo json_encode($json);
    }

    public function find_store() {
        $html = '';
        $this->load->language('information/locations');
        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = '';
        }

        $html = [];
        $html['text_error'] = $this->language->get('text_error');
        $html['text_not_found'] = $this->language->get('text_not_found');
        $this->load->model('setting/store');

        if ('autosuggestion' == $this->config->get('config_store_location')) {
            //use location index to send lat lang for store collection

            $addressTmp = $this->getZipcode(urldecode($this->request->get['location']));

            $data['zipcode'] = $addressTmp ? $addressTmp : '';
            //$stores = $this->model_setting_store->getStoreByLatLang($this->request->get['zipcode'], $filter_name);
            $stores = $this->model_setting_store->getCollectionStoreByZip($data['zipcode'], $this->request->get['collection_id']);
        } else {
            //$stores = $this->model_setting_store->getStoreByZip($this->request->get['zipcode'], $filter_name);
            $stores = $this->model_setting_store->getCollectionStoreByZip($this->request->get['zipcode'], $this->request->get['collection_id']);
        }

        //print_r($stores);

        if ($stores) {
            $html['store'] = true;
            //$this->session->data['zipcode'] = $this->request->get['zipcode'];

            if ('autosuggestion' == $this->config->get('config_store_location')) {
                $_COOKIE['location'] = urldecode($this->request->get['location']);

                setcookie('location', urldecode($this->request->get['location']), time() + (86400 * 30 * 30 * 30 * 3), '/'); // 3 months expiry
            } else {
                $_COOKIE['zipcode'] = $this->request->get['zipcode'];

                setcookie('zipcode', $this->request->get['zipcode'], time() + (86400 * 30 * 30 * 30 * 3), '/'); // 3 months expiry
            }

            $this->session->data['config_store_id'] = $stores['store_id'];

            $html['redirect_url'] = $this->url->link('product/store&store_id=' . $stores['store_id'] . '');
        } else {
            $html['store'] = false;
        }
        echo json_encode($html);
    }

    public function index() {
        unset($this->session->data['config_store_id']);

        unset($_COOKIE['zipcode']);

        setcookie('zipcode', '', time() - 3600);

        setcookie('location', '', time() - 3600);
        setcookie('location_name', '', time() - 3600);

        $log = new Log('error.log');

        $data['kondutoStatus'] = $this->config->get('config_konduto_status');

        $this->load->model('setting/store');

        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        if (isset($this->session->data['language'])) {
            $data['config_language'] = $this->session->data['language'];
        } else {
            $data['config_language'] = 'pt-BR';
        }

        if (isset($this->request->get['refer'])) {
            $x = strpos($this->request->get['refer'], '!@');

            $p = substr($this->request->get['refer'], $x + 2);

            $cookie_name = 'referral';
            $cookie_value = $p;
            setcookie($cookie_name, $cookie_value, time() + (86400 * 30), '/'); // 1 day expiry
        }

        $log->write($data['konduto_public_key']);
        unset($this->session->data['visitor_id']);

        $log->write($this->session->data['language']);

        /* if(count($_COOKIE) > 0 && isset($_COOKIE['zipcode'])) {
          $this->response->redirect($this->url->link('information/locations/stores', 'zipcode=' . $_COOKIE['zipcode']));
          }

          if(count($_COOKIE) > 0 && isset($_COOKIE['location'])) {
          $this->response->redirect($this->url->link('information/locations/stores', 'location=' . $_COOKIE['location']));
          }


          if(isset($this->session->data['config_store_id'])){
          //$this->response->redirect($this->url->link('product/store'));

          $this->response->redirect($this->url->link('product/store','store_id='.$this->session->data['config_store_id'].''));
          } */

        $data['telephone_mask'] = $this->config->get('config_telephone_mask');

        if (isset($data['telephone_mask'])) {
            $data['telephone_mask_number'] = str_replace('#', '9', $this->config->get('config_telephone_mask'));
        }

        $data['taxnumber_mask'] = $this->config->get('config_taxnumber_mask');

        if (isset($data['taxnumber_mask'])) {
            $data['taxnumber_mask_number'] = str_replace('#', '*', $this->config->get('config_taxnumber_mask'));
        }

        $this->load->model('tool/image');
        $this->load->language('common/home');

        $data['blocks'] = [];

        $blocks = $this->model_tool_image->getBlocks();

        //echo "<pre>";print_r($blocks);die;
        foreach ($blocks as $block) {
            if (is_file(DIR_IMAGE . $block['image'])) {
                $image = $this->model_tool_image->resize($block['image'], 290, 163);
            } else {
                $image = $this->model_tool_image->resize('no_image.png', 290, 163);
            }

            $temp['image'] = $image;
            $temp['description'] = trim($block['description']);
            $temp['title'] = $block['title'];
            $temp['sort_order'] = $block['sort_order'];

            array_push($data['blocks'], $temp);
        }

        //echo "<pre>";print_r($data['blocks']);die;

        $this->document->setDescription($this->config->get('config_meta_description'));
        $this->document->setKeywords($this->config->get('config_meta_keyword'));

        $data['description'] = $this->document->getDescription();
        $data['keywords'] = $this->document->getKeywords();
        $data['metas'] = $this->document->getMetas();

        $data['zipcode_mask'] = $this->config->get('config_zipcode_mask');

        if (isset($data['zipcode_mask'])) {
            $data['zipcode_mask_number'] = str_replace('#', '9', $this->config->get('config_zipcode_mask'));
        }

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        if (!$this->config->get('config_seo_url') and isset($this->request->get['path'])) {
            $this->document->addLink($server, 'canonical');
        }

        if ($this->config->get('config_google_analytics_status')) {
            $data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');
        } else {
            $data['google_analytics'] = '';
        }

        $data['text_get_groceries'] = $this->language->get('text_get_groceries');

        $data['text_deliver_in'] = $this->language->get('text_deliver_in');
        $data['text_order_fresh'] = $this->language->get('text_order_fresh');
        $data['text_shop_at'] = $this->language->get('text_shop_at');

        $data['text_from_device'] = $this->language->get('text_from_device');
        $data['text_schedule_delivery'] = $this->language->get('text_schedule_delivery');
        $data['text_get_grocery_at'] = $this->language->get('text_get_grocery_at');
        $data['text_an_hour'] = $this->language->get('text_an_hour');

        $data['text_or_want_them'] = $this->language->get('text_or_want_them');
        $data['text_get_delivered'] = $this->language->get('text_get_delivered');
        $data['text_fresh_handpicked'] = $this->language->get('text_fresh_handpicked');
        $data['text_local_stores'] = $this->language->get('text_local_stores');

        $data['text_welcome_user'] = $this->language->get('text_welcome_user');
        $data['text_open_store'] = $this->language->get('text_open_store');
        $data['text_store_working'] = $this->language->get('text_store_working');
        $data['text_enter_zipcode_title'] = $this->language->get('text_enter_zipcode_title');
        $data['text_shop_now'] = $this->language->get('text_shop_now');

        $data['text_enter_zipcode_title_collection'] = $this->language->get('text_enter_zipcode_title_collection');
        $data['text_delivery_detail'] = $this->language->get('text_delivery_detail');
        $data['text_enter_zipcode'] = $this->language->get('text_enter_zipcode');
        $data['text_find_store'] = $this->language->get('text_find_store');
        $data['text_have_account'] = $this->language->get('text_have_account');
        $data['text_log_in'] = $this->language->get('text_log_in');
        $data['text_get_delivered'] = $this->language->get('text_get_delivered');
        $data['heading_title'] = $this->language->get('heading_title');

        if (isset($this->session->data['error'])) {
            $data['error_warning'] = $this->session->data['error'];

            unset($this->session->data['error']);
        } else {
            $data['error_warning'] = '';
        }

        $data['text_move_next'] = $this->language->get('text_move_next');
        $data['text_login'] = $this->language->get('text_login');
        $data['text_back'] = $this->language->get('text_back');
        $data['text_enter_code_in_area'] = $this->language->get('text_enter_code_in_area');
        $data['text_move_Next'] = $this->language->get('text_move_Next');
        $data['text_enter_you_agree'] = $this->language->get('text_enter_you_agree');
        $data['text_terms_of_service'] = $this->language->get('text_terms_of_service');
        $data['text_privacy_policy'] = $this->language->get('text_privacy_policy');

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
        $data['text_heading'] = $this->language->get('text_heading');
        $data['text_heading2'] = $this->language->get('text_heading2');
        $data['text_heading3'] = $this->language->get('text_heading3');
        $data['text_heading4'] = $this->language->get('text_heading4');
        $data['text_heading5'] = $this->language->get('text_heading5');
        $data['text_heading6'] = $this->language->get('text_heading6');
        $data['text_we_located_at'] = $this->language->get('text_we_located_at');

        $data['text_my_cash'] = $this->language->get('text_my_cash');
        $data['text_my_wishlist'] = $this->language->get('text_my_wishlist');
        $data['label_my_address'] = $this->language->get('label_my_address');
        $data['text_my_profile'] = $this->language->get('text_my_profile');
        $data['contactus'] = $this->language->get('contactus');
        $data['text_logout'] = $this->language->get('text_logout');
        $data['text_register'] = $this->language->get('text_register');

        $data['step1'] = $this->language->get('step1');
        $data['step2'] = $this->language->get('step2');
        $data['step3'] = $this->language->get('step3');
        $data['step4'] = $this->language->get('step4');

        $data['label_start'] = $this->language->get('label_start');
        $data['label_name'] = $this->language->get('label_name');
        $data['label_email/phone'] = $this->language->get('label_email/phone');
        $data['label_msg'] = $this->language->get('label_msg');

        $data['button_send'] = $this->language->get('button_send');

        $data['is_login'] = $this->customer->isLogged();
        $data['f_name'] = $this->customer->getFirstName();
        $data['name'] = $this->customer->getFirstName();
        $data['l_name'] = $this->customer->getLastName();
        $data['full_name'] = $data['f_name']; //.' '.$data['l_name'];
        $data['home'] = $this->url->link('common/home');
        $data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
        $data['logged'] = $this->customer->isLogged();
        $data['account'] = $this->url->link('account/account', '', 'SSL');
        $data['register'] = $this->url->link('account/register', '', 'SSL');
        $data['login'] = $this->url->link('account/login', '', 'SSL');
        $data['order'] = $this->url->link('account/order', '', 'SSL');
        $data['credit'] = $this->url->link('account/credit', '', 'SSL');
        $data['pezesha'] = $this->url->link('account/pezesha', '', 'SSL');
        $data['download'] = $this->url->link('account/download', '', 'SSL');
        $data['logout'] = $this->url->link('account/logout', '', 'SSL');
        $data['shopping_cart'] = $this->url->link('checkout/cart');
        $data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
        $data['contact'] = $this->url->link('information/contact');
        $data['telephone'] = $this->config->get('config_telephone');
        $data['refer'] = $this->url->link('account/refer', '', 'SSL');
        $data['reward'] = $this->url->link('account/reward', '', 'SSL');
        $data['footer'] = $this->load->controller('common/footer');
        $data['action'] = $this->url->link('common/home/find_store');
        $data['address'] = $this->url->link('account/address', '', 'SSL');
        $data['help'] = $this->url->link('information/help');

        $log->write($this->session->data);

        $data['login_modal'] = $this->load->controller('common/login_modal');

        $data['signup_modal'] = $this->load->controller('common/signup_modal');

        $data['forget_modal'] = $this->load->controller('common/forget_modal');

        $link = $_SERVER['REQUEST_URI'];
        $link_array = explode('/', $link);
        $data['filter'] = end($link_array);

        //$data['heading_title'] = $this->config->get('config_meta_title', '');

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
            //$data['icon'] = $server . 'image/' . $this->config->get('config_icon');
            $data['icon'] = $this->model_tool_image->resize($this->config->get('config_icon'), 100, 100);
        } else {
            $data['icon'] = '';
        }

        if (is_file(DIR_IMAGE . $this->config->get('config_fav_icon'))) {
            $data['fav_icon'] = $server . 'image/' . $this->config->get('config_fav_icon');
        } else {
            $data['fav_icon'] = '';
        }

        $data['collection_id'] = $this->request->get['collection_id'];
        $store_group_data = $this->model_setting_store->getStoreGroup($this->request->get['collection_id']);

        //echo "<pre>";print_r($store_group_data);die;

        if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
            //$data['logo'] = $server . 'image/'. $this->config->get('config_logo');
            $data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'), 197, 34);
        } else {
            //$data['logo'] = '';
            $data['logo'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        if (is_file(DIR_IMAGE . $this->config->get('config_white_logo'))) {
            //$data['white_logo'] = $server . 'image/'. $this->config->get('config_white_logo');
            $data['white_logo'] = $this->model_tool_image->resize($this->config->get('config_white_logo'), 197, 34);
        } else {
            //$data['white_logo'] = '';
            $data['white_logo'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        if (count($store_group_data) > 0 && is_file(DIR_IMAGE . $store_group_data['logo'])) {
            //$data['store_collection_logo'] = $server . 'image/'. $store_group_data['logo'];
            $data['store_collection_logo'] = $this->model_tool_image->resize($store_group_data['logo'], 100, 100);
        } else {
            //$data['store_collection_logo'] = '';
            $data['store_collection_logo'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        if (count($store_group_data) > 0) {
            $data['store_collection_name'] = $store_group_data['name'];
        } else {
            $data['store_collection_name'] = '';
        }

        $data['store_heading_title'] = $data['store_collection_name'] . ' - ' . $data['heading_title'];

        if (isset($this->session->data['warning'])) {
            $data['warning'] = $this->session->data['warning'];
            unset($this->session->data['warning']);
        } else {
            $data['warning'] = '';
        }

        $data['banners'] = $data['testimonials'] = [];

        $rows = $this->model_tool_image->getTestimonial();

        foreach ($rows as $row) {
            $row['thumb'] = $this->model_tool_image->resize($row['image'], 80, 80);
            $data['testimonials'][] = $row;
        }

        //banners
        $rows = $this->model_tool_image->getAllOffers();

        foreach ($rows as $row) {
            if (false === strpos($row['link'], '://')) {
                $row['link'] = 'http://' . $row['link'];
            }
            $row['image'] = $this->model_tool_image->resize($row['image'], 300, 300);
            $data['banners'][] = $row;
        }

        /* START */

        $this->load->model('setting/store');
        $data['store_collections'] = $this->model_setting_store->getCollectionStoresDetails($this->request->get['collection_id']);

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['base'] = $server;

        $data['login'] = $this->url->link('account/login', '', 'SSL');
        $data['register'] = $this->url->link('account/register', '', 'SSL');
        $data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/store_collection.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/store_collection.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/common/store_collection.tpl', $data));
        }
    }

    public function toHome() {
        unset($this->session->data['config_store_id']);

        setcookie('zipcode', null, time() - 3600, '/');

        $this->cart->clear();

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $this->response->redirect($server);
    }

    public function toStore() {
        unset($this->session->data['config_store_id']);
        $this->cart->clear();
        $this->response->redirect($this->url->link('common/home/index'));
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

}
