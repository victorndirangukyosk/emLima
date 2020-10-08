<?php

class ModelSettingJobPosition extends Model
{
    public function addJobPosition($data)
    {  
        
        $sql='INSERT INTO '.DB_PREFIX."jobposition SET `job_category` = '".$this->db->escape($data['job_category'])."', job_type = '".$this->db->escape($data['job_type'])."', job_location='".$data['job_location']."' , skills = '".$this->db->escape($data['skills'])."', experience = '".$this->db->escape($data['experience'])."', roles_responsibilities = '".$this->db->escape($data['roles_responsibilities'])."', otherinfo_1 = '".$this->db->escape($data['otherinfo_1'])."', otherinfo_2 = '".$this->db->escape($data['otherinfo_2'])."', date_added = '".$data['date_added']."', status='".$data['status']."', sort_order='".$data['sort_order']."'";
       
         //echo "<pre>";print_r($sql);die;
       
       $this->db->query('INSERT INTO '.DB_PREFIX."jobposition SET `job_category` = '".$this->db->escape($data['job_category'])."', job_type = '".$this->db->escape($data['job_type'])."', job_location='".$data['job_location']."' , skills = '".$this->db->escape($data['skills'])."', experience = '".$this->db->escape($data['experience'])."', roles_responsibilities = '".$this->db->escape($data['roles_responsibilities'])."', otherinfo_1 = '".$this->db->escape($data['otherinfo_1'])."', otherinfo_2 = '".$this->db->escape($data['otherinfo_2'])."',date_added = '".$data['date_added']."', status='".$data['status']."', sort_order='".$data['sort_order']."'");
    }

    public function editJobPosition($job_id, $data)
    {

          
    //     $sql= 'UPDATE '.DB_PREFIX."jobposition SET `job_category` = '".$this->db->escape($data['job_category'])."', job_type = '".$this->db->escape($data['job_type'])."', job_location='".$data['job_location']."' , skills = '".$this->db->escape($data['skills'])."', experience = '".$this->db->escape($data['experience'])."', roles_responsibilities = '".$this->db->escape($data['roles_responsibilities'])."', otherinfo_1 = '".$this->db->escape($data['otherinfo_1'])."', otherinfo_2 = '".$this->db->escape($data['otherinfo_2'])."', date_added = '".$data['date_added']."', status='".$data['status']."', sort_order='".$data['sort_order']."' WHERE job_id='".$job_id."'";
       
    //    echo "<pre>";print_r($sql);die;
        $this->db->query('UPDATE '.DB_PREFIX."jobposition SET `job_category` = '".$this->db->escape($data['job_category'])."', job_type = '".$this->db->escape($data['job_type'])."', job_location='".$data['job_location']."' , skills = '".$this->db->escape($data['skills'])."', experience = '".$this->db->escape($data['experience'])."', roles_responsibilities = '".$this->db->escape($data['roles_responsibilities'])."', otherinfo_1 = '".$this->db->escape($data['otherinfo_1'])."', otherinfo_2 = '".$this->db->escape($data['otherinfo_2'])."', date_added = '".$data['date_added']."', status='".$data['status']."', sort_order='".$data['sort_order']."' WHERE job_id='".$job_id."'");
    }

    public function deleteJobPosition($job_id)
    {
        $this->db->query('DELETE FROM '.DB_PREFIX."jobposition WHERE job_id = '".(int) $job_id."'");
    }

    public function getJobPosition($job_id)
    {
        $query = $this->db->query('SELECT DISTINCT * FROM '.DB_PREFIX."jobposition WHERE job_id = '".(int) $job_id."'");
        // echo "<pre>";print_r($query->row);die;
        return $query->row;
    }

    public function getJobPositions()
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX.'jobposition ORDER BY job_id');
        //echo "<pre>";print_r($query->rows);die;
        return  $query->rows;
    }

    public function getTotalJobPositions()
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX.'jobposition');

        return $query->row['total'];
    }
}
