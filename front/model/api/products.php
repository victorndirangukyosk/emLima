<?php

class ModelApiProducts extends Model
{
    public function getProducts($data = [])
    {
        if (isset($data['store_id'])) {
            $this->db->select('product_to_store.*,product.*,product_description.*', false);
            $this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
            $this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');
            $this->db->join('product_to_category', 'product_to_category.product_id = product_to_store.product_id', 'left');

            if (!empty($data['filter_category_id'])) {
                if (!empty($data['filter_sub_category'])) {
                    $this->db->join('category_path', 'category_path.category_id = product_to_category.category_id', 'left');
                }
            }
            if (!empty($data['filter_category_id'])) {
                if (!empty($data['filter_sub_category'])) {
                    $this->db->where('category_path.path_id', (int) $data['filter_category_id']);
                } else {
                    $this->db->where('product_to_category.category_id', (int) $data['filter_category_id']);
                }
            }

            if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
                if (!empty($data['filter_name'])) {
                    $this->db->like('product_description.name', $this->db->escape($data['filter_name']), 'both');
                    //$this->db->where('(MATCH('. DB_PREFIX .'product_description.name) AGAINST("'.$data['filter_name'].'"))', NULL, FALSE);

                        // if ( !empty( $data['filter_description'] ) ) {
                        //         $this->db->like('product_description.description', $this->db->escape( $data['filter_name'] ) , 'both');
                        // }
                }
                // if ( !empty( $data['filter_tag'] ) ) {
                //      $this->db->like('product_description.tag', $this->db->escape( $data['filter_tag'] ) , 'both');
                // }
            }

            if (!empty($data['search'])) {
                $words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['search'])));

                foreach ($words as $word) {
                    $this->db->like('product_description.name', $this->db->escape($data['search']), 'both');
                }

                /*

                 if (!empty($data['description'])) {
                     $sql .= " OR pd.description LIKE '%" . $this->db->escape($data['search']) . "%'";
                 }

                 $sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['search'])) . "'";
                 $sql .= ")";*/
            }

            if (isset($data['start']) && isset($data['limit'])) {
                if ($data['start'] < 0) {
                    $data['start'] = 0;
                    $offset = $data['start'];
                } else {
                    $offset = $data['start'];
                }
                if ($data['limit'] < 1) {
                    $data['limit'] = 20;
                    $limit = $data['limit'];
                } else {
                    $limit = $data['limit'];
                }
            }

            $sort_data = [
                'product_description.name',
                'product.model',
                'product_to_store.quantity',
                'product_to_store.price',
                'product.sort_order',
                'product.date_added',
            ];
            if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
                if ('product_description.name' == $data['sort'] || 'product.model' == $data['sort']) {
                    $this->db->order_by($data['sort'], 'asc');
                } else {
                    $this->db->order_by($data['sort'], 'asc');
                }
            } else {
                $this->db->order_by('product.sort_order', 'asc');
            }
            $this->db->group_by('product_to_store.product_store_id');
            $this->db->where('product_to_store.store_id', $data['store_id']);
            //$this->db->where('product_to_store.status', 1);
            $this->db->where('product.status', 1);
            if (isset($data['start']) && isset($data['limit'])) {
                $ret = $this->db->get('product_to_store', $limit, $offset)->rows;
            } else {
                $ret = $this->db->get('product_to_store')->rows;
            }

            // echo $this->db->last_query();die;
            return $ret;
        } else {
            echo $data;
            echo 'store id not set';

            return [];
        }
    }

    public function getAdminProducts($data = [])
    {
        $sql = 'SELECT p.*,pd.*,p2c.product_id product_id2 FROM '.DB_PREFIX.'product p LEFT JOIN '.DB_PREFIX.'product_description pd ON (p.product_id = pd.product_id) LEFT JOIN '.DB_PREFIX.'product_to_category p2c ON (p.product_id = p2c.product_id)';

        if (!empty($data['filter_store'])) {
            $sql .= ' LEFT JOIN `'.DB_PREFIX.'product_to_store` ps on ps.product_id = p.product_id';
        }

        $sql .= " WHERE pd.language_id = '".(int) $this->config->get('config_language_id')."'";

        if (!empty($data['filter_store'])) {
            $sql .= ' AND ps.store_id="'.$data['filter_store'].'"';
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

        if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
            $lGroup = false;
            $sql .= " AND p2c.category_id = '".$this->db->escape($data['filter_category'])."'";
        } else {
            $lGroup = true;
        }

        // if ( isset( $data['filter_quantity'] ) && !is_null( $data['filter_quantity'] ) ) {
        //     $sql .= " AND p.quantity = '" . (int) $data['filter_quantity'] . "'";
        // }

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

    public function getProduct($product_id, $store_id = null)
    {
        if (isset($store_id)) {
            //$this->db->select('product_to_store.*,product_description.*,product.image', FALSE);
            $this->db->select('product_to_store.*,product_description.*,product.unit,product.image,product.model', false);

            $this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
            $this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');
            /*$this->db->join('product_to_category', 'product_to_category.product_id = product_to_store.product_id', 'left');*/
            $this->db->group_by('product_to_store.product_store_id');
            $this->db->where('product_to_store.store_id', $store_id);
            //$this->db->where('product_to_store.status', 1);
            $this->db->where('product.status', 1);
            $this->db->where('product_to_store.product_id', $product_id);
            $ret = $this->db->get('product_to_store')->row;

            return $ret;
        } else {
            return [];
        }
    }

    public function getAdminProduct($product_id)
    {
        /*$this->db->select('product.*,product_description.*,product.image', FALSE);
        $this->db->join('product_description', 'product_description.product_id = product.product_id', 'left');
        $this->db->where('product.product_id',$product_id);
        $ret = $this->db->get('product')->row;
        return $ret;*/

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

    public function getProductImages($product_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."product_image WHERE product_id = '".(int) $product_id."' ORDER BY sort_order ASC");

        return $query->rows;
    }

    public function getTotals($data = [])
    {
        $sql = 'SELECT COUNT(DISTINCT p.product_id) AS number';

        $sql .= $this->getExtraConditions($data);

        //echo "<pre>";print_r($sql);die;
        $query = $this->db->query($sql);

        return $query->row;
    }

    public function getAdminTotals($data = [])
    {
        $sql = 'SELECT count(distinct p.product_id) AS number FROM '.DB_PREFIX.'product p LEFT JOIN '.DB_PREFIX.'product_description pd ON (p.product_id = pd.product_id)';

        $sql .= ' LEFT JOIN '.DB_PREFIX.'product_to_category p2c ON (p.product_id = p2c.product_id)';

        $sql .= " WHERE pd.language_id = '".(int) $this->config->get('config_language_id')."'";

        if (!empty($data['filter_name'])) {
            $sql .= " AND pd.name LIKE '".$this->db->escape($data['filter_name'])."%'";
        }

        if (!empty($data['filter_model'])) {
            $sql .= " AND p.model LIKE '".$this->db->escape($data['filter_model'])."%'";
        }

        if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
            $sql .= " AND p2c.category_id = '".$this->db->escape($data['filter_category'])."'";
        }

        if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
            $sql .= " AND p.quantity = '".(int) $data['filter_quantity']."'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND p.status = '".(int) $data['filter_status']."'";
        }

        $query = $this->db->query($sql);

        return $query->row;
    }

    public function getCategories($data = [])
    {
        $sql = "SELECT cp.category_id AS category_id,c1.image as image,cd1.description as description,cd1.meta_description as meta_description, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR ' > ') AS name, c1.parent_id, c1.status, c1.sort_order FROM ".DB_PREFIX.'category_path cp LEFT JOIN '.DB_PREFIX.'category c1 ON (cp.category_id = c1.category_id) LEFT JOIN '.DB_PREFIX.'category c2 ON (cp.path_id = c2.category_id) LEFT JOIN '.DB_PREFIX.'category_description cd1 ON (cp.path_id = cd1.category_id) LEFT JOIN '.DB_PREFIX."category_description cd2 ON (cp.category_id = cd2.category_id) WHERE cd1.language_id = '".(int) $this->config->get('config_language_id')."' AND cd2.language_id = '".(int) $this->config->get('config_language_id')."'";

        if (!empty($data['filter_name'])) {
            $sql .= " AND cd2.name LIKE '".$this->db->escape($data['filter_name'])."%'";
        }

        if (isset($data['filter_status']) and !is_null($data['filter_status'])) {
            $sql .= " AND c1.status = '".$data['filter_status']."'";
        }
        $sql .= ' GROUP BY cp.category_id';

        $sort_data = [
            'status',
            'sort_order',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY '.$data['sort'];
        } else {
            $sql .= ' ORDER BY sort_order';
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

    public function getProductCategories($product_id)
    {
        $product_category_data = [];

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."product_to_category WHERE product_id = '".(int) $product_id."'");

        foreach ($query->rows as $result) {
            $product_category_data[] = $result['category_id'];
        }

        return $product_category_data;
    }

    public function getTotalAdminProducts($data = [])
    {
        $sql = 'SELECT count(distinct p.product_id) AS total FROM '.DB_PREFIX.'product p LEFT JOIN '.DB_PREFIX.'product_description pd ON (p.product_id = pd.product_id)';

        $sql .= ' LEFT JOIN '.DB_PREFIX.'product_to_category p2c ON (p.product_id = p2c.product_id)';

        $sql .= " WHERE pd.language_id = '".(int) $this->config->get('config_language_id')."'";

        if (!empty($data['filter_name'])) {
            $sql .= " AND pd.name LIKE '".$this->db->escape($data['filter_name'])."%'";
        }

        if (!empty($data['filter_model'])) {
            $sql .= " AND p.model LIKE '".$this->db->escape($data['filter_model'])."%'";
        }

        if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
            $sql .= " AND p2c.category_id = '".$this->db->escape($data['filter_category'])."'";
        }

        if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
            $sql .= " AND p.quantity = '".(int) $data['filter_quantity']."'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND p.status = '".(int) $data['filter_status']."'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    private function getExtraConditions($data)
    {
        $sql = '';

        if (!empty($data['category'])) {
            if (!empty($data['sub_category'])) {
                $sql .= ' FROM '.DB_PREFIX.'category_path cp LEFT JOIN '.DB_PREFIX.'product_to_category p2c ON (cp.category_id = p2c.category_id)';
            } else {
                $sql .= ' FROM '.DB_PREFIX.'product_to_category p2c';
            }

            if (!empty($data['filter'])) {
                $sql .= ' LEFT JOIN '.DB_PREFIX.'product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN '.DB_PREFIX.'product p ON (pf.product_id = p.product_id)';
            } else {
                $sql .= ' LEFT JOIN '.DB_PREFIX.'product p ON (p2c.product_id = p.product_id)';
            }
        } else {
            $sql .= ' FROM '.DB_PREFIX.'product p';
        }

        // $sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1'  AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

        $sql .= ' LEFT JOIN '.DB_PREFIX.'product_description pd ON (p.product_id = pd.product_id) LEFT JOIN '.DB_PREFIX."product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '".(int) $this->config->get('config_language_id')."' AND p.status = '1'  AND p2s.store_id = '".$data['store_id']."' ";

        if (!empty($data['category'])) {
            if (!empty($data['sub_category'])) {
                $sql .= " AND cp.path_id = '".(int) $data['category']."'";
            } else {
                $sql .= " AND p2c.category_id = '".(int) $data['category']."'";
            }

            if (!empty($data['filter'])) {
                $implode = [];

                $filters = explode(',', $data['filter']);

                foreach ($filters as $filter_id) {
                    $implode[] = (int) $filter_id;
                }

                $sql .= ' AND pf.filter_id IN ('.implode(',', $implode).')';
            }
        }

        if (!empty($data['search'])) {
            $sql .= ' AND (';

            $implode = [];

            $words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['search'])));

            foreach ($words as $word) {
                $implode[] = "pd.name LIKE '%".$this->db->escape($word)."%'";
            }

            if ($implode) {
                $sql .= ' '.implode(' AND ', $implode).'';
            }

            if (!empty($data['description'])) {
                $sql .= " OR pd.description LIKE '%".$this->db->escape($data['search'])."%'";
            }

            $sql .= " OR LCASE(p.model) = '".$this->db->escape(utf8_strtolower($data['search']))."'";
            $sql .= ')';
        }

        /*if (!empty($data['manufacturer'])) {
            $sql .= " AND p.manufacturer_id = '" . (int)$data['manufacturer'] . "'";
        }*/

        return $sql;
    }

    private function getAdminExtraConditions($data = [])
    {
        $sql = 'SELECT p.*,pd.*,p2c.product_id product_id2 FROM '.DB_PREFIX.'product p LEFT JOIN '.DB_PREFIX.'product_description pd ON (p.product_id = pd.product_id) LEFT JOIN '.DB_PREFIX.'product_to_category p2c ON (p.product_id = p2c.product_id)';

        if (!empty($data['filter_store'])) {
            $sql .= ' LEFT JOIN `'.DB_PREFIX.'product_to_store` ps on ps.product_id = p.product_id';
        }

        $sql .= " WHERE pd.language_id = '".(int) $this->config->get('config_language_id')."'";

        if (!empty($data['filter_store'])) {
            $sql .= ' AND ps.store_id="'.$data['filter_store'].'"';
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

        if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
            $lGroup = false;
            $sql .= " AND p2c.category_id = '".$this->db->escape($data['filter_category'])."'";
        } else {
            $lGroup = true;
        }

        // if ( isset( $data['filter_quantity'] ) && !is_null( $data['filter_quantity'] ) ) {
        //     $sql .= " AND p.quantity = '" . (int) $data['filter_quantity'] . "'";
        // }

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

    public function editProduct($store_product_id, $data)
    {
        $this->trigger->fire('pre.admin.product.edit', $data);

        /*        if ($this->user->isVendor()) {
                    $data['status'] = $this->config->get('config_auto_approval_product');
                }
        */
        //$data['status'] = 1;
        //echo "efre";
        $query = 'UPDATE '.DB_PREFIX."product_to_store SET product_id = '".$data['product_id']."', store_id = '".$this->db->escape($data['product_store'])."', price = '".$data['price']."',special_price = '".$data['special_price']."',tax_percentage = '".$data['tax_percentage']."',quantity = '".$data['quantity']."',min_quantity = '".$data['min_quantity']."',subtract_quantity = '".$data['subtract_quantity']."',status = '".$data['status']."',tax_class_id = '".$data['tax_class_id']."' WHERE product_store_id = '".(int) $store_product_id."'";

        $this->db->query($query);

        //  delete variation here
        /*$this->db->query("DELETE FROM " . DB_PREFIX . "variation_to_product_store WHERE product_store_id = '" . (int) $store_product_id . "'");
        // insert variation
        if (isset( $this->request->post['product_variation']['variation'])) {

            foreach ( $this->request->post['product_variation']['variation'] as $prv => $value ) {
                $this->db->query( "INSERT INTO " . DB_PREFIX . "variation_to_product_store SET  variation_id = '".$value."', product_store_id = '" .$store_product_id . "', price = '" .$this->request->post['product_variation']['price'][$prv] . "',special_price = '" . $this->request->post['product_variation']['special_price'][$prv] . "'" );

            }
        }
        $this->trigger->fire( 'post.admin.product.edit', $store_product_id );
*/
        return $store_product_id;
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
                'unit' => $result['unit'],
                'tag' => $result['tag'],
            ];
        }

        return $product_description_data;
    }

    public function editAdminProduct($product_id, $data)
    {
        $this->db->query('UPDATE '.DB_PREFIX."product SET default_variation_name = '".$data['default_variation_name']."', default_price = '".$this->db->escape($data['product_price'])."', model = '".$this->db->escape($data['model'])."', status = '".(int) $data['status']."' ,sort_order = '".(int) $data['sort_order']."', date_modified = NOW() WHERE product_id = '".(int) $product_id."'");

        if (isset($data['image'])) {
            $this->db->query('UPDATE '.DB_PREFIX."product SET image = '".$this->db->escape($data['image'])."' WHERE product_id = '".(int) $product_id."'");
        }

        $this->db->query('DELETE FROM '.DB_PREFIX."product_description WHERE product_id = '".(int) $product_id."'");

        foreach ($data['product_description'] as $language_id => $value) {
            empty($value['meta_title']) ? $value['meta_title'] = $value['name'] : $value['meta_title'];

            $this->db->query('INSERT INTO '.DB_PREFIX."product_description SET product_id = '".(int) $product_id."', language_id = '".(int) $language_id."', name = '".$this->db->escape($value['name'])."', unit = '".$this->db->escape($value['unit'])."', description = '".$this->db->escape($value['description'])."', tag = '".$this->db->escape($value['tag'])."', meta_title = '".$this->db->escape($value['meta_title'])."', meta_description = '".$this->db->escape($value['meta_description'])."', meta_keyword = '".$this->db->escape($value['meta_keyword'])."'");

            if (isset($value['name']) && $value['unit']) {
                $this->db->query('UPDATE '.DB_PREFIX."product SET name = '".$this->db->escape($value['name'])."', unit = '".$this->db->escape($value['unit'])."' WHERE product_id = '".(int) $product_id."'");
            }
        }

        /*
        if ( isset( $data['product_variation'] ) ) {
            foreach ( $data['product_variation'] as $product_variation ) {
                $this->db->query( "INSERT INTO " . DB_PREFIX . "product_variation SET  product_id = '" . (int) $product_id . "', image = '" . $this->db->escape( $product_variation['image'] ) . "', name = '" . $product_variation['name'] . "', sort_order = '" . (int) $product_variation['sort_order'] . "',model = '" . (int) $product_variation['model'] . "'" );


            }
        }*/

        //image update
        /*if (isset($data['product_image'])) {
            foreach ($data['product_image'] as $product_image) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($product_image['image']) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
            }
        }*/
        //end
        /*foreach ( $data['seo_url'] as $language_id => $value ) {
            $this->db->query( "DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int) $product_id . "' AND language_id = '" . $this->db->escape( $language_id ) . "'" );

            $alias = empty( $value ) ? $data['product_description'][$language_id]['name'] : $value;

            $alias = $this->model_catalog_url_alias->generateAlias( $alias );

            if ( $alias ) {
                $this->db->query( "INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int) $product_id . "', keyword = '" . $this->db->escape( $alias ) . "', language_id = '" . $language_id . "'" );
            }
        }*/

        //$this->cache->delete( 'product' );

        $this->trigger->fire('post.admin.product.edit', $product_id);
    }

    public function deleteProduct($product_store_id)
    {
        $this->db->query('DELETE FROM '.DB_PREFIX."product_to_store WHERE product_store_id = '".(int) $product_store_id."'");
    }

    public function deleteAdminProduct($product_id)
    {
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
    }

    public function getTotalProducts($data = [])
    {
        if (isset($data['store_id'])) {
            $this->db->select('product_to_store.*,product.*,product_description.*', false);
            $this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
            $this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');
            $this->db->join('product_to_category', 'product_to_category.product_id = product_to_store.product_id', 'left');

            if (!empty($data['filter_category_id'])) {
                if (!empty($data['filter_sub_category'])) {
                    $this->db->join('category_path', 'category_path.category_id = product_to_category.category_id', 'left');
                }
            }
            if (!empty($data['filter_category_id'])) {
                if (!empty($data['filter_sub_category'])) {
                    $this->db->where('category_path.path_id', (int) $data['filter_category_id']);
                } else {
                    $this->db->where('product_to_category.category_id', (int) $data['filter_category_id']);
                }
            }

            if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
                if (!empty($data['filter_name'])) {
                    $this->db->like('product_description.name', $this->db->escape($data['filter_name']), 'both');
                    //$this->db->where('(MATCH('. DB_PREFIX .'product_description.name) AGAINST("'.$data['filter_name'].'"))', NULL, FALSE);
                }
            }

            if (!empty($data['search'])) {
                $words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['search'])));

                foreach ($words as $word) {
                    $this->db->like('product_description.name', $this->db->escape($data['search']), 'both');
                }
            }

            $this->db->group_by('product_to_store.product_store_id');
            $this->db->where('product_to_store.store_id', $data['store_id']);
            $this->db->where('product_description.language_id', $this->config->get('config_language_id'));
            //$this->db->where('product_to_store.status', 1);
            $this->db->where('product.status', 1);

            $ret = $this->db->get('product_to_store')->rows;
            // echo $this->db->last_query();die;
            return count($ret);
        } else {
            return 0;
        }
    }

    public function addProduct($data)
    {
        //echo '<pre>';print_r($data);exit;
        $this->trigger->fire('pre.admin.product.add', $data);
        $data['status'] = $this->config->get('config_auto_approval_product');

        $this->db->query('INSERT INTO '.DB_PREFIX."product_to_store SET  product_id = '".$data['product_id']."', store_id = '".$this->db->escape($data['product_store'])."', price = '".$data['price']."',special_price = '".$data['special_price']."',tax_percentage = '".$data['tax_percentage']."',quantity = '".$data['quantity']."',min_quantity = '".$data['min_quantity']."',subtract_quantity = '".$data['subtract_quantity']."',status = '".$data['status']."',tax_class_id = '".$data['tax_class_id']."'");
        $product_store_id = $this->db->getLastId();

        foreach ($data['product_variation']['variation'] as $prv => $value) {
            $this->db->query('INSERT INTO '.DB_PREFIX."variation_to_product_store SET  variation_id = '".$value."', product_store_id = '".$product_store_id."', price = '".$data['product_variation']['price'][$prv]."',special_price = '".$data['product_variation']['special_price'][$prv]."'");
        }

        $this->cache->delete('product');

        $this->trigger->fire('post.admin.product.add', $product_id);

        return $product_store_id;
    }
}
