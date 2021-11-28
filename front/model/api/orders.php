<?php

class ModelApiOrders extends Model
{
    public function getOrders($data = [])
    {
        $sql = "SELECT st.delivery_by_owner,st.store_pickup_timeslots,o.order_id,o.firstname,o.lastname, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM ".DB_PREFIX."order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '".(int) $this->config->get('config_language_id')."') AS status, o.shipping_code, o.total, o.store_name , o.delivery_date ,o.delivery_timeslot,o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `".DB_PREFIX.'order` o';

        $sql .= ' INNER JOIN `'.DB_PREFIX.'store` st on st.store_id = o.store_id ';

        //$sql .= ' AND st.vendor_id = "' . $this->session->data['api_id'] . '"';

        /*if(isset($this->session->data['store_id'])) {
            $sql .= ' AND st.store_id = "' . $this->session->data['store_id'] . '"';
        }*/
        //$sql .= ' AND st.store_id = '.$data['store_id'];

        $this->load->model('setting/setting');

        $json['vendor_info'] = $this->model_setting_setting->getUser($this->session->data['api_id']);

        $vendor_group_ids = explode(',', $this->config->get('config_vendor_group_ids'));

        // if(in_array($json['vendor_info']['user_group_id'], $vendor_group_ids)) {
        //     $json['vendor_info']['user_type'] =  'vendor';
        // }else{
        //     $json['vendor_info']['user_type'] =  'admin';
        // }

        if (in_array($json['vendor_info']['user_group_id'], $vendor_group_ids)) {
            //vendor
            $sql .= ' AND st.vendor_id="'.$this->session->data['api_id'].'"';
        }

        $sql .= $this->getExtraConditions($data);

        $sort_data = [
            'o.order_id',
            'customer',
            'status',
            'o.date_added',
            'o.date_modified',
            'o.total',
            'o.delivery_date',
        ];

        //$sql .= " GROUP BY delivery_date";

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY '.$data['sort'];
        } else {
            $sql .= ' ORDER BY o.order_id';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
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

        return $query->rows;
    }

    public function getTotalOrdersApi($data = [])
    {
        $sql = "SELECT o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM ".DB_PREFIX."order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '".(int) $this->config->get('config_language_id')."') AS status, o.shipping_code, o.total, o.store_name , o.delivery_date ,o.delivery_timeslot,o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `".DB_PREFIX.'order` o';

        $sql .= ' INNER JOIN `'.DB_PREFIX.'store` st on st.store_id = o.store_id ';

        //$sql .= ' AND st.vendor_id = "' . $this->session->data['api_id'] . '"';

        /*if(isset($this->session->data['store_id'])) {
            $sql .= ' AND st.store_id = "' . $this->session->data['store_id'] . '"';
        }*/
        //$sql .= ' AND st.store_id = '.$data['store_id'];

        $this->load->model('setting/setting');

        $json['vendor_info'] = $this->model_setting_setting->getUser($this->session->data['api_id']);

        $vendor_group_ids = explode(',', $this->config->get('config_vendor_group_ids'));

        // if(in_array($json['vendor_info']['user_group_id'], $vendor_group_ids)) {
        //     $json['vendor_info']['user_type'] =  'vendor';
        // }else{
        //     $json['vendor_info']['user_type'] =  'admin';
        // }

        if (in_array($json['vendor_info']['user_group_id'], $vendor_group_ids)) {
            //vendor
            $sql .= ' AND st.vendor_id="'.$this->session->data['api_id'].'"';
        }

        $sql .= $this->getExtraConditions($data);

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getOrdersNew($data = []) {
        $sql = "SELECT c.name as city, o.firstname,o.lastname,o.comment,    cust.company_name AS company_name,o.order_id, o.delivery_date, o.delivery_timeslot, o.shipping_method, o.shipping_address, o.payment_method, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status,(SELECT os.color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS color, o.shipping_code, o.order_status_id,o.store_name,  o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified,o.po_number,o.SAP_customer_no,o.SAP_doc_no FROM `" . DB_PREFIX . 'order` o ';
        //$sql = "SELECT c.name as city, o.firstname,o.lastname,o.comment, (SELECT cust.company_name FROM hf7_customer cust WHERE o.customer_id = cust.customer_id ) AS company_name,o.order_id, o.delivery_date, o.delivery_timeslot, o.shipping_method, o.shipping_address, o.payment_method, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status,(SELECT os.color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS color, o.shipping_code, o.order_status_id,o.store_name,  o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified,o.po_number FROM `" . DB_PREFIX . "order` o ";

        $sql .= 'left join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id';
        $sql .= ' LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id) ';
        $sql .= ' LEFT JOIN ' . DB_PREFIX . 'customer cust on (cust.customer_id = o.customer_id) ';

         
        //   echo "<pre>";print_r($data['filter_order_type']);die; 

             
        $sql .= $this->getExtraConditions($data);       
 

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

        if (isset($data['order']) && ('DESC' == $data['order'])) {
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

        //   echo "<pre>";print_r($sql);die;

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalOrdersFilter($data = [])
    {
        $sql = 'SELECT COUNT(*) AS total FROM `'.DB_PREFIX.'order` o ';

        $sql .= 'left join `'.DB_PREFIX.'city` c on c.city_id = o.shipping_city_id ';
        $sql .= 'LEFT JOIN '.DB_PREFIX.'store on('.DB_PREFIX.'store.store_id = o.store_id)';
        if (!empty($data['filter_order_status'])) {
            $implode = [];

            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implode[] = "o.order_status_id = '".(int) $order_status_id."'";
            }

            if ($implode) {
                $sql .= ' WHERE ('.implode(' OR ', $implode).')';
            }
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }

        if ($this->user->isVendor()) {
            $sql .= ' AND vendor_id="'.$this->user->getId().'"';
        }
        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '".$data['filter_city']."%'";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '".(int) $data['filter_order_id']."'";
        }

        if (!empty($data['filter_vendor'])) {
            $sql .= ' AND vendor_id="'.$data['filter_vendor'].'"';
        }

        if (!empty($data['filter_store_name'])) {
            $sql .= " AND o.store_name = '".$data['filter_store_name']."'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%".$this->db->escape($data['filter_customer'])."%'";
        }

        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(o.date_added) = DATE('".$this->db->escape($data['filter_date_added'])."')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(o.date_modified) = DATE('".$this->db->escape($data['filter_date_modified'])."')";
        }

        if (!empty($data['filter_total'])) {
            $sql .= " AND o.total = '".(float) $data['filter_total']."'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getOrdersFilter($data = [])
    {
        $sql = "SELECT c.name as city, o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM ".DB_PREFIX."order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '".(int) $this->config->get('config_language_id')."') AS status, (SELECT os.color FROM ".DB_PREFIX."order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '".(int) $this->config->get('config_language_id')."') AS color, o.shipping_code, o.total, o.currency_code, o.store_name , o.delivery_date ,o.delivery_timeslot,  o.currency_value, o.date_added, o.date_modified FROM `".DB_PREFIX.'order` o ';

        $sql .= 'left join `'.DB_PREFIX.'city` c on c.city_id = o.shipping_city_id';
        $sql .= ' LEFT JOIN '.DB_PREFIX.'store on('.DB_PREFIX.'store.store_id = o.store_id) ';

        if (isset($data['filter_order_status'])) {
            $implode = [];

            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implode[] = "o.order_status_id = '".(int) $order_status_id."'";
            }

            if ($implode) {
                $sql .= ' WHERE ('.implode(' OR ', $implode).')';
            } else {
            }
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }

        /*if($this->user->isVendor()) {

            $sql .= ' AND '.DB_PREFIX.'store.vendor_id="' . $this->user->getId() . '"';
        }*/
        if (2 != $this->session->data['api_id']) {
            $sql .= ' AND '.DB_PREFIX.'store.vendor_id="'.$this->session->data['api_id'].'"';
        }

        //echo "<pre>";print_r($sql);die;
        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '".$data['filter_city']."%'";
        }

        if (!empty($data['filter_order_day'])) {
            if ('today' == $data['filter_order_day']) {
                $delivery_date = date('Y-m-d');
            } else {
                $delivery_date = date('Y-m-d', strtotime('+1 day'));
            }

            //$sql .= " AND DATE(o.delivery_date) = " . $delivery_date;
            $sql .= " AND DATE(o.delivery_date) = DATE('".$this->db->escape($delivery_date)."')";

            //echo "<pre>";print_r($delivery_date);die;
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '".(int) $data['filter_order_id']."'";
        }

        if (!empty($data['store_id'])) {
            $sql .= " AND o.store_id = '".(int) $data['store_id']."'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%".$this->db->escape($data['filter_customer'])."%'";
        }

        if (!empty($data['filter_vendor'])) {
            $sql .= ' AND vendor_id="'.$data['filter_vendor'].'"';
        }

        if (!empty($data['filter_store_name'])) {
            $sql .= " AND o.store_name = '".$data['filter_store_name']."'";
        }

        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(o.date_added) = DATE('".$this->db->escape($data['filter_date_added'])."')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(o.date_modified) = DATE('".$this->db->escape($data['filter_date_modified'])."')";
        }

        if (!empty($data['filter_total'])) {
            $sql .= " AND o.total = '".(float) $data['filter_total']."'";
        }

        $sort_data = [
            'o.order_id',
            'customer',
            'status',
            'o.delivery_timeslot',
            'o.date_added',
            'o.date_modified',
            'o.total',
            'c.name',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY '.$data['sort'];
        } else {
            $sql .= ' ORDER BY o.order_id';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' ASC';
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

            $sql .= ' LIMIT '.(int) $data['start'].','.(int) $data['limit'];
        }

        //echo "<pre>";print_r($sql);die;
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalOrdersFilterApi($data = [])
    {
        $sql = 'SELECT COUNT(*) AS total FROM `'.DB_PREFIX.'order` o ';

        $sql .= 'left join `'.DB_PREFIX.'city` c on c.city_id = o.shipping_city_id ';
        $sql .= 'LEFT JOIN '.DB_PREFIX.'store on('.DB_PREFIX.'store.store_id = o.store_id)';
        if (!empty($data['filter_order_status'])) {
            $implode = [];

            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implode[] = "o.order_status_id = '".(int) $order_status_id."'";
            }

            if ($implode) {
                $sql .= ' WHERE ('.implode(' OR ', $implode).')';
            }
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }

        // if($this->user->isVendor()) {
        //     $sql .= ' AND vendor_id="' . $this->user->getId() . '"';
        // }
        if (2 != $this->session->data['api_id']) {
            $sql .= ' AND vendor_id="'.$this->session->data['api_id'].'"';
        }

        if (!empty($data['store_id'])) {
            $sql .= " AND o.store_id = '".(int) $data['store_id']."'";
        }

        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '".$data['filter_city']."%'";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '".(int) $data['filter_order_id']."'";
        }

        if (!empty($data['filter_vendor'])) {
            $sql .= ' AND vendor_id="'.$data['filter_vendor'].'"';
        }

        if (!empty($data['filter_store_name'])) {
            $sql .= " AND o.store_name = '".$data['filter_store_name']."'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%".$this->db->escape($data['filter_customer'])."%'";
        }

        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(o.date_added) = DATE('".$this->db->escape($data['filter_date_added'])."')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(o.date_modified) = DATE('".$this->db->escape($data['filter_date_modified'])."')";
        }

        if (!empty($data['filter_total'])) {
            $sql .= " AND o.total = '".(float) $data['filter_total']."'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getAdminOrders($data = [])
    {
        $sql = "SELECT o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM ".DB_PREFIX."order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '".(int) $this->config->get('config_language_id')."') AS status, o.shipping_code, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `".DB_PREFIX.'order` o';

        $sql .= ' INNER JOIN `'.DB_PREFIX.'store` st on st.store_id = o.store_id ';

        $sql .= $this->getExtraConditions($data);

        $sort_data = [
            'o.order_id',
            'customer',
            'status',
            'o.date_added',
            'o.date_modified',
            'o.total',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY '.$data['sort'];
        } else {
            $sql .= ' ORDER BY o.order_id';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
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

            $sql .= ' LIMIT '.(int) $data['start'].','.(int) $data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotals($data = [])
    {
        $sql = 'SELECT COUNT(*) AS number, SUM(o.total) AS price FROM `'.DB_PREFIX.'order` o';

        $sql .= ' INNER JOIN `'.DB_PREFIX.'store` st on st.store_id = o.store_id ';

        $sql .= $this->getExtraConditions($data);

        //echo "<pre>";print_r($sql);die;
        $query = $this->db->query($sql);

        return $query->row;
    }

    public function getAdminTotals($data = [])
    {
        $sql = 'SELECT COUNT(*) AS number, SUM(o.total) AS price FROM `'.DB_PREFIX.'order` o';

        $sql .= $this->getAdminExtraConditions($data);

        //echo "<pre>";print_r($sql);die;
        $query = $this->db->query($sql);

        return $query->row;
    }

    public function getStatuses()
    {
        $query = $this->db->query('SELECT order_status_id, name FROM '.DB_PREFIX."order_status WHERE language_id = '".(int) $this->config->get('config_language_id')."' ORDER BY name");

        return $query->rows;
    }

    public function getOrderStatusesById($order_status_ids)
    {
        //echo "<pre>";print_r($order_status_ids);die;
        $query = $this->db->query('SELECT order_status_id, name FROM '.DB_PREFIX."order_status WHERE language_id = '".(int) $this->config->get('config_language_id')."' and order_status_id in (".$order_status_ids.') ORDER BY name');

        return $query->rows;
    }

    public function getOrderProducts($order_id)
    {
        /* $sql = "SELECT
                     op.product_id,
                     op.name,
                     op.quantity,
                     (op.price+op.tax) AS price,
                     op.model,
                     op.order_product_id,
                     p.image,
                     o.currency_code,
                     o.currency_value,
                     p.status,
                     p.sku,
                     p.upc,
                     p.ean,
                     p.jan,
                     p.isbn,
                     p.manufacturer_id,
                     p.weight,
                     p.length,
                     p.width,
                     p.height,
                 FROM `" . DB_PREFIX . "order_product` AS op
                 LEFT JOIN `" . DB_PREFIX . "order` o ON op.order_id = o.order_id
                 LEFT JOIN `" . DB_PREFIX . "product` AS p ON op.product_id = p.product_id
                 WHERE op.order_id = '" . $id . "'";

//                print_r($sql);
         $query = $this->db->query($sql);
         return $query->rows;
         */

        $query = $this->db->query('SELECT a.*,b.image as image
        FROM `'.DB_PREFIX.'order_product` a,`'.DB_PREFIX.'product` b,`'.DB_PREFIX."product_to_store` c
        WHERE b.product_id=c.product_id
        AND a.product_id=c.product_store_id
        AND a.order_id='".$order_id."'");

        return $query->rows;
    }

    public function getOrderHistories($order_id)
    {
        $query = $this->db->query('SELECT oh.order_history_id, oh.order_status_id, os.name AS order_status_name, oh.comment, oh.notify, date_added FROM '.DB_PREFIX.'order_history oh LEFT JOIN '.DB_PREFIX."order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '".(int) $order_id."' AND os.language_id = '".(int) $this->config->get('config_language_id')."' ORDER BY oh.date_added");

        return $query->rows;
    }

    private function getExtraConditions($data)
    {
        $sql = '';

        $implode = [];

        if (isset($data['status']) && !empty($data['status'])) {
            $implode2 = [];

            $order_statuses = explode(',', $data['status']);

            foreach ($order_statuses as $order_status_id) {
                $implode2[] = "o.order_status_id = '".(int) $order_status_id."'";
            }

            if ($implode2) {
                $implode[] = '('.implode(' OR ', $implode2).')';
            } else {
                $implode[] = "o.order_status_id > '0'";
            }
        } else {
            $implode[] = "o.order_status_id > '0'";
        }

        if (!empty($data['search'])) {
            //$implode[] = "CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['search']) . "%'";
            $implode[] = "o.order_id LIKE '%".$this->db->escape($data['search'])."%'";
        }

        if (!empty($data['customer'])) {
            $implode[] = "o.customer_id = '".(float) $data['customer']."'";
        }

        if (!empty($data['date_from'])) {
            $implode[] = "o.date_added >= '".$this->db->escape($data['date_from'])." 00:00:00'";
        }

        if (!empty($data['date_to'])) {
            $implode[] = "o.date_added <= '".$this->db->escape($data['date_to'])." 23:59:59'";
        }


        if (!empty($data['delivery_date_from'])) {
            $implode[] = "o.delivery_date >= '".$this->db->escape($data['delivery_date_from'])." 00:00:00'";
        }

        if (!empty($data['delivery_date_to'])) {
            $implode[] = "o.delivery_date <= '".$this->db->escape($data['delivery_date_to'])." 23:59:59'";
        }


        if (!empty($data['delivery_date'])) {
            $implode[] = "o.delivery_date = '".$this->db->escape($data['delivery_date'])."'";
        }

        if (!empty($data['total'])) {
            $implode[] = "o.total = '".(float) $data['total']."'";
        }

        if (!empty($data['store_id'])) {
            $implode[] = "o.store_id = '".$data['store_id']."'";
        }

        if (!empty($data['vendor_id'])) {
            $implode[] = "st.vendor_id = '".$data['vendor_id']."'";
        }

        if (!empty($data['filter_pickup'])) {
            $implode[] = "st.store_pickup_timeslots = '".$data['filter_pickup']."'";
        }

        if ($implode) {
            $sql .= ' WHERE '.implode(' AND ', $implode);
        }
        //   echo  $sql;

        return $sql;
    }

    private function getAdminExtraConditions($data)
    {
        $sql = '';

        $implode = [];

        if (isset($data['status'])) {
            $implode2 = [];

            $order_statuses = explode(',', $data['status']);

            foreach ($order_statuses as $order_status_id) {
                $implode2[] = "o.order_status_id = '".(int) $order_status_id."'";
            }

            if ($implode2) {
                $implode[] = '('.implode(' OR ', $implode2).')';
            } else {
                $implode[] = "o.order_status_id > '0'";
            }
        } else {
            $implode[] = "o.order_status_id > '0'";
        }

        if (!empty($data['search'])) {
            $implode[] = "CONCAT(o.firstname, ' ', o.lastname) LIKE '%".$this->db->escape($data['search'])."%'";
        }

        if (!empty($data['customer'])) {
            $implode[] = "o.customer_id = '".(float) $data['customer']."'";
        }

        if (!empty($data['date_from'])) {
            $implode[] = "o.date_added >= '".$this->db->escape($data['date_from'])." 00:00:00'";
        }

        if (!empty($data['date_to'])) {
            $implode[] = "o.date_added <= '".$this->db->escape($data['date_to'])." 23:59:59'";
        }

        if (!empty($data['total'])) {
            $implode[] = "o.total = '".(float) $data['total']."'";
        }

        if (!empty($data['store_id'])) {
            $implode[] = "o.store_id = '".$data['store_id']."'";
        }

        if (!empty($data['vendor_id'])) {
            $implode[] = "st.vendor_id = '".$data['vendor_id']."'";
        }

        if ($implode) {
            $sql .= ' WHERE '.implode(' AND ', $implode);
        }

        return $sql;
    }
}
