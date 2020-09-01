<?php

class ModelAffiliateCommission extends Model
{
    public function getCommissions($data = [])
    {
        $sql = 'SELECT * FROM `'.DB_PREFIX."affiliate_commission` WHERE affiliate_id = '".(int) $this->affiliate->getId()."'";

        $sort_data = [
            'amount',
            'description',
            'date_added'
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

    public function getTotalCommissions()
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `'.DB_PREFIX."affiliate_commission` WHERE affiliate_id = '".(int) $this->affiliate->getId()."'");

        return $query->row['total'];
    }

    public function getBalance()
    {
        $query = $this->db->query('SELECT SUM(amount) AS total FROM `'.DB_PREFIX."affiliate_commission` WHERE affiliate_id = '".(int) $this->affiliate->getId()."' GROUP BY affiliate_id");

        if ($query->num_rows) {
            return $query->row['total'];
        } else {
            return 0;
        }
    }
}
