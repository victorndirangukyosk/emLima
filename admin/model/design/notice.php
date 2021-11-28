<?php

class ModelDesignNotice extends Model
{
    public function addNotice($data)
    {
        $this->db->query('INSERT INTO '.DB_PREFIX."notice SET notice = '".$this->db->escape($data['notice'])."', image = '".$data['image']."', notice_force ='".$data['force']."', latitude = '".$data['latitude']."', longitude = '".$data['longitude']."', radius = '".$data['radius']."', zipcode = '".$data['zipcode']."', status ='".$data['status']."', date_added=NOW()");

        $notice_id = $this->db->getLastId();

        return $notice_id;
    }

    public function editNotice($notice_id, $data)
    {
        $this->db->query('UPDATE '.DB_PREFIX."notice SET notice = '".$this->db->escape($data['notice'])."', image = '".$data['image']."', notice_force ='".$data['force']."', latitude = '".$data['latitude']."', longitude = '".$data['longitude']."', radius = '".$data['radius']."', zipcode = '".$data['zipcode']."', status ='".$data['status']."' WHERE notice_id = '".(int) $notice_id."'");

        return $notice_id;
    }

    public function deleteNotice($notice_id)
    {
        $this->db->query('DELETE FROM '.DB_PREFIX."notice WHERE notice_id = '".(int) $notice_id."'");
    }

    public function getNotice($notice_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."notice WHERE notice_id = '".(int) $notice_id."'");

        return $query->row;
    }

    public function getNotices($data = [])
    {
        $sql = 'SELECT * FROM '.DB_PREFIX.'notice';

        $sort_data = [
            'notice', ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY '.$data['sort'];
        } else {
            $sql .= ' ORDER BY notice';
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

    public function getTotalNotices()
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX.'notice');

        return $query->row['total'];
    }
}
