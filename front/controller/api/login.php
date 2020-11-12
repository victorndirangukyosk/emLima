<?php

class ControllerApiLogin extends Controller {

    public function index() {
        $this->load->language('api/login');

        // Delete old login so not to cause any issues if there is an error
        //echo $this->session->data['api_id'];
        // echo "session api id";

        unset($this->session->data['api_id']);

        $keys = [
            'username',
            'password',
        ];

        foreach ($keys as $key) {
            if (!isset($this->request->post[$key])) {
                $this->request->post[$key] = '';
            }
        }

        $json = [];

        $this->load->model('account/api');
        $this->load->model('user/user');

        $api_info = $this->model_account_api->login($this->request->post['username'], $this->request->post['password']);
        if ($api_info['user_id']) {
            $user_info = $this->model_user_user->getUser($api_info['user_id']);
        }
        // echo $this->request->post['username'], $this->request->post['password'];

        if ($api_info['status']) {
            if (!isset($this->session->data['api_id'])) {
                $this->session->data['api_id'] = $api_info['user_id'];
            }
            //echo $this->request->post['groups'];exit;
            if (isset($this->request->post['groups']) && count($this->request->post['groups']) > 0) {
                if (in_array($user_info['user_group'], $this->request->post['groups'])) {
                    $this->session->data['api_id'] = $api_info['user_id'];
                    $json['success'] = $this->language->get('text_success');
                } else {
                    $this->response->addHeader('Content-Type: application/json');
                    $this->response->setOutput(json_encode(['error' => 'Not authorized to access this api']));
                    $this->response->output();
                    die();
                }
            } else {
                $json['success'] = $this->language->get('text_success');
            }
        } else {
            //print_r("else");
            $json['error'] = $this->language->get('error_login');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addsendpushnotification() {
        $this->load->model('account/customer');
        $customers = $this->model_account_customer->getCustomerById($this->request->post['customer_id']);
        $log = new Log('error.log');
        $log->write('customers');
        $log->write($customers);
        $log->write('customers');
        
        foreach ($customers as $customer) {
            $sen['customer_id'] = '';
            $log->write($customer['customer_id'].' '.$customer['device_id']);
            $ret = $this->emailtemplate->sendDynamicPushNotification($customer['customer_id'], $customer['device_id'], $this->request->post['message'], $this->request->post['title'], $sen);
        }
        
        //$ret = $this->emailtemplate->sendPushNotification($this->request->post['vendor_id'], $this->request->post['device_id'], $this->request->post['order_id'], $this->request->post['store_id'], $this->request->post['message'], $this->request->post['title']);
        $json['response'] = $ret;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
