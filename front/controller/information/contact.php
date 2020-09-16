<?php

class ControllerInformationContact extends Controller
{
    private $error = [];

    public function index()
    {
        $log = new Log('error.log');
        $log->write('11');
        $this->load->language('information/contact');

        //$this->document->setTitle($this->language->get('heading_title'));
        $this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_checkout.css');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->request->isAjax() && $this->validate()) {
            $log->write('12');
            //$this->request->post['mobile'] = $this->request->post['telephone'];
            $subject = $this->emailtemplate->getSubject('Contact', 'contact_1', $this->request->post);
            $message = $this->emailtemplate->getMessage('Contact', 'contact_1', $this->request->post);

            $mail = new Mail($this->config->get('config_mail'));
            $mail->setTo($this->config->get('config_email'));
            $mail->setFrom($this->config->get('config_from_email'));
            $mail->setSender($this->request->post['name']);
            $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
            $mail->setHtml(html_entity_decode(strip_tags($message), ENT_QUOTES, 'UTF-8'));
            $mail->send();

            $data['status'] = true;
            $data['redirect'] = $this->url->link('account/account', '', 'SSL');
            $data['text_message'] = $this->language->get('text_success_contact');

            if ($this->request->isAjax()) {
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($data));
            }

            //$this->response->redirect($this->url->link('information/contact/success'));
        }

        $log->write('13');

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('information/contact'),
        ];

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_location'] = $this->language->get('text_location');
        $data['text_store'] = $this->language->get('text_store');
        $data['text_contact'] = $this->language->get('text_contact');
        $data['text_address'] = $this->language->get('text_address');
        $data['text_telephone'] = $this->language->get('text_telephone');
        $data['text_fax'] = $this->language->get('text_fax');
        $data['text_open'] = $this->language->get('text_open');
        $data['text_comment'] = $this->language->get('text_comment');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_enquiry'] = $this->language->get('entry_enquiry');

        $data['button_map'] = $this->language->get('button_map');
        $data['button_submit'] = $this->language->get('button_submit');

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        if (isset($this->error['email'])) {
            $data['error_email'] = $this->error['email'];
        } else {
            $data['error_email'] = '';
        }

        if (isset($this->error['enquiry'])) {
            $data['error_enquiry'] = $this->error['enquiry'];
        } else {
            $data['error_enquiry'] = '';
        }

        /*if (isset($this->error['company-name'])) {
            $data['error_company'] = $this->error['company-name'];
        } else {
            $data['error_company'] = '';
        }*/

        $data['button_submit'] = $this->language->get('button_submit');

        $data['action'] = $this->url->link('information/contact');

        $this->load->model('tool/image');

        if ($this->config->get('config_image')) {
            $data['image'] = $this->model_tool_image->resize($this->config->get('config_image'), $this->config->get('config_image_location_width'), $this->config->get('config_image_location_height'));
        } else {
            $data['image'] = false;
        }

        $data['store'] = $this->config->get('config_name');
        $data['address'] = nl2br($this->config->get('config_address'));
        $data['geocode'] = $this->config->get('config_geocode');
        $data['telephone'] = $this->config->get('config_telephone');
        $data['fax'] = $this->config->get('config_fax');
        $data['open'] = nl2br($this->config->get('config_open'));
        $data['comment'] = $this->config->get('config_comment');

        $data['locations'] = [];

        $this->load->model('localisation/location');

        foreach ((array) $this->config->get('config_location') as $location_id) {
            $location_info = $this->model_localisation_location->getLocation($location_id);

            if ($location_info) {
                if ($location_info['image']) {
                    $image = $this->model_tool_image->resize($location_info['image'], $this->config->get('config_image_location_width'), $this->config->get('config_image_location_height'));
                } else {
                    $image = false;
                }

                $data['locations'][] = [
                    'location_id' => $location_info['location_id'],
                    'name' => $location_info['name'],
                    'address' => nl2br($location_info['address']),
                    'geocode' => $location_info['geocode'],
                    'telephone' => $location_info['telephone'],
                    'fax' => $location_info['fax'],
                    'image' => $image,
                    'open' => nl2br($location_info['open']),
                    'comment' => $location_info['comment'],
                ];
            }
        }

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } else {
            $data['name'] = $this->customer->getFirstName();
        }

        if (isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } else {
            $data['email'] = $this->customer->getEmail();
        }

        if (isset($this->request->post['enquiry'])) {
            $data['enquiry'] = $this->request->post['enquiry'];
        } else {
            $data['enquiry'] = '';
        }

        if ($this->config->get('config_google_captcha_status')) {
            $this->document->addScript('https://www.google.com/recaptcha/api.js');

            $data['site_key'] = $this->config->get('config_google_captcha_public');
        } else {
            $data['site_key'] = '';
        }
        $log->write('14');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        //$data['footer'] = $this->load->controller('common/footer');
        //$data['header'] = $this->load->controller('common/header/onlyHeader');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->request->isAjax()) {
            $log->write('15.4');
            if (!$this->validate()) {
                $data['status'] = false;
                if ($this->request->isAjax()) {
                    $this->response->addHeader('Content-Type: application/json');
                    $this->response->setOutput(json_encode($data));
                }
            } else {
                $data['status'] = true;
                $data['redirect'] = $this->url->link('account/account', '', 'SSL');

                if ($this->request->isAjax()) {
                    $this->response->addHeader('Content-Type: application/json');
                    $this->response->setOutput(json_encode($data));
                }
            }
        }

        $log->write('15');
        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/information/contact.tpl')) {
            return $this->load->view($this->config->get('config_template').'/template/information/contact.tpl', $data);
        } else {
            return $this->load->view('default/template/information/contact.tpl', $data);
        }
    }

    public function success()
    {
        $this->load->language('information/contact');

        $this->document->setTitle($this->language->get('heading_title'));
        $this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_checkout.css');

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('information/contact'),
        ];

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_message'] = $this->language->get('text_success_contact');

        $data['button_continue'] = $this->language->get('button_continue');

        $data['continue'] = $this->url->link('common/home');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/information');

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/common/success.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/common/success.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/common/success.tpl', $data));
        }
    }

    protected function validate()
    {
        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if (!filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if ((utf8_strlen($this->request->post['enquiry']) < 10) || (utf8_strlen($this->request->post['enquiry']) > 3000)) {
            $this->error['enquiry'] = $this->language->get('error_enquiry');
        }

        /*if ((utf8_strlen($this->request->post['company-name']) < 1)) {
            $this->error['company-name'] = 'Company name is required!';
        }*/

        /*if ($this->config->get('config_google_captcha_status')) {
            $json = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($this->config->get('config_google_captcha_secret')) . '&response=g-recaptcha&remoteip=' . $this->request->server['REMOTE_ADDR']);
            $json = json_decode($json, true);
            if (!$json['success']) {
                $this->error['captcha'] = $this->language->get('error_captcha');
            }
        }*/

        return !$this->error;
    }

    //send email by AJAX
    public function send()
    {
        $json = [];

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validate()) {
            $subject = $this->emailtemplate->getSubject('Contact', 'contact_1', $this->request->post);
            $message = $this->emailtemplate->getMessage('Contact', 'contact_1', $this->request->post);

            $mail = new Mail($this->config->get('config_mail'));
            $mail->setTo($this->config->get('config_email'));
            $mail->setFrom($this->config->get('config_from_email'));
            $mail->setSender($this->request->post['name']);
            $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
            $mail->setHtml(html_entity_decode(strip_tags($message), ENT_QUOTES, 'UTF-8'));
            $mail->send();

            $json['status'] = 1;
        } else {
            if (isset($this->error['name'])) {
                $json['error_name'] = $this->error['name'];
            } else {
                $json['error_name'] = '';
            }

            if (isset($this->error['email'])) {
                $json['error_email'] = $this->error['email'];
            } else {
                $json['error_email'] = '';
            }

            if (isset($this->error['enquiry'])) {
                $json['error_enquiry'] = $this->error['enquiry'];
            } else {
                $json['error_enquiry'] = '';
            }

            $json['status'] = 0;
        }

        echo json_encode($json);
    }
}
