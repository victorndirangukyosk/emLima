<?php

class ControllerApiCustomerFeedback extends Controller
{
    private $error = [];

    
    public function addFeedback()//saveFeedback
    {        
       
        //  echo "<pre>";print_r($this->request->post);die;
        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];
        //params selectedorderid,feedback_type,comments,selectissuetype,rating_id
        try{       

            $log = new Log('error.log');
            $log->write('Feedback Api '.$this->customer->getId());
            $log->write('Feedback Api '.echo "<pre>";print_r($this->request->post););
             
        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm() ) {
            // $this->model_account_feedback->saveFeedback($this->request->post);
           
            $log->write('Validated Feedback Api'.$this->customer->getId());
            $this->load->model('account/customer');
            // $stats= $this->model_account_customer->addCustomerIssue($this->customer->getId(), $this->request->post);
            $stats= $this->model_account_customer->addCustomerfeedback($this->customer->getId(), $this->request->post);
           
            if($stats==true) 
         {          

         $json['message'][] = ['type' => '', 'body' => 'Thanks for your feedback'];
         } else {
         $json['status'] = 600;

         $json['message'][] = ['type' => '', 'body' => 'Feedback not saved'];
           }

        }
        else {
            

            $json['status'] = 500;

            $json['message'][] = ['type' => '', 'body' => 'Some Fields Data Missing'];

            http_response_code(400);
        }

       
 
    }
    catch(exception $ex)
    {
        $json['status'] = 500;

        $json['message'][] = ['type' => '', 'body' => 'Feedback not saved'];

    }  
           
        finally{

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
        }
    }


    protected function validateForm()
    {
        if (empty($this->request->post['rating_id'])) {
            $this->error['rating_id'] = "Rating Required";
        }
        if (empty($this->request->post['feedback_type'])) {
            $this->error['feedback_type'] = "Feedback Type Required";
        }   

        if (empty($this->request->post['comments'])) {
            $this->error['comments'] = 'Comments Required';
        }
 
        return !$this->error;
    }

     
}
