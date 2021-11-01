<?php

class ModelAccountCredit extends Model
{
    public function getCredits($data = [])
    {
        $sql = 'SELECT * FROM `'.DB_PREFIX."customer_credit` WHERE customer_id = '".(int) $this->customer->getId()."'";

        $sort_data = [
            'amount',
            'description',
            'date_added',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY '.$data['sort'];
        } else {
            $sql .= ' ORDER BY date_added';
        }

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

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalCredits()
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `'.DB_PREFIX."customer_credit` WHERE customer_id = '".(int) $this->customer->getId()."'");

        return $query->row['total'];
    }

    public function getTotalAmount()
    {


        $query = $this->db->query('SELECT SUM(amount) AS total FROM `'.DB_PREFIX."customer_credit` WHERE customer_id = '".(int) $this->customer->getId()."' GROUP BY customer_id");
        // echo "<pre>";print_r('SELECT SUM(amount) AS total FROM `'.DB_PREFIX."customer_credit` WHERE customer_id = '".(int) $this->customer->getId()."' GROUP BY customer_id");die;

        if ($query->num_rows) {
        //   echo "<pre>";print_r($query->row['total']);die;

            return $query->row['total'];
        } else {
            return 0;
        }
    }
}
