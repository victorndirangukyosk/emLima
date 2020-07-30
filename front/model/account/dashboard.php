<?php

class ModelAccountDashboard extends Model {

    public function getCustomerDashboardData($customer_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int) $customer_id . "'");

        return $query->row;
    }

    public function getCustomerOtherInfo($customer_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_other_info WHERE customer_id = '" . (int) $customer_id . "'");

        return $query->row;
    }

    public function getTotalOrders($customer_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` o WHERE customer_id = '" . (int) $customer_id . "' AND o.order_status_id > '0' ");

        //return $query;
        return $query->row['total'];
    }

    public function getOrders($customer_id) {
        $query = $this->db->query("SELECT order_id, date_added   FROM `" . DB_PREFIX . "order` o WHERE customer_id = '" . (int) $customer_id . "' AND o.order_status_id > '0'  order by date_added ASC");

        //return $query;
        return $query->rows;
    }

    public function getRecentOrders($customer_id) {
        $query = $this->db->query("SELECT o.order_id, o.invoice_no, DATE_FORMAT(o.delivery_date, '%d/%m/%Y') AS delivery_date, DATE_FORMAT(o.date_added, '%d/%m/%Y') AS date_added, o.order_status_id, os.name FROM " . DB_PREFIX . "order AS o JOIN " . DB_PREFIX . "order_status AS os ON (o.order_status_id = os.order_status_id)  WHERE o.customer_id = '" . (int) $customer_id . "' AND o.order_status_id > '0'  order by o.date_added ASC");

        //return $query;
        return $query->rows;
    }

    public function getRecentActivity($customer_id) {
        $query = $this->db->query("SELECT o.order_id, o.invoice_no, DATE_FORMAT(o.delivery_date, '%d/%m/%Y') AS delivery_date, DATE_FORMAT(o.date_added, '%d/%m/%Y %H:%i:%s') AS date_added, o.order_status_id, os.name, o.store_id, o.store_name, o.total FROM " . DB_PREFIX . "order AS o JOIN " . DB_PREFIX . "order_status AS os ON (o.order_status_id = os.order_status_id)  WHERE o.customer_id = '" . (int) $customer_id . "' AND o.order_status_id > '0'  order by o.date_added ASC");

        //return $query;
        return $query->rows;
    }

    public function getBuyingPattern($customer_id) {
        $query = $this->db->query("SELECT o.order_id, o.invoice_no, DATE_FORMAT(o.delivery_date, '%d/%m/%Y') AS delivery_date, DATE_FORMAT(o.date_added, '%d/%m/%Y %H:%i:%s') AS date_added, o.order_status_id, os.name, o.store_id, o.store_name, o.total FROM " . DB_PREFIX . "order AS o JOIN " . DB_PREFIX . "order_status AS os ON (o.order_status_id = os.order_status_id)  WHERE o.customer_id = '" . (int) $customer_id . "' AND o.order_status_id > '0' AND DATE_FORMAT(o.date_added, '%Y-%m-%d') >= DATE(NOW()) - INTERVAL 7 DAY order by o.date_added ASC");

        //return $query;
        return $query->rows;
    }

    public function getMostPurchased($customer_id) {
        // $complete_status_ids = '('.implode(',', $this->config->get('config_complete_status')).')';
        //echo "<pre>";print_r($complete_status_ids);die;
        $date = date('Y-m-d', strtotime('-30 day'));
        $query = $this->db->query("SELECT SUM( op.quantity )AS total, op.product_id,op.general_product_id, pd.name,op.unit FROM " . DB_PREFIX . "order_product AS op LEFT JOIN " . DB_PREFIX . "order AS o ON ( op.order_id = o.order_id ) LEFT JOIN  " . DB_PREFIX . "product_description AS pd ON (op.general_product_id = pd.product_id)  WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND o.customer_id = " . $customer_id . " AND o.date_added >= " . $date . " GROUP BY pd.name ORDER BY total DESC LIMIT 10");

        //  echo "SELECT SUM( op.quantity )AS total, op.product_id,op.general_product_id, pd.name,op.unit FROM " . DB_PREFIX . "order_product AS op LEFT JOIN " . DB_PREFIX . "order AS o ON ( op.order_id = o.order_id ) LEFT JOIN  " . DB_PREFIX . "product_description AS pd ON (op.general_product_id = pd.product_id)  WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') ."' AND o.customer_id = " . $customer_id . " AND o.date_added >= " . $date." GROUP BY pd.name ORDER BY total DESC LIMIT 10";
//' AND o.order_status_id IN " . $complete_status_ids . "
        return $query->rows;
    }

}
