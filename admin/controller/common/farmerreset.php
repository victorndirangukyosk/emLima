<?php

class ControllerCommonFarmerReset extends Controller
{
    private $error = [];

    public function index()
    {
        /*if ($this->user->isLogged() && isset($this->request->get['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
            $this->response->redirect($this->url->link('common/dashboard', '', 'SSL'));
        }*/

        if (!$this->config->get('config_password')) {
            $this->response->redirect($this->url->link('common/farmer', '', 'SSL'));
        }

        if (isset($this->request->get['code'])) {
            $code = $this->request->get['code'];
        } else {
            $code = '';
        }

        $this->load->model('user/farmer');
        //echo "<pre>";print_r("ew");die;
        $user_info = $this->model_user_farmer->getFarmerByCode($code);

        /*echo "<pre>";print_r($user_info);
            die;*/

        if ($user_info) {
            $this->load->language('common/reset');

            $this->document->setTitle($this->language->get('heading_title'));

            //echo "<pre>";print_r("1");

            if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validate()) {
                /*echo "<pre>";print_r($this->request->post);
            die;*/
                $this->model_user_farmer->editPassword($user_info['farmer_id'], trim($this->request->post['password']));

                $this->session->data['success'] = $this->language->get('text_success');

                $this->response->redirect($this->url->link('common/farmer', '', 'SSL'));
            }

            /*echo "<pre>";print_r($user_info);
            die;*/

            $data['heading_title'] = $this->language->get('heading_title');

            $data['text_password'] = $this->language->get('text_password');

            $data['entry_password'] = $this->language->get('entry_password');
            $data['entry_confirm'] = $this->language->get('entry_confirm');

            $data['button_save'] = $this->language->get('button_save');
            $data['button_cancel'] = $this->language->get('button_cancel');

            $data['breadcrumbs'] = [];

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('sale/farmer_transactions', '', 'SSL'),
            ];

            $data['breadcrumbs'][] = [
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('common/farmerreset', '', 'SSL'),
            ];

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

            $data['action'] = $this->url->link('common/farmerreset', 'code='.$code, 'SSL');

            $data['cancel'] = $this->url->link('common/farmer', '', 'SSL');

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

            $data['header'] = $this->load->controller('common/header');
            $data['footer'] = $this->load->controller('common/footer');

            $this->response->setOutput($this->load->view('common/farmerreset.tpl', $data));
        } else {
            $this->load->model('setting/setting');

            $this->model_setting_setting->editSettingValue('config', 'config_password', '0');

            return new Action('common/farmer');
        }
    }

    protected function validate()
    {
        if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
            $this->error['password'] = $this->language->get('error_password');
        }

        if ($this->request->post['confirm'] != $this->request->post['password']) {
            $this->error['confirm'] = $this->language->get('error_confirm');
        }

        return !$this->error;
    }
}
