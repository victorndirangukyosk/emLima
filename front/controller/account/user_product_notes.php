<?php

class ControllerAccountUserProductNotes extends Controller {

    private $error = [];

    public function index() {

        $data['kondutoStatus'] = $this->config->get('config_konduto_status');

        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

        $data['redirect_coming'] = false;
        $this->document->addStyle('/front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_login.css');
        $this->document->addScript('https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js');
        $this->document->addScript('https://www.js-tutorials.com/demos/jquery_bootstrap_pagination_example_demo/jquery.twbsPagination.min.js');
        $this->document->addStyle('/front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/layout_login.css');

        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/account', '', 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $this->load->language('account/user_product_notes');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title'] = $this->language->get('heading_title');

        $data['edit'] = $this->url->link('account/edit', '', 'SSL');
        $data['password'] = $this->url->link('account/password', '', 'SSL');
        $data['address'] = $this->url->link('account/address', '', 'SSL');
        $data['wishlist'] = $this->url->link('account/wishlist');
        $data['order'] = $this->url->link('account/order', '', 'SSL');
        $data['download'] = $this->url->link('account/download', '', 'SSL');
        $data['return'] = $this->url->link('account/return', '', 'SSL');
        $data['credit'] = $this->url->link('account/credit', '', 'SSL');
        $data['newsletter'] = $this->url->link('account/newsletter', '', 'SSL');
        $data['logout'] = $this->url->link('account/logout', '', 'SSL');
        $data['recurring'] = $this->url->link('account/recurring', '', 'SSL');

        if ('POST' != $this->request->server['REQUEST_METHOD']) {
            $this->load->model('account/customer');
            $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
        }

        //for membership
        // $member_group_id = $this->config->get('config_member_group_id');
        // $customer_group_id = $this->customer->getGroupId();
        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['base'] = $server;

        $data['action'] = $this->url->link('account/account', '', 'SSL');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');

        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/information');

        $data['account_edit'] = $this->load->controller('account/edit');

        $data['home'] = $this->url->link('common/home/toHome');
        /* Added new params */
        $data['is_login'] = $this->customer->isLogged();
        $data['full_name'] = $this->customer->getFirstName();
        $data['text_my_cash'] = $this->language->get('text_my_cash');
        $data['text_my_wishlist'] = $this->language->get('text_my_wishlist');
        $data['label_my_address'] = $this->language->get('label_my_address');
        $data['contactus'] = $this->language->get('contactus');
        $data['text_cash'] = $this->language->get('text_cash');
        $data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');

        //echo "<pre>";print_r($data['telephone'] );die;
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/user_product_notes.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/user_product_notes.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/account/user_product_notes.tpl', $data));
        }
    }

    public function getUserProductNotes() {
        $data['user_product_notes'] = [];

        $this->load->model('account/customer');
        $this->load->model('account/user_product_notes');
        $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
        $user_product_info = $this->model_account_user_product_notes->getUserProductNotes($this->customer->getId());
        $data['user_product_notes'][] = $user_product_info;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

}
