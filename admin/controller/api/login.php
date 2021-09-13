<?php

require_once DIR_SYSTEM.'vendor/firebase/php-jwt/vendor/autoload.php';
use Firebase\JWT\JWT;

class ControllerApiLogin extends Controller {

    private $error = [];

    public function index() {

        $json = [];
        $json['success'] = '';
        $json['statuscode'] = 200;
        $json['message'] = '';
        try{
        $this->load->language('common/login');
         // Delete old login so not to cause any issues if there is an error
        //echo $this->session->data['api_id'];
        // unset($this->session->data['token']);

        $shopper_group_id = $this->config->get('config_shopper_group_ids');

        if ($this->user->isLogged() && isset($this->request->get['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
            if ($shopper_group_id == $this->user->getGroupId()) {
                // $this->response->redirect($this->url->link('shopper/request', 'token=' . $this->session->data['token'], 'SSL'));
                $json['success'] = False;
                $json['statuscode'] = 300;
                $json['message'] = 'Vendor Login not yet implemented';
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($json));
                $this->response->output();
                die();
            } else {
                // $this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
            }
        }

        //  echo "<pre>";print_r($this->session->data['token']);die;        
        // $api_info = $this->model_account_api->login($this->request->post['username'], $this->request->post['password']);
         
        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validate()) {
            // echo "<pre>";print_r($this->request->post);die;

            $this->session->data['token'] = md5(mt_rand());
            $json['token'] =  $this->session->data['token'];
            $this->session->data['admintoken'] = $this->session->data['token']; //this name is used in API

            if (!empty($this->request->post['lang'])) {
                $this->session->data['language'] = $this->request->post['lang'];
            }

            // if ($this->config->get('config_sec_admin_login')) {
            //     $mailData = [
            //         'username' => $this->request->post['username'],
            //         'store_name' => $this->config->get('config_name'),
            //         'ip_address' => $this->request->server['REMOTE_ADDR'],
            //     ];

            //     $subject = $this->emailtemplate->getSubject('Login', 'admin_1', $mailData);
            //     $message = $this->emailtemplate->getMessage('Login', 'admin_1', $mailData);

            //     try {
            //         $mail = new Mail($this->config->get('config_mail'));
            //         $mail->setTo($this->config->get('config_sec_admin_login'));
            //         $mail->setFrom($this->config->get('config_from_email'));
            //         $mail->setSender($this->config->get('config_name'));
            //         $mail->setSubject($subject);
            //         $mail->setHtml($message);
            //         $mail->send();
            //     } catch (Exception $e) {
                    
            //     }
            // }
            // Add to activity log
            $log = new Log('error.log');
            $this->load->model('user/user_activity');

            $activity_data = [
                'user_id' => $this->user->getId(),
                'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
                'user_group_id' => $this->user->getGroupId(),
            ];
            $log->write('user login');

            $this->model_user_user_activity->addActivity('login', $activity_data);

            $log->write('user login');

            // if ($shopper_group_id == $this->user->getGroupId()) {
            //     $this->response->redirect($this->url->link('shopper/request', 'token=' . $this->session->data['token'], 'SSL'));
            // } elseif ($this->user->getGroupId() == 21) {
            //     $this->response->redirect($this->url->link('setting/jobposition', 'token=' . $this->session->data['token'], 'SSL'));
            // } elseif (isset($this->request->post['redirect']) && (0 === strpos($this->request->post['redirect'], HTTP_SERVER) || 0 === strpos($this->request->post['redirect'], HTTPS_SERVER))) {
            //     $this->response->redirect($this->request->post['redirect'] . '&token=' . $this->session->data['token']);
            // } else {
            //     $this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
            // }
            $json['success'] = TRUE;
            $json['message'] = 'User Logged in';


            // if ((isset($this->session->data['token']) && !isset($this->request->get['token'])) || ((isset($this->request->get['token']) && (isset($this->session->data['token']) && ($this->request->get['token'] != $this->session->data['token']))))) {
            // $this->error['warning'] = $this->language->get('error_token');
            // }

            // if (isset($this->error['warning'])) {
            //     $data['error_warning'] = $this->error['warning'];
            // } else {
            //     $data['error_warning'] = '';
            // }

        if (isset($this->session->data['success'])) {
            // $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            // $data['success'] = '';
        }

        $data['action'] = $this->url->link('common/login', '', 'SSL');

        // if (isset($this->request->post['username'])) {
        //     $data['username'] = $this->request->post['username'];
        // } else {
        //     $data['username'] = '';
        // }

        // if (isset($this->request->post['password'])) {
        //     $data['password'] = $this->request->post['password'];
        // } else {
        //     $data['password'] = '';
        // }

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

        // if ($this->config->get('config_password')) {
        //     $data['forgotten'] = $this->url->link('common/forgotten', '', 'SSL');
        // } else {
        //     $data['forgotten'] = '';
        // }

        $this->load->model('tool/image');

        if ($this->config->get('config_image') && is_file(DIR_IMAGE . $this->config->get('config_image'))) {
            $data['thumb'] = $this->model_tool_image->resize($this->config->get('config_image'), 200, 110);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 200, 110);
        }

        $data['store'] = [
            'name' => $this->config->get('config_name'),
            'href' => HTTP_CATALOG,
        ];
        // $data['farmer_login'] = $this->url->link('common/farmer', '', 'SSL');

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


        }
        else{

            $json['success'] = FALSE;
            $json['message'] = 'Username and password didnt match';
        }
 

        }
        catch(exception $ex)
        {
            $json['success'] = FALSE;
            $json['message'] = $ex;
        }
        finally{
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));

        } 
 
    }

    protected function validate() {
        if (!isset($this->request->post['username']) || !isset($this->request->post['password']) || !$this->user->login($this->request->post['username'], $this->request->post['password'])) {
            $this->error['warning'] = $this->language->get('error_login');
        }

        // echo $this->error['warning'];die;


        return !$this->error;
    }
  

}
