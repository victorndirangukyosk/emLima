<?php

class ModelReportUser extends Model {

    public function getUserActivities($data = []) {
        $sql = 'SELECT ca.activity_id, ca.user_id, ca.key, ca.data, ca.ip, ca.date_added,ca.order_id,cust.company_name FROM ' . DB_PREFIX . 'user_activity ca LEFT JOIN ' . DB_PREFIX . 'user c ON (ca.user_id = c.user_id)  LEFT OUTER JOIN ' . DB_PREFIX . 'customer cust ON (cust.customer_id = ca.customer_id) ';

        $implode = [];

        if (!empty($data['filter_user'])) {
            //$implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '" . $this->db->escape($data['filter_user']) . "'";
            $implode[] = "c.username LIKE '" . $this->db->escape($data['filter_user']) . "'";
        }
        
        if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '" . $this->db->escape($data['filter_name']) . "'";
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
        if (!empty($data['filter_key'])) {
            $implode[] = "ca.key LIKE '" . $this->db->escape($data['filter_key']) . "'";
        }

        if (!empty($data['filter_company'])) {
            $implode[] ="  cust.company_name LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        if (!empty($data['filter_customer'])) {
            $implode[] =" CONCAT(cust.firstname, ' ', cust.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
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

        //    echo "<pre>";print_r($sql);die;


        
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalUserActivities($data = []) {
        $sql = 'SELECT COUNT(*) AS total FROM `' . DB_PREFIX . 'user_activity` ca LEFT JOIN ' . DB_PREFIX . 'user c ON (ca.user_id = c.user_id)  LEFT OUTER JOIN ' . DB_PREFIX . 'customer cust ON (cust.customer_id = ca.customer_id) ';

        $implode = [];

        if (!empty($data['filter_user'])) {
            //$implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '" . $this->db->escape($data['filter_user']) . "'";
            $implode[] = "c.username LIKE '" . $this->db->escape($data['filter_user']) . "'";
        }
        
        if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '" . $this->db->escape($data['filter_name']) . "'";
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

        if (!empty($data['filter_key'])) {
            $implode[] = "ca.key LIKE '" . $this->db->escape($data['filter_key']) . "'";
        }

        if (!empty($data['filter_company'])) {
            $sql .= " AND cust.company_name LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(cust.firstname, ' ', cust.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
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

        $sql = 'SELECT distinct ca.key  FROM ' . DB_PREFIX . "user_activity ca";

        $query = $this->db->query($sql);

        return $query->rows;
    }

}
