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

}
