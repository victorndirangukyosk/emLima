<?php

class ModelCatalogHelpCategory extends Model
{
    public function addCategory($data)
    {
        /*        $this->db->query("INSERT INTO " . DB_PREFIX . "help_category SET icon='".$data['icon']."', sort_order = '" . (int) $data['sort_order'] . "', name = '" .  $data['name'] . "'");

                return $this->db->getLastId();
        */

        foreach ($data['help_category'] as $language_id => $value) {
            empty($value['meta_title']) ? $value['meta_title'] = $value['name'] : $value['meta_title'];
            if (isset($category_id)) {
                $this->db->query('INSERT INTO '.DB_PREFIX."help_category SET category_id = '".(int) $category_id."', language_id = '".(int) $language_id."', name = '".$this->db->escape($value['name'])."', sort_order = '".$this->db->escape($value['sort_order'])."', icon = '".$this->db->escape($value['icon'])."', meta_title = '".$this->db->escape($value['meta_title'])."', meta_description = '".$this->db->escape($value['meta_description'])."', meta_keyword = '".$this->db->escape($value['meta_keyword'])."'");
            } else {
                $this->db->query('INSERT INTO '.DB_PREFIX."help_category SET  language_id = '".(int) $language_id."', name = '".$this->db->escape($value['name'])."', sort_order = '".$this->db->escape($value['sort_order'])."', icon = '".$this->db->escape($value['icon'])."', meta_title = '".$this->db->escape($value['meta_title'])."', meta_description = '".$this->db->escape($value['meta_description'])."', meta_keyword = '".$this->db->escape($value['meta_keyword'])."'");
                $category_id = $this->db->getLastId();
            }
        }

        foreach ($data['seo_url'] as $language_id => $value) {
            $alias = empty($value) ? $data['help_category'][$language_id]['name'] : $value;

            $alias = $this->model_catalog_url_alias->generateAlias($alias);

            if ($alias) {
                $this->db->query('INSERT INTO '.DB_PREFIX."url_alias SET query = 'help_id=".(int) $category_id."', keyword = '".$this->db->escape($alias)."', language_id = '".$language_id."'");
            }
        }

        $this->cache->delete('help_category');

        return $category_id;
    }

    public function editCategory($category_id, $data)
    {
        /*$this->db->query("UPDATE " . DB_PREFIX . "help_category SET icon='".$data['icon']."', sort_order = '" . (int) $data['sort_order'] . "', name = '" . $data['name']."' WHERE category_id = '" . (int) $category_id . "'");*/

        $this->db->query('DELETE FROM '.DB_PREFIX."help_category WHERE category_id = '".(int) $category_id."'");

        foreach ($data['help_category'] as $language_id => $value) {
            empty($value['meta_title']) ? $value['meta_title'] = $value['name'] : $value['meta_title'];

            $this->db->query('INSERT INTO '.DB_PREFIX."help_category SET category_id = '".(int) $category_id."', language_id = '".(int) $language_id."', name = '".$this->db->escape($value['name'])."', sort_order = '".$this->db->escape($value['sort_order'])."', icon = '".$this->db->escape($value['icon'])."', meta_title = '".$this->db->escape($value['meta_title'])."', meta_description = '".$this->db->escape($value['meta_description'])."', meta_keyword = '".$this->db->escape($value['meta_keyword'])."'");
        }

        foreach ($data['seo_url'] as $language_id => $value) {
            $this->db->query('DELETE FROM '.DB_PREFIX."url_alias WHERE query = 'help_id=".(int) $category_id."' AND language_id = '".$this->db->escape($language_id)."'");

            $alias = empty($value) ? $data['help_category'][$language_id]['name'] : $value;

            $alias = $this->model_catalog_url_alias->generateAlias($alias);

            if ($alias) {
                $this->db->query('INSERT INTO '.DB_PREFIX."url_alias SET query = 'help_id=".(int) $category_id."', keyword = '".$this->db->escape($alias)."', language_id = '".$language_id."'");
            }
        }

        $this->cache->delete('help_category');
    }

    public function deleteCategory($category_id)
    {
        $this->db->query('DELETE FROM '.DB_PREFIX."help_category WHERE category_id = '".(int) $category_id."'");
    }

    public function getCategory($category_id)
    {
        /*return $this->db->query("SELECT * from `".DB_PREFIX."help_category` WHERE category_id='".$category_id."' and language_id = '" . (int)$this->config->get('config_language_id') . "'")->row;*/

        $information['seo_url'] = [];

        $query = $this->db->query('SELECT keyword, language_id FROM '.DB_PREFIX."url_alias WHERE query = 'help_id=".(int) $category_id."'");

        if ($query->rows) {
            foreach ($query->rows as $row) {
                $information['seo_url'][$row['language_id']] = $row['keyword'];
            }
        }

        return $information;
    }

    public function getRecipeCategories($help_id)
    {
        $sql = 'select rc.category_id from `'.DB_PREFIX.'help_to_category` rc inner join `'.DB_PREFIX.'help_category` c on c.category_id = rc.category_id';
        $sql .= ' WHERE rc.help_id = "'.$help_id.'"';
        $sql .= ' GROUP BY rc.category_id';
        $rows = $this->db->query($sql)->rows;

        $result = [];

        foreach ($rows as $row) {
            $result[] = $row['category_id'];
        }

        return $result;
    }

    public function getCategoryDetail($category_id)
    {
        $category_data = [];

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."help_category WHERE category_id = '".(int) $category_id."'");

        foreach ($query->rows as $result) {
            $category_data[$result['language_id']] = [
                    'name' => $result['name'],
                    'icon' => $result['icon'],
                    'meta_title' => $result['meta_title'],
                    'sort_order' => $result['sort_order'],
                    'meta_description' => $result['meta_description'],
                    'meta_keyword' => $result['meta_keyword'],
                ];
        }

        return $category_data;
    }

    public function getCategories($data = [])
    {
        $sql = 'SELECT * from `'.DB_PREFIX.'help_category`';

        $where = 0;
        if (!empty($data['filter_name'])) {
            $where = 1;
            $sql .= " WHERE name LIKE '".$this->db->escape($data['filter_name'])."%'";
        }

        if ($where) {
            $sql .= " And language_id = '".(int) $this->config->get('config_language_id')."'";
        } else {
            $sql .= " WHERE language_id = '".(int) $this->config->get('config_language_id')."'";
        }

        $sort_data = [
            'name',
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

    public function getTotalCategories()
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX."help_category WHERE language_id = '".(int) $this->config->get('config_language_id')."'");

        return $query->row['total'];
    }

    public function getTotalCategoriesFilter($data)
    {
        $sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX."help_category WHERE language_id = '".(int) $this->config->get('config_language_id')."'";

        if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
            $sql .= "and 	name LIKE '".$this->db->escape($data['filter_name'])."%'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }
}
