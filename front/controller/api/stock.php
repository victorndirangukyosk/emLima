<?php

class ControllerApiStock extends Controller {

    public function getproductsStock($args = []) {
        
        $json = [];

       
            $this->load->model('api/stock');

            $available_stock = $this->model_api_stock->getAvaialbleStock($args);

            
            $json = $available_stock;
       

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

  
}
