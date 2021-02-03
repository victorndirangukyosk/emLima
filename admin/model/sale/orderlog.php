<?php

class ModelSaleOrderlog extends Model {

    public function addOrderLog($product) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "order_edit_log SET vendor_id='" . (int) $product['vendor_id'] . "', store_id='" . (int) $product['store_id'] . "', unit='" . $product['unit'] . "', order_id = '" . (int) $product['order_id'] . "', product_id = '" . (int) $product['product_store_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (float) $product['quantity'] . "', order_product_id = '" . (int) $product['order_product_id'] . "', order_status_id = '" . (int) $product['order_status_id'] . "', general_product_id = '" . (int) $product['general_product_id'] . "', old_quantity = '" . (int) $product['old_quantity'] . "', created_at = NOW()");
    }

    public function getOrderLog($order_id) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "order_edit_log WHERE order_id = '" . (int) $order_id . "'");
        return $query->rows;
    }

}
