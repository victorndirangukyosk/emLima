<?php

class ModelDesignBlocks extends Model
{
    public function addBlock($data)
    {
        foreach ($data['block'] as $language_id => $value) {
            if (isset($block_id)) {
                $this->db->query('INSERT INTO '.DB_PREFIX."blocks SET block_id = '".(int) $block_id."', language_id = '".(int) $language_id."', sort_order = '".$this->db->escape($value['sort_order'])."', image = '".$this->db->escape($value['image'])."', title = '".$this->db->escape($value['title'])."', description = '".$this->db->escape($value['description'])."'");
            } else {
                $this->db->query('INSERT INTO '.DB_PREFIX."blocks SET language_id = '".(int) $language_id."', title = '".$this->db->escape($value['title'])."', sort_order = '".$this->db->escape($value['sort_order'])."', image = '".$this->db->escape($value['image'])."', description = '".$this->db->escape($value['description'])."'");

                $block_id = $this->db->getLastId();
            }
        }

        $this->cache->delete('blocks');

        return $block_id;
    }

    public function editBlock($block_id, $data)
    {
        $this->db->query('DELETE FROM '.DB_PREFIX."blocks WHERE block_id = '".(int) $block_id."'");

        foreach ($data['block'] as $language_id => $value) {
            if ('&lt;p&gt;&lt;br&gt;&lt;/p&gt;' == $value['description']) {
                $value['description'] = null;
            }
            $this->db->query('INSERT INTO '.DB_PREFIX."blocks SET block_id = '".(int) $block_id."', language_id = '".(int) $language_id."', sort_order = '".$this->db->escape($value['sort_order'])."', title = '".$this->db->escape($value['title'])."', image = '".$this->db->escape($value['image'])."', description = '".$this->db->escape($value['description'])."'");
        }

        $this->cache->delete('blocks');
    }

    public function deleteBlock($block_id)
    {
        $this->db->query('DELETE FROM '.DB_PREFIX."blocks WHERE block_id = '".(int) $block_id."'");

        $this->cache->delete('blocks');
    }

    public function getBlockOls($block_id)
    {
        /*$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blocks WHERE block_id = '" . (int)$block_id . "'");

        return $query->row;*/
        $block_data = [];

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."blocks WHERE block_id = '".(int) $block_id."'");

        foreach ($query->rows as $result) {
            $this->load->model('tool/image');

            if (is_file(DIR_IMAGE.$result['image'])) {
                $image = $result['image'];
                $thumb = $this->model_tool_image->resize($result['image'], 100, 100);
            } else {
                $image = $thumb = $this->model_tool_image->resize('no_image.png', 100, 100);
            }

            $block_data[$result['language_id']] = ['image' => $image, 'thumb' => $thumb, 'title' => $result['title'], 'description' => $result['description']];
        }

        return $block_data;
    }

    public function getBlock($block_id)
    {
        $block_data = [];

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."blocks WHERE block_id = '".(int) $block_id."'");

        foreach ($query->rows as $result) {
            $this->load->model('tool/image');

            if (is_file(DIR_IMAGE.$result['image'])) {
                $image = $result['image'];
                $thumb = $this->model_tool_image->resize($result['image'], 100, 100);
            } else {
                $image = $thumb = $this->model_tool_image->resize('no_image.png', 100, 100);
            }

            $block_data[$result['language_id']] = ['image' => $image, 'thumb' => $thumb, 'title' => $result['title'], 'description' => $result['description'], 'sort_order' => $result['sort_order']];
        }

        return $block_data;
    }

    public function getBlocks($data = [])
    {
        if ($data) {
            $sql = 'SELECT * FROM '.DB_PREFIX."blocks WHERE language_id = '".(int) $this->config->get('config_language_id')."'";

            $sql .= ' ORDER BY title';

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
            $order_status_data = $this->cache->get('blocks.'.(int) $this->config->get('config_language_id'));

            if (!$order_status_data) {
                $query = $this->db->query('SELECT * FROM '.DB_PREFIX."blocks WHERE language_id = '".(int) $this->config->get('config_language_id')."'");

                $order_status_data = $query->rows;

                $this->cache->set('blocks.'.(int) $this->config->get('config_language_id'), $order_status_data);
            }

            return $order_status_data;
        }
    }

    public function getTotalBlocks()
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX."blocks WHERE language_id = '".(int) $this->config->get('config_language_id')."'");

        return $query->row['total'];
    }
}
