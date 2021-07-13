<?php

class ModelSaleCustomerIssue extends Model
{  

    public function getCustomerIssue($customer_issue_id)
    {
        $query = $this->db->query('SELECT * FROM `'.DB_PREFIX."issue` WHERE issue_id = '".(int) $customer_issue_id."'");

        return $query->row;
    }

    public function getCustomerIssues($data = [])
    {
        $sql = "SELECT CONCAT(c.firstname, ' ', c.lastname) AS name,issue_id,issue_type,issue_details,order_id, company_name FROM ".DB_PREFIX.'issue f join '.DB_PREFIX."customer c on c.customer_id= f.customer_id";
    
        $sql .= ' ORDER BY `issue_id`';

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

    public function getTotalCustomerIssues($data = [])
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `'.DB_PREFIX.'issue`');

        return $query->row['total'];
    }
}
