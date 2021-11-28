<?php

class ModelApiCheckout extends Model
{
    //get vendor commision
    public function get_commision($vendor_id)
    {
        $row = $this->db->query('select commision, free_from, free_to from '.DB_PREFIX.'user where user_id="'.$vendor_id.'"')->row;
        if (time() >= strtotime($row['free_from']) && time() <= strtotime($row['free_to'])) {
            return 0; //in free trial month
        } else {
            return $row['commision'];
        }
    }

    public function addOrder($stores)
    {
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
        $this->load->model('account/customer');
        $is_he_parents = $this->model_account_customer->CheckHeIsParent();
        $log->write('addMultiOrder 123');
        $log->write($is_he_parents);
        $log->write('addMultiOrder 123');
        $parent_approval = null == $is_he_parents ? 'Approved' : 'Pending';
        $order_status_id = null == $is_he_parents ? 14 : 15;

        if ($orders) {
            foreach ($stores as $key => $data) {
                //echo $this->session->data['order_id'][$key];

                $this->deleteOrder($this->session->data['order_id'][$key]);
                $order_id = $this->session->data['order_id'][$key];
                $log->write('addorder 2');
                $this->db->query('INSERT INTO `'.DB_PREFIX."order` SET order_id='".$order_id."', parent_approval='".$parent_approval."', order_status_id='".$order_status_id."', invoice_prefix = '".$this->db->escape($data['invoice_prefix'])."', store_id = '".(int) $data['store_id']."', store_name = '".$this->db->escape($data['store_name'])."', store_url = '".$this->db->escape($data['store_url'])."', customer_id = '".(int) $data['customer_id']."', customer_group_id = '".(int) $data['customer_group_id']."', firstname = '".$this->db->escape($data['firstname'])."', lastname = '".$this->db->escape($data['lastname'])."', email = '".$this->db->escape($data['email'])."', telephone = '".$this->db->escape($data['telephone'])."', fax = '".$this->db->escape($data['fax'])."', custom_field = '".$this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '')."', payment_method = '".$this->db->escape($data['payment_method'])."', payment_code = '".$this->db->escape($data['payment_code'])."',shipping_method = '".$this->db->escape($data['shipping_method'])."', shipping_code = '".$this->db->escape($data['shipping_code'])."', comment = '".$this->db->escape($data['comment'])."', total = '".(float) $data['total']."', latitude = '".$data['latitude']."',longitude = '".$data['longitude']."', affiliate_id = '".(int) $data['affiliate_id']."',marketing_id = '".(int) $data['marketing_id']."', tracking = '".$this->db->escape($data['tracking'])."', language_id = '".(int) $data['language_id']."', currency_id = '".(int) $data['currency_id']."', currency_code = '".$this->db->escape($data['currency_code'])."', currency_value = '".(float) $data['currency_value']."', ip = '".$this->db->escape($data['ip'])."', forwarded_ip = '".$this->db->escape($data['forwarded_ip'])."', user_agent = '".$this->db->escape($data['user_agent'])."', accept_language = '".$this->db->escape($data['accept_language'])."', commission = '".$this->db->escape($data['commission'])."', fixed_commission = '".$this->db->escape($data['fixed_commission'])."',delivery_date = '".$this->db->escape(date('Y-m-d', strtotime($data['delivery_date'])))."',delivery_timeslot = '".$this->db->escape($data['delivery_timeslot'])."',  date_added = NOW(), date_modified = NOW()");

                $this->db->query('UPDATE `'.DB_PREFIX.'order` SET '
                        ."shipping_city_id = '".$this->db->escape($data['shipping_city_id'])."', "
                        ."shipping_contact_no = '".$this->db->escape($data['shipping_contact_no'])."', "
                        ."shipping_address = '".$this->db->escape($data['shipping_address'])."', "
                        ."shipping_flat_number = '".$this->db->escape($data['shipping_flat_number'])."', "
                        ."shipping_building_name = '".$this->db->escape($data['shipping_building_name'])."', "
                        ."shipping_landmark = '".$this->db->escape($data['shipping_landmark'])."', "
                        ."shipping_zipcode = '".$this->db->escape($data['shipping_zipcode'])."', "
                        ."shipping_name = '".$this->db->escape($data['shipping_name'])."' "
                        ."WHERE order_id='".$order_id."'");

                if (isset($data['products'])) {
                    foreach ($data['products'] as $product) {
                        $this->db->query('INSERT INTO '.DB_PREFIX."order_product SET vendor_id='".(int) $product['vendor_id']."', store_id='".(int) $product['store_id']."', product_type='".$product['product_type']."', general_product_id='".$product['product_id']."', unit='".$product['unit']."', order_id = '".(int) $order_id."', variation_id = '".(int) $product['store_product_variation_id']."', product_id = '".(int) $product['product_store_id']."', name = '".$this->db->escape($product['name'])."', model = '".$this->db->escape($product['model'])."', quantity = '". $product['quantity']."', price = '".(float) $product['price']."', total = '".(float) $product['total']."', tax = '".(float) $product['tax']."', reward = '".(int) $product['reward']."'");
                    }
                }
                if (isset($data['totals'])) {
                    foreach ($data['totals'] as $total) {
                        $this->db->query('INSERT INTO '.DB_PREFIX."order_total SET order_id = '".(int) $order_id."', code = '".$this->db->escape($total['code'])."', title = '".$this->db->escape($total['title'])."', `value` = '".(float) $total['value']."', sort_order = '".(int) $total['sort_order']."'");
                    }
                }
            }
        } else {
            foreach ($stores as $data) {
                $this->db->query('INSERT INTO `'.DB_PREFIX."order` SET invoice_prefix = '".$this->db->escape($data['invoice_prefix'])."', parent_approval='".$parent_approval."', order_status_id='".$order_status_id."', store_id = '".(int) $data['store_id']."', store_name = '".$this->db->escape($data['store_name'])."', store_url = '".$this->db->escape($data['store_url'])."', customer_id = '".(int) $data['customer_id']."', customer_group_id = '".(int) $data['customer_group_id']."', firstname = '".$this->db->escape($data['firstname'])."', lastname = '".$this->db->escape($data['lastname'])."', email = '".$this->db->escape($data['email'])."', telephone = '".$this->db->escape($data['telephone'])."', fax = '".$this->db->escape($data['fax'])."', custom_field = '".$this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '')."', payment_method = '".$this->db->escape($data['payment_method'])."', payment_code = '".$this->db->escape($data['payment_code'])."',shipping_method = '".$this->db->escape($data['shipping_method'])."', shipping_code = '".$this->db->escape($data['shipping_code'])."', comment = '".$this->db->escape($data['comment'])."', total = '".(float) $data['total']."', affiliate_id = '".(int) $data['affiliate_id']."',marketing_id = '".(int) $data['marketing_id']."', latitude = '".$data['latitude']."',longitude = '".$data['longitude']."', tracking = '".$this->db->escape($data['tracking'])."', language_id = '".(int) $data['language_id']."', currency_id = '".(int) $data['currency_id']."', currency_code = '".$this->db->escape($data['currency_code'])."', currency_value = '".(float) $data['currency_value']."', ip = '".$this->db->escape($data['ip'])."', forwarded_ip = '".$this->db->escape($data['forwarded_ip'])."', user_agent = '".$this->db->escape($data['user_agent'])."', fixed_commission = '".$this->db->escape($data['fixed_commission'])."',commission = '".$this->db->escape($data['commission'])."',delivery_date = '".$this->db->escape(date('Y-m-d', strtotime($data['delivery_date'])))."',delivery_timeslot = '".$this->db->escape($data['delivery_timeslot'])."',  accept_language = '".$this->db->escape($data['accept_language'])."', date_added = NOW(), date_modified = NOW()");
                $order_id = $this->db->getLastId();

                $this->session->data['order_id'][$data['store_id']] = $order_id;

                $this->db->query('UPDATE `'.DB_PREFIX.'order` SET '
                        ."shipping_city_id = '".$this->db->escape($data['shipping_city_id'])."', "
                        ."shipping_contact_no = '".$this->db->escape($data['shipping_contact_no'])."', "
                        ."shipping_address = '".$this->db->escape($data['shipping_address'])."', "
                        ."shipping_flat_number = '".$this->db->escape($data['shipping_flat_number'])."', "
                        ."shipping_building_name = '".$this->db->escape($data['shipping_building_name'])."', "
                        ."shipping_landmark = '".$this->db->escape($data['shipping_landmark'])."', "
                        ."shipping_zipcode = '".$this->db->escape($data['shipping_zipcode'])."', "
                        ."shipping_name = '".$this->db->escape($data['shipping_name'])."' "
                        ."WHERE order_id='".$order_id."'");

                if (isset($data['products'])) {
                    foreach ($data['products'] as $product) {
                        $this->db->query('INSERT INTO '.DB_PREFIX."order_product SET vendor_id='".(int) $product['vendor_id']."', store_id='".(int) $product['store_id']."', product_type='".$product['product_type']."', general_product_id='".$product['product_id']."', unit='".$product['unit']."', order_id = '".(int) $order_id."', variation_id = '".(int) $product['store_product_variation_id']."', product_id = '".(int) $product['product_store_id']."', name = '".$this->db->escape($product['name'])."', model = '".$this->db->escape($product['model'])."', quantity = '". $product['quantity']."', price = '".(float) $product['price']."', total = '".(float) $product['total']."', tax = '".(float) $product['tax']."', reward = '".(int) $product['reward']."'");
                    }
                }

                if (isset($data['totals'])) {
                    foreach ($data['totals'] as $total) {
                        $this->db->query('INSERT INTO '.DB_PREFIX."order_total SET order_id = '".(int) $order_id."', code = '".$this->db->escape($total['code'])."', title = '".$this->db->escape($total['title'])."', `value` = '".(float) $total['value']."', sort_order = '".(int) $total['sort_order']."'");
                    }
                }
            }
        }
    }

    public function addMultiOrder($stores)
    {
        $log = new Log('error.log');
        $log->write('addMultiOrder 1');
          /*TIME ZONE ISSUE*/
          $tz = (new DateTime('now', new DateTimeZone('Africa/Nairobi')))->format('P');
          $this->db->query("SET time_zone='$tz';");
          /*TIME ZONE ISSUE*/
        $this->trigger->fire('pre.order.add', $stores);

        $order_ids = [];

        $orders = isset($this->session->data['order_id']);
        $this->load->model('account/customer');
        $is_he_parents = $this->model_account_customer->CheckHeIsParent();
        $log->write('addMultiOrder 123');
        $log->write($is_he_parents);
        $log->write('addMultiOrder 123');
        // $parent_approval = null == $is_he_parents ? 'Approved' : 'Pending';
        // $order_status_id = null == $is_he_parents ? 14 : 15;

        $order_appoval_access = FALSE;
        if ($this->session->data['order_approval_access'] > 0 && $this->session->data['order_approval_access_role'] != NULL) {
            $order_appoval_access = TRUE;
        }

        $parent_approval = $is_he_parents == NULL || $order_appoval_access == TRUE ? 'Approved' : 'Pending';
        $order_status_id = $is_he_parents == NULL || $order_appoval_access == TRUE ? $this->config->get('cod_order_status_id') : 15;


        //if ( $orders && count($stores) == count($this->session->data['order_id'])) {
        if ($orders) {
            $log->write('addMultiOrder if');
        } else {
            $log->write('addMultiOrder else');

            foreach ($stores as $data) {
                $log->write('loop stores');
                $log->write($data);
                if($data['login_longitude']==NULL)
                {
                $this->db->query('INSERT INTO `'.DB_PREFIX."order` SET invoice_prefix = '".$this->db->escape($data['invoice_prefix'])."', parent_approval='".$parent_approval."', order_status_id='0', store_id = '".(int) $data['store_id']."', store_name = '".$this->db->escape($data['store_name'])."', store_url = '".$this->db->escape($data['store_url'])."', customer_id = '".(int) $data['customer_id']."', customer_group_id = '".(int) $data['customer_group_id']."', firstname = '".$this->db->escape($data['firstname'])."', lastname = '".$this->db->escape($data['lastname'])."', email = '".$this->db->escape($data['email'])."', telephone = '".$this->db->escape($data['telephone'])."', fax = '".$this->db->escape($data['fax'])."', custom_field = '".$this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '')."', payment_method = '".$this->db->escape($data['payment_method'])."', payment_code = '".$this->db->escape($data['payment_code'])."',shipping_method = '".$this->db->escape($data['shipping_method'])."', shipping_code = '".$this->db->escape($data['shipping_code'])."', comment = '".$this->db->escape($data['comment'])."', total = '".(float) $data['total']."', affiliate_id = '".(int) $data['affiliate_id']."',marketing_id = '".(int) $data['marketing_id']."', latitude = '".$data['latitude']."',longitude = '".$data['longitude']."', tracking = '".$this->db->escape($data['tracking'])."', language_id = '".(int) $data['language_id']."', currency_id = '".(int) $data['currency_id']."', currency_code = '".$this->db->escape($data['currency_code'])."', currency_value = '".(float) $data['currency_value']."', ip = '".$this->db->escape($data['ip'])."', forwarded_ip = '".$this->db->escape($data['forwarded_ip'])."', user_agent = '".$this->db->escape($data['user_agent'])."', fixed_commission = '".$this->db->escape($data['fixed_commission'])."',commission = '".$this->db->escape($data['commission'])."',delivery_date = '".$this->db->escape(date('Y-m-d', strtotime($data['delivery_date'])))."',delivery_timeslot = '".$this->db->escape($data['delivery_timeslot'])."',  order_reference_number = '".$this->db->escape($data['order_reference_number'])."',  accept_language = '".$this->db->escape($data['accept_language'])."', date_added = NOW(), date_modified = NOW()");
                }
                else
                {
                $this->db->query('INSERT INTO `'.DB_PREFIX."order` SET invoice_prefix = '".$this->db->escape($data['invoice_prefix'])."', parent_approval='".$parent_approval."', order_status_id='0', store_id = '".(int) $data['store_id']."', store_name = '".$this->db->escape($data['store_name'])."', store_url = '".$this->db->escape($data['store_url'])."', customer_id = '".(int) $data['customer_id']."', customer_group_id = '".(int) $data['customer_group_id']."', firstname = '".$this->db->escape($data['firstname'])."', lastname = '".$this->db->escape($data['lastname'])."', email = '".$this->db->escape($data['email'])."', telephone = '".$this->db->escape($data['telephone'])."', fax = '".$this->db->escape($data['fax'])."', custom_field = '".$this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '')."', payment_method = '".$this->db->escape($data['payment_method'])."', payment_code = '".$this->db->escape($data['payment_code'])."',shipping_method = '".$this->db->escape($data['shipping_method'])."', shipping_code = '".$this->db->escape($data['shipping_code'])."', comment = '".$this->db->escape($data['comment'])."', total = '".(float) $data['total']."', affiliate_id = '".(int) $data['affiliate_id']."',marketing_id = '".(int) $data['marketing_id']."', latitude = '".$data['latitude']."',longitude = '".$data['longitude']."', tracking = '".$this->db->escape($data['tracking'])."', language_id = '".(int) $data['language_id']."', currency_id = '".(int) $data['currency_id']."', currency_code = '".$this->db->escape($data['currency_code'])."', currency_value = '".(float) $data['currency_value']."', ip = '".$this->db->escape($data['ip'])."', forwarded_ip = '".$this->db->escape($data['forwarded_ip'])."', user_agent = '".$this->db->escape($data['user_agent'])."', fixed_commission = '".$this->db->escape($data['fixed_commission'])."',commission = '".$this->db->escape($data['commission'])."',delivery_date = '".$this->db->escape(date('Y-m-d', strtotime($data['delivery_date'])))."',delivery_timeslot = '".$this->db->escape($data['delivery_timeslot'])."',  order_reference_number = '".$this->db->escape($data['order_reference_number'])."',  accept_language = '".$this->db->escape($data['accept_language'])."', date_added = NOW(), date_modified = NOW() , login_latitude = '".$data['login_latitude']."',login_longitude = '".$data['login_longitude']."', login_mode = '".$this->db->escape($data['login_mode'])."'");
                }
                
                $order_id = $this->db->getLastId();

                $order_ids[] = $order_id;

                $this->db->query('UPDATE `'.DB_PREFIX.'order` SET '
                        ."shipping_city_id = '".$this->db->escape($data['shipping_city_id'])."', "
                        ."shipping_contact_no = '".$this->db->escape($data['shipping_contact_no'])."', "
                        ."shipping_address = '".$this->db->escape($data['shipping_address'])."', "
                        ."shipping_flat_number = '".$this->db->escape($data['shipping_flat_number'])."', "
                        ."shipping_building_name = '".$this->db->escape($data['shipping_building_name'])."', "
                        ."shipping_landmark = '".$this->db->escape($data['shipping_landmark'])."', "
                        ."shipping_zipcode = '".$this->db->escape($data['shipping_zipcode'])."', "
                        ."shipping_name = '".$this->db->escape($data['shipping_name'])."' "
                        ."WHERE order_id='".$order_id."'");

                if (isset($data['products'])) {
                    foreach ($data['products'] as $product) {
                        $this->db->query('INSERT INTO '.DB_PREFIX."order_product SET vendor_id='".(int) $product['vendor_id']."', store_id='".(int) $product['store_id']."', product_type='".$product['product_type']."', product_note='".$product['product_note']."', produce_type='".$product['produce_type']."', general_product_id='".$product['product_id']."', unit='".$product['unit']."', order_id = '".(int) $order_id."', variation_id = '".(int) $product['store_product_variation_id']."', product_id = '".(int) $product['product_store_id']."', name = '".$this->db->escape($product['name'])."', model = '".$this->db->escape($product['model'])."', quantity = '". $product['quantity']."', price = '".(float) $product['price']."', total = '".(float) $product['total']."', tax = '".(float) $product['tax']."', reward = '".(int) $product['reward']."'");
                    }
                }

                if (isset($data['totals'])) {
                    foreach ($data['totals'] as $total) {
                        if (isset($total['actual_value'])) {
                            $this->db->query('INSERT INTO '.DB_PREFIX."order_total SET order_id = '".(int) $order_id."', code = '".$this->db->escape($total['code'])."', title = '".$this->db->escape($total['title'])."', `value` = '".(float) $total['value']."', `actual_value` = '".(float) $total['actual_value']."', sort_order = '".(int) $total['sort_order']."'");
                        } else {
                            $this->db->query('INSERT INTO '.DB_PREFIX."order_total SET order_id = '".(int) $order_id."', code = '".$this->db->escape($total['code'])."', title = '".$this->db->escape($total['title'])."', `value` = '".(float) $total['value']."', sort_order = '".(int) $total['sort_order']."'");
                        }

                        //$this->db->query( "INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int) $order_id . "', code = '" . $this->db->escape( $total['code'] ) . "', title = '" . $this->db->escape( $total['title'] ) . "', `value` = '" . (float) $total['value'] . "', sort_order = '" . (int) $total['sort_order'] . "'" );
                    }
                }
            }
        }

        return $order_ids;
    }

    public function editOrder($order_id, $data)
    {
        $this->trigger->fire('pre.order.edit', $data);

        // Void the order first
        $this->addOrderHistory($order_id, 0);

        $store_id = 0;

        $this->db->query('UPDATE `'.DB_PREFIX.'order` SET '
                ."invoice_prefix = '".$this->db->escape($data['invoice_prefix'])."', "
                ."store_id = '".(int) $data['store_id']."', "
                ."store_name = '".$this->db->escape($data['store_name'])."', "
                ."store_url = '".$this->db->escape($data['store_url'])."', "
                ."customer_id = '".(int) $data['customer_id']."', "
                ."customer_group_id = '".(int) $data['customer_group_id']."', "
                ."firstname = '".$this->db->escape($data['firstname'])."', "
                ."lastname = '".$this->db->escape($data['lastname'])."', "
                ."email = '".$this->db->escape($data['email'])."', "
                ."telephone = '".$this->db->escape($data['telephone'])."', "
                ."fax = '".$this->db->escape($data['fax'])."', "
                ."custom_field = '".$this->db->escape(serialize($data['custom_field']))."', "
                ."payment_method = '".$this->db->escape($data['payment_method'])."', "
                ."payment_code = '".$this->db->escape($data['payment_code'])."', "
                ."shipping_method = '".$this->db->escape($data['shipping_method'])."', "
                ."shipping_code = '".$this->db->escape($data['shipping_code'])."', "
                ."shipping_city_id = '".$this->db->escape($data['shipping_city_id'])."', "
                ."shipping_contact_no = '".$this->db->escape($data['shipping_contact_no'])."', "
                ."shipping_address = '".$this->db->escape($data['shipping_address'])."', "
                ."shipping_name = '".$this->db->escape($data['shipping_name'])."', "
                ."comment = '".$this->db->escape($data['comment'])."', "
                ."total = '".(float) $data['total']."', "
                ."commission = '".(float) $data['commission']."', "
                ."fixed_commission = '".(float) $data['fixed_commission']."', "
                ."delivery_date = '".date('Y-m-d', strtotime($data['delivery_date']))."', "
                ."delivery_timeslot = '".$data['delivery_timeslot']."', "
                .'date_modified = NOW()'
                ."WHERE order_id = '".(int) $order_id."'");

        $this->db->query('DELETE FROM '.DB_PREFIX."order_product WHERE order_id = '".(int) $order_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."order_option WHERE order_id = '".(int) $order_id."'");

        $stores = [];

        // Products
        if (isset($data['products'])) {
            foreach ($data['products'] as $product) {
                $this->db->query('INSERT INTO '.DB_PREFIX."order_product SET vendor_id='".(int) $product['vendor_id']."', store_id='".(int) $product['store_id']."', product_type='".$product['product_type']."', order_id = '".(int) $order_id."', variation_id = '".(int) $product['store_product_variation_id']."', product_id = '".(int) $product['product_store_id']."', name = '".$this->db->escape($product['name'])."', model = '".$this->db->escape($product['model'])."', quantity = '". $product['quantity']."', price = '".(float) $product['price']."', total = '".(float) $product['total']."', tax = '".(float) $product['tax']."', reward = '".(int) $product['reward']."'");
            }
        }

        // Totals
        $this->db->query('DELETE FROM '.DB_PREFIX."order_total WHERE order_id = '".(int) $order_id."'");

        if (isset($data['totals'])) {
            foreach ($data['totals'] as $total) {
                $this->db->query('INSERT INTO '.DB_PREFIX."order_total SET order_id = '".(int) $order_id."', code = '".$this->db->escape($total['code'])."', title = '".$this->db->escape($total['title'])."', `value` = '".(float) $total['value']."', sort_order = '".(int) $total['sort_order']."'");
            }
        }

        $this->trigger->fire('post.order.edit', $order_id);
    }

    public function deleteOrder($order_id)
    {
        $this->trigger->fire('pre.order.delete', $order_id);
        $log = new Log('error.log');
        // Void the order first
        $log->write('deleteorder 1');
        $log->write($order_id);
        $this->addOrderHistory($order_id, 0);

        $this->db->query('DELETE FROM `'.DB_PREFIX."order` WHERE order_id = '".(int) $order_id."'");
        $this->db->query('DELETE FROM `'.DB_PREFIX."order_custom_field` WHERE order_id = '".(int) $order_id."'");
        $this->db->query('DELETE FROM `'.DB_PREFIX."order_fraud` WHERE order_id = '".(int) $order_id."'");
        $this->db->query('DELETE FROM `'.DB_PREFIX."order_history` WHERE order_id = '".(int) $order_id."'");
        $this->db->query('DELETE FROM `'.DB_PREFIX."order_option` WHERE order_id = '".(int) $order_id."'");
        $this->db->query('DELETE FROM `'.DB_PREFIX."order_product` WHERE order_id = '".(int) $order_id."'");
        $this->db->query('DELETE `or`, ort FROM `'.DB_PREFIX.'order_recurring` `or`, `'.DB_PREFIX."order_recurring_transaction` `ort` WHERE order_id = '".(int) $order_id."' AND ort.order_recurring_id = `or`.order_recurring_id");
        $this->db->query('DELETE FROM `'.DB_PREFIX."order_request` WHERE order_id = '".(int) $order_id."'");
        $this->db->query('DELETE FROM `'.DB_PREFIX."order_total` WHERE order_id = '".(int) $order_id."'");
        $this->db->query('DELETE FROM `'.DB_PREFIX."order_voucher` WHERE order_id = '".(int) $order_id."'");
        //$this->db->query( "DELETE `po`, pot FROM `" . DB_PREFIX . "paypal_order` `po`, `" . DB_PREFIX . "paypal_order_transaction` `pot` WHERE order_id = '" . (int) $order_id . "' AND pot.paypal_order_id = `po`.paypal_order_id" );
        // Gift Voucher
        $this->load->model('checkout/voucher');

        $this->model_checkout_voucher->disableVoucher($order_id);

        $this->trigger->fire('post.order.delete', $order_id);
    }

    public function getOrder($order_id)
    {
        $order_query = $this->db->query('SELECT *, (SELECT os.name FROM `'.DB_PREFIX.'order_status` os WHERE os.order_status_id = o.order_status_id AND os.language_id = o.language_id) AS order_status FROM `'.DB_PREFIX."order` o WHERE o.order_id = '".(int) $order_id."'");

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
                'delivery_timeslot' => $order_query->row['delivery_timeslot'],
                'dropoff_latitude' => $order_query->row['latitude'],
                'dropoff_longitude' => $order_query->row['longitude'],
                    /* 'date_modified' => $order_query->row['date_modified'],
                      'date_added' => $order_query->row['date_added'] */
            ];
        } else {
            return false;
        }
    }

    public function refundToCustomerWallet($order_id)
    {
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
                $log->write('refundToCustomerWallet');
                //referee points below
                $description = 'Refund of order#'.$order_id;
                $this->model_account_activity->addCredit($order_info['customer_id'], $description, $order_info['total'], $order_id);
            }
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

                $log->write('status'.$status);
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
                $log->write('ReadyForPickupStatus elsex');
                $deliverSystemStatus = $this->config->get('config_deliver_system_status');

                $checkoutDeliverSystemStatus = $this->config->get('config_checkout_deliver_system_status');

                if ($deliverSystemStatus && !$checkoutDeliverSystemStatus) {
                    $allowedShippingMethods = $this->config->get('config_delivery_shipping_methods_status');

                    //$log->write("ReadyForPickupStatus elsex");
                    if (is_array($allowedShippingMethods) && count($allowedShippingMethods) > 0) {
                        foreach ($allowedShippingMethods as $method) {
                            if ($order_info['shipping_code'] == $method.'.'.$method) {
                                $deliverSystemStatus = true;
                            }
                        }
                    }
                } else {
                    $deliverSystemStatus = false;
                }

                if ($deliverSystemStatus) {
                    $log->write('kondutoStatus else');
                    $this->createDeliveryRequest($order_id, $order_status_id);
                } else {
                    $log->write('deliverSystemStatus elsex');
                }
            }

            if ($order_status_id > 0) {
                $this->db->query('UPDATE `'.DB_PREFIX."order` SET order_status_id = '".(int) $order_status_id."', order_pdf_link ='".$pdf_link."', date_modified = NOW() WHERE order_id = '".(int) $order_id."'");

                $this->db->query('INSERT INTO '.DB_PREFIX."order_history SET order_id = '".(int) $order_id."', order_status_id = '".(int) $order_status_id."', notify = '".(int) $notify."', comment = '".$this->db->escape($comment)."', date_added = NOW()");
            } else {
                // FOR SUB USERS ORDERS
                $log->write('Add Order History Method');
                $log->write($order_status_id.'Add Order History Method');
                $log->write('Add Order History Method');
                $this->load->model('account/customer');
                $is_he_parents = $this->model_account_customer->CheckHeIsParent();
                $order_status_id_sub = null == $is_he_parents ? 14 : 15;
                $log->write($order_status_id.'Add Order History Method222');
                $query_order_history = $this->db->query('SELECT COUNT(*) AS total FROM `'.DB_PREFIX."order_history` o WHERE order_id = '".(int) $order_id."'");

                if (null != $is_he_parents) {
                    $this->db->query('UPDATE `'.DB_PREFIX."order` SET order_status_id = '".(int) $order_status_id_sub."', order_pdf_link ='".$pdf_link."', date_modified = NOW() WHERE order_id = '".(int) $order_id."'");
                    if (0 == $query_order_history->row['total']) {
                        $this->db->query('INSERT INTO '.DB_PREFIX."order_history SET order_id = '".(int) $order_id."', order_status_id = '".(int) $order_status_id_sub."', notify = '".(int) $notify."', comment = '".$this->db->escape($comment)."', date_added = NOW()");
                    }
                } elseif (null == $is_he_parents && 0 == $order_status_id) {
                    $log->write($order_status_id.'MAIN USERS ORDERS');
                    $this->db->query('UPDATE `'.DB_PREFIX."order` SET order_status_id = '".(int) $order_status_id_sub."', order_pdf_link ='".$pdf_link."', date_modified = NOW() WHERE order_id = '".(int) $order_id."'");
                    if (0 == $query_order_history->row['total']) {
                        $this->db->query('INSERT INTO '.DB_PREFIX."order_history SET order_id = '".(int) $order_id."', order_status_id = '".(int) $order_status_id_sub."', notify = '".(int) $notify."', comment = '".$this->db->escape($comment)."', date_added = NOW()");
                    }
                }
            }

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

                $log = new Log('error.log');

                //$log->write('PayPal Express debug '. print_r($order_total_query));

                $log->write('processnig inside');

                foreach ($order_total_query->rows as $order_total) {
                    $this->load->model('total/'.$order_total['code']);

                    /* $log->write('PayPal Express debug '. $order_total['code']."s"); */
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

                $log->write('insisde refund status');
                foreach ($order_total_query->rows as $order_total) {
                    $this->load->model('total/'.$order_total['code']);

                    if (method_exists($this->{'model_total_'.$order_total['code']}, 'unconfirm')) {
                        $this->{'model_total_'.$order_total['code']}->unconfirm($order_id);
                    }
                }

                // Remove credit and reward points recieved on order complete
                $this->load->model('total/credit');
                $this->load->model('total/reward');

                $this->model_total_credit->unconfirm($order_id);
                $this->model_total_reward->unconfirm($order_id);
            }

            $log->write('refund outer');

            // 11 refunded and 7 cancelled. refund and cancel logic
            if (in_array($order_info['order_status_id'], $this->config->get('config_refund_status'))) {
                $log->write('refund if');
                //check if payment done via iugu then call refund API
                if ('iugu_credit_card' == $order_info['payment_code']) {
                    //refund successfull
                    $log->write('refund another if');
                    $this->refundIuguAPI($order_id);
                }
            }

            $log->write('refund end');
            // refund and cancel logic end
            $this->cache->delete('product');

            if (in_array($order_status_id, array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status')))) {
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

                $log->write($order_info['order_status_id']);
                $log->write($order_status_id);
                //Send Email
                if ((!$order_info['order_status_id'] && $order_status_id) || ($order_info['order_status_id'] && $order_status_id && $notify)) {
                    $log->write('in if');
                    /* vendor mail sending */
                    $tempVendorInfo = $this->db->query('select * from '.DB_PREFIX.'order LEFT JOIN '.DB_PREFIX.'store on('.DB_PREFIX.'store.store_id = '.DB_PREFIX.'order.store_id) WHERE '.DB_PREFIX.'order.store_id="'.$order_info['store_id'].'" AND order_id="'.$order_id.'"')->row;

                    $vendorData = $this->getVendorDetails($tempVendorInfo['vendor_id']);

                    //$log->write($vendorData);
                    $log->write('vendorData');
                    if (isset($vendorData['email'])) {
                        // 7 merchant mail
                        $log->write('vendot if');
                        $vendorData['order_id'] = $order_id;
                        $vendorData['order_link'] = HTTPS_ADMIN.'index.php?path=sale/order/info&order_id='.$order_id;

                        $log->write('1');
                        $subject = $this->emailtemplate->getSubject('Contact', 'contact_7', $vendorData);

                        $log->write('2');
                        $message = $this->emailtemplate->getMessage('Contact', 'contact_7', $vendorData);

                        $mail = new mail($this->config->get('config_mail'));

                        $mail->setTo($this->config->get('config_email'));

                        $mail->setFrom($this->config->get('config_from_email'));

                        $mail->setSender($this->config->get('config_name'));

                        $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));

                        $mail->setHtml(html_entity_decode(strip_tags($message), ENT_QUOTES, 'UTF-8'));

                        $mail->send();

                        $log->write('end');
                    }

                    $log->write('vendro end');
                    /* vendor mail end */
                    if($customer_info['email_notification'] == 1) {
                    $subject = $this->emailtemplate->getSubject('OrderAll', 'order_'.(int) $order_status_id, $data);
                    $message = $this->emailtemplate->getMessage('OrderAll', 'order_'.(int) $order_status_id, $data);
                    //$log->write($message);
                    //die;
                    $sms_message = $this->emailtemplate->getSmsMessage('OrderAll', 'order_'.(int) $order_status_id, $data);

                    $log->write('mail stsart');
                    $log->write($subject);
                    $log->write($message);
                    $log->write($order_info['email']);
                    $log->write($this->config->get('config_email'));

                    $mail = new mail($this->config->get('config_mail'));
                    $mail->setTo($order_info['email']);
                    $mail->setFrom($this->config->get('config_from_email'));
                    $mail->setSender($order_info['store_name']);
                    $mail->setSubject($subject);
                    $mail->setHtml($message);
                    $mail->setText($text);
                    $mail->send();

                    $log->write('mail end');
                    }

                    if ($customer_info['sms_notification'] == 1 && $this->emailtemplate->getSmsEnabled('OrderAll', 'order_'.(int) $order_status_id)) {
                        $ret = $this->emailtemplate->sendmessage($order_info['telephone'], $sms_message);
                    }

                    $log->write('outside mobi noti');
                    if ($this->emailtemplate->getNotificationEnabled('OrderAll', 'order_'.(int) $order_status_id)) {
                        $log->write('status enabled of mobi noti');
                        $mobile_notification_template = $this->emailtemplate->getNotificationMessage('OrderAll', 'order_'.(int) $order_status_id, $data);

                        $log->write($mobile_notification_template);

                        $mobile_notification_title = $this->emailtemplate->getNotificationTitle('OrderAll', 'order_'.(int) $order_status_id, $data);

                        $log->write($mobile_notification_title);

                        $temporaryVendorInfo = $this->db->query('select * from '.DB_PREFIX.'order LEFT JOIN '.DB_PREFIX.'store on('.DB_PREFIX.'store.store_id = '.DB_PREFIX.'order.store_id) WHERE '.DB_PREFIX.'order.store_id="'.$order_info['store_id'].'" AND order_id="'.$order_id.'"')->row;

                        $vendorData = $this->getVendorDetails($temporaryVendorInfo['vendor_id']);

                        $log->write($vendorData);

                        if (isset($vendorData['device_id']) && strlen($vendorData['device_id']) > 0) {
                            $log->write('VENDOR MOBILE PUSH NOTIFICATION device id set FRONT.MODEL.API.CHECKOUT');
                            $ret = $this->emailtemplate->sendPushNotification($temporaryVendorInfo['vendor_id'], $vendorData['device_id'], $order_id, $order_info['store_id'], $mobile_notification_template, $mobile_notification_title);

                            $this->saveVendorNotification($temporaryVendorInfo['vendor_id'], $vendorData['device_id'], $order_id, $mobile_notification_template, $mobile_notification_title);
                        } else {
                            $log->write('VENDOR MOBILE PUSH NOTIFICATION device id not set FRONT.MODEL.API.CHECKOUT');
                        }
                    }

                    if ($this->config->get('config_order_mail')) {
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

            if (in_array($order_status_id, $this->config->get('config_complete_status'))) {
                //completed order
                //reward on order complete

                $config_reward_switch_order_value = $this->config->get('config_reward_switch_order_value');
                $config_reward_on_order_total = $this->config->get('config_reward_on_order_total');
                $config_reward_enabled = $this->config->get('config_reward_enabled');

                $total = $order_info['total'];

                if ($config_reward_enabled) {
                    // get order detail by order id
                    if ('p' == $config_reward_switch_order_value) {
                        // its Percentage
                        $points = ($total * $config_reward_on_order_total) / 100;
                    } else {
                        // its Fixed value
                        $points = $config_reward_on_order_total;
                    }

                    $log->write('completed status');
                    $log->write('completed status'.$order_info['customer_id']);
                    $log->write($order_info['order_id']);

                    $this->load->language('checkout/success');

                    $log->write($this->language->get('text_order_id'));

                    $this->setCustomerReward($order_info['customer_id'], $order_info['order_id'], $this->language->get('text_order_id'), $points);
                }

                //credit on order complete

                $config_credit_switch_order_value = $this->config->get('config_credit_switch_order_value');
                $config_credit_on_order_total = $this->config->get('config_credit_on_order_total');
                $config_credit_enabled = $this->config->get('config_credit_enabled');

                $total = $order_info['total'];

                if ($config_credit_enabled) {
                    // get order detail by order id
                    if ('p' == $config_credit_switch_order_value) {
                        // its Percentage
                        $points = ($total * $config_credit_on_order_total) / 100;
                    } else {
                        // its Fixed value
                        $points = $config_credit_on_order_total;
                    }

                    $this->load->language('checkout/success');

                    $creditDescription = sprintf($this->language->get('text_order_id'), $order_info['order_id']);

                    //$log->write($this->language->get('text_order_id'));

                    $this->load->model('account/activity');

                    $this->model_account_activity->addCredit($order_info['customer_id'], $creditDescription, $points, $order_info['order_id']);
                }

                if ($config_credit_enabled) {
                    $this->load->language('checkout/success');
                    $points = 0;

                    $coupon_history_data = $this->db->query('SELECT amount FROM `'.DB_PREFIX."coupon_history` WHERE order_id = '".$order_info['order_id']."'")->row;

                    if (count($coupon_history_data) > 0) {
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

        return true;

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

    public function apiAddTransaction($data, $order_ids)
    {
        $order_id = implode(',', $order_ids);
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

    public function getVendorDetails($vendor_id)
    {
        $sql = 'select *, CONCAT(firstname," ",lastname) as name from `'.DB_PREFIX.'user`';
        $sql .= ' WHERE user_id="'.$vendor_id.'" AND user_group_id =11 LIMIT 1';

        return $this->db->query($sql)->row;
    }

    public function refundIuguAPI($order_id)
    {
        $this->load->model('payment/iugu');

        require_once DIR_SYSTEM.'library/Iugu.php';

        //$invoiceId = "9EC5B44FACD047B7A280CD4E750D1A8B";

        $sql = 'select * from `'.DB_PREFIX.'order_iugu`';
        $sql .= ' WHERE order_id="'.$order_id.'" LIMIT 1';

        $iuguData = $this->db->query($sql)->row;

        if ($iuguData) {
            $invoiceId = $iuguData['invoice_id'];

            Iugu::setApiKey($this->config->get('iugu_token'));

            $invoice = Iugu_Invoice::fetch($invoiceId);
            $result = $invoice->refund();
            //true

            if (1 == $result) {
                //success
                return true;
            }
        }

        return false;
    }

    public function saveIuguCustomer($data)
    {
        $this->db->query('INSERT INTO '.DB_PREFIX."customer_to_customer_iugu SET customer_id = '".$data['customer_id']."', iugu_customer_id = '".$data['id']."'");
    }

    public function saveVendorNotification($user_id, $deviceId, $order_id, $message, $title)
    {
        $this->db->query('INSERT INTO '.DB_PREFIX."vendor_notifications SET user_id = '".$user_id."', type = 'order', purpose_id = '".$order_id."', title = '".$title."', message = '".$message."', status = 'unread', created_at = NOW() , updated_at = NOW()");
    }

    public function createDeliveryRequest($order_id, $order_status_id = 1)
    {
        $log = new Log('error.log');
        $log->write('createDeliveryRequest here in admin/model/api/checkout');
        $order_info = $this->getOrder($order_id);
        $this->load->language('checkout/success');

        $this->load->model('account/order');

        $deliveryAlreadyCreated = $this->getOrderDeliveryId($order_id);

        if ($order_info && !$deliveryAlreadyCreated) {
            $log->write('inside createDeliveryRequest');

            $data['products']['products'] = [];
            $weight = 0;

            $products = $this->model_account_order->getOrderProducts($order_id);

            foreach ($products as $product) {
                $weight += ($product['weight'] * $product['quantity']);

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
                    'product_name' => htmlspecialchars_decode($product['name']),
                    'product_unit' => $product['unit'],
                    'product_quantity' => $product['quantity'],
                    'product_image' => $image, //"http:\/\/\/product-images\/camera.jpg",
                    'product_price' => $product['price'], //"1500.00",//product price unit price?? or total
                    'product_replaceable' => $replacable, //"no"
                ];

                array_push($data['products']['products'], $var);
            }
            $log->write($data['products']['products']);

            $data['text_weight'] = sprintf($this->language->get('text_weight'), $weight);

            $store_details = $this->model_account_order->getStoreById($order_info['store_id']);

            $log->write($store_details);

            $delivery_priority = 'normal';

            $temp = explode('.', $order_info['shipping_code']);
            if (isset($temp[0])) {
                $delivery_priority = $temp[0];
            }

            $store_city_name = $this->model_account_order->getCityName($store_details['city_id']);
            $store_state_name = $this->model_account_order->getCityState($store_details['city_id']);

            $timeSlotAverage = $this->getTimeslotAverage($order_info['delivery_timeslot']);

            $deliverAddress = $order_info['shipping_flat_number'].', '.$order_info['shipping_building_name'].', '.$order_info['shipping_landmark'];

            $this->load->model('sale/order');

            $new_total = 0;

            $totals = $this->model_sale_order->getOrderTotals($order_id);

            foreach ($totals as $total) {
                if ('total' == $total['code']) {
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
                'pickup_address' => $store_details['address'],
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

            $log->write('body_data sent');
            $log->write($data['body']);
            $data['email'] = $this->config->get('config_delivery_username');
            $data['password'] = $this->config->get('config_delivery_secret');
            $response = $this->load->controller('deliversystem/deliversystem/getToken', $data);

            if ($response['status']) {
                $data['token'] = $response['token'];
                $res = $this->load->controller('deliversystem/deliversystem/createDelivery', $data);

                //$res['status'] = false;
                if ($res['status']) {
                    $log->write('stsus');
                    if (isset($res['data']->delivery_id)) {
                        $delivery_id = $res['data']->delivery_id;
                        $this->db->query('UPDATE `'.DB_PREFIX."order` SET delivery_id = '".$delivery_id."', date_modified = NOW() WHERE order_id = '".(int) $order_id."'");
                    }
                    //save in order table delivery id
                }
            }
        }
    }

    public function isOnlinePayment($payment_code)
    {
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

    public function getOrderDeliveryId($order_id)
    {
        $s = $this->db->query('Select delivery_id from `'.DB_PREFIX."order` WHERE order_id = '".(int) $order_id."'");
        if ($s->num_rows && !empty($s->row['delivery_id'])) {
            return true;
        }

        return false;
    }
}
