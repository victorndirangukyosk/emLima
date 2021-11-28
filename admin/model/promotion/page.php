<?php

class ModelPromotionPage extends Model
{
    public function addProductCollection($data)
    {
        $this->db->query('INSERT INTO '.DB_PREFIX."product_collection SET status = '".(int) $data['status']."'");

        $product_collection_id = $this->db->getLastId();

        if (isset($data['product_collection_product'])) {
            foreach ($data['product_collection_product'] as $product_id) {
                $this->db->query('INSERT INTO '.DB_PREFIX."product_collection_products SET product_collection_id = '".(int) $product_collection_id."', product_id = '".(int) $product_id."'");
            }
        }

        foreach ($data['product_collection_description'] as $language_id => $value) {
            empty($value['meta_title']) ? $value['meta_title'] = $data['name'] : $value['meta_title'];

            $this->db->query('INSERT INTO '.DB_PREFIX."product_collection_description SET product_collection_id = '".(int) $product_collection_id."', name = '".$this->db->escape($value['name'])."', language_id = '".(int) $language_id."', meta_title = '".$this->db->escape($value['meta_title'])."', meta_description = '".$this->db->escape($value['meta_description'])."', meta_keyword = '".$this->db->escape($value['meta_keyword'])."'");

            /*if ( isset( $value['name'] )) {
                $this->db->query( "UPDATE " . DB_PREFIX . "product SET name = '" . $this->db->escape( $value['name'] ). "' WHERE product_id = '" . (int) $product_id . "'" );
            }*/
        }

        $this->load->model('catalog/url_alias');

        if (isset($data['seo_url'])) {
            foreach ($data['seo_url'] as $language_id => $value) {
                //$alias = empty( $value ) ? $data['product_collection_description'][$language_id]['name'] : $value;
                $alias = empty($value) ? $data['name'] : $value;

                $alias = $this->model_catalog_url_alias->generateAlias($alias);

                if ($alias) {
                    $this->db->query('INSERT INTO '.DB_PREFIX."url_alias SET query = 'product_collection_id=".(int) $product_collection_id."', keyword = '".$this->db->escape($alias)."', language_id = '".$language_id."'");
                }
            }
        }

        $this->trigger->fire('post.admin.product_collection.add', $product_collection_id);

        return $product_collection_id;
    }

    public function editProductCollection($product_collection_id, $data)
    {
        $this->trigger->fire('pre.admin.product_collection.edit', $data);

        $this->db->query('UPDATE '.DB_PREFIX."product_collection SET status = '".(int) $data['status']."' WHERE product_collection_id = '".(int) $product_collection_id."'");

        $this->db->query('DELETE FROM '.DB_PREFIX."product_collection_products WHERE product_collection_id = '".(int) $product_collection_id."'");

        if (isset($data['product_collection_product'])) {
            foreach ($data['product_collection_product'] as $product_id) {
                $this->db->query('INSERT INTO '.DB_PREFIX."product_collection_products SET product_collection_id = '".(int) $product_collection_id."', product_id = '".(int) $product_id."'");
            }
        }

        $this->load->model('catalog/url_alias');

        $this->db->query('DELETE FROM '.DB_PREFIX."product_collection_description WHERE product_collection_id = '".(int) $product_collection_id."'");

        foreach ($data['product_collection_description'] as $language_id => $value) {
            empty($value['meta_title']) ? $value['meta_title'] = $data['name'] : $value['meta_title'];

            $this->db->query('INSERT INTO '.DB_PREFIX."product_collection_description SET product_collection_id = '".(int) $product_collection_id."', name = '".$this->db->escape($value['name'])."', language_id = '".(int) $language_id."', meta_title = '".$this->db->escape($value['meta_title'])."', meta_description = '".$this->db->escape($value['meta_description'])."', meta_keyword = '".$this->db->escape($value['meta_keyword'])."'");
        }

        foreach ($data['seo_url'] as $language_id => $value) {
            $this->db->query('DELETE FROM '.DB_PREFIX."url_alias WHERE query = 'product_collection_id=".(int) $product_collection_id."' AND language_id = '".$this->db->escape($language_id)."'");

            //$alias = empty( $value ) ? $data['product_description'][$language_id]['name'] : $value;
            $alias = empty($value) ? $data['name'] : $value;

            $alias = $this->model_catalog_url_alias->generateAlias($alias);

            if ($alias) {
                $this->db->query('INSERT INTO '.DB_PREFIX."url_alias SET query = 'product_collection_id=".(int) $product_collection_id."', keyword = '".$this->db->escape($alias)."', language_id = '".$language_id."'");
            }
        }

        $this->trigger->fire('post.admin.product_collection.edit', $product_collection_id);
    }

    public function deleteProductCollection($product_collection_id)
    {
        $this->trigger->fire('pre.admin.coupon.delete', $product_collection_id);

        $this->db->query('DELETE FROM '.DB_PREFIX."product_collection WHERE product_collection_id = '".(int) $product_collection_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."product_collection_products WHERE product_collection_id = '".(int) $product_collection_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."product_collection_description WHERE product_collection_id = '".(int) $product_collection_id."'");

        $this->db->query('DELETE FROM '.DB_PREFIX."url_alias WHERE query = 'product_collection_id=".(int) $product_collection_id."'");

        $this->trigger->fire('post.admin.coupon.delete', $product_collection_id);
    }

    public function getProductCollectionDetails($product_collection_id)
    {
        $query = $this->db->query('SELECT * from  '.DB_PREFIX."product_collection WHERE product_collection_id = '".(int) $product_collection_id."'");

        $product_collection = $query->row;

        $product_collection['seo_url'] = [];

        $query = $this->db->query('SELECT keyword, language_id FROM '.DB_PREFIX."url_alias WHERE query = 'product_collection_id=".(int) $product_collection_id."'");

        if ($query->rows) {
            foreach ($query->rows as $row) {
                $product_collection['seo_url'][$row['language_id']] = $row['keyword'];
            }
        }

        return $product_collection;
    }

    public function getProductCollectionDescriptions($product_collection_id)
    {
        $product_description_data = [];

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."product_collection_description WHERE product_collection_id = '".(int) $product_collection_id."'");

        foreach ($query->rows as $result) {
            $product_description_data[$result['language_id']] = [
                'name' => $result['name'],
                /*'description' => $result['description'],*/
                'meta_title' => $result['meta_title'],
                'meta_description' => $result['meta_description'],
                'meta_keyword' => $result['meta_keyword'],
                //'tag' => $result['tag']
            ];
        }

        return $product_description_data;
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

    public function getProductCollectionProducts($product_collection_id)
    {
        $product_collection_product_data = [];

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."product_collection_products WHERE product_collection_id = '".(int) $product_collection_id."'");

        foreach ($query->rows as $result) {
            $product_collection_product_data[] = $result['product_id'];
        }

        return $product_collection_product_data;
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

    public function getTotalProductCollection()
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX.'product_collection');

        return $query->row['total'];
    }

    public function getTotalProductCollectionFilter($data)
    {
        //$sql = ("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_collection");

        //$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "product_collection pc";
        $sql = 'SELECT count(*) as total FROM   `'.DB_PREFIX.'product_collection` pc  JOIN `'.DB_PREFIX.'product_collection_description`  pcd ON pcd.product_collection_id = pc.product_collection_id ';

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

        /*if (isset($data['filter_date_start']) && !is_null($data['filter_date_start'])) {
            $isWhere = 1;

            $_sql[] = "date_start = '" . $this->db->escape($data['filter_date_start']) . "'" ;
        }

        if (isset($data['filter_date_end']) && !is_null($data['filter_date_end'])) {
            $isWhere = 1;

            $_sql[] = "date_end = '" . $this->db->escape($data['filter_date_end']) . "'" ;
        }

        if (isset($data['filter_store']) && !is_null($data['filter_store'])) {
            $isWhere = 1;

            $_sql[] = "s.store_id = '" . $this->db->escape($data['filter_store']) . "'" ;
        }*/

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $isWhere = 1;

            $_sql[] = "pc.status = '".$this->db->escape($data['filter_status'])."'";
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
