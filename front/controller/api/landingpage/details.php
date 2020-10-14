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

    public function addPartner() {
        $json = [];
        try{
            $this->load->model('information/partners');
            $id= $this->model_information_partners->createPartners(str_replace("'", "", $this->request->post['firstname']), str_replace("'", "", $this->request->post['lastname']), str_replace("'", "", $this->request->post['designation']), str_replace("'", "", $this->request->post['company']), str_replace("'", "", $this->request->post['email']), str_replace("'", "", $this->request->post['phone']), str_replace("'", "", $this->request->post['description']));
            $json['status'] = 200;
            $json['message'] = 'Thank you we will contact you shortly';
            $json['id'] = $id;
        }
        catch(Exception $ex)
        {
            $json['status'] = 500;
            $json['error'] =$ex;
        }
        finally{

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }
    }

    public function addFarmer() {
        $json = [];
        try{
            $this->load->model('account/farmer');
            $id= $this->model_account_farmer->addFarmer(str_replace("'", "", $this->request->post['firstname']), str_replace("'", "", $this->request->post['lastname']), str_replace("'", "", $this->request->post['designation']), str_replace("'", "", $this->request->post['company']), str_replace("'", "", $this->request->post['email']), str_replace("'", "", $this->request->post['phone']), str_replace("'", "", $this->request->post['description']));
            $json['status'] = 200;
            $json['message'] = 'Thank you we will contact you shortly';
            $json['id'] = $id;
            
        }
        catch(Exception $ex)
        {
            $json['status'] = 500;
            $json['error'] =$ex;
        }
        finally{

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }
    }
}
