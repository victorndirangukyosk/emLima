<?php

class ModelAssetsRecipe extends Model
{
    public function getCategories()
    {
        $sql = 'SELECT * from `'.DB_PREFIX.'recipe_category`';
        $sql .= ' ORDER BY sort_order ASC';
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getRecipe($recipe_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."recipe WHERE recipe_id='".$recipe_id."'");

        return $query->row;
    }

    public function getRecipes($data)
    {
        $sql = 'SELECT * FROM '.DB_PREFIX.'recipe r';

        if ($data['filter_category']) {
            $sql .= ' INNER JOIN `'.DB_PREFIX.'recipe_to_category` rc on rc.recipe_id = r.recipe_id';
            $sql .= " WHERE rc.category_id='".$data['filter_category']."'";
            $sql .= ' GROUP BY r.recipe_id';
        }

        $sql .= ' order by sort_order '; //LIMIT " . $data['start'] . "," . $data['limit'];

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getPopularRecipes($data)
    {
        $sql = 'SELECT * FROM '.DB_PREFIX.'recipe r';

        if ($data['filter_category']) {
            $sql .= ' INNER JOIN `'.DB_PREFIX.'recipe_to_category` rc on rc.recipe_id = r.recipe_id';
            $sql .= " WHERE rc.category_id='".$data['filter_category']."'";
            $sql .= ' GROUP BY r.recipe_id';
        }

        $sql .= ' order by sort_order LIMIT '.$data['start'].','.$data['limit'];

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getProducts($recipe_id)
    {
        $query = $this->db->query('select * from `'.DB_PREFIX.'recipe_product` where recipe_id="'.$recipe_id.'"');

        return $query->rows;
    }

    public function getTotalRecipes($data = [])
    {
        $sql = 'SELECT count(*) as total FROM '.DB_PREFIX.'recipe r';

        if ($data['filter_category']) {
            $sql .= ' INNER JOIN `'.DB_PREFIX.'recipe_to_category` rc on rc.recipe_id = r.recipe_id';
            $sql .= " WHERE rc.category_id='".$data['filter_category']."'";
            $sql .= ' GROUP BY r.recipe_id';
        }

        $query = $this->db->query($sql);
        if ($this->db->countAffected()) {
            return $query->row['total'];
        } else {
            return false;
        }
    }
}
