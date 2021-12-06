
<?php

class ControllerAccountAddress extends Controller {

    private $error = [];

    public function index() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/address', '', 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $data['kondutoStatus'] = $this->config->get('config_konduto_status');

        $this->load->language('account/address');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        $this->load->model('account/address');

        $this->getList();
    }

    public function addInAddressBookFromAccount() {
        $data = $this->request->post;

        $log = new Log('error.log');
        $log->write($data);

        //die;
        $this->load->model('account/address');

        $save = false;
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');

        $msg = '';

        if ($this->customer->isLogged()) {
            $this->load->model('account/address');

            $data['zipcode'] = $data['shipping_zipcode'];

            if ('autosuggestion' == $this->config->get('config_store_location')) {
                $zipcode_exists['static_zipcode'] = 34565;
            } else {
                $zipcode_exists = $this->model_account_address->zipcodeExists($data['zipcode']);
            }

            if (isset($data['isdefault_address'])) {//if true then field will come or else not come
                $data['default'] = 1;
            }

            /*
              get city_id from zipcode
             */
            if (count($zipcode_exists) > 0) {
                //$data['city_id'] = $data['shipping_city_id'];
                $data['city_id'] = 0;

                if (0 == $data['city_id'] && !isset($zipcode_exists['static_zipcode'])) {
                    $data['city_id'] = $zipcode_exists['city_id'];
                } else {
                    $data['city_id'] = 32; //new york
                }
                $data['name'] = $data['modal_address_name'];

                $data['contact_no'] = isset($data['shipping_contact_no']) ? $data['shipping_contact_no'] : '';

                $data['flat_number'] = isset($data['modal_address_flat']) ? $data['modal_address_flat'] : '';
                $data['building_name'] = isset($data['modal_address_street']) ? $data['modal_address_street'] : '';

                $data['address_type'] = isset($data['modal_address_type']) ? $data['modal_address_type'] : 'home';

                $data['landmark'] = isset($data['modal_address_locality']) ? $data['modal_address_locality'] : '';

                //$data['address'] = $data['modal_address_flat'].", ".$data['building_name'].", ".$data['modal_address_locality'];
                $data['address'] = $data['modal_address_flat'] . ', ' . $data['modal_address_locality'];

                $mapAddress = $data['building_name'] . ' ' . $data['zipcode'];

                /* $correctAddress = $this->model_account_address->addressCheck($mapAddress);
                  if($correctAddress['status'])
                  {
                  $data['lat'] = $correctAddress['lat'];
                  $data['lng'] = $correctAddress['lng'];

                  $save = true;
                  $address_id = $this->model_account_address->addAddress($data);
                  } else {
                  $msg = $this->language->get('text_addree_not_found');
                  } */

                if ($this->config->get('config_address_check')) {
                    $correctAddress['lat'] = isset($data['latitude']) ? $data['latitude'] : null;
                    $correctAddress['lng'] = isset($data['longitude']) ? $data['longitude'] : null;

                    //echo "<pre>";print_r($mapAddress);die;
                    //$correctAddress = $this->model_account_address->addressCheck($mapAddress,$data['zipcode']);
                    $correctAddress['status'] = true;

                    if (is_null($correctAddress['lat']) || is_null($correctAddress['lng']) || !$correctAddress['lng'] || !$correctAddress['lat']) {
                        $correctAddress['status'] = false;
                    }

                    //echo "<pre>";print_r($correctAddress);die;
                    if ($correctAddress['status']) {
                        $data['lat'] = $correctAddress['lat'];
                        $data['lng'] = $correctAddress['lng'];

                        if ('autosuggestion' == $this->config->get('config_store_location')) {
                            $addressTmp = $this->getZipcode($data['lat'] . ',' . $data['lng']);

                            //$data['zipcode'] = $addressTmp?$addressTmp:'';

                            $data['shipping_zipcode'] = $data['zipcode'];

                            $city_id = $this->model_account_address->getCityByName(isset($data['picker_city_name']) ? $data['picker_city_name'] : '');

                            if ($city_id) {
                                $data['city_id'] = $city_id;
                            }
                        }

                        $save = true;
                        $address_id = $this->model_account_address->addAddress($data);
                    } else {
                        $msg = $this->language->get('text_addree_not_found');
                    }
                } else {
                    $data['lat'] = '';
                    $data['lng'] = '';

                    $save = true;
                    $address_id = $this->model_account_address->addAddress($data);
                }
            } else {
                $msg = $this->language->get('text_zipcode_not_found');
            }
        }

        $data['text_other'] = $this->language->get('text_other');
        $data['text_home_address'] = $this->language->get('text_home_address');
        $data['text_office'] = $this->language->get('text_office');

        $results = $this->model_account_address->getAddresses();

        //echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            $data['addresses'][] = [
                'address_id' => $result['address_id'],
                'name' => $result['name'],
                'contact_no' => $result['contact_no'],
                'address' => $result['address'],
                'city_id' => $result['city_id'],
                'flat_number' => $result['flat_number'],
                'building_name' => $result['building_name'],
                'landmark' => $result['landmark'],
                'city' => $result['city'],
                'zipcode' => $result['zipcode'],
                'address_type' => $result['address_type'],
                'update' => $this->url->link('account/address/edit', 'address_id=' . $result['address_id'], 'SSL'),
                'delete' => $this->url->link('account/address/delete', 'address_id=' . $result['address_id'], 'SSL'),
            ];
        }
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/account-address-panel.tpl')) {
            $html = $this->load->view($this->config->get('config_template') . '/template/checkout/account-address-panel.tpl', $data);
        } else {
            $html = $this->load->view('default/template/checkout/account-address-panel.tpl', $data);
        }
        if ($save) {
            echo json_encode(['status' => 1, 'address_id' => $address_id, 'html' => $html, 'redirect' => $this->url->link('checkout/checkout')]);
        } else {
            echo json_encode(['status' => 0, 'message' => $msg, 'html' => $html, 'redirect' => $this->url->link('checkout/checkout')]);
        }
    }

    public function add() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/address', '', 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }

        //echo "<pre>";print_r($this->request->post);die;
        $this->load->language('account/address');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addScript('front/ui/javascript/jquery/datetimepicker/moment.js');
        $this->document->addScript('front/ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
        $this->document->addStyle('front/ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

        $this->load->model('account/address');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $this->model_account_address->addAddress($this->request->post);

            $this->session->data['success'] = $this->language->get('text_add');

            // Add to activity log
            $this->load->model('account/activity');

            $activity_data = [
                'customer_id' => $this->customer->getId(),
                'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
            ];

            $this->model_account_activity->addActivity('address_add', $activity_data);

            $this->response->redirect($this->url->link('account/address', '', 'SSL'));
        }

        $this->getForm();
    }

    public function edit() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/address', '', 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }

        //echo "<pre>";print_r($this->request->post);die;
        $this->load->language('account/address');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addScript('front/ui/javascript/jquery/datetimepicker/moment.js');
        $this->document->addScript('front/ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
        $this->document->addStyle('front/ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

        $this->load->model('account/address');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $this->model_account_address->editAddress($this->request->get['address_id'], $this->request->post);

            // Default Shipping Address
            if (isset($this->session->data['shipping_address']['address_id']) && ($this->request->get['address_id'] == $this->session->data['shipping_address']['address_id'])) {
                $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->request->get['address_id']);

                unset($this->session->data['shipping_method']);
                unset($this->session->data['shipping_methods']);
            }

            // Default Payment Address
            if (isset($this->session->data['payment_address']['address_id']) && ($this->request->get['address_id'] == $this->session->data['payment_address']['address_id'])) {
                $this->session->data['payment_address'] = $this->model_account_address->getAddress($this->request->get['address_id']);

                unset($this->session->data['payment_method']);
                unset($this->session->data['payment_methods']);
            }

            $this->session->data['success'] = $this->language->get('text_edit');

            // Add to activity log
            $this->load->model('account/activity');

            $activity_data = [
                'customer_id' => $this->customer->getId(),
                'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
            ];

            $this->model_account_activity->addActivity('address_edit', $activity_data);

            $this->response->redirect($this->url->link('account/address', '', 'SSL'));
        }

        $this->getForm();
    }

    public function delete() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/address', '', 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $this->load->language('account/address');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/address');

        if (isset($this->request->get['address_id']) && $this->validateDelete()) {
            $this->model_account_address->deleteAddress($this->request->get['address_id']);

            // Default Shipping Address
            if (isset($this->session->data['shipping_address']['address_id']) && ($this->request->get['address_id'] == $this->session->data['shipping_address']['address_id'])) {
                unset($this->session->data['shipping_address']);
                unset($this->session->data['shipping_method']);
                unset($this->session->data['shipping_methods']);
            }

            $this->session->data['success'] = $this->language->get('text_delete');

            // Add to activity log
            $this->load->model('account/activity');

            $activity_data = [
                'customer_id' => $this->customer->getId(),
                'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
            ];

            $this->model_account_activity->addActivity('address_delete', $activity_data);

            $this->response->redirect($this->url->link('account/address', '', 'SSL'));
        }

        $this->getList();
    }

    protected function getList() {
        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];
        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/account', '', 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('account/address', '', 'SSL'),
        ];

        $data['heading_title'] = $this->language->get('heading_title');

        $data['kondutoStatus'] = $this->config->get('config_konduto_status');

        $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_login.css');

        $data['detect_location'] = $this->language->get('detect_location');
        $data['text_locating'] = $this->language->get('text_locating');
        $data['locate_me'] = $this->language->get('locate_me');
        $data['text_ok'] = $this->language->get('text_ok');
        $data['text_your_location'] = $this->language->get('text_your_location');

        $data['text_address_book'] = $this->language->get('text_address_book');
        $data['text_empty'] = $this->language->get('text_empty');
        $data['button_new_address'] = $this->language->get('button_new_address');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_back'] = $this->language->get('button_back');

        $data['text_home_address'] = $this->language->get('text_home_address');
        $data['text_name'] = $this->language->get('text_name');
        $data['text_save'] = $this->language->get('text_save');
        $data['text_close'] = $this->language->get('text_close');
        $data['text_view'] = $this->language->get('text_view');
        $data['text_free'] = $this->language->get('text_free');
        $data['text_item'] = $this->language->get('text_item');
        $data['text_office'] = $this->language->get('text_office');
        $data['text_locality'] = $this->language->get('text_locality');
        $data['text_delivery_address'] = $this->language->get('text_delivery_address');
        $data['text_add_new_address'] = $this->language->get('text_add_new_address');
        $data['text_edit_address_new'] = $this->language->get('text_edit_address_new');
        $data['text_add_New_Address'] = $this->language->get('text_add_New_Address');
        $data['text_other'] = $this->language->get('text_other');
        $data['text_flat_house_office'] = $this->language->get('text_flat_house_office');
        $data['text_stree_society_office'] = $this->language->get('text_stree_society_office');

        /* $data['latitude'] = 46.15242437752303;
          $data['longitude'] = 2.7470703125; */
        $data['latitude'] = null;
        $data['longitude'] = null;

        $data['f_name'] = $this->customer->getFirstName();
        $data['l_name'] = $this->customer->getLastName();
        $data['full_name'] = $data['f_name']; //.' '.$data['l_name'];

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $data['addresses'] = [];

        $results = $this->model_account_address->getAddresses();

        //echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            $data['addresses'][] = [
                'address_id' => $result['address_id'],
                'name' => $result['name'],
                'contact_no' => $result['contact_no'],
                'address' => $result['address'],
                'city_id' => $result['city_id'],
                'flat_number' => $result['flat_number'],
                'building_name' => $result['building_name'],
                'street_address' => $result['street_address'],
                'landmark' => $result['landmark'],
                'city' => $result['city'],
                'zipcode' => $result['zipcode'],
                'address_type' => $result['address_type'],
                'update' => $this->url->link('account/address/edit', 'address_id=' . $result['address_id'], 'SSL'),
                'delete' => $this->url->link('account/address/delete', 'address_id=' . $result['address_id'], 'SSL'),
            ];
        }

        $data['entry_zipcode'] = $this->language->get('entry_zipcode');
        $data['label_zipcode'] = $this->language->get('label_zipcode');

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['base'] = $server;

        $data['zipcode_mask'] = $this->config->get('config_zipcode_mask');

        if (isset($data['zipcode_mask'])) {
            $data['zipcode_mask_number'] = str_replace('#', '9', $this->config->get('config_zipcode_mask'));
        }

        $data['check_address'] = false;

        if ($this->config->get('config_address_check')) {
            $data['check_address'] = true;
        }

        $data['zipcode'] = '00100';

        $data['add'] = $this->url->link('account/address/add', '', 'SSL');
        $data['back'] = $this->url->link('account/account', '', 'SSL');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/information');

        //echo "<pre>";print_r($data['addresses']);die;
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/address_list.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/maps.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/account/address_list.tpl', $data));
        }
    }

    protected function getForm() {
        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/account', '', 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('account/address', '', 'SSL'),
        ];

        if (!isset($this->request->get['address_id'])) {
            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_edit_address'),
                'href' => $this->url->link('account/address/add', '', 'SSL'),
            ];
        } else {
            $data['breadcrumbs'][] = [
                'text' => $this->language->get('text_edit_address'),
                'href' => $this->url->link('account/address/edit', 'address_id=' . $this->request->get['address_id'], 'SSL'),
            ];
        }

        $data['heading_title'] = $this->language->get('heading_title');
        $data['kondutoStatus'] = $this->config->get('config_konduto_status');

        $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_login.css');

        $data['detect_location'] = $this->language->get('detect_location');
        $data['text_locating'] = $this->language->get('text_locating');
        $data['locate_me'] = $this->language->get('locate_me');
        $data['text_ok'] = $this->language->get('text_ok');
        $data['text_your_location'] = $this->language->get('text_your_location');

        $data['text_edit_address'] = $this->language->get('text_edit_address');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_select'] = $this->language->get('text_select');
        $data['text_none'] = $this->language->get('text_none');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_type'] = $this->language->get('entry_type');

        $data['text_home'] = $this->language->get('text_home');
        $data['text_office'] = $this->language->get('text_office');

        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_company'] = $this->language->get('entry_company');
        $data['entry_address'] = $this->language->get('entry_address');

        $data['error_building_name'] = $this->language->get('error_building_name');
        $data['error_flat_number'] = $this->language->get('error_flat_number');
        $data['error_contact_no'] = $this->language->get('error_contact_no');
        $data['error_landmark'] = $this->language->get('error_landmark');

        $data['entry_zipcode'] = $this->language->get('entry_zipcode');
        $data['entry_flat_number'] = $this->language->get('entry_flat_number');
        $data['entry_building_name'] = $this->language->get('entry_building_name');
        $data['entry_landmark'] = $this->language->get('entry_landmark');

        $data['entry_contact_no'] = $this->language->get('entry_contact_no');
        $data['entry_address_1'] = $this->language->get('entry_address_1');
        $data['entry_address_2'] = $this->language->get('entry_address_2');
        $data['entry_postcode'] = $this->language->get('entry_postcode');
        $data['entry_city'] = $this->language->get('entry_city');
        $data['entry_country'] = $this->language->get('entry_country');
        $data['entry_zone'] = $this->language->get('entry_zone');
        $data['entry_default'] = $this->language->get('entry_default');

        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_back'] = $this->language->get('button_back');
        $data['button_upload'] = $this->language->get('button_upload');

        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_select_city_first'] = $this->language->get('text_select_city_first');

        if (isset($this->error['error_building_name'])) {
            $data['error_building_name'] = $this->error['error_building_name'];
        } else {
            $data['error_building_name'] = '';
        }

        if (isset($this->error['error_flat_number'])) {
            $data['error_flat_number'] = $this->error['error_flat_number'];
        } else {
            $data['error_flat_number'] = '';
        }
        if (isset($this->error['error_contact_no'])) {
            $data['error_contact_no'] = $this->error['error_contact_no'];
        } else {
            $data['error_contact_no'] = '';
        }
        if (isset($this->error['error_landmark'])) {
            $data['error_landmark'] = $this->error['error_landmark'];
        } else {
            $data['error_landmark'] = '';
        }

        if (isset($this->error['error_zipcode'])) {
            $data['error_zipcode'] = $this->error['error_zipcode'];
        } else {
            $data['error_zipcode'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        if (isset($this->error['address'])) {
            $data['error_address'] = $this->error['address'];
        } else {
            $data['error_address'] = '';
        }

        if (isset($this->error['error_city_id'])) {
            $data['error_city_id'] = $this->error['error_city_id'];
        } else {
            $data['error_city_id'] = '';
        }

        if (isset($this->error['contact_no'])) {
            $data['error_contact_no'] = $this->error['contact_no'];
        } else {
            $data['error_contact_no'] = [];
        }

        if (!isset($this->request->get['address_id'])) {
            $data['action'] = $this->url->link('account/address/add', '', 'SSL');
        } else {
            $data['action'] = $this->url->link('account/address/edit', 'address_id=' . $this->request->get['address_id'], 'SSL');
        }

        if (isset($this->request->get['address_id']) && ('POST' != $this->request->server['REQUEST_METHOD'])) {
            $address_info = $this->model_account_address->getAddress($this->request->get['address_id']);
        }

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($address_info)) {
            $data['name'] = $address_info['name'];
        } else {
            $data['name'] = '';
        }

        if (isset($this->request->post['contact_no'])) {
            $data['contact_no'] = $this->request->post['contact_no'];
        } elseif (!empty($address_info)) {
            $data['contact_no'] = $address_info['contact_no'];
        } else {
            $data['contact_no'] = '';
        }

        if (isset($this->request->post['address'])) {
            $data['address'] = $this->request->post['address'];
        } elseif (!empty($address_info)) {
            $data['address'] = $address_info['address'];
        } else {
            $data['address'] = '';
        }

        if (isset($this->request->post['city_id'])) {
            $data['city_id'] = $this->request->post['city_id'];
        } elseif (!empty($address_info)) {
            $data['city_id'] = $address_info['city_id'];
            //$data['city_id'] = $this->getSelectedCity($address_info['city_id']);
        } else {
            $data['city_id'] = '';
        }

        if (isset($this->request->post['flat_number'])) {
            $data['flat_number'] = $this->request->post['flat_number'];
        } elseif (!empty($address_info)) {
            $data['flat_number'] = $address_info['flat_number'];
        } else {
            $data['flat_number'] = '';
        }

        if (isset($this->request->post['building_name'])) {
            $data['building_name'] = $this->request->post['building_name'];
        } elseif (!empty($address_info)) {
            $data['building_name'] = $address_info['building_name'];
        } else {
            $data['building_name'] = '';
        }

        if (isset($this->request->post['landmark'])) {
            $data['landmark'] = $this->request->post['landmark'];
        } elseif (!empty($address_info)) {
            $data['landmark'] = $address_info['landmark'];
        } else {
            $data['landmark'] = '';
        }

        if (isset($this->request->post['zipcode'])) {
            $data['zipcode'] = $this->request->post['zipcode'];
            //$data['zipcode'] = $this->getZipcodesOfCity($data['city_id'],$this->request->post['zipcode']);
        } elseif (!empty($address_info)) {
            $data['zipcode'] = $this->getZipcodesOfCity($data['city_id'], $address_info['zipcode']);

            //$data['zipcode'] = $address_info['zipcode'];
        } else {
            $json = '<select name="zipcode" id="zipcode" class="form-control">';
            $json .= '<option selected value="">' . $data['text_select_city_first'] . '</option>';
            $json .= '</select>';

            $data['zipcode'] = $json;
        }

        if (isset($this->request->post['default'])) {
            $data['default'] = $this->request->post['default'];
        } elseif (isset($this->request->get['address_id'])) {
            $data['default'] = $this->customer->getAddressId() == $this->request->get['address_id'];
        } else {
            $data['default'] = false;
        }

        if (isset($this->request->post['address_type'])) {
            $data['address_type'] = $this->request->post['address_type'];
        } elseif (!empty($address_info)) {
            $data['address_type'] = $address_info['address_type'];
        } else {
            $data['address_type'] = false;
        }

        $this->load->model('account/address');

        $data['cities'] = $this->model_account_address->getCities();

        $data['back'] = $this->url->link('account/address', '', 'SSL');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/information');

        //echo "<pre>";print_r($data);die;
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/address_form.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/address_form.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/account/address_form.tpl', $data));
        }
    }

    protected function validateForm() {
        if ((utf8_strlen(trim($this->request->post['name'])) < 1) || (utf8_strlen(trim($this->request->post['name'])) > 32)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if (empty($this->request->post['address'])) {
            $this->error['address'] = $this->language->get('error_address');
        }

        if (empty($this->request->post['contact_no'])) {
            $this->error['contact_no'] = $this->language->get('error_contact_no');
        }

        if (empty($this->request->post['city_id'])) {
            $this->error['error_city_id'] = $this->language->get('error_city_id');
        }

        /* if (empty($this->request->post['building_name'])) {
          $this->error['error_building_name'] = $this->language->get('error_building_name');
          } */

        if (empty($this->request->post['flat_number'])) {
            $this->error['error_flat_number'] = $this->language->get('error_flat_number');
        }

        if (empty($this->request->post['landmark'])) {
            $this->error['error_landmark'] = $this->language->get('error_landmark');
        }

        return !$this->error;
    }

    protected function validateDelete() {
        /* if ($this->model_account_address->getTotalAddresses() == 1) {
          $this->error['warning'] = $this->language->get('error_delete');
          } */

        /* if ($this->customer->getAddressId() == $this->request->get['address_id']) {
          $this->error['warning'] = $this->language->get('error_default');
          } */

        return !$this->error;
    }

    public function getZipcodes() {
        if (isset($this->request->get['city_id'])) {
            $city_id = $this->request->get['city_id'];
        } else {
            $city_id = '';
        }

        $this->load->model('account/address');

        $zipcodes = $this->model_account_address->getAllZipcodeByCity($city_id);

        $json = '<select name="zipcode" id="zipcode" class="form-control">';

        foreach ($zipcodes as $zipcode) {
            $json .= '<option value=' . $zipcode['zipcode'] . '>' . $zipcode['zipcode'] . '</option>';
        }
        $json .= '</select>';
        echo $json;
    }

    public function getZipcodesOfCity($city_id, $select) {
        $this->load->model('account/address');

        $zipcodes = $this->model_account_address->getAllZipcodeByCity($city_id);

        $json = '<select name="zipcode" id="zipcode" class="form-control">';

        foreach ($zipcodes as $zipcode) {
            if ($zipcode['zipcode'] == $select) {
                $json .= '<option selected value=' . $zipcode['zipcode'] . '>' . $zipcode['zipcode'] . '</option>';
            } else {
                $json .= '<option value=' . $zipcode['zipcode'] . '>' . $zipcode['zipcode'] . '</option>';
            }
        }
        $json .= '</select>';

        return $json;
    }

    public function getAddress($address_id) {
        $this->load->model('account/address');

        $address_name = '';

        $data = $this->request->post;

        $data = $this->model_account_address->getAddress($data['address_id']);

        // echo "<pre>";print_r($data);die;
        if (isset($data)) {
            $address_name = $data['landmark'];
        }

        $data['detect_location'] = $this->language->get('detect_location');
        $data['text_locating'] = $this->language->get('text_locating');
        $data['locate_me'] = $this->language->get('locate_me');
        $data['text_ok'] = $this->language->get('text_ok');
        $data['text_your_location'] = $this->language->get('text_your_location');

        $data['entry_zipcode'] = $this->language->get('entry_zipcode');
        $data['label_zipcode'] = $this->language->get('label_zipcode');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');

        $data['text_home_address'] = $this->language->get('text_home_address');
        $data['text_name'] = $this->language->get('text_name');
        $data['text_save'] = $this->language->get('text_save');
        $data['text_close'] = $this->language->get('text_close');
        $data['text_view'] = $this->language->get('text_view');
        $data['text_free'] = $this->language->get('text_free');
        $data['text_item'] = $this->language->get('text_item');
        $data['text_office'] = $this->language->get('text_office');
        $data['text_locality'] = $this->language->get('text_locality');
        $data['text_delivery_address'] = $this->language->get('text_delivery_address');
        $data['text_add_new_address'] = $this->language->get('text_add_new_address');
        $data['text_edit_address_new'] = $this->language->get('text_edit_address_new');
        $data['text_add_New_Address'] = $this->language->get('text_add_New_Address');
        $data['text_other'] = $this->language->get('text_other');
        $data['text_flat_house_office'] = $this->language->get('text_flat_house_office');
        $data['text_stree_society_office'] = $this->language->get('text_stree_society_office');

        $data['check_address'] = false;

        $data['zipcode_mask'] = $this->config->get('config_zipcode_mask');

        if (isset($data['zipcode_mask'])) {
            $data['zipcode_mask_number'] = str_replace('#', '9', $this->config->get('config_zipcode_mask'));
        }

        if ($this->config->get('config_address_check')) {
            $data['check_address'] = true;
        }

        $log = new Log('error.log');
        $log->write($data);
        //echo "<pre>";print_r($data);die;
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/edit_address_form.tpl')) {
            $html = $this->load->view($this->config->get('config_template') . '/template/checkout/edit_address_form.tpl', $data);
        } else {
            $html = $this->load->view('default/template/checkout/edit_address_form.tpl', $data);
        }

        echo json_encode(['status' => 1, 'latitude' => $data['latitude'], 'longitude' => $data['longitude'], 'address_id' => $address_id, 'address_name' => $address_name, 'html' => $html, 'redirect' => $this->url->link('checkout/checkout')]);
    }

    public function editAddress() {
        $data = $this->request->post;

        $log = new Log('error.log');
        $log->write('save edit address');
        $log->write($data);

        //die;
        $save = false;
        $msg = '';
        $this->load->model('account/address');

        if ($this->customer->isLogged()) {
            $this->load->model('account/address');

            $data['address_id'] = $data['address_id'];

            $data['zipcode'] = $data['shipping_zipcode'];
            $data['city_id'] = $data['shipping_city_id'];
            $data['name'] = $data['edit_modal_address_name'];

            $data['contact_no'] = isset($data['edit_shipping_contact_no']) ? $data['edit_shipping_contact_no'] : '';

            $data['flat_number'] = isset($data['edit_modal_address_flat']) ? $data['edit_modal_address_flat'] : '';
            $data['building_name'] = isset($data['edit_modal_address_street']) ? $data['edit_modal_address_street'] : '';

            $data['address_type'] = isset($data['edit_modal_address_type']) ? $data['edit_modal_address_type'] : 'home';

            $data['landmark'] = isset($data['edit_modal_address_locality']) ? $data['edit_modal_address_locality'] : '';

            //$data['address'] = $data['edit_modal_address_flat'].", ".$data['building_name'].", ".$data['edit_modal_address_locality'];
            $data['address'] = $data['edit_modal_address_flat'] . ', ' . $data['edit_modal_address_locality'];

            $mapAddress = $data['building_name'] . ' ' . $data['zipcode'];

            /* if($this->config->get('config_address_check')) {
              $correctAddress = $this->model_account_address->addressCheck($mapAddress,$data['zipcode']);
              if($correctAddress['status'])
              {
              $data['lat'] = $correctAddress['lat'];
              $data['lng'] = $correctAddress['lng'];

              $save = true;
              $address_id = $this->model_account_address->editAddress($data['address_id'],$data);
              } else {
              $msg = $this->language->get('text_addree_not_found');
              }
              } else {
              $data['lat'] = '';
              $data['lng'] = '';

              $save = true;
              $address_id = $this->model_account_address->editAddress($data['address_id'],$data);
              } */


            if (isset($data['isdefault_address'])) {//if true then field will come or else not come
                $data['default'] = 1;
            }

            if ($this->config->get('config_address_check')) {
                $correctAddress['lat'] = isset($data['latitude']) ? $data['latitude'] : null;
                $correctAddress['lng'] = isset($data['longitude']) ? $data['longitude'] : null;

                $correctAddress['status'] = true;

                if (is_null($correctAddress['lat']) || is_null($correctAddress['lng']) || !$correctAddress['lng'] || !$correctAddress['lat']) {
                    $correctAddress['status'] = false;
                }

                //echo "<pre>";print_r($correctAddress);die;
                if ($correctAddress['status']) {
                    $data['lat'] = $correctAddress['lat'];
                    $data['lng'] = $correctAddress['lng'];

                    $save = true;

                    $city_id = $this->model_account_address->getCityByName(isset($data['picker_city_name']) ? $data['picker_city_name'] : '');

                    if ($city_id) {
                        $data['city_id'] = $city_id;
                    }

                    $address_id = $this->model_account_address->editAddress($data['address_id'], $data);
                } else {
                    $msg = $this->language->get('text_addree_not_found');
                }
            } else {
                $data['lat'] = '';
                $data['lng'] = '';

                $save = true;
                $address_id = $this->model_account_address->editAddress($data['address_id'], $data);
            }
        } else {
            $msg = $this->language->get('text_zipcode_not_found');
        }

        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['text_home_address'] = $this->language->get('text_home_address');
        $data['text_other'] = $this->language->get('text_other');
        $data['text_office'] = $this->language->get('text_office');

        $results = $this->model_account_address->getAddresses();

        //echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            $data['addresses'][] = [
                'address_id' => $result['address_id'],
                'name' => $result['name'],
                'contact_no' => $result['contact_no'],
                'address' => $result['address'],
                'city_id' => $result['city_id'],
                'flat_number' => $result['flat_number'],
                'building_name' => $result['building_name'],
                'landmark' => $result['landmark'],
                'city' => $result['city'],
                'zipcode' => $result['zipcode'],
                'address_type' => $result['address_type'],
                'update' => $this->url->link('account/address/edit', 'address_id=' . $result['address_id'], 'SSL'),
                'delete' => $this->url->link('account/address/delete', 'address_id=' . $result['address_id'], 'SSL'),
            ];
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/account-address-panel.tpl')) {
            $html = $this->load->view($this->config->get('config_template') . '/template/checkout/account-address-panel.tpl', $data);
        } else {
            $html = $this->load->view('default/template/checkout/account-address-panel.tpl', $data);
        }

        if ($save) {
            echo json_encode(['status' => 1, 'address_id' => $address_id, 'html' => $html, 'redirect' => $this->url->link('checkout/checkout')]);
        } else {
            echo json_encode(['status' => 0, 'message' => $msg, 'html' => $html, 'redirect' => $this->url->link('checkout/checkout')]);
        }

        /* echo json_encode(array('status'=>1,'address_id' => $address_id,'html' => $html,'redirect' => $this->url->link('checkout/checkout'))); */
    }

    public function getZipcode($address) {
        if (!empty($address)) {
            //Formatted address
            $formattedAddr = str_replace(' ', '+', $address);
            //Send request and receive json data by address

            $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $formattedAddr . '&sensor=false&key=' . $this->config->get('config_google_server_api_key');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);

            $headers = [
                'Cache-Control: no-cache',];
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //curl_setopt($ch, CURLOPT_REFERER, '52.178.112.211');

            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_WHATEVER);

            $response = curl_exec($ch);
            curl_close($ch);
            $output1 = json_decode($response);

            //Get latitude and longitute from json data
            $latitude = $output1->results[0]->geometry->location->lat;
            $longitude = $output1->results[0]->geometry->location->lng;

            $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $latitude . ',' . $longitude . '&sensor=false&key=' . $this->config->get('config_google_server_api_key');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);

            $headers = [
                'Cache-Control: no-cache',];
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //curl_setopt($ch, CURLOPT_REFERER, '52.178.112.211');

            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_WHATEVER);

            $response = curl_exec($ch);
            curl_close($ch);
            $output2 = json_decode($response);

            if (!empty($output2)) {
                $addressComponents = $output2->results[0]->address_components;
                foreach ($addressComponents as $addrComp) {
                    if ('postal_code' == $addrComp->types[0]) {
                        //Return the zipcode
                        return $addrComp->long_name;
                    }
                }

                return false;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function maps() {
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/maps.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/maps.tpl'));
        }
    }

}
