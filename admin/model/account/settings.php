<?php

class ModelAccountSettings extends Model
{
    public function update($data)
    {
        //echo "<pre>";print_r($data);die;
        $sql = 'UPDATE `'.DB_PREFIX.'user` SET '
                ."tin_no='".$data['tin_no']."', "
                ."username = '".$this->db->escape($data['username'])."', "
                ."firstname = '".$this->db->escape($data['firstname'])."', "
                ."lastname = '".$this->db->escape($data['lastname'])."', "
                ."email = '".$this->db->escape($data['email'])."', "
                ."mobile = '".$this->db->escape($data['mobile'])."', "
                ."telephone  = '".$this->db->escape($data['telephone'])."', "
                //. "state  = '" . $this->db->escape($data['state']) . "', "
                //. "city  = '" . $this->db->escape($data['city']) . "', "
                ."address  = '".$this->db->escape($data['address'])."', "
                ."latitude  = '".$this->db->escape($data['latitude'])."', "
                ."longitude  = '".$this->db->escape($data['longitude'])."', "
                ."ifsc_code  = '".$this->db->escape($data['ifsc_code'])."', "
                ."bank_acc_no  = '".$this->db->escape($data['bank_acc_no'])."' "
                ."WHERE user_id = '".(int) $this->user->getId()."'";

        $this->db->query($sql);

        $this->addVendorBank($data, $this->user->getId());
    }

    public function addVendorBank($data, $vendor_id)
    {
        $this->db->query('DELETE FROM '.DB_PREFIX."vendor_bank_account WHERE vendor_id = '".(int) $vendor_id."'");

        $this->db->query('INSERT INTO '.DB_PREFIX."vendor_bank_account SET vendor_id = '".(int) $vendor_id."', bank_account_number = '".$data['bank_account_number']."', bank_account_name = '".$this->db->escape($data['bank_account_name'])."', bank_name = '".$this->db->escape($data['bank_name'])."', bank_branch_name = '".$this->db->escape($data['bank_branch_name'])."', bank_account_type = '".$this->db->escape($data['bank_account_type'])."'");
    }

    public function password($data)
    {
        $this->db->query('UPDATE `'.DB_PREFIX."user` SET salt = '".$this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9))."', password = '".$this->db->escape(sha1($salt.sha1($salt.sha1($data['password']))))."' WHERE user_id = '".(int) $this->user->getId()."'");
    }

    public function getUser($user_id)
    {
        return $this->db->query('select * from '.DB_PREFIX.'user where user_id="'.$user_id.'"')->row;
    }

    public function getCity($city_id)
    {
        return $this->db->query('select * from '.DB_PREFIX.'city where city_id= "'.$city_id.'"')->row;
    }
}
