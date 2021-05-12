<?php

class ModelDashboardCharts extends Model {

    public function getVendorSales($date_start, $date_end, $group, $user_id = NULL) {
        $complete_status_ids = '(' . implode(',', $this->config->get('config_complete_status')) . ')';

        if (isset($this->request->get['vendor_id'])) {
            $vendor_id = $this->request->get['vendor_id'];
        } else {
            $vendor_id = $this->user->getId();
        }

        $query = $this->db->query('SELECT SUM(total) AS total, HOUR(' . DB_PREFIX . 'order.date_added) AS hour, CONCAT(MONTHNAME(' . DB_PREFIX . "order.date_added), ' ', YEAR(" . DB_PREFIX . 'order.date_added)) AS month, YEAR(' . DB_PREFIX . 'order.date_added) AS year, DATE(' . DB_PREFIX . 'order.date_added) AS date,' . DB_PREFIX . 'store.vendor_id  FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = ' . DB_PREFIX . "order.store_id) WHERE vendor_id='" . $vendor_id . "' AND order_status_id IN " . $complete_status_ids . ' AND DATE(' . DB_PREFIX . "order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' GROUP BY " . $group . '(' . DB_PREFIX . 'order.date_added) ORDER BY ' . DB_PREFIX . 'order.date_added ASC');

        return $query;
    }

    public function getAccountManagerSales($date_start, $date_end, $group, $user_id = NULL) {
        $complete_status_ids = '(' . implode(',', $this->config->get('config_complete_status')) . ')';
        
        if (isset($this->request->get['account_manager_id'])) {
            $account_manager_id = $this->request->get['account_manager_id'];
        } else if($this->user->isAccountManager() == 1){
            $account_manager_id = $this->user->getId();
        } else {
           $account_manager_id =  $user_id;
        }
        
        $query = $this->db->query('SELECT SUM(total) AS total, HOUR(' . DB_PREFIX . 'order.date_added) AS hour, CONCAT(MONTHNAME(' . DB_PREFIX . "order.date_added), ' ', YEAR(" . DB_PREFIX . 'order.date_added)) AS month, YEAR(' . DB_PREFIX . 'order.date_added) AS year, DATE(' . DB_PREFIX . 'order.date_added) AS date,' . DB_PREFIX . 'customer.account_manager_id  FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'customer on(' . DB_PREFIX . 'customer.customer_id = ' . DB_PREFIX . "order.customer_id) WHERE account_manager_id='" . $account_manager_id . "' AND order_status_id IN " . $complete_status_ids . ' AND DATE(' . DB_PREFIX . "order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' GROUP BY " . $group . '(' . DB_PREFIX . 'order.date_added) ORDER BY ' . DB_PREFIX . 'order.date_added ASC');

        return $query;
    }

    public function getVendorBookedSales($date_start, $date_end, $group, $user_id = NULL) {
        $complete_status_ids = '(' . implode(',', $this->config->get('config_complete_status')) . ')';

        if (isset($this->request->get['vendor_id'])) {
            $vendor_id = $this->request->get['vendor_id'];
        } else {
            $vendor_id = $this->user->getId();
        }

        $query = $this->db->query('SELECT SUM(total) AS total, HOUR(' . DB_PREFIX . 'order.date_added) AS hour, CONCAT(MONTHNAME(' . DB_PREFIX . "order.date_added), ' ', YEAR(" . DB_PREFIX . 'order.date_added)) AS month, YEAR(' . DB_PREFIX . 'order.date_added) AS year, DATE(' . DB_PREFIX . 'order.date_added) AS date,' . DB_PREFIX . 'store.vendor_id  FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = ' . DB_PREFIX . "order.store_id) WHERE vendor_id='" . $vendor_id . "' AND order_status_id IN " . $complete_status_ids . ' AND DATE(' . DB_PREFIX . "order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' GROUP BY " . $group . '(' . DB_PREFIX . 'order.date_added) ORDER BY ' . DB_PREFIX . 'order.date_added ASC');

        return $query;
    }

    public function getAccountManagerBookedSales($date_start, $date_end, $group, $user_id = NULL) {
        $complete_status_ids = '(' . implode(',', $this->config->get('config_complete_status')) . ')';

        if (isset($this->request->get['account_manager_id'])) {
            $account_manager_id = $this->request->get['account_manager_id'];
        } else if($this->user->isAccountManager() == 1) {
            $account_manager_id = $this->user->getId();
        } else {
           $account_manager_id =  $user_id; 
        }

        $query = $this->db->query('SELECT SUM(total) AS total, HOUR(' . DB_PREFIX . 'order.date_added) AS hour, CONCAT(MONTHNAME(' . DB_PREFIX . "order.date_added), ' ', YEAR(" . DB_PREFIX . 'order.date_added)) AS month, YEAR(' . DB_PREFIX . 'order.date_added) AS year, DATE(' . DB_PREFIX . 'order.date_added) AS date,' . DB_PREFIX . 'customer.account_manager_id  FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'customer on(' . DB_PREFIX . 'customer.customer_id = ' . DB_PREFIX . "order.customer_id) WHERE account_manager_id='" . $account_manager_id . "' AND order_status_id IN " . $complete_status_ids . ' AND DATE(' . DB_PREFIX . "order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' GROUP BY " . $group . '(' . DB_PREFIX . 'order.date_added) ORDER BY ' . DB_PREFIX . 'order.date_added ASC');

        return $query;
    }

    public function getTotalAccountManagerBookedSales($date_start, $date_end, $user_id = NULL) {
        $complete_status_ids = '(' . implode(',', $this->config->get('config_complete_status')) . ')';

        if (isset($this->request->get['account_manager_id'])) {
            $account_manager_id = $this->request->get['account_manager_id'];
        } else if($this->user->isAccountManager() == 1){
            $account_manager_id = $this->user->getId();
        } else {
            $account_manager_id =  $user_id;
        }

        $query = $this->db->query('SELECT SUM(value) AS total FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'order_total on(' . DB_PREFIX . 'order.order_id = ' . DB_PREFIX . 'order_total.order_id) LEFT JOIN ' . DB_PREFIX . 'customer on(' . DB_PREFIX . 'order.customer_id = ' . DB_PREFIX . 'customer.customer_id) WHERE  DATE(' . DB_PREFIX . "order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'AND " . DB_PREFIX . "order_total.code='sub_total' AND " . DB_PREFIX . "customer.account_manager_id=" . $account_manager_id);
        //  "SELECT COUNT(*) AS total,SUM(value) as value  FROM `" . DB_PREFIX . "order` LEFT JOIN ".DB_PREFIX."order_total on(".DB_PREFIX."order.order_id = ".DB_PREFIX."order_total.order_id) WHERE order_status_id IN " . $complete_status_ids . " AND DATE(".DB_PREFIX."order.date_modified) BETWEEN '" . $this->db->escape($date_start) ."' AND '" . $this->db->escape($date_end) . "' AND ".DB_PREFIX."order_total.code='sub_total'")

        return $query->row;
    }

    public function getTotalAccountManagerCreatedOrders($date_start, $date_end, $user_id = NULL) {
        $complete_status_ids = '(' . implode(',', $this->config->get('config_complete_status')) . ')';

        if (isset($this->request->get['account_manager_id'])) {
            $account_manager_id = $this->request->get['account_manager_id'];
        } else if($this->user->isAccountManager() == 1) {
            $account_manager_id = $this->user->getId();
        } else {
           $account_manager_id =  $user_id;
        }

        $query = $this->db->query('SELECT COUNT(*) AS total FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = ' . DB_PREFIX . 'order.store_id) LEFT JOIN ' . DB_PREFIX . 'customer on(' . DB_PREFIX . 'order.customer_id = ' . DB_PREFIX . 'customer.customer_id) WHERE DATE(' . DB_PREFIX . "order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' AND " . DB_PREFIX . "customer.account_manager_id=" . $account_manager_id);

        return $query->row;
    }

    public function getTotalAccountManagerCancelledOrders($date_start, $date_end, $user_id = NULL) {
        $complete_status_ids = '(' . implode(',', $this->config->get('config_refund_status')) . ')';
        
        if (isset($this->request->get['account_manager_id'])) {
            $account_manager_id = $this->request->get['account_manager_id'];
        } else if($this->user->isAccountManager() == 1) {
            $account_manager_id = $this->user->getId();
        } else {
           $account_manager_id =  $user_id;
        }
        
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = ' . DB_PREFIX . 'order.store_id) LEFT JOIN ' . DB_PREFIX . 'customer on(' . DB_PREFIX . 'order.customer_id = ' .DB_PREFIX . 'customer.customer_id) WHERE order_status_id IN ' . $complete_status_ids . ' AND  DATE(' . DB_PREFIX . "order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' AND ". DB_PREFIX . "customer.account_manager_id=" . $account_manager_id);

        return $query->row;
    }

    public function getTotalVendorSales($date_start, $date_end, $user_id = NULL) {
        $complete_status_ids = '(' . implode(',', $this->config->get('config_complete_status')) . ')';

        if (isset($this->request->get['vendor_id'])) {
            $vendor_id = $this->request->get['vendor_id'];
        } else {
            $vendor_id = $this->user->getId();
        }

        $query = $this->db->query('SELECT SUM(total) AS total,' . DB_PREFIX . 'store.vendor_id FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = ' . DB_PREFIX . "order.store_id) WHERE vendor_id='" . $vendor_id . "' AND order_status_id IN " . $complete_status_ids . ' AND DATE(' . DB_PREFIX . "order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'");

        return $query->row;
    }

    public function getTotalAccountManagerSales($date_start, $date_end, $user_id = NULL) {
        $complete_status_ids = '(' . implode(',', $this->config->get('config_complete_status')) . ')';

        if (isset($this->request->get['account_manager_id'])) {
            $account_manager_id = $this->request->get['account_manager_id'];
        } else if($this->user->isAccountManager() == 1) {
            $account_manager_id = $this->user->getId();
        } else {
           $account_manager_id =  $user_id;
        }
        
        $query = $this->db->query('SELECT SUM(total) AS total,' . DB_PREFIX . 'customer.account_manager_id FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'customer on(' . DB_PREFIX . 'customer.customer_id  = ' . DB_PREFIX . "order.customer_id) WHERE account_manager_id='" . $account_manager_id . "' AND order_status_id IN " . $complete_status_ids . ' AND DATE(' . DB_PREFIX . "order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'");

        return $query->row;
    }

    public function getSales($date_start, $date_end, $group, $user_id = NULL) {
        $complete_status_ids = '(' . implode(',', $this->config->get('config_complete_status')) . ')';

        $query = $this->db->query('SELECT SUM(value) AS total, HOUR(' . DB_PREFIX . 'order.date_added) AS hour, CONCAT(MONTHNAME(' . DB_PREFIX . "order.date_added), ' ', YEAR(" . DB_PREFIX . 'order.date_added)) AS month, YEAR(' . DB_PREFIX . 'order.date_added) AS year, DATE(' . DB_PREFIX . 'order.date_added) AS date,' . DB_PREFIX . 'store.vendor_id  FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'order_total on(' . DB_PREFIX . 'order.order_id = ' . DB_PREFIX . 'order_total.order_id)   JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = ' . DB_PREFIX . 'order.store_id) WHERE order_status_id IN ' . $complete_status_ids . ' AND DATE(' . DB_PREFIX . "order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'AND " . DB_PREFIX . "order_total.code='sub_total' GROUP BY " . $group . '(' . DB_PREFIX . 'order.date_added) ORDER BY ' . DB_PREFIX . 'order.date_added ASC');
        // $query = $this->db->query("SELECT SUM(value) AS total FROM `" . DB_PREFIX . "order`  WHERE order_status_id IN " . $complete_status_ids . " AND DATE(".DB_PREFIX."order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'AND ".DB_PREFIX."order_total.code='sub_total'");

        return $query;
    }

    public function getBookedSales($date_start, $date_end, $group, $user_id = NULL) {
        $complete_status_ids = '(' . implode(',', $this->config->get('config_complete_status')) . ')';

        $query = $this->db->query('SELECT SUM(value) AS total, HOUR(' . DB_PREFIX . 'order.date_added) AS hour, CONCAT(MONTHNAME(' . DB_PREFIX . "order.date_added), ' ', YEAR(" . DB_PREFIX . 'order.date_added)) AS month, YEAR(' . DB_PREFIX . 'order.date_added) AS year, DATE(' . DB_PREFIX . 'order.date_added) AS date,' . DB_PREFIX . 'store.vendor_id  FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'order_total on(' . DB_PREFIX . 'order.order_id = ' . DB_PREFIX . 'order_total.order_id)   JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = ' . DB_PREFIX . 'order.store_id) WHERE   DATE(' . DB_PREFIX . "order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'AND " . DB_PREFIX . "order_total.code='sub_total' GROUP BY " . $group . '(' . DB_PREFIX . 'order.date_added) ORDER BY ' . DB_PREFIX . 'order.date_added ASC');
        // $query = $this->db->query("SELECT SUM(value) AS total FROM `" . DB_PREFIX . "order`  WHERE order_status_id IN " . $complete_status_ids . " AND DATE(".DB_PREFIX."order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'AND ".DB_PREFIX."order_total.code='sub_total'");

        return $query;
    }

    public function getTotalBookedSales($date_start, $date_end, $user_id = NULL) {
        $complete_status_ids = '(' . implode(',', $this->config->get('config_complete_status')) . ')';

        $query = $this->db->query('SELECT SUM(value) AS total FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'order_total on(' . DB_PREFIX . 'order.order_id = ' . DB_PREFIX . 'order_total.order_id) WHERE  DATE(' . DB_PREFIX . "order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'AND " . DB_PREFIX . "order_total.code='sub_total'");
        //  "SELECT COUNT(*) AS total,SUM(value) as value  FROM `" . DB_PREFIX . "order` LEFT JOIN ".DB_PREFIX."order_total on(".DB_PREFIX."order.order_id = ".DB_PREFIX."order_total.order_id) WHERE order_status_id IN " . $complete_status_ids . " AND DATE(".DB_PREFIX."order.date_modified) BETWEEN '" . $this->db->escape($date_start) ."' AND '" . $this->db->escape($date_end) . "' AND ".DB_PREFIX."order_total.code='sub_total'")

        return $query->row;
    }

    public function getTotalSales($date_start, $date_end, $user_id = NULL) {
        $complete_status_ids = '(' . implode(',', $this->config->get('config_complete_status')) . ')';

        $query = $this->db->query('SELECT SUM(value) AS total FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'order_total on(' . DB_PREFIX . 'order.order_id = ' . DB_PREFIX . 'order_total.order_id) WHERE order_status_id IN ' . $complete_status_ids . ' AND DATE(' . DB_PREFIX . "order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'AND " . DB_PREFIX . "order_total.code='sub_total'");
        //  "SELECT COUNT(*) AS total,SUM(value) as value  FROM `" . DB_PREFIX . "order` LEFT JOIN ".DB_PREFIX."order_total on(".DB_PREFIX."order.order_id = ".DB_PREFIX."order_total.order_id) WHERE order_status_id IN " . $complete_status_ids . " AND DATE(".DB_PREFIX."order.date_modified) BETWEEN '" . $this->db->escape($date_start) ."' AND '" . $this->db->escape($date_end) . "' AND ".DB_PREFIX."order_total.code='sub_total'")

        return $query->row;
    }

    public function getVendorOrders($date_start, $date_end, $group, $user_id = NULL) {
        $complete_status_ids = '(' . implode(',', $this->config->get('config_complete_status')) . ')';

        if (isset($this->request->get['vendor_id'])) {
            $vendor_id = $this->request->get['vendor_id'];
        } else {
            $vendor_id = $this->user->getId();
        }

        $query = $this->db->query('SELECT COUNT(*) AS total, HOUR(' . DB_PREFIX . 'order.date_added) AS hour, CONCAT(MONTHNAME(' . DB_PREFIX . "order.date_added), ' ', YEAR(" . DB_PREFIX . 'order.date_added)) AS month, YEAR(' . DB_PREFIX . 'order.date_added) AS year, DATE(' . DB_PREFIX . 'order.date_added) AS date  FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = ' . DB_PREFIX . 'order.store_id) WHERE order_status_id IN ' . $complete_status_ids . ' AND DATE(' . DB_PREFIX . "order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' AND vendor_id='" . $vendor_id . "' GROUP BY " . $group . '(' . DB_PREFIX . 'order.date_added) ORDER BY ' . DB_PREFIX . 'order.date_added ASC');

        return $query;
    }

    public function getAccountManagerOrders($date_start, $date_end, $group, $user_id = NULL) {
        $complete_status_ids = '(' . implode(',', $this->config->get('config_complete_status')) . ')';

        if (isset($this->request->get['account_manager_id'])) {
            $account_manager_id = $this->request->get['account_manager_id'];
        } else if($this->user->isAccountManager() == 1) {
            $account_manager_id = $this->user->getId();
        } else {
          $account_manager_id =  $user_id;
        }

        $query = $this->db->query('SELECT COUNT(*) AS total, HOUR(' . DB_PREFIX . 'order.date_added) AS hour, CONCAT(MONTHNAME(' . DB_PREFIX . "order.date_added), ' ', YEAR(" . DB_PREFIX . 'order.date_added)) AS month, YEAR(' . DB_PREFIX . 'order.date_added) AS year, DATE(' . DB_PREFIX . 'order.date_added) AS date  FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'customer on(' . DB_PREFIX . 'customer.customer_id = ' . DB_PREFIX . 'order.customer_id) WHERE order_status_id IN ' . $complete_status_ids . ' AND DATE(' . DB_PREFIX . "order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' AND account_manager_id='" . $account_manager_id . "' GROUP BY " . $group . '(' . DB_PREFIX . 'order.date_added) ORDER BY ' . DB_PREFIX . 'order.date_added ASC');

        return $query;
    }

    public function getVendorCreatedOrders($date_start, $date_end, $group, $user_id = NULL) {
        if (isset($this->request->get['vendor_id'])) {
            $vendor_id = $this->request->get['vendor_id'];
        } else {
            $vendor_id = $this->user->getId();
        }

        $query = $this->db->query('SELECT COUNT(*) AS total, HOUR(' . DB_PREFIX . 'order.date_added) AS hour, CONCAT(MONTHNAME(' . DB_PREFIX . "order.date_added), ' ', YEAR(" . DB_PREFIX . 'order.date_added)) AS month, YEAR(' . DB_PREFIX . 'order.date_added) AS year, DATE(' . DB_PREFIX . 'order.date_added) AS date  FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = ' . DB_PREFIX . 'order.store_id) WHERE   DATE(' . DB_PREFIX . "order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' AND vendor_id='" . $vendor_id ."' AND  order_status_id  >0 GROUP BY " . $group . '(' . DB_PREFIX . 'order.date_added) ORDER BY ' . DB_PREFIX . 'order.date_added ASC');

        return $query;
    }

    public function getAccountManagerCreatedOrders($date_start, $date_end, $group, $user_id = NULL) {
        if (isset($this->request->get['account_manager_id'])) {
            $account_manager_id = $this->request->get['account_manager_id'];
        } else if($this->user->isAccountManager() == 1) {
            $account_manager_id = $this->user->getId();
        } else {
            $account_manager_id = $user_id;
        }

        $query = $this->db->query('SELECT COUNT(*) AS total, HOUR(' . DB_PREFIX . 'order.date_added) AS hour, CONCAT(MONTHNAME(' . DB_PREFIX . "order.date_added), ' ', YEAR(" . DB_PREFIX . 'order.date_added)) AS month, YEAR(' . DB_PREFIX . 'order.date_added) AS year, DATE(' . DB_PREFIX . 'order.date_added) AS date  FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'customer on(' . DB_PREFIX . 'customer.customer_id = ' . DB_PREFIX . 'order.customer_id) WHERE   DATE(' . DB_PREFIX . "order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' AND account_manager_id='" . $account_manager_id . "' GROUP BY " . $group . '(' . DB_PREFIX . 'order.date_added) ORDER BY ' . DB_PREFIX . 'order.date_added ASC');

        return $query;
    }

    public function getVendorCancelledOrders($date_start, $date_end, $group, $user_id = NULL) {
        if (isset($this->request->get['vendor_id'])) {
            $vendor_id = $this->request->get['vendor_id'];
        } else {
            $vendor_id = $this->user->getId();
        }

        $complete_status_ids = '(' . implode(',', $this->config->get('config_refund_status')) . ')';

        $query = $this->db->query('SELECT COUNT(*) AS total, HOUR(' . DB_PREFIX . 'order.date_added) AS hour, CONCAT(MONTHNAME(' . DB_PREFIX . "order.date_added), ' ', YEAR(" . DB_PREFIX . 'order.date_added)) AS month, YEAR(' . DB_PREFIX . 'order.date_added) AS year, DATE(' . DB_PREFIX . 'order.date_added) AS date  FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = ' . DB_PREFIX . 'order.store_id) WHERE order_status_id IN ' . $complete_status_ids . ' AND  DATE(' . DB_PREFIX . "order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' AND vendor_id='" . $vendor_id . "' GROUP BY " . $group . '(' . DB_PREFIX . 'order.date_added) ORDER BY ' . DB_PREFIX . 'order.date_added ASC');

        return $query;
    }

    public function getAccountManagerCancelledOrders($date_start, $date_end, $group, $user_id = NULL) {
        if (isset($this->request->get['account_manager_id'])) {
            $account_manager_id = $this->request->get['account_manager_id'];
        } else if($this->user->isAccountManager() == 1) {
            $account_manager_id = $this->user->getId();
        } else {
            $account_manager_id = $user_id;
        }

        $complete_status_ids = '(' . implode(',', $this->config->get('config_refund_status')) . ')';

        $query = $this->db->query('SELECT COUNT(*) AS total, HOUR(' . DB_PREFIX . 'order.date_added) AS hour, CONCAT(MONTHNAME(' . DB_PREFIX . "order.date_added), ' ', YEAR(" . DB_PREFIX . 'order.date_added)) AS month, YEAR(' . DB_PREFIX . 'order.date_added) AS year, DATE(' . DB_PREFIX . 'order.date_added) AS date  FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'customer on(' . DB_PREFIX . 'customer.customer_id = ' . DB_PREFIX . 'order.customer_id) WHERE order_status_id IN ' . $complete_status_ids . ' AND  DATE(' . DB_PREFIX . "order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' AND account_manager_id='" . $account_manager_id . "' GROUP BY " . $group . '(' . DB_PREFIX . 'order.date_added) ORDER BY ' . DB_PREFIX . 'order.date_added ASC');

        return $query;
    }

    public function getTotalVendorOrders($date_start, $date_end, $user_id = NULL) {
        $complete_status_ids = '(' . implode(',', $this->config->get('config_complete_status')) . ')';

        if (isset($this->request->get['vendor_id'])) {
            $vendor_id = $this->request->get['vendor_id'];
        } else {
            $vendor_id = $this->user->getId();
        }

        $query = $this->db->query('SELECT COUNT(*) AS total FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = ' . DB_PREFIX . "order.store_id) WHERE vendor_id='" . $vendor_id . "' AND order_status_id IN " . $complete_status_ids . ' AND DATE(' . DB_PREFIX . "order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'");

        return $query->row;
    }
    public function getTotalVendorCancelledOrders($date_start, $date_end, $user_id = NULL) {
        // $complete_status_ids = '(' . implode(',', $this->config->get('config_complete_status')) . ')';
        $complete_status_ids = '(' . implode(',', $this->config->get('config_refund_status')) . ')';

        if (isset($this->request->get['vendor_id'])) {
            $vendor_id = $this->request->get['vendor_id'];
        } else {
            $vendor_id = $this->user->getId();
        }

        $query = $this->db->query('SELECT COUNT(*) AS total FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = ' . DB_PREFIX . "order.store_id) WHERE vendor_id='" . $vendor_id . "' AND order_status_id IN " . $complete_status_ids . ' AND DATE(' . DB_PREFIX . "order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'");

        return $query->row;
    }

    public function getTotalVendorBookedSales($date_start, $date_end, $user_id = NULL) {
        $complete_status_ids = '(' . implode(',', $this->config->get('config_complete_status')) . ')';

        if (isset($this->request->get['vendor_id'])) {
            $vendor_id = $this->request->get['vendor_id'];
        } else {
            $vendor_id = $this->user->getId();
        }

        $query = $this->db->query('SELECT COUNT(*) AS total FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = ' . DB_PREFIX . "order.store_id) WHERE vendor_id='" . $vendor_id . "' AND order_status_id IN " . $complete_status_ids . ' AND DATE(' . DB_PREFIX . "order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'");

        return $query->row;
    }

    public function getTotalVendorCreatedOrders($date_start, $date_end, $user_id = NULL) {
        $complete_status_ids = '';
       
        if (isset($this->request->get['vendor_id'])) {
            $vendor_id = $this->request->get['vendor_id'];
        } else {
            $vendor_id = $this->user->getId();
        }

        $query = $this->db->query('SELECT COUNT(*) AS total FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = ' . DB_PREFIX . "order.store_id) WHERE vendor_id='" . $vendor_id . "' AND order_status_id  >0 " . $complete_status_ids . ' AND DATE(' . DB_PREFIX . "order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'");

        return $query->row;
    }

    public function getTotalAccountManagerOrders($date_start, $date_end, $user_id = NULL) {
        $complete_status_ids = '(' . implode(',', $this->config->get('config_complete_status')) . ')';

        if (isset($this->request->get['account_manager_id'])) {
            $account_manager_id = $this->request->get['account_manager_id'];
        } else if($this->user->isAccountManager() == 1) {
            $account_manager_id = $this->user->getId();
        } else {
            $account_manager_id = $user_id;
        }

        $query = $this->db->query('SELECT COUNT(*) AS total FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'customer on(' . DB_PREFIX . 'customer.customer_id = ' . DB_PREFIX . "order.customer_id) WHERE account_manager_id='" . $account_manager_id . "' AND order_status_id IN " . $complete_status_ids . ' AND DATE(' . DB_PREFIX . "order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'");

        return $query->row;
    }

    public function getOrders($date_start, $date_end, $group, $user_id = NULL) {
        $complete_status_ids = '(' . implode(',', $this->config->get('config_complete_status')) . ')';

        $query = $this->db->query('SELECT COUNT(*) AS total, HOUR(' . DB_PREFIX . 'order.date_added) AS hour, CONCAT(MONTHNAME(' . DB_PREFIX . "order.date_added), ' ', YEAR(" . DB_PREFIX . 'order.date_added)) AS month, YEAR(' . DB_PREFIX . 'order.date_added) AS year, DATE(' . DB_PREFIX . 'order.date_added) AS date  FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = ' . DB_PREFIX . 'order.store_id) WHERE order_status_id IN ' . $complete_status_ids . ' AND DATE(' . DB_PREFIX . "order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' GROUP BY " . $group . '(' . DB_PREFIX . 'order.date_added) ORDER BY ' . DB_PREFIX . 'order.date_added ASC');

        return $query;
    }

    public function getCreatedOrders($date_start, $date_end, $group, $user_id = NULL) {
        $query = $this->db->query('SELECT COUNT(*) AS total, HOUR(' . DB_PREFIX . 'order.date_added) AS hour, CONCAT(MONTHNAME(' . DB_PREFIX . "order.date_added), ' ', YEAR(" . DB_PREFIX . 'order.date_added)) AS month, YEAR(' . DB_PREFIX . 'order.date_added) AS year, DATE(' . DB_PREFIX . 'order.date_added) AS date  FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = ' . DB_PREFIX . 'order.store_id) WHERE   DATE(' . DB_PREFIX . "order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' GROUP BY " . $group . '(' . DB_PREFIX . 'order.date_added) ORDER BY ' . DB_PREFIX . 'order.date_added ASC');

        return $query;
    }

    public function getCancelledOrders($date_start, $date_end, $group, $user_id = NULL) {
        $complete_status_ids = '(' . implode(',', $this->config->get('config_refund_status')) . ')';
        $query = $this->db->query('SELECT COUNT(*) AS total, HOUR(' . DB_PREFIX . 'order.date_added) AS hour, CONCAT(MONTHNAME(' . DB_PREFIX . "order.date_added), ' ', YEAR(" . DB_PREFIX . 'order.date_added)) AS month, YEAR(' . DB_PREFIX . 'order.date_added) AS year, DATE(' . DB_PREFIX . 'order.date_added) AS date  FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = ' . DB_PREFIX . 'order.store_id) WHERE order_status_id IN ' . $complete_status_ids . ' AND  DATE(' . DB_PREFIX . "order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' GROUP BY " . $group . '(' . DB_PREFIX . 'order.date_added) ORDER BY ' . DB_PREFIX . 'order.date_added ASC');

        return $query;
    }

    public function getTodaysOrders($date_start, $date_end, $group, $type, $user_id = NULL) {
        //echo "<pre>";print_r($date_start."ce".$date_end."er".$group."er".$type);
        if ('complete' == $type) {
            $complete_status_ids = '(' . implode(',', $this->config->get('config_complete_status')) . ')';

            $query = $this->db->query('SELECT COUNT(*) AS total,SUM(value) as value  FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'order_total on(' . DB_PREFIX . 'order.order_id = ' . DB_PREFIX . 'order_total.order_id) WHERE order_status_id IN ' . $complete_status_ids . ' AND DATE(' . DB_PREFIX . "order.date_modified) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' AND " . DB_PREFIX . "order_total.code='sub_total'");
        } elseif ('cancelled' == $type) {
            $complete_status_ids = '(' . implode(',', $this->config->get('config_refund_status')) . ')';

            $query = $this->db->query('SELECT COUNT(*) AS total,SUM(value) as value  FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'order_total on(' . DB_PREFIX . 'order.order_id = ' . DB_PREFIX . 'order_total.order_id) WHERE order_status_id IN ' . $complete_status_ids . ' AND DATE(' . DB_PREFIX . "order.date_modified) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' AND " . DB_PREFIX . "order_total.code='sub_total'");
        } else {
            // today created order

            $query = $this->db->query('SELECT COUNT(*) AS total,SUM(value) as value  FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'order_total on(' . DB_PREFIX . 'order.order_id = ' . DB_PREFIX . 'order_total.order_id) WHERE order_status_id != 0 AND DATE(' . DB_PREFIX . "order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' AND " . DB_PREFIX . "order_total.code='sub_total'");
        }

        return $query->row;
    }

    public function getTotalCreatedOrders($date_start, $date_end, $user_id = NULL) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = ' . DB_PREFIX . 'order.store_id) WHERE   DATE(' . DB_PREFIX . "order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'");

        return $query->row;
    }

    public function getTotalCancelledOrders($date_start, $date_end, $user_id = NULL) {
        $complete_status_ids = '(' . implode(',', $this->config->get('config_refund_status')) . ')';
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = ' . DB_PREFIX . 'order.store_id) WHERE order_status_id IN ' . $complete_status_ids . ' AND  DATE(' . DB_PREFIX . "order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'");

        return $query->row;
    }

    public function getTotalOrders($date_start, $date_end, $user_id = NULL) {
        $complete_status_ids = '(' . implode(',', $this->config->get('config_complete_status')) . ')';

        $query = $this->db->query('SELECT COUNT(*) AS total FROM `' . DB_PREFIX . 'order` LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = ' . DB_PREFIX . 'order.store_id) WHERE order_status_id IN ' . $complete_status_ids . ' AND DATE(' . DB_PREFIX . "order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'");

        return $query->row;
    }

    public function getCustomers($date_start, $date_end, $group, $user_id = NULL) {
        $query = $this->db->query("SELECT COUNT(*) AS total, HOUR(date_added) AS hour, CONCAT(MONTHNAME(date_added), ' ', YEAR(date_added)) AS month, YEAR(date_added) AS year, DATE(date_added) AS date  FROM `" . DB_PREFIX . "customer` WHERE  DATE(date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' GROUP BY " . $group . '(date_added) ORDER BY date_added ASC');

        return $query;
    }
    
    public function getAccountManagerCustomers($date_start, $date_end, $group, $user_id = NULL) {
        
        if (isset($this->request->get['account_manager_id'])) {
            $account_manager_id = $this->request->get['account_manager_id'];
        } else if($this->user->isAccountManager() == 1) {
            $account_manager_id = $this->user->getId();
        } else {
            $account_manager_id = $user_id;
        }
        
        $query = $this->db->query("SELECT COUNT(*) AS total, HOUR(date_added) AS hour, CONCAT(MONTHNAME(date_added), ' ', YEAR(date_added)) AS month, YEAR(date_added) AS year, DATE(date_added) AS date  FROM `" . DB_PREFIX . "customer` WHERE account_manager_id = '".$account_manager_id."' AND DATE(date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' GROUP BY " . $group . '(date_added) ORDER BY date_added ASC');

        return $query;
    }
    
    public function getTotalAccountManagerCustomers($date_start, $date_end, $user_id = NULL) {
        
        if (isset($this->request->get['account_manager_id'])) {
            $account_manager_id = $this->request->get['account_manager_id'];
        } else if($this->user->isAccountManager() == 1) {
            $account_manager_id = $this->user->getId();
        } else {
            $account_manager_id = $user_id;
        }
        
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `' . DB_PREFIX . "customer` WHERE account_manager_id = '".$account_manager_id."' AND  DATE(date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'");

        return $query->row;
    }

    public function getTotalCustomers($date_start, $date_end, $user_id = NULL) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `' . DB_PREFIX . "customer` WHERE  DATE(date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'");

        return $query->row;
    }

    public function getReviews($date_start, $date_end, $group, $user_id = NULL) {
        $query = $this->db->query("SELECT COUNT(*) AS total, HOUR(date_added) AS hour, CONCAT(MONTHNAME(date_added), ' ', YEAR(date_added)) AS month, YEAR(date_added) AS year, DATE(date_added) AS date  FROM `" . DB_PREFIX . "review` WHERE  DATE(date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' GROUP BY " . $group . '(date_added) ORDER BY date_added ASC');

        return $query;
    }

    public function getTotalReviews($date_start, $date_end, $user_id = NULL) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `' . DB_PREFIX . "review` WHERE  DATE(date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'");

        return $query->row;
    }

    public function getAffiliates($date_start, $date_end, $group, $user_id = NULL) {
        $query = $this->db->query("SELECT COUNT(*) AS total, HOUR(date_added) AS hour, CONCAT(MONTHNAME(date_added), ' ', YEAR(date_added)) AS month, YEAR(date_added) AS year, DATE(date_added) AS date  FROM `" . DB_PREFIX . "affiliate` WHERE DATE(date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' GROUP BY " . $group . '(date_added) ORDER BY date_added ASC');

        return $query;
    }

    public function getTotalAffiliates($date_start, $date_end, $user_id = NULL) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `' . DB_PREFIX . "affiliate` WHERE status = 1 AND DATE(date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'");

        return $query->row;
    }

    public function getRewards($date_start, $date_end, $group, $user_id = NULL) {
        $query = $this->db->query("SELECT COUNT(*) AS total, HOUR(date_added) AS hour, CONCAT(MONTHNAME(date_added), ' ', YEAR(date_added)) AS month, YEAR(date_added) AS year, DATE(date_added) AS date  FROM `" . DB_PREFIX . "customer_reward` WHERE DATE(date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' GROUP BY " . $group . '(date_added) ORDER BY date_added ASC');

        return $query;
    }

    public function getTotalRewards($date_start, $date_end, $user_id = NULL) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `' . DB_PREFIX . "customer_reward` WHERE DATE(date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'");

        return $query->row;
    }

}
