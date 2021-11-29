<?php

class ModelDesignSlider extends Model
{
    public function addSlider($data)
    {
        $this->trigger->fire('pre.admin.slider.add', $data);

        $this->db->query('INSERT INTO '.DB_PREFIX."sliders SET name = '".$this->db->escape($data['name'])."', store_id = '".(int) $data['store_id']."', date_start = '".$this->db->escape($data['date_start'])."', date_end = '".$this->db->escape($data['date_end'])."', status = '".(int) $data['status']."'");

        $slider_id = $this->db->getLastId();

        if (isset($data['slider_image'])) {
            foreach ($data['slider_image'] as $data) {
                $this->db->query('INSERT INTO '.DB_PREFIX."slider_datas SET slider_id = '".(int) $slider_id."', link = '".$data['link']."', image = '".$data['image']."'");
            }
        }

        $this->trigger->fire('post.admin.slider.add', $slider_id);

        return $slider_id;
    }

    public function editslider($slider_id, $data)
    {
        $this->trigger->fire('pre.admin.slider.edit', $data);

        $this->db->query('UPDATE '.DB_PREFIX."sliders SET name = '".$this->db->escape($data['name'])."', store_id = '".(int) $data['store_id'].$this->db->escape($data['name'])."', date_start = '".$this->db->escape($data['date_start'])."', date_end = '".$this->db->escape($data['date_end'])."', status = '".(int) $data['status']."' WHERE slider_id = '".(int) $slider_id."'");

        $this->db->query('DELETE FROM '.DB_PREFIX."slider_datas WHERE slider_id = '".(int) $slider_id."'");

        if (isset($data['slider_image'])) {
            foreach ($data['slider_image'] as $data) {
                $this->db->query('INSERT INTO '.DB_PREFIX."slider_datas SET slider_id = '".(int) $slider_id."', link = '".$data['link']."', image = '".$data['image']."'");
            }
        }

        $this->trigger->fire('post.admin.slider.edit', $slider_id);
    }

    public function deleteslider($slider_id)
    {
        $this->trigger->fire('pre.admin.coupon.delete', $slider_id);

        $this->db->query('DELETE FROM '.DB_PREFIX."sliders WHERE slider_id = '".(int) $slider_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."slider_datas WHERE slider_id = '".(int) $slider_id."'");

        $this->trigger->fire('post.admin.coupon.delete', $slider_id);
    }

    public function getslider($slider_id)
    {
        $query = $this->db->query('SELECT of.*,st.name as store_name FROM '.DB_PREFIX.'sliders of inner join '.DB_PREFIX."store st on st.store_id = of.store_id  WHERE slider_id = '".(int) $slider_id."'");

        return $query->row;
    }

    public function getProductCollection($data = [])
    {
        //$sql = "SELECT * FROM " . DB_PREFIX . "product_collection pc" ;

        $sql = 'SELECT * FROM   `'.DB_PREFIX.'product_collection` pc  JOIN `'.DB_PREFIX.'product_collection_description`  pcd ON pcd.product_collection_id = pc.product_collection_id';

        $isWhere = 0;
        $_sql = [];

        if (true) {
            $isWhere = 1;
            $_sql[] = "pcd.language_id= '".(int) $this->config->get('config_language_id')."'";
        }

        if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
            $isWhere = 1;

            $_sql[] = "pcd.name LIKE '".$this->db->escape($data['filter_name'])."%'";
        }

        /*if (isset($data['meta_description']) && !is_null($data['meta_description'])) {
            $isWhere = 1;

            $_sql[] = "pc.meta_description = '" . $this->db->escape($data['meta_description']) . "'" ;
        }*/

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $isWhere = 1;

            $_sql[] = "pc.status = '".$this->db->escape($data['filter_status'])."'";
        }

        if ($isWhere) {
            $sql .= ' WHERE '.implode(' AND ', $_sql);
        }

        $sort_data = [
            'name',
            'meta_description',
            'meta_keywords',
            'content',
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

    public function getCouponByCode($code)
    {
        $query = $this->db->query('SELECT DISTINCT * FROM '.DB_PREFIX."coupon WHERE code = '".$this->db->escape($code)."'");

        return $query->row;
    }

    public function getsliders($data = [])
    {
        $sql = 'SELECT slider_id, of.name as name,s.name as store_name, date_start, date_end, of.status as status FROM '.DB_PREFIX.'sliders of inner join '.DB_PREFIX.'store s on s.store_id = of.store_id';

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

    public function getSliderData($slider_id)
    {
        $slider_product_data = [];

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."slider_datas WHERE slider_id = '".(int) $slider_id."'");

        return $query->rows;
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

    public function getTotalSliders()
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX.'sliders');

        return $query->row['total'];
    }

    public function getTotalSlidersFilter($data)
    {
        //$sql = ("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "sliders");

        $sql = 'SELECT count(*) as total FROM '.DB_PREFIX.'sliders of inner join '.DB_PREFIX.'store s on s.store_id = of.store_id';

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

    public function deleteImage($id)
    {
        $this->db->query('DELETE FROM '.DB_PREFIX."slider_datas WHERE id = '".(int) $id."'");
    }
}
