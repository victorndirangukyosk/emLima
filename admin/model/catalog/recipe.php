<?php

class ModelCatalogRecipe extends Model
{
    public function addRecipe($data)
    {
        $this->db->query('INSERT INTO `'.DB_PREFIX."recipe` SET video='".$data['video']."', title = '".$this->db->escape($data['title'])."', sort_order = '".(int) $data['sort_order']."', description='".$data['description']."', directions='".$data['directions']."', author='".$data['author']."', image='".$data['image']."'");

        $recipe_id = $this->db->getLastId();

        foreach ($data['products'] as $product) {
            $this->db->query('INSERT INTO '.DB_PREFIX."recipe_product SET recipe_id = '".(int) $recipe_id."', name = '".$product['name']."', quantity = '".$product['quantity']."', model='".$product['model']."', image='".$product['image']."'");
        }

        foreach ($data['category'] as $category) {
            $this->db->query('INSERT INTO '.DB_PREFIX."recipe_to_category SET recipe_id = '".(int) $recipe_id."', category_id = '".$category."'");
        }

        return $recipe_id;
    }

    public function editRecipe($recipe_id, $data)
    {
        $this->trigger->fire('pre.admin.recipe.edit', $data);

        $this->db->query('UPDATE `'.DB_PREFIX."recipe` SET video='".$data['video']."', title = '".$this->db->escape($data['title'])."', sort_order = '".(int) $data['sort_order']."', description='".$data['description']."', directions='".$data['directions']."', author='".$data['author']."', image='".$data['image']."' WHERE recipe_id = '".(int) $recipe_id."'");

        $this->db->query('DELETE FROM '.DB_PREFIX."recipe_product WHERE recipe_id = '".(int) $recipe_id."'");

        foreach ($data['products'] as $product) {
            $this->db->query('INSERT INTO '.DB_PREFIX."recipe_product SET recipe_id = '".(int) $recipe_id."', name = '".$product['name']."', quantity = '".$product['quantity']."', model='".$product['model']."', image='".$product['image']."'");
        }

        $this->db->query('DELETE FROM '.DB_PREFIX."recipe_to_category WHERE recipe_id = '".(int) $recipe_id."'");

        foreach ($data['category'] as $category) {
            $this->db->query('INSERT INTO '.DB_PREFIX."recipe_to_category SET recipe_id = '".(int) $recipe_id."', category_id = '".$category."'");
        }
    }

    public function deleteRecipe($recipe_id)
    {
        $this->db->query('DELETE FROM `'.DB_PREFIX."recipe` WHERE recipe_id = '".(int) $recipe_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."recipe_product WHERE recipe_id = '".(int) $recipe_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."recipe_to_category WHERE recipe_id = '".(int) $recipe_id."'");
    }

    public function getRecipeProducts($recipe_id)
    {
        $query = $this->db->query('SELECT * FROM `'.DB_PREFIX."recipe_product` WHERE recipe_id = '".(int) $recipe_id."'");

        return $query->row;
    }

    public function getRecipe($recipe_id)
    {
        $query = $this->db->query('SELECT * FROM `'.DB_PREFIX."recipe` WHERE recipe_id = '".(int) $recipe_id."'");

        return $query->row;
    }

    public function getRecipes($data = [])
    {
        $sql = 'SELECT * FROM `'.DB_PREFIX.'recipe` r';

        if (!empty($data['filter_title'])) {
            $sql .= " AND od.title LIKE '".$this->db->escape($data['filter_title'])."%'";
        }

        $sort_data = [
            'r.title',
            'r.author',
            'r.sort_order',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY '.$data['sort'];
        } else {
            $sql .= ' ORDER BY r.title';
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

    public function getProducts($recipe_id)
    {
        return $this->db->query('select * from `'.DB_PREFIX.'recipe_product` WHERE recipe_id="'.$recipe_id.'"')->rows;
    }

    public function getTotalRecipes()
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `'.DB_PREFIX.'recipe`');

        return $query->row['total'];
    }
}
