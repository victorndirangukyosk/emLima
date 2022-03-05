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

    public function getDeliveryTimeslots() {
        $json = [];
        $this->load->model('setting/store');
        $deliveryTimeslots = $this->model_setting_store->getDeliveryTimeslots(75);
        $log = new Log('error');
        $json['delivery_timeslots'] = $deliveryTimeslots;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode(['suggestions' => $json]));
    }

    public function product_autocomplete() {

        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = '';
        }

        $this->load->model('sale/order');
        $this->load->model('catalog/vendor_product');

        $send = [];

        $json = $this->model_sale_order->getProductsForInventory($filter_name);
        $log = new Log('error.log');

        foreach ($json as $j) {
            if (isset($j['special_price']) && !is_null($j['special_price']) && $j['special_price'] && (float) $j['special_price']) {
                $j['price'] = $j['special_price'];
            }

            $j['name'] = htmlspecialchars_decode($j['name']);

            $send[] = $j;
        }
        echo json_encode($send);
    }

    public function getVendorProductVariantsInfo() {

        $log = new Log('error.log');
        $log->write($this->request->get['product_store_id']);
        $this->load->model('sale/order');
        $this->load->model('catalog/vendor_product');
        $product_details = $this->model_catalog_vendor_product->getProduct($this->request->get['product_store_id']);
        $product_info = $this->model_sale_order->getProductForPopup($this->request->get['product_store_id'], false, $product_details['store_id']);
        $variations = $this->model_sale_order->getVendorProductVariations($product_info['name'], $product_details['store_id']);
        $log->write($variations);
        $json = $variations;

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getCustomerExperienceTeam() {
        $json = [];

        if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_email']) || isset($this->request->get['filter_telephone'])) {
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

            if (isset($this->request->get['filter_telephone'])) {
                $filter_telephone = $this->request->get['filter_telephone'];
            } else {
                $filter_telephone = '';
            }

            $this->load->model('user/user');

            $filter_data = [
                'filter_name' => $filter_name,
                'filter_email' => $filter_email,
                'filter_telephone' => $filter_telephone,
                'start' => 0,
                'limit' => 5,
            ];

            $results = $this->model_user_user->getCustomerExperienceUsers($filter_data);

            foreach ($results as $result) {
                if ($this->user->isVendor()) {
                    $result['name'] = $result['firstname'];
                }

                $json[] = [
                    'user_id' => $result['user_id'],
                    'user_group_id' => $result['user_group_id'],
                    'username' => strip_tags(html_entity_decode($result['username'], ENT_QUOTES, 'UTF-8')),
                    'name' => $result['name'],
                    'firstname' => $result['firstname'],
                    'lastname' => $result['lastname'],
                    'email' => $result['email'],
                    'telephone' => $result['telephone']
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
