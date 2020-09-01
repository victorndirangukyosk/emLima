<?php

class ControllerApiFastOrders extends Controller
{
    public function getFastOrders($args = [])
    {
        $this->load->language('api/orders');

        $json = [];

        $log = new Log('error.log');
        $log->write('getFastOrders');

        //echo "<pre>";print_r($this->session->data['api_id']);die;
        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/orders');
            $this->load->model('account/order');

            $order_data = [];

            //$results = $this->model_api_orders->getOrders($args);

            $order_total_final = [];
            $results_final = [];

            if (!empty($args['filter_order_status']) || !empty($args['filter_order_day'])) {
                //echo "<pre>";print_r($args['filter_order_status']);die;
                $filter_order_status_temp = explode(',', $args['filter_order_status']);

                //echo "<pre>";print_r($filter_order_status_temp);die;

                foreach ($filter_order_status_temp as $tmp) {
                    // code...
                    $args['filter_order_status'] = $tmp;
                    $args['sort'] = 'o.delivery_timeslot';
                    /*if(isset($args['page'])) {
                        $args['start'] = ($args['page'] - 1) * $this->config->get('config_limit_admin');
                        $args['limit'] = $this->config->get('config_limit_admin');
                    }*/

                    //$order_total = $this->model_api_orders->getTotalOrdersFilter($args);

                    $results = $this->model_api_orders->getOrdersFilter($args);

                    //echo "<pre>";print_r($results);
                    $temp = $results;

                    $amTimeslot = [];
                    $pmTimeslot = [];
                    $inPmfirstTimeslot = [];

                    //sort timeslot based

                    //echo "<pre>";print_r($temp);die;
                    foreach ($temp as $temp1) {
                        $temp2 = explode('-', $temp1['delivery_timeslot']);

                        if (false !== strpos($temp2[0], 'am')) {
                            array_push($amTimeslot, $temp1);
                        } else {
                            if ('12' == substr($temp2[0], 0, 2)) {
                                array_push($inPmfirstTimeslot, $temp1);
                            } else {
                                array_push($pmTimeslot, $temp1);
                            }
                        }
                    }

                    // echo "<pre>";print_r($amTimeslot);die;
                    // echo "<pre>";print_r($pmTimeslot);die;

                    foreach ($inPmfirstTimeslot as $te) {
                        array_push($amTimeslot, $te);
                    }

                    foreach ($pmTimeslot as $te) {
                        array_push($amTimeslot, $te);
                    }

                    $results = $amTimeslot;
                    array_push($results_final, $results);
                }

                //die;
                //$order_total = array_sum($order_total_final);
            } else {
                $order_total = 0;
            }

            $results = $results_final;

            $final_order_data = [];
            if (!empty($results_final) || count($results_final) > 0) {
                $this->load->model('checkout/order');
                foreach ($results_final as $key => $results) {
                    $result_status_tmp = null;

                    foreach ($results as $result) {
                        $order = $this->model_checkout_order->getOrder($result['order_id']);

                        $order['subtotal'] = 0;
                        $order['nice_subtotal'] = 0;

                        if (isset($order)) {
                            $data['totals'] = [];

                            $totals = $this->model_account_order->getOrderTotals($result['order_id']);

                            foreach ($totals as $total) {
                                if ('sub_total' == $total['code']) {
                                    $order['subtotal'] = $total['value'];
                                    $order['nice_subtotal'] = $this->currency->format($order['subtotal'], $order['currency_code'], $order['currency_value']);
                                }
                            }
                        }

                        $order['nice_total'] = $this->currency->format($order['total'], $order['currency_code'], $order['currency_value']);

                        $order['products'] = [];

                        $products = $this->model_account_order->getOrderProducts($result['order_id']);

                        $order['products_quantity'] = 0;

                        if (!empty($products)) {
                            foreach ($products as $product) {
                                $product['nice_total'] = $this->currency->format($product['total'], $order['currency_code'], $order['currency_value']);

                                $order['products_quantity'] += $product['quantity'];

                                $order['products'][] = $product;
                            }
                        }

                        $order_data[] = $order;

                        $result_status_tmp = $result['status'];

                        $final_order_data[] = $order;
                    }

                    if (!is_null($result_status_tmp)) {
                        //$final_order_data[$result_status_tmp] = $order_data;
                    }
                }
            }

            if (count($final_order_data) < 1) {
                $final_order_data = (object) [];
            }

            $json = $final_order_data;

            $log->write($json);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
