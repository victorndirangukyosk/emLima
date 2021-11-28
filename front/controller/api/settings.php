<?php

class ControllerApiSettings extends Controller
{
    public function getSettings($args = [])
    {
        $this->load->language('api/settings');

        $json = [];

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');
        $this->load->model('account/customer');
        $this->load->model('assets/information');

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('setting/setting');

            $store_id = 0;

            if (!empty($args['url'])) {
                $this->load->model('setting/store');

                $stores = $this->model_setting_store->getStores();

                if (!empty($stores)) {
                    foreach ($stores as $store) {
                        $url = str_replace('http://', '', $store['url']);
                        $url = str_replace('https://', '', $url);
                        $url = rtrim($url, '/');

                        if ($url != $args['url']) {
                            continue;
                        }

                        $store_id = $store['store_id'];
                        break;
                    }
                }
            }

            $code = !empty($args['code']) ? $args['code'] : 'config';

            $config = $this->model_setting_setting->getSetting($code, $store_id);

            if (!empty($args['url'])) {
                $config['config_url'] = $args['url'];
            } else {
                $config_url = !empty($config['config_url']) ? $config['config_url'] : $this->config->get('config_url');

                $url = str_replace('http://', '', $config_url);
                $url = str_replace('https://', '', $url);
                $url = rtrim($url, '/');

                $config['config_url'] = $url;
            }

            if (!empty($config['config_image'])) {
                $config['config_image'] = 'image/'.$config['config_image'];
            } else {
                $config['config_image'] = 'image/placeholder.png';
            }

            $config['config_logo'] = 'image/'.$config['config_logo'];
            $config['config_icon'] = 'image/'.$config['config_icon'];

            $json = $config;
            $json['stores'] = $this->model_setting_setting->getUserStores($this->session->data['api_id']);

            $json['vendor_info'] = $this->model_setting_setting->getUser($this->session->data['api_id']);

            $vendor_group_ids = explode(',', $this->config->get('config_vendor_group_ids'));

            if (in_array($json['vendor_info']['user_group_id'], $vendor_group_ids)) {
                $json['vendor_info']['user_type'] = 'vendor';
            } else {
                $json['vendor_info']['user_type'] = 'admin';
            }

            $json['vendor_info']['view_map'] = $this->config->get('config_view_map');

            if (count($json['stores']) > 0) {
                $this->session->data['store_id'] = $json['stores'][0]['store_id'];
            } else {
            }
        }

        //echo "<pre>";print_r($json);die;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getAdminSettings($args = [])
    {
        $this->load->language('api/settings');

        $json = [];

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');
        $this->load->model('account/customer');
        $this->load->model('assets/information');

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
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
                if ('config_telephone_code' == $dat['key']) {
                    $newData[] = $dat;
                }

                if ('config_telephone' == $dat['key']) {
                    $newData[] = $dat;
                }

                if ('config_email' == $dat['key']) {
                    $newData[] = $dat;
                }
            }

            $newData[] = ['key' => 'left_symbol_currency', 'value' => $this->currency->getSymbolLeft()];
            $newData[] = ['key' => 'right_symbol_currency', 'value' => $this->currency->getSymbolRight()];

            if ($this->request->server['HTTPS']) {
                $site_link = $this->config->get('config_ssl');
            } else {
                $site_link = $this->config->get('config_url');
            }

            $newData[] = ['key' => 'refer_link', 'value' => $site_link];

            $newData[] = ['key' => 'privacy_policy_link', 'value' => htmlspecialchars_decode($this->url->link('information/information/agree', 'information_id='.$this->config->get('config_privacy_policy_id'), 'SSL'))];

            //$newData[] = ['key'      => 'terms_conditions_link','value' => htmlspecialchars_decode($this->url->link('information/information', 'information_id=' .  $this->config->get('config_privacy_policy_id'), 'SSL')) ];

            $information_info = $this->model_assets_information->getInformation($this->config->get('config_return_id'));

            if ($information_info) {
                $text_agree = htmlspecialchars_decode($this->url->link('information/information/agree', 'information_id='.$this->config->get('config_return_id'), 'SSL'));
            } else {
                $text_agree = '';
            }

            $newData[] = ['key' => 'terms_conditions_link', 'value' => $text_agree];

            $json['data'] = $newData;
        }

        //echo "<pre>";print_r($json);die;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getSessionVariables($args = [])
    {
        $json = $this->session->data;

        $json['cookie'] = $this->session->getId();

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addSetSessionVariable($args = [])
    {
        $json = [];

        echo 'addSetSessionVariable';
        print_r($args);
        /*echo $args['store_id'];
        echo "set";*/
        if (isset($args['store_id'])) {
            echo 'in if';
            $this->session->data['store_id'] = $args['store_id'];

            $json = $this->session->data;
            $json['cookie'] = $this->session->getId();

            $json['cookiess'] = $this->session->getId();

            $json['success'] = 'the session set is seferf'.$this->session->data['store_id'];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addDeviceIdToUser($args = [])
    {
        $json = [];
        $log = new Log('error.log');
        $log->write('addDeviceIdToUser');
        $log->write($args);
        if (isset($args['device_id']) && isset($args['user_id'])) {
            $this->load->model('setting/store');

            $this->model_setting_store->updateDeviceId($args);

            $json['success'] = 'added device id';
        } else {
            $json['success'] = 'missing data ';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addremoveDeviceIdToUser($args = [])
    {
        $json = [];
        //print_r($args);
        //echo "removeDeviceIdToUser";

        $log = new Log('error.log');
        $log->write('addremoveDeviceIdToUser');
        $log->write($args);

        if (isset($args['user_id'])) {
            $this->load->model('setting/store');

            $args['device_id'] = null;

            $this->model_setting_store->removeDeviceId($args);

            $json['success'] = 'removed device id';
        } else {
            $json['success'] = 'missing data ';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
