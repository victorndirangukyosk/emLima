<?php

// Nochex via form will work for both simple "Seller" account and "Merchant" account holders
// Nochex via APC maybe only avaiable to "Merchant" account holders only - site docs a bit vague on this point
class ControllerPaymentNochex extends Controller
{
    public function index()
    {
        $this->load->language('payment/nochex');

        $data['button_confirm'] = $this->language->get('button_confirm');

        $this->load->model('checkout/order');

        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        $data['action'] = 'https://secure.nochex.com/';

        // Nochex minimum requirements
        // The merchant ID is usually your Nochex registered email address but can be altered for "Merchant" accounts see below
        if ($this->config->get('nochex_email') != $this->config->get('nochex_merchant')) {
            // This MUST be changed on your Nochex account!!!!
            $data['merchant_id'] = $this->config->get('nochex_merchant');
        } else {
            $data['merchant_id'] = $this->config->get('nochex_email');
        }

        $data['amount'] = $this->currency->format($order_info['total'], 'GBP', false, false);
        $data['order_id'] = $this->session->data['order_id'];
        $data['description'] = $this->config->get('config_name');

        $data['billing_fullname'] = $order_info['firstname'].' '.$order_info['lastname'];

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
            $data['billing_address'] = $address_info['address'].', '.$address_info['city'];
        } else {
            $data['billing_address'] = '';
        }

        $data['billing_postcode'] = '';

        $data['delivery_fullname'] = $order_info['shipping_name'];
        $data['delivery_address'] = $order_info['shipping_address']."\r\n";
        $data['delivery_postcode'] = '';

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['email_address'] = $order_info['email'];
        $data['customer_phone_number'] = $order_info['telephone'];
        $data['test'] = $this->config->get('nochex_test');
        $data['success_url'] = $server.'checkout-success'; //$this->url->link('checkout/success', '', 'SSL');
        $data['cancel_url'] = $this->url->link('checkout/payment', '', 'SSL');
        $data['declined_url'] = $this->url->link('payment/nochex/callback', 'method=decline', 'SSL');
        $data['callback_url'] = $this->url->link('payment/nochex/callback', 'order='.$this->session->data['order_id'], 'SSL');

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/payment/nochex.tpl')) {
            return $this->load->view($this->config->get('config_template').'/template/payment/nochex.tpl', $data);
        } else {
            return $this->load->view('default/template/payment/nochex.tpl', $data);
        }
    }

    public function callback()
    {
        $this->load->language('payment/nochex');

        if (isset($this->request->get['method']) && 'decline' == $this->request->get['method']) {
            $this->session->data['error'] = $this->language->get('error_declined');

            $this->response->redirect($this->url->link('checkout/cart'));
        }

        if (isset($this->request->post['order_id'])) {
            $order_id = $this->request->post['order_id'];
        } else {
            $order_id = 0;
        }

        $this->load->model('checkout/order');

        $order_info = $this->model_checkout_order->getOrder($order_id);

        if (!$order_info) {
            $this->session->data['error'] = $this->language->get('error_no_order');

            $this->response->redirect($this->url->link('checkout/cart'));
        }

        // Fraud Verification Step.
        $request = '';

        foreach ($this->request->post as $key => $value) {
            $request .= '&'.$key.'='.urlencode(stripslashes($value));
        }

        $curl = curl_init('https://www.nochex.com/nochex.dll/apc/apc');

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, trim($request, '&'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($curl);

        curl_close($curl);

        if (0 == strcmp($response, 'AUTHORISED')) {
            $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('nochex_order_status_id'));
        } else {
            $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('config_order_status_id'), 'Auto-Verification step failed. Manually check the transaction.');
        }

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        // Since it returned, the customer should see success.
        // It's up to the store owner to manually verify payment.
        $this->response->redirect($server.'checkout-success');
    }
}
