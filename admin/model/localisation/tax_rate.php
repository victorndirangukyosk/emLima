<?php

class ModelLocalisationTaxRate extends Model
{
    public function addTaxRate($data)
    {
        $this->db->query('INSERT INTO '.DB_PREFIX."tax_rate SET name = '".$this->db->escape($data['name'])."', rate = '".(float) $data['rate']."', `type` = '".$this->db->escape($data['type'])."', city_id = '".(int) $data['city_id']."', date_added = NOW(), date_modified = NOW()");

        $tax_rate_id = $this->db->getLastId();

        if (isset($data['tax_rate_customer_group'])) {
            foreach ($data['tax_rate_customer_group'] as $customer_group_id) {
                $this->db->query('INSERT INTO '.DB_PREFIX."tax_rate_to_customer_group SET tax_rate_id = '".(int) $tax_rate_id."', customer_group_id = '".(int) $customer_group_id."'");
            }
        }

        return $tax_rate_id;
    }

    public function editTaxRate($tax_rate_id, $data)
    {
        $this->db->query('UPDATE '.DB_PREFIX."tax_rate SET name = '".$this->db->escape($data['name'])."', rate = '".(float) $data['rate']."', `type` = '".$this->db->escape($data['type'])."', city_id = '".(int) $data['city_id']."', date_modified = NOW() WHERE tax_rate_id = '".(int) $tax_rate_id."'");

        $this->db->query('DELETE FROM '.DB_PREFIX."tax_rate_to_customer_group WHERE tax_rate_id = '".(int) $tax_rate_id."'");

        if (isset($data['tax_rate_customer_group'])) {
            foreach ($data['tax_rate_customer_group'] as $customer_group_id) {
                $this->db->query('INSERT INTO '.DB_PREFIX."tax_rate_to_customer_group SET tax_rate_id = '".(int) $tax_rate_id."', customer_group_id = '".(int) $customer_group_id."'");
            }
        }
    }

    public function deleteTaxRate($tax_rate_id)
    {
        $this->db->query('DELETE FROM '.DB_PREFIX."tax_rate WHERE tax_rate_id = '".(int) $tax_rate_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."tax_rate_to_customer_group WHERE tax_rate_id = '".(int) $tax_rate_id."'");
    }

    public function getTaxRate($tax_rate_id)
    {
        $query = $this->db->query('SELECT tr.tax_rate_id, tr.name AS name, tr.rate, tr.type, tr.city_id, c.name AS city, tr.date_added, tr.date_modified FROM '.DB_PREFIX.'tax_rate tr LEFT JOIN '.DB_PREFIX."city c ON (tr.city_id = c.city_id) WHERE tr.tax_rate_id = '".(int) $tax_rate_id."'");

        return $query->row;
    }

    public function getTaxRates($data = [])
    {
        $sql = 'SELECT tr.tax_rate_id, tr.name AS name, tr.rate, tr.type, c.name AS city, tr.date_added, tr.date_modified FROM '.DB_PREFIX.'tax_rate tr LEFT JOIN '.DB_PREFIX.'city c ON (tr.city_id = c.city_id)';

        $sort_data = [
            'tr.name',
            'tr.rate',
            'tr.type',
            'c.name',
            'tr.date_added',
            'tr.date_modified',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY '.$data['sort'];
        } else {
            $sql .= ' ORDER BY tr.name';
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

    public function getTaxRateCustomerGroups($tax_rate_id)
    {
        $tax_customer_group_data = [];

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."tax_rate_to_customer_group WHERE tax_rate_id = '".(int) $tax_rate_id."'");

        foreach ($query->rows as $result) {
            $tax_customer_group_data[] = $result['customer_group_id'];
        }

        return $tax_customer_group_data;
    }

    public function getTotalTaxRates()
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX.'tax_rate');

        return $query->row['total'];
    }

    public function getTotalTaxRatesByGeoZoneId($city_id)
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX."tax_rate WHERE city_id = '".(int) $city_id."'");

        return $query->row['total'];
    }
}
