<?php

class ModelSettingSeo extends Model
{
    public function getKeywords($query)
    {
        $rows = $this->db->query('select * from `'.DB_PREFIX.'url_alias` WHERE query="'.$query.'"')->rows;

        $result = [];

        foreach ($rows as $row) {
            $result[$row['language_id']] = $row['keyword'];
        }

        return $result;
    }

    public function get($url_alias_id)
    {
        return $this->db->query('select * from `'.DB_PREFIX.'url_alias` WHERE url_alias_id="'.$url_alias_id.'"')->row;
    }

    public function getTotalFilter($data)
    {
        $sql = 'select count(*) as total from `'.DB_PREFIX.'url_alias`';

        $implode = [];

        if ($data['filter_query']) {
            $implode[] = 'query = "'.$data['filter_query'].'"';
        }

        if ($data['filter_keyword']) {
            $implode[] = 'keyword = "'.$data['filter_keyword'].'"';
        }

        $implode[] = 'is_manual=1';
        //$implode[] = 'language_id=' . $this->config->get('config_language_id');

        $sql .= ' WHERE '.implode(' AND ', $implode);

        return $this->db->query($sql)->row['total'];
    }

    public function getTotal()
    {
        return $this->db->query('select count(*) as total from `'.DB_PREFIX.'url_alias` WHERE is_manual=1')->row['total'];
    }

    public function getAlias($data)
    {
        $sql = 'Select * from `'.DB_PREFIX.'url_alias`';

        $implode = [];

        if ($data['filter_query']) {
            $implode[] = 'query = "'.$data['filter_query'].'"';
        }

        if ($data['filter_keyword']) {
            $implode[] = 'keyword = "'.$data['filter_keyword'].'"';
        }

        //$implode[] = 'language_id=' . $this->config->get('config_language_id');
        $implode[] = 'is_manual=1';

        if ($implode) {
            $sql .= ' WHERE '.implode(' AND ', $implode);
        }

        if ($data['sort']) {
            $sql .= ' ORDER BY '.$data['sort'];
        } else {
            $sql .= ' ORDER BY query';
        }

        if ($data['order']) {
            $sql .= ' '.$data['order'].' ';
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

    public function add($data)
    {
        $this->db->query('DELETE FROM '.DB_PREFIX.'url_alias WHERE query="'.$data['query'].'"');

        foreach ($data['keywords'] as $key => $value) {
            $this->db->query('INSERT INTO '.DB_PREFIX.'url_alias set query="'.$data['query'].'", keyword="'.$value.'", language_id="'.$key.'", is_manual="1"');
        }

        return $this->db->getLastId();
    }

    public function edit($data)
    {
        $this->db->query('DELETE FROM '.DB_PREFIX.'url_alias WHERE query="'.$data['query'].'"');

        foreach ($data['keywords'] as $key => $value) {
            $this->db->query('INSERT INTO '.DB_PREFIX.'url_alias set query="'.$data['query'].'", keyword="'.$value.'", language_id="'.$key.'", is_manual="1"');
        }

        return $this->db->getLastId();
    }

    public function delete($url_alias_id)
    {
        $row = $this->db->query('select * from `'.DB_PREFIX.'url_alias` WHERE url_alias_id="'.$url_alias_id.'"')->row;

        if ($row) {
            $this->db->query('DELETE FROM `'.DB_PREFIX.'url_alias` WHERE query="'.$row['query'].'"');
        }
    }
}
