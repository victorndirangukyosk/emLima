<?php

    /**
     * @copyright     	(c) 2017 iPay Limited. All rights reserved.
     * @author        	Moses King'ori <moses@intrepid.co.ke>
     * @license			This program is free software; you can redistribute it and/or modify
     *            		it under the terms of the GNU General Public License, version 2, as
     *              	published by the Free Software Foundation.
     *
     * 					This program is distributed in the hope that it will be useful,
     *      			but WITHOUT ANY WARRANTY; without even the implied warranty of
     *         			MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *            		GNU General Public License for more details.
     *
     * 					You should have received a copy of the GNU General Public License
     *      			along with this program; if not, write to the Free Software
     *         			Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
     */
    class ControllerPaymentiPayPaymentSoftware extends Controller
    {
        /**
         * [index gets all the parameters to be passed to iPay].
         *
         * @return [view] [It loads the iPay interface]
         */
        public function index()
        {
            $this->language->load('payment/iPay_payment_software');
            $this->load->model('checkout/order');

            $data['button_confirm'] = $this->language->get('button_confirm');

            //$order_info	= $this->model_checkout_order->getOrder($this->session->data['order_id']);

            $total = 0;
            foreach ($this->session->data['order_id'] as $order_id) {
                $order_info = $this->model_checkout_order->getOrder($order_id);

                $total += (int) $order_info['total'];
            }

            $order_ids = implode(',', $this->session->data['order_id']);
            //$data['print_order']	= $order_info;
            //$ipay_url 	= 'https://payments.ipayafrica.com/v3/ke';

            $ipay_url = $this->config->get('iPay_payment_software_ipay_url');

            if ('live' == $this->config->get('iPay_payment_software_mode')) {
                $live = 1;
            } elseif ('test' == $this->config->get('iPay_payment_software_mode')) {
                $live = 0;
            }

            $mpesa = $this->config->get('iPay_payment_software_mpesa_enabled');
            $airtel = $this->config->get('iPay_payment_software_airtel_enabled');
            $equity = $this->config->get('iPay_payment_software_equity_enabled');
            $mobilebanking = $this->config->get('iPay_payment_software_mobilebanking_enabled');
            $debitcard = $this->config->get('iPay_payment_software_debitcard_enabled');
            $creditcard = $this->config->get('iPay_payment_software_creditcard_enabled');
            $mkoporahisi = $this->config->get('iPay_payment_software_mkoporahisi_enabled');
            $saida = $this->config->get('iPay_payment_software_saida_enabled');

            $mm = 1;
            $mb = 1;
            $dc = 1;
            $cc = 1;

            $oid = $order_ids;
            $inv = $order_info['invoice_prefix'];
            //$ttl 	= $order_info['total'];
            $ttl = $total;
            $tel = $order_info['telephone'];
            $eml = $order_info['email'];

            /**
             * incase of any dashes in the telephone number the code below removes them.
             *
             * @var [string]
             */
            $tel = str_replace('-', '', $tel);
            $tel = str_replace([' ', '<', '>', '&', '{', '}', '*', '+', '!', '@', '#', '$', '%', '^', '&'], '', $tel);

            $vid = $this->config->get('iPay_payment_software_merchant_name');
            $curr = $order_info['currency_code'];

            /**
             * $p1, $p2, $p3, $p4  are optional fields. Allow sending & receiving your custom parameters
             * Each option should not exceed 20 characters.
             */
            $p1 = '';
            $p2 = '';
            $p3 = '';
            $p4 = '';

            /**
             * [$callbk holds the callback URL].
             */
            $callbk = $this->url->link('payment/iPay_payment_software/callback');
            //$callbk = $this->url->link($this->config->get('iPay_payment_software_callback_url'));

            $cst = 1;
            $crl = 0;

            /**
             * [$hsh holds the merchant's secret key].
             */
            $hsh = $this->config->get('iPay_payment_software_merchant_key');

            //The data string values
            $datastring = $live.$oid.$inv.$ttl.$tel.$eml.$vid.$curr.$p1.$p2.$p3.$p4.$callbk.$cst.$crl;

            //Setting the hashing algorithm to SHA1
            $hashid = hash_hmac('sha1', $datastring, $hsh);

            //URLENCODE
            $cbk = urlencode($callbk);

            $data['url'] = $ipay_url.'?live='.$live.'&oid='.$oid.'&inv='.$inv.'&ttl='.$ttl.'&tel='.$tel.'&eml='.$eml.'&vid='.$vid.'&p1='.$p1.'&p2='.$p2.'&p3='.$p3.'&p4='.$p4.'&crl='.$crl.'&cbk='.$cbk.'&cst='.$cst.'&curr='.$curr.'&hsh='.$hashid.'&mpesa='.$mpesa.'&airtel='.$airtel.'&equity='.$equity.'&mobilebanking='.$mobilebanking.'&debitcard='.$debitcard.'&creditcard='.$creditcard.'&mkoporahisi='.$mkoporahisi.'&saida='.$saida.'';

            //echo "<pre>";print_r($data['url']);die;
            //if(version_compare(VERSION, '2.2.0.0', "<")) {
            if (true) {
                if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/payment/iPay_payment_software.tpl')) {
                    return $this->load->view($this->config->get('config_template').'/template/payment/iPay_payment_software.tpl', $data);
                } else {
                    return $this->load->view('default/template/payment/iPay_payment_software.tpl', $data);
                }
            } else {
                return $this->load->view('payment/iPay_payment_software', $data);
            }
        }

        public function callback()
        {
            $this->load->model('checkout/order');

            $log = new Log('error.log');
            $log->write('callback ipay');
            $log->write($this->request->get);

            $txncd = '';

            if (isset($this->request->get['txncd'])) {
                $txncd = $this->request->get['txncd'];
            }
            //$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

            /**
             * these values below are picked from the incoming URL and assigned to variables
             * that wewill use in our security check URL.
             *
             * The value of the parameter “vendor”, in the url being opened above, is your iPay assigned Vendor ID
             */
            $request = 'vendor='.urlencode($this->config->get('iPay_payment_software_merchant_name'));
            $request .= '&id='.urlencode($this->request->get['id']);
            $request .= '&ivm='.urlencode($this->request->get['ivm']);
            $request .= '&qwh='.urlencode($this->request->get['qwh']);
            $request .= '&afd='.urlencode($this->request->get['afd']);
            $request .= '&poi='.urlencode($this->request->get['poi']);
            $request .= '&uyt='.urlencode($this->request->get['uyt']);
            $request .= '&ifd='.urlencode($this->request->get['ifd']);

            $ipnurl = 'https://www.ipayafrica.com/ipn/?'.$request;

            /*
             * If the payment mode is LIVE, it gets the payment status.
             * If the plugin is on test mode, it always gives a successful response.
             */
            if ('live' == $this->config->get('iPay_payment_software_mode')) {
                $fp = fopen($ipnurl, 'rb');
                $response = stream_get_contents($fp, -1, -1);
                fclose($fp);
            } else {
                $response = 'aei7p7yrx4ae34';
            }

            //$order_id		= $this->session->data['order_id'];
            $this->load->model('checkout/order');

            /*

             * Success
             * The transaction is valid. Therefore you can update this transaction.
             */
            if ('aei7p7yrx4ae34' === $response) {
                /*if (isset($this->session->data['order_id'])) {

                    $order_id = $this->session->data['order_id'];
                }else{
                    echo "The system could not capture the order ID. Kindly refresh the page";
                }*/

                $this->load->model('payment/mpesa');

                foreach ($this->session->data['order_id'] as $order_id) {
                    //$ret = $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('cod_order_status_id'));
                    $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('iPay_payment_software_order_status_id'));

                    //save order transaction id
                    if (isset($txncd)) {
                        $this->model_payment_mpesa->insertOrderTransactionId($order_id, $txncd);
                    }
                }

                $this->response->redirect($this->url->link('checkout/success'));
            }

            /*
             * Pending
             * Incoming Mobile Money Transaction Not found. The user should try again after 5 Minutes.
             */
            elseif ('bdi6p2yy76etrs' === $response) {
                $data['message'] = 'Incoming Mobile Money Transaction Not found.';
            }

            /*
             * Used:
             * This code has been used already. A notification of this transaction sent to the merchant.
             */
            elseif ('cr5i3pgy9867e1' === $response) {
                $data['message'] = 'This code has been used already.';
            }

            /*
             * More
             * The amount that you have sent via mobile money is MORE than what was required to validate this transaction
             */
            elseif ('eq3i7p5yt7645e' === $response) {
                $this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('config_order_status_id'));

                $data['message'] = 'More: The amount that you have sent via mobile money is MORE than what was required to validate this transaction.';

                $this->model_checkout_order->update($this->session->data['order_id'], $this->config->get('iPay_payment_software_order_status_id'), $message, false);
            }

            /*
             * Less
             * The amount that you have sent via mobile money is LESS than what was required to validate
             */
            elseif ('dtfi4p7yty45wq' === $response) {
                $data['message'] = 'Less: The amount that you have sent via mobile money is LESS than what was required to validate this transaction.';
            }

            /*
             * Failed transaction
             * Not all parameters fulfilled. A notification of this transaction sent to the merchant.
             */
            elseif ('fe2707etr5s4wq' === $response) {
                $this->response->redirect($this->url->link('checkout/checkout'));
            }

            /*
             * [console_log It helps output data to the browser console]
             * @param  [type] $data [description]
             * To call it anywhere in the class use $this->console_log("your data here")
             */
        }

        public function console_log($data)
        {
            echo '<script>';
            echo 'console.log('.json_encode($data).')';
            echo '</script>';
        }
    }
