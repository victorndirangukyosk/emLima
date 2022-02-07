<?php

require_once DIR_SYSTEM . '/vendor/pesapal/OAuth.php';

class ControllerPaymentPesapal extends Controller {

    public function index() {
        $this->load->language('payment/pesapal');
        $this->load->model('setting/setting');
        $this->load->model('payment/pesapal');
        $this->load->model('checkout/order');
        $this->load->model('account/customer');

        $order_ids = array();
        foreach ($this->session->data['order_id'] as $key => $value) {
            $order_ids[] = $value;
            $order_id = $value;
            if ($order_id != NULL) {
                $this->model_checkout_order->UpdateParentApproval($order_id);
            }
        }

        $amount = 0;
        foreach ($this->session->data['order_id'] as $key => $value) {
            /* FOR KWIKBASKET ORDERS */
            //if ($key == 75) {
            $order_id = $value;
            //}

            $log = new Log('error.log');
            $log->write('Pesapal Order ID');
            $log->write($this->session->data['order_id']);
            $log->write('Pesapal Order ID');
            $order_info = $this->model_checkout_order->getOrder($order_id);
            $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);
            $log->write('Pesapal Creds Customer Info');
            $log->write($customer_info);
            $log->write('Pesapal Creds Customer Info');

            $log->write('Pesapal Order Info');
            $log->write($order_info);
            $log->write('Pesapal Order Info');

            if (count($order_info) > 0) {
                $total_data = $this->totalData();
                $all_sub_total = 0;
                $all_total = 0;
                foreach ($total_data as $tots) {
                    foreach ($tots as $tot) {
                        if ($tot['title'] == 'Sub-Total') {
                            $all_sub_total = $tot['value'];
                        }

                        if ($tot['title'] == 'Total') {
                            $all_total += $tot['value'];
                        }
                    }
                }
                $amount = (int) ($all_total);
            }
        }

        $this->load->model('account/credit');
        $customer_wallet_total = $this->model_account_credit->getTotalAmount();
        if ($this->session->data['payment_wallet_method']['code'] == 'wallet' && $customer_wallet_total > 0) {
            $amount = $amount - $customer_wallet_total;
            $amount = abs($amount);
        }

        $data['text_instruction'] = $this->language->get('text_instruction');
        $data['text_payable'] = $this->language->get('text_payable');
        $data['text_address'] = $this->language->get('text_address');
        $data['text_payment'] = $this->language->get('text_payment');

        $data['button_confirm'] = $this->language->get('button_confirm');

        $data['payable'] = $this->config->get('pesapal_payable');
        $data['address'] = nl2br($this->config->get('config_address'));

        $data['continue'] = $this->url->link('checkout/success');

        $data['customer_number'] = $this->customer->getTelephone();

        $pesapal_creds = $this->model_setting_setting->getSetting('pesapal', 0);

        //pesapal params
        $token = $params = null;

        /*
          PesaPal Sandbox is at https://demo.pesapal.com. Use this to test your developement and
          when you are ready to go live change to https://www.pesapal.com.
         */
        $consumer_key = $pesapal_creds['pesapal_consumer_key']; //Register a merchant account on
        //demo.pesapal.com and use the merchant key for testing.
        //When you are ready to go live make sure you change the key to the live account
        //registered on www.pesapal.com!
        $consumer_secret = $pesapal_creds['pesapal_consumer_secret']; // Use the secret from your test
        //account on demo.pesapal.com. When you are ready to go live make sure you
        //change the secret to the live account registered on www.pesapal.com!
        $signature_method = new OAuthSignatureMethod_HMAC_SHA1();
        $iframelink = 'https://www.pesapal.com/api/PostPesapalDirectOrderV4'; //change to
        //https://www.pesapal.com/API/PostPesapalDirectOrderV4 when you are ready to go live!
        //get form details
        //$amount = $this->cart->getTotalForKwikBasket();
        $transaction_fee = 0;
        $percentage = 3.5;
        $transaction_fee = ($percentage / 100) * $amount;
        $amount = str_replace(',', '', $amount + $transaction_fee);
        $log->write('TRANSACTION FEE');
        $log->write($transaction_fee);
        $log->write($amount);
        //$amount = 100;
        $amount = number_format($amount, 2); //format amount to 2 decimal places

        $desc = $customer_info['company_name'] . '-' . $customer_info['firstname'] . '-' . $customer_info['lastname'] . '-' . $order_id;
        $type = 'MERCHANT'; //default value = MERCHANT
        $reference = $order_id . '-' . time() . '-' . $customer_info['customer_id']; //unique order id of the transaction, generated by merchant
        $first_name = $customer_info['firstname'];
        $last_name = $customer_info['lastname'];
        $email = $customer_info['email'];
        $phonenumber = '+254' . $customer_info['telephone']; //ONE of email or phonenumber is required
        $Currency = 'KES';

        $callback_url = $this->url->link('payment/pesapal/status', '', 'SSL'); //redirect url, the page that will handle the response from pesapal.

        $post_xml = '<?xml version="1.0" encoding="utf-8"?><PesapalDirectOrderInfo xmlns:xsi="http://www.w3.org/2001/XMLSchemainstance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" Amount="' . $amount . '" Description="' . $desc . '" Type="' . $type . '" Reference="' . $reference . '" FirstName="' . $first_name . '" LastName="' . $last_name . '" Email="' . $email . '" PhoneNumber="' . $phonenumber . '" xmlns="http://www.pesapal.com" />';
        $post_xml = htmlentities($post_xml);

        $consumer = new OAuthConsumer($consumer_key, $consumer_secret, $callback_url);
        //print_r($consumer);
        //post transaction to pesapal
        $iframe_src = OAuthRequest::from_consumer_and_token($consumer, $token, 'GET', $iframelink, $params);
        $iframe_src->set_parameter('oauth_callback', $callback_url);
        $iframe_src->set_parameter('pesapal_request_data', $post_xml);
        $iframe_src->sign_request($signature_method, $consumer, $token);
        //display pesapal - iframe and pass iframe_src
        $log->write($iframe_src);
        $data['iframe'] = $iframe_src;

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/pesapal.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/payment/pesapal.tpl', $data);
        } else {
            return $this->load->view('default/template/payment/pesapal.tpl', $data);
        }
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

        $PartyA = $this->config->get('config_telephone_code') . '' . $number;

        $BusinessShortCode = $this->config->get('mpesa_business_short_code');
        $LipaNaMpesaPasskey = $this->config->get('mpesa_lipanampesapasskey');
        $TransactionType = 'CustomerBuyGoodsOnline';
        $CallBackURL = $this->url->link('deliversystem/deliversystem/updateMpesaOrderStatus', '', 'SSL');
        //$CallBackURL = 'cer';

        $Amount = $amount;

        //$PartyB = '174379';
        $PartyB = $this->config->get('mpesa_business_short_code');

        //$PhoneNumber = '254708374149';
        $PhoneNumber = $this->config->get('config_telephone_code') . '' . $number;
        $AccountReference = 'GPK'; //$this->config->get('config_name');
        $TransactionDesc = '#' . $mpesa_refrence_id;
        $Remarks = 'PAYMENT';

        $log->write($BusinessShortCode . 'x' . $LipaNaMpesaPasskey . 'x' . $TransactionType . 'amount' . $Amount . 'x' . $PartyA . 'x' . $PartyB . 'x' . $PhoneNumber . 'x' . $CallBackURL . 'x' . $AccountReference . 'x' . $TransactionDesc . 'x' . $Remarks);

        $stkPushSimulation = $mpesa->STKPushSimulation($BusinessShortCode, $LipaNaMpesaPasskey, $TransactionType, $Amount, $PartyA, $PartyB, $PhoneNumber, $CallBackURL, $AccountReference, $TransactionDesc, $Remarks);

        // Void the order first
        $log->write('STKPushSimulation');
        $log->write($stkPushSimulation);

        $stkPushSimulation = json_decode($stkPushSimulation);

        $json['response'] = $stkPushSimulation;

        if (isset($stkPushSimulation->ResponseCode) && 0 == $stkPushSimulation->ResponseCode) {
            //save in

            $log->write($mpesa_refrence_id . 'xwe' . $stkPushSimulation->MerchantRequestID . 'xwe' . $stkPushSimulation->CheckoutRequestID);

            foreach ($order_details as $order_detail) {
                $order_id = $order_detail['order_id'];

                $this->model_payment_mpesa->addOrderApi($mpesa_refrence_id, $stkPushSimulation->MerchantRequestID, $stkPushSimulation->CheckoutRequestID, $order_id);
            }

            $json['status'] = true;
        } else {
            //failing orders from api
            if (isset($json['response']->errorMessage)) {
                $json['response']->errorMessage = 'Above number is not a registered mPesa number';
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
                if ('total' == $total['code']) {
                    $amount = (int) $total['value'];
                }
            }

            /* $order_info = $this->model_checkout_order->getOrder($order_id);
              if(count($order_info) > 0) {
              $amount = (int)($order_info['total']);
              } */
        }

        $mpesa = new \Safaricom\Mpesa\Mpesa($this->config->get('mpesa_customer_key'), $this->config->get('mpesa_customer_secret'), $this->config->get('mpesa_environment'));

        $PartyA = $this->config->get('config_telephone_code') . '' . $number;

        $BusinessShortCode = $this->config->get('mpesa_business_short_code');
        $LipaNaMpesaPasskey = $this->config->get('mpesa_lipanampesapasskey');
        $TransactionType = 'CustomerBuyGoodsOnline';
        $CallBackURL = $this->url->link('deliversystem/deliversystem/mpesaOrderStatus', '', 'SSL');
        //$CallBackURL = 'cer';

        $Amount = $amount;

        //$PartyB = '174379';
        $PartyB = $this->config->get('mpesa_business_short_code');

        //$PhoneNumber = '254708374149';
        $PhoneNumber = $this->config->get('config_telephone_code') . '' . $number;
        $AccountReference = 'GPK'; //$this->config->get('config_name');
        $TransactionDesc = '#' . $order_id;
        $Remarks = 'PAYMENT';

        $log->write($BusinessShortCode . 'x' . $LipaNaMpesaPasskey . 'x' . $TransactionType . 'amount' . $Amount . 'x' . $PartyA . 'x' . $PartyB . 'x' . $PhoneNumber . 'x' . $CallBackURL . 'x' . $AccountReference . 'x' . $TransactionDesc . 'x' . $Remarks);

        $stkPushSimulation = $mpesa->STKPushSimulation($BusinessShortCode, $LipaNaMpesaPasskey, $TransactionType, $Amount, $PartyA, $PartyB, $PhoneNumber, $CallBackURL, $AccountReference, $TransactionDesc, $Remarks);

        // Void the order first
        $log->write('STKPushSimulation');
        $log->write($stkPushSimulation);

        $stkPushSimulation = json_decode($stkPushSimulation);

        $json['response'] = $stkPushSimulation;

        if (isset($stkPushSimulation->ResponseCode) && 0 == $stkPushSimulation->ResponseCode) {
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
                    $timestamp = '20' . date('ymdhis');
                    $password = base64_encode($BusinessShortCode . $LipaNaMpesaPasskey . $timestamp);

                    $stkPushSimulation = $mpesa->STKPushQuery($live, $checkoutRequestID, $BusinessShortCode, $password, $timestamp);

                    // Void the order first
                    $log->write('STKPushSimulation');
                    $log->write($stkPushSimulation);

                    $stkPushSimulation = json_decode($stkPushSimulation);

                    if (isset($stkPushSimulation->ResultCode) && 0 == $stkPushSimulation->ResultCode && $order_id) {
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
                            $url_data = [];
                            $log->write('if');
                            foreach ($dataAddHisory as $key => $value) {
                                if ('path' != $key && 'token' != $key && 'store_id' != $key) {
                                    $url_data[$key] = $value;
                                }
                            }

                            $curl = curl_init();

                            // Set SSL if required
                            if ('https' == substr($url, 0, 5)) {
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
                            $log->write('resp');
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
                    $timestamp = '20' . date('ymdhis');
                    $password = base64_encode($BusinessShortCode . $LipaNaMpesaPasskey . $timestamp);

                    $stkPushSimulation = $mpesa->STKPushQuery($live, $checkoutRequestID, $BusinessShortCode, $password, $timestamp);

                    // Void the order first
                    $log->write('STKPushSimulation');
                    $log->write($stkPushSimulation);

                    $stkPushSimulation = json_decode($stkPushSimulation);

                    $status_update = true;
                    $order_info = $this->model_checkout_order->getOrder($order_id);
                    if (count($order_info) > 0) {
                        $status_update = ('mod' == $order_info['payment_code']) ? false : true;
                    }

                    if (isset($stkPushSimulation->ResultCode) && 0 == $stkPushSimulation->ResultCode) {
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
                                $url_data = [];
                                $log->write('if');
                                foreach ($dataAddHisory as $key => $value) {
                                    if ('path' != $key && 'token' != $key && 'store_id' != $key) {
                                        $url_data[$key] = $value;
                                    }
                                }

                                $curl = curl_init();

                                // Set SSL if required
                                if ('https' == substr($url, 0, 5)) {
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
                                $log->write('resp');
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

    public function status() {
        $log = new Log('error.log');

        $this->load->language('payment/pesapal');
        $this->load->model('setting/setting');
        $this->load->model('payment/pesapal');
        $this->load->model('checkout/order');
        $this->load->model('account/customer');

        foreach ($this->session->data['order_id'] as $key => $value) {
            /* FOR KWIKBASKET ORDERS */
            //if ($key == 75) {
            $order_id = $value;

            $log->write('Pesapal Order ID');
            $log->write($this->session->data['order_id']);
            $log->write('Pesapal Order ID');
            $order_info = $this->model_checkout_order->getOrder($order_id);
            $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);
            $log->write('Pesapal Creds Customer Info');
            $log->write($customer_info);
            $log->write('Pesapal Creds Customer Info');

            $log->write('Pesapal Order Info');
            $log->write($order_info);
            $log->write('Pesapal Order Info');

            if (count($order_info) > 0) {
                $amount = (int) ($order_info['total']);
            }

            $log->write('PESAPAL CALL BACK');
            $transaction_tracking_id = $this->request->get['pesapal_transaction_tracking_id'];
            $merchant_reference = $this->request->get['pesapal_merchant_reference'];
            $log->write($transaction_tracking_id);
            $log->write($merchant_reference);
            $log->write('PESAPAL CALL BACK');
            $customer_id = $customer_info['customer_id'];
            $this->model_payment_pesapal->insertOrderTransactionIdPesapal($order_id, $transaction_tracking_id, $merchant_reference, $customer_id);
            $this->model_payment_pesapal->OrderTransaction($order_id, $transaction_tracking_id);
            $status = $this->ipinlistenercustom('CHANGE', $transaction_tracking_id, $merchant_reference, $order_id);
            //}
        }

        if ('COMPLETED' == $status) {
            //$this->load->controller('payment/cod/confirmnonkb');
            $this->response->redirect($this->url->link('checkout/success'));
        }

        if ('COMPLETED' != $status || null == $status) {
            $this->load->controller('payment/cod/confirmnonkb');
            $this->response->redirect($this->url->link('checkout/success/orderfailed'));
        }
    }

    public function ipinlistenercustom($pesapalNotification, $pesapalTrackingId, $pesapal_merchant_reference, $order_id) {
        $status = null;
        $log = new Log('error.log');
        $log->write('ipinlistener');
        $this->load->model('setting/setting');
        $this->load->model('payment/pesapal');
        $this->load->model('checkout/order');
        $this->load->model('account/customer');
        $pesapal_creds = $this->model_setting_setting->getSetting('pesapal', 0);
        $order_info = $this->model_checkout_order->getOrder($order_id);
        $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);
        $customer_id = $customer_info['customer_id'];

        $consumer_key = $pesapal_creds['pesapal_consumer_key']; //Register a merchant account on
        //demo.pesapal.com and use the merchant key for testing.
        //When you are ready to go live make sure you change the key to the live account
        //registered on www.pesapal.com!
        $consumer_secret = $pesapal_creds['pesapal_consumer_secret']; // Use the secret from your test
        //account on demo.pesapal.com. When you are ready to go live make sure you
        //change the secret to the live account registered on www.pesapal.com!
        $statusrequestAPI = 'https://www.pesapal.com/api/querypaymentstatus';
        //'https://demo.pesapal.com/api/querypaymentstatus'; //change to
        //https://www.pesapal.com/api/querypaymentstatus' when you are ready to go live!
        // Parameters sent to you by PesaPal IPN
        $pesapalNotification = $pesapalNotification;
        $pesapalTrackingId = $pesapalTrackingId;
        $pesapal_merchant_reference = $pesapal_merchant_reference;

        /* $pesapalNotification = $this->request->get['pesapal_notification_type'];
          $pesapalTrackingId = $this->request->get['pesapal_transaction_tracking_id'];
          $pesapal_merchant_reference = $this->request->get['pesapal_merchant_reference']; */

        if ('CHANGE' == $pesapalNotification && '' != $pesapalTrackingId) {
            $log->write('ipinlistener');
            $token = $params = null;
            $consumer = new OAuthConsumer($consumer_key, $consumer_secret);
            $signature_method = new OAuthSignatureMethod_HMAC_SHA1();

            //get transaction status
            $request_status = OAuthRequest::from_consumer_and_token($consumer, $token, 'GET', $statusrequestAPI, $params);
            $request_status->set_parameter('pesapal_merchant_reference', $pesapal_merchant_reference);
            $request_status->set_parameter('pesapal_transaction_tracking_id', $pesapalTrackingId);
            $request_status->sign_request($signature_method, $consumer, $token);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $request_status);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            if (defined('CURL_PROXY_REQUIRED')) {
                if (CURL_PROXY_REQUIRED == 'True') {
                    $proxy_tunnel_flag = (defined('CURL_PROXY_TUNNEL_FLAG') && 'FALSE' == strtoupper(CURL_PROXY_TUNNEL_FLAG)) ? false : true;
                    curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, $proxy_tunnel_flag);
                    curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
                    curl_setopt($ch, CURLOPT_PROXY, CURL_PROXY_SERVER_DETAILS);
                }
            }

            $response = curl_exec($ch);
            $log->write($response);

            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $raw_header = substr($response, 0, $header_size - 4);
            $headerArray = explode("\r\n\r\n", $raw_header);
            $header = $headerArray[count($headerArray) - 1];

            //transaction status
            $elements = preg_split('/=/', substr($response, $header_size));
            $status = $elements[1];
            $log->write('ORDER STATUS');
            $log->write($status);
            $log->write('ORDER STATUS');
            curl_close($ch);

            $order_info = $this->model_checkout_order->getOrder($order_id);
            if ($response != null && $status != null && $status == 'FAILED') {
                $this->model_payment_pesapal->addOrderHistoryFailed($order_id, $this->config->get('pesapal_failed_order_status_id'), $customer_info['customer_id'], 'customer', $order_info['paid']);
                $this->model_payment_pesapal->updateorderstatusipn($order_id, $pesapalTrackingId, $pesapal_merchant_reference, $customer_id, $status);
            } elseif ($response != null && $status != null && $status == 'PENDING') {
                $this->model_payment_pesapal->addOrderHistoryFailed($order_id, $this->config->get('pesapal_pending_order_status_id'), $customer_info['customer_id'], 'customer', $order_info['paid']);
                $this->model_payment_pesapal->updateorderstatusipn($order_id, $pesapalTrackingId, $pesapal_merchant_reference, $customer_id, $status);
            } elseif ($response != null && $status != null && $status == 'COMPLETED') {

                /* WALLET */
                $this->load->model('account/credit');
                $this->load->model('sale/order');
                $this->load->model('payment/wallet');

                $customer_wallet_total = $this->model_account_credit->getTotalAmount();
                if ($this->session->data['payment_wallet_method']['code'] == 'wallet' && $customer_wallet_total > 0 && $order_info['paid'] == 'N') {
                    $log->write($this->session->data['payment_wallet_method']);
                    $totals = $this->model_sale_order->getOrderTotals($order_id);
                    $log->write($totals);
                    $total = 0;
                    foreach ($totals as $total) {
                        if ('total' == $total['code']) {
                            $total = $total['value'];
                            break;
                        }
                    }
                    if ($customer_wallet_total > 0 && $totals != NULL && $total > 0 && $total <= $customer_wallet_total) {
                        $this->model_payment_wallet->addTransactionCreditForHybridPayment($this->customer->getId(), "Wallet amount deducted #" . $order_id, $total, $order_id, 'Y', 0);
                        $ret = $this->model_checkout_order->addOrderHistory($order_id, 1, 'Paid Through Wallet By Customer', FALSE, $this->customer->getId(), 'customer');
                    } elseif ($customer_wallet_total > 0 && $totals != NULL && $total > 0 && $total > $customer_wallet_total) {
                        $this->model_payment_wallet->addTransactionCreditForHybridPayment($this->customer->getId(), "Wallet amount deducted #" . $order_id, $customer_wallet_total, $order_id, 'P', $customer_wallet_total);
                        $ret = $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('mod_order_status_id'), 'Paid Partially Through Wallet By Customer', FALSE, $this->customer->getId(), 'customer');

                        $this->model_payment_pesapal->addOrderHistory($order_id, $this->config->get('pesapal_order_status_id'), $customer_info['customer_id'], 'customer');
                        $this->model_payment_pesapal->updateorderstatusipn($order_id, $pesapalTrackingId, $pesapal_merchant_reference, $customer_id, $status);
                    }
                }
                /* WALLET */ elseif ((!isset($this->session->data['payment_wallet_method']['code']) || $this->session->data['payment_wallet_method']['code'] == 0 || ($customer_wallet_total <= 0 && $this->session->data['payment_wallet_method']['code'] == 'wallet')) && $order_info['paid'] == 'N') {
                    $this->model_payment_pesapal->addOrderHistory($order_id, $this->config->get('pesapal_order_status_id'), $customer_info['customer_id'], 'customer');
                    $this->model_payment_pesapal->updateorderstatusipn($order_id, $pesapalTrackingId, $pesapal_merchant_reference, $customer_id, $status);
                }
            } else {
                $this->model_payment_pesapal->addOrderHistory($order_id, $this->config->get('pesapal_pending_order_status_id'), $customer_info['customer_id'], 'customer');
                $this->model_payment_pesapal->updateorderstatusipn($order_id, $pesapalTrackingId, $pesapal_merchant_reference, $customer_id, $status);
            }
        }
        echo $status;
    }

    public function ipinlistener() {
        $status = null;
        $order_id = null;
        $log = new Log('error.log');
        $log->write('ipinlistener');
        $this->load->model('setting/setting');
        $this->load->model('payment/pesapal');
        $this->load->model('checkout/order');
        $this->load->model('account/customer');

        $pesapalNotification = $this->request->get['pesapal_notification_type'];
        $pesapalTrackingId = $this->request->get['pesapal_transaction_tracking_id'];
        $pesapal_merchant_reference = $this->request->get['pesapal_merchant_reference'];

        if (strpos($pesapal_merchant_reference, 'KBCUST') !== false) {
            if (null != $pesapal_merchant_reference) {
                $order_details = explode('-', $pesapal_merchant_reference);
                $order_id = str_replace("KBCUST", "", $order_details[0]);
            }
            $log->write('ipinlistener');
            $log->write($order_id);
            $log->write('ipinlistener');

            $pesapal_creds = $this->model_setting_setting->getSetting('pesapal', 0);
            $customer_info = $this->model_account_customer->getCustomer($order_id);
            $customer_id = $customer_info['customer_id'];

            $consumer_key = $pesapal_creds['pesapal_consumer_key']; //Register a merchant account on
            //demo.pesapal.com and use the merchant key for testing.
            //When you are ready to go live make sure you change the key to the live account
            //registered on www.pesapal.com!
            $consumer_secret = $pesapal_creds['pesapal_consumer_secret']; // Use the secret from your test
            //account on demo.pesapal.com. When you are ready to go live make sure you
            //change the secret to the live account registered on www.pesapal.com!
            $statusrequestAPI = 'https://www.pesapal.com/api/querypaymentstatus';
            //'https://demo.pesapal.com/api/querypaymentstatus'; //change to
            //https://www.pesapal.com/api/querypaymentstatus' when you are ready to go live!
            // Parameters sent to you by PesaPal IPN
            /* $pesapalNotification = $pesapalNotification;
              $pesapalTrackingId = $pesapalTrackingId;
              $pesapal_merchant_reference = $pesapal_merchant_reference; */

            if ('CHANGE' == $pesapalNotification && '' != $pesapalTrackingId) {
                $log->write('ipinlistener');
                $token = $params = null;
                $consumer = new OAuthConsumer($consumer_key, $consumer_secret);
                $signature_method = new OAuthSignatureMethod_HMAC_SHA1();

                //get transaction status
                $request_status = OAuthRequest::from_consumer_and_token($consumer, $token, 'GET', $statusrequestAPI, $params);
                $request_status->set_parameter('pesapal_merchant_reference', $pesapal_merchant_reference);
                $request_status->set_parameter('pesapal_transaction_tracking_id', $pesapalTrackingId);
                $request_status->sign_request($signature_method, $consumer, $token);

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $request_status);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HEADER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                if (defined('CURL_PROXY_REQUIRED')) {
                    if (CURL_PROXY_REQUIRED == 'True') {
                        $proxy_tunnel_flag = (defined('CURL_PROXY_TUNNEL_FLAG') && 'FALSE' == strtoupper(CURL_PROXY_TUNNEL_FLAG)) ? false : true;
                        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, $proxy_tunnel_flag);
                        curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
                        curl_setopt($ch, CURLOPT_PROXY, CURL_PROXY_SERVER_DETAILS);
                    }
                }

                $response = curl_exec($ch);
                $log->write($response);

                $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
                $raw_header = substr($response, 0, $header_size - 4);
                $headerArray = explode("\r\n\r\n", $raw_header);
                $header = $headerArray[count($headerArray) - 1];

                //transaction status
                $elements = preg_split('/=/', substr($response, $header_size));
                $status = $elements[1];
                $log->write('ORDER STATUS');
                $log->write($status);
                $log->write('ORDER STATUS');
                curl_close($ch);

                if ($response != null && $status != null && $status == 'FAILED') {
                    $this->model_payment_pesapal->updateorderstatusipnOther($order_id, $pesapalTrackingId, $pesapal_merchant_reference, $customer_id, $status);
                } elseif ($response != null && $status != null && $status == 'PENDING') {
                    $this->model_payment_pesapal->updateorderstatusipnOther($order_id, $pesapalTrackingId, $pesapal_merchant_reference, $customer_id, $status);
                } elseif ($response != null && $status != null && $status == 'COMPLETED') {
                    $this->model_payment_pesapal->updateorderstatusipnOther($order_id, $pesapalTrackingId, $pesapal_merchant_reference, $customer_id, $status);
                } else {
                    $this->model_payment_pesapal->updateorderstatusipnOther($order_id, $pesapalTrackingId, $pesapal_merchant_reference, $customer_id, $status);
                }
            }
        } else {
            if (null != $pesapal_merchant_reference) {
                $order_details = explode('-', $pesapal_merchant_reference);
                $order_id = $order_details[0];
            }
            $ord_arr = explode(",", $order_id);
            foreach ($ord_arr as $ord_ar) {
                $log->write('ipinlistener');
                $log->write($ord_ar);
                $log->write('ipinlistener');

                $pesapal_creds = $this->model_setting_setting->getSetting('pesapal', 0);
                $order_info = $this->model_checkout_order->getOrder($ord_ar);
                $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);
                $customer_id = $customer_info['customer_id'];

                $consumer_key = $pesapal_creds['pesapal_consumer_key']; //Register a merchant account on
                //demo.pesapal.com and use the merchant key for testing.
                //When you are ready to go live make sure you change the key to the live account
                //registered on www.pesapal.com!
                $consumer_secret = $pesapal_creds['pesapal_consumer_secret']; // Use the secret from your test
                //account on demo.pesapal.com. When you are ready to go live make sure you
                //change the secret to the live account registered on www.pesapal.com!
                $statusrequestAPI = 'https://www.pesapal.com/api/querypaymentstatus';
                //'https://demo.pesapal.com/api/querypaymentstatus'; //change to
                //https://www.pesapal.com/api/querypaymentstatus' when you are ready to go live!
                // Parameters sent to you by PesaPal IPN
                /* $pesapalNotification = $pesapalNotification;
                  $pesapalTrackingId = $pesapalTrackingId;
                  $pesapal_merchant_reference = $pesapal_merchant_reference; */

                if ('CHANGE' == $pesapalNotification && '' != $pesapalTrackingId) {
                    $log->write('ipinlistener');
                    $token = $params = null;
                    $consumer = new OAuthConsumer($consumer_key, $consumer_secret);
                    $signature_method = new OAuthSignatureMethod_HMAC_SHA1();

                    //get transaction status
                    $request_status = OAuthRequest::from_consumer_and_token($consumer, $token, 'GET', $statusrequestAPI, $params);
                    $request_status->set_parameter('pesapal_merchant_reference', $pesapal_merchant_reference);
                    $request_status->set_parameter('pesapal_transaction_tracking_id', $pesapalTrackingId);
                    $request_status->sign_request($signature_method, $consumer, $token);

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $request_status);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HEADER, 1);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    if (defined('CURL_PROXY_REQUIRED')) {
                        if (CURL_PROXY_REQUIRED == 'True') {
                            $proxy_tunnel_flag = (defined('CURL_PROXY_TUNNEL_FLAG') && 'FALSE' == strtoupper(CURL_PROXY_TUNNEL_FLAG)) ? false : true;
                            curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, $proxy_tunnel_flag);
                            curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
                            curl_setopt($ch, CURLOPT_PROXY, CURL_PROXY_SERVER_DETAILS);
                        }
                    }

                    $response = curl_exec($ch);
                    $log->write($response);

                    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
                    $raw_header = substr($response, 0, $header_size - 4);
                    $headerArray = explode("\r\n\r\n", $raw_header);
                    $header = $headerArray[count($headerArray) - 1];

                    //transaction status
                    $elements = preg_split('/=/', substr($response, $header_size));
                    $status = $elements[1];
                    $log->write('ORDER STATUS');
                    $log->write($status);
                    $log->write('ORDER STATUS');
                    curl_close($ch);

                    $order_info = $this->model_checkout_order->getOrder($ord_ar);
                    if ($response != null && $status != null && $status == 'FAILED') {
                        $this->model_payment_pesapal->addOrderHistoryFailed($ord_ar, $this->config->get('pesapal_failed_order_status_id'), $customer_id, 'customer', $order_info['paid']);
                        $this->model_payment_pesapal->updateorderstatusipn($ord_ar, $pesapalTrackingId, $pesapal_merchant_reference, $customer_id, $status);
                    } elseif ($response != null && $status != null && $status == 'PENDING') {
                        $this->model_payment_pesapal->addOrderHistoryFailed($ord_ar, $this->config->get('pesapal_pending_order_status_id'), $customer_id, 'customer', $order_info['paid']);
                        $this->model_payment_pesapal->updateorderstatusipn($ord_ar, $pesapalTrackingId, $pesapal_merchant_reference, $customer_id, $status);
                    } elseif ($response != null && $status != null && $status == 'COMPLETED') {
                        $this->model_payment_pesapal->addOrderHistory($ord_ar, $this->config->get('pesapal_order_status_id'));
                        $this->model_payment_pesapal->updateorderstatusipn($ord_ar, $pesapalTrackingId, $pesapal_merchant_reference, $customer_id, $status);
                    }
                }
            }
        }
        echo $status;
    }

    public function totalData() {
        $this->load->language('checkout/cart');

        if (isset($this->request->get['city_id'])) {
            $this->tax->setShippingAddress($this->request->get['city_id']);
        }

        // Totals
        $this->load->model('extension/extension');

        $total_data = [];
        $total = 0;
        $taxes = $this->cart->getTaxes();

        // Display prices
        if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
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

            //echo "<pre>";print_r($results);die;
            $sort_order = [];

            foreach ($total_data as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }

            array_multisort($sort_order, SORT_ASC, $total_data);
        }

        $data['totals'] = [];

        foreach ($total_data as $total) {
            $data['totals'][] = [
                'title' => $total['title'],
                'text' => $this->currency->format($total['value']),
                'value' => $total['value'],
            ];
        }

        return $data;
    }

}
