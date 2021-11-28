<?php

class ModelApiPayment extends Model
{
    public function stripePayment($order_ids, $card_token)
    {
        $json = [];

        //echo "<pre>";print_r("send");die;
        $this->load->library('stripe');
        $this->load->model('checkout/order');
        $this->load->model('account/customer');
        $this->load->model('payment/stripe');

        $stripe_environment = $this->config->get('stripe_environment');

        //echo "<pre>";print_r($order_ids);die;

        foreach ($order_ids as $key => $value) {
            // code...
            $order_id = $value;
        }

        //$order_info = $this->model_checkout_order->getOrder($order_ids);
        $order_info = $this->model_checkout_order->getOrder($order_id);

        //echo "<pre>";print_r($order_info);die;
        if ($this->initStripe()) {
            //$use_existing_card = json_decode($this->request->post['existingCard']);

            //$saveCreditCard = $this->request->post['saveCreditCard'];
            //$card_token = $this->request->post['card'];

            $saveCreditCard = false;

            $use_existing_card = false;

            $stripe_customer_id = '';
            $stripe_charge_parameters = [
                'amount' => (int) ($order_info['total'] * 100),
                'currency' => $this->config->get('stripe_currency'),
                'metadata' => [
                    //'orderId' => $order_ids
                    'orderId' => $order_id,
                ],
                'transfer_group' => '{ORDERID#'.$order_id.'}',
            ];

            /*// Create a Charge:
            $charge = \Stripe\Charge::create(array(
              "amount" => 10000,
              "currency" => "usd",
              "source" => "tok_visa",
              //"transfer_group" => "{ORDER10}",
              "transfer_group" => "{ORDERID#".$order_id."}",
            ));*/

            $stripe_customer = $this->model_payment_stripe->getCustomer($this->customer->getId());

            // If customer is logged, but isn't registered as a customer in Stripe
            if ($this->customer->isLogged() && !$stripe_customer) {
                $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

                if (isset($customer_info['email']) && !empty($customer_info['email'])) {
                    $stripe_customer = \Stripe\Customer::create([
                        'email' => $customer_info['email'],
                        'metadata' => [
                            'customerId' => $this->customer->getId(),
                        ],
                    ]);

                    $this->model_payment_stripe->addCustomer(
                        $stripe_customer,
                        $this->customer->getId(),
                        $stripe_environment
                    );
                }
            }

            // If customer exists we use it

            //echo "<pre>";print_r($stripe_charge_parameters);die;

            $log = new Log('error.log');
            $log->write('model_payment_stripe');
            $log->write($stripe_customer);
            $log->write($use_existing_card);

            // May be the customer want to save its credit card
            if ($stripe_customer && (false == $use_existing_card)) {
                $log->write('if');
                $stripe_charge_parameters['customer'] = $stripe_customer['stripe_customer_id'];
                $customer = \Stripe\Customer::retrieve($stripe_customer['stripe_customer_id']);
                $stripe_card = $customer->sources->create(['source' => $card_token]);
                $stripe_charge_parameters['customer'] = $customer['id'];
                $stripe_charge_parameters['source'] = $stripe_card['id'];

                if ((bool) json_decode($saveCreditCard)) {
                    $log->write('saveCreditCard');
                    $this->model_payment_stripe->addCard(
                        $stripe_card,
                        $this->customer->getId(),
                        $stripe_environment
                    );
                }
            } else {
                $log->write('else');
                $stripe_charge_parameters['source'] = $card_token;
            }

            if ($use_existing_card && $stripe_customer) {
                $stripe_charge_parameters['customer'] = $stripe_customer['stripe_customer_id'];
            }

            $charge = \Stripe\Charge::create($stripe_charge_parameters);

            if (!json_decode($saveCreditCard) && isset($customer) && isset($stripe_card)) {
                $customer->sources->retrieve($stripe_card['id'])->delete();
            }

            if (isset($charge['id'])) {
                $this->model_payment_stripe->addOrder($order_info, $charge['id'], $stripe_environment);
                $message = 'Charge ID: '.$charge['id'].' Status:'.$charge['status'];
                //$this->model_checkout_order->addOrderHistory($order_ids, $this->config->get('stripe_order_status_id'), $message, false);

                foreach ($order_ids as $key => $value) {
                    //value is order id
                    $this->model_checkout_order->addOrderHistory($value, $this->config->get('stripe_order_status_id'), $message, false);
                }

                $json['processed'] = true;
            }
        } else {
            $json['error'] = 'Contact administrator';
        }

        return $json;
    }

    private function initStripe()
    {
        $this->load->library('stripe');
        if ('live' == $this->config->get('stripe_environment')) {
            $stripe_secret_key = $this->config->get('stripe_live_secret_key');
        } else {
            $stripe_secret_key = $this->config->get('stripe_test_secret_key');
        }

        if ('' != $stripe_secret_key && null != $stripe_secret_key) {
            \Stripe\Stripe::setApiKey($stripe_secret_key);

            return true;
        }

        return false;
    }
}
