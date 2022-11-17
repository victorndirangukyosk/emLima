<?php

class ModelCommonProductEntry extends Model {


    public function getTotalProductsEntry($data = [])
    {
        $sql = 'SELECT count(p.product_entry_id) AS total FROM '.DB_PREFIX.'product_entry p where 1=1 ';
        

        if (!empty($data['filter_name'])) {
            $sql .= " AND p.product_name LIKE '%".$this->db->escape($data['filter_name'])."%'";
        }

        if (!empty($data['filter_source'])) {
            $sql .= " AND p.source LIKE '".$this->db->escape($data['filter_source'])."%'";
        }        

        if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
            $sql .= " AND p.quantity = '". $data['filter_quantity']."'";
        }

        if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
            $sql .= " AND p.price = '". $data['filter_price']."'";
        }         

        $query = $this->db->query($sql);

        return $query->row['total'];    }

    public function getProductEntries($data = [])
    {
        $sql = 'SELECT p.* FROM '.DB_PREFIX.'product_entry p where 1=1 ';

         

        if (!empty($data['filter_name'])) {
            $sql .= " AND p.product_name LIKE '%".$this->db->escape($data['filter_name'])."%'";
        }

        if (!empty($data['filter_source'])) {
            $sql .= " AND p.source LIKE '".$this->db->escape($data['filter_source'])."%'";
        }

        // if (!empty($data['filter_price'])) {
        //     $sql .= " AND p.price = '". $data['filter_price']."'";
        // }

        

        // if ( isset( $data['filter_quantity'] ) && !is_null( $data['filter_quantity'] ) ) {
        //     $sql .= " AND p.quantity = '" .  $data['filter_quantity'] . "'";
        // }
 

        if (!empty($data['filter_date_added']) && empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(p.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_date_added']) && !empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(p.date_added) BETWEEN DATE('" . $this->db->escape($data['filter_date_added']) . "') AND DATE('" . $this->db->escape($data['filter_date_added_end']) . "')";
        }

        // $sql .= ' GROUP BY p.product_name';

        $sort_data = [
            'p.product_name',
            'p.source',
            'p.price',
            'p.product_entry_id',           
            'p.quantity',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY '.$data['sort'];
        } else {
            $sql .= ' ORDER BY p.product_entry_id';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else if(isset($data['order']) && ('ASC' == $data['order']))
        {
            $sql .= ' ASC';
        }        
        else {
            $sql .= ' DESC';
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
        // echo "<pre>";print_r($sql);die;

        $query = $this->db->query($sql);

        return $query->rows;
    }



    public function addProductEntry($data) {
        $this->db->query('INSERT INTO `' . DB_PREFIX . "product_entry` SET product_name = '" . $this->db->escape($data['name']) . "', unit = '" . $this->db->escape($data['unit']) . "', source = '" . $this->db->escape($data['source']) . "', quantity = '" . $this->db->escape($data['quantity']) . "', price = '" . $data['price'] . "', date_added = NOW()");

        return $this->db->getLastId();
    }

    public function editProductEntry($product_entry_id, $data) {
        $this->db->query('UPDATE `' . DB_PREFIX . "product_entry` SET product_name = '" . $this->db->escape($data['name']) . "', unit = '" . $this->db->escape($data['unit']) . "', source = '" . $this->db->escape($data['source']) . "', quantity = '" . $this->db->escape($data['quantity']) . "', price = '" . $this->db->escape($data['price']) . "' WHERE product_entry_id = '" . (int) $product_entry_id . "'");

    }


    public function getProductEntry($product_entry_id) {
        $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . "product_entry` p WHERE p.product_entry_id = '" . (int) $product_entry_id . "'");

        return $query->row;
    }

    public function deleteProductEntry($product_entry_id) {
        $this->db->query('DELETE FROM `' . DB_PREFIX . "product_entry` WHERE product_entry_id = '" . (int) $product_entry_id . "'");
    }
}
