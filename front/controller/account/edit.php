<?php

class ControllerAccountEdit extends Controller
{
    private $error = [];

    public function index()
    {
        $this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_login.css');

        if (!$this->customer->isLogged()) {
            //$this->session->data['redirect'] = $this->url->link('account/edit', '', 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }

        //print_r("expression");die;

        $this->load->language('account/edit');

        //$this->document->setTitle($this->language->get('heading_title'));

        $this->document->addScript('front/ui/javascript/jquery/datetimepicker/moment.js');
        $this->document->addScript('front/ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
        $this->document->addStyle('front/ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

        $this->load->model('account/customer');

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/account', '', 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_edit'),
            'href' => $this->url->link('account/edit', '', 'SSL'),
        ];

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_your_details'] = $this->language->get('text_your_details');
        $data['text_additional'] = $this->language->get('text_additional');
        $data['text_select'] = $this->language->get('text_select');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['text_male'] = $this->language->get('text_male');
        $data['text_female'] = $this->language->get('text_female');
        $data['text_other'] = $this->language->get('text_other');
        $data['entry_dob'] = $this->language->get('entry_dob');

        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_fax'] = $this->language->get('entry_fax');

        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_back'] = $this->language->get('button_back');
        $data['button_upload'] = $this->language->get('button_upload');
        $data['button_save'] = $this->language->get('button_save');

        $data['entry_gender'] = $this->language->get('entry_gender');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['firstname'])) {
            $data['error_firstname'] = $this->error['firstname'];
        } else {
            $data['error_firstname'] = '';
        }

        if (isset($this->error['lastname'])) {
            $data['error_lastname'] = $this->error['lastname'];
        } else {
            $data['error_lastname'] = '';
        }

        if (isset($this->error['email'])) {
            $data['error_email'] = $this->error['email'];
        } else {
            $data['error_email'] = '';
        }

        if (isset($this->error['telephone'])) {
            $data['error_telephone'] = $this->error['telephone'];
        } else {
            $data['error_telephone'] = '';
        }

        if (isset($this->error['dob'])) {
            $data['error_dob'] = $this->error['dob'];
        } else {
            $data['error_dob'] = '';
        }

        if (isset($this->error['custom_field'])) {
            $data['error_custom_field'] = $this->error['custom_field'];
        } else {
            $data['error_custom_field'] = [];
        }

        $data['action'] = $this->url->link('account/account', '', 'SSL');

        if ('POST' != $this->request->server['REQUEST_METHOD']) {
            $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
        }

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['base'] = $server;

        if (isset($this->request->post['gender'])) {
            $data['gender'] = $this->request->post['gender'];
        } elseif (!empty($customer_info)) {
            $data['gender'] = $customer_info['gender'];
        } else {
            $data['gender'] = '';
        }

        if (isset($this->request->post['dob'])) {
            $data['dob'] = date('d/m/Y', strtotime($this->request->post['dob']));
        } elseif (!empty($customer_info['dob'])) {
            $data['dob'] = date('d/m/Y', strtotime($customer_info['dob']));
        } else {
            $data['dob'] = '';
        }

        if (isset($this->request->post['firstname'])) {
            $data['firstname'] = $this->request->post['firstname'];
        } elseif (!empty($customer_info)) {
            $data['firstname'] = $customer_info['firstname'];
        } else {
            $data['firstname'] = '';
        }

        if (isset($this->request->post['lastname'])) {
            $data['lastname'] = $this->request->post['lastname'];
        } elseif (!empty($customer_info)) {
            $data['lastname'] = $customer_info['lastname'];
        } else {
            $data['lastname'] = '';
        }

        if (isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } elseif (!empty($customer_info)) {
            $data['email'] = $customer_info['email'];
        } else {
            $data['email'] = '';
        }

        $data['telephone_mask'] = $this->config->get('config_telephone_mask');

        if (isset($data['telephone_mask'])) {
            $data['telephone_mask_number'] = str_replace('#', '9', $this->config->get('config_telephone_mask'));
        }

        $data['taxnumber_mask'] = $this->config->get('config_taxnumber_mask');

        if (isset($data['taxnumber_mask'])) {
            $data['taxnumber_mask_number'] = str_replace('#', '*', $this->config->get('config_taxnumber_mask'));
        }

        if (isset($this->request->post['telephone'])) {
            $data['telephone'] = $this->request->post['telephone'];
        } elseif (!empty($customer_info)) {
            $data['telephone'] = $this->formatTelephone($customer_info['telephone']);
        } else {
            $data['telephone'] = '';
        }

        if (isset($this->request->post['fax'])) {
            $data['fax'] = $this->request->post['fax'];
        } elseif (!empty($customer_info)) {
            $data['fax'] = $customer_info['fax'];
        } else {
            $data['fax'] = '';
        }

        // Custom Fields
        $this->load->model('account/custom_field');

        //print_r("expression");
        $data['custom_fields'] = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));

        if (isset($this->request->post['custom_field'])) {
            $data['account_custom_field'] = $this->request->post['custom_field'];
        } elseif (isset($customer_info)) {
            $data['account_custom_field'] = unserialize($customer_info['custom_field']);
        } else {
            $data['account_custom_field'] = [];
        }

        $data['back'] = $this->url->link('account/account', '', 'SSL');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        //$data['footer'] = $this->load->controller('common/footer');
        //$data['header'] = $this->load->controller('common/header/information');

        //echo "<pre>";print_r($data);die;
        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/account/edit.tpl')) {
            return $this->load->view($this->config->get('config_template').'/template/account/edit.tpl', $data);
        //$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/edit.tpl', $data));
        } else {
            //$this->response->setOutput($this->load->view('default/template/account/edit.tpl', $data));
            return $this->load->view('default/template/account/edit.tpl', $data);
        }
    }

    protected function validate()
    {
        if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
            $this->error['firstname'] = $this->language->get('error_firstname');
        }

        /*if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
            $this->error['lastname'] = $this->language->get('error_lastname');
        }*/

        //print_r($this->request->post);
        if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if (($this->customer->getEmail() != $this->request->post['email']) && $this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
            $this->error['warning'] = $this->language->get('error_exists');
        }

        if (false !== strpos($this->request->post['telephone'], '#')) {
            $this->error['telephone'] = $this->language->get('error_telephone');
        }

        /*if ( ( utf8_strlen( $this->request->post['telephone'] ) != 15 )  ) {
            $this->error['telephone'] = $this->language->get( 'error_telephone' );
        }*/

        /*$str  = $this->request->post['telephone'];
        $str1 = substr($str,1,2);
        $str2 = substr($str,5,5);
        $str3 = substr($str,11,4);

        if(!ctype_digit($str1.$str2.$str3)) {

            $this->error['telephone'] = $this->language->get( 'error_telephone' );
        }*/

        if (!isset($this->request->post['gender'])) {
            $this->error['gender'] = $this->language->get('error_gender');
        }

        if (false == DateTime::createFromFormat('d/m/Y', $this->request->post['dob'])) {
            $this->error['dob'] = $this->language->get('error_dob');
        }

        //print_r("expression1");
        // Custom field validation
        $this->load->model('account/custom_field');

        $custom_fields = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));

        foreach ($custom_fields as $custom_field) {
            if (('account' == $custom_field['location']) && $custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['custom_field_id']])) {
                $this->error['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
            }
        }

        return !$this->error;
    }

    public function formatTelephone($telephone)
    {
        /*if(strlen($telephone) == 11 ) {
            //(21) 42353-5255

            $str1 = '(';
            $str3 = ')';
            $str4 = ' ';
            $str6 = '-';

            $str  = $telephone;
            $str2 = substr($str,0,2);
            $str5 = substr($str,2,5);
            $str7 = substr($str,7,4);


            return  $str1.$str2.$str3.$str4.$str5.$str6.$str7;
        } else {
            return $telephone;
        }*/
        return $telephone;
    }
}
