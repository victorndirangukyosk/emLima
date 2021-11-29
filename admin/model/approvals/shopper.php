<?php

class ModelApprovalsShopper extends Model
{
    /*----------------------------------------------------------------
       Move Enquiry to User list
     ----------------------------------------------------------------*/
    public function move($data)
    {
        $row = $this->db->query('select * from `'.DB_PREFIX.'shopper` where shopper_id = '.$data['shopper_id'])->row;

        $data = array_merge($data, $row);

        //add to user list
        $this->db->query('INSERT INTO `'.DB_PREFIX.'user`'
                    ." SET code='".uniqid()."',"
                    ." username = '".$this->db->escape($data['username'])."',"
                    ." salt = '".$this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9))."',"
                    ." password = '".$this->db->escape(sha1($salt.sha1($salt.sha1($data['password']))))."',"
                    ." firstname = '".$this->db->escape($data['firstname'])."', "
                    ." lastname = '".$this->db->escape($data['lastname'])."', "
                    ." email = '".$this->db->escape($data['email'])."', "
                    ." user_group_id = '".(int) $data['user_group_id']."', "
                    ." mobile = '".$data['mobile']."',
                        telephone = '".$data['telephone']."',
                        city_id = '".$data['city_id']."',
                        address = '".$data['address']."',
                        status = '1', date_added = NOW()");

        //remove from shopper
        $this->db->query('DELETE FROM `'.DB_PREFIX."shopper` WHERE shopper_id = '".(int) $data['shopper_id']."'");
    }

    public function delete($shopper_id)
    {
        $this->db->query('DELETE FROM `'.DB_PREFIX."shopper` WHERE shopper_id = '".(int) $shopper_id."'");
    }

    public function get($data = [])
    {
        $sql = 'SELECT * FROM `'.DB_PREFIX.'shopper`';

        $sort_data = [
            'username',
            'status',
            'date_added',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY '.$data['sort'];
        } else {
            $sql .= ' ORDER BY shopper_id';
        }

        if (isset($data['order']) && ('ASC' == $data['order'])) {
            $sql .= ' ASC';
        } else {
            $sql .= ' DESC';
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
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `'.DB_PREFIX.'shopper`');

        return $query->row['total'];
    }

    public function getUserGroup($shopper_group_ids)
    {
        return $this->db->query('select * from '.DB_PREFIX.'user_group where user_group_id IN ('.$shopper_group_ids.')')->rows;
    }

    public function getCityAndShopper($shopper_id)
    {
        $sql = 'select s.*, c.name as city from '.DB_PREFIX.'shopper s ';
        $sql .= 'left join `'.DB_PREFIX.'city` c on c.city_id = s.city_id ';
        $sql .= 'where shopper_id="'.$shopper_id.'"';

        return $this->db->query($sql)->row;
    }
}
