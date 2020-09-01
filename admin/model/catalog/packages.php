<?php

class ModelCatalogPackages extends Model
{
    public function add($data)
    {
        $this->db->query('INSERT INTO '.DB_PREFIX."packages SET priority='".$data['priority']."', name = '".$this->db->escape($data['name'])."', amount = '".$data['amount']."', free_month='".$data['free_month']."', free_year='".$data['free_year']."', status='".$data['status']."', date_added='".date('Y-m-d')."'");

        return $this->db->getLastId();
    }

    public function edit($package_id, $data)
    {
        $this->db->query('UPDATE '.DB_PREFIX."packages SET priority='".$data['priority']."', name = '".$this->db->escape($data['name'])."', amount = '".$data['amount']."', free_month='".$data['free_month']."', free_year='".$data['free_year']."', status='".$data['status']."' where package_id='".$package_id."'");

        return $package_id;
    }

    public function delete($package_id)
    {
        $this->db->query('DELETE FROM '.DB_PREFIX."packages WHERE package_id = '".(int) $package_id."'");
    }

    public function getPackage($package_id)
    {
        return $this->db->query('SELECT * FROM '.DB_PREFIX."packages WHERE package_id = '".(int) $package_id."'")->row;
    }

    public function getPackages($data = [])
    {
        $sql = 'SELECT * FROM '.DB_PREFIX.'packages';

        if ($data['sort']) {
            $sql .= ' ORDER BY '.$data['sort'];
        } else {
            $sql .= ' ORDER BY name';
        }

        if ($data['order']) {
            $sql .= ' '.$data['order'];
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

    public function getTotal()
    {
        $sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'packages';

        return $this->db->query($sql)->row['total'];
    }
}
