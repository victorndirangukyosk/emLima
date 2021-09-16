<?php

class ModelAccountAddress extends Model {

    public function addAddress($data) {
        $this->trigger->fire('pre.customer.add.address', $data);

        /* $this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int) $this->customer->getId() . "', name = '" . $this->db->escape($data['name']) . "', contact_no = '" . $this->db->escape($data['contact_no']) . "', city_id = '" . $this->db->escape($data['city_id']) . "', address = '" . $this->db->escape($data['address']) . "'"); */
        $this->db->query('INSERT INTO ' . DB_PREFIX . "address SET customer_id = '" . (int) $this->customer->getId() . "', name = '" . $this->db->escape($data['name']) . "', contact_no = '" . $this->db->escape($data['contact_no']) . "', city_id = '" . $this->db->escape($data['city_id']) . "', address_type = '" . $this->db->escape($data['address_type']) . "', flat_number = '" . $this->db->escape($data['flat_number']) . "', building_name = '" . $this->db->escape($data['building_name']) . "', street_address = '" . $this->db->escape($data['landmark']) . "', latitude = '" . $this->db->escape($data['lat']) . "', longitude = '" . $this->db->escape($data['lng']) . "', landmark = '" . $this->db->escape($data['landmark']) . "', zipcode = '" . $this->db->escape($data['zipcode']) . "', address = '" . $this->db->escape($data['address']) . "'");

        $address_id = $this->db->getLastId();

        if (!empty($data['default'])) {
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET address_id = '" . (int) $address_id . "' WHERE customer_id = '" . (int) $this->customer->getId() . "'");
        }

        $this->trigger->fire('post.customer.add.address', $address_id);

        return $address_id;
    }

    public function editAddress($address_id, $data) {
        $this->trigger->fire('pre.customer.edit.address', $data);

        $this->db->query('UPDATE ' . DB_PREFIX . "address SET name = '" . $this->db->escape($data['name']) . "', contact_no = '" . $this->db->escape($data['contact_no']) . "', address_type = '" . $this->db->escape($data['address_type']) . "', flat_number = '" . $this->db->escape($data['flat_number']) . "', building_name = '" . $this->db->escape($data['building_name']) . "', street_address = '" . $this->db->escape($data['landmark']) . "', latitude = '" . $this->db->escape($data['lat']) . "', longitude = '" . $this->db->escape($data['lng']) . "', landmark = '" . $this->db->escape($data['landmark']) . "', zipcode = '" . $this->db->escape($data['zipcode']) . "', address = '" . $this->db->escape($data['address']) . "', city_id = '" . $this->db->escape($data['city_id']) . "' WHERE address_id  = '" . (int) $address_id . "' AND customer_id = '" . (int) $this->customer->getId() . "'");

        if (!empty($data['default'])) {
            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET address_id = '" . (int) $address_id . "' WHERE customer_id = '" . (int) $this->customer->getId() . "'");
        }
        else//else condition is to remove default address , if user unchecks the existing default address
        {
            $default_address_id=0;           
            $default_address_query = $this->db->query('SELECT address_id FROM ' . DB_PREFIX . "customer WHERE   customer_id = '" . (int) $this->customer->getId() . "'");
            if ($default_address_query->num_rows) {
                $default_address_id=$default_address_query->row['address_id'];
            }
            if($default_address_id==$address_id)
            {
                $this->db->query('UPDATE ' . DB_PREFIX . "customer SET address_id = 0 WHERE customer_id = '" . (int) $this->customer->getId() . "'");

            }
        }

        $this->trigger->fire('post.customer.edit.address', $address_id);
    }

    public function deleteAddress($address_id) {
        $this->trigger->fire('pre.customer.delete.address', $address_id);

        $this->db->query('DELETE FROM ' . DB_PREFIX . "address WHERE address_id = '" . (int) $address_id . "' AND customer_id = '" . (int) $this->customer->getId() . "'");

        $this->trigger->fire('post.customer.delete.address', $address_id);
    }

    public function getAddress($address_id) {
        $address_query = $this->db->query('SELECT DISTINCT * FROM ' . DB_PREFIX . "address WHERE address_id = '" . (int) $address_id . "' AND customer_id = '" . (int) $this->customer->getId() . "'");

        //get default addresss from customer table
        $default_address_id=0;
        $isdefault_address=0;
        $default_address_query = $this->db->query('SELECT address_id FROM ' . DB_PREFIX . "customer WHERE   customer_id = '" . (int) $this->customer->getId() . "'");
        if ($default_address_query->num_rows) {
            $default_address_id=$default_address_query->row['address_id'];
        }
        //end default address region

        if ($address_query->num_rows) {
            $city_query = $this->db->query('select * from `' . DB_PREFIX . 'city` WHERE city_id="' . $address_query->row['city_id'] . '"');

            if ($city_query->num_rows) {
                $city = $city_query->row['name'];
            } else {
                $city = '';
            }

            $log = new Log('error.log');
            $log->write('address_query');
            $log->write($address_query->row);
            $log->write('address_query');

            if($address_query->row['address_id']==$default_address_id)
            {
                $isdefault_address=1;
            }

            $address_data = [
                'address_id' => $address_query->row['address_id'],
                'name' => $address_query->row['name'],
                'contact_no' => $address_query->row['contact_no'],
                'address' => $address_query->row['address'],
                'address_type' => $address_query->row['address_type'],
                'zipcode' => $address_query->row['zipcode'],
                'flat_number' => $address_query->row['flat_number'],
                'building_name' => $address_query->row['building_name'],
                'city_id' => $address_query->row['city_id'],
                'landmark' => $address_query->row['landmark'],
                'city' => $city,
                'latitude' => $address_query->row['latitude'],
                'longitude' => $address_query->row['longitude'],
                'isdefault_address' => $isdefault_address,

            ];

            return $address_data;
        } else {
            return false;
        }
    }

    public function getAddresses() {
        $address_data = [];

         //get default addresss from customer table
         $default_address_id=0;
         $default_address_query = $this->db->query('SELECT address_id FROM ' . DB_PREFIX . "customer WHERE   customer_id = '" . (int) $this->customer->getId() . "'");
         if ($default_address_query->num_rows) {
             $default_address_id=$default_address_query->row['address_id'];
         }
         //end default address region

        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "address WHERE customer_id = '" . (int) $this->customer->getId() . "'");

        foreach ($query->rows as $result) {
         $isdefault_address=0;

            $city_query = $this->db->query('select * from `' . DB_PREFIX . 'city` WHERE city_id="' . $result['city_id'] . '"');

            if ($city_query->num_rows) {
                $city = $city_query->row['name'];
            } else {
                $city = '';
            }
            if($result['address_id']==$default_address_id)
            {
                $isdefault_address=1;
            }

            /* if($result['address_type']) {
              $address_type = 'Home';
              }else{
              $address_type = 'Other';
              } */

            $address_data[$result['address_id']] = [
                'address_id' => $result['address_id'],
                'name' => $result['name'],
                'contact_no' => $result['contact_no'],
                'address' => $result['address'],
                'city_id' => $result['city_id'],
                'flat_number' => $result['flat_number'],
                'building_name' => $result['building_name'],
                'street_address' => $result['street_address'],
                'landmark' => $result['landmark'],
                'city' => $city,
                'zipcode' => $result['zipcode'],
                'latitude' => $result['latitude'],
                'longitude' => $result['longitude'],
                'address_type' => ucfirst($result['address_type']),
                'isdefault_address' => $isdefault_address,

            ];
        }

        return $address_data;
    }

    public function getCheckoutQuestion($data = []) {
        //$sql = "SELECT * FROM " . DB_PREFIX . "checkout_question pc" ;

        $sql = 'SELECT * FROM   `' . DB_PREFIX . 'checkout_question` pc  JOIN `' . DB_PREFIX . 'checkout_question_description`  pcd ON pcd.checkout_question_id = pc.checkout_question_id';

        $isWhere = 0;
        $_sql = [];

        if (true) {
            $isWhere = 1;
            $_sql[] = "pcd.language_id= '" . (int) $this->config->get('config_language_id') . "'";
        }

        if (true) {
            $isWhere = 1;

            $_sql[] = 'pc.status = 1';
        }

        if ($isWhere) {
            $sql .= ' WHERE ' . implode(' AND ', $_sql);
        }

        $sort_data = [
            'question',
            'meta_description',
            'meta_keywords',
            'content',
            'status',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY question';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getAddressesByZipcode($zipcode) {
        $address_data = [];

        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "address WHERE customer_id = '" . (int) $this->customer->getId() . "' and zipcode='" . $zipcode . "'");

        foreach ($query->rows as $result) {
            $city_query = $this->db->query('select * from `' . DB_PREFIX . 'city` WHERE city_id="' . $result['city_id'] . '"');

            if ($city_query->num_rows) {
                $city = $city_query->row['name'];
            } else {
                $city = '';
            }

            /* if($result['address_type']) {
              $address_type = 'Home';
              }else{
              $address_type = 'Other';
              } */

            $address_data[$result['address_id']] = [
                'address_id' => $result['address_id'],
                'name' => $result['name'],
                'contact_no' => $result['contact_no'],
                'address' => $result['address'],
                'city_id' => $result['city_id'],
                'flat_number' => $result['flat_number'],
                'building_name' => $result['building_name'],
                'landmark' => $result['landmark'],
                'address_type' => ucfirst($result['address_type']),
                'city' => $city,
            ];
        }

        return $address_data;
    }

    public function getTotalAddresses() {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . "address WHERE customer_id = '" . (int) $this->customer->getId() . "'");

        return $query->row['total'];
    }

    public function getCities() {
        return $this->db->query('select * from `' . DB_PREFIX . 'city` WHERE status=1 order by sort_order')->rows;
    }

    public function getCountries($country_id) {
        return $this->db->query('select iso_code_2 from `' . DB_PREFIX . 'country` WHERE country_id="' . $country_id . '"')->row;
    }

    public function getCustomer($customer_id) {
        return $this->db->query('select * from `' . DB_PREFIX . 'customer` WHERE customer_id="' . $customer_id . '"')->row;
    }

    public function updateCustomer($customer_id, $member_upto, $customer_group_id) {
        $this->db->query('update `' . DB_PREFIX . 'customer` SET member_upto="' . $member_upto . '", customer_group_id="' . $customer_group_id . '" WHERE customer_id="' . $customer_id . '"');
    }

    public function getStoreData($store_id) {
        return $this->db->query('select store_id,name,min_order_cod,min_order_amount,city_id,logo,zipcode,serviceable_radius,latitude,longitude from `' . DB_PREFIX . 'store` where status=1 and store_id="' . $store_id . '"')->row;
    }

    public function getZipList($store_id) {
        $result = [];
        $rows = $this->db->query('select zipcode from ' . DB_PREFIX . 'store_zipcodes where store_id="' . $store_id . '"')->rows;
        foreach ($rows as $row) {
            $result[] = $row['zipcode'];
        }

        return $result;
        //return implode(',', $result);
    }

    public function getCityName($shipping_city_id) {
        return $this->db->query('select name from ' . DB_PREFIX . 'city WHERE city_id="' . $shipping_city_id . '"')->row['name'];
    }

    public function getCityByName($name) {
        $row = $this->db->query('select city_id from ' . DB_PREFIX . 'city WHERE name="' . $name . '"')->row; //['city_id'];
        if (isset($row['city_id'])) {
            return $row['city_id'];
        }

        return '';
    }

    public function updateOrder($payment_code, $payment_method, $shipping_name, $shipping_contact_no, $shipping_address, $shipping_city_id, $flat_number, $building_name, $landmark, $order_id) {
        $this->db->query('UPDATE `' . DB_PREFIX . 'order` SET  payment_code="' . $payment_code . '", payment_method="' . $payment_method . '", shipping_name="' . $shipping_name . '", shipping_contact_no="' . $shipping_contact_no . '", shipping_address="' . $shipping_address . '", shipping_flat_number="' . $flat_number . '", shipping_building_name="' . $building_name . '", shipping_landmark="' . $landmark . '", shipping_city_id="' . $shipping_city_id . '" WHERE order_id="' . $order_id . '"');
    }

    public function getVendorId($store_id) {
        return $this->db->query('select vendor_id from `' . DB_PREFIX . 'store` WHERE store_id="' . $store_id . '"')->row['vendor_id'];
    }

    public function getAllStoreData($store_id) {
        return $this->db->query('select store_id,commision,name,min_order_amount,city_id from `' . DB_PREFIX . 'store` where status=1 and store_id="' . $store_id . '"')->row;
    }

    public function getVendorData($store_id) {
        return $this->db->query('select vendor_id from `' . DB_PREFIX . 'store` WHERE store_id="' . $store_id . '"')->row;
    }

    public function getAllZipcodeByCity($city_id) {
        return $this->db->query('select * from `' . DB_PREFIX . 'city_zipcodes` where city_id="' . $city_id . '"')->rows;
    }

    public function zipcodeExists($zipcode) {
        return $this->db->query('select * from `' . DB_PREFIX . 'city_zipcodes` where zipcode="' . $zipcode . '"')->row;
    }

    public function addressCheck($address, $zipcode = '') {
        $address = str_replace(' ', '+', $address);

        $log = new Log('error.log');
        $log->write('addressCheck');

        $res['status'] = false;

        $region = $this->config->get('config_country_code');

        //print_r($region);
        $url = "http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&components=country:$region";

        $log->write($url);

        //print_r($url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response);

        //print_r($response_a);
        if (isset($response_a->status) && 'OK' == $response_a->status) {
            foreach ($response_a->results[0]->address_components as $key => $value) {
                //print_r($value);
                if (false !== strpos($zipcode, $value->long_name)) {
                    $res['status'] = true;
                    $res['lat'] = $response_a->results[0]->geometry->location->lat;
                    $res['lng'] = $response_a->results[0]->geometry->location->lng;

                    break;
                }
            }

            return $res;
        }

        return $res;
    }

    public function getStoreNameById($id) {
        $sql = 'SELECT name from `' . DB_PREFIX . 'store` WHERE store_id =' . $id;

        return $this->db->query($sql)->row['name'];
    }

    public function getStoreTextById($id) {
        $store_info = $this->getStoreData($id);
        $store_total = $this->cart->getSubTotal($id);

        $ret = "<center style='background-color:#43b02a;color:#fff'> Yay! Free Delivery </center>";

        if ((0 <= $store_info['min_order_cod']) && ($store_info['min_order_cod'] <= 10000)) {
            if ($store_info['min_order_cod'] > $store_total) {
                $freedeliveryprice = $store_info['min_order_cod'] - $store_total;

                $ret = "<center style='background-color:#ff811e;color:#fff'> You are only " . $this->currency->format($freedeliveryprice) . ' away for FREE DELIVERY! </center>';
            }
        } else {
            $ret = '';
        }

        if ($this->config->get('config_active_store_minimum_order_amount') > $this->cart->getSubTotal()) {
            $currentprice = $this->config->get('config_active_store_minimum_order_amount') - $this->cart->getSubTotal();

            $ret = "<center style='background-color:#ee4054;color:#fff'>" . $this->currency->format($currentprice) . ' away from minimum order value </center>';
        }

        return $ret;
    }

    public function getTotalByStore($store_id) {
        $sql = 'SELECT name from `' . DB_PREFIX . 'store` WHERE store_id =' . $id;

        return $this->db->query($sql)->row['name'];
    }

    public function editMakeDefaultAddress($address_id) {
        $this->db->query('UPDATE ' . DB_PREFIX . "customer SET address_id = '" . (int) $address_id . "' WHERE customer_id = '" . (int) $this->customer->getId() . "'");
    }

    public function editMakeDefaultAddressApi($address_id, $customer_id) {
        $this->db->query('UPDATE ' . DB_PREFIX . "customer SET address_id = '" . (int) $address_id . "' WHERE customer_id = '" . (int) $customer_id . "'");
    }

    public function getCityDeliveryDays($city_id) {
        $query = $this->db->query('SELECT DISTINCT * FROM ' . DB_PREFIX . "city_delivery WHERE city_id = '" . (int) $city_id . "'");
        return $query->row;
    }

    public function getCityDetails($city_id) {
        $query = $this->db->query('SELECT DISTINCT * FROM ' . DB_PREFIX . "city WHERE city_id = '" . (int) $city_id . "'");
        return $query->row;
    }

    public function getRegion($region_id) {
        $query = $this->db->query('SELECT DISTINCT * FROM ' . DB_PREFIX . "regions WHERE region_id = '" . (int) $region_id . "'");
        return $query->row;
    }

}
