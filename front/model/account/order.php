<?php

class ModelAccountOrder extends Model {

    public function getStatusById($order_status_id) {
        $row = $this->db->query('select * from ' . DB_PREFIX . 'order_status where language_id="' . $this->config->get('config_language_id') . '" AND order_status_id="' . $order_status_id . '"')->row;
        if ($row) {
            return $row['name'];
        }
    }

    public function getStripeOrderPaymentId($order_id) {

        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "stripe_order` where `order_id` = '" . (int)$order_id . "' LIMIT 1");

        if ($query->num_rows) {
            return $query->row;
        } else {
            return false;
        }
    }

    public function updateNewShippingAddress($order_id,$data) {

        $data['shipping_address'] = !empty($data['shipping_flat_number'])?$data['shipping_flat_number'].", ".$data['shipping_landmark']:$data['shipping_landmark'];

        $this->db->query( 
            "UPDATE `" . DB_PREFIX . "order` SET latitude = '" . $this->db->escape($data['shipping_latitude']) .
            "', longitude = '" .$data['shipping_longitude']. 
            "', shipping_landmark = '" .$data['shipping_landmark']. 
            "', shipping_flat_number = '" .$data['shipping_flat_number']. 
            "', shipping_zipcode = '" .$data['shipping_zipcode']. 
            "', shipping_building_name = '" .$data['shipping_building_name']. 
            "', shipping_address = '" .$data['shipping_address']. 
            "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . 
            "'" );  

        return true;

    }

    public function updateOnlyFlatNumber($order_id,$data,$old_address) {

        $data['shipping_address'] = !empty($data['shipping_flat_number'])?$data['shipping_flat_number'].", ".$old_address['shipping_landmark']:$old_address['shipping_landmark'];

        $this->db->query( 
            "UPDATE `" . DB_PREFIX . "order` SET 
             shipping_flat_number = '" .$data['shipping_flat_number']. 
            "', shipping_address = '" .$data['shipping_address']. 
            "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . 
            "'" );  

        return true;

    }

    

    public function editTimeslotOrder($order_id,$data) {

        $this->db->query( "UPDATE `" . DB_PREFIX . "order` SET delivery_date = '" .$this->db->escape(date('Y-m-d',strtotime($data['delivery_date']))) ."', delivery_timeslot = '" .$data['delivery_timeslot']. "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'" );  

        return true;

    }


    public function getOrderDSDeliveryId($order_id) {
        $s = $this->db->query( "Select delivery_id from `" . DB_PREFIX . "order` WHERE order_id = '" . (int) $order_id . "'" ); 
        if($s->num_rows && !empty($s->row['delivery_id'])) {

            return $s->row['delivery_id'];
        }

        return false;
    }
    
    public function getNonDSCreatedOrders($pickupStatus) {


        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` o WHERE o.delivery_id is null  AND DATE(o.delivery_date) >= DATE('" . $this->db->escape(date('Y-m-d',strtotime('-1 day'))) . "') and o.order_status_id in (".implode(",",$pickupStatus).")");
        
        //echo "<pre>";print_r($query);die;
        return $query->rows;
    }

    
    public function getCashbackAmount($order_id) {

        return $this->db->query("SELECT amount FROM `" . DB_PREFIX . "coupon_history` WHERE order_id = '".$order_id."'")->row;
    }

    public function hasRealOrderProducts($order_id) {

        $sql = "SELECT * FROM " . DB_PREFIX . "real_order_product WHERE order_id = '" . (int) $order_id ."'";
        
        $query = $this->db->query($sql);

        if($query->num_rows) {
            return true;
        }

        return false;

    }

    public function payment_status($order_id, $status, $store_id){
            
            $log = new Log('error.log');

            $log->write('order payment distribution fn');

            $this->load->model('sale/order');
            
            /*$this->db->query('update `' . DB_PREFIX . 'order` SET commsion_received="' . $status . '" WHERE store_id="' . $store_id . '" AND order_id="' . $order_id . '"');*/
            
            $order_info = $this->db->query('select * from '.DB_PREFIX.'order LEFT JOIN '.DB_PREFIX.'store on('.DB_PREFIX.'store.store_id = '.DB_PREFIX.'order.store_id) WHERE '.DB_PREFIX.'order.store_id="'.$store_id.'" AND order_id="'.$order_id.'"')->row;

            $order_sub_total = $order_info['total'];

            $shipping_charges = 0;

            $deduct_from_vendor_shipping = false;

            $order_total_info = $this->db->query('select * from '.DB_PREFIX.'order_total  WHERE order_id="'.$order_id.'" and code="shipping"')->row;

            if(is_array($order_total_info) && !is_null($order_total_info['actual_value'])) {

                $tempCalc = $order_total_info['value'] + 0;
                if(!$tempCalc) {
                    $deduct_from_vendor_shipping = true;
                }

                $shipping_charges = $order_total_info['actual_value']; 
            }

            //shipping_charges to be sent to delviery system

            $order_total_info = $this->db->query('select * from '.DB_PREFIX.'order_total  WHERE order_id="'.$order_id.'" and code="sub_total"')->row;

            if(is_array($order_total_info)) {
               $order_sub_total = $order_total_info['value']; 
            }

            

            if(isset($order_info['settlement_amount'])) {
                $order_sub_total = $order_info['settlement_amount'];
            }

            //echo "<pre>";print_r($order_sub_total);die;

            //print_r($order_info);die;
            

            $temp_vendor_commision = 0;
            $temp_vendor_com = 0;

            $store_info = $this->getStoreData($store_id);

            //echo "<pre>";print_r($store_info);die;
            if($store_info['commission_type'] ==  'category') {
                $order_products = $this->getOrderProductsAdminCopy($order_id,$store_id);

                //echo "<pre>";print_r($order_products);die;
                foreach ($order_products as $key => $value) {

                    
                    //this is product store id $value['product_id']

                    $p_to_s_info = $this->db->query('select * from '.DB_PREFIX.'product_to_store  WHERE product_store_id='.$value['product_id'])->row;

                    if(count($p_to_s_info) > 0) {

                        $cat_info = [];

                       $cat_infos = $this->db->query('select * from '.DB_PREFIX.'product_to_category  WHERE product_id='.$p_to_s_info['product_id'])->rows;

                       //echo "<pre>";print_r($cat_infos);die;
                        foreach ($cat_infos as $key => $value_tmp) {

                            $cat_temp = $this->db->query('select * from '.DB_PREFIX.'category  WHERE category_id='.$value_tmp['category_id'])->row;

                            if($cat_temp['parent_id'] == 0) {
                                $cat_info = $value_tmp;
                            }
                        }

                        //echo "<pre>";print_r($cat_info);die;
                        

                        if(count($cat_info) > 0) {

                            $cat_id = $cat_info['category_id']; 

                            $cat_commission = $this->getStoreCategoryCommision($store_id,$cat_id);

                            //echo "<pre>";print_r($cat_commission);die;

                            if(count($cat_commission) > 0) {
                                
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

            if($deduct_from_vendor_shipping) {
                $vendor_commision =  $vendor_commision - $shipping_charges;
            }

            $order_total_info_tax = $this->db->query('select * from '.DB_PREFIX.'order_total  WHERE order_id="'.$order_id.'" and code="tax"')->row;

            if(is_array($order_total_info_tax) && isset($order_total_info_tax['value'])) {
               $vendor_commision += $order_total_info_tax['value']; 
            }



            $log->write('front payment_status');

            $log->write("vendor_commision".$vendor_commision);
            $log->write("admin_commision".$admin_commision);
            $log->write("delivery sytem send".$shipping_charges);
            $log->write("status".$status);
            $log->write($order_info);

            
            $vendorCommision = 0;
            if($status == 1 && $order_info['commsion_received'] == 1) {
                
                $log->write("vendor_commision if ".$vendor_commision);
                
                //echo "<pre>";print_r("s");die;
                //$vendorCommision = $vendor_commision;
                $vendorCommision = 0 -$vendor_commision;

                $this->db->query('insert into `'.DB_PREFIX.'vendor_wallet` SET vendor_id="'.$order_info['vendor_id'].'", order_id="'.$order_id.'", description="Order Value : '.$this->currency->format($order_sub_total).'", amount="'.$vendor_commision.'", date_added=NOW()');

                //Admin Waller add
                $this->db->query('insert into `'.DB_PREFIX.'admin_wallet` SET  order_id="'.$order_id.'", description="admin commision", amount="'.$admin_commision.'", date_added=NOW()');
                

                if($order_info['shipping_code'] == "shopper.shopper") {
                    if ($order_info['payment_code'] == 'cod') {
                        //debit order_total - shopper commision                         
                        $this->db->query('insert into `'.DB_PREFIX.'shopper_wallet` SET shopper_id="'.$order_info['shopper_id'].'", order_id="'.$order_id.'", description="shopper commision", amount="'.($order_sub_total - $shopper_commision).'", date_added=NOW()');
                    } else {
                        //credit shopper commision   
                        $this->db->query('insert into `'.DB_PREFIX.'shopper_wallet` SET shopper_id="'.$order_info['shopper_id'].'", order_id="'.$order_id.'", description="shopper commision", amount="'.$shopper_commision.'", date_added=NOW()');
                    }
                }
                
            }else{
                
                $log->write("vendor_commision else".$vendor_commision);
                //$vendorCommision = 0 -$vendor_commision;
                $vendorCommision = $vendor_commision;
                $this->db->query('insert into `'.DB_PREFIX.'vendor_wallet` SET vendor_id="'.$order_info['vendor_id'].'", order_id="'.$order_id.'", description="Order Value : '.$this->currency->format($order_sub_total).'", amount="'.$vendor_commision.'", date_added=NOW()');
                    
                //Admin Wallet add
                $this->db->query('insert into `'.DB_PREFIX.'admin_wallet` SET  order_id="'.$order_id.'", description="admin commision", amount="'.(0-$admin_commision).'", date_added=NOW()');


                if($order_info['shipping_code'] == "shopper.shopper") {
                    if ($order_info['payment_code'] == 'cod') {
                        //debit order_total - shopper commision                         
                        $this->db->query('insert into `'.DB_PREFIX.'shopper_wallet` SET shopper_id="'.$order_info['shopper_id'].'", order_id="'.$order_id.'", description="shopper commision", amount="'.(0 - ($order_sub_total - $shopper_commision)).'", date_added=NOW()');
                    } else {
                        //credit shopper commision   
                        $this->db->query('insert into `'.DB_PREFIX.'shopper_wallet` SET shopper_id="'.$order_info['shopper_id'].'", order_id="'.$order_id.'", description="shopper commision", amount="'.(0-$shopper_commision).'", date_added=NOW()');
                    }
                }
            }

            $vendorData = $this->getVendorDetails($order_info['vendor_id']);

            //echo "<pre>";print_r($vendorData);die;
          
            if(isset($vendorData['email'])) {
                // 6 merchant mail
                $vendorData['amount'] = $vendorCommision;

                $vendorData['transaction_type'] = 'credited';

                if($vendorData['amount'] <= 0 ) {
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
            if ( $this->emailtemplate->getNotificationEnabled('Contact','contact_6')) {
                
                $vendorData['amount'] = $vendorCommision;
                $vendorData['transaction_type'] = 'credited';

                if($vendorData['amount'] <= 0 ) {
                    $vendorData['transaction_type'] = 'debited';
                }

                $vendorData['amount'] = $this->currency->format($vendorData['amount']);
                
                $log->write('status enabled of wallet mobi noti');
                $mobile_notification_template = $this->emailtemplate->getNotificationMessage( 'Contact', 'contact_6', $vendorData );

                $log->write($mobile_notification_template);

                $mobile_notification_title = $this->emailtemplate->getNotificationTitle( 'Contact', 'contact_6', $vendorData );

                $log->write($mobile_notification_title);

                $log->write($vendorData);

                if(isset($vendorData['device_id']) && strlen($vendorData['device_id']) > 0 ) {

                    /*$log->write('device id set');
                    $ret =  $this->emailtemplate->sendVendorPushNotification($order_info['vendor_id'],$vendorData['device_id'],$order_id,$order_info['store_id'],$mobile_notification_template,$mobile_notification_title);

                    $this->saveVendorNotification($order_info['vendor_id'],$vendorData['device_id'],$order_id,$mobile_notification_template,$mobile_notification_title);*/

                    $log->write('device id set');

                    $notification_id = $this->saveVendorNotification($order_info['vendor_id'],$vendorData['device_id'],$order_id,$mobile_notification_template,$mobile_notification_title);

                    $sen['notification_id'] = $notification_id;

                    $ret =  $this->emailtemplate->sendVendorPushNotification($order_info['vendor_id'],$vendorData['device_id'],$order_id,$order_info['store_id'],$mobile_notification_template,$mobile_notification_title,$sen);

                } else {

                    $log->write('device id not set ');

                }
            }

    }

    public function saveVendorNotification($user_id,$deviceId,$order_id,$message,$title) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "vendor_notifications SET user_id = '" .$user_id. "', type = 'wallet', purpose_id = '" . $order_id ."', title = '" . $title ."', message = '" . $message ."', status = 'unread', created_at = NOW() , updated_at = NOW()");

        $notificaiton_id = $this->db->getLastId();

        return $notificaiton_id;

    }
    

    public function getVendorDetails($vendor_id) {

        $sql  = 'select *, CONCAT(firstname," ",lastname) as name from `'.DB_PREFIX.'user`';
        $sql .= ' WHERE user_id="'.$vendor_id.'" AND user_group_id =11 LIMIT 1';
        
        return $this->db->query($sql)->row;
    }
    
    public function getStoreData($store_id) {
        
        return $this->db->query('select * from '.DB_PREFIX.'store where store_id ='.$store_id.'')->row;
    }

    public function getStoreCategoryCommision($store_id,$category_id) {
        
        $sql  = 'select * from `'.DB_PREFIX.'store_category_commission`';
        $sql .= ' WHERE store_id="'.$store_id.'" and category_id="'.$category_id.'"  LIMIT 1';
        
        return $this->db->query($sql)->row;
    }

    public function getOrderProductsAdminCopy($order_id, $store_id = 0) {
       
        $sql = "SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'";
        
        if($store_id) {
            $sql .= " AND store_id='".$store_id."'";
        }
        
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getOrder($order_id) {
        
        $order_query = $this->db->query("SELECT * ," . DB_PREFIX . "order.date_added as order_date ," . DB_PREFIX . "order.email as order_email ," . DB_PREFIX . "order.telephone as order_telephone FROM `" . DB_PREFIX . "order` LEFT JOIN " . DB_PREFIX . "store ON ( ". DB_PREFIX . "store.store_id = ". DB_PREFIX . "order.store_id) LEFT JOIN " . DB_PREFIX . "order_status ON ( ". DB_PREFIX . "order_status.order_status_id = ". DB_PREFIX . "order.order_status_id)  WHERE order_id = '" . (int) $order_id . "' AND customer_id = '" . (int) $this->customer->getId() . "' AND ". DB_PREFIX . "order.order_status_id > '0' ");

        if ($order_query->num_rows) {

            $city_info = $this->db->query('select * from `'.DB_PREFIX.'city` WHERE city_id="'.$order_query->row['shipping_city_id'].'"')->row;
            
            if($city_info) {
                $shipping_city = $city_info['name'];
            }else{
                $shipping_city = '';
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
                'delivery_timeslot'=>$order_query->row['delivery_timeslot'],
                'delivery_date'=>$order_query->row['delivery_date'],
                'store_address'=>$order_query->row['address'],
                'store_name'=>$order_query->row['store_name'],
                'status'=>$order_query->row['name'],
                'order_date'=>$order_query->row['order_date'],
                'delivery_id'=>$order_query->row['delivery_id'],
                'settlement_amount'=>$order_query->row['settlement_amount'],
            );
        } else {
            return false;
        }
    }

    public function getOrderByReferenceId($order_reference_number) {
        
        $order_query = $this->db->query("SELECT * ," . DB_PREFIX . "order.date_added as order_date ," . DB_PREFIX . "order.email as order_email ," . DB_PREFIX . "order.telephone as order_telephone FROM `" . DB_PREFIX . "order` LEFT JOIN " . DB_PREFIX . "store ON ( ". DB_PREFIX . "store.store_id = ". DB_PREFIX . "order.store_id) LEFT JOIN " . DB_PREFIX . "order_status ON ( ". DB_PREFIX . "order_status.order_status_id = ". DB_PREFIX . "order.order_status_id)  WHERE order_reference_number = '" . $order_reference_number . "' AND ". DB_PREFIX . "order.order_status_id > '0' ");

        if ($order_query->num_rows) {
            return true;
        } else {
            return false;
        }
    }

    public function getOrderByReferenceIdApi($order_reference_number) {
        
        $order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_reference_number = '" . $order_reference_number . "'");

        return $order_query->row;
    }


    public function getOrderByReferenceIdIPay($order_reference_number) {
        
        $order_query = $this->db->query("SELECT * FROM `".DB_PREFIX."order`  WHERE order_reference_number = '" . $order_reference_number."'");

        if ( $order_query->num_rows ) {

            return $order_query->rows;
        } else {
            return false;
        }
    }


    public function getAdminOrder($order_id) {

        $order_query = $this->db->query( "SELECT *, (SELECT os.name FROM `" . DB_PREFIX . "order_status` os WHERE os.order_status_id = o.order_status_id AND os.language_id = o.language_id) AS order_status FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int) $order_id . "'" );

        if ( $order_query->num_rows ) {

            return $order_query->row;
        } else {
            return false;
        }
    }


    public function getOrders($start = 0, $limit = 20) {

        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 1;
        }

        $query = $this->db->query("SELECT o.delivery_date,o.delivery_timeslot,o.shipping_zipcode,o.shipping_city_id,o.payment_method,o.shipping_address,o.shipping_flat_number,o.shipping_method,o.shipping_building_name,o.store_name,o.shipping_name, o.order_id, o.firstname, o.lastname, os.name as status , os.color as order_status_color ,o.order_status_id, o.date_modified , o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '" . (int) $this->customer->getId() . "' AND o.order_status_id > '0' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY o.order_id DESC LIMIT " . (int) $start . "," . (int) $limit);
        
        return $query->rows;
    }

    public function getOrderProduct($order_id, $order_product_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "' AND order_product_id = '" . (int) $order_product_id . "'");

        return $query->row;
    }

    public function getCityName($city_id) {
        $city_info = $this->db->query('select * from `'.DB_PREFIX.'city` WHERE city_id="'.$city_id.'"')->row;

        if($city_info) {
            $shipping_city = $city_info['name'];
        }else{
            $shipping_city = '';
        }

        return $shipping_city;
    }

    public function getCityState($city_id) {

        $shipping_state = '';

        $city_info = $this->db->query('select * from `'.DB_PREFIX.'city` WHERE city_id="'.$city_id.'"')->row;

        if($city_info) {

            $state_info = $this->db->query('select * from `'.DB_PREFIX.'state` WHERE state_id="'.$city_info['state_id'].'"')->row;

            if($state_info) {
                $shipping_state = $state_info['name'];
            }
        }

        return $shipping_state;
    }


    public function getOrderProductByProductId($order_id, $product_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "' AND product_id = '" . (int) $product_id . "'");

        return $query->row;
    }

    public function getOrderProducts($order_id) {

        /*$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");*/
        $query = $this->db->query("SELECT a.*,b.image as image,b.weight as weight
        FROM `" . DB_PREFIX . "order_product` a,`" . DB_PREFIX . "product` b,`" . DB_PREFIX . "product_to_store` c
        WHERE b.product_id=c.product_id
        AND a.product_id=c.product_store_id
        AND a.order_id='".$order_id."'");

        return $query->rows;
    }

    public function getRealOrderProducts($order_id) {

        /*$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");*/
        $query = $this->db->query("SELECT a.*,b.image as image,b.weight as weight
        FROM `" . DB_PREFIX . "real_order_product` a,`" . DB_PREFIX . "product` b,`" . DB_PREFIX . "product_to_store` c
        WHERE b.product_id=c.product_id
        AND a.product_id=c.product_store_id
        AND a.order_id='".$order_id."'");

        $query1 = $this->db->query("SELECT a.*
        FROM `" . DB_PREFIX . "real_order_product` a
        WHERE a.order_id='".$order_id."' and a.product_id REGEXP '^-?[^0-9]+$'");

        $p = $query->rows;
        $q = $query1->rows;

        foreach ($q as $key => $value) {
            array_push($p, $value);    
        }
        //echo "<pre>";print_r($p);die;
        
        return $p;
    }


    public function getOrderOptions($order_id, $order_product_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int) $order_id . "' AND order_product_id = '" . (int) $order_product_id . "'");

        return $query->rows;
    }

    public function getOrderVouchers($order_id) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_voucher` WHERE order_id = '" . (int) $order_id . "'");

        return $query->rows;
    }

    public function getOrderTotals($order_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int) $order_id . "' ORDER BY sort_order");

        return $query->rows;
    }

    public function getOrderHistories($order_id) {
        $query = $this->db->query("SELECT date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int) $order_id . "' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY oh.date_added");

        return $query->rows;
    }

    public function getLatestOrderHistories($order_id) {
        $query = $this->db->query("SELECT date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int) $order_id . "' AND oh.date_added >= DATE('" .  $this->db->escape( date("Y-m-d H:i:s", strtotime("-2 minute")) ) . "') AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY oh.date_added desc");

        return $query->row;
    }


    public function getTotalOrders() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` o WHERE customer_id = '" . (int) $this->customer->getId() . "' AND o.order_status_id > '0' ");

        //return $query;
        return $query->row['total'];
    }

    public function getTotalOrderProductsByOrderId($order_id) {
        /*$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");

        return $query->row['total'];*/
        $query = $this->db->query("SELECT SUM(quantity) AS total FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");

        return $query->row['total'];
    }

    public function getTotalRealOrderProductsByOrderId($order_id) {
        /*$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "real_order_product WHERE order_id = '" . (int) $order_id . "'");

        return $query->row['total'];*/

        $query = $this->db->query("SELECT SUM(quantity) AS total FROM " . DB_PREFIX . "real_order_product WHERE order_id = '" . (int) $order_id . "'");

        return $query->row['total'];
    }


    public function getTotalOrderVouchersByOrderId($order_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order_voucher` WHERE order_id = '" . (int) $order_id . "'");

        return $query->row['total'];
    }

    public function getStoreById($store_id) {
        $sql  = 'select * from `' . DB_PREFIX . 'store` where store_id ="'. $store_id.'"';
        return $this->db->query($sql)->row;       
    }

    public function updateOrderDeliveryId($delivery_id,$order_id) {

        $this->db->query( "UPDATE `" . DB_PREFIX . "order` SET delivery_id = '" .$delivery_id ."', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'" );  

    }

    public function saveRatingOrder($rating,$order_id) {

        $this->db->query( "UPDATE `" . DB_PREFIX . "order` SET rating = '" .$rating ."', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'" );  

    }

    public function getFormatedOrder($order_id) {

        return $this->db->query( "SELECT *, (SELECT os.name FROM `" . DB_PREFIX . "order_status` os WHERE os.order_status_id = o.order_status_id AND os.language_id = o.language_id) AS order_status FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int) $order_id . "'" );
    }

    public function getAppOrderStatuses() {
        
        $p = [];

        $sql = "SELECT * FROM " . DB_PREFIX . "app_order_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";

        $sql .= " ORDER BY name";

        $query = $this->db->query($sql);

        foreach ($query->rows as $row) {
            if($row && isset($row['app_order_status_id'])) {

                

                $sql1 = "SELECT * FROM " . DB_PREFIX . "app_order_status_mapping where app_order_status_id=".$row['app_order_status_id'] ;

                $query1 = $this->db->query($sql1);

                if($query1->row && isset($query1->row['app_order_status_id'])) {
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

        $sql = "SELECT * FROM " . DB_PREFIX . "app_order_status_mapping where order_status_id=".$order_status_id;


        $query = $this->db->query($sql);

        //echo "<pre>";print_r($query->row);die;
        if($query->row && isset($query->row['app_order_status_id'])) {

            $p['code'] = $query->row['code'];
            $sql1 = "SELECT * FROM " . DB_PREFIX . "app_order_status where app_order_status_id=".$query->row['app_order_status_id'] . ' AND language_id="' . $this->config->get('config_language_id') . '"' ;

            $query1 = $this->db->query($sql1);

            //echo "<pre>";print_r($query1->row);die;
            
            if($query1->row && isset($query1->row['name'])) {
                $p['name'] = $query1->row['name'];
                $resp['data']= $p;
                $resp['status'] = true;
            }
        }

        return $resp;
    }

}

