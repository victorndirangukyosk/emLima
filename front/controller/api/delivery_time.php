<?php

class ControllerApiDeliverytime extends Controller {

    public function index() {
        $data = [];

        $store_id = $this->request->post['store_id'];
        $shipping_method = $this->request->post['shipping_method'];

        $getActiveDays = $this->getActiveDays($store_id, $shipping_method);

        $data['dates'] = $this->getDates($getActiveDays, $store_id, $shipping_method);
        $data['store'] = $this->getStoreDetail($store_id);

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/api/delivery_time.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/api/delivery_time.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/api/delivery_time.tpl', $data));
        }
    }

    public function getStoreDetail($store_id) {
        $this->load->model('account/activity');

        return $this->model_account_activity->getStoreData($store_id);
    }

    protected function getDates($getActiveDays, $store_id, $shipping_method) {
        $avalday = [];
        foreach ($getActiveDays as $ad) {
            $avalday[] = $ad['day'];
        }

        $date = $this->checkCurrentDateTs($store_id, $shipping_method);
        if ($date) {
            $tmpDate = date('Y-m-d');
        } else {
            $tmpDate = date('Y-m-d', strtotime('+1 Days'));
        }

        $i = 0;
        $nextBusinessDay = [];
        for ($i = 0; $i <= 6; ++$i) {
            if (in_array($i, $avalday)) {
                $nextBusinessDay[] = date('d-m-Y', strtotime($tmpDate . ' +' . $i . ' Days'));
            }
        }
        $total = count($nextBusinessDay);
        if ($total <= 7) {
            $length = 7 - $total;
            for ($i = 7; $i <= 6 + $length; ++$i) {
                if (in_array($i, $avalday)) {
                    $nextBusinessDay[] = date('d-m-Y', strtotime($tmpDate . ' +' . $i . ' Days'));
                }
            }
        }

        return $nextBusinessDay;
    }

    public function getActiveDays($store_id, $method) {
        $shipping_method = explode('.', $method);

        if ('pickup' == $shipping_method[0]) {
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

    public function getStoreTimeSlot($store_id, $method, $day) {
        $shipping_method = explode('.', $method);

        if ('pickup' == $shipping_method[0]) {
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

            return $rows;
        }
    }

    public function get_time_slot() {
        $store_id = $this->request->post['store_id'];
        $shipping_method = $this->session->data['shipping_method'][$store_id]['shipping_method']['code']; // $this->request->post['shipping_method'];
        //echo $shipping_method;die;

        $date = $this->request->post['date'];
        $day = date('w', strtotime($date));

        $data['timeslot'] = $this->getStoreTimeSlot($store_id, $shipping_method, $day);

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/api/delivery_slot.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/api/delivery_slot.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/api/delivery_slot.tpl', $data));
        }
    }

    public function checkCurrentDateTs($store_id, $method) {
        $storeDetail = $this->getStoreDetail($store_id);
        $timeDiff = $storeDetail['delivery_time_diff'];
        $day = date('w');
        $shipping_method = explode('.', $method);
        if ('pickup' == $shipping_method[0]) {
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
            $is_enabled = $this->timeIsBetween($temp[0], $temp[1], date('h:ma'), $timeDiff);
        }

        return $is_enabled;
    }

    private function timeIsBetween($from, $to, $time, $time_diff = false) {
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

        if ($min < $to_min) {
            return true;
        } else {
            return false;
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
        } else {
            $delivery_timeslot = '';
        }
        exit;
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
            $data['selected_time_slot_time'] = $selected_time_slot[1] . ' - ' . $selected_time_slot[3];
            $data['selected_time_slot_date'] = $selected_time_slot[0];
        }

        return $data;
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

}
