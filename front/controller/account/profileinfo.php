<?php

require_once DIR_SYSTEM . '/vendor/konduto/vendor/autoload.php';

//require_once DIR_SYSTEM.'/vendor/mpesa-php-sdk-master/vendor/autoload.php';

require_once DIR_SYSTEM . '/vendor/fcp-php/autoload.php';

require DIR_SYSTEM . 'vendor/Facebook/autoload.php';

require_once DIR_APPLICATION . '/controller/api/settings.php';

class ControllerAccountProfileInfo extends Controller {

    private $error = [];

    public function index() {
        $data['kondutoStatus'] = $this->config->get('config_konduto_status');

        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        $data['redirect_coming'] = false;

        $this->document->addStyle('/front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_login.css');

        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/profileinfo', '', 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $this->load->language('account/edit');
        $this->load->language('account/account');

        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('account/customer');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validate()) {
            $this->model_account_customer->addEditCustomerInfo($this->customer->getId(), $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');

            // Add to activity log
            $this->load->model('account/activity');

            $activity_data = [
                'customer_id' => $this->customer->getId(),
                'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
            ];
            $log = new Log('error.log');
            $log->write('account profileinfo');

            $this->model_account_activity->addActivity('profileinfo', $activity_data);

            $log->write('account profileinfo');

            $this->response->redirect($this->url->link('account/profileinfo', '', 'SSL'));
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/account', '', 'SSL'),
        ];

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_your_details'] = $this->language->get('text_your_details');
        $data['text_additional'] = $this->language->get('text_additional');
        $data['text_select'] = $this->language->get('text_select');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['text_male'] = $this->language->get('text_male');
        $data['text_female'] = $this->language->get('text_female');
        $data['text_other'] = $this->language->get('text_other');
        $data['entry_dob'] = $this->language->get('entry_dob');

        $data['entry_password'] = $this->language->get('entry_password');
        $data['entry_confirmpassword'] = $this->language->get('entry_confirmpassword');

        $data['entry_location'] = $this->language->get('entry_location');
        $data['entry_requirement'] = $this->language->get('entry_requirement');
        $data['entry_mandatory_products'] = $this->language->get('entry_mandatory_products');

        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_back'] = $this->language->get('button_back');
        $data['button_upload'] = $this->language->get('button_upload');
        $data['button_save'] = $this->language->get('button_save');

        $data['text_my_account'] = $this->language->get('text_my_account');
        $data['text_my_orders'] = $this->language->get('text_my_orders');
        $data['text_my_newsletter'] = $this->language->get('text_my_newsletter');
        $data['text_my_logout'] = $this->language->get('text_my_logout');
        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_password'] = $this->language->get('text_password');
        $data['text_address'] = $this->language->get('text_address');
        $data['text_wishlist'] = $this->language->get('text_wishlist');
        $data['text_order'] = $this->language->get('text_order');
        $data['text_download'] = $this->language->get('text_download');
        $data['text_reward'] = $this->language->get('text_reward');
        $data['text_return'] = $this->language->get('text_return');
        $data['text_credit'] = $this->language->get('text_credit');
        $data['text_newsletter'] = $this->language->get('text_newsletter');
        $data['text_logout'] = $this->language->get('text_logout');
        $data['text_signout'] = $this->language->get('text_signout');
        $data['text_recurring'] = $this->language->get('text_recurring');
        $data['text_membership'] = $this->language->get('text_membership');
        $data['text_change_password'] = $this->language->get('text_change_password');

        $data['button_become_member'] = $this->language->get('button_become_member');

        $data['label_name'] = $this->language->get('label_name');
        $data['label_contact_no'] = $this->language->get('label_contact_no');
        $data['label_address'] = $this->language->get('label_address');

        $data['edit'] = $this->url->link('account/edit', '', 'SSL');
        $data['password'] = $this->url->link('account/password', '', 'SSL');
        $data['address'] = $this->url->link('account/address', '', 'SSL');
        $data['wishlist'] = $this->url->link('account/wishlist');
        $data['order'] = $this->url->link('account/order', '', 'SSL');
        $data['download'] = $this->url->link('account/download', '', 'SSL');
        $data['return'] = $this->url->link('account/return', '', 'SSL');
        $data['credit'] = $this->url->link('account/credit', '', 'SSL');
        $data['pezesha'] = $this->url->link('account/pezesha', '', 'SSL');
        $data['newsletter'] = $this->url->link('account/newsletter', '', 'SSL');
        $data['logout'] = $this->url->link('account/logout', '', 'SSL');
        $data['recurring'] = $this->url->link('account/recurring', '', 'SSL');

        if ('POST' != $this->request->server['REQUEST_METHOD']) {
            $customer_info = $this->model_account_customer->getCustomerOtherInfo($this->customer->getId());
            // echo '<pre>';print_r($customer_info);exit;
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['location'])) {
            $data['error_location'] = $this->error['location'];
        } else {
            $data['error_location'] = '';
        }

        if (isset($this->error['requirement'])) {
            $data['error_requirement'] = $this->error['requirement'];
        } else {
            $data['error_requirement'] = '';
        }

        if (isset($this->error['mandatory_products'])) {
            $data['error_mandatory_products'] = $this->error['mandatory_products'];
        } else {
            $data['error_mandatory_products'] = '';
        }

        if (isset($this->request->post['location'])) {
            $data['location'] = $this->request->post['location'];
        } else {
            $data['location'] = $customer_info['location'];
        }

        if (isset($this->request->post['requirement'])) {
            $data['requirement'] = $this->request->post['requirement'];
        } else {
            $data['requirement'] = $customer_info['requirement_per_week'];
        }
        if (isset($this->request->post['mandatory_products'])) {
            $data['mandatory_products'] = $this->request->post['mandatory_products'];
        } else {
            $data['mandatory_products'] = $customer_info['mandatory_veg_fruits'];
        }

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['base'] = $server;

        $data['action'] = $this->url->link('account/profileinfo', '', 'SSL');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');

        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/information');

        $data['account_edit'] = $this->load->controller('account/edit');

        $data['home'] = $this->url->link('common/home/toHome');
        //$data['telephone'] =  $this->formatTelephone($this->customer->getTelephone());
        /* Added new params */
        $data['is_login'] = $this->customer->isLogged();
        $data['full_name'] = $this->customer->getFirstName();
        $data['text_my_cash'] = $this->language->get('text_my_cash');
        $data['text_my_wishlist'] = $this->language->get('text_my_wishlist');
        $data['label_my_address'] = $this->language->get('label_my_address');
        $data['contactus'] = $this->language->get('contactus');
        $data['text_cash'] = $this->language->get('text_cash');

        //echo "<pre>";print_r($data['telephone'] );die;
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/profileinfo.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/profileinfo.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/account/profileinfo.tpl', $data));
        }
    }

    protected function validate() {
        //print_r($this->request->post);die;
        $this->load->language('account/edit');

        if ((utf8_strlen(trim($this->request->post['location'])) < 1) || (utf8_strlen(trim($this->request->post['location'])) > 32)) {
            $this->error['location'] = $this->language->get('error_location');
        }

        if ((utf8_strlen(trim($this->request->post['requirement'])) < 1)) {
            $this->error['requirement'] = $this->language->get('error_requirement');
        }

        if ((utf8_strlen(trim($this->request->post['mandatory_products'])) < 1)) {
            $this->error['mandatory_products'] = $this->language->get('error_mandatory_products');
        }

        return !$this->error;
    }

}
