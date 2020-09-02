<?php

class ControllerApiCustomers extends Controller
{
    public function getCustomer($args = [])
    {
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

    public function addCustomer($args = [])
    {
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

    public function editCustomer($args = [])
    {
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

    public function deleteCustomer($args = [])
    {
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

    public function getCustomers($args = [])
    {
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

    public function getTotals($args = [])
    {
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

    public function getAddresses($args = [])
    {
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

    public function getOrders($args = [])
    {
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

    public function getCustomerGroups()
    {
        $this->load->model('account/customer_group');
        $json['status'] = 200;
        $json['data'] = $this->model_account_customer_group->getCustomerGroups();
        $json['msg'] = 'Customer Groups fetched successfully';
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
