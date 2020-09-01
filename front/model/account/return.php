<?php

class ModelAccountReturn extends Model
{
    public function addReturn($data)
    {
        $this->trigger->fire('pre.return.add', $data);
        $this->load->model('checkout/order');
        $this->load->model('account/customer');
        $log = new Log('error.log');

        $log = new Log('addReturn');

        $this->db->query('INSERT INTO `'.DB_PREFIX."return` SET product_id = '".(int) $data['product_id']."', order_id = '".(int) $data['order_id']."', customer_id = '".(int) $this->customer->getId()."', firstname = '".$this->db->escape($data['firstname'])."', lastname = '".$this->db->escape($data['lastname'])."', email = '".$this->db->escape($data['email'])."', telephone = '".$this->db->escape($data['telephone'])."', product = '".$this->db->escape($data['product'])."', unit = '".$this->db->escape($data['unit'])."', price = '".$this->db->escape($data['price'])."', model = '".$this->db->escape($data['model'])."', quantity = '".(int) $data['quantity']."', opened = '".(int) $data['opened']."', return_reason_id = '".(int) $data['return_reason_id']."',  customer_desired_action = '".$data['customer_desired_action']."',  return_status_id = '".(int) $this->config->get('config_return_status_id')."', comment = '".$this->db->escape($data['comment'])."', date_ordered = '".$this->db->escape($data['date_ordered'])."', date_added = NOW(), date_modified = NOW()");

        $return_id = $this->db->getLastId();

        $this->db->query('INSERT INTO '.DB_PREFIX."return_history SET return_id = '".(int) $return_id."', return_status_id = '".(int) $this->config->get('config_return_status_id')."', notify = '".(isset($data['notify']) ? (int) $data['notify'] : 0)."', comment = '".$this->db->escape(strip_tags($data['comment']))."', date_added = NOW()");

        //update return id in order_product table

        $this->db->query('UPDATE `'.DB_PREFIX.'order_product` SET '
                    ."return_id = '".$return_id."' "
                    ."WHERE order_id='".(int) $data['order_id']."'"
                    ."and product_id='".(int) $data['product_id']."'");

        //update return id in real_order_product table
        $this->db->query('UPDATE `'.DB_PREFIX.'real_order_product` SET '
                    ."return_id = '".$return_id."' "
                    ."WHERE order_id='".(int) $data['order_id']."'"
                    ."and product_id='".(int) $data['product_id']."'");

        $this->trigger->fire('post.return.add', $return_id);

        $this->updateGetAmount($data['order_id'], ($data['price'] * $data['quantity']));

        // send return notification

        $order_info = $this->model_checkout_order->getOrder($data['order_id']);

        if ($order_info) {
            $log = new Log('addReturn 1');
            $data['product_name'] = $data['product'];
            $data['return_id'] = $return_id;

            $log->write('return status enabled of mobi noti');
            $mobile_notification_template = $this->emailtemplate->getNotificationMessage('vendorreturn', 'vendorreturn_3', $data);

            $log->write($mobile_notification_template);

            $mobile_notification_title = $this->emailtemplate->getNotificationTitle('vendorreturn', 'vendorreturn_3', $data);

            $log->write($mobile_notification_title);

            $log->write('return status enabled of mobi noti');
            $customer_mobile_notification_template = $this->emailtemplate->getNotificationMessage('return', 'return_1', $data);

            $log->write($customer_mobile_notification_template);

            $customer_mobile_notification_title = $this->emailtemplate->getNotificationTitle('return', 'return_1', $data);

            $log->write($customer_mobile_notification_title);

            $temporaryVendorInfo = $this->db->query('select * from '.DB_PREFIX.'order LEFT JOIN '.DB_PREFIX.'store on('.DB_PREFIX.'store.store_id = '.DB_PREFIX.'order.store_id) WHERE '.DB_PREFIX.'order.store_id="'.$order_info['store_id'].'" AND order_id="'.$data['order_id'].'"')->row;

            $vendorData = $this->model_checkout_order->getVendorDetails($temporaryVendorInfo['vendor_id']);

            $store_details = $this->model_account_order->getStoreById($order_info['store_id']);

            $log->write($vendorData);

            /* vendor mail/sms/notificaiton start*/
            $subject = $this->emailtemplate->getSubject('vendorreturn', 'vendorreturn_3', $data);
            $message = $this->emailtemplate->getMessage('vendorreturn', 'vendorreturn_3', $data);
            $sms_message = $this->emailtemplate->getSmsMessage('vendorreturn', 'vendorreturn_3', $data);

            //$log->write($message);

            //echo "<pre>";print_r($message);die;
            if ($this->emailtemplate->getEmailEnabled('Return', 'vendorreturn_3')) {
                $mail = new mail($this->config->get('config_mail'));
                $mail->setTo($store_details['email']);
                $mail->setFrom($this->config->get('config_from_email'));
                $mail->setSender($order_info['store_name']);
                $mail->setSubject($subject);
                $mail->setHtml($message);
                //$mail->setText( $text );
                $mail->send();

                $log->write('mail end');
            }
            /* vendor mail/sms/notificaiton start */

            /* customer mail/sms/notificaiton start*/
            $subject = $this->emailtemplate->getSubject('Return', 'return_1', $data);
            $message = $this->emailtemplate->getMessage('Return', 'return_1', $data);
            $sms_message = $this->emailtemplate->getSmsMessage('Return', 'return_1', $data);

            //$log->write($message);

            //echo "<pre>";print_r($message);die;
            if ($this->emailtemplate->getEmailEnabled('Return', 'return_1')) {
                $mail = new mail($this->config->get('config_mail'));
                $mail->setTo($order_info['email']);
                $mail->setFrom($this->config->get('config_from_email'));
                $mail->setSender($order_info['store_name']);
                $mail->setSubject($subject);
                $mail->setHtml($message);
                //$mail->setText( $text );
                $mail->send();

                $log->write('mail end');
            }
            /* customer mail/sms/notificaiton start */

            /* vendor notificaiton start */
            if (isset($vendorData['device_id']) && strlen($vendorData['device_id']) > 0) {
                $log->write('device id set');

                if ($this->emailtemplate->getNotificationEnabled('vendorreturn', 'vendorreturn_3')) {
                    $ret = $this->emailtemplate->sendReturnPushNotification($temporaryVendorInfo['vendor_id'], $vendorData['device_id'], $return_id, $order_info['store_id'], $mobile_notification_template, $mobile_notification_title);
                }

                $this->model_checkout_order->saveReturnVendorNotification($temporaryVendorInfo['vendor_id'], $vendorData['device_id'], $return_id, $mobile_notification_template, $mobile_notification_title);
            } else {
                $log->write('device id not set');
            }

            /* vendor notificaiton end */

            /* cust notificaiton start */

            $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);

            if (isset($customer_info) && isset($customer_info['device_id']) && strlen($customer_info['device_id']) > 0) {
                $log->write('device id set');

                if ($this->emailtemplate->getNotificationEnabled('return', 'return_1')) {
                    $ret = $this->emailtemplate->sendCustomerReturnPushNotification($customer_info['customer_id'], $customer_info['device_id'], $return_id, $order_info['store_id'], $customer_mobile_notification_template, $customer_mobile_notification_title);
                }

                //$this->model_checkout_order->saveReturnVendorNotification($customer_info['customer_id'],$customer_info['device_id'],$return_id,$customer_mobile_notification_template,$customer_mobile_notification_title);
            } else {
                $log->write('device id not set');
            }

            /* cust notificaiton end */
        }

        return $return_id;
    }

    public function updateGetAmount($order_id, $total)
    {
        $log = new Log('error.log');

        $log->write('inside updateGetAmount');

        $this->load->model('account/order');
        $this->load->model('api/checkout');

        $order_info = $this->model_api_checkout->getOrder($order_id);

        //echo "<pre>";print_r($order_info);die;
        $deliveryAlreadyCreated = $this->model_account_order->getOrderDSDeliveryId($order_id);

        if ($order_info && $deliveryAlreadyCreated && 'cod' == $order_info['payment_code']) {
            $data['body'] = [
                'manifest_id' => $deliveryAlreadyCreated, //order_id,
                //'total_price' => (int) round($new_total),
                'get_amount' => (int) round($total),
                //'total_type' => $total_type,
                //'manifest_data' => json_encode($data['products'])
            ];

            $log->write($data['body']);

            $data['email'] = $this->config->get('config_delivery_username');
            $data['password'] = $this->config->get('config_delivery_secret');
            $response = $this->load->controller('deliversystem/deliversystem/getToken', $data);

            $log->write('token');
            $log->write($response);
            if ($response['status']) {
                $data['tokens'] = $response['token'];
                $res = $this->load->controller('deliversystem/deliversystem/updateDelivery', $data);
                $log->write('reeponse');
                $log->write($res);
            }
        }

        return true;
    }

    public function getReturn($return_id)
    {
        $query = $this->db->query('SELECT r.return_id,r.price,r.product_id, r.order_id, r.firstname, r.lastname, r.email, r.telephone, r.product, r.unit,r.return_action_id,r.return_reason_id, r.model, r.quantity,r.return_status_id, r.opened, (SELECT rr.name FROM '.DB_PREFIX."return_reason rr WHERE rr.return_reason_id = r.return_reason_id AND rr.language_id = '".(int) $this->config->get('config_language_id')."') AS reason, (SELECT ra.name FROM ".DB_PREFIX."return_action ra WHERE ra.return_action_id = r.return_action_id AND ra.language_id = '".(int) $this->config->get('config_language_id')."') AS action, (SELECT rs.name FROM ".DB_PREFIX."return_status rs WHERE rs.return_status_id = r.return_status_id AND rs.language_id = '".(int) $this->config->get('config_language_id')."') AS status, r.comment, r.date_ordered, r.date_added, r.date_modified FROM `".DB_PREFIX."return` r WHERE return_id = '".(int) $return_id."' AND customer_id = '".$this->customer->getId()."'");

        return $query->row;
    }

    public function getReturns($start = 0, $limit = 20)
    {
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 20;
        }

        $query = $this->db->query('SELECT r.return_id, r.order_id, r.firstname, r.return_status_id, r.lastname, rs.name as status, r.date_added FROM `'.DB_PREFIX.'return` r LEFT JOIN '.DB_PREFIX."return_status rs ON (r.return_status_id = rs.return_status_id) WHERE r.customer_id = '".$this->customer->getId()."' AND rs.language_id = '".(int) $this->config->get('config_language_id')."' ORDER BY r.return_id DESC LIMIT ".(int) $start.','.(int) $limit);

        return $query->rows;
    }

    public function getTotalReturns()
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `'.DB_PREFIX."return`WHERE customer_id = '".$this->customer->getId()."'");

        return $query->row['total'];
    }

    public function getReturnHistories($return_id)
    {
        $query = $this->db->query('SELECT rh.date_added, rs.name AS status, rh.comment, rh.notify FROM '.DB_PREFIX.'return_history rh LEFT JOIN '.DB_PREFIX."return_status rs ON rh.return_status_id = rs.return_status_id WHERE rh.return_id = '".(int) $return_id."' AND rh.notify = '1' AND rs.language_id = '".(int) $this->config->get('config_language_id')."' ORDER BY rh.date_added ASC");

        return $query->rows;
    }

    public function getProduct($order_id, $product_id)
    {
        $this->load->model('account/order');

        $realproducts = $this->model_account_order->hasRealOrderProducts($order_id);

        if ($realproducts) {
            /*$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");*/
            $query = $this->db->query('SELECT a.*,b.image as image
	        FROM `'.DB_PREFIX.'real_order_product` a,`'.DB_PREFIX.'product` b,`'.DB_PREFIX."product_to_store` c
	        WHERE b.product_id=c.product_id
	        AND c.product_store_id='".$product_id."'
	        AND a.order_id='".$order_id."'
	        AND a.product_id='".$product_id."'");

            //echo "<pre>";print_r($query);die;
            return $query->row;
        } else {
            $query = $this->db->query('SELECT a.*,b.image as image
	        FROM `'.DB_PREFIX.'order_product` a,`'.DB_PREFIX.'product` b,`'.DB_PREFIX."product_to_store` c
	        WHERE b.product_id=c.product_id
	        AND c.product_store_id='".$product_id."'
	        AND a.order_id='".$order_id."'
	        AND a.product_id='".$product_id."'");

            //echo "<pre>";print_r($query);die;
            return $query->row;
        }
    }

    public function getRealProduct($order_id, $product_id)
    {
        /*$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");*/
        $query = $this->db->query('SELECT a.*,b.image as image
        FROM `'.DB_PREFIX.'real_order_product` a,`'.DB_PREFIX.'product` b,`'.DB_PREFIX."product_to_store` c
        WHERE b.product_id=c.product_id
        AND c.product_store_id='".$product_id."'
        AND a.order_id='".$order_id."'
        AND a.product_id='".$product_id."'");

        //echo "<pre>";print_r($query);die;
        return $query->row;
    }
}
