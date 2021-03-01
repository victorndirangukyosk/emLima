<?php

class ControllerDashboardCustomer extends Controller {

    public function index() {
        $this->load->language('dashboard/customer');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/customer');

        $today = $this->model_sale_customer->getTotalCustomersForDashboard(['filter_date_added' => date('Y-m-d', strtotime('-1 day'))]);

        $yesterday = $this->model_sale_customer->getTotalCustomersForDashboard(['filter_date_added' => date('Y-m-d', strtotime('-2 day'))]);

        $difference = $today - $yesterday;

        if ($difference && $today) {
            $data['percentage'] = round(($difference / $today) * 100);
        } else {
            $data['percentage'] = 0;
        }

        $customer_total = $this->model_sale_customer->getTotalCustomersForDashboard();

        if ($customer_total > 1000000000000) {
            $data['total'] = round($customer_total / 1000000000000, 1) . 'T';
        } elseif ($customer_total > 1000000000) {
            $data['total'] = round($customer_total / 1000000000, 1) . 'B';
        } elseif ($customer_total > 1000000) {
            $data['total'] = round($customer_total / 1000000, 1) . 'M';
        } elseif ($customer_total > 1000) {
            $data['total'] = round($customer_total / 1000, 1) . 'K';
        } else {
            $data['total'] = $customer_total;
        }

        $data['customer'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'], 'SSL');

        return $this->load->view('dashboard/customer.tpl', $data);
    }

    public function CustomersOnboarded() {

        $this->load->language('dashboard/customer');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/customer');

        $customer_total = $this->model_sale_customer->getTotalCustomersOnBoarded();

        $data['url'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&', 'SSL');
        $data['total'] = $customer_total;
        $data['customer'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'], 'SSL');

        return $this->load->view('dashboard/customer_onboarded.tpl', $data);
    }

    public function CustomersRegistered() {

        $this->load->language('dashboard/customer');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/customer');

        $customer_total = $this->model_sale_customer->getTotalCustomersForDashboard();
        $data['url'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&', 'SSL');
        $data['total'] = $customer_total;
        $data['customer'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'], 'SSL');

        return $this->load->view('dashboard/customer_registered.tpl', $data);
    }

    public function CustomersPendingApproval() {

        $this->load->language('dashboard/customer');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/customer');

        $customer_total = $this->model_sale_customer->getTotalCustomersForDashboard(['filter_approved' => 0]);

        $data['url'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&filter_approved=0&filter_sub_customer_show=1', 'SSL');
        $data['total'] = $customer_total;
        $data['customer'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'], 'SSL');

        return $this->load->view('dashboard/customer_pending_approval.tpl', $data);
    }


    public function CustomersRegisteredByYstDate($date) {

        $this->load->language('dashboard/customer');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/customer');
        if($date==null || $date=="")
        {
            $date=date('Y-m-d');
        }
        $yesterdayDate=date('Y-m-d', strtotime('-1 day', strtotime($date)));
        $filter_data = [
            'filter_date_added' => $yesterdayDate,            
        ];

        
        $customer_total = $this->model_sale_customer->getTotalCustomersForDashboard($filter_data);
        $data['url'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&filter_sub_customer_show=1&filter_date_added='.$yesterdayDate, 'SSL');
        $data['total'] = $customer_total;
        $data['customer'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token']. '&filter_sub_customer_show=1&filter_date_added='.$yesterdayDate, 'SSL');

        return $this->load->view('dashboard/customer_registered_ystdate.tpl', $data);
    }

    public function CustomersRegisteredByTodayDate($date) {

        $this->load->language('dashboard/customer');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/customer');
        if($date==null || $date=="")
        {
            $date=date('Y-m-d');
        }
        $filter_data = [
            'filter_date_added' => $date,            
        ];
        $customer_total = $this->model_sale_customer->getTotalCustomersForDashboard($filter_data);
        $data['url'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&filter_sub_customer_show=1&filter_date_added='.$date, 'SSL');
        $data['total'] = $customer_total;
        $data['customer'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token']. '&filter_sub_customer_show=1&filter_date_added='.$date, 'SSL');

        return $this->load->view('dashboard/customer_registered_todaydate.tpl', $data);
    }

    public function CustomersRegisteredByTmrwDate($date) {

        $this->load->language('dashboard/customer');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/customer');
        if($date==null || $date=="")
        {
            $date=date('Y-m-d');
        }
        $tmrwDate=date('Y-m-d', strtotime('1 day', strtotime($date)));
        $filter_data = [
            'filter_date_added' => $tmrwDate,            
        ];
        $customer_total = $this->model_sale_customer->getTotalCustomersForDashboard($filter_data);
        $data['url'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&filter_sub_customer_show=1&filter_date_added='.$tmrwDate, 'SSL');
        $data['total'] = $customer_total;
        $data['customer'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token']. '&filter_sub_customer_show=1&filter_date_added='.$tmrwDate, 'SSL');

        return $this->load->view('dashboard/customer_registered_tmrwdate.tpl', $data);
    }


}
