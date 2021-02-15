<?php

class ControllerApiOrderStatuses extends Controller
{
    

    public function getOrderStatuses($args = [])
    {
        $this->load->model('tool/image');
        // $this->load->language('api/orderstatuses');
          
        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/orderstatuses');       
            
            $orderstatuses = $this->model_api_orderstatuses->getorderstatuses($args);
            $product['OrderStatuses'] = $orderstatuses;
            $json = $product;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    
}
