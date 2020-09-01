<?php

class ModelAppearanceLayout extends Model
{
    public function addLayout($data)
    {
        $this->trigger->fire('pre.admin.layout.add', $data);

        $this->db->query('INSERT INTO '.DB_PREFIX."layout SET name = '".$this->db->escape($data['name'])."'");

        $layout_id = $this->db->getLastId();

        if (isset($data['layout_route'])) {
            foreach ($data['layout_route'] as $layout_route) {
                $this->db->query('INSERT INTO '.DB_PREFIX."layout_route SET layout_id = '".(int) $layout_id."', store_id = '".(int) $layout_route['store_id']."', route = '".$this->db->escape($layout_route['route'])."'");
            }
        }

        if (isset($data['layout_module'])) {
            foreach ($data['layout_module'] as $layout_module) {
                $this->db->query('INSERT INTO '.DB_PREFIX."layout_module SET layout_id = '".(int) $layout_id."', code = '".$this->db->escape($layout_module['code'])."', position = '".$this->db->escape($layout_module['position'])."', sort_order = '".(int) $layout_module['sort_order']."'");
            }
        }

        $this->trigger->fire('post.admin.layout.add', $layout_id);

        return $layout_id;
    }

    public function editLayout($layout_id, $data)
    {
        if (empty($data['layout_route'])) {
            $data['layout_route'] = $this->getLayoutRoutes($layout_id);
        }
        $this->trigger->fire('pre.admin.layout.edit', $data);

        $this->db->query('UPDATE '.DB_PREFIX."layout SET name = '".$this->db->escape($data['name'])."' WHERE layout_id = '".(int) $layout_id."'");

        $this->db->query('DELETE FROM '.DB_PREFIX."layout_route WHERE layout_id = '".(int) $layout_id."'");

        if (isset($data['layout_route'])) {
            foreach ($data['layout_route'] as $layout_route) {
                $this->db->query('INSERT INTO '.DB_PREFIX."layout_route SET layout_id = '".(int) $layout_id."', store_id = '".(int) $layout_route['store_id']."', route = '".$this->db->escape($layout_route['route'])."'");
            }
        }

        $this->db->query('DELETE FROM '.DB_PREFIX."layout_module WHERE layout_id = '".(int) $layout_id."'");

        if (isset($data['layout_module'])) {
            foreach ($data['layout_module'] as $layout_module) {
                $this->db->query('INSERT INTO '.DB_PREFIX."layout_module SET layout_id = '".(int) $layout_id."', code = '".$this->db->escape($layout_module['code'])."', position = '".$this->db->escape($layout_module['position'])."', sort_order = '".(int) $layout_module['sort_order']."'");
            }
        }

        $this->trigger->fire('post.admin.layout.edit', $layout_id);
    }

    public function removeLayout($layout_id)
    {
        $this->db->query('DELETE FROM `'.DB_PREFIX."layout`        WHERE `layout_id` = '".(int) $layout_id."'");
        $this->db->query('DELETE FROM `'.DB_PREFIX."layout_route`  WHERE `layout_id` = '".(int) $layout_id."'");
        $this->db->query('DELETE FROM `'.DB_PREFIX."layout_module` WHERE `layout_id` = '".(int) $layout_id."'");
    }

    public function addLayoutModule($data)
    {
        $this->db->query('INSERT INTO '.DB_PREFIX."layout_module SET layout_id = '".(int) $data['layout_id']."', code = '".$this->db->escape($data['code'])."', position = '".$this->db->escape($data['position'])."', sort_order = '".(int) $data['sort_order']."'");
    }

    public function removeModule($module_id)
    {
        $this->db->query('DELETE FROM `'.DB_PREFIX."module` WHERE `module_id` = '".(int) $module_id."'");
    }

    public function removeLayoutModule($data)
    {
        $this->db->query('DELETE FROM `'.DB_PREFIX."layout_module` WHERE `layout_id` = '".(int) $data['layout_id']."' AND `layout_module_id` = '".$data['layout_module_id']."'");
    }

    public function deleteLayout($layout_id)
    {
        $this->trigger->fire('pre.admin.layout.delete', $layout_id);

        $this->db->query('DELETE FROM '.DB_PREFIX."layout WHERE layout_id = '".(int) $layout_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."layout_route WHERE layout_id = '".(int) $layout_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."layout_module WHERE layout_id = '".(int) $layout_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."category_to_layout WHERE layout_id = '".(int) $layout_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."product_to_layout WHERE layout_id = '".(int) $layout_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."information_to_layout WHERE layout_id = '".(int) $layout_id."'");

        $this->trigger->fire('post.admin.layout.delete', $layout_id);
    }

    public function getLayout($layout_id)
    {
        $query = $this->db->query('SELECT DISTINCT * FROM '.DB_PREFIX."layout WHERE layout_id = '".(int) $layout_id."'");

        return $query->row;
    }

    public function getLayouts($data = [])
    {
        $sql = 'SELECT * FROM '.DB_PREFIX.'layout';

        $sort_data = ['name'];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY '.$data['sort'];
        } else {
            $sql .= ' ORDER BY name';
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

    public function getLayoutRoutes($layout_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."layout_route WHERE layout_id = '".(int) $layout_id."'");

        return $query->rows;
    }

    public function getLayoutModules($layout_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."layout_module WHERE layout_id = '".(int) $layout_id."' ORDER BY sort_order");

        if (!empty($query->rows)) {
            $data = [];

            foreach ($query->rows as $layout_module) {
                if (false === strpos($layout_module['code'], '.')) {
                    $layout_module['name'] = $this->getModuleName($layout_module['code']);
                    $layout_module['link'] = $this->url->link('module/'.$layout_module['code'], 'token='.$this->session->data['token'], 'SSL');
                } else {
                    $_code = explode('.', $layout_module['code']);
                    $layout_module['name'] = $this->getModuleName($_code[0], 1, $_code[1]);
                    $layout_module['link'] = $this->url->link('module/'.$_code[0], 'module_id='.$_code[1].'&token='.$this->session->data['token'], 'SSL');
                }

                $data[$layout_module['position']][] = $layout_module;
            }

            return $data;
        } else {
            return $query->rows;
        }
    }

    public function getTotalLayouts()
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX.'layout');

        return $query->row['total'];
    }

    public function getModuleName($code, $specific = 0, $module_id = 0)
    {
        if (empty($specific)) {
            $this->load->language('module/'.$code);
            $name = $this->language->get('heading_title');
        } else {
            if (!empty($module_id)) {
                $module = " AND module_id = '".$module_id."'";
            }
            $this->load->language('module/'.$code);
            $query = $this->db->query('SELECT name FROM `'.DB_PREFIX."module` WHERE `code` = '".$this->db->escape($code)."'");
            $name = $this->language->get('heading_title').' &gt; '.($query->row['name']) ? $query->row['name'] : '';
        }

        return ($name) ? $name : '';
    }

    public function getCities()
    {
        return $this->db->query('select * from `'.DB_PREFIX.'city`')->rows;
    }
}
