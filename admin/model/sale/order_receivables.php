<?php

class ModelSaleOrderReceivables extends Model
{
    public function getOrderReceivables($data = [])
    {
        $sql = "SELECT o.order_id, c.firstname,c.lastname,CONCAT(c.firstname, ' ', c.lastname) as customer, o.total,o.date_added  FROM `".DB_PREFIX.'order` o inner join '.DB_PREFIX.'customer c on(c.customer_id = o.customer_id)';

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id LIKE '".$data['filter_order_id']."%'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(c.firstname, ' ', c.lastname)  LIKE '%".$this->db->escape($data['filter_customer'])."%'";
        }
        

        // if (!empty($data['filter_date_added'])) {
        //     $sql .= " AND DATE(o.date_added) = DATE('".$this->db->escape($data['filter_date_added'])."')";
        // }

        // if (!empty($data['filter_date_modified'])) {
        //     $sql .= " AND DATE(o.date_modified) = DATE('".$this->db->escape($data['filter_date_modified'])."')";
        // }

        // if (!empty($data['filter_total'])) {
        //     $sql .= " AND o.total = '".(float) $data['filter_total']."'";
        // }

        $sort_data = [
            'o.order_id',
            'customer',
            // 'status',
            // 'o.date_added',
            // 'o.date_modified',
            // 'o.total',
            // 'c.name',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY '.$data['sort'];
        } else {
            $sql .= ' ORDER BY o.transaction_details_id';
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
        // echo $this->db->last_query();die;
        return $query->rows;
    }

  
    public function getTotalOrderReceivablesAndGrandTotal($data = [])
    {
        $sql = 'SELECT COUNT(*) as total,sum(ot.value) as GrandTotal FROM `'.DB_PREFIX.'order` o inner join '.DB_PREFIX.'customer c on(c.customer_id = o.customer_id) inner join '.DB_PREFIX.'order_total ot on(o.order_id =ot.order_id) and ot.code="total" ';

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id LIKE '".$data['filter_order_id']."%'";
        }
        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(c.firstname, ' ', c.lastname)  LIKE '%".$this->db->escape($data['filter_customer'])."%'";
        }
        // if (!empty($data['filter_customer'])) {
        //     $sql .= " AND c.firstname LIKE '%".$this->db->escape($data['filter_customer'])."%'";
        // }
        // if (!empty($data['filter_customer'])) {
        //     $sql .= " AND c.lastname LIKE '%".$this->db->escape($data['filter_customer'])."%'";
        // }
        // if (!empty($data['filter_date_added'])) {
        //     $sql .= " AND DATE(o.date_added) = DATE('".$this->db->escape($data['filter_date_added'])."')";
        // }
        // if (!empty($data['filter_total'])) {
        //     $sql .= " AND o.total = '".(float) $data['filter_total']."'";
        // }

        $query = $this->db->query($sql);

        // return $query->row['total'];
        return $query->row;
    }
}
