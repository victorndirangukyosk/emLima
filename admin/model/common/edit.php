<?php

class ModelCommonEdit extends Model
{
    public function changeStatus($type, $ids, $status, $extension = false)
    {

            // echo "<pre>";print_r($extension);
            // echo "<pre>";print_r($type);die;

        if ($extension) {
            foreach ($ids as $id) {
                $this->db->query('UPDATE '.DB_PREFIX."setting SET `value` = {$status} WHERE `code` = '{$id}' AND `key` = '{$id}_status'", 'query');
            }
        } else {
            foreach ($ids as $id) {
                $this->db->query('UPDATE '.DB_PREFIX."{$type} SET status = {$status} WHERE {$type}_id = {$id}", 'query');
            
             // Add to activity log
         if($type=='product')
         {

            $log = new Log('error.log');
            $this->load->model('user/user_activity');

            $activity_data = [
                'user_id' => $this->user->getId(),
                'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
                'user_group_id' => $this->user->getGroupId(),
                'product_id' => $id,
            ];
                 //  $log->write('product status modified');

            if($status==0)
            {
            $this->model_user_user_activity->addActivity('product_disabled', $activity_data);
            }
            else{
                $this->model_user_user_activity->addActivity('product_enabled', $activity_data);

            }
            //  $log->write('product status modified');
         }
         else if($type=='vendor_product')
         {

            $log = new Log('error.log');
            $this->load->model('user/user_activity');

            $activity_data = [
                'user_id' => $this->user->getId(),
                'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
                'user_group_id' => $this->user->getGroupId(),
                'product_store_id' => $id,
            ];
                 //  $log->write('product status modified');

            if($status==0)
            {
            $this->model_user_user_activity->addActivity('vendor_product_disabled', $activity_data);
            }
            else{
                $this->model_user_user_activity->addActivity('vendor_product_enabled', $activity_data);

            }
            //  $log->write('product status modified');
         }
            }
        }
    }

    public function mychangeStatus($type, $ids, $status, $extension = false)
    {

        if ($extension) {
            foreach ($ids as $id) {
                $this->db->query('UPDATE '.DB_PREFIX."setting SET `value` = {$status} WHERE `code` = '{$id}' AND `key` = '{$id}_status'", 'query');
            }
        } else {
            foreach ($ids as $id) {
                $this->db->query('UPDATE '.DB_PREFIX."product_to_store SET status = {$status} WHERE product_store_id = {$id}", 'query');
            
                if($type=='product_to_store')
                {
       
                   $log = new Log('error.log');
                   $this->load->model('user/user_activity');
       
                   $activity_data = [
                       'user_id' => $this->user->getId(),
                       'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
                       'user_group_id' => $this->user->getGroupId(),
                       'product_store_id' => $id,
                   ];
                        //  $log->write('product status modified');
       
                   if($status==0)
                   {
                   $this->model_user_user_activity->addActivity('vendor_product_disabled', $activity_data);
                   }
                   else{
                       $this->model_user_user_activity->addActivity('vendor_product_enabled', $activity_data);
       
                   }
                   //  $log->write('product status modified');
                }
            }
        }
    }
}
