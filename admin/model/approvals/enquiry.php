<?php

class ModelApprovalsEnquiry extends Model
{
    /*----------------------------------------------------------------
       Move Enquiry to User list
     ----------------------------------------------------------------*/
    public function move($data)
    {
        $row = $this->db->query('select * from `'.DB_PREFIX.'enquiries` where enquiry_id = '.$data['enquiry_id'])->row;

        //add to user list
        $data = array_merge($data, $row);
        $trial_month = $this->config->get('config_trial_months');
        $date_from = date('Y-m-d');
        $date_to = date('Y-m-d', strtotime('+ '.$trial_month.' months'));
        $this->db->query('INSERT INTO `'.DB_PREFIX.'user`'
                    ." SET code='".uniqid()."', free_from='".$date_from."', free_to='".$date_to."', commision='".$data['commision']."',"
                    ." username = '".$this->db->escape($data['username'])."', salt = '".$this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9))."',"
                    ." password = '".$this->db->escape(sha1($salt.sha1($salt.sha1($data['password']))))."', firstname = '".$this->db->escape($data['firstname'])."', "
                    ." lastname = '".$this->db->escape($data['lastname'])."', email = '".$this->db->escape($data['email'])."', user_group_id = '".(int) $data['user_group_id']."', "
                    ." business = '".$data['business']."',
                        type = '".$data['type']."',
                        tin_no = '".$data['tin_no']."',
                        mobile = '".$data['mobile']."',
                        telephone = '".$data['telephone']."',
                        city_id = '".$data['city_id']."',
                        address = '".$data['address']."',
                        store_name ='".$data['store_name']."',"
                    ." status = '1', date_added = NOW()");
        $user_id = $this->db->getLastId();

        //remove from enquiry
        $this->db->query('DELETE FROM `'.DB_PREFIX."enquiries` WHERE enquiry_id = '".(int) $data['enquiry_id']."'");
    }

    public function delete($enquiry_id)
    {
        $this->db->query('DELETE FROM `'.DB_PREFIX."enquiries` WHERE enquiry_id = '".(int) $enquiry_id."'");
    }

    public function get($data = [])
    {
        $sql = 'SELECT * FROM `'.DB_PREFIX.'enquiries`';

        $sort_data = [
            'username',
            'status',
            'date_added',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY '.$data['sort'];
        } else {
            $sql .= ' ORDER BY enquiry_id';
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
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `'.DB_PREFIX.'enquiries`');

        return $query->row['total'];
    }

    public function getVendorGroup($config_vendor_group_ids)
    {
        return $this->db->query('select * from '.DB_PREFIX.'user_group where user_group_id IN ('.$config_vendor_group_ids.')')->rows;
    }

    public function getEnquiry($enquiry_id)
    {
        $sql = 'select e.*, c.name as city from '.DB_PREFIX.'enquiries e ';
        $sql .= 'left join `'.DB_PREFIX.'city` c on c.city_id = e.city_id ';
        $sql .= 'where e.enquiry_id="'.$enquiry_id.'"';

        return  $this->db->query($sql)->row;
    }
}
