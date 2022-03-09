<?php

class ModelSettingStore extends Model {

    public function getStoreByZip($zipcode, $filter_name = '') {
        $sql = 'select * from `' . DB_PREFIX . 'store` s inner join `' . DB_PREFIX . 'store_zipcodes` sz on sz.store_id = s.store_id';
        $sql .= ' where sz.zipcode = "' . $zipcode . '"';
        $sql .= ' and s.status = 1';

        if ($filter_name) {
            //$sql .= ' AND s.name LIKE "%'.$this->db->escape( $filter_name ).'%"';
            $sql .= ' AND s.name LIKE "%' . $filter_name . '%"';
        }

        $sql .= ' group by s.store_id';

        return $this->db->query($sql)->rows;
    }

    public function updateCustomerDeviceId($data) {
        $query = 'UPDATE ' . DB_PREFIX . "customer SET device_id='" . $data['device_id'] . "' WHERE customer_id = '" . (int) $data['customer_id'] . "'";

        $this->db->query($query);
    }

    public function removeDeviceIdAll($data) {
        $query = 'UPDATE ' . DB_PREFIX . "customer SET device_id='' WHERE device_id = '" . $data['device_id'] . "'";

        $this->db->query($query);
    }

    public function getStoreByLatLang($location, $filter_name = '') {
        //echo "<pre>";print_r($filter_name);die;
        $sql = 'select * from `' . DB_PREFIX . 'store`';
        $sql .= ' where status = 1';

        if ($filter_name) {
            $sql .= ' AND name LIKE "%' . $this->db->escape($filter_name) . '%"';
            ////$sql .= ' AND name LIKE "%'.$filter_name .'%"';
        }

        $stores = $this->db->query($sql)->rows;

        $userSearch = explode(',', $location);

        $tempStores = [];

        if (count($userSearch) >= 2) {
            foreach ($stores as $sto) {
                //echo "<pre>";print_r($sto);die;
                $res = $this->getDistance($userSearch[0], $userSearch[1], $sto['latitude'], $sto['longitude'], $sto['serviceable_radius']);
                if ($res) {
                    $tempStores[] = $sto;
                }
            }
        }

        return $tempStores;
    }

    public function getCollectionStoreByZip($zipcode, $collection_id) {
        $sql = 'select * from `' . DB_PREFIX . 'store_groups` where id=' . $collection_id;

        $store_group = $this->db->query($sql)->row;

        if (count($store_group) > 0) {
            //select * from `hf7_store` s inner join `hf7_store_zipcodes` sz on sz.store_id = s.store_id where sz.zipcode = "46577-688" and s.status = 1 and s.store_id in (Array)

            $sql = 'select * from `' . DB_PREFIX . 'store` s inner join `' . DB_PREFIX . 'store_zipcodes` sz on sz.store_id = s.store_id';
            $sql .= ' where sz.zipcode = "' . $zipcode . '"';
            $sql .= ' and s.status = 1';
            $sql .= ' and s.store_id in (' . $store_group['stores'] . ')';

            return $this->db->query($sql)->row;
        }
    }

    public function getCollectionStoreByLatLan($zipcode, $collection_id) {
        $sql = 'select * from `' . DB_PREFIX . 'store_groups` where id=' . $collection_id;

        $store_group = $this->db->query($sql)->row;

        if (count($store_group) > 0) {
            //select * from `hf7_store` s inner join `hf7_store_zipcodes` sz on sz.store_id = s.store_id where sz.zipcode = "46577-688" and s.status = 1 and s.store_id in (Array)

            $sql = 'select * from `' . DB_PREFIX . 'store` s inner join `' . DB_PREFIX . 'store_zipcodes` sz on sz.store_id = s.store_id';
            $sql .= ' where sz.zipcode = "' . $zipcode . '"';
            $sql .= ' and s.status = 1';
            $sql .= ' and s.store_id in (' . $store_group['stores'] . ')';

            return $this->db->query($sql)->row;
        }
    }

    public function getCollectionStoresDetails($collection_id) {
        $sql = 'select * from `' . DB_PREFIX . 'store_groups` where id=' . $collection_id;

        $store_group = $this->db->query($sql)->row;

        if (count($store_group) > 0) {
            //select * from `hf7_store` s inner join `hf7_store_zipcodes` sz on sz.store_id = s.store_id where sz.zipcode = "46577-688" and s.status = 1 and s.store_id in (Array)

            $sql = 'select s.city_id as city_id,c.name as city_name  from `' . DB_PREFIX . 'store` s inner join `' . DB_PREFIX . 'city` c on c.city_id = s.city_id';
            $sql .= ' where s.status = 1';
            $sql .= ' and s.store_id in (' . $store_group['stores'] . ') group by s.city_id';

            $cities = $this->db->query($sql)->rows;

            //echo "<pre>";print_r($cities);die;
            if (count($cities) > 0) {
                $data = [];

                foreach ($cities as $city) {
                    $tempdata['city_name'] = $city['city_name'];

                    // code...
                    $sql = 'select *,c.name as city_name,s.name as store_name from `' . DB_PREFIX . 'store` s inner join `' . DB_PREFIX . 'city` c on c.city_id = s.city_id';
                    $sql .= ' where s.status = 1';
                    $sql .= ' and s.city_id = ' . $city['city_id'];
                    $sql .= ' and s.store_id in (' . $store_group['stores'] . ')';

                    $stores = $this->db->query($sql)->rows;

                    $tempdata['stores'] = $stores;

                    array_push($data, $tempdata);
                }

                return $data;
            }
        }

        return [];
    }

    public function getStores($data = []) {
        $store_data = $this->cache->get('store');

        if (!$store_data) {
            $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . 'store ORDER BY url');

            $store_data = $query->rows;

            $this->cache->set('store', $store_data);
        }

        return $store_data;
    }

    public function getStoreGroup($id) {
        $sql = 'select * from `' . DB_PREFIX . 'store_groups` as s where id=' . $id;
        $sql .= ' and s.status = 1';

        return $this->db->query($sql)->row;
    }

    public function updateDeviceId($data) {
        $query = 'UPDATE ' . DB_PREFIX . "user SET device_id='" . $data['device_id'] . "', push_notification = 1 WHERE user_id = '" . (int) $data['user_id'] . "'";

        $this->db->query($query);
    }

    public function removeDeviceId($data) {
        $query = 'UPDATE ' . DB_PREFIX . "user SET device_id='" . $data['device_id'] . "' , push_notification = 0 WHERE user_id = '" . (int) $data['user_id'] . "'";

        $this->db->query($query);
    }

    public function getDeviceId($data) {
        $query = 'SELECT device_id from  ' . DB_PREFIX . "user  WHERE user_id = '" . (int) $data['user_id'] . "'";

        $this->db->query($query);
    }

    public function getStoreCategories($store_id) {
        $sql = 'select * from ' . DB_PREFIX . 'category_to_store cs';
        $sql .= ' left join ' . DB_PREFIX . 'category_description cd on cd.category_id = cs.category_id';
        /* Added below lines for categories images * */
        $sql .= ' left join ' . DB_PREFIX . 'category c on c.category_id = cd.category_id';
        $sql .= ' where store_id="' . $store_id . '" AND cd.language_id="' . $this->config->get('config_language_id') . '"';

        return $this->db->query($sql)->rows;
    }

    public function getStoreCategoriesbyStoreId($store_id, $category_id) {
        // echo $category_id;exit;
        $sql = 'select * from ' . DB_PREFIX . 'category_to_store cs';
        $sql .= ' left join ' . DB_PREFIX . 'category cd on cd.category_id = cs.category_id';
        $sql .= ' where cs.category_id="' . (int) $category_id . '" AND store_id="' . $store_id . '"';
        $result = $this->db->query($sql)->rows;

        return count($result);
    }

    public function getStoreRatingReviewCount($store_id) {
        //$store_id = 43;

        $res['rating'] = 0;
        $res['review_count'] = 0;

        $query = 'SELECT SUM(rating) as rating from  ' . DB_PREFIX . "order  WHERE store_id = '" . (int) $store_id . "'";

        $query1 = 'SELECT COUNT(*) as review_count from  ' . DB_PREFIX . "order  WHERE store_id = '" . (int) $store_id . "' and rating is NOT Null";

        $total_rating = $this->db->query($query)->row['rating'];
        $review_count = $this->db->query($query1)->row['review_count'];

        if ($review_count) {
            $res['rating'] = $total_rating / $review_count;
            $res['review_count'] = $review_count;
        }

        return $res;
        //echo "<pre>";print_r($review_count);die;
        //echo "<pre>";print_r($res);die;
    }

    public function getCityNameByZip($zipcode) {
        $sql = 'select * from ' . DB_PREFIX . 'city_zipcodes z';
        $sql .= ' left join ' . DB_PREFIX . 'city c on c.city_id = z.city_id';
        $sql .= ' where zipcode="' . $zipcode . '"';

        $res = $this->db->query($sql)->row;

        if (isset($res['name'])) {
            return $res['name'];
        }

        return '';
    }

    /* public function getCityNameByLatLang($location) {
      $sql = 'select * from ' . DB_PREFIX . 'city_zipcodes z';
      $sql .= ' left join ' . DB_PREFIX . 'city c on c.city_id = z.city_id';
      $sql .= ' where zipcode="' . $zipcode .'"';
      return $this->db->query($sql)->row['name'];
      } */

    public function getDistance($latitude1, $longitude1, $latitude2, $longitude2, $storeRadius) {
        $earth_radius = 6371;

        $dLat = deg2rad($latitude2 - $latitude1);
        $dLon = deg2rad($longitude2 - $longitude1);

        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * asin(sqrt($a));
        $d = $earth_radius * $c;

        if ($d < $storeRadius) {
            //echo "Within 100 kilometer radius";
            return true;
        } else {
            //echo "Outside 100 kilometer radius";
            return false;
        }
    }

    public function getStoresAll($data = []) {
        $sql = 'SELECT * FROM ' . DB_PREFIX . 'store';
        if (isset($data['filter_status'])) {
            $sql .= " WHERE status = '" . $data['filter_status'] . "'";
        }

        $sql .= ' ORDER BY store_id';

        $query = $this->db->query($sql);

        $store_data = $query->rows;

        return $store_data;
    }

    public function getSeoUrl($query) {
        $sql = 'SELECT keyword FROM ' . DB_PREFIX . "url_alias WHERE query='" . $query . "'";
        $query = $this->db->query($sql);

        return $query->row['keyword'];
    }

    public function getStore($store_id) {
        $query = $this->db->query('SELECT DISTINCT * FROM ' . DB_PREFIX . "store WHERE store_id = '" . (int) $store_id . "'");

        $store = $query->row;

        //echo "<pre>";print_r("SELECT DISTINCT * FROM " . DB_PREFIX . "store WHERE store_id = '" . (int) $store_id . "'");die;

        if (count($store) > 0) {
            $store['seo_url'] = '';

            $rows = $this->db->query('SELECT keyword, language_id FROM ' . DB_PREFIX . "url_alias WHERE query = 'store_id=" . (int) $store_id . "'")->row;

            if ($rows) {
                $store['seo_url'] = $rows['keyword'];
            }
        }

        return $store;
    }

    public function getStoreInfo($store_id) {
        $this->db->select('store.store_id,store.name,store.min_order_amount,store.city_id,store.commision,store.fixed_commision,user.commision as vendor_commision ,user.fixed_commision as vendor_fixed_commision', false);
        $this->db->join('user', 'user.user_id = store.vendor_id', 'left');
        $this->db->where('store.store_id', $store_id);
        $this->db->where('store.status', 1);
        $store_info = $this->db->get('store')->row;
        return $store_info;
    }

}
