<?php

    /**
     * @copyright     	(c) 2017 iPay Limited. All rights reserved.
     * @author        	Moses King'ori <moses@intrepid.co.ke>
     * @license			This program is free software; you can redistribute it and/or modify
     *            		it under the terms of the GNU General Public License, version 2, as
     *              	published by the Free Software Foundation.
     *
     * 					This program is distributed in the hope that it will be useful,
     *      			but WITHOUT ANY WARRANTY; without even the implied warranty of
     *         			MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *            		GNU General Public License for more details.
     *
     * 					You should have received a copy of the GNU General Public License
     *      			along with this program; if not, write to the Free Software
     *         			Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
     */
    class ModelPaymentiPayPaymentSoftware extends Model
    {
        //public function getMethod($address, $total) {
        public function getMethod($total)
        {
            $this->load->language('payment/iPay_payment_software');

            /*$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('iPay_payment_software_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");*/

            if ($this->config->get('iPay_payment_software_total') > $total) {
                $status = false;
            } /*elseif (!$this->config->get('iPay_payment_software_geo_zone_id')) {
                $status = true;
            } elseif ($query->num_rows) {
                $status = true;
            }*/ else {
                $status = true;
            }

            $method_data = [];

            if ($status) {
                $method_data = [
                    'code' => 'iPay_payment_software',
                    'title' => $this->language->get('text_title'),
                    'terms' => $this->language->get('text_terms'),
                    'sort_order' => $this->config->get('iPay_payment_software_sort_order'),
                  ];
            }

            return $method_data;
        }
    }
?>

