<?php

class ControllerAccountMember extends Controller
{
    public function index()
    {
        $this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_login.css');

        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/member', '', 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $this->load->language('account/member');

        $this->document->setTitle($this->language->get('heading_title'));

        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];
            unset($this->session->data['error']);
        } else {
            $data['error'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/member', '', 'SSL'),
        ];

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $cost = $this->currency->format($this->config->get('config_member_account_fee'));

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_benefits'] = $this->language->get('text_benefits');
        $data['text_become_member'] = $this->language->get('text_become_member');

        $data['button_pay'] = sprintf($this->language->get('button_pay'), $cost);

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/information');

        $data['action'] = $this->url->link('account/member/payu');

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/account/member.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/account/member.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/account/member.tpl', $data));
        }
    }

    //send request to payu

    public function payu()
    {
        $data['merchant'] = $this->config->get('payu_merchant');

        /////////////////////////////////////Start Payu Vital  Information /////////////////////////////////

        if ('demo' == $this->config->get('payu_test')) {
            $data['action'] = 'https://test.payu.in/_payment.php';
        } else {
            $data['action'] = 'https://secure.payu.in/_payment.php';
        }

        $txnid = time();

        $data['key'] = $this->config->get('payu_merchant');
        $data['salt'] = $this->config->get('payu_salt');
        $data['txnid'] = $txnid;
        $data['amount'] = round($this->config->get('config_member_account_fee'), 2);
        $data['productinfo'] = 'Become member';

        $data['firstname'] = $this->customer->getFirstName();
        $data['Lastname'] = $this->customer->getLastName();
        $data['Zipcode'] = '';
        $data['email'] = $this->customer->getEmail();
        $data['phone'] = $this->customer->getTelephone();
        $data['address1'] = '';
        $data['address2'] = '';
        $data['state'] = '';
        $data['city'] = '';

        //get country code

        $this->load->model('account/address');
        $row = $this->model_account_member->getCountries($this->config->get('config_country_id'));

        if ($row) {
            $data['country'] = $row['iso_code_2'];
        } else {
            $data['country'] = 'IN';
        }

        $firstname = $this->customer->getFirstName();
        $email = $this->customer->getEmail();

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['Pg'] = 'CC';
        $data['surl'] = $server.'index.php?path=account/member/callback';
        $data['Furl'] = $server.'index.php?path=account/member/callback';
        $data['curl'] = $server.'index.php?path=account/member';
        $key = $this->config->get('payu_merchant');
        $amount = round($this->config->get('config_member_account_fee'), 2);
        $productInfo = $data['productinfo'];
        $salt = $this->config->get('payu_salt');

        $Hash = hash('sha512', $key.'|'.$txnid.'|'.$amount.'|'.$productInfo.'|'.$firstname.'|'.$email.'|||||||||||'.$salt);

        $data['user_credentials'] = $data['key'].':'.$data['email'];
        $data['Hash'] = $Hash;

        /////////////////////////////////////End Payu Vital  Information /////////////////////////////////

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/account/payu.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/account/payu.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/account/payu.tpl', $data));
        }
    }

    //payu callback

    public function callback()
    {
        $this->load->language('account/member');

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        if (isset($this->request->post['key']) && ($this->request->post['key'] == $this->config->get('payu_merchant'))) {
            $data['title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));

            if (!isset($this->request->server['HTTPS']) || ('on' != $this->request->server['HTTPS'])) {
                $data['base'] = HTTP_SERVER;
            } else {
                $data['base'] = HTTPS_SERVER;
            }

            $data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));

            $data['text_success'] = $this->language->get('text_success');
            $data['text_failure'] = $this->language->get('text_failure');
            $data['text_cancelled'] = $this->language->get('text_cancelled');
            $data['text_pending'] = $this->language->get('text_pending');

            $customer_id = $this->customer->getId();
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

                if ($sentHashString == $this->request->post['hash']) {
                    //success page
                    $data['text_message'] = $data['text_success']; //'Success: Payment Received For Membership Account!';
                } else {
                    //success page with pending message
                    $data['text_message'] = $data['text_pending']; //'Success: We Processing Payment For Membership Account!';
                }

                //success action
                $this->update_membership($customer_id);

                $this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_login.css');

                $data['button_continue'] = $this->language->get('button_continue');

                $data['continue'] = $this->url->link('common/home');
                $data['header'] = $this->load->controller('common/header/information');
                $data['footer'] = $this->load->controller('common/footer');

                if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/common/success.tpl')) {
                    $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/common/success.tpl', $data));
                } else {
                    $this->response->setOutput($this->load->view('default/template/common/success.tpl', $data));
                }
            } else {
                //fail message
                $this->session->data['error'] = $data['text_failure']; //'Error: Payment failed!';

                $this->response->redirect($this->url->link('account/member', 'token='.$this->session->data['token']));
            }
        }
    }

    private function update_membership($customer_id)
    {
        $this->load->model('account/address');

        $customer = $this->model_account_address->getCustomer($customer_id);

        if (strtotime($customer['member_upto']) > time()) {
            $member_upto = date('Y-m-d', strtotime('+1 year', strtotime($customer['member_upto'])));
        } else {
            $member_upto = date('Y-m-d', strtotime('+1 year'));
        }

        $customer_group_id = $this->config->get('config_member_group_id');

        $this->model_account_address->updateCustomer($customer_id, $member_upto, $customer_group_id);
    }
}
