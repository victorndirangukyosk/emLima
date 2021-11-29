<?php

class ControllerShopperSetting extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->model('account/settings');

        $this->getForm();
    }

    protected function getForm()
    {
        $this->language->load('account/settings');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_contact_details'] = $this->language->get('tab_contact_details');
        $data['tab_bank_details'] = $this->language->get('tab_bank_details');

        $data['entry_username'] = $this->language->get('entry_username');
        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_tin_no'] = $this->language->get('entry_tin_no');
        $data['entry_mobile'] = $this->language->get('entry_mobile');
        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_city'] = $this->language->get('entry_city');
        $data['entry_address'] = $this->language->get('entry_address');
        $data['entry_ifsc_code'] = $this->language->get('entry_ifsc_code');
        $data['entry_bank_account_no'] = $this->language->get('entry_bank_account_no');
        $data['entry_password'] = $this->language->get('entry_password');
        $data['entry_confirm'] = $this->language->get('entry_confirm');
        $data['entry_vendor_group'] = $this->language->get('entry_vendor_group');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_captcha'] = $this->language->get('entry_captcha');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['username'])) {
            $data['error_username'] = $this->error['username'];
        } else {
            $data['error_username'] = '';
        }

        if (isset($this->error['firstname'])) {
            $data['error_firstname'] = $this->error['firstname'];
        } else {
            $data['error_firstname'] = '';
        }

        if (isset($this->error['lastname'])) {
            $data['error_lastname'] = $this->error['lastname'];
        } else {
            $data['error_lastname'] = '';
        }

        $data['action'] = $this->url->link('shopper/setting/update', 'token='.$this->session->data['token'], 'SSL');

        $vendor_info = $this->model_account_settings->getUser($this->user->getId());

        if (isset($this->request->post['username'])) {
            $data['username'] = $this->request->post['username'];
        } else {
            $data['username'] = $vendor_info['username'];
        }

        if (isset($this->request->post['tin_no'])) {
            $data['tin_no'] = $this->request->post['tin_no'];
        } else {
            $data['tin_no'] = $vendor_info['tin_no'];
        }

        if (isset($this->request->post['mobile'])) {
            $data['mobile'] = $this->request->post['mobile'];
        } else {
            $data['mobile'] = $vendor_info['mobile'];
        }

        if (isset($this->request->post['telephone'])) {
            $data['telephone'] = $this->request->post['telephone'];
        } else {
            $data['telephone'] = $vendor_info['telephone'];
        }

//        if (isset($this->request->post['state'])) {
//            $data['state'] = $this->request->post['state'];
//        } else {
//            $data['state'] = $vendor_info['state'];
//        }

        if (isset($this->request->post['city'])) {
            $data['city'] = $this->request->post['city'];
        } else {
            $city = $this->model_account_settings->getCity($vendor_info['city_id']);
            $data['city'] = $city['city'];
        }

        if (isset($this->request->post['address'])) {
            $data['address'] = $this->request->post['address'];
        } else {
            $data['address'] = $vendor_info['address'];
        }

        if (isset($this->request->post['ifsc_code'])) {
            $data['ifsc_code'] = $this->request->post['ifsc_code'];
        } else {
            $data['ifsc_code'] = $vendor_info['ifsc_code'];
        }

        if (isset($this->request->post['bank_acc_no'])) {
            $data['bank_acc_no'] = $this->request->post['bank_acc_no'];
        } else {
            $data['bank_acc_no'] = $vendor_info['bank_acc_no'];
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['firstname'])) {
            $data['firstname'] = $this->request->post['firstname'];
        } else {
            $data['firstname'] = $vendor_info['firstname'];
        }

        if (isset($this->request->post['lastname'])) {
            $data['lastname'] = $this->request->post['lastname'];
        } else {
            $data['lastname'] = $vendor_info['lastname'];
        }

        if (isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } else {
            $data['email'] = $vendor_info['email'];
        }

        $data['header'] = $this->load->controller('shopper/common/header');
        $data['footer'] = $this->load->controller('shopper/common/footer');

        $this->response->setOutput($this->load->view('shopper/common/settings.tpl', $data));
    }

    public function password()
    {
        $this->language->load('account/settings');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/settings');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validatePassword()) {
            $this->model_account_settings->password($this->request->post);

            $this->session->data['success'] = 'Success: Password updated successfully!';

            $this->response->redirect($this->url->link('shopper/setting/password', 'token='.$this->session->data['token'], 'SSL'));
        }

        $this->getPasswordForm();
    }

    public function getPasswordForm()
    {
        $data['action'] = $this->url->link('shopper/setting/password', 'token='.$this->session->data['token'], 'SSL');

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

        if (isset($this->error['password'])) {
            $data['error_password'] = $this->error['password'];
        } else {
            $data['error_password'] = '';
        }

        if (isset($this->error['confirm'])) {
            $data['error_confirm'] = $this->error['confirm'];
        } else {
            $data['error_confirm'] = '';
        }

        if (isset($this->request->post['password'])) {
            $data['password'] = $this->request->post['password'];
        } else {
            $data['password'] = '';
        }

        if (isset($this->request->post['confirm'])) {
            $data['confirm'] = $this->request->post['confirm'];
        } else {
            $data['confirm'] = '';
        }

        $data['entry_password'] = $this->language->get('entry_password');
        $data['entry_confirm_pswd'] = $this->language->get('entry_confirm_pswd');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_save'] = $this->language->get('button_save');
        $data['button_submit'] = $this->language->get('button_submit');

        $data['heading_title'] = 'Change password';

        $data['header'] = $this->load->controller('shopper/common/header');
        $data['footer'] = $this->load->controller('shopper/common/footer');

        $this->response->setOutput($this->load->view('shopper/common/password.tpl', $data));
    }

    public function update()
    {
        $this->load->model('account/settings');

        $this->language->load('account/settings');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $this->model_account_settings->update($this->request->post);

            $this->session->data['success'] = 'Success: Account updated successfully!';

            $this->response->redirect($this->url->link('shopper/setting', 'token='.$this->session->data['token'], 'SSL'));
        }

        $this->getForm();
    }

    public function validatePassword()
    {
        if ($this->request->post['password'] || (!isset($this->request->get['user_id']))) {
            if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
                $this->error['password'] = $this->language->get('error_password');
            }

            if ($this->request->post['password'] != $this->request->post['confirm']) {
                $this->error['confirm'] = $this->language->get('error_confirm');
            }
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    public function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'shopper/setting')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['username']) < 3) || (utf8_strlen($this->request->post['username']) > 20)) {
            $this->error['username'] = $this->language->get('error_username');
        }

        $this->load->model('user/user');

        $user_info = $this->model_user_user->getUserByUsername($this->request->post['username']);

        if ($user_info && ($this->user->getId() != $user_info['user_id'])) {
            $this->error['warning'] = $this->language->get('error_exists');
        }

        if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
            $this->error['firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
            $this->error['lastname'] = $this->language->get('error_lastname');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
}
