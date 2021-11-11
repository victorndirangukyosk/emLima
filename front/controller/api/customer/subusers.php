<?php

require_once DIR_SYSTEM . '/vendor/konduto/vendor/autoload.php';

use Konduto\Core\Konduto;
use paragraph1\phpFCM\Message;

require_once DIR_SYSTEM . '/vendor/fcp-php/autoload.php';

require DIR_SYSTEM . 'vendor/Facebook/autoload.php';

require_once DIR_APPLICATION . '/controller/api/settings.php';

class ControllerApiCustomerSubusers extends Controller {

    private $error = [];

    public function index($args = []) {
        $json = [];

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        if (!empty($this->session->data['parent'])) {
            // $this->response->redirect($this->url->link('account/account'));
            $json['status'] = 10014;
        }
        // $data['kondutoStatus'] = $this->config->get('config_konduto_status');
        // $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');
        // $data['redirect_coming'] = false;
        //$this->document->addStyle('/front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_login.css');

        if (!$this->customer->isLogged()) {
            // $this->session->data['redirect'] = $this->url->link('account/profileinfo', '', 'SSL');
            // $this->response->redirect($this->url->link('account/login', '', 'SSL'));
            $json['status'] = 10014;
        }

        $this->load->language('account/edit');
        $this->load->language('account/account');

        $this->document->setTitle('Add User');
        $this->load->model('account/customer');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validate()) {
            $this->model_account_customer->addEditCustomerInfo($this->customer->getId(), $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');

            // Add to activity log
            $this->load->model('account/activity');

            $activity_data = [
                'customer_id' => $this->customer->getId(),
                'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
            ];
            $log = new Log('error.log');
            $log->write('account profileinfo');

            $this->model_account_activity->addActivity('profileinfo', $activity_data);

            $log->write('account profileinfo');

            // $this->response->redirect($this->url->link('account/profileinfo', '', 'SSL'));
        }

        // $data['breadcrumbs'] = array();
        // $data['breadcrumbs'][] = array(
        //     'text' => $this->language->get('text_home'),
        //     'href' => $this->url->link('common/home')
        // );
        // $data['breadcrumbs'][] = array(
        //     'text' => $this->language->get('text_account'),
        //     'href' => $this->url->link('account/account', '', 'SSL')
        // );

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_your_details'] = $this->language->get('text_your_details');
        $data['text_additional'] = $this->language->get('text_additional');
        $data['text_select'] = $this->language->get('text_select');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['text_male'] = $this->language->get('text_male');
        $data['text_female'] = $this->language->get('text_female');
        $data['text_other'] = $this->language->get('text_other');
        $data['entry_dob'] = $this->language->get('entry_dob');

        $data['entry_password'] = $this->language->get('entry_password');
        $data['entry_confirmpassword'] = $this->language->get('entry_confirmpassword');

        $data['entry_location'] = $this->language->get('entry_location');
        $data['entry_requirement'] = $this->language->get('entry_requirement');
        $data['entry_mandatory_products'] = $this->language->get('entry_mandatory_products');

        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_back'] = $this->language->get('button_back');
        $data['button_upload'] = $this->language->get('button_upload');
        $data['button_save'] = $this->language->get('button_save');

        $data['text_my_account'] = $this->language->get('text_my_account');
        $data['text_my_orders'] = $this->language->get('text_my_orders');
        $data['text_my_newsletter'] = $this->language->get('text_my_newsletter');
        $data['text_my_logout'] = $this->language->get('text_my_logout');
        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_password'] = $this->language->get('text_password');
        $data['text_address'] = $this->language->get('text_address');
        $data['text_wishlist'] = $this->language->get('text_wishlist');
        $data['text_order'] = $this->language->get('text_order');
        $data['text_download'] = $this->language->get('text_download');
        $data['text_reward'] = $this->language->get('text_reward');
        $data['text_return'] = $this->language->get('text_return');
        $data['text_credit'] = $this->language->get('text_credit');
        $data['text_newsletter'] = $this->language->get('text_newsletter');
        $data['text_logout'] = $this->language->get('text_logout');
        $data['text_signout'] = $this->language->get('text_signout');
        $data['text_recurring'] = $this->language->get('text_recurring');
        $data['text_membership'] = $this->language->get('text_membership');
        $data['text_change_password'] = $this->language->get('text_change_password');

        $data['button_become_member'] = $this->language->get('button_become_member');

        $data['label_name'] = $this->language->get('label_name');
        $data['label_contact_no'] = $this->language->get('label_contact_no');
        $data['label_address'] = $this->language->get('label_address');

        $data['edit'] = $this->url->link('account/edit', '', 'SSL');
        $data['password'] = $this->url->link('account/password', '', 'SSL');
        $data['address'] = $this->url->link('account/address', '', 'SSL');
        $data['wishlist'] = $this->url->link('account/wishlist');
        $data['order'] = $this->url->link('account/order', '', 'SSL');
        $data['download'] = $this->url->link('account/download', '', 'SSL');
        $data['return'] = $this->url->link('account/return', '', 'SSL');
        $data['credit'] = $this->url->link('account/credit', '', 'SSL');
        $data['pezesha'] = $this->url->link('account/pezesha', '', 'SSL');
        $data['newsletter'] = $this->url->link('account/newsletter', '', 'SSL');
        $data['logout'] = $this->url->link('account/logout', '', 'SSL');
        $data['recurring'] = $this->url->link('account/recurring', '', 'SSL');

        if ('POST' != $this->request->server['REQUEST_METHOD']) {
            $customer_info = $this->model_account_customer->getCustomerOtherInfo($this->customer->getId());
            // echo '<pre>';print_r($customer_info);exit;
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['location'])) {
            $data['error_location'] = $this->error['location'];
        } else {
            $data['error_location'] = '';
        }

        if (isset($this->error['requirement'])) {
            $data['error_requirement'] = $this->error['requirement'];
        } else {
            $data['error_requirement'] = '';
        }

        if (isset($this->error['mandatory_products'])) {
            $data['error_mandatory_products'] = $this->error['mandatory_products'];
        } else {
            $data['error_mandatory_products'] = '';
        }

        if (isset($this->request->post['location'])) {
            $data['location'] = $this->request->post['location'];
        } else {
            $data['location'] = $customer_info['location'];
        }

        if (isset($this->request->post['requirement'])) {
            $data['requirement'] = $this->request->post['requirement'];
        } else {
            $data['requirement'] = $customer_info['requirement_per_week'];
        }
        if (isset($this->request->post['mandatory_products'])) {
            $data['mandatory_products'] = $this->request->post['mandatory_products'];
        } else {
            $data['mandatory_products'] = $customer_info['mandatory_veg_fruits'];
        }

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['base'] = $server;

        $data['action'] = $this->url->link('account/profileinfo', '', 'SSL');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');

        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/information');

        $data['account_edit'] = $this->load->controller('account/edit');
        $data['pay'] = $this->url->link('account/transactions', 'pay=online');
        $data['home'] = $this->url->link('common/home/toHome');
        //$data['telephone'] =  $this->formatTelephone($this->customer->getTelephone());
        /* Added new params */
        $data['is_login'] = $this->customer->isLogged();
        $data['full_name'] = $this->customer->getFirstName();
        $data['text_my_cash'] = $this->language->get('text_my_cash');
        $data['text_my_wishlist'] = $this->language->get('text_my_wishlist');
        $data['label_my_address'] = $this->language->get('label_my_address');
        $data['contactus'] = $this->language->get('contactus');
        $data['text_cash'] = $this->language->get('text_cash');

        $data['orders'] = [];
        $filter_data = [
            'filter_parent' => $_SESSION['customer_id'],
            'order' => 'DESC',
            'start' => 0,
            'limit' => 1000,
        ];
        $this->load->model('sale/order');
        $customer_total = $this->model_sale_order->getTotalCustomers($filter_data);
        $result_customers = $this->model_sale_order->getCustomers($filter_data);

        $data['heading_title'] = $this->language->get('heading_title');

        //echo "<pre>";print_r($data['title']);die;

        $data['text_your_details'] = $this->language->get('text_your_details');
        $data['text_additional'] = $this->language->get('text_additional');
        $data['text_select'] = $this->language->get('text_select');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['text_male'] = $this->language->get('text_male');
        $data['text_female'] = $this->language->get('text_female');
        $data['text_other'] = $this->language->get('text_other');
        $data['entry_dob'] = $this->language->get('entry_dob');

        $data['entry_password'] = $this->language->get('entry_password');
        $data['entry_confirmpassword'] = $this->language->get('entry_confirmpassword');

        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_phone'] = $this->language->get('entry_phone');
        $data['entry_fax'] = $this->language->get('entry_fax');
        $data['entry_companyname'] = $this->language->get('entry_companyname');
        $data['entry_companyaddress'] = $this->language->get('entry_companyaddress');

        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_back'] = $this->language->get('button_back');
        $data['button_upload'] = $this->language->get('button_upload');
        $data['button_save'] = $this->language->get('button_save');

        $data['entry_gender'] = $this->language->get('entry_gender');

        $data['text_my_account'] = $this->language->get('text_my_account');
        $data['text_my_orders'] = $this->language->get('text_my_orders');
        $data['text_my_newsletter'] = $this->language->get('text_my_newsletter');
        $data['text_my_logout'] = $this->language->get('text_my_logout');
        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_password'] = $this->language->get('text_password');
        $data['text_address'] = $this->language->get('text_address');
        $data['text_wishlist'] = $this->language->get('text_wishlist');
        $data['text_order'] = $this->language->get('text_order');
        $data['text_download'] = $this->language->get('text_download');
        $data['text_reward'] = $this->language->get('text_reward');
        $data['text_return'] = $this->language->get('text_return');
        $data['text_credit'] = $this->language->get('text_credit');
        $data['text_newsletter'] = $this->language->get('text_newsletter');
        $data['text_logout'] = $this->language->get('text_logout');
        $data['text_signout'] = $this->language->get('text_signout');
        $data['text_recurring'] = $this->language->get('text_recurring');
        $data['text_membership'] = $this->language->get('text_membership');
        $data['text_change_password'] = $this->language->get('text_change_password');

        $data['button_become_member'] = $this->language->get('button_become_member');

        $data['label_name'] = $this->language->get('label_name');
        $data['label_contact_no'] = $this->language->get('label_contact_no');
        $data['label_address'] = $this->language->get('label_address');

        $data['edit'] = $this->url->link('account/edit', '', 'SSL');
        $data['password'] = $this->url->link('account/password', '', 'SSL');
        $data['address'] = $this->url->link('account/address', '', 'SSL');
        $data['wishlist'] = $this->url->link('account/wishlist');
        $data['order'] = $this->url->link('account/order', '', 'SSL');
        $data['download'] = $this->url->link('account/download', '', 'SSL');
        $data['return'] = $this->url->link('account/return', '', 'SSL');
        $data['credit'] = $this->url->link('account/credit', '', 'SSL');
        $data['pezesha'] = $this->url->link('account/pezesha', '', 'SSL');
        $data['newsletter'] = $this->url->link('account/newsletter', '', 'SSL');
        $data['logout'] = $this->url->link('account/logout', '', 'SSL');
        $data['recurring'] = $this->url->link('account/recurring', '', 'SSL');

        if ('POST' != $this->request->server['REQUEST_METHOD']) {
            $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['firstname'])) {
            $data['error_firstname'] = $this->error['firstname'];
        } else {
            $data['error_firstname'] = '';
        }

        if (isset($this->error['companyname'])) {
            $data['error_companyname'] = $this->error['companyname'];
        } else {
            $data['error_companyname'] = '';
        }

        if (isset($this->error['companyaddress'])) {
            $data['error_companyaddress'] = $this->error['companyaddress'];
        } else {
            $data['error_companyaddress'] = '';
        }

        if (isset($this->error['lastname'])) {
            $data['error_lastname'] = $this->error['lastname'];
        } else {
            $data['error_lastname'] = '';
        }

        if (isset($this->error['password'])) {
            $data['error_password'] = $this->error['password'];
        } else {
            $data['error_password'] = '';
        }

        if (isset($this->error['confirmpassword'])) {
            $data['error_confirmpassword'] = $this->error['confirmpassword'];
        } else {
            $data['error_confirmpassword'] = '';
        }

        if (isset($this->error['email'])) {
            $data['error_email'] = $this->error['email'];
        } else {
            $data['error_email'] = '';
        }

        if (isset($this->error['telephone'])) {
            $data['error_telephone'] = $this->error['telephone'];
        } else {
            $data['error_telephone'] = '';
        }

        if (isset($this->error['error_tax'])) {
            $data['error_tax'] = $this->error['error_tax'];
        } else {
            $data['error_tax'] = '';
        }

        if (isset($this->error['dob'])) {
            $data['error_dob'] = $this->error['dob'];
        } else {
            $data['error_dob'] = '';
        }

        if (isset($this->error['custom_field'])) {
            $data['error_custom_field'] = $this->error['custom_field'];
        } else {
            $data['error_custom_field'] = [];
        }

        if ($this->config->get('reward_status')) {
            $data['reward'] = $this->url->link('account/reward', '', 'SSL');
        } else {
            $data['reward'] = '';
        }

        if (isset($this->request->post['gender'])) {
            $data['gender'] = $this->request->post['gender'];
        } elseif (!empty($customer_info)) {
            if (empty($customer_info['gender'])) {
                $data['gender'] = 'male';
            } else {
                $data['gender'] = $customer_info['gender'];
            }
        } else {
            $data['gender'] = 'male';
        }

        if (isset($this->request->post['dob']) && '' != trim($this->request->post['dob'])) {
            $data['dob'] = date('d/m/Y', strtotime($this->request->post['dob']));
        } elseif (!empty($customer_info['dob'])) {
            $data['dob'] = date('d/m/Y', strtotime($customer_info['dob']));
        } else {
            $data['dob'] = '01/01/1990';
        }

        if (isset($this->request->post['firstname'])) {
            $data['firstname'] = $this->request->post['firstname'];
        } elseif (!empty($customer_info)) {
            $data['firstname'] = $customer_info['firstname'];
        } else {
            $data['firstname'] = '';
        }

        if (isset($this->request->post['companyname'])) {
            $data['companyname'] = $this->request->post['companyname'];
        } elseif (!empty($customer_info)) {
            $data['companyname'] = $customer_info['company_name'];
        } else {
            $data['companyname'] = '';
        }

        if (isset($this->request->post['companyaddress'])) {
            $data['companyaddress'] = $this->request->post['companyaddress'];
        } elseif (!empty($customer_info)) {
            $data['companyaddress'] = $customer_info['company_address'];
        } else {
            $data['companyaddress'] = '';
        }

        if (isset($this->request->post['fax'])) {
            $data['fax'] = $this->request->post['fax'];
        } elseif (!empty($customer_info)) {
            $data['fax'] = $customer_info['fax'];
        } else {
            $data['fax'] = '';
        }

        if (isset($this->request->post['lastname'])) {
            $data['lastname'] = $this->request->post['lastname'];
        } elseif (!empty($customer_info)) {
            $data['lastname'] = $customer_info['lastname'];
        } else {
            $data['lastname'] = '';
        }

        if (isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } elseif (!empty($customer_info)) {
            $data['email'] = $customer_info['email'];
        } else {
            $data['email'] = '';
        }

        if (isset($this->error['captcha'])) {
            $data['error_captcha'] = $this->error['captcha'];
        } else {
            $data['error_captcha'] = '';
        }

        if (isset($this->request->post['captcha'])) {
            $data['captcha'] = $this->request->post['captcha'];
        } else {
            $data['captcha'] = '';
        }

        if ($this->config->get('config_google_captcha_status')) {
            $this->document->addScript('https://www.google.com/recaptcha/api.js');

            $data['site_key'] = $this->config->get('config_google_captcha_public');
        } else {
            $data['site_key'] = '';
        }

        //for membership
        // $member_group_id = $this->config->get('config_member_group_id');
        // $customer_group_id = $this->customer->getGroupId();
        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['telephone_mask'] = $this->config->get('config_telephone_mask');

        if (isset($data['telephone_mask'])) {
            $data['telephone_mask_number'] = str_replace('#', '9', $this->config->get('config_telephone_mask'));
        }

        $data['taxnumber_mask'] = $this->config->get('config_taxnumber_mask');

        if (isset($data['taxnumber_mask'])) {
            $data['taxnumber_mask_number'] = str_replace('#', '*', $this->config->get('config_taxnumber_mask'));
        }

        $data['base'] = $server;

        $data['action'] = $this->url->link('account/account/adduser', '', 'SSL');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');

        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/information');

        $data['account_edit'] = $this->load->controller('account/edit');

        $data['home'] = $this->url->link('common/home/toHome');
        $data['telephone'] = $this->customer->getTelephone();
        /* Added new params */
        $data['is_login'] = $this->customer->isLogged();
        $data['full_name'] = $this->customer->getFirstName();
        $data['text_my_cash'] = $this->language->get('text_my_cash');
        $data['text_my_wishlist'] = $this->language->get('text_my_wishlist');
        $data['label_my_address'] = $this->language->get('label_my_address');
        $data['contactus'] = $this->language->get('contactus');
        $data['text_cash'] = $this->language->get('text_cash');
        //echo '<pre>'; print_r($result_customers);exit;
        /* $this->load->model('account/order');
          $order_total = $this->model_account_order->getTotalOrders();

          $results_orders = $this->model_account_order->getOrders(($page - 1) * 10, 10,$NoLimit=true);
          $PaymentFilter = array('mPesa On Delivery','Cash On Delivery','mPesa Online');
          $statusCancelledFilter  = array('Cancelled');
          $statusSucessFilter  =  array('Delivered','Partially Delivered');
          $statusPendingFilter  = array('Cancelled','Delivered','Refunded','Returned','Partially Delivered');
          //$results_pending = $this->model_account_order->getOrders(($page - 1) * 10, 10,$PaymentFilter,$statusPendingFilter,$In=false);
          //$results_success = $this->model_account_order->getOrders(($page - 1) * 10, 10,$PaymentFilter,$statusPendingFilter,$In=true);
          //$results_cancelled = $this->model_account_order->getOrders(($page - 1) * 10, 10,$PaymentFilter,$statusCancelledFilter,$In=true);
          $data['pending_transactions'] = array();
          $data['success_transactions'] = array();
          $data['cancelled_transactions'] = array();
          //echo "<pre>";print_r($results_orders);die;
          $totalPendingAmount = 0;
          if(count($results_orders)>0){
          foreach($results_orders as $order){
          $this->load->model('sale/order');
          $order['transcation_id'] = $this->model_sale_order->getOrderTransactionId($order['order_id']);
          //echo "<pre>";print_r($order);die;
          if(in_array($order['payment_method'],$PaymentFilter)){
          if(!empty($order['transcation_id'])){
          //if(in_array($order['status'],$statusSucessFilter) && !empty($order['transcation_id'])){
          $data['success_transactions'][] = $order;
          }else if(in_array($order['status'],$statusCancelledFilter)){
          $data['cancelled_transactions'][] = $order;
          }else  if(!in_array($order['status'],$statusCancelledFilter)){
          $totalPendingAmount = $totalPendingAmount + $order['total'];
          $data['pending_order_id'][] = $order['order_id'];
          $data['pending_transactions'][] = $order;
          }

          }
          }
          }
          //echo "<pre>";print_r($data);die;
          $data['total_pending_amount'] = $totalPendingAmount;
          $data['pending_order_id'] = implode('--',$data['pending_order_id']); */
        $data['sub_users'] = $result_customers;
        $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
        $data['sub_customer_order_approval'] = $customer_info['sub_customer_order_approval'];

        $json['success'] = $this->language->get('text_success');

        $json['status'] = true;

        $json['data'] = $data;

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validate($args) {
        $this->load->language('account/edit');

        if ((utf8_strlen(trim($args['location'])) < 1) || (utf8_strlen(trim($args['location'])) > 32)) {
            $this->error['location'] = $this->language->get('error_location');
        }

        if ((utf8_strlen(trim($args['requirement'])) < 1)) {
            $this->error['requirement'] = $this->language->get('error_requirement');
        }

        if ((utf8_strlen(trim($args['mandatory_products'])) < 1)) {
            $this->error['mandatory_products'] = $this->language->get('error_mandatory_products');
        }

        return !$this->error;
    }

    public function addActivateSubUser($args = []) {
        $log = new Log('error.log');
        $log->write($args);
        $user_id = $args['user_id'];
        $log->write($user_id . 'USER ID');
        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->model('account/customer');
        $this->model_account_customer->approvecustom($user_id, $args['active_status']);
        if ($args['active_status'] == "0") {
            $json['message'][] = ['type' => '', 'body' => 'User de-activated!'];

            $json['success'] = 'User de-activated!';
        } else if ($args['active_status'] == "1") {
            $json['message'][] = ['type' => '', 'body' => 'User activated!'];

            $json['success'] = 'User activated!';
        }
        if (isset($this->request->post['logged_customer_id'])) {// $this->request->post['parent_customer_id'];// Add to activity log
            $this->load->model('account/activity');

            $activity_data = [
                'customer_id' => $this->request->post['logged_customer_id'],
                'name' => $this->request->post['logged_customer_firstname'] . ' ' . $this->request->post['logged_customer_lastname'],
                'sub_customers_id' => $this->request->post['user_id']
            ];

            if ($this->request->post['active_status'] == 1) {
                $this->model_account_activity->addActivity('sub_user_activated', $activity_data);
            }

            if ($this->request->post['active_status'] == 0) {
                $this->model_account_activity->addActivity('sub_user_deactivated', $activity_data);
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    //mobile API,Skip once new developer
    public function addSubuser($args = []) {
        $json = [];

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        // $this->load->language('api/general');
        // $this->load->model('api/approval');

        $log = new Log('error.log');

        $this->load->model('account/customer');
        //echo "<pre>";print_r($args);die;
        // if (!$this->validate()) {
        //     $json['status'] = 10014;
        //     foreach ($this->error as $key => $value) {
        //         $json['message'][] = ['type' => '', 'body' => $value];
        //     }
        //     http_response_code(400);
        // } else 
        //{
        $this->request->post['dob'] = null;
        $this->request->post['source'] = 'Mobile';
        $this->request->post['parent'] = $this->request->post['parent_customer_id']; //$this->customer->getId();           
        $parentcustomer_info = $this->model_account_customer->getCustomer($this->request->post['parent']);
        if ($parentcustomer_info != null) {
            $this->request->post['customer_group_id'] = $parentcustomer_info['customer_group_id'];
        }
        $sub_customer_id = $this->model_account_customer->addCustomer($this->request->post, true);
        // Clear any previous login attempts for unregistered accounts.
        $this->model_account_customer->deleteLoginAttempts($this->request->post['email']);
        //$logged_in = $this->customer->login($this->request->post['email'], $this->request->post['password']);
        //  $json['message'] ='User added successfully!'
        // Add to activity log
        $this->load->model('account/activity');

        $activity_data = [
            'customer_id' => $this->request->post['logged_customer_id'],
            'name' => $this->request->post['logged_customer_firstname'] . ' ' . $this->request->post['logged_customer_lastname'],
            'sub_customers_id' => $sub_customer_id,
        ];

        // $this->model_account_activity->addActivity('register', $activity_data);
        $this->model_account_activity->addActivity('sub_customer_created', $activity_data);

        /* If not able to login */
        $data['status'] = true;

        // if (!$logged_in) {
        //     $data['status'] = false;
        // }
        // $data['text_new_signup_reward'] = $this->language->get('text_new_signup_reward');
        // $data['text_new_signup_credit'] = $this->language->get('text_new_signup_credit');
        //$data['message'] = $this->language->get( 'verify_mail_sent' );

        $json['message'][] = ['type' => $this->language->get('text_success_registered'), 'body' => $this->language->get('verify_mail_sent')];

        // }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function editEmailUnique($args = []) {
        $log = new Log('error.log');
        $log->write($args['email']);
        $json = [];
        $json['status'] = 300;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->model('account/customer');
        $count = $this->model_account_customer->getTotalCustomersByEmail($args['email']);
        $log->write($count . 'Email Count');
        if (0 == $count || null == $count) {
            $json['message'][] = ['type' => '', 'body' => 'TRUE'];
        } else {
            $json['message'][] = ['type' => '', 'body' => 'FALSE'];
        }

        //  $json['success'] = $count == 0 || $count == NULL ? TRUE : FALSE;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addDeleteSubUser($args = []) {
        $log = new Log('error.log');
        $log->write($args['user_id']);
        $user_id = $args['user_id'];
        $log->write($user_id . 'USER ID');
        $json = [];
        $json['status'] = 300;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->model('account/customer');
        $this->model_account_customer->deletecustom($user_id);

        $json['success'] = 'User deleted!';

        if (isset($this->request->post['logged_customer_id'])) {// $this->request->post['parent_customer_id'];// Add to activity log
            $this->load->model('account/activity');

            $activity_data = [
                'customer_id' => $this->request->post['logged_customer_id'],
                'name' => $this->request->post['logged_customer_firstname'] . ' ' . $this->request->post['logged_customer_lastname'],
                'sub_customers_id' => $this->request->post['user_id']
            ];

            $this->model_account_activity->addActivity('sub_user_deleted', $activity_data);
        }


        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    //use this only for Admin/authorizatioin not available , used in mobile
    public function getAllSubUsers($args = []) {
        $json = [];

        $log = new Log('error.log');
        $log->write('getAllSubUsers');
        $parentuser_id = $args['parent_user_id'];
        $log->write($this->request->get);

        // $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $filter_data = [
            'filter_parent' => $parentuser_id,
            'order' => 'DESC',
            'start' => 0,
            'limit' => 1000,
        ];
        $this->load->model('sale/order');
        $customer_total = $this->model_sale_order->getTotalCustomers($filter_data);
        $result_customers = $this->model_sale_order->getCustomers($filter_data);

        //if( $this->customer->isLogged() )         
        // foreach ($results as $result) {
        //     $data['delivery_addresses'][] = [
        //     'address_id' => $result['address_id'],
        //     'name' => $result['name'],
        //       ];           

        $json['data'] = $result_customers; // $data;
        // }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    //Same method copied from web
    public function addAssignorderapproval() {

        $json = [];
        $log = new Log('error.log');
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = 'Updated';

        $log->write($this->request->post['button']);
        $log->write($this->request->post['head_chef']);
        $log->write($this->request->post['procurement_person']);
        $this->load->model('account/customer');
        if ($this->request->post['button'] == 'assign_head_chef') {
            // $this->model_account_customer->UpdateOrderApprovalAccess($this->customer->getId(), $this->request->post['head_chef'], 1, 'head_chef');
            $this->model_account_customer->UpdateOrderApprovalAccess($this->request->post['customer_id'], $this->request->post['head_chef'], 1, 'head_chef');

            $customer_info = $this->model_account_customer->getCustomer($this->request->post['customer_id']);
            // echo '<pre>';print_r($customer_info);exit;
            $this->load->model('account/activity');
            $activity_data = [
                'customer_id' => $customer_info['customer_id'],
                'name' => $customer_info['firstname'] . ' ' . $customer_info['lastname'],
                'sub_customers_id' => $this->request->post['head_chef']
            ];

            $this->model_account_activity->addActivity('assign_head_chef', $activity_data);
        }

        if ($this->request->post['button'] == 'assign_procurement_person') {
            $this->model_account_customer->UpdateOrderApprovalAccess($this->request->post['customer_id'], $this->request->post['procurement_person'], 1, 'procurement_person');
            $customer_info = $this->model_account_customer->getCustomer($this->request->post['customer_id']);

            $this->load->model('account/activity');
            $activity_data = [
                'customer_id' => $customer_info['customer_id'],
                'name' => $customer_info['firstname'] . ' ' . $customer_info['lastname'],
                'sub_customers_id' => $this->request->post['procurement_person']
            ];

            $this->model_account_activity->addActivity('assign_procurement_person', $activity_data);
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    //not using
    public function addassignsubcustomerorderapproval() {
        $log = new Log('error.log');
        $json['success'] = true;
        $this->load->model('account/customer');

        $customer_info = $this->model_account_customer->getCustomer($this->request->post['customer_id']);

        if (isset($customer_info) && $customer_info != NULL) {
            $this->model_account_customer->UpdateCustomerOrderApproval($this->request->post['customer_id'], $this->request->post['sub_customer_order_approval']);
        }

        $log->write($this->request->post['sub_customer_order_approval']);
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    //Approval required or not required.
    public function addassignsubcustomerorderapprovalbysubcustomerid($args = []) {
        $log = new Log('error.log');
        $log->write($args);
        $customer_id = $args['customer_id'];
        $log->write($customer_id . 'Login customer_id');
        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->model('account/customer');

        $customer_info = $this->model_account_customer->getCustomer($customer_id);
        $sub_customer_info = $this->model_account_customer->getCustomer($this->request->post['sub_customer_id']);

        if (isset($customer_info) && $customer_info != NULL && isset($sub_customer_info) && $sub_customer_info != NULL) {
            $this->model_account_customer->UpdateCustomerOrderApprovalBySubCustomerId($customer_id, $this->request->post['sub_customer_id'], $this->request->post['status']);
            $json['message'][] = ['type' => '', 'body' => 'success'];
            $json['success'] = 'success';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    //add assign order approval need change
    public function getSubUsers($args = []) {
        $json = [];

        $log = new Log('error.log');
        $log->write('getSubUsers');
        // $parentuser_id = $args['parent_user_id'];
        $parentuser_id = $this->customer->getId();
        $log->write($parentuser_id);

        // $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $filter_data = [
            'filter_parent' => $parentuser_id,
            'order' => 'DESC',
            'start' => 0,
            'limit' => 1000,
        ];
        $this->load->model('sale/order');
        $customer_total = $this->model_sale_order->getTotalCustomers($filter_data);
        $result_customers = $this->model_sale_order->getCustomers($filter_data);

        //if( $this->customer->isLogged() )         
        // foreach ($results as $result) {
        //     $data['delivery_addresses'][] = [
        //     'address_id' => $result['address_id'],
        //     'name' => $result['name'],
        //       ];           

        $json['data'] = $result_customers; // $data;
        // }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addNewSubUser($args = []) {
        $json = [];

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];
        try {

            $this->load->language('api/general');
            // $this->load->model('api/approval');

            $log = new Log('error.log');

            $this->load->model('account/customer');
            // echo "<pre>";print_r($this->customer->getFirstName());die;
            // if (!$this->validate()) {
            //     $json['status'] = 10014;
            //     foreach ($this->error as $key => $value) {
            //         $json['message'][] = ['type' => '', 'body' => $value];
            //     }
            //     http_response_code(400);
            // } else 
            //{
            $this->request->post['dob'] = null;
            $this->request->post['source'] = 'Mobile';
            $this->request->post['parent'] = $this->customer->getId();
            $parentcustomer_info = $this->model_account_customer->getCustomer($this->request->post['parent']);
            if ($parentcustomer_info != null) {
                $this->request->post['customer_group_id'] = $parentcustomer_info['customer_group_id'];
            }

            //  $log = new Log('error.log');
            $log->write('before add customer');
            $sub_customer_id = $this->model_account_customer->addCustomer($this->request->post, true);

            $log->write('before add customer');
            // Clear any previous login attempts for unregistered accounts.
            $this->model_account_customer->deleteLoginAttempts($this->request->post['email']);
            //$logged_in = $this->customer->login($this->request->post['email'], $this->request->post['password']);
            //  $json['message'] ='User added successfully!'
            // Add to activity log
            $this->load->model('account/activity');

            $activity_data = [
                'customer_id' => $this->customer->getId(),
                'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
                'sub_customers_id' => $sub_customer_id,
            ];

            // $this->model_account_activity->addActivity('register', $activity_data);
            $this->model_account_activity->addActivity('sub_customer_created', $activity_data);

            /* If not able to login */
            // $data['status'] = true;
            // $data['customer_id'] = $sub_customer_id;
            // $json['data'] =$data;
            $json['customer_id'] = $sub_customer_id;

            // if (!$logged_in) {
            //     $data['status'] = false;
            // }
            // $data['text_new_signup_reward'] = $this->language->get('text_new_signup_reward');
            // $data['text_new_signup_credit'] = $this->language->get('text_new_signup_credit');
            //$data['message'] = $this->language->get( 'verify_mail_sent' );

            $json['message'] = $this->language->get('text_success_registered');

            // }
        } catch (exception $ex) {
            $json['message'] = 'Something went wrong';
        } finally {

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }
    }

}
