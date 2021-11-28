<?php
/*
    Author: Valdeir Santana
    Site: http://www.valdeirsantana.com.br
    License: http://www.gnu.org/licenses/gpl-3.0.en.html
*/
class ModelPaymentIuguCreditCard extends Model
{
    public function getMethod($total)
    {
        $this->load->language('payment/iugu');

        /*$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('cod_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
*/
        if (!$this->config->get('iugu_geo_zone_id')) {
            $status = true;
        } /*elseif ($query->num_rows) {
            $status = true;
        }*/ else {
            $status = false;
        }

        if ($total <= 0.00) {
            $status = false;
        } else {
            $status = true;
        }

        $method_data = [];

        if ($status) {
            $method_data = [
                'code' => 'iugu_credit_card',
                'title' => $this->language->get('text_credit_card'),
                'terms' => '',
                'sort_order' => $this->config->get('iugu_sort_order'),
            ];
        }

        return $method_data;
    }

    public function saveIuguSubAccount($data)
    {
        $this->db->query('INSERT INTO '.DB_PREFIX.'iugu_sub_account SET account_id = "'.$data['account_id'].'", name = "'.$this->db->escape($data['name']).'", live_api_token = "'.$this->db->escape($data['live_api_token']).'", test_api_token = "'.$this->db->escape($data['test_api_token']).'", user_token = "'.$this->db->escape($data['user_token']).'"');
    }
}
