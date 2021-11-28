<?php

class ControllerPaymentTwoCheckout extends Controller
{
    public function index()
    {
        $data['button_confirm'] = $this->language->get('button_confirm');

        $this->load->model('checkout/order');

        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        //get address
        $this->load->model('account/address');
        $this->load->model('account/customer');

        $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);

        if ($customer_info) {
            $address_id = $customer_info['address_id'];
        } else {
            $address_id = 0;
        }

        $address_info = $this->model_account_address->getAddress($address_id);

        if ($address_info) {
            $city = $address_info['city'];
            $address = $address_info['address'].', '.$address_info['city'];
        } else {
            $city = '';
            $address = '';
        }

        $data['action'] = 'https://www.2checkout.com/checkout/purchase';

        $data['sid'] = $this->config->get('twocheckout_account');
        $data['currency_code'] = $order_info['currency_code'];
        $data['total'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
        $data['cart_order_id'] = $this->session->data['order_id'];
        $data['card_holder_name'] = $order_info['firstname'].' '.$order_info['lastname'];
        $data['street_address'] = $address;
        $data['city'] = $city;

        if ('US' == $order_info['payment_iso_code_2'] || 'CA' == $order_info['payment_iso_code_2']) {
            $data['state'] = $order_info['payment_zone'];
        } else {
            $data['state'] = 'XX';
        }

        $data['zip'] = $order_info['payment_postcode'];
        $data['country'] = $order_info['payment_country'];
        $data['email'] = $order_info['email'];
        $data['phone'] = $order_info['telephone'];

        $data['ship_street_address'] = $order_info['shipping_address'];
        $data['ship_city'] = '';
        $data['ship_state'] = '';
        $data['ship_zip'] = '';
        $data['ship_country'] = $this->config->get('config_country_code');

        $data['products'] = [];

        $products = $this->cart->getProducts();

        foreach ($products as $product) {
            $data['products'][] = [
                'product_id' => $product['product_id'],
                'name' => $product['name'],
                'description' => $product['name'],
                'quantity' => $product['quantity'],
                'price' => $this->currency->format($product['price'], $order_info['currency_code'], $order_info['currency_value'], false),
            ];
        }

        if ($this->config->get('twocheckout_test')) {
            $data['demo'] = 'Y';
        } else {
            $data['demo'] = '';
        }

        if ($this->config->get('twocheckout_display')) {
            $data['display'] = 'Y';
        } else {
            $data['display'] = '';
        }

        $data['lang'] = $this->session->data['language'];

        $data['return_url'] = $this->url->link('payment/twocheckout/callback', '', 'SSL');

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/payment/twocheckout.tpl')) {
            return $this->load->view($this->config->get('config_template').'/template/payment/twocheckout.tpl', $data);
        } else {
            return $this->load->view('default/template/payment/twocheckout.tpl', $data);
        }
    }

    public function callback()
    {
        $this->load->model('checkout/order');

        $order_info = $this->model_checkout_order->getOrder($this->request->post['cart_order_id']);

        if (!$this->config->get('twocheckout_test')) {
            $order_number = $this->request->post['order_number'];
        } else {
            $order_number = '1';
        }

        if (strtoupper(md5($this->config->get('twocheckout_secret').$this->config->get('twocheckout_account').$order_number.$this->request->post['total'])) == $this->request->post['key']) {
            if ($this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false) == $this->request->post['total']) {
                $this->model_checkout_order->addOrderHistory($this->request->post['cart_order_id'], $this->config->get('twocheckout_order_status_id'));
            } else {
                $this->model_checkout_order->addOrderHistory($this->request->post['cart_order_id'], $this->config->get('config_order_status_id')); // Ugh. Some one've faked the sum. What should we do? Probably drop a mail to the shop owner?
            }

            // We can't use $this->response->redirect() here, because of 2CO behavior. It fetches this page
            // on behalf of the user and thus user (and his browser) see this as located at 2checkout.com
            // domain. So user's cookies are not here and he will see empty basket and probably other
            // weird things.
            if ($this->request->server['HTTPS']) {
                $server = $this->config->get('config_ssl');
            } else {
                $server = $this->config->get('config_url');
            }

            echo '<html>'."\n";
            echo '<head>'."\n";
            echo '  <meta http-equiv="Refresh" content="0; url='.$server.'checkout-success'.'">'."\n";
            echo '</head>'."\n";
            echo '<body>'."\n";
            echo '  <p>Please follow <a href="'.$server.'checkout-success'.'">link</a>!</p>'."\n";
            echo '</body>'."\n";
            echo '</html>'."\n";
            exit();
        } else {
            echo 'The response from 2checkout.com can\'t be parsed. Contact site administrator, please!';
        }
    }
}
