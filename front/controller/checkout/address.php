<?php

class ControllerCheckoutAddress extends Controller
{
    //save delivery info
    public function save()
    {
        $data = $this->request->post;
        $order_id = $this->session->data['order_id'];
        $payment_code = '';
        $shipping_code = '';
        $payment_method = '';
        $shipping_method = '';

        //validate city for each stores
        $errors = [];

        $stores = $this->cart->getStores();

        $this->load->model('account/address');

        //print_r($data);die;

        foreach ($stores as $key => $store_id) {
            $store_info = $this->model_account_address->getStoreData($store_id);

            if ($store_info['city_id'] != $data['shipping_city_id']) {
                $city_name = $this->model_account_address->getCityName($data['shipping_city_id']);

                $errors[] = $store_info['name'].' store not provide shipping to '.$city_name.'!';
            }
        }

        if ($errors) {
            echo json_encode(['status' => 0, 'msg' => implode('<br />', $errors)]);
            die();
        }

        //shipping_method
        foreach ($stores as $key => $store_id) {
            if (isset($this->request->post['shipping_method'][$store_id])) {
                $shipping_code = $this->request->post['shipping_method'];
                $arr = explode('.', $this->request->post['shipping_method']);
                if (isset($this->session->data['shipping_methods'][$store_id][$arr[0]])) {
                    $shipping_method = $this->session->data['shipping_methods'][$store_id][$arr[0]]['title'];
                }
            }
        }

        //payment_method
        if (isset($this->request->post['payment_method'])) {
            $payment_code = $this->request->post['payment_method'];
            if (isset($this->session->data['payment_methods'][$payment_code])) {
                $payment_method = $this->session->data['payment_methods'][$payment_code]['title'];
            }
        }

        //shipping_address_id

        //$data['shipping_address'] = $data['flat_number'].",".$data['building_name'].",".$data['landmark'];
        $data['shipping_address'] = $data['flat_number'].','.$data['landmark'];

        foreach ($this->session->data['order_id'] as $order_id) {
            $this->model_account_address->updateOrder($payment_code, $payment_method, $data['shipping_name'], $data['shipping_contact_no'], $data['shipping_address'], $data['shipping_city_id'], $data['flat_number'], $data['building_name'], $data['landmark'], $order_id);
        }

        if (isset($this->request->post['delivery_date'])) {
            $this->session->data['delivery_date'] = $this->request->post['delivery_date'];
        } else {
            $this->session->data['delivery_date'] = '';
        }

        if (isset($this->request->post['timeslot'])) {
            $this->session->data['timeslot'] = $this->request->post['timeslot'];
        } else {
            $this->session->data['timeslot'] = '';
        }

        echo json_encode(['status' => 1]);
    }

    public function index()
    {
        $this->load->language('checkout/checkout');

        $data = $this->language->all();

        $this->load->model('account/address');

        $data['addresses'] = $this->model_account_address->getAddresses();

        $this->load->model('localisation/country');

        $data['countries'] = $this->model_localisation_country->getCountries();

        // Custom Fields
        $this->load->model('account/custom_field');

        $data['custom_fields'] = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));

        $data = $this->getData($data);

        $data['shipping_required'] = $this->cart->hasShipping();

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/checkout/address.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/checkout/address.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/checkout/address.tpl', $data));
        }
    }

    public function save_old()
    {
        $this->load->language('checkout/checkout');

        $json = [];

        // Validate cart has products and has stock.
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $json['redirect'] = $this->url->link('checkout/cart');
        }

        // Validate minimum quantity requirements.
        $products = $this->cart->getProducts();

        foreach ($products as $product) {
            $product_total = 0;

            foreach ($products as $product_2) {
                if ($product_2['product_id'] == $product['product_id']) {
                    $product_total += $product_2['quantity'];
                }
            }

            if ($product['minimum'] > $product_total) {
                $json['redirect'] = $this->url->link('checkout/cart');

                break;
            }
        }

        if (!$json) {
            $this->session->data['comment'] = strip_tags($this->request->post['comment']);

            if (!empty($this->request->post['same_address']) or !$this->cart->hasShipping()) {
                if (isset($this->request->post['payment_address']) && 'existing' == $this->request->post['payment_address']) {
                    $this->load->model('account/address');

                    if (empty($this->request->post['payment_address_id'])) {
                        $json['error']['warning'] = $this->language->get('error_address');
                    } elseif (!in_array($this->request->post['payment_address_id'], array_keys($this->model_account_address->getAddresses()))) {
                        $json['error']['warning'] = $this->language->get('error_address');
                    }

                    if (!$json) {
                        // Default Shipping Address
                        $this->load->model('account/address');

                        $address = $this->model_account_address->getAddress($this->request->post['payment_address_id']);

                        // Set payment address
                        $this->session->data['payment_address'] = $address;

                        unset($this->session->data['payment_method']);
                        unset($this->session->data['payment_methods']);

                        // Set shipping address
                        $this->session->data['shipping_address'] = $address;

                        unset($this->session->data['shipping_method']);
                        unset($this->session->data['shipping_methods']);

                        $this->load->controller('checkout/shipping_method');
                        $this->load->controller('checkout/shipping_method/save');
                    }
                } else {
                    $json = $this->validateFields('payment');

                    if (!$json) {
                        $this->saveAddress('same');
                    }
                }
            } else {
                if (isset($this->request->post['payment_address']) && 'existing' == $this->request->post['payment_address']) {
                    $this->load->model('account/address');

                    if (empty($this->request->post['payment_address_id'])) {
                        $json['error']['warning'] = $this->language->get('error_address');
                    } elseif (!in_array($this->request->post['payment_address_id'], array_keys($this->model_account_address->getAddresses()))) {
                        $json['error']['warning'] = $this->language->get('error_address');
                    }

                    if (!$json) {
                        // Default Payment Address
                        $this->load->model('account/address');

                        $this->session->data['payment_address'] = $this->model_account_address->getAddress($this->request->post['payment_address_id']);

                        unset($this->session->data['payment_method']);
                        unset($this->session->data['payment_methods']);
                    }
                } else {
                    $json = $this->validateFields('payment');

                    if (!$json) {
                        $this->saveAddress('payment');
                    }
                }

                if (isset($this->request->post['shipping_address']) && 'existing' == $this->request->post['shipping_address']) {
                    $this->load->model('account/address');

                    if (empty($this->request->post['shipping_address_id'])) {
                        $json['error']['warning'] = $this->language->get('error_address');
                    } elseif (!in_array($this->request->post['shipping_address_id'], array_keys($this->model_account_address->getAddresses()))) {
                        $json['error']['warning'] = $this->language->get('error_address');
                    }

                    if (!$json) {
                        // Default Shipping Address
                        $this->load->model('account/address');

                        $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->request->post['shipping_address_id']);

                        unset($this->session->data['shipping_method']);
                        unset($this->session->data['shipping_methods']);

                        $this->load->controller('checkout/shipping_method');
                        $this->load->controller('checkout/shipping_method/save');
                    }
                } else {
                    $json = $this->validateFields('shipping');

                    if (!$json) {
                        $this->saveAddress('shipping');
                    }
                }
            }

            if (!$json and $this->cart->hasShipping()) {
                $shipping_address = $this->session->data['shipping_address'];

                if ($shipping_address['address_format']) {
                    $format = $shipping_address['address_format'];
                } else {
                    $format = '{firstname} {lastname}'."\n".'{company}'."\n".'{address_1}'."\n".'{address_2}'."\n".'{city} {postcode}'."\n".'{zone}'."\n".'{country}';
                }

                $find = [
                    '{firstname}',
                    '{lastname}',
                    '{company}',
                    '{address_1}',
                    '{address_2}',
                    '{city}',
                    '{postcode}',
                    '{zone}',
                    '{zone_code}',
                    '{country}',
                ];

                $replace = [
                    'firstname' => $shipping_address['firstname'],
                    'lastname' => $shipping_address['lastname'],
                    'company' => $shipping_address['company'],
                    'address_1' => $shipping_address['address_1'],
                    'address_2' => $shipping_address['address_2'],
                    'city' => $shipping_address['city'],
                    'postcode' => $shipping_address['postcode'],
                    'zone' => $shipping_address['zone'],
                    'zone_code' => $shipping_address['zone_code'],
                    'country' => $shipping_address['country'],
                ];

                $address = str_replace(["\r\n", "\r", "\n"], '<br />', preg_replace(["/\s\s+/", "/\r\r+/", "/\n\n+/"], '<br />', trim(str_replace($find, $replace, $format))));

                $json['address'] = $address;
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function saveAddress($src)
    {
        $prefix = ('same' == $src) ? 'payment_' : $src.'_';

        // Get the post data related with the $src (payment or shipping)
        $data = [];
        foreach ($this->request->post as $name => $value) {
            if (!strstr($name, $prefix)) {
                continue;
            }

            $org_name = str_replace($prefix, '', $name);

            $data[$org_name] = $value;
        }

        // Save and get the address
        if ($this->customer->isLogged()) {
            $this->load->model('account/address');

            $address_id = $this->model_account_address->addAddress($data);

            $address = $this->model_account_address->getAddress($address_id);
        } else {
            $this->load->model('localisation/country');
            $this->load->model('localisation/zone');

            if (!empty($data['country_id'])) {
                $country = $this->model_localisation_country->getCountry($data['country_id']);

                $data['country'] = $country['name'];
                $data['iso_code_2'] = $country['iso_code_2'];
                $data['iso_code_3'] = $country['iso_code_3'];
                $data['address_format'] = $country['address_format'];
            } else {
                $data['country'] = '';
                $data['iso_code_2'] = '';
                $data['iso_code_3'] = '';
                $data['address_format'] = '';
            }

            if (!empty($data['zone_id'])) {
                $zone = $this->model_localisation_zone->getZone($data['zone_id']);

                $data['zone'] = $zone['name'];
                $data['zone_code'] = $zone['code'];
            } else {
                $data['zone'] = '';
                $data['zone_code'] = '';
            }

            $address = $data;
        }

        // Set payment address
        if (('same' == $src) or ('payment' == $src)) {
            $this->session->data['payment_address'] = $address;

            unset($this->session->data['payment_method']);
            unset($this->session->data['payment_methods']);
        }

        // Set shipping address
        if (('same' == $src) or ('shipping' == $src)) {
            $this->session->data['shipping_address'] = $address;

            unset($this->session->data['shipping_method']);
            unset($this->session->data['shipping_methods']);

            $this->load->controller('checkout/shipping_method');
            $this->load->controller('checkout/shipping_method/save');
        }

        $this->load->model('account/activity');

        $activity_data = [
            'customer_id' => $this->customer->getId(),
            'name' => $this->customer->getFirstName().' '.$this->customer->getLastName(),
        ];

        $this->model_account_activity->addActivity('address_add', $activity_data);
    }

    public function addInAddressBook()
    {
        $data = $this->request->post;

        $log = new Log('error.log');
        $log->write($data);

        $save = false;
        $msg = '';
        $this->load->model('account/address');

        $this->load->language('checkout/checkout');
        if ($this->customer->isLogged()) {
            $this->load->model('account/address');

            $data['zipcode'] = $data['shipping_zipcode'];
            $data['city_id'] = $data['shipping_city_id'];
            $data['name'] = $data['modal_address_name'];

            $data['contact_no'] = isset($data['shipping_contact_no']) ? $data['shipping_contact_no'] : '';

            $data['flat_number'] = isset($data['modal_address_flat']) ? $data['modal_address_flat'] : '';

            $data['address_type'] = isset($data['modal_address_type']) ? $data['modal_address_type'] : 'home';

            $data['landmark'] = isset($data['modal_address_locality']) ? $data['modal_address_locality'] : '';

            $data['building_name'] = explode(',', $data['landmark'])[0];
            //$data['building_name'] = isset($data['modal_address_street'])?$data['modal_address_street']:'';

            $data['address'] = $data['modal_address_flat'].', '.$data['modal_address_locality'];

            $mapAddress = $data['modal_address_locality'].' '.$data['zipcode'];

            if (defined('const_latitude') && defined('const_longitude') && !empty(const_latitude) && !empty(const_longitude)) {
                $data['latitude'] = const_latitude;
                $data['longitude'] = const_longitude;
            }

            if(isset($data['isdefault_address']))//if true then field will come or else not come
            {
                $data['default']=1;
            }

            if ($this->config->get('config_address_check')) {
                $correctAddress['lat'] = isset($data['latitude']) ? $data['latitude'] : null;
                $correctAddress['lng'] = isset($data['longitude']) ? $data['longitude'] : null;

                $correctAddress['status'] = true;

                if (is_null($correctAddress['lat']) || is_null($correctAddress['lng']) || !$correctAddress['lng'] || !$correctAddress['lat']) {
                    $correctAddress['status'] = false;
                }

                $addressTmp = $this->getZipcode($correctAddress['lat'].','.$correctAddress['lng']);

                $lat_lng_zipcode = $addressTmp ? $addressTmp : '';

                if ($lat_lng_zipcode != $data['zipcode']) {
                    $correctAddress['status'] = false;

                    $msg = sprintf($this->language->get('text_addree_select_from_zipcode'), $data['zipcode']);
                } else {
                    //echo "<pre>";print_r($correctAddress);die;
                    if ($correctAddress['status']) {
                        $data['lat'] = $correctAddress['lat'];
                        $data['lng'] = $correctAddress['lng'];

                        $city_id = $this->model_account_address->getCityByName(isset($data['picker_city_name']) ? $data['picker_city_name'] : '');

                        if ($city_id) {
                            $data['city_id'] = $city_id;
                        }

                        $order_stores = $this->cart->getStores();

                        $res = true;
                        foreach ($order_stores as $os) {
                            $store_info = $this->model_account_address->getStoreData($os);

                            if (!empty($store_info['serviceable_radius'])) {
                                $res1 = $this->getDistance($data['lat'], $data['lng'], $store_info['latitude'], $store_info['longitude'], $store_info['serviceable_radius']);

                                if (!$res1) {
                                    $res = false;
                                    break;
                                }
                            }
                        }

                        if ($res) {
                            $save = true;

                            $address_id = $this->model_account_address->addAddress($data);
                        } else {
                            //address not enabled
                            $correctAddress['status'] = false;

                            $msg = $this->language->get('text_addree_select_from_location');
                        }
                    } else {
                        $msg = $this->language->get('text_addree_not_found');
                    }
                }
            } else {
                $data['lat'] = '';
                $data['lng'] = '';

                $save = true;
                $address_id = $this->model_account_address->addAddress($data);
            }
        }

        $data['text_other'] = $this->language->get('text_other');
        $data['text_home_address'] = $this->language->get('text_home_address');
        $data['text_office'] = $this->language->get('text_office');
        $data['text_deliver_here'] = $this->language->get('text_deliver_here');
        $data['text_not_deliver_here'] = $this->language->get('text_not_deliver_here');

        $data['addresses'] = $this->model_account_address->getAddresses();

        if (count($_COOKIE) > 0 && isset($_COOKIE['location'])) {
            //echo "<pre>";print_r($_COOKIE['location']);die;

            $tmpD = explode(',', $_COOKIE['location']);

            if (count($tmpD) >= 2) {
                $data['latitude'] = $tmpD[0];
                $data['longitude'] = $tmpD[1];
            }

            /*echo "<pre>";print_r($store_info);
            echo "<pre>";print_r($data['addresses']);die;*/

            $allAddresses = [];

            $store_serviceable_radius = 5;

            $order_stores = $this->cart->getStores();

            foreach ($order_stores as $os) {
                $store_info = $this->model_account_address->getStoreData($os);
            }

            foreach ($data['addresses'] as $addre) {
                $addre['show_enabled'] = true;

                foreach ($order_stores as $os) {
                    $store_info = $this->model_account_address->getStoreData($os);

                    //echo "<pre>";print_r($store_info);die;
                    if (!empty($addre['latitude']) || !empty($addre['longitude']) || !empty($store_info['serviceable_radius'])) {
                        $res = $this->getDistance($addre['latitude'], $addre['longitude'], $data['latitude'], $data['longitude'], $store_info['serviceable_radius']);

                        if (!$res) {
                            $addre['show_enabled'] = false;
                            break;
                        }
                    }
                }

                $allAddresses[] = $addre;
            }

            $data['addresses'] = $allAddresses;
        }

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/checkout/address-panel.tpl')) {
            $html = $this->load->view($this->config->get('config_template').'/template/checkout/address-panel.tpl', $data);
        } else {
            $html = $this->load->view('default/template/checkout/address-panel.tpl', $data);
        }

        /*echo json_encode(array('status'=>1,'address_id' => $address_id,'html' => $html,'redirect' => $this->url->link('checkout/checkout')));*/
        if ($save) {
            echo json_encode(['status' => 1, 'address_id' => $address_id, 'html' => $html, 'redirect' => $this->url->link('checkout/checkout')]);
        } else {
            echo json_encode(['status' => 0, 'message' => $msg, 'html' => $html, 'redirect' => $this->url->link('checkout/checkout')]);
        }
    }

    public function validateFields($prefix)
    {
        $json = [];

        if ((utf8_strlen(trim($this->request->post[$prefix.'_firstname'])) < 1) || (utf8_strlen(trim($this->request->post[$prefix.'_firstname'])) > 32)) {
            $json['error'][$prefix.'_firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen(trim($this->request->post[$prefix.'_lastname'])) < 1) || (utf8_strlen(trim($this->request->post[$prefix.'_lastname'])) > 32)) {
            $json['error'][$prefix.'_lastname'] = $this->language->get('error_lastname');
        }

        if ((utf8_strlen(trim($this->request->post[$prefix.'_address_1'])) < 3) || (utf8_strlen(trim($this->request->post[$prefix.'_address_1'])) > 128)) {
            $json['error'][$prefix.'_address_1'] = $this->language->get('error_address_1');
        }

        if ((utf8_strlen(trim($this->request->post[$prefix.'_city'])) < 2) || (utf8_strlen(trim($this->request->post[$prefix.'_city'])) > 128)) {
            $json['error'][$prefix.'_city'] = $this->language->get('error_city');
        }

        $this->load->model('localisation/country');

        $country_info = $this->model_localisation_country->getCountry($this->request->post[$prefix.'_country_id']);

        if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($this->request->post[$prefix.'_postcode'])) < 2 || utf8_strlen(trim($this->request->post[$prefix.'_postcode'])) > 10)) {
            $json['error'][$prefix.'_postcode'] = $this->language->get('error_postcode');
        }

        if ('' == $this->request->post[$prefix.'_country_id']) {
            $json['error'][$prefix.'_country'] = $this->language->get('error_country');
        }

        if (!isset($this->request->post[$prefix.'_zone_id']) || '' == $this->request->post[$prefix.'_zone_id']) {
            $json['error'][$prefix.'_zone'] = $this->language->get('error_zone');
        }

        // Custom field validation
        $this->load->model('account/custom_field');

        $custom_fields = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));

        foreach ($custom_fields as $custom_field) {
            if (('address' == $custom_field['location']) && $custom_field['required'] && empty($this->request->post[$prefix.'_custom_field'][$custom_field['custom_field_id']])) {
                $json['error'][$prefix.'_custom_field'.$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
            }
        }

        return $json;
    }

    public function getData($data)
    {
        // Payment Address
        if ($this->customer->isLogged()) {
            if (isset($this->session->data['payment_address']['address_id'])) {
                $data['payment_address_id'] = $this->session->data['payment_address']['address_id'];
            } else {
                $data['payment_address_id'] = $this->customer->getAddressId();
            }
        }

        if (isset($this->session->data['payment_address']['firstname'])) {
            $data['payment_firstname'] = $this->session->data['payment_address']['firstname'];
        } else {
            $data['payment_firstname'] = '';
        }

        if (isset($this->session->data['payment_address']['lastname'])) {
            $data['payment_lastname'] = $this->session->data['payment_address']['lastname'];
        } else {
            $data['payment_lastname'] = '';
        }

        if (isset($this->session->data['payment_address']['company'])) {
            $data['payment_company'] = $this->session->data['payment_address']['company'];
        } else {
            $data['payment_company'] = '';
        }

        if (isset($this->session->data['payment_address']['address_1'])) {
            $data['payment_address_1'] = $this->session->data['payment_address']['address_1'];
        } else {
            $data['payment_address_1'] = '';
        }

        if (isset($this->session->data['payment_address']['address_2'])) {
            $data['payment_address_2'] = $this->session->data['payment_address']['address_2'];
        } else {
            $data['payment_address_2'] = '';
        }

        if (isset($this->session->data['payment_address']['postcode'])) {
            $data['payment_postcode'] = $this->session->data['payment_address']['postcode'];
        } else {
            $data['payment_postcode'] = '';
        }

        if (isset($this->session->data['payment_address']['city'])) {
            $data['payment_city'] = $this->session->data['payment_address']['city'];
        } else {
            $data['payment_city'] = '';
        }

        if (isset($this->session->data['payment_address']['country_id'])) {
            $data['payment_country_id'] = $this->session->data['payment_address']['country_id'];
        } else {
            $data['payment_country_id'] = $this->config->get('config_country_id');
        }

        if (isset($this->session->data['payment_address']['zone_id'])) {
            $data['payment_zone_id'] = $this->session->data['payment_address']['zone_id'];
        } else {
            $data['payment_zone_id'] = $this->config->get('config_zone_id');
        }

        if (isset($this->session->data['payment_address']['custom_field'])) {
            $data['payment_address_custom_field'] = $this->session->data['payment_address']['custom_field'];
        } else {
            $data['payment_address_custom_field'] = [];
        }

        // Shipping Address
        if ($this->customer->isLogged()) {
            if (isset($this->session->data['shipping_address']['address_id'])) {
                $data['shipping_address_id'] = $this->session->data['shipping_address']['address_id'];
            } else {
                $data['shipping_address_id'] = $this->customer->getAddressId();
            }
        }

        if (isset($this->session->data['shipping_address']['firstname'])) {
            $data['shipping_firstname'] = $this->session->data['shipping_address']['firstname'];
        } else {
            $data['shipping_firstname'] = '';
        }

        if (isset($this->session->data['shipping_address']['lastname'])) {
            $data['shipping_lastname'] = $this->session->data['shipping_address']['lastname'];
        } else {
            $data['shipping_lastname'] = '';
        }

        if (isset($this->session->data['shipping_address']['company'])) {
            $data['shipping_company'] = $this->session->data['shipping_address']['company'];
        } else {
            $data['shipping_company'] = '';
        }

        if (isset($this->session->data['shipping_address']['address_1'])) {
            $data['shipping_address_1'] = $this->session->data['shipping_address']['address_1'];
        } else {
            $data['shipping_address_1'] = '';
        }

        if (isset($this->session->data['shipping_address']['address_2'])) {
            $data['shipping_address_2'] = $this->session->data['shipping_address']['address_2'];
        } else {
            $data['shipping_address_2'] = '';
        }

        if (isset($this->session->data['shipping_address']['postcode'])) {
            $data['shipping_postcode'] = $this->session->data['shipping_address']['postcode'];
        } else {
            $data['shipping_postcode'] = '';
        }

        if (isset($this->session->data['shipping_address']['city'])) {
            $data['shipping_city'] = $this->session->data['shipping_address']['city'];
        } else {
            $data['shipping_city'] = '';
        }

        if (isset($this->session->data['shipping_address']['country_id'])) {
            $data['shipping_country_id'] = $this->session->data['shipping_address']['country_id'];
        } else {
            $data['shipping_country_id'] = $this->config->get('config_country_id');
        }

        if (isset($this->session->data['shipping_address']['zone_id'])) {
            $data['shipping_zone_id'] = $this->session->data['shipping_address']['zone_id'];
        } else {
            $data['shipping_zone_id'] = $this->config->get('config_zone_id');
        }

        if (isset($this->session->data['shipping_address']['custom_field'])) {
            $data['shipping_address_custom_field'] = $this->session->data['shipping_address']['custom_field'];
        } else {
            $data['shipping_address_custom_field'] = [];
        }

        if (isset($this->session->data['comment'])) {
            $data['comment'] = $this->session->data['comment'];
        } else {
            $data['comment'] = '';
        }

        return $data;
    }

    public function getDistance($latitude1, $longitude1, $latitude2, $longitude2, $storeRadius)
    {
        $earth_radius = 6371;
        //$storeRadius = 2;
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

    public function getZipcode($address)
    {
        if (!empty($address)) {
            //Formatted address
            $formattedAddr = str_replace(' ', '+', $address);
            //Send request and receive json data by address

            $url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=false&key='.$this->config->get('config_google_server_api_key');
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
            $output1 = json_decode($response);

            //Get latitude and longitute from json data
            $latitude = $output1->results[0]->geometry->location->lat;
            $longitude = $output1->results[0]->geometry->location->lng;

            //Send request and receive json data by latitude longitute

            $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$latitude.','.$longitude.'&sensor=false&key='.$this->config->get('config_google_server_api_key');
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
}
