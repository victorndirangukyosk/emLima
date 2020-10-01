<?php

class ModelSettingNewfeature extends Model
{
    public function addNewfeature($data)
    {  
        
        $sql='INSERT INTO '.DB_PREFIX."newfeature SET `user_name` = '".$this->db->escape($data['name'])."', detail_description = '".$this->db->escape($data['detail_description'])."', summary='".$data['summary']."' , additional_requirement = '".$this->db->escape($data['additional_requirement'])."', File = '".$this->db->escape($data['File'])."', business_impact = '".$this->db->escape($data['business_impact'])."', is_customer_requirement = '".$this->db->escape($data['is_customer_requirement'])."', customer_name = '".$this->db->escape($data['customer_name'])."', customers_requested = '".$this->db->escape($data['no_of _customers_requested'])."', no_of_customers_onboarded='".$data['no_of_customers_onboarded']."'";
       
       //echo "<pre>";print_r($sql);die;
       
       $this->db->query('INSERT INTO '.DB_PREFIX."newfeature SET `user_name` = '".$this->db->escape($data['name'])."', detail_description = '".$this->db->escape($data['detail_description'])."', summary='".$data['summary']."' , additional_requirement = '".$this->db->escape($data['additional_requirement'])."', File = '".$this->db->escape($data['File'])."', business_impact = '".$this->db->escape($data['business_impact'])."', is_customer_requirement = '".$this->db->escape($data['is_customer_requirement'])."', customer_name = '".$this->db->escape($data['customer_name'])."',customers_requested = '".$data['no_of _customers_requested']."', no_of_customers_onboarded='".$data['no_of_customers_onboarded']."'");
    }

    public function editNewfeature($newfeature_id, $data)
    {

          
    //     $sql= 'UPDATE '.DB_PREFIX."newfeature SET `user_name` = '".$this->db->escape($data['name'])."', detail_description = '".$this->db->escape($data['detail_description'])."', summary='".$data['summary']."' , additional_requirement = '".$this->db->escape($data['additional_requirement'])."', File = '".$this->db->escape($data['File'])."', business_impact = '".$this->db->escape($data['business_impact'])."', is_customer_requirement = '".$this->db->escape($data['is_customer_requirement'])."', customer_name = '".$this->db->escape($data['customer_name'])."', customers_requested = '".$data['no_of_customers_requested']."', no_of_customers_onboarded='".$data['no_of_customers_onboarded']."' WHERE newfeature_id='".$newfeature_id."'";
       
    //    echo "<pre>";print_r($sql);die;
        $this->db->query('UPDATE '.DB_PREFIX."newfeature SET `user_name` = '".$this->db->escape($data['name'])."', detail_description = '".$this->db->escape($data['detail_description'])."', summary='".$data['summary']."' , additional_requirement = '".$this->db->escape($data['additional_requirement'])."', File = '".$this->db->escape($data['File'])."', business_impact = '".$this->db->escape($data['business_impact'])."', is_customer_requirement = '".$this->db->escape($data['is_customer_requirement'])."', customer_name = '".$this->db->escape($data['customer_name'])."', customers_requested = '".$data['no_of_customers_requested']."', no_of_customers_onboarded='".$data['no_of_customers_onboarded']."' WHERE newfeature_id='".$newfeature_id."'");
    }

    public function deleteTestimonial($newfeature_id)
    {
        $this->db->query('DELETE FROM '.DB_PREFIX."newfeature WHERE newfeature_id = '".(int) $newfeature_id."'");
    }

    public function getNewfeature($newfeature_id)
    {
        $query = $this->db->query('SELECT DISTINCT * FROM '.DB_PREFIX."newfeature WHERE newfeature_id = '".(int) $newfeature_id."'");
        // echo "<pre>";print_r($query->row);die;
        return $query->row;
    }

    public function getNewfeatures()
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX.'newfeature ORDER BY newfeature_id');
        //echo "<pre>";print_r($query->rows);die;
        return  $query->rows;
    }

    public function getTotalNewfeatures()
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX.'newfeature');

        return $query->row['total'];
    }
}
