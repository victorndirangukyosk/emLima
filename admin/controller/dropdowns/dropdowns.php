<?php

class ControllerPaymentPezesha extends Controller {

    public function companynames() {
        $json = [];

        if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_email'])) {
            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            if (isset($this->request->get['filter_email'])) {
                $filter_email = $this->request->get['filter_email'];
            } else {
                $filter_email = '';
            }

            $this->load->model('sale/customer');

            $filter_data = [
                'filter_name' => $filter_name,
                'filter_email' => $filter_email,
                'start' => 0,
                'limit' => 5,
            ];

            $results = $this->model_sale_customer->getCustomers($filter_data);

            foreach ($results as $result) {
                if ($this->user->isVendor()) {
                    $result['name'] = $result['firstname'];
                }

                $json[] = [
                    'customer_id' => $result['customer_id'],
                    'customer_group_id' => $result['customer_group_id'],
                    'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                    'customer_group' => $result['customer_group'],
                    'firstname' => $result['firstname'],
                    'lastname' => $result['lastname'],
                    'email' => $result['email'],
                    'telephone' => $result['telephone'],
                    'fax' => $result['fax'],
                    'custom_field' => unserialize($result['custom_field']),
                    'address' => $this->model_sale_customer->getAddresses($result['customer_id']),
                ];
            }
        }

        $sort_order = [];

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['name'];
        }

        array_multisort($sort_order, SORT_ASC, $json);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
