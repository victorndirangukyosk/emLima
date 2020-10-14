<?php

class ControllerApiLandingpagedetails extends Controller
{
    private $error = []; 
     
    public function getDetails() {
        $json = [];
        try{
        $json['status'] = 200;
        // $json['data'] = [];
        // $json['message'] = [];
        $this->load->model('sale/order');
        $json['OrdersCount']=$this->model_sale_order->getOrdersCount();
        $json['CustomersCount']=$this->model_sale_order->getCustomersCount();
        $json['FarmersCount']=$this->model_sale_order->getFarmersCount()??0;
        }
        catch(Exception $ex)
        {
            $json['status'] = 500;
        }
        finally{

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }


    }
}
