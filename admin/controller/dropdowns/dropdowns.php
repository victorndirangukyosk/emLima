<?php

class ControllerDropdownsDropdowns extends Controller {

    public function companynames() {
        $json = [];

        if (isset($this->request->post['filter_name']) || isset($this->request->post['filter_email']) || isset($this->request->post['filter_company'])) {
            if (isset($this->request->post['filter_name'])) {
                $filter_name = $this->request->post['filter_name'];
            } else {
                $filter_name = '';
            }

            if (isset($this->request->post['filter_email'])) {
                $filter_email = $this->request->post['filter_email'];
            } else {
                $filter_email = '';
            }

            if (isset($this->request->post['filter_company'])) {
                $filter_company = $this->request->post['filter_company'];
            } else {
                $filter_company = '';
            }

            $this->load->model('sale/customer');

            $filter_data = [
                'filter_name' => $filter_name,
                'filter_email' => $filter_email,
                'filter_company' => $filter_company,
                'start' => 0,
                'limit' => 5,
            ];

            $results = $this->model_sale_customer->getCustomersNew($filter_data);

            foreach ($results as $result) {
                $json[] = [
                    'tag' => strip_tags(html_entity_decode($result['company_name'], ENT_QUOTES, 'UTF-8')),
                    'value' => (int) $result['customer_id'],
                ];
            }
        }

        $sort_order = [];

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['tag'];
        }

        array_multisort($sort_order, SORT_ASC, $json);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode(['suggestions' => $json]));
    }

    public function orderids() {
        $json = [];

        if (isset($this->request->post['filter_order_id'])) {
            if (isset($this->request->post['filter_order_id'])) {
                $filter_order_id = $this->request->post['filter_order_id'];
            } else {
                $filter_order_id = '';
            }

            $this->load->model('sale/customer');

            $filter_data = [
                'filter_order_id' => $filter_order_id,
                'start' => 0,
                'limit' => 5,
            ];

            $results = $this->model_sale_customer->getOrdersFilterNew($filter_data);

            foreach ($results as $result) {
                $json[] = [
                    'tag' => $result['order_id'],
                    'value' => (int) $result['customer_id'],
                ];
            }
        }

        $sort_order = [];

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['tag'];
        }

        array_multisort($sort_order, SORT_ASC, $json);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode(['suggestions' => $json]));
    }

}
