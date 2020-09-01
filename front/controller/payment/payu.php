<?php

class ControllerPaymentPayu extends Controller
{
    public function index()
    {
        $data['button_confirm'] = $this->language->get('button_confirm');

        $this->load->model('checkout/order');
        $this->language->load('payment/payu');

        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        $data['merchant'] = $this->config->get('payu_merchant');

        /////////////////////////////////////Start Payu Vital  Information /////////////////////////////////

        if ('demo' == $this->config->get('payu_test')) {
            $data['action'] = 'https://test.payu.in/_payment.php';
        } else {
            $data['action'] = 'https://secure.payu.in/_payment.php';
        }

        $log = new Log('error.log');
        $log->write('inside payu');
        $log->write($this->session->data['order_id']);
        $log->write($this->config->get('payu_order_prefix'));
        $log->write($data);
        //$txnid = $this->session->data['order_id'];
        $txnid = $this->config->get('payu_order_prefix').$this->session->data['order_id'][8];

        $log->write($this->config->get('payu_order_prefix'));

        $log->write('5555');

        $data['key'] = $this->config->get('payu_merchant');
        $data['salt'] = $this->config->get('payu_salt');
        $data['txnid'] = $txnid;
        $data['amount'] = (int) $order_info['total'];
        $data['productinfo'] = 'opencart products information';
        $data['firstname'] = $order_info['firstname'];
        $data['Lastname'] = $order_info['lastname'];
        $data['Zipcode'] = '';
        $data['email'] = $order_info['email'];
        $data['phone'] = $order_info['telephone'];
        $data['address1'] = '';
        $data['address2'] = '';
        $data['state'] = '';
        $data['city'] = '';
        $data['country'] = '';
        $data['Pg'] = 'CC';

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['surl'] = $server.'index.php?path=payment/payu/callback';
        $data['Furl'] = $server.'index.php?path=payment/payu/callback';
        $data['curl'] = $server.'index.php?path=checkout/checkout';
        $key = $this->config->get('payu_merchant');
        $amount = (int) $order_info['total'];
        $productInfo = $data['productinfo'];
        $firstname = $order_info['firstname'];
        $email = $order_info['email'];
        $salt = $this->config->get('payu_salt');

        $Hash = hash('sha512', $key.'|'.$txnid.'|'.$amount.'|'.$productInfo.'|'.$firstname.'|'.$email.'|||||||||||'.$salt);

        $data['user_credentials'] = $data['key'].':'.$data['email'];
        $data['Hash'] = $Hash;

        /////////////////////////////////////End Payu Vital  Information /////////////////////////////////
        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/payment/payu.tpl')) {
            return $this->load->view($this->config->get('config_template').'/template/payment/payu.tpl', $data);
        } else {
            return $this->load->view('default/template/payment/payu.tpl', $data);
        }
    }

    public function callback()
    {
        if (isset($this->request->post['key']) && ($this->request->post['key'] == $this->config->get('payu_merchant'))) {
            $this->language->load('payment/payu');

            $data['title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));

            if (!isset($this->request->server['HTTPS']) || ('on' != $this->request->server['HTTPS'])) {
                $data['base'] = HTTP_SERVER;
            } else {
                $data['base'] = HTTPS_SERVER;
            }

            $this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_checkout.css');

            $data['charset'] = $this->language->get('charset');
            $data['language'] = $this->language->get('code');
            $data['direction'] = $this->language->get('direction');
            $data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));
            $data['text_response'] = $this->language->get('text_response');
            $data['text_success'] = $this->language->get('text_success');

            if ($this->request->server['HTTPS']) {
                $server = $this->config->get('config_ssl');
            } else {
                $server = $this->config->get('config_url');
            }

            $data['text_success_wait'] = sprintf($this->language->get('text_success_wait'), $server.'checkout-success');
            $data['text_failure'] = $this->language->get('text_failure');
            $data['text_cancelled'] = $this->language->get('text_cancelled');
            $data['text_cancelled_wait'] = sprintf($this->language->get('text_cancelled_wait'), $this->url->link('checkout/cart'));
            $data['text_pending'] = $this->language->get('text_pending');
            $data['text_failure_wait'] = sprintf($this->language->get('text_failure_wait'), $this->url->link('checkout/cart'));

            $this->load->model('checkout/order');
            //$order_id = $this->request->post['txnid'];
            $order_id = str_replace($this->config->get('payu_order_prefix'), '', $this->request->post['txnid']);

            $order_info = $this->model_checkout_order->getOrder($order_id);

            $key = $this->request->post['key'];
            $amount = $this->request->post['amount'];
            $productInfo = $this->request->post['productinfo'];
            $firstname = $this->request->post['firstname'];
            $email = $this->request->post['email'];
            $salt = $this->config->get('payu_salt');
            $txnid = $this->request->post['txnid'];
            $keyString = $key.'|'.$txnid.'|'.$amount.'|'.$productInfo.'|'.$firstname.'|'.$email.'||||||||||';
            $keyArray = explode('|', $keyString);
            $reverseKeyArray = array_reverse($keyArray);
            $reverseKeyString = implode('|', $reverseKeyArray);

            if (isset($this->request->post['status']) && 'success' == $this->request->post['status']) {
                $saltString = $salt.'|'.$this->request->post['status'].'|'.$reverseKeyString;
                $sentHashString = strtolower(hash('sha512', $saltString));
                $responseHashString = $this->request->post['hash'];

                $order_id = str_replace($this->config->get('payu_order_prefix'), '', $this->request->post['txnid']);

                $message = '';
                $message .= 'orderId: '.$this->request->post['txnid']."\n";
                $message .= 'Transaction Id: '.$this->request->post['mihpayid']."\n";
                foreach ($this->request->post as $k => $val) {
                    $message .= $k.': '.$val."\n";
                }
                if ($sentHashString == $this->request->post['hash']) {
                    //$this->model_checkout_order->confirm($order_id, $this->config->get('payu_order_status_id'));
                    //$this->model_checkout_order->update($order_id, $this->config->get('payu_order_status_id'), $message, false);

                    foreach ($this->session->data['order_id'] as $order_ids) {
                        $this->model_checkout_order->addOrderHistory($order_ids, $this->config->get('payu_order_status_id'));
                    }

                    /*$data['continue'] = $this->url->link('checkout/success');

                    $data['column_left'] = $this->load->controller('common/column_left');
                    $data['column_right'] = $this->load->controller('common/column_right');
                    $data['content_top'] = $this->load->controller('common/content_top');
                    $data['content_bottom'] = $this->load->controller('common/content_bottom');
                    $data['footer'] = $this->load->controller('common/footer');
                    $data['header'] = $this->load->controller('common/header/information');

                    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/payu_success.tpl')) {
                        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/payu_success.tpl', $data));
                    } else {
                        $this->response->setOutput($this->load->view('default/template/payment/payu_success.tpl', $data));
                    }*/

                    $this->response->redirect('checkout/success');
                } else {
                    //Transaction will be pending
                    //$this->model_checkout_order->confirm($order_id, 1);
                    //$this->model_checkout_order->update($order_id, 1, $message, false);
                    foreach ($this->session->data['order_id'] as $order_ids) {
                        $this->model_checkout_order->addOrderHistory($order_ids, $this->config->get('payu_order_status_id'), 'Payment pending for processing');
                    }
                    $data['continue'] = $this->url->link('checkout/checkout', '', 'SSL');

                    $data['column_left'] = $this->load->controller('common/column_left');
                    $data['column_right'] = $this->load->controller('common/column_right');
                    $data['content_top'] = $this->load->controller('common/content_top');
                    $data['content_bottom'] = $this->load->controller('common/content_bottom');
                    $data['footer'] = $this->load->controller('common/footer');
                    $data['header'] = $this->load->controller('common/header/information');

                    if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/payment/payu_pending.tpl')) {
                        $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/payment/payu_pending.tpl', $data));
                    } else {
                        $this->response->setOutput($this->load->view('default/template/payment/payu_pending.tpl', $data));
                    }
                }
            } else {
                $data['continue'] = $this->url->link('checkout/cart');

                $data['column_left'] = $this->load->controller('common/column_left');
                $data['column_right'] = $this->load->controller('common/column_right');
                $data['content_top'] = $this->load->controller('common/content_top');
                $data['content_bottom'] = $this->load->controller('common/content_bottom');
                $data['footer'] = $this->load->controller('common/footer');
                $data['header'] = $this->load->controller('common/header/information');

                if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/payment/payu_failure.tpl')) {
                    $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/payment/payu_failure.tpl', $data));
                } else {
                    $this->response->setOutput($this->load->view('default/template/payment/payu_failure.tpl', $data));
                }
            }
        }
    }
}
