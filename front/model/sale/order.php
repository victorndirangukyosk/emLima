<?php

class ModelSaleOrder extends Model
{
    public function getStatusNameById($store_id)
    {
        $row = $this->db->query('select name from '.DB_PREFIX.'order_status WHERE language_id= '.$this->config->get('config_language_id').' AND order_status_id = '.$store_id)->row;
        if ($row) {
            return $row['name'];
        }
    }

    public function getOrder($order_id)
    {
        $order_query = $this->db->query("SELECT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM ".DB_PREFIX.'customer c WHERE c.customer_id = o.customer_id) AS customer FROM `'.DB_PREFIX."order` o WHERE o.order_id = '".(int) $order_id."'");

        if ($order_query->num_rows) {
            $reward = 0;

            $order_product_query = $this->db->query('SELECT * FROM '.DB_PREFIX."order_product WHERE order_id = '".(int) $order_id."'");

            foreach ($order_product_query->rows as $product) {
                $reward += $product['reward'];
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
                'shipping_address' => $order_query->row['shipping_address'],
                'shipping_contact_no' => $order_query->row['shipping_contact_no'],
                'shipping_method' => $order_query->row['shipping_method'],
                'shipping_code' => $order_query->row['shipping_code'],
                'comment' => $order_query->row['comment'],
                'total' => $order_query->row['total'],
                'reward' => $reward,
                'order_status_id' => $order_query->row['order_status_id'],
                'commission' => $order_query->row['commission'],
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
            ];
        } else {
            return;
        }
    }

    public function getOrders($data = [])
    {
        $sql = "SELECT o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM ".DB_PREFIX."order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '".(int) $this->config->get('config_language_id')."') AS status, o.shipping_code, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `".DB_PREFIX.'order` o';

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

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '".(int) $data['filter_order_id']."'";
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

    public function getOrderProducts($order_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."order_product WHERE order_id = '".(int) $order_id."'");

        return $query->rows;
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

    public function getOrderVouchers($order_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."order_voucher WHERE order_id = '".(int) $order_id."'");

        return $query->rows;
    }

    public function getOrderVoucherByVoucherId($voucher_id)
    {
        $query = $this->db->query('SELECT * FROM `'.DB_PREFIX."order_voucher` WHERE voucher_id = '".(int) $voucher_id."'");

        return $query->row;
    }

    public function getOrderTotals($order_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."order_total WHERE order_id = '".(int) $order_id."' ORDER BY sort_order");

        return $query->rows;
    }

    public function getTotalOrders($data = [])
    {
        $sql = 'SELECT COUNT(*) AS total FROM `'.DB_PREFIX.'order`';

        if (!empty($data['filter_order_status'])) {
            $implode = [];

            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implode[] = "order_status_id = '".(int) $order_status_id."'";
            }

            if ($implode) {
                $sql .= ' WHERE ('.implode(' OR ', $implode).')';
            }
        } else {
            $sql .= " WHERE order_status_id > '0'";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND order_id = '".(int) $data['filter_order_id']."'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(firstname, ' ', lastname) LIKE '%".$this->db->escape($data['filter_customer'])."%'";
        }

        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(date_added) = DATE('".$this->db->escape($data['filter_date_added'])."')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(date_modified) = DATE('".$this->db->escape($data['filter_date_modified'])."')";
        }

        if (!empty($data['filter_total'])) {
            $sql .= " AND total = '".(float) $data['filter_total']."'";
        }

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

            $this->db->query('UPDATE `'.DB_PREFIX."order` SET invoice_no = '".(int) $invoice_no."', invoice_prefix = '".$this->db->escape($order_info['invoice_prefix'])."' WHERE order_id = '".(int) $order_id."'");

            return $order_info['invoice_prefix'].$invoice_no;

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

        $query = $this->db->query('SELECT oh.date_added, os.name AS status, oh.comment, oh.notify FROM '.DB_PREFIX.'order_history oh LEFT JOIN '.DB_PREFIX."order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '".(int) $order_id."' AND os.language_id = '".(int) $this->config->get('config_language_id')."' ORDER BY oh.date_added ASC LIMIT ".(int) $start.','.(int) $limit);

        return $query->rows;
    }

    public function getFullOrderHistoriesByOrderId($order_id)
    {
        $query = $this->db->query('SELECT oh.date_added, os.name AS status, oh.comment, oh.notify FROM '.DB_PREFIX.'order_history oh LEFT JOIN '.DB_PREFIX."order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '".(int) $order_id."' AND os.language_id = '".(int) $this->config->get('config_language_id')."' ORDER BY oh.date_added ASC ");

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

    public function getOrderIugu($order_id)
    {
        $sql = 'select * from `'.DB_PREFIX.'order_iugu`';
        $sql .= ' WHERE order_id="'.$order_id.'" LIMIT 1';

        return $this->db->query($sql)->row;
    }

    public function getOrderTransactionId($order_id)
    {
        $sql = 'SELECT transaction_id FROM '.DB_PREFIX."order_transaction_id WHERE order_id = '".(int) $order_id."'";

        $query = $this->db->query($sql);
        if (isset($query->row)) {
            /*$log = new Log('error.log');
            $log->write('order model');
            $log->write($query->row);
            $log->write('order model');*/
            if(array_key_exists('transaction_id', $query->row)) {
            return $query->row['transaction_id'];
            } else {
            return '';    
            }
        }

        return null;
    }
    
    public function getOrderTransactionDetailsId($order_id) {
        $sql = 'SELECT * FROM '.DB_PREFIX."transaction_details WHERE order_ids = '".(int) $order_id."'";
        $query = $this->db->query($sql);
        return $query->row;
    }

    public function getCustomers($data = [])
    {
        $sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, cgd.name AS customer_group FROM ".DB_PREFIX.'customer c LEFT JOIN '.DB_PREFIX."customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = '".(int) $this->config->get('config_language_id')."'";

        $implode = [];

        if (!empty($data['filter_name'])) {
            if ($this->user->isVendor()) {
                $implode[] = "c.firstname LIKE '%".$this->db->escape($data['filter_name'])."%'";
            } else {
                $implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%".$this->db->escape($data['filter_name'])."%'";
            }
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "c.email LIKE '".$this->db->escape($data['filter_email'])."%'";
        }

        if (!empty($data['filter_telephone'])) {
            $implode[] = "c.telephone LIKE '".$this->db->escape($data['filter_telephone'])."%'";
        }

        if (isset($data['filter_newsletter']) && !is_null($data['filter_newsletter'])) {
            $implode[] = "c.newsletter = '".(int) $data['filter_newsletter']."'";
        }

        if (!empty($data['filter_customer_group_id'])) {
            $implode[] = "c.customer_group_id = '".(int) $data['filter_customer_group_id']."'";
        }

        if (!empty($data['filter_ip'])) {
            $implode[] = "c.ip = '".$this->db->escape($data['filter_ip'])."'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "c.status = '".(int) $data['filter_status']."'";
        }

        if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
            $implode[] = "c.approved = '".(int) $data['filter_approved']."'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(c.date_added) = DATE('".$this->db->escape($data['filter_date_added'])."')";
        }

        if (isset($data['filter_parent']) && !is_null($data['filter_parent'])) {
            $implode[] = "c.parent = '".(int) $data['filter_parent']."'";
        }

        if ($implode) {
            $sql .= ' AND '.implode(' AND ', $implode);
        }

        $sort_data = [
            'name',
            'c.email',
            'customer_group',
            'c.status',
            'c.approved',
            'c.ip',
            'c.date_added',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY '.$data['sort'];
        } else {
            $sql .= ' ORDER BY name';
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

    public function getTotalCustomers($data = [])
    {
        $sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'customer';

        $implode = [];

        if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%".$this->db->escape($data['filter_name'])."%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "email LIKE '".$this->db->escape($data['filter_email'])."%'";
        }

        if (!empty($data['filter_telephone'])) {
            $implode[] = "telephone LIKE '".$this->db->escape($data['filter_telephone'])."%'";
        }

        if (isset($data['filter_newsletter']) && !is_null($data['filter_newsletter'])) {
            $implode[] = "newsletter = '".(int) $data['filter_newsletter']."'";
        }

        if (!empty($data['filter_customer_group_id'])) {
            $implode[] = "customer_group_id = '".(int) $data['filter_customer_group_id']."'";
        }

        if (!empty($data['filter_ip'])) {
            $implode[] = "ip = '".$this->db->escape($data['filter_ip'])."'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "status = '".(int) $data['filter_status']."'";
        }

        if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
            $implode[] = "approved = '".(int) $data['filter_approved']."'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(date_added) = DATE('".$this->db->escape($data['filter_date_added'])."')";
        }

        if (isset($data['filter_parent']) && !is_null($data['filter_parent'])) {
            $implode[] = "parent = '".(int) $data['filter_parent']."'";
        }

        if ($implode) {
            $sql .= ' WHERE '.implode(' AND ', $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
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

    public function getRealOrderProducts($order_id, $store_id = 0) {
        $sql = 'SELECT * FROM ' . DB_PREFIX . "real_order_product WHERE order_id = '" . (int) $order_id . "'";

        if ($store_id) {
            $sql .= " AND store_id='" . $store_id . "'";
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }


    public function getRealOrderProductsItems($order_id, $store_id = 0)
    {
        $qty = 0;

        $sql = 'SELECT sum(quantity) as quantity FROM '.DB_PREFIX."real_order_product WHERE order_id = '".(int) $order_id."'";

        if ($store_id) {
            $sql .= " AND store_id='".$store_id."'";
        }

        $query = $this->db->query($sql)->row;

        if (isset($query['quantity'])) {
            $qty = $query['quantity'];
        }

        return $qty;
    }

    public function getOrderProductsItems($order_id, $store_id = 0)
    {
        $qty = 0;

        $sql = 'SELECT sum(quantity) as quantity FROM '.DB_PREFIX."order_product WHERE order_id = '".(int) $order_id."'";

        if ($store_id) {
            $sql .= " AND store_id='".$store_id."'";
        }

        $query = $this->db->query($sql)->row;

        if (isset($query['quantity'])) {
            $qty = $query['quantity'];
        }

        return $qty;
    }

    public function getStoreData($store_id)
    {
        return $this->db->query('select * from '.DB_PREFIX.'store where store_id ='.$store_id.'')->row;
    }

    public function getOrdersCount()
    {
        $sql = "SELECT  count(o.order_id) as OrdersCount FROM `".DB_PREFIX.'order` o';  
        $query = $this->db->query($sql);
        return $query->row['OrdersCount'];
    }

    public function getFarmersCount()
    {
        $sql = "SELECT  count(f.order_id) as FarmersCount FROM `".DB_PREFIX.'farmer` f';  
        $query = $this->db->query($sql);
        return $query->row['FarmersCount'];
    }

    public function getCustomersCount()
    {
        $sql = "SELECT  count(c.customer_id) as CustomersCount FROM `".DB_PREFIX.'customer` c';  
        $query = $this->db->query($sql);
        return $query->row['CustomersCount'];
    }

    public function addVehicleLatLng($vehicle_number,$latitude,$longitude) {
        $sql = "SELECT  vehicle_number FROM `".DB_PREFIX."amitruck_vehicle` where vehicle_number='" . $this->db->escape($vehicle_number) . "'";  
        $query = $this->db->query($sql);
        if($query->rows>0){
        $this->db->query('UPDATE ' . DB_PREFIX . "amitruck_vehicle SET   latitude = '" . $this->db->escape($latitude) . "', longitude = '" . $this->db->escape($longitude) . "', speed = '" . ($speed) . "', date_added = NOW() where vehicle_number = '". $this->db->escape($vehicle_number)."'");
        }
        else{
        $this->db->query('INSERT INTO ' . DB_PREFIX . "amitruck_vehicle SET vehicle_number = '" . $this->db->escape($vehicle_number) . "', latitude = '" . $this->db->escape($latitude) . "', longitude = '" . $this->db->escape($longitude) . "', speed = '" . ($speed) . "', date_added = NOW()");
        }
        $id = $this->db->getLastId();
        return $id;
    }


    public function getVehicleLatLng($vehicle_number=0) {
       
        
        $sql = "SELECT vehicle_number, latitude,longitude,speed FROM `".DB_PREFIX.'amitruck_vehicle` ';

        if (isset($vehicle_number) && $vehicle_number>0) {            
                $sql .= ' WHERE vehicle_number= '.$vehicle_number;          
        }  

        $query = $this->db->query($sql);

        return $query->rows;
    }


}
