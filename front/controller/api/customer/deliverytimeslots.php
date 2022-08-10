<?php

class ControllerApiCustomerDeliverytimeslots extends Controller {

    private $error = [];

    public function getDeliverytimeslot() {
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

            // $data['text_no_timeslot'] = $this->language->get('text_no_timeslot');

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

            $json['message'][] = ['type' => '', 'body' => 'Store Id and Shipping Method required'];

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

    public function getPaymentmethods() {
        //echo "<pre>";print_r('getStoreShippingMethods');die;
        $json = [];

        $this->load->language('information/locations');
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');
        $this->load->language('checkout/checkout');

        $this->load->model('assets/category');
        $this->load->model('assets/product');
        $this->load->model('tool/image');
        $this->load->model('account/customer');
        $this->load->model('extension/extension');

        if (isset($this->request->get['total']) && isset($this->request->get['cutomer_id']) && $this->request->get['cutomer_id'] > 0) {

            $customer_info = $this->model_account_customer->getCustomer($this->request->get['cutomer_id']);

            if (is_array($customer_info) && count($customer_info) > 0) {
                // Payment Methods
                $method_data = [];

                $results = $this->model_extension_extension->getExtensions('payment');

                // echo "<pre>";print_r($results);die;
                $total = $this->request->get['total'];
                if ($customer_info['payment_terms'] == 'Payment On Delivery') {
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
                } else if ($customer_info['payment_terms'] == '7 Days Credit' || $customer_info['payment_terms'] == '15 Days Credit' || $customer_info['payment_terms'] == '30 Days Credit') {
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
            }
        } else {
            $json['status'] = 10013;

            $json['message'][] = ['type' => '', 'body' => 'Customer ID And Order total is required.'];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getMixedPaymentMethodsOld() {
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
            if (($this->customer->getPaymentTerms() == 'Payment On Delivery' && $this->customer->getCustomerPezeshaId() == NULL && $this->customer->getCustomerPezeshauuId() == NULL) || ($this->customer->getPaymentTerms() == 'Payment On Delivery' && $this->customer->getCustomerPezeshaId() != NULL && $this->customer->getCustomerPezeshauuId() != NULL && $pezesha_customer_credit_limit == 0)) {

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
            } if ((($this->customer->getPaymentTerms() == '7 Days Credit' || $this->customer->getPaymentTerms() == '15 Days Credit' || $this->customer->getPaymentTerms() == '30 Days Credit') && ($this->customer->getCustomerPezeshaId() == NULL && $this->customer->getCustomerPezeshauuId() == NULL)) || (($this->customer->getPaymentTerms() == '7 Days Credit' || $this->customer->getPaymentTerms() == '15 Days Credit' || $this->customer->getPaymentTerms() == '30 Days Credit') && ($this->customer->getCustomerPezeshaId() != NULL && $this->customer->getCustomerPezeshauuId() != NULL && $pezesha_customer_credit_limit == 0))) {

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
            } if ($this->customer->getCustomerPezeshaId() != NULL && $this->customer->getCustomerPezeshauuId() != NULL && $this->config->get('pezesha_status') && $pezesha_customer_credit_limit > 0) {

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

    public function getMixedPaymentMethods() {
        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];
        try {

            if (isset($this->request->get['total'])) {

                unset($this->session->data['pezesha_amount_limit']);
                unset($this->session->data['pezesha_customer_amount_limit']);
                //get the pezesha amount limit 
                // $this->load->controller('customer/getPezeshaLoanOffers');

                $log = new Log('error.log');

                $b = $this->getPezeshaLoanOffers();
                $pezesha_customer_credit_limit = $this->getPezeshaCustomerCreditLimit();

                // echo "<pre>";print_r($this->session->data['pezesha_amount_limit']);die;
                $customer_id = $this->customer->getId();
                $log->write('Getting payments in mobile.....' . $customer_id);

                $this->load->language('checkout/checkout');
                // Payment Methods
                $method_data = [];

                $this->load->model('extension/extension');

                $results = $this->model_extension_extension->getExtensions('payment');

                // echo "<pre>";print_r($this->customer);die;
                $total = $this->request->get['total'];
                // if ($this->customer->getPaymentTerms() == 'Payment On Delivery') {
                // Totals
                $total_data = [];
                $total = 0;
                $taxes = $this->cart->getTaxes();
                // echo "<pre>";print_r($taxes);die;
                $this->load->model('extension/extension');
                $sort_order = [];
                $results = $this->model_extension_extension->getExtensions('total');

                foreach ($results as $key => $value) {
                    $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
                }

                array_multisort($sort_order, SORT_ASC, $results);

                foreach ($results as $result) {
                    if ($this->config->get($result['code'] . '_status')) {
                        $this->load->model('total/' . $result['code']);

                        $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
                    }
                }

                // Payment Methods
                $method_data = [];

                $this->load->model('extension/extension');

                $results = $this->model_extension_extension->getExtensions('payment');

                //    echo "<pre>";print_r($total);die;
                $recurring = $this->cart->hasRecurringProducts();
                //    if($total !=$this->request->get['total'] )
                //    {
                //     $log = new Log('error.log');
                //     $log->write('total in payment methods API not same as send amount');
                //     $total =$this->request->get['total'];
                //    }

                foreach ($results as $result) {

                    $log->write('code payment 123');
                    $log->write($result['code']);
                    $log->write('code');
                    if ($this->config->get($result['code'] . '_status')) {
                        $this->load->model('payment/' . $result['code']);

                        $method = $this->{'model_payment_' . $result['code']}->getMethod($total);
                        //    echo "<pre>";print_r($method);
                        $log->write($total);
                        $log->write('total calculated');

                        if ($method) {
                            if ($recurring) {
                                if (method_exists($this->{'model_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_payment_' . $result['code']}->recurringPayments()) {
                                    $method_data[$result['code']] = $method;
                                }
                            } else {
                                $method_data[$result['code']] = $method;
                            }
                        }
                    }
                }
                $sort_order = [];

                foreach ($method_data as $key => $value) {
                    $sort_order[$key] = $value['sort_order'];
                }

                array_multisort($sort_order, SORT_ASC, $method_data);
                //   echo "<pre>";print_r($method_data);die;
                $log->write($method_data);

                $this->session->data['payment_methods'] = $method_data;

                //   echo "<pre>";print_r(empty($this->session->data['payment_methods']));die;

                if (empty($this->session->data['payment_methods'])) {
                    $data['error_warning'] = 'No payment methods available.Please contact kwikbasket team';
                    $log->write('method_data is empty or not');
                } else {
                    $data['error_warning'] = '';
                }
                if (isset($this->session->data['payment_methods'])) {
                    $data['payment_methods'] = $this->session->data['payment_methods'];
                } else {
                    $data['payment_methods'] = [];
                }

                $log->write('getPaymentTerms');
                $log->write($this->customer->getPaymentTerms());
                // unset($this->session->data['pezesha_amount_limit']);
                // unset($this->session->data['pezesha_customer_amount_limit']);
                // //get the pezesha amount limit 
                // // $a= $this->load->controller('api/customer/getPezeshaLoanOffers');
                // $a = $this->getPezeshaLoanOffers();
                // echo "<pre>";print_r($this->session->data['pezesha_customer_amount_limit']);die;
                // echo "<pre>";print_r($data);die;
                $log->write('pezesha data Start ');

                $log->write($this->customer->getCustomerPezeshauuId());
                $log->write($this->customer->getCustomerPezeshaId());
                $log->write($this->getPezeshaCustomerCreditLimit() . 'PEZESHA LIMIT');
                $log->write($pezesha_customer_credit_limit . 'PEZESHA LIMIT');

                /* $log->write($this->session->data['pezesha_customer_amount_limit']);
                  $log->write($this->session->data['pezesha_amount_limit']); */
                $log->write('pezesha data');
                $log->write('pezesha data  getting or not');

                if (($this->customer->getPaymentTerms() == 'Payment On Delivery' && $this->customer->getCustomerPezeshaId() == NULL && $this->customer->getCustomerPezeshauuId() == NULL) || ($this->customer->getPaymentTerms() == 'Payment On Delivery' && $this->customer->getCustomerPezeshaId() != NULL && $this->customer->getCustomerPezeshauuId() != NULL && $pezesha_customer_credit_limit == 0)) {
                    foreach ($data['payment_methods'] as $payment_method) {
                        if ($payment_method['code'] == 'wallet') {
                            $data['payment_wallet_methods'] = $payment_method;
                        }
                        if (/* $payment_method['code'] != 'wallet' && */ $payment_method['code'] != 'mod' && $payment_method['code'] != 'pesapal' && $payment_method['code'] != 'interswitch' && $payment_method['code'] != 'mpesa') {
                            unset($data['payment_methods'][$payment_method['code']]);
                        }
                    }
                } if ((($this->customer->getPaymentTerms() == '7 Days Credit' || $this->customer->getPaymentTerms() == '15 Days Credit' || $this->customer->getPaymentTerms() == '30 Days Credit') && ($this->customer->getCustomerPezeshaId() == NULL && $this->customer->getCustomerPezeshauuId() == NULL)) || (($this->customer->getPaymentTerms() == '7 Days Credit' || $this->customer->getPaymentTerms() == '15 Days Credit' || $this->customer->getPaymentTerms() == '30 Days Credit') && ($this->customer->getCustomerPezeshaId() != NULL && $this->customer->getCustomerPezeshauuId() != NULL && $pezesha_customer_credit_limit == 0))) {
                    foreach ($data['payment_methods'] as $payment_method) {
                        if ($payment_method['code'] == 'wallet') {
                            $data['payment_wallet_methods'] = $payment_method;
                        }
                        if ($payment_method['code'] != 'cod') {
                            unset($data['payment_methods'][$payment_method['code']]);
                        }
                    }
                } if ($this->customer->getCustomerPezeshaId() != NULL && $this->customer->getCustomerPezeshauuId() != NULL && $this->config->get('pezesha_status') && $pezesha_customer_credit_limit > 0) {
                    foreach ($data['payment_methods'] as $payment_method) {
                        if ($payment_method['code'] == 'wallet') {
                            $data['payment_wallet_methods'] = $payment_method;
                        }
                        if ($payment_method['code'] != 'pezesha' && $payment_method['code'] != 'mpesa') {//&& $payment_method['code'] != 'pesapal'
                            unset($data['payment_methods'][$payment_method['code']]);
                        }
                    }
                }
                $log->write('getPaymentTerms');

                //}
                $json['data'] = $data;
                $log->write($data);

                if (empty($data['payment_methods']) && empty($data['payment_wallet_methods'])) {
                    $data['error_warning'] = 'No payment methods available.Please contact kwikbasket team';
                    $log->write('No payment methods available.Please contact kwikbasket team');
                } else {
                    $data['error_warning'] = '';
                }
            } else {
                $json['status'] = 10013;

                $json['message'][] = ['type' => '', 'body' => 'Order total is required.'];

                http_response_code(400);
                $log = new Log('error.log');
                $log->write('total received is 0 and unable to get payment methods');
            }
        } catch (exception $ex) {
            $log = new Log('error.log');
            $log->write('get Mixed payment methods--ERROR');
            $log->write($ex->getMessage());
        } finally {
            $log->write('PAYMENT METHODS RESPONSE');
            $log->write($json);
            $log->write('PAYMENT METHODS RESPONSE');
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }
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

    public function addCheckout($args = []) {

        $log = new Log('error.log');
        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = '';

        if ($this->validatenewly($args)) {
            $log->write('addMpesaCheckOutNew');
            $log->write($args);
            $log->write('addMpesaCheckOutNew');
            $stores = array_keys($args['stores']);

            $active_Store_exsists = in_array("75", $stores);
            $shipping_added = 0;

            //print_r($stores);
            foreach ($stores as $store_id) {
                $order_data[$store_id] = [];
                $order_data[$store_id]['totals'] = [];

                $total = 0;
                $taxes = $this->cart->getTaxes();
                $taxes_by_store = $this->cart->getTaxesByStore($store_id);
                $log->write('taxes_by_store mobile');
                $log->write($store_id);
                $log->write($taxes_by_store);
                $log->write('taxes_by_store mobile');

                $this->load->model('extension/extension');

                $sort_order = [];

                $results = $this->model_extension_extension->getExtensions('total');

                foreach ($results as $key => $value) {
                    $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
                }
                array_multisort($sort_order, SORT_ASC, $results);

                foreach ($results as $result) {
                    if ($this->config->get($result['code'] . '_status')) {
                        $log->write($result['code']);
                        $this->load->model('total/' . $result['code']);

                        /* $log->write("in multiStoreIndex".$result['code']);
                          $log->write("in loop".$total); */

                        //$this->{'model_total_' . $result['code']}->getApiTotal( $order_data[$store_id]['totals'], $total, $taxes,$store_id ,$args['stores'][$store_id]);

                        if ($result['code'] == 'shipping') {
                            if ($active_Store_exsists == 1) {
                                $this->{'model_total_' . $result['code']}->getTotal($order_data[$store_id]['totals'], $total, $taxes_by_store, $store_id);
                            } else if ($result['code'] == 'shipping' && $active_Store_exsists == 0 && $shipping_added == 0) {
                                $this->{'model_total_' . $result['code']}->getTotal($order_data[$store_id]['totals'], $total, $taxes_by_store, -1);
                            }
                            $shipping_added = 1; //shipping charge added to one of the stores
                        } else {
                            $this->{'model_total_' . $result['code']}->getApiTotal($order_data[$store_id]['totals'], $total, $taxes_by_store, $store_id, $args);
                        }
                    }
                }

                $log->write('addOrder b total end');

                $sort_order = [];

                foreach ($order_data[$store_id]['totals'] as $key => $value) {
                    $sort_order[$key] = $value['sort_order'];
                }

                array_multisort($sort_order, SORT_ASC, $order_data[$store_id]['totals']);

                $this->load->model('sale/order');
                $store_info = $this->model_sale_order->getStoreInfo($store_id);

                $this->load->language('checkout/checkout');
                $order_data[$store_id]['invoice_prefix'] = $this->config->get('config_invoice_prefix');
                $order_data[$store_id]['store_id'] = $store_id;
                $order_data[$store_id]['store_name'] = $store_info['name'];

                $order_data[$store_id]['commission'] = ($store_info['commision'] > 0) ? $store_info['commision'] : $store_info['vendor_commision'];

                $order_data[$store_id]['fixed_commission'] = ($store_info['fixed_commision'] > 0) ? $store_info['fixed_commision'] : $store_info['vendor_fixed_commision'];

                if ($this->request->server['HTTPS']) {
                    $server = $this->config->get('config_ssl');
                } else {
                    $server = $this->config->get('config_url');
                }

                if ($order_data[$store_id]['store_id']) {
                    $order_data[$store_id]['store_url'] = $this->config->get('config_url');
                } else {
                    $order_data[$store_id]['store_url'] = $server;
                }

                if (!trim($order_data[$store_id]['store_url'])) {
                    $order_data[$store_id]['store_url'] = $server;
                }

                if ($this->customer->isLogged()) {
                    $this->load->model('account/customer');

                    $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

                    $order_data[$store_id]['customer_id'] = $this->customer->getId();
                    $order_data[$store_id]['customer_group_id'] = $customer_info['customer_group_id'];
                    $order_data[$store_id]['firstname'] = $customer_info['firstname'];
                    $order_data[$store_id]['lastname'] = $customer_info['lastname'];
                    $order_data[$store_id]['email'] = $customer_info['email'];
                    $order_data[$store_id]['telephone'] = $customer_info['telephone'];
                    $order_data[$store_id]['fax'] = $customer_info['fax'];
                    $order_data[$store_id]['custom_field'] = unserialize($customer_info['custom_field']);
                } elseif (isset($this->session->data['guest'])) {
                    $order_data[$store_id]['customer_id'] = 0;
                    $order_data[$store_id]['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
                    $order_data[$store_id]['firstname'] = $this->session->data['guest']['firstname'];
                    $order_data[$store_id]['lastname'] = $this->session->data['guest']['lastname'];
                    $order_data[$store_id]['email'] = $this->session->data['guest']['email'];
                    $order_data[$store_id]['telephone'] = $this->session->data['guest']['telephone'];
                    $order_data[$store_id]['fax'] = $this->session->data['guest']['fax'];
                    $order_data[$store_id]['custom_field'] = $this->session->data['guest']['custom_field'];
                }

                if (isset($args['payment_method'])) {
                    $order_data[$store_id]['payment_method'] = $args['payment_method'];
                } else {
                    $order_data[$store_id]['payment_method'] = '';
                }

                if (isset($args['mpesa_phonenumber']) && $args['mpesa_phonenumber'] != NULL) {
                    $order_data[$store_id]['mpesa_phonenumber'] = $args['mpesa_mobile_number'];
                } else {
                    $order_data[$store_id]['mpesa_phonenumber'] = $this->customer->getTelephone();
                }

                if (isset($args['payment_method_code'])) {
                    $order_data[$store_id]['payment_code'] = $args['payment_method_code'];

                    $c = $this->getPaymentName($args['payment_method_code']);
                    if (!empty($c)) {
                        $order_data[$store_id]['payment_method'] = $c;
                    }
                } else {
                    $order_data[$store_id]['payment_code'] = '';
                }

                if (isset($args['stores'][$store_id]['shipping_method']) && isset($args['stores'][$store_id]['shipping_code'])) {
                    if (isset($args['stores'][$store_id]['shipping_method'])) {
                        $order_data[$store_id]['shipping_method'] = $args['stores'][$store_id]['shipping_method'];
                    } else {
                        $order_data[$store_id]['shipping_method'] = '';
                    }

                    if (isset($args['stores'][$store_id]['shipping_code'])) {
                        $order_data[$store_id]['shipping_code'] = $args['stores'][$store_id]['shipping_code'];

                        $c = $this->getShippingName($args['stores'][$store_id]['shipping_code'], $store_id);
                        if (!empty($c)) {
                            $order_data[$store_id]['shipping_method'] = $c;
                        }
                    } else {
                        $order_data[$store_id]['shipping_code'] = '';
                    }
                } else {
                    $order_data[$store_id]['shipping_method'] = '';
                    $order_data[$store_id]['shipping_code'] = '';
                }

                if (isset($args['shipping_city_id'])) {
                    $shipping_city_id = $args['shipping_city_id'];
                    $order_data[$store_id]['shipping_city_id'] = $shipping_city_id;
                } else {
                    $order_data[$store_id]['shipping_city_id'] = '';
                }

                if (isset($args['shipping_address_id'])) {
                    $shipping_address_id = $args['shipping_address_id'];
                    $this->load->model('account/address');
                    $shipping_address_data = $this->model_account_address->getAddress($shipping_address_id);

                    $order_data[$store_id]['shipping_address'] = $shipping_address_data['address'];
                    $order_data[$store_id]['shipping_name'] = $shipping_address_data['name'];

                    $order_data[$store_id]['shipping_flat_number'] = $shipping_address_data['flat_number'];
                    $order_data[$store_id]['shipping_landmark'] = $shipping_address_data['landmark'];
                    $order_data[$store_id]['shipping_building_name'] = $shipping_address_data['building_name'];
                    $order_data[$store_id]['shipping_zipcode'] = $shipping_address_data['zipcode'];

                    $order_data[$store_id]['latitude'] = $shipping_address_data['latitude'];
                    $order_data[$store_id]['longitude'] = $shipping_address_data['longitude'];

                    if (isset($args['shipping_contact_no'])) {
                        $shipping_contact_no = $args['shipping_contact_no'];
                        $order_data[$store_id]['shipping_contact_no'] = $shipping_contact_no;
                    } elseif (isset($shipping_address_data['contact_no'])) {
                        $order_data[$store_id]['shipping_contact_no'] = $shipping_address_data['contact_no'];
                    } else {
                        $order_data[$store_id]['shipping_contact_no'] = '';
                    }
                } else {
                    $order_data[$store_id]['shipping_address'] = '';
                    $order_data[$store_id]['shipping_name'] = '';
                    $order_data[$store_id]['shipping_contact_no'] = '';
                    $order_data[$store_id]['shipping_zipcode'] = '';
                    $order_data[$store_id]['shipping_flat_number'] = '';
                    $order_data[$store_id]['shipping_landmark'] = '';
                    $order_data[$store_id]['shipping_building_name'] = '';
                }

                $order_data[$store_id]['products'] = [];

                $this->load->model('assets/product');

                foreach ($args['products'] as $product) {
                    $option_data = [];

                    $vendor_id = $this->model_extension_extension->getVendorId($product['store_id']);

                    $db_product_detail = $this->model_assets_product->getProductForPopupByApi($product['store_id'], $product['product_store_id']);

                    if ($store_id == $product['store_id']) {

                        if (is_null($db_product_detail['special_price']) || !($db_product_detail['special_price'] + 0)) {
                            $db_product_detail['special_price'] = $db_product_detail['price'];
                        }

                        $order_data[$store_id]['products'][] = [
                            'product_store_id' => $product['product_store_id'],
                            'product_id' => isset($db_product_detail['product_id']) ? $db_product_detail['product_id'] : '',
                            'store_product_variation_id' => $product['store_product_variation_id'],
                            'store_id' => $product['store_id'],
                            'vendor_id' => $vendor_id,
                            'name' => $db_product_detail['pd_name'],
                            'unit' => $db_product_detail['unit'],
                            'product_type' => trim($product['product_type']),
                            'product_note' => trim($product['product_note']),
                            'produce_type' => trim($product['produce_type']),
                            'model' => $db_product_detail['model'],
                            'option' => $option_data,
                            'download' => $product['download'],
                            'quantity' => $product['quantity'],
                            'subtract' => $db_product_detail['subtract_quantity'],
                            // 'price' => $db_product_detail['special_price'],
                            'price' => $product['price'], //check
                            // 'total' => ($product['quantity'] * $db_product_detail['special_price']),
                            'total' => ($product['price'] * $product['quantity']),
                            'tax' => $this->tax->getTax($product['price'], $db_product_detail['tax_class_id']),
                            // 'tax' => $this->tax->getTax($db_product_detail['special_price'], $db_product_detail['tax_class_id']),
                            'reward' => $product['reward'],
                        ];
                    }
                }
                $order_data[$store_id]['vouchers'] = [];

                /* if(isset($args['dropoff_notes']) && strlen($args['dropoff_notes']) > 0 ) {
                  $order_data[$store_id]['comment'] = $args['dropoff_notes'];
                  } else {
                  $order_data[$store_id]['comment'] = '';
                  } */

                if (isset($args['stores'][$store_id]['comment']) && strlen($args['stores'][$store_id]['comment']) > 0) {
                    $order_data[$store_id]['comment'] = $args['stores'][$store_id]['comment'];
                } else {
                    $order_data[$store_id]['comment'] = '';
                }

                $order_data[$store_id]['total'] = $total;

                $order_data[$store_id]['affiliate_id'] = 0;
                $order_data[$store_id]['marketing_id'] = 0;
                $order_data[$store_id]['tracking'] = '';
                $order_data[$store_id]['language_id'] = $this->config->get('config_language_id');
                $order_data[$store_id]['currency_id'] = $this->currency->getId();
                $order_data[$store_id]['currency_code'] = $this->currency->getCode();
                $order_data[$store_id]['currency_value'] = $this->currency->getValue($this->currency->getCode());
                $order_data[$store_id]['ip'] = $this->request->server['REMOTE_ADDR'];
                $order_data[$store_id]['customer_id'] = $this->customer->getId();

                if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
                    $order_data[$store_id]['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
                } elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
                    $order_data[$store_id]['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
                } else {
                    $order_data[$store_id]['forwarded_ip'] = '';
                }

                /* if (isset($args['order_reference_number'])) {
                  $order_data[$store_id]['order_reference_number'] = $args['order_reference_number'];
                  } else {
                  $order_data[$store_id]['order_reference_number'] = '';
                  } */

                if (isset($args['stores'][$store_id]['order_reference_number']) && strlen($args['stores'][$store_id]['order_reference_number']) > 0) {
                    $order_data[$store_id]['order_reference_number'] = $args['stores'][$store_id]['order_reference_number'];
                } else {
                    $order_data[$store_id]['order_reference_number'] = '';
                }

                if (isset($this->request->server['HTTP_USER_AGENT'])) {
                    $order_data[$store_id]['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
                } else {
                    $order_data[$store_id]['user_agent'] = '';
                }

                if (isset($this->request->server['HTTP_USER_AGENT'])) {
                    $order_data[$store_id]['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
                } else {
                    $order_data[$store_id]['user_agent'] = '';
                }

                $order_data[$store_id]['accept_language'] = '';

                $this->load->model('api/checkout');
                if ($store_id == 75) {
                    if (isset($args['stores'][$store_id]['dates'])) {
                        $order_data[$store_id]['delivery_date'] = $args['stores'][$store_id]['dates'];
                    } else {
                        $order_data[$store_id]['delivery_date'] = date('d-m-Y');
                    }

                    //$log->write("shipin code".$order_data[$store_id]['shipping_code']);die;

                    if (isset($order_data[$store_id]['shipping_code']) && 'express.express' == trim($order_data[$store_id]['shipping_code'])) {
                        $order_data[$store_id]['delivery_date'] = date('d-m-Y');

                        $settings = $this->getSettings('express', 0);
                        $timeDiff = $settings['express_how_much_time'];

                        $min = 0;
                        if ($timeDiff) {
                            $i = explode(':', $timeDiff);
                            $min = $min + $i[0] * 60 + $i[1]; //add difference minut to current time
                        }
                        $to = date('h:ia', strtotime('+' . $min . ' minutes', strtotime(date('h:ia'))));

                        $delivery_timeslot = date('h:ia') . ' - ' . $to;

                        $order_data[$store_id]['delivery_timeslot'] = $delivery_timeslot;
                    } else {
                        if (isset($args['stores'][$store_id]['dates'])) {
                            $order_data[$store_id]['delivery_date'] = $args['stores'][$store_id]['dates'];
                        } else {
                            $order_data[$store_id]['delivery_date'] = date('d-m-Y');
                        }

                        if (isset($args['stores'][$store_id]['timeslot'])) {
                            $order_data[$store_id]['delivery_timeslot'] = $args['stores'][$store_id]['timeslot'];
                        } else {
                            $settings = $this->getSettings('express', 0);
                            $timeDiff = $settings['express_how_much_time'];

                            $min = 0;
                            if ($timeDiff) {
                                $i = explode(':', $timeDiff);
                                $min = $min + $i[0] * 60 + $i[1]; //add difference minut to current time
                            }
                            $to = date('h:ia', strtotime('+' . $min . ' minutes', strtotime(date('h:ia'))));

                            $delivery_timeslot = date('h:ia') . ' - ' . $to;

                            $order_data[$store_id]['delivery_timeslot'] = $delivery_timeslot;
                        }
                    }
                }
                if ($store_id != 75) {
                    $other_vendor_delivery_time = $this->load->controller('api/delivery_time/getothervendordeliverytime', $store_id);
                    $log = new Log('error.log');
                    $log->write('other_vendor_delivery_time');
                    $log->write($other_vendor_delivery_time);
                    $log->write('other_vendor_delivery_time');
                    //$other_vendor_delivery_time = $this->getothervendordeliverytime($store_id);

                    if ($other_vendor_delivery_time != null && $other_vendor_delivery_time['selected_time_slot_date'] != null && $other_vendor_delivery_time['selected_time_slot_date'] != '') {
                        $order_data[$store_id]['delivery_date'] = $other_vendor_delivery_time['selected_time_slot_date'];
                        $order_data[$store_id]['delivery_timeslot'] = $other_vendor_delivery_time['selected_time_slot_time'];
                    } else {
                        if (isset($args['stores'][$store_id]['dates'])) {
                            $order_data[$store_id]['delivery_date'] = $args['stores'][$store_id]['dates'];
                        } else {
                            $order_data[$store_id]['delivery_date'] = date('d-m-Y');
                        }

                        if (isset($args['stores'][$store_id]['timeslot'])) {
                            $order_data[$store_id]['delivery_timeslot'] = $args['stores'][$store_id]['timeslot'];
                        }
                    }
                }
            }
            $order_data[$store_id]['login_latitude'] = $args['login_latitude'];
            $order_data[$store_id]['login_longitude'] = $args['login_longitude'];
            $order_data[$store_id]['login_mode'] = $args['login_mode'];

            $log->write('addMultiOrder call');

            /* $order_ids = [];

              $order_ids = $this->model_api_checkout->addMultiOrder($order_data); */

            $tot = 0;

            foreach ($stores as $store_id) {
                $data['totals'] = [];
                foreach ($order_data[$store_id]['totals'] as $total) {
                    $data['totals'][] = [
                        'title' => $total['title'],
                        'text' => $this->currency->format($total['value']),
                    ];
                    $tot += $total['value'];
                }
            }

            $transactionData = [
                'no_of_products' => count($args['products']),
                'total' => $args['total'],
            ];

            //$log->write($transactionData);
            //$this->model_api_checkout->apiAddTransaction($transactionData, $order_ids);

            if (('mpesa' == $args['payment_method_code']) || ('mpesa' == $args['payment_method_code'] && 'wallet' == $args['payment_wallet_method_code'])) {
                //save for refrence id correct order id
                $mpesa_result = NULL;
                if (('mpesa' == $args['payment_method_code']) && (!isset($args['payment_wallet_method_code']))) {
                    $mpesa_result = $this->SendPaymentRequestToMpesa($this->cart->getTotalWithShipping(), $args['mpesa_mobile_number'], base64_encode($this->customer->getId() . '_' . $this->cart->getTotalWithShipping() . '_' . date("Y-m-d h:i:s")));
                }

                if (('mpesa' == $args['payment_method_code']) && isset($args['payment_wallet_method_code']) && 'wallet' == $args['payment_wallet_method_code']) {
                    $mpesa_result = $this->SendPaymentRequestToMpesa($this->cart->getTotalWithShipping(), $args['mpesa_mobile_number'], base64_encode($this->customer->getId() . '_' . $this->cart->getTotalWithShipping() . '_' . date("Y-m-d h:i:s")));
                }
                $log->write('mpesa_result');
                $log->write($mpesa_result);
                $log->write('mpesa_result');
                $cache_pre_fix = '_' . $mpesa_result['CheckoutRequestID'];
                if (isset($mpesa_result) && isset($mpesa_result['ResponseCode']) && $mpesa_result['ResponseCode'] == 0) {
                    $this->load->model('payment/mpesa');

                    $mpesa_request_ids = $this->model_payment_mpesa->insertMobileMpesaRequest($this->customer->getId(), $mpesa_result['MerchantRequestID'], $mpesa_result['CheckoutRequestID'], $this->cart->getTotalWithShipping());

                    $log->write('mpesa_request_ids');
                    $log->write($mpesa_request_ids);
                    $log->write('mpesa_request_ids');

                    $this->cache->delete('customer_order_data' . $cache_pre_fix);
                    $this->cache->set('customer_order_data' . $cache_pre_fix, $order_data);

                    $json['status'] = 200;
                    $json['message_from_mpesa'] = $mpesa_result['ResponseDescription'];
                    $json['message'] = 'A payment request has been sent on your above number. Please make the payment by entering mpesa PIN.';
                    $json['data']['merchant_request_id'] = $mpesa_result['MerchantRequestID'];
                    $json['data']['checkout_request_id'] = $mpesa_result['CheckoutRequestID'];
                } elseif (isset($mpesa_result) && isset($mpesa_result['errorCode']) && $mpesa_result['errorCode'] > 0) {
                    $this->cache->delete('customer_order_data' . $cache_pre_fix);

                    $json['status'] = 400;
                    $json['message'] = $mpesa_result['errorMessage'];
                }
            }
        } else {
            $json['status'] = 10014;

            foreach ($this->error as $key => $value) {
                $json['message'][] = ['type' => $key, 'body' => $value];
            }

            http_response_code(400);
        }

        $log->write('ordernew json');
        $log->write($json);
        $log->write('ordernew json');
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validatenewly($args) {
        if (empty($args['customer_id']) || (isset($args['customer_id']) && !is_numeric($args['customer_id']))) {
            $this->error['customer_id'] = 'Customer ID Required!';
        }

        $args['customer_id'] = isset($args['customer_id']) && $args['customer_id'] > 0 ? $args['customer_id'] : 0;
        if (empty($args['payment_method'])) {
            $this->error['payment_method'] = $this->language->get('error_payment_method');
        }

        if (empty($args['payment_method_code'])) {
            $this->error['payment_method_code'] = $this->language->get('error_payment_method_code');
        }

        if (empty($args['shipping_address_id'])) {
            $this->error['shipping_address_id'] = $this->language->get('error_shipping_address_id');
        }

        if (empty($args['stores']) || !is_array($args['stores'])) {
            $this->error['error_stores'] = $this->language->get('error_stores');
        }

        if (empty($args['products']) || !is_array($args['products'])) {
            $this->error['error_products'] = $this->language->get('error_products');
        }

        if (!empty($args['products']) && is_array($args['products'])) {
            foreach ($args['products'] as $product) {
                if (!array_key_exists('product_store_id', $product)) {
                    $this->error['product_store_id'] = 'Product Store ID Required!';
                }

                if (!array_key_exists('store_id', $product)) {
                    $this->error['store_id'] = 'Store ID Required!';
                }

                if (!array_key_exists('store_product_variation_id', $product)) {
                    $this->error['store_product_variation_id'] = 'Product Store Variation ID Required!';
                }

                if (!array_key_exists('product_type', $product)) {
                    $this->error['product_type'] = 'Product Type Required!';
                }

                if (!array_key_exists('product_note', $product)) {
                    $this->error['product_note'] = 'Product Note Required!';
                }

                if (!array_key_exists('quantity', $product)) {
                    $this->error['quantity'] = 'Product Quantity Required!';
                }

                if (!array_key_exists('price', $product)) {
                    $this->error['price'] = 'Product Price Required!';
                }
            }
        }

        if (!empty($args['stores']) && is_array($args['stores'])) {
            foreach ($args['stores'] as $store) {
                if (!array_key_exists('store_id', $store)) {
                    $this->error['store_id'] = 'Store ID Required!';
                }

                if (!array_key_exists('timeslot', $store)) {
                    $this->error['timeslot'] = 'Store TimeSlot Required!';
                }

                if (!array_key_exists('timeslot_selected', $store)) {
                    $this->error['timeslot_selected'] = 'Store TimeSlot Selected Required!';
                }

                if (!array_key_exists('dates', $store)) {
                    $this->error['dates'] = 'Store TimeSlot Selected Required!';
                }

                if (!array_key_exists('delivery_date', $store)) {
                    $this->error['delivery_date'] = 'Store Delivery Date Required!';
                }

                if (!array_key_exists('comment', $store)) {
                    $this->error['comment'] = 'Store Comment Required!';
                }

                if (!array_key_exists('shipping_code', $store)) {
                    $this->error['shipping_code'] = 'Store Shipping Code Required!';
                }

                if (!array_key_exists('shipping_method', $store)) {
                    $this->error['shipping_method'] = 'Store Shipping Method Required!';
                }

                if (!array_key_exists('sub_total', $store)) {
                    $this->error['sub_total'] = 'Store Sub Total Required!';
                }

                if (!array_key_exists('total', $store)) {
                    $this->error['total'] = 'Store Total Required!';
                }

                if (!array_key_exists('weight', $store)) {
                    $this->error['weight'] = 'Store Weight Required!';
                }

                if (!array_key_exists('order_reference_number', $store)) {
                    $this->error['order_reference_number'] = 'Store Order Reference Number Required!';
                }
            }
        }

        $vendor_terms = json_decode($this->getCheckOtherVendorOrderExist($args), true);
        if ($vendor_terms['modal_open'] == TRUE) {
            $this->error['vendor_terms'] = 'Please accept vendor terms!';
        }

        $pending_orders_count = $this->getunpaidorderscount($args['customer_id']);
        if (isset($pending_orders_count) && count($pending_orders_count) > 0 && $pending_orders_count['unpaid_orders_count'] > 0) {
            $this->error['unpaid_orders'] = 'Your Order(s) Payment Is Pending!';
        }

        return !$this->error;
    }

    public function getCheckOtherVendorOrderExist($args) {

        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];
        $log = new Log('error.log');
        $json['modal_open'] = FALSE;

        $this->load->model('account/customer');
        $customer_info = $this->model_account_customer->getCustomer($args['customer_id']);

        if (isset($args['products']) && count($args['products']) > 0) {
            foreach ($args['products'] as $store_products) {
                /* FOR KWIKBASKET ORDERS */
                $log->write('CheckOtherVendorOrderExists');
                $log->write($store_products['store_id']);
                $log->write('CheckOtherVendorOrderExists');
                if ($store_products['store_id'] > 75 && $customer_info['payment_terms'] != 'Payment On Delivery') {
                    $json['modal_open'] = TRUE;
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getunpaidorderscount($customer_id) {
        $json = [];
        $data = [];
        $log = new Log('error.log');

        $this->load->model('account/customer');
        $customer_info = $this->model_account_customer->getCustomer($customer_id);

        if (isset($customer_info) && is_array($customer_info) && count($customer_info) > 0) {
            $log->write($customer_info['payment_terms']);
            $log->write($customer_info['customer_id']);

            $data['pending_order_id'] = NULL;

            if ($customer_info['payment_terms'] == 'Payment On Delivery') {
                $this->load->model('account/order');
                $this->load->model('sale/order');
                $page = 1;
                $results_orders = $this->model_account_order->getOrdersNew(($page - 1) * 10, 10, $NoLimit = true);
                $PaymentFilter = ['mPesa On Delivery', 'Cash On Delivery', 'mPesa Online', 'Corporate Account/ Cheque Payment', 'PesaPal', 'Interswitch', 'Pezesha'];
                if (count($results_orders) > 0) {
                    foreach ($results_orders as $order) {
                        if (in_array($order['payment_method'], $PaymentFilter) && ($order['order_status_id'] == 4 || $order['order_status_id'] == 5)) {
                            $order['transcation_id'] = $this->model_sale_order->getOrderTransactionId($order['order_id']);
                            if (empty($order['transcation_id']) || $order['paid'] == 'P') {
                                $data['pending_order_id'][] = $order['order_id'];
                            }
                        }
                    }
                }
            }

            $data['unpaid_orders_count'] = count($data['pending_order_id']);
            $data['message'] = count($data['pending_order_id']) > 0 ? 'Your Order(s) Payment Is Pending!' : '';
        }
        return $data;
    }

    public function getunpaidorderscounts($customer_id) {
        $json = [];
        $log = new Log('error.log');

        $this->load->model('account/customer');
        $customer_info = $this->model_account_customer->getCustomer($customer_id);

        if (isset($customer_info) && is_array($customer_info) && count($customer_info) > 0) {

            $log->write($customer_info['payment_terms']);
            $log->write($customer_info['customer_id']);

            $data['pending_order_id'] = NULL;

            if ($customer_info['payment_terms'] == 'Payment On Delivery') {
                $this->load->model('account/order');
                $this->load->model('sale/order');
                $page = 1;
                $results_orders = $this->model_account_order->getOrdersNew(($page - 1) * 10, 10, $NoLimit = true);
                $PaymentFilter = ['mPesa On Delivery', 'Cash On Delivery', 'mPesa Online', 'Corporate Account/ Cheque Payment', 'PesaPal', 'Interswitch', 'Pezesha'];
                if (count($results_orders) > 0) {
                    foreach ($results_orders as $order) {
                        if (in_array($order['payment_method'], $PaymentFilter) && ($order['order_status_id'] == 4 || $order['order_status_id'] == 5)) {
                            $order['transcation_id'] = $this->model_sale_order->getOrderTransactionId($order['order_id']);
                            if (empty($order['transcation_id']) || $order['paid'] == 'P') {
                                $data['pending_order_id'][] = $order['order_id'];
                            }
                        }
                    }
                }
            }

            $data['unpaid_orders_count'] = count($data['pending_order_id']);
            $data['message'] = count($data['pending_order_id']) > 0 ? 'Your Order(s) Payment Is Pending!' : '';
            //return $data;
            $json['status'] = 200;
            $json['data'] = $data;
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
