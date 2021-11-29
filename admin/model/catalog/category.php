<?php

class ModelCatalogCategory extends Model
{
    public function getTopCategories()
    {
        $sql = 'select cd.name, c.category_id from `'.DB_PREFIX.'category` c inner join `'.DB_PREFIX.'category_description` cd on cd.category_id = c.category_id where parent_id="0" and language_id = "'.(int) $this->config->get('config_language_id').'"';

        return $this->db->query($sql)->rows;
    }

    public function getStoreCategoriesCommission($store_id)
    {
        $sql = 'select * from `'.DB_PREFIX.'store_category_commission` where store_id="'.$store_id.'"';

        return $this->db->query($sql)->rows;
    }

    public function getStoreTypes()
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."store_type WHERE language_id = '".(int) $this->config->get('config_language_id')."' ORDER BY sort_order");

        return $query->rows;
    }

    public function getCategoryByStore($store_id)
    {
        $sql = 'SELECT cts.category_id FROM '.DB_PREFIX.'category_to_store cts LEFT JOIN '.DB_PREFIX.'category_description cd on cts.category_id = cd.category_id where cts.store_id='.$store_id." and language_id = '".(int) $this->config->get('config_language_id')."'";

        return $this->db->query($sql)->rows;
    }

    public function addCategory($data)
    {
        $this->trigger->fire('pre.admin.category.add', $data);

        $this->db->query('INSERT INTO '.DB_PREFIX."category SET discount = '".$data['discount']."', parent_id = '".(int) $data['parent_id']."', `top` = '".(isset($data['top']) ? (int) $data['top'] : 0)."', `column` = '".(int) $data['column']."', sort_order = '".(int) $data['sort_order']."', status = '".(int) $data['status']."', delivery_time = '".(int) $data['delivery_time']."', date_modified = NOW(), date_added = NOW()");

        $category_id = $this->db->getLastId();

        if (!empty($data['top'])) {
            if (empty($data['parent_id'])) {
                $this->db->query('INSERT INTO `'.DB_PREFIX."menu` SET sort_order = '".(int) $data['sort_order']."', columns = '".(int) $data['column']."', menu_type = 'category', status = '".$data['status']."'");
                $menu_id = $this->db->getLastId();
                foreach ($data['category_description'] as $language_id => $value) {
                    $this->db->query('INSERT INTO '.DB_PREFIX."menu_description SET menu_id = '".(int) $menu_id."', language_id = '".(int) $language_id."', name = '".$this->db->escape($value['name'])."', link = '".$category_id."'");
                }
                if (isset($data['category_store'])) {
                    foreach ($data['category_store'] as $store_id) {
                        $this->db->query('INSERT INTO '.DB_PREFIX."menu_to_store SET menu_id = '".(int) $menu_id."', store_id = '".(int) $store_id."'");
                    }
                }
            } else {
                $query = $this->db->query('SELECT * FROM `'.DB_PREFIX.'menu_description` AS md LEFT JOIN `'.DB_PREFIX."menu` AS m ON m.menu_id = md.menu_id WHERE m.menu_type = 'category' AND md.link '".(int) $data['parent_id']."'");
                if (empty($query->num_rows)) {
                    $this->db->query('INSERT INTO `'.DB_PREFIX."menu` SET sort_order = '".(int) $data['sort_order']."', columns = '".(int) $data['column']."', menu_type = 'category', status = '".$data['status']."'");
                    $menu_id = $this->db->getLastId();
                    foreach ($data['category_description'] as $language_id => $value) {
                        $this->db->query('INSERT INTO '.DB_PREFIX."menu_description SET menu_id = '".(int) $menu_id."', language_id = '".(int) $language_id."', name = '".$this->db->escape($value['name'])."', link = '".$category_id."'");
                    }
                    if (isset($data['category_store'])) {
                        foreach ($data['category_store'] as $store_id) {
                            $this->db->query('INSERT INTO '.DB_PREFIX."menu_to_store SET menu_id = '".(int) $menu_id."', store_id = '".(int) $store_id."'");
                        }
                    }
                } else {
                    $menu_id = $query->row['menu_id'];
                    $this->db->query('INSERT INTO '.DB_PREFIX."menu_child SET menu_id = '".(int) $menu_id."', sort_order = '".(int) $data['sort_order']."', menu_type = 'category', status = '".$data['status']."'");
                    $menu_child_id = $this->db->getLastId();
                    foreach ($data['category_description'] as $language_id => $value) {
                        $this->db->query('INSERT INTO '.DB_PREFIX."menu_child_description SET menu_child_id = '".(int) $menu_child_id."', language_id = '".(int) $language_id."', menu_id = '".(int) $menu_id."', name = '".$this->db->escape($value['name'])."', link = '".$category_id."'");
                    }
                    if (isset($data['category_store'])) {
                        foreach ($data['category_store'] as $store_id) {
                            $this->db->query('INSERT INTO '.DB_PREFIX."menu_child_to_store SET menu_child_id = '".(int) $menu_child_id."', store_id = '".(int) $store_id."'");
                        }
                    }
                }
            }
        }

        if (isset($data['image'])) {
            $this->db->query('UPDATE '.DB_PREFIX."category SET image = '".$this->db->escape($data['image'])."' WHERE category_id = '".(int) $category_id."'");
        }

        foreach ($data['category_description'] as $language_id => $value) {
            $value['meta_title'] = empty($value['meta_title']) ? $value['name'] : $value['meta_title'];

            $this->db->query('INSERT INTO '.DB_PREFIX."category_description SET category_id = '".(int) $category_id."', language_id = '".(int) $language_id."', name = '".$this->db->escape($value['name'])."', description = '".$this->db->escape($value['description'])."', meta_title = '".$this->db->escape($value['meta_title'])."', meta_description = '".$this->db->escape($value['meta_description'])."', meta_keyword = '".$this->db->escape($value['meta_keyword'])."'");
        }

        // MySQL Hierarchical Data Closure Table Pattern
        $level = 0;

        $query = $this->db->query('SELECT * FROM `'.DB_PREFIX."category_path` WHERE category_id = '".(int) $data['parent_id']."' ORDER BY `level` ASC");

        foreach ($query->rows as $result) {
            $this->db->query('INSERT INTO `'.DB_PREFIX."category_path` SET `category_id` = '".(int) $category_id."', `path_id` = '".(int) $result['path_id']."', `level` = '".(int) $level."'");

            ++$level;
        }

        $this->db->query('INSERT INTO `'.DB_PREFIX."category_path` SET `category_id` = '".(int) $category_id."', `path_id` = '".(int) $category_id."', `level` = '".(int) $level."'");

        if (isset($data['category_filter'])) {
            foreach ($data['category_filter'] as $filter_id) {
                $this->db->query('INSERT INTO '.DB_PREFIX."category_filter SET category_id = '".(int) $category_id."', filter_id = '".(int) $filter_id."'");
            }
        }

        foreach ($data['seo_url'] as $language_id => $value) {
            $alias = empty($value) ? $data['category_description'][$language_id]['name'] : $value;

            $alias = $this->model_catalog_url_alias->generateAlias($alias);

            if ($alias) {
                $this->db->query('INSERT INTO '.DB_PREFIX."url_alias SET query = 'category_id=".(int) $category_id."', keyword = '".$this->db->escape($alias)."', language_id = '".$language_id."'");
            }
        }

        $this->cache->delete('category');

        $this->addSubCategoriesToStore($category_id);
        //$this->addSubCategoriesToStore(1036);
        $this->trigger->fire('post.admin.category.add', $category_id);

        return $category_id;
    }

    public function editCategory($category_id, $data)
    {
        $this->trigger->fire('pre.admin.category.edit', $data);

        $isTop = $this->db->query('SELECT * FROM `'.DB_PREFIX."category` WHERE category_id = '".(int) $category_id."'");

        $this->db->query('UPDATE '.DB_PREFIX."category SET discount = '".$data['discount']."', parent_id = '".(int) $data['parent_id']."', `top` = '".(isset($data['top']) ? (int) $data['top'] : 0)."', `column` = '".(int) $data['column']."', sort_order = '".(int) $data['sort_order']."', status = '".(int) $data['status']."', delivery_time = '".(int) $data['delivery_time']."', date_modified = NOW() WHERE category_id = '".(int) $category_id."'");

        if (isset($data['image'])) {
            $this->db->query('UPDATE '.DB_PREFIX."category SET image = '".$this->db->escape($data['image'])."' WHERE category_id = '".(int) $category_id."'");
        }

        if (!empty($data['top'])) {
            if (empty($data['parent_id'])) {
                $query = $this->db->query('SELECT * FROM `'.DB_PREFIX.'menu_description` AS md LEFT JOIN `'.DB_PREFIX."menu` AS m ON m.menu_id = md.menu_id WHERE m.menu_type = 'category' AND md.link = '".(int) $category_id."'");
                if (empty($query->num_rows)) {
                    $this->db->query('INSERT INTO `'.DB_PREFIX."menu` SET sort_order = '".(int) $data['sort_order']."', columns = '".(int) $data['column']."', menu_type = 'category', status = '".$data['status']."'");
                    $menu_id = $this->db->getLastId();
                    foreach ($data['category_description'] as $language_id => $value) {
                        $this->db->query('INSERT INTO '.DB_PREFIX."menu_description SET menu_id = '".(int) $menu_id."', language_id = '".(int) $language_id."', name = '".$this->db->escape($value['name'])."', link = '".$category_id."'");
                    }
                    if (isset($data['category_store'])) {
                        foreach ($data['category_store'] as $store_id) {
                            $this->db->query('INSERT INTO '.DB_PREFIX."menu_to_store SET menu_id = '".(int) $menu_id."', store_id = '".(int) $store_id."'");
                        }
                    }
                } elseif (!empty($data['update_menu_name'])) {
                    foreach ($data['category_description'] as $language_id => $value) {
                        $this->db->query('UPDATE '.DB_PREFIX.'menu_description AS md LEFT JOIN '.DB_PREFIX."menu AS m ON md.menu_id = m.menu_id SET md.name = '".$this->db->escape($value['name'])."' WHERE m.menu_type = 'category' AND md.link = '".(int) $category_id."' AND md.language_id = '".(int) $language_id."'");
                    }
                    $query = $this->db->query('SELECT m.* FROM `'.DB_PREFIX.'menu_description` AS md LEFT JOIN `'.DB_PREFIX."menu` AS m ON m.menu_id = md.menu_id WHERE m.menu_type = 'category' AND md.link = '".(int) $category_id."'");
                    if (!empty($query->row['menu_id'])) {
                        $this->db->query('DELETE FROM `'.DB_PREFIX."menu_to_store` WHERE menu_id = '".(int) $query->row['menu_id']."'");
                        if (isset($data['category_store'])) {
                            foreach ($data['category_store'] as $store_id) {
                                $this->db->query('INSERT INTO '.DB_PREFIX."menu_to_store SET menu_id = '".(int) $query->row['menu_id']."', store_id = '".(int) $store_id."'");
                            }
                        }
                    }
                }
            } else {
                $query = $this->db->query('SELECT m.* FROM `'.DB_PREFIX.'menu_description` AS md LEFT JOIN `'.DB_PREFIX."menu` AS m ON m.menu_id = md.menu_id WHERE m.menu_type = 'category' AND md.link = '".(int) $data['parent_id']."'");
                if (empty($query->num_rows)) {
                    if (empty($query->row['menu_id'])) {
                        $query = $this->db->query('SELECT m.* FROM `'.DB_PREFIX.'menu_description` AS md LEFT JOIN `'.DB_PREFIX."menu` AS m ON m.menu_id = md.menu_id WHERE m.menu_type = 'category' AND md.link = '".(int) $category_id."'");
                        if (empty($query->num_rows)) {
                            $this->db->query('INSERT INTO `'.DB_PREFIX."menu` SET sort_order = '".(int) $data['sort_order']."', columns = '".(int) $data['column']."', menu_type = 'category', status = '".$data['status']."'");
                            $menu_id = $this->db->getLastId();
                            foreach ($data['category_description'] as $language_id => $value) {
                                $this->db->query('INSERT INTO '.DB_PREFIX."menu_description SET menu_id = '".(int) $menu_id."', language_id = '".(int) $language_id."', name = '".$this->db->escape($value['name'])."', link = '".$category_id."'");
                            }
                            if (isset($data['category_store'])) {
                                foreach ($data['category_store'] as $store_id) {
                                    $this->db->query('INSERT INTO '.DB_PREFIX."menu_to_store SET menu_id = '".(int) $menu_id."', store_id = '".(int) $store_id."'");
                                }
                            }
                        } elseif (!empty($data['update_menu_name'])) {
                            foreach ($data['category_description'] as $language_id => $value) {
                                $this->db->query('UPDATE '.DB_PREFIX.'menu_description AS md LEFT JOIN '.DB_PREFIX."menu AS m ON md.menu_id = m.menu_id SET md.name = '".$this->db->escape($value['name'])."' WHERE m.menu_type = 'category' AND md.link = '".(int) $category_id."' AND md.language_id = '".(int) $language_id."'");
                                $query = $this->db->query('SELECT m.* FROM `'.DB_PREFIX.'menu_description` AS md LEFT JOIN `'.DB_PREFIX."menu` AS m ON m.menu_id = md.menu_id WHERE m.menu_type = 'category' AND md.link = '".(int) $category_id."'");
                                if (!empty($query->row['menu_id'])) {
                                    $this->db->query('DELETE FROM `'.DB_PREFIX."menu_to_store` WHERE menu_id = '".(int) $query->row['menu_id']."'");
                                    if (isset($data['category_store'])) {
                                        foreach ($data['category_store'] as $store_id) {
                                            $this->db->query('INSERT INTO '.DB_PREFIX."menu_to_store SET menu_id = '".(int) $query->row['menu_id']."', store_id = '".(int) $store_id."'");
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        $menu_id = $query->row['menu_id'];
                        $query = $this->db->query('SELECT m.* FROM `'.DB_PREFIX.'menu_child_description` AS md LEFT JOIN `'.DB_PREFIX."menu_child` AS m ON m.menu_id = md.menu_id WHERE m.menu_type = 'category' AND md.link = '".(int) $category_id."'");
                        if (empty($query->num_rows)) {
                            $this->db->query('INSERT INTO '.DB_PREFIX."menu_child SET menu_id = '".(int) $menu_id."', sort_order = '".(int) $data['sort_order']."', menu_type = 'category', status = '".$data['status']."'");
                            $menu_child_id = $this->db->getLastId();
                            foreach ($data['category_description'] as $language_id => $value) {
                                $this->db->query('INSERT INTO '.DB_PREFIX."menu_child_description SET menu_child_id = '".(int) $menu_child_id."', language_id = '".(int) $language_id."', menu_id = '".(int) $menu_id."', name = '".$this->db->escape($value['name'])."', link = '".$category_id."'");
                            }
                            if (isset($data['category_store'])) {
                                foreach ($data['category_store'] as $store_id) {
                                    $this->db->query('INSERT INTO '.DB_PREFIX."menu_child_to_store SET menu_child_id = '".(int) $menu_child_id."', store_id = '".(int) $store_id."'");
                                }
                            }
                        } elseif (!empty($data['update_menu_name'])) {
                            foreach ($data['category_description'] as $language_id => $value) {
                                $this->db->query('UPDATE '.DB_PREFIX.'menu_child_description AS md LEFT JOIN '.DB_PREFIX."menu_child AS m ON md.menu_id = m.menu_id SET md.name = '".$this->db->escape($value['name'])."' WHERE m.menu_type = 'category' AND md.link = '".(int) $category_id."' AND md.language_id = '".(int) $language_id."'");
                                $query = $this->db->query('SELECT m.* FROM `'.DB_PREFIX.'menu_child_description` AS md LEFT JOIN `'.DB_PREFIX."menu_child` AS m ON m.menu_id = md.menu_id WHERE m.menu_type = 'category' AND md.link = '".(int) $category_id."'");
                                if (!empty($query->row['menu_child_id'])) {
                                    $this->db->query('DELETE FROM `'.DB_PREFIX."menu_child_to_store` WHERE menu_child_id = '".(int) $query->row['menu_child_id']."'");
                                    if (isset($data['category_store'])) {
                                        foreach ($data['category_store'] as $store_id) {
                                            $this->db->query('INSERT INTO '.DB_PREFIX."menu_child_to_store SET menu_id = '".(int) $query->row['menu_child_id']."', store_id = '".(int) $store_id."'");
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } else {
            if ($isTop->row['top']) {
                if (empty($data['parent_id'])) {
                    $query = $this->db->query('SELECT * FROM `'.DB_PREFIX.'menu_description` AS md LEFT JOIN `'.DB_PREFIX."menu` AS m ON m.menu_id = md.menu_id WHERE m.menu_type = 'category' AND md.link = '".(int) $category_id."'");
                    if (!empty($query->row['menu_id'])) {
                        $menu_id = $query->row['menu_id'];
                        $this->db->query('DELETE FROM `'.DB_PREFIX."menu` WHERE menu_id = '".(int) $menu_id."'");
                        $this->db->query('DELETE FROM `'.DB_PREFIX."menu_description` WHERE menu_id = '".(int) $menu_id."'");
                        $this->db->query('DELETE FROM `'.DB_PREFIX."menu_to_store` WHERE menu_id = '".(int) $menu_id."'");
                    }
                } else {
                    $query = $this->db->query('SELECT * FROM `'.DB_PREFIX.'menu_description` AS md LEFT JOIN `'.DB_PREFIX."menu` AS m ON m.menu_id = md.menu_id WHERE m.menu_type = 'category' AND md.link = '".(int) $data['parent_id']."'");
                    if (empty($query->row['menu_id'])) {
                        $query = $this->db->query('SELECT m.* FROM `'.DB_PREFIX.'menu_description` AS md LEFT JOIN `'.DB_PREFIX."menu` AS m ON m.menu_id = md.menu_id WHERE m.menu_type = 'category' AND md.link = '".(int) $category_id."'");
                        if (!empty($query->row['menu_id'])) {
                            $menu_id = $query->row['menu_id'];
                            $this->db->query('DELETE FROM `'.DB_PREFIX."menu_` WHERE menu_id = '".(int) $menu_id."'");
                            $this->db->query('DELETE FROM `'.DB_PREFIX."menu_description` WHERE menu_id = '".(int) $menu_id."'");
                            $this->db->query('DELETE FROM `'.DB_PREFIX."menu_to_store` WHERE menu_id = '".(int) $menu_id."'");
                        }
                    } else {
                        if (!empty($query->row['menu_id'])) {
                            $menu_id = $query->row['menu_id'];
                            $this->db->query('DELETE FROM `'.DB_PREFIX."menu_child` WHERE menu_id = '".(int) $menu_id."'");
                            $this->db->query('DELETE FROM `'.DB_PREFIX."menu_child_description` WHERE menu_id = '".(int) $menu_id."'");
                            $this->db->query('DELETE FROM `'.DB_PREFIX."menu_child_to_store` WHERE menu_id = '".(int) $menu_id."'");
                        }
                    }
                }
            }
        }

        $this->db->query('DELETE FROM '.DB_PREFIX."category_description WHERE category_id = '".(int) $category_id."'");

        foreach ($data['category_description'] as $language_id => $value) {
            empty($value['meta_title']) ? $value['meta_title'] = $value['name'] : $value['meta_title'];

            $this->db->query('INSERT INTO '.DB_PREFIX."category_description SET category_id = '".(int) $category_id."', language_id = '".(int) $language_id."', name = '".$this->db->escape($value['name'])."', description = '".$this->db->escape($value['description'])."', meta_title = '".$this->db->escape($value['meta_title'])."', meta_description = '".$this->db->escape($value['meta_description'])."', meta_keyword = '".$this->db->escape($value['meta_keyword'])."'");
        }

        // MySQL Hierarchical Data Closure Table Pattern
        $query = $this->db->query('SELECT * FROM `'.DB_PREFIX."category_path` WHERE path_id = '".(int) $category_id."' ORDER BY level ASC");

        if ($query->rows) {
            foreach ($query->rows as $category_path) {
                // Delete the path below the current one
                $this->db->query('DELETE FROM `'.DB_PREFIX."category_path` WHERE category_id = '".(int) $category_path['category_id']."' AND level < '".(int) $category_path['level']."'");

                $path = [];

                // Get the nodes new parents
                $query = $this->db->query('SELECT * FROM `'.DB_PREFIX."category_path` WHERE category_id = '".(int) $data['parent_id']."' ORDER BY level ASC");

                foreach ($query->rows as $result) {
                    $path[] = $result['path_id'];
                }

                // Get whats left of the nodes current path
                $query = $this->db->query('SELECT * FROM `'.DB_PREFIX."category_path` WHERE category_id = '".(int) $category_path['category_id']."' ORDER BY level ASC");

                foreach ($query->rows as $result) {
                    $path[] = $result['path_id'];
                }

                // Combine the paths with a new level
                $level = 0;

                foreach ($path as $path_id) {
                    $this->db->query('REPLACE INTO `'.DB_PREFIX."category_path` SET category_id = '".(int) $category_path['category_id']."', `path_id` = '".(int) $path_id."', level = '".(int) $level."'");

                    ++$level;
                }
            }
        } else {
            // Delete the path below the current one
            $this->db->query('DELETE FROM `'.DB_PREFIX."category_path` WHERE category_id = '".(int) $category_id."'");

            // Fix for records with no paths
            $level = 0;

            $query = $this->db->query('SELECT * FROM `'.DB_PREFIX."category_path` WHERE category_id = '".(int) $data['parent_id']."' ORDER BY level ASC");

            foreach ($query->rows as $result) {
                $this->db->query('INSERT INTO `'.DB_PREFIX."category_path` SET category_id = '".(int) $category_id."', `path_id` = '".(int) $result['path_id']."', level = '".(int) $level."'");

                ++$level;
            }

            $this->db->query('REPLACE INTO `'.DB_PREFIX."category_path` SET category_id = '".(int) $category_id."', `path_id` = '".(int) $category_id."', level = '".(int) $level."'");
        }

        $this->db->query('DELETE FROM '.DB_PREFIX."category_filter WHERE category_id = '".(int) $category_id."'");

        if (isset($data['category_filter'])) {
            foreach ($data['category_filter'] as $filter_id) {
                $this->db->query('INSERT INTO '.DB_PREFIX."category_filter SET category_id = '".(int) $category_id."', filter_id = '".(int) $filter_id."'");
            }
        }

        foreach ($data['seo_url'] as $language_id => $value) {
            $this->db->query('DELETE FROM '.DB_PREFIX."url_alias WHERE query = 'category_id=".(int) $category_id."' AND language_id = '".$this->db->escape($language_id)."'");

            $alias = empty($value) ? $data['category_description'][$language_id]['name'] : $value;

            $alias = $this->model_catalog_url_alias->generateAlias($alias);

            if ($alias) {
                $this->db->query('INSERT INTO '.DB_PREFIX."url_alias SET query = 'category_id=".(int) $category_id."', keyword = '".$this->db->escape($alias)."', language_id = '".$language_id."'");
            }
        }

        $this->cache->delete('category');

        $this->trigger->fire('post.admin.category.edit', $category_id);
    }

    public function deleteCategory($category_id)
    {
        $this->trigger->fire('pre.admin.category.delete', $category_id);

        $this->db->query('DELETE FROM '.DB_PREFIX."category_path WHERE category_id = '".(int) $category_id."'");

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."category_path WHERE path_id = '".(int) $category_id."'");

        foreach ($query->rows as $result) {
            $this->deleteCategory($result['category_id']);
        }

        $this->db->query('DELETE FROM '.DB_PREFIX."category WHERE category_id = '".(int) $category_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."category_description WHERE category_id = '".(int) $category_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."category_filter WHERE category_id = '".(int) $category_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."category_to_store WHERE category_id = '".(int) $category_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."category_to_layout WHERE category_id = '".(int) $category_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."product_to_category WHERE category_id = '".(int) $category_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."url_alias WHERE query = 'category_id=".(int) $category_id."'");

        $this->cache->delete('category');

        $this->trigger->fire('post.admin.category.delete', $category_id);
    }

    public function repairCategories($parent_id = 0)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."category WHERE parent_id = '".(int) $parent_id."'");

        foreach ($query->rows as $category) {
            // Delete the path below the current one
            $this->db->query('DELETE FROM `'.DB_PREFIX."category_path` WHERE category_id = '".(int) $category['category_id']."'");

            // Fix for records with no paths
            $level = 0;

            $query = $this->db->query('SELECT * FROM `'.DB_PREFIX."category_path` WHERE category_id = '".(int) $parent_id."' ORDER BY level ASC");

            foreach ($query->rows as $result) {
                $this->db->query('INSERT INTO `'.DB_PREFIX."category_path` SET category_id = '".(int) $category['category_id']."', `path_id` = '".(int) $result['path_id']."', level = '".(int) $level."'");

                ++$level;
            }

            $this->db->query('REPLACE INTO `'.DB_PREFIX."category_path` SET category_id = '".(int) $category['category_id']."', `path_id` = '".(int) $category['category_id']."', level = '".(int) $level."'");

            $this->repairCategories($category['category_id']);
        }
    }

    public function getCategory($category_id)
    {
        $query = $this->db->query("SELECT DISTINCT *, (SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') FROM ".DB_PREFIX.'category_path cp LEFT JOIN '.DB_PREFIX."category_description cd1 ON (cp.path_id = cd1.category_id AND cp.category_id != cp.path_id) WHERE cp.category_id = c.category_id AND cd1.language_id = '".(int) $this->config->get('config_language_id')."' GROUP BY cp.category_id) AS path FROM ".DB_PREFIX.'category c LEFT JOIN '.DB_PREFIX."category_description cd2 ON (c.category_id = cd2.category_id) WHERE c.category_id = '".(int) $category_id."' AND cd2.language_id = '".(int) $this->config->get('config_language_id')."'");

        $category = $query->row;
        $category['seo_url'] = [];

        $query = $this->db->query('SELECT keyword, language_id FROM '.DB_PREFIX."url_alias WHERE query = 'category_id=".(int) $category_id."'");

        if ($query->rows) {
            foreach ($query->rows as $row) {
                $category['seo_url'][$row['language_id']] = $row['keyword'];
            }
        }

        return $category;
    }

    public function getCategories($data = [])
    {
        $sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name, c1.parent_id, c1.status, c1.sort_order FROM ".DB_PREFIX.'category_path cp LEFT JOIN '.DB_PREFIX.'category c1 ON (cp.category_id = c1.category_id) LEFT JOIN '.DB_PREFIX.'category c2 ON (cp.path_id = c2.category_id) LEFT JOIN '.DB_PREFIX.'category_description cd1 ON (cp.path_id = cd1.category_id) LEFT JOIN '.DB_PREFIX."category_description cd2 ON (cp.category_id = cd2.category_id) WHERE cd1.language_id = '".(int) $this->config->get('config_language_id')."' AND cd2.language_id = '".(int) $this->config->get('config_language_id')."'";

        if (!empty($data['filter_name'])) {
            $sql .= " AND cd2.name LIKE '".$this->db->escape($data['filter_name'])."%'";
        }

        if (isset($data['filter_status']) and !is_null($data['filter_status'])) {
            $sql .= " AND c1.status = '".$data['filter_status']."'";
        }
        $sql .= ' GROUP BY cp.category_id';

        $sort_data = [
            'status',
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

    public function getCategoryDescriptions($category_id)
    {
        $category_description_data = [];

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."category_description WHERE category_id = '".(int) $category_id."'");

        foreach ($query->rows as $result) {
            $category_description_data[$result['language_id']] = [
                'name' => $result['name'],
                'meta_title' => $result['meta_title'],
                'meta_description' => $result['meta_description'],
                'meta_keyword' => $result['meta_keyword'],
                'description' => $result['description'],
            ];
        }

        return $category_description_data;
    }

    public function getCategoryFilters($category_id)
    {
        $category_filter_data = [];

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."category_filter WHERE category_id = '".(int) $category_id."'");

        foreach ($query->rows as $result) {
            $category_filter_data[] = $result['filter_id'];
        }

        return $category_filter_data;
    }

    public function getCategoryStores($category_id)
    {
        $category_store_data = [];

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."category_to_store WHERE category_id = '".(int) $category_id."'");

        foreach ($query->rows as $result) {
            $category_store_data[] = $result['store_id'];
        }

        return $category_store_data;
    }

    public function getCategoryLayouts($category_id)
    {
        $category_layout_data = [];

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."category_to_layout WHERE category_id = '".(int) $category_id."'");

        foreach ($query->rows as $result) {
            $category_layout_data[$result['store_id']] = $result['layout_id'];
        }

        return $category_layout_data;
    }

    public function getTotalCategories()
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX.'category');

        return $query->row['total'];
    }

    public function getTotalCategoriesFilter($data)
    {
        $sql = ('SELECT COUNT(*) AS total FROM '.DB_PREFIX.'category c LEFT JOIN '.DB_PREFIX.'category_description cd ON c.category_id = cd.category_id');

        $isWhere = 0;
        $_sql = [];

        if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
            $isWhere = 1;
            $_sql[] = " cd.name LIKE '".$this->db->escape($data['filter_name'])."%'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $isWhere = 1;

            $_sql[] = "c.status = '".$this->db->escape($data['filter_status'])."'";
        }

        if ($isWhere) {
            $sql .= ' WHERE '.implode(' AND ', $_sql);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalCategoriesByLayoutId($layout_id)
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX."category_to_layout WHERE layout_id = '".(int) $layout_id."'");

        return $query->row['total'];
    }

    public function addSubCategoriesToStore($new_category_id)
    {
        $sql = 'SELECT * FROM '.DB_PREFIX.'store';

        $stores = $this->db->query($sql)->rows;

        foreach ($stores as $store) {
            // code...

            $store_id = $store['store_id'];

            $storeCategories = $this->db->query('select * from `'.DB_PREFIX.'category_to_store` where store_id='.$store_id)->rows;

            /*

            if($store_id == 43) {
                //echo "<pre>";print_r($storeCategories);die;
            } else {
                continue;
            }*/

            $query = 'DELETE FROM '.DB_PREFIX.'category_to_store where store_id='.(int) $store_id;
            $this->db->query($query);

            foreach ($storeCategories as $cat) {
                $sql = 'SELECT count(*) as total FROM '.DB_PREFIX."category_to_store where category_id = '".(int) $cat['category_id']."' and store_id = '".$store_id."'";

                //echo "<pre>";print_r($cat);die;
                if (0 == $this->db->query($sql)->row['total']) {
                    $query = 'INSERT INTO '.DB_PREFIX.'category_to_store value ('.$cat['category_id'].','.(int) $store_id.',0)';
                    $this->db->query($query);

                    //echo "<pre>";print_r();die;
                    $this->saveSubCategoriesToStore($cat['category_id'], $store_id);
                }
            }
        }
    }

    public function saveSubCategoriesToStore($top_cat_id, $store_id)
    {
        //echo $top_cat_id;

        $sql = 'SELECT * FROM '.DB_PREFIX."category c where c.parent_id = '".(int) $top_cat_id."' and c.status = '1'";

        $level1 = $this->db->query($sql)->rows;
        //echo "<pre>";print_r($level1);die;
        foreach ($level1 as $level_cat_id) {
            $sql = 'SELECT count(*) as total FROM '.DB_PREFIX."category_to_store where category_id = '".(int) $level_cat_id['category_id']."' and store_id = '".$store_id."'";

            if (0 == $this->db->query($sql)->row['total']) {
                $query = 'INSERT INTO '.DB_PREFIX.'category_to_store value ('.$level_cat_id['category_id'].','.(int) $store_id.',0)';

                $this->db->query($query);

                $this->saveSubCategoriesToStore($level_cat_id['category_id'], $store_id);
            }
        }
    }
}
