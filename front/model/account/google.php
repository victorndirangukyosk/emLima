<?php

class ModelAccountGoogle extends Model
{
    //call after return from google auth
    public function google_returned()
    {
        $this->load->library('openid');

        $this->openid = new Openid();

        if ($this->openid->mode) {
            if ($this->openid->validate()) {
                $data = $this->openid->getAttributes();

                $email = $data['contact/email'];

                //check db for existanse
                $customer_from_db = $this->db->query('select * from `'.DB_PREFIX.'customer` WHERE email="'.$email.'"')->row;

                //insert to db if not exists
                if (!$customer_from_db) {
                    $this->load->model('account/customer');

                    $data = [
                        'firstname' => $data['namePerson/first'],
                        'lastname' => $data['namePerson/last'],
                        'email' => $email,
                        'telephone' => '',
                        'fax' => '',
                        'password' => '',
                    ];

                    $this->model_account_customer->addCustomer($data);
                }

                //login
                $this->customer->login($email, '', $override = true);

                return true;
            } else {
                $this->session->data['warning'] = 'Error: Google Validation Not Completed Successfully!';

                return false;
            }
        } else {
            $this->session->data['warning'] = 'Error: Google Validation Not Completed Successfully!';

            return false;
        }
    }
}
