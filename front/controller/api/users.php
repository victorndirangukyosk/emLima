<?php

class ControllerApiUsers extends Controller
{
    public function getUsers($args = [])
    {
        $this->load->language('api/products');

        $json = [];

        //echo "api/product";

        //echo $args['id'];
        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('setting/setting');

            $json = $this->model_setting_setting->getUser($this->session->data['api_id']);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function editUser($args = [])
    {
        $this->load->language('api/products');
        $this->load->model('api/users');
        //index.php/api/users/3
        // echo "editUser";

        //echo "<pre>";print_r($args);die;

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            // $args['id'] this should be store product id

            //echo "<pre>";print_r($args);die;

            $this->model_api_users->editUser($args['id'], $args);

            $json['success'] = $this->language->get('text_success');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getVendors($args = [])
    {
        $this->load->language('api/products');

        $json = [];

        //echo "api/product";

        //echo $args['id'];
        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/users');

            $d = $this->model_api_users->getVendors($args);
            $json['total'] = $this->model_api_users->getTotalVendors($args);
            $json['vendors'] = $d;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
