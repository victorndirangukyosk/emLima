<?php

class ControllerApiCustomers extends Controller {

    public function getCustomer($args = []) {
        $this->load->language('api/customers');

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/customers');

            $customer_data = [];

            $customer = $this->model_api_customers->getCustomer($args['id']);

            if (!empty($customer)) {
                $this->load->model('api/orders');

                $c_args['customer'] = $customer['customer_id'];

                $orders = $this->model_api_orders->getAdminOrders($c_args);

                $customer['order_number'] = count($orders);

                if (empty($orders)) {
                    $customer['order_total'] = '0';
                    $customer['order_nice_total'] = $this->currency->format('0');
                } else {
                    $total = 0;

                    foreach ($orders as $order) {
                        $total += $order['total'];
                    }

                    $customer['order_total'] = (string) $total;
                    $customer['order_nice_total'] = $this->currency->format($total, $order['currency_code'], $order['currency_value']);
                }

                $customer_data[] = $customer;
            }

            //echo "<pre>";print_r($customer_data);die;
            $json = $customer_data;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addCustomer($args = []) {
        $this->load->language('api/customers');

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/customers');

            $json = $this->model_api_customers->addCustomer($args);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function editCustomer($args = []) {
        $this->load->language('api/customers');

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/customers');

            $json = $this->model_api_customers->editCustomer($args['id'], $args);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function deleteCustomer($args = []) {
        $this->load->language('api/customers');

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/customers');

            $this->model_api_customers->deleteCustomer($args['id']);

            $json['success'] = $this->language->get('text_success');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getCustomers($args = []) {
        $this->load->language('api/customers');

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/customers');

            $customer_data = [];

            $customers = $this->model_api_customers->getCustomers($args);

            if (!empty($customers)) {
                $this->load->model('api/orders');

                foreach ($customers as $customer) {
                    $c_args['customer'] = $customer['customer_id'];

                    $orders = $this->model_api_orders->getAdminOrders($c_args);

                    $customer['order_number'] = count($orders);

                    if (empty($orders)) {
                        $customer['order_total'] = '0';
                        $customer['order_nice_total'] = $this->currency->format('0');
                    } else {
                        $total = 0;

                        foreach ($orders as $order) {
                            $total += $order['total'];
                        }

                        $customer['order_total'] = (string) $total;
                        $customer['order_nice_total'] = $this->currency->format($total, $order['currency_code'], $order['currency_value']);
                    }

                    $customer_data[] = $customer;
                }
            }

            $json = $customer_data;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getTotals($args = []) {
        $this->load->language('api/customers');

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/customers');

            $json = $this->model_api_customers->getTotals($args);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getAddresses($args = []) {
        $this->load->language('api/customers');

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/customers');

            $json = $this->model_api_customers->getAddresses($args['id']);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getOrders($args = []) {
        $this->load->language('api/customers');

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/orders');

            $order_data = [];

            $args['customer'] = $args['id'];
            unset($args['id']);

            $results = $this->model_api_orders->getAdminOrders($args);

            if (!empty($results)) {
                $this->load->model('checkout/order');

                foreach ($results as $result) {
                    $order = $this->model_checkout_order->getOrder($result['order_id']);

                    $order['product_number'] = count($this->model_checkout_order->getOrderProducts($result['order_id']));

                    $order['nice_total'] = $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']);

                    $order_data[] = $order;
                }
            }

            $json = $order_data;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getCustomerGroups() {
        $this->load->model('account/customer_group');
        $json['status'] = 200;
        $json['data'] = $this->model_account_customer_group->getCustomerGroups();
        $json['msg'] = 'Customer Groups fetched successfully';
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getCustomerCities() {
        $this->load->model('account/customer_city');
        $json['status'] = 200;
        $json['data'] = $this->model_account_customer_city->getCities();
        $json['msg'] = 'Cities fetched successfully';
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getCustomerRegions() {
        $this->load->model('account/address');
        $json['status'] = 200;
        $json['data'] = $this->model_account_address->getAllRegions();

        $json['msg'] = 'Regions fetched successfully';
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getDeliveryTimeSlots() {
        $this->load->model('setting/store');
        $deliveryTimeslots = $this->model_setting_store->getDeliveryTimeslots(75);
        $json['status'] = 200;
        $json['data'] = $deliveryTimeslots;

        $json['msg'] = 'Delivery Time Slots Fetched Successfully';
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getAccountManagers($args = []) {
        try {
            $this->load->model('user/user');

            // echo "<pre>";print_r($args['filter_name']); die;

            $filter_data = [
                'filter_name' => $args['name'],
                    // 'filter_email' => $filter_email,
                    // 'filter_telephone' => $filter_telephone,
                    // 'start' => 0,
                    // 'limit' => 5,
            ];
            $json['status'] = 200;
            $results = $this->model_user_user->getAccountManagerUsers($filter_data);
            $finalAutoCompleteData = [];
            foreach ($results as $result) {

                $temp['user_id'] = $result['user_id'];
                $temp['user_group_id'] = $result['user_group_id'];
                $temp['username'] = strip_tags(html_entity_decode($result['username'], ENT_QUOTES, 'UTF-8'));
                $temp['name'] = $result['name'];
                $temp['firstname'] = $result['firstname'];
                $temp['lastname'] = $result['lastname'];
                $temp['email'] = $result['email'];
                $temp['telephone'] = $result['telephone'];
                $temp['status'] = $result['status'];

                array_push($finalAutoCompleteData, $temp);
            }
            $json['data'] = $finalAutoCompleteData;

            // $sort_order = [];
            // foreach ($json as $key => $value) {
            //     $sort_order[$key] = $value['name'];
            // }
            // array_multisort($sort_order, SORT_ASC, $json);
            // $log->write($json);
            // $this->response->addHeader('Content-Type: application/json');

            $json['msg'] = 'Account Managers fetched successfully';
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        } catch (exception $ex) {
            $json['status'] = 400;
            $json['data'] = '';
            $json['msg'] = 'Account Managers fetching failed';
        }
    }

}
