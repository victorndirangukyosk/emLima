<?php

class ControllerCommonDashboard extends Controller { 

    public function temp() {
        $this->load->model('catalog/product');

        $results = $this->model_catalog_product->getProductIds();

        foreach ($results as $row) {
            $this->model_catalog_product->copyProduct($row['product_id']);
        }

        echo 'done!';
        die();
    }

    public function index() {
        $shopper_group_ids = explode(',', $this->config->get('config_shopper_group_ids'));

        if (in_array($this->user->getGroupId(), $shopper_group_ids)) {
            $this->response->redirect($this->url->link('shopper/request', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->load->language('common/dashboard');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_sale'] = $this->language->get('text_sale');
        $data['text_map'] = $this->language->get('text_map');
        $data['text_activity'] = $this->language->get('text_activity');
        $data['text_recent'] = $this->language->get('text_recent');

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
        ];

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $data['token'] = $this->session->data['token'];
        $filter_date_input = date('Y-m-d');
        $data['filter_date_input'] = $filter_date_input;
        
        $filter_monthyear_input = date('Y-m', strtotime(date('Y').'-'.date('m')));
        $data['filter_monthyear_input'] = $filter_monthyear_input;

        if ($this->user->isVendor()) {
            $this->vendor($data);
        } elseif ($this->user->isAccountManager()) {
            $this->accountmanager($data);
        } else {
            $this->admin($data);
        }
    }

    private function vendor($data) {
        $data['error_install'] = '';

        $data['order'] = $this->load->controller('dashboard/order/vendor');
        $data['sale'] = $this->load->controller('dashboard/sale/vendor');
        $data['customer'] = $this->load->controller('dashboard/customer');
        $data['online'] = $this->load->controller('dashboard/online');
        $data['chart'] = $this->load->controller('dashboard/chart');
        $data['charts'] = $this->load->controller('dashboard/charts');

        $data['actualSales'] = $this->load->controller('dashboard/sale/vendorActualSales');

        $data['recenttabs'] = $this->load->controller('dashboard/recenttabs');

        $this->response->setOutput($this->load->view('common/vendor_dashboard.tpl', $data));
    }

    private function accountmanager($data) {
        $data['error_install'] = '';
        $data['online_customers_url'] = $this->url->link('report/account_manager_customer_online', 'token=' . $this->session->data['token'], 'SSL');
        $data['order'] = $this->load->controller('dashboard/order/accountmanager');
        $data['sale'] = $this->load->controller('dashboard/sale/accountmanager');
        $data['customer'] = $this->load->controller('dashboard/customer');
        $data['online'] = $this->load->controller('dashboard/accountmanagercustomersonline');
        $data['chart'] = $this->load->controller('dashboard/chart');
        $data['charts'] = $this->load->controller('dashboard/accountmanagercharts');

        $data['actualSales'] = $this->load->controller('dashboard/sale/accountmanagerActualSales');

        $data['recenttabs'] = $this->load->controller('dashboard/recenttabs');

        $this->response->setOutput($this->load->view('common/account_manager_dashboard.tpl', $data));
    }

    private function admin($data) {
        // Check install directory exists
        if (is_dir(dirname(DIR_APPLICATION) . '/install')) {
            $data['error_install'] = $this->language->get('error_install');
        } else {
            $data['error_install'] = '';
        }

        $data['online_customers_url'] = $this->url->link('report/customer_online', 'token=' . $this->session->data['token'], 'SSL');
        // $data['order'] = $this->load->controller('dashboard/order');
        // $data['sale'] = $this->load->controller('dashboard/sale');
        // $data['customer'] = $this->load->controller('dashboard/customer');
        $data['online'] = $this->load->controller('dashboard/online');
        $data['manualorders'] = $this->load->controller('dashboard/manualorders/ManualOrdersAll');
        $data['onlineorders'] = $this->load->controller('dashboard/onlineorders/OnlineOrdersAll');

        $data['map'] = $this->load->controller('dashboard/map');
        $data['chart'] = $this->load->controller('dashboard/chart');
        $data['charts'] = $this->load->controller('dashboard/charts');
        $data['activity'] = $this->load->controller('dashboard/activity');
        $data['recent'] = $this->load->controller('dashboard/recent');
        $data['recenttabs'] = $this->load->controller('dashboard/recenttabs');

        //OverView        
        $data['order_received'] = $this->load->controller('dashboard/order/ReceivedOrdersAll');
        $data['order_processed'] = $this->load->controller('dashboard/order/ProcessedOrdersAll');
        $data['order_cancelled'] = $this->load->controller('dashboard/order/CancelledOrdersAll');
        $data['order_incomeplete'] = $this->load->controller('dashboard/order/IncompleteOrdersAll');
        $data['order_approval_pening'] = $this->load->controller('dashboard/order/ApprovalPendingOrdersAll');
        $data['order_fast'] = $this->load->controller('dashboard/order/FastOrdersAll');
        
        $data['total_customers_onboarded'] = $this->load->controller('dashboard/customer/CustomersOnboardedAll');
        $data['total_customers_registered'] = $this->load->controller('dashboard/customer/CustomersRegisteredAll');
        $data['total_customers_approval_pending'] = $this->load->controller('dashboard/customer/CustomersPendingApprovalAll');

        $data['total_revenue_booked'] = $this->load->controller('dashboard/order/TotalRevenueBookedDashBoardAll');
        $data['total_revenue_collected'] = $this->load->controller('dashboard/order/TotalRevenueCollectedDashBoardAll');
        $data['total_revenue_pending'] = $this->load->controller('dashboard/order/TotalRevenuePendingDashBoardAll');
        
        //OverView 2 
        $data['order_received_month'] = $this->load->controller('dashboard/order/ReceivedOrders');
        $data['order_processed_month'] = $this->load->controller('dashboard/order/ProcessedOrders');
        $data['order_cancelled_month'] = $this->load->controller('dashboard/order/CancelledOrders');
        $data['order_incomeplete_month'] = $this->load->controller('dashboard/order/IncompleteOrders');
        $data['order_approval_pending_month'] = $this->load->controller('dashboard/order/ApprovalPendingOrders');
        $data['order_fast_month'] = $this->load->controller('dashboard/order/FastOrders');

        $data['onlineorders_month'] = $this->load->controller('dashboard/onlineorders');
        $data['manualorders_month'] = $this->load->controller('dashboard/manualorders');
        
        $data['total_customers_onboarded_month'] = $this->load->controller('dashboard/customer/CustomersOnboarded');
        $data['total_customers_registered_month'] = $this->load->controller('dashboard/customer/CustomersRegistered');
        $data['total_customers_approval_pending_month'] = $this->load->controller('dashboard/customer/CustomersPendingApproval');

        $data['total_revenue_booked_month'] = $this->load->controller('dashboard/order/TotalRevenueBookedDashBoard');
        $data['total_revenue_collected_month'] = $this->load->controller('dashboard/order/TotalRevenueCollectedDashBoard');
        $data['total_revenue_pending_month'] = $this->load->controller('dashboard/order/TotalRevenuePendingDashBoard');
        
        $data['order_received_ystdate'] = $this->load->controller('dashboard/order/DeliveredOrdersByYstDate');
        $data['total_revenue_booked_ystdate'] = $this->load->controller('dashboard/order/TotalRevenueBookedDashBoardByYstDate');
        $data['total_customers_registered_ystdate'] = $this->load->controller('dashboard/customer/CustomersRegisteredByYstDate');
        $data['total_customers_approval_pending_ystdate'] = $this->load->controller('dashboard/customer/CustomersPendingApprovalByYstDate');
        
        $data['dashboard_yesterday'] = $this->load->controller('dashboard/order/DashboardYesterday');
        
        $data['order_received_todaydate'] = $this->load->controller('dashboard/order/DeliveredOrdersByTodayDate');
        $data['total_revenue_booked_todaydate'] = $this->load->controller('dashboard/order/TotalRevenueBookedDashBoardByTodayDate');
        $data['total_customers_registered_todaydate'] = $this->load->controller('dashboard/customer/CustomersRegisteredByTodayDate');
        $data['total_customers_approval_pending_todaydate'] = $this->load->controller('dashboard/customer/CustomersPendingApprovalByTodayDate');
     
        $data['dashboard_today'] = $this->load->controller('dashboard/order/DashboardToday');
        
        $data['order_received_tmrwdate'] = $this->load->controller('dashboard/order/DeliveredOrdersByTmrwDate');
        $data['total_revenue_booked_tmrwdate'] = $this->load->controller('dashboard/order/TotalRevenueBookedDashBoardByTmrwDate');
   
        $data['dashboard_tomorrow'] = $this->load->controller('dashboard/order/DashboardTomorrow');


        // Run currency update
        if ($this->config->get('config_currency_auto')) {
            $this->load->model('localisation/currency');
            $this->model_localisation_currency->refresh();
        }

        $this->response->setOutput($this->load->view('common/dashboard.tpl', $data));
    }

    public function export_mostpurchased_products_excel($customer_id) {
        $data = [];

        if (isset($this->request->get['customer_id'])) {
            $data['customer_id'] = $this->request->get['customer_id'];
        }

        $this->load->model('report/excel');
        $this->model_report_excel->download_mostpurchased_products_excel($data);
    }

   

}
