<?php

class ModelCatalogHelp extends Model
{
    public function addHelp($data)
    {
        /*$sql = "INSERT INTO " . DB_PREFIX . "help SET question='".$this->db->escape($data['question'])."',answer='".$this->db->escape($data['answer'])."', category_id='".(int)$this->db->escape($data['category_id'])."',sort_order = '" . (int) $data['sort_order'] . "'";
        $this->db->query($sql);
        return $this->db->getLastId(); */

        foreach ($data['help'] as $language_id => $value) {
            if (isset($help_id)) {
                $sql = 'INSERT INTO '.DB_PREFIX."help SET question='".$this->db->escape($value['question'])."',answer='".$this->db->escape($value['answer'])."',help_id = '".(int) $help_id."',language_id = '".(int) $language_id."', category_id='".(int) $this->db->escape($value['category_id'])."',sort_order = '".(int) $value['sort_order']."'";

                $this->db->query($sql);
            } else {
                $sql = 'INSERT INTO '.DB_PREFIX."help SET question='".$this->db->escape($value['question'])."',answer='".$this->db->escape($value['answer'])."',language_id = '".(int) $language_id."', category_id='".(int) $this->db->escape($value['category_id'])."',sort_order = '".(int) $value['sort_order']."'";

                $this->db->query($sql);

                $help_id = $this->db->getLastId();
            }
        }

        $this->cache->delete('help');

        return $help_id;
    }

    public function editHelp($help_id, $data)
    {
        /* $this->db->query("UPDATE " . DB_PREFIX . "help SET question='".$this->db->escape($data['question'])."',answer='".$this->db->escape($data['answer'])."', category_id='".(int)$this->db->escape($data['category_id'])."',sort_order = '" . (int) $data['sort_order'] . "' WHERE help_id = '" . (int) $help_id . "'");*/

        $this->db->query('DELETE FROM '.DB_PREFIX."help WHERE help_id = '".(int) $help_id."'");

        foreach ($data['help'] as $language_id => $value) {
            $sql = 'INSERT INTO '.DB_PREFIX."help SET question='".$this->db->escape($value['question'])."',answer='".$this->db->escape($value['answer'])."', category_id='".(int) $this->db->escape($value['category_id'])."',help_id = '".(int) $help_id."',language_id = '".(int) $language_id."',sort_order = '".(int) $value['sort_order']."'";

            $this->db->query($sql);
        }

        $this->cache->delete('help');
    }

    public function deleteHelp($help_id)
    {
        $this->db->query('DELETE FROM '.DB_PREFIX."help WHERE help_id = '".(int) $help_id."'");
    }

    public function getHelp($help_id)
    {
        return $this->db->query('SELECT * FROM '.DB_PREFIX."help WHERE help_id = '".(int) $help_id."'")->row;
    }

    public function getHelpDetails($help_id)
    {
        $category_data = [];

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."help WHERE help_id = '".(int) $help_id."'");

        foreach ($query->rows as $result) {
            $category_data[$result['language_id']] = [
                    'question' => $result['question'],
                    'answer' => $result['answer'],
                    'sort_order' => $result['sort_order'],
                    'category_id' => $result['category_id'],
                ];
        }

        return $category_data;
    }

    public function getHelps($data = [])
    {
        $sql = 'SELECT * from `'.DB_PREFIX.'help`';

        $where = 0;
        if (!empty($data['filter_question'])) {
            $where = 1;
            $sql .= " WHERE question LIKE '".$this->db->escape($data['filter_question'])."%'";
        }

        if ($where) {
            $sql .= " And language_id = '".(int) $this->config->get('config_language_id')."'";
        } else {
            $sql .= " WHERE language_id = '".(int) $this->config->get('config_language_id')."'";
        }

        $sort_data = [
            'question',
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

    public function getCategories()
    {
        $sql = 'SELECT * from `'.DB_PREFIX."help_category` where language_id = '".(int) $this->config->get('config_language_id')."'";
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalHelp()
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX."help where language_id = '".(int) $this->config->get('config_language_id')."'");

        return $query->row['total'];
    }

    public function getTotalHelpFilter($data)
    {
        $sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX."help where language_id = '".(int) $this->config->get('config_language_id')."'";

        if (isset($data['filter_question']) && !is_null($data['filter_question'])) {
            $sql .= "and    question LIKE '".$this->db->escape($data['filter_question'])."%'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }
}
