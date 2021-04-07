<?php

class ControllerCommonLoginAPI extends Controller
{


    
 //Customer login by admin for new app//same method replicated in admin module regarding token session
    //this is temperory method, need to validate, category pricing, two level approval process,tax
    public function getloginbyadmin() {//$args
        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];
        // $json['customer_id'] = $args['customer_id'];
        $json['url'] = [];

            //  echo "<pre>";print_r($this->request->get['customer_id']);die; 


        if (isset($this->request->get['customer_id'])) {//isset($args['customer_id'])
            $customer_id = $this->request->get['customer_id'];
        } else {
            $customer_id = 0;
        }

        //below token check added for security
        #region       


        // if ((isset($this->session->data['token']) && !isset($this->request->get['token'])) || ((isset($this->request->get['token']) && (isset($this->session->data['token']) && ($this->request->get['token'] != $this->session->data['token']))))) {
        //     $this->error['warning'] = $this->language->get('error_token');
        // }

            //   echo "<pre>";print_r($this->session);die; 
            
        // if(!isset($this->request->get['admintoken']))
        if(!isset($this->request->get['token']))
        {
            $json['message'] = 'Please check the URL ';                    
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
            return;
        }

        // if(!isset($this->session->data['admintoken']))
        if(!isset($this->session->data['token']))
        {
            $json['message'] = 'Please login again as Admin ';                    
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
            return;
        }
        if ((isset($this->session->data['token']) && !isset($this->request->get['token'])) || ((isset($this->request->get['token']) && (isset($this->session->data['token']) && ($this->request->get['token'] != $this->session->data['token']))))) {
        
            $json['message'] = 'Authentication Failed.Please login again as Admin';                    
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
            return;
        }

        #endregion

        $this->load->model('sale/customer');
        $customer_info = $this->model_sale_customer->getCustomer($customer_id);

        $this->load->model('account/customer');
        // $customer_info = $this->model_account_customer->getCustomer($args['customer_id']);
        $customer_info['devices'] = $this->model_account_customer->getCustomerDevices($api_info['customer_id']);
       
        if ($customer_info) {
            $token = md5(mt_rand()); 
            //  $this->model_account_customer->editToken($customer_id, $token);
             $this->model_sale_customer->editToken($customer_id, $token);
            if (isset($args['store_id'])) {
                $store_id = $args['store_id'];
            } else {
                $store_id = 0;
            }
             $this->load->model('setting/store');
             $store_info = $this->model_setting_store->getStore($store_id);
            
              // Add to activity log
              $this->load->model('account/activity');

            $activity_data = [
                'customer_id' => $customer_info['customer_id'],
                'name' => $customer_info['firstname'] . ' ' . $customer_info['lastname'],
            ];

            $this->model_account_activity->addActivity('login', $activity_data);

            //   if ($store_info) {
            //     //  $this->response->redirect($store_info['url'] . 'index.php?path=account/login/adminRedirectLogin&token=' . $token);
            //   } else {
            //     // $data=  $this->response->redirect(HTTP_CATALOG . 'index.php?path=account/login/adminRedirectLogin&token=' . $token);
               
            //     }

            // $data=  $this->adminRedirectLogin($token) ;this method should be called from front folder
           
            $data=  $this->response->redirect(HTTP_CATALOG . 'index.php?path=account/login/adminRedirectLoginAPI&token=' . $token);
           
            $json['message'] = 'User Logged';
            $json['customer_token'] = $token;
            $json['token'] = $data;
            $json['customer'] = $customer_info;

            
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));

        } else {
            $json['message'] = 'User not found'; 
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));

        }
    }

  

}
