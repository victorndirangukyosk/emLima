<?php

class ModelSchedulerDbupdates extends Model {


    public function UpdateOrderProcessing($delivery_date) {
        try{
        // update `hf7_order` set order_status_id=1 WHERE `order_status_id`=14 and delivery_date ='2021-02-19';

        // UPDATE `hf7_order` SET order_processing_group_id = 1, order_processor_id = 1 WHERE order_status_id = 1 and delivery_date ='2021-02-19';

        $sql = 'UPDATE  ' . DB_PREFIX . "order SET  order_status_id = '1', order_processing_group_id = 1, order_processor_id = 1 WHERE order_status_id = 14 and delivery_date='".$delivery_date."'";


        // echo "<pre>";print_r($sql);die;

        $result= $this->db->query($sql);

        if (!$result) {
            //   die('Invalid query: ' . mysql_error());
            $result=0;
        }
        else
        $result=1;
        }

        catch(exception $ex)
        {
            $result=-1;
        }
       
        return $result;

    }

}
