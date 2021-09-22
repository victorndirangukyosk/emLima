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
        $json['Login']=$this->url->link('account/login/customer','','SSL');
        $json['Registraion']=$this->url->link('account/login/newCustomer','','SSL');
        $json['FarmerRegistraion']=$this->url->link('account/login/farmer','','SSL');

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


        //         $json = file_get_contents('php://input');

        // // Converts it into a PHP object
        // $data = json_decode($json);
        // echo "<pre>";print_r($data->firstname);die;


        $json = [];

        try{
            $this->load->model('information/partners');
            if(empty($this->request->post['firstname']) || empty($this->request->post['lastname'])|| empty($this->request->post['designation'])|| empty($this->request->post['company'])|| empty($this->request->post['email'])|| empty($this->request->post['phone']))
            {
                $json['status'] = 500;
                $json['error'] ="Please enter data";
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($json));
                return;
            }
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
             $this->load->language('account/farmerregister');
            // $this->request->post['telephone'] = preg_replace('/[^0-9]/', '', $this->request->post['telephone']);
             $log = new Log('error.log');
            if (('POST' == $this->request->server['REQUEST_METHOD'])  ) {
                // $farmer_id = $this->model_account_farmer->addFarmer($this->request->post);
                //add farmer is not fully implemented .So just send MAil 

                 //send mail notification to 'stalluri@technobraingroup.com'
                 $first_name=str_replace("'", "", $this->request->post['farmer-first-name']);
                 $last_name=str_replace("'", "", $this->request->post['farmer-last-name']);
                 $email=str_replace("'", "", $this->request->post['farmer-email']);
                 $phone=str_replace("'", "", $this->request->post['farmer-phone']);
                 $type=str_replace("'", "", $this->request->post['farmer-type']);
                 $location=str_replace("'", "", $this->request->post['farmer-location']);
                 $produce=str_replace("'", "", $this->request->post['farmer-produce-grown']);
                 
                $subject = "Farmer Registration";
                
                $message = "Following farmer details are received.  <br>";
                $message = $message ."<li> First Name :".$first_name ."</li><br><li> Last Name :".$last_name ."</li><br><li> Email :".$email ."</li><br><li> Phone :".$phone ."</li><br><li> Type :".$type ."</li><br><li> Location :".$location ."</li><br><li> Produce Grown :".$produce ."</li><br>";
               
            //     if(strpos(Career_Mail_ID,"@")==true)//if mail Id not set in define.php
            //    {
            //     $email = Career_Mail_ID;
            //    } 
            //    else
            //    {
            //     $email = "sridivya.talluri@technobraingroup.com";

            //    }

                   $this->load->model('setting/setting');
                    $email = $this->model_setting_setting->getEmailSetting('careers');
                     
                    if(strpos( $email,"@")==false)//if mail Id not set in define.php
                   {
                   $email = "sridivya.talluri@technobraingroup.com";
                   }

                $mail = new Mail($this->config->get('config_mail'));
                $mail->setTo($email);
                $mail->setBCC($bccemail);
                $mail->setFrom($this->config->get('config_from_email'));
                $mail->setSender($this->config->get('config_name'));
                $mail->setSubject($subject);
                $mail->setHTML($message);
                $mail->send();

                $json['status'] = 200;    
                $json['message'] = $this->language->get('register_mail_sent');    
                $json['success_message'] = $this->language->get('text_success');
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

    public function addNewFarmer() {//account/farmerregister/register
        // above metho in use , omewhere
        $json = [];
        $json['status'] = false;
        try{
            $this->load->model('account/farmer');
             $this->load->language('account/farmerregister');
            // $this->request->post['telephone'] = preg_replace('/[^0-9]/', '', $this->request->post['telephone']);
             $log = new Log('error.log');
            if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validate() ) {
                
                $farmer_id = $this->model_account_farmer->addNewFarmer($this->request->post);

                $farmer_info['firstname'] = $this->request->post['first_name'];
                $farmer_info['lastname'] = $this->request->post['last_name'];
                $farmer_info['store_name'] = 'KwikBasket';
                // $farmer_info['order_link'] = HTTPS_SERVER . 'index.php?path=common/farmer';
                $farmer_info['system_name'] = 'KwikBasket';
    
                $log->write('SMS SENDING');
                $sms_message = $this->emailtemplate->getSmsMessage('Customer', 'customer_9', $farmer_info);
                $log = new Log('error.log');
                $log->write($sms_message);
                // send message here
                if ($this->emailtemplate->getSmsEnabled('Customer', 'customer_9')) {
                    $log->write('FARMER SMS NOTIFICATION');
                    $ret = $this->emailtemplate->sendmessage($this->request->post['telephone'], $sms_message);
                }
                try {
                    if ($this->emailtemplate->getEmailEnabled('Customer', 'customer_9')) {
                        $subject = $this->emailtemplate->getSubject('Customer', 'customer_9', $farmer_info);
                        $message = $this->emailtemplate->getMessage('Customer', 'customer_9', $farmer_info);
    
                        $mail = new mail($this->config->get('config_mail'));
                        $mail->setTo($this->request->post['email']);
                        $mail->setFrom($this->config->get('config_from_email'));
                        $mail->setSubject($subject);
                        $mail->setSender($this->config->get('config_name'));
                        $mail->setHtml($message);
                        $mail->send();
                    }
                } catch (Exception $e) {
                    
                }

                    

                $json['status'] =  // Add to activity log
                $this->load->model('account/activity');
    
                $activity_data = [
                    'farmer_id' => $farmer_id,
                    'name' => $this->request->post['first_name'] . ' ' . $this->request->post['last_name'],
                    'user_group_id' => $this->config->get('config_farmer_group_id')
                ];
    
                $log->write('farmer registration');
                $this->model_account_activity->addFarmerActivity('farmer_register', $activity_data);
    
                $json['status'] = true; 
                $json['success_message'] = $this->language->get('text_success'); 200;    
                $json['message'] = $this->language->get('register_mail_sent');    
                $json['success_message'] = $this->language->get('text_success');
            } 

            else {
                $log->write('outside form 3nr dime');
                // $data['entry_submit'] = $this->language->get('entry_submit');
                // $data['entry_email_address'] = $this->language->get('entry_email_address');
                // $data['entry_phone'] = $this->language->get('entry_phone');
                // $data['heading_text'] = $this->language->get('heading_text');
    
                if (isset($this->error['warning'])) {
                    $json['error_warning'] = $this->error['warning'];
                } else {
                    $json['error_warning'] = '';
                }
    
                if (isset($this->error['name'])) {
                    $json['error_name'] = $this->error['name'];
                } else {
                    $json['error_firstname'] = false;
                }
    
                if (isset($this->error['email'])) {
                    $json['error_email'] = $this->error['email'];
                } else {
                    $json['error_email'] = false;
                }
    
                if (isset($this->error['telephone'])) {
                    $json['error_telephone'] = $this->error['telephone'];
                } else {
                    $json['error_telephone'] = false;
                }
    
                if (isset($this->error['telephone_exists'])) {
                    $json['error_telephone_exists'] = $this->error['telephone_exists'];
                } else {
                    $json['error_telephone_exists'] = false;
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
    

    public function validate() {
        if ((utf8_strlen(trim($this->request->post['first_name'])) < 1) || (utf8_strlen(trim($this->request->post['first_name'])) > 32)) {
            $this->error['first_name'] = $this->language->get('error_name');
        }

        if ((utf8_strlen(trim($this->request->post['last_name'])) < 1) || (utf8_strlen(trim($this->request->post['last_name'])) > 32)) {
            $this->error['last_name'] = $this->language->get('error_name');
        }

        if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if ($this->model_account_farmer->getTotalfarmersByEmail($this->request->post['email'])) {
            $numb = $this->model_account_farmer->getfarmerByEmail($this->request->post['email']);

            if (isset($numb['mobile'])) {
                $this->error['warning'] = sprintf($this->language->get('error_exists_email'), $numb['mobile']);
            } else {
                $this->error['warning'] = $this->language->get('error_exists');
            }
        }

        if (false !== strpos($this->request->post['telephone'], '#') || empty($this->request->post['telephone'])) {
            $this->error['telephone'] = $this->language->get('error_telephone');
        }

        $this->request->post['telephone'] = preg_replace('/[^0-9]/', '', $this->request->post['telephone']);

        // echo "<pre>";print_r($this->request->post);die;

        if ($this->model_account_farmer->getTotalfarmersByPhone($this->request->post['telephone'])) {
            $this->error['telephone_exists'] = $this->language->get('error_telephone_exists');
            $this->error['warning'] = $this->language->get('error_telephone_exists');
        }

        return !$this->error;
    }


    public function addPartnerJson() {


        $json = file_get_contents('php://input');
        // Converts it into a PHP object
        $data = json_decode($json);
        // echo "<pre>";print_r($data->firstname);die;
        $json = [];

        try{
            $this->load->model('information/partners');
            if(empty($data->first_name) || empty($data->last_name)|| empty($data->designation)|| empty($data->company)|| empty($data->email)|| empty($data->phone))
            {
                $json['status'] = 500;
                $json['error'] ="Please enter data";
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($json));
                return;
            }
            //writing like this,as not to disturb model methods
            $this->request->post['firstname']=$data->first_name;
            $this->request->post['lastname']=$data->last_name;
            $this->request->post['designation']=$data->designation;
            $this->request->post['company']=$data->company;
            $this->request->post['email']=$data->email;
            $this->request->post['phone']=$data->phone;
            $this->request->post['description']=$data->description;

            // echo "<pre>";print_r($this->request->post);die;

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



    public function addNewFarmerJson() {//account/farmerregister/register
       

        $json = file_get_contents('php://input');
        // Converts it into a PHP object
        $data = json_decode($json);
        // echo "<pre>";print_r($data);die;
         //writing like this,as not to disturb model methods
         $this->request->post['first_name']=$data->first_name;
         $this->request->post['last_name']=$data->last_name;
         $this->request->post['email']=$data->email;
         $this->request->post['telephone']=$data->telephone; 

        $json = [];
        $json['status'] = false;
        try{
            $this->load->model('account/farmer');
             $this->load->language('account/farmerregister');
            // $this->request->post['telephone'] = preg_replace('/[^0-9]/', '', $this->request->post['telephone']);
             $log = new Log('error.log');
            if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validate() ) {
                
                $farmer_id = $this->model_account_farmer->addNewFarmer($this->request->post);

                $farmer_info['firstname'] = $this->request->post['first_name'];
                $farmer_info['lastname'] = $this->request->post['last_name'];
                $farmer_info['store_name'] = 'KwikBasket';
                // $farmer_info['order_link'] = HTTPS_SERVER . 'index.php?path=common/farmer';
                $farmer_info['system_name'] = 'KwikBasket';
    
                $log->write('SMS SENDING');
                $sms_message = $this->emailtemplate->getSmsMessage('Customer', 'customer_9', $farmer_info);
                $log = new Log('error.log');
                $log->write($sms_message);
                // send message here
                if ($this->emailtemplate->getSmsEnabled('Customer', 'customer_9')) {
                    $log->write('FARMER SMS NOTIFICATION');
                    $ret = $this->emailtemplate->sendmessage($this->request->post['telephone'], $sms_message);
                }
                try {
                    if ($this->emailtemplate->getEmailEnabled('Customer', 'customer_9')) {
                        $subject = $this->emailtemplate->getSubject('Customer', 'customer_9', $farmer_info);
                        $message = $this->emailtemplate->getMessage('Customer', 'customer_9', $farmer_info);
    
                        $mail = new mail($this->config->get('config_mail'));
                        $mail->setTo($this->request->post['email']);
                        $mail->setFrom($this->config->get('config_from_email'));
                        $mail->setSubject($subject);
                        $mail->setSender($this->config->get('config_name'));
                        $mail->setHtml($message);
                        $mail->send();
                    }
                } catch (Exception $e) {
                    
                }

                    

                $json['status'] =  // Add to activity log
                $this->load->model('account/activity');
    
                $activity_data = [
                    'farmer_id' => $farmer_id,
                    'name' => $this->request->post['first_name'] . ' ' . $this->request->post['last_name'],
                    'user_group_id' => $this->config->get('config_farmer_group_id')
                ];
    
                $log->write('farmer registration');
                $this->model_account_activity->addFarmerActivity('farmer_register', $activity_data);
    
                $json['status'] = true; 
                $json['success_message'] = $this->language->get('text_success'); 200;    
                $json['message'] = $this->language->get('register_mail_sent');    
                $json['success_message'] = $this->language->get('text_success');
            } 

            else {
                $log->write('outside form 3nr dime');
                // $data['entry_submit'] = $this->language->get('entry_submit');
                // $data['entry_email_address'] = $this->language->get('entry_email_address');
                // $data['entry_phone'] = $this->language->get('entry_phone');
                // $data['heading_text'] = $this->language->get('heading_text');
    
                if (isset($this->error['warning'])) {
                    $json['error_warning'] = $this->error['warning'];
                } else {
                    $json['error_warning'] = '';
                }
    
                if (isset($this->error['name'])) {
                    $json['error_name'] = $this->error['name'];
                } else {
                    $json['error_firstname'] = false;
                }
    
                if (isset($this->error['email'])) {
                    $json['error_email'] = $this->error['email'];
                } else {
                    $json['error_email'] = false;
                }
    
                if (isset($this->error['telephone'])) {
                    $json['error_telephone'] = $this->error['telephone'];
                } else {
                    $json['error_telephone'] = false;
                }
    
                if (isset($this->error['telephone_exists'])) {
                    $json['error_telephone_exists'] = $this->error['telephone_exists'];
                } else {
                    $json['error_telephone_exists'] = false;
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
