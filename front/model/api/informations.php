<?php

class ModelApiInformations extends Model
{
    public function getInformation($data = [])
    {
        /*$sql = "SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) LEFT JOIN " . DB_PREFIX . "information_to_store i2s ON (i.information_id = i2s.information_id)";

        $sql .= $this->getExtraConditions($data);

        $query = $this->db->query($sql);

        return $query->row;*/

        //echo "<pre>";print_r($data);die;
        $query = $this->db->query('SELECT DISTINCT * FROM '.DB_PREFIX.'information i LEFT JOIN '.DB_PREFIX.'information_description id ON (i.information_id = id.information_id) LEFT JOIN '.DB_PREFIX."information_to_store i2s ON (i.information_id = i2s.information_id) WHERE i.information_id = '".(int) $data['id']."' AND id.language_id = '".(int) $this->config->get('config_language_id')."' AND i2s.store_id = '".(int) $this->config->get('config_store_id')."' AND i.status = '1'");

        // echo "SELECT DISTINCT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) LEFT JOIN " . DB_PREFIX . "information_to_store i2s ON (i.information_id = i2s.information_id) WHERE i.information_id = '" . (int)$data['id'] . "' AND id.language_id = '" . (int)$this->config->get('config_language_id') . "' AND i2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND i.status = '1'";

        return $query->row;
    }

    public function getInformations($data = [])
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX.'information i LEFT JOIN '.DB_PREFIX."information_description id ON (i.information_id = id.information_id) WHERE id.language_id = '".(int) $this->config->get('config_language_id')."' AND i.status = '1' ORDER BY i.sort_order, LCASE(id.title) ASC");

        return $query->rows;

        /*$sql = "SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) LEFT JOIN " . DB_PREFIX . "information_to_store i2s ON (i.information_id = i2s.information_id)";

        $sql .= $this->getExtraConditions($data);

        $sort_data = array(
            'id.title',
            'i.status',
            'i.sort_order',
            'i.date_added'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY id.title";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;*/
    }

    public function getTotals($data = [])
    {
        $sql = 'SELECT COUNT(DISTINCT i.information_id) AS number FROM '.DB_PREFIX.'information i LEFT JOIN '.DB_PREFIX.'information_description id ON (i.information_id = id.information_id) LEFT JOIN '.DB_PREFIX.'information_to_store i2s ON (i.information_id = i2s.information_id)';

        $sql .= $this->getExtraConditions($data);

        $query = $this->db->query($sql);

        return $query->row;
    }

    private function getExtraConditions($data)
    {
        $sql = '';

        $implode = [];

        if (!empty($data['id'])) {
            $implode[] = "i.information_id = '".(int) $data['id']."'";
        }

        if (!empty($data['search'])) {
            $implode[] = "(id.title LIKE '%".$this->db->escape($data['search'])."%' OR id.description LIKE '%".$this->db->escape($data['search'])."%')";
        }

        if (!empty($data['status'])) {
            $implode[] = "i.status = '".(int) $data['status']."'";
        }

        $implode[] = "id.language_id = '".(int) $this->config->get('config_language_id')."'";

        $implode[] = "i2s.store_id = '".(int) $this->config->get('config_store_id')."'";

        if ($implode) {
            $sql .= ' WHERE '.implode(' AND ', $implode);
        }

        return $sql;
    }
}
