<?php

class ModelUserSupplier extends Model {

    public function addSupplier($data) {
        $this->db->query('INSERT INTO `' . DB_PREFIX . "farmer` SET user_group_id = '" . $this->config->get('config_supplier_group_id') . "', first_name = '" . $this->db->escape($data['first_name']) . "', last_name = '" . $this->db->escape($data['last_name']) . "', email = '" . $this->db->escape($data['email']) . "', mobile = '" . $this->db->escape($data['mobile']) . "', location = '" . $data['location'] . "', description = '" . $data['description'] . "',  salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', ip = '" . $data['ip'] . "', latitude = '" . $data['latitude'] . "', longitude = '" . (int) $data['longitude'] . "', status = '" . $data['status'] . "', organization = '" . $data['organization'] . "', created_at = NOW()");

        return $this->db->getLastId();
    }

    public function editSupplier($supplier_id, $data) {
        $this->db->query('UPDATE `' . DB_PREFIX . "farmer` SET username = '" . $this->db->escape($data['username']) . "', first_name = '" . $this->db->escape($data['first_name']) . "', last_name = '" . $this->db->escape($data['last_name']) . "', email = '" . $this->db->escape($data['email']) . "', mobile = '" . $this->db->escape($data['mobile']) . "', location = '" . $data['location'] . "', description = '" . $data['description'] . "', organization = '" . $data['organization'] . "', status = '" . (int) $data['status'] . "', updated_at = NOW() WHERE farmer_id = '" . (int) $supplier_id . "'");

        if ($data['password']) {
            $this->db->query('UPDATE `' . DB_PREFIX . "farmer` SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' WHERE farmer_id = '" . (int) $supplier_id . "'");
        }
    }

    public function editPassword($user_id, $password) {
        $this->db->query('UPDATE `' . DB_PREFIX . "farmer` SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "', code = '' WHERE farmer_id = '" . (int) $user_id . "'");
    }

    public function editCode($email, $code) {
        $this->db->query('UPDATE `' . DB_PREFIX . "farmer` SET code = '" . $this->db->escape($code) . "' WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
    }

    public function editCodeMobile($mobile, $code) {
        $this->db->query('UPDATE `' . DB_PREFIX . "farmer` SET code = '" . $this->db->escape($code) . "' WHERE mobile = '" . $this->db->escape($mobile) . "'");
    }

    public function deleteUser($user_id) {
        $this->db->query('DELETE FROM `' . DB_PREFIX . "user` WHERE user_id = '" . (int) $user_id . "'");
    }

    public function getSupplier($supplier_id) {
        $query = $this->db->query('SELECT *, (SELECT ug.name FROM `' . DB_PREFIX . 'user_group` ug WHERE ug.user_group_id = f.user_group_id) AS user_group FROM `' . DB_PREFIX . "farmer` f WHERE f.farmer_id = '" . (int) $supplier_id . "'");

        return $query->row;
    }

    public function getSupplierByUsername($username) {
        $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . "farmer` WHERE username = '" . $this->db->escape($username) . "'");

        return $query->row;
    }

    public function getSupplierByName($name) {
        $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . "user` WHERE CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($name) . "%' AND user_group_id ='" . $this->config->get('config_farmer_group_id') . "'");
        return $query->row;
    }

    public function getSupplierByEmail($email) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "farmer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "' AND email != '' AND user_group_id = '" . (int) $this->config->get('config_farmer_group_id') . "'");

        return $query->row;
    }

    public function getSupplierByPhone($mobile) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "farmer WHERE mobile = '" . $this->db->escape($mobile) . "' AND mobile != '' AND user_group_id = '" . (int) $this->config->get('config_farmer_group_id') . "'");

        return $query->row;
    }

    public function getSupplierByCode($code) {
        $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . "farmer` WHERE code = '" . $this->db->escape($code) . "' AND code != ''");

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

    public function getTotalSuppliersByEmail($email) {
        $sql = 'SELECT COUNT(*) AS total FROM `' . DB_PREFIX . "farmer` WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'";

        //filter vendor groups
        /* $sql .= ' AND user_group_id NOT IN (' . $this->db->escape($this->config->get('config_vendor_group_ids')) . ') ';
          $sql .= ' AND user_group_id NOT IN (' . $this->db->escape($this->config->get('config_shopper_group_ids')) . ') '; */

        $query = $this->db->query($sql);

        //echo "<pre>";print_r($query);die;
        return $query->row['total'];
    }

    public function getTotalSuppliersByMobile($mobile) {
        $sql = 'SELECT COUNT(*) AS total FROM `' . DB_PREFIX . "farmer` WHERE mobile = '" . $this->db->escape($mobile) . "'";

        //filter vendor groups
        /* $sql .= ' AND user_group_id NOT IN (' . $this->db->escape($this->config->get('config_vendor_group_ids')) . ') ';
          $sql .= ' AND user_group_id NOT IN (' . $this->db->escape($this->config->get('config_shopper_group_ids')) . ') '; */

        $query = $this->db->query($sql);

        //echo "<pre>";print_r($query);die;
        return $query->row['total'];
    }

    public function getTotalSuppliers($data = []) {
        $sql = 'SELECT COUNT(*) AS total FROM ' . DB_PREFIX . 'farmer';

        $implode = [];

        $implode[] = "user_group_id = '" . $this->config->get('config_supplier_group_id') . "'";
        if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(first_name, ' ', last_name) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (!empty($data['filter_mobile'])) {
            $implode[] = "mobile LIKE '" . $this->db->escape($data['filter_mobile']) . "%'";
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

    public function getSuppliers($data = []) {
        $sql = "SELECT *, CONCAT(c.first_name, ' ', c.last_name) AS name FROM " . DB_PREFIX . 'farmer c';

        $implode = [];
        $implode[] = "user_group_id = '" . $this->config->get('config_supplier_group_id') . "'";
        if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(c.first_name, ' ', c.last_name) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (!empty($data['filter_mobile'])) {
            $implode[] = "c.mobile LIKE '" . $this->db->escape($data['filter_mobile']) . "%'";
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

    public function getSupplierActivities($data = []) {
        $sql = 'SELECT c.organization, c.first_name, c.last_name, c.email, ca.activity_id, ca.farmer_id, ca.key, ca.data, ca.ip, ca.date_added FROM ' . DB_PREFIX . 'farmer_activity ca LEFT JOIN ' . DB_PREFIX . 'farmer c ON (ca.farmer_id = c.farmer_id)';

        $implode = [];

        if (!empty($data['filter_farmer'])) {
            $implode[] = "CONCAT(c.first_name, ' ', c.last_name) LIKE '" . $this->db->escape($data['filter_farmer']) . "'";
        }

        if (!empty($data['filter_ip'])) {
            $implode[] = "ca.ip LIKE '" . $this->db->escape($data['filter_ip']) . "'";
        }

        if (!empty($data['filter_date_start'])) {
            $implode[] = "DATE(ca.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $implode[] = "DATE(ca.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_organization'])) {
            $implode[] = "c.organization LIKE '" . $this->db->escape($data['filter_organization']) . "'";
        }


        if (!empty($data['filter_key'])) {
            $implode[] = "ca.key LIKE '" . $this->db->escape($data['filter_key']) . "'";
        }

        if ($implode) {
            $sql .= ' WHERE ' . implode(' AND ', $implode);
        }

        $sql .= ' ORDER BY ca.date_added DESC';

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

    public function getTotalSupplierActivities($data = []) {
        $sql = 'SELECT COUNT(*) AS total FROM `' . DB_PREFIX . 'farmer_activity` ca LEFT JOIN ' . DB_PREFIX . 'farmer c ON (ca.farmer_id = c.farmer_id)';

        $implode = [];

        if (!empty($data['filter_farmer'])) {
            $implode[] = "CONCAT(c.first_name, ' ', c.last_name) LIKE '" . $this->db->escape($data['filter_farmer']) . "'";
        }

        if (!empty($data['filter_ip'])) {
            $implode[] = "ca.ip LIKE '" . $this->db->escape($data['filter_ip']) . "'";
        }

        if (!empty($data['filter_date_start'])) {
            $implode[] = "DATE(ca.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $implode[] = "DATE(ca.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_organization'])) {
            $implode[] = "c.organization LIKE '" . $this->db->escape($data['filter_organization']) . "'";
        }


        if (!empty($data['filter_key'])) {
            $implode[] = "ca.key LIKE '" . $this->db->escape($data['filter_key']) . "'";
        }

        if ($implode) {
            $sql .= ' WHERE ' . implode(' AND ', $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getActivityKeys() {

        $sql = 'SELECT distinct ca.key  FROM ' . DB_PREFIX . "farmer_activity ca";

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getSupplierOrganizations($data = []) {
        $sql = 'SELECT distinct organization AS name FROM ' . DB_PREFIX . 'farmer WHERE status = 1';

        $implode = [];
        if (!empty($data['filter_name'])) {
            $implode[] = " organization LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }
        if ($implode) {
            $sql .= ' AND ' . implode(' AND ', $implode);
        }
        $sql .= ' ORDER BY organization';

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

}
