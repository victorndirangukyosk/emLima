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

        if (empty($data['orders']) || !is_array($data['orders'])) {
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

        if (empty($data['order_reference_number']) || !is_array($data['order_reference_number'])) {
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
                        $transaction_details = $this->model_payment_mpesa->getOrderTransactionDetailsByOrderId($order_id);
                        if (is_array($transaction_details) && count($transaction_details) <= 0) {
                            $this->model_payment_mpesa->insertOrderTransactionId($order_id, $stkPushSimulation->CheckoutRequestID);
                        }
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
        $log->write('Mpesacomplete json');
        $log->write($json);
        $log->write('Mpesacomplete json');
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addMpesaCheckout($data = []) {
        $json['status'] = false;

        $this->load->language('payment/mpesa');
        $this->load->model('sale/order');
        $this->load->model('payment/mpesa');
        $this->load->model('checkout/order');
        $kb_order_reference_number = NULL;

        if ($this->validatecheckout($data)) {
            foreach ($data['order_reference_number'] as $key => $order_reference_number) {
                $log = new Log('error.log');
                $log->write('key' . ' ' . 'order_reference_number');
                $log->write($key . ' ' . $order_reference_number);
                $log->write('key' . ' ' . 'order_reference_number');

                //if ($key == 75) {
                $log->write('key' . ' ' . 'order_reference_number');
                $log->write($key . ' ' . $order_reference_number);
                $log->write('key' . ' ' . 'order_reference_number');
                $order_reference_number = $order_reference_number;
                $kb_order_reference_number = $order_reference_number;
                $number = $data['mpesa_phonenumber'];

                $log->write($data);
                /* start */

                //$amount = $this->cart->getTotalForKwikBasket();
                // $amount = $this->cart->getTotal();
                $amount = $this->cart->getTotalWithShipping();
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
                    $CallBackURL = $this->url->link('deliversystem/deliversystem/mpesamobileOrderStatusTransactionss', '', 'SSL');
                    $Amount = $amount;
                    $PartyB = $this->config->get('mpesa_business_short_code');
                    $PhoneNumber = $this->config->get('config_telephone_code') . '' . $number;
                    //$AccountReference = 'GPK'; //$this->config->get('config_name');
                    $AccountReference = "#" . $order_reference_number; //$this->config->get('config_name');
                    $TransactionDesc = "#" . $order_reference_number;
                    $Remarks = 'PAYMENT';
                    $log->write($BusinessShortCode . 'x' . $LipaNaMpesaPasskey . 'x' . $TransactionType . 'amount' . $Amount . 'x' . $PartyA . 'x' . $PartyB . 'x' . $PhoneNumber . 'x' . $CallBackURL . 'x' . $AccountReference . 'x' . $TransactionDesc . 'x' . $Remarks);
                    $stkPushSimulation = $mpesa->STKPushSimulation($BusinessShortCode, $LipaNaMpesaPasskey, $TransactionType, $Amount, $PartyA, $PartyB, $PhoneNumber, $CallBackURL, $AccountReference, $TransactionDesc, $Remarks);
                    $log->write('STKPushSimulation');
                    $log->write($stkPushSimulation);
                    $stkPushSimulation = json_decode($stkPushSimulation);
                    $json['response'] = $stkPushSimulation;
                }
                //}
            }

            if (isset($stkPushSimulation->ResponseCode) && 0 == $stkPushSimulation->ResponseCode) {
                //save in
                $order_info['order_id'] = 0;
                $this->model_payment_mpesa->addOrderMobile($order_info, $stkPushSimulation->MerchantRequestID, $stkPushSimulation->CheckoutRequestID, $this->customer->getId(), 0, $kb_order_reference_number);
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
        $log = new Log('error.log');
        $log->write('MpesaCheckout json');
        $log->write($json);
        $log->write('MpesaCheckout json');
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
            foreach ($data['order_reference_number'] as $key => $order_reference_number) {
                $log = new Log('error.log');
                $log->write('COMPLETE key' . ' ' . 'order_reference_number');
                $log->write($key . ' ' . $order_reference_number);
                $log->write('COMPLETE key' . ' ' . 'order_reference_number');

                //if ($key == 75) {
                $order_reference_number = $order_reference_number;
                $number = $data['mpesa_phonenumber'];
                $log = new Log('error.log');
                $log->write($data);

                $mpesaDetails = $this->model_payment_mpesa->getMpesaByOrderReferenceNumber($order_reference_number);
                $log->write('mpesaDetails');
                $log->write($mpesaDetails);
                $log->write('mpesaDetails');
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
                        $transaction_details = $this->model_payment_mpesa->getOrderTransactionDetails($mpesaDetails['order_reference_number']);

                        if (is_array($transaction_details) && count($transaction_details) <= 0) {
                            $this->model_payment_mpesa->insertMpesaOrderTransaction($mpesaDetails['order_id'], $mpesaDetails['order_reference_number'], $stkPushSimulation->CheckoutRequestID);
                        }

                        $json['status'] = true;
                        $json['message'] = 'Payment Successfull.';
                        $json['mpesa_response'] = $stkPushSimulation;
                    }
                }
                //}
            }
        } else {
            $json['status'] = 10014;

            foreach ($this->error as $key => $value) {
                $json['message'][] = ['type' => $key, 'body' => $value];
            }

            http_response_code(400);
        }
        $log = new Log('error.log');
        $log->write('MpesaCheckoutComplete json');
        $log->write($json);
        $log->write('MpesaCheckoutComplete json');
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validatetopup($data) {
        // ['payment_type'] == 'topup'
        if (empty($data['payment_method'])) {
            $this->error['payment_method'] = 'Payment method required!';
        }


        if (empty($data['payment_method_code'])) {
            $this->error['payment_method_code'] = 'Payment method code required!';
        }

        if (empty($data['mpesa_phonenumber'])) {
            $this->error['mpesa_phonenumber'] = 'Phone number required!';
        }

        if (empty($data['customer_id'])) {
            $this->error['customer_id'] = 'Customer ID required!';
        }

        if (empty($data['customer_reference_number'])) {
            $this->error['customer_reference_number'] = 'Customer reference number required!';
        }

        if (empty($data['amount'])) {
            $this->error['amount'] = 'Amount required!';
        }

        /* if (empty($data['merchant_request_id'])) {
          $this->error['merchant_request_id'] = 'Merchant request id required!';
          }

          if (empty($data['checkout_request_id'])) {
          $this->error['checkout_request_id'] = 'Checkout request id required!';
          } */

        return !$this->error;
    }

    protected function validatetopupcomplete($data) {
        // ['payment_type'] == 'topup'
        if (empty($data['payment_method'])) {
            $this->error['payment_method'] = 'Payment method required!';
        }


        if (empty($data['payment_method_code'])) {
            $this->error['payment_method_code'] = 'Payment method code required!';
        }

        if (empty($data['mpesa_phonenumber'])) {
            $this->error['mpesa_phonenumber'] = 'Phone number required!';
        }

        if (empty($data['customer_id'])) {
            $this->error['customer_id'] = 'Customer ID required!';
        }

        if (empty($data['customer_reference_number'])) {
            $this->error['customer_reference_number'] = 'Customer reference number required!';
        }

        // if (empty($data['amount'])) {
        //     $this->error['amount'] = 'Amount required!';
        // }

        /* if (empty($data['merchant_request_id'])) {
          $this->error['merchant_request_id'] = 'Merchant request id required!';
          }

          if (empty($data['checkout_request_id'])) {
          $this->error['checkout_request_id'] = 'Checkout request id required!';
          } */

        return !$this->error;
    }

    public function addMpesaTopup($data = []) {
        $json['status'] = false;

        $this->load->language('payment/mpesa');
        $this->load->model('sale/order');
        $this->load->model('payment/mpesa');
        $this->load->model('checkout/order');
        $kb_order_reference_number = NULL;

        if ($this->validatetopup($data)) {
            $customer_id = $data['customer_id'];
            $number = $data['mpesa_phonenumber'];
            $amount = $data['amount'];
            $order_reference_number = $data['customer_reference_number']; //order_reference_number
            $kb_order_reference_number = $order_reference_number;
            $log = new Log('error.log');

            $log->write($data);
            /* start */
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
                // $CallBackURL = $this->url->link('deliversystem/deliversystem/mpesamobileOrderStatusTransactionss', '', 'SSL');mpesaOrderStatus
                $CallBackURL = $this->url->link('deliversystem/deliversystem/mpesamobileTopupStatus', '', 'SSL');
                $Amount = $amount;
                $PartyB = $this->config->get('mpesa_business_short_code');
                $PhoneNumber = $this->config->get('config_telephone_code') . '' . $number;
                //$AccountReference = 'GPK'; //$this->config->get('config_name');

                $AccountReference = $this->customer->getId() . 'WALLET_TOPUP';
                // $AccountReference = "#" . $order_reference_number; //$this->config->get('config_name');
                $TransactionDesc = "#" . $customer_id;
                $Remarks = 'Wallet TOPUP';
                $log->write($BusinessShortCode . 'x' . $LipaNaMpesaPasskey . 'x' . $TransactionType . 'amount' . $Amount . 'x' . $PartyA . 'x' . $PartyB . 'x' . $PhoneNumber . 'x' . $CallBackURL . 'x' . $AccountReference . 'x' . $TransactionDesc . 'x' . $Remarks);
                $stkPushSimulation = $mpesa->STKPushSimulation($BusinessShortCode, $LipaNaMpesaPasskey, $TransactionType, $Amount, $PartyA, $PartyB, $PhoneNumber, $CallBackURL, $AccountReference, $TransactionDesc, $Remarks);
                $log->write('STKPushSimulation');
                $log->write($stkPushSimulation);
                $stkPushSimulation = json_decode($stkPushSimulation);
                $json['response'] = $stkPushSimulation;
            }

            if (isset($stkPushSimulation->ResponseCode) && 0 == $stkPushSimulation->ResponseCode) {
                //save in
                $order_info['order_id'] = 0;
                $this->model_payment_mpesa->addOrderMobile($order_info, $stkPushSimulation->MerchantRequestID, $stkPushSimulation->CheckoutRequestID, $this->customer->getId(), $Amount, $kb_order_reference_number);
                // $this->model_payment_mpesa->addOrder(0, $stkPushSimulation->MerchantRequestID, $stkPushSimulation->CheckoutRequestID, $this->customer->getId(), $amount);

                $json['status'] = true;
                $json['message'] = sprintf($this->language->get('text_sms_sent'), $number);
            } else {
                //failing orders from api
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

    public function addMpesaTopupComplete($data = []) {
        $json['status'] = false;

        $this->load->language('payment/mpesa');
        $this->load->model('sale/order');
        $this->load->model('payment/mpesa');
        $this->load->model('checkout/order');
        $this->load->model('account/customer');

        if ($this->validatetopupcomplete($data)) {
            $customer_id = $data['customer_id'];
            $number = $data['mpesa_phonenumber'];
            $order_reference_number = $data['customer_reference_number'];
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
                    $transaction_details = $this->model_payment_mpesa->getOrderTransactionDetails($mpesaDetails['order_reference_number']);

                    if (is_array($transaction_details) && count($transaction_details) <= 0) {
                        // $this->model_payment_mpesa->insertMpesaOrderTransaction($mpesaDetails['order_id'], $mpesaDetails['order_reference_number'], $stkPushSimulation->CheckoutRequestID);
                        $this->model_payment_mpesa->insertMpesaCustomerTransaction($mpesaDetails['order_id'], $mpesaDetails['customer_id'], $mpesaDetails['order_reference_number'], $stkPushSimulation->CheckoutRequestID);
                    }
                    // lll
                    // $this->model_payment_mpesa->insertCustomerTransactionId($customer_id, $stkPushSimulation->CheckoutRequestID);
                    // // $this->model_payment_mpesa->addOrderHistoryTransaction($order_id, $this->config->get('mpesa_order_status_id'), $customer_info['customer_id'], 'customer', $order_info['order_status_id'], 'mPesa Online', 'mpesa');
                    // $this->model_payment_mpesa->addCustomerHistoryTransaction($customer_id, $this->config->get('mpesa_order_status_id'), $amount_topup, 'mPesa Online', 'mpesa', $stkPushSimulation->MerchantRequestID);


                    $json['status'] = true;
                    $json['message'] = 'Topup Successfull.';
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

    public function mpesacheckout($orders) {

        $json['status'] = false;

        if (isset($orders) && isset($orders['mpesa_phonenumber']) && $orders['mpesa_phonenumber'] != NULL) {
            $this->load->language('payment/mpesa');
            $this->load->model('sale/order');
            $this->load->model('payment/mpesa');
            $this->load->model('checkout/order');

            $log = new Log('error.log');
            $number = $orders['mpesa_phonenumber'];
            $order_ids = '#' . implode("#", $orders);

            /* $amount = $this->cart->getTotalForKwikBasket();
              $amount = $this->cart->getTotal(); */
            $amount = $this->cart->getTotalWithShipping();
            $live = 'true';
            $mpesa = new \Safaricom\Mpesa\Mpesa($this->config->get('mpesa_customer_key'), $this->config->get('mpesa_customer_secret'), $this->config->get('mpesa_environment'), $live);
            $sta = false;

            $log->write('STKPushSimulation confirm');
            $log->write($sta);
            if (!$sta) {
                $PartyA = $this->config->get('config_telephone_code') . '' . $number;

                $BusinessShortCode = $this->config->get('mpesa_business_short_code');
                $LipaNaMpesaPasskey = $this->config->get('mpesa_lipanampesapasskey');
                $TransactionType = 'CustomerPayBillOnline';
                $CallBackURL = $this->url->link('deliversystem/deliversystem/mpesamobileOrderStatusTransactionss', '', 'SSL');
                $Amount = $amount;
                $PartyB = $this->config->get('mpesa_business_short_code');
                $PhoneNumber = $this->config->get('config_telephone_code') . '' . $number;
                $AccountReference = $order_ids;
                $TransactionDesc = $order_ids;
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
                $order_info['order_id'] = 0;
                $this->model_payment_mpesa->addOrderMobile($order_info, $stkPushSimulation->MerchantRequestID, $stkPushSimulation->CheckoutRequestID, $this->customer->getId(), 0, $kb_order_reference_number);
                $json['status'] = true;
                $json['status_code'] = 200;
                $json['message'] = sprintf($this->language->get('text_sms_sent'), $number);
            } else {
                $json['status_code'] = 400;
                $json['status'] = false;
                $json['message'] = '';
                //failing orders from api
            }
        } else {
            $json['status_code'] = 400;
            $json['message'] = 'Invalid Phone Number!';
        }

        $log->write('MpesaCheckout json');
        $log->write($json);
        $log->write('MpesaCheckout json');
        return $json;
    }

    public function auth() {

        $log = new Log('error.log');

        $mpesa_customer_key = $this->config->get('mpesa_customer_key');
        $mpesa_customer_secret = $this->config->get('mpesa_customer_secret');
        $timestamp = '20' . date('ymdhis');

        $password = 'Basic ' . base64_encode($mpesa_customer_key . ':' . $mpesa_customer_secret . $timestamp);
        $password_new = 'Basic ' . base64_encode($mpesa_customer_key . ':' . $mpesa_customer_secret);

        $log->write($password);
        $log->write($password_new);
        $log->write($mpesa_customer_key);
        $log->write($mpesa_customer_secret);
        $log->write($timestamp);

        $curl = curl_init();
        if ($this->config->get('mpesa_environment') == 'live') {
            $log->write('MPESA_PRODUCTION');
            $log->write($this->config->get('mpesa_environment'));
            curl_setopt($curl, CURLOPT_URL, 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials');
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization:' . $password_new));
        } else {
            $log->write('MPESA_PRODUCTION');
            $log->write($this->config->get('mpesa_environment'));
            curl_setopt($curl, CURLOPT_URL, 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials');
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization:' . $password));
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 0);
        //curl_setopt($curl, CURLOPT_POSTFIELDS, $body); //Setting post data as xml
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($curl);

        $log->write($result);
        curl_close($curl);
        $result = json_decode($result, true);
        return $result;
        /* $json['status'] = true;
          $json['data'] = $result;

          $this->response->addHeader('Content-Type: application/json');
          $this->response->setOutput(json_encode($json)); */
    }

    public function addRegisterUrl($data = []) {

        $json['status'] = false;

        $log = new Log('error.log');
        $access_token = $this->auth();

        $log->write($access_token);

        if (isset($access_token) && isset($access_token['access_token']) && $access_token['access_token'] != NULL) {
            $token = 'Bearer ' . $access_token['access_token'];
            $curl = curl_init();
            if ($this->config->get('mpesa_environment') == 'live') {
                $log->write('MPESA_PRODUCTION');
                $log->write($this->config->get('mpesa_environment'));
                //curl_setopt($curl, CURLOPT_URL, 'https://api.safaricom.co.ke/mpesa/c2b/v1/registerurl');
                curl_setopt($curl, CURLOPT_URL, 'https://api.safaricom.co.ke/mpesa/c2b/v2/registerurl');
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:' . $token));
            } else {
                $log->write('MPESA_PRODUCTION');
                $log->write($this->config->get('mpesa_environment'));
                curl_setopt($curl, CURLOPT_URL, 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl');
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:' . $token));
            }

            $curl_post_data = array(
                'ShortCode' => $this->config->get('mpesa_business_short_code'),
                'ResponseType' => "[Completed/Cancelled]",
                'ConfirmationURL' => $this->url->link('deliversystem/deliversystem/paymentsconfirmation', '', 'SSL'),
                'ValidationURL' => $this->url->link('deliversystem/deliversystem/paymentsvalidation', '', 'SSL'),
            );

            $log->write('curl_post_data');
            $log->write($this->url->link('deliversystem/deliversystem/paymentsconfirmation', '', 'SSL'));
            $log->write($this->url->link('deliversystem/deliversystem/paymentsvalidation', '', 'SSL'));
            $log->write($curl_post_data);
            $log->write('curl_post_data');

            $data_string = json_encode($curl_post_data);

            $log->write('curl_post_data_2');
            $log->write($curl_post_data);
            $log->write('curl_post_data_2');

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $result = curl_exec($curl);

            $log->write($result);
            curl_close($curl);
            $result = json_decode($result, true);
            $json['data'] = $result;
            $json['status'] = true;
            $json['status_code'] = 200;
        } else {
            $json['status_code'] = 400;
            $json['data'] = NULL;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
