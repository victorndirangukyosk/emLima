<?php

class ModelUserUserGroup extends Model
{
    public function addUserGroup($data)
    {
        $this->db->query('INSERT INTO '.DB_PREFIX."user_group SET name = '".$this->db->escape($data['name'])."', permission = '".(isset($data['permission']) ? $this->db->escape(serialize($data['permission'])) : '')."'");

        return $this->db->getLastId();
    }

    public function editUserGroup($user_group_id, $data)
    {
        $this->db->query('UPDATE '.DB_PREFIX."user_group SET name = '".$this->db->escape($data['name'])."', permission = '".(isset($data['permission']) ? $this->db->escape(serialize($data['permission'])) : '')."' WHERE user_group_id = '".(int) $user_group_id."'");
    }

    public function deleteUserGroup($user_group_id)
    {
        $this->db->query('DELETE FROM '.DB_PREFIX."user_group WHERE user_group_id = '".(int) $user_group_id."'");
    }

    public function getUserGroup($user_group_id)
    {
        $query = $this->db->query('SELECT DISTINCT * FROM '.DB_PREFIX."user_group WHERE user_group_id = '".(int) $user_group_id."'");

        $user_group = [
            'name' => $query->row['name'],
            'permission' => unserialize($query->row['permission']),
        ];

        return $user_group;
    }

    public function getUserGroups($data = [])
    {
        $sql = 'SELECT * FROM '.DB_PREFIX.'user_group';

        //filter vendor groups
        $sql .= ' WHERE user_group_id NOT IN ('.$this->db->escape($this->config->get('config_vendor_group_ids')).') ';
        $sql .= ' AND user_group_id NOT IN ('.$this->db->escape($this->config->get('config_shopper_group_ids')).') ';

        if (isset($data['filter_user_group']) && !is_null($data['filter_user_group']) && '*' != $data['filter_user_group']) {
            $sql .= " AND name LIKE '".$this->db->escape($data['filter_user_group'])."%'";
        }

        $sql .= ' ORDER BY name';

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

    public function getTotalUserGroups()
    {
        $sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'user_group';

        //filter vendor groups
        $sql .= ' WHERE user_group_id NOT IN ('.$this->db->escape($this->config->get('config_vendor_group_ids')).') ';
        $sql .= ' AND user_group_id NOT IN ('.$this->db->escape($this->config->get('config_shopper_group_ids')).') ';

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function addPermission($user_group_id, $type, $path)
    {
        $user_group_query = $this->db->query('SELECT DISTINCT * FROM '.DB_PREFIX."user_group WHERE user_group_id = '".(int) $user_group_id."'");

        if ($user_group_query->num_rows) {
            $data = unserialize($user_group_query->row['permission']);

            $data[$type][] = $path;

            $this->db->query('UPDATE '.DB_PREFIX."user_group SET permission = '".$this->db->escape(serialize($data))."' WHERE user_group_id = '".(int) $user_group_id."'");
        }
    }

    public function removePermission($user_group_id, $type, $path)
    {
        $user_group_query = $this->db->query('SELECT DISTINCT * FROM '.DB_PREFIX."user_group WHERE user_group_id = '".(int) $user_group_id."'");

        if ($user_group_query->num_rows) {
            $data = unserialize($user_group_query->row['permission']);

            $data[$type] = array_diff($data[$type], [$path]);

            $this->db->query('UPDATE '.DB_PREFIX."user_group SET permission = '".$this->db->escape(serialize($data))."' WHERE user_group_id = '".(int) $user_group_id."'");
        }
    }
}
