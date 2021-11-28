<?php

class ControllerAccountSettings extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->model('account/settings');
        $this->load->model('vendor/vendor');

        $this->getForm();
    }

    protected function getForm()
    {
        $this->document->addScript('https://maps.google.com/maps/api/js?key='.$this->config->get('config_google_api_key').'&sensor=false&libraries=places');
        $this->document->addScript('ui/javascript/map-picker/js/locationpicker.jquery.js?v=2.2');

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

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token='.$this->session->data['token'], 'SSL'),
            'separator' => false,
        ];

        $data['breadcrumbs'][] = [
            'text' => 'Account settings',
            'href' => $this->url->link('account/settings', 'token='.$this->session->data['token'], 'SSL'),
            'separator' => ' :: ',
        ];

        $data['action'] = $this->url->link('account/settings/update', 'token='.$this->session->data['token'], 'SSL');

        $vendor_info = $this->model_account_settings->getUser($this->user->getId());

        //echo "<pre>";print_r($vendor_info);die;
        $vendor_bank_info = $this->model_vendor_vendor->getVendorBank($this->user->getId());

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

//                if (isset($this->request->post['state'])) {
        //			$data['state'] = $this->request->post['state'];
        //		}  else {
        //			$data['state'] = $vendor_info['state'];
        //		}

        if (isset($this->request->post['city'])) {
            $data['city'] = $this->request->post['city'];
        } else {
            $city = $this->model_account_settings->getCity($vendor_info['city_id']);
            $data['city'] = $city['name'];
        }

        if (isset($this->request->post['address'])) {
            $data['address'] = $this->request->post['address'];
        } else {
            $data['address'] = $vendor_info['address'];
        }

        if (isset($this->request->post['latitude'])) {
            $data['latitude'] = $this->request->post['latitude'];
        } elseif (!empty($vendor_info)) {
            $data['latitude'] = $vendor_info['latitude'];
        } else {
            $data['latitude'] = '';
        }

        if (isset($this->request->post['longitude'])) {
            $data['longitude'] = $this->request->post['longitude'];
        } elseif (!empty($vendor_info)) {
            $data['longitude'] = $vendor_info['longitude'];
        } else {
            $data['longitude'] = '';
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

        /*Bank details*/

        if (isset($this->error['bank_account_number'])) {
            $data['error_bank_account_number'] = $this->error['bank_account_number'];
        } else {
            $data['error_bank_account_number'] = '';
        }

        if (isset($this->error['bank_account_name'])) {
            $data['error_bank_account_name'] = $this->error['bank_account_name'];
        } else {
            $data['error_bank_account_name'] = '';
        }

        if (isset($this->error['bank_name'])) {
            $data['error_bank_name'] = $this->error['bank_name'];
        } else {
            $data['error_bank_name'] = '';
        }

        if (isset($this->error['bank_branch_name'])) {
            $data['error_bank_branch_name'] = $this->error['bank_branch_name'];
        } else {
            $data['error_bank_branch_name'] = '';
        }

        /*end*/

        /*bank */

        if (isset($this->request->post['bank_account_number'])) {
            $data['bank_account_number'] = $this->request->post['bank_account_number'];
        } elseif (!empty($vendor_bank_info)) {
            $data['bank_account_number'] = $vendor_bank_info['bank_account_number'];
        } else {
            $data['bank_account_number'] = '';
        }

        if (isset($this->request->post['bank_account_name'])) {
            $data['bank_account_name'] = $this->request->post['bank_account_name'];
        } elseif (!empty($vendor_bank_info)) {
            $data['bank_account_name'] = $vendor_bank_info['bank_account_name'];
        } else {
            $data['bank_account_name'] = '';
        }

        if (isset($this->request->post['bank_name'])) {
            $data['bank_name'] = $this->request->post['bank_name'];
        } elseif (!empty($vendor_bank_info)) {
            $data['bank_name'] = $vendor_bank_info['bank_name'];
        } else {
            $data['bank_name'] = '';
        }

        if (isset($this->request->post['bank_branch_name'])) {
            $data['bank_branch_name'] = $this->request->post['bank_branch_name'];
        } elseif (!empty($vendor_bank_info)) {
            $data['bank_branch_name'] = $vendor_bank_info['bank_branch_name'];
        } else {
            $data['bank_branch_name'] = '';
        }

        if (isset($this->request->post['bank_account_type'])) {
            $data['bank_account_type'] = $this->request->post['bank_account_type'];
        } elseif (!empty($vendor_bank_info)) {
            $data['bank_account_type'] = $vendor_bank_info['bank_account_type'];
        } else {
            $data['bank_account_type'] = '';
        }

        /*bank end*/

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

        //echo "<pre>";print_r($data);die;
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $this->response->setOutput($this->load->view('account/settings.tpl', $data));
    }

    public function password()
    {
        $this->language->load('account/settings');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/settings');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validatePassword()) {
            $this->model_account_settings->password($this->request->post);

            $this->session->data['success'] = 'Success: Password updated successfully!';

            $this->response->redirect($this->url->link('account/settings/password', 'token='.$this->session->data['token'], 'SSL'));
        }

        $this->getPasswordForm();
    }

    public function getPasswordForm()
    {
        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token='.$this->session->data['token'], 'SSL'),
            'separator' => false,
        ];

        $data['breadcrumbs'][] = [
            'text' => 'Change Password',
            'href' => $this->url->link('account/settings/password', 'token='.$this->session->data['token'], 'SSL'),
            'separator' => ' :: ',
        ];

        $data['action'] = $this->url->link('account/settings/password', 'token='.$this->session->data['token'], 'SSL');

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

        $data['column_password'] = $this->language->get('column_password');
        $data['column_confirm_pswd'] = $this->language->get('column_confirm_pswd');

        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_save'] = $this->language->get('button_save');

        $data['heading_title'] = 'Change password';

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('account/password.tpl', $data));
    }

    public function update()
    {
        $this->load->model('account/settings');

        $this->language->load('account/settings');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            //echo "<pre>";print_r("Re");die;
            $this->model_account_settings->update($this->request->post);

            $this->session->data['success'] = 'Success: Account updated successfully!';

            $this->response->redirect($this->url->link('account/settings', 'token='.$this->session->data['token'], 'SSL'));
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
        if (!$this->user->hasPermission('modify', 'account/settings')) {
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
