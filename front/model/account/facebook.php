<?php

class ModelAccountFacebook extends Model
{
    //call after return from facebook
    public function callback($data)
    {
        $customer_from_db = false;

        //check db for existanse
        if (isset($data['email'])) {
            $customer_from_db = $this->db->query('select * from `'.DB_PREFIX.'customer` WHERE email="'.$data['email'].'"')->row;
        }

        //insert to db if not exists
        if (isset($data['email']) && !$customer_from_db) {
            $this->load->model('account/customer');

            if (!isset($data['first_name'])) {
                $data['first_name'] = $data['name'];
            }

            if (!isset($data['last_name'])) {
                $data['last_name'] = '';
            }

            $customer = [
                'firstname' => $data['first_name'],
                'lastname' => $data['last_name'],
                'email' => $data['email'],
                'telephone' => '',
                'fax' => '',
                'password' => '',
            ];

            $customer_id = $this->model_account_customer->addCustomer($customer);

            $customer_from_db = $this->db->query('select * from `'.DB_PREFIX.'customer` WHERE customer_id="'.$customer_id.'"')->row;
        }

        //login
        if ($customer_from_db) {
            $this->customer->login($customer_from_db['email'], '', $override = true);

            return true;
        } else {
            $this->session->data['warning'] = 'Error: Facebook Validation Not Completed Successfully!';

            return false;
        }
    }
}
