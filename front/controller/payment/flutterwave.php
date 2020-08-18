<?php

class ControllerPaymentFlutterwave extends Controller {

    public function index() {
        $this->load->language('payment/flutterwave');

        $this->load->model('setting/setting');
        $this->load->model('payment/flutterwave');
        $this->load->model('checkout/order');

        $data['text_instruction'] = $this->language->get('text_instruction');
        $data['text_payable'] = $this->language->get('text_payable');
        $data['text_address'] = $this->language->get('text_address');
        $data['text_payment'] = $this->language->get('text_payment');

        $data['button_confirm'] = $this->language->get('button_confirm');

        $data['payable'] = $this->config->get('flutterwave_payable');
        $data['address'] = nl2br($this->config->get('config_address'));

        $data['continue'] = $this->url->link('checkout/success');

        $data['customer_number'] = $this->customer->getTelephone();

        $data['action'] = $this->url->link('payment/flutterwave/confirm', '', 'SSL');

        $flutter_creds = $this->model_setting_setting->getSetting('flutterwave', 0);
        $log = new Log('error.log');
        $log->write('Flutterwave Creds');
        $log->write($flutter_creds);
        $log->write('Flutterwave Creds');

        foreach ($this->session->data['order_id'] as $key => $value) {
            $order_id = $value;
        }

        $log->write('Flutterwave Order ID');
        $log->write($this->session->data['order_id']);
        $log->write('Flutterwave Order ID');

        $order_info = $this->model_checkout_order->getOrder($order_id);

        $log->write('Flutterwave Order Info');
        $log->write($order_info);
        $log->write('Flutterwave Order Info');

        if (count($order_info) > 0) {
            $amount = (int) ($order_info['total']);
        }

        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/account', '', 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }

        if ($amount <= 0 || $amount == NULL || $order_id == NULL || $flutter_creds == NULL || $order_info == NULL) {
            $this->response->redirect($this->url->link('common/home/homepage'));
        }

        if ($this->customer->isLogged()) {
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/flutterwave.tpl')) {
                return $this->load->view($this->config->get('config_template') . '/template/payment/flutterwave.tpl', $data);
            } else {
                return $this->load->view('default/template/payment/flutterwave.tpl', $data);
            }
        }
    }

    public function confirm() {

        $this->load->model('setting/setting');
        $this->load->language('payment/flutterwave');
        $this->load->model('payment/flutterwave');
        $this->load->model('checkout/order');
        $this->load->model('account/customer');

        $flutter_creds = $this->model_setting_setting->getSetting('flutterwave', 0);
        $log = new Log('error.log');
        $log->write('Flutterwave Creds Customer Info');
        //$log->write($flutter_creds);
        $log->write('Flutterwave Creds Customer Info');
        $log->write($this->request->post["payment_option"] . 'PAYMENT_OPTION');

        foreach ($this->session->data['order_id'] as $key => $value) {
            $order_id = $value;
        }

        $log->write('Flutterwave Order ID');
        $log->write($this->session->data['order_id']);
        $log->write('Flutterwave Order ID');

        $order_info = $this->model_checkout_order->getOrder($order_id);
        $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);
        $log->write('Flutterwave Creds Customer Info');
        //$log->write($customer_info);
        $log->write($this->request->post["payment_option"] . 'PAYMENT OPTIONS');
        $log->write('Flutterwave Creds Customer Info');

        $log->write('Flutterwave Order Info');
        //$log->write($order_info);
        $log->write('Flutterwave Order Info');

        if (count($order_info) > 0) {
            $amount = (int) ($order_info['total']);
        }

        $curl = curl_init();

        $txref = "kwikbasket-" . $order_id . "-" . $customer_info['customer_id'] . "-" . time(); // ensure you generate unique references per transaction.
        $redirect_url = $this->url->link('payment/flutterwave/status', '', 'SSL');
        $customer_email = $customer_info['email'];
        $customer_phone = $customer_info['telephone'];
        $customer_name = $customer_info['firstname'] . " " . $customer_info['lastname'];
        $amount = $amount;
        $currency = "KES";
        $payment_opions = $this->request->post["payment_option"] == NULL ? "card" : $this->request->post["payment_option"];
        $customizations_title = 'Kwik Basket';
        $customizations_description = 'Kwik Basket Products';
        $customizations_logo = 'https://www.kwikbasket.com/front/ui/theme/metaorganic/assets_landing_page/img/logo.svg';
        $public_key = $flutter_creds['flutterwave_secret_key']; // get your public key from the dashboard.


        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.flutterwave.com/v3/payments",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                "tx_ref" => $txref,
                "redirect_url" => $this->url->link('payment/flutterwave/status', '', 'SSL'),
                "customer" => array("email" => $customer_email, "phone_number" => $customer_phone, "name" => $customer_name),
                "amount" => $amount,
                "customer_email" => $customer_email,
                "currency" => $currency,
                "payment_options" => $payment_opions,
                "customizations" => array("title" => $customizations_title, "description" => $customizations_description, "logo" => $customizations_logo)
            ]),
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer " . $public_key,
                "content-type: application/json",
                "cache-control: no-cache"
            ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if ($err) {
            // there was an error contacting the rave API
            die('Curl returned error: ' . $err);
        }

        $transaction = json_decode($response);
        $log->write($transaction);

        if (!$transaction->data && !$transaction->data->link) {
            // there was an error from the API
            print_r('API returned error: ' . $transaction->message);
        }

        // uncomment out this line if you want to redirect the user to the payment page
        //print_r($transaction->data->message);
        // redirect to page so User can pay
        // uncomment this line to allow the user redirect to the payment page
        //header('Location: ' . $transaction->data->link);
        $this->load->model('payment/flutterwave');
        $this->load->model('checkout/order');
        $this->model_payment_flutterwave->addOrder($order_info, $txref);
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($transaction));
    }

    public function complete() {

        $log = new Log('error.log');
        $json['processed'] = false;
        $json['status'] = false;

        $json['error'] = 'Transaction Failed. Please Try again.';

        if ($this->session->data['payment_method']['code'] == 'mpesa' || $this->request->post['payment_method'] == 'mpesa') {
            $this->load->language('payment/mpesa');

            $this->load->model('payment/mpesa');

            $this->load->model('checkout/order');

            foreach ($this->session->data['order_id'] as $key => $value) {
                $order_id = $value;
            }

            if (isset($this->request->post['order_id'])) {
                $order_id = $this->request->post['order_id'];
            }

            /* $mpesa= new \Safaricom\Mpesa\Mpesa('shiabWTekqy4Iod73mTmWJdD9VIhC3fl','TqNNiqllXfRqayxz','live','true');
              $BusinessShortCode = '705705';
              $LipaNaMpesaPasskey = '8007821ca4a18721c0518a67938c855cd7c552c782a298f5dfd280ef22ae3cf7';

              $checkoutRequestID = 'ws_CO_28032018142406660';
              $live = "true";
              $timestamp='20'.date(    "ymdhis");
              $password=base64_encode($BusinessShortCode.$LipaNaMpesaPasskey.$timestamp);
              $stkPushSimulation = $mpesa->STKPushQuery($live, $checkoutRequestID, $BusinessShortCode, $password, $timestamp);

              echo "<pre>";print_r($stkPushSimulation);die; */

            //$order_id = 2;
            $mpesaDetails = $this->model_payment_mpesa->getMpesaByOrderId($order_id);

            $live = true;

            $mpesa = new \Safaricom\Mpesa\Mpesa($this->config->get('mpesa_customer_key'), $this->config->get('mpesa_customer_secret'), $this->config->get('mpesa_environment'), $live);


            if ($mpesaDetails) {

                foreach ($mpesaDetails as $mpesaDetail) {


                    //echo "<pre>";print_r($mpesaDetail);die;


                    $BusinessShortCode = $this->config->get('mpesa_business_short_code');
                    $LipaNaMpesaPasskey = $this->config->get('mpesa_lipanampesapasskey');

                    $checkoutRequestID = $mpesaDetail['checkout_request_id']; //'ws_CO_28032018142406660';
                    $timestamp = '20' . date("ymdhis");
                    $password = base64_encode($BusinessShortCode . $LipaNaMpesaPasskey . $timestamp);


                    $stkPushSimulation = $mpesa->STKPushQuery($live, $checkoutRequestID, $BusinessShortCode, $password, $timestamp);

                    // Void the order first
                    $log->write('STKPushSimulation');
                    $log->write($stkPushSimulation);

                    $stkPushSimulation = json_decode($stkPushSimulation);

                    /* stdClass Object
                      (
                      [ResponseCode] => 0
                      [ResponseDescription] => The service request has been accepted successsfully
                      [MerchantRequestID] => 12365-2129383-1
                      [CheckoutRequestID] => ws_CO_28032018142406660
                      [ResultCode] => 0
                      [ResultDesc] => The service request is processed successfully.
                      )
                     */

                    if (isset($stkPushSimulation->ResultCode) && $stkPushSimulation->ResultCode == 0) {

                        //success pending to processing
                        $order_status_id = $this->config->get('mpesa_order_status_id');

                        $log->write('updateMpesaOrderStatus validatex');

                        $this->load->model('localisation/order_status');

                        $order_status = $this->model_localisation_order_status->getOrderStatuses();

                        $dataAddHisory['order_id'] = $order_id;
                        $dataAddHisory['order_status_id'] = $order_status_id;
                        $dataAddHisory['notify'] = 0;
                        $dataAddHisory['append'] = 0;
                        $dataAddHisory['comment'] = '';

                        $url = HTTPS_SERVER;
                        $api = 'api/order/addHistory';

                        if (isset($api)) {

                            $url_data = array();
                            $log->write("if");
                            foreach ($dataAddHisory as $key => $value) {
                                if ($key != 'path' && $key != 'token' && $key != 'store_id') {
                                    $url_data[$key] = $value;
                                }
                            }

                            $curl = curl_init();

                            // Set SSL if required
                            if (substr($url, 0, 5) == 'https') {
                                curl_setopt($curl, CURLOPT_PORT, 443);
                            }

                            curl_setopt($curl, CURLOPT_HEADER, false);
                            curl_setopt($curl, CURLINFO_HEADER_OUT, true);
                            curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
                            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                            curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($curl, CURLOPT_URL, $url . 'index.php?path=' . $api . ($url_data ? '&' . http_build_query($url_data) : ''));

                            $resp = curl_exec($curl);
                            $log->write("resp");
                            $log->write($url . 'index.php?path=' . $api . ($url_data ? '&' . http_build_query($url_data) : ''));


                            $log->write($resp);
                            curl_close($curl);

                            $json['status'] = true;

                            break;
                        }
                    }
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function checkMpesaStatus($order_id, $mpesa) {

        $log = new Log('error.log');
        $json['processed'] = false;
        $status = false;

        $json['error'] = 'Transaction Failed. Please Try again.';

        if (true) {
            $this->load->language('payment/mpesa');

            $this->load->model('payment/mpesa');

            $this->load->model('checkout/order');

            /* foreach ($this->session->data['order_id'] as $key => $value) {
              $order_id = $value;
              } */


            $mpesaDetails = $this->model_payment_mpesa->getMpesaByOrderId($order_id);

            $live = "true";

            //$mpesa= new \Safaricom\Mpesa\Mpesa($this->config->get('mpesa_customer_key'),$this->config->get('mpesa_customer_secret'),$this->config->get('mpesa_environment'),$live);


            if ($mpesaDetails) {

                foreach ($mpesaDetails as $mpesaDetail) {


                    //echo "<pre>";print_r($mpesaDetail);die;


                    $BusinessShortCode = $this->config->get('mpesa_business_short_code');
                    $LipaNaMpesaPasskey = $this->config->get('mpesa_lipanampesapasskey');

                    $checkoutRequestID = $mpesaDetail['checkout_request_id']; //'ws_CO_28032018142406660';
                    $timestamp = '20' . date("ymdhis");
                    $password = base64_encode($BusinessShortCode . $LipaNaMpesaPasskey . $timestamp);


                    $log->write($live . "xx" . $checkoutRequestID . "xx" . $BusinessShortCode . "xx" . $password . "xx" . $timestamp);

                    $stkPushSimulation = $mpesa->STKPushQuery($live, $checkoutRequestID, $BusinessShortCode, $password, $timestamp);

                    // Void the order first
                    $log->write('STKPushSimulation');
                    $log->write($stkPushSimulation);

                    $stkPushSimulation = json_decode($stkPushSimulation);

                    if (isset($stkPushSimulation->ResultCode) && $stkPushSimulation->ResultCode == 1001) {

                        //success pending to processing


                        $status = true;

                        break;
                    }
                }
            }
        }

        return $status;
    }

    public function apiConfirm($data) {

        $amount = $data['amount'];
        $mpesa_refrence_id = $data['mpesa_refrence_id'];
        $number = $data['mpesa_phonenumber'];
        $order_id = 0;

        $log = new Log('error.log');

        $log->write($data);

        $json['status'] = false;


        $this->load->language('payment/mpesa');

        $this->load->model('payment/mpesa');

        $this->load->model('checkout/order');

        $this->load->model('account/order');

        $order_details = $this->model_account_order->getOrderByReferenceIdApi($mpesa_refrence_id);

        /* if(isset($order_detail['order_id'])) {
          $order_id = $order_detail['order_id'];

          } */




        $json['message'] = sprintf($this->language->get('text_sms_sent'), $number);

        $json['confirm_button_text'] = $this->language->get('confirm_button_text');
        $json['timer_value'] = $this->language->get('timer_value');
        $json['back_button'] = $this->language->get('back_button');
        $json['extra_message'] = $this->language->get('extra_message');


        $mpesa = new \Safaricom\Mpesa\Mpesa($this->config->get('mpesa_customer_key'), $this->config->get('mpesa_customer_secret'), $this->config->get('mpesa_environment'));

        $PartyA = $this->config->get('config_telephone_code') . "" . $number;

        $BusinessShortCode = $this->config->get('mpesa_business_short_code');
        $LipaNaMpesaPasskey = $this->config->get('mpesa_lipanampesapasskey');
        $TransactionType = 'CustomerBuyGoodsOnline';
        $CallBackURL = $this->url->link('deliversystem/deliversystem/updateMpesaOrderStatus', '', 'SSL');
        //$CallBackURL = 'cer';

        $Amount = $amount;

        //$PartyB = '174379';
        $PartyB = $this->config->get('mpesa_business_short_code');

        //$PhoneNumber = '254708374149';
        $PhoneNumber = $this->config->get('config_telephone_code') . "" . $number;
        $AccountReference = 'GPK'; //$this->config->get('config_name');
        $TransactionDesc = '#' . $mpesa_refrence_id;
        $Remarks = 'PAYMENT';

        $log->write($BusinessShortCode . "x" . $LipaNaMpesaPasskey . "x" . $TransactionType . "amount" . $Amount . "x" . $PartyA . "x" . $PartyB . "x" . $PhoneNumber . "x" . $CallBackURL . "x" . $AccountReference . "x" . $TransactionDesc . "x" . $Remarks);

        $stkPushSimulation = $mpesa->STKPushSimulation($BusinessShortCode, $LipaNaMpesaPasskey, $TransactionType, $Amount, $PartyA, $PartyB, $PhoneNumber, $CallBackURL, $AccountReference, $TransactionDesc, $Remarks);


        // Void the order first
        $log->write('STKPushSimulation');
        $log->write($stkPushSimulation);


        $stkPushSimulation = json_decode($stkPushSimulation);

        $json['response'] = $stkPushSimulation;

        if (isset($stkPushSimulation->ResponseCode) && $stkPushSimulation->ResponseCode == 0) {

            //save in 

            $log->write($mpesa_refrence_id . "xwe" . $stkPushSimulation->MerchantRequestID . "xwe" . $stkPushSimulation->CheckoutRequestID);

            foreach ($order_details as $order_detail) {

                $order_id = $order_detail['order_id'];

                $this->model_payment_mpesa->addOrderApi($mpesa_refrence_id, $stkPushSimulation->MerchantRequestID, $stkPushSimulation->CheckoutRequestID, $order_id);
            }



            $json['status'] = true;
        } else {

            //failing orders from api
            if (isset($json['response']->errorMessage)) {
                $json['response']->errorMessage = "Above number is not a registered mPesa number";
            }
        }

        /* end */


        return $json;
    }

    public function apiDSConfirm($data) {

        $orders = $data['orders'];
        $number = $data['mpesa_phonenumber'];


        $log = new Log('error.log');

        $log->write($data);

        $json['status'] = false;


        $this->load->language('payment/mpesa');
        $this->load->model('sale/order');
        $this->load->model('payment/mpesa');

        $this->load->model('checkout/order');

        $json['message'] = sprintf($this->language->get('text_sms_sent'), $number);

        /* start */

        foreach ($orders as $order_id) {
            $order_id = $order_id;
        }



        if (isset($order_id)) {

            $totals = $this->model_sale_order->getOrderTotals($order_id);

            //echo "<pre>";print_r($totals);die;
            foreach ($totals as $total) {
                if ($total['code'] == 'total') {
                    $amount = (int) $total['value'];
                }
            }

            /* $order_info = $this->model_checkout_order->getOrder($order_id); 
              if(count($order_info) > 0) {
              $amount = (int)($order_info['total']);
              } */
        }

        $mpesa = new \Safaricom\Mpesa\Mpesa($this->config->get('mpesa_customer_key'), $this->config->get('mpesa_customer_secret'), $this->config->get('mpesa_environment'));

        $PartyA = $this->config->get('config_telephone_code') . "" . $number;

        $BusinessShortCode = $this->config->get('mpesa_business_short_code');
        $LipaNaMpesaPasskey = $this->config->get('mpesa_lipanampesapasskey');
        $TransactionType = 'CustomerBuyGoodsOnline';
        $CallBackURL = $this->url->link('deliversystem/deliversystem/mpesaOrderStatus', '', 'SSL');
        //$CallBackURL = 'cer';

        $Amount = $amount;

        //$PartyB = '174379';
        $PartyB = $this->config->get('mpesa_business_short_code');

        //$PhoneNumber = '254708374149';
        $PhoneNumber = $this->config->get('config_telephone_code') . "" . $number;
        $AccountReference = 'GPK'; //$this->config->get('config_name');
        $TransactionDesc = '#' . $order_id;
        $Remarks = 'PAYMENT';

        $log->write($BusinessShortCode . "x" . $LipaNaMpesaPasskey . "x" . $TransactionType . "amount" . $Amount . "x" . $PartyA . "x" . $PartyB . "x" . $PhoneNumber . "x" . $CallBackURL . "x" . $AccountReference . "x" . $TransactionDesc . "x" . $Remarks);

        $stkPushSimulation = $mpesa->STKPushSimulation($BusinessShortCode, $LipaNaMpesaPasskey, $TransactionType, $Amount, $PartyA, $PartyB, $PhoneNumber, $CallBackURL, $AccountReference, $TransactionDesc, $Remarks);


        // Void the order first
        $log->write('STKPushSimulation');
        $log->write($stkPushSimulation);


        $stkPushSimulation = json_decode($stkPushSimulation);

        $json['response'] = $stkPushSimulation;

        if (isset($stkPushSimulation->ResponseCode) && $stkPushSimulation->ResponseCode == 0) {

            //save in 

            $sen['order_id'] = $order_id;

            $this->model_payment_mpesa->addOrder($sen, $stkPushSimulation->MerchantRequestID, $stkPushSimulation->CheckoutRequestID);

            $json['status'] = true;
        } else {

            //failing orders from api
        }

        /* end */


        return $json;
    }

    public function apiComplete($data) {

        $order_id = $data['mpesa_refrence_id'];

        $log = new Log('error.log');
        $json['status'] = false;
        //$json['status'] = true;

        $json['error'] = 'Transaction Failed. Please Try again.';

        if (true) {
            $this->load->language('payment/mpesa');

            $this->load->model('payment/mpesa');

            $this->load->model('checkout/order');

            /* foreach ($orders as $order_id) {
              $order_id = $order_id;
              } */

            $mpesaDetails = $this->model_payment_mpesa->getMpesaByOrderIdApi($order_id);

            $live = true;

            $mpesa = new \Safaricom\Mpesa\Mpesa($this->config->get('mpesa_customer_key'), $this->config->get('mpesa_customer_secret'), $this->config->get('mpesa_environment'), $live);

            if ($mpesaDetails) {

                foreach ($mpesaDetails as $mpesaDetail) {

                    //echo "<pre>";print_r($mpesaDetails);die;


                    $BusinessShortCode = $this->config->get('mpesa_business_short_code');
                    $LipaNaMpesaPasskey = $this->config->get('mpesa_lipanampesapasskey');

                    $checkoutRequestID = $mpesaDetail['checkout_request_id']; //'ws_CO_28032018142406660';

                    $order_id = $mpesaDetail['order_id'];
                    $timestamp = '20' . date("ymdhis");
                    $password = base64_encode($BusinessShortCode . $LipaNaMpesaPasskey . $timestamp);


                    $stkPushSimulation = $mpesa->STKPushQuery($live, $checkoutRequestID, $BusinessShortCode, $password, $timestamp);

                    // Void the order first
                    $log->write('STKPushSimulation');
                    $log->write($stkPushSimulation);

                    $stkPushSimulation = json_decode($stkPushSimulation);


                    if (isset($stkPushSimulation->ResultCode) && $stkPushSimulation->ResultCode == 0 && $order_id) {
                        //if(true && $order_id) {
                        //success pending to processing
                        $order_status_id = $this->config->get('mpesa_order_status_id');

                        $log->write('updateMpesaOrderStatus validatex');

                        $this->load->model('localisation/order_status');

                        $order_status = $this->model_localisation_order_status->getOrderStatuses();

                        $dataAddHisory['order_id'] = $order_id;
                        $dataAddHisory['order_status_id'] = $order_status_id;
                        $dataAddHisory['notify'] = 0;
                        $dataAddHisory['append'] = 0;
                        $dataAddHisory['comment'] = '';

                        $url = HTTPS_SERVER;
                        $api = 'api/order/addHistory';

                        if (isset($api)) {

                            $url_data = array();
                            $log->write("if");
                            foreach ($dataAddHisory as $key => $value) {
                                if ($key != 'path' && $key != 'token' && $key != 'store_id') {
                                    $url_data[$key] = $value;
                                }
                            }

                            $curl = curl_init();

                            // Set SSL if required
                            if (substr($url, 0, 5) == 'https') {
                                curl_setopt($curl, CURLOPT_PORT, 443);
                            }

                            curl_setopt($curl, CURLOPT_HEADER, false);
                            curl_setopt($curl, CURLINFO_HEADER_OUT, true);
                            curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
                            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                            curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($curl, CURLOPT_URL, $url . 'index.php?path=' . $api . ($url_data ? '&' . http_build_query($url_data) : ''));

                            $resp = curl_exec($curl);
                            $log->write("resp");
                            $log->write($url . 'index.php?path=' . $api . ($url_data ? '&' . http_build_query($url_data) : ''));


                            $log->write($resp);
                            curl_close($curl);

                            $json['status'] = true;

                            //break;
                        }
                    }
                }
            }
        }
        return $json;
    }

    public function apiDSComplete($data) {



        $orders = $data['orders'];

        $log = new Log('error.log');
        $json['status'] = false;

        $json['error'] = 'Transaction Failed. Please Try again.';

        if (true) {
            $this->load->language('payment/mpesa');

            $this->load->model('payment/mpesa');

            $this->load->model('checkout/order');

            foreach ($orders as $order_id) {
                $order_id = $order_id;
            }

            $log->write('getMpesaByOrderIdApi');
            $log->write($order_id);

            $mpesaDetails = $this->model_payment_mpesa->getMpesaByOrderId($order_id);

            $log->write($mpesaDetails);

            $live = true;

            $mpesa = new \Safaricom\Mpesa\Mpesa($this->config->get('mpesa_customer_key'), $this->config->get('mpesa_customer_secret'), $this->config->get('mpesa_environment'), $live);

            $log->write($mpesa);
            if ($mpesaDetails) {

                foreach ($mpesaDetails as $mpesaDetail) {


                    $BusinessShortCode = $this->config->get('mpesa_business_short_code');
                    $LipaNaMpesaPasskey = $this->config->get('mpesa_lipanampesapasskey');

                    $checkoutRequestID = $mpesaDetail['checkout_request_id']; //'ws_CO_28032018142406660';
                    $timestamp = '20' . date("ymdhis");
                    $password = base64_encode($BusinessShortCode . $LipaNaMpesaPasskey . $timestamp);


                    $stkPushSimulation = $mpesa->STKPushQuery($live, $checkoutRequestID, $BusinessShortCode, $password, $timestamp);

                    // Void the order first
                    $log->write('STKPushSimulation');
                    $log->write($stkPushSimulation);

                    $stkPushSimulation = json_decode($stkPushSimulation);

                    $status_update = true;
                    $order_info = $this->model_checkout_order->getOrder($order_id);
                    if (count($order_info) > 0) {
                        $status_update = ($order_info['payment_code'] == 'mod') ? false : true;
                    }

                    if (isset($stkPushSimulation->ResultCode) && $stkPushSimulation->ResultCode == 0) {

                        //success pending to processing

                        if ($status_update) {
                            $order_status_id = $this->config->get('mpesa_order_status_id');

                            $log->write('updateMpesaOrderStatus validatex');

                            $this->load->model('localisation/order_status');

                            $order_status = $this->model_localisation_order_status->getOrderStatuses();

                            $dataAddHisory['order_id'] = $order_id;
                            $dataAddHisory['order_status_id'] = $order_status_id;
                            $dataAddHisory['notify'] = 0;
                            $dataAddHisory['append'] = 0;
                            $dataAddHisory['comment'] = '';

                            $url = HTTPS_SERVER;
                            $api = 'api/order/addHistory';

                            if (isset($api)) {

                                $url_data = array();
                                $log->write("if");
                                foreach ($dataAddHisory as $key => $value) {
                                    if ($key != 'path' && $key != 'token' && $key != 'store_id') {
                                        $url_data[$key] = $value;
                                    }
                                }

                                $curl = curl_init();

                                // Set SSL if required
                                if (substr($url, 0, 5) == 'https') {
                                    curl_setopt($curl, CURLOPT_PORT, 443);
                                }

                                curl_setopt($curl, CURLOPT_HEADER, false);
                                curl_setopt($curl, CURLINFO_HEADER_OUT, true);
                                //curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
                                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                                curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($curl, CURLOPT_URL, $url . 'index.php?path=' . $api . ($url_data ? '&' . http_build_query($url_data) : ''));

                                $resp = curl_exec($curl);
                                $log->write("resp");
                                $log->write($url . 'index.php?path=' . $api . ($url_data ? '&' . http_build_query($url_data) : ''));


                                $log->write($resp);
                                curl_close($curl);
                            }
                        }

                        $json['status'] = true;
                        break;
                    }
                }
            }
        }
        return $json;
    }

    public function cancelled() {
        echo "Payment Cancelled";
        exit;
    }

    public function status() {

        /* echo "status : " . $this->request->get['status'];
          echo "tx_ref : " . $this->request->get['tx_ref'];
          echo "transaction_id : " . $this->request->get['transaction_id'];
          echo "Payment Status Checking"; */

        //if ($this->session->data['payment_method']['code'] == 'flutterwave' || $this->request->post['payment_method'] == 'flutterwave') {

        $this->load->language('payment/flutterwave');
        $this->load->model('payment/flutterwave');
        $this->load->model('payment/flutterwavetransactions');
        $this->load->model('checkout/order');
        $this->load->model('setting/setting');

        foreach ($this->session->data['order_id'] as $key => $value) {
            $order_id = $value;
        }

        if (isset($this->request->post['order_id'])) {
            $order_id = $this->request->post['order_id'];
        }
        $flutter_creds = $this->model_setting_setting->getSetting('flutterwave', 0);

        $curl = curl_init();

        $public_key = $flutter_creds['flutterwave_secret_key']; // get your public key from the dashboard.
        $transaction_id = $this->request->get['transaction_id'];

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.flutterwave.com/v3/transactions/" . $transaction_id . "/verify",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer " . $public_key,
                "content-type: application/json",
                "cache-control: no-cache"
            ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if ($err) {
            // there was an error contacting the rave API
            die('Curl returned error: ' . $err);
        }

        $transaction = json_decode($response, true);
        $this->model_payment_flutterwavetransactions->addOrderTransaction($transaction['data'], $order_id);
        $log = new Log('error.log');
        $log->write($transaction);
        exit;

        $flutterwaveDetails = $this->model_payment_flutterwave->getFlutterwaveByOrderId($order_id, $this->request->get['tx_ref']);
        if ($flutterwaveDetails != NULL) {
            $this->model_payment_flutterwave->updateFlutterwaveOrder($order_id, $this->request->get['tx_ref'], $this->request->get['transaction_id'], $this->request->get['status']);
        }
        //}
        exit;
    }

}
