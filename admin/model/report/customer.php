<?php

class ModelReportCustomer extends Model {

    public function getTotalCustomersByDay() {
        $customer_data = [];

        for ($i = 0; $i < 24; ++$i) {
            $customer_data[$i] = [
                'hour' => $i,
                'total' => 0,
            ];
        }

        $query = $this->db->query('SELECT COUNT(*) AS total, HOUR(date_added) AS hour FROM `' . DB_PREFIX . 'customer` WHERE DATE(date_added) = DATE(NOW()) GROUP BY HOUR(date_added) ORDER BY date_added ASC');

        foreach ($query->rows as $result) {
            $customer_data[$result['hour']] = [
                'hour' => $result['hour'],
                'total' => $result['total'],
            ];
        }

        return $customer_data;
    }

    public function getTotalCustomersByWeek() {
        $customer_data = [];

        $date_start = strtotime('-' . date('w') . ' days');

        for ($i = 0; $i < 7; ++$i) {
            $date = date('Y-m-d', $date_start + ($i * 86400));

            $order_data[date('w', strtotime($date))] = [
                'day' => date('D', strtotime($date)),
                'total' => 0,
            ];
        }

        $query = $this->db->query('SELECT COUNT(*) AS total, date_added FROM `' . DB_PREFIX . "customer` WHERE DATE(date_added) >= DATE('" . $this->db->escape(date('Y-m-d', $date_start)) . "') GROUP BY DAYNAME(date_added)");

        foreach ($query->rows as $result) {
            $customer_data[date('w', strtotime($result['date_added']))] = [
                'day' => date('D', strtotime($result['date_added'])),
                'total' => $result['total'],
            ];
        }

        return $customer_data;
    }

    public function getTotalCustomersByMonth() {
        $customer_data = [];

        for ($i = 1; $i <= date('t'); ++$i) {
            $date = date('Y') . '-' . date('m') . '-' . $i;

            $customer_data[date('j', strtotime($date))] = [
                'day' => date('d', strtotime($date)),
                'total' => 0,
            ];
        }

        $query = $this->db->query('SELECT COUNT(*) AS total, date_added FROM `' . DB_PREFIX . "customer` WHERE DATE(date_added) >= '" . $this->db->escape(date('Y') . '-' . date('m') . '-1') . "' GROUP BY DATE(date_added)");

        foreach ($query->rows as $result) {
            $customer_data[date('j', strtotime($result['date_added']))] = [
                'day' => date('d', strtotime($result['date_added'])),
                'total' => $result['total'],
            ];
        }

        return $customer_data;
    }

    public function getTotalCustomersByYear() {
        $customer_data = [];

        for ($i = 1; $i <= 12; ++$i) {
            $customer_data[$i] = [
                'month' => date('M', mktime(0, 0, 0, $i)),
                'total' => 0,
            ];
        }

        $query = $this->db->query('SELECT COUNT(*) AS total, date_added FROM `' . DB_PREFIX . 'customer` WHERE YEAR(date_added) = YEAR(NOW()) GROUP BY MONTH(date_added)');

        foreach ($query->rows as $result) {
            $customer_data[date('n', strtotime($result['date_added']))] = [
                'month' => date('M', strtotime($result['date_added'])),
                'total' => $result['total'],
            ];
        }

        return $customer_data;
    }

    public function getOrders($data = []) {
        $sql = "SELECT c.customer_id,c.company_name as company, CONCAT(c.firstname, ' ', c.lastname) AS customer, c.email, cgd.name AS customer_group, c.status, o.order_id, SUM(op.quantity) as products, SUM(DISTINCT o.total) AS total FROM `" . DB_PREFIX . 'order` o LEFT JOIN `' . DB_PREFIX . 'order_product` op ON (o.order_id = op.order_id)LEFT JOIN `' . DB_PREFIX . 'customer` c ON (o.customer_id = c.customer_id) LEFT JOIN `' . DB_PREFIX . "customer_group_description` cgd ON (c.customer_group_id = cgd.customer_group_id) WHERE o.customer_id > 0 AND cgd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " AND o.order_status_id > '0'";
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(c.firstname, ' ', c.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "'";
        }

        $sql .= ' GROUP BY o.order_id';

        $sql = 'SELECT t.customer_id, t.customer,t.company, t.email, t.customer_group, t.status, COUNT(DISTINCT t.order_id) AS orders, SUM(t.products) AS products, SUM(t.total) AS total FROM (' . $sql . ') AS t GROUP BY t.customer_id ORDER BY total DESC';

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

    public function getAccountManagerOrders($data = []) {
        $sql = "SELECT c.customer_id, CONCAT(c.firstname, ' ', c.lastname) AS customer,c.company_name as company, c.email, cgd.name AS customer_group, c.status, o.order_id, SUM(op.quantity) as products, SUM(DISTINCT o.total) AS total FROM `" . DB_PREFIX . 'order` o LEFT JOIN `' . DB_PREFIX . 'order_product` op ON (o.order_id = op.order_id)LEFT JOIN `' . DB_PREFIX . 'customer` c ON (o.customer_id = c.customer_id) LEFT JOIN `' . DB_PREFIX . "customer_group_description` cgd ON (c.customer_group_id = cgd.customer_group_id) WHERE o.customer_id > 0 AND cgd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " AND o.order_status_id > '0'";
        }
        $sql .= " AND o.order_status_id NOT IN (0,6,8,16)";
        if ($this->user->isAccountManager()) {
            $sql .= " AND c.account_manager_id = '" . (int) $this->user->getId() . "'";
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.delivery_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.delivery_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(c.firstname, ' ', c.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "'";
        }

        $sql .= ' GROUP BY o.order_id';

        $sql = 'SELECT t.customer_id, t.customer,t.company, t.email, t.customer_group, t.status, COUNT(DISTINCT t.order_id) AS orders, SUM(t.products) AS products, SUM(t.total) AS total FROM (' . $sql . ') AS t GROUP BY t.customer_id ORDER BY total DESC';

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

    public function getOrdersOld($data = []) {
        $sql = "SELECT c.customer_id, CONCAT(c.firstname, ' ', c.lastname) AS customer, c.email, cgd.name AS customer_group, c.status, COUNT(o.order_id) AS orders, SUM(op.quantity) AS products, SUM(o.total) AS `total` FROM `" . DB_PREFIX . 'order` o LEFT JOIN `' . DB_PREFIX . 'order_product` op ON (o.order_id = op.order_id)LEFT JOIN `' . DB_PREFIX . 'customer` c ON (o.customer_id = c.customer_id) LEFT JOIN `' . DB_PREFIX . "customer_group_description` cgd ON (c.customer_group_id = cgd.customer_group_id) WHERE o.customer_id > 0 AND cgd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " AND o.order_status_id > '0'";
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        $sql .= ' GROUP BY o.customer_id ORDER BY total DESC';

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

    public function getTotalOrders($data = []) {
        $sql = 'SELECT COUNT(DISTINCT o.customer_id) AS total FROM `' . DB_PREFIX . "order` o WHERE o.customer_id > '0'";

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " AND o.order_status_id > '0'";
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getAccountManagerTotalOrders($data = []) {
        $sql = 'SELECT COUNT(DISTINCT o.customer_id) AS total FROM `' . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "customer` c ON (o.customer_id = c.customer_id) WHERE o.customer_id > '0'";

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " AND o.order_status_id > '0'";
        }
        $sql .= " AND o.order_status_id NOT IN (0,6,8,16)";
        if ($this->user->isAccountManager()) {
            $sql .= " AND c.account_manager_id = '" . (int) $this->user->getId() . "'";
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.delivery_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.delivery_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getRewardPoints($data = []) {
        $sql = "SELECT cr.customer_id, CONCAT(c.firstname, ' ', c.lastname) AS customer, c.email, cgd.name AS customer_group, c.status, SUM(cr.points) AS points, COUNT(o.order_id) AS orders, SUM(o.total) AS total FROM " . DB_PREFIX . 'customer_reward cr LEFT JOIN `' . DB_PREFIX . 'customer` c ON (cr.customer_id = c.customer_id) LEFT JOIN ' . DB_PREFIX . 'customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id) LEFT JOIN `' . DB_PREFIX . "order` o ON (cr.order_id = o.order_id) WHERE cgd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(cr.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(cr.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        $sql .= ' GROUP BY cr.customer_id ORDER BY points DESC';

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

    public function getTotalRewardPoints($data = []) {
        $sql = 'SELECT COUNT(DISTINCT customer_id) AS total FROM `' . DB_PREFIX . 'customer_reward`';

        $implode = [];

        if (!empty($data['filter_date_start'])) {
            $implode[] = "DATE(date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $implode[] = "DATE(date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if ($implode) {
            $sql .= ' WHERE ' . implode(' AND ', $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getCredit($data = []) {
        $sql = "SELECT ct.customer_id, CONCAT(c.firstname, ' ', c.lastname) AS customer, c.email, cgd.name AS customer_group, c.status, SUM(ct.amount) AS total FROM `" . DB_PREFIX . 'customer_credit` ct LEFT JOIN `' . DB_PREFIX . 'customer` c ON (ct.customer_id = c.customer_id) LEFT JOIN `' . DB_PREFIX . "customer_group_description` cgd ON (c.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(ct.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(ct.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        $sql .= ' GROUP BY ct.customer_id ORDER BY total DESC';

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

    public function getTotalCredit($data = []) {
        $sql = 'SELECT COUNT(DISTINCT customer_id) AS total FROM `' . DB_PREFIX . 'customer_credit`';

        $implode = [];

        if (!empty($data['filter_date_start'])) {
            $implode[] = "DATE(date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $implode[] = "DATE(date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if ($implode) {
            $sql .= ' WHERE ' . implode(' AND ', $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getCustomersOnline($data = []) {
        $sql = 'SELECT co.ip, co.customer_id, co.url, co.referer, co.date_added FROM ' . DB_PREFIX . 'customer_online co LEFT JOIN ' . DB_PREFIX . 'customer c ON (co.customer_id = c.customer_id)';

        $implode = [];

        if (!empty($data['filter_ip'])) {
            $implode[] = "co.ip LIKE '" . $this->db->escape($data['filter_ip']) . "'";
        }
        $implode[] = "c.customer_id > 0";
        if (!empty($data['filter_customer'])) {
            $implode[] = "co.customer_id > 0 AND CONCAT(c.firstname, ' ', c.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "'";
        }

        if (!empty($data['filter_company'])) {
            $implode[] = "co.customer_id > 0 AND CONCAT(c.company_name) LIKE '" . $this->db->escape($data['filter_company']) . "'";
        }

        if ($implode) {
            $sql .= ' WHERE ' . implode(' AND ', $implode);
        }

        $sql .= ' ORDER BY co.date_added DESC';

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

    public function getAccountManagerCustomersOnline($data = []) {
        $sql = 'SELECT co.ip, co.customer_id, co.url, co.referer, co.date_added FROM ' . DB_PREFIX . 'customer_online co LEFT JOIN ' . DB_PREFIX . 'customer c ON (co.customer_id = c.customer_id)';

        $implode = [];

        if (!empty($data['filter_ip'])) {
            $implode[] = "co.ip LIKE '" . $this->db->escape($data['filter_ip']) . "'";
        }

        if ($this->user->isAccountManager()) {
            $implode[] = "c.account_manager_id = '" . (int) $this->user->getId() . "'";
        }

        if (!empty($data['filter_customer'])) {
            $implode[] = "co.customer_id > 0 AND CONCAT(c.firstname, ' ', c.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "'";
        }

        if ($implode) {
            $sql .= ' WHERE ' . implode(' AND ', $implode);
        }

        $sql .= ' ORDER BY co.date_added DESC';

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

    public function getTotalCustomersOnline($data = []) {
        $sql = 'SELECT COUNT(*) AS total FROM `' . DB_PREFIX . 'customer_online` co LEFT JOIN ' . DB_PREFIX . 'customer c ON (co.customer_id = c.customer_id)';

        $implode = [];

        if (!empty($data['filter_ip'])) {
            $implode[] = "co.ip LIKE '" . $this->db->escape($data['filter_ip']) . "'";
        }
        $implode[] = "c.customer_id > 0";
        if (!empty($data['filter_customer'])) {
            $implode[] = "co.customer_id > 0 AND CONCAT(c.firstname, ' ', c.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "'";
        }

        if (!empty($data['filter_company'])) {
            $implode[] = "co.customer_id > 0 AND (c.company_name) LIKE '" . $this->db->escape($data['filter_company']) . "'";
        }

        if ($implode) {
            $sql .= ' WHERE ' . implode(' AND ', $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalAccountManagerCustomersOnline($data = []) {
        $sql = 'SELECT COUNT(*) AS total FROM `' . DB_PREFIX . 'customer_online` co LEFT JOIN ' . DB_PREFIX . 'customer c ON (co.customer_id = c.customer_id)';

        $implode = [];

        if (!empty($data['filter_ip'])) {
            $implode[] = "co.ip LIKE '" . $this->db->escape($data['filter_ip']) . "'";
        }

        if ($this->user->isAccountManager()) {
            $implode[] = "c.account_manager_id = '" . (int) $this->user->getId() . "'";
        }

        if (!empty($data['filter_customer'])) {
            $implode[] = "co.customer_id > 0 AND CONCAT(c.firstname, ' ', c.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "'";
        }

        if ($implode) {
            $sql .= ' WHERE ' . implode(' AND ', $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getCustomerActivities($data = []) {
        $sql = 'SELECT c.company_name ,c.email, ca.activity_id, ca.customer_id, ca.key, ca.data, ca.ip, ca.date_added ,ca.order_id FROM ' . DB_PREFIX . 'customer_activity ca LEFT JOIN ' . DB_PREFIX . 'customer c ON (ca.customer_id = c.customer_id)';

        $implode = [];

        if (!empty($data['filter_customer'])) {
            $implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "'";
        }

        if ($this->user->isAccountManager()) {
            $implode[] = "c.account_manager_id='" . (int) $this->user->getId() . "'";
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

        if (!empty($data['filter_company'])) {
            $implode[] = "c.company_name LIKE '" . $this->db->escape($data['filter_company']) . "'";
        }


        if (!empty($data['filter_key'])) {
            $implode[] = "ca.key LIKE '" . $this->db->escape($data['filter_key']) . "'";
        }

        if (!empty($data['filter_order'])) {
            $implode[] = "ca.order_id = '" . $this->db->escape($data['filter_order']) . "'";
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

    public function getTotalCustomerActivities($data = []) {
        $sql = 'SELECT COUNT(*) AS total FROM `' . DB_PREFIX . 'customer_activity` ca LEFT JOIN ' . DB_PREFIX . 'customer c ON (ca.customer_id = c.customer_id)';

        $implode = [];

        if (!empty($data['filter_customer'])) {
            $implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "'";
        }

        if ($this->user->isAccountManager()) {
            $implode[] = "c.account_manager_id='" . (int) $this->user->getId() . "'";
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

        if (!empty($data['filter_company'])) {
            $implode[] = "c.company_name LIKE '" . $this->db->escape($data['filter_company']) . "'";
        }


        if (!empty($data['filter_key'])) {
            $implode[] = "ca.key LIKE '" . $this->db->escape($data['filter_key']) . "'";
        }

        if (!empty($data['filter_order'])) {
            $implode[] = "ca.order_id = '" . $this->db->escape($data['filter_order']) . "'";
        }

        if ($implode) {
            $sql .= ' WHERE ' . implode(' AND ', $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getActivityKeys() {

        $sql = 'SELECT distinct ca.key  FROM ' . DB_PREFIX . "customer_activity ca";

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalCustomerOrders($data = []) {
        $sql = 'SELECT COUNT(DISTINCT o.order_id) AS total FROM `' . DB_PREFIX . 'order` o  LEFT JOIN `' . DB_PREFIX . "customer` c ON (o.customer_id = c.customer_id) WHERE o.customer_id > '0'";

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " AND o.order_status_id > '0'";
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_customer'])) {
            // $sql .= " AND o.customer_id = '" . (int)$data['filter_customer'] . "'";
            $sql .= " AND CONCAT(c.firstname, ' ', c.lastname)  LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_company'])) {
            $sql .= " AND  c.company_name  LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getCustomerOrders($data = []) {
        $sql = "SELECT c.company_name  as company, o.delivery_date  as delivery_date,c.customer_id, CONCAT(c.firstname, ' ', c.lastname) AS customer, c.email, cgd.name AS customer_group, c.status, o.order_id,o.po_number,o.date_added,o.order_status_id, SUM(op.quantity) as products, SUM(DISTINCT o.total) AS total FROM `" . DB_PREFIX . 'order` o LEFT JOIN `' . DB_PREFIX . 'order_product` op ON (o.order_id = op.order_id)LEFT JOIN `' . DB_PREFIX . 'customer` c ON (o.customer_id = c.customer_id) LEFT JOIN `' . DB_PREFIX . "customer_group_description` cgd ON (c.customer_group_id = cgd.customer_group_id) WHERE o.customer_id > 0 AND cgd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " AND o.order_status_id > '0'";
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_customer'])) {
            // $sql .= " AND   c.customer_id   = '" .(int) $this->db->escape($data['filter_customer']) . "'";
            $sql .= " AND CONCAT(c.firstname, ' ', c.lastname)  LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_company'])) {
            $sql .= " AND c.company_name   LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        $sql .= ' GROUP BY o.order_id ORDER BY o.delivery_date asc';

        //$sql = "SELECT t.customer_id, t.customer, t.email, t.customer_group, t.status, COUNT(DISTINCT t.order_id) AS orders, SUM(t.products) AS products, SUM(t.total) AS total FROM (" . $sql . ") AS t GROUP BY t.customer_id ORDER BY total DESC";

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

    //Added newly

    public function getTotalValidCustomerOrders($data = []) {
        $sql = 'SELECT COUNT(DISTINCT o.order_id) AS total FROM `' . DB_PREFIX . 'order` o  LEFT JOIN `' . DB_PREFIX . "customer` c ON (o.customer_id = c.customer_id) WHERE o.customer_id > '0'";

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " AND o.order_status_id > '0' AND  o.order_status_id != '6'";
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_customer'])) {
            // $sql .= " AND o.customer_id = '" . (int)$data['filter_customer'] . "'";
            $sql .= " AND CONCAT(c.firstname, ' ', c.lastname)  LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_company'])) {
            $sql .= " AND  c.company_name  LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getValidCustomerOrders($data = []) {
        $sql = "SELECT c.company_name  as company,c.SAP_customer_no, o.delivery_date  as delivery_date,c.customer_id, CONCAT(c.firstname, ' ', c.lastname) AS customer, c.email, cgd.name AS customer_group, c.status, o.order_id,o.po_number,o.date_added,o.order_status_id, SUM(op.quantity) as products, SUM(DISTINCT o.total) AS total,o.amount_partialy_paid,o.paid FROM `" . DB_PREFIX . 'order` o LEFT JOIN `' . DB_PREFIX . 'order_product` op ON (o.order_id = op.order_id)LEFT JOIN `' . DB_PREFIX . 'customer` c ON (o.customer_id = c.customer_id) LEFT JOIN `' . DB_PREFIX . "customer_group_description` cgd ON (c.customer_group_id = cgd.customer_group_id) WHERE o.customer_id > 0 AND cgd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " AND o.order_status_id > '0' AND  o.order_status_id != '6'";
        }


        if (!empty($data['statement_duration'])) {
            $sql .= " AND c.statement_duration= '" . $this->db->escape($data['statement_duration']) . "'";
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_customer'])) {
            // $sql .= " AND   c.customer_id   = '" .(int) $this->db->escape($data['filter_customer']) . "'";
            $sql .= " AND CONCAT(c.firstname, ' ', c.lastname)  LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_company'])) {
            $sql .= " AND c.company_name   LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        $sql .= ' GROUP BY o.order_id ORDER BY o.delivery_date asc';

        //$sql = "SELECT t.customer_id, t.customer, t.email, t.customer_group, t.status, COUNT(DISTINCT t.order_id) AS orders, SUM(t.products) AS products, SUM(t.total) AS total FROM (" . $sql . ") AS t GROUP BY t.customer_id ORDER BY total DESC";

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

        // echo  ($sql);die;


        return $query->rows;
    }

    public function getValidCompanyOrders($data = []) {
        $sql = "SELECT c.company_name  as company  , sum(ot.value) as Total,  extract(MONTH from o.date_added) as month    FROM `" . DB_PREFIX . 'order` o LEFT JOIN `' . DB_PREFIX . 'customer` c ON (o.customer_id = c.customer_id) LEFT JOIN `' . DB_PREFIX . "order_total` ot ON (o.order_id = ot.order_id) WHERE ot.code ='total' ";
        //$sql = "SELECT  c.company_name,  sum(ot.value) as Total,extract(MONTH from o.date_added) as month  FROM `".DB_PREFIX.'order` o JOIN `'.DB_PREFIX.'customer` c  ON c.customer_id = o.customer_id join `'.DB_PREFIX.'order_total` ot on ot.order_id =o.order_id'";
        //$sql .= " WHERE ot.code='total' ";

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " AND o.order_status_id > '0' AND  o.order_status_id != '6'";
        }
        // and o.date_added BETWEEN '2020-06-01 00:00:00' AND '2020-09-30 00:00:00' 
        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.date_added) < '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        // if (!empty($data['filter_customer'])) {
        //     // $sql .= " AND   c.customer_id   = '" .(int) $this->db->escape($data['filter_customer']) . "'";
        //     $sql .= " AND CONCAT(c.firstname, ' ', c.lastname)  LIKE '%".$this->db->escape($data['filter_customer'])."%'";
        // }

        if (!empty($data['filter_company'])) {
            $sql .= " AND c.company_name   LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }
        $sql .= "group by month( o.date_added),c.company_name ORDER BY c.company_name asc";

        // if (isset($data['start']) || isset($data['limit'])) {
        //     if ($data['start'] < 0) {
        //         $data['start'] = 0;
        //     }
        //     if ($data['limit'] < 1) {
        //         $data['limit'] = 20;
        //     }
        //     $sql .= ' LIMIT '.(int) $data['start'].','.(int) $data['limit'];
        // }
        $query = $this->db->query($sql);
        //echo  ($sql);die;
        //echo "<pre>";print_r($query->rows);die;
        return $query->rows;
    }

    public function getmonths($data = []) {
        $sql = "SELECT  distinct  extract(MONTH from o.date_added) as month    FROM `" . DB_PREFIX . 'order` o LEFT JOIN `' . DB_PREFIX . 'customer` c ON (o.customer_id = c.customer_id) LEFT JOIN `' . DB_PREFIX . "order_total` ot ON (o.order_id = ot.order_id) WHERE ot.code ='total' ";
        // if (!empty($data['filter_order_status_id'])) {
        //     $sql .= " AND o.order_status_id = '".(int) $data['filter_order_status_id']."'";
        // } else {
        //     $sql .= " AND o.order_status_id > '0' AND  o.order_status_id != '6'";
        // }
        // and o.date_added BETWEEN '2020-06-01 00:00:00' AND '2020-09-30 00:00:00' 
        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }
        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.date_added) < '" . $this->db->escape($data['filter_date_end']) . "'";
        }
        if (!empty($data['filter_account_manager_id'])) {
            $sql .= " AND c.account_manager_id = '" . (int) $this->db->escape($data['filter_account_manager_id']) . "'";
        }
        // if (!empty($data['filter_customer'])) {
        //     // $sql .= " AND   c.customer_id   = '" .(int) $this->db->escape($data['filter_customer']) . "'";
        //     $sql .= " AND CONCAT(c.firstname, ' ', c.lastname)  LIKE '%".$this->db->escape($data['filter_customer'])."%'";
        // }
        // if (!empty($data['filter_company'])) {
        //     $sql .= " AND c.company_name   LIKE '%".$this->db->escape($data['filter_company'])."%'";
        // }
        $sql .= "group by month( o.date_added)    asc";
        $query = $this->db->query($sql);
        //echo  ($sql);die;
        //echo "<pre>";print_r($query->rows);die;
        return $query->rows;
    }

    public function getValidCompanies($data = []) {
        $log = new Log('error.log');
        $log->write($data);
        $sql = "SELECT c.company_name  as company ,c.customer_id from hf7_customer c ";
        $implode = [];
        

        if (!empty($data['filter_company']) && $data['filter_sub_customer_show'] == 0) {
            $implode[] = "c.company_name   LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        if (!empty($data['filter_company']) && $data['filter_sub_customer_show'] == 1) {

        $sub_users_query = $this->db->query("SELECT customer_id  FROM " . DB_PREFIX . "customer WHERE company_name   LIKE '%" . $this->db->escape($data['filter_company']) . "%'");
        $sub_users = $sub_users_query->rows;
        $s_users = array_column($sub_users, 'customer_id');
        $sub_users_od = implode(',', $s_users);

        //  echo  ($sub_users_od);die;

        if(isset($sub_users_od))
        {
            $implode[] = " (c.company_name   LIKE '%" . $this->db->escape($data['filter_company']) . "%' || c.parent in ('" .  $sub_users_od . "'))";
        }

        else {
            $implode[] = " (c.company_name   LIKE '%" . $this->db->escape($data['filter_company']) . "%' )";
            
        }
        }


        if (!empty($data['filter_account_manager_id']) && $data['filter_account_manager_id'] > 0) {
            $implode[] = "c.account_manager_id = '" . (int) $data['filter_account_manager_id'] . "'";
        }

        if (($data['filter_sub_customer_show'] == 0 || $data['filter_sub_customer_show'] == NULL || !array_key_exists('filter_sub_customer_show', $data)) && !isset($data['filter_parent_customer_id'])) {//!array_key_exists('filter_parent_customer_id', $data)
            $implode[] = "(parent = 0 OR parent IS NULL)";
        }

        if ($implode) {
            $sql .= ' WHERE ' . implode(' AND ', $implode);
        }

        $sql .= "group by  c.company_name ORDER BY c.company_name asc";

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
        //  echo  ($sql);die;
        //echo "<pre>";print_r($query->rows);die;
        return $query->rows;
    }

    public function getTotalValidCompanies($data = []) {
        $sql = "SELECT count( distinct (c.company_name) ) as companycount  from hf7_customer c ";
        $implode = [];

        if (!empty($data['filter_company']) && $data['filter_sub_customer_show'] == 0) {
            $implode[] = "c.company_name   LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        if (!empty($data['filter_company']) && $data['filter_sub_customer_show'] == 1) {

        $sub_users_query = $this->db->query("SELECT customer_id  FROM " . DB_PREFIX . "customer WHERE company_name   LIKE '%" . $this->db->escape($data['filter_company']) . "%'");
        $sub_users = $sub_users_query->rows;
        $s_users = array_column($sub_users, 'customer_id');
        $sub_users_od = implode(',', $s_users);

        //  echo  ($sub_users_od);die;

        if(isset($sub_users_od))
        {
            $implode[] = " (c.company_name   LIKE '%" . $this->db->escape($data['filter_company']) . "%' || c.parent in ('" .  $sub_users_od . "'))";
        }

        else {
            $implode[] = " (c.company_name   LIKE '%" . $this->db->escape($data['filter_company']) . "%' )";
            
        }
        }

        if (!empty($data['filter_account_manager_id']) && $data['filter_account_manager_id'] > 0) {
            $implode[] = "c.account_manager_id = '" . (int) $data['filter_account_manager_id'] . "'";
        }

        if (($data['filter_sub_customer_show'] == 0 || $data['filter_sub_customer_show'] == NULL || !array_key_exists('filter_sub_customer_show', $data)) ) {
            $implode[] = "(parent = 0 OR parent IS NULL)";
        }

        if ($implode) {
            $sql .= ' WHERE ' . implode(' AND ', $implode);
        }
        //$sql .= "group by  c.company_name ORDER BY c.company_name asc"; 
        //  echo  ($sql);die;

        $query = $this->db->query($sql);
        // echo  ($query->row['companycount']);die;
        //echo "<pre>";print_r($query->rows);die;
        return $query->row['companycount'];
    }

    public function getCompanyTotal($data = [], $month, $company,$customer_id='') {
        $sql = "SELECT c.company_name  as company  , sum(ot.value) as Total,count(o.order_id) as TotalOrders,  extract(MONTH from o.date_added) as month    FROM `" . DB_PREFIX . 'order` o LEFT JOIN `' . DB_PREFIX . 'customer` c ON (o.customer_id = c.customer_id) LEFT JOIN `' . DB_PREFIX . "order_total` ot ON (o.order_id = ot.order_id) WHERE ot.code ='total' ";
        //$sql = "SELECT  c.company_name,  sum(ot.value) as Total,extract(MONTH from o.date_added) as month  FROM `".DB_PREFIX.'order` o JOIN `'.DB_PREFIX.'customer` c  ON c.customer_id = o.customer_id join `'.DB_PREFIX.'order_total` ot on ot.order_id =o.order_id'";
        //$sql .= " WHERE ot.code='total' ";

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " AND o.order_status_id > '0' AND  o.order_status_id NOT IN (6,8)";
        }

        if (!empty($data['filter_account_manager_id']) && $data['filter_account_manager_id'] > 0) {
            $sql .= " AND c.account_manager_id = '" . (int) $data['filter_account_manager_id'] . "'";
        }

        // and o.date_added BETWEEN '2020-06-01 00:00:00' AND '2020-09-30 00:00:00' 
        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.date_added) < '" . $this->db->escape($data['filter_date_end']) . "'";
        }
        $sql .= " AND Month(o.date_added) = '" . $month . "'";

        // if (!empty($data['filter_customer'])) {
        //     // $sql .= " AND   c.customer_id   = '" .(int) $this->db->escape($data['filter_customer']) . "'";
        //     $sql .= " AND CONCAT(c.firstname, ' ', c.lastname)  LIKE '%".$this->db->escape($data['filter_customer'])."%'";
        // }
        // if (!empty($data['filter_company'])) {
        // $sql .= " AND c.company_name   LIKE '%".$this->db->escape($data['filter_company'])."%'";
        // $sql .= " AND c.company_name   = '" . $company . "'";
        // }

        if (($data['filter_sub_customer_show'] == 0 || $data['filter_sub_customer_show'] == NULL || !array_key_exists('filter_sub_customer_show', $data)) && !isset($data['filter_parent_customer_id'])) {//!array_key_exists('filter_parent_customer_id', $data)
            // $implode[] = "(parent = 0 OR parent IS NULL)";
            $sql .= " AND (c.company_name   = '" . $company . "' || c.parent='" . $customer_id . "' ) ";
        }
        else {
            $sql .= " AND c.company_name   = '" . $company . "'";

        }

        $sql .= "group by month( o.date_added) ";

        // if (isset($data['start']) || isset($data['limit'])) {
        //     if ($data['start'] < 0) {
        //         $data['start'] = 0;
        //     }
        //     if ($data['limit'] < 1) {
        //         $data['limit'] = 20;
        //     }
        //     $sql .= ' LIMIT '.(int) $data['start'].','.(int) $data['limit'];
        // }

        $query = $this->db->query($sql);
        //    echo  ($sql);die;
        //echo "<pre>";print_r($query->rows);die;


        return $query->row;
    }

    public function getboughtproducts($data = []) {
        $sql = "SELECT c.company_name  as company,op.name,op.unit,op.general_product_id, SUM( op.quantity )AS quantity  FROM `" . DB_PREFIX . 'order_product` op LEFT JOIN `' . DB_PREFIX . 'order` o ON (op.order_id = o.order_id) LEFT JOIN `' . DB_PREFIX . "customer` c ON (c.customer_id = o.customer_id) WHERE o.customer_id > 0   and o.order_status_id >0 ";

        // if (!empty($data['filter_order_status_id'])) {
        //     $sql .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        // } else {
        //     $sql .= " AND o.order_status_id > '0' AND  o.order_status_id != '6'";
        // }GROUP BY pd.name   ORDER BY total DESC

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_customer'])) {
            // $sql .= " AND   c.customer_id   = '" .(int) $this->db->escape($data['filter_customer']) . "'";
            $sql .= " AND CONCAT(c.firstname, ' ', c.lastname)  LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_company'])) {
            $sql .= " AND c.company_name   LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        $sql .= ' GROUP BY op.general_product_id   ORDER BY quantity DESC'; //group by name should not be done

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        //   echo  ($sql);die;
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getboughtproductswithRealOrders($data = []) {
        //general product not available..need to change code
        //Order Rejected(16),Order Approval Pending(15),Cancelled(6),Failed(8),Pending(9),Possible Fraud(10)
        $sql0 = "SELECT c.company_name  as company,op.name,op.unit,op.product_id, SUM( op.quantity )AS quantity  FROM `" . DB_PREFIX . 'order_product` op LEFT JOIN `' . DB_PREFIX . 'order` o ON (op.order_id = o.order_id) LEFT JOIN `' . DB_PREFIX . "customer` c ON (c.customer_id = o.customer_id) WHERE o.customer_id > 0   and o.order_status_id not in (0,16,15,6,8,9,10)   and o.order_id not in (select order_id from `hf7_real_order_product`)  ";
        $sql1 = "SELECT c.company_name  as company,op.name,op.unit,op.product_id, SUM( op.quantity )AS quantity  FROM `" . DB_PREFIX . 'real_order_product` op LEFT JOIN `' . DB_PREFIX . 'order` o ON (op.order_id = o.order_id) LEFT JOIN `' . DB_PREFIX . "customer` c ON (c.customer_id = o.customer_id) WHERE o.customer_id > 0   and o.order_status_id not in (0,16,15,6,8,9,10) ";

        // if (!empty($data['filter_order_status_id'])) {
        //     $sql .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        // } else {
        //     $sql .= " AND o.order_status_id > '0' AND  o.order_status_id != '6'";
        // }GROUP BY pd.name   ORDER BY total DESC

        if (!empty($data['filter_date_start'])) {
            $sql0 .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
            $sql1 .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql0 .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
            $sql1 .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_customer'])) {
            // $sql .= " AND   c.customer_id   = '" .(int) $this->db->escape($data['filter_customer']) . "'";
            $sql0 .= " AND CONCAT(c.firstname, ' ', c.lastname)  LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
            $sql1 .= " AND CONCAT(c.firstname, ' ', c.lastname)  LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_company'])) {
            $sql0 .= " AND c.company_name   LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
            $sql1 .= " AND c.company_name   LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }
        //$log = new Log('error.log');
        //$log->write($data['filter_variations']);
        if (!empty($data['filter_variations']) && count($data['filter_variations']) > 0) {
            $variant_array = array_column($data['filter_variations'], 'variation_id');
            $variants = implode(',', $variant_array);
            $sql0 .= "AND op.product_id IN (" . $variants . ")";
            $sql1 .= "AND op.product_id IN (" . $variants . ")";
        }

        $sql0 .= ' GROUP BY op.product_id ';
        $sql1 .= ' GROUP BY op.product_id '; //general_product_id

        $sql = "SELECT company,name,unit,product_id, sum(quantity )AS quantity from (" . $sql0 . "union all " . $sql1 . ") as t";
        $sql .= ' GROUP BY product_id   ORDER BY quantity DESC';

        // if (isset($data['start']) || isset($data['limit'])) {
        //     if ($data['start'] < 0) {
        //         $data['start'] = 0;
        //     }
        //     if ($data['limit'] < 1) {
        //         $data['limit'] = 20;
        //     }
        //     $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        // }
        // echo  ($sql);die;
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalboughtproducts($data = []) {
        // // $sql = 'SELECT COUNT(DISTINCT pd.product_id) AS total FROM `' . DB_PREFIX . 'order` o  LEFT JOIN `' . DB_PREFIX . "customer` c ON (o.customer_id = c.customer_id) WHERE o.customer_id > '0'";
        // //   $sql = "SELECT count(DISTINCT op.general_product_id) AS total  FROM `" . DB_PREFIX . 'order_product` op LEFT JOIN `' . DB_PREFIX . 'order` o ON (op.order_id = o.order_id) LEFT JOIN `' . DB_PREFIX . "customer` c ON (c.customer_id = o.customer_id) WHERE o.customer_id > 0   and o.order_status_id >0 ";
        // $sql = "SELECT count( distinct op.general_product_id ) as total  FROM `" . DB_PREFIX . 'order_product` op LEFT JOIN `' . DB_PREFIX . 'order` o ON (op.order_id = o.order_id) LEFT JOIN `' . DB_PREFIX . "customer` c ON (c.customer_id = o.customer_id) WHERE o.customer_id > 0   and o.order_status_id >0 ";

        $sql = "SELECT c.company_name  as company,op.name,op.unit,op.general_product_id, SUM( op.quantity )AS quantity  FROM `" . DB_PREFIX . 'order_product` op LEFT JOIN `' . DB_PREFIX . 'order` o ON (op.order_id = o.order_id) LEFT JOIN `' . DB_PREFIX . "customer` c ON (c.customer_id = o.customer_id) WHERE o.customer_id > 0   and o.order_status_id >0 ";

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_customer'])) {
            // $sql .= " AND o.customer_id = '" . (int)$data['filter_customer'] . "'";
            $sql .= " AND CONCAT(c.firstname, ' ', c.lastname)  LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_company'])) {
            $sql .= " AND  c.company_name  LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }
        $sql .= ' GROUP BY op.name   ORDER BY quantity DESC';
        //  echo  ($sql);die;
        $query = $this->db->query($sql);

        return count($query->rows);
        // return $query->num_rows();
    }

    public function getTotalOrdersPlaced($data = []) {
        $sql = 'SELECT COUNT(DISTINCT c.customer_id) AS total FROM `' . DB_PREFIX . 'customer` c  JOIN `' . DB_PREFIX . "order` o ON (o.customer_id = c.customer_id) WHERE c.customer_id > '0' and o.order_status_id NOT IN (0,6,8,15,16,10)";

        // if (!empty($data['filter_order_status_id'])) {
        //     $sql .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        // } else {
        //     $sql .= " AND o.order_status_id > '0'";
        // }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(c.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(c.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }
        //   echo  ($sql);die;
        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getOrdersPlaced($data = []) {
        $sql = "SELECT c.customer_id,c.company_name as company, CONCAT(c.firstname, ' ', c.lastname) AS customer, c.email, cgd.name AS customer_group, c.status, o.order_id, SUM(op.quantity) as products, SUM(DISTINCT o.total) AS total FROM `" . DB_PREFIX . 'order` o LEFT JOIN `' . DB_PREFIX . 'order_product` op ON (o.order_id = op.order_id)LEFT JOIN `' . DB_PREFIX . 'customer` c ON (o.customer_id = c.customer_id) LEFT JOIN `' . DB_PREFIX . "customer_group_description` cgd ON (c.customer_group_id = cgd.customer_group_id) WHERE o.customer_id > 0 AND cgd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

        // if (!empty($data['filter_order_status_id'])) {
        //     $sql .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        // } else {
        $sql .= " AND o.order_status_id NOT IN (0,6,8,15,16,10)";
        // }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(c.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(c.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(c.firstname, ' ', c.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "'";
        }

        $sql .= ' GROUP BY o.order_id';

        $sql = 'SELECT t.customer_id, t.customer,t.company, t.email, t.customer_group, t.status, COUNT(DISTINCT t.order_id) AS orders, SUM(t.products) AS products, SUM(t.total) AS total FROM (' . $sql . ') AS t GROUP BY t.customer_id ORDER BY total DESC';

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }
        //  echo  ($sql);die;
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalCustomersOnboarded($data = []) {
        $sql = 'SELECT count(DISTINCT c.customer_id) AS total FROM `' . DB_PREFIX . 'customer` c  JOIN `' . DB_PREFIX . "order` o ON (o.customer_id = c.customer_id) WHERE c.customer_id > '0' and o.order_status_id not in (0,16,6,8,9,10)";

        // if (!empty($data['filter_order_status_id'])) {
        //     $sql .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        // } else {
        //     $sql .= " AND o.order_status_id > '0'";
        // }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        $sql .= " AND o.customer_id not in (select customer_id from  `" . DB_PREFIX . "order` where date_added < '" . $this->db->escape($data['filter_date_start']) . "')";

        //  echo  ($sql);die;
        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getCustomersOnboarded($data = []) {
        $sql = "SELECT   c.customer_id,c.company_name as company, CONCAT(c.firstname, ' ', c.lastname) AS customer, o.order_id, o.date_added FROM `" . DB_PREFIX . "order` o  JOIN `" . DB_PREFIX . "customer` c ON (o.customer_id = c.customer_id)  WHERE o.customer_id > 0  ";

        // if (!empty($data['filter_order_status_id'])) {
        //     $sql .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        // } else {
        $sql .= " AND o.order_status_id not in (0,16,6,8,9,10)";
        // }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(c.firstname, ' ', c.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "'";
        }
        $sql .= " AND o.customer_id not in (select customer_id from  `" . DB_PREFIX . "order` where date_added < '" . $this->db->escape($data['filter_date_start']) . "' and order_status_id not in(0,16,6,8,9,10))";

        // $sql .= ' GROUP BY o.order_id';
        $sql .= ' GROUP BY c.customer_id,c.company_name,customer ';
        $sql .= ' Order BY o.order_id asc';

        // $sql = 'SELECT t.customer_id, t.customer,t.company, t.email, t.customer_group, t.status, COUNT(DISTINCT t.order_id) AS orders, SUM(t.products) AS products, SUM(t.total) AS total FROM (' . $sql . ') AS t GROUP BY t.customer_id ORDER BY total DESC';

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }
        //   echo  ($sql);die;
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalCustomersUnordered($data = []) {
        $sql = 'SELECT count(DISTINCT c.customer_id) AS total FROM `' . DB_PREFIX . 'customer` c   WHERE c.customer_id > 0 and (c.parent =0 or c.parent=null)';

        // if (!empty($data['filter_order_status_id'])) {
        //     $sql .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        // } else {
        //     $sql .= " AND o.order_status_id > '0'";
        // }
        // if (!empty($data['filter_date_start'])) {
        //     $sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        // }
        // if (!empty($data['filter_date_end'])) {
        //     $sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        // }

        $sql .= " AND c.customer_id not in (select customer_id from  `" . DB_PREFIX . "order` where order_status_id not in (0,16,6,8,9,10))";

        //    echo  ($sql);die;
        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getCustomersUnordered($data = []) {
        $sql = "SELECT   c.customer_id,c.company_name as company,c.date_added, CONCAT(c.firstname, ' ', c.lastname) AS customer, c.email,c.status,c.approved FROM `" . DB_PREFIX . "customer` c  WHERE c.customer_id > 0 and (c.parent =0 or c.parent=null) ";

        // if (!empty($data['filter_order_status_id'])) {
        //     $sql .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        // } else {
        // $sql .= " AND o.order_status_id > '0'";
        // }
        // if (!empty($data['filter_date_start'])) {
        //     $sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        // }
        // if (!empty($data['filter_date_end'])) {
        //     $sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        // }
        // if (!empty($data['filter_customer'])) {
        //     $sql .= " AND CONCAT(c.firstname, ' ', c.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "'";
        // }
        $sql .= " AND c.customer_id not in (select customer_id from  `" . DB_PREFIX . "order` where order_status_id not in (0,16,6,8,9,10)) ";

        // $sql .= ' GROUP BY c.customer_id,c.company_name,customer ';
        $sql .= ' Order BY c.customer_id desc';

        // $sql = 'SELECT t.customer_id, t.customer,t.company, t.email, t.customer_group, t.status, COUNT(DISTINCT t.order_id) AS orders, SUM(t.products) AS products, SUM(t.total) AS total FROM (' . $sql . ') AS t GROUP BY t.customer_id ORDER BY total DESC';

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }
        //    echo  ($sql);die;
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getCustomerWithOrders($data = []) {


        $sql = 'SELECT distinct CONCAT(c.firstname, " ", c.lastname) AS name,c.customer_id,c.email FROM `' . DB_PREFIX . 'order` o  LEFT JOIN `' . DB_PREFIX . "customer` c ON (o.customer_id = c.customer_id) WHERE o.customer_id > '0'";

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " AND o.order_status_id > '0'";
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_customer'])) {
            // $sql .= " AND o.customer_id = '" . (int)$data['filter_customer'] . "'";
            $sql .= " AND CONCAT(c.firstname, ' ', c.lastname)  LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_company'])) {
            $sql .= " AND  c.company_name  LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }
        // echo "<pre>";print_r($sql);die;


        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getCustomerContacts($customer_id) {
        $contacts = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer_contact c WHERE c.customer_id = '" . (int) $customer_id . "' and c.send=1");
        return $contacts->rows;
    }

    public function getValidCustomerOrdersByDates($data = [], $dt) {

        $results_biweekly_2 = NULL;
        $results = NULL;
        if (date('l', $dt) == 'Sunday') {//weekly
            $data['filter_date_start'] = date("Y-m-d", strtotime("-1 days", $dt));
            $data['filter_date_end'] = date("Y-m-d", strtotime("-7 days", $dt));
            $data['statement_duration'] = 7;
            $results_weekly = $this->getValidCustomerOrders($data);
        }
        if (date('d', $dt) == '01' || date('d', $dt) == '16') {//bi-wwekly
            if (date('d', $dt) == '01') {//monthly
                $dtp = date("Y-m-d", strtotime("-1 days", $dt));
                $data['filter_date_end'] = date("Y-m-t", strtotime($dtp));
                $data['filter_date_start'] = date("Y-m-16", strtotime($dtp));
                $data['statement_duration'] = 15;
                $results_biweekly_1 = $this->getValidCustomerOrders($data);
                // echo "<pre>";print_r(date('l', $results_biweekly_1));die;
            } else {
                $dtp = date("Y-m-d", strtotime("-1 days", $dt));
                $data['filter_date_start'] = date("Y-m-01", strtotime($dtp));
                $data['filter_date_end'] = $dtp;
                $data['statement_duration'] = 15;
                $results_biweekly_2 = $this->getValidCustomerOrders($data);
            }
        }
        if (date('d', $dt) == '01') {//monthly
            $dtp = date("Y-m-d", strtotime("-1 days", $dt));
            $data['filter_date_end'] = date("Y-m-t", strtotime($dtp));
            $data['filter_date_start'] = date("Y-m-01", strtotime($dtp));
            $data['statement_duration'] = 30;

            $results_monthly = $this->getValidCustomerOrders($data);

            // echo "<pre>";print_r( $results_monthly );die;
        }
        // echo "<pre>";print_r($data);die;

        if (!isset($data['statement_duration'])) {
            return;
        }

        // echo "<pre>";print_r(date(d, $dt));
        // echo "<pre>";print_r('no data fetched');;

        $results;
        if ($results_weekly != null) {
            $results = $results_weekly;
        }
        if ($results_biweekly_1 != null) {
            if ($results != null)
                $results = array_merge($results, $results_biweekly_1);
            else
                $results = $results_biweekly_1;
        }

        if ($results_biweekly_2 != null) {
            if ($results != null)
                $results = array_merge($results, $results_biweekly_2);
            else
                $results = $results_biweekly_2;
        }

        if ($results_monthly != null) {
            if ($results != null)
                $results = array_merge($results, $results_monthly);
            else
                $results = $results_monthly;
        }

        //   echo "<pre>";print_r($results);die;
        return $results;
    }


    
    public function getCustomerFinancialStatementByID($data = []) {
        $sql = "SELECT year(o.delivery_date) as fiscal_year,date(o.delivery_date) as posting_date,'DR'as Document_type,'Debit' as credit_debit,'KES' as currency,c.SAP_customer_no, o.delivery_date  as delivery_date,c.customer_id, CONCAT(c.firstname, ' ', c.lastname) AS customer,c.company_name, c.email,  c.status, o.order_id,o.date_added,o.order_status_id,  o.total AS total,ot.value as updated_total,o.amount_partialy_paid,o.paid FROM `" . DB_PREFIX . 'order` o LEFT JOIN `'  . DB_PREFIX . 'customer` c ON (o.customer_id = c.customer_id) LEFT JOIN `' . DB_PREFIX . "order_total` ot ON (o.order_id=ot.order_id)  WHERE o.customer_id > 0 AND ot.title = 'Total'";
        $sqlcredit = "SELECT year(cc.date_added) as fiscal_year,date(cc.date_added) as posting_date,'CR'as Document_type,'Credit' as credit_debit,'KES' as currency,c.SAP_customer_no, cc.date_added  as delivery_date,c.customer_id, CONCAT(c.firstname, ' ', c.lastname) AS customer,c.company_name, c.email,  c.status, 0 as order_id,cc.date_added,'' as order_status_id,  cc.amount AS total,cc.amount as updated_total,0 as amount_partialy_paid,'' as paid FROM `" . DB_PREFIX . "customer_credit` cc LEFT JOIN `"  . DB_PREFIX . "customer` c ON (cc.customer_id = c.customer_id)     WHERE cc.customer_id > 0 AND cc.amount >0";

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " AND o.order_status_id > '0' AND  o.order_status_id != '6'";//
        }

 

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
            $sqlcredit .= " AND DATE(cc.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
            $sqlcredit .= " AND DATE(cc.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_customer'])) {
            // $sql .= " AND   c.customer_id   = '" .(int) $this->db->escape($data['filter_customer']) . "'";
            $sql .= " AND CONCAT(c.firstname, ' ', c.lastname)  LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
            $sqlcredit .= " AND CONCAT(c.firstname, ' ', c.lastname)  LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_company'])) {
            $sql .= " AND c.company_name   LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
            $sqlcredit .= " AND c.company_name   LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        $sql .= ' GROUP BY o.order_id ';//ORDER BY o.delivery_date asc
        // $sqlcredit .= ' ORDER BY cc.date_added asc';

        
        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            // $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
            $sqllimit .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }
        $sqlFinal=" select * from (".$sql." union all ".$sqlcredit.") as t order by delivery_date ". $sqllimit ;
        // $query = $this->db->query($sql);
        $query = $this->db->query($sqlFinal);

        // echo  ($sqlFinal);die;


        return $query->rows;
    }


    
    public function getTotalCustomerOrdersByID($data = []) {

        $sql = 'SELECT COUNT(DISTINCT o.order_id) AS total FROM `' . DB_PREFIX . 'order` o  LEFT JOIN `' . DB_PREFIX . "customer` c ON (o.customer_id = c.customer_id) WHERE o.customer_id > '0'";

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " AND o.order_status_id > '0' AND  o.order_status_id != '6'";//AND  o.order_status_id != '6'
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_customer'])) {
            // $sql .= " AND o.customer_id = '" . (int)$data['filter_customer'] . "'";
            $sql .= " AND CONCAT(c.firstname, ' ', c.lastname)  LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_company'])) {
            $sql .= " AND  c.company_name  LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }


    public function getTotalCustomerWalletCreditsByID($data = []) {
        $sql = 'SELECT COUNT(DISTINCT customer_credit_id) AS total FROM `' . DB_PREFIX . 'customer_credit` cc  LEFT JOIN `' . DB_PREFIX . "customer` c ON (cc.customer_id = c.customer_id) WHERE cc.customer_id > '0'";

       

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(cc.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(cc.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_customer'])) {
            // $sql .= " AND o.customer_id = '" . (int)$data['filter_customer'] . "'";
            $sql .= " AND CONCAT(c.firstname, ' ', c.lastname)  LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_company'])) {
            $sql .= " AND  c.company_name  LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getYears($data = []) {

        $sql = 'SELECT DISTINCT year(o.delivery_date) AS year FROM `' . DB_PREFIX . 'order` o  LEFT JOIN `' . DB_PREFIX . "customer` c ON (o.customer_id = c.customer_id) WHERE o.customer_id > '0'";

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " AND o.order_status_id > '0' AND  o.order_status_id != '6'";//AND  o.order_status_id != '6'
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_customer'])) {
            // $sql .= " AND o.customer_id = '" . (int)$data['filter_customer'] . "'";
            $sql .= " AND CONCAT(c.firstname, ' ', c.lastname)  LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_company'])) {
            $sql .= " AND  c.company_name  LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        $query = $this->db->query($sql);
        // echo  ($sql);die;
        //   echo "<pre>";print_r($query->rows);die;


        return $query->rows;
    }


    public function getCustomerFinancialStatementByGroup($data = []) {
        $sql = "SELECT year(o.delivery_date) as fiscal_year,month(o.delivery_date) as fiscal_month,'DR'as Document_type,'Debit' as credit_debit,'KES' as currency,c.SAP_customer_no, o.delivery_date  as delivery_date,c.customer_id, CONCAT(c.firstname, ' ', c.lastname) AS customer,c.company_name, c.email,  c.status, o.order_id,o.date_added,o.order_status_id,  o.total AS total,ot.value as updated_total,o.amount_partialy_paid,o.paid FROM `" . DB_PREFIX . 'order` o LEFT JOIN `'  . DB_PREFIX . 'customer` c ON (o.customer_id = c.customer_id) LEFT JOIN `' . DB_PREFIX . "order_total` ot ON (o.order_id=ot.order_id)  WHERE o.customer_id > 0 AND ot.title = 'Total'";
        $sqlcredit = "SELECT year(cc.date_added) as fiscal_year,month(cc.date_added) as fiscal_month,'CR'as Document_type,'Credit' as credit_debit,'KES' as currency,c.SAP_customer_no, cc.date_added  as delivery_date,c.customer_id, CONCAT(c.firstname, ' ', c.lastname) AS customer,c.company_name, c.email,  c.status, 0 as order_id,cc.date_added,'' as order_status_id,  cc.amount AS total,cc.amount as updated_total,0 as amount_partialy_paid,'' as paid FROM `" . DB_PREFIX . "customer_credit` cc LEFT JOIN `"  . DB_PREFIX . "customer` c ON (cc.customer_id = c.customer_id)     WHERE cc.customer_id > 0 AND cc.amount >0";

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " AND o.order_status_id > '0' AND  o.order_status_id != '6'";//
        }

 

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
            $sqlcredit .= " AND DATE(cc.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
            $sqlcredit .= " AND DATE(cc.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_customer'])) {
            // $sql .= " AND   c.customer_id   = '" .(int) $this->db->escape($data['filter_customer']) . "'";
            $sql .= " AND CONCAT(c.firstname, ' ', c.lastname)  LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
            $sqlcredit .= " AND CONCAT(c.firstname, ' ', c.lastname)  LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_company'])) {
            $sql .= " AND c.company_name   LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
            $sqlcredit .= " AND c.company_name   LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        $sql .= ' GROUP BY o.order_id ';//ORDER BY o.delivery_date asc
        // $sqlcredit .= ' ORDER BY cc.date_added asc';

        
        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            // $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
            $sqllimit .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }
        $sqlFinal=" select * from (".$sql." union all ".$sqlcredit.") as t Group by month(delivery_date),year(delivery_date),credit_Debit order by delivery_date ". $sqllimit ;
        // $query = $this->db->query($sql);
        $query = $this->db->query($sqlFinal);

        // echo  ($sqlFinal);die;


        return $query->rows;
    }


    public function getCustomerFinancialStatementByGroup1($data = []) {
        $sql = "SELECT year(o.delivery_date) as fiscal_year,month(o.delivery_date) as fiscal_month,sum(o.total) AS total,sum(ot.value) as updated_total ,0 as wallet_total FROM `" . DB_PREFIX . 'order` o LEFT JOIN `'  . DB_PREFIX . 'customer` c ON (o.customer_id = c.customer_id) LEFT JOIN `' . DB_PREFIX . "order_total` ot ON (o.order_id=ot.order_id)  WHERE o.customer_id > 0 AND ot.title = 'Total'";

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " AND o.order_status_id > '0' AND  o.order_status_id != '6'";//
        }

 

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_customer'])) {
            // $sql .= " AND   c.customer_id   = '" .(int) $this->db->escape($data['filter_customer']) . "'";
            $sql .= " AND CONCAT(c.firstname, ' ', c.lastname)  LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
            $sqlcredit .= " AND CONCAT(c.firstname, ' ', c.lastname)  LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_company'])) {
            $sql .= " AND c.company_name   LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        $sql .= ' Group by month(o.delivery_date),year(o.delivery_date) order by o.delivery_date asc';       
       
        $query_order = $this->db->query($sql);
        

        return $query_order->rows;
    }

    public function getCustomerFinancialStatementByGroup2($data = []) {

        $sqlcredit = "SELECT year(cc.date_added) as fiscal_year,month(cc.date_added) as fiscal_month, 0 AS total,sum(cc.amount) as updated_total,0 as wallet_total FROM `" . DB_PREFIX . "customer_credit` cc LEFT JOIN `"  . DB_PREFIX . "customer` c ON (cc.customer_id = c.customer_id)     WHERE cc.customer_id > 0 AND cc.amount >0";

        
 

        if (!empty($data['filter_date_start'])) {
            $sqlcredit .= " AND DATE(cc.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sqlcredit .= " AND DATE(cc.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_customer'])) {
           $sqlcredit .= " AND CONCAT(c.firstname, ' ', c.lastname)  LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_company'])) {
            $sqlcredit .= " AND c.company_name   LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        // $sqlcredit .= ' ORDER BY cc.date_added asc';
        $sqlcredit .= ' Group by month(cc.date_added),year(cc.date_added) order by cc.date_added asc';

         
       
        $query_credit = $this->db->query($sqlcredit);

        return $query_credit->rows;
    }
}
