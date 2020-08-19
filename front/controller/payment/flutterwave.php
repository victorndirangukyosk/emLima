<?php

class ControllerPaymentFlutterwave extends Controller {

    public function index() {
        $this->load->language('payment/flutterwave');

        $this->load->model('setting/setting');
        $this->load->model('payment/flutterwave');
        $this->load->model('checkout/order');
        $this->load->model('payment/flutterwavepaymentoptions');

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

        $data['payment_options'] = $this->model_payment_flutterwavepaymentoptions->getpaymentoptions();

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

        if ($this->session->data['payment_method']['code'] == 'flutterwave' || $this->request->post['payment_method'] == 'flutterwave') {

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

            foreach ($this->session->data['order_id'] as $order_id) {
                $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('flutterwave_failed_order_status_id'));
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

        if ($this->session->data['payment_method']['code'] == 'flutterwave' || $this->request->post['payment_method'] == 'flutterwave') {

            $this->load->language('payment/flutterwave');
            $this->load->model('payment/flutterwave');
            $this->load->model('payment/flutterwavetransactions');
            $this->load->model('checkout/order');
            $this->load->model('setting/setting');
            $this->load->model('account/customer');
            $log = new Log('error.log');

            foreach ($this->session->data['order_id'] as $key => $value) {
                $order_id = $value;
            }

            if (isset($this->request->post['order_id'])) {
                $order_id = $this->request->post['order_id'];
            }
            $flutter_creds = $this->model_setting_setting->getSetting('flutterwave', 0);
            $public_key = $flutter_creds['flutterwave_secret_key']; // get your public key from the dashboard.
            $transaction_id = $this->request->get['transaction_id'];
            $order_info = $this->model_checkout_order->getOrder($order_id);
            $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);

            if ($this->request->get['status'] == 'successful') {
                $curl = curl_init();

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

                $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('flutterwave_order_status_id'));
                $this->model_checkout_order->UpdateOrderStatusFlutterWave($order_id, $this->config->get('flutterwave_order_status_id'), $customer_info['customer_id']);
                $this->model_payment_flutterwave->insertOrderTransactionId($order_id, $transaction_id);
                $this->model_payment_flutterwavetransactions->addOrderTransaction($transaction['data'], $order_id);
                $log->write($transaction);
                $this->response->redirect($this->url->link('checkout/success'));
            }

            if ($this->request->get['status'] == 'cancelled' || $this->request->get['status'] != 'successful') {

                $flutterwaveDetails = $this->model_payment_flutterwave->getFlutterwaveByOrderId($order_id, $this->request->get['tx_ref']);
                if ($flutterwaveDetails != NULL) {
                    $this->model_payment_flutterwave->updateFlutterwaveOrder($order_id, $this->request->get['tx_ref'], $this->request->get['transaction_id'], $this->request->get['status']);
                }
                $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('flutterwave_failed_order_status_id'));
                $this->model_checkout_order->UpdateOrderStatusFlutterWave($order_id, $this->config->get('flutterwave_failed_order_status_id'), $customer_info['customer_id']);
                $this->response->redirect($this->url->link('checkout/success/orderfailed'));
            }
        }
    }

}
