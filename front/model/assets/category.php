<?php

class ModelAssetsCategory extends Model {

    public function getCategory($category_id) {
        $query = $this->db->query('SELECT DISTINCT * FROM ' . DB_PREFIX . 'category c LEFT JOIN ' . DB_PREFIX . 'category_description cd ON (c.category_id = cd.category_id) LEFT JOIN ' . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.category_id = '" . (int) $category_id . "' AND cd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND c.status = '1'");

        return $query->row;
    }

    public function getCategoryById($store_id, $parent_id, $category_id) {
        $sql = 'SELECT * FROM ' . DB_PREFIX . 'category c LEFT JOIN ' . DB_PREFIX . 'category_description cd ON (c.category_id = cd.category_id) LEFT JOIN ' . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c2s.store_id = '" . $store_id . "' AND c.parent_id = '" . (int) $parent_id . "' AND c.category_id = '" . (int) $category_id . "' AND cd.language_id = '" . (int) $this->config->get('config_language_id') . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)";

        return $this->db->query($sql)->rows;
    }

    public function getCustomerCategoryById($store_id, $parent_id) {
        $sql = 'SELECT *,ctc.category_id  FROM ' . DB_PREFIX . 'category c LEFT JOIN ' . DB_PREFIX . 'category_description cd ON (c.category_id = cd.category_id) JOIN ' . DB_PREFIX . 'category_to_customer ctc ON (c.category_id = ctc.category_id) LEFT JOIN ' . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int) $parent_id . "' AND ctc.customer_id = '" . (int) $this->customer->getId() . "' AND cd.language_id = '" . (int) $this->config->get('config_language_id') . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)";
        return $this->db->query($sql)->rows;
    }

    public function getCategoryByStore($parent_id = 0) {
        $sql = 'SELECT * FROM ' . DB_PREFIX . 'category c LEFT JOIN ' . DB_PREFIX . 'category_description cd ON (c.category_id = cd.category_id) LEFT JOIN ' . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c2s.store_id = '" . $this->session->data['config_store_id'] . "' AND c.parent_id = '" . (int) $parent_id . "' AND cd.language_id = '" . (int) $this->config->get('config_language_id') . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)";

        return $this->db->query($sql)->rows;
    }

    public function getCategoryByStoreId($store_id, $parent_id) {
        $sql = 'SELECT * FROM ' . DB_PREFIX . 'category c LEFT JOIN ' . DB_PREFIX . 'category_description cd ON (c.category_id = cd.category_id) LEFT JOIN ' . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c2s.store_id = '" . $store_id . "' AND c.parent_id = '" . (int) $parent_id . "' AND cd.language_id = '" . (int) $this->config->get('config_language_id') . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)";

        return $this->db->query($sql)->rows;
    }

    public function getFullNoticeData($location) {
        //return $this->db->query('select * from `'.DB_PREFIX.'notice` WHERE status = 1 AND zipcode="'.$zipcode.'"')->row;

        $sql = 'select * from `' . DB_PREFIX . 'notice`';
        $sql .= ' where status = 1';

        $notices = $this->db->query($sql)->rows;

        $userSearch = explode(',', $location);

        $tempNotices = [];

        if (count($userSearch) >= 2) {
            foreach ($notices as $not) {
                //echo "<pre>";print_r($not);die;
                $res = $this->getDistance($userSearch[0], $userSearch[1], $not['latitude'], $not['longitude'], $not['radius']);
                if ($res) {
                    $tempNotices[] = $not;
                }
            }
        }

        return $tempNotices;
    }

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

    public function getCategoriesByStoreId($store_id) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . 'category c LEFT JOIN ' . DB_PREFIX . 'category_description cd ON (c.category_id = cd.category_id) LEFT JOIN ' . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . 0 . "' AND cd.language_id = '" . (int) $this->config->get('config_language_id') . "'  AND c.status = '1' and c2s.store_id=" . $store_id . ' GROUP BY c2s.category_id ORDER BY c.sort_order, LCASE(cd.name) ');

        return $query->rows;
    }

    public function getCategoryByStoreForCron($parent_id = 0, $store_id) {
        $sql = 'SELECT * FROM ' . DB_PREFIX . 'category c LEFT JOIN ' . DB_PREFIX . 'category_description cd ON (c.category_id = cd.category_id) LEFT JOIN ' . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c2s.store_id = '" . $store_id . "' AND c.parent_id = '" . (int) $parent_id . "' AND cd.language_id = '" . (int) $this->config->get('config_language_id') . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)";

        //echo "<pre>";print_r($sql);die;
        return $this->db->query($sql)->rows;
    }

    public function getCategories($parent_id = 0) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . 'category c LEFT JOIN ' . DB_PREFIX . 'category_description cd ON (c.category_id = cd.category_id) LEFT JOIN ' . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int) $parent_id . "' AND cd.language_id = '" . (int) $this->config->get('config_language_id') . "'  AND c.status = '1' GROUP BY c2s.category_id ORDER BY c.sort_order, LCASE(cd.name) ");

        return $query->rows;
    }

    public function getCategoriesNoRelationStore($parent_id = 0) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . 'category c LEFT JOIN ' . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id)  WHERE c.parent_id = '" . (int) $parent_id . "' AND cd.language_id = '" . (int) $this->config->get('config_language_id') . "'  AND c.status = '1' GROUP BY c.category_id ORDER BY c.sort_order, LCASE(cd.name) ");

        return $query->rows;
    }

    public function getStoreTypes() {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "store_type WHERE language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY sort_order");

        return $query->rows;
    }

    public function getCategoryFilters($category_id) {
        $implode = [];

        $query = $this->db->query('SELECT filter_id FROM ' . DB_PREFIX . "category_filter WHERE category_id = '" . (int) $category_id . "'");

        foreach ($query->rows as $result) {
            $implode[] = (int) $result['filter_id'];
        }

        $filter_group_data = [];

        if ($implode) {
            $filter_group_query = $this->db->query('SELECT DISTINCT f.filter_group_id, fgd.name, fg.sort_order FROM ' . DB_PREFIX . 'filter f LEFT JOIN ' . DB_PREFIX . 'filter_group fg ON (f.filter_group_id = fg.filter_group_id) LEFT JOIN ' . DB_PREFIX . 'filter_group_description fgd ON (fg.filter_group_id = fgd.filter_group_id) WHERE f.filter_id IN (' . implode(',', $implode) . ") AND fgd.language_id = '" . (int) $this->config->get('config_language_id') . "' GROUP BY f.filter_group_id ORDER BY fg.sort_order, LCASE(fgd.name)");

            foreach ($filter_group_query->rows as $filter_group) {
                $filter_data = [];

                $filter_query = $this->db->query('SELECT DISTINCT f.filter_id, fd.name FROM ' . DB_PREFIX . 'filter f LEFT JOIN ' . DB_PREFIX . 'filter_description fd ON (f.filter_id = fd.filter_id) WHERE f.filter_id IN (' . implode(',', $implode) . ") AND f.filter_group_id = '" . (int) $filter_group['filter_group_id'] . "' AND fd.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY f.sort_order, LCASE(fd.name)");

                foreach ($filter_query->rows as $filter) {
                    $filter_data[] = [
                        'filter_id' => $filter['filter_id'],
                        'name' => $filter['name'],
                    ];
                }

                if ($filter_data) {
                    $filter_group_data[] = [
                        'filter_group_id' => $filter_group['filter_group_id'],
                        'name' => $filter_group['name'],
                        'filter' => $filter_data,
                    ];
                }
            }
        }

        return $filter_group_data;
    }

    public function getCategoryLayoutId($category_id) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int) $category_id . "' AND store_id = '" . (int) $this->config->get('config_store_id') . "'");

        if ($query->num_rows) {
            return $query->row['layout_id'];
        } else {
            return 0;
        }
    }

    public function getTotalCategoriesByCategoryId($parent_id = 0) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . 'category c LEFT JOIN ' . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int) $parent_id . "' AND c2s.store_id = '" . (int) $this->config->get('config_store_id') . "' AND c.status = '1'");

        return $query->row['total'];
    }

    public function getCities() {
        return $this->db->query('select * from `' . DB_PREFIX . 'city` where status=1 order by sort_order')->rows;
    }

    public function getUser($email) {
        return $this->db->query('select * from `' . DB_PREFIX . 'user` WHERE email = "' . $email . '"')->row;
    }

    public function getUserByUsername($email) {
        return $this->db->query('select * from `' . DB_PREFIX . 'user` WHERE email = "' . $email . '"')->row;
    }

    public function getEnquiries($username) {
        return $this->db->query('select * from `' . DB_PREFIX . 'enquiries` WHERE username = "' . $username . '"');
    }

    public function getStoreData($config_store_id) {
        return $this->db->query('select * from `' . DB_PREFIX . 'store` WHERE store_id = "' . $config_store_id . '"')->row;
    }

    public function getNoticeData($zipcode) {
        return $this->db->query('select notice from `' . DB_PREFIX . 'notice` WHERE status = 1 AND zipcode="' . $zipcode . '"')->rows;
    }

    public function getUserLists() {
        return $this->db->query('select *  from `' . DB_PREFIX . 'wishlist` WHERE customer_id = ' . $this->customer->getId())->rows;
    }

    public function getStoreSlider($store_id) {
        $slider_ids = $this->db->query('select slider_id from `' . DB_PREFIX . 'sliders` WHERE store_id =' . $store_id . ' and status=1')->rows;

        if (count($slider_ids) > 0) {
            $temp = [];
            foreach ($slider_ids as $key => $value) {
                array_push($temp, $value['slider_id']);
            }
            //echo "<pre>";print_r($slider_ids);die;
            return $this->db->query('select image,link  from `' . DB_PREFIX . 'slider_datas` WHERE slider_id in (' . implode(',', $temp) .
                            ')')->rows;
        }

        return [];
    }

}
