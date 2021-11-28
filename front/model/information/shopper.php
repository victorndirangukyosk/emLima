<?php

class ModelInformationShopper extends Model
{
    public function save($data)
    {
        $this->db->query('insert into `'.DB_PREFIX.'shopper` SET 
            mobile = "'.$data['mobile'].'",
            telephone = "'.$data['telephone'].'",
            city_id = "'.$data['city_id'].'",
            address = "'.$data['address'].'",
            firstname = "'.$data['firstname'].'",
            lastname = "'.$data['lastname'].'",
            username = "'.$data['username'].'",
            password = "'.$data['password'].'",
            email = "'.$data['email'].'",
            date_added =  "'.date('Y-m-d').'"'
        );
    }
}
