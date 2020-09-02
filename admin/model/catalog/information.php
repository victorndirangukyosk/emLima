<?php

class ModelCatalogInformation extends Model
{
    public function addInformation($data)
    {
        $this->trigger->fire('pre.admin.information.add', $data);

        $this->db->query('INSERT INTO '.DB_PREFIX."information SET sort_order = '".(int) $data['sort_order']."', bottom = '".(isset($data['bottom']) ? (int) $data['bottom'] : 0)."', status = '".(int) $data['status']."'");

        $information_id = $this->db->getLastId();

        foreach ($data['information_description'] as $language_id => $value) {
            empty($value['meta_title']) ? $value['meta_title'] = $value['title'] : $value['meta_title'];

            $this->db->query('INSERT INTO '.DB_PREFIX."information_description SET information_id = '".(int) $information_id."', language_id = '".(int) $language_id."', title = '".$this->db->escape($value['title'])."', description = '".$this->db->escape($value['description'])."', meta_title = '".$this->db->escape($value['meta_title'])."', meta_description = '".$this->db->escape($value['meta_description'])."', meta_keyword = '".$this->db->escape($value['meta_keyword'])."'");
        }

        foreach ($data['seo_url'] as $language_id => $value) {
            $alias = empty($value) ? $data['information_description'][$language_id]['name'] : $value;

            $alias = $this->model_catalog_url_alias->generateAlias($alias);

            if ($alias) {
                $this->db->query('INSERT INTO '.DB_PREFIX."url_alias SET query = 'information_id=".(int) $information_id."', keyword = '".$this->db->escape($alias)."', language_id = '".$language_id."'");
            }
        }

        $this->cache->delete('information');

        $this->trigger->fire('post.admin.information.add', $information_id);

        return $information_id;
    }

    public function editInformation($information_id, $data)
    {
        $this->trigger->fire('pre.admin.information.edit', $data);

        $this->db->query('UPDATE '.DB_PREFIX."information SET sort_order = '".(int) $data['sort_order']."', bottom = '".(isset($data['bottom']) ? (int) $data['bottom'] : 0)."', status = '".(int) $data['status']."' WHERE information_id = '".(int) $information_id."'");

        $this->db->query('DELETE FROM '.DB_PREFIX."information_description WHERE information_id = '".(int) $information_id."'");

        foreach ($data['information_description'] as $language_id => $value) {
            empty($value['meta_title']) ? $value['meta_title'] = $value['title'] : $value['meta_title'];

            $this->db->query('INSERT INTO '.DB_PREFIX."information_description SET information_id = '".(int) $information_id."', language_id = '".(int) $language_id."', title = '".$this->db->escape($value['title'])."', description = '".$this->db->escape($value['description'])."', meta_title = '".$this->db->escape($value['meta_title'])."', meta_description = '".$this->db->escape($value['meta_description'])."', meta_keyword = '".$this->db->escape($value['meta_keyword'])."'");
        }

        foreach ($data['seo_url'] as $language_id => $value) {
            $this->db->query('DELETE FROM '.DB_PREFIX."url_alias WHERE query = 'information_id=".(int) $information_id."' AND language_id = '".$this->db->escape($language_id)."'");

            $alias = empty($value) ? $data['information_description'][$language_id]['name'] : $value;

            $alias = $this->model_catalog_url_alias->generateAlias($alias);

            if ($alias) {
                $this->db->query('INSERT INTO '.DB_PREFIX."url_alias SET query = 'information_id=".(int) $information_id."', keyword = '".$this->db->escape($alias)."', language_id = '".$language_id."'");
            }
        }

        $this->cache->delete('information');

        $this->trigger->fire('post.admin.information.edit', $information_id);
    }

    public function deleteInformation($information_id)
    {
        $this->trigger->fire('pre.admin.information.delete', $information_id);

        $this->db->query('DELETE FROM '.DB_PREFIX."information WHERE information_id = '".(int) $information_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."information_description WHERE information_id = '".(int) $information_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."information_to_store WHERE information_id = '".(int) $information_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."information_to_layout WHERE information_id = '".(int) $information_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."url_alias WHERE query = 'information_id=".(int) $information_id."'");

        $this->cache->delete('information');

        $this->trigger->fire('post.admin.information.delete', $information_id);
    }

    public function getInformation($information_id)
    {
        $query = $this->db->query('SELECT DISTINCT * FROM '.DB_PREFIX."information WHERE information_id = '".(int) $information_id."'");

        $information = $query->row;
        $information['seo_url'] = [];

        $query = $this->db->query('SELECT keyword, language_id FROM '.DB_PREFIX."url_alias WHERE query = 'information_id=".(int) $information_id."'");

        if ($query->rows) {
            foreach ($query->rows as $row) {
                $information['seo_url'][$row['language_id']] = $row['keyword'];
            }
        }

        return $information;
    }

    public function getInformations($data = [])
    {
        if ($data) {
            $sql = 'SELECT * FROM '.DB_PREFIX.'information i LEFT JOIN '.DB_PREFIX."information_description id ON (i.information_id = id.information_id) WHERE id.language_id = '".(int) $this->config->get('config_language_id')."'";

            $sort_data = [
                'i.status',
                'i.sort_order',
            ];

            if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
                $sql .= ' ORDER BY '.$data['sort'];
            } else {
                $sql .= ' ORDER BY id.title';
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
        } else {
            $information_data = $this->cache->get('information.'.(int) $this->config->get('config_language_id'));

            if (!$information_data) {
                $query = $this->db->query('SELECT * FROM '.DB_PREFIX.'information i LEFT JOIN '.DB_PREFIX."information_description id ON (i.information_id = id.information_id) WHERE id.language_id = '".(int) $this->config->get('config_language_id')."' ORDER BY id.title");

                $information_data = $query->rows;

                $this->cache->set('information.'.(int) $this->config->get('config_language_id'), $information_data);
            }

            return $information_data;
        }
    }

    public function getInformationDescriptions($information_id)
    {
        $information_description_data = [];

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."information_description WHERE information_id = '".(int) $information_id."'");

        foreach ($query->rows as $result) {
            $information_description_data[$result['language_id']] = [
                'title' => $result['title'],
                'description' => $result['description'],
                'meta_title' => $result['meta_title'],
                'meta_description' => $result['meta_description'],
                'meta_keyword' => $result['meta_keyword'],
            ];
        }

        return $information_description_data;
    }

    public function getInformationStores($information_id)
    {
        $information_store_data = [];

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."information_to_store WHERE information_id = '".(int) $information_id."'");

        foreach ($query->rows as $result) {
            $information_store_data[] = $result['store_id'];
        }

        return $information_store_data;
    }

    public function getInformationLayouts($information_id)
    {
        $information_layout_data = [];

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."information_to_layout WHERE information_id = '".(int) $information_id."'");

        foreach ($query->rows as $result) {
            $information_layout_data[$result['store_id']] = $result['layout_id'];
        }

        return $information_layout_data;
    }

    public function getTotalInformations()
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX.'information');

        return $query->row['total'];
    }

    public function getTotalInformationsByLayoutId($layout_id)
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX."information_to_layout WHERE layout_id = '".(int) $layout_id."'");

        return $query->row['total'];
    }
}
