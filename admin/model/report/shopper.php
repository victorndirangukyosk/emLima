<?php

class ModelReportShopper extends Model
{
    public function getShoppers($data = [])
    {
        $shopper_group_id = $this->config->get('config_shopper_group_ids');

        $sql = 'SELECT c.name as city, MIN(o.shppr_delivered_date) as date_from, MAX(o.shppr_delivered_date) as date_to, ';
        $sql .= "u.status, u.user_id, CONCAT(u.firstname, ' ', u.lastname) AS shopper, u.email, COUNT(o.order_id) AS orders, SUM(o.shopper_commision) AS commision FROM `".DB_PREFIX.'user` u ';
        $sql .= 'LEFT JOIN `'.DB_PREFIX.'vendor_order` o on o.shopper_id = u.user_id ';
        $sql .= 'LEFT JOIN `'.DB_PREFIX.'city` c on c.city_id = u.city_id ';
        $sql .= 'WHERE u.user_group_id IN ('.$shopper_group_id.') ';

        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '".$this->db->escape($data['filter_city'])."%'";
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.shppr_delivered_date) >= '".$this->db->escape($data['filter_date_start'])."'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.shppr_delivered_date) <= '".$this->db->escape($data['filter_date_end'])."'";
        }

        if (!empty($data['filter_vendor'])) {
            $sql .= " AND CONCAT(u.firstname, ' ', u.lastname) LIKE '".$this->db->escape($data['filter_vendor'])."%'";
        }

        if (!empty($data['filter_group'])) {
            $group = $data['filter_group'];
        } else {
            $group = 'week';
        }

        switch ($group) {
            case 'day':
                $sql .= ' GROUP BY u.user_id, DAY(o.shppr_delivered_date)';
                break;
            default:
            case 'week':
                $sql .= ' GROUP BY u.user_id, WEEK(o.shppr_delivered_date)';
                break;
            case 'month':
                $sql .= ' GROUP BY u.user_id, MONTH(o.shppr_delivered_date)';
                break;
            case 'year':
                $sql .= ' GROUP BY u.user_id, YEAR(o.shppr_delivered_date)';
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

    public function getTotal($data = [])
    {
        $shopper_group_id = $this->config->get('config_shopper_group_ids');

        $sql = 'SELECT c.name as city, MIN(o.shppr_delivered_date) as date_from, MAX(o.shppr_delivered_date) as date_to, ';
        $sql .= "u.status, u.user_id, CONCAT(u.firstname, ' ', u.lastname) AS shopper, u.email, COUNT(o.order_id) AS orders, SUM(o.shopper_commision) AS commision FROM `".DB_PREFIX.'user` u ';
        $sql .= 'LEFT JOIN `'.DB_PREFIX.'vendor_order` o on o.shopper_id = u.user_id ';
        $sql .= 'LEFT JOIN `'.DB_PREFIX.'city` c on c.city_id = u.city_id ';
        $sql .= 'WHERE u.user_group_id IN ('.$shopper_group_id.') ';

        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '".$this->db->escape($data['filter_city'])."%'";
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.shppr_delivered_date) >= '".$this->db->escape($data['filter_date_start'])."'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.shppr_delivered_date) <= '".$this->db->escape($data['filter_date_end'])."'";
        }

        if (!empty($data['filter_vendor'])) {
            $sql .= " AND CONCAT(u.firstname, ' ', u.lastname) LIKE '".$this->db->escape($data['filter_vendor'])."%'";
        }

        if (!empty($data['filter_group'])) {
            $group = $data['filter_group'];
        } else {
            $group = 'week';
        }

        switch ($group) {
            case 'day':
                $sql .= ' GROUP BY u.user_id, DAY(o.shppr_delivered_date)';
                break;
            default:
            case 'week':
                $sql .= ' GROUP BY u.user_id, WEEK(o.shppr_delivered_date)';
                break;
            case 'month':
                $sql .= ' GROUP BY u.user_id, MONTH(o.shppr_delivered_date)';
                break;
            case 'year':
                $sql .= ' GROUP BY u.user_id, YEAR(o.shppr_delivered_date)';
                break;
        }

        $query = $this->db->query($sql);

        return $query->num_rows;
    }

    public function getAssigned($shopper_id, $date_start, $date_end, $group)
    {
        $complete_status_ids = '('.implode(',', $this->config->get('config_complete_status')).')';

        $sql = "SELECT COUNT(vendor_order_id) AS total, HOUR(date_added) AS hour, CONCAT(MONTHNAME(date_added), ' ', YEAR(date_added)) AS month, ";
        $sql .= 'YEAR(date_added) AS year, DATE(date_added) AS date  FROM `'.DB_PREFIX.'shopper_order_log` ';
        $sql .= "WHERE action='assigned' "; //order_status_id IN " . $complete_status_ids . "

        if ($shopper_id) {
            $sql .= "AND shopper_id='".$shopper_id."' ";
        }

        $sql .= "AND DATE(date_added) BETWEEN '".$this->db->escape($date_start)."' AND '".$this->db->escape($date_end)."' ";
        $sql .= 'GROUP BY '.$group.'(date_added) ORDER BY date_added ASC';

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getRejected($shopper_id, $date_start, $date_end, $group)
    {
        $complete_status_ids = '('.implode(',', $this->config->get('config_complete_status')).')';

        $sql = "SELECT COUNT(vendor_order_id) AS total, HOUR(date_added) AS hour, CONCAT(MONTHNAME(date_added), ' ', YEAR(date_added)) AS month, ";
        $sql .= 'YEAR(date_added) AS year, DATE(date_added) AS date  FROM `'.DB_PREFIX.'shopper_order_log` ';
        $sql .= "WHERE action='rejected' "; //order_status_id IN " . $complete_status_ids . " AND

        if ($shopper_id) {
            $sql .= "AND shopper_id='".$shopper_id."' ";
        }

        $sql .= 'AND DATE(date_added) ';
        $sql .= "BETWEEN '".$this->db->escape($date_start)."' AND '".$this->db->escape($date_end)."' ";
        $sql .= 'GROUP BY '.$group.'(date_added) ORDER BY date_added ASC';

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getFullfilled($shopper_id, $date_start, $date_end, $group)
    {
        $complete_status_ids = '('.implode(',', $this->config->get('config_complete_status')).')';

        $sql = "SELECT COUNT(vendor_order_id) AS total, HOUR(date_added) AS hour, CONCAT(MONTHNAME(date_added), ' ', YEAR(date_added)) AS month, ";
        $sql .= 'YEAR(date_added) AS year, DATE(date_added) AS date  FROM `'.DB_PREFIX.'shopper_order_log` ';
        $sql .= "WHERE action='fullfilled' "; // order_status_id IN " . $complete_status_ids . " AND

        if ($shopper_id) {
            $sql .= "AND shopper_id='".$shopper_id."' ";
        }

        $sql .= "AND DATE(date_added) BETWEEN '".$this->db->escape($date_start)."' AND '".$this->db->escape($date_end)."' ";
        $sql .= 'GROUP BY '.$group.'(date_added) ORDER BY date_added ASC';

        $query = $this->db->query($sql);

        return $query->rows;
    }
}
