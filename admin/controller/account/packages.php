<?php

class ControllerAccountPackages extends Controller
{
    private $error = [];

    public function index()
    {
        $this->language->load('account/packages');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/packages');

        $this->getList();
    }

    protected function getList()
    {
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token='.$this->session->data['token'], 'SSL'),
            'separator' => false,
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('account/packages', 'token='.$this->session->data['token'].$url, 'SSL'),
            'separator' => ' :: ',
        ];

        $data = [
            'start' => ($page - 1) * $this->config->get('config_admin_limit'),
            'limit' => $this->config->get('config_admin_limit'),
        ];

        $total = $this->model_account_packages->getTotal();

        $data['results'] = $this->model_account_packages->getPackages($data);

        $data['text_list'] = $this->language->get('text_list');
        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_pay'] = $this->language->get('text_pay');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_price'] = $this->language->get('column_price');
        $data['column_benefits'] = $this->language->get('column_benefits');
        $data['column_priority'] = $this->language->get('column_priority');
        $data['column_date'] = $this->language->get('column_date');
        $data['column_action'] = $this->language->get('column_action');

        $data['button_insert'] = $this->language->get('button_insert');
        $data['button_delete'] = $this->language->get('button_delete');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $pagination = new Pagination();
        $pagination->total = $total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_admin_limit');
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('account/packages', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['page_results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total - $this->config->get('config_limit_admin'))) ? $total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total, ceil($total / $this->config->get('config_limit_admin')));

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('account/package_list.tpl', $data));
    }

    public function pay()
    {
        $data['package_id'] = $this->session->data['package_id'] = $this->request->get['package_id'];

        $data['payu_action'] = $this->url->link('account/packages/payu', 'token='.$this->session->data['token']);

        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_list'] = $this->language->get('text_list');

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token='.$this->session->data['token'], 'SSL'),
            'separator' => false,
        ];

        $data['breadcrumbs'][] = [
            'text' => 'Packages',
            'href' => $this->url->link('account/packages', 'token='.$this->session->data['token'], 'SSL'),
            'separator' => ' :: ',
        ];

        $data['breadcrumbs'][] = [
            'text' => 'Pay',
            'href' => $this->url->link('account/packages/pay', 'package_id='.$data['package_id'].'&token='.$this->session->data['token'], 'SSL'),
            'separator' => ' :: ',
        ];

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('account/payment.tpl', $data));
    }

    //send request to payu
    public function payu()
    {
        $data['merchant'] = $this->config->get('payu_merchant');

        $this->load->model('account/packages');

        $package_info = $this->model_account_packages->getPackage($this->request->post['package_id']);

        $token = $this->session->data['token'];

        /////////////////////////////////////Start Payu Vital  Information /////////////////////////////////

        if ('demo' == $this->config->get('payu_test')) {
            $data['action'] = 'https://test.payu.in/_payment.php';
        } else {
            $data['action'] = 'https://secure.payu.in/_payment.php';
        }

        $txnid = $this->config->get('config_package_prefix').$this->request->post['package_id'];

        $data['key'] = $this->config->get('payu_merchant');
        $data['salt'] = $this->config->get('payu_salt');
        $data['txnid'] = $txnid;
        $data['amount'] = (int) $package_info['amount'];
        $data['productinfo'] = $package_info['name'];

        $user = $this->model_account_packages->getUser((int) $this->session->data['user_id']);

        $data['firstname'] = $user['firstname'];
        $data['Lastname'] = $user['lastname'];
        $data['Zipcode'] = $this->request->post['postcode'];
        $data['email'] = $user['email'];
        $data['phone'] = $user['mobile'];
        $data['address1'] = $this->request->post['payment_address_1'];
        $data['address2'] = $this->request->post['payment_address_2'];
        $data['state'] = $this->request->post['payment_zone'];
        $data['city'] = $this->request->post['payment_city'];
        $data['country'] = 'IN';
        $firstname = $user['firstname'];
        $email = $user['email'];

        $data['Pg'] = 'CC';
        $data['surl'] = $this->url->link('account/packages/callback', 'token='.$token); //HTTP_SERVER.'index.php?path=payment/payu/callback';
        $data['Furl'] = $this->url->link('account/packages/fail', 'token='.$token); //HTTP_SERVER.'index.php?path=payment/payu/callback';
        $data['curl'] = $this->url->link('account/packages', 'token='.$token, 'SSL');
        $key = $this->config->get('payu_merchant');
        $amount = (int) $package_info['amount'];
        $productInfo = $data['productinfo'];
        $salt = $this->config->get('payu_salt');

        $Hash = hash('sha512', $key.'|'.$txnid.'|'.$amount.'|'.$productInfo.'|'.$firstname.'|'.$email.'|||||||||||'.$salt);

        $data['user_credentials'] = $data['key'].':'.$data['email'];
        $data['Hash'] = $Hash;

        /////////////////////////////////////End Payu Vital  Information /////////////////////////////////

        $this->response->setOutput($this->load->view('account/payu.tpl', $data));
    }

    //payu callback
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

            $data['charset'] = $this->language->get('charset');
            $data['language'] = $this->language->get('code');
            $data['direction'] = $this->language->get('direction');
            $data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));
            $data['text_response'] = $this->language->get('text_response');
            $data['text_success'] = $this->language->get('text_success');
            $data['text_success_wait'] = sprintf($this->language->get('text_success_wait'), $this->url->link('checkout/success'));
            $data['text_failure'] = $this->language->get('text_failure');
            $data['text_cancelled'] = $this->language->get('text_cancelled');
            $data['text_cancelled_wait'] = sprintf($this->language->get('text_cancelled_wait'), $this->url->link('checkout/cart'));
            $data['text_pending'] = $this->language->get('text_pending');
            $data['text_failure_wait'] = sprintf($this->language->get('text_failure_wait'), $this->url->link('checkout/cart'));

            $package_id = $this->request->post['txnid'];
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

                $package_id = $this->request->post['txnid'];

                if ($sentHashString == $this->request->post['hash']) {
                    //success page
                    $this->session->data['success'] = 'Success: Payment Received For Package #'.$package_id.'!';
                } else {
                    //success page with pending message
                    $this->session->data['success'] = 'Success: Payment Pending For Package #'.$package_id.'!';
                }

                //ad transaction
                $this->load->model('account/packages');
                $this->model_account_packages->savePayment($package_id, $this->request->post);
            } else {
                //fail message
                $this->session->data['error'] = 'Error: Payment failed!';
            }

            $this->response->redirect($this->url->link('account/packages', 'token='.$this->session->data['token']));
        }
    }
}
