<?php

class ModelAccountFarmer extends Model
{
    public function addFarmer($data)
    {
        $log = new Log('error.log');

        $log->write('farmer add');
        $this->trigger->fire('pre.farmer.add', $data);

        if (isset($data['telephone'])) {
            //(21) 42353-5255
            $data['telephone'] = preg_replace('/[^0-9]/', '', $data['telephone']);
        }
        $this->db->query('INSERT INTO '.DB_PREFIX."farmer SET
      name = '".$this->db->escape($data['name'])."',
      email_id = '".$this->db->escape($data['email'])."',
      farm_address = '".$this->db->escape($data['Address'])."',
      contact_number = '".$this->db->escape($data['telephone'])."',
      farmer_type = '".$this->db->escape($data['farmertype'])."',
      work_on_farm = '".$this->db->escape($data['farm'])."',
      country = '".$this->db->escape($data['country_id'])."',
      village = '".$this->db->escape($data['town'])."',
      business_entity = '".$this->db->escape($data['businessentity'])."',
      name_of_farm = '".$this->db->escape($data['nameoffarm'])."',
      total = '".$this->db->escape($data['Total'])."',
      crop_type = '".$this->db->escape($data['Crop'])."',
      crops_grown = '".$this->db->escape($data['cropsgrown'])."',
      crop_produce = '".$this->db->escape($data['cropproduce'])."',
      sell_produce = '".$this->db->escape($data['sellproduce'])."'");

        $farmer_id = $this->db->getLastId();

        //Get Email Template

        //     $subject = $this->emailtemplate->getSubject('Customer', 'customer_1', $data);
        //     $message = $this->emailtemplate->getMessage('Customer', 'customer_1', $data);
        //     $sms_message = $this->emailtemplate->getSmsMessage('Customer', 'customer_1', $data);

        // $mail = new Mail($this->config->get('config_mail'));
        // $mail->setTo($data['email']);
        // $mail->setFrom($this->config->get('config_from_email'));
        // $mail->setSender($this->config->get('config_name'));
        // $mail->setSubject($subject);
        // $mail->setHTML($message);
        // $mail->send();

        // // send message here
        // if ( $this->emailtemplate->getSmsEnabled('Customer','customer_1')) {

        //     $ret =  $this->emailtemplate->sendmessage($data['telephone'],$sms_message);

        // }

        // Send to main admin email if new account email is enabled
        // if ($this->config->get('config_account_mail')) {
        //     $mail->setTo($this->config->get('config_email'));
        //     $mail->send();

        //     $emails = explode(',', $this->config->get('config_alert_emails'));

        //     foreach ($emails as $email) {
        //         if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        //             $mail->setTo($email);
        //             $mail->send();
        //         }
        //     }
        // }

        $this->trigger->fire('post.farmer.add', $farmer_id);

        return $farmer_id;
    }
    
    public function addNewFarmer($data)
    {
        $log = new Log('error.log');

        $log->write('farmer add');
        $log->write($data);
        $log->write('farmer add');
        $this->trigger->fire('pre.farmer.add', $data);

        if (isset($data['telephone'])) {
            //(21) 42353-5255
            $data['telephone'] = preg_replace('/[^0-9]/', '', $data['telephone']);
        }
        $this->db->query('INSERT INTO '.DB_PREFIX."farmer SET
      name = '".$this->db->escape($data['name'])."',
      email_id = '".$this->db->escape($data['email'])."',
      farm_address = '".$this->db->escape($data['Address'])."',
      contact_number = '".$this->db->escape($data['telephone'])."',
      farmer_type = '".$this->db->escape($data['farmertype'])."',
      work_on_farm = '".$this->db->escape($data['farm'])."',
      country = '".$this->db->escape($data['country_id'])."',
      village = '".$this->db->escape($data['town'])."',
      business_entity = '".$this->db->escape($data['businessentity'])."',
      name_of_farm = '".$this->db->escape($data['nameoffarm'])."',
      total = '".$this->db->escape($data['Total'])."',
      crop_type = '".$this->db->escape($data['Crop'])."',
      crops_grown = '".$this->db->escape($data['cropsgrown'])."',
      crop_produce = '".$this->db->escape($data['cropproduce'])."',
      sell_produce = '".$this->db->escape($data['sellproduce'])."'");

        $farmer_id = $this->db->getLastId();

        //Get Email Template

        //     $subject = $this->emailtemplate->getSubject('Customer', 'customer_1', $data);
        //     $message = $this->emailtemplate->getMessage('Customer', 'customer_1', $data);
        //     $sms_message = $this->emailtemplate->getSmsMessage('Customer', 'customer_1', $data);

        // $mail = new Mail($this->config->get('config_mail'));
        // $mail->setTo($data['email']);
        // $mail->setFrom($this->config->get('config_from_email'));
        // $mail->setSender($this->config->get('config_name'));
        // $mail->setSubject($subject);
        // $mail->setHTML($message);
        // $mail->send();

        // // send message here
        // if ( $this->emailtemplate->getSmsEnabled('Customer','customer_1')) {

        //     $ret =  $this->emailtemplate->sendmessage($data['telephone'],$sms_message);

        // }

        // Send to main admin email if new account email is enabled
        // if ($this->config->get('config_account_mail')) {
        //     $mail->setTo($this->config->get('config_email'));
        //     $mail->send();

        //     $emails = explode(',', $this->config->get('config_alert_emails'));

        //     foreach ($emails as $email) {
        //         if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        //             $mail->setTo($email);
        //             $mail->send();
        //         }
        //     }
        // }

        $this->trigger->fire('post.farmer.add', $farmer_id);

        return $farmer_id;
    }
    
    public function editfarmer($data)
    {
        // echo "<pre>";print_r($data);die;
        $this->trigger->fire('pre.farmer.edit', $data);

        $farmer_id = $this->farmer->getId();

        if (isset($data['telephone'])) {
            //(21) 42353-5255
            $data['telephone'] = preg_replace('/[^0-9]/', '', $data['telephone']);
        }

        $this->db->query('UPDATE '.DB_PREFIX."farmer SET name = '".$this->db->escape($data['name'])."' , email = '".$this->db->escape($data['email'])."', telephone = '".$this->db->escape($data['telephone'])."'  WHERE farmer_id = '".(int) $farmer_id."'");

        $this->trigger->fire('post.farmer.edit', $farmer_id);
    }

    public function getfarmer($farmer_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."farmer WHERE farmer_id = '".(int) $farmer_id."'");

        return $query->row;
    }

    public function getfarmerByEmail($email)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."farmer WHERE LOWER(email_id) = '".$this->db->escape(utf8_strtolower($email))."'");

        return $query->row;
    }

    public function getfarmerByPhone($phone)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."farmer WHERE telephone='".$this->db->escape($phone)."'");

        return $query->row;
    }

    public function getTotalfarmersByEmail($email)
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX."farmer WHERE LOWER(email) = '".$this->db->escape(utf8_strtolower($email))."'");

        return $query->row['total'];
    }

    public function getTotalfarmersByPhone($telephone)
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX."farmer WHERE telephone = '".$telephone."'");

        return $query->row['total'];
    }
}
