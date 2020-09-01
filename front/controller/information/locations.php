<?php

class ControllerInformationLocations extends Controller
{
    public function index()
    {
        $this->load->language('information/locations');

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        $data['kondutoStatus'] = $this->config->get('config_konduto_status');
        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        $this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_checkout.css');
        $this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_locations.css');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_heading1'] = $this->language->get('text_heading1');
        $data['text_heading2'] = $this->language->get('text_heading2');
        $data['text_find'] = $this->language->get('text_find');
        $data['text_delivers'] = $this->language->get('text_delivers');
        $data['button_check'] = $this->language->get('button_check');
        $data['entry_zipcode'] = $this->language->get('entry_zipcode');

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['base'] = $server;

        //$data['action'] = $this->url->link('information/locations/stores');
        $data['action'] = $server.'index.php?path=information/locations/stores';

        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/information');

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/information/locations.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/information/locations.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/information/locations.tpl', $data));
        }
    }

    public function stores()
    {
        //echo "<pre>";print_r("er");die;
        $this->load->language('information/locations');
        $this->load->model('setting/store');

        $this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_checkout.css');
        $this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_locations.css');

        $data['kondutoStatus'] = $this->config->get('config_konduto_status');
        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        if ('autosuggestion' == $this->config->get('config_store_location')) {
            if (isset($this->request->get['location'])) {
                $location = urldecode($this->request->get['location']);
            } else {
                if (isset($_COOKIE['location'])) {
                    $location = $_COOKIE['location'];
                }
            }

            //echo "<pre>";print_r($location);die;
        } else {
            if (isset($this->request->get['zipcode'])) {
                $zipcode = $this->request->get['zipcode'];
            } else {
                $zipcode = $_COOKIE['zipcode'];
            }
        }

        if (isset($this->request->get['filter'])) {
            $data['filter'] = urlencode($this->request->get['filter']);
            $filter = urldecode($this->request->get['filter']);
        } else {
            $filter = '';
            $data['filter'] = '';
        }

        //echo "<pre>";print_r(urlencode($this->request->get['filter']));die;

        if ($this->config->get('config_google_analytics_status')) {
            $data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');
        } else {
            $data['google_analytics'] = '';
        }

        $data['text_choose_store'] = $this->language->get('text_choose_store');
        $data['entry_recommended_store'] = $this->language->get('entry_recommended_store');
        $data['text_verify_number'] = $this->language->get('text_verify_number');
        $data['text_proceed_to_checkout'] = $this->language->get('text_proceed_to_checkout');
        $data['text_register'] = $this->language->get('text_register');
        $data['text_sign_in'] = $this->language->get('text_sign_in');
        $data['text_change_locality_warning'] = $this->language->get('text_change_locality_warning');
        $data['text_only_on_change_locality_warning'] = $this->language->get('text_only_on_change_locality_warning');
        $data['text_heading_title'] = $this->language->get('text_heading_title');
        $data['text_change_location_name'] = $this->language->get('text_change_location_name');

        $data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');

        //echo "<pre>";print_r($filter);die;

        $data['location_name'] = '';

        //echo "<pre>";print_r("er");die;
        if ('autosuggestion' == $this->config->get('config_store_location')) {
            $data['location_name'] = $this->getHeaderPlace($location);

            if (isset($this->request->get['address'])) {
                $data['location_name'] = $this->request->get['address'];
            }

            //echo "<pre>";print_r($data['location_name']);die;

            $data['location_name_full'] = $data['location_name'];

            $data['location_name'] = strlen($data['location_name']) > 10 ? substr($data['location_name'], 0, 10).'...' : $data['location_name'];

            $data['city_name'] = $data['location_name_full']; //$this->getPlace($location);

            if (isset($this->request->get['address'])) {
                $data['city_name'] = $this->request->get['address'];
            }

            // $data['city_name'] = strlen($data['city_name']) > 35 ? substr($data['city_name'],0,35)."..." : $data['city_name'];

            $data['zipcode'] = '';

            $data['stores'] = $this->model_setting_store->getStoreByLatLang($location, $filter);
        } else {
            $data['city_name'] = $this->model_setting_store->getCityNameByZip($zipcode);
            $data['stores'] = $this->model_setting_store->getStoreByZip($zipcode, $filter);
        }

        /*$userSearch = explode(",", $location);

        $latitude = false;
        $longitude = false;

        if(count($userSearch) >= 2) {
            $latitude = $userSearch[0];
            $longitude = $userSearch[1];
        }*/

        $tempStores = $data['stores'];
        $data['stores'] = [];
        $this->load->model('tool/image');

        $this->load->model('assets/category');
        //$data['categories'] = $this->model_assets_category->getCategories(0);
        $store_types = $this->model_assets_category->getStoreTypes();

        $storeTypes = [];

        foreach ($store_types as $store_type) {
            $temp_store_type['name'] = $store_type['name'];
            $temp_store_type['stores'] = [];

            foreach ($tempStores as $store) {
                if (in_array($store_type['store_type_id'], explode(',', $store['store_type_ids']))) {
                    $temp_store = $store;

                    //echo "<pre>";print_r($store);die;
                    if (!empty($store['logo']) && $store['logo']) {
                        $image = $this->model_tool_image->resize($store['logo'], $this->config->get('config_image_location_width'), $this->config->get('config_image_location_height'));
                    } else {
                        $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_location_width'), $this->config->get('config_image_location_height'));
                    }

                    $temp_store['image'] = $image;
                    $temp_store['store_open_hours'] = '';
                    // code...
                    $store_open_hours = $this->model_tool_image->getStoreOpenHours($store['store_id'], date('w'));

                    if ($store_open_hours && isset($store_open_hours['timeslot'])) {
                        $temp_store['store_open_hours'] = $store_open_hours['timeslot'];
                    }

                    array_push($temp_store_type['stores'], $temp_store);
                }
            }

            if (count($temp_store_type['stores']) > 0) {
                array_push($storeTypes, $temp_store_type);
            }
        }

        if (1 == count($storeTypes) && 1 == count($storeTypes[0]['stores'])) {
            $this->response->redirect($this->url->link('product/store', 'store_id='.$storeTypes[0]['stores'][0]['store_id']));
        }

        $data['store_lists'] = $storeTypes;

        //echo "<pre>";print_r($data);die;
        $data['text_heading3'] = $this->language->get('text_heading3');

        $data['text_available'] = $this->language->get('text_available');

        $data['text_shop'] = $this->language->get('text_shop');

        $data['account_register'] = $this->load->controller('account/register');
        //echo "<pre>";print_r($this->language->get('heading_title'));die;
        $data['login_modal'] = $this->load->controller('common/login_modal');

        $data['signup_modal'] = $this->load->controller('common/signup_modal');
        $data['forget_modal'] = $this->load->controller('common/forget_modal');

        $data['button_change_locality'] = $this->language->get('button_change_locality');
        $data['button_change_store'] = $this->language->get('button_change_store');
        $data['text_change_locality'] = $this->language->get('text_change_locality');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->session->data['error'] = null;

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['base'] = $server;

        $data['telephone_mask'] = $this->config->get('config_telephone_mask');

        if (isset($data['telephone_mask'])) {
            $data['telephone_mask_number'] = str_replace('#', '9', $this->config->get('config_telephone_mask'));
        }

        $data['taxnumber_mask'] = $this->config->get('config_taxnumber_mask');

        if (isset($data['taxnumber_mask'])) {
            $data['taxnumber_mask_number'] = str_replace('#', '*', $this->config->get('config_taxnumber_mask'));
        }

        if ($data['store_lists']) {
            if ('autosuggestion' == $this->config->get('config_store_location')) {
                //$data['zipcode'] = $location;

                // get selected suggestion text name

                if (isset($_COOKIE['location']) && $_COOKIE['location'] != $location) {
                    $p = $this->getLocationHeaderPlace($location);

                    $_COOKIE['location_name'] = $p;
                    setcookie('location_name', $p, time() + (86400 * 30 * 30 * 30 * 3), '/');
                }

                $_COOKIE['location'] = $location;

                /*if(isset($this->request->get['address'])) {
                    $_COOKIE['location_name'] = $this->request->get['address'];
                    setcookie('location_name', $this->request->get['address'], time() + (86400 * 30 * 30 * 30 * 3), "/");
                }*/

                setcookie('location', $location, time() + (86400 * 30 * 30 * 30 * 3), '/'); // 3 month expiry
            } else {
                $data['zipcode'] = $zipcode;
                $_COOKIE['zipcode'] = $zipcode;
                setcookie('zipcode', $zipcode, time() + (86400 * 30 * 30 * 30 * 3), '/'); // 3 month expiry
            }

            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header/storeHeader');
            $data['toHome'] = $this->url->link('common/home/toHome');
            $data['toStore'] = $this->url->link('common/home/toStore');

            //echo "<pre>";print_r($data['zipcode']);die;
            if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/information/stores.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/information/stores.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/information/stores.tpl', $data));
            }
        } else {
            //echo "<pre>";print_r("r");die;
            //$this->session->data['error'] = 'Zipcode not found';
            //$this->response->redirect($this->url->link('common/home/index'));

            //echo "<pre>";print_r("Cer");die;
            //No such store with <store_search_term_name>

            $data['text_error'] = htmlspecialchars_decode(sprintf($this->language->get('text_error'), $data['filter']));

            $this->document->setTitle(sprintf($this->language->get('text_error'), $data['filter']));

            $data['heading_title'] = htmlspecialchars_decode(sprintf($this->language->get('text_error'), $data['filter']));

            $data['button_continue'] = $this->language->get('button_continue');

            $data['continue'] = $this->url->link('common/home/toHome');

            $this->response->addHeader($this->request->server['SERVER_PROTOCOL'].' 404 Not Found');

            if ('autosuggestion' == $this->config->get('config_store_location')) {
                $data['zipcode'] = $location;

                // get selected suggestion text name

                $_COOKIE['location'] = $location;
                setcookie('location', $location, time() + (86400 * 30 * 30 * 30 * 3), '/'); // 3 month expiry
            } else {
                $data['zipcode'] = $zipcode;
                $_COOKIE['zipcode'] = $zipcode;
                setcookie('zipcode', $zipcode, time() + (86400 * 30 * 30 * 30 * 3), '/'); // 3 month expiry
            }

            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header/onlyHeader');

            if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/error/not_found.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/error/not_found.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
            }
        }
    }

    //start shopping

    public function start()
    {
        $this->session->data['config_store_id'] = $this->request->get['store_id'];

        $this->response->redirect($this->url->link('product/store', 'store_id='.$this->request->get['store_id'].''));
    }

    public function getStoreDetail()
    {
        $data = [];

        $this->load->model('tool/image');
        $this->load->model('setting/store');
        $this->language->load('checkout/delivery_time');

        $data['text_no_timeslot'] = $this->language->get('text_no_timeslot');

        $store_id = $this->request->get['store_id'];

        $data['store_info'] = $this->model_tool_image->getStore($store_id);

        $ratingDetails = $this->model_setting_store->getStoreRatingReviewCount($store_id);

        if ($data['store_info']['logo']) {
            $data['store_info']['image'] = $this->model_tool_image->resize($data['store_info']['logo'], $this->config->get('config_image_location_width'), $this->config->get('config_image_location_height'));
        } else {
            $data['store_info']['image'] = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_location_width'), $this->config->get('config_image_location_height'));
        }

        //$data['rating'] = ($ratingDetails['rating']/5)*100;
        //$data['rating'] = 3;
        /*if(substr($x,1) > .75) {
            $y = round($x*2)/2;
        } else {
            $y = floor($x*2)/2;
        }*/

        //echo "<pre>";print_r($ratingDetails['rating']);die;
        $tempRating = (int) $ratingDetails['rating'];
        $finalRating = 0;
        $pointRating = $ratingDetails['rating'] - $tempRating;
        if ($pointRating <= .25) {
            $finalRating = $tempRating;
        } elseif ($pointRating > .25 && $pointRating <= .75) {
            $finalRating = $tempRating + .5;
        } elseif ($pointRating > .75) {
            $finalRating = $tempRating + 1;
        }

        //echo "<pre>";print_r($finalRating);die;
        $data['rating'] = $finalRating;

        $data['review_count'] = $ratingDetails['review_count'];

        $data['store_open_hours'] = $this->model_tool_image->getStoreOpenHours($store_id, date('w'));

        $deliveryTypesResponse = $this->getDeliveryMethods($store_id);

        //echo "<pre>";print_r($deliveryTypesResponse);die;
        $deliveryTypes = ' -- ';
        $nextTimeslot = ' -- ';
        $distanceFromZipcode = ' -- ';
        $storeCategories = ' -- ';

        $deliveryTypeMethod = null;

        if (count($deliveryTypesResponse) > 1) {
            $deliveryTypes = $deliveryTypesResponse[0]['title'].'...';
            $deliveryTypeMethod = $deliveryTypesResponse[0]['code'];
        } elseif (1 == count($deliveryTypesResponse)) {
            $deliveryTypes = $deliveryTypesResponse[0]['title'];
            $deliveryTypeMethod = $deliveryTypesResponse[0]['code'];
        }

        //echo "<pre>";print_r($deliveryTypeMethod);die;

        if (isset($deliveryTypeMethod)) {
            $nextTimeslot = $this->getNextTimeslotForStore($store_id, $deliveryTypeMethod);
            //echo "<pre>";print_r($nextTimeslot);die;
        }

        //echo "<pre>";print_r($data);die;

        /*if( isset($data['store_info']['latitude']) && isset($data['store_info']['longitude']) ) {
            $distanceFromZipcode = $this->getDistanceFromZipcode($data['store_info']['latitude'],$data['store_info']['longitude'],$data['store_info']['store_zipcode']);
        }*/

        //echo "<pre>";print_r($distanceFromZipcode);die;

        $tempstoreCategories = $this->model_setting_store->getStoreCategories($store_id);

        //echo "<pre>";print_r($tempstoreCategories);die;

        if (count($tempstoreCategories) > 0) {
            $tempArray = [];
            foreach ($tempstoreCategories as $temp) {
                array_push($tempArray, $temp['name']);
            }

            $tmp = implode(',', $tempArray);
            $storeCategories = strlen($tmp) > 40 ? substr($tmp, 0, 40).'...' : $tmp;
        }
        //echo "<pre>";print_r($storeCategories);die;

        $data['store_detail_one'] = $deliveryTypes.' | '.$nextTimeslot.' | '.$distanceFromZipcode;
        $data['store_detail_two'] = $storeCategories;

        $data['store_next_timeslot'] = $nextTimeslot;

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['base'] = $server;

        //echo "<pre>";print_r($data);die;
        //echo "<pre>";print_r($data['store_open_hours']);die;
        //echo "<pre>";print_r($data);die;
        //Shipping data end

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/information/store_details_popup.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/information/store_details_popup.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/information/store_details_popup.tpl', $data));
        }
    }

    public function getDeliveryMethods($store_id)
    {
        $this->load->language('checkout/checkout');

        // Shipping Methods
        $method_data = [];

        $this->load->model('extension/extension');

        $results = $this->model_extension_extension->getExtensions('shipping');

        $this->load->model('tool/image');

        $store_info = $this->model_tool_image->getStore($store_id);

        $delivery_by_owner = $store_info['delivery_by_owner'];

        $pickup_delivery = $store_info['store_pickup_timeslots'];

        $free_delivery_amount = $store_info['min_order_cod'];

        $cost = 0;
        foreach ($results as $result) {
            if ($this->config->get($result['code'].'_status')) {
                $this->load->model('shipping/'.$result['code']);
                $quote = $this->{'model_shipping_'.$result['code']}->getQuote($cost, $store_info['name']);
                if ($quote) {
                    $method_data[] = [
                        'title' => $quote['title'],
                        'sort_order' => $quote['sort_order'],
                        'code' => $result['code'], ];
                }
            }
        }

        $sort_order = [];

        foreach ($method_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }
        array_multisort($sort_order, SORT_ASC, $method_data);

        return $method_data;
    }

    public function getNextTimeslotForStore($store_id, $deliveryTypeMethod)
    {
        //$data['shipping_method'] = 'express';//$deliveryTypeMethod;
        $data['shipping_method'] = $deliveryTypeMethod;
        $data['store_id'] = $store_id;

        $timeInMin = $this->load->Controller('checkout/delivery_time/getApiNextTimeSlot', $data);

        $time = '--';
        $text = ' min';

        if (is_numeric($timeInMin)) {
            if ($timeInMin > 120) {
                $time = ceil($timeInMin / 60);
                $text = ' hr';
            } else {
                $time = $timeInMin;
            }

            return $time.$text;
        } else {
            $time = $timeInMin;

            return $time;
        }
    }

    public function getDistanceFromZipcode($pickup_lat, $pickup_lng, $dropoff_zipcode)
    {
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$dropoff_zipcode.'&sensor=false';
        $details = file_get_contents($url);
        $result = json_decode($details, true);

        if (count($result['results']) > 0) {
            $dropoff_lat = $result['results'][0]['geometry']['location']['lat'];

            $dropoff_lng = $result['results'][0]['geometry']['location']['lng'];

            $distance = $this->distance($pickup_lat, $pickup_lng, $dropoff_lat, $dropoff_lng, 'K');

            //return $distance;
            return round($distance, 2).' Km';
        } else {
            return '--';
        }

        return $distance;
    }

    public function distance($lat1, $lon1, $lat2, $lon2, $unit)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ('K' == $unit) {
            return $miles * 1.609344;
        } elseif ('N' == $unit) {
            return $miles * 0.8684;
        } else {
            return $miles;
        }
    }

    public function getPlace($location)
    {
        $p = '';

        $userSearch = explode(',', $location);

        if (count($userSearch) >= 2) {
            $validateLat = is_numeric($userSearch[0]);
            $validateLat2 = is_numeric($userSearch[1]);

            $validateLat3 = strpos($userSearch[0], '.');
            $validateLat4 = strpos($userSearch[1], '.');

            if ($validateLat && $validateLat2 && $validateLat3 && $validateLat4) {
                $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$location.'&sensor=false&key='.$this->config->get('config_google_server_api_key');

                //echo "<pre>";print_r($url);die;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                $headers = [
                             'Cache-Control: no-cache', ];
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                //curl_setopt($ch, CURLOPT_REFERER, '52.178.112.211');

                curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_WHATEVER);

                $response = curl_exec($ch);

                //echo "<pre>";print_r($response);die;

                curl_close($ch);
                $output = json_decode($response);

                //print_r($output);die;

                if (isset($output)) {
                    $p = $output->results[0]->formatted_address;
                }
            }
        }

        return $p;
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
                         'Cache-Control: no-cache', ];
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //curl_setopt($ch, CURLOPT_REFERER, '52.178.112.211');

            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_WHATEVER);

            //curl_setopt($ch, CURLOPT_REFERER, 'https://demo.grocerypik.com');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $response = curl_exec($ch);
            curl_close($ch);
            $output1 = json_decode($response);

            //Get latitude and longitute from json data
            $latitude = $output1->results[0]->geometry->location->lat;
            $longitude = $output1->results[0]->geometry->location->lng;

            $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$latitude.','.$longitude.'&sensor=false&key='.$this->config->get('config_google_server_api_key');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);

            //curl_setopt($ch, CURLOPT_REFERER, 'https://demo.grocerypik.com');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

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

    public function getHeaderPlace($location)
    {
        if (isset($_COOKIE['location_name']) && !empty($_COOKIE['location_name'])) {
            $p = $_COOKIE['location_name'];
        } else {
            $p = '';

            $userSearch = explode(',', $location);

            if (count($userSearch) >= 2) {
                $validateLat = is_numeric($userSearch[0]);
                $validateLat2 = is_numeric($userSearch[1]);

                $validateLat3 = strpos($userSearch[0], '.');
                $validateLat4 = strpos($userSearch[1], '.');

                if ($validateLat && $validateLat2 && $validateLat3 && $validateLat4) {
                    //echo "<pre>";print_r("er");die;
                    try {
                        $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.urlencode($location).'&sensor=false&key='.$this->config->get('config_google_server_api_key');

                        //echo "<pre>";print_r($url);
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        $headers = [
                                     'Cache-Control: no-cache', ];
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);

                        //curl_setopt($ch, CURLOPT_REFERER, '52.178.112.211');
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_WHATEVER);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                        $response = curl_exec($ch);
                        curl_close($ch);

                        $output = json_decode($response);

                        //echo "<pre>";print_r($output);die;
                        if (isset($output)) {
                            foreach ($output->results[0]->address_components as $addres) {
                                if (isset($addres->types)) {
                                    if (in_array('sublocality_level_1', $addres->types)) {
                                        //echo "<pre>";print_r($addres);die;
                                        $p = $addres->long_name;
                                        break;
                                    }
                                }
                            }
                            if (isset($output->results[0]->formatted_address)) {
                                $p = $output->results[0]->formatted_address;
                            }

                            $_COOKIE['location_name'] = $p;
                            setcookie('location_name', $p, time() + (86400 * 30 * 30 * 30 * 3), '/');
                        }
                    } catch (Exception $e) {
                    }
                }
            }
        }

        return $p;
    }

    public function getLocationHeaderPlace($location)
    {
        $p = '';

        $userSearch = explode(',', $location);

        if (count($userSearch) >= 2) {
            $validateLat = is_numeric($userSearch[0]);
            $validateLat2 = is_numeric($userSearch[1]);

            $validateLat3 = strpos($userSearch[0], '.');
            $validateLat4 = strpos($userSearch[1], '.');

            if ($validateLat && $validateLat2 && $validateLat3 && $validateLat4) {
                //echo "<pre>";print_r("er");die;
                try {
                    $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.urlencode($location).'&sensor=false&key='.$this->config->get('config_google_server_api_key');

                    //echo "<pre>";print_r($url);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    $headers = [
                                 'Cache-Control: no-cache', ];
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);

                    //curl_setopt($ch, CURLOPT_REFERER, '52.178.112.211');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_WHATEVER);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                    $response = curl_exec($ch);
                    curl_close($ch);

                    $output = json_decode($response);

                    //echo "<pre>";print_r($output);die;
                    if (isset($output)) {
                        foreach ($output->results[0]->address_components as $addres) {
                            if (isset($addres->types)) {
                                if (in_array('sublocality_level_1', $addres->types)) {
                                    //echo "<pre>";print_r($addres);die;
                                    $p = $addres->long_name;
                                    break;
                                }
                            }
                        }
                        if (isset($output->results[0]->formatted_address)) {
                            $p = $output->results[0]->formatted_address;
                        }
                    }
                } catch (Exception $e) {
                }
            }
        }

        return $p;
    }
}
