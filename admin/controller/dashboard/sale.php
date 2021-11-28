<?php

class ControllerDashboardSale extends Controller
{
    public function index()
    {
        $this->load->language('dashboard/sale');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        $this->load->model('report/sale');

        $today = $this->model_report_sale->getTotalSales(['filter_date_added' => date('Y-m-d', strtotime('-1 day'))]);

        $yesterday = $this->model_report_sale->getTotalSales(['filter_date_added' => date('Y-m-d', strtotime('-2 day'))]);

        $difference = $today - $yesterday;

        if ($difference && $today > 0) {
            $data['percentage'] = round(($difference / $today) * 100);
        } else {
            $data['percentage'] = 0;
        }

        $sale_total = $this->model_report_sale->getTotalSales();
        $data['total'] = 'KSh  '.number_format($sale_total, 2);

        /*if ($sale_total > 1000000000000) {
            $data['total'] = round($sale_total / 1000000000000, 1) . 'T';
        } elseif ($sale_total > 1000000000) {
            $data['total'] = round($sale_total / 1000000000, 1) . 'B';
        } elseif ($sale_total > 1000000) {
            $data['total'] = round($sale_total / 1000000, 1) . 'M';
        } elseif ($sale_total > 1000) {
            $data['total'] = round($sale_total / 1000, 1) . 'K';
        } else {
            $data['total'] = round($sale_total);
        }*/

        $data['sale'] = $this->url->link('sale/order', 'token='.$this->session->data['token'], 'SSL');

        return $this->load->view('dashboard/sale.tpl', $data);
    }
    
    public function index_custom()
    {
        $data['token'] = $this->session->data['token'];
        $this->load->model('report/sale');
        //$sale_total = $this->model_report_sale->getTotalSales();
        $data['filter_date_start'] = $this->request->get['start'];
        $data['filter_date_end'] = $this->request->get['end'];
        $sale_total = $this->model_report_sale->getTotalSalesCustom($data);
        $data['total'] = 'KSh  '.number_format($sale_total, 2);
        $data['sale'] = $this->url->link('sale/order', 'token='.$this->session->data['token'], 'SSL');
        $json['data'] = $data;
        $this->response->setOutput(json_encode($json));
    }

    public function vendor()
    {
        $this->load->language('dashboard/sale');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        $this->load->model('report/sale');

        $today = $this->model_report_sale->getVendorTotalSales(['filter_date_added' => date('Y-m-d', strtotime('-1 day'))]);

        $yesterday = $this->model_report_sale->getVendorTotalSales(['filter_date_added' => date('Y-m-d', strtotime('-2 day'))]);

        $difference = $today - $yesterday;

        if ($difference && $today) {
            $data['percentage'] = round(($difference / $today) * 100);
        } else {
            $data['percentage'] = 0;
        }

        $sale_total = $this->model_report_sale->getVendorTotalSales();

        if ($sale_total > 1000000000000) {
            $data['total'] = round($sale_total / 1000000000000, 1).'T';
        } elseif ($sale_total > 1000000000) {
            $data['total'] = round($sale_total / 1000000000, 1).'B';
        } elseif ($sale_total > 1000000) {
            $data['total'] = round($sale_total / 1000000, 1).'M';
        } elseif ($sale_total > 1000) {
            $data['total'] = round($sale_total / 1000, 1).'K';
        } else {
            $data['total'] = round($sale_total);
        }

        $data['sale'] = $this->url->link('sale/order', 'token='.$this->session->data['token'], 'SSL');

        return $this->load->view('dashboard/sale.tpl', $data);
    }
    
    public function accountmanager()
    {
        $this->load->language('dashboard/sale');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        $this->load->model('report/sale');

        $today = $this->model_report_sale->getAccountManagerTotalSales(['filter_date_added' => date('Y-m-d', strtotime('-1 day'))]);

        $yesterday = $this->model_report_sale->getAccountManagerTotalSales(['filter_date_added' => date('Y-m-d', strtotime('-2 day'))]);

        $difference = $today - $yesterday;

        if ($difference && $today) {
            $data['percentage'] = round(($difference / $today) * 100);
        } else {
            $data['percentage'] = 0;
        }

        $sale_total = $this->model_report_sale->getAccountManagerTotalSales();

        if ($sale_total > 1000000000000) {
            $data['total'] = round($sale_total / 1000000000000, 1).'T';
        } elseif ($sale_total > 1000000000) {
            $data['total'] = round($sale_total / 1000000000, 1).'B';
        } elseif ($sale_total > 1000000) {
            $data['total'] = round($sale_total / 1000000, 1).'M';
        } elseif ($sale_total > 1000) {
            $data['total'] = round($sale_total / 1000, 1).'K';
        } else {
            $data['total'] = round($sale_total);
        }

        $data['sale'] = $this->url->link('sale/order', 'token='.$this->session->data['token'], 'SSL');

        return $this->load->view('dashboard/sale.tpl', $data);
    }
    
    public function vendorActualSales()
    {
        $this->load->language('dashboard/sale');

        $data['heading_title'] = $this->language->get('actual_heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        $this->load->model('report/sale');

        // $today = $this->model_report_sale->getVendorTotalSales(array('filter_date_added' => date('Y-m-d', strtotime('-1 day'))));

        // $yesterday = $this->model_report_sale->getVendorTotalSales(array('filter_date_added' => date('Y-m-d', strtotime('-2 day'))));

        // $difference = $today - $yesterday;

        // if ($difference && $today) {
        // 	$data['percentage'] = round(($difference / $today) * 100);
        // } else {
        // 	$data['percentage'] = 0;
        // }

        $sale_total = $this->model_report_sale->getActualVendorSales();

        if ($sale_total > 1000000000000) {
            $data['total'] = round($sale_total / 1000000000000, 1).'T';
        } elseif ($sale_total > 1000000000) {
            $data['total'] = round($sale_total / 1000000000, 1).'B';
        } elseif ($sale_total > 1000000) {
            $data['total'] = round($sale_total / 1000000, 1).'M';
        } elseif ($sale_total > 1000) {
            $data['total'] = round($sale_total / 1000, 1).'K';
        } else {
            $data['total'] = round($sale_total);
        }

        $data['sale'] = $this->url->link('sale/order', 'token='.$this->session->data['token'], 'SSL');

        return $this->load->view('dashboard/sale.tpl', $data);
    }
    
    public function accountmanagerActualSales()
    {
        $this->load->language('dashboard/sale');

        $data['heading_title'] = $this->language->get('actual_heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        $this->load->model('report/sale');

        // $today = $this->model_report_sale->getVendorTotalSales(array('filter_date_added' => date('Y-m-d', strtotime('-1 day'))));

        // $yesterday = $this->model_report_sale->getVendorTotalSales(array('filter_date_added' => date('Y-m-d', strtotime('-2 day'))));

        // $difference = $today - $yesterday;

        // if ($difference && $today) {
        // 	$data['percentage'] = round(($difference / $today) * 100);
        // } else {
        // 	$data['percentage'] = 0;
        // }

        $sale_total = $this->model_report_sale->getActualAccountManagerSales();

        if ($sale_total > 1000000000000) {
            $data['total'] = round($sale_total / 1000000000000, 1).'T';
        } elseif ($sale_total > 1000000000) {
            $data['total'] = round($sale_total / 1000000000, 1).'B';
        } elseif ($sale_total > 1000000) {
            $data['total'] = round($sale_total / 1000000, 1).'M';
        } elseif ($sale_total > 1000) {
            $data['total'] = round($sale_total / 1000, 1).'K';
        } else {
            $data['total'] = round($sale_total);
        }

        $data['sale'] = $this->url->link('sale/order', 'token='.$this->session->data['token'], 'SSL');

        return $this->load->view('dashboard/actual_sale.tpl', $data);
    }
    
    public function ActualSales()
    {
        $this->load->language('dashboard/sale');

        $data['heading_title'] = $this->language->get('actual_heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        $this->load->model('report/sale');
        
        $data['filter_date_start'] = $this->request->get['start'];
        $data['filter_date_end'] = $this->request->get['end'];
        $data['account_manager'] = isset($this->request->get['account_manager']) && $this->request->get['account_manager'] > 0 ? $this->request->get['account_manager'] : '';
        $sale_total = $this->model_report_sale->getActualSales($data);

        if ($sale_total > 1000000000000) {
            $data['total'] = round($sale_total / 1000000000000, 1).'T';
        } elseif ($sale_total > 1000000000) {
            $data['total'] = round($sale_total / 1000000000, 1).'B';
        } elseif ($sale_total > 1000000) {
            $data['total'] = round($sale_total / 1000000, 1).'M';
        } elseif ($sale_total > 1000) {
            $data['total'] = round($sale_total / 1000, 1).'K';
        } else {
            $data['total'] = round($sale_total);
        }

        $data['sale'] = $this->url->link('sale/order', 'token='.$this->session->data['token'], 'SSL');

        $json['data'] = $data;
        $this->response->setOutput(json_encode($json));
    }
}
