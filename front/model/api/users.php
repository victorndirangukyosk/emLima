<?php

class ModelApiUsers extends Model
{
    public function editUser($user_id, $data)
    {
        if ($data['password']) {
            $this->db->query('UPDATE `'.DB_PREFIX."user` SET salt = '".$this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9))."', password = '".$this->db->escape(sha1($salt.sha1($salt.sha1($data['password']))))."' WHERE user_id = '".(int) $user_id."'");
        }

        unset($data['id']);
        unset($data['password']);
        $i = 0;
        $sql = 'UPDATE `'.DB_PREFIX.'user` SET ';
        foreach ($data as $key => $value) {
            $value = (in_array($key, ['city_id', 'user_group_id'])) ? (int) $value : $this->db->escape($value);
            $sql .= (0 == $i) ? " $key = '".$value."'" : " , $key = '".$value."'";
            ++$i;
        }

        $sql .= " WHERE user_id = '".(int) $user_id."'";
        $this->db->query($sql);
    }

    public function getVendors($data = [])
    {
        $sql = 'SELECT * FROM '.DB_PREFIX.'user u ';

        $sql .= ' WHERE u.user_group_id IN ('.$this->config->get('config_vendor_group_ids').')';

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(u.date_added) >= '".$this->db->escape($data['filter_date_start'])."'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(u.date_added) <= '".$this->db->escape($data['filter_date_end'])."'";
        }

        $sql .= ' ORDER BY u.date_added DESC';

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

    public function getTotalVendors($data = [])
    {
        $sql = 'SELECT count(*) as total FROM '.DB_PREFIX.'user u ';

        $sql .= ' WHERE u.user_group_id IN ('.$this->config->get('config_vendor_group_ids').')';

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(u.date_added) >= '".$this->db->escape($data['filter_date_start'])."'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(u.date_added) <= '".$this->db->escape($data['filter_date_end'])."'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }
}
