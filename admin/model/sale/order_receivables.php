<?php

class ModelSaleOrderReceivables extends Model
{
    public function getOrderReceivables($data = [])
    {
        $sql = "SELECT o.order_id, c.customer_id,c.firstname,c.lastname,CONCAT(c.firstname, ' ', c.lastname) as customer,c.company_name as company, o.total,o.date_added ,o.paid,o.amount_partialy_paid,c.payment_terms FROM `".DB_PREFIX.'order` o inner join '.DB_PREFIX.'customer c on(c.customer_id = o.customer_id) ';

        $sql .= " Where (o.paid = 'P' or o.paid = 'N')  ";

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
        
        if (isset($data['filter_customer_group']) && !empty($data['filter_customer_group'])) {
            $sql .= ' AND c.customer_group_id="' . $data['filter_customer_group'] . '"';
        }

        if (!empty($data['filter_date_added']) && empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) BETWEEN DATE('" . $this->db->escape($data['filter_date_added']) . "') AND DATE('" . $this->db->escape($data['filter_date_added_end']) . "')";
        }


        if (!empty($data['filter_payment_terms'])) {
            $sql .= " AND c.payment_terms LIKE '%".$data['filter_payment_terms']."%'";
        }

        if (!empty($data['filter_payment'])) {
            $sql .= " AND o.payment_method LIKE '%" . $data['filter_payment'] . "%'";
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
        $sql = 'SELECT COUNT(*) as total,sum(ort.value) as GrandTotal,sum(o.amount_partialy_paid) as GrandPartialyPaid FROM `'.DB_PREFIX.'order` o inner join '.DB_PREFIX.'customer c on(c.customer_id = o.customer_id) left outer join '.DB_PREFIX.'order_total ort on(o.order_id =ort.order_id) and ort.code="total" ';
        $sql .= " Where (o.paid = 'P' or o.paid = 'N') "; 
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

        if (isset($data['filter_customer_group']) && !empty($data['filter_customer_group'])) {
            $sql .= ' AND c.customer_group_id="' . $data['filter_customer_group'] . '"';
        }

        if (!empty($data['filter_payment'])) {
            $sql .= " AND o.payment_method LIKE '%" . $data['filter_payment'] . "%'";
        }

        if (!empty($data['filter_date_added']) && empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(o.date_added) BETWEEN DATE('" . $this->db->escape($data['filter_date_added']) . "') AND DATE('" . $this->db->escape($data['filter_date_added_end']) . "')";
        }

        if (!empty($data['filter_payment_terms'])) {
            $sql .= " AND c.payment_terms LIKE '%".$data['filter_payment_terms']."%'";
        }

        // if (!empty($data['filter_total'])) {
        //     $sql .= " AND o.total = '".(float) $data['filter_total']."'";
        // }

        $query = $this->db->query($sql);
        //    echo $sql;die;


        // return $query->row['total'];
        return $query->row;
    }


    public function confirmPaymentReceived($paid_order_id, $transaction_id, $amount_received = 0,$amount_partialy_paid=0,$paid_to = '',$grand_total=0,$partial_amount_applied=0) {
  

            $this->db->query('update `' . DB_PREFIX . 'order` SET paid="Y" , amount_partialy_paid = 0,paid_to="'.$paid_to.'" WHERE order_id="' . $paid_order_id . '"');
            // echo 'update `' . DB_PREFIX . 'order` SET paid="Y" , amount_partialy_paid = 0,paid_to="'.$paid_to.'" WHERE order_id="' . $paid_order_id . '"';die;
            
            // $sql = 'DELETE FROM ' . DB_PREFIX . "order_transaction_id WHERE order_id = '" . (int) $paid_order_id . "'";

            // $query = $this->db->query($sql);
    
             //insert  payments history
            $sql = 'INSERT into ' . DB_PREFIX . "payment_history SET order_id = '" . $paid_order_id . "', transaction_id = '" . $transaction_id . "', partial_amount = '" . $amount_partialy_paid . "',amount_received='".$amount_received."',grand_total='".$grand_total."', added_by = '" . $this->user->getId() . "',ip='".$this->db->escape($this->request->server['REMOTE_ADDR'])."',patial_amount_applied='".$partial_amount_applied."'" ;

            $query = $this->db->query($sql);

    
            $sql = 'INSERT into ' . DB_PREFIX . "order_transaction_id SET order_id = '" . $paid_order_id . "', transaction_id = '" . $transaction_id . "'";
    
            $query = $this->db->query($sql);

    }


    public function confirmPaymentReceived_credit($customer_id, $transaction_id, $amount_received = 0,$amount_partialy_paid=0,$paid_to = '',$grand_total=0,$partial_amount_applied=0,$credit_id=0) {
       

         //insert  payments history
        $sql = 'INSERT into ' . DB_PREFIX . "payment_history SET customer_id = '" . $customer_id . "', transaction_id = '" . $transaction_id . "', partial_amount = '" . $amount_partialy_paid . "',amount_received='".$amount_received."',grand_total='".$grand_total."', added_by = '" . $this->user->getId() . "',ip='".$this->db->escape($this->request->server['REMOTE_ADDR'])."',credit_id='".$credit_id."',patial_amount_applied='".$partial_amount_applied."'" ;

        $query = $this->db->query($sql);

 

}
    public function confirmPartialPaymentReceived($paid_order_id, $transaction_id='', $amount_received = '',$amount_partialy_paid=0,$paid_to='',$grand_total=0,$partial_amount_applied=0) {
  
        // $this->db->query('update `' . DB_PREFIX . 'order` SET amount_partialy_paid='" .  $amount_partialy_paid . "'  WHERE order_id="' . $paid_order_id . '"');
        

        $sql = 'UPDATE ' . DB_PREFIX . "order SET amount_partialy_paid = '" . $amount_partialy_paid . "', paid = 'P',paid_to='".$paid_to."' WHERE order_id = '" . (int) $paid_order_id . "'";

        $query = $this->db->query($sql);
        // $sql = 'DELETE FROM ' . DB_PREFIX . "order_transaction_id WHERE order_id = '" . (int) $paid_order_id . "'";

        // $query = $this->db->query($sql);

         //insert  payments history
        //  $sql = 'INSERT into ' . DB_PREFIX . "payment_history SET order_id = '" . $paid_order_id . "', transaction_id = '" . $transaction_id . "', partial_amount = '" . $amount_partialy_paid . "'";
         $sql = 'INSERT into ' . DB_PREFIX . "payment_history SET order_id = '" . $paid_order_id . "', transaction_id = '" . $transaction_id . "', partial_amount = '" . $amount_partialy_paid . "',amount_received='".$amount_received."',grand_total='".$grand_total."', added_by = '" . $this->user->getId() . "',ip='".$this->db->escape($this->request->server['REMOTE_ADDR'])."',patial_amount_applied='".$partial_amount_applied."'" ;

            // echo $sql;die;
         
         $query = $this->db->query($sql);


        $sql = 'INSERT into ' . DB_PREFIX . "order_transaction_id SET order_id = '" . $paid_order_id . "', transaction_id = '" . $transaction_id . "'";

        $query = $this->db->query($sql);

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
        $sql = "SELECT o.order_id, c.customer_id,c.firstname,c.lastname,CONCAT(c.firstname, ' ', c.lastname) as customer,c.company_name as company, o.total,o.date_added ,ot.transaction_id ,o.paid,o.amount_partialy_paid,o.paid_to,ph.partial_amount,ph.amount_received,ph.patial_amount_applied FROM `".DB_PREFIX.'order` o inner join '.DB_PREFIX.'customer c on(c.customer_id = o.customer_id)  left outer join   '.DB_PREFIX.'order_transaction_id ot on ot.order_id = o.order_id';
        $sql .= " left outer join   ".DB_PREFIX.'payment_history ph on ph.order_id = ot.order_id and ph.transaction_id=ot.transaction_id';

        $sql .= " Where (o.paid = 'Y' || o.paid = 'P')   "; 

        $sql .= " and o.order_status_id not in (0,6,7,8,16,9,10,11,12) ";//15


        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id LIKE '".$data['filter_order_id']."%'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(c.firstname, ' ', c.lastname)  LIKE '%".$this->db->escape($data['filter_customer'])."%'";
        }

        if (!empty($data['filter_company'])) {
            $sql .= " AND c.company_name   LIKE '%".$this->db->escape($data['filter_company'])."%'";
        }
        

        if (isset($data['filter_customer_group']) && !empty($data['filter_customer_group'])) {
            $sql .= ' AND c.customer_group_id="' . $data['filter_customer_group'] . '"';
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
            $sql .= ' ORDER BY '.$data['sort'] .',ph.date_added';
        } else {
            $sql .= ' ORDER BY o.transaction_details_id,ph.date_added';
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
        $sql = 'SELECT COUNT(*) as total,sum(ort.value) as GrandTotal FROM `'.DB_PREFIX.'order` o inner join '.DB_PREFIX.'customer c on(c.customer_id = o.customer_id) left outer join '.DB_PREFIX.'order_total ort on(o.order_id =ort.order_id) and ort.code="total"  left outer join   '.DB_PREFIX.'order_transaction_id ot on ot.order_id = o.order_id';
        $sql .= " Where (o.paid = 'Y' || o.paid = 'P')    "; 
        $sql .= " and o.order_status_id not in (0,6,7,8,16,9,10,11,12) ";//15

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

        if (isset($data['filter_customer_group']) && !empty($data['filter_customer_group'])) {
            $sql .= ' AND c.customer_group_id="' . $data['filter_customer_group'] . '"';
        }

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

    public function getSuccessfulOrderReceivablesGrandTotal($data = [])
    {
        $sql = 'SELECT COUNT(*) as total,sum(ort.value) as GrandTotal FROM `'.DB_PREFIX.'order` o inner join '.DB_PREFIX.'customer c on(c.customer_id = o.customer_id) left outer join '.DB_PREFIX.'order_total ort on(o.order_id =ort.order_id) and ort.code="total" ';
        $sql .= " Where (o.paid = 'Y' || o.paid = 'P')    "; 
        $sql .= " and o.order_status_id not in (0,6,7,8,16,9,10,11,12) ";//15

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

        if (isset($data['filter_customer_group']) && !empty($data['filter_customer_group'])) {
            $sql .= ' AND c.customer_group_id="' . $data['filter_customer_group'] . '"';
        }

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
    public function getTotalPendingAmount($data = [])
    {
        $sql = 'SELECT  sum(ort.value) as total1,sum(o.amount_partialy_paid) as total2  FROM `'.DB_PREFIX.'order` o inner join '.DB_PREFIX.'customer c on(c.customer_id = o.customer_id) left outer join '.DB_PREFIX.'order_total ort on(o.order_id =ort.order_id) and ort.code="total"';
        $sql .= " Where  o.paid = 'P'     "; 
        $sql .= " and o.order_status_id not in (0,6,7,8,16,9,10,11,12) ";//15

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

        if (isset($data['filter_customer_group']) && !empty($data['filter_customer_group'])) {
            $sql .= ' AND c.customer_group_id="' . $data['filter_customer_group'] . '"';
        }

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


        return ($query->row['total1']-$query->row['total2']);
        // return $query->row;
    }

    public function reversePaymentReceived($paid_order_id,$transaction_id, $patial_amount_applied_value = 0,$order_total=0,$amount_partialy_paid=0) {
       
       
       if($patial_amount_applied_value==0 || $patial_amount_applied_value=='')
       
       {
        return;//if no $patial_amount_applied_value , make it non reversable,
       }
       $current=$amount_partialy_paid-$patial_amount_applied_value;
    
        if($amount_partialy_paid==0 || $current==0)
        {
        $this->db->query('update `' . DB_PREFIX . 'order` SET paid="N", amount_partialy_paid = 0,paid_to="" WHERE order_id="' . $paid_order_id . '"');
        }
        else if($amount_partialy_paid>0)
        {
            if($current>0)
            {
            $this->db->query('update `' . DB_PREFIX . 'order` SET paid="P", amount_partialy_paid = "'.$current.'"  WHERE order_id="' . $paid_order_id . '"');
            }
            else
            {
                $log = new Log('error.log');
                $log->write('reversing payment receivables went negative');
                $log->write('order_id'.$paid_order_id);
                $log->write('current'.$current);

                $log->write('reversing payment receivables went negative');

            }

        }
    
        
        $sql = 'DELETE FROM ' . DB_PREFIX . "order_transaction_id WHERE order_id = '" . (int) $paid_order_id . "' and transaction_id = '" .  $transaction_id . "' ";

        $query = $this->db->query($sql);
 

    }


    public function insertPaymentReceivedEntery($selected, $transaction_id, $amount_received,$grand_total,$added_by,$reversed_by=NULL) {
  
        $sql = 'INSERT into ' . DB_PREFIX . "payment_received SET order_ids = '" . $selected . "', transaction_id = '" . $transaction_id . "', amount_received = '" . $amount_received . "', grand_total = '" . $grand_total . "', added_by = '" . $added_by . "',reversed_by='".$reversed_by."'";

        $query = $this->db->query($sql);

    }


    public function reversePaymentReceivedEntery($selected, $transaction_id, $reversed_by=NULL) {
  
        $sql = 'Update ' . DB_PREFIX . "payment_history SET reversed_by='".$reversed_by."' where order_id = '" . $selected . "' and  transaction_id = '" . $transaction_id . "'";

        $query = $this->db->query($sql);

    }

 
    public function checkPaymentReceivedEntery($transaction_id,$paid_to) {
  
    $sql = 'Select transaction_id from ' . DB_PREFIX . "payment_received where transaction_id = '" . $transaction_id . "'";

    $query = $this->db->query($sql);
    $status=$query->rows;
    if(count($status)>0)
    {
        return "111";      
    }
    else
    {
        if($paid_to=="Mpesa")
        {
            $sql1 = 'Select transaction_id from ' . DB_PREFIX . "mpesa_track_payments_confirmation where transaction_id = '" . $transaction_id . "'";

            $query1 = $this->db->query($sql1);
            $status1=$query1->rows;
            if(count($status1)>0)
            {
            return "0";                  
            }
            else{
                return "222";
            }
        }
        else{
            return "0"; 
        }
    }

    }

}
