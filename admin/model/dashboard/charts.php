<?php

class ModelDashboardCharts extends Model {
    public function getVendorSales($date_start, $date_end, $group){
        $complete_status_ids = '('.implode(',', $this->config->get('config_complete_status')).')';
        
        if(isset($this->request->get['vendor_id'])){
            $vendor_id = $this->request->get['vendor_id'];
        }else{
            $vendor_id = $this->user->getId();
        }
        
        $query = $this->db->query("SELECT SUM(total) AS total, HOUR(".DB_PREFIX."order.date_added) AS hour, CONCAT(MONTHNAME(".DB_PREFIX."order.date_added), ' ', YEAR(".DB_PREFIX."order.date_added)) AS month, YEAR(".DB_PREFIX."order.date_added) AS year, DATE(".DB_PREFIX."order.date_added) AS date,".DB_PREFIX."store.vendor_id  FROM `".DB_PREFIX."order` LEFT JOIN ".DB_PREFIX."store on(".DB_PREFIX."store.store_id = ".DB_PREFIX."order.store_id) WHERE vendor_id='".$vendor_id."' AND order_status_id IN " . $complete_status_ids . " AND DATE(".DB_PREFIX."order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' GROUP BY ". $group ."(".DB_PREFIX."order.date_added) ORDER BY ".DB_PREFIX."order.date_added ASC");
        return $query;
    }

    


    public function getTotalVendorSales($date_start, $date_end){
        $complete_status_ids = '('.implode(',', $this->config->get('config_complete_status')).')';

        if(isset($this->request->get['vendor_id'])){
            $vendor_id = $this->request->get['vendor_id'];
        }else{
            $vendor_id = $this->user->getId();
        }
        
        $query = $this->db->query("SELECT SUM(total) AS total,".DB_PREFIX."store.vendor_id FROM `" . DB_PREFIX . "order` LEFT JOIN ".DB_PREFIX."store on(".DB_PREFIX."store.store_id = ".DB_PREFIX."order.store_id) WHERE vendor_id='".$vendor_id."' AND order_status_id IN " . $complete_status_ids . " AND DATE(".DB_PREFIX."order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'");
        return $query->row;
    }

    public function getSales($date_start, $date_end, $group){
        $complete_status_ids = '('.implode(',', $this->config->get('config_complete_status')).')';

        $query = $this->db->query("SELECT SUM(total) AS total, HOUR(".DB_PREFIX."order.date_added) AS hour, CONCAT(MONTHNAME(".DB_PREFIX."order.date_added), ' ', YEAR(".DB_PREFIX."order.date_added)) AS month, YEAR(".DB_PREFIX."order.date_added) AS year, DATE(".DB_PREFIX."order.date_added) AS date,".DB_PREFIX."store.vendor_id  FROM `" . DB_PREFIX . "order` LEFT JOIN ".DB_PREFIX."store on(".DB_PREFIX."store.store_id = ".DB_PREFIX."order.store_id) WHERE order_status_id IN " . $complete_status_ids . " AND DATE(".DB_PREFIX."order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' GROUP BY ". $group ."(".DB_PREFIX."order.date_added) ORDER BY ".DB_PREFIX."order.date_added ASC");
        return $query;
    }

    public function getTotalSales($date_start, $date_end){
        $complete_status_ids = '('.implode(',', $this->config->get('config_complete_status')).')';

        $query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "order` LEFT JOIN ".DB_PREFIX."store on(".DB_PREFIX."store.store_id = ".DB_PREFIX."order.store_id) WHERE order_status_id IN " . $complete_status_ids . " AND DATE(".DB_PREFIX."order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'");
        return $query->row;
    }

    public function getVendorOrders($date_start, $date_end, $group){
        $complete_status_ids = '('.implode(',', $this->config->get('config_complete_status')).')';

        if(isset($this->request->get['vendor_id'])){
            $vendor_id = $this->request->get['vendor_id'];
        }else{
            $vendor_id = $this->user->getId();
        }
        
        $query = $this->db->query("SELECT COUNT(*) AS total, HOUR(".DB_PREFIX."order.date_added) AS hour, CONCAT(MONTHNAME(".DB_PREFIX."order.date_added), ' ', YEAR(".DB_PREFIX."order.date_added)) AS month, YEAR(".DB_PREFIX."order.date_added) AS year, DATE(".DB_PREFIX."order.date_added) AS date  FROM `" . DB_PREFIX . "order` LEFT JOIN ".DB_PREFIX."store on(".DB_PREFIX."store.store_id = ".DB_PREFIX."order.store_id) WHERE order_status_id IN " . $complete_status_ids . " AND DATE(".DB_PREFIX."order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' AND vendor_id='".$vendor_id."' GROUP BY ". $group ."(".DB_PREFIX."order.date_added) ORDER BY ".DB_PREFIX."order.date_added ASC");
        return $query;
    }

    public function getTotalVendorOrders($date_start, $date_end){
        $complete_status_ids = '('.implode(',', $this->config->get('config_complete_status')).')';

        if(isset($this->request->get['vendor_id'])){
            $vendor_id = $this->request->get['vendor_id'];
        }else{
            $vendor_id = $this->user->getId();
        }
        
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` LEFT JOIN ".DB_PREFIX."store on(".DB_PREFIX."store.store_id = ".DB_PREFIX."order.store_id) WHERE vendor_id='".$vendor_id."' AND order_status_id IN " . $complete_status_ids . " AND DATE(".DB_PREFIX."order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'");
        
        return $query->row;
    }

    public function getOrders($date_start, $date_end, $group){
        $complete_status_ids = '('.implode(',', $this->config->get('config_complete_status')).')';

        $query = $this->db->query("SELECT COUNT(*) AS total, HOUR(".DB_PREFIX."order.date_added) AS hour, CONCAT(MONTHNAME(".DB_PREFIX."order.date_added), ' ', YEAR(".DB_PREFIX."order.date_added)) AS month, YEAR(".DB_PREFIX."order.date_added) AS year, DATE(".DB_PREFIX."order.date_added) AS date  FROM `" . DB_PREFIX . "order` LEFT JOIN ".DB_PREFIX."store on(".DB_PREFIX."store.store_id = ".DB_PREFIX."order.store_id) WHERE order_status_id IN " . $complete_status_ids . " AND DATE(".DB_PREFIX."order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' GROUP BY ". $group ."(".DB_PREFIX."order.date_added) ORDER BY ".DB_PREFIX."order.date_added ASC");
        return $query;
    }

    public function getTodaysOrders($date_start, $date_end, $group,$type) {

        //echo "<pre>";print_r($date_start."ce".$date_end."er".$group."er".$type);
        if($type == 'complete') {
            $complete_status_ids = '('.implode(',', $this->config->get('config_complete_status')).')';

            $query = $this->db->query("SELECT COUNT(*) AS total,SUM(value) as value  FROM `" . DB_PREFIX . "order` LEFT JOIN ".DB_PREFIX."order_total on(".DB_PREFIX."order.order_id = ".DB_PREFIX."order_total.order_id) WHERE order_status_id IN " . $complete_status_ids . " AND DATE(".DB_PREFIX."order.date_modified) BETWEEN '" . $this->db->escape($date_start) ."' AND '" . $this->db->escape($date_end) . "' AND ".DB_PREFIX."order_total.code='sub_total'");

        } elseif ($type == 'cancelled') {
            $complete_status_ids = '('.implode(',', $this->config->get('config_refund_status')).')';

            $query = $this->db->query("SELECT COUNT(*) AS total,SUM(value) as value  FROM `" . DB_PREFIX . "order` LEFT JOIN ".DB_PREFIX."order_total on(".DB_PREFIX."order.order_id = ".DB_PREFIX."order_total.order_id) WHERE order_status_id IN " . $complete_status_ids . " AND DATE(".DB_PREFIX."order.date_modified) BETWEEN '" . $this->db->escape($date_start) ."' AND '" . $this->db->escape($date_end) . "' AND ".DB_PREFIX."order_total.code='sub_total'");

        } else {

            // today created order
            
            $query = $this->db->query("SELECT COUNT(*) AS total,SUM(value) as value  FROM `" . DB_PREFIX . "order` LEFT JOIN ".DB_PREFIX."order_total on(".DB_PREFIX."order.order_id = ".DB_PREFIX."order_total.order_id) WHERE order_status_id != 0 AND DATE(".DB_PREFIX."order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' AND ".DB_PREFIX."order_total.code='sub_total'");


        }
        
        return $query->row;
    }



    public function getTotalOrders($date_start, $date_end){
        $complete_status_ids = '('.implode(',', $this->config->get('config_complete_status')).')';

        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` LEFT JOIN ".DB_PREFIX."store on(".DB_PREFIX."store.store_id = ".DB_PREFIX."order.store_id) WHERE order_status_id IN " . $complete_status_ids . " AND DATE(".DB_PREFIX."order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'");
        return $query->row;
    }

    public function getCustomers($date_start, $date_end, $group){
        $query = $this->db->query("SELECT COUNT(*) AS total, HOUR(date_added) AS hour, CONCAT(MONTHNAME(date_added), ' ', YEAR(date_added)) AS month, YEAR(date_added) AS year, DATE(date_added) AS date  FROM `" . DB_PREFIX . "customer` WHERE  DATE(date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' GROUP BY ". $group ."(date_added) ORDER BY date_added ASC");
        return $query;
    }

    public function getTotalCustomers($date_start, $date_end){
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "customer` WHERE  DATE(date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'");
        return $query->row;
    }

    public function getReviews($date_start, $date_end, $group){
        $query = $this->db->query("SELECT COUNT(*) AS total, HOUR(date_added) AS hour, CONCAT(MONTHNAME(date_added), ' ', YEAR(date_added)) AS month, YEAR(date_added) AS year, DATE(date_added) AS date  FROM `" . DB_PREFIX . "review` WHERE  DATE(date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' GROUP BY ". $group ."(date_added) ORDER BY date_added ASC");
        return $query;
    }

    public function getTotalReviews($date_start, $date_end){
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "review` WHERE  DATE(date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'");
        return $query->row;
    }

    public function getAffiliates($date_start, $date_end, $group){
        $query = $this->db->query("SELECT COUNT(*) AS total, HOUR(date_added) AS hour, CONCAT(MONTHNAME(date_added), ' ', YEAR(date_added)) AS month, YEAR(date_added) AS year, DATE(date_added) AS date  FROM `" . DB_PREFIX . "affiliate` WHERE DATE(date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' GROUP BY ". $group ."(date_added) ORDER BY date_added ASC");
        return $query;
    }

    public function getTotalAffiliates($date_start, $date_end){
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "affiliate` WHERE status = 1 AND DATE(date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'");
        return $query->row;
    }

    public function getRewards($date_start, $date_end, $group){
        $query = $this->db->query("SELECT COUNT(*) AS total, HOUR(date_added) AS hour, CONCAT(MONTHNAME(date_added), ' ', YEAR(date_added)) AS month, YEAR(date_added) AS year, DATE(date_added) AS date  FROM `" . DB_PREFIX . "customer_reward` WHERE DATE(date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "' GROUP BY ". $group ."(date_added) ORDER BY date_added ASC");
        return $query;
    }

    public function getTotalRewards($date_start, $date_end){
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "customer_reward` WHERE DATE(date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'");
        return $query->row;
    }
}