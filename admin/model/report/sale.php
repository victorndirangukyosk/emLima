<?php

class ModelReportSale extends Model {

    public function getTotalIncome($data) {
        $sql = 'select count(*) as total from ' . DB_PREFIX . 'user u ';
        $sql .= 'LEFT JOIN `' . DB_PREFIX . 'city` c on c.city_id = u.city_id ';
        $sql .= 'WHERE u.user_group_id IN (' . $this->config->get('config_vendor_group_ids') . ')';

        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $this->db->escape($data['filter_city']) . "%'";
        }

        return $this->db->query($sql)->row['total'];
    }

    public function getIncomes($data) {
        $filter = [];

        if (!empty($data['filter_date_start'])) {
            $filter[] .= " DATE(u.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $filter[] .= " DATE(u.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        $where = ' WHERE ' . implode(' AND ', $filter);

        $pt = '(select sum(amount) from ' . DB_PREFIX . 'package_transactions ' . $where . ' AND vendor_id=u.user_id) as pt ';

        $sql = 'select ' . $pt . ', u.username as vendor from `' . DB_PREFIX . 'user` u ';
        $sql .= 'LEFT JOIN `' . DB_PREFIX . 'city` c on c.city_id = u.city_id ';
        $sql .= 'WHERE user_group_id IN (' . $this->config->get('config_vendor_group_ids') . ')';

        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $this->db->escape($data['filter_city']) . "%'";
        }

        $sort_data = [
            'vendor',
            'at',
            'mat',
            'pt',
            'c.name',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY vendor';
        }

        if (isset($data['order']) && ('ASC' == $data['order'])) {
            $sql .= ' ASC';
        } else {
            $sql .= ' DESC';
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

        return $this->db->query($sql)->rows;
    }

    public function getVendors($data = []) {
        $sql = 'SELECT MIN(u.date_added) AS date_start, MAX(u.date_added) AS date_end, COUNT(u.user_id) AS `total` FROM ' . DB_PREFIX . 'user u ';

        $sql .= ' left join `' . DB_PREFIX . 'city` c on c.city_id = u.city_id ';

        $sql .= ' WHERE u.user_group_id IN (' . $this->config->get('config_vendor_group_ids') . ')';

        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $this->db->escape($data['filter_city']) . "%'";
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(u.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(u.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_group'])) {
            $group = $data['filter_group'];
        } else {
            $group = 'week';
        }

        switch ($group) {
            case 'day':
                $sql .= ' GROUP BY DAY(u.date_added)';
                break;
            default:
            case 'week':
                $sql .= ' GROUP BY WEEK(u.date_added)';
                break;
            case 'month':
                $sql .= ' GROUP BY MONTH(u.date_added)';
                break;
            case 'year':
                $sql .= ' GROUP BY YEAR(u.date_added)';
                break;
        }

        $sql .= ' ORDER BY u.date_added DESC';

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

    public function getTotalVendors($data = []) {
        if (!empty($data['filter_group'])) {
            $group = $data['filter_group'];
        } else {
            $group = 'week';
        }

        switch ($group) {
            case 'day':
                $sql = 'SELECT COUNT(DISTINCT DAY(u.date_added)) AS total FROM `' . DB_PREFIX . 'user` u';
                break;
            default:
            case 'week':
                $sql = 'SELECT COUNT(DISTINCT WEEK(u.date_added)) AS total FROM `' . DB_PREFIX . 'user` u';
                break;
            case 'month':
                $sql = 'SELECT COUNT(DISTINCT MONTH(u.date_added)) AS total FROM `' . DB_PREFIX . 'user` u';
                break;
            case 'year':
                $sql = 'SELECT COUNT(DISTINCT YEAR(u.date_added)) AS total FROM `' . DB_PREFIX . 'user` u';
                break;
        }

        $sql .= ' left join `' . DB_PREFIX . 'city` c on c.city_id = u.city_id ';

        $sql .= ' WHERE u.user_group_id IN (' . $this->config->get('config_vendor_group_ids') . ')';

        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $this->db->escape($data['filter_city']) . "%'";
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(u.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(u.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalVendorOrders($data = []) {
        $sql = 'select count(*) as total from ' . DB_PREFIX . 'order vo';
        $sql .= ' inner join ' . DB_PREFIX . 'order_product op on op.order_id = vo.order_id';
        $sql .= ' inner join ' . DB_PREFIX . 'store st on st.store_id = vo.store_id';
        $sql .= ' inner join ' . DB_PREFIX . 'user u on u.user_id = st.vendor_id';
        $sql .= ' inner join ' . DB_PREFIX . 'order o on o.order_id = vo.order_id';

        if (!empty($data['filter_city'])) {
            $sql .= ' inner join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id';
        }

        if (!empty($data['filter_order_status_id'])) {
            $sql .= ' WHERE o.order_status_id = "' . $data['filter_order_status_id'] . '"';
        } else {
            $sql .= ' WHERE o.order_status_id > 0';
        }

        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $this->db->escape($data['filter_city']) . "%'";
        }

        if ($this->user->isVendor()) {
            $sql .= ' AND vo.vendor_id = "' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(vo.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(vo.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_group'])) {
            $group = $data['filter_group'];
        } else {
            $group = 'week';
        }

        switch ($group) {
            case 'day':
                $sql .= ' GROUP BY u.user_id, YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added)';
                break;
            default:
            case 'week':
                $sql .= ' GROUP BY u.user_id, YEAR(o.date_added), WEEK(o.date_added)';
                break;
            case 'month':
                $sql .= ' GROUP BY u.user_id, YEAR(o.date_added), MONTH(o.date_added)';
                break;
            case 'year':
                $sql .= ' GROUP BY u.user_id, YEAR(o.date_added)';
                break;
        }

        $query = $this->db->query($sql);

        //echo "<pre>";print_r($sql);die;
        if ($query->num_rows) {
            return $query->row['total'];
        }
    }

    public function getVendorOrdersOld($data = []) {
        $sql = 'select  sum(op.total) as subtotal,sum(vo.total) as total, MAX(vo.date_added) as date_end, MIN(vo.date_added) as date_start, u.username as vendor, count(DISTINCT(vo.order_id)) as orders, SUM(op.quantity) as products from ' . DB_PREFIX . 'order vo';

        $sql .= ' inner join ' . DB_PREFIX . 'store st on st.store_id = vo.store_id';

        $sql .= ' inner join ' . DB_PREFIX . 'order_product op on op.order_id = vo.order_id';
        $sql .= ' inner join ' . DB_PREFIX . 'user u on u.user_id = st.vendor_id';
        $sql .= ' inner join ' . DB_PREFIX . 'order o on o.order_id = vo.order_id';

        if (!empty($data['filter_city'])) {
            $sql .= ' inner join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id';
        }

        if (!empty($data['filter_order_status_id'])) {
            $sql .= ' WHERE o.order_status_id = "' . $data['filter_order_status_id'] . '"';
        } else {
            $sql .= ' WHERE o.order_status_id > 0';
        }

        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $this->db->escape($data['filter_city']) . "%'";
        }

        if ($this->user->isVendor()) {
            $sql .= ' AND vo.vendor_id = "' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(vo.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(vo.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_group'])) {
            $group = $data['filter_group'];
        } else {
            $group = 'week';
        }

        switch ($group) {
            case 'day':
                $sql .= ' GROUP BY u.user_id, YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added)';
                break;
            default:
            case 'week':
                $sql .= ' GROUP BY u.user_id, YEAR(o.date_added), WEEK(o.date_added)';
                break;
            case 'month':
                $sql .= ' GROUP BY u.user_id, YEAR(o.date_added), MONTH(o.date_added)';
                break;
            case 'year':
                $sql .= ' GROUP BY u.user_id, YEAR(o.date_added)';
                break;
        }

        $sort_data = [
            'orders',
            'products',
            'total',
            'subtotal',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY total';
        }

        if (isset($data['order']) && ('ASC' == $data['order'])) {
            $sql .= ' ASC';
        } else {
            $sql .= ' DESC';
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

        //echo "<pre>";print_r($sql);die;
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getVendorOrders($data = []) {
        $sql = 'SELECT MIN(o.date_added) AS date_start, MAX(o.date_added) AS date_end, COUNT(*) AS `orders`, SUM((SELECT SUM(op.quantity) FROM `' . DB_PREFIX . 'order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id)) AS products, 

		SUM((SELECT SUM(op.total) FROM `' . DB_PREFIX . 'order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id)) AS subtotal,

		u.username as vendor, SUM((SELECT SUM(ot.value) FROM `' . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'tax' GROUP BY ot.order_id)) AS tax, SUM(o.total) AS `total` FROM `" . DB_PREFIX . 'order` o';

        $sql .= ' INNER JOIN `' . DB_PREFIX . 'store` st on st.store_id = o.store_id ';
        $sql .= ' inner join ' . DB_PREFIX . 'user u on u.user_id = st.vendor_id';

        if (!empty($data['filter_city'])) {
            $sql .= ' LEFT JOIN `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id ';
        }

        if (!empty($data['filter_vendor'])) {
            $sql .= ' AND st.vendor_id = "' . $data['filter_vendor'] . '"';
        }

        if ($this->user->isVendor()) {
            $sql .= ' AND st.vendor_id = "' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " WHERE o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }

        if (!empty($data['filter_city'])) {
            $sql .= ' AND c.name LIKE "' . $data['filter_city'] . '%"';
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if ($this->user->isVendor()) {
            $sql .= ' AND o.vendor_id = "' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_group'])) {
            $group = $data['filter_group'];
        } else {
            $group = 'week';
        }

        switch ($group) {
            case 'day':
                $sql .= ' GROUP BY u.user_id,YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added)';
                break;
            default:
            case 'week':
                $sql .= ' GROUP BY u.user_id,YEAR(o.date_added), WEEK(o.date_added)';
                break;
            case 'month':
                $sql .= ' GROUP BY u.user_id,YEAR(o.date_added), MONTH(o.date_added)';
                break;
            case 'year':
                $sql .= ' GROUP BY u.user_id,YEAR(o.date_added)';
                break;
        }

        $sql .= ' ORDER BY o.date_added DESC';

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        //echo $sql;die;

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getReportVendorOrders($data = []) {
        $sql = "SELECT c.name as city, o.store_id, o.firstname,o.lastname,o.delivery_date,o.order_id, o.shipping_method, o.payment_method, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status,(SELECT os.color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS color, o.shipping_code, o.order_status_id,o.store_name,  o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . 'order` o ';

        $sql .= 'left join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id';
        $sql .= ' LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id) ';

        if (!empty($data['filter_city'])) {
            $sql .= ' LEFT JOIN `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id ';
        }

        //echo "<pre>";print_r($sql);die;

        if ($this->user->isVendor()) {
            $sql .= ' AND ' . DB_PREFIX . 'store.vendor_id="' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " WHERE o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }
        $sql .= " AND o.order_status_id NOT IN (0,6,8,16)";

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }
        if (!empty($data['filter_vendor'])) {
            $sql .= ' AND vendor_id="' . $data['filter_vendor'] . '"';
        }

        if (!empty($data['filter_store'])) {
            $sql .= ' AND o.store_id = "' . $data['filter_store'] . '"';
        }

        //echo "<pre>";print_r($sql);die;

        $sql .= ' ORDER BY o.order_id DESC';

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        //echo "<pre>";print_r($sql);die;
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getCombinedReportVendorOrders($data = []) {
        $sql = "SELECT c.name as city, o.store_id, o.firstname,o.lastname,o.delivery_date,o.order_id, o.shipping_method, o.payment_method, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status,(SELECT os.color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS color, o.shipping_code, o.order_status_id,o.store_name,  o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . 'order` o ';

        $sql .= 'left join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id';
        $sql .= ' LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id) ';

        if (!empty($data['filter_city'])) {
            $sql .= ' LEFT JOIN `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id ';
        }

        //echo "<pre>";print_r($sql);die;

        if ($this->user->isVendor()) {
            $sql .= ' AND ' . DB_PREFIX . 'store.vendor_id="' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_order_status_id'])) {
            $sql .= ' WHERE o.order_status_id IN(' . implode(',', $this->config->get('config_complete_status')) . ')';
        } else {
            $sql .= ' WHERE o.order_status_id IN(' . implode(',', $this->config->get('config_complete_status')) . ')';
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.delivery_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.delivery_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }
        if (!empty($data['filter_vendor'])) {
            $sql .= ' AND vendor_id="' . $data['filter_vendor'] . '"';
        }

        if (!empty($data['filter_store'])) {
            $sql .= ' AND o.store_id = "' . $data['filter_store'] . '"';
        }

        //echo "<pre>";print_r($sql);die;

        $sql .= ' ORDER BY o.order_id DESC';

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        //echo "<pre>";print_r($sql);die;
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getVendorStores($store_id = false) {
        //echo "<pre>";print_r($store_id);die;

        if ($store_id) {
            $store_ids = $store_id;
        } else {
            $store_ids = '';

            if ($this->user->isVendor()) {
                $vendor_id = $this->user->getId();

                $query = $this->db->query('SELECT store_id  FROM ' . DB_PREFIX . 'store WHERE vendor_id =' . $vendor_id . '');
            } else {
                $query = $this->db->query('SELECT store_id FROM ' . DB_PREFIX . 'store');
            }

            $tmp_store_ids = $query->rows;

            foreach ($tmp_store_ids as $key => $value) {
                $t_store_ids[] = $value['store_id'];
            }

            //echo "<pre>";print_r($t_store_ids);die;

            if (is_array($t_store_ids)) {
                $store_ids = implode(',', $t_store_ids);
            }
        }

        //echo "<pre>";print_r($store_ids);die;

        $query = $this->db->query('SELECT DISTINCT * FROM ' . DB_PREFIX . 'store WHERE store_id  IN(' . $store_ids . ')');

        $stores = $query->rows;

        $tmp_stores = $stores;
        $ts_store_ids = [];

        foreach ($tmp_stores as $key => $value) {
            $ts_store_ids[] = $value['name'];
        }

        //echo "<pre>";print_r($ts_store_ids);die;
        $stores_names = '';

        if (is_array($ts_store_ids)) {
            $stores_names = implode(',', $ts_store_ids);
        }

        //echo "<pre>";print_r($stores_names);die;

        return $stores_names;
    }

    public function getTotalReportVendorOrders($data = []) {
        $sql = "SELECT c.name as city, o.firstname,o.lastname,o.delivery_date,o.order_id, o.shipping_method, o.payment_method, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status,(SELECT os.color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS color, o.shipping_code, o.order_status_id,o.store_name,  o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . 'order` o ';

        $sql .= 'left join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id';
        $sql .= ' LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id) ';

        if (!empty($data['filter_city'])) {
            $sql .= ' LEFT JOIN `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id ';
        }

        //echo "<pre>";print_r($sql);die;

        if ($this->user->isVendor()) {
            $sql .= ' AND ' . DB_PREFIX . 'store.vendor_id="' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " WHERE o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }
        $sql .= " AND o.order_status_id NOT IN (0,6,8,16)";

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }
        if (!empty($data['filter_vendor'])) {
            $sql .= ' AND vendor_id="' . $data['filter_vendor'] . '"';
        }

        if (!empty($data['filter_store'])) {
            $sql .= ' AND o.store_id = "' . $data['filter_store'] . '"';
        }

        $sql .= ' ORDER BY o.order_id DESC';

        //echo "<pre>";print_r($sql);die;
        $query = $this->db->query($sql);

        return $query->num_rows;
    }

    public function getTotalCombinedReportVendorOrders($data = []) {
        $sql = "SELECT c.name as city, o.firstname,o.lastname,o.delivery_date,o.order_id, o.shipping_method, o.payment_method, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status,(SELECT os.color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS color, o.shipping_code, o.order_status_id,o.store_name,  o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . 'order` o ';

        $sql .= 'left join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id';
        $sql .= ' LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id) ';

        if (!empty($data['filter_city'])) {
            $sql .= ' LEFT JOIN `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id ';
        }

        //echo "<pre>";print_r($sql);die;

        if ($this->user->isVendor()) {
            $sql .= ' AND ' . DB_PREFIX . 'store.vendor_id="' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " WHERE o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= ' WHERE o.order_status_id IN(' . implode(',', $this->config->get('config_complete_status')) . ')';
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.delivery_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.delivery_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }
        if (!empty($data['filter_vendor'])) {
            $sql .= ' AND vendor_id="' . $data['filter_vendor'] . '"';
        }

        if (!empty($data['filter_store'])) {
            $sql .= ' AND o.store_id = "' . $data['filter_store'] . '"';
        }

        $sql .= ' ORDER BY o.order_id DESC';

        //echo "<pre>";print_r($sql);die;
        $query = $this->db->query($sql);

        return $query->num_rows;
    }

    public function getStoreSaleOrdersOld($data = []) {
        $sql = 'select sum(op.total) as subtotal,sum(o.total) as total, MAX(vo.date_added) as date_end, MIN(vo.date_added) as date_start, st.name as store, count(DISTINCT(vo.order_id)) as orders, SUM(op.quantity) as products from ' . DB_PREFIX . 'order vo';
        $sql .= ' inner join ' . DB_PREFIX . 'store st on st.store_id = vo.store_id';

        $sql .= ' inner join ' . DB_PREFIX . 'order_product op on op.order_id = vo.order_id';
        $sql .= ' inner join ' . DB_PREFIX . 'user u on u.user_id = st.vendor_id';
        $sql .= ' inner join ' . DB_PREFIX . 'order o on o.order_id = vo.order_id';

        if (!empty($data['filter_city'])) {
            $sql .= ' inner join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id';
        }

        if (!empty($data['filter_order_status_id'])) {
            $sql .= ' WHERE o.order_status_id = "' . $data['filter_order_status_id'] . '"';
        } else {
            $sql .= ' WHERE o.order_status_id > 0';
        }

        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $this->db->escape($data['filter_city']) . "%'";
        }

        if ($this->user->isVendor()) {
            $sql .= ' AND u.user_id = "' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(vo.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(vo.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_group'])) {
            $group = $data['filter_group'];
        } else {
            $group = 'week';
        }

        switch ($group) {
            case 'day':
                $sql .= ' GROUP BY st.store_id, YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added)';
                break;
            default:
            case 'week':
                $sql .= ' GROUP BY st.store_id, YEAR(o.date_added), WEEK(o.date_added)';
                break;
            case 'month':
                $sql .= ' GROUP BY st.store_id, YEAR(o.date_added), MONTH(o.date_added)';
                break;
            case 'year':
                $sql .= ' GROUP BY st.store_id, YEAR(o.date_added)';
                break;
        }

        $sort_data = [
            'orders',
            'products',
            'total',
            'subtotal',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY total';
        }

        if (isset($data['order']) && ('ASC' == $data['order'])) {
            $sql .= ' ASC';
        } else {
            $sql .= ' DESC';
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

        //echo "<pre>";print_r($sql);die;
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getStoreSaleOrders($data = []) {
        $sql = 'SELECT MIN(o.date_added) AS date_start, MAX(o.date_added) AS date_end, COUNT(*) AS `orders`, SUM((SELECT SUM(op.quantity) FROM `' . DB_PREFIX . 'order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id)) AS products, 

		st.name as store,

		SUM((SELECT SUM(op.total) FROM `' . DB_PREFIX . 'order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id)) AS subtotal,

		u.username as vendor, SUM((SELECT SUM(ot.value) FROM `' . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'tax' GROUP BY ot.order_id)) AS tax, SUM(o.total) AS `total` FROM `" . DB_PREFIX . 'order` o';

        $sql .= ' INNER JOIN `' . DB_PREFIX . 'store` st on st.store_id = o.store_id ';
        $sql .= ' inner join ' . DB_PREFIX . 'user u on u.user_id = st.vendor_id';

        if (!empty($data['filter_city'])) {
            $sql .= ' LEFT JOIN `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id ';
        }

        if (!empty($data['filter_store'])) {
            $sql .= ' AND st.store_id = "' . $data['filter_store'] . '"';
        }

        if ($this->user->isVendor()) {
            $sql .= ' AND st.vendor_id = "' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " WHERE o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }

        if (!empty($data['filter_city'])) {
            $sql .= ' AND c.name LIKE "' . $data['filter_city'] . '%"';
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if ($this->user->isVendor()) {
            $sql .= ' AND st.vendor_id = "' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_group'])) {
            $group = $data['filter_group'];
        } else {
            $group = 'week';
        }

        switch ($group) {
            case 'day':
                $sql .= ' GROUP BY st.store_id,YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added)';
                break;
            default:
            case 'week':
                $sql .= ' GROUP BY st.store_id,YEAR(o.date_added), WEEK(o.date_added)';
                break;
            case 'month':
                $sql .= ' GROUP BY st.store_id,YEAR(o.date_added), MONTH(o.date_added)';
                break;
            case 'year':
                $sql .= ' GROUP BY st.store_id,YEAR(o.date_added)';
                break;
        }

        $sql .= ' ORDER BY o.date_added DESC';

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        //echo $sql;die;

        $query = $this->db->query($sql);

        return $query->rows;
    }

    //vendor sale

    public function getVendorTotalSales($data = []) {
        $sql = 'SELECT SUM(op.total) AS total FROM `' . DB_PREFIX . 'order_product` op';
        $sql .= ' inner join ' . DB_PREFIX . 'order o on o.order_id = op.order_id';
        $sql .= ' inner join ' . DB_PREFIX . 'store st on st.store_id = o.store_id';

        $sql .= " where st.vendor_id='" . $this->user->getId() . "'";
        $sql .= " AND  o.order_status_id > '0'";

        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getAccountManagerTotalSales($data = []) {
        $sql = 'SELECT SUM(op.total) AS total FROM `' . DB_PREFIX . 'order_product` op';
        $sql .= ' inner join ' . DB_PREFIX . 'order o on o.order_id = op.order_id';
        $sql .= ' inner join ' . DB_PREFIX . 'store st on st.store_id = o.store_id';
        $sql .= ' inner join ' . DB_PREFIX . 'customer c on c.customer_id = o.customer_id';

        $sql .= " where c.account_manager_id='" . $this->user->getId() . "'";
        $sql .= " AND  o.order_status_id > '0'";
        $sql .= " AND o.order_status_id NOT IN (0,6,8,16)";

        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    //vendor actual sales

    public function getActualVendorSales() {
        $complete_status_ids = '(' . implode(',', $this->config->get('config_complete_status')) . ')';

        if (isset($this->request->get['vendor_id'])) {
            $vendor_id = $this->request->get['vendor_id'];
        } else {
            $vendor_id = $this->user->getId();
        }

        $query = $this->db->query('SELECT SUM(total) AS total FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = ' . DB_PREFIX . "order.store_id) WHERE vendor_id='" . $vendor_id . "' AND order_status_id IN " . $complete_status_ids);

        /* echo "SELECT SUM(total) AS total FROM `".DB_PREFIX."order` LEFT JOIN ".DB_PREFIX."store on(".DB_PREFIX."store.store_id = ".DB_PREFIX."order.store_id) WHERE vendor_id='".$vendor_id."' AND order_status_id IN " . $complete_status_ids ; */
        return $query->row['total'];
    }
    
    public function getActualAccountManagerSales() {
        $complete_status_ids = '(' . implode(',', $this->config->get('config_complete_status')) . ')';
        $log = new Log('error.log');
        $log->write('config_complete_status');
        $log->write($complete_status_ids);
        $log->write('config_complete_status');

        if (isset($this->request->get['account_manager_id'])) {
            $account_manager_id = $this->request->get['account_manager_id'];
        } else if($this->user->isAccountManager() == 1) {
            $account_manager_id = $this->user->getId();
        }
        
        if($account_manager_id != NULL) {
        $query = $this->db->query('SELECT SUM(total) AS total FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'customer on(' . DB_PREFIX . 'customer.customer_id = ' . DB_PREFIX . "order.customer_id) WHERE account_manager_id='" . $account_manager_id . "' AND order_status_id IN " . $complete_status_ids);
        } else {
        $query = $this->db->query('SELECT SUM(total) AS total FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'customer on(' . DB_PREFIX . 'customer.customer_id = ' . DB_PREFIX . "order.customer_id) WHERE order_status_id IN " . $complete_status_ids);    
        }
        /* echo "SELECT SUM(total) AS total FROM `".DB_PREFIX."order` LEFT JOIN ".DB_PREFIX."store on(".DB_PREFIX."store.store_id = ".DB_PREFIX."order.store_id) WHERE vendor_id='".$vendor_id."' AND order_status_id IN " . $complete_status_ids ; */
        return $query->row['total'];
    }    

    // Sales
    public function getActualSales($data = []) {
        $complete_status_ids = '(' . implode(',', $this->config->get('config_complete_status')) . ')';
        $log = new Log('error.log');
        $log->write('config_complete_status');
        $log->write($complete_status_ids);
        $log->write('config_complete_status');
        
        $account_manager_id = NULL;
        if (isset($this->request->get['account_manager_id'])) {
            $account_manager_id = $this->request->get['account_manager_id'];
        } else if($this->user->isAccountManager() == 1) {
            $account_manager_id = $this->user->getId();
        } else if($data['account_manager'] != NULL && $data['account_manager'] > 0) {
          $account_manager_id = $data['account_manager'];  
        }
                
        if($account_manager_id != NULL) {
        $sql = 'SELECT SUM(total) AS total FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'customer on(' . DB_PREFIX . 'customer.customer_id = ' . DB_PREFIX . "order.customer_id) WHERE account_manager_id='" . $account_manager_id . "' AND order_status_id IN " . $complete_status_ids;
        } else {
        $sql = 'SELECT SUM(total) AS total FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'customer on(' . DB_PREFIX . 'customer.customer_id = ' . DB_PREFIX . "order.customer_id) WHERE order_status_id IN " . $complete_status_ids;    
        }
        
        if (!empty($data['filter_date_start']) && !empty($data['filter_date_end'])) {
            $sql .= " AND DATE(".DB_PREFIX."order.date_added) BETWEEN DATE('".$this->db->escape($data['filter_date_start'])."') AND DATE('".$this->db->escape($data['filter_date_end'])."')";
        }
        $query = $this->db->query($sql);
        /* echo "SELECT SUM(total) AS total FROM `".DB_PREFIX."order` LEFT JOIN ".DB_PREFIX."store on(".DB_PREFIX."store.store_id = ".DB_PREFIX."order.store_id) WHERE vendor_id='".$vendor_id."' AND order_status_id IN " . $complete_status_ids ; */
        return $query->row['total'];
    }

    public function getTotalSales($data = []) {
        $sql = 'SELECT SUM(total) AS total FROM `' . DB_PREFIX . "order` WHERE order_status_id > '0'";

        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }
        //AVOID Cancelled,Failed Orders
        $sql .= " AND order_status_id NOT IN (6,8)";
        $query = $this->db->query($sql);

        return $query->row['total'];
    }
    
    public function getTotalSalesCustom($data = []) {
        $sql = 'SELECT SUM(total) AS total FROM `' . DB_PREFIX . "order` WHERE order_status_id > '0'";

        if (!empty($data['filter_date_start']) && !empty($data['filter_date_end'])) {
            $sql .= " AND DATE(date_added) BETWEEN DATE('".$this->db->escape($data['filter_date_start'])."') AND DATE('".$this->db->escape($data['filter_date_end'])."')";
        }
        $query = $this->db->query($sql);

        return $query->row['total'];
    }    

    // Map

    public function getTotalOrdersByCountry() {
        $query = $this->db->query('SELECT COUNT(*) AS total, SUM(o.total) AS amount, c.iso_code_2 FROM `' . DB_PREFIX . 'order` o LEFT JOIN `' . DB_PREFIX . "country` c ON (o.payment_country_id = c.country_id) WHERE o.order_status_id > '0' GROUP BY o.payment_country_id");

        return $query->rows;
    }

    // Orders

    public function getTotalOrdersByDay() {
        $implode = [];

        foreach ($this->config->get('config_complete_status') as $order_status_id) {
            $implode[] = "'" . (int) $order_status_id . "'";
        }

        $order_data = [];

        for ($i = 0; $i < 24; ++$i) {
            $order_data[$i] = [
                'hour' => $i,
                'total' => 0,
            ];
        }

        $query = $this->db->query('SELECT COUNT(*) AS total, HOUR(date_added) AS hour FROM `' . DB_PREFIX . 'order` WHERE order_status_id IN(' . implode(',', $implode) . ') AND DATE(date_added) = DATE(NOW()) GROUP BY HOUR(date_added) ORDER BY date_added ASC');

        foreach ($query->rows as $result) {
            $order_data[$result['hour']] = [
                'hour' => $result['hour'],
                'total' => $result['total'],
            ];
        }

        return $order_data;
    }

    public function getTotalOrdersByWeek() {
        $implode = [];

        foreach ($this->config->get('config_complete_status') as $order_status_id) {
            $implode[] = "'" . (int) $order_status_id . "'";
        }

        $order_data = [];

        $date_start = strtotime('-' . date('w') . ' days');

        for ($i = 0; $i < 7; ++$i) {
            $date = date('Y-m-d', $date_start + ($i * 86400));

            $order_data[date('w', strtotime($date))] = [
                'day' => date('D', strtotime($date)),
                'total' => 0,
            ];
        }

        $query = $this->db->query('SELECT COUNT(*) AS total, date_added FROM `' . DB_PREFIX . 'order` WHERE order_status_id IN(' . implode(',', $implode) . ") AND DATE(date_added) >= DATE('" . $this->db->escape(date('Y-m-d', $date_start)) . "') GROUP BY DAYNAME(date_added)");

        foreach ($query->rows as $result) {
            $order_data[date('w', strtotime($result['date_added']))] = [
                'day' => date('D', strtotime($result['date_added'])),
                'total' => $result['total'],
            ];
        }

        return $order_data;
    }

    public function getTotalOrdersByMonth() {
        $implode = [];

        foreach ($this->config->get('config_complete_status') as $order_status_id) {
            $implode[] = "'" . (int) $order_status_id . "'";
        }

        $order_data = [];

        for ($i = 1; $i <= date('t'); ++$i) {
            $date = date('Y') . '-' . date('m') . '-' . $i;

            $order_data[date('j', strtotime($date))] = [
                'day' => date('d', strtotime($date)),
                'total' => 0,
            ];
        }

        $query = $this->db->query('SELECT COUNT(*) AS total, date_added FROM `' . DB_PREFIX . 'order` WHERE order_status_id IN(' . implode(',', $implode) . ") AND DATE(date_added) >= '" . $this->db->escape(date('Y') . '-' . date('m') . '-1') . "' GROUP BY DATE(date_added)");

        foreach ($query->rows as $result) {
            $order_data[date('j', strtotime($result['date_added']))] = [
                'day' => date('d', strtotime($result['date_added'])),
                'total' => $result['total'],
            ];
        }

        return $order_data;
    }

    public function getTotalOrdersByYear() {
        $implode = [];

        foreach ($this->config->get('config_complete_status') as $order_status_id) {
            $implode[] = "'" . (int) $order_status_id . "'";
        }

        $order_data = [];

        for ($i = 1; $i <= 12; ++$i) {
            $order_data[$i] = [
                'month' => date('M', mktime(0, 0, 0, $i)),
                'total' => 0,
            ];
        }

        $query = $this->db->query('SELECT COUNT(*) AS total, date_added FROM `' . DB_PREFIX . 'order` WHERE order_status_id IN(' . implode(',', $implode) . ') AND YEAR(date_added) = YEAR(NOW()) GROUP BY MONTH(date_added)');

        foreach ($query->rows as $result) {
            $order_data[date('n', strtotime($result['date_added']))] = [
                'month' => date('M', strtotime($result['date_added'])),
                'total' => $result['total'],
            ];
        }

        return $order_data;
    }

    public function getOrders($data = []) {
        $sql = 'SELECT MIN(o.date_added) AS date_start, MAX(o.date_added) AS date_end, COUNT(*) AS `orders`, SUM((SELECT SUM(op.quantity) FROM `' . DB_PREFIX . 'order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id)) AS products, SUM((SELECT SUM(ot.value) FROM `' . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'tax' GROUP BY ot.order_id)) AS tax, SUM(o.total) AS `total` FROM `" . DB_PREFIX . 'order` o';

        $sql .= ' INNER JOIN `' . DB_PREFIX . 'store` st on st.store_id = o.store_id ';

        if (!empty($data['filter_city'])) {
            $sql .= ' LEFT JOIN `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id ';
        }
        
        if (!empty($data['filter_customer'])) {
            $sql .= ' LEFT JOIN `' . DB_PREFIX . 'customer` cr on cr.customer_id = o.customer_id ';
        }

        if ($this->user->isVendor()) {
            $sql .= ' AND st.vendor_id = "' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " WHERE o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }

        if (!empty($data['filter_city'])) {
            $sql .= ' AND c.name LIKE "' . $data['filter_city'] . '%"';
        }
        
        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(cr.firstname,' ',cr.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_group'])) {
            $group = $data['filter_group'];
        } else {
            $group = 'week';
        }

        switch ($group) {
            case 'day':
                $sql .= ' GROUP BY YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added)';
                break;
            default:
            case 'week':
                $sql .= ' GROUP BY YEAR(o.date_added), WEEK(o.date_added)';
                break;
            case 'month':
                $sql .= ' GROUP BY YEAR(o.date_added), MONTH(o.date_added)';
                break;
            case 'year':
                $sql .= ' GROUP BY YEAR(o.date_added)';
                break;
        }

        $sql .= ' ORDER BY o.date_added DESC';

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        //echo $sql;die;

        $query = $this->db->query($sql);

        return $query->rows;
    }
    
    public function getOrdersbyDeliveryDate($data = []) {
        $sql = 'SELECT MIN(o.delivery_date) AS date_start, MAX(o.delivery_date) AS date_end, COUNT(*) AS `orders`, SUM((SELECT SUM(op.quantity) FROM `' . DB_PREFIX . 'order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id)) AS products, SUM((SELECT SUM(ot.value) FROM `' . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'tax' GROUP BY ot.order_id)) AS tax, SUM((SELECT SUM(ots.value) FROM ".DB_PREFIX."order_total ots WHERE ots.order_id = o.order_id AND ots.code = 'total' GROUP BY ots.order_id)) AS totals, SUM((SELECT SUM(rop.quantity) FROM ".DB_PREFIX."real_order_product rop WHERE rop.order_id = o.order_id GROUP BY rop.order_id)) AS realproducts, SUM(o.total) AS `total` FROM `" . DB_PREFIX . 'order` o';

        $sql .= ' INNER JOIN `' . DB_PREFIX . 'store` st on st.store_id = o.store_id ';

        if (!empty($data['filter_city'])) {
            $sql .= ' LEFT JOIN `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id ';
        }
        
        if (!empty($data['filter_customer'])) {
            $sql .= ' LEFT JOIN `' . DB_PREFIX . 'customer` cr on cr.customer_id = o.customer_id ';
        }

        if ($this->user->isVendor()) {
            $sql .= ' AND st.vendor_id = "' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " WHERE o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            // $sql .= " WHERE o.order_status_id > '0'";
            $sql .= " WHERE o.order_status_id not in (0,6,8,16)";

        }

        if (!empty($data['filter_city'])) {
            $sql .= ' AND c.name LIKE "' . $data['filter_city'] . '%"';
        }
        
        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(cr.firstname,' ',cr.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.delivery_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.delivery_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_group'])) {
            $group = $data['filter_group'];
        } else {
            $group = 'week';
        }

        switch ($group) {
            case 'day':
                $sql .= ' GROUP BY YEAR(o.delivery_date), MONTH(o.delivery_date), DAY(o.delivery_date)';
                break;
            default:
            case 'week':
                $sql .= ' GROUP BY YEAR(o.delivery_date), WEEK(o.delivery_date)';
                break;
            case 'month':
                $sql .= ' GROUP BY YEAR(o.delivery_date), MONTH(o.delivery_date)';
                break;
            case 'year':
                $sql .= ' GROUP BY YEAR(o.delivery_date)';
                break;
        }

        $sql .= ' ORDER BY o.delivery_date DESC';

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        //echo $sql;die;

        $query = $this->db->query($sql);

        return $query->rows;
    }
    
    public function getAccountManagerOrders($data = []) {
        $sql = 'SELECT MIN(o.delivery_date) AS date_start, MAX(o.delivery_date) AS date_end, COUNT(*) AS `orders`, SUM((SELECT SUM(op.quantity) FROM `' . DB_PREFIX . 'order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id)) AS products, SUM((SELECT SUM(ot.value) FROM `' . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'tax' GROUP BY ot.order_id)) AS tax, SUM(o.total) AS `total` FROM `" . DB_PREFIX . 'order` o';

        $sql .= ' INNER JOIN `' . DB_PREFIX . 'store` st on st.store_id = o.store_id ';

        if (!empty($data['filter_city'])) {
            $sql .= ' LEFT JOIN `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id ';
        }
        
        if (!empty($data['filter_customer']) || $this->user->isAccountManager()) {
            $sql .= ' INNER JOIN `' . DB_PREFIX . 'customer` cr on cr.customer_id = o.customer_id ';
        }

        if ($this->user->isVendor()) {
            $sql .= ' AND st.vendor_id = "' . $this->user->getId() . '"';
        }
        
        if ($this->user->isAccountManager()) {
            $sql .= ' AND cr.account_manager_id = "' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " WHERE o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }
        $sql .= " AND o.order_status_id NOT IN (6,8,0)";
        if (!empty($data['filter_city'])) {
            $sql .= ' AND c.name LIKE "' . $data['filter_city'] . '%"';
        }
        
        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(cr.firstname,' ',cr.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.delivery_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.delivery_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_group'])) {
            $group = $data['filter_group'];
        } else {
            $group = 'week';
        }

        switch ($group) {
            case 'day':
                $sql .= ' GROUP BY YEAR(o.delivery_date), MONTH(o.delivery_date), DAY(o.delivery_date)';
                break;
            default:
            case 'week':
                $sql .= ' GROUP BY YEAR(o.delivery_date), WEEK(o.delivery_date)';
                break;
            case 'month':
                $sql .= ' GROUP BY YEAR(o.delivery_date), MONTH(o.delivery_date)';
                break;
            case 'year':
                $sql .= ' GROUP BY YEAR(o.delivery_date)';
                break;
        }

        $sql .= ' ORDER BY o.delivery_date DESC';

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        //echo $sql;die;

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getproductmissingOrders($data = []) {
        //echo "<pre>";print_r($data);die;
        $sql = "SELECT c.name as city, o.firstname,o.lastname,o.order_id, o.delivery_date, o.delivery_timeslot, o.shipping_method, o.payment_method, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status,(SELECT os.color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS color, o.shipping_code, o.order_status_id,o.store_name,  o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . 'order` o ';

        $sql .= 'left join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id';
        $sql .= ' LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id) ';

        $sql .= ' LEFT JOIN ' . DB_PREFIX . 'real_order_product on(' . DB_PREFIX . 'real_order_product.order_id = o.order_id) ';

        $sql .= ' WHERE ' . DB_PREFIX . 'real_order_product.order_product_id is not null';

        if ($this->user->isVendor()) {
            $sql .= ' AND st.vendor_id = "' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " AND o.order_status_id > '0'";
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.delivery_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.delivery_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        //echo "<pre>";print_r($sql);die;
        if (!empty($data['filter_store'])) {
            $sql .= " AND o.store_id = '" . $data['filter_store'] . "'";
        }

        $sort_data = [
            'o.order_id',
            'customer',
            'status',
            'o.date_added',
            'o.date_modified',
            'o.total',
            'c.name',
        ];

        $sql .= ' GROUP BY o.order_id';

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY o.order_id';
        }

        if (isset($data['order']) && ('DESC' == $data['order']) || true) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        //echo "<pre>";print_r($sql);die;
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getAdvancedOrders($data = []) {
        //echo "<pre>";print_r($data);die;
        $sql = "SELECT c.name as city, o.firstname,o.lastname,o.order_id, o.delivery_date, o.delivery_timeslot, o.shipping_method, o.payment_method, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status,(SELECT os.color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS color, o.shipping_code, o.order_status_id,o.store_name,  o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . 'order` o ';

        $sql .= 'left join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id';
        $sql .= ' LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id) ';

        /*  if (isset($data['filter_order_status'])) {
          $implode = array();

          $order_statuses = explode(',', $data['filter_order_status']);

          foreach ($order_statuses as $order_status_id) {
          $implode[] = "o.order_status_id = '" . (int) $order_status_id . "'";
          }

          if ($implode) {
          $sql .= " WHERE (" . implode(" OR ", $implode) . ")";
          } else {

          }
          } else {
          $sql .= " WHERE o.order_status_id > '0'";
          }
         */
        if ($this->user->isVendor()) {
            $sql .= ' AND st.vendor_id = "' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " WHERE o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.delivery_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.delivery_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        //echo "<pre>";print_r($sql);die;
        if (!empty($data['filter_store'])) {
            $sql .= " AND o.store_id = '" . $data['filter_store'] . "'";
        }

        $sort_data = [
            'o.order_id',
            'customer',
            'status',
            'o.date_added',
            'o.date_modified',
            'o.total',
            'c.name',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY o.order_id';
        }

        if (isset($data['order']) && ('DESC' == $data['order']) || true) {
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

    public function getExcelAdvancedOrders($data = []) {
        //echo "<pre>";print_r($data);die;
        $sql = "SELECT c.name as city, o.firstname,o.lastname,o.order_id, o.delivery_date, o.delivery_timeslot, o.shipping_method, o.payment_method, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status,(SELECT os.color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS color, o.shipping_code, o.order_status_id,o.store_name,  o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . 'order` o ';

        $sql .= 'left join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id';
        $sql .= ' LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id) ';

        /*  if (isset($data['filter_order_status'])) {
          $implode = array();

          $order_statuses = explode(',', $data['filter_order_status']);

          foreach ($order_statuses as $order_status_id) {
          $implode[] = "o.order_status_id = '" . (int) $order_status_id . "'";
          }

          if ($implode) {
          $sql .= " WHERE (" . implode(" OR ", $implode) . ")";
          } else {

          }
          } else {
          $sql .= " WHERE o.order_status_id > '0'";
          }
         */
        if ($this->user->isVendor()) {
            $sql .= ' AND st.vendor_id = "' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " WHERE o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.delivery_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.delivery_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        //echo "<pre>";print_r($sql);die;
        if (!empty($data['filter_store'])) {
            $sql .= " AND o.store_id = '" . $data['filter_store'] . "'";
        }

        $sort_data = [
            'o.order_id',
            'customer',
            'status',
            'o.date_added',
            'o.date_modified',
            'o.total',
            'c.name',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY o.order_id';
        }

        if (isset($data['order']) && ('DESC' == $data['order']) || true) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalOrders($data = []) {
        $sql = 'SELECT Count(*) AS `total` FROM `' . DB_PREFIX . 'order` o';

        if (!empty($data['filter_group'])) {
            $group = $data['filter_group'];
        } else {
            $group = 'week';
        }

        switch ($group) {
            case 'day':
                $sql = 'SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added)) AS total FROM `' . DB_PREFIX . 'order` o';
                break;
            default:
            case 'week':
                $sql = 'SELECT COUNT(DISTINCT YEAR(o.date_added), WEEK(o.date_added)) AS total FROM `' . DB_PREFIX . 'order` o';
                break;
            case 'month':
                $sql = 'SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added)) AS total FROM `' . DB_PREFIX . 'order` o';
                break;
            case 'year':
                $sql = 'SELECT COUNT(DISTINCT YEAR(o.date_added)) AS total FROM `' . DB_PREFIX . 'order` o';
                break;
        }

        if (!empty($data['filter_city'])) {
            $sql .= ' LEFT JOIN `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id ';
        }
        
        if (!empty($data['filter_customer'])) {
            $sql .= ' LEFT JOIN `' . DB_PREFIX . 'customer` cr on cr.customer_id = o.customer_id ';
        }

        $sql .= ' INNER JOIN `' . DB_PREFIX . 'store` st on st.store_id = o.store_id ';

        if ($this->user->isVendor()) {
            $sql .= ' AND st.vendor_id = "' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " WHERE o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }

        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $this->db->escape($data['filter_city']) . "%'";
        }
        
        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(cr.firstname,' ',cr.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "%'";
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
    
    public function getTotalOrdersbyDeliveryDate($data = []) {
        $sql = 'SELECT Count(*) AS `total` FROM `' . DB_PREFIX . 'order` o';

        if (!empty($data['filter_group'])) {
            $group = $data['filter_group'];
        } else {
            $group = 'week';
        }

        switch ($group) {
            case 'day':
                $sql = 'SELECT COUNT(DISTINCT YEAR(o.delivery_date), MONTH(o.delivery_date), DAY(o.delivery_date)) AS total FROM `' . DB_PREFIX . 'order` o';
                break;
            default:
            case 'week':
                $sql = 'SELECT COUNT(DISTINCT YEAR(o.delivery_date), WEEK(o.delivery_date)) AS total FROM `' . DB_PREFIX . 'order` o';
                break;
            case 'month':
                $sql = 'SELECT COUNT(DISTINCT YEAR(o.delivery_date), MONTH(o.delivery_date)) AS total FROM `' . DB_PREFIX . 'order` o';
                break;
            case 'year':
                $sql = 'SELECT COUNT(DISTINCT YEAR(o.delivery_date)) AS total FROM `' . DB_PREFIX . 'order` o';
                break;
        }

        if (!empty($data['filter_city'])) {
            $sql .= ' LEFT JOIN `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id ';
        }
        
        if (!empty($data['filter_customer'])) {
            $sql .= ' LEFT JOIN `' . DB_PREFIX . 'customer` cr on cr.customer_id = o.customer_id ';
        }

        $sql .= ' INNER JOIN `' . DB_PREFIX . 'store` st on st.store_id = o.store_id ';

        if ($this->user->isVendor()) {
            $sql .= ' AND st.vendor_id = "' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " WHERE o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            // $sql .= " WHERE o.order_status_id > '0'";
            $sql .= " WHERE o.order_status_id not in (0,6,8,16)";

        }

        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $this->db->escape($data['filter_city']) . "%'";
        }
        
        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(cr.firstname,' ',cr.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "%'";
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
    
    public function AccountManagergetTotalOrders($data = []) {
        $sql = 'SELECT Count(*) AS `total` FROM `' . DB_PREFIX . 'order` o';

        if (!empty($data['filter_group'])) {
            $group = $data['filter_group'];
        } else {
            $group = 'week';
        }

        switch ($group) {
            case 'day':
                $sql = 'SELECT COUNT(DISTINCT YEAR(o.delivery_date), MONTH(o.delivery_date), DAY(o.delivery_date)) AS total FROM `' . DB_PREFIX . 'order` o';
                break;
            default:
            case 'week':
                $sql = 'SELECT COUNT(DISTINCT YEAR(o.delivery_date), WEEK(o.delivery_date)) AS total FROM `' . DB_PREFIX . 'order` o';
                break;
            case 'month':
                $sql = 'SELECT COUNT(DISTINCT YEAR(o.delivery_date), MONTH(o.delivery_date)) AS total FROM `' . DB_PREFIX . 'order` o';
                break;
            case 'year':
                $sql = 'SELECT COUNT(DISTINCT YEAR(o.delivery_date)) AS total FROM `' . DB_PREFIX . 'order` o';
                break;
        }

        if (!empty($data['filter_city'])) {
            $sql .= ' LEFT JOIN `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id ';
        }
        
        if (!empty($data['filter_customer']) || $this->user->isAccountManager()) {
            $sql .= ' INNER JOIN `' . DB_PREFIX . 'customer` cr on cr.customer_id = o.customer_id ';
        }

        $sql .= ' INNER JOIN `' . DB_PREFIX . 'store` st on st.store_id = o.store_id ';

        if ($this->user->isVendor()) {
            $sql .= ' AND st.vendor_id = "' . $this->user->getId() . '"';
        }
        
        if ($this->user->isAccountManager()) {
            $sql .= ' AND cr.account_manager_id = "' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " WHERE o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }
        $sql .= " AND o.order_status_id NOT IN (6,8,0)";
        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $this->db->escape($data['filter_city']) . "%'";
        }
        
        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(cr.firstname,' ',cr.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "%'";
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

    public function getTotalproductmissingOrders($data = []) {
        //echo "<pre>";print_r($data);die;
        $sql = "SELECT c.name as city, o.firstname,o.lastname,o.order_id, o.delivery_date, o.delivery_timeslot, o.shipping_method, o.payment_method, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status,(SELECT os.color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS color, o.shipping_code, o.order_status_id,o.store_name,  o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . 'order` o ';

        $sql .= 'left join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id';
        $sql .= ' LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id) ';

        /*  if (isset($data['filter_order_status'])) {
          $implode = array();

          $order_statuses = explode(',', $data['filter_order_status']);

          foreach ($order_statuses as $order_status_id) {
          $implode[] = "o.order_status_id = '" . (int) $order_status_id . "'";
          }

          if ($implode) {
          $sql .= " WHERE (" . implode(" OR ", $implode) . ")";
          } else {

          }
          } else {
          $sql .= " WHERE o.order_status_id > '0'";
          }
         */
        if ($this->user->isVendor()) {
            $sql .= ' AND st.vendor_id = "' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " WHERE o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.delivery_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.delivery_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        //echo "<pre>";print_r($sql);die;
        if (!empty($data['filter_store'])) {
            $sql .= " AND o.store_id = '" . $data['filter_store'] . "'";
        }

        $query = $this->db->query($sql);
        //echo "<pre>";print_r($query->num_rows);die;
        return $query->num_rows;
    }

    public function getTotalAdvancedOrders($data = []) {
        //echo "<pre>";print_r($data);die;
        $sql = "SELECT c.name as city, o.firstname,o.lastname,o.order_id, o.delivery_date, o.delivery_timeslot, o.shipping_method, o.payment_method, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status,(SELECT os.color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS color, o.shipping_code, o.order_status_id,o.store_name,  o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . 'order` o ';

        $sql .= 'left join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id';
        $sql .= ' LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id) ';

        /*  if (isset($data['filter_order_status'])) {
          $implode = array();

          $order_statuses = explode(',', $data['filter_order_status']);

          foreach ($order_statuses as $order_status_id) {
          $implode[] = "o.order_status_id = '" . (int) $order_status_id . "'";
          }

          if ($implode) {
          $sql .= " WHERE (" . implode(" OR ", $implode) . ")";
          } else {

          }
          } else {
          $sql .= " WHERE o.order_status_id > '0'";
          }
         */
        if ($this->user->isVendor()) {
            $sql .= ' AND st.vendor_id = "' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " WHERE o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.delivery_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.delivery_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        //echo "<pre>";print_r($sql);die;
        if (!empty($data['filter_store'])) {
            $sql .= " AND o.store_id = '" . $data['filter_store'] . "'";
        }

        $query = $this->db->query($sql);
        //echo "<pre>";print_r($query->num_rows);die;
        return $query->num_rows;
    }

    public function getTaxes($data = []) {
        $sql = 'SELECT MIN(o.date_added) AS date_start, MAX(o.date_added) AS date_end, ot.title, SUM(ot.value) AS total, COUNT(o.order_id) AS `orders` FROM `' . DB_PREFIX . 'order` o LEFT JOIN `' . DB_PREFIX . 'order_total` ot ON (ot.order_id = o.order_id)';

        if (!empty($data['filter_city'])) {
            $sql .= ' LEFT JOIN `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id ';
        }

        $sql .= " WHERE ot.code = 'tax' ";

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " AND o.order_status_id > '0'";
        }

        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $this->db->escape($data['filter_city']) . "%'";
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_group'])) {
            $group = $data['filter_group'];
        } else {
            $group = 'week';
        }

        switch ($group) {
            case 'day':
                $sql .= ' GROUP BY YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added), ot.title';
                break;
            default:
            case 'week':
                $sql .= ' GROUP BY YEAR(o.date_added), WEEK(o.date_added), ot.title';
                break;
            case 'month':
                $sql .= ' GROUP BY YEAR(o.date_added), MONTH(o.date_added), ot.title';
                break;
            case 'year':
                $sql .= ' GROUP BY YEAR(o.date_added), ot.title';
                break;
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

    public function getTotalTaxes($data = []) {
        if (!empty($data['filter_group'])) {
            $group = $data['filter_group'];
        } else {
            $group = 'week';
        }

        switch ($group) {
            case 'day':
                $sql = 'SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added), ot.title) AS total FROM `' . DB_PREFIX . 'order` o';
                break;
            default:
            case 'week':
                $sql = 'SELECT COUNT(DISTINCT YEAR(o.date_added), WEEK(o.date_added), ot.title) AS total FROM `' . DB_PREFIX . 'order` o';
                break;
            case 'month':
                $sql = 'SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), ot.title) AS total FROM `' . DB_PREFIX . 'order` o';
                break;
            case 'year':
                $sql = 'SELECT COUNT(DISTINCT YEAR(o.date_added), ot.title) AS total FROM `' . DB_PREFIX . 'order` o';
                break;
        }

        $sql .= ' LEFT JOIN `' . DB_PREFIX . 'order_total` ot ON (o.order_id = ot.order_id)';

        if (!empty($data['filter_city'])) {
            $sql .= ' LEFT JOIN `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id ';
        }

        $sql .= " WHERE ot.code = 'tax' ";

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " AND o.order_status_id > '0'";
        }

        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $this->db->escape($data['filter_city']) . "%'";
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

    public function getShipping($data = []) {
        $sql = 'SELECT MIN(o.date_added) AS date_start, MAX(o.date_added) AS date_end, ot.title, SUM(ot.value) AS total, COUNT(o.order_id) AS `orders` FROM `' . DB_PREFIX . 'order` o LEFT JOIN `' . DB_PREFIX . 'order_total` ot ON (o.order_id = ot.order_id) ';

        if (!empty($data['filter_city'])) {
            $sql .= ' left join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id ';
        }

        $sql .= " WHERE ot.code = 'shipping'";

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " AND o.order_status_id > '0'";
        }

        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $this->db->escape($data['filter_city']) . "%'";
        }

        if (!empty($data['filter_delivery_method'])) {
            $sql .= " AND ot.title LIKE '%" . $this->db->escape($data['filter_delivery_method']) . "%'";
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_group'])) {
            $group = $data['filter_group'];
        } else {
            $group = 'week';
        }

        switch ($group) {
            case 'day':
                $sql .= ' GROUP BY YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added), ot.title';
                break;
            default:
            case 'week':
                $sql .= ' GROUP BY YEAR(o.date_added), WEEK(o.date_added), ot.title';
                break;
            case 'month':
                $sql .= ' GROUP BY YEAR(o.date_added), MONTH(o.date_added), ot.title';
                break;
            case 'year':
                $sql .= ' GROUP BY YEAR(o.date_added), ot.title';
                break;
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

    public function getPayment($data = []) {
        $sql = 'SELECT MIN(o.date_added) AS date_start, MAX(o.date_added) AS date_end, o.payment_method as title, SUM(o.total) AS total, COUNT(o.order_id) AS `orders` FROM `' . DB_PREFIX . 'order` o LEFT JOIN `' . DB_PREFIX . 'order_total` ot ON (o.order_id = ot.order_id) ';

        if (!empty($data['filter_city'])) {
            $sql .= ' left join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id ';
        }

        $sql .= " WHERE ot.code = 'shipping'";

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " AND o.order_status_id > '0'";
        }

        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $this->db->escape($data['filter_city']) . "%'";
        }

        if (!empty($data['filter_payment'])) {
            $sql .= " AND o.payment_method LIKE '%" . $this->db->escape($data['filter_payment']) . "%'";
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_group'])) {
            $group = $data['filter_group'];
        } else {
            $group = 'week';
        }

        switch ($group) {
            case 'day':
                $sql .= ' GROUP BY YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added), o.payment_method';
                break;
            default:
            case 'week':
                $sql .= ' GROUP BY YEAR(o.date_added), WEEK(o.date_added), o.payment_method';
                break;
            case 'month':
                $sql .= ' GROUP BY YEAR(o.date_added), MONTH(o.date_added), o.payment_method';
                break;
            case 'year':
                $sql .= ' GROUP BY YEAR(o.date_added), o.payment_method';
                break;
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

    public function getTotalShipping($data = []) {
        if (!empty($data['filter_group'])) {
            $group = $data['filter_group'];
        } else {
            $group = 'week';
        }

        switch ($group) {
            case 'day':
                $sql = 'SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added), ot.title) AS total FROM `' . DB_PREFIX . 'order` o';
                break;
            default:
            case 'week':
                $sql = 'SELECT COUNT(DISTINCT YEAR(o.date_added), WEEK(o.date_added), ot.title) AS total FROM `' . DB_PREFIX . 'order` o';
                break;
            case 'month':
                $sql = 'SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), ot.title) AS total FROM `' . DB_PREFIX . 'order` o';
                break;
            case 'year':
                $sql = 'SELECT COUNT(DISTINCT YEAR(o.date_added), ot.title) AS total FROM `' . DB_PREFIX . 'order` o';
                break;
        }

        if (!empty($data['filter_city'])) {
            $sql .= ' left join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id ';
        }

        $sql .= ' LEFT JOIN `' . DB_PREFIX . 'order_total` ot ON (o.order_id = ot.order_id) ';

        $sql .= "WHERE ot.code = 'shipping'";

        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $this->db->escape($data['filter_city']) . "%'";
        }

        if (!empty($data['filter_delivery_method'])) {
            $sql .= " AND ot.title LIKE '%" . $this->db->escape($data['filter_delivery_method']) . "%'";
        }

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " AND order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " AND order_status_id > '0'";
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

    public function getTotalPayment($data = []) {
        if (!empty($data['filter_group'])) {
            $group = $data['filter_group'];
        } else {
            $group = 'week';
        }

        switch ($group) {
            case 'day':
                $sql = 'SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added), ot.title) AS total FROM `' . DB_PREFIX . 'order` o';
                break;
            default:
            case 'week':
                $sql = 'SELECT COUNT(DISTINCT YEAR(o.date_added), WEEK(o.date_added), ot.title) AS total FROM `' . DB_PREFIX . 'order` o';
                break;
            case 'month':
                $sql = 'SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), ot.title) AS total FROM `' . DB_PREFIX . 'order` o';
                break;
            case 'year':
                $sql = 'SELECT COUNT(DISTINCT YEAR(o.date_added), ot.title) AS total FROM `' . DB_PREFIX . 'order` o';
                break;
        }

        if (!empty($data['filter_city'])) {
            $sql .= ' left join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id ';
        }

        $sql .= ' LEFT JOIN `' . DB_PREFIX . 'order_total` ot ON (o.order_id = ot.order_id) ';

        $sql .= "WHERE ot.code = 'shipping'";

        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $this->db->escape($data['filter_city']) . "%'";
        }

        if (!empty($data['filter_payment'])) {
            $sql .= " AND o.payment_method LIKE '%" . $this->db->escape($data['filter_payment']) . "%'";
        }

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " AND order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " AND order_status_id > '0'";
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

    public function getOrdersCommission($data = []) {
        $sql = 'SELECT MIN(o.date_added) AS date_start,st.name as store, MAX(o.date_added) AS date_end, COUNT(*) AS `orders`,SUM(o.total) AS `total`,`commission`,`username` FROM `' . DB_PREFIX . 'order` o';

        $sql .= ' INNER JOIN `' . DB_PREFIX . 'store` st on st.store_id = o.store_id ';
        $sql .= ' INNER JOIN `' . DB_PREFIX . 'user` u on u.user_id = st.vendor_id ';

        $sql .= " WHERE o.order_status_id > '0'";
        $sql .= ' AND o.commsion_received = 1';

        if (!empty($data['filter_store'])) {
            $sql .= ' AND st.store_id = "' . $data['filter_store'] . '"';
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_vendor_name'])) {
            $sql .= " AND username LIKE '" . $this->db->escape($data['filter_vendor_name']) . "%'";
        }

        if (!empty($data['filter_group'])) {
            $group = $data['filter_group'];
        } else {
            $group = 'week';
        }
        $sql .= ' GROUP BY username';

        switch ($group) {
            case 'day':
                $sql .= ',YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added) ';
                break;
            default:
            case 'week':
                $sql .= ',YEAR(o.date_added), WEEK(o.date_added) ';
                break;
            case 'month':
                $sql .= ',YEAR(o.date_added), MONTH(o.date_added) ';
                break;
            case 'year':
                $sql .= ',YEAR(o.date_added)';
                break;
        }

        $sql .= ' ORDER BY o.date_added DESC';

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        //echo $sql;die;

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalOrdersCommission($data = []) {
        $sql = 'SELECT MIN(o.date_added) AS date_start, MAX(o.date_added) AS date_end, COUNT(*) AS `orders`,SUM(o.total) AS `total`,`commission`,`username` FROM `' . DB_PREFIX . 'order` o';

        $sql .= ' INNER JOIN `' . DB_PREFIX . 'store` st on st.store_id = o.store_id ';
        $sql .= ' INNER JOIN `' . DB_PREFIX . 'user` u on u.user_id = st.vendor_id ';

        $sql .= " WHERE o.order_status_id > '0'";
        $sql .= ' AND o.commsion_received = 1';

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_vendor_name'])) {
            $sql .= " AND username LIKE '" . $this->db->escape($data['filter_vendor_name']) . "%'";
        }

        if (!empty($data['filter_group'])) {
            $group = $data['filter_group'];
        } else {
            $group = 'week';
        }
        $sql .= ' GROUP BY username';
        switch ($group) {
            case 'day':
                $sql .= ',YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added) ';
                break;
            default:
            case 'week':
                $sql .= ',YEAR(o.date_added), WEEK(o.date_added) ';
                break;
            case 'month':
                $sql .= ',YEAR(o.date_added), MONTH(o.date_added) ';
                break;
            case 'year':
                $sql .= ',YEAR(o.date_added)';
                break;
        }

        $sql .= ' ORDER BY o.date_added DESC';

        $query = $this->db->query($sql);

        //echo count($query->rows);die;
        return count($query->rows);
    }

    public function getVendorOrdersCommission($data = []) {
        $sql = 'SELECT MIN(o.date_added) AS date_start,st.name as store, MAX(o.date_added) AS date_end, COUNT(*) AS `orders`,SUM(o.total) AS `total`,`commission`,`username` FROM `' . DB_PREFIX . 'order` o';

        $sql .= ' INNER JOIN `' . DB_PREFIX . 'store` st on st.store_id = o.store_id ';
        $sql .= ' INNER JOIN `' . DB_PREFIX . 'user` u on u.user_id = st.vendor_id ';

        $sql .= " WHERE o.order_status_id > '0'";
        $sql .= ' AND o.commsion_received = 1';

        if (!empty($data['filter_store'])) {
            $sql .= ' AND st.store_id = "' . $data['filter_store'] . '"';
        }

        if ($this->user->isVendor()) {
            $sql .= ' AND st.vendor_id = "' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_group'])) {
            $group = $data['filter_group'];
        } else {
            $group = 'week';
        }
        //$sql .= " GROUP BY username";

        switch ($group) {
            case 'day':
                $sql .= 'GROUP BY  YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added) ';
                break;
            default:
            case 'week':
                $sql .= 'GROUP BY  YEAR(o.date_added), WEEK(o.date_added) ';
                break;
            case 'month':
                $sql .= 'GROUP BY  YEAR(o.date_added), MONTH(o.date_added) ';
                break;
            case 'year':
                $sql .= 'GROUP BY  YEAR(o.date_added)';
                break;
        }

        $sql .= ' ORDER BY o.date_added DESC';

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }

        //echo $sql;die;

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getVendorTotalOrdersCommission($data = []) {
        $sql = 'SELECT MIN(o.date_added) AS date_start, MAX(o.date_added) AS date_end, COUNT(*) AS `orders`,SUM(o.total) AS `total`,`commission`,`username` FROM `' . DB_PREFIX . 'order` o';

        $sql .= ' INNER JOIN `' . DB_PREFIX . 'store` st on st.store_id = o.store_id ';
        $sql .= ' INNER JOIN `' . DB_PREFIX . 'user` u on u.user_id = st.vendor_id ';

        $sql .= " WHERE o.order_status_id > '0'";
        $sql .= ' AND o.commsion_received = 1';

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if (!empty($data['filter_vendor_name'])) {
            $sql .= " AND username LIKE '" . $this->db->escape($data['filter_vendor_name']) . "%'";
        }

        if (!empty($data['filter_group'])) {
            $group = $data['filter_group'];
        } else {
            $group = 'week';
        }
        //$sql .= " GROUP BY username";
        switch ($group) {
            case 'day':
                $sql .= 'GROUP BY YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added) ';
                break;
            default:
            case 'week':
                $sql .= 'GROUP BY YEAR(o.date_added), WEEK(o.date_added) ';
                break;
            case 'month':
                $sql .= 'GROUP BY YEAR(o.date_added), MONTH(o.date_added) ';
                break;
            case 'year':
                $sql .= 'GROUP BY YEAR(o.date_added)';
                break;
        }

        $sql .= ' ORDER BY o.date_added DESC';

        $query = $this->db->query($sql);
        //echo count($query->rows);die;
        return count($query->rows);
    }

    public function getQueryResult($filter_name, $config_vendor_group_ids) {
        $sql = 'select username, user_id from `' . DB_PREFIX . 'user` WHERE username LIKE "' . $filter_name . '%" And user_group_id IN (' . $config_vendor_group_ids . ') limit 5';

        return $this->db->query($sql)->rows;
    }

    public function getStoreData($filter_name) {
        $sql = 'select store_id,name from `' . DB_PREFIX . 'store`';
        $sql .= ' WHERE name LIKE "' . $filter_name . '%" ';

        if ($this->user->isVendor()) {
            $sql .= ' AND vendor_id = "' . $this->user->getId() . '"';
        }

        $sql .= ' LIMIT 5';

        return $this->db->query($sql)->rows;
    }

    public function getNonCancelledOrders($data = []) {
        $sql = "SELECT o.order_id, o.delivery_date, o.order_status_id,o.store_name,o.comment,  o.date_added,o.shipping_address, o.date_modified FROM `" . DB_PREFIX . 'order` o ';
        //$sql = "SELECT c.name as city, o.firstname,o.lastname,o.comment, (SELECT cust.company_name FROM hf7_customer cust WHERE o.customer_id = cust.customer_id ) AS company_name,o.order_id, o.delivery_date, o.delivery_timeslot, o.shipping_method, o.shipping_address, o.payment_method, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status,(SELECT os.color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS color, o.shipping_code, o.order_status_id,o.store_name,  o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified,o.po_number FROM `" . DB_PREFIX . "order` o ";
        // $sql .= 'left join `'.DB_PREFIX.'city` c on c.city_id = o.shipping_city_id';
        // $sql .= ' LEFT JOIN '.DB_PREFIX.'store on('.DB_PREFIX.'store.store_id = o.store_id) ';
        //o.order_status_id != '6'  And o.order_status_id != '15'  And
        if (!empty($data['filter_customer'])) {
            $sql .= ' LEFT JOIN `' . DB_PREFIX . 'customer` cr on cr.customer_id = o.customer_id ';
        }
        
        if (isset($data['filter_order_status_id'])) {
            $sql .= " WHERE  o.order_status_id > '0'";
        } else {
            $sql .= " WHERE o.order_status_id > '0' ";
        }

        if ($this->user->isVendor()) {
            $sql .= ' AND ' . DB_PREFIX . 'store.vendor_id="' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $data['filter_city'] . "%'";
        }
        
        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(cr.firstname,' ',cr.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "%'";
        }
        

        if (DATE($data['filter_date_start']) != DATE($data['filter_date_end'])) {
            if (!empty($data['filter_date_start'])) {
                $sql .= " AND DATE(o.date_added) >= DATE('" . ($data['filter_date_start']) . "')";
            }

            if (!empty($data['filter_date_end'])) {
                $sql .= " AND DATE(o.date_added) <= DATE('" . $this->db->escape($data['filter_date_end']) . "')";
            }
        } else {
            $sql .= " AND DATE(o.date_added) = DATE('" . ($data['filter_date_start']) . "')";
        }




        //     $sort_data = [
        //        'o.order_id',
        //        'customer',
        //        'status',
        //        'o.date_added',
        //        'o.date_modified',
        //        'o.total',
        //        'c.name',
        //    ];
        // if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
        //     $sql .= ' ORDER BY '.$data['sort'];
        // } else {
        //     $sql .= ' ORDER BY o.order_id';
        // }
        // if (isset($data['order']) && ('DESC' == $data['order'])) {
        //     $sql .= ' DESC';
        // } else {
        //     $sql .= ' ASC';
        // }



        $query = $this->db->query($sql);

        //  echo "<pre>";print_r($sql);die;


        return $query->rows;
    }
    
    public function getAccountManagerNonCancelledOrders($data = []) {
        $sql = "SELECT o.order_id, o.delivery_date, o.order_status_id,o.store_name,o.comment,  o.date_added,o.shipping_address, o.date_modified FROM `" . DB_PREFIX . 'order` o ';
        //$sql = "SELECT c.name as city, o.firstname,o.lastname,o.comment, (SELECT cust.company_name FROM hf7_customer cust WHERE o.customer_id = cust.customer_id ) AS company_name,o.order_id, o.delivery_date, o.delivery_timeslot, o.shipping_method, o.shipping_address, o.payment_method, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status,(SELECT os.color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS color, o.shipping_code, o.order_status_id,o.store_name,  o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified,o.po_number FROM `" . DB_PREFIX . "order` o ";
        // $sql .= 'left join `'.DB_PREFIX.'city` c on c.city_id = o.shipping_city_id';
        // $sql .= ' LEFT JOIN '.DB_PREFIX.'store on('.DB_PREFIX.'store.store_id = o.store_id) ';
         //o.order_status_id != '6'  And o.order_status_id != '15'  And
        if (!empty($data['filter_customer']) || $this->user->isAccountManager()) {
            $sql .= ' LEFT JOIN `' . DB_PREFIX . 'customer` cr on cr.customer_id = o.customer_id ';
        }
        
        if (isset($data['filter_order_status_id'])) {
            $sql .= " WHERE  o.order_status_id > '0'";
        } else {
            $sql .= " WHERE o.order_status_id > '0' ";
        }
        $sql .= " AND o.order_status_id NOT IN (6,8,0)";

        if ($this->user->isVendor()) {
            $sql .= ' AND ' . DB_PREFIX . 'store.vendor_id="' . $this->user->getId() . '"';
        }
        
        if ($this->user->isAccountManager()) {
            $sql .= ' AND cr.account_manager_id="' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $data['filter_city'] . "%'";
        }
        
        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(cr.firstname,' ',cr.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "%'";
        }
        

        if (DATE($data['filter_date_start']) != DATE($data['filter_date_end'])) {
            if (!empty($data['filter_date_start'])) {
                $sql .= " AND DATE(o.delivery_date) >= DATE('" . ($data['filter_date_start']) . "')";
            }

            if (!empty($data['filter_date_end'])) {
                $sql .= " AND DATE(o.delivery_date) <= DATE('" . $this->db->escape($data['filter_date_end']) . "')";
            }
        } else {
            $sql .= " AND DATE(o.delivery_date) = DATE('" . ($data['filter_date_start']) . "')";
        }




        //     $sort_data = [
        //        'o.order_id',
        //        'customer',
        //        'status',
        //        'o.date_added',
        //        'o.date_modified',
        //        'o.total',
        //        'c.name',
        //    ];
        // if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
        //     $sql .= ' ORDER BY '.$data['sort'];
        // } else {
        //     $sql .= ' ORDER BY o.order_id';
        // }
        // if (isset($data['order']) && ('DESC' == $data['order'])) {
        //     $sql .= ' DESC';
        // } else {
        //     $sql .= ' ASC';
        // }



        $query = $this->db->query($sql);

        //  echo "<pre>";print_r($sql);die;


        return $query->rows;
    }
    public function getstockoutOrders($data = []) {
        //echo "<pre>";print_r($data);die;
        $sql = "SELECT   o.firstname,o.lastname,o.order_id, o.delivery_date, o.delivery_timeslot, o.shipping_method, o.payment_method, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status,(SELECT os.color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS color, o.shipping_code, o.order_status_id,o.store_name,  o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . 'order` o ';

        // $sql .= 'left join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id';
        $sql .= '  JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id) ';

        //$sql .= ' LEFT JOIN ' . DB_PREFIX . 'real_order_product on(' . DB_PREFIX . 'real_order_product.order_id = o.order_id) ';

       // $sql .= ' WHERE ' . DB_PREFIX . 'real_order_product.order_product_id is not null';

        if ($this->user->isVendor()) {
            $sql .= ' AND st.vendor_id = "' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " AND o.order_status_id > '0'";
        }
        $sql .= " AND o.order_status_id NOT IN (0,6,8,16)";
        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.delivery_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.delivery_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        //echo "<pre>";print_r($sql);die;
        if (!empty($data['filter_store'])) {
            $sql .= " AND o.store_id = '" . $data['filter_store'] . "'";
        }

        $sort_data = [
            'o.order_id',
            'customer',
            'status',
            'o.date_added',
            'o.date_modified',
            'o.total',
            'c.name',
        ];

        $sql .= ' GROUP BY o.order_id';

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY o.order_id';
        }

        if (isset($data['order']) && ('DESC' == $data['order']) || true) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        // echo "<pre>";print_r($sql);die;
        $query = $this->db->query($sql);

        return $query->rows;
    }


    public function getstockoutOnlyOrders($data = []) {
        //echo "<pre>";print_r($data);die;
        $sql = "SELECT  o.order_id,o.store_name FROM `" . DB_PREFIX . 'order` o ';

        // $sql .= 'left join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id';
        $sql .= '  JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id) ';

        //$sql .= ' LEFT JOIN ' . DB_PREFIX . 'real_order_product on(' . DB_PREFIX . 'real_order_product.order_id = o.order_id) ';

       // $sql .= ' WHERE ' . DB_PREFIX . 'real_order_product.order_product_id is not null';

        if ($this->user->isVendor()) {
            $sql .= ' AND st.vendor_id = "' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " AND o.order_status_id > '0'";
        }
        $sql .= " AND o.order_status_id NOT IN (0,6,8,16)";
        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.delivery_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.delivery_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        //echo "<pre>";print_r($sql);die;
        if (!empty($data['filter_store'])) {
            $sql .= " AND o.store_id = '" . $data['filter_store'] . "'";
        }

        $sort_data = [
            'o.order_id',
            'customer',
            'status',
            'o.date_added',
            'o.date_modified',
            'o.total',
            'c.name',
        ];

        $sql .= ' GROUP BY o.order_id';

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY o.order_id';
        }

        if (isset($data['order']) && ('DESC' == $data['order']) || true) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        // echo "<pre>";print_r($sql);die;
        $query = $this->db->query($sql);

        return $query->rows;
    }


    public function getNonCancelledOrdersbyDeliveryDate($data = []) {
        $sql = "SELECT o.order_id, o.delivery_date, o.order_status_id,o.store_name,o.comment,  o.date_added,o.shipping_address, o.date_modified FROM `" . DB_PREFIX . 'order` o ';
         
        if (!empty($data['filter_customer'])) {
            $sql .= ' LEFT JOIN `' . DB_PREFIX . 'customer` cr on cr.customer_id = o.customer_id ';
        }
         

        if (!empty($data['filter_order_status_id'])) {
            $sql .= " WHERE o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            // $sql .= " WHERE o.order_status_id > '0'";
            $sql .= " WHERE o.order_status_id not in (0,6,8,16)";

        }


        if ($this->user->isVendor()) {
            $sql .= ' AND ' . DB_PREFIX . 'store.vendor_id="' . $this->user->getId() . '"';
        }

        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '" . $data['filter_city'] . "%'";
        }
        
        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(cr.firstname,' ',cr.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "%'";
        }
        

        if (DATE($data['filter_date_start']) != DATE($data['filter_date_end'])) {
            if (!empty($data['filter_date_start'])) {
                $sql .= " AND DATE(o.delivery_date) >= DATE('" . ($data['filter_date_start']) . "')";
            }

            if (!empty($data['filter_date_end'])) {
                $sql .= " AND DATE(o.delivery_date) <= DATE('" . $this->db->escape($data['filter_date_end']) . "')";
            }
        } else {
            $sql .= " AND DATE(o.delivery_date) = DATE('" . ($data['filter_date_start']) . "')";
        }



 

        $query = $this->db->query($sql);

        //  echo "<pre>";print_r($sql);die;


        return $query->rows;
    }
    
}
