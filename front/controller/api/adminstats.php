<?php

class Controllerapiadminstats extends Controller
{
    public function getAdminstats($args = [])
    {
        //echo "er";die;
        $this->load->language('api/stats');

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/orders');
            $this->load->model('api/products');
            $this->load->model('api/customers');

            $data = [];

            if (!isset($args['status'])) {
                $complete_status = $this->config->get('config_complete_status');
                $processing_status = $this->config->get('config_processing_status');

                $args['status'] = implode(',', $complete_status).','.implode(',', $processing_status);
            }

            $orders = $this->model_api_orders->getAdminTotals($args);

            /*SELECT COUNT(*) AS number, SUM(o.total) AS price FROM `hf7_order` o WHERE (o.order_status_id = '7' OR o.order_status_id = '5' OR o.order_status_id = '11' OR o.order_status_id = '2') AND o.date_added >= '2017-06-08 00:00:00' AND o.date_added <= '2017-06-08 23:59:59'*/
            $orders['nice_price'] = $this->currency->format($orders['price']);

            $products = $this->model_api_products->getAdminTotals($args);

            //echo "<pre>";print_r($products);die;

            $customers = $this->model_api_customers->getTotals($args);

            //echo "<pre>";print_r($customers);die;
            if (!empty($args['date_from']) && !empty($args['date_to'])) {
                $this->load->model('api/stats');

                $orders['daily'] = $this->model_api_stats->getAdminDailyOrders($args);
                $products['daily'] = $this->model_api_stats->getAdminDailyProducts($args);
                $customers['daily'] = $this->model_api_stats->getAdminDailyCustomers($args);
            }

            $data['orders'] = $orders;
            $data['products'] = $products;
            $data['customers'] = $customers;

            //echo "<pre>";print_r($data['products']);die;
            $json = $data;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getOrders($args = [])
    {
        $this->load->controller('api/orders/gettotals', $args);
    }

    public function getCustomers($args = [])
    {
        $this->load->controller('api/customers/gettotals', $args);
    }

    public function getProducts($args = [])
    {
        $this->load->controller('api/products/gettotals', $args);
    }
}
