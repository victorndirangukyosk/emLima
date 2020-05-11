<?php

class ControllerCommonLogin extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('common/login');

        $this->document->setTitle($this->language->get('heading_title'));

        $shopper_group_id = $this->config->get('config_shopper_group_ids');
        
        if ($this->user->isLogged() && isset($this->request->get['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
            if($shopper_group_id == $this->user->getGroupId()) {
                $this->response->redirect($this->url->link('shopper/request', 'token=' . $this->session->data['token'], 'SSL'));
            } else {
                $this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
            }
        }

        //echo "<pre>";print_r($this->request->post);die;
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

            //echo "<pre>";print_r($this->request->post);die;

            $this->session->data['token'] = md5(mt_rand());

            if (!empty($this->request->post['lang'])) {
                $this->session->data['language'] = $this->request->post['lang'];
            }

            if ($this->config->get('config_sec_admin_login')) {

                $mailData = array(
                    'username' => $this->request->post['username'],
                    'store_name' => $this->config->get('config_name'),
                    'ip_address' => $this->request->server['REMOTE_ADDR'],
                );

                $subject = $this->emailtemplate->getSubject('Login', 'admin_1', $mailData);
                $message = $this->emailtemplate->getMessage('Login', 'admin_1', $mailData);

                try {

                    $mail = new Mail($this->config->get('config_mail'));
                    $mail->setTo($this->config->get('config_sec_admin_login'));
                    $mail->setFrom($this->config->get('config_from_email'));
                    $mail->setSender($this->config->get('config_name'));
                    $mail->setSubject($subject);
                    $mail->setHtml($message);
                    $mail->send();  
                    
                } catch (Exception $e) {
                    
                }
            }

            if($shopper_group_id == $this->user->getGroupId()) {
                $this->response->redirect($this->url->link('shopper/request', 'token=' . $this->session->data['token'], 'SSL'));
            } elseif (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], HTTP_SERVER) === 0 || strpos($this->request->post['redirect'], HTTPS_SERVER) === 0 )) {
                $this->response->redirect($this->request->post['redirect'] . '&token=' . $this->session->data['token']);
            } else {
                $this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
            }
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_login'] = $this->language->get('text_login');
        $data['text_forgotten'] = $this->language->get('text_forgotten');

        $data['entry_username'] = $this->language->get('entry_username');
        $data['entry_password'] = $this->language->get('entry_password');
        $data['entry_language'] = $this->language->get('entry_language');

        $data['button_login'] = $this->language->get('button_login');

        if ((isset($this->session->data['token']) && !isset($this->request->get['token'])) || ((isset($this->request->get['token']) && (isset($this->session->data['token']) && ($this->request->get['token'] != $this->session->data['token']))))) {
            $this->error['warning'] = $this->language->get('error_token');
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $data['action'] = $this->url->link('common/login', '', 'SSL');

        if (isset($this->request->post['username'])) {
            $data['username'] = $this->request->post['username'];
        } else {
            $data['username'] = '';
        }

        if (isset($this->request->post['password'])) {
            $data['password'] = $this->request->post['password'];
        } else {
            $data['password'] = '';
        }

        if (isset($this->request->get['path'])) {
            $path = $this->request->get['path'];

            unset($this->request->get['path']);
            unset($this->request->get['token']);

            $url = '';

            if ($this->request->get) {
                $url .= http_build_query($this->request->get);
            }

            $data['redirect'] = $this->url->link($path, $url, 'SSL');
        } else {
            $data['redirect'] = '';
        }

        if ($this->config->get('config_password')) {
            $data['forgotten'] = $this->url->link('common/forgotten', '', 'SSL');
        } else {
            $data['forgotten'] = '';
        }

        $this->load->model('tool/image');

        if ($this->config->get('config_image') && is_file(DIR_IMAGE . $this->config->get('config_image'))) {
            $data['thumb'] = $this->model_tool_image->resize($this->config->get('config_image'), 200, 110);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 200, 110);
        }

        $data['store'] = array(
            'name' => $this->config->get('config_name'),
            'href' => HTTP_CATALOG
        );

        // Language list
        $this->load->model('localisation/language');

        $total_languages = $this->model_localisation_language->getTotalLanguages();
        if ($total_languages > 1) {
            $data['languages'] = $this->model_localisation_language->getLanguages();
        } else {
            $data['languages'] = '';
            $this->session->data['language'] = $this->config->get('config_admin_language');
        }

        $data['config_admin_language'] = $this->config->get('config_admin_language');

        $data['header'] = $this->load->controller('common/header');
        $data['footer'] = $this->load->controller('common/footer');
                
        $this->response->setOutput($this->load->view('common/login.tpl', $data));
    }

    protected function validate() {
        if (!isset($this->request->post['username']) || !isset($this->request->post['password']) || !$this->user->login($this->request->post['username'], $this->request->post['password'])) {
            $this->error['warning'] = $this->language->get('error_login');
        }

        return !$this->error;
    }

    public function check() {
        $path = '';

        if (isset($this->request->get['path'])) {
            $part = explode('/', $this->request->get['path']);

            if (isset($part[0])) {
                $path .= $part[0];
            }

            if (isset($part[1])) {
                $path .= '/' . $part[1];
            }
        }

        $ignore = array(
            'common/login',
            'common/forgotten',
            'common/reset'
        );

        if (!$this->user->isLogged() && !in_array($path, $ignore)) {
            return new Action('common/login');
        }

        if (isset($this->request->get['path'])) {
            $ignore = array(
                'common/login',
                'common/logout',
                'common/forgotten',
                'common/reset',
                'error/not_found',
                'error/permission'
            );

            if (!in_array($path, $ignore) && (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token']))) {
                return new Action('common/login');
            }
        } else {
            if (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
                return new Action('common/login');
            }
        }
    }
}
