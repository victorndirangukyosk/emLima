<?php

class ModelApprovalsProduct extends Model
{
    public function approveProduct($product_id)
    {
        $this->db->query('update `'.DB_PREFIX.'product` SET status=1 WHERE product_id = "'.$product_id.'"');
    }

    public function deleteProduct($product_id)
    {
        $this->trigger->fire('pre.admin.product.delete', $product_id);

        $this->cache->delete('product');

        $this->trigger->fire('post.admin.product.delete', $product_id);
    }

    public function getProduct($product_id)
    {
        $query = $this->db->query('SELECT DISTINCT * FROM '.DB_PREFIX.'product p LEFT JOIN '.DB_PREFIX."product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '".(int) $product_id."' AND pd.language_id = '".(int) $this->config->get('config_language_id')."'");

        $product = $query->row;

        return $product;
    }

    public function getTotalProductsStore($data = [])
    {
        $sql = 'SELECT Distinct st.store_id  from '.DB_PREFIX.'product_to_store ps LEFT JOIN '.DB_PREFIX.'store st ON (st.store_id = ps.store_id)';
        $sql .= ' WHERE ps.status=0';

        if (!empty($data['filter_name'])) {
            $sql .= " AND st.name LIKE '".$this->db->escape($data['filter_name'])."%'";
        }
        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND st.status = '".(int) $data['filter_status']."'";
        }
        //$sql .= ' GROUP BY st.store_id';
        $query = $this->db->query($sql);

        return count($query->rows);
    }

    public function getProductsStore($data = [])
    {
        $sql = 'SELECT Distinct st.store_id ,st.store_id as s_id,st.name,st.address,st.status from '.DB_PREFIX.'product_to_store ps LEFT JOIN '.DB_PREFIX.'store st ON (st.store_id = ps.store_id)';
        $sql .= ' WHERE ps.status=0';
        //$sql .= ' GROUP BY st.store_id';
        if (!empty($data['filter_store_id'])) {
            $sql .= " AND st.store_id = '".$this->db->escape($data['filter_store_id'])."'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND st.status = '".(int) $data['filter_status']."'";
        }
        //echo $sql;die;
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalProductsByStore($store_id)
    {
        $sql = 'SELECT count(*) as tot from '.DB_PREFIX.'product_to_store ps';
        $sql .= " WHERE ps.status=0 AND store_id = '".$store_id."'";
        $query = $this->db->query($sql);

        return $query->row['tot'];
    }

    public function getProducts($data = [])
    {
        $sql = 'SELECT ps.*,p2c.product_id,pd.name,p.*,st.name as store_name,v.firstname as fs,v.lastname as ls,ps.status as sts from '.DB_PREFIX.'product_to_store ps LEFT JOIN '.DB_PREFIX.'product_to_category p2c ON (ps.product_id = p2c.product_id) LEFT JOIN '.DB_PREFIX.'product p ON (p.product_id = ps.product_id) LEFT JOIN '.DB_PREFIX.'product_description pd ON (p.product_id = pd.product_id) LEFT JOIN '.DB_PREFIX.'store st ON (st.store_id = ps.store_id) LEFT JOIN '.DB_PREFIX.'user v ON (v.user_id = st.vendor_id)';

        $sql .= " WHERE pd.language_id = '".(int) $this->config->get('config_language_id')."' AND ps.status=0";
        if (!empty($data['filter_store_id'])) {
            $sql .= " AND st.store_id = '".$this->db->escape($data['filter_store_id'])."'";
        }

        if (!empty($data['filter_vendor_name'])) {
            $sql .= " AND v.firstname LIKE '".$this->db->escape($data['filter_vendor_name'])."%'";
            $sql .= " OR v.lastname LIKE '".$this->db->escape($data['filter_vendor_name'])."%'";
        }

        if (!empty($data['filter_name'])) {
            $sql .= " AND pd.name LIKE '".$this->db->escape($data['filter_name'])."%'";
        }

        if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
            $sql .= " AND ps.price LIKE '".$this->db->escape($data['filter_price'])."%'";
        }

        if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
            $sql .= " AND p2c.category_id = '".$this->db->escape($data['filter_category'])."'";
        }

        if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
            $lGroup = false;
            $sql .= " AND p2c.category_id = '".$this->db->escape($data['filter_category'])."'";
        } else {
            $lGroup = true;
        }

        if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
            $sql .= " AND ps.quantity = '".(int) $data['filter_quantity']."'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND ps.status = '".(int) $data['filter_status']."'";
        }

        $sort_data = [
            'pd.name',
            'p.price',
            'p2c.category_id',
            'p.quantity',
            'ps.status', ];
        $sql .= ' GROUP BY ps.product_store_id';
        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY '.$data['sort'];
        } else {
            $sql .= ' ORDER BY pd.name';
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

        // echo $sql;die;
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalProducts($data = [])
    {
        $sql = 'SELECT ps.*,p2c.product_id,pd.name,p.*,st.name as store_name,v.firstname as fs,v.lastname as ls,ps.status as sts from '.DB_PREFIX.'product_to_store ps LEFT JOIN '.DB_PREFIX.'product_to_category p2c ON (ps.product_id = p2c.product_id) LEFT JOIN '.DB_PREFIX.'product p ON (p.product_id = ps.product_id) LEFT JOIN '.DB_PREFIX.'product_description pd ON (p.product_id = pd.product_id) LEFT JOIN '.DB_PREFIX.'store st ON (st.store_id = ps.store_id) LEFT JOIN '.DB_PREFIX.'user v ON (v.user_id = st.vendor_id)';

        $sql .= " WHERE pd.language_id = '".(int) $this->config->get('config_language_id')."' AND ps.status=0";
        if (!empty($data['filter_store_id'])) {
            $sql .= " AND st.store_id = '".$this->db->escape($data['filter_store_id'])."'";
        }

        if (!empty($data['filter_vendor_name'])) {
            $sql .= " AND v.firstname LIKE '".$this->db->escape($data['filter_vendor_name'])."%'";
            $sql .= " OR v.lastname LIKE '".$this->db->escape($data['filter_vendor_name'])."%'";
        }

        if (!empty($data['filter_name'])) {
            $sql .= " AND pd.name LIKE '".$this->db->escape($data['filter_name'])."%'";
        }

        if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
            $sql .= " AND ps.price LIKE '".$this->db->escape($data['filter_price'])."%'";
        }

        if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
            $sql .= " AND p2c.category_id = '".$this->db->escape($data['filter_category'])."'";
        }

        if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
            $lGroup = false;
            $sql .= " AND p2c.category_id = '".$this->db->escape($data['filter_category'])."'";
        } else {
            $lGroup = true;
        }

        if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
            $sql .= " AND ps.quantity = '".(int) $data['filter_quantity']."'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND ps.status = '".(int) $data['filter_status']."'";
        }
        $sort_data = [
            'pd.name',
            'p.price',
            'p2c.category_id',
            'p.quantity',
            'ps.status', ];
        $sql .= ' GROUP BY ps.product_store_id';
        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY '.$data['sort'];
        } else {
            $sql .= ' ORDER BY pd.name';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        $query = $this->db->query($sql);

        //echo count($query->rows );die;
        return count($query->rows);
    }

    public function getProductsByCategoryId($category_id)
    {
        $sql = 'SELECT * FROM '.DB_PREFIX.'product p LEFT JOIN '.DB_PREFIX.'product_description pd ON (p.product_id = pd.product_id) LEFT JOIN '.DB_PREFIX.'product_to_category p2c ON (p.product_id = p2c.product_id) WHERE ';
        $sql .= " pd.language_id = '".(int) $this->config->get('config_language_id')."'";

        $sql .= ' AND p.vendor_id!="0" AND p.status = "0"';

        $sql .= " AND p2c.category_id = '".(int) $category_id."' ORDER BY pd.name ASC";
        $query = $this->db->query();

        return $query->rows;
    }

    public function getProductCategories($product_id)
    {
        $product_category_data = [];

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."product_to_category WHERE product_id = '".(int) $product_id."'");

        foreach ($query->rows as $result) {
            $product_category_data[] = $result['category_id'];
        }

        return $product_category_data;
    }

    public function getProductStores($product_store_id)
    {
        $product_store_data = [];

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."product_to_store WHERE product_store_id = '".(int) $product_store_id."'");

        foreach ($query->rows as $result) {
            $product_store_data[] = $result['store_id'];
        }

        return $product_store_data;
    }
}
