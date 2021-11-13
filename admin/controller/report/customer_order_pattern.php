<?php

class ControllerReportCustomerOrderPattern extends Controller {

    public function index() {
        $this->load->language('report/customer_order_pattern');
        $this->document->setTitle($this->language->get('heading_title'));

        if (isset($this->request->get['filter_date_start'])) {
            $filter_date_start = $this->request->get['filter_date_start'];
        } else {
            $filter_date_start = '';
        }
        if (isset($this->request->get['filter_date_end'])) {
            $filter_date_end = $this->request->get['filter_date_end'];
        } else {
            $filter_date_end = '';
        }
        if (isset($this->request->get['filter_order_status_id'])) {
            $filter_order_status_id = $this->request->get['filter_order_status_id'];
        } else {
            $filter_order_status_id = 0;
        }
        // if (isset($this->request->get['filter_customer'])) {
        //     $filter_customer = $this->request->get['filter_customer'];
        // } else {
        //     $filter_customer = '';
        // }
        if (isset($this->request->get['filter_company'])) {
            $filter_company = $this->request->get['filter_company'];
        } else {
            $filter_company = '';
        }

        if (isset($this->request->get['filter_account_manager_name'])) {
            $filter_account_manager_name = $this->request->get['filter_account_manager_name'];
        } else {
            $filter_account_manager_name = '';
        }

        //placing pagination effecting the calculation so, adding pagination to customer list
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }
        $url = '';
        if (isset($this->request->get['filter_date_start'])) {
            $url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
        }
        if (isset($this->request->get['filter_date_end'])) {
            $url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
        }

        if (isset($this->request->get['filter_sub_customer_show'])) {
            $url .= '&filter_sub_customer_show=' . $this->request->get['filter_sub_customer_show'];
        }
        if (isset($this->request->get['filter_order_status_id'])) {
            $url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
        }
        // if (isset($this->request->get['filter_customer'])) {
        //     $url .= '&filter_customer='.$this->request->get['filter_customer'];
        // }
        if (isset($this->request->get['filter_company'])) {
            $url .= '&filter_company=' . $this->request->get['filter_company'];
        }
        if (isset($this->request->get['filter_account_manager_name'])) {
            $url .= '&filter_account_manager_name=' . $this->request->get['filter_account_manager_name'];
        }
        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }
        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('report/customer_order_pattern', 'token=' . $this->session->data['token'] . $url, 'SSL'),
        ];

        $this->load->model('report/customer');

        $data['customers'] = [];

        $filter_account_manager_id = NULL;
        if ($filter_account_manager_name != NULL) {
            $this->load->model('user/accountmanager');
            $account_manager = $this->model_user_accountmanager->getAccountManagerByName($filter_account_manager_name);
            $log = new Log('error.log');
            $log->write($account_manager);
            if (is_array($account_manager) && count($account_manager) > 0) {
                $filter_account_manager_id = $account_manager['user_id'];
            } else {
                $filter_account_manager_id = NULL;
            }
        }

        
        if (isset($this->request->get['filter_sub_customer_show'])) {
            $filter_sub_customer_show = $this->request->get['filter_sub_customer_show'];
        } else {
            $filter_sub_customer_show = null;
        }

        $filter_data = [
            'filter_date_start' => $filter_date_start,
            'filter_date_end' => $filter_date_end,
            'filter_order_status_id' => $filter_order_status_id,
            //'filter_customer' => $filter_customer,
            'filter_company' => $filter_company,
            'filter_account_manager_name' => $filter_account_manager_name,
            'filter_account_manager_id' => $filter_account_manager_id,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
            'filter_sub_customer_show' => $filter_sub_customer_show,
        ];

        if ('' != $filter_date_start && '' != $filter_date_end) {
            $company_total = $this->model_report_customer->getTotalValidCompanies($filter_data);

            //$results = $this->model_report_customer->getValidCompanyOrders($filter_data);
            $customerresults = $this->model_report_customer->getValidCompanies($filter_data);
            // echo "<pre>";print_r($customerresults);die;
            $months = $this->model_report_customer->getmonths($filter_data); //need to check simple way
            //    echo "<pre>";print_r($months);die;
        } else {
            $company_total = 0;
            $customerresults = null; 
        }


        $this->load->model('sale/order');
        if (is_array($customerresults) && count($customerresults) > 0) {
            $log = new Log('error.log');
            $log->write('Yes It Is Array');
            $i = 0;
            foreach ($customerresults as $result) {
                $totalpermonth = 0;
                $data['customers'][] = [
                    'Company Name' => $result['company'],
                ];
                $totalOrders = 0;
                $OrdersValue = 0;
                foreach ($months as $month) {
                    $totalpermonth = $this->model_report_customer->getCompanyTotal($filter_data, $month['month'], $result['company'], $result['customer_id']);
                    $monthname = $this->getmonthname($month['month']);
                    $totalOrders = $totalOrders + $totalpermonth['TotalOrders'];
                    $OrdersValue = $OrdersValue + $totalpermonth['Total'];
                    //$data['customers'][$i][$monthname]=$this->currency->format($totalpermonth['Total'], $this->config->get('config_currency'));
                    $data['customers'][$i][$monthname] = number_format($totalpermonth['Total'], 2)??0;
                }
                $data['customers'][$i]['Total'] = number_format($OrdersValue);
                $data['customers'][$i]['Order Count'] = $totalOrders;
                if ($OrdersValue > 0 && $totalOrders > 0) {
                    $data['customers'][$i]['Avg. Order Value'] = number_format(($OrdersValue / $totalOrders), 2);
                } else {
                    $data['customers'][$i]['Avg. Order Value'] = 0;
                }
                // echo "<pre>";print_r($data['customers']);die;
                $i++;
            }
        }
        //    echo "<pre>";print_r($data['customers']);die;
        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_all_status'] = $this->language->get('text_all_status');
        $data['column_customer'] = $this->language->get('column_customer');
        $data['column_email'] = $this->language->get('column_email');
        $data['column_customer_group'] = $this->language->get('column_customer_group');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_orders'] = $this->language->get('column_orders');
        $data['column_products'] = $this->language->get('column_products');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_action'] = $this->language->get('column_action');
        $data['entry_date_start'] = $this->language->get('entry_date_start');
        $data['entry_date_end'] = $this->language->get('entry_date_end');
        $data['entry_month_start'] = $this->language->get('entry_month_start');
        $data['entry_month_end'] = $this->language->get('entry_month_end');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_customer'] = $this->language->get('entry_customer');
        // $data['button_edit'] = $this->language->get('button_edit');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_show_filter'] = $this->language->get('button_show_filter');
        $data['button_hide_filter'] = $this->language->get('button_hide_filter');

        $data['token'] = $this->session->data['token'];
        $this->load->model('localisation/order_status');
        $data['order_statuses'] = $this->model_localisation_order_status->getValidOrderStatuses();

        $this->load->model('sale/customer');
        // $data['customer_names'] = $this->model_sale_customer->getCustomers(null);
        $url = '';
        if (isset($this->request->get['filter_date_start'])) {
            $url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
        }
        if (isset($this->request->get['filter_date_end'])) {
            $url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
        }

        if (isset($this->request->get['filter_sub_customer_show'])) {
            $url .= '&filter_sub_customer_show=' . $this->request->get['filter_sub_customer_show'];
        }
        if (isset($this->request->get['filter_order_status_id'])) {
            $url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
        }
        // if (isset($this->request->get['filter_customer'])) {
        //     $url .= '&filter_customer='.$this->request->get['filter_customer'];
        // }
        if (isset($this->request->get['filter_company'])) {
            $url .= '&filter_company=' . $this->request->get['filter_company'];
        }
        $pagination = new Pagination();
        $pagination->total = $company_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('report/customer_order_pattern', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($company_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($company_total - $this->config->get('config_limit_admin'))) ? $company_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $company_total, ceil($company_total / $this->config->get('config_limit_admin')));

        $data['filter_date_start'] = $filter_date_start;
        $data['filter_date_end'] = $filter_date_end;
        $data['filter_sub_customer_show'] = $filter_sub_customer_show;
        $data['filter_order_status_id'] = $filter_order_status_id;
        // $data['filter_customer'] = $filter_customer;
        $data['filter_company'] = $filter_company;
        $data['filter_account_manager_name'] = $filter_account_manager_name;
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('report/customer_order_pattern.tpl', $data));
    }

    public function getmonthname($month) {

        if ($month == 1) {
            $name = "January";
        } else if ($month == 2) {
            $name = "February";
        } else if ($month == 3) {
            $name = "March";
        } else if ($month == 4) {
            $name = "April";
        } else if ($month == 5) {
            $name = "May";
        } else if ($month == 6) {
            $name = "June";
        } else if ($month == 7) {
            $name = "July";
        } else if ($month == 8) {
            $name = "August";
        } else if ($month == 9) {
            $name = "September";
        } else if ($month == 10) {
            $name = "October";
        } else if ($month == 11) {
            $name = "November";
        } else if ($month == 12) {
            $name = "December";
        }
        return $name;
    }

    public function order_patternexcel() {
        $this->load->language('report/customer_order_pattern');

        $this->document->setTitle($this->language->get('heading_title'));

        if (isset($this->request->get['filter_date_start'])) {
            $filter_date_start = $this->request->get['filter_date_start'];
        }

        if (isset($this->request->get['filter_date_end'])) {
            $filter_date_end = $this->request->get['filter_date_end'];
        } else {
            $filter_date_end = date('Y-m-d');
        }

        if (isset($this->request->get['filter_order_status_id'])) {
            $filter_order_status_id = $this->request->get['filter_order_status_id'];
        } else {
            $filter_order_status_id = 0;
        }

        // if (isset($this->request->get['filter_customer'])) {
        //     $filter_customer = $this->request->get['filter_customer'];
        // } else {
        //     $filter_customer = 0;
        // }

        if (isset($this->request->get['filter_company'])) {
            $filter_company = $this->request->get['filter_company'];
        } else {
            $filter_company = 0;
        }

        if (isset($this->request->get['filter_account_manager_name'])) {
            $filter_account_manager_name = $this->request->get['filter_account_manager_name'];
        } else {
            $filter_account_manager_name = '';
        }
        $this->load->model('report/customer');

        $filter_account_manager_id = NULL;
        if ($filter_account_manager_name != NULL) {
            $this->load->model('user/accountmanager');
            $account_manager = $this->model_user_accountmanager->getAccountManagerByName($filter_account_manager_name);
            $log = new Log('error.log');
            $log->write($account_manager);
            if (is_array($account_manager) && count($account_manager) > 0) {
                $filter_account_manager_id = $account_manager['user_id'];
            } else {
                $filter_account_manager_id = NULL;
            }
        }

        if (isset($this->request->get['filter_sub_customer_show'])) {
            $filter_sub_customer_show = $this->request->get['filter_sub_customer_show'];
        } else {
            $filter_sub_customer_show = null;
        }

        $filter_data = [
            'filter_date_start' => $filter_date_start,
            'filter_date_end' => $filter_date_end,
            'filter_order_status_id' => $filter_order_status_id,
            'filter_customer' => $filter_customer,
            'filter_company' => $filter_company,
            'filter_account_manager_id' => $filter_account_manager_id,
            'filter_account_manager_name' => $filter_account_manager_name,
            'filter_sub_customer_show' => $filter_sub_customer_show,
        ];

        if ('' != $filter_date_start && '' != $filter_date_end) {
            $company_total = $this->model_report_customer->getTotalValidCompanies($filter_data);

            //$results = $this->model_report_customer->getValidCompanyOrders($filter_data);
            $customerresults = $this->model_report_customer->getValidCompanies($filter_data);
            // echo "<pre>";print_r($customerresults);die;
            $months = $this->model_report_customer->getmonths($filter_data); //need to check simple way
            //    echo "<pre>";print_r($months);die;
        } else {
            $company_total = 0;
            $customerresults = null; 
        }


        $this->load->model('sale/order');
        if (is_array($customerresults) && count($customerresults) > 0) {
            $log = new Log('error.log');
            $log->write('Yes It Is Array');
            $i = 0;
            foreach ($customerresults as $result) {
                $totalpermonth = 0;
                $data['customers'][] = [
                    'Company Name' => $result['company'],
                ];
                $totalOrders = 0;
                $OrdersValue = 0;
                foreach ($months as $month) {
                    $totalpermonth = $this->model_report_customer->getCompanyTotal($filter_data, $month['month'], $result['company'], $result['customer_id']);
                    $monthname = $this->getmonthname($month['month']);
                    $totalOrders = $totalOrders + $totalpermonth['TotalOrders'];
                    $OrdersValue = $OrdersValue + $totalpermonth['Total'];
                    //$data['customers'][$i][$monthname]=$this->currency->format($totalpermonth['Total'], $this->config->get('config_currency'));
                    $data['customers'][$i][$monthname] = number_format($totalpermonth['Total'], 2)??0;
                }
                $data['customers'][$i]['Total'] = number_format($OrdersValue);
                $data['customers'][$i]['Order Count'] = $totalOrders;
                if ($OrdersValue > 0 && $totalOrders > 0) {
                    $data['customers'][$i]['Avg. Order Value'] = number_format(($OrdersValue / $totalOrders), 2);
                } else {
                    $data['customers'][$i]['Avg. Order Value'] = 0;
                }
                // echo "<pre>";print_r($data['customers']);die;
                $i++;
            }
        }
        //    echo "<pre>";print_r($data['customers']);die;
        //  echo "<pre>";print_r($data['customers']);die;

        $this->load->model('report/excel');
        $this->model_report_excel->download_customer_order_pattern_excel($data['customers']);
    }

}
