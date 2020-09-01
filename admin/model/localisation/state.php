<?php

class ModelLocalisationState extends Model
{
    public function addState($data)
    {
        $this->db->query('INSERT INTO '.DB_PREFIX."state SET name = '".$this->db->escape($data['name'])."', status = '".$this->db->escape($data['status'])."', sort_order = '".$this->db->escape($data['sort_order'])."'");
        $state_id = $this->db->getLastId();

        /*foreach ($data['state_zipcodes'] as $key => $value) {

            $this->db->query("INSERT INTO " . DB_PREFIX . "state_zipcodes SET state_id = '" . (int) $state_id . "', zipcode = '" . $this->db->escape($value) . "'");
        }*/

        return $state_id;
    }

    public function editState($state_id, $data)
    {
        $this->db->query('UPDATE '.DB_PREFIX."state SET name = '".$this->db->escape($data['name'])."', status = '".$this->db->escape($data['status'])."', sort_order = '".$this->db->escape($data['sort_order'])."' WHERE state_id = '".(int) $state_id."'");

        /*$this->db->query("DELETE FROM " . DB_PREFIX . "state_zipcodes WHERE state_id = " . (int) $state_id);

        foreach ($data['state_zipcodes'] as $key => $value) {

            $this->db->query("INSERT INTO " . DB_PREFIX . "state_zipcodes SET state_id = '" . (int) $state_id . "', zipcode = '" . $this->db->escape($value) . "'");
        }*/
    }

    public function deleteState($state_id)
    {
        $this->db->query('DELETE FROM '.DB_PREFIX.'state WHERE state_id = '.(int) $state_id);
    }

    public function getState($state_id)
    {
        $query = $this->db->query('SELECT DISTINCT * FROM '.DB_PREFIX."state WHERE state_id = '".(int) $state_id."'");

        return $query->row;
    }

    public function getCities($data = [])
    {
        $sql = 'SELECT * FROM '.DB_PREFIX.'state';

        $sort_data = [
            'name',
            'sort_order',
            'status',
        ];

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

    public function getTotalCities()
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX.'state');

        return $query->row['total'];
    }

    public function getAllCities()
    {
        return $this->db->query('select * from `'.DB_PREFIX.'state` order by sort_order')->rows;
    }

    /*public function getAllZipcodeByState($state_id) {

        return $this->db->query('select * from `'.DB_PREFIX.'state_zipcodes` where state_id="'.$state_id.'"')->rows;
    }

    public function getZipcodeFilteredByState($like,$state_id) {

       return  $this->db->query('select * from `'.DB_PREFIX.'state_zipcodes` where state_id="'.$state_id.'" and zipcode LIKE "'.$like.'%"')->rows;
    }*/
}
