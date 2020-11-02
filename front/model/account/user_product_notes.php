<?php

class ModelAccountUserProductNotes extends Model {

    public function getUserProductNotes($customer_id) {
        $order_info = $this->db->query('select * from ' . DB_PREFIX . 'product LEFT JOIN ' . DB_PREFIX . 'customer_product_notes on(' . DB_PREFIX . 'customer_product_notes.product_id = ' . DB_PREFIX . 'product.product_id) LEFT JOIN ' . DB_PREFIX . 'product_to_store on(' . DB_PREFIX . 'product_to_store.product_store_id = ' . DB_PREFIX . 'customer_product_notes.product_store_id) WHERE ' . DB_PREFIX . 'customer_product_notes.customer_id="' . $customer_id . '"')->row;
        return $order_info;
    }

}
