<?php

require_once DIR_SYSTEM . '/vendor/pesapal/OAuth.php';

class ControllerAccountCredit extends Controller {

    public function updateBarcode() {
        $allProducts = $this->db->query('SELECT * from ' . DB_PREFIX . 'product')->rows;

        //echo "<pre>";print_r($allProducts);die;

        foreach ($allProducts as $product) {
            $length = mt_rand(9, 13);

            $characters = '1234567890';
            $randomString = '';
            for ($i = 0; $i < $length; ++$i) {
                $randomString .= $characters[rand(0, strlen($characters) - 1)];
            }

            $this->db->query('UPDATE ' . DB_PREFIX . "product SET model = '" . (int) $randomString . "' WHERE product_id = '" . (int) $product['product_id'] . "'");
            //die;
        }
    }

    public function index() {
        /* $x = 'Second Parklands Avenuez';
          $data['building_name'] = explode(",",$x)[0];
          echo "<pre>";print_r($data['building_name']);die; */
        /* $value = "Test a";
          echo strtok($value, " "); // Test
          die; */
        //echo "<pre>";print_r(floor(1.234));die;
        //echo "<pre>";print_r(date($this->language->get('full_datetime_format'), strtotime(date("Y-m-d H:i:s"))));die;
        $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_login.css');

        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/credit', '', 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $data['kondutoStatus'] = $this->config->get('config_konduto_status');

        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        $this->load->language('account/credit');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/account', '', 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_credit'),
            'href' => $this->url->link('account/credit', '', 'SSL'),
        ];

        $this->load->model('account/credit');

        $data['label_address'] = $this->language->get('label_address');
        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_order'] = $this->language->get('text_order');
        $data['text_balance'] = $this->language->get('text_balance');
        $data['text_activity'] = $this->language->get('text_activity');
        $data['text_report_issue'] = $this->language->get('text_report_issue');

        $data['text_load_more'] = $this->language->get('text_load_more');
        $data['text_no_more'] = $this->language->get('text_no_more');

        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_description'] = $this->language->get('column_description');
        $data['column_amount'] = sprintf($this->language->get('column_amount'), $this->config->get('config_currency'));

        $data['text_total'] = $this->language->get('text_total');
        $data['text_empty'] = $this->language->get('text_empty');

        $data['button_continue'] = $this->language->get('button_continue');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['credits'] = [];

        $filter_data = [
            'sort' => 'date_added',
            'order' => 'DESC',
            'start' => ($page - 1) * 10,
            'limit' => 10,
        ];

        $data['telephone'] = $this->customer->getTelephone();

        $credit_total = $this->model_account_credit->getTotalCredits();

        $results = $this->model_account_credit->getCredits($filter_data);

        foreach ($results as $result) {
            $transaction_ID = "";
            if (isset($result['transaction_id']) && $result['transaction_id'] != "") {
                $transaction_ID = '#Transaction ID ' . $result['transaction_id'];
            }
            $data['credits'][] = [
                'amount' => $this->currency->format($result['amount'], $this->config->get('config_currency')),
                'plain_amount' => $result['amount'],
                'description' => $result['description'] . ' ' . $transaction_ID,
                'date_added' => date($this->language->get('date_format_medium'), strtotime($result['date_added'])),
            ];
        }

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['base'] = $server;

        $pagination = new Pagination();
        $pagination->total = $credit_total;
        $pagination->page = $page;
        $pagination->limit = 10;
        $pagination->url = $this->url->link('account/credit', 'page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($credit_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($credit_total - 10)) ? $credit_total : ((($page - 1) * 10) + 10), $credit_total, ceil($credit_total / 10));

        $data['total'] = $this->currency->format($this->customer->getBalance());
        $data['text_signout'] = $this->language->get('text_signout');
        $data['text_shopping'] = $this->language->get('text_shopping');

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['continue'] = $this->url->link('account/account', '', 'SSL');
        $data['order'] = $this->url->link('account/order', '', 'SSL');
        $data['credit'] = $this->url->link('account/credit', '', 'SSL');
        $data['address'] = $this->url->link('account/address', '', 'SSL');
        $data['home'] = $server;

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/information');

        // echo "<pre>";print_r($data['credits']);die;
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/credit.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/credit.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/account/credit.tpl', $data));
        }
    }

    public function getWalletTotal() {
        $total = 0;
        // if (!$this->customer->isLogged()) {
        //     $this->session->data['redirect'] = $this->url->link('account/credit', '', 'SSL');
        //     $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        // } 
        $this->load->model('account/credit');

        $result = $this->model_account_credit->getTotalAmount();
        $total = $this->currency->format($result, $this->config->get('config_currency'));
        return $total;
    }

    public function pesapal() {

        $log = new Log('error.log');

        $this->load->language('payment/pesapal');
        $this->load->model('setting/setting');
        $this->load->model('payment/pesapal');
        $this->load->model('account/customer');

        $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
        $amount = $this->request->post['amount'];
        $pesapal_creds = $this->model_setting_setting->getSetting('pesapal', 0);
        //pesapal params
        $token = $params = null;

        /*
          PesaPal Sandbox is at https://demo.pesapal.com. Use this to test your developement and
          when you are ready to go live change to https://www.pesapal.com.
         */
        $consumer_key = $pesapal_creds['pesapal_consumer_key']; //Register a merchant account on
        //demo.pesapal.com and use the merchant key for testing.
        //When you are ready to go live make sure you change the key to the live account
        //registered on www.pesapal.com!
        $consumer_secret = $pesapal_creds['pesapal_consumer_secret']; // Use the secret from your test
        //account on demo.pesapal.com. When you are ready to go live make sure you
        //change the secret to the live account registered on www.pesapal.com!
        $signature_method = new OAuthSignatureMethod_HMAC_SHA1();
        $iframelink = 'https://www.pesapal.com/api/PostPesapalDirectOrderV4'; //change to
        //https://www.pesapal.com/API/PostPesapalDirectOrderV4 when you are ready to go live!
        //get form details
        $transaction_fee = 0;
        $percentage = 3.5;
        $transaction_fee = ($percentage / 100) * $amount;
        $amount = $amount + $transaction_fee;
        $log->write('TRANSACTION FEE');
        $log->write($transaction_fee);
        $log->write($amount);
        //$amount = 100;
        $amount = number_format($amount, 2); //format amount to 2 decimal places

        $desc = $customer_info['company_name'] . '-' . $customer_info['firstname'] . '-' . $customer_info['lastname'] . '-' . $amount . '-' . time() . '-' . $this->customer->getId();
        $type = 'MERCHANT'; //default value = MERCHANT
        $reference = 'WALLET_TOPUP' . '_' . $amount . '_' . time() . '_' . $this->customer->getId(); //unique order id of the transaction, generated by merchant

        $first_name = $customer_info['firstname'];
        $last_name = $customer_info['lastname'];
        $email = $customer_info['email'];
        $phonenumber = '+254' . $customer_info['telephone']; //ONE of email or phonenumber is required
        $Currency = 'KES';

        $callback_url = $this->url->link('account/credit/status', '', 'SSL'); //redirect url, the page that will handle the response from pesapal.

        $post_xml = '<?xml version="1.0" encoding="utf-8"?><PesapalDirectOrderInfo xmlns:xsi="http://www.w3.org/2001/XMLSchemainstance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" Amount="' . $amount . '" Description="' . $desc . '" Type="' . $type . '" Reference="' . $reference . '" FirstName="' . $first_name . '" LastName="' . $last_name . '" Email="' . $email . '" PhoneNumber="' . $phonenumber . '" xmlns="http://www.pesapal.com" />';
        $post_xml = htmlentities($post_xml);

        $consumer = new OAuthConsumer($consumer_key, $consumer_secret, $callback_url);
        //print_r($consumer);
        //post transaction to pesapal
        $iframe_src = OAuthRequest::from_consumer_and_token($consumer, $token, 'GET', $iframelink, $params);
        $iframe_src->set_parameter('oauth_callback', $callback_url);
        $iframe_src->set_parameter('pesapal_request_data', $post_xml);
        $iframe_src->sign_request($signature_method, $consumer, $token);
        //display pesapal - iframe and pass iframe_src
        $log->write($iframe_src);
        $data['iframe'] = $iframe_src;

        echo '<iframe src=' . $iframe_src . ' width="100%" height="700px"  scrolling="no" frameBorder="0"><p>Browser unable to load iFrame</p></iframe>';
    }

    public function status() {
        $log = new Log('error.log');
        $status = NULL;
        $amount = NULL;

        $this->load->language('payment/pesapal');
        $this->load->model('setting/setting');
        $this->load->model('payment/pesapal');
        $this->load->model('account/customer');

        $log->write('PESAPAL WALLET CALL BACK');
        $transaction_tracking_id = $this->request->get['pesapal_transaction_tracking_id'];
        $merchant_reference = $this->request->get['pesapal_merchant_reference'];
        $log->write($transaction_tracking_id);
        $log->write($merchant_reference);
        $wallet_topup_details = explode('_', $merchant_reference);
        if (is_array($wallet_topup_details)) {
            $amount = $wallet_topup_details[0];
        }
        $log->write('PESAPAL WALLET CALL BACK');
        $status = $this->ipinlistenercustom('CHANGE', $transaction_tracking_id, $merchant_reference);

        // Add to activity log
        $this->load->model('account/activity');
        $activity_data = [
            'customer_id' => $this->customer->getId(),
            'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
            'transaction_tracking_id' => $transaction_tracking_id,
            'merchant_reference' => $merchant_reference,
            'status' => $status,
            'amount' => $amount
        ];

        $this->model_account_activity->addActivity('WALLET_TOPUP_CHECKING_STATUS', $activity_data);
        // Add to activity log

        if ('COMPLETED' == $status) {
            $this->response->redirect($this->url->link('credit/pesapalsuccess'));
        }

        if ('COMPLETED' != $status || NULL == $status) {
            $this->response->redirect($this->url->link('credit/pesapalfailed'));
        }
    }

    public function ipinlistenercustom($pesapalNotification, $pesapalTrackingId, $pesapal_merchant_reference) {
        $status = null;
        $log = new Log('error.log');
        $log->write('ipinlistener');
        $this->load->model('setting/setting');
        $this->load->model('payment/pesapal');
        $this->load->model('checkout/order');
        $this->load->model('account/customer');
        $pesapal_creds = $this->model_setting_setting->getSetting('pesapal', 0);

        $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
        $customer_id = $customer_info['customer_id'];

        $consumer_key = $pesapal_creds['pesapal_consumer_key']; //Register a merchant account on
        //demo.pesapal.com and use the merchant key for testing.
        //When you are ready to go live make sure you change the key to the live account
        //registered on www.pesapal.com!
        $consumer_secret = $pesapal_creds['pesapal_consumer_secret']; // Use the secret from your test
        //account on demo.pesapal.com. When you are ready to go live make sure you
        //change the secret to the live account registered on www.pesapal.com!
        $statusrequestAPI = 'https://www.pesapal.com/api/querypaymentstatus';
        //'https://demo.pesapal.com/api/querypaymentstatus'; //change to
        //https://www.pesapal.com/api/querypaymentstatus' when you are ready to go live!
        // Parameters sent to you by PesaPal IPN
        $pesapalNotification = $pesapalNotification;
        $pesapalTrackingId = $pesapalTrackingId;
        $pesapal_merchant_reference = $pesapal_merchant_reference;

        /* $pesapalNotification = $this->request->get['pesapal_notification_type'];
          $pesapalTrackingId = $this->request->get['pesapal_transaction_tracking_id'];
          $pesapal_merchant_reference = $this->request->get['pesapal_merchant_reference']; */

        if ('CHANGE' == $pesapalNotification && '' != $pesapalTrackingId) {
            $log->write('PESAPAL WALLET STATUS ipinlistener');
            $token = $params = null;
            $consumer = new OAuthConsumer($consumer_key, $consumer_secret);
            $signature_method = new OAuthSignatureMethod_HMAC_SHA1();

            //get transaction status
            $request_status = OAuthRequest::from_consumer_and_token($consumer, $token, 'GET', $statusrequestAPI, $params);
            $request_status->set_parameter('pesapal_merchant_reference', $pesapal_merchant_reference);
            $request_status->set_parameter('pesapal_transaction_tracking_id', $pesapalTrackingId);
            $request_status->sign_request($signature_method, $consumer, $token);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $request_status);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            if (defined('CURL_PROXY_REQUIRED')) {
                if (CURL_PROXY_REQUIRED == 'True') {
                    $proxy_tunnel_flag = (defined('CURL_PROXY_TUNNEL_FLAG') && 'FALSE' == strtoupper(CURL_PROXY_TUNNEL_FLAG)) ? false : true;
                    curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, $proxy_tunnel_flag);
                    curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
                    curl_setopt($ch, CURLOPT_PROXY, CURL_PROXY_SERVER_DETAILS);
                }
            }

            $response = curl_exec($ch);
            $log->write('PESAPAL WALLET RESPONSE');
            $log->write($response);
            $log->write('PESAPAL WALLET RESPONSE');

            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $raw_header = substr($response, 0, $header_size - 4);
            $headerArray = explode("\r\n\r\n", $raw_header);
            $header = $headerArray[count($headerArray) - 1];

            //transaction status
            $elements = preg_split('/=/', substr($response, $header_size));
            $status = $elements[1];
            $log->write('PESAPAL WALLET STATUS');
            $log->write($status);
            $log->write('PESAPAL WALLET STATUS');
            curl_close($ch);
        }
        echo $status;
    }

    public function pesapalsuccess() {

        $this->load->language('checkout/success');

        $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_checkout.css');

        if (!empty($_SESSION['parent'])) {
            $this->document->setTitle($this->language->get('heading_title_sub_user'));
        }
        if (empty($_SESSION['parent'])) {
            $this->document->setTitle($this->language->get('heading_title'));
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_basket'),
            'href' => $this->url->link('checkout/cart'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_checkout'),
            'href' => $this->url->link('checkout/checkout', '', 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_success'),
            'href' => $this->url->link('checkout/success'),
        ];

        $data['referral_description'] = $this->language->get('referral_description');
        if (!empty($_SESSION['parent'])) {
            $data['heading_title'] = $this->language->get('heading_title_sub_user');
        }
        if (empty($_SESSION['parent'])) {
            $data['heading_title'] = $this->language->get('heading_title');
        }

        $data['text_basket'] = $this->language->get('text_basket');
        if (empty($_SESSION['parent'])) {
            $data['text_customer'] = $this->language->get('text_customer');
        }
        if (!empty($_SESSION['parent'])) {
            $data['text_customer'] = $this->language->get('text_customer_sub_user');
        }
        $data['text_guest'] = $this->language->get('text_guest');
        $data['text_order_id'] = $this->language->get('text_order_id');

        // Get Order Status enter Message
        if ($this->customer->isLogged() && empty($_SESSION['parent'])) {
            $data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/order', '', 'SSL'), $this->url->link('account/account', '', 'SSL'));
        } elseif ($this->customer->isLogged() && !empty($_SESSION['parent'])) {
            $data['text_message'] = sprintf($this->language->get('text_customer_sub_user'), $this->url->link('account/order', '', 'SSL'), $this->url->link('account/account', '', 'SSL'));
        } else {
            $data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
        }

        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        $data['button_continue'] = $this->language->get('button_continue');

        $data['continue'] = $this->url->link('common/home');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/onlyHeader');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/wallet_success.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/wallet_success.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/common/wallet_success.tpl', $data));
        }
    }

    public function pesapalfailed() {

        $this->load->language('checkout/success');

        $this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_checkout.css');

        $this->document->setTitle($this->language->get('heading_title_failed'));

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_basket'),
            'href' => $this->url->link('checkout/cart'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_checkout'),
            'href' => $this->url->link('checkout/checkout', '', 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_success'),
            'href' => $this->url->link('checkout/success'),
        ];

        $data['referral_description'] = $this->language->get('referral_description');
        $data['heading_title'] = $this->language->get('heading_title_failed');
        $data['text_basket'] = $this->language->get('text_basket');
        $data['text_customer'] = $this->language->get('text_customer_failed');
        $data['text_guest'] = $this->language->get('text_guest');
        $data['text_order_id'] = $this->language->get('text_order_id');

        // Get Order Status enter Message
        if ($this->customer->isLogged()) {
            $data['text_message'] = sprintf($this->language->get('text_customer_failed'), $this->url->link('account/order', '', 'SSL'), $this->url->link('account/account', '', 'SSL'));
        } else {
            $data['text_message'] = sprintf($this->language->get('text_customer_failed'), $this->url->link('information/contact'));
        }

        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        $data['button_continue'] = $this->language->get('button_continue');

        $data['continue'] = $this->url->link('common/home');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/onlyHeader');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/wallet_success.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/wallet_success.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/common/wallet_success.tpl', $data));
        }
    }

}
