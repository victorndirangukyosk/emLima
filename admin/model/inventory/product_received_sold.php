<?php

class ModelInventoryProductReceivedSold extends Model {

    
    public function getProductsReceivedSold($data = []) {
        //op.product_id,
        //i.product_store_id as product_id,
        $sql1 = 'SELECT op.name,op.unit,op.quantity,op.total as sum1,(op.tax*op.quantity)as sum2,0 as procured_qty,0 as rejected_qty,0 as buying_price,0 as total ,0 as count FROM `hf7_order_product`op join hf7_order o on o.order_id =op.order_id WHERE   o.order_status_id not in(0,6,8,9,16) and o.order_id not in (select order_id from hf7_real_order_product) ';
        $sql2 = 'SELECT op.name,op.unit,op.quantity,op.total as sum1,(op.tax*op.quantity)as sum2,0 as procured_qty,0 as rejected_qty,0 as buying_price,0 as total,0 as count FROM `hf7_real_order_product`op join hf7_order o on o.order_id =op.order_id WHERE   o.order_status_id not in(0,6,8,9,16)  ';

        $sql3 = 'SELECT i.product_name as name,p.unit,0 as quantity,0 as sum1,0 as sum2,i.procured_qty,i.rejected_qty,i.buying_price as buying_price,(i.buying_price*i.procured_qty) as total,1 as count   FROM `hf7_product_inventory_history` i join hf7_product p on i.product_id =p.product_id WHERE  1=1  ';
       
        if (!empty($data['filter_name'])) {
            $sql1 .= " AND op.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
            $sql2 .= " AND op.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
           
            $sql3 .= " AND i.product_name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }
 
        if (!empty($data['filter_date_added'])  && !empty($data['filter_date_added_to'])) {
            $sql1 .= " AND DATE_FORMAT(o.delivery_date, '%Y-%m-%d') >= '" . $this->db->escape($data['filter_date_added']) . "' and DATE_FORMAT(o.delivery_date, '%Y-%m-%d') <= '" . $this->db->escape($data['filter_date_added_to']) . "'";
            $sql2 .= " AND DATE_FORMAT(o.delivery_date, '%Y-%m-%d') >= '" . $this->db->escape($data['filter_date_added']) . "' and DATE_FORMAT(o.delivery_date, '%Y-%m-%d') <= '" . $this->db->escape($data['filter_date_added_to']) . "'";
            
            $sql3 .= " AND DATE_FORMAT(i.date_added, '%Y-%m-%d') >= '" . $this->db->escape($data['filter_date_added']) . "' and DATE_FORMAT(i.date_added, '%Y-%m-%d') <= '" . $this->db->escape($data['filter_date_added_to']) . "'";
        }
        else if(!empty($data['filter_date_added']) && empty($data['filter_date_added_to']))
        {
            $sql1 .= " AND DATE_FORMAT(o.delivery_date, '%Y-%m-%d') = '" . $this->db->escape($data['filter_date_added']) . "'";
            $sql2 .= " AND DATE_FORMAT(o.delivery_date, '%Y-%m-%d') = '" . $this->db->escape($data['filter_date_added']) . "'";
            
            $sql3 .= " AND DATE_FORMAT(i.date_added, '%Y-%m-%d') = '" . $this->db->escape($data['filter_date_added']) . "'";

        }
        else if(!empty($data['filter_date_added_to']) && empty($data['filter_date_added']))
        {          
            $sql1 .= "AND DATE_FORMAT(o.delivery_date, '%Y-%m-%d') = '" . $this->db->escape($data['filter_date_added_to']) . "'";
            $sql2 .= "AND DATE_FORMAT(o.delivery_date, '%Y-%m-%d') = '" . $this->db->escape($data['filter_date_added_to']) . "'";
           
            $sql2 .= "AND DATE_FORMAT(i.date_added, '%Y-%m-%d') = '" . $this->db->escape($data['filter_date_added_to']) . "'";
          
        }
            //buying price not coming correctly, due to wrong data

        $sort_data = [
            't.name',           
            // 't.product_id',
           
        ];

        $sql .= 'select  t.name,t.unit,sum(quantity)as quantity,sum(t.sum1+t.sum2)as revenue,sum(procured_qty) as procured_qty ,sum(rejected_qty) as rejected_qty,0 as priceperItem ,sum(total) as Totalprice from ('.$sql1. ' union all ' .$sql2 .' union all ' .$sql3.') as t';
        $sql .= '  group by  t.name ,t.unit ';//t.product_id,t.product_id,

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
            // $sql .= ' ORDER BY pw.date_added  DESC';
          

        } else {
            // $sql .= ' ORDER BY pd.name';
            $sql .= 'ORDER BY  t.name  ';
        }
        if (isset($data['order']) && ('ASC' == $data['order'])) {
            $sql .= ' ASC';
        } else {
            $sql .= ' DESC';
        }
      

        // if (isset($data['start']) || isset($data['limit'])) {
        //     if ($data['start'] < 0) {
        //         $data['start'] = 0;
        //     }

        //     if ($data['limit'] < 1) {
        //         $data['limit'] = 20;
        //     }

        //     $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        // }

        // echo $sql;die;

        // echo "<pre>";print_r($sql);die;

        $query = $this->db->query($sql);

        return $query->rows;
    }
  
     
}
