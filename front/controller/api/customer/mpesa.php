<?php

require_once DIR_SYSTEM . '/vendor/mpesa-php-sdk-master/vendor/autoload.php';

class ControllerApiCustomerMpesa extends Controller {

    public function addMpesa($data = []) {
        $json['status'] = false;

        $this->load->language('payment/mpesa');
        $this->load->model('sale/order');
        $this->load->model('payment/mpesa');
        $this->load->model('checkout/order');

        $error_response = $this->validate($data);
        $log = new Log('error.log');
        $log->write($error_response);
        if ($this->validate($data)) {
            $orders = $data['orders'];
            $number = $data['mpesa_phonenumber'];

            $log = new Log('error.log');

            $log->write($data);
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

}
