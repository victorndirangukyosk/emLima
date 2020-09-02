<?php
/*
    Author: Valdeir Santana
    Site: http://www.valdeirsantana.com.br
    License: http://www.gnu.org/licenses/gpl-3.0.en.html
*/
class ModelPaymentIuguSubaccount extends Model
{
    public function saveIuguSubAccount($data)
    {
        $this->db->query('INSERT INTO '.DB_PREFIX.'iugu_sub_account SET account_id = "'.$data['account_id'].'", name = "'.$this->db->escape($data['name']).'", live_api_token = "'.$this->db->escape($data['live_api_token']).'", test_api_token = "'.$this->db->escape($data['test_api_token']).'", commision = "'.$this->db->escape($data['commision']).'", vendor_id = "'.$this->db->escape($data['vendor_id']).'", user_token = "'.$this->db->escape($data['user_token']).'"');
    }
}
