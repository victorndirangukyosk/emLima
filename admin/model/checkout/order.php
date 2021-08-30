<?php

class ModelCheckoutOrder extends Model
{
    public function getOrder($order_id)
    {
        $order_query = $this->db->query('SELECT *, (SELECT os.name FROM `'.DB_PREFIX.'order_status` os WHERE os.order_status_id = o.order_status_id AND os.language_id = o.language_id) AS order_status FROM `'.DB_PREFIX."order` o WHERE o.order_id = '".(int) $order_id."'");

        if ($order_query->num_rows) {
            $this->load->model('localisation/language');
            $this->load->model('account/order');

            $language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);

            $city_name = $this->model_account_order->getCityName($order_query->row['shipping_city_id']);

            if ($language_info) {
                $language_code = $language_info['code'];
                $language_directory = $language_info['directory'];
            } else {
                $language_code = '';
                $language_directory = '';
            }

            return [
                'order_id' => $order_query->row['order_id'],
                'invoice_no' => $order_query->row['invoice_no'],
                'invoice_prefix' => $order_query->row['invoice_prefix'],
                'store_id' => $order_query->row['store_id'],
                'store_name' => $order_query->row['store_name'],
                'store_url' => $order_query->row['store_url'],
                'customer_id' => $order_query->row['customer_id'],
                'firstname' => $order_query->row['firstname'],
                'lastname' => $order_query->row['lastname'],
                'email' => $order_query->row['email'],
                'telephone' => $order_query->row['telephone'],
                'fax' => $order_query->row['fax'],
                'custom_field' => unserialize($order_query->row['custom_field']),
                'shipping_name' => $order_query->row['shipping_name'],
                'shipping_address' => $order_query->row['shipping_address'],
                'shipping_city' => $city_name,
                'shipping_contact_no' => $order_query->row['shipping_contact_no'],
                'shipping_method' => $order_query->row['shipping_method'],
                'shipping_zipcode' => $order_query->row['shipping_zipcode'],
                'shipping_code' => $order_query->row['shipping_code'],

                'shipping_flat_number' => $order_query->row['shipping_flat_number'],
                'shipping_building_name' => $order_query->row['shipping_building_name'],
                'shipping_landmark' => $order_query->row['shipping_landmark'],
                'driver_id' => $order_query->row['driver_id'],
                'vehicle_number' => $order_query->row['vehicle_number'],
                'delivery_executive_id' => $order_query->row['delivery_executive_id'],

                'payment_method' => $order_query->row['payment_method'],
                'payment_code' => $order_query->row['payment_code'],
                'comment' => $order_query->row['comment'],
                'total' => $order_query->row['total'],
                'order_status_id' => $order_query->row['order_status_id'],
                'order_status' => $order_query->row['order_status'],
                'affiliate_id' => $order_query->row['affiliate_id'],
                'commission' => $order_query->row['commission'],
                'language_id' => $order_query->row['language_id'],
                'language_code' => $language_code,
                'language_directory' => $language_directory,
                'currency_id' => $order_query->row['currency_id'],
                'currency_code' => $order_query->row['currency_code'],
                'currency_value' => $order_query->row['currency_value'],
                'order_pdf_link' => $order_query->row['order_pdf_link'],
                'ip' => $order_query->row['ip'],
                'forwarded_ip' => $order_query->row['forwarded_ip'],
                'user_agent' => $order_query->row['user_agent'],
                'accept_language' => $order_query->row['accept_language'],
                'date_modified' => $order_query->row['date_modified'],
                'date_added' => $order_query->row['date_added'],

                'delivery_date' => $order_query->row['delivery_date'],
                'delivery_timeslot' => $order_query->row['delivery_timeslot'],
                'delivery_charges' => $order_query->row['delivery_charges'],
                /*'date_modified' => $order_query->row['date_modified'],
                'date_added' => $order_query->row['date_added']*/
            ];
        } else {
            return false;
        }
    }

    public function addOrderHistory($order_id, $order_status_id, $comment = '', $notify = true)
    {
        $this->trigger->fire('pre.order.history.add', $order_id);

        $order_info = $this->getOrder($order_id);

        $pdf_link = '';

        if (isset($this->session->data['result_iugu_pdf_link'])) {
            $pdf_link = $this->session->data['result_iugu_pdf_link'];
        }

        $log = new Log('error.log');
        $log->write('addOrderHistory 1');
        $log->write($order_status_id);

        if ($order_info) {
            // Fraud Detection
            $this->load->model('account/customer');

            $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);

            if ($customer_info && $customer_info['safe']) {
                $safe = true;
            } else {
                $safe = false;
            }

            if ($this->config->get('config_fraud_detection')) {
                $this->load->model('checkout/fraud');

                $risk_score = $this->model_checkout_fraud->getFraudScore($order_info);

                if (!$safe && $risk_score > $this->config->get('config_fraud_score')) {
                    $order_status_id = $this->config->get('config_fraud_status_id');
                }
            }
            $log->write('addOrderHistory 1');
            $log->write($order_status_id);
            $log->write($safe);
            // Ban IP
            if (!$safe) {
                $log->write('addOrderHistory 1.3');
                $status = false;

                if ($order_info['customer_id']) {
                    $results = $this->model_account_customer->getIps($order_info['customer_id']);

                    foreach ($results as $result) {
                        if ($this->model_account_customer->isBanIp($result['ip'])) {
                            $status = true;

                            break;
                        }
                    }
                } else {
                    $status = $this->model_account_customer->isBanIp($order_info['ip']);
                }

                if ($status) {
                    $order_status_id = $this->config->get('config_order_status_id');
                }
            }

            $log->write('addOrderHistory here in admin/model/checkout/order');

            //$this->createDeliveryRequest($order_id,$order_status_id);
            //delivery request
            /*if($order_status_id == 1) {

                $data['products']['products'] = [];

                $products = $this->model_account_order->getOrderProducts($order_id);


                //echo "<pre>";print_r($products);die;

                $log = new Log('error.log');

                $log->write('tester log' );
                $log->write($order_info);

                foreach ( $products as $product ) {

                    $replacable = 'no';

                    if($product['product_type'] == 'replacable')
                        $replacable = 'yes';

                    $this->load->model('tool/image');

                    if ( file_exists( DIR_IMAGE .$product['image'] ) ) {
                        $image = HTTP_IMAGE.$product['image'];
                    } else {
                        $image = HTTP_IMAGE.'placeholder.png';
                    }

                    $var= [
                        "product_name" => $product['name'],
                        "product_unit"=>$product['unit'],
                        "product_quantity"=>$product['quantity'],
                        "product_image"=>$image,//"http:\/\/\/product-images\/camera.jpg",
                        "product_price"=>$product['price'],//"1500.00",//product price unit price?? or total
                        "product_replaceable"=>$replacable//"no"

                    ];

                    array_push($data['products']['products'], $var);
                }
                $log->write($data['products']['products']);

                $store_details = $this->model_account_order->getStoreById($order_info['store_id']);

                $log->write($store_details);

                $delivery_priority = 'normal';

                $temp = explode('.',$order_info['shipping_code']);
                if(isset($temp[0])) {
                    $delivery_priority = $temp[0];
                }

                $store_city_name = $this->model_account_order->getCityName($store_details['city_id']);

                $timeSlotAverage = $this->getTimeslotAverage($order_info['delivery_timeslot']);

                $data['body'] = [
                    'pickup_name' => $store_details['name'],//store name??
                    'pickup_phone' => $store_details['telephone'],
                    'pickup_address' => 'BTM  2nd stage, Axa building',//$store_details['address'],//
                    'pickup_city' => 'Bengaluru',//$store_city_name,// from $order_info['city_id'],
                    'pickup_state' => 'Karnataka',
                    'pickup_zipcode' => '560067',//$store_details['zipcode'],
                    'pickup_notes' => 'This is pickup notes.',
                    'dropoff_name' => $order_info['shipping_name'],
                    'dropoff_phone' => $order_info['telephone'],
                    'dropoff_address' => 'BTM  2nd stage, Axa building',//$order_info['shipping_building_name']." ".$order_info['shipping_landmark'],//'BTM  2nd stage, Axa building',
                    'dropoff_city' => $order_info['shipping_city'],// from $order_info['city_id'],
                    'dropoff_state' => 'Karnataka',// from $order_info['city_id'],
                    'dropoff_zipcode' => '560068',//$order_info['shipping_zipcode'],// from $order_info['city_id'],
                    'delivery_priority' => $delivery_priority,// normal/express all small
                    'delivery_date' => $order_info['delivery_date'],//2017-04-13
                    'delivery_slot' => $timeSlotAverage,//$order_info['delivery_timeslot'],//"10:30" //delivery slot is time so what will i enter here as i have data in format 06:26pm - 08:32pm
                    'dropoff_notes' => "This is drop off notes.",
                    'type_of_delivery' => 'delivery',//delivery/return . Is it only one option for this index?

                    'manifest_id' => $order_id,//order_id,
                    'manifest_data' => json_encode($data['products'])
                ];
                //if pending status create delivery in system

                $log->write($data['body']);

                $data['email'] = $this->config->get('config_delivery_username');
                   $data['password'] = $this->config->get('config_delivery_secret');
                $response = $this->load->controller('deliversystem/deliversystem/getToken',$data);

                if($response['status']) {
                    $data['token'] = $response['token'];
                    $res = $this->load->controller('deliversystem/deliversystem/createDelivery',$data);
                    $log->write("reeponse");
                    $log->write($res);

                    if($res['status']) {
                        $log->write("stsus");
                        if(isset($res['data']->delivery_id)) {

                            $delivery_id = $res['data']->delivery_id;
                            $log->write($delivery_id);
                            $this->db->query( "UPDATE `" . DB_PREFIX . "order` SET delivery_id = '" .$delivery_id ."', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'" );
                        }
                        //save in order table delivery id
                    }
                }
            }
*/
            $this->db->query('UPDATE `'.DB_PREFIX."order` SET order_status_id = '".(int) $order_status_id."', order_pdf_link ='".$pdf_link."', date_modified = NOW() WHERE order_id = '".(int) $order_id."'");

            $this->db->query('INSERT INTO '.DB_PREFIX."order_history SET order_id = '".(int) $order_id."', order_status_id = '".(int) $order_status_id."', notify = '".(int) $notify."', comment = '".$this->db->escape($comment)."', date_added = NOW()");

            // If current order status is not processing or complete but new status is processing or complete then commence completing the order
            //print_r($order_info['order_status_id']);

            if (!in_array($order_info['order_status_id'], array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status'))) &&
                in_array($order_status_id, array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status')))) {
                // Stock subtraction
                $order_product_query = $this->db->query('SELECT * FROM '.DB_PREFIX."order_product WHERE order_id = '".(int) $order_id."'");

                foreach ($order_product_query->rows as $order_product) {
                    $this->db->query('UPDATE '.DB_PREFIX.'product_to_store SET quantity = (quantity - '.(float) $order_product['quantity'].") WHERE product_store_id = '".(int) $order_product['product_id']."' AND subtract_quantity = '1'");
                }

                // Redeem coupon, vouchers and reward points
                $order_total_query = $this->db->query('SELECT * FROM `'.DB_PREFIX."order_total` WHERE order_id = '".(int) $order_id."' ORDER BY sort_order ASC");

                /*$log = new Log('tester.log');

                $log->write('tester log' . print_r($order_total_query, 1));*/

                //echo "<pre>";print_r($order_total_query,1);die;
                $log = new Log('error.log');

                //$log->write('PayPal Express debug '. print_r($order_total_query));

                foreach ($order_total_query->rows as $order_total) {
                    $this->load->model('total/'.$order_total['code']);

                    /*$log->write('PayPal Express debug '. $order_total['code']."s");*/
                    if (method_exists($this->{'model_total_'.$order_total['code']}, 'confirm')) {
                        //$this->logger();
                        // $log->write('PayPal Express debug '. $order_total['code']."s");
                        $this->{'model_total_'.$order_total['code']}->confirm($order_info, $order_total);
                    }
                }
            }

            // If old order status is the processing or complete status but new status is not then commence restock, and remove coupon, voucher and reward history
            if (in_array($order_info['order_status_id'], array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status'))) && !in_array($order_status_id, array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status')))) {
                // Restock

                $product_query = $this->db->query('SELECT * FROM '.DB_PREFIX."order_product WHERE order_id = '".(int) $order_id."'");

                foreach ($product_query->rows as $product) {
                    $this->db->query('UPDATE `'.DB_PREFIX.'product_to_store` SET quantity = (quantity + '.(float) $product['quantity'].") WHERE product_store_id = '".(int) $product['product_id']."' AND subtract_quantity = '1'");
                }

                // Remove coupon, vouchers and reward points history
                $this->load->model('account/order');

                $order_total_query = $this->db->query('SELECT * FROM `'.DB_PREFIX."order_total` WHERE order_id = '".(int) $order_id."' ORDER BY sort_order ASC");

                foreach ($order_total_query->rows as $order_total) {
                    $this->load->model('total/'.$order_total['code']);

                    if (method_exists($this->{'model_total_'.$order_total['code']}, 'unconfirm')) {
                        $this->{'model_total_'.$order_total['code']}->unconfirm($order_id);
                    }
                }
            }

            $this->cache->delete('product');

            if (in_array($order_status_id, array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status'))) || $notify) {
                $order_status = $this->db->query('SELECT name FROM '.DB_PREFIX."order_status WHERE order_status_id = '".(int) $order_status_id."' AND language_id = '".(int) $order_info['language_id']."'");

                if ($order_status->num_rows) {
                    $order_status = $order_status->row['name'];
                } else {
                    $order_status = '';
                }

                // Account Href
                $order_href = '';
                $order_pdf_href = '';

                if ($order_info['customer_id']) {
                    $order_href = $order_info['store_url'].'index.php?path=account/order/info&order_id='.$order_info['order_id'];
                }

                //Address Shipping and Payment
                $totals = [];
                $tax_amount = 0;

                if (0 != strlen($order_info['shipping_name'])) {
                    $address = $order_info['shipping_name'].'<br />'.$order_info['shipping_address'].'<br /><b>Contact No.:</b> '.$order_info['shipping_contact_no'];
                } else {
                    $address = '';
                }

                $payment_address = '';

                $order_total = $this->db->query('SELECT * FROM `'.DB_PREFIX."order_total` WHERE order_id = '".(int) $order_info['order_id']."'");

                foreach ($order_total->rows as $total) {
                    $totals[$total['code']][] = [
                        'title' => $total['title'],
                        'text' => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
                        'value' => $total['value'],
                    ];

                    if ('tax' == $total['code']) {
                        $tax_amount += $total['value'];
                    }
                }

                $log = new Log('error.log');

                $this->load->model('account/order');

                $log->write('in orderhis constant');

                $log->write($order_info['order_id']);

                $iugu_detail = $this->getOrderDetailIugu($order_info['order_id']);

                $log->write($iugu_detail);

                if (count($iugu_detail) > 0) {
                    $order_pdf_href = '<a href="'.$iugu_detail['pdf'].'"> Payment Gateway Receipt </a>';
                }

                $special = null;

                $data = [
                    'template_id' => 'order_'.(int) $order_status_id,
                    'order_info' => $order_info,
                    'address' => $address,
                    'payment_address' => $payment_address,
                    'special' => $special,
                    'order_href' => $order_href,
                    'order_pdf_href' => $order_pdf_href,
                    'order_status' => $order_status,
                    'totals' => $totals,
                    'tax_amount' => $tax_amount,
                    'invoice_no' => !empty($invoice_no) ? $invoice_no : '',
                ];

                /*
                                $log->write('in orderhis constant');

                                $log->write($data);*/
                //$log->write($order_status_id);

                //die;

                $getTotal = $order_total->rows;

                $textData = [
                    'order_info' => $order_info,
                    'order_id' => $order_id,
                    'order_status' => $order_status,
                    'comment' => $comment,
                    'notify' => $notify,
                    'getProdcuts' => $this->getOrderProducts($order_id),
                    'getVouchers' => [],
                    'getTotal' => $getTotal,
                ];

                $text = $this->emailtemplate->getText('Order', 'order', $textData);

                //Send Email
                if ((!$order_info['order_status_id'] && $order_status_id) || ($order_info['order_status_id'] && $order_status_id && $notify)) {
                    $subject = $this->emailtemplate->getSubject('OrderAll', 'order_'.(int) $order_status_id, $data);
                    $message = $this->emailtemplate->getMessage('OrderAll', 'order_'.(int) $order_status_id, $data);
                    //$log->write($message);

                    //die;
                    $sms_message = $this->emailtemplate->getSmsMessage('OrderAll', 'order_'.(int) $order_status_id, $data);
                    
                    if($customer_info['email_notification'] == 1) {
                    $mail = new mail($this->config->get('config_mail'));
                    $mail->setTo($order_info['email']);
                    $mail->setFrom($this->config->get('config_from_email'));
                    $mail->setSender($order_info['store_name']);
                    $mail->setSubject($subject);
                    $mail->setHtml($message);
                    $mail->setText($text);
                    $mail->send();
                    }

                    if ($customer_info['sms_notification'] == 1 && $this->emailtemplate->getSmsEnabled('OrderAll', 'order_'.(int) $order_status_id)) {
                        $ret = $this->emailtemplate->sendmessage($order_info['telephone'], $sms_message);
                    }

                    if ($customer_info['email_notification'] == 1 && $this->config->get('config_order_mail')) {
                        if (!isset($mail)) {
                            $mail = new mail($this->config->get('config_mail'));
                            $mail->setTo($order_info['email']);
                            $mail->setFrom($this->config->get('config_from_email'));
                            $mail->setSender($order_info['store_name']);
                        }

                        $mail->setHTML($message);

                        $mail->setText($text);
                        $mail->setTo($this->config->get('config_email'));
                        $mail->send();

                        $emails = explode(',', $this->config->get('config_alert_emails'));

                        foreach ($emails as $email) {
                            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                $mail->setTo($email);
                                $mail->send();
                            }
                        }
                    }
                }
            }
        }

        $this->trigger->fire('post.order.history.add', $order_id);
    }

    public function addTransaction($data)
    {
        $order_id = implode(',', $this->session->data['order_id']);
        //echo $this->session->data['transaction_id'];die;
        //unset($this->session->data['transaction_id']);
        if (isset($this->session->data['transaction_id'])) {
            // update here
            $this->db->query('UPDATE  '.DB_PREFIX."transaction_details SET order_ids = '".$order_id."', customer_id = '".$this->customer->getId()."', no_of_products = '".$this->db->escape($data['no_of_products'])."', `total` = '".(float) $data['total']."',date_added = NOW() where transaction_details_id ='".$this->session->data['transaction_id']."' ");
        } else {
            // insert here
            $this->db->query('INSERT INTO '.DB_PREFIX."transaction_details SET order_ids = '".$order_id."', customer_id = '".$this->customer->getId()."', no_of_products = '".$this->db->escape($data['no_of_products'])."', `total` = '".(float) $data['total']."',date_added = NOW()");
            $transaction_id = $this->db->getLastId();
            $this->session->data['transaction_id'] = $transaction_id;
        }
    }

    protected function _prepareProductSpecial($customer_group_id, $limit)
    {
        $special = [];
        $product_special = $this->_getProductSpecial((int) $customer_group_id, $limit);

        if (0 != sizeof($product_special)) {
            foreach ($product_special as $product) {
                $discount = round((($product['price'] - $product['special']) / $product['price']) * 100, 0);

                $special[] = '<a href="'.$this->url->link('product/product', 'product_id='.$product['product_id'], 'SSL').'">'.$product['name'].'</a> (<font color="red">-'.$discount.'%</font>)';
            }
        }

        return $special;
    }

    protected function _getProductSpecial($customer_group_id, $limit = 5)
    {
        $sql = 'SELECT DISTINCT ps.product_id, ps.price AS special, p.price, pd.name, (SELECT AVG(rating) FROM '.DB_PREFIX."review r1 WHERE r1.product_id = ps.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM ".DB_PREFIX.'product_special ps LEFT JOIN '.DB_PREFIX.'product p ON (ps.product_id = p.product_id) LEFT JOIN '.DB_PREFIX.'product_description pd ON (p.product_id = pd.product_id) LEFT JOIN '.DB_PREFIX."product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '".(int) $this->config->get('config_store_id')."' AND ps.customer_group_id = '".(int) $customer_group_id."' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) GROUP BY ps.product_id ORDER BY p.sort_order ASC LIMIT ".(int) $limit;

        $product_data = [];

        $query = $this->db->query($sql);

        foreach ($query->rows as $result) {
            $product_data[] = [
                'product_id' => $result['product_id'],
                'price' => $result['price'],
                'special' => $result['special'],
                'name' => $result['name'],
            ];
        }

        return $product_data;
    }

    public function getOrderProducts($order_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."order_product WHERE order_id = '".(int) $order_id."'");

        return $query->rows;
    }

    public function getOrderVouchers($order_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."order_voucher WHERE order_id = '".(int) $order_id."'");

        return $query->rows;
    }

    public function updateOrderCoupons($order_id, $total)
    {
        /*foreach ($order_id as $key => $value) {

            $query = $this->db->query( "SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . $value . "' AND code = 'reward'");
            if($query->num_rows == 0) {

                $title = 'Reward Points ('.$total.')';
                $amount = $total * -2;

                $this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . $value . "', code = 'reward', title = '" .$title. "', `value` = '" .$amount. "', sort_order = '2'");

                //total update
                $query = $this->db->query( "SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . $value . "' AND code = 'total'");

                echo "<pre>";print_r($query);die;
                if($query->num_rows == 0) {
                    $val = $query->row['value'] - $amount;
                    echo "<pre>";print_r($query);die;
                    $this->db->query("UPDATE " . DB_PREFIX . "order_total SET `value` = '" .$val. "' WHERE order_id = '" . $value ."' AND code='total'");
                }
            }
        }*/
    }

    public function setCustomerReward($customer_id, $order_id, $text_order_id, $points)
    {
        $this->db->query('INSERT INTO '.DB_PREFIX."customer_reward SET customer_id = '".(int) $customer_id."', order_id = '".(int) $order_id."', description = '".$this->db->escape(sprintf($text_order_id, (int) $order_id))."', points = '".(float) $points."', date_added = NOW()");
    }

    public function getOrderProductData($order_id)
    {
        return $this->db->query("SELECT `name`, `model`, `price`, `quantity`, `tax` / `price` * 100 AS 'tax_rate' FROM `".DB_PREFIX.'order_product` WHERE `order_id` = '.(int) $order_id." UNION ALL SELECT '', `code`, `amount`, '1', 0.00 FROM `".DB_PREFIX.'order_voucher` WHERE `order_id` = '.(int) $order_id);
    }

    public function getOrderTotal($order_id)
    {
        return $this->db->query('SELECT `value` FROM `'.DB_PREFIX.'order_total` WHERE `order_id` = '.(int) $order_id." AND `code` = 'klarna_fee'");
    }

    public function getOrderDetailIugu($order_id)
    {
        $log = new Log('error.log');

        $log->write('in igi constant');
        $log->write('SELECT * FROM `'.DB_PREFIX.'order_iugu` WHERE `order_id` = '.(int) $order_id);

        return $this->db->query('SELECT * FROM `'.DB_PREFIX.'order_iugu` WHERE `order_id` = '.(int) $order_id)->row;
    }

    public function getTimeslotAverage($timeslot)
    {
        $str = $timeslot; //"06:26pm - 08:32pm";
        $arr = explode('-', $str);
        //print_r($arr);
        if (2 == count($arr)) {
            $one = date('H:i', strtotime($arr[0]));
            $two = date('H:i', strtotime($arr[1]));

            $time1 = explode(':', $one);
            $time2 = explode(':', $two);
            if (2 == count($time1) && 2 == count($time2)) {
                $mid1 = ($time1[0] + $time2[0]) / 2;
                $mid2 = ($time1[1] + $time2[1]) / 2;

                $mid1 = round($mid1);
                $mid2 = round($mid2);

                if ($mid2 <= 9) {
                    $mid2 = '0'.$mid2;
                }
                if ($mid1 <= 9) {
                    $mid1 = '0'.$mid1;
                }

                //if 19.5 is mid1 then i send 19 integer part cant send decimals

                return $mid1.':'.$mid2;
            }
        }

        return false;
    }

    public function getCustomer($customer_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."customer WHERE customer_id = '".(int) $customer_id."'");

        return $query->row;
    }

    public function addOrder($order_id, $data, $credit_card = false)
    {
        $this->db->query('DELETE FROM '.DB_PREFIX."order_iugu WHERE order_id = '".(int) $order_id."'");

        if (!$credit_card) {
            $this->db->query('INSERT INTO '.DB_PREFIX.'order_iugu SET order_id = "'.(int) $order_id.'", invoice_id = "'.$this->db->escape($data['id']).'", pdf = "'.$this->db->escape($data['secure_url'].'.pdf').'", identification = "'.$this->db->escape($data['secure_id']).'", date_added = NOW(), date_modified = NOW()');
        } else {
            $this->db->query('INSERT INTO '.DB_PREFIX.'order_iugu SET order_id = "'.(int) $order_id.'", invoice_id = "'.$this->db->escape($data['invoice_id']).'", pdf = "'.$this->db->escape($data['pdf']).'", identification = "'.$this->db->escape($data['identification']).'", date_added = NOW(), date_modified = NOW()');
        }
    }

    public function createDeliveryRequest($order_id, $order_status_id = 1)
    {
        $log = new Log('error.log');
        $log->write('createDeliveryRequest here in admin/model/checkout/order');
        $order_info = $this->getOrder($order_id);

        $this->load->model('account/order');

        $deliveryAlreadyCreated = $this->model_account_order->getOrderDeliveryId($this->request->get['order_id']);

        if (1 == $order_status_id && $order_info && !$deliveryAlreadyCreated) {
            $log->write('inside createDeliveryRequest');

            $data['products']['products'] = [];

            $products = $this->model_account_order->getOrderProducts($order_id);

            $log = new Log('error.log');

            $log->write('tester log');
            $log->write($order_info);

            foreach ($products as $product) {
                $replacable = 'no';

                if ('replacable' == $product['product_type']) {
                    $replacable = 'yes';
                }

                $this->load->model('tool/image');

                if (file_exists(DIR_IMAGE.$product['image'])) {
                    $image = HTTP_IMAGE.$product['image'];
                } else {
                    $image = HTTP_IMAGE.'placeholder.png';
                }

                $var = [
                    'product_name' => $product['name'],
                    'product_unit' => $product['unit'],
                    'product_quantity' => $product['quantity'],
                    'product_image' => $image, //"http:\/\/\/product-images\/camera.jpg",
                    'product_price' => $product['price'], //"1500.00",//product price unit price?? or total
                    'product_replaceable' => $replacable, //"no"
                ];

                array_push($data['products']['products'], $var);
            }
            $log->write($data['products']['products']);

            $store_details = $this->model_account_order->getStoreById($order_info['store_id']);

            $log->write($store_details);

            $delivery_priority = 'normal';

            $temp = explode('.', $order_info['shipping_code']);
            if (isset($temp[0])) {
                $delivery_priority = $temp[0];
            }

            $store_city_name = $this->model_account_order->getCityName($store_details['city_id']);

            $timeSlotAverage = $this->getTimeslotAverage($order_info['delivery_timeslot']);

            $deliverAddress = $order_info['shipping_flat_number'].', '.$order_info['shipping_building_name'].', '.$order_info['shipping_landmark'];

            $data['body'] = [
                'pickup_name' => $store_details['name'], //store name??
                'pickup_phone' => $store_details['telephone'],
                'pickup_address' => $store_details['address'],
                'pickup_city' => $store_city_name,
                'pickup_state' => 'Brussels',
                'from_lat' => $store_details['latitude'],
                'from_lng' => $store_details['longitude'],
                'pickup_zipcode' => $store_details['store_zipcode'], //''
                'pickup_notes' => '',
                'dropoff_name' => $order_info['shipping_name'],
                'dropoff_phone' => $order_info['telephone'],
                'dropoff_address' => $deliverAddress,
                'to_lat' => $order_info['latitude'],
                'to_lng' => $order_info['longitude'],
                'dropoff_city' => $order_info['shipping_city'], // from $order_info['city_id'],
                'dropoff_state' => 'Brussels',
                'dropoff_zipcode' => $order_info['shipping_zipcode'], // from $order_info['city_id'],
                'delivery_priority' => $delivery_priority, // normal/express all small
                'delivery_date' => $order_info['delivery_date'], //2017-04-13
                'delivery_slot' => $timeSlotAverage, //$order_info['delivery_timeslot'],//"10:30" //delivery slot is time so what will i enter here as i have data in format 06:26pm - 08:32pm
                'dropoff_notes' => '',
                'type_of_delivery' => 'delivery', //delivery/return . Is it only one option for this index?

                'manifest_id' => $order_id, //order_id,
                'manifest_data' => json_encode($data['products']),
                'payment_method' => $order_info['payment_method'],
                'payment_code' => $order_info['payment_code'],
            ];

            $log->write($data['body']);

            $data['email'] = $this->config->get('config_delivery_username');
            $data['password'] = $this->config->get('config_delivery_secret');
            $response = $this->load->controller('deliversystem/deliversystem/getToken', $data);

            if ($response['status']) {
                $data['token'] = $response['token'];
                $res = $this->load->controller('deliversystem/deliversystem/createDelivery', $data);
                $log->write('reeponse');
                $log->write($res);

                if ($res['status']) {
                    $log->write('stsus');
                    if (isset($res['data']->delivery_id)) {
                        $delivery_id = $res['data']->delivery_id;
                        $log->write($delivery_id);
                        $this->db->query('UPDATE `'.DB_PREFIX."order` SET delivery_id = '".$delivery_id."', date_modified = NOW() WHERE order_id = '".(int) $order_id."'");
                    }
                    //save in order table delivery id
                }
            }
        }
    }
}
