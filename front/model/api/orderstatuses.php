<?php

class ModelApiOrderStatuses extends Model
{
    
       

    public function getOrderStatuses($data = [])
    {
        $sql = "SELECT order_status_id,name FROM ".DB_PREFIX.'order_status os';

        // if (!empty($data['filter_name'])) {
        //     $sql .= " AND cd2.name LIKE '".$this->db->escape($data['filter_name'])."%'";
        // }

        
        $sql .= ' GROUP BY os.order_status_id';

        $sort_data = [
            'name',
             
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY '.$data['sort'];
        } else {
            $sql .= ' ORDER BY sort_order';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        // if (isset($data['start']) || isset($data['limit'])) {
        //     if ($data['start'] < 0) {
        //         $data['start'] = 0;
        //     }

        //     if ($data['limit'] < 1) {
        //         $data['limit'] = 20;
        //     }

        //     $sql .= ' LIMIT '.(int) $data['start'].','.(int) $data['limit'];
        // }

        $query = $this->db->query($sql);

        return $query->rows;
    }
 
   
}
