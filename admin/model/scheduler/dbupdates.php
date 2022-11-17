<?php

class ModelSchedulerDbupdates extends Model {


    public function UpdateOrderProcessing($delivery_date) {
        try{
            $log = new Log('error.log'); 
            // update `hf7_order` set order_status_id=1 WHERE `order_status_id`=14 and delivery_date ='2021-02-19';
            // UPDATE `hf7_order` SET order_processing_group_id = 1, order_processor_id = 1 WHERE order_status_id = 1 and delivery_date ='2021-02-19';
            $sqlSelect = 'select order_id from  ' . DB_PREFIX . "order WHERE order_status_id = 14 and delivery_date='".$delivery_date."'";//
            $Order_ids= $this->db->query($sqlSelect)->rows;
            $sql = 'UPDATE  ' . DB_PREFIX . "order SET  order_status_id = '1', order_processing_group_id = 1, order_processor_id = 1 WHERE order_status_id = 14 and delivery_date='".$delivery_date."'";
         echo "<pre>";print_r($Order_ids);    
            $result= $this->db->query($sql);
                try{    
                    foreach ($Order_ids as $order_id) {
                       $this->addProcessingOrderHistory($order_id['order_id'],1,"Automatically updated",false,null,null);
                    }
                }
                catch(exception $ex)
                {
                    $log->write('UpdateOrderProcessing automatically -error');
                }


        if (!$result) {
            //   die('Invalid query: ' . mysql_error());
            $result=0;
        }
        else
        $result=1;
        }

        catch(exception $ex)
        {
            $log->write('UpdateOrderProcessing automatically -error');

            $result=-1;
        }
       
        return $result;

    }


    public function addProcessingOrderHistory($order_id, $order_status_id, $comment = '', $notify = true, $added_by = '', $added_by_role = '') 
    {
        $log = new Log('error.log'); 
        $this->trigger->fire('pre.order.history.add', $order_id);
        try{
                // $order_info = $this->getOrder($order_id);     
                // if($order_info!=null)  
                {
                    $log->write('addProcessingOrderHistory -error'.$order_id);
                
                    $this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int) $order_id . "', added_by = '" . (int) $added_by . "', role = '" . $added_by_role . "', order_status_id = '" . (int) $order_status_id . "', notify = '" . (int) $notify . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
                
                    // Stock subtraction
                    $order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");
                    $real_order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "real_order_product WHERE order_id = '" . (int) $order_id . "'");
                    $products=$real_order_product_query->rows;
                    if($products==null || $products=="")
                    $products=$order_product_query->rows;
                    foreach ($products as $order_product) {
                        $this->db->query("UPDATE " . DB_PREFIX . "product_to_store SET quantity = (quantity - " . (float) $order_product['quantity'] . ") WHERE product_store_id = '" . (int) $order_product['product_id'] . "' AND subtract_quantity = '1'");
                    }
                }
            }
            catch(exception $ex)
            {
                $log->write('addProcessingOrderHistory -error');
            }

         return true;   
    }


    public function insertLogURL($log_file_url) 
    {
        $log = new Log('error.log');  
        try
        {                 
           $result= $this->db->query("INSERT INTO " . DB_PREFIX . "logfile_url SET url = '" .  $log_file_url . "', date_added = NOW()");
           if($result)
            { return 1;
            }
            else {
                return 0;
            }
        }
        catch(exception $ex)
        {
            $log->write('Log URL insertion Error');
            return 0;
        }

     }

     public function getWalletRunningLowCustomers() {

        $sql = "SELECT distinct c.customer_id FROM " . DB_PREFIX . "customer_credit c where c.customer_id>0 order by c.customer_id ASC";
         
        $query = $this->db->query($sql);

        //  echo "<pre>";print_r($sql);die;

        return $query->rows;
     }

     public function checkWalletRunningLow($customer_id) {
        $log = new Log('error.log');
        //check the customer wallet and send mail, if wallet is low
        $query = $this->db->query('SELECT SUM(amount) AS total FROM `' . DB_PREFIX . "customer_credit` WHERE customer_id = '" . (int) $customer_id . "' GROUP BY customer_id");
        // $customer_wallet_amount = 0;
        $customer_order_average = 0;
        if ($query->num_rows) {
            $customer_wallet_amount = $query->row['total'];
        }
        //get average order value of customer
        //SELECT AVG(total) AS total FROM (select total,order_id from `hf7_order` WHERE customer_id = '273'   ORDER BY order_id DESC   LIMIT 0, 3) as t
        $query1 = $this->db->query('SELECT AVG(total) AS total FROM (select total,order_id FROM `' . DB_PREFIX . "order` WHERE total>0 and customer_id = '" . (int) $customer_id . "' ORDER BY order_id DESC LIMIT 0, 5) as t");
        // echo '<pre>';print_r('SELECT AVG(total) AS total FROM (select total,order_id FROM `'.DB_PREFIX."order` WHERE customer_id = '".(int) $customer_id."' ORDER BY order_id DESC LIMIT 0, 3) as t");exit;
        if ($query1->num_rows) {
            $customer_order_average = $query1->row['total'];
        }
        // echo '<pre>';print_r( $customer_order_average);die;
        $log = new Log('error.log');
        $log->write($customer_wallet_amount);
        $log->write('Above wallet, below average order');
        $log->write($customer_order_average);
        if (isset($customer_wallet_amount) && $customer_wallet_amount > 0 && $customer_wallet_amount <= $customer_order_average) {
           
            $log->write("average value of wallet order ");  
             $log->write($customer_id);
           
            //then send mail to customer
        $this->load->model('account/customer');

            $data = $this->model_account_customer->getCustomerById($customer_id);
            $data = $data[0];
            //    echo '<pre>'; print_r($data);die;
            // $log->write($data['email']);

            try {
                if ($data['email_notification'] == 1 && $this->emailtemplate->getEmailEnabled('Customer', 'customer_19')) {
                    $log->write('low wallet mail sending');
                    $subject = $this->emailtemplate->getSubject('Customer', 'customer_19', $data);
                    $message = $this->emailtemplate->getMessage('Customer', 'customer_19', $data);
                    $sms_message = $this->emailtemplate->getSmsMessage('Customer', 'customer_19', $data);
                    // echo '<pre>'; print_r($subject);die;

                    $mail = new Mail($this->config->get('config_mail'));
                    $mail->setTo($data['email']);
                    $mail->setFrom($this->config->get('config_from_email'));
                    $mail->setSender($this->config->get('config_name'));
                    $mail->setSubject($subject);
                    $mail->setHTML($message);
                     $mail->send();

                    $log->write("Wallet low mail send to -".$customer_id."mail ID".$data['email']);

                    //   return;
                }



                if ($data['sms_notification'] == 1 && $this->emailtemplate->getSmsEnabled('Customer', 'customer_19')) {

                    $ret = $this->emailtemplate->sendmessage($data['telephone'], $sms_message);
                }

                // if ($this->emailtemplate->getNotificationEnabled('Customer', 'customer_19')) {
                //     $mobile_notification_template = $this->emailtemplate->getNotificationMessage('Customer', 'customer_19', $data);
                //     //$log->write($mobile_notification_template);
                //     $mobile_notification_title = $this->emailtemplate->getNotificationTitle('Customer', 'customer_19' , $data);
                //     //$log->write($mobile_notification_title);
                //     if (isset($data) && isset($data['device_id']) && $data['mobile_notification'] == 1 && strlen($data['device_id']) > 0) {
                //         $log->write('customer device id set FRONT.MODEL.CHECKOUT.ORDER');
                //         $ret = $this->emailtemplate->sendPushNotification($data['customer_id'], $data['device_id'], '', '', $mobile_notification_title, $mobile_notification_template, 'com.instagolocal.showorder');
                //     } else {
                //         $log->write('customer device id not set FRONT.MODEL.CHECKOUT.ORDER');
                //     }
                // }
            } catch (Exception $e) {
                //   echo '<pre>';print_r( $data);die;
            }
        }
    }


    public function getCutoffTimes() 
    {

        $sql = "SELECT distinct timeslot,cut_off_time FROM " . DB_PREFIX . "store_delivery_timeslot  where status=1 and store_id=75";
         
        $query = $this->db->query($sql);

        //  echo "<pre>";print_r($sql);die;

        return $query->rows;
    }

     
     public function updateUnapprovedOrdersTimeslot($timeslot,$new_timeslot,$delivery_date) {

        $sql_select = "select order_id from " . DB_PREFIX . "order  where timeslot=" .$timeslot." and  delivery_date=".$delivery_date." and order_status_id=15";
       
        $sql = "update " . DB_PREFIX . "order set timeslot=".$new_timeslot." where timeslot=" .$timeslot." and  delivery_date=".$delivery_date." and order_status_id=15";
        $log = new Log('error.log');
        $log->write("updated orders timeslot");
        $query_select = $this->db->query($sql_select);
        $query = $this->db->query($sql);
        //  echo "<pre>";print_r($sql);die;
        $log->write($query_select->rows);
        $log->write($query->rows);

        // return $query->rows;
     }


     public function getUnapprovedOrders($timeslot,$new_timeslot,$delivery_date) {

        $sql_select = "select o.order_id,o.customer_id,c.parent,o.firstname,o.lastname,c.company_name,o.ip,c.device_id from " . DB_PREFIX . "order o  join ". DB_PREFIX . "customer c on o.customer_id=c.customer_id where o.timeslot=" .$timeslot." and  o.delivery_date=".$delivery_date." and o.order_status_id=15";
       
        $log = new Log('error.log');
        $log->write("selected orders timeslot");
        $query_select = $this->db->query($sql_select);
        //  echo "<pre>";print_r($sql);die;
        $log->write($query_select->rows);

        // return $query->rows;
     }

     public function GetUnallocatedFundCustomers() {
        try{
            $log = new Log('error.log'); 
            $sqlSelect = 'select distinct customer_id from  ' . DB_PREFIX . "customer_unallocated_fund WHERE closed = 0 ";// || available_balance>0
            $customer_ids= $this->db->query($sqlSelect)->rows;
            // echo "<pre>";print_r($customer_ids); 
            return $customer_ids;
        }
        catch(exception $ex)
        {
            $log = new Log('error.log'); 
            $log->write('GetUnallocatedFundCustomers - error');
            $log->write($ex);
            return null;
        }       

    }



    public function GetCustomerPendingOrders($customer_id) {
        try{
            $log = new Log('error.log'); 
            $sqlSelect = 'select o.order_id,ot.value as total,o.amount_partialy_paid,o.delivery_date from  ' . DB_PREFIX . "order o join hf7_order_total ot on o.order_id=ot.order_id  WHERE  o.customer_id = ".$customer_id ." and ot.code = 'total' and o.paid!='Y' and o.order_status_id = 18   order by o.delivery_date asc";// || not in (0,6,7,8,15,16,9,10,11,12) available_balance>0
            // echo "<pre>";print_r($sqlSelect); 
            
            $order_data= $this->db->query($sqlSelect)->rows;
            // echo "<pre>";print_r($order_data); 
            return $order_data;
        }
        catch(exception $ex)
        {
            $log = new Log('error.log'); 
            $log->write('GetCustomerPendingOrders - error');
            $log->write($ex);
            return null;
        }       

    }


    public function GetUnallocatedFunds() {
        try{
            $log = new Log('error.log'); 
            $sqlSelect = 'select customer_fund_id,customer_id,amount,transaction_id,available_balance, amount_used from  ' . DB_PREFIX . "customer_unallocated_fund WHERE closed = 0 ";// || available_balance>0
            $fund_data= $this->db->query($sqlSelect)->rows;
            // echo "<pre>";print_r($fund_data); 
            return $fund_data;
        }
        catch(exception $ex)
        {
            $log = new Log('error.log'); 
            $log->write('GetUnallocatedFunds - error');
            $log->write($ex);
            return null;
        }       

    }

    public function confirmPaymentReceived($paid_order_id, $transaction_id, $amount_received = 0,$amount_partialy_paid=0,$paid_to = '',$grand_total=0,$partial_amount_applied=0) {
  

        $this->db->query('update `' . DB_PREFIX . 'order` SET paid="Y" , amount_partialy_paid = 0,paid_to="'.$paid_to.'" WHERE order_id="' . $paid_order_id . '"');
        //insert  payments history
        $sql = 'INSERT into ' . DB_PREFIX . "payment_history SET order_id = '" . $paid_order_id . "', transaction_id = '" . $transaction_id . "', partial_amount = '" . $amount_partialy_paid . "',amount_received='".$amount_received."',grand_total='".$grand_total."', added_by = '" . $this->user->getId() . "',ip='".$this->db->escape($this->request->server['REMOTE_ADDR'])."',patial_amount_applied='".$partial_amount_applied."'" ;
        $query = $this->db->query($sql);

        $sql = 'INSERT into ' . DB_PREFIX . "order_transaction_id SET order_id = '" . $paid_order_id . "', transaction_id = '" . $transaction_id . "'";

        $query = $this->db->query($sql);

    }


    public function confirmPaymentReceived_credit($customer_id, $transaction_id, $amount_received = 0,$amount_partialy_paid=0,$paid_to = '',$grand_total=0,$partial_amount_applied=0,$credit_id=0) {
        //insert  payments history
      $sql = 'INSERT into ' . DB_PREFIX . "payment_history SET customer_id = '" . $customer_id . "', transaction_id = '" . $transaction_id . "', partial_amount = '" . $amount_partialy_paid . "',amount_received='".$amount_received."',grand_total='".$grand_total."', added_by = '" . $this->user->getId() . "',ip='".$this->db->escape($this->request->server['REMOTE_ADDR'])."',credit_id='".$credit_id."',patial_amount_applied='".$partial_amount_applied."'" ;

      $query = $this->db->query($sql);
    }
    
    public function confirmPartialPaymentReceived($paid_order_id, $transaction_id='', $amount_received = '',$amount_partialy_paid=0,$paid_to='',$grand_total=0,$partial_amount_applied=0) {

      // $this->db->query('update `' . DB_PREFIX . 'order` SET amount_partialy_paid='" .  $amount_partialy_paid . "'  WHERE order_id="' . $paid_order_id . '"');
      $sql = 'UPDATE ' . DB_PREFIX . "order SET amount_partialy_paid = '" . $amount_partialy_paid . "', paid = 'P',paid_to='".$paid_to."' WHERE order_id = '" . (int) $paid_order_id . "'";

      $query = $this->db->query($sql);
       // $sql = 'DELETE FROM ' . DB_PREFIX . "order_transaction_id WHERE order_id = '" . (int) $paid_order_id . "'";

       // $query = $this->db->query($sql);

       //insert  payments history
         //  $sql = 'INSERT into ' . DB_PREFIX . "payment_history SET order_id = '" . $paid_order_id . "', transaction_id = '" . $transaction_id . "', partial_amount = '" . $amount_partialy_paid . "'";
       $sql = 'INSERT into ' . DB_PREFIX . "payment_history SET order_id = '" . $paid_order_id . "', transaction_id = '" . $transaction_id . "', partial_amount = '" . $amount_partialy_paid . "',amount_received='".$amount_received."',grand_total='".$grand_total."', added_by = '" . $this->user->getId() . "',ip='".$this->db->escape($this->request->server['REMOTE_ADDR'])."',patial_amount_applied='".$partial_amount_applied."'" ;

        // echo $sql;die;
     
         $query = $this->db->query($sql);


        $sql = 'INSERT into ' . DB_PREFIX . "order_transaction_id SET order_id = '" . $paid_order_id . "', transaction_id = '" . $transaction_id . "'";

        $query = $this->db->query($sql);

   }


   public function updateFundAndTotals($customer_fund_id, $closed, $available_balance,$amount_used,$amount_used_single,$customer_id) {
    $this->db->query('update `' . DB_PREFIX . 'customer_unallocated_fund` SET available_balance="'.$available_balance.'" , amount_used = "'.$amount_used.'",closed="'.$closed.'" WHERE customer_fund_id="' . $customer_fund_id . '"');
    $sqlSelect = 'select amount from  ' . DB_PREFIX . 'customer_unallocated_fund_totals WHERE customer_id = "' . $customer_id . '"';// || available_balance>0
    $total= $this->db->query($sqlSelect)->row['amount']??0;
    $updated_total=$total-$amount_used_single;

    $this->db->query('update `' . DB_PREFIX . 'customer_unallocated_fund_totals` SET amount="'.$updated_total.'" WHERE customer_id="' . $customer_id . '"');
     
  }

}
