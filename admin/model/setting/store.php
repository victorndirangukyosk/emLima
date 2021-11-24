<?php

class ModelSettingStore extends Model
{
    public function getZipList($store_id)
    {
        $result = [];
        $rows = $this->db->query('select zipcode from '.DB_PREFIX.'store_zipcodes where store_id="'.$store_id.'"')->rows;
        foreach ($rows as $row) {
            $result[] = $row['zipcode'];
        }

        return $result;
        //return implode(',', $result);
    }

    public function getStoreCategoryIds($store_id)
    {
        $result = [];
        $categories = $this->db->query('select category_id from `'.DB_PREFIX.'category_to_store` where store_id='.$store_id)->rows;
        foreach ($categories as $row) {
            $result[] = $row['category_id'];
        }

        return $result;
    }

    public function addStore($data)
    {
        $this->trigger->fire('pre.admin.store.add', $data);

        if ($this->user->isVendor()) {
            $vendor_id = $this->user->getId();
        } else {
            $vendor_id = $data['vendor_id'];
        }

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

        $query = 'INSERT INTO '.DB_PREFIX."store SET city_id='".$data['city_id']."', fax='".$data['fax']."', telephone='".$data['telephone']."', email='".$data['email']."', order_notification_emails='".$data['order_notification_emails']."', latitude='".$this->db->escape($data['latitude'])."', longitude='".$this->db->escape($data['longitude'])."',zipcode = '".$this->db->escape($data['zipcode'])."',name = '".$this->db->escape($data['name'])."', store_zipcode='".$data['store_zipcode']."', vendor_id='".$vendor_id."', address='".$data['address']."', pickup_notes='".$data['pickup_notes']."', about_us='".$data['about_us']."', store_type_ids='".implode(',', $data['store_type_ids'])."', logo='".$data['logo']."', banner_logo='".$data['banner_logo']."', banner_logo_status='".$data['banner_logo_status']."', big_logo='".$data['big_logo']."',commission_type='".$data['commission_type']."', delivery_date_time_status='".$data['delivery_date_time_status']."', delivery_time_diff='".$data['delivery_time_diff']."',status='".$data['status']."',serviceable_radius='".$data['serviceable_radius']."',min_order_amount='".$data['min_order_amount']."',tax='".$data['tax']."',min_order_cod ='".$data['min_order_cod']."',fixed_commision='".$data['fixed_commision']."',commision='".$data['commision']."',store_pickup_timeslots='".$data['pickup_delivery']."',delivery_by_owner='".$data['delivery_by_owner']."',cost_of_delivery='".$data['cost_of_delivery']."',date_added='".date('Y-m-d')."'";

        $this->db->query($query);

        $store_id = $this->db->getLastId();

        //ADD CATEGORIES RELATED TO STORE
        foreach ($data['storeCategories'] as $cat_id) {
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

        /*//add zipcodes
        $zipcodes = explode(',', $data['zipcode']);
        foreach ($zipcodes as $zipcode) {
            $this->db->query('INSERT INTO `' . DB_PREFIX . 'store_zipcodes` set store_id="' . $store_id . '", zipcode="' . $zipcode . '"');
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

        $alias = $this->model_catalog_url_alias->generateAlias($alias);

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

    public function addDuplicateStore($data)
    {
        $this->trigger->fire('pre.admin.store.add', $data);

        if ($this->user->isVendor()) {
            $vendor_id = $this->user->getId();
        } else {
            $vendor_id = $data['vendor_id'];
        }

        /*$resp = $this->getZipcode($data['latitude'],$data['longitude']);

        $storeZipcode = null;
        if($resp['status']) {
            $storeZipcode = $resp['zipcode'];
        }*/
        if (count($data['city_zipcodes']) > 0) {
            $data['zipcode'] = implode(',', $data['city_zipcodes']);
        }

        $query = 'INSERT INTO '.DB_PREFIX."store SET city_id='".$data['city_id']."', fax='".$data['fax']."', telephone='".$data['telephone']."', email='".$data['email']."', latitude='".$this->db->escape($data['latitude'])."', longitude='".$this->db->escape($data['longitude'])."',zipcode = '".$this->db->escape($data['zipcode'])."',name = '".$this->db->escape($data['name'])."', store_zipcode='".$data['store_zipcode']."', vendor_id='".$vendor_id."', address='".$data['address']."', pickup_notes='".$data['pickup_notes']."', about_us='".$data['about_us']."', store_type_ids='".implode(',', $data['store_type_ids'])."', logo='".$data['logo']."', delivery_date_time_status='".$data['delivery_date_time_status']."', delivery_time_diff='".$data['delivery_time_diff']."',status='".$data['status']."',serviceable_radius='".$data['serviceable_radius']."',min_order_amount='".$data['min_order_amount']."',min_order_cod ='".$data['min_order_cod']."',commission_type='".$data['commission_type']."',fixed_commision='".$data['fixed_commision']."',commision='".$data['commision']."',store_pickup_timeslots='".$data['store_pickup_timeslots']."',delivery_by_owner='".$data['delivery_by_owner']."',cost_of_delivery='".$data['cost_of_delivery']."',date_added='".date('Y-m-d')."'";

        $this->db->query($query);

        $store_id = $this->db->getLastId();

        //ADD CATEGORIES RELATED TO STORE
        foreach ($data['storeCategories'] as $cat_id) {
            $sqlTemp = 'SELECT count(*) as total FROM '.DB_PREFIX."category_to_store where category_id = '".(int) $cat_id."' and store_id = '".$store_id."'";

            //echo "<pre>";print_r($sqlTemp);die;
            if (0 == $this->db->query($sqlTemp)->row['total']) {
                $query = 'INSERT INTO '.DB_PREFIX.'category_to_store value ('.$cat_id.','.(int) $store_id.',0)';
                $this->db->query($query);
            }

            $this->saveSubCategoriesToStore($cat_id, $store_id);
        }

        //add zipcodes
        foreach ($data['city_zipcodes'] as $zipcode) {
            $this->db->query('INSERT INTO `'.DB_PREFIX.'store_zipcodes` set store_id="'.$store_id.'", zipcode="'.$zipcode.'"');
        }

        $this->cache->delete('store');

        foreach ($data['pickup_timeslots'] as $arr) {
            $this->db->query('INSERT INTO '.DB_PREFIX.'store_pickup_timeslot SET store_id = "'.$store_id.'", day="'.$arr['day'].'", timeslot="'.$arr['timeslot'].'", status="'.$arr['status'].'"');
        }

        //timeslots
        $i = 0; //foreach day
        foreach ($data['delivery_timeslots'] as $arr) {
            $this->db->query('INSERT INTO '.DB_PREFIX.'store_delivery_timeslot SET store_id = "'.$store_id.'", day="'.$arr['day'].'", timeslot="'.$arr['timeslot'].'", status="'.$arr['status'].'"');

            ++$i;
        }

        foreach ($data['openHours'] as $arr) {
            $this->db->query('INSERT INTO '.DB_PREFIX.'store_open_hours SET store_id = "'.$store_id.'", day="'.$arr['day'].'", timeslot="'.$arr['timeslot'].'", status="'.$arr['status'].'"');
        }

        $alias = empty($data['seo_url']) ? $data['name'] : $data['seo_url'];

        $alias = $this->model_catalog_url_alias->generateAlias($alias);

        if ($alias) {
            $this->db->query('INSERT INTO '.DB_PREFIX."url_alias SET query = 'store_id=".(int) $store_id."', keyword = '".$this->db->escape($alias)."' ,language_id = '1' ");
        }

        //$this->addProductToStoreDuplicate($data['productToStore'],$store_id);

        return $store_id;
    }

    public function addProductToStoreDuplicate($datas, $store_id)
    {
        foreach ($datas as $data) {
            $this->db->query('INSERT INTO '.DB_PREFIX."product_to_store SET  product_id = '".$data['product_id']."', store_id = '".$this->db->escape($store_id)."', price = '".$data['price']."',special_price = '".$data['special_price']."',tax_percentage = '".$data['tax_percentage']."',quantity = '".$data['quantity']."',min_quantity = '".$data['min_quantity']."',subtract_quantity = '".$data['subtract_quantity']."',status = '".$data['status']."',tax_class_id = '".$data['tax_class_id']."'");
        }
    }

    public function editStore($store_id, $data)
    {
        $this->trigger->fire('pre.admin.store.edit', $data);

        if ($this->user->isVendor()) {
            $vendor_id = $this->user->getId();
        } else {
            $vendor_id = $data['vendor_id'];
        }
        $data['zipcode'] = '';
        if (count($data['city_zipcodes']) > 0) {
            $data['zipcode'] = implode(',', $data['city_zipcodes']);
        }

        /*$resp = $this->getZipcode($data['latitude'],$data['longitude']);

        $storeZipcode = null;
        if($resp['status']) {
            $storeZipcode = $resp['zipcode'];
        }*/

        $query = 'UPDATE '.DB_PREFIX."store SET city_id='".$data['city_id']."', fax='".$data['fax']."', telephone='".$data['telephone']."', email='".$data['email']."', order_notification_emails='".$data['order_notification_emails']."', latitude='".$this->db->escape($data['latitude'])."', longitude='".$this->db->escape($data['longitude'])."',`zipcode` = '".$this->db->escape($data['zipcode'])."', name = '".$this->db->escape($data['name'])."', store_zipcode='".$data['store_zipcode']."', vendor_id='".$vendor_id."', store_type_ids='".implode(',', $data['store_type_ids'])."', address='".$data['address']."', pickup_notes='".$data['pickup_notes']."', about_us='".$data['about_us']."', big_logo='".$data['big_logo']."', banner_logo='".$data['banner_logo']."', banner_logo_status='".$data['banner_logo_status']."', logo='".$data['logo']."', delivery_time_diff='".$data['delivery_time_diff']."', status='".$data['status']."',min_order_amount='".$data['min_order_amount']."',serviceable_radius='".$data['serviceable_radius']."',min_order_cod ='".$data['min_order_cod']."',tax='".$data['tax']."',commission_type='".$data['commission_type']."',fixed_commision='".$data['fixed_commision']."',commision='".$data['commision']."',store_pickup_timeslots='".$data['pickup_delivery']."',delivery_by_owner='".$data['delivery_by_owner']."',cost_of_delivery='".$data['cost_of_delivery']."' WHERE store_id = '".(int) $store_id."'";

        $this->db->query($query);

        //DELATE ALL CATEGORIES RELATED TO STORE
        $query = 'DELETE FROM '.DB_PREFIX.'category_to_store where store_id='.(int) $store_id;
        $this->db->query($query);

        //ADD CATEGORIES RELATED TO STORE

        //echo "<pre>";print_r($data['storeCategories']);die;
        foreach ($data['storeCategories'] as $cat_id) {
            $sqlTemp = 'SELECT count(*) as total FROM '.DB_PREFIX."category_to_store where category_id = '".(int) $cat_id."' and store_id = '".$store_id."'";

            //echo "<pre>";print_r($sqlTemp);die;
            if (0 == $this->db->query($sqlTemp)->row['total']) {
                $query = 'INSERT INTO '.DB_PREFIX.'category_to_store value ('.$cat_id.','.(int) $store_id.',0)';
                $this->db->query($query);
            }

            $this->saveSubCategoriesToStore($cat_id, $store_id);
        }

        //remove old zipcode list
        $this->db->query('delete from `'.DB_PREFIX.'store_zipcodes` where store_id="'.$store_id.'"');
        $this->cache->delete('store');

        //add zipcodes
        //$zipcodes = explode(',', $data['zipcode']);
        foreach ($data['city_zipcodes'] as $zipcode) {
            $this->db->query('INSERT INTO `'.DB_PREFIX.'store_zipcodes` set store_id="'.$store_id.'", zipcode="'.$zipcode.'"');
        }

        //openhour start
        $this->db->query('DELETE FROM '.DB_PREFIX.'store_open_hours WHERE store_id="'.$store_id.'"');
        $i = 0; //foreach day
        foreach ($data['open_hours'] as $arr) {
            //foreach timeslot
            foreach ($arr as $key => $value) {
                $this->db->query('INSERT INTO '.DB_PREFIX.'store_open_hours SET store_id = "'.$store_id.'", day="'.$i.'", timeslot="'.$key.'", status="'.$value.'"');
            }
            ++$i;
        }

        //openhour end

        //timeslots
        $this->db->query('DELETE FROM '.DB_PREFIX.'store_pickup_timeslot WHERE store_id="'.$store_id.'"');
        $this->db->query('DELETE FROM '.DB_PREFIX.'store_delivery_timeslot WHERE store_id="'.$store_id.'"');

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
        foreach ($data['delivery_timeslots'] as $arr) {
            //foreach timeslot
            foreach ($arr as $key => $value) {
                $this->db->query('INSERT INTO '.DB_PREFIX.'store_delivery_timeslot SET store_id = "'.$store_id.'", day="'.$i.'", timeslot="'.$key.'", status="'.$value.'"');
            }
            ++$i;
        }

        $this->db->query('DELETE FROM '.DB_PREFIX."url_alias WHERE query = 'store_id=".(int) $store_id."'");

        $alias = empty($data['seo_url']) ? $data['name'] : $data['seo_url'];

        $alias = $this->model_catalog_url_alias->generateAlias($alias);

        if ($alias) {
            $this->db->query('INSERT INTO '.DB_PREFIX."url_alias SET query = 'store_id=".(int) $store_id."', keyword = '".$this->db->escape($alias)."',language_id = '1'");
        }

        //category store commission

        //echo "<pre>";print_r($data['category_commission']);die;
        $this->db->query('DELETE FROM '.DB_PREFIX.'store_category_commission WHERE store_id='.(int) $store_id);

        if (isset($data['category_commission'])) {
            foreach ($data['category_commission'] as $arr) {
                $this->db->query('INSERT INTO '.DB_PREFIX.'store_category_commission SET store_id = "'.$store_id.'", commission="'.$arr['commission'].'", fixed_commission="'.$arr['fixed_commission'].'", category_id="'.$arr['category_id'].'"');
            }
        }

        $this->trigger->fire('post.admin.store.edit', $store_id);
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

    public function duplicateStore($store_id)
    {
        $this->trigger->fire('pre.admin.store.delete', $store_id);

        die;

        $this->db->query('DELETE FROM '.DB_PREFIX."information_to_store WHERE store_id = '".(int) $store_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."manufacturer_to_store WHERE store_id = '".(int) $store_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."menu_child_to_store WHERE store_id = '".(int) $store_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."menu_to_store WHERE store_id = '".(int) $store_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."simple_blog_article_to_store WHERE store_id = '".(int) $store_id."'");
        $this->db->query('DELETE FROM '.DB_PREFIX."simple_blog_category_to_store WHERE store_id = '".(int) $store_id."'");

        $this->trigger->fire('post.admin.store.delete', $store_id);
    }

    public function addManufacturerForDuplicate($data)
    {
        $this->trigger->fire('pre.admin.manufacturer.add', $data);

        $this->db->query('INSERT INTO '.DB_PREFIX."manufacturer SET sort_order = '".(int) $data['sort_order']."', status = '".(int) $data['status']."', date_modified = NOW(), date_added = NOW()");

        $manufacturer_id = $this->db->getLastId();

        if (isset($data['image'])) {
            $this->db->query('UPDATE '.DB_PREFIX."manufacturer SET image = '".$this->db->escape($data['image'])."' WHERE manufacturer_id = '".(int) $manufacturer_id."'");
        }

        foreach ($data['manufacturer_description'] as $language_id => $value) {
            $value['meta_title'] = empty($value['meta_title']) ? $value['name'] : $value['meta_title'];

            $this->db->query('INSERT INTO '.DB_PREFIX."manufacturer_description SET manufacturer_id = '".(int) $manufacturer_id."', language_id = '".(int) $language_id."', name = '".$this->db->escape($value['name'])."', description = '".$this->db->escape($value['description'])."', meta_title = '".$this->db->escape($value['meta_title'])."', meta_description = '".$this->db->escape($value['meta_description'])."', meta_keyword = '".$this->db->escape($value['meta_keyword'])."'");
        }

        if (isset($data['manufacturer_store'])) {
            foreach ($data['manufacturer_store'] as $store_id) {
                $this->db->query('INSERT INTO '.DB_PREFIX."manufacturer_to_store SET manufacturer_id = '".(int) $manufacturer_id."', store_id = '".(int) $store_id."'");
            }
        }

        // Set which layout to use with this manufacturer
        if (isset($data['manufacturer_layout'])) {
            foreach ($data['manufacturer_layout'] as $store_id => $layout_id) {
                $this->db->query('INSERT INTO '.DB_PREFIX."manufacturer_to_layout SET manufacturer_id = '".(int) $manufacturer_id."', store_id = '".(int) $store_id."', layout_id = '".(int) $layout_id."'");
            }
        }

        foreach ($data['seo_url'] as $language_id => $value) {
            $alias = empty($value) ? $data['manufacturer_description'][$language_id]['name'] : $value;

            $alias = $this->model_catalog_url_alias->generateAlias($alias);

            if ($alias) {
                $this->db->query('INSERT INTO '.DB_PREFIX."url_alias SET query = 'manufacturer_id=".(int) $manufacturer_id."', keyword = '".$this->db->escape($alias)."', language_id = '".$language_id."'");
            }
        }

        $this->cache->delete('manufacturer');

        $this->trigger->fire('post.admin.manufacturer.add', $manufacturer_id);

        return $manufacturer_id;
    }

    public function getStore($store_id)
    {
        $query = $this->db->query('SELECT DISTINCT * FROM '.DB_PREFIX."store WHERE store_id = '".(int) $store_id."'");

        $store = $query->row;

        //echo "<pre>";print_r("SELECT DISTINCT * FROM " . DB_PREFIX . "store WHERE store_id = '" . (int) $store_id . "'");die;

        if (count($store) > 0) {
            $store['seo_url'] = '';

            $rows = $this->db->query('SELECT keyword, language_id FROM '.DB_PREFIX."url_alias WHERE query = 'store_id=".(int) $store_id."'")->row;

            if ($rows) {
                $store['seo_url'] = $rows['keyword'];
            }
        }

        return $store;
    }

    public function getStores($data = [])
    {
        $sql = 'SELECT s.*, c.name as city FROM '.DB_PREFIX.'store s ';
        $sql .= 'LEFT JOIN `'.DB_PREFIX.'city` c on c.city_id = s.city_id ';

        if (!empty($data['filter_vendor'])) {
            $sql .= ' inner join `'.DB_PREFIX.'user` u on u.user_id = s.vendor_id';
        }

        $implode = [];

        if ($this->user->isVendor()) {
            $implode[] = "s.vendor_id = '".$this->user->getId()."'";
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

        if ($this->user->isVendor()) {
            $implode[] = "s.vendor_id = '".$this->user->getId()."'";
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

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalStoresByLayoutId($layout_id)
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX."setting WHERE `key` = 'config_layout_id' AND `value` = '".(int) $layout_id."' AND store_id != '0'");

        return $query->row['total'];
    }

    public function getTotalStoresByLanguage($language)
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX."setting WHERE `key` = 'config_language' AND `value` = '".$this->db->escape($language)."' AND store_id != '0'");

        return $query->row['total'];
    }

    public function getTotalStoresByCurrency($currency)
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX."setting WHERE `key` = 'config_currency' AND `value` = '".$this->db->escape($currency)."' AND store_id != '0'");

        return $query->row['total'];
    }

    public function getTotalStoresByCountryId($country_id)
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX."setting WHERE `key` = 'config_country_id' AND `value` = '".(int) $country_id."' AND store_id != '0'");

        return $query->row['total'];
    }

    public function getTotalStoresByZoneId($zone_id)
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX."setting WHERE `key` = 'config_zone_id' AND `value` = '".(int) $zone_id."' AND store_id != '0'");

        return $query->row['total'];
    }

    public function getTotalStoresByCustomerGroupId($customer_group_id)
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX."setting WHERE `key` = 'config_customer_group_id' AND `value` = '".(int) $customer_group_id."' AND store_id != '0'");

        return $query->row['total'];
    }

    public function getTotalStoresByInformationId($information_id)
    {
        $account_query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX."setting WHERE `key` = 'config_account_id' AND `value` = '".(int) $information_id."' AND store_id != '0'");

        $checkout_query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX."setting WHERE `key` = 'config_checkout_id' AND `value` = '".(int) $information_id."' AND store_id != '0'");

        return $account_query->row['total'] + $checkout_query->row['total'];
    }

    public function getTotalStoresByOrderStatusId($order_status_id)
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX."setting WHERE `key` = 'config_order_status_id' AND `value` = '".(int) $order_status_id."' AND store_id != '0'");

        return $query->row['total'];
    }

    public function getPickupTimeslots($store_id)
    {
        return $this->db->query('select * from '.DB_PREFIX.'store_pickup_timeslot WHERE store_id="'.$store_id.'" GROUP BY timeslot')->rows;
    }

    public function getDeliveryTimeslots($store_id)
    {
        return $this->db->query('select * from '.DB_PREFIX.'store_delivery_timeslot WHERE store_id="'.$store_id.'" GROUP BY timeslot')->rows;
    }

    public function getOpenHours($store_id)
    {
        return $this->db->query('select * from '.DB_PREFIX.'store_open_hours WHERE store_id="'.$store_id.'" GROUP BY timeslot')->rows;
    }

    public function getOpenHoursOfStore($store_id)
    {
        return $this->db->query('select * from '.DB_PREFIX.'store_open_hours WHERE store_id="'.$store_id.'"')->rows;
    }

    public function getPickupTimeslotsForDuplicate($store_id)
    {
        return $this->db->query('select timeslot,status,day from '.DB_PREFIX.'store_pickup_timeslot WHERE store_id="'.$store_id.'"')->rows;
    }

    public function getDeliveryTimeslotsForDuplicate($store_id)
    {
        return $this->db->query('select timeslot,status,day from '.DB_PREFIX.'store_delivery_timeslot WHERE store_id="'.$store_id.'"')->rows;
    }

    public function getProductToStoreForDuplicate($store_id)
    {
        return $this->db->query('select * from '.DB_PREFIX.'product_to_store WHERE store_id="'.$store_id.'"')->rows;
    }

    public function getInformationToStoreForDuplicate($store_id)
    {
        return $this->db->query('select * from '.DB_PREFIX.'information_to_store WHERE store_id="'.$store_id.'"')->rows;
    }

    public function getDeliveryStatus($timeslot, $day, $store_id)
    {
        $row = $this->db->query('select * from '.DB_PREFIX.'store_delivery_timeslot WHERE timeslot="'.$timeslot.'" AND day="'.$day.'" AND store_id="'.$store_id.'"')->row;

        if ($row) {
            return $row['status'];
        }
    }

    public function getOpenHoursStatus($timeslot, $day, $store_id)
    {
        $row = $this->db->query('select * from '.DB_PREFIX.'store_open_hours WHERE timeslot="'.$timeslot.'" AND day="'.$day.'" AND store_id="'.$store_id.'"')->row;

        if ($row) {
            return $row['status'];
        }
    }

    public function getPickupStatus($timeslot, $day, $store_id)
    {
        $row = $this->db->query('select * from '.DB_PREFIX.'store_pickup_timeslot WHERE timeslot="'.$timeslot.'" AND day="'.$day.'" AND store_id="'.$store_id.'"')->row;

        if ($row) {
            return $row['status'];
        }
    }

    public function getStoreIds($vendor_id)
    {
        return $this->db->query('select store_id from `'.DB_PREFIX.'store` WHERE vendor_id="'.$vendor_id.'"')->rows;
    }

    public function getZipcode($lat, $long)
    {
        $address = $lat.','.$long;

        //$address = '-25.4422846,-49.2805478';

        $res['status'] = false;

        $url = 'https://maps.google.com/maps/api/geocode/json?latlng='.$address;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response);

        /*[0] => stdClass Object
                                (
                                    [long_name] => 200-292
                                    [short_name] => 200-292
                                    [types] => Array
                                        (
                                            [0] => street_number
                                        )

                                )*/

        // [7] => stdClass Object
        // (
        //     [long_name] => 86020-430
        //     [short_name] => 86020-430
        //     [types] => Array
        //         (
        //             [0] => postal_code
        //         )

        // )
        //echo "<pre>";print_r($response_a);die;
        if (isset($response_a->status) && 'OK' == $response_a->status) {
            $res['status'] = true;

            /*foreach ($response_a->results[0]->address_components as $address) {

                # code...
                $address
            }*/
            $res['zipcode'] = $response_a->results[0]->address_components[8]->long_name;

            return $res;
        }

        return $res;
    }

    public function getVendorDetails($vendor_id)
    {
        $sql = 'select *, CONCAT(firstname," ",lastname) as name from `'.DB_PREFIX.'user`';
        $sql .= ' WHERE user_id="'.$vendor_id.'" AND user_group_id =11 LIMIT 1';

        $p = $this->db->query($sql);

        //echo "<pre>";print_r($p);die;
        if ($p->num_rows > 0) {
            return $p->row['name'];
        } else {
            return '';
        }
    }

    public function saveSubCategoriesToStore($top_cat_id, $store_id)
    {
        $sql = 'SELECT * FROM '.DB_PREFIX."category c where c.parent_id = '".(int) $top_cat_id."' and c.status = '1'";

        $level1 = $this->db->query($sql)->rows;
//
        //      echo "<pre>";print_r($level1);die;
        foreach ($level1 as $level_cat_id) {
            $sqlTemp = 'SELECT count(*) as total FROM '.DB_PREFIX."category_to_store where category_id = '".(int) $level_cat_id['category_id']."' and store_id = '".$store_id."'";

            //echo "<pre>";print_r($sqlTemp);die;
            if (0 == $this->db->query($sqlTemp)->row['total']) {
                $query = 'INSERT INTO '.DB_PREFIX.'category_to_store value ('.$level_cat_id['category_id'].','.(int) $store_id.',0)';

                $this->db->query($query);

                $this->saveSubCategoriesToStore($level_cat_id['category_id'], $store_id);
            }
        }
    }
}
