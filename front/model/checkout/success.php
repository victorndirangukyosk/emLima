<?php

class ModelCheckoutSuccess extends Model
{
    public function getShippingCode($order_id)
    {
        $row = $this->db->query('select shipping_code from `'.DB_PREFIX.'order` WHERE order_id="'.$order_id.'"')->row;

        if ($row) {
            return $row['shipping_code'];
        }
    }

    public function getMessage($order_id)
    {
        $message = '';
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."order_history WHERE order_id = '".(int) $order_id."'");

        if (!empty($query->num_rows)) {
            $order_status_id = $query->row['order_status_id'];

            $query = $this->db->query('SELECT * FROM '.DB_PREFIX."order_status WHERE order_status_id = '".(int) $order_status_id."' AND language_id ='".$this->config->get('config_language_id')."'");

            if (!empty($query->num_rows)) {
                $message = $query->row['message'];
            }
        }

        return $message;
    }

    //notify shoppers about new order

    public function notify_shoppers($order_id)
    {
        $config_dri_auto_assign = $this->config->get('config_dri_auto_assign');

        if (!$config_dri_auto_assign) {
            return false;
        }

        $sub_orders = $this->db->query('select * from `'.DB_PREFIX.'vendor_order` WHERE order_id="'.$order_id.'"')->rows;

        foreach ($sub_orders as $sub_order) {
            $order_info = $this->getOrderInfo($sub_order['vendor_order_id']);

            $message = $this->get_shopper_email_html($sub_order['vendor_order_id'], $order_info);

            $mail = new Mail($this->config->get('config_mail'));
            $mail->setSubject('New order request #'.$sub_order['vendor_order_id']);
            $mail->setFrom($this->config->get('config_from_email'));
            $mail->setSender($order_info['store_name']);
            $mail->setHTML($message);

            $shopper = $this->find_shoppers($order_info['latitude'], $order_info['longitude']);

            if ($shopper && filter_var($shopper['email'], FILTER_VALIDATE_EMAIL)) {
                $mail->setTo($shopper['email']);
                $mail->send();

                //save order_id shopper_id in order_request
                $this->db->query('INSERT INTO `'.DB_PREFIX.'order_request` SET order_id="'.$sub_order['vendor_order_id'].'", shopper_id="'.$shopper['user_id'].'"');

                //log action
                $this->db->query('INSERT INTO `'.DB_PREFIX.'shopper_order_log` SET shopper_id="'.$shopper['user_id'].'", vendor_order_id="'.$sub_order['vendor_order_id'].'", action="assigned", date_added=NOW()');
            }
        }
    }

    public function getOrderInfo($vendor_order_id)
    {
        $this->load->language('sale/order');

        $data['title'] = $this->language->get('text_invoice');

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        if ($this->request->server['HTTPS']) {
            $data['base'] = $server;
        } else {
            $data['base'] = $server;
        }

        $data['direction'] = $this->language->get('direction');
        $data['lang'] = $this->language->get('code');

        $data['text_invoice'] = $this->language->get('text_invoice');
        $data['text_order_detail'] = $this->language->get('text_order_detail');
        $data['text_order_id'] = $this->language->get('text_order_id');
        $data['text_invoice_no'] = $this->language->get('text_invoice_no');
        $data['text_invoice_date'] = $this->language->get('text_invoice_date');
        $data['text_date_added'] = $this->language->get('text_date_added');
        $data['text_telephone'] = $this->language->get('text_telephone');
        $data['text_fax'] = $this->language->get('text_fax');
        $data['text_email'] = $this->language->get('text_email');
        $data['text_website'] = $this->language->get('text_website');
        $data['text_to'] = $this->language->get('text_to');
        $data['text_ship_to'] = $this->language->get('text_ship_to');
        $data['text_payment_method'] = $this->language->get('text_payment_method');
        $data['text_shipping_method'] = $this->language->get('text_shipping_method');

        $data['column_product'] = $this->language->get('column_product');
        $data['column_model'] = $this->language->get('column_model');
        $data['column_quantity'] = $this->language->get('column_quantity');
        $data['column_price'] = $this->language->get('column_price');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_comment'] = $this->language->get('column_comment');

        $this->load->model('sale/order');

        $this->load->model('setting/setting');

        $sql = 'select vo.*, o.email, o.telephone, o.shipping_address, o.shipping_name, o.shipping_contact_no, ';
        $sql .= 'c.name as city, o.shipping_method, o.payment_method, o.payment_code, o.shipping_code, o.comment from `'.DB_PREFIX.'vendor_order` vo ';
        $sql .= 'inner join `'.DB_PREFIX.'order` o on o.order_id = vo.order_id ';
        $sql .= 'left join `'.DB_PREFIX.'city` c on c.city_id = o.shipping_city_id ';
        $sql .= 'where vo.vendor_order_id="'.$vendor_order_id.'"';

        $order_info = $this->db->query($sql)->row;

        $store_info = $this->db->query('select * from `'.DB_PREFIX.'store` WHERE store_id="'.$order_info['store_id'].'"')->row;

        if ($store_info) {
            $store_address = $store_info['address'];
            $store_email = $store_info['email'];
            $store_telephone = $store_info['telephone'];
            $store_fax = $store_info['fax'];
            $store_latitude = $store_info['latitude'];
            $store_longitude = $store_info['longitude'];
        } else {
            $store_address = '';
            $store_email = '';
            $store_telephone = '';
            $store_fax = '';
            $store_latitude = '';
            $store_longitude = '';
        }

        $this->load->model('tool/upload');

        $product_data = [];

        $products = $this->db->query('select * from `'.DB_PREFIX.'order_product` WHERE store_id="'.$order_info['store_id'].'" AND order_id="'.$order_info['order_id'].'"')->rows;

        foreach ($products as $product) {
            $option_data = [];

            $options = $this->model_sale_order->getOrderOptions($order_info['order_id'], $product['order_product_id']);

            foreach ($options as $option) {
                if ('file' != $option['type']) {
                    $value = $option['value'];
                } else {
                    $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

                    if ($upload_info) {
                        $value = $upload_info['name'];
                    } else {
                        $value = '';
                    }
                }

                $option_data[] = [
                    'name' => $option['name'],
                    'value' => $value,
                ];
            }

            $product_data[] = [
                'name' => $product['name'],
                'store_id' => $product['store_id'],
                'model' => $product['model'],
                'option' => $option_data,
                'quantity' => $product['quantity'],
                'price' => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                'total' => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
            ];
        }

        $total_data = [];

        $totals = $this->db->query('select * from `'.DB_PREFIX.'vendor_order_total` where store_id="'.$order_info['store_id'].'" AND order_id="'.$order_info['order_id'].'"')->rows;

        foreach ($totals as $total) {
            $total_data[] = [
                'title' => $total['title'],
                'text' => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']), ];
        }

        $result = [
            'order_id' => $vendor_order_id,
            'invoice_no' => '',
            'latitude' => $store_info['latitude'],
            'longitude' => $store_info['longitude'],
            'date_added' => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
            'store_name' => $order_info['store_name'],
            'store_address' => nl2br($store_address),
            'store_email' => $store_email,
            'store_telephone' => $store_telephone,
            'store_fax' => $store_fax,
            'store_latitude' => $store_latitude,
            'store_longitude' => $store_longitude,
            'email' => $order_info['email'],
            'telephone' => $order_info['telephone'],
            'shipping_address' => $order_info['shipping_address'],
            'shipping_contact_no' => $order_info['shipping_contact_no'],
            'shipping_name' => $order_info['shipping_name'],
            'shipping_method' => $order_info['shipping_method'],
            'payment_method' => $order_info['payment_method'],
            'payment_code' => $order_info['payment_code'],
            'shipping_code' => $order_info['shipping_code'],
            'products' => $product_data,
            'comment' => $order_info['comment'],
            'total' => $total_data, ];

        return $result;
    }

    //get shopper email html

    public function get_shopper_email_html($order_id, $order_info)
    {
        $data['order'] = $order_info;

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['base'] = $server;
        $data['order_id'] = $order_id;

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/mail/shopper.tpl')) {
            return $this->load->view($this->config->get('config_template').'/template/mail/shopper.tpl', $data);
        } else {
            return $this->load->view('default/template/mail/shopper.tpl', $data);
        }
    }

    //find shopper to send order mail

    public function find_shoppers($store_latitude, $store_longitude)
    {
        /* 1) get shopper with minimum distance
         * 2) shopper sort by processing order assigned
         * 3) processing order assigned < config_dri_max_order
         * 4) shopper sort by order requests
         */

        $config_dri_max_order = $this->config->get('config_dri_max_order');

        $distance = '        (111.045 * DEGREES(                        ACOS(                                COS(                                    RADIANS(u.latitude)                                )                                *                                 COS(                                    RADIANS('.$store_latitude.')                                )                                *                                 COS(                                    RADIANS(u.longitude) - RADIANS('.$store_longitude.')                                )                                +                                 SIN(RADIANS(u.latitude))                                *                                 SIN(                                    RADIANS('.$store_latitude.')                                )                            )                    )) AS distance_in_km';

        $total_order_req = '(select count(r.order_id) from `'.DB_PREFIX.'order_request` r where r.shopper_id = u.user_id) as total_order_req';
        $total_order_proc = '(select count(vo.vendor_order_id) from `'.DB_PREFIX.'vendor_order` vo WHERE vo.shopper_id = u.user_id AND vo.order_status_id IN ('.implode(',', $this->config->get('config_processing_status')).') ) as total_order_proc';

        $sql = 'select '.$total_order_req.',  '.$total_order_proc.', '.$distance.', u.user_id, u.email from `'.DB_PREFIX.'user` u ';
        $sql .= 'INNER JOIN `'.DB_PREFIX.'shopper_timeslot` st on st.shopper_id = u.user_id ';
        $sql .= 'WHERE st.weekday="'.date('w').'" ';
        $sql .= 'AND CURTIME() >= st.from_time ';
        $sql .= 'AND CURTIME() <= st.to_time ';
        $sql .= 'group by u.user_id ';
        $sql .= 'HAVING total_order_proc < '.$config_dri_max_order.' ';
        $sql .= 'ORDER BY distance_in_km ASC, total_order_proc ASC, total_order_req ASC ';
        $sql .= 'LIMIT 1';

        return $this->db->query($sql)->row;
    }
}
