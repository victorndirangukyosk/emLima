<?php

class ControllerSaleOrderDashBoard extends Controller {

    private $error = [];

    public function index() {

        $shopper_group_ids = explode(',', $this->config->get('config_shopper_group_ids'));

        if (in_array($this->user->getGroupId(), $shopper_group_ids)) {
            $this->response->redirect($this->url->link('shopper/request', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->load->language('common/dashboard');

        $this->document->setTitle($this->language->get('sales_heading_title'));

        $data['heading_title'] = $this->language->get('sales_heading_title');

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

        $this->salesdashboard($data);
    }

    private function salesdashboard($data) {

        // Check install directory exists
        if (is_dir(dirname(DIR_APPLICATION) . '/install')) {
            $data['error_install'] = $this->language->get('error_install');
        } else {
            $data['error_install'] = '';
        }

        $data['order'] = $this->load->controller('dashboard/order');
        $data['sale'] = $this->load->controller('dashboard/sale');
        $data['chart'] = $this->load->controller('dashboard/chart');
        $data['charts'] = $this->load->controller('dashboard/charts/salesdashboard');
        $data['recent'] = $this->load->controller('dashboard/recent');
        $data['recenttabs'] = $this->load->controller('dashboard/recenttabs/custom_index');
        $data['actualSales'] = $this->load->controller('dashboard/sale/accountmanagerActualSales');

        // Run currency update
        if ($this->config->get('config_currency_auto')) {
            $this->load->model('localisation/currency');
            $this->model_localisation_currency->refresh();
        }

        $this->response->setOutput($this->load->view('common/sales_dashboard.tpl', $data));
    }

}
