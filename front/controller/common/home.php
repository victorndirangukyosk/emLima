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
            $data['logo'] = $server . 'image/' . $this->config->get('config_logo');
        } else {
            $data['logo'] = 'assets/img/logo.svg';
        }
        
        $data['base'] = '';
        $data['store_name'] = $this->config->get('config_name');

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
        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/landing_page/terms-and-conditions.tpl', $data));
    }

    public function privacy_policy() {
        $data['customer_id'] = NULL;
        if ($this->customer->isLogged()) {
            $data['customer_id'] = $this->session->data['customer_id'];
        }
        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/landing_page/privacy-policy.tpl', $data));
    }

    public function index() {
        if (!isset($this->session->data['customer_id'])) {
            if (isset($_REQUEST['action']) && ('shop' == $_REQUEST['action'])) {
                $this->response->redirect($this->url->link('account/login/customer'));
            }
        }

        $this->load->model('account/customer');
        $is_he_parents = $this->model_account_customer->CheckHeIsParent();
    
        if ($is_he_parents != NULL) {
            $customer_details = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . $this->db->escape($is_he_parents) . "' AND status = '1'");
        } else {
            $customer_details = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . $this->customer->getId() . "' AND status = '1'");
        }

        $this->session->data['customer_category'] = isset($customer_details->row['customer_category']) ? $customer_details->row['customer_category'] : null;

        if (isset($this->session->data['language'])) {
            $data['config_language'] = $this->session->data['language'];
        } else {
            $data['config_language'] = 'pt-BR';
        }


        unset($this->session->data['visitor_id']);

        $data['justlogged_in'] = false;

        if (isset($this->session->data['just_loggedin']) && $this->session->data['just_loggedin']) {
            $data['justlogged_in'] = true;
            $this->session->data['just_loggedin'] = false;
        }

        if (isset($this->session->data['config_store_id'])) {
            $this->response->redirect($this->url->link('product/store', 'store_id=' . $this->session->data['config_store_id'] . ''));
        }

        $this->load->model('tool/image');
        $this->load->language('common/home');
        $this->load->model('account/wishlist');

        $wishlist_results = $this->model_account_wishlist->getWishlists();
        $data['wishlist_count'] = count($wishlist_results);
        
        $this->document->setTitle($this->config->get('config_meta_title'));
        $this->document->setDescription($this->config->get('config_meta_description'));
        $this->document->setKeywords($this->config->get('config_meta_keyword'));

        $data['description'] = $this->document->getDescription();
        $data['keywords'] = $this->document->getKeywords();
        $data['metas'] = $this->document->getMetas();

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

        $data['base'] = $server;
       
        if (isset($this->session->data['error'])) {
            $data['error_warning'] = $this->session->data['error'];
            unset($this->session->data['error']);
        } else {
            $data['error_warning'] = '';
        }

      
        $data['is_login'] = $this->customer->isLogged();
        $data['f_name'] = $this->customer->getFirstName();
        $data['name'] = $this->customer->getFirstName();
        $data['l_name'] = $this->customer->getLastName();
        $data['full_name'] = $data['f_name'];
        $data['home'] = $this->url->link('common/home');
        $data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
        $data['logged'] = $this->customer->isLogged();
        $data['account'] = $this->url->link('account/account', '', 'SSL');
        $data['dashboard'] = $this->url->link('account/dashboard', '', 'SSL');
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
        $data['action'] = $this->url->link('common/home/find_store');
        $data['address'] = $this->url->link('account/address', '', 'SSL');
        $data['help'] = $this->url->link('information/help');

        
        $data['heading_title'] = $this->config->get('config_meta_title', '');

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

       
        if (isset($this->session->data['warning'])) {
            $data['warning'] = $this->session->data['warning'];
            unset($this->session->data['warning']);
        } else {
            $data['warning'] = '';
        }

        if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
            $data['logo'] = $server . 'image/' . $this->config->get('config_logo');
        } else {
            $data['logo'] = 'assets/img/logo.svg';
        }

        
        $data['login'] = $this->url->link('account/login', '', 'SSL');
        $data['register'] = $this->url->link('account/register', '', 'SSL');
        $data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');


        $this->load->model('assets/category');
        $data['categories'] = [];
        $categories = $this->model_assets_category->getCategoryByStoreId(ACTIVE_STORE_ID, 0);

        foreach ($categories as $category) {
            $filter_data_product = [
                'filter_category_id' => $category['category_id'],
                'filter_sub_category' => true,
                'start' => 0,
                'limit' => (1359 == $category['category_id']) ? 12 : 12,
                'store_id' => ACTIVE_STORE_ID,
            ];

            // Level 1
            $productslisted = $this->getProducts($filter_data_product);
            $data['categories'][] = [
                'name' => $category['name'],
                'id' => $category['category_id'],
                'thumb' => $this->model_tool_image->resize($category['image'], 300, 300),
                'column' => $category['column'] ? $category['column'] : 1,
                'href' => $this->url->link('product/category', 'category=' . $category['category_id']),
                'products' => $productslisted,
            ];
        }

        // echo "<pre>";print_r($data['categories']);die;

        $data['page'] = isset($_REQUEST['page']) ? $_REQUEST['page'] : '';
       
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
      

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/home.tpl') && isset($this->session->data['customer_id'])) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/homenew.tpl', $data));
        } else {
            $this->load->model('sale/order');
            $numberOfOrders = count($this->model_sale_order->getOrders());
            $data['order_count'] = $numberOfOrders;

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
            $data['full_name'] = $data['f_name'];
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

            if ($this->cart->getTotalProductsByStore($os) && $store_info['min_order_amount'] > $store_total) {
                $log->write('3');
                $currentprice = $store_info['min_order_amount'] - $store_total;
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

            if ($this->cart->getTotalProductsByStore($os) && $store_info['min_order_amount'] > $store_total) {
                $log->write('3');
                $currentprice = $store_info['min_order_amount'] - $store_total;
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

        $cachePrice_data = $this->cache->get('category_price_data');
        $results = $this->model_assets_product->getProductsForHomePage($filter_data);

        $data['products'] = [];

        foreach ($results as $result) {
            if ($result['image'] != NULL && file_exists(DIR_IMAGE . $result['image'])) {
                $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
            } else if ($result['image'] == NULL || !file_exists(DIR_IMAGE . $result['image'])) {
                $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
            }

            $s_price = 0;
            $o_price = 0;

            if (!$this->config->get('config_inclusiv_tax')) {
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

                if (CATEGORY_PRICE_ENABLED == true && isset($cachePrice_data) && isset($cachePrice_data[$result['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $filter_data['store_id']])) {
                    $s_price = $cachePrice_data[$result['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $filter_data['store_id']];
                    $o_price = $cachePrice_data[$result['product_store_id'] . '_' . $_SESSION['customer_category'] . '_' . $filter_data['store_id']];
                    $special_price = $this->currency->format($s_price);
                    $price = $this->currency->format($o_price);
                }
            }

            $name = $result['name'];
            if (isset($result['pd_name'])) {
                $name = $result['pd_name'];
            }

            $percent_off = null;
            if (isset($s_price) && isset($o_price) && 0 != $o_price && 0 != $s_price) {
                $percent_off = (($o_price - $s_price) / $o_price) * 100;
            }

            if (!empty($this->session->data['config_store_id'])) {
                $storeId = $this->session->data['config_store_id'];
            } else {
                $storeId = $filter_data['store_id'];
            }

            // Avoid adding duplicates for similar products with different variations
            $productNames = array_column($data['products'], 'name');
            if (true !== array_search($result['name'], $productNames)) {
                $data['products'][] = [
                    'variations' => $this->model_assets_product->getProductVariationsNew($name, $storeId),
                    'store_product_variation_id' => 0,
                    'product_id' => $result['product_id'],
                    'product_store_id' => $result['product_store_id'],
                    'default_variation_name' => $result['default_variation_name'],
                    'thumb' => $image,
                    'name' => $name,
                    'store_id' => $result['store_id'],
                    'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
                    'percent_off' => number_format($percent_off, 0),
                    'tax' => $result['tax_percentage'],
                    'minimum' => $result['min_quantity'] > 0 ? $result['min_quantity'] : $result['quantity'],
                    'rating' => 0,
                    'href' => $this->url->link('product/product', '&product_store_id=' . $result['product_store_id']),
                ];
            } 

            // echo "<pre>";print_r($this->session->data['cart']);die;

            // Get qty in cart
            foreach($data['products'] as &$product) {
                $quantityInCart = 0;
                foreach($product['variations'] as $variation) {
                    $tempProduct['product_id'] = (int) $product['product_id'];
                    $tempProduct['variation_id'] = (int) $variation['product_store_id'];
                    $tempProduct['store_id'] = (int) $variation['store_id'];
                    $key = base64_encode(serialize($tempProduct));

                    if (isset($this->session->data['cart'][$key])) {
                        $quantityInCart = $this->session->data['cart'][$key]['quantity'];
                    }
                }
                $product['qty_in_cart'] = $quantityInCart;
            }
        }
        // echo "<pre>";print_r($data['products']);die;

        return $data['products'];
    }

}
