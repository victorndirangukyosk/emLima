<?php

 

class ControllerApiCustomerCommon extends Controller
{
      private $error = [];
    
      //To check whether the requested API, is valid to access by Loged in customer
    public function getcheckValidCustomer($customerid=null,$customername=null,$companyname=null)
    { 

        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];         
        $json['valid'] = [];         
        try{      
        $LogincustomerID=$this->customer->getId();
        $this->load->model('common/customer');
        $data['customers'] = [];
        $result = $this->model_common_customer->getValidCustomers($LogincustomerID);
    
        //  echo "<pre>";print_r($data['customers']);die;         
        // $data['token'] = $this->session->data['token'];
          // echo "<pre>";print_r($data);die;
         $json['data'] =$data;
         if(1==1)
         {
         $json['valid'] =true;
         }
         else $json['valid'] =false;
         $this->response->addHeader('Content-Type: application/json');
         $this->response->setOutput(json_encode($json));
        }
        catch(exception $ex)
        {
            $json['status'] = 400;
            $json['data'] = [];
            $json['message'] = "Error in fetching data.";
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));

        }
    }

    
 
}
