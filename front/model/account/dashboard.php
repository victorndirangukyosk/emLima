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
        
        $s_users = array();
        $sub_users_query = $this->db->query("SELECT c.customer_id FROM " . DB_PREFIX . "customer c WHERE parent = '" . (int) $customer_id . "'");
        $sub_users = $sub_users_query->rows;
        $s_users = array_column($sub_users, 'customer_id');
        
        array_push($s_users, $customer_id);
        $sub_users_od = implode(",", $s_users);
        
        $query = $this->db->query("SELECT o.order_id, o.invoice_no, DATE_FORMAT(o.delivery_date, '%d/%m/%Y') AS delivery_date, DATE_FORMAT(o.date_added, '%d/%m/%Y') AS date_added, o.order_status_id, os.name FROM " . DB_PREFIX . "order AS o JOIN " . DB_PREFIX . "order_status AS os ON (o.order_status_id = os.order_status_id)  WHERE o.customer_id IN (".$sub_users_od.") AND o.order_status_id > '0'  order by o.date_added Desc");
        //$query = $this->db->query("SELECT o.order_id, o.invoice_no, DATE_FORMAT(o.delivery_date, '%d/%m/%Y') AS delivery_date, DATE_FORMAT(o.date_added, '%d/%m/%Y') AS date_added, o.order_status_id, os.name FROM " . DB_PREFIX . "order AS o JOIN " . DB_PREFIX . "order_status AS os ON (o.order_status_id = os.order_status_id)  WHERE o.customer_id = '" . (int) $customer_id . "' AND o.order_status_id > '0'  order by o.date_added Desc");

        //return $query;
        return $query->rows;
    }

    public function getRecentActivity($customer_id) {
        
        $s_users = array();
        $sub_users_query = $this->db->query("SELECT c.customer_id FROM " . DB_PREFIX . "customer c WHERE parent = '" . (int) $customer_id . "'");
        $sub_users = $sub_users_query->rows;
        $s_users = array_column($sub_users, 'customer_id');
        
        array_push($s_users, $customer_id);
        $sub_users_od = implode(",", $s_users);
        
        $query = $this->db->query("SELECT o.order_id, o.invoice_no, o.lastname, o.firstname, DATE_FORMAT(o.delivery_date, '%d/%m/%Y') AS delivery_date, DATE_FORMAT(o.date_added, '%d/%m/%Y %H:%i:%s') AS date_added, o.order_status_id, os.name, o.store_id, o.store_name, o.total FROM " . DB_PREFIX . "order AS o JOIN " . DB_PREFIX . "order_status AS os ON (o.order_status_id = os.order_status_id)  WHERE o.customer_id IN (".$sub_users_od.") AND o.order_status_id > '0'  order by o.date_added ASC");
        
        //$query = $this->db->query("SELECT o.order_id, o.invoice_no, o.lastname, o.firstname, DATE_FORMAT(o.delivery_date, '%d/%m/%Y') AS delivery_date, DATE_FORMAT(o.date_added, '%d/%m/%Y %H:%i:%s') AS date_added, o.order_status_id, os.name, o.store_id, o.store_name, o.total FROM " . DB_PREFIX . "order AS o JOIN " . DB_PREFIX . "order_status AS os ON (o.order_status_id = os.order_status_id)  WHERE o.customer_id = '" . (int) $customer_id . "' AND o.order_status_id > '0'  order by o.date_added ASC");
  //// echo "SELECT SUM(total) AS total,  DATE(".DB_PREFIX."order.date_added) AS date  FROM `" . DB_PREFIX . "order` LEFT JOIN ".DB_PREFIX."order_total on(".DB_PREFIX."order.order_id = ".DB_PREFIX."order_total.order_id)   JOIN ".DB_PREFIX."store on(".DB_PREFIX."store.store_id = ".DB_PREFIX."order.store_id) WHERE  ".DB_PREFIX."order_total.code='sub_total' GROUP BY ". $group ."(".DB_PREFIX."order.date_added) ORDER BY ".DB_PREFIX."order.date_added ASC";
  // echo " SELECT  o.total  AS total,   DATE(o.date_added ) AS date  FROM " . DB_PREFIX . "order AS o JOIN " . DB_PREFIX . "order_status AS os ON (o.order_status_id = os.order_status_id)  WHERE o.customer_id = '" . (int) $customer_id . "' AND o.order_status_id > '0'  GROUP BY ". $group ."(o.date_added) order by o.date_added ASC limit 7";   //return $query;
        return $query->rows;
    }

    public function getBuyingPattern($customer_id) {
            $query = $this->db->query("SELECT  SUM(o.total) AS total,   DATE(o.date_added ) AS date  FROM " . DB_PREFIX . "order AS o JOIN " . DB_PREFIX . "order_status AS os ON (o.order_status_id = os.order_status_id)  WHERE o.customer_id = '" . (int) $customer_id . "' AND o.order_status_id > '0'  GROUP BY ". $group ." DATE(o.date_added) order by o.date_added ASC  ");

         //  $query = $this->db->query("SELECT SUM(total) AS total,  DATE(".DB_PREFIX."order.date_added) AS date  FROM `" . DB_PREFIX . "order` LEFT JOIN ".DB_PREFIX."order_total on(".DB_PREFIX."order.order_id = ".DB_PREFIX."order_total.order_id)    WHERE  ".DB_PREFIX."order.customer_id='" . (int) $customer_id . "' and  ".DB_PREFIX."order_total.code='sub_total' GROUP BY ". $group ."(".DB_PREFIX."order.date_added) ORDER BY ".DB_PREFIX."order.date_added ASC  ");
       // $query = $this->db->query("SELECT SUM(value) AS total FROM `" . DB_PREFIX . "order`  WHERE order_status_id IN " . $complete_status_ids . " AND DATE(".DB_PREFIX."order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'AND ".DB_PREFIX."order_total.code='sub_total'");
       return $query;
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
