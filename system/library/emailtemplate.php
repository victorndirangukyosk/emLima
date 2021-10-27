<?php

require_once DIR_SYSTEM . '/vendor/autoload.php'; // Loads the library
require DIR_SYSTEM . '/vendor/twilio-php-master/Twilio/autoload.php';

use AfricasTalking\SDK\AfricasTalking;
use paragraph1\phpFCM\Client as FCMClient;

require DIR_SYSTEM . '/vendor/zenvia/human_gateway_client_api/HumanClientMain.php';

use paragraph1\phpFCM\Message;
use paragraph1\phpFCM\Notification;
use paragraph1\phpFCM\Recipient\Device;
use Twilio\Rest\Client;

require_once DIR_SYSTEM . '/vendor/fcp-php/autoload.php';

class Emailtemplate {

    public function __construct($registry) {
        $this->config = $registry->get('config');
        $this->url = $registry->get('url');
        $this->language = $registry->get('language');
        $this->db = $registry->get('db');

        $this->currency = new Currency($registry);
    }

    // Mail Subject
    public function getSubject($type, $template_id, $data) {
        $template = $this->getEmailTemplate($template_id);

        //echo "<pre>";print_r($template);die;
        $findFunctionName = 'get' . ucwords($type) . 'Find';
        $replaceFunctionName = 'get' . ucwords($type) . 'Replace';

        $find = $this->$findFunctionName();
        $replace = $this->$replaceFunctionName($data);

        if (!empty($template['name'])) {
            $subject = trim(str_replace($find, $replace, $template['name']));
        } else {
            $subject = $this->getDefaultSubject($type, $template_id, $data);
        }

        return $subject;
    }

    // Mail Message
    public function getMessage($type, $template_id, $data) {
        $template = $this->getEmailTemplate($template_id);

        $log = new Log('error.log');

        /* $log->write('in fes rhis constant');
          $log->write($data);
          $log->write($template); */
        $findFunctionName = 'get' . ucwords($type) . 'Find';
        $replaceFunctionName = 'get' . ucwords($type) . 'Replace';

        //$log->write($findFunctionName);
        //$log->write($replaceFunctionName);

        $find = $this->$findFunctionName();
        $replace = $this->$replaceFunctionName($data);

        if (!empty($template['description'])) {
            if ('OrderAll' == ucwords($type) || 'VendorOrder' == ucwords($type)) {
                preg_match('/{product:start}(.*){product:stop}/Uis', $template['description'], $template_product);
                if (!empty($template_product[1])) {
                    $template['description'] = str_replace($template_product[1], '', $template['description']);
                }

                preg_match('/{voucher:start}(.*){voucher:stop}/Uis', $template['description'], $template_voucher);
                if (!empty($template_voucher[1])) {
                    $template['description'] = str_replace($template_voucher[1], '', $template['description']);
                }

                preg_match('/{comment:start}(.*){comment:stop}/Uis', $template['description'], $template_comment);
                if (!empty($template_comment[1])) {
                    $template['description'] = str_replace($template_comment[1], '', $template['description']);
                }

                preg_match('/{tax:start}(.*){tax:stop}/Uis', $template['description'], $template_tax);
                if (!empty($template_tax[1])) {
                    $template['description'] = str_replace($template_tax[1], '', $template['description']);
                }

                preg_match('/{total:start}(.*){total:stop}/Uis', $template['description'], $template_total);
                if (!empty($template_total[1])) {
                    $template['description'] = str_replace($template_total[1], '', $template['description']);
                }
            }
            // print_r($template['description']);
            // die;
            //$log->write($find);
             //$log->write($replace);
             //$log->write('finddddddddddd');
            /* $log->write("replace");
              $log->write($template['description']);
              $log->write($replace);
              $log->write($find); */
              
            $message = trim(str_replace($find, $replace, $template['description']));
            //$log->write($message);
        } else {
            $message = $this->getDefaultMessage($type, $template_id, $data);
        }

        return $message;
    }

    public function getSmsMessage($type, $template_id, $data) {
        $template = $this->getEmailTemplate($template_id);

        $findFunctionName = 'get' . ucwords($type) . 'Find';
        $replaceFunctionName = 'get' . ucwords($type) . 'Replace';

        $find = $this->$findFunctionName();
        $replace = $this->$replaceFunctionName($data);

        if (!empty($template['sms'])) {
            $message = trim(str_replace($find, $replace, $template['sms']));
        } else {
            $message = $this->getDefaultMessage($type, $template_id, $data);
        }

        return $message;
    }

    public function getSmsEnabled($type, $template_id) {
        $template = $this->getEmailTemplate($template_id);
        if ($template['sms_status']) {
            return true;
        } else {
            return false;
        }
    }

    public function getEmailEnabled($type, $template_id) {
        $template = $this->getEmailTemplate($template_id);
        if ($template['email_status']) {
            return true;
        } else {
            return false;
        }
    }

    public function getNotificationEnabled($type, $template_id) {
        $template = $this->getEmailTemplate($template_id);
        if ($template['mobile_notification']) {
            return true;
        } else {
            return false;
        }
    }

    public function sendDynamicPushNotification($to, $deviceId, $message, $title, $sendData, $app_action = 'com.instagolocal.show_wallet') {
        $log = new Log('error.log');
        $log->write('sendDynamicPushNotification');

        $log->write($to);
        $log->write($deviceId);
        $log->write($message);
        $log->write($title);
        $log->write($sendData);

        if (isset($to)) {
            if (isset($deviceId) && isset($to)) {
                $log->write('api key');

                $apiKey = $this->config->get('config_seller_api_key');
                $log->write($apiKey);
                $client = new FCMClient();
                $client->setApiKey($apiKey);
                $client->injectHttpClient(new \GuzzleHttp\Client());

                $note = new Notification($title, $message);
                $note->setIcon('notification_icon_resource_name')
                        ->setColor('#3ca826')
                        ->setSound('notification_sound')
                        ->setClickAction($app_action)
                        ->setBadge(1);

                $message = new Message();
                $message->addRecipient(new Device($deviceId));
                $message->setNotification($note)
                        ->setData($sendData);

                $response = $client->send($message);
                //var_dump($response);die;

                $log->write($response);
                if ($response->getStatusCode()) {
                    $json['success'] = 'Success: push notification sent.';
                } else {
                    $json['error'] = 'fcm api failed ';
                }
            } else {
                $json['error'] = 'device id empty of user';
            }
        } else {
            $json['error'] = 'no user_id';
        }

        $log->write('retruen');

        return true;
    }

    public function getNotificationMessage($type, $template_id, $data) {
        $template = $this->getEmailTemplate($template_id);

        $findFunctionName = 'get' . ucwords($type) . 'Find';
        $replaceFunctionName = 'get' . ucwords($type) . 'Replace';

        $find = $this->$findFunctionName();
        $replace = $this->$replaceFunctionName($data);

        if (!empty($template['mobile_notification_template'])) {
            $message = trim(str_replace($find, $replace, $template['mobile_notification_template']));
        } else {
            $message = $this->getDefaultMessage($type, $template_id, $data);
        }

        return $message;
    }

    public function getNotificationTitle($type, $template_id, $data) {
        $template = $this->getEmailTemplate($template_id);

        $findFunctionName = 'get' . ucwords($type) . 'Find';
        $replaceFunctionName = 'get' . ucwords($type) . 'Replace';

        $find = $this->$findFunctionName();
        $replace = $this->$replaceFunctionName($data);

        if (!empty($template['mobile_notification_title'])) {
            $message = trim(str_replace($find, $replace, $template['mobile_notification_title']));
        } else {
            $message = $this->getDefaultMessage($type, $template_id, $data);
        }

        return $message;
    }

    //Mail Text
    public function getText($type, $template_id, $data) {
        $findName = 'get' . ucwords($type) . 'Text';

        return $this->$findName($template_id, $data);
    }

    // Mail Template
    public function getEmailTemplate($email_template) {
        //$email_template_data = [];
        $item = explode('_', $email_template);
        //  echo "<pre>";print_r($item);die;
        if ('order' == $item[0]) {
            if (0 == $item[1]) {
                $item[1] = 1;
            }
        }
        $log = new Log('error.log');
        $log = new Log($email_template);

        $log->write('email template');
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . 'email AS e LEFT JOIN ' . DB_PREFIX . "email_description AS ed ON ed.email_id = e.id WHERE e.type = '{$item[0]}' AND e.text_id = '{$item[1]}' AND ed.language_id = '{$this->config->get('config_language_id')}'");

        if (!$query->num_rows) {
            $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . 'email AS e LEFT JOIN ' . DB_PREFIX . "email_description AS ed ON ed.email_id = e.id WHERE e.type = '{$item[0]}' AND e.text_id = '{$item[1]}'");
        }
        /*
          $log->write($query->rows); */
        // echo "<pre>";print_r("SELECT * FROM " . DB_PREFIX . "email AS e LEFT JOIN " . DB_PREFIX . "email_description AS ed ON ed.email_id = e.id WHERE e.type = '{$item[0]}' AND e.text_id = '{$item[1]}' AND ed.language_id = '{$this->config->get('config_language_id')}'");die;

        foreach ($query->rows as $result) {
            $email_template_data = [
                'text' => $result['text'],
                'text_id' => $result['text_id'],
                'type' => $result['type'],
                'context' => $result['context'],
                'name' => $result['name'],
                'description' => $result['description'],
                'status' => $result['status'],
                'sms_status' => $result['sms_status'],
                'email_status' => $result['email_status'],
                'sms' => $result['sms'],
                'mobile_notification' => $result['mobile_notification'],
                'mobile_notification_template' => $result['mobile_notification_template'],
                'mobile_notification_title' => $result['mobile_notification_title'],
            ];
        }

        return $email_template_data;
    }

    // Customer Login OTP
    public function getLoginOTPFind() {
        $result = ['{username}', '{otp}', '{site_url}', '{logo}', '{system_name}', '{year}', '{help_center}', '{white_logo}', '{terms}', '{privacy_policy}', '{system_email}', '{system_phone}'];

        return $result;
    }

    public function getLoginOTPReplace($data) {
        $result = [
            'username' => $data['username'],
            'otp' => $data['otp'],
            //common replace
            'site_url' => HTTPS_CATALOG,
            //'logo'=> HTTPS_CATALOG.'image/' . $this->config->get('config_logo'),
            'logo' => $this->resize($this->config->get('config_logo'), 197, 34),
            //'site_url'=>$this->config->get('config_url'),
            'system_name' => $this->config->get('config_name'),
            'year' => date('Y'),
            'help_center' => $this->url->adminLink('information/help'),
            //'white_logo'=> HTTPS_CATALOG.'image/'. $this->config->get('config_white_logo'),
            'white_logo' => $this->resize($this->config->get('config_white_logo'), 197, 34),
            'terms' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_account_id'), 'SSL'),
            'privacy_policy' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_privacy_policy_id'), 'SSL'),
            'system_email' => $this->config->get('config_email'),
            'system_phone' => '+' . $this->config->get('config_telephone_code') . ' ' . $this->config->get('config_telephone'),
        ];

        return $result;
    }

    // Customer Regsiter OTP
    public function getRegisterOTPFind() {
        $result = ['{username}', '{otp}', '{site_url}', '{logo}', '{system_name}', '{year}', '{help_center}', '{white_logo}', '{terms}', '{privacy_policy}', '{system_email}', '{system_phone}'];

        return $result;
    }

    public function getRegisterOTPReplace($data) {
        $result = [
            'username' => $data['username'],
            'otp' => $data['otp'],
            //common replace
            'site_url' => HTTPS_CATALOG,
            //'logo'=> HTTPS_CATALOG.'image/' . $this->config->get('config_logo'),
            'logo' => $this->resize($this->config->get('config_logo'), 197, 34),
            //'site_url'=>$this->config->get('config_url'),
            'system_name' => $this->config->get('config_name'),
            'year' => date('Y'),
            'help_center' => $this->url->adminLink('information/help'),
            //'white_logo'=> HTTPS_CATALOG.'image/'. $this->config->get('config_white_logo'),
            'white_logo' => $this->resize($this->config->get('config_white_logo'), 197, 34),
            'terms' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_account_id'), 'SSL'),
            'privacy_policy' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_privacy_policy_id'), 'SSL'),
            'system_email' => $this->config->get('config_email'),
            'system_phone' => '+' . $this->config->get('config_telephone_code') . ' ' . $this->config->get('config_telephone'),
        ];

        return $result;
    }

    // Admin Login
    public function getLoginFind() {
        $result = ['{username}', '{store_name}', '{ip_address}', '{site_url}', '{logo}', '{system_name}', '{year}', '{help_center}', '{white_logo}', '{terms}', '{privacy_policy}', '{system_email}', '{system_phone}'];

        return $result;
    }

    public function getLoginReplace($data) {
        $result = [
            'username' => $data['username'],
            'store_name' => $data['store_name'],
            'ip_address' => $data['ip_address'],
            //common replace
            'site_url' => HTTPS_CATALOG,
            //'logo'=> HTTPS_CATALOG.'image/' . $this->config->get('config_logo'),
            'logo' => $this->resize($this->config->get('config_logo'), 197, 34),
            //'site_url'=>$this->config->get('config_url'),
            'system_name' => $this->config->get('config_name'),
            'year' => date('Y'),
            'help_center' => $this->url->adminLink('information/help'),
            //'white_logo'=> HTTPS_CATALOG.'image/'. $this->config->get('config_white_logo'),
            'white_logo' => $this->resize($this->config->get('config_white_logo'), 197, 34),
            'terms' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_account_id'), 'SSL'),
            'privacy_policy' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_privacy_policy_id'), 'SSL'),
            'system_email' => $this->config->get('config_email'),
            'system_phone' => '+' . $this->config->get('config_telephone_code') . ' ' . $this->config->get('config_telephone'),
        ];
        //echo "<pre>";print_r($result);die;

        return $result;
    }

    // Affilate
    public function getAffiliateFind() {
        $result = ['{firstname}', '{lastname}', '{date}', '{store_name}', '{description}', '{order_id}', '{amount}', '{total}', '{email}', '{password}', '{affiliate_code}', '{account_href}', '{site_url}', '{logo}', '{system_name}', '{year}', '{help_center}', '{white_logo}', '{terms}', '{privacy_policy}', '{system_email}', '{system_phone}'];

        return $result;
    }

    public function getAffiliateReplace($data) {
        $result = [
            'firstname' => (!empty($data['firstname'])) ? $data['firstname'] : '',
            'lastname' => (!empty($data['lastname'])) ? $data['lastname'] : '',
            'date' => date($this->language->get('date_format_short'), strtotime(date('Y-m-d H:i:s'))),
            'store_name' => $this->config->get('config_name'),
            'description' => (!empty($data['description'])) ? nl2br($data['description']) : '',
            'order_id' => (!empty($data['order_id'])) ? $data['order_id'] : '',
            'amount' => (!empty($data['amount'])) ? $data['amount'] : '',
            'total' => (!empty($data['total'])) ? $data['total'] : '',
            'email' => (!empty($data['email'])) ? $data['email'] : '',
            'password' => (!empty($data['password'])) ? $data['password'] : '',
            'affiliate_code' => (!empty($data['code'])) ? $data['code'] : '',
            'account_href' => $this->url->link('affiliate/login', '', 'SSL'),
            //common replace
            'site_url' => HTTPS_CATALOG,
            //'logo'=> HTTPS_CATALOG.'image/' . $this->config->get('config_logo'),
            'logo' => $this->resize($this->config->get('config_logo'), 197, 34),
            //'site_url'=>$this->config->get('config_url'),
            'system_name' => $this->config->get('config_name'),
            'year' => date('Y'),
            'help_center' => $this->url->adminLink('information/help'),
            //'white_logo'=> HTTPS_CATALOG.'image/'. $this->config->get('config_white_logo'),
            'white_logo' => $this->resize($this->config->get('config_white_logo'), 197, 34),
            'terms' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_account_id'), 'SSL'),
            'privacy_policy' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_privacy_policy_id'), 'SSL'),
            'system_email' => $this->config->get('config_email'),
            'system_phone' => '+' . $this->config->get('config_telephone_code') . ' ' . $this->config->get('config_telephone'),
        ];

        return $result;
    }

    // Customer
    public function getCustomerFind() {
        $result = ['{firstname}', '{lastname}', '{branchname}', '{subuserfirstname}', '{subuserlastname}', '{subuserorderid}', '{drivername}', '{driverphone}', '{vehicle}', '{deliveryexecutivename}', '{deliveryexecutivephone}', '{date}', '{store_name}', '{email}', '{password}', '{account_href}', '{activate_href}', '{order_link}', '{site_url}', '{logo}', '{system_name}', '{year}', '{help_center}', '{white_logo}', '{terms}', '{privacy_policy}', '{system_email}', '{system_phone}', '{amount}', '{transfer_type}', '{ip_address}', '{order_id}', '{status}', '{mpesa_receipt_number}'];

        return $result;
    }

    public function getCustomerReplace($data) {
        $result = [
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'branchname' => isset($data['branchname']) ? $data['branchname'] : '',
            'subuserfirstname' => isset($data['subuserfirstname']) ? $data['subuserfirstname'] : '',
            'subuserlastname' => isset($data['subuserlastname']) ? $data['subuserlastname'] : '', 
            'subuserorderid' => isset($data['subuserorderid']) ? $data['subuserorderid'] : '',
            'drivername' => isset($data['drivername']) ? $data['drivername'] : '',
            'driverphone' => isset($data['driverphone']) ? $data['driverphone'] : '',
            'vehicle' => isset($data['vehicle']) ? $data['vehicle'] : '',
            'deliveryexecutivename' => isset($data['deliveryexecutivename']) ? $data['deliveryexecutivename'] : '',
            'deliveryexecutivephone' => isset($data['deliveryexecutivephone']) ? $data['deliveryexecutivephone'] : '',
            'date' => date($this->language->get('date_format_short'), strtotime(date('Y-m-d H:i:s'))),
            'store_name' => $this->config->get('config_name'),
            'email' => $data['email'],
            'password' => $data['password'],
            'account_href' => HTTP_CATALOG . 'index.php?path=account/login',
            'activate_href' => (!empty($data['confirm_code'])) ? $this->url->link('account/activate', 'token=' . $data['confirm_code'], 'SSL') : '',
            'order_link' => isset($data['order_link']) ? $data['order_link'] : '',
            //common replace
            'site_url' => HTTPS_CATALOG,
            //'logo'=> HTTPS_CATALOG.'image/' . $this->config->get('config_logo'),
            'logo' => $this->resize($this->config->get('config_logo'), 197, 34),
            //'site_url'=>$this->config->get('config_url'),
            'system_name' => $this->config->get('config_name'),
            'year' => date('Y'),
            'help_center' => $this->url->adminLink('information/help'),
            //'white_logo'=> HTTPS_CATALOG.'image/'. $this->config->get('config_white_logo'),
            'white_logo' => $this->resize($this->config->get('config_white_logo'), 197, 34),
            'terms' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_account_id'), 'SSL'),
            'privacy_policy' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_privacy_policy_id'), 'SSL'),
            'system_email' => $this->config->get('config_email'),
            'system_phone' => '+' . $this->config->get('config_telephone_code') . ' ' . $this->config->get('config_telephone'),
            'amount' => isset($data['amount']) ? $data['amount'] : '',
            'transfer_type' => isset($data['transfer_type']) ? $data['transfer_type'] : '',
            'ip_address' => isset($data['ip_address']) ? $data['ip_address'] : '',
            'order_id' => $data['order_id'],
            'status' => $data['status'],
            'mpesa_receipt_number' => $data['mpesa_receipt_number'],
        ];

        return $result;
    }

    // Referral
    public function getReferralFind() {
        $result = ['{site_name}', '{name}', '{reward_text}', '{refer_link}', '{site_url}', '{logo}', '{system_name}', '{year}', '{help_center}', '{white_logo}', '{terms}', '{privacy_policy}', '{system_email}', '{system_phone}'];

        return $result;
    }

    public function getReferralReplace($data) {
        $result = [
            'site_name' => $data['site_name'],
            'name' => $data['name'],
            'reward_text' => $data['reward_text'],
            'refer_link' => $data['refer_link'],
            //common replace
            'site_url' => HTTPS_CATALOG,
            //'logo'=> HTTPS_CATALOG.'image/' . $this->config->get('config_logo'),
            'logo' => $this->resize($this->config->get('config_logo'), 197, 34),
            //'site_url'=>$this->config->get('config_url'),
            'system_name' => $this->config->get('config_name'),
            'year' => date('Y'),
            'help_center' => $this->url->adminLink('information/help'),
            //'white_logo'=> HTTPS_CATALOG.'image/'. $this->config->get('config_white_logo'),
            'white_logo' => $this->resize($this->config->get('config_white_logo'), 197, 34),
            'terms' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_account_id'), 'SSL'),
            'privacy_policy' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_privacy_policy_id'), 'SSL'),
            'system_email' => $this->config->get('config_email'),
            'system_phone' => '+' . $this->config->get('config_telephone_code') . ' ' . $this->config->get('config_telephone'),
        ];

        return $result;
    }

    // Coming soon
    public function getComingSoonFind() {
        $result = ['{website_link}', '{email_address}', '{site_url}', '{logo}', '{system_name}', '{year}', '{help_center}', '{white_logo}', '{terms}', '{privacy_policy}', '{system_email}', '{system_phone}'];

        return $result;
    }

    public function getComingSoonReplace($data) {
        $result = [
            'website_link' => $data['website_link'],
            'email_address' => $data['email_address'],
            //common replace
            'site_url' => HTTPS_CATALOG,
            //'logo'=> HTTPS_CATALOG.'image/' . $this->config->get('config_logo'),
            'logo' => $this->resize($this->config->get('config_logo'), 197, 34),
            //'site_url'=>$this->config->get('config_url'),
            'system_name' => $this->config->get('config_name'),
            'year' => date('Y'),
            'help_center' => $this->url->adminLink('information/help'),
            //'white_logo'=> HTTPS_CATALOG.'image/'. $this->config->get('config_white_logo'),
            'white_logo' => $this->resize($this->config->get('config_white_logo'), 197, 34),
            'terms' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_account_id'), 'SSL'),
            'privacy_policy' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_privacy_policy_id'), 'SSL'),
            'system_email' => $this->config->get('config_email'),
            'system_phone' => '+' . $this->config->get('config_telephone_code') . ' ' . $this->config->get('config_telephone'),
        ];

        return $result;
    }

    // Return
    public function getVendorReturnFind() {
        $result = ['{return_id}', '{product_name}', '{unit}', '{order_id}', '{site_url}', '{logo}', '{system_name}', '{year}', '{help_center}', '{white_logo}', '{terms}', '{privacy_policy}', '{system_email}', '{system_phone}'];

        return $result;
    }

    public function getVendorReturnReplace($data) {
        $result = [
            'return_id' => $data['return_id'],
            'product_name' => $data['product_name'],
            'unit' => $data['unit'],
            'order_id' => $data['order_id'],
            //common replace
            'site_url' => HTTPS_CATALOG,
            //'logo'=> HTTPS_CATALOG.'image/' . $this->config->get('config_logo'),
            'logo' => $this->resize($this->config->get('config_logo'), 197, 34),
            //'site_url'=>$this->config->get('config_url'),
            'system_name' => $this->config->get('config_name'),
            'year' => date('Y'),
            'help_center' => $this->url->adminLink('information/help'),
            //'white_logo'=> HTTPS_CATALOG.'image/'. $this->config->get('config_white_logo'),
            'white_logo' => $this->resize($this->config->get('config_white_logo'), 197, 34),
            'terms' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_account_id'), 'SSL'),
            'privacy_policy' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_privacy_policy_id'), 'SSL'),
            'system_email' => $this->config->get('config_email'),
            'system_phone' => '+' . $this->config->get('config_telephone_code') . ' ' . $this->config->get('config_telephone'),
        ];

        //echo "<pre>";print_r($result);die;
        return $result;
    }

    // Return
    public function getReturnFind() {
        $result = ['{return_id}', '{product_name}', '{unit}', '{order_id}', '{site_url}', '{logo}', '{system_name}', '{year}', '{help_center}', '{white_logo}', '{terms}', '{privacy_policy}', '{system_email}', '{system_phone}'];

        return $result;
    }

    public function getReturnReplace($data) {
        $result = [
            'return_id' => $data['return_id'],
            'product_name' => $data['product_name'],
            'unit' => $data['unit'],
            'order_id' => $data['order_id'],
            //common replace
            'site_url' => HTTPS_CATALOG,
            //'logo'=> HTTPS_CATALOG.'image/' . $this->config->get('config_logo'),
            'logo' => $this->resize($this->config->get('config_logo'), 197, 34),
            //'site_url'=>$this->config->get('config_url'),
            'system_name' => $this->config->get('config_name'),
            'year' => date('Y'),
            'help_center' => $this->url->adminLink('information/help'),
            //'white_logo'=> HTTPS_CATALOG.'image/'. $this->config->get('config_white_logo'),
            'white_logo' => $this->resize($this->config->get('config_white_logo'), 197, 34),
            'terms' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_account_id'), 'SSL'),
            'privacy_policy' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_privacy_policy_id'), 'SSL'),
            'system_email' => $this->config->get('config_email'),
            'system_phone' => '+' . $this->config->get('config_telephone_code') . ' ' . $this->config->get('config_telephone'),
        ];

        return $result;
    }

    // vendor order mails ( Information )
    /* public function getVendorOrderFind() {
      $result = array('{name}', '{email}', '{store_name}', '{enquiry}', '{firstname}', '{lastname}','{mobile}','{approve_link}','{login_link}','{amount}','{order_id}','{order_link}','{transaction_type}', '{site_url}' , '{logo}', '{system_name}', '{year}', '{help_center}', '{white_logo}', '{terms}','{privacy_policy}','{system_email}', '{system_phone}');
      return $result;
      }



      public function getVendorOrderReplace($data) {
      $result = array(
      'name' => (!empty($data['name'])) ? $data['name'] : '',
      'email' => (!empty($data['email'])) ? $data['email'] : '',
      'store_name' => (!empty($data['order_info']['store_name'])) ? $data['order_info']['store_name'] : '',
      'enquiry' => (!empty($data['enquiry'])) ? $data['enquiry'] : '',
      'firstname' => (!empty($data['firstname'])) ? $data['firstname'] : '',
      'lastname' => (!empty($data['lastname'])) ? $data['lastname'] : '',
      'mobile' => (!empty($data['mobile'])) ? $data['mobile'] : '',
      'approve_link' => (!empty($data['approve_link'])) ? $data['approve_link'] : '',
      'login_link' => (!empty($data['login_link'])) ? $data['login_link'] : '',
      'amount' => (!empty($data['amount'])) ? $data['amount'] : '',
      'order_id' => (!empty($data['order_id'])) ? $data['order_id'] : '',
      'order_link' => (!empty($data['order_link'])) ? $data['order_link'] : '',
      'transaction_type' => (!empty($data['transaction_type'])) ? $data['transaction_type'] : '',

      //common replace
      'site_url'=> HTTPS_CATALOG,
      //'logo'=> HTTPS_CATALOG.'image/' . $this->config->get('config_logo'),
      'logo' => $this->resize($this->config->get('config_logo'),197,34),
      //'site_url'=>$this->config->get('config_url'),
      'system_name'=>$this->config->get('config_name'),
      'year'=>date('Y'),
      'help_center'=> $this->url->adminLink('information/help'),
      //'white_logo'=> HTTPS_CATALOG.'image/'. $this->config->get('config_white_logo'),
      'white_logo' => $this->resize($this->config->get('config_white_logo'),197,34),

      'terms' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_account_id'), 'SSL'),
      'privacy_policy' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_privacy_policy_id'), 'SSL'),
      'system_email'=>$this->config->get('config_email'),
      'system_phone'=>$this->config->get('config_telephone'),

      );

      return $result;
      } */

    // Order
    public function getVendorOrderFind() {
        $result = [
            '{firstname}', '{lastname}', '{delivery_address}', '{shipping_address}', '{payment_address}', '{order_date}', '{product:start}', '{product:stop}',
            '{total:start}', '{total:stop}', '{voucher:start}', '{voucher:stop}', '{special}', '{date}', '{payment}', '{shipment}', '{order_id}', '{total}', '{invoice_number}',
            '{order_href}', '{store_url}', '{status_name}', '{store_name}', '{ip}', '{comment:start}', '{comment:stop}', '{sub_total}', '{shipping_cost}',
            '{client_comment}', '{tax:start}', '{tax:stop}', '{tax_amount}', '{email}', '{telephone}', '{order_pdf_href}', '{delivery_date}', '{delivery_time}', '{customer_notes}', '{site_url}', '{customer_cpf}', '{store_address}', '{store_telephone}', '{store_tax_number}', '{shipping_contact_number}', '{shipping_flat_number}', '{shipping_street_address}', '{shipping_landmark}', '{shipping_zipcode}', '{logo}', '{system_name}', '{year}', '{help_center}', '{white_logo}', '{terms}', '{privacy_policy}', '{system_email}', '{system_phone}',
        ];

        return $result;
    }

    public function getVendorOrderReplace($data) {
        $emailTemplate = $this->getEmailTemplate($data['template_id']);
        $data['order_href'] = $this->maskingOrderDetailUrl($data['order_href']);
        $log = new Log('error.log');

        $log->write('in getVendorOrderReplace');

        foreach ($data as $dataKey => $dataValue) {
            $dataKey = $dataValue;
        }

        // Special
        $special = [];

        // if (sizeof($email_template['special']) <> 0) {
        // //   $special = $this->_prepareProductSpecial((int)$order_info['customer_group_id'], $email_template['special']);
        // }

        $order_info = $data['order_info'];

        // Products
        preg_match('/{product:start}(.*){product:stop}/Uis', $emailTemplate['description'], $template_product);

        if (sizeof($template_product) > 0) {
            if (isset($data['new_invoice'])) {
                //call for new invoice
                $getProducts = $this->getRealOrderProducts($order_info['order_id']);
            } else {
                $getProducts = $this->getOrderProducts($order_info['order_id']);
            }

            $products = $this->getProductsTemplate($order_info, $getProducts, $template_product);

            $emailTemplate['description'] = str_replace($template_product[1], '', $emailTemplate['description']);
        } else {
            $products = [];
        }

        // Comment
        preg_match('/{comment:start}(.*){comment:stop}/Uis', $emailTemplate['description'], $template_comment);

        if (sizeof($template_comment) > 0) {
            if (empty($comment)) {
                $comment[0] = '';
            } else {
                $comment = $this->getCommentTemplate($comment, $template_comment);
            }
            $emailTemplate['description'] = str_replace($template_comment[1], '', $emailTemplate['description']);
        } else {
            $comment[0] = '';
        }

        // Tax
        preg_match('/{tax:start}(.*){tax:stop}/Uis', $emailTemplate['description'], $template_tax);

        if (sizeof($template_tax) > 0) {
            // $taxes = $this->getTaxTemplate($totals, $template_tax);
            $taxes = [];
            $emailTemplate['description'] = str_replace($template_tax[1], '', $emailTemplate['description']);
        } else {
            $taxes = [];
        }

        // Total
        preg_match('/{total:start}(.*){total:stop}/Uis', $emailTemplate['description'], $template_total);

        $order_total = $this->db->query('SELECT * FROM `' . DB_PREFIX . "order_total` WHERE order_id = '" . (int) $order_info['order_id'] . "' and code = 'sub_total' order by sort_order");
        $getTotal = $order_total->rows;

        if (sizeof($template_total) > 0) {
            $tempTotals = $this->getTotalTemplate($getTotal, $template_total, $order_info);
            $emailTemplate['description'] = str_replace($template_total[1], '', $emailTemplate['description']);
        } else {
            $tempTotals = [];
        }
        //$log->write($emailTemplate);
        $address = $data['address'];

        //echo "<pre>";print_r($data);die;
        $payment_address = $address;
        $invoice_no = $data['order_info']['invoice_no'];
        $totals = $data['totals'];
        $tax_amount = $data['tax_amount'];

        $store_info = $this->getStore($order_info['store_id']);
        //$customer_info = $this->getCustomer($order_info['customer_id']);

        $result = [
            'firstname' => $order_info['firstname'],
            'lastname' => $order_info['lastname'],
            'delivery_address' => $address,
            'shipping_address' => $order_info['shipping_address'],
            'payment_address' => $payment_address,
            'order_date' => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
            'product:start' => implode('', $products),
            'product:stop' => '',
            'total:start' => implode('', $tempTotals),
            'total:stop' => '',
            'voucher:start' => '',
            'voucher:stop' => '',
            'special' => (0 != sizeof($special)) ? implode('<br />', $special) : '',
            'date' => date($this->language->get('full_datetime_format'), strtotime(date('Y-m-d H:i:s'))),
            'payment' => $order_info['payment_method'],
            'shipment' => $order_info['shipping_method'],
            'order_id' => $order_info['order_id'],
            'total' => $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value']),
            'invoice_number' => $order_info['invoice_prefix'] . $invoice_no,
            'order_href' => $data['order_href'],
            'store_url' => $order_info['store_url'],
            'status_name' => $data['order_status'],
            'store_name' => $order_info['store_name'],
            'ip' => $order_info['ip'],
            'comment:start' => implode('', $comment),
            'comment:stop' => '',
            'sub_total' => $totals['sub_total'][0]['text'],
            'shipping_cost' => (isset($totals['shipping'][0]['text'])) ? $totals['shipping'][0]['text'] : '',
            'client_comment' => $order_info['comment'],
            'tax:start' => implode('', $taxes),
            'tax:stop' => '',
            'tax_amount' => $this->currency->format($tax_amount, $order_info['currency_code'], $order_info['currency_value']),
            'email_id' => $order_info['email'],
            'telephone' => '+' . $this->config->get('config_telephone_code') . ' ' . $order_info['telephone'],
            'order_pdf_href' => $data['order_pdf_href'],
            'delivery_date' => $order_info['delivery_date'],
            'delivery_time' => $order_info['delivery_timeslot'],
            'customer_notes' => $order_info['comment'],
            'customer_cpf' => $order_info['fax'],
            'store_address' => $store_info['address'],
            'store_telephone' => '+' . $this->config->get('config_telephone_code') . ' ' . $store_info['telephone'],
            'store_tax_number' => $store_info['tax'],
            'shipping_contact_number' => $order_info['shipping_contact_no'],
            'shipping_flat_number' => $order_info['shipping_name'] . ' <br /> ' . $order_info['shipping_flat_number'],
            'shipping_street_address' => $order_info['shipping_landmark'],
            'shipping_landmark' => $order_info['shipping_landmark'],
            'shipping_zipcode' => $order_info['shipping_zipcode'],
            //common replace
            'site_url' => HTTPS_CATALOG,
            //'logo'=> HTTPS_CATALOG.'image/' . $this->config->get('config_logo'),
            'logo' => $this->resize($this->config->get('config_logo'), 197, 34),
            //'site_url'=>$this->config->get('config_url'),
            'system_name' => $this->config->get('config_name'),
            'year' => date('Y'),
            'help_center' => $this->url->adminLink('information/help'),
            //'white_logo'=> HTTPS_CATALOG.'image/'. $this->config->get('config_white_logo'),
            'white_logo' => $this->resize($this->config->get('config_white_logo'), 197, 34),
            'terms' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_account_id'), 'SSL'),
            'privacy_policy' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_privacy_policy_id'), 'SSL'),
            'system_email' => $this->config->get('config_email'),
            'system_phone' => '+' . $this->config->get('config_telephone_code') . ' ' . $this->config->get('config_telephone'),
        ];

        return $result;
    }

    // Vendor Text
    public function getVendorOrderText($template_id, $data) {
        foreach ($data as $dataKey => $dataValue) {
            $$dataKey = $dataValue;
        }

        // Load the language for any mails that might be required to be sent out
        $language = new Language($order_info['language_directory']);
        $language->load('english');
        $language->load('mail/order');

        $text = sprintf($language->get('text_new_greeting'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8')) . "\n\n";
        $text .= $language->get('text_new_order_id') . ' ' . $order_id . "\n";
        $text .= $language->get('text_new_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n";
        $text .= $language->get('text_new_order_status') . ' ' . $order_status . "\n\n";

        if ($comment && $notify) {
            $text .= $language->get('text_new_instruction') . "\n\n";
            $text .= $comment . "\n\n";
        }

        // Products
        $text .= $language->get('text_new_products') . "\n";

        foreach ($getProdcuts as $product) {
            $text .= $product['quantity'] . 'x ' . $product['name'] . ' (' . $product['model'] . ') ' . html_entity_decode($this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";

            $order_option_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "order_option WHERE order_id = '" . (int) $order_id . "' AND order_product_id = '" . $product['order_product_id'] . "'");

            foreach ($order_option_query->rows as $option) {
                if ('file' != $option['type']) {
                    $value = $option['value'];
                } else {
                    $upload_info = $this->getUploadByCode($option['value']);

                    if ($upload_info) {
                        $value = $upload_info['name'];
                    } else {
                        $value = '';
                    }
                }

                $text .= chr(9) . '-' . $option['name'] . ' ' . (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value) . "\n";
            }
        }

        foreach ($getVouchers as $voucher) {
            $text .= '1x ' . $voucher['description'] . ' ' . $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']);
        }

        $text .= "\n";

        $text .= $language->get('text_new_order_total') . "\n";

        foreach ($getTotal as $total) {
            $text .= $total['title'] . ': ' . html_entity_decode($this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";
        }

        $text .= "\n";

        if ($order_info['customer_id']) {
            $text .= $language->get('text_new_link') . "\n";
            $text .= $order_info['store_url'] . 'index.php?path=account/order/info&order_id=' . $order_id . "\n\n";
        }

        /*
          if ($download_status) {
          $text .= $language->get('text_new_download') . "\n";
          $text .= $order_info['store_url'] . 'index.php?path=account/download' . "\n\n";
          }
         */
        // Comment
        if ($order_info['comment']) {
            $text .= $language->get('text_new_comment') . "\n\n";
            $text .= $order_info['comment'] . "\n\n";
        }

        $text .= $language->get('text_new_footer') . "\n\n";

        return $text;
    }

    // vendor order mails end
    // Contact ( Information )
    public function getContactFind() {
        $result = ['{name}', '{email}', '{store_name}', '{enquiry}', '{firstname}', '{lastname}', '{mobile}', '{approve_link}', '{login_link}', '{amount}', '{order_id}', '{order_link}', '{transaction_type}', '{site_url}', '{logo}', '{system_name}', '{year}', '{help_center}', '{white_logo}', '{terms}', '{privacy_policy}', '{system_email}', '{system_phone}'];

        return $result;
    }

    public function getContactReplace($data) {
        $result = [
            'name' => (!empty($data['name'])) ? $data['name'] : '',
            'email' => (!empty($data['email'])) ? $data['email'] : '',
            'store_name' => $this->config->get('config_name'),
            'enquiry' => (!empty($data['enquiry'])) ? $data['enquiry'] : '',
            'firstname' => (!empty($data['firstname'])) ? $data['firstname'] : '',
            'lastname' => (!empty($data['lastname'])) ? $data['lastname'] : '',
            'mobile' => (!empty($data['mobile'])) ? $data['mobile'] : '',
            'approve_link' => (!empty($data['approve_link'])) ? $data['approve_link'] : '',
            'login_link' => (!empty($data['login_link'])) ? $data['login_link'] : '',
            'amount' => (!empty($data['amount'])) ? $data['amount'] : '',
            'order_id' => (!empty($data['order_id'])) ? $data['order_id'] : '',
            'order_link' => (!empty($data['order_link'])) ? $data['order_link'] : '',
            'transaction_type' => (!empty($data['transaction_type'])) ? $data['transaction_type'] : '',
            //common replace
            'site_url' => HTTPS_CATALOG,
            //'logo'=> HTTPS_CATALOG.'image/' . $this->config->get('config_logo'),
            'logo' => $this->resize($this->config->get('config_logo'), 197, 34),
            //'site_url'=>$this->config->get('config_url'),
            'system_name' => $this->config->get('config_name'),
            'year' => date('Y'),
            'help_center' => $this->url->adminLink('information/help'),
            //'white_logo'=> HTTPS_CATALOG.'image/'. $this->config->get('config_white_logo'),
            'white_logo' => $this->resize($this->config->get('config_white_logo'), 197, 34),
            'terms' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_account_id'), 'SSL'),
            'privacy_policy' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_privacy_policy_id'), 'SSL'),
            'system_email' => $this->config->get('config_email'),
            'system_phone' => '+' . $this->config->get('config_telephone_code') . ' ' . $this->config->get('config_telephone'),
        ];

        return $result;
    }

    public function getSellerFind() {
        $result = ['{email}', '{firstname}', '{lastname}', '{login_link}', '{site_url}', '{logo}', '{system_name}', '{year}', '{help_center}', '{white_logo}', '{terms}', '{privacy_policy}', '{system_email}', '{system_phone}'];

        return $result;
    }

    public function getSellerReplace($data) {
        $result = [
            'email' => (!empty($data['email'])) ? $data['email'] : '',
            'firstname' => (!empty($data['firstname'])) ? $data['firstname'] : '',
            'lastname' => (!empty($data['lastname'])) ? $data['lastname'] : '',
            'login_link' => (!empty($data['login_link'])) ? $data['login_link'] : '',
            //common replace
            'site_url' => HTTPS_CATALOG,
            //'logo'=> HTTPS_CATALOG.'image/' . $this->config->get('config_logo'),
            'logo' => $this->resize($this->config->get('config_logo'), 197, 34),
            //'site_url'=>$this->config->get('config_url'),
            'system_name' => $this->config->get('config_name'),
            'year' => date('Y'),
            'help_center' => $this->url->adminLink('information/help'),
            //'white_logo'=> HTTPS_CATALOG.'image/'. $this->config->get('config_white_logo'),
            'white_logo' => $this->resize($this->config->get('config_white_logo'), 197, 34),
            'terms' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_account_id'), 'SSL'),
            'privacy_policy' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_privacy_policy_id'), 'SSL'),
            'system_email' => $this->config->get('config_email'),
            'system_phone' => '+' . $this->config->get('config_telephone_code') . ' ' . $this->config->get('config_telephone'),
        ];

        return $result;
    }

    // Order
    public function getOrderAllFind() {
        $result = [
            '{firstname}', '{lastname}', '{delivery_address}', '{shipping_address}', '{payment_address}', '{order_date}', '{product:start}', '{product:stop}',
            '{total:start}', '{total:stop}', '{voucher:start}', '{voucher:stop}', '{special}', '{date}', '{payment}', '{shipment}', '{order_id}', '{total}', '{invoice_number}',
            '{order_href}', '{store_url}', '{status_name}', '{store_name}', '{ip}', '{comment:start}', '{comment:stop}', '{sub_total}', '{shipping_cost}',
            '{client_comment}', '{tax:start}', '{tax:stop}', '{tax_amount}', '{email}', '{telephone}', '{order_pdf_href}', '{delivery_date}', '{delivery_time}', '{customer_notes}', '{customer_cpf}', '{customer_company_name}', '{store_address}', '{store_telephone}', '{store_tax_number}', '{shipping_contact_number}', '{shipping_flat_number}', '{shipping_street_address}', '{shipping_landmark}', '{shipping_zipcode}', '{site_url}', '{logo}', '{system_name}', '{year}', '{help_center}', '{white_logo}', '{terms}', '{privacy_policy}', '{system_email}', '{system_phone}',
        ];

        return $result;
    }

    public function getOrderAllReplace($data) {
        $emailTemplate = $this->getEmailTemplate($data['template_id']);
        $data['order_href'] = $this->maskingOrderDetailUrl($data['order_href']);
        $log = new Log('error.log');

        $log->write('in getOrderAllReplace');
        //$log->write('in $dathref'.$data['order_href']);
        //die;

        foreach ($data as $dataKey => $dataValue) {
            $dataKey = $dataValue;
        }

        // Special
        $special = [];

        // if (sizeof($email_template['special']) <> 0) {
        // //   $special = $this->_prepareProductSpecial((int)$order_info['customer_group_id'], $email_template['special']);
        // }

        $order_info = $data['order_info'];

        // Products
        preg_match('/{product:start}(.*){product:stop}/Uis', $emailTemplate['description'], $template_product);

        if (sizeof($template_product) > 0) {
            if (isset($data['new_invoice'])) {
                //call for new invoice
                $getProducts = $this->getRealOrderProducts($order_info['order_id']);
            } else {
                $getProducts = $this->getOrderProducts($order_info['order_id']);
            }

            $products = $this->getProductsTemplate($order_info, $getProducts, $template_product);

            $emailTemplate['description'] = str_replace($template_product[1], '', $emailTemplate['description']);
        } else {
            $products = [];
        }

        // Comment
        preg_match('/{comment:start}(.*){comment:stop}/Uis', $emailTemplate['description'], $template_comment);

        if (sizeof($template_comment) > 0) {
            if (empty($comment)) {
                $comment[0] = '';
            } else {
                $comment = $this->getCommentTemplate($comment, $template_comment);
            }
            $emailTemplate['description'] = str_replace($template_comment[1], '', $emailTemplate['description']);
        } else {
            $comment[0] = '';
        }

        // Tax
        preg_match('/{tax:start}(.*){tax:stop}/Uis', $emailTemplate['description'], $template_tax);

        if (sizeof($template_tax) > 0) {
            // $taxes = $this->getTaxTemplate($totals, $template_tax);
            $taxes = [];
            $emailTemplate['description'] = str_replace($template_tax[1], '', $emailTemplate['description']);
        } else {
            $taxes = [];
        }

        // Total
        preg_match('/{total:start}(.*){total:stop}/Uis', $emailTemplate['description'], $template_total);

        $order_total = $this->db->query('SELECT * FROM `' . DB_PREFIX . "order_total` WHERE order_id = '" . (int) $order_info['order_id'] . "' order by sort_order");
        $getTotal = $order_total->rows;

        $order_grandtotal = 0;

        foreach ($getTotal as $tmpvalue) {
            if ('total' == $tmpvalue['code']) {
                $order_grandtotal = $tmpvalue['value'];
            }
        }

        if (sizeof($template_total) > 0) {
            $tempTotals = $this->getTotalTemplate($getTotal, $template_total, $order_info);
            $emailTemplate['description'] = str_replace($template_total[1], '', $emailTemplate['description']);
        } else {
            $tempTotals = [];
        }
        //$log->write($emailTemplate);
        $address = $data['address'];

        //echo "<pre>";print_r($data);die;
        $payment_address = $address;
        $invoice_no = $data['order_info']['invoice_no'];
        $totals = $data['totals'];
        $tax_amount = $data['tax_amount'];

        $store_info = $this->getStore($order_info['store_id']);
        $customer_info = $this->getCustomer($order_info['customer_id']);

        $result = [
            'firstname' => $order_info['firstname'],
            'lastname' => $order_info['lastname'],
            'delivery_address' => $address,
            'shipping_address' => $order_info['shipping_address'],
            'payment_address' => $payment_address,
            'order_date' => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
            'product:start' => implode('', $products),
            'product:stop' => '',
            'total:start' => implode('', $tempTotals),
            'total:stop' => '',
            'voucher:start' => '',
            'voucher:stop' => '',
            'special' => (0 != sizeof($special)) ? implode('<br />', $special) : '',
            'date' => date('d M Y h:i A', strtotime(date('Y-m-d H:i:s'))),
            'payment' => $order_info['payment_method'],
            'shipment' => $order_info['shipping_method'],
            'order_id' => $order_info['order_id'],
            'total' => $this->currency->format($order_grandtotal, $order_info['currency_code'], $order_info['currency_value']),
            'invoice_number' => $order_info['invoice_prefix'] . $invoice_no,
            'order_href' => $data['order_href'],
            'store_url' => $order_info['store_url'],
            'status_name' => $data['order_status'],
            'store_name' => $order_info['store_name'],
            'ip' => $order_info['ip'],
            'comment:start' => implode('', $comment),
            'comment:stop' => '',
            'sub_total' => $totals['sub_total'][0]['text'],
            'shipping_cost' => (isset($totals['shipping'][0]['text'])) ? $totals['shipping'][0]['text'] : '',
            'client_comment' => $order_info['comment'],
            'tax:start' => implode('', $taxes),
            'tax:stop' => '',
            'tax_amount' => $this->currency->format($tax_amount, $order_info['currency_code'], $order_info['currency_value']),
            'email' => $order_info['email'],
            'telephone' => '+' . $this->config->get('config_telephone_code') . ' ' . $order_info['telephone'],
            'order_pdf_href' => $data['order_pdf_href'],
            'delivery_date' => $order_info['delivery_date'],
            'delivery_time' => $order_info['delivery_timeslot'],
            'customer_notes' => $order_info['comment'],
            'customer_cpf' => $order_info['fax'],
            'customer_company_name' => $customer_info['company_name'],
            'store_address' => $store_info['address'],
            'store_telephone' => '+' . $this->config->get('config_telephone_code') . ' ' . $store_info['telephone'],
            'store_tax_number' => $store_info['tax'],
            'shipping_contact_number' => $order_info['shipping_contact_no'],
            'shipping_flat_number' => $order_info['shipping_name'] . ' <br /> ' . $order_info['shipping_flat_number'],
            'shipping_street_address' => $order_info['shipping_landmark'],
            'shipping_landmark' => $order_info['shipping_landmark'],
            'shipping_zipcode' => $order_info['shipping_zipcode'],
            //common replace
            'site_url' => HTTPS_CATALOG,
            //'logo'=> HTTPS_CATALOG.'image/' . $this->config->get('config_logo'),
            'logo' => $this->resize($this->config->get('config_logo'), 197, 34),
            //'site_url'=>$this->config->get('config_url'),
            'system_name' => $this->config->get('config_name'),
            'year' => date('Y'),
            'help_center' => $this->url->adminLink('information/help'),
            //'white_logo'=> HTTPS_CATALOG.'image/'. $this->config->get('config_white_logo'),
            'white_logo' => $this->resize($this->config->get('config_white_logo'), 197, 34),
            'terms' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_account_id'), 'SSL'),
            'privacy_policy' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_privacy_policy_id'), 'SSL'),
            'system_email' => $this->config->get('config_email'),
            'system_phone' => '+' . $this->config->get('config_telephone_code') . ' ' . $this->config->get('config_telephone'),
        ];

        /* $log->write($result);
        $log->write("MAIL DATA");
        $log->write($this->config->get('config_logo'));

          $log->write($this->language->get('full_datetime_format')); */

        return $result;
    }

    // Review
    public function getReviewFind() {
        $result = ['{author}', '{review}', '{date}', '{rating}', '{product}', '{site_url}', '{logo}', '{system_name}', '{year}', '{help_center}', '{white_logo}', '{terms}', '{privacy_policy}', '{system_email}', '{system_phone}'];

        return $result;
    }

    public function getReviewReplace($data) {
        $result = [
            'author' => $data['name'],
            'review' => $data['text'],
            'date' => date($this->language->get('date_format_short'), time()),
            'rating' => $data['rating'],
            'product' => $data['product'],
            //common replace
            'site_url' => HTTPS_CATALOG,
            //'logo'=> HTTPS_CATALOG.'image/' . $this->config->get('config_logo'),
            'logo' => $this->resize($this->config->get('config_logo'), 197, 34),
            //'site_url'=>$this->config->get('config_url'),
            'system_name' => $this->config->get('config_name'),
            'year' => date('Y'),
            'help_center' => $this->url->adminLink('information/help'),
            //'white_logo'=> HTTPS_CATALOG.'image/'. $this->config->get('config_white_logo'),
            'white_logo' => $this->resize($this->config->get('config_white_logo'), 197, 34),
            'terms' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_account_id'), 'SSL'),
            'privacy_policy' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_privacy_policy_id'), 'SSL'),
            'system_email' => $this->config->get('config_email'),
            'system_phone' => '+' . $this->config->get('config_telephone_code') . ' ' . $this->config->get('config_telephone'),
        ];

        return $result;
    }

    // Voucher
    public function getVoucherFind() {
        $result = ['{recip_name}', '{recip_email}', '{date}', '{store_name}', '{name}', '{amount}', '{message}', '{store_href}', '{image}', '{code}', '{site_url}', '{logo}', '{system_name}', '{year}', '{help_center}', '{white_logo}', '{terms}', '{privacy_policy}', '{system_email}', '{system_phone}'];

        return $result;
    }

    public function getVoucherReplace($data) {
        $result = [
            'recip_name' => $data['recip_name'],
            'recip_email' => $data['recip_email'],
            'date' => date($this->language->get('date_format_short'), strtotime(date('Y-m-d H:i:s'))),
            'store_name' => $data['store_name'],
            'name' => $data['name'],
            'amount' => $data['amount'],
            'message' => $data['message'],
            'store_href' => $data['store_href'],
            'image' => (file_exists(DIR_IMAGE . $data['image'])) ? 'cid:' . md5(basename($data['image'])) : '', 'code' => $data['code'],
            //common replace
            'site_url' => HTTPS_CATALOG,
            //'logo'=> HTTPS_CATALOG.'image/' . $this->config->get('config_logo'),
            'logo' => $this->resize($this->config->get('config_logo'), 197, 34),
            //'site_url'=>$this->config->get('config_url'),
            'system_name' => $this->config->get('config_name'),
            'year' => date('Y'),
            'help_center' => $this->url->adminLink('information/help'),
            //'white_logo'=> HTTPS_CATALOG.'image/'. $this->config->get('config_white_logo'),
            'white_logo' => $this->resize($this->config->get('config_white_logo'), 197, 34),
            'terms' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_account_id'), 'SSL'),
            'privacy_policy' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_privacy_policy_id'), 'SSL'),
            'system_email' => $this->config->get('config_email'),
            'system_phone' => '+' . $this->config->get('config_telephone_code') . ' ' . $this->config->get('config_telephone'),
        ];

        return $result;
    }

    // invoice
    public function getInvoiceFind() {
        $result = ['{order_id}', '{total}', '{subtotal}', '{site_url}', '{logo}', '{system_name}', '{year}', '{help_center}', '{white_logo}', '{terms}', '{privacy_policy}', '{system_email}', '{system_phone}'];

        return $result;
    }

    public function getInvoiceReplace($data) {
        $result = [
            'order_id' => $data['order_id'],
            'total' => $data['total'],
            'subtotal' => $data['subtotal'],
            //common replace
            'site_url' => HTTPS_CATALOG,
            //'logo'=> HTTPS_CATALOG.'image/' . $this->config->get('config_logo'),
            'logo' => $this->resize($this->config->get('config_logo'), 197, 34),
            //'site_url'=>$this->config->get('config_url'),
            'system_name' => $this->config->get('config_name'),
            'year' => date('Y'),
            'help_center' => $this->url->adminLink('information/help'),
            //'white_logo'=> HTTPS_CATALOG.'image/'. $this->config->get('config_white_logo'),
            'white_logo' => $this->resize($this->config->get('config_white_logo'), 197, 34),
            'terms' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_account_id'), 'SSL'),
            'privacy_policy' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_privacy_policy_id'), 'SSL'),
            'system_email' => $this->config->get('config_email'),
            'system_phone' => '+' . $this->config->get('config_telephone_code') . ' ' . $this->config->get('config_telephone'),
        ];

        return $result;
    }

    // Order Text
    public function getOrderText($template_id, $data) {
        foreach ($data as $dataKey => $dataValue) {
            $$dataKey = $dataValue;
        }

        // Load the language for any mails that might be required to be sent out
        $language = new Language($order_info['language_directory']);
        $language->load('english');
        $language->load('mail/order');

        $text = sprintf($language->get('text_new_greeting'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8')) . "\n\n";
        $text .= $language->get('text_new_order_id') . ' ' . $order_id . "\n";
        $text .= $language->get('text_new_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n";
        $text .= $language->get('text_new_order_status') . ' ' . $order_status . "\n\n";

        if ($comment && $notify) {
            $text .= $language->get('text_new_instruction') . "\n\n";
            $text .= $comment . "\n\n";
        }

        // Products
        $text .= $language->get('text_new_products') . "\n";

        foreach ($getProdcuts as $product) {
            $text .= $product['quantity'] . 'x ' . $product['name'] . ' (' . $product['model'] . ') ' . html_entity_decode($this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";

            $order_option_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "order_option WHERE order_id = '" . (int) $order_id . "' AND order_product_id = '" . $product['order_product_id'] . "'");

            foreach ($order_option_query->rows as $option) {
                if ('file' != $option['type']) {
                    $value = $option['value'];
                } else {
                    $upload_info = $this->getUploadByCode($option['value']);

                    if ($upload_info) {
                        $value = $upload_info['name'];
                    } else {
                        $value = '';
                    }
                }

                $text .= chr(9) . '-' . $option['name'] . ' ' . (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value) . "\n";
            }
        }

        foreach ($getVouchers as $voucher) {
            $text .= '1x ' . $voucher['description'] . ' ' . $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']);
        }

        $text .= "\n";

        $text .= $language->get('text_new_order_total') . "\n";

        foreach ($getTotal as $total) {
            $text .= $total['title'] . ': ' . html_entity_decode($this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";
        }

        $text .= "\n";

        if ($order_info['customer_id']) {
            $text .= $language->get('text_new_link') . "\n";
            $text .= $order_info['store_url'] . 'index.php?path=account/order/info&order_id=' . $order_id . "\n\n";
        }

        /*
          if ($download_status) {
          $text .= $language->get('text_new_download') . "\n";
          $text .= $order_info['store_url'] . 'index.php?path=account/download' . "\n\n";
          }
         */
        // Comment
        if ($order_info['comment']) {
            $text .= $language->get('text_new_comment') . "\n\n";
            $text .= $order_info['comment'] . "\n\n";
        }

        $text .= $language->get('text_new_footer') . "\n\n";

        return $text;
    }

    // Language
    public function getLanguage() {
        $sql = 'SELECT * FROM ' . DB_PREFIX . "language WHERE language_id = '" . $this->config->get('config_language_id') . "'";
        $query = $this->db->query($sql);

        return $query->row;
    }

    // Order Special
    // Order Product
    public function getOrderProducts($order_id) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");

        return $query->rows;
    }

    public function getProductsTemplate($order_info, $getProducts, $template_product) {
        $result = [];

        foreach ($getProducts as $product) {
            $option = [];
            $attribute = [];

            // Product Option Order
            if (false !== stripos($template_product[1], '{product_option}')) {
                $product_option = $this->getOrderOptions($order_info['order_id'], $product['product_id']);

                foreach ($product_option as $option) {
                    if ('file' != $option['type']) {
                        $option[] = '<i>' . $option['name'] . '</i>: ' . $option['value'];
                    } else {
                        $filename = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
                        $option[] = '<i>' . $option['name'] . '</i>: ' . (utf8_strlen($filename) > 20 ? utf8_substr($filename, 0, 20) . '..' : $filename);
                    }
                }
            }

            // Product Attribute Order
            if (false !== stripos($template_product[1], '{product_attribute}')) {
                $product_attributes = $this->getProductAttributes($product['product_id'], $order_info['language_id']);

                foreach ($product_attributes as $attribute_group) {
                    $attribute_sub_data = '';

                    foreach ($attribute_group['attribute'] as $attribute) {
                        $attribute_sub_data .= '<br />' . $attribute['name'] . ': ' . $attribute['text'];
                    }

                    $attribute[] = '<u>' . $attribute_group['name'] . '</u>' . $attribute_sub_data;
                }
            }

            $getProduct = $this->getProduct($product['product_id']);

            // Product Image Order
            if ($getProduct['image']) {
                if ($this->config->get('template_email_product_thumbnail_width') && $this->config->get('template_email_product_thumbnail_height')) {
                    $image = $this->imageResize($getProduct['image'], $this->config->get('template_email_product_thumbnail_width'), $this->config->get('template_email_product_thumbnail_height'));
                } else {
                    $image = $this->imageResize($getProduct['image'], 80, 80);
                }
            } else {
                $image = '';
            }

            //Replace Product Short Code to Values
            $product_replace = $this->getProductReplace($image, $product, $order_info, $attribute, $option);

            $product_find = $this->getProductFind();

            $result[] = trim(str_replace($product_find, $product_replace, $template_product[1]));
        }

        return $result;
    }

    public function getProductFind() {
        $result = [
            '{product_image}', '{product_name}', '{product_unit}', '{product_model}', '{product_quantity}', '{product_price}', '{product_price_gross}', '{product_attribute}',
            '{product_option}', '{product_tax}', '{product_total}', '{product_total_gross}', '{site_url}', '{logo}', '{system_name}', '{year}', '{help_center}', '{white_logo}', '{terms}', '{privacy_policy}', '{system_email}', '{system_phone}',
        ];

        return $result;
    }

    public function getProductReplace($image, $product, $order_info, $attribute, $option) {
        $getProduct = $this->getProduct($product['product_id']);

        $result = [
            'product_image' => $image,
            'product_name' => $product['name'],
            'product_unit' => $product['unit'],
            'product_model' => $product['model'],
            'product_quantity' => $product['quantity'],
            'product_price' => $this->currency->format($product['price'], $order_info['currency_code'], $order_info['currency_value']),
            'product_price_gross' => $this->currency->format(($product['price'] + $product['tax']), $order_info['currency_code'], $order_info['currency_value']),
            'product_attribute' => implode('<br />', $attribute),
            'product_option' => implode('<br />', $option),
            'product_tax' => $this->currency->format($product['tax'], $order_info['currency_code'], $order_info['currency_value']),
            'product_total' => $this->currency->format($product['total'], $order_info['currency_code'], $order_info['currency_value']),
            'product_total_gross' => $this->currency->format($product['total'] + ($product['tax'] * $product['quantity']), $order_info['currency_code'], $order_info['currency_value']),
            //common replace
            'site_url' => HTTPS_CATALOG,
            //'logo'=> HTTPS_CATALOG.'image/' . $this->config->get('config_logo'),
            'logo' => $this->resize($this->config->get('config_logo'), 197, 34),
            //'site_url'=>$this->config->get('config_url'),
            'system_name' => $this->config->get('config_name'),
            'year' => date('Y'),
            'help_center' => $this->url->adminLink('information/help'),
            //'white_logo'=> HTTPS_CATALOG.'image/'. $this->config->get('config_white_logo'),
            'white_logo' => $this->resize($this->config->get('config_white_logo'), 197, 34),
            'terms' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_account_id'), 'SSL'),
            'privacy_policy' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_privacy_policy_id'), 'SSL'),
            'system_email' => $this->config->get('config_email'),
            'system_phone' => '+' . $this->config->get('config_telephone_code') . ' ' . $this->config->get('config_telephone'),
        ];

        return $result;
    }

    public function getOrderOptions($order_id, $order_product_id) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "order_option WHERE order_id = '" . (int) $order_id . "' AND order_product_id = '" . (int) $order_product_id . "'");

        return $query->rows;
    }

    public function getProductAttributes($product_id, $language_id) {
        $product_attribute_group_data = [];

        $product_attribute_group_query = $this->db->query('SELECT ag.attribute_group_id, agd.name FROM ' . DB_PREFIX . 'product_attribute pa LEFT JOIN ' . DB_PREFIX . 'attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN ' . DB_PREFIX . 'attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN ' . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE pa.product_id = '" . (int) $product_id . "' AND agd.language_id = '" . (int) $language_id . "' GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");

        foreach ($product_attribute_group_query->rows as $product_attribute_group) {
            $product_attribute_data = [];

            $product_attribute_query = $this->db->query('SELECT a.attribute_id, ad.name, pa.text FROM ' . DB_PREFIX . 'product_attribute pa LEFT JOIN ' . DB_PREFIX . 'attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN ' . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = '" . (int) $product_id . "' AND a.attribute_group_id = '" . (int) $product_attribute_group['attribute_group_id'] . "' AND ad.language_id = '" . (int) $language_id . "' AND pa.language_id = '" . (int) $language_id . "' ORDER BY a.sort_order, ad.name");

            foreach ($product_attribute_query->rows as $product_attribute) {
                $product_attribute_data[] = [
                    'attribute_id' => $product_attribute['attribute_id'],
                    'name' => $product_attribute['name'],
                    'text' => $product_attribute['text'],
                ];
            }

            $product_attribute_group_data[] = [
                'attribute_group_id' => $product_attribute_group['attribute_group_id'],
                'name' => $product_attribute_group['name'],
                'attribute' => $product_attribute_data,
            ];
        }

        return $product_attribute_group_data;
    }

    // Order Voucher
    public function getOrderVouchers($order_id) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "order_voucher WHERE order_id = '" . (int) $order_id . "'");

        return $query->rows;
    }

    public function getVoucherTemplate($order_info, $getVouchers, $template_voucher) {
        $result = [];

        foreach ($getVouchers as $voucher) {
            // Replace Product Short Code to Values
            $voucher_find = $this->getOrderVoucherFind();
            $voucher_replace = $this->getOrderVoucherReplace($voucher, $order_info);

            $result[] = trim(str_replace($voucher_find, $voucher_replace, $template_voucher[1]));
        }

        return $result;
    }

    public function getOrderVoucherFind() {
        $result = ['{voucher_description}', '{voucher_amount}', '{site_url}', '{logo}', '{system_name}', '{year}', '{help_center}', '{white_logo}', '{terms}', '{privacy_policy}', '{system_email}', '{system_phone}'];

        return $result;
    }

    public function getOrderVoucherReplace($voucher, $order_info) {
        $result = [
            'voucher_description' => $voucher['description'],
            'voucher_amount' => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
            //common replace
            'site_url' => HTTPS_CATALOG,
            //'logo'=> HTTPS_CATALOG.'image/' . $this->config->get('config_logo'),
            'logo' => $this->resize($this->config->get('config_logo'), 197, 34),
            //'site_url'=>$this->config->get('config_url'),
            'system_name' => $this->config->get('config_name'),
            'year' => date('Y'),
            'help_center' => $this->url->adminLink('information/help'),
            //'white_logo'=> HTTPS_CATALOG.'image/'. $this->config->get('config_white_logo'),
            'white_logo' => $this->resize($this->config->get('config_white_logo'), 197, 34),
            'terms' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_account_id'), 'SSL'),
            'privacy_policy' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_privacy_policy_id'), 'SSL'),
            'system_email' => $this->config->get('config_email'),
            'system_phone' => '+' . $this->config->get('config_telephone_code') . ' ' . $this->config->get('config_telephone'),
        ];

        return $result;
    }

    // Order Comment
    public function getCommentTemplate($comment, $template_comment) {
        $result = [];

        // Replace Product Short Code to Values
        $comment_find = $this->getCommentFind();
        $comment_replace = $this->getCommentReplace($comment);

        $result[] = trim(str_replace($comment_find, $comment_replace, $template_comment[1]));

        return $result;
    }

    public function getCommentFind() {
        $result = ['{comment}', '{site_url}', '{logo}', '{system_name}', '{year}', '{help_center}', '{white_logo}', '{terms}', '{privacy_policy}', '{system_email}', '{system_phone}'];

        return $result;
    }

    public function getCommentReplace($comment) {
        $result = [
            'comment' => $comment,
            //common replace
            'site_url' => HTTPS_CATALOG,
            //'logo'=> HTTPS_CATALOG.'image/' . $this->config->get('config_logo'),
            'logo' => $this->resize($this->config->get('config_logo'), 197, 34),
            //'site_url'=>$this->config->get('config_url'),
            'system_name' => $this->config->get('config_name'),
            'year' => date('Y'),
            'help_center' => $this->url->adminLink('information/help'),
            //'white_logo'=> HTTPS_CATALOG.'image/'. $this->config->get('config_white_logo'),
            'white_logo' => $this->resize($this->config->get('config_white_logo'), 197, 34),
            'terms' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_account_id'), 'SSL'),
            'privacy_policy' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_privacy_policy_id'), 'SSL'),
            'system_email' => $this->config->get('config_email'),
            'system_phone' => '+' . $this->config->get('config_telephone_code') . ' ' . $this->config->get('config_telephone'),
        ];

        return $result;
    }

    // Order Tax
    public function getTaxTemplate($totals, $template_tax) {
        $result = [];

        if (isset($totals['tax'])) {
            foreach ($totals['tax'] as $tax) {
                // Replace Product Short Code to Values
                $tax_find = $this->getTaxFind();
                $tax_replace = $this->getTaxReplace($tax);

                $result[] = trim(str_replace($tax_find, $tax_replace, $template_tax[1]));
            }
        }

        return $result;
    }

    public function getTaxFind() {
        $result = ['{tax_title}', '{tax_value}', '{site_url}', '{logo}', '{system_name}', '{year}', '{help_center}', '{white_logo}', '{terms}', '{privacy_policy}', '{system_email}', '{system_phone}'];

        return $result;
    }

    public function getTaxReplace($tax) {
        $result = [
            'tax_title' => $tax['title'],
            'tax_value' => $tax['text'],
            //common replace
            'site_url' => HTTPS_CATALOG,
            //'logo'=> HTTPS_CATALOG.'image/' . $this->config->get('config_logo'),
            'logo' => $this->resize($this->config->get('config_logo'), 197, 34),
            //'site_url'=>$this->config->get('config_url'),
            'system_name' => $this->config->get('config_name'),
            'year' => date('Y'),
            'help_center' => $this->url->adminLink('information/help'),
            //'white_logo'=> HTTPS_CATALOG.'image/'. $this->config->get('config_white_logo'),
            'white_logo' => $this->resize($this->config->get('config_white_logo'), 197, 34),
            'terms' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_account_id'), 'SSL'),
            'privacy_policy' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_privacy_policy_id'), 'SSL'),
            'system_email' => $this->config->get('config_email'),
            'system_phone' => '+' . $this->config->get('config_telephone_code') . ' ' . $this->config->get('config_telephone'),
        ];

        return $result;
    }

    // Order Total
    public function getTotalTemplate($getTotal, $template_total, $order_info) {
        $result = [];

        //echo "<pre>";print_r($getTotal);die;
        foreach ($getTotal as $total) {
            // Replace Product Short Code to Values
            $total_find = $this->getTotalFind();
            $total_replace = $this->getTotalReplace($total, $order_info);

            $result[] = trim(str_replace($total_find, $total_replace, $template_total[1]));
        }

        return $result;
    }

    public function getTotalFind() {
        $result = ['{total_title}', '{total_value}', '{site_url}', '{logo}', '{system_name}', '{year}', '{help_center}', '{white_logo}', '{terms}', '{privacy_policy}', '{system_email}', '{system_phone}'];

        return $result;
    }

    public function getTotalReplace($total, $order_info) {
        $result = [
            'total_title' => $total['title'],
            'total_value' => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
            //common replace
            'site_url' => HTTPS_CATALOG,
            //'logo'=> HTTPS_CATALOG.'image/' . $this->config->get('config_logo'),
            'logo' => $this->resize($this->config->get('config_logo'), 197, 34),
            //'site_url'=>$this->config->get('config_url'),
            'system_name' => $this->config->get('config_name'),
            'year' => date('Y'),
            'help_center' => $this->url->adminLink('information/help'),
            //'white_logo'=> HTTPS_CATALOG.'image/'. $this->config->get('config_white_logo'),
            'white_logo' => $this->resize($this->config->get('config_white_logo'), 197, 34),
            'terms' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_account_id'), 'SSL'),
            'privacy_policy' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_privacy_policy_id'), 'SSL'),
            'system_email' => $this->config->get('config_email'),
            'system_phone' => '+' . $this->config->get('config_telephone_code') . ' ' . $this->config->get('config_telephone'),
        ];

        return $result;
    }

    public function getDefaultSubject($type, $template_id, $data) {
        switch (ucwords($type)) {
            case 'Login':
                $subject = $this->getDefautLoginSubject($template_id, $data);
                break;
            case 'Affilate':
                $subject = $this->getDefautAffilateSubject($template_id, $data);
                break;
            case 'Customer':
                $subject = $this->getDefautCustomerSubject($template_id, $data);
                break;
            case 'Contact':
                $subject = $this->getDefautContactSubject($template_id, $data);
                break;
            case 'Order':
                $subject = $this->getDefautOrderSubject($template_id, $data);
                break;
            case 'Review':
                $subject = $this->getDefautReviewSubject($template_id, $data);
                break;
            case 'Voucher':
                $subject = $this->getDefautVoucherSubject($template_id, $data);
                break;
            case 'Order':
                $subject = $this->getDefautOrderSubject($template_id, $data);
                break;
        }

        return $subject;
    }

    public function getDefaultMessage($type, $template_id, $data) {
        $subject = null;

        switch (ucwords($type)) {
            case 'Login':
                $subject = $this->getDefautLoginMessage($template_id, $data);
                break;
            case 'Affilate':
                $subject = $this->getDefautAffilateMessage($template_id, $data);
                break;
            /* case 'Customer':
              $subject = $this->getDefautCustomerMessage($template_id, $data);
              break; */
            case 'Contact':
                $subject = $this->getDefautContactMessage($template_id, $data);
                break;
            case 'Order':
                $subject = $this->getDefautOrderMessage($template_id, $data);
                break;
            case 'Review':
                $subject = $this->getDefautReviewMessage($template_id, $data);
                break;
            case 'Voucher':
                $subject = $this->getDefautVoucherMessage($template_id, $data);
                break;
            case 'Order':
                $subject = $this->getDefautOrderMessage($template_id, $data);
                break;
        }

        return $subject;
    }

    public function getDefautLoginSubject($type_id, $data) {
        $username = $data['username'];

        $subject = 'User ' . $username . ' logged in on ' . $this->config->get('config_name') . ' admin panel';

        return $subject;
    }

    public function getDefautLoginMessage($type_id, $data) {
        $message = 'Hello,<br/><br/>';
        $message .= 'We would like to notify you that user ' . $data['username'] . ' has just logged in to the admin panel of your store, ' . $data['store_name'] . ', using IP address ' . $data['ip_address'] . '.<br/><br/>';
        $message .= 'If this is expected you need to do nothing about it. If you suspect a hacking attempt, please log in to your store\'s admin panel immediately and change your password at once.<br/><br/>';
        $message .= 'Best Regards,<br/><br/>';
        $message .= 'The ' . $data['store_name'] . ' team<br/><br/>';

        return $message;
    }

    public function getDefautAffilateSubject($type_id, $data) {
        $this->load->language('mail/affiliate');

        if ('affiliate_4' == $type_id) {
            $subject = sprintf($this->language->get('text_approve_subject'), $this->config->get('config_name'));
        } elseif ('affiliate_5' == $type_id) {
            $subject = sprintf($this->language->get('text_commission_subject'), $this->config->get('config_name'));
        } elseif ('affiliate_1' == $type_id) {
            $subject = sprintf($this->language->get('text_register_subject'), $this->config->get('config_name'));
        } elseif ('affiliate_3' == $type_id) {
            $subject = sprintf($this->language->get('text_register_approve_subject'), $this->config->get('config_name'));
        } elseif ('affiliate_2' == $type_id) {
            // Reset Password Null
            $subject = '';
        } else {
            $subject = $this->config->get('config_name') . ' - Affilate Mail';
        }

        $this->load->language('mail/affiliate');

        return $subject;
    }

    public function getDefautAffilateMessage($type_id, $data) {
        $this->load->language('mail/affiliate');

        if ('affiliate_4' == $type_id) {
            $message = sprintf($this->language->get('text_approve_welcome'), $this->config->get('config_name')) . "\n\n";
            $message .= $this->language->get('text_approve_login') . "\n";
            $message .= HTTP_CATALOG . 'index.php?path=affiliate/login' . "\n\n";
            $message .= $this->language->get('text_approve_services') . "\n\n";
            $message .= $this->language->get('text_approve_thanks') . "\n";
            $message .= $this->config->get('config_name');
        } elseif ('affiliate_5' == $type_id) {
            $message = sprintf($this->language->get('text_commission_received'), $this->currency->format($data['amount'], $this->config->get('config_currency'))) . "\n\n";
            $message .= sprintf($this->language->get('text_commission_total'), $this->currency->format($this->getCommissionTotal($data['affiliate_id']), $this->config->get('config_currency')));
        } elseif ('affiliate_1' == $type_id) {
            $message = sprintf($this->language->get('text_register_message'), $data['firstname'] . ' ' . $data['lastname'], $this->config->get('config_name'));
        } elseif ('affiliate_3' == $type_id) {
            $message = sprintf($this->language->get('text_register_approve_message'), $data['firstname'] . ' ' . $data['lastname'], $this->config->get('config_name'));
        } elseif ('affiliate_2' == $type_id) {
            // Reset Password Null
            $message = sprintf($this->language->get('text_register_approve_subject'), $this->config->get('config_name'), $data['firstname'] . ' ' . $data['lastname']);
        } else {
            $message = 'Hi!' . "\n" . 'Welcome ' . $data['firstname'] . ' ' . $data['lastname'];
        }

        return $message;
    }

    /* Affilate getComissionTotal Frontend & Backend */

    public function getCommissionTotal($affiliate_id) {
        $query = $this->db->query('SELECT SUM(amount) AS total FROM ' . DB_PREFIX . "affiliate_commission WHERE affiliate_id = '" . (int) $affiliate_id . "'");

        return $query->row['total'];
    }

    /* Affilate getComissionTotal Frontend & Backend */

    public function getDefaultCustomerSubject($type_id, $data) {
        $this->load->language('mail/customer');

        if ('customer_4' == $type_id) {
            $subject = sprintf($this->language->get('text_approve_subject'), $this->config->get('config_name'));
        } elseif ('customer_1' == $type_id) {
            // Register
            $subject = sprintf($this->language->get('text_register_subject'), $this->config->get('config_name'));
        } elseif ('customer_2' == $type_id) {
            // Aprove
            $subject = sprintf($this->language->get('text_approve_wait_subject'), $this->config->get('config_name'));
        } elseif ('customer_3' == $type_id) {
            // Reset
            $subject = sprintf($this->language->get('text_approve_subject'), $this->config->get('config_name'));
        }

        return $subject;
    }

    public function getDefaultCustomerMessage($type_id, $data) {
        $this->load->language('mail/customer');

        if ('customer_4' == $type_id) {
            $store_name = $this->config->get('config_name');
            $store_url = HTTP_CATALOG . 'index.php?path=account/login';

            $message = sprintf($this->language->get('text_approve_welcome'), $store_name) . "\n\n";
            $message .= $this->language->get('text_approve_login') . "\n";
            $message .= $store_url . "\n\n";
            $message .= $this->language->get('text_approve_services') . "\n\n";
            $message .= $this->language->get('text_approve_thanks') . "\n";
            $message .= $store_name;
        } elseif ('customer_1' == $type_id) {
            // Register
            $message = sprintf($this->language->get('text_register_message'), $this->config->get('config_name'));
        } elseif ('customer_2' == $type_id) {
            // Aprove
            $message = sprintf($this->language->get('text_register_message'), $this->config->get('config_name'));
        } elseif ('customer_3' == $type_id) {
            $message = ' --- ';
        }

        return $message;
    }

    public function imageResize($filename, $width, $height) {
        if (!is_file(DIR_IMAGE . $filename)) {
            return;
        }

        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        $old_image = $filename;
        $new_image = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . $width . 'x' . $height . '.' . $extension;

        if (!is_file(DIR_IMAGE . $new_image) || (filectime(DIR_IMAGE . $old_image) > filectime(DIR_IMAGE . $new_image))) {
            $path = '';

            $directories = explode('/', dirname(str_replace('../', '', $new_image)));

            foreach ($directories as $directory) {
                $path = $path . '/' . $directory;

                if (!is_dir(DIR_IMAGE . $path)) {
                    $this->filesystem->mkdir(DIR_IMAGE . $path);
                }
            }

            list($width_orig, $height_orig) = getimagesize(DIR_IMAGE . $old_image);

            if ($width_orig != $width || $height_orig != $height) {
                $image = new Image(DIR_IMAGE . $old_image);
                $image->resize($width, $height);
                $image->save(DIR_IMAGE . $new_image);
            } else {
                copy(DIR_IMAGE . $old_image, DIR_IMAGE . $new_image);
            }
        }
        $log = new Log('error.log');

        if ($_SERVER['HTTPS']) {
            return HTTPS_IMAGE . $new_image;
        } else {
            return HTTP_IMAGE . $new_image;
        }

        if (!trim($this->config->get('config_ssl')) && !trim($this->config->get('config_url'))) {
            return HTTP_SERVER . 'image/' . $new_image;
        }
    }

    public function getUploadByCode($code) {
        $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . "upload` WHERE code = '" . $this->db->escape($code) . "'");

        return $query->row;
    }

    public function getProduct($product_id) {
        $this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
        $this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');
        $this->db->where('product_to_store.product_store_id', $product_id);

        $query = $this->db->get('product_to_store');

        if ($query->num_rows) {
            return [
                'product_id' => $query->row['product_id'],
                'name' => $query->row['name'],
                'unit' => $query->row['unit'],
                'description' => $query->row['description'],
                'meta_title' => $query->row['meta_title'],
                'meta_description' => $query->row['meta_description'],
                'meta_keyword' => $query->row['meta_keyword'],
                'tag' => $query->row['tag'],
                'model' => $query->row['model'],
                'quantity' => $query->row['quantity'],
                'image' => $query->row['image'],
                'price' => ($query->row['price'] ? $query->row['price'] : $query->row['price']),
                'special' => $query->row['special_price'],
                'tax_class_id' => $query->row['tax_class_id'],
                'subtract' => $query->row['subtract_quantity'],
                'rating' => 0,
                'reviews' => 0,
                'minimum' => $query->row['min_quantity'],
                'sort_order' => $query->row['sort_order'],
                'status' => $query->row['status'],
                'date_added' => $query->row['date_added'],
                'date_modified' => $query->row['date_modified'],
                'viewed' => 0,
            ];
        } else {
            return false;
        }
    }

    public function sendmessageOld($to, $message) {
        $log = new Log('error.log');
        /* $sender_id = $this->config->get('config_sms_sender_id');
          $username  = $this->config->get('config_sms_username');
          $password  = $this->config->get('config_sms_password');

          $url= 'http://login.smsgatewayhub.com/smsapi/pushsms.aspx?user='.$username.'&pwd='.$password.'&to='.$to.'&sid='.$sender_id.'&msg='.urlencode($message).'&fl=0&gwid=2';

          // Get cURL resource
          $curl = curl_init();
          // Set some options - we are passing in a useragent too here
          curl_setopt_array($curl, array(
          CURLOPT_RETURNTRANSFER => 1,
          CURLOPT_URL => $url,
          CURLOPT_USERAGENT => 'Codular Sample cURL Request'
          ));
          //Send the request & save response to $resp
          $resp = curl_exec($curl);
          // Close request to clear up some resources
          curl_close($curl);
         */
        //zenvia,twilio

        if ('twilio' == $this->config->get('config_sms_protocol')) {
            $sid = $this->config->get('config_sms_sender_id');
            $token = $this->config->get('config_sms_token');
            $from = $this->config->get('config_sms_number');

            $log->write('sms twilio 2');
            $log->write($to);
            if ('+' != substr($to, 0, 1)) {
                $to = '+' . $to;
            }
            //$log->write($to);
            $client = new Client($sid, $token);

            try {
                $sms = $client->messages->create(
                        $to, [
                    'from' => $from,
                    //'from' => '+19789864215',
                    'body' => $message,
                        ]
                );
            } catch (Exception $exception) {
                return false;
            }
        } else {
            $from = $this->config->get('config_zenvia_sms_sender_id');
            $authToken = $this->config->get('config_zenvia_sms_token');
            $apiEndPoint = $this->config->get('config_zenvia_sms_number');

            $log->write('zenvia sms');

            $postData = [
                'sendSmsRequest' => [
                    'from' => $from,
                    'to' => $to,
                    'msg' => $message,
                    'callbackOption' => 'NONE',
                    'id' => uniqid(),
                    'aggregateId' => '1111',
                ],
            ];
            //c3VwZXIub25saW5lLndlYjpydFdjSVVZUENO
            $headr = [];
            $headr[] = 'Accept : application/json';
            $headr[] = 'Content-type: application/json';
            $headr[] = 'Authorization: Basic ' . $authToken;

            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, $apiEndPoint);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headr);

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postData));

            $response = curl_exec($curl);

            curl_close($curl);
            $response = json_decode($response, true);

            if (isset($response['sendSmsResponse'])) {
                if (is_array($response['sendSmsResponse'])) {
                    if (00 == $response['sendSmsResponse']['statusCode']) {
                        
                    } else {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    public function sendmessage($to, $message) {

        $log = new Log('error.log');
        $log->write('SMS SENDING');
        $log->write($to);
        $log->write($message);
        $log->write('checking message .................');
        $log->write('SMS SENDING');
        /* $number = '734000006';
          if (strpos($to, $number) !== false) {
          $log = new Log('error.log');
          $log->write('sms not sending for RR Corporate Ltd Customer ID : 301');
          } else { */
        $log = new Log('error.log');

        $result['status'] = false;
        $result['message'] = 'Failed';
        //zenvia,twilio

        $country_prefix = $this->config->get('config_telephone_code');

        $to = $country_prefix . '' . $to;

        if ('awssns' == $this->config->get('config_sms_protocol')) {
            $sdk = new Aws\Sns\SnsClient([
                'region' => 'us-east-2',
                'version' => 'latest',
                'credentials' => ['key' => 'AKIAUWRTJZVBPUAIMRKY', 'secret' => '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']
            ]);

            $result = $sdk->publish([
                'Message' => $message,
                'PhoneNumber' => $to,
                'MessageAttributes' => ['AWS.SNS.SMS.SenderID' => [
                        'DataType' => 'String',
                        'StringValue' => 'KWIKBASKET'
                    ]
            ]]);
        } elseif ('africastalking' == $this->config->get('config_sms_protocol')) {
            $username = $this->config->get('config_africastalking_sms_username');
            $apiKey = $this->config->get('config_africastalking_sms_api_key');
            $AT = new AfricasTalking($username, $apiKey);
            $sms = $AT->sms();

            $sms->send([
                'to' => $this->formatPhoneNumber($to),
                'message' => $message,
                'from' => 'KWIKBASKET'
            ]);

            $log->write("email template Africa's Talking Sending SMS " . $message . ' to ' . $to);
        } elseif ('twilio' == $this->config->get('config_sms_protocol')) {
            $sid = $this->config->get('config_sms_sender_id');
            $token = $this->config->get('config_sms_token');
            $from = $this->config->get('config_sms_number');

            // Your Account Sid and Auth Token from twilio.com/user/account
            //$sid = "AC75111c89124c19fffb2538524b8701ae";
            //$token = "e4231d69832c9c7c65ecc78512d9ec1c";
            //$sid = "ACe596b1c5068a7076d1a05552a66503f3";
            //$token = "a15911012556c6795359cba517bb7328";

            $log->write('sms twilio 2');
            $log->write($to);
            if ('+' != substr($to, 0, 1)) {
                $to = '+' . $to;
            }
            //$log->write($to);
            $client = new Client($sid, $token);

            try {
                $sms = $client->messages->create(
                        $to, [
                    'from' => $from,
                    //'from' => '+19789864215',
                    'body' => $message,
                        ]
                );
            } catch (Exception $exception) {
                return $result;
            }
        } elseif ('zenvia' == $this->config->get('config_sms_protocol')) {
            $from = $this->config->get('config_zenvia_sms_sender_id');
            $authToken = $this->config->get('config_zenvia_sms_token');
            $apiEndPoint = $this->config->get('config_zenvia_sms_number');

            $log->write('zenvia sms  2ss');

            $postData = [
                'sendSmsRequest' => [
                    'from' => $from,
                    'to' => $to,
                    'msg' => $message,
                    'callbackOption' => 'NONE',
                    'id' => uniqid(),
                    'aggregateId' => '1111',
                ],
            ];

            $log->write($postData);
            //c3VwZXIub25saW5lLndlYjpydFdjSVVZUENO

            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, $apiEndPoint);
            //curl_setopt($curl, CURLOPT_URL,"https://api-rest.zenvia360.com.br/services/send-sms");
            //https://api-rest.zenvia360.com.br/services/send-sms
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Accept: application/json', 'Content-type: application/json', 'Authorization: Basic ' . $authToken]);

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postData));

            $response = curl_exec($curl);

            $log->write($response);

            $response = json_decode($response, true);

            if (isset($response['sendSmsResponse'])) {
                if (is_array($response['sendSmsResponse'])) {
                    if (00 == $response['sendSmsResponse']['statusCode']) {
                        
                    } else {
                        return false;
                    }
                }
            }
            curl_close($curl);
        } elseif ('uwaziimobile' == $this->config->get('config_sms_protocol')) {
            $curl = curl_init();
            //$authToken = 'VlNMVEQ6VlNMVEQxMjM0NQ==';

            $from = $this->config->get('config_uwaziimobile_sms_number');
            //$from = 'cer';
            $username = $this->config->get('config_uwaziimobile_sms_token');
            $password = $this->config->get('config_uwaziimobile_sms_sender_id');

            //echo "<pre>";print_r($from."c".$username."d".$password);die;
            $str = $username . ':' . $password;

            $authToken = base64_encode($str);

            $apiEndPoint = 'http://107.20.199.106/restapi/sms/1/text/single';
            $postData = [
                'from' => $from,
                'to' => $to,
                'text' => $message,];

            curl_setopt($curl, CURLOPT_URL, $apiEndPoint);
            //curl_setopt($curl, CURLOPT_URL,"https://api-rest.zenvia360.com.br/services/send-sms");
            //https://api-rest.zenvia360.com.br/services/send-sms
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Accept: application/json', 'Content-type: application/json', 'Authorization: Basic ' . $authToken]);

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postData));

            try {
                //$response = $request->send();
                $response = curl_exec($curl);

                $response = json_decode($response, true);

                //echo "<pre>";print_r($response);die;
                if (isset($response['messages']) && isset($response['messages'][0]['status']) && 0 == $response['messages'][0]['status']['id']) {
                    $result['status'] = true;
                } else {
                    $result['status'] = false;
                    $result['message'] = $response['messages'][0]['status']['description'];
                }
            } catch (HttpException $ex) {
                // echo $ex;

                $result['status'] = false;
            }

            return $result;
        } else {
            //wayhub
            // $sender_id = 'KRAFTY';
            // $username  = 'krafty';
            // $password  = 'krafty@123';

            $sender_id = $this->config->get('config_wayhub_sms_sender_id');
            $username = $this->config->get('config_wayhub_sms_token');
            $password = $this->config->get('config_wayhub_sms_number');

            $msg = $message;

            $url = 'http://login.smsgatewayhub.com/smsapi/pushsms.aspx?user=' . $username . '&pwd=' . $password . '&to=' . $to . '&sid=' . $sender_id . '&msg=' . urlencode($msg) . '&fl=0&gwid=2';

            // Get cURL resource
            $curl = curl_init();
            // Set some options - we are passing in a useragent too here
            curl_setopt_array($curl, [
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $url,
                CURLOPT_USERAGENT => 'Codular Sample cURL Request',
            ]);
            // Send the request & save response to $resp
            $resp = curl_exec($curl);

            // Close request to clear up some resources
            curl_close($curl);
        }

        return true;
        //}
    }

    public function formatPhoneNumber($phoneNumber) {
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        try {
            $numberPrototype = $phoneUtil->parse($phoneNumber, 'KE');

            return $phoneUtil->format($numberPrototype, \libphonenumber\PhoneNumberFormat::E164);
        } catch (\libphonenumber\NumberParseException $e) {
            var_dump($e);
        }
    }

    public function sendPushNotification($to, $deviceId, $order_id, $store_id, $message, $title, $app_action = 'com.instagolocal.showorder', $transaction = '') {
        try {
            $log = new Log('error.log');
            $log->write('sendPushNotification');

            $log->write('TO :' . $to);
            $log->write('DEVICE:' . $deviceId);
            $log->write('ORDER ID :' . $order_id);
            $log->write('STORE ID :' . $store_id);
            $log->write('MESSAGE:' . $message);
            $log->write('TITLE:' . $title);
            $log->write('ACTION:' . $app_action);
            $log->write('transaction:' . $transaction);
            
            if (isset($to)) {
                if (isset($deviceId) && isset($to)) {
                    $log->write('api key');

                    $apiKey = $this->config->get('config_seller_api_key');
                    //$apiKey = 'AIzaSyAK3VgDt_MTGRaTMfs_9v_YdsK6tjFRsoo';

                    $log->write($apiKey);
                    $client = new FCMClient();
                    $client->setApiKey($apiKey);
                    $client->injectHttpClient(new \GuzzleHttp\Client());
                    $log->write('FCM INJECT');
                    $note = new Notification($title, $message);
                    $note->setIcon('notification_icon_resource_name')
                            ->setColor('#3ca826')
                            ->setSound('default')
                            ->setClickAction($app_action)
                            ->setBadge(1);

                    $message = new Message();
                    //$message->addRecipient(new Device('dLz1Z9CHl_g:APA91bGTZlzoAF-5JGsqHgA9y4N9Odz0h3Dg19dOrE0Sulrnixz-QzMUaasxSljrmncmZGoUAZ0Q-VJytOFsQYfhHfoUJKOCqb4SCnc9z_0sqmwu4fNqmZ_yuIg6vkp19ChJGb1ibuht'));
                    $message->addRecipient(new Device($deviceId));

                    //$dataSend = array('order_id' => $order_id,'store_id' => $store_id,"body" => $message,"title" => $title,"click_action"=>$app_action);
                    //$log->write($dataSend);
                    $message->setNotification($note)
                            //$message->setData( $dataSend );
                            ->setData(['order_id' => $order_id, 'store_id' => $store_id, 'transaction' => $transaction]);

                    $response = $client->send($message);

                    $log->write('FCM');
                    $log->write($response);
                    $log->write('FCM');
                    //var_dump($response);die;
                    if ($response->getStatusCode()) {
                        $json['success'] = 'Success: push notification sent.';
                    } else {
                        $json['error'] = 'fcm api failed ';
                    }
                } else {
                    $json['error'] = 'device id empty of user';
                }
            } else {
                $json['error'] = 'no user_id';
            }

            return true;
        } catch (Exception $e) {
            $log = new Log('error.log');
            $log->write('sendPushNotification Log');
            $log->write($e);
            $log->write('sendPushNotification Log');
        }
    }

    public function sendReturnPushNotification($to, $deviceId, $return_id, $store_id, $message, $title, $app_action = 'com.instagolocal.showorder') {
        $log = new Log('error.log');
        $log->write('sendPushNotification');

        $log->write($to);
        $log->write($deviceId);
        $log->write($return_id);
        $log->write($message);
        $log->write($title);

        if (isset($to)) {
            if (isset($deviceId) && isset($to)) {
                $log->write('api key');

                $apiKey = $this->config->get('config_seller_api_key');
                //$apiKey = 'AIzaSyAK3VgDt_MTGRaTMfs_9v_YdsK6tjFRsoo';

                $log->write($apiKey);
                $client = new FCMClient();
                $client->setApiKey($apiKey);
                $client->injectHttpClient(new \GuzzleHttp\Client());

                $note = new Notification($title, $message);
                $note->setIcon('notification_icon_resource_name')
                        ->setColor('#3ca826')
                        ->setSound('default')
                        //->setClickAction($app_action)
                        ->setBadge(1);

                $message = new Message();
                //$message->addRecipient(new Device('dLz1Z9CHl_g:APA91bGTZlzoAF-5JGsqHgA9y4N9Odz0h3Dg19dOrE0Sulrnixz-QzMUaasxSljrmncmZGoUAZ0Q-VJytOFsQYfhHfoUJKOCqb4SCnc9z_0sqmwu4fNqmZ_yuIg6vkp19ChJGb1ibuht'));
                $message->addRecipient(new Device($deviceId));

                //$log->write($dataSend);
                $message->setNotification($note)
                        //$message->setData( $dataSend );
                        ->setData(['return_id' => $return_id, 'store_id' => $store_id]);

                $response = $client->send($message);

                $log->write($response);
                //var_dump($response);die;
                if ($response->getStatusCode()) {
                    $json['success'] = 'Success: push notification sent.';
                } else {
                    $json['error'] = 'fcm api failed ';
                }
            } else {
                $json['error'] = 'device id empty of user';
            }
        } else {
            $json['error'] = 'no user_id';
        }

        return true;
    }

    public function sendCustomerReturnPushNotification($to, $deviceId, $return_id, $store_id, $message, $title, $app_action = 'com.instagolocal.showreturn') {
        $log = new Log('error.log');
        $log->write('sendPushNotification');

        $log->write($to);
        $log->write($deviceId);
        $log->write($return_id);
        $log->write($message);
        $log->write($title);

        if (isset($to)) {
            if (isset($deviceId) && isset($to)) {
                $log->write('api key');

                $apiKey = $this->config->get('config_seller_api_key');
                //$apiKey = 'AIzaSyAK3VgDt_MTGRaTMfs_9v_YdsK6tjFRsoo';

                $log->write($apiKey);
                $client = new FCMClient();
                $client->setApiKey($apiKey);
                $client->injectHttpClient(new \GuzzleHttp\Client());

                $note = new Notification($title, $message);
                $note->setIcon('notification_icon_resource_name')
                        ->setColor('#3ca826')
                        ->setSound('default')
                        ->setClickAction($app_action)
                        ->setBadge(1);

                $message = new Message();
                //$message->addRecipient(new Device('dLz1Z9CHl_g:APA91bGTZlzoAF-5JGsqHgA9y4N9Odz0h3Dg19dOrE0Sulrnixz-QzMUaasxSljrmncmZGoUAZ0Q-VJytOFsQYfhHfoUJKOCqb4SCnc9z_0sqmwu4fNqmZ_yuIg6vkp19ChJGb1ibuht'));
                $message->addRecipient(new Device($deviceId));

                //$log->write($dataSend);
                $message->setNotification($note)
                        //$message->setData( $dataSend );
                        ->setData(['return_id' => $return_id, 'store_id' => $store_id]);

                $response = $client->send($message);

                $log->write($response);
                //var_dump($response);die;
                if ($response->getStatusCode()) {
                    $json['success'] = 'Success: push notification sent.';
                } else {
                    $json['error'] = 'fcm api failed ';
                }
            } else {
                $json['error'] = 'device id empty of user';
            }
        } else {
            $json['error'] = 'no user_id';
        }

        return true;
    }

    public function sendVendorPushNotification($to, $deviceId, $wallet_id, $store_id, $message, $title, $args) {
        $log = new Log('error.log');
        $log->write('sendVendorPushNotification');

        $log->write($to);
        $log->write($deviceId);
        $log->write($wallet_id);
        $log->write($message);
        $log->write($title);

        if (isset($to)) {
            if (isset($deviceId) && isset($to)) {
                $log->write('api key');

                $apiKey = $this->config->get('config_seller_api_key');
                //$apiKey = 'AIzaSyAK3VgDt_MTGRaTMfs_9v_YdsK6tjFRsoo';
                $log->write($apiKey);
                $client = new FCMClient();
                $client->setApiKey($apiKey);
                $client->injectHttpClient(new \GuzzleHttp\Client());

                $note = new Notification($title, $message);
                $note->setIcon('notification_icon_resource_name')
                        ->setColor('#3ca826')
                        ->setSound('notification_sound')
                        ->setBadge(1);

                $message = new Message();
                //$message->addRecipient(new Device('dLz1Z9CHl_g:APA91bGTZlzoAF-5JGsqHgA9y4N9Odz0h3Dg19dOrE0Sulrnixz-QzMUaasxSljrmncmZGoUAZ0Q-VJytOFsQYfhHfoUJKOCqb4SCnc9z_0sqmwu4fNqmZ_yuIg6vkp19ChJGb1ibuht'));
                $message->addRecipient(new Device($deviceId));
                $message->setNotification($note)
                        ->setData(['wallet_id' => $wallet_id, 'store_id' => $store_id, 'notification_id' => $args['notification_id']]);

                $response = $client->send($message);
                //var_dump($response);die;
                if ($response->getStatusCode()) {
                    $json['success'] = 'Success: push notification sent.';
                } else {
                    $json['error'] = 'fcm api failed ';
                }
            } else {
                $json['error'] = 'device id empty of user';
            }
        } else {
            $json['error'] = 'no user_id';
        }

        return true;
    }

    public function sendOrderVendorPushNotification($to, $deviceId, $order_id, $store_id, $message, $title, $args) {
        $log = new Log('error.log');
        $log->write('sendVendorPushNotification');

        $log->write($to);
        $log->write($deviceId);
        $log->write($order_id);
        $log->write($message);
        $log->write($title);

        if (isset($to)) {
            if (isset($deviceId) && isset($to)) {
                $log->write('api key');

                $apiKey = $this->config->get('config_seller_api_key');
                //$apiKey = 'AIzaSyAK3VgDt_MTGRaTMfs_9v_YdsK6tjFRsoo';
                $log->write($apiKey);
                $client = new FCMClient();
                $client->setApiKey($apiKey);
                $client->injectHttpClient(new \GuzzleHttp\Client());

                $note = new Notification($title, $message);
                $note->setIcon('notification_icon_resource_name')
                        ->setColor('#3ca826')
                        ->setSound('notification_sound')
                        ->setBadge(1);

                $message = new Message();
                //$message->addRecipient(new Device('dLz1Z9CHl_g:APA91bGTZlzoAF-5JGsqHgA9y4N9Odz0h3Dg19dOrE0Sulrnixz-QzMUaasxSljrmncmZGoUAZ0Q-VJytOFsQYfhHfoUJKOCqb4SCnc9z_0sqmwu4fNqmZ_yuIg6vkp19ChJGb1ibuht'));
                $message->addRecipient(new Device($deviceId));
                $message->setNotification($note)
                        ->setData(['order_id' => $order_id, 'store_id' => $store_id, 'notification_id' => $args['notification_id']]);

                $response = $client->send($message);
                //var_dump($response);die;
                if ($response->getStatusCode()) {
                    $json['success'] = 'Success: push notification sent.';
                } else {
                    $json['error'] = 'fcm api failed ';
                }
            } else {
                $json['error'] = 'device id empty of user';
            }
        } else {
            $json['error'] = 'no user_id';
        }

        return true;
    }

    public function getRealOrderProducts($order_id, $store_id = 0) {
        $sql = 'SELECT * FROM ' . DB_PREFIX . "real_order_product WHERE order_id = '" . (int) $order_id . "'";

        if ($store_id) {
            $sql .= " AND store_id='" . $store_id . "'";
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getStore($store_id) {
        return $this->db->query('select * from ' . DB_PREFIX . 'store WHERE store_id="' . $store_id . '"')->row;
    }

    public function getCustomer($customer_id) {
        return $this->db->query('select * from ' . DB_PREFIX . 'customer WHERE customer_id="' . $customer_id . '"')->row;
    }

    public function resize($filename, $width, $height) {
        if (!is_file(DIR_IMAGE . $filename)) {
            return;
        }

        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        $old_image = $filename;
        $new_image = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . $width . 'x' . $height . '.' . $extension;

        if (!is_file(DIR_IMAGE . $new_image) || (filectime(DIR_IMAGE . $old_image) > filectime(DIR_IMAGE . $new_image))) {
            $path = '';

            $directories = explode('/', dirname(str_replace('../', '', $new_image)));

            foreach ($directories as $directory) {
                $path = $path . '/' . $directory;

                if (!is_dir(DIR_IMAGE . $path)) {
                    $this->filesystem->mkdir(DIR_IMAGE . $path);
                }
            }

            list($width_orig, $height_orig) = getimagesize(DIR_IMAGE . $old_image);

            if ($width_orig != $width || $height_orig != $height) {
                $image = new Image(DIR_IMAGE . $old_image);
                $image->resize($width, $height);
                $image->save(DIR_IMAGE . $new_image);
            } else {
                copy(DIR_IMAGE . $old_image, DIR_IMAGE . $new_image);
            }
        }

        if (isset($this->request->server['HTTPS']) && $this->request->server['HTTPS']) {
            return HTTPS_CATALOG . 'image/' . $new_image;
        } else {
            return HTTPS_CATALOG . 'image/' . $new_image;
        }
    }

    public function maskingOrderDetailUrl($link) {
        $parts = parse_url($link);
        parse_str($parts['query'], $query);
        $orderId = $query['order_id'];
        $decodedOrderId = base64_encode('     ' . $query['order_id'] . '     ');
        $maskedhref = $this->url->link('account/order/info', 'order_id=' . $decodedOrderId);

        return $maskedhref;
    }

    public function getConsolidatedOrderSheetFind() {
        $result = ['{deliverydate}', '{site_url}', '{logo}', '{system_name}', '{year}', '{help_center}', '{white_logo}', '{terms}', '{privacy_policy}', '{system_email}', '{system_phone}'];
        return $result;
    }

    public function getConsolidatedOrderSheetReplace($data) {
        $result = [
            'deliverydate' => $data['deliverydate'],
            'site_url' => HTTPS_CATALOG,
            //'logo'=> HTTPS_CATALOG.'image/' . $this->config->get('config_logo'),
            'logo' => $this->resize($this->config->get('config_logo'), 197, 34),
            //'site_url'=>$this->config->get('config_url'),
            'system_name' => $this->config->get('config_name'),
            'year' => date('Y'),
            'help_center' => $this->url->adminLink('information/help'),
            //'white_logo'=> HTTPS_CATALOG.'image/'. $this->config->get('config_white_logo'),
            'white_logo' => $this->resize($this->config->get('config_white_logo'), 197, 34),
            'terms' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_account_id'), 'SSL'),
            'privacy_policy' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_privacy_policy_id'), 'SSL'),
            'system_email' => $this->config->get('config_email'),
            'system_phone' => '+' . $this->config->get('config_telephone_code') . ' ' . $this->config->get('config_telephone'),
        ];

        return $result;
    }

    public function getNewDeviceLoginFind() {
        $result = ['{username}', '{otp}', '{site_url}', '{logo}', '{system_name}', '{year}', '{help_center}', '{white_logo}', '{terms}', '{privacy_policy}', '{system_email}', '{system_phone}'];

        return $result;
    }

    public function getNewDeviceLoginReplace($data) {
        $result = [
            'username' => $data['username'],
            'otp' => $data['otp'],
            //common replace
            'site_url' => HTTPS_CATALOG,
            //'logo'=> HTTPS_CATALOG.'image/' . $this->config->get('config_logo'),
            'logo' => $this->resize($this->config->get('config_logo'), 197, 34),
            //'site_url'=>$this->config->get('config_url'),
            'system_name' => $this->config->get('config_name'),
            'year' => date('Y'),
            'help_center' => $this->url->adminLink('information/help'),
            //'white_logo'=> HTTPS_CATALOG.'image/'. $this->config->get('config_white_logo'),
            'white_logo' => $this->resize($this->config->get('config_white_logo'), 197, 34),
            'terms' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_account_id'), 'SSL'),
            'privacy_policy' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_privacy_policy_id'), 'SSL'),
            'system_email' => $this->config->get('config_email'),
            'system_phone' => '+' . $this->config->get('config_telephone_code') . ' ' . $this->config->get('config_telephone'),
        ];

        return $result;
    }

    public function getStockOutFind() {
        $result = ['{fromdate}', '{todate}', '{site_url}', '{logo}', '{system_name}', '{year}', '{help_center}', '{white_logo}', '{terms}', '{privacy_policy}', '{system_email}', '{system_phone}'];
        return $result;
    }

    public function getStockOutReplace($data) {
        $result = [
            'fromdate' => $data['fromdate'],
            'todate' => $data['todate'],
            'site_url' => HTTPS_CATALOG,
            //'logo'=> HTTPS_CATALOG.'image/' . $this->config->get('config_logo'),
            'logo' => $this->resize($this->config->get('config_logo'), 197, 34),
            //'site_url'=>$this->config->get('config_url'),
            'system_name' => $this->config->get('config_name'),
            'year' => date('Y'),
            'help_center' => $this->url->adminLink('information/help'),
            //'white_logo'=> HTTPS_CATALOG.'image/'. $this->config->get('config_white_logo'),
            'white_logo' => $this->resize($this->config->get('config_white_logo'), 197, 34),
            'terms' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_account_id'), 'SSL'),
            'privacy_policy' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_privacy_policy_id'), 'SSL'),
            'system_email' => $this->config->get('config_email'),
            'system_phone' => '+' . $this->config->get('config_telephone_code') . ' ' . $this->config->get('config_telephone'),
        ];

        return $result;
    }

    public function getFeedbackFind() {
        $result = ['{customer_name}', '{email}', '{mobile}', '{feedback_type}', '{description}', '{issue_status}', '{site_url}', '{logo}', '{system_name}', '{year}', '{help_center}', '{white_logo}', '{terms}', '{privacy_policy}', '{system_email}', '{system_phone}'];
        return $result;
    }

    public function getFeedbackReplace($data) {
        $result = [
            'customer_name' => $data['customer_name'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'feedback_type' => $data['feedback_type'],
            'description' => $data['description'],
            'issue_status' => $data['issue_status'],
            'site_url' => HTTPS_CATALOG,
            //'logo'=> HTTPS_CATALOG.'image/' . $this->config->get('config_logo'),
            'logo' => $this->resize($this->config->get('config_logo'), 197, 34),
            //'site_url'=>$this->config->get('config_url'),
            'system_name' => $this->config->get('config_name'),
            'year' => date('Y'),
            'help_center' => $this->url->adminLink('information/help'),
            //'white_logo'=> HTTPS_CATALOG.'image/'. $this->config->get('config_white_logo'),
            'white_logo' => $this->resize($this->config->get('config_white_logo'), 197, 34),
            'terms' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_account_id'), 'SSL'),
            'privacy_policy' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_privacy_policy_id'), 'SSL'),
            'system_email' => $this->config->get('config_email'),
            'system_phone' => '+' . $this->config->get('config_telephone_code') . ' ' . $this->config->get('config_telephone'),
        ];

        return $result;
    }

    public function getCustomerstatementFind() {
        $result = ['{customer_name}', '{email}', '{start_date}', '{end_date}', '{site_url}', '{logo}', '{system_name}', '{year}', '{help_center}', '{white_logo}', '{terms}', '{privacy_policy}', '{system_email}', '{system_phone}'];
        return $result;
    }

    public function getCustomerstatementReplace($data) {
        $result = [
            'customer_name' => $data['customer_name'],
            'email' => $data['email'],
            // 'mobile' => $data['mobile'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'site_url' => HTTPS_CATALOG,
            //'logo'=> HTTPS_CATALOG.'image/' . $this->config->get('config_logo'),
            'logo' => $this->resize($this->config->get('config_logo'), 197, 34),
            //'site_url'=>$this->config->get('config_url'),
            'system_name' => $this->config->get('config_name'),
            'year' => date('Y'),
            'help_center' => $this->url->adminLink('information/help'),
            //'white_logo'=> HTTPS_CATALOG.'image/'. $this->config->get('config_white_logo'),
            'white_logo' => $this->resize($this->config->get('config_white_logo'), 197, 34),
            'terms' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_account_id'), 'SSL'),
            'privacy_policy' => $this->url->adminLink('information/information', 'information_id=' . $this->config->get('config_privacy_policy_id'), 'SSL'),
            'system_email' => $this->config->get('config_email'),
            'system_phone' => '+' . $this->config->get('config_telephone_code') . ' ' . $this->config->get('config_telephone'),
        ];

        return $result;
    }

    public function SendSNS($mobile) {
        $sdk = new Aws\Sns\SnsClient([
            'region' => 'af-south-1',
            'version' => 'latest',
            'credentials' => ['key' => 'AKIAUWRTJZVBPUAIMRKY', 'secret' => 'Qu8Pc7Vj5X74VIdwR+OuQVphnt0MsO/hsyahftaO']
        ]);

        $result = $sdk->publish([
            'Message' => 'Hello sri, Your Kwik Basket signup verification code is 9831. Enter code to complete registration.',
            'PhoneNumber' => $mobile,
            'MessageAttributes' => ['AWS.SNS.SMS.SenderID' => [
                    'DataType' => 'String',
                    'StringValue' => 'KWIKBASKET'
                ]
        ]]);

        print_r($result);
    }

}
