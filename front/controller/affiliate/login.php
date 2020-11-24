<?php

class ControllerAffiliateLogin extends Controller
{
    private $error = [];

    public function index()
    {
        if ($this->affiliate->isLogged()) {
            $this->response->redirect($this->url->link('affiliate/account', '', 'SSL'));
        }

        $this->load->language('affiliate/login');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('affiliate/affiliate');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && isset($this->request->post['email']) && isset($this->request->post['password']) && $this->validate()) {
            // Add to activity log
            $this->load->model('affiliate/activity');

            $activity_data = [
                'affiliate_id' => $this->affiliate->getId(),
                'name' => $this->affiliate->getFirstName().' '.$this->affiliate->getLastName(),
            ];

            $this->model_affiliate_activity->addActivity('login', $activity_data);

            if (isset($this->request->post['redirect']) && (false !== strpos($this->request->post['redirect'], $this->config->get('config_url')) || false !== strpos($this->request->post['redirect'], $this->config->get('config_ssl')))) {
                $this->response->redirect(str_replace('&amp;', '&', $this->request->post['redirect']));
            } else {
                $this->response->redirect($this->url->link('affiliate/account', '', 'SSL'));
            }
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('affiliate/account', '', 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_login'),
            'href' => $this->url->link('affiliate/login', '', 'SSL'),
        ];

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_description'] = sprintf($this->language->get('text_description'), $this->config->get('config_name'), $this->config->get('config_name'), $this->config->get('config_affiliate_commission').'%');
        $data['text_new_affiliate'] = $this->language->get('text_new_affiliate');
        $data['text_register_account'] = $this->language->get('text_register_account');
        $data['text_returning_affiliate'] = $this->language->get('text_returning_affiliate');
        $data['text_i_am_returning_affiliate'] = $this->language->get('text_i_am_returning_affiliate');
        $data['text_forgotten'] = $this->language->get('text_forgotten');

        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_password'] = $this->language->get('entry_password');

        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_login'] = $this->language->get('button_login');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['action'] = $this->url->link('affiliate/login', '', 'SSL');
        $data['register'] = $this->url->link('affiliate/register', '', 'SSL');
        $data['forgotten'] = $this->url->link('affiliate/forgotten', '', 'SSL');

        if (isset($this->request->post['redirect'])) {
            $data['redirect'] = $this->request->post['redirect'];
        } elseif (isset($this->session->data['redirect'])) {
            $data['redirect'] = $this->session->data['redirect'];

            unset($this->session->data['redirect']);
        } else {
            $data['redirect'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } else {
            $data['email'] = '';
        }

        if (isset($this->request->post['password'])) {
            $data['password'] = $this->request->post['password'];
        } else {
            $data['password'] = '';
        }

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');
        
        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }
        
        if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
            //$data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'),200,110);
            $data['logo'] = $server . 'image/' . $this->config->get('config_logo');
        } else {
            $data['logo'] = 'assets/img/logo.svg';
        }
        
        $data['store_name'] = $this->config->get('config_name');

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/affiliate/login.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/affiliate/login.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/affiliate/login.tpl', $data));
        }
    }

    protected function validate()
    {
        // Check how many login attempts have been made.
        $login_info = $this->model_affiliate_affiliate->getLoginAttempts($this->request->post['email']);

        if ($login_info && ($login_info['total'] >= $this->config->get('config_login_attempts')) && strtotime('-1 hour') < strtotime($login_info['date_modified'])) {
            $this->error['warning'] = $this->language->get('error_attempts');
        }

        // Check if affiliate has been approved.
        $affiliate_info = $this->model_affiliate_affiliate->getAffiliateByEmail($this->request->post['email']);

        if ($affiliate_info && !$affiliate_info['approved']) {
            $this->error['warning'] = $this->language->get('error_approved');
        }

        if (!$this->error) {
            if (!$this->affiliate->login($this->request->post['email'], $this->request->post['password'])) {
                $this->error['warning'] = $this->language->get('error_login');

                $this->model_affiliate_affiliate->addLoginAttempt($this->request->post['email']);
            } else {
                $this->model_affiliate_affiliate->deleteLoginAttempts($this->request->post['email']);
            }
        }

        return !$this->error;
    }
}
