<?php

class ControllerApiCustomerPasswordReset extends Controller
{
    private $error = [];

    public function addPasswordReset($args = [])
    {
        $json = [];

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');
        $this->load->model('api/approval');

        $log = new Log('error.log');

        $this->load->model('account/customer');
        //echo "<pre>";print_r($args);die;
        if (!$this->validate()) {
            $json['status'] = 10014;

            foreach ($this->error as $key => $value) {
                $json['message'][] = ['type' => $key, 'body' => $value];
            }

            http_response_code(400);
        } else {
            $this->load->language('account/forgotten');

            $this->load->model('account/customer');

            $this->load->language('mail/forgotten');

            $password = substr(sha1(uniqid(mt_rand(), true)), 0, 10);

            $this->model_account_customer->editPassword($this->request->post['email'], $password);

            $this->model_account_customer->resetPasswordMail($this->request->post['email'], $password);

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

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_success')];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validate()
    {
        $this->load->model('account/customer');
        $this->load->language('account/register');

        if (!isset($this->request->post['email'])) {
            $this->error['warning'] = $this->language->get('error_email');
        } elseif (!$this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
            $this->error['warning'] = $this->language->get('error_email');
        }

        return !$this->error;
    }
}
