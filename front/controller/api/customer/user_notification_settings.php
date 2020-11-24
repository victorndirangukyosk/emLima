<?php

class Controllerapicustomerusernotificationsettings extends Controller {

    public function getCustomerNotificationSettings() {
 
        $json = [];
        $this->load->language('account/user_product_notes'); 
        $data['customer_info'] = '';
        if ('POST' != $this->request->server['REQUEST_METHOD']) {
            $this->load->model('account/customer');
            $json = $this->model_account_customer->getCustomer($this->customer->getId());
            
        }
        //for membership
        // $member_group_id = $this->config->get('config_member_group_id');
        // $customer_group_id = $this->customer->getGroupId();
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addCustomerNotificationSetting() {
        //   echo "<pre>";print_r($this->customer->getId());die; 
        try{
             
            // if (!$this->customer->isLogged()) {
            //     // $this->session->data['redirect'] = $this->url->link('account/profileinfo', '', 'SSL');
            //     // $this->response->redirect($this->url->link('account/login', '', 'SSL'));
            //     $json['status'] = 10014;
            //     $json['error'] = "Customer not Logged In";
            //     return;
            // }
             
            
        $log = new Log('error.log');
        $log->write($this->request->post['customer_id']);
        $log->write($this->request->post['notification_id']);
        $log->write($this->request->post['active_status']);

        $customer_id = $user_id = $this->request->post['customer_id'];
        $notification_id = $this->request->post['notification_id'];
        $active_status = $this->request->post['active_status'];
        $active_status_text = $active_status == 1 ? 'Enabled' : 'Disabled';

        $this->load->model('account/customer');
        if(isset($customer_id) && isset($notification_id)  && isset($active_status) ){
        $this->model_account_customer->customernotifications($user_id, $active_status, $notification_id);
        }
        else{
            $json['status'] =500;
            $json['Error'] ="Please pass appropriate params";
            return;
        }
        // Add to activity log
        $this->load->model('account/activity');

        $activity_data = [
            'customer_id' => $this->customer->getId(),
            'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
        ];
        $log->write('notification edit');

        $this->model_account_activity->addActivity($notification_id . ' notifiction ' . $active_status_text, $activity_data);

        $log->write('notification edit');

        $json['success'] = 'Customer ' . $notification_id . ' Notfications ' . $active_status_text . '!';
        
        $json['status'] =200;
    }
    catch(exception $ex)
    {
        $json['Error'] ="Error";
        $json['status'] =400;
    }
    finally{
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    }

}
