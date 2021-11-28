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
}
