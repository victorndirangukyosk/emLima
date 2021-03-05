<?php

 

class ControllerCommonCheck extends Controller
{
      private $error = [];
    
      //To check whether the requested API, is valid to access by Loged in customer
    public function checkValidCustomer($dataparams)
    { 
        

        $log = new Log('error.log');
        $log->write('Log 1.cs');
        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        // $json['message'] = [];         
        $json['valid'] = "false"; 
        if($dataparams!=null)
        {
       $customername=$dataparams[0];
        $customername=$dataparams[1];
        $companyname=$dataparams[2];  
        }  
       
        try{      
        $LogincustomerID=$this->customer->getId();
        $log->write($LogincustomerID);
        $this->load->model('common/customer');
        $data['customers'] = [];
        $customers = $this->model_common_customer->getValidCustomers($LogincustomerID);
    
        //   echo "<pre>";print_r($customers);die;         
        // $data['token'] = $this->session->data['token'];
          // echo "<pre>";print_r($data);die;
         $json['data'] =$customers;
        //  $json['valid'] =false;
         foreach ($customers as $cust) {
            if($customerid!=null && $customerid!="")
            {
                if($cust['customer_id']==$customerid)
                {
                $json['valid'] ="true";
                }
            }
            else if($companyname!=null && $companyname!="")
            {
                if($cust['company_name']==$companyname)
                {
                $json['valid'] ="true";
                }

            }
            else if($customername!=null && $customername!="")
            {
                if($cust['customer_name']==$customername)
                {
                $json['valid'] ="true";
                }
            }
         }

        //  echo "<pre>";print_r($json);die;
         
        //  $this->response->addHeader('Content-Type: application/json');
        //  $this->response->setOutput(json_encode($json));
        return $json['valid'];
        }
        catch(exception $ex)
        {
            $json['status'] = 400;
            $json['data'] = [];
            $json['message'] = "Error in fetching data.";
            $json['valid'] =false;
            // $this->response->addHeader('Content-Type: application/json');
            // $this->response->setOutput(json_encode($json));
            return $json['valid'];

        }
    }

    
 
}
