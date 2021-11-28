<?php

class ControllerApiManufacturers extends Controller
{
    public function getManufacturer($args = [])
    {
        $this->load->language('api/manufacturers');

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/manufacturers');

            $manufacturer = $this->model_api_manufacturers->getManufacturer($args);

            $manufacturer['name'] = html_entity_decode($manufacturer['name'], ENT_QUOTES, 'UTF-8');
            $manufacturer['description'] = html_entity_decode($manufacturer['description'], ENT_QUOTES, 'UTF-8');

            if ($this->request->server['HTTPS']) {
                $manufacturer['image'] = str_replace($this->config->get('config_ssl'), '', $manufacturer['image']);
            } else {
                $manufacturer['image'] = str_replace($this->config->get('config_url'), '', $manufacturer['image']);
            }

            $json = $manufacturer;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getManufacturers($args = [])
    {
        $this->load->language('api/manufacturers');

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/manufacturers');

            $manufacturer_data = [];

            $results = $this->model_api_manufacturers->getManufacturers($args);

            if (!empty($results)) {
                foreach ($results as $result) {
                    $result['name'] = html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8');
                    $result['description'] = html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8');

                    if ($this->request->server['HTTPS']) {
                        $result['image'] = str_replace($this->config->get('config_ssl'), '', $result['image']);
                    } else {
                        $result['image'] = str_replace($this->config->get('config_url'), '', $result['image']);
                    }

                    $manufacturer_data[] = $result;
                }
            }

            $json = $manufacturer_data;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getTotals($args = [])
    {
        $this->load->language('api/manufacturers');

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/manufacturers');

            $json = $this->model_api_manufacturers->getTotals($args);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getProducts($args = [])
    {
        $vars = [];
        $vars['manufacturer'] = $args['id'];

        $this->load->controller('api/products/getproducts', $vars);
    }
}
