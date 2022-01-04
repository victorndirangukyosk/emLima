<?php

class ModelAccountMissedrejectedproducts extends Model {

    public function addProducts($data) {
        $this->db->query('INSERT INTO ' . DB_PREFIX . "missed_rejected_products SET order_id = '" . (int) $data['order_id'] . "', product_id = '" . (int) $data['product_id'] . "', product_store_id = '" . (int) $data['product_store_id'] . "', quantity = '" . $data['quantity'] . "', type = '" . $data['type'] . "', notes = '" . $data['notes'] . "'");
        return $this->db->getLastId();
    }

}
