<?php

class ControllerApiCustomerApplypezesha extends Controller {

    private $error = [];


    public function getInfo() {

        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];
        try{

        $this->load->language('account/pezesha');
        $this->load->model('account/credit');
        $this->load->model('account/customer');
        if($this->customer->getId()!=null && $this->customer->getId()!="")
        {
        $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
        $data['national_id'] = $customer_info['national_id'];
        $data['kra'] = $customer_info['kra'];
        $data['dob'] = $customer_info['dob'] != NULL ? date('d/m/Y', strtotime($customer_info['dob'])) : NULL;
        $data['gender'] = $customer_info['gender'];
        $data['customer_id'] = $customer_info['customer_id'];
        $json['data'] = $data;
        $json['message'][] = ['type' => 'success', 'body' => 'Info fetched'];

        // echo "<pre>";print_r($json);die;
        }
        else{
            $json['status'] = 10013;
            $json['message'][] = ['type' => 'error', 'body' => 'Please login again'];
            http_response_code(400);
        }

        }
        catch(exception $ex)
        {
            $json['status'] = 10013;
            $json['message'][] = ['type' => 'error', 'body' => $ex->getMessage()];
            http_response_code(400);
        }
        finally{
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
        }

    }


    public function addupdatecustomerinfo() {
        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];
        try{
            if($this->customer->getId()==null || $this->customer->getId()==null)
            {
                $json['status'] = 10013;
            $json['message'][] = ['type' => 'error', 'body' => 'Please login again'];
            http_response_code(400);
            return;

            }
        $log = new Log('error.log');
        $log->write($this->request->post);
        $this->load->model('account/customer');
        $data['gender'] = $this->request->post['gender'];
        $data['kra'] = $this->request->post['kra'];
        $data['national_id'] = $this->request->post['national_id'];
        $data['credit_period'] = $this->request->post['credit_period'];

        $data['customer_id'] =$this->customer->getId();
        $date = $this->request->post['dob'];
        if (isset($date) && $date != NULL) {
            $date = DateTime::createFromFormat('d/m/Y', $date);
            $this->request->post['dob'] = $date->format('Y-m-d');
        } else {
            $this->request->post['dob'] = null;
        }
        $data['dob'] = $this->request->post['dob'];

        if($data['gender']==null ||$data['kra']==null || $data['national_id']==null  )
        {
            $json['status'] = 10013;
            $json['message'][] = ['type' => 'error', 'body' => 'Gender ,KRA,National_ID,DoB are mandatory.Please check. '];
            http_response_code(400);
            return;
        }

        if($this->request->files['copy_of_certificate_of_incorporation']==null ||$this->request->files['copy_of_bussiness_operating_permit']==null || $this->request->files['copy_of_id_of_bussiness_owner_managing_director']==null  )
        {
            $json['status'] = 10013;
            $json['message'][] = ['type' => 'error', 'body' => 'Please Upload appropriate three mentioned files '];
            http_response_code(400);
            return;
        }


     $this->model_account_customer->updatecustomerinfo($this->customer->getId(), $data);
    //  echo "<pre>";print_r($this->request->files['copy_of_certificate_of_incorporation']);
    //  echo "<pre>";print_r($this->request->files['copy_of_bussiness_operating_permit']);
    //  echo "<pre>";print_r($this->request->files['copy_of_id_of_bussiness_owner_managing_director']);die;
     
     $file_upload_status = $this->pezeshafiles($this->request->files['copy_of_certificate_of_incorporation'],$this->request->files['copy_of_bussiness_operating_permit'],$this->request->files['copy_of_id_of_bussiness_owner_managing_director']);
     if ($file_upload_status==false)
     {
        $json['status'] = 10013;

        $json['uploadstatus']    = false;
        $json['message'][] = ['type' => 'error', 'body' => 'Please upload correct file and data'];
        http_response_code(400);
        return;
    }


     
     $json['message'][] = ['type' => 'success', 'body' => 'Updated'];
    //  $data['asd']=$this->addpezesha();
     $json['data'] = $data;

        }
        catch(exception $ex)
        {
            $json['status'] = 10013;
            $json['message'][] = ['type' => 'error', 'body' => $ex->getMessage()];
            http_response_code(400);
        }
        finally{
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
        }
    }

    public function addpezesha() {
        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];
        try{
            if($this->customer->getId()==null || $this->customer->getId()==null)
            {
                $json['status'] = 10013;
            $json['message'][] = ['type' => 'error', 'body' => 'Please login again'];
            http_response_code(400);
            return;

            }

            
        $log = new Log('error.log');
        $log->write($this->request->post);
        $val=1;

        $userregistration = $this->load->controller('account/applypezesha/userregistration');

                // echo "<pre>";print_r(json_encode($userregistration));die;

        $data['accrptterms'] = $this->load->controller('account/applypezesha/accrptterms',1);
        $data['dataingestion'] = $this->load->controller('account/applypezesha/dataingestion',1);

     //    $json['message'][] = ['type' => 'success', 'body' => 'Updated'];
        $json['data'] = $data;

        }
        catch(exception $ex)
        {
            $json['status'] = 10013;
            $json['message'][] = ['type' => 'error', 'body' => $ex->getMessage()];
            http_response_code(400);
        }
        finally{
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
        }
    }

   
    public function pezeshafiles($file_data1,$file_data2,$file_data3) {
        $log = new Log('error.log');

        try{
        // echo "<pre>";print_r($file_datas);die;

        if ((isset($file_data1)) && (is_uploaded_file($file_data1['tmp_name']))) {
        
        $file_name = $file_data1['name'];
        $temp_file_location = $file_data1['tmp_name'];

        $log->write($this->request->files);
        $mail = new Mail();
        $sthree_doc_url = $mail->UploadToSThree($file_name, $temp_file_location, 'copy_of_certificate_of_incorporation', $this->customer->getId());
        $log->write('sthree_doc_url');
        $log->write($sthree_doc_url);
        $log->write('sthree_doc_url');
        if ($sthree_doc_url != NULL) {
            $this->load->model('account/customer');
            $this->model_account_customer->SaveCustomerFiles($this->customer->getId(), $sthree_doc_url, 'PEZESHA', 'Copy Of Certificate Of Incorporation');
        }
    }
    if ((isset($file_data2)) && (is_uploaded_file($file_data2['tmp_name']))) 

    {
        $file_name = $file_data2['name'];
        $temp_file_location = $file_data2['tmp_name'];

        $log->write($this->request->files);

        $mail = new Mail();
        $sthree_doc_url = $mail->UploadToSThree($file_name, $temp_file_location, 'copy_of_bussiness_operating_permit', $this->customer->getId());
        $log->write('sthree_doc_url');
        $log->write($sthree_doc_url);
        $log->write('sthree_doc_url');

        if ($sthree_doc_url != NULL) {
            $this->load->model('account/customer');
            $this->model_account_customer->SaveCustomerFiles($this->customer->getId(), $sthree_doc_url, 'PEZESHA', 'Copy Of Bussiness Operating Permit');
        }

    }
    if ((isset($file_data3)) && (is_uploaded_file($file_data3['tmp_name']))) {

        $file_name = $file_data3['name'];
        $temp_file_location = $file_data3['tmp_name'];

        $log->write($this->request->files);

        $mail = new Mail();
        $sthree_doc_url = $mail->UploadToSThree($file_name, $temp_file_location, 'copy_of_id_of_bussiness_owner_managing_director', $this->customer->getId());
        $log->write('sthree_doc_url');
        $log->write($sthree_doc_url);
        $log->write('sthree_doc_url');

        $this->load->model('account/customer');
        $this->model_account_customer->SaveCustomerFiles($this->customer->getId(), $sthree_doc_url, 'PEZESHA', 'Copy Of ID Of Bussiness Owner / Managing Director');
   

    }
    return true;
}
catch(exception $e)
{
    return false;
}


    }  


}
