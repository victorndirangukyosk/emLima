<?php

class ModelCatalogVendorProduct extends Model {

    public function addProduct($data) {
        $this->trigger->fire('pre.admin.product.add', $data);

        if ($this->user->isVendor()) {
            $data['status'] = $this->config->get('config_auto_approval_product');
        }

        $monday = in_array("monday", $data['product_delivery']) ? 1 : 0;
        $tuesday = in_array("tuesday", $data['product_delivery']) ? 1 : 0;
        $wednesday = in_array("wednesday", $data['product_delivery']) ? 1 : 0;
        $thursday = in_array("thursday", $data['product_delivery']) ? 1 : 0;
        $friday = in_array("friday", $data['product_delivery']) ? 1 : 0;
        $saturday = in_array("saturday", $data['product_delivery']) ? 1 : 0;
        $sunday = in_array("sunday", $data['product_delivery']) ? 1 : 0;

        $this->db->query('INSERT INTO ' . DB_PREFIX . "product_to_store SET  product_id = '" . $data['product_id'] . "', store_id = '" . $this->db->escape($data['product_store']) . "', merchant_id = '" . $this->db->escape($data['merchant_id']) . "', price = '" . $data['price'] . "',special_price = '" . $data['special_price'] . "',tax_percentage = '" . $data['tax_percentage'] . "',quantity = '" . $data['quantity'] . "',min_quantity = '" . $data['min_quantity'] . "',subtract_quantity = '" . $data['subtract_quantity'] . "',status = '" . $data['status'] . "',tax_class_id = '" . $data['tax_class_id'] . "', monday = '" . $monday . "', tuesday = '" . $tuesday . "', wednesday = '" . $wednesday . "', thursday = '" . $thursday . "', friday = '" . $friday . "', saturday = '" . $saturday . "', sunday = '" . $sunday . "'");
        $product_store_id = $this->db->getLastId();

        foreach ($data['product_variation']['variation'] as $prv => $value) {
            $this->db->query('INSERT INTO ' . DB_PREFIX . "variation_to_product_store SET  variation_id = '" . $value . "', product_store_id = '" . $product_store_id . "', price = '" . $data['product_variation']['price'][$prv] . "',special_price = '" . $data['product_variation']['special_price'][$prv] . "'");
        }

        $this->cache->delete('product');

        $this->trigger->fire('post.admin.product.add', $product_id);

        return $product_id;
    }

    public function editProduct($store_product_id, $data) {
        $this->trigger->fire('pre.admin.product.edit', $data);

        /* if ($this->user->isVendor() && !$this->config->get('config_auto_approval_product')) {
          $data['status'] = $this->config->get('config_auto_approval_product');
          } */

        $monday = in_array("monday", $data['product_delivery']) ? 1 : 0;
        $tuesday = in_array("tuesday", $data['product_delivery']) ? 1 : 0;
        $wednesday = in_array("wednesday", $data['product_delivery']) ? 1 : 0;
        $thursday = in_array("thursday", $data['product_delivery']) ? 1 : 0;
        $friday = in_array("friday", $data['product_delivery']) ? 1 : 0;
        $saturday = in_array("saturday", $data['product_delivery']) ? 1 : 0;
        $sunday = in_array("sunday", $data['product_delivery']) ? 1 : 0;

        $query = 'UPDATE ' . DB_PREFIX . "product_to_store SET product_id = '" . $data['product_id'] . "', store_id = '" . $this->db->escape($data['product_store']) . "', merchant_id = '" . $data['merchant_id'] . "', price = '" . $data['price'] . "',special_price = '" . $data['special_price'] . "',tax_percentage = '" . $data['tax_percentage'] . "',quantity = '" . $data['quantity'] . "',min_quantity = '" . $data['min_quantity'] . "',subtract_quantity = '" . $data['subtract_quantity'] . "',status = '" . $data['status'] . "',tax_class_id = '" . $data['tax_class_id'] . "', monday = '" . $monday . "', tuesday = '" . $tuesday . "', wednesday = '" . $wednesday . "', thursday = '" . $thursday . "', friday = '" . $friday . "', saturday = '" . $saturday . "', sunday = '" . $sunday . "' WHERE product_store_id = '" . (int) $store_product_id . "'";

        $this->db->query($query);

        //  delete variation here
        $this->db->query('DELETE FROM ' . DB_PREFIX . "variation_to_product_store WHERE product_store_id = '" . (int) $store_product_id . "'");
        // insert variation
        if (isset($this->request->post['product_variation']['variation'])) {
            foreach ($this->request->post['product_variation']['variation'] as $prv => $value) {
                $this->db->query('INSERT INTO ' . DB_PREFIX . "variation_to_product_store SET  variation_id = '" . $value . "', product_store_id = '" . $store_product_id . "', price = '" . $this->request->post['product_variation']['price'][$prv] . "',special_price = '" . $this->request->post['product_variation']['special_price'][$prv] . "'");
            }
        }
        $this->trigger->fire('post.admin.product.edit', $store_product_id);

        return $product_id;
    }

    public function enabledisablevendorproducts($store_product_id, $status) {
        $query = $this->db->query('SELECT DISTINCT p.*,pd.name,v.user_id as vendor_id FROM ' . DB_PREFIX . 'product_to_store p LEFT JOIN ' . DB_PREFIX . 'product_description pd ON (p.product_id = pd.product_id) LEFT JOIN ' . DB_PREFIX . 'store st ON (st.store_id = p.store_id) LEFT JOIN ' . DB_PREFIX . "user v ON (v.user_id = st.vendor_id) WHERE p.product_store_id = '" . (int) $store_product_id . "' AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "'");

        $data = $query->row;
        $log = new Log('error.log');
        $log->write('enabledisablevendorproducts');
        $log->write($data);
        $log->write('enabledisablevendorproducts');

        $this->trigger->fire('pre.admin.product.edit', $data);

        /* if ($this->user->isVendor() && !$this->config->get('config_auto_approval_product')) {
          $data['status'] = $this->config->get('config_auto_approval_product');
          } */

        $query = 'UPDATE ' . DB_PREFIX . "product_to_store SET status = '" . $status . "' WHERE product_store_id = '" . (int) $store_product_id . "'";

        $this->db->query($query);

        //  delete variation here
        $this->db->query('DELETE FROM ' . DB_PREFIX . "variation_to_product_store WHERE product_store_id = '" . (int) $store_product_id . "'");
        // insert variation
        if (isset($this->request->post['product_variation']['variation'])) {
            foreach ($this->request->post['product_variation']['variation'] as $prv => $value) {
                $this->db->query('INSERT INTO ' . DB_PREFIX . "variation_to_product_store SET  variation_id = '" . $value . "', product_store_id = '" . $store_product_id . "', price = '" . $this->request->post['product_variation']['price'][$prv] . "',special_price = '" . $this->request->post['product_variation']['special_price'][$prv] . "'");
            }
        }
        $this->trigger->fire('post.admin.product.edit', $store_product_id);

        return $product_id;
    }

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
        $sql = 'SELECT ps.*,p2c.product_id,pd.name as product_name ,p.*,st.name as store_name,v.firstname as fs,v.lastname as ls,ps.status as sts,v.user_id as vendor_id from ' . DB_PREFIX . 'product_to_store ps LEFT JOIN ' . DB_PREFIX . 'product_to_category p2c ON (ps.product_id = p2c.product_id) LEFT JOIN ' . DB_PREFIX . 'product p ON (p.product_id = ps.product_id) LEFT JOIN ' . DB_PREFIX . 'product_description pd ON (p.product_id = pd.product_id) LEFT JOIN ' . DB_PREFIX . 'store st ON (st.store_id = ps.store_id) LEFT JOIN ' . DB_PREFIX . 'user v ON (v.user_id = st.vendor_id)';

        $sql .= " WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

        if (!empty($data['filter_store_id'])) {
            $sql .= " AND st.name LIKE '" . $this->db->escape($data['filter_store_id']) . "%'";
        }

        if ($this->user->isVendor()) {
            $sql .= ' AND v.user_id="' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_vendor_name'])) {
            $sql .= ' AND v.user_id="' . $this->db->escape($data['filter_vendor_name']) . '"';
            /* $sql .= " AND v.firstname LIKE '" . $this->db->escape($data['filter_vendor_name']) . "%'";
              $sql .= " OR v.lastname LIKE '" . $this->db->escape($data['filter_vendor_name']) . "%'"; */
        }

        if (!empty($data['filter_model'])) {
            $sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
        }

        if (!empty($data['filter_name'])) {
            $sql .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
            if (!$data['filter_price']) {
                $sql .= " AND ps.price = '" . $this->db->escape($data['filter_price']) . "'";
            } else {
                $sql .= " AND (ps.price = '" . $this->db->escape($data['filter_price']) . "' or ps.special_price = '" . $this->db->escape($data['filter_price']) . "' )";
            }
        }

        if (!empty($data['filter_product_id_from'])) {
            $sql .= " AND ps.product_store_id >= '" . (int) $data['filter_product_id_from'] . "'";
        }

        if (!empty($data['filter_category_price_prods'])) {
            $filter_category_price_prods = implode(',', $data['filter_category_price_prods']);
            $sql .= ' AND ps.product_store_id IN (' . $filter_category_price_prods . ') ';
        }

        if (!empty($data['filter_product_id_to'])) {
            $sql .= " AND ps.product_store_id <= '" . (int) $data['filter_product_id_to'] . "'";
        }

        if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
            $sql .= " AND p2c.category_id = '" . $this->db->escape($data['filter_category']) . "'";
        }

        if (isset($data['filter_tax_class_id']) && !is_null($data['filter_tax_class_id'])) {
            $sql .= " AND ps.tax_class_id = '" . (int) $data['filter_tax_class_id'] . "'";
        }

        if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
            $lGroup = false;
            $sql .= " AND p2c.category_id = '" . $this->db->escape($data['filter_category']) . "'";
        } else {
            $lGroup = true;
        }

        if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
            if (0 == $data['filter_quantity']) {
                $sql .= " AND ps.quantity = '" . (int) $data['filter_quantity'] . "'";
            } else {
                $sql .= " AND ps.quantity <= '" . (int) $data['filter_quantity'] . "' AND ps.quantity > '0'";
            }
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND ps.status = '" . (int) $data['filter_status'] . "'";
        }

        $sort_data = [
            'pd.name',
            'p.price',
            'p.product_id',
            'ps.product_store_id',
            'p2c.category_id',
            'ps.quantity',
            'p.model',
            'ps.status',
            'st.name',
        ];

        $sql .= ' GROUP BY ps.product_store_id';
        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
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

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        // echo $sql;die;
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getProductsCount($data = []) {
        $sql = 'SELECT ps.*,p2c.product_id,pd.name as product_name ,p.*,st.name as store_name,v.firstname as fs,v.lastname as ls,ps.status as sts,v.user_id as vendor_id from ' . DB_PREFIX . 'product_to_store ps LEFT JOIN ' . DB_PREFIX . 'product_to_category p2c ON (ps.product_id = p2c.product_id) LEFT JOIN ' . DB_PREFIX . 'product p ON (p.product_id = ps.product_id) LEFT JOIN ' . DB_PREFIX . 'product_description pd ON (p.product_id = pd.product_id) LEFT JOIN ' . DB_PREFIX . 'store st ON (st.store_id = ps.store_id) LEFT JOIN ' . DB_PREFIX . 'user v ON (v.user_id = st.vendor_id)';

        $sql .= " WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

        if (!empty($data['filter_store_id'])) {
            $sql .= " AND st.name LIKE '" . $this->db->escape($data['filter_store_id']) . "%'";
        }

        if ($this->user->isVendor()) {
            $sql .= ' AND v.user_id="' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_vendor_name'])) {
            $sql .= ' AND v.user_id="' . $this->db->escape($data['filter_vendor_name']) . '"';
            /* $sql .= " AND v.firstname LIKE '" . $this->db->escape($data['filter_vendor_name']) . "%'";
              $sql .= " OR v.lastname LIKE '" . $this->db->escape($data['filter_vendor_name']) . "%'"; */
        }

        if (!empty($data['filter_model'])) {
            $sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
        }

        if (!empty($data['filter_name'])) {
            $sql .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
            if (!$data['filter_price']) {
                $sql .= " AND ps.price = '" . $this->db->escape($data['filter_price']) . "'";
            } else {
                $sql .= " AND (ps.price = '" . $this->db->escape($data['filter_price']) . "' or ps.special_price = '" . $this->db->escape($data['filter_price']) . "' )";
            }
        }

        if (!empty($data['filter_product_id_from'])) {
            $sql .= " AND ps.product_store_id >= '" . (int) $data['filter_product_id_from'] . "'";
        }

        if (!empty($data['filter_product_id_to'])) {
            $sql .= " AND ps.product_store_id <= '" . (int) $data['filter_product_id_to'] . "'";
        }

        if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
            $sql .= " AND p2c.category_id = '" . $this->db->escape($data['filter_category']) . "'";
        }

        if (isset($data['filter_tax_class_id']) && !is_null($data['filter_tax_class_id'])) {
            $sql .= " AND ps.tax_class_id = '" . (int) $data['filter_tax_class_id'] . "'";
        }

        if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
            $lGroup = false;
            $sql .= " AND p2c.category_id = '" . $this->db->escape($data['filter_category']) . "'";
        } else {
            $lGroup = true;
        }

        if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
            if (0 == $data['filter_quantity']) {
                $sql .= " AND ps.quantity = '" . (int) $data['filter_quantity'] . "'";
            } else {
                $sql .= " AND ps.quantity <= '" . (int) $data['filter_quantity'] . "' AND ps.quantity > '0'";
            }
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND ps.status = '" . (int) $data['filter_status'] . "'";
        }

        $sort_data = [
            'pd.name',
            'p.price',
            'p.product_id',
            'ps.product_store_id',
            'p2c.category_id',
            'ps.quantity',
            'p.model',
            'ps.status',
            'st.name',
        ];

        $sql .= ' GROUP BY ps.product_store_id';
        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY pd.name';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        /* if (isset($data['start']) || isset($data['limit'])) {
          if ($data['start'] < 0) {
          $data['start'] = 0;
          }

          if ($data['limit'] < 1) {
          $data['limit'] = 20;
          }

          $sql .= ' LIMIT '.(int) $data['start'].','.(int) $data['limit'];
          } */

        // echo $sql;die;
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalProducts($data = []) {
        $sql = 'SELECT Distinct product_store_id from ' . DB_PREFIX . 'product_to_store ps LEFT JOIN ' . DB_PREFIX . 'product_to_category p2c ON (ps.product_id = p2c.product_id) LEFT JOIN ' . DB_PREFIX . 'product p ON (p.product_id = ps.product_id) LEFT JOIN ' . DB_PREFIX . 'product_description pd ON (p.product_id = pd.product_id) LEFT JOIN ' . DB_PREFIX . 'store st ON (st.store_id = ps.store_id) LEFT JOIN ' . DB_PREFIX . 'user v ON (v.user_id = st.vendor_id)';
        $sql .= " WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";
        if (!empty($data['filter_store_id'])) {
            $sql .= " AND st.name LIKE '" . $this->db->escape($data['filter_store_id']) . "%'";
        }
        if ($this->user->isVendor()) {
            $sql .= ' AND v.user_id="' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_model'])) {
            $sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
        }

        if (!empty($data['filter_name'])) {
            $sql .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (isset($data['filter_tax_class_id']) && !is_null($data['filter_tax_class_id'])) {
            $sql .= " AND ps.tax_class_id = '" . (int) $data['filter_tax_class_id'] . "'";
        }

        if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
            //$sql .= " AND ps.price = '" . $this->db->escape($data['filter_price']) . "'";
            $sql .= " AND (ps.price = '" . $this->db->escape($data['filter_price']) . "' or ps.special_price = '" . $this->db->escape($data['filter_price']) . "' )";
        }

        if (!empty($data['filter_product_id_from'])) {
            $sql .= " AND ps.product_store_id >= '" . (int) $data['filter_product_id_from'] . "'";
        }

        if (!empty($data['filter_product_id_to'])) {
            $sql .= " AND ps.product_store_id <= '" . (int) $data['filter_product_id_to'] . "'";
        }

        if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
            $sql .= " AND p2c.category_id = '" . $this->db->escape($data['filter_category']) . "'";
        }

        if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
            $lGroup = false;
            $sql .= " AND p2c.category_id = '" . $this->db->escape($data['filter_category']) . "'";
        } else {
            $lGroup = true;
        }

        if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
            if (0 == $data['filter_quantity']) {
                $sql .= " AND ps.quantity = '" . (int) $data['filter_quantity'] . "'";
            } else {
                $sql .= " AND ps.quantity <= '" . (int) $data['filter_quantity'] . "' AND ps.quantity > '0'";
            }
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND ps.status = '" . (int) $data['filter_status'] . "'";
        }

        $sql .= ' GROUP BY ps.product_store_id';

        $query = $this->db->query($sql);

        return count($query->rows);
    }

    public function copyGeneralProduct($product_id) {
        $someExist = false;

        $status = 0;

        if ($this->user->isVendor()) {
            $status = $this->config->get('config_auto_approval_product');
        }
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "product p WHERE p.product_id = '" . (int) $product_id . "'");

        $product_info = $query->row;

        if ($product_info) {
            $variations_id = explode(',', $product_info['variations_id']);

            foreach ($this->request->post['product_store'] as $store_id) {
                $product_to_store_ids = [];

                foreach ($variations_id as $variation) {
                    $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "product_to_store p WHERE p.product_id = '" . (int) $variation . "' AND p.store_id = '" . (int) $store_id . "'");

                    $exists = $query->row;

                    if ($exists) {
                        array_push($product_to_store_ids, $exists['product_store_id']);
                    }
                }

                $product_to_store_ids = implode(',', $product_to_store_ids);

                $Countquery = $this->db->query('SELECT COUNT(*) as count FROM ' . DB_PREFIX . "product_to_store p WHERE p.product_id = '" . (int) $product_id . "' AND  store_id = '" . $store_id . "'");

                $countExists = $Countquery->row;

                if ($countExists['count'] >= 1) {
                    $someExist = true;
                } else {
                    $this->db->query('INSERT INTO ' . DB_PREFIX . "product_to_store SET  product_id = '" . $product_id . "', quantity = 0, price = '" . $product_info['default_price'] . "', store_id = '" . $store_id . "',status = '" . $status . "'");
                }
            }

            return $someExist;
        }
    }

    public function copyAllGeneralProduct() {
        $someExist = false;

        $status = 0;

        if ($this->user->isVendor()) {
            $status = $this->config->get('config_auto_approval_product');
        }
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . 'product');

        //echo "<pre>";print_r($query->rows);
        foreach ($query->rows as $product_info) {
            //echo "<pre>";print_r($product_info);
            if ($product_info) {
                $product_id = $product_info['product_id'];

                $variations_id = explode(',', $product_info['variations_id']);

                foreach ($this->request->post['product_store'] as $store_id) {
                    $product_to_store_ids = [];

                    foreach ($variations_id as $variation) {
                        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "product_to_store p WHERE p.product_id = '" . (int) $variation . "' AND p.store_id = '" . (int) $store_id . "'");

                        $exists = $query->row;

                        if ($exists) {
                            array_push($product_to_store_ids, $exists['product_store_id']);
                        }
                    }

                    $product_to_store_ids = implode(',', $product_to_store_ids);

                    $Countquery = $this->db->query('SELECT COUNT(*) as count FROM ' . DB_PREFIX . "product_to_store p WHERE p.product_id = '" . (int) $product_id . "' AND  store_id = '" . $store_id . "'");

                    $countExists = $Countquery->row;

                    if ($countExists['count'] >= 1) {
                        $someExist = true;
                    } else {
                        $this->db->query('INSERT INTO ' . DB_PREFIX . "product_to_store SET  product_id = '" . $product_id . "', quantity = 0, price = '" . $product_info['default_price'] . "', store_id = '" . $store_id . "',status = '" . $status . "'");
                    }
                }
            }
        }

        return $someExist;
    }

    public function copyGeneralProductVariations($product_id) {
        if ($this->user->isVendor()) {
            $status = $this->config->get('config_auto_approval_product');
        }
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "product p WHERE p.product_id = '" . (int) $product_id . "'");

        $product_info = $query->row;

        if ($product_info) {
            $variations_id = explode(',', $product_info['variations_id']);

            foreach ($this->request->post['product_store'] as $store_id) {
                $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "product_to_store p WHERE p.product_id = '" . (int) $product_id . "' AND p.store_id = '" . (int) $store_id . "'");

                $exists = $query->row;

                if ($exists) {
                    $product_to_store_ids = [];
                    $product_store_id = $exists['product_store_id'];

                    foreach ($variations_id as $variation) {
                        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "product_to_store p WHERE p.product_id = '" . (int) $variation . "' AND p.store_id = '" . (int) $store_id . "'");

                        $exists = $query->row;

                        if ($exists) {
                            array_push($product_to_store_ids, $exists['product_store_id']);

                            $this->updateVendorProductVariations($variation);
                        }
                    }

                    $product_to_store_ids = implode(',', $product_to_store_ids);

                    $this->db->query('UPDATE ' . DB_PREFIX . "product_to_store SET product_to_store_ids = '" . $product_to_store_ids . "' WHERE product_store_id='" . $product_store_id . "'");
                }
            }
        }
    }

    public function copyAllGeneralProductVariations() {
        if ($this->user->isVendor()) {
            $status = $this->config->get('config_auto_approval_product');
        }

        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . 'product');

        foreach ($query->rows as $product_info) {
            if ($product_info) {
                $product_id = $product_info['product_id'];

                $variations_id = explode(',', $product_info['variations_id']);

                foreach ($this->request->post['product_store'] as $store_id) {
                    $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "product_to_store p WHERE p.product_id = '" . (int) $product_id . "' AND p.store_id = '" . (int) $store_id . "'");

                    $exists = $query->row;

                    if ($exists) {
                        $product_to_store_ids = [];
                        $product_store_id = $exists['product_store_id'];

                        foreach ($variations_id as $variation) {
                            $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "product_to_store p WHERE p.product_id = '" . (int) $variation . "' AND p.store_id = '" . (int) $store_id . "'");

                            $exists = $query->row;

                            if ($exists) {
                                array_push($product_to_store_ids, $exists['product_store_id']);

                                $this->updateVendorProductVariations($variation);
                            }
                        }

                        $product_to_store_ids = implode(',', $product_to_store_ids);

                        $this->db->query('UPDATE ' . DB_PREFIX . "product_to_store SET product_to_store_ids = '" . $product_to_store_ids . "' WHERE product_store_id='" . $product_store_id . "'");
                    }
                }
            }
        }
    }

    public function updateVendorProductVariations($product_id) {
        if ($this->user->isVendor()) {
            $status = $this->config->get('config_auto_approval_product');
        }
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "product p WHERE p.product_id = '" . (int) $product_id . "'");

        $product_info = $query->row;

        if ($product_info) {
            $variations_id = explode(',', $product_info['variations_id']);

            foreach ($this->request->post['product_store'] as $store_id) {
                $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "product_to_store p WHERE p.product_id = '" . (int) $product_id . "' AND p.store_id = '" . (int) $store_id . "'");

                $exists = $query->row;

                if ($exists) {
                    $product_to_store_ids = [];
                    $product_store_id = $exists['product_store_id'];

                    foreach ($variations_id as $variation) {
                        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "product_to_store p WHERE p.product_id = '" . (int) $variation . "' AND p.store_id = '" . (int) $store_id . "'");

                        $exists = $query->row;

                        if ($exists) {
                            array_push($product_to_store_ids, $exists['product_store_id']);
                        }
                    }

                    $product_to_store_ids = implode(',', $product_to_store_ids);

                    $this->db->query('UPDATE ' . DB_PREFIX . "product_to_store SET product_to_store_ids = '" . $product_to_store_ids . "' WHERE product_store_id='" . $product_store_id . "'");
                }
            }
        }
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

    public function copyProduct($store_product_id) {
        $query = $this->db->query('SELECT DISTINCT p.* FROM ' . DB_PREFIX . "product_to_store p  WHERE p.product_store_id = '" . (int) $store_product_id . "'");

        if ($query->num_rows) {
            $data = $query->row;

            //$data['product_variation'] = $this->getProductVariations($store_product_id);
            if ($this->user->isVendor()) {
                $status = $this->config->get('config_auto_approval_product');
            } else {
                $status = $data['status'];
            }
            $this->db->query('INSERT INTO ' . DB_PREFIX . "product_to_store SET  product_id = '" . $data['product_id'] . "', store_id = '" . $this->db->escape($data['store_id']) . "', price = '" . $data['price'] . "',special_price = '" . $data['special_price'] . "',tax_percentage = '" . $data['tax_percentage'] . "',quantity = '" . $data['quantity'] . "',min_quantity = '" . $data['min_quantity'] . "',subtract_quantity = '" . $data['subtract_quantity'] . "',status = '" . $status . "'");
            $product_store_id = $this->db->getLastId();
        }
    }

    public function deleteProduct($store_product_id) {
        $this->trigger->fire('pre.admin.product.delete', $store_product_id);

        $this->db->query('DELETE FROM ' . DB_PREFIX . "product_to_store WHERE product_store_id = '" . (int) $store_product_id . "'");

        $this->db->query('DELETE FROM ' . DB_PREFIX . "variation_to_product_store WHERE product_store_id = '" . (int) $store_product_id . "'");

        $this->cache->delete('product');

        $this->trigger->fire('post.admin.product.delete', $product_id);
    }

    public function updateProductInventory($store_product_id, $data) {
        $this->trigger->fire('pre.admin.product.edit', $data);

        $log = new Log('error.log');
        $log->write($data['rejected_qty']);
        $log->write($data['procured_qty']);
        if (!isset($data['rejected_qty']) || $data['rejected_qty'] < 0 || $data['rejected_qty'] == NULL || $data['rejected_qty'] == '') {
            $data['rejected_qty'] = 0;
        }

        if (!isset($data['procured_qty']) || $data['procured_qty'] < 0 || $data['procured_qty'] == NULL || $data['procured_qty'] == '') {
            $data['procured_qty'] = 0;
        }

        $qty = $data['current_qty'] + ($data['procured_qty'] - $data['rejected_qty']);

        $sel_query = 'SELECT * FROM ' . DB_PREFIX . "product_to_store WHERE product_store_id ='" . (int) $store_product_id . "'";
        $sel_query = $this->db->query($sel_query);
        $sel = $sel_query->row;
        $log = new Log('error.log');
        $log->write($sel['quantity']);
        //$data['current_qty'];

        $previous_quantity = $sel['quantity'];
        $previous_buying_price = $sel['buying_price'];
        if ($data['current_buying_price'] < 0 || $data['current_buying_price'] == '' || $data['current_buying_price'] == NULL) {
            $data['current_buying_price'] = $sel['buying_price'];
        } else {
            $data['current_buying_price'] = $data['current_buying_price'];
        }

        $previous_source = $sel['source'];
        if ($data['source'] == '' || $data['source'] == NULL) {
            $data['source'] = $sel['source'];
        } else {
            $data['source'] = $data['source'];
        }

        $query = 'UPDATE ' . DB_PREFIX . "product_to_store SET quantity = '" . $qty . "', buying_price = '" . $data['current_buying_price'] . "', source = '" . $data['source'] . "' WHERE product_store_id = '" . (int) $store_product_id . "'";
        //echo $query;
        $this->db->query($query);

        $this->db->query('INSERT INTO ' . DB_PREFIX . "product_inventory_history SET product_id = '" . $data['product_id'] . "', product_store_id = '" . $store_product_id . "', product_name = '" . $data['product_name'] . "', procured_qty = '" . $data['procured_qty'] . "', prev_qty = '" . $previous_quantity . "', current_qty = '" . $qty . "', rejected_qty = '" . $data['rejected_qty'] . "', buying_price= '" . $data['current_buying_price'] . "', prev_buying_price= '" . $previous_buying_price . "',  source = '" . $data['source'] . "', prev_source = '" . $previous_source . "', added_by = '" . $this->user->getId() . "', added_user_role = '" . $this->user->getGroupName() . "', added_user = '" . $this->user->getFirstName() . ' ' . $this->user->getLastName() . "',  date_added = '" . $this->db->escape(date('Y-m-d H:i:s')) . "'");

        $this->trigger->fire('post.admin.product.edit', $store_product_id);

        return $product_id;
    }

    public function updateProductInventoryPricing($store_product_id, $data) {
        $this->trigger->fire('pre.admin.product.edit', $data);

        $query = 'UPDATE ' . DB_PREFIX . "product_to_store SET buying_price = '" . $data['buying_price'] . "', source = '" . $data['source'] . "' WHERE product_store_id = '" . (int) $store_product_id . "'";
        //echo $query;
        $this->db->query($query);

        $this->db->query('INSERT INTO ' . DB_PREFIX . "product_inventory_price_history SET  product_id = '" . $data['product_id'] . "', product_store_id = '" . $store_product_id . "', store_id = '" . $data['store_id'] . "', buying_price = '" . $data['buying_price'] . "', prev_buying_price = '" . $data['current_buying_price'] . "', source = '" . $data['source'] . "', prev_source = '" . $data['current_source'] . "', product_name = '" . $data['product_name'] . "', added_by = '" . $data['added_by'] . "', added_user_role = '" . $this->user->getGroupName() . "', added_user = '" . $this->user->getFirstName() . ' ' . $this->user->getLastName() . "', date_added = '" . $this->db->escape(date('Y-m-d H:i:s')) . "'");

        $this->trigger->fire('post.admin.product.edit', $store_product_id);

        return $product_id;
    }

    public function productInventoryHistory($store_product_id) {
        $query = 'SELECT * FROM ' . DB_PREFIX . "product_inventory_history WHERE product_store_id ='" . (int) $store_product_id . "' order by date_added desc LIMIT 5";
        $query = $this->db->query($query);

        return $query->rows;
    }

    public function getTotalProductInventoryHistory($data = []) {
        $sql = 'SELECT COUNT(*) AS total FROM ' . DB_PREFIX . 'product_inventory_history';

        $implode = [];

        if (!empty($data['filter_store_id'])) {
            $implode[] = "product_store_id = '" . $this->db->escape($data['filter_store_id']) . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "date_added = '" . $this->db->escape($data['filter_date_added']) . "'";
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

    public function getTotalproductInventoryPriceHistory($data = []) {
        $sql = 'SELECT COUNT(*) AS total FROM ' . DB_PREFIX . 'product_inventory_price_history';

        $implode = [];

        if (!empty($data['filter_store_id'])) {
            $implode[] = "product_store_id = '" . $this->db->escape($data['filter_store_id']) . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "date_added = '" . $this->db->escape($data['filter_date_added']) . "'";
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

    public function getProductInventoryHistory($data = []) {
        $sql = "SELECT * FROM " . DB_PREFIX . 'product_inventory_history';

        $implode = [];

        if (!empty($data['filter_store_id'])) {
            $implode[] = "product_store_id = '" . $this->db->escape($data['filter_store_id']) . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "date_added = '" . $this->db->escape($data['filter_date_added']) . "'";
        }

        if (!empty($data['filter_name'])) {
            $implode[] = "product_name = '" . $this->db->escape($data['filter_name']) . "'";
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
        //echo "<pre>";print_r($sql);die;

        return $query->rows;
    }

    public function getproductInventoryPriceHistory($data = []) {
        $sql = "SELECT * FROM " . DB_PREFIX . 'product_inventory_price_history';

        $implode = [];

        if (!empty($data['filter_store_id'])) {
            $implode[] = "product_store_id = '" . $this->db->escape($data['filter_store_id']) . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "date_added = '" . $this->db->escape($data['filter_date_added']) . "'";
        }

        if (!empty($data['filter_name'])) {
            $implode[] = "product_name = '" . $this->db->escape($data['filter_name']) . "'";
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
        //echo "<pre>";print_r($sql);die;

        return $query->rows;
    }

    public function productInventoryPriceHistory($store_product_id) {
        $query = 'SELECT * FROM ' . DB_PREFIX . "product_inventory_price_history WHERE product_store_id ='" . (int) $store_product_id . "'  order by date_added desc LIMIT 6";
        $query = $this->db->query($query);

        return $query->rows;
    }

    public function getProductInventoryPriceHistorybyDate($start_date, $store_product_id, $end_date = null) {
        $query = 'SELECT * FROM ' . DB_PREFIX . "product_inventory_price_history WHERE product_store_id ='" . (int) $store_product_id . "'  and  DATE(date_added) ='" . $start_date . "'  order by date_added desc ";
        //   echo "<pre>";print_r($query);die;
        $query = $this->db->query($query);

        return $query->row['buying_price'];
    }

    public function updateCategoryPrices($category, $data) {
        $query_exist = 'SELECT * FROM ' . DB_PREFIX . "product_category_prices WHERE product_store_id ='" . (int) $data['product_store_id'] . "' AND price_category='$category'";
        $res = $this->db->query($query_exist);
        //echo '<pre>';print_r($res);exit;
        if (count($res->rows) > 0) {
            $query = 'UPDATE ' . DB_PREFIX . "product_category_prices SET price = '" . $data[$category] . "' WHERE product_store_id ='" . (int) $data['product_store_id'] . "' AND price_category='$category'";
        } else {
            $query = 'INSERT INTO ' . DB_PREFIX . "product_category_prices SET  product_id = '" . $data['product_id'] . "', product_store_id = '" . $data['product_store_id'] . "', product_name = '" . $data['product_name'] . "', store_id = 75, price_category = '" . $category . "',price = '" . $data[$category] . "', status = 1";
        }
        //echo $query;
        $this->db->query($query);
        $price = number_format((float) $data[$category], 2, '.', '');
        //echo $res->row['price'].'===>'. $price;exit;
        if ($res->row['price'] != $price) {
            $this->db->query('INSERT INTO ' . DB_PREFIX . "product_category_prices_history SET  product_id = '" . $data['product_id'] . "', product_store_id = '" . $data['product_store_id'] . "', product_name = '" . $data['product_name'] . "',price_category = '" . $category . "',price = '" . $data[$category] . "', date_added = '" . $this->db->escape(date('Y-m-d H:i:s')) . "'");
        }

        return $product_id;
    }

    public function addVendorProductToCategoryPrices($data) {
        $category = $data['price_category'];
        $price = $data['product_price'];
        $query_exist = 'SELECT * FROM ' . DB_PREFIX . "product_category_prices WHERE product_store_id ='" . (int) $data['product_store_id'] . "' AND price_category='$category'";
        $res = $this->db->query($query_exist);
        if (count($res->rows) > 0) {
            $query = 'UPDATE ' . DB_PREFIX . "product_category_prices SET price = '" . $price . "' WHERE product_store_id ='" . (int) $data['product_store_id'] . "' AND price_category='$category'";
        } else {
            $query = 'INSERT INTO ' . DB_PREFIX . "product_category_prices SET  product_id = '" . $data['product_id'] . "', product_store_id = '" . $data['product_store_id'] . "', product_name = '" . $data['name'] . "', store_id = 75, price_category = '" . $category . "',price = '" . $price . "', status = 1";
        }
        //echo $query;
        $this->db->query($query);
        $price = number_format((float) $data[$category], 2, '.', '');
        //echo $res->row['price'].'===>'. $price;exit;
        if ($res->row['price'] != $price) {
            $this->db->query('INSERT INTO ' . DB_PREFIX . "product_category_prices_history SET  product_id = '" . $data['product_id'] . "', product_store_id = '" . $data['product_store_id'] . "', product_name = '" . $data['name'] . "',price_category = '" . $category . "',price = '" . $price . "', date_added = '" . $this->db->escape(date('Y-m-d H:i:s')) . "'");
        }

        return $product_id;
    }

    public function productCategoryPriceHistory($store_product_id) {
        $query = 'SELECT * FROM ' . DB_PREFIX . "product_category_prices_history WHERE product_store_id ='" . (int) $store_product_id . "'";
        $query = $this->db->query($query);

        return $query->rows;
    }

    public function getCategoryPriceDetails($product_store_id, $product_id, $product_name, $store_id, $price_category) {
        $category_price = 'SELECT * FROM ' . DB_PREFIX . "product_category_prices WHERE product_store_id ='" . (int) $product_store_id . "' AND price_category='" . $price_category . "' AND product_id='" . $product_id . "' AND product_name='" . $product_name . "' AND store_id='" . $store_id . "'";
        $res = $this->db->query($category_price);
        return $res->row;
    }

    public function getCategoryPriceDetailsByCategoryName($store_id, $price_category) {
        $category_price = 'SELECT * FROM ' . DB_PREFIX . "product_category_prices WHERE price_category='" . $price_category . "' AND store_id='" . $store_id . "'";
        $res = $this->db->query($category_price);
        return $res->rows;
    }

    public function getCategoryProductsCategoryName($price_category) {
        $category_price = 'SELECT * FROM ' . DB_PREFIX . "product_category_prices WHERE price_category='" . $price_category . "'";
        $res = $this->db->query($category_price);
        return $res->rows;
    }

    public function updateCategoryPricesStatus($product_store_id, $product_id, $product_name, $status) {
        $query = 'UPDATE ' . DB_PREFIX . "product_category_prices SET status = '" . $status . "' WHERE product_store_id = '" . (int) $product_store_id . "' AND product_id='" . (int) $product_id . "' AND product_name='" . $product_name . "' AND store_id=75";

        $this->db->query($query);
    }

    public function updateCategoryPricesStatuss($product_store_id, $product_id, $product_name, $status, $price_category) {
        $query = 'UPDATE ' . DB_PREFIX . "product_category_prices SET status = '" . $status . "' WHERE product_store_id = '" . (int) $product_store_id . "' AND product_id='" . (int) $product_id . "' AND product_name='" . $product_name . "' AND store_id=75 AND price_category='" . $price_category . "'";

        $this->db->query($query);
    }

}
