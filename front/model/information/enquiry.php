<?php

class ModelInformationEnquiry extends Model
{
    public function save($data)
    {
        if ($data['business']) {
            $business = implode(',', $data['business']);
        } else {
            $business = '';
        }

        $enquiry_query = $this->db->query('select * from `'.DB_PREFIX.'enquiries` where email = "'.$data['email'].'"');

        if ($enquiry_query->num_rows) {
            return 0;
        } else {
            $this->db->query('insert into `'.DB_PREFIX.'enquiries` SET 
                business = "'.$business.'",
                type = "'.$data['type'].'",
                mobile = "'.$data['mobile'].'",
                telephone = "'.$data['telephone'].'",
                tin_no = "'.$data['tin_no'].'",   
                city_id = "'.$data['city_id'].'",
                address = "'.$data['address'].'",
                store_name = "'.$data['store_name'].'",
                firstname = "'.$data['firstname'].'",
                lastname = "'.$data['lastname'].'",
                username = "'.$data['email'].'",
                password = "'.$data['password'].'",
                email = "'.$data['email'].'",
                about_us = "'.$data['about_us'].'",
                date_added =  "'.date('Y-m-d').'"'
            );

            return $this->db->getLastId();
        }
    }
}
