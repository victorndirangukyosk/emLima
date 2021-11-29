<?php

class ControllerApiStats extends Controller
{
    public function getStats($args = [])
    {
        $this->load->language('api/stats');

        $json = [];
        $log = new Log('error.log');
        $log->write('getStats');

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/orders');
            $this->load->model('api/products');
            $this->load->model('api/customers');

            $data = [];

            /*if (!isset($args['status'])) {
                $complete_status = $this->config->get('config_complete_status');
                $processing_status = $this->config->get('config_processing_status');

                $args['status'] = implode(',', $complete_status). ',' . implode(',', $processing_status);
            }*/

            $orders = $this->model_api_orders->getTotals($args);

            if (is_null($orders['price'])) {
                $orders['price'] = 0;
            }
            //echo "<pre>";print_r($orders);die;

            /*SELECT COUNT(*) AS number, SUM(o.total) AS price FROM `hf7_order` o WHERE (o.order_status_id = '7' OR o.order_status_id = '5' OR o.order_status_id = '11' OR o.order_status_id = '2') AND o.date_added >= '2017-06-08 00:00:00' AND o.date_added <= '2017-06-08 23:59:59'*/
            $orders['nice_price'] = $this->currency->format($orders['price'], $this->config->get('config_currency'), false);

            $products = $this->model_api_products->getTotals($args);

            //echo "<pre>";print_r($products);die;

            $customers = $this->model_api_customers->getTotals($args);

            //echo "<pre>";print_r($customers);die;
            if (!empty($args['date_from']) && !empty($args['date_to'])) {
                $this->load->model('api/stats');

                $orders['daily'] = $this->model_api_stats->getDailyOrders($args);
                $products['daily'] = $this->model_api_stats->getDailyProducts($args);
                $customers['daily'] = $this->model_api_stats->getDailyCustomers($args);

                /* started */

                $log->write($args);

                $orders['createdOrders'] = $this->getTodayOrderChartData('xyz', 'day', $args);
                $orders['deliveredOrders'] = $this->getTodayOrderChartData('complete', 'day', $args);
                $orders['cancelledOrders'] = $this->getTodayOrderChartData('cancelled', 'day', $args);

                $calc = 0;
                if (count($orders['createdOrders']) <= 0) {
                    $orders['createdOrders']['total'] = 0;
                    $orders['createdOrders']['value'] = $this->currency->format(0, $this->config->get('config_currency'), false);
                } else {
                    $calc = $orders['createdOrders']['value'];

                    $orders['createdOrders']['value'] = $this->currency->format($orders['createdOrders']['value'], $this->config->get('config_currency'), false);
                }

                if (count($orders['deliveredOrders']) <= 0) {
                    $orders['deliveredOrders']['total'] = 0;
                    $orders['deliveredOrders']['value'] = $this->currency->format(0, $this->config->get('config_currency'), false);
                } else {
                    $orders['deliveredOrders']['value'] = $this->currency->format($orders['deliveredOrders']['value'], $this->config->get('config_currency'), false);
                }

                if (count($orders['cancelledOrders']) <= 0) {
                    $orders['cancelledOrders']['total'] = 0;
                    $orders['cancelledOrders']['value'] = $this->currency->format(0, $this->config->get('config_currency'), false);
                } else {
                    $orders['cancelledOrders']['value'] = $this->currency->format($orders['cancelledOrders']['value'], $this->config->get('config_currency'), false);
                }

                if (is_null($orders['createdOrders']['value'])) {
                    $orders['createdOrders']['value'] = $this->currency->format(0, $this->config->get('config_currency'), false);
                }
                if (is_null($orders['deliveredOrders']['value'])) {
                    $orders['deliveredOrders']['value'] = $this->currency->format(0, $this->config->get('config_currency'), false);
                }
                if (is_null($orders['cancelledOrders']['value'])) {
                    $orders['cancelledOrders']['value'] = $this->currency->format(0, $this->config->get('config_currency'), false);
                }

                if ($orders['createdOrders']['total']) {
                    $orders['avg_order_value'] = $this->currency->format(($calc / $orders['createdOrders']['total']), $this->config->get('config_currency'), false);
                } else {
                    $orders['avg_order_value'] = $this->currency->format($calc, $this->config->get('config_currency'), false);
                }

                $days = 1;

                if (isset($args['date_from']) && isset($args['date_to'])) {
                    $from = date_create($args['date_from']);
                    $to = date_create($args['date_to']);
                    $diff = date_diff($from, $to);

                    $days = $diff->format('%R%a') + 1;
                    $log->write($days);
                }

                $orders['avg_order_day'] = round($orders['createdOrders']['total'] / $days, 2);

                $log->write($orders);
                $log->write('end');

                /* end */
            }

            $data['orders'] = $orders;
            $data['products'] = $products;
            $data['customers'] = $customers;

            //echo "<pre>";print_r($data);die;
            $json = $data;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getTodayOrderChartData($type, $range, $args)
    {
        $results = '';
        $modelFunction = 'getTodaysOrders';
        $this->load->model('api/stats');

        $json = [];

        if (isset($args['date_from'])) {
            $start = $args['date_from'];
        } else {
            $start = '';
        }

        if (!empty($args['date_to'])) {
            $end = $args['date_to'];
        } else {
            $end = '';
        }

        $date_start = date_create($start)->format('Y-m-d');
        $date_end = date_create($end)->format('Y-m-d');

        switch ($range) {
            case 'hour':
                $results = $this->model_api_stats->{$modelFunction}($date_start, $date_end, 'HOUR', $type, $args);

                break;
            default:
            case 'day':
                $results = $this->model_api_stats->{$modelFunction}($date_start, $date_end, 'DAY', $type, $args);

                break;
            case 'month':
                $results = $this->model_api_stats->{$modelFunction}($date_start, $date_end, 'MONTH', $type, $args);

                break;
            case 'year':
                $results = $this->model_api_stats->{$modelFunction}($date_start, $date_end, 'YEAR', $type, $args);

                break;
        }

        return $results;
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
