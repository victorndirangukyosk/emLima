<?php

class ModelUserFarmer extends Model {

    public function addFarmer($data) {
        $this->db->query('INSERT INTO `' . DB_PREFIX . "farmer` SET first_name = '" . $this->db->escape($data['first_name']) . "', last_name = '" . $this->db->escape($data['last_name']) . "', email = '" . $this->db->escape($data['email']) . "', mobile = '" . $this->db->escape($data['mobile']) . "', farmer_type = '" . $data['farmer_type'] . "', irrigation_type = '" . $data['irrigation_type'] . "', location = '" . $data['location'] . "', description = '" . $data['description'] . "',  salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', ip = '" . $data['ip'] . "', latitude = '" . $data['latitude'] . "', longitude = '" . (int) $data['longitude'] . "', status = '" . $data['status'] . "', farm_size = '" . $data['farm_size'] . "', farm_size_type = '" . $data['farm_size_type'] . "', created_at = NOW()");

        return $this->db->getLastId();
    }

    public function editUser($user_id, $data) {
        $this->db->query('UPDATE `' . DB_PREFIX . "user` SET username = '" . $this->db->escape($data['username']) . "', user_group_id = '" . (int) $this->config->get('config_farmer_group_id') . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', image = '" . $this->db->escape($data['image']) . "', status = '" . (int) $data['status'] . "' WHERE user_id = '" . (int) $user_id . "'");

        if ($data['password']) {
            $this->db->query('UPDATE `' . DB_PREFIX . "user` SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' WHERE user_id = '" . (int) $user_id . "'");
        }
    }

    public function editPassword($user_id, $password) {
        $this->db->query('UPDATE `' . DB_PREFIX . "user` SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "', code = '' WHERE user_id = '" . (int) $user_id . "'");
    }

    public function editCode($email, $code) {
        $this->db->query('UPDATE `' . DB_PREFIX . "user` SET code = '" . $this->db->escape($code) . "' WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
    }

    public function deleteUser($user_id) {
        $this->db->query('DELETE FROM `' . DB_PREFIX . "user` WHERE user_id = '" . (int) $user_id . "'");
    }

    public function getFarmer($farmer_id) {
        $query = $this->db->query('SELECT *, (SELECT ug.name FROM `' . DB_PREFIX . 'user_group` ug WHERE ug.user_group_id = f.user_group_id) AS user_group FROM `' . DB_PREFIX . "farmer` f WHERE f.farmer_id = '" . (int) $farmer_id . "'");

        return $query->row;
    }

    public function getUserByUsername($username) {
        $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . "user` WHERE username = '" . $this->db->escape($username) . "'");

        return $query->row;
    }

    public function getFarmerByName($name) {
        $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . "user` WHERE CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($name) . "%' AND user_group_id ='" . $this->config->get('config_farmer_group_id') . "'");
        return $query->row;
    }

    public function getUserByEmail($email) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "user WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "' AND email != '' AND user_group_id = '" . (int) $this->config->get('config_farmer_group_id') . "'");

        return $query->row;
    }

    public function getUserByCode($code) {
        $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . "user` WHERE code = '" . $this->db->escape($code) . "' AND code != ''");

        return $query->row;
    }

    public function getUsers($data = []) {
        $sql = 'SELECT * FROM `' . DB_PREFIX . 'farmer`';

        $isWhere = 1;
        $_sql = [];

        //filter vendor groups

        if (isset($data['filter_user_name']) && !is_null($data['filter_user_name'])) {
            $isWhere = 1;

            $_sql[] = "username LIKE '" . $this->db->escape($data['filter_user_name']) . "%'";
        }

        if (isset($data['filter_user_group']) && !is_null($data['filter_user_group'])) {
            $isWhere = 1;

            $_sql[] = 'user_group_id LIKE ( SELECT ug.user_group_id FROM `' . DB_PREFIX . "user_group` ug WHERE ug.name LIKE '" . $this->db->escape($data['filter_user_group']) . "%') ";
        }

        if (isset($data['filter_first_name']) && !is_null($data['filter_first_name'])) {
            $isWhere = 1;

            $_sql[] = "firstname LIKE '" . $this->db->escape($data['filter_first_name']) . "%'";
        }

        if (isset($data['filter_last_name']) && !is_null($data['filter_last_name'])) {
            $isWhere = 1;

            $_sql[] = "lastname LIKE '" . $this->db->escape($data['filter_last_name']) . "%'";
        }

        if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
            $isWhere = 1;

            $_sql[] = "email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $isWhere = 1;

            $_sql[] = "status LIKE '" . $this->db->escape($data['filter_status']) . "%'";
        }

        if ($_sql) {
            $sql .= ' AND ' . implode(' AND ', $_sql);
        }

        $sort_data = [
            'username',
            'status',
            'date_added',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY username';
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

    public function getTotalUsers() {
        $sql = 'SELECT COUNT(*) AS total FROM `' . DB_PREFIX . 'farmer`';

        //filter vendor groups

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalUsersFilter($data) {
        $sql = ('SELECT COUNT(*) AS total FROM `' . DB_PREFIX . 'farmer`');

        $isWhere = 1;
        $_sql = [];

        //filter vendor groups

        if (isset($data['filter_user_name']) && !is_null($data['filter_user_name'])) {
            $isWhere = 1;

            $_sql[] = "username LIKE '" . $this->db->escape($data['filter_user_name']) . "%'";
        }

        if (isset($data['filter_first_name']) && !is_null($data['filter_first_name'])) {
            $isWhere = 1;

            $_sql[] = "firstname LIKE '" . $this->db->escape($data['filter_first_name']) . "%'";
        }

        if (isset($data['filter_last_name']) && !is_null($data['filter_last_name'])) {
            $isWhere = 1;

            $_sql[] = "lastname LIKE '" . $this->db->escape($data['filter_last_name']) . "%'";
        }

        if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
            $isWhere = 1;

            $_sql[] = "email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $isWhere = 1;

            $_sql[] = "status LIKE '" . $this->db->escape($data['filter_status']) . "%'";
        }

        if ($_sql) {
            $sql .= ' AND ' . implode(' AND ', $_sql);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalUsersByGroupId($user_group_id) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `' . DB_PREFIX . "user` WHERE user_group_id = '" . (int) $user_group_id . "'");

        return $query->row['total'];
    }

    public function getTotalUsersByEmail($email) {
        $sql = 'SELECT COUNT(*) AS total FROM `' . DB_PREFIX . "user` WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'";

        //filter vendor groups
        /* $sql .= ' AND user_group_id NOT IN (' . $this->db->escape($this->config->get('config_vendor_group_ids')) . ') ';
          $sql .= ' AND user_group_id NOT IN (' . $this->db->escape($this->config->get('config_shopper_group_ids')) . ') '; */

        $query = $this->db->query($sql);

        //echo "<pre>";print_r($query);die;
        return $query->row['total'];
    }

    public function getTotalFarmers($data = []) {
        $sql = 'SELECT COUNT(*) AS total FROM ' . DB_PREFIX . 'farmer';

        $implode = [];

        if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(first_name, ' ', last_name) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (!empty($data['filter_telephone'])) {
            $implode[] = "mobile LIKE '" . $this->db->escape($data['filter_telephone']) . "%'";
        }


        if (!empty($data['filter_ip'])) {
            $implode[] = "ip = '" . $this->db->escape($data['filter_ip']) . "'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "status = '" . (int) $data['filter_status'] . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(created_at) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if ($implode) {
            $sql .= ' WHERE ' . implode(' AND ', $implode);
        }

        $query = $this->db->query($sql);

        //echo "<pre>";print_r($sql);die;

        return $query->row['total'];
    }

    public function getFarmers($data = []) {
        $sql = "SELECT *, CONCAT(c.first_name, ' ', c.last_name) AS name FROM " . DB_PREFIX . 'farmer c';

        $implode = [];

        if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(c.first_name, ' ', c.last_name) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (!empty($data['filter_telephone'])) {
            $implode[] = "c.mobile LIKE '" . $this->db->escape($data['filter_telephone']) . "%'";
        }


        if (!empty($data['filter_ip'])) {
            $implode[] = "c.ip = '" . $this->db->escape($data['filter_ip']) . "'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "c.status = '" . (int) $data['filter_status'] . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(c.created_at) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if ($implode) {
            $sql .= ' WHERE ' . implode(' AND ', $implode);
        }

        $sort_data = [
            'c.farmer_id',
            'name',
            'c.email',
            'c.status',
            'c.ip',
            'c.created_at',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY name';
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
        //echo "<pre>";print_r($sql);die;

        return $query->rows;
    }

}
