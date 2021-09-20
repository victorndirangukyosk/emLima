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

    public function addFarmer() {//check thi metho account/farmerregister/register
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


    
}
