<?php

require_once DIR_SYSTEM . '/vendor/autoload.php'; // Loads the library

use Twilio\Rest\Client;

require DIR_SYSTEM . '/vendor/zenvia/human_gateway_client_api/HumanClientMain.php';

class ControllerSettingSetting extends Controller {

    private $error = [];

    public function index() {
        //echo "<pre>";print_r(phpinfo());die;
        //echo "<pre>";print_r(date('d M Y h:i A', strtotime(date("Y-m-d H:i:s"))));die;
        //echo "<pre>";print_r('https://maps.google.com/maps/api/js?key='.$this->config->get('config_google_api_key').'&sensor=false&libraries=places');die;
        $this->load->language('setting/setting');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validate()) {
            $config_mail = $this->request->post['config_mail'];

            //zecho "<pre>";print_r($this->request->post);die;
            $config_mail['sendmail_path'] = !isset($config_mail['sendmail_path']) ? '/usr/sbin/sendmail -bs' : $config_mail['sendmail_path'];
            $config_mail['smtp_hostname'] = !isset($config_mail['smtp_hostname']) ? '' : $config_mail['smtp_hostname'];
            $config_mail['smtp_username'] = !isset($config_mail['smtp_username']) ? '' : $config_mail['smtp_username'];
            $config_mail['smtp_password'] = !isset($config_mail['smtp_password']) ? '' : $config_mail['smtp_password'];
            $config_mail['smtp_port'] = !isset($config_mail['smtp_port']) ? '25' : $config_mail['smtp_port'];
            $config_mail['smtp_encryption'] = !isset($config_mail['smtp_encryption']) ? '' : $config_mail['smtp_encryption'];

            $config_mail['aws_region'] = !isset($config_mail['aws_region']) ? '' : $config_mail['aws_region'];
            $config_mail['aws_access_id'] = !isset($config_mail['aws_access_id']) ? '' : $config_mail['aws_access_id'];
            $config_mail['aws_secret_key'] = !isset($config_mail['aws_secret_key']) ? '' : $config_mail['aws_secret_key'];

            $config_mail['mailgun'] = !isset($config_mail['mailgun']) ? '' : $config_mail['mailgun'];

            $this->request->post['config_mail'] = $config_mail;

            if ($this->request->post['config_pagecache_exclude']) {
                $ex_paths = '';

                foreach (explode("\n", $this->request->post['config_pagecache_exclude']) as $id) {
                    $id = trim($id);

                    if ($id) {
                        $ex_paths .= $id . ',';
                    }
                }

                $this->request->post['config_pagecache_exclude'] = trim($ex_paths, ',');
            }

            //echo "<pre>";print_r($this->request->post);die;
            $this->model_setting_setting->editSetting('config', $this->request->post);

            if ($this->config->get('config_currency_auto')) {
                $this->load->model('localisation/currency');

                $this->model_localisation_currency->refresh();
            }

            $this->session->data['success'] = $this->language->get('text_success');

            if (isset($this->request->post['button']) and 'save' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('setting/setting', 'token=' . $this->session->data['token'], 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('setting/store/add', 'token=' . $this->session->data['token'], 'SSL'));
            }

            $this->response->redirect($this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $data = $this->language->all();
        // leaving the followings for extension B/C purpose
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_google_captcha'] = $this->language->get('text_google_captcha');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_delivery_status'] = $this->language->get('entry_delivery_status');

        $data['entry_checkout_delivery_status'] = $this->language->get('entry_checkout_delivery_status');

        $data['entry_bulk_mailer_status'] = $this->language->get('entry_bulk_mailer_status');
        $data['entry_konduto_status'] = $this->language->get('entry_konduto_status');
        $data['entry_google_recaptch_status'] = $this->language->get('entry_google_recaptch_status');
        $data['entry_google_analytics_status'] = $this->language->get('entry_google_analytics_status');
        $data['text_footer'] = $this->language->get('text_footer');
        $data['entry_footer_video_link'] = $this->language->get('entry_footer_video_link');

        $data['help_geocode'] = $this->language->get('help_geocode');
        $data['help_google_captcha'] = $this->language->get('help_google_captcha');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['entry_footer_image'] = $this->language->get('entry_footer_image');

        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_google'] = $this->language->get('tab_google');
        $data['entry_konduto_order_status'] = $this->language->get('entry_konduto_order_status');
        $data['text_footer_text'] = $this->language->get('text_footer_text');
        $data['text_footer_video'] = $this->language->get('text_footer_video');

        $data['tzlist'] = DateTimeZone::listIdentifiers(DateTimeZone::ALL);

        //echo "<pre>";print_r($data['tzlist']);die;
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }


        if (isset($this->error['store_latitude'])) {
            $data['error_store_latitude'] = $this->error['store_latitude'];
        } else {
            $data['error_store_latitude'] = '';
        }


        if (isset($this->error['store_longitude'])) {
            $data['error_store_longitude'] = $this->error['store_longitude'];
        } else {
            $data['error_store_longitude'] = '';
        }
        if (isset($this->error['amitruck_url'])) {
            $data['error_amitruck_url'] = $this->error['amitruck_url'];
        } else {
            $data['error_amitruck_url'] = '';
        }


        if (isset($this->error['amitruck_clientId'])) {
            $data['error_amitruck_clientId'] = $this->error['amitruck_clientId'];
        } else {
            $data['error_amitruck_clientId'] = '';
        }

        if (isset($this->error['amitruck_clientSecret'])) {
            $data['error_amitruck_clientSecret'] = $this->error['amitruck_clientSecret'];
        } else {
            $data['error_amitruck_clientSecret'] = '';
        }




        if (isset($this->error['shopper_group_ids'])) {
            $data['error_shopper_group_ids'] = $this->error['shopper_group_ids'];
        } else {
            $data['error_shopper_group_ids'] = '';
        }

        if (isset($this->error['vendor_group_ids'])) {
            $data['error_vendor_group_ids'] = $this->error['vendor_group_ids'];
        } else {
            $data['error_vendor_group_ids'] = '';
        }

        if (isset($this->error['account_manager_group_ids'])) {
            $data['error_account_manager_group_id'] = $this->error['account_manager_group_id'];
        } else {
            $data['error_account_manager_group_id'] = '';
        }


        if (isset($this->error['customer_experience_group_ids'])) {
            $data['error_customer_experience_group_id'] = $this->error['customer_experience_group_id'];
        } else {
            $data['error_customer_experience_group_id'] = '';
        }



        if (isset($this->error['farmer_group_ids'])) {
            $data['error_farmer_group_id'] = $this->error['farmer_group_id'];
        } else {
            $data['error_farmer_group_id'] = '';
        }

        if (isset($this->error['supplier_group_ids'])) {
            $data['error_supplier_group_id'] = $this->error['supplier_group_id'];
        } else {
            $data['error_supplier_group_id'] = '';
        }

        if (isset($this->error['active_store_id'])) {
            $data['error_active_store_id'] = $this->error['active_store_id'];
        } else {
            $data['error_active_store_id'] = '';
        }

        if (isset($this->error['active_store_minimum_order_amount'])) {
            $data['error_active_store_minimum_order_amount'] = $this->error['active_store_minimum_order_amount'];
        } else {
            $data['error_active_store_minimum_order_amount'] = '';
        }

        if (isset($this->error['owner'])) {
            $data['error_owner'] = $this->error['owner'];
        } else {
            $data['error_owner'] = '';
        }

        if (isset($this->error['address'])) {
            $data['error_address'] = $this->error['address'];
        } else {
            $data['error_address'] = '';
        }

        if (isset($this->error['email'])) {
            $data['error_email'] = $this->error['email'];
        } else {
            $data['error_email'] = '';
        }

        if (isset($this->error['from_email'])) {
            $data['error_from_email'] = $this->error['from_email'];
        } else {
            $data['error_from_email'] = '';
        }

        if (isset($this->error['telephone'])) {
            $data['error_telephone'] = $this->error['telephone'];
        } else {
            $data['error_telephone'] = '';
        }

        if (isset($this->error['meta_title'])) {
            $data['error_meta_title'] = $this->error['meta_title'];
        } else {
            $data['error_meta_title'] = '';
        }

        if (isset($this->error['city'])) {
            $data['error_city'] = $this->error['city'];
        } else {
            $data['error_city'] = '';
        }

        if (isset($this->error['customer_group_display'])) {
            $data['error_customer_group_display'] = $this->error['customer_group_display'];
        } else {
            $data['error_customer_group_display'] = '';
        }

        if (isset($this->error['login_attempts'])) {
            $data['error_login_attempts'] = $this->error['login_attempts'];
        } else {
            $data['error_login_attempts'] = '';
        }

        if (isset($this->error['voucher_min'])) {
            $data['error_voucher_min'] = $this->error['voucher_min'];
        } else {
            $data['error_voucher_min'] = '';
        }

        if (isset($this->error['voucher_max'])) {
            $data['error_voucher_max'] = $this->error['voucher_max'];
        } else {
            $data['error_voucher_max'] = '';
        }

        if (isset($this->error['shipped_status'])) {
            $data['error_shipped_status'] = $this->error['shipped_status'];
        } else {
            $data['error_shipped_status'] = '';
        }

        if (isset($this->error['processing_status'])) {
            $data['error_processing_status'] = $this->error['processing_status'];
        } else {
            $data['error_processing_status'] = '';
        }

        if (isset($this->error['refund_status'])) {
            $data['error_refund_status'] = $this->error['refund_status'];
        } else {
            $data['error_refund_status'] = '';
        }

        if (isset($this->error['delivery_status'])) {
            $data['error_ready_for_pickup_status'] = $this->error['delivery_status'];
        } else {
            $data['error_ready_for_pickup_status'] = '';
        }

        if (isset($this->error['complete_status'])) {
            $data['error_complete_status'] = $this->error['complete_status'];
        } else {
            $data['error_complete_status'] = '';
        }

        if (isset($this->error['image_category'])) {
            $data['error_image_category'] = $this->error['image_category'];
        } else {
            $data['error_image_category'] = '';
        }

        if (isset($this->error['image_thumb'])) {
            $data['error_image_thumb'] = $this->error['image_thumb'];
        } else {
            $data['error_image_thumb'] = '';
        }

        if (isset($this->error['zoomimage_thumb'])) {
            $data['error_zoomimage_thumb'] = $this->error['zoomimage_thumb'];
        } else {
            $data['error_zoomimage_thumb'] = '';
        }

        if (isset($this->error['image_popup'])) {
            $data['error_image_popup'] = $this->error['image_popup'];
        } else {
            $data['error_image_popup'] = '';
        }

        if (isset($this->error['image_product'])) {
            $data['error_image_product'] = $this->error['image_product'];
        } else {
            $data['error_image_product'] = '';
        }

        if (isset($this->error['image_location'])) {
            $data['error_image_location'] = $this->error['image_location'];
        } else {
            $data['error_image_location'] = '';
        }

        if (isset($this->error['image_app_category'])) {
            $data['error_app_image_category'] = $this->error['image_app_category'];
        } else {
            $data['error_app_image_category'] = '';
        }

        if (isset($this->error['image_app_thumb'])) {
            $data['error_app_image_thumb'] = $this->error['image_app_thumb'];
        } else {
            $data['error_app_image_thumb'] = '';
        }

        if (isset($this->error['image_app_popup'])) {
            $data['error_app_image_popup'] = $this->error['image_app_popup'];
        } else {
            $data['error_app_image_popup'] = '';
        }

        if (isset($this->error['image_app_product'])) {
            $data['error_app_image_product'] = $this->error['image_app_product'];
        } else {
            $data['error_app_image_product'] = '';
        }

        if (isset($this->error['image_app_location'])) {
            $data['error_app_image_location'] = $this->error['image_app_location'];
        } else {
            $data['error_app_image_location'] = '';
        }

        if (isset($this->error['notice_image_app_location'])) {
            $data['error_app_notice_image_location'] = $this->error['notice_image_app_location'];
        } else {
            $data['error_app_notice_image_location'] = '';
        }

        if (isset($this->error['image_app_cart'])) {
            $data['error_app_image_cart'] = $this->error['image_app_cart'];
        } else {
            $data['error_app_image_cart'] = '';
        }

        if (isset($this->error['image_additional'])) {
            $data['error_image_additional'] = $this->error['image_additional'];
        } else {
            $data['error_image_additional'] = '';
        }

        if (isset($this->error['image_related'])) {
            $data['error_image_related'] = $this->error['image_related'];
        } else {
            $data['error_image_related'] = '';
        }

        if (isset($this->error['image_compare'])) {
            $data['error_image_compare'] = $this->error['image_compare'];
        } else {
            $data['error_image_compare'] = '';
        }

        if (isset($this->error['image_wishlist'])) {
            $data['error_image_wishlist'] = $this->error['image_wishlist'];
        } else {
            $data['error_image_wishlist'] = '';
        }

        if (isset($this->error['image_cart'])) {
            $data['error_image_cart'] = $this->error['image_cart'];
        } else {
            $data['error_image_cart'] = '';
        }

        if (isset($this->error['error_filename'])) {
            $data['error_error_filename'] = $this->error['error_filename'];
        } else {
            $data['error_error_filename'] = '';
        }

        if (isset($this->error['product_limit'])) {
            $data['error_product_limit'] = $this->error['product_limit'];
        } else {
            $data['error_product_limit'] = '';
        }

        if (isset($this->error['payment_methods'])) {
            $data['error_payment_methods'] = $this->error['payment_methods'];
        } else {
            $data['error_payment_methods'] = '';
        }

        if (isset($this->error['product_app_limit'])) {
            $data['error_app_product_limit'] = $this->error['product_app_limit'];
        } else {
            $data['error_app_product_limit'] = '';
        }

        if (isset($this->error['member_account_fee'])) {
            $data['error_member_account_fee'] = $this->error['member_account_fee'];
        } else {
            $data['error_member_account_fee'] = '';
        }

        if (isset($this->error['product_description_length'])) {
            $data['error_product_description_length'] = $this->error['product_description_length'];
        } else {
            $data['error_product_description_length'] = '';
        }

        if (isset($this->error['limit_admin'])) {
            $data['error_limit_admin'] = $this->error['limit_admin'];
        } else {
            $data['error_limit_admin'] = '';
        }

        if (isset($this->error['encryption'])) {
            $data['error_encryption'] = $this->error['encryption'];
        } else {
            $data['error_encryption'] = '';
        }

        if (isset($this->error['cache_lifetime'])) {
            $data['error_cache_lifetime'] = $this->error['cache_lifetime'];
        } else {
            $data['error_cache_lifetime'] = '';
        }

        $data['test_sms_link'] = $this->url->link('setting/setting/testSms');

        $data['token'] = $this->session->data['token'];

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_stores'),
            'href' => $this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('setting/setting', 'token=' . $this->session->data['token'], 'SSL'),
        ];

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $data['action'] = $this->url->link('setting/setting', 'token=' . $this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL');

        $data['token'] = $this->session->data['token'];

        if (isset($this->request->post['config_footer_text'])) {
            $data['config_footer_text'] = $this->request->post['config_footer_text'];
        } else {
            $data['config_footer_text'] = $this->config->get('config_footer_text');
        }

        if (isset($this->request->post['config_state_code'])) {
            $data['config_state_code'] = $this->request->post['config_state_code'];
        } else {
            $data['config_state_code'] = $this->config->get('config_state_code');
        }

        if (isset($this->request->post['config_country_code'])) {
            $data['config_country_code'] = $this->request->post['config_country_code'];
        } else {
            $data['config_country_code'] = $this->config->get('config_country_code');
        }

        if (isset($this->request->post['config_country'])) {
            $data['config_country'] = $this->request->post['config_country'];
        } else {
            $data['config_country'] = $this->config->get('config_country');
        }

        if (isset($this->request->post['config_state'])) {
            $data['config_state'] = $this->request->post['config_state'];
        } else {
            $data['config_state'] = $this->config->get('config_state');
        }

        if (isset($this->request->post['config_dri_charge'])) {
            $data['config_dri_charge'] = $this->request->post['config_dri_charge'];
        } else {
            $data['config_dri_charge'] = $this->config->get('config_dri_charge');
        }

        if (isset($this->request->post['config_dri_max_order'])) {
            $data['config_dri_max_order'] = $this->request->post['config_dri_max_order'];
        } else {
            $data['config_dri_max_order'] = $this->config->get('config_dri_max_order');
        }

        if (isset($this->request->post['config_dri_auto_assign'])) {
            $data['config_dri_auto_assign'] = $this->request->post['config_dri_auto_assign'];
        } else {
            $data['config_dri_auto_assign'] = $this->config->get('config_dri_auto_assign');
        }

        if (isset($this->request->post['config_dri_cust_sms'])) {
            $data['config_dri_cust_sms'] = $this->request->post['config_dri_cust_sms'];
        } else {
            $data['config_dri_cust_sms'] = $this->config->get('config_dri_cust_sms');
        }

        if (isset($this->request->post['config_dri_wallet_sms'])) {
            $data['config_dri_wallet_sms'] = $this->request->post['config_dri_wallet_sms'];
        } else {
            $data['config_dri_wallet_sms'] = $this->config->get('config_dri_wallet_sms');
        }

        if (isset($this->request->post['config_name'])) {
            $data['config_name'] = $this->request->post['config_name'];
        } else {
            $data['config_name'] = $this->config->get('config_name');
        }

        if (isset($this->request->post['config_shopper_group_ids'])) {
            $data['config_shopper_group_ids'] = $this->request->post['config_shopper_group_ids'];
        } else {
            $data['config_shopper_group_ids'] = $this->config->get('config_shopper_group_ids');
        }

        if (isset($this->request->post['config_vendor_group_ids'])) {
            $data['config_vendor_group_ids'] = $this->request->post['config_vendor_group_ids'];
        } else {
            $data['config_vendor_group_ids'] = $this->config->get('config_vendor_group_ids');
        }


        if (isset($this->request->post['config_store_latitude'])) {
            $data['config_store_latitude'] = $this->request->post['config_store_latitude'];
        } else {
            $data['config_store_latitude'] = $this->config->get('config_store_latitude');
        }

        if (isset($this->request->post['config_store_longitude'])) {
            $data['config_store_longitude'] = $this->request->post['config_store_longitude'];
        } else {
            $data['config_store_longitude'] = $this->config->get('config_store_longitude');
        }

        if (isset($this->request->post['config_amitruck_url'])) {
            $data['config_amitruck_url'] = $this->request->post['config_amitruck_url'];
        } else {
            $data['config_amitruck_url'] = $this->config->get('config_amitruck_url');
        }
        if (isset($this->request->post['config_amitruck_clientId'])) {
            $data['config_amitruck_clientId'] = $this->request->post['config_amitruck_clientId'];
        } else {
            $data['config_amitruck_clientId'] = $this->config->get('config_amitruck_clientId');
        }

        if (isset($this->request->post['config_amitruck_clientSecret'])) {
            $data['config_amitruck_clientSecret'] = $this->request->post['config_amitruck_clientSecret'];
        } else {
            $data['config_amitruck_clientSecret'] = $this->config->get('config_amitruck_clientSecret');
        }

        if (isset($this->request->post['config_account_namager_group_id'])) {
            $data['config_account_manager_group_id'] = $this->request->post['config_account_manager_group_id'];
        } else {
            $data['config_account_manager_group_id'] = $this->config->get('config_account_manager_group_id');
        }

        if (isset($this->request->post['config_customer_experience_group_id'])) {
            $data['config_customer_experience_group_id'] = $this->request->post['config_customer_experience_group_id'];
        } else {
            $data['config_customer_experience_group_id'] = $this->config->get('config_customer_experience_group_id');
        }

        if (isset($this->request->post['config_farmer_group_id'])) {
            $data['config_farmer_group_id'] = $this->request->post['config_farmer_group_id'];
        } else {
            $data['config_farmer_group_id'] = $this->config->get('config_farmer_group_id');
        }

        if (isset($this->request->post['config_supplier_group_id'])) {
            $data['config_supplier_group_id'] = $this->request->post['config_supplier_group_id'];
        } else {
            $data['config_supplier_group_id'] = $this->config->get('config_supplier_group_id');
        }

        if (isset($this->request->post['config_active_store_id'])) {
            $data['config_active_store_id'] = $this->request->post['config_active_store_id'];
        } else {
            $data['config_active_store_id'] = $this->config->get('config_active_store_id');
        }

        if (isset($this->request->post['config_active_store_minimum_order_amount'])) {
            $data['config_active_store_minimum_order_amount'] = $this->request->post['config_active_store_minimum_order_amount'];
        } else {
            $data['config_active_store_minimum_order_amount'] = $this->config->get('config_active_store_minimum_order_amount');
        }

        if (isset($this->request->post['config_owner'])) {
            $data['config_owner'] = $this->request->post['config_owner'];
        } else {
            $data['config_owner'] = $this->config->get('config_owner');
        }

        if (isset($this->request->post['config_aboutus'])) {
            $data['config_aboutus'] = $this->request->post['config_aboutus'];
        } else {
            $data['config_aboutus'] = $this->config->get('config_aboutus');
        }

        if (isset($this->request->post['config_address_check'])) {
            $data['config_address_check'] = $this->request->post['config_address_check'];
        } else {
            $data['config_address_check'] = $this->config->get('config_address_check');
        }

        if (isset($this->request->post['config_information_url'])) {
            $data['config_information_url'] = $this->request->post['config_information_url'];
        } else {
            $data['config_information_url'] = $this->config->get('config_information_url');
        }

        if (isset($this->request->post['config_address'])) {
            $data['config_address'] = $this->request->post['config_address'];
        } else {
            $data['config_address'] = $this->config->get('config_address');
        }

        if (isset($this->request->post['config_fb_secret'])) {
            $data['config_fb_secret'] = $this->request->post['config_fb_secret'];
        } else {
            $data['config_fb_secret'] = $this->config->get('config_fb_secret');
        }

        if (isset($this->request->post['config_fb_app_id'])) {
            $data['config_fb_app_id'] = $this->request->post['config_fb_app_id'];
        } else {
            $data['config_fb_app_id'] = $this->config->get('config_fb_app_id');
        }

        if (isset($this->request->post['config_seller_api_key'])) {
            $data['config_seller_api_key'] = $this->request->post['config_seller_api_key'];
        } else {
            $data['config_seller_api_key'] = $this->config->get('config_seller_api_key');
        }

        if (isset($this->request->post['config_view_map'])) {
            $data['config_view_map'] = $this->request->post['config_view_map'];
        } else {
            $data['config_view_map'] = $this->config->get('config_view_map');
        }

        if (isset($this->request->post['config_update_android'])) {
            $data['config_update_android'] = $this->request->post['config_update_android'];
        } else {
            $data['config_update_android'] = $this->config->get('config_update_android');
        }

        if (isset($this->request->post['config_update_ios'])) {
            $data['config_update_ios'] = $this->request->post['config_update_ios'];
        } else {
            $data['config_update_ios'] = $this->config->get('config_update_ios');
        }

        if (isset($this->request->post['config_shopper_link'])) {
            $data['config_shopper_link'] = $this->request->post['config_shopper_link'];
        } else {
            $data['config_shopper_link'] = $this->config->get('config_shopper_link');
        }

        if (isset($this->request->post['config_konduto_private_key'])) {
            $data['config_konduto_private_key'] = $this->request->post['config_konduto_private_key'];
        } else {
            $data['config_konduto_private_key'] = $this->config->get('config_konduto_private_key');
        }

        if (isset($this->request->post['config_konduto_public_key'])) {
            $data['config_konduto_public_key'] = $this->request->post['config_konduto_public_key'];
        } else {
            $data['config_konduto_public_key'] = $this->config->get('config_konduto_public_key');
        }

        if (isset($this->request->post['config_sendy_public_key'])) {
            $data['config_sendy_public_key'] = $this->request->post['config_sendy_public_key'];
        } else {
            $data['config_sendy_public_key'] = $this->config->get('config_sendy_public_key');
        }

        if (isset($this->request->post['config_sendy_api_end'])) {
            $data['config_sendy_api_end'] = $this->request->post['config_sendy_api_end'];
        } else {
            $data['config_sendy_api_end'] = $this->config->get('config_sendy_api_end');
        }

        if (isset($this->request->post['config_sendy_mail_from_name'])) {
            $data['config_sendy_mail_from_name'] = $this->request->post['config_sendy_mail_from_name'];
        } else {
            $data['config_sendy_mail_from_name'] = $this->config->get('config_sendy_mail_from_name');
        }

        if (isset($this->request->post['config_sendy_mail_from'])) {
            $data['config_sendy_mail_from'] = $this->request->post['config_sendy_mail_from'];
        } else {
            $data['config_sendy_mail_from'] = $this->config->get('config_sendy_mail_from');
        }

        if ($this->request->server['HTTPS']) {
            $server = HTTPS_CATALOG;
        } else {
            $server = HTTP_CATALOG;
        }

        if (isset($this->request->post['config_sendy_db_host'])) {
            $data['config_sendy_db_host'] = $this->request->post['config_sendy_db_host'];
        } else {
            $data['config_sendy_db_host'] = $this->config->get('config_sendy_db_host');
        }

        if (isset($this->request->post['config_sendy_db_user'])) {
            $data['config_sendy_db_user'] = $this->request->post['config_sendy_db_user'];
        } else {
            $data['config_sendy_db_user'] = $this->config->get('config_sendy_db_user');
        }

        if (isset($this->request->post['config_sendy_db_pass'])) {
            $data['config_sendy_db_pass'] = $this->request->post['config_sendy_db_pass'];
        } else {
            $data['config_sendy_db_pass'] = $this->config->get('config_sendy_db_pass');
        }

        if (isset($this->request->post['config_sendy_db_port'])) {
            $data['config_sendy_db_port'] = $this->request->post['config_sendy_db_port'];
        } else {
            $data['config_sendy_db_port'] = $this->config->get('config_sendy_db_port');
        }

        if (isset($this->request->post['config_sendy_db_name'])) {
            $data['config_sendy_db_name'] = $this->request->post['config_sendy_db_name'];
        } else {
            $data['config_sendy_db_name'] = $this->config->get('config_sendy_db_name');
        }

        if (isset($this->request->post['config_delivery_username'])) {
            $data['config_delivery_username'] = $this->request->post['config_delivery_username'];
        } else {
            $data['config_delivery_username'] = $this->config->get('config_delivery_username');
        }

        if (isset($this->request->post['config_delivery_status_webhook'])) {
            $data['config_delivery_status_webhook'] = $server . 'index.php?path=deliversystem/deliversystem/updateOrderHistory';
        } else {
            $data['config_delivery_status_webhook'] = $server . 'index.php?path=deliversystem/deliversystem/updateOrderHistory';
        }

        if (isset($this->request->post['config_delivery_secret'])) {
            $data['config_delivery_secret'] = $this->request->post['config_delivery_secret'];
        } else {
            $data['config_delivery_secret'] = $this->config->get('config_delivery_secret');
        }

        $data['stripe_info_exists'] = false;

        //client id : ca_BoxbgCghYSt10rKtPzsW6WCLQ7nEEXIz
        // prod clieb id : ca_Boxbu44NY0Vjs9rGAp1bvmVMtOzTZmhn

        $this->load->model('payment/stripe');

        $data['stripe_info'] = $this->model_payment_stripe->getVendorStripeAccount('0');

        if ($data['stripe_info']) {
            $data['stripe_info_exists'] = true;
        }

        $this->load->model('tool/image');

        $data['publishable_key'] = $this->config->get('stripe_connect_platform_id'); //'ca_BoxbgCghYSt10rKtPzsW6WCLQ7nEEXIz';

        $data['stripe_image'] = $this->model_tool_image->resize('stripe_image.png', 190, 33);

        /* if($this->config->has('config_delivery_stripe_account')) {
          $data['config_delivery_stripe_account'] = $this->config->get('config_delivery_stripe_account');
          } else {
          $data['config_delivery_stripe_account'] = false;
          } */

        if (isset($this->request->post['config_package_prefix'])) {
            $data['config_package_prefix'] = $this->request->post['config_package_prefix'];
        } else {
            $data['config_package_prefix'] = $this->config->get('config_package_prefix');
        }

        if (isset($this->request->post['config_email'])) {
            $data['config_email'] = $this->request->post['config_email'];
        } else {
            $data['config_email'] = $this->config->get('config_email');
        }

        if (isset($this->request->post['config_from_email'])) {
            $data['config_from_email'] = $this->request->post['config_from_email'];
        } else {
            $data['config_from_email'] = $this->config->get('config_from_email');
        }

        if (isset($this->request->post['config_telephone'])) {
            $data['config_telephone'] = $this->request->post['config_telephone'];
        } else {
            $data['config_telephone'] = $this->config->get('config_telephone');
        }

        if (isset($this->request->post['config_telephone_code'])) {
            $data['config_telephone_code'] = $this->request->post['config_telephone_code'];
        } else {
            $data['config_telephone_code'] = $this->config->get('config_telephone_code');
        }

        if (isset($this->request->post['config_fax'])) {
            $data['config_fax'] = $this->request->post['config_fax'];
        } else {
            $data['config_fax'] = $this->config->get('config_fax');
        }

        if (isset($this->request->post['config_twitter'])) {
            $data['config_twitter'] = $this->request->post['config_twitter'];
        } else {
            $data['config_twitter'] = $this->config->get('config_twitter');
        }

        if (isset($this->request->post['config_facebook'])) {
            $data['config_facebook'] = $this->request->post['config_facebook'];
        } else {
            $data['config_facebook'] = $this->config->get('config_facebook');
        }

        if (isset($this->request->post['config_reward_value'])) {
            $data['config_reward_value'] = $this->request->post['config_reward_value'];
        } else {
            $data['config_reward_value'] = $this->config->get('config_reward_value');
        }

        if (isset($this->request->post['config_reward_onsignup'])) {
            $data['config_reward_onsignup'] = $this->request->post['config_reward_onsignup'];
        } else {
            $data['config_reward_onsignup'] = $this->config->get('config_reward_onsignup');
        }

        if (isset($this->request->post['config_credit_onsignup'])) {
            $data['config_credit_onsignup'] = $this->request->post['config_credit_onsignup'];
        } else {
            $data['config_credit_onsignup'] = $this->config->get('config_credit_onsignup');
        }

        if (isset($this->request->post['config_reward_switch_order_value'])) {
            $data['config_reward_switch_order_value'] = $this->request->post['config_reward_switch_order_value'];
        } else {
            $data['config_reward_switch_order_value'] = $this->config->get('config_reward_switch_order_value');
        }

        //echo "<pre>";print_r($data['config_reward_switch_order_value']);die;
        if (isset($this->request->post['config_reward_on_order_total'])) {
            $data['config_reward_on_order_total'] = $this->request->post['config_reward_on_order_total'];
        } else {
            $data['config_reward_on_order_total'] = $this->config->get('config_reward_on_order_total');
        }

        if (isset($this->request->post['config_credit_switch_order_value'])) {
            $data['config_credit_switch_order_value'] = $this->request->post['config_credit_switch_order_value'];
        } else {
            $data['config_credit_switch_order_value'] = $this->config->get('config_credit_switch_order_value');
        }

        //echo "<pre>";print_r($data['config_credit_switch_order_value']);die;
        if (isset($this->request->post['config_credit_on_order_total'])) {
            $data['config_credit_on_order_total'] = $this->request->post['config_credit_on_order_total'];
        } else {
            $data['config_credit_on_order_total'] = $this->config->get('config_credit_on_order_total');
        }

        if (isset($this->request->post['config_reward_enabled'])) {
            $data['config_reward_enabled'] = $this->request->post['config_reward_enabled'];
        } else {
            $data['config_reward_enabled'] = $this->config->get('config_reward_enabled');
        }

        if (isset($this->request->post['config_checkout_question_enabled'])) {
            $data['config_checkout_question_enabled'] = $this->request->post['config_checkout_question_enabled'];
        } else {
            $data['config_checkout_question_enabled'] = $this->config->get('config_checkout_question_enabled');
        }

        if (isset($this->request->post['config_credit_enabled'])) {
            $data['config_credit_enabled'] = $this->request->post['config_credit_enabled'];
        } else {
            $data['config_credit_enabled'] = $this->config->get('config_credit_enabled');
        }

        if (isset($this->request->post['config_youtube'])) {
            $data['config_youtube'] = $this->request->post['config_youtube'];
        } else {
            $data['config_youtube'] = $this->config->get('config_youtube');
        }

        if (isset($this->request->post['config_instagram'])) {
            $data['config_instagram'] = $this->request->post['config_instagram'];
        } else {
            $data['config_instagram'] = $this->config->get('config_instagram');
        }

        if (isset($this->request->post['config_google'])) {
            $data['config_google'] = $this->request->post['config_google'];
        } else {
            $data['config_google'] = $this->config->get('config_google');
        }

        if (isset($this->request->post['config_google_client_id'])) {
            $data['config_google_client_id'] = $this->request->post['config_google_client_id'];
        } else {
            $data['config_google_client_id'] = $this->config->get('config_google_client_id');
        }

        if (isset($this->request->post['config_google_client_secret'])) {
            $data['config_google_client_secret'] = $this->request->post['config_google_client_secret'];
        } else {
            $data['config_google_client_secret'] = $this->config->get('config_google_client_secret');
        }

        if (isset($this->request->post['config_image'])) {
            $data['config_image'] = $this->request->post['config_image'];
        } else {
            $data['config_image'] = $this->config->get('config_image');
        }

        if (isset($this->request->post['config_footer_thumb'])) {
            $data['config_footer_thumb'] = $this->request->post['config_footer_thumb'];
        } else {
            $data['config_footer_thumb'] = $this->config->get('config_footer_thumb');
        }

        $this->load->model('tool/image');

        if (isset($this->request->post['config_image']) && is_file(DIR_IMAGE . $this->request->post['config_image'])) {
            $data['thumb'] = $this->model_tool_image->resize($this->request->post['config_image'], 100, 100);
        } elseif ($this->config->get('config_image') && is_file(DIR_IMAGE . $this->config->get('config_image'))) {
            $data['thumb'] = $this->model_tool_image->resize($this->config->get('config_image'), 100, 100);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        if (isset($this->request->post['config_footer_thumb']) && is_file(DIR_IMAGE . $this->request->post['config_footer_thumb'])) {
            $data['footer_thumb'] = $this->model_tool_image->resize($this->request->post['config_footer_thumb'], 100, 100);
        } elseif ($this->config->get('config_footer_thumb') && is_file(DIR_IMAGE . $this->config->get('config_footer_thumb'))) {
            $data['footer_thumb'] = $this->model_tool_image->resize($this->config->get('config_footer_thumb'), 100, 100);
        } else {
            $data['footer_thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        /*
          config_android_app_link
          config_apple_app_link
          config_promo_app_image
          config_map_image
          map_image_thumb
          promo_app_image_thumb
         */

        if (isset($this->request->post['config_map_image']) && is_file(DIR_IMAGE . $this->request->post['config_map_image'])) {
            $data['map_image_thumb'] = $this->model_tool_image->resize($this->request->post['config_map_image'], 100, 100);
        } elseif ($this->config->get('config_map_image') && is_file(DIR_IMAGE . $this->config->get('config_map_image'))) {
            $data['map_image_thumb'] = $this->model_tool_image->resize($this->config->get('config_map_image'), 100, 100);
        } else {
            $data['map_image_thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        if (isset($this->request->post['config_promo_app_image']) && is_file(DIR_IMAGE . $this->request->post['config_promo_app_image'])) {
            $data['promo_app_image_thumb'] = $this->model_tool_image->resize($this->request->post['config_promo_app_image'], 100, 100);
        } elseif ($this->config->get('config_promo_app_image') && is_file(DIR_IMAGE . $this->config->get('config_promo_app_image'))) {
            $data['promo_app_image_thumb'] = $this->model_tool_image->resize($this->config->get('config_promo_app_image'), 100, 100);
        } else {
            $data['promo_app_image_thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        if (isset($this->request->post['config_map_image'])) {
            $data['config_map_image'] = $this->request->post['config_map_image'];
        } else {
            $data['config_map_image'] = $this->config->get('config_map_image');
        }

        if (isset($this->request->post['config_promo_app_image'])) {
            $data['config_promo_app_image'] = $this->request->post['config_promo_app_image'];
        } else {
            $data['config_promo_app_image'] = $this->config->get('config_promo_app_image');
        }

        if (isset($this->request->post['config_android_app_link'])) {
            $data['config_android_app_link'] = $this->request->post['config_android_app_link'];
        } else {
            $data['config_android_app_link'] = $this->config->get('config_android_app_link');
        }

        if (isset($this->request->post['config_apple_app_link'])) {
            $data['config_apple_app_link'] = $this->request->post['config_apple_app_link'];
        } else {
            $data['config_apple_app_link'] = $this->config->get('config_apple_app_link');
        }

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        $this->load->model('localisation/location');

        $data['locations'] = $this->model_localisation_location->getLocations();

        if (isset($this->request->post['config_location'])) {
            $data['config_location'] = $this->request->post['config_location'];
        } elseif ($this->config->get('config_location')) {
            $data['config_location'] = $this->config->get('config_location');
        } else {
            $data['config_location'] = [];
        }

        if (isset($this->request->post['config_meta_title'])) {
            $data['config_meta_title'] = $this->request->post['config_meta_title'];
        } else {
            $data['config_meta_title'] = $this->config->get('config_meta_title');
        }

        if (isset($this->request->post['config_meta_description'])) {
            $data['config_meta_description'] = $this->request->post['config_meta_description'];
        } else {
            $data['config_meta_description'] = $this->config->get('config_meta_description');
        }

        if (isset($this->request->post['config_meta_keyword'])) {
            $data['config_meta_keyword'] = $this->request->post['config_meta_keyword'];
        } else {
            $data['config_meta_keyword'] = $this->config->get('config_meta_keyword');
        }

        $this->load->model('appearance/layout');

        $data['layouts'] = $this->model_appearance_layout->getLayouts();

        if (isset($this->request->post['config_template'])) {
            $data['config_template'] = $this->request->post['config_template'];
        } else {
            $data['config_template'] = $this->config->get('config_template');
        }

        $data['templates'] = [];

        $directories = glob(DIR_CATALOG . 'ui/theme/*', GLOB_ONLYDIR);

        foreach ($directories as $directory) {
            $data['templates'][] = basename($directory);
        }

        $this->load->model('appearance/layout');

        $data['cities'] = $this->model_appearance_layout->getCities();

        if (isset($this->request->post['config_city_id'])) {
            $data['config_city_id'] = $this->request->post['config_city_id'];
        } else {
            $data['config_city_id'] = $this->config->get('config_city_id');
        }

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        if (isset($this->request->post['config_admin_language'])) {
            $data['config_admin_language'] = $this->request->post['config_admin_language'];
        } else {
            $data['config_admin_language'] = $this->config->get('config_admin_language');
        }

        if (isset($this->request->post['config_language'])) {
            $data['config_language'] = $this->request->post['config_language'];
        } else {
            $data['config_language'] = $this->config->get('config_language');

            //echo "<pre>";print_r($data['config_language']);die;
        }

        if (isset($this->request->post['config_currency'])) {
            $data['config_currency'] = $this->request->post['config_currency'];
        } else {
            $data['config_currency'] = $this->config->get('config_currency');
        }

        if (isset($this->request->post['config_timezone'])) {
            $data['config_timezone'] = $this->request->post['config_timezone'];
        } else {
            $data['config_timezone'] = $this->config->get('config_timezone');
        }

        if (isset($this->request->post['config_currency_auto'])) {
            $data['config_currency_auto'] = $this->request->post['config_currency_auto'];
        } else {
            $data['config_currency_auto'] = $this->config->get('config_currency_auto');
        }

        $this->load->model('localisation/currency');

        $data['currencies'] = $this->model_localisation_currency->getCurrencies();

        if (isset($this->request->post['config_product_limit'])) {
            $data['config_product_limit'] = $this->request->post['config_product_limit'];
        } else {
            $data['config_product_limit'] = $this->config->get('config_product_limit');
        }

        if (isset($this->request->post['config_app_product_limit'])) {
            $data['config_app_product_limit'] = $this->request->post['config_app_product_limit'];
        } else {
            $data['config_app_product_limit'] = $this->config->get('config_app_product_limit');
        }

        if (isset($this->request->post['config_product_description_length'])) {
            $data['config_product_description_length'] = $this->request->post['config_product_description_length'];
        } else {
            $data['config_product_description_length'] = $this->config->get('config_product_description_length');
        }

        if (isset($this->request->post['config_refered_points'])) {
            $data['config_refered_points'] = $this->request->post['config_refered_points'];
        } else {
            $data['config_refered_points'] = $this->config->get('config_refered_points');
        }

        if (isset($this->request->post['config_referee_points'])) {
            $data['config_referee_points'] = $this->request->post['config_referee_points'];
        } else {
            $data['config_referee_points'] = $this->config->get('config_referee_points');
        }

        if (isset($this->request->post['config_limit_admin'])) {
            $data['config_limit_admin'] = $this->request->post['config_limit_admin'];
        } else {
            $data['config_limit_admin'] = $this->config->get('config_limit_admin');
        }

        if (isset($this->request->post['config_text_editor'])) {
            $data['config_text_editor'] = $this->request->post['config_text_editor'];
        } else {
            $data['config_text_editor'] = $this->config->get('config_text_editor');
        }

        if (isset($this->request->post['config_store_location'])) {
            $data['config_store_location'] = $this->request->post['config_store_location'];
        } else {
            $data['config_store_location'] = $this->config->get('config_store_location');
        }

        if (isset($this->request->post['config_zipcode_mask'])) {
            $data['config_zipcode_mask'] = $this->request->post['config_zipcode_mask'];
        } else {
            $data['config_zipcode_mask'] = $this->config->get('config_zipcode_mask');
        }

        if (isset($this->request->post['config_telephone_mask'])) {
            $data['config_telephone_mask'] = $this->request->post['config_telephone_mask'];
        } else {
            $data['config_telephone_mask'] = $this->config->get('config_telephone_mask');
        }

        if (isset($this->request->post['config_taxnumber_mask'])) {
            $data['config_taxnumber_mask'] = $this->request->post['config_taxnumber_mask'];
        } else {
            $data['config_taxnumber_mask'] = $this->config->get('config_taxnumber_mask');
        }

        if (isset($this->request->post['config_product_description_display'])) {
            $data['config_product_description_display'] = $this->request->post['config_product_description_display'];
        } else {
            $data['config_product_description_display'] = $this->config->get('config_product_description_display');
        }

        if (isset($this->request->post['config_tax'])) {
            $data['config_tax'] = $this->request->post['config_tax'];
        } else {
            $data['config_tax'] = $this->config->get('config_tax');
        }

        if (isset($this->request->post['config_refer_type'])) {
            $data['config_refer_type'] = $this->request->post['config_refer_type'];
        } else {
            $data['config_refer_type'] = $this->config->get('config_refer_type');
        }

        //echo "<pre>";print_r($data);die;

        if (isset($this->request->post['config_customer_online'])) {
            $data['config_customer_online'] = $this->request->post['config_customer_online'];
        } else {
            $data['config_customer_online'] = $this->config->get('config_customer_online');
        }

        if (isset($this->request->post['config_member_account_fee'])) {
            $data['config_member_account_fee'] = $this->request->post['config_member_account_fee'];
        } else {
            $data['config_member_account_fee'] = $this->config->get('config_member_account_fee');
        }

        if (isset($this->request->post['config_return_timeout'])) {
            $data['config_return_timeout'] = $this->request->post['config_return_timeout'];
        } else {
            $data['config_return_timeout'] = $this->config->get('config_return_timeout');
        }

        if (isset($this->request->post['config_member_group_id'])) {
            $data['config_member_group_id'] = $this->request->post['config_member_group_id'];
        } else {
            $data['config_member_group_id'] = $this->config->get('config_member_group_id');
        }

        if (isset($this->request->post['config_customer_group_id'])) {
            $data['config_customer_group_id'] = $this->request->post['config_customer_group_id'];
        } else {
            $data['config_customer_group_id'] = $this->config->get('config_customer_group_id');
        }

        if (isset($this->request->post['config_account_return_status'])) {
            $data['config_account_return_status'] = $this->request->post['config_account_return_status'];
        } else {
            $data['config_account_return_status'] = $this->config->get('config_account_return_status');
        }

        if (isset($this->request->post['config_account_return_product_status'])) {
            $data['config_account_return_product_status'] = $this->request->post['config_account_return_product_status'];
        } else {
            $data['config_account_return_product_status'] = $this->config->get('config_account_return_product_status');
        }

        if (isset($this->request->post['config_footer_video_link'])) {
            $data['config_footer_video_link'] = $this->request->post['config_footer_video_link'];
        } else {
            $data['config_footer_video_link'] = $this->config->get('config_footer_video_link');
        }

        $this->load->model('sale/customer_group');

        $data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

        if (isset($this->request->post['config_customer_group_display'])) {
            $data['config_customer_group_display'] = $this->request->post['config_customer_group_display'];
        } elseif ($this->config->get('config_customer_group_display')) {
            $data['config_customer_group_display'] = $this->config->get('config_customer_group_display');
        } else {
            $data['config_customer_group_display'] = [];
        }

        if (isset($this->request->post['config_customer_price'])) {
            $data['config_customer_price'] = $this->request->post['config_customer_price'];
        } else {
            $data['config_customer_price'] = $this->config->get('config_customer_price');
        }

        if (isset($this->request->post['config_login_attempts'])) {
            $data['config_login_attempts'] = $this->request->post['config_login_attempts'];
        } elseif ($this->config->has('config_login_attempts')) {
            $data['config_login_attempts'] = $this->config->get('config_login_attempts');
        } else {
            $data['config_login_attempts'] = 5;
        }

        if (isset($this->request->post['config_account_id'])) {
            $data['config_account_id'] = $this->request->post['config_account_id'];
        } else {
            $data['config_account_id'] = $this->config->get('config_account_id');
        }

        $this->load->model('catalog/information');

        $data['informations'] = $this->model_catalog_information->getInformations();

        if (isset($this->request->post['config_account_mail'])) {
            $data['config_account_mail'] = $this->request->post['config_account_mail'];
        } else {
            $data['config_account_mail'] = $this->config->get('config_account_mail');
        }

        if (isset($this->request->post['config_api_id'])) {
            $data['config_api_id'] = $this->request->post['config_api_id'];
        } else {
            $data['config_api_id'] = $this->config->get('config_api_id');
        }

        $this->load->model('user/api');

        $data['apis'] = $this->model_user_api->getApis();

        if (isset($this->request->post['config_invoice_prefix'])) {
            $data['config_invoice_prefix'] = $this->request->post['config_invoice_prefix'];
        } elseif ($this->config->get('config_invoice_prefix')) {
            $data['config_invoice_prefix'] = $this->config->get('config_invoice_prefix');
        } else {
            $data['config_invoice_prefix'] = 'INV-' . date('Y') . '-00';
        }

        if (isset($this->request->post['config_order_status_id'])) {
            $data['config_order_status_id'] = $this->request->post['config_order_status_id'];
        } else {
            $data['config_order_status_id'] = $this->config->get('config_order_status_id');
        }

        if (isset($this->request->post['config_shipped_status'])) {
            $data['config_shipped_status'] = $this->request->post['config_shipped_status'];
        } elseif ($this->config->get('config_shipped_status')) {
            $data['config_shipped_status'] = $this->config->get('config_shipped_status');
        } else {
            $data['config_shipped_status'] = [];
        }

        if (isset($this->request->post['config_payment_methods_status'])) {
            $data['config_payment_methods_status'] = $this->request->post['config_payment_methods_status'];
        } elseif ($this->config->get('config_payment_methods_status')) {
            $data['config_payment_methods_status'] = $this->config->get('config_payment_methods_status');
        } else {
            $data['config_payment_methods_status'] = [];
        }

        if (isset($this->request->post['config_delivery_shipping_methods_status'])) {
            $data['config_delivery_shipping_methods_status'] = $this->request->post['config_delivery_shipping_methods_status'];
        } elseif ($this->config->get('config_delivery_shipping_methods_status')) {
            $data['config_delivery_shipping_methods_status'] = $this->config->get('config_delivery_shipping_methods_status');
        } else {
            $data['config_delivery_shipping_methods_status'] = [];
        }

        //echo "<pre>";print_r($this->config->get('config_delivery_shipping_methods_status'));die;

        if (isset($this->request->post['config_processing_status'])) {
            $data['config_processing_status'] = $this->request->post['config_processing_status'];
        } elseif ($this->config->get('config_processing_status')) {
            $data['config_processing_status'] = $this->config->get('config_processing_status');
        } else {
            $data['config_processing_status'] = [];
        }

        if (isset($this->request->post['config_refund_status'])) {
            $data['config_refund_status'] = $this->request->post['config_refund_status'];
        } elseif ($this->config->get('config_refund_status')) {
            $data['config_refund_status'] = $this->config->get('config_refund_status');
        } else {
            $data['config_refund_status'] = [];
        }

        if (isset($this->request->post['config_ready_for_pickup_status'])) {
            $data['config_ready_for_pickup_status'] = $this->request->post['config_ready_for_pickup_status'];
        } elseif ($this->config->get('config_ready_for_pickup_status')) {
            $data['config_ready_for_pickup_status'] = $this->config->get('config_ready_for_pickup_status');
        } else {
            $data['config_ready_for_pickup_status'] = [];
        }

        if (isset($this->request->post['config_complete_status'])) {
            $data['config_complete_status'] = $this->request->post['config_complete_status'];
        } elseif ($this->config->get('config_complete_status')) {
            $data['config_complete_status'] = $this->config->get('config_complete_status');
        } else {
            $data['config_complete_status'] = [];
        }

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        /* start */

        $data['payment_methods'] = [];

        $this->load->model('extension/extension');

        $payment_methods = $this->model_extension_extension->getExtensions('payment');

        foreach ($payment_methods as $payment_method) {
            if ($this->config->get($payment_method['code'] . '_status')) {
                $p = $this->load->language('payment/' . $payment_method['code']);
                //print_r($p['heading_title']);

                $payment_method['name'] = $p['heading_title'];

                array_push($data['payment_methods'], $payment_method);
            }
        }

        //echo "<pre>";print_r($data['payment_methods']);die;

        /* Start */

        $data['shipping_methods'] = [];

        $this->load->model('extension/extension');

        $shipping_methods = $this->model_extension_extension->getExtensions('shipping');

        foreach ($shipping_methods as $s_method) {
            $p = $this->load->language('shipping/' . $s_method['code']);
            //print_r($p['heading_title']);

            $s_method['name'] = $p['heading_title'];

            array_push($data['shipping_methods'], $s_method);
        }

        //echo "<pre>";print_r($data['shipping_methods']);
        //die;
        /* echo "<pre>";print_r($methods);die;
          foreach ($methods as $method) {

          if ($this->config->get($method['code'] . '_status')) {
          }
          } */

        /* END */
        if (isset($this->request->post['config_order_mail'])) {
            $data['config_order_mail'] = $this->request->post['config_order_mail'];
        } else {
            $data['config_order_mail'] = $this->config->get('config_order_mail');
        }

        if (isset($this->request->post['config_multi_store'])) {
            $data['config_multi_store'] = $this->request->post['config_multi_store'];
        } else {
            $data['config_multi_store'] = $this->config->get('config_multi_store');
        }

        if (isset($this->request->post['config_stock_display'])) {
            $data['config_stock_display'] = $this->request->post['config_stock_display'];
        } else {
            $data['config_stock_display'] = $this->config->get('config_stock_display');
        }

        if (isset($this->request->post['config_stock_warning'])) {
            $data['config_stock_warning'] = $this->request->post['config_stock_warning'];
        } else {
            $data['config_stock_warning'] = $this->config->get('config_stock_warning');
        }

        if (isset($this->request->post['config_stock_checkout'])) {
            $data['config_stock_checkout'] = $this->request->post['config_stock_checkout'];
        } else {
            $data['config_stock_checkout'] = $this->config->get('config_stock_checkout');
        }

        if (isset($this->request->post['config_return_id'])) {
            $data['config_return_id'] = $this->request->post['config_return_id'];
        } else {
            $data['config_return_id'] = $this->config->get('config_return_id');
        }

        if (isset($this->request->post['config_seller_id'])) {
            $data['config_seller_id'] = $this->request->post['config_seller_id'];
        } else {
            $data['config_seller_id'] = $this->config->get('config_seller_id');
        }

        if (isset($this->request->post['config_privacy_policy_id'])) {
            $data['config_privacy_policy_id'] = $this->request->post['config_privacy_policy_id'];
        } else {
            $data['config_privacy_policy_id'] = $this->config->get('config_privacy_policy_id');
        }

        if (isset($this->request->post['config_return_status_id'])) {
            $data['config_return_status_id'] = $this->request->post['config_return_status_id'];
        } else {
            $data['config_return_status_id'] = $this->config->get('config_return_status_id');
        }

        if (isset($this->request->post['config_complete_return_status_id'])) {
            $data['config_complete_return_status_id'] = $this->request->post['config_complete_return_status_id'];
        } else {
            $data['config_complete_return_status_id'] = $this->config->get('config_complete_return_status_id');
        }

        $this->load->model('localisation/return_status');

        $data['return_statuses'] = $this->model_localisation_return_status->getReturnStatuses();

        if (isset($this->request->post['config_logo'])) {
            $data['config_logo'] = $this->request->post['config_logo'];
        } else {
            $data['config_logo'] = $this->config->get('config_logo');
        }

        if (isset($this->request->post['config_white_logo'])) {
            $data['config_white_logo'] = $this->request->post['config_white_logo'];
        } else {
            $data['config_white_logo'] = !empty($this->config->get('config_white_logo')) ? $this->config->get('config_white_logo') : '';
        }

        if (isset($this->request->post['config_logo']) && is_file(DIR_IMAGE . $this->request->post['config_logo'])) {
            $data['logo'] = $this->model_tool_image->resize($this->request->post['config_logo'], 100, 100);
        } elseif ($this->config->get('config_logo') && is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
            $data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'), 100, 100);
        } else {
            $data['logo'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        if (isset($this->request->post['config_white_logo']) && is_file(DIR_IMAGE . $this->request->post['config_white_logo'])) {
            $data['white_logo'] = $this->model_tool_image->resize($this->request->post['config_white_logo'], 100, 100);
        } elseif ($this->config->get('config_white_logo') && is_file(DIR_IMAGE . $this->config->get('config_white_logo'))) {
            $data['white_logo'] = $this->model_tool_image->resize($this->config->get('config_white_logo'), 100, 100);
        } else {
            $data['white_logo'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        if (isset($this->request->post['config_small_icon'])) {
            $data['config_small_icon'] = $this->request->post['config_small_icon'];
        } else {
            $data['config_small_icon'] = $this->config->get('config_small_icon');
        }

        if (isset($this->request->post['config_small_icon']) && is_file(DIR_IMAGE . $this->request->post['config_small_icon'])) {
            $data['small_icon'] = $this->model_tool_image->resize($this->request->post['config_small_icon'], 100, 100);
        } elseif ($this->config->get('config_icon') && is_file(DIR_IMAGE . $this->config->get('config_small_icon'))) {
            $data['small_icon'] = $this->model_tool_image->resize($this->config->get('config_small_icon'), 100, 100);
        } else {
            $data['small_icon'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        if (isset($this->request->post['config_icon'])) {
            $data['config_icon'] = $this->request->post['config_icon'];
        } else {
            $data['config_icon'] = $this->config->get('config_icon');
        }

        if (isset($this->request->post['config_icon']) && is_file(DIR_IMAGE . $this->request->post['config_icon'])) {
            $data['icon'] = $this->model_tool_image->resize($this->request->post['config_logo'], 100, 100);
        } elseif ($this->config->get('config_icon') && is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
            $data['icon'] = $this->model_tool_image->resize($this->config->get('config_icon'), 100, 100);
        } else {
            $data['icon'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        if (isset($this->request->post['config_image_category_width'])) {
            $data['config_image_category_width'] = $this->request->post['config_image_category_width'];
        } else {
            $data['config_image_category_width'] = $this->config->get('config_image_category_width');
        }

        if (isset($this->request->post['config_image_category_height'])) {
            $data['config_image_category_height'] = $this->request->post['config_image_category_height'];
        } else {
            $data['config_image_category_height'] = $this->config->get('config_image_category_height');
        }

        if (isset($this->request->post['config_image_thumb_width'])) {
            $data['config_image_thumb_width'] = $this->request->post['config_image_thumb_width'];
        } else {
            $data['config_image_thumb_width'] = $this->config->get('config_image_thumb_width');
        }

        if (isset($this->request->post['config_image_thumb_height'])) {
            $data['config_image_thumb_height'] = $this->request->post['config_image_thumb_height'];
        } else {
            $data['config_image_thumb_height'] = $this->config->get('config_image_thumb_height');
        }

        if (isset($this->request->post['config_zoomimage_thumb_width'])) {
            $data['config_zoomimage_thumb_width'] = $this->request->post['config_zoomimage_thumb_width'];
        } else {
            $data['config_zoomimage_thumb_width'] = $this->config->get('config_zoomimage_thumb_width');
        }

        if (isset($this->request->post['config_zoomimage_thumb_height'])) {
            $data['config_zoomimage_thumb_height'] = $this->request->post['config_zoomimage_thumb_height'];
        } else {
            $data['config_zoomimage_thumb_height'] = $this->config->get('config_zoomimage_thumb_height');
        }

        if (isset($this->request->post['config_image_product_width'])) {
            $data['config_image_product_width'] = $this->request->post['config_image_product_width'];
        } else {
            $data['config_image_product_width'] = $this->config->get('config_image_product_width');
        }

        if (isset($this->request->post['config_image_product_height'])) {
            $data['config_image_product_height'] = $this->request->post['config_image_product_height'];
        } else {
            $data['config_image_product_height'] = $this->config->get('config_image_product_height');
        }

        if (isset($this->request->post['config_image_cart_width'])) {
            $data['config_image_cart_width'] = $this->request->post['config_image_cart_width'];
        } else {
            $data['config_image_cart_width'] = $this->config->get('config_image_cart_width');
        }

        if (isset($this->request->post['config_image_cart_height'])) {
            $data['config_image_cart_height'] = $this->request->post['config_image_cart_height'];
        } else {
            $data['config_image_cart_height'] = $this->config->get('config_image_cart_height');
        }

        if (isset($this->request->post['config_image_location_width'])) {
            $data['config_image_location_width'] = $this->request->post['config_image_location_width'];
        } else {
            $data['config_image_location_width'] = $this->config->get('config_image_location_width');
        }

        if (isset($this->request->post['config_image_location_height'])) {
            $data['config_image_location_height'] = $this->request->post['config_image_location_height'];
        } else {
            $data['config_image_location_height'] = $this->config->get('config_image_location_height');
        }

        if (isset($this->request->post['config_app_image_category_width'])) {
            $data['config_app_image_category_width'] = $this->request->post['config_app_image_category_width'];
        } else {
            $data['config_app_image_category_width'] = $this->config->get('config_app_image_category_width');
        }

        if (isset($this->request->post['config_app_image_category_height'])) {
            $data['config_app_image_category_height'] = $this->request->post['config_app_image_category_height'];
        } else {
            $data['config_app_image_category_height'] = $this->config->get('config_app_image_category_height');
        }

        if (isset($this->request->post['config_app_image_thumb_width'])) {
            $data['config_app_image_thumb_width'] = $this->request->post['config_app_image_thumb_width'];
        } else {
            $data['config_app_image_thumb_width'] = $this->config->get('config_app_image_thumb_width');
        }

        if (isset($this->request->post['config_app_image_thumb_height'])) {
            $data['config_app_image_thumb_height'] = $this->request->post['config_app_image_thumb_height'];
        } else {
            $data['config_app_image_thumb_height'] = $this->config->get('config_app_image_thumb_height');
        }

        if (isset($this->request->post['config_app_image_product_width'])) {
            $data['config_app_image_product_width'] = $this->request->post['config_app_image_product_width'];
        } else {
            $data['config_app_image_product_width'] = $this->config->get('config_app_image_product_width');
        }

        if (isset($this->request->post['config_app_image_product_height'])) {
            $data['config_app_image_product_height'] = $this->request->post['config_app_image_product_height'];
        } else {
            $data['config_app_image_product_height'] = $this->config->get('config_app_image_product_height');
        }

        if (isset($this->request->post['config_app_image_cart_width'])) {
            $data['config_app_image_cart_width'] = $this->request->post['config_app_image_cart_width'];
        } else {
            $data['config_app_image_cart_width'] = $this->config->get('config_app_image_cart_width');
        }

        if (isset($this->request->post['config_app_image_cart_height'])) {
            $data['config_app_image_cart_height'] = $this->request->post['config_app_image_cart_height'];
        } else {
            $data['config_app_image_cart_height'] = $this->config->get('config_app_image_cart_height');
        }

        if (isset($this->request->post['config_app_image_location_width'])) {
            $data['config_app_image_location_width'] = $this->request->post['config_app_image_location_width'];
        } else {
            $data['config_app_image_location_width'] = $this->config->get('config_app_image_location_width');
        }

        if (isset($this->request->post['config_app_notice_image_location_width'])) {
            $data['config_app_notice_image_location_width'] = $this->request->post['config_app_notice_image_location_width'];
        } else {
            $data['config_app_notice_image_location_width'] = $this->config->get('config_app_notice_image_location_width');
        }

        if (isset($this->request->post['config_app_image_location_height'])) {
            $data['config_app_image_location_height'] = $this->request->post['config_app_image_location_height'];
        } else {
            $data['config_app_image_location_height'] = $this->config->get('config_app_image_location_height');
        }

        if (isset($this->request->post['config_app_notice_image_location_height'])) {
            $data['config_app_notice_image_location_height'] = $this->request->post['config_app_notice_image_location_height'];
        } else {
            $data['config_app_notice_image_location_height'] = $this->config->get('config_app_notice_image_location_height');
        }

        if (isset($this->request->post['config_mail'])) {
            $config_mail = $this->request->post['config_mail'];

            $config_sms_protocol = $this->request->post['config_sms_protocol'];

            /* echo "<pre>";print_r($config_mail); print_r($config_sms_protocol);
              die; */

            $data['config_mail_protocol'] = empty($config_mail['protocol']) ? 'phpmail' : $config_mail['protocol'];
            $data['config_mail_sendmail_path'] = empty($config_mail['sendmail_path']) ? '/usr/sbin/sendmail -bs' : $config_mail['sendmail_path'];
            $data['config_smtp_hostname'] = empty($config_mail['smtp_hostname']) ? '' : $config_mail['smtp_hostname'];
            $data['config_smtp_username'] = empty($config_mail['smtp_username']) ? '' : $config_mail['smtp_username'];
            $data['config_smtp_password'] = empty($config_mail['smtp_password']) ? '' : $config_mail['smtp_password'];
            $data['config_smtp_port'] = empty($config_mail['smtp_port']) ? 25 : $config_mail['smtp_port'];
            $data['config_smtp_encryption'] = empty($config_mail['smtp_encryption']) ? 'none' : $config_mail['smtp_encryption'];

            $data['config_aws_region'] = empty($config_mail['aws_region']) ? '' : $config_mail['aws_region'];
            $data['config_aws_access_id'] = empty($config_mail['aws_access_id']) ? '' : $config_mail['aws_access_id'];
            $data['config_aws_secret_key'] = empty($config_mail['aws_secret_key']) ? '' : $config_mail['aws_secret_key'];

            $data['config_mailgun'] = empty($config_mail['mailgun']) ? '' : $config_mail['mailgun'];
            $data['config_mailgun_domain'] = empty($config_mail['mailgun_domain']) ? '' : $config_mail['mailgun_domain'];

            $data['config_sms_protocol'] = empty($config_sms_protocol) ? 'twilio' : $config_sms_protocol;
        } elseif ($this->config->get('config_mail')) {
            $config_mail = $this->config->get('config_mail');

            $config_sms_protocol = $this->config->get('config_sms_protocol');

            $data['config_mail_protocol'] = $config_mail['protocol'];
            $data['config_mail_sendmail_path'] = $config_mail['sendmail_path'];
            $data['config_smtp_hostname'] = $config_mail['smtp_hostname'];
            $data['config_smtp_username'] = $config_mail['smtp_username'];
            $data['config_smtp_password'] = $config_mail['smtp_password'];
            $data['config_smtp_port'] = $config_mail['smtp_port'];
            $data['config_smtp_encryption'] = $config_mail['smtp_encryption'];

            $data['config_aws_region'] = $config_mail['aws_region'];
            $data['config_aws_access_id'] = $config_mail['aws_access_id'];
            $data['config_aws_secret_key'] = $config_mail['aws_secret_key'];

            $data['config_mailgun'] = $config_mail['mailgun'];
            $data['config_mailgun_domain'] = $config_mail['mailgun_domain'];

            $data['config_sms_protocol'] = empty($config_sms_protocol) ? 'twilio' : $config_sms_protocol;
        } else {
            $data['config_sms_protocol'] = 'twilio';
            $data['config_mail_protocol'] = 'phpmail';
            $data['config_mail_sendmail_path'] = '/usr/sbin/sendmail -bs';
            $data['config_smtp_hostname'] = '';
            $data['config_smtp_username'] = '';
            $data['config_smtp_password'] = '';
            $data['config_smtp_port'] = 25;
            $data['config_smtp_encryption'] = 'none';

            $data['config_aws_region'] = '';
            $data['config_aws_access_id'] = '';
            $data['config_aws_secret_key'] = '';

            $data['config_mailgun'] = '';
            $data['config_mailgun_domain'] = '';
        }

        if (isset($this->request->post['config_mail_alert'])) {
            $data['config_mail_alert'] = $this->request->post['config_mail_alert'];
        } else {
            $data['config_mail_alert'] = $this->config->get('config_mail_alert');
        }

        // SEO
        if (isset($this->request->post['config_seo_url'])) {
            $data['config_seo_url'] = $this->request->post['config_seo_url'];
        } else {
            $data['config_seo_url'] = $this->config->get('config_seo_url');
        }

        if (isset($this->request->post['config_seo_rewrite'])) {
            $data['config_seo_indexphp'] = $this->request->post['config_seo_rewrite'];
        } else {
            $data['config_seo_rewrite'] = $this->config->get('config_seo_rewrite');
        }

        if (isset($this->request->post['config_seo_suffix'])) {
            $data['config_seo_suffix'] = $this->request->post['config_seo_suffix'];
        } else {
            $data['config_seo_suffix'] = $this->config->get('config_seo_suffix');
        }

        if (isset($this->request->post['config_seo_category'])) {
            $data['config_seo_category'] = $this->request->post['config_seo_category'];
        } else {
            $data['config_seo_category'] = $this->config->get('config_seo_category');
        }

        if (isset($this->request->post['config_seo_translate'])) {
            $data['config_seo_translate'] = $this->request->post['config_seo_translate'];
        } else {
            $data['config_seo_translate'] = $this->config->get('config_seo_translate');
        }

        if (isset($this->request->post['config_seo_lang_code'])) {
            $data['config_seo_lang_code'] = $this->request->post['config_seo_lang_code'];
        } else {
            $data['config_seo_lang_code'] = $this->config->get('config_seo_lang_code');
        }

        if (isset($this->request->post['config_seo_canonical'])) {
            $data['config_seo_canonical'] = $this->request->post['config_seo_canonical'];
        } else {
            $data['config_seo_canonical'] = $this->config->get('config_seo_canonical');
        }

        if (isset($this->request->post['config_seo_www_red'])) {
            $data['config_seo_www_red'] = $this->request->post['config_seo_www_red'];
        } else {
            $data['config_seo_www_red'] = $this->config->get('config_seo_www_red');
        }

        if (isset($this->request->post['config_seo_nonseo_red'])) {
            $data['config_seo_nonseo_red'] = $this->request->post['config_seo_nonseo_red'];
        } else {
            $data['config_seo_nonseo_red'] = $this->config->get('config_seo_nonseo_red');
        }

        if (isset($this->request->post['config_meta_title_add'])) {
            $data['config_meta_title_add'] = $this->request->post['config_meta_title_add'];
        } else {
            $data['config_meta_title_add'] = $this->config->get('config_meta_title_add');
        }

        if (isset($this->request->post['config_meta_generator'])) {
            $data['config_meta_generator'] = $this->request->post['config_meta_generator'];
        } else {
            $data['config_meta_generator'] = $this->config->get('config_meta_generator');
        }

        if (isset($this->request->post['config_meta_googlekey'])) {
            $data['config_meta_googlekey'] = $this->request->post['config_meta_googlekey'];
        } else {
            $data['config_meta_googlekey'] = $this->config->get('config_meta_googlekey');
        }

        if (isset($this->request->post['config_meta_alexakey'])) {
            $data['config_meta_alexakey'] = $this->request->post['config_meta_alexakey'];
        } else {
            $data['config_meta_alexakey'] = $this->config->get('config_meta_alexakey');
        }

        $data['config_sitemap_all'] = str_replace('admin/', '', $this->url->link('feed/google_sitemap'));
        $data['config_sitemap_products'] = str_replace('admin/', '', $this->url->link('feed/google_sitemap/products'));
        $data['config_sitemap_categories'] = str_replace('admin/', '', $this->url->link('feed/google_sitemap/categories'));
        $data['config_sitemap_manufacturers'] = str_replace('admin/', '', $this->url->link('feed/google_sitemap/manufacturers'));

        if (isset($this->request->post['config_google_analytics'])) {
            $data['config_google_analytics'] = $this->request->post['config_google_analytics'];
        } else {
            $data['config_google_analytics'] = $this->config->get('config_google_analytics');
        }

        if (isset($this->request->post['config_google_analytics_status'])) {
            $data['config_google_analytics_status'] = $this->request->post['config_google_analytics_status'];
        } else {
            $data['config_google_analytics_status'] = $this->config->get('config_google_analytics_status');
        }

        if (isset($this->request->post['config_offer_status'])) {
            $data['config_offer_status'] = $this->request->post['config_offer_status'];
        } else {
            $data['config_offer_status'] = $this->config->get('config_offer_status');
        }

        if (isset($this->request->post['config_konduto_status'])) {
            $data['config_konduto_status'] = $this->request->post['config_konduto_status'];
        } else {
            $data['config_konduto_status'] = $this->config->get('config_konduto_status');
        }

        if (isset($this->request->post['config_sendy_status'])) {
            $data['config_sendy_status'] = $this->request->post['config_sendy_status'];
        } else {
            $data['config_sendy_status'] = $this->config->get('config_sendy_status');
        }

        if (isset($this->request->post['config_deliver_system_status'])) {
            $data['config_deliver_system_status'] = $this->request->post['config_deliver_system_status'];
        } else {
            $data['config_deliver_system_status'] = $this->config->get('config_deliver_system_status');
        }

        if (isset($this->request->post['config_checkout_deliver_system_status'])) {
            $data['config_checkout_deliver_system_status'] = $this->request->post['config_checkout_deliver_system_status'];
        } else {
            $data['config_checkout_deliver_system_status'] = $this->config->get('config_checkout_deliver_system_status');
        }

        // Cache
        if (isset($this->request->post['config_cache_storage'])) {
            $data['config_cache_storage'] = $this->request->post['config_cache_storage'];
        } else {
            $data['config_cache_storage'] = $this->config->get('config_cache_storage', 'file');
        }

        if (isset($this->request->post['config_cache_lifetime'])) {
            $data['config_cache_lifetime'] = $this->request->post['config_cache_lifetime'];
        } else {
            $data['config_cache_lifetime'] = $this->config->get('config_cache_lifetime', 86400);
        }

        if (isset($this->request->post['config_pagecache'])) {
            $data['config_pagecache'] = $this->request->post['config_pagecache'];
        } else {
            $data['config_pagecache'] = $this->config->get('config_pagecache');
        }

        if (isset($this->request->post['config_cache_clear'])) {
            $data['config_cache_clear'] = $this->request->post['config_cache_clear'];
        } else {
            $data['config_cache_clear'] = $this->config->get('config_cache_clear');
        }

        if (isset($this->request->post['config_pagecache_exclude'])) {
            $data['config_pagecache_exclude'] = $this->request->post['config_pagecache_exclude'];
        } elseif ($this->config->get('config_pagecache_exclude')) {
            $ex_paths = '';

            foreach (explode(',', $this->config->get('config_pagecache_exclude')) as $id) {
                $id = trim($id);

                if ($id) {
                    $ex_paths .= $id . "\n";
                }
            }

            $data['config_pagecache_exclude'] = $ex_paths;
        } else {
            $data['config_pagecache_exclude'] = '';
        }

        // Security
        if (isset($this->request->post['config_secure'])) {
            $data['config_secure'] = $this->request->post['config_secure'];
        } else {
            $data['config_secure'] = $this->config->get('config_secure');
        }

        if (isset($this->request->post['config_encryption'])) {
            $data['config_encryption'] = $this->request->post['config_encryption'];
        } else {
            $data['config_encryption'] = $this->config->get('config_encryption');
        }

        if (isset($this->request->post['config_sec_admin_login'])) {
            $data['config_sec_admin_login'] = $this->request->post['config_sec_admin_login'];
        } else {
            $data['config_sec_admin_login'] = $this->config->get('config_sec_admin_login');
        }

        if (isset($this->request->post['config_sec_admin_keyword'])) {
            $data['config_sec_admin_keyword'] = $this->request->post['config_sec_admin_keyword'];
        } else {
            $data['config_sec_admin_keyword'] = $this->config->get('config_sec_admin_keyword');
        }

        if (isset($this->request->post['config_sec_lfi'])) {
            $data['config_sec_lfi'] = $this->request->post['config_sec_lfi'];
        } else {
            $data['config_sec_lfi'] = $this->config->get('config_sec_lfi', []);
        }

        if (isset($this->request->post['config_sec_rfi'])) {
            $data['config_sec_rfi'] = $this->request->post['config_sec_rfi'];
        } else {
            $data['config_sec_rfi'] = $this->config->get('config_sec_rfi', []);
        }

        if (isset($this->request->post['config_sec_sql'])) {
            $data['config_sec_sql'] = $this->request->post['config_sec_sql'];
        } else {
            $data['config_sec_sql'] = $this->config->get('config_sec_sql', []);
        }

        if (isset($this->request->post['config_sec_xss'])) {
            $data['config_sec_xss'] = $this->request->post['config_sec_xss'];
        } else {
            $data['config_sec_xss'] = $this->config->get('config_sec_xss', []);
        }

        if (isset($this->request->post['config_sec_htmlpurifier'])) {
            $data['config_sec_htmlpurifier'] = $this->request->post['config_sec_htmlpurifier'];
        } else {
            $data['config_sec_htmlpurifier'] = $this->config->get('config_sec_htmlpurifier');
        }

        if (isset($this->request->post['config_file_max_size'])) {
            $data['config_file_max_size'] = $this->request->post['config_file_max_size'];
        } else {
            $data['config_file_max_size'] = $this->config->get('config_file_max_size', 300000);
        }

        if (isset($this->request->post['config_file_ext_allowed'])) {
            $data['config_file_ext_allowed'] = $this->request->post['config_file_ext_allowed'];
        } else {
            $data['config_file_ext_allowed'] = $this->config->get('config_file_ext_allowed');
        }

        if (isset($this->request->post['config_file_mime_allowed'])) {
            $data['config_file_mime_allowed'] = $this->request->post['config_file_mime_allowed'];
        } else {
            $data['config_file_mime_allowed'] = $this->config->get('config_file_mime_allowed');
        }

        if (isset($this->request->post['config_google_captcha_public'])) {
            $data['config_google_captcha_public'] = $this->request->post['config_google_captcha_public'];
        } else {
            $data['config_google_captcha_public'] = $this->config->get('config_google_captcha_public');
        }

        if (isset($this->request->post['config_google_captcha_secret'])) {
            $data['config_google_captcha_secret'] = $this->request->post['config_google_captcha_secret'];
        } else {
            $data['config_google_captcha_secret'] = $this->config->get('config_google_captcha_secret');
        }

        if (isset($this->request->post['config_google_api_key'])) {
            $data['config_google_api_key'] = $this->request->post['config_google_api_key'];
        } else {
            $data['config_google_api_key'] = $this->config->get('config_google_api_key');
        }

        if (isset($this->request->post['config_google_server_api_key'])) {
            $data['config_google_server_api_key'] = $this->request->post['config_google_server_api_key'];
        } else {
            $data['config_google_server_api_key'] = $this->config->get('config_google_server_api_key');
        }

        if (isset($this->request->post['config_google_captcha_status'])) {
            $data['config_google_captcha_status'] = $this->request->post['config_google_captcha_status'];
        } else {
            $data['config_google_captcha_status'] = $this->config->get('config_google_captcha_status');
        }

        // Fraud
        if (isset($this->request->post['config_fraud_detection'])) {
            $data['config_fraud_detection'] = $this->request->post['config_fraud_detection'];
        } else {
            $data['config_fraud_detection'] = $this->config->get('config_fraud_detection');
        }

        if (isset($this->request->post['config_fraud_key'])) {
            $data['config_fraud_key'] = $this->request->post['config_fraud_key'];
        } else {
            $data['config_fraud_key'] = $this->config->get('config_fraud_key');
        }

        if (isset($this->request->post['config_fraud_score'])) {
            $data['config_fraud_score'] = $this->request->post['config_fraud_score'];
        } else {
            $data['config_fraud_score'] = $this->config->get('config_fraud_score');
        }

        if (isset($this->request->post['config_fraud_status_id'])) {
            $data['config_fraud_status_id'] = $this->request->post['config_fraud_status_id'];
        } else {
            $data['config_fraud_status_id'] = $this->config->get('config_fraud_status_id');
        }

        if (isset($this->request->post['config_konduto_status_id'])) {
            $data['config_konduto_status_id'] = $this->request->post['config_konduto_status_id'];
        } else {
            $data['config_konduto_status_id'] = $this->config->get('config_konduto_status_id');
        }

        if (isset($this->request->post['config_shared'])) {
            $data['config_shared'] = $this->request->post['config_shared'];
        } else {
            $data['config_shared'] = $this->config->get('config_shared');
        }

        if (isset($this->request->post['config_robots'])) {
            $data['config_robots'] = $this->request->post['config_robots'];
        } else {
            $data['config_robots'] = $this->config->get('config_robots');
        }

        if (isset($this->request->post['config_maintenance'])) {
            $data['config_maintenance'] = $this->request->post['config_maintenance'];
        } else {
            $data['config_maintenance'] = $this->config->get('config_maintenance');
        }

        if (isset($this->request->post['config_coming_soon'])) {
            $data['config_coming_soon'] = $this->request->post['config_coming_soon'];
        } else {
            $data['config_coming_soon'] = $this->config->get('config_coming_soon');
        }

        if (isset($this->request->post['config_password'])) {
            $data['config_password'] = $this->request->post['config_password'];
        } else {
            $data['config_password'] = $this->config->get('config_password');
        }

        if (isset($this->request->post['config_compression'])) {
            $data['config_compression'] = $this->request->post['config_compression'];
        } else {
            $data['config_compression'] = $this->config->get('config_compression');
        }

        if (isset($this->request->post['config_debug_system'])) {
            $data['config_debug_system'] = $this->request->post['config_debug_system'];
        } else {
            $data['config_debug_system'] = $this->config->get('config_debug_system');
        }

        if (isset($this->request->post['config_error_display'])) {
            $data['config_error_display'] = $this->request->post['config_error_display'];
        } else {
            $data['config_error_display'] = $this->config->get('config_error_display');
        }

        if (isset($this->request->post['config_error_log'])) {
            $data['config_error_log'] = $this->request->post['config_error_log'];
        } else {
            $data['config_error_log'] = $this->config->get('config_error_log');
        }

        if (isset($this->request->post['config_error_filename'])) {
            $data['config_error_filename'] = $this->request->post['config_error_filename'];
        } else {
            $data['config_error_filename'] = $this->config->get('config_error_filename');
        }

        if (isset($this->request->post['config_auto_approval_product'])) {
            $data['config_auto_approval_product'] = $this->request->post['config_auto_approval_product'];
        } else {
            $data['config_auto_approval_product'] = $this->config->get('config_auto_approval_product');
        }

        if (isset($this->request->post['config_inclusiv_tax'])) {
            $data['config_inclusiv_tax'] = $this->request->post['config_inclusiv_tax'];
        } else {
            $data['config_inclusiv_tax'] = $this->config->get('config_inclusiv_tax');
        }

        if (isset($this->request->post['config_sms_protocol'])) {
            $data['config_sms_protocol'] = $this->request->post['config_sms_protocol'];
        } else {
            $data['config_sms_protocol'] = $this->config->get('config_sms_protocol');
        }

        if (isset($this->request->post['config_sms_sender_id'])) {
            $data['config_sms_sender_id'] = $this->request->post['config_sms_sender_id'];
        } else {
            $data['config_sms_sender_id'] = $this->config->get('config_sms_sender_id');
        }

        if (isset($this->request->post['config_sms_token'])) {
            $data['config_sms_token'] = $this->request->post['config_sms_token'];
        } else {
            $data['config_sms_token'] = $this->config->get('config_sms_token');
        }

        if (isset($this->request->post['config_sms_number'])) {
            $data['config_sms_number'] = $this->request->post['config_sms_number'];
        } else {
            $data['config_sms_number'] = $this->config->get('config_sms_number');
        }

        if (isset($this->request->post['config_zenvia_sms_sender_id'])) {
            $data['config_zenvia_sms_sender_id'] = $this->request->post['config_zenvia_sms_sender_id'];
        } else {
            $data['config_zenvia_sms_sender_id'] = $this->config->get('config_zenvia_sms_sender_id');
        }

        if (isset($this->request->post['config_zenvia_sms_token'])) {
            $data['config_zenvia_sms_token'] = $this->request->post['config_zenvia_sms_token'];
        } else {
            $data['config_zenvia_sms_token'] = $this->config->get('config_zenvia_sms_token');
        }

        if (isset($this->request->post['config_zenvia_sms_number'])) {
            $data['config_zenvia_sms_number'] = $this->request->post['config_zenvia_sms_number'];
        } else {
            $data['config_zenvia_sms_number'] = $this->config->get('config_zenvia_sms_number');
        }

        if (isset($this->request->post['config_wayhub_sms_sender_id'])) {
            $data['config_wayhub_sms_sender_id'] = $this->request->post['config_wayhub_sms_sender_id'];
        } else {
            $data['config_wayhub_sms_sender_id'] = $this->config->get('config_wayhub_sms_sender_id');
        }

        if (isset($this->request->post['config_wayhub_sms_token'])) {
            $data['config_wayhub_sms_token'] = $this->request->post['config_wayhub_sms_token'];
        } else {
            $data['config_wayhub_sms_token'] = $this->config->get('config_wayhub_sms_token');
        }

        if (isset($this->request->post['config_wayhub_sms_number'])) {
            $data['config_wayhub_sms_number'] = $this->request->post['config_wayhub_sms_number'];
        } else {
            $data['config_wayhub_sms_number'] = $this->config->get('config_wayhub_sms_number');
        }

        if (isset($this->request->post['config_uwaziimobile_sms_token'])) {
            $data['config_uwaziimobile_sms_token'] = $this->request->post['config_uwaziimobile_sms_token'];
        } else {
            $data['config_uwaziimobile_sms_token'] = $this->config->get('config_uwaziimobile_sms_token');
        }

        if (isset($this->request->post['config_uwaziimobile_sms_sender_id'])) {
            $data['config_uwaziimobile_sms_sender_id'] = $this->request->post['config_uwaziimobile_sms_sender_id'];
        } else {
            $data['config_uwaziimobile_sms_sender_id'] = $this->config->get('config_uwaziimobile_sms_sender_id');
        }

        if (isset($this->request->post['config_uwaziimobile_sms_number'])) {
            $data['config_uwaziimobile_sms_number'] = $this->request->post['config_uwaziimobile_sms_number'];
        } else {
            $data['config_uwaziimobile_sms_number'] = $this->config->get('config_uwaziimobile_sms_number');
        }

        if (isset($this->request->post['config_africastalking_sms_username'])) {
            $data['config_africastalking_sms_username'] = $this->request->post['config_africastalking_sms_username'];
        } else {
            $data['config_africastalking_sms_username'] = $this->config->get('config_africastalking_sms_username');
        }

        if (isset($this->request->post['config_africastalking_sms_api_key'])) {
            $data['config_africastalking_sms_api_key'] = $this->request->post['config_africastalking_sms_api_key'];
        } else {
            $data['config_africastalking_sms_api_key'] = $this->config->get('config_africastalking_sms_api_key');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('setting/setting.tpl', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'setting/setting')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['config_name']) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if (empty($this->request->post['config_member_account_fee']) || !is_numeric($this->request->post['config_member_account_fee'])) {
            $this->error['member_account_fee'] = $this->language->get('error_member_account_fee');
        }

        /* if (!$this->request->post['config_shopper_group_ids']) {
          $this->error['shopper_group_ids'] = $this->language->get('error_shopper_group_ids');
          } */

        if (!$this->request->post['config_vendor_group_ids']) {
            $this->error['vendor_group_ids'] = $this->language->get('error_vendor_group_ids');
        }


        if (!$this->request->post['config_store_latitude']) {
            $this->error['store_latitude'] = $this->language->get('error_store_latitude');
        }

        if (!$this->request->post['config_store_longitude']) {
            $this->error['store_longitude'] = $this->language->get('error_store_longitude');
        }
        if (!$this->request->post['config_amitruck_url']) {
            $this->error['amitruck_url'] = $this->language->get('error_amitruck_url');
        }
        if (!$this->request->post['config_amitruck_clientId']) {
            $this->error['amitruck_clientId'] = $this->language->get('error_amitruck_clientId');
        }

        if (!$this->request->post['config_amitruck_clientSecret']) {
            $this->error['amitruck_clientSecret'] = $this->language->get('error_amitruck_clientSecret');
        }

        if (!$this->request->post['config_account_manager_group_id']) {
            $this->error['account_manager_group_id'] = $this->language->get('error_account_manager_group_id');
        }

        if (!$this->request->post['config_customer_experience_group_id']) {
            $this->error['customer_experience_group_id'] = $this->language->get('error_customer_experience_group_id');
        }

        if (!$this->request->post['config_farmer_group_id']) {
            $this->error['farmer_group_id'] = $this->language->get('error_farmer_group_id');
        }

        if (!$this->request->post['config_supplier_group_id']) {
            $this->error['supplier_group_id'] = $this->language->get('error_supplier_group_id');
        }

        if (!$this->request->post['config_active_store_id']) {
            $this->error['active_store_id'] = $this->language->get('error_active_store_id');
        }

        if (!$this->request->post['config_active_store_minimum_order_amount']) {
            $this->error['active_store_minimum_order_amount'] = $this->language->get('error_active_store_minimum_order_amount');
        }

        if ((utf8_strlen($this->request->post['config_owner']) < 3) || (utf8_strlen($this->request->post['config_owner']) > 64)) {
            $this->error['owner'] = $this->language->get('error_owner');
        }

        if ((utf8_strlen($this->request->post['config_address']) < 3) || (utf8_strlen($this->request->post['config_address']) > 256)) {
            $this->error['address'] = $this->language->get('error_address');
        }

        if ((utf8_strlen($this->request->post['config_email']) > 96) || !filter_var($this->request->post['config_email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if ((utf8_strlen($this->request->post['config_from_email']) > 96) || !filter_var($this->request->post['config_from_email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['from_email'] = $this->language->get('error_from_email');
        }

        if ((utf8_strlen($this->request->post['config_telephone']) < 3) || (utf8_strlen($this->request->post['config_telephone']) > 32)) {
            $this->error['telephone'] = $this->language->get('error_telephone');
        }

        if (!$this->request->post['config_meta_title']) {
            $this->error['meta_title'] = $this->language->get('error_meta_title');
        }

        if (!empty($this->request->post['config_customer_group_display']) && !in_array($this->request->post['config_customer_group_id'], $this->request->post['config_customer_group_display'])) {
            $this->error['customer_group_display'] = $this->language->get('error_customer_group_display');
        }

        if ($this->request->post['config_login_attempts'] < 1) {
            $this->error['login_attempts'] = $this->language->get('error_login_attempts');
        }

        if (!isset($this->request->post['config_shipped_status'])) {
            $this->error['shipped_status'] = $this->language->get('error_shipped_status');
        }

        if (!isset($this->request->post['config_processing_status'])) {
            $this->error['processing_status'] = $this->language->get('error_processing_status');
        }

        if (!isset($this->request->post['config_refund_status'])) {
            $this->error['refund_status'] = $this->language->get('error_refund_status');
        }

        if (!isset($this->request->post['config_ready_for_pickup_status'])) {
            $this->error['ready_for_pickup_status'] = $this->language->get('error_ready_for_pickup_status');
        }

        if (!isset($this->request->post['config_complete_status'])) {
            $this->error['complete_status'] = $this->language->get('error_complete_status');
        }

        if (!$this->request->post['config_app_image_category_width'] || !$this->request->post['config_app_image_category_height']) {
            $this->error['image_app_category'] = $this->language->get('error_app_image_category');
        }

        if (!$this->request->post['config_app_image_thumb_width'] || !$this->request->post['config_app_image_thumb_height']) {
            $this->error['image_app_thumb'] = $this->language->get('error_app_image_thumb');
        }

        if (!$this->request->post['config_app_image_product_width'] || !$this->request->post['config_app_image_product_height']) {
            $this->error['image_app_product'] = $this->language->get('error_app_image_product');
        }

        if (!$this->request->post['config_app_image_cart_width'] || !$this->request->post['config_app_image_cart_height']) {
            $this->error['image_app_cart'] = $this->language->get('error_app_image_cart');
        }

        if (!$this->request->post['config_app_image_location_width'] || !$this->request->post['config_app_image_location_height']) {
            $this->error['image_app_location'] = $this->language->get('error_app_image_location');
        }

        if (!$this->request->post['config_app_image_location_width'] || !$this->request->post['config_app_image_location_height']) {
            $this->error['image_app_location'] = $this->language->get('error_app_image_location');
        }

        if (!$this->request->post['config_app_notice_image_location_width'] || !$this->request->post['config_app_notice_image_location_height']) {
            $this->error['notice_image_app_location'] = $this->language->get('error_app_image_location');
        }

        if (!$this->request->post['config_image_category_width'] || !$this->request->post['config_image_category_height']) {
            $this->error['image_category'] = $this->language->get('error_image_category');
        }

        if (!$this->request->post['config_image_thumb_width'] || !$this->request->post['config_image_thumb_height']) {
            $this->error['image_thumb'] = $this->language->get('error_image_thumb');
        }

        if (!$this->request->post['config_zoomimage_thumb_width'] || !$this->request->post['config_zoomimage_thumb_height']) {
            $this->error['zoomimage_thumb'] = $this->language->get('error_zoomimage_thumb');
        }

        if (!$this->request->post['config_image_product_width'] || !$this->request->post['config_image_product_height']) {
            $this->error['image_product'] = $this->language->get('error_image_product');
        }

        if (!$this->request->post['config_image_cart_width'] || !$this->request->post['config_image_cart_height']) {
            $this->error['image_cart'] = $this->language->get('error_image_cart');
        }

        if (!$this->request->post['config_image_location_width'] || !$this->request->post['config_image_location_height']) {
            $this->error['image_location'] = $this->language->get('error_image_location');
        }

        if (!$this->request->post['config_error_filename']) {
            $this->error['error_error_filename'] = $this->language->get('error_error_filename');
        } else {
            $this->request->post['config_error_filename'] = str_replace(['../', '..\\', '..'], '', $this->request->post['config_error_filename']);
        }

        if (!$this->request->post['config_product_limit']) {
            $this->error['product_limit'] = $this->language->get('error_limit');
        }

        if (!$this->request->post['config_app_product_limit']) {
            $this->error['product_app_limit'] = $this->language->get('error_limit');
        }

        if (!$this->request->post['config_product_description_length']) {
            $this->error['product_description_length'] = $this->language->get('error_limit');
        }

        if (!$this->request->post['config_limit_admin']) {
            $this->error['limit_admin'] = $this->language->get('error_limit');
        }

        if ((utf8_strlen($this->request->post['config_encryption']) < 3) || (utf8_strlen($this->request->post['config_encryption']) > 32)) {
            $this->error['encryption'] = $this->language->get('error_encryption');
        }

        if (!$this->request->post['config_cache_lifetime']) {
            $this->error['cache_lifetime'] = $this->language->get('error_cache_lifetime');
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        //echo "<pre>";print_r($this->error);die;

        return !$this->error;
    }

    public function template() {
        if ($this->request->server['HTTPS']) {
            $server = HTTPS_CATALOG;
        } else {
            $server = HTTP_CATALOG;
        }

        if (is_file(DIR_IMAGE . 'templates/' . basename($this->request->get['template']) . '.png')) {
            $this->response->setOutput($server . 'image/templates/' . basename($this->request->get['template']) . '.png');
        } else {
            $this->response->setOutput($server . 'image/no_image.png');
        }
    }

    public function country() {
        $json = [];

        $this->load->model('localisation/country');

        $country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

        if ($country_info) {
            $this->load->model('localisation/zone');

            $json = [
                'country_id' => $country_info['country_id'],
                'name' => $country_info['name'],
                'iso_code_2' => $country_info['iso_code_2'],
                'iso_code_3' => $country_info['iso_code_3'],
                'address_format' => $country_info['address_format'],
                'postcode_required' => $country_info['postcode_required'],
                'zone' => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
                'status' => $country_info['status'],
            ];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function clearCache() {
        $json = [];

        if ($this->cache->clear()) {
            $json['message'] = $this->language->get('text_cache_cleared');
        } else {
            $json['error'] = $this->language->get('error_cache_not_cleared');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function testSms() {
        //return "webf";
        $log = new Log('error.log');
        $log->write('sms twilio 2');
        $log->write('testSms');
        $log->write($this->request->post);

        $country_prefix = $this->config->get('config_telephone_code');

        if (!empty($this->request->post['to']) || !empty($this->request->post['message'])) {
            $to = $country_prefix . '' . $this->request->post['to'];
            $message = $this->request->post['message'];
            $log->write($to);
            $log->write($message);
            $result = $this->sendmessage($to, $message);
            if ($result['status']) {
                echo json_encode(['status' => 1, 'message' => $result['message']]);
            } else {
                echo json_encode(['status' => 0, 'message' => $result['message']]);
            }
        } else {
            $log->write('sms twilio 2 end');
            //$log->write($to);
            echo json_encode(['status' => 0, 'message' => 'Something went wrong']);
        }
    }

    public function sendmessage($to, $message) {
        $log = new Log('error.log');
        $log->write($this->config->get('config_sms_protocol'));

        $result['status'] = false;
        $result['message'] = 'Failed';

        if ('awssns' == $this->config->get('config_sms_protocol')) {
            $log->write('AWSNS');
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
            $log->write('AWS SNS RESULT');
            $log->write($result);
            $log->write('AWS SNS RESULT');
        } elseif ('africastalking' == $this->config->get('config_sms_protocol')) {
            $username = $this->config->get('config_africastalking_sms_username');
            $apiKey = $this->config->get('config_africastalking_sms_api_key');
            $AT = new \AfricasTalking\SDK\AfricasTalking($username, $apiKey);
            $sms = $AT->sms();

            $sms->send([
                'to' => $this->formatPhoneNumber($to),
                'message' => $message,
            ]);

            $log->write("setting.php Africa's Talking Sending SMS " . $message . ' to ' . $to);
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
                        $to,
                        [
                            'from' => $from,
                            //'from' => '+19789864215',
                            'body' => $message,
                        ]
                );
            } catch (Exception $exception) {
                $log->write($exception);
                $log->write('exception');

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
            //c3VwZXIub25saW5lLndlYjpydFdjSVVZUENO
            /* $headr = array();
              $headr[] = 'Accept : application/json';
              $headr[] = 'Content-type: application/json';
              $headr[] = 'Authorization: Basic '.$authToken; */

            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, $apiEndPoint);
            //curl_setopt($curl, CURLOPT_URL,"https://api-rest.zenvia360.com.br/services/send-sms");
            //https://api-rest.zenvia360.com.br/services/send-sms
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Accept: application/json', 'Content-type: application/json', 'Authorization: Basic ' . $authToken]);

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postData));

            $response = curl_exec($curl);
            $response = json_decode($response, true);

            if (isset($response['sendSmsResponse'])) {
                if (is_array($response['sendSmsResponse'])) {
                    $result['status'] = false;

                    if (00 == $response['sendSmsResponse']['statusCode']) {
                        $result['status'] = true;
                    }

                    $result['message'] = $response['sendSmsResponse']['detailDescription'];

                    return $result;
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

            //$msg = 'Your OTP is not required. :) Regards, Abhishek ';
            $msg = $message;

            $url = 'http://login.smsgatewayhub.com/smsapi/pushsms.aspx?user=' . $username . '&pwd=' . $password . '&to=' . $to . '&sid=' . $sender_id . '&msg=' . urlencode($msg) . '&fl=0&gwid=2';

            //echo "<pre>";print_r($url);die;
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

            //echo "<pre>";print_r($resp);die;
            // Close request to clear up some resources
            curl_close($curl);
        }

        $result['status'] = true;
        $result['message'] = 'Successfully sent';

        return $result;
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

    public function setting_email() {
        if (('POST' == $this->request->server['REQUEST_METHOD'])) {// 
            if ($this->validateEmail()) {
                $this->load->model('setting/setting');
                // echo "<pre>";print_r($this->request->post);die;
                $this->model_setting_setting->editEmailSettings($this->request->post);
                $this->session->data['success'] = "success : You have modified settings";

                if (isset($this->request->post['button']) and 'save' == $this->request->post['button']) {
                    $this->response->redirect($this->url->link('setting/setting/setting_email', 'token=' . $this->session->data['token'], 'SSL'));
                }
                $this->response->redirect($this->url->link('setting/setting/setting_cancel', 'token=' . $this->session->data['token'], 'SSL'));
            } else {
                if (isset($this->error['warning'])) {
                    $data['error_warning'] = $this->error['warning'];
                } else {
                    $data['error_warning'] = '';
                }


                if (isset($this->error['email'])) {
                    $data['error_warning'] = $this->error['email'];
                } else {
                    $data['error_warning'] = '';
                }
            }
        }


        $data['token'] = $this->session->data['token'];
        $data['breadcrumbs'] = [];
        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('setting/setting/setting_email', 'token=' . $this->session->data['token'], 'SSL'),
        ];

        $this->getEmailForm();
    }

    public function getEmailForm() {
        // echo "<pre>";print_r($this->error);die;
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }


        if (isset($this->error['email'])) {
            $data['error_warning'] = $this->error['email'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }
        $this->document->setTitle("Email Settings");
        $this->load->model('setting/setting');
        $email_info = $this->model_setting_setting->getEmailSettings();
        // echo "<pre>";print_r($email_info);die;
        if (isset($this->request->post['config_consolidatedorder'])) {
            $data['config_consolidatedorder'] = $this->request->post['config_consolidatedorder'];
        } elseif (isset($email_info[0]['value'])) {
            $data['config_consolidatedorder'] = $email_info[0]['value'];
        } else {
            $data['config_consolidatedorder'] = '';
        }

        if (isset($this->request->post['config_careers'])) {
            $data['config_careers'] = $this->request->post['config_careers'];
        } elseif (isset($email_info[1]['value'])) {
            $data['config_careers'] = $email_info[1]['value'];
        } else {
            $data['config_careers'] = '';
        }

        if (isset($this->request->post['config_stockout'])) {
            $data['config_stockout'] = $this->request->post['config_stockout'];
        } elseif (isset($email_info[2]['value'])) {
            $data['config_stockout'] = $email_info[2]['value'];
        } else {
            $data['config_stockout'] = '';
        }


        if (isset($this->request->post['config_issue'])) {
            $data['config_issue'] = $this->request->post['config_issue'];
        } elseif (isset($email_info[3]['value'])) {
            $data['config_issue'] = $email_info[3]['value'];
        } else {
            $data['config_issue'] = '';
        }


        if (isset($this->request->post['config_financeteam'])) {
            $data['config_financeteam'] = $this->request->post['config_financeteam'];
        } elseif (isset($email_info[4]['value'])) {
            $data['config_financeteam'] = $email_info[4]['value'];
        } else {
            $data['config_financeteam'] = '';
        }

        if (isset($this->request->post['config_meatcheckingteam'])) {
            $data['config_meatcheckingteam'] = $this->request->post['config_meatcheckingteam'];
        } elseif (isset($email_info[5]['value'])) {
            $data['config_meatcheckingteam'] = $email_info[5]['value'];
        } else {
            $data['config_meatcheckingteam'] = '';
        }


        $this->document->setTitle("Email Settings");
        $this->load->model('setting/setting');

        $data['action'] = $this->url->link('setting/setting/setting_email', 'token=' . $this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('setting/setting/setting_cancel', 'token=' . $this->session->data['token'], 'SSL');

        $data['token'] = $this->session->data['token'];
        $data['heading_title'] = $this->language->get('heading_title');
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        // echo "<pre>";print_r($data);die;

        $this->response->setOutput($this->load->view('setting/setting_email.tpl', $data));
    }

    public function setting_cancel() {
        $this->getEmailForm();
    }

    protected function validateEmail() {
        if (!$this->user->hasPermission('modify', 'setting/setting')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        // foreach($emails as $email){
        //     if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        //        $error['Other'][] = "<div class=error>$email is not valid Email.</div>";
        //     }else{
        //        $filtered_emails[] = $email
        //     }
        //   }
        // if ((utf8_strlen($this->request->post['config_consolidatedorder']) > 96) || !filter_var($this->request->post['config_consolidatedorder'], FILTER_VALIDATE_EMAIL)) {
        if ((strpos($this->request->post['config_consolidatedorder'], "@") == false)) {
            $this->error['email'] = "Please enter correct Email";
        }

        if ((strpos($this->request->post['config_careers'], "@") == false)) {
            $this->error['email'] = "Please enter correct Email";
        }

        if ((strpos($this->request->post['config_stockout'], "@") == false)) {
            $this->error['email'] = "Please enter correct Email";
        }

        if ((strpos($this->request->post['config_issue'], "@") == false)) {
            $this->error['email'] = "Please enter correct Email";
        }


        if ((strpos($this->request->post['config_financeteam'], "@") == false)) {
            $this->error['email'] = "Please enter correct Email";
        }


        if ((strpos($this->request->post['config_meatcheckingteam'], "@") == false)) {
            $this->error['email'] = "Please enter correct Email";
        }



        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        //echo "<pre>";print_r($this->error);die;

        return !$this->error;
    }

}
