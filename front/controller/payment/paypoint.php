<?php

class ControllerPaymentPaypoint extends Controller {

    public function index() {
        $data['button_confirm'] = $this->language->get('button_confirm');

        $this->load->model('checkout/order');

        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        $data['merchant'] = $this->config->get('paypoint_merchant');
        $data['trans_id'] = $this->session->data['order_id'];
        $data['amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);

        if ($this->config->get('paypoint_password')) {
            $data['digest'] = md5($this->session->data['order_id'] . $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false) . $this->config->get('paypoint_password'));
        } else {
            $data['digest'] = '';
        }


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
            $address = $address_info['address'] . ', ' . $address_info['city'];
        } else {
            $city = '';
            $address = '';
        }

        $data['bill_name'] = $order_info['firstname'] . ' ' . $order_info['lastname'];
        $data['bill_addr_1'] = $address;
        $data['bill_addr_2'] = '';
        $data['bill_city'] = $city;
        $data['bill_state'] = $this->config->get('config_state');
        $data['bill_post_code'] = '';
        $data['bill_country'] = $this->config->get('config_country');
        $data['bill_tel'] = $order_info['telephone'];
        $data['bill_email'] = $order_info['email'];

        $data['ship_name'] = $order_info['shipping_name'];
        $data['ship_addr_1'] = $order_info['shipping_address'];
        $data['ship_addr_2'] = '';
        $data['ship_city'] = '';
        $data['ship_state'] = $this->config->get('config_state');
        $data['ship_post_code'] = $order_info['shipping_postcode'];
        $data['ship_country'] = $this->config->get('config_country');

        $data['currency'] = $this->currency->getCode();
        $data['callback'] = $this->url->link('payment/paypoint/callback', '', 'SSL');

        switch ($this->config->get('paypoint_test')) {
            case 'live':
                $status = 'live';
                break;
            case 'successful':
            default:
                $status = 'true';
                break;
            case 'fail':
                $status = 'false';
                break;
        }

        $data['options'] = 'test_status=' . $status . ',dups=false,cb_post=false';

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/paypoint.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/payment/paypoint.tpl', $data);
        } else {
            return $this->load->view('default/template/payment/paypoint.tpl', $data);
        }
    }

    public function callback() {
        if (isset($this->request->get['trans_id'])) {
            $order_id = $this->request->get['trans_id'];
        } else {
            $order_id = 0;
        }

        $this->load->model('checkout/order');

        $order_info = $this->model_checkout_order->getOrder($order_id);

        // Validate the request is from PayPoint
        if ($this->config->get('paypoint_password')) {
            if (!empty($this->request->get['hash'])) {
                $status = ($this->request->get['hash'] == md5(str_replace('hash=' . $this->request->get['hash'], '', htmlspecialchars_decode($this->request->server['REQUEST_URI'], ENT_COMPAT)) . $this->config->get('paypoint_password')));
            } else {
                $status = false;
            }
        } else {
            $status = true;
        }

        if ($order_info) {
            $this->load->language('payment/paypoint');

            if ($this->request->server['HTTPS']) {
                $server = $this->config->get('config_ssl');
            } else {
                $server = $this->config->get('config_url');
            }

            $data['title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));

            if (!$this->request->server['HTTPS']) {
                $data['base'] = $server;
            } else {
                $data['base'] = $server;
            }

            $data['language'] = $this->language->get('code');
            $data['direction'] = $this->language->get('direction');

            $data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));

            $data['text_response'] = $this->language->get('text_response');
            $data['text_success'] = $this->language->get('text_success');
            $data['text_success_wait'] = sprintf($this->language->get('text_success_wait'), $server.'checkout-success');
            $data['text_failure'] = $this->language->get('text_failure');
            $data['text_failure_wait'] = sprintf($this->language->get('text_failure_wait'), $this->url->link('checkout/cart'));

            if (isset($this->request->get['code']) && $this->request->get['code'] == 'A' && $status) {
                $message = '';

                if (isset($this->request->get['code'])) {
                    $message .= 'code: ' . $this->request->get['code'] . "\n";
                }

                if (isset($this->request->get['auth_code'])) {
                    $message .= 'auth_code: ' . $this->request->get['auth_code'] . "\n";
                }

                if (isset($this->request->get['ip'])) {
                    $message .= 'ip: ' . $this->request->get['ip'] . "\n";
                }

                if (isset($this->request->get['cv2avs'])) {
                    $message .= 'cv2avs: ' . $this->request->get['cv2avs'] . "\n";
                }

                if (isset($this->request->get['valid'])) {
                    $message .= 'valid: ' . $this->request->get['valid'] . "\n";
                }

                $this->load->model('checkout/order');

                $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('paypoint_order_status_id'), $message, false);

                if ($this->request->server['HTTPS']) {
                    $server = $this->config->get('config_ssl');
                } else {
                    $server = $this->config->get('config_url');
                }

                $data['continue'] = $server.'checkout-success';
                //$this->url->link('checkout/success');

                $data['column_left'] = $this->load->controller('common/column_left');
                $data['column_right'] = $this->load->controller('common/column_right');
                $data['content_top'] = $this->load->controller('common/content_top');
                $data['content_bottom'] = $this->load->controller('common/content_bottom');
                $data['footer'] = $this->load->controller('common/footer');
                $data['header'] = $this->load->controller('common/header');

                if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/paypoint_success.tpl')) {
                    $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/paypoint_success.tpl', $data));
                } else {
                    $this->response->setOutput($this->load->view('default/template/payment/paypoint_success.tpl', $data));
                }
            } else {
                $data['continue'] = $this->url->link('checkout/cart');

                $data['column_left'] = $this->load->controller('common/column_left');
                $data['column_right'] = $this->load->controller('common/column_right');
                $data['content_top'] = $this->load->controller('common/content_top');
                $data['content_bottom'] = $this->load->controller('common/content_bottom');
                $data['footer'] = $this->load->controller('common/footer');
                $data['header'] = $this->load->controller('common/header');

                if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/paypoint_failure.tpl')) {
                    $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/paypoint_failure.tpl', $data));
                } else {
                    $this->response->setOutput($this->load->view('default/template/payment/paypoint_failure.tpl', $data));
                }
            }
        }
    }
}
