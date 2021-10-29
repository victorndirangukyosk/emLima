<?php

require_once DIR_SYSTEM . '/vendor/mpesa-php-sdk-master/vendor/autoload.php';

class ControllerApiCustomerMpesa extends Controller {

    private $error = [];

    public function addMpesa($data = []) {
        $json['status'] = false;

        $this->load->language('payment/mpesa');
        $this->load->model('sale/order');
        $this->load->model('payment/mpesa');
        $this->load->model('checkout/order');

        if ($this->validate($data)) {
            $orders = $data['orders'];
            $number = $data['mpesa_phonenumber'];

            $log = new Log('error.log');

            $log->write($data);
            /* start */
            $amount = 0;
            foreach ($orders as $order_id) {
                $order_id = $order_id;

                if (isset($order_id)) {
                    $order_info = $this->model_checkout_order->getOrder($order_id);
                    if (count($order_info) > 0) {
                        $amount += (int) ($order_info['total'] - $order_info['amount_partialy_paid']);
                    }
                }
            }
            $live = 'true';
            $mpesa = new \Safaricom\Mpesa\Mpesa($this->config->get('mpesa_customer_key'), $this->config->get('mpesa_customer_secret'), $this->config->get('mpesa_environment'), $live);
            $sta = false;

            $log->write('STKPushSimulation confirm');
            $log->write($sta);
            if (!$sta) {
                $PartyA = $this->config->get('config_telephone_code') . '' . $number;

                $BusinessShortCode = $this->config->get('mpesa_business_short_code');
                $LipaNaMpesaPasskey = $this->config->get('mpesa_lipanampesapasskey');
                $TransactionType = 'CustomerPayBillOnline'; //'CustomerBuyGoodsOnline';    
                $CallBackURL = $this->url->link('deliversystem/deliversystem/mpesamobileOrderStatusTransactions', '', 'SSL');
                $Amount = $amount;
                $PartyB = $this->config->get('mpesa_business_short_code');
                $PhoneNumber = $this->config->get('config_telephone_code') . '' . $number;
                //$AccountReference = 'GPK'; //$this->config->get('config_name');
                $AccountReference = "#" . implode('#', $orders); //$this->config->get('config_name');
                $TransactionDesc = implode(" #", $orders);
                $Remarks = 'PAYMENT';
                $log->write($BusinessShortCode . 'x' . $LipaNaMpesaPasskey . 'x' . $TransactionType . 'amount' . $Amount . 'x' . $PartyA . 'x' . $PartyB . 'x' . $PhoneNumber . 'x' . $CallBackURL . 'x' . $AccountReference . 'x' . $TransactionDesc . 'x' . $Remarks);
                $stkPushSimulation = $mpesa->STKPushSimulation($BusinessShortCode, $LipaNaMpesaPasskey, $TransactionType, $Amount, $PartyA, $PartyB, $PhoneNumber, $CallBackURL, $AccountReference, $TransactionDesc, $Remarks);
                $log->write('STKPushSimulation');
                $log->write($stkPushSimulation);
                $stkPushSimulation = json_decode($stkPushSimulation);
                $json['response'] = $stkPushSimulation;
            }

            if (isset($stkPushSimulation->ResponseCode) && 0 == $stkPushSimulation->ResponseCode) {
                //save in

                foreach ($orders as $order_id) {
                    $sen['order_id'] = $order_id;

                    $this->model_payment_mpesa->addOrder($sen, $stkPushSimulation->MerchantRequestID, $stkPushSimulation->CheckoutRequestID);
                }
                $json['status'] = true;
                $json['message'] = sprintf($this->language->get('text_sms_sent'), $number);
            } else {
                //failing orders from api
            }

            /* end */

            //return $json;
        } else {
            $json['status'] = 10014;

            foreach ($this->error as $key => $value) {
                $json['message'][] = ['type' => $key, 'body' => $value];
            }

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validate($data) {
        if (empty($data['payment_method'])) {
            $this->error['payment_method'] = 'Payment method required!';
        }

        if (empty($data['payment_method_code'])) {
            $this->error['payment_method_code'] = 'Payment method code required!';
        }

        if (empty($data['mpesa_phonenumber'])) {
            $this->error['mpesa_phonenumber'] = 'Phone number required!';
        }

        if (empty($data['orders'])) {
            $this->error['error_orders'] = 'Order(s) required!';
        }
        return !$this->error;
    }

    protected function validatecheckout($data) {
        if (empty($data['payment_method'])) {
            $this->error['payment_method'] = 'Payment method required!';
        }

        if (empty($data['payment_method_code'])) {
            $this->error['payment_method_code'] = 'Payment method code required!';
        }

        if (empty($data['mpesa_phonenumber'])) {
            $this->error['mpesa_phonenumber'] = 'Phone number required!';
        }

        if (empty($data['order_reference_number'])) {
            $this->error['order_reference_number'] = 'Order reference number required!';
        }

        /* if (empty($data['merchant_request_id'])) {
          $this->error['merchant_request_id'] = 'Merchant request id required!';
          }

          if (empty($data['checkout_request_id'])) {
          $this->error['checkout_request_id'] = 'Checkout request id required!';
          } */

        $amount = $this->cart->getTotalForKwikBasket();
        if ($amount <= 0) {
            $this->error['kwikbasket_order_total'] = 'KwikBasket Store Order Total Cant Be Less Or Equal To Zero!';
        }
        return !$this->error;
    }

    public function addMpesacomplete($data = []) {
        $json['status'] = false;

        $this->load->language('payment/mpesa');
        $this->load->model('sale/order');
        $this->load->model('payment/mpesa');
        $this->load->model('checkout/order');
        $this->load->model('account/customer');

        if ($this->validate($data)) {
            $orders = $data['orders'];
            $number = $data['mpesa_phonenumber'];
            $log = new Log('error.log');
            $log->write($data);

            foreach ($data['orders'] as $order_id) {
                $log->write('ORDER ID');
                $log->write($data['orders']);
                $log->write('ORDER ID');
                $order_id = $order_id;
                $mpesaDetails = $this->model_payment_mpesa->getMpesaByOrderId($order_id);

                $live = true;

                $mpesa = new \Safaricom\Mpesa\Mpesa($this->config->get('mpesa_customer_key'), $this->config->get('mpesa_customer_secret'), $this->config->get('mpesa_environment'), $live);

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
                        $json['error'] = $stkPushSimulation->ResultDesc;
                        $json['mpesa_response'] = $stkPushSimulation;
                    }

                    if (isset($stkPushSimulation->ResultCode) && 0 == $stkPushSimulation->ResultCode) {
                        //success pending to processing
                        $order_status_id = $this->config->get('mpesa_order_status_id');

                        $log->write('updateMpesaOrderStatus validatex');

                        $this->load->model('localisation/order_status');

                        $order_status = $this->model_localisation_order_status->getOrderStatuses();

                        $order_info = $this->model_checkout_order->getOrder($order_id);
                        $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);
                        $this->model_payment_mpesa->insertOrderTransactionId($order_id, $stkPushSimulation->CheckoutRequestID);
                        $this->model_payment_mpesa->addOrderHistoryTransaction($order_id, $this->config->get('mpesa_order_status_id'), $customer_info['customer_id'], 'customer', $order_info['order_status_id'], 'mPesa Online', 'mpesa');
                        $json['status'] = true;
                        $json['message'] = 'Payment Successfull.';
                        $json['mpesa_response'] = $stkPushSimulation;
                    }
                }
            }
        } else {
            $json['status'] = 10014;

            foreach ($this->error as $key => $value) {
                $json['message'][] = ['type' => $key, 'body' => $value];
            }

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addMpesaCheckout($data = []) {
        $json['status'] = false;

        $this->load->language('payment/mpesa');
        $this->load->model('sale/order');
        $this->load->model('payment/mpesa');
        $this->load->model('checkout/order');

        if ($this->validatecheckout($data)) {
            $order_reference_number = $data['order_reference_number'];
            $number = $data['mpesa_phonenumber'];
            $merchant_request_id = $data['merchant_request_id'];
            $checkout_request_id = $data['checkout_request_id'];

            $log = new Log('error.log');

            $log->write($data);
            /* start */

            $amount = $this->cart->getTotalForKwikBasket();
            $live = true;
            $mpesa = new \Safaricom\Mpesa\Mpesa($this->config->get('mpesa_customer_key'), $this->config->get('mpesa_customer_secret'), $this->config->get('mpesa_environment'), $live);
            $sta = false;
            if (!$sta) {
                $PartyA = $this->config->get('config_telephone_code') . '' . $number;

                $BusinessShortCode = $this->config->get('mpesa_business_short_code');
                $LipaNaMpesaPasskey = $this->config->get('mpesa_lipanampesapasskey');
                $TransactionType = 'CustomerBuyGoodsOnline';
                $CallBackURL = $this->url->link('deliversystem/deliversystem/mpesamobilecheckoutcallback', '', 'SSL');

                $Amount = $amount;

                $PartyB = $this->config->get('mpesa_business_short_code');

                $PhoneNumber = $this->config->get('config_telephone_code') . '' . $number;
                $AccountReference = "#" . $order_reference_number; //$this->config->get('config_name');
                $TransactionDesc = "#" . $order_reference_number;
                $Remarks = 'PAYMENT';

                $log->write($BusinessShortCode . 'x' . $LipaNaMpesaPasskey . 'x' . $TransactionType . 'amount' . $Amount . 'x' . $PartyA . 'x' . $PartyB . 'x' . $PhoneNumber . 'x' . $CallBackURL . 'x' . $AccountReference . 'x' . $TransactionDesc . 'x' . $Remarks);

                $stkPushSimulation = $mpesa->STKPushSimulation($BusinessShortCode, $LipaNaMpesaPasskey, $TransactionType, $Amount, $PartyA, $PartyB, $PhoneNumber, $CallBackURL, $AccountReference, $TransactionDesc, $Remarks);
                // Void the order first
                $log->write('STKPushSimulation');
                $log->write($stkPushSimulation);

                $stkPushSimulation = json_decode($stkPushSimulation);

                $json['response'] = $stkPushSimulation;
                $json['order_reference_number'] = $order_reference_number;
            }

            if (isset($stkPushSimulation->ResponseCode) && 0 == $stkPushSimulation->ResponseCode) {
                //save in
                $order_info['order_id'] = 0;
                $this->model_payment_mpesa->addOrderMobile($order_info, $stkPushSimulation->MerchantRequestID, $stkPushSimulation->CheckoutRequestID, $this->customer->getId(), 0, $order_reference_number);
                $json['status'] = true;
                $json['message'] = sprintf($this->language->get('text_sms_sent'), $number);
            } else {
                //failing orders from api
            }

            /* end */

            //return $json;
            /* $order_info['order_id'] = 0;
              $this->model_payment_mpesa->addOrderMobile($order_info, $merchant_request_id, $checkout_request_id, $this->customer->getId(), 0, $order_reference_number);
              $json['status'] = true;
              $json['message'] = sprintf($this->language->get('text_sms_sent'), $number); */
        } else {
            $json['status'] = 10014;

            foreach ($this->error as $key => $value) {
                $json['message'][] = ['type' => $key, 'body' => $value];
            }

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addMpesaCheckoutComplete($data = []) {
        $json['status'] = false;

        $this->load->language('payment/mpesa');
        $this->load->model('sale/order');
        $this->load->model('payment/mpesa');
        $this->load->model('checkout/order');
        $this->load->model('account/customer');

        if ($this->validatecheckout($data)) {
            $order_reference_number = $data['order_reference_number'];
            $number = $data['mpesa_phonenumber'];
            $log = new Log('error.log');
            $log->write($data);

            $mpesaDetails = $this->model_payment_mpesa->getMpesaByOrderReferenceNumber($order_reference_number);

            $live = true;

            $mpesa = new \Safaricom\Mpesa\Mpesa($this->config->get('mpesa_customer_key'), $this->config->get('mpesa_customer_secret'), $this->config->get('mpesa_environment'), $live);

            if ($mpesaDetails) {

                $BusinessShortCode = $this->config->get('mpesa_business_short_code');
                $LipaNaMpesaPasskey = $this->config->get('mpesa_lipanampesapasskey');

                $checkoutRequestID = $mpesaDetails['checkout_request_id']; //'ws_CO_28032018142406660';
                $timestamp = '20' . date('ymdhis');
                $password = base64_encode($BusinessShortCode . $LipaNaMpesaPasskey . $timestamp);

                $stkPushSimulation = $mpesa->STKPushQuery($live, $checkoutRequestID, $BusinessShortCode, $password, $timestamp);

                // Void the order first
                $log->write('COMPLETE STKPushSimulation');
                $log->write($stkPushSimulation);

                $stkPushSimulation = json_decode($stkPushSimulation);
                $log->write('COMPLETE STKPushSimulation JSON ARRAY');
                $log->write($stkPushSimulation);
                if (isset($stkPushSimulation->ResultCode) && 0 != $stkPushSimulation->ResultCode && $stkPushSimulation->ResultDesc != NULL) {
                    $json['error'] = $stkPushSimulation->ResultDesc;
                    $json['mpesa_response'] = $stkPushSimulation;
                }

                if (isset($stkPushSimulation->ResultCode) && 0 == $stkPushSimulation->ResultCode) {
                    $json['status'] = true;
                    $json['message'] = 'Payment Successfull.';
                    $json['mpesa_response'] = $stkPushSimulation;
                }
            }
        } else {
            $json['status'] = 10014;

            foreach ($this->error as $key => $value) {
                $json['message'][] = ['type' => $key, 'body' => $value];
            }

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
