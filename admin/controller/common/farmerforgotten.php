<?php

class ControllerCommonFarmerForgotten extends Controller {

    private $error = [];

    public function index() {
        $log = new Log('error.log');
        if ($this->user->isFarmerLogged() && isset($this->request->get['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
            $this->response->redirect($this->url->link('sale/farmer_transactions', '', 'SSL'));
        }

        if (!$this->config->get('config_password')) {
            $this->response->redirect($this->url->link('common/farmer', '', 'SSL'));
        }

        $this->load->language('common/forgotten');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('user/farmer');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validate()) {
            $this->load->language('mail/forgotten');

            $get_farmer_email = $this->model_user_farmer->getFarmerByEmail($this->request->post['email']);
            $get_farmer_phone = $this->model_user_farmer->getFarmerByPhone($this->request->post['email']);
            if (isset($get_farmer_phone) || isset($get_farmer_email)) {
                $code = sha1(uniqid(mt_rand(), true));

                if (isset($get_farmer_email) && sizeof($get_farmer_email) > 0 && $get_farmer_email['email'] != NULL) {
                    $this->model_user_farmer->editCode($this->request->post['email'], $code);
                }

                if (isset($get_farmer_phone) && sizeof($get_farmer_phone) > 0 && $get_farmer_phone['mobile'] != NULL) {
                    $this->model_user_farmer->editCodeMobile($this->request->post['email'], $code);
                }

                if (isset($get_farmer_email) && sizeof($get_farmer_email) > 0 && $get_farmer_email['email'] != NULL) {

                    $farmer_info['firstname'] = $get_farmer_email['first_name'];
                    $farmer_info['lastname'] = $get_farmer_email['last_name'];
                    $farmer_info['store_name'] = 'KwikBasket';
                    $farmer_info['order_link'] = $this->url->link('common/farmerreset', 'code=' . $code, 'SSL');
                    $farmer_info['system_name'] = 'KwikBasket';

                    try {
                        if ($this->emailtemplate->getEmailEnabled('Customer', 'customer_11')) {
                            $subject = $this->emailtemplate->getSubject('Customer', 'customer_11', $farmer_info);
                            $message = $this->emailtemplate->getMessage('Customer', 'customer_11', $farmer_info);

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
                }

                if (isset($get_farmer_phone) && sizeof($get_farmer_phone) > 0 && $get_farmer_phone['mobile'] != NULL) {

                    $farmer_info['firstname'] = $get_farmer_phone['first_name'];
                    $farmer_info['lastname'] = $get_farmer_phone['last_name'];
                    $farmer_info['store_name'] = 'KwikBasket';
                    $farmer_info['order_link'] = $this->url->link('common/farmerreset', 'code=' . $code, 'SSL');
                    $farmer_info['system_name'] = 'KwikBasket';

                    $log->write('SMS SENDING');
                    $sms_message = $this->emailtemplate->getSmsMessage('Customer', 'customer_11', $farmer_info);
                    $log->write($sms_message);
                    // send message here
                    if ($this->emailtemplate->getSmsEnabled('Customer', 'customer_11')) {
                        $log->write('FARMER SMS NOTIFICATION');
                        $ret = $this->emailtemplate->sendmessage($get_farmer_phone['mobile'], $sms_message);
                    }
                }

                $this->session->data['success'] = $this->language->get('text_success');
            }

            $this->response->redirect($this->url->link('common/farmer', '', 'SSL'));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_your_email'] = $this->language->get('text_your_email');
        $data['text_email'] = $this->language->get('text_email');

        $data['entry_email'] = $this->language->get('entry_email');

        $data['button_reset'] = $this->language->get('button_reset');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', '', 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('common/forgotten', 'token=' . '', 'SSL'),
        ];

        $data['action'] = $this->url->link('common/farmerforgotten', '', 'SSL');

        $data['cancel'] = $this->url->link('common/farmer', '', 'SSL');

        if (isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } else {
            $data['email'] = '';
        }

        $this->load->model('tool/image');

        if ($this->config->get('config_image') && is_file(DIR_IMAGE . $this->config->get('config_image'))) {
            $data['thumb'] = $this->model_tool_image->resize($this->config->get('config_image'), 200, 110);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 200, 110);
        }

        $data['store'] = [
            'name' => $this->config->get('config_name'),
            'href' => HTTP_CATALOG,
        ];

        $data['header'] = $this->load->controller('common/header');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('common/farmerforgotten.tpl', $data));
    }

    protected function validate() {
        if (!isset($this->request->post['email'])) {
            $this->error['warning'] = $this->language->get('error_email');
        } elseif (!$this->model_user_farmer->getTotalFarmersByEmail($this->request->post['email']) && !$this->model_user_farmer->getTotalFarmersByMobile($this->request->post['email'])) {
            $this->error['warning'] = $this->language->get('error_email');
        }

        return !$this->error;
    }

}
