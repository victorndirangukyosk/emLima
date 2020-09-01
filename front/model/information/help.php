<?php

class ModelInformationHelp extends Model
{
    public function searchData($q)
    {
        $sql = 'select * from `'.DB_PREFIX.'help`';
        $sql .= ' WHERE question LIKE "%'.$q.'%"';
        $sql .= ' OR answer LIKE "%'.$q.'%"';

        return $this->db->query($sql)->rows;
    }

    public function getData($category_id)
    {
        return $this->db->query('select * from `'.DB_PREFIX.'help` WHERE category_id="'.$category_id.'"')->rows;
    }

    public function getCategory($category_id)
    {
        return $this->db->query('select * from `'.DB_PREFIX."help_category` WHERE category_id='".$category_id."' and language_id = '".(int) $this->config->get('config_language_id')."'")->row;
    }

    public function getCategories()
    {
        return $this->db->query('select * from `'.DB_PREFIX."help_category` WHERE language_id = '".(int) $this->config->get('config_language_id')."' order by sort_order")->rows;
    }
}
