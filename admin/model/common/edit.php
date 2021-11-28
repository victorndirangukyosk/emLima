<?php

class ModelCommonEdit extends Model
{
    public function changeStatus($type, $ids, $status, $extension = false)
    {
        if ($extension) {
            foreach ($ids as $id) {
                $this->db->query('UPDATE '.DB_PREFIX."setting SET `value` = {$status} WHERE `code` = '{$id}' AND `key` = '{$id}_status'", 'query');
            }
        } else {
            foreach ($ids as $id) {
                $this->db->query('UPDATE '.DB_PREFIX."{$type} SET status = {$status} WHERE {$type}_id = {$id}", 'query');
            }
        }
    }

    public function mychangeStatus($type, $ids, $status, $extension = false)
    {
        if ($extension) {
            foreach ($ids as $id) {
                $this->db->query('UPDATE '.DB_PREFIX."setting SET `value` = {$status} WHERE `code` = '{$id}' AND `key` = '{$id}_status'", 'query');
            }
        } else {
            foreach ($ids as $id) {
                $this->db->query('UPDATE '.DB_PREFIX."product_to_store SET status = {$status} WHERE product_store_id = {$id}", 'query');
            }
        }
    }
}
