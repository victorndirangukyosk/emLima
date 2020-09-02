<?php

class ControllerAccountProfile extends Controller
{
    private $error = [];

    public function index()
    {
        $this->language->load('account/profile');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token='.$this->session->data['token'], 'SSL'),
            'separator' => false,
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('account/profile', 'token='.$this->session->data['token'], 'SSL'),
            'separator' => ' :: ',
        ];

        $data['heading_title'] = $this->language->get('heading_title');
        $data['tab_bank_details'] = $this->language->get('tab_bank_details');
        $data['text_general'] = $this->language->get('text_general');
        $data['text_vendor'] = $this->language->get('text_vendor');
        $data['text_package'] = $this->language->get('text_package');

        $data['column_username'] = $this->language->get('column_username');
        $data['column_firstname'] = $this->language->get('column_firstname');
        $data['column_lastname'] = $this->language->get('column_lastname');
        $data['column_email'] = $this->language->get('column_email');
        $data['column_ip_address'] = $this->language->get('column_ip_address');
        $data['column_date'] = $this->language->get('column_date');
        $data['column_commision'] = $this->language->get('column_commision');
        $data['column_account'] = $this->language->get('column_account');
        $data['column_business'] = $this->language->get('column_business');
        $data['column_type'] = $this->language->get('column_type');
        $data['column_tin_no'] = $this->language->get('column_tin_no');
        $data['column_mobile'] = $this->language->get('column_mobile');
        $data['column_telephone'] = $this->language->get('column_telephone');
        $data['column_city'] = $this->language->get('column_city');
        $data['column_address'] = $this->language->get('column_address');
        $data['column_name'] = $this->language->get('column_name');
        $data['column_priority'] = $this->language->get('column_priority');
        $data['column_activation_date'] = $this->language->get('column_activation_date');
        $data['column_active_upto_date'] = $this->language->get('column_active_upto_date');

        $this->load->model('account/packages');
        $this->load->model('vendor/vendor');

        $data['user'] = $this->model_account_packages->getUser($this->user->getId());

        $city = $this->model_account_packages->getCity($data['user']['city_id']);

        if ($city) {
            $data['user']['city'] = $city['name'];
        } else {
            $data['user']['city'] = '';
        }

        $data['package'] = $this->model_account_packages->getVendorToPackage($this->user->getId());

        $vendor_bank_info = $this->model_vendor_vendor->getVendorBank($this->user->getId());

        if (!empty($vendor_bank_info)) {
            $data['bank_account_number'] = $vendor_bank_info['bank_account_number'];
        } else {
            $data['bank_account_number'] = '';
        }

        if (!empty($vendor_bank_info)) {
            $data['bank_account_name'] = $vendor_bank_info['bank_account_name'];
        } else {
            $data['bank_account_name'] = '';
        }

        if (!empty($vendor_bank_info)) {
            $data['bank_name'] = $vendor_bank_info['bank_name'];
        } else {
            $data['bank_name'] = '';
        }

        if (!empty($vendor_bank_info)) {
            $data['bank_branch_name'] = $vendor_bank_info['bank_branch_name'];
        } else {
            $data['bank_branch_name'] = '';
        }

        if (!empty($vendor_bank_info)) {
            $data['bank_account_type'] = $vendor_bank_info['bank_account_type'];
        } else {
            $data['bank_account_type'] = '';
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('account/profile.tpl', $data));
    }
}
