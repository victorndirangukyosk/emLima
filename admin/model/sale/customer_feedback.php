<?php

class ModelSaleCustomerFeedback extends Model
{  

    public function getCustomerFeedback($customer_feedback_id)
    {
        $query = $this->db->query('SELECT * FROM `'.DB_PREFIX."feedback` WHERE feedback_id = '".(int) $customer_feedback_id."'");

        return $query->row;
    }

    public function getCustomerFeedbacks($data = [])
    {
        $sql = "SELECT CONCAT(c.firstname, ' ', c.lastname) AS name,feedback_id,rating,feedback_type,comments,order_id, company_name,issue_type,date(created_date) as created_date,f.status,accepted_by,closed_date,closed_comments FROM ".DB_PREFIX.'feedback f join '.DB_PREFIX."customer c on c.customer_id= f.customer_id";
    
        $sql .= ' ORDER BY `feedback_id`';

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT '.(int) $data['start'].','.(int) $data['limit'];
        }
        // echo $sql;die;
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalCustomerFeedbacks($data = [])
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `'.DB_PREFIX.'feedback`');

        return $query->row['total'];
    }


    public function acceptIssue($feedback_id,$accepted_user_id) {
       
        
            $this->db->query('UPDATE ' . DB_PREFIX . "feedback SET status = 'Attending' , accepted_by= '" . (int) $accepted_user_id . "'  accepted_date= NOW() WHERE feedback_id = '" . (int) $feedback_id . "'");
                       
         
    }
}
