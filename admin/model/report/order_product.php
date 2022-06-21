<?php

class ModelReportOrderProduct extends Model {

    public function getOrders($data = []) {
        $sql = 'SELECT * FROM `' . DB_PREFIX . "order` o WHERE o.customer_id > '0'";

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " AND o.order_status_id > '0'";
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        $sql .= ' GROUP BY o.order_id';

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalOrders($data = []) {
        $sql = 'SELECT COUNT(DISTINCT o.customer_id) AS total FROM `' . DB_PREFIX . "order` o WHERE o.customer_id > '0'";

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getOrderedProducts($order_id) {
        $sql = 'SELECT order_id,product_id,name,unit,quantity as customer_ordered_quantity FROM `' . DB_PREFIX . "order_product` o WHERE o.order_id = '" . $order_id . "'";
        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function getOrderUpdatedProducts($order_id) {
        $sql = 'SELECT order_id,product_id,name,unit,quantity as updated_quantity FROM `' . DB_PREFIX . "real_order_product` o WHERE o.order_id = '" . $order_id . "'";
        $query = $this->db->query($sql);
        return $query->rows;
    }


    public function getOrderedAndUpdatedProducts($data = []) {
        $sql0 = 'SELECT op.order_id, op.product_id, op.name,op.unit, op.quantity,0 as updated_quantity  FROM `' . DB_PREFIX . "order_product` op join hf7_order o on o.order_id =op.order_id ";
        $sql1 = 'SELECT rp.order_id, rp.product_id, rp.name,rp.unit,0 as quantity, rp.quantity as updated_quantity  FROM `' . DB_PREFIX . "real_order_product` rp  join hf7_order o on o.order_id =rp.order_id";

        if (!empty($data['filter_order_status_id'])) {
            $sql0 .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
            $sql1 .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql0 .= " AND o.order_status_id > '0'";
            $sql1 .= " AND o.order_status_id > '0'";
        }


        if (!empty($data['filter_order_id'])) {
            $sql0 .= " AND o.order_id = '" . (int) $data['filter_order_id'] . "'";
            $sql1 .= " AND o.order_id = '" . (int) $data['filter_order_id'] . "'";
        }  


        if (!empty($data['filter_date_start'])) {
            $sql0 .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
            $sql1 .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql0 .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
            $sql1 .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        $sql .= 'select order_id ,product_id, name, unit,sum(quantity) as quantity,sum(updated_quantity) as updated_quantity from ('.$sql0.' union all '.$sql1.')as t ';
        $sql .= ' GROUP BY t.order_id,t.product_id, t.name ,t.unit order by t.order_id asc';

        // if (isset($data['start']) || isset($data['limit'])) {
        //     if ($data['start'] < 0) {
        //         $data['start'] = 0;
        //     }

        //     if ($data['limit'] < 1) {
        //         $data['limit'] = 20;
        //     }

        //     $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        // }

        $query = $this->db->query($sql);

            //    echo  ($sql);die; 


        return $query->rows;
    }

}
