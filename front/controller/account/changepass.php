<?php

class ControllerAccountChangepass extends Controller{
 
    private $error = array();
    
    public function index() {
        
        if(!$this->customer->isLogged()){
            $this->response->redirect($this->url->link("common/home"));            
        }

        $this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_login.css');

        $this->load->language('account/changepass');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/changepass');

        $data['heading_title'] = $this->language->get('heading_title');
        $data['heading_text']  = $this->language->get('heading_text');

        $data['label_current']  = $this->language->get('label_current');
        $data['label_new']  = $this->language->get('label_new');
        $data['label_retype']  = $this->language->get('label_retype');


        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/information');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/changepass.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/changepass.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/account/changepass.tpl', $data));
        }
       
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->language->get('error_warning_message');
        } else {
            $data['error_warning'] = '';
        }


        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
           
           // echo "<pre>";print_r($this->request->post['newpassword']);die;
            $this->load->model('account/changepass');
           
            $result=  $this->model_account_changepass->change($this->request->post);
         
              
             if ($result == 0) {
                $this->$data['error_warning'] = $this->language->get('error_warning_message');
            } else {
                $this->session->data['success'] = 'Password changed successfully';
                $this->response->redirect($this->url->link('account/account', '', 'SSL'));
                
               
               // $this->response->redirect($this->url->link('account/changepass/success'));
            }         
        }
      
        if (isset($this->error['current'])) {
            $data['error_current'] = $this->error['current'];
        } else {
            $data['error_current'] = '';
        }
              
         if (isset($this->error['new'])) {
            $data['error_new'] = $this->error['new'];
        } else {
            $data['error_new'] = '';
        }
        
         if (isset($this->error['retype'])) {
            $data['error_retype'] = $this->error['retype'];
        } else {
            $data['error_retype'] = '';
        }
        
         if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/changepass.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/changepass.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/account/changepass.tpl', $data));
        }
    }

    public function validate() {
        
        // if ((utf8_strlen(trim($this->request->post['currentpassword'])) < 1) || (utf8_strlen(trim($this->request->post['currentpassword'])) > 32)) {
        //     $this->error['current'] = $this->language->get('error_current');
        // }
         
        if ((utf8_strlen(trim($this->request->post['newpassword'])) < 1) || (utf8_strlen(trim($this->request->post['newpassword'])) > 32)) {
            $this->error['new'] = $this->language->get('error_new');
        }

        if ((utf8_strlen($this->request->post['retypepassword']) > 96) || ($this->request->post['newpassword'] !== $this->request->post['retypepassword'])) {
            $this->error['retype'] = $this->language->get('error_retype');
        }
        
        // if (empty($this->request->post['currentpassword'])) {
        //     $this->error['current'] = $this->language->get('error_check');
        // }
        
        // if (empty($this->request->post['newpassword'])) {
        //     $this->error['new'] = $this->language->get('error_new');
        // }

        // if (empty($this->request->post['retypepassword'])) {
        //     $this->error['retype'] = $this->language->get('error_retype');
        // }
        return !$this->error;
    }
}
