<?php

class ModelApiWallet extends Model
{
    public function getVendorWallet($data = [])
    {
        /*$sql = "SELECT p.product_id, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special";

        $sql .= $this->getExtraConditions($data);

        $sql .= " GROUP BY p.product_id";

        $sort_data = array(
            'pd.name',
            'p.model',
            'p.quantity',
            'p.price',
            'rating',
            'p.sort_order',
            'p.date_added'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
                $sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
            } elseif ($data['sort'] == 'p.price') {
                $sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
            } else {
                $sql .= " ORDER BY " . $data['sort'];
            }
        } else {
            $sql .= " ORDER BY p.sort_order";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC, LCASE(pd.name) DESC";
        } else {
            $sql .= " ASC, LCASE(pd.name) ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;*/
        /*$data = array(
                    'filter_sub_category' => false,
                    'start' => 0,
                    'limit' => 6
                );*/

        //$store_id = 10;
        if (isset($this->session->data['store_id'])) {
            $store_id = $this->session->data['store_id'];

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
                    // if ( !empty( $data['filter_description'] ) ) {
                        //         $this->db->like('product_description.description', $this->db->escape( $data['filter_name'] ) , 'both');
                        // }
                }
                // if ( !empty( $data['filter_tag'] ) ) {
                //      $this->db->like('product_description.tag', $this->db->escape( $data['filter_tag'] ) , 'both');
                // }
            }

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
            $this->db->where('product_to_store.store_id', $store_id);
            $this->db->where('product_to_store.status', 1);
            $this->db->where('product.status', 1);
            $ret = $this->db->get('product_to_store', $limit, $offset)->rows;
            // echo $this->db->last_query();die;
            return $ret;
        } else {
            return [];
        }
    }

    public function getTotalVendorWallet($data = [])
    {
        $sql = 'SELECT COUNT(*) AS total  FROM '.DB_PREFIX.'user c  JOIN '.DB_PREFIX.'vendor_wallet cgd ON (c.user_id = cgd.vendor_id)';

        $sql .= ' where cgd.vendor_id = "'.$this->session->data['api_id'].'"';

        //$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_credit";

        $implode = [];

        if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%".$this->db->escape($data['filter_name'])."%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "email LIKE '".$this->db->escape($data['filter_email'])."%'";
        }

        if (!empty($data['filter_order_id'])) {
            $implode[] = "order_id = '".$this->db->escape($data['filter_order_id'])."'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(cgd.date_added) = DATE('".$this->db->escape($data['filter_date_added'])."')";
        }

        if (!empty($data['filter_type'])) {
            if ('credit' == strtolower($data['filter_type'])) {
                $implode[] = 'amount > 0';
            } else {
                $implode[] = 'amount <= 0';
            }
        }

        if ($implode) {
            $sql .= ' WHERE '.implode(' AND ', $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getAllVendorCredits($data = [])
    {
        $sql = "SELECT CONCAT(c.firstname, ' ', c.lastname) AS name, cgd.*,c.email  FROM ".DB_PREFIX.'user c  JOIN '.DB_PREFIX.'vendor_wallet cgd ON (c.user_id = cgd.vendor_id)';

        //echo "<pre>";print_r($sql);die;
        $sql .= ' where cgd.vendor_id = "'.$this->session->data['api_id'].'"';

        $implode = [];

        if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%".$this->db->escape($data['filter_name'])."%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "c.email LIKE '".$this->db->escape($data['filter_email'])."%'";
        }

        if (!empty($data['filter_order_id'])) {
            $implode[] = "order_id = '".$this->db->escape($data['filter_order_id'])."'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(cgd.date_added) = DATE('".$this->db->escape($data['filter_date_added'])."')";
        }

        if (!empty($data['date_from'])) {
            $implode[] = "DATE(cgd.date_added) >= DATE('".$this->db->escape($data['date_from'])."')";
        }

        if (!empty($data['date_to'])) {
            $implode[] = "DATE(cgd.date_added) <= DATE('".$this->db->escape($data['date_to'])."')";
        }

        if (!empty($data['filter_type'])) {
            if ('credit' == strtolower($data['filter_type'])) {
                $implode[] = 'amount > 0';
            } else {
                $implode[] = 'amount <= 0';
            }
        }

        if ($implode) {
            $sql .= ' AND '.implode(' AND ', $implode);
        }

        $sort_data = [
            'name',
            'email',
            'date_added',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY '.$data['sort'];
        } else {
            //$sql .= " ORDER BY data";
            $sql .= ' ORDER BY data_added';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        //echo "<pre>";print_r($sql);die;
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

    public function getAllVendorCreditsTotal($data = [])
    {
        $sql = "SELECT CONCAT(c.firstname, ' ', c.lastname) AS name, cgd.*,c.email  FROM ".DB_PREFIX.'user c  JOIN '.DB_PREFIX.'vendor_wallet cgd ON (c.user_id = cgd.vendor_id)';

        //echo "<pre>";print_r($sql);die;
        $sql .= ' where cgd.vendor_id = "'.$this->session->data['api_id'].'"';

        $implode = [];

        if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%".$this->db->escape($data['filter_name'])."%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "c.email LIKE '".$this->db->escape($data['filter_email'])."%'";
        }

        if (!empty($data['filter_order_id'])) {
            $implode[] = "order_id = '".$this->db->escape($data['filter_order_id'])."'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(cgd.date_added) = DATE('".$this->db->escape($data['filter_date_added'])."')";
        }

        if (!empty($data['date_from'])) {
            $implode[] = "DATE(cgd.date_added) >= DATE('".$this->db->escape($data['date_from'])."')";
        }

        if (!empty($data['date_to'])) {
            $implode[] = "DATE(cgd.date_added) <= DATE('".$this->db->escape($data['date_to'])."')";
        }

        if (!empty($data['filter_type'])) {
            if ('credit' == strtolower($data['filter_type'])) {
                $implode[] = 'amount > 0';
            } else {
                $implode[] = 'amount <= 0';
            }
        }

        if ($implode) {
            $sql .= ' AND '.implode(' AND ', $implode);
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getCreditTotal($vendor_id)
    {
        $query = $this->db->query('SELECT SUM(amount) AS total FROM '.DB_PREFIX."vendor_wallet WHERE vendor_id = '".(int) $vendor_id."'");

        return $query->row['total'];
    }

    public function getAllAdminCredits($data = [])
    {
        $sql = 'SELECT * FROM '.DB_PREFIX.'admin_wallet where id != 0 ';

        //echo "<pre>";print_r($sql);die;
        $implode = [];

        /*if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }*/

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(date_added) = DATE('".$this->db->escape($data['filter_date_added'])."')";
        }

        if (!empty($data['filter_order_id'])) {
            $implode[] = "order_id = '".$this->db->escape($data['filter_order_id'])."'";
        }

        if (!empty($data['filter_type'])) {
            if ('credit' == strtolower($data['filter_type'])) {
                $implode[] = 'amount > 0';
            } else {
                $implode[] = 'amount <= 0';
            }
        }

        if ($implode) {
            $sql .= ' AND '.implode(' AND ', $implode);
        }

        $sort_data = [
            'date_added',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY '.$data['sort'];
        } else {
            $sql .= ' ORDER BY date_added';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        //echo "<pre>";print_r($sql);die;
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

    public function getTotalAdminWallet($data = [])
    {
        $sql = 'SELECT COUNT(*) AS total  FROM '.DB_PREFIX.'admin_wallet';

        //$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_credit";

        $implode = [];

        /*if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }*/

        if (!empty($data['filter_order_id'])) {
            $implode[] = "order_id = '".$this->db->escape($data['filter_order_id'])."'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(date_added) = DATE('".$this->db->escape($data['filter_date_added'])."')";
        }

        if (!empty($data['filter_type'])) {
            if ('credit' == strtolower($data['filter_type'])) {
                $implode[] = 'amount > 0';
            } else {
                $implode[] = 'amount <= 0';
            }
        }

        if ($implode) {
            $sql .= ' WHERE '.implode(' AND ', $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getAllCustomerCredits($data = [])
    {
        $sql = "SELECT CONCAT(c.firstname, ' ', c.lastname) AS name, cgd.*,c.email  FROM ".DB_PREFIX.'customer c  JOIN '.DB_PREFIX.'customer_credit cgd ON (c.customer_id = cgd.customer_id)';

        //echo "<pre>";print_r($sql);die;
        $implode = [];

        if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%".$this->db->escape($data['filter_name'])."%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "c.email LIKE '".$this->db->escape($data['filter_email'])."%'";
        }

        if (!empty($data['filter_order_id'])) {
            $implode[] = "order_id = '".$this->db->escape($data['filter_order_id'])."'";
        }

        if (!empty($data['filter_type'])) {
            if ('credit' == strtolower($data['filter_type'])) {
                $implode[] = 'amount > 0';
            } else {
                $implode[] = 'amount <= 0';
            }
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(cgd.date_added) = DATE('".$this->db->escape($data['filter_date_added'])."')";
        }

        if ($implode) {
            $sql .= ' AND '.implode(' AND ', $implode);
        }

        $sort_data = [
            'name',
            'email',
            'date_added',
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

        //echo "<pre>";print_r($sql);die;
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

    public function getTotalCustomerWallet($data = [])
    {
        $sql = 'SELECT COUNT(*) AS total  FROM '.DB_PREFIX.'customer c  JOIN '.DB_PREFIX.'customer_credit cgd ON (c.customer_id = cgd.customer_id)';

        //$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_credit";

        $implode = [];

        if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%".$this->db->escape($data['filter_name'])."%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "email LIKE '".$this->db->escape($data['filter_email'])."%'";
        }

        if (!empty($data['filter_order_id'])) {
            $implode[] = "order_id = '".$this->db->escape($data['filter_order_id'])."'";
        }

        if (!empty($data['filter_type'])) {
            if ('credit' == strtolower($data['filter_type'])) {
                $implode[] = 'amount > 0';
            } else {
                $implode[] = 'amount <= 0';
            }
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(cgd.date_added) = DATE('".$this->db->escape($data['filter_date_added'])."')";
        }

        if ($implode) {
            $sql .= ' WHERE '.implode(' AND ', $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getAllVendorCreditsByAdmin($data = [])
    {
        $sql = "SELECT CONCAT(c.firstname, ' ', c.lastname) AS name, cgd.*,c.email  FROM ".DB_PREFIX.'user c  JOIN '.DB_PREFIX.'vendor_wallet cgd ON (c.user_id = cgd.vendor_id)';

        //echo "<pre>";print_r($sql);die;
        $implode = [];

        if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%".$this->db->escape($data['filter_name'])."%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "c.email LIKE '".$this->db->escape($data['filter_email'])."%'";
        }

        if (!empty($data['filter_order_id'])) {
            $implode[] = "order_id = '".$this->db->escape($data['filter_order_id'])."'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(cgd.date_added) = DATE('".$this->db->escape($data['filter_date_added'])."')";
        }

        if (!empty($data['filter_type'])) {
            if ('credit' == strtolower($data['filter_type'])) {
                $implode[] = 'amount > 0';
            } else {
                $implode[] = 'amount <= 0';
            }
        }

        if ($implode) {
            $sql .= ' AND '.implode(' AND ', $implode);
        }

        $sort_data = [
            'name',
            'email',
            'date_added',
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

        //echo "<pre>";print_r($sql);die;
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

    public function getTotalVendorWalletByAdmin($data = [])
    {
        $sql = 'SELECT COUNT(*) AS total  FROM '.DB_PREFIX.'user c  JOIN '.DB_PREFIX.'vendor_wallet cgd ON (c.user_id = cgd.vendor_id)';

        //$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_credit";

        $implode = [];

        if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%".$this->db->escape($data['filter_name'])."%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "email LIKE '".$this->db->escape($data['filter_email'])."%'";
        }

        if (!empty($data['filter_order_id'])) {
            $implode[] = "order_id = '".$this->db->escape($data['filter_order_id'])."'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(cgd.date_added) = DATE('".$this->db->escape($data['filter_date_added'])."')";
        }

        if (!empty($data['filter_type'])) {
            if ('credit' == strtolower($data['filter_type'])) {
                $implode[] = 'amount > 0';
            } else {
                $implode[] = 'amount <= 0';
            }
        }

        if ($implode) {
            $sql .= ' WHERE '.implode(' AND ', $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getVendorCreditTotal()
    {
        $query = $this->db->query('SELECT SUM(amount) AS total FROM '.DB_PREFIX.'vendor_wallet ');

        return $query->row['total'];
    }

    public function getAdminCreditTotal()
    {
        $query = $this->db->query('SELECT SUM(amount) AS total FROM '.DB_PREFIX.'admin_wallet');

        return $query->row['total'];
    }

    public function getCustomerCreditTotal()
    {
        $query = $this->db->query('SELECT SUM(amount) AS total FROM '.DB_PREFIX.'customer_credit');

        return $query->row['total'];
    }
}
