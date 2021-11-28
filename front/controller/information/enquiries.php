<?php

class ControllerInformationEnquiries extends Controller
{
    private $error = [];

    public function index()
    {
        //echo "<pre>";print_r("enq");die;
        $this->load->language('information/enquiries');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_checkout.css');

        if ($this->request->server['HTTPS']) {
            $data['admin_link'] = HTTP_ADMIN;
        } else {
            $data['admin_link'] = HTTPS_ADMIN;
        }

        //echo "<pre>";print_r($this->request->post);die;
        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validate()) {
            $this->load->model('information/enquiry');

            $result = $this->model_information_enquiry->save($this->request->post);

            $this->request->post['approve_link'] = HTTPS_ADMIN.'index.php?path=approvals/enquiries/view&enquiry_id='.$result;

            $subject = $this->emailtemplate->getSubject('Contact', 'contact_2', $this->request->post);
            $message = $this->emailtemplate->getMessage('Contact', 'contact_2', $this->request->post);
            //mishramanjari15@gmail.com
            $mail = new mail($this->config->get('config_mail'));
            $mail->setTo($this->config->get('config_email'));
            $mail->setFrom($this->config->get('config_from_email'));
            $mail->setReplyTo($this->request->post['email']);
            $mail->setSender($this->request->post['firstname'].' '.$this->request->post['lastname']);
            $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
            $mail->setHtml(html_entity_decode(strip_tags($message), ENT_QUOTES, 'UTF-8'));
            $mail->send();

            $subject = $this->emailtemplate->getSubject('Contact', 'contact_3', $this->request->post);
            $message = $this->emailtemplate->getMessage('Contact', 'contact_3', $this->request->post);

            $mail = new mail($this->config->get('config_mail'));
            $mail->setTo($this->request->post['email']);
            $mail->setFrom($this->config->get('config_from_email'));
            //$mail->setReplyTo($this->request->post['email']);
            $mail->setSender($this->request->post['firstname'].' '.$this->request->post['lastname']);
            $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
            $mail->setHtml(html_entity_decode(strip_tags($message), ENT_QUOTES, 'UTF-8'));
            $mail->send();

            if ('0' == $result) {
                $this->error['error_warning'] = $this->language->get('error_verification_message');
            } else {
                $this->response->redirect($this->url->link('information/enquiries/success'));
            }
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

        $data['heading_text'] = $this->language->get('heading_text');
        $data['text_location'] = $this->language->get('text_location');
        $data['text_store'] = $this->language->get('text_store');
        $data['text_contact'] = $this->language->get('text_contact');
        $data['text_address'] = $this->language->get('text_address');
        $data['text_telephone'] = $this->language->get('text_telephone');
        $data['text_fax'] = $this->language->get('text_fax');
        $data['text_open'] = $this->language->get('text_open');
        $data['text_comment'] = $this->language->get('text_comment');
        $data['text_warning'] = $this->language->get('text_warning');
        $data['text_terms'] = $this->language->get('text_terms');
        //$data['text_agree'] = $this->language->get('text_agree');

        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_username'] = $this->language->get('entry_username');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_password'] = $this->language->get('entry_password');
        $data['entry_confirm_password'] = $this->language->get('entry_confirm_password');
        $data['entry_enquiry'] = $this->language->get('entry_enquiry');
        $data['entry_about_us'] = $this->language->get('entry_about_us');
        $data['entry_business'] = $this->language->get('entry_business');
        $data['entry_type'] = $this->language->get('entry_type');
        $data['entry_tin_no'] = $this->language->get('entry_tin_no');
        $data['entry_mobile'] = $this->language->get('entry_mobile');
        $data['entry_city'] = $this->language->get('entry_city');
        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_store'] = $this->language->get('entry_store');
        $data['entry_address'] = $this->language->get('entry_address');
        $data['button_map'] = $this->language->get('button_map');

        $data['option_single'] = $this->language->get('option_single');
        $data['option_multi'] = $this->language->get('option_multi');
        $data['option_corporate'] = $this->language->get('option_corporate');

        $data['text_fill_details'] = $this->language->get('text_fill_details');
        $data['text_seller_signup'] = $this->language->get('text_seller_signup');
        $data['text_have_account'] = $this->language->get('text_have_account');
        $data['text_login'] = $this->language->get('text_login');
        $data['text_increase_sales'] = $this->language->get('text_increase_sales');
        $data['text_submit_details'] = $this->language->get('text_submit_details');

        if (isset($this->error['error_warning'])) {
            $data['error_warning'] = $this->error['error_warning'];
        } else {
            $data['error_warning'] = '';
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

        if (isset($this->error['confirm_password'])) {
            $data['error_confirm_password'] = $this->error['confirm_password'];
        } else {
            $data['error_confirm_password'] = '';
        }

        if (isset($this->error['mismatch_password'])) {
            $data['error_mismatch_password'] = $this->error['mismatch_password'];
        } else {
            $data['error_mismatch_password'] = '';
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

        if (isset($this->error['store_name'])) {
            $data['error_store_name'] = $this->error['store_name'];
        } else {
            $data['error_store_name'] = '';
        }

        if (isset($this->error['about_us'])) {
            $data['error_about_us'] = $this->error['about_us'];
        } else {
            $data['error_about_us'] = '';
        }

        if (isset($this->error['captcha'])) {
            $data['error_captcha'] = $this->error['captcha'];
        } else {
            $data['error_captcha'] = '';
        }

        $data['button_submit'] = $this->language->get('button_submit');

        $data['action'] = $this->url->link('information/enquiries');

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

        if (isset($this->request->post['tin_no'])) {
            $data['tin_no'] = $this->request->post['tin_no'];
        } else {
            $data['tin_no'] = '';
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

        if (isset($this->request->post['confirm_password'])) {
            $data['confirm_password'] = $this->request->post['confirm_password'];
        } else {
            $data['confirm_password'] = '';
        }

        if (isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } else {
            $data['email'] = $this->customer->getEmail();
        }
        //echo "<pre>";print_r("s".$data['email']."s");
        if (empty($data['email'])) {
            //echo "<pre>";print_r("s".$data['email']."s");die;
            $data['email'] = '';
        }

        $this->load->model('assets/category');
        //$data['categories'] = $this->model_assets_category->getCategories(0);
        $data['categories'] = $this->model_assets_category->getStoreTypes();

        if (isset($this->request->post['business'])) {
            $data['business'] = $this->request->post['business'];
        } else {
            $data['business'] = [];
        }

        if (isset($this->request->post['type'])) {
            $data['type'] = $this->request->post['type'];
        } else {
            $data['type'] = '';
        }

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

        if (isset($this->request->post['store_name'])) {
            $data['store_name'] = $this->request->post['store_name'];
        } else {
            $data['store_name'] = '';
        }

        if (isset($this->request->post['about_us'])) {
            $data['about_us'] = $this->request->post['about_us'];
        } else {
            $data['about_us'] = '';
        }

        if ($this->config->get('config_seller_id')) {
            $this->load->model('assets/information');

            $information_info = $this->model_assets_information->getInformation($this->config->get('config_seller_id'));

            //echo "<pre>";print_r($this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_seller_id')));die;
            if ($information_info) {
                $data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id='.$this->config->get('config_seller_id'), 'SSL'), $information_info['title'], $information_info['title']);
            } else {
                $data['text_agree'] = '';
            }
        } else {
            $data['text_agree'] = '';
        }

        $data['terms_link'] = $this->url->link('information/information', 'information_id='.$this->config->get('config_seller_id'), 'SSL');

        if (isset($this->request->post['agree'])) {
            $data['agree'] = $this->request->post['agree'];
        } else {
            $data['agree'] = '';
        }

        if (isset($this->request->post['captcha'])) {
            $data['captcha'] = $this->request->post['captcha'];
        } else {
            $data['captcha'] = '';
        }

        $data['cities'] = $this->model_assets_category->getCities();

        $data['footer'] = $this->load->controller('common/footer');

        $data['header'] = $this->load->controller('common/header/onlyHeader');

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/information/enquiries.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/information/enquiries.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/information/enquiries.tpl', $data));
        }
    }

    public function success()
    {
        $this->load->language('information/enquiries');

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
        $data['header'] = $this->load->controller('common/header/onlyHeader');

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

        /*if ((utf8_strlen($this->request->post['username']) < 5) || (utf8_strlen($this->request->post['username']) > 50)) {
            $this->error['username'] = $this->language->get('error_username');
        }*/

        if (!filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        $this->load->model('assets/category');

        $user_query = $this->model_assets_category->getUser($this->request->post['email']);

        $enquiry_query = $this->model_assets_category->getEnquiries($this->request->post['email']);

        if ($enquiry_query->num_rows) {
            $this->error['username'] = $this->language->get('error_username_exists');
        }

        if (count($user_query) > 0) {
            $this->error['username'] = $this->language->get('error_username_exists');
        }

        if ((utf8_strlen($this->request->post['password']) < 3) || (utf8_strlen($this->request->post['password']) > 32)) {
            $this->error['password'] = $this->language->get('error_password');
        }

        if ((utf8_strlen($this->request->post['confirm_password']) < 3) || (utf8_strlen($this->request->post['confirm_password']) > 32)) {
            $this->error['confirm_password'] = $this->language->get('error_confirm_password');
        }

        if ($this->request->post['confirm_password'] != $this->request->post['password']) {
            $this->error['mismatch_password'] = $this->language->get('error_mismatch_password');
        }

        if ((utf8_strlen($this->request->post['about_us']) < 10) || (utf8_strlen($this->request->post['about_us']) > 3000)) {
            $this->error['about_us'] = $this->language->get('error_about_us');
        }

        // if (empty($this->request->post['agree'])){
        //     $this->error['agree'] =  $this->language->get('error_agree');
        // }

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

        if (empty($this->request->post['store_name'])) {
            $this->error['store_name'] = $this->language->get('error_store_name');
        }

        //echo "<pre>";print_r($this->error);die;
        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
}
