<?php

class ModelCatalogRecipeCategory extends Model
{
    public function addCategory($data)
    {
        $this->db->query('INSERT INTO '.DB_PREFIX."recipe_category SET sort_order = '".(int) $data['sort_order']."', name = '".$data['name']."'");

        return $this->db->getLastId();
    }

    public function editCategory($category_id, $data)
    {
        $this->db->query('UPDATE '.DB_PREFIX."recipe_category SET sort_order = '".(int) $data['sort_order']."', name = '".$data['name']."' WHERE category_id = '".(int) $category_id."'");
    }

    public function deleteCategory($category_id)
    {
        $this->db->query('DELETE FROM '.DB_PREFIX."recipe_to_category WHERE category_id = '".(int) $category_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."recipe_category WHERE category_id = '".(int) $category_id."'");
    }

    public function getCategory($category_id)
    {
        return $this->db->query('SELECT * from `'.DB_PREFIX."recipe_category` WHERE category_id='".$category_id."'")->row;
    }

    public function getRecipeCategories($recipe_id)
    {
        $sql = 'select rc.category_id from `'.DB_PREFIX.'recipe_to_category` rc inner join `'.DB_PREFIX.'recipe_category` c on c.category_id = rc.category_id';
        $sql .= ' WHERE rc.recipe_id = "'.$recipe_id.'"';
        $sql .= ' GROUP BY rc.category_id';
        $rows = $this->db->query($sql)->rows;

        $result = [];

        foreach ($rows as $row) {
            $result[] = $row['category_id'];
        }

        return $result;
    }

    public function getCategories($data = [])
    {
        $sql = 'SELECT * from `'.DB_PREFIX.'recipe_category`';

        if (!empty($data['filter_name'])) {
            $sql .= " WHERE name LIKE '".$this->db->escape($data['filter_name'])."%'";
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
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX.'recipe_category');

        return $query->row['total'];
    }

    public function getTotalCategoriesFilter($data)
    {
        $sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'recipe_category';

        $isWhere = 0;
        $_sql = [];

        if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
            $isWhere = 1;
            $_sql[] = "	name LIKE '".$this->db->escape($data['filter_name'])."%'";
        }

        if ($isWhere) {
            $sql .= ' WHERE '.implode(' AND ', $_sql);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }
}
