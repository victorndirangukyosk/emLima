<?php

class ModelMarketingOffer extends Model
{
    public function addOffer($data)
    {
        $this->trigger->fire('pre.admin.offer.add', $data);

        $this->db->query('INSERT INTO '.DB_PREFIX."offers SET name = '".$this->db->escape($data['name'])."', store_id = '".(int) $data['store_id']."', discount = '".(float) $data['discount']."', date_start = '".$this->db->escape($data['date_start'])."', date_end = '".$this->db->escape($data['date_end'])."', status = '".(int) $data['status']."'");

        $offer_id = $this->db->getLastId();

        if (isset($data['offer_product'])) {
            foreach ($data['offer_product'] as $product_id) {
                $this->db->query('INSERT INTO '.DB_PREFIX."offer_products SET offer_id = '".(int) $offer_id."', product_id = '".(int) $product_id."'");
            }
        }

        $this->trigger->fire('post.admin.offer.add', $offer_id);

        return $offer_id;
    }

    public function editOffer($offer_id, $data)
    {
        $this->trigger->fire('pre.admin.offer.edit', $data);

        $this->db->query('UPDATE '.DB_PREFIX."offers SET name = '".$this->db->escape($data['name'])."', discount = '".(float) $data['discount']."', store_id = '".(int) $data['store_id'].$this->db->escape($data['name'])."', date_start = '".$this->db->escape($data['date_start'])."', date_end = '".$this->db->escape($data['date_end'])."', status = '".(int) $data['status']."' WHERE offer_id = '".(int) $offer_id."'");

        $this->db->query('DELETE FROM '.DB_PREFIX."offer_products WHERE offer_id = '".(int) $offer_id."'");

        if (isset($data['offer_product'])) {
            foreach ($data['offer_product'] as $product_id) {
                $this->db->query('INSERT INTO '.DB_PREFIX."offer_products SET offer_id = '".(int) $offer_id."', product_id = '".(int) $product_id."'");
            }
        }

        $this->trigger->fire('post.admin.offer.edit', $offer_id);
    }

    public function deleteOffer($offer_id)
    {
        $this->trigger->fire('pre.admin.coupon.delete', $offer_id);

        $this->db->query('DELETE FROM '.DB_PREFIX."offers WHERE offer_id = '".(int) $offer_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."offer_products WHERE offer_id = '".(int) $offer_id."'");

        $this->trigger->fire('post.admin.coupon.delete', $offer_id);
    }

    public function getOffer($offer_id)
    {
        $query = $this->db->query('SELECT of.*,st.name as store_name FROM '.DB_PREFIX.'offers of inner join '.DB_PREFIX."store st on st.store_id = of.store_id  WHERE offer_id = '".(int) $offer_id."'");

        return $query->row;
    }

    public function getCouponByCode($code)
    {
        $query = $this->db->query('SELECT DISTINCT * FROM '.DB_PREFIX."coupon WHERE code = '".$this->db->escape($code)."'");

        return $query->row;
    }

    public function getOffers($data = [])
    {
        $sql = 'SELECT offer_id, of.name as name,s.name as store_name,title, discount, date_start, date_end, of.status as status FROM '.DB_PREFIX.'offers of inner join '.DB_PREFIX.'store s on s.store_id = of.store_id';

        $isWhere = 0;
        $_sql = [];

        if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
            $isWhere = 1;

            $_sql[] = "of.name LIKE '".$this->db->escape($data['filter_name'])."%'";
        }

        if (isset($data['filter_date_start']) && !is_null($data['filter_date_start'])) {
            $isWhere = 1;

            $_sql[] = "date_start = '".$this->db->escape($data['filter_date_start'])."'";
        }

        if (isset($data['filter_date_end']) && !is_null($data['filter_date_end'])) {
            $isWhere = 1;

            $_sql[] = "date_end = '".$this->db->escape($data['filter_date_end'])."'";
        }

        if (isset($data['filter_store']) && !is_null($data['filter_store'])) {
            $isWhere = 1;

            $_sql[] = "s.store_id = '".$this->db->escape($data['filter_store'])."'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $isWhere = 1;

            $_sql[] = "of.status = '".$this->db->escape($data['filter_status'])."'";
        }

        if ($isWhere) {
            $sql .= ' WHERE '.implode(' AND ', $_sql);
        }

        $sort_data = [
            'name',
            'discount',
            'date_start',
            'date_end',
            'status',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY '.$data['sort'];
        } else {
            $sql .= ' ORDER BY name';
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

        return $query->rows;
    }

    public function getOfferProducts($offer_id)
    {
        $offer_product_data = [];

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."offer_products WHERE offer_id = '".(int) $offer_id."'");

        foreach ($query->rows as $result) {
            $offer_product_data[] = $result['product_id'];
        }

        return $offer_product_data;
    }

    public function getTotalProductsByStore($store_id)
    {
        $sql = 'SELECT count(*) as tot from '.DB_PREFIX.'product_to_store ps';
        $sql .= " WHERE ps.status=0 AND store_id = '".$store_id."'";
        $query = $this->db->query($sql);

        return $query->row['tot'];
    }

    public function getProductsByStore($store_id)
    {
        $sql = 'SELECT *,p.name from '.DB_PREFIX.'product_to_store ps inner join '.DB_PREFIX.'product p on (p.product_id = ps.product_id)';
        $sql .= " WHERE ps.status=0 AND store_id = '".$store_id."'";
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getCouponCategories($coupon_id)
    {
        $coupon_category_data = [];

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."coupon_category WHERE coupon_id = '".(int) $coupon_id."'");

        foreach ($query->rows as $result) {
            $coupon_category_data[] = $result['category_id'];
        }

        return $coupon_category_data;
    }

    public function getTotalOffers()
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX.'offers');

        return $query->row['total'];
    }

    public function getTotalOffersFilter($data)
    {
        //$sql = ("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "offers");

        $sql = 'SELECT count(*) as total FROM '.DB_PREFIX.'offers of inner join '.DB_PREFIX.'store s on s.store_id = of.store_id';

        $isWhere = 0;
        $_sql = [];

        if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
            $isWhere = 1;

            $_sql[] = "of.name LIKE '".$this->db->escape($data['filter_name'])."%'";
        }

        if (isset($data['filter_date_start']) && !is_null($data['filter_date_start'])) {
            $isWhere = 1;

            $_sql[] = "date_start = '".$this->db->escape($data['filter_date_start'])."'";
        }

        if (isset($data['filter_date_end']) && !is_null($data['filter_date_end'])) {
            $isWhere = 1;

            $_sql[] = "date_end = '".$this->db->escape($data['filter_date_end'])."'";
        }

        if (isset($data['filter_store']) && !is_null($data['filter_store'])) {
            $isWhere = 1;

            $_sql[] = "s.store_id = '".$this->db->escape($data['filter_store'])."'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $isWhere = 1;

            $_sql[] = "of.status = '".$this->db->escape($data['filter_status'])."'";
        }

        if ($isWhere) {
            $sql .= ' WHERE '.implode(' AND ', $_sql);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getCouponHistories($coupon_id, $start = 0, $limit = 10)
    {
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 10;
        }

        $query = $this->db->query("SELECT ch.order_id, CONCAT(c.firstname, ' ', c.lastname) AS customer, ch.amount, ch.date_added FROM ".DB_PREFIX.'coupon_history ch LEFT JOIN '.DB_PREFIX."customer c ON (ch.customer_id = c.customer_id) WHERE ch.coupon_id = '".(int) $coupon_id."' ORDER BY ch.date_added ASC LIMIT ".(int) $start.','.(int) $limit);

        return $query->rows;
    }

    public function getTotalCouponHistories($coupon_id)
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX."coupon_history WHERE coupon_id = '".(int) $coupon_id."'");

        return $query->row['total'];
    }
}
