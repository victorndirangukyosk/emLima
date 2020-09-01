<?php

class ModelReportReturn extends Model
{
    public function getReturns($data = [])
    {
        $sql = 'SELECT MIN(r.date_added) AS date_start, MAX(r.date_added) AS date_end, COUNT(r.return_id) AS `returns` FROM `'.DB_PREFIX.'return` r';

        $sql .= ' inner join `'.DB_PREFIX.'order` o on o.order_id = r.order_id ';
        $sql .= ' inner join `'.DB_PREFIX.'store` st on st.store_id = o.store_id ';

        if (!empty($data['filter_city'])) {
            $sql .= ' inner join `'.DB_PREFIX.'city` c on c.city_id = o.shipping_city_id ';
        }

        if (!empty($data['filter_return_status_id'])) {
            $sql .= " WHERE r.return_status_id = '".(int) $data['filter_return_status_id']."'";
        } else {
            $sql .= " WHERE r.return_status_id > '0'";
        }

        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '".$this->db->escape($data['filter_city'])."%'";
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(r.date_added) >= '".$this->db->escape($data['filter_date_start'])."'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(r.date_added) <= '".$this->db->escape($data['filter_date_end'])."'";
        }

        if ($this->user->isVendor()) {
            $sql .= ' AND st.vendor_id = "'.$this->user->getId().'"';
        }

        if (isset($data['filter_group'])) {
            $group = $data['filter_group'];
        } else {
            $group = 'week';
        }

        switch ($group) {
            case 'day':
                $sql .= ' GROUP BY YEAR(r.date_added), MONTH(r.date_added), DAY(r.date_added)';
                break;
            default:
            case 'week':
                $sql .= ' GROUP BY YEAR(r.date_added), WEEK(r.date_added)';
                break;
            case 'month':
                $sql .= ' GROUP BY YEAR(r.date_added), MONTH(r.date_added)';
                break;
            case 'year':
                $sql .= ' GROUP BY YEAR(r.date_added)';
                break;
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

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getReportReturns($data = [])
    {
        $sql = "SELECT *, CONCAT(r.firstname, ' ', r.lastname) AS customer, (SELECT o.store_id FROM ".DB_PREFIX.'order o WHERE o.order_id = r.order_id) AS store_id  , (SELECT rs.name FROM '.DB_PREFIX."return_status rs WHERE rs.return_status_id = r.return_status_id AND rs.language_id = '".(int) $this->config->get('config_language_id')."') AS status FROM `".DB_PREFIX.'return` r';

        $implode = [];

        if (isset($data['filter_return_id'])) {
            $implode[] = "r.return_id = '".(int) $data['filter_return_id']."'";
        }

        if (!empty($data['filter_order_id'])) {
            $implode[] = "r.order_id = '".(int) $data['filter_order_id']."'";
        }

        if (!empty($data['filter_date_start'])) {
            $implode[] = " DATE(r.date_added) >= '".$this->db->escape($data['filter_date_start'])."'";
        }

        if (!empty($data['filter_date_end'])) {
            $implode[] = " DATE(r.date_added) <= '".$this->db->escape($data['filter_date_end'])."'";
        }

        if (!empty($data['filter_customer'])) {
            $implode[] = "CONCAT(r.firstname, ' ', r.lastname) LIKE '".$this->db->escape($data['filter_customer'])."%'";
        }

        if (!empty($data['filter_return_status_id'])) {
            $implode[] = "r.return_status_id = '".(int) $data['filter_return_status_id']."'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(r.date_added) = DATE('".$this->db->escape($data['filter_date_added'])."')";
        }

        if (!empty($data['filter_date_modified'])) {
            $implode[] = "DATE(r.date_modified) = DATE('".$this->db->escape($data['filter_date_modified'])."')";
        }

        /*if (!empty($data['filter_store'])) {
            $implode[] = "r.store_id = '" . $data['filter_store'] . "'";
        }*/

        if ($implode) {
            $sql .= ' WHERE '.implode(' AND ', $implode);
        }

        $sql .= ' ORDER BY r.order_id DESC';

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT '.(int) $data['start'].','.(int) $data['limit'];
        }

        //echo "<pre>";print_r($sql);die;
        $query = $this->db->query($sql);

        //echo "<pre>";print_r($query);die;
        return $query->rows;
    }

    public function getReportTotalReturns($data = [])
    {
        $sql = "SELECT *, CONCAT(r.firstname, ' ', r.lastname) AS customer, (SELECT o.store_id FROM ".DB_PREFIX.'order o WHERE o.order_id = r.order_id) AS store_id  , (SELECT rs.name FROM '.DB_PREFIX."return_status rs WHERE rs.return_status_id = r.return_status_id AND rs.language_id = '".(int) $this->config->get('config_language_id')."') AS status FROM `".DB_PREFIX.'return` r';

        /*'SELECT *,customer, store_id, status FROM ('.

        "SELECT *, CONCAT(r.firstname, ' ', r.lastname) AS customer, (SELECT o.store_id FROM ". DB_PREFIX ."order o WHERE o.order_id = r.order_id) AS store_id  , (SELECT rs.name FROM " . DB_PREFIX . "return_status rs WHERE rs.return_status_id = r.return_status_id AND rs.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status FROM `" . DB_PREFIX . "return` r ) ";*/

        //echo "<pre>";print_r($sql);die;
        $implode = [];

        if (isset($data['filter_return_id'])) {
            $implode[] = "r.return_id = '".(int) $data['filter_return_id']."'";
        }

        if (!empty($data['filter_order_id'])) {
            $implode[] = "r.order_id = '".(int) $data['filter_order_id']."'";
        }

        if (!empty($data['filter_date_start'])) {
            $implode[] = " DATE(r.date_added) >= '".$this->db->escape($data['filter_date_start'])."'";
        }

        if (!empty($data['filter_date_end'])) {
            $implode[] = " DATE(r.date_added) <= '".$this->db->escape($data['filter_date_end'])."'";
        }

        if (!empty($data['filter_customer'])) {
            $implode[] = "CONCAT(r.firstname, ' ', r.lastname) LIKE '".$this->db->escape($data['filter_customer'])."%'";
        }

        if (!empty($data['filter_return_status_id'])) {
            $implode[] = "r.return_status_id = '".(int) $data['filter_return_status_id']."'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(r.date_added) = DATE('".$this->db->escape($data['filter_date_added'])."')";
        }

        if (!empty($data['filter_date_modified'])) {
            $implode[] = "DATE(r.date_modified) = DATE('".$this->db->escape($data['filter_date_modified'])."')";
        }

        /*if (!empty($data['filter_store'])) {
            $implode[] = "r.store_id = '" . $data['filter_store'] . "'";
        }*/

        if ($implode) {
            $sql .= ' WHERE '.implode(' AND ', $implode);
        }

        //echo "<pre>";print_r($sql);die;
        $query = $this->db->query($sql);

        //echo "<pre>";print_r($query);die;
        return $query->num_rows;
    }

    public function getTotalReturns($data = [])
    {
        if (!empty($data['filter_group'])) {
            $group = $data['filter_group'];
        } else {
            $group = 'week';
        }

        switch ($group) {
            case 'day':
                $sql = 'SELECT COUNT(DISTINCT YEAR(r.date_added), MONTH(r.date_added), DAY(r.date_added)) AS total FROM `'.DB_PREFIX.'return` r ';
                break;
            default:
            case 'week':
                $sql = 'SELECT COUNT(DISTINCT YEAR(r.date_added), WEEK(r.date_added)) AS total FROM `'.DB_PREFIX.'return` r ';
                break;
            case 'month':
                $sql = 'SELECT COUNT(DISTINCT YEAR(r.date_added), MONTH(r.date_added)) AS total FROM `'.DB_PREFIX.'return` r ';
                break;
            case 'year':
                $sql = 'SELECT COUNT(DISTINCT YEAR(r.date_added)) AS total FROM `'.DB_PREFIX.'return` r ';
                break;
        }
        $sql .= ' inner join `'.DB_PREFIX.'order` o on o.order_id = r.order_id ';
        $sql .= ' inner join `'.DB_PREFIX.'store` st on st.store_id = o.store_id ';

        if (!empty($data['filter_city'])) {
            $sql .= ' inner join `'.DB_PREFIX.'city` c on c.city_id = o.shipping_city_id ';
        }

        if (!empty($data['filter_return_status_id'])) {
            $sql .= " WHERE r.return_status_id = '".(int) $data['filter_return_status_id']."'";
        } else {
            $sql .= " WHERE r.return_status_id > '0'";
        }

        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '".$this->db->escape($data['filter_city'])."%'";
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(r.date_added) >= '".$this->db->escape($data['filter_date_start'])."'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(r.date_added) <= '".$this->db->escape($data['filter_date_end'])."'";
        }

        if ($this->user->isVendor()) {
            $sql .= ' AND st.vendor_id = "'.$this->user->getId().'"';
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getCities($filter_name)
    {
        return $this->db->query('select * from `'.DB_PREFIX.'city` WHERE name LIKE "'.$filter_name.'%" order by sort_order')->rows;
    }

    public function getCombinedReportReturns($data = [])
    {
        $sql = "SELECT *, CONCAT(r.firstname, ' ', r.lastname) AS customer, (SELECT o.store_id FROM ".DB_PREFIX.'order o WHERE o.order_id = r.order_id) AS store_id  , (SELECT rs.name FROM '.DB_PREFIX."return_status rs WHERE rs.return_status_id = r.return_status_id AND rs.language_id = '".(int) $this->config->get('config_language_id')."') AS status FROM `".DB_PREFIX.'return` r';

        $implode = [];

        if (isset($data['filter_return_id'])) {
            $implode[] = "r.return_id = '".(int) $data['filter_return_id']."'";
        }

        if (!empty($data['filter_order_id'])) {
            $implode[] = "r.order_id = '".(int) $data['filter_order_id']."'";
        }

        if (!empty($data['filter_date_start'])) {
            $implode[] = " DATE(r.date_added) >= '".$this->db->escape($data['filter_date_start'])."'";
        }

        if (!empty($data['filter_date_end'])) {
            $implode[] = " DATE(r.date_added) <= '".$this->db->escape($data['filter_date_end'])."'";
        }

        if (!empty($data['filter_customer'])) {
            $implode[] = "CONCAT(r.firstname, ' ', r.lastname) LIKE '".$this->db->escape($data['filter_customer'])."%'";
        }

        if (!empty($data['filter_return_status_id'])) {
            $implode[] = "r.return_status_id = '".(int) $data['filter_return_status_id']."'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(r.date_added) = DATE('".$this->db->escape($data['filter_date_added'])."')";
        }

        if (!empty($data['filter_date_modified'])) {
            $implode[] = "DATE(r.date_modified) = DATE('".$this->db->escape($data['filter_date_modified'])."')";
        }

        /*if (!empty($data['filter_store'])) {
            $implode[] = "r.store_id = '" . $data['filter_store'] . "'";
        }*/

        if ($implode) {
            $sql .= ' WHERE '.implode(' AND ', $implode);
        }

        $sql .= ' ORDER BY r.order_id DESC';

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT '.(int) $data['start'].','.(int) $data['limit'];
        }

        //echo "<pre>";print_r($sql);die;
        $query = $this->db->query($sql);

        //echo "<pre>";print_r($query);die;
        return $query->rows;
    }

    public function getCombinedReportTotalReturns($data = [])
    {
        $sql = "SELECT *, CONCAT(r.firstname, ' ', r.lastname) AS customer, (SELECT o.store_id FROM ".DB_PREFIX.'order o WHERE o.order_id = r.order_id) AS store_id  , (SELECT rs.name FROM '.DB_PREFIX."return_status rs WHERE rs.return_status_id = r.return_status_id AND rs.language_id = '".(int) $this->config->get('config_language_id')."') AS status FROM `".DB_PREFIX.'return` r';

        //echo "<pre>";print_r($sql);die;
        $implode = [];

        if (isset($data['filter_return_id'])) {
            $implode[] = "r.return_id = '".(int) $data['filter_return_id']."'";
        }

        if (!empty($data['filter_order_id'])) {
            $implode[] = "r.order_id = '".(int) $data['filter_order_id']."'";
        }

        if (!empty($data['filter_date_start'])) {
            $implode[] = " DATE(r.date_added) >= '".$this->db->escape($data['filter_date_start'])."'";
        }

        if (!empty($data['filter_date_end'])) {
            $implode[] = " DATE(r.date_added) <= '".$this->db->escape($data['filter_date_end'])."'";
        }

        if (!empty($data['filter_customer'])) {
            $implode[] = "CONCAT(r.firstname, ' ', r.lastname) LIKE '".$this->db->escape($data['filter_customer'])."%'";
        }

        if (!empty($data['filter_return_status_id'])) {
            $implode[] = "r.return_status_id = '".(int) $data['filter_return_status_id']."'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(r.date_added) = DATE('".$this->db->escape($data['filter_date_added'])."')";
        }

        if (!empty($data['filter_date_modified'])) {
            $implode[] = "DATE(r.date_modified) = DATE('".$this->db->escape($data['filter_date_modified'])."')";
        }

        /*if (!empty($data['filter_store'])) {
            $implode[] = "r.store_id = '" . $data['filter_store'] . "'";
        }*/

        if ($implode) {
            $sql .= ' WHERE '.implode(' AND ', $implode);
        }

        //echo "<pre>";print_r($sql);die;
        $query = $this->db->query($sql);

        //echo "<pre>";print_r($query);die;
        return $query->num_rows;
    }
}
