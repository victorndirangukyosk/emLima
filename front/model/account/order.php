<?php

class ModelAccountOrder extends Model {

    public function getStatusById($order_status_id) {
        $row = $this->db->query('select * from ' . DB_PREFIX . 'order_status where language_id="' . $this->config->get('config_language_id') . '" AND order_status_id="' . $order_status_id . '"')->row;
        if ($row) {
            return $row['name'];
        }
    }

    public function getStripeOrderPaymentId($order_id) {
        $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . "stripe_order` where `order_id` = '" . (int) $order_id . "' LIMIT 1");

        if ($query->num_rows) {
            return $query->row;
        } else {
            return false;
        }
    }

    public function getOrderDetailsById($order_id) {
        $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . "order` where `order_id` = '" . (int) $order_id . "' LIMIT 1");
        return $query->row;
    }

    public function updateNewShippingAddress($order_id, $data) {
        $data['shipping_address'] = !empty($data['shipping_flat_number']) ? $data['shipping_flat_number'] . ', ' . $data['shipping_landmark'] : $data['shipping_landmark'];

        $this->db->query(
                'UPDATE `' . DB_PREFIX . "order` SET latitude = '" . $this->db->escape($data['shipping_latitude']) .
                "', longitude = '" . $data['shipping_longitude'] .
                "', shipping_landmark = '" . $data['shipping_landmark'] .
                "', shipping_flat_number = '" . $data['shipping_flat_number'] .
                "', shipping_zipcode = '" . $data['shipping_zipcode'] .
                "', shipping_building_name = '" . $data['shipping_building_name'] .
                "', shipping_address = '" . $data['shipping_address'] .
                "', date_modified = NOW() WHERE order_id = '" . (int) $order_id .
                "'");

        return true;
    }

    public function updateOnlyFlatNumber($order_id, $data, $old_address) {
        $data['shipping_address'] = !empty($data['shipping_flat_number']) ? $data['shipping_flat_number'] . ', ' . $old_address['shipping_landmark'] : $old_address['shipping_landmark'];

        $this->db->query(
                'UPDATE `' . DB_PREFIX . "order` SET
             shipping_flat_number = '" . $data['shipping_flat_number'] .
                "', shipping_address = '" . $data['shipping_address'] .
                "', date_modified = NOW() WHERE order_id = '" . (int) $order_id .
                "'");

        return true;
    }

    public function editTimeslotOrder($order_id, $data) {
        $this->db->query('UPDATE `' . DB_PREFIX . "order` SET delivery_date = '" . $this->db->escape(date('Y-m-d', strtotime($data['delivery_date']))) . "', delivery_timeslot = '" . $data['delivery_timeslot'] . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");

        return true;
    }

    public function getOrderDSDeliveryId($order_id) {
        $s = $this->db->query('Select delivery_id from `' . DB_PREFIX . "order` WHERE order_id = '" . (int) $order_id . "'");
        if ($s->num_rows && !empty($s->row['delivery_id'])) {
            return $s->row['delivery_id'];
        }

        return false;
    }

    public function getNonDSCreatedOrders($pickupStatus) {
        $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . "order` o WHERE o.delivery_id is null  AND DATE(o.delivery_date) >= DATE('" . $this->db->escape(date('Y-m-d', strtotime('-1 day'))) . "') and o.order_status_id in (" . implode(',', $pickupStatus) . ')');

        //echo "<pre>";print_r($query);die;
        return $query->rows;
    }

    public function getCashbackAmount($order_id) {
        return $this->db->query('SELECT amount FROM `' . DB_PREFIX . "coupon_history` WHERE order_id = '" . $order_id . "'")->row;
    }

    public function hasRealOrderProducts($order_id) {
        $sql = 'SELECT * FROM ' . DB_PREFIX . "real_order_product WHERE order_id = '" . (int) $order_id . "'";

        $query = $this->db->query($sql);

        if ($query->num_rows) {
            return true;
        }

        return false;
    }

    public function payment_status($order_id, $status, $store_id) {
        $log = new Log('error.log');

        $log->write('order payment distribution fn');

        $this->load->model('sale/order');

        /* $this->db->query('update `' . DB_PREFIX . 'order` SET commsion_received="' . $status . '" WHERE store_id="' . $store_id . '" AND order_id="' . $order_id . '"'); */

        $order_info = $this->db->query('select * from ' . DB_PREFIX . 'order LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = ' . DB_PREFIX . 'order.store_id) WHERE ' . DB_PREFIX . 'order.store_id="' . $store_id . '" AND order_id="' . $order_id . '"')->row;

        $order_sub_total = $order_info['total'];

        $shipping_charges = 0;

        $deduct_from_vendor_shipping = false;

        $order_total_info = $this->db->query('select * from ' . DB_PREFIX . 'order_total  WHERE order_id="' . $order_id . '" and code="shipping"')->row;

        if (is_array($order_total_info) && !is_null($order_total_info['actual_value'])) {
            $tempCalc = $order_total_info['value'] + 0;
            if (!$tempCalc) {
                $deduct_from_vendor_shipping = true;
            }

            $shipping_charges = $order_total_info['actual_value'];
        }

        //shipping_charges to be sent to delviery system

        $order_total_info = $this->db->query('select * from ' . DB_PREFIX . 'order_total  WHERE order_id="' . $order_id . '" and code="sub_total"')->row;

        if (is_array($order_total_info)) {
            $order_sub_total = $order_total_info['value'];
        }

        if (isset($order_info['settlement_amount'])) {
            $order_sub_total = $order_info['settlement_amount'];
        }

        //echo "<pre>";print_r($order_sub_total);die;
        //print_r($order_info);die;

        $temp_vendor_commision = 0;
        $temp_vendor_com = 0;

        $store_info = $this->getStoreData($store_id);

        //echo "<pre>";print_r($store_info);die;
        if ('category' == $store_info['commission_type']) {
            $order_products = $this->getOrderProductsAdminCopy($order_id, $store_id);

            //echo "<pre>";print_r($order_products);die;
            foreach ($order_products as $key => $value) {
                //this is product store id $value['product_id']

                $p_to_s_info = $this->db->query('select * from ' . DB_PREFIX . 'product_to_store  WHERE product_store_id=' . $value['product_id'])->row;

                if (count($p_to_s_info) > 0) {
                    $cat_info = [];

                    $cat_infos = $this->db->query('select * from ' . DB_PREFIX . 'product_to_category  WHERE product_id=' . $p_to_s_info['product_id'])->rows;

                    //echo "<pre>";print_r($cat_infos);die;
                    foreach ($cat_infos as $key => $value_tmp) {
                        $cat_temp = $this->db->query('select * from ' . DB_PREFIX . 'category  WHERE category_id=' . $value_tmp['category_id'])->row;

                        if (0 == $cat_temp['parent_id']) {
                            $cat_info = $value_tmp;
                        }
                    }

                    //echo "<pre>";print_r($cat_info);die;

                    if (count($cat_info) > 0) {
                        $cat_id = $cat_info['category_id'];

                        $cat_commission = $this->getStoreCategoryCommision($store_id, $cat_id);

                        //echo "<pre>";print_r($cat_commission);die;

                        if (count($cat_commission) > 0) {
                            $temp_vendor_com += ($value['total'] * $cat_commission['commission'] / 100);
                        } else {
                            // not present so applying stores commission
                            $temp_vendor_com += ($value['total'] * $order_info['commission'] / 100);
                        }
                    } else {
                        $temp_vendor_com += ($value['total'] * $order_info['commission'] / 100);
                    }
                }
            }

            //fixed subtraction at last

            $temp_vendor_com += $order_info['fixed_commission'];

            $vendor_commision = $order_sub_total - $temp_vendor_com;
            $admin_commision = $order_sub_total - $vendor_commision;
        } else {
            $vendor_commision_rate = $order_info['commission'];
            $vendor_commision = $order_sub_total - ($order_sub_total * $vendor_commision_rate / 100);

            //my added //if fixed is set subtract it
            $vendor_commision_fixed = $order_info['fixed_commission'];
            $vendor_commision = $vendor_commision - $vendor_commision_fixed;

            //end

            $shopper_commision = $order_info['shopper_commision'];
            $admin_commision = $order_sub_total - $vendor_commision;
        }

        if ($deduct_from_vendor_shipping) {
            $vendor_commision = $vendor_commision - $shipping_charges;
        }

        $order_total_info_tax = $this->db->query('select * from ' . DB_PREFIX . 'order_total  WHERE order_id="' . $order_id . '" and code="tax"')->row;

        if (is_array($order_total_info_tax) && isset($order_total_info_tax['value'])) {
            $vendor_commision += $order_total_info_tax['value'];
        }

        $log->write('front payment_status');

        $log->write('vendor_commision' . $vendor_commision);
        $log->write('admin_commision' . $admin_commision);
        $log->write('delivery sytem send' . $shipping_charges);
        $log->write('status' . $status);
        $log->write($order_info);

        $vendorCommision = 0;
        if (1 == $status && 1 == $order_info['commsion_received']) {
            $log->write('vendor_commision if ' . $vendor_commision);

            //echo "<pre>";print_r("s");die;
            //$vendorCommision = $vendor_commision;
            $vendorCommision = 0 - $vendor_commision;

            $this->db->query('insert into `' . DB_PREFIX . 'vendor_wallet` SET vendor_id="' . $order_info['vendor_id'] . '", order_id="' . $order_id . '", description="Order Value : ' . $this->currency->format($order_sub_total) . '", amount="' . $vendor_commision . '", date_added=NOW()');

            //Admin Waller add
            $this->db->query('insert into `' . DB_PREFIX . 'admin_wallet` SET  order_id="' . $order_id . '", description="admin commision", amount="' . $admin_commision . '", date_added=NOW()');

            if ('shopper.shopper' == $order_info['shipping_code']) {
                if ('cod' == $order_info['payment_code']) {
                    //debit order_total - shopper commision
                    $this->db->query('insert into `' . DB_PREFIX . 'shopper_wallet` SET shopper_id="' . $order_info['shopper_id'] . '", order_id="' . $order_id . '", description="shopper commision", amount="' . ($order_sub_total - $shopper_commision) . '", date_added=NOW()');
                } else {
                    //credit shopper commision
                    $this->db->query('insert into `' . DB_PREFIX . 'shopper_wallet` SET shopper_id="' . $order_info['shopper_id'] . '", order_id="' . $order_id . '", description="shopper commision", amount="' . $shopper_commision . '", date_added=NOW()');
                }
            }
        } else {
            $log->write('vendor_commision else' . $vendor_commision);
            //$vendorCommision = 0 -$vendor_commision;
            $vendorCommision = $vendor_commision;
            $this->db->query('insert into `' . DB_PREFIX . 'vendor_wallet` SET vendor_id="' . $order_info['vendor_id'] . '", order_id="' . $order_id . '", description="Order Value : ' . $this->currency->format($order_sub_total) . '", amount="' . $vendor_commision . '", date_added=NOW()');

            //Admin Wallet add
            $this->db->query('insert into `' . DB_PREFIX . 'admin_wallet` SET  order_id="' . $order_id . '", description="admin commision", amount="' . (0 - $admin_commision) . '", date_added=NOW()');

            if ('shopper.shopper' == $order_info['shipping_code']) {
                if ('cod' == $order_info['payment_code']) {
                    //debit order_total - shopper commision
                    $this->db->query('insert into `' . DB_PREFIX . 'shopper_wallet` SET shopper_id="' . $order_info['shopper_id'] . '", order_id="' . $order_id . '", description="shopper commision", amount="' . (0 - ($order_sub_total - $shopper_commision)) . '", date_added=NOW()');
                } else {
                    //credit shopper commision
                    $this->db->query('insert into `' . DB_PREFIX . 'shopper_wallet` SET shopper_id="' . $order_info['shopper_id'] . '", order_id="' . $order_id . '", description="shopper commision", amount="' . (0 - $shopper_commision) . '", date_added=NOW()');
                }
            }
        }

        $vendorData = $this->getVendorDetails($order_info['vendor_id']);

        //echo "<pre>";print_r($vendorData);die;

        if (isset($vendorData['email'])) {
            // 6 merchant mail
            $vendorData['amount'] = $vendorCommision;

            $vendorData['transaction_type'] = 'credited';

            if ($vendorData['amount'] <= 0) {
                $vendorData['transaction_type'] = 'debited';
            }

            $vendorData['amount'] = $this->currency->format($vendorData['amount']);

            $subject = $this->emailtemplate->getSubject('Contact', 'contact_6', $vendorData);
            $message = $this->emailtemplate->getMessage('Contact', 'contact_6', $vendorData);
            //mishramanjari15@gmail.com
            $mail = new mail($this->config->get('config_mail'));
            $mail->setTo($vendorData['email']);
            $mail->setFrom($this->config->get('config_from_email'));
            //$mail->setReplyTo($vendorData['email']);
            $mail->setSender($this->config->get('config_name'));
            $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
            $mail->setHtml(html_entity_decode(strip_tags($message), ENT_QUOTES, 'UTF-8'));
            $mail->send();
        }

        $log = new Log('error.log');
        if ($this->emailtemplate->getNotificationEnabled('Contact', 'contact_6')) {
            $vendorData['amount'] = $vendorCommision;
            $vendorData['transaction_type'] = 'credited';

            if ($vendorData['amount'] <= 0) {
                $vendorData['transaction_type'] = 'debited';
            }

            $vendorData['amount'] = $this->currency->format($vendorData['amount']);

            $log->write('status enabled of wallet mobi noti');
            $mobile_notification_template = $this->emailtemplate->getNotificationMessage('Contact', 'contact_6', $vendorData);

            $log->write($mobile_notification_template);

            $mobile_notification_title = $this->emailtemplate->getNotificationTitle('Contact', 'contact_6', $vendorData);

            $log->write($mobile_notification_title);

            $log->write($vendorData);

            if (isset($vendorData['device_id']) && strlen($vendorData['device_id']) > 0) {
                /* $log->write('device id set');
                  $ret =  $this->emailtemplate->sendVendorPushNotification($order_info['vendor_id'],$vendorData['device_id'],$order_id,$order_info['store_id'],$mobile_notification_template,$mobile_notification_title);

                  $this->saveVendorNotification($order_info['vendor_id'],$vendorData['device_id'],$order_id,$mobile_notification_template,$mobile_notification_title); */

                $log->write('VENDOR MOBILE PUSH NOTIFICATION device id set FRONT.MODEL.ACCOUNT.ORDER');

                $notification_id = $this->saveVendorNotification($order_info['vendor_id'], $vendorData['device_id'], $order_id, $mobile_notification_template, $mobile_notification_title);

                $sen['notification_id'] = $notification_id;

                $ret = $this->emailtemplate->sendVendorPushNotification($order_info['vendor_id'], $vendorData['device_id'], $order_id, $order_info['store_id'], $mobile_notification_template, $mobile_notification_title, $sen);
            } else {
                $log->write('VENDOR MOBILE PUSH NOTIFICATION device id not set FRONT.MODEL.ACCOUNT.ORDER');
            }
        }
    }

    public function saveVendorNotification($user_id, $deviceId, $order_id, $message, $title) {
        $this->db->query('INSERT INTO ' . DB_PREFIX . "vendor_notifications SET user_id = '" . $user_id . "', type = 'wallet', purpose_id = '" . $order_id . "', title = '" . $title . "', message = '" . $message . "', status = 'unread', created_at = NOW() , updated_at = NOW()");

        $notificaiton_id = $this->db->getLastId();

        return $notificaiton_id;
    }

    public function getVendorDetails($vendor_id) {
        $sql = 'select *, CONCAT(firstname," ",lastname) as name from `' . DB_PREFIX . 'user`';
        $sql .= ' WHERE user_id="' . $vendor_id . '" AND user_group_id =11 LIMIT 1';

        return $this->db->query($sql)->row;
    }

    public function getStoreData($store_id) {
        return $this->db->query('select * from ' . DB_PREFIX . 'store where store_id =' . $store_id . '')->row;
    }

    public function getStoreCategoryCommision($store_id, $category_id) {
        $sql = 'select * from `' . DB_PREFIX . 'store_category_commission`';
        $sql .= ' WHERE store_id="' . $store_id . '" and category_id="' . $category_id . '"  LIMIT 1';

        return $this->db->query($sql)->row;
    }

    public function getOrderProductsAdminCopy($order_id, $store_id = 0) {
        $sql = 'SELECT * FROM ' . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'";

        if ($store_id) {
            $sql .= " AND store_id='" . $store_id . "'";
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getOrder($order_id, $notLogin = false) {
        $s_users = [];
        $sub_users_od = [];
        $parent_user_id = NULL;
        $order_approval_access = $this->db->query('SELECT c.customer_id, c.parent FROM ' . DB_PREFIX . "customer c WHERE c.customer_id = '" . (int) $this->customer->getId() . "' AND c.order_approval_access = 1 AND (c.order_approval_access_role = 'head_chef' OR c.order_approval_access_role = 'procurement_person')");
        $order_approval_access_user = $order_approval_access->row;

        if (is_array($order_approval_access_user) && count($order_approval_access_user) > 0) {
            //$log->write('order_approval_access_user');
            //$log->write($order_approval_access_user);
            //$log->write('order_approval_access_user');
            $parent_user_id = $order_approval_access_user['parent'];
        }

        if ($parent_user_id != NULL) {
            $sub_users_query = $this->db->query('SELECT c.customer_id FROM ' . DB_PREFIX . "customer c WHERE parent = '" . (int) $parent_user_id . "'");
            $sub_users = $sub_users_query->rows;
            //$log->write('SUB USERS ORDERS');
            //$log->write($sub_users);
            //$log->write('SUB USERS ORDERS');
            $s_users = array_column($sub_users, 'customer_id');
            array_push($s_users, $order_approval_access_user['parent']);
            $sub_users_od = implode(',', $s_users);
        } else {
            $sub_users_query = $this->db->query('SELECT c.customer_id FROM ' . DB_PREFIX . "customer c WHERE parent = '" . (int) $this->customer->getId() . "'");
            $sub_users = $sub_users_query->rows;
            $s_users = array_column($sub_users, 'customer_id');

            array_push($s_users, $this->customer->getId());
            $sub_users_od = implode(',', $s_users);
        }

        if (false == $notLogin) {
            $order_query = $this->db->query('SELECT * ,' . DB_PREFIX . 'order.date_added as order_date ,' . DB_PREFIX . 'order.email as order_email ,' . DB_PREFIX . 'order.telephone as order_telephone FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'store ON ( ' . DB_PREFIX . 'store.store_id = ' . DB_PREFIX . 'order.store_id) LEFT JOIN ' . DB_PREFIX . 'order_status ON ( ' . DB_PREFIX . 'order_status.order_status_id = ' . DB_PREFIX . "order.order_status_id)  WHERE order_id = '" . (int) $order_id . "' AND customer_id IN (" . $sub_users_od . ') AND ' . DB_PREFIX . "order.order_status_id > '0' ");
            //$order_query = $this->db->query("SELECT * ," . DB_PREFIX . "order.date_added as order_date ," . DB_PREFIX . "order.email as order_email ," . DB_PREFIX . "order.telephone as order_telephone FROM `" . DB_PREFIX . "order` LEFT JOIN " . DB_PREFIX . "store ON ( " . DB_PREFIX . "store.store_id = " . DB_PREFIX . "order.store_id) LEFT JOIN " . DB_PREFIX . "order_status ON ( " . DB_PREFIX . "order_status.order_status_id = " . DB_PREFIX . "order.order_status_id)  WHERE order_id = '" . (int) $order_id . "' AND customer_id = '" . (int) $this->customer->getId() . "' AND " . DB_PREFIX . "order.order_status_id > '0' ");
        } else {
            $order_query = $this->db->query('SELECT * ,' . DB_PREFIX . 'order.date_added as order_date ,' . DB_PREFIX . 'order.email as order_email ,' . DB_PREFIX . 'order.telephone as order_telephone FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'store ON ( ' . DB_PREFIX . 'store.store_id = ' . DB_PREFIX . 'order.store_id) LEFT JOIN ' . DB_PREFIX . 'order_status ON ( ' . DB_PREFIX . 'order_status.order_status_id = ' . DB_PREFIX . "order.order_status_id)  WHERE order_id = '" . (int) $order_id . "' AND " . DB_PREFIX . "order.order_status_id > '0' ");
        }
        if ($order_query->num_rows) {
            $city_info = $this->db->query('select * from `' . DB_PREFIX . 'city` WHERE city_id="' . $order_query->row['shipping_city_id'] . '"')->row;

            if ($city_info) {
                $shipping_city = $city_info['name'];
            } else {
                $shipping_city = '';
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
                'telephone' => $order_query->row['telephone'],
                'order_telephone' => $order_query->row['order_telephone'],
                'store_name' => $order_query->row['store_name'],
                'fax' => $order_query->row['fax'],
                'email' => $order_query->row['email'],
                'order_email' => $order_query->row['order_email'],
                'payment_method' => $order_query->row['payment_method'],
                'payment_code' => $order_query->row['payment_code'],
                'shipping_name' => $order_query->row['shipping_name'],
                'shipping_city_id' => $order_query->row['shipping_city_id'],
                'shipping_city' => $shipping_city,
                'shipping_address' => $order_query->row['shipping_address'],
                'shipping_flat_number' => $order_query->row['shipping_flat_number'],
                'shipping_building_name' => $order_query->row['shipping_building_name'],
                'shipping_landmark' => $order_query->row['shipping_landmark'],
                'shipping_contact_no' => $order_query->row['shipping_contact_no'],
                'shipping_method' => $order_query->row['shipping_method'],
                'comment' => $order_query->row['comment'],
                'total' => $order_query->row['total'],
                'rating' => $order_query->row['rating'],
                'order_status_id' => $order_query->row['order_status_id'],
                'language_id' => $order_query->row['language_id'],
                'currency_id' => $order_query->row['currency_id'],
                'currency_code' => $order_query->row['currency_code'],
                'currency_value' => $order_query->row['currency_value'],
                'date_modified' => $order_query->row['date_modified'],
                'date_added' => $order_query->row['date_added'],
                'ip' => $order_query->row['ip'],
                'delivery_timeslot' => $order_query->row['delivery_timeslot'],
                'delivery_date' => $order_query->row['delivery_date'],
                'store_address' => $order_query->row['address'],
                'store_name' => $order_query->row['store_name'],
                'status' => $order_query->row['name'],
                'order_date' => $order_query->row['order_date'],
                'delivery_id' => $order_query->row['delivery_id'],
                'settlement_amount' => $order_query->row['settlement_amount'],
                'driver_id' => $order_query->row['driver_id'],
                'vehicle_number' => $order_query->row['vehicle_number'],
                'delivery_executive_id' => $order_query->row['delivery_executive_id'],
                'paid' => $order_query->row['paid'],
            ];
        } else {
            return false;
        }
    }

    public function getOrderByReferenceId($order_reference_number) {
        $order_query = $this->db->query('SELECT * ,' . DB_PREFIX . 'order.date_added as order_date ,' . DB_PREFIX . 'order.email as order_email ,' . DB_PREFIX . 'order.telephone as order_telephone FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'store ON ( ' . DB_PREFIX . 'store.store_id = ' . DB_PREFIX . 'order.store_id) LEFT JOIN ' . DB_PREFIX . 'order_status ON ( ' . DB_PREFIX . 'order_status.order_status_id = ' . DB_PREFIX . "order.order_status_id)  WHERE order_reference_number = '" . $order_reference_number . "' AND " . DB_PREFIX . "order.order_status_id > '0' ");

        if ($order_query->num_rows) {
            return true;
        } else {
            return false;
        }
    }

    public function getOrderByReferenceIdApi($order_reference_number) {
        $order_query = $this->db->query('SELECT * FROM `' . DB_PREFIX . "order` WHERE order_reference_number = '" . $order_reference_number . "'");

        return $order_query->row;
    }

    public function getOrderByReferenceIdStoreIdApi($order_reference_number, $store_id) {
        $order_query = $this->db->query('SELECT * FROM `' . DB_PREFIX . "order` WHERE order_reference_number = '" . $order_reference_number . "' AND store_id = '" . (int) $store_id . "'");

        return $order_query->row;
    }

    public function getOrderByReferenceIdIPay($order_reference_number) {
        $order_query = $this->db->query('SELECT * FROM `' . DB_PREFIX . "order`  WHERE order_reference_number = '" . $order_reference_number . "'");

        if ($order_query->num_rows) {
            return $order_query->rows;
        } else {
            return false;
        }
    }

    public function getAdminOrder($order_id) {
        $order_query = $this->db->query('SELECT *, (SELECT os.name FROM `' . DB_PREFIX . 'order_status` os WHERE os.order_status_id = o.order_status_id AND os.language_id = o.language_id) AS order_status FROM `' . DB_PREFIX . "order` o WHERE o.order_id = '" . (int) $order_id . "'");

        if ($order_query->num_rows) {
            return $order_query->row;
        } else {
            return false;
        }
    }

    public function getOrders($start = 0, $limit = 20, $noLimit = false) {
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 1;
        }

        $log = new Log('error.log');
        $log->write('getOrders');
        $log->write($this->customer->getId());
        $log->write('getOrders');
        $s_users = [];
        $parent_user_id = NULL;
        $order_approval_access = $this->db->query('SELECT c.customer_id, c.parent FROM ' . DB_PREFIX . "customer c WHERE c.customer_id = '" . (int) $this->customer->getId() . "' AND c.order_approval_access = 1 AND (c.order_approval_access_role = 'head_chef' OR c.order_approval_access_role = 'procurement_person')");
        $order_approval_access_user = $order_approval_access->row;

        if (is_array($order_approval_access_user) && count($order_approval_access_user) > 0) {
            //$log->write('order_approval_access_user');
            //$log->write($order_approval_access_user);
            //$log->write('order_approval_access_user');
            $parent_user_id = $order_approval_access_user['parent'];
        }

        if ($parent_user_id != NULL) {
            $sub_users_query = $this->db->query('SELECT c.customer_id FROM ' . DB_PREFIX . "customer c WHERE parent = '" . (int) $parent_user_id . "' AND parent > 0");
            $sub_users = $sub_users_query->rows;
            //$log->write('SUB USERS ORDERS');
            //$log->write($sub_users);
            //$log->write('SUB USERS ORDERS');
            $s_users = array_column($sub_users, 'customer_id');
            array_push($s_users, $order_approval_access_user['parent']);
            $sub_users_od = implode(',', $s_users);
        } else {
            $sub_users_query = $this->db->query('SELECT c.customer_id FROM ' . DB_PREFIX . "customer c WHERE parent = '" . (int) $this->customer->getId() . "' AND parent > 0");
            $sub_users = $sub_users_query->rows;
            //$log->write('SUB USERS ORDERS');
            //$log->write($sub_users);
            //$log->write('SUB USERS ORDERS');
            $s_users = array_column($sub_users, 'customer_id');
            array_push($s_users, $this->customer->getId());
            $sub_users_od = implode(',', $s_users);
        }

        if (false == $noLimit) {
            //$sub_users_orders = $this->db->query("SELECT o.order_id FROM " . DB_PREFIX . "order o WHERE customer_id IN (".$sub_users_od.")");
            //$ord = $sub_users_orders->rows;
            //echo "<pre>";print_r($ord);die;

            $query = $this->db->query('SELECT o.customer_id, o.parent_approval, o.head_chef, o.procurement, o.delivery_date,o.delivery_timeslot,o.shipping_zipcode,o.shipping_city_id,o.payment_method,o.payment_code,o.shipping_address,o.shipping_flat_number,o.shipping_method,o.shipping_building_name,o.store_name,o.store_id,o.shipping_name, o.order_id, o.firstname, o.lastname, os.name as status , os.color as order_status_color ,o.order_status_id, o.date_modified , o.date_added, o.total, o.currency_code, o.currency_value, ot.value,o.amount_partialy_paid,o.paid FROM `' . DB_PREFIX . 'order` o LEFT JOIN ' . DB_PREFIX . 'order_status os ON (o.order_status_id = os.order_status_id) LEFT JOIN ' . DB_PREFIX . 'order_total ot ON (o.order_id = ot.order_id) WHERE o.customer_id IN (' . $sub_users_od . ") AND o.order_status_id > '0' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' AND ot.code = 'total' AND ot.title = 'Total' ORDER BY o.order_id DESC LIMIT " . (int) $start . ',' . (int) $limit);
            //$query = $this->db->query("SELECT o.delivery_date,o.delivery_timeslot,o.shipping_zipcode,o.shipping_city_id,o.payment_method,o.shipping_address,o.shipping_flat_number,o.shipping_method,o.shipping_building_name,o.store_name,o.shipping_name, o.order_id, o.firstname, o.lastname, os.name as status , os.color as order_status_color ,o.order_status_id, o.date_modified , o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '" . (int) $this->customer->getId() . "' AND o.order_status_id > '0' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY o.order_id DESC LIMIT " . (int) $start . "," . (int) $limit);
        } else {
            $query = $this->db->query('SELECT o.customer_id, o.parent_approval, o.head_chef, o.procurement, o.delivery_date,o.delivery_timeslot,o.shipping_zipcode,o.shipping_city_id,o.payment_method,o.payment_code,o.shipping_address,o.shipping_flat_number,o.shipping_method,o.shipping_building_name,o.store_name,o.store_id,o.shipping_name, o.order_id, o.firstname, o.lastname, os.name as status , os.color as order_status_color ,o.order_status_id, o.date_modified , o.date_added, o.total, o.currency_code, o.currency_value, ot.value,o.amount_partialy_paid,o.paid FROM `' . DB_PREFIX . 'order` o LEFT JOIN ' . DB_PREFIX . 'order_status os ON (o.order_status_id = os.order_status_id) LEFT JOIN ' . DB_PREFIX . 'order_total ot ON (o.order_id = ot.order_id) WHERE o.customer_id IN (' . $sub_users_od . ") AND o.order_status_id > '0' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' AND ot.code = 'total' AND ot.title = 'Total' ORDER BY o.order_id DESC");
            //$query = $this->db->query("SELECT o.delivery_date,o.delivery_timeslot,o.shipping_zipcode,o.shipping_city_id,o.payment_method,o.shipping_address,o.shipping_flat_number,o.shipping_method,o.shipping_building_name,o.store_name,o.shipping_name, o.order_id, o.firstname, o.lastname, os.name as status , os.color as order_status_color ,o.order_status_id, o.date_modified , o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '" . (int) $this->customer->getId() . "' AND o.order_status_id > '0' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY o.order_id DESC");
        }
        /* if($statuses == null && $payment_methods == null){
          $query = $this->db->query("SELECT o.delivery_date,o.delivery_timeslot,o.shipping_zipcode,o.shipping_city_id,o.payment_method,o.shipping_address,o.shipping_flat_number,o.shipping_method,o.shipping_building_name,o.store_name,o.shipping_name, o.order_id, o.firstname, o.lastname, os.name as status , os.color as order_status_color ,o.order_status_id, o.date_modified , o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '" . (int) $this->customer->getId() . "' AND o.order_status_id > '0' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY o.order_id DESC LIMIT " . (int) $start . "," . (int) $limit);
          }else{
          if($In == true){
          $sql = "SELECT o.delivery_date,o.delivery_timeslot,o.shipping_zipcode,o.shipping_city_id,o.payment_method,o.shipping_address,o.shipping_flat_number,o.shipping_method,o.shipping_building_name,o.store_name,o.shipping_name, o.order_id, o.firstname, o.lastname, os.name as status , os.color as order_status_color ,o.order_status_id, o.date_modified , o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '" . (int) $this->customer->getId() . "' AND o.order_status_id > '0' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "'  AND o.payment_method IN ($payment_methods) AND os.name IN ($statuses) ORDER BY o.order_id DESC";
          }else{
          $sql = "SELECT o.delivery_date,o.delivery_timeslot,o.shipping_zipcode,o.shipping_city_id,o.payment_method,o.shipping_address,o.shipping_flat_number,o.shipping_method,o.shipping_building_name,o.store_name,o.shipping_name, o.order_id, o.firstname, o.lastname, os.name as status , os.color as order_status_color ,o.order_status_id, o.date_modified , o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '" . (int) $this->customer->getId() . "' AND o.order_status_id > '0' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "'  AND o.payment_method IN ($payment_methods) AND os.name NOT IN ($statuses) ORDER BY o.order_id DESC";
          }
          $query = $this->db->query($sql);
          } */
        //$log->write('ORDERS COUNT');
        //$log->write(count($query->rows));
        //$log->write('ORDERS COUNT');

        return $query->rows;
    }

    public function getOrdersNew($start = 0, $limit = 20, $noLimit = false) {
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 1;
        }

        $log = new Log('error.log');
        $log->write('getOrders');
        $log->write($this->customer->getId());
        $log->write('getOrders');
        $s_users = [];
        $parent_user_id = NULL;
        $order_approval_access = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer c WHERE c.customer_id = '" . (int) $this->customer->getId() . "'");
        $order_approval_access_user = $order_approval_access->row;

        $parent_user_id = $order_approval_access_user['parent'];

        if ($parent_user_id != NULL && $parent_user_id > 0) {
            $sub_users_query = $this->db->query('SELECT c.customer_id FROM ' . DB_PREFIX . "customer c WHERE parent = '" . (int) $parent_user_id . "' AND parent > 0");
            $sub_users = $sub_users_query->rows;
            //$log->write('SUB USERS ORDERS');
            //$log->write($sub_users);
            //$log->write('SUB USERS ORDERS');
            $s_users = array_column($sub_users, 'customer_id');
            array_push($s_users, $order_approval_access_user['parent']);
            $sub_users_od = implode(',', $s_users);
        } else {
            $sub_users_query = $this->db->query('SELECT c.customer_id FROM ' . DB_PREFIX . "customer c WHERE parent = '" . (int) $this->customer->getId() . "' AND parent > 0");
            $sub_users = $sub_users_query->rows;
            //$log->write('SUB USERS ORDERS');
            //$log->write($sub_users);
            //$log->write('SUB USERS ORDERS');
            $s_users = array_column($sub_users, 'customer_id');
            array_push($s_users, $this->customer->getId());
            $sub_users_od = implode(',', $s_users);
        }

        if (false == $noLimit) {
            //$sub_users_orders = $this->db->query("SELECT o.order_id FROM " . DB_PREFIX . "order o WHERE customer_id IN (".$sub_users_od.")");
            //$ord = $sub_users_orders->rows;
            //echo "<pre>";print_r($ord);die;

            $query = $this->db->query('SELECT o.customer_id, o.parent_approval, o.head_chef, o.procurement, o.delivery_date,o.delivery_timeslot,o.shipping_zipcode,o.shipping_city_id,o.payment_method,o.payment_code,o.shipping_address,o.shipping_flat_number,o.shipping_method,o.shipping_building_name,o.store_name,o.store_id,o.shipping_name, o.order_id, o.firstname, o.lastname, os.name as status , os.color as order_status_color ,o.order_status_id, o.date_modified , o.date_added, o.total, o.currency_code, o.currency_value, ot.value,o.amount_partialy_paid,o.paid FROM `' . DB_PREFIX . 'order` o LEFT JOIN ' . DB_PREFIX . 'order_status os ON (o.order_status_id = os.order_status_id) LEFT JOIN ' . DB_PREFIX . 'order_total ot ON (o.order_id = ot.order_id) WHERE o.customer_id IN (' . $sub_users_od . ") AND o.order_status_id > '0' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' AND ot.code = 'total' AND ot.title = 'Total' ORDER BY o.order_id DESC LIMIT " . (int) $start . ',' . (int) $limit);
            //$query = $this->db->query("SELECT o.delivery_date,o.delivery_timeslot,o.shipping_zipcode,o.shipping_city_id,o.payment_method,o.shipping_address,o.shipping_flat_number,o.shipping_method,o.shipping_building_name,o.store_name,o.shipping_name, o.order_id, o.firstname, o.lastname, os.name as status , os.color as order_status_color ,o.order_status_id, o.date_modified , o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '" . (int) $this->customer->getId() . "' AND o.order_status_id > '0' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY o.order_id DESC LIMIT " . (int) $start . "," . (int) $limit);
        } else {
            $query = $this->db->query('SELECT o.customer_id, o.parent_approval, o.head_chef, o.procurement, o.delivery_date,o.delivery_timeslot,o.shipping_zipcode,o.shipping_city_id,o.payment_method,o.payment_code,o.shipping_address,o.shipping_flat_number,o.shipping_method,o.shipping_building_name,o.store_name,o.store_id,o.shipping_name, o.order_id, o.firstname, o.lastname, os.name as status , os.color as order_status_color ,o.order_status_id, o.date_modified , o.date_added, o.total, o.currency_code, o.currency_value, ot.value,o.amount_partialy_paid,o.paid FROM `' . DB_PREFIX . 'order` o LEFT JOIN ' . DB_PREFIX . 'order_status os ON (o.order_status_id = os.order_status_id) LEFT JOIN ' . DB_PREFIX . 'order_total ot ON (o.order_id = ot.order_id) WHERE o.customer_id IN (' . $sub_users_od . ") AND o.order_status_id > '0' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' AND ot.code = 'total' AND ot.title = 'Total' ORDER BY o.order_id DESC");
            //$query = $this->db->query("SELECT o.delivery_date,o.delivery_timeslot,o.shipping_zipcode,o.shipping_city_id,o.payment_method,o.shipping_address,o.shipping_flat_number,o.shipping_method,o.shipping_building_name,o.store_name,o.shipping_name, o.order_id, o.firstname, o.lastname, os.name as status , os.color as order_status_color ,o.order_status_id, o.date_modified , o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '" . (int) $this->customer->getId() . "' AND o.order_status_id > '0' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY o.order_id DESC");
        }
        /* if($statuses == null && $payment_methods == null){
          $query = $this->db->query("SELECT o.delivery_date,o.delivery_timeslot,o.shipping_zipcode,o.shipping_city_id,o.payment_method,o.shipping_address,o.shipping_flat_number,o.shipping_method,o.shipping_building_name,o.store_name,o.shipping_name, o.order_id, o.firstname, o.lastname, os.name as status , os.color as order_status_color ,o.order_status_id, o.date_modified , o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '" . (int) $this->customer->getId() . "' AND o.order_status_id > '0' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY o.order_id DESC LIMIT " . (int) $start . "," . (int) $limit);
          }else{
          if($In == true){
          $sql = "SELECT o.delivery_date,o.delivery_timeslot,o.shipping_zipcode,o.shipping_city_id,o.payment_method,o.shipping_address,o.shipping_flat_number,o.shipping_method,o.shipping_building_name,o.store_name,o.shipping_name, o.order_id, o.firstname, o.lastname, os.name as status , os.color as order_status_color ,o.order_status_id, o.date_modified , o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '" . (int) $this->customer->getId() . "' AND o.order_status_id > '0' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "'  AND o.payment_method IN ($payment_methods) AND os.name IN ($statuses) ORDER BY o.order_id DESC";
          }else{
          $sql = "SELECT o.delivery_date,o.delivery_timeslot,o.shipping_zipcode,o.shipping_city_id,o.payment_method,o.shipping_address,o.shipping_flat_number,o.shipping_method,o.shipping_building_name,o.store_name,o.shipping_name, o.order_id, o.firstname, o.lastname, os.name as status , os.color as order_status_color ,o.order_status_id, o.date_modified , o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '" . (int) $this->customer->getId() . "' AND o.order_status_id > '0' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "'  AND o.payment_method IN ($payment_methods) AND os.name NOT IN ($statuses) ORDER BY o.order_id DESC";
          }
          $query = $this->db->query($sql);
          } */
        //$log->write('ORDERS COUNT');
        //$log->write(count($query->rows));
        //$log->write('ORDERS COUNT');

        return $query->rows;
    }

    public function getOrdersForTransactions($start = 0, $limit = 20, $noLimit = false, $filters = []) {
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 1;
        }

        $log = new Log('error.log');
        $s_users = [];
        $parent_user_id = NULL;
        $order_approval_access = $this->db->query('SELECT c.customer_id, c.parent FROM ' . DB_PREFIX . "customer c WHERE c.customer_id = '" . (int) $this->customer->getId() . "' AND c.order_approval_access = 1 AND (c.order_approval_access_role = 'head_chef' OR c.order_approval_access_role = 'procurement_person')");
        $order_approval_access_user = $order_approval_access->row;

        if (is_array($order_approval_access_user) && count($order_approval_access_user) > 0) {
            $parent_user_id = $order_approval_access_user['parent'];
        }

        if ($parent_user_id != NULL) {
            $sub_users_query = $this->db->query('SELECT c.customer_id FROM ' . DB_PREFIX . "customer c WHERE parent = '" . (int) $parent_user_id . "'");
            $sub_users = $sub_users_query->rows;
            $s_users = array_column($sub_users, 'customer_id');
            array_push($s_users, $order_approval_access_user['parent']);
            $sub_users_od = implode(',', $s_users);
        } else {
            $sub_users_query = $this->db->query('SELECT c.customer_id FROM ' . DB_PREFIX . "customer c WHERE parent = '" . (int) $this->customer->getId() . "'");
            $sub_users = $sub_users_query->rows;
            $s_users = array_column($sub_users, 'customer_id');
            array_push($s_users, $this->customer->getId());
            $sub_users_od = implode(',', $s_users);
        }

        if (false == $noLimit) {
            $query = $this->db->query('SELECT o.customer_id, o.parent_approval, o.head_chef, o.procurement, o.delivery_date,o.delivery_timeslot,o.shipping_zipcode,o.shipping_city_id,o.payment_method,o.payment_code,o.shipping_address,o.shipping_flat_number,o.shipping_method,o.shipping_building_name,o.store_name,o.shipping_name, o.order_id, o.firstname, o.lastname, os.name as status , os.color as order_status_color ,o.order_status_id, o.date_modified , o.date_added, o.total, o.currency_code, o.currency_value, ot.value FROM `' . DB_PREFIX . 'order` o LEFT JOIN ' . DB_PREFIX . 'order_status os ON (o.order_status_id = os.order_status_id) LEFT JOIN ' . DB_PREFIX . 'order_total ot ON (o.order_id = ot.order_id) WHERE o.customer_id IN (' . $sub_users_od . ") AND o.order_status_id > '0' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' AND ot.code = 'total' AND ot.title = 'Total' AND o.order_status_id IN (" . $filters['filter_order_status_id'] . ") ORDER BY o.order_id DESC LIMIT " . (int) $start . ',' . (int) $limit);
        } else {
            $query = $this->db->query('SELECT o.customer_id, o.parent_approval, o.head_chef, o.procurement, o.delivery_date,o.delivery_timeslot,o.shipping_zipcode,o.shipping_city_id,o.payment_method,o.payment_code,o.shipping_address,o.shipping_flat_number,o.shipping_method,o.shipping_building_name,o.store_name,o.shipping_name, o.order_id, o.firstname, o.lastname, os.name as status , os.color as order_status_color ,o.order_status_id, o.date_modified , o.date_added, o.total, o.currency_code, o.currency_value, ot.value FROM `' . DB_PREFIX . 'order` o LEFT JOIN ' . DB_PREFIX . 'order_status os ON (o.order_status_id = os.order_status_id) LEFT JOIN ' . DB_PREFIX . 'order_total ot ON (o.order_id = ot.order_id) WHERE o.customer_id IN (' . $sub_users_od . ") AND o.order_status_id > '0' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' AND ot.code = 'total' AND ot.title = 'Total' AND o.order_status_id IN (" . $filters['filter_order_status_id'] . ") ORDER BY o.order_id DESC");
        }
        return $query->rows;
    }

    public function getOrderProduct($order_id, $order_product_id) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "' AND order_product_id = '" . (int) $order_product_id . "'");

        return $query->row;
    }

    public function getCityName($city_id) {
        $city_info = $this->db->query('select * from `' . DB_PREFIX . 'city` WHERE city_id="' . $city_id . '"')->row;

        if ($city_info) {
            $shipping_city = $city_info['name'];
        } else {
            $shipping_city = '';
        }

        return $shipping_city;
    }

    public function getCityState($city_id) {
        $shipping_state = '';

        $city_info = $this->db->query('select * from `' . DB_PREFIX . 'city` WHERE city_id="' . $city_id . '"')->row;

        if ($city_info) {
            $state_info = $this->db->query('select * from `' . DB_PREFIX . 'state` WHERE state_id="' . $city_info['state_id'] . '"')->row;

            if ($state_info) {
                $shipping_state = $state_info['name'];
            }
        }

        return $shipping_state;
    }

    public function getOrderProductByProductId($order_id, $product_id) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "' AND product_id = '" . (int) $product_id . "'");

        return $query->row;
    }

    public function getOrderProductByOrderProductId($order_id, $product_id, $order_product_id) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "' AND product_id = '" . (int) $product_id . "' AND order_product_id = '" . (int) $order_product_id . "'");

        return $query->row;
    }

    public function getOrderProducts($order_id) {
        /* $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'"); */
        $query = $this->db->query('SELECT a.*,b.image as image,b.weight as weight
        FROM `' . DB_PREFIX . 'order_product` a,`' . DB_PREFIX . 'product` b,`' . DB_PREFIX . "product_to_store` c
        WHERE b.product_id=c.product_id
        AND a.product_id=c.product_store_id
        AND a.order_id='" . $order_id . "'");

        return $query->rows;
    }

    public function getOrderProductsByProductId($order_id, $product_store_id) {
        /* $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'"); */
        $query = $this->db->query('SELECT a.*,b.image as image,b.weight as weight
        FROM `' . DB_PREFIX . 'order_product` a,`' . DB_PREFIX . 'product` b,`' . DB_PREFIX . "product_to_store` c
        WHERE b.product_id=c.product_id
        AND a.product_id=c.product_store_id
        AND a.order_id='" . $order_id . "' AND a.product_id='" . $product_store_id . "'");

        return $query->row;
    }

    public function getRealOrderProducts($order_id) {
        /* $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'"); */
        $query = $this->db->query('SELECT a.*,b.image as image,b.weight as weight
        FROM `' . DB_PREFIX . 'real_order_product` a,`' . DB_PREFIX . 'product` b,`' . DB_PREFIX . "product_to_store` c
        WHERE b.product_id=c.product_id
        AND a.product_id=c.product_store_id
        AND a.order_id='" . $order_id . "'");

        $query1 = $this->db->query('SELECT a.*
        FROM `' . DB_PREFIX . "real_order_product` a
        WHERE a.order_id='" . $order_id . "' and a.product_id REGEXP '^-?[^0-9]+$'");

        $p = $query->rows;
        $q = $query1->rows;

        foreach ($q as $key => $value) {
            array_push($p, $value);
        }
        //echo "<pre>";print_r($p);die;

        return $p;
    }

    public function getOrderOptions($order_id, $order_product_id) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "order_option WHERE order_id = '" . (int) $order_id . "' AND order_product_id = '" . (int) $order_product_id . "'");

        return $query->rows;
    }

    public function getOrderVouchers($order_id) {
        $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . "order_voucher` WHERE order_id = '" . (int) $order_id . "'");

        return $query->rows;
    }

    public function getOrderTotals($order_id) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "order_total WHERE order_id = '" . (int) $order_id . "' ORDER BY sort_order");

        return $query->rows;
    }

    public function getOrderHistories($order_id) {
        $query = $this->db->query('SELECT date_added, os.name AS status, oh.comment, oh.notify FROM ' . DB_PREFIX . 'order_history oh LEFT JOIN ' . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int) $order_id . "' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY oh.date_added");

        return $query->rows;
    }

    public function getLatestOrderHistories($order_id) {
        $query = $this->db->query('SELECT date_added, os.name AS status, oh.comment, oh.notify FROM ' . DB_PREFIX . 'order_history oh LEFT JOIN ' . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int) $order_id . "' AND oh.date_added >= DATE('" . $this->db->escape(date('Y-m-d H:i:s', strtotime('-2 minute'))) . "') AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY oh.date_added desc");

        return $query->row;
    }

    public function getTotalOrders() {
        $log = new Log('error.log');
        $s_users = [];
        $parent_user_id = NULL;
        $order_approval_access = $this->db->query('SELECT c.customer_id, c.parent FROM ' . DB_PREFIX . "customer c WHERE c.customer_id = '" . (int) $this->customer->getId() . "' AND c.order_approval_access = 1 AND (c.order_approval_access_role = 'head_chef' OR c.order_approval_access_role = 'procurement_person')");
        $order_approval_access_user = $order_approval_access->row;

        if (is_array($order_approval_access_user) && count($order_approval_access_user) > 0) {
            $log->write('order_approval_access_user');
            $log->write($order_approval_access_user);
            $log->write('order_approval_access_user');
            $parent_user_id = $order_approval_access_user['parent'];
        }

        if ($parent_user_id != NULL) {
            $sub_users_query = $this->db->query('SELECT c.customer_id FROM ' . DB_PREFIX . "customer c WHERE parent = '" . (int) $parent_user_id . "' AND parent > 0");
            $sub_users = $sub_users_query->rows;
            $log->write('SUB USERS ORDERS_1');
            $log->write($sub_users);
            $log->write('SUB USERS ORDERS_1');
            $s_users = array_column($sub_users, 'customer_id');

            array_push($s_users, $order_approval_access_user['parent']);
            $sub_users_od = implode(',', $s_users);
            $log->write($sub_users_od);
        } else {
            $sub_users_query = $this->db->query('SELECT c.customer_id FROM ' . DB_PREFIX . "customer c WHERE parent = '" . (int) $this->customer->getId() . "' AND parent > 0");
            $sub_users = $sub_users_query->rows;
            $log->write('SUB USERS ORDERS_2');
            $log->write($this->customer->getId());
            $log->write($sub_users);
            $log->write('SUB USERS ORDERS_2');
            $s_users = array_column($sub_users, 'customer_id');

            array_push($s_users, $this->customer->getId());
            $sub_users_od = implode(',', $s_users);
            $log->write($sub_users_od);
        }



        $query = $this->db->query('SELECT COUNT(*) AS total FROM `' . DB_PREFIX . "order` o WHERE customer_id IN (" . $sub_users_od . ") AND o.order_status_id > '0' ");

        $log->write($query->row['total']);
        //return $query;
        return $query->row['total'];
    }

    public function getTotalOrderProductsByOrderId($order_id) {
        /* $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");

          return $query->row['total']; */
        $query = $this->db->query('SELECT SUM(quantity) AS total FROM ' . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");

        return $query->row['total'];
    }

    public function getTotalOrderedProductsByOrderId($order_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");

        return $query->row['total'];
    }

    public function getTotalRealOrderProductsByOrderId($order_id) {
        /* $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "real_order_product WHERE order_id = '" . (int) $order_id . "'");

          return $query->row['total']; */

        $query = $this->db->query('SELECT SUM(quantity) AS total FROM ' . DB_PREFIX . "real_order_product WHERE order_id = '" . (int) $order_id . "'");

        return $query->row['total'];
    }

    public function getTotalRealOrderedProductsByOrderId($order_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "real_order_product WHERE order_id = '" . (int) $order_id . "'");

        return $query->row['total'];
    }

    public function getTotalOrderVouchersByOrderId($order_id) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `' . DB_PREFIX . "order_voucher` WHERE order_id = '" . (int) $order_id . "'");

        return $query->row['total'];
    }

    public function getStoreById($store_id) {
        $sql = 'select * from `' . DB_PREFIX . 'store` where store_id ="' . $store_id . '"';

        return $this->db->query($sql)->row;
    }

    public function updateOrderDeliveryId($delivery_id, $order_id) {
        $this->db->query('UPDATE `' . DB_PREFIX . "order` SET delivery_id = '" . $delivery_id . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");
    }

    public function saveRatingOrder($rating, $order_id) {
        $this->db->query('UPDATE `' . DB_PREFIX . "order` SET rating = '" . $rating . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");
    }

    public function getFormatedOrder($order_id) {
        return $this->db->query('SELECT *, (SELECT os.name FROM `' . DB_PREFIX . 'order_status` os WHERE os.order_status_id = o.order_status_id AND os.language_id = o.language_id) AS order_status FROM `' . DB_PREFIX . "order` o WHERE o.order_id = '" . (int) $order_id . "'");
    }

    public function getAppOrderStatuses() {
        $p = [];

        $sql = 'SELECT * FROM ' . DB_PREFIX . "app_order_status WHERE language_id = '" . (int) $this->config->get('config_language_id') . "'";

        $sql .= ' ORDER BY name';

        $query = $this->db->query($sql);

        foreach ($query->rows as $row) {
            if ($row && isset($row['app_order_status_id'])) {
                $sql1 = 'SELECT * FROM ' . DB_PREFIX . 'app_order_status_mapping where app_order_status_id=' . $row['app_order_status_id'];

                $query1 = $this->db->query($sql1);

                if ($query1->row && isset($query1->row['app_order_status_id'])) {
                    $tmp['name'] = $row['name'];
                    $tmp['code'] = $query1->row['code'];

                    $p[] = $tmp;
                }
            }
        }

        return $p;
    }

    public function getAppOrderStatusMapping($order_status_id) {
        $resp['status'] = false;
        $resp['data'] = [];

        $sql = 'SELECT * FROM ' . DB_PREFIX . 'app_order_status_mapping where order_status_id=' . $order_status_id;

        $query = $this->db->query($sql);

        //echo "<pre>";print_r($query->row);die;
        if ($query->row && isset($query->row['app_order_status_id'])) {
            $p['code'] = $query->row['code'];
            $sql1 = 'SELECT * FROM ' . DB_PREFIX . 'app_order_status where app_order_status_id=' . $query->row['app_order_status_id'] . ' AND language_id="' . $this->config->get('config_language_id') . '"';

            $query1 = $this->db->query($sql1);

            //echo "<pre>";print_r($query1->row);die;

            if ($query1->row && isset($query1->row['name'])) {
                $p['name'] = $query1->row['name'];
                $resp['data'] = $p;
                $resp['status'] = true;
            }
        }

        return $resp;
    }

    public function getSubUserOrderDetails($order_id, $customer_id) {
        $sub_users_order = $this->db->query('SELECT * FROM ' . DB_PREFIX . "order WHERE order_id = '" . (int) $order_id . "' AND customer_id  ='" . (int) $customer_id . "'");

        return $sub_users_order->row;
    }

    public function getSubUserOrderDetailsapi($order_id) {
        $sub_users_order = $this->db->query('SELECT * FROM ' . DB_PREFIX . "order WHERE order_id = '" . (int) $order_id . "'");

        return $sub_users_order->row;
    }

    public function ApproveOrRejectSubUserOrder($order_id, $customer_id, $order_status) {
        if ('Approved' == $order_status) {
            $this->db->query('UPDATE `' . DB_PREFIX . "order` SET parent_approval = '" . $order_status . "'  WHERE order_id = '" . (int) $order_id . "' AND customer_id = '" . (int) $customer_id . "'");
            //$this->db->query('INSERT INTO ' . DB_PREFIX . "order_history SET order_id = '" . (int) $order_id . "', order_status_id = 14, notify = 1, comment = 'Order Approved By Parent User', date_added = NOW()");
        }
        if ('Rejected' == $order_status) {
            $this->db->query('UPDATE `' . DB_PREFIX . "order` SET parent_approval = '" . $order_status . "' WHERE order_id = '" . (int) $order_id . "' AND customer_id = '" . (int) $customer_id . "'");
            //$this->db->query('INSERT INTO ' . DB_PREFIX . "order_history SET order_id = '" . (int) $order_id . "', order_status_id = 16, notify = 1, comment = 'Order Rejected By Parent User', date_added = NOW()");
        }
    }

    public function ApproveOrRejectSubUserOrderByChefProcurement($order_id, $customer_id, $order_status, $role) {
        if ('Approved' == $order_status && $role == 'head_chef') {
            $this->db->query('UPDATE `' . DB_PREFIX . "order` SET head_chef = '" . $order_status . "'  WHERE order_id = '" . (int) $order_id . "' AND customer_id = '" . (int) $customer_id . "'");
            //$this->db->query('INSERT INTO ' . DB_PREFIX . "order_history SET order_id = '" . (int) $order_id . "', order_status_id = 14, notify = 1, comment = 'Order Approved By Parent User', date_added = NOW()");
        }

        if ('Approved' == $order_status && $role == 'procurement') {
            $this->db->query('UPDATE `' . DB_PREFIX . "order` SET procurement = '" . $order_status . "'  WHERE order_id = '" . (int) $order_id . "' AND customer_id = '" . (int) $customer_id . "'");
            //$this->db->query('INSERT INTO ' . DB_PREFIX . "order_history SET order_id = '" . (int) $order_id . "', order_status_id = 14, notify = 1, comment = 'Order Approved By Parent User', date_added = NOW()");
        }

        if ('Rejected' == $order_status && $role == 'head_chef') {
            $this->db->query('UPDATE `' . DB_PREFIX . "order` SET head_chef = '" . $order_status . "' WHERE order_id = '" . (int) $order_id . "' AND customer_id = '" . (int) $customer_id . "'");
            //$this->db->query('INSERT INTO ' . DB_PREFIX . "order_history SET order_id = '" . (int) $order_id . "', order_status_id = 16, notify = 1, comment = 'Order Rejected By Parent User', date_added = NOW()");
        }

        if ('Rejected' == $order_status && $role == 'procurement') {
            $this->db->query('UPDATE `' . DB_PREFIX . "order` SET procurement = '" . $order_status . "' WHERE order_id = '" . (int) $order_id . "' AND customer_id = '" . (int) $customer_id . "'");
            //$this->db->query('INSERT INTO ' . DB_PREFIX . "order_history SET order_id = '" . (int) $order_id . "', order_status_id = 16, notify = 1, comment = 'Order Rejected By Parent User', date_added = NOW()");
        }
    }

    public function UpdateOrderStatus($order_id, $order_status_id, $comment, $added_by = '', $added_by_role = '') {
        $this->db->query('UPDATE `' . DB_PREFIX . "order` SET order_status_id = '" . (int) $order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");
        $this->db->query('INSERT INTO ' . DB_PREFIX . "order_history SET order_id = '" . (int) $order_id . "', added_by = '" . (int) $added_by . "', role = '" . $added_by_role . "', order_status_id = '" . (int) $order_status_id . "', notify = 1, comment = '" . $comment . "', date_added = NOW()");
    }

    public function ApproveOrRejectSubUserOrderApi($order_id, $order_status) {
        if ('Approved' == $order_status) {
            $this->db->query('UPDATE `' . DB_PREFIX . "order` SET parent_approval = '" . $order_status . "'   WHERE order_id = '" . (int) $order_id . "'");
            //$this->db->query('INSERT INTO ' . DB_PREFIX . "order_history SET order_id = '" . (int) $order_id . "', order_status_id = 14, notify = 1, comment = 'Order Approved By Parent User', date_added = NOW()");
        }
        if ('Rejected' == $order_status) {
            $this->db->query('UPDATE `' . DB_PREFIX . "order` SET parent_approval = '" . $order_status . "'  WHERE order_id = '" . (int) $order_id . "' ");
            // $this->db->query('INSERT INTO ' . DB_PREFIX . "order_history SET order_id = '" . (int) $order_id . "', order_status_id = 16, notify = 1, comment = 'Order Rejected By Parent User', date_added = NOW()");
        }
    }

    public function getOnlyOrderProducts($order_id, $store_id = 0) {
        $sql = "SELECT * ,'0' as quantity_updated,'0' as unit_updated FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'";

        if ($store_id) {
            $sql .= " AND store_id='" . $store_id . "'";
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getOnlyRealOrderProducts($order_id, $store_id = 0) {
        $sql = 'SELECT * FROM ' . DB_PREFIX . "real_order_product WHERE order_id = '" . (int) $order_id . "'";

        if ($store_id) {
            $sql .= " AND store_id='" . $store_id . "'";
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function hasRealOrderProduct($order_id) {
        $sql = 'SELECT * FROM ' . DB_PREFIX . "real_order_product WHERE order_id = '" . (int) $order_id . "'";

        $query = $this->db->query($sql);

        if ($query->num_rows) {
            return true;
        }

        return false;
    }

    public function download_products_excel($data) {
        $this->load->library('excel');
        $this->load->library('iofactory');

        // echo "<pre>";print_r($rows);die;

        try {
            set_time_limit(2500);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()
                    ->setTitle('Order Details')
                    ->setDescription('none');

            // Consolidated Customer Orders
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setTitle('Products');

            $title = [
                'font' => [
                    'bold' => true,
                    'color' => [
                        'rgb' => 'FFFFFF',
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => [
                        'rgb' => '51AB66',
                    ],
                ],
            ];

            $sheet_title = 'Order Details';
            $sheet_title0 = ' ' . $data['consolidation'][0]['company'];
            $sheet_subtitle = '' . $data['consolidation'][0]['customer'] . ' #  ' . 'Order Id :' . $data['consolidation'][0]['orderid'];
            $sheet_subtitle1 = '' . $data['consolidation'][0]['date'];
            $sheet_subtitle2 = '' . $data['consolidation'][0]['deliverydate'];
            $sheet_subtitle3 = '' . $data['consolidation'][0]['paymentmethod'];
            $sheet_subtitle4 = '' . $data['consolidation'][0]['shippingaddress'];

            $objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
            $objPHPExcel->getActiveSheet()->mergeCells('B2:D2');
            $objPHPExcel->getActiveSheet()->mergeCells('B3:D3');
            $objPHPExcel->getActiveSheet()->mergeCells('B4:D4');
            $objPHPExcel->getActiveSheet()->mergeCells('B5:D5');
            $objPHPExcel->getActiveSheet()->mergeCells('B6:D6');
            $objPHPExcel->getActiveSheet()->mergeCells('B7:D7');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', $sheet_title);

            $objPHPExcel->getActiveSheet()->setCellValue('A2', 'Company Name:');
            $objPHPExcel->getActiveSheet()->setCellValue('A3', 'Customer Name & Order ID:');
            $objPHPExcel->getActiveSheet()->setCellValue('A4', 'Order Date: ');
            $objPHPExcel->getActiveSheet()->setCellValue('A5', 'Delivery Date: ');
            $objPHPExcel->getActiveSheet()->setCellValue('A6', 'Payment Method: ');
            $objPHPExcel->getActiveSheet()->setCellValue('A7', 'Shipping Address: ');

            $objPHPExcel->getActiveSheet()->setCellValue('B2', $sheet_title0);
            $objPHPExcel->getActiveSheet()->setCellValue('B3', $sheet_subtitle);
            $objPHPExcel->getActiveSheet()->setCellValue('B4', $sheet_subtitle1);
            $objPHPExcel->getActiveSheet()->setCellValue('B5', $sheet_subtitle2);
            $objPHPExcel->getActiveSheet()->setCellValue('B6', $sheet_subtitle3);
            $objPHPExcel->getActiveSheet()->setCellValue('B7', $sheet_subtitle4);

            $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '51AB66',
            ]]);
            $objPHPExcel->getActiveSheet()->getStyle('A2:D2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '51AB66',
            ]]);
            $objPHPExcel->getActiveSheet()->getStyle('A3:D3')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '51AB66',
            ]]);
            $objPHPExcel->getActiveSheet()->getStyle('A4:D4')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '51AB66',
            ]]);

            $objPHPExcel->getActiveSheet()->getStyle('A5:D5')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '51AB66',
            ]]);

            $objPHPExcel->getActiveSheet()->getStyle('A6:D6')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '51AB66',
            ]]);

            $objPHPExcel->getActiveSheet()->getStyle('A7:D7')->applyFromArray(['font' => ['bold' => true], 'color' => [
                    'rgb' => '51AB66',
            ]]);

            $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A2:B7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getStyle('A8:C8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            //    foreach(range('A','L') as $columnID) {
            // 	   $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
            // 		   ->setAutoSize(true);
            //    }
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 8, 'Product');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 8, 'Unit');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 8, 'Quantity');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 8, 'Total');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 8)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 8)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 8)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, 8)->applyFromArray($title);

            $row = 9;
            $Amount = 0;
            foreach ($data['products'] as $order) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $order['name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $order['unit_updated']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $order['quantity_updated']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, str_replace('KES', ' ', $order['total_updated']));
                $Amount = $Amount + $order['total_updatedvalue'];
                ++$row;
            }
            $Amount = str_replace('KES', ' ', $this->currency->format($Amount));
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, $row)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3, $row)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Amount');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $Amount);

            $objPHPExcel->setActiveSheetIndex(0);

            $filename = 'Products_' . $data['consolidation'][0]['customer'] . '_' . $data['consolidation'][0]['orderid'] . '.xlsx';

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $objWriter->save('php://output');
            exit;
        } catch (Exception $e) {

            $errstr = $e->getMessage();
            $errline = $e->getLine();
            $errfile = $e->getFile();
            $errno = $e->getCode();
            $this->session->data['export_import_error'] = ['errstr' => $errstr, 'errno' => $errno, 'errfile' => $errfile, 'errline' => $errline];
            if ($this->config->get('config_error_log')) {
                $this->log->write('PHP ' . get_class($e) . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
            }

            return;
        }
    }

    public function SubUserOrderReject($order_id, $order_status_id) {

        $this->load->model('checkout/order');
        $this->load->model('account/customer');
        $order_info = $this->model_checkout_order->getOrder($order_id);

        //this is solely used to send mails

        $order_status = $this->db->query("SELECT name FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int) $order_status_id . "' AND language_id = '" . (int) $order_info['language_id'] . "'");
        $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);

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
        $log->write('MAIL SENDING');

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

        $log->write('in if');

        $log->write("cust orderData");

        /* customer mail/sms/notificaiton start */
        $subject = $this->emailtemplate->getSubject('OrderAll', 'order_' . (int) $order_status_id, $data);
        $message = $this->emailtemplate->getMessage('OrderAll', 'order_' . (int) $order_status_id, $data);
        $sms_message = $this->emailtemplate->getSmsMessage('OrderAll', 'order_' . (int) $order_status_id, $data);

        //$log->write($message);
        //echo "<pre>";print_r($message);die;
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

        if ($customer_info['sms_notification'] == 1 && $this->emailtemplate->getSmsEnabled('OrderAll', 'order_' . (int) $order_status_id)) {

            $ret = $this->emailtemplate->sendmessage($order_info['telephone'], $sms_message);
        }

        $log->write('outside mobi noti');
        if ($customer_info['mobile_notification'] == 1 && $this->emailtemplate->getNotificationEnabled('OrderAll', 'order_' . (int) $order_status_id)) {

            $log->write('status enabled of mobi noti');
            $mobile_notification_template = $this->emailtemplate->getNotificationMessage('OrderAll', 'order_' . (int) $order_status_id, $data);

            //$log->write($mobile_notification_template);

            $mobile_notification_title = $this->emailtemplate->getNotificationTitle('OrderAll', 'order_' . (int) $order_status_id, $data);

            //$log->write($mobile_notification_title);
            // customer push notitification start

            if (isset($customer_info) && isset($customer_info['device_id']) && strlen($customer_info['device_id']) > 0) {

                $log->write('customer device id set FRONT.MODEL.ACCOUNT.ORDER');
                $ret = $this->emailtemplate->sendPushNotification($order_info['customer_id'], $customer_info['device_id'], $order_id, $order_info['store_id'], $mobile_notification_title, $mobile_notification_template, 'com.instagolocal.showorder');
            } else {
                $log->write('customer device id not set FRONT.MODEL.ACCOUNT.ORDER');
            }

            // customer push notitification end
        }

        /* customer mail/sms/notificaiton end */
    }

    public function SubUserOrderApproved($order_id, $order_status_id) {
        $log = new Log('error.log');
        $log->write('SEND MAIL');
        $log->write($order_id);
        $this->load->model('account/customer');
        $this->load->model('checkout/order');

        $order_info = $this->model_checkout_order->getOrder($order_id);
        $is_he_parents = $this->model_account_customer->CheckHeIsParent();
        $customer_info = $this->model_account_customer->getCustomer($is_he_parents);

        if ($order_info) {
            $store_name = $order_info['firstname'] . ' ' . $order_info['lastname'];
            $store_url = $this->url->link('account/login/customer');
        }
        $sub_customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);

        $order_id = $order_info['order_id'];
        $customer_id = $order_info['customer_id'];

        $customer_info['firstname'] = $sub_customer_info['firstname'];
        $customer_info['lastname'] = $sub_customer_info['lastname'];
        $customer_info['email'] = $sub_customer_info['email'];
        $customer_info['subuserfirstname'] = $sub_customer_info['firstname'];
        $customer_info['subuserlastname'] = $sub_customer_info['lastname'];
        $customer_info['subuserorderid'] = $order_info['order_id'];
        $customer_info['order_link'] = $this->url->link('account/order', '', 'SSL');
        $customer_info['device_id'] = $sub_customer_info['device_id'];
        $customer_info['telephone'] = $sub_customer_info['telephone'];

        $log->write('EMAIL SENDING');
        $log->write($customer_info);
        $log->write('EMAIL SENDING');

        if ($sub_customer_info['email_notification'] == 1) {
            $subject = $this->emailtemplate->getSubject('Customer', 'customer_14', $customer_info);
            $message = $this->emailtemplate->getMessage('Customer', 'customer_14', $customer_info);

            try {
                $mail = new Mail($this->config->get('config_mail'));
                $mail->setTo($sub_customer_info['email']);
                $mail->setFrom($this->config->get('config_from_email'));
                $mail->setSender($this->config->get('config_name'));
                $mail->setSubject($subject);
                $mail->setHTML($message);
                $mail->send();
            } catch (Exception $e) {
                
            }
        }

        $log->write('SMS SENDING');
        $sms_message = $this->emailtemplate->getSmsMessage('Customer', 'customer_14', $customer_info);
        // send message here
        if ($sub_customer_info['sms_notification'] == 1 && $this->emailtemplate->getSmsEnabled('Customer', 'customer_14')) {
            $ret = $this->emailtemplate->sendmessage($customer_info['telephone'], $sms_message);
        }

        $log->write('outside mobi noti');
        if ($sub_customer_info['mobile_notification'] == 1 && $this->emailtemplate->getNotificationEnabled('Customer', 'customer_14')) {

            $log->write('status enabled of mobi noti');
            $mobile_notification_template = $this->emailtemplate->getNotificationMessage('Customer', 'customer_14', $customer_info);

            //$log->write($mobile_notification_template);

            $mobile_notification_title = $this->emailtemplate->getNotificationTitle('Customer', 'customer_14', $customer_info);

            //$log->write($mobile_notification_title);
            // customer push notitification start

            if (isset($customer_info) && isset($customer_info['device_id']) && strlen($customer_info['device_id']) > 0) {

                $log->write('customer device id set FRONT.MODEL.CHECKOUT.ORDER');
                $ret = $this->emailtemplate->sendPushNotification($order_info['customer_id'], $customer_info['device_id'], $order_id, $order_info['store_id'], $mobile_notification_title, $mobile_notification_template, 'com.instagolocal.showorder');
            } else {
                $log->write('customer device id not set FRONT.MODEL.CHECKOUT.ORDER');
            }

            // customer push notitification end
        }
    }

    public function getCustomerParentByOrderId($OrderId) {
        $row = $this->db->query('select parent as customer_id from ' . DB_PREFIX . 'order o  join ' . DB_PREFIX . 'customer c  on o.customer_id =c.customer_id  where order_id="' . $OrderId . '"')->row;

        // echo "<pre>";print_r('select parent as customer_id from ' . DB_PREFIX . 'order o  join ' . DB_PREFIX . 'customer c  on o.customer_id =c.customer_id  where order_id="' . $OrderId . '"');die; 

        if ($row) {
            return $row['customer_id'];
        }
    }

    public function getDriver($driver_id) {
        $query = $this->db->query('SELECT DISTINCT * FROM ' . DB_PREFIX . "drivers WHERE driver_id = '" . (int) $driver_id . "'");
        return $query->row;
    }

    public function getDriverName($driver_id) {
        $query = $this->db->query('SELECT   CONCAT(firstname," ",lastname) as name , telephone FROM ' . DB_PREFIX . "drivers WHERE driver_id = '" . (int) $driver_id . "'");
        return $query->row;
    }

    public function getExecutiveName($delivery_executive_id) {
        $query = $this->db->query('SELECT   CONCAT(firstname," ",lastname) as name , telephone FROM ' . DB_PREFIX . "delivery_executives WHERE delivery_executive_id = '" . (int) $delivery_executive_id . "'");
        return $query->row;
    }

    public function getIncompleteOrders($start = 0, $limit = 20, $noLimit = false) {
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 1;
        }

        $log = new Log('error.log');
        // $s_users = [];
        // $parent_user_id = NULL;
        // $order_approval_access = $this->db->query('SELECT c.customer_id, c.parent FROM ' . DB_PREFIX . "customer c WHERE c.customer_id = '" . (int) $this->customer->getId() . "' AND c.order_approval_access = 1 AND (c.order_approval_access_role = 'head_chef' OR c.order_approval_access_role = 'procurement_person')");
        // $order_approval_access_user = $order_approval_access->row;
        // if (is_array($order_approval_access_user) && count($order_approval_access_user) > 0) {
        //     //$log->write('order_approval_access_user');
        //     //$log->write($order_approval_access_user);
        //     //$log->write('order_approval_access_user');
        //     $parent_user_id = $order_approval_access_user['parent'];
        // }
        // if ($parent_user_id != NULL) {
        //     $sub_users_query = $this->db->query('SELECT c.customer_id FROM ' . DB_PREFIX . "customer c WHERE parent = '" . (int) $parent_user_id . "'");
        //     $sub_users = $sub_users_query->rows;
        //     //$log->write('SUB USERS ORDERS');
        //     //$log->write($sub_users);
        //     //$log->write('SUB USERS ORDERS');
        //     $s_users = array_column($sub_users, 'customer_id');
        //     array_push($s_users, $order_approval_access_user['parent']);
        //     $sub_users_od = implode(',', $s_users);
        // } else {
        //     $sub_users_query = $this->db->query('SELECT c.customer_id FROM ' . DB_PREFIX . "customer c WHERE parent = '" . (int) $this->customer->getId() . "'");
        //     $sub_users = $sub_users_query->rows;
        //     //$log->write('SUB USERS ORDERS');
        //     //$log->write($sub_users);
        //     //$log->write('SUB USERS ORDERS');
        //     $s_users = array_column($sub_users, 'customer_id');
        //     array_push($s_users, $this->customer->getId());
        //     $sub_users_od = implode(',', $s_users);
        // }

        if (false == $noLimit) {
            //$sub_users_orders = $this->db->query("SELECT o.order_id FROM " . DB_PREFIX . "order o WHERE customer_id IN (".$sub_users_od.")");
            //$ord = $sub_users_orders->rows;
            //echo "<pre>";print_r($ord);die;
            // $query = $this->db->query('SELECT o.customer_id, o.parent_approval, o.head_chef, o.procurement, o.delivery_date,o.delivery_timeslot,o.shipping_zipcode,o.shipping_city_id,o.payment_method,o.payment_code,o.shipping_address,o.shipping_flat_number,o.shipping_method,o.shipping_building_name,o.store_name,o.shipping_name, o.order_id, o.firstname, o.lastname, os.name as status , os.color as order_status_color ,o.order_status_id, o.date_modified , o.date_added, o.total, o.currency_code, o.currency_value, ot.value FROM `' . DB_PREFIX . 'order` o LEFT JOIN ' . DB_PREFIX . 'order_status os ON (o.order_status_id = os.order_status_id) LEFT JOIN ' . DB_PREFIX . 'order_total ot ON (o.order_id = ot.order_id) WHERE o.customer_id IN (' . $sub_users_od . ") AND o.order_status_id > '0' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' AND ot.code = 'total' AND ot.title = 'Total' ORDER BY o.order_id DESC LIMIT " . (int) $start . ',' . (int) $limit);
            $query = $this->db->query("SELECT  o.customer_id, o.parent_approval, o.head_chef, o.procurement,o.delivery_date,o.delivery_timeslot,o.shipping_zipcode,o.shipping_city_id,o.payment_method,o.payment_code,o.shipping_address,o.shipping_flat_number,o.shipping_method,o.shipping_building_name,o.store_name,o.shipping_name, o.order_id, o.firstname, o.lastname, os.name as status , os.color as order_status_color ,o.order_status_id, o.date_modified , o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '" . (int) $this->customer->getId() . "' AND o.order_status_id = '0'   ORDER BY o.order_id DESC LIMIT " . (int) $start . "," . (int) $limit);
        } else {
            // $query = $this->db->query('SELECT o.customer_id, o.parent_approval, o.head_chef, o.procurement, o.delivery_date,o.delivery_timeslot,o.shipping_zipcode,o.shipping_city_id,o.payment_method,o.payment_code,o.shipping_address,o.shipping_flat_number,o.shipping_method,o.shipping_building_name,o.store_name,o.shipping_name, o.order_id, o.firstname, o.lastname, os.name as status , os.color as order_status_color ,o.order_status_id, o.date_modified , o.date_added, o.total, o.currency_code, o.currency_value, ot.value FROM `' . DB_PREFIX . 'order` o LEFT JOIN ' . DB_PREFIX . 'order_status os ON (o.order_status_id = os.order_status_id) LEFT JOIN ' . DB_PREFIX . 'order_total ot ON (o.order_id = ot.order_id) WHERE o.customer_id IN (' . $sub_users_od . ") AND o.order_status_id > '0' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' AND ot.code = 'total' AND ot.title = 'Total' ORDER BY o.order_id DESC");
            $query = $this->db->query("SELECT  o.customer_id, o.parent_approval, o.head_chef, o.procurement,o.delivery_date,o.delivery_timeslot,o.shipping_zipcode,o.shipping_city_id,o.payment_method,o.payment_code,o.shipping_address,o.shipping_flat_number,o.shipping_method,o.shipping_building_name,o.store_name,o.shipping_name, o.order_id, o.firstname, o.lastname, os.name as status , os.color as order_status_color ,o.order_status_id, o.date_modified , o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '" . (int) $this->customer->getId() . "' AND o.order_status_id = '0' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY o.order_id DESC");
        }
        // echo "<pre>";print_r("SELECT o.delivery_date,o.delivery_timeslot,o.shipping_zipcode,o.shipping_city_id,o.payment_method,o.shipping_address,o.shipping_flat_number,o.shipping_method,o.shipping_building_name,o.store_name,o.shipping_name, o.order_id, o.firstname, o.lastname, os.name as status , os.color as order_status_color ,o.order_status_id, o.date_modified , o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '" . (int) $this->customer->getId() . "' AND o.order_status_id = '0'  ORDER BY o.order_id DESC");die;
        /* if($statuses == null && $payment_methods == null){
          $query = $this->db->query("SELECT o.delivery_date,o.delivery_timeslot,o.shipping_zipcode,o.shipping_city_id,o.payment_method,o.shipping_address,o.shipping_flat_number,o.shipping_method,o.shipping_building_name,o.store_name,o.shipping_name, o.order_id, o.firstname, o.lastname, os.name as status , os.color as order_status_color ,o.order_status_id, o.date_modified , o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '" . (int) $this->customer->getId() . "' AND o.order_status_id == '0' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY o.order_id DESC LIMIT " . (int) $start . "," . (int) $limit);
          }else{
          if($In == true){
          $sql = "SELECT o.delivery_date,o.delivery_timeslot,o.shipping_zipcode,o.shipping_city_id,o.payment_method,o.shipping_address,o.shipping_flat_number,o.shipping_method,o.shipping_building_name,o.store_name,o.shipping_name, o.order_id, o.firstname, o.lastname, os.name as status , os.color as order_status_color ,o.order_status_id, o.date_modified , o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '" . (int) $this->customer->getId() . "' AND o.order_status_id > '0' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "'  AND o.payment_method IN ($payment_methods) AND os.name IN ($statuses) ORDER BY o.order_id DESC";
          }else{
          $sql = "SELECT o.delivery_date,o.delivery_timeslot,o.shipping_zipcode,o.shipping_city_id,o.payment_method,o.shipping_address,o.shipping_flat_number,o.shipping_method,o.shipping_building_name,o.store_name,o.shipping_name, o.order_id, o.firstname, o.lastname, os.name as status , os.color as order_status_color ,o.order_status_id, o.date_modified , o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '" . (int) $this->customer->getId() . "' AND o.order_status_id > '0' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "'  AND o.payment_method IN ($payment_methods) AND os.name NOT IN ($statuses) ORDER BY o.order_id DESC";
          }
          $query = $this->db->query($sql);
          } */
        //$log->write('ORDERS COUNT');
        //$log->write(count($query->rows));
        //$log->write('ORDERS COUNT');

        return $query->rows;
    }

    public function getTotalIncompleteOrders() {
        $log = new Log('error.log');
        // $s_users = [];
        // $parent_user_id = NULL;
        // $order_approval_access = $this->db->query('SELECT c.customer_id, c.parent FROM ' . DB_PREFIX . "customer c WHERE c.customer_id = '" . (int) $this->customer->getId() . "' AND c.order_approval_access = 1 AND (c.order_approval_access_role = 'head_chef' OR c.order_approval_access_role = 'procurement_person')");
        // $order_approval_access_user = $order_approval_access->row;
        // if (is_array($order_approval_access_user) && count($order_approval_access_user) > 0) {
        //     $log->write('order_approval_access_user');
        //     $log->write($order_approval_access_user);
        //     $log->write('order_approval_access_user');
        //     $parent_user_id = $order_approval_access_user['parent'];
        // }
        // if ($parent_user_id != NULL) {
        //     $sub_users_query = $this->db->query('SELECT c.customer_id FROM ' . DB_PREFIX . "customer c WHERE parent = '" . (int) $parent_user_id . "'");
        //     $sub_users = $sub_users_query->rows;
        //     $log->write('SUB USERS ORDERS');
        //     $log->write($sub_users);
        //     $log->write('SUB USERS ORDERS');
        //     $s_users = array_column($sub_users, 'customer_id');
        //     array_push($s_users, $order_approval_access_user['parent']);
        //     $sub_users_od = implode(',', $s_users);
        //     $log->write($sub_users_od);
        // } else {
        //     $sub_users_query = $this->db->query('SELECT c.customer_id FROM ' . DB_PREFIX . "customer c WHERE parent = '" . (int) $this->customer->getId() . "'");
        //     $sub_users = $sub_users_query->rows;
        //     $log->write('SUB USERS ORDERS');
        //     $log->write($sub_users);
        //     $log->write('SUB USERS ORDERS');
        //     $s_users = array_column($sub_users, 'customer_id');
        //     array_push($s_users, $this->customer->getId());
        //     $sub_users_od = implode(',', $s_users);
        //     $log->write($sub_users_od);
        // }
        // $query = $this->db->query('SELECT COUNT(*) AS total FROM `' . DB_PREFIX . "order` o WHERE customer_id IN (" . $sub_users_od . ") AND o.order_status_id > '0' ");
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `' . DB_PREFIX . "order` o WHERE customer_id  = '" . (int) $this->customer->getId() . "' AND o.order_status_id = '0' ");
        // echo "<pre>";print_r($query);die;

        $log->write($query->row['total']);
        //return $query;
        return $query->row['total'];
    }

    public function getIncompleteOrder($order_id, $notLogin = false) {
        $s_users = [];
        $sub_users_od = [];
        $parent_user_id = NULL;
        $order_approval_access = $this->db->query('SELECT c.customer_id, c.parent FROM ' . DB_PREFIX . "customer c WHERE c.customer_id = '" . (int) $this->customer->getId() . "' AND c.order_approval_access = 1 AND (c.order_approval_access_role = 'head_chef' OR c.order_approval_access_role = 'procurement_person')");
        $order_approval_access_user = $order_approval_access->row;

        if (is_array($order_approval_access_user) && count($order_approval_access_user) > 0) {
            //$log->write('order_approval_access_user');
            //$log->write($order_approval_access_user);
            //$log->write('order_approval_access_user');
            $parent_user_id = $order_approval_access_user['parent'];
        }

        if ($parent_user_id != NULL) {
            $sub_users_query = $this->db->query('SELECT c.customer_id FROM ' . DB_PREFIX . "customer c WHERE parent = '" . (int) $parent_user_id . "'");
            $sub_users = $sub_users_query->rows;
            //$log->write('SUB USERS ORDERS');
            //$log->write($sub_users);
            //$log->write('SUB USERS ORDERS');
            $s_users = array_column($sub_users, 'customer_id');
            array_push($s_users, $order_approval_access_user['parent']);
            $sub_users_od = implode(',', $s_users);
        } else {
            $sub_users_query = $this->db->query('SELECT c.customer_id FROM ' . DB_PREFIX . "customer c WHERE parent = '" . (int) $this->customer->getId() . "'");
            $sub_users = $sub_users_query->rows;
            $s_users = array_column($sub_users, 'customer_id');

            array_push($s_users, $this->customer->getId());
            $sub_users_od = implode(',', $s_users);
        }

        if (false == $notLogin) {
            $order_query = $this->db->query('SELECT * ,' . DB_PREFIX . 'order.date_added as order_date ,' . DB_PREFIX . 'order.email as order_email ,' . DB_PREFIX . 'order.telephone as order_telephone FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'store ON ( ' . DB_PREFIX . 'store.store_id = ' . DB_PREFIX . 'order.store_id) LEFT JOIN ' . DB_PREFIX . 'order_status ON ( ' . DB_PREFIX . 'order_status.order_status_id = ' . DB_PREFIX . "order.order_status_id)  WHERE order_id = '" . (int) $order_id . "' AND customer_id IN (" . $sub_users_od . ') AND ' . DB_PREFIX . "order.order_status_id = '0' ");
            //$order_query = $this->db->query("SELECT * ," . DB_PREFIX . "order.date_added as order_date ," . DB_PREFIX . "order.email as order_email ," . DB_PREFIX . "order.telephone as order_telephone FROM `" . DB_PREFIX . "order` LEFT JOIN " . DB_PREFIX . "store ON ( " . DB_PREFIX . "store.store_id = " . DB_PREFIX . "order.store_id) LEFT JOIN " . DB_PREFIX . "order_status ON ( " . DB_PREFIX . "order_status.order_status_id = " . DB_PREFIX . "order.order_status_id)  WHERE order_id = '" . (int) $order_id . "' AND customer_id = '" . (int) $this->customer->getId() . "' AND " . DB_PREFIX . "order.order_status_id = '0' ");
        } else {
            $order_query = $this->db->query('SELECT * ,' . DB_PREFIX . 'order.date_added as order_date ,' . DB_PREFIX . 'order.email as order_email ,' . DB_PREFIX . 'order.telephone as order_telephone FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'store ON ( ' . DB_PREFIX . 'store.store_id = ' . DB_PREFIX . 'order.store_id) LEFT JOIN ' . DB_PREFIX . 'order_status ON ( ' . DB_PREFIX . 'order_status.order_status_id = ' . DB_PREFIX . "order.order_status_id)  WHERE order_id = '" . (int) $order_id . "' AND " . DB_PREFIX . "order.order_status_id = '0' ");
        }
        if ($order_query->num_rows) {
            $city_info = $this->db->query('select * from `' . DB_PREFIX . 'city` WHERE city_id="' . $order_query->row['shipping_city_id'] . '"')->row;

            if ($city_info) {
                $shipping_city = $city_info['name'];
            } else {
                $shipping_city = '';
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
                'telephone' => $order_query->row['telephone'],
                'order_telephone' => $order_query->row['order_telephone'],
                'store_name' => $order_query->row['store_name'],
                'fax' => $order_query->row['fax'],
                'email' => $order_query->row['email'],
                'order_email' => $order_query->row['order_email'],
                'payment_method' => $order_query->row['payment_method'],
                'payment_code' => $order_query->row['payment_code'],
                'shipping_name' => $order_query->row['shipping_name'],
                'shipping_city_id' => $order_query->row['shipping_city_id'],
                'shipping_city' => $shipping_city,
                'shipping_address' => $order_query->row['shipping_address'],
                'shipping_flat_number' => $order_query->row['shipping_flat_number'],
                'shipping_building_name' => $order_query->row['shipping_building_name'],
                'shipping_landmark' => $order_query->row['shipping_landmark'],
                'shipping_contact_no' => $order_query->row['shipping_contact_no'],
                'shipping_method' => $order_query->row['shipping_method'],
                'comment' => $order_query->row['comment'],
                'total' => $order_query->row['total'],
                'rating' => $order_query->row['rating'],
                'order_status_id' => $order_query->row['order_status_id'],
                'language_id' => $order_query->row['language_id'],
                'currency_id' => $order_query->row['currency_id'],
                'currency_code' => $order_query->row['currency_code'],
                'currency_value' => $order_query->row['currency_value'],
                'date_modified' => $order_query->row['date_modified'],
                'date_added' => $order_query->row['date_added'],
                'ip' => $order_query->row['ip'],
                'delivery_timeslot' => $order_query->row['delivery_timeslot'],
                'delivery_date' => $order_query->row['delivery_date'],
                'store_address' => $order_query->row['address'],
                'store_name' => $order_query->row['store_name'],
                'status' => $order_query->row['name'],
                'order_date' => $order_query->row['order_date'],
                'delivery_id' => $order_query->row['delivery_id'],
                'settlement_amount' => $order_query->row['settlement_amount'],
                'driver_id' => $order_query->row['driver_id'],
                'vehicle_number' => $order_query->row['vehicle_number'],
                'delivery_executive_id' => $order_query->row['delivery_executive_id'],
            ];
        } else {
            return false;
        }
    }

    public function getTotalPendingOrders() {
        $log = new Log('error.log');
        $s_users = [];
        $parent_user_id = NULL;
        $order_approval_access = $this->db->query('SELECT c.customer_id, c.parent FROM ' . DB_PREFIX . "customer c WHERE c.customer_id = '" . (int) $this->customer->getId() . "' AND c.order_approval_access = 1 AND (c.order_approval_access_role = 'head_chef' OR c.order_approval_access_role = 'procurement_person')");
        $order_approval_access_user = $order_approval_access->row;

        if (is_array($order_approval_access_user) && count($order_approval_access_user) > 0) {
            $log->write('order_approval_access_user');
            $log->write($order_approval_access_user);
            $log->write('order_approval_access_user');
            $parent_user_id = $order_approval_access_user['parent'];
        }

        if ($parent_user_id != NULL) {
            $sub_users_query = $this->db->query('SELECT c.customer_id FROM ' . DB_PREFIX . "customer c WHERE parent = '" . (int) $parent_user_id . "'");
            $sub_users = $sub_users_query->rows;
            $log->write('SUB USERS ORDERS');
            $log->write($sub_users);
            $log->write('SUB USERS ORDERS');
            $s_users = array_column($sub_users, 'customer_id');

            array_push($s_users, $order_approval_access_user['parent']);
            $sub_users_od = implode(',', $s_users);
            $log->write($sub_users_od);
        } else {
            $sub_users_query = $this->db->query('SELECT c.customer_id FROM ' . DB_PREFIX . "customer c WHERE parent = '" . (int) $this->customer->getId() . "'");
            $sub_users = $sub_users_query->rows;
            $log->write('SUB USERS ORDERS');
            $log->write($sub_users);
            $log->write('SUB USERS ORDERS');
            $s_users = array_column($sub_users, 'customer_id');

            array_push($s_users, $this->customer->getId());
            $sub_users_od = implode(',', $s_users);
            $log->write($sub_users_od);
        }



        $query = $this->db->query('SELECT COUNT(*) AS total FROM `' . DB_PREFIX . "order` o WHERE customer_id IN (" . $sub_users_od . ") AND o.order_status_id = '15'   ");

        $log->write($query->row['total']);
        //return $query;
        return $query->row['total'];
    }

    public function getPendingOrders($start = 0, $limit = 20, $noLimit = false) {
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 1;
        }

        $log = new Log('error.log');
        $s_users = [];
        $parent_user_id = NULL;
        $order_approval_access = $this->db->query('SELECT c.customer_id, c.parent FROM ' . DB_PREFIX . "customer c WHERE c.customer_id = '" . (int) $this->customer->getId() . "' AND c.order_approval_access = 1 AND (c.order_approval_access_role = 'head_chef' OR c.order_approval_access_role = 'procurement_person')");
        $order_approval_access_user = $order_approval_access->row;

        if (is_array($order_approval_access_user) && count($order_approval_access_user) > 0) {
            //$log->write('order_approval_access_user');
            //$log->write($order_approval_access_user);
            //$log->write('order_approval_access_user');
            $parent_user_id = $order_approval_access_user['parent'];
        }

        if ($parent_user_id != NULL) {
            $sub_users_query = $this->db->query('SELECT c.customer_id FROM ' . DB_PREFIX . "customer c WHERE parent = '" . (int) $parent_user_id . "'");
            $sub_users = $sub_users_query->rows;
            //$log->write('SUB USERS ORDERS');
            //$log->write($sub_users);
            //$log->write('SUB USERS ORDERS');
            $s_users = array_column($sub_users, 'customer_id');
            array_push($s_users, $order_approval_access_user['parent']);
            $sub_users_od = implode(',', $s_users);
        } else {
            $sub_users_query = $this->db->query('SELECT c.customer_id FROM ' . DB_PREFIX . "customer c WHERE parent = '" . (int) $this->customer->getId() . "'");
            $sub_users = $sub_users_query->rows;
            //$log->write('SUB USERS ORDERS');
            //$log->write($sub_users);
            //$log->write('SUB USERS ORDERS');
            $s_users = array_column($sub_users, 'customer_id');
            array_push($s_users, $this->customer->getId());
            $sub_users_od = implode(',', $s_users);
        }

        if (false == $noLimit) {
            //$sub_users_orders = $this->db->query("SELECT o.order_id FROM " . DB_PREFIX . "order o WHERE customer_id IN (".$sub_users_od.")");
            //$ord = $sub_users_orders->rows;
            //echo "<pre>";print_r($ord);die;

            $query = $this->db->query('SELECT o.customer_id, o.parent_approval, o.head_chef, o.procurement, o.delivery_date,o.delivery_timeslot,o.shipping_zipcode,o.shipping_city_id,o.payment_method,o.payment_code,o.shipping_address,o.shipping_flat_number,o.shipping_method,o.shipping_building_name,o.store_name,o.shipping_name, o.order_id, o.firstname, o.lastname, os.name as status , os.color as order_status_color ,o.order_status_id, o.date_modified , o.date_added, o.total, o.currency_code, o.currency_value, ot.value FROM `' . DB_PREFIX . 'order` o LEFT JOIN ' . DB_PREFIX . 'order_status os ON (o.order_status_id = os.order_status_id) LEFT JOIN ' . DB_PREFIX . 'order_total ot ON (o.order_id = ot.order_id) WHERE o.customer_id IN (' . $sub_users_od . ") AND o.order_status_id = '15' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' AND ot.code = 'total' AND ot.title = 'Total' ORDER BY o.order_id DESC LIMIT " . (int) $start . ',' . (int) $limit);
            //$query = $this->db->query("SELECT o.delivery_date,o.delivery_timeslot,o.shipping_zipcode,o.shipping_city_id,o.payment_method,o.shipping_address,o.shipping_flat_number,o.shipping_method,o.shipping_building_name,o.store_name,o.shipping_name, o.order_id, o.firstname, o.lastname, os.name as status , os.color as order_status_color ,o.order_status_id, o.date_modified , o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '" . (int) $this->customer->getId() . "' AND o.order_status_id = '15' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY o.order_id DESC LIMIT " . (int) $start . "," . (int) $limit);
        } else {
            $query = $this->db->query('SELECT o.customer_id, o.parent_approval, o.head_chef, o.procurement, o.delivery_date,o.delivery_timeslot,o.shipping_zipcode,o.shipping_city_id,o.payment_method,o.payment_code,o.shipping_address,o.shipping_flat_number,o.shipping_method,o.shipping_building_name,o.store_name,o.shipping_name, o.order_id, o.firstname, o.lastname, os.name as status , os.color as order_status_color ,o.order_status_id, o.date_modified , o.date_added, o.total, o.currency_code, o.currency_value, ot.value FROM `' . DB_PREFIX . 'order` o LEFT JOIN ' . DB_PREFIX . 'order_status os ON (o.order_status_id = os.order_status_id) LEFT JOIN ' . DB_PREFIX . 'order_total ot ON (o.order_id = ot.order_id) WHERE o.customer_id IN (' . $sub_users_od . ") AND o.order_status_id = '15' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' AND ot.code = 'total' AND ot.title = 'Total' ORDER BY o.order_id DESC");
            //$query = $this->db->query("SELECT o.delivery_date,o.delivery_timeslot,o.shipping_zipcode,o.shipping_city_id,o.payment_method,o.shipping_address,o.shipping_flat_number,o.shipping_method,o.shipping_building_name,o.store_name,o.shipping_name, o.order_id, o.firstname, o.lastname, os.name as status , os.color as order_status_color ,o.order_status_id, o.date_modified , o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '" . (int) $this->customer->getId() . "' AND o.order_status_id = '15' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY o.order_id DESC");
        }
        /* if($statuses == null && $payment_methods == null){
          $query = $this->db->query("SELECT o.delivery_date,o.delivery_timeslot,o.shipping_zipcode,o.shipping_city_id,o.payment_method,o.shipping_address,o.shipping_flat_number,o.shipping_method,o.shipping_building_name,o.store_name,o.shipping_name, o.order_id, o.firstname, o.lastname, os.name as status , os.color as order_status_color ,o.order_status_id, o.date_modified , o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '" . (int) $this->customer->getId() . "' AND o.order_status_id = '15' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY o.order_id DESC LIMIT " . (int) $start . "," . (int) $limit);
          }else{
          if($In == true){
          $sql = "SELECT o.delivery_date,o.delivery_timeslot,o.shipping_zipcode,o.shipping_city_id,o.payment_method,o.shipping_address,o.shipping_flat_number,o.shipping_method,o.shipping_building_name,o.store_name,o.shipping_name, o.order_id, o.firstname, o.lastname, os.name as status , os.color as order_status_color ,o.order_status_id, o.date_modified , o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '" . (int) $this->customer->getId() . "' AND o.order_status_id = '15' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "'  AND o.payment_method IN ($payment_methods) AND os.name IN ($statuses) ORDER BY o.order_id DESC";
          }else{
          $sql = "SELECT o.delivery_date,o.delivery_timeslot,o.shipping_zipcode,o.shipping_city_id,o.payment_method,o.shipping_address,o.shipping_flat_number,o.shipping_method,o.shipping_building_name,o.store_name,o.shipping_name, o.order_id, o.firstname, o.lastname, os.name as status , os.color as order_status_color ,o.order_status_id, o.date_modified , o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '" . (int) $this->customer->getId() . "' AND o.order_status_id = '15' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "'  AND o.payment_method IN ($payment_methods) AND os.name NOT IN ($statuses) ORDER BY o.order_id DESC";
          }
          $query = $this->db->query($sql);
          } */
        //$log->write('ORDERS COUNT');
        //$log->write(count($query->rows));
        //$log->write('ORDERS COUNT');

        return $query->rows;
    }

    public function getCompanyName($customer_id) {
        $companyNAme = $this->db->query('SELECT company_name FROM ' . DB_PREFIX . "customer WHERE customer_id='" . $customer_id . "'");
        // echo "<pre>";print_r('SELECT company_name FROM ' . DB_PREFIX . "customer WHERE customer_id='".$customer_id."'");die;


        return $companyNAme->row['company_name'];
    }

    public function checkValidOrder($orderid, $customer_id) {
        $valid = "false";
        $sub_users_query = $this->db->query('SELECT c.customer_id FROM ' . DB_PREFIX . "customer c WHERE parent = '" . (int) $customer_id . "'");
        $sub_users = $sub_users_query->rows;

        $s_users = array_column($sub_users, 'customer_id');
        array_push($s_users, $customer_id);
        $sub_users_od = implode(',', $s_users);

        $orderavailable = $this->db->query('SELECT order_id FROM ' . DB_PREFIX . "order WHERE customer_id IN (" . $sub_users_od . ") and order_id='" . $orderid . "'")->row;
        //   echo "<pre>";print_r('SELECT order_id FROM ' . DB_PREFIX . "order WHERE customer_id IN (" . $sub_users_od . ") and order_id='".$orderid."'");die;

        if ($orderavailable != null)
            $valid = "true";
        // echo "<pre>";print_r($valid);

        return $valid;
    }

    public function getCustomerTotalOrders() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order WHERE customer_id = '" . (int) $this->customer->getId() . "' AND order_status_id > 0");
        return $query->row['total'];
    }

    public function updateWalletOrder($customer_id, $order_id) {
        $query = $this->db->query('SELECT total AS total FROM ' . DB_PREFIX . "order WHERE customer_id = '" . (int) $this->customer->getId() . "' AND order_id = '" . (int) $order_id . "'");
        $total = $query->row['total'];
        $description = 'Wallet amount deducted#' . $order_id;
        $this->db->query('DELETE FROM ' . DB_PREFIX . "customer_credit WHERE customer_id = '" . (int) $customer_id . "' and  order_id = '" . (int) $order_id . "'");
        $this->db->query('INSERT INTO ' . DB_PREFIX . "customer_credit SET customer_id = '" . (int) $customer_id . "', order_id = '" . (int) $order_id . "', description = '" . $description . "', amount = '" . (float) ($total * -1) . "', date_added = NOW()");
        $this->db->query('INSERT INTO ' . DB_PREFIX . "order_transaction_id SET customer_id = '" . (int) $customer_id . "', order_id = '" . (int) $order_id . "', transaction_id = 'Paid from wallet amount'");

        $this->db->query('UPDATE ' . DB_PREFIX . "order SET paid='Y', amount_partialy_paid = 0 ,total='" . (float) $total . "'  WHERE order_id='" . (int) $order_id . "'");

        // echo "<pre>";print_r('INSERT INTO ' . DB_PREFIX . "customer_credit SET customer_id = '" . (int) $customer_id . "', order_id = '" . (int)  $order_id . "', description = '".    $description . "', amount = '" . (float) ($total*-1) . "', date_added = NOW()");die;
    }

    public function getOrderCreditAmount($order_id) {
        $query = $this->db->query('SELECT sum(amount) AS total FROM ' . DB_PREFIX . "customer_credit WHERE order_id = '" . (int) $order_id . "'");
        if ($query->num_rows) {
            $total = $query->row['total'];
            return $total;
        } else {
            return 0;
        }
    }

    public function getPezeshaloans() {

        $s_users = [];
        $sub_users_od = [];
        $parent_user_id = NULL;
        $order_approval_access = $this->db->query('SELECT c.customer_id, c.parent FROM ' . DB_PREFIX . "customer c WHERE c.customer_id = '" . (int) $this->customer->getId() . "' AND c.order_approval_access = 1 AND (c.order_approval_access_role = 'head_chef' OR c.order_approval_access_role = 'procurement_person')");
        $order_approval_access_user = $order_approval_access->row;

        if (is_array($order_approval_access_user) && count($order_approval_access_user) > 0) {
            //$log->write('order_approval_access_user');
            //$log->write($order_approval_access_user);
            //$log->write('order_approval_access_user');
            $parent_user_id = $order_approval_access_user['parent'];
        }

        if ($parent_user_id != NULL) {
            $sub_users_query = $this->db->query('SELECT c.customer_id FROM ' . DB_PREFIX . "customer c WHERE parent = '" . (int) $parent_user_id . "'");
            $sub_users = $sub_users_query->rows;
            //$log->write('SUB USERS ORDERS');
            //$log->write($sub_users);
            //$log->write('SUB USERS ORDERS');
            $s_users = array_column($sub_users, 'customer_id');
            array_push($s_users, $order_approval_access_user['parent']);
            $sub_users_od = implode(',', $s_users);
        } else {
            $sub_users_query = $this->db->query('SELECT c.customer_id FROM ' . DB_PREFIX . "customer c WHERE parent = '" . (int) $this->customer->getId() . "'");
            $sub_users = $sub_users_query->rows;
            $s_users = array_column($sub_users, 'customer_id');

            array_push($s_users, $this->customer->getId());
            $sub_users_od = implode(',', $s_users);
        }

        $pezesha_loans = $this->db->query('SELECT p.loan_id,p.order_id,p.customer_id,o.total,p.loan_type,p.created_at FROM ' . DB_PREFIX . "customer_pezesha_loans p join " . DB_PREFIX . "order o on p.order_id = o.order_id WHERE p.customer_id IN (" . $sub_users_od . ") ORDER BY p.id DESC");
        return $pezesha_loans->rows;
    }

}
