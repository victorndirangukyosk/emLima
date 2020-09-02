<?php

class ModelDesignOffer extends Model
{
    public function addOffer($data)
    {
        $this->db->query('INSERT INTO '.DB_PREFIX."offer SET title = '".$this->db->escape($data['title'])."', description = '".$data['description']."', image ='".$data['image']."', link='".$data['link']."'");

        $offer_id = $this->db->getLastId();

        return $offer_id;
    }

    public function editOffer($offer_id, $data)
    {
        $this->db->query('UPDATE '.DB_PREFIX."offer SET title = '".$this->db->escape($data['title'])."', description = '".$data['description']."', image ='".$data['image']."', link='".$data['link']."' WHERE offer_id = '".(int) $offer_id."'");

        return $offer_id;
    }

    public function deleteOffer($offer_id)
    {
        $this->db->query('DELETE FROM '.DB_PREFIX."offer WHERE offer_id = '".(int) $offer_id."'");
    }

    public function getOffer($offer_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."offer WHERE offer_id = '".(int) $offer_id."'");

        return $query->row;
    }

    public function getOffers($data = [])
    {
        $sql = 'SELECT * FROM '.DB_PREFIX.'offer';

        $sort_data = [
            'title', ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY '.$data['sort'];
        } else {
            $sql .= ' ORDER BY title';
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

    public function getTotalOffers()
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX.'offer');

        return $query->row['total'];
    }
}
