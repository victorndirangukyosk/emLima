<?php

class ModelAccountFeedback extends Model
{
    public function saveFeedback($data)
    {
        //need to check the insertion of feedback form
         $this->db->query('INSERT INTO '.DB_PREFIX."feedback SET customer_id = '".(int) $this->customer->getId()."', comments = '".$this->db->escape($data['comments'])."', rating = '".$this->db->escape($data['rating'])."', feedback_type = '".$this->db->escape($data['feedback_type'])."', created_date = '" . $this->db->escape(date('Y-m-d H:i:s')) . "'");

        $feedback_id = $this->db->getLastId();       

        return $feedback_id;
    }

    
 

    public function getFeedback($feedback_id)
    {
        $feedback_query = $this->db->query('SELECT DISTINCT * FROM '.DB_PREFIX."feedback WHERE feedback_id = '".(int) $feedback_id."' ");

        if ($feedback_query->num_rows) {
          

            $feedback_data = [
                'feedback_id' => $address_query->row['feedback_id'],
                'rating' => $address_query->row['rating'],
                'feedback_type' => $address_query->row['feedback_type'],
                'comments' => $address_query->row['comments'],
                'customer_id' => $address_query->row['customer_id'],
                'order_id' => $address_query->row['order_id'],
                'created_date' => $address_query->row['created_date'], 
            ];

            return $feedback_data;
        } else {
            return false;
        }
    }

    public function getFeedbacks()
    {
        $feedback_data = [];

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."feedback");

        foreach ($query->rows as $result) {
            
 

            $feedback_data[$result['feedback_id']] = [
                'feedback_id' => $result['feedback_id'],
                'rating' => $address_query->row['rating'],
                'feedback_type' => $address_query->row['feedback_type'],
                'comments' => $address_query->row['comments'],
                'customer_id' => $address_query->row['customer_id'],
                'order_id' => $address_query->row['order_id'],
                'created_date' => $address_query->row['created_date'], 
            ];
        }

        return $feedback_data;
    }
  
  
}
