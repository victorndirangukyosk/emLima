<?php

class Controllercheckoutdeliverytime extends Controller {

    public function index() {
        $data = [];

        $rangeonestart = "10:00:00";
        $rangeoneend = "18:59:59";

        $rangetwostart = "19:00:00";
        $rangetwoend = "21:59:59";

        $rangethreestart = "22:00:00";
        $rangethreeend = "23:59:59";

        $rangefourstart = "00:00:00";
        $rangefourend = "08:59:59";

        $rangefivestart = "09:00:00";
        $rangefiveend = "09:59:59";

        $log = new Log('error.log');
        $log->write('RANGE');
        $log->write(date("H:i:s"));
        $log->write(time());
        $log->write('RANGE');

        $same_day = date('Y-m-d');
        $next_day = date('d-m-Y', strtotime($same_day . "+1 days"));

        if (time() >= strtotime($rangeonestart) && time() <= strtotime($rangeoneend)) {
            $pre_defined_slots = array('06:00am - 08:00am');
            $selected_slot = $pre_defined_slots[0];
            $data['selected_slot'] = $selected_slot;
            $data['selected_date_slot'] = $next_day;
            $log->write($selected_slot);
            $log->write('RANGE ONE');
        }

        if (time() >= strtotime($rangetwostart) && time() <= strtotime($rangetwoend)) {
            $pre_defined_slots = array('08:00am - 10:00am');
            $selected_slot = $pre_defined_slots[0];
            $data['selected_slot'] = $selected_slot;
            $data['selected_date_slot'] = $next_day;
            $log->write('RANGE TWO');
        }

        if (time() >= strtotime($rangethreestart) && time() <= strtotime($rangethreeend)) {
            $pre_defined_slots = array('10:00am - 12:00pm');
            $selected_slot = $pre_defined_slots[0];
            $data['selected_slot'] = $selected_slot;
            $data['selected_date_slot'] = $next_day;
            $log->write('RANGE THREE');
        }

        if (time() >= strtotime($rangefourstart) && time() <= strtotime($rangefourend)) {
            $pre_defined_slots = array('02:00pm - 04:00pm');
            $selected_slot = $pre_defined_slots[0];
            $data['selected_slot'] = $selected_slot;
            $data['selected_date_slot'] = date('d-m-Y');
            $log->write('RANGE FOUR');
        }

        if (time() >= strtotime($rangefivestart) && time() <= strtotime($rangefiveend)) {
            $pre_defined_slots = array('04:00pm - 06:00pm');
            $selected_slot = $pre_defined_slots[0];
            $data['selected_slot'] = $selected_slot;
            $data['selected_date_slot'] = date('d-m-Y');
            $log->write('RANGE FIVE');
        }

        $this->language->load('checkout/delivery_time');

        $data['text_no_timeslot'] = $this->language->get('text_no_timeslot');

        $store_id = $this->request->get['store_id'];
        $shipping_method = $this->request->get['shipping_method'];

        //TO STORE STORE ID AND SHIPPING METHOD IN SESSION
        $this->session->data['store_id_for_timeslot'] = $store_id;
        $this->session->data['shipping_method_for_timeslot'] = $shipping_method;

        $getActiveDays = $this->getActiveDays($store_id, $shipping_method);
        $log = new Log('error.log');
        $log->write('timeslots');
        $log->write($store_id . 'ss' . $shipping_method);

        $log->write($getActiveDays);
        $data['dates'] = $this->getDates($getActiveDays, $store_id, $shipping_method);
        $data['timeslots'] = [];

        $data['formatted_dates'] = [];
        $log->write($data['dates']);
        foreach ($data['dates'] as $date) {
            $amTimeslot = [];
            $pmTimeslot = [];
            $inPmfirstTimeslot = [];

            $temp = $this->get_all_time_slot($store_id, $shipping_method, $date);

            foreach ($temp as $temp1) {
                $temp2 = explode('-', $temp1['timeslot']);

                if (false !== strpos($temp2[0], 'am')) {
                    array_push($amTimeslot, $temp1);
                } else {
                    if ('12' == substr($temp2[0], 0, 2)) {
                        array_push($inPmfirstTimeslot, $temp1);
                    } else {
                        array_push($pmTimeslot, $temp1);
                    }
                }
            }

            foreach ($inPmfirstTimeslot as $te) {
                array_push($amTimeslot, $te);
            }

            foreach ($pmTimeslot as $te) {
                array_push($amTimeslot, $te);
            }

            //echo "<pre>";print_r($temp);print_r($amTimeslot);

            if (count($amTimeslot) > 0) {
                $data['timeslots'][$date] = $amTimeslot;
                $data['formatted_dates'][] = $date;
            }

            //$data['timeslots'][$date] = $temp;
        }

        $data['dates'] = $data['formatted_dates'];

        $log->write('timeslots final');
        $log->write($data['dates']);
        $log->write($data['timeslots']);
        $data['store'] = $this->getStoreDetail($store_id);

        /* REMOVE DAYS BASED ON CITY OR REGION */
        $order_delivery_days = NULL;
        $city_details = NULL;
        $selected_address_id = $this->session->data['shipping_address_id'];
        $this->load->model('account/address');
        $customer_selected_address = $this->model_account_address->getAddress($selected_address_id);
        $log->write($customer_selected_address);
        if (isset($customer_selected_address) && is_array($customer_selected_address) && $customer_selected_address['city_id'] > 0) {
            $city_details = $this->model_account_address->getCityDetails($customer_selected_address['city_id']);
            $order_delivery_days = $this->model_account_address->getRegion($city_details['region_id']);
        }

        if ($order_delivery_days != NULL && is_array($order_delivery_days)) {
            $log->write($city_details);
            $log->write($order_delivery_days);
            foreach ($data['timeslots'] as $key => $value) {
                $order_delivery_days_timestamp = strtotime($key);
                $day_name = date('l', $order_delivery_days_timestamp);
                $day_name = strtolower($day_name);
                if ($order_delivery_days[$day_name] == 0) {
                    $log->write($key . ' ' . $day_name);
                    unset($data['timeslots'][$key]);
                }
            }
            foreach ($data['dates'] as $order_day_dates) {
                $order_day_dates_timestamp = strtotime($order_day_dates);
                $order_day_name = date('l', $order_day_dates_timestamp);
                $order_day_name = strtolower($order_day_name);
                if ($order_delivery_days[$order_day_name] == 0) {
                    $log->write($order_day_name);
                    if (($get_key = array_search($order_day_dates, $data['dates'])) !== false) {
                        unset($data['dates'][$get_key]);
                    }
                }
            }
            if (in_array($data['selected_date_slot'], $data['dates'])) {
                $log->write('FOUNDED');
            } else {
                $log->write('NOT FOUNDED');
                $data['selected_date_slot'] = reset($data['dates']);
            }
        }
        /* REMOVE DAYS BASED ON CITY OR REGION */

        //echo "<pre>";print_r($data);die;
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/delivery_time.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/checkout/delivery_time.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/checkout/delivery_time.tpl', $data));
        }
    }

    public function getOrderEditRawTimeslotFromAdmin() {
        $this->language->load('checkout/delivery_time');
        $this->load->model('account/order');

        $order_id = $this->request->get['order_id'];

        $order_info = $this->model_account_order->getAdminOrder($order_id);

        //echo "<pre>";print_r($order_info);die;
        $shipping_method = $order_info['shipping_code'];
        $store_id = $order_info['store_id'];
        $date_added = $order_info['date_added'];
        $delivery_date = $order_info['delivery_date'];

        $data = [];
        $this->language->load('checkout/delivery_time');
        $data['order_id'] = $order_id;
        $data['text_no_timeslot'] = $this->language->get('text_no_timeslot');

        //TO STORE STORE ID AND SHIPPING METHOD IN SESSION
        $this->session->data['store_id_for_timeslot'] = $store_id;
        $this->session->data['shipping_method_for_timeslot'] = $shipping_method;

        $getActiveDays = $this->getActiveDays($store_id, $shipping_method);
        $log = new Log('error.log');
        $log->write('timeslots');
        //$log->write($store_id."ss".$shipping_method);
        //$log->write($getActiveDays);
        //$data['dates'] = $this->getDates($getActiveDays, $store_id, $shipping_method);
        $data['dates'] = $this->getDatesbyOrderDate($getActiveDays, $store_id, $shipping_method, $date_added);
        $data['timeslots'] = [];

        //echo "<pre>";print_r($data['dates']);die;
        $data['formatted_dates'] = [];
        //$log->write($data['dates']);
        foreach ($data['dates'] as $date) {
            $amTimeslot = [];
            $pmTimeslot = [];
            $inPmfirstTimeslot = [];

            $temp = $this->get_all_time_slot_admin($store_id, $shipping_method, $date);

            foreach ($temp as $temp1) {
                $temp2 = explode('-', $temp1['timeslot']);

                if (false !== strpos($temp2[0], 'am')) {
                    array_push($amTimeslot, $temp1);
                } else {
                    if ('12' == substr($temp2[0], 0, 2)) {
                        array_push($inPmfirstTimeslot, $temp1);
                    } else {
                        array_push($pmTimeslot, $temp1);
                    }
                }
            }

            foreach ($inPmfirstTimeslot as $te) {
                array_push($amTimeslot, $te);
            }

            foreach ($pmTimeslot as $te) {
                array_push($amTimeslot, $te);
            }

            //echo "<pre>";print_r($temp);print_r($amTimeslot);

            if (count($amTimeslot) > 0) {
                $newDateTemp = date('m-d-Y', strtotime($date));
                //$data['timeslots'][$newDateTemp] = $amTimeslot;
                $data['timeslots'][$date] = $amTimeslot;
                $data['formatted_dates'][] = $date;
            }

            //$data['timeslots'][$date] = $temp;
        }

        $data['dates'] = $data['formatted_dates'];

        /*
          if usa_date uncomment below
         */

        /* $newDate = [];
          foreach ($data['dates'] as $dateT) {
          $newDate[] = date("m-d-Y", strtotime($dateT));
          }

          $data['dates'] = $newDate; */

        /*
          if usa_date uncomment above
         */

        $data['store'] = $this->getStoreDetail($store_id);
        //echo "<pre>";print_r($data);die;
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/edit_timeslot_order_by_admin.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/checkout/edit_timeslot_order_by_admin.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/checkout/edit_timeslot_order_by_admin.tpl', $data));
        }
    }

    public function saveOrderEditRawTimeslotFromAdmin() {
        $log = new Log('error.log');
        $log->write('saveOrderEditRawTimeslot');
        $log->write($this->request->post);

        $this->language->load('checkout/delivery_time');
        $this->load->model('account/order');

        $json['message'] = "<center style='color:green'>" . $this->language->get('text_edited_success') . '</center>';
        $this->load->model('api/checkout');
        $order_id = $this->request->post['order_id'];
        $order_info = $this->model_api_checkout->getOrder($order_id);

        $shipped = false;
        foreach ($this->config->get('config_shipped_status') as $key => $value) {
            if ($value == $order_info['order_status_id']) {
                $shipped = true;
                break;
            }
        }

        if ($shipped) {
            $json['message'] = "<p style='color:red'>" . $this->language->get('text_edited_shipping_error') . '</p>';
        }

        $data['timeslot_valid']['valid'] = true;

        if ('express.express' == $order_info['shipping_code']) {
            $delivery_date = date('d-m-Y');

            $settings = $this->getSettings('express', 0);
            $timeDiff = $settings['express_how_much_time'];

            $min = 0;
            if ($timeDiff) {
                $i = explode(':', $timeDiff);
                $min = $min + $i[0] * 60 + $i[1]; //add difference minut to current time
            }
            $to = date('h:ia', strtotime('+' . $min . ' minutes', strtotime(date('h:ia'))));

            $delivery_timeslot = date('h:ia') . ' - ' . $to;

            $this->request->post['delivery_date'] = $delivery_date;
            $this->request->post['delivery_timeslot'] = $delivery_timeslot;
        }

        $sendData = $this->request->post;
        $sendData['store_id'] = $order_info['store_id'];
        $sendData['shipping_code'] = $order_info['shipping_code'];

        $log->write($sendData);
        // $data['timeslot_valid'] = $this->load->controller('checkout/confirm/validateTimeslotEditOrder', $sendData);
        //Validation not required, as only valid dates are displayed in the UI for selection
        //commented this, as the method is redirecting to customer/Front controller
        $data['timeslot_valid']['valid'] = true;
        $log->write($data['timeslot_valid']);

        if (!$data['timeslot_valid']['valid']) {
            $log->write('timeslot_valid failed');

            $json['message'] = "<p style='color:red'>" . $this->language->get('text_edited_timeslot_error') . '</p>';
        }

        if (!$shipped && $data['timeslot_valid']['valid']) {
            $edited = $this->model_account_order->editTimeslotOrder($order_id, $this->request->post);

            $order_info = $this->model_api_checkout->getOrder($order_id);

            $log->write($edited);

            if ($edited && $order_info) {
                //echo "<pre>";print_r($order_info);die;
                $deliveryAlreadyCreated = $this->model_account_order->getOrderDSDeliveryId($order_id);

                //$deliveryAlreadyCreated = true;

                $log->write($deliveryAlreadyCreated);

                if ($deliveryAlreadyCreated) {
                    $delivery_priority = 'normal';

                    $temp = explode('.', $order_info['shipping_code']);
                    if (isset($temp[0]) && 'express' == $temp[0]) {
                        $delivery_priority = $temp[0];
                    }

                    $timeSlotAverage = $this->getTimeslotAverage($order_info['delivery_timeslot']);

                    $data['body'] = [
                        'manifest_id' => $deliveryAlreadyCreated, //order_id,
                        'delivery_priority' => $delivery_priority, // normal/express all small
                        'delivery_date' => $order_info['delivery_date'], //2017-04-13
                        'delivery_slot' => $timeSlotAverage,
                        'delivery_original_slot' => $order_info['delivery_timeslot'],
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
            }
        }

        if ($this->request->post['user_id'] != NULL && $this->request->post['user_id'] > 0) {
            $user_id = $this->request->post['user_id'];
            $this->load->model('user/user');
            $user_info = $this->model_user_user->getUser($user_id);
            if ($user_info != NULL) {
                // Add to activity log
                $log = new Log('error.log');
                $this->load->model('user/user_activity');

                $activity_data = [
                    'user_id' => $user_info['user_id'],
                    'name' => $user_info['firstname'] . ' ' . $user_info['lastname'],
                    'user_group_id' => $user_info['user_group_id'],
                    'order_id' => $order_id,
                ];
                $log->write('save order edit raw timeslot from admin');

                $this->model_user_user_activity->addActivity('order_time_slots_updated', $activity_data);

                $log->write('save order edit raw timeslot from admin');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function saveOrderEditRawTimeslotOverrideFromAdmin() {
        $log = new Log('error.log');
        $log->write('saveOrderEditRawTimeslot');
        $log->write($this->request->post);

        $this->language->load('checkout/delivery_time');
        $this->load->model('account/order');

        $json['message'] = "<center style='color:green'>" . $this->language->get('text_edited_success') . '</center>';
        $this->load->model('api/checkout');
        $order_id = $this->request->post['order_id'];
        $order_info = $this->model_api_checkout->getOrder($order_id);

        $shipped = false;
        /* foreach ($this->config->get('config_shipped_status') as $key => $value) {
          if($value == $order_info['order_status_id']) {
          $shipped = true;
          break;
          }
          } */

        $data['timeslot_valid']['valid'] = false;

        $temp2 = explode('-', $this->request->post['delivery_timeslot']);

        if (2 == count($temp2) && (false !== strpos($temp2[0], 'am') || false !== strpos($temp2[0], 'pm'))) {
            if (('00' <= substr($temp2[0], 0, 2)) && (substr($temp2[0], 0, 2) <= '12') && ('00' <= substr($temp2[1], 0, 2)) && (substr($temp2[1], 0, 2) <= '12')) {
                $temp2[0] = trim($temp2[0]);

                $temp2[1] = trim($temp2[1]);

                if (('00' <= substr($temp2[0], 3, 2)) && (substr($temp2[0], 3, 2) <= '59') && ('00' <= substr($temp2[1], 3, 2)) && (substr($temp2[1], 3, 2) <= '59')) {
                    $data['timeslot_valid']['valid'] = true;
                }
            }
        }

        if ('express.express' == $order_info['shipping_code']) {
            $delivery_date = date('d-m-Y');

            $settings = $this->getSettings('express', 0);
            $timeDiff = $settings['express_how_much_time'];

            $min = 0;
            if ($timeDiff) {
                $i = explode(':', $timeDiff);
                $min = $min + $i[0] * 60 + $i[1]; //add difference minut to current time
            }
            $to = date('h:ia', strtotime('+' . $min . ' minutes', strtotime(date('h:ia'))));

            $delivery_timeslot = date('h:ia') . ' - ' . $to;

            $this->request->post['delivery_date'] = $delivery_date;
            $this->request->post['delivery_timeslot'] = $delivery_timeslot;
        }

        $sendData = $this->request->post;
        $sendData['store_id'] = $order_info['store_id'];
        $sendData['shipping_code'] = $order_info['shipping_code'];

        if (!$shipped && $data['timeslot_valid']['valid']) {
            $edited = $this->model_account_order->editTimeslotOrder($order_id, $this->request->post);

            $order_info = $this->model_api_checkout->getOrder($order_id);

            $log->write($edited);

            if ($edited && $order_info) {
                //echo "<pre>";print_r($order_info);die;
                $deliveryAlreadyCreated = $this->model_account_order->getOrderDSDeliveryId($order_id);

                //$deliveryAlreadyCreated = true;

                $log->write($deliveryAlreadyCreated);

                if ($deliveryAlreadyCreated) {
                    $delivery_priority = 'normal';

                    $temp = explode('.', $order_info['shipping_code']);
                    if (isset($temp[0]) && 'express' == $temp[0]) {
                        $delivery_priority = $temp[0];
                    }

                    $timeSlotAverage = $this->getTimeslotAverage($order_info['delivery_timeslot']);

                    $data['body'] = [
                        'manifest_id' => $deliveryAlreadyCreated, //order_id,
                        'delivery_priority' => $delivery_priority, // normal/express all small
                        'delivery_date' => $order_info['delivery_date'], //2017-04-13
                        'delivery_slot' => $timeSlotAverage,
                        'delivery_original_slot' => $order_info['delivery_timeslot'],
                    ];

                    $log->write($data['body']);

                    $data['email'] = $this->config->get('config_delivery_username');
                    $data['password'] = $this->config->get('config_delivery_secret');
                    $response = $this->load->controller('deliversystem/deliversystem/getToken', $data);

                    $log->write('token');
                    $log->write($response);
                    if ($response['status']) {
                        $data['tokens'] = $response['token'];
                        $res = $this->load->controller('deliversystem/deliversystem/updateDeliveryDateTime', $data);
                        $log->write('reeponse');
                        $log->write($res);
                    }
                }
            }
        }

        if ($this->request->post['user_id'] != NULL && $this->request->post['user_id'] > 0) {
            $user_id = $this->request->post['user_id'];
            $this->load->model('user/user');
            $user_info = $this->model_user_user->getUser($user_id);
            if ($user_info != NULL) {
                // Add to activity log
                $log = new Log('error.log');
                $this->load->model('user/user_activity');

                $activity_data = [
                    'user_id' => $user_info['user_id'],
                    'name' => $user_info['firstname'] . ' ' . $user_info['lastname'],
                    'user_group_id' => $user_info['user_group_id'],
                    'order_id' => $order_id,
                ];
                $log->write('save order edit raw timeslot override from admin');

                $this->model_user_user_activity->addActivity('order_time_slots_updated', $activity_data);

                $log->write('save order edit raw timeslot override from admin');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getTimeslotAverage($timeslot) {
        $str = $timeslot; //"06:26pm - 08:32pm";
        $arr = explode('-', $str);
        //print_r($arr);
        if (2 == count($arr)) {
            $one = date('H:i', strtotime($arr[0]));
            $two = date('H:i', strtotime($arr[1]));

            return $one;

            $time1 = explode(':', $one);
            $time2 = explode(':', $two);
            if (2 == count($time1) && 2 == count($time2)) {
                $mid1 = ($time1[0] + $time2[0]) / 2;
                $mid2 = ($time1[1] + $time2[1]) / 2;

                $mid1 = round($mid1);
                $mid2 = round($mid2);

                if ($mid2 <= 9) {
                    $mid2 = '0' . $mid2;
                }
                if ($mid1 <= 9) {
                    $mid1 = '0' . $mid1;
                }

                //if 19.5 is mid1 then i send 19 integer part cant send decimals

                return $mid1 . ':' . $mid2;
            }
        }

        return false;
    }

    public function getApiNextTimeSlot($args) {
        $data = [];

        $store_id = $args['store_id'];
        $shipping_method = $args['shipping_method'];

        if ('express' == $shipping_method) {
            $settings = $this->getSettings('express', 0);

            $time_diff = $settings['express_delivery_time_diff'];

            $i = explode(':', $time_diff);
            $min = $i[0] * 60 + $i[1]; //add difference minut to current time

            return $min;
        }

        $this->session->data['store_id_for_timeslot'] = $store_id;
        $this->session->data['shipping_method_for_timeslot'] = $shipping_method;

        $getActiveDays = $this->getActiveDays($store_id, $shipping_method);
        $log = new Log('error.log');

        $log->write($getActiveDays);
        $data['dates'] = $this->getDates($getActiveDays, $store_id, $shipping_method);
        $data['timeslots'] = [];

        $log->write($data['dates']);
        foreach ($data['dates'] as $date) {
            $amTimeslot = [];
            $pmTimeslot = [];
            $inPmfirstTimeslot = [];

            $temp = $this->get_all_time_slot($store_id, $shipping_method, $date);

            foreach ($temp as $temp1) {
                $temp2 = explode('-', $temp1['timeslot']);

                if (false !== strpos($temp2[0], 'am')) {
                    array_push($amTimeslot, $temp1);
                } else {
                    if ('12' == substr($temp2[0], 0, 2)) {
                        array_push($inPmfirstTimeslot, $temp1);
                    } else {
                        array_push($pmTimeslot, $temp1);
                    }
                }
            }

            foreach ($inPmfirstTimeslot as $te) {
                array_push($amTimeslot, $te);
            }

            foreach ($pmTimeslot as $te) {
                array_push($amTimeslot, $te);
            }

            //echo "<pre>";print_r($temp);print_r($amTimeslot);
            $data['timeslots'][$date] = $amTimeslot;
            //$data['timeslots'][$date] = $temp;
        }

        //echo "<pre>";print_r($data);die;

        if (count($data['dates']) > 0) {
            if (date('d-m-Y') == $data['dates'][0]) {
                // todays date avialable
                if (isset($data['timeslots'][$data['dates'][0]]) && count($data['timeslots'][$data['dates'][0]]) > 0) {
                    //echo "<pre>";print_r($data['timeslots'][$data['dates'][0]]);die;

                    if (count($data['timeslots'][$data['dates'][0]]) > 0) {
                        $temp = explode('-', $data['timeslots'][$data['dates'][0]][0]['timeslot']);

                        if ('normal' == $shipping_method) {
                            $settings = $this->getSettings('normal', 0);
                            $timeDiff = $settings['normal_delivery_time_diff'];
                        } else {
                            $storeDetail = $this->getStoreDetail($store_id);
                            $timeDiff = $storeDetail['delivery_time_diff'];
                        }

                        //echo "<pre>";print_r($timeDiff);die;

                        $is_enabled = $this->timeIsBetween($temp[0], $temp[1], date('h:ia'), $timeDiff);

                        //echo "<pre>";print_r($is_enabled);die;

                        if ($is_enabled) {
                            $i = explode(':', $timeDiff);
                            $min = $i[0] * 60 + $i[1]; //add difference minut to current time
                            //echo "<pre>";print_r($min);die;
                            return $min;
                        } else {
                            return '--';
                        }
                    } else {
                        return '--';
                    }
                }
            } else {
                $timestamp = strtotime($data['dates'][0]);
                //$day = date('D', $timestamp); // thr
                $day = date('l', $timestamp); //Thursday

                return $day;
            }
        } else {
            return '--';
        }

        return '--';
    }

    public function getRawTimeslot() {
        $data = [];

        $this->language->load('checkout/delivery_time');

        $data['text_no_timeslot'] = $this->language->get('text_no_timeslot');

        $store_id = $this->request->get['store_id'];
        $shipping_method = $this->request->get['shipping_method'];

        $data['store'] = $this->getStoreDetail($store_id);
        //echo "<pre>";print_r($data);die;
        //Shipping data start

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

        $store_total = $this->cart->getSubTotal($store_id);

        if ($store_total > $free_delivery_amount) {
            $cost = 0;
        } else {
            $cost = $store_info['cost_of_delivery'];
        }
        // code = pickup
        // code = store_delivery
        //echo "<pre>";print_r($results);die;

        foreach ($results as $result) {
            if ($this->config->get($result['code'] . '_status')) {
                if ('normal' == $result['code']) {
                    //echo "<pre>";print_r('normal');die;
                    //if ($delivery_by_owner) {
                    $this->load->model('shipping/' . $result['code']);
                    $quote = $this->{'model_shipping_' . $result['code']}->getQuote($cost, $store_info['name']);

                    if ($quote) {
                        $method_data[$result['code']] = [
                            'title' => $quote['title'],
                            'quote' => $quote['quote'],
                            'sort_order' => $quote['sort_order'],
                            'error' => $quote['error'],
                            'shipping_timeslots' => $this->getRawTimeslots($quote['code'], $store_id),
                        ];

                        //echo "<pre>";print_r(key($method_data[$result['code']]['quote']);die;
                    }

                    //}
                } elseif ('express' == $result['code']) {
                    //echo "<pre>";print_r('express');die;
                    $this->load->model('shipping/' . $result['code']);
                    $quote = $this->{'model_shipping_' . $result['code']}->getQuote($cost, $store_info['name']);

                    if ($quote) {
                        $method_data[$result['code']] = [
                            'title' => $quote['title'],
                            'quote' => $quote['quote'],
                            'sort_order' => $quote['sort_order'],
                            'error' => $quote['error'],
                            'shipping_timeslots' => $this->getRawTimeslots($quote['code'], $store_id),
                        ];
                    }
                } elseif ('store_delivery' == $result['code']) {
                    if ($delivery_by_owner) {
                        $this->load->model('shipping/' . $result['code']);
                        $quote = $this->{'model_shipping_' . $result['code']}->getQuote($cost, $store_info['name']);
                        if ($quote) {
                            $method_data[$result['code']] = [
                                'title' => $quote['title'],
                                'quote' => $quote['quote'],
                                'sort_order' => $quote['sort_order'],
                                'error' => $quote['error'],
                                'shipping_timeslots' => $this->getRawTimeslots($quote['code'], $store_id),
                            ];
                        }
                    }
                } elseif ('pickup' == $result['code']) {
                    if ($pickup_delivery) {
                        $this->load->model('shipping/' . $result['code']);
                        $quote = $this->{'model_shipping_' . $result['code']}->getQuote($cost, $store_info['name']);
                        if ($quote) {
                            $method_data[$result['code']] = [
                                'title' => $quote['title'],
                                'quote' => $quote['quote'],
                                'sort_order' => $quote['sort_order'],
                                'error' => $quote['error'],
                                'shipping_timeslots' => $this->getRawTimeslots($quote['code'], $store_id),
                            ];
                        }
                    }
                } else {
                    $this->load->model('shipping/' . $result['code']);
                    $quote = $this->{'model_shipping_' . $result['code']}->getQuote($cost, $store_info['name']);
                    if ($quote) {
                        $method_data[$result['code']] = [
                            'title' => $quote['title'],
                            'quote' => $quote['quote'],
                            'sort_order' => $quote['sort_order'],
                            'error' => $quote['error'],
                            'shipping_timeslots' => $this->getRawTimeslots($quote['code'], $store_id),
                        ];
                    }
                }
            }
        }

        $sort_order = [];

        foreach ($method_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }
        array_multisort($sort_order, SORT_ASC, $method_data);

        $this->session->data['shipping_methods'][$store_id] = $method_data;

        $data['text_shipping_method'] = $this->language->get('text_shipping_method');
        $data['text_comments'] = $this->language->get('text_comments');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['button_continue'] = $this->language->get('button_continue');

        if (empty($this->session->data['shipping_methods'][$store_id])) {
            $data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['shipping_methods'][$store_id])) {
            $data['shipping_methods'] = $this->session->data['shipping_methods'][$store_id];
        } else {
            $data['shipping_methods'] = [];
        }

        if (isset($this->session->data['shipping_method'][$store_id])) {
            $found = false;

            foreach ($this->session->data['shipping_method'] as $key => $value) {
                if ($store_id == $key) {
                    if (isset($value['shipping_method']['code'])) {
                        $data['code'] = $value['shipping_method']['code'];
                        $found = true;
                        break;
                    }
                }
            }
            if (false === $found) {
                $data['code'] = '';
            }
        } else {
            $data['code'] = 'store_delivery.store_delivery';
        }
        //echo "<pre>";print_r($method_data);die;
        $data['code'] = 'express.express';

        $data['store_id'] = $store_id;

        //Shipping data end

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/delivery_time.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/information/delivery_time.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/information/delivery_time.tpl', $data));
        }
    }

    public function get_all_time_slot($store_id, $shipping_method, $date) {
        $log = new Log('error.log');
        $log->write('get_all_time_slot');

        $day = date('w', strtotime($date));
        $log->write($day);

        if (isset($date)) {
            $delivery_date = $date;
            //$this->session->data['dates'][$store_id] = $delivery_date;

            $log->write($delivery_date);
        } else {
            $delivery_date = '';
        }

        $data['timeslot'] = $this->newGetStoreTimeSlot($store_id, $shipping_method, $day, $date);

        return $data['timeslot'];
    }

    public function get_all_time_slot_Admin($store_id, $shipping_method, $date) {
        $log = new Log('error.log');
        $log->write('get_all_time_slot');

        $day = date('w', strtotime($date));
        $log->write($day);

        if (isset($date)) {
            $delivery_date = $date;
            //$this->session->data['dates'][$store_id] = $delivery_date;

            $log->write($delivery_date);
        } else {
            $delivery_date = '';
        }

        $data['timeslot'] = $this->newGetStoreTimeSlotAdmin($store_id, $shipping_method, $day, $date);

        return $data['timeslot'];
    }

    public function getStoreDetail($store_id) {
        $this->load->model('tool/image');

        return $this->model_tool_image->getStoreData($store_id);
    }

    public function getSettings($code, $store_id = 0) {
        $this->load->model('setting/setting');

        return $this->model_setting_setting->getSetting($code, $store_id);
    }

    protected function getDates($getActiveDays, $store_id, $shipping_method) {
        $avalday = [];

        $log = new Log('error.log');
        $log->write('getDates');
        //CREATING ARRAY FOR THE AVAILABLE DAYS OF THE WEEK.

        foreach ($getActiveDays as $ad) {
            $avalday[] = $ad['day'];
        }

        $log->write($avalday);

        //echo "<pre>";print_r($avalday);die;
        //CHECKS IF CURRENT DAY IS IN THE LIST OF AVAILABLE DAYS OF WEEK
        if (in_array(date('w'), $avalday)) {
            //echo "<pre>";print_r("current day");die;
            $date = $this->checkCurrentDateTs($store_id, $shipping_method);

            if ($date) {
                $tmpDate = date('Y-m-d');
            } else {
                //echo "<pres>";print_r($date."hvhj");die;
                $tmpDate = date('Y-m-d', strtotime('+1 Days'));
            }
        } else {
            //echo "<pre>";print_r("current no");die;
            $tmpDate = date('Y-m-d');
        }

        $log->write($tmpDate);
        //CREATES THE LIST OF DAYS TO DISPLAY USER AS AVAILABLE DAYS OF DELIVERY.
        $nextBusinessDay = [];
        $j = 0;

        $log->write(date('w'));

        /* for($i=date("w", strtotime($tmpDate));$i<=6;$i++) {
          if(in_array($i, $avalday)) {
          $nextBusinessDay[] = date('d-m-Y', strtotime($tmpDate.' +'.$j.'Days'));
          }
          $j++;
          } */
        $log->write($nextBusinessDay);

        $shipping_method = explode('.', $shipping_method);

        if ('normal' == $shipping_method[0] || 'express' == $shipping_method[0]) {
            if ($this->config->get('normal_number_of_days')) {
                $forwardDays = $this->config->get('normal_number_of_days');
            } else {
                $forwardDays = 7;
            }
        } else {
            $forwardDays = 7;
        }

        //echo "<pre>";print_r($nextBusinessDay);die;
        if (count($nextBusinessDay) < $forwardDays) {
            $end;
            if (!empty($nextBusinessDay)) {
                $log->write($nextBusinessDay);

                $end = end($nextBusinessDay);

                $log->write($end);

                $log->write('end');

                for ($i = 1; $i <= 49; ++$i) {
                    $tmp_date = date('d-m-Y', strtotime($end . ' +' . $i . ' Days'));

                    $day = date('w', strtotime($tmp_date));

                    $daycheck = $this->futurecheckDateTs($this->session->data['store_id_for_timeslot'], $this->session->data['shipping_method_for_timeslot'], $day);

                    $log->write('daycheck');
                    $log->write($daycheck);

                    //echo "<pre>";print_r($daycheck);
                    if (!empty($daycheck)) {
                        $nextBusinessDay[] = $tmp_date;
                    }
                    if (count($nextBusinessDay) == $forwardDays) {
                        break;
                    }
                }
            } else {
                $end = date('d-m-Y');
                for ($i = 0; $i <= 49; ++$i) {
                    $tmp_date = date('d-m-Y', strtotime($end . ' +' . $i . ' Days'));

                    $day = date('w', strtotime($tmp_date));

                    $daycheck = $this->futurecheckDateTs($this->session->data['store_id_for_timeslot'], $this->session->data['shipping_method_for_timeslot'], $day);

                    if (!empty($daycheck)) {
                        $nextBusinessDay[] = $tmp_date;
                    }
                    if (count($nextBusinessDay) == $forwardDays) {
                        break;
                    }
                }
            }
        }

        /* $i = 0;
          $nextBusinessDay = array();
          for ($i=0; $i <=6; $i++) {
          if (in_array($i, $avalday)) {
          $nextBusinessDay[] = date('d-m-Y', strtotime($tmpDate . ' +' . $i . ' Days'));
          }

          }
          $total = count($nextBusinessDay);
          if ($total <= 7) {
          $length = 7 -  $total;
          for ($i=7; $i <= 6 +$length; $i++) {
          if (in_array($i, $avalday)) {
          $nextBusinessDay[] = date('d-m-Y', strtotime($tmpDate . ' +' . $i . ' Days'));
          }

          }

          } */
        return $nextBusinessDay;
    }

    protected function getDatesbyOrderDate($getActiveDays, $store_id, $shipping_method, $date_added) {
        $avalday = [];

        $log = new Log('error.log');
        $log->write('getDates');
        //CREATING ARRAY FOR THE AVAILABLE DAYS OF THE WEEK.

        foreach ($getActiveDays as $ad) {
            $avalday[] = $ad['day'];
        }

        $log->write($avalday);

        //echo "<pre>";print_r($avalday);die;
        //CHECKS IF CURRENT DAY IS IN THE LIST OF AVAILABLE DAYS OF WEEK
        if (in_array(date('w'), $avalday)) {
            //echo "<pre>";print_r("current day");die;
            $date = $this->checkCurrentDateTs($store_id, $shipping_method);

            if ($date) {
                $tmpDate = date('Y-m-d');
            } else {
                //echo "<pres>";print_r($date."hvhj");die;
                $tmpDate = date('Y-m-d', strtotime('+1 Days'));
            }
        } else {
            //echo "<pre>";print_r("current no");die;
            $tmpDate = date('Y-m-d');
        }

        $log->write($tmpDate);
        //CREATES THE LIST OF DAYS TO DISPLAY USER AS AVAILABLE DAYS OF DELIVERY.
        $nextBusinessDay = [];
        $j = 0;

        $log->write(date('w'));

        /* for($i=date("w", strtotime($tmpDate));$i<=6;$i++) {
          if(in_array($i, $avalday)) {
          $nextBusinessDay[] = date('d-m-Y', strtotime($tmpDate.' +'.$j.'Days'));
          }
          $j++;
          } */
        $log->write($nextBusinessDay);

        $shipping_method = explode('.', $shipping_method);

        if ('normal' == $shipping_method[0] || 'express' == $shipping_method[0]) {
            if ($this->config->get('normal_number_of_days')) {
                $forwardDays = $this->config->get('normal_number_of_days');
            } else {
                $forwardDays = 7;
            }
        } else {
            $forwardDays = 7;
        }

        //echo "<pre>";print_r($nextBusinessDay);die;
        if (count($nextBusinessDay) < $forwardDays) {
            $end;
            if (!empty($nextBusinessDay)) {
                $log->write($nextBusinessDay);

                $end = end($nextBusinessDay);

                $log->write($end);

                $log->write('end');

                for ($i = 1; $i <= 49; ++$i) {
                    $tmp_date = date('d-m-Y', strtotime($end . ' +' . $i . ' Days'));

                    $day = date('w', strtotime($tmp_date));

                    $daycheck = $this->futurecheckDateTs($this->session->data['store_id_for_timeslot'], $this->session->data['shipping_method_for_timeslot'], $day);

                    $log->write('daycheck');
                    $log->write($daycheck);

                    //echo "<pre>";print_r($daycheck);
                    if (!empty($daycheck)) {
                        $nextBusinessDay[] = $tmp_date;
                    }
                    if (count($nextBusinessDay) == $forwardDays) {
                        break;
                    }
                }
            } else {
                // $end = date('d-m-Y');//if current date
                // $end =($date_added);//if based on order date// format check
                $end = date('d-m-Y', strtotime("-2 days"));
                ; //just show two days earlier
                //echo "<pre>";print_r($end);die;

                for ($i = 0; $i <= 49; ++$i) {
                    $tmp_date = date('d-m-Y', strtotime($end . ' +' . $i . ' Days'));

                    $day = date('w', strtotime($tmp_date));

                    $daycheck = $this->futurecheckDateTs($this->session->data['store_id_for_timeslot'], $this->session->data['shipping_method_for_timeslot'], $day);

                    if (!empty($daycheck)) {
                        $nextBusinessDay[] = $tmp_date;
                    }
                    if (count($nextBusinessDay) == $forwardDays) {
                        break;
                    }
                }
            }
        }

        /* $i = 0;
          $nextBusinessDay = array();
          for ($i=0; $i <=6; $i++) {
          if (in_array($i, $avalday)) {
          $nextBusinessDay[] = date('d-m-Y', strtotime($tmpDate . ' +' . $i . ' Days'));
          }

          }
          $total = count($nextBusinessDay);
          if ($total <= 7) {
          $length = 7 -  $total;
          for ($i=7; $i <= 6 +$length; $i++) {
          if (in_array($i, $avalday)) {
          $nextBusinessDay[] = date('d-m-Y', strtotime($tmpDate . ' +' . $i . ' Days'));
          }

          }

          } */
        return $nextBusinessDay;
    }

    public function getActiveDays($store_id, $method) {
        $shipping_method = explode('.', $method);

        if ('normal' == $shipping_method[0]) {
            $this->db->group_by('day');
            $this->db->select('day', false);
            $this->db->where('status', '1');
            $rows = $this->db->get('normal_delivery_timeslot')->rows;

            return $rows;
        } elseif ('express' == $shipping_method[0]) {
            $this->db->group_by('day');
            $this->db->select('day', false);
            $this->db->where('status', '1');
            $rows = $this->db->get('express_delivery_timeslot')->rows;

            return $rows;
        } elseif ('pickup' == $shipping_method[0]) {
            $this->db->group_by('day');
            $this->db->select('day', false);
            $this->db->where('status', '1');
            $this->db->where('store_id', $store_id);
            $rows = $this->db->get('store_pickup_timeslot')->rows;

            return $rows;
        } else {
            $this->db->group_by('day');
            $this->db->select('day', false);
            $this->db->where('status', '1');
            $this->db->where('store_id', $store_id);
            $rows = $this->db->get('store_delivery_timeslot')->rows;

            return $rows;
        }
    }

    public function newGetStoreTimeSlot($store_id, $method, $day, $date) {
        $log = new Log('error.log');
        $log->write('newGetStoreTimeSlot');

        $storeDetail = $this->getStoreDetail($store_id);
        $timeDiff = $storeDetail['delivery_time_diff'];

        $shipping_method = explode('.', $method);

        if ('normal' == $shipping_method[0]) {
            $settings = $this->getSettings('normal', 0);
            $timeDiff = $settings['normal_delivery_time_diff'];

            //echo "<pre>";print_r($timeDiff);die;
            $this->db->where('day', $day);
            $this->db->select('timeslot', false);
            $this->db->where('status', '1');
            $rows = $this->db->get('normal_delivery_timeslot')->rows;
            // return $rows;
        } elseif ('express' == $shipping_method[0]) {
            $settings = $this->getSettings('express', 0);
            $timeDiff = $settings['express_delivery_time_diff'];

            $this->db->where('day', $day);
            $this->db->select('timeslot', false);
            $this->db->where('status', '1');
            $rows = $this->db->get('express_delivery_timeslot')->rows;
            // return $rows;
        } elseif ('pickup' == $shipping_method[0]) {
            $this->db->where('day', $day);
            $this->db->select('timeslot', false);
            $this->db->where('status', '1');
            $this->db->where('store_id', $store_id);
            $rows = $this->db->get('store_pickup_timeslot')->rows;

            return $rows;
        } else {
            $this->db->where('day', $day);
            $this->db->select('timeslot', false);
            $this->db->where('status', '1');
            $this->db->where('store_id', $store_id);
            $rows = $this->db->get('store_delivery_timeslot')->rows;
            //echo "<pre>";print_r($timeDiff);die;
            //return $rows;
        }

        $is_enabled = false;
        $time_slot_rows = [];
        /* echo "<pre>";
          print_r($rows); */
        $log->write('newGetStoreTimeSlot rows');
        $log->write(date('h:ia'));

        //$log->write($rows);
        foreach ($rows as $tslot) {
            $row['timeslot'] = $tslot['timeslot'];
            $temp = explode('-', $tslot['timeslot']);
            $date = $date;

            $log->write($date);

            if ($date != date('d-m-Y')) {
                $log->write($date);
                array_push($time_slot_rows, $row);
            } else {
                $log->write(date('d-m-Y'));

                $is_enabled = $this->timeIsBetween($temp[0], $temp[1], date('h:ia'), $timeDiff);
                //echo "<pre>";print_r($is_enabled);
                //echo "<pre>";print_r("er");die;
                if ($is_enabled) {
                    array_push($time_slot_rows, $row);
                }
            }
        }
        $log->write('time_slot_rows row');
        //$log->write($time_slot_rows);
        //print_r($time_slot_rows);die;

        return $time_slot_rows;
        //return $is_enabled;
    }

    public function newGetStoreTimeSlotAdmin($store_id, $method, $day, $date) {
        $log = new Log('error.log');
        $log->write('newGetStoreTimeSlot');

        $storeDetail = $this->getStoreDetail($store_id);
        $timeDiff = $storeDetail['delivery_time_diff'];

        $shipping_method = explode('.', $method);

        if ('normal' == $shipping_method[0]) {
            $settings = $this->getSettings('normal', 0);
            $timeDiff = $settings['normal_delivery_time_diff'];

            //echo "<pre>";print_r($timeDiff);die;
            $this->db->where('day', $day);
            $this->db->select('timeslot', false);
            $this->db->where('status', '1');
            $rows = $this->db->get('normal_delivery_timeslot')->rows;
            // return $rows;
        } elseif ('express' == $shipping_method[0]) {
            $settings = $this->getSettings('express', 0);
            $timeDiff = $settings['express_delivery_time_diff'];

            $this->db->where('day', $day);
            $this->db->select('timeslot', false);
            $this->db->where('status', '1');
            $rows = $this->db->get('express_delivery_timeslot')->rows;
            // return $rows;
        } elseif ('pickup' == $shipping_method[0]) {
            $this->db->where('day', $day);
            $this->db->select('timeslot', false);
            $this->db->where('status', '1');
            $this->db->where('store_id', $store_id);
            $rows = $this->db->get('store_pickup_timeslot')->rows;

            return $rows;
        } else {
            $this->db->where('day', $day);
            $this->db->select('timeslot', false);
            $this->db->where('status', '1');
            $this->db->where('store_id', $store_id);
            $rows = $this->db->get('store_delivery_timeslot')->rows;
            //echo "<pre>";print_r($timeDiff);die;
            //return $rows;
        }

        $is_enabled = false;
        $time_slot_rows = [];
        /* echo "<pre>";
          print_r($rows); */
        $log->write('newGetStoreTimeSlot rows');
        $log->write(date('h:ia'));

        //$log->write($rows);
        foreach ($rows as $tslot) {
            $row['timeslot'] = $tslot['timeslot'];
            $temp = explode('-', $tslot['timeslot']);
            $date = $date;

            $log->write($date);

            if ($date != date('d-m-Y')) {
                $log->write($date);
                array_push($time_slot_rows, $row);
            } else {
                // $log->write(date('d-m-Y'));
                // $is_enabled = $this->timeIsBetween($temp[0], $temp[1], date('h:ia'), $timeDiff);
                // //echo "<pre>";print_r($is_enabled);
                // //echo "<pre>";print_r("er");die;
                // if ($is_enabled) {
                //     array_push($time_slot_rows, $row);
                // }

                $log->write($date);
                array_push($time_slot_rows, $row);
            }
        }
        $log->write('time_slot_rows row');
        //$log->write($time_slot_rows);
        //print_r($time_slot_rows);die;

        return $time_slot_rows;
        //return $is_enabled;
    }

    public function getStoreTimeSlot($store_id, $method, $day) {
        /* $storeDetail = $this->getStoreDetail($store_id);
          $timeDiff = $storeDetail['delivery_time_diff']; */
        $shipping_method = explode('.', $method);

        if ('normal' == $shipping_method[0]) {
            $settings = $this->getSettings('normal', 0);
            $timeDiff = $settings['normal_delivery_time_diff'];

            $this->db->where('day', $day);
            $this->db->select('timeslot', false);
            $this->db->where('status', '1');
            $this->db->where('store_id', $store_id);
            $rows = $this->db->get('normal_delivery_timeslot')->rows;
            // return $rows;
        } else {
            $settings = $this->getSettings('express', 0);
            $timeDiff = $settings['express_delivery_time_diff'];

            $this->db->where('day', $day);
            $this->db->select('timeslot', false);
            $this->db->where('status', '1');
            //$this->db->where('store_id', $store_id);
            $rows = $this->db->get('express_delivery_timeslot')->rows;
            // return $rows;
        }

        $is_enabled = false;
        $time_slot_rows = [];
        /* echo "<pre>";
          print_r($rows); */
        foreach ($rows as $tslot) {
            $row['timeslot'] = $tslot['timeslot'];
            $temp = explode('-', $tslot['timeslot']);
            $date = $this->request->get['date'];
            if (date('N') != $day) {
                array_push($time_slot_rows, $row);
            } else {
                $is_enabled = $this->timeIsBetween($temp[0], $temp[1], date('h:ia'), $timeDiff);
                if ($is_enabled) {
                    array_push($time_slot_rows, $row);
                }
            }
        }
        //print_r($time_slot_rows);
        //die;
        return $time_slot_rows;
        //return $is_enabled;
    }

    public function get_time_slot() {
        $store_id = $this->request->get['store_id'];
        $shipping_method = $this->request->get['shipping_method'];
        $date = $this->request->get['date'];
        $day = date('w', strtotime($date));

        if (isset($this->request->get['date'])) {
            $delivery_date = $this->request->get['date'];
            //$this->session->data['dates'][$store_id] = $delivery_date;
        } else {
            $delivery_date = '';
        }

        $data['timeslot'] = $this->getStoreTimeSlot($store_id, $shipping_method, $day);

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/delivery_slot.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/checkout/delivery_slot.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/checkout/delivery_slot.tpl', $data));
        }
    }

    public function checkCurrentDateTs($store_id, $method) {
        $storeDetail = $this->getStoreDetail($store_id);
        $timeDiff = $storeDetail['delivery_time_diff'];

        $log = new Log('error.log');
        $log->write('checkCurrentDateTs');

        $day = date('w');
        //print_r($day);
        $shipping_method = explode('.', $method);
        if ('normal' == $shipping_method[0]) {
            $settings = $this->getSettings('normal', 0);
            $timeDiff = $settings['normal_delivery_time_diff'];

            $this->db->where('day', $day);
            $this->db->select('timeslot', false);
            $this->db->where('status', '1');
            $timeslots = $this->db->get('normal_delivery_timeslot')->rows;
        } elseif ('express' == $shipping_method[0]) {
            $settings = $this->getSettings('express', 0);
            $timeDiff = $settings['express_delivery_time_diff'];

            $this->db->where('day', $day);
            $this->db->select('timeslot', false);
            $this->db->where('status', '1');
            $timeslots = $this->db->get('express_delivery_timeslot')->rows;
        } elseif ('pickup' == $shipping_method[0]) {
            $this->db->where('day', $day);
            $this->db->select('timeslot', false);
            $this->db->where('status', '1');
            $this->db->where('store_id', $store_id);
            $timeslots = $this->db->get('store_pickup_timeslot')->rows;
        } else {
            $this->db->where('day', $day);
            $this->db->select('timeslot', false);
            $this->db->where('status', '1');
            $this->db->where('store_id', $store_id);
            $timeslots = $this->db->get('store_delivery_timeslot')->rows;
        }

        $is_enabled = false;

        foreach ($timeslots as $timeslot) {
            $temp = explode('-', $timeslot['timeslot']);
            $is_enabled = $this->timeIsBetween($temp[0], $temp[1], date('h:ia'), $timeDiff);

            if ($is_enabled) {
                return $is_enabled;
            }
        }
        //echo "<pre>";print_r("ena".$is_enabled);die;
        return $is_enabled;
    }

    private function timeIsBetween($from, $to, $time, $time_diff = false) {
        //echo "time";print_r($from.$to.$time.$time_diff);
        $log = new Log('error.log');
        $log->write('time diff');
        $log->write($from . $to . $time . $time_diff);

        $to = trim($to);
        //calculate from_time in minuts
        $i = explode(':', $to);
        if (12 == $i[0]) {
            $to_min = substr($i[1], 0, 2);
        } else {
            $to_min = ($i[0] * 60) + substr($i[1], 0, 2);
        }
        //if pm add 12 hours
        $am_pm = substr($to, -2);
        if ('pm' == $am_pm) {
            $to_min += 12 * 60;
        }

        //calculate time in minuts
        $i = explode(':', $time);
        if (12 == $i[0]) {
            $min = substr($i[1], 0, 2);
        } else {
            $min = $i[0] * 60 + substr($i[1], 0, 2);
        }

        //if pm add 12 hours
        $am_pm = substr($time, -2);
        if ('pm' == $am_pm) {
            $min += 12 * 60;
        }

        //if time difference
        if ($time_diff) {
            $i = explode(':', $time_diff);
            $min = $min + $i[0] * 60 + $i[1]; //add difference minut to current time
        }

        $log->write($min);
        $log->write($to_min);
        if ($min < $to_min) {
            return true;
        } else {
            return 0;
        }
    }

    //save deliery date / timeslot
    public function save() {
        if (isset($this->request->post['store_id'])) {
            $store_id = $this->request->post['store_id'];
        } else {
            $store_id;
        }
        if (isset($this->request->post['date'])) {
            $delivery_date = $this->request->post['date'];
            $this->session->data['dates'][$store_id] = $delivery_date;
        } else {
            $delivery_date = '';
        }

        if (isset($this->request->post['timeslot'])) {
            $delivery_timeslot = $this->request->post['timeslot'];
            $this->session->data['timeslot'][$store_id] = $delivery_timeslot;
            $this->session->data['timeslot'][76] = $delivery_timeslot;
            foreach ($this->session->data['timeslot'] as $sid => $stslot) {
                $this->session->data['timeslot'][$sid] = $this->session->data['timeslot'][75];
            }
            $log = new Log('error.log');
            $log->write('timeslot');
            $log->write($this->session->data['timeslot']);
            $log->write('timeslot');
        } else {
            $delivery_timeslot = '';
        }
        $this->load->controller('checkout/confirm');
        exit;
    }

    //NEW METHOD TO CHECK EACH DAYS TIMESLOTS
    public function checkDateTs($store_id, $method, $day) {
        $shipping_method = explode('.', $method);

        if ('normal' == $shipping_method[0]) {
            $settings = $this->getSettings('normal', 0);
            $timeDiff = $settings['normal_delivery_time_diff'];

            $this->db->where('day', $day);
            $this->db->select('timeslot', false);
            $this->db->where('status', '1');
            $timeslots = $this->db->get('normal_delivery_timeslot')->rows;
        } else {
            $settings = $this->getSettings('express', 0);
            $timeDiff = $settings['express_delivery_time_diff'];

            $this->db->where('day', $day);
            $this->db->select('timeslot', false);
            $this->db->where('status', '1');
            $timeslots = $this->db->get('express_delivery_timeslot')->rows;
        }

        $is_enabled = false;

        /* echo $day;
          echo "<pre>";print_r($timeslots); */

        foreach ($timeslots as $timeslot) {
            $temp = explode('-', $timeslot['timeslot']);
            $is_enabled = $this->timeIsBetween($temp[0], $temp[1], date('h:ia'), $timeDiff);
            //echo "<pre>";print_r("ena".$is_enabled."is_enabled");

            if ($is_enabled) {
                break;
            }
        }

        return $is_enabled;
    }

    //NEW METHOD TO CHECK EACH DAYS TIMESLOTS
    public function futurecheckDateTs($store_id, $method, $day) {
        $storeDetail = $this->getStoreDetail($store_id);
        $timeDiff = $storeDetail['delivery_time_diff'];

        $shipping_method = explode('.', $method);

        if ('normal' == $shipping_method[0]) {
            $settings = $this->getSettings('normal', 0);
            $timeDiff = $settings['normal_delivery_time_diff'];

            $this->db->where('day', $day);
            $this->db->select('timeslot', false);
            $this->db->where('status', '1');
            $timeslots = $this->db->get('normal_delivery_timeslot')->rows;
        } elseif ('express' == $shipping_method[0]) {
            $settings = $this->getSettings('express', 0);
            $timeDiff = $settings['express_delivery_time_diff'];

            $this->db->where('day', $day);
            $this->db->select('timeslot', false);
            $this->db->where('status', '1');
            $timeslots = $this->db->get('express_delivery_timeslot')->rows;
        } elseif ('pickup' == $shipping_method[0]) {
            $this->db->where('day', $day);
            $this->db->select('timeslot', false);
            $this->db->where('status', '1');
            $this->db->where('store_id', $store_id);
            $timeslots = $this->db->get('store_pickup_timeslot')->rows;
        } else {
            $this->db->where('day', $day);
            $this->db->select('timeslot', false);
            $this->db->where('status', '1');
            $this->db->where('store_id', $store_id);
            $timeslots = $this->db->get('store_delivery_timeslot')->rows;
        }

        $is_enabled = false;

        /* echo $day;
          echo "<pre>";print_r($timeslots); */

        /* foreach ($timeslots as $timeslot) {
          $temp = explode('-', $timeslot['timeslot']);
          $is_enabled = $this->timeIsBetween($temp[0], $temp[1], date('h:ia'),$timeDiff);
          //echo "<pre>";print_r("ena".$is_enabled."is_enabled");

          if($is_enabled) {
          break;
          }
          } */

        if (count($timeslots) > 0) {
            return true;
        }

        return false;
    }

    public function getRawTimeslots($shipping_method, $store_id) {
        $data = [];

        $this->language->load('checkout/delivery_time');

        $data['text_no_timeslot'] = $this->language->get('text_no_timeslot');

        //TO STORE STORE ID AND SHIPPING METHOD IN SESSION
        $this->session->data['store_id_for_timeslot'] = $store_id;
        $this->session->data['shipping_method_for_timeslot'] = $shipping_method;

        $getActiveDays = $this->getActiveDays($store_id, $shipping_method);

        $log = new Log('error.log');

        $data['dates'] = $this->getDates($getActiveDays, $store_id, $shipping_method);
        $data['timeslots'] = [];

        $log->write($data['dates']);
        foreach ($data['dates'] as $date) {
            $amTimeslot = [];
            $pmTimeslot = [];
            $inPmfirstTimeslot = [];

            $temp = $this->get_all_time_slot($store_id, $shipping_method, $date);

            foreach ($temp as $temp1) {
                $temp2 = explode('-', $temp1['timeslot']);
                //echo "vrve";print_r($temp2);die;
                if (false !== strpos($temp2[0], 'am')) {
                    array_push($amTimeslot, $temp1);
                } else {
                    if ('12' == substr($temp2[0], 0, 2)) {
                        array_push($inPmfirstTimeslot, $temp1);
                    } else {
                        array_push($pmTimeslot, $temp1);
                    }
                }
            }

            //array_push($amTimeslot,$pmTimeslot);

            foreach ($inPmfirstTimeslot as $te) {
                array_push($amTimeslot, $te);
            }

            foreach ($pmTimeslot as $te) {
                array_push($amTimeslot, $te);
            }

            /* echo "<pre>";
              print_r($amTimeslot);
              print_r($pmTimeslot);

              print_r($temp);
              print_r($amTimeslot);
              die; */

            //echo "<pre>";print_r($temp);print_r($amTimeslot);
            $data['timeslots'][$date] = $amTimeslot;
            //$data['timeslots'][$date] = $temp;
        }

        return $data;
    }

    public function indexNew() {
        $data = [];

        $rangeonestart = "10:00:00";
        $rangeoneend = "18:59:59";

        $rangetwostart = "19:00:00";
        $rangetwoend = "21:59:59";

        $rangethreestart = "22:00:00";
        $rangethreeend = "23:59:59";

        $rangefourstart = "00:00:00";
        $rangefourend = "08:59:59";

        $rangefivestart = "09:00:00";
        $rangefiveend = "09:59:59";

        $log = new Log('error.log');
        $log->write('RANGE');
        $log->write(date("H:i:s"));
        $log->write(time());
        $log->write('RANGE');

        $same_day = date('Y-m-d');
        $next_day = date('d-m-Y', strtotime($same_day . "+1 days"));

        if (time() >= strtotime($rangeonestart) && time() <= strtotime($rangeoneend)) {
            $pre_defined_slots = array('06:00am - 08:00am');
            $selected_slot = $pre_defined_slots[0];
            $data['selected_slot'] = $selected_slot;
            $data['selected_date_slot'] = $next_day;
            $data['disabled_slot'] = array();
            $log->write($selected_slot);
            $log->write('RANGE ONE');
        }

        if (time() >= strtotime($rangetwostart) && time() <= strtotime($rangetwoend)) {
            $pre_defined_slots = array('08:00am - 10:00am');
            $selected_slot = $pre_defined_slots[0];
            $data['selected_slot'] = $selected_slot;
            $data['selected_date_slot'] = $next_day;
            $data['disabled_slot'] = array('06:00am - 08:00am');
            $log->write('RANGE TWO');
        }

        if (time() >= strtotime($rangethreestart) && time() <= strtotime($rangethreeend)) {
            $pre_defined_slots = array('10:00am - 12:00pm');
            $selected_slot = $pre_defined_slots[0];
            $data['selected_slot'] = $selected_slot;
            $data['selected_date_slot'] = $next_day;
            $data['disabled_slot'] = array('06:00am - 08:00am', '08:00am - 10:00am');
            $log->write('RANGE THREE');
        }

        if (time() >= strtotime($rangefourstart) && time() <= strtotime($rangefourend)) {
            $pre_defined_slots = array('02:00pm - 04:00pm');
            $selected_slot = $pre_defined_slots[0];
            $data['selected_slot'] = $selected_slot;
            $data['selected_date_slot'] = date('d-m-Y');
            $data['disabled_slot'] = array('06:00am - 08:00am', '08:00am - 10:00am', '10:00am - 12:00pm');
            $log->write('RANGE FOUR');
        }

        if (time() >= strtotime($rangefivestart) && time() <= strtotime($rangefiveend)) {
            $pre_defined_slots = array('04:00pm - 06:00pm');
            $selected_slot = $pre_defined_slots[0];
            $data['selected_slot'] = $selected_slot;
            $data['selected_date_slot'] = date('d-m-Y');
            $data['disabled_slot'] = array('06:00am - 08:00am', '08:00am - 10:00am', '10:00am - 12:00pm', '02:00pm - 04:00pm');
            $log->write('RANGE FIVE');
        }

        $this->language->load('checkout/delivery_time');

        $data['text_no_timeslot'] = $this->language->get('text_no_timeslot');

        $store_id = $this->request->get['store_id'];
        $shipping_method = $this->request->get['shipping_method'];

        //TO STORE STORE ID AND SHIPPING METHOD IN SESSION
        $this->session->data['store_id_for_timeslot'] = $store_id;
        $this->session->data['shipping_method_for_timeslot'] = $shipping_method;

        $getActiveDays = $this->getActiveDays($store_id, $shipping_method);
        $log = new Log('error.log');
        $log->write('timeslots');
        $log->write($store_id . 'ss' . $shipping_method);

        $log->write($getActiveDays);
        $data['dates'] = $this->getDates($getActiveDays, $store_id, $shipping_method);
        $data['timeslots'] = [];

        $data['formatted_dates'] = [];
        $log->write($data['dates']);
        foreach ($data['dates'] as $date) {
            $amTimeslot = [];
            $pmTimeslot = [];
            $inPmfirstTimeslot = [];

            $temp = $this->get_all_time_slot($store_id, $shipping_method, $date);

            foreach ($temp as $temp1) {
                $temp2 = explode('-', $temp1['timeslot']);

                if (false !== strpos($temp2[0], 'am')) {
                    array_push($amTimeslot, $temp1);
                } else {
                    if ('12' == substr($temp2[0], 0, 2)) {
                        array_push($inPmfirstTimeslot, $temp1);
                    } else {
                        array_push($pmTimeslot, $temp1);
                    }
                }
            }

            foreach ($inPmfirstTimeslot as $te) {
                array_push($amTimeslot, $te);
            }

            foreach ($pmTimeslot as $te) {
                array_push($amTimeslot, $te);
            }

            //echo "<pre>";print_r($temp);print_r($amTimeslot);

            if (count($amTimeslot) > 0) {
                $data['timeslots'][$date] = $amTimeslot;
                $data['formatted_dates'][] = $date;
            }

            //$data['timeslots'][$date] = $temp;
        }

        $data['dates'] = $data['formatted_dates'];

        $log->write('timeslots final');
        $log->write($data['dates']);
        $log->write($data['timeslots']);
        $data['store'] = $this->getStoreDetail($store_id);

        /* REMOVE DAYS BASED ON CITY OR REGION */
        $order_delivery_days = NULL;
        $city_details = NULL;
        $selected_address_id = $this->session->data['shipping_address_id'];
        $this->load->model('account/address');
        $customer_selected_address = $this->model_account_address->getAddress($selected_address_id);
        $log->write($customer_selected_address);
        if (isset($customer_selected_address) && is_array($customer_selected_address) && $customer_selected_address['city_id'] > 0) {
            $city_details = $this->model_account_address->getCityDetails($customer_selected_address['city_id']);
            $order_delivery_days = $this->model_account_address->getRegion($city_details['region_id']);
        }

        if ($order_delivery_days != NULL && is_array($order_delivery_days)) {
            $log->write($city_details);
            $log->write($order_delivery_days);
            foreach ($data['timeslots'] as $key => $value) {
                $order_delivery_days_timestamp = strtotime($key);
                $day_name = date('l', $order_delivery_days_timestamp);
                $day_name = strtolower($day_name);
                if ($order_delivery_days[$day_name] == 0) {
                    $log->write($key . ' ' . $day_name);
                    unset($data['timeslots'][$key]);
                }
            }
            foreach ($data['dates'] as $order_day_dates) {
                $order_day_dates_timestamp = strtotime($order_day_dates);
                $order_day_name = date('l', $order_day_dates_timestamp);
                $order_day_name = strtolower($order_day_name);
                if ($order_delivery_days[$order_day_name] == 0) {
                    $log->write($order_day_name);
                    if (($get_key = array_search($order_day_dates, $data['dates'])) !== false) {
                        unset($data['dates'][$get_key]);
                    }
                }
            }
            if (in_array($data['selected_date_slot'], $data['dates'])) {
                $log->write('FOUNDED');
            } else {
                $log->write('NOT FOUNDED');
                $data['selected_date_slot'] = reset($data['dates']);
            }
        }
        $log->write('dates');
        $log->write($data['dates']);
        $log->write('dates');
        /* REMOVE DAYS BASED ON CITY OR REGION */


        $json['dates'] = $data['dates'];
        $json['timeslots'] = $data['timeslots'];
        $json['selected_slot'] = $data['selected_slot'];
        $json['selected_date_slot'] = $data['selected_date_slot'];
        $json['disabled_slot'] = $data['disabled_slot'];

        $stores = $this->cart->getStores();
        foreach ($stores as $store_id) {
            $this->session->data['timeslot'][$store_id] = $data['selected_slot'];
            $this->session->data['dates'][$store_id] = $data['selected_date_slot'];
            //$this->session->data['dates'][$store_id] = current($data['dates']);
            //$this->session->data['dates'][$store_id] = $data['dates'][0];
            $this->load->model('user/user');
            $store_details = $this->model_user_user->getVendor($store_id);
            $vendor_details = $this->model_user_user->getUser($store_details['vendor_id']);
            if ($store_id > 75 && $vendor_details['delivery_time'] != NULL && $vendor_details['delivery_time'] > 0) {
                $this->getothervendordeliverytime($store_id);
            }
        }
        /* $log = new Log('error.log');
          $log->write('SLOTS');
          $log->write($data['selected_slot']);
          $log->write($data['dates'][0]);
          $log->write('SLOTS'); */
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getothervendordeliverytime($store_id) {

        $data = [];
        $log = new Log('error.log');
        $log->write('getothervendordeliverytime');
        $new_time = NULL;
        $this->load->model('user/user');
        $store_details = $this->model_user_user->getVendor($store_id);
        $vendor_details = $this->model_user_user->getUser($store_details['vendor_id']);
        if ($vendor_details['delivery_time'] != NULL && $vendor_details['delivery_time'] > 0) {
            $new_time = date("d-m-Y H:i:s", strtotime('+' . $vendor_details['delivery_time'] . ' hours'));
            $new_time_array = explode(' ', $new_time);
            $new_time_date = $new_time_array[0];
            $new_time_datetime = $new_time_array[1];

            $this->language->load('checkout/delivery_time');

            $data['text_no_timeslot'] = $this->language->get('text_no_timeslot');

            $parent_store_id = 75;
            $shipping_method = 'Standard Delivery';

            $getActiveDays = $this->getActiveDays($parent_store_id, $shipping_method);

            $data['dates'] = $this->getDates($getActiveDays, $parent_store_id, $shipping_method);
            $data['timeslots'] = [];

            $data['formatted_dates'] = [];
            foreach ($data['dates'] as $date) {
                $amTimeslot = [];
                $pmTimeslot = [];
                $inPmfirstTimeslot = [];

                $temp = $this->get_all_time_slot($parent_store_id, $shipping_method, $date);

                foreach ($temp as $temp1) {
                    $temp2 = explode('-', $temp1['timeslot']);

                    if (false !== strpos($temp2[0], 'am')) {
                        array_push($amTimeslot, $temp1);
                    } else {
                        if ('12' == substr($temp2[0], 0, 2)) {
                            array_push($inPmfirstTimeslot, $temp1);
                        } else {
                            array_push($pmTimeslot, $temp1);
                        }
                    }
                }

                foreach ($inPmfirstTimeslot as $te) {
                    array_push($amTimeslot, $te);
                }

                foreach ($pmTimeslot as $te) {
                    array_push($amTimeslot, $te);
                }

                //echo "<pre>";print_r($temp);print_r($amTimeslot);

                if (count($amTimeslot) > 0) {
                    $data['timeslots'][$date] = $amTimeslot;
                    $data['formatted_dates'][] = $date;
                }

                //$data['timeslots'][$date] = $temp;
            }

            $data['dates'] = $data['formatted_dates'];

            $data['store'] = $this->getStoreDetail($parent_store_id);
            $data['new_time'] = $new_time;
            $data['new_time_date'] = $new_time_date;
            $data['new_time_datetime'] = $new_time_datetime;
            $data['new_time_slots'] = $data['timeslots'][$new_time_date];
            $selected_time_slot = NULL;
            foreach ($data['timeslots'] as $d_slot => $t_slot) {
                $log->write('timeslots2');
                $log->write($d_slot);
                $log->write($t_slot);
                $log->write('timeslots2');
                foreach ($t_slot as $t_slo) {
                    $log->write('timeslots3');
                    $log->write($t_slo['timeslot']);
                    $t_slo_array = explode(' - ', $t_slo['timeslot']);
                    $new_time2 = strtotime($new_time);
                    $new_slot_start2 = strtotime($d_slot . ' ' . $t_slo_array[0]);
                    $new_slot_end2 = strtotime($d_slot . ' ' . $t_slo_array[1]);

                    $log->write($new_time2);
                    $log->write($new_time);
                    $log->write(date('d-m-Y H:i', $new_time2));

                    $log->write($new_slot_start2);
                    $log->write($d_slot . ' ' . $t_slo_array[0]);
                    $log->write(date('d-m-Y H:i', $new_slot_start2));

                    $log->write($new_slot_end2);
                    $log->write($d_slot . ' ' . $t_slo_array[1]);
                    $log->write(date('d-m-Y H:i', $new_slot_end2));

                    if ($new_time2 > $new_slot_start2 && $new_time2 < $new_slot_end2 && $selected_time_slot == NULL) {
                        $log->write('selected2');
                        $selected_time_slot = $d_slot . ' ' . $t_slo['timeslot'];
                        $data['selected_time_slot'] = $selected_time_slot;
                        $log->write($d_slot);
                        $log->write($t_slo['timeslot']);
                        $log->write('selected2');
                    } elseif ($new_time2 < $new_slot_start2 && $new_time2 < $new_slot_end2 && $selected_time_slot == NULL) {
                        $log->write('selected21');
                        $selected_time_slot = $d_slot . ' ' . $t_slo['timeslot'];
                        $data['selected_time_slot'] = $selected_time_slot;
                        $log->write($d_slot);
                        $log->write($t_slo['timeslot']);
                        $log->write('selected21');
                    }
                    $log->write('timeslots3');
                }
            }
        }
        $log->write($data);
        $log->write('getothervendordeliverytime');

        if ($data['selected_time_slot'] != NULL) {
            $selected_time_slot = explode(' ', $data['selected_time_slot']);
            $this->session->data['timeslot'][$store_id] = $selected_time_slot[1] . ' - ' . $selected_time_slot[3];
            $this->session->data['dates'][$store_id] = $selected_time_slot[0];
        }

        return $data;
    }

}
