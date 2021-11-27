<?php

class ModelExecutivesExecutives extends Model {

    public function addExecutive($data) {
        if(isset($data['password']) && $data['password']!='default' &&$data['password']!='' && $data['password']!=NULL )

        {
            $options = [
                'cost' => 8
              ];
              $encrypted_password = password_hash($data['password'], PASSWORD_BCRYPT, $options);
            $this->db->query('INSERT INTO ' . DB_PREFIX . "delivery_executives SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', password = '" . $this->db->escape($encrypted_password) . "', telephone = '" . $this->db->escape($data['telephone']) . "', status = '" . (int) $data['status'] . "', date_added = NOW()");

        }else{
        $this->db->query('INSERT INTO ' . DB_PREFIX . "delivery_executives SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', status = '" . (int) $data['status'] . "', date_added = NOW()");
        }
        $executive_id = $this->db->getLastId();
        return $executive_id;
    }

    public function editExecutive($executive_id, $data) {
        if(isset($data['password']) && $data['password']!='default' &&$data['password']!='' && $data['password']!=NULL )
        {

            $options = [
                'cost' => 8
              ];
              $encrypted_password = password_hash($data['password'], PASSWORD_BCRYPT, $options);
        $this->db->query('UPDATE ' . DB_PREFIX . "delivery_executives SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', password = '" . $this->db->escape($encrypted_password) . "', telephone = '" . $this->db->escape($data['telephone']) . "', status = '" . (int) $data['status'] . "' WHERE delivery_executive_id = '" . (int) $executive_id . "'");

        }else{
        $this->db->query('UPDATE ' . DB_PREFIX . "delivery_executives SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', status = '" . (int) $data['status'] . "' WHERE delivery_executive_id = '" . (int) $executive_id . "'");
        }
    }

    public function editToken($customer_id, $token) {
        $this->db->query('UPDATE ' . DB_PREFIX . "customer SET token = '" . $this->db->escape($token) . "' WHERE customer_id = '" . (int) $customer_id . "'");
    }

    public function deleteExecutive($executive_id) {
        $this->db->query('DELETE FROM ' . DB_PREFIX . "delivery_executives WHERE delivery_executive_id = '" . (int) $executive_id . "'");
    }

    public function getExecutive($executive_id) {
        $query = $this->db->query('SELECT DISTINCT * FROM ' . DB_PREFIX . "delivery_executives WHERE delivery_executive_id = '" . (int) $executive_id . "'");

        return $query->row;
    }

    public function getExecutiveByEmail($email) {
        $query = $this->db->query('SELECT DISTINCT * FROM ' . DB_PREFIX . "delivery_executives WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

        return $query->row;
    }

    public function getExecutives($data = []) {
        $sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name FROM " . DB_PREFIX . 'delivery_executives c';

        $implode = [];

        if (!empty($data['filter_name'])) {
            if ($this->user->isVendor()) {
                $implode[] = "c.firstname LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
            } else {
                $implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
            }
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (!empty($data['filter_telephone'])) {
            $implode[] = "c.telephone LIKE '" . $this->db->escape($data['filter_telephone']) . "%'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "c.status = '" . (int) $data['filter_status'] . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if ($implode) {
            $sql .= ' WHERE ' . implode(' AND ', $implode);
        }

        $sort_data = [
            'name',
            'c.email',
            'c.status',
            'c.date_added',
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

    public function getCompanies($data = []) {
        $sql = 'SELECT distinct company_name AS name FROM ' . DB_PREFIX . 'customer WHERE status = 1';

        $implode = [];
        if (!empty($data['filter_name'])) {
            $implode[] = " company_name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }
        if (!empty($data['filter_account_manager_id'])) {
            $implode[] = " account_manager_id = '" . $this->db->escape($data['filter_account_manager_id']) . "'";
        }
        if ($implode) {
            $sql .= ' AND ' . implode(' AND ', $implode);
        }
        $sql .= ' ORDER BY company_name';

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

    public function getTotalExecutives($data = []) {
        $sql = 'SELECT COUNT(*) AS total FROM ' . DB_PREFIX . 'delivery_executives';

        $implode = [];

        if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (!empty($data['filter_telephone'])) {
            $implode[] = "telephone LIKE '" . $this->db->escape($data['filter_telephone']) . "%'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "status = '" . (int) $data['filter_status'] . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if ($implode) {
            $sql .= ' WHERE ' . implode(' AND ', $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

}
