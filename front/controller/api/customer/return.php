<?php

require_once DIR_SYSTEM.'/vendor/konduto/vendor/autoload.php';

require_once DIR_SYSTEM.'/vendor/fcp-php/autoload.php';

require DIR_SYSTEM.'vendor/Facebook/autoload.php';

require_once DIR_APPLICATION.'/controller/api/settings.php';

class ControllerApiCustomerReturn extends Controller
{
    private $error = [];

    public function getUserReturns()
    {
        $json = [];

        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');
        $this->load->model('account/customer');
        $this->load->model('account/order');
        //if( $this->customer->isLogged()) {
        if (true) {
            $this->load->language('account/return');
            $this->load->model('account/return');

            if (isset($this->request->get['page'])) {
                $page = $this->request->get['page'];
            } else {
                $page = 1;
            }

            $data['returns'] = [];

            $return_total = $this->model_account_return->getTotalReturns();

            $results = $this->model_account_return->getReturns(($page - 1) * 10, 10);

            foreach ($results as $result) {
                $order_info = $this->model_account_order->getOrder($result['order_id']);

                $store_name = '';
                if (isset($order_info['store_name'])) {
                    $store_name = htmlspecialchars_decode($order_info['store_name']);
                }

                $data['returns'][] = [
                    'return_id' => $result['return_id'],
                    'store_name' => htmlspecialchars_decode($store_name),
                    'order_id' => $result['order_id'],
                    'name' => $result['firstname'].' '.$result['lastname'],
                    'return_status_id' => $result['return_status_id'],
                    'status' => $result['status'],
                    //'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                    'date_added' => date($this->language->get('date_format'), strtotime($result['date_added'])),
                    'href' => $this->url->link('account/return/info', 'return_id='.$result['return_id'], 'SSL'),
                ];
            }

            $data['results'] = sprintf($this->language->get('text_pagination'), ($return_total) ? (($page - 1) * $this->config->get('config_product_limit')) + 1 : 0, ((($page - 1) * $this->config->get('config_product_limit')) > ($return_total - $this->config->get('config_product_limit'))) ? $return_total : ((($page - 1) * $this->config->get('config_product_limit')) + $this->config->get('config_product_limit')), $return_total, ceil($return_total / $this->config->get('config_product_limit')));

            $data['return_total'] = $return_total;

            $json['data'] = $data;
        } else {
            $json['status'] = 10013;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_not_loggedin')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getReturnDetail()
    {
        $json = [];

        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');
        $this->load->model('account/customer');

        //if( $this->customer->isLogged()) {
        if (isset($this->request->get['return_id'])) {
            $this->load->language('account/return');
            $this->load->model('account/return');

            $return_id = $this->request->get['return_id'];

            $return_info = $this->model_account_return->getReturn($return_id);

            $this->load->model('account/order');
            //echo "<pre>";print_r($return_info);die;

            if ($return_info) {
                $data['order_info'] = $this->model_account_order->getOrder($return_info['order_id']);

                if (isset($data['order_info']['store_name'])) {
                    $data['order_info']['store_name'] = htmlspecialchars_decode($data['order_info']['store_name']);
                }

                //echo "<pre>";print_r($order_info);die;

                $data['return_id'] = $return_info['return_id'];
                $data['return_status_id'] = $return_info['return_status_id'];
                $data['return_action_id'] = $return_info['return_action_id'];
                $data['return_reason_id'] = $return_info['return_reason_id'];
                $data['product_id'] = $return_info['product_id'];
                $data['order_id'] = $return_info['order_id'];
                $data['date_ordered'] = date($this->language->get('date_format'), strtotime($return_info['date_ordered']));
                $data['date_added'] = date($this->language->get('date_format'), strtotime($return_info['date_added']));
                $data['firstname'] = $return_info['firstname'];
                $data['lastname'] = $return_info['lastname'];
                $data['email'] = $return_info['email'];
                $data['telephone'] = $return_info['telephone'];
                $data['product'] = $return_info['product'];
                $data['unit'] = $return_info['unit'];
                $data['price'] = $return_info['price'];
                $data['total_price'] = ($return_info['price'] * $return_info['quantity']);
                $data['model'] = $return_info['model'];
                $data['quantity'] = $return_info['quantity'];
                $data['reason'] = $return_info['reason'];
                $data['opened'] = $return_info['opened'] ? $this->language->get('text_yes') : $this->language->get('text_no');
                $data['comment'] = nl2br($return_info['comment']);
                $data['action'] = $return_info['action'];

                $data['histories'] = [];

                //echo "<pre>";print_r($data['product_id']);die;
                $product_details = $this->model_account_return->getProduct($data['order_id'], $data['product_id']);

                //echo "<pre>";print_r($product_details);die;
                $this->load->model('tool/image');

                if (count($product_details) > 0 && file_exists(DIR_IMAGE.$product_details['image'])) {
                    $data['image'] = $this->model_tool_image->resize($product_details['image'], 80, 100);
                } else {
                    $data['image'] = $this->model_tool_image->resize('placeholder.png', 80, 100);
                }
                //echo "<pre>";print_r($product_details);die;

                $results = $this->model_account_return->getReturnHistories($this->request->get['return_id']);

                foreach ($results as $result) {
                    $data['histories'][] = [
                        'date_added' => date($this->language->get('date_format'), strtotime($result['date_added'])),
                        'status' => $result['status'],
                        'comment' => nl2br($result['comment']),
                    ];
                }

                $this->load->model('localisation/return_reason');
                $this->load->model('api/return');

                $data['return_reasons'] = $this->model_localisation_return_reason->getReturnReasons();

                $data['return_actions'] = $this->model_api_return->getReturnActions();

                $data['return_statuses'] = $this->model_api_return->getReturnStatuses();

                $json['data'] = $data;
            } else {
                $json['status'] = 10026;

                $json['message'][] = ['type' => '', 'body' => $this->language->get('text_return_not_found')];

                http_response_code(400);
            }
        } else {
            $json['status'] = 10013;

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_not_loggedin')];

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addReturnProduct()
    {
        $json = [];

        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');
        $this->load->model('account/customer');

        $log = new Log('error.log');
        $log->write('addReturnProduct');
        $log->write($this->request->post);

        //if( $this->customer->isLogged()) {
        if (true && $this->validate()) {
            $this->load->language('account/return');
            $this->load->model('account/return');

            $this->load->model('account/customer');

            $order_id = $this->request->post['order_id'];
            $product_id = $this->request->post['product_id'];
            $quantity = $this->request->post['quantity'];

            $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

            //fnam,lname,email,telephone,o_id,order date,product name,unit,model,qty,reason for ret,opened,fault comment
            $data = $customer_info;

            $this->load->model('account/order');

            $order_info = $this->model_account_order->getOrder($order_id);

            $data['date_ordered'] = $order_info['order_date'];
            $data['order_id'] = $order_id;
            $data['product_id'] = $product_id;

            $realproducts = $this->model_account_order->hasRealOrderProducts($order_id);

            if ($realproducts) {
                $product_details = $this->model_account_return->getRealProduct($data['order_id'], $data['product_id']);
            } else {
                $product_details = $this->model_account_return->getProduct($data['order_id'], $data['product_id']);
            }

            $data['price'] = $product_details['price'];

            $data['product'] = $product_details['name'];
            $data['unit'] = $product_details['unit'];
            $data['model'] = $product_details['model'];
            $data['quantity'] = $quantity;

            $data['return_reason_id'] = $this->request->post['return_reason_id'];
            $data['comment'] = $this->request->post['comment'];
            $data['opened'] = $this->request->post['opened'];

            //echo "<pre>";print_r($data);die;
            $return_id = $this->model_account_return->addReturn($data);

            // Add to activity log
            $this->load->model('account/activity');

            if ($this->customer->isLogged()) {
                $activity_data = [
                    'customer_id' => $this->customer->getId(),
                    'name' => $this->customer->getFirstName().' '.$this->customer->getLastName(),
                    'return_id' => $return_id,
                ];

                $this->model_account_activity->addActivity('return_account', $activity_data);
            } else {
                $activity_data = [
                    'name' => $this->request->post['firstname'].' '.$this->request->post['lastname'],
                    'return_id' => $return_id,
                ];

                $this->model_account_activity->addActivity('return_guest', $activity_data);
            }

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_return_submited')];

        //$json['data'] = $data;
        } else {
            $json['status'] = 10014;

            foreach ($this->error as $key => $value) {
                $json['message'][] = ['type' => $key, 'body' => $value];
            }

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addReturnProductMultiple()
    {
        $json = [];

        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');
        $this->load->model('account/customer');

        $log = new Log('error.log');
        $log->write('addReturnProduct');
        $log->write($this->request->post);

        //if( $this->customer->isLogged()) {
        if (true && $this->validate()) {
            $this->load->language('account/return');
            $this->load->model('account/return');

            $this->load->model('account/customer');

            $order_id = $this->request->post['order_id'];
            $products = $this->request->post['product_id'];
            //$product_id = $this->request->post['product_id'];
            //$quantity = $this->request->post['quantity'];
            $quantities = $this->request->post['quantity'];

            $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

            //fnam,lname,email,telephone,o_id,order date,product name,unit,model,qty,reason for ret,opened,fault comment

            $this->load->model('account/order');

            $order_info = $this->model_account_order->getOrder($order_id);

            $realproducts = $this->model_account_order->hasRealOrderProducts($order_id);
            if (count($products) > 0) {
                foreach ($products as $keyproduct => $productvalue) {
                    if ($realproducts) {
                        $product_details = $this->model_account_return->getRealProduct($data['order_id'], $productvalue);
                    } else {
                        $product_details = $this->model_account_return->getProduct($data['order_id'], $productvalue);
                    }

                    $data = $customer_info;
                    $data['date_ordered'] = $order_info['order_date'];
                    $data['order_id'] = $order_id;
                    $data['product_id'] = $productvalue;
                    $data['price'] = $product_details['price'];
                    $data['product'] = $product_details['name'];
                    $data['unit'] = $product_details['unit'];
                    $data['model'] = $product_details['model'];
                    $data['quantity'] = $quantities[$keyproduct];
                    $data['return_reason_id'] = $this->request->post['return_reason_id'];
                    $data['comment'] = $this->request->post['comment'];
                    $data['opened'] = $this->request->post['opened'];
                    $data['customer_desired_action'] = $this->request->post['customer_desired_action'];

                    //echo "<pre>";print_r($data);//die;
                    $return_id = $this->model_account_return->addReturn($data);

                    // Add to activity log
                    $this->load->model('account/activity');

                    if ($this->customer->isLogged()) {
                        $activity_data = [
                            'customer_id' => $this->customer->getId(),
                            'name' => $this->customer->getFirstName().' '.$this->customer->getLastName(),
                            'return_id' => $return_id,
                        ];

                        $this->model_account_activity->addActivity('return_account', $activity_data);
                    } else {
                        $activity_data = [
                            'name' => $this->request->post['firstname'].' '.$this->request->post['lastname'],
                            'return_id' => $return_id,
                        ];

                        $this->model_account_activity->addActivity('return_guest', $activity_data);
                    }
                }
            }

            //echo "<pre>";print_r($data);die;
            // $return_id = $this->model_account_return->addReturn($data);

            $json['message'][] = ['type' => '', 'body' => $this->language->get('text_return_submited')];

        //$json['data'] = $data;
        } else {
            $json['status'] = 10014;

            foreach ($this->error as $key => $value) {
                $json['message'][] = ['type' => $key, 'body' => $value];
            }

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addAcceptDelivery()
    {
        $json = [];

        $this->load->language('information/locations');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->load->language('api/general');
        $this->load->model('account/customer');

        $log = new Log('error.log');
        $log->write('addReturnProduct');
        $log->write($this->request->post);

        //if( $this->customer->isLogged()) {
        if (true && $this->validateAcceptKeys()) {
            $this->load->language('account/return');
            $this->load->model('account/return');

            $this->load->model('account/customer');

            $order_id = $this->request->post['order_id'];
            $products = $this->request->post['product_id'];
            //$product_id = $this->request->post['product_id'];
            //$quantity = $this->request->post['quantity'];
            $actions = $this->request->post['action'];
            $actions_note = $this->request->post['action_note'];

            //$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

            //fnam,lname,email,telephone,o_id,order date,product name,unit,model,qty,reason for ret,opened,fault comment

            $this->load->model('account/order');

            //$order_info = $this->model_account_order->getOrder($order_id);

            //$realproducts = $this->model_account_order->hasRealOrderProducts($order_id);
            if (count($products) > 0) {
                $return_replace_count = 0;
                foreach ($products as $keyproduct => $productvalue) {
                    $product_id = $productvalue;
                    $action = $actions[$keyproduct];
                    if ('return' == $action || 'replace' == $action) {
                        ++$return_replace_count;
                    }
                    $action_note = $actions_note[$keyproduct];
                    $this->db->query('UPDATE `'.DB_PREFIX."order_product` SET 	on_delivery_action = '".$action."', delivery_action_note = '".$action_note."' WHERE order_id = '".(int) $order_id."' AND product_id = '".(int) $product_id."'");
                }

                if ($return_replace_count > 0) {
                    $orderStatus = 'Partially Delivered';
                } else {
                    $orderStatus = 'Delivered';
                }

                $sql = 'SELECT order_status_id FROM '.DB_PREFIX."order_status WHERE language_id = '".(int) $this->config->get('config_language_id')."' AND name='".$orderStatus."'";
                $query = $this->db->query($sql);
                $order_status_id = $query->row['order_status_id'];
                //echo "Order_status_id "+$order_status_id;
                $comment = 'Automatic status change on Accept Delivery';
                $this->db->query('UPDATE `'.DB_PREFIX."order` SET order_status_id = '".(int) $order_status_id."', date_modified = NOW() WHERE order_id = '".(int) $order_id."'");
                $this->db->query('INSERT INTO `'.DB_PREFIX."order_history` SET order_id = '".(int) $order_id."', comment = '".$this->db->escape($comment)."', date_added = NOW()");
            }

            $json['message'][] = ['type' => '', 'body' => 'Delivery submission completed!'];
        } else {
            $json['status'] = 10014;

            foreach ($this->error as $key => $value) {
                $json['message'][] = ['type' => $key, 'body' => $value];
            }

            http_response_code(400);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validateAcceptKeys()
    {
        if (!isset($this->request->post['order_id'])) {
            $this->error['order_id'] = $this->language->get('error_order_id');
        }

        if (!isset($this->request->post['product_id'])) {
            $this->error['product_id'] = $this->language->get('error_product');
        }

        if (!isset($this->request->post['action'])) {
            $this->error['action'] = $this->language->get('error_action');
        }

        if (empty($this->request->post['action_note'])) {
            $this->error['action_note'] = $this->language->get('error_action_note');
        }

        /*if ($this->config->get('config_google_captcha_status')) {
            $json = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($this->config->get('config_google_captcha_secret')) . '&response=' . $this->request->post['g-recaptcha-response'] . '&remoteip=' . $this->request->server['REMOTE_ADDR']);

            $json = json_decode($json, true);

            if (!$json['success']) {
                $this->error['captcha'] = $this->language->get('error_captcha');
            }
        }
        */

        if ($this->error) {
            $this->error['warning'] = 'Plase check the form carefully!';
        }

        return !$this->error;
    }

    protected function validate()
    {
        if (!isset($this->request->post['order_id'])) {
            $this->error['order_id'] = $this->language->get('error_order_id');
        }

        if (!isset($this->request->post['product_id'])) {
            $this->error['product_id'] = $this->language->get('error_product');
        }

        if (!isset($this->request->post['quantity'])) {
            $this->error['quantity'] = $this->language->get('error_quantity');
        }

        if (empty($this->request->post['return_reason_id'])) {
            $this->error['reason'] = $this->language->get('error_reason');
        }

        /*if ($this->config->get('config_google_captcha_status')) {
            $json = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($this->config->get('config_google_captcha_secret')) . '&response=' . $this->request->post['g-recaptcha-response'] . '&remoteip=' . $this->request->server['REMOTE_ADDR']);

            $json = json_decode($json, true);

            if (!$json['success']) {
                $this->error['captcha'] = $this->language->get('error_captcha');
            }
        }
        */

        if ($this->error) {
            $this->error['warning'] = 'Plase check the form carefully!';
        }

        return !$this->error;
    }
}
