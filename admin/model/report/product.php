<?php

class ModelReportProduct extends Model
{
    public function getProductsViewed($data = [])
    {
        $sql = 'SELECT pd.name, p.model, p.viewed FROM '.DB_PREFIX.'product p LEFT JOIN '.DB_PREFIX."product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '".(int) $this->config->get('config_language_id')."' AND p.viewed > 0 ORDER BY p.viewed DESC";

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

    public function getTotalProductViews()
    {
        $query = $this->db->query('SELECT SUM(viewed) AS total FROM '.DB_PREFIX.'product');

        return $query->row['total'];
    }

    public function getTotalProductsViewed()
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX.'product WHERE viewed > 0');

        return $query->row['total'];
    }

    public function reset()
    {
        $this->db->query('UPDATE '.DB_PREFIX."product SET viewed = '0'");
    }

    public function getPurchased($data = [])
    {
        $sql = 'SELECT op.name,op.unit, op.model, SUM(op.quantity) AS quantity, SUM((op.price + op.tax) * op.quantity) AS total FROM '.DB_PREFIX.'order_product op LEFT JOIN `'.DB_PREFIX.'order` o ON (op.order_id = o.order_id)';

        $sql .= ' inner join '.DB_PREFIX.'store st on st.store_id = o.store_id';

        if (!empty($data['filter_city'])) {
            $sql .= ' left join `'.DB_PREFIX.'city` c on c.city_id = o.shipping_city_id ';
        }

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " WHERE o.order_status_id = '".(int) $data['filter_order_status_id']."'";
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }

        if ($this->user->isVendor()) {
            $sql .= ' AND st.vendor_id = "'.$this->user->getId().'"';
        }

        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '".$data['filter_city']."%'";
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.date_added) >= '".$this->db->escape($data['filter_date_start'])."'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.date_added) <= '".$this->db->escape($data['filter_date_end'])."'";
        }

        if (true) {
            $sql .= ' AND op.general_product_id > 0';
        }

        $sql .= ' GROUP BY op.general_product_id ORDER BY total DESC';

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

    public function getTotalPurchased($data)
    {
        $sql = 'SELECT COUNT(DISTINCT op.general_product_id) AS total FROM `'.DB_PREFIX.'order_product` op LEFT JOIN `'.DB_PREFIX.'order` o ON (op.order_id = o.order_id)';
        $sql .= ' inner join '.DB_PREFIX.'store st on st.store_id = o.store_id';

        if (!empty($data['filter_city'])) {
            $sql .= ' left join `'.DB_PREFIX.'city` c on c.city_id = o.shipping_city_id ';
        }

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " WHERE o.order_status_id = '".(int) $data['filter_order_status_id']."'";
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }
        if ($this->user->isVendor()) {
            $sql .= ' AND st.vendor_id = "'.$this->user->getId().'"';
        }

        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '".$data['filter_city']."%'";
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.date_added) >= '".$this->db->escape($data['filter_date_start'])."'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.date_added) <= '".$this->db->escape($data['filter_date_end'])."'";
        }

        if (true) {
            $sql .= ' AND op.general_product_id > 0';
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getproductsconsumption($data = []) {
        //Order Rejected(16),Order Approval Pending(15),Cancelled(6),Failed(8),Pending(9),Possible Fraud(10)
        $sql0 = "SELECT o.order_id,date(o.date_added) as date_added,c.company_name  as company,c.payment_terms,CONCAT(c.firstname, ' ', c.lastname) as customer,c.status as customer_status,op.name,op.unit,op.product_id,  op.quantity ,os.name as status  FROM `" . DB_PREFIX . 'order_product` op LEFT JOIN `' . DB_PREFIX . 'order` o ON (op.order_id = o.order_id) LEFT JOIN `' . DB_PREFIX . "customer` c ON (c.customer_id = o.customer_id)  LEFT JOIN `" . DB_PREFIX . "order_status` os ON (os.order_status_id = o.order_status_id) WHERE o.customer_id > 0   and o.order_status_id not in (0,16,15,6,8,9,10)   and o.order_id not in (select order_id from `hf7_real_order_product`)  ";
        $sql1 = "SELECT o.order_id,date(o.date_added) as date_added,c.company_name  as company,c.payment_terms,CONCAT(c.firstname, ' ', c.lastname) as customer,c.status as customer_status,op.name,op.unit,op.product_id,  op.quantity  ,os.name as status FROM `" . DB_PREFIX . 'real_order_product` op LEFT JOIN `' . DB_PREFIX . 'order` o ON (op.order_id = o.order_id) LEFT JOIN `' . DB_PREFIX . "customer` c ON (c.customer_id = o.customer_id) LEFT JOIN `" . DB_PREFIX . "order_status` os ON (os.order_status_id = o.order_status_id)  WHERE o.customer_id > 0   and o.order_status_id not in (0,16,15,6,8,9,10) ";

        // if (!empty($data['filter_order_status_id'])) {
        //     $sql .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        // } else {
        //     $sql .= " AND o.order_status_id > '0' AND  o.order_status_id != '6'";
        // }GROUP BY pd.name   ORDER BY total DESC

        if (!empty($data['filter_name'])) {
            $sql0 .= " AND op.name LIKE '".$data['filter_name']."%'";
            $sql1 .= " AND op.name LIKE '".$data['filter_name']."%'";
        }


        if (!empty($data['filter_date_start'])) {
            $sql0 .= " AND DATE(o.date_added) = '" . $this->db->escape($data['filter_date_start']) . "'";
            $sql1 .= " AND DATE(o.date_added) = '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        // if (!empty($data['filter_date_end'])) {
        //     $sql0 .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        //     $sql1 .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        // }

        if (!empty($data['filter_customer'])) {
            // $sql .= " AND   c.customer_id   = '" .(int) $this->db->escape($data['filter_customer']) . "'";
            $sql0 .= " AND CONCAT(c.firstname, ' ', c.lastname)  LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
            $sql1 .= " AND CONCAT(c.firstname, ' ', c.lastname)  LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_company'])) {
            $sql0 .= " AND c.company_name   LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
            $sql1 .= " AND c.company_name   LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }
        //$log = new Log('error.log');
        //$log->write($data['filter_variations']);
        if (!empty($data['filter_variations']) && count($data['filter_variations']) > 0) {
            $variant_array = array_column($data['filter_variations'], 'variation_id');
            $variants = implode(',', $variant_array);
            $sql0 .= "AND op.product_id IN (" . $variants . ")";
            $sql1 .= "AND op.product_id IN (" . $variants . ")";
        }

        // $sql0 .= ' GROUP BY op.product_id ';
        // $sql1 .= ' GROUP BY op.product_id '; //general_product_id

        $sql = "SELECT order_id,date_added,company,payment_terms,customer,customer_status,name,unit,product_id, quantity ,status from (" . $sql0 . "union all " . $sql1 . ") as t";
        // $sql .= ' GROUP BY product_id   ORDER BY quantity DESC';

        // if (isset($data['start']) || isset($data['limit'])) {
        //     if ($data['start'] < 0) {
        //         $data['start'] = 0;
        //     }
        //     if ($data['limit'] < 1) {
        //         $data['limit'] = 20;
        //     }
        //     $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        // }
        // echo  ($sql);die;
        $query = $this->db->query($sql);
        // echo  ($query->rows);die;

        return $query->rows;
    }


    public function getInventoryPurchased($data = [])
    {
        $sql = 'SELECT ih.product_history_id,ih.product_name,p.unit, (ih.procured_qty) AS quantity,ih.source,date(ih.date_added) as date,(ih.buying_price) as price, ((ih.buying_price) * ih.procured_qty) AS total FROM '.DB_PREFIX.'product_inventory_history ih LEFT JOIN `'.DB_PREFIX.'product` p ON (ih.product_id = p.product_id)  where 1=1';

 
        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(ih.date_added) >= '".$this->db->escape($data['filter_date_start'])."'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(ih.date_added) <= '".$this->db->escape($data['filter_date_end'])."'";
        }

        

        // $sql .= ' GROUP BY op.general_product_id ';
        $sql .= '  ORDER BY ih.date_added DESC';

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

    public function getTotalInventoryPurchased($data)
    {
        $sql = 'SELECT count(ih.product_history_id) AS total FROM '.DB_PREFIX.'product_inventory_history ih LEFT JOIN `'.DB_PREFIX.'product` p ON (ih.product_id = p.product_id)  where 1=1';

 
        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(ih.date_added) >= '".$this->db->escape($data['filter_date_start'])."'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(ih.date_added) <= '".$this->db->escape($data['filter_date_end'])."'";
        }

        

        // $sql .= ' GROUP BY op.general_product_id ';
        // $sql .= '  ORDER BY ih.date_added DESC';

        

        $query = $this->db->query($sql);

        return $query->row['total'];
    }


}
