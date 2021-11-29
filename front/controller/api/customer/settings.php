<?php

require_once DIR_SYSTEM.'/vendor/konduto/vendor/autoload.php';

require_once DIR_SYSTEM.'/vendor/fcp-php/autoload.php';

require DIR_SYSTEM.'vendor/Facebook/autoload.php';

require_once DIR_APPLICATION.'/controller/api/settings.php';

class ControllerApiCustomerSettings extends Controller
{
    private $error = [];

    public function getAdminSettings()
    {
        $json = [];

        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');
        $this->load->model('account/customer');
        $this->load->model('assets/information');

        //if( $this->customer->isLogged()) {
        if (true) {
            if (isset($this->requet->get['store_id'])) {
                $store_id = $this->requet->get['store_id'];
            } else {
                $store_id = 0;
            }

            $code = 'config';

            $data = $this->model_account_customer->getAdminConfigSettings($store_id, $code);

            //echo "<pre>";print_r($data);die;

            $newData = [];

            $newData = $this->model_account_customer->getAdminConfigSettings($store_id, 'stripe');

            foreach ($data as $dat) {
                if ('config_telephone_code' == $dat['key'] || 'config_telephone' == $dat['key']) {
                    $newData[] = $dat;
                }

                if ('config_reward_enabled' == $dat['key']) {
                    $newData[] = $dat;
                }

                if ('reward_status' == $dat['key']) {
                    $newData[] = $dat;
                }

                if ('config_android_app_link' == $dat['key']) {
                    $newData[] = $dat;
                }

                if ('config_apple_app_link' == $dat['key']) {
                    $newData[] = $dat;
                }

                if ('config_email' == $dat['key']) {
                    $newData[] = $dat;
                }

                if ('config_update_android' == $dat['key']) {
                    $newData[] = $dat;
                }

                if ('config_update_ios' == $dat['key']) {
                    $newData[] = $dat;
                }
            }

            /*ipay config*/

            $newData[] = ['key' => 'iPay_payment_software_merchant_name', 'value' => $this->config->get('iPay_payment_software_merchant_name')];

            $newData[] = ['key' => 'iPay_payment_software_merchant_key', 'value' => $this->config->get('iPay_payment_software_merchant_key')];

            $newData[] = ['key' => 'iPay_payment_software_callback_url', 'value' => $this->url->link($this->config->get('iPay_payment_software_callback_url'))];

            $newData[] = ['key' => 'iPay_payment_software_ipay_url', 'value' => $this->config->get('iPay_payment_software_ipay_url')];

            $newData[] = ['key' => 'iPay_payment_software_mode', 'value' => $this->config->get('iPay_payment_software_mode')];

            $iPay_payment_software_mode_in_binary = '1';
            if ('test' == $this->config->get('iPay_payment_software_mode')) {
                $iPay_payment_software_mode_in_binary = '0';
            }

            $newData[] = ['key' => 'iPay_payment_software_mode_in_binary', 'value' => $iPay_payment_software_mode_in_binary];

            $newData[] = ['key' => 'iPay_payment_software_method', 'value' => $this->config->get('iPay_payment_software_method')];

            $newData[] = ['key' => 'iPay_payment_software_order_status_id', 'value' => $this->config->get('iPay_payment_software_order_status_id')];

            $newData[] = ['key' => 'iPay_payment_software_status', 'value' => $this->config->get('iPay_payment_software_status')];

            $newData[] = ['key' => 'iPay_payment_software_total', 'value' => $this->config->get('iPay_payment_software_total')];

            $newData[] = ['key' => 'iPay_payment_software_elipa_enabled', 'value' => $this->config->get('iPay_payment_software_elipa_enabled')];

            $newData[] = ['key' => 'iPay_payment_software_mvisa_enabled', 'value' => $this->config->get('iPay_payment_software_mvisa_enabled')];

            $newData[] = ['key' => 'iPay_payment_software_mpesa_enabled', 'value' => $this->config->get('iPay_payment_software_mpesa_enabled')];

            $newData[] = ['key' => 'iPay_payment_software_airtel_enabled', 'value' => $this->config->get('iPay_payment_software_airtel_enabled')];

            $newData[] = ['key' => 'iPay_payment_software_equity_enabled', 'value' => $this->config->get('iPay_payment_software_equity_enabled')];

            $newData[] = ['key' => 'iPay_payment_software_mobilebanking_enabled', 'value' => $this->config->get('iPay_payment_software_mobilebanking_enabled')];

            $newData[] = ['key' => 'iPay_payment_software_debitcard_enabled', 'value' => $this->config->get('iPay_payment_software_debitcard_enabled')];

            $newData[] = ['key' => 'iPay_payment_software_creditcard_enabled', 'value' => $this->config->get('iPay_payment_software_creditcard_enabled')];

            $newData[] = ['key' => 'iPay_payment_software_mkoporahisi_enabled', 'value' => $this->config->get('iPay_payment_software_mkoporahisi_enabled')];

            $newData[] = ['key' => 'iPay_payment_software_saida_enabled', 'value' => $this->config->get('iPay_payment_software_saida_enabled')];

            /* ipay config end*/
            $newData[] = ['key' => 'reward_status', 'value' => $this->config->get('reward_status')];

            $newData[] = ['key' => 'left_symbol_currency', 'value' => $this->currency->getSymbolLeft()];
            $newData[] = ['key' => 'right_symbol_currency', 'value' => $this->currency->getSymbolRight()];

            if ($this->request->server['HTTPS']) {
                $site_link = $this->config->get('config_ssl');
            } else {
                $site_link = $this->config->get('config_url');
            }

            $newData[] = ['key' => 'refer_link', 'value' => 'https://www.emlima.com'];

            $newData[] = ['key' => 'privacy_policy_link', 'value' => htmlspecialchars_decode($this->url->link('information/information/agree', 'information_id='.$this->config->get('config_privacy_policy_id'), 'SSL'))];

            //$newData[] = ['key'      => 'terms_conditions_link','value' => htmlspecialchars_decode($this->url->link('information/information', 'information_id=' .  $this->config->get('config_privacy_policy_id'), 'SSL')) ];

            $information_info = $this->model_assets_information->getInformation($this->config->get('config_return_id'));

            if ($information_info) {
                $text_agree = htmlspecialchars_decode($this->url->link('information/information/agree', 'information_id='.$this->config->get('config_return_id'), 'SSL'));
            } else {
                $text_agree = '';
            }

            $this->load->model('localisation/return_reason');
            $this->load->model('api/return');

            $return_reasons = $this->model_localisation_return_reason->getReturnReasons();

            $return_actions = $this->model_api_return->getReturnActions();

            $return_statuses = $this->model_api_return->getReturnStatuses();

            $newData[] = ['key' => 'terms_conditions_link', 'value' => $text_agree];
            $newData[] = ['key' => 'return_reasons', 'value' => $return_reasons];

            $newData[] = ['key' => 'return_actions', 'value' => $return_actions];
            $newData[] = ['key' => 'return_statuses', 'value' => $return_statuses];

            $newData[] = ['key' => 'config_maintenance', 'value' => $this->config->get('config_maintenance')];

            $const_latitude = null;
            $const_longitude = null;
            $const_location_name = null;

            if (defined('const_latitude') && defined('const_longitude') && !empty(const_latitude) && !empty(const_longitude)) {
                $const_latitude = const_latitude;
                $const_longitude = const_longitude;
                $const_location_name = const_location_name;
            }

            $newData[] = ['key' => 'const_latitude', 'value' => $const_latitude];
            $newData[] = ['key' => 'const_longitude', 'value' => $const_longitude];
            $newData[] = ['key' => 'const_location_name', 'value' => $const_location_name];

            $json['data'] = $newData;
        } else {
            $json['status'] = 10013;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_not_loggedin')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addDeviceIdToCustomer($args = [])
    {
        $log = new Log('error.log');
        $log->write('addDeviceIdToCustomer');

        $log->write($args);

        $json = [];

        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        if ($this->customer->isLogged() && isset($args['device_id'])) {
            $this->load->model('setting/store');

            $args['customer_id'] = $this->customer->getId();

            $log->write($args);

            $this->model_setting_store->updateCustomerDeviceId($args);

            $json['message'][] = ['type' => '', 'body' => 'added device id'];
        } else {
            $json['status'] = 10013;
            $json['message'][] = ['type' => '', 'body' => 'missing data'];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getHelps()
    {
        $json = [];

        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');
        $this->load->model('information/help');

        $categories = $this->model_information_help->getCategories();

        //echo "<pre>";print_r($categories);die;

        $data['categories'] = [];

        foreach ($categories as $category) {
            $category_info = $this->model_information_help->getCategory($category['category_id']);

            $data['title'] = empty($category_info['name']) ? $category_info['name'] : $category_info['name'];

            if (is_file(DIR_IMAGE.$this->config->get('config_fav_icon'))) {
                $data['fav_icon'] = $server.'image/'.$this->config->get('config_fav_icon');
            } else {
                $data['fav_icon'] = '';
            }

            $data['heading_title'] = $category_info['name'];

            $data['result'] = $this->model_information_help->getData($category_info['category_id']);

            $category['data'] = $data['result'];

            $data['categories'][] = $category;

            //echo "<pre>";print_r($result);die;
        }

        $json['data'] = $data['categories'];

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getAboutUs()
    {
        $json = [];

        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');
        $this->load->model('account/customer');

        //if( $this->customer->isLogged()) {
        if (true) {
            $data['about_us'] = htmlspecialchars_decode($this->config->get('config_aboutus'));

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
