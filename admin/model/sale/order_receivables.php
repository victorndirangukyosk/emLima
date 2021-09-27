<?php

class ModelSaleOrderReceivables extends Model
{
    public function getOrderReceivables($data = [])
    {
        $sql = "SELECT o.order_id, c.customer_id,c.firstname,c.lastname,CONCAT(c.firstname, ' ', c.lastname) as customer,c.company_name as company, o.total,o.date_added ,ot.transaction_id ,o.paid,o.amount_partialy_paid FROM `".DB_PREFIX.'order` o inner join '.DB_PREFIX.'customer c on(c.customer_id = o.customer_id) left outer join   '.DB_PREFIX.'order_transaction_id ot on ot.order_id = o.order_id';

        $sql .= " Where (o.paid = 'P' or o.paid = 'N')  and  ot.transaction_id  is null ";

        $sql .= " and o.order_status_id not in (0,6,7,8,15,16,9,10,11,12) ";


        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id LIKE '".$data['filter_order_id']."%'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(c.firstname, ' ', c.lastname)  LIKE '%".$this->db->escape($data['filter_customer'])."%'";
        }

        if (!empty($data['filter_company'])) {
            $sql .= " AND c.company_name   LIKE '%".$this->db->escape($data['filter_company'])."%'";
        }
        

        if (!empty($data['filter_date_added']) && empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) BETWEEN DATE('" . $this->db->escape($data['filter_date_added']) . "') AND DATE('" . $this->db->escape($data['filter_date_added_end']) . "')";
        }

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
        //  echo $this->db->last_query();die;
        return $query->rows;
    }

  
    public function getTotalOrderReceivablesAndGrandTotal($data = [])
    {
        $sql = 'SELECT COUNT(*) as total,sum(ort.value) as GrandTotal FROM `'.DB_PREFIX.'order` o inner join '.DB_PREFIX.'customer c on(c.customer_id = o.customer_id) left outer join '.DB_PREFIX.'order_total ort on(o.order_id =ort.order_id) and ort.code="total" left outer join   '.DB_PREFIX.'order_transaction_id ot on ot.order_id = o.order_id';
        $sql .= " Where (o.paid = 'P' or o.paid = 'N')   and  ot.transaction_id  is null ";
        $sql .= " and o.order_status_id not in (0,6,7,8,15,16,9,10,11,12) ";

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id LIKE '".$data['filter_order_id']."%'";
        }
        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(c.firstname, ' ', c.lastname)  LIKE '%".$this->db->escape($data['filter_customer'])."%'";
        }

        if (!empty($data['filter_company'])) {
            $sql .= " AND c.company_name  LIKE '%".$this->db->escape($data['filter_company'])."%'";
        }
        // if (!empty($data['filter_customer'])) {
        //     $sql .= " AND c.firstname LIKE '%".$this->db->escape($data['filter_customer'])."%'";
        // }
        // if (!empty($data['filter_customer'])) {
        //     $sql .= " AND c.lastname LIKE '%".$this->db->escape($data['filter_customer'])."%'";
        // }
        if (!empty($data['filter_date_added']) && empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) BETWEEN DATE('" . $this->db->escape($data['filter_date_added']) . "') AND DATE('" . $this->db->escape($data['filter_date_added_end']) . "')";
        }

        // if (!empty($data['filter_total'])) {
        //     $sql .= " AND o.total = '".(float) $data['filter_total']."'";
        // }

        $query = $this->db->query($sql);
        //    echo $sql;die;


        // return $query->row['total'];
        return $query->row;
    }


    public function confirmPaymentReceived($paid_order_id, $transaction_id, $amount_received = 0) {
  
            $this->db->query('update `' . DB_PREFIX . 'order` SET paid="Y" , amount_partialy_paid = 0 WHERE order_id="' . $paid_order_id . '"');
            // echo 'update `' . DB_PREFIX . 'order` SET paid="Y" , amount_partialy_paid = amount_partialy_paid+"'.$amount_received.'" WHERE order_id="' . $paid_order_id . '"';die;
            
            $sql = 'DELETE FROM ' . DB_PREFIX . "order_transaction_id WHERE order_id = '" . (int) $paid_order_id . "'";

            $query = $this->db->query($sql);
    
            $sql = 'INSERT into ' . DB_PREFIX . "order_transaction_id SET order_id = '" . $paid_order_id . "', transaction_id = '" . $transaction_id . "'";
    
            $query = $this->db->query($sql);

    }


    public function confirmPartialPaymentReceived($paid_order_id, $transaction_id='', $amount_received = '',$amount_partialy_paid=0) {
  
        // $this->db->query('update `' . DB_PREFIX . 'order` SET amount_partialy_paid='" .  $amount_partialy_paid . "'  WHERE order_id="' . $paid_order_id . '"');
        

        $sql = 'UPDATE ' . DB_PREFIX . "order SET amount_partialy_paid = '" . $amount_partialy_paid . "', paid = 'P' WHERE order_id = '" . (int) $paid_order_id . "'";

        $query = $this->db->query($sql);
        // $sql = 'DELETE FROM ' . DB_PREFIX . "order_transaction_id WHERE order_id = '" . (int) $paid_order_id . "'";

        // $query = $this->db->query($sql);

        // $sql = 'INSERT into ' . DB_PREFIX . "order_transaction_id SET order_id = '" . $paid_order_id . "', transaction_id = '" . $transaction_id . "'";

        // $query = $this->db->query($sql);

}
public function getOrderTotal($order_id)
{
    $sql = 'SELECT ort.value as order_total,o.amount_partialy_paid FROM `'.DB_PREFIX.'order` o left outer join '.DB_PREFIX.'order_total ort on(o.order_id =ort.order_id) and ort.code="total" ';
    $sql .= " Where o.order_id = '".$order_id."'";
    $query = $this->db->query($sql);
        // echo $sql;die;

        // $req_amount=$query->row['order_total']-$query->row['amount_partialy_paid'];
      return $query->row;
    // return $query->row;
}



public function getSuccessfulOrderReceivables($data = [])
{
    $sql = "SELECT o.order_id, c.customer_id,c.firstname,c.lastname,CONCAT(c.firstname, ' ', c.lastname) as customer,c.company_name as company, o.total,o.date_added ,ot.transaction_id ,o.paid,o.amount_partialy_paid FROM `".DB_PREFIX.'order` o inner join '.DB_PREFIX.'customer c on(c.customer_id = o.customer_id) left outer join   '.DB_PREFIX.'order_transaction_id ot on ot.order_id = o.order_id';

    $sql .= " Where (o.paid = 'Y' || o.paid = 'P')   ";//and  ot.transaction_id  is null

    // $sql .= " and o.order_status_id not in (0,6,7,8,15,16,9,10,11,12) ";


    if (!empty($data['filter_order_id'])) {
        $sql .= " AND o.order_id LIKE '".$data['filter_order_id']."%'";
    }

    if (!empty($data['filter_customer'])) {
        $sql .= " AND CONCAT(c.firstname, ' ', c.lastname)  LIKE '%".$this->db->escape($data['filter_customer'])."%'";
    }

    if (!empty($data['filter_company'])) {
        $sql .= " AND c.company_name   LIKE '%".$this->db->escape($data['filter_company'])."%'";
    }
    

    if (!empty($data['filter_date_added']) && empty($data['filter_date_added_end'])) {
        $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
    }

    if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_end'])) {
        $sql .= " AND DATE(o.date_added) BETWEEN DATE('" . $this->db->escape($data['filter_date_added']) . "') AND DATE('" . $this->db->escape($data['filter_date_added_end']) . "')";
    }

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
    //  echo $this->db->last_query();die;
    return $query->rows;
}


public function getTotalSuccessfulOrderReceivablesAndGrandTotal($data = [])
{
    $sql = 'SELECT COUNT(*) as total,sum(ort.value) as GrandTotal FROM `'.DB_PREFIX.'order` o inner join '.DB_PREFIX.'customer c on(c.customer_id = o.customer_id) left outer join '.DB_PREFIX.'order_total ort on(o.order_id =ort.order_id) and ort.code="total" left outer join   '.DB_PREFIX.'order_transaction_id ot on ot.order_id = o.order_id';
    $sql .= " Where (o.paid = 'Y' || o.paid = 'P')    ";//and  ot.transaction_id  is not null
    // $sql .= " and o.order_status_id not in (0,6,7,8,15,16,9,10,11,12) ";

    if (!empty($data['filter_order_id'])) {
        $sql .= " AND o.order_id LIKE '".$data['filter_order_id']."%'";
    }
    if (!empty($data['filter_customer'])) {
        $sql .= " AND CONCAT(c.firstname, ' ', c.lastname)  LIKE '%".$this->db->escape($data['filter_customer'])."%'";
    }

    if (!empty($data['filter_company'])) {
        $sql .= " AND c.company_name  LIKE '%".$this->db->escape($data['filter_company'])."%'";
    }
    // if (!empty($data['filter_customer'])) {
    //     $sql .= " AND c.firstname LIKE '%".$this->db->escape($data['filter_customer'])."%'";
    // }
    // if (!empty($data['filter_customer'])) {
    //     $sql .= " AND c.lastname LIKE '%".$this->db->escape($data['filter_customer'])."%'";
    // }
    if (!empty($data['filter_date_added']) && empty($data['filter_date_added_end'])) {
        $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
    }

    if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_end'])) {
        $sql .= " AND DATE(o.date_added) BETWEEN DATE('" . $this->db->escape($data['filter_date_added']) . "') AND DATE('" . $this->db->escape($data['filter_date_added_end']) . "')";
    }

    // if (!empty($data['filter_total'])) {
    //     $sql .= " AND o.total = '".(float) $data['filter_total']."'";
    // }

    $query = $this->db->query($sql);
    //    echo $sql;die;


    // return $query->row['total'];
    return $query->row;
}


    public function reversePaymentReceived($paid_order_id, $amount_received = '') {
    
        $this->db->query('update `' . DB_PREFIX . 'order` SET paid="N", amount_partialy_paid = 0 WHERE order_id="' . $paid_order_id . '"');
    
        
        $sql = 'DELETE FROM ' . DB_PREFIX . "order_transaction_id WHERE order_id = '" . (int) $paid_order_id . "'";

        $query = $this->db->query($sql);
 

    }

 
}
