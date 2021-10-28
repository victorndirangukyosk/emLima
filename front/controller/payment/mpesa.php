<?php

require_once DIR_SYSTEM . '/vendor/mpesa-php-sdk-master/vendor/autoload.php';

class ControllerPaymentMpesa extends Controller {

    public function index() {
        $this->load->language('payment/mpesa');

        $data['text_instruction'] = $this->language->get('text_instruction');
        $data['text_payable'] = $this->language->get('text_payable');
        $data['text_address'] = $this->language->get('text_address');
        $data['text_payment'] = $this->language->get('text_payment');

        $data['button_confirm'] = $this->language->get('button_confirm');

        $data['payable'] = $this->config->get('mpesa_payable');
        $data['address'] = nl2br($this->config->get('config_address'));

        $data['continue'] = $this->url->link('checkout/success');

        $data['customer_number'] = $this->customer->getTelephone();

        $this->load->model('checkout/order');
        $order_ids = array();
        foreach ($this->session->data['order_id'] as $key => $value) {
            /* FOR KWIKBASKET ORDERS */
            //if ($key == 75) {
            $order_ids[] = $value;
            $order_id = $value;
            if ($order_id != NULL) {
                $this->model_checkout_order->UpdateParentApproval($order_id);
            }
            //}
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/mpesa.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/payment/mpesa.tpl', $data);
        } else {
            return $this->load->view('default/template/payment/mpesa.tpl', $data);
        }
    }

    public function confirm() {
        $log = new Log('error.log');
        $json['processed'] = false;

        if ('mpesa' == $this->session->data['payment_method']['code'] || 'mpesa' == $this->request->post['payment_method']) {
            $this->load->language('payment/mpesa');

            $this->load->model('payment/mpesa');

            $this->load->model('checkout/order');
            foreach ($this->session->data['order_id'] as $key => $value) {
                $order_id = $value;
            }

            if (isset($this->request->post['order_id'])) {
                $order_id = $this->request->post['order_id'];
            }

            foreach ($this->session->data['order_id'] as $key => $value) {
                /* FOR KWIKBASKET ORDERS */
                if ($key == 75) {
                    $order_id = $value;
                }
            }

            $amount = 0;

            if (isset($order_id)) {
                $order_info = $this->model_checkout_order->getOrder($order_id);
                if (count($order_info) > 0) {
                    $amount = (int) ($order_info['total']);
                }
            }

            if (empty($order_id)) {
                $amount = (int) $this->request->post['amount'];
            }

            $live = 'true';

            $mpesa = new \Safaricom\Mpesa\Mpesa($this->config->get('mpesa_customer_key'), $this->config->get('mpesa_customer_secret'), $this->config->get('mpesa_environment'), $live);

            //$sta = $this->checkMpesaStatus($order_id,$mpesa);

            $sta = false;

            $log->write('STKPushSimulation confirm');
            $log->write($sta);

            if (!$sta) {
                $PartyA = $this->config->get('config_telephone_code') . '' . $this->request->post['mobile'];

                $BusinessShortCode = $this->config->get('mpesa_business_short_code');
                $LipaNaMpesaPasskey = $this->config->get('mpesa_lipanampesapasskey');
                $TransactionType = 'CustomerPayBillOnline'; //'CustomerBuyGoodsOnline';
                $CallBackURL = $this->url->link('deliversystem/deliversystem/mpesaOrderStatus', '', 'SSL');
                //$CallBackURL = 'https://a1c6dda0aaba.ngrok.io/kwikbasket/index.php?path=deliversystem/deliversystem/mpesaOrderStatus';

                $Amount = $amount;
                //$Amount = 10;
                $PartyB = $this->config->get('mpesa_business_short_code');

                $PhoneNumber = $this->config->get('config_telephone_code') . '' . $this->request->post['mobile'];
                //$AccountReference = 'GPK'; //$this->config->get('config_name');
                $AccountReference = '#' . $order_id; //$this->config->get('config_name');
                $TransactionDesc = '#' . $order_id;

                if (empty($order_id)) {
                    if (isset($this->request->post['paymode']) && !empty($this->request->post['paymode']) && 'pay_other' == $this->request->post['paymode']) {
                        $TransactionDesc = '#' . $this->request->post['pending_order_ids'] . '##' . $this->request->post['customer'];
                    } else {
                        $TransactionDesc = '#' . $this->request->post['pending_order_ids'];
                    }
                }

                $Remarks = 'PAYMENT';

                $log->write($BusinessShortCode . 'x' . $LipaNaMpesaPasskey . 'x' . $TransactionType . 'amount' . $Amount . 'x' . $PartyA . 'x' . $PartyB . 'x' . $PhoneNumber . 'x' . $CallBackURL . 'x' . $AccountReference . 'x' . $TransactionDesc . 'x' . $Remarks);

                $stkPushSimulation = $mpesa->STKPushSimulation($BusinessShortCode, $LipaNaMpesaPasskey, $TransactionType, $Amount, $PartyA, $PartyB, $PhoneNumber, $CallBackURL, $AccountReference, $TransactionDesc, $Remarks);

                // Void the order first
                $log->write('STKPushSimulation');
                $log->write($stkPushSimulation);

                $stkPushSimulation = json_decode($stkPushSimulation);

                $json['response'] = $stkPushSimulation;
                $json['error'] = '';
                if (isset($json['response']->errorMessage)) {
                    $json['error'] = $json['response']->errorMessage;
                }

                if (isset($stkPushSimulation->ResponseCode) && 0 == $stkPushSimulation->ResponseCode) {
                    if (!empty($order_id)) {
                        //save in
                        $this->model_payment_mpesa->addOrder($order_info, $stkPushSimulation->MerchantRequestID, $stkPushSimulation->CheckoutRequestID);
                    } else {
                        if (isset($this->request->post['paymode']) && !empty($this->request->post['paymode']) && 'pay_other' == $this->request->post['paymode']) {
                            //$TransactionDesc = '#'.$this->request->post['pending_order_ids'].'##'.$this->request->post['customer'];
                        } else {
                            $pendingOrdersIds = explode('--', $this->request->post['pending_order_ids']);
                            if (count($pendingOrdersIds)) {
                                foreach ($pendingOrdersIds as $key => $value) {
                                    $order_info = $this->model_checkout_order->getOrder($value);
                                    $this->model_payment_mpesa->addOrder($order_info, $stkPushSimulation->MerchantRequestID, $stkPushSimulation->CheckoutRequestID);
                                }
                            }
                        }
                    }
                    /* foreach ($this->session->data['order_id'] as $order_id) {

                      $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('mpesa_order_status_id'));
                      } */

                    $json['processed'] = true;
                } else {
                    $json['processed'] = false;
                }
            } else {
                $json['processed'] = true;
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function confirmtransaction() {
        $log = new Log('error.log');
        $json['processed'] = false;

        // echo "<pre>";print_r($this->request->post);die; 

        if ('mpesa' == $this->request->post['payment_method']) {
            $this->load->language('payment/mpesa');

            $this->load->model('payment/mpesa');

            $this->load->model('checkout/order');

            $log->write($this->request->post['mobile']);
            $log->write($this->request->post['order_id']);
            $log->write($this->request->post['amount']);
            $log->write($this->request->post['payment_type']);
            $log->write($this->request->post['payment_method']);

            $amount = 0;

            if ($this->request->post['payment_type'] == 'pay_full') {
                $this->request->post['order_id'] = explode('--', $this->request->post['order_id']);
            }
            $log->write($this->request->post['order_id']);

            foreach ($this->request->post['order_id'] as $key => $value) {
                $order_info = $this->model_checkout_order->getOrder($value);
                if (count($order_info) > 0) {
                    $amount += (int) ($order_info['total'] - $order_info['amount_partialy_paid']);
                }
            }

            if ($this->request->post['payment_type'] == 'topup') {
                $amount = $this->request->post['amount'];
            }
            $log->write($amount);

            $this->request->post['pending_order_ids'] = '';
            if (is_array($this->request->post['order_id']) && count($this->request->post['order_id']) > 1) {
                $this->request->post['pending_order_ids'] = implode('--', $this->request->post['order_id']);
            }

            // echo "<pre>";print_r($this->request->post['pending_order_ids']); 





            $live = 'true';

            $mpesa = new \Safaricom\Mpesa\Mpesa($this->config->get('mpesa_customer_key'), $this->config->get('mpesa_customer_secret'), $this->config->get('mpesa_environment'), $live);

            //$sta = $this->checkMpesaStatus($order_id,$mpesa);

            $sta = false;

            $log->write('STKPushSimulation confirm');
            $log->write($sta);

            if (!$sta) {
                $PartyA = $this->config->get('config_telephone_code') . '' . $this->request->post['mobile'];

                $BusinessShortCode = $this->config->get('mpesa_business_short_code');
                $LipaNaMpesaPasskey = $this->config->get('mpesa_lipanampesapasskey');
                $TransactionType = 'CustomerPayBillOnline'; //'CustomerBuyGoodsOnline';

                if ($this->request->post['payment_type'] == 'topup') {
                    $CallBackURL = $this->url->link('deliversystem/deliversystem/mpesaOrderStatus', '', 'SSL');
                } else {
                    $CallBackURL = $this->url->link('deliversystem/deliversystem/mpesaOrderStatusTransactions', '', 'SSL');
                    //$CallBackURL = 'https://a1c6dda0aaba.ngrok.io/kwikbasket/index.php?path=deliversystem/deliversystem/mpesaOrderStatus';
                }
                $Amount = $amount;
                //$Amount = 10;
                $PartyB = $this->config->get('mpesa_business_short_code');

                $PhoneNumber = $this->config->get('config_telephone_code') . '' . $this->request->post['mobile'];
                $AccountReference = $this->customer->getId().'WALLET_TOPUP'; //$this->config->get('config_name');
                if (is_array($this->request->post['pending_order_ids']) && count($this->request->post['pending_order_ids']) > 0) {
                    $AccountReference = "#".implode('#', $this->request->post['pending_order_ids']);
                }
                if (is_array($this->request->post['order_id']) && count($this->request->post['order_id']) > 0) {
                    $AccountReference = "#".implode('#', $this->request->post['order_id']);
                }

                $TransactionDesc = '#' . $this->request->post['pending_order_ids'] . '##' . $this->customer->getId();
            }

            $Remarks = 'PAYMENT';

            $log->write($BusinessShortCode . 'x' . $LipaNaMpesaPasskey . 'x' . $TransactionType . 'amount' . $Amount . 'x' . $PartyA . 'x' . $PartyB . 'x' . $PhoneNumber . 'x' . $CallBackURL . 'x' . $AccountReference . 'x' . $TransactionDesc . 'x' . $Remarks);

            $stkPushSimulation = $mpesa->STKPushSimulation($BusinessShortCode, $LipaNaMpesaPasskey, $TransactionType, $Amount, $PartyA, $PartyB, $PhoneNumber, $CallBackURL, $AccountReference, $TransactionDesc, $Remarks);

            // Void the order first
            $log->write('STKPushSimulation');
            $log->write($stkPushSimulation);

            $stkPushSimulation = json_decode($stkPushSimulation);

            $json['response'] = $stkPushSimulation;
            $json['error'] = '';
            if (isset($json['response']->errorMessage)) {
                $json['error'] = $json['response']->errorMessage;
            }

            if (isset($stkPushSimulation->ResultCode) && 0 != $stkPushSimulation->ResultCode && $stkPushSimulation->ResultDesc != NULL) {
                $json['error'] = $json['error'] . ' ' . $stkPushSimulation->ResultDesc;
            }

            if (isset($stkPushSimulation->ResponseCode) && 0 == $stkPushSimulation->ResponseCode) {
                if (is_array($this->request->post['order_id']) && count($this->request->post['order_id']) > 1) {
                    $pendingOrdersIds = explode('--', $this->request->post['pending_order_ids']);
                }

                if (is_array($this->request->post['order_id']) && count($this->request->post['order_id']) == 1) {
                    $pendingOrdersIds = $this->request->post['order_id'];
                }

                if (isset($pendingOrdersIds) && count($pendingOrdersIds)) {
                    foreach ($pendingOrdersIds as $key => $value) {
                        $order_info = $this->model_checkout_order->getOrder($value);
                        $this->model_payment_mpesa->addOrder($order_info, $stkPushSimulation->MerchantRequestID, $stkPushSimulation->CheckoutRequestID);
                    }
                    $this->session->data['mpesa_payments_request'] = array('checkout_request_id' => $stkPushSimulation->CheckoutRequestID);
                    $this->session->data['mpesa_payments_response'] = array('checkout_request_id' => $stkPushSimulation->CheckoutRequestID);
                } else {

                    $this->model_payment_mpesa->addOrder(0, $stkPushSimulation->MerchantRequestID, $stkPushSimulation->CheckoutRequestID, $this->customer->getId(), $amount);
                }
                /* foreach ($this->session->data['order_id'] as $order_id) {

                  $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('mpesa_order_status_id'));
                  } */

                $json['processed'] = true;
            } else {
                $json['processed'] = false;
            }
        } else {
            $json['processed'] = true;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function complete() {
        $log = new Log('error.log');
        $json['processed'] = false;
        $json['status'] = false;

        $json['error'] = 'Transaction Failed. Please Try again.';

        if ('mpesa' == $this->session->data['payment_method']['code'] || 'mpesa' == $this->request->post['payment_method']) {
            $this->load->language('payment/mpesa');

            $this->load->model('payment/mpesa');

            $this->load->model('checkout/order');

            foreach ($this->session->data['order_id'] as $key => $value) {
                if ($key == 75) {
                    $order_id = $value;
                }
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
                /* foreach ($mpesaDetails as $mpesaDetail) { */
                //echo "<pre>";print_r($mpesaDetail);die;

                $BusinessShortCode = $this->config->get('mpesa_business_short_code');
                $LipaNaMpesaPasskey = $this->config->get('mpesa_lipanampesapasskey');

                $checkoutRequestID = $mpesaDetails['checkout_request_id']; //'ws_CO_28032018142406660';
                $timestamp = '20' . date('ymdhis');
                $password = base64_encode($BusinessShortCode . $LipaNaMpesaPasskey . $timestamp);

                $stkPushSimulation = $mpesa->STKPushQuery($live, $checkoutRequestID, $BusinessShortCode, $password, $timestamp);

                // Void the order first
                $log->write('STKPushSimulation');
                $log->write($stkPushSimulation);

                $stkPushSimulation = json_decode($stkPushSimulation);
                $log->write('STKPushSimulation JSON ARRAY');
                $log->write($stkPushSimulation);
                if (isset($stkPushSimulation->ResultCode) && 0 != $stkPushSimulation->ResultCode && $stkPushSimulation->ResultDesc != NULL) {
                    $json['error'] = $json['error'] . ' ' . $stkPushSimulation->ResultDesc;
                }
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

                if (isset($stkPushSimulation->ResultCode) && 0 == $stkPushSimulation->ResultCode) {
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
                    $dataAddHisory['paid'] = 'Y';

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
                /* } */
            }
            $this->load->controller('payment/cod/confirmnonkb');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function completetransaction() {
        $log = new Log('error.log');
        $json['processed'] = false;
        $json['status'] = false;

        $json['error'] = 'Transaction Failed. Please Try again.';

        if ('mpesa' == $this->request->post['payment_method']) {
            $this->load->language('payment/mpesa');

            $this->load->model('payment/mpesa');

            $this->load->model('checkout/order');
            $this->load->model('account/customer');

            $amount = 0;

            if ($this->request->post['payment_type'] == 'pay_full') {
                $this->request->post['order_id'] = explode('--', $this->request->post['order_id']);
            }
            $log->write($this->request->post['order_id']);

            foreach ($this->request->post['order_id'] as $key => $value) {
                $order_info = $this->model_checkout_order->getOrder($value);
                if (count($order_info) > 0) {
                    $amount += (int) ($order_info['total']);
                }
            }

            if ($this->request->post['payment_type'] == 'topup') {
                $amount = $this->request->post['amount'];
            }

            $this->request->post['pending_order_ids'] = '';
            if (is_array($this->request->post['order_id']) && count($this->request->post['order_id']) > 1) {
                $this->request->post['pending_order_ids'] = implode('--', $this->request->post['order_id']);
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
            foreach ($this->request->post['order_id'] as $key => $value) {
                $log->write('ORDER ID');
                $log->write($value);
                $log->write($this->request->post['order_id']);
                $log->write('ORDER ID');
                $order_id = $value;
                $mpesaDetails = $this->model_payment_mpesa->getMpesaByOrderId($order_id);

                $live = true;

                $mpesa = new \Safaricom\Mpesa\Mpesa($this->config->get('mpesa_customer_key'), $this->config->get('mpesa_customer_secret'), $this->config->get('mpesa_environment'), $live);

                if ($mpesaDetails) {
                    /* foreach ($mpesaDetails as $mpesaDetail) { */
                    //echo "<pre>";print_r($mpesaDetail);die;

                    $BusinessShortCode = $this->config->get('mpesa_business_short_code');
                    $LipaNaMpesaPasskey = $this->config->get('mpesa_lipanampesapasskey');

                    $checkoutRequestID = $mpesaDetails['checkout_request_id']; //'ws_CO_28032018142406660';
                    $timestamp = '20' . date('ymdhis');
                    $password = base64_encode($BusinessShortCode . $LipaNaMpesaPasskey . $timestamp);

                    $stkPushSimulation = $mpesa->STKPushQuery($live, $checkoutRequestID, $BusinessShortCode, $password, $timestamp);

                    // Void the order first
                    $log->write('STKPushSimulation');
                    $log->write($stkPushSimulation);

                    $stkPushSimulation = json_decode($stkPushSimulation);
                    $log->write('STKPushSimulation JSON ARRAY');
                    $log->write($stkPushSimulation);
                    if (isset($stkPushSimulation->ResultCode) && 0 != $stkPushSimulation->ResultCode && $stkPushSimulation->ResultDesc != NULL) {
                        $json['error'] = $json['error'] . ' ' . $stkPushSimulation->ResultDesc;
                    }
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

                    if (isset($stkPushSimulation->ResultCode) && 0 == $stkPushSimulation->ResultCode) {
                        //success pending to processing
                        $order_status_id = $this->config->get('mpesa_order_status_id');

                        $log->write('updateMpesaOrderStatus validatex');

                        $this->load->model('localisation/order_status');

                        $order_status = $this->model_localisation_order_status->getOrderStatuses();

                        $order_info = $this->model_checkout_order->getOrder($order_id);
                        $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);
                        //SKIPPNG HERE UPDATING CheckoutRequestID..BUT WE NEED TO UPDATE RECEIPT NUMBER
                        //$this->model_payment_mpesa->insertOrderTransactionId($order_id, $stkPushSimulation->CheckoutRequestID);
                        $this->model_payment_mpesa->addOrderHistoryTransaction($order_id, $this->config->get('mpesa_order_status_id'), $customer_info['customer_id'], 'customer', $order_info['order_status_id'], 'mPesa Online', 'mpesa');
                        $json['status'] = true;
                        $json['redirect'] = $this->url->link('account/transactions');
                        /* $dataAddHisory['order_id'] = $order_id;
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
                          } */
                    }
                    /* } */
                }
            }


            // for topup $this->request->post['order_id'] will be null
            if ($this->request->post['payment_type'] == 'topup') {

                $mpesaDetails = $this->model_payment_mpesa->getMpesaByCustomerId($this->customer->getId());

                $live = true;

                $mpesa = new \Safaricom\Mpesa\Mpesa($this->config->get('mpesa_customer_key'), $this->config->get('mpesa_customer_secret'), $this->config->get('mpesa_environment'), $live);
                $customer_id = $this->customer->getId();
                $amount_topup = $this->request->post['amount'];
                if ($mpesaDetails) {


                    $BusinessShortCode = $this->config->get('mpesa_business_short_code');
                    $LipaNaMpesaPasskey = $this->config->get('mpesa_lipanampesapasskey');

                    $checkoutRequestID = $mpesaDetails['checkout_request_id']; //'ws_CO_28032018142406660';
                    $timestamp = '20' . date('ymdhis');
                    $password = base64_encode($BusinessShortCode . $LipaNaMpesaPasskey . $timestamp);

                    $stkPushSimulation = $mpesa->STKPushQuery($live, $checkoutRequestID, $BusinessShortCode, $password, $timestamp);

                    // Void the order first
                    $log->write('STKPushSimulation');
                    $log->write($stkPushSimulation);

                    $stkPushSimulation = json_decode($stkPushSimulation);
                    $log->write('STKPushSimulation JSON ARRAY');
                    $log->write($stkPushSimulation);
                    if (isset($stkPushSimulation->ResultCode) && 0 != $stkPushSimulation->ResultCode && $stkPushSimulation->ResultDesc != NULL) {
                        $json['error'] = $json['error'] . ' ' . $stkPushSimulation->ResultDesc;
                    }

                    if (isset($stkPushSimulation->ResultCode) && 0 == $stkPushSimulation->ResultCode) {
                        //success pending to processing
                        $order_status_id = $this->config->get('mpesa_order_status_id');

                        $log->write('updateMpesaOrderStatus validatex');

                        // $this->load->model('localisation/order_status');
                        // $order_status = $this->model_localisation_order_status->getOrderStatuses();
                        // $order_info = $this->model_checkout_order->getOrder($order_id);
                        // $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);
                        $this->model_payment_mpesa->insertCustomerTransactionId($customer_id, $stkPushSimulation->CheckoutRequestID);
                        // $this->model_payment_mpesa->addOrderHistoryTransaction($order_id, $this->config->get('mpesa_order_status_id'), $customer_info['customer_id'], 'customer', $order_info['order_status_id'], 'mPesa Online', 'mpesa');
                        $this->model_payment_mpesa->addCustomerHistoryTransaction($customer_id, $this->config->get('mpesa_order_status_id'), $amount_topup, 'mPesa Online', 'mpesa', $stkPushSimulation->MerchantRequestID);
                        $json['status'] = true;
                        $json['redirect'] = $this->url->link('account/credit');
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

            $live = 'true';

            //$mpesa= new \Safaricom\Mpesa\Mpesa($this->config->get('mpesa_customer_key'),$this->config->get('mpesa_customer_secret'),$this->config->get('mpesa_environment'),$live);

            if ($mpesaDetails) {
                foreach ($mpesaDetails as $mpesaDetail) {
                    //echo "<pre>";print_r($mpesaDetail);die;

                    $BusinessShortCode = $this->config->get('mpesa_business_short_code');
                    $LipaNaMpesaPasskey = $this->config->get('mpesa_lipanampesapasskey');

                    $checkoutRequestID = $mpesaDetail['checkout_request_id']; //'ws_CO_28032018142406660';
                    $timestamp = '20' . date('ymdhis');
                    $password = base64_encode($BusinessShortCode . $LipaNaMpesaPasskey . $timestamp);

                    $log->write($live . 'xx' . $checkoutRequestID . 'xx' . $BusinessShortCode . 'xx' . $password . 'xx' . $timestamp);

                    $stkPushSimulation = $mpesa->STKPushQuery($live, $checkoutRequestID, $BusinessShortCode, $password, $timestamp);

                    // Void the order first
                    $log->write('STKPushSimulation');
                    $log->write($stkPushSimulation);

                    $stkPushSimulation = json_decode($stkPushSimulation);

                    if (isset($stkPushSimulation->ResultCode) && 1001 == $stkPushSimulation->ResultCode) {
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
                        $dataAddHisory['paid'] = 'Y';

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
                            $dataAddHisory['paid'] = 'Y';

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

    public function mpesacallbackupdatemailfail($stkCallback) {
        $log = new Log('error.log');
        $MpesaReceiptNumber = NULL;
        $this->load->model('payment/mpesa');
        $this->load->model('account/customer');
        $this->load->model('checkout/order');
        $manifest_id = $this->model_payment_mpesa->getMpesaOrders($stkCallback->MerchantRequestID);

        if (is_array($manifest_id) && count($manifest_id) > 0) {
            foreach ($manifest_id as $manifest_ids) {
                $log->write($manifest_ids['order_id']);
                $order_info = $this->model_checkout_order->getOrder($manifest_ids['order_id']);
                $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);

                if (isset($stkCallback->CallbackMetadata->Item)) {
                    foreach ($stkCallback->CallbackMetadata->Item as $key => $value) {
                        $log->write($value);

                        if ('MpesaReceiptNumber' == $value->Name) {
                            $MpesaReceiptNumber = $value->Value;
                        }
                    }
                }

                $customer_info['order_id'] = $order_info['order_id'];
                $customer_info['status'] = $stkCallback->ResultCode == 0 ? 'Successful' : 'Failed';
                $customer_info['amount'] = $this->currency->format($order_info['total'] - $order_info['amount_partialy_paid']);
                $customer_info['email'] = $customer_info['email'];

                if ($customer_info['email_notification'] == 1) {
                    $subject = $this->emailtemplate->getSubject('Customer', 'customer_93', $customer_info);
                    $message = $this->emailtemplate->getMessage('Customer', 'customer_93', $customer_info);
                    //$log->write($subject);
                    //$log->write($message);

                    $mail = new Mail($this->config->get('config_mail'));
                    $mail->setTo($customer_info['email']);
                    $mail->setFrom($this->config->get('config_from_email'));
                    $mail->setSender($this->config->get('config_name'));
                    $mail->setSubject($subject);
                    $mail->setHTML($message);
                    $mail->send();
                }

                if ($customer_info['sms_notification'] == 1) {
                    $sms_message = $this->emailtemplate->getSmsMessage('Customer', 'customer_93', $customer_info);

                    if ($this->emailtemplate->getSmsEnabled('Customer', 'customer_93')) {
                        $this->emailtemplate->sendmessage($customer_info['telephone'], $sms_message);
                    }
                }

                if ($customer_info['mobile_notification'] == 1) {
                    if ($this->emailtemplate->getNotificationEnabled('Customer', 'customer_93')) {
                        $mobile_notification_template = $this->emailtemplate->getNotificationMessage('Customer', 'customer_93', $customer_info);
                        $log->write($mobile_notification_template);
                        $mobile_notification_title = $this->emailtemplate->getNotificationTitle('Customer', 'customer_93', $customer_info);
                        $log->write($mobile_notification_title);
                        if (isset($customer_info) && isset($customer_info['device_id']) && $customer_info['mobile_notification'] == 1 && strlen($customer_info['device_id']) > 0) {
                            $log->write('customer device id set FRONT.CONTROLLER.PAYMENT.MPESA');
                            $this->emailtemplate->sendPushNotification($customer_info['customer_id'], $customer_info['device_id'], $order_info['order_id'], $order_info['store_id'], $mobile_notification_title, $mobile_notification_template, 'FLUTTER_NOTIFICATION_CLICK', 'false');
                        } else {
                            $log->write('customer device id set FRONT.CONTROLLER.PAYMENT.MPESA');
                        }
                    }
                }
            }
        }
    }

    public function mpesacallbackupdatemail($stkCallback) {
        $log = new Log('error.log');
        $MpesaReceiptNumber = NULL;
        $this->load->model('payment/mpesa');
        $this->load->model('account/customer');
        $this->load->model('checkout/order');
        $manifest_id = $this->model_payment_mpesa->getMpesaOrders($stkCallback->MerchantRequestID);
        if (is_array($manifest_id) && count($manifest_id) > 0) {
            foreach ($manifest_id as $manifest_ids) {
                $log->write($manifest_ids['order_id']);
                $order_info = $this->model_checkout_order->getOrder($manifest_ids['order_id']);
                $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);
                if (isset($stkCallback->CallbackMetadata->Item)) {
                    foreach ($stkCallback->CallbackMetadata->Item as $key => $value) {
                        $log->write($value);

                        if ('MpesaReceiptNumber' == $value->Name) {
                            $MpesaReceiptNumber = $value->Value;
                        }
                    }
                }

                $customer_info['order_id'] = $order_info['order_id'];
                $customer_info['status'] = $stkCallback->ResultCode == 0 ? 'Successful' : 'Failed';
                $customer_info['amount'] = $this->currency->format($order_info['total'] - $order_info['amount_partialy_paid']);
                $customer_info['email'] = $customer_info['email'];
                $customer_info['mpesa_receipt_number'] = $MpesaReceiptNumber;

                if ($customer_info['email_notification'] == 1) {
                    $subject = $this->emailtemplate->getSubject('Customer', 'customer_93', $customer_info);
                    $message = $this->emailtemplate->getMessage('Customer', 'customer_93', $customer_info);
                    //$log->write($subject);
                    //$log->write($message);

                    $mail = new Mail($this->config->get('config_mail'));
                    $mail->setTo($customer_info['email']);
                    $mail->setFrom($this->config->get('config_from_email'));
                    $mail->setSender($this->config->get('config_name'));
                    $mail->setSubject($subject);
                    $mail->setHTML($message);
                    $mail->send();
                }

                if ($customer_info['sms_notification'] == 1) {
                    $sms_message = $this->emailtemplate->getSmsMessage('Customer', 'customer_93', $customer_info);

                    if ($this->emailtemplate->getSmsEnabled('Customer', 'customer_93')) {
                        $this->emailtemplate->sendmessage($customer_info['telephone'], $sms_message);
                    }
                }

                if ($customer_info['mobile_notification'] == 1) {
                    if ($this->emailtemplate->getNotificationEnabled('Customer', 'customer_93')) {
                        $mobile_notification_template = $this->emailtemplate->getNotificationMessage('Customer', 'customer_93', $customer_info);
                        $log->write($mobile_notification_template);
                        $mobile_notification_title = $this->emailtemplate->getNotificationTitle('Customer', 'customer_93', $customer_info);
                        $log->write($mobile_notification_title);
                        if (isset($customer_info) && isset($customer_info['device_id']) && $customer_info['mobile_notification'] == 1 && strlen($customer_info['device_id']) > 0) {
                            $log->write('customer device id set FRONT.CONTROLLER.PAYMENT.MPESA');
                            $this->emailtemplate->sendPushNotification($customer_info['customer_id'], $customer_info['device_id'], $order_info['order_id'], $order_info['store_id'], $mobile_notification_title, $mobile_notification_template, 'FLUTTER_NOTIFICATION_CLICK', 'true');
                        } else {
                            $log->write('customer device id set FRONT.CONTROLLER.PAYMENT.MPESA');
                        }
                    }
                }
            }
        }
    }

    public function mpesacallbackupdate($stkCallback) {
        $log = new Log('error.log');
        $MpesaReceiptNumber = NULL;
        $this->load->model('payment/mpesa');
        $manifest_id = $this->model_payment_mpesa->getMpesaOrders($stkCallback->MerchantRequestID);
        if (is_array($manifest_id) && count($manifest_id) > 0) {
            foreach ($manifest_id as $manifest_ids) {
                $log->write($manifest_ids['order_id']);
                if (isset($stkCallback->CallbackMetadata->Item)) {
                    foreach ($stkCallback->CallbackMetadata->Item as $key => $value) {
                        $log->write($value);

                        if ('MpesaReceiptNumber' == $value->Name) {
                            $MpesaReceiptNumber = $value->Value;
                        }
                    }
                }
            }
        }
        $this->cache->set('mpesa_payments_response', array('result' => $stkCallback->ResultCode, 'merchant_request_id' => $stkCallback->MerchantRequestID, 'checkout_request_id' => $stkCallback->CheckoutRequestID, 'mpesa_receipt_number' => $MpesaReceiptNumber, 'description' => $stkCallback->ResultDesc));
        $log->write($this->cache->get('mpesa_payments_response'));
    }

    public function mpesaautoupdate() {
        $mpesa_payments_response = '';
        $json['processed'] = NULL;
        $log = new Log('error.log');
        $log->write('mpesa_payments_response');
        $log->write($this->cache->get('mpesa_payments_response'));
        $log->write('mpesa_payments_response');
        $mpesa_payments_request = $this->session->data['mpesa_payments_request'];
        $mpesa_payments_response = $this->cache->get('mpesa_payments_response');
        //$json['mpesa_payments_request'] = $mpesa_payments_request;
        $json['mpesa_payments_response'] = $mpesa_payments_response;
        $log->write($mpesa_payments_request);
        if (is_array($mpesa_payments_response) && $mpesa_payments_response['checkout_request_id'] == $this->request->post['mpesa_checkout_request_id'] && $mpesa_payments_response['result'] == 0) {
            $json['processed'] = true;
            $json['redirect'] = $this->url->link('account/transactions');
        }
        if (is_array($mpesa_payments_response) && $mpesa_payments_response['checkout_request_id'] == $this->request->post['mpesa_checkout_request_id'] && $mpesa_payments_response['result'] != 0) {
            $json['processed'] = false;
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
