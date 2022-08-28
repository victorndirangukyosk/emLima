<?php

class ModelInventoryInventoryWastage extends Model {

    public function getProduct($product_store_id) {
        $query = $this->db->query('SELECT DISTINCT p.*,pd.name,v.user_id as vendor_id FROM ' . DB_PREFIX . 'product_to_store p LEFT JOIN ' . DB_PREFIX . 'product_description pd ON (p.product_id = pd.product_id) LEFT JOIN ' . DB_PREFIX . 'store st ON (st.store_id = p.store_id) LEFT JOIN ' . DB_PREFIX . "user v ON (v.user_id = st.vendor_id) WHERE p.product_store_id = '" . (int) $product_store_id . "' AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "'");

        $product = $query->row;

        return $product;
    }

    public function getProductDetail($p_id) {
        $query = $this->db->query('SELECT * from ' . DB_PREFIX . "product WHERE product_id = '" . $p_id . "'");

        $product = $query->row;

        return $product;
    }

    public function getProducts($data = []) {
        $sql = 'SELECT pw.product_wastage_id,ps.product_store_id,p.product_id ,pw.wastage_qty,pw.date_added,pw.added_by,pw.cumulative_wastage,CONCAT(u1.firstname," " ,u1.lastname) as added_by_user,pd.name,p.unit,p.image,pw.avg_buying_price from ' . DB_PREFIX . 'product_wastage pw LEFT JOIN ' . DB_PREFIX . 'product_to_store ps on (pw.product_store_id = ps.product_store_id) LEFT JOIN ' . DB_PREFIX . 'product p ON (p.product_id = ps.product_id) LEFT JOIN ' . DB_PREFIX . 'product_description pd ON (p.product_id = pd.product_id) LEFT JOIN ' . DB_PREFIX . 'store st ON (st.store_id = ps.store_id) LEFT JOIN ' . DB_PREFIX . 'user v ON (v.user_id = st.vendor_id) LEFT JOIN ' . DB_PREFIX . 'user u1 ON (pw.added_by = u1.user_id)';

        $sql .= " WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

        if (!empty($data['filter_name'])) {
            $sql .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_to'])) {
            $sql .= " AND DATE_FORMAT(pw.date_added, '%Y-%m-%d') >= '" . $this->db->escape($data['filter_date_added']) . "' and DATE_FORMAT(pw.date_added, '%Y-%m-%d') <= '" . $this->db->escape($data['filter_date_added_to']) . "'";
        } else if (!empty($data['filter_date_added']) && empty($data['filter_date_added_to'])) {
            $sql .= " AND DATE_FORMAT(pw.date_added, '%Y-%m-%d') = '" . $this->db->escape($data['filter_date_added']) . "'";
        } else if (!empty($data['filter_date_added_to']) && empty($data['filter_date_added'])) {
            $sql .= "AND DATE_FORMAT(pw.date_added, '%Y-%m-%d') = '" . $this->db->escape($data['filter_date_added_to']) . "'";
        }




        if (($data['filter_group_by_date'] == 0 || $data['filter_group_by_date'] == NULL || !array_key_exists('filter_group_by_date', $data)) && !isset($data['filter_group_by_date'])) {//!array_key_exists('filter_parent_customer_id', $data)
            //group by pending based on requirement
        }



        $sort_data = [
            'pd.name',
            'pw.date_added',
            'p.product_id',
            'ps.product_store_id',
        ];

        // $sql .= ' GROUP BY ps.product_store_id';
        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
            // $sql .= ' ORDER BY pw.date_added  DESC';
            if (isset($data['order']) && ('ASC' == $data['order'])) {
                $sql .= ' ASC';
            } else {
                $sql .= ' DESC';
            }
        } else {
            // $sql .= ' ORDER BY pd.name';
            $sql .= 'ORDER BY  pw.date_added DESC ';
        }



        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        // echo $sql;die;
        // echo "<pre>";print_r($sql);die;

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalProducts($data = []) {
        $sql = 'SELECT pw.product_wastage_id from ' . DB_PREFIX . 'product_wastage pw LEFT JOIN ' . DB_PREFIX . 'product_to_store ps on (pw.product_store_id = ps.product_store_id) LEFT JOIN ' . DB_PREFIX . 'product p ON (p.product_id = ps.product_id) LEFT JOIN ' . DB_PREFIX . 'product_description pd ON (p.product_id = pd.product_id) LEFT JOIN ' . DB_PREFIX . 'store st ON (st.store_id = ps.store_id) LEFT JOIN ' . DB_PREFIX . 'user v ON (v.user_id = st.vendor_id) LEFT JOIN ' . DB_PREFIX . 'user u1 ON (pw.added_by = u1.user_id)';
        // $sql = 'SELECT Distinct product_store_id from ' . DB_PREFIX . 'product_to_store ps LEFT JOIN ' . DB_PREFIX . 'product_to_category p2c ON (ps.product_id = p2c.product_id) LEFT JOIN ' . DB_PREFIX . 'product p ON (p.product_id = ps.product_id) LEFT JOIN ' . DB_PREFIX . 'product_description pd ON (p.product_id = pd.product_id) LEFT JOIN ' . DB_PREFIX . 'store st ON (st.store_id = ps.store_id) LEFT JOIN ' . DB_PREFIX . 'user v ON (v.user_id = st.vendor_id)';
        $sql .= " WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";
        if (!empty($data['filter_name'])) {
            $sql .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }
        if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_to'])) {
            $sql .= " AND DATE_FORMAT(pw.date_added, '%Y-%m-%d') >= '" . $this->db->escape($data['filter_date_added']) . "' and DATE_FORMAT(pw.date_added, '%Y-%m-%d') <= '" . $this->db->escape($data['filter_date_added_to']) . "'";
        } else if (!empty($data['filter_date_added']) && empty($data['filter_date_added_to'])) {
            $sql .= " AND DATE_FORMAT(pw.date_added, '%Y-%m-%d') = '" . $this->db->escape($data['filter_date_added']) . "'";
        } else if (!empty($data['filter_date_added_to']) && empty($data['filter_date_added'])) {
            $sql .= "AND DATE_FORMAT(pw.date_added, '%Y-%m-%d') = '" . $this->db->escape($data['filter_date_added_to']) . "'";
        }
        if (($data['filter_group_by_date'] == 0 || $data['filter_group_by_date'] == NULL || !array_key_exists('filter_group_by_date', $data)) && !isset($data['filter_group_by_date'])) {//!array_key_exists('filter_parent_customer_id', $data)
            //group by pending based on requirement
        }


        // $sql .= ' GROUP BY ps.product_store_id';

        $query = $this->db->query($sql);

        return count($query->rows);
    }

    public function getProductCategories($product_id) {
        $product_category_data = [];

        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "product_to_category WHERE product_id = '" . (int) $product_id . "'");

        foreach ($query->rows as $result) {
            $product_category_data[] = $result['category_id'];
        }

        return $product_category_data;
    }

    public function getProductStores($product_store_id) {
        $product_store_data = [];

        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "product_to_store WHERE product_store_id = '" . (int) $product_store_id . "'");

        foreach ($query->rows as $result) {
            $product_store_data[] = $result['store_id'];
        }

        return $product_store_data;
    }

    public function getProductVariations($product_store_id) {
        $query = 'SELECT * FROM ' . DB_PREFIX . "product_to_store pv WHERE product_store_id ='" . (int) $product_store_id . "'";

        $query = $this->db->query($query);

        if (!empty($query->row['product_to_store_ids'])) {
            $all_variations = 'SELECT * FROM ' . DB_PREFIX . 'product_to_store ps LEFT JOIN ' . DB_PREFIX . 'product p ON (ps.product_id = p.product_id) WHERE product_store_id IN (' . $query->row['product_to_store_ids'] . ')';

            $result = $this->db->query($all_variations);

            return $result->rows;
        }
    }

    public function getProductStoreVariations($product_store_id) {
        $query = 'SELECT * FROM ' . DB_PREFIX . 'variation_to_product_store vps  LEFT JOIN ' . DB_PREFIX . "product_variation pv ON (vps.variation_id = pv.id)  WHERE product_store_id = '" . (int) $product_store_id . "'  ORDER BY sort_order ASC ";
        $query = $this->db->query($query);
        //$this->db->last_query();die;
        return $query->rows;
    }

    public function getStoreProducts($data = []) {
        $sql = 'SELECT ps.*,p2c.product_id,pd.name,p.*,st.name as store_name,v.firstname as fs,v.lastname as ls,ps.status as sts,v.user_id as vendor_id from ' . DB_PREFIX . 'product_to_store ps LEFT JOIN ' . DB_PREFIX . 'product_to_category p2c ON (ps.product_id = p2c.product_id) LEFT JOIN ' . DB_PREFIX . 'product p ON (p.product_id = ps.product_id) LEFT JOIN ' . DB_PREFIX . 'product_description pd ON (p.product_id = pd.product_id) LEFT JOIN ' . DB_PREFIX . 'store st ON (st.store_id = ps.store_id) LEFT JOIN ' . DB_PREFIX . 'user v ON (v.user_id = st.vendor_id)';

        $sql .= " WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";
        if (!empty($data['filter_store'])) {
            $sql .= " AND st.store_id  = '" . $this->db->escape($data['filter_store']) . "'";
        }

        if (!empty($data['filter_name'])) {
            $sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND ps.status = '" . (int) $data['filter_status'] . "'";
        }

        $sql .= ' GROUP BY ps.product_store_id';
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function updateProductWastage($vendor_product_name, $vendor_product_uom, $wastage_qty, $product_average_buying_price) {

        $this->trigger->fire('pre.admin.product.wastage', $vendor_product_name);

        $log = new Log('error.log');
        $log->write($data['wastage_qty']);

        if ($wastage_qty == null || $wastage_qty == '') {
            $data['wastage_qty'] = 0;
        }

        if ($product_average_buying_price == 'NA' || $product_average_buying_price == 'N/A') {
            $product_average_buying_price = NULL;
        }
        // echo "<pre>";print_r($product_average_buying_price);die;
        // $qty = $data['current_qty'] + ($data['procured_qty'] - $data['rejected_qty']);

        $sel_query = 'SELECT ps.product_store_id,ps.product_id,ps.quantity FROM ' . DB_PREFIX . 'product_to_store ps join ' . DB_PREFIX . "product p on ps.product_id=p.product_id  WHERE name ='" . $vendor_product_name . "' and unit='" . $vendor_product_uom . "'";
        // echo "<pre>";print_r($sel_query);die;

        $sel_query = $this->db->query($sel_query);
        $sel = $sel_query->row;

        $log = new Log('error.log');
        $log->write($sel['quantity']);
        $previous_quantity = $sel['quantity'];
        $product_general_id = $sel['product_id'];
        $product_store_id = $sel['product_store_id'];
        $current_quantity = $sel['quantity'] - $wastage_qty;
        $log->write($current_quantity);
        $log->write('wastage quantity updated');

        $query_cumm = 'SELECT sum(wastage_qty) as cummulative  FROM ' . DB_PREFIX . "product_wastage WHERE product_store_id ='" . (int) $product_store_id . "'  and date(date_added)=DATE(NOW())";
        // echo "<pre>";print_r($query_cumm);die;

        $query_cumm = $this->db->query($query_cumm);
        $cummulative = $query_cumm->row['cummulative'];

        $cummulative_current = $cummulative + $wastage_qty;

        // echo "<pre>";print_r('INSERT INTO ' . DB_PREFIX . "product_wastage SET product_id = '" . $product_general_id . "', product_store_id = '" . $product_store_id . "',  wastage_qty = '" . $wastage_qty . "',  added_by = '" . $this->user->getId() . "', added_user_role = '" . $this->user->getGroupName() . "', date_added = '" . $this->db->escape(date('Y-m-d H:i:s')) . "',cumulative_wastage='".$cummulative_current."',avg_buying_price='".$product_average_buying_price."'");die;




        $query = 'UPDATE ' . DB_PREFIX . "product_to_store SET quantity = '" . $current_quantity . "' WHERE product_store_id = '" . (int) $product_store_id . "'";
        //echo $query;
        $this->db->query($query);

        $log->write($data['wastage_qty']);
        $log->write('product_to_store data modified with wastage quantity');

        if ($product_average_buying_price == NULL || $product_average_buying_price == '') {


            $this->db->query('INSERT INTO ' . DB_PREFIX . "product_wastage SET product_id = '" . $product_general_id . "', product_store_id = '" . $product_store_id . "',  wastage_qty = '" . $wastage_qty . "',  added_by = '" . $this->user->getId() . "', added_user_role = '" . $this->user->getGroupName() . "', date_added = '" . $this->db->escape(date('Y-m-d H:i:s')) . "',cumulative_wastage='" . $cummulative_current . "'");
        } else {


            $this->db->query('INSERT INTO ' . DB_PREFIX . "product_wastage SET product_id = '" . $product_general_id . "', product_store_id = '" . $product_store_id . "',  wastage_qty = '" . $wastage_qty . "',  added_by = '" . $this->user->getId() . "', added_user_role = '" . $this->user->getGroupName() . "', date_added = '" . $this->db->escape(date('Y-m-d H:i:s')) . "',cumulative_wastage='" . $cummulative_current . "',avg_buying_price='" . $product_average_buying_price . "'");
        }

        $this->trigger->fire('post.admin.product.wastage', $product_store_id);

        return $this->db->getLastId();
    }

    public function productInventoryWastage($store_product_id) {
        $query = 'SELECT * FROM ' . DB_PREFIX . "product_inventory_wastage WHERE product_store_id ='" . (int) $store_product_id . "' order by date_added desc LIMIT 5";
        $query = $this->db->query($query);

        return $query->rows;
    }

    public function getTotalProductInventoryWastage($data = []) {
        $sql = 'SELECT COUNT(*) AS total FROM ' . DB_PREFIX . 'product_inventory_wastage';

        $implode = [];

        if (!empty($data['filter_store_id'])) {
            $implode[] = "product_store_id = '" . $this->db->escape($data['filter_store_id']) . "'";
        }

        if (!empty($data['filter_date_added']) && empty($data['filter_date_added_end'])) {
            $implode[] = "DATE_FORMAT(date_added, '%Y-%m-%d') = '" . $this->db->escape($data['filter_date_added']) . "'";
        }

        if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_end'])) {
            $implode[] = "DATE_FORMAT(date_added, '%Y-%m-%d') BETWEEN DATE('" . $this->db->escape($data['filter_date_added']) . "') AND DATE('" . $this->db->escape($data['filter_date_added_end']) . "')";
        }

        if (!empty($data['filter_name'])) {
            $implode[] = "product_name = '" . $this->db->escape($data['filter_name']) . "'";
        }

        if ($implode) {
            $sql .= ' WHERE ' . implode(' AND ', $implode);
        }

        $query = $this->db->query($sql);

        //echo "<pre>";print_r($sql);die;

        return $query->row['total'];
    }

    public function getProductInventoryWastage($data = []) {
        $sql = "SELECT * FROM " . DB_PREFIX . 'product_inventory_wastage';

        $implode = [];

        if (!empty($data['filter_store_id'])) {
            $implode[] = "product_store_id = '" . $this->db->escape($data['filter_store_id']) . "'";
        }

        if (!empty($data['filter_date_added']) && empty($data['filter_date_added_end'])) {
            $implode[] = "DATE_FORMAT(date_added, '%Y-%m-%d') = '" . $this->db->escape($data['filter_date_added']) . "'";
        }

        if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_end'])) {
            $implode[] = "DATE_FORMAT(date_added, '%Y-%m-%d') BETWEEN DATE('" . $this->db->escape($data['filter_date_added']) . "') AND DATE('" . $this->db->escape($data['filter_date_added_end']) . "')";
        }

        if (!empty($data['filter_product_history_id'])) {
            $implode[] = "product_history_id = '" . $this->db->escape($data['filter_product_history_id']) . "'";
        }

        if ($implode) {
            $sql .= ' WHERE ' . implode(' AND ', $implode);
        }

        $sort_data = [
            'product_store_id',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY date_added';
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

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        $query = $this->db->query($sql);
        // echo "<pre>";print_r($sql);die;

        return $query->rows;
    }

    public function getProductsByGroup($data = []) {
        $sql = 'SELECT ps.product_store_id,p.product_id ,Sum(pw.wastage_qty) as wastage_qty, pd.name,p.unit,date(pw.date_added) as date_added,avg(pw.avg_buying_price) as avg_buying_price  from ' . DB_PREFIX . 'product_wastage pw LEFT JOIN ' . DB_PREFIX . 'product_to_store ps on (pw.product_store_id = ps.product_store_id) LEFT JOIN ' . DB_PREFIX . 'product p ON (p.product_id = ps.product_id) LEFT JOIN ' . DB_PREFIX . 'product_description pd ON (p.product_id = pd.product_id) LEFT JOIN ' . DB_PREFIX . 'store st ON (st.store_id = ps.store_id) LEFT JOIN ' . DB_PREFIX . 'user v ON (v.user_id = st.vendor_id) LEFT JOIN ' . DB_PREFIX . 'user u1 ON (pw.added_by = u1.user_id)';

        $sql .= " WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

        if (!empty($data['filter_name'])) {
            $sql .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_to'])) {
            $sql .= " AND DATE_FORMAT(pw.date_added, '%Y-%m-%d') >= '" . $this->db->escape($data['filter_date_added']) . "' and DATE_FORMAT(pw.date_added, '%Y-%m-%d') <= '" . $this->db->escape($data['filter_date_added_to']) . "'";
        } else if (!empty($data['filter_date_added']) && empty($data['filter_date_added_to'])) {
            $sql .= " AND DATE_FORMAT(pw.date_added, '%Y-%m-%d') = '" . $this->db->escape($data['filter_date_added']) . "'";
        } else if (!empty($data['filter_date_added_to']) && empty($data['filter_date_added'])) {
            $sql .= "AND DATE_FORMAT(pw.date_added, '%Y-%m-%d') = '" . $this->db->escape($data['filter_date_added_to']) . "'";
        }


        if ($data['filter_group_by_date'] == 1) {//!array_key_exists('filter_parent_customer_id', $data)
            $sql .= " GROUP BY pw.product_store_id , DATE_FORMAT(pw.date_added, '%Y-%m-%d')";
        } else {
            $sql .= " GROUP BY pw.product_store_id";
        }



        $sort_data = [
            'pd.name',
            'p.product_id',
            'ps.product_store_id',
        ];

        // $sql .= ' GROUP BY ps.product_store_id';
        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            // $sql .= ' ORDER BY ' . $data['sort'];
            $sql .= ' ORDER BY pw.date_added  DESC';
        } else {
            // $sql .= ' ORDER BY pd.name';
            $sql .= ' ORDER BY  pw.date_added DESC ';
        }

        // if (isset($data['order']) && ('ASC' == $data['order'])) {
        //     $sql .= ' ASC';
        // } else {
        //     $sql .= ' DESC';
        // }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        // echo $sql;die;
        // echo "<pre>";print_r($sql);die;

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalProductsByGroup($data = []) {
        $sql = 'SELECT   pw.product_store_id from ' . DB_PREFIX . 'product_wastage pw LEFT JOIN ' . DB_PREFIX . 'product_to_store ps on (pw.product_store_id = ps.product_store_id) LEFT JOIN ' . DB_PREFIX . 'product p ON (p.product_id = ps.product_id) LEFT JOIN ' . DB_PREFIX . 'product_description pd ON (p.product_id = pd.product_id) LEFT JOIN ' . DB_PREFIX . 'store st ON (st.store_id = ps.store_id) LEFT JOIN ' . DB_PREFIX . 'user v ON (v.user_id = st.vendor_id) LEFT JOIN ' . DB_PREFIX . 'user u1 ON (pw.added_by = u1.user_id)';
        // $sql = 'SELECT Distinct product_store_id from ' . DB_PREFIX . 'product_to_store ps LEFT JOIN ' . DB_PREFIX . 'product_to_category p2c ON (ps.product_id = p2c.product_id) LEFT JOIN ' . DB_PREFIX . 'product p ON (p.product_id = ps.product_id) LEFT JOIN ' . DB_PREFIX . 'product_description pd ON (p.product_id = pd.product_id) LEFT JOIN ' . DB_PREFIX . 'store st ON (st.store_id = ps.store_id) LEFT JOIN ' . DB_PREFIX . 'user v ON (v.user_id = st.vendor_id)';
        $sql .= " WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";
        if (!empty($data['filter_name'])) {
            $sql .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }
        if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_to'])) {
            $sql .= " AND DATE_FORMAT(pw.date_added, '%Y-%m-%d') >= '" . $this->db->escape($data['filter_date_added']) . "' and DATE_FORMAT(pw.date_added, '%Y-%m-%d') <= '" . $this->db->escape($data['filter_date_added_to']) . "'";
        } else if (!empty($data['filter_date_added']) && empty($data['filter_date_added_to'])) {
            $sql .= " AND DATE_FORMAT(pw.date_added, '%Y-%m-%d') = '" . $this->db->escape($data['filter_date_added']) . "'";
        } else if (!empty($data['filter_date_added_to']) && empty($data['filter_date_added'])) {
            $sql .= "AND DATE_FORMAT(pw.date_added, '%Y-%m-%d') = '" . $this->db->escape($data['filter_date_added_to']) . "'";
        }
        if ($data['filter_group_by_date'] == 1) {//!array_key_exists('filter_parent_customer_id', $data)
            $sql .= " GROUP BY pw.product_store_id , DATE_FORMAT(pw.date_added, '%Y-%m-%d')";
        } else {
            $sql .= " GROUP BY pw.product_store_id";
        }


        // $sql .= ' GROUP BY ps.product_store_id';

        $query = $this->db->query($sql);

        return count($query->rows);
    }

    public function updateProductWastage_Edit($product_wastage_id, $vendor_product_name, $vendor_product_uom, $wastage_qty, $cumulative_wastage, $date_added_date) {

        $this->trigger->fire('pre.admin.product.wastage', $vendor_product_name);

        $log = new Log('error.log');
        $log->write("Wastage quantity Edit");

        if ($wastage_qty == null || $wastage_qty == '') {
            $wastage_qty = 0;
        }
        // $log->write($wastage_qty);

        $sel_query = 'SELECT ps.product_store_id,ps.product_id,ps.quantity FROM ' . DB_PREFIX . 'product_to_store ps join ' . DB_PREFIX . "product p on ps.product_id=p.product_id  WHERE name ='" . $vendor_product_name . "' and unit='" . $vendor_product_uom . "'";
        // echo "<pre>";print_r($sel_query);die;
        $sel_query = $this->db->query($sel_query);
        $sel = $sel_query->row;

        $product_general_id = $sel['product_id'];
        $product_store_id = $sel['product_store_id'];
        $product_quantity = $sel['quantity'];

        $wastage_prev = 'SELECT *  FROM ' . DB_PREFIX . "product_wastage WHERE product_wastage_id ='" . (int) $product_wastage_id . "' ";
        // echo "<pre>";print_r($wastage_prev);die;
        $wastage_prev = $this->db->query($wastage_prev);
        $wastage_prev_data = $wastage_prev->row;

        $prev_product_quantity = 'SELECT ps.quantity FROM ' . DB_PREFIX . 'product_to_store ps join ' . DB_PREFIX . "product p on ps.product_id=p.product_id  WHERE ps.product_store_id ='" . $wastage_prev_data['product_store_id'] . "'";
        // echo "<pre>";print_r($sel_query);die;
        $prev_product_quantity = $this->db->query($prev_product_quantity);
        $prev_product_quantity_data = $prev_product_quantity->row;

        // $log->write('wastage data prev');

        $prev_product_current_quantity = ($prev_product_quantity_data['quantity'] ?? 0) + $wastage_prev_data['wastage_qty'];
        $product_store_id_prev = $wastage_prev_data['product_store_id'];

        $log->write('product to store id prev');

        if ($product_store_id_prev == $product_store_id) {
            $current_product_current_quantity = ($prev_product_current_quantity - $wastage_qty);
        } else {
            $current_product_current_quantity = ($product_quantity - $wastage_qty);
        }

        $log->write($product_store_id_prev);
        $log->write($prev_product_quantity_data['quantity']);
        $log->write($wastage_qty);
        $log->write($prev_product_current_quantity);

        $log->write('current_quantity_final');

        $log->write($product_store_id);
        $log->write($product_quantity);
        $log->write($wastage_qty);
        $log->write($current_product_current_quantity);

        $this->db->query('Update ' . DB_PREFIX . "product_wastage SET product_id = '" . $product_general_id . "', product_store_id = '" . $product_store_id . "',  wastage_qty = '" . $wastage_qty . "',  modified_by = '" . $this->user->getId() . "', date_modified = '" . $this->db->escape(date('Y-m-d H:i:s')) . "',cumulative_wastage='" . $cumulative_wastage . "',date_added = '" . $date_added_date . "' where product_wastage_id='" . $product_wastage_id . "'");
        $log->write('wastage data updated');

        //delete previous updated quantity
        $query_update1 = 'UPDATE ' . DB_PREFIX . "product_to_store SET quantity = '" . $prev_product_current_quantity . "' WHERE product_store_id = '" . (int) $product_store_id_prev . "'";
        //echo $query;
        $this->db->query($query_update1);
        $log->write($current_quantity);
        $log->write('current quantity');
        $log->write($product_store_id_prev);

        //update current quantity
        $query_update2 = 'UPDATE ' . DB_PREFIX . "product_to_store SET quantity = '" . $current_product_current_quantity . "' WHERE product_store_id = '" . (int) $product_store_id . "'";
        //echo $query;

        $log->write($current_quantity_final);
        $log->write('current quantity final');
        $log->write($product_store_id);

        $this->db->query($query_update2);

        $log->write('product_to_store data modified with wastage quantity');

        $this->trigger->fire('post.admin.product.wastage', $product_store_id);

        return $this->db->getLastId();
    }

    public function getAverageBuyingPrice($filter_name, $filter_product_uom, $date) {
        // echo '<pre>';print_r('SELECT avg(i.buying_price) as price from ' . DB_PREFIX . "product_inventory_price_history i join hf7_product p on i.product_id=p.product_id  WHERE p.name = '" . $filter_name . "' and p.unit='".filter_product_uom."' and i.date_added <='".$date."' and i.date_added>=DATE_ADD('".$date."' , INTERVAL -500 DAY)");die;
        // echo '<pre>';print_r('SELECT s.buying_price as price from ' . DB_PREFIX . "product_to_store s join hf7_product p on i.product_id=p.product_id  WHERE p.name = '" . $filter_name . "' and p.unit='".$filter_product_uom."'");die;
        $query = $this->db->query('SELECT avg(i.buying_price) as price from ' . DB_PREFIX . "product_inventory_price_history i join hf7_product p on i.product_id=p.product_id  WHERE p.name = '" . $filter_name . "' and i.buying_price > 0  and p.unit='" . $filter_product_uom . "' and i.date_added <='" . $date . "' and i.date_added>=DATE_ADD('" . $date . "' , INTERVAL -10 DAY)");
        // echo $query;die;

        $avg_price = $query->row['price'];
        if ($avg_price == null || $avg_price == '' || $avg_price == 0) {
            $query1 = $this->db->query('SELECT s.buying_price as price from ' . DB_PREFIX . "product_to_store s join hf7_product p on s.product_id=p.product_id  WHERE p.name = '" . $filter_name . "' and p.unit='" . $filter_product_uom . "'");
            $avg_price = $query1->row['price'] ?? 'NA';
        }

        return $avg_price;
    }

    public function getProductAverageBuyingPrice($data = []) {
        $sql = "SELECT AVG(buying_price) AS average_buying_price FROM " . DB_PREFIX . 'product_inventory_history';

        $implode = [];

        if (!empty($data['filter_date_added']) && empty($data['filter_date_added_end'])) {
            $implode[] = "DATE_FORMAT(date_added, '%Y-%m-%d') = '" . $this->db->escape($data['filter_date_added']) . "'";
        }

        if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_end'])) {
            $implode[] = "DATE_FORMAT(date_added, '%Y-%m-%d') BETWEEN DATE('" . $this->db->escape($data['filter_date_added']) . "') AND DATE('" . $this->db->escape($data['filter_date_added_end']) . "')";
        }

        if (!empty($data['filter_product_store_id'])) {
            $implode[] = "product_store_id = '" . $this->db->escape($data['filter_product_store_id']) . "'";
        }

        if ($implode) {
            $sql .= ' WHERE ' . implode(' AND ', $implode);
        }

        $query = $this->db->query($sql);
        // echo "<pre>";print_r($sql);die;

        return $query->row;
    }

}
