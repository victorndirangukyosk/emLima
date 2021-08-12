<?php

class ModelApiStores extends Model
{
    public function editStore($store_id, $data)
    {
        $this->trigger->fire('pre.admin.store.edit', $data);

        /*if ($this->user->isVendor()) {
            $vendor_id = $this->user->getId();
        } else {
            $vendor_id = $data['vendor_id'];
        }*/

        $vendor_id = $this->session->data['api_id'];

        /*$data['zipcode'] = '';
        if(count($data['city_zipcodes']) > 0 ) {

            $data['zipcode'] = implode(',', $data['city_zipcodes']);
        }*/

        /*$resp = $this->getZipcode($data['latitude'],$data['longitude']);

        $storeZipcode = null;
        if($resp['status']) {
            $storeZipcode = $resp['zipcode'];
        }*/

        /*$query = "UPDATE " . DB_PREFIX . "store SET city_id='".$data['city_id']."', fax='".$data['fax']."', telephone='".$data['telephone']."', email='".$data['email']."', latitude='" . $this->db->escape($data['latitude']) . "', longitude='" . $this->db->escape($data['longitude']) . "',`zipcode` = '" . $this->db->escape($data['zipcode']) . "', name = '" . $this->db->escape($data['name']) . "', store_zipcode='" . $data['store_zipcode']  . "', vendor_id='" . $vendor_id . "', address='" . $data['address'] . "', big_logo='" . $data['big_logo'] . "', logo='" . $data['logo'] . "', delivery_time_diff='" . $data['delivery_time_diff'] . "', status='" . $data['status'] . "',min_order_amount='" . $data['min_order_amount'] . "',min_order_cod ='" . $data['min_order_cod'] . "',commision='" . $data['commision'] . "',delivery_by_owner='" . $data['delivery_by_owner'] ."',cost_of_delivery='" . $data['cost_of_delivery'] ."' WHERE store_id = '" . (int) $store_id . "'";*/

        //store_zipcode is address of zipcode

        /*$query = "UPDATE " . DB_PREFIX . "store SET fax='".$data['fax']."', telephone='".$data['telephone']."', email='".$data['email']."', latitude='" . $this->db->escape($data['latitude']) . "', longitude='" . $this->db->escape($data['longitude']) . "', pickup_notes = '" . $this->db->escape($data['pickup_notes']) . "', name = '" . $this->db->escape($data['name']) . "', store_zipcode='" . $data['store_zipcode']  . "', address='" . $data['address'] . "', big_logo='" . $data['big_logo'] . "', logo='" . $data['logo'] . "', status='" . $data['status'] . "',min_order_amount='" . $data['min_order_amount'] . "',min_order_cod ='" . $data['min_order_cod'] . "',commision='" . $data['commision'] . "',delivery_by_owner='" . $data['delivery_by_owner']  ."' WHERE store_id = '" . (int) $store_id . "'";*/

        //store_pickup_timeslots
        unset($data['id']);
        $i = 0;
        $sql = 'UPDATE `'.DB_PREFIX.'store` SET ';
        foreach ($data as $key => $value) {
            if ('pickup_delivery' == $key) {
                $key = 'store_pickup_timeslots';
            }
            if ('tin_no' == $key) {
                $key = 'tax';
            }

            if (in_array($key, ['city_id', 'user_group_id', 'store_pickup_timeslots'])) {
                $value = (int) $value;
            } elseif (in_array($key, ['latitude', 'longitute', 'name', 'pickup_notes'])) {
                $value = $this->db->escape($value);
            }

            $sql .= (0 == $i) ? " $key = '".$value."'" : " , $key = '".$value."'";
            ++$i;
        }

        $sql .= " WHERE store_id = '".(int) $store_id."'";
        //echo $sql;exit;
        // $query = "UPDATE " . DB_PREFIX . "store SET pickup_notes = '" . $this->db->escape($data['pickup_notes']) ."' WHERE store_id = '" . (int) $store_id . "'";

        $this->db->query($sql);

        /*//remove old zipcode list
        $this->db->query('delete from `' . DB_PREFIX . 'store_zipcodes` where store_id="' . $store_id . '"');
        $this->cache->delete('store');

        //add zipcodes
        //$zipcodes = explode(',', $data['zipcode']);
        foreach ($data['city_zipcodes'] as $zipcode) {
            $this->db->query('INSERT INTO `' . DB_PREFIX . 'store_zipcodes` set store_id="' . $store_id . '", zipcode="' . $zipcode . '"');
        }

         //timeslots
        $this->db->query( 'DELETE FROM '.DB_PREFIX.'store_pickup_timeslot WHERE store_id="'.$store_id.'"' );
        $this->db->query( 'DELETE FROM '.DB_PREFIX.'store_delivery_timeslot WHERE store_id="'.$store_id.'"' );



        //timeslots
        $i=0;//foreach day
        foreach ( $data['pickup_timeslots'] as $arr ) {

            //foreach timeslot
            foreach ( $arr as $key=> $value ) {
                $this->db->query( 'INSERT INTO '.DB_PREFIX.'store_pickup_timeslot SET store_id = "'.$store_id.'", day="'.$i.'", timeslot="'.$key.'", status="'.$value.'"' );
            }
            $i++;
        }


        //timeslots
        $i=0;//foreach day
        foreach ( $data['delivery_timeslots'] as $arr ) {

            //foreach timeslot
            foreach ( $arr as $key=> $value ) {
                $this->db->query( 'INSERT INTO '.DB_PREFIX.'store_delivery_timeslot SET store_id = "'.$store_id.'", day="'.$i.'", timeslot="'.$key.'", status="'.$value.'"' );
            }
            $i++;
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'store_id=" . (int) $store_id . "'");

        $alias = empty($data['seo_url']) ? $data['name'] : $data['seo_url'];

        $alias = $this->model_catalog_url_alias->generateAlias($alias);

        if ($alias) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'store_id=" . (int) $store_id . "', keyword = '" . $this->db->escape($alias) . "',language_id = '1'");
        }
*/
        $this->trigger->fire('post.admin.store.edit', $store_id);
    }

    public function getStores($data = [])
    {
        $sql = 'SELECT s.*, c.name as city FROM '.DB_PREFIX.'store s ';
        $sql .= 'LEFT JOIN `'.DB_PREFIX.'city` c on c.city_id = s.city_id ';

        if (!empty($data['filter_vendor'])) {
            $sql .= ' inner join `'.DB_PREFIX.'user` u on u.user_id = s.vendor_id';
        }

        $implode = [];

        $json['vendor_info'] = $this->model_setting_setting->getUser($this->session->data['api_id']);

        $vendor_group_ids = explode(',', $this->config->get('config_vendor_group_ids'));

        if (in_array($json['vendor_info']['user_group_id'], $vendor_group_ids)) {
            $implode[] = "s.vendor_id = '".$this->session->data['api_id']."'";
        }

        /*if ($this->user->isVendor()) {

        }*/

        if (!empty($data['filter_vendor'])) {
            $implode[] = "CONCAT(u.firstname,' ',u.lastname) LIKE '".$this->db->escape($data['filter_vendor'])."%'";
        }

        if (!empty($data['filter_city'])) {
            $implode[] = "c.name LIKE '".$this->db->escape($data['filter_city'])."%'";
        }
        if (!empty($data['filter_name'])) {
            $implode[] = "s.name LIKE '".$this->db->escape($data['filter_name'])."%'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "s.date_added = '".$this->db->escape($data['filter_date_added'])."'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "s.status = '".$this->db->escape($data['filter_status'])."'";
        }

        if ($implode) {
            $sql .= ' WHERE '.implode(' AND ', $implode);
        }

        if (!empty($data['filter_vendor_id'])) {
            $sql .= " WHERE s.vendor_id= '".$data['filter_vendor_id']."'";
        }

        $sort_data = [
            's.name',
            's.status',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY '.$this->db->escape($data['sort']);
        } else {
            $sql .= ' ORDER BY s.name';
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

            $sql .= ' LIMIT '.(int) $data['start'].','.(int) $data['limit'];
        }
        
        $log = new Log('error.log');
        $log->write($sql);
        return $this->db->query($sql)->rows;
    }

    public function getTotalStores($data = [])
    {
        $sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'store s ';
        $sql .= 'LEFT JOIN `'.DB_PREFIX.'city` c on c.city_id = s.city_id ';

        if (!empty($data['filter_vendor'])) {
            $sql .= ' inner join `'.DB_PREFIX.'user` u on u.user_id = s.vendor_id';
        }

        $implode = [];

        // if ($this->user->isVendor()) {
        //     $implode[] = "s.vendor_id = '" . $this->user->getId() . "'";
        // }
        $json['vendor_info'] = $this->model_setting_setting->getUser($this->session->data['api_id']);

        $vendor_group_ids = explode(',', $this->config->get('config_vendor_group_ids'));

        if (in_array($json['vendor_info']['user_group_id'], $vendor_group_ids)) {
            $implode[] = "s.vendor_id = '".$this->session->data['api_id']."'";
        }

        if (!empty($data['filter_vendor'])) {
            $implode[] = "CONCAT(u.firstname,' ',u.lastname) LIKE '".$this->db->escape($data['filter_vendor'])."%'";
        }

        if (!empty($data['filter_city'])) {
            $implode[] = "c.name LIKE '".$this->db->escape($data['filter_city'])."%'";
        }

        if (!empty($data['filter_name'])) {
            $implode[] = "s.name LIKE '".$this->db->escape($data['filter_name'])."%'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "s.date_added = '".$this->db->escape($data['filter_date_added'])."'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "s.status = '".$this->db->escape($data['filter_status'])."'";
        }

        if ($implode) {
            $sql .= ' WHERE '.implode(' AND ', $implode);
        }

        if (!empty($data['filter_vendor_id'])) {
            $sql .= " WHERE s.vendor_id= '".$data['filter_vendor_id']."'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function deleteStore($store_id)
    {
        $this->trigger->fire('pre.admin.store.delete', $store_id);

        $this->db->query('DELETE FROM '.DB_PREFIX."store WHERE store_id = '".(int) $store_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."category_to_store WHERE store_id = '".(int) $store_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."information_to_store WHERE store_id = '".(int) $store_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."manufacturer_to_store WHERE store_id = '".(int) $store_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."menu_child_to_store WHERE store_id = '".(int) $store_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."menu_to_store WHERE store_id = '".(int) $store_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."product_to_store WHERE store_id = '".(int) $store_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."simple_blog_article_to_store WHERE store_id = '".(int) $store_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."simple_blog_category_to_store WHERE store_id = '".(int) $store_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."store_zipcodes WHERE store_id = '".(int) $store_id."'");
        //s$this->db->query("DELETE FROM " . DB_PREFIX . "layout_path WHERE store_id = '" . (int) $store_id . "'");

        $this->db->query('DELETE FROM '.DB_PREFIX."store_delivery_timeslot WHERE 
            store_id = '".(int) $store_id."'");

        $this->db->query('DELETE FROM '.DB_PREFIX."store_pickup_timeslot WHERE store_id = '".(int) $store_id."'");
        $this->cache->delete('store');

        $this->trigger->fire('post.admin.store.delete', $store_id);
    }

    public function getTotalOrdersByStoreId($store_id)
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `'.DB_PREFIX."order` WHERE store_id = '".(int) $store_id."'");

        return $query->row['total'];
    }

    public function addStore($data)
    {
        //echo'<pre>';print_r($data);exit;
        $this->trigger->fire('pre.admin.store.add', $data);

        $vendor_id = $data['vendor_id'];

        /*$resp = $this->getZipcode($data['latitude'],$data['longitude']);

        $storeZipcode = null;
        if($resp['status']) {
            $storeZipcode = $resp['zipcode'];
        }*/
        if (count($data['city_zipcodes']) > 0) {
            $data['zipcode'] = implode(',', $data['city_zipcodes']);
        } else {
            $data['zipcode'] = '';
        }

        //$query = "INSERT INTO " . DB_PREFIX . "store SET city_id='".$data['city_id']."', fax='".$data['fax']."', telephone='".$data['telephone']."', email='".$data['email']."', latitude='" . $this->db->escape($data['latitude']) . "', longitude='" . $this->db->escape($data['longitude']) . "',zipcode = '" . $this->db->escape($data['zipcode'])."',name = '" . $this->db->escape($data['name']). "', store_zipcode='" . $data['store_zipcode']  . "', vendor_id='" . $vendor_id . "', address='" . $data['address'] . "', pickup_notes='" . $data['pickup_notes'] . "', about_us='" . $data['about_us'] . "', store_type_ids='" . implode(',', $data['store_type_ids']) . "', logo='" . $data['logo'] . "', banner_logo='" . $data['banner_logo'] . "', banner_logo_status='" . $data['banner_logo_status'] . "', big_logo='" . $data['big_logo'] . "',commission_type='" . $data['commission_type'] . "', delivery_date_time_status='" . $data['delivery_date_time_status'] . "', delivery_time_diff='" . $data['delivery_time_diff'] . "',status='" . $data['status'] . "',serviceable_radius='" . $data['serviceable_radius'] . "',min_order_amount='" . $data['min_order_amount'] . "',tax='" . $data['tax'] . "',min_order_cod ='" . $data['min_order_cod']. "',fixed_commision='" . $data['fixed_commision']  . "',commision='" . $data['commision'] . "',store_pickup_timeslots='" . $data['pickup_delivery'] . "',delivery_by_owner='" . $data['delivery_by_owner'] ."',cost_of_delivery='" . $data['cost_of_delivery'] ."',date_added='" . date('Y-m-d') . "'";
        $query = 'INSERT INTO '.DB_PREFIX."store SET city_id='".$data['city_id']."', fax='".$data['fax']."', telephone='".$data['telephone']."', email='".$data['email']."', latitude='".$this->db->escape($data['latitude'])."', longitude='".$this->db->escape($data['longitude'])."',zipcode = '".$this->db->escape($data['zipcode'])."',name = '".$this->db->escape($data['name'])."', store_zipcode='".$data['store_zipcode']."', vendor_id='".$vendor_id."', address='".$data['address']."', pickup_notes='".$data['pickup_notes']."', about_us='".$data['about_us']."', store_type_ids='".$data['store_type_ids']."', logo='".$data['logo']."', banner_logo='".$data['banner_logo']."', banner_logo_status='".$data['banner_logo_status']."', big_logo='".$data['big_logo']."',commission_type='".$data['commission_type']."', delivery_date_time_status='".$data['delivery_date_time_status']."', delivery_time_diff='".$data['delivery_time_diff']."',status='".$data['status']."',serviceable_radius='".$data['serviceable_radius']."',min_order_amount='".$data['min_order_amount']."',tax='".$data['tax']."',min_order_cod ='".$data['min_order_cod']."',fixed_commision='".$data['fixed_commision']."',commision='".$data['commision']."',store_pickup_timeslots='".$data['pickup_delivery']."',delivery_by_owner='".$data['delivery_by_owner']."',cost_of_delivery='".$data['cost_of_delivery']."',date_added='".date('Y-m-d')."'";
        $this->db->query($query);

        $store_id = $this->db->getLastId();
        $storeCategories = explode(',', $data['store_categories']);
        //ADD CATEGORIES RELATED TO STORE
        foreach ($storeCategories as $cat_id) {
            $sqlTemp = 'SELECT count(*) as total FROM '.DB_PREFIX."category_to_store where category_id = '".(int) $cat_id."' and store_id = '".$store_id."'";

            //echo "<pre>";print_r($sqlTemp);die;
            if (0 == $this->db->query($sqlTemp)->row['total']) {
                $query = 'INSERT INTO '.DB_PREFIX.'category_to_store value ('.$cat_id.','.(int) $store_id.',0)';
                $this->db->query($query);
            }

            $this->saveSubCategoriesToStore($cat_id, $store_id);
        }

        // Layout Route
        /*$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "layout_path WHERE store_id = '0'");

        foreach ($query->rows as $layout_path) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "layout_path SET layout_id = '" . (int) $layout_path['layout_id'] . "', path = '" . $this->db->escape($layout_path['path']) . "', store_id = '" . (int) $store_id . "'");
        }*/

        //add zipcodes
        $zipcodes = explode(',', $data['store_zipcode']);
        foreach ($zipcodes as $zipcode) {
            $this->db->query('INSERT INTO `'.DB_PREFIX.'store_zipcodes` set store_id="'.$store_id.'", zipcode="'.$zipcode.'"');
        }

        //add zipcodes
        foreach ($data['city_zipcodes'] as $zipcode) {
            $this->db->query('INSERT INTO `'.DB_PREFIX.'store_zipcodes` set store_id="'.$store_id.'", zipcode="'.$zipcode.'"');
        }

        $this->cache->delete('store');

        //timeslots
        $i = 0; //foreach day
        foreach ($data['pickup_timeslots'] as $arr) {
            //foreach timeslot
            foreach ($arr as $key => $value) {
                $this->db->query('INSERT INTO '.DB_PREFIX.'store_pickup_timeslot SET store_id = "'.$store_id.'", day="'.$i.'", timeslot="'.$key.'", status="'.$value.'"');
            }
            ++$i;
        }

        //timeslots
        $i = 0; //foreach day

        if (isset($data['delivery_timeslots'])) {
            foreach ($data['delivery_timeslots'] as $arr) {
                //foreach timeslot
                foreach ($arr as $key => $value) {
                    $this->db->query('INSERT INTO '.DB_PREFIX.'store_delivery_timeslot SET store_id = "'.$store_id.'", day="'.$i.'", timeslot="'.$key.'", status="'.$value.'"');
                }
                ++$i;
            }
        } else {
            // if no delivery_timeslots set add 8am - 8pm timslot

            $loop[] = '08:00am - 10:00am';
            $loop[] = '09:00am - 11:00am';

            $loop[] = '10:00am - 12:00am';
            $loop[] = '11:00am - 01:00pm';

            $loop[] = '12:00pm - 02:00pm';

            $loop[] = '01:00pm - 03:00pm';
            $loop[] = '02:00pm - 04:00pm';
            $loop[] = '03:00pm - 05:00pm';
            $loop[] = '04:00pm - 06:00pm';
            $loop[] = '05:00pm - 07:00pm';

            $loop[] = '06:00pm - 08:00pm';

            foreach ($loop as $lp) {
                //$key = '08:00am - 08:00pm';
                $key = $lp;

                $i = 0; //foreach day

                //echo "<pre>";print_r($lp);die;
                for ($k = 0; $k <= 6; ++$k) {
                    $value = 1;

                    $this->db->query('INSERT INTO '.DB_PREFIX.'store_delivery_timeslot SET store_id = "'.$store_id.'", day="'.$i.'", timeslot="'.$key.'", status="'.$value.'"');
                    ++$i;
                }
            }
        }

        //openhour start
        $this->db->query('DELETE FROM '.DB_PREFIX.'store_open_hours WHERE store_id="'.$store_id.'"');

        $i = 0; //foreach day

        for ($k = 0; $k <= 6; ++$k) {
            $key = '08:00am - 08:00pm';
            $value = 1;
            $this->db->query('INSERT INTO '.DB_PREFIX.'store_open_hours SET store_id = "'.$store_id.'", day="'.$i.'", timeslot="'.$key.'", status="'.$value.'"');

            ++$i;
        }

        //openhour end

        $alias = empty($data['seo_url']) ? $data['name'] : $data['seo_url'];

        //$alias = $this->model_catalog_url_alias->generateAlias($alias);

        if ($alias) {
            $this->db->query('INSERT INTO '.DB_PREFIX."url_alias SET query = 'store_id=".(int) $store_id."', keyword = '".$this->db->escape($alias)."' ,language_id = '1' ");
        }

        //category store commission

        //echo "<pre>";print_r($data['category_commission']);die;
        $this->db->query('DELETE FROM '.DB_PREFIX.'store_category_commission WHERE store_id='.(int) $store_id);

        if (isset($data['category_commission'])) {
            foreach ($data['category_commission'] as $arr) {
                $this->db->query('INSERT INTO '.DB_PREFIX.'store_category_commission SET store_id = "'.$store_id.'", commission="'.$arr['commission'].'", fixed_commission="'.$arr['fixed_commission'].'", category_id="'.$arr['category_id'].'"');
            }
        }

        $this->trigger->fire('post.admin.store.add', $store_id);

        return $store_id;
    }

    public function saveSubCategoriesToStore($top_cat_id, $store_id)
    {
        $sql = 'SELECT * FROM '.DB_PREFIX."category c where c.parent_id = '".(int) $top_cat_id."' and c.status = '1'";

        $level1 = $this->db->query($sql)->rows;
        foreach ($level1 as $level_cat_id) {
            $sqlTemp = 'SELECT count(*) as total FROM '.DB_PREFIX."category_to_store where category_id = '".(int) $level_cat_id['category_id']."' and store_id = '".$store_id."'";

            if (0 == $this->db->query($sqlTemp)->row['total']) {
                $query = 'INSERT INTO '.DB_PREFIX.'category_to_store value ('.$level_cat_id['category_id'].','.(int) $store_id.',0)';

                $this->db->query($query);

                $this->saveSubCategoriesToStore($level_cat_id['category_id'], $store_id);
            }
        }
    }
}
