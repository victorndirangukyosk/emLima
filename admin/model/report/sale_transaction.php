<?php

class ModelReportSaleTransaction extends Model
{
    public function getShopper($shopper_id)
    {
        $query = $this->db->query('select CONCAT(firstname," ",lastname) as name from `'.DB_PREFIX.'` WHERE user_id="'.$shopper_id.'"');

        if ($query->num_rows) {
            return $query->row['name'];
        }
    }

    public function getTopCategories()
    {
        $sql = 'select d.name, c.category_id from oc_category c inner join '.DB_PREFIX.'category_description d on d.category_id = c.category_id WHERE c.parent_id=0 AND d.language_id="'.$this->config->get('config_language_id').'"';
        $rows = $this->db->query($sql)->rows;

        $result = [];
        foreach ($rows as $row) {
            $result[$row['category_id']] = $row['name'];
        }

        return $result;
    }

    public function getProductDataByStoreFilter($filter_name, $store_id)
    {
        //$store_id = (int)$this->session->data['config_store_id'];
        $language_id = (int) $this->config->get('config_language_id');

        $limit = 5;
        $offset = 0;

        $this->db->select('product_description.*,product_to_store.*,product.unit', false);
        $this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
        $this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');
        $this->db->join('product_to_category', 'product_to_category.product_id = product_to_store.product_id', 'left');

        if (!empty($filter_name)) {
            $this->db->like('product_description.name', $this->db->escape($filter_name), 'both');
        }

        /*if ( $data['start'] < 0 ) {
            $data['start'] = 0;
            $offset = $data['start'];
        }else{
            $offset = $data['start'];
        }
        if ( $data['limit'] < 1 ) {
            $data['limit'] = 20;
            $limit = $data['limit'];

        }else{
            $limit = $data['limit'];
        }
        $sort_data = array(
            'product_description.name',
            'product.model',
            'product_to_store.quantity',
            'product_to_store.price',
            'product.sort_order',
            'product.date_added'
        );
        if ( isset( $data['sort'] ) && in_array( $data['sort'], $sort_data ) ) {
            if ( $data['sort'] == 'product_description.name' || $data['sort'] == 'product.model' ) {
                $this->db->order_by($data['sort'], 'asc');
            }else {
                $this->db->order_by($data['sort'], 'asc');
            }
        } else {
            $this->db->order_by('product.sort_order', 'asc');
        }   */
        $this->db->group_by('product_to_store.product_store_id');
        $this->db->where('product_to_store.store_id', $store_id);
        $this->db->where('product_to_store.status', 1);
        $this->db->where('product_description.language_id', $language_id);
        $this->db->where('product.status', 1);
        $ret = $this->db->get('product_to_store', $limit)->rows;
        //$ret = $this->db->get('product_to_store')->rows;
        // echo $this->db->last_query();die;
        return $ret;
    }

    public function getOrdersFilter($data = [])
    {
        $sql = "SELECT c.name as city, o.order_id, o.shipping_method, o.payment_method, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM ".DB_PREFIX."order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '".(int) $this->config->get('config_language_id')."') AS status, (SELECT os.color FROM ".DB_PREFIX."order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '".(int) $this->config->get('config_language_id')."') AS color, o.shipping_code, o.total, o.currency_code, o.store_name , o.delivery_date ,o.delivery_timeslot,  o.currency_value, o.date_added, o.date_modified FROM `".DB_PREFIX.'order` o ';

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

        if ($this->user->isVendor()) {
            $sql .= ' AND '.DB_PREFIX.'store.vendor_id="'.$this->user->getId().'"';
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
            'delivery_timeslot',
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

    public function getSellingByTopCategory($category_id)
    {
        $sql = 'select sum(op.total) as total from '.DB_PREFIX.'order_product op';
        $sql .= ' inner join '.DB_PREFIX.'order o on o.order_id = op.order_id';
        $sql .= ' where op.category_id = "'.$category_id.'"';
        // o.order_status_id = '.$this->config->get('config_complete_status_id');$sql .= ' AND
        return $this->db->query($sql)->row['total'];
    }

    public function getVendorTotalSales()
    {
        $query = $this->db->query('SELECT SUM(total) AS total FROM `'.DB_PREFIX."vendor_order` WHERE payment_status = '1' and vendor_id='".$this->user->getId()."'");

        return $query->row['total'];
    }

    public function getVendorTotalSalesByYear($year)
    {
        $query = $this->db->query('SELECT SUM(total) AS total FROM `'.DB_PREFIX."vendor_order` WHERE payment_status = '1' AND YEAR(date_added) = '".(int) $year."'");

        return $query->row['total'];
    }

    public function getVendorTotal($order_id)
    {
        /*
          key
          - total
          - total_data
         */
        $result = [];
        $sub_orders = $this->db->query('select * from '.DB_PREFIX.'vendor_order where order_id='.$order_id)->rows;

        foreach ($sub_orders as $sub_order) {
            $result[$sub_order['store_id']]['total'] = $sub_order['total'];
            $result[$sub_order['store_id']]['total_data'] = $this->db->query('select * from '.DB_PREFIX.'vendor_order_total where store_id="'.$sub_order['store_id'].'" AND order_id="'.$order_id.'"')->rows;
        }

        return $result;
    }

    public function get_commision($vendor_id)
    {
        $row = $this->db->query('select commision, free_from, free_to from '.DB_PREFIX.'user where user_id="'.$vendor_id.'"')->row;
        if (time() >= strtotime($row['free_from']) && time() <= strtotime($row['free_to'])) {
            return 0; //in free trial month
        } else {
            return $row['commision'];
        }
    }

    public function getTotalCommisionByVendor($user_id)
    {
        $sql = 'SELECT sum(commision * vo.total / 100) as result from '.DB_PREFIX.'order vo';
        $sql .= ' left join '.DB_PREFIX.'store st on st.store_id = vo.order_id';
        $sql .= " WHERE vo.order_status_id='".$this->config->get('config_complete_status_id')."'";
        $sub_query = 'select order_id from '.DB_PREFIX."order_product where vendor_id='".$user_id."' group by order_id";
        $sql .= ' AND vo.order_id IN ('.$sub_query.')';
        $sql .= " AND st.vendor_id='".$user_id."'";

        return $this->currency->format($this->db->query($sql)->row['result']);
    }

    public function getTotalSellingByVendor($user_id)
    {
        $sql = 'SELECT sum(op.total) as result from '.DB_PREFIX.'order_product op';
        $sql .= ' inner join '.DB_PREFIX.'order o on o.order_id = op.order_id';
        $sql .= " where op.vendor_id='".$user_id."'";
        $sql .= " AND o.order_status_id='".$this->config->get('config_complete_status_id')."'";

        return $this->currency->format($this->db->query($sql)->row['result']);
    }

    public function getTotalOrdersByVendor($user_id)
    {
        $row = $this->db->query('select count(order_id) as total from '.DB_PREFIX."order_product where vendor_id='".$user_id."' group by order_id")->row;
        if ($row) {
            return $row['total'];
        } else {
            return 0;
        }
    }

    public function getStatus($status_id)
    {
        $sql = 'select * from '.DB_PREFIX.'order_status WHERE order_status_id="'.$status_id.'" AND language_id="'.$this->config->get('config_language_id').'"';

        $row = $this->db->query($sql)->row;
        if ($row) {
            return $row['name'];
        }
    }

    public function getOrderQuestions($order_id)
    {
        $sql = 'select * from '.DB_PREFIX.'order_questions WHERE order_id="'.$order_id.'"';

        return  $this->db->query($sql)->rows;
    }

    public function getStatusNameById($store_id)
    {
        $row = $this->db->query('select name from '.DB_PREFIX.'order_status WHERE language_id= '.$this->config->get('config_language_id').' AND order_status_id = '.$store_id)->row;
        if ($row) {
            return $row['name'];
        }
    }

    public function getVendorId($store_id)
    {
        return $this->db->query('select vendor_id from `'.DB_PREFIX.'store` WHERE store_id="'.$store_id.'"')->row['vendor_id'];
    }

    public function getOrderData($order_id)
    {
        $order_query = $this->db->query("SELECT o.*,  CONCAT(c.firstname, ' ', c.lastname) AS customer ,c.customer_id FROM `".DB_PREFIX.'order` o   Left Join  `'.DB_PREFIX."customer` c on  c.customer_id = o.customer_id WHERE o.order_id = '".(int) $order_id."'");
        //echo $this->db->last_query();die;

        if ($order_query->num_rows) {
            return [
                'order_id' => $order_query->row['order_id'],
                'invoice_no' => $order_query->row['invoice_no'],
                'invoice_prefix' => $order_query->row['invoice_prefix'],
                'customer_id' => $order_query->row['customer_id'],
                'invoice_sufix' => $order_query->row['invoice_sufix'],
            ];
        } else {
            return;
        }
    }

    public function getOrder($order_id)
    {
        $order_query = $this->db->query("SELECT o.*, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM ".DB_PREFIX.'customer c WHERE c.customer_id = o.customer_id) AS customer FROM `'.DB_PREFIX."order` o WHERE o.order_id = '".(int) $order_id."'");

        // echo $this->db->last_query();die;

        if ($order_query->num_rows) {
            $reward = 0;

            $order_product_query = $this->db->query('SELECT * FROM '.DB_PREFIX."order_product WHERE order_id = '".(int) $order_id."'");

            foreach ($order_product_query->rows as $product) {
                $reward += $product['reward'];
            }

            if ($order_query->row['affiliate_id']) {
                $affiliate_id = $order_query->row['affiliate_id'];
            } else {
                $affiliate_id = 0;
            }

            $this->load->model('localisation/city');

            $shipping_city_info = $this->model_localisation_city->getCity($order_query->row['shipping_city_id']);

            if ($shipping_city_info) {
                $shipping_city = $shipping_city_info['name'];
            } else {
                $shipping_city = '';
            }

            $this->load->model('localisation/language');

            $language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);

            if ($language_info) {
                $language_code = $language_info['code'];
                $language_directory = $language_info['directory'];
            } else {
                $language_code = '';
                $language_directory = '';
            }

            return [
                'order_id' => $order_query->row['order_id'],
                'invoice_no' => $order_query->row['invoice_no'],
                'invoice_prefix' => $order_query->row['invoice_prefix'],
                'store_id' => $order_query->row['store_id'],
                'store_name' => $order_query->row['store_name'],
                'store_url' => $order_query->row['store_url'],
                'customer_id' => $order_query->row['customer_id'],
                'customer' => $order_query->row['customer'],
                'customer_group_id' => $order_query->row['customer_group_id'],
                'firstname' => $order_query->row['firstname'],
                'lastname' => $order_query->row['lastname'],
                'email' => $order_query->row['email'],
                'telephone' => $order_query->row['telephone'],
                'fax' => $order_query->row['fax'],
                'custom_field' => unserialize($order_query->row['custom_field']),
                'payment_method' => $order_query->row['payment_method'],
                'payment_code' => $order_query->row['payment_code'],
                'shipping_name' => $order_query->row['shipping_name'],
                'shipping_city_id' => $order_query->row['shipping_city_id'],
                'shipping_city' => $shipping_city,
                'shipping_address' => $order_query->row['shipping_address'],
                'shipping_contact_no' => $order_query->row['shipping_contact_no'],
                'shipping_method' => $order_query->row['shipping_method'],
                'shipping_code' => $order_query->row['shipping_code'],

                'shipping_flat_number' => $order_query->row['shipping_flat_number'],
                'shipping_building_name' => $order_query->row['shipping_building_name'],
                'shipping_landmark' => $order_query->row['shipping_landmark'],
                'shipping_zipcode' => $order_query->row['shipping_zipcode'],

                'comment' => $order_query->row['comment'],
                'total' => $order_query->row['total'],
                'reward' => $reward,
                'rating' => $order_query->row['rating'],
                'order_status_id' => $order_query->row['order_status_id'],
                'affiliate_id' => $order_query->row['affiliate_id'],
                //'commission' => $order_query->row['commission'],
                'language_id' => $order_query->row['language_id'],
                'language_code' => $language_code,
                'language_directory' => $language_directory,
                'currency_id' => $order_query->row['currency_id'],
                'currency_code' => $order_query->row['currency_code'],
                'currency_value' => $order_query->row['currency_value'],
                'ip' => $order_query->row['ip'],
                'forwarded_ip' => $order_query->row['forwarded_ip'],
                'user_agent' => $order_query->row['user_agent'],
                'accept_language' => $order_query->row['accept_language'],
                'date_added' => $order_query->row['date_added'],
                'date_modified' => $order_query->row['date_modified'],
                'delivery_date' => $order_query->row['delivery_date'],
                'delivery_timeslot' => $order_query->row['delivery_timeslot'],
                'commsion_received' => $order_query->row['commsion_received'],
                'delivery_id' => $order_query->row['delivery_id'],
                'commission' => $order_query->row['commission'],
                'settlement_amount' => $order_query->row['settlement_amount'],

                'latitude' => $order_query->row['latitude'],
                'longitude' => $order_query->row['longitude'],
            ];
        } else {
            return;
        }
    }

    public function getOrders($data = [])
    {
        $sql = "SELECT c.name as city,cus.company_name as company, oti.transaction_id, o.order_id, o.shipping_method, o.payment_method, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM ".DB_PREFIX."order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '".(int) $this->config->get('config_language_id')."') AS status,(SELECT os.color FROM ".DB_PREFIX."order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '".(int) $this->config->get('config_language_id')."') AS color, o.shipping_code, o.order_status_id,o.store_name,  o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified,o.delivery_date FROM `".DB_PREFIX.'order` o ';

        $sql .= 'left join `'.DB_PREFIX.'city` c on c.city_id = o.shipping_city_id';
        $sql .= ' left join `'.DB_PREFIX.'customer` cus on cus.customer_id = o.customer_id';
        $sql .= ' LEFT JOIN '.DB_PREFIX.'store on('.DB_PREFIX.'store.store_id = o.store_id) ';
        $sql .= ' LEFT JOIN '.DB_PREFIX.'order_transaction_id oti on oti.order_id = o.order_id';

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

        if ($this->user->isVendor()) {
            $sql .= ' AND '.DB_PREFIX.'store.vendor_id="'.$this->user->getId().'"';
        }

        // echo "<pre>";print_r($sql);die;
        if (!empty($data['filter_city'])) {
            $sql .= " AND c.name LIKE '".$data['filter_city']."%'";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '".(int) $data['filter_order_id']."'";
        }

        if (!empty($data['filter_transaction_id'])) {
            $sql .= " AND oti.transaction_id = '".$data['filter_transaction_id']."'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%".$this->db->escape($data['filter_customer'])."%'";
        }

        if (!empty($data['filter_company'])) {
            $sql .= " AND cus.company_name LIKE '%".$this->db->escape($data['filter_company'])."%'";
        }

        if (!empty($data['filter_vendor'])) {
            $sql .= ' AND vendor_id="'.$data['filter_vendor'].'"';
        }

        if (!empty($data['filter_store_name'])) {
            $sql .= " AND o.store_name = '".$data['filter_store_name']."'";
        }

        if (!empty($data['filter_payment'])) {
            $sql .= " AND o.payment_method LIKE '%".$data['filter_payment']."%'";
        }

        if (!empty($data['filter_delivery_method'])) {
            $sql .= " AND o.shipping_method LIKE '%".$data['filter_delivery_method']."%'";
        }

        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(o.date_added) >= DATE('".$this->db->escape($data['filter_date_added'])."')";
        }

        if (!empty($data['filter_date_order'])) {
            $sql .= " AND DATE(o.date_added) = DATE('".$this->db->escape($data['filter_date_order'])."')";
        }

        if (!empty($data['filter_date_delivery'])) {
            $sql .= " AND DATE(o.delivery_date) = DATE('".$this->db->escape($data['filter_date_delivery'])."')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(o.date_modified) <= DATE('".$this->db->escape($data['filter_date_modified'])."')";
        }

        if (!empty($data['filter_total'])) {
            $sql .= " AND o.total = '".(float) $data['filter_total']."'";
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
        //  echo "<pre>";print_r($query->rows);die;
        return $query->rows;
    }

    public function getOrderProducts($order_id, $store_id = 0)
    {
        $sql = 'SELECT * FROM '.DB_PREFIX."order_product WHERE order_id = '".(int) $order_id."'";

        if ($store_id) {
            $sql .= " AND store_id='".$store_id."'";
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getRealOrderProducts($order_id, $store_id = 0)
    {
        $sql = 'SELECT * FROM '.DB_PREFIX."real_order_product WHERE order_id = '".(int) $order_id."'";

        if ($store_id) {
            $sql .= " AND store_id='".$store_id."'";
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function deleteOrderProduct($order_id, $product_id)
    {
        $sql = 'DELETE FROM '.DB_PREFIX."real_order_product WHERE order_id = '".(int) $order_id."' and product_id = '".(int) $product_id."'";

        $query = $this->db->query($sql);
    }

    public function getOrderProductsIds($order_id)
    {
        $sql = 'SELECT product_id FROM '.DB_PREFIX."real_order_product WHERE order_id = '".(int) $order_id."'";

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getOrderTransactionId($order_id)
    {
        $sql = 'SELECT transaction_id FROM '.DB_PREFIX."order_transaction_id WHERE order_id = '".(int) $order_id."'";

        $query = $this->db->query($sql);

        return $query->row;
    }

    public function deleteOrderTotal($order_id)
    {
        $sql = 'DELETE FROM '.DB_PREFIX."order_total WHERE order_id = '".(int) $order_id."'";

        $query = $this->db->query($sql);
    }

    public function insertOrderTransactionId($order_id, $transaction_id)
    {
        $sql = 'DELETE FROM '.DB_PREFIX."order_transaction_id WHERE order_id = '".(int) $order_id."'";

        $query = $this->db->query($sql);

        $sql = 'INSERT into '.DB_PREFIX."order_transaction_id SET order_id = '".$order_id."', transaction_id = '".$transaction_id."'";

        $query = $this->db->query($sql);
    }

    public function insertOrderTotal($order_id, $total)
    {
        $sql = 'INSERT into '.DB_PREFIX."order_total SET value = '".$total['value']."', order_id = '".$order_id."', title = '".$total['title']."', sort_order = '".$total['sort']."', code = '".$total['code']."'";

        $query = $this->db->query($sql);
    }

    public function insertOrderSubTotalAndTotal($order_id, $sub_total, $total, $sort_order)
    {
        $sql = 'INSERT into '.DB_PREFIX."order_total SET value = '".$sub_total."', order_id = '".$order_id."', title = 'Sub-Total', code = 'sub_total', sort_order = '1'";

        $query = $this->db->query($sql);

        $sql = 'INSERT into '.DB_PREFIX."order_total SET value = '".$total."', order_id = '".$order_id."', title = 'Total', code = 'total', sort_order = '".$sort_order."'";

        $query = $this->db->query($sql);
    }

    public function hasRealOrderProducts($order_id)
    {
        $sql = 'SELECT * FROM '.DB_PREFIX."real_order_product WHERE order_id = '".(int) $order_id."'";

        $query = $this->db->query($sql);

        if ($query->num_rows) {
            return true;
        }

        return false;
    }

    public function updateOrderProduct($order_id, $product_id, $data)
    {
        $total = $data['price'] * $data['quantity'];

        $sql = 'SELECT * FROM '.DB_PREFIX."real_order_product WHERE order_id = '".(int) $order_id."' and product_id = '".$product_id."'";

        $query = $this->db->query($sql);

        if (!$query->num_rows) {
            $sql = 'INSERT into '.DB_PREFIX."real_order_product SET name = '".$this->db->escape($data['name'])."', quantity = '".$this->db->escape($data['quantity'])."', price = '".$this->db->escape($data['price'])."', unit = '".$this->db->escape($data['unit'])."', order_id = '".$order_id."', product_id = '".$product_id."', total = '".$total."'";

            $query = $this->db->query($sql);
        } else {
            $sql = 'UPDATE '.DB_PREFIX."real_order_product SET name = '".$this->db->escape($data['name'])."', quantity = '".$this->db->escape($data['quantity'])."', price = '".$this->db->escape($data['price'])."', unit = '".$this->db->escape($data['unit'])."', total = '".$total."' WHERE order_id = '".(int) $order_id."' and product_id = '".$product_id."'";

            $query = $this->db->query($sql);
        }
    }

    public function getOrderOption($order_id, $order_option_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."order_option WHERE order_id = '".(int) $order_id."' AND order_option_id = '".(int) $order_option_id."'");

        return $query->row;
    }

    public function getOrderOptions($order_id, $order_product_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."order_option WHERE order_id = '".(int) $order_id."' AND order_product_id = '".(int) $order_product_id."'");

        return $query->rows;
    }

    public function getOrderTotals($order_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."order_total WHERE order_id = '".(int) $order_id."' ORDER BY sort_order");

        return $query->rows;
    }

    public function getTotalOrders($data = [])
    {
        $sql = 'SELECT COUNT(*) AS total FROM `'.DB_PREFIX.'order` o ';

        $sql .= 'left join `'.DB_PREFIX.'city` c on c.city_id = o.shipping_city_id ';
        $sql .= ' left join `'.DB_PREFIX.'customer` cus on cus.customer_id = o.customer_id';
        $sql .= ' LEFT JOIN '.DB_PREFIX.'store on('.DB_PREFIX.'store.store_id = o.store_id)';
        $sql .= ' LEFT JOIN '.DB_PREFIX.'order_transaction_id oti on oti.order_id = o.order_id';

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

        if (!empty($data['filter_transaction_id'])) {
            $sql .= " AND oti.transaction_id = '".$data['filter_transaction_id']."'";
        }

        if (!empty($data['filter_vendor'])) {
            $sql .= ' AND vendor_id="'.$data['filter_vendor'].'"';
        }

        if (!empty($data['filter_store_name'])) {
            $sql .= " AND o.store_name = '".$data['filter_store_name']."'";
        }

        if (!empty($data['filter_payment'])) {
            $sql .= " AND o.payment_method LIKE '%".$data['filter_payment']."%'";
        }

        if (!empty($data['filter_delivery_method'])) {
            $sql .= " AND o.shipping_method LIKE '%".$data['filter_delivery_method']."%'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%".$this->db->escape($data['filter_customer'])."%'";
        }

        if (!empty($data['filter_company'])) {
            $sql .= " AND cus.company_name LIKE '%".$this->db->escape($data['filter_company'])."%'";
        }

        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(o.date_added) >= DATE('".$this->db->escape($data['filter_date_added'])."')";
        }

        if (!empty($data['filter_date_order'])) {
            $sql .= " AND DATE(o.date_added)= DATE('".$this->db->escape($data['filter_date_order'])."')";
        }

        if (!empty($data['filter_date_delivery'])) {
            $sql .= " AND DATE(o.delivery_date) = DATE('".$this->db->escape($data['filter_date_delivery'])."')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(o.date_modified) <= DATE('".$this->db->escape($data['filter_date_modified'])."')";
        }

        if (!empty($data['filter_total'])) {
            $sql .= " AND o.total = '".(float) $data['filter_total']."'";
        }


        // echo "<pre>";print_r($sql);die;
        
        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalOrdersByStoreId($store_id)
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `'.DB_PREFIX."order` WHERE store_id = '".(int) $store_id."'");

        return $query->row['total'];
    }

    public function getTotalOrdersByOrderStatusId($order_status_id)
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `'.DB_PREFIX."order` WHERE order_status_id = '".(int) $order_status_id."' AND order_status_id > '0'");

        return $query->row['total'];
    }

    public function getTotalOrdersByProcessingStatus()
    {
        $implode = [];

        $order_statuses = $this->config->get('config_processing_status');

        foreach ($order_statuses as $order_status_id) {
            $implode[] = "order_status_id = '".(int) $order_status_id."'";
        }

        if ($implode) {
            $query = $this->db->query('SELECT COUNT(*) AS total FROM `'.DB_PREFIX.'order` WHERE '.implode(' OR ', $implode));

            return $query->row['total'];
        } else {
            return 0;
        }
    }

    public function getTotalOrdersByCompleteStatus()
    {
        $implode = [];

        $order_statuses = $this->config->get('config_complete_status');

        foreach ($order_statuses as $order_status_id) {
            $implode[] = "order_status_id = '".(int) $order_status_id."'";
        }

        if ($implode) {
            $query = $this->db->query('SELECT COUNT(*) AS total FROM `'.DB_PREFIX.'order` WHERE '.implode(' OR ', $implode).'');

            return $query->row['total'];
        } else {
            return 0;
        }
    }

    public function getTotalOrdersByLanguageId($language_id)
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `'.DB_PREFIX."order` WHERE language_id = '".(int) $language_id."' AND order_status_id > '0'");

        return $query->row['total'];
    }

    public function getTotalOrdersByCurrencyId($currency_id)
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `'.DB_PREFIX."order` WHERE currency_id = '".(int) $currency_id."' AND order_status_id > '0'");

        return $query->row['total'];
    }

    public function createInvoiceNo($order_id)
    {
        $order_info = $this->getOrderData($order_id);

        if ($order_info && !$order_info['invoice_no']) {
            $query = $this->db->query('SELECT MAX(invoice_no) AS invoice_no FROM `'.DB_PREFIX."order` WHERE invoice_prefix = '".$this->db->escape($order_info['invoice_prefix'])."'");

            if ($query->row['invoice_no']) {
                $invoice_no = $query->row['invoice_no'] + 1;
            } else {
                $invoice_no = 1;
            }
            $invoice_sufix = '-'.$order_info['customer_id'].'-'.$order_id;
            $this->db->query('UPDATE `'.DB_PREFIX."order` SET invoice_no = '".(int) $invoice_no."', invoice_prefix = '".$this->db->escape($order_info['invoice_prefix'])."', invoice_sufix = '".$invoice_sufix."' WHERE order_id = '".(int) $order_id."'");

            return $order_info['invoice_prefix'].$invoice_no.$invoice_sufix;
        }
    }

    public function getOrderHistories($order_id, $start = 0, $limit = 10)
    {
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 10;
        }

        $query = $this->db->query('SELECT oh.date_added, os.name AS status, os.color AS color, oh.comment, oh.notify FROM '.DB_PREFIX.'order_history oh LEFT JOIN '.DB_PREFIX."order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '".(int) $order_id."' AND os.language_id = '".(int) $this->config->get('config_language_id')."' ORDER BY oh.date_added ASC LIMIT ".(int) $start.','.(int) $limit);

        return $query->rows;
    }

    public function getTotalOrderHistories($order_id)
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX."order_history WHERE order_id = '".(int) $order_id."'");

        return $query->row['total'];
    }

    public function getTotalOrderHistoriesByOrderStatusId($order_status_id)
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX."order_history WHERE order_status_id = '".(int) $order_status_id."'");

        return $query->row['total'];
    }

    public function getEmailsByProductsOrdered($products, $start, $end)
    {
        $implode = [];

        foreach ($products as $product_id) {
            $implode[] = "op.product_id = '".(int) $product_id."'";
        }

        $query = $this->db->query('SELECT DISTINCT email FROM `'.DB_PREFIX.'order` o LEFT JOIN '.DB_PREFIX.'order_product op ON (o.order_id = op.order_id) WHERE ('.implode(' OR ', $implode).") AND o.order_status_id <> '0' LIMIT ".(int) $start.','.(int) $end);

        return $query->rows;
    }

    public function getTotalEmailsByProductsOrdered($products)
    {
        $implode = [];

        foreach ($products as $product_id) {
            $implode[] = "op.product_id = '".(int) $product_id."'";
        }

        $query = $this->db->query('SELECT DISTINCT email FROM `'.DB_PREFIX.'order` o LEFT JOIN '.DB_PREFIX.'order_product op ON (o.order_id = op.order_id) WHERE ('.implode(' OR ', $implode).") AND o.order_status_id <> '0'");

        return $query->row['total'];
    }

    public function payment_status($order_id, $status, $store_id)
    {
        $log = new Log('error.log');

        $log->write('order payment distribution fn admin');

        $this->load->model('sale/order');

        /*$this->db->query('update `' . DB_PREFIX . 'order` SET commsion_received="' . $status . '" WHERE store_id="' . $store_id . '" AND order_id="' . $order_id . '"');*/

        $order_info = $this->db->query('select * from '.DB_PREFIX.'order LEFT JOIN '.DB_PREFIX.'store on('.DB_PREFIX.'store.store_id = '.DB_PREFIX.'order.store_id) WHERE '.DB_PREFIX.'order.store_id="'.$store_id.'" AND order_id="'.$order_id.'"')->row;

        $order_sub_total = $order_info['total'];

        $shipping_charges = 0;

        $deduct_from_vendor_shipping = false;

        $order_total_info = $this->db->query('select * from '.DB_PREFIX.'order_total  WHERE order_id="'.$order_id.'" and code="shipping"')->row;

        if (is_array($order_total_info) && !is_null($order_total_info['actual_value'])) {
            $tempCalc = $order_total_info['value'] + 0;
            if (!$tempCalc) {
                $deduct_from_vendor_shipping = true;
            }

            $shipping_charges = $order_total_info['actual_value'];
        }

        //shipping_charges to be sent to delviery system

        $order_total_info = $this->db->query('select * from '.DB_PREFIX.'order_total  WHERE order_id="'.$order_id.'" and code="sub_total"')->row;

        if (is_array($order_total_info)) {
            $order_sub_total = $order_total_info['value'];
        }

        if (isset($order_info['settlement_amount'])) {
            $order_sub_total = $order_info['settlement_amount'];
        }

        //echo "<pre>";print_r($order_sub_total);die;

        //print_r($order_info);die;

        $temp_vendor_commision = 0;
        $temp_vendor_com = 0;

        $store_info = $this->getStoreData($store_id);

        //echo "<pre>";print_r($store_info);die;
        if ('category' == $store_info['commission_type']) {
            $order_products = $this->getOrderProductsAdminCopy($order_id, $store_id);

            //echo "<pre>";print_r($order_products);die;
            foreach ($order_products as $key => $value) {
                //this is product store id $value['product_id']

                $p_to_s_info = $this->db->query('select * from '.DB_PREFIX.'product_to_store  WHERE product_store_id='.$value['product_id'])->row;

                if (count($p_to_s_info) > 0) {
                    $cat_info = [];

                    $cat_infos = $this->db->query('select * from '.DB_PREFIX.'product_to_category  WHERE product_id='.$p_to_s_info['product_id'])->rows;

                    //echo "<pre>";print_r($cat_infos);die;
                    foreach ($cat_infos as $key => $value_tmp) {
                        $cat_temp = $this->db->query('select * from '.DB_PREFIX.'category  WHERE category_id='.$value_tmp['category_id'])->row;

                        if (0 == $cat_temp['parent_id']) {
                            $cat_info = $value_tmp;
                        }
                    }

                    //echo "<pre>";print_r($cat_info);die;

                    if (count($cat_info) > 0) {
                        $cat_id = $cat_info['category_id'];

                        $cat_commission = $this->getStoreCategoryCommision($store_id, $cat_id);

                        //echo "<pre>";print_r($cat_commission);die;

                        if (count($cat_commission) > 0) {
                            $temp_vendor_com += ($value['total'] * $cat_commission['commission'] / 100);
                        } else {
                            // not present so applying stores commission
                            $temp_vendor_com += ($value['total'] * $order_info['commission'] / 100);
                        }
                    } else {
                        $temp_vendor_com += ($value['total'] * $order_info['commission'] / 100);
                    }
                }
            }

            //fixed subtraction at last

            $temp_vendor_com += $order_info['fixed_commission'];

            $vendor_commision = $order_sub_total - $temp_vendor_com;
            $admin_commision = $order_sub_total - $vendor_commision;
        } else {
            $vendor_commision_rate = $order_info['commission'];
            $vendor_commision = $order_sub_total - ($order_sub_total * $vendor_commision_rate / 100);

            //my added //if fixed is set subtract it
            $vendor_commision_fixed = $order_info['fixed_commission'];
            $vendor_commision = $vendor_commision - $vendor_commision_fixed;

            //end

            $shopper_commision = $order_info['shopper_commision'];
            $admin_commision = $order_sub_total - $vendor_commision;
        }

        if ($deduct_from_vendor_shipping) {
            $vendor_commision = $vendor_commision - $shipping_charges;
        }

        $order_total_info_tax = $this->db->query('select * from '.DB_PREFIX.'order_total  WHERE order_id="'.$order_id.'" and code="tax"')->row;

        if (is_array($order_total_info_tax) && isset($order_total_info_tax['value'])) {
            $vendor_commision += $order_total_info_tax['value'];
        }

        $log->write('front payment_status');

        $log->write('vendor_commision'.$vendor_commision);
        $log->write('admin_commision'.$admin_commision);
        $log->write('delivery sytem send'.$shipping_charges);
        $log->write('status'.$status);
        $log->write($order_info);

        $vendorCommision = 0;
        if (1 == $status && 1 == $order_info['commsion_received']) {
            $log->write('vendor_commision if '.$vendor_commision);

            //echo "<pre>";print_r("s");die;
            //$vendorCommision = $vendor_commision;
            $vendorCommision = 0 - $vendor_commision;

            $this->db->query('insert into `'.DB_PREFIX.'vendor_wallet` SET vendor_id="'.$order_info['vendor_id'].'", order_id="'.$order_id.'", description="Order Value : '.$this->currency->format($order_sub_total).'", amount="'.$vendor_commision.'", date_added=NOW()');

            //Admin Waller add
            $this->db->query('insert into `'.DB_PREFIX.'admin_wallet` SET  order_id="'.$order_id.'", description="admin commision", amount="'.$admin_commision.'", date_added=NOW()');

            if ('shopper.shopper' == $order_info['shipping_code']) {
                if ('cod' == $order_info['payment_code']) {
                    //debit order_total - shopper commision
                    $this->db->query('insert into `'.DB_PREFIX.'shopper_wallet` SET shopper_id="'.$order_info['shopper_id'].'", order_id="'.$order_id.'", description="shopper commision", amount="'.($order_sub_total - $shopper_commision).'", date_added=NOW()');
                } else {
                    //credit shopper commision
                    $this->db->query('insert into `'.DB_PREFIX.'shopper_wallet` SET shopper_id="'.$order_info['shopper_id'].'", order_id="'.$order_id.'", description="shopper commision", amount="'.$shopper_commision.'", date_added=NOW()');
                }
            }
        } else {
            $log->write('vendor_commision else'.$vendor_commision);
            //$vendorCommision = 0 -$vendor_commision;
            $vendorCommision = $vendor_commision;
            $this->db->query('insert into `'.DB_PREFIX.'vendor_wallet` SET vendor_id="'.$order_info['vendor_id'].'", order_id="'.$order_id.'", description="Order Value : '.$this->currency->format($order_sub_total).'", amount="'.$vendor_commision.'", date_added=NOW()');

            //Admin Wallet add
            $this->db->query('insert into `'.DB_PREFIX.'admin_wallet` SET  order_id="'.$order_id.'", description="admin commision", amount="'.(0 - $admin_commision).'", date_added=NOW()');

            if ('shopper.shopper' == $order_info['shipping_code']) {
                if ('cod' == $order_info['payment_code']) {
                    //debit order_total - shopper commision
                    $this->db->query('insert into `'.DB_PREFIX.'shopper_wallet` SET shopper_id="'.$order_info['shopper_id'].'", order_id="'.$order_id.'", description="shopper commision", amount="'.(0 - ($order_sub_total - $shopper_commision)).'", date_added=NOW()');
                } else {
                    //credit shopper commision
                    $this->db->query('insert into `'.DB_PREFIX.'shopper_wallet` SET shopper_id="'.$order_info['shopper_id'].'", order_id="'.$order_id.'", description="shopper commision", amount="'.(0 - $shopper_commision).'", date_added=NOW()');
                }
            }
        }

        $vendorData = $this->getVendorDetails($order_info['vendor_id']);

        //echo "<pre>";print_r($vendorData);die;

        if (isset($vendorData['email'])) {
            // 6 merchant mail
            $vendorData['amount'] = $vendorCommision;

            $vendorData['transaction_type'] = 'credited';

            if ($vendorData['amount'] <= 0) {
                $vendorData['transaction_type'] = 'debited';
            }

            $vendorData['amount'] = $this->currency->format($vendorData['amount']);

            $subject = $this->emailtemplate->getSubject('Contact', 'contact_6', $vendorData);
            $message = $this->emailtemplate->getMessage('Contact', 'contact_6', $vendorData);
            //mishramanjari15@gmail.com
            $mail = new mail($this->config->get('config_mail'));
            $mail->setTo($vendorData['email']);
            $mail->setFrom($this->config->get('config_from_email'));
            //$mail->setReplyTo($vendorData['email']);
            $mail->setSender($this->config->get('config_name'));
            $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
            $mail->setHtml(html_entity_decode(strip_tags($message), ENT_QUOTES, 'UTF-8'));
            $mail->send();
        }

        $log = new Log('error.log');
        if ($this->emailtemplate->getNotificationEnabled('Contact', 'contact_6')) {
            $vendorData['amount'] = $vendorCommision;
            $vendorData['transaction_type'] = 'credited';

            if ($vendorData['amount'] <= 0) {
                $vendorData['transaction_type'] = 'debited';
            }

            $vendorData['amount'] = $this->currency->format($vendorData['amount']);

            $log->write('status enabled of wallet mobi noti');
            $mobile_notification_template = $this->emailtemplate->getNotificationMessage('Contact', 'contact_6', $vendorData);

            $log->write($mobile_notification_template);

            $mobile_notification_title = $this->emailtemplate->getNotificationTitle('Contact', 'contact_6', $vendorData);

            $log->write($mobile_notification_title);

            $log->write($vendorData);

            if (isset($vendorData['device_id']) && strlen($vendorData['device_id']) > 0) {
                $log->write('VENDOR MOBILE PUSH NOTIFICATION device id set ADMIN.MODEL.REPORT.SALE TRANSACTION');

                $notification_id = $this->saveVendorNotification($order_info['vendor_id'], $vendorData['device_id'], $order_id, $mobile_notification_template, $mobile_notification_title);

                $sen['notification_id'] = $notification_id;

                $ret = $this->emailtemplate->sendVendorPushNotification($order_info['vendor_id'], $vendorData['device_id'], $order_id, $order_info['store_id'], $mobile_notification_template, $mobile_notification_title, $sen);
            } else {
                $log->write('VENDOR MOBILE PUSH NOTIFICATION device id not set ADMIN.MODEL.REPORT.SALE TRANSACTION');
            }
        }
    }

    public function getReturn($order_id)
    {
        return $this->db->query('select count(*) as total from `'.DB_PREFIX.'return` WHERE order_id="'.$order_id.'"');
    }

    public function getStoreData($store_id)
    {
        return $this->db->query('select * from '.DB_PREFIX.'store where store_id ='.$store_id.'')->row;
    }

    public function isMyOrder($order_id, $store_id)
    {
        return $this->db->query('select * from '.DB_PREFIX.'order WHERE order_id="'.$order_id.'" AND store_id="'.$store_id.'"')->row;
    }

    public function getCreditDetail($transaction_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."vendor_wallet WHERE id = '".(int) $transaction_id."'");

        return $query->row;
    }

    public function isVendorOrder($order_id, $vendor_id)
    {
        return  $this->db->query('select * from '.DB_PREFIX.'order  LEFT JOIN '.DB_PREFIX.'store on('.DB_PREFIX.'store.store_id = '.DB_PREFIX.'order.store_id) WHERE order_id="'.$order_id.'" AND vendor_id = "'.$vendor_id.'"')->row;
    }

    public function getCities()
    {
        return  $this->db->query('select city_id, name from `'.DB_PREFIX.'city` order by sort_order')->rows;
    }

    public function getCitiesLike($like)
    {
        return  $this->db->query('select name, city_id from `'.DB_PREFIX.'city` WHERE name LIKE "'.$like.'%" order by sort_order')->rows;
    }

    public function getCitiesLikeWithLimit($like, $limit)
    {
        return  $this->db->query('select name, city_id from `'.DB_PREFIX.'city` WHERE name LIKE "'.$like.'%" order by sort_order limit '.$limit)->rows;
    }

    public function getTotalFromOrder($city_id)
    {
        return $this->db->query('select count(*) as total from `'.DB_PREFIX.'order` WHERE shipping_city_id="'.$city_id.'"')->row['total'];
    }

    public function getTotalFromAddress($city_id)
    {
        return $this->db->query('select count(*) as total from `'.DB_PREFIX.'address` WHERE city_id="'.$city_id.'"')->row['total'];
    }

    public function getVendorToPackages($vendor_id)
    {
        return $this->db->query('select * from '.DB_PREFIX.'vendor_to_package where vendor_id="'.$vendor_id.'" AND date_end > "'.date('Y-m-d').'" ORDER BY vp DESC')->row;
    }

    public function getStoreDatas($vendor_id)
    {
        return $this->db->query('select * from '.DB_PREFIX.'store where vendor_id="'.$vendor_id.'"')->rows;
    }

    public function getUserDetails($filter_name, $shopper_group_id)
    {
        $sql = 'select CONCAT(firstname," ",lastname) as name, user_id from `'.DB_PREFIX.'user` ';
        $sql .= 'WHERE CONCAT(firstname," ",lastname) LIKE "'.$filter_name.'%" ';
        $sql .= 'AND user_group_id IN ('.$shopper_group_id.')';

        return $this->db->query($sql)->rows;
    }

    public function getVendorUserData($filter_name)
    {
        $sql = 'select user_id, CONCAT(firstname," ",lastname) as name from `'.DB_PREFIX.'user`';
        $sql .= ' WHERE CONCAT(firstname," ",lastname) LIKE "'.$filter_name.'%" AND user_group_id =11 LIMIT 5';

        return $this->db->query($sql)->rows;
    }

    public function getVendorUserDataOnlyFirstName($filter_name)
    {
        $sql = 'select user_id, CONCAT(firstname) as name from `'.DB_PREFIX.'user`';
        $sql .= ' WHERE CONCAT(firstname," ",lastname) LIKE "'.$filter_name.'%" AND user_group_id =11 LIMIT 5';

        return $this->db->query($sql)->rows;
    }

    public function getVendorDetails($vendor_id)
    {
        $sql = 'select *, CONCAT(firstname," ",lastname) as name from `'.DB_PREFIX.'user`';
        $sql .= ' WHERE user_id="'.$vendor_id.'" AND user_group_id =11 LIMIT 1';

        return $this->db->query($sql)->row;
    }

    public function getUserData($filter_name)
    {
        $sql = 'select user_id, CONCAT(firstname," ",lastname) as name from `'.DB_PREFIX.'user`';
        $sql .= ' WHERE CONCAT(firstname," ",lastname) LIKE "'.$filter_name.'%" LIMIT 5';

        return $this->db->query($sql)->rows;
    }

    public function getStoreDetails($q)
    {
        $sql = 'SELECT name, store_id from `'.DB_PREFIX.'store` WHERE name LIKE "%'.$q.'%"';

        if ($this->user->isVendor()) {
            $sql .= " AND vendor_id='".$this->user->getId()."'";
        }

        $sql .= ' LIMIT 5';

        return $this->db->query($sql)->rows;
    }

    public function getStoreGroupDetails($q)
    {
        $sql = 'SELECT name, id from `'.DB_PREFIX.'store_groups` WHERE name LIKE "%'.$q.'%"';

        $sql .= ' LIMIT 5';

        return $this->db->query($sql)->rows;
    }

    public function getStoreNameById($id)
    {
        $sql = 'SELECT name,store_id from `'.DB_PREFIX.'store` WHERE store_id ='.$id;

        return $this->db->query($sql)->row;
    }

    public function getStoreIdByVendorId($vendor_id)
    {
        return $this->db->query('select store_id from `'.DB_PREFIX.'store` WHERE vendor_id="'.$vendor_id.'"')->rows;
    }

    public function getVendorOrder($order_id, $store_id)
    {
        return $this->db->query('select * from '.DB_PREFIX.'vendor_order WHERE order_id="'.$order_id.'" AND store_id="'.$store_id.'"')->row;
    }

    public function updateVendorOrderDeliveryDate($value, $key, $order_id)
    {
        $this->db->query('update '.DB_PREFIX.'vendor_order SET delivery_date="'.date('Y-m-d', strtotime($value)).'" where store_id="'.$key.'" AND order_id = "'.$order_id.'"');
    }

    public function updateVendorOrderDeliveryTime($value, $key, $order_id)
    {
        $this->db->query('update '.DB_PREFIX.'vendor_order set delivery_timeslot="'.$value.'" where store_id="'.$key.'" AND order_id = "'.$order_id.'"');
    }

    public function updateVendorOrderStatus($status, $store_id, $order_id)
    {
        $this->db->query('update `'.DB_PREFIX.'vendor_order` SET order_status_id="'.$status.'" WHERE store_id="'.$store_id.'" AND order_id="'.$order_id.'"');
    }

    public function selectDeliverySlotsFromStore($store_id)
    {
        return $this->db->query('select delivery_time_diff, home_delivery_timeslots from '.DB_PREFIX.'store where store_id = "'.$store_id.'"')->row;
    }

    public function shopper_autocomplete($config_shopper_group_ids, $filter_name)
    {
        $sql = "SELECT CONCAT(firstname,' ',lastname) as name, user_id FROM `".DB_PREFIX.'user` ';
        $sql .= 'WHERE user_group_id IN ('.$config_shopper_group_ids.') ';

        if ($filter_name) {
            $sql .= 'AND CONCAT(firstname," ",lastname) LIKE "'.$filter_name.'%"';
        }

        return $this->db->query($sql)->rows;
    }

    public function settle_payment($order_id, $final_amount)
    {
        $this->db->query('update `'.DB_PREFIX.'order` SET settlement_amount="'.$final_amount.'" WHERE order_id="'.$order_id.'"');
    }

    public function getOrderIugu($order_id)
    {
        $sql = 'select * from `'.DB_PREFIX.'order_iugu`';
        $sql .= ' WHERE order_id="'.$order_id.'" LIMIT 1';

        return $this->db->query($sql)->row;
    }

    public function getOrderIuguCustomer($customer_id)
    {
        $sql = 'select * from `'.DB_PREFIX.'customer_to_customer_iugu`';
        $sql .= ' WHERE customer_id="'.$customer_id.'" LIMIT 1';

        return $this->db->query($sql)->row;
    }

    public function getOrderIuguAndTotal($order_id)
    {
        $sql = 'SELECT    * FROM   `'.DB_PREFIX.'order_iugu` n LEFT OUTER JOIN `'.DB_PREFIX.'order_total`  a ON a.order_id = n.order_id WHERE (a.code = "sub_total" and  a.order_id = "'.$order_id.'")';

        return $this->db->query($sql)->row;
    }

    public function saveVendorNotification($user_id, $deviceId, $order_id, $message, $title)
    {
        $this->db->query('INSERT INTO '.DB_PREFIX."vendor_notifications SET user_id = '".$user_id."', type = 'wallet', purpose_id = '".$order_id."', title = '".$title."', message = '".$message."', status = 'unread', created_at = NOW() , updated_at = NOW()");

        $notificaiton_id = $this->db->getLastId();

        return $notificaiton_id;
    }

    public function getStoreCategoryCommision($store_id, $category_id)
    {
        $sql = 'select * from `'.DB_PREFIX.'store_category_commission`';
        $sql .= ' WHERE store_id="'.$store_id.'" and category_id="'.$category_id.'"  LIMIT 1';

        return $this->db->query($sql)->row;
    }

    public function stripeCharge()
    {
    }
}
