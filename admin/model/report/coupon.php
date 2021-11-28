<?php

class ModelReportCoupon extends Model
{
    public function getCoupons($data = [])
    {
        $sql = 'SELECT ch.coupon_id, c.name, c.code, COUNT(DISTINCT ch.order_id) AS `orders`, SUM(ch.amount) AS total FROM `'.DB_PREFIX.'coupon_history` ch LEFT JOIN `'.DB_PREFIX.'coupon` c ON (ch.coupon_id = c.coupon_id) ';

        if (!empty($data['filter_city'])) {
            $sql .= ' left join '.DB_PREFIX.'order o on o.order_id = ch.order_id ';
            $sql .= ' left join '.DB_PREFIX.'city ct on ct.city_id = o.shipping_city_id ';
        }

        $implode = [];

        if (!empty($data['filter_city'])) {
            $implode[] = "ct.name LIKE '".$this->db->escape($data['filter_city'])."%'";
        }

        if (!empty($data['filter_date_start'])) {
            $implode[] = "DATE(ch.date_added) >= '".$this->db->escape($data['filter_date_start'])."'";
        }

        if (!empty($data['filter_date_end'])) {
            $implode[] = "DATE(ch.date_added) <= '".$this->db->escape($data['filter_date_end'])."'";
        }

        if ($implode) {
            $sql .= ' WHERE '.implode(' AND ', $implode);
        }

        $sql .= ' GROUP BY ch.coupon_id ORDER BY total DESC';

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

    public function getTotalCoupons($data = [])
    {
        $sql = 'SELECT COUNT(DISTINCT ch.coupon_id) AS total FROM `'.DB_PREFIX.'coupon_history` ch ';

        if (!empty($data['filter_city'])) {
            $sql .= ' left join '.DB_PREFIX.'order o on o.order_id = ch.order_id ';
            $sql .= ' left join '.DB_PREFIX.'city ct on ct.city_id = o.shipping_city_id ';
        }

        $implode = [];

        if (!empty($data['filter_city'])) {
            $implode[] = "ct.name LIKE '".$this->db->escape($data['filter_city'])."%'";
        }

        if (!empty($data['filter_date_start'])) {
            $implode[] = "DATE(ch.date_added) >= '".$this->db->escape($data['filter_date_start'])."'";
        }

        if (!empty($data['filter_date_end'])) {
            $implode[] = "DATE(ch.date_added) <= '".$this->db->escape($data['filter_date_end'])."'";
        }

        if ($implode) {
            $sql .= ' WHERE '.implode(' AND ', $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }
}
