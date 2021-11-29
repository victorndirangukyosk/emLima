<?php

class ControllerCommonComingSoon extends Controller
{
    public function index()
    {
        if ($this->config->get('config_coming_soon')) {
            $path = '';

            if (isset($this->request->get['path'])) {
                $part = explode('/', $this->request->get['path']);

                if (isset($part[0])) {
                    $path .= $part[0];
                }
            }

            // Show site if logged in as admin
            $this->load->library('user');

            $this->user = new User($this->registry);

            if (('payment' != $path && 'api' != $path) && !$this->user->isLogged()) {
                return new Action('common/maintenance/info');
            }
        }
    }

    public function info()
    {
        $log = new Log('error.log');

        $log->write('info fn');
        $this->load->language('common/coming_soon');

        $data['heading_title'] = $this->config->get('config_meta_title');

        $this->document->setTitle($data['heading_title']);

        //echo "<pre>";print_r($data);die;
        $data['description'] = $this->config->get('config_meta_description');
        $data['keywords'] = $this->config->get('config_meta_keyword');

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $this->load->model('tool/image');

        if (isset($this->request->post['config_image']) && is_file(DIR_IMAGE.$this->request->post['config_image'])) {
            $data['thumb'] = $this->model_tool_image->resize($this->request->post['config_image'], 100, 100);
        } elseif ($this->config->get('config_image') && is_file(DIR_IMAGE.$this->config->get('config_image'))) {
            $data['thumb'] = $this->model_tool_image->resize($this->config->get('config_image'), 100, 100);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        if (is_file(DIR_IMAGE.$this->config->get('config_icon'))) {
            $data['icon'] = $server.'image/'.$this->config->get('config_icon');
        } else {
            $data['icon'] = '';
        }

        if (is_file(DIR_IMAGE.$this->config->get('config_logo'))) {
            //817 × 262
            $data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'), 208, 90);
        // $server . 'image/' . $this->config->get('config_logo');
        } else {
            $data['logo'] = $this->model_tool_image->resize('no_image.png', 208, 90);
        }

        $data['shopper_link'] = $this->config->get('config_shopper_link');

        $data['facebook'] = $this->config->get('config_facebook');
        $data['twitter'] = $this->config->get('config_twitter');
        $data['google'] = $this->config->get('config_google');
        $data['play_store'] = $this->config->get('config_android_app_link');
        $data['app_store'] = $this->config->get('config_apple_app_link');

        $data['youtube'] = $this->config->get('config_youtube');
        $data['instagram'] = $this->config->get('config_instagram');
        $data['mail_to'] = $this->config->get('config_email');
        //$data['mail_to'] = 'info@gatoo.be';

        //echo "<pre>";print_r($data);die;
        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_coming_soon'),
            'href' => $this->url->link('common/coming_soon'),
        ];

        $data['message'] = $this->language->get('text_message');
        $data['text_invalid_email'] = $this->language->get('text_invalid_email');

        $data['text_heading'] = $this->language->get('text_heading');

        $data['text_subheading'] = sprintf($this->language->get('text_subheading'), $this->config->get('config_name'));
        $data['text_your_email'] = $this->language->get('text_your_email');
        $data['text_sen'] = $this->language->get('text_sen');
        $data['text_sending'] = $this->language->get('text_sending');

        $data['text_become_shopper'] = $this->language->get('text_become_shopper');
        $data['text_or'] = $this->language->get('text_or');
        $data['text_follow_us'] = $this->language->get('text_follow_us');
        $data['text_door_step_delivery'] = $this->language->get('text_door_step_delivery');
        $data['text_subscribe_to_know'] = $this->language->get('text_subscribe_to_know');
        $data['text_interested_to_earn'] = $this->language->get('text_interested_to_earn');

        $data['base'] = $server;

        //$data['language'] = $this->load->controller('common/language');
        $this->load->model('localisation/language');

        $data['languages'] = [];

        if (isset($this->session->data['language'])) {
            $data['config_language'] = $this->session->data['language'];
        } else {
            $data['config_language'] = 'pt-BR';
        }

        $data['action'] = $this->url->link('common/language/language');
        $data['send_mail_action'] = $this->url->link('common/coming_soon/send_mail');

        $url_data = $this->request->get;
        unset($url_data['lang']);
        unset($url_data['_path_']);

        if (isset($this->session->data['message'])) {
            $data['message'] = $this->session->data['message'];

            unset($this->session->data['message']);
        } else {
            $data['message'] = null;
        }

        $data['redirect'] = $server;

        $results = $this->model_localisation_language->getLanguages();

        //echo "<pre>";print_r($data['config_language']);die;
        foreach ($results as $result) {
            if ($result['status']) {
                $data['languages'][] = [
                    'name' => $result['name'],
                    'code' => $result['code'],
                ];
            }
        }

        //$data['footer'] = $this->load->controller('common/footer');
        $data['language'] = $this->load->controller('common/language');

        //echo "<pre>";print_r($data);die;

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/common/coming_soon.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/common/coming_soon.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/common/coming_soon.tpl', $data));
        }
    }

    public function send_mail()
    {
        $log = new Log('error.log');

        $log->write('send_mail');

        $data['status'] = false;
        $data['message'] = $this->language->get('text_error');

        //$data['message'] = $this->language->get('text_success');
        if (('POST' == $this->request->server['REQUEST_METHOD']) && isset($this->request->post['email'])) {
            //send mail
            //echo "<pre>";print_r("er");die;
            $log->write('in if coming');
            $log->write($this->request->post);

            //$userData['name'] = "abhishek";
            $userData['website_link'] = 'www.gatoo.be';
            $userData['email_address'] = $this->request->post['email'];

            $subject = $this->emailtemplate->getSubject('comingsoon', 'comingsoon_1', $userData);

            $log->write('2');
            $message = $this->emailtemplate->getMessage('comingsoon', 'comingsoon_1', $userData);

            $mail = new mail($this->config->get('config_mail'));

            $mail->setTo($this->request->post['email']);

            $mail->setFrom($this->config->get('config_from_email'));

            $mail->setSender($this->config->get('config_name'));

            $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));

            $mail->setHtml(html_entity_decode(strip_tags($message), ENT_QUOTES, 'UTF-8'));

            if ($mail->send()) {
                $log->write('end true');
                $data['status'] = true;
                $data['message'] = $this->language->get('text_success');
            }

            /*Admin mail sending*/
            //A New subscriber has signed up with following details:
            //Email : {email_address}

            $adminSubject = $this->emailtemplate->getSubject('comingsoon', 'comingsoon_2', $userData);

            $adminMessage = $this->emailtemplate->getMessage('comingsoon', 'comingsoon_2', $userData);
            $log->write('adminMessage');
            $log->write($adminMessage);

            $mail->setSubject(html_entity_decode($adminSubject, ENT_QUOTES, 'UTF-8'));

            $mail->setHtml(html_entity_decode(strip_tags($adminMessage), ENT_QUOTES, 'UTF-8'));

            $mail->setTo($this->config->get('config_email'));

            if ($mail->send()) {
                $log->write('end true');
                $data['status'] = true;
                $data['message'] = $this->language->get('text_success');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }
}
