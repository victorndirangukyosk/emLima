<?php

class ModelSettingSetting extends Model
{
    public function getSetting($code, $store_id = 0)
    {
        $setting_data = [];

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."setting WHERE store_id = '".(int) $store_id."' AND `code` = '".$this->db->escape($code)."'");

        foreach ($query->rows as $result) {
            if (!$result['serialized']) {
                $setting_data[$result['key']] = $result['value'];
            } else {
                $setting_data[$result['key']] = unserialize($result['value']);
            }
        }

        return $setting_data;
    }

    public function getDeliveryTimeslots($code)
    {
        return $this->db->query('select * from '.DB_PREFIX.$code.'_delivery_timeslot GROUP BY timeslot')->rows;
    }

    public function getDeliveryStatus($timeslot, $day, $code)
    {
        $row = $this->db->query('select * from '.DB_PREFIX.$code.'_delivery_timeslot WHERE timeslot="'.$timeslot.'" AND day="'.$day.'"')->row;

        if ($row) {
            return $row['status'];
        }
    }

    public function editSetting($code, $data, $store_id = 0)
    {
        $this->db->query('DELETE FROM `'.DB_PREFIX."setting` WHERE store_id = '".(int) $store_id."' AND `code` = '".$this->db->escape($code)."'");

        foreach ($data as $key => $value) {
            if (substr($key, 0, strlen($code)) == $code) {
                if (!is_array($value)) {
                    $this->db->query('INSERT INTO '.DB_PREFIX."setting SET store_id = '".(int) $store_id."', `code` = '".$this->db->escape($code)."', `key` = '".$this->db->escape($key)."', `value` = '".$this->db->escape($value)."'");
                } else {
                    $this->db->query('INSERT INTO '.DB_PREFIX."setting SET store_id = '".(int) $store_id."', `code` = '".$this->db->escape($code)."', `key` = '".$this->db->escape($key)."', `value` = '".$this->db->escape(serialize($value))."', serialized = '1'");
                }
            }
        }

        // store delivery time diff

        //timeslots
        $i = 0; //foreach day

        $this->db->query('TRUNCATE '.DB_PREFIX.$code.'_delivery_timeslot');

        foreach ($data['delivery_timeslots'] as $arr) {
            //foreach timeslot
            foreach ($arr as $key => $value) {
                $this->db->query('INSERT INTO '.DB_PREFIX.$code.'_delivery_timeslot SET day="'.$i.'", timeslot="'.$key.'", status="'.$value.'"');
            }
            ++$i;
        }
    }

    public function deleteSetting($code, $store_id = 0)
    {
        $this->db->query('DELETE FROM '.DB_PREFIX."setting WHERE store_id = '".(int) $store_id."' AND `code` = '".$this->db->escape($code)."'");
    }

    public function editSettingValue($code = '', $key = '', $value = '', $store_id = 0)
    {
        if (!is_array($value)) {
            $this->db->query('UPDATE '.DB_PREFIX."setting SET `value` = '".$this->db->escape($value)."' WHERE `code` = '".$this->db->escape($code)."' AND `key` = '".$this->db->escape($key)."' AND store_id = '".(int) $store_id."'");
        } else {
            $this->db->query('UPDATE '.DB_PREFIX."setting SET `value` = '".$this->db->escape(serialize($value))."', serialized = '1' WHERE `code` = '".$this->db->escape($code)."' AND `key` = '".$this->db->escape($key)."' AND store_id = '".(int) $store_id."'");
        }
    }

    public function getStore($store_id)
    {
        return $this->db->query('select * from '.DB_PREFIX.'store WHERE store_id="'.$store_id.'"')->row;
    }

    public function getUserGroup($moduleName)
    {
        $query = $this->db->query('SELECT DISTINCT * FROM '.DB_PREFIX."user_group WHERE name = '".$moduleName."'");

        return $query->rows;
    }

    public function saveUserGroup($moduleName, $permissions)
    {
        return $this->db->query('INSERT INTO '.DB_PREFIX."user_group SET name = '".$moduleName."', permission = '".(isset($permissions) ? serialize($permissions) : '')."'");
    }

    public function getUserGroupRow($moduleName)
    {
        $query = $this->db->query('SELECT DISTINCT * FROM '.DB_PREFIX."user_group WHERE name = '".$moduleName."'");

        return $query->row;
    }

    public function getUserByGroup($moduleName)
    {
        $queryFirst = $this->db->query('SELECT DISTINCT * FROM '.DB_PREFIX."user_group WHERE name = '".$moduleName."'");
        $user_group_id = $queryFirst->row['user_group_id'];
        $query = $this->db->query('SELECT * FROM `'.DB_PREFIX."user` WHERE user_group_id = '".$this->db->escape($user_group_id)."'");

        return $query->rows;
    }

    public function removeUser($user_id)
    {
        return $this->db->query('DELETE FROM `'.DB_PREFIX."user` WHERE user_id = '".(int) $user_id."'");
    }

    public function saveUser($username, $password, $email, $user_group_id)
    {
        $this->db->query('INSERT INTO `'.DB_PREFIX."user` 
			SET 
			username = '".$this->db->escape($username)."',
			salt = '".$this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9))."',
			password = '".$this->db->escape(sha1($salt.sha1($salt.sha1($password))))."',
			firstname = '".$this->db->escape($username)."',
			lastname = '".$this->db->escape($username)."',
			email = '".$this->db->escape($email)."',
			user_group_id = '".(int) $user_group_id."',
			status = '1',
			date_added = NOW()");
    }

    public function editEmailSettings($data)
    {
        if($this->db->escape($data['config_consolidatedorder']))
        // $query='UPDATE '.DB_PREFIX."setting_email SET `value` = '".$this->db->escape($data['config_consolidatedorder'])."' WHERE `code` = 'consolidatedorder' AND `key` =  'consolidatedorder' ";
        // echo "<pre>";print_r($query);die;
        $this->db->query('UPDATE '.DB_PREFIX."setting_email SET `value` = '".$this->db->escape($data['config_consolidatedorder'])."' WHERE `code` = 'consolidatedorder' AND `key` =  'consolidatedorder' ");
        if($this->db->escape($data['config_careers']))         
        $this->db->query('UPDATE '.DB_PREFIX."setting_email SET `value` = '".$this->db->escape($data['config_careers'])."' WHERE `code` = 'careers' AND `key` =  'careers' ");
        if($this->db->escape($data['config_stockout']))         
        $this->db->query('UPDATE '.DB_PREFIX."setting_email SET `value` = '".$this->db->escape($data['config_stockout'])."' WHERE `code` = 'stockout' AND `key` =  'stockout' ");
       
        if($this->db->escape($data['config_issue']))         
        $this->db->query('UPDATE '.DB_PREFIX."setting_email SET `value` = '".$this->db->escape($data['config_issue'])."' WHERE `code` = 'issue' AND `key` =  'issue' ");
       
        if($this->db->escape($data['config_financeteam']))         
        $this->db->query('UPDATE '.DB_PREFIX."setting_email SET `value` = '".$this->db->escape($data['config_financeteam'])."' WHERE `code` = 'financeteam' AND `key` =  'financeteam' ");
       

        if($this->db->escape($data['config_meatcheckingteam']))         
        $this->db->query('UPDATE '.DB_PREFIX."setting_email SET `value` = '".$this->db->escape($data['config_meatcheckingteam'])."' WHERE `code` = 'meatcheckingteam' AND `key` =  'meatcheckingteam' ");
       
    }

    
    public function getEmailSettings()
    {
        
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."setting_email WHERE  `code` =  'consolidatedorder' or `code` =  'careers'  or `code` =  'stockout' or `code` =  'issue' or `code` =  'financeteam' or `code` ='meatcheckingteam'");
       
        return $query->rows;
    }


    public function getEmailSetting($code)
    {
        
        $query = $this->db->query('SELECT value FROM '.DB_PREFIX."setting_email WHERE  `code` =  '".$code."'   ");
        //echo "<pre>";print_r($query->row[value]);die;
        return $query->row['value'];
    }
}
