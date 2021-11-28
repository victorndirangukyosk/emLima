<?php
/*
    Author: Valdeir Santana
    Site: http://www.valdeirsantana.com.br
    License: http://www.gnu.org/licenses/gpl-3.0.en.html
*/
class ModelPaymentIuguBillet extends Model
{
    public function getMethod($address, $total)
    {
        $this->load->language('payment/iugu');

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."zone_to_geo_zone WHERE geo_zone_id = '".(int) $this->config->get('cod_geo_zone_id')."' AND country_id = '".(int) $address['country_id']."' AND (zone_id = '".(int) $address['zone_id']."' OR zone_id = '0')");

        if (!$this->config->get('iugu_geo_zone_id')) {
            $status = true;
        } elseif ($query->num_rows) {
            $status = true;
        } else {
            $status = false;
        }

        $method_data = [];

        if ($status) {
            $method_data = [
                'code' => 'iugu_billet',
                'title' => $this->language->get('text_billet'),
                'terms' => '',
                'sort_order' => $this->config->get('iugu_sort_order'),
            ];
        }

        return $method_data;
    }
}
