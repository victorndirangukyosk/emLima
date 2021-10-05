<?php

class ControllerCommonHome extends Controller {

    public function show_home() {
        unset($this->session->data['config_store_id']);

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $this->response->redirect($server);
    }

    public function saveVisitorId() {
        $this->session->data['visitor_id'] = $this->request->get['visitor_id'];

        return $this->session->data['visitor_id'];
    }

    public function start() {
        $this->session->data['config_store_id'] = $this->request->post['store_id'];

        $json['status'] = 1;

        $json['location'] = $this->url->link('product/store', 'store_id=' . $this->session->data['config_store_id'] . '');

        echo json_encode($json);
    }

    public function getFacebookRedirectUrl() {
        $url = null;
        if (isset($this->request->get['category'])) {
            $url = $this->request->get['category'];
        }

        if (isset($this->request->get['redirect_url'])) {
            if (false !== ($pos = strpos($this->request->get['redirect_url'], 'path='))) {
                $redirectPath = substr($this->request->get['redirect_url'], $pos + 5);
                if ($url) {
                    $redirectPath .= '&category=' . $url;
                }
                $this->session->data['redirect'] = $this->url->link($redirectPath);
            } else {
                $this->session->data['redirect'] = $this->request->get['redirect_url'];
            }
        }

        require DIR_SYSTEM . 'vendor/Facebook/autoload.php';

        $fb = new Facebook\Facebook([
            'app_id' => !empty($this->config->get('config_fb_app_id')) ? $this->config->get('config_fb_app_id') : 'randomstringforappid',
            'app_secret' => !empty($this->config->get('config_fb_secret')) ? $this->config->get('config_fb_secret') : 'randomstringforappsecret',
            'default_graph_version' => 'v2.5',
                //'default_access_token' => $this->request->get['code']//'5ce6c3df96acc19c6215f2ac62d3480e', // optional
        ]);

        $helper = $fb->getRedirectLoginHelper();

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $json['facebook'] = $helper->getLoginUrl($server . 'index.php?path=account/facebook', ['email']);

        echo json_encode($json);
    }

    public function saveLocation() {
        if (isset($this->request->post['name']) && isset($this->request->post['lat']) && isset($this->request->post['lng'])) {
            $_COOKIE['location'] = $this->request->post['lat'] . ',' . $this->request->post['lng'];
            /* Commented For Meta Organic */
            //setcookie('location', $this->request->post['lat'].','.$this->request->post['lng'], time() + (86400 * 30 * 30 * 30 * 3), "/");// 3 month expiry
            /* Commented For Meta Organic */
            $_COOKIE['location_name'] = $this->request->post['name'];
            //setcookie('location_name', $this->request->post['name'], time() + (86400 * 30 * 30 * 30 * 3), "/");
        }

        $html['status'] = true;

        echo json_encode($html);
    }

    public function find_store() {
        $html = '';
        $this->load->language('information/locations');
        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = '';
        }

        $html = [];
        $html['text_error'] = $this->language->get('text_error');
        $html['text_not_found'] = $this->language->get('text_not_found');
        $this->load->model('setting/store');

        if ('autosuggestion' == $this->config->get('config_store_location')) {
            $stores = $this->model_setting_store->getStoreByLatLang($this->request->get['zipcode'], $filter_name);
        } else {
            $stores = $this->model_setting_store->getStoreByZip($this->request->get['zipcode'], $filter_name);
        }

        if ($stores) {
            $html['store'] = true;
        } else {
            $html['store'] = false;
        }
        echo json_encode($html);
    }

    public function homepage() {
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

        $data['base'] = $server;
        $data['is_login'] = $this->customer->isLogged();
        $data['f_name'] = $this->customer->getFirstName();
        $data['name'] = $this->customer->getFirstName();
        $data['l_name'] = $this->customer->getLastName();
        $data['full_name'] = $data['f_name']; //.' '.$data['l_name'];
        $data['home'] = $this->url->link('common/home');
        $data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
        $data['logged'] = $this->customer->isLogged();
        $data['account'] = $this->url->link('account/account', '', 'SSL');
        $data['dashboard'] = $this->url->link('account/dashboard', '', 'SSL');
        $data['feedback'] = $this->url->link('account/feedback', '', 'SSL');

        $data['register'] = $this->url->link('account/register', '', 'SSL');
        $data['login'] = $this->url->link('account/login', '', 'SSL');
        $data['order'] = $this->url->link('account/order', '', 'SSL');
        $data['credit'] = $this->url->link('account/credit', '', 'SSL');
        $data['download'] = $this->url->link('account/download', '', 'SSL');
        $data['logout'] = $this->url->link('account/logout', '', 'SSL');
        $data['shopping_cart'] = $this->url->link('checkout/cart');
        $data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
        $data['contact'] = $this->url->link('information/contact');
        $data['telephone'] = $this->config->get('config_telephone');
        $data['refer'] = $this->url->link('account/refer', '', 'SSL');
        $data['reward'] = $this->url->link('account/reward', '', 'SSL');
        $data['footer'] = $this->load->controller('common/footer');
        $data['action'] = $this->url->link('common/home/find_store');
        $data['address'] = $this->url->link('account/address', '', 'SSL');
        $data['help'] = $this->url->link('information/help');
        $data['language'] = $this->load->controller('common/language/dropDown');
        $data['login'] = $this->url->link('account/login', '', 'SSL');
        $data['register'] = $this->url->link('account/register', '', 'SSL');
        $data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');
        $data['store_name'] = $this->config->get('config_name');

//    echo "<pre>";print_r($data);die;

        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/landing_page/index.tpl', $data));
    }

    public function faq() {
        $this->load->model('catalog/help');
        $questions = $this->model_catalog_help->getHelps();
        $categories = $this->model_catalog_help->getCategories();

        $data = [];

        foreach ($categories as $category) {
            $data[$category['category_id']]['category'] = $category['name'];
        }

        foreach ($questions as $question) {
            $data[$question['category_id']]['questions'][] = $question;
        }

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
            //$data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'),200,110);
            $data[0]['logo'] = $server . 'image/' . $this->config->get('config_logo');
        } else {
            $data[0]['logo'] = 'assets/img/logo.svg';
        }

        $data['base'] = '';
        // $data[0]['base'] = '';
        $data[0]['store_name'] = $this->config->get('config_name');

        //  echo "<pre>";print_r($data);die;

        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/landing_page/faq.tpl', $data));
    }

    public function covid19() {

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

        $data['base'] = '';
        $data['store_name'] = $this->config->get('config_name');

        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/landing_page/covid19.tpl', $data));
    }

    public function blog() {

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

        $data['base'] = '';
        $data['store_name'] = $this->config->get('config_name');

        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/landing_page/blog.tpl', $data));
    }

    public function technology() {

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

        $data['base'] = '';
        $data['store_name'] = $this->config->get('config_name');

        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/landing_page/technology.tpl', $data));
    }

    public function careers($id = 0, $successmessage = "", $errormessage = "") {

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

        $data['base'] = '';
        $data['store_name'] = $this->config->get('config_name');

        $data['site_key'] = $this->config->get('config_google_captcha_public');
        $data['action'] = $this->url->link('common/home/savecareers', '', 'SSL');
        $data['message'] = $successmessage;
        $data['errormessage'] = $errormessage;
        if (isset($this->request->get['filter_category'])) {
            if ($this->request->get['filter_category'] != "All Job Category") {
                $filter_category = $this->request->get['filter_category'];
            } else {
                $filter_category = null;
            }
        } else {
            $filter_category = null;
        }

        if (isset($this->request->get['filter_type'])) {
            if ($this->request->get['filter_type'] != "All Job Type") {
                $filter_type = $this->request->get['filter_type'];
            } else {
                $filter_type = null;
            }
        } else {
            $filter_type = null;
        }

        if (isset($this->request->get['filter_location'])) {
            if ($this->request->get['filter_location'] != "All Job Location") {
                $filter_location = $this->request->get['filter_location'];
            } else {
                $filter_location = null;
            }
        } else {
            $filter_location = null;
        }

        $url = '';

        if (isset($this->request->get['filter_category'])) {
            $url .= '&filter_category=' . urlencode(html_entity_decode($this->request->get['filter_category'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_type'])) {
            $url .= '&filter_type=' . urlencode(html_entity_decode($this->request->get['filter_type'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_location'])) {
            $url .= '&filter_location=' . urlencode(html_entity_decode($this->request->get['filter_location'], ENT_QUOTES, 'UTF-8'));
        }
        $filter_data = [
            'filter_category' => $filter_category,
            'filter_type' => $filter_type,
            'filter_location' => $filter_location,
                // 'sort' => $sort,
                // 'order' => $order,
                // 'start' => ($page - 1) * $this->config->get('config_limit_admin'),
                // 'limit' => $this->config->get('config_limit_admin'),
        ];
        // if($filter_data['filter_category']!=null)
        // $data['job_category_name'] = $filter_data['filter_category'];
        // if($filter_data['filter_type']!=null)
        // $data['job_type_name'] = $filter_data['filter_type'];
        // if($filter_data['filter_location']!=null)
        // $data['job_location_name'] = $filter_data['filter_location'];

        $this->load->model('information/careers');

        $data['jobpositions'] = $this->model_information_careers->getJobPositions($filter_data);
        $data['job_categories'] = $this->model_information_careers->getJobCategories();
        $data['job_types'] = $this->model_information_careers->getJobTypes();
        $data['job_locations'] = $this->model_information_careers->getJobLocations();

        $url = '';

        if (isset($this->request->get['filter_category'])) {
            $url .= '&filter_category=' . urlencode(html_entity_decode($this->request->get['filter_category'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_type'])) {
            $url .= '&filter_type=' . urlencode(html_entity_decode($this->request->get['filter_type'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_location'])) {
            $url .= '&filter_location=' . urlencode(html_entity_decode($this->request->get['filter_location'], ENT_QUOTES, 'UTF-8'));
        }
        $filter_data = [
            'filter_category' => $filter_category,
            'filter_type' => $filter_type,
            'filter_location' => $filter_location,
                // 'sort' => $sort,
                // 'order' => $order,
                // 'start' => ($page - 1) * $this->config->get('config_limit_admin'),
                // 'limit' => $this->config->get('config_limit_admin'),
        ];

        //   echo "<pre>";($url);;
        if ($filter_data['filter_category'] != null)
            $data['job_category_name'] = $filter_data['filter_category'];
        if ($filter_data['filter_type'] != null)
            $data['job_type_name'] = $filter_data['filter_type'];
        if ($filter_data['filter_location'] != null)
            $data['job_location_name'] = $filter_data['filter_location'];
        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/landing_page/careers.tpl', $data));
        //   echo "<pre>";print_r($data);die;
    }

    public function job_opening_details($id = 0, $message = "", $errormessage = "") {
        $data['site_key'] = $this->config->get('config_google_captcha_public');
        if (isset($this->request->get['id'])) {
            $filter['id'] = $this->request->get['id'];
        } else {
            $filter['id'] = $id;
        }
        // echo  ($id);die;
        $this->load->model('information/careers');

        $data['jobpositions'] = $this->model_information_careers->getJobPositions($filter);
        $data['jobpositions'][0]['site_key'] = $this->config->get('config_google_captcha_public');
        $data['jobpositions'][0]['action'] = $this->url->link('common/home/savecareers', '', 'SSL');
        $data['jobpositions'][0]['message'] = $message;
        $data['jobpositions'][0]['errormessage'] = $errormessage;
        if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
            //$data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'),200,110);
            $data['jobpositions'][0]['logo'] = $server . 'image/' . $this->config->get('config_logo');
        } else {
            $data['jobpositions'][0]['logo'] = 'assets/img/logo.svg';
        }
        //   echo "<pre>";print_r($data['jobpositions'][0]);die;
        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/landing_page/jobopening.tpl', $data['jobpositions'][0]));
    }

    public function savecareers() {

        $this->load->model('information/careers');

        if (('POST' == $this->request->server['REQUEST_METHOD'])) {
            $file_upload_status = $this->FeatureFileUpload($this->request->files);
            $file_data = $this->request->files;
            $log = new Log('error.log');
            $log->write($file_upload_status);
            if ($file_upload_status != NULL && $file_upload_status['status'] == TRUE && $file_upload_status['file_name'] != NULL) {
                $this->load->model('setting/setting');
                $first_name = str_replace("'", "", $this->request->post['careers-first-name']);
                $email = str_replace("'", "", $this->request->post['careers-email']);
                $phone = str_replace("'", "", $this->request->post['careers-phone-number']);
                $id = $this->model_information_careers->createCareers($first_name, str_replace("'", "", $this->request->post['lastname']), str_replace("'", "", $this->request->post['role']), str_replace("'", "", $this->request->post['yourself']), $email, $phone, str_replace("'", "", $this->request->post['careers-job-id']), str_replace("'", "", $this->request->post['careers-cover-letter']), $file_upload_status['file_name'], str_replace("'", "", $this->request->post['careers-job-position']));
                $status = true;
                $success_message = 'Thank you we will contact you shortly';

                if ($id > 0) {

                    //send mail notification to 'stalluri@technobraingroup.com'
                    // $subject = $this->emailtemplate->getSubject('Customer', 'customer_1', $data);
                    // $message = $this->emailtemplate->getMessage('Customer', 'customer_1', $data);
                    $subject = "Job Request";
                    if ($jobposition != "")
                        $message = "Following details are received for the job position - " . $jobposition . "<br>";
                    else
                        $message = "Following details are received.  <br>";
                    $message = $message . "<li> Full Name :" . $first_name . "</li><br><li> Email :" . $email . "</li><br><li> Phone :" . $phone . "</li><br>";

                    $this->load->model('setting/setting');
                    $email = $this->model_setting_setting->getEmailSetting('careers');

                    if (strpos($email, "@") == false) {//if mail Id not set in define.php
                        $email = "sridivya.talluri@technobraingroup.com";
                    }

                    // $bccemail = "sridivya.talluri@technobraingroup.com";
                    //  echo "<pre>";print_r($file_data);die;
                    $filepath = DIR_UPLOAD . "careers/" . $file_upload_status['file_name'];
                    $mail = new Mail($this->config->get('config_mail'));
                    $mail->setTo($email);
                    $mail->setBCC($bccemail);
                    $mail->setFrom($this->config->get('config_from_email'));
                    $mail->setSender($this->config->get('config_name'));
                    $mail->setSubject($subject);
                    $mail->setHTML($message);
                    $mail->addAttachment($filepath);
                    $mail->send();
                }
            } else {
                $status = true;
                $error_message = 'Please upload correct file and data';
            }


            //$this->response->addHeader('Content-Type: application/json');
        }
        // $this->response->setOutput(json_encode($json));
        if ($this->request->post['careers-job-id'] == 0)
            $this->careers(0, $success_message, $error_message);
        else
            $this->job_opening_details($this->request->post['careers-job-id'], $success_message, $error_message);
        // $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/landing_page/jobopening.tpl', $data['jobpositions'][0]));
    }

    public function FeatureFileUpload($file_data) {
        $status = array();

        //echo "<pre>";print_r($file_data);die;
        if ((isset($file_data['careers-resume'])) && (is_uploaded_file($file_data['careers-resume']['tmp_name']))) {

            if ($file_data['careers-resume']['type'] != "application/msword" && $file_data['careers-resume']['type'] != "application/vnd.openxmlformats-officedocument.wordprocessingml.document" && $file_data['careers-resume']['type'] != "application/octet-stream" && $file_data['careers-resume']['type'] != "application/pdf") {
                return $status = array('status' => FALSE, 'file_name' => '');
            }
            if ($file_data['careers-resume']['size'] > 5000000) {
                return $status = array('status' => FALSE, 'file_name' => '');
            }


            if (!file_exists(DIR_UPLOAD . 'careers/')) {
                mkdir(DIR_UPLOAD . 'careers/', 0777, true);
            }//md5(mt_rand())
            $file_name = (rand(10, 100) ) . '' . $file_data['careers-resume']['name'];
            if (move_uploaded_file($file_data['careers-resume']['tmp_name'], DIR_UPLOAD . 'careers/' . $file_name)) {
                return $status = array('status' => TRUE, 'file_name' => $file_name);
            } else {
                return $status = array('status' => FALSE, 'file_name' => '');
            }
        }
    }

    public function savepartner() {

        $this->load->model('information/partners');
        $this->model_information_partners->createPartners(str_replace("'", "", $this->request->post['firstname']), str_replace("'", "", $this->request->post['lastname']), str_replace("'", "", $this->request->post['designation']), str_replace("'", "", $this->request->post['company']), str_replace("'", "", $this->request->post['email']), str_replace("'", "", $this->request->post['phone']), str_replace("'", "", $this->request->post['description']));
        $json['status'] = true;
        $json['success_message'] = 'Thank you we will contact you shortly';
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function farmers() {

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

        $data['base'] = '';
        $data['store_name'] = $this->config->get('config_name');

        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/landing_page/farmer-engagement.tpl', $data));
    }

    public function partners() {
        $data['register'] = $this->request->post['register'];
        $data['site_key'] = $this->config->get('config_google_captcha_public');

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

        $data['base'] = '';
        $data['store_name'] = $this->config->get('config_name');

        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/landing_page/partners.tpl', $data));
    }

    public function about_us() {

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

        $data['base'] = '';
        $data['store_name'] = $this->config->get('config_name');
        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/landing_page/about-us.tpl', $data));
    }

    public function terms_and_conditions() {
        $data['customer_id'] = NULL;
        if ($this->customer->isLogged()) {
            $data['customer_id'] = $this->session->data['customer_id'];
        }

        if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
            //$data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'),200,110);
            $data['logo'] = $server . 'image/' . $this->config->get('config_logo');
        } else {
            $data['logo'] = 'assets/img/logo.svg';
        }

        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/landing_page/terms-and-conditions.tpl', $data));
    }

    public function privacy_policy() {
        $data['customer_id'] = NULL;
        if ($this->customer->isLogged()) {
            $data['customer_id'] = $this->session->data['customer_id'];
        }
        if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
            //$data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'),200,110);
            $data['logo'] = $server . 'image/' . $this->config->get('config_logo');
        } else {
            $data['logo'] = 'assets/img/logo.svg';
        }
        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/landing_page/privacy-policy.tpl', $data));
    }

    public function index() {
        if (!isset($this->session->data['customer_id'])) {
            if (isset($_REQUEST['action']) && ('shop' == $_REQUEST['action'])) {
                $this->response->redirect($this->url->link('account/login/customer'));
            } else {
                //$this->response->redirect($this->url->link('common/home/homepage'));
            }
        }
        $this->load->model('account/customer');
        $is_he_parents = $this->model_account_customer->CheckHeIsParent();
        $log = new Log('error.log');
        $log->write('is_he_parents FRONT.CONTROLLER.COMMON.HOME');
        $log->write($is_he_parents);
        $log->write('is_he_parents FRONT.CONTROLLER.COMMON.HOME');
        if ($is_he_parents != NULL) {
            $customer_details = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . $this->db->escape($is_he_parents) . "' AND status = '1'");
        } else {
            $customer_details = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . $this->customer->getId() . "' AND status = '1'");
        }
        $this->session->data['customer_category'] = isset($customer_details->row['customer_category']) ? $customer_details->row['customer_category'] : null;

        $log = new Log('error.log');
        $log->write($this->session->data['customer_category'] . 'customer_category');

        $data['kondutoStatus'] = $this->config->get('config_konduto_status');

        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        //echo $this->config->get('config_store_location');die;

        if (isset($this->session->data['language'])) {
            $data['config_language'] = $this->session->data['language'];
        } else {
            $data['config_language'] = 'pt-BR';
        }

        if (isset($this->request->get['refer'])) {
            $x = strpos($this->request->get['refer'], '%21%40');

            $p = substr($this->request->get['refer'], $x + 6);

            $cookie_name = 'referral';
            $cookie_value = $p;

            //echo "<pre>";print_r($p);die;
            setcookie($cookie_name, $cookie_value, time() + (86400 * 30), '/'); // 1 day expiry
        }

        $data['seo_url_test'] = $this->url->link('store/collection', 'collection_id=3');

        $log->write($data['konduto_public_key']);
        unset($this->session->data['visitor_id']);

        //$log->write($this->session->data['language']);

        $data['justlogged_in'] = false;

        if (isset($this->session->data['just_loggedin']) && $this->session->data['just_loggedin']) {
            $data['justlogged_in'] = true;
            $this->session->data['just_loggedin'] = false;
        }

        if (defined('const_latitude') && defined('const_longitude') && !empty(const_latitude) && !empty(const_longitude)) {
            $_COOKIE['location'] = const_latitude . ',' . const_longitude;

            setcookie('location', const_latitude . ',' . const_longitude, time() + (86400 * 30 * 30 * 30 * 3), '/'); // 3 month expiry

            $_COOKIE['location_name'] = const_location_name;
            setcookie('location_name', const_location_name, time() + (86400 * 30 * 30 * 30 * 3), '/');

            $this->response->redirect($this->url->link('information/locations/stores', 'location=' . const_latitude . ',' . const_longitude));
        }

        if (count($_COOKIE) > 0 && isset($_COOKIE['zipcode'])) {
            $this->response->redirect($this->url->link('information/locations/stores', 'zipcode=' . $_COOKIE['zipcode']));
        }
        if (count($_COOKIE) > 0 && isset($_COOKIE['location'])) {
            $this->response->redirect($this->url->link('information/locations/stores', 'location=' . $_COOKIE['location']));
        }

        if (isset($this->session->data['config_store_id'])) {
            //$this->response->redirect($this->url->link('product/store'));

            $this->response->redirect($this->url->link('product/store', 'store_id=' . $this->session->data['config_store_id'] . ''));
        }

        $this->load->model('tool/image');
        $this->load->language('common/home');
        $this->load->model('account/wishlist');

        $wishlist_results = $this->model_account_wishlist->getWishlists();
        foreach ($wishlist_results as $result) {
            $wishlist_products = $this->model_account_wishlist->getWishlistProduct($result['wishlist_id']);
            $totalCount = 0;
            if (!empty($wishlist_products)) {
                $totalCount = count($wishlist_products);
            }
            $data['wishlists'][] = [
                'wishlist_id' => $result['wishlist_id'],
                'name' => $result['name'],
                'date_added' => date($this->language->get('date_format_medium'), strtotime($result['date_added'])),
                'product_count' => $totalCount,
                'products' => $wishlist_products,
                'href' => $this->url->link('account/wishlist/info', 'wishlist_id=' . $result['wishlist_id'], 'SSL'),
            ];
        }

        $data['blocks'] = [];

        $blocks = $this->model_tool_image->getBlocks();

        // echo "<pre>";print_r($blocks);die;
        foreach ($blocks as $block) {
            if (is_file(DIR_IMAGE . $block['image'])) {
                $image = $this->model_tool_image->resize($block['image'], 290, 163);
            } else {
                $image = $this->model_tool_image->resize('no_image.png', 290, 163);
            }

            $temp['image'] = $image;
            $temp['description'] = trim($block['description']);
            $temp['title'] = $block['title'];
            $temp['sort_order'] = $block['sort_order'];

            array_push($data['blocks'], $temp);
        }

        $this->load->model('sale/order');
        $numberOfOrders = count($this->model_sale_order->getOrders());
        $data['order_count'] = $numberOfOrders;

        //echo "<pre>";print_r($data['blocks']);die;
        $this->document->setTitle($this->config->get('config_meta_title'));
        $this->document->setDescription($this->config->get('config_meta_description'));
        $this->document->setKeywords($this->config->get('config_meta_keyword'));

        $data['description'] = $this->document->getDescription();
        $data['keywords'] = $this->document->getKeywords();
        $data['metas'] = $this->document->getMetas();

        $data['zipcode_mask'] = $this->config->get('config_zipcode_mask');

        $data['zipcode_mask_number'] = '';

        if (isset($data['zipcode_mask'])) {
            $data['zipcode_mask_number'] = str_replace('#', '9', $this->config->get('config_zipcode_mask'));
        }

        $data['telephone_mask'] = $this->config->get('config_telephone_mask');

        if (isset($data['telephone_mask'])) {
            $data['telephone_mask_number'] = str_replace('#', '9', $this->config->get('config_telephone_mask'));
        }

        $data['taxnumber_mask'] = $this->config->get('config_taxnumber_mask');

        if (isset($data['taxnumber_mask'])) {
            $data['taxnumber_mask_number'] = str_replace('#', '*', $this->config->get('config_taxnumber_mask'));
        }

        //echo "<pre>";print_r($data);die;

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        if (!$this->config->get('config_seo_url') and isset($this->request->get['path'])) {
            $this->document->addLink($server, 'canonical');
        }

        if ($this->config->get('config_google_analytics_status')) {
            $data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');
        } else {
            $data['google_analytics'] = '';
        }

        $data['text_get_groceries'] = $this->language->get('text_get_groceries');
        $data['base'] = $server;
        $data['text_deliver_in'] = $this->language->get('text_deliver_in');
        $data['text_order_fresh'] = $this->language->get('text_order_fresh');
        $data['text_shop_at'] = $this->language->get('text_shop_at');

        $data['text_from_device'] = $this->language->get('text_from_device');
        $data['text_schedule_delivery'] = $this->language->get('text_schedule_delivery');
        $data['text_get_grocery_at'] = $this->language->get('text_get_grocery_at');
        $data['text_an_hour'] = $this->language->get('text_an_hour');

        $data['text_or_want_them'] = $this->language->get('text_or_want_them');
        $data['text_get_delivered'] = $this->language->get('text_get_delivered');
        $data['text_fresh_handpicked'] = $this->language->get('text_fresh_handpicked');
        $data['text_local_stores'] = $this->language->get('text_local_stores');

        $data['text_my_wishlist'] = $this->language->get('text_my_wishlist');

        $data['text_welcome_user'] = $this->language->get('text_welcome_user');
        $data['text_open_store'] = $this->language->get('text_open_store');
        $data['text_store_working'] = $this->language->get('text_store_working');
        $data['text_enter_zipcode_title'] = $this->language->get('text_enter_zipcode_title');
        $data['text_delivery_detail'] = $this->language->get('text_delivery_detail');
        $data['text_enter_zipcode'] = $this->language->get('text_enter_zipcode');
        $data['text_find_store'] = $this->language->get('text_find_store');
        $data['text_have_account'] = $this->language->get('text_have_account');
        $data['text_log_in'] = $this->language->get('text_log_in');
        $data['text_get_delivered'] = $this->language->get('text_get_delivered');
        $data['heading_title'] = $this->language->get('heading_title');

        if (isset($this->session->data['error'])) {
            $data['error_warning'] = $this->session->data['error'];

            unset($this->session->data['error']);
        } else {
            $data['error_warning'] = '';
        }

        $data['text_move_next'] = $this->language->get('text_move_next');
        $data['text_login'] = $this->language->get('text_login');
        $data['text_back'] = $this->language->get('text_back');
        $data['text_enter_code_in_area'] = $this->language->get('text_enter_code_in_area');
        $data['text_move_Next'] = $this->language->get('text_move_Next');
        $data['text_enter_you_agree'] = $this->language->get('text_enter_you_agree');
        $data['text_terms_of_service'] = $this->language->get('text_terms_of_service');
        $data['text_privacy_policy'] = $this->language->get('text_privacy_policy');
        $data['text_get_delivered_download_apps'] = $this->language->get('text_get_delivered_download_apps');

        $data['support'] = $this->language->get('support');
        $data['faq'] = $this->language->get('faq');
        $data['call'] = $this->language->get('call');
        $data['text'] = $this->language->get('text');
        $data['text_account'] = $this->language->get('text_account');
        $data['text_rewards'] = $this->language->get('text_rewards');
        $data['text_orders'] = $this->language->get('text_orders');
        $data['text_refer'] = $this->language->get('text_refer');
        $data['text_sign_out'] = $this->language->get('text_sign_out');
        $data['text_sign_in'] = $this->language->get('text_sign_in');
        $data['text_heading'] = $this->language->get('text_heading');
        $data['text_heading2'] = $this->language->get('text_heading2');
        $data['text_heading3'] = $this->language->get('text_heading3');
        $data['text_heading4'] = $this->language->get('text_heading4');
        $data['text_heading5'] = $this->language->get('text_heading5');
        $data['text_heading6'] = $this->language->get('text_heading6');

        $data['text_my_cash'] = $this->language->get('text_my_cash');
        $data['label_my_address'] = $this->language->get('label_my_address');
        $data['text_my_profile'] = $this->language->get('text_my_profile');
        $data['contactus'] = $this->language->get('contactus');
        $data['text_logout'] = $this->language->get('text_logout');
        $data['text_register'] = $this->language->get('text_register');

        $data['step1'] = $this->language->get('step1');
        $data['step2'] = $this->language->get('step2');
        $data['step3'] = $this->language->get('step3');
        $data['step4'] = $this->language->get('step4');

        $data['label_start'] = $this->language->get('label_start');
        $data['label_name'] = $this->language->get('label_name');
        $data['label_email/phone'] = $this->language->get('label_email/phone');
        $data['label_msg'] = $this->language->get('label_msg');

        $data['button_send'] = $this->language->get('button_send');

        $data['is_login'] = $this->customer->isLogged();
        $data['f_name'] = $this->customer->getFirstName();
        $data['name'] = $this->customer->getFirstName();
        $data['l_name'] = $this->customer->getLastName();
        $data['full_name'] = $data['f_name']; //.' '.$data['l_name'];
        $data['home'] = $this->url->link('common/home');
        $data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
        $data['logged'] = $this->customer->isLogged();
        $data['account'] = $this->url->link('account/account', '', 'SSL');
        $data['dashboard'] = $this->url->link('account/dashboard', '', 'SSL');
        $data['feedback'] = $this->url->link('account/feedback', '', 'SSL');
        $data['po_ocr'] = $this->url->link('account/ocr', '', 'SSL');

        $data['register'] = $this->url->link('account/register', '', 'SSL');
        $data['login'] = $this->url->link('account/login', '', 'SSL');
        $data['order'] = $this->url->link('account/order', '', 'SSL');
        $data['credit'] = $this->url->link('account/credit', '', 'SSL');
        $data['download'] = $this->url->link('account/download', '', 'SSL');
        $data['logout'] = $this->url->link('account/logout', '', 'SSL');
        $data['shopping_cart'] = $this->url->link('checkout/cart');
        $data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
        $data['contact'] = $this->url->link('information/contact');
        $data['telephone'] = $this->config->get('config_telephone');
        $data['refer'] = $this->url->link('account/refer', '', 'SSL');
        $data['reward'] = $this->url->link('account/reward', '', 'SSL');
        $data['footer'] = $this->load->controller('common/footer');
        $data['action'] = $this->url->link('common/home/find_store');
        $data['address'] = $this->url->link('account/address', '', 'SSL');
        $data['help'] = $this->url->link('information/help');

        $data['language'] = $this->load->controller('common/language/dropDown');

        $log->write('home tpl 3');
        $data['login_modal'] = $this->load->controller('common/login_modal');

        $log->write('home tpl 3.1');
        $data['signup_modal'] = $this->load->controller('common/signup_modal');

        $log->write('home tpl 3.2');
        $data['forget_modal'] = $this->load->controller('common/forget_modal');

        $log->write('home tpl 4');

        $data['heading_title'] = $this->config->get('config_meta_title', '');

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
            //$data['icon'] = $server . 'image/' . $this->config->get('config_icon');
            $data['icon'] = $this->model_tool_image->resize($this->config->get('config_icon'), 30, 30);
        } else {
            $data['icon'] = '';
        }

        if (is_file(DIR_IMAGE . $this->config->get('config_fav_icon'))) {
            $data['fav_icon'] = $server . 'image/' . $this->config->get('config_fav_icon');
        } else {
            $data['fav_icon'] = '';
        }

        if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
            //$data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'),200,110);
            $data['logo'] = $server . 'image/' . $this->config->get('config_logo');
        } else {
            $data['logo'] = 'assets/img/logo.svg';
        }

        $data['playStorelogo'] = $this->model_tool_image->resize('play-store-logo.png', 200, 60);

        $data['appStorelogo'] = $this->model_tool_image->resize('app-store-logo.png', 200, 60);

        if (isset($this->session->data['warning'])) {
            $data['warning'] = $this->session->data['warning'];
            unset($this->session->data['warning']);
        } else {
            $data['warning'] = '';
        }

        $data['banners'] = $data['testimonials'] = [];

        $rows = $this->model_tool_image->getTestimonial();

        foreach ($rows as $row) {
            $row['thumb'] = $this->model_tool_image->resize($row['image'], 80, 80);
            $data['testimonials'][] = $row;
        }

        //banners
        $rows = $this->model_tool_image->getAllOffers();

        foreach ($rows as $row) {
            if (false === strpos($row['link'], '://')) {
                $row['link'] = 'http://' . $row['link'];
            }
            $row['image'] = $this->model_tool_image->resize($row['image'], 300, 300);
            $data['banners'][] = $row;
        }

        $data['play_store'] = $this->config->get('config_android_app_link');
        $data['app_store'] = $this->config->get('config_apple_app_link');

        //echo "<pre>";print_r($this->config->get('config_android_app_link'));die;
        $this->load->model('setting/setting');
        $te = $this->model_setting_setting->getSetting('config');

        if ($te) {
            $data['play_store'] = $te['config_android_app_link'];
            $data['app_store'] = $te['config_apple_app_link'];
        }
        #region fix-sometimes cache getting clear and getting category pricing issues
        // $cachePrice_data = $this->cache->get('category_price_data');
        // if($cachePrice_data==null)
        // {
        //     $this->load->model('account/customer');
        //     $this->model_account_customer->cacheProductPrices(ACTIVE_STORE_ID);
        //     $cachePrice_data = $this->cache->get('category_price_data');
        // }
        ////   echo '<pre>';print_r($cachePrice_data);exit;
        #endregion

        $data['login'] = $this->url->link('account/login', '', 'SSL');
        $data['register'] = $this->url->link('account/register', '', 'SSL');
        $data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');
        $this->load->model('assets/category');
        $data['categories'] = [];
        $this->load->controller('product/store');
        $new_categories = $this->model_assets_category->getCategoryByStoreId(ACTIVE_STORE_ID, 0);
        $data['categories_new'] = $new_categories;
        //$categories = $this->model_assets_category->getCategoriesNoRelationStore();
        $selected_categoory_id = isset($this->request->get['filter_category']) && $this->request->get['filter_category'] > 0 ? $this->request->get['filter_category'] : 0;
        if ($selected_categoory_id == 0) {
            $categories = $this->model_assets_category->getCategoryByStoreId(ACTIVE_STORE_ID, 0);
        } else {
            $categories = $this->model_assets_category->getCategoryById(ACTIVE_STORE_ID, 0, $selected_categoory_id);
        }

        /* $log = new Log('error.log');
          $log->write('categories');
          $log->write($categories);
          $log->write('categories'); */

        $selectedProducts = [];
        foreach ($categories as $category) {
            // Level 2
            $children_data = [];

            $children = $this->model_assets_category->getCategories($category['category_id']);

            //echo "<pre>";print_r($children);die;
            foreach ($children as $child) {
                $children_data[] = [
                    'name' => $child['name'],
                    'id' => $child['category_id'],
                    'href' => $this->url->link('product/category', 'category=' . $category['category_id'] . '_' . $child['category_id']),
                ];
            }

            $filter_data_product = [
                'filter_category_id' => $category['category_id'],
                'filter_sub_category' => true,
                'start' => 0,
                'limit' => (1359 == $category['category_id']) ? 12 : 12,
                'store_id' => ACTIVE_STORE_ID,
                'selectedProducts' => $selectedProducts,
                'filter_sort' => isset($this->request->get['filter_sort']) || $this->request->get['filter_sort'] != NULL ? $this->request->get['filter_sort'] : NULL
            ];

            // Level 1
            $productslisted = $this->getProducts($filter_data_product);
            $data['categories'][] = [
                'name' => $category['name'],
                'id' => $category['category_id'],
                'thumb' => $this->model_tool_image->resize($category['image'], 300, 300),
                'children' => $children_data,
                'column' => $category['column'] ? $category['column'] : 1,
                'href' => $this->url->link('product/category', 'category=' . $category['category_id']),
                'products' => $productslisted,
            ];

            foreach ($productslisted as $producted) {
                $selectedProducts[] = $producted['product_store_id'];
            }
        }
        //	   echo "<pre>";print_r($data['categories']);die;
        $data['page'] = isset($_REQUEST['page']) ? $_REQUEST['page'] : '';
        //$this->load->language('module/store');
        $this->load->model('setting/store');
        $filter = [];
        $filter['filter_status'] = 1;
        if (isset($_REQUEST['location'])) {
            $userSearch = explode(',', $_REQUEST['location']);
            $filter['filter_location'] = $_REQUEST['location'];
        }
        if (isset($_REQUEST['category'])) {
            $filter['filter_category'] = $_REQUEST['category'];
        }
        //echo'<pre>';print_r($filter);exit;
        //$categoriesIds = $this->model_setting_store->getStoreCategoriesbyStoreId(2,$_REQUEST['category']);
        //echo'<pre>';print_r($categoriesIds);exit;
        $stores = $this->model_setting_store->getStoresAll($filter);

        /* Code for storeType Dynamic */
        $this->load->model('assets/category');
        $store_types = $this->model_assets_category->getStoreTypes();
        $tempStoreTypeArray = [];
        foreach ($store_types as $value) {
            $tempStoreTypeArray[$value['store_type_id']] = $value['name'];
        }
        // echo'<pre>';print_r($stores);exit;
        foreach ($stores as $store) {
            $tempStore = $store;
            $tempStore['href'] = $this->model_setting_store->getSeoUrl('store_id=' . $store['store_id']);
            $tempStore['thumb'] = $this->model_tool_image->resize($store['logo'], 300, 300);
            $tempStore['categorycount'] = $this->model_setting_store->getStoreCategoriesbyStoreId($store['store_id'], isset($_REQUEST['category']) ? $_REQUEST['category'] : '');
            if (!empty($store['store_type_ids'])) {
                $arrayStoretypes = explode(',', $store['store_type_ids']);
                $tempStoretypename = '';
                foreach ($arrayStoretypes as $key => $types) {
                    $tempStoretypename .= (0 == $key) ? $tempStoreTypeArray[$types] : ',' . $tempStoreTypeArray[$types];
                }
                $tempStore['storeTypes'] = $tempStoretypename;
            }

            if (isset($_REQUEST['location']) && isset($_REQUEST['category'])) {
                //echo 'locat';exit;
                $res = $this->model_setting_store->getDistance($userSearch[0], $userSearch[1], $store['latitude'], $store['longitude'], $store['serviceable_radius']);
                if ($res && ($tempStore['categorycount'] > 0)) {
                    $data['stores'][] = $tempStore;
                }
            } elseif (isset($_REQUEST['location'])) {
                //echo 'loc';exit;
                $res = $this->model_setting_store->getDistance($userSearch[0], $userSearch[1], $store['latitude'], $store['longitude'], $store['serviceable_radius']);
                if ($res) {
                    $data['stores'][] = $tempStore;
                }
            } elseif (isset($_REQUEST['category'])) {
                //echo 'cat';exit;
                // $categorycount = $this->model_setting_store->getStoreCategoriesbyStoreId($store['store_id'],$_REQUEST['category']);
                if ($tempStore['categorycount'] > 0) {
                    $data['stores'][] = $tempStore;
                }
            } else {
                //echo 'no';exit;
                $data['stores'][] = $tempStore;
            }
            /* if($_REQUEST['category']){
              $categorycount = $this->model_setting_store->getStoreCategoriesbyStoreId($store['store_id'],$_REQUEST['category']);
              if($categorycount > 0){
              $data['stores'][] = $tempStore;
              }
              }else{
              $data['stores'][] = $tempStore;
              } */
        }
        //	    echo'<pre>';print_r($data['stores']);exit;
        // 5 best seller product
        $complete_status_ids = '(' . implode(',', $this->config->get('config_complete_status')) . ')';
        $query_best = $this->db->query('SELECT SUM( op.quantity )AS total, op.product_id,op.general_product_id, pd.name FROM ' . DB_PREFIX . 'order_product AS op LEFT JOIN ' . DB_PREFIX . 'order AS o ON ( op.order_id = o.order_id ) LEFT JOIN  ' . DB_PREFIX . "product_description AS pd ON (op.general_product_id = pd.product_id)  WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND o.order_status_id IN " . $complete_status_ids . ' GROUP BY pd.name ORDER BY total DESC LIMIT 5');
        $best_products = $query_best->rows;

        foreach ($best_products as $products) {
            $product_detail = $this->model_assets_product->getDetailproduct($products['product_id']);
            $product_detail['thumb'] = $this->model_tool_image->resize(isset($product_detail['image']) ? $product_detail['image'] : '', 100, 100);
            $data['bestseller'][] = $product_detail;
        }

        /** Products To Percentage off * */
        $prductsOffer = $this->getProducts([
            'store_id' => ACTIVE_STORE_ID,
        ]);
        $this->array_sort_by_column($prductsOffer, 'percent_off');
        $data['offer_products'] = array_slice($prductsOffer, 0, 5, true);
        //echo '<pre>';print_r($data['offer_products']);exit;
        /* add Contact modal */
        $data['contactus_modal'] = $this->load->controller('information/contact');
        $data['checkout_summary'] = $this->url->link('checkout/checkoutitems', '', 'SSL');
        $data['mostboughtproducts'] = array_slice($this->getMostBoughtProducts(), 0, 6);
        $data['mostboughtproducts_url'] = $this->url->link('product/store/featuredproducts', '', 'SSL');
        $data['cartproducts'] = $this->cart->getProducts();
        $data['wallet_url'] = $this->url->link('account/credit', '', 'SSL');
        $data['wallet_amount'] = $this->load->controller('account/credit/getWalletTotal');
        /* $log->write('mostboughtproducts');
          $log->write($this->cart->getProducts());
          $log->write($this->getMostBoughtProducts());
          $log->write('mostboughtproducts'); */
        $data['category_url'] = $this->url->link('common/home', '', 'SSL');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/home.tpl') && isset($this->session->data['customer_id'])) {
            // $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/home.tpl', $data));
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/homenew.tpl', $data));
        } else {
            if ($this->request->server['HTTPS']) {
                $server = $this->config->get('config_ssl');
            } else {
                $server = $this->config->get('config_url');
            }

            $data['base'] = $server;
            $data['is_login'] = $this->customer->isLogged();
            $data['f_name'] = $this->customer->getFirstName();
            $data['name'] = $this->customer->getFirstName();
            $data['l_name'] = $this->customer->getLastName();
            $data['full_name'] = $data['f_name']; //.' '.$data['l_name'];
            $data['home'] = $this->url->link('common/home');
            $data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
            $data['logged'] = $this->customer->isLogged();
            $data['account'] = $this->url->link('account/account', '', 'SSL');
            $data['dashboard'] = $this->url->link('account/dashboard', '', 'SSL');
            $data['feedback'] = $this->url->link('account/feedback', '', 'SSL');

            $data['register'] = $this->url->link('account/register', '', 'SSL');
            $data['login'] = $this->url->link('account/login', '', 'SSL');
            $data['order'] = $this->url->link('account/order', '', 'SSL');
            $data['credit'] = $this->url->link('account/credit', '', 'SSL');
            $data['download'] = $this->url->link('account/download', '', 'SSL');
            $data['logout'] = $this->url->link('account/logout', '', 'SSL');
            $data['shopping_cart'] = $this->url->link('checkout/cart');
            $data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
            $data['contact'] = $this->url->link('information/contact');
            $data['telephone'] = $this->config->get('config_telephone');
            $data['refer'] = $this->url->link('account/refer', '', 'SSL');
            $data['reward'] = $this->url->link('account/reward', '', 'SSL');
            $data['footer'] = $this->load->controller('common/footer');
            $data['action'] = $this->url->link('common/home/find_store');
            $data['address'] = $this->url->link('account/address', '', 'SSL');
            $data['help'] = $this->url->link('information/help');
            $data['language'] = $this->load->controller('common/language/dropDown');
            $data['login'] = $this->url->link('account/login', '', 'SSL');
            $data['register'] = $this->url->link('account/register', '', 'SSL');
            $data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');
            $data['store_name'] = $this->config->get('config_name');

//    echo "<pre>";print_r($data);die;

            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/landing_page/index.tpl', $data));
            //$this->response->setOutput($this->load->view('default/template/common/home.tpl', $data));
        }
    }

    public function toHome() {
        //echo "cer";die;
        unset($this->session->data['config_store_id']);
        unset($this->session->data['zipcode']);

        unset($_COOKIE['zipcode']);
        unset($_COOKIE['location']);

        setcookie('zipcode', null, time() - 3600, '/');
        setcookie('location', null, time() - 3600, '/');

        setcookie('location_name', null, time() - 3600, '/');

        unset($this->session->data['shipping_method']);
        unset($this->session->data['shipping_methods']);
        unset($this->session->data['payment_method']);
        unset($this->session->data['payment_methods']);
        unset($this->session->data['guest']);
        unset($this->session->data['comment']);
        unset($this->session->data['order_id']);
        unset($this->session->data['coupon']);
        unset($this->session->data['reward']);
        unset($this->session->data['voucher']);
        unset($this->session->data['vouchers']);
        unset($this->session->data['totals']);

        $this->cart->clear();

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $this->response->redirect($server);
    }

    public function toStore() {
        if (!$this->config->get('config_multi_store')) {
            unset($this->session->data['config_store_id']);
            $this->cart->clear();
        }

        $this->response->redirect($this->url->link('common/home/index'));
    }

    public function array_sort_by_column(&$arr, $col, $dir = SORT_DESC) {
        $sort_col = [];
        foreach ($arr as $key => $row) {
            $sort_col[$key] = $row[$col];
        }
        array_multisort($sort_col, $dir, $arr);
    }

    public function cartItemDetails() {
        $this->load->language('common/home');

        //check login
        $this->load->model('account/address');

        $json['status'] = true;
        $json['amount'] = 0;
        $currentprice = 'initial';
        $log = new Log('error.log');
        $log->write('1');
        $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_checkout.css');

        $json['href'] = $this->url->link('checkout/checkoutitems', '', 'SSL');

        // Validate cart has products and has stock.
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) /* || ( !$this->cart->hasStock() && !$this->config->get( 'config_stock_checkout' ) ) */) {
            $log->write('2');
            $json['status'] = false;
            $currentprice = 0;
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }

        $data['total_quantity'] = 0;
        $data['product_total_amount'] = 0;

        // Validate minimum quantity requirements.
        $products = $this->cart->getProducts();
        //echo '<pre>';print_r($products);exit;
        $product_total_count = 0;
        $product_total_amount = 0;

        $data['products_details'] = [];

        $this->load->model('tool/image');
        foreach ($products as $product) {
            $product_total = 0;

            foreach ($products as $product_2) {
                if ($product_2['product_store_id'] == $product['product_store_id']) {
                    $product_total += $product_2['quantity'];
                }
            }

            if ($product['image']) {
                $image = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
            } else {
                $image = '';
            }

            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
            } else {
                $price = false;
            }
            $log->write('2.x');
            // Display prices
            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $total = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']);
            } else {
                $total = false;
            }

            $product_total_count += $product['quantity'];
            $product_total_amount += $product['total'];

            $data['products_details'][] = [
                'key' => $product['key'],
                'product_store_id' => $product['product_store_id'],
                'thumb' => $image,
                'name' => $product['name'],
                'unit' => $product['unit'],
                'model' => $product['model'],
                'quantity' => $product['quantity'],
                'stock' => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
                'reward' => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
                'price' => $price,
                'total' => $total,
                'href' => $this->url->link('product/product', 'product_store_id=' . $product['product_store_id']),
            ];

            /* if ( $product['minimum'] > $product_total ) {
              $json['status'] = false;
              $message = 'product has minimum requirement';

              $this->response->addHeader('Content-Type: application/json');
              $this->response->setOutput(json_encode($json));
              } */
        }
        $log->write('2.3');
        // echo "<pre>";print_r($data['products_details']);die;
        $data['total_quantity'] = $product_total_count;

        $data['product_total_amount'] = $this->currency->format($product_total_amount);

        $order_stores = $this->cart->getStores();
        $min_order_or_not = [];
        $store_data = [];

        /*
          sort array to make this current store as lst element
         */

        foreach ($order_stores as $key => $value) {
            if ($value == $this->session->data['config_store_id']) {
                unset($order_stores[$key]);
            }
        }

        if (isset($this->session->data['config_store_id'])) {
            array_push($order_stores, $this->session->data['config_store_id']);
        }

        /* $json['store_note'] = "<center style='background-color:#ee4054'> $2.3 away from minimum order value</center>'";
          $json['store_note'] = "<center style='background-color:#ff811e'> $2.3 away from minimum order value</center>'"; */

        foreach ($order_stores as $os) {
            $json['store_note'][$os] = "<center style='background-color:#43b02a;color:#fff'> Yay! Free Delivery </center>";

            $store_info = $this->model_account_address->getStoreData($os);

            // echo "<pre>";print_r($store_info);die;
            $store_total = $this->cart->getSubTotal($os);
            $store_data[] = $store_info;

            if ((0 <= $store_info['min_order_cod']) && ($store_info['min_order_cod'] <= 10000)) {
                if ($store_info['min_order_cod'] > $store_total) {
                    $freedeliveryprice = $store_info['min_order_cod'] - $store_total;

                    $json['store_note'][$os] = "<center style='background-color:#ff811e;color:#fff'> You are only " . $this->currency->format($freedeliveryprice) . ' away for FREE DELIVERY! </center>';
                }
            } else {
                $json['store_note'][$os] = '';
            }

            if ($this->cart->getTotalProductsByStore($os) && $this->config->get('config_active_store_minimum_order_amount') > $this->cart->getSubTotal()) {
                $log->write('3');
                $currentprice = $this->config->get('config_active_store_minimum_order_amount') - $this->cart->getSubTotal();
                $store_name = $store_info['name'];
                $json['status'] = false;
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($json));

                $json['store_note'][$os] = "<center style='background-color:#ee4054;color:#fff'>" . $this->currency->format($currentprice) . ' away from minimum order value </center>';
            }
        }
        $log->write('4');

        if ($json['status']) {
            $json['status'] = true;
        }

        if (0 == $currentprice) {
            $json['amount'] = $this->language->get('text_no_product');
        } else {
            $json['amount'] = $this->currency->format($currentprice) . ' ' . $this->language->get('text_away_from') . ' ( ' . $store_name . ' )'; //. $this->language->get('text_store') .' )';
        }

        $json['text_proceed_to_checkout'] = $this->language->get('text_proceed_to_checkout');

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function cartDetails() {
        $this->load->language('common/home');

        //check login
        $this->load->model('account/address');

        $json['status'] = true;
        $json['amount'] = 0;
        $currentprice = 'initial';
        $log = new Log('error.log');
        $log->write('1');
        $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_checkout.css');

        $json['href'] = $this->url->link('checkout/checkoutitems', '', 'SSL');

        // Validate cart has products and has stock.
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) /* || ( !$this->cart->hasStock() && !$this->config->get( 'config_stock_checkout' ) ) */) {
            $log->write('2');
            $json['status'] = false;
            $currentprice = 0;
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }

        $data['total_quantity'] = 0;
        $data['product_total_amount'] = 0;

        // Validate minimum quantity requirements.
        $products = $this->cart->getProducts();
        $product_total_count = 0;
        $product_total_amount = 0;

        $data['products_details'] = [];

        $this->load->model('tool/image');
        foreach ($products as $product) {
            $product_total = 0;

            foreach ($products as $product_2) {
                if ($product_2['product_store_id'] == $product['product_store_id']) {
                    $product_total += $product_2['quantity'];
                }
            }

            if ($product['image']) {
                $image = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
            } else {
                $image = '';
            }

            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
            } else {
                $price = false;
            }
            $log->write('2.x');
            // Display prices
            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $total = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']);
            } else {
                $total = false;
            }

            $product_total_count += $product['quantity'];
            $product_total_amount += $product['total'];

            $data['products_details'][] = [
                'key' => $product['key'],
                'product_store_id' => $product['product_store_id'],
                'thumb' => $image,
                'name' => $product['name'],
                'unit' => $product['unit'],
                'model' => $product['model'],
                'quantity' => $product['quantity'],
                'stock' => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
                'reward' => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
                'price' => $price,
                'total' => $total,
                'href' => $this->url->link('product/product', 'product_store_id=' . $product['product_store_id']),
            ];

            /* if ( $product['minimum'] > $product_total ) {
              $json['status'] = false;
              $message = 'product has minimum requirement';

              $this->response->addHeader('Content-Type: application/json');
              $this->response->setOutput(json_encode($json));
              } */
        }
        $log->write('2.3');
        // echo "<pre>";print_r($data['products_details']);die;
        $data['total_quantity'] = $product_total_count;

        $data['product_total_amount'] = $this->currency->format($product_total_amount);

        $order_stores = $this->cart->getStores();
        $min_order_or_not = [];
        $store_data = [];

        /*
          sort array to make this current store as lst element
         */

        foreach ($order_stores as $key => $value) {
            if ($value == $this->session->data['config_store_id']) {
                unset($order_stores[$key]);
            }
        }

        if (isset($this->session->data['config_store_id'])) {
            array_push($order_stores, $this->session->data['config_store_id']);
        }

        /* $json['store_note'] = "<center style='background-color:#ee4054'> $2.3 away from minimum order value</center>'";
          $json['store_note'] = "<center style='background-color:#ff811e'> $2.3 away from minimum order value</center>'"; */

        foreach ($order_stores as $os) {
            $json['store_note'][$os] = "<center style='background-color:#43b02a;color:#fff'> Yay! Free Delivery </center>";

            $store_info = $this->model_account_address->getStoreData($os);

            // echo "<pre>";print_r($store_info);die;
            $store_total = $this->cart->getSubTotal($os);
            $store_data[] = $store_info;

            if ((0 <= $store_info['min_order_cod']) && ($store_info['min_order_cod'] <= 10000)) {
                if ($store_info['min_order_cod'] > $store_total) {
                    $freedeliveryprice = $store_info['min_order_cod'] - $store_total;

                    $json['store_note'][$os] = "<center style='background-color:#ff811e;color:#fff'> You are only " . $this->currency->format($freedeliveryprice) . ' away for FREE DELIVERY! </center>';
                }
            } else {
                $json['store_note'][$os] = '';
            }

            if ($this->cart->getTotalProductsByStore($os) && $this->config->get('config_active_store_minimum_order_amount') > $this->cart->getSubTotal()) {
                $log->write('3');
                $currentprice = $this->config->get('config_active_store_minimum_order_amount') - $this->cart->getSubTotal();
                $store_name = $store_info['name'];
                $json['status'] = false;
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($json));

                $json['store_note'][$os] = "<center style='background-color:#ee4054;color:#fff'>" . $this->currency->format($currentprice) . ' away from minimum order value </center>';
            }
        }
        $log->write('4');

        if ($json['status']) {
            $json['status'] = true;
        }

        if (0 == $currentprice) {
            $json['amount'] = $this->language->get('text_no_product');
        } else {
            $json['amount'] = $this->currency->format($currentprice) . ' ' . $this->language->get('text_away_from') . ' ( ' . $store_name . ' )'; //. $this->language->get('text_store') .' )';
        }

        $json['text_proceed_to_checkout'] = $this->language->get('text_proceed_to_checkout');

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getProducts($filter_data) {
        $this->load->model('assets/product');
        $this->load->model('tool/image');
        $this->load->model('user/user');

        $cachePrice_data = $this->cache->get('category_price_data');
        // echo '<pre>';print_r($cachePrice_data);exit;
        //$results = $this->model_assets_product->getProducts($filter_data);
        $results = $this->model_assets_product->getProductsForHomePage($filter_data);

        $data['products'] = [];

        // echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            $vendor_details = $this->model_user_user->getUser($result['merchant_id']);
            /* $log = new Log('error.log');
              $log->write('vendor_details');
              $log->write($vendor_details);
              $log->write('vendor_details */
            // if qty less then 1 dont show product
            //REMOVED QUANTITY CHECK CONDITION
            /* if ($result['quantity'] <= 0) {
              continue;
              } */

            $log = new Log('error.log');
            if ($result['image'] != NULL && file_exists(DIR_IMAGE . $result['image'])) {
                $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
            } else if ($result['image'] == NULL || !file_exists(DIR_IMAGE . $result['image'])) {
                $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
            }

            //if category discount define override special price

            $discount = '';

            $s_price = 0;
            $o_price = 0;

            if (!$this->config->get('config_inclusiv_tax')) {
                //FOR CATEGORY PRICING
                $category_s_price = 0;
                $category_o_price = 0;
                if (CATEGORY_PRICE_ENABLED == true && isset($cachePrice_data) && isset($cachePrice_data[$result['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $result['store_id']])) {
                    $category_s_price = $cachePrice_data[$result['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $result['store_id']];
                    $category_o_price = $cachePrice_data[$result['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $result['store_id']];
                    if ($category_s_price != NULL && $category_s_price > 0) {
                        $result['price'] = $category_s_price;
                        $result['special_price'] = $category_s_price;
                    }
                }
                //FOR CATEGORY PRICING
                //get price html
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));

                    $o_price = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
                } else {
                    $price = false;
                }
                if ((float) $result['special_price']) {
                    $special_price = $this->currency->format($this->tax->calculate($result['special_price'], $result['tax_class_id'], $this->config->get('config_tax')));

                    $s_price = $this->tax->calculate($result['special_price'], $result['tax_class_id'], $this->config->get('config_tax'));
                } else {
                    $special_price = false;
                }
            } else {
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($result['price']);
                } else {
                    $price = $result['price'];
                }

                if ((float) $result['special_price']) {
                    $special_price = $this->currency->format($result['special_price']);
                } else {
                    $special_price = $result['special_price'];
                }

                $s_price = $result['special_price'];
                $o_price = $result['price'];

                // echo $s_price.'===>'.$o_price.'==>'.$special_price.'===>'.$price.'</br>';//exit;

                if (CATEGORY_PRICE_ENABLED == true && isset($cachePrice_data) && isset($cachePrice_data[$result['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $result['store_id']])) {
                    $s_price = $cachePrice_data[$result['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $result['store_id']];
                    $o_price = $cachePrice_data[$result['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $result['store_id']];
                    $special_price = $this->currency->format($s_price);
                    $price = $this->currency->format($o_price);
                }
            }

            //get qty in cart
            if (!empty($this->session->data['config_store_id'])) {
                $key = base64_encode(serialize(['product_store_id' => (int) $result['product_store_id'], 'store_id' => $this->session->data['config_store_id']]));
            } else {
                //PREVIOUS CODE
                //$key = base64_encode(serialize(['product_store_id' => (int) $result['product_store_id'], 'store_id' => $result['store_id']]));
                $key = base64_encode(serialize(['product_store_id' => (int) $result['product_store_id'], 'store_id' => $result['store_id']]));
            }
            if (isset($this->session->data['cart'][$key])) {
                $qty_in_cart = $this->session->data['cart'][$key]['quantity'];
            } else {
                $qty_in_cart = 0;
            }

            //$result['name'] = strlen($result['name']) > 27 ? substr($result['name'],0,27)."..." : $result['name'];
            $name = $result['name'];
            if (isset($result['pd_name'])) {
                $name = $result['pd_name'];
            }

            //$name .= str_repeat('&nbsp;',30 - strlen($result['name']));

            $percent_off = null;
            if (isset($s_price) && isset($o_price) && 0 != $o_price && 0 != $s_price) {
                $percent_off = (($o_price - $s_price) / $o_price) * 100;
            }

            // Avoid adding duplicates for similar products with different variations

            $productNames = array_column($data['products'], 'name');
            if (false !== array_search($result['name'], $productNames)) {
                // Add variation to existing product
                $productIndex = array_search($result['name'], $productNames);
                // TODO: Check for product variation duplicates
                $data['products'][$productIndex][variations][] = [
                    'variation_id' => $result['product_store_id'],
                    'unit' => $result['unit'],
                    'weight' => floatval($result['weight']),
                    'price' => $price,
                    'special' => $special_price,
                ];
            } else {
                // Add as new product
                $data['products'][] = [
                    'key' => $key,
                    'qty_in_cart' => $qty_in_cart,
                    'variations' => $this->model_assets_product->getVariations($result['product_store_id']),
                    'store_product_variation_id' => 0,
                    'product_id' => $result['product_id'],
                    'product_store_id' => $result['product_store_id'],
                    'default_variation_name' => $result['default_variation_name'],
                    'thumb' => $image,
                    'name' => $name,
                    'store_id' => $result['store_id'],
                    'variations' => [
                        [
                            'variation_id' => $result['product_store_id'],
                            'unit' => $result['unit'],
                            'weight' => floatval($result['weight']),
                            'price' => $price,
                            'special' => $special_price,
                        ],
                    ],
                    'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
                    'percent_off' => number_format($percent_off, 0),
                    'tax' => $result['tax_percentage'],
                    'minimum' => $result['min_quantity'] > 0 ? $result['min_quantity'] : $result['quantity'],
                    'rating' => 0,
                    'href' => $this->url->link('product/product', '&product_store_id=' . $result['product_store_id']),
                    'vendor_display_name' => $vendor_details['display_name'],
                    'sort_price' => $s_price
                ];
            }
        }
        // echo "<pre>";print_r($data['products']);die;
        $log = new Log('error.log');
        $log->write('filter_data');
        $log->write($filter_data);
        $log->write('filter_data');
        if (isset($filter_data['filter_sort']) && $filter_data['filter_sort'] != NULL && $filter_data['filter_sort'] == 'nasc') {
            $new_arry = $this->multisort($data['products'], 'name', 'nasc');
            $log = new Log('error.log');
            $log->write('products');
            $log->write($new_arry);
            $log->write('products');
            return $new_arry;
        } else if (isset($filter_data['filter_sort']) && $filter_data['filter_sort'] != NULL && $filter_data['filter_sort'] == 'ndesc') {
            $new_arry = $this->multisort($data['products'], 'name', 'ndesc');
            $log = new Log('error.log');
            $log->write('products');
            $log->write($new_arry);
            $log->write('products');
            return $new_arry;
        } else if (isset($filter_data['filter_sort']) && $filter_data['filter_sort'] != NULL && $filter_data['filter_sort'] == 'pasc') {
            $new_arry = $this->multisort($data['products'], 'sort_price', 'pasc');
            $log = new Log('error.log');
            $log->write('products');
            $log->write($new_arry);
            $log->write('products');
            return $new_arry;
        } else if (isset($filter_data['filter_sort']) && $filter_data['filter_sort'] != NULL && $filter_data['filter_sort'] == 'pdesc') {
            $new_arry = $this->multisort($data['products'], 'sort_price', 'pdesc');
            $log = new Log('error.log');
            $log->write('products');
            $log->write($new_arry);
            $log->write('products');
            return $new_arry;
        } else {
            return $data['products'];
        }
    }

    public function getCartDetails() {

        $json = [];
        $this->load->language('common/home');

        $this->load->model('account/address');

        $order_stores = $this->cart->getStores();
        $min_order_or_not = [];
        $store_data = [];

        /*
          sort array to make this current store as lst element
         */

        foreach ($order_stores as $key => $value) {
            if ($value == $this->session->data['config_store_id']) {
                unset($order_stores[$key]);
            }
        }

        if (isset($this->session->data['config_store_id'])) {
            array_push($order_stores, $this->session->data['config_store_id']);
        }

        foreach ($order_stores as $os) {
            $store_info = $this->model_account_address->getStoreData($os);
            $store_total = $this->cart->getSubTotal($os);

            $json['store_total'] = $store_total;
            $json['proceed_to_checkout'] = TRUE;
            $json['url'] = $this->url->link('checkout/checkoutitems', '', 'SSL');
            $json['store_note'] = "<center style='background-color:#43b02a;color:#fff'> Yay! Free Delivery </center>";

            $store_data[] = $store_info;

            if ((0 <= $store_info['min_order_cod']) && ($store_info['min_order_cod'] <= 10000)) {
                if ($store_info['min_order_cod'] > $store_total) {
                    $freedeliveryprice = $store_info['min_order_cod'] - $store_total;

                    $json['store_total'] = $store_total;
                    $json['proceed_to_checkout'] = FALSE;
                    $json['url'] = '';
                    $json['store_note'] = "<center style='background-color:#ff811e;color:#fff'> You are only " . $this->currency->format($freedeliveryprice) . ' away for FREE DELIVERY! </center>';
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getMostBoughtProducts() {
        $this->load->model('assets/product');
        $this->load->model('tool/image');
        $this->load->model('user/user');

        $cachePrice_data = $this->cache->get('category_price_data');
        //echo '<pre>';print_r(ACTIVE_STORE_ID);exit;
        $results = $this->model_assets_product->getMostBoughtProducts(null, $this->customer->getId(), null);

        $data['products'] = [];

        //  echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            $vendor_details = $this->model_user_user->getUser($result['merchant_id']);
            /* $log = new Log('error.log');
              $log->write('vendor_details');
              $log->write($vendor_details);
              $log->write('vendor_details'); */
            // if qty less then 1 dont show product
            /* if ($result['quantity'] <= 0) {
              continue;
              } */

            if ($result['image'] != NULL && file_exists(DIR_IMAGE . $result['image'])) {
                $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
            } else if ($result['image'] == NULL || !file_exists(DIR_IMAGE . $result['image'])) {
                $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
            }

            //if category discount define override special price

            $discount = '';

            $s_price = 0;
            $o_price = 0;

            if (!$this->config->get('config_inclusiv_tax')) {
                //FOR CATEGORY PRICING
                $category_s_price = 0;
                $category_o_price = 0;
                if (CATEGORY_PRICE_ENABLED == true && isset($cachePrice_data) && isset($cachePrice_data[$result['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $result['store_id']])) {
                    $category_s_price = $cachePrice_data[$result['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $result['store_id']];
                    $category_o_price = $cachePrice_data[$result['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $result['store_id']];
                    if ($category_s_price != NULL && $category_s_price > 0) {
                        $result['price'] = $category_s_price;
                        $result['special_price'] = $category_s_price;
                    }
                }
                //FOR CATEGORY PRICING
                //get price html
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));

                    $o_price = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
                } else {
                    $price = false;
                }
                if ((float) $result['special_price']) {
                    $special_price = $this->currency->format($this->tax->calculate($result['special_price'], $result['tax_class_id'], $this->config->get('config_tax')));

                    $s_price = $this->tax->calculate($result['special_price'], $result['tax_class_id'], $this->config->get('config_tax'));
                } else {
                    $special_price = false;
                }
            } else {
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($result['price']);
                } else {
                    $price = $result['price'];
                }

                if ((float) $result['special_price']) {
                    $special_price = $this->currency->format($result['special_price']);
                } else {
                    $special_price = $result['special_price'];
                }

                $s_price = $result['special_price'];
                $o_price = $result['price'];

                //echo $s_price.'===>'.$o_price.'==>'.$special_price.'===>'.$price;//exit;

                if (CATEGORY_PRICE_ENABLED == true && isset($cachePrice_data) && isset($cachePrice_data[$result['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $result['store_id']])) {
                    $s_price = $cachePrice_data[$result['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $result['store_id']];
                    $o_price = $cachePrice_data[$result['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $result['store_id']];
                    $special_price = $this->currency->format($s_price);
                    $price = $this->currency->format($o_price);
                }
            }

            //get qty in cart
            if (!empty($this->session->data['config_store_id'])) {
                $key = base64_encode(serialize(['product_store_id' => (int) $result['product_store_id'], 'store_id' => $this->session->data['config_store_id']]));
            } else {
                $key = base64_encode(serialize(['product_store_id' => (int) $result['product_store_id'], 'store_id' => $result['store_id']]));
            }
            if (isset($this->session->data['cart'][$key])) {
                $qty_in_cart = $this->session->data['cart'][$key]['quantity'];
            } else {
                $qty_in_cart = 0;
            }

            //$result['name'] = strlen($result['name']) > 27 ? substr($result['name'],0,27)."..." : $result['name'];
            $name = $result['name'];
            if (isset($result['pd_name'])) {
                $name = $result['pd_name'];
            }

            //$name .= str_repeat('&nbsp;',30 - strlen($result['name']));

            $percent_off = null;
            if (isset($s_price) && isset($o_price) && 0 != $o_price && 0 != $s_price) {
                $percent_off = (($o_price - $s_price) / $o_price) * 100;
            }

            // Avoid adding duplicates for similar products with different variations

            $productNames = array_column($data['products'], 'name');
            if (false !== array_search($result['name'], $productNames)) {
                // Add variation to existing product
                $productIndex = array_search($result['name'], $productNames);
                // TODO: Check for product variation duplicates
                $data['products'][$productIndex][variations][] = [
                    'variation_id' => $result['product_store_id'],
                    'unit' => $result['unit'],
                    'weight' => floatval($result['weight']),
                    'price' => $price,
                    'special' => $special_price,
                ];
            } else {
                // Add as new product
                $data['products'][] = [
                    'key' => $key,
                    'qty_in_cart' => $qty_in_cart,
                    'variations' => $this->model_assets_product->getVariations($result['product_store_id']),
                    'store_product_variation_id' => 0,
                    'store_id' => $result['store_id'],
                    'product_id' => $result['product_id'],
                    'product_store_id' => $result['product_store_id'],
                    'default_variation_name' => $result['default_variation_name'],
                    'thumb' => $image,
                    'name' => $name,
                    'variations' => [
                        [
                            'variation_id' => $result['product_store_id'],
                            'unit' => $result['unit'],
                            'weight' => floatval($result['weight']),
                            'price' => $price,
                            'special' => $special_price,
                        ],
                    ],
                    'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
                    'percent_off' => number_format($percent_off, 0),
                    'tax' => $result['tax_percentage'],
                    'minimum' => $result['min_quantity'] > 0 ? $result['min_quantity'] : $result['quantity'],
                    'rating' => 0,
                    'href' => $this->url->link('product/product', '&product_store_id=' . $result['product_store_id']),
                    'vendor_display_name' => $vendor_details['display_name']
                ];
            }
        }
        //echo "<pre>";print_r($data['products']);die;

        return $data['products'];
    }

    function multisort(&$array, $key, $sort) {
        $valsort = array();
        $ret = array();
        reset($array);
        foreach ($array as $ii => $va) {
            $valsort[$ii] = $va[$key];
        }
        if ($sort == 'nasc') {
            asort($valsort, 2);
        } else if ($sort == 'ndesc') {
            arsort($valsort, 2);
        } else if ($sort == 'pasc') {
            asort($valsort, 1);
        } else if ($sort == 'pdesc') {
            arsort($valsort, 1);
        }
        foreach ($valsort as $ii => $va) {
            $ret[$ii] = $array[$ii];
        }
        $array = $ret;
        return $array;
    }

    function allproducts() {

        if (!isset($this->session->data['customer_id'])) {
            if (isset($_REQUEST['action']) && ('shop' == $_REQUEST['action'])) {
                $this->response->redirect($this->url->link('account/login/customer'));
            } else {
                /* REMOVED FOR HOME PAGE ON DOMINE NAME
                 * $this->response->redirect($this->url->link('common/home/homepage')); */
                if ($this->request->server['HTTPS']) {
                    $server = $this->config->get('config_ssl');
                } else {
                    $server = $this->config->get('config_url');
                }

                $data['base'] = $server;
                $data['is_login'] = $this->customer->isLogged();
                $data['f_name'] = $this->customer->getFirstName();
                $data['name'] = $this->customer->getFirstName();
                $data['l_name'] = $this->customer->getLastName();
                $data['full_name'] = $data['f_name']; //.' '.$data['l_name'];
                $data['home'] = $this->url->link('common/home');
                $data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
                $data['logged'] = $this->customer->isLogged();
                $data['account'] = $this->url->link('account/account', '', 'SSL');
                $data['dashboard'] = $this->url->link('account/dashboard', '', 'SSL');

                $data['register'] = $this->url->link('account/register', '', 'SSL');
                $data['login'] = $this->url->link('account/login', '', 'SSL');
                $data['order'] = $this->url->link('account/order', '', 'SSL');
                $data['credit'] = $this->url->link('account/credit', '', 'SSL');
                $data['download'] = $this->url->link('account/download', '', 'SSL');
                $data['logout'] = $this->url->link('account/logout', '', 'SSL');
                $data['shopping_cart'] = $this->url->link('checkout/cart');
                $data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
                $data['contact'] = $this->url->link('information/contact');
                $data['telephone'] = $this->config->get('config_telephone');
                $data['refer'] = $this->url->link('account/refer', '', 'SSL');
                $data['reward'] = $this->url->link('account/reward', '', 'SSL');
                $data['footer'] = $this->load->controller('common/footer');
                $data['action'] = $this->url->link('common/home/find_store');
                $data['address'] = $this->url->link('account/address', '', 'SSL');
                $data['help'] = $this->url->link('information/help');
                $data['language'] = $this->load->controller('common/language/dropDown');
                $data['login'] = $this->url->link('account/login', '', 'SSL');
                $data['register'] = $this->url->link('account/register', '', 'SSL');
                $data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');
                $data['store_name'] = $this->config->get('config_name');

                if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
                    //$data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'),200,110);
                    $data['logo'] = $server . 'image/' . $this->config->get('config_logo');
                } else {
                    $data['logo'] = 'assets/img/logo.svg';
                }

                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/landing_page/index.tpl', $data));
            }
        }

        if (isset($this->request->get['store_id'])) {
            $this->session->data['config_store_id'] = $this->request->get['store_id'];
        }

        $this->load->language('product/store');

        $this->load->model('assets/category');

        $this->load->model('assets/product');

        $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_categories.css');

        $this->load->model('tool/image');

        if (isset($this->request->get['filter'])) {
            $filter = $this->request->get['filter'];
        } else {
            $filter = '';
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'p.sort_order';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['limit'])) {
            $limit = $this->request->get['limit'];
        } else {
            $limit = $this->config->get('config_product_limit');
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        $title = 'Products';
        $this->document->setTitle($title);

        $data['text_add_to_list'] = $this->language->get('text_add_to_list');
        $data['text_list_name'] = $this->language->get('text_list_name');
        $data['text_add_to'] = $this->language->get('text_add_to');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_or'] = $this->language->get('text_or');
        $data['text_enter_list_name'] = $this->language->get('text_enter_list_name');
        $data['text_create_list'] = $this->language->get('text_create_list');

        $data['location_name'] = '';
        $data['zipcode'] = '';

        $data['text_refine'] = $this->language->get('text_refine');
        $data['text_change_locality_warning'] = $this->language->get('text_change_locality_warning');
        $data['text_change_location_name'] = $this->language->get('text_change_location_name');
        $data['text_only_on_change_locality_warning'] = $this->language->get('text_only_on_change_locality_warning');
        $data['text_empty'] = $this->language->get('text_empty');
        $data['text_quantity'] = $this->language->get('text_quantity');
        $data['text_manufacturer'] = $this->language->get('text_manufacturer');
        $data['text_model'] = $this->language->get('text_model');
        $data['text_price'] = $this->language->get('text_price');
        $data['text_tax'] = $this->language->get('text_tax');
        $data['text_points'] = $this->language->get('text_points');
        $data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
        $data['text_sort'] = $this->language->get('text_sort');
        $data['text_limit'] = $this->language->get('text_limit');
        $data['text_incart'] = $this->language->get('text_incart');
        $data['text_view'] = $this->language->get('text_view');
        $data['text_offer'] = $this->language->get('text_offer');
        $data['text_deliver'] = $this->language->get('text_deliver');
        $data['text_change_locality'] = $this->language->get('text_change_locality');

        $data['error_no_delivery'] = $this->language->get('error_no_delivery');

        $data['button_change'] = $this->language->get('button_change');
        $data['button_cart'] = $this->language->get('button_cart');

        $data['button_change_locality'] = $this->language->get('button_change_locality');
        $data['button_change_store'] = $this->language->get('button_change_store');

        $data['button_wishlist'] = $this->language->get('button_wishlist');
        $data['button_compare'] = $this->language->get('button_compare');
        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_list'] = $this->language->get('button_list');
        $data['button_grid'] = $this->language->get('button_grid');
        $data['button_add'] = $this->language->get('button_add');
        $data['button_clear_cart'] = $this->language->get('button_clear_cart');
        $data['button_checkout'] = $this->language->get('button_checkout');

        $data['toHome'] = $this->url->link('common/home/toHome');
        $data['toStore'] = $this->url->link('common/home/toStore');

        $data['lists'] = [];

        if (!$this->customer->isLogged()) {
            $data['checkout_link'] = $this->url->link('checkout/checkout');
        } else {
            //get listes
            $data['lists'] = $this->model_assets_category->getUserLists();
            $data['checkout_link'] = $this->url->link('checkout/checkout#collapseTwo');
        }

        //echo "<pre>";print_r($data['lists']);die;
        if (isset($store_info['logo'])) {
            $data['thumb'] = $this->model_tool_image->resize($store_info['logo'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
        } else {
            $data['thumb'] = '';
        }

        if (isset($store_info['banner_logo'])) {
            $data['banner_logo'] = $this->model_tool_image->resize($store_info['banner_logo'], 800, 450);
        } else {
            $data['banner_logo'] = $this->model_tool_image->resize('placeholder.png', 800, 450);
        }

        $data['description'] = '';
        $data['compare'] = $this->url->link('product/compare');

        $url = '';

        if (isset($this->request->get['filter'])) {
            $url .= '&filter=' . $this->request->get['filter'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['limit'])) {
            $url .= '&limit=' . $this->request->get['limit'];
        }

        $results = $this->model_assets_category->getCategories();

        //echo "<pre>";print_r($results);die;
        $filter_data = [
            'filter_category_id' => isset($this->request->get['filter_category']) && $this->request->get['filter_category'] > 0 ? $this->request->get['filter_category'] : NULL,
            'filter_sort' => isset($this->request->get['filter_sort']) || $this->request->get['filter_sort'] != NULL ? $this->request->get['filter_sort'] : NULL
        ];
        $data['categories_list'] = $results;

        $products = $this->getProductsForCategoryPages($filter_data);
        $data['products'] = $products;

        //echo "<pre>";print_r($products);die;
        $data['products_url'] = $this->url->link('common/home/allproducts');

        $url = '';

        if (isset($this->request->get['filter'])) {
            $url .= '&filter=' . $this->request->get['filter'];
        }

        if (isset($this->request->get['limit'])) {
            $url .= '&limit=' . $this->request->get['limit'];
        }

        $url = '';

        if (isset($this->request->get['filter'])) {
            $url .= '&filter=' . $this->request->get['filter'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $data['continue'] = $this->url->link('common/home');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $data['account_register'] = $this->load->controller('account/register');
        $data['login_modal'] = $this->load->controller('common/login_modal');
        $data['signup_modal'] = $this->load->controller('common/signup_modal');
        $data['forget_modal'] = $this->load->controller('common/forget_modal');

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['base'] = $server;

        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/products.tpl', $data));
    }

    public function getProductsForCategoryPages($filter_data) {
        $cachePrice_data = $this->cache->get('category_price_data');
        $this->load->model('assets/product');
        $this->load->model('tool/image');
        $this->load->model('user/user');

        //$results = $this->model_assets_product->getProducts($filter_data);
        //$results = $this->model_assets_product->getProductsForHomePage($filter_data);
        $results = $this->model_assets_product->getProductsForCategoryPage($filter_data);
//        echo '<pre>';print_r($results); die;

        $data['products'] = [];

        foreach ($results as $result) {
            $vendor_details = $this->model_user_user->getUser($result['merchant_id']);
            // if qty less then 1 dont show product
            //REMOVED QUANTITY CHECK CONDITION
            /* if ($result['quantity'] <= 0) {
              continue;
              } */

            $log = new Log('error.log');
            if ($result['image'] != NULL && file_exists(DIR_IMAGE . $result['image'])) {
                $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
            } else if ($result['image'] == NULL || !file_exists(DIR_IMAGE . $result['image'])) {
                $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
            }

            //if category discount define override special price
            $discount = '';

            $s_price = 0;
            $o_price = 0;

            if (!$this->config->get('config_inclusiv_tax')) {
                //FOR CATEGORY PRICING
                $category_s_price = 0;
                $category_o_price = 0;
                if (CATEGORY_PRICE_ENABLED == true && isset($cachePrice_data) && isset($cachePrice_data[$result['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $result['store_id']])) {
                    $category_s_price = $cachePrice_data[$result['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $result['store_id']];
                    $category_o_price = $cachePrice_data[$result['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $result['store_id']];
                    if ($category_s_price != NULL && $category_s_price > 0) {
                        $result['price'] = $category_s_price;
                        $result['special_price'] = $category_s_price;
                    }
                }
                //FOR CATEGORY PRICING
                //get price html
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));

                    $o_price = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
                } else {
                    $price = false;
                }
                if ((float) $result['special_price']) {
                    $special_price = $this->currency->format($this->tax->calculate($result['special_price'], $result['tax_class_id'], $this->config->get('config_tax')));

                    $s_price = $this->tax->calculate($result['special_price'], $result['tax_class_id'], $this->config->get('config_tax'));
                } else {
                    $special_price = false;
                }
            } else {
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($result['price']);
                } else {
                    $price = $result['price'];
                }

                if ((float) $result['special_price']) {
                    $special_price = $this->currency->format($result['special_price']);
                } else {
                    $special_price = $result['special_price'];
                }

                $s_price = $result['special_price'];
                $o_price = $result['price'];

                if (CATEGORY_PRICE_ENABLED == true && isset($cachePrice_data) && isset($cachePrice_data[$result['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $result['store_id']])) {
                    $s_price = $cachePrice_data[$result['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $result['store_id']];
                    $o_price = $cachePrice_data[$result['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $result['store_id']];
                    $special_price = $this->currency->format($s_price);
                    $price = $this->currency->format($o_price);
                }
            }

            //get qty in cart
            if (!empty($this->session->data['config_store_id'])) {
                $key = base64_encode(serialize(['product_store_id' => (int) $result['product_store_id'], 'store_id' => $this->session->data['config_store_id']]));
            } else {
                $key = base64_encode(serialize(['product_store_id' => (int) $result['product_store_id'], 'store_id' => $result['store_id']]));
            }
            if (isset($this->session->data['cart'][$key])) {
                $qty_in_cart = $this->session->data['cart'][$key]['quantity'];
            } else {
                $qty_in_cart = 0;
            }

            //$result['name'] = strlen($result['name']) > 27 ? substr($result['name'],0,27)."..." : $result['name'];
            $name = $result['name'];
            if (isset($result['pd_name'])) {
                $name = $result['pd_name'];
            }

            //$name .= str_repeat('&nbsp;',30 - strlen($result['name']));

            $percent_off = null;
            if (isset($s_price) && isset($o_price) && 0 != $o_price && 0 != $s_price) {
                $percent_off = (($o_price - $s_price) / $o_price) * 100;
            }

            // Avoid adding duplicates for similar products with different variations

            $productNames = array_column($data['products'], 'name');
            if (false !== array_search($result['name'], $productNames)) {
                // Add variation to existing product
                $productIndex = array_search($result['name'], $productNames);
                // TODO: Check for product variation duplicates
                $data['products'][$productIndex][variations][] = [
                    'variation_id' => $result['product_store_id'],
                    'unit' => $result['unit'],
                    'weight' => floatval($result['weight']),
                    'price' => $price,
                    'special' => $special_price,
                ];
            } else {
                // Add as new product
                $data['products'][] = [
                    'key' => $key,
                    'store_id' => $result['store_id'],
                    'qty_in_cart' => $qty_in_cart,
                    'variations' => $this->model_assets_product->getVariations($result['product_store_id']),
                    'store_product_variation_id' => 0,
                    'product_id' => $result['product_id'],
                    'product_store_id' => $result['product_store_id'],
                    'default_variation_name' => $result['default_variation_name'],
                    'thumb' => $image,
                    'name' => $name,
                    'variations' => [
                        [
                            'variation_id' => $result['product_store_id'],
                            'unit' => $result['unit'],
                            'weight' => floatval($result['weight']),
                            'price' => $price,
                            'special' => $special_price,
                        ],
                    ],
                    'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
                    'percent_off' => number_format($percent_off, 0),
                    'tax' => $result['tax_percentage'],
                    'minimum' => $result['min_quantity'] > 0 ? $result['min_quantity'] : $result['quantity'],
                    'rating' => 0,
                    'href' => $this->url->link('product/product', '&product_store_id=' . $result['product_store_id']),
                    'vendor_display_name' => $vendor_details['display_name'],
                    'sort_price' => $s_price
                ];
            }
        }

        //return $data['products'];
        $log = new Log('error.log');
        $log->write('filter_data');
        $log->write($filter_data);
        $log->write('filter_data');
        if (isset($filter_data['filter_sort']) && $filter_data['filter_sort'] != NULL && $filter_data['filter_sort'] == 'nasc') {
            $new_arry = $this->multisort($data['products'], 'name', 'nasc');
            $log = new Log('error.log');
            $log->write('products');
            $log->write($new_arry);
            $log->write('products');
            return $new_arry;
        } else if (isset($filter_data['filter_sort']) && $filter_data['filter_sort'] != NULL && $filter_data['filter_sort'] == 'ndesc') {
            $new_arry = $this->multisort($data['products'], 'name', 'ndesc');
            $log = new Log('error.log');
            $log->write('products');
            $log->write($new_arry);
            $log->write('products');
            return $new_arry;
        } else if (isset($filter_data['filter_sort']) && $filter_data['filter_sort'] != NULL && $filter_data['filter_sort'] == 'pasc') {
            $new_arry = $this->multisort($data['products'], 'sort_price', 'pasc');
            $log = new Log('error.log');
            $log->write('products');
            $log->write($new_arry);
            $log->write('products');
            return $new_arry;
        } else if (isset($filter_data['filter_sort']) && $filter_data['filter_sort'] != NULL && $filter_data['filter_sort'] == 'pdesc') {
            $new_arry = $this->multisort($data['products'], 'sort_price', 'pdesc');
            $log = new Log('error.log');
            $log->write('products');
            $log->write($new_arry);
            $log->write('products');
            return $new_arry;
        } else {
            return $data['products'];
        }
    }

}
