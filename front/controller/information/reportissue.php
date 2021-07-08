<?php

class ControllerInformationReportissue extends Controller
{
    private $error = [];

    public function index()
    {
        $log = new Log('error.log');
        $log->write('11');
        $this->load->language('information/reportissue');

        //$this->document->setTitle($this->language->get('heading_title'));
        $this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_checkout.css');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->request->isAjax() && $this->validate()) {
            $this->load->model('account/customer');
           $stats= $this->model_account_customer->addCustomerIssue($this->customer->getId(), $this->request->post);
           if($stats==true)
           {

            $data['status'] = true;
            $data['redirect'] = $this->url->link('account/account', '', 'SSL');
            $data['text_message'] = $this->language->get('text_success_contact');
           }
           else
           {
            $data['status'] = false;
            $data['redirect'] = $this->url->link('account/account', '', 'SSL');
            $data['text_message'] ="";
          
           }
            if ($this->request->isAjax()) {
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($data));
            }

            //$this->response->redirect($this->url->link('information/contact/success'));
        }

        $log->write('13');

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('information/reportissue'),
        ];

        $data['heading_title'] = $this->language->get('heading_title'); 
        $data['text_issuesummary'] = $this->language->get('text_issuesummary');

        $data['entry_issuesummary'] = $this->language->get('entry_issuesummary');  
        $data['entry_issuetype'] = $this->language->get('entry_issuetype');  
        $data['button_submit'] = $this->language->get('button_submit');

        if (isset($this->error['issuesummary'])) {
            $data['error_issuesummary'] = $this->error['issuesummary'];
        } else {
            $data['error_issuesummary'] = '';
        }
  
        $data['button_submit'] = $this->language->get('button_submit');

        $data['action'] = $this->url->link('information/reportissue');
 
    

        if (isset($this->request->post['issuesummary'])) {
            $data['issuesummary'] = $this->request->post['issuesummary'];
        } else {
            $data['issuesummary'] = '';
        }

        if (isset($this->request->post['customer_id'])) {
            $data['customer_id'] = $this->request->post['customer_id'];
        } else {
            $data['customer_id'] = $this->customer->getId();
        }


        if (isset($this->request->post['order_id'])) {
            $data['order_id'] = $this->request->post['order_id'];
        } else {
            $data['order_id'] = 0;
        }

        if (isset($this->request->post['issue_type'])) {
            $data['issue_type'] = $this->request->post['issue_type'];
        } else {
            $data['issue_type'] = 0;
        }

        if ($this->config->get('config_google_captcha_status')) {
            $this->document->addScript('https://www.google.com/recaptcha/api.js');

            $data['site_key'] = $this->config->get('config_google_captcha_public');
        } else {
            $data['site_key'] = '';
        }
        $log->write('14');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        
        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->request->isAjax()) {
            $log->write('15.4');
            if (!$this->validate()) {
                $data['status'] = false;
                if ($this->request->isAjax()) {
                    $this->response->addHeader('Content-Type: application/json');
                    $this->response->setOutput(json_encode($data));
                }
            } else {
                $data['status'] = true;
                $data['redirect'] = $this->url->link('account/account', '', 'SSL');

                if ($this->request->isAjax()) {
                    $this->response->addHeader('Content-Type: application/json');
                    $this->response->setOutput(json_encode($data));
                }
            }
        }

        $log->write('15');
         
            return $this->load->view('default/template/information/reportissue.tpl', $data);
         
    }

    public function success()
    {
        $this->load->language('information/conreportissuetact');

        $this->document->setTitle($this->language->get('heading_title'));
        $this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_checkout.css');

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('information/reportissue'),
        ];

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_message'] = $this->language->get('text_success_contact');

        $data['button_submit'] = $this->language->get('button_submit');

        $data['continue'] = $this->url->link('common/home');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        
        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/common/reportissue.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/common/reportissue.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/common/success.tpl', $data));
        }
    }

    protected function validate()
    {
        if ((utf8_strlen($this->request->post['issuesummary']) < 10) || (utf8_strlen($this->request->post['issuesummary']) > 2000)) {
            $this->error['issuesummary'] = $this->language->get('error_issuesummary');
        }

           
        return !$this->error;
    }

     
}
