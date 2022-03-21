<?php

require_once DIR_SYSTEM . '/vendor/konduto/vendor/autoload.php';

//require_once DIR_SYSTEM.'/vendor/mpesa-php-sdk-master/vendor/autoload.php';

use paragraph1\phpFCM\Client;

require_once DIR_SYSTEM . '/vendor/fcp-php/autoload.php';

require DIR_SYSTEM . 'vendor/Facebook/autoload.php';

require_once DIR_APPLICATION . '/controller/api/settings.php';

class ControllerAccountAccount extends Controller {

    private $error = [];

    public function createDSrequest() {
        $log = new Log('dserror.log');
        $log->write('in createDSrequest');
        $this->load->model('account/order');
        $this->load->model('checkout/order');

        $pickupStatus = $this->config->get('config_ready_for_pickup_status');

        $orders = $this->model_account_order->getNonDSCreatedOrders($pickupStatus);

        //echo "<pre>";print_r($orders);die;
        //$log->write($orders);

        foreach ($orders as $order_info) {
            $orderhistory = $this->model_account_order->getLatestOrderHistories($order_info['order_id']);

            if (!$orderhistory) {
                //echo "<pre>";print_r($orderhistory);die;
                continue;
            }

            $ReadyForPickupStatus = false;

            if (is_array($pickupStatus) && count($pickupStatus) > 0) {
                foreach ($pickupStatus as $pickupStat) {
                    if ($order_info['order_status_id'] == $pickupStat) {
                        $ReadyForPickupStatus = true;
                    }
                }
            }

            if ($ReadyForPickupStatus) {
                $log->write('ReadyForPickupStatus if');
                $deliverSystemStatus = $this->config->get('config_deliver_system_status');

                $checkoutDeliverSystemStatus = $this->config->get('config_checkout_deliver_system_status');

                $deliverSystemStatusForShipping = false;

                $log->write($deliverSystemStatus . 'erf' . $checkoutDeliverSystemStatus);

                if ($deliverSystemStatus && !$checkoutDeliverSystemStatus) {
                    $log->write('ReadyForPickupStatus elsex yes');
                    $allowedShippingMethods = $this->config->get('config_delivery_shipping_methods_status');

                    $log->write($allowedShippingMethods);

                    if (is_array($allowedShippingMethods) && count($allowedShippingMethods) > 0) {
                        foreach ($allowedShippingMethods as $method) {
                            /* if($order_info['shipping_code'] == $method.".".$method) {
                              $deliverSystemStatus = true;
                              $deliverSystemStatusForShipping = true;
                              } */

                            $p = explode('.', $order_info['shipping_code']);

                            if ($p[0] == $method) {
                                $deliverSystemStatus = true;
                                $deliverSystemStatusForShipping = true;
                            }
                        }
                    }
                } else {
                    $deliverSystemStatus = false;
                }

                if ($deliverSystemStatus && $deliverSystemStatusForShipping) {
                    $log->write('createDeliveryRequest creating'); //die;
                    $this->model_checkout_order->createDeliveryRequest($order_info['order_id'], $order_info['order_status_id']);
                } else {
                    $log->write('deliverSystemStatus elsex');
                }
            }
        }
    }

    public function updateGetAmount($order_id, $total) {
        $log = new Log('error.log');

        $log->write('inside updateGetAmount');

        $this->load->model('account/order');
        $this->load->model('api/checkout');

        $order_info = $this->model_api_checkout->getOrder($order_id);

        //echo "<pre>";print_r($order_info);die;
        $deliveryAlreadyCreated = $this->model_account_order->getOrderDSDeliveryId($order_id);

        if ($order_info && $deliveryAlreadyCreated && 'cod' == $order_info['payment_code']) {
            $data['body'] = [
                'manifest_id' => $deliveryAlreadyCreated, //order_id,
                //'total_price' => (int) round($new_total),
                'get_amount' => (int) round($total),
                    //'total_type' => $total_type,
                    //'manifest_data' => json_encode($data['products'])
            ];

            $log->write($data['body']);

            $data['email'] = $this->config->get('config_delivery_username');
            $data['password'] = $this->config->get('config_delivery_secret');
            $response = $this->load->controller('deliversystem/deliversystem/getToken', $data);

            $log->write('token');
            $log->write($response);
            if ($response['status']) {
                $data['tokens'] = $response['token'];
                $res = $this->load->controller('deliversystem/deliversystem/updateDelivery', $data);
                $log->write('reeponse');
                $log->write($res);
            }
        }

        return true;
    }

    public function index() {
        /* if (date_default_timezone_get()) {
          echo 'date_default_timezone_set: ' . date_default_timezone_get() . '<br />';
          }

          if (ini_get('date.timezone')) {
          echo 'date.timezone: ' . ini_get('date.timezone');
          }
          die; */
        /* $this->updateGetAmount(712,22);
          die; */
        /* return $this->response->setOutput($this->load->view('default/template/account/testing.tpl'));
          die; */
        /* $order_id = 712;
          $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "return WHERE order_id = '" . (int)$order_id . "' and return_status_id='".$this->config->get('config_complete_return_status_id')."'");

          echo "<pre>";print_r($query->row['total']);die; */

        /* $code = 'cod';
          $c = $this->getPaymentName($code);

          echo "<pre>";print_r($c);die; */

        /* $code = 'normal.normal';
          $store_id = 8;
          $c = $this->getShippingName($code,$store_id);


          echo "<pre>";print_r($c);die; */

        $data['kondutoStatus'] = $this->config->get('config_konduto_status');

        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        $data['redirect_coming'] = false;

        if (isset($_GET['redirect'])) {
            $this->session->data['checkout_redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
            $data['redirect_coming'] = true;
            //print_r($_GET['redirect']);
        }

        $this->document->addStyle('/front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_login.css');

        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/account', '', 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $this->load->language('account/edit');
        $this->load->language('account/account');

        $this->document->setTitle($this->language->get('heading_title'));
        //echo "<pre>";print_r($this->language->get('heading_title'));die;
        $this->load->model('account/customer');
        $this->load->model('account/changepass');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validate()) {
            $date = $this->request->post['dob'];

            $log = new Log('error.log');
            $log->write('account edit');

            if (isset($date)) {
                $date = DateTime::createFromFormat('d/m/Y', $date);
                $this->request->post['dob'] = $date->format('Y-m-d');
            } else {
                $this->request->post['dob'] = null;
            }

            $this->model_account_customer->editCustomer($this->request->post);

            if (isset($this->request->post['email']) && isset($this->request->post['password'])) {
                //echo "<pre>";print_r($this->request->post);die;
                $this->model_account_customer->editPassword($this->request->post['email'], $this->request->post['password']);
                $this->model_account_changepass->savepassword($this->customer->getId(), $this->request->post['password']);
                $this->model_account_changepass->deleteoldpassword($this->customer->getId());
            }

            //$this->session->data['success'] = $this->language->get('text_success');
            // } else {
            //     $this->session->data['success'] = $this->language->get('text_success');
            // }

            $this->session->data['success'] = $this->language->get('text_success');

            // Add to activity log
            $this->load->model('account/activity');

            $activity_data = [
                'customer_id' => $this->customer->getId(),
                'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
            ];
            $log->write('account edit1');

            $this->model_account_activity->addActivity('edit', $activity_data);

            $log->write('account edit2');

            if (isset($this->session->data['checkout_redirect'])) {
                $redirectTo = $this->session->data['checkout_redirect'];
                unset($this->session->data['checkout_redirect']);
                $this->response->redirect($redirectTo);
            }

            $this->response->redirect($this->url->link('account/account', '', 'SSL'));
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/account', '', 'SSL'),
        ];

        /* if (isset($this->session->data['success'])) {
          $data['success'] = $this->session->data['success'];

          unset($this->session->data['success']);
          } else {
          $data['success'] = '';
          } */

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
        $data['entry_national_id'] = $this->language->get('entry_national_id');
        $data['entry_date_of_birth'] = $this->language->get('entry_date_of_birth');
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
        $data['entry_kra'] = $this->language->get('entry_kra');
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
        $data['pezesha_loans'] = $this->url->link('account/pezeshaloans', '', 'SSL');
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
        
        if (isset($this->error['kra'])) {
            $data['error_kra'] = $this->error['kra'];
        } else {
            $data['error_kra'] = '';
        }

        if (isset($this->error['dob'])) {
            $data['error_dob'] = $this->error['dob'];
        } else {
            $data['error_dob'] = '';
        }

        if (isset($this->error['national_id'])) {
            $data['error_national_id'] = $this->error['national_id'];
        } else {
            $data['error_national_id'] = '';
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

        if (isset($this->request->post['dob']) && preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $this->request->post['dob'])) {
            $data['dob'] = date('d/m/Y', strtotime($this->request->post['dob']));
        } elseif (!empty($customer_info['dob'])) {
            $data['dob'] = date('d/m/Y', strtotime($customer_info['dob']));
        } else {
            $data['dob'] = NULL;
        }

        if (isset($this->request->post['firstname'])) {
            $data['firstname'] = $this->request->post['firstname'];
        } elseif (!empty($customer_info)) {
            $data['firstname'] = $customer_info['firstname'];
        } else {
            $data['firstname'] = '';
        }

        if (isset($this->request->post['national_id'])) {
            $data['national_id'] = $this->request->post['national_id'];
        } elseif (!empty($customer_info)) {
            $data['national_id'] = $customer_info['national_id'];
        } else {
            $data['national_id'] = '';
        }
        
        if (isset($this->request->post['kra'])) {
            $data['kra'] = $this->request->post['kra'];
        } elseif (!empty($customer_info)) {
            $data['kra'] = $customer_info['kra'];
        } else {
            $data['kra'] = '';
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

        $data['action'] = $this->url->link('account/account', '', 'SSL');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');

        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/information');

        $data['account_edit'] = $this->load->controller('account/edit');

        $data['home'] = $this->url->link('common/home/toHome');
        $data['telephone'] = $this->formatTelephone($this->customer->getTelephone());
        /* Added new params */
        $data['is_login'] = $this->customer->isLogged();
        $data['full_name'] = $this->customer->getFirstName();
        $data['text_my_cash'] = $this->language->get('text_my_cash');
        $data['text_my_wishlist'] = $this->language->get('text_my_wishlist');
        $data['label_my_address'] = $this->language->get('label_my_address');
        $data['contactus'] = $this->language->get('contactus');
        $data['text_cash'] = $this->language->get('text_cash');
        $data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');

        //echo "<pre>";print_r($data['telephone'] );die;
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/account.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/account.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/account/account.tpl', $data));
        }
    }

    public function adduser() {
        $log = new Log('error.log');
        $log->write('sub customer add');
        $this->load->model('account/customer');

        $this->request->post['dob'] = null;
        $this->request->post['parent'] = $this->customer->getId();
        $this->request->post['source'] = 'WEB';
        $sub_customer_id = $this->model_account_customer->addCustomer($this->request->post, true);

        if ($this->request->post['assign_order_approval'] == 'head_chef') {
            $log = new Log('error.log');
            $log->write('sub customer head_chef');
            $this->model_account_customer->UpdateOrderApprovalAccess($this->customer->getId(), $sub_customer_id, 1, 'head_chef');
        }

        if ($this->request->post['assign_order_approval'] == 'procurement_person') {
            $log = new Log('error.log');
            $log->write('sub customer procurement_person');
            $this->model_account_customer->UpdateOrderApprovalAccess($this->customer->getId(), $sub_customer_id, 1, 'procurement_person');
        }

        $_SESSION['success_msg'] = 'User added successfully!';

        // Add to activity log
        $this->load->model('account/activity');

        $activity_data = [
            'customer_id' => $this->customer->getId(),
            'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
            'sub_customers_id' => $sub_customer_id,
        ];
        $log->write('Add sub user account1');

        $this->model_account_activity->addActivity('sub_customer_created', $activity_data);

        $log->write('Add sub user account1');

        /* if(isset($this->session->data['checkout_redirect']) ) {
          $redirectTo = $this->session->data['checkout_redirect'];
          unset($this->session->data['checkout_redirect']);
          $this->response->redirect($redirectTo);
          } */

        $this->response->redirect($this->url->link('account/sub_users', '', 'SSL'));
    }

    public function getShippingName($code, $store_id) {
        $mp = explode('.', $code);

        //echo "<pre>";print_r($mp);die;

        if (!isset($mp[0])) {
            return '';
        }

        $code = $mp[0];

        $method_data = [];

        $this->load->model('extension/extension');

        $results = $this->model_extension_extension->getExtensions('shipping');

        $this->load->model('tool/image');

        $store_info = $this->model_tool_image->getStore($store_id);

        $delivery_by_owner = $store_info['delivery_by_owner'];

        $pickup_delivery = $store_info['store_pickup_timeslots'];

        $free_delivery_amount = $store_info['min_order_cod'];

        $store_total = 99999999999999999;
        $subtotal = $store_total;
        if ($store_total > $free_delivery_amount) {
            $cost = 0;
        } else {
            $cost = $store_info['cost_of_delivery'];
        }

        //echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            if ($this->config->get($result['code'] . '_status')) {
                if ('normal' == $result['code']) {
                    $this->load->model('shipping/' . $result['code']);
                    $quote = $this->{'model_shipping_' . $result['code']}->getApiQuote($cost, $store_info['name'], $subtotal, $subtotal);
                    if ($quote) {
                        $method_data[$result['code']] = [
                            'title' => $quote['title'],
                            'quote' => $quote['quote'],
                            'sort_order' => $quote['sort_order'],
                            'error' => $quote['error'],
                        ];
                    }
                } elseif ('express' == $result['code']) {
                    //echo "<pre>";print_r('express');die;
                    $this->load->model('shipping/' . $result['code']);
                    $quote = $this->{'model_shipping_' . $result['code']}->getApiQuote($cost, $store_info['name'], $subtotal, $subtotal);

                    if ($quote) {
                        $method_data[$result['code']] = [
                            'title' => $quote['title'],
                            'quote' => $quote['quote'],
                            'sort_order' => $quote['sort_order'],
                            'error' => $quote['error'],
                        ];
                    }
                } elseif ('store_delivery' == $result['code']) {
                    if ($delivery_by_owner) {
                        $this->load->model('shipping/' . $result['code']);
                        $quote = $this->{'model_shipping_' . $result['code']}->getApiQuote($cost, $store_info['name'], $subtotal, $subtotal);
                        if ($quote) {
                            $method_data[$result['code']] = [
                                'title' => 'Standard Delivery', //$quote['title'],
                                'quote' => $quote['quote'],
                                'sort_order' => $quote['sort_order'],
                                'error' => $quote['error'],
                            ];
                        }
                    }
                } elseif ('pickup' == $result['code']) {
                    if ($pickup_delivery) {
                        $this->load->model('shipping/' . $result['code']);
                        $quote = $this->{'model_shipping_' . $result['code']}->getApiQuote($cost, $store_info['name'], $subtotal, $subtotal);
                        if ($quote) {
                            $method_data[$result['code']] = [
                                'title' => $quote['title'],
                                'quote' => $quote['quote'],
                                'sort_order' => $quote['sort_order'],
                                'error' => $quote['error'],
                            ];
                        }
                    }
                } else {
                    //echo "<pre>";print_r('express');die;
                    $this->load->model('shipping/' . $result['code']);
                    $quote = $this->{'model_shipping_' . $result['code']}->getApiQuote($cost, $store_info['name'], $subtotal, $subtotal);
                    if ($quote) {
                        $method_data[$result['code']] = [
                            'title' => $quote['title'],
                            'quote' => $quote['quote'],
                            'sort_order' => $quote['sort_order'],
                            'error' => $quote['error'],
                        ];
                    }
                }
            }
        }

        //echo "<pre>";print_r($method_data);die;
        if (isset($method_data[$code])) {
            return $method_data[$code]['title'];
        } else {
            return '';
        }
    }

    public function refundToCustomerWallet($order_id) {
        $this->load->model('account/activity');
        $log = new Log('error.log');

        $order_info = $this->getOrder($order_id);
        $this->load->language('checkout/success');
        $refundToCustomerWallet = false;

        if ($order_info) {
            $allowedPaymentMethods = $this->config->get('config_payment_methods_status');

            //echo "<pre>";print_r($allowedPaymentMethods);die;
            $log->write($allowedPaymentMethods);

            if (is_array($allowedPaymentMethods) && count($allowedPaymentMethods) > 0) {
                foreach ($allowedPaymentMethods as $method) {
                    if ($order_info['payment_code'] == $method) {
                        $refundToCustomerWallet = true;
                    }
                }
            }

            if ($refundToCustomerWallet) {
                $log->write('refundToCustomerWallet');
                //referee points below
                $description = 'Refund of order#' . $order_id;
                $this->model_account_activity->addCredit($order_info['customer_id'], $description, $order_info['total'], $order_id);
            }
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

    protected function validate() {
        $this->load->language('account/edit');

        if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
            $this->error['firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen(trim($this->request->post['companyname'])) < 1) || (utf8_strlen(trim($this->request->post['companyname'])) > 255)) {
            $this->error['companyname'] = $this->language->get('error_companyname');
        }
        if ((utf8_strlen(trim($this->request->post['companyaddress'])) < 1) || (utf8_strlen(trim($this->request->post['companyaddress'])) > 255)) {
            $this->error['companyaddress'] = $this->language->get('error_companyaddress');
        }

        //print_r($this->request->post);die;
        if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if (($this->customer->getEmail() != $this->request->post['email']) && !empty($this->request->post['email']) && $this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
            $this->error['warning'] = $this->language->get('error_exists');
        }

        if (false !== strpos($this->request->post['telephone'], '#') || empty($this->request->post['telephone'])) {
            $this->error['telephone'] = $this->language->get('error_telephone');
        }

        /* if (strpos($this->request->post['fax'], '#') !== false || empty($this->request->post['fax'])) {
          $this->error['error_tax'] = $this->language->get( 'error_tax' );
          } */

        if ($this->request->post['national_id'] != NULL && !preg_match('/^[0-9]{8}$/', $this->request->post['national_id'])) {
            $this->error['national_id'] = $this->language->get('error_invalid_national_id');
        }
        
        if ($this->request->post['kra'] != NULL && !preg_match('/^[A-Z0-9]{11}$/', $this->request->post['kra'])) {
            $this->error['kra'] = $this->language->get('error_invalid_kra');
        }

        if ($this->request->post['dob'] != NULL && !preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $this->request->post['dob'])) {
            $this->error['dob'] = $this->language->get('error_invalid_dob');
        }

        if (utf8_strlen($this->request->post['confirmpassword']) >= 1 && utf8_strlen($this->request->post['password']) >= 1 && (utf8_strlen($this->request->post['password'] < 6) && utf8_strlen($this->request->post['password']) > 20)) {
            $this->error['password'] = $this->language->get('error_password');
        }

        if (utf8_strlen($this->request->post['password']) >= 1 && !preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{6,}$/', $this->request->post['password'])) {
            $this->error['password'] = 'Password must contain 6 characters 1 capital(A-Z) 1 numeric(0-9) 1 special(@$!%*#?&)';
        }

        if (utf8_strlen($this->request->post['password']) >= 1 && (utf8_strlen($this->request->post['confirmpassword']) >= 1) && (utf8_strlen($this->request->post['confirmpassword']) < 6 || utf8_strlen($this->request->post['confirmpassword']) > 20)) {
            $this->error['confirmpassword'] = $this->language->get('error_confirmpassword');
        }

        if ($this->request->post['confirmpassword'] != $this->request->post['password']) {
            $this->error['confirmpassword'] = $this->language->get('error_mismatch_password');
        }

        if ($this->config->get('config_google_captcha_status')) {
            $json = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($this->config->get('config_google_captcha_secret')) . '&response=' . $this->request->post['g-recaptcha-response'] . '&remoteip=' . $this->request->server['REMOTE_ADDR']);

            $json = json_decode($json, true);

            if (!$json['success']) {
                $this->error['captcha'] = $this->language->get('error_captcha');
            }
        }

        $this->load->model('account/changepass');
        $change_pass_count = $this->model_account_changepass->check_customer_previous_password($this->customer->getId(), $this->request->post['password']);
        $change_current_pass_count = $this->model_account_changepass->check_customer_current_password($this->customer->getId(), $this->request->post['password']);

        if ($this->request->post['password'] != NULL && ($change_pass_count > 0 || $change_current_pass_count > 0)) {
            $this->error['password'] = 'Password must not match previous 3 passwords';
        }

        // if ( DateTime::createFromFormat('d/m/Y', $this->request->post['dob'] ) == FALSE ) {
        //     $this->error['dob'] = $this->language->get( 'error_dob' );
        // }
        //print_r("expression1");
        // Custom field validation
        $this->load->model('account/custom_field');

        $custom_fields = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));

        foreach ($custom_fields as $custom_field) {
            if (('account' == $custom_field['location']) && $custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['custom_field_id']])) {
                $this->error['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
            }
        }

//        echo "<pre>";print_r($this->error);die;
        return !$this->error;
    }

    public function resetOfferPrice() {
        $log = new Log('error.log');
        $log->write('in resetOfferPrice');

        $this->load->model('api/offer');

        $filter_data = [
            'filter_date_end' => date('Y-m-d', strtotime('- 1 day')),
        ];

        $results = $this->model_api_offer->getOffers($filter_data);

        $log->write($results);

        foreach ($results as $key => $result) {
            $offer_id = $result['offer_id'];
            $products = $this->model_api_offer->getOfferProducts($result['offer_id']);

            $log->write($products);

            foreach ($products as $pro) {
                $this->model_api_offer->resetSpecialPrice($pro);
            }
        }
    }

    public function formatTelephone($telephone) {
        /* if(strlen($telephone) == 11 ) {
          //(21) 42353-5255

          $str1 = '(';
          $str3 = ')';
          $str4 = ' ';
          $str6 = '-';

          $str  = $telephone;
          $str2 = substr($str,0,2);
          $str5 = substr($str,2,5);
          $str7 = substr($str,7,4);


          return  $str1.$str2.$str3.$str4.$str5.$str6.$str7;
          } else {
          return $telephone;
          } */
        return $telephone;
    }

    public function getOrder($order_id) {
        $order_query = $this->db->query('SELECT *, (SELECT os.name FROM `' . DB_PREFIX . 'order_status` os WHERE os.order_status_id = o.order_status_id AND os.language_id = o.language_id) AS order_status FROM `' . DB_PREFIX . "order` o WHERE o.order_id = '" . (int) $order_id . "'");

        if ($order_query->num_rows) {
            $this->load->model('localisation/language');
            $this->load->model('account/order');

            $language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);

            $city_name = $this->model_account_order->getCityName($order_query->row['shipping_city_id']);

            if ($language_info) {
                $language_code = $language_info['code'];
                $language_directory = $language_info['directory'];
            } else {
                $language_code = '';
                $language_directory = '';
            }

            return [
                'order_id' => $order_query->row['order_id'],
                'invoice_no' => $order_query->row['invoice_no'],
                'invoice_prefix' => $order_query->row['invoice_prefix'],
                'store_id' => $order_query->row['store_id'],
                'store_name' => $order_query->row['store_name'],
                'store_url' => $order_query->row['store_url'],
                'customer_id' => $order_query->row['customer_id'],
                'firstname' => $order_query->row['firstname'],
                'companyname' => $order_query->row['companyname'],
                'companyaddress' => $order_query->row['companyaddress'],
                'lastname' => $order_query->row['lastname'],
                'email' => $order_query->row['email'],
                'telephone' => $order_query->row['telephone'],
                'fax' => $order_query->row['fax'],
                'custom_field' => unserialize($order_query->row['custom_field']),
                'shipping_name' => $order_query->row['shipping_name'],
                'shipping_address' => $order_query->row['shipping_address'],
                'shipping_city' => $city_name,
                'shipping_contact_no' => $order_query->row['shipping_contact_no'],
                'shipping_method' => $order_query->row['shipping_method'],
                'shipping_zipcode' => $order_query->row['shipping_zipcode'],
                'shipping_code' => $order_query->row['shipping_code'],
                'shipping_flat_number' => $order_query->row['shipping_flat_number'],
                'shipping_building_name' => $order_query->row['shipping_building_name'],
                'shipping_landmark' => $order_query->row['shipping_landmark'],
                'latitude' => $order_query->row['latitude'],
                'longitude' => $order_query->row['longitude'],
                'payment_method' => $order_query->row['payment_method'],
                'payment_code' => $order_query->row['payment_code'],
                'comment' => $order_query->row['comment'],
                'total' => $order_query->row['total'],
                'order_status_id' => $order_query->row['order_status_id'],
                'order_status' => $order_query->row['order_status'],
                'affiliate_id' => $order_query->row['affiliate_id'],
                'commission' => $order_query->row['commission'],
                'language_id' => $order_query->row['language_id'],
                'language_code' => $language_code,
                'language_directory' => $language_directory,
                'currency_id' => $order_query->row['currency_id'],
                'currency_code' => $order_query->row['currency_code'],
                'currency_value' => $order_query->row['currency_value'],
                'order_pdf_link' => $order_query->row['order_pdf_link'],
                'ip' => $order_query->row['ip'],
                'forwarded_ip' => $order_query->row['forwarded_ip'],
                'user_agent' => $order_query->row['user_agent'],
                'accept_language' => $order_query->row['accept_language'],
                'date_modified' => $order_query->row['date_modified'],
                'date_added' => $order_query->row['date_added'],
                'delivery_date' => $order_query->row['delivery_date'],
                'delivery_timeslot' => $order_query->row['delivery_timeslot'],
                    /* 'date_modified' => $order_query->row['date_modified'],
                      'date_added' => $order_query->row['date_added'] */
            ];
        } else {
            return false;
        }
    }

    public function getTimeslotAverage($timeslot) {
        $str = $timeslot; //"06:26pm - 08:32pm";
        $arr = explode('-', $str);
        //print_r($arr);
        if (2 == count($arr)) {
            $one = date('H:i', strtotime($arr[0]));
            $two = date('H:i', strtotime($arr[1]));

            $time1 = explode(':', $one);
            $time2 = explode(':', $two);
            if (2 == count($time1) && 2 == count($time2)) {
                $mid1 = ($time1[0] + $time2[0]) / 2;
                $mid2 = ($time1[1] + $time2[1]) / 2;

                return $mid1 . ':' . $mid2;
            }
        }

        return false;
    }

    public function send($to, $msg) {
        // $sender_id = $this->config->get('config_sms_sender_id');
        // $username  = $this->config->get('config_sms_username');
        // $password  = $this->config->get('config_sms_password');
        $sender_id = 'KRAFTY';
        $username = 'krafty';
        $password = 'krafty@123';

        $msg = 'Your OTP is not required. :) Regards, Abhishek ';
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

        return $resp;
    }

    public function stripe_return() {
        $this->load->model('payment/stripe');

        if ('live' == $this->config->get('stripe_environment')) {
            $secret_key = $this->config->get('stripe_live_secret_key');
        } else {
            $secret_key = $this->config->get('stripe_test_secret_key');
        }

        //echo "<pre>";print_r($secret_key);die;
        //echo "<pre>";print_r($_GET);print_r($_POST);die;
        if (isset($_GET['code'])) { // Redirect w/ code
            $code = $_GET['code'];

            $client_id = $this->config->get('stripe_connect_platform_id'); //'ca_BoxbgCghYSt10rKtPzsW6WCLQ7nEEXIz';

            $token_request_body = [
                'grant_type' => 'authorization_code',
                'client_id' => $client_id,
                'code' => $code,
                //'client_secret' => 'sk_test_XKnQhs0XiKIEWP5bdjejJ0n4'
                'client_secret' => $secret_key,
            ];

            $req = curl_init('https://connect.stripe.com/oauth/token');
            curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($req, CURLOPT_POST, true);
            curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($token_request_body));

            // TODO: Additional error handling
            $respCode = curl_getinfo($req, CURLINFO_HTTP_CODE);
            $resp = json_decode(curl_exec($req), true);
            curl_close($req);

            //echo "<pre>";print_r($resp);die;

            if (isset($resp) && isset($resp['access_token'])) {
                //save returned stripe repsonse for future response_type

                $resp['vendor_id'] = $_GET['state'];

                if ('deliverysystem' == $_GET['state']) {
                    $resp['vendor_id'] = 0;

                    //$this->db->query( "DELTE " . DB_PREFIX . "setting SET store_id = 0, `code` = 'config', `key` = '" . $this->db->escape( $key ) . "', `value` = '" . $this->db->escape( $value ) . "'" );
                }

                if ($this->model_payment_stripe->addVendorStripeAccount($resp)) {
                    echo "<center><h3 style='color:green'>Stripe account successfully linked!</h3> <a href='#' onclick='window.close();return false;'>Close</a> </center>";
                } else {
                    echo "<center><h3 style='color:red'>Stripe User Already present!!</h3> <a href='#' onclick='window.close();return false;'>close</a> </center>";
                }
            } elseif (isset($resp['error'])) { // Error
                echo "<center><h3 style='color:red'>" . $resp['error_description'] . ' </h3>  </center>';
            } else {
                //error access token not granted
                echo "<center><h3 style='color:red'>Access token not granted. Try again </h3></center>";
            }
        } elseif (isset($_GET['error'])) { // Error
            echo "<center><h3 style='color:red'>" . $_GET['error_description'] . '</h3></center>';
        } else {
            echo "<center><h3 style='color:red'>Something went wrong. Try again </h3></center>";
        }

        die;
    }

    public function getCategoryProducts($filter_data) {
        $this->load->model('assets/product');

        $max_discount = 5;
        $results = $this->model_assets_product->getProductsForCron($filter_data);

        //echo "<pre>";print_r($filter_data);die;
        //echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            // if qty less then 1 dont show product
            if ($result['quantity'] <= 0) {
                continue;
            }

            $discount = '';
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
            }

            $percent_off = null;
            if (isset($s_price) && isset($o_price) && 0 != $o_price && 0 != $s_price) {
                $percent_off = (($o_price - $s_price) / $o_price) * 100;
            }

            if ($percent_off > $max_discount) {
                $max_discount = $percent_off;
            }
        }

        return number_format($max_discount, 0);
    }

    public function categoryPercentageSave() {
        $this->load->model('assets/category');

        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . 'store');

        foreach ($query->rows as $row) {
            $results = $this->model_assets_category->getCategoryByStoreForCron(0, $row['store_id']);

            foreach ($results as $result) {
                $filter_data = [
                    'filter_category_id' => $result['category_id'],
                    'filter_sub_category' => true,
                    'store_id' => $row['store_id'],
                ];

                $children = $this->model_assets_category->getCategories($result['category_id']);

                foreach ($children as $child) {
                    $child_filter_data = [
                        'filter_category_id' => $child['category_id'],
                        'filter_sub_category' => true,
                        'store_id' => $row['store_id'],
                    ];

                    $max_discount = $this->getCategoryProducts($child_filter_data);

                    $this->db->query('UPDATE ' . DB_PREFIX . "category_to_store SET max_discount ='" . (int) $max_discount . "' where store_id=" . $row['store_id'] . ' and category_id=' . $child['category_id']);
                }

                $max_discount = $this->getCategoryProducts($filter_data);

                $this->db->query('UPDATE ' . DB_PREFIX . "category_to_store SET max_discount ='" . (int) $max_discount . "' where store_id=" . $row['store_id'] . ' and category_id=' . $result['category_id']);
            }
        }

        die;
    }

    public function getAddressFromLatLng($location) {
        $data['full_address'] = '';
        $data['street_number'] = '';
        $data['short_address'] = '';
        $data['city'] = '';

        $userSearch = explode(',', $location);

        //echo "<pre>";print_r($location);die;

        if (count($userSearch) >= 2) {
            $validateLat = is_numeric($userSearch[0]);
            $validateLat2 = is_numeric($userSearch[1]);

            $validateLat3 = strpos($userSearch[0], '.');
            $validateLat4 = strpos($userSearch[1], '.');

            if ($validateLat && $validateLat2 && $validateLat3 && $validateLat4) {
                try {
                    $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $location . '&sensor=false&key=' . $this->config->get('config_google_server_api_key');

                    //echo "<pre>";print_r($url);die;
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);

                    $headers = [
                        'Cache-Control: no-cache',
                    ];
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    //curl_setopt($ch, CURLOPT_REFERER, '52.178.112.211');

                    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_WHATEVER);

                    $response = curl_exec($ch);
                    curl_close($ch);
                    $output = json_decode($response);

                    //echo "<pre>";print_r($output);die;
                    if (isset($output)) {
                        foreach ($output->results[0]->address_components as $addres) {
                            if (isset($addres->types)) {
                                if (in_array('street_number', $addres->types)) {
                                    //echo "<pre>";print_r($addres);die;
                                    $data['street_number'] = $addres->long_name;
                                }

                                if (in_array('route', $addres->types)) {
                                    //echo "<pre>";print_r($addres);die;
                                    $data['short_address'] = $addres->short_name;
                                }

                                if (in_array('locality', $addres->types)) {
                                    //echo "<pre>";print_r($addres);die;
                                    $data['city'] = $addres->short_name;
                                }
                            }
                        }

                        if (isset($output->results[0]->formatted_address)) {
                            $data['full_address'] = $output->results[0]->formatted_address;
                        }
                    }
                } catch (Exception $e) {
                    
                }
            }
        }

        //echo "<pre>";print_r($data['street_number']."ss".$data['short_address']."fd".$data['full_address']);die;
        return $data;
    }

}
