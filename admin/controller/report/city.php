<?php

class ControllerReportCity extends Controller
{
    public function index()
    {
        $this->load->language('report/city');

        $this->load->model('report/city');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_orders'] = $this->language->get('text_orders');
        $data['text_vendors'] = $this->language->get('text_vendors');
        $data['text_stores'] = $this->language->get('text_stores');
        $data['text_shoppers'] = $this->language->get('text_shoppers');

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
                'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
                'text' => $this->language->get('text_home'),
        ];

        $data['breadcrumbs'][] = [
                'href' => $this->url->link('report/city', 'token='.$this->session->data['token'], 'SSL'),
                'text' => $this->language->get('heading_title'),
        ];

        $cities = $this->model_report_city->getCities();

        $data['orders'] = [];

        foreach ($cities as $city) {
            $data['orders'][] = [
                'label' => $city['name'],
                'data' => $this->model_report_city->getTotalOrder($city['city_id']),
            ];
        }

        $data['vendors'] = [];

        foreach ($cities as $city) {
            $data['vendors'][] = [
                'label' => $city['name'],
                'data' => $this->model_report_city->getTotalVendor($city['city_id']),
            ];
        }

        $data['shoppers'] = [];

        foreach ($cities as $city) {
            $data['shoppers'][] = [
                'label' => $city['name'],
                'data' => $this->model_report_city->getTotalShopper($city['city_id']),
            ];
        }

        $data['stores'] = [];

        foreach ($cities as $city) {
            $data['stores'][] = [
                'label' => $city['name'],
                'data' => $this->model_report_city->getTotalStore($city['city_id']),
            ];
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('report/city.tpl', $data));
    }
}
