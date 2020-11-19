<?php

class ControllerApiForgetPassword extends Controller
{
    private $error = [];

    public function addForgetPassword()
    {
        //echo "forget";

        $this->load->model('user/user');

        $this->load->language('mail/appforgotten');

        $json['status'] = 501;

        if ($this->validate()) {
            $code = sha1(uniqid(mt_rand(), true));

            if ($this->request->server['HTTPS']) {
                $server = $this->config->get('config_ssl');
            } else {
                $server = $this->config->get('config_url');
            }

            $this->model_user_user->editCode($this->request->post['email'], $code);

            $subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));

            $message = sprintf($this->language->get('text_greeting'), $this->config->get('config_name'))."\n\n";
            $message .= $this->language->get('text_change')."\n\n";
            //$message .= $this->url->link('common/reset', 'code=' . $code, 'SSL') . "\n\n";
            $message .= $server.'admin/index.php?path=common/reset&code='.$code."\n\n";

            $message .= sprintf($this->language->get('text_ip'), $this->request->server['REMOTE_ADDR'])."\n\n";

            $mail = new mail($this->config->get('config_mail'));
            $mail->setTo($this->request->post['email']);
            $mail->setFrom($this->config->get('config_from_email'));
            $mail->setSender($this->config->get('config_name'));
            $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
            $mail->setHtml(html_entity_decode(strip_tags($message), ENT_QUOTES, 'UTF-8'));
            $mail->send();

            unset($this->session->data['api_id']);
            $json['success'] = $this->language->get('text_success');
            $json['status'] = 200;
        } else {
            $json['message'] = $this->language->get('error_email');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validate()
    {
        if (!isset($this->request->post['email'])) {
            $this->error['warning'] = $this->language->get('error_email');
        } elseif (!$this->model_user_user->getTotalUsersByEmail($this->request->post['email'])) {
            $this->error['warning'] = $this->language->get('error_email');
        }

        return !$this->error;
    }

    public function ForgotPassword()
    {
        //echo "forget";

        $this->load->model('account/customer');

        $this->load->language('account/forgotten');

        $json['status'] = 502;

        if ($this->validateCustomer()) {
            $this->load->language('mail/forgotten');
            $password = substr(sha1(uniqid(mt_rand(), true)), 0, 10);
            $this->model_account_customer->resetPassword($this->request->post['email'], $password, 1);
            //1 implies, new password is generated and user need to update his password

            $this->model_account_customer->resetPasswordMail($this->request->post['email'], $password);

            
            $this->session->data['success'] = $this->language->get('text_success');

            $this->model_account_customer->deleteLoginAttempts($this->request->post['email']);


             // Add to activity log
             $customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);

             if ($customer_info) {
                 $this->load->model('account/activity');
 
                 $activity_data = [
                     'customer_id' => $customer_info['customer_id'],
                     'name' => $customer_info['firstname'].' '.$customer_info['lastname'],
                 ];
 
                 $this->model_account_activity->addActivity('forgotten', $activity_data);
             }

            unset($this->session->data['api_id']);
            $json['success'] = $this->language->get('text_success');
            $json['status'] = 200;
        } else {
            
            $json['message'] = $this->language->get('error_email');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validateCustomer()
    {

        
        if (!isset($this->request->post['email'])) {
            $this->error['warning'] = $this->language->get('error_email');
        } elseif (!$this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
            $this->error['warning'] = $this->language->get('error_email');
        }

        return !$this->error;
    }


    public function changepassword()
    {
        $json = [];
        $json['success'] = 'Something went wrong!';

        // if (!isset($this->session->data['api_id'])) {
        //     $json['error'] = $this->language->get('error_permission');
        // } else 
        {

         
        $this->load->language('account/changepass');
        $this->load->model('account/changepass');       

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->language->get('error_warning_message');
        } else {
            $data['error_warning'] = '';
        }

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validatepasword()) {
            // echo "<pre>";print_r($this->request->post['newpassword']);die;
            $this->load->model('account/changepass');

            $result = $this->model_account_changepass->changePassword($this->request->post);

            if (0 == $result) {
                $json['error'] =  $this->language->get('error_warning_message');
            } else {
                $json['success'] =   'Password changed successfully';
               // $this->response->redirect($this->url->link('account/account', '', 'SSL'));

                // $this->response->redirect($this->url->link('account/changepass/success'));
                 // Add to activity log
                 $this->load->model('account/customer');
                 $customer_info = $this->model_account_customer->getCustomer($this->request->post['customerid']);
                 
                $this->load->model('account/activity');

                $activity_data = [
                    'customer_id' => $customer_info['customer_id'],
                    'name' =>  $customer_info['firstname'] . ' ' . $customer_info['lastname'],
                ];

                $this->model_account_activity->addActivity('password', $activity_data);

            
            }
        }

        if (isset($this->error['current'])) {
            $json['error_current'] = $this->error['current'];
        } else {
            $json['error_current'] = '';
        }

        if (isset($this->error['new'])) {
            $json['error_new'] = $this->error['new'];
        } else {
            $json['error_new'] = '';
        }

        if (isset($this->error['retype'])) {
            $json['error_retype'] = $this->error['retype'];
        } else {
            $json['error_retype'] = '';
        }

        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function validatePasword()
    {
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
