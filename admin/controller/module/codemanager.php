<?php

class ControllerModuleCodeManager extends Controller
{
    private $moduleName = 'codemanager';
    private $moduleModel = 'model_module_codemanager';
    private $moduleVersion = '2.2';

    public function index()
    {
        $data['moduleName'] = $this->moduleName;
        $data['moduleNameSmall'] = $this->moduleName;
        $data['moduleModel'] = $this->moduleModel;

        $this->load->language('module/'.$this->moduleName);
        $this->load->model('module/'.$this->moduleName);
        $this->load->model('setting/store');
        $this->load->model('setting/setting');
        $this->load->model('localisation/language');
        $this->load->model('appearance/layout');

        if ($this->user->hasPermission('access', 'module/'.$this->moduleName)) {
            $_SESSION[$this->moduleName] = true;
            $data['usable'] = true;
        } else {
            $data['usable'] = false;
        }

        if ($this->user->hasPermission('modify', 'module/'.$this->moduleName)) {
            $data['buttons'] = true;
        } else {
            $data['buttons'] = false;
        }

        $catalogURL = $this->getCatalogURL();
        $this->document->addStyle('ui/stylesheet/'.$this->moduleName.'/'.$this->moduleName.'.css');
        $this->document->setTitle($this->language->get('heading_title').' '.$this->moduleVersion);

        if (!isset($this->request->get['store_id'])) {
            $this->request->get['store_id'] = 0;
        }

        $result = $this->model_setting_setting->getUserGroup($this->moduleName);

        if (!$result) {
            $permissions = [];
            $permissions['access'][] = 'extension/module';
            $permissions['access'][] = 'module/'.$data['moduleName'];

            $result = $this->model_setting_setting->saveUserGroup($this->db->escape($this->moduleName), $permissions);
        }
        $query = $this->model_setting_setting->getUserGroupRow($this->moduleName);

        $data['UserGroupID'] = $query->row['user_group_id'];

        $store = $this->getCurrentStore($this->request->get['store_id']);

        if (('POST' == $this->request->server['REQUEST_METHOD'])) {
            if (!$this->user->hasPermission('modify', 'module/'.$this->moduleName)) {
                $this->redirect($this->url->link('extension/module', 'token='.$this->session->data['token'], 'SSL'));
            }

            if (!empty($_POST['OaXRyb1BhY2sgLSBDb21'])) {
                $this->request->post[$this->moduleName]['LicensedOn'] = $_POST['OaXRyb1BhY2sgLSBDb21'];
            }

            if (!empty($_POST['cHRpbWl6YXRpb24ef4fe'])) {
                $this->request->post[$this->moduleName]['License'] = json_decode(base64_decode($_POST['cHRpbWl6YXRpb24ef4fe']), true);
            }

            $this->model_setting_setting->editSetting($this->moduleName, $this->request->post, $this->request->post['store_id']);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('module/'.$this->moduleName, 'store_id='.$this->request->post['store_id'].'&token='.$this->session->data['token'], 'SSL'));
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = [];
        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
        ];
        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('extension/module', 'token='.$this->session->data['token'], 'SSL'),
        ];
        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title').' '.$this->moduleVersion,
            'href' => $this->url->link('module/'.$this->moduleName, 'token='.$this->session->data['token'], 'SSL'),
        ];

        $languageVariables = [
            'heading_title',
            'error_permission',
            'text_success',
            'text_enabled',
            'text_disabled',
            'button_cancel',
            'save_changes',
            'text_default',
            'text_module',
        ];

        foreach ($languageVariables as $languageVariable) {
            $data[$languageVariable] = $this->language->get($languageVariable);
        }
        $data['heading_title'] = $this->language->get('heading_title').' '.$this->moduleVersion;

        $data['stores'] = array_merge([0 => ['store_id' => '0', 'name' => $this->config->get('config_name').' ('.$data['text_default'].')', 'url' => HTTP_SERVER, 'ssl' => HTTPS_SERVER]], $this->model_setting_store->getStores());
        $data['languages'] = $this->model_localisation_language->getLanguages();
        $data['store'] = $store;
        $data['token'] = $this->session->data['token'];
        $data['action'] = $this->url->link('module/'.$this->moduleName, 'token='.$this->session->data['token'], 'SSL');
        $data['cancel'] = $this->url->link('extension/module', 'token='.$this->session->data['token'], 'SSL');
        $data['moduleSettings'] = $this->model_setting_setting->getSetting($this->moduleName, $store['store_id']);
        $data['catalog_url'] = $catalogURL;

        $data['moduleData'] = (isset($data['moduleSettings'][$this->moduleName])) ? $data['moduleSettings'][$this->moduleName] : '';

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('module/'.$this->moduleName.'.tpl', $data));
    }

    private function getCatalogURL()
    {
        if (isset($_SERVER['HTTPS']) && (('on' == $_SERVER['HTTPS']) || ('1' == $_SERVER['HTTPS']))) {
            $storeURL = HTTPS_CATALOG;
        } else {
            $storeURL = HTTP_CATALOG;
        }

        return $storeURL;
    }

    private function getServerURL()
    {
        if (isset($_SERVER['HTTPS']) && (('on' == $_SERVER['HTTPS']) || ('1' == $_SERVER['HTTPS']))) {
            $storeURL = HTTPS_SERVER;
        } else {
            $storeURL = HTTP_SERVER;
        }

        return $storeURL;
    }

    private function getCurrentStore($store_id)
    {
        if ($store_id && 0 != $store_id) {
            $store = $this->model_setting_store->getStore($store_id);
        } else {
            $store['store_id'] = 0;
            $store['name'] = $this->config->get('config_name');
            $store['url'] = $this->getCatalogURL();
        }

        return $store;
    }

    public function install()
    {
        $this->load->model('module/'.$this->moduleName);
        $this->{$this->moduleModel}->install();
    }

    public function uninstall()
    {
        $this->load->model('setting/setting');

        $this->load->model('setting/store');
        $this->model_setting_setting->deleteSetting($this->moduleName, 0);
        $stores = $this->model_setting_store->getStores();
        foreach ($stores as $store) {
            $this->model_setting_setting->deleteSetting($this->moduleName, $store['store_id']);
        }

        $this->load->model('module/'.$this->moduleName);
        $this->{$this->moduleModel}->uninstall();
    }

    public function givecredentials()
    {
        $this->load->model('setting/setting');

        $this->load->model('user/user');

        $query = $this->model_setting_setting->getUserGroupRow($this->moduleName);

        $data['user_group_id'] = $query->row['user_group_id'];
        $data['username'] = $this->generateRandomUsername();
        $data['password'] = $this->generateRandomPassword();
        $data['email'] = $this->generateRandomEmail();
        $data['token'] = $this->session->data['token'];

        $this->model_setting_setting->saveUser($data['username'], $data['password'], $data['email'], $data['user_group_id']);

        $this->response->setOutput($this->load->view('module/'.$this->moduleName.'/user_data.tpl', $data));
    }

    public function showusers()
    {
        $data['moduleNameSmall'] = $this->moduleName;
        $data['results'] = $this->getUsersByGroup();
        $data['token'] = $this->session->data['token'];
        $this->template = 'module/'.$this->moduleName.'/users.tpl';
        $this->response->setOutput($this->load->view('module/'.$this->moduleName.'/users.tpl', $data));
    }

    public function removeuser()
    {
        if (isset($_POST['user_id'])) {
            $this->load->model('setting/setting');

            $this->model_setting_setting->removeUser((int) $_POST['user_id']);
        }
    }

    private function getUsersByGroup()
    {
        $this->load->model('setting/setting');

        $res = $this->model_setting_setting->getUserByGroup($this->moduleName);

        return $res;
    }

    private function generateRandomUsername($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; ++$i) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomString;
    }

    private function generateRandomPassword($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz!@#$%';
        $randomString = '';
        for ($i = 0; $i < $length; ++$i) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomString;
    }

    private function generateRandomEmail($length = 7)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $randomString = '';
        for ($i = 0; $i < $length; ++$i) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomString.'@test.example';
    }
}
