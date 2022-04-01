<?php

class ModelApiStock extends Model
{
    public function getAvaialbleStock($store_id)
    {
        $query = $this->db->query('SELECT ps.product_store_id, ps.product_id,p.name,p.unit,ps.store_id,ps.quantity FROM '.DB_PREFIX.'product_to_store ps join '.DB_PREFIX."product p on ps.product_id = p.product_id");

        return $query->rows;
    }
}
