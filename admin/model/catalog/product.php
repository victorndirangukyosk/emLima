<?php

class ModelCatalogProduct extends Model
{
    public function addProduct($data)
    {
        $this->trigger->fire('pre.admin.product.add', $data);

        if ($this->user->isVendor()) {
            $data['status'] = 0;
        }

        $this->db->query('INSERT INTO '.DB_PREFIX."product SET default_variation_name = '".$data['default_variation_name']."', model = '".$this->db->escape($data['model'])."', sku = '".$this->db->escape($data['sku'])."', upc = '".$this->db->escape($data['upc'])."', location = '".$this->db->escape($data['location'])."', quantity = '".(int) $data['quantity']."', minimum = '".(int) $data['minimum']."', subtract = '".(int) $data['subtract']."', stock_status_id = '".(int) $data['stock_status_id']."', manufacturer_id = '".(int) $data['manufacturer_id']."', shipping = '".(int) $data['shipping']."', special_price = '".(float) $data['special_price']."', price = '".(float) $data['price']."', status = '".(int) $data['status']."', tax_class_id = '".$this->db->escape($data['tax_class_id'])."', sort_order = '".(int) $data['sort_order']."', date_added = NOW()");

        $product_id = $this->db->getLastId();

        if (isset($data['image'])) {
            $this->db->query('UPDATE '.DB_PREFIX."product SET image = '".$this->db->escape($data['image'])."' WHERE product_id = '".(int) $product_id."'");
        }

        if ($this->user->isVendor()) {
            // $this->db->query("UPDATE " . DB_PREFIX . "product SET vendor_id = '" . $this->user->getId() . "' WHERE product_id = '" . (int) $product_id . "'");
        }

        foreach ($data['product_description'] as $language_id => $value) {
            empty($value['meta_title']) ? $value['meta_title'] = $value['name'] : $value['meta_title'];

            $this->db->query('INSERT INTO '.DB_PREFIX."product_description SET product_id = '".(int) $product_id."', language_id = '".(int) $language_id."', name = '".$this->db->escape($value['name'])."', description = '".$this->db->escape($value['description'])."', tag = '".$this->db->escape($value['tag'])."', meta_title = '".$this->db->escape($value['meta_title'])."', meta_description = '".$this->db->escape($value['meta_description'])."', meta_keyword = '".$this->db->escape($value['meta_keyword'])."'");
        }

        if (isset($data['product_store'])) {
            foreach ($data['product_store'] as $store_id) {
                $this->db->query('INSERT INTO '.DB_PREFIX."product_to_store SET product_id = '".(int) $product_id."', store_id = '".(int) $store_id."'");
            }
        }

        if (isset($data['product_variation'])) {
            foreach ($data['product_variation'] as $product_variation) {
                $this->db->query('INSERT INTO '.DB_PREFIX."product_variation SET special_price = '".(int) $product_variation['special_price']."', product_id = '".(int) $product_id."', image = '".$this->db->escape($product_variation['image'])."', price = '".(int) $product_variation['price']."', name = '".$product_variation['name']."', sort_order = '".(int) $product_variation['sort_order']."'");
            }
        }

        if (isset($data['product_category'])) {
            foreach ($data['product_category'] as $category_id) {
                $this->db->query('INSERT INTO '.DB_PREFIX."product_to_category SET product_id = '".(int) $product_id."', category_id = '".(int) $category_id."'");
            }
        }

        if (isset($data['seo_url'])) {
            foreach ($data['seo_url'] as $language_id => $value) {
                $alias = empty($value) ? $data['product_description'][$language_id]['name'] : $value;

                $alias = $this->model_catalog_url_alias->generateAlias($alias);

                if ($alias) {
                    $this->db->query('INSERT INTO '.DB_PREFIX."url_alias SET query = 'product_id=".(int) $product_id."', keyword = '".$this->db->escape($alias)."', language_id = '".$language_id."'");
                }
            }
        }

        $this->cache->delete('product');

        $this->trigger->fire('post.admin.product.add', $product_id);

        return $product_id;
    }

    public function editProduct($product_id, $data)
    {
        $this->trigger->fire('pre.admin.product.edit', $data);

        if ($this->user->isVendor()) {
            $data['status'] = 0;
        }

        $this->db->query('UPDATE '.DB_PREFIX."product SET default_variation_name = '".$data['default_variation_name']."', model = '".$this->db->escape($data['model'])."', sku = '".$this->db->escape($data['sku'])."', upc = '".$this->db->escape($data['upc'])."',  location = '".$this->db->escape($data['location'])."', quantity = '".(int) $data['quantity']."', minimum = '".(int) $data['minimum']."', subtract = '".(int) $data['subtract']."', stock_status_id = '".(int) $data['stock_status_id']."', manufacturer_id = '".(int) $data['manufacturer_id']."', shipping = '".(int) $data['shipping']."', special_price = '".(float) $data['special_price']."', price = '".(float) $data['price']."', status = '".(int) $data['status']."', tax_class_id = '".$this->db->escape($data['tax_class_id'])."', sort_order = '".(int) $data['sort_order']."', date_modified = NOW() WHERE product_id = '".(int) $product_id."'");

        if (isset($data['product_variation'])) {
            $this->db->query('DELETE FROM '.DB_PREFIX."product_variation WHERE product_id='".$product_id."'");
            foreach ($data['product_variation'] as $product_variation) {
                $this->db->query('INSERT INTO '.DB_PREFIX."product_variation SET product_id = '".(int) $product_id."', image = '".$this->db->escape($product_variation['image'])."', special_price = '".(int) $product_variation['special_price']."', price = '".(int) $product_variation['price']."', name = '".$product_variation['name']."', sort_order = '".(int) $product_variation['sort_order']."'");
            }
        }

        if (isset($data['image'])) {
            $this->db->query('UPDATE '.DB_PREFIX."product SET image = '".$this->db->escape($data['image'])."' WHERE product_id = '".(int) $product_id."'");
        }

        $this->db->query('DELETE FROM '.DB_PREFIX."product_description WHERE product_id = '".(int) $product_id."'");

        foreach ($data['product_description'] as $language_id => $value) {
            empty($value['meta_title']) ? $value['meta_title'] = $value['name'] : $value['meta_title'];

            $this->db->query('INSERT INTO '.DB_PREFIX."product_description SET product_id = '".(int) $product_id."', language_id = '".(int) $language_id."', name = '".$this->db->escape($value['name'])."', description = '".$this->db->escape($value['description'])."', tag = '".$this->db->escape($value['tag'])."', meta_title = '".$this->db->escape($value['meta_title'])."', meta_description = '".$this->db->escape($value['meta_description'])."', meta_keyword = '".$this->db->escape($value['meta_keyword'])."'");
        }

        $this->db->query('DELETE FROM '.DB_PREFIX."product_to_store WHERE product_id = '".(int) $product_id."'");

        if (isset($data['product_store'])) {
            foreach ($data['product_store'] as $store_id) {
                $this->db->query('INSERT INTO '.DB_PREFIX."product_to_store SET product_id = '".(int) $product_id."', store_id = '".(int) $store_id."'");
            }
        }
        //multiple image
        $this->db->query('DELETE FROM '.DB_PREFIX."product_image WHERE product_id = '".(int) $product_id."'");

        if (isset($data['product_image'])) {
            foreach ($data['product_image'] as $product_image) {
                $this->db->query('INSERT INTO '.DB_PREFIX."product_image SET product_id = '".(int) $product_id."', image = '".$this->db->escape($product_image['image'])."', sort_order = '".(int) $product_image['sort_order']."'");
            }
        }
        //end
        $this->db->query('DELETE FROM '.DB_PREFIX."product_to_category WHERE product_id = '".(int) $product_id."'");

        if (isset($data['product_category'])) {
            foreach ($data['product_category'] as $category_id) {
                $this->db->query('INSERT INTO '.DB_PREFIX."product_to_category SET product_id = '".(int) $product_id."', category_id = '".(int) $category_id."'");
            }
        }

        $this->db->query('DELETE FROM '.DB_PREFIX."product_related WHERE product_id = '".(int) $product_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."product_related WHERE related_id = '".(int) $product_id."'");

        if (isset($data['product_related'])) {
            foreach ($data['product_related'] as $related_id) {
                $this->db->query('DELETE FROM '.DB_PREFIX."product_related WHERE product_id = '".(int) $product_id."' AND related_id = '".(int) $related_id."'");
                $this->db->query('INSERT INTO '.DB_PREFIX."product_related SET product_id = '".(int) $product_id."', related_id = '".(int) $related_id."'");
                $this->db->query('DELETE FROM '.DB_PREFIX."product_related WHERE product_id = '".(int) $related_id."' AND related_id = '".(int) $product_id."'");
                $this->db->query('INSERT INTO '.DB_PREFIX."product_related SET product_id = '".(int) $related_id."', related_id = '".(int) $product_id."'");
            }
        }

        foreach ($data['seo_url'] as $language_id => $value) {
            $this->db->query('DELETE FROM '.DB_PREFIX."url_alias WHERE query = 'product_id=".(int) $product_id."' AND language_id = '".$this->db->escape($language_id)."'");

            $alias = empty($value) ? $data['product_description'][$language_id]['name'] : $value;

            $alias = $this->model_catalog_url_alias->generateAlias($alias);

            if ($alias) {
                $this->db->query('INSERT INTO '.DB_PREFIX."url_alias SET query = 'product_id=".(int) $product_id."', keyword = '".$this->db->escape($alias)."', language_id = '".$language_id."'");
            }
        }

        $this->cache->delete('product');

        $this->trigger->fire('post.admin.product.edit', $product_id);
    }

    public function copyProduct($product_id)
    {
        $query = $this->db->query('SELECT DISTINCT * FROM '.DB_PREFIX.'product p LEFT JOIN '.DB_PREFIX."product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '".(int) $product_id."' AND pd.language_id = '".(int) $this->config->get('config_language_id')."'");

        if ($query->num_rows) {
            $data = $query->row;

            $data['sku'] = '';
            $data['upc'] = '';
            $data['viewed'] = '0';
            $data['keyword'] = '';
            //$data['status'] = '0';

            $data['product_description'] = $this->getProductDescriptions($product_id);
            $data['product_image'] = $this->getProductImages($product_id);
            $data['product_variation'] = $this->getProductVariations($product_id);
            $data['product_related'] = $this->getProductRelated($product_id);
            $data['product_category'] = $this->getProductCategories($product_id);

            if (isset($this->request->post['product_store'])) {
                $data['product_store'] = $this->request->post['product_store'];
            } else {
                $data['product_store'] = $this->getProductStores($product_id);
            }

            $this->addProduct($data);
        }
    }

    public function deleteProduct($product_id)
    {
        $this->trigger->fire('pre.admin.product.delete', $product_id);

        $this->db->query('DELETE FROM '.DB_PREFIX."product WHERE product_id = '".(int) $product_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."product_description WHERE product_id = '".(int) $product_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."product_image WHERE product_id = '".(int) $product_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."product_variation WHERE product_id='".(int) $product_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."product_related WHERE product_id = '".(int) $product_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."product_related WHERE related_id = '".(int) $product_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."product_to_category WHERE product_id = '".(int) $product_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."product_to_store WHERE product_id = '".(int) $product_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."review WHERE product_id = '".(int) $product_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."url_alias WHERE query = 'product_id=".(int) $product_id."'");

        $this->cache->delete('product');

        $this->trigger->fire('post.admin.product.delete', $product_id);
    }

    public function getProduct($product_id)
    {
        $query = $this->db->query('SELECT DISTINCT * FROM '.DB_PREFIX.'product p LEFT JOIN '.DB_PREFIX."product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '".(int) $product_id."' AND pd.language_id = '".(int) $this->config->get('config_language_id')."'");

        $product = $query->row;
        $product['seo_url'] = [];

        $query = $this->db->query('SELECT keyword, language_id FROM '.DB_PREFIX."url_alias WHERE query = 'product_id=".(int) $product_id."'");

        if ($query->rows) {
            foreach ($query->rows as $row) {
                $product['seo_url'][$row['language_id']] = $row['keyword'];
            }
        }

        return $product;
    }

    public function getProducts($data = [])
    {
        $sql = 'SELECT p.*,pd.*,p2c.product_id product_id2 FROM '.DB_PREFIX.'product p LEFT JOIN '.DB_PREFIX.'product_description pd ON (p.product_id = pd.product_id) LEFT JOIN '.DB_PREFIX.'product_to_category p2c ON (p.product_id = p2c.product_id)';

        if (!empty($data['filter_store'])) {
            $sql .= ' LEFT JOIN `'.DB_PREFIX.'product_to_store` ps on ps.product_id = p.product_id';
        }

        $sql .= " WHERE pd.language_id = '".(int) $this->config->get('config_language_id')."'";

        if (!empty($data['filter_store'])) {
            $sql .= ' AND ps.store_id="'.$data['filter_store'].'"';
        }

        if ($this->user->isVendor()) {
            // $sql .= ' AND p.vendor_id="'.$this->user->getId().'"';
        } else {
            // $sql .= ' AND p.vendor_id!="0"';
        }

        if (!empty($data['filter_name'])) {
            $sql .= " AND pd.name LIKE '%".$this->db->escape($data['filter_name'])."%'";
            //$this->db->like('product_description.name', $this->db->escape( $filter_name ) , 'both');
        }

        if (!empty($data['filter_model'])) {
            $sql .= " AND p.model LIKE '".$this->db->escape($data['filter_model'])."%'";
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

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND p.status = '".(int) $data['filter_status']."'";
        }

        $sql .= ' GROUP BY p.product_id';

        $sort_data = [
            'pd.name',
            'p.model',
            'p.price',
            'p2c.category_id',
            'p.quantity',
            'p.status',
            'p.sort_order',
        ];

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

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getProductsByCategoryId($category_id)
    {
        $sql = 'SELECT * FROM '.DB_PREFIX.'product p LEFT JOIN '.DB_PREFIX.'product_description pd ON (p.product_id = pd.product_id) LEFT JOIN '.DB_PREFIX.'product_to_category p2c ON (p.product_id = p2c.product_id) WHERE ';
        $sql .= " pd.language_id = '".(int) $this->config->get('config_language_id')."'";
        if ($this->user->isVendor()) {
            // $sql .= ' AND p.vendor_id = "'.$this->user->getId().'"';
        }
        $sql .= " AND p2c.category_id = '".(int) $category_id."' ORDER BY pd.name ASC";
        $query = $this->db->query();

        return $query->rows;
    }

    public function getProductDescriptions($product_id)
    {
        $product_description_data = [];

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."product_description WHERE product_id = '".(int) $product_id."'");

        foreach ($query->rows as $result) {
            $product_description_data[$result['language_id']] = [
                'name' => $result['name'],
                'description' => $result['description'],
                'meta_title' => $result['meta_title'],
                'meta_description' => $result['meta_description'],
                'meta_keyword' => $result['meta_keyword'],
                'tag' => $result['tag'],
            ];
        }

        return $product_description_data;
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

    public function getProductFilters($product_id)
    {
        $product_filter_data = [];

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."product_filter WHERE product_id = '".(int) $product_id."'");

        foreach ($query->rows as $result) {
            $product_filter_data[] = $result['filter_id'];
        }

        return $product_filter_data;
    }

    public function getProductAttributes($product_id)
    {
        $product_attribute_data = [];

        $product_attribute_query = $this->db->query('SELECT attribute_id FROM '.DB_PREFIX."product_attribute WHERE product_id = '".(int) $product_id."' GROUP BY attribute_id");

        foreach ($product_attribute_query->rows as $product_attribute) {
            $product_attribute_description_data = [];

            $product_attribute_description_query = $this->db->query('SELECT * FROM '.DB_PREFIX."product_attribute WHERE product_id = '".(int) $product_id."' AND attribute_id = '".(int) $product_attribute['attribute_id']."'");

            foreach ($product_attribute_description_query->rows as $product_attribute_description) {
                $product_attribute_description_data[$product_attribute_description['language_id']] = ['text' => $product_attribute_description['text']];
            }

            $product_attribute_data[] = [
                'attribute_id' => $product_attribute['attribute_id'],
                'product_attribute_description' => $product_attribute_description_data,
            ];
        }

        return $product_attribute_data;
    }

    public function getProductOptions($product_id)
    {
        $product_option_data = [];

        $product_option_query = $this->db->query('SELECT * FROM `'.DB_PREFIX.'product_option` po LEFT JOIN `'.DB_PREFIX.'option` o ON (po.option_id = o.option_id) LEFT JOIN `'.DB_PREFIX."option_description` od ON (o.option_id = od.option_id) WHERE po.product_id = '".(int) $product_id."' AND od.language_id = '".(int) $this->config->get('config_language_id')."'");

        foreach ($product_option_query->rows as $product_option) {
            $product_option_value_data = [];

            $product_option_value_query = $this->db->query('SELECT * FROM '.DB_PREFIX."product_option_value WHERE product_option_id = '".(int) $product_option['product_option_id']."'");

            foreach ($product_option_value_query->rows as $product_option_value) {
                $product_option_value_data[] = [
                    'product_option_value_id' => $product_option_value['product_option_value_id'],
                    'option_value_id' => $product_option_value['option_value_id'],
                    'quantity' => $product_option_value['quantity'],
                    'subtract' => $product_option_value['subtract'],
                    'price' => $product_option_value['price'],
                    'price_prefix' => $product_option_value['price_prefix'],
                    'points' => $product_option_value['points'],
                    'points_prefix' => $product_option_value['points_prefix'],
                    'weight' => $product_option_value['weight'],
                    'weight_prefix' => $product_option_value['weight_prefix'],
                ];
            }

            $product_option_data[] = [
                'product_option_id' => $product_option['product_option_id'],
                'product_option_value' => $product_option_value_data,
                'option_id' => $product_option['option_id'],
                'name' => $product_option['name'],
                'type' => $product_option['type'],
                'value' => $product_option['value'],
                'required' => $product_option['required'],
            ];
        }

        return $product_option_data;
    }

    public function getProductImages($product_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."product_image WHERE product_id = '".(int) $product_id."' ORDER BY sort_order ASC");

        return $query->rows;
    }

    public function getProductVariations($product_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."product_variation WHERE product_id = '".(int) $product_id."' ORDER BY sort_order ASC");

        return $query->rows;
    }

    public function getProductDiscounts($product_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."product_discount WHERE product_id = '".(int) $product_id."' ORDER BY quantity, priority, price");

        return $query->rows;
    }

    public function getProductSpecials($product_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."product_special WHERE product_id = '".(int) $product_id."' ORDER BY priority, price");

        return $query->rows;
    }

    public function getProductRewards($product_id)
    {
        $product_reward_data = [];

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."product_reward WHERE product_id = '".(int) $product_id."'");

        foreach ($query->rows as $result) {
            $product_reward_data[$result['customer_group_id']] = ['points' => $result['points']];
        }

        return $product_reward_data;
    }

    public function getProductDownloads($product_id)
    {
        $product_download_data = [];

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."product_to_download WHERE product_id = '".(int) $product_id."'");

        foreach ($query->rows as $result) {
            $product_download_data[] = $result['download_id'];
        }

        return $product_download_data;
    }

    public function getProductStores($product_id)
    {
        $product_store_data = [];

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."product_to_store WHERE product_id = '".(int) $product_id."'");

        foreach ($query->rows as $result) {
            $product_store_data[] = $result['store_id'];
        }

        return $product_store_data;
    }

    public function getProductLayouts($product_id)
    {
        $product_layout_data = [];

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."product_to_layout WHERE product_id = '".(int) $product_id."'");

        foreach ($query->rows as $result) {
            $product_layout_data[$result['store_id']] = $result['layout_id'];
        }

        return $product_layout_data;
    }

    public function getProductRelated($product_id)
    {
        $product_related_data = [];

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."product_related WHERE product_id = '".(int) $product_id."'");

        foreach ($query->rows as $result) {
            $product_related_data[] = $result['related_id'];
        }

        return $product_related_data;
    }

    public function getRecurrings($product_id)
    {
        $query = $this->db->query('SELECT * FROM `'.DB_PREFIX."product_recurring` WHERE product_id = '".(int) $product_id."'");

        return $query->rows;
    }

    public function getTotalProducts($data = [])
    {
        $sql = 'SELECT count(distinct p.product_id) AS total FROM '.DB_PREFIX.'product p LEFT JOIN '.DB_PREFIX.'product_description pd ON (p.product_id = pd.product_id)';

        $sql .= ' LEFT JOIN '.DB_PREFIX.'product_to_category p2c ON (p.product_id = p2c.product_id)';

        $sql .= " WHERE pd.language_id = '".(int) $this->config->get('config_language_id')."'";

        if ($this->user->isVendor()) {
            //$sql .= ' AND p.vendor_id="'.$this->user->getId().'"';
        } else {
            //$sql .= ' AND p.vendor_id!="0"';
        }

        if (!empty($data['filter_name'])) {
            $sql .= " AND pd.name LIKE '".$this->db->escape($data['filter_name'])."%'";
        }

        if (!empty($data['filter_model'])) {
            $sql .= " AND p.model LIKE '".$this->db->escape($data['filter_model'])."%'";
        }

        if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
            $sql .= " AND p2c.category_id = '".$this->db->escape($data['filter_category'])."'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND p.status = '".(int) $data['filter_status']."'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalProductsByTaxClassId($tax_class_id)
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX."product WHERE tax_class_id = '".(int) $tax_class_id."'");

        return $query->row['total'];
    }

    public function getTotalProductsByStockStatusId($stock_status_id)
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX."product WHERE stock_status_id = '".(int) $stock_status_id."'");

        return $query->row['total'];
    }

    public function getTotalProductsByWeightClassId($weight_class_id)
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX."product WHERE weight_class_id = '".(int) $weight_class_id."'");

        return $query->row['total'];
    }

    public function getTotalProductsByLengthClassId($length_class_id)
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX."product WHERE length_class_id = '".(int) $length_class_id."'");

        return $query->row['total'];
    }

    public function getTotalProductsByDownloadId($download_id)
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX."product_to_download WHERE download_id = '".(int) $download_id."'");

        return $query->row['total'];
    }

    public function getTotalProductsByManufacturerId($manufacturer_id)
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX."product WHERE manufacturer_id = '".(int) $manufacturer_id."'");

        return $query->row['total'];
    }

    public function getTotalProductsByAttributeId($attribute_id)
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX."product_attribute WHERE attribute_id = '".(int) $attribute_id."'");

        return $query->row['total'];
    }

    public function getTotalProductsByOptionId($option_id)
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX."product_option WHERE option_id = '".(int) $option_id."'");

        return $query->row['total'];
    }

    public function getTotalProductsByProfileId($recurring_id)
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX."product_recurring WHERE recurring_id = '".(int) $recurring_id."'");

        return $query->row['total'];
    }

    public function getTotalProductsByLayoutId($layout_id)
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX."product_to_layout WHERE layout_id = '".(int) $layout_id."'");

        return $query->row['total'];
    }

    public function updateProduct($vendor_id, $product_id)
    {
        $this->db->query('update `'.DB_PREFIX.'product` SET  status="'.$status.'" WHERE vendor_id="'.$vendor_id.'" AND product_id="'.$product_id.'"');
    }

    public function getProductIds()
    {
        $sql = 'SELECT product_id FROM '.DB_PREFIX.'product WHERE vendor_id=15'; //sourde vendor id

        $query = $this->db->query($sql);

        return $query->rows;
    }
}
