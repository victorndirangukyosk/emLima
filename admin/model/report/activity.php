<?php

class ModelReportActivity extends Model
{
    public function getActivities()
    {
        $sql = "SELECT CONCAT('customer_',ca.key) AS `key`,ca.data,ca.date_added FROM  `".DB_PREFIX.'customer_activity` ca ORDER BY  ca.date_added DESC LIMIT 0, 5';
        //echo $sql;die;
        $query = $this->db->query($sql);

        return $query->rows;
    }
}
