<?php

class ControllerCommonDashboard extends Controller
{
    public function temp()
    {
        $this->load->model('catalog/product');

        $results = $this->model_catalog_product->getProductIds();

        foreach ($results as $row) {
            $this->model_catalog_product->copyProduct($row['product_id']);
        }

        echo 'done!';
        die();
    }

    public function index()
    {
        $shopper_group_ids = explode(',', $this->config->get('config_shopper_group_ids'));

        if (in_array($this->user->getGroupId(), $shopper_group_ids)) {
            $this->response->redirect($this->url->link('shopper/request', 'token='.$this->session->data['token'], 'SSL'));
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
            'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $data['token'] = $this->session->data['token'];

        if ($this->user->isVendor()) {
            $this->vendor($data);
        } elseif($this->user->isAccountManager()) {
            $this->isAccountManager($data);
        } else {
            $this->admin($data);
        }
    }

    private function vendor($data)
    {
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

    private function admin($data)
    {
        // Check install directory exists
        if (is_dir(dirname(DIR_APPLICATION).'/install')) {
            $data['error_install'] = $this->language->get('error_install');
        } else {
            $data['error_install'] = '';
        }

        $data['order'] = $this->load->controller('dashboard/order');
        $data['sale'] = $this->load->controller('dashboard/sale');
        $data['customer'] = $this->load->controller('dashboard/customer');
        $data['online'] = $this->load->controller('dashboard/online');
        $data['map'] = $this->load->controller('dashboard/map');
        $data['chart'] = $this->load->controller('dashboard/chart');
        $data['charts'] = $this->load->controller('dashboard/charts');
        $data['activity'] = $this->load->controller('dashboard/activity');
        $data['recent'] = $this->load->controller('dashboard/recent');
        $data['recenttabs'] = $this->load->controller('dashboard/recenttabs');

        // Run currency update
        if ($this->config->get('config_currency_auto')) {
            $this->load->model('localisation/currency');
            $this->model_localisation_currency->refresh();
        }

        $this->response->setOutput($this->load->view('common/dashboard.tpl', $data));
    }

    public function export_mostpurchased_products_excel($customer_id)
    {
        $data = [];

        if (isset($this->request->get['customer_id'])) {
            $data['customer_id'] = $this->request->get['customer_id'];
        }

        $this->load->model('report/excel');
        $this->model_report_excel->download_mostpurchased_products_excel($data);
    }
}
