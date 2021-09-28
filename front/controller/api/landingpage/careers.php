<?php

class ControllerApiLandingpagecareers extends Controller
{
    private $error = [];     
    

    public function getcareers($id=0,$successmessage="",$errormessage="") {
        $json = [];
        try{
            $json['status'] = 200;
            $json['site_key'] = $this->config->get('config_google_captcha_public');
            $json['action'] = $this->url->link('common/home/savecareers','','SSL');
            $json['message'] = $successmessage;
            $json['errormessage'] = $errormessage;

            $log = new Log('error.log');
            $log->write($this->request->get['filter_category']);
            $log->write($this->request->get['filter_type']);
            $log->write($this->request->get['filter_location']);

            if (isset($this->request->get['filter_category'])) {
                if($this->request->get['filter_category']!="All Job Category")
                {
                $filter_category = $this->request->get['filter_category'];
                }
                else
                {
                    $filter_category = null;
                }
            } else {
                $filter_category = null;
            }

            if (isset($this->request->get['filter_type'])) {
                if($this->request->get['filter_type']!="All Job Type")
                {
                $filter_type = $this->request->get['filter_type'];
                }
                else
                {
                    $filter_type = null;
                }
            } else {
                $filter_type = null;
            }

            if (isset($this->request->get['filter_location'])) {
                if($this->request->get['filter_location']!="All Job Location")
                {
                $filter_location = $this->request->get['filter_location'];
                }
                else
                {
                    $filter_location = null;
                }
            } else {
                $filter_location = null;
            }

        
            $filter_data = [
                'filter_category' => $filter_category,
                'filter_type' => $filter_type,
                'filter_location' => $filter_location, 
                // 'sort' => $sort,
                // 'order' => $order,
                // 'start' => ($page - 1) * $this->config->get('config_limit_admin'),
                // 'limit' => $this->config->get('config_limit_admin'),
            ]; 

            $this->load->model('information/careers');


            $json['jobpositions'] = $this->model_information_careers->getJobPositions($filter_data);
            $json['job_categories'] =$this->model_information_careers->getJobCategories();
            $json['job_types']=$this->model_information_careers->getJobTypes();
            $json['job_locations']=$this->model_information_careers->getJobLocations();
 
            if($filter_data['filter_category']!=null)
            $json['job_category_name'] = $filter_data['filter_category'];
            if($filter_data['filter_type']!=null)
            $json['job_type_name'] = $filter_data['filter_type'];
            if($filter_data['filter_location']!=null)
            $json['job_location_name'] = $filter_data['filter_location'];
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
       
        //   echo "<pre>";print_r($json);die;
    
    }

    public function getcareersbyid($id=0,$successmessage="",$errormessage="") {
        $json = [];
        try{
            $json['status'] = 200;
            $json['site_key'] = $this->config->get('config_google_captcha_public');
            $json['action'] = $this->url->link('common/home/savecareers','','SSL');
            $json['message'] = $successmessage;
            $json['errormessage'] = $errormessage;

            $log = new Log('error.log');
            $log->write($this->request->get['id']);
             
            if (isset($this->request->get['id'])) {
                $filter_data['id'] = $this->request->get['id'];
            } else {
                $filter_data['id'] = $id;
            } 
            $this->load->model('information/careers');


            $json['jobpositions'] = $this->model_information_careers->getJobPositions($filter_data);
            $description = htmlspecialchars_decode($json['jobpositions'][0]['roles_responsibilities']);
            $json['jobpositions'][0]['roles_responsibilities'] = $description;
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
       
        //   echo "<pre>";print_r($json);die;
    
    }

    public function addcareer() {

        
        $json = [];
        try{
        $this->load->model('information/careers');
          if (('POST' == $this->request->server['REQUEST_METHOD']) ) {
        $file_upload_status = $this->FeatureFileUpload($this->request->files);
        $file_data =$this->request->files;
        $log = new Log('error.log');
        $log->write($file_upload_status);
        $log->write('sr');
          if ($file_upload_status != NULL && $file_upload_status['status'] == TRUE && $file_upload_status['file_name'] != NULL) {
            $this->load->model('setting/setting');
            if(isset($this->request->post['careers-first-name'])){
            $first_name=str_replace("'", "", $this->request->post['careers-first-name']);
            }
            else {
                $first_name=str_replace("'", "", $this->request->post['full_name']);

            }
            if(isset($this->request->post['careers-email'])){
            $email=str_replace("'", "", $this->request->post['careers-email']);
            }
            else {
                $email=str_replace("'", "", $this->request->post['email']);

            }
            if(isset($this->request->post['careers-phone-number'])){
            $phone=str_replace("'", "", $this->request->post['careers-phone-number']);
            }
            else
            {
            $phone=str_replace("'", "", $this->request->post['telephone']);

            }

            if(isset($this->request->post['careers-job-id'])){
                $job_id=str_replace("'", "", $this->request->post['careers-job-id']);
                }
                else
                {
                $job_id=str_replace("'", "", $this->request->post['job_id']);
    
                }

                if(isset($this->request->post['careers-cover-letter'])){
                    $cover_letter=str_replace("'", "", $this->request->post['careers-cover-letter']);
                    }
                    else
                    {
                    $cover_letter=str_replace("'", "", $this->request->post['cover_letter']);
        
                    }

                    $jobposition=$this->request->post['job_position'];
            
            $id=$this->model_information_careers->createCareers($first_name, str_replace("'", "", $this->request->post['lastname']), str_replace("'", "", $this->request->post['role']), str_replace("'", "", $this->request->post['yourself']), $email, $phone, $job_id, $cover_letter, $file_upload_status['file_name'], $jobposition);
             
            $json['uploadstatus']    = true;
            $json['message']  = 'Thank you we will contact you shortly';
            if ($id>0) {
           
                //send mail notification to 'stalluri@technobraingroup.com'
                // $subject = $this->emailtemplate->getSubject('Customer', 'customer_1', $data);
                // $message = $this->emailtemplate->getMessage('Customer', 'customer_1', $data);
                $subject = "Job Request";
                if($jobposition!="")
                $message = "Following details are received for the job position - ". $jobposition."<br>";
               else
                $message = "Following details are received.  <br>";
                $message = $message ."<li> Full Name :".$first_name ."</li><br><li> Email :".$email ."</li><br><li> Phone :".$phone ."</li><br>";
               
                $this->load->model('setting/setting');
                $email = $this->model_setting_setting->getEmailSetting('careers');
                 
                if(strpos( $email,"@")==false)//if mail Id not set in define.php
               {
               $email = "sridivya.talluri@technobraingroup.com";
               }

                // $bccemail = "sridivya.talluri@technobraingroup.com";
                //  echo "<pre>";print_r($file_data);die;
                $filepath = DIR_UPLOAD . "careers/" . $file_upload_status['file_name'];
                $mail = new Mail($this->config->get('config_mail'));
                $mail->setTo($email);
                $mail->setBCC($bccemail);
                $mail->setFrom($this->config->get('config_from_email'));
                $mail->setSender($this->config->get('config_name'));
                $mail->setSubject($subject);
                $mail->setHTML($message);
                $mail->addAttachment($filepath);
                $mail->send();
            } 

        } else {
            $json['uploadstatus']    = false;
            $json['message']  = 'Please upload correct file and data';
          }
        
          } 

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
    public function FeatureFileUpload($file_data) {
        $status = array();
        // echo "<pre>";print_r($file_data);die;
        if(isset($file_data['careers_resume']))
        {
            $file_data['careers-resume']= $file_data['careers_resume'];
        }
        if ((isset($file_data['careers-resume'])) && (is_uploaded_file($file_data['careers-resume']['tmp_name']))) {
           
            if($file_data['careers-resume']['type']!="application/msword" && $file_data['careers-resume']['type'] != "application/vnd.openxmlformats-officedocument.wordprocessingml.document" && $file_data['careers-resume']['type'] != "application/octet-stream" && $file_data['careers-resume']['type'] != "application/pdf")
            {
                return $status = array('status' => FALSE, 'file_name' => '');
            }
            if($file_data['careers-resume']['size']> 5000000)
            {
                return $status = array('status' => FALSE, 'file_name' => '');
            }
            
           
            if (!file_exists(DIR_UPLOAD . 'careers/')) {
                mkdir(DIR_UPLOAD . 'careers/', 0777, true);
            }//md5(mt_rand())
            $file_name = (rand(10,100) ). '' . $file_data['careers-resume']['name'];
            if (move_uploaded_file($file_data['careers-resume']['tmp_name'], DIR_UPLOAD . 'careers/' . $file_name)) {
                return $status = array('status' => TRUE, 'file_name' => $file_name);
            } else {
                return $status = array('status' => FALSE, 'file_name' => '');
            }
        }
    }

    public function addcareerJson() {



        $json = file_get_contents('php://input');
        // Converts it into a PHP object
        $data = json_decode($json);
        // echo "<pre>";print_r($data);die;
         //writing like this,as not to disturb model methods
         $this->request->post['careers-first-name']=$data->full_name;
         $this->request->post['careers-email']=$data->email;
         $this->request->post['careers-phone-number']=$data->telephone;
         $this->request->post['job_id']=$data->job_id;
         $this->request->post['cover_letter']=$data->cover_letter;




        $json = [];
        try{
        $this->load->model('information/careers');
          if (('POST' == $this->request->server['REQUEST_METHOD']) ) {
           $this->load->model('setting/setting');
            $first_name=str_replace("'", "", $this->request->post['careers-first-name']);
            $email=str_replace("'", "", $this->request->post['careers-email']);
            $phone=str_replace("'", "", $this->request->post['careers-phone-number']);
            $jobid=str_replace("'", "", $this->request->post['job_id']);
            $coverletter=str_replace("'", "", $this->request->post['cover_letter']);
            $id=$this->model_information_careers->saveCareers($first_name,  $email, $phone, $jobid, $coverletter);
                 
            $json['message']  = 'Thank you we will contact you shortly';
            $json['id']  = $id;
           
          } 

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

    //below method fields need to verify
    public function addcareerFileUpload() { 

        $json = [];
        try{
        $this->load->model('information/careers');
          if (('POST' == $this->request->server['REQUEST_METHOD']) ) {
        $file_upload_status = $this->FeatureFileUpload($this->request->files);
        $file_data =$this->request->files;
        $job_id=$this->request->post['job_id'];
        $jobposition=$this->request->post['job_position'];
        
          if ($file_upload_status != NULL && $file_upload_status['status'] == TRUE && $file_upload_status['file_name'] != NULL && $job_id !=NULL) {
            $this->load->model('setting/setting');
             $id=$this->model_information_careers->createCareers($first_name, str_replace("'", "", $this->request->post['lastname']), str_replace("'", "", $this->request->post['role']), str_replace("'", "", $this->request->post['yourself']), $email, $phone, str_replace("'", "", $this->request->post['careers-job-id']), str_replace("'", "", $this->request->post['careers-cover-letter']), $file_upload_status['file_name'], str_replace("'", "", $this->request->post['careers-job-position']));
             
              $json['uploadstatus']    = true;
            $json['message']  = 'File Uploaded.';
            if ($id>0) {
           
                //send mail notification to 'stalluri@technobraingroup.com'
                // $subject = $this->emailtemplate->getSubject('Customer', 'customer_1', $data);
                // $message = $this->emailtemplate->getMessage('Customer', 'customer_1', $data);
                $subject = "Job Request";
                if($jobposition!="")
                $message = "Following details are received for the job position - ". $jobposition."<br>";
               else
                $message = "Following details are received.  <br>";
                $message = $message ."<li> Full Name :".$first_name ."</li><br><li> Email :".$email ."</li><br><li> Phone :".$phone ."</li><br>";
               
                $this->load->model('setting/setting');
                $email = $this->model_setting_setting->getEmailSetting('careers');
                 
                if(strpos( $email,"@")==false)//if mail Id not set in define.php
               {
               $email = "sridivya.talluri@technobraingroup.com";
               }

                // $bccemail = "sridivya.talluri@technobraingroup.com";
                //  echo "<pre>";print_r($file_data);die;
                $filepath = DIR_UPLOAD . "careers/" . $file_upload_status['file_name'];
                $mail = new Mail($this->config->get('config_mail'));
                $mail->setTo($email);
                $mail->setBCC($bccemail);
                $mail->setFrom($this->config->get('config_from_email'));
                $mail->setSender($this->config->get('config_name'));
                $mail->setSubject($subject);
                $mail->setHTML($message);
                $mail->addAttachment($filepath);
                $mail->send();
            } 

        } else {
            $json['uploadstatus']    = false;
            $json['message']  = 'Please upload correct file and data';
          }
        
          } 

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
