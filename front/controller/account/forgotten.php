<?php

class ControllerAccountForgotten extends Controller
{
    private $error = [];

    public function index()
    {
        $this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_login.css');

        if ($this->customer->isLogged()) {
            $this->response->redirect($this->url->link('account/account', '', 'SSL'));
        }

        $this->load->language('account/forgotten');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/customer');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->request->isAjax() && $this->validate()) {
            $this->load->language('mail/forgotten');

            $password = substr(sha1(uniqid(mt_rand(), true)), 0, 10);

            $this->model_account_customer->resetPassword($this->request->post['email'], $password, 1);
            //1 implies, new password is generated and user need to update his password

            $this->model_account_customer->resetPasswordMail($this->request->post['email'], $password);

            //echo "<pre>";print_r($password);die;
            $this->session->data['success'] = $this->language->get('text_success');

            $this->model_account_customer->deleteLoginAttempts($this->request->post['email']);

            // Add to activity log
            $customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);

            if ($customer_info) {
                $this->load->model('account/activity');

                $activity_data = [
                    'customer_id' => $customer_info['customer_id'],
                    'name' => $customer_info['firstname'].' '.$customer_info['lastname'],
                ];

                $this->model_account_activity->addActivity('forgotten', $activity_data);
            }

            $data['status'] = true;
            $data['redirect'] = $this->url->link('account/account', '', 'SSL');
            $data['text_message'] = $this->language->get('text_success');

            if ($this->request->isAjax()) {
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($data));
            }
            //$this->response->redirect($this->url->link('account/login', '', 'SSL'));
        } else {
            if (isset($this->error['warning'])) {
                $data['error_warning'] = $this->error['warning'];
            } else {
                $data['error_warning'] = '';
            }

            $data['status'] = false;
            $data['text_message'] = $data['error_warning'];

            if ($this->request->isAjax()) {
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($data));
            }
        }

        return true;
    }

    protected function validate()
    {
        if (!isset($this->request->post['email'])) {
            $this->error['warning'] = $this->language->get('error_email');
        } elseif (!$this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
            $this->error['warning'] = $this->language->get('error_email');
        }

        return !$this->error;
    }
}
