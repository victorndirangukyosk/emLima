<?php

class ModelCheckoutOrder extends Model {

    //get vendor commision
    public function get_commision($vendor_id) {
        $row = $this->db->query('select commision, free_from, free_to from ' . DB_PREFIX . 'user where user_id="' . $vendor_id . '"')->row;
        if (time() >= strtotime($row['free_from']) && time() <= strtotime($row['free_to'])) {
            return 0; //in free trial month
        } else {
            return $row['commision'];
        }
    }

    public function addOrder($stores) {

        $log = new Log('error.log');
        $log->write('addorder 1');
        /*TIME ZONE ISSUE*/
        $tz = (new DateTime('now', new DateTimeZone('Africa/Nairobi')))->format('P');
        $this->db->query("SET time_zone='$tz';");
        /*TIME ZONE ISSUE*/
        $this->trigger->fire('pre.order.add', $stores);
        //print_r($data);die;
        //unset($this->session->data['order_id']);die;
        //echo "<pre>";print_r($this->session->data['order_id']);
        //print_r($stores);die;
        $orders = isset($this->session->data['order_id']);

        if ($orders) {
            foreach ($stores as $key => $data) {
                //echo $this->session->data['order_id'][$key];

                $this->deleteOrder($this->session->data['order_id'][$key]);
                $order_id = $this->session->data['order_id'][$key];
                $log->write('addorder 2');
                $this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET order_id='" . $order_id . "', invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "', store_id = '" . (int) $data['store_id'] . "', store_name = '" . $this->db->escape($data['store_name']) . "', store_url = '" . $this->db->escape($data['store_url']) . "', customer_id = '" . (int) $data['customer_id'] . "', customer_group_id = '" . (int) $data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', payment_code = '" . $this->db->escape($data['payment_code']) . "',shipping_method = '" . $this->db->escape($data['shipping_method']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', comment = '" . $this->db->escape($data['comment']) . "', total = '" . (float) $data['total'] . "', latitude = '" . $data['latitude'] . "',longitude = '" . $data['longitude'] . "', affiliate_id = '" . (int) $data['affiliate_id'] . "',marketing_id = '" . (int) $data['marketing_id'] . "', tracking = '" . $this->db->escape($data['tracking']) . "', language_id = '" . (int) $data['language_id'] . "', currency_id = '" . (int) $data['currency_id'] . "', currency_code = '" . $this->db->escape($data['currency_code']) . "', currency_value = '" . (float) $data['currency_value'] . "', ip = '" . $this->db->escape($data['ip']) . "', forwarded_ip = '" . $this->db->escape($data['forwarded_ip']) . "', user_agent = '" . $this->db->escape($data['user_agent']) . "', accept_language = '" . $this->db->escape($data['accept_language']) . "', commission = '" . $this->db->escape($data['commission']) . "', fixed_commission = '" . $this->db->escape($data['fixed_commission']) . "',delivery_date = '" . $this->db->escape(date('Y-m-d', strtotime($data['delivery_date']))) . "',delivery_timeslot = '" . $this->db->escape($data['delivery_timeslot']) . "',  date_added = NOW(), date_modified = NOW()");



                $this->db->query("UPDATE `" . DB_PREFIX . "order` SET "
                        . "shipping_city_id = '" . $this->db->escape((array_key_exists('shipping_city_id', $data) ? $data['shipping_city_id'] : '')) . "', "
                        . "shipping_contact_no = '" . $this->db->escape($data['shipping_contact_no']) . "', "
                        . "shipping_address = '" . $this->db->escape($data['shipping_address']) . "', "
                        . "shipping_flat_number = '" . $this->db->escape($data['shipping_flat_number']) . "', "
                        . "shipping_building_name = '" . $this->db->escape($data['shipping_building_name']) . "', "
                        . "shipping_landmark = '" . $this->db->escape($data['shipping_landmark']) . "', "
                        . "shipping_zipcode = '" . $this->db->escape($data['shipping_zipcode']) . "', "
                        . "shipping_name = '" . $this->db->escape($data['shipping_name']) . "' "
                        . "WHERE order_id='" . $order_id . "'");

                if (isset($data['products'])) {
                    foreach ($data['products'] as $product) {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET vendor_id='" . (int) $product['vendor_id'] . "', store_id='" . (int) $product['store_id'] . "', product_type='" . $product['product_type'] . "', unit='" . $product['unit'] . "', order_id = '" . (int) $order_id . "', variation_id = '" . (int) $product['store_product_variation_id'] . "', product_id = '" . (int) $product['product_store_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (float) $product['quantity'] . "', price = '" . (float) $product['price'] . "', total = '" . (float) $product['total'] . "', tax = '" . (float) $product['tax'] . "', reward = '" . (int) $product['reward'] . "'");
                    }
                }
                if (isset($data['totals'])) {

                    foreach ($data['totals'] as $total) {

                        if (isset($total['actual_value'])) {

                            $this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int) $order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', `value` = '" . (float) $total['value'] . "', `actual_value` = '" . (float) $total['actual_value'] . "', sort_order = '" . (int) $total['sort_order'] . "'");
                        } else {

                            $this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int) $order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', `value` = '" . (float) $total['value'] . "', sort_order = '" . (int) $total['sort_order'] . "'");
                        }
                    }
                }
            }
        } else {
            foreach ($stores as $data) {
                $this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "', store_id = '" . (int) $data['store_id'] . "', store_name = '" . $this->db->escape($data['store_name']) . "', store_url = '" . $this->db->escape($data['store_url']) . "', customer_id = '" . (int) $data['customer_id'] . "', customer_group_id = '" . (int) $data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '' ) . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', payment_code = '" . $this->db->escape($data['payment_code']) . "',shipping_method = '" . $this->db->escape($data['shipping_method']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', comment = '" . $this->db->escape($data['comment']) . "', total = '" . (float) $data['total'] . "', affiliate_id = '" . (int) $data['affiliate_id'] . "',marketing_id = '" . (int) $data['marketing_id'] . "', latitude = '" . $data['latitude'] . "',longitude = '" . $data['longitude'] . "', tracking = '" . $this->db->escape($data['tracking']) . "', language_id = '" . (int) $data['language_id'] . "', currency_id = '" . (int) $data['currency_id'] . "', currency_code = '" . $this->db->escape($data['currency_code']) . "', currency_value = '" . (float) $data['currency_value'] . "', ip = '" . $this->db->escape($data['ip']) . "', forwarded_ip = '" . $this->db->escape($data['forwarded_ip']) . "', user_agent = '" . $this->db->escape($data['user_agent']) . "', fixed_commission = '" . $this->db->escape($data['fixed_commission']) . "',commission = '" . $this->db->escape($data['commission']) . "',delivery_date = '" . $this->db->escape(date('Y-m-d', strtotime($data['delivery_date']))) . "',delivery_timeslot = '" . $this->db->escape($data['delivery_timeslot']) . "',  accept_language = '" . $this->db->escape($data['accept_language']) . "', date_added = NOW(), date_modified = NOW()");
                $order_id = $this->db->getLastId();

                $this->session->data['order_id'][$data['store_id']] = $order_id;


                $this->db->query("UPDATE `" . DB_PREFIX . "order` SET "
                        . "shipping_city_id = '" . $this->db->escape($data['shipping_city_id']) . "', "
                        . "shipping_contact_no = '" . $this->db->escape($data['shipping_contact_no']) . "', "
                        . "shipping_address = '" . $this->db->escape($data['shipping_address']) . "', "
                        . "shipping_flat_number = '" . $this->db->escape($data['shipping_flat_number']) . "', "
                        . "shipping_building_name = '" . $this->db->escape($data['shipping_building_name']) . "', "
                        . "shipping_landmark = '" . $this->db->escape($data['shipping_landmark']) . "', "
                        . "shipping_zipcode = '" . $this->db->escape($data['shipping_zipcode']) . "', "
                        . "shipping_name = '" . $this->db->escape($data['shipping_name']) . "' "
                        . "WHERE order_id='" . $order_id . "'");

                if (isset($data['products'])) {
                    foreach ($data['products'] as $product) {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET vendor_id='" . (int) $product['vendor_id'] . "', store_id='" . (int) $product['store_id'] . "', product_type='" . $product['product_type'] . "', unit='" . $product['unit'] . "', order_id = '" . (int) $order_id . "', variation_id = '" . (int) $product['store_product_variation_id'] . "', product_id = '" . (int) $product['product_store_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (float) $product['quantity'] . "', price = '" . (float) $product['price'] . "', total = '" . (float) $product['total'] . "', tax = '" . (float) $product['tax'] . "', reward = '" . (int) $product['reward'] . "'");
                    }
                }

                if (isset($data['totals'])) {
                    foreach ($data['totals'] as $total) {

                        if (isset($total['actual_value'])) {

                            $this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int) $order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', `value` = '" . (float) $total['value'] . "', `actual_value` = '" . (float) $total['actual_value'] . "', sort_order = '" . (int) $total['sort_order'] . "'");
                        } else {

                            $this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int) $order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', `value` = '" . (float) $total['value'] . "', sort_order = '" . (int) $total['sort_order'] . "'");
                        }
                    }
                }
            }
        }
    }

    public function addMultiOrder($stores) {

        $log = new Log('error.log');
        $log->write('addMultiOrder 1'); 
        /*TIME ZONE ISSUE*/
        $tz = (new DateTime('now', new DateTimeZone('Africa/Nairobi')))->format('P');
        $this->db->query("SET time_zone='$tz';");
        /*TIME ZONE ISSUE*/
        //$log->write($stores);
        $this->trigger->fire('pre.order.add', $stores);


        $orders = isset($this->session->data['order_id']);

        //if ( $orders && count($stores) == count($this->session->data['order_id'])) {
        if ($orders) {
            foreach ($stores as $key => $data) {
                //print_r($this->session->data['order_id']);die;
            // echo "<pre>";print_r($key);die;
            // echo "<pre>";print_r("UPDATE `" . DB_PREFIX . "order` SET paid='Y', amount_partialy_paid = 0  WHERE order_id='" . (int)  $order_id . "'");die;

                $this->deleteOrder($this->session->data['order_id'][$key]);
                $order_id = $this->session->data['order_id'][$key];

                $this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET order_id='" . $order_id . "', invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "', store_id = '" . (int) $data['store_id'] . "', store_name = '" . $this->db->escape($data['store_name']) . "', store_url = '" . $this->db->escape($data['store_url']) . "', customer_id = '" . (int) $data['customer_id'] . "', customer_group_id = '" . (int) $data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', payment_code = '" . $this->db->escape($data['payment_code']) . "',shipping_method = '" . $this->db->escape($data['shipping_method']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', comment = '" . $this->db->escape($data['comment']) . "', total = '" . (float) $data['total'] . "', latitude = '" . $data['latitude'] . "',longitude = '" . $data['longitude'] . "', affiliate_id = '" . (int) $data['affiliate_id'] . "',marketing_id = '" . (int) $data['marketing_id'] . "', tracking = '" . $this->db->escape($data['tracking']) . "', language_id = '" . (int) $data['language_id'] . "', currency_id = '" . (int) $data['currency_id'] . "', currency_code = '" . $this->db->escape($data['currency_code']) . "', currency_value = '" . (float) $data['currency_value'] . "', ip = '" . $this->db->escape($data['ip']) . "', forwarded_ip = '" . $this->db->escape($data['forwarded_ip']) . "', user_agent = '" . $this->db->escape($data['user_agent']) . "', accept_language = '" . $this->db->escape($data['accept_language']) . "', fixed_commission = '" . $this->db->escape($data['fixed_commission']) . "', commission = '" . $this->db->escape($data['commission']) . "',delivery_date = '" . $this->db->escape(date('Y-m-d', strtotime($data['delivery_date']))) . "',delivery_timeslot = '" . $this->db->escape($data['delivery_timeslot']) . "',  date_added = NOW(), date_modified = NOW(), login_latitude = '" . $this->db->escape($data['login_latitude']) . "', login_longitude = '" . $this->db->escape($data['login_longitude']) . "', login_mode = '" . $this->db->escape($data['login_mode']) . "'");
                //cant place directly in insert , due to dependencies

                 //ADDED FOR MULTI VENDOR ORDER
                 if($order_id == NULL) {
                    $order_id = $this->db->getLastId();
                    $this->session->data['order_id'][$key] = $order_id;
                    }

                $order_total_value=0;
                $credit_total_value=0;
                //check wallet update
                foreach ($data['totals'] as $tot) {
                    // echo "<pre>";print_r($tot);die;
                    if($tot['code']=='credit')
                    {
                        $credit_total_value=$tot['value'];
                        if($credit_total_value<0)
                        {
                            $this->db->query('DELETE FROM ' . DB_PREFIX . "customer_credit WHERE customer_id = '" . (int) $data['customer_id'] . "' and  order_id = '" . (int)  $order_id . "'");
                            $this->db->query('INSERT INTO ' . DB_PREFIX . "customer_credit SET customer_id = '" . (int) $data['customer_id'] . "', order_id = '" . (int)  $order_id . "', description = 'Wallet amount deducted', amount = '" . (float) $tot['value'] . "', date_added = NOW()");
                            $this->db->query('UPDATE ' . DB_PREFIX . "order SET paid='Y', amount_partialy_paid = 0 ,total='" . (float) ABS($tot['value']) . "'  WHERE order_id='" . (int)  $order_id . "'");
                            // console.log('UPDATE ' . DB_PREFIX . "order SET paid='Y', amount_partialy_paid = 0  WHERE order_id='" . (int)  $order_id . "'");
                           
                        }
                    }
                    // if($tot['code']=='total')
                    // {
                    //     $order_total_value=$tot['value'];
                    // }

                  }
                //   if($credit_total_value==$order_total_value)//credit amount and order total amount are same, then order is paid order
                //   {
                //     $this->db->query("UPDATE `" . DB_PREFIX . "order` SET paid='Y', amount_partialy_paid = 0  WHERE order_id='" . $order_id . "'");
                //   }
                
                
               
                //ADDED FOR MULTI VENDOR ORDER

                if ($this->session->data['adminlogin'] && $this->session->data['adminlogin'] == 1) {
                    $this->db->query("UPDATE `" . DB_PREFIX . "order` SET isadmin_login = 1  WHERE order_id='" . $order_id . "'");
                }

                $this->db->query("UPDATE `" . DB_PREFIX . "order` SET "
                        . "shipping_city_id = '" . $this->db->escape((array_key_exists('shipping_city_id', $data) ? $data['shipping_city_id'] : '')) . "', "
                        . "shipping_contact_no = '" . $this->db->escape($data['shipping_contact_no']) . "', "
                        . "shipping_address = '" . $this->db->escape($data['shipping_address']) . "', "
                        . "shipping_flat_number = '" . $this->db->escape($data['shipping_flat_number']) . "', "
                        . "shipping_building_name = '" . $this->db->escape($data['shipping_building_name']) . "', "
                        . "shipping_landmark = '" . $this->db->escape($data['shipping_landmark']) . "', "
                        . "shipping_zipcode = '" . $this->db->escape($data['shipping_zipcode']) . "', "
                        . "shipping_name = '" . $this->db->escape($data['shipping_name']) . "' "
                        . "WHERE order_id='" . $order_id . "'");

                if (isset($data['products'])) {
                    foreach ($data['products'] as $product) {
                        $produce_type = '';
                        if (is_array($product) && array_key_exists('produce_type', $product) && is_array($product['produce_type'])) {
                            foreach ($product['produce_type'] as $producttype) {
                                $produce_type = $produce_type . ' ' . $producttype['type'] . '-' . $producttype['value'];
                            }
                        }
                        $log = new Log('error.log');
                        $log->write('PRODUCT NOTE FRONT.MODEL.CHECKOUT.ORDER');
                        $log->write($product['product_note'] . '-' . $product['product_id'] . '-' . $product['name']);
                        $log->write('PRODUCT NOTE FRONT.MODEL.CHECKOUT.ORDER');
                        if ($product['product_note'] == 'undefined' || $product['product_note'] == 'null') {
                            $product['product_note'] = '';
                        }
                        $this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET vendor_id='" . (int) $product['vendor_id'] . "', store_id='" . (int) $product['store_id'] . "', product_type='" . $product['product_type'] . "',product_note='" . $product['product_note'] . "', general_product_id='" . $product['product_id'] . "', unit='" . $product['unit'] . "', order_id = '" . (int) $order_id . "', variation_id = '" . (int) $product['store_product_variation_id'] . "', product_id = '" . (int) $product['product_store_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (float) $product['quantity'] . "', price = '" . (float) $product['price'] . "', total = '" . (float) $product['total'] . "', tax = '" . (float) $product['tax'] . "', reward = '" . (int) $product['reward'] . "', produce_type = '" . $this->db->escape($produce_type) . "'");
                    }
                }
                if (isset($data['totals'])) {
                    foreach ($data['totals'] as $total) {

                        if($total['code']=='total')
                        {
                            $value=$total['value'];
                            if(isset($credit_total_value))
                            {
                                $value +=abs($credit_total_value);
                            }
                            if (isset($total['actual_value'])) {
                                $this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int) $order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', `value` = '" . (float) $value . "', `actual_value` = '" . (float) $total['actual_value'] . "', sort_order = '" . (int) $total['sort_order'] . "'");
                            } else {
    
                                $this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int) $order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', `value` = '" . (float) $value . "', sort_order = '" . (int) $total['sort_order'] . "'");
                            }
                        }
                        else{
                        if (isset($total['actual_value'])) {
                            $this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int) $order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', `value` = '" . (float) $total['value'] . "', `actual_value` = '" . (float) $total['actual_value'] . "', sort_order = '" . (int) $total['sort_order'] . "'");
                        } else {

                            $this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int) $order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', `value` = '" . (float) $total['value'] . "', sort_order = '" . (int) $total['sort_order'] . "'");
                        }
                        }
                    }
                }
            }
        } else {

            //   echo "<pre>";print_r($stores);die;

            foreach ($stores as $data) {
                $this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "', store_id = '" . (int) $data['store_id'] . "', store_name = '" . $this->db->escape($data['store_name']) . "', store_url = '" . $this->db->escape($data['store_url']) . "', customer_id = '" . (int) $data['customer_id'] . "', customer_group_id = '" . (int) $data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '' ) . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', payment_code = '" . $this->db->escape($data['payment_code']) . "',shipping_method = '" . $this->db->escape($data['shipping_method']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', comment = '" . $this->db->escape($data['comment']) . "', total = '" . (float) $data['total'] . "', affiliate_id = '" . (int) $data['affiliate_id'] . "',marketing_id = '" . (int) $data['marketing_id'] . "', latitude = '" . $data['latitude'] . "',longitude = '" . $data['longitude'] . "', tracking = '" . $this->db->escape($data['tracking']) . "', language_id = '" . (int) $data['language_id'] . "', currency_id = '" . (int) $data['currency_id'] . "', currency_code = '" . $this->db->escape($data['currency_code']) . "', currency_value = '" . (float) $data['currency_value'] . "', ip = '" . $this->db->escape($data['ip']) . "', forwarded_ip = '" . $this->db->escape($data['forwarded_ip']) . "', user_agent = '" . $this->db->escape($data['user_agent']) . "', fixed_commission = '" . $this->db->escape($data['fixed_commission']) . "',commission = '" . $this->db->escape($data['commission']) . "',delivery_date = '" . $this->db->escape(date('Y-m-d', strtotime($data['delivery_date']))) . "',delivery_timeslot = '" . $this->db->escape($data['delivery_timeslot']) . "',  accept_language = '" . $this->db->escape($data['accept_language']) . "', date_added = NOW(), date_modified = NOW()");
                $order_id = $this->db->getLastId();
                // echo "<pre>";print_r($data);die;
                $this->session->data['order_id'][$data['store_id']] = $order_id;


                $order_total_value=0;
                $credit_total_value=0;
                //check wallet update
                foreach ($data['totals'] as $tot) {
                    // echo "<pre>";print_r($tot);die;
                    if($tot['code']=='credit')
                    {
                        $credit_total_value=$tot['value'];
                        if($credit_total_value<0)
                        {
                            //as the same method is calling multiple times, delete if credit record is available
                        $this->db->query('DELETE FROM ' . DB_PREFIX . "customer_credit WHERE customer_id = '" . (int) $data['customer_id'] . "' and  order_id = '" . (int)  $order_id . "'");

                        $this->db->query('INSERT INTO ' . DB_PREFIX . "customer_credit SET customer_id = '" . (int) $data['customer_id'] . "', order_id = '" . (int)  $order_id . "', description = 'Wallet amount deducted', amount = '" . (float) $tot['value'] . "', date_added = NOW()");
                        // $this->db->query("UPDATE `" . DB_PREFIX . "order` SET paid='Y', amount_partialy_paid = 0  WHERE order_id='" . (int)  $order_id . "'");
                        $this->db->query('UPDATE ' . DB_PREFIX . "order SET paid='Y', amount_partialy_paid = 0 ,total='" . (float) ABS($tot['value']) . "'  WHERE order_id='" . (int)  $order_id . "'");
                        // console.log('UPDATE ' . DB_PREFIX . "order SET paid='Y', amount_partialy_paid = 0  WHERE order_id='" . (int)  $order_id . "'");
                        }
                    }
                    // if($tot['code']=='total')
                    // {
                    //     $order_total_value=$tot['value'];
                    // }

                     //   if($credit_total_value==$order_total_value)//credit amount and order total amount are same, then order is paid order
                //   {
                //     $this->db->query("UPDATE `" . DB_PREFIX . "order` SET paid='Y', amount_partialy_paid = 0  WHERE order_id='" . $order_id . "'");
                //   }

                  }
               

                $this->db->query("UPDATE `" . DB_PREFIX . "order` SET "
                        . "shipping_city_id = '" . $this->db->escape((array_key_exists('shipping_city_id', $data) ? $data['shipping_city_id'] : '')) . "', "
                        . "shipping_contact_no = '" . $this->db->escape($data['shipping_contact_no']) . "', "
                        . "shipping_address = '" . $this->db->escape($data['shipping_address']) . "', "
                        . "shipping_flat_number = '" . $this->db->escape($data['shipping_flat_number']) . "', "
                        . "shipping_building_name = '" . $this->db->escape($data['shipping_building_name']) . "', "
                        . "shipping_landmark = '" . $this->db->escape($data['shipping_landmark']) . "', "
                        . "shipping_zipcode = '" . $this->db->escape($data['shipping_zipcode']) . "', "
                        . "shipping_name = '" . $this->db->escape($data['shipping_name']) . "' "
                        . "WHERE order_id='" . $order_id . "'");

                if (isset($data['products'])) {
                    foreach ($data['products'] as $product) {
                        $produce_type = '';
                        if (is_array($product) && array_key_exists('produce_type', $product) && is_array($product['produce_type'])) {
                            foreach ($product['produce_type'] as $producttype) {
                                $produce_type = $produce_type . ' ' . $producttype['type'] . '-' . $producttype['value'];
                            }
                        }
                        $log = new Log('error.log');
                        $log->write('PRODUCT NOTE FRONT.MODEL.CHECKOUT.ORDER');
                        $log->write($product['product_note'] . '-' . $product['product_id'] . '-' . $product['name']);
                        $log->write('PRODUCT NOTE FRONT.MODEL.CHECKOUT.ORDER');
                        if ($product['product_note'] == 'undefined' || $product['product_note'] == 'null') {
                            $product['product_note'] = '';
                        }
                        $this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET vendor_id='" . (int) $product['vendor_id'] . "', store_id='" . (int) $product['store_id'] . "', product_type='" . $product['product_type'] . "',product_note='" . $product['product_note'] . "', general_product_id='" . $product['product_id'] . "', unit='" . $product['unit'] . "', order_id = '" . (int) $order_id . "', variation_id = '" . (int) $product['store_product_variation_id'] . "', product_id = '" . (int) $product['product_store_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (float) $product['quantity'] . "', price = '" . (float) $product['price'] . "', total = '" . (float) $product['total'] . "', tax = '" . (float) $product['tax'] . "', reward = '" . (int) $product['reward'] . "', produce_type = '" . $this->db->escape($produce_type) . "'");
                    }
                }

                if (isset($data['totals'])) {



                    foreach ($data['totals'] as $total) {

                        if($total['code']=='total')
                        {
                            $value=$total['value'];
                            if(isset($credit_total_value))
                            {
                                $value +=abs($credit_total_value);
                            }
                            if (isset($total['actual_value'])) {
                                $this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int) $order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', `value` = '" . (float) $value . "', `actual_value` = '" . (float) $total['actual_value'] . "', sort_order = '" . (int) $total['sort_order'] . "'");
                            } else {
    
                                $this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int) $order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', `value` = '" . (float) $value . "', sort_order = '" . (int) $total['sort_order'] . "'");
                            }
                        }
                        else{
                        if (isset($total['actual_value'])) {

                            $this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int) $order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', `value` = '" . (float) $total['value'] . "', `actual_value` = '" . (float) $total['actual_value'] . "', sort_order = '" . (int) $total['sort_order'] . "'");
                        } else {

                            $this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int) $order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', `value` = '" . (float) $total['value'] . "', sort_order = '" . (int) $total['sort_order'] . "'");
                        }
                    }
                    }
                }
            }
        }
    }

    public function editOrder($order_id, $data) {

        $this->trigger->fire('pre.order.edit', $data);

        // Void the order first
        $this->addOrderHistory($order_id, 0, '', true, '', '');

        $store_id = 0;

        $this->db->query("UPDATE `" . DB_PREFIX . "order` SET "
                . "invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "', "
                . "store_id = '" . (int) $data['store_id'] . "', "
                . "store_name = '" . $this->db->escape($data['store_name']) . "', "
                . "store_url = '" . $this->db->escape($data['store_url']) . "', "
                . "customer_id = '" . (int) $data['customer_id'] . "', "
                . "customer_group_id = '" . (int) $data['customer_group_id'] . "', "
                . "firstname = '" . $this->db->escape($data['firstname']) . "', "
                . "lastname = '" . $this->db->escape($data['lastname']) . "', "
                . "email = '" . $this->db->escape($data['email']) . "', "
                . "telephone = '" . $this->db->escape($data['telephone']) . "', "
                . "fax = '" . $this->db->escape($data['fax']) . "', "
                . "custom_field = '" . $this->db->escape(serialize($data['custom_field'])) . "', "
                . "payment_method = '" . $this->db->escape($data['payment_method']) . "', "
                . "payment_code = '" . $this->db->escape($data['payment_code']) . "', "
                . "shipping_method = '" . $this->db->escape($data['shipping_method']) . "', "
                . "shipping_code = '" . $this->db->escape($data['shipping_code']) . "', "
                . "shipping_city_id = '" . $this->db->escape($data['shipping_city_id']) . "', "
                . "shipping_contact_no = '" . $this->db->escape($data['shipping_contact_no']) . "', "
                . "shipping_address = '" . $this->db->escape($data['shipping_address']) . "', "
                . "shipping_name = '" . $this->db->escape($data['shipping_name']) . "', "
                . "comment = '" . $this->db->escape($data['comment']) . "', "
                . "total = '" . (float) $data['total'] . "', "
                . "commission = '" . (float) $data['commission'] . "', "
                . "fixed_commission = '" . (float) $data['fixed_commission'] . "', "
                . "delivery_date = '" . date('Y-m-d', strtotime($data['delivery_date'])) . "', "
                . "delivery_timeslot = '" . $data['delivery_timeslot'] . "', "
                . "date_modified = NOW()"
                . "WHERE order_id = '" . (int) $order_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int) $order_id . "'");

        $stores = array();

        // Products
        if (isset($data['products'])) {
            foreach ($data['products'] as $product) {

                $produce_type = '';
                foreach ($product['produce_type'] as $producttype) {
                    $produce_type = $produce_type . ' ' . $producttype['type'] . '-' . $producttype['value'];
                }
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET vendor_id='" . (int) $product['vendor_id'] . "', store_id='" . (int) $product['store_id'] . "', product_type='" . $product['product_type'] . "', order_id = '" . (int) $order_id . "', variation_id = '" . (int) $product['store_product_variation_id'] . "', product_id = '" . (int) $product['product_store_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (float) $product['quantity'] . "', price = '" . (float) $product['price'] . "', total = '" . (float) $product['total'] . "', tax = '" . (float) $product['tax'] . "', reward = '" . (int) $product['reward'] . "', produce_type = '" . $this->db->escape($produce_type) . "'");
            }
        }

        // Totals
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int) $order_id . "'");

        if (isset($data['totals'])) {
            foreach ($data['totals'] as $total) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int) $order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', `value` = '" . (float) $total['value'] . "', sort_order = '" . (int) $total['sort_order'] . "'");
            }
        }

        $this->trigger->fire('post.order.edit', $order_id);
    }

    public function deleteOrder($order_id) {
        $this->trigger->fire('pre.order.delete', $order_id);
        $log = new Log('error.log');
        // Void the order first
        $log->write('deleteorder 1');
        $log->write($order_id);
        $this->addOrderHistory($order_id, 0, '', true, '', '');

        $this->db->query("DELETE FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int) $order_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "order_custom_field` WHERE order_id = '" . (int) $order_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "order_fraud` WHERE order_id = '" . (int) $order_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "order_history` WHERE order_id = '" . (int) $order_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "order_option` WHERE order_id = '" . (int) $order_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "order_product` WHERE order_id = '" . (int) $order_id . "'");
        $this->db->query("DELETE `or`, ort FROM `" . DB_PREFIX . "order_recurring` `or`, `" . DB_PREFIX . "order_recurring_transaction` `ort` WHERE order_id = '" . (int) $order_id . "' AND ort.order_recurring_id = `or`.order_recurring_id");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "order_request` WHERE order_id = '" . (int) $order_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int) $order_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "order_voucher` WHERE order_id = '" . (int) $order_id . "'");
        //$this->db->query( "DELETE `po`, pot FROM `" . DB_PREFIX . "paypal_order` `po`, `" . DB_PREFIX . "paypal_order_transaction` `pot` WHERE order_id = '" . (int) $order_id . "' AND pot.paypal_order_id = `po`.paypal_order_id" );
        // Gift Voucher
        $this->load->model('checkout/voucher');

        $this->model_checkout_voucher->disableVoucher($order_id);

        $this->trigger->fire('post.order.delete', $order_id);
    }

    public function getOrder($order_id) {
        $order_query = $this->db->query("SELECT *, (SELECT os.name FROM `" . DB_PREFIX . "order_status` os WHERE os.order_status_id = o.order_status_id AND os.language_id = o.language_id) AS order_status FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int) $order_id . "'");

        if ($order_query->num_rows) {

            $this->load->model('localisation/language');
            $this->load->model('account/order');

            $language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);

            $city_name = $this->model_account_order->getCityName($order_query->row['shipping_city_id']);
            $state_name = $this->model_account_order->getCityState($order_query->row['shipping_city_id']);

            if ($language_info) {
                $language_code = $language_info['code'];
                $language_directory = $language_info['directory'];
            } else {
                $language_code = '';
                $language_directory = '';
            }

            return array(
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
                'shipping_state' => $state_name,
                'latitude' => $order_query->row['latitude'],
                'longitude' => $order_query->row['longitude'],
                'payment_method' => $order_query->row['payment_method'],
                'payment_code' => $order_query->row['payment_code'],
                'comment' => $order_query->row['comment'],
                'total' => $order_query->row['total'],
                'order_status_id' => $order_query->row['order_status_id'],
                'order_status' => $order_query->row['order_status'],
                'affiliate_id' => $order_query->row['affiliate_id'],
                'commission' => $order_query->row['commission'],
                'fixed_commission' => $order_query->row['fixed_commission'],
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
                'eta_date' => date($this->language->get('date_format'), strtotime($order_query->row['delivery_date'])),
                'delivery_date_formatted' => date($this->language->get('date_format_short'), strtotime($order_query->row['delivery_date'])),
                'delivery_timeslot' => $order_query->row['delivery_timeslot'],
                'dropoff_latitude' => $order_query->row['latitude'],
                'dropoff_longitude' => $order_query->row['longitude'],
                'amount_partialy_paid'=> $order_query->row['amount_partialy_paid'],
                'paid'=> $order_query->row['paid']
                    /* 'date_modified' => $order_query->row['date_modified'],
                      'date_added' => $order_query->row['date_added'] */
            );
        } else {
            return false;
        }
    }

    public function addOrderHistory($order_id, $order_status_id, $comment = '', $notify = true, $added_by = '', $added_by_role = '', $other_vendor_terms = null, $paid = '') {//dont pass default valiue to paid

        //$notify = true;
        $log = new Log('error.log');
        $log->write('mod loop addOrderHistory' . $order_id);
        $this->trigger->fire('pre.order.history.add', $order_id);

        $order_info = $this->getOrder($order_id);

        $pdf_link = '';

        if (isset($this->session->data['result_iugu_pdf_link'])) {
            $pdf_link = $this->session->data['result_iugu_pdf_link'];
        }

        $log = new Log('error.log');

        if ($order_info && $order_info['order_status_id'] != $order_status_id) {
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

            $log->write($safe);
            // Ban IP
            if (!$safe) {
                $log->write('addOrderHistory not safe');
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

                $log->write('status' . $status);
                if ($status) {
                    $order_status_id = $this->config->get('config_order_status_id');
                }
            }

            $log->write($order_status_id);
            $log->write('addOrderHistory 2');

            $ReadyForPickupStatus = false;
            $pickupStatus = $this->config->get('config_ready_for_pickup_status');

            if (is_array($pickupStatus) && count($pickupStatus) > 0) {
                foreach ($pickupStatus as $pickupStat) {

                    if ($order_status_id == $pickupStat) {
                        $ReadyForPickupStatus = true;
                    }
                }
            }



            if ($ReadyForPickupStatus) {

                $log->write("ReadyForPickupStatus elsex");
                $deliverSystemStatus = $this->config->get('config_deliver_system_status');

                $checkoutDeliverSystemStatus = $this->config->get('config_checkout_deliver_system_status');

                $deliverSystemStatusForShipping = false;

                $log->write($deliverSystemStatus . "erf" . $checkoutDeliverSystemStatus);

                if ($deliverSystemStatus && !$checkoutDeliverSystemStatus) {

                    $log->write("ReadyForPickupStatus elsex yes");
                    $allowedShippingMethods = $this->config->get('config_delivery_shipping_methods_status');

                    $log->write($allowedShippingMethods);

                    if (is_array($allowedShippingMethods) && count($allowedShippingMethods) > 0) {
                        foreach ($allowedShippingMethods as $method) {

                            /* if($order_info['shipping_code'] == $method.".".$method) {
                              $deliverSystemStatus = true;
                              $deliverSystemStatusForShipping = true;
                              } */

                            $p = explode(".", $order_info['shipping_code']);

                            if ($p[0] == $method) {
                                $deliverSystemStatus = true;
                                $deliverSystemStatusForShipping = true;
                            }
                        }
                    }
                } else {
                    $deliverSystemStatus = false;
                }


                if ($deliverSystemStatus && $deliverSystemStatusForShipping) {
                    $log->write("kondutoStatus else");
                    //$this->createDeliveryRequest($order_id,$order_status_id);
                } else {
                    $log->write("deliverSystemStatus elsex");
                }
            }
            
            if ($other_vendor_terms != NULL) {
            $log->write('accept_vendor_terms');
            $log->write($other_vendor_terms);
            $log->write('accept_vendor_terms');
                if($paid!='')
                {
                $this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int) $order_status_id . "', order_pdf_link ='" . $pdf_link . "', vendor_terms_cod ='" . (int) $other_vendor_terms . "', paid ='" .  $paid . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");
                
                }
                else {
                $this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int) $order_status_id . "', order_pdf_link ='" . $pdf_link . "', vendor_terms_cod ='" . (int) $other_vendor_terms . "',  date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");
                    
                }
            } else {
                if($paid!='')
                {
            $this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int) $order_status_id . "', order_pdf_link ='" . $pdf_link . "', paid ='" .  $paid . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");    
            
            }
            else {
                $this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int) $order_status_id . "', order_pdf_link ='" . $pdf_link . "',  date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");    

            }
        }

            $this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int) $order_id . "', added_by = '" . (int) $added_by . "', role = '" . $added_by_role . "', order_status_id = '" . (int) $order_status_id . "', notify = '" . (int) $notify . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");


            // If current order status is not processing or complete but new status is processing or complete then commence completing the order
            //print_r($order_info['order_status_id']);

            if (in_array($order_status_id, $this->config->get('config_processing_status'))) {

                // Stock subtraction
                $order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");


                foreach ($order_product_query->rows as $order_product) {
                    $this->db->query("UPDATE " . DB_PREFIX . "product_to_store SET quantity = (quantity - " . (float) $order_product['quantity'] . ") WHERE product_store_id = '" . (int) $order_product['product_id'] . "' AND subtract_quantity = '1'");
                }

                // Redeem coupon, vouchers and reward points
                $order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int) $order_id . "' ORDER BY sort_order ASC");

                $log = new Log('error.log');

                //$log->write('PayPal Express debug '. print_r($order_total_query));

                $log->write('processnig inside');

                foreach ($order_total_query->rows as $order_total) {
                    $this->load->model('total/' . $order_total['code']);

                    /* $log->write('PayPal Express debug '. $order_total['code']."s"); */
                    if (method_exists($this->{'model_total_' . $order_total['code']}, 'confirm')) {
                        //$this->logger();
                        // $log->write('PayPal Express debug '. $order_total['code']."s");
                        $this->{'model_total_' . $order_total['code']}->confirm($order_info, $order_total);
                    }
                }
            }


            // If old order status is the processing or complete status but new status is not then commence restock, and remove coupon, voucher and reward history
            if (in_array($order_status_id, $this->config->get('config_refund_status'))) {
                // Restock

                $product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");

                foreach ($product_query->rows as $product) {
                    $this->db->query("UPDATE `" . DB_PREFIX . "product_to_store` SET quantity = (quantity + " . (float) $product['quantity'] . ") WHERE product_store_id = '" . (int) $product['product_id'] . "' AND subtract_quantity = '1'");
                }
                // Remove coupon, vouchers and reward points history
                $this->load->model('account/order');

                $order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int) $order_id . "' ORDER BY sort_order ASC");

                $log->write('insisde refund status');
                foreach ($order_total_query->rows as $order_total) {
                    $this->load->model('total/' . $order_total['code']);

                    if (method_exists($this->{'model_total_' . $order_total['code']}, 'unconfirm')) {
                        $this->{'model_total_' . $order_total['code']}->unconfirm($order_id);
                    }
                }

                // Remove credit and reward points recieved on order complete
                $this->load->model('total/credit');
                $this->load->model('total/reward');

                $this->model_total_credit->unconfirm($order_id);
                $this->model_total_reward->unconfirm($order_id);
            }

            $log->write('refund outer');

            // 11 refunded and 6 cancelled,16 order rejected. refund and cancel logic
            if (in_array($order_status_id, $this->config->get('config_refund_status'))) {
                $log->write('refund if');

                //check if payment paid by wallet
                $this->load->model('account/order');
                $totals_info = $this->model_account_order->getOrderTotals($order_id);
     
                $credit_refund = 0;
                foreach ($totals_info as $total) {           
    
                    if ('credit' == $total['code']) {
                        $credit_refund = $total['value'];
                    }
                     
                }
                // echo "<pre>";print_r($totals_info);    
                if($credit_refund!=0)//as the order is cancelled, if  any cart amount deducted, then need to rever it
                {
                    // echo "<pre>";print_r($totals_info);die;                   
                    $this->load->model('total/credit');
                     $this->model_total_credit->addOnlyCredit($order_info['customer_id'],'Refund of order #'.$order_id,abs($credit_refund),$order_id);
                }    
                //  echo "<pre>";print_r('$totals_info');die;
                //end of wallet payment check

                //check if payment done via iugu then call refund API
                if ($order_info['payment_code'] == 'iugu_credit_card') {
                    //refund successfull
                    $log->write('refund another if');
                    $this->refundIuguAPI($order_id);
                }

                // update delviery system order status
                $this->sendCancelOrderStatus($order_id);

                // refund to customer wallet
                $this->refundToCustomerWallet($order_id);
            }


            $log->write('refund end');
            // refund and cancel logic end
            $this->cache->delete('product');

            //this is solely used to send mails
            //if ( in_array( $order_status_id, array_merge( $this->config->get( 'config_processing_status' ), $this->config->get( 'config_complete_status' ) ) ) ) {
            try {
            if ($notify) {

                //this is solely used to send mails

                $order_status = $this->db->query("SELECT name FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int) $order_status_id . "' AND language_id = '" . (int) $order_info['language_id'] . "'");

                if ($order_status->num_rows) {
                    $order_status = $order_status->row['name'];
                } else {
                    $order_status = '';
                }

                // Account Href
                $order_href = '';
                $order_pdf_href = '';

                if ($order_info['customer_id']) {
                    $order_href = $order_info['store_url'] . 'index.php?path=account/order/info&order_id=' . $order_info['order_id'];
                }

                //Address Shipping and Payment
                $totals = array();
                $tax_amount = 0;

                if (strlen($order_info['shipping_name']) <> 0) {
                    $address = $order_info['shipping_name'] . '<br />' . $order_info['shipping_address'] . '<br /><b>Contact No.:</b> ' . $order_info['shipping_contact_no'];
                } else {
                    $address = '';
                }

                $payment_address = '';

                $order_total = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int) $order_info['order_id'] . "'");

                foreach ($order_total->rows as $total) {
                    $totals[$total['code']][] = array(
                        'title' => $total['title'],
                        'text' => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
                        'value' => $total['value']
                    );

                    if ($total['code'] == 'tax') {
                        $tax_amount += $total['value'];
                    }
                }

                $log = new Log('error.log');

                $this->load->model('account/order');

                $special = NULL;

                $data = array(
                    'template_id' => 'order_' . (int) $order_status_id,
                    'order_info' => $order_info,
                    'address' => $address,
                    'payment_address' => $payment_address,
                    'special' => $special,
                    'order_href' => $order_href,
                    'order_pdf_href' => $order_pdf_href,
                    'order_status' => $order_status,
                    'totals' => $totals,
                    'tax_amount' => $tax_amount,
                    'order_id' => $order_id,
                    'invoice_no' => !empty($invoice_no) ? $invoice_no : ''
                );


                $getTotal = $order_total->rows;

                $textData = array(
                    'order_info' => $order_info,
                    'order_id' => $order_id,
                    'order_status' => $order_status,
                    'comment' => $comment,
                    'notify' => $notify,
                    'getProdcuts' => $this->getOrderProducts($order_id),
                    'getVouchers' => array(),
                    'getTotal' => $getTotal
                );

                //$text = $this->emailtemplate->getText( 'Order', 'order', $textData );
                //Send Email
                //if ( ( !$order_info['order_status_id'] && $order_status_id ) || ( $order_info['order_status_id'] && $order_status_id && $notify ) ) {
                if ($notify) {

                    $log->write('in if');


                    $log->write("cust orderData");
                    //$log->write($data);

                    /* customer mail/sms/notificaiton start */
                    $subject = $this->emailtemplate->getSubject('OrderAll', 'order_' . (int) $order_status_id, $data);
                    $message = $this->emailtemplate->getMessage('OrderAll', 'order_' . (int) $order_status_id, $data);
                    $sms_message = $this->emailtemplate->getSmsMessage('OrderAll', 'order_' . (int) $order_status_id, $data);

                    //$log->write($message);
                    //echo "<pre>";print_r($message);die;
                    // try{
                    if ($customer_info['email_notification'] == 1 && $this->emailtemplate->getEmailEnabled('OrderAll', 'order_' . (int) $order_status_id)) {


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
                    // }
                    // catch(exception $ex)
                    // {
                    //     $log->write('Order History Mail Error');
                    //     $log->write($ex);
                    // }



                    if ($customer_info['sms_notification'] == 1 && $this->emailtemplate->getSmsEnabled('OrderAll', 'order_' . (int) $order_status_id)) {

                        $ret = $this->emailtemplate->sendmessage($order_info['telephone'], $sms_message);
                    }

                    $log->write('outside mobi noti');
                    if ($this->emailtemplate->getNotificationEnabled('OrderAll', 'order_' . (int) $order_status_id)) {

                        $log->write('status enabled of mobi noti');
                        $mobile_notification_template = $this->emailtemplate->getNotificationMessage('OrderAll', 'order_' . (int) $order_status_id, $data);

                        //$log->write($mobile_notification_template);

                        $mobile_notification_title = $this->emailtemplate->getNotificationTitle('OrderAll', 'order_' . (int) $order_status_id, $data);

                        //$log->write($mobile_notification_title);
                        // customer push notitification start

                        if (isset($customer_info) && isset($customer_info['device_id']) && $customer_info['mobile_notification'] == 1 && strlen($customer_info['device_id']) > 0) {

                            $log->write('customer device id set FRONT.MODEL.CHECKOUT.ORDER');
                            $ret = $this->emailtemplate->sendPushNotification($order_info['customer_id'], $customer_info['device_id'], $order_id, $order_info['store_id'], $mobile_notification_title, $mobile_notification_template, 'com.instagolocal.showorder');

                            $this->saveVendorNotification($order_info['customer_id'], $customer_info['device_id'], $order_id, $mobile_notification_template, $mobile_notification_title);
                        } else {
                            $log->write('customer device id not set FRONT.MODEL.CHECKOUT.ORDER');
                        }

                        // customer push notitification end

                        /* $temporaryVendorInfo = $this->db->query('select * from '.DB_PREFIX.'order LEFT JOIN '.DB_PREFIX.'store on('.DB_PREFIX.'store.store_id = '.DB_PREFIX.'order.store_id) WHERE '.DB_PREFIX.'order.store_id="'.$order_info['store_id'].'" AND order_id="'.$order_id.'"')->row;

                          $vendorData = $this->getVendorDetails($temporaryVendorInfo['vendor_id']);

                          $log->write($vendorData);

                          if(isset($vendorData['device_id']) && strlen($vendorData['device_id']) > 0 ) {

                          $log->write('device id set');
                          $ret =  $this->emailtemplate->sendPushNotification($temporaryVendorInfo['vendor_id'],$vendorData['device_id'],$order_id,$order_info['store_id'],$mobile_notification_template,$mobile_notification_title);

                          $this->saveVendorNotification($temporaryVendorInfo['vendor_id'],$vendorData['device_id'],$order_id,$mobile_notification_template,$mobile_notification_title);
                          } else {
                          $log->write('device id not set');
                          }
                         */
                        // vendor push notitification end
                    }

                    /* customer mail/sms/notificaiton end */

                    /* vendor mail sending */

                    $tempVendorInfo = $this->db->query('select * from ' . DB_PREFIX . 'order LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = ' . DB_PREFIX . 'order.store_id) WHERE ' . DB_PREFIX . 'order.store_id="' . $order_info['store_id'] . '" AND order_id="' . $order_id . '"')->row;

                    $vendorData = $this->getVendorDetails($tempVendorInfo['vendor_id']);



                    $this->load->model('account/order');

                    $store_details = $this->model_account_order->getStoreById($order_info['store_id']);


                    // checking if vendor mail is on


                    $log->write("vendorData");
                    $log->write($store_details['email']);
                    $log->write($order_status_id);

                    //$text = $this->emailtemplate->getText( 'Order', 'order', $textData );

                    $vendor_sms_message = $this->emailtemplate->getSmsMessage('VendorOrder', 'vendororder_' . (int) $order_status_id, $data);


                    if (isset($vendorData['email']) && $this->emailtemplate->getEmailEnabled('VendorOrder', 'vendororder_' . (int) $order_status_id) && (in_array($order_status_id, $this->config->get('config_processing_status')) || in_array($order_status_id, $this->config->get('config_complete_status')))) {

                        // 7 merchant mail
                        $log->write('vendot if');
                        $vendorData['order_id'] = $order_id;
                        $vendorData['order_link'] = HTTPS_ADMIN . 'index.php?path=sale/order/info&order_id=' . $order_id;

                        $log->write('1');
                        //$subject = $this->emailtemplate->getSubject('VendorOrder', 'vendororder_'. (int) $order_status_id, $vendorData);
                        $subject = $this->emailtemplate->getSubject('VendorOrder', 'vendororder_' . (int) $order_status_id, $data);

                        $log->write('2');
                        //$message = $this->emailtemplate->getMessage('VendorOrder', 'vendororder_'. (int) $order_status_id, $vendorData);
                        $message = $this->emailtemplate->getMessage('VendorOrder', 'vendororder_' . (int) $order_status_id, $data);

                        $mail = new mail($this->config->get('config_mail'));


                        $mail->setTo($vendorData['email']);
                        $mail->setFrom($this->config->get('config_from_email'));
                        $mail->setSender($this->config->get('config_name'));
                        $mail->setSubject($subject);
                        $mail->setHtml($message);

                        $mail->send();

                        $log->write("end");
                    }

                    if (isset($store_details['email']) && $this->emailtemplate->getEmailEnabled('VendorOrder', 'vendororder_' . (int) $order_status_id)) {
                        // 7 merchant mail
                        $log->write('vendot if');
                        $vendorData['order_id'] = $order_id;
                        $vendorData['order_link'] = HTTPS_ADMIN . 'index.php?path=sale/order/info&order_id=' . $order_id;

                        $log->write('1');
                        //$subject = $this->emailtemplate->getSubject('VendorOrder', 'vendororder_'. (int) $order_status_id, $vendorData);
                        $subject = $this->emailtemplate->getSubject('VendorOrder', 'vendororder_' . (int) $order_status_id, $data);

                        $log->write('2');
                        //$message = $this->emailtemplate->getMessage('VendorOrder', 'vendororder_'. (int) $order_status_id, $vendorData);
                        $message = $this->emailtemplate->getMessage('VendorOrder', 'vendororder_' . (int) $order_status_id, $data);

                        $mail = new mail($this->config->get('config_mail'));


                        /* $mail->setTo($store_details['email']);

                          $mail->setFrom($this->config->get('config_from_email'));


                          $mail->setSender($this->config->get('config_name'));


                          $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));


                          $mail->setHtml(html_entity_decode(strip_tags($message), ENT_QUOTES, 'UTF-8')); */

                        $mail->setTo($store_details['email']);
                        $mail->setFrom($this->config->get('config_from_email'));
                        $mail->setSender($this->config->get('config_name'));
                        $mail->setSubject($subject);
                        $mail->setHtml($message);
                        //$mail->setText( $text );
                        $mail->send();

                        $log->write("end");
                    }

                    $log->write('vendro end');


                    if ($this->emailtemplate->getNotificationEnabled('VendorOrder', 'vendororder_' . (int) $order_status_id)) {

                        $log->write('status enabled of mobi noti');

                        $mobile_notification_template = $this->emailtemplate->getNotificationMessage('VendorOrder', 'vendororder_' . (int) $order_status_id, $data);

                        //$log->write($mobile_notification_template);

                        $mobile_notification_title = $this->emailtemplate->getNotificationTitle('VendorOrder', 'vendororder_' . (int) $order_status_id, $data);

                        //$log->write($mobile_notification_title);

                        $temporaryVendorInfo = $this->db->query('select * from ' . DB_PREFIX . 'order LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = ' . DB_PREFIX . 'order.store_id) WHERE ' . DB_PREFIX . 'order.store_id="' . $order_info['store_id'] . '" AND order_id="' . $order_id . '"')->row;

                        $vendorData = $this->getVendorDetails($temporaryVendorInfo['vendor_id']);

                        //$log->write($vendorData);

                        if (isset($vendorData['device_id']) && strlen($vendorData['device_id']) > 0) {

                            $log->write('VENDOR MOBILE PUSH NOTIFICATION device id set front.model.checkout.order');

                            $notification_id = $this->saveVendorNotification($temporaryVendorInfo['vendor_id'], $vendorData['device_id'], $order_id, $mobile_notification_template, $mobile_notification_title);

                            $sen['notification_id'] = $notification_id;
                            $log->write('title:' . $mobile_notification_title);
                            $log->write('template:' . $mobile_notification_template);
                            $log->write('order status id:' . $order_status_id);

                            $ret = $this->emailtemplate->sendOrderVendorPushNotification($temporaryVendorInfo['vendor_id'], $vendorData['device_id'], $order_id, $order_info['store_id'], $mobile_notification_title, $mobile_notification_template, $sen);
                        } else {
                            $log->write('VENDOR MOBILE PUSH NOTIFICATION device id not set front.model.checkout.order');
                        }

                        // vendor push notitification end 
                    }

                    if ($this->emailtemplate->getSmsEnabled('VendorOrder', 'vendororder_' . (int) $order_status_id)) {

                        $ret = $this->emailtemplate->sendmessage($store_details['telephone'], $vendor_sms_message);
                    }

                    $log->write('vendro end 1');
                    /* vendor mail end */


                    /* if ( $this->config->get( 'config_order_mail' ) ) {
                      if ( !isset( $mail ) ) {
                      $mail = new mail( $this->config->get( 'config_mail' ) );
                      $mail->setTo( $order_info['email'] );
                      $mail->setFrom( $this->config->get('config_from_email') );
                      $mail->setSender( $order_info['store_name'] );
                      }

                      $mail->setHTML( $message );


                      //$mail->setText( $text );
                      $mail->setTo( $this->config->get( 'config_email' ) );
                      $mail->send();

                      $emails = explode( ',', $this->config->get( 'config_alert_emails' ) );

                      foreach ( $emails as $email ) {
                      if ( filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
                      $mail->setTo( $email );
                      $mail->send();
                      }
                      }
                      } */
                }
            }
            }
            catch(exception $ex)
            {
            $log->write('Order History Mail Error');
            $log->write($ex);
            }
            
            $this->load->model('account/activity');

            if (in_array($order_status_id, $this->config->get('config_complete_status'))) {
                //completed order
                //reward on order complete

                /* editOrder settle amount */

                $order_sub_total = 0;

                $log->write("editOrder settle amount");

                $this->load->model('sale/order');

                $new_total = 0;

                $totals = $this->model_sale_order->getOrderTotals($order_id);

                foreach ($totals as $total) {
                    if ($total['code'] == 'total') {
                        $new_total = $total['value'];
                    }

                    if ($total['code'] == 'sub_total') {
                        $order_sub_total = $total['value'];
                    }
                }

                $pay_diff = ($new_total - $order_info['total']);
                if ($this->isOnlinePayment($order_info['payment_code']) && $pay_diff < 0) {

                    $log->write("editOrder settle amount if");

                    $this->model_account_activity->addCredit($order_info['customer_id'], 'Credit return on order ID#' . $order_info['order_id'], -($pay_diff), $order_info['order_id']);
                }

                /* editOrder settle amount end */


                /*
                  refer reward on first order only
                 */

                $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);

                $customer_orders_count = $this->model_account_customer->getTotalOrders($order_info['customer_id']);

                if (isset($customer_info) && !is_null($customer_info['refree_user_id']) && $customer_orders_count <= 1) {
                    $config_reward_enabled = $this->config->get('config_reward_enabled');

                    $config_credit_enabled = $this->config->get('config_credit_enabled');

                    $config_refer_type = $this->config->get('config_refer_type');

                    $config_refered_points = $this->config->get('config_refered_points');
                    $config_referee_points = $this->config->get('config_referee_points');

                    $customer_id = $order_info['customer_id'];
                    $referee_user_id = $customer_info['refree_user_id'];
                    $data['referral_description'] = 'Referral';

                    if ($config_refer_type == 'reward') {

                        $log->write($config_reward_enabled);

                        if ($config_reward_enabled && $config_refered_points && $config_referee_points) {
                            $log->write("if");
                            //referee points below
                            $this->model_account_activity->addCustomerReward($referee_user_id, $config_referee_points, $data['referral_description']);
                        }
                    } elseif ($config_refer_type == 'credit') {

                        $log->write('credit if');

                        if ($config_credit_enabled && $config_refered_points && $config_referee_points) {

                            //referee points below
                            $this->model_account_activity->addCredit($referee_user_id, $data['referral_description'], $config_referee_points);
                        }
                    }
                }

                /* refral end */

                $config_reward_switch_order_value = $this->config->get('config_reward_switch_order_value');
                $config_reward_on_order_total = $this->config->get('config_reward_on_order_total');
                $config_reward_enabled = $this->config->get('config_reward_enabled');

                //$total = $order_info['total'];
                // reward on subtotal

                $total = $order_sub_total;

                if ($config_reward_enabled) {
                    // get order detail by order id
                    if ($config_reward_switch_order_value == 'p') {
                        // its Percentage 
                        $points = floor(($total * $config_reward_on_order_total) / 100);
                    } else {
                        // its Fixed value
                        $points = $config_reward_on_order_total;
                    }

                    /* $log->write('completed status');
                      $log->write('completed status'.$order_info['customer_id']);
                      $log->write($order_info['order_id']); */


                    $this->load->language('checkout/success');

                    $log->write($this->language->get('text_order_id'));

                    if ($points > 0) {

                        $this->setCustomerReward($order_info['customer_id'], $order_info['order_id'], $this->language->get('text_order_id'), $points);
                    }
                }


                //credit on order complete

                $config_credit_switch_order_value = $this->config->get('config_credit_switch_order_value');
                $config_credit_on_order_total = $this->config->get('config_credit_on_order_total');
                $config_credit_enabled = $this->config->get('config_credit_enabled');

                $total = $order_info['total'];

                if ($config_credit_enabled) {
                    // get order detail by order id
                    if ($config_credit_switch_order_value == 'p') {
                        // its Percentage 
                        $points = ($total * $config_credit_on_order_total) / 100;
                    } else {
                        // its Fixed value
                        $points = $config_credit_on_order_total;
                    }

                    $this->load->language('checkout/success');

                    $creditDescription = sprintf($this->language->get('text_order_id'), $order_info['order_id']);

                    //$log->write($this->language->get('text_order_id'));



                    if ($points > 0) {
                        $this->model_account_activity->addCredit($order_info['customer_id'], $creditDescription, $points, $order_info['order_id']);
                    }
                }

                if ($config_credit_enabled) {

                    $this->load->language('checkout/success');
                    $points = 0;

                    $coupon_history_data = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coupon_history` WHERE order_id = '" . $order_info['order_id'] . "'")->row;

                    if (count($coupon_history_data) > 0) {

                        $coupon_query = $this->db->query("SELECT coupon_type FROM `" . DB_PREFIX . "coupon` WHERE coupon_id = " . $coupon_history_data['coupon_id']);

                        if ($coupon_query->num_rows && isset($coupon_query->row['coupon_type']) && $coupon_query->row['coupon_type'] == 'c') {

                            $points = (-1 * $coupon_history_data['amount']);

                            $creditDescription = sprintf($this->language->get('text_coupon_order_id'), $order_info['order_id']);


                            $log->write('coupon cashback');

                            $log->write($points);
                            $this->load->model('account/activity');

                            $this->model_account_activity->addCredit($order_info['customer_id'], $creditDescription, $points, $order_info['order_id']);
                        }
                    }
                }
            }
        }

        return true;

        $this->trigger->fire('post.order.history.add', $order_id);
    }

    public function addTransaction($data) {

        $log = new Log('error.log');
        $log->write('addTransaction');
        $log->write($data);
        $log->write('addTransaction');
        $order_id = implode(',', $this->session->data['order_id']);
        //echo $this->session->data['transaction_id'];die;
        //unset($this->session->data['transaction_id']);
        if (isset($this->session->data['transaction_id'])) {
            // update here
            $this->db->query("UPDATE  " . DB_PREFIX . "transaction_details SET order_ids = '" . $order_id . "', customer_id = '" . $this->customer->getId() . "', no_of_products = '" . $this->db->escape($data['no_of_products']) . "', `total` = '" . (float) $data['total'] . "',date_added = NOW() where transaction_details_id ='" . $this->session->data['transaction_id'] . "' ");
        } else {

            // insert here
            $this->db->query("INSERT INTO " . DB_PREFIX . "transaction_details SET order_ids = '" . $order_id . "', customer_id = '" . $this->customer->getId() . "', no_of_products = '" . $this->db->escape($data['no_of_products']) . "', `total` = '" . (float) $data['total'] . "',date_added = NOW()");
            $transaction_id = $this->db->getLastId();
            $this->session->data['transaction_id'] = $transaction_id;
        }
        if ($data['total'] <= 0 || $data['total'] == NULL) {
            $log->write('addTransaction Mail');
            $log->write($data);
            $log->write('addTransaction Mail');
            try {
                $subject = 'Order Total Value Zero';
                $message = 'Order ID : ' . $order_id . ' ' . ' Order Total:' . $data['total'];

                $mail = new mail($this->config->get('config_mail'));
                $mail->setTo('bugs.kwikbasket@yopmail.com');
                $mail->setFrom($this->config->get('config_from_email'));
                $mail->setSubject($subject);
                $mail->setSender($this->config->get('config_name'));
                $mail->setHtml($message);
                $mail->send();
            } catch (Exception $e) {
                
            }
        }
    }

    protected function _prepareProductSpecial($customer_group_id, $limit) {
        $special = array();
        $product_special = $this->_getProductSpecial((int) $customer_group_id, $limit);

        if (sizeof($product_special) <> 0) {
            foreach ($product_special as $product) {
                $discount = round(( ( $product['price'] - $product['special'] ) / $product['price'] ) * 100, 0);

                $special[] = '<a href="' . $this->url->link('product/product', 'product_id=' . $product['product_id'], 'SSL') . '">' . $product['name'] . '</a> (<font color="red">-' . $discount . '%</font>)';
            }
        }

        return $special;
    }

    protected function _getProductSpecial($customer_group_id, $limit = 5) {
        $sql = "SELECT DISTINCT ps.product_id, ps.price AS special, p.price, pd.name, (SELECT AVG(rating) FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = ps.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int) $this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int) $customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) GROUP BY ps.product_id ORDER BY p.sort_order ASC LIMIT " . (int) $limit;

        $product_data = array();

        $query = $this->db->query($sql);

        foreach ($query->rows as $result) {
            $product_data[] = array(
                'product_id' => $result['product_id'],
                'price' => $result['price'],
                'special' => $result['special'],
                'name' => $result['name']
            );
        }

        return $product_data;
    }

    public function getOrderProducts($order_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");

        return $query->rows;
    }

    public function getOrderVouchers($order_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int) $order_id . "'");

        return $query->rows;
    }

    public function updateOrderCoupons($order_id, $total) {

        /* foreach ($order_id as $key => $value) {

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
          } */
    }

    public function setCustomerReward($customer_id, $order_id, $text_order_id, $points) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "customer_reward SET customer_id = '" . (int) $customer_id . "', order_id = '" . (int) $order_id . "', description = '" . $this->db->escape(sprintf($text_order_id, (int) $order_id)) . "', points = '" . (float) $points . "', date_added = NOW()");
    }

    public function getOrderProductData($order_id) {

        return $this->db->query("SELECT `name`, `model`, `price`, `quantity`, `tax` / `price` * 100 AS 'tax_rate' FROM `" . DB_PREFIX . "order_product` WHERE `order_id` = " . (int) $order_id . " UNION ALL SELECT '', `code`, `amount`, '1', 0.00 FROM `" . DB_PREFIX . "order_voucher` WHERE `order_id` = " . (int) $order_id);
    }

    public function getOrderTotal($order_id) {

        return $this->db->query("SELECT `value` FROM `" . DB_PREFIX . "order_total` WHERE `order_id` = " . (int) $order_id . " AND `code` = 'klarna_fee'");
    }

    public function getOrderDetailIugu($order_id) {

        $log = new Log('error.log');


        $log->write('in igi constant');
        $log->write("SELECT * FROM `" . DB_PREFIX . "order_iugu` WHERE `order_id` = " . (int) $order_id);

        return $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_iugu` WHERE `order_id` = " . (int) $order_id)->row;
    }

    public function getTimeslotAverage($timeslot) {

        $str = $timeslot; //"06:26pm - 08:32pm";
        $arr = explode('-', $str);
        //print_r($arr);
        if (count($arr) == 2) {
            $one = date("H:i", strtotime($arr[0]));
            $two = date("H:i", strtotime($arr[1]));

            return $one;

            $time1 = explode(':', $one);
            $time2 = explode(':', $two);
            if (count($time1) == 2 && count($time2) == 2) {
                $mid1 = ($time1[0] + $time2[0]) / 2;
                $mid2 = ($time1[1] + $time2[1]) / 2;

                $mid1 = round($mid1);
                $mid2 = round($mid2);

                if ($mid2 <= 9) {
                    $mid2 = '0' . $mid2;
                }
                if ($mid1 <= 9) {
                    $mid1 = '0' . $mid1;
                }

                //if 19.5 is mid1 then i send 19 integer part cant send decimals

                return $mid1 . ":" . $mid2;
            }
        }

        return false;
    }

    public function getCustomer($customer_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int) $customer_id . "'");

        return $query->row;
    }

    public function getVendorDetails($vendor_id) {

        $sql = 'select *, CONCAT(firstname," ",lastname) as name from `' . DB_PREFIX . 'user`';
        $sql .= ' WHERE user_id="' . $vendor_id . '" AND user_group_id =11 LIMIT 1';

        return $this->db->query($sql)->row;
    }

    public function refundToCustomerWallet($order_id) {

        $this->load->model('account/activity');
        $log = new Log('error.log');

        $order_info = $this->getOrder($order_id);
        $this->load->language('checkout/success');
        $refundToCustomerWallet = false;

        if ($order_info) {
            $allowedPaymentMethods = $this->config->get('config_payment_methods_status');

            $log->write($allowedPaymentMethods);

            if (is_array($allowedPaymentMethods) && count($allowedPaymentMethods) > 0) {
                foreach ($allowedPaymentMethods as $method) {

                    if ($order_info['payment_code'] == $method) {
                        $refundToCustomerWallet = true;
                    }
                }
            }

            if ($refundToCustomerWallet) {
                $log->write($this->config->get('credit_status'));
                $log->write("refundToCustomerWallet");
                //referee points below
                $description = 'Refund of order#' . $order_id;
                if ($this->config->get('credit_status') == 1) {
                    $this->model_account_activity->addCredit($order_info['customer_id'], $description, $order_info['total'], $order_id);
                }
            }
        }
    }

    public function refundIuguAPI($order_id) {

        $this->load->model('payment/iugu');

        require_once DIR_SYSTEM . 'library/Iugu.php';

        //$invoiceId = "9EC5B44FACD047B7A280CD4E750D1A8B";

        $sql = 'select * from `' . DB_PREFIX . 'order_iugu`';
        $sql .= ' WHERE order_id="' . $order_id . '" LIMIT 1';

        $iuguData = $this->db->query($sql)->row;

        if ($iuguData) {

            $invoiceId = $iuguData['invoice_id'];

            Iugu::setApiKey($this->config->get('iugu_token'));

            $invoice = Iugu_Invoice::fetch($invoiceId);
            $result = $invoice->refund();
            //true 

            if ($result == 1) {
                //success
                return true;
            }
        }
        return false;
    }

    public function saveIuguCustomer($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "customer_to_customer_iugu SET customer_id = '" . $data['customer_id'] . "', iugu_customer_id = '" . $data['id'] . "'");
    }

    public function saveVendorNotification($user_id, $deviceId, $order_id, $message, $title) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "vendor_notifications SET user_id = '" . $user_id . "', type = 'order', purpose_id = '" . $order_id . "', title = '" . $title . "', message = '" . $message . "', status = 'unread', created_at = NOW() , updated_at = NOW()");

        $notificaiton_id = $this->db->getLastId();

        return $notificaiton_id;
    }

    public function saveReturnVendorNotification($user_id, $deviceId, $return_id, $message, $title) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "vendor_notifications SET user_id = '" . $user_id . "', type = 'return', purpose_id = '" . $return_id . "', title = '" . $title . "', message = '" . $message . "', status = 'unread', created_at = NOW() , updated_at = NOW()");
    }

    public function createDeliveryRequest($order_id, $order_status_id = 1) {
        $log = new Log('dserror.log');
        $log->write('createDeliveryRequest here in admin/model/checkout/order');
        $order_info = $this->getOrder($order_id);
        $this->load->language('checkout/success');

        $this->load->model('account/order');

        $deliveryAlreadyCreated = $this->getOrderDeliveryId($order_id);

        if ($order_info && !$deliveryAlreadyCreated) {

            $log->write("inside createDeliveryRequest");


            $data['products']['products'] = [];
            $weight = 0;

            //$products = $this->model_account_order->getOrderProducts($order_id);
            if ($this->model_account_order->hasRealOrderProducts($order_id)) {

                $products = $this->model_account_order->getRealOrderProducts($order_id);
            } else {

                $products = $this->model_account_order->getOrderProducts($order_id);
            }

            foreach ($products as $product) {

                $weight += ($product['weight'] * $product['quantity']);

                $replacable = 'no';

                if ($product['product_type'] == 'replacable')
                    $replacable = 'yes';

                $this->load->model('tool/image');

                if (file_exists(DIR_IMAGE . $product['image'])) {
                    $image = HTTP_IMAGE . $product['image'];
                } else {
                    $image = HTTP_IMAGE . 'placeholder.png';
                }

                $var = [
                    "product_name" => htmlspecialchars_decode($product['name']),
                    "product_unit" => $product['unit'],
                    "product_quantity" => $product['quantity'],
                    "product_image" => $image, //"http:\/\/\/product-images\/camera.jpg",
                    "product_price" => $product['price'], //"1500.00",//product price unit price?? or total
                    "product_replaceable" => $replacable//"no"
                ];

                array_push($data['products']['products'], $var);
            }
            //$log->write($data['products']['products']);

            $data['text_weight'] = sprintf($this->language->get('text_weight'), $weight);

            $store_details = $this->model_account_order->getStoreById($order_info['store_id']);

            //$log->write($store_details);

            $delivery_priority = 'normal';

            $temp = explode('.', $order_info['shipping_code']);
            if (isset($temp[0])) {
                $delivery_priority = $temp[0];
            }

            $store_city_name = $this->model_account_order->getCityName($store_details['city_id']);
            $store_state_name = $this->model_account_order->getCityState($store_details['city_id']);

            $timeSlotAverage = $this->getTimeslotAverage($order_info['delivery_timeslot']);

            //$deliverAddress = $order_info['shipping_flat_number'].", ". $order_info['shipping_building_name'].", ".$order_info['shipping_landmark'];
            $deliverAddress = $order_info['shipping_flat_number'] . ", " . $order_info['shipping_landmark'];


            $this->load->model('sale/order');

            $new_total = 0;

            $totals = $this->model_sale_order->getOrderTotals($order_id);

            foreach ($totals as $total) {
                if ($total['code'] == 'total') {
                    $new_total = $total['value'];
                    break;
                }
            }

            $pay_diff = ($new_total - $order_info['total']);

            $total_type = 'green';

            if (!$this->isOnlinePayment($order_info['payment_code']) || $pay_diff > 0) {
                $total_type = 'red';
            }

            if ($this->isOnlinePayment($order_info['payment_code'])) {

                if ($pay_diff < 0) {
                    $getPayment = 0;
                } else {
                    $getPayment = $pay_diff;
                }
            } else {

                $getPayment = $new_total;
            }

            $data['body'] = [
                'pickup_name' => $store_details['name'], //store name??
                'pickup_phone' => $store_details['telephone'],
                'pickup_address' => $store_details['address'], //
                'pickup_city' => $store_city_name,
                //'pickup_state' => 'Brussels',
                'pickup_state' => $store_state_name,
                'from_lat' => $store_details['latitude'],
                'from_lng' => $store_details['longitude'],
                'pickup_zipcode' => $store_details['store_zipcode'], //''
                //'pickup_notes' => $data['text_weight'],
                'pickup_notes' => $store_details['pickup_notes'],
                'dropoff_name' => $order_info['shipping_name'],
                'dropoff_phone' => $order_info['telephone'],
                'dropoff_address' => $deliverAddress,
                'to_lat' => $order_info['latitude'],
                'to_lng' => $order_info['longitude'],
                'dropoff_city' => $order_info['shipping_city'], // from $order_info['city_id'],
                //'dropoff_state' => 'Brussels',
                'dropoff_state' => $order_info['shipping_state'],
                'dropoff_zipcode' => $order_info['shipping_zipcode'], // from $order_info['city_id'],
                'delivery_priority' => $delivery_priority, // normal/express all small
                'delivery_date' => $order_info['delivery_date'], //2017-04-13
                'delivery_slot' => $timeSlotAverage, //$order_info['delivery_timeslot'],//"10:30" //delivery slot is time so what will i enter here as i have data in format 06:26pm - 08:32pm
                'delivery_original_slot' => $order_info['delivery_timeslot'], //2017-04-13
                'dropoff_notes' => $order_info['comment'],
                'type_of_delivery' => 'delivery', //delivery/return . Is it only one option for this index?
                'manifest_id' => $order_id, //order_id,
                'manifest_data' => json_encode($data['products']),
                'payment_method' => $order_info['payment_method'],
                'payment_code' => $order_info['payment_code'],
                'total_price' => (int) round($new_total),
                'get_amount' => (int) round($getPayment),
                'total_type' => $total_type,
            ];


            $data['email'] = $this->config->get('config_delivery_username');
            $data['password'] = $this->config->get('config_delivery_secret');
            $response = $this->load->controller('deliversystem/deliversystem/getToken', $data);

            if ($response['status']) {
                $data['token'] = $response['token'];
                $res = $this->load->controller('deliversystem/deliversystem/createDelivery', $data);

                //$res['status'] = false;
                if ($res['status']) {
                    $log->write("stsusxx");
                    if (isset($res['data']->delivery_id)) {
                        $delivery_id = $res['data']->delivery_id;
                        $this->db->query("UPDATE `" . DB_PREFIX . "order` SET delivery_id = '" . $delivery_id . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");
                    }
                    //save in order table delivery id 
                }
            }
        }
    }

    public function getOrderDeliveryId($order_id) {
        $s = $this->db->query("Select delivery_id from `" . DB_PREFIX . "order` WHERE order_id = '" . (int) $order_id . "'");
        if ($s->num_rows && !empty($s->row['delivery_id'])) {

            return true;
        }

        return false;
    }

    public function sendCancelOrderStatus($order_id) {

        $log = new Log('error.log');
        $log->write('sendCancelOrderStatus');
        $log->write($order_id);

        $data['email'] = $this->config->get('config_delivery_username');
        $data['password'] = $this->config->get('config_delivery_secret');

        $data['delivery_id'] = $order_id;
        $data['delivery_status'] = 308;

        // delivery_status:308
        //$data['rating'] = 3;

        $response = $this->load->controller('deliversystem/deliversystem/getToken', $data);

        $log->write($response);
        if ($response['status']) {
            $data['token'] = $response['token'];

            $log->write("response true");
            $log->write($data);


            $respon = $this->load->controller('deliversystem/deliversystem/updateCancelledOrder', $data);
        }
    }

    public function isOnlinePayment($payment_code) {
        $refundToCustomerWallet = false;

        $allowedPaymentMethods = $this->config->get('config_payment_methods_status');


        if (is_array($allowedPaymentMethods) && count($allowedPaymentMethods) > 0) {
            foreach ($allowedPaymentMethods as $method) {

                if ($payment_code == $method) {
                    $refundToCustomerWallet = true;
                }
            }
        }

        return $refundToCustomerWallet;
    }

    public function getOrderNew($order_id) {
        $log = new Log('error.log');
        $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . "order` WHERE order_id  = '" . $order_id . "'");
        $log->write($query->row);

        return $query->row;
    }

    public function SendMailToParentUser($order_id) {
        $log = new Log('error.log');
        $log->write('SEND MAIL');
        $log->write($order_id);
        $this->load->model('account/customer');
        $is_he_parents = $this->model_account_customer->CheckHeIsParent();
        $customer_info = $this->model_account_customer->getCustomer($is_he_parents);
        $order_info_custom = $this->getOrderNew($order_id);
        //$log->write($order_info_custom);
        $order_info = $this->getOrder($order_id);
        if ($order_info) {
            $store_name = $order_info['firstname'] . ' ' . $order_info['lastname'];
            $store_url = $this->url->link('account/login/customer');
        }
        $sub_customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);

        $ciphering = 'AES-128-CTR';
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;
        $encryption_iv = '1234567891011121';
        $encryption_key = 'KwikBasket';

        $order_id = openssl_encrypt($order_info['order_id'], $ciphering, $encryption_key, $options, $encryption_iv);
        $customer_id = openssl_encrypt($order_info['customer_id'], $ciphering, $encryption_key, $options, $encryption_iv);
        $parent_id = openssl_encrypt($is_he_parents, $ciphering, $encryption_key, $options, $encryption_iv);

        $customer_info['store_name'] = $store_name;
        $customer_info['branchname'] = $sub_customer_info['company_name'];
        $customer_info['subuserfirstname'] = $sub_customer_info['firstname'];
        $customer_info['subuserlastname'] = $sub_customer_info['lastname'];
        $customer_info['subuserorderid'] = $order_info['order_id'];
        $customer_info['ip_address'] = $order_info['ip'];
        $customer_info['order_link'] = $this->url->link('account/login/checksubuserorder', 'order_token=' . $order_id . '&user_token=' . $customer_id . '&parent_user_token=' . $parent_id, 'SSL');
        $customer_info['device_id'] = $customer_info['device_id'];

        $log->write('EMAIL SENDING');
        $log->write($customer_info);
        $log->write('EMAIL SENDING');
        try {
            if ($customer_info['email_notification'] == 1) {
                $subject = $this->emailtemplate->getSubject('Customer', 'customer_7', $customer_info);
                $message = $this->emailtemplate->getMessage('Customer', 'customer_7', $customer_info);

                $mail = new Mail($this->config->get('config_mail'));
                $mail->setTo($customer_info['email']);
                $mail->setFrom($this->config->get('config_from_email'));
                $mail->setSender($this->config->get('config_name'));
                $mail->setSubject($subject);
                $mail->setHTML($message);
                $mail->send();
            }
        } catch (exception $ex) {
            $log->write('SendMailToParentUser Error');
            $log->write($ex);
        }


        $log->write('status enabled of mobi noti');
        $mobile_notification_template = $this->emailtemplate->getNotificationMessage('Customer', 'customer_7', $customer_info);

        $mobile_notification_title = $this->emailtemplate->getNotificationTitle('Customer', 'customer_7', $customer_info);

        if (isset($customer_info) && isset($customer_info['device_id']) && $customer_info['mobile_notification'] == 1 && strlen($customer_info['device_id']) > 0) {

            $log->write('customer device id set FRONT.MODEL.CHECKOUT.ORDER');
            $ret = $this->emailtemplate->sendPushNotification($order_info['customer_id'], $customer_info['device_id'], $order_info['order_id'], $order_info['store_id'], $mobile_notification_title, $mobile_notification_template, 'com.instagolocal.showorder');
        } else {
            $log->write('customer device id not set FRONT.MODEL.CHECKOUT.ORDER');
        }

        if ($is_he_parents != NULL && $is_he_parents > 0) {
            $order_approval_access = $this->db->query('SELECT c.customer_id, c.parent, c.order_approval_access_role, c.order_approval_access, c.email, c.firstname, c.lastname, c.device_id, c.sms_notification, c.mobile_notification, c.email_notification  FROM ' . DB_PREFIX . "customer c WHERE c.parent = '" . (int) $is_he_parents . "' AND c.order_approval_access = 1 AND (c.order_approval_access_role = 'head_chef' OR c.order_approval_access_role = 'procurement_person')");
            $order_approval_access_user = $order_approval_access->rows;

            foreach ($order_approval_access_user as $order_approval_access_use) {
                if ($order_approval_access_use['order_approval_access_role'] == 'head_chef' && $order_approval_access_use['order_approval_access'] > 0) {

                    $order_id = openssl_encrypt($order_info['order_id'], $ciphering, $encryption_key, $options, $encryption_iv);
                    $customer_id = openssl_encrypt($order_info['customer_id'], $ciphering, $encryption_key, $options, $encryption_iv);
                    $parent_id = openssl_encrypt($order_approval_access_use['customer_id'], $ciphering, $encryption_key, $options, $encryption_iv);

                    $order_approval_access_use['store_name'] = $store_name;
                    $order_approval_access_use['branchname'] = $sub_customer_info['company_name'];
                    $order_approval_access_use['subuserfirstname'] = $sub_customer_info['firstname'];
                    $order_approval_access_use['subuserlastname'] = $sub_customer_info['lastname'];
                    $order_approval_access_use['order_link'] = $this->url->link('account/login/checksubuserorder', 'order_token=' . $order_id . '&user_token=' . $customer_id . '&parent_user_token=' . $parent_id, 'SSL');
                    $order_approval_access_use['device_id'] = $order_approval_access_use['device_id'];

                    $log->write('EMAIL SENDING');
                    $log->write($customer_info);
                    $log->write('EMAIL SENDING');

                    $subject = $this->emailtemplate->getSubject('Customer', 'customer_7', $order_approval_access_use);
                    $message = $this->emailtemplate->getMessage('Customer', 'customer_7', $order_approval_access_use);
                    try {
                        if ($order_approval_access_use['email_notification'] == 1) {
                            $mail = new Mail($this->config->get('config_mail'));
                            $mail->setTo($order_approval_access_use['email']);
                            $mail->setFrom($this->config->get('config_from_email'));
                            $mail->setSender($this->config->get('config_name'));
                            $mail->setSubject($subject);
                            $mail->setHTML($message);
                            $mail->send();
                        }
                    } catch (exception $ex) {
                        $log->write('SendMailToParentUser Error');
                        $log->write($ex);
                    }


                    $log->write('status enabled of mobi noti');
                    $mobile_notification_template = $this->emailtemplate->getNotificationMessage('Customer', 'customer_7', $order_approval_access_user);

                    $mobile_notification_title = $this->emailtemplate->getNotificationTitle('Customer', 'customer_7', $order_approval_access_use);

                    if (isset($order_approval_access_use) && isset($order_approval_access_use['device_id']) && $order_approval_access_use['mobile_notification'] && strlen($order_approval_access_use['device_id']) > 0) {

                        $log->write('customer device id set FRONT.MODEL.CHECKOUT.ORDER');
                        $ret = $this->emailtemplate->sendPushNotification($order_info['customer_id'], $order_approval_access_use['device_id'], $order_info['order_id'], $order_info['store_id'], $mobile_notification_title, $mobile_notification_template, 'com.instagolocal.showorder');
                    } else {
                        $log->write('customer device id not set FRONT.MODEL.CHECKOUT.ORDER');
                    }
                }

                if ($order_approval_access_use['order_approval_access_role'] == 'procurement_person' && $order_approval_access_use['order_approval_access'] > 0) {
                    $order_id = openssl_encrypt($order_info['order_id'], $ciphering, $encryption_key, $options, $encryption_iv);
                    $customer_id = openssl_encrypt($order_info['customer_id'], $ciphering, $encryption_key, $options, $encryption_iv);
                    $parent_id = openssl_encrypt($order_approval_access_use['customer_id'], $ciphering, $encryption_key, $options, $encryption_iv);

                    $order_approval_access_use['store_name'] = $store_name;
                    $order_approval_access_use['branchname'] = $sub_customer_info['company_name'];
                    $order_approval_access_use['subuserfirstname'] = $sub_customer_info['firstname'];
                    $order_approval_access_use['subuserlastname'] = $sub_customer_info['lastname'];
                    $order_approval_access_use['order_link'] = $this->url->link('account/login/checksubuserorder', 'order_token=' . $order_id . '&user_token=' . $customer_id . '&parent_user_token=' . $parent_id, 'SSL');
                    $order_approval_access_use['device_id'] = $order_approval_access_use['device_id'];

                    $log->write('EMAIL SENDING');
                    $log->write($customer_info);
                    $log->write('EMAIL SENDING');

                    try {
                        if ($order_approval_access_use['email_notification'] == 1) {
                            $subject = $this->emailtemplate->getSubject('Customer', 'customer_7', $order_approval_access_use);
                            $message = $this->emailtemplate->getMessage('Customer', 'customer_7', $order_approval_access_use);

                            $mail = new Mail($this->config->get('config_mail'));
                            $mail->setTo($order_approval_access_use['email']);
                            $mail->setFrom($this->config->get('config_from_email'));
                            $mail->setSender($this->config->get('config_name'));
                            $mail->setSubject($subject);
                            $mail->setHTML($message);
                            $mail->send();
                        }
                    } catch (exception $ex) {
                        $log->write('SendMailToParentUser Error');
                        $log->write($ex);
                    }

                    $log->write('status enabled of mobi noti');
                    $mobile_notification_template = $this->emailtemplate->getNotificationMessage('Customer', 'customer_7', $order_approval_access_user);

                    $mobile_notification_title = $this->emailtemplate->getNotificationTitle('Customer', 'customer_7', $order_approval_access_use);

                    if (isset($order_approval_access_use) && isset($order_approval_access_use['device_id']) && $order_approval_access_use['mobile_notification'] == 1 && strlen($order_approval_access_use['device_id']) > 0) {

                        $log->write('customer device id set FRONT.MODEL.CHECKOUT.ORDER');
                        $ret = $this->emailtemplate->sendPushNotification($order_info['customer_id'], $order_approval_access_use['device_id'], $order_info['order_id'], $order_info['store_id'], $mobile_notification_title, $mobile_notification_template, 'com.instagolocal.showorder');
                    } else {
                        $log->write('customer device id not set FRONT.MODEL.CHECKOUT.ORDER');
                    }
                }
            }
        }

        $log->write('SMS SENDING');
        $sms_message = $this->emailtemplate->getSmsMessage('Customer', 'customer_7', $customer_info);
        // send message here
        if ($customer_info['sms_notification'] == 1 && $this->emailtemplate->getSmsEnabled('Customer', 'customer_7')) {
            $ret = $this->emailtemplate->sendmessage($customer_info['telephone'], $sms_message);
        }
    }

    public function UpdatePaymentMethod($order_id, $payment_method, $payment_code) {
        $this->db->query('UPDATE `' . DB_PREFIX . "order` SET payment_method = '" . $payment_method . "',payment_code = '" . $payment_code . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");
    }

    public function UpdateParentApproval($order_id) {
        $log = new Log('error.log');
        $this->load->model('account/customer');
        $is_he_parents = $this->model_account_customer->CheckHeIsParent();
        $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

        $parent_customer_info = NULL;
        if ($is_he_parents != NULL && $is_he_parents > 0) {
            $parent_customer_info = $this->model_account_customer->getCustomer($is_he_parents);
        }

        $sub_customer_order_approval_required = 1;
        if (isset($parent_customer_info) && $parent_customer_info != NULL && is_array($parent_customer_info)) {
            $sub_customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
            $sub_customer_order_approval_required = $sub_customer_info['sub_customer_order_approval'];
        }

        $order_appoval_access = FALSE;
        if ($this->session->data['order_approval_access'] > 0 && $this->session->data['order_approval_access_role'] != NULL && $sub_customer_order_approval_required == 1) {
            $order_appoval_access = TRUE;
        }
        $log->write('Order Confirm In COD FRONT/MODEL/CHECKOUT/ORDER.PHP');
        $log->write($is_he_parents);
        $log->write('Order Confirm In COD FRONT/MODEL/CHECKOUT/ORDER.PHP');

        $head_chef = 'Approved';
        $procurement = 'Approved';
        if ($is_he_parents != NULL && $is_he_parents > 0 && $order_appoval_access == FALSE && $sub_customer_order_approval_required == 1) {
            $order_approval_access = $this->db->query('SELECT c.customer_id, c.parent, c.order_approval_access_role, c.order_approval_access FROM ' . DB_PREFIX . "customer c WHERE c.parent = '" . (int) $is_he_parents . "' AND c.order_approval_access = 1 AND (c.order_approval_access_role = 'head_chef' OR c.order_approval_access_role = 'procurement_person')");
            $order_approval_access_user = $order_approval_access->rows;

            foreach ($order_approval_access_user as $order_approval_access_use) {
                if ($order_approval_access_use['order_approval_access_role'] == 'head_chef' && $order_approval_access_use['order_approval_access'] > 0) {
                    $head_chef = 'Pending';

                    $log->write('Order Approval Access');
                    $log->write($order_approval_access_user);
                    $log->write('Order Approval Access');
                }

                if ($order_approval_access_use['order_approval_access_role'] == 'procurement_person' && $order_approval_access_use['order_approval_access'] > 0) {
                    $procurement = 'Pending';

                    $log->write('Order Approval Access');
                    $log->write($order_approval_access_user);
                    $log->write('Order Approval Access');
                }
            }
        }

        $log->write('UPDATING SUB USER ORDER' . $order_id);
        $parent_approval = $is_he_parents == NULL || $order_appoval_access == TRUE || $sub_customer_order_approval_required == 0 ? 'Approved' : 'Pending';
        $order_status_id = $is_he_parents == NULL || $order_appoval_access == TRUE || $sub_customer_order_approval_required == 0 ? $this->config->get('cod_order_status_id') : 15;
        $this->db->query('UPDATE `' . DB_PREFIX . "order` SET parent_approval = '" . $parent_approval . "',head_chef = '" . $head_chef . "',procurement = '" . $procurement . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");
    }

    public function UpdateParentApprovalAPI($order_id, $order_approval_access, $order_approval_access_role) {
        $log = new Log('error.log');
        $this->load->model('account/customer');
        $is_he_parents = $this->model_account_customer->CheckHeIsParent();
        $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

        $parent_customer_info = NULL;
        if ($is_he_parents != NULL && $is_he_parents > 0) {
            $parent_customer_info = $this->model_account_customer->getCustomer($is_he_parents);
        }

        $sub_customer_order_approval_required = 1;
        if (isset($parent_customer_info) && $parent_customer_info != NULL && is_array($parent_customer_info)) {
            // $sub_customer_order_approval_required = $parent_customer_info['sub_customer_order_approval'];    

            $sub_customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
            $sub_customer_order_approval_required = $sub_customer_info['sub_customer_order_approval'];
        }

        $order_appoval_access = FALSE;
        // if ($this->session->data['order_approval_access'] > 0 && $this->session->data['order_approval_access_role'] != NULL) {
        if ($order_approval_access > 0 && $order_approval_access_role != NULL && $sub_customer_order_approval_required == 1) {
            $order_appoval_access = TRUE;
        }
        $log->write('Order Confirm In COD FRONT/MODEL/CHECKOUT/ORDER.PHP');
        $log->write($is_he_parents);
        $log->write('Order Confirm In COD FRONT/MODEL/CHECKOUT/ORDER.PHP');

        $head_chef = 'Approved';
        $procurement = 'Approved';
        if ($is_he_parents != NULL && $is_he_parents > 0 && $order_appoval_access == FALSE && $sub_customer_order_approval_required == 1) {
            $order_approval_access = $this->db->query('SELECT c.customer_id, c.parent, c.order_approval_access_role, c.order_approval_access FROM ' . DB_PREFIX . "customer c WHERE c.parent = '" . (int) $is_he_parents . "' AND c.order_approval_access = 1 AND (c.order_approval_access_role = 'head_chef' OR c.order_approval_access_role = 'procurement_person')");
            $order_approval_access_user = $order_approval_access->rows;

            foreach ($order_approval_access_user as $order_approval_access_use) {
                if ($order_approval_access_use['order_approval_access_role'] == 'head_chef' && $order_approval_access_use['order_approval_access'] > 0) {
                    $head_chef = 'Pending';

                    $log->write('Order Approval Access');
                    $log->write($order_approval_access_user);
                    $log->write('Order Approval Access');
                }

                if ($order_approval_access_use['order_approval_access_role'] == 'procurement_person' && $order_approval_access_use['order_approval_access'] > 0) {
                    $procurement = 'Pending';

                    $log->write('Order Approval Access');
                    $log->write($order_approval_access_user);
                    $log->write('Order Approval Access');
                }
            }
        }
        $log->write('UPDATING SUB USER ORDER' . $order_id);
        $parent_approval = $is_he_parents == NULL || $order_appoval_access == TRUE || $sub_customer_order_approval_required == 0 ? 'Approved' : 'Pending';
        $order_status_id = $is_he_parents == NULL || $order_appoval_access == TRUE || $sub_customer_order_approval_required == 0 ? $this->config->get('cod_order_status_id') : 15;
        $this->db->query('UPDATE `' . DB_PREFIX . "order` SET parent_approval = '" . $parent_approval . "',head_chef = '" . $head_chef . "',procurement = '" . $procurement . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");
    }

}
