<?php

class ControllerInformationShopper extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('information/shopper');

        $this->document->setTitle($this->language->get('heading_title'));
        $this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_checkout.css');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validate()) {
            $subject = $this->emailtemplate->getSubject('Contact', 'contact_1', $this->request->post);
            $message = $this->emailtemplate->getMessage('Contact', 'contact_1', $this->request->post);

            $mail = new Mail($this->config->get('config_mail'));
            $mail->setTo($this->config->get('config_email'));
            //$mail->setFrom($this->request->post['email']);
            $mail->setFrom($this->config->get('config_from_email'));

            $mail->setSender($this->request->post['firstname'].' '.$this->request->post['lastname']);
            $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
            $mail->setHtml(html_entity_decode(strip_tags($message), ENT_QUOTES, 'UTF-8'));
            $mail->send();

            $this->load->model('information/shopper');
            $this->model_information_shopper->save($this->request->post);

            $this->response->redirect($this->url->link('information/shopper/success'));
        }

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

        $data['text_heading'] = $this->language->get('text_heading');
        $data['text_location'] = $this->language->get('text_location');
        $data['text_store'] = $this->language->get('text_store');
        $data['text_contact'] = $this->language->get('text_contact');
        $data['text_address'] = $this->language->get('text_address');
        $data['text_telephone'] = $this->language->get('text_telephone');
        $data['text_fax'] = $this->language->get('text_fax');
        $data['text_city'] = $this->language->get('text_city');
        $data['text_mobile'] = $this->language->get('text_mobile');
        $data['text_firstname'] = $this->language->get('text_firstname');
        $data['text_lastname'] = $this->language->get('text_lastname');
        $data['text_username'] = $this->language->get('text_username');
        $data['text_password'] = $this->language->get('text_password');
        $data['text_email'] = $this->language->get('text_fax');
        $data['text_open'] = $this->language->get('text_email');
        $data['text_comment'] = $this->language->get('text_comment');

        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_username'] = $this->language->get('entry_username');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_password'] = $this->language->get('entry_password');
        $data['entry_enquiry'] = $this->language->get('entry_enquiry');
        $data['entry_enquiry'] = $this->language->get('entry_enquiry');
        $data['entry_enquiry'] = $this->language->get('entry_enquiry');
        $data['entry_enquiry'] = $this->language->get('entry_enquiry');
        $data['entry_enquiry'] = $this->language->get('entry_enquiry');

        $data['button_map'] = $this->language->get('button_map');
        $data['button_submit'] = $this->language->get('button_submit');

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

        if (isset($this->error['username'])) {
            $data['error_username'] = $this->error['username'];
        } else {
            $data['error_username'] = '';
        }

        if (isset($this->error['agree'])) {
            $data['error_agree'] = $this->error['agree'];
        } else {
            $data['error_agree'] = '';
        }

        if (isset($this->error['password'])) {
            $data['error_password'] = $this->error['password'];
        } else {
            $data['error_password'] = '';
        }

        if (isset($this->error['email'])) {
            $data['error_email'] = $this->error['email'];
        } else {
            $data['error_email'] = '';
        }
        /*
        business
        type
        mobile
        telephone
        city_id
        address
        store_name
        */

        if (isset($this->error['mobile'])) {
            $data['error_mobile'] = $this->error['mobile'];
        } else {
            $data['error_mobile'] = '';
        }

        if (isset($this->error['city_id'])) {
            $data['error_city_id'] = $this->error['city_id'];
        } else {
            $data['error_city_id'] = '';
        }

        if (isset($this->error['address'])) {
            $data['error_address'] = $this->error['address'];
        } else {
            $data['error_address'] = '';
        }

        if (isset($this->error['captcha'])) {
            $data['error_captcha'] = $this->error['captcha'];
        } else {
            $data['error_captcha'] = '';
        }

        $data['button_submit'] = $this->language->get('button_submit');

        $data['action'] = $this->url->link('information/shopper');

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

        if (isset($this->request->post['firstname'])) {
            $data['firstname'] = $this->request->post['firstname'];
        } else {
            $data['firstname'] = $this->customer->getFirstName();
        }

        if (isset($this->request->post['lastname'])) {
            $data['lastname'] = $this->request->post['lastname'];
        } else {
            $data['lastname'] = $this->customer->getLastName();
        }

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

        if (isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } else {
            $data['email'] = $this->customer->getEmail();
        }

        $this->load->model('assets/category');
        $data['categories'] = $this->model_assets_category->getCategories(0);

        $data['cities'] = $this->model_assets_category->getCities();

        /*
        business
        type
        mobile
        telephone
        city_id
        address
        store_name
        */

        if (isset($this->request->post['mobile'])) {
            $data['mobile'] = $this->request->post['mobile'];
        } else {
            $data['mobile'] = '';
        }

        if (isset($this->request->post['telephone'])) {
            $data['telephone'] = $this->request->post['telephone'];
        } else {
            $data['telephone'] = '';
        }

        if (isset($this->request->post['city_id'])) {
            $data['city_id'] = $this->request->post['city_id'];
        } else {
            $data['city_id'] = '';
        }

        if (isset($this->request->post['address'])) {
            $data['address'] = $this->request->post['address'];
        } else {
            $data['address'] = '';
        }

        if (isset($this->request->post['captcha'])) {
            $data['captcha'] = $this->request->post['captcha'];
        } else {
            $data['captcha'] = '';
        }

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/information');

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/information/shopper.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/information/shopper.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/information/shopper.tpl', $data));
        }
    }

    public function success()
    {
        $this->load->language('information/shopper');

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

        $data['text_message'] = $this->language->get('text_message');

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
        if ((utf8_strlen($this->request->post['firstname']) < 3) || (utf8_strlen($this->request->post['firstname']) > 32)) {
            $this->error['firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen($this->request->post['lastname']) < 3) || (utf8_strlen($this->request->post['lastname']) > 32)) {
            $this->error['lastname'] = $this->language->get('error_lastname');
        }

        if ((utf8_strlen($this->request->post['username']) < 3) || (utf8_strlen($this->request->post['username']) > 32)) {
            $this->error['username'] = $this->language->get('error_username');
        }

        $this->load->model('assets/category');
        $row = $this->model_assets_category->getUser($this->request->post['username']);

        if ($row) {
            $this->error['username'] = $this->language->get('error_username_exists');
        }

        if ((utf8_strlen($this->request->post['password']) < 3) || (utf8_strlen($this->request->post['password']) > 32)) {
            $this->error['password'] = $this->language->get('error_password');
        }

        if (!filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        /*
        business
        type
        mobile
        telephone
        city_id
        address
        store_name
        */
        if (empty($this->request->post['mobile'])) {
            $this->error['mobile'] = $this->language->get('error_mobile');
        }

        if (empty($this->request->post['city_id'])) {
            $this->error['city_id'] = $this->language->get('error_city_id');
        }

        if (empty($this->request->post['address'])) {
            $this->error['address'] = $this->language->get('error_address');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
}
