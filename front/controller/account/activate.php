<?php

class ControllerAccountActivate extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->model('account/customer');
        $this->load->language('account/activate');

        if ($this->customer->isLogged()) {
            $this->response->redirect($this->url->link('account/account', '', 'SSL'));
        }

        $data['success'] = false;
        if (!empty($this->request->get['token'])) {
            unset($this->session->data['wishlist']);
            unset($this->session->data['payment_address']);
            unset($this->session->data['payment_method']);
            unset($this->session->data['payment_methods']);
            unset($this->session->data['shipping_address']);
            unset($this->session->data['shipping_method']);
            unset($this->session->data['shipping_methods']);
            unset($this->session->data['comment']);
            unset($this->session->data['order_id']);
            unset($this->session->data['coupon']);
            unset($this->session->data['reward']);
            unset($this->session->data['voucher']);
            unset($this->session->data['vouchers']);
            unset($this->session->data['adminlogin']);

            $customer_info = $this->model_account_customer->getCustomerByToken($this->request->get['token']);

            if ($customer_info) {
                $this->model_account_customer->approve($customer_info['customer_id']);
                //success
                $data['success'] = true;
            }
        }

        $this->document->addStyle('front/ui/theme/instacart/stylesheet/layout_checkout.css');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_error'] = $this->language->get('text_error');
        $data['text_success'] = $this->language->get('text_success');
        $data['text_login'] = $this->language->get('text_login');
        $data['description'] = $this->language->get('description');

        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/onlyHeader');
        $data['login_modal'] = $this->load->controller('common/login_modal');

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/account/activate.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/account/activate.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/account/activate.tpl', $data));
        }
    }

    public function resendEmail()
    {
        $data = [];
        $data['status'] = false;

        if ($this->customer->isLogged()) {
            $this->response->redirect($this->url->link('account/account', '', 'SSL'));
        }

        $this->load->language('account/activate');

        $this->load->model('account/customer');

        if ($this->request->isAjax() && !empty($this->request->post['email'])) {
            $this->load->language('mail/forgotten');

            $customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);

            if ($customer_info) {
                $result = $this->model_account_customer->resendVerificationEmail($customer_info, $customer_info['customer_id']);

                $data['status'] = true;

                $data['success_message'] = $this->language->get('text_mail_sent');
            } else {
                $data['error_warning'] = $this->language->get('text_mail_not_found');
            }
        } else {
            $data['error_warning'] = $this->language->get('text_mail_id_missing');
        }

        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($data));
        }
    }
}
