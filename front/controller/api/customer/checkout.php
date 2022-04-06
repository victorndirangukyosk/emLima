<?php

class ControllerApiCustomerCheckout extends Controller {

    public function getDeliveryTimeslot() {
        //echo "<pre>";print_r('getDeliveryTimeslot');die;
        $json = [];

        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');

        $this->load->model('assets/category');

        $this->load->model('assets/product');

        $this->load->model('tool/image');

        if (isset($this->request->get['store_id']) && isset($this->request->get['shipping_method'])) {
            $data = [];

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
            /* $log->write($store_id."ss".$shipping_method);

              $log->write($getActiveDays); */
            $data['dates'] = $this->getDates($getActiveDays, $store_id, $shipping_method);
            $data['timeslots'] = [];

            $data['formatted_dates'] = [];

            //$log->write($data['dates']);
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
                //$data['timeslots'][$date] = $amTimeslot;
                if (count($amTimeslot) > 0) {
                    $data['timeslots'][$date] = $amTimeslot;
                    $data['formatted_dates'][] = $date;
                }
                //$data['timeslots'][$date] = $temp;
            }

            $data['dates'] = $data['formatted_dates'];

            /* $log->write('timeslots final');
              $log->write($data['timeslots']); */
            $data['store'] = $this->getStoreDetail($store_id);

            if ('express.express' == $this->request->get['shipping_method']) {
                $data['settings'] = $this->getSettings('express', 0);

                $settings = $this->getSettings('express', 0);
                $timeDiff = $settings['express_how_much_time'];

                $min = 0;
                if ($timeDiff) {
                    $i = explode(':', $timeDiff);
                    $min = $min + $i[0] * 60 + $i[1]; //add difference minut to current time
                }
                $to = date('h:ia', strtotime('+' . $min . ' minutes', strtotime(date('h:ia'))));

                $delivery_timeslot = date('h:ia') . ' - ' . $to;

                //$data['delivery_timeslot'] = $delivery_timeslot;
                $data['delivery_timeslot'] = 'Within ' . $min . ' minutes';
            } else {
                $data['delivery_timeslot'] = '';
            }
            //echo "<pre>";print_r($data);die;

            $json['data'] = $data;
        } else {
            $json['status'] = 10013;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_not_loggedin')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getApiDeliveryTimeslot($data) {
        //echo "<pre>";print_r('getDeliveryTimeslot');die;
        $json = [];

        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');

        $this->load->model('assets/category');

        $this->load->model('assets/product');

        $this->load->model('tool/image');

        if (isset($data['store_id']) && isset($data['shipping_method'])) {
            $this->language->load('checkout/delivery_time');

            $data['text_no_timeslot'] = $this->language->get('text_no_timeslot');

            $store_id = $data['store_id'];
            $shipping_method = $data['shipping_method'];

            //TO STORE STORE ID AND SHIPPING METHOD IN SESSION
            $this->session->data['store_id_for_timeslot'] = $store_id;
            $this->session->data['shipping_method_for_timeslot'] = $shipping_method;

            $getActiveDays = $this->getActiveDays($store_id, $shipping_method);

            $log = new Log('error.log');

            $data['dates'] = $this->getDates($getActiveDays, $store_id, $shipping_method);
            $data['timeslots'] = [];

            $data['formatted_dates'] = [];

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
                //$data['timeslots'][$date] = $amTimeslot;
                if (count($amTimeslot) > 0) {
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

            if ('express.express' == $data['shipping_method']) {
                $data['settings'] = $this->getSettings('express', 0);

                $settings = $this->getSettings('express', 0);
                $timeDiff = $settings['express_how_much_time'];

                $min = 0;
                if ($timeDiff) {
                    $i = explode(':', $timeDiff);
                    $min = $min + $i[0] * 60 + $i[1]; //add difference minut to current time
                }
                $to = date('h:ia', strtotime('+' . $min . ' minutes', strtotime(date('h:ia'))));

                $delivery_timeslot = date('h:ia') . ' - ' . $to;

                //$data['delivery_timeslot'] = $delivery_timeslot;
                $data['delivery_timeslot'] = 'Within ' . $min . ' minutes';
            } else {
                $data['delivery_timeslot'] = '';
            }
            //echo "<pre>";print_r($data);die;

            $json['data'] = $data;
        } else {
            $json['status'] = 10013;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_not_loggedin')];

            http_response_code(400);
        }

        return $json;
        /* $this->response->addHeader('Content-Type: application/json');
          $this->response->setOutput(json_encode($json)); */
    }

    public function getPaymentMethods() {
        //echo "<pre>";print_r('getStoreShippingMethods');die;
        $json = [];

        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');

        $this->load->model('assets/category');

        $this->load->model('assets/product');

        $this->load->model('tool/image');

        if (isset($this->request->get['total'])) {
            $this->load->language('checkout/checkout');
            // Payment Methods
            $method_data = [];

            $this->load->model('extension/extension');

            $results = $this->model_extension_extension->getExtensions('payment');

            // echo "<pre>";print_r($results);die;
            $total = $this->request->get['total'];
            if ($this->customer->getPaymentTerms() == 'Payment On Delivery') {
                foreach ($results as $result) {
                    if ($result['code'] == 'wallet' || $result['code'] == 'mod' || $result['code'] == 'mpesa' || $result['code'] == 'pesapal' || $result['code'] == 'interswitch') {
                        if ($this->config->get($result['code'] . '_status')) {
                            $this->load->model('payment/' . $result['code']);

                            $method = $this->{'model_payment_' . $result['code']}->getMethod($total);

                            if ($method) {
                                $method['terms'] = str_replace("(No Transaction Fee)", "", $method['terms']);
                                //removed  (No Transaction Fee) from terms,as suggested
                                //echo "<pre>";print_r($method);die;
                                $method_data[] = $method;
                            }
                        }
                    }
                }
            } else if ($this->customer->getPaymentTerms() == '7 Days Credit' || $this->customer->getPaymentTerms() == '15 Days Credit' || $this->customer->getPaymentTerms() == '30 Days Credit') {
                foreach ($results as $result) {
                    if ($result['code'] == 'cod' || $result['code'] == 'wallet') {
                        if ($this->config->get($result['code'] . '_status')) {
                            $this->load->model('payment/' . $result['code']);

                            $method = $this->{'model_payment_' . $result['code']}->getMethod($total);

                            if ($method) {
                                $method['terms'] = str_replace("(No Transaction Fee)", "", $method['terms']);
                                //removed  (No Transaction Fee) from terms,as suggested
                                //echo "<pre>";print_r($method);die;
                                $method_data[] = $method;
                            }
                        }
                    }
                }
            } else {
                foreach ($results as $result) {
                    if ($this->config->get($result['code'] . '_status')) {
                        $this->load->model('payment/' . $result['code']);

                        $method = $this->{'model_payment_' . $result['code']}->getMethod($total);

                        if ($method) {
                            $method['terms'] = str_replace("(No Transaction Fee)", "", $method['terms']);
                            //removed  (No Transaction Fee) from terms,as suggested
                            //echo "<pre>";print_r($method);die;
                            $method_data[] = $method;
                        }
                    }
                }
            }

            $sort_order = [];

            foreach ($method_data as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }

            array_multisort($sort_order, SORT_ASC, $method_data);

            //echo "<pre>";print_r($method_data);die;
            $json['data'] = $method_data;
        } else {
            $json['status'] = 10013;

            $json['message'][] = ['type' => '', 'body' => 'Order total is required.'];
            
            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getMixedPaymentMethods() {
        //echo "<pre>";print_r('getStoreShippingMethods');die;
        $json = [];

        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');

        $this->load->model('assets/category');

        $this->load->model('assets/product');

        $this->load->model('tool/image');

        if (isset($this->request->get['total'])) {

            unset($this->session->data['pezesha_amount_limit']);
            unset($this->session->data['pezesha_customer_amount_limit']);
            //get the pezesha amount limit 
            $this->load->controller('customer/getPezeshaLoanOffers');

            // echo "<pre>";print_r($this->session->data['pezesha_amount_limit']);die;

            
            $this->load->language('checkout/checkout');
            // Payment Methods
            $method_data = [];

            $this->load->model('extension/extension');

            $results = $this->model_extension_extension->getExtensions('payment');

            // echo "<pre>";print_r($this->customer);die;
            $total = $this->request->get['total'];
            // if ($this->customer->getPaymentTerms() == 'Payment On Delivery') {
        if (($this->customer->getPaymentTerms() == 'Payment On Delivery' && $this->customer->getCustomerPezeshaId() == NULL && $this->customer->getCustomerPezeshauuId() == NULL) || ($this->customer->getPaymentTerms() == 'Payment On Delivery' && $this->customer->getCustomerPezeshaId() != NULL && $this->customer->getCustomerPezeshauuId() != NULL && $this->session->data['pezesha_customer_amount_limit'] == 0)) {

                foreach ($results as $result) {
                    if ($result['code'] == 'wallet' || $result['code'] == 'mod' || $result['code'] == 'mpesa' || $result['code'] == 'pesapal' || $result['code'] == 'interswitch') {
                        if ($this->config->get($result['code'] . '_status')) {
                            $this->load->model('payment/' . $result['code']);

                            $method = $this->{'model_payment_' . $result['code']}->getMethod($total);

                            if ($method) {
                                $method['terms'] = str_replace("(No Transaction Fee)", "", $method['terms']);
                                //removed  (No Transaction Fee) from terms,as suggested
                                //echo "<pre>";print_r($method);die;
                                $method_data[] = $method;
                            }
                        }
                    }
                }
            // } else if ($this->customer->getPaymentTerms() == '7 Days Credit' || $this->customer->getPaymentTerms() == '15 Days Credit' || $this->customer->getPaymentTerms() == '30 Days Credit') {
        } if ((($this->customer->getPaymentTerms() == '7 Days Credit' || $this->customer->getPaymentTerms() == '15 Days Credit' || $this->customer->getPaymentTerms() == '30 Days Credit') && ($this->customer->getCustomerPezeshaId() == NULL && $this->customer->getCustomerPezeshauuId() == NULL)) || (($this->customer->getPaymentTerms() == '7 Days Credit' || $this->customer->getPaymentTerms() == '15 Days Credit' || $this->customer->getPaymentTerms() == '30 Days Credit') && ($this->customer->getCustomerPezeshaId() != NULL && $this->customer->getCustomerPezeshauuId() != NULL && $this->session->data['pezesha_customer_amount_limit'] == 0))) {
               
                foreach ($results as $result) {
                    if ($result['code'] == 'cod' || $result['code'] == 'wallet') {
                        if ($this->config->get($result['code'] . '_status')) {
                            $this->load->model('payment/' . $result['code']);

                            $method = $this->{'model_payment_' . $result['code']}->getMethod($total);

                            if ($method) {
                                $method['terms'] = str_replace("(No Transaction Fee)", "", $method['terms']);
                                //removed  (No Transaction Fee) from terms,as suggested
                                //echo "<pre>";print_r($method);die;
                                $method_data[] = $method;
                            }
                        }
                    }
                }
            // } else {
        } if ($this->customer->getCustomerPezeshaId() != NULL && $this->customer->getCustomerPezeshauuId() != NULL && $this->config->get('pezesha_status') && $this->session->data['pezesha_customer_amount_limit'] > 0) {

                foreach ($results as $result) {
                    if ($result['code'] == 'pezesha' || $result['code'] == 'wallet') {

                    if ($this->config->get($result['code'] . '_status')) {
                        $this->load->model('payment/' . $result['code']);

                        $method = $this->{'model_payment_' . $result['code']}->getMethod($total);

                        if ($method) {
                            $method['terms'] = str_replace("(No Transaction Fee)", "", $method['terms']);
                            //removed  (No Transaction Fee) from terms,as suggested
                            //echo "<pre>";print_r($method);die;
                            $method_data[] = $method;
                        }
                    }
                }
                }
            }

            $sort_order = [];

            foreach ($method_data as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }

            array_multisort($sort_order, SORT_ASC, $method_data);

            //echo "<pre>";print_r($method_data);die;
            $json['data'] = $method_data;
        } else {
            $json['status'] = 10013;

            $json['message'][] = ['type' => '', 'body' => 'Order total is required.'];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    
    public function addApplyCoupon() {
        //echo "<pre>";print_r('addApplyCoupon');die;
        $json = [];

        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');

        $this->load->model('assets/category');

        $this->load->model('assets/product');

        $this->load->model('tool/image');

        if (isset($this->request->post['coupon']) && isset($this->request->post['store_id']) && isset($this->request->post['total']) && isset($this->request->post['sub_total'])) {
            $this->load->language('checkout/coupon');

            $this->load->model('checkout/coupon');

            if (isset($this->request->post['coupon'])) {
                $coupon = $this->request->post['coupon'];
            } else {
                $coupon = '';
            }

            $coupon_info = $this->model_checkout_coupon->apiGetCoupon($coupon, $this->request->post['sub_total']);

            $detail = $this->getCouponDiscountAmount($value = '', $coupon, $this->request->post['store_id'], $this->request->post['sub_total'], $this->request->post['sub_total']);

            //echo "<pre>";print_r($detail);die;

            if (empty($this->request->post['coupon'])) {
                $json['message'][] = ['type' => '', 'body' => $this->language->get('error_empty')];
            } elseif ($coupon_info && count($detail) > 0) {
                $json['data'] = $coupon_info;

                $json['data']['discount_amount'] = isset($detail[0]['value']) ? -$detail[0]['value'] : 0;
                $json['data']['coupon_type'] = isset($detail[0]['coupon_type']) ? $detail[0]['coupon_type'] : '';

                $json['message'][] = ['type' => '', 'body' => $this->language->get('text_success')];
            } else {
                $json['status'] = 10021;

                $json['message'][] = ['type' => '', 'body' => $this->language->get('error_coupon')];
            }
        } else {
            $json['status'] = 10013;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_not_loggedin')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addApplyReward() {
        //echo "<pre>";print_r('addApplyCoupon');die;
        $json = [];

        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');
        $this->load->language('checkout/reward');

        if (isset($this->request->post['reward']) && $this->request->post['reward'] > 0) {
            $this->load->model('checkout/coupon');

            $points = $this->customer->getRewardPoints();

            //echo "<pre>";print_r($points);die;
            if ($this->request->post['reward'] > $points) {
                $json['status'] = 10024;

                $json['message'][] = ['type' => '', 'body' => sprintf($this->language->get('error_points'), $this->request->post['reward'])];
            } else {
                $json['data']['discount_amount'] = $this->request->post['reward'] * $this->config->get('config_reward_value');

                $json['message'][] = ['type' => '', 'body' => sprintf($this->language->get('text_success'), $this->request->post['reward'], $this->customer->getRewardPoints())];
            }
        } else {
            $json['status'] = 10025;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('error_reward')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
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

        $storeDetail = $this->getStoreDetail($store_id);
        $number_of_days_front = $storeDetail['number_of_days_front'];

        foreach ($getActiveDays as $ad) {
            $avalday[] = $ad['day'];
        }

        //CHECKS IF CURRENT DAY IS IN THE LIST OF AVAILABLE DAYS OF WEEK
        if (in_array(date('w'), $avalday)) {
            $date = $this->checkCurrentDateTs($store_id, $shipping_method);

            if ($date) {
                $tmpDate = date('Y-m-d');
            } else {
                //echo "<pres>";print_r($date."hvhj");die;
                $tmpDate = date('Y-m-d', strtotime('+1 Days'));
            }
        } else {
            $tmpDate = date('Y-m-d');
        }

        //CREATES THE LIST OF DAYS TO DISPLAY USER AS AVAILABLE DAYS OF DELIVERY.
        $nextBusinessDay = [];
        $j = 0;

        $shipping_method = explode('.', $shipping_method);

        $forwardDays = 7;

        if ('normal' == $shipping_method[0] || 'express' == $shipping_method[0]) {
            if ($this->config->get('normal_number_of_days')) {
                $forwardDays = $this->config->get('normal_number_of_days');
            }
        } elseif ('store_delivery' == $shipping_method[0] || 'pickup' == $shipping_method[0]) {
            if ($number_of_days_front) {
                $forwardDays = $number_of_days_front;
            }
        }

        if (count($nextBusinessDay) < $forwardDays) {
            $end;
            if (!empty($nextBusinessDay)) {
                $end = end($nextBusinessDay);

                //$log->write($end);

                $log->write('end');

                for ($i = 1; $i <= 49; ++$i) {
                    $tmp_date = date('d-m-Y', strtotime($end . ' +' . $i . ' Days'));

                    $day = date('w', strtotime($tmp_date));

                    //$daycheck = $this->futurecheckDateTs($this->session->data['store_id_for_timeslot'],$this->session->data['shipping_method_for_timeslot'],$day);

                    $daycheck = $this->get_all_time_slot($this->session->data['store_id_for_timeslot'], $this->session->data['shipping_method_for_timeslot'], $tmp_date);

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

                    //$daycheck = $this->futurecheckDateTs($this->session->data['store_id_for_timeslot'],$this->session->data['shipping_method_for_timeslot'],$day);

                    $daycheck = $this->get_all_time_slot($this->session->data['store_id_for_timeslot'], $this->session->data['shipping_method_for_timeslot'], $tmp_date);

                    if (!empty($daycheck)) {
                        $nextBusinessDay[] = $tmp_date;
                    }
                    if (count($nextBusinessDay) == $forwardDays) {
                        break;
                    }
                }
            }
        }

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

            $this->db->where('day', $day);
            $this->db->select('timeslot', false);
            $this->db->where('status', '1');
            $rows = $this->db->get('normal_delivery_timeslot')->rows;
        } elseif ('express' == $shipping_method[0]) {
            $settings = $this->getSettings('express', 0);
            $timeDiff = $settings['express_delivery_time_diff'];

            $this->db->where('day', $day);
            $this->db->select('timeslot', false);
            $this->db->where('status', '1');
            $rows = $this->db->get('express_delivery_timeslot')->rows;
        } elseif ('pickup' == $shipping_method[0]) {
            $this->db->where('day', $day);
            $this->db->select('timeslot', false);
            $this->db->where('status', '1');
            $this->db->where('store_id', $store_id);
            $rows = $this->db->get('store_pickup_timeslot')->rows;
        } else {
            $this->db->where('day', $day);
            $this->db->select('timeslot', false);
            $this->db->where('status', '1');
            $this->db->where('store_id', $store_id);
            $rows = $this->db->get('store_delivery_timeslot')->rows;
        }

        $is_enabled = false;
        $time_slot_rows = [];

        $log->write('newGetStoreTimeSlot rows');

        foreach ($rows as $tslot) {
            $row['timeslot'] = $tslot['timeslot'];
            $temp = explode('-', $tslot['timeslot']);
            $date = $date;

            if ($date != date('d-m-Y')) {
                array_push($time_slot_rows, $row);
            } else {
                $log->write(date('d-m-Y'));
                $log->write($timeDiff);
                $log->write($temp);

                $is_enabled = $this->timeIsBetween($temp[0], $temp[1], date('h:ia'), $timeDiff);
                if ($is_enabled) {
                    array_push($time_slot_rows, $row);
                }
            }
        }
        $log->write('time_slot_rows row');

        return $time_slot_rows;
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

    public function getCouponDiscountAmount($value = '', $coupon, $store_id, $sub_total, $total) {
        $total_data = [];

        if ($store_id) {
            if (isset($coupon)) {
                $this->load->language('total/coupon');

                $this->load->model('checkout/coupon');

                $coupon_info = $this->model_checkout_coupon->apiGetCoupon($coupon, $total);

                if ($coupon_info && $sub_total) {
                    $discount_total = 0;

                    if (!$coupon_info['product']) {
                        //$sub_total = $this->cart->getSubTotal($store_id);
                    } else {
                        //$sub_total = 0;

                        /* foreach ($this->cart->getProducts() as $product) {

                          if ($product['store_id'] == $store_id) {
                          if (in_array($product['product_id'], $coupon_info['product'])) {
                          $sub_total += $product['total'];
                          }
                          }else{
                          if (in_array($product['product_id'], $coupon_info['product'])) {
                          $sub_total += $product['total'];
                          }
                          }


                          } */
                    }
                    //$main_total  = $this->cart->getSubTotal();

                    $main_total = $sub_total;

                    $weightage = ($sub_total * 100) / $main_total;
                    if ('F' == $coupon_info['type']) {
                        $store_discount = ($coupon_info['discount'] * $weightage) / 100;

                        $discount_total = min($store_discount, $sub_total);
                    } elseif ('P' == $coupon_info['type']) {
                        $discount_total = $sub_total / 100 * $coupon_info['discount'];
                    }

                    //echo "<pre>";print_r($discount_total);die;

                    /* if ($coupon_info['shipping'] && isset($this->session->data['shipping_method'][$store_id])) {

                      $cost = $this->session->data['shipping_method'][$store_id]['shipping_method']['cost'];

                      $discount_total += $cost;
                      } */

                    if ($coupon_info['shipping'] && isset($this->session->data['shipping_method'][$store_id])) {
                        if (!empty($this->session->data['shipping_method'][$store_id]['shipping_method']['tax_class_id'])) {
                            $tax_rates = $this->tax->getRates($this->session->data['shipping_method'][$store_id]['shipping_method']['cost'], $this->session->data['shipping_method'][$store_id]['shipping_method']['tax_class_id']);

                            foreach ($tax_rates as $tax_rate) {
                                if ('P' == $tax_rate['type']) {
                                    $taxes[$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
                                }
                            }
                        }

                        $discount_total += $this->session->data['shipping_method'][$store_id]['shipping_method']['cost'];
                    }

                    if ($discount_total > $total) {
                        $discount_total = $total;
                    }

                    /* if($coupon_info['coupon_type'] == 'c') {
                      $discount_total = -0;
                      } */

                    $total_data[] = [
                        'code' => 'coupon',
                        'coupon_type' => $coupon_info['coupon_type'],
                        'title' => sprintf($this->language->get('text_coupon'), $coupon),
                        'value' => -$discount_total,
                        'sort_order' => $this->config->get('coupon_sort_order'),
                    ];

                    $total -= $discount_total;
                }
            }
        }

        return $total_data;
    }

    public function getSetDeliveryTimeSlot() {

        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $data['kondutoStatus'] = $this->config->get('config_konduto_status');
        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        if (!$this->customer->isLogged()) {
            $json['status'] = 10014;

            $json['message'] = 'Unauthorized Session Expired!';

            http_response_code(401);
        }

        if ($this->customer->isLogged()) {
            $data = [];

            $rangeonestart = "09:00:00";
            $rangeoneend = "18:59:59";

            $rangetwostart = "19:00:00";
            $rangetwoend = "21:59:59";

            $rangethreestart = "22:00:00";
            $rangethreeend = "23:59:59";

            $rangefourstart = "00:00:00";
            $rangefourend = "08:59:59";

            /* $rangefivestart = "09:00:00";
              $rangefiveend = "09:59:59"; */

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
                $data['disabled_slot'] = array('06:00am - 08:00am');
                $data['selected_date_slot'] = $next_day;
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

            /* if (time() >= strtotime($rangefivestart) && time() <= strtotime($rangefiveend)) {
              $pre_defined_slots = array('04:00pm - 06:00pm');
              $selected_slot = $pre_defined_slots[0];
              $data['selected_slot'] = $selected_slot;
              $data['selected_date_slot'] = date('d-m-Y');
              $data['disabled_slot'] = array('06:00am - 08:00am', '08:00am - 10:00am', '10:00am - 12:00pm', '02:00pm - 04:00pm');
              $log->write('RANGE FIVE');
              } */

            $this->language->load('checkout/delivery_time');

            $data['text_no_timeslot'] = $this->language->get('text_no_timeslot');

            $store_id = 75;
            $shipping_method = 75;

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

            if (isset($this->request->get['shipping_address_id']) && $this->request->get['shipping_address_id'] != NULL && $this->request->get['shipping_address_id'] > 0) {
                $log->write('shipping_address_id');
                $log->write($this->request->get['shipping_address_id']);
                $log->write('shipping_address_id');
                /* REMOVE DAYS BASED ON CITY OR REGION */
                $order_delivery_days = NULL;
                $city_details = NULL;
                $selected_address_id = $this->request->get['shipping_address_id'];
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
                $data['dates'] = array_values($data['dates']);
                $log->write('dates');
                $log->write($data['dates']);
                $log->write('dates');
                /* REMOVE DAYS BASED ON CITY OR REGION */
            }


            $json['data']['dates'] = $data['dates'];
            $json['data']['timeslots'] = $data['timeslots'];
            $json['data']['selected_time_slot'] = $data['selected_slot'];
            $json['data']['selected_date_slot'] = $data['selected_date_slot'];
            $json['data']['disabled_slot'] = $data['disabled_slot'];
            $json['message'] = 'Please Pre Populate These Date And Time Slots!';

            $stores = $this->cart->getStores();
            foreach ($stores as $store_id) {
                $this->session->data['timeslot'][$store_id] = $data['selected_slot'];
                $this->session->data['dates'][$store_id] = $data['selected_date_slot'];
            }
            /* $log = new Log('error.log');
              $log->write('SLOTS');
              $log->write($data['selected_slot']);
              $log->write($data['dates'][0]);
              $log->write('SLOTS'); */
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
