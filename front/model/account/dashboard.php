<?php

class ModelAccountDashboard extends Model
{
    public function getCustomerDashboardData($customer_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."customer WHERE customer_id = '".(int) $customer_id."'");

        return $query->row;
    }

    public function getCustomerSubUsers($customer_id)
    {
        $query = $this->db->query('SELECT customer_id ,company_name  FROM '.DB_PREFIX."customer WHERE parent = '".(int) $customer_id."' or  customer_id = '".(int) $customer_id."' order by customer_id");

        return $query->rows;
    }

    public function getCustomerOtherInfo($customer_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."customer_other_info WHERE customer_id = '".(int) $customer_id."'");

        return $query->row;
    }

    public function getTotalOrders($customer_id, $selectedCustomer_id, $date_start = null, $date_end = null)
    {
        if (null == $date_start && null == $date_end) {
            $query = $this->db->query('SELECT COUNT(*) AS total FROM `'.DB_PREFIX."order` o WHERE customer_id = '".(int) $customer_id."' AND o.order_status_id > '0' ");
        } else {
            if (-1 == $selectedCustomer_id) {
                $s_users = [];
                $sub_users_query = $this->db->query('SELECT c.customer_id FROM '.DB_PREFIX."customer c WHERE parent = '".(int) $customer_id."'");
                $sub_users = $sub_users_query->rows;
                $s_users = array_column($sub_users, 'customer_id');

                array_push($s_users, $customer_id);
                $sub_users_od = implode(',', $s_users);

                $query = $this->db->query('SELECT COUNT(*) AS total FROM `'.DB_PREFIX.'order` o WHERE customer_id IN ('.$sub_users_od.") AND o.order_status_id > '0' AND o.date_added >='".$date_start."'AND o.date_added < '".$date_end."' ");
            } else {
                $query = $this->db->query('SELECT COUNT(*) AS total FROM `'.DB_PREFIX."order` o WHERE customer_id = '".(int) $selectedCustomer_id."' AND o.order_status_id > '0' AND o.date_added >='".$date_start."'AND o.date_added < '".$date_end."' ");
            }
        }

        // echo "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` o WHERE customer_id = '" . (int) $customer_id . "' AND o.order_status_id > '0'AND o.date_added >='". $date_start."'AND o.date_added < '".$date_end."' ";
        //return $query;
        return $query->row['total'];
    }

    public function getOrders($customer_id, $selectedCustomer_id, $date_start = null, $date_end = null)
    {
        if (null == $date_start && null == $date_end) {
            $query = $this->db->query('SELECT order_id, date_added   FROM `'.DB_PREFIX."order` o WHERE customer_id = '".(int) $customer_id."' AND o.order_status_id > '0'  order by date_added ASC");
        } else {
            if (-1 == $selectedCustomer_id) {
                $s_users = [];
                $sub_users_query = $this->db->query('SELECT c.customer_id FROM '.DB_PREFIX."customer c WHERE parent = '".(int) $customer_id."'");
                $sub_users = $sub_users_query->rows;
                $s_users = array_column($sub_users, 'customer_id');

                array_push($s_users, $customer_id);
                $sub_users_od = implode(',', $s_users);
                $query = $this->db->query('SELECT order_id, date_added   FROM `'.DB_PREFIX.'order` o WHERE customer_id IN ('.$sub_users_od.") AND o.order_status_id > '0'  AND o.date_added >='".$date_start."'AND o.date_added < '".$date_end."'  order by date_added ASC");
            } else {
                $query = $this->db->query('SELECT order_id, date_added   FROM `'.DB_PREFIX."order` o WHERE customer_id = '".(int) $selectedCustomer_id."' AND o.order_status_id > '0'  AND o.date_added >='".$date_start."'AND o.date_added < '".$date_end."'  order by date_added ASC");
            }
        }

        //return $query;
        return $query->rows;
    }

    public function getRecentOrders($customer_id)
    {
        $s_users = [];
        $sub_users_query = $this->db->query('SELECT c.customer_id FROM '.DB_PREFIX."customer c WHERE parent = '".(int) $customer_id."'");
        $sub_users = $sub_users_query->rows;
        $s_users = array_column($sub_users, 'customer_id');

        array_push($s_users, $customer_id);
        $sub_users_od = implode(',', $s_users);

        $query = $this->db->query("SELECT o.order_id, o.invoice_no, DATE_FORMAT(o.delivery_date, '%d/%m/%Y') AS delivery_date, DATE_FORMAT(o.date_added, '%d/%m/%Y') AS date_added, o.order_status_id, os.name FROM ".DB_PREFIX.'order AS o JOIN '.DB_PREFIX.'order_status AS os ON (o.order_status_id = os.order_status_id)  WHERE o.customer_id IN ('.$sub_users_od.") AND o.order_status_id > '0'  order by o.date_added Desc Limit 10");
        //$query = $this->db->query("SELECT o.order_id, o.invoice_no, DATE_FORMAT(o.delivery_date, '%d/%m/%Y') AS delivery_date, DATE_FORMAT(o.date_added, '%d/%m/%Y') AS date_added, o.order_status_id, os.name FROM " . DB_PREFIX . "order AS o JOIN " . DB_PREFIX . "order_status AS os ON (o.order_status_id = os.order_status_id)  WHERE o.customer_id = '" . (int) $customer_id . "' AND o.order_status_id > '0'  order by o.date_added Desc");

        //return $query;
        return $query->rows;
    }

    public function getRecentActivity($customer_id)
    {
        $s_users = [];
        $sub_users_query = $this->db->query('SELECT c.customer_id FROM '.DB_PREFIX."customer c WHERE parent = '".(int) $customer_id."'");
        $sub_users = $sub_users_query->rows;
        $s_users = array_column($sub_users, 'customer_id');

        array_push($s_users, $customer_id);
        $sub_users_od = implode(',', $s_users);

        $query = $this->db->query("SELECT o.order_id, o.invoice_no, o.lastname, o.firstname, DATE_FORMAT(o.delivery_date, '%d/%m/%Y') AS delivery_date, DATE_FORMAT(o.date_added, '%d/%m/%Y %H:%i:%s') AS date_added,  DATE_FORMAT(o.date_modified, '%d/%m/%Y %H:%i:%s') AS date_modified, o.order_status_id, os.name, o.store_id, o.store_name, o.total FROM ".DB_PREFIX.'order AS o JOIN '.DB_PREFIX.'order_status AS os ON (o.order_status_id = os.order_status_id)  WHERE o.customer_id IN ('.$sub_users_od.") AND o.order_status_id > '0'  order by o.date_modified DESC Limit 10");

        // echo "SELECT o.order_id, o.invoice_no, o.lastname, o.firstname, DATE_FORMAT(o.delivery_date, '%d/%m/%Y') AS delivery_date, DATE_FORMAT(o.date_added, '%d/%m/%Y %H:%i:%s') AS date_added , DATE_FORMAT(o.date_modified, '%d/%m/%Y %H:%i:%s') AS date_modified, o.order_status_id, os.name, o.store_id, o.store_name, o.total FROM " . DB_PREFIX . "order AS o JOIN " . DB_PREFIX . "order_status AS os ON (o.order_status_id = os.order_status_id)  WHERE o.customer_id IN (".$sub_users_od.") AND o.order_status_id > '0'  order by o.date_modified DESC Limit 10";
        return $query->rows;
    }

    public function getCustomerActivities($customer_id)
    {
        $s_users = [];
        $sub_users_query = $this->db->query('SELECT c.customer_id FROM '.DB_PREFIX."customer c WHERE parent = '".(int) $customer_id."'");
        $sub_users = $sub_users_query->rows;
        $s_users = array_column($sub_users, 'customer_id');

        array_push($s_users, $customer_id);
        $sub_users_od = implode(',', $s_users);
        $sql = 'SELECT ca.activity_id, ca.customer_id, ca.key, ca.data, ca.ip, ca.date_added FROM '.DB_PREFIX.'customer_activity ca LEFT JOIN '.DB_PREFIX.'customer c ON (ca.customer_id = c.customer_id) WHERE ca.customer_id IN ('.$sub_users_od.") and ca.key == 'order_account'  ORDER BY ca.date_added DESC  Limit 10 ";
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getBuyingPattern($customer_id)
    {
        $query = $this->db->query('SELECT  SUM(o.total) AS total,   DATE(o.date_added ) AS date  FROM '.DB_PREFIX.'order AS o JOIN '.DB_PREFIX."order_status AS os ON (o.order_status_id = os.order_status_id)  WHERE o.customer_id = '".(int) $customer_id."' AND o.order_status_id > '0'  GROUP BY ".$group.' DATE(o.date_added) order by o.date_added ASC Limit 10  ');

        //  $query = $this->db->query("SELECT SUM(total) AS total,  DATE(".DB_PREFIX."order.date_added) AS date  FROM `" . DB_PREFIX . "order` LEFT JOIN ".DB_PREFIX."order_total on(".DB_PREFIX."order.order_id = ".DB_PREFIX."order_total.order_id)    WHERE  ".DB_PREFIX."order.customer_id='" . (int) $customer_id . "' and  ".DB_PREFIX."order_total.code='sub_total' GROUP BY ". $group ."(".DB_PREFIX."order.date_added) ORDER BY ".DB_PREFIX."order.date_added ASC  ");
        // $query = $this->db->query("SELECT SUM(value) AS total FROM `" . DB_PREFIX . "order`  WHERE order_status_id IN " . $complete_status_ids . " AND DATE(".DB_PREFIX."order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'AND ".DB_PREFIX."order_total.code='sub_total'");
        return $query;
    }

    public function getMostPurchased($customer_id)
    {
        $s_users = [];
        $sub_users_query = $this->db->query('SELECT c.customer_id FROM '.DB_PREFIX."customer c WHERE parent = '".(int) $customer_id."'");
        $sub_users = $sub_users_query->rows;
        $s_users = array_column($sub_users, 'customer_id');

        array_push($s_users, $customer_id);
        $sub_users_od = implode(',', $s_users);

        // echo "<pre>";print_r($sub_users_od);die;
        // $complete_status_ids = '('.implode(',', $this->config->get('config_complete_status')).')';
        //echo "<pre>";print_r($complete_status_ids);die;
        $date = date('Y-m-d', strtotime('-30 day'));

        $query = $this->db->query('SELECT SUM( op.quantity )AS total,pd.name,op.unit,op.product_id FROM '.DB_PREFIX.'order_product AS op LEFT JOIN '.DB_PREFIX.'order AS o ON ( op.order_id = o.order_id ) LEFT JOIN  '.DB_PREFIX."product_description AS pd ON (op.general_product_id = pd.product_id)  WHERE pd.language_id = '".(int) $this->config->get('config_language_id')."' AND o.customer_id IN (".$sub_users_od.') AND o.order_status_id not IN (0,6,8,9,10,16) AND o.date_added >= '.$date.' GROUP BY pd.name  having sum(op.quantity)>100  ORDER BY total DESC LIMIT 10');
        //$query = $this->db->query("SELECT SUM( op.quantity )AS total,pd.name,op.unit FROM " . DB_PREFIX . "order_product AS op LEFT JOIN " . DB_PREFIX . "order AS o ON ( op.order_id = o.order_id ) LEFT JOIN  " . DB_PREFIX . "product_description AS pd ON (op.general_product_id = pd.product_id)  WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND o.customer_id = " . $customer_id . " AND o.date_added >= " . $date . " GROUP BY pd.name  having sum(op.quantity)>100  ORDER BY total DESC LIMIT 10");

        //echo "SELECT SUM( op.quantity )AS total, op.product_id,op.general_product_id, pd.name,op.unit FROM " . DB_PREFIX . "order_product AS op LEFT JOIN " . DB_PREFIX . "order AS o ON ( op.order_id = o.order_id ) LEFT JOIN  " . DB_PREFIX . "product_description AS pd ON (op.general_product_id = pd.product_id)  WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') ."' AND o.customer_id = " . $customer_id . " AND o.date_added >= " . $date."  GROUP BY pd.name having sum(op.quantity)>1 ORDER BY total DESC LIMIT 10";
        //' AND o.order_status_id IN " . $complete_status_ids . "
        return $query->rows;
    }

    public function getrecentorderproducts($data = [])
    {
        $customer_id = $data['customer_id'];
        $s_users = [];
        $sub_users_query = $this->db->query('SELECT c.customer_id FROM '.DB_PREFIX."customer c WHERE parent = '".(int) $customer_id."'");
        $sub_users = $sub_users_query->rows;
        $s_users = array_column($sub_users, 'customer_id');

        array_push($s_users, $customer_id);
        $sub_users_od = implode(',', $s_users);


        $date = date('Y-m-d', strtotime('-30 day'));
       
        $sql = 'SELECT SUM( op.quantity )AS total,pd.name,op.unit,pd.product_id FROM '.DB_PREFIX.'order_product AS op LEFT JOIN '.DB_PREFIX.'order AS o ON ( op.order_id = o.order_id ) LEFT JOIN  '.DB_PREFIX."product_description AS pd ON (op.general_product_id = pd.product_id)  WHERE pd.language_id = '".(int) $this->config->get('config_language_id')."' AND o.customer_id IN (".$sub_users_od.') AND o.order_status_id not IN (0,6,8,9,10,16) AND o.date_added >= '.$date.' GROUP BY pd.name  having sum(op.quantity)>100   ';
    //   'SELECT SUM( op.quantity )AS total,pd.name,op.unit,op.product_id FROM '.DB_PREFIX.'order_product AS op LEFT JOIN '.DB_PREFIX.'order AS o ON ( op.order_id = o.order_id ) LEFT JOIN  '.DB_PREFIX."product_description AS pd ON (op.general_product_id = pd.product_id)  WHERE pd.language_id = '".(int) $this->config->get('config_language_id')."' AND o.customer_id IN (".$sub_users_od.') AND o.order_status_id not IN (0,6,8,9,10,16) AND o.date_added >= '.$date.' GROUP BY pd.name  having sum(op.quantity)>100  ORDER BY total DESC LIMIT 10');

        $implode = [];

        if (!empty($data['filter_product_name'])) {
            $implode[] = " pd.name LIKE '%".$this->db->escape($data['filter_product_name'])."%'";
        }

        if ($implode) {
            $sql .= ' AND '.implode(' AND ', $implode);
        }

        $sort_data = [
        'pd.name',
        'op.unit',
        'total',
    ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY '.$data['sort'];
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

            $sql .= ' LIMIT '.(int) $data['start'].','.(int) $data['limit'];
        }
        //  echo "<pre>";print_r($sql);die;

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalrecentorderproducts($data = [])
    {
        $customer_id = $data['customer_id'];
        $date = date('Y-m-d', strtotime('-30 day'));
        $sql = 'SELECT COUNT(*) AS count FROM '.DB_PREFIX.'order_product AS op LEFT JOIN '.DB_PREFIX.'order AS o ON ( op.order_id = o.order_id ) LEFT JOIN  '.DB_PREFIX."product_description AS pd ON (op.general_product_id = pd.product_id)  WHERE pd.language_id = '".(int) $this->config->get('config_language_id')."' AND o.customer_id = ".$customer_id.' AND o.date_added >= '.$date.' GROUP BY pd.name  having sum(op.quantity)>100  ';

        $implode = [];

        if (!empty($data['filter_product_name'])) {
            $implode[] = " pd.name LIKE '%".$this->db->escape($data['filter_product_name'])."%'";
        }

        if ($implode) {
            $sql .= ' WHERE '.implode(' AND ', $implode);
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getPurchaseHistory($product_id, $customer_id)
    {
        $date = date('Y-m-d', strtotime('-30 day'));

        $s_users = [];
        $sub_users_query = $this->db->query('SELECT c.customer_id FROM '.DB_PREFIX."customer c WHERE parent = '".(int) $customer_id."'");
        $sub_users = $sub_users_query->rows;
        $s_users = array_column($sub_users, 'customer_id');

        array_push($s_users, $customer_id);
        $sub_users_od = implode(',', $s_users);

        $sql = 'SELECT count( op.product_id )AS timespurchased,sum(op.quantity) as qunatitypurchased,sum(op.total) as totalvalue,op.unit,op.product_id FROM '.DB_PREFIX.'order_product AS op LEFT JOIN '.DB_PREFIX.'order AS o ON ( op.order_id = o.order_id ) LEFT JOIN  '.DB_PREFIX."product_description AS pd ON (op.general_product_id = pd.product_id)  WHERE pd.language_id = '".(int) $this->config->get('config_language_id')."' AND o.customer_id IN (".$sub_users_od.')  AND o.date_added >= '.$date.' And op.product_id='.$product_id;

        //  echo "<pre>";print_r($sql);die;
        $query = $this->db->query($sql);

        return $query->row;
    }

    public function getrecentordersofcustomer($data = [])
    {
        $customer_id = $data['customer_id'];

        $s_users = [];
        $sub_users_query = $this->db->query('SELECT c.customer_id FROM '.DB_PREFIX."customer c WHERE parent = '".(int) $customer_id."'");
        $sub_users = $sub_users_query->rows;
        $s_users = array_column($sub_users, 'customer_id');

        array_push($s_users, $customer_id);
        $sub_users_od = implode(',', $s_users);

        $sql = "SELECT o.order_id, o.invoice_no, DATE_FORMAT(o.delivery_date, '%d/%m/%Y') AS delivery_date, DATE_FORMAT(o.date_added, '%d/%m/%Y') AS date_added, o.order_status_id, os.name FROM ".DB_PREFIX.'order AS o JOIN '.DB_PREFIX.'order_status AS os ON (o.order_status_id = os.order_status_id)  WHERE o.customer_id IN ('.$sub_users_od.") AND o.order_status_id > '0' ";

        //$query = $this->db->query("SELECT o.order_id, o.invoice_no, DATE_FORMAT(o.delivery_date, '%d/%m/%Y') AS delivery_date, DATE_FORMAT(o.date_added, '%d/%m/%Y') AS date_added, o.order_status_id  FROM " . DB_PREFIX . "order AS o JOIN " . DB_PREFIX . "order_status AS os ON (o.order_status_id = os.order_status_id)  WHERE o.customer_id IN (".$sub_users_od.") AND o.order_status_id > '0'  order by o.date_added Desc");
        // echo "<pre>";print_r($query);die;
        $implode = [];

        if (!empty($data['filter_product_name'])) {
            $implode[] = " pd.name LIKE '%".$this->db->escape($data['filter_product_name'])."%'";
        }

        if (isset($data['filter_order_status'])) {
            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implode[] = "o.order_status_id = '".(int) $order_status_id."'";
            }

            // if ($implode) {
        //     $sql .= " WHERE (" . implode(" OR ", $implode) . ")";
        // } else {

        // }
        }

        //echo "<pre>";print_r($sql);die;
        // if (!empty($data['filter_city'])) {
        //     $implode[] = " AND c.name LIKE '" . $data['filter_city'] . "%'";
        // }

        // if (!empty($data['filter_order_day'])) {

        //     if($data['filter_order_day'] == 'today') {
        //         $delivery_date = date('Y-m-d');
        //     } else {
        //         $delivery_date = date('Y-m-d',strtotime('+1 day'));
        //     }

        //     $implode[]= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($delivery_date) . "')";

        // }

        if (!empty($data['filter_order_id'])) {
            $implode[] = " AND o.order_id = '".(int) $data['filter_order_id']."'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = " AND DATE(o.date_added) = DATE('".$this->db->escape($data['filter_date_added'])."')";
        }

        if (!empty($data['filter_date_modified'])) {
            $implode[] = " AND DATE(o.date_modified) = DATE('".$this->db->escape($data['filter_date_modified'])."')";
        }

        // if (!empty($data['filter_total'])) {
        //     $implode[]= " AND o.total = '" . (float) $data['filter_total'] . "'";
        // }

        if ($implode) {
            $sql .= ' AND '.implode(' AND ', $implode);
        }

        $sort_data = [
        'o.order_id',
        'status',
        'o.date_added',
        'o.date_modified',
        //'o.total',
        //'c.name'
    ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY '.$data['sort'];
        } else {
            $sql .= ' ORDER BY o.order_id';
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

            $sql .= ' LIMIT '.(int) $data['start'].','.(int) $data['limit'];
        }

        $query = $this->db->query($sql);

        // echo "<pre>";print_r($sql);die;

        return $query->rows;
    }

    public function getTotalrecentorders($data = [])
    {
        $customer_id = $data['customer_id'];

        $s_users = [];
        $sub_users_query = $this->db->query('SELECT c.customer_id FROM '.DB_PREFIX."customer c WHERE parent = '".(int) $customer_id."'");
        $sub_users = $sub_users_query->rows;
        $s_users = array_column($sub_users, 'customer_id');

        array_push($s_users, $customer_id);
        $sub_users_od = implode(',', $s_users);

        $sql = 'SELECT COUNT(*) AS count FROM '.DB_PREFIX.'order AS o JOIN '.DB_PREFIX.'order_status AS os ON (o.order_status_id = os.order_status_id)  WHERE o.customer_id IN ('.$sub_users_od.") AND o.order_status_id > '0'   ";

        $implode = [];

        if (isset($data['filter_order_status'])) {
            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implode[] = "o.order_status_id = '".(int) $order_status_id."'";
            }

            // if ($implode) {
        //     $sql .= " WHERE (" . implode(" OR ", $implode) . ")";
        // } else {

        // }
        }
        // else {
        //     $implode[] = " WHERE o.order_status_id > '0'";
        // }

        //echo "<pre>";print_r($sql);die;
        // if (!empty($data['filter_city'])) {
        //     $implode[] = " AND c.name LIKE '" . $data['filter_city'] . "%'";
        // }

        // if (!empty($data['filter_order_day'])) {

        //     if($data['filter_order_day'] == 'today') {
        //         $delivery_date = date('Y-m-d');
        //     } else {
        //         $delivery_date = date('Y-m-d',strtotime('+1 day'));
        //     }

        //     $implode[]= " AND DATE(o.delivery_date) = DATE('" . $this->db->escape($delivery_date) . "')";

        // }

        if (!empty($data['filter_order_id'])) {
            $implode[] = " AND o.order_id = '".(int) $data['filter_order_id']."'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = " AND DATE(o.date_added) = DATE('".$this->db->escape($data['filter_date_added'])."')";
        }

        if (!empty($data['filter_date_modified'])) {
            $implode[] = " AND DATE(o.date_modified) = DATE('".$this->db->escape($data['filter_date_modified'])."')";
        }

        // if (!empty($data['filter_total'])) {
        //     $implode[]= " AND o.total = '" . (float) $data['filter_total'] . "'";
        // }

        if ($implode) {
            $sql .= ' AND '.implode(' AND ', $implode);
        }
        $query = $this->db->query($sql);

        return $query->row['count'];
    }

    public function download_mostpurchased_products_excel($data)
    {
        $this->load->library('excel');
        $this->load->library('iofactory');

        // $this->load->language('report/income');
        // $this->load->model('sale/customer');
        // $rows = $this->model_sale_customer->getCustomers($data);

        $customer_id = $data['customer_id'];
        $s_users = [];
        $sub_users_query = $this->db->query('SELECT c.customer_id FROM '.DB_PREFIX."customer c WHERE parent = '".(int) $customer_id."'");
        $sub_users = $sub_users_query->rows;
        $s_users = array_column($sub_users, 'customer_id');

        array_push($s_users, $customer_id);
        $sub_users_od = implode(',', $s_users);

        $date = date('Y-m-d', strtotime('-30 day'));
        $sql = 'SELECT SUM( op.quantity )AS total,pd.name,op.unit FROM '.DB_PREFIX.'order_product AS op LEFT JOIN '.DB_PREFIX.'order AS o ON ( op.order_id = o.order_id ) LEFT JOIN  '.DB_PREFIX."product_description AS pd ON (op.general_product_id = pd.product_id)  WHERE pd.language_id = '".(int) $this->config->get('config_language_id')."' AND o.customer_id IN (".$sub_users_od.') AND o.order_status_id not IN (0,6,8,9,10,16)  AND o.date_added >= '.$date.' GROUP BY pd.name  having sum(op.quantity)>100   ';
       
        
        $query = $this->db->query($sql);
        $rows = $query->rows;

        // echo "<pre>";print_r($rows);die;

        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle('Most bought Products')->setDescription('none');
            $objPHPExcel->setActiveSheetIndex(0);

            // Field names in the first row
            // ID, Photo, Name, Contact no., Reason, Valid from, Valid upto, Intime, Outtime
            $title = [
            'font' => [
                'bold' => true,
                'color' => [
                    'rgb' => 'FFFFFF',
                ],
            ],
            'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => [
                    'rgb' => '4390df',
                ],
            ],
        ];

            //Company name, address
            $objPHPExcel->getActiveSheet()->mergeCells('A1:C2');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Products ');
            $objPHPExcel->getActiveSheet()->getStyle('A1:C2')->applyFromArray(['font' => ['bold' => true], 'color' => [
                'rgb' => '4390df',
            ]]);

            //subtitle

            $objPHPExcel->getActiveSheet()->getStyle('A1:C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);

            // foreach(range('A','L') as $columnID) {
            // 	$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
            // 		->setAutoSize(true);
            // }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Product Name');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Unit of Measure');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Qty');

            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, 4)->applyFromArray($title);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2, 4)->applyFromArray($title);

            // Fetching the table data
            $row = 5;
            foreach ($rows as $result) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['name']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['unit']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['total']);
                ++$row;
            }

            $objPHPExcel->setActiveSheetIndex(0);

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $filename = 'MostBoughtProducts.xlsx';
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $objWriter->save('php://output');
            exit;
        } catch (Exception $e) {
            $errstr = $e->getMessage();
            $errline = $e->getLine();
            $errfile = $e->getFile();
            $errno = $e->getCode();
            $this->session->data['export_import_error'] = ['errstr' => $errstr, 'errno' => $errno, 'errfile' => $errfile, 'errline' => $errline];
            if ($this->config->get('config_error_log')) {
                $this->log->write('PHP '.get_class($e).':  '.$errstr.' in '.$errfile.' on line '.$errline);
            }

            return;
        }
    }

    public function getValueOfBasket($Selectedcustomer_id, $date_start, $date_end, $group, $customer_id)
    {
        if (-1 == $Selectedcustomer_id) {
            $s_users = [];
            $sub_users_query = $this->db->query('SELECT c.customer_id FROM '.DB_PREFIX."customer c WHERE parent = '".(int) $customer_id."'");
            $sub_users = $sub_users_query->rows;
            $s_users = array_column($sub_users, 'customer_id');

            array_push($s_users, $customer_id);
            $sub_users_od = implode(',', $s_users);
            $query = $this->db->query('SELECT SUM(value) AS total,  CONCAT(MONTHNAME('.DB_PREFIX."order.date_added), ' ', YEAR(".DB_PREFIX.'order.date_added)) AS month, YEAR('.DB_PREFIX.'order.date_added) AS year, DATE('.DB_PREFIX.'order.date_added) AS date  FROM `'.DB_PREFIX.'order` LEFT JOIN '.DB_PREFIX.'order_total on('.DB_PREFIX.'order.order_id = '.DB_PREFIX.'order_total.order_id)    WHERE   DATE('.DB_PREFIX."order.date_added) BETWEEN '".$this->db->escape($date_start)."' AND '".$this->db->escape($date_end)."'AND ".DB_PREFIX."order_total.code='sub_total'  AND  ".DB_PREFIX.'order.customer_id IN ('.$sub_users_od.') GROUP BY '.$group.'('.DB_PREFIX.'order.date_added) ORDER BY '.DB_PREFIX.'order.date_added DESC');
        // echo "SELECT SUM(value) AS total,  CONCAT(MONTHNAME(".DB_PREFIX."order.date_added), ' ', YEAR(".DB_PREFIX."order.date_added)) AS month, YEAR(".DB_PREFIX."order.date_added) AS year, DATE(".DB_PREFIX."order.date_added) AS date  FROM `" . DB_PREFIX . "order` LEFT JOIN ".DB_PREFIX."order_total on(".DB_PREFIX."order.order_id = ".DB_PREFIX."order_total.order_id)    WHERE   DATE(".DB_PREFIX."order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'AND ".DB_PREFIX."order_total.code='sub_total'  AND  ".DB_PREFIX."order.customer_id IN (".$sub_users_od.") GROUP BY ". $group ."(".DB_PREFIX."order.date_added) ORDER BY ".DB_PREFIX."order.date_added DESC";
        } else {
            //HOUR(".DB_PREFIX."order.date_added) AS hour,

            $query = $this->db->query('SELECT SUM(value) AS total,  CONCAT(MONTHNAME('.DB_PREFIX."order.date_added), ' ', YEAR(".DB_PREFIX.'order.date_added)) AS month, YEAR('.DB_PREFIX.'order.date_added) AS year, DATE('.DB_PREFIX.'order.date_added) AS date  FROM `'.DB_PREFIX.'order` LEFT JOIN '.DB_PREFIX.'order_total on('.DB_PREFIX.'order.order_id = '.DB_PREFIX.'order_total.order_id)    WHERE   DATE('.DB_PREFIX."order.date_added) BETWEEN '".$this->db->escape($date_start)."' AND '".$this->db->escape($date_end)."'AND ".DB_PREFIX."order_total.code='sub_total'  AND  ".DB_PREFIX."order.customer_id='".$this->db->escape($Selectedcustomer_id)."' GROUP BY ".$group.'('.DB_PREFIX.'order.date_added) ORDER BY '.DB_PREFIX.'order.date_added DESC');
            // echo "SELECT SUM(value) AS total,  CONCAT(MONTHNAME(".DB_PREFIX."order.date_added), ' ', YEAR(".DB_PREFIX."order.date_added)) AS month, YEAR(".DB_PREFIX."order.date_added) AS year, DATE(".DB_PREFIX."order.date_added) AS date  FROM `" . DB_PREFIX . "order` LEFT JOIN ".DB_PREFIX."order_total on(".DB_PREFIX."order.order_id = ".DB_PREFIX."order_total.order_id)    WHERE   DATE(".DB_PREFIX."order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'AND ".DB_PREFIX."order_total.code='sub_total'  AND  ".DB_PREFIX."order.customer_id='" . $this->db->escape($Selectedcustomer_id) . "' GROUP BY ". $group ."(".DB_PREFIX."order.date_added) ORDER BY ".DB_PREFIX."order.date_added DESC";
        }

        return $query;
    }

    public function getTotalValueOfBasket($Selectedcustomer_id, $date_start, $date_end, $customer_id)
    {
        // $s_users = array();
        // $sub_users_query = $this->db->query("SELECT c.customer_id FROM " . DB_PREFIX . "customer c WHERE parent = '" . (int) $customer_id . "'");
        // $sub_users = $sub_users_query->rows;
        // $s_users = array_column($sub_users, 'customer_id');

        // array_push($s_users, $customer_id);
        // $sub_users_od = implode(",", $s_users);

        if (-1 == $Selectedcustomer_id) {
            $s_users = [];
            $sub_users_query = $this->db->query('SELECT c.customer_id FROM '.DB_PREFIX."customer c WHERE parent = '".(int) $customer_id."'");
            $sub_users = $sub_users_query->rows;
            $s_users = array_column($sub_users, 'customer_id');

            array_push($s_users, $customer_id);
            $sub_users_od = implode(',', $s_users);
            $query = $this->db->query('SELECT SUM(value) AS total FROM `'.DB_PREFIX.'order` LEFT JOIN '.DB_PREFIX.'order_total on('.DB_PREFIX.'order.order_id = '.DB_PREFIX.'order_total.order_id) WHERE  DATE('.DB_PREFIX."order.date_added) BETWEEN '".$this->db->escape($date_start)."' AND '".$this->db->escape($date_end)."'AND ".DB_PREFIX."order_total.code='sub_total' AND  ".DB_PREFIX.'order.customer_id IN ('.$sub_users_od.')');
        // echo "SELECT SUM(value) AS total FROM `" . DB_PREFIX . "order` LEFT JOIN ".DB_PREFIX."order_total on(".DB_PREFIX."order.order_id = ".DB_PREFIX."order_total.order_id) WHERE  DATE(".DB_PREFIX."order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'AND ".DB_PREFIX."order_total.code='sub_total' AND  ".DB_PREFIX."order.customer_id IN (".$sub_users_od.")";
        } else {
            $query = $this->db->query('SELECT SUM(value) AS total FROM `'.DB_PREFIX.'order` LEFT JOIN '.DB_PREFIX.'order_total on('.DB_PREFIX.'order.order_id = '.DB_PREFIX.'order_total.order_id) WHERE  DATE('.DB_PREFIX."order.date_added) BETWEEN '".$this->db->escape($date_start)."' AND '".$this->db->escape($date_end)."'AND ".DB_PREFIX."order_total.code='sub_total' AND  ".DB_PREFIX."order.customer_id='".$this->db->escape($Selectedcustomer_id)."'");
            //   echo "SELECT SUM(value) AS total FROM `" . DB_PREFIX . "order` LEFT JOIN ".DB_PREFIX."order_total on(".DB_PREFIX."order.order_id = ".DB_PREFIX."order_total.order_id) WHERE  DATE(".DB_PREFIX."order.date_added) BETWEEN '" . $this->db->escape($date_start) . "' AND '" . $this->db->escape($date_end) . "'AND ".DB_PREFIX."order_total.code='sub_total' AND  ".DB_PREFIX."order.customer_id='" . $this->db->escape($Selectedcustomer_id) . "'";
        }
        //  "SELECT COUNT(*) AS total,SUM(value) as value  FROM `" . DB_PREFIX . "order` LEFT JOIN ".DB_PREFIX."order_total on(".DB_PREFIX."order.order_id = ".DB_PREFIX."order_total.order_id) WHERE order_status_id IN " . $complete_status_ids . " AND DATE(".DB_PREFIX."order.date_modified) BETWEEN '" . $this->db->escape($date_start) ."' AND '" . $this->db->escape($date_end) . "' AND ".DB_PREFIX."order_total.code='sub_total'")

        return $query->row;
    }

    //     $query = $this->db->query('SELECT SUM(value) AS total FROM `'.DB_PREFIX.'order` LEFT JOIN '.DB_PREFIX.'order_total on('.DB_PREFIX.'order.order_id = '.DB_PREFIX.'order_total.order_id) WHERE  DATE('.DB_PREFIX."order.date_added) BETWEEN '".$this->db->escape($date_start)."' AND '".$this->db->escape($date_end)."'AND ".DB_PREFIX."order_total.code='sub_total' AND  ".DB_PREFIX."order.customer_id='".$this->db->escape($customer_id)."'");
    //     //  "SELECT COUNT(*) AS total,SUM(value) as value  FROM `" . DB_PREFIX . "order` LEFT JOIN ".DB_PREFIX."order_total on(".DB_PREFIX."order.order_id = ".DB_PREFIX."order_total.order_id) WHERE order_status_id IN " . $complete_status_ids . " AND DATE(".DB_PREFIX."order.date_modified) BETWEEN '" . $this->db->escape($date_start) ."' AND '" . $this->db->escape($date_end) . "' AND ".DB_PREFIX."order_total.code='sub_total'")

    //     return $query->row;
    // }
}
