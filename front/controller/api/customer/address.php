<?php

class ControllerApiCustomerAddress extends Controller
{
    private $error = [];

    public function getAllAddress()
    {
        $json = [];

        $log = new Log('error.log');
        $log->write('getAllAddress');

        $log->write($this->request->get);

        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');

        $this->load->model('assets/category');

        $this->load->model('assets/product');

        $this->load->model('tool/image');

        //if( $this->customer->isLogged() ) {
        if (!empty($this->request->get['zipcode'])) {
            $stores = array_keys($this->request->get['stores']);

            //echo "<pre>";print_r($this->customer);die;
            $this->load->model('account/address');

            $data['delivery_addresses'] = [];
            $data['non_delivery_addresses'] = [];

            $zipcodeToSort = $this->request->get['zipcode'];
            //echo "<pre>";print_r($zipcodeToSort);die;

            $default_address_id = $this->customer->getAddressId();
            //$default_address_id = 1;

            $results = $this->model_account_address->getAddresses();
            if (false !== strpos($zipcodeToSort, '.')) {
                //lat lang based

                $log->write('getAllAddress if');

                $tmpD = explode(',', $zipcodeToSort);

                if (count($tmpD) >= 2) {
                    $data['latitude'] = $tmpD[0];
                    $data['longitude'] = $tmpD[1];

                    /*echo "<pre>";print_r($store_info);
                    echo "<pre>";print_r($data['addresses']);die;*/

                    $serviceable_radius = 100;

                    if (array_key_exists($default_address_id, $results)) {
                        $dafaultEnable = true;

                        foreach ($stores as $store_id) {
                            $store_info = $this->model_account_address->getStoreData($store_id);

                            //echo "<pre>";print_r($store_info);die;
                            if (!empty($store_info['serviceable_radius'])) {
                                $res1 = $this->getDistance($results[$default_address_id]['latitude'], $results[$default_address_id]['longitude'], $data['latitude'], $data['longitude'], $store_info['serviceable_radius']);

                                if (!$res1) {
                                    $dafaultEnable = false;
                                    break;
                                }
                            }
                        }

                        if ($dafaultEnable) {
                            $data['delivery_addresses'][] = $results[$default_address_id];
                            unset($results[$default_address_id]);
                        }
                    }

                    foreach ($results as $result) {
                        if (!empty($result['latitude']) || !empty($result['longitude']) || !empty($data['latitude']) || !empty($data['longitude'])) {
                            $res = true;

                            foreach ($stores as $store_id) {
                                $store_info = $this->model_account_address->getStoreData($store_id);

                                //echo "<pre>";print_r($store_info);die;
                                if (!empty($store_info['serviceable_radius'])) {
                                    $res = $this->getDistance($result['latitude'], $result['longitude'], $data['latitude'], $data['longitude'], $store_info['serviceable_radius']);

                                    if (!$res1) {
                                        $res = false;
                                        break;
                                    }
                                }
                            }

                            if ($res) {
                                $data['delivery_addresses'][] = [
                                    'address_id' => $result['address_id'],
                                    'name' => $result['name'],
                                    'contact_no' => $result['contact_no'],
                                    'address' => $result['address'],
                                    'city_id' => $result['city_id'],
                                    'flat_number' => $result['flat_number'],
                                    'latitude' => $result['latitude'],
                                    'longitude' => $result['longitude'],
                                    'building_name' => $result['building_name'],
                                    'landmark' => $result['landmark'],
                                    'city' => $result['city'],
                                    'zipcode' => $result['zipcode'],
                                    'address_type' => $result['address_type'],
                                    'update' => $this->url->link('account/address/edit', 'address_id='.$result['address_id'], 'SSL'),
                                    'delete' => $this->url->link('account/address/delete', 'address_id='.$result['address_id'], 'SSL'),
                                ];
                            } else {
                                $data['non_delivery_addresses'][] = [
                                    'address_id' => $result['address_id'],
                                    'name' => $result['name'],
                                    'contact_no' => $result['contact_no'],
                                    'address' => $result['address'],
                                    'city_id' => $result['city_id'],
                                    'flat_number' => $result['flat_number'],
                                    'latitude' => $result['latitude'],
                                    'longitude' => $result['longitude'],
                                    'building_name' => $result['building_name'],
                                    'landmark' => $result['landmark'],
                                    'city' => $result['city'],
                                    'zipcode' => $result['zipcode'],
                                    'address_type' => $result['address_type'],
                                    'update' => $this->url->link('account/address/edit', 'address_id='.$result['address_id'], 'SSL'),
                                    'delete' => $this->url->link('account/address/delete', 'address_id='.$result['address_id'], 'SSL'),
                                ];
                            }
                        }
                    }

                    $log->write($data['non_delivery_addresses']);
                    $log->write($data['delivery_addresses']);
                }
            } else {
                //zipcode based
                if (array_key_exists($default_address_id, $results)) {
                    //echo "yes";
                    if ($results[$default_address_id]['zipcode'] == $zipcodeToSort) {
                        $data['delivery_addresses'][] = $results[$default_address_id];
                        unset($results[$default_address_id]);
                    }
                }

                foreach ($results as $result) {
                    if ($zipcodeToSort == $result['zipcode']) {
                        $data['delivery_addresses'][] = [
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
                            'update' => $this->url->link('account/address/edit', 'address_id='.$result['address_id'], 'SSL'),
                            'delete' => $this->url->link('account/address/delete', 'address_id='.$result['address_id'], 'SSL'),
                        ];
                    } else {
                        $data['non_delivery_addresses'][] = [
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
                            'update' => $this->url->link('account/address/edit', 'address_id='.$result['address_id'], 'SSL'),
                            'delete' => $this->url->link('account/address/delete', 'address_id='.$result['address_id'], 'SSL'),
                        ];
                    }
                }
            }

            $data['check_address'] = false;

            if ($this->config->get('config_address_check')) {
                $data['check_address'] = true;
            }

            $json['data'] = $data;
        } else {
            $this->load->model('account/address');
            $results = $this->model_account_address->getAddresses();
            foreach ($results as $result) {
                $data['delivery_addresses'][] = [
                'address_id' => $result['address_id'],
                'name' => $result['name'],
                'contact_no' => $result['contact_no'],
                'address' => $result['address'],
                'city_id' => $result['city_id'],
                'flat_number' => $result['flat_number'],
                'latitude' => $result['latitude'],
                'longitude' => $result['longitude'],
                'building_name' => $result['building_name'],
                'landmark' => $result['landmark'],
                'city' => $result['city'],
                'zipcode' => $result['zipcode'],
                'address_type' => $result['address_type'],
                'update' => $this->url->link('account/address/edit', 'address_id='.$result['address_id'], 'SSL'),
                'delete' => $this->url->link('account/address/delete', 'address_id='.$result['address_id'], 'SSL'),
            ];
            }

            $json['data'] = $data;
            /*$json['status'] = 10013;

            $json['message'][] = ['type' =>  '' , 'body' =>  $this->language->get('text_not_loggedin') ];

            http_response_code(400);
            */
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function deleteAddress()
    {
        $json = [];

        //echo "<pre>";print_r("deleteAddress");die;
        //echo "<pre>";print_r("deleteAddress");die;

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');
        $this->load->model('account/address');

        $log = new Log('error.log');
        $log->write('deleteAddress');

        if (isset($this->request->get['address_id']) && $this->validateDelete()) {
            $log->write('deleteAddress if');
            $this->model_account_address->deleteAddress($this->request->get['address_id']);

            $json['status'] = 10020;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_address_deleted')];

            // Add to activity log
            $this->load->model('account/activity');

            $activity_data = [
                'customer_id' => $this->customer->getId(),
                'name' => $this->customer->getFirstName().' '.$this->customer->getLastName(),
            ];

            $this->model_account_activity->addActivity('address_delete', $activity_data);
        } else {
            $json['status'] = 10013;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_not_loggedin')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addAddress()
    {
        $log = new Log('error.log');
        $log->write('addAddress fn');
        $log->write($this->request->post);

        $json = [];

        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');

        $this->load->model('assets/category');

        $this->load->model('assets/product');

        $this->load->model('tool/image');

        $this->load->model('account/address');

        //echo "<pre>";print_r("th");die;
        if ($this->validateForm()) {
            $log->write('addAddress fn if');
            //echo "<pre>";print_r("if");die;
            $data = $this->request->post;

            $this->load->model('account/address');

            $save = false;

            $zipcode_exists = $this->model_account_address->zipcodeExists($data['zipcode']);
            /*
                get city_id from zipcode
            */


            if(isset($data['is_default_address']) && $data['is_default_address']==1)//if true then field will come or else not come
            {
                $data['default']=1;
            }

            if (count($zipcode_exists) > 0) {
                //$data['city_id'] = $data['shipping_city_id'];
                $data['city_id'] = 0;

                if (0 == $data['city_id']) {
                    $data['city_id'] = $zipcode_exists['city_id'];
                }

                $data['name'] = isset($data['name_title']) ? $data['name_title'].' '.$data['name'] : $data['name'];

                $data['contact_no'] = isset($data['contact_no']) ? $data['contact_no'] : '';
                $data['flat_number'] = isset($data['flat_number']) ? $data['flat_number'] : '';
                $data['building_name'] = isset($data['address_street']) ? $data['address_street'] : '';

                $data['address_type'] = isset($data['address_type']) ? $data['address_type'] : 'home';

                $data['landmark'] = isset($data['landmark']) ? $data['landmark'] : '';

                if (empty($data['building_name'])) {
                    $data['building_name'] = $data['landmark'];
                }
                //$data['address'] = $data['flat_number'].", ".$data['building_name'].", ".$data['landmark'];
                $data['address'] = $data['flat_number'].', '.$data['landmark'];

                $mapAddress = $data['building_name'].' '.$data['zipcode'];

                //echo "<pre>";print_r($this->request->post);die;
                //echo "<pre>";print_r($data);die;

                if ($this->config->get('config_address_check') || true) {
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

                        $respData = $this->getAddressFromLatLng($data['lat'].','.$data['lng']);

                        if (count($respData) > 0 && !empty(trim($respData['full_address']))) {
                            $city_id = $this->model_account_address->getCityByName($respData['city']);

                            if ($city_id) {
                                $data['city_id'] = $city_id;
                            }
                        }

                        $save = true;
                        $address_id = $this->model_account_address->addAddress($data);
                    } else {
                        $json['message'][] = ['type' => '', 'body' => $this->language->get('text_addree_not_found')];
                    }
                } else {
                    $data['lat'] = '';
                    $data['lng'] = '';

                    $save = true;
                    $address_id = $this->model_account_address->addAddress($data);
                }

                if ($save) { 
                    // saved address
                    //$json['status'] = 10015;
                     // Add to activity log
                    $this->load->model('account/activity');

                    $activity_data = [
                        'customer_id' => $this->customer->getId(),
                        'name' => $this->customer->getFirstName().' '.$this->customer->getLastName(),
                    ];

                    $this->model_account_activity->addActivity('address_add', $activity_data);
 
                    $json['data']['address_id'] = $address_id;

                    $json['message'][] = ['type' => '', 'body' => $this->language->get('text_added_successfully')];
                } else {
                    $json['status'] = 10016;

                    $json['message'][] = ['type' => '', 'body' => $this->language->get('text_could_not_save')];
                }
            } else {
                $json['status'] = 10017;

                $json['message'][] = ['type' => '', 'body' => $this->language->get('text_zipcode_not_exists')];
            }
        } else {
            $log->write('addAddress fn else');

            $json['status'] = 10014;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_missing_form_data')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getAddress($args = [])
    {
        $json = [];
        //echo "<pre>";print_r($args);die;
        //echo "<pre>";print_r("getaddress");die;
        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');
        $this->load->model('account/address');

        if ($args['address_id']) {
            $address_name = '';

            $data = $this->model_account_address->getAddress($args['address_id']);

            //echo "<pre>";print_r($data);die;
            if (!empty($data)) {
                $data['name_title'] = null;

                $name_title_mr = substr($data['name'], 0, 4);
                $name_title_mrs = substr($data['name'], 0, 5);
                $name_title_miss = substr($data['name'], 0, 6);

                //send me name title as full with dot also
                if ('mr. ' == strtolower($name_title_mr)) {
                    $data['name_title'] = $name_title_mr;
                } elseif ('mrs. ' == strtolower($name_title_mrs)) {
                    $data['name_title'] = $name_title_mrs;
                } elseif ('miss. ' == strtolower($name_title_miss)) {
                    $data['name_title'] = $name_title_miss;
                }

                $json['data'] = $data;
            } else {
                //address not found

                $json['status'] = 10019;

                $json['message'][] = ['type' => '', 'body' => $this->language->get('text_address_not_found')];
            }
        } else {
            $json['status'] = 10013;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_not_loggedin')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function editAddress($args = [])
    {
        $log = new Log('error.log');
        $log->write('editAddress');

        $json = [];
        //echo "<pre>";print_r($args);die;
        //echo "<pre>";print_r("getaddress");die;

        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');

        $this->load->model('assets/category');

        $this->load->model('assets/product');

        $this->load->model('tool/image');

        $this->load->model('account/address');

        if ($this->editvalidateForm($args) && isset($args['address_id'])) {
            $data = $args;

            $this->load->model('account/address');

            $save = false;

            if(isset($data['is_default_address']) && $data['is_default_address']==1)//if true then field will come or else not come
            {
                $data['default']=1;
            }

            $zipcode_exists = $this->model_account_address->zipcodeExists($data['zipcode']);
            /*
                get city_id from zipcode
            */
            if (count($zipcode_exists) > 0) {
                //$data['city_id'] = $data['shipping_city_id'];
                $data['address_id'] = $data['address_id'];
                $data['zipcode'] = $data['zipcode'];
                //$data['city_id'] = $data['city_id'];
                $data['city_id'] = 0;

                if (0 == $data['city_id']) {
                    $data['city_id'] = $zipcode_exists['city_id'];
                }

                $data['name'] = isset($data['name_title']) ? $data['name_title'].' '.$data['name'] : $data['name'];

                $data['flat_number'] = isset($data['flat_number']) ? $data['flat_number'] : '';

                $data['contact_no'] = isset($data['contact_no']) ? $data['contact_no'] : '';

                $data['building_name'] = isset($data['address_street']) ? $data['address_street'] : '';

                $data['address_type'] = isset($data['address_type']) ? $data['address_type'] : 'home';

                $data['landmark'] = isset($data['landmark']) ? $data['landmark'] : '';

                //$data['address'] = $data['flat_number'].", ".$data['building_name'].", ".$data['landmark'];
                $data['address'] = $data['flat_number'].', '.$data['landmark'];

                $mapAddress = $data['building_name'].' '.$data['zipcode'];

                //echo "<pre>";print_r($args);die;
                //echo "<pre>";print_r($data);die;

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

                        $respData = $this->getAddressFromLatLng($data['lat'].','.$data['lng']);

                        if (count($respData) > 0 && !empty(trim($respData['full_address']))) {
                            $log->write($respData);
                            $city_id = $this->model_account_address->getCityByName($respData['city']);

                            if (!empty($city_id)) {
                                $data['city_id'] = $city_id;
                            }
                        }

                        $address_id = $this->model_account_address->editAddress($data['address_id'], $data);
                    } else {
                        $json['message'][] = ['type' => '', 'body' => $this->language->get('text_addree_not_found')];
                    }
                } else {
                    $data['lat'] = '';
                    $data['lng'] = '';

                    $save = true;
                    $address_id = $this->model_account_address->editAddress($data['address_id'], $data);
                }

                if ($save) {
                    // saved address
                    //$json['status'] = 10018;

                    $this->load->model('account/activity');
                    $activity_data = [
                        'customer_id' => $this->customer->getId(),
                        'name' => $this->customer->getFirstName().' '.$this->customer->getLastName(),
                    ];        
                    $this->model_account_activity->addActivity('address_edit', $activity_data);
        

                    $json['message'][] = ['type' => '', 'body' => $this->language->get('text_edited_successfully')];
                } else {
                    $json['status'] = 10016;

                    $json['message'][] = ['type' => '', 'body' => $this->language->get('text_could_not_save')];
                }
            } else {
                $json['status'] = 10017;

                $json['message'][] = ['type' => '', 'body' => $this->language->get('text_zipcode_not_exists')];
            }
        } else {
            $json['status'] = 10013;

            $json['message'][] = ['type' => '', 'body' => 'Proper address id & data is not passed'];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validateForm()
    {
        $log = new Log('error.log');

        if ((utf8_strlen(trim($this->request->post['name'])) < 1) || (utf8_strlen(trim($this->request->post['name'])) > 32)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if (empty($this->request->post['address_type'])) {
            $this->error['address_type'] = $this->language->get('error_address_type');
        }

        /*if (empty($this->request->post['address_street'])) {
            $this->error['address_street'] = $this->language->get('error_address_street');
        }*/

        if (empty($this->request->post['latitude'])) {
            $this->error['latitude'] = $this->language->get('error_latitude');
        }

        if (empty($this->request->post['longitude'])) {
            $this->error['error_longitude'] = $this->language->get('error_longitude');
        }

        /*if (empty($this->request->post['flat_number'])) {
            $this->error['error_flat_number'] = $this->language->get('error_flat_number');
        }*/

        if (empty($this->request->post['landmark'])) {
            $this->error['error_landmark'] = $this->language->get('error_landmark');
        }

        if (empty($this->request->post['zipcode'])) {
            $this->error['error_zipcode'] = $this->language->get('error_zipcode');
        }

        $log->write('validateForm fn');
        $log->write($this->error);

        //echo "<pre>";print_r($this->error);die;
        return !$this->error;
    }

    protected function editvalidateForm($args)
    {
        if ((utf8_strlen(trim($args['name'])) < 1) || (utf8_strlen(trim($args['name'])) > 32)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if (empty($args['address_type'])) {
            $this->error['address_type'] = $this->language->get('error_address_type');
        }

        if (empty($args['address_street'])) {
            $this->error['address_street'] = $this->language->get('error_address_street');
        }

        if (empty($args['latitude'])) {
            $this->error['latitude'] = $this->language->get('error_latitude');
        }

        if (empty($args['longitude'])) {
            $this->error['error_longitude'] = $this->language->get('error_longitude');
        }

        /*if (empty($args['flat_number'])) {
            $this->error['error_flat_number'] = $this->language->get('error_flat_number');
        }*/

        if (empty($args['landmark'])) {
            $this->error['error_landmark'] = $this->language->get('error_landmark');
        }

        if (empty($args['zipcode'])) {
            $this->error['error_zipcode'] = $this->language->get('error_zipcode');
        }

        /*if (empty($args['city_id'])) {
            $this->error['error_city_id'] = $this->language->get('error_city_id');
        }*/

        //echo "<pre>";print_r($this->error);die;
        return !$this->error;
    }

    protected function validateDelete()
    {
        /*if ($this->customer->getAddressId() == $this->request->get['address_id']) {
            $this->error['warning'] = $this->language->get('error_default');
        }*/

        return !$this->error;
    }

    public function addMakedefaultaddress()
    {
        $json = [];

        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');

        $this->load->model('assets/category');

        $this->load->model('assets/product');

        $this->load->model('tool/image');

        $this->load->model('account/address');

        //echo "<pre>";print_r("th");die;

        $log = new Log('error.log');
        $log->write('addMakedefaultaddress');
        $log->write($this->customer->getId());
        //$log->write($this->session->data['customer_id']);
        $log->write($this->model_account_address->getAddress($this->request->post['address_id']));

        //if( !empty($this->request->post['address_id']) && $this->model_account_address->getAddress($this->request->post['address_id'])) {
        if (!empty($this->request->post['address_id'])) {
            $customer_id = $this->request->post['customer_id'];

            $this->model_account_address->editMakeDefaultAddressApi($this->request->post['address_id'], $customer_id);

            //$json['status'] = 10018;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_edited_successfully')];
        } else {
            $json['status'] = 10014;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_missing_form_data')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getDistance($latitude1, $longitude1, $latitude2, $longitude2, $storeRadius)
    {
        $earth_radius = 6371;

        $dLat = deg2rad($latitude2 - $latitude1);
        $dLon = deg2rad($longitude2 - $longitude1);

        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * asin(sqrt($a));
        $d = $earth_radius * $c;

        if ($d < $storeRadius) {
            //echo "Within 100 kilometer radius";
            return true;
        } else {
            //echo "Outside 100 kilometer radius";
            return false;
        }
    }

    public function getAddressFromLatLng($location)
    {
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
                    $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$location.'&sensor=false&key='.$this->config->get('config_google_server_api_key');

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
                    if (isset($output) && isset($output->results) && isset($output->results[0])) {
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
