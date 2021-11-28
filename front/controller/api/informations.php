<?php

class ControllerApiInformations extends Controller
{
    public function getInformation($args = [])
    {
        $this->load->language('api/informations');

        //get information_to_store entry should be there

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/informations');

            $information = $this->model_api_informations->getInformation($args);

            //echo "Efew";print_r($information);die;
            if (count($information) > 0) {
                $information['title'] = html_entity_decode($information['title'], ENT_QUOTES, 'UTF-8');
                $information['description'] = html_entity_decode($information['description'], ENT_QUOTES, 'UTF-8');

                $json = $information;
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getInformations($args = [])
    {
        $this->load->language('api/informations');

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/informations');

            $information_data = [];

            $results = $this->model_api_informations->getInformations($args);

            //echo "Efew";print_r($results);die;

            if (!empty($results)) {
                foreach ($results as $result) {
                    $result['title'] = html_entity_decode($result['title'], ENT_QUOTES, 'UTF-8');
                    $result['description'] = html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8');

                    $information_data[] = $result;
                }
            }

            $json = $information_data;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getTotals($args = [])
    {
        $this->load->language('api/informations');

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/informations');

            $json = $this->model_api_informations->getTotals($args);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
