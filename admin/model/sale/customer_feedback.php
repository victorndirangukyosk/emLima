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
        $logged_userid=$this->user->getId();
       
        $sql = "SELECT CONCAT(c.firstname, ' ', c.lastname) AS name,CONCAT(u.firstname, ' ', u.lastname) AS accepted_user, feedback_id,rating,feedback_type,comments,order_id, company_name,issue_type,date(created_date) as created_date,f.status,accepted_by,closed_date,closed_comments FROM ".DB_PREFIX.'feedback f join '.DB_PREFIX."customer c on c.customer_id= f.customer_id left outer join ".DB_PREFIX."user u on u.user_id= f.accepted_by ";
    
       


        $implode = [];

        if (!empty($data['filter_company'])) {
            if ('' != $data['filter_company']) {
                $implode[] = "c.company_name = '" . $this->db->escape($data['filter_company']) . "'";
            }
        }

        if (!empty($data['isaccountmanager'])) {
            if (true== $data['isaccountmanager']) {
                $implode[] = "c.account_manager_id = '" . $logged_userid . "'";
            }
        }

        if (!empty($data['filter_name'])) {
            if ($this->user->isVendor()) {
                $implode[] = "c.firstname LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
            } else {
                $implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
            }
        }

        if (!empty($data['filter_customer_rating_id'])) {
            $implode[] = "f.rating = '" . (int) $data['filter_customer_rating_id'] . "'";
        }
 

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "f.status = '" .  $data['filter_status'] . "'";
        }

        if (isset($data['filter_feedback_id']) && !is_null($data['filter_feedback_id'])) {
            $implode[] = "f.feedback_id = '" .  $data['filter_feedback_id'] . "'";
        }


        if ($implode) {
            $sql .= ' Where ' . implode(' AND ', $implode);
        }

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
        //  echo $sql;die;
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalCustomerFeedbacks($data = [])
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `'.DB_PREFIX.'feedback`');

        return $query->row['total'];
    }


    public function acceptIssue($feedback_id,$accepted_user_id) {
       
        //   echo '<pre>';print_r('UPDATE ' . DB_PREFIX . "feedback SET status = 'Attending' , accepted_by= '" . (int) $accepted_user_id . "' , accepted_date= NOW() WHERE feedback_id = '" . (int) $feedback_id . "'");exit;
        
            $this->db->query('UPDATE ' . DB_PREFIX . "feedback SET status = 'Attending' , accepted_by= '" . (int) $accepted_user_id . "' , accepted_date= NOW() WHERE feedback_id = '" . (int) $feedback_id . "'");
              
            
            $activity_data = [
                'user_id' => $this->user->getId(),
                'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
                'user_group_id' => $this->user->getGroupId(),
                'feedback_id' => $feedback_id,
            ];
            $this->load->model('user/user_activity');

            $this->model_user_user_activity->addActivity('Issue_Accepted', $activity_data);

         
    }


    public function closeIssue($feedback_id,$closing_comments,$accepted_user_id) {
       
        
            $this->db->query('UPDATE ' . DB_PREFIX . "feedback SET status = 'Closed' ,  closed_date= NOW(),closed_comments='" .   $closing_comments . "' WHERE feedback_id = '" . (int) $feedback_id . "'");
               
            $activity_data = [
                'user_id' => $this->user->getId(),
                'name' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
                'user_group_id' => $this->user->getGroupId(),
                'feedback_id' => $feedback_id,
            ];
             
            $this->load->model('user/user_activity');
            $this->model_user_user_activity->addActivity('Issue_Closed', $activity_data);
        
         
    }



    public function GetNonProcessedIssues($Issues_currentDateTime,$max_Issues_currentDateTime,$issue_status)
    {
 
        if($issue_status=='Open')
        $sql = "SELECT CONCAT(c.firstname, ' ', c.lastname) AS name,'' as AcceptedBy,c.email, c.telephone,feedback_id,rating,feedback_type,comments,order_id, company_name,issue_type,date(created_date) as created_date,f.status,accepted_by,closed_date,closed_comments FROM ".DB_PREFIX.'feedback f join '.DB_PREFIX."customer c on c.customer_id= f.customer_id where f.status ='Open' and DATE_ADD(f.created_date, INTERVAL 12 HOUR) >= '".$Issues_currentDateTime."' and DATE_ADD(f.created_date, INTERVAL 12 HOUR)< '".$max_Issues_currentDateTime."' ";
          else
          {
         $sql = "SELECT CONCAT(c.firstname, ' ', c.lastname) AS name,CONCAT(u.firstname, ' ', u.lastname) AS AcceptedBy,c.email,c.telephone,feedback_id,rating,feedback_type,comments,order_id, company_name,issue_type,date(created_date) as created_date,f.status,accepted_by,closed_date,closed_comments FROM ".DB_PREFIX.'feedback f join '.DB_PREFIX."customer c on c.customer_id= f.customer_id  join ".DB_PREFIX."user u on  u.user_id = f.accepted_by  where f.status ='Attending' and DATE_ADD(f.accepted_date, INTERVAL 24 HOUR) >= '".$Issues_currentDateTime."' and DATE_ADD(f.accepted_date, INTERVAL 24 HOUR)< '".$max_Issues_currentDateTime."' ";
            //   echo $sql;die;
          }
        $sql .= ' ORDER BY `feedback_id`';

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

       
            // echo $sql;die;
        $query = $this->db->query($sql);

        return $query->rows;
    }

}
