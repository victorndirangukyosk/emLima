<?php

class ControllerApiOrders extends Controller
{
    public function getOrder($args = [])
    {
        $this->load->language('api/orders');

        $log = new Log('error.log');
        $log->write('getOrder');
        $log->write($args);

        //echo "cvrg";die;
        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('checkout/order');
            $this->load->model('account/order');

            $this->load->model('api/orders');

            $order = $this->model_checkout_order->getOrder($args['id']);

            if (isset($order)) {
                $order['store_details'] = $this->model_account_order->getStoreById($order['store_id']);

                /* totals */
                $data['totals'] = [];

                $totals = $this->model_account_order->getOrderTotals($args['id']);

                $data['newTotal'] = $this->currency->format(0);

                $data['total'] = 0;
                $order['subtotal'] = 0;
                $order['nice_subtotal'] = 0;
                //echo "<pre>";print_r($totals);die;
                foreach ($totals as $total) {
                    if ('sub_total' == $total['code']) {
                        $order['subtotal'] = $total['value'];
                        $order['nice_subtotal'] = $this->currency->format($order['subtotal'], $order['currency_code'], $order['currency_value']);
                    }

                    if ('total' == $total['code']) {
                        $temptotal = $total['value'];
                        $data['total'] = $total['value'];
                    }

                    /*$val = array('title' => $total['title'],'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']));

                    $data['totals'][] = [
                            $total['code'] => $val];



                    $data['plain_settlement_amount'] = $order_info['settlement_amount'];
                    if(isset($data['settlement_amount']) && isset($data['subtotal']) && isset($temptotal)) {

                        $data['newTotal'] = $this->currency->format($temptotal - $data['subtotal'] + $order_info['settlement_amount']);
                    }*/
                }
            }

            //echo "<pre>";print_r();die;

            $order['nice_total'] = $this->currency->format($order['total'], $order['currency_code'], $order['currency_value']);

            $order['products'] = [];

            //$products = $this->model_api_orders->getOrderProducts($args['id']);

            $realproducts = $this->model_account_order->hasRealOrderProducts($args['id']);

            if ($realproducts) {
                $products = $this->model_account_order->getRealOrderProducts($args['id']);
            } else {
                $products = $this->model_account_order->getOrderProducts($args['id']);
            }

            //echo "<pre>";print_r($products);die;

            if (!empty($products)) {
                foreach ($products as $product) {
                    $product['nice_total'] = $this->currency->format($product['total'], $order['currency_code'], $order['currency_value']);

                    $product['nice_price'] = $this->currency->format($product['price'], $order['currency_code'], $order['currency_value']);

                    $order['products'][] = $product;
                }
            }

            $order['histories'] = [];

            $this->load->model('sale/order');

            $results = $this->model_sale_order->getFullOrderHistoriesByOrderId($args['id']);

            foreach ($results as $result) {
                $order['histories'][] = [
                    'notify' => $result['notify'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
                    'status' => $result['status'],
                    'comment' => nl2br($result['comment']),
                    'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                ];
            }

            //echo "<pre>";print_r($order);die;

            $json = $order;
            $log->write($json);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addOrder($args = [])
    {
        $this->load->language('api/orders');

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('checkout/order');

            $json['order_id'] = $this->model_checkout_order->addOrder($args);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function editOrder($args = [])
    {
        $this->load->language('api/orders');

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('checkout/order');

            $this->model_checkout_order->editOrder($args['id'], $args);

            $json['success'] = $this->language->get('text_success');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function deleteOrder($args = [])
    {
        $this->load->language('api/orders');

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('checkout/order');

            $order_info = $this->model_checkout_order->getOrder($args['id']);

            if ($order_info) {
                $this->model_checkout_order->deleteOrder($args['id']);

                $json['success'] = $this->language->get('text_success');
            } else {
                $json['error'] = $this->language->get('error_not_found');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getOrders($args = [])
    {
        $this->load->language('api/orders');

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/orders');
            $this->load->model('account/order');

            $order_data = [];

            //$args['sort'] = 'o.delivery_date';

            if (isset($args['page'])) {
                $args['start'] = ($args['page'] - 1) * $this->config->get('config_limit_admin');
                $args['limit'] = $this->config->get('config_limit_admin');
            }

            $orderCount = 0;
            $orderValue = 0;

            $orderCountData = $this->model_api_orders->getTotalOrdersApi($args);

            $log = new Log('error.log');
            $log->write('getOrders');

            $orderCount = count($orderCountData);

            foreach ($orderCountData as $da) {
                $totals = $this->model_account_order->getOrderTotals($da['order_id']);

                foreach ($totals as $total) {
                    if ('sub_total' == $total['code']) {
                        $orderValue += $total['value'];
                    }
                }
                //$orderValue += $da['total'];
            }

            $results = $this->model_api_orders->getOrders($args);

            /*$log->write($results);

            //echo "<pre>";print_r($results);die;
            $temp = $results;

            $amTimeslot = [];
            $pmTimeslot = [];
            $inPmfirstTimeslot = [];

            foreach ($temp as $temp1) {

                $temp2 = explode('-', $temp1['delivery_timeslot']);


                if (strpos($temp2[0], 'am') !== false) {
                    array_push($amTimeslot, $temp1);
                } else {

                    if(substr($temp2[0], 0,2) == '12') {

                        array_push($inPmfirstTimeslot, $temp1);
                    } else {
                        array_push($pmTimeslot, $temp1);
                    }
                }

            }
            foreach ($inPmfirstTimeslot as $te) {

                array_push($amTimeslot, $te);
            }

            foreach ($pmTimeslot as $te) {

                array_push($amTimeslot, $te);
            }

            $results = $amTimeslot;

            $log->write($results);*/

            //echo "<pre>";print_r($results);die;
            if (!empty($results)) {
                $this->load->model('checkout/order');

                foreach ($results as $result) {
                    $order = $this->model_checkout_order->getOrder($result['order_id']);

                    $order['subtotal'] = 0;
                    $order['nice_subtotal'] = 0;

                    if (isset($order)) {
                        /* totals */
                        $data['totals'] = [];

                        $totals = $this->model_account_order->getOrderTotals($result['order_id']);

                        //echo "<pre>";print_r($totals);die;
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
                    $order['products_count'] = 0;

                    if (!empty($products)) {
                        foreach ($products as $product) {
                            $product['nice_total'] = $this->currency->format($product['total'], $order['currency_code'], $order['currency_value']);

                            $order['products_quantity'] += $product['quantity'];
                            $order['products_count'] +=1;
                            $order['products'][] = $product;
                        }
                    }

                    $order_data[] = $order;
                }
            }

            $json['orders'] = $order_data;
            $json['orders_count'] = $orderCount;
            $json['orders_value'] = $this->currency->format($orderValue);

            //$log->write($json);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getTotals($args = [])
    {
        $this->load->language('api/orders');

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/orders');

            $total = $this->model_api_orders->getTotals($args);

            $total['price'] = isset($total['price']) ? $total['price'] : '0';
            $total['nice_price'] = $this->currency->format($total['price']);
            $total['number'] = isset($total['number']) ? $total['number'] : '0';

            $json = $total;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getStatuses($args = [])
    {
        $this->load->language('api/orders');

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/orders');

            $statuses = [];
            $statuses[] = ['order_status_id' => '0', 'name' => $this->language->get('text_missing')];

            $rows = $this->model_api_orders->getStatuses();

            if (!empty($rows)) {
                foreach ($rows as $row) {
                    $statuses[] = ['order_status_id' => $row['order_status_id'], 'name' => $row['name']];
                }
            }

            $json = $statuses;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getReadyToPikcupStatuses($args = [])
    {
        $this->load->language('api/orders');

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/orders');

            $statuses = [];

            $ready_for_pickup_status_ids = $this->config->get('config_ready_for_pickup_status');

            //echo "<pre>";print_r($ready_for_pickup_status_ids);die;

            //$statuses[] = array('order_status_id' => '0', 'name' => $this->language->get('text_missing'));

            $rows = $this->model_api_orders->getOrderStatusesById(implode(',', $ready_for_pickup_status_ids));

            if (!empty($rows)) {
                foreach ($rows as $row) {
                    $statuses[] = ['order_status_id' => $row['order_status_id'], 'name' => $row['name']];
                }
            }

            $json = $statuses;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getProducts($args = [])
    {
        $this->load->language('api/orders');

        $json = [];

        //echo "<pre>";print_r($args);die;
        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/orders');
            $this->load->model('account/order');

            //$rows = $this->model_api_orders->getOrderProducts($args['id']);

            $realproducts = $this->model_account_order->hasRealOrderProducts($args['id']);

            if ($realproducts) {
                $rows = $this->model_account_order->getRealOrderProducts($args['id']);
            } else {
                $rows = $this->model_account_order->getOrderProducts($args['id']);
            }

            //echo "<pre>";print_r($rows);die;
            $order_products = [];

            if ($rows) {
                $this->load->model('tool/image');
                $this->load->model('tool/upload');
                $this->load->model('account/order');

                foreach ($rows as $row) {
                    $currency_value = false;

                    if (isset($args['currency_code'])) {
                        $currency_code = $args['currency_code'];
                    } elseif (isset($row['currency_code'])) {
                        $currency_code = $row['currency_code'];
                        $currency_value = $row['currency_value'];
                    } else {
                        $currency_code = $this->config->get('config_currency');
                    }

                    $row['name'] = html_entity_decode($row['name'], ENT_QUOTES, 'UTF-8');

                    $row['nice_price'] = $this->currency->format($row['price'], $currency_code, $currency_value);

                    $row['nice_total'] = $this->currency->format($row['total'], $currency_code, $currency_value);

                    $row['quantity'] = intval($row['quantity']);

                    $order_product_options = $this->model_account_order->getOrderOptions($args['id'], $row['order_product_id']);

                    $option_data = [];
                    foreach ($order_product_options as $option) {
                        if ('file' != $option['type']) {
                            $value = $option['value'];
                        } else {
                            $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);
                            if ($upload_info) {
                                $value = $option['value'];
                            } else {
                                $value = '';
                            }
                        }

                        $option_data[] = [$option['name'] => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 26).'..' : $value)];
                    }

                    $row['options'] = $option_data;

                    if (empty($args['skip_images'])) {
                        $thumb_width = $this->config->get('config_image_thumb_width', 300);
                        $thumb_height = $this->config->get('config_image_thumb_height', 300);

                        $thumb_zoomwidth = $this->config->get('config_zoomimage_thumb_width', 600);
                        $thumb_zoomheight = $this->config->get('config_zoomimage_thumb_height', 600);

                        $tmpImg = $row['image'];
                        if (!empty($row['image'])) {
                            $row['image'] = $this->model_tool_image->resize($row['image'], $thumb_width, $thumb_height);

                            $row['zoom_image'] = $this->model_tool_image->resize($tmpImg, $thumb_zoomwidth, $thumb_zoomheight);
                        } else {
                            $row['image'] = $this->model_tool_image->resize('placeholder.png', $thumb_width, $thumb_height);

                            $row['zoom_image'] = $this->model_tool_image->resize('placeholder.png', $thumb_zoomwidth, $thumb_zoomheight);
                        }

                        if ($this->request->server['HTTPS']) {
                            $row['image'] = str_replace($this->config->get('config_ssl'), '', $row['image']);

                            $row['zoom_image'] = str_replace($this->config->get('config_ssl'), '', $row['zoom_image']);
                        } else {
                            $row['image'] = str_replace($this->config->get('config_url'), '', $row['image']);
                            $row['zoom_image'] = str_replace($this->config->get('config_url'), '', $row['zoom_image']);
                        }
                    }

                    $order_products[] = $row;
                }
            }

            $json = $order_products;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getHistories($args = [])
    {
        $this->load->language('api/orders');

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/orders');

            $json = $this->model_api_orders->getOrderHistories($args['id']);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addHistory($args = [])
    {
        $this->load->language('api/orders');

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('checkout/order');

            // Add keys for missing post vars
            $keys = [
                'notify',
                'comment',
            ];

            foreach ($keys as $key) {
                if (!isset($args[$key])) {
                    $args[$key] = '';
                }
            }

            $order_info = $this->model_checkout_order->getOrder($args['id']);

            if ($order_info) {
                $this->model_checkout_order->addOrderHistory($args['id'], $args['status'], $args['comment'], $args['notify']);

                $json['success'] = $this->language->get('text_success');
            } else {
                $json['error'] = $this->language->get('error_not_found');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    /*** API for third party delivery system to get [Ready to Pickup] Orders **/
    public function getOrdersForDelivery($args = [])
    {
        $this->load->language('api/orders');

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/orders');
            $this->load->model('account/order');

            $order_data = [];

            if (isset($args['page'])) {
                $args['start'] = ($args['page'] - 1) * $this->config->get('config_limit_admin');
                $args['limit'] = $this->config->get('config_limit_admin');
            }

            $orderCount = 0;
            $orderValue = 0;

            $orderCountData = $this->model_api_orders->getTotalOrdersApi($args);

            $log = new Log('error.log');
            $log->write('getOrders');

            $orderCount = count($orderCountData);

            foreach ($orderCountData as $da) {
                $totals = $this->model_account_order->getOrderTotals($da['order_id']);

                foreach ($totals as $total) {
                    if ('sub_total' == $total['code']) {
                        $orderValue += $total['value'];
                    }
                }
                //$orderValue += $da['total'];
            }

            $args['status'] = DELEVERY_GENERATE_STATUS;
            $args['filter_pickup'] = 1;
            $results = $this->model_api_orders->getOrders($args);

            if (!empty($results)) {
                $this->load->model('checkout/order');

                foreach ($results as $result) {
                    $order = $this->model_checkout_order->getOrder($result['order_id']);

                    $order['subtotal'] = 0;
                    $order['nice_subtotal'] = 0;

                    if (isset($order)) {
                        /* totals */
                        $data['totals'] = [];

                        $totals = $this->model_account_order->getOrderTotals($result['order_id']);

                        //echo "<pre>";print_r($totals);die;
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
                }
            }

            $json['orders'] = $order_data;
            $json['msg'] = 'Orders List fetched!';
            $json['status'] = 200;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    //API to get all Orders --> Admin users
    //Copy getOrders
    public function getAllOrders($args = [])
    {
        $this->load->language('api/orders');

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/orders');
            $this->load->model('account/order');

            $order_data = [];

            //$args['sort'] = 'o.delivery_date';

            if (isset($args['page'])) {
                $args['start'] = ($args['page'] - 1) * $this->config->get('config_limit_admin');
                $args['limit'] = $this->config->get('config_limit_admin');
            }

            $orderCount = 0;
            $orderValue = 0;

            $orderCountData = $this->model_api_orders->getTotalOrdersApi($args);

            $log = new Log('error.log');
            $log->write('getOrders');

            $orderCount = count($orderCountData);

            foreach ($orderCountData as $da) {
                $totals = $this->model_account_order->getOrderTotals($da['order_id']);

                foreach ($totals as $total) {
                    if ('sub_total' == $total['code']) {
                        $orderValue += $total['value'];
                    }
                }
                //$orderValue += $da['total'];
            }

            $results = $this->model_api_orders->getOrdersNew($args);
            // echo "<pre>";print_r($results);die;

            /*$log->write($results);

             
            $temp = $results;

            $amTimeslot = [];
            $pmTimeslot = [];
            $inPmfirstTimeslot = [];

            foreach ($temp as $temp1) {

                $temp2 = explode('-', $temp1['delivery_timeslot']);


                if (strpos($temp2[0], 'am') !== false) {
                    array_push($amTimeslot, $temp1);
                } else {

                    if(substr($temp2[0], 0,2) == '12') {

                        array_push($inPmfirstTimeslot, $temp1);
                    } else {
                        array_push($pmTimeslot, $temp1);
                    }
                }

            }
            foreach ($inPmfirstTimeslot as $te) {

                array_push($amTimeslot, $te);
            }

            foreach ($pmTimeslot as $te) {

                array_push($amTimeslot, $te);
            }

            $results = $amTimeslot;

            $log->write($results);*/

            //echo "<pre>";print_r($results);die;
            if (!empty($results)) {
                $this->load->model('checkout/order');

                foreach ($results as $result) {
                    $order = $this->model_checkout_order->getOrder($result['order_id']);

                    $order['subtotal'] = 0;
                    $order['nice_subtotal'] = 0;
                    $order['company_name'] = $result['company_name'];

                    if (isset($order)) {
                        /* totals */
                        $data['totals'] = [];

                        $totals = $this->model_account_order->getOrderTotals($result['order_id']);

                        //echo "<pre>";print_r($totals);die;
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
                }
            }

            $json['orders'] = $order_data;
            $json['orders_count'] = $orderCount;
            $json['pages_count'] = ceil($orderCount/$this->config->get('config_limit_admin'));
            $json['orders_value'] = $this->currency->format($orderValue);

            //$log->write($json);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getOrdersAndRealOrders($args = [])
    {
        $this->load->language('api/orders');

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/orders');
            $this->load->model('account/order');

            $order_data = [];

            //$args['sort'] = 'o.delivery_date';

            if (isset($args['page'])) {
                $args['start'] = ($args['page'] - 1) * $this->config->get('config_limit_admin');
                $args['limit'] = $this->config->get('config_limit_admin');
            }

            $orderCount = 0;
            $orderValue = 0;

            $orderCountData = $this->model_api_orders->getTotalOrdersApi($args);

            $log = new Log('error.log');
            $log->write('getOrders');

            $orderCount = count($orderCountData);

            foreach ($orderCountData as $da) {
                $totals = $this->model_account_order->getOrderTotals($da['order_id']);

                foreach ($totals as $total) {
                    if ('sub_total' == $total['code']) {
                        $orderValue += $total['value'];
                    }
                }
                //$orderValue += $da['total'];
            }

            $results = $this->model_api_orders->getOrders($args);

            /*$log->write($results);

            //echo "<pre>";print_r($results);die;
            $temp = $results;

            $amTimeslot = [];
            $pmTimeslot = [];
            $inPmfirstTimeslot = [];

            foreach ($temp as $temp1) {

                $temp2 = explode('-', $temp1['delivery_timeslot']);


                if (strpos($temp2[0], 'am') !== false) {
                    array_push($amTimeslot, $temp1);
                } else {

                    if(substr($temp2[0], 0,2) == '12') {

                        array_push($inPmfirstTimeslot, $temp1);
                    } else {
                        array_push($pmTimeslot, $temp1);
                    }
                }

            }
            foreach ($inPmfirstTimeslot as $te) {

                array_push($amTimeslot, $te);
            }

            foreach ($pmTimeslot as $te) {

                array_push($amTimeslot, $te);
            }

            $results = $amTimeslot;

            $log->write($results);*/

            //echo "<pre>";print_r($results);die;
            if (!empty($results)) {
                $this->load->model('checkout/order');

                foreach ($results as $result) {
                    $order = $this->model_checkout_order->getOrder($result['order_id']);

                    $order['subtotal'] = 0;
                    $order['nice_subtotal'] = 0;

                    if (isset($order)) {
                        /* totals */
                        $data['totals'] = [];

                        $totals = $this->model_account_order->getOrderTotals($result['order_id']);

                        //echo "<pre>";print_r($totals);die;
                        foreach ($totals as $total) {
                            if ('sub_total' == $total['code']) {
                                $order['subtotal'] = $total['value'];
                                $order['nice_subtotal'] = $this->currency->format($order['subtotal'], $order['currency_code'], $order['currency_value']);
                            }
                        }
                    }

                    $order['nice_total'] = $this->currency->format($order['total'], $order['currency_code'], $order['currency_value']);

                    $order['products'] = [];
                    $order['real_products'] = [];

                    $products = $this->model_account_order->getOrderProducts($result['order_id']);
                    $real_products = $this->model_account_order->getRealOrderProducts($result['order_id']);

                    $order['products_quantity'] = 0;
                    $order['products_count'] = 0;
                    $order['real_products_quantity'] = 0;
                    $order['real_products_count'] = 0;

                    if (!empty($products)) {
                        foreach ($products as $product) {
                            $product['nice_total'] = $this->currency->format($product['total'], $order['currency_code'], $order['currency_value']);

                            $order['products_quantity'] += $product['quantity'];
                            $order['products_count'] +=1;
                            $order['products'][] = $product;
                        }
                    }

                    if (!empty($real_products)) {
                        foreach ($real_products as $real_product) {
                            $real_product['nice_total'] = $this->currency->format($real_product['total'], $order['currency_code'], $order['currency_value']);

                            $order['real_products_quantity'] += $real_product['quantity'];
                            $order['real_products_count'] +=1;
                            $order['real_products'][] = $real_product;
                        }
                    }

                    $order_data[] = $order;
                }
            }

            $json['orders'] = $order_data;
            $json['orders_count'] = $orderCount;
            $json['orders_value'] = $this->currency->format($orderValue);

            //$log->write($json);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function export_products_excel($args = []) {
        $data = [];

        $orderid = $this->request->get['order_id'];

        $this->load->model('account/order');
        if(($this->customer->getId()==NULL || $this->customer->getId()==""))
        {
            $json['error'] =  "Please login again";
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
            return;
        }
        $isValidOrder=$this->model_account_order->checkValidOrder($orderid,$this->customer->getId());

        if($isValidOrder=="false")
        {
            $json['error'] =  "Invalid Order";
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
            return;
        }
        $order_info = $this->model_account_order->getOrder($orderid);
        //   echo "<pre>";print_r($order_info);die;

        $customer_id = $order_info['customer_id'];
        
        $customer = $order_info['firstname'] . ' ' . $order_info['lastname'];
        $company = $this->model_account_order->getCompanyName($customer_id);;
        $date = $order_info['date_added'];
        $deliverydate = $order_info['delivery_date'];
        $shippingaddress = $order_info['shipping_address'] . ' . ' . $order_info['shipping_city'] . ' ' . $order_info['zipcode'];
        $paymentmethod = $order_info['payment_method'];

        //   echo "<pre>";print_r($company);die;



        $data['consolidation'][] = [
            'orderid' => $orderid,
            'customer' => $customer,
            'company' => $company,
            'date' => $date,
            'deliverydate' => $deliverydate,
            'shippingaddress' => $shippingaddress,
            'paymentmethod' => $paymentmethod,
        ];

        $orderProducts = $this->getOrderProductsWithVariancesNew($orderid);
        $data['products'] = $orderProducts;

        // echo "<pre>";print_r($orderProducts);die;
        // $sum = 0;
        // foreach ($orderProducts as $item) {
        //     $sum += $item['total_updatedvalue'];
        // } 


        $this->load->model('account/order');
        $this->model_account_order->download_products_excel($data);
    }

    public function getOrderProductsWithVariancesNew($order_id) {

        $this->load->model('account/order');
        $orderProducts = [];


        if ($this->model_account_order->hasRealOrderProduct($order_id)) {
            // Order products with weight change
            $originalProducts = $products = $this->model_account_order->getOnlyRealOrderProducts($order_id);
        } else {

            // Products as the user ordered them on the platform
            $originalProducts = $products = $this->model_account_order->getOnlyOrderProducts($order_id);
        }

        //echo "<pre>";print_r($originalProducts);die;

        foreach ($originalProducts as $originalProduct) {
            // $totalUpdated = $originalProduct['price'] * $originalProduct['quantity']
            //     + ($this->config->get('config_tax') ? $originalProduct['tax'] : 0);
            //in admin orders screen, directly showing total
            $totalUpdated = $originalProduct['total'];

            $uomOrderedWithoutApproximations = trim(explode('(', $originalProduct['unit'])[0]);

            $orderProducts[] = [
                'order_product_id' => $originalProduct['order_product_id'],
                'product_id' => $originalProduct['product_id'],
                'vendor_id' => $originalProduct['vendor_id'],
                'store_id' => $originalProduct['store_id'],
                'name' => $originalProduct['name'],
                'unit' => $uomOrderedWithoutApproximations,
                'product_type' => $originalProduct['product_type'],
                'model' => $originalProduct['model'],
                'quantity' => $originalProduct['quantity'],
                'quantity_updated' => $originalProduct['quantity'],
                'unit_updated' => $uomOrderedWithoutApproximations,
                'price' => $this->currency->format($originalProduct['price'] + ($this->config->get('config_tax') ? $originalProduct['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                'total' => $this->currency->format($originalProduct['total'] + ($this->config->get('config_tax') ? ($originalProduct['tax'] * $originalProduct['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
                'total_updated' => $this->currency->format($totalUpdated, $order_info['currency_code'], $order_info['currency_value']),
                'total_updated_currency' => trim(explode(' ', $this->currency->format($totalUpdated, $order_info['currency_code'], $order_info['currency_value']))[0]),
                'total_updated_value' => trim(explode(' ', $this->currency->format($totalUpdated, $order_info['currency_code'], $order_info['currency_value']))[1]),
                'total_updatedvalue' => $totalUpdated,
            ];
        }

        return $orderProducts;
    }

}
