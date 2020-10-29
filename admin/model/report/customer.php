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
        $sql = "SELECT c.customer_id, CONCAT(c.firstname, ' ', c.lastname) AS customer, c.email, cgd.name AS customer_group, c.status, o.order_id, SUM(op.quantity) as products, SUM(DISTINCT o.total) AS total FROM `" . DB_PREFIX . 'order` o LEFT JOIN `' . DB_PREFIX . 'order_product` op ON (o.order_id = op.order_id)LEFT JOIN `' . DB_PREFIX . 'customer` c ON (o.customer_id = c.customer_id) LEFT JOIN `' . DB_PREFIX . "customer_group_description` cgd ON (c.customer_group_id = cgd.customer_group_id) WHERE o.customer_id > 0 AND cgd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

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

        $sql = 'SELECT t.customer_id, t.customer, t.email, t.customer_group, t.status, COUNT(DISTINCT t.order_id) AS orders, SUM(t.products) AS products, SUM(t.total) AS total FROM (' . $sql . ') AS t GROUP BY t.customer_id ORDER BY total DESC';

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
        $sql = "SELECT c.customer_id, CONCAT(c.firstname, ' ', c.lastname) AS customer, c.email, cgd.name AS customer_group, c.status, o.order_id, SUM(op.quantity) as products, SUM(DISTINCT o.total) AS total FROM `" . DB_PREFIX . 'order` o LEFT JOIN `' . DB_PREFIX . 'order_product` op ON (o.order_id = op.order_id)LEFT JOIN `' . DB_PREFIX . 'customer` c ON (o.customer_id = c.customer_id) LEFT JOIN `' . DB_PREFIX . "customer_group_description` cgd ON (c.customer_group_id = cgd.customer_group_id) WHERE o.customer_id > 0 AND cgd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " AND o.order_status_id > '0'";
        }

        if ($this->user->isAccountManager()) {
            $sql .= " AND c.account_manager_id = '" . (int) $this->user->getId() . "'";
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

        $sql = 'SELECT t.customer_id, t.customer, t.email, t.customer_group, t.status, COUNT(DISTINCT t.order_id) AS orders, SUM(t.products) AS products, SUM(t.total) AS total FROM (' . $sql . ') AS t GROUP BY t.customer_id ORDER BY total DESC';

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

        if ($this->user->isAccountManager()) {
            $sql .= " AND c.account_manager_id = '" . (int) $this->user->getId() . "'";
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

        if (!empty($data['filter_customer'])) {
            $implode[] = "co.customer_id > 0 AND CONCAT(c.firstname, ' ', c.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "'";
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
        $sql = 'SELECT ca.activity_id, ca.customer_id, ca.key, ca.data, ca.ip, ca.date_added FROM ' . DB_PREFIX . 'customer_activity ca LEFT JOIN ' . DB_PREFIX . 'customer c ON (ca.customer_id = c.customer_id)';

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

        if ($implode) {
            $sql .= ' WHERE ' . implode(' AND ', $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
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
        $sql = "SELECT c.company_name  as company, o.delivery_date  as delivery_date,c.customer_id, CONCAT(c.firstname, ' ', c.lastname) AS customer, c.email, cgd.name AS customer_group, c.status, o.order_id,o.po_number,o.date_added,o.order_status_id, SUM(op.quantity) as products, SUM(DISTINCT o.total) AS total FROM `" . DB_PREFIX . 'order` o LEFT JOIN `' . DB_PREFIX . 'order_product` op ON (o.order_id = op.order_id)LEFT JOIN `' . DB_PREFIX . 'customer` c ON (o.customer_id = c.customer_id) LEFT JOIN `' . DB_PREFIX . "customer_group_description` cgd ON (c.customer_group_id = cgd.customer_group_id) WHERE o.customer_id > 0 AND cgd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

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
        $sql = "SELECT c.company_name  as company  from hf7_customer c ";

        if (!empty($data['filter_company'])) {
            $sql .= " where c.company_name   LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
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

        if (!empty($data['filter_company'])) {
            $sql .= " where c.company_name   LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }
        //$sql .= "group by  c.company_name ORDER BY c.company_name asc"; 

        $query = $this->db->query($sql);
        // echo  ($query->row['companycount']);die;
        //echo "<pre>";print_r($query->rows);die;
        return $query->row['companycount'];
    }

    public function getCompanyTotal($data = [], $month, $company) {
        $sql = "SELECT c.company_name  as company  , sum(ot.value) as Total,count(o.order_id) as TotalOrders,  extract(MONTH from o.date_added) as month    FROM `" . DB_PREFIX . 'order` o LEFT JOIN `' . DB_PREFIX . 'customer` c ON (o.customer_id = c.customer_id) LEFT JOIN `' . DB_PREFIX . "order_total` ot ON (o.order_id = ot.order_id) WHERE ot.code ='total' ";
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
        $sql .= " AND Month(o.date_added) = '" . $month . "'";


        // if (!empty($data['filter_customer'])) {
        //     // $sql .= " AND   c.customer_id   = '" .(int) $this->db->escape($data['filter_customer']) . "'";
        //     $sql .= " AND CONCAT(c.firstname, ' ', c.lastname)  LIKE '%".$this->db->escape($data['filter_customer'])."%'";
        // }
        // if (!empty($data['filter_company'])) {
        // $sql .= " AND c.company_name   LIKE '%".$this->db->escape($data['filter_company'])."%'";
        $sql .= " AND c.company_name   = '" . $company . "'";
        // }
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
        //   echo  ($sql);die;
        //echo "<pre>";print_r($query->rows);die;


        return $query->row;
    }

}
