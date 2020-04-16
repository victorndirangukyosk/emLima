<?php

class ControllerApiLogout extends Controller {
    public function getLogout() {
        $this->load->language('api/logout');

        // Delete old login so not to cause any issues if there is an error

        //echo $this->session->data['api_id'];
        //echo "session api id";

        unset($this->session->data['api_id']);
        //unset($this->session->data['store_id']);

        
        $json['success'] = $this->language->get('text_success');
       

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
