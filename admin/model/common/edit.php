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
            
         $product_details = $this->getProduct($id);
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
                'product_name' => $product_details['name']
            ];
                 //  $log->write('product status modified');

            if($status==0)
            {
            $this->model_user_user_activity->addActivity('new_vendor_product_disabled', $activity_data);
            }
            else{
                $this->model_user_user_activity->addActivity('new_vendor_product_enabled', $activity_data);

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
                $product_details = $this->getProduct($id);
                if($type=='product_to_store')
                {
       
                   $log = new Log('error.log');
                   $this->load->model('user/user_activity');
       
                   $activity_data = [
                       'user_id' => $this->user->getId(),
                       'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
                       'user_group_id' => $this->user->getGroupId(),
                       'product_store_id' => $id,
                       'product_name' => $product_details['name']
                   ];
                        //  $log->write('product status modified');
       
                   if($status==0)
                   {
                   $this->model_user_user_activity->addActivity('new_vendor_product_disabled', $activity_data);
                   }
                   else{
                       $this->model_user_user_activity->addActivity('new_vendor_product_enabled', $activity_data);
       
                   }
                   //  $log->write('product status modified');
                }
            }
        }
    }
    
        public function getProduct($product_store_id) {
        $query = $this->db->query('SELECT DISTINCT p.*,pd.name,v.user_id as vendor_id FROM ' . DB_PREFIX . 'product_to_store p LEFT JOIN ' . DB_PREFIX . 'product_description pd ON (p.product_id = pd.product_id) LEFT JOIN ' . DB_PREFIX . 'store st ON (st.store_id = p.store_id) LEFT JOIN ' . DB_PREFIX . "user v ON (v.user_id = st.vendor_id) WHERE p.product_store_id = '" . (int) $product_store_id . "' AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "'");

        $product = $query->row;

        return $product;
    }
}
